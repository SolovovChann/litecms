<?php

namespace Litecms\Core\Models;

use const Litecms\Config\ProjectSettings as Config;

class Application
{
    public $name = Config['name'];
    public $url = Config['url'];
}