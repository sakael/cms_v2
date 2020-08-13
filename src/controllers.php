<?php

$container['ProductController'] = function ($container) {
    return new App\Controllers\ProductController($container);
};
$container['AuthController'] = function ($container) {
    return new App\Controllers\Auth\AuthController($container);
};
$container['GeneralController'] = function ($container) {
    return new App\Controllers\GeneralController($container);
};
$container['SuperController'] = function ($container) {
    return new App\Controllers\SuperController($container);
};
$container['ProductInfoController'] = function ($container) {
    return new App\Controllers\ProductInfoController($container);
};
$container['AttributeController'] = function ($container) {
    return new App\Controllers\AttributeController($container);
};
$container['ProductBrandController'] = function ($container) {
    return new App\Controllers\ProductBrandController($container);
};
$container['ProductTypeController'] = function ($container) {
    return new App\Controllers\ProductTypeController($container);
};
$container['OrderController'] = function ($container) {
    return new App\Controllers\OrderController($container);
};
$container['NoteController'] = function ($container) {
    return new App\Controllers\NoteController($container);
};
$container['LabelController'] = function ($container) {
    return new App\Controllers\LabelController($container);
};
$container['CategoryController'] = function ($container) {
    return new App\Controllers\CategoryController($container);
};
$container['MultiSafepayController'] = function ($container) {
    return new App\Controllers\MultiSafepayController($container);
};
