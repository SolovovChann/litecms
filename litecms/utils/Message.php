<?php

namespace Litecms\Utils;

/**
 * Used to display information as messages popups
 * 
 * @package Litecms\Utils
 */
class Message
{
    public $class;
    public $text;
    public $title;

    public function __construct(string $text, string $class, string $title = "!")
    {
        $this->class = $class;
        $this->text = $text;
        $this->title = $title;
    }

    
    /**
     * Output last messages from session
     * 
     * @param int $limit    Limit of output messages
     * @param string $containerClass    The block class in which all messages will be stored
     * @return void
     */
    public static function show(int $limit = 5, string $containerClass = "message-container"): void
    {
        if(empty($_SESSION['messages'])) return;

        $messages = array_reverse($_SESSION['messages']);
        if ($limit > 0) {
            $messages = array_slice($messages, 0, $limit);
        }

        echo "<div class=\"container {$containerClass}\">";
        foreach ($messages as $message) {
            echo "<div class=\"message {$message->class}\">
            <strong>{$message->title}</strong>
            {$message->text}
            <button class=\"btn btn-close\">&times;</button>
            </div>";
        }
        echo "</div>";
        self::clear();
    }


    /**
     * Store current message in session
     * 
     * @return void
     */
    public function store()
    {
        $_SESSION['messages'][] = $this;
    }


    /**
     * Clear message queue
     * @return void 
     */
    public static function clear()
    {
        unset($_SESSION['messages']);
    }
}
