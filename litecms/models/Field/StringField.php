<?php

namespace Litecms\Models\Field;

/**
 * Store data as string 
 */
class StringField extends Field
{
    public $datatype = 'varchar';
    public $max = 255;
    public $mmetype = "text";
}