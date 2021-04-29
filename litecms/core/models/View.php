<?php

namespace Litecms\Core\Models;

use function Litecms\Assets\path;
use function Litecms\Assets\pureUrl;
use const Litecms\Config\Directories as Dirs;

class View
{
    /**
     * Render template via context
     * 
     * @param string $template – name of template
     * @param array $context – variables will be used in rendering
     */
    static public function render ($template, $context = []) {
        $file = path (Dirs['templates'], $template);

        if (!$file) {
            return "Can't find template '$template'";
        }

        $defaultContext = [
            'app' => new Application (),
            'page' => new Page (),
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