<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Connection;
use function Litecms\Assets\pureUrl;
use const Litecms\Config\DBPprefix;

class Page extends Model
{
    public static $database = DBPprefix . "pages";
	public static $verboseName = "Страница";
	public static $verbosePlural = "Страницы";

    public $url;
    public $title;

    public function __construct () {}

    /**
     * Find URL in DB
     * 
     * @param string $url 
     * @return void
     */
    public function getUrl ($url) {
        // Trim url from slashes on start and end
        $url = pureUrl ($url);

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