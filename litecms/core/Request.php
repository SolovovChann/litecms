<?php

namespace Litecms\Core;

/**
 * Confortable way store server values.
 * @package Litecms\Core
 * 
 * @var array $cookie Equal to $_COOKIE variable
 * @var array $request Equal to $_REQUEST variable
 * @var array $files Equal to $_FILES variables
 * @var array $get Equal to $_GET variables
 * @var string $ip Server's address
 * @var float $time Server's request time. Corresponds to the time when the request has started
 * @var string $method Request's method (POST, GET, SET etc)
 * @var array $post Equal to $_POST variable
 * @var string $referer Server's http referer or single slash (used in redirects)
 * @var array $server Array, contains main server's settings
 * @var string $server['name'] Server's name
 * @var string $server['protocol'] Server's protocol
 * @var bool $server['https'] Bool var does server usess secure protocol or not
 * @var string type Current page's content type
 * @var string url Current page's url address
 */
class Request
{
    public $cookie;
    public $files;
    public $get;
    public $ip;
    public $method;
    public $post;
    public $referer;
    public $request;
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
        $this->request = $_REQUEST;
        $this->referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->server = [
            'name' => $_SERVER['SERVER_NAME'],
            'protocol' => $_SERVER['SERVER_PROTOCOL'],
            'https' => ($_SERVER['HTTPS'] == 'on'),
        ];
        $this->type = $_SERVER['CONTENT_TYPE'];
        $this->url = $url;
    }
}