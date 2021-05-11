<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Connection;
use Litecms\Core\Models\{Model, ORM};
use const Litecms\Config\Project\TimeZone;

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
     * 
     */
    public static function auth (string $email, string $password) {
        // Try find user with email
        $user = User::filter ('email = ' . $email);
        
        if (!$user) {
            // If user not found
            return;
        }

        // Verify password
        if (!password_verify ($password, $user->password)) {
            // If passwords not match
            return;
        }

        $timeZone = new DateTimeZone (TimeZone);
        $currentTime = new DateTime ("now", $timeZone);
        $extraTime = new DateInterval ("PT15M"); // Extra 15 minutes

        // Put user's token and DateTime object of it's expire in session
        $_SESSION['user'] = [
            'token' => hash ("sha256", $user->email . $currentTime), // Hash user email and current time
            'expiry' => $currentTime->add ($extraTime) // Add extra munutes to current
        ];
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

        $currentTime = new DateTime ("now", TimeZone);
        
        if ($currentTime->diff ($token['expiry'])) {
            // Token lifetime is out
            User::logout ();
        } else {
            // Refresh token lifetime
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
     * Sign user out
     * 
     * @example User::logout ();
     * 
     * @return void
     */
    public static function logout ()
    {
        if (empty ($_SESSION['user'])) {
            // User is not logined
            return;
        }

        unset ($_SESSION['user']);
    }
}