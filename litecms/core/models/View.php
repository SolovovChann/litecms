<?php

namespace Litecms\Core\Models;

use function Litecms\Assets\path;
use const Litecms\Config\Directories as Dirs;

class View
{
    static public function render ($template, $context) {
        $file = path (Dirs['templates'], $template);

        if (!$file) {
            return "Can't find template '$template'";
        }

        $defaultContext = [
            'app' => new Application (),
            'page' => new Page ($_SERVER['REQUEST_URI']),
        ];

        $context = array_merge ($defaultContext, $context);
        extract ($context, EXTR_SKIP);

        ob_start ();
        include $file;
        $render = ob_get_contents ();
        ob_end_clean ();
        return $render;
    }
}