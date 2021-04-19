<?php

namespace litecms\core\classes;

class Connection
{
	private $link;
	public $db_prefix; # All DB tables will start's with

	public function __construct () {
		$this->link = new mysqli(
			$_CONNECTION['host'],
			$_CONNECTION['user'],
			$_CONNECTION['password'],
			$_CONNECTION['database'],
		);
	}

	public function query ($query, $args, $resultType = 'assoc') {
		if (!$this->link)
			$this->connect();

		$query = vsprintf($query, $args);
		$result = $this->link->query($query);

		switch ($resultType) {
			case 'assoc':
			case 'associal': {
				return $result->fetch_all(MYSQLI_ASSOC);
				break;
			}

			case 'row': {
				return $result->fetch_row();
				break;
			}

			case 'arr':
			case 'array': {
				return $result->fetch_array();
				break;
			}

			default: {
				return $result;
				break;
			}
		}
	}
	public function createTable ($name, $args = []) {
		$isExists = $this->query("SELECT 1 FROM `%s`", [$name], null);
		if ($isExists->num_rows == 0)
			$this->query("CREATE TABLE %s (%s)", [$name, explode(', ', $args)]);
	}
}

?>