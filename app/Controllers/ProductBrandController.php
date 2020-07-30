<?php

namespace App\Controllers;

use Slim\View\Twig as View;
use App\Classes\{Brand, UserActivity, Event};
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
        return $this->view->render($response, 'brands/brand_all.tpl', ['active_menu' => 'products', 'page_title' => 'Alle Merken']);
    }

    /**************************************************************************************************************************************************
     **************************************************************(Attribute Groups Get Data)*********************************************************
     **************************************************************************************************************************************************/
    public function brandsGetData($request, $response, $args)
    {
        $Brands = Brand::All();

        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($Brands),
            'recordsFiltered' => count($Brands),
            'data' => $Brands
        ];

        return json_encode($returndata);
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
        return $this->view->render($response, 'brands/brand_single.tpl', ['Brand' => $Brand,
            'active_menu' => 'products', 'page_title' => ucfirst($Brand['name']) . ' - ' . $Brand['id']]);
    }

    /**************************************************************************************************************************************************
     **************************************************************(BrandsGetTypesInBranda)************************************************************
     **************************************************************************************************************************************************/
    public function brandsGetTypesInBrand($request, $response, $args)
    {
        $types = Brand::AllTypesIn($request->getParam('product_brand_id'));

        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($types),
            'recordsFiltered' => count($types),
            'data' => $types
        ];
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
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
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
        return $this->view->render($response, 'brands/brand_add.tpl', ['active_menu' => 'products', 'page_title' => 'Merk toevoegen']);
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
