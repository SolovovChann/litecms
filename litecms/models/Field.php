<?php

namespace Litecms\Models;

class Field
{
    /**
     * Create string to create row in database
     * 
     * @param string $datatype Database table's data type like varchar, int or date
     * @param ?array $paramethers Array of custom paramethers.
     * Use next paramethers to define propertys: 
     * > null. Allow nullable values
     * > default. Pass default value when create new object 
     * > max. Define max allowed value of field
     */
    public function __contsruct(string $datatype, array $paramethers = [])
    {
        $this->datatype = $datatype;
        $this->default = $paramethers['default'] ?? null;
        $this->max = $paramethers['max'] ?? null;
        $this->null = $paramethers['null'] ?? null;
    }


    public static function new(string $datatype, array $paramethers = [])
    {
        $field = new self($datatype, $paramethers);
        return json_encode($field);
    }


    public function __toString()
    {
        return $this->default;
    }


    /**
     * Format object to ready sql column query
     * 
     * @return string
     */
    protected function format(): string
    {
        $string = "{$this->datatype}";

        if (!empty($this->max)) {
            $string .= "({$this->max})";
        }

        if ($this->default !== null) {
            $string .= " DEFAULT {$this->default}";
        }

        if ($this->null === true) {
            $string .= " NULL";
        } elseif ($this->null === false) {
            $string .= " NOT NULL";
        }

        return $string;
    }
        
}