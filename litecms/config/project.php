<?php

namespace Litecms\Config\Project;

// Warning! Disable on production
const Debug = true;

const Name = "Example Project";

const Url = "litecms";

const Dirs = [
    'root' => 'litecms',
    'core' => 'litecms/core',
    'content' => 'litecms/core/content',
    
    'apps' => 'apps',
    'static' => 'static',
    'templates' => 'templates',
];

// Register your models here
const Applications = [
    'Litecms\Apps\Articles\Article',
    'Litecms\Apps\Articles\Comment',
];