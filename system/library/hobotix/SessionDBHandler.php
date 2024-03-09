<?php

namespace hobotix\SessionHandler;

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

	public static function unserialize($session_data) {
		$method = ini_get("session.serialize_handler");
		switch ($method) {
			case "php":
			return self::unserialize_php($session_data);
			break;
			case "php_binary":
			return self::unserialize_phpbinary($session_data);
			break;
			default:
			throw new \Exception("Unsupported session.serialize_handler: " . $method . ". Supported: php, php_binary");
		}
	}

	private static function unserialize_php($session_data) {
		$return_data = array();
		$offset = 0;
		while ($offset < strlen($session_data)) {
			if (!strstr(substr($session_data, $offset), "|")) {
				throw new \Exception("invalid data, remaining: " . substr($session_data, $offset));
			}
			$pos = strpos($session_data, "|", $offset);
			$num = $pos - $offset;
			$varname = substr($session_data, $offset, $num);
			$offset += $num + 1;
			$data = unserialize(substr($session_data, $offset));
			$return_data[$varname] = $data;
			$offset += strlen(serialize($data));
		}
		return $return_data;
	}

	private static function unserialize_phpbinary($session_data) {
		$return_data = array();
		$offset = 0;
		while ($offset < strlen($session_data)) {
			$num = ord($session_data[$offset]);
			$offset += 1;
			$varname = substr($session_data, $offset, $num);
			$offset += $num;
			$data = unserialize(substr($session_data, $offset));
			$return_data[$varname] = $data;
			$offset += strlen(serialize($data));
		}
		return $return_data;
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
