<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Connection;

class Page extends Model
{
    public $url;
    public $title;

    public function __construct ($url) {
        // Trim url from slashes on start and end
        $url = trim ($url, '/');

        // Get page from DB by it's URL
        $link = new Connection ();

        // Try to get Alias
        $alias = $link->select ('pages_aliases', '*', "`alias` = '$url'");
        $condition = (!$alias or empty ($alias))
            ? "`url` = '$url'"
            : "`id` = '".$alias['page']."'";

        $result = $link->select ('pages', '*', $condition);

        if (!$result or empty ($result)) {
            return;
        }

        $this->url = $result['url'];
        $this->title = $result['title'];
        $this->description = $result['description'];

        $link->close ();
    }

    /**
     * Includes component into a page
     * 
     * @param string $name Name of the component
     * @param string $template Component's template
     * @param array $args Component's settings
     * 
     * @return void;
     */
    static public function component ($name, $template, ...$args) {

    }
}