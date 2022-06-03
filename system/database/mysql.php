<?php
final class DBMySQL {		
	private $link;

	public function __construct($hostname, $username, $password, $database) {

		$this->cache = new Cache(DB_CACHED_EXPIRE);
	
		$err_level = error_reporting(0);  
		if (!$this->link = mysql_connect($hostname, $username, $password)) {
			trigger_error('Error: Could not make a database link using ' . $username . '@' . $hostname);
		}
		error_reporting($err_level); 
		
		if (!mysql_select_db($database, $this->link)) {
			trigger_error('Error: Could not connect to database ' . $database);
		}

		mysql_query("SET NAMES 'utf8'", $this->link);
		mysql_query("SET CHARACTER SET utf8", $this->link);
		mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $this->link);
		mysql_query("SET SQL_MODE = ''", $this->link);
	}
	
	public function non_cached_query($sql){
		if ($this->link) {	
		
			$resource = mysql_query($sql, $this->link);

			if ($resource) {
				if (is_resource($resource)) {
					$i = 0;

					$data = array();

					while ($result = mysql_fetch_assoc($resource)) {
						$data[$i] = $result;

						$i++;
					}

					mysql_free_result($resource);

					$query = new stdClass();
					$query->row = isset($data[0]) ? $data[0] : array();
					$query->rows = $data;
					$query->num_rows = $i;

					unset($data);
					$query->sql = $sql;					
					$query->fromcache = 'really non cached!';			
					
					return $query;	
				} else {
					return true;
				}
			} else {
				trigger_error('Error: ' . mysql_error($this->link) . '<br />Error No: ' . mysql_errno($this->link) . '<br />' . $sql);
				exit();
			}
		}
	}

	public function query($sql) {
		if ($this->link) {
		
		    $md5query = '';
			if (stripos($sql, 'select ') === 0){
			  
				$md5query = md5($sql);
				if ($query = $this->cache->get('sql.' . $md5query)){				
					if ($query->sql == $sql) {
						$this->cachedquery = $query;
						$query->fromcache = 'cached!';
						return($query);					
					}				
				}
			}
		
		
			$resource = mysql_query($sql, $this->link);

			if ($resource) {
				if (is_resource($resource)) {
					$i = 0;

					$data = array();

					while ($result = mysql_fetch_assoc($resource)) {
						$data[$i] = $result;

						$i++;
					}

					mysql_free_result($resource);

					$query = new stdClass();
					$query->row = isset($data[0]) ? $data[0] : array();
					$query->rows = $data;
					$query->num_rows = $i;

					unset($data);
					$query->sql = $sql;					
					$query->fromcache = 'non cached!';
					$this->cache->set('sql.' . $md5query, $query);
					
					return $query;	
				} else {
					return true;
				}
			} else {
				trigger_error('Error: ' . mysql_error($this->link) . '<br />Error No: ' . mysql_errno($this->link) . '<br />' . $sql);
				exit();
			}
		}
	}

	public function escape($value) {
		if ($this->link) {
			return mysql_real_escape_string($value, $this->link);
		}
	}

	public function countAffected() {
		if ($this->link) {
			if ($this->cachedquery) {
				return $this->cachedquery->num_rows;
			}  else  {
				return mysql_affected_rows($this->link);
	    };
		}
	}

	public function getLastId() {
		if ($this->link) {
			return mysql_insert_id($this->link);
		}
	}

	public function __destruct() {
		if ($this->link) {
			mysql_close($this->link);
		}
	}
}
?>