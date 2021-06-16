<?php

namespace Litecms\Core;

use Litecms\Core\{Route, Request, Connection};
use Litecms\User\User;
use Litecms\Utils\{Message, Debug};
use const Litecms\Config\{Models, Connection as ConnCfg};

class Application
{    
    /**
     * The whole project's entry point.
     * 
     * @return void
     */
    public static function start()
    {
        session_start();
        Route::start();
    }


    /**
     * 
     */
    public static function initializeDB()
    {
        $pdo = new Connection;
        foreach (Models as $model) {
            $object = new $model;
            $sql = "SELECT * FROM information_schema.tables WHERE table_schema  = ? AND table_name = ?";
            $check = $pdo->query($sql, [ConnCfg['database'], $pdo->prefix($object::$table)]);

            if (!empty($check)) {
                continue;
            }

            $sql = sprintf("CREATE TABLE {$pdo->prefix($object::$table)} (%s)", implode(", ", $object->formTable()));
            $pdo->query($sql);
        }
    }
}