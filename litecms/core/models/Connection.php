<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Model;
use const Litecms\Config\Connection as Config;
use \mysqli;

class Connection extends Model
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
    public function query ($query, ...$args) {
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
    public function select ($table, $query, $condition) {
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
     * 
     * @param string $table
     * @param array $data
     * 
     * @return bool
     */
    public function insert ($table, ...$data) {
        $query = sprintf (
            "INSERT INTO %s (`%s`) VALUES (\"%s\")",
            $this->prefix . $table,
            implode('`, `',array_keys($data)),
            implode('", "',array_values($data))
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
    static public function formatResult ($result) {
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