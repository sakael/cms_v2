<?php

use Respect\Validation\Validator as v;
use Slim\App;
use Slim\Flash;
use Slim\Views;
use App\Auth\Auth;
use App\Validation\Validator;
use App\Classes\Note;
use Twig\TwigFilter;
use Twig\TwigFunction;

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}
date_default_timezone_set('Europe/Amsterdam');

require __DIR__ . '/../config.inc.php';



//custom validations
v::with('App\\Validation\\Rules\\');

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new App($settings);

// Get container
$container = $app->getContainer();

// Register provider
//Flash Message

$container['flash'] = function () {
    return new Flash\Messages();
};

//Auth

$container['auth'] = function ($cotainer) {
    return new Auth;
};

$container['notes'] = function ($cotainer) {
    return new Note;
};


$container['view'] = function ($container) {
    $view = new Views\Twig(__DIR__ . '/../templates', [
        //'cache' => __DIR__.'/../cache'    // cache is not needed for tpl files
        'debug' => true,
    ]);
    $view->addExtension(new Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));
    $view->addExtension(new \Twig\Extension\DebugExtension());


    $view->getEnvironment()->addGlobal('flash', $container->flash);

    $view->getEnvironment()->addGlobal('notes', [
        'notes_count' => $container->notes->CountNotDone()
    ]);
    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);
    $view->getEnvironment()->addGlobal('user', $container->auth);

    if (language) {
        $view->getEnvironment()->addGlobal('language', language);
    }
    if (IMAGE_PATH) {
        $view->getEnvironment()->addGlobal('IMAGE_PATH', IMAGE_PATH);
    }

    if (SITE_URL) {
        $view->getEnvironment()->addGlobal('SITE_URL', SITE_URL);
    }
    $view->getEnvironment()->addGlobal('presecret', 'gg39^*&T(#@ewb^(#');


    $function = new TwigFunction('getThumb', function ($image, $thumb) {
        global $GLOBALS;
        $path=dirname($image);
        $imageName = pathinfo($image, PATHINFO_FILENAME);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $imageThumb=$path.'/thumbs/'.$imageName.'_'.$thumb.'.'.$ext;
        $check = $GLOBALS['s3']->doesObjectExist('cms', $imageThumb);
        if (!$check) {
            return $image;
        }
        return $imageThumb;
    });
    $view->getEnvironment()->addFunction($function);

    $function = new TwigFunction('dd', function () {
        $args = func_get_args();
        return call_user_func_array('dump', $args);
    });
    $view->getEnvironment()->addFunction($function);

    $filter = new TwigFilter('md5', function ($string) {
        return md5($string);
    });
    
    $view->getEnvironment()->addFilter($filter);
    return $view;
};


// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register Controllers
require __DIR__ . '/../src/controllers.php';

// error_handler
require __DIR__ . '/../src/error_handler.php';

//require __DIR__ . '/../app/classes/Auth.php';

// Register routes
foreach (glob(__DIR__ . '/../routes/*.route.php') as $file) {
    require $file;
}

//Validation
$container['validator'] = function ($cotainer) {
    return new Validator;
};

// Run app
$app->run();
