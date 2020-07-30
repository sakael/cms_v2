<?php

namespace App\Controllers\Auth;

use Respect\Validation\Validator as v;
use App\Controllers\Controller;
use DB;
use App\Auth\Auth;
use App\Classes\UserActivity;

class AuthController extends Controller
{

    /**************************************************************************************************************************************************
     *************************************************************(Sign Up Form Get)*******************************************************************
     **************************************************************************************************************************************************/
    public function getSignUp($request, $response, $args)
    {
        return $this->view->render($response, 'auth/signup.tpl');
    }

    /**************************************************************************************************************************************************
     *************************************************************(Sign Up Form Post)******************************************************************
     **************************************************************************************************************************************************/
    public function PostSignUp($request, $response, $args)
    {
        //validate the registration form fields
        $validation = $this->validator->validate($request, [
            'email' => v::notEmpty()->email()->EmailAvailable(),
            'name' => v::notEmpty(),
            'lastname' => v::notEmpty(),
            'password' => v::notEmpty()->noWhitespace(),
        ]);

        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }
        ///create the user
        $user = new $this->auth;
        $user->name = $request->getParam('name');
        $user->lastname = $request->getParam('lastname');
        $user->email = $request->getParam('email');
        $user->password = $request->getParam('password');
        $status = $user->create();

        //Check if the user is created redirect to home page with a message
        if ($status) {
            //   $check=$this->auth->attempt($request->getParam('email'),$request->getParam('password'));
            $this->container->flash->addMessage('info', 'Gebruiker is gemaakt');
            UserActivity::Record('Create', $status, 'Auth');
            return $response->withRedirect($this->router->pathFor('users.index'));
        } else {
            //Check if the user is not created return back with variable and error message
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }
    }

    /**************************************************************************************************************************************************
     *************************************************************(Sign In Form Get)*******************************************************************
     **************************************************************************************************************************************************/

    public function getSignIn($request, $response, $args)
    {
        if (!$this->auth->check()) {
            return $this->view->render($response, 'auth/login.tpl');
        } else  return $response->withRedirect($this->router->pathFor('home'));
    }

    /**************************************************************************************************************************************************
     *************************************************************(Sign In Form Post)******************************************************************
     **************************************************************************************************************************************************/
    public function postSignIn($request, $response, $args)
    {
        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );
        if (!$auth) {
            $this->container->flash->addMessage('error', 'De inloggegevens fout zijn !!');
            return $response->withRedirect($this->router->pathFor('auth.login'));
        } elseif ($auth == 'disable') {
            $this->container->flash->addMessage('error', 'Uw account is uitgeschakeld neem contact op met het administratiekantoor !!');
            return $response->withRedirect($this->router->pathFor('auth.login'));
        }
        $this->container->flash->addMessage('success', 'U bent aangemeld');
        UserActivity::Record('SignIn', $auth, 'Auth');

        if ($_SESSION['trying_to_access'] && $_SESSION['trying_to_access'] != '') {
            $tmp = $_SESSION['trying_to_access'];
            $_SESSION['trying_to_access'] = '';
            unset($_SESSION['trying_to_access']);
            return $response->withRedirect($tmp);
        } else {
            return $response->withRedirect($this->router->pathFor('home'));
        }
    }

    /**************************************************************************************************************************************************
     *************************************************************(Sign In Form Get)*******************************************************************
     **************************************************************************************************************************************************/

    public function getSignOut($request, $response, $args)
    {
        $id = $this->auth->user_id();
        UserActivity::Record('SignOut', $id, 'Auth');
        $this->auth->logout();
        $this->container->flash->addMessage('info', 'U bent afgemeld ');
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    /**************************************************************************************************************************************************
     *************************************************************(Change Password Get)****************************************************************
     **************************************************************************************************************************************************/
    public function getAccount($request, $response, $args)
    {
        return $this->view->render($response, 'account/account.tpl');
    }

    /**************************************************************************************************************************************************
     *************************************************************(Change Password Post)***************************************************************
     **************************************************************************************************************************************************/
    public function postAccount($request, $response, $args)
    {
        $user = $this->auth->user();
        //validate the registration form fields
        if ($request->getParam('old_password') && $request->getParam('old_password') != '') {
            $validation = $this->validator->validate($request, [
                'email' => v::notEmpty()->email(),
                'name' => v::notEmpty(),
                'lastname' => v::notEmpty(),
                'old_password' => v::noWhitespace()->notEmpty()->MatchesPassword($this->auth->GetPassword(), $this->auth->generate_password($request->getParam('old_password'))),
                'password' => v::noWhitespace()->notEmpty(),
            ]);
        } else {
            $validation = $this->validator->validate($request, [
                'email' => v::notEmpty()->email(),
                'name' => v::notEmpty(),
                'lastname' => v::notEmpty(),

            ]);
        }
        ///check if failed return back with error message and the fields
        if ($validation->failed()) {
            $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
        } else {
            $user = new $this->auth;
            $user->id = $request->getParam('id');
            $user->name = $request->getParam('name');
            $user->lastname = $request->getParam('lastname');
            $user->email = $request->getParam('email');
            $user->password = $request->getParam('password');
            $status = $user->update();
            if ($status) {
                $this->container->flash->addMessage('success', 'Uw account is bijgewerkt');
                UserActivity::Record('Update', $user->id, 'Auth');
            } else {
                $this->container->flash->addMessage('error', 'Er is een probleem opgetreden. Probeer het opnieuw of neem contact op met het administratiebureau.');
            }
        }
        return $response->withRedirect($this->router->pathFor('auth.account'));
    }
}
