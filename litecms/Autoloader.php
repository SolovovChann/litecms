<?php

use const Litecms\Config\Dirs;

spl_autoload_register(function($class) {
    $prefix = ucwords(Dirs['root']);
    $root = __DIR__;
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        // Request to another namespace 
        return;
    }

    // Make only last element of class titlecase
    $relative = substr($class, $len);
    $relative = strtolower($relative);
    $relative = explode('\\', $relative);
    $relative[ count($relative) - 1 ] = ucwords(end($relative));
    $relative = implode('/', $relative);

    $file = realpath($root.$relative.".php");
    if (!$file) {
        // File not exists
        return;
    }

    require_once $file;
});