<?php

namespace Litecms\Core\Controllers;

use Litecms\Core\Models\Controller;
use Litecms\Core\Models\View;

class Controller404 extends Controller
{
    public function default ()
    {
        $message = $_GET['msg'];
        echo View::render ('404.php', [
            'title' => 'Something gone wrong',
            'message' => $message
        ]);
    }
}