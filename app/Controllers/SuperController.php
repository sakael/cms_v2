<?php

namespace App\Controllers;

use Slim\View\Twig as View;
use DB;
use Respect\Validation\Validator as v;
use Carbon\Carbon;

class SuperController extends Controller
{
    /**
     * Get all users page function
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return mix view
     */
    public function usersIndex($request, $response, $args)
    {
        $users = DB::query("SELECT id,name,lastname,super,created_at,email FROM users");
        return $this->view->render($response, 'users/index.tpl', ['active_menu' => 'users','users' => $users,'page_title' => 'Gebruikers']);
    }
    
    /**
     * deleteUser function
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return array json
     */
    public function deleteUser($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
        ]);
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            return $response->withJson(['status' => 'false', 'msg' => 'gebruiker is niet verwijderd']);
        }
        if (DB::delete('users', "id=%i", $request->getParam('id'))) {
            return $response->withJson(['status' => 'true', 'msg' => 'gebruiker is verwijderd']);
        }
    }
    /**
     * getUser get user page function
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return mix view
     */
    public function getUser($request, $response, $args)
    {
        $validation = $this->validator->validateGet($args, [
            'id' => v::notEmpty(),
        ]);
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('users.index'));
        }
        $user = DB::queryFirstRow("SELECT id,name,lastname,email,super FROM users WHERE id=%i", $args['id']);
        if (!$user) {
            $this->container->flash->addMessage('error', 'Geen gebruiker met deze ID');
            return $response->withRedirect($this->router->pathFor('users.index'));
        } else {
            $routes = DB::query("select * from routes order by route_name");
            $routes_user = DB::query("select routes.*, user_route.user_id as pivot_user_id, user_route.route_id as pivot_route_id, user_route.allow as pivot_allow from routes inner join user_route on routes.id = user_route.route_id where user_route.user_id =%i and user_id = %i
            order by routes.route_name
            ", $user['id'], $user['id']);

            return $this->view->render($response, 'users/edit.tpl', ['user' => $user, 'routes' => $routes,
            'routes_user' => $routes_user, 'active_menu' => 'users',
            'page_title' => $user['name'] . ' - ' . $user['id']]);
        }
    }
    /**
     * Undocumented function
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return mix view
     */
    public function updateUser($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty(),
        ]);
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('users.index'));
        }
        $id = $request->getParam('id');
        $user = DB::queryFirstRow("SELECT id,name,lastname,email,super FROM users WHERE id=%i", $id);
        if (!$user) {
            $this->container->flash->addMessage('error', 'Geen gebruiker met deze ID');
            return $response->withRedirect($this->router->pathFor('users.index'));
        }

        $user = new $this->auth;
        $user->id = $id;
        $user->name = $request->getParam('name');
        $user->lastname = $request->getParam('lastname');
        $user->email = $request->getParam('email');
        if ($request->getParam('super')) {
            $user->super = $request->getParam('super');
        } else {
            $user->super = 0;
        }

        if ($request->getParam('password') != '' && ($user->password = $request->getParam('password'))) {
            $user->password = $request->getParam('password');
        }
        $status = $user->update();
        DB::delete('user_route', "user_id=%i", $request->getParam('id'));
        $routes_input = $request->getParam('route_id');

        foreach ($routes_input as $key => $route_input) {
            if (!isset($route_input['allow'])) {
                $allow = 0;
            } else {
                $allow = 1;
            }
            if (!isset($route_input['route_name'])) {
                $RouteName = '';
            } else {
                $RouteName = $route_input['route_name'];
            }

            if ($allow != 0) {
                DB::insert('user_route', array(
                    'user_id' => $request->getParam('id'),
                    'route_id' => $key,
                    'route_name' => $RouteName,
                    'allow' => $allow,
                ));
            }
        }
        $this->container->flash->addMessage('success', 'Gebruiker bijgewerkt');
        return $response->withRedirect($this->router->pathFor('users.userGet', ['id' => $request->getParam('id')]));
    }

    /**
     * Save Routes Name to database function
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return mix view
     */
    public function saveRoutes($request, $response, $args)
    {
        $routes = $this->container->router->getRoutes();
        foreach ($routes as $route) {
            $name = $route->getName();
            $url = $route->getPattern();
            $result = DB::queryFirstRow("SELECT * FROM routes WHERE route_name=%s", $name);
            if (!$result) {
                $check = DB::insert('routes', array(
                    'route_name' => $name,
                    'route_url' => $url,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ));
            }
        }
        $this->container->flash->addMessage('success', 'The Routes Are Updated !!');
        return $response->withRedirect($this->router->pathFor('home'));
    }
    /**
     * Get Users Aactivities view function
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $next
     * @return void view
     */
    public function getUsersAactivities($request, $response, $next)
    {
        return $this->view->render($response, 'users/activities.tpl', ['active_menu' => 'users','page_title' => 'Gebruikers activiteit']);
    }
    /**
     *  Get Users Aactivities datatable function
     *
     * @param [type] $request
     * @param [type] $response
     * @param [type] $next
     * @return void json datatable
     */
    public function getUsersAactivitiesData($request, $response, $next)
    {
        $activities = DB::query("select activity_log.id,activity_log.task,activity_log.subject_id,activity_log.created_at,users.name,users.lastname 
        from  activity_log left join users on users.id=activity_log.user_id order by id DESC");
        $returndata = array(
            'draw' => null,
            'cached' => null,
            'recordsTotal' => count($activities),
            'recordsFiltered' => count($activities),
            'data' => $activities
        );

        return json_encode($returndata);
    }
}
