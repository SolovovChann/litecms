<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Model;
use const Litecms\Config\ProjectSettings as Config;

class Application extends Model
{
    public $name = Config['name'];
    public $url = Config['url'];
}