<?php

namespace Litecms\Config\Project;


// Warning! Disable on production
const Debug = true;

const Name = "Example project";

// Register your models here 
const Applications = [
    'Litecms\Core\Models\Pages',
];


// Use with Litecms\Assets\Filesystem\Path function
const Dirs = [
    'litecms' => 'litecms/',
    'core' => 'litecms/core',
    'app' => 'litecms/apps/',
    'templates' => 'templates/',
    'static' => 'static/',
];