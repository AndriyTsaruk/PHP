<?php
/**
 * Created by PhpStorm.
 * User: Patsay Sergey
 * Date: 04.06.2018
 * Time: 19:26
 */

namespace Kernel\Server;


class HttpServer
{
    public function run(HttpRequest $httpRequest) {
        $currentUrl = $_SERVER['REQUEST_URI'];
        $urlParts = explode('?', $currentUrl);
        $currentUrl = substr($urlParts[0],1);

        $controller = Router::getController($currentUrl);
        if(!$controller) {
            die('Controller not found');
        }
        $action = Router::getAction($currentUrl);
        if(!method_exists($controller,$action)) {
            die('Action not found');
        }
        $controller->$action($httpRequest);



    }
}