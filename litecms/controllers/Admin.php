<?php

namespace Litecms\Controllers;

use Litecms\Core\{Application, Controller, Connection, Request, View, Route};
use Litecms\User\User;
use const Litecms\Config\Models;

class Admin extends Controller
{
    public static function default(Request $request)
    {
        
        return View::extend($request, "markups/base.php", "admin/index.php", [
            'title' => 'Панель администратора',
            'models' => Models,
        ]);
    }

    public static function create(Request $request, string $tableName)
    {
        $model = getModelByTable($tableName);
        $entry = new $model;

        if ($request->method === 'POST' and !empty($request->post)) {
            foreach ($request->post as $key => $value) {
                if ($key === "id") continue;
                $entry->$key = $value;
            }
            $entry->save();
            message("success", "Запись успешно добавлена", "");
            redirect(
                url(Admin::class, "table", [$entry::$table]), $request
            );
        }

        return View::extend($request, "markups/base.php", "admin/entry.php", [
            'title' => "{$model::$verbose}: создание новой записи",
            'entry' => $entry,
        ]);
    }


    public static function createDB(Request $request)
    {
        Application::initializeDB();
        message("success", "База данных успешно создана", "Успех!");
        return redirect("previous", $request);
    }


    public static function configurate(Request $request)
    {
        return View::extend($request, "markups/base.php", "admin/configurate.php", ['title' => 'Первоначальная настройка системы']);
    }


    public static function delete(Request $request, string $tableName, int $id)
    {
        $model = getModelByTable($tableName);
        $entry = $model::get($id);
        $entry->delete();
        message("success", "Запись успешно удалена", "");

        return redirect("previous", $request);
    }


    public static function edit(Request $request, string $tableName, int $id)
    {
        $model = getModelByTable($tableName);
        $entry = $model::get($id);

        if ($request->method === 'POST' and !empty($request->post)) {
            foreach ($request->post as $key => $value) {
                if ($key === "id") continue;
                $entry->$key = $value;
            }

            $entry->save();
            message("success", "Данные обновлены", "");
            redirect(
                url(Admin::class, "table", [$entry::$table]), $request
            );
        }

        return View::extend($request, "markups/base.php", "admin/entry.php", [
            'title' => "Изменение записи: {$model::$verbose} №{$id}",
            'name' => $tableName,
            'entry' => $entry,
        ]);
    }

    
    public static function table(Request $request, string $tableName)
    {
        $pdo = new Connection;
        $table = $pdo->query("SELECT * FROM {$pdo->prefix($tableName)} WHERE 1");
        $head = $pdo->query("SELECT * FROM INFORMATION_SCHEMA.columns WHERE TABLE_NAME = ?", [$pdo->prefix($tableName)]);

        return View::extend($request, "markups/base.php", "admin/table.php", [
            'title' => "Таблица {$tableName}",
            'table' => $table,
            'name' => $tableName,
            'head' => $head,
        ]);
    }
}