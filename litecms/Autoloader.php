<?php

// Include settings and assets
include 'config/config.php';
include 'assets/assets.php';

// Class autoloader
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

