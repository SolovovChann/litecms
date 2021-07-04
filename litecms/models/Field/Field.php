<?php

namespace Litecms\Models\Field;

/**
 * Base field class, implements the ORM
 */
class Field
{
    public $datatype;
    public $default;
    public $max;
    public $null;
    public $mmetype;

    public function __construct(array $params = [])
    {
        $this->default = $params['default'] ?? null;

        if (gettype($params['null']) === 'boolean') {
            $this->null = $params['null'];
        }

        if (isset($params['max'])) {
            $this->max = $params['max'];
        }
    }

    public function __toString()
    {
        return $this->default ?? $this->datatype;
    }
}
