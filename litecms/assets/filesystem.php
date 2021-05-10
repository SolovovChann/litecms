<?php

namespace Litecms\Assets;

class Filesystem
{
    /** 
     * Get absolute path to file/folder or false if it's not exists
     * Use function realpath with Server's Document root constant
     * 
     * @example path ('home/file.php');
     * @example path ('home', 'file.php');
     * @example path ('home', 'some', 'long', 'path', 'to', 'file.php'); // you can pass few arguments
     * 
     * @param array $data – array of strings
     * 
     * @return string
     */
    static public function path (...$path) {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/' . implode ('/', $path);
        return  $path;
    }

    /**
     * Creates new empty file
     * You can provide content, by passing argument content
     * 
     * @example Filesystem::new_file ('apps/articles.php', '<?php echo "Hello world!"; ?>');
     * 
     * @param string $path – path to file
     * @param mixed $content – data to be written to file
     * 
     * @return void
     */
    static public function new_file (string $path, $content = '') {
        $path = Filesystem::path ($path);
        file_put_contents ($path, $content);
    }

    /**
     * Creates new directory
     * 
     * @example Filesystem::new_dir ('apps/articles');
     * 
     * @param string $path – path to file
     * @param bool $force – allows the creation of nested directories specified in the path
     * 
     * @return void
     */
    static public function new_dir (string $path, bool $force = false) {
        $path = Filesystem::path ($path);
        mkdir ($path, 0777, $force);
    }

    /**
     * Return extension of file
     * 
     * @example Filesystem::get_extension ('litecms/assets/Filesystem.php') // php
     * @example Filesystem::get_extension ('some/long/path/to/file/some.strange.file.name.js') // js
     * 
     * @param string $file
     * 
     * @return string
     */
    static public function get_extension ($file) {
        $ext = explode ('.', $file, 2);
        if (count ($ext) != 2) {
            return;
        }
        
        return end ($ext);
    }

    static public function walk (string $folder)
    {
        $fodler = Filesystem::path ($folder);

        if (!is_dir ($folder)) {
            // Not directory
            return "'$folder' is not a directory";
        }

        $walk = scandir ($folder, 1);
        $walk = array_diff ($walk, ['.', '..']); // Remove dots

        return $walk;
    }
}
