<?php

namespace litecms\core\classes;

class Page
{
	public $title;
	public $description;

	public function __construct () {}

	public function save ($file) {} # Saves changes at $file
	public static function getPage ($file) {}
	public static function component ($name, $template, $settings) {}
}

?>