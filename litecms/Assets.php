<?php

namespace litecms;

function debug ($data) {
	if (!DEBUG)
		return;

	echo "<pre>", var_dump($data), "</pre>";
}

function path ($path) {
	return $_SERVER['DOCUMENT_ROOT'] . DIRS[$path] ;
}


?>