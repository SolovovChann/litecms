<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Model;

class Page extends Model
{
    public static $table = 'pages';
    public static $verboseName = 'Страница';
    public static $verboseNamePlural = 'Страницы';

    public $url;
    public $title;
    public $description; 
}