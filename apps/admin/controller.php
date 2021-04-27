<?php

// Admin's homepage
use Litecms\Core\Models\Controller;
use Litecms\Core\Models\View;

class admin_controller extends Controller
{
	public function default () {
		echo View::render ('admin.php', ['title' => 'Администрирование']);
	}
}