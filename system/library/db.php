<?php
	
	if (!class_exists('DB')){
		class DB {
			private $connection;
			private $mySQLReInitException = ['Error: MySQL server has gone away', 'Error: Deadlock found when trying to get lock'];		
			private $driver = '';
			private $hostname = '';
			private $username = '';
			private $password = '';
			private $database = '';
			
			private function validateMySQLReINIT($e){
				foreach ($this->mySQLReInitException as $mySQLReInitException){
					if (strpos($e->getMessage(), $mySQLReInitException) === 0){
						return true;
					}
				}
				
				return false;			
			}
			
			private function createConnection(){
				$file = DIR_DATABASE . $this->driver . '.php';
				
				if (file_exists($file)) {
					require_once($file);
					
					$class = 'DB' . $this->driver;
					
					$this->connection = new $class($this->hostname, $this->username, $this->password, $this->database);
					} else {
					exit('Error: Could not load database driver type ' . $this->driver . '!');
				}
			}
			
			public function __construct($driver, $hostname, $username, $password, $database) {
				
				$this->driver 	= $driver;
				$this->hostname = $hostname;
				$this->username = $username;			
				$this->password = $password;
				$this->database = $database;
				
				if (defined('DEBUGSQL') && DEBUGSQL) {			
					$GLOBALS['sql'] = array();
				}
				
				$this->createConnection();
			}
			
			public function query($sql) {
				if (defined('DEBUGSQL') && DEBUGSQL) {		
					$starttime = microtime(true);
					
					$result = $this->connection->query($sql);
					
					$finishtime = microtime(true) - $starttime;
					
					if (!isset($GLOBALS['controller_name'])) { 
						$GLOBALS['controller_name'] = '';
					}
					
					$GLOBALS['sql'][] = $sql. '[sep]'. $finishtime . '[sep]'.	 ($GLOBALS['controller_name']) .'[sep]' . (is_object($result)?$result->fromCache:'non-select');
					
					return $result;
					
					} else {
					
					try	{
						$result = $this->connection->query($sql);
						} catch (Exception $e){	
						
						if (PHP_SAPI  === 'cli' || IS_DEBUG){
							echo('[DB ERROR] ' . $e->getMessage() . PHP_EOL);
						}
						
						$result = false;										
						
						if ($this->validateMySQLReINIT($e)){
							$this->createConnection();
							$result = $this->connection->query($sql);
						}
					}
					
					return $result;
				}
			}
			
			public function ncquery($sql) {
				return $this->non_cached_query($sql);
			}
			
			public function non_cached_query($sql) {
				if (defined('DEBUGSQL') && DEBUGSQL) {		
					$starttime = microtime(true);
					
					$result = $this->connection->non_cached_query($sql);
					$finishtime = microtime(true) - $starttime;
					
					if (!isset($GLOBALS['controller_name'])) $GLOBALS['controller_name'] = '';
					$GLOBALS['sql'][] = $sql. '[sep]'. $finishtime . '[sep]'.	 ($GLOBALS['controller_name']) .'[sep]' .  (is_object($result)?$result->fromCache:'non-select');
					
					return $result;
					
					} else {
						
					return $this->connection->non_cached_query($sql);
				}
			}
			
			public function escape($value) {
				return $this->connection->escape($value);
			}
			
			public function countAffected() {
				return $this->connection->countAffected();
			}
			
			public function getLastId() {
				return $this->connection->getLastId();
			}
		}		
	}	