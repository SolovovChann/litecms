<?php

namespace litecms;

# Settings
require_once 'Settings.php';

# Autoloader
spl_autoload_register(function ($class) {
	$prefix = 'litecms\\core\\';
	$base_dir = __DIR__ . DIRS['core'];

	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0)
		return;

	$relative_class = substr($class, $len);
	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

	if (file_exists($file)) {
		require $file;
	}
});