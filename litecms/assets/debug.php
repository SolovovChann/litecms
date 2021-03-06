<?php

namespace Litecms\Assets;

abstract class Debug 
{
    /**
     * Output data as debug info
     * Print <pre> tags and use var_dump function
     * 
     * @param array $data – array of data
     * 
     * @return void
    */
    static public function print (...$data) {
        echo "<pre>";
    
        foreach ($data as $item) {
            echo var_dump ($item);
        }
    
        echo "</pre>";
    }
}