<?php
// DIC configuration
$container = $app->getContainer();
// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};
// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
// Cache
$container['cache'] = function () {
    return new Phpfastcache\Helper\Psr16Adapter('redis', new Phpfastcache\Drivers\Redis\Config([
        'host' => '159.65.206.219', //Default value
        'port' => 6379, //Default value
        'password' => 'NFzxBzvzVVJaUqKpbTgC6I/0f79r1U7hMASHKqanxCm35FMWvWsZf9oU6yfYVuT+XB0e7zzctOnoUWwQ', //Default value
        'database' => 4, //Default value
    ]));
};
