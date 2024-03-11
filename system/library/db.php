<?php

if (!class_exists('DB')){
	class DB {
		private $connection;
		private $mySQLReInitException = ['Error: MySQL server has gone away', 'Error: Deadlock found when trying to get lock'];		
		private $driver 	= '';
		private $hostname 	= '';
		private $username 	= '';
		private $password 	= '';
		private $database 	= '';

		private function validateMySQLReINIT($e){
			foreach ($this->mySQLReInitException as $mySQLReInitException){
				if (strpos($e->getMessage(), $mySQLReInitException) === 0){
					return true;
				}
			}

			return false;			
		}

		private function createConnection(){
			$file = dirname(__FILE__) . '/database/' . $this->driver . '.php';

			if (file_exists($file)) {
				require_once($file);

				$class = '\hobotix\Database\DB' . $this->driver;

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

		public function getCurrentDatabase(){
			return $this->database;			
		}

		public function query($sql, $debug = false) {
			$result = false;

			if ($debug && is_cli()){
				echoLine('[DB::query] Running Query: ' . reparseEOLSToSpace($sql), 'd');
			}

			if (defined('DEBUGSQL') && DEBUGSQL) {		
				$queryTimer = new \hobotix\FPCTimer();
				$result 	= $this->connection->query($sql);					

				$GLOBALS['sql-debug'][] = [
					'sql' 			=> $sql,
					'querytime' 	=> $queryTimer->getTime(),
					'bad'			=> ($queryTimer->getTime() > 0.004),
					'cached'		=> (is_object($result)?$result->fromCache:'non-select'),
					'backtrace'		=> debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
				];

				$queryTimer = null;
			} else {
				try	{
					$result = $this->connection->query($sql);
				} catch (Exception $e){	

					if (PHP_SAPI  === 'cli' || IS_DEBUG){
						echo('[DB ERROR] in query: ' . $sql);
						echo('[DB ERROR] ' . $e->getMessage() . PHP_EOL);
					}

					$result = false;										

					if ($this->validateMySQLReINIT($e)){
						$this->createConnection();
						$result = $this->connection->query($sql);
					}
				}
			}

			return $result;
		}

		public function ncquery($sql, $debug = false) {
			return $this->non_cached_query($sql);
		}

		public function non_cached_query($sql, $debug = false) {
			if ($debug && is_cli()){
				echoLine('[DB::ncquery] Running Query: ' . reparseEOLSToSpace($sql), 'd');
			}

			if (defined('DEBUGSQL') && DEBUGSQL) {		
				$queryTimer = new \hobotix\FPCTimer();

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
			if (is_null($value) || (string)$value === ''){
				return false;
			}

			return $this->connection->escape($value);
		}

		public function countAffected() {
			return $this->connection->countAffected();
		}

		public function getLastId() {
			return $this->connection->getLastId();
		}

		public function doUpdateQuery($table, $entity_id, $entity_name, $fields){

			$sql = "UPDATE `" . $this->escape($table) . "` SET ";
			foreach ($fields as $field) {
				if ($field['type'] == 'int') {
					$implode[] = " `" . $field['field'] . "` = '" . (int)$field['value'] . "'";
				}

				if ($field['type'] == 'float') {
					$implode[] = " `" . $field['field'] . "` = '" . (float)$field['value'] . "'";
				}

				if ($field['type'] == 'varchar') {
					$implode[] = " `" . $field['field'] . "` = '" . $this->escape($field['value']) . "'";
				}

				if ($field['type'] == 'date') {
					$implode[] = " `" . $field['field'] . "` = '" . date('Y-m-d', strtotime($field['value'])) . "'";
				}

				if ($field['type'] == 'datetime') {
					$implode[] = " `" . $field['field'] . "` = '" . date('Y-m-d H:i:s', strtotime($field['value'])) . "'";
				}
			}

			$implode[] = " `date_modified` = NOW() ";
			$sql .= implode(',', $implode);
			$sql .= " WHERE `" . $this->escape($entity_name) . "` = '" . (int)$entity_id . "'";

			$this->query($sql);

			return [
				'sql' 		=> $sql,
				'affected' 	=> $this->countAffected()
			];

		}

		public function prepareUpdateQuery($table, $fields){
			$error 		= [];
			$results 	= [];

			foreach ($fields as $field => $value) {
				$type = false;

				$check = $this->query("SHOW FULL COLUMNS FROM `" . $this->escape($table) . "` LIKE '" . $this->escape($field) . "'");

				if (!$check->num_rows) {
					$error[] = 'There is no ' . $field . ' in ' . $table . ' table';
				}

				if (mb_stripos($check->row['Type'], 'int') !== false) {
					if ($field == $table . '_id') {
						$error[] = 'You can not change ' . $field . ' in ' . $table . ' table';
					} else {

						if (!is_numeric($value)) {
							$error[] = 'invalid data type for ' . $field .', must be integer';
						} else {
							$type = 'int';
						}

					}
				}

				if (mb_stripos($check->row['Type'], 'decimal') !== false) {
					if (!is_numeric($value)) {
						$error[] = 'invalid data type for ' . $field .', must be float';
					} else {
						$type = 'float';
					}
				}

				if (mb_stripos($check->row['Type'], 'varchar') !== false) {
					$type = 'varchar';
				}

				if (mb_stripos($check->row['Type'], 'text') !== false) {
					$type = 'varchar';
				}


				if (mb_stripos($check->row['Type'], 'datetime') !== false) {
					if (date('Y-m-d H:i:s', strtotime($value)) != $value) {
						$error[] = 'invalid data type for for ' . $field .', must be datetime Y-m-d H:i:s';
					} else {
						$type = 'datetime';
					}					
				}

				if (mb_stripos($check->row['Type'], 'date') !== false && mb_stripos($check->row['Type'], 'datetime') === false) {
					if (date('Y-m-d', strtotime($value)) != $value) {
						$error[] = 'invalid data type for for ' . $field .', must be date Y-m-d';
					} else {
						$type = 'date';
					}					
				}

				if ($type){
					$result[] = [ 
						'field'  => $check->row['Field'],
						'value' => $value,
						'type'  => $type
					];
				}
			}

			return [
				'error' 	=> $error,
				'result' 	=> $result
			];

		}
	}		
}	