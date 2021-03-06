<?php

namespace App\Controllers;

use Slim\View\Twig as View;
use App\Classes\Brand;
use App\Classes\UserActivity;
use App\Classes\Event;
use DB;
use Respect\Validation\Validator as v;
use Slim\Exception\NotFoundException;

class ProductBrandController extends Controller
{
    /**************************************************************************************************************************************************
     *************************************************************(Attribute Groups Index Get)*********************************************************
     **************************************************************************************************************************************************/
    public function brandsGetIndex($request, $response, $args)
    {
        $Brands = Brand::All();
        return $this->view->render($response, 'brand/index.tpl', ['active_menu' => 'brands', 'page_title' => 'Alle Merken', 'brands' => $Brands]);
    }
    /**************************************************************************************************************************************************
     ************************************************************(Attribute Groups Get Single)*********************************************************
     **************************************************************************************************************************************************/
    public function brandsGetSingle($request, $response, $args)
    {
        $validation = $this->validator->validateGet($args, [
            'id' => v::notEmpty(),
        ]);

        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Brands.GetIndex'));
        }

        $Brand = Brand::Find($args['id']);

        if (!$Brand) {
            throw new NotFoundException($request, $response);
        }

        $Brand['contents'] = json_decode($Brand['contents'], true);
        return $this->view->render($response, 'brand/edit.tpl', ['Brand' => $Brand,
            'active_menu' => 'brands', 'page_title' => ucfirst($Brand['name']) . ' - ' . $Brand['id']]);
    }

    /**************************************************************************************************************************************************
     **************************************************************(BrandsGetTypesInBranda)************************************************************
     **************************************************************************************************************************************************/
    public function brandsGetTypesInBrand($request, $response, $args)
    {
        $brandId = $request->getParam('product_brand_id');

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
           where (product_brand_type.id=%i) or (product_brand_type.name like %s) and product_brand_type.product_brand_id = %i
           ", $search, '%'.$search.'%', '%'.$search.'%',$brandId);
           $typesCount = $typesCount[0]['count'];

           //get all types in specific limit
           $types = DB::query("SELECT product_brand_type.id,product_brand_type.product_brand_id,product_brand_type.main_category_id,product_brand_type.name,
           JSON_UNQUOTE(JSON_EXTRACT(product_brand_type.slug, '$." . language . "')) as slug,
           product_brand_type.active_menu,product_brand_type.active_feed,product_brand_type.staff_pick,product_brand_type.kb_options,product_brand_type.created_at,
           product_brand_type.updated_at,product_brand_type.popular_list,product_brand_type.photo,product_brand.name as brand_name FROM product_brand_type
           LEFT JOIN product_brand ON product_brand.id = product_brand_type.product_brand_id
           where (product_brand_type.id=%i) or (product_brand_type.name like %s) and product_brand_type.product_brand_id = %i
           order by $orderBy $orderDir limit %i offset %i", $search, '%'.$search.'%', '%'.$search.'%',$brandId, $limit, $offset);
       } else {
           //get count of all types in table
           $typesCount = DB::query("SELECT COUNT(*) as count FROM product_brand_type
           where product_brand_type.product_brand_id = %i",$brandId);
           $typesCount = $typesCount[0]['count'];

           //get all types in specific limit
           $types = DB::query("SELECT product_brand_type.id,product_brand_type.product_brand_id,product_brand_type.main_category_id,product_brand_type.name,
           JSON_UNQUOTE(JSON_EXTRACT(product_brand_type.slug, '$." . language . "')) as slug,
           product_brand_type.active_menu,product_brand_type.active_feed,product_brand_type.staff_pick,product_brand_type.kb_options,product_brand_type.created_at,
           product_brand_type.updated_at,product_brand_type.popular_list,product_brand_type.photo,product_brand.name as brand_name FROM product_brand_type
           LEFT JOIN product_brand ON product_brand.id = product_brand_type.product_brand_id
           where product_brand_type.product_brand_id = %i
           order by $orderBy $orderDir limit %i offset %i", $brandId, $limit, $offset);
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
     ************************************************************(Attribute Groups Get Single)*********************************************************
     **************************************************************************************************************************************************/
    public function brandsUpdateSingle($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
            'name' => v::notEmpty(),
            'slug' => v::notEmpty(),
            'active_menu' => v::notEmpty(),
        ]);

        //if the general fields are validated we can check if the url is changed or not
        if (!$validation->failed()) {
            $brand = Brand::Find($request->getParam('id'));
            if ($brand['slug'] != Slugify($request->getParam('slug'))) {
                $validation = $this->validator->validate($request, [
                    'slug' => v::BrandUniqueSlug(),
                ]);
            }
        }

        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            // $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            if ($request->getParam('id')) {
                return $response->withRedirect($this->router->pathFor('Brands.GetSingle', ['id' => $request->getParam('id')]));
            } else {
                return $response->withRedirect($this->router->pathFor('Brands.GetIndex'));
            }
        }

        $uploadedFiles = $request->getUploadedFiles();
        $photo = $uploadedFiles['photo'];

        $Brand = new Brand;
        $Brand->id = $request->getParam('id');
        $Brand->contents = $request->getParam('contents');
        $Brand->name = $request->getParam('name');
        $Brand->active_menu = $request->getParam('active_menu');
        $Brand->active_feed = $request->getParam('active_feed');
        $Brand->popular_list = $request->getParam('popular_list');
        $Brand->slug = Slugify($request->getParam('slug'));
        if ($photo->getError() === UPLOAD_ERR_OK && $photo) {
            $Brand->photo = $photo;
        } else {
            $Brand->photo = '';
        }

        if ($Brand->Update()) {
            $this->container->flash->addMessage('success', 'Merk is bijgewerkt');
            UserActivity::Record('Update', $request->getParam('id'), 'Brands');
            return $response->withRedirect($this->router->pathFor('Brands.GetSingle', ['id' => $request->getParam('id')]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Brands.GetSingle', ['id' => $request->getParam('id')]));
        }
    }

    /**************************************************************************************************************************************************
     ************************************************************(Attribute Groups Get Add)************************************************************
     **************************************************************************************************************************************************/
    public function brandsAddGet($request, $response, $args)
    {
        LangToDefault();
        return $this->view->render($response, 'brand/add.tpl', ['active_menu' => 'brands', 'page_title' => 'Merk toevoegen']);
    }

    /**************************************************************************************************************************************************
     ************************************************************(Attribute Groups Post New)***********************************************************
     **************************************************************************************************************************************************/
    public function brandsAddPost($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'name' => v::notEmpty(),
            'slug' => v::notEmpty()->BrandUniqueSlug(),
        ]);
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Brands.GetAdd'));
        }
        $uploadedFiles = $request->getUploadedFiles();
        $photo = $uploadedFiles['photo'];

        $Brand = new Brand;
        $Brand->id = $request->getParam('id');
        $Brand->contents = $request->getParam('contents');
        $Brand->name = $request->getParam('name');
        $Brand->active_menu = $request->getParam('active_menu');
        $Brand->active_feed = $request->getParam('active_feed');
        $Brand->popular_list = $request->getParam('popular_list');
        $Brand->slug = Slugify($request->getParam('slug'));
        if ($photo->getError() === UPLOAD_ERR_OK && $photo) {
            $Brand->photo = $photo;
        } else {
            $Brand->photo = '';
        }
        $id = $Brand->Create();
        if ($id) {
            unset($_COOKIE['language']);
            $this->container->flash->addMessage('success', 'Merk is toegevoegd');
            UserActivity::Record('Create', $id, 'Brands');
            return $response->withRedirect($this->router->pathFor('Brands.GetSingle', ['id' => $id]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Brands.GetAdd'));
        }
    }

    /**************************************************************************************************************************************************
     ************************************************************( Remove type from brand )************************************************************
     **************************************************************************************************************************************************/
    public function brandsRemoveType($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Type data klopt niet !!']);
        }

        $check = DB::update('product_brand_type', ['product_brand_id' => null], 'id=%i', $request->getParam('id'));
        if (!$check) {
            return $response->withJson(['status' => 'false', 'msg' => 'Heeft niet ontkoppeld !!']);
        }
        return $response->withJson(['status' => 'true', 'msg' => 'Heeft ontkoppeld !!']);
    }
}
