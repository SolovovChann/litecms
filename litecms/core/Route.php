<?php

namespace Litecms\Core;

use Litecms\Utils\Debug;
use const Litecms\Config\{Dirs, Debug};

class Route
{
    private static $routes = [];

    /**
     * Add new url to track list
     * 
     * @param string $url URL path, triggers that view
     * @param mixed $view Function, called when url triggers, pass array like [classname, method] to use method as callback
     * @param $methods List of allowed server methods.
     * @return void
     */
    public static function add(string $url, $view, $methods = ['GET', 'POST']): void
    {
        array_push(self::$routes, [
            'url' => $url,
            'view' => $view,
            'methods' => $methods
        ]);
    }


    /**
     * Initialize routing
     * 
     * @return void
     */
    public static function start()
    {
        require_once Dirs['root']."/Routes.php";

        $request = new Request;

        foreach (self::$routes as $route) {
            $url =  addcslashes($route['url'], "/");
            $pattern = preg_replace("/\{\w+\}/", "([\w\-\_]+)", $url);
            preg_match_all("/{$pattern}/", $request->url, $result, PREG_PATTERN_ORDER);
            
            if ($result[0][0] !== $request->url) {
                continue;
            }

            if (!in_array($request->method, $route['methods'])) { 
                self::notFound($request);
            }

            $paramethers = array_slice($result, 1);
            // Flatten array
            $paramethers = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($paramethers));
            
            echo call_user_func($route['view'], $request, ...$paramethers);
            return;
        }
        self::notFound($request);
    }


    public static function notFound(Request $request)
    {
        if (Debug !== true) {
            die("Can't find controller, responsible for url: '{$request->url}'. Check for it in litecms\Settings.php file");
        } else {
            redirect('404');
        }
    }


    /**
     * Get url by controller's method
     */
    public static function url(string $controller, string $method, array $arguments = [])
    {
        $route = array_filter(self::$routes, fn($item) => $item['view'] == [$controller, $method]);
        $route = array_shift($route);
        $url = $route['url'];

        $pattern = array_fill(0, count($arguments), "/\{\w+\}/");
        $result = preg_replace($pattern, $arguments, $url, 1);
               
        return "/{$result}/";
    }
}