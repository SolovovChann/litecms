<?php

namespace Litecms\Assets;

class Validator
{
    /**
     * Use filter_var to validate email
     * 
     * @param string $input
     * 
     * @return string|bool
     */
    public static function email (string $input)
    {
        $email = filter_var ($input, FILTER_VALIDATE_EMAIL);

        return $email;
    }

    /**
     * Check if pasword meets all requirements
     * If password contents lowercase, uppercase, numbers, special chars and it's length more 6
     * Returns password or false
     * 
     * @param string $input
     * 
     * @return string|bool
     */
    public static function password (string $input)
    {
        $length = strlen ($input);
        $numbers = preg_match ("@[0-9]@", $input);
        $upper = preg_match ("@[A-Z]@", $input);
        $lower = preg_match ("@[a-z]@", $input);
        $special = preg_match ("@[\W]@", $input);

        return ($length < 6 or empty ($numbers) or empty ($upper) or empty ($lower) or empty ($special))
        ? false
        : $input;
    }

    /**
     * Validate phone 
     */
    public static function phone (string $input)
    {
    }
}