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
					$GLOBALS['sql-debug'] = [];
				}
				
				$this->createConnection();
			}
			
			public function query($sql) {
				if (defined('DEBUGSQL') && DEBUGSQL) {		
					$queryTimer = new FPCTimer();
					$result = $this->connection->query($sql);					
					
					$GLOBALS['sql-debug'][] = [
						'sql' 			=> $sql,
						'querytime' 	=> $queryTimer->getTime(),
						'bad'			=> ($queryTimer->getTime() > 0.004),
						'cached'		=> (is_object($result)?$result->fromCache:'non-select'),
						'backtrace'		=> debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
					];
					
					$queryTimer = null;

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
					$queryTimer = new FPCTimer();
					
					$result = $this->connection->non_cached_query($sql);

					$GLOBALS['sql-debug'][] = [
						'sql' 			=> $sql,
						'querytime' 	=> $queryTimer->getTime(),
						'bad'			=> ($queryTimer->getTime() > 0.004),
						'cached'		=> (is_object($result)?$result->fromCache:'non-select'),
						'backtrace'		=> debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
					];

					$queryTimer = null;
					
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