<?php

namespace Litecms\Core\User;

use Litecms\Assets\{Message, Filesystem};
use Litecms\Core\Models\{
    Connection,
    Model,
    ORM,
    Router,
};
use const Litecms\Config\Project\{
    TimeZone,
    LogoutTime
};

class User extends Model
{
    public static $table = "users";
    public static $verboseName = "Пользователь";
    public static $verboseNamePlural = "Пользователи";

    public $username;
    private $email;
    private $password; 
    public $online;
    public $groups;
    public $avatar;


    public static function init ()
    {
        return ORM::migrate (self::$table, [
            ORM::varchar ("username"),
            ORM::varchar ("email"),
            ORM::varchar ("password"),
            ORM::varchar ("online"),
            ORM::foreign ("groups", UserGroup::$table, ORM::setNull),
            ORM::varchar ("avatar", 255, true, "/static/img/defaultava.svg"),
        ]);
    }


    public function __toString ()
    {
        return $this->username;
    }

    /**
     * Authenticate user in session
     * 
     * @param string $email – user's email
     * @param string $password – user's password
     * 
     * @return self
     */
    public static function auth (string $email, string $password) {
        // Try find user with email
        $user = User::filter ('email = ' . $email);
        
        if (!$user) {
            // If user not found
            Message::error ("Пользователь с таким email не найден!");
            return Router::redirect ("HomeController");
        }

        // Verify password
        if (!password_verify ($password, $user->password)) {
            // If passwords not match
            Message::error ("Неверный пароль");
            return Router::redirect ("HomeController");
        }

        $currentTime = time ();
        $extraTime = $currentTime + LogoutTime * 60; // Add extra 15 minutes

        $_SESSION['user'] = [
            'user_id' => $user->id, // Save user's id to identificate
            'token' => hash ("sha256", $user->email . $currentTime), // Hash user email and current time
            'expiry' => $extraTime,
        ];

        $user->online = "1";
        $user->save ();

        return $user;
    }


    /**
     * Check for expiry of user's token
     * 
     * @return void
     */
    public static function checkToken ()
    {
        $token = !empty ($_SESSION['user'])
        ? $_SESSION['user']
        : false;

        if ($token === false) {
            // User is not authorised
            return;
        }

        $currentTime = time ();
        
        if ($currentTime > $token['expiry']) {
            // Token lifetime is out
            User::signout ();
        } else {
            // Refresh token lifetime
            $_SESSION['user']['expiry'] = time () + LogoutTime * 60;
        }
    }


    /**
     * Get list of authenticated users
     * 
     * @return array
     */
    public static function getOnline ()
    {
        $users = User::filter ("online = 1");
        if (gettype ($users) != 'array') {
            $users = [$users];
        }

        return $users;
    }


    /**
     * Check if authenticated user have access level
     * 
     * @param int $accessCode – validate if authenticated user 
     */
    public static function is_admin (int $accessLevel = 10)
    {
        $group = UserGroup::get (User::me ()->groups);

        return ($group->accessLevel !== $accessLevel); // Return bool 
    }


    /**
     * Check for user's authentication. Return true or flase
     * 
     * @example if (User::is_authenticated ()) {
     *  // Do smth...
     * }
     * 
     * @return bool
     */
    public static function is_authenticated ()
    {
        return (!empty ($_SESSION['user']))
        ? true
        : false;
    }


    /**
     * Get authenticated user or return false 
     * 
     * @return self|bool
     */
    public static function me ()
    {
        $id = $_SESSION['user']['user_id'] ?? false;
        if ($id === false) {
            // User is not authenticated
            return false;
        }

        $user = User::get ($id);

        return $user;
    }


    /**
     * Sign user out
     * 
     * @example User::logout ();
     * 
     * @return void
     */
    public static function signout ()
    {
        if (empty ($_SESSION['user'])) {
            // User is not logined
            return;
        }
        $user = User::me ();
        $user->online = date("Y-m-d H:i:s");
        $user->save ();

        unset ($_SESSION['user']);
    }


    /* Model object's methods */


    /**
     * Register new user.
     * Returns false in case of error
     * 
     * @return self|bool
     */
    public function signup (string $username, string $email, string $password)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash ($password, PASSWORD_DEFAULT);

        // Dublicate check
        $dublicate = User::filter ('username = '.$username);
        if (!empty ($dublicate)) {
            // Duplicate username
            Message::error ("User with username = $username already exists!");
            return;
        }

        $dublicate = User::filter ("email = $email");
        if (!empty ($dublicate)) {
            // Duplicate email
            Message::error ("User with email = $email already exists!");
            return;
        }

        $link = new Connection;
        $result = $link->query ("INSERT INTO %s (`username`, `email`, `password`, `groups`, `avatar`) VALUES ('%s', '%s', '%s', '%d', '%s')",
            $link->prefix.self::$table,
            $this->username,
            $this->email,
            $this->password,
            1,
            $this->avatar
        );

        return $this;
    }
}