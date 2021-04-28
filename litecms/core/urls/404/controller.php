<?php

require_once 'view.php';

use Litecms\Core\Models\Controller;
use Litecms\Core\Models\View;

class page404_controller extends Controller 
{
	public function default () {
		$message = $_GET['message'] ?? null;
		echo View::render ('404.php', ['message' => $message]);
	}
}