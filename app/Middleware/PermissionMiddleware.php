<?php

namespace App\Middleware;

use DB;

class PermissionMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $user = $this->auth->user();
        $route = $request->getAttribute('route');
        if ($user) {
            if ($user['super'] != 1) {
                $user_perm = DB::queryFirstRow("SELECT allow FROM user_route WHERE user_id=%i and route_name=%s", $user['id'], $route->getName());
                if ($user_perm) {

                    if ($user_perm['allow'] != true) {
                        if ($request->isXhr()) {
                            echo (json_encode(['status' => 'false', 'msg' => 'Sorry You are not allowed  to do these action on that page.<br>Please, ask the administration office for a permission on this route (' . $route->getName() . ')']));
                            die();
                        }
                        $this->container->flash->addMessage('error', 'Sorry, u mag deze actie op die pagina niet uitvoeren');
                        $this->container->flash->addMessage('info', 'Vraag het administratiekantoor om toestemming voor (' . $route->getName() . ')');
                        return $response->withRedirect($this->container->router->pathFor('home'));
                    }
                } else {
                    if ($request->isXhr()) {
                        echo (json_encode(["status" => "false", "msg" => "Sorry You are not allowed  to do these action on that page.<br>Please, ask the administration office for a permission on this route (" . $route->getName() . ")"]));
                        die();
                    }
                    $this->container->flash->addMessage('error', 'Sorry, u mag deze actie op die pagina niet uitvoeren');
                    $this->container->flash->addMessage('info', 'Vraag het administratiekantoor om toestemming voor (' . $route->getName() . ')');
                    return $response->withRedirect($this->container->router->pathFor('home'));
                }
            }
        }
        $response = $next($request, $response);
        return $response;
    }
}
