<?php

use Litecms\Utils\Message;
use Litecms\Core\{Request, Route};


/**
 * Output values in pre tags
 * 
 * @param array $values List of values to be displayed
 * @return void
 */
function debug(...$values) { 
    if (empty($values)) return;
    echo "<pre>", var_dump(...$values),"</pre>";
 }

/**
 * Redirect to another page
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


function url(string $controller, string $method, array $arguments = []) {
    return Route::url($controller, $method, $arguments);
}


function getModelByTable(string $table) {
    foreach (Litecms\Config\Models as $model) {
        if ($model::$table === $table) {
            return $model;
        }
    }
}