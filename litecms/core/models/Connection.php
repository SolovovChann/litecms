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
     * Use PDO for create connection. Basicly it's a PDO wrapper 
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
            $query = vsprintf ($query, ...$values);
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
     * @example $link->select ("user", ['id', 'username'], "`name` = John AND `age > 18`");
     * @example $link->select ("user", '*'); // Get all users from DB
     * 
     * @param string $table
     * @param string|array $fields – selected fields
     * @param string $condition
     * 
     * @return array|bool
    */
    public function select (string $table, $fields, string $condition = "1") {
        if ($fields != '*') {
            $fields = "('". implode (', \'', $fields) ."')";
        }
        
        $result = $this->query ("SELECT %s FROM %s WHERE %s", [
            $fields,
            $this->prefix . $table,
            $condition
        ]);

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