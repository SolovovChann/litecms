<?php

namespace Litecms\Core\Models;

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
    static public function render (string $template, array $context = []) {} 
}