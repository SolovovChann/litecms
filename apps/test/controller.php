<?php

namespace Litecms\Apps\Test;

require_once 'view.php';

use Litecms\Core\Models\Controller;

class test_controller extends Controller 
{
	public function default () {
		$this->view = new test_view ();
		echo $this->view->render ('test.php', []);
	}
	
	public function test (...$args) {
		$this->view = new test_view ();
		echo $this->view->render ('test.php', ['args' => $args]);
	}
}