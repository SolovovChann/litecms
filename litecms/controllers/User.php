<?php

namespace Litecms\Controllers;

use Litecms\Core\{Controller, Request, View};

class User extends Controller
{
    public static function default(Request $request)
    {
        return View::extend($request, "markups/base.php", "user/index.php", ['title' => 'Страница пользователя']);
    }
}