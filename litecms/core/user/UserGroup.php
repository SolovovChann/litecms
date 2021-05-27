<?php

namespace Litecms\Core\User;

use Litecms\Core\Models\{Model, ORM};

class UserGroup extends Model
{
    public static $table = "user_groups";
    public static $verboseName = "Группа пользователей";
    public static $verboseNamePlural = "Группы пользователей";

    public $name;
    public $description;
    public $accessLevel;
    
    public static function init ()
    {
        return ORM::migrate (self::$table, [
            ORM::varchar ("name"),
            ORM::varchar ("description"),
            ORM::int ("accessLevel"),
        ]);
    }
}