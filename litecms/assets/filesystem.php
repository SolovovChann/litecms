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
        $root = $_SERVER['DOCUMENT_ROOT'];

        // Merge all input to one string
        $path = implode ('/', $path);

        // Replace all backslash to regular
        $path = str_replace ('\\', '/', $path);

        if (strncmp ($path, $root, strlen ($root)) == 0) {
            // Path already include root
            return $path;
        }

        return $root.'/'.$path;
    }

    /**
     * Creates new empty file
     * You can provide content, by passing argument content
     * 
     * @example Filesystem::new_file (['apps', 'articles.php'], '<?php echo "Hello world!"; ?>');
     * 
     * @param array $path – array of strings, contents path to file
     * @param mixed $content – data to be written to file
     * 
     * @return string|bool
     */
    static public function new_file (array $path, $content = '') {
        $path = Filesystem::path (...$path);

        if (file_exists ($path)) {
            // File already exists
            return false;
        }

        $result = file_put_contents ($path, $content);

        return (gettype ($result) == 'int')
        ? $path
        : false;
    }

    /**
     * Creates new directory
     * 
     * @example Filesystem::new_dir (['apps', 'articles']);
     * 
     * @param array $path – array of strings, contents path to directory
     * @param bool $force – allows the creation of nested directories specified in the path
     * 
     * @return bool true if directory created, false if something gone wrong
     */
    static public function new_dir (array $path, bool $force = false) {
        $path = Filesystem::path (...$path);

        if (file_exists ($path)) {
            // Dir already exists
            return false;
        }

        $result = mkdir ($path, 0777, $force);

        return ($result == true)
        ? $path
        : false;
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
    static public function get_extension (...$path) {
        $file = Filesystem::path (...$path);
        
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
