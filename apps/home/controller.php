<?php

require_once 'view.php';
require_once 'model.php';

use Litecms\Core\Models\Controller;

class home_controller extends Controller 
{
	public function default () {
		$this->view = new home_view ();
		echo $this->view->render ('home.php', ['title' => 'Homepage', 'message' => 'Hello world!']);
	}

	public function test () {
		$this->view = new home_view ();
		echo $this->view->render ('home.php', ['title' => 'Homepage but test', 'message' => 'Hello world!']);
	}
}