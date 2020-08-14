<?php

use App\Middleware\PermissionMiddleware as Permission;
use App\Middleware\AuthMiddleware as Auth;

$app->group('/product-info', function () use ($app) {

    ////////////////////////////////////////////////////////////////////////
    ///////////////            Attributes Groups           /////////////////
    ////////////////////////////////////////////////////////////////////////
    $app->get('/attribute-groups', 'AttributeController:attributeGroupsGetIndex')->setName('AttributeGroups.GetIndex');
    $app->get('/attribute-groups/data', 'AttributeController:attributeGroupsGetData')->setName('AttributeGroups.GetData');

    $app->get('/attribute-groups/attribute/{id}', 'AttributeController:attributeGroupsGetSingle')->setName('AttributeGroups.GetSingle');
    $app->put('/attribute-groups/attribute/update', 'AttributeController:attributeGroupsUpdateSingle')->setName('AttributeGroups.UpdateSingle');

    $app->get('/attribute-groups/add/attribute', 'AttributeController:attributeGroupsAddGet')->setName('AttributeGroups.GetAdd');
    $app->post('/attribute-groups/add/attribute', 'AttributeController:attributeGroupsAddPost')->setName('AttributeGroups.PostAdd');

    ////////////////////////////////////////////////////////////////////////
    ///////////////               Attributes               /////////////////
    ////////////////////////////////////////////////////////////////////////
    $app->get('/attributes', 'AttributeController:attributesGetIndex')->setName('Attributes.GetIndex');
    $app->get('/attributes/data', 'AttributeController:attributesGetData')->setName('Attributes.GetData');

    $app->get('/attributes/attribute/{id}', 'AttributeController:attributesGetSingle')->setName('Attributes.GetSingle');
    $app->put('/attributes/attribute/update', 'AttributeController:attributesUpdateSingle')->setName('Attributes.UpdateSingle');

    $app->get('/attributes/add/attribute/{id}', 'AttributeController:attributesAddGet')->setName('Attributes.GetAdd');
    $app->post('/attributes/add/attribute/post', 'AttributeController:attributesAddPost')->setName('Attributes.PostAdd');
    $app->delete('/attributes/delete/attribute', 'AttributeController:attributesDelete')->setName('Attributes.Delete');

    ////////////////////////////////////////////////////////////////////////
    /////////////////                Brands                /////////////////
    ////////////////////////////////////////////////////////////////////////

    $app->get('/brands', 'ProductBrandController:brandsGetIndex')->setName('Brands.GetIndex');
    
    $app->get('/brands/brand/{id}', 'ProductBrandController:brandsGetSingle')->setName('Brands.GetSingle');
    $app->get('/brands/brand/types/data', 'ProductBrandController:brandsGetTypesInBrand')->setName('Brands.GetTypesInBrandDataTable');
    $app->put('/brands/brand/{id}', 'ProductBrandController:brandsUpdateSingle')->setName('Brands.UpdateSingle');

    $app->get('/brands/add/brand', 'ProductBrandController:brandsAddGet')->setName('Brands.GetAdd');
    $app->post('/brands/add/brand', 'ProductBrandController:brandsAddPost')->setName('Brands.PostAdd');

    $app->put('/brands/type/remove', 'ProductBrandController:brandsRemoveType')->setName('Brands.TypeRemove');

    ////////////////////////////////////////////////////////////////////////
    /////////////////                 Types                /////////////////
    ////////////////////////////////////////////////////////////////////////

    $app->get('/types', 'ProductTypeController:typesGetIndex')->setName('Types.GetIndex');
    $app->get('/types/data', 'ProductTypeController:typesGetData')->setName('Types.GetData');

    $app->get('/types/type/{id}', 'ProductTypeController:typesGetSingle')->setName('Types.GetSingle');
    $app->get('/types/type/types/data', 'ProductTypeController:typesGetTypesInType')->setName('Types.GetTypesInType');
    $app->put('/types/type/{id}', 'ProductTypeController:typesUpdateSingle')->setName('Types.UpdateSingle');

    $app->get('/brands/add/type/{id}', 'ProductTypeController:typesAddGet')->setName('Brands.GetAddType');

    $app->get('/types/add/type', 'ProductTypeController:typesAddGet')->setName('Types.GetAdd');
    $app->post('/types/add/type', 'ProductTypeController:typesAddPost')->setName('Types.PostAdd');
    $app->get('/types/in/brand', 'ProductTypeController:brandsGetTypesInBrand')->setName('Types.GetTypesInBrand');

    $app->post('/types/search/products', 'ProductTypeController:typesGetProductsIdsAndNames')->setName('Types.ProductsSearch');

    $app->delete('/types/delete', 'ProductTypeController:typesDeleteSingle')->setName('Types.Delete');

    //Categories
    $app->post('/categories/update', 'ProductTypeController:categoryTypeUpdate')->setName('CategoriesType.UpdatePost');
    $app->get('/categories/get', 'CategoryController:categoryGetAll')->setName('CategoriesType.GetAll');
    
    // DataTables Generate Types
    $app->get('/{id}/types/generate', 'ProductTypeController:getProductsGenerate')->setName('TypeGetProductsGenerate');

    ////////////////////////////////////////////////////////////////////////
    /////////////////               Categories             /////////////////
    ////////////////////////////////////////////////////////////////////////

    $app->get('/categories', 'CategoryController:categoriesGetIndex')->setName('Categories.GetIndex');
    $app->get('/categories/data', 'CategoryController:categoriesGetData')->setName('Categories.GetData');

    $app->get('/categories/category/{id}', 'CategoryController:categoriesGetSingle')->setName('Categories.GetSingle');
    $app->put('/categories/category/update', 'CategoryController:categoriesUpdateSingle')->setName('Categories.UpdateSingle');

    $app->get('/categories/add/category', 'CategoryController:categoriesAddGet')->setName('Categories.GetAdd');
    $app->post('/categories/add/category', 'CategoryController:categoriesAddPost')->setName('Categories.PostAdd');

    $app->put('/categories/type/remove', 'CategoryController:categoriesRemoveType')->setName('Categories.TypeRemove');

    ////////////////////////////////////////////////////////////////////////
    ///////////////            Variations Groups           /////////////////
    ////////////////////////////////////////////////////////////////////////
    $app->get('/get-variations', 'ProductInfoController:variationsGroupsGet')->setName('VariationsGroups.Get');
    $app->post('/update-product-variations', 'ProductInfoController:productVariationsPost')
    ->setName('ProductVariations.Post');

    ////////////////////////////////////////////////////////////////////////
    ///////////////                  Reviews               /////////////////
    ////////////////////////////////////////////////////////////////////////
    $app->get('/reviews', 'ProductInfoController:getNewReviews')->setName('Reviews.Get.New');
    $app->get('/reviews/data/all', 'ProductInfoController:getAllReviewsData')->setName('Reviews.Get.AllData');
    $app->get('/reviews/data', 'ProductInfoController:getNewReviewsData')->setName('Reviews.Get.NewData');
    $app->put('/reviews/review/update', 'ProductInfoController:reviewsUpdateSingle')->setName('Reviews.Update.single');
})->add(new Auth($container))->add(new Permission($container));
