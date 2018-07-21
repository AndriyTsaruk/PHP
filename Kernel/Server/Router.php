<?php
/**
 * Created by PhpStorm.
 * User: Patsay Sergey
 * Date: 04.06.2018
 * Time: 20:10
 */

namespace Kernel\Server;

class Router
{
    const DEFAULT_CONTROLLER = 'Main';
    const DEFAULT_ACTION = 'index';

    public static function getController($url) {
        $urlParts = explode('/',$url);
        $controller = $urlParts[0];
        if(!$controller) {
            $controller = self::DEFAULT_CONTROLLER;
        }

        $fullName = 'App\\Controller\\'.$controller;
        if(class_exists($fullName)) {
            return new $fullName();
        } else {
            return false;
        }
    }

    public static function getAction($url) {
        $urlParts = explode('/',$url);
        return isset($urlParts[1]) ? $urlParts[1] : self::DEFAULT_ACTION;
    }
}