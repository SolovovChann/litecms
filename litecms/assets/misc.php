<?php

namespace Litecms\Assets;

class Misc
{
    /** 
     * Checks if a value exists in an associative array 
     *  
     * @param mixed $needle
     * @param array $haystack
     * 
     * @return bool
    */
    static public function in_assoc ($needle, $haystack) {
        return empty ($haystack[$needle])
        ? false
        : true;
    }
    
    
    /**
     * Get clean url
     * Remove slashes from the URL at the beginning and end of a line and GET arguments
     * Optional: if split = true, returns array of url, splited by slash 
     * 
     * @example clear_url ('/home/apps/home/'); // Returned: home/apps/home
     * @example clear_url (''/home/apps/home/', true); // Returned: ['home', 'apps', 'home']
     * 
     * @param string $url
     * @param bool $split
    */
    static public function clear_url (string $url, bool $split = false) {
        // Remove slashes
        $url = trim ($url, '/');

        // Remove GET arguments
        $url = strtok ($url, '?');
    
        return ($split === true)
        ? explode ('/', $url)
        : $url;
    }
}