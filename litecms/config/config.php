<?php

namespace Litecms\Config;

// Warning! Disable on production server
const Debug = true;

const ProjectSettings = [
    'name' => 'Test project',
    'url' => 'litecms',
    'allowed_hosts' => [
        'localhost',
        'litecms',
        '127.0.0.1',
    ]
];

// Use with Litecms/Assets/path function to get absolute path
const Directories = [
    'root' => 'litecms',
    'core' => 'litecms/core',
    'content' => 'litecms/core/content',
    
    'apps' => 'apps',
    'static' => 'static',
    'templates' => 'templates',
];

const DBPprefix = "lcms_";

// Warning! Do not share your DB password
const Connection = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'root',
    'database' => 'litecms',
    'table_prefix' => 'lcms_',
];

const Urlpatterns = [
    '' => [
        'controller' => Directories['apps'] . '/home/controller.php',
        'class' => 'home_controller',
    ],
    'home' => [
        'controller' => Directories['apps'] . '/home/controller.php',
        'class' => 'home_controller',
    ],
    'test' => [
        'controller' => Directories['apps'] . '/test/controller.php',
        'class' => 'test_controller',
    ],
    'admin' => [
        'controller' => Directories['apps'] . '/admin/controller.php',
        'class' => 'admin_controller',
    ],
    '404' => [
        'controller' => 'litecms/core/urls/404/controller.php',
        'class' => 'page404_controller',
    ],
];

const Models = [
    "Litecms\Core\Models\Page",
    "Litecms\Core\Models\User",
];