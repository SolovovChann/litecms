<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Page;

class Controller
{
    private $page;
    
    public function __construct () {
        $this->page = new Page ($_SERVER['REQUEST_URI']);
    }
}