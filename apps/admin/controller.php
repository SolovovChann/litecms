<?php

// Admin's homepage
use Litecms\Core\Models\Connection;
use Litecms\Core\Models\Controller;
use Litecms\Core\Models\View;
use function Litecms\Assets\debug;

class admin_controller extends Controller
{
    public function default () {
        echo View::render ('admin.php', ['title' => 'Администрирование']);
    }

    public function add ()
    {
    }

    public function remove ()
    {
    }

    public function edit ()
    {
    }

    public function model (String $class)
    {
        $class = 'Litecms\Core\Models\\' . $class;
        debug ($class, $model);
        $link = new Connection ();
        // $result = $link->query ("SELECT * FROM %s WHERE 1", $class::$database);
        // $result = Connection::formatResult ($result);
        // $link->close ();

        echo View::render ('admin-table.php', ['table' => $result]);
    }
}