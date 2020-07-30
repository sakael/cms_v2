<?php

use App\Middleware\PermissionMiddleware as Permission;
use App\Middleware\AuthMiddleware as Auth;
use App\Middleware\SuperMiddleware as Super;

/**************************************************************************************************************************************************
 **********************************************************( General Function )********************************************************************
 **************************************************************************************************************************************************/
$app->get('/', 'GeneralController:homeIndex')->setName('home')->add(new Auth($container));
$app->get('/products/product/byid/find/{id}', 'GeneralController:getProductDataById')->setName('products.getProductDataById')->add(new Auth($container))->add(new Permission($container)); //GetProductDataById

$app->group('', function () use ($app,  $container) {
    /**************************************************************************************************************************************************
     **************************************************************( Account )*************************************************************************
     **************************************************************************************************************************************************/
    $app->get('/account', 'AuthController:getAccount')->setName('auth.account');
    $app->post('/account', 'AuthController:postAccount')->setName('auth.account.post');;
})->add(new Auth($container))->add(new Permission($container));

$app->get('/logout', 'AuthController:getSignOut')->setName('auth.logout');
$app->get('/signup', 'AuthController:getSignUp')->setName('auth.signup')->add(new Auth($container))->add(new Super($container));
$app->post('/signup', 'AuthController:postSignUp')->setName('auth.signup.post')->add(new Auth($container))->add(new Super($container));
$app->get('/login', 'AuthController:getSignIn')->setName('auth.login');
$app->post('/login', 'AuthController:postSignIn')->setName('auth.login.post');

/**************************************************************************************************************************************************
 ****************************************************( General Controller )************************************************************************
 **************************************************************************************************************************************************/
$app->group('/general', function () use ($app,  $container) {
    $app->get('/livesearch-main', 'GeneralController:mainLiveSearch')->setName('GeneralMainLiveSearch');
})->add(new Auth($container))->add(new Permission($container));
