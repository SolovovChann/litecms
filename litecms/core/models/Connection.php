<?php

namespace Litecms\Core\Models;

use Litecms\Core\Models\Model;
use Litecms\Config\Connection as Config;
use \PDO;

class Connection extends Model
{
    protected $link;
    
    public function __construct () {
        $this->link = $this->connect ();
    }

    private function connect () {
        $this->link = new mysqli (Config['host'], Config['user'], Config['password'], Config['database']);
    }

    public function query ($query, $resultType = 'assoc', $args = []) {
        if (!empty ($args)) {
            $query = vsprintf($query, $args);
        }
        
        $result = $this->link->query ($query);

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
}