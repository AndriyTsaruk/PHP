<?php
/**
 * Created by PhpStorm.
 * User: Patsay Sergey
 * Date: 04.06.2018
 * Time: 21:17
 */

namespace App\Controller;

use App\Model\User;
use Kernel\Core\Controller;
use Kernel\Server\HttpRequest;

class Login extends Controller
{
      public function logout() {
        User::logout();
        header('Location: /main/index');
    }

    public function auth(HttpRequest $request) {
        $login = $request->post('login');
        $password = $request->post('password');
        $User = User::auth($login, $password);

        if(is_array($User)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Пользователь  не найден']);
        }
    }

    public function register(HttpRequest $request) {
        $data = [];
        if ($request->isPost()) {
            $login = $request->post('login');
            $password = $request->post('password');
            $name = $request->post('name');
            $age = $request->post('age');
            $phone = $request->post('phone');
            if ($login and $password and $name and $age and $phone) {
                $User = User::reg($login, $password, $name, $age, $phone);
                $data = [];
                if (is_array($User)) {
                    header('Location: /Main/index');
                } else {
                    $data['error'] = 'Пользователь не найден';
                }
            } else {
                $data['error'] = 'Заполните все поля';
            }
        }
        $this->render('\App\View\Login\register.html', $data);
    }
}