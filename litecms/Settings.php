<?php

namespace Litecms\Config;

// Warning! Disable on production
const Debug = true;
const Name = "Тестовый проект";
const Timezone = "UTF";

// Path to folders from document's root
const Dirs = [
    'models' => 'models',
    'root' => 'litecms',
    'static' => 'static',
    'uploads' => 'uploads',

    'templates' => [
        'templates',
    ],
];

// List of the installed applications.
// Add your models into the END of the list
const Models = [
    'Litecms\User\User',
    'Litecms\User\Group',
];

// Warning! Do not share your connection data to strangers!
const Connection = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => 'root',
    'database' => 'testdb',
    'table_prefix' => 'lcms',
];

const SessionTime = 5;