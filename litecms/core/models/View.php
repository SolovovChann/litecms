<?php

namespace Litecms\Core\Models;

use function Litecms\Assets\path;
use const Litecms\Config\Directories as Dirs;
use const Litecms\Config\ProjectSettings as Settings;

class View
{
    private $context = [
        'site' => [
            'name' => Settings['name'],
            'url' => Settings['url'],
        ],
    ];

    public function render ($template, $context) {
        $file = path (Dirs['templates'], $template);

        if (!$file) {
            return "Can't find template '$template'";
        }

        $context = array_merge ($this->context, $context);
        extract ($context, EXTR_SKIP);

        ob_start ();
        include $file;
        $render = ob_get_contents ();
        ob_end_clean ();
        return $render;
    }
}