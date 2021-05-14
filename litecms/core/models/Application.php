<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\{
    Router,
    User,
    View,
};
use Litecms\Assets\{
    Filesystem,
    Misc,
    Debug
};
use const Litecms\Config\Project\{
    Applications,
    Name,
    TimeZone,
    Dirs,
};

class Application
{
    public $name = Name;

    /**
     * Get page url and use responsible controller
     * 
     * @return void
     */
    public function start () {
        date_default_timezone_set(TimeZone);
        session_start ();
        
        User::checkToken ();
        Router::start ();
    }

    /**
     * Create application folder with controller and models files in it
     * 
     * @param string $name – name of application. Will be used in namespace
     */
    public static function createapp (string $name, string $url, $model = null) {
        $name = ucwords (strtolower ($name)); // Name to title case
        $folder = Filesystem::new_dir ([Dirs['apps'], $name]);

        if ($folder == false) {
            // Can't create directory
            return false;
        }

        $class = $name."Controller";
        $namespace = "Litecms\\Apps\\$name";
        $template = $name.".php";
        Filesystem::new_file ([Dirs['templates'][0], $template], "<h1>App created successfuly!</h1>");

        $content = "<?php\n\nnamespace {$namespace};\n\nuse Litecms\Core\Models\{\n\tController,\n\tView\n};\n\nclass {$class} extends Controller\n{\n\tpublic function default ()\n\t{\n\t\techo View::render ('{$template}');\n\t}\n}\n";
        $file = $class.'.php';
        Filesystem::new_file ([$folder, $file], $content);

        Application::declareApp ($url, $namespace.'\\'.$class);

        if ($model != null) {
            $table = strtolower ($model);
            $class = ucwords ($table).'.php';
            $content = "<?php\n\nnamespace {$namespace};\n\nuse Litecms\Core\Models\Model;\n\nclass {$class} extends Model\n{\n\tpublic static \$table = '{$table}';\n\tpublic static \$verboseName = '{$model}';\n\tpublic static \$verboseNamePlural = '{$model}s';\n}\n";

            Filesystem::new_file ([$folder, $model], $content);
        }
    }

    public static function declareApp (string $url, string $class)
    {
        // Edit routes.php file, to start tracking new controller
        $routes = Filesystem::path ('litecms', 'config', 'routes.php');

        if (!file_exists ($routes)) {
            // Routers.php file not found
            return false;
        }
        if (Misc::in_assoc ($url, Router::$routes)) {
            // Url already exists
            return false;
        }
        if (in_array ($name, Router::$routes)) {
            // Controller already exists
            return false;
        }

        $newRoute = "Router::add (\"{$url}\", \"{$class}\");" . PHP_EOL;
        $result = file_put_contents ($routes, $newRoute, FILE_APPEND);
    }

    /**
     * Walkthrough all installed applications and create it's tables in db
     * 
     * @return void
     */
    public static function migrate () {
        foreach (Applications as $app) {
            $app::init ();
        }
    }
}