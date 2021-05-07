<?php

namespace Litecms\Core\Controllers;

use Litecms\Core\Models\Controller;
use Litecms\Core\Models\View;
use Litecms\Core\Models\Router;
use const Litecms\Config\Project\Applications;

class AdminController extends Controller
{
    public function default ()
    {
        return Router::redirect ('HomeController:test:arg1:arg2:argN');
        
        echo View::render ('admin.php', [
            'title' => 'Admin',
            'apps' => Applications,
        ]);
    }
}