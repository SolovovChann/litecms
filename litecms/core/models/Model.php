<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Connection;

class Model
{
    public $id;
    
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
        $result = $link->getObject (get_called_class (), static::$table, -1); // If pass -1 to getObject, it returns all entrys as objects
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
     * @return array|bool
     */
    static public function filter (...$condition) {
        $link = new Connection ();
        $result = $link->select (static::$table, '*', $condition);

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
    public function set ($args) {
        foreach ($args as $key => $value) {
            $this->$key = $value;
        }
    }
    
    /**
     * Saves model changes in database
     * Also can be used for updating inforamtion
     * 
     * @example
     * $john = new Human ();
     * $john->name = 'John';
     * $john->age = 28;
     * $john->height = 1.82;
     * $john->save ();
     * @example
     * $john = Human::get (5); // Get human with id = 5
     * $john->age += 1; // Yey, John celebrates his birthday! Congrats!
     * $john->save (); // Save new John's age in db
     * @example
     * $john = Human::get (5);
     * echo $john->name; // "John"
     * $john->name = "John"; // Do not worry, the same information will not be overwritten
     * $john->age += 1; // Saves only that part
     * $john->save ();
     * 
     * @return void
     */
    public function save () {
        $link = new Connection ();

        if (!empty ($this->id)) {
            // Get entry from db
            $entry = static::get ($this->id);
            
            // Can't get entry from db
            if (empty ($entry)) {
                return false;
            }

            // Get object fields with values
            $fields = get_object_vars ($this);
            $changes = [];

            // If object do not match with entry from db, write it down
            foreach ($fields as $key => $value) {
                if ($entry->$key != $value) {
                    $changes[$key] = $value;
                }
            }

            $query = "UPDATE %s SET %s WHERE `id` = %s";

            // Transform assoc array to string like `key` = 'value', `key2` = 'value2' ... 
            $insert = implode (', ', array_map (
                function ($v, $k) { return sprintf ("`%s` = '%s'", $k, $v); },
                $changes,
                array_keys ($changes)
            ));
            
            $link->query ($query, $link->prefix.static::$table, $insert, $this->id);
        } else {
            $link->insert (static::$table, get_object_vars ($this));
        }
    }

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