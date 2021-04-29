<?php

require_once 'view.php';

use Litecms\Core\Models\View;
use Litecms\Core\Models\Controller;

class test_controller extends Controller 
{
    public function default () {
        echo View::render ('test.php');
    }
    
    public function dump (...$args) {
        echo View::render ('test.php', ['args' => $args]);
    }
}