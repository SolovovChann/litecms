<?php

namespace Litecms\Core\Controllers;

use Litecms\Assets\Debug;
use Litecms\Core\Models\{
    Controller,
    User,
    View,
    Router,
};

class UserController extends Controller
{
    public function default ()
    {
        echo View::render ('user.php', [
            'title' => 'Страница пользователя',
            'user' => User::me (),
        ]);
    }

    public function signin ()
    {
        if (User::is_authenticated ()) {
            Router::redirect ('Litecms\Apps\HomeController');
        }

        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];

        User::auth ($email, $password);

        return Router::redirect ('UserController');
    }

    public function signout ()
    {
        User::signout ();

        return Router::redirect ("HomeController");
    }

    public function signup ()
    {

    }
}