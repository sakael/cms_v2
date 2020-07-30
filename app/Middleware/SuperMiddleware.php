<?php

namespace App\Middleware;

use DB;

class SuperMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!$this->auth->super()) {
            $this->container->flash->addMessage('error', 'Sorry, you do not have permission for this action.');
            return $response->withRedirect($this->container->router->pathFor('home'));
        }
        $response = $next($request, $response);
        return $response;
    }
}
