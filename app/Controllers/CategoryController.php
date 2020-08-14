<?php

namespace App\Controllers;

use Slim\View\Twig as View;
use App\Classes\UserActivity;
use App\Classes\Category;
use DB;
use Respect\Validation\Validator as v;

class CategoryController extends Controller
{
    public function categoryGetAll($request, $response, $args)
    {
        $categories = Category::All();
        return $response->withJson(['status' => 'true', 'categories' => $categories]);
    }
    /**
     * categoriesGetIndex function get rendered view with datatable data
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return void
     */
    public function categoriesGetIndex($request, $response, $args)
    {
        $categories = Category::All();
        return $this->view->render($response, 'category/index.tpl', ['active_menu' => 'products', 'page_title' => 'Alle categorieën', 'categories' => $categories]);
    }

    /**************************************************************************************************************************************************
     ************************************************************(Attribute Groups Get Single)*********************************************************
     **************************************************************************************************************************************************/
    public function categoriesGetSingle($request, $response, $args)
    {
        $validation = $this->validator->validateGet($args, [
            'id' => v::notEmpty(),
        ]);

        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Categories.GetIndex'));
        }

        $Category = Category::Find($args['id']);

        if (!$Category) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Categories.GetIndex'));
        }
        $Category['contents'] = json_decode($Category['contents'], true);
        return $this->view->render($response, 'categories/category_single.tpl', ['Category' => $Category,
        'active_menu' => 'products','page_title' => $Category['name'] . ' - ' . $Category['id']]);
    }
    /**************************************************************************************************************************************************
     **************************************************************(CategoriesGetTypesInCategorya)************************************************************
     **************************************************************************************************************************************************/
    public function categoriesGetTypesInCategory($request, $response, $args)
    {
        $types = Category::AllTypesIn($request->getParam('product_category_id'));

        $returndata = array(
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($types),
            'recordsFiltered' => count($types),
            'data' => $types
        );
        return json_encode($returndata);
    }
    /**************************************************************************************************************************************************
     ************************************************************(Attribute Groups Get Single)*********************************************************
     **************************************************************************************************************************************************/
    public function categoriesUpdateSingle($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
            'name' => v::notEmpty(),
            'slug' => v::notEmpty(),
        ]);

        //if the general fields are validated we can check if the url is changed or not
        if (!$validation->failed()) {
            $category = Category::Find($request->getParam('id'));
            if ($category['slug'] != Slugify($request->getParam('slug'))) {
                $validation = $this->validator->validate($request, [
                    'slug' => v::CategoryUniqueSlug(),
                ]);
            }
        }
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            if ($request->getParam('id')) {
                return $response->withRedirect($this->router->pathFor('Categories.GetSingle', ['id' => $request->getParam('id')]));
            } else {
                return $response->withRedirect($this->router->pathFor('Categories.GetIndex'));
            }
        }

        $uploadedFiles = $request->getUploadedFiles();
        $photo = $uploadedFiles['photo'];

        $Category = new Category;
        $Category->id = $request->getParam('id');
        $Category->contents = $request->getParam('contents');
        $Category->name = $request->getParam('name');
        $Category->active = $request->getParam('active');
        $Category->slug = Slugify($request->getParam('slug'));
        if ($photo->getError() === UPLOAD_ERR_OK && $photo) {
            $Category->photo = $photo;
        } else {
            $Category->photo = '';
        }

        if ($Category->Update()) {
            $this->container->flash->addMessage('success', 'Categorie is bijgewerkt');
            UserActivity::Record('Update', $request->getParam('id'), 'Categories');
            return $response->withRedirect($this->router->pathFor('Categories.GetSingle', ['id' => $request->getParam('id')]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Categories.GetSingle', ['id' => $request->getParam('id')]));
        }
    }
    /**************************************************************************************************************************************************
     ************************************************************(Attribute Groups Get Add)************************************************************
     **************************************************************************************************************************************************/
    public function categoriesAddGet($request, $response, $args)
    {
        LangToDefault();
        return $this->view->render($response, 'categories/category_add.tpl', ['active_menu' => 'products','page_title' => 'Categorie toevoegen']);
    }
    /**************************************************************************************************************************************************
     ************************************************************(Attribute Groups Post New)***********************************************************
     **************************************************************************************************************************************************/
    public function categoriesAddPost($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'name' => v::notEmpty(),
            'slug' => v::notEmpty()->CategoryUniqueSlug(),
        ]);
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Categories.GetAdd'));
        }
        $uploadedFiles = $request->getUploadedFiles();
        $photo = $uploadedFiles['photo'];

        $Category = new Category;
        $Category->id = $request->getParam('id');
        $Category->contents = $request->getParam('contents');
        $Category->name = $request->getParam('name');
        $Category->active = $request->getParam('active');
        $Category->slug = Slugify($request->getParam('slug'));
        if ($photo->getError() === UPLOAD_ERR_OK && $photo) {
            $Category->photo = $photo;
        } else {
            $Category->photo = '';
        }
        $id = $Category->Create();
        if ($id) {
            unset($_COOKIE['language']);
            $this->container->flash->addMessage('success', 'Categorie is toegevoegd');
            UserActivity::Record('Create', $id, 'Categories');
            return $response->withRedirect($this->router->pathFor('Categories.GetSingle', ['id' => $id]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Categories.GetAdd'));
        }
    }
    /**************************************************************************************************************************************************
     ************************************************************( Remove type from category )************************************************************
     **************************************************************************************************************************************************/
    public function categoriesRemoveType($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Type data klopt niet !!']);
        }

        //$check=DB::update('product_category_type', array('product_category_id' => NULL), "id=%i",$request->getParam('id'));
        if (!$check) {
            return $response->withJson(['status' => 'false', 'msg' => 'Heeft niet ontkoppeld !!']);
        }
        return $response->withJson(['status' => 'true', 'msg' => 'Heeft ontkoppeld !!']);
    }
}
