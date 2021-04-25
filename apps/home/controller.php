<?php

require_once 'view.php';

use Litecms\Core\Models\Controller;

class home_controller extends Controller 
{
	public function default () {
		$this->view = new home_view ();
		echo $this->view->render ('home.php', []);
	}

	public function test () {
		echo "Home test action";
	}
}