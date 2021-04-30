<?php

namespace Litecms\Config;

use Litecms\Core\Models\Router;

Router::addindex ('Litecms\Apps\Home\HomeController');
Router::add ('home', 'Litecms\Apps\Home\HomeController');