<?php

namespace Litecms\Assets\Misc;

/** 
 * Checks if a value exists in an associative array 
 *  
 * @param mixed $needle
 * @param array $haystack
 * 
 * @return bool
*/
function in_assoc ($needle, $haystack) {
    return empty ($haystack[$needle])
    ? false
    : true;
}