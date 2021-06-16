<?php

namespace Litecms\Utils;

/**
 * System class for debugging
 * 
 * 
 */
class Debug
{
    /**
     * Output values in pre tags
     * 
     * @param array $values List of values to be displayed
     * @return void
     */
    public static function print(...$values): void
    {
        if (empty($values)) return;
        echo "<pre>", var_dump(...$values),"</pre>";
    }


    /**
     * Put data into log file
     * 
     * @param string $path  Path to log file
     * @param array $values List of values to be log
     */
    public static function log(string $path, ...$values): void
    {
        $timestamp = date("H.i.s_d.m.y");
        $values = implode(",\t", $values);
        $content = $timestamp."\t".print_r($values, true);
        $file = "{$path}/log_{$timestamp}.log";

        file_put_contents($file, $content.PHP_EOL, FILE_APPEND);
    }
}