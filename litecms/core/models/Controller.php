<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Page;
use function Litecms\Assets\pureUrl;

class Controller
{
    private $page;
    
    public function __construct () {
        $this->page = new Page (pureUrl ($_SERVER['REQUEST_URI']));
    }
}