<?php

namespace Litecms\Core\Models;

class Model
{
    public static $database;
    public static $verboseName;
    public static $verbosePlural;

    public function __toString () {
        return get_class ($this);
    }
}