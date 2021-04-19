<?php

namespace litecms\core\classes;

class Application
{
	public $name;	# Example project
	public $url;	# www.example.com
	public $pages; # Array of pages included
	public $db_prefix; # All DB tables will start's with

	private $connection; # Connection to DB
	
	public function __construct ()
	{ # Calls at setup
	} 
	public function __toString () {
		return $this->name;
	}

	public static function init ($file) : void { # Includes every page
	
	}
	public static function load ($template) : void { # Load template by it's name
		
	} 
	public static function checkUpdates () : void {

	}
	
}

?>