<?php

require_once 'view.php';
require_once 'model.php';

use Litecms\Core\Models\Controller; 
use Litecms\Core\Models\View;

class home_controller extends Controller 
{
    public function default () {
        echo View::render ('home.php', [
            'title' => 'Homepage',
        ]);
    }

    public function test () {
        if ($_SERVER['REQEST_METHOD'] == 'POST') {
            $data = $_POST;
            var_dump ($data);
        }

        echo View::render ('home.php', [
            'title' => 'Homepage but test'
        ]);
    }

    public function person ($name, $age) {
        echo View::render ('home.php', [
            'title' => "$name is $age years old",
        ]);
    }
}