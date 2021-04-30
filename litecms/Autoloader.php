<?php

spl_autoload_register (function ($class) {
    $prefix = 'Litecms';
    $baseDir = __DIR__;

    $len = strlen ($prefix);
    if (strncmp ($prefix, $class, $len) !== 0) {
        return;
    }

    $relClass = substr ($class, $len);
    $file = realpath ($baseDir . '/' . $relClass . '.php');

    if (!$file) {
        return;
    }

    require_once $file;
});

// Settings
require_once "config/connection.php";
require_once "config/project.php";
require_once "config/routes.php";