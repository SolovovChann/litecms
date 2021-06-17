<?php

namespace Litecms\User;

use Litecms\Models\Model;
use const Litecms\Config\SessionTime;

/**
 * Class User provides tools for work with users.
 */
class User extends Model
{
    public static $table = "user";
    public static $verbose = "Пользователь";
    public static $plural = "Пользователи";

    public $username;
    public $fullname;
    public $bio;
    public $creation_date = 'CURRENT_TIMESTAMP';
    protected $email;
    protected $password;
    public $is_active;
    public $is_superuser;
    public $last_login;

    
    public function __toString()
    {
        return $this->username;
    }


    /**
     * Authenticate user
     * 
     * @param string $email
     * @param string $password
     * @return self
     */
    public static function auth(string $email, string $password)
    {
        $user = self::filter('email = ?', [$email])[0];
        if (empty($user)) {
            message("error", "Пользователя $email не найдено");
            return;
        }

        if (password_verify($password, $user->password) === false) {
            message("error", "Пароли не совпадают");
            return;
        }
    
        $time = time();
        $extra = $time + SessionTime * 60;

        $_SESSION['user'] = [
            'id' => $user->id,
            'token' => hash("sha256", $user->email.$time),
            'expiry' => $extra
        ];

        $user->last_login = $time;
        $user->is_active = true;
        $user->save();

        return $user;
    }


    /**
     * Get list of online users
     * 
     * @param int $limit
     * @return array
     */
    public static function getOnline(int $limit = 10)
    {
        $users = self::filter("is_active = ?", [true]);    
        return $users;
    }


    /**
     * Return authenticated user
     * 
     * @return self
     */
    public static function me()
    {
        $id = $_SESSION['user']['id'] or redirect('404');
        $user = self::get($id) or redirect('404');

        return $user;
    }


    // Object's methods //


    /**
     * Signout user
     * 
     * @return void
     */
    public static function signout()
    {
        $user = self::me() or redirect('404');
        $user->is_active = false;
        $user->last_login = date("Y-m-d H:i:s");
        $user->save();

        unset($_SESSION['user']);
    }


    /**
     * Register new user
     * 
     * @return self
     */
    public function signup(string $username, string $fullname, string $email, string $password)
    {
        $this->username = $username;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        $duplicate = self::filter("`username` = ? or `email` = ?", [$username, $email], 1);
        if (empty($duplicate) === false) {
            message("error", "Пользователь с таким логином или паролем уже существует");
            redirect("404");
        }

        $this->save();
        self::auth($this->email, $this->password);
    }
}