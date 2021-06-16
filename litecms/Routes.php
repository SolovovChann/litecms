<?php

use Litecms\Core\{Route, Controller};
use Litecms\Controllers\{Admin, Home, User};

Route::add("", [Home::class, "default"]);
Route::add("404", [Home::class, "notfound"]);

// Admin urls
Route::add("admin", [Admin::class, "default"]);
Route::add("admin/config", [Admin::class, "configurate"]);
Route::add("admin/createdb", [Admin::class, "createDB"]);
Route::add("admin/table/{table}", [Admin::class, "table"]);
// CRUD
Route::add("admin/entry/{table}/new", [Admin::class, "create"]);
Route::add("admin/delete/table{tableName}/id{id}", [Admin::class, "delete"] );
Route::add("admin/edit/table{tableName}/id{id}", [Admin::class, "edit"] );