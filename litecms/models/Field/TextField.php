<?php

namespace Litecms\Models\Field;

/**
 * Store data as long text
 */
class TextField extends Field
{
    public $datatype = 'text';
    public $mmetype = "textarea";
}