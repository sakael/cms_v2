<?php

use App\Middleware\PermissionMiddleware as Permission;
use App\Middleware\AuthMiddleware as Auth;

$app->group('/orders', function () use ($app, $container) {
    $app->get('', 'OrderController:ordersGetIndex')->setName('OrdersIndex');
    $app->get('/other', 'OrderController:ordersGetOtherIndex')->setName('OrdersOtherIndex');

    // Bol.com  @ line 280 OC
    $app->get('/bol', 'OrderController:ordersGetBol')->setName('OrdersBol');

    //datatable requests
    $app->get('/new/data', 'OrderController:getAllNew')->setName('OrdersNewGetAll');
    $app->get('/claimed/data', 'OrderController:getAllClaimed')->setName('OrdersClaimedGetAll');
    $app->get('/ready_for_shipping/data', 'OrderController:getAllReadyForShipping')->setName('OrdersReadyForShippingGetAll');
    $app->get('/return/data', 'OrderController:getAllReturn')->setName('OrdersReturnGetAll');
    $app->get('/wait_supplier/data', 'OrderController:getAllWaitSupplier')->setName('OrdersWaitSupplierGetAll');
    $app->get('/wait_external_supplier/data', 'OrderController:getAllWaitExternalSupplier')->setName('OrdersWaitExternalSupplierGetAll');
    $app->get('/wait_customer/data', 'OrderController:getAllWaitCustomer')->setName('OrdersWaitCustomerGetAll');
    $app->get('/credit/data', 'OrderController:getAllCredit')->setName('OrdersCreditGetAll');


    //Single requests
    $app->group('/order', function () use ($app,  $container) {
        $app->get('/{id:[0-9]+}', 'OrderController:getSingle')->setName('OrdersGetSingle');
        $app->get('/{id:[0-9]+}/edit', 'OrderController:getSingleEdit')->setName('OrdersGetSingleEdit');
        $app->get('/popup/{id}', 'OrderController:getSingle')->setName('OrdersGetSinglePopup');
        $app->put('/update', 'OrderController:postUpdateSingle')->setName('OrdersPostUpdateSingle');
        $app->put('/order_items/update', 'OrderController:orderItemsUpdate')->setName('OrdersOrderItemsPostUpdateSingle');
        $app->post('/order_items/delete', 'OrderController:orderItemDelete')->setName('OrdersOrderItemsPostDeleteSingle');
        $app->post('/claim', 'OrderController:claimSingle')->setName('OrdersClaimSingle');
        $app->post('/claim-orders-by-product', 'OrderController:claimOrdersByProduct')->setName('OrdersClaimByProductSingle');
        $app->put('/status/update', 'OrderController:orderStatusUpdate')->setName('OrdersOrderStatusPostUpdate');
        $app->put('/status/manually_update', 'OrderController:orderStatusUpdate')->setName('OrdersOrderStatusPostUpdateManually');
        //Adding new
        $app->get('/add', 'OrderController:addNew')->setName('Orders.GetNew');
        $app->post('/add/post', 'OrderController:addNewPost')->setName('Orders.PostNew');
        $app->post('/duplicate', 'OrderController:orderDuplicate')->setName('OrdersOrderDuplicatePost');
        $app->post('/register-payment', 'OrderController:registerPayment')->setName('Orders.RegisterPayment');
    });


    //claiming routes
    $app->post('/claim/per-stelling', 'OrderController:orderClaimByStelling')->setName('OrdersClaimPerStelling');
    $app->get('/claim/per-productgroup', 'OrderController:orderClaimByProductGroup')->setName('OrdersClaimPerProductGroup');
    $app->post('/claim/per-buitenland', 'OrderController:orderClaimByBuitenland')->setName('OrdersClaimPerBuitenland');
    $app->post('/claim/per-b2b', 'OrderController:orderClaimByb2b')->setName('OrdersClaimB2B');

    //Print routes
    $app->get('/print/pick-list', 'LabelController:printPickList')->setName('OrdersPrintPickList');
    $app->get('/print/box', 'LabelController:printLabel')->setName('OrdersPrintBox');
    $app->get('/print/parcel', 'LabelController:printLabelParcel')->setName('OrdersPrintParcel');
    $app->get('/print/post', 'LabelController:printLabel')->setName('OrdersPrintPost');
    // $app->post('/print/post-box','LabelController:PrintPostBox')->setName('OrdersPrintPostBox');

    //Barcode routes
    $app->get('/barcode', 'GeneralController:barcodeGet')->setName('BarcodeGet');
    $app->get('/barcode/read', 'GeneralController:barcodeRead')->setName('BarcodeRead');

    //PostCode Contoller routes
    $app->get('/postcode-controller', 'OrderController:checkPostcode')->setName('Orders.PostcodeController');

    //Pakbon Index
    $app->get('/pakbon', 'OrderController:pakbonIndex')->setName('Orders.Pakbon.Index');
    //Pakbon notify by dhl
    $app->get('/pakbon/dhl/notify', 'OrderController:pakbonDhlNotify')->setName('Orders.Dhl.Notify');
    ////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////
    ///////////////            Import orders           /////////////////////
    ////////////////////////////////////////////////////////////////////////
})->add(new Auth($container))->add(new Permission($container));

$app->get('/factuur/order/{id:[0-9]+}', 'GeneralController:pdfGeneratorOrder')->setName('OrderGetInvoice');
$app->get('/orders/import-from-webshop', 'OrderController:importOrders')->setName('ImportFromWebshop.Get');
