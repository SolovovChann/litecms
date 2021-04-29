<?php

namespace Litecms\Core\Models;

class Connection
{
    private $link;
    public $prefix;

    /**
     * Creates connection to database
     * Use PDO for create connection. Basicly it's a PDO wrapper 
     * 
     * @return Connection
     */
    public function __construct () {
        $this->link = new PDO ("mysqli:litecms;host=localhost", "root", "root");
    }
    
    /**
     * Closes connection after work
     * The destructor method will be called as soon as there are no other references to a particular object, or in any order during the shutdown sequence
     * 
     * @return void
     */
    public function __destruct () {
        $this->close ();
    }
    

    /**
     * Creates connection to database
     * Use PDO for create connection. Basicly it's a PDO wrapper 
     * 
     * @example $link = Connection::connect ();
     * 
     * @return void
     */
    static public function connect () {}
    
    /**
     * Send query to database. Use vsprint function to cast vars
     * Returns associative array or false on failure
     * 
     * @example $link->query ("SELECT * FROM %s WHERE `%s` = %d", ['table', 'id', 4]);
     * @example $link->query ("SELECT * FROM `table` WHERE `id` = 5"); // You can call function without arguments
     * 
     * @param string $query – string to format
     * @param array $values 
     * 
     * @return array|bool
     */
    public function query (string $query, ...$values) : mixed {
        if (!empty ($values)) {
            $query = vsprintf ($query, $values);
        }

        $this->link->prepare ($query);
        $result = $this->link->execute ();

        if (!$result or $result->rowCount == 0) {
            return false;    
        }

        return ($result->rowCount == 1)
        ? $result->fetchCol
        : $result->fetchAll (PDO::FETCH_ASSOC);
    }
    
    /**
     * Selects data from tabble uses condition
     * Query to database like SELECT {select} FROM {table} WHERE {condition}
     * 
     * @example Connection::select ('user', ['id', 'first_name', 'email'], ['name = John', 'id != 4']);
     * 
     * @param string $table
     * @param string $select – array of selection paramethers
     * @param array $condition
     * 
     * @return array
     */
    static public function select (string $table, array $select, $condition = "1") : array {}
    
    /**
     * Inserts associative array of data into table
     * 
     * @example Conection::insert ('users', ['username' => 'John', 'email' => 'foobarbaz@yahoo.com']);
     * 
     * @param string $table
     * @param array $data – associative array like 'field' => value
     * 
     * @return bool
     */
    static public function insert (string $table, array $data) : bool {}
    
    /**
     * Closes connection
     * 
     * @example Connection::close ();
     * 
     * @return void
     */
    static public function close () {
        unset ($this->link);
    }
    
}