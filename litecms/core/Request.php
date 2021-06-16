<?php

namespace Litecms\Core;

class Request
{
    public $cookie;
    public $files;
    public $get;
    public $ip;
    public $method;
    public $post;
    public $referer;
    public $server;
    public $time;
    public $type;
    public $url;


    public function __construct() {
        $url = $_SERVER['REQUEST_URI'];
        $url = parse_url($url)['path'];
        $url = trim($url, '/');
        
        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
        $this->get = $_GET;
        $this->ip = $_SERVER['SERVER_ADDR'];
        $this->time = $_SERVER['REQUEST_TIME_FLOAT'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->post = $_POST;
        $this->referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->server = [
            'name' => $_SERVER['SERVER_NAME'],
            'protocol' => $_SERVER['SERVER_PROTOCOL'],
            'https' => ($_SERVER['HTTPS'] == 'on') ? true : false,
        ];
        $this->type = $_SERVER['CONTENT_TYPE'];
        $this->url = $url;
    }
}