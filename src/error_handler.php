<?php
/*
$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        return $container['view']->render($response->withStatus(990), 'errors/990.tpl', [
            "error" => "foobar2000"
        ]);
    };
};


$container['notAllowedHandler'] = function ($container) {
    return function ($request, $response, $methods) use ($container) {
        return $container['view']->render($response->withStatus(500), 'errors/not_allowed.tpl', [
            "error" => "foobar2000"
        ]);
    };
};

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['view']->render($response->withStatus(404), 'errors/404.tpl', [
            "error" => "foobar2000"
        ]);
    };
};
*/