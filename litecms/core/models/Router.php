<?php

namespace Litecms\Core\Models;

use const Litecms\Config\Urlpatterns as URLS;

class Router extends Model
{
    static public function start () {
        // Remove slashes from start and end
        $url = trim ($_SERVER['REQUEST_URI'], '/');

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
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header ('HTTP/1.1 404 Not Found');
		header ("Status: 404 Not Found");
		header ('Location: /404?msg='.$message);
    }

    static function getController ($name) {
        $controller = URLS[$name] ?? null;

        if ($controller === null) {
            Router::throw404 ("Can't find controller $name '$controller'");
            return;
        }

        $path = \Litecms\Assets\path ($controller['controller']);

        if ($path === false) {
            Router::throw404 ("Controller '$name' not found");
            return;
        }
        
        $class = $controller['class'];

        
        include_once $path;
        return new $class;
    }
}