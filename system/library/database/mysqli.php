<?php

namespace  hobotix\Database;

class DBMySQLi {
	private $link 			= null;
	private $defaultPort 	= 3306;

	public function __construct($hostname, $username, $password, $database) {
		$port = $this->defaultPort;

		if (stripos($hostname, 'sock')){
			$socket 	= $hostname;
			$hostname 	= null;
		} else {
			$socket 	= null;
		}

		if ($hostname && stripos($hostname, ':')){
			$exploded 	= explode(':', $hostname);
			$hostname 	= $exploded[0];
			$port 		= $exploded[1];
			$socket 	= null;
		}
			
		$this->link 	= new \mysqli($hostname, $username, $password, $database, $port, $socket);		

		if (mysqli_connect_error()) {
			throw new \ErrorException('Error: Could not make a database link (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
		}

		$this->link->set_charset("utf8");
		$this->link->query("SET SQL_MODE = ''");
	}

	public function query($sql) {
		$query = $this->link->query($sql);

		if (!$this->link->errno){
			if (isset($query->num_rows)) {
				$result 			= new \stdClass();
				$result->num_rows 	= $query->num_rows;
				$result->sql 		= $sql;	
				$result->rows 		= [];

				while ($row = $query->fetch_assoc()) {
					$result->rows[] = $row;
				}

				$result->row 		= isset($result->rows[0]) ? $result->rows[0] : [];
				$result->fromCache 	= 'uncacheable driver';					
				$query->close();

				return $result;
			} else{
				return true;
			}
		} else {
			throw new \ErrorException('Error: ' . $this->link->error . '<br />Error No: ' . $this->link->errno . '<br />' . $sql);
			exit();
		}
	}


	public function non_cached_query($sql) {
		return $this->query($sql);
	}

	public function escape($value) {	
		if (is_null($value) || empty($value) || !$value){
			return false;
		}

		return $this->link->real_escape_string($value);
	}

	public function countAffected() {
		return $this->link->affected_rows;
	}

	public function getLastId() {
		return $this->link->insert_id;
	}

	public function __destruct() {
		$this->link->close();
	}
}	