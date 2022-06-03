<?
class ControllerApiExtCall extends Controller { 	
	
	public function originateCallAjax(){
		
		if (!empty( $this->request->post['phone']) )
		{
			
				if ($this->user->getIPBX()){
					$ext = $this->user->getIPBX();
				} else {
					die ('no extension set to user!');
				}
				
				$num = $this->request->post['phone'];
				$num = preg_replace( "/\D/", "", $num );
				$ext = preg_replace( "/\D/", "", $ext );
 
				if ( ! empty( $num ) )
				{
						echo "Dialing $num\r\n";
 
						$timeout = 10;
 
						$socket = fsockopen($this->config->get('config_asterisk_ami_host'),"5038", $errno, $errstr, $timeout);
						fputs($socket, "Action: Login\r\n");
						fputs($socket, "UserName: ".$this->config->get('config_asterisk_ami_user')."\r\n");
						fputs($socket, "Secret: ".$this->config->get('config_asterisk_ami_pass')."\r\n\r\n");
 
						$wrets=fgets($socket,128);
						echo $wrets;
 
						fputs($socket, "Action: Originate\r\n" );
						fputs($socket, "Channel: Local/$ext@from-internal\r\n" );
						fputs($socket, "Exten: $num\r\n" );
						fputs($socket, "Context: from-internal\r\n" );
						fputs($socket, "Priority: 1\r\n" );
						fputs($socket, "Async: yes\r\n" );
						fputs($socket, "WaitTime: 15\r\n" );
						fputs($socket, "Callerid: $num\r\n\r\n" );
 
						$wrets=fgets($socket,128);
						echo $wrets;
						fclose($socket);
				}
					else
				{
					echo "Unable to determine number from (" . $this->request->post['phone'] . ")\r\n";
				}
		}
	}
	
	public function getAStatusAjax(){
		$timeout = 10;
 
		$socket = fsockopen($this->config->get('config_asterisk_ami_host'),"5038", $errno, $errstr, $timeout);
		if (!$socket){
			die ('НЕ УДАЛОСЬ СОЕДИНИТЬСЯ С АТС!');			
		}
		fputs($socket, "Action: Login\r\n");
		fputs($socket, "UserName: ".$this->config->get('config_asterisk_ami_user')."\r\n");
		fputs($socket, "Secret: ".$this->config->get('config_asterisk_ami_pass')."\r\n\r\n");
	
		$status = '';
 
		$status .= fgets($socket);
		$status .= ' : <b>' . fgets($socket).'</b>';		
    
		echo $status;
		fclose($socket);
		die();
	}
	
	public function getQueueMemberPenaltyAjax(){
		$queue_id = (int)$this->request->get['queue_id'];
		$member_id = (int)$this->request->get['member_id'];
		
		$timeout = 10;
 
		$socket = fsockopen($this->config->get('config_asterisk_ami_host'),"5038", $errno, $errstr, $timeout);
		if (!$socket){
			die ('НЕ УДАЛОСЬ СОЕДИНИТЬСЯ С АТС!');			
		}
		fputs($socket, "Action: Login\r\n");
		fputs($socket, "UserName: ".$this->config->get('config_asterisk_ami_user')."\r\n");
		fputs($socket, "Secret: ".$this->config->get('config_asterisk_ami_pass')."\r\n\r\n");
					
		$queues = "Action: QueueStatus\r\n"; 
		$queues .= "Queue: $queue_id\r\n";
		$queues .= "Member: Local/$member_id@from-queue/n\r\n";
        $queues .= "\r\n";
        fwrite($socket, $queues); 
		
		$line = trim(fgets($socket));
        while ($line != "Event: QueueStatusComplete") {
			$line = trim(fgets($socket));			
			if (mb_substr($line, 0, 8) == 'Penalty:'){
				$penalty = (int)trim(mb_substr($line, 9));
				fclose($socket);
				break;
			}			
		}
		
		if (isset($penalty)) {
		
		if ($penalty == 0){
			echo "<span style='color:white; padding:3px; background:#cf4a61; font-weight:700; display:inline-block; width:150px; text-align:center'>дежурный</span> приоритет: ".$penalty;			
		} elseif ($penalty == 5) {
			echo "<span style='color:white; padding:3px; background:#e4c25a; font-weight:700; display:inline-block; width:150px; text-align:center'>не дежурный</span> приоритет: ".$penalty;	
		} elseif ($penalty == 10) {
			echo "<span style='color:white; padding:3px; background:#ffaa56; font-weight:700; display:inline-block; width:150px; text-align:center'>не дежурный</span> приоритет: ".$penalty;	
		} else {
			echo "<span style='color:white; padding:3px; background:#4ea24e; font-weight:700; display:inline-block; width:150px; text-align:center'>не дежурный</span> приоритет: ".$penalty;
		}
		
		} else {
			
			echo "<span style='color:white; padding:3px; background:#cf4a61; font-weight:700; display:inline-block; width:150px; text-align:center'>не в очереди</span>";	
			
		}
	
		die();		
	}
	
	
	public function setMainManagerAjax(){
		$manager_id = (int)$this->request->get['manager_id'];
		$queue_id = (int)$this->request->get['queue_id'];
		
		$this->load->model('user/user');		
		$all_managers = $this->model_user_user->getUsersByGroups(array($this->model_user_user->getUserGroupID($manager_id)), true);
		
		$managers = array();	

		foreach ($all_managers as $manager){
			
			if ($manager['user_id'] == $manager_id) {
				$managers[$manager['internal_pbx_num']] = 0;
			}
			
			if ($manager['user_id'] != $manager_id) {
				$managers[$manager['internal_pbx_num']] = 10;				
			}
		}
		
		$this->setPenalties($queue_id, $managers);
		die();
	}
	
	public function setPenaltyAjax(){
		$manager = (int)$this->request->get['manager_id'];
		$penalty = (int)$this->request->get['penalty'];
		$queue_id = (int)$this->request->get['queue_id'];
		
		$timeout = 10;
 
		$socket = fsockopen($this->config->get('config_asterisk_ami_host'),"5038", $errno, $errstr, $timeout);
		if (!$socket){
			die ('НЕ УДАЛОСЬ СОЕДИНИТЬСЯ С АТС!');			
		}
		fputs($socket, "Action: Login\r\n");
		fputs($socket, "UserName: ".$this->config->get('config_asterisk_ami_user')."\r\n");
		fputs($socket, "Secret: ".$this->config->get('config_asterisk_ami_pass')."\r\n\r\n");
		$queues = "Action: QueuePenalty\r\n"; 
		$queues .= "Queue: $queue_id\r\n";
		$queues .= "Interface: Local/$manager@from-queue/n\r\n";
		$queues .= "Penalty: $penalty\r\n";
		$queues .= "\r\n";
		fwrite($socket, $queues);
		sleep (1);
		
		fclose($socket);
		
		echo 'ok';
	}
	
	public function setPenalties($queue_id, $managers = array()){
		//кто первый - у того меньше пенальти
		
		$timeout = 10;
 
		$socket = fsockopen($this->config->get('config_asterisk_ami_host'),"5038", $errno, $errstr, $timeout);
		if (!$socket){
			die ('НЕ УДАЛОСЬ СОЕДИНИТЬСЯ С АТС!');			
		}
		fputs($socket, "Action: Login\r\n");
		fputs($socket, "UserName: ".$this->config->get('config_asterisk_ami_user')."\r\n");
		fputs($socket, "Secret: ".$this->config->get('config_asterisk_ami_pass')."\r\n\r\n");
				
		foreach ($managers as $manager => $penalty){
			
			$queues = "Action: QueuePenalty\r\n"; 
			$queues .= "Queue: $queue_id\r\n";
			$queues .= "Interface: Local/$manager@from-queue/n\r\n";
			$queues .= "Penalty: $penalty\r\n";
			$queues .= "\r\n";
			fwrite($socket, $queues); 
			sleep(1);	
		}
		
		fclose($socket);
	}

	public function getPeerAjax(){
		$timeout = 10;
 
		$peer = (int)$this->request->get['peer'];
 
		$socket = fsockopen($this->config->get('config_asterisk_ami_host'),"5038", $errno, $errstr, $timeout);
		fputs($socket, "Action: Login\r\n");
		fputs($socket, "UserName: ".$this->config->get('config_asterisk_ami_user')."\r\n");
		fputs($socket, "Secret: ".$this->config->get('config_asterisk_ami_pass')."\r\n\r\n");
 
		$status=fgets($socket,128);
	
		//201
		$checkpeer = "Action: Command\r\n";
        $checkpeer .= "Command: sip show peer $peer\r\n";
        $checkpeer .= "\r\n";
        fwrite($socket, $checkpeer); 
		sleep (1);
		 $line = trim(fgets($socket));
         $found_entry = false;
            while ($line != "--END COMMAND--") {
                if (substr($line,0,6) == "Status") {
                    $status = trim(substr(strstr($line, ":"),1));
                    $found_entry = true;
                    if (substr($status,0,2) == "OK") {
                        $peer_ok = "<span style='color:white; display:inline-block;  width:100px; padding:3px; background:#4ea24e; font-weight:700'>Онлайн</span>";
                    } else {
                        $peer_ok = "<span style='color:white; display:inline-block;  width:100px; padding:3px; background:#cf4a61; font-weight:700'>Оффлайн</span>";
                    }
                }
                $line = trim(fgets($socket));
            } 
			echo $peer . ' : '.$peer_ok;
			fclose($socket);
			die;
	}
	
	public function checkPeer($peer){
		$timeout = 10;
		
		$peer = (int)$peer;

		$socket = fsockopen($this->config->get('config_asterisk_ami_host'),"5038", $errno, $errstr, $timeout);
		fputs($socket, "Action: Login\r\n");
		fputs($socket, "UserName: ".$this->config->get('config_asterisk_ami_user')."\r\n");
		fputs($socket, "Secret: ".$this->config->get('config_asterisk_ami_pass')."\r\n\r\n");
 
		$status=fgets($socket,128);
	
		//201
		$checkpeer = "Action: Command\r\n";
        $checkpeer .= "Command: sip show peer $peer\r\n";
        $checkpeer .= "\r\n";
        fwrite($socket, $checkpeer); 
		
		 $line = trim(fgets($socket));
         $found_entry = false;
            while ($line != "--END COMMAND--") {
                if (substr($line,0,6) == "Status") {
                    $status = trim(substr(strstr($line, ":"),1));
                    $found_entry = true;
                    if (substr($status,0,2) == "OK") {
                        $peer_ok = true;
                    } else {
                        $peer_ok = false;
                    }
                }
                $line = trim(fgets($socket));
            } 			
			fclose($socket);
			return $peer_ok;				
	}	
	
	public function checkManagersOnline(){
		$this->load->model('user/user');		
		$this->data['managers'] = $this->model_user_user->getUsersByGroups(array(12), true);
		
			$worktime = explode('-',$this->config->get('config_asterisk_ami_worktime'));					

			$all_absent = true;
			$online = array();
			foreach ($this->data['managers'] as $manager){			
				$peer_ok = $this->checkPeer($manager['internal_pbx_num']);
			
				echo 'peer: '.$manager['internal_pbx_num'].' '.$peer_ok.PHP_EOL;
				
				if ($peer_ok){
					$online[] = $manager['internal_pbx_num'];
				}
				
				$all_absent = (count($online) == 0);
			}
			
			$this->cache->delete('sip_managers_online');
			$this->cache->set('sip_managers_online', $online);
			
			if ($all_absent && date('H') >= $worktime[0] && date('H') < $worktime[1] && date('D') != 'Sun' && date('D') != 'Sat'){
				
				$data = array(
					'type' => 'warning',
					'text' => "Все менеджера в оффлайне!", 
					'entity_type' => '', 
					'entity_id' => 0
				);
			
				$this->mAlert->insertAlertForGroup('sales', $data);
				$this->mAlert->insertAlertForGroup('admins', $data);					
								
		} else {
			echo 'not worktime, or all ok!'.PHP_EOL;			
			die();
		}						
	}
}
