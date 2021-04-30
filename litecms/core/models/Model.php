<?php

namespace Litecms\Core\Models;

class Model
{
    public static $table;
    public static $verboseName;
    public static $verboseNamePlural;

    /* Class static methods */
    
    /**
     * Get all objects
     * Select all entrys from current table
     * 
     * @example MyModel::all ();
     * 
     * @return array
     */
    static public function all () {}
    
    /**
     * Create object
     * 
     * @param array $data – assoc array where key is field and value is value
     * 
     * @example MyModel::create (['name' => 'John', 'age' => 28, 'height' => 1.92]);
     * 
     * @return self
     */
    static public function create (array $data) {}
    
    /**
     * Get all objects matching the condition
     * You can use few conditions, divided with coma 
     * 
     * @example MyModel::filter ('id = 5', 'name = John')
     * 
     * @param array $condition - array of strings used for filtering
     * 
     * @return void
     */
    static public function filter (...$condition) {}
    
    /**
     * Get object matching the condition
     * Unlike filter function returns ony one (first found) object
     * 
     * @example MyModel::get ('id = 5', 'name = John');
     * 
     * @param array $condition – array of strings used for filtering
     * 
     * @return void
     */
    static public function get (...$condition) {}
    
    /**
     * Removes selected object by 
     * More description
     * 
     * @param array $condition – array of strings used for filtering
     * 
     * @return void
     */
    static public function remove (...$condition) {}

    
    /**
     * 
     * Model object's methods
     * 
     */


    /**
     * Description
     * More description
     * 
     * @param
     * 
     * @return void
     */
    public function set ($args) {}
    
    /**
     * Save
     * More description
     * 
     * @param
     * 
     * @return void
     */
    public function save () {}
}