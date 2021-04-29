<?php

use Litecms\Core\Models\Router;
use Litecms\Core\Models\Model;
use const Litecms\Config\Directories as Dirs;
use function Litecms\Assets\path;

class Manager extends Model
{
    public function createapp ($name, ...$args)
    {
        $name = strtolower ($name);

        // Check special chars in app name
        if (preg_match ('/[^\w]/', $name, $matches)) {
            throw new Exception ("You can't use special chars in app name! " . array_shift ($matches));
        }

        $dir = path (Dirs['apps']) . '/' . $name;
        $file = $dir . '/' . 'controller.php';

        // Create dir
        mkdir ($dir);

        // Define pattern for controller file
        $contollerPat = "<?php\n\nuse Litecms\Core\Models\Controller;\nuse Litecms\Core\Models\View;\n\nclass {$name}_controller extends Controller\n{\n\tpublic function default () {\n\t\techo \"All systems works good!\";\n\t}\n}";

        // Input data to file
        file_put_contents ($file);
    }
}
