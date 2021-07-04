<?php

namespace Litecms\Models\Field;

/**
 * Store data as integer number
 */
class IntField extends Field
{ 
    public $datatype = 'int';
    public $max = 255;
    public $mmetype = "number";
}