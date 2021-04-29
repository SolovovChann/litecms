<?php

namespace Litecms\Core\Models;

use const Litecms\Config\DBPprefix;

class User extends Model
{
	public static $database = DBPprefix . "users";
	public static $verboseName = "Пользователь";
	public static $verbosePlural = "Пользователи";

	/**
	 * 
	 */
	public function auth () {}
	/**
	 * Logout user
	 */
	public function signout () {}
	/**
	 * Authorisation of user
	 */
	public function signin () {
		if (isset ($_SESSION['user'])) {
			return true;
		}
	}
	/**
	 * Registration of user
	 */
	public function signup () {}
}