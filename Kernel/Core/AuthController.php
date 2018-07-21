<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19.06.2018
 * Time: 21:09
 */

namespace Kernel\Core;
use App\Model\User;

class AuthController extends Controller {
    public function __construct() {
        if (!User::isAuth()) {
            header('Location: /login/index');
        }
    }
}