<?php

namespace App\Middleware;

class ValidationErrorsMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!isset($_SESSION['_errors'])) {
            $_SESSION['_errors'] = '';
        }
        $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['_errors']);
        unset($_SESSION['_errors']);
        $response = $next($request, $response);
        return $response;
    }
}
