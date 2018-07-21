<?php
session_start();
include 'Kernel\Core\ClassLoader.php';

define('ROOT_PATH', __DIR__);

$ClassLoader = new \Kernel\Core\ClassLoader();
spl_autoload_register([$ClassLoader,'loadClass']);

$HttpRequest = \Kernel\Server\HttpRequest::init($_POST, $_GET, $_COOKIE, $_SERVER['REQUEST_METHOD']);

$Server = new \Kernel\Server\HttpServer();
$Server->run($HttpRequest);