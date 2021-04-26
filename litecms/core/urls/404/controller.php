<?php

require_once 'view.php';

use Litecms\Core\Models\Controller;
use Litecms\Core\Models\View;

class page404_controller extends Controller 
{
	public function default () {
		$this->view = new View ();
		$message = $_GET['message'] ?? null;
		echo $this->view->render ('404.php', ['message' => $message]);
	}
}