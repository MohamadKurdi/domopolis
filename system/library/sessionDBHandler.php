<?php
    namespace Hobotix\SessionHandler;
    class SessionHandler implements \SessionHandlerInterface
    {

        private $savePath;
        protected $dbConnection;
        protected $dbTable;
		public $lifeTime = 518400;
		
		private function is_cli(){
			return (php_sapi_name() === 'cli');
		}
		
        public function setDbDetails($dbHost, $dbUser, $dbPassword, $dbDatabase)
        {
			
			if (stripos($dbHost, 'sock')){
				$dbSocket = $dbHost;
				$dbHost = NULL;
				} else {
				$dbSocket = false;
			}
			
            $this->dbConnection = new \mysqli($dbHost, $dbUser, $dbPassword, $dbDatabase, 3306, $dbSocket);
			
            if (mysqli_connect_error()) {
                throw new Exception('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			}
		}			
		
        public function setDbConnection($dbConnection)
        {
            $this->dbConnection = $dbConnection;
		}
		
        public function setDbTable($dbTable)
        {
            $this->dbTable = $dbTable;
		}
		
		public function setSessionLifeTime($lifeTime){
			
			$this->lifeTime = $lifeTime;
			
		}
		
        public function open($savePath, $sessionName): bool
        {
			if ($this->dbConnection){			
				return true;
				} else {
				return false;
			}
		}
		
        public function close(): bool
        {
            return $this->dbConnection->close();						
		}
		
		private function escape($value): string 
		{	
			return $this->dbConnection->real_escape_string($value);
		}
		
        public function read($sessionId): string
        {
			$sessionId = $this->escape($sessionId);
            $sql = "SELECT `data` FROM $this->dbTable WHERE id = '$sessionId' LIMIT 1";						
            if ($result = $this->dbConnection->query($sql)) {
				
				
				if ($result->num_rows){
					$data = array();
					
					while ($row = $result->fetch_assoc()) {
						$data[] = $row;
					}
					
					
					return isset($data[0]['data']) ? $data[0]['data'] : '';			
					
					} else {
					return '';
				}
				
				} else {
                return '';
			}
		}
		
        public function write($sessionId, $sessionData): bool
        {                      			
			$sessionId = $this->escape($sessionId);
			$sessionData = $this->escape($sessionData);
			$timestamp = time();
			
            $sql = "REPLACE INTO $this->dbTable (`id`, `data`, `timestamp`) VALUES('$sessionId', '$sessionData', '$timestamp')";
			
			
            return $this->dbConnection->query($sql);
		}
		
        public function destroy($sessionId): bool
        {			
			$sessionId = $this->escape($sessionId);
			
            $sql = "DELETE FROM $this->dbTable WHERE id = '$sessionId'";
            return $this->dbConnection->query($sql);
		}
		
        public function gc($maxlifetime): int
        {
			$time = time() - intval($maxlifetime);		
			
            $sql = "DELETE FROM $this->dbTable WHERE timestamp < $time";
			
			if ($this->is_cli()){
				echoLine($sql);
			}
			
            return $this->dbConnection->query($sql);
		}
	}
