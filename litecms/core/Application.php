<?php

namespace Litecms\Core;

use Litecms\Core\{Route, Request, Connection};
use Litecms\User\User;
use Litecms\Utils\{Message, Debug};
use const Litecms\Config\{Models, Connection as ConnCfg};

class Application
{
    /**
     * Create Settings.php file. Returns false in case of error
     * 
     * @param array $data Associative array with connection data
     * @return bool
     */
    public static function configurate(array $data)
    {
        $settingsText = "<?php

namespace Litecms\Config;

// Warning! Disable on production
const Debug = true;
const Name = \"{$data['application_name']}\";
const Timezone = \"UTF\";

// Path to folders from document's root
const Dirs = [
    'models' => 'models',
    'root' => 'litecms',
    'static' => 'static',
    'uploads' => 'uploads',

    'templates' => [
        'templates',
    ],
];

// List of the installed applications.
// Add your models into the END of the list
const Models = [
    'Litecms\User\User',
    'Litecms\User\Group',
];

// Warning! Do not share your connection data to strangers!
const Connection = [
    'host' => '{$data['connection_host']}',
    'username' => '{$data['connection_user']}',
    'password' => '{$data['connection_password']}',
    'database' => '{$data['connection_database']}',
    'table_prefix' => '{$data['connection_prefix']}',
];

const SessionTime = 5;";
        // Create settings file 
        return file_put_contents($_SERVER['DOCUMENT_ROOT']."/litecms/Settings.php", $settingsText);
    }


    /**
     * Creaet database tables 
     * 
     * @return void
     */
    public static function initializeDB()
    {
        $pdo = new Connection;
        foreach (Models as $model) {
            $object = new $model;
            $sql = "SELECT * FROM information_schema.tables WHERE table_schema  = ? AND table_name = ?";
            $check = $pdo->query($sql, [ConnCfg['database'], $pdo->prefix($object::$table)]);

            if (!empty($check)) {
                continue;
            }

            $sql = sprintf("CREATE TABLE {$pdo->prefix($object::$table)} (%s)", implode(", ", $object->formTable()));
            $pdo->query($sql);
        }
    }


    /**
     * Create new model
     * 
     * @param string $name Name of new model. Use titlecase for correct work.
     * @return bool
     */
    public static function newmodel(string $name)
    {
        $class = "<?php

namespace Litecms\Models;

use Litecms\Models\Model;

class {$name} extends Model
{
    public static \$table = \"user\";
    public static \$verbose = \"Пользователь\";
    public static \$plural = \"Пользователи\";
}
";
        return file_put_contents($_SERVER['DOCUMENT_ROOT']."/litecms/models/{$name}.php", $class);
    }


    /**
     * The whole project's entry point.
     * 
     * @return void
     */
    public static function start()
    {
        session_start();
        Route::start();
    }
}