<?php

namespace App\Middleware;

use DB;

class AuthMiddleware extends Middleware
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
    public function __invoke($request, $response, $next)
    {

        if (!$this->auth->check()) {
            if ($request->isXhr()) {
                $this->container->flash->addMessage('error', 'Log alsjeblieft nogmaals in');
                return $response->withJson(['status' => 'false', 'msg' => 'Log alsjeblieft <a sty href="' . $this->container->router->pathFor('auth.login') . '"><u> nogmaals in</u></a>']);
            } else {
                $this->container->flash->addMessage('error', 'Log alsjeblieft nogmaals in');
                $route = $request->getAttribute('route');
                $_SESSION['trying_to_access'] = $request->getUri()->getPath();
                return $response->withRedirect($this->container->router->pathFor('auth.login'));
            }
        }
        $response = $next($request, $response);
        return $response;
    }
}
