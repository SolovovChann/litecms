<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\{
    Connection,
    Model,
    ORM,
    Validator,
    Router,
};
use const Litecms\Config\Project\{
    TimeZone,
    LogoutTime
};

class User extends Model
{
    public static $table = "user";
    public static $verboseName = "Пользователь";
    public static $verboseNamePlural = "Пользователи";

    public $username;
    private $email;
    private $password; 
    public $groups;

    public static function init ()
    {
        $username = ORM::varchar ();
        $email = ORM::varchar ();
        $password = ORM::varchar ();
        $groups = ORM::varchar ();

        return ORM::migrate (self::$table, [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'groups' => $groups
        ]);
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
            return "User not found";
        }

        // Verify password
        if (!password_verify ($password, $user->password)) {
            // If passwords not match
            return "Passwords not match";
        }

        $currentTime = time ();
        $extraTime = $currentTime + LogoutTime * 60; // Add extra 15 minutes

        $_SESSION['user'] = [
            'user_id' => $user->id, // Save user's id to identificate
            'token' => hash ("sha256", $user->email . $currentTime), // Hash user email and current time
            'expiry' => $extraTime,
        ];

        return $user;
    }

    /**
     * Check for expiry of user's token
     * 
     * @return void
     */
    public static function checkToken ()
    {
        $token = $_SESSION['user'];

        if (empty ($token)) {
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
        $id = $_SESSION['user']['user_id'];
        if (empty ($id)) {
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

        unset ($_SESSION['user']);
    }


    /* Model object's methods */


    public function signup ()
    {
        if (!$this->password or !$this->username or !$this->email) {
            // Not all arguments passed
            return;
        }
        $this->password = password_hash ($this->password, PASSWORD_DEFAULT);

        $link = new Connection;
        $result = $link->query ("INSERT INTO %s (`username`, `email`, `password`, `groups`) VALUES ('%s', '%s', '%s', %d)",
            $link->prefix.self::$table,
            $this->username,
            $this->email,
            $this->password,
            1
        );

        return $result;
    }
}