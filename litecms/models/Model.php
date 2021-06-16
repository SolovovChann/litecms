<?php

namespace Litecms\Models;

use Litecms\Core\Connection;

class Model
{
    public $id;

    public static $table;
    public static $verbose;
    public static $plural;

    //  Static methods  //


    /**
     * Get all model's objects from DB 
     * 
     * @param int $limit = 50, how many entries will be returned. Use $limit = -1 to get all entries
     * @param string $order = 'id', which field is used to order the entries
     * @param bool $reverse = false, use reverse order
     * @example Article::all(); // Get first 50 articles
     * @example Article::all(-1) // Get all articles
     * @example Article::all(-1, 'title') // Get all articles and sort it by title
     * 
     * @return array
     */
    public static function all(
        int $limit = 50,
        string $order = "id",
        bool $reverse = false
    ): array {
        $pdo = new Connection;

        $reverse = ($reverse === true)
        ? "DESC"
        : "ASC";

        $sql = sprintf("SELECT * FROM %s WHERE 1 ORDER BY `%s` %s",
            $pdo->prefix(static::$table),
            $order,
            $reverse,
        );
        if ($limit !== -1) {
            $sql .= " LIMIT {$limit}";
        }
        
        $result = $pdo->query($sql, [], get_called_class());
        return $result;
    }


    /**
     * Find models objects in DB that match the condition
     * 
     * @param string $condition Filter condition 
     * @param array $values Array of data, used in condition
     * @param int $limit 
     * @param string $order
     * @param bool $reverse
     * @example Article::filter("name LIKE ? AND age < ?", ["Jo%", 29]) // Get all users, whoes name starts with Jo and younger than 29 years.  
     * @example Article::filter("name LIKE ? AND age < ?", ["Jo%", 29], 10, 'name', true) // Same result but set limit to 10 and reverse order by name
     * @return array
     */
    public static function filter(
        string $condition,
        array $values = [],
        int $limit = 50,
        string $order = "id",
        bool $reverse = false
    ): array {
        $pdo = new Connection;

        $reverse = ($reverse === true)
        ? "DESC"
        : "ASC";

        $sql = sprintf("SELECT * FROM %s WHERE %s ORDER BY `%s` %s LIMIT %s",
            $pdo->prefix(static::$table),
            $condition,
            $order,
            $reverse,
            $limit
        );

        $result = $pdo->query($sql, $values, get_called_class());
        return $result;
    }


    /**
     * 
     */
    public function formTable()
    {
        $table = [];

        foreach ($this as $key => $value) {
            if ($key === 'id') {
                $table[] = "id int(11) not null primary key auto_increment";
                continue;
            }
            $result = "{$key} varchar(1024)";

            if ($value !== null) {
                $result .= " DEFAULT {$value}";
            }

            $table[] = $result;
        }

        return $table;
    }


    /**
     * Get model object via unique key
     * 
     * @param int $id Unique object's id
     * @example Article::get(1) // Get article with 'id' = 1
     * @return object
     */
    public static function get(int $id): ?object
    {
        $pdo = new Connection;
        $result = $pdo->query(
            "SELECT * FROM {$pdo->prefix(static::$table)} WHERE `id` = ?",
            [
                $id
            ],
            get_called_class()
        );
        $result = $result[0];

        return $result;
    }


//  Object's methods  //


    public function __toString()
    {
        return (isset(static::$plural))
        ? static::$plural
        : $this->id;
    }


    /**
     * Deteles selected entry from DB
     * 
     * @return void
     */
    public function delete(): void
    {
        $pdo = new Connection;

        $pdo->query("DELETE FROM {$pdo->prefix(static::$table)} WHERE `id` = ?", [
            $this->id
        ]);
    }


    /**
     * Saves selected object in database
     * 
     * @return void
     */
    public function save(): void
    {
        $pdo = new Connection;

        // Only entrys from DB has ID's
        if(!empty($this->id)) {
            $entry = static::get($this->id) or die("Can't get entry with id = {$this->id}");
            $change = [];

            // Check for 
            foreach($this as $key => $value) {
                if ($entry->$key !== $value) {
                    $change[$key] = $value;
                }
            }

            if (empty($change)) {
                return;
            }

            // Format input data as `key1` = ?, `key2` = ?...
            $data = implode(',', array_map(
                function ($v) { return sprintf("`%s` = ?", $v); },
                array_keys($change)
            ));

            $sql = sprintf("UPDATE %s SET %s WHERE `id` = %s",
                $pdo->prefix(static::$table),
                $data,
                $this->id
            );

            $pdo->query($sql, array_values($change));
        }
        else {
            $input = get_object_vars($this);
            $keys = implode(', ', array_map(
                function ($v) { return sprintf("`%s`", $v); },
                array_keys($input)
            ));
            $values = array_values($input);

            $sql = sprintf("INSERT INTO `%s` (%s) VALUES (%s)",
                $pdo->prefix(static::$table),
                $keys,
                str_repeat("?, ", count($input) - 1)."?"
            );

            $pdo->query($sql, $values);
        }
    }


    /**
     * Use $args array to fill object's fields
     * 
     * @example $article = new Article();
     * $article->set([
     *      'author' => 'John',
     *      'title' => 'Using set method to models',
     *      'date' => date()
     * ]);
     * @param array $args associative array like field => value
     * @return void
     */
    public function set (array $args): void
    {
        foreach ($args as $key => $value) {
            $this->$key = $value;
        }
    }
}