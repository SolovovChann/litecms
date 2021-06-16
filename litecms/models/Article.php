<?php

namespace Litecms\Models;

use Litecms\Models\Model;

class Article extends Model
{
    public static $table = "article";
    public static $verbose = "Статья";
    public static $plural = "Статьи";

    public $title;
    public $text;
    public $date;
    public $author;
    public $likes = 0;
    public $views = 0;
}