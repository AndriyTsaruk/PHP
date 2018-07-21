<?php
/**
 * Created by PhpStorm.
 * User: Patsay Sergey
 * Date: 16.06.2018
 * Time: 11:50
 */

namespace App\Model;

use Kernel\Component\DB\MySQL;

class User
{
    public static function auth($login, $password) {
        $DB = new MySQL();
        $password = md5($password);
        $result = $DB->selectOne('user', "login ='$login' AND password = '$password'");
        if($result) {
            $_SESSION['user'] = $result;
            return $result;
        } else {
            return false;
        }
    }

    public static function getId() {
        return isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : false;
    }

    public static function getData() {
        return isset($_SESSION['user']) ? $_SESSION['user'] : false;
    }

    public static function isAuth() {
        return isset($_SESSION['user']);
    }

    public static function logout() {
        unset($_SESSION['user']);
    }

    public static function reg($login, $password, $name, $age, $phone) {
        $data = ["name"=>$name, "phone"=>$phone, "age"=>$age, "login"=>$login, "password"=>md5($password)];
        $DB = new MySQL();
        $result = $DB->insert('test.user', $data);
        if($result) {
            return User::auth($login, $password);
        }
    }

    public static function edit($password, $name, $age, $phone, $login, $id) {
        $data = ["password"=>$password, "name"=>$name, "age"=>$age, "phone"=>$phone];
        $DB = new MySQL();
        $result = $DB->update ($id, 'user', $data);
        if ($result) {
            return User::auth($login, $password);
        }
    }
}