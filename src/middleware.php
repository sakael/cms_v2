<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
//$app->add(new \App\Middleware\AuthMiddleware($container));
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
