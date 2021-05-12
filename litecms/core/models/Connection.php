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

    public function pureQuery (string $query, ...$values){
        $paramCount = count ($values);

        $stmt = $this->link->prepare ($query) or die ($this->link->error);

        if ($paramCount > 0) {
            $stmt->bind_param (str_repeat ('s', $paramCount), ...$values);
        }

        $stmt->execute ();

        $result = $stmt->get_result () or die ($this->link->error);

        return ($result->num_rows == 1)
        ? $result->fetch_array (MYSQLI_ASSOC)
        : $result->fetch_all (MYSQLI_ASSOC);
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
     * You can get all entrys as objects, for that, pass -1 as id
     * 
     * @example $link->ormQuery ('Litecms\Apps\Articles\Article', ['id = 6']); // Get article with id = 6 and return as object
     * @example $link->ormQuery ('Litecms\Apps\Articles\Article', ['title LIKE Art of %']); // Get articles with condition
     * @example $link->ormQuery ('Litecms\Apps\Articles\Article', 1); // Get all articles
     * 
     * @param string $class - class to fetch
     * @param string $table – table, where get all data
     * @param array $values
     * 
     * @return object|null
     */
    public function ormQuery (string $class, $condition) {
        $query = sprintf ("SELECT * FROM %s WHERE %s",
            $this->prefix.$class::$table,
            $this->regex ($condition)
        );
        $result = $this->link->query ($query);

        if (!$result) {
            // Return null
            return "Query '$query' do not returns anything";
        }

        // If returns only one entry
        if ($result->num_rows == 1) {
            return $result->fetch_object ($class);
        }

        // Fetch all entrys as objects
        $array = [];
        while ($obj = $result->fetch_object($class)) {
            $array[] = $obj;
        }
        return $array;
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
    public function insert (string $table, array $data) {
        // Exclude all empty variables
        $data = array_filter ($data);

        $result = $this->query ("INSERT INTO %s (`%s`) VALUES ('%s')", 
            $this->prefix.$table,
            implode ("`, `", array_keys ($data)),
            implode ("', '", array_values ($data))
        );

        return $result;
    }

    /**
     * Send query to database like SELECT <fields> FROM <table> WHERE <condition>
     * 
     * @example $link->select ("user"); // Get all entrys from DB
     * @example $link->select ("user", ['id', 'username'], ['name LIKE Jo%', 'email LIKE %@yahoo.com'], 10, 'email', true);
     * @example $link->select ("user", 'name', ["name=John", "age>18"]) // Error. Do not forget about spaces in condition
     * 
     * @param string $table
     * @param string|array $fields – selected fields
     * @param string|array $condition
     * @param string $order – field to order result (id by default)
     * @param bool $reverse – reverse result list or not
     * 
     * @return array|bool
     */
    public function select (string $table, $fields = "*", $condition = "", $limit = 50, string $order = 'id', bool $reverse = false) {
        // Collapse all fields to one string
        if (gettype ($fields) == 'array') {
            $fields = '`'. implode ('`, `', $fields) . '`';
        }

        $query = "SELECT %s FROM %s WHERE %s ORDER BY `%s` %s";

        // Add limit if exists
        if (!empty ($limit)) {
            $query .= " LIMIT $limit";
        }

        $condition = $this->regex ($condition);

        // Set $reverse "DESC" or "ASC" value based on it's current value
        $reverse = ($reverse === true)
        ? "DESC"
        : "ASC";

        $result = $this->query ($query,
            $fields,
            $this->prefix . $table,
            $condition,
            $order,
            $reverse
        );


        return $result;
    }

    public function createTable (string $name, array $fields)
    {
        $check = $this->query ("SELECT * FROM information_schema.tables WHERE table_schema = '%s' AND table_name = '%s' LIMIT 1", 'litecms', $this->prefix.$name);
        if ($check !== false) {
            // Table already exists
            return "Table '".$this->prefix.$name."' already exists";
        }

        // Merge array like `key` value, `key2` value2
        $data = [];
        foreach ($fields as $key => $value) {
            $data[] = sprintf ("`%s` %s", $key, $value);
        }
        $data = implode (', ', $data);

        $result = $this->query ("CREATE TABLE %s (%s)", $this->prefix.$name, $data);

        return $result;
    }

    /**
     * Returns string like `field` = "value" 
     * If array is empty, returns "1"
     * 
     * @param $input – array like ['id = 4', 'age > 12']
     * 
     * @return string
     */
    private function regex ($input) {
        $output = "";

        if (gettype ($input) == 'array' and !empty ($input)) {
            preg_match_all ("/(\S*)\s?(!=|<=|>=|<|>|=|LIKE)\s?(\S*)/", implode(' ', $input), $matches, PREG_SET_ORDER);
            // It's bad practice, but I don't know better way :c
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
        $result = $this->query ("DELETE FROM `%s` WHERE %s",
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
        $this->link->close ();
        unset ($this->link);
    }
    
}