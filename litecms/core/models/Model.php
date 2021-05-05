<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Connection;
use const Litecms\Config\Connection\TablePrefix;

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
    static public function all () {
        $link = new Connection ();
        $result = $link->select (static::$table);
        $link->close ();

        return $result;
    }
    
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
     * @example MyModel::filter ('name = John', 'age <= 18');
     * 
     * @param array $condition - array of strings used for filtering
     * 
     * @return void
     */
    static public function filter (...$condition) {
        $link = new Connection ();
        $result = $link->select (static::$table, '*', $condition);
        $link->close ();

        return $result;
    }
    
    /**
     * Get object by ID
     * If case of error, returns false
     * 
     * @example MyModel::get (5);
     * 
     * @param int $id 
     * 
     * @return object|null
     */
    static public function get (int $id) {
        
        $link = new Connection ();
        $result = $link->getObject (
            get_called_class (),
            static::$table,
            $id
        );

        return $result;
    }    

    /* Model object's methods */


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

    /**
     * Deletes selected object
     * In case of error, returns false
     * 
     * @return void|bool
     */
    public function delete () {
        $link = new Connection ();
        $result = $link->delete (static::$table, ["id = " . $this->id]);

        return $result;
    }
}