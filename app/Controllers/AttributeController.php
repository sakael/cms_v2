<?php

namespace App\Controllers;

use Slim\View\Twig as View;
use App\Classes\AttributeGroup;
use App\Classes\Attribute;
use App\Classes\UserActivity;
use App\Classes\Event;
use DB;
use Respect\Validation\Validator as v;
use Slim\Exception\NotFoundException;
use Carbon\Carbon as Carbon;

class AttributeController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////                     Attributes Groups                        //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
    /**************************************************************************************************************************************************
     *************************************************************(Attribute Groups Index Get)*********************************************************
     **************************************************************************************************************************************************/
    public function attributeGroupsGetIndex($request, $response, $args)
    {
        return $this->view->render(
            $response,
            'attributes/attribute_group_all.tpl',
            ['active_menu' => 'products', 'page_title' => 'Attribuut groepen']
        );
    }
    /**************************************************************************************************************************************************
     **************************************************************(Attribute Groups Get Data)*********************************************************
     **************************************************************************************************************************************************/
    public function attributeGroupsGetData($request, $response, $args)
    {
        $attributeGroup = AttributeGroup::All();

        $returnData = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($attributeGroup),
            'recordsFiltered' => count($attributeGroup),
            'data' => $attributeGroup
        ];
        return json_encode($returnData);
    }

    /**************************************************************************************************************************************************
     ************************************************************(Attribute Groups Get Single)*********************************************************
     **************************************************************************************************************************************************/
    public function attributeGroupsGetSingle($request, $response, $args)
    {
        $validation = $this->validator->validateGet($args, [
            'id' => v::notEmpty(),
        ]);

        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('AttributeGroups.GetIndex'));
        }

        $AttributeGroup = AttributeGroup::Find($args['id']);
        if (!$AttributeGroup) {
            throw new NotFoundException($request, $response);
        }
        return $this->view->render($response, 'attributes/attribute_group_single.tpl', [
            'AttributeGroup' => $AttributeGroup,
            'active_menu' => 'products', 'page_title' => $AttributeGroup['name'] . ' - ' . $AttributeGroup['id']
        ]);
    }

    /**************************************************************************************************************************************************
     ************************************************************(Attribute Groups Get Single)*********************************************************
     **************************************************************************************************************************************************/
    public function attributeGroupsUpdateSingle($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
            'name' => v::notEmpty(),
        ]);
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            if ($request->getParam('id')) {
                return $response->withRedirect($this->router->pathFor('AttributeGroups.GetSingle', ['id' => $request->getParam('id')]));
            } else {
                return $response->withRedirect($this->router->pathFor('AttributeGroups.GetIndex'));
            }
        }
        $AttributeGroup = new AttributeGroup;
        $AttributeGroup->id = $request->getParam('id');
        $AttributeGroup->multiselect = $request->getParam('multiselect');
        $AttributeGroup->name = $request->getParam('name');
        if ($AttributeGroup->Update()) {
            $this->container->flash->addMessage('success', 'Attribuutgroep is bijgewerkt');
            UserActivity::Record('Update', $request->getParam('id'), 'AttributeGroups');
            return $response->withRedirect($this->router->pathFor('AttributeGroups.GetSingle', ['id' => $request->getParam('id')]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('AttributeGroups.GetSingle', ['id' => $request->getParam('id')]));
        }
    }

    /**************************************************************************************************************************************************
     ************************************************************(Attribute Groups Get Add)************************************************************
     **************************************************************************************************************************************************/
    public function attributeGroupsAddGet($request, $response, $args)
    {
        LangToDefault();
        return $this->view->render($response, 'attributes/attribute_group_add.tpl', [
            'active_menu' => 'products',
            'page_title' => 'Attribuut toevoegen'
        ]);
    }

    /**************************************************************************************************************************************************
     ************************************************************(Attribute Groups Post New)***********************************************************
     **************************************************************************************************************************************************/
    public function attributeGroupsAddPost($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'name' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('AttributeGroups.GetAdd'));
        }
        $AttributeGroup = new AttributeGroup;
        $AttributeGroup->multiselect = $request->getParam('multiselect');
        $AttributeGroup->name = $request->getParam('name');
        $id = $AttributeGroup->Create();
        if ($id) {
            unset($_COOKIE['language']);
            $this->container->flash->addMessage('success', 'Attribuutgroep is toegevoegd');
            UserActivity::Record('Create', $id, 'AttributeGroups');
            return $response->withRedirect($this->router->pathFor('AttributeGroups.GetSingle', ['id' => $id]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('AttributeGroups.GetAdd'));
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////                      Attributes                              //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * attributesGetIndex function
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return void rendered template with attributes
     */
    public function attributesGetIndex($request, $response, $args)
    {
        if ($request->getParam('attribute_group_id') && $request->getParam('attribute_group_id') != '') {
            $attribbute_group_id = $request->getParam('attribute_group_id');
            $Attribute = Attribute::AllinAttributeGroup($attribbute_group_id);
        } else {
            $Attribute = Attribute::All();
        }
        return $this->view->render($response, 'attributes/index.tpl', ['active_menu' => 'products', 'page_title' => 'Attributen', 'attributes' => $Attribute]);
    }

    /**************************************************************************************************************************************************
     **************************************************************(Attribute s Get Data)*********************************************************
     **************************************************************************************************************************************************/
    public function attributesGetData($request, $response, $args)
    {
        if ($request->getParam('attribute_group_id') && $request->getParam('attribute_group_id') != '') {
            $attribbute_group_id = $request->getParam('attribute_group_id');
            $Attribute = Attribute::AllinAttributeGroup($attribbute_group_id);
        } else {
            $Attribute = Attribute::All();
        }
        $returndata = [
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($Attribute),
            'recordsFiltered' => count($Attribute),
            'data' => $Attribute
        ];
        return json_encode($returndata);
    }

    /**************************************************************************************************************************************************
     ************************************************************(Attribute s Get Single)*********************************************************
     **************************************************************************************************************************************************/
    public function attributesGetSingle($request, $response, $args)
    {
        $validation = $this->validator->validateGet($args, [
            'id' => v::notEmpty(),
        ]);
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Attributes.GetIndex'));
        }

        $Attribute = Attribute::Find($args['id']);
        if (!$Attribute) {
            throw new NotFoundException($request, $response);
        }

        $AttributeGroups = AttributeGroup::All();
        return $this->view->render($response, 'attributes/attribute_single.tpl', [
            'Attribute' => $Attribute,
            'AttributeGroups' => $AttributeGroups, 'active_menu' => 'products',
            'page_title' => $Attribute['name'] . ' - ' . $Attribute['id']
        ]);
    }

    /**************************************************************************************************************************************************
     ************************************************************(Attribute s Get Single)*********************************************************
     **************************************************************************************************************************************************/
    public function attributesUpdateSingle($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'name' => v::notEmpty(),
            'attribute_group' => v::notEmpty(),
        ]);

        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            if ($request->getParam('id')) {
                return $response->withRedirect($this->router->pathFor('Attributes.GetSingle', ['id' => $request->getParam('id')]));
            } else {
                return $response->withRedirect($this->router->pathFor('Attributes.GetIndex'));
            }
        }
        $Attribute = new Attribute;
        $Attribute->id = $request->getParam('id');
        $Attribute->attribute_group_id = $request->getParam('attribute_group');
        $Attribute->name = $request->getParam('name');
        if ($Attribute->Update()) {
            UserActivity::Record('Update', $request->getParam('id'), 'Attribute');
            $this->container->flash->addMessage('success', 'Attribuut is bijgewerkt');
            return $response->withRedirect($this->router->pathFor('Attributes.GetSingle', ['id' => $request->getParam('id')]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Attributes.GetSingle', ['id' => $request->getParam('id')]));
        }
    }

    /**************************************************************************************************************************************************
     ************************************************************(Attribute s Get Add)************************************************************
     **************************************************************************************************************************************************/
    public function attributesAddGet($request, $response, $args)
    {
        LangToDefault();
        $id = $args['id'];
        $AttributeGroup = AttributeGroup::Find($id);
        return $this->view->render($response, 'attributes/attribute_add.tpl', [
            'AttributeGroup' => $AttributeGroup,
            'active_menu' => 'products', 'page_title' => 'Attribuut toevoegen aan ' . $AttributeGroup['name']
        ]);
    }

    /**************************************************************************************************************************************************
     ****************************************************************(Attribute Delete)****************************************************************
     **************************************************************************************************************************************************/
    public function attributesDelete($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'Attribute data klopt niet !!']);
        }
        $check = DB::query('delete from attribute where id =%i', $request->getParam('id'));
        if (!$check) {
            return $response->withJson(['status' => 'false', 'msg' => 'Heeft niet verwijderd!!']);
        }
        return $response->withJson(['status' => 'true', 'msg' => 'Heeft verwijderd']);
    }

    /**************************************************************************************************************************************************
     ************************************************************(Attribute s Post New)***********************************************************
     **************************************************************************************************************************************************/
    public function attributesAddPost($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'name' => v::notEmpty(),
            'attribute_group_id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Attributes.GetAdd'));
        }
        $Attribute = new Attribute;
        $Attribute->attribute_group_id = $request->getParam('attribute_group_id');
        $Attribute->name = $request->getParam('name');
        $id = $Attribute->Create();
        if ($id) {
            unset($_COOKIE['language']);
            UserActivity::Record('Create', $id, 'Attribute');
            $this->container->flash->addMessage('success', 'Attribuutgroep is toegevoegd');
            return $response->withRedirect($this->router->pathFor('AttributeGroups.GetSingle', ['id' => $request->getParam('attribute_group_id')]));
        } else {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('Attributes.GetAdd', ['id' => $request->getParam('attribute_group_id')]));
        }
    }
}
