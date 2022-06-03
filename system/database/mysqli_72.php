<?php
final class DBMySQLi {
	private $link;

	public function __construct($hostname, $username, $password, $database) {
	
		if (stripos($hostname, 'sock')){
			$socket = $hostname;
			$hostname = NULL;
		} else {
			$socket = false;
		}
	
		$err_level = error_reporting(0);
		$this->link = new mysqli($hostname, $username, $password, $database);
		error_reporting($err_level); 
		
		if (mysqli_connect_error()) {
			//throw new ErrorException('Error: Could not make a database link (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			throw new ErrorException('Error: Could not make a database link (' . mysqli_connect_errno() . ') ');
		}

		$this->link->set_charset("utf8");
		$this->link->query("SET SQL_MODE = ''");
	}

	public function query($sql) {
		$query = $this->link->query($sql);

		if (!$this->link->errno){
			if (isset($query->num_rows)) {
				$data = array();			

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = isset($data[0]) ? $data[0] : array();
				$result->rows = $data;
				$result->fromcache = 'uncacheable driver!';

				unset($data);

				$query->close();

				return $result;
			} else{
				return true;
			}
		} else {
			throw new ErrorException('Error: ' . $this->link->error . '<br />Error No: ' . $this->link->errno . '<br />' . $sql);
			exit();
		}
	}
	
	public function non_cached_query($sql) {
		$query = $this->link->query($sql);

		if (!$this->link->errno){
			if (isset($query->num_rows)) {
				$data = array();

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = isset($data[0]) ? $data[0] : array();
				$result->rows = $data;
				$result->fromcache = 'uncacheable driver!';

				unset($data);

				$query->close();

				return $result;
			} else{
				return true;
			}
		} else {
			throw new ErrorException('Error: ' . $this->link->error . '<br />Error No: ' . $this->link->errno . '<br />' . $sql);
			exit();
		}
	}

	public function escape($value) {	
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
?>