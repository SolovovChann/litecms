<?php

namespace Litecms\Core\Controllers;

use Litecms\Assets\Debug;
use Litecms\Core\Models\{
    Controller,
    User,
    View,
    Router,
    Validator
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
        $user = new User;
        $user->username = $_REQUEST['username'];
        $email = Validator::email ($_REQUEST['email']);
        $password = Validator::password ($_REQUEST['password']);

        $user = $user->signup (); // register new user
        User::auth ($user->email, $user->password); // authenticate new user

        return Router::redirect ("UserController");
    }
}