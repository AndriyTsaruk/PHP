<?php
/**
 * Created by PhpStorm.
 * User: Patsay Sergey
 * Date: 04.06.2018
 * Time: 19:34
 */

namespace Kernel\Core;

class ClassLoader
{
    public function loadClass($className) {
        $classPath = ROOT_PATH.'/'.$className.'.php';
        if(file_exists($classPath)) {
            include $classPath;
        }
    }
}