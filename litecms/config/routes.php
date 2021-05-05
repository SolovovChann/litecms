<?php

namespace Litecms\Config\Routes;

use Litecms\Core\Models\Router;

Router::addindex ('Litecms\Apps\Home\HomeController');
Router::add404 ('Litecms\Assets\Controller404');
Router::add ('home', 'Litecms\Apps\Home\HomeController');
Router::add ('admin', 'Litecms\Apps\Admin\AdminController');
Router::add ('articles', 'Litecms\Apps\Articles\ArticlesController');