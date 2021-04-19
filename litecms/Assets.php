<?php

namespace litecms;

class Assets
{
	public static function debug ($data = "Hello world!") {
		if (DEBUG == true)
			echo "<pre>", var_dump($data), "</pre>";
	}
}


?>