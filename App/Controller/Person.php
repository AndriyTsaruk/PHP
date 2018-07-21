<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19.06.2018
 * Time: 20:18
 */

namespace App\Controller;

use Kernel\Core\AuthController;
use Kernel\Server\HttpRequest;
use App\Model\User;


class Person extends AuthController {

    public function index(HttpRequest $request) {
            $this->render('\App\View\Person\index.html');
    }

    public function edit(HttpRequest $request) {
        if ($request->isPost()) {
            $login = $_SESSION['user']['login'];
            $id = $_SESSION['user']['id'];
            $password = $request->post('password');
            $password = md5($password);
            $name = $request->post('name');
            $age = $request->post('age');
            $phone = $request->post('phone');
            $result = User::edit($password, $name, $age, $phone, $login, $id);
            $data = [];
            if ($result) {
                echo "изменения успешно сохранены";
            } else {
                $data['error'] = 'Не удалось сохранить данные';
            }
        }
        $this->render('\App\View\Person\edit.html');
    }
}