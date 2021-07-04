<?php

namespace Litecms\Models\Field;

/**
 * Store data as Mysql date 
 */
class DateField extends Field
{
    public $datatype = 'date';
    public $mmetype = "datetime-local";
}