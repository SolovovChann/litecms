<?php

namespace Litecms\Core\Models;

class Model
{
    public static $database;
    public static $verboseName;
    public static $verbosePlural;

    /**
     * Creates entry in db using data
     * 
     * @param array $data – associative array where key = field
     * 
     * @return object
     */
    public function __construct (array $data) {

    }

    /**
     * Calls when try to use object as string
     * 
     * @return void
     */
    public function __toString () {
        return __CLASS__;
    }

    /**
     * Find object by some condition
     * 
     * @param string $condition
     * 
     * @return void
     */
    static public function get (string $condition) {}

    /**
     * Find object and edit it's data
     * 
     * @param int $id
     * @param string $field
     * @param string $value
     * 
     * @return void
    */
    static public function set (int $id, string $field, string $value) {}

    /**
     * Get all objects that conform filter
     * 
     * @param @filter
     * 
     * @return void
     */
    static public function filter (string $filter) {}

    /**
     * Deletes object
     * 
     * @return void
    */
    public function remove () {}

    /**
     * Get all objects of that model
     * 
     * @return array
     */
    public function all () {}
}