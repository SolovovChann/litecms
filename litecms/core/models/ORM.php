<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Connection;
use const Litecms\Config\Connection\TablePrefix;

class ORM
{
    const restrict = 'RESTRICT';
    const cascade = 'CASCADE';
    const setNull = 'SET NULL';
    const nothing = 'NO ACTION';

    /**
     * Create timestamp field in database
     * 
     * @param string $field – Name of field
     * @param $null = false – Accept nullable values 
     * @param $autonow = false – Default value
     * 
     * @return string
     */
    public static function date (string $field, $null = false, $autonow = false) {
        $format = ($autonow === true)
        ? ORM::format ($null, "CURRENT_TIMESTAMP")
        : ORM::format ($null, null);

        $result = sprintf ("`%s` TIMESTAMP %s", $field, $format);
        return $result;
    }

    /**
     * Use another table's id as foreign key
     * 
     * @param string $field – Name of field
     * @param string $table – Foreign table, which id is foreign key 
     * @param string $table – Foreign table
     * @param string $onDelete – Mysqli action called when a foreign key is dropped. Use on of ORM constants: ORM::restrict, ORM::cascade, ORM::setNull or ORM::nothing
     * @param string $onDelete – Mysqli action called when a foreign key is dropped. Use on of ORM constants:  
     * @param string|null $onUpdate – The Mysqli action called when editing a foreign key. If passed null, will be set the same as $onDelete 
     * 
     * @return string
     */
    public static function foreign (string $field, string $table, string $onDelete, $onUpdate = null) {
        if ($onUpdate === null) {
            $onUpdate = ORM::nothing;
        }
        // author INT(15), FOREIGN KEY (author) REFERENCES lcms_users(`id`) ON DELETE CASCADE ON UPDATE RESTRICT
        $result = sprintf ('`%1$s` INT(15), FOREIGN KEY (%1$s) REFERENCES %2$s(`id`) ON DELETE %3$s ON UPDATE %4$s',
            $field,
            TablePrefix.$table,
            $onDelete,
            $onUpdate
        );
        return $result;
    }

    /**
     * Format null and default strings
     * 
     * @return string
     */
    private static function format ($null = false, $default = null)
    {
        $null = ($null === true)
        ? 'NULL'
        : 'NOT NULL';
    
        $default = ($default === null)
        ? ''
        : sprintf (" DEFAULT %s", $default);
    
        return sprintf ("%s%s", $null, $default);
    }

    /**
     * Create Ineger field in database
     * 
     * @param string $field – Name of field
     * @param int $max = 255 – Max value
     * @param bool $null = false – Accept nullable values
     * @param mixed $default = null – Default value
     * 
     * @return string
     */
    public static function int (string $field, int $max = 255, $null = false, $default = null) {
        $result = sprintf ("`%s` int(%d) %s", $field, $max, ORM::format ($null, $default));

        return $result;
    }

    /**
     * Get all fields and create table in database
     * 
     * @param $table – Name of table
     * @param $fields – Array of fields
     * 
     * @return void
     */
    public static function migrate (string $table, $fields) {
        // Add ID field
        array_unshift ($fields, ORM::primary ());

        $link = new Connection;
        $link->createTable ($table, $fields);
    }

    /**
     * Create ID integer field as primary key 
     * 
     * @return string
     */
    public static function primary () {
        $result = "`id` int(15) PRIMARY KEY NULL AUTO_INCREMENT";

        return $result;
    }

    /**
     * @param string $field – Name of field
     * @param bool $null = false – Accept nullable values
     * @param mixed $default = null – Default value
     * 
     * @return string
     */
    public static function text (string $field, $null = false, $default = null) {
        $result = sprintf ("`%s` text(2048) %s", $field, ORM::format ($null, $default));

        return $result;
    }

    /**
     * Create text field in database
     * 
     * @param string $field – Name of field
     * @param int $max = 255 – Max value
     * @param null = false – Accept nullable values
     * @param mixed $default = null – Default value
     * 
     * @return string
     */
    public static function varchar (string $field, int $max = 255, $null = false, $default = null) {
        $result = sprintf ("`%s` varchar(%d) %s", $field, $max, ORM::format ($null, $default));

        return $result;
    }
}