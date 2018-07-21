<?php
/**
 * Created by PhpStorm.
 * User: Patsay Sergey
 * Date: 04.06.2018
 * Time: 19:59
 */

namespace Kernel\Server;


class HttpRequest
{
    private $post;
    private $get;
    private $cookie;
    private $method;

    public static function init($post,$get,$cookie,$method) {
        $inst = new self();
        $inst->post = $post;
        $inst->get = $get;
        $inst->cookie = $cookie;
        $inst->method = $method;
        return $inst;
    }

    public function post($key) {
        if(isset($this->post[$key])) {
            return $this->post[$key];
        } else {
            return null;
        }
    }

    public function isPost() {
        return $this->method == "POST";
    }

    public function get($key) {
        if(isset($this->get[$key])) {
            return $this->get[$key];
        } else {
            return null;
        }
    }
}