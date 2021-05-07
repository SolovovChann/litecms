<?php

namespace Litecms\Core\Controllers;

use Litecms\Core\Models\Controller;
use Litecms\Core\Models\User;
use Litecms\Core\Models\View;
use Litecms\Core\Models\Router;

class UserController extends Controller
{
    public function default ()
    {
        echo View::render ('user.php', [
            'title' => 'Страница пользователя'
        ]);
    }

    public function signin ()
    {
        if (User::is_authenticated ()) {
            return Router::redirect ('Litecms\Apps\HomeController');
        }

        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];

        $user = User::auth ($email, $password);
    }

    public function signout ()
    {

    }
}