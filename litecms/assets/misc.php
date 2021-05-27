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

        if ($diff->m > 3) {
            return $old->format("d.m.Y. H:i");
        }


        if ($diff->m != 0 and $diff->m <= 3) {
            switch ($diff->m) {
                case 1:
                    return "месяц назад";
                    break;
                
                case 2:
                case 3:
                case 4:
                    return sprintf("%s месяца назад", $diff->m);

                default:
                    return sprintf ("%s месяцев назад", $diff->m);
                    break;
            }
        }


        if ($diff->d != 0 and $diff->d <= 7) {
            switch ($diff->d) {
                case 1:
                    return "вчера";
                    break;
                
                case 2:
                    return "позавчера";
                    break;

                case 3:
                case 4:
                    return sprintf("%s дня назад", $diff->d);
                    break;

                default:
                    return sprintf("%s дней назад", $diff->d);
                    break;
            }
        }


        if ($diff->h != 0 and $diff->h <= 12) {
            switch ($diff->h) {
                case 1:
                    return "час назад";
                    break;
                
                case 2:
                case 3:
                case 4:
                    return sprintf("%s часа назад", $diff->h);
                    break;

                default:
                    return sprintf("Сегодня %s часов назад", $diff->h);
                    break;
            }
        }


        if ($diff->i != 0 and $diff->i <= 30) {
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
                
                case (15 <= $diff->i and $diff->i < 20):
                    return "15 минут назад";
                    break;
                
                case (20 <= $diff->i and $diff->i < 30):
                    return "20 минут назад";
                    break;
                
                case (30 <= $diff->i and $diff->i < 45):
                    return "полчаса назад";
                    break;
                
                default:
                    return sprintf("%s минут назад", $diff->i);
                    break;
            }
        }
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
