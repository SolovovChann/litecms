<?php

namespace Litecms\Core\Models;

class Router extends Model
{
    static public function start () {
        // Split request URL
        $routes = explode ('/', $_SERVER['REQUEST_URI']);
        $controllerName = $routes[1] ?? 'home';
        $action = $routes[2] ?? 'default';
        $controller = Router::getController ($controllerName);

        if (method_exists ($controller, $action)) {
            $controller->$action ();
        } else {
            $controller->default ();
        }
    }

    static function throw404 ($message) {
        $host = sprintf ('http://%s/', $_SERVER['HTTP_HOST']);
        header ('HTTP/1.1 404 Not Found');
		header ("Status: 404 Not Found");
		header ('Location:'.$host.'404');
    }

    static function getController ($name) {
        $controller = \Litecms\Config\Urlpatterns[$name] ?? null;

        if ($controller === null) {
            Router::throw404 ("Can't find controller $name '$controller'");
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