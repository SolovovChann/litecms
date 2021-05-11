<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\{Model, ORM};

class Page extends Model
{
    public static $table = 'pages';
    public static $verboseName = 'Страница';
    public static $verboseNamePlural = 'Страницы';

    public $url;
    public $title;
    public $description;

    public static function init ()
    {
        $url = ORM::varchar (255, "NULL", true);
        $title = ORM::varchar (255);
        $description = ORM::varchar (255);

        return ORM::migrate (self::$table, [
            'url' => $url,
            'title' => $title,
            'description' => $description
        ]);
    }
}