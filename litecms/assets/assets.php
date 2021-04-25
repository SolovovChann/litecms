<?php

namespace Litecms\Assets;

/**
 * Dump array of data in <pre> tags
 * 
 * Function echo <pre> tag than in foreach loop
 * Walkthrough the $data array and put in in
 * var_dump function. Works ONLY when Litecms\Config\Debug
 * is TRUE.
 * 
 * @param array $data Array of data
 * 
 * @return void
 */
function debug (...$data) {
    if (\Litecms\Config\Debug === false) {
        return;
    }
    echo "<pre>";

    foreach ($data as $item) {
        var_dump ($item);
    }

    echo "</pre>";
}

/**
 * Get absolute path to file/directory or false if not exists
 * 
 * Implodes array to string (with '/' separator) and
 * $_SERVER['DOCUMENT_ROOT'] variable
 *  
 * 
 * @param array $path Array of strings, that 
 * @return string|bool
 */
function path (...$path) {
    return realpath ($_SERVER['DOCUMENT_ROOT'] . '/' . implode ('/', $path));
}

function in_assoc ($needle, $haystack) {
    return !empty ($haystack[$needle]) ? true : false;
}