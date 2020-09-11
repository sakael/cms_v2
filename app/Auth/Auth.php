<?php

namespace App\Auth;

use DB;
use Carbon\Carbon;

class Auth
{
    public $id;
    public $name;
    public $lastname;
    public $email;
    public $password;
    public $super;
    public $user;
    public function salt()
    {
        return '0E30FE40-C378-11E5-8119-49822AEC0665';
    }

    public function __construct()
    {
        if (isset($_SESSION['user_id']) && !isset($_SESSION['user'.$_SESSION['user_id']])) {
            $user = DB::queryFirstRow("SELECT id,lastname,email,super,disable,name FROM users WHERE id=%i", $_SESSION['user_id']);
            if ($user) {
                $_SESSION['user'.$_SESSION['user_id']]=$user;
                $this->id = $user['id'];
                $this->name = $user['name'];
                $this->lastname = $user['lastname'];
                $this->email = $user['email'];
                $this->super = $user['super'];
            }
        }else if(!isset($_SESSION['user_id'])){
           return '';
        }
        else{
            return $_SESSION['user'.$_SESSION['user_id']];
        }
    }

    static function user()
    {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user'.$_SESSION['user_id']];
            //return DB::queryFirstRow("SELECT id,name,lastname,email,super FROM users WHERE id=%i", $_SESSION['user_id']);
        } else return false;
        
    }

    static function user_id()
    {
        if (isset($_SESSION['user_id'])) {
          //  $user = DB::queryFirstRow("SELECT id FROM users WHERE id=%i", $_SESSION['user_id']);
            return $_SESSION['user_id'];
        } else return false;
    }

    static function check()
    {
        return isset($_SESSION['user_id']);
    }

    public function super()
    {
        return $this->super;
    }

    static function GetPassword()
    {
        if (isset($_SESSION['user_id'])) {
            return DB::queryFirstRow("SELECT password FROM users WHERE id=%i", $_SESSION['user_id']);
        } else return false;
    }

    public function attempt($email, $password)
    {
        $user = DB::queryFirstRow("SELECT * FROM users WHERE email=%s and password=%s", $email, $this->generate_password($password));
        if (!$user) {
            return false;
        } elseif ($user['disable'] == 1) {
            return 'disable';
        } else {
            $_SESSION['user_id'] = $user['id'];
            return $user['id'];
        }
    }

    public function generate_password($pass)
    {
        $pass = md5($this->salt() . $pass);
        return $pass;
    }

    public function create()
    {
        $check = DB::insert('users', array(
            'name' => $this->name,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => $this->generate_password($this->password),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ), 'id=%s', 'goodbye');

        if ($check) return DB::insertId();
        else false;
    }

    public function update()
    {
        $user_id = $this->id;
        if (($this->password) && $this->password != '') {
            $check = DB::update('users', array(
                'name' => $this->name,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'password' => $this->generate_password($this->password),
                'super' => $this->super,
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ), 'id=%i', $user_id);
        } else {
            $check = DB::update('users', array(
                'name' => $this->name,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'super' => $this->super,
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ), 'id=%i', $user_id);
        }
        if ($check) return $user_id;
        else false;
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        session_destroy();
        setcookie("cms_login", null, time() - 3600);
        return true;
    }

    public static function All()
    {
        $users = DB::query("SELECT * from users order by name");
        $userTemp = array();
        foreach ($users as $user) {
            $userTemp[$user['id']] = $user;
        }
        return $userTemp;
    }

    public function getUserNameById($id)
    {
        if (isset($id)) {
            $user = DB::queryFirstRow("SELECT * FROM users WHERE id=%i", $id);
            if ($user) return $user['name'] . ' ' . $user['lastname'];
            else return 'No User Found';
        }
    }
    public function checkPermissionByRouteName($route)
    {
        $user = $this->user();
        if ($user) {
            if ($user['super'] != 1) {
                $user_perm = DB::queryFirstRow("SELECT allow FROM user_route WHERE user_id=%i and route_name=%s", $user['id'], $route);
                if ($user_perm) {
                    if ($user_perm['allow'] != true) {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
        return true;
    }
}
