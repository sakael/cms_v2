<?php

namespace App\Controllers;

use Slim\View\Twig as View;
use App\Classes\{Product, EAN, UserActivity, Brand, Attribute, Event};
use DB;
use Respect\Validation\Validator as v;
use Carbon\Carbon as Carbon;
use Slim\Exception\NotFoundException;

class ProductController extends Controller
{
    public function index($request, $response, $args)
    {
        return $this->view->render($response, 'product/all.tpl', ['active_menu' => 'products', 'page_title' => 'Alle artikelen']);
    }

    public function getAll($request, $response, $args)
    {
        if ($request->getParam('active') == 'all') {
            $products = DB::query("
            SELECT id,sku,active,updated_at,contents->>'$." . language . ".title' as title
            FROM product order by id ");
        } elseif ($request->getParam('active') == 'bol') {
            $products = DB::query("
              SELECT product.id,sku,active,updated_at,contents->>'$." . language . ".title' as title
              FROM product,product_shop
              where product_shop.product_id=product.id and product_shop.shop_id=3  order by product.id ");
        } else {
            $products = DB::query("
            SELECT id,sku,active,updated_at,contents->>'$." . language . ".title' as title
            FROM product where active = 0 order by id ");
        }

        $returndata = array(
          'draw' => null,
          'cached' => null,
          'recordsTotal' => count($products),
          'recordsFiltered' => count($products),
          'data' => $products
        );
        return json_encode($returndata);
    }

    public function getLatestChangedProducts()
    {
        $products = DB::queryOneColumn(
            "subject_id",
            "select * from activity_log where subject_type like 'Products%' and task like '%Update%' group by  subject_id order by created_at DESC limit 100 "
        );
        $products = implode("','", $products);
        $products = DB::query("
            SELECT id,sku,active,updated_at,contents->>'$." . language . ".title' as title
            FROM product 
            where id 
            IN ('" . $products . "') ORDER BY field(id,'" . $products . "')");

        $returndata = array(
          'draw' => null,
          'cached' => null,
          'recordsTotal' => count($products),
          'recordsFiltered' => count($products),
          'data' => $products
        );
        return json_encode($returndata);
    }

    public function getAllBol()
    {
        $products = DB::query("
          SELECT *,
          JSON_UNQUOTE(JSON_EXTRACT(contents, '$." . language . ".title')) as title,
          left join
          FROM product order by id ");
    }

    public function getProduct($request, $response, $args)
    {
        // fetch productdata
        $result = Product::getProduct($args['id']);

        if (!$result) {
            throw new NotFoundException($request, $response);
        }
        // attributes
        $attributes = Product::getAttributes($args['id']);

        // categories
        $categories = Product::getCategories($args['id']);

        // webshops
        $shops = Product::getWebshops($args['id']);

        //history
        $history = DB::query("select activity_log.task,activity_log.created_at,users.name,users.lastname from activity_log
        left join users on users.id=user_id
        where subject_type=%s and subject_id=%i and (task like '%Update%' or task like '%Delete%') order by created_at DESC", 'Products', $args['id']);

        //Brands
        $brands = Brand::All();
        return $this->view->render($response, 'product/product.tpl', [
          'product' => $result,
          'attributes' => $attributes,
          'categories' => $categories,
          'shops' => $shops,
          'active_menu' => 'products',
          'brands' => $brands,
          'history' => $history,
          'page_title' => $result['contents']['title'] . ' - ' . $result['id']
        ]);
    }

    public function updateProduct($request, $response, $args)
    {
        $request = $request->getParsedBody();
        $result = Product::handleUpdate($args['id'], $request);
        DB::update('product', array('updated_at' => Carbon::now()->format('Y-m-d H:i:s')), 'id=%i', $args['id']);
        if ($result && $request['type'] == 'contents') {
            return $response->withJson(['status' => 'true', 'msg' => 'De contents is bijgewerkt']);
        }
    }

    public function addingImage($request, $response, $args)
    {
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['file'];

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            Product::handleUpload($args['id'], $uploadedFile);
        }
    }

    public function sortingImages($request, $response, $args)
    {
        $request = $request->getParsedBody();
        if (Product::handleUpdate($args['id'], $request)) {
            return true;
        }
        return false;
    }

    public function getChildren($request, $response, $args)
    {
        $children = DB::query("
                    SELECT pc.id, pbt.name as type, pb.name as brand FROM product_child pc
                    LEFT JOIN product_brand_type pbt ON pbt.id = pc.product_brand_type_id
                    LEFT JOIN product_brand pb ON pb.id = pbt.product_brand_id
                    WHERE product_id=%i", $args['id']);
        return json_encode(array('data' => $children));
    }
    /**
     * getChildrenEan get the eans for this type and product function
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return array json eans
     */
    public function getChildrenEan($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'product_id' => v::notEmpty(),
            'type_id' => v::notEmpty(),
          ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Product data klopt niet !!']);
        }
        $request->getParam('product_id');
        $request->getParam('type_id');
        $result = DB::query(
            'select * from EAN 
            where product_id=%i and type_id=%i',
            $request->getParam('product_id'),
            $request->getParam('type_id')
        );

        foreach ($result as $key => $ean) {
            $variation = DB::queryFirstRow(
                "select variations.name->>'$.nl' as name,sub_variation_id from product_variation 
                left join variations on variations.id = product_variation.variation_id
                where product_variation.id=%i",
                $ean['variation_id']
            );
            if ($variation['sub_variation_id']) {
                $variationSub = DB::queryFirstRow(
                    "select name->>'$.nl' as name from variations
                    where id=%i",
                    $variation['sub_variation_id']
                );
                if ($variationSub) {
                    $result[$key]['varation_sub_name'] = $variationSub['name'];
                }
            } else {
                $result[$key]['varation_sub_name'] = '';
            }
            if ($variation) {
                $result[$key]['varation_name'] = $variation['name'];
            }
        }
        return $response->withJson(['status' => 'true', 'data' => $result]);
    }
    
    /**************************************************************************************************************************************************
     **************************************************************(Price Update)**********************************************************************
     **************************************************************************************************************************************************/
    public function priceUpdate($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
          'productId' => v::notEmpty(),
          'shopId' => v::notEmpty(),
          'prices' => v::notEmpty(),
        ]);

        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Product data klopt niet !!']);
        }

        $shopId = $request->getParam('shopId');

        $results = DB::queryFirstRow("SELECT price FROM product_pricing WHERE product_id=%i", $request->getParam('productId'));
        if (!$results) {
            $prices = array();
        } else {
            $prices = json_decode($results['price'], true);
        }

        foreach ($request->getParam('prices') as $key => $price) {
            $prices[$shopId][$key] = $price;
        }
        if (!$results) {
            $check = DB::insert('product_pricing', array('price' => json_encode($prices), 'product_id' => $request->getParam('productId'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')));
        } else {
            $check = DB::update('product_pricing', array('price' => json_encode($prices), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')), 'product_id=%i', $request->getParam('productId'));
        }
        if ($check) {
            //run event
            $event = new Event();
            $event->productUpdated($request->getParam('productId'), '', 'price');
            UserActivity::Record('Update Price', $request->getParam('productId'), 'Products');
            return $response->withJson(['status' => 'true', 'msg' => 'De prijs van het product is gewijzigd !!']);
        }
        return $response->withJson(['status' => 'false', 'msg' => 'De prijs van het product  is niet gewijzigd !!']);
    }

    /**************************************************************************************************************************************************
     **************************************************************(Measurements Update)***************************************************************
     **************************************************************************************************************************************************/
    public function measurementsUpdate($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
          'productId' => v::notEmpty(),
          'measurements' => v::notEmpty(),
        ]);

        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Product data klopt niet !!']);
        }

        $results = DB::queryFirstRow("SELECT measurements FROM product_measurements WHERE product_id=%i", $request->getParam('productId'));
        if (!$results) {
            $measurements = array();
        } else {
            $measurements = json_decode($results['measurements'], true);
        }

        foreach ($request->getParam('measurements') as $key => $measurement) {
            $measurements[$key] = $measurement;
        }

        if (!$results) {
            $check = DB::insert('product_measurements', array('measurements' => json_encode($measurements), 'product_id' => $request->getParam('productId'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')));
        } else {
            $check = DB::update('product_measurements', array('measurements' => json_encode($measurements), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')), 'product_id=%i', $request->getParam('productId'));
        }

        if ($check) {
            //run event
            $event = new Event();
            $event->productUpdated($request->getParam('productId'), '', 'measurements');
            UserActivity::Record('Update Measurements', $request->getParam('productId'), 'Products');
            return $response->withJson(['status' => 'true', 'msg' => 'De afmetingen van het product is gewijzigd !!']);
        }
        return $response->withJson(['status' => 'false', 'msg' => 'De afmetingen van het product is niet gewijzigd !!']);
    }


    /**************************************************************************************************************************************************
     ***************************************************************(OtherInfo Update)*****************************************************************
     **************************************************************************************************************************************************/
    public function otherInfoUpdate($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
          'productId' => v::notEmpty(),
          'otherInfo' => v::notEmpty(),
        ]);

        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Product data klopt niet !!']);
        }

        $OtherInfo = $request->getParam('otherInfo');
        $product = Product::getProduct($request->getParam('productId'));
        $check = DB::update('product', array('classification' => $OtherInfo['classification'], 'location' => $OtherInfo['location'], 'package' => $OtherInfo['package'], 'stocklevel' => $OtherInfo['stocklevel'], 'active' => $OtherInfo['active'], 'comment' => $OtherInfo['comment']), 'id=%i', $request->getParam('productId'));
        if ($check) {
            //run event
            $event = new Event();
            $event->productUpdated($request->getParam('productId'), '', 'content');
            updateProductStockStatus($request->getParam('productId'), $OtherInfo['stocklevel'], $product['stocklevel']);
            UserActivity::Record('Update OtherInfo', $request->getParam('productId'), 'Products');
            return $response->withJson(['status' => 'true', 'msg' => 'Het product is gewijzigd !!']);
        }
        return $response->withJson(['status' => 'false', 'msg' => 'Het product is niet gewijzigd !!']);
    }

    /**************************************************************************************************************************************************
     ***************************************************************(OtherInfo Update)*****************************************************************
     **************************************************************************************************************************************************/
    public function getProductUrls($request, $response, $args)
    {
        UserActivity::Record('Get Product urls', $request->getParam('product_id'), 'Products');

        $validation = $this->validator->validate($request, [
          'product_id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Product id klopt niet !!']);
        }

        $result = DB::query("SELECT * FROM product_url where product_id=%i and lang=%s order by id DESC", $request->getParam('product_id'), language);

        return $response->withJson(['status' => 'true', 'product_urls' => $result]);
    }

    /**************************************************************************************************************************************************
     ***************************************************************(OtherInfo Update)*****************************************************************
     **************************************************************************************************************************************************/
    public function urlsUpdate($request, $response, $args)
    {
        UserActivity::Record('Update Product urls', $request->getParam('product_id'), 'Products');

        $validation = $this->validator->validate($request, [
          'product_id' => v::notEmpty(),
          'product_urls' => v::notEmpty(),
        ]);

        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Product data klopt niet !!']);
        }

        foreach ($request->getParam('product_urls') as $productUrl) {
            if ($productUrl['new'] != 1 || $productUrl['slug'] == '') {
                continue;
            }
            $result = DB::query("SELECT slug,id FROM product_url where slug=%s and lang=%s limit 1", Slugify($productUrl['slug']), language);

            if (!$result) {
                $check = DB::insert('product_url', array(['product_id' => $request->getParam('product_id'), 'lang' => language, 'slug' => Slugify($productUrl['slug']), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')]));
                if (!$check) {
                    return $response->withJson(['status' => 'false', 'msg' => 'De urls zijn niet gewijzigd !!']);
                }
            } else {
                return $response->withJson(['status' => 'false', 'msg' => 'Hij bestaat al (' . $result[0]['id'] . ')']);
            }

            $result = DB::query("SELECT * FROM product_url where lang=%s and product_id=%i order by id DESC", language, $request->getParam('product_id'));
            //run event
            $event = new Event();
            $event->productUpdated($request->getParam('product_id'), '', 'url');
            return $response->withJson(['status' => 'true', 'msg' => 'De Urls zijn gewijzigd !!', 'product_urls' => $result]);
        }
        return $response->withJson(['status' => 'false', 'msg' => 'De urls zijn niet gewijzigd !!']);
    }

    /**************************************************************************************************************************************************
     ***************************************************************(productChilds Update)*************************************************************
     **************************************************************************************************************************************************/
    public function productChildsUpdate($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
          'product_id' => v::notEmpty(),
          'brandID' => v::notEmpty(),
          'typeID' => v::notEmpty(),
        ]);

        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Product data klopt niet !!']);
        }
        $check = DB::insert('product_child', array(
          [
            'product_id' => $request->getParam('product_id'),
            'product_brand_type_id' => $request->getParam('typeID'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
          ]
        ));
        if (!$check) {
            return $response->withJson(['status' => 'false', 'msg' => 'Is niet gewijzigd !!']);
        }
        //run event
        $event = new Event();
        $event->productChildsUpdated($request->getParam('product_id'), $request->getParam('typeID'));
        $event->productUpdated($request->getParam('product_id'), '', 'child');
        gereateEanForType($request->getParam('typeID'), $request->getParam('product_id'));
        return $response->withJson(['status' => 'true', 'msg' => 'Het is toegevoegd !!']);
    }
    /**************************************************************************************************************************************************
     ***************************************************************(productChilds Remove)*************************************************************
     **************************************************************************************************************************************************/
    public function productChildsRemove($request, $response, $args)
    {
        UserActivity::Record('Delete Child', $request->getParam('product_id'), 'Products');
        $validation = $this->validator->validate($request, [
          'product_id' => v::notEmpty(),
          'id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Product data klopt niet !!']);
        }
        if ($request->getParam('type') && $request->getParam('type') == 'type') {
            $check = DB::query('delete from product_child where product_id =%i and product_brand_type_id=%i', $request->getParam('product_id'), $request->getParam('id'));
            $typeId = $request->getParam('id');
        } else {
            $result = DB::query('select product_brand_type_id from product_child where product_id =%i and id=%i', $request->getParam('product_id'), $request->getParam('id'));
            if ($result) {
                $typeId = $result[0]['product_brand_type_id'];
            }
            $check = DB::query('delete from product_child where product_id =%i and id=%i', $request->getParam('product_id'), $request->getParam('id'));
        }
        if (!$check) {
            return $response->withJson(['status' => 'false', 'msg' => 'Heeft niet ontkoppeld!!']);
        }
        //run event
        $event = new Event();
        $event->productChildsUpdated($request->getParam('product_id'), $typeId);
        $event->productUpdated($request->getParam('product_id'), '', 'child');
        return $response->withJson(['status' => 'true', 'msg' => 'Heeft ontkoppeld']);
    }

    /**************************************************************************************************************************************************
     ***************************************************************(Get Combos Update)****************************************************************
     **************************************************************************************************************************************************/
    public function getProductCombos($request, $response, $args)
    {
        UserActivity::Record('Get Product Combos', $request->getParam('product_id'), 'Products');

        $validation = $this->validator->validate($request, [
          'product_id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Product id klopt niet !!']);
        }

        $products = Product::getProducts('', false);

        $productsT = array();
        foreach ($products as $product) {
            $productsT[$product['id']] = $product;
        }
        $products = $productsT;

        $result = DB::query("SELECT * FROM product_combo where product_id=%i order by sort_order ASC", $request->getParam('product_id'));
        return $response->withJson(['status' => 'true', 'product_combos' => $result, 'products' => $products]);
    }

    /**************************************************************************************************************************************************
     **************************************************************(product Combos Update)*************************************************************
     **************************************************************************************************************************************************/
    public function combosUpdate($request, $response, $args)
    {
        UserActivity::Record('Update Product Combos', $request->getParam('product_id'), 'Products');

        $validation = $this->validator->validate($request, [
          'product_id' => v::notEmpty(),
          'product_combos' => v::notEmpty(),
          'type' => v::notEmpty(),
        ]);

        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Product data klopt niet !!']);
        }

        $productCombos = $request->getParam('product_combos');
        $type = $request->getParam('type');
        $index = $request->getParam('index');
        foreach ($productCombos as $key => $productCombo) {
            if ($type == 'update' && $productCombo['new'] == 0) {
                $check = DB::update('product_combo', array('sort_order' => $productCombo['sort']), 'id=%i', $productCombo['id']);
            } elseif ($type == 'insert' && $productCombo['new'] == 1) {
                $check = DB::insert('product_combo', array(
                    [
                      'product_id' => $request->getParam('product_id'),
                      'combo_product_id' => $productCombo['product_id'],
                      'sort_order' => $productCombo['sort'],
                      'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ]
                  ));
                if (!$check) {
                    return $response->withJson(['status' => 'false', 'msg' => 'Is niet gewijzigd !!']);
                } else {
                    return $response->withJson(['status' => 'true', 'msg' => 'Is gewijzigd !!', 'combo_id' => DB::insertId()]);
                }
            } elseif ($type == 'updateInsert' && $productCombo['new'] == 0 && $index !== null && $index == $key) {
                $check = DB::update('product_combo', array('combo_product_id' => $productCombo['product_id']), 'id=%i', $productCombo['id']);
            }
        }
        if (!$check) {
            return $response->withJson(['status' => 'false', 'msg' => 'Is niet gewijzigd !!']);
        } else {
            //run event
            $event = new Event();
            $event->productUpdated($request->getParam('product_id'), '', 'combo');
            return $response->withJson(['status' => 'true', 'msg' => 'Is gewijzigd !!']);
        }
    }

    /**************************************************************************************************************************************************
     *************************************************************( product Combos Remove )************************************************************
     **************************************************************************************************************************************************/
    public function combosDelete($request, $response, $args)
    {
        UserActivity::Record('Delete Combo', $request->getParam('product_id'), 'Products');
        $validation = $this->validator->validate($request, [
          'product_id' => v::notEmpty(),
          'id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Product data klopt niet !!']);
        }
        $check = DB::query('delete from product_combo where product_id =%i and id=%i', $request->getParam('product_id'), $request->getParam('id'));
        if (!$check) {
            return $response->withJson(['status' => 'false', 'msg' => 'Niet verwijderd !!']);
        }
        //run event
        $event = new Event();
        $event->productUpdated($request->getParam('product_id'), '', 'comboDeleted');
        return $response->withJson(['status' => 'true', 'msg' => 'Verwijderd !!']);
    }
    /**************************************************************************************************************************************************
     **************************************************************( product b2b Update )**************************************************************
     **************************************************************************************************************************************************/
    public function b_2_bUpdate($request, $response, $args)
    {
        UserActivity::Record('Update b2b ', $request->getParam('product_id'), 'Products');
        $validation = $this->validator->validate($request, ['product_id' => v::notEmpty(),]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Product data klopt niet !!']);
        }
        $check = DB::replace('b_2_b', array('product_id' => $request->getParam('product_id'), 'latest_bought_price' => $request->getParam('latest_bought_price', 0.00), 'remarks' => $request->getParam('remarks', '')));
        if (!$check) {
            return $response->withJson(['status' => 'false', 'msg' => 'Niet gewijzigd !!']);
        }
        return $response->withJson(['status' => 'true', 'msg' => 'Gewijzigd !!']);
    }
    /**************************************************************************************************************************************************
     ******************************************************************( WriteOff )********************************************************************
     **************************************************************************************************************************************************/
    public function writeoffGet($request, $response, $args)
    {
        return $this->view->render($response, 'product/writeoff.tpl', ['active_menu' => 'products','page_title' => '']);
    }
    /**************************************************************************************************************************************************
     **************************************************************( WriteOff Ajax )*******************************************************************
     **************************************************************************************************************************************************/
    public function writeoffGetData($request, $response, $args)
    {
        $dateFrom = $request->getParam('data_from');
        $dateTo = $request->getParam('data_to');
        $results = DB::query(
            "SELECT t3.sku,t2.product_id, SUM(t2.totalprice) as totaal, SUM(t2.count) as aantal
                    FROM orders t1
                    LEFT JOIN order_item t2 ON t2.order_id = t1.id
                    LEFT JOIN product t3 ON t2.product_id = t3.id
                    WHERE ispaid =  2
                    AND t2.count > 0
                    AND t1.status_id = 3
                    AND t1.shop_id != 4
                    AND t1.created_at
                    BETWEEN %s
                    AND  %s
                    GROUP BY t2.product_id ORDER BY SUM(t2.count)  DESC,sku",
            $dateFrom,
            $dateTo
        );

        $returndata = array(
          'draw' => null,
          'cached' => null,
          'recordsTotal' => count($results),
          'recordsFiltered' => count($results),
          'data' => $results
        );

        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     **************************************************************( Add new Product  )****************************************************************
     **************************************************************************************************************************************************/
    public function productAddGet($request, $response, $args)
    {
        LangToDefault();
        return $this->view->render($response, 'product/product_add.tpl', ['active_menu' => 'products','page_title' => 'Product toevoegen']);
    }
    /**************************************************************************************************************************************************
     *********************************************************( Add new Product Post  )****************************************************************
     **************************************************************************************************************************************************/
    public function productAddPost($request, $response, $args)
    {
        // insert a new account
        $contents = array();
        foreach ($request->getParam('contents') as $key => $content) {
            $contents[language][$key] = $content;
        }
        DB::insert('product', array(
          'contents' => json_encode($contents),
          'sku' => $request->getParam('sku'),
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ));
        $id = DB::insertId();
        if ($id) {
            $this->container->flash->addMessage('success', 'Aangemaakt !!');
            return $response->withJson(['status' => 'true', 'product_id' => $id, 'msg' => 'Aangemaakt !!']);
        } else {
            return $response->withJson(['status' => 'false', 'msg' => 'Is niet Aangemaakt !!']);
        }
    }
    /**************************************************************************************************************************************************
    **************************************************************(Generate Types Update)**************************************************************
    **************************************************************************************************************************************************/
    public function getTypesGenerate($request, $response, $args)
    {
        $kb = Attribute::AllinAttributeGroup(9);

        $kbOptions = array();
        foreach ($kb as $k) {
            $kbOptions[$k['id']] = $k;
        }

        $brandsQ = DB::query("select id,name FROM product_brand order by name ASC");
        $brands = array();
        foreach ($brandsQ as $brand) {
            $brands[$brand['id']]['id'] = $brand['id'];
            $brands[$brand['id']]['name'] = $brand['name'];
        }

        $typesQ = DB::query("select id,name FROM product_brand_type where active_menu = 1 order by name ASC");
        $types = array();
        foreach ($typesQ as $type) {
            $types[$type['id']]['id'] = $type['id'];
            $types[$type['id']]['name'] = $type['name'];
        }
        $product = Product::getProduct($args['id']);
        //dd($product['measurements']);
        //DB::debugMode();
        $possibleTypes = DB::query("select * from  product_brand_type where 
    measurements->>'$.length' >= %i AND measurements->>'$.length' <= %i AND 
    measurements->>'$.width' >= %i AND measurements->>'$.width' <= %i AND
    active_menu = '1' ", $product['measurements']['minlength'], $product['measurements']['maxlength'], $product['measurements']['minwidth'], $product['measurements']['maxwidth']);
        $typesInProduct = DB::queryOneColumn("product_brand_type_id", "select product_brand_type_id FROM product_child where product_id = %i", $args['id']);
        $data = array();
        foreach ($possibleTypes as $key => $possible) {
            $possibleTmp = array();
            $yes = 0;
            if (in_array($possible['id'], $typesInProduct)) {
                $yes = 1;
            }
            $possibleTmp['yes'] = $yes;
            $possibleTmp['name'] = $possible['name'];
            $possibleTmp['brand'] = $brands[$possible['product_brand_id']]['name'];
            $possibleTmp['brand_id'] = $brands[$possible['product_brand_id']]['id'];
            $possibleTmp['id'] = $possible['id'];
            $possibleTmp['kb'] = '';
            if ($possible['kb_options']) {
                $possible['kb_options'] = explode('|', $possible['kb_options']);
            } else {
                $possible['kb_options'] = '';
            }
            $i = 0;
 
            $possibleTmp['kb'] = '';
            if (isset($possible['kb_options']) && $possible['kb_options'] != '') {
                foreach ($possible['kb_options'] as $kbTmp) {
                    if (isset($kbOptions[$kbTmp]['name'])) {
                        if ($i == 0) {
                            $possibleTmp['kb'] .= $kbOptions[$kbTmp]['name'];
                        } else {
                            $possibleTmp['kb'] .= ' | ' . $kbOptions[$kbTmp]['name'];
                        }
                    }
                    $i++;
                }
            }
            $data[] = $possibleTmp;
        }

        $returndata = array(
          'draw' => null,
          'cached' => null,
          'recordsTotal' => count($data),
          'recordsFiltered' => count($data),
          'data' => $data
        );
        return json_encode($returndata);
    }
}
