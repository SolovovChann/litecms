<?php

namespace Litecms\Assets;

/**
 * Dump array of data in <pre> tags
 * 
 * Function echo <pre> tag than in foreach loop
 * Walkthrough the $data array and put in in
 * print_r function. Works ONLY when Litecms\Config\Debug
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
        print_r ($item);
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
 * @param array $path Array of strings, that will be merget to one path
 * 
 * @return string|bool
 */
function path (...$path) {
    return realpath ($_SERVER['DOCUMENT_ROOT'] . '/' . implode ('/', $path));
}

/**
 * Check variable in associative array
 * @param mixed $needle - What to search
 * @param array $haystack - Where to search
 * 
 * @return bool
 */
function assocHas ($needle, $haystack) {
    return !empty ($haystack[$needle]) ? true : false;
}

/**
 * Remove forward slashes in url
 * 
 * @param string $url
 * @return string
 */
function pureUrl (string $url) {
    $url = trim ($url, '/');

    // Remove GET query from string
    if (strpos ($url, '?') != false) {
        $url = explode ('?', $url, 2)[0];
    }

    return $url;
}