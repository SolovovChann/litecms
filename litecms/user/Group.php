<?php

namespace Litecms\User;

use Litecms\Models\Model;

class Group extends Model
{
    public static $table = "user_group";
    public static $verbose = "Группа пользователей";
    public static $plural = "Группы пользователей";

    public $name;
    public $description;
    public $accessLevel;
}