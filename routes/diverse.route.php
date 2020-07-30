<?php

use App\Middleware\PermissionMiddleware as Permission;
use App\Middleware\AuthMiddleware as Auth;
use App\Middleware\SuperMiddleware as Super;
use Slim\View\Twig as View;

$app->get('/camera', function ($request, $response, $args) use ($app) {
  return $this->view->render($response, 'diverse/camera.tpl', ['active_menu' => 'camera']);
})->setName('camera.index')->add(new Auth($container))->add(new Permission($container));

//B2B Part
////////////////////////////////////////////////////////////////////////
/////////////////          Customer of i12Cover        /////////////////
////////////////////////////////////////////////////////////////////////

$app->group('/i12_customers', function () use ($app,  $container) {
  $app->get('', 'GeneralController:i12CustomersGetIndex')->setName('i12Customers.GetIndex');
  $app->get('/all/data', 'GeneralController:i12CustomersGetIndexData')->setName('i12Customers.GetData');
  $app->get('/customer/{id}', 'GeneralController:i12CustomersGetSingle')->setName('i12Customers.GetSingle');
  $app->put('/customer/update', 'GeneralController:i12CustomersUpdateSingle')->setName('i12Customers.UpdateSingle');
  $app->get('/add/customer', 'GeneralController:i12CustomersAddGet')->setName('i12Customers.GetAdd');
  $app->post('/add/customer', 'GeneralController:i12CustomersAddPost')->setName('i12Customers.PostAdd');
  $app->put('/customer/remove', 'GeneralController:i12CustomersRemoveCustomer')->setName('i12Customers.CustomerRemove');
  $app->post('/customer/note/add', 'GeneralController:i12CustomersAddPostNote')->setName('i12Customers.PostAddNote');
  $app->get('/invoice/data', 'GeneralController:i12CustomersGetInvoiceData')->setName('i12Customers.Invoice.GetData');
})->add(new Auth($container))->add(new Permission($container));

////////////////////////////////////////////////////////////////////////
/////////////////                Users                 /////////////////
////////////////////////////////////////////////////////////////////////
$app->group('/users', function () use ($app,  $container) {

  $app->get('', 'SuperController:usersIndex')->setName('users.index');
  $app->get('/data', 'SuperController:usersGetAll')->setName('users.data');
  $app->get('/user/{id}', 'SuperController:getUser')->setName('users.userGet');
  $app->put('/user/update', 'SuperController:updateUser')->setName('users.userPut');
  $app->post('/user/delete', 'SuperController:deleteUser')->setName('users.userDelete');
  $app->get('/saveroutes', 'SuperController:saveRoutes')->setName('routes.save');
  $app->get('/activities', 'SuperController:getUsersAactivities')->setName('users.userGetActivities');
  $app->get('/activities/data', 'SuperController:getUsersAactivitiesData')->setName('users.userGetActivitiesData');
})->add(new Auth($container))->add(new Super($container))->add(new Permission($container));

////////////////////////////////////////////////////////////////////////
/////////////////                Notes                 /////////////////
////////////////////////////////////////////////////////////////////////
$app->group('/notes', function () use ($app,  $container) {
  $app->get('', 'NoteController:noteAll')->setName('notes.all');
  $app->get('/add', 'NoteController:noteAddNew')->setName('notes.note.add');
  $app->put('/note/update/status', 'NoteController:noteUpdateStatus')->setName('notes.note.update.status');
  $app->get('/get_all_from_notes', 'NoteController:noteAllFromData')->setName('notes.GetAllFrom');
  $app->get('/get_all_to_notes', 'NoteController:noteAllToData')->setName('notes.GetAllSent');
  $app->get('/get_all_selected_Models', 'NoteController:getModels')->setName('notes.GetModels');
})->add(new Auth($container));

////////////////////////////////////////////////////////////////////////
/////////////////                Status                 ////////////////
////////////////////////////////////////////////////////////////////////
$app->group('/status', function () use ($app,  $container) {
  $app->get('', 'GeneralController:statusIndex')->setName('status.index');
  $app->get('/data', 'GeneralController:statusAll')->setName('status.data');
  $app->get('/single/{id}', 'GeneralController:statusGetSingle')->setName('status.Single');
  $app->put('/single/update', 'GeneralController:statusUpdateSingle')->setName('status.UpdateSingle');
})->add(new Auth($container))->add(new Permission($container));

////////////////////////////////////////////////////////////////////////
/////////////////               diverse                /////////////////
////////////////////////////////////////////////////////////////////////

$app->group('/diverse', function () use ($app,  $container) {
  //SentPackages
  $app->get('/sent-packages', 'GeneralController:sentPackagesGet')->setName('SentPackages.GetIndex');
  $app->get('/sent-packages/data', 'GeneralController:sentPackagesGetData')->setName('SentPackages.GetData');
})->add(new Auth($container))->add(new Permission($container));



////////////////////////////////////////////////////////////////////////
/////////////////                Coupons               /////////////////
////////////////////////////////////////////////////////////////////////

$app->group('/coupons', function () use ($app,  $container) {
  $app->get('', 'GeneralController:CouponsGetIndex')->setName('Coupons.GetIndex');
  $app->get('/all/data', 'GeneralController:CouponsGetIndexData')->setName('Coupons.GetData');
  $app->get('/coupon/{id}', 'GeneralController:CouponsGetSingle')->setName('Coupons.GetSingle');
  $app->put('/coupon/update', 'GeneralController:CouponsUpdateSingle')->setName('Coupons.UpdateSingle');
  $app->get('/add/coupon', 'GeneralController:CouponsAddGet')->setName('Coupons.GetAdd');
  $app->post('/add/coupon', 'GeneralController:CouponsAddPost')->setName('Coupons.PostAdd');
  $app->put('/coupon/remove', 'GeneralController:CouponsRemoveCoupon')->setName('Coupons.CustomerRemove');
})->add(new Auth($container))->add(new Permission($container));
