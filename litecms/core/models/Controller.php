<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Page;

class Controller
{
	public function __construct () {
		$url = $_SERVER['REQUEST_URI'];
		$this->page = new Page ();
	}
}