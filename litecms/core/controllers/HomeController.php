<?php

namespace Litecms\Core\Controllers;

use const Litecms\Config\Project\Dirs;

use Litecms\Assets\Debug;
use Litecms\Assets\Filesystem;
use Litecms\Core\Models\{
    Controller,
    Page,
    Router,
    View,
};

class HomeController extends Controller
{
    public function default ()
    {
        $page = Page::get (1);
        echo View::render ('home.php', [
            'title' => 'Homepage'
        ]);
        Debug::print ($page);
    }

    public function test (...$args)
    {
        echo View::render ('test.php', [
            'title' => 'Страница для тестирования',
            'args' => $args
        ]);
    }
}