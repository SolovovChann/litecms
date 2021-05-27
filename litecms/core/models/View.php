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
    public static function render (string $template, array $context = []) {
        $file = View::get_template ($template);
        
        if (!$file) {
            echo "Can't file template '$template'. Check for DIRS in litecms/config/project.php file.";
            return;
        }

        // Provide access to context within a template 
        extract ($context, EXTR_SKIP);

        // Start buffering
        ob_start ();
        include $file;
        $render = ob_get_contents ();
        ob_end_clean ();

        return $render;
    }


    /**
     * Extend existing template with new template 
     */
    public static function extend (string $base, string $template, array $context = [])
    {
        $extender = View::render ($template, $context);
        $context['MAIN'] = $extender;

        return View::render ($base, $context);
    }


    /**
     * 
     */
    public static function get_template (string $template)
    {
        foreach (Dirs['templates'] as $folder) {
            $file = Filesystem::path ($folder, $template);

            if (file_exists ($file)) {
                return $file;
            }
        }
    }
}