<?php

use Litecms\Utils\Message;
use Litecms\Core\{Request, Route};


/**
 * Output values in pre tags via var_dump() functiob
 * 
 * @param array $values List of values to be displayed
 * @return void
 */
function debug(...$values) { 
    if (empty($values)) return;
    echo "<pre>", var_dump(...$values),"</pre>";
 }

/**
 * Send header for redirect to another page
 * 
 * @param Request $request Request class's object
 * @param string $to Destination page's URL.
 * Also can use 'self', '404' and 'previous' as values 
 * @return void
 */
function redirect(string $to, Request $request = null) {
    $defaultKeys = [
        'self' => 'Refresh:1',
        'previous' => "Location: {$request->referer}",
        'home' => 'Location: /',
    ];

    if ($request !== null and array_key_exists($to, $defaultKeys)) {
        header($defaultKeys[$to]);
        return;
    } elseif ($to == '404') {
        header("{$request->server['protocol']} 404 Not Found", true, 404);
        header("Location: /404");
        return;
    } else {
        header("Location: {$to}");
    }

    die("Can't redirect to {$to}");
}


/**
 * Creates and stores new message.
 * Same as creating new Message object. See Litecms\Utils\Message for more information
 * 
 * @param string $type Message's type. Use one of default values: debug, error, info, success, warning;
 * @param string $message Text, to be shown
 * @param string $title. All messages starts with title in bold tags. Also you can pass empty string
 * @return void
 */
function message(string $type, string $message, $title = "Ошибка!") { 
    $default = [
        'debug' => 'message-debug',
        'error' => 'message-danger',
        'info' => 'message-info',
        'success' => 'message-success',
        'warning' => 'message-warning',
    ];

    if (!array_key_exists($type, $default)) return;

    $message = new Message($message, $default[$type], $title);
    $message->store();
}


/**
 * Get url by it's handler class's method.
 * @param string $controller Controller's class name
 * @param string $method Controller's method name
 * @param array $arguments If method contains arguments, pass it directly in this array
 * @return string
 */
function url(string $controller, string $method, array $arguments = []) {
    return Route::url($controller, $method, $arguments);
}


/**
 * Find registred model by table 
 * 
 * @param string $table Model's table
 * @return string Returns moldel's name as a string
 */
function getModelByTable(string $table) {
    foreach (Litecms\Config\Models as $model) {
        if ($model::$table === $table) {
            return $model;
        }
    }
}