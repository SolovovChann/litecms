<?php

namespace Litecms\Controllers;

use Litecms\Core\{Application, Controller, Connection, Request, View};
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
        if ($request->method === 'POST' and !empty($request->post)) {
            $settingsText = "<?php

namespace Litecms\Config;

// Warning! Disable on production
const Debug = true;
const Name = \"{$request->post['application_name']}\";
const Timezone = \"UTF\";

// Path to folders from document's root
const Dirs = [
    'models' => 'models',
    'root' => 'litecms',
    'static' => 'static',
    'uploads' => 'uploads',

    'templates' => [
        'templates',
    ],
];

// List of the installed applications.
// Add your models into the END of the list
const Models = [
    'Litecms\User\User',
    'Litecms\User\Group',
];

// Warning! Do not share your connection data to strangers!
const Connection = [
    'host' => '{$request->post['connection_host']}',
    'username' => '{$request->post['connection_user']}',
    'password' => '{$request->post['connection_password']}',
    'database' => '{$request->post['connection_database']}',
    'table_prefix' => '{$request->post['connection_prefix']}',
];

const SessionTime = 5;";
            // Create settings file 
            file_put_contents($_SERVER['DOCUMENT_ROOT']."/litecms/Settings.php", $settingsText);
            message("success", "Файл настроек успешно создан", "");
            sleep(10);

            // Create database
            Application::initializeDB();
            message("success", "База данных успешно создана");
            sleep(10);

            // Create superuser
            $admin = new \Litecms\User\User;
            $admin->signup(
                $request->post['admin_username'],
                "{$request->post['admin_first_name']} {$request->post['admin_last_name']}",
                $request->post['admin_email'],
                $request->post['admin_password'],
            );

            message("success", "Всё готово для работы");
            redirect("home", $request);
        }
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