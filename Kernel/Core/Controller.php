<?php
/**
 * Created by PhpStorm.
 * User: Patsay Sergey
 * Date: 16.06.2018
 * Time: 11:38
 */

namespace Kernel\Core;


use App\Model\User;

class Controller
{
    protected function render($view, $data = []) {
        if ($hasUser = User::isAuth()) {
            $UserName = $_SESSION['user']['name'];
        }
        include ROOT_PATH.'\App\View\header.html';
        include ROOT_PATH.$view;
        include ROOT_PATH.'\App\View\footer.html';
    }

}