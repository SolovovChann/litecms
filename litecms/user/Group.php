<?php

namespace Litecms\User;

use Litecms\Models\Model;
use Litecms\Models\Field;

class Group extends Model
{
    public static $table = "user_group";
    public static $verbose = "Группа пользователей";
    public static $plural = "Группы пользователей";

    public function init()
    {
        $this->name = new Field\StringField();
        $this->description = new Field\TextField();
        $this->accessLevel = new Field\IntField();

        return $this;
    }
}