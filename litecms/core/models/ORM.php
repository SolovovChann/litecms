<?php

namespace Litecms\Core\Models;

class Field
{
    /**
     * Format null and default
     * 
     * @param null|bool $nullable – use null as possible value
     * @param null|bool $default – pass default value
     * 
     * @return string
     */
    public static function postfix ($default = null, $nullable = false) {
        $default = ($default === null)
        ?: sprintf ("DEFAULT %s", $default);
        
        $nullable = ($nullable === false)
        ? null
        : "NOT NULL";

        return implode (' ', array_filter([$default, $nullable]));
    }

    /**
     * Date field
     * 
     * @param null|bool $default – pass default value
     * @param null|bool $nullable – use null as possible value
     * 
     * @return string
     */
    public static function date ($default = null, $nullable = null) {
        $max = 11;
        $result = sprintf ("date (%s) %s", $max, self::postfix ($nullable, $default));
        return $result;
    }

    /**
     * Int field
     * 
     * @param null|bool $default – pass default value
     * @param null|bool $nullable – use null as possible value
     * 
     * @return string
     */
    public static function int ($max = 255, $default = null, $nullable = null) {
        $result = sprintf ("int (%s) %s", $max, self::postfix ($nullable, $default));
        return $result;
    }

    /**
     * Big text field
     * 
     * @param null|bool $default – pass default value
     * @param null|bool $nullable – use null as possible value
     * 
     * @return string
     */
    public static function text ($default = null, $nullable = null) {
        $result = sprintf ("Text %s", self::postfix ($nullable, $default));
        return $result;
    }

    /**
     * Short text field
     * 
     * @param int $max – value's character limit
     * @param null|bool $default – pass default value
     * @param null|bool $nullable – use null as possible value
     * 
     * @return string
     */
    public static function varchar ($max = 255, $default = null, $nullable = null) {
        $result = sprintf ("varchar (%s) %s", $max, self::postfix ($nullable, $default));
        return $result;
    }

    /**
     * Save
     */
    public static function migrate (...$fields) {}
}