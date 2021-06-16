<?php

namespace Litecms\Controllers;

use Litecms\Core\{Controller, Request, View};

class Home extends Controller
{
    public static function default(Request $request)
    {
        return View::extend($request, "markups/base.php", "home.php", ['title' => 'Главная страница']);
    }


    public static function notfound(Request $request)
    {
        return View::extend($request, "markups/base.php", "404.php", ['title' => 'Упс, произошла ошибка', 'url']);
    }
}