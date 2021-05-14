<?php

namespace Litecms\Core\Controllers;

use Litecms\Core\Models\{
    Application,
    Controller,
    Router,
    View,
};
use Litecms\Assets\{Filesystem, Misc, Debug};
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

    public function newapp ()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $url = Misc::clear_url ($_REQUEST['url']);
            $name = $_REQUEST['name'];
            $model = $_REQUEST['model'];

            $result = Application::createapp ($name, $url, $model);

            Debug::print ($result); 
            // return Router::redirect ("AdminController");
        }

        echo View::render ('createapp.php');
    }

    public function table (...$model)
    {
        $class = implode ('\\',$model);
        $table = $class::all ();
        $keys = get_object_vars ($table[0]);

        echo View::render ('table.php', ['table' => $table, 'keys' => $keys]);
    }
}