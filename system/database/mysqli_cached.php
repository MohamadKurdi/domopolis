<?php

	final class DBMySQLi_Cached {
		private $link 				= null;
		private $cache 				= null;
		private $cachedQuery 		= false;
		private $slaveDB 			= false;
		private $defaultPort 		= 3306;
		private $uncacheableTables 	= [];
		private $currentQueries 	= [];			
		
		public function __construct($hostname, $username, $password, $database) {
			$port = $this->defaultPort;
			
			if ($hostname && stripos($hostname, 'sock')){
				$socket = $hostname;
				$hostname = NULL;
				} else {
				$socket = NULL;
			}
			
			if ($hostname && stripos($hostname, ':')){
				$exploded = explode(':', $hostname);
				$hostname = $exploded[0];
				$port = $exploded[1];
				$socket = NULL;
			}
			
			$this->cache = new Cache(DB_CACHED_EXPIRE);
			
			$err_level = error_reporting(0);
			$this->link = new mysqli($hostname, $username, $password, $database, $port, $socket);
			
			if (function_exists('loadJsonConfig')){
				$jsonConfig = loadJsonConfig('dbcache');
				$this->uncacheableTables = $jsonConfig['uncacheableTables'];
				$this->explicitCacheableTables = $jsonConfig['explicitCacheableTables'];
			}

			error_reporting($err_level); 
			
			if (mysqli_connect_error()) {
				throw new ErrorException('Error: Could not make a database link (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			}
			
			$this->link->set_charset("utf8");
			$this->link->query("SET SQL_MODE = ''");
		}
		
		private function validateIfQueryIsCacheable($sql){			
			foreach ($this->uncacheableTables as $table){
				if (strpos($sql, $table) !== false){
					return false;
				}
			}
			
			return true;
		}

		private function validateIfQueryIsCacheableExpilcit($sql){			
			foreach ($this->explicitCacheableTables as $table){
				if (strpos($sql, 'FROM ' . $table) !== false){
					return true;
				}
			}
			
			return false;
		}
		
		public function returnQueryData($sql, $cached){
			$query = $this->link->query($sql);
			
			if (!$this->link->errno){
				if (isset($query->num_rows)) {
					$data = [];
					
					while ($row = $query->fetch_assoc()) {
						$data[] = $row;
					}
					
					$result 			= new stdClass();
					$result->num_rows 	= $query->num_rows;
					$result->row 		= isset($data[0]) ? $data[0] : array();
					$result->rows 		= $data;					
					$result->sql 		= $sql;	
					
					if ($cached){											
						$this->cache->set('sql.' . md5($sql), $result);
						$result->fromCache = 'cacheable';
					} else {
						$result->fromCache = 'non-cached';
					}
					
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

		private function getCachedQueryAndReturn($sql, $fromCache, $explicit = false){

			if ($result = $this->cache->get('sql.' . md5($sql), $explicit)){				
				if ($result->sql == $sql) {
					$this->cachedQuery = $result;
					$result->fromCache = $fromCache;
					return $result;					
				}				
			}

			return false;
		}
		
		public function query($sql) {
			if (stripos($sql, 'select ') === 0){	
				$md5query = md5($sql);			
				
				if (!$this->validateIfQueryIsCacheable($sql)){
					return $this->non_cached_query($sql);
				}

				if (in_array($md5query, $this->currentQueries)){
					if ($result = $this->getCachedQueryAndReturn($sql, 'cached-repeat', true)){
						return $result;
					}
				} else {
					$this->currentQueries[] = $md5query;
				}				

				if ($this->validateIfQueryIsCacheableExpilcit($sql)){
					if ($result = $this->getCachedQueryAndReturn($sql, 'cached-explicit', true)){
						return $result;
					}
				}

				if ($result = $this->getCachedQueryAndReturn($sql, 'cached')){
					return $result;
				}
			}

			return $this->returnQueryData($sql, true);			
		}
		
		public function non_cached_query($sql) {
			return $this->returnQueryData($sql, false);
		}
		
		public function escape($value) {
			return $this->link->real_escape_string($value);
		}
		
		public function countAffected() {			
			if (isset($this->cachedQuery) && $this->cachedQuery) {
				return $this->cachedQuery->num_rows;
			}
			
			return $this->link->affected_rows;
		}
		
		public function getLastId() {
			return $this->link->insert_id;
		}
		
		public function __destruct() {
			$this->link->close();
		}
	}
