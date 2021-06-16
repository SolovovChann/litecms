<?php

namespace Litecms\Core;

use const Litecms\Config\Connection as ConnCfg;

class Connection
{
    public $prefix;

    
    /**
     * Create new connection to database.
     * Connection info is set in Litecms\Config\Connection.php file
     * 
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     * @param string $prefix – prefix to all tables in database
     * 
     * @return self
     */
    public function __construct(
        string $host = ConnCfg['host'],
        string $user = ConnCfg['username'],
        string $password = ConnCfg['password'],
        string $database = ConnCfg['database'],
        string $prefix = ConnCfg['table_prefix']
    ) {
        $this->prefix = $prefix;
        $this->pdo = new \PDO ("mysql:dbname={$database};host={$host};charset=utf8mb4", $user, $password) or die($this->pdo->errorInfo());
    }


    public function printError(\PDOStatement $sth)
    {
        $error = $sth->errorInfo();
        return sprintf("<strong>Connection error! {$error[1]}</strong>: {$error[2]}");
    }

    /**
     * Create new table in DB using fields
     * 
     * @param string $name, name of table
     * @param array $fields, array of fields, describes columns
     * 
     * @return void
     */
    public function table(string $name, array $fields): void
    {
        $check = $this->query (
            "SELECT * FROM information_schema.tables WHERE table_schema = ? AND table_name = ? LIMIT 1", [
                'litecms',
                $this->prefix($name),
            ]
        );

        if(!empty($check)) {
            return;
        }

        $data = implode(', ', $fields);
        $sql = "CREATE TABLE {$this->prefix($name)} ({$data})";
        $result = $this->query($sql);
    }


    /**
     * Get table with prefix
     * 
     * @param string $table
     * 
     * @return string
     */
    public function prefix(string $table): string
    {
        $result = sprintf("%s_%s", $this->prefix, $table);
        return $result;
    }


    /**
     * Send query to database
     * 
     * @param string $sql – sql string. Replace data to escape to question mark 
     * @param array $arguments – list of arguments. Every question mark in sql string will be replaces with appropriate element of array
     * @param string $fetchMode = PDO::FETCH_ASSOC – type of return result. Pass class name to return result as object
     * 
     * @return mixed
     */
    public function query(
        string $sql,
        array $arguments = [],
        $fetchMode = \PDO::FETCH_ASSOC
    ) {
        $sth = $this->pdo->prepare($sql);
        $sth->execute($arguments) or die($this->printError($sth));

        if (class_exists ($fetchMode)) {
            $sth->setFetchMode(\PDO::FETCH_CLASS, $fetchMode);
        } else {
            $sth->setFetchMode($fetchMode);
        }

        $result = $sth->fetchAll();
        return $result;
    }
}