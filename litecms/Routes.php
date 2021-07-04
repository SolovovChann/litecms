<?php

use Litecms\Core\Route;
use Litecms\Controllers\{Admin, Home, User};

Route::add("", [Home::class, "default"]);
Route::add("404", [Home::class, "notfound"]);

// Admin urls
Route::add("admin", [Admin::class, "default"]);
Route::add("admin/config", [Admin::class, "configurate"]);
Route::add("admin/migrate", [Admin::class, "createDB"]);
Route::add("admin/model/new", [Admin::class, "createModel"]);
// CRUD
Route::add("admin/{table}", [Admin::class, "table"]);
Route::add("entry/{table}/new", [Admin::class, "create"]);
Route::add("entry/{table}/delete/{id}", [Admin::class, "delete"]);
Route::add("entry/{table}/edit/{id}", [Admin::class, "edit"]);