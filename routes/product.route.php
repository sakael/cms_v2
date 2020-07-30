<?php

use App\Middleware\PermissionMiddleware as Permission;
use App\Middleware\AuthMiddleware as Auth;

$app->group('/products', function () use ($app,  $container) {
  $app->get('', 'ProductController:index')->setName('ProductsIndex');
  $app->get('/data', 'ProductController:getAll')->setName('ProductsGetAll');
  $app->get('/data/changes', 'ProductController:getLatestChangedProducts')->setName('ProductsGetAllChnages');
  //writeOff
  $app->get('/writeoff', 'ProductController:writeoffGet')->setName('Products.Writeoff.GetIndex');
  $app->get('/writeoff/data', 'ProductController:writeoffGetData')->setName('Products.Writeoff.GetData');
})->add(new Auth($container))->add(new Permission($container));

$app->group('/product', function () use ($app) {

  $app->get('/{id}', 'ProductController:getProduct')->setName('ProductGet');
  $app->put('/{id}', 'ProductController:updateProduct')->setName('ProductUpdate');

  $app->get('/new/add', 'ProductController:productAddGet')->setName('Product.ProductAddGet');
  $app->post('/new/add', 'ProductController:productAddPost')->setName('Product.ProductAddPost');

  $app->post('/image/add/{id}', 'ProductController:addingImage')->setName('ProductImageAdd');
  $app->put('/images/sort/{id}', 'ProductController:sortingImages')->setName('ProductImageSort');

  // DataTables Child
  $app->get('/{id}/children', 'ProductController:getChildren')->setName('ProductGetChildren');
  // DataTables Child Get Ean
  $app->get('/children/ean', 'ProductController:getChildrenEan')->setName('Product.ProductGetChildrenEan');
  // DataTables Generate Types
  $app->get('/{id}/types/generate', 'ProductController:getTypesGenerate')->setName('ProductGetTypesGenerate');

  // Price update
  $app->put('/price/update', 'ProductController:priceUpdate')->setName('ProductPriceUpdate');

  // Measurements update
  $app->put('/measurements/update', 'ProductController:measurementsUpdate')->setName('ProductMeasurementsUpdate');

  // Measurements update
  $app->put('/other-info/update', 'ProductController:otherInfoUpdate')->setName('ProductOtherInfoUpdate');

  // Urls update
  $app->put('/urls/update', 'ProductController:urlsUpdate')->setName('Product.UrlsUpdate');
  // Urls get
  $app->get('/urls/get', 'ProductController:getProductUrls')->setName('Product.UrlsGet');

  // Childs update
  $app->post('/type/add', 'ProductController:productChildsUpdate')->setName('Product.ProductChildsUpdate');
  // Childs Remove
  $app->delete('/type/delete', 'ProductController:productChildsRemove')->setName('Product.ProductChildsDelete');

  // Urls products list and product combos
  $app->get('/combos/get', 'ProductController:getProductCombos')->setName('Product.CombosGet');
  // update product combos
  $app->post('/combos/update', 'ProductController:combosUpdate')->setName('Product.CombosUpdate');
  // combos Remove
  $app->delete('/combos/delete', 'ProductController:combosDelete')->setName('Product.CombosDelete');
  // update product b2b info
  $app->post('/b_2_b/update', 'ProductController:b_2_bUpdate')->setName('Product.B2bUpdate');
})->add(new Auth($container))->add(new Permission($container));
