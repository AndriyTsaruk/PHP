<?php
/**
 * Created by PhpStorm.
 * User: Patsay Sergey
 * Date: 09.06.2018
 * Time: 10:54
 */

namespace Kernel\Core;


class Debug
{
    public static function mvd($var) {
        echo '<pre>';
        die(var_dump($var));
        echo '</pre>';
    }
}