<?php

namespace Litecms\Core\Controllers;

use Litecms\Core\Models\{
    Application,
    Controller,
    Router,
    View,
};
use const Litecms\Config\Project\Applications;

class AdminController extends Controller
{
    public function default ()
    {
        echo View::render ('admin.php', [
            'title' => 'Admin',
            'apps' => Applications,
        ]);
    }

    public function migrate ()
    {
        $app = new Application;
        $app->migrate ();

        return Router::redirect ('AdminController');
    }
}