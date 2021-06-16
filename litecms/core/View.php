<?php

namespace Litecms\Core;

use const Litecms\Config\{Dirs, Request, Debug};

class View
{
    /**
     * Extend base template with expander
     * 
     * @param string $base Path to base template
     * @param string $expander Path to extention template
     * @param array $context Variables, avalible in both templates 
     * @return string
     */
    public static function extend(Request $request, string $base, string $expander, array $context = [])
    {
        $expander = self::render($request, $expander, $context);
        $context['MAIN'] = $expander;
        $template = self::render($request, $base, $context);
        
        return $template;
    }


    /**
     * Render template with context
     * 
     * @param string $template Name of template
     * @param array $context Array of values, used as variables in template
     * @return string 
     */
    public static function render(Request $request, string $template, array $context = [])
    {
        $template = View::getTemplate($template);
        if (!$template) return;
        $context['request'] = $request;
        extract($context, EXTR_SKIP);

        ob_start();
        include $template;
        $render = ob_get_contents();
        ob_end_clean();

        return $render;
    }


    /**
     * Find template in templates folder
     * 
     * @param string $template Template name
     */
    public static function getTemplate(string $template): string
    {
        foreach (Dirs['templates'] as $folder) {
            $file = realpath($folder.'/'.$template);
            if ($file === false) continue;
            return $file;
        }

        if (Debug === true) {
            die("Can't find template '{$template}'. Check your templates folders in 'litecms/Settings.php' file");
        }
    }
}