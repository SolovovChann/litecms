<?php

namespace Litecms\Core\Models;

use Litecms\Assets\Misc;
use Litecms\Core\Models\View;
use const Litecms\Config\Project\Debug;

class Router
{
    /**
     * @var array $routes contains all avalible routes.
     * You can edit it in /litecms/config/routes.php file 
     */
    static public $routes = [];

    /**
     * Catch request and use controllers for response
     * Parse url like controller/action, calls action or throw 404 page
     * 
     * @return void
     */
    static public function start ()
    {
        $routes = Misc::clear_url ($_SERVER['REQUEST_URI'], true);
        $method = $_SERVER['REQUEST_METHOD'];

        $controller = self::$routes[$routes[0]];
        $action = $routes[1] ?? 'default'; // If action is not defended, use default action
        $args = array_slice ($routes, 2) ?? null;

        if (!$controller) {
            Router::throw404 ("Controller not found");
            return;
        }

        $controller = new $controller;

        // Try to pass arguments in function or throw 404
        try {
            $controller->$action (...$args);
        } catch (\ArgumentCountError $e) {
            Router::throw404 ("Can't find function '$action' that expect " . count ($args) . " argument(s)");
        }
    }

    /**
     * Add tracking route
     * 
     * @example Router::add ('home/', 'Litecms\Apps\Home\Home_controller');
     * 
     * @param string $route – URL
     * @param string $controller – controller's class
     * 
     * @return void
     */
    static public function add (string $route, string $controller)
    {
        $url = Misc::clear_url ($route);
        self::$routes[$url] = $controller;
    }

    /**
     * Define controller responsible for the index page
     * 
     * @example Router::addindex ('Litecms\Apps\Home\MyController');
     * 
     * @param string $controller – controller's class 
     * 
     * @return void
     */
    static public function addindex (string $controller)
    {
        self::$routes[''] = $controller;
    }

    /**
     * Define controller responsible for the 404 page
     * 
     * @example Router::add404 ('Litecms\Apps\My404Controller');
     * 
     * @param string $controller – controller's class 
     * 
     * @return void
     */
    static public function add404 (string $controller)
    {
        self::$routes['404'] = $controller;
    }

    /**
     * Redirect to default 404 page
     * 
     * @example if (!file_exists ('apps/HomeController.php')) {
     *     Router::throw404 ("Can't find controller");
     * }
     * 
     * @param string $message – message to user
     * 
     * @return void
     */
    static public function throw404 (string $message) {
        // Redirect to 404
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header ('HTTP/1.1 404 Not Found');
        header ("Status: 404 Not Found");
        header ('Location: '.Router::url ('Controller404'));
    }

    /**
     * Get link by special entry like <controller>:<action>:<arguments>
     * 
     * @example echo Router::url ('articles:view:4');
     * @example echo Router::url ('articles);
     * 
     * @return string
     */
    public static function url (string $to)
    {
        // Split input
        $split = explode (':', $to);
        $controller = $split[0];
        $action = $split[1];
        $args = array_slice ($split, 2);

        foreach (self::$routes as $url => $route) {
            $cont = end (explode ('\\', $route));
            
            if ($cont == $controller) {
                // If call home controller with action
                if ($url == '' and !empty ($action)) {
                    continue;
                }
                // Add action if isset
                $url .= (!empty ($action))
                ? "/" . $action
                : "";

                // Add arguments if isset
                $url .= (!empty ($args))
                ? "/" . implode ('/', $args)
                : "";

                return '/'.$url;
            }
        }
    }

    /**
     * Redirect using special entry like <controller>:<action>:<argument1>:<argument2>:...:<argumentN>
     * Finds controller in Applications, and redirects to it's url
     * 
     * @example return Route::redirect ('articles');
     * @example return Route::redirect ('articles:view:4') // 
     * 
     * @return void
     */
    public static function redirect (string $to)
    {
        $url = static::url ($to);
        header ('Location: '.$url);
    }
}