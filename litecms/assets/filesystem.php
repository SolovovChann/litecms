<?php

namespace Litecms\Assets;

class Filesystem
{
    /** 
     * Get absolute path to file/folder or false if it's not exists
     * Use function realpath with Server's Document root constant
     * 
     * @example path ('home/controller.php');
     * @example path ('home', 'controller.php');
     * @example path ('home', 'some', 'long', 'path', 'to', 'controller.php');
     * 
     * @param array $data – array of strings
     * 
     * @return string
    */
    static public function path (...$path) {
        return realpath ($_SERVER['DOCUMENT_ROOT'] . '/' . implode ('/', $path));
    }
}
