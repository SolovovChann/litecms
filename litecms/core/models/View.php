<?php

namespace Litecms\Core\Models;

use Litecms\Assets\Filesystem;
use Litecms\Core\Models\Router;
use const Litecms\Config\Project\Dirs;

class View
{
    /**
     * Render template with context
     * Using open buffering for rendering template, returns result of buffering, that could be printed
     * 
     * @param string $template – name of template
     * @param array $context – array of variables, used in template
     * 
     * @return string
     */
    static public function render (string $template, array $context = []) {
        // Search all folders, set in config file
        foreach (Dirs['templates'] as $folder) {
            $file = Filesystem::path ($folder, $template);

            if (file_exists ($file)) {               
                // Context variables is avalible as regular variables
                extract ($context, EXTR_SKIP);
        
                // Start buffering
                ob_start ();
                include $file;
                $render = ob_get_contents ();
                ob_end_clean ();
        
                return $render;
            }
        }

        throw Router::throw404 ("Template '$template' is not exists");
    } 
}