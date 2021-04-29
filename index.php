<?php

require_once 'litecms/autoloader.php';

use Litecms\Core\Models\Router;
use function Litecms\Assets\path;
use function Litecms\Assets\debug;

// Enable page routing
Router::start ();