<?php

namespace App\Controllers;

use Slim\View\Twig as View;
use App\Classes\Brand;
use App\Classes\Type;
use App\Classes\UserActivity;
use App\Classes\Category;
use App\Classes\Product;
use App\Classes\General;
use DB;
use Respect\Validation\Validator as v;
use Slim\Exception\NotFoundException;
use Nextimage\Nextimage\Resize;

class ProductTypeController extends Controller
{

/**
 * typesGetIndex function // render view
 *
 * @param [type] $request
 * @param [type] $response
 * @param [type] $args
 * @return void rendered view
 */
    public function typesGetIndex($request, $response, $args)
    {
        return $this->view->render($response, 'type/index.tpl', ['active_menu' => 'products', 'page_title' => 'Types']);
    }
    /**
     * typesGetData function get data for datatable of types list
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return array datatable json
     */
    public function typesGetData($request, $response, $args)
    {
        $columns = array('0' => 'product_brand_type.id', '1' => 'product_brand_type.name', '2' => 'product_brand.brand_name', '3' => 'product_brand_type.popular_list',
        '4' => 'product_brand_type.active_menu', '5' => 'product_brand_type.active_feed','6' => 'product_brand_type.updated_at', '7' => 'product_brand_type.created_at');
  
        //get order by and order direction
        $orderBy = $columns[$request->getParam('order')[0]['column']];
        $orderDir = strtoupper($request->getParam('order')[0]['dir']);
        
        //get limit and offset
        $limit = $request->getParam('length');
        $offset = $request->getParam('start');

        //get search value
        $search = $request->getParam('search')['value'];
        
        //check if search value not empty add it to the query
        if ($search !='' && !empty($search)) {

            //get count of all products in table
            $typesCount = DB::query("SELECT COUNT(*) as count 
            FROM product_brand_type 
            LEFT JOIN product_brand ON product_brand.id = product_brand_type.product_brand_id
            where (product_brand_type.id=%i) or (product_brand_type.name like %s) or (product_brand.name like %s)
            ", $search, '%'.$search.'%', '%'.$search.'%');
            $typesCount = $typesCount[0]['count'];

            //get all types in specific limit
            $types = DB::query("SELECT product_brand_type.id,product_brand_type.product_brand_id,product_brand_type.main_category_id,product_brand_type.name,
            JSON_UNQUOTE(JSON_EXTRACT(product_brand_type.slug, '$." . language . "')) as slug,
            product_brand_type.active_menu,product_brand_type.active_feed,product_brand_type.staff_pick,product_brand_type.kb_options,product_brand_type.created_at,
            product_brand_type.updated_at,product_brand_type.popular_list,product_brand_type.photo,product_brand.name as brand_name FROM product_brand_type
            LEFT JOIN product_brand ON product_brand.id = product_brand_type.product_brand_id
            where (product_brand_type.id=%i) or (product_brand_type.name like %s) or (product_brand.name like %s)
            order by $orderBy $orderDir limit %i offset %i", $search, '%'.$search.'%', '%'.$search.'%', $limit, $offset);
        } else {
            //get count of all types in table
            $typesCount = DB::query("SELECT COUNT(*) as count FROM product_brand_type");
            $typesCount = $typesCount[0]['count'];

            //get all types in specific limit
            $types = DB::query("SELECT product_brand_type.id,product_brand_type.product_brand_id,product_brand_type.main_category_id,product_brand_type.name,
            JSON_UNQUOTE(JSON_EXTRACT(product_brand_type.slug, '$." . language . "')) as slug,
            product_brand_type.active_menu,product_brand_type.active_feed,product_brand_type.staff_pick,product_brand_type.kb_options,product_brand_type.created_at,
            product_brand_type.updated_at,product_brand_type.popular_list,product_brand_type.photo,product_brand.name as brand_name FROM product_brand_type
            LEFT JOIN product_brand ON product_brand.id = product_brand_type.product_brand_id
            order by $orderBy $orderDir limit %i offset %i", $limit, $offset);
        }

       
        if ($types) {
            foreach ($types as $key => $type) {
                $types[$key]['photo'] = IMAGE_PATH .'/'. getThumb($type['photo'], '123bestdeal');
            }
        }
        $returndata = array(
            'draw' => $request->getParam('draw'),
            'cached' => null,
            'recordsTotal' => count($types),
            'recordsFiltered' => $typesCount,
            'data' => $types
          );
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     ****************************************************************(Type s Get Single)***************************************************************
     **************************************************************************************************************************************************/
    public function typesGetSingle($request, $response, $args)
    {
        $validation = $this->validator->validateGet($args, [
            'id' => v::notEmpty(),
        ]);

        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Types.GetIndex'));
        }

        $Type = Type::Find($args['id']);
        if (!$Type) {
            throw new NotFoundException($request, $response);
        }

        $Type['contents'] = json_decode($Type['contents'], true);
        $Type['measurements'] = json_decode($Type['measurements'], true);
        $Type['categories_contents'] = json_decode($Type['categories_contents'], true);
   
        $products = Product::getAllProductsIdSkuTitle();
        $Brands = Brand::All();
        return $this->view->render($response, 'type/edit.tpl', ['Type' => $Type, 'Brands' => $Brands, 'products' => $products,
        'active_menu' => 'types', 'page_title' => ucfirst($Type['name']) . ' - ' . $Type['id']]);
    }

    /**************************************************************************************************************************************************
     ****************************************************************(Type s Get Single)***************************************************************
     **************************************************************************************************************************************************/
    public function typesUpdateSingle($request, $response, $args)
    {
        //validate the general fields
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
            'name' => v::notEmpty(),
            'product_brand_id' => v::notEmpty(),
            'slug' => v::notEmpty()
        ]);
        //if the general fields are validated we can check if the url is changed or not
        if (!$validation->failed()) {
            $type = Type::Find($request->getParam('id'));
            if ($type['slug'] != Slugify($request->getParam('slug'))) {
                $validation = $this->validator->validate($request, [
                    'slug' => v::TypeUniqueSlug(),
                ]);
            }
        }

        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            if ($request->getParam('id')) {
                return $response->withRedirect($this->router->pathFor('Types.GetSingle', ['id' => $request->getParam('id')]));
            } else {
                return $response->withRedirect($this->router->pathFor('Types.GetIndex'));
            }
        }

        $uploadedFiles = $request->getUploadedFiles();

        $photo = $uploadedFiles['photo'];
        $Type = new Type;
        $Type->id = $request->getParam('id');
        $Type->product_brand_id = $request->getParam('product_brand_id');
        $Type->name = $request->getParam('name');
        $Type->contents = $request->getParam('contents');
        $Type->measurements = $request->getParam('measurements');
        $Type->kb_options = $request->getParam('kb_options', '0');
        $Type->staff_pick = $request->getParam('staff_pick', 0);
        $Type->active_menu = $request->getParam('active_menu', 0);
        $Type->active_feed = $request->getParam('active_feed', 0);
        $Type->popular_list = $request->getParam('popular_list', 0);
        $Type->slug = Slugify($request->getParam('slug'));
        if ($photo && $photo->getError() === UPLOAD_ERR_OK) {
            $Type->photo = $photo;
        } else {
            $Type->photo = '';
        }

        if ($Type->Update()) {
            UserActivity::Record('Update', $request->getParam('id'), 'Type');
            $this->container->flash->addMessage('success', 'Type is bijgewerkt');
            return $response->withRedirect($this->router->pathFor('Types.GetSingle', ['id' => $request->getParam('id')]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Types.GetSingle', ['id' => $request->getParam('id')]));
        }
    }
    /**************************************************************************************************************************************************
     ****************************************************************(Type s Get Add)******************************************************************
     **************************************************************************************************************************************************/
    public function typesAddGet($request, $response, $args)
    {
        LangToDefault();
        $products = Product::getAllProductsIdSkuTitle();
        $mainCatoegories = General::getMainCategories();
        if (isset($args['id']) && $args['id'] != '') {
            $id = $args['id'];
            $Brand = Brand::Find($id);
            return $this->view->render($response, 'type/add.tpl', ['Brand' => $Brand, 'products' => $products,
            'mainCatoegories' => $mainCatoegories, 'page_title' => 'Type toevoegen aan ' . ucfirst($Brand['name']),'active_menu' => 'products']);
        } else {
            $Brands = Brand::All();
            return $this->view->render($response, 'type/add.tpl', ['Brands' => $Brands, 'products' => $products,
            'mainCatoegories' => $mainCatoegories, 'active_menu' => 'types', 'page_title' => 'Type toevoegen']);
        }
    }

    /**************************************************************************************************************************************************
     ****************************************************************(Type s Post New)*****************************************************************
     **************************************************************************************************************************************************/
    public function typesAddPost($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'name' => v::notEmpty(),
            'product_brand_id' => v::notEmpty(),
            'slug' => v::notEmpty()->TypeUniqueSlug(),
            'main_category_id' => v::notEmpty()
        ]);
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Types.GetAdd'));
        }

        $uploadedFiles = $request->getUploadedFiles();
        $photo = $uploadedFiles['photo'];

        $Type = new Type;
        $Type->id = $request->getParam('id');
        $Type->product_brand_id = $request->getParam('product_brand_id');
        $Type->name = $request->getParam('name');
        $Type->contents = $request->getParam('contents');
        $Type->measurements = $request->getParam('measurements');
        $Type->kb_options = $request->getParam('kb_options');
        $Type->staff_pick = $request->getParam('staff_pick', 0);
        $Type->active_menu = $request->getParam('active_menu');
        $Type->active_feed = $request->getParam('active_feed');
        $Type->popular_list = $request->getParam('popular_list');
        $Type->main_category_id = $request->getParam('main_category_id');
        $Type->slug = Slugify($request->getParam('slug'));
        if ($photo && $photo->getError() === UPLOAD_ERR_OK) {
            $Type->photo = $photo;
        } else {
            $Type->photo = '';
        }

        $id = $Type->Create();
        if ($id) {
            // if ($Type->photo != '') $this->typesThumbs($Type, $Type->photo);
            unset($_COOKIE['language']);
            UserActivity::Record('Create', $id, 'Type');
            $this->container->flash->addMessage('success', 'Type is toegevoegd');
            return $response->withRedirect($this->router->pathFor('Types.GetSingle', ['id' => $id]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Types.GetAdd', ['id' => $request->getParam('type_group_id')]));
        }
    }

    /**************************************************************************************************************************************************
     ******************************************(Get Products Names and Ids in a Type s Index Get)******************************************************
     **************************************************************************************************************************************************/
    public function typesGetProductsIdsAndNames($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'search' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['return' => 'Error', 'msg' => 'Er is geen resultaat']);
        }
        if ($request->getParam('type')) {
            $type = $request->getParam('type');
        }
        $s = $request->getParam('search');
        $products = DB::query("select id ,sku, JSON_UNQUOTE(JSON_EXTRACT(contents, '$.nl.title')) as title from product
                                where JSON_UNQUOTE(JSON_EXTRACT(contents, '$.nl.title')) like %s
                                or sku like %s
                                or id like %s
                                limit  10", $s . '%', $s . '%', $s . '%');
        return $response->withJson(['return' => 'True', 'products' => $products]);
    }
    /**************************************************************************************************************************************************
     ***************************************************************(Get Types in A Brand )************************************************************
     **************************************************************************************************************************************************/
    public function brandsGetTypesInBrand($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'brand_id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Missende data']);
        }
        $types = Brand::AllTypesIn($request->getParam('brand_id'));
        return $response->withJson(['status' => 'true', 'types' => $types]);
    }
    /**************************************************************************************************************************************************
     *******************************************************************(Type Delete)******************************************************************
     **************************************************************************************************************************************************/
    public function typesDeleteSingle($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Type data klopt niet !!']);
        }
        $check = DB::query('delete from product_brand_type where id =%i', $request->getParam('id'));
        if (!$check) {
            return $response->withJson(['status' => 'false', 'msg' => 'Heeft niet verwijderd!!']);
        }
        return $response->withJson(['status' => 'true', 'msg' => 'Heeft verwijderd']);
    }
    /**************************************************************************************************************************************************
     *****************************************************************(Category Type Update)***********************************************************
     **************************************************************************************************************************************************/
    public function categoryTypeUpdate($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'type_id' => v::notEmpty(),
            'type' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Type data klopt niet !!']);
        }


        $catId = $request->getParam('cat_id');
        $Type = new Type;
        $Type->id = $request->getParam('type_id');
        if ($request->getParam('type') == 'image' && $catId) {
            $uploadedFiles = $request->getUploadedFiles();
            $photo = Upload('type_categories', $request->getParam('type_id') . '_' . $catId, $uploadedFiles['file']);
            if ($photo) {
                $tmp = array('id' => $request->getParam('type_id') . '_' . $catId, 'photo' => $photo, 'name' => 'type_categories');
                Thumbs($tmp, $uploadedFiles['file'], Type::$thumbs);
                if ($Type->UpdateCategories('image', $catId, $photo)) {
                    UserActivity::Record('Update Image', $Type->id, 'Type Category');
                    return $response->withJson(['status' => 'true', 'image' => $photo, 'msg' => 'Type foto is bijgewerkt']);
                } else {
                    return $response->withJson(['status' => 'false', 'msg' => 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.']);
                }
            }
        } else {
            $categoryType = $request->getParam('category_type');
            $Type->categoriesContents = $categoryType;
            if ($Type->UpdateCategories()) {
                UserActivity::Record('Update', $Type->id, 'Type Category');
                return $response->withJson(['status' => 'true', 'msg' => 'Type is bijgewerkt']);
            } else {
                return $response->withJson(['status' => 'false', 'msg' => 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.']);
            }
        }
        return $response->withJson(['status' => 'false', 'msg' => 'Missende data']);
    }

    /**************************************************************************************************************************************************
     ************************************************************(Generate Productss Update)************************************************************
     **************************************************************************************************************************************************/
    public function getProductsGenerate($request, $response, $args)
    {
        $type = Type::Find($args['id']);
        if (!$type) {
            throw new NotFoundException($request, $response);
        }
        $type['measurements'] = json_decode($type['measurements'], true);

        $possibleProducts = DB::query("select product_id,product.sku, product.contents->>'$." . language . ".title' as title from  product_measurements 
        left join product on product.id = product_measurements.product_id
        where measurements->>'$.maxlength' >= %i AND measurements->>'$.minlength' <= %i AND 
        measurements->>'$.maxwidth' >= %i AND measurements->>'$.minwidth' <= %i AND
        stocklevel != 4 ORDER BY sku ASC ", $type['measurements']['length'], $type['measurements']['length'], $type['measurements']['width'], $type['measurements']['width']);

        $productsInType = DB::queryOneColumn("product_id", "select product_id FROM product_child where product_brand_type_id = %i", $args['id']);
        $data = array();
        foreach ($possibleProducts as $key => $possible) {
            $possibleTmp = array();
            $yes = 0;
            if (in_array($possible['product_id'], $productsInType)) {
                $yes = 1;
            }
            $possibleTmp['yes'] = $yes;
            $possibleTmp['sku'] = $possible['sku'];
            ;
            $possibleTmp['title'] = $possible['title'];
            $possibleTmp['product_id'] = $possible['product_id'];
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
