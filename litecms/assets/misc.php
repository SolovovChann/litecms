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


    /**
     * Format input date to format
     * 
     * @example Misc::date ($article->date, "d.m.y H:i");
     * @param string $input
     * 
     * @return string
     */
    public static function date_interval (string $input)
    {
        $timestamp = new \DateTimeZone(\Litecms\Config\Project\TimeZone);
        $now = new \DateTime ("now", $timestamp);
        $old = new \DateTime ($input, $timestamp);
        $diff = $now->diff($old);

        if ($diff->i <= 30 and empty($diff->d) and empty($diff->m) and empty($diff->y)) {
            switch ($diff->i) {
                case (1 <= $diff->i and $diff->i < 5):
                    return "только что";
                    break;
                
                case (5 <= $diff->i and $diff->i < 10):
                    return "5 минут назад";
                    break;
                
                case (10 <= $diff->i and $diff->i < 15):
                    return "10 минут назад";
                    break;
            }
        }

        return $old->format ("d F Y в H:i");
    }

    /**
     * Format input as URL
     * 
     * @example Misc::format_url ("\\system\\path\\to\\image.png"); // Get: /system/path/to/image.png
     * 
     * @param string $input
     * 
     * @return string
     */
    public static function format_url (string $input)
    {
        // Replace all backslashes to regular
        $output = str_replace ("\\", "/", $input);

        // Add first slash
        if (!substr ($output, 0, 1) != '/') {
            $output = '/'.$output;
        }

        return $output;
    }
}
