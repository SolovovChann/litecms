<?php

namespace Litecms\Models;

use Litecms\Core\Connection;

class Model
{
    public static $table;
    public static $verbose;
    public static $plural;

    //  Static methods  //


    /**
     * Get all model's objects from DB 
     * 
     * @param array $params Array of paramethers, used for querying to DB
     * @param int $params['limit'] How many entries will be returned
     * @param string $params['order'] Which field is used to order the entries
     * @param bool $params['reverse'] Use reversed order
     * @return array
     */
    public static function all(array $params = []): array {
        $limit = $params['limit'] ?? -1;
        $order = $params['order'] ?? 'id';
        $reverse = $params['reverse'] ?? false;

        $reverse = ($reverse === true)
        ? "DESC"
        : "ASC";
        
        $pdo = new Connection;
        $sql = sprintf("SELECT * FROM %s WHERE 1 ORDER BY `%s` %s",
            $pdo->prefix(static::$table),
            $order,
            $reverse,
        );

        if ($limit > 0) {
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
     * @param array $params Array of paramethers, used for querying to DB
     * @param int $params['limit'] How many entries will be returned
     * @param string $params['order'] Which field is used to order the entries
     * @param bool $params['reverse'] Use reversed order
     * @return array
     */
    public static function filter(
        string $condition,
        array $values = [],
        array $params = []
    ): array {
        $limit = $params['limit'] ?? -1;
        $order = $params['order'] ?? 'id';
        $reverse = $params['reverse'] ?? false;

        $pdo = new Connection;

        $reverse = ($reverse === true)
        ? "DESC"
        : "ASC";

        $sql = sprintf("SELECT * FROM %s WHERE %s ORDER BY `%s` %s",
            $pdo->prefix(static::$table),
            $condition,
            $order,
            $reverse,
        );

        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";
        }

        $result = $pdo->query($sql, $values, get_called_class());
        return $result;
    }


    /**
     * Use object to form table in database
     * 
     * @return void
     */
    public function createTable()
    {
        $table = [];
        $table[] = "`id` int(11) not null primary key auto_increment";
        $pdo = new Connection;

        foreach ($this->init() as $key => $value)
        {
            if ($value->datatype === 'foreign key') {
                $column = "`{$key}` int(11), foreign key ({$key}) references {$pdo->prefix($value->reference::$table)}(id) ON DELETE {$value->ondelete} ON UPDATE {$value->onupdate}";
            } else {
                $column = "`{$key}` {$value->datatype}";

                if ($value->max !== null) {
                    $column .= "($value->max)";
                }

                if ($value->null !== null) {
                    $column .= $value->null === true ? " null" : " not null";
                }

                if ($value->default !== null) {
                    $column .= " default {$value->default}";
                }
            }

            $table[] = $column;
        }

        $table = sprintf("create table if not exists %s (%s) ", $pdo->prefix(static::$table), implode(', ', $table));
        $result = $pdo->query($table);

        return $result;
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
            "SELECT * FROM {$pdo->prefix(static::$table)} WHERE `id` = ?", [$id],
            get_called_class()
        );
        $result = $result[0];

        return $result;
    }


//  Object's methods  //


    public function __toString()
    {
        return $this->id;
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
     * Function, describes fields as Mysqli entitys
     * 
     * @return self
     */
    public function init()
    {
        return $this;
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

            $this->createTable();
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
     * @param array $args Associative array like field => value
     * @return void
     */
    public function set(array $args): void
    {
        foreach ($args as $key => $value) {
            $this->$key = $value;
        }
    }
}