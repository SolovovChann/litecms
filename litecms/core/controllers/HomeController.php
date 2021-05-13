<?php

namespace Litecms\Core\Controllers;

use const Litecms\Config\Project\Dirs;

use Litecms\Assets\Debug;
use Litecms\Assets\Filesystem;
use Litecms\Core\Models\{
    Connection,
    Controller,
    Page,
    Router,
    View,
    Validator
};
use Litecms\Apps\Articles\Article;

class HomeController extends Controller
{
    public function default ()
    {
        echo View::render ('home.php', [
            'title' => 'Homepage'
        ]);
    }

    public function test (...$args)
    {
        echo View::render ('test.php', [
            'title' => 'Страница для тестирования',
            'args' => $args
        ]);
    }
}