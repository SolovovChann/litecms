<?php

namespace Litecms\Assets;

use const Litecms\Config\Project\Dirs;

class Filesystem
{
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
    static public function get_extension (...$path)
    {
        $file = Filesystem::path (...$path);
        
        $ext = explode ('.', $file, 2);
        if (count ($ext) != 2) {
            return;
        }
        
        return end ($ext);
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
    static public function new_dir (string $path, bool $force = false)
    {
        $path = Filesystem::path ($path);

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
    static public function new_file (string $path, string $content = '')
    {
        $path = Filesystem::path ($path);

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
    static public function path (...$path) 
    {
        // Join path
        $path = implode ("\\", $path);

        // Cleaning path regarding OS
        $path = mb_ereg_replace ('\\\\|/', DIRECTORY_SEPARATOR, $path, 'msr');
        // Check if path start with a separator (UNIX)
        $startWithSeparator = $path[0] === DIRECTORY_SEPARATOR;
        // Check if start with drive letter
        preg_match('/^[a-z]:/', $path, $matches);
        $startWithLetterDir = isset($matches[0]) ? $matches[0] : false;
        // Get and filter empty sub paths
        $subPaths = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'mb_strlen');

        $absolutes = [];
        foreach ($subPaths as $subPath) {
            if ('.' === $subPath) {
                continue;
            }
            // Save absolute cause that's a relative and we can't deal with that and just forget that we want go up
            if ('..' === $subPath
                && !$startWithSeparator
                && !$startWithLetterDir
                && empty(array_filter($absolutes, function ($value) { return !('..' === $value); }))
            ) {
                $absolutes[] = $subPath;
                continue;
            }
            if ('..' === $subPath) {
                array_pop($absolutes);
                continue;
            }
            $absolutes[] = $subPath;
        }

        return
            (($startWithSeparator ? DIRECTORY_SEPARATOR : $startWithLetterDir) ?
                $startWithLetterDir.DIRECTORY_SEPARATOR : ''
            ).implode(DIRECTORY_SEPARATOR, $absolutes);
    }


    /**
     * Uploads files into server
     * Return array of paths to uploaded files
     * 
     * @param array $files – array of files, uploaded via POST method
     * 
     * @return array $uploaded – array of paths to uploaded files
     */
    public static function upload (array $files, string $to = '')
    {
        $uploaded = [];

        foreach ($files as $file) {
            if ($file['error'] != 0) {
                // Oops, something gone wrong
                echo "Ошибка: ".$file['error'];
                continue;
            }

            $ext = Filesystem::get_extension ($file['name']);
            $name = sprintf ("upload[%s].%s", date ("d-m-y H-i-s"), $ext); // Files name's will be like upload[01-01-01 23-59-59].png
            $dir = Filesystem::path (Dirs['uploads'], $to);

            if (!file_exists ($dir)) {
                Filesystem::new_dir ($dir, true);
            }

            $path = Filesystem::path ($dir, $name);
            $result = move_uploaded_file ($file['tmp_name'], $path);

            $uploaded[] = $path;
        }

        return $uploaded;
    }
    

    /**
     * Return array of folders and files inside input
     * 
     * @param string $folder
     * 
     * @return array 
     */
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
