<?php

use App\Middleware\PermissionMiddleware as Permission;
use App\Middleware\AuthMiddleware as Auth;

$app->group('/msp', function () use ($app) {
    $app->get('/status/{transaction-id}', 'MultiSafepayController:getStatus')->setName('MSP.GetStatus');
})->add(new Auth($container))->add(new Permission($container));
