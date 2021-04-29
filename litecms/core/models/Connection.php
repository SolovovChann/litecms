<?php

namespace Litecms\Core\Models;

use const Litecms\Config\Connection as Config;
use \mysqli;
use \mysqli_result;

class Connection
{
    protected $link;
    public $prefix;
    
    /**
     * Creates connection to database
     */
    public function __construct () {
        $this->link = new mysqli (
            Config['host'],
            Config['user'],
            Config['password'],
            Config['database']
        );
        $this->prefix = Config['table_prefix'];
    }

    /**
     * Send query to database
     * 
     * @param string $query
     * @param array $args - array of arguments for vsprintf () function
     * 
     * @return mysqli_result
     */
    public function query (string $query, ...$args) {
        $query = vsprintf ($query, $args);
        $result = $this->link->query ($query);

        return $result;
    }

    /**
     * Select information from table using condition
     * 
     * @param string $table - Source of data
     * @param string $query - Type of data
     * @param string $condition - Filter data
     * 
     * @return array
     */
    public function select (string $table, string $query, string $condition) {
        $query = sprintf (
            "SELECT %s FROM %s WHERE %s", 
            $query,
            $this->prefix . $table,
            $condition
        );
        $result = $this->query ($query);

        return Connection::formatResult ($result);
    }

    /**
     * Input data into a table
     * Use associatiive array to insert data into table where keys is columns
     * 
     * @param string $table
     * @param array $data
     * 
     * @return bool
     */
    public function insert (string $table, ...$data) {
        $query = sprintf (
            "INSERT INTO %s (`%s`) VALUES (\"%s\")",
            $this->prefix . $table,
            implode ('`, `', array_keys($data)),
            implode ('", "', array_values($data))
        );

        $result = $this->query ($query);
        return $result;
    }

    /**
     * Format result as Associative array
     * 
     * @param mysqli_result $result
     * 
     * @return array
     */
    static public function formatResult (mysqli_result $result) {
        return ($result->num_rows > 1)
            ? $result->fetch_all (MYSQLI_ASSOC)
            : $result->fetch_array (MYSQLI_ASSOC);
    }

    /**
     * Close connection
     * 
     * @return void
     */
    public function close () {
        $this->link->close ();
    }
}