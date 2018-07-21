<?php
/**
 * Created by PhpStorm.
 * User: Patsay Sergey
 * Date: 04.06.2018
 * Time: 20:28
 */

namespace App\Controller;


use Kernel\Core\Controller;
use Kernel\Server\HttpRequest;

class Main extends Controller
{
    public function index(HttpRequest $request) {
        $this->render('\App\View\Main\index.html');
    }
}