<?php

namespace Litecms\Core\Models;

use const Litecms\Config\Connection\{
    Host,
    User,
    Password,
    Database,
    TablePrefix
};

class Connection
{
    private $link;
    public $prefix = TablePrefix;

    /**
     * Create connection to database
     * Use PDO for create connection. Basicly it's a mysqli wrapper 
     * 
     * @return self
     */
    public function __construct () {
        $this->connect ();
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
     * Create connection to database
     * Use mysqli class for create connection
     * 
     * @example $link = $link->connect ();
     * 
     * @return void
     */
    public function connect () {
        $this->link = new \mysqli (Host, User, Password, Database);
    }

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
    public function query (string $query, ...$values)  {
        if (!empty ($values)) {
            $query = sprintf ($query, ...$values);
        }

        $result = $this->link->query ($query);

        if (!$result or $result->num_rows == 0) {
            return false;    
        }

        return ($result->num_rows == 1)
        ? $result->fetch_array (MYSQLI_ASSOC)
        : $result->fetch_all (MYSQLI_ASSOC);
    }

    /**
     * Same function as Connection::query, but returns object of class
     * 
     * @param string $class - class to fetch
     * @param string $table – table, where get all data
     * @param array $values
     * 
     * @return object|null
     */
    public function getObject (string $class, string $table, int $id) {
        $result = $this->link->query (sprintf ("SELECT * FROM %s WHERE `id` = %d LIMIT 1",
            $this->prefix.$table,
            $id
        ));
        $result = $result->fetch_object ($class);

        return $result;
    }

    /**
     * Insert data into table
     * 
     * @example $connection->insert ('users', ['username' => 'John', 'email' => 'foobarbaz@yahoo.com']);
     * 
     * @param string $table
     * @param array $data – associative array like 'field' => value
     * 
     * @return bool
     */
    public function insert (string $table, array $data) : bool {
        $result = $this->query ("INSERT INTO %s (`%s`) VALUES ('%s')", [
            $table,
            implode (', `', array_keys ($data)),
            implode (', \'', array_values ($data)),
        ]);

        return $result;
    }

    /**
     * Send query to database like SELECT <fields> FROM <table> WHERE <condition>
     * 
     * @example $link->select ("user"); // Get all entrys from DB
     * @example $link->select ("user", ['id', 'username'], ["name = John", "age > 18"]); // Get ids and usernames all users, that meet the condition
     * @example $link->select ("user", 'name', ["name=John", "age>18"]) // Error. Do not forget about spaces in condition
     * 
     * @param string $table
     * @param string|array $fields – selected fields
     * @param string|array $condition
     * 
     * @return array|bool
     */
    public function select (string $table, $fields = "*", $condition = "") {
        \Litecms\Assets\Debug::print ();

        if (gettype ($fields) == 'array') {
            $fields = implode (', ', $fields);
        }

        $result = $this->query ("SELECT %s FROM %s WHERE %s",
            $fields,
            $this->prefix . $table,
            $this->regex ($condition)
        );

        return $result;
    }

    /**
     * Returns string like `field` = "value" 
     * If array is empty, returns "1" string
     * 
     * @param array $input – array like ['id = 4', 'age > 12']
     * 
     * @return string
     */
    private function regex ($input) {
        $output = "";

        if (!empty ($input)) {
            preg_match_all ("/(\S*)\s?(!=|<=|>=|<|>|=|LIKE)\s?(\S*)/", implode(' ', $input), $matches, PREG_SET_ORDER);
            // It's bad practice, but i don't know better way :c
            foreach ($matches as $match) {
                $output .= "`{$match[1]}` {$match[2]} \"{$match[3]}\" AND ";
            }
        }

        $output .= "1";

        return $output;
    }

    /**
     * Delete entry from table by condition
     * 
     * @example $link->delete ("users", ['age < 12']);
     * @example $link->delete ("users", ['age < 12', 'name = John']); // You can pass few arguments
     * 
     * @param string $table 
     * @param array $condition
     * 
     * @return bool
     */
    public function delete (string $table, array $condition) {
        $result = $this->query ("DELETE FROM %s WHERE %s",
            $this->prefix.$table,
            $this->regex ($condition)
        );
 
        return $result;
    }
    
    /**
     * Close connection
     * 
     * @example $link->close ();
     * 
     * @return void
     */
    public function close () {
        unset ($this->link);
    }
    
}