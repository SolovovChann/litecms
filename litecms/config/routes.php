<?php

namespace Litecms\Config;

use Litecms\Core\Models\Router;

Router::addindex ('Litecms\Apps\Home\HomeController');
Router::add ('404', 'Litecms\Assets\Controller404');
Router::add ('home', 'Litecms\Apps\Home\HomeController');
Router::add ('admin', 'Litecms\Apps\Admin\AdminController');