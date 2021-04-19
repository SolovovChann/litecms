<?php

namespace litecms;

# Warning! Disable on production server
const DEBUG = true; 

const PROJECT_SETTINGS = [
	'name' => 'Test project', # Use it by call Application class as string 
	'allowed hosts' => [
		'localhost',
		'litecms',
		'127.0.0.1'
	]
];

# Use with $_BASE_DIR
const DIRS = [
	'litecms' => '/litecms',
	'core' => '/litecms/core',
	'components' => '/litecms/components',
	'templates' => '/litecms/templates',
];

# Don't forget to delete it if you upload code on github 
const CONNECTION = [
	'host' => 'localhost',
	'user' => 'root',
	'password' => 'root',
	'database' => 'litecms'
];

?>