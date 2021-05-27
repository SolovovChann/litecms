<?php

namespace Litecms\Assets;

class Message
{
    public $code;
    public $message;
    public $class;

    // Default message classes
    private static $error = "message-danger";
    private static $info = "message-ingo";
    private static $success = "message-success";
    private static $warning = "message-warning";


    /**
     * Create new message
     * 
     * @param string $message
     * @param string $class
     * @param int $code – code of error
     * 
     * @return self
     */
    public function __construct (string $message, string $class, $code = 1) {
        $this->message = $message;
        $this->class = $class;
        $this->code = $code;

        $_SESSION['messages'][] = $this;
    }


    /**
     * Print all messages
     * 
     * @return void
     */
    public static function all () {
        if (!empty ($_SESSION['messages'])) {
            echo "<div class=\"message-box\">";
            foreach ($_SESSION['messages'] as $message) {
                $message->show ();
            }
            echo "</div>";
        }
    }


    /**
     * Print selected message
     * 
     * @return void
     */
    public function show () {
        echo "<div class=\"message {$this->class}\"><strong>Уведомлеие:</strong> {$this->message} <button class=\"btn-close\">&times;</div>";
        unset ($_SESSION['messages']);
    }


    /**
     * Select last message and print it
     * 
     * @return void
     */
    public static function pop () {
        if (!empty ($_SESSION['messages'])) {
            $message = array_pop ($_SESSION['messages']);
            $message->show ();
        }
    }


    /**
     * Create new message with error class
     * 
     * @param string $message
     * 
     * @return void
     */
    public static function error (string $message) {
        new Message ($message, static::$error);
    }


    /**
     * Create new message with info class
     * 
     * @param string $message
     * 
     * @return void
     */
    public static function info (string $message) {
        new Message ($message, static::$info);
    }


    /**
     * Create new message with success class
     * 
     * @param string $message
     * 
     * @return void
     */
    public static function success (string $message) {
        new Message ($message, static::$success);
    }


    /**
     * Create new message with warning class
     * 
     * @param string $message
     * 
     * @return void
     */
    public static function warning (string $message) {
        new Message ($message, static::$warning);
    }
}