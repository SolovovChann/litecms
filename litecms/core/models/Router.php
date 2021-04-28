<?php

namespace Litecms\Core\Models;

use const \Litecms\Config\Urlpatterns as URLS;
use function \Litecms\Assets\assocHas;
use function Litecms\Assets\pureUrl;
use function \Litecms\Assets\path;

class Router extends Model
{
    private static $routes = [];

    public function add ($route, $handler) {
        array_push (self::$routes, [
            'route' => $route,
            'handler' => $handler
        ]);
    }

    static public function start () {
        // Remove slashes from start and end
        $url = pureUrl ($_SERVER['REQUEST_URI']);
        $method =  $_SERVER['REQUEST_METHOD'];

        // Ignore GET attributes
        if   (!empty ($_GET)) {
            $url = explode ('?', $url, 2)[0];
        }
        
        // Split request URL
        $routes = explode ('/', $url);
        $controllerName = $routes[0] ?? null;
        $action = $routes[1] ?? 'default';
        $args = array_slice ($routes, 2) ?? null;

        // Get controller via url
        $controller = Router::getController ($controllerName);

        if (!method_exists ($controller, $action)) {
            Router::throw404 ("Method is not exists");
        }

        // Try exec $action or throw 404
        try {
            $controller->$action (...$args);
        } catch (\ArgumentCountError $e) {
            Router::throw404 ("Can't find function that expect " . count ($args) . " arguments");
        }
    }

    static function throw404 ($message) {
        $redirect = 'http://'.$_SERVER['HTTP_HOST'].'/404';
        header ('HTTP/1.1 404 Not Found');
		header ("Status: 404 Not Found");
        
        // Trace debug information if Debug is true
        if (\Litecms\Config\Debug === true) {
            $redirect .= '?msg='.$message;
        }

        header ('Location: '.$redirect);
        exit ();
    }

    static function getController ($name) {
        if (!assocHas ($name, URLS)) {
            Router::throw404 ("Can't find controller '$name' in 'litecms/Config/Urlpatterns'");
            return;
        }

        $controller = URLS[$name];
        $path = path ($controller['controller']);

        if ($path === false) {
            Router::throw404 ("Controller '$name' not found");
            return;
        }
        
        $class = $controller['class'];

        
        include_once $path;
        return new $class;
    }
}