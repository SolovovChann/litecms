<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Router;
use Litecms\Core\Models\User;
use const Litecms\Config\Project\Name;
use const Litecms\Config\Project\TimeZone;
use const Litecms\Config\Project\Applications;

class Application
{
    public $name = Name;

    /**
     * Starts routing pages and use controllers
     * 
     * @return void
     */
    public function start () {
        date_default_timezone_set(TimeZone);
        session_start ();
        
        User::checkToken ();
        Router::start ();
    }

    /**
     * Create application folder with controller and models files in it
     * 
     * @param string $name – name of application. Will be used in namespace
     */
    public function createapp (string $name) {}

    /**
     * Walkthrough all installed applications and create it's tables in db
     * 
     * @return void
     */
    public function migrate () {
        echo "<strong>Start working!</strong><br>";
        echo "<ol>Installed apps:";

        foreach (Applications as $app) {
            $result = $app::init ();
            echo "<li>".$app::$verboseNamePlural.": $result</li>";
        }

        echo "</ol>";

        echo "<strong>Done!</strong>";
    }
}