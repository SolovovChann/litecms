<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Router;
use const Litecms\Config\Project\Name;

class Application
{
    public $name = Name;

    /**
     * Starts routing pages and use controllers
     * 
     * @return void
     */
    public function start () {
        Router::start ();
        
    }

    /**
     * Create application folder with controller and models files in it
     * 
     * @param string $name – name of application. Will be used in namespace
     */
    public function createapp (string $name) {}
}