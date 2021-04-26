<?php

namespace Litecms\Core\Models;

use Model;

class User extends Model
{
	/**
	 * 
	 */
	public function authorize () {}
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