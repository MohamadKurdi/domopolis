<?php
class Mail {
	protected $to 			= null;
	protected $from 		= null;
	protected $setSender	= null;
	protected $subject 		= null;
	protected $replyTo  	= null;
	protected $bcc  		= null;
	protected $cc 			= null;
	protected $emailtemplate= null;
	protected $is_marketing = null;
	protected $text 		= null;
	protected $html 		= null;
	protected $attachments 	= [];	

	public $protocol 	= 'mail';
	public $newline 	= "\n";
	public $crlf 		= "\r\n";
	public $verp 		= false;

	public function __construct($registry = false){
		if ($registry){
			$this->db 				= $registry->get('db');
			$this->config 			= $registry->get('config');			
			$this->emailBlackList 	= $registry->get('emailBlackList');
			$this->protocol 		= $this->config->get('config_mail_protocol');
			
			$this->setFrom($this->config->get('config_email'));
			$this->setSender($this->config->get('config_name'));
		}
	}

	public function setTo($to) {
		$this->to = trim($to);
		return $this;
	}

	public function setFrom($from) {
		$this->from = trim($from);
		return $this;
	}

	public function setProtocol($protocol){
		$this->protocol = trim($protocol);
		return $this;
	}

	public function setIsMarketing($is_marketing){
		$this->is_marketing = trim($is_marketing);
		$this->setFrom($this->config->get('config_mail_trigger_mail_from'));
		return $this;
	}

	public function setSender($sender) {
		$this->sender = $sender;
		return $this;
	}

	public function setSubject($subject) {
		$this->subject = $subject;
		return $this;
	}

	public function setText($text) {
		$this->text = $text;
		return $this;
	}

	public function setHtml($html) {
		$this->html = $html;
		return $this;
	}

	public function addAttachment($filename) {
		$this->attachments[] = $filename;
		return $this;
	}

	public function setReplyTo($address, $name = ''){
		$this->replyTo = $address;
		$this->replyToName = ($name != '') ? $name : $address;
		return $this;
	}	

	public function setCc($address) {
		$this->cc = $address;
		return $this;
	}

	public function setBcc($address) {
		$this->bcc = $address;
		return $this;
	}

	public function setEmailTemplate(EmailTemplate $oEmail) {
		$this->emailtemplate = $oEmail;
		return $this;
	}

	public function getHtml() {
		return $this->html;
	}

	public function getSubject(){
		return $this->subject;		
	}

	public function send($myLogLogic = false, $data = [], $marketing = false){
		if 	(!$this->emailBlackList->check($this->to)){
			echoLine('[Mail] Mail can not be checked by emailBlackList', 'e');
			return 0;
		}

		if ($this->to == $this->config->get('config_email')){
			$this->protocol = 'mail';
		}

		$transmission_id = $this->selectEngineAndSend();

		if ($myLogLogic){
			$data['transmission_id'] = $transmission_id;			
			if($this->emailtemplate){					
				$this->emailtemplate->myLog($data, $marketing);
			}
		} else {
			if($this->emailtemplate){
				$logData = get_object_vars($this);
				$logData['transmission_id'] = $transmission_id;		
				unset($logData['emailtemplate']);
				$this->emailtemplate->log($logData);
			}
		}

		return $transmission_id;			
	}

	private function selectEngineAndSend() {
		if ($this->protocol == 'sparkpost'){
			return $this->send_sparkpost();
		}

		if ($this->protocol == 'mailgun'){
			return $this->send_mailgun();
		}

		if ($this->protocol == 'mail') {
			$this->preparemail()->send_sendmail();
		}

		if ($this->protocol == 'sendsay') {
			return $this->preparemail()->send_sendsay();
		}

		if ($this->protocol == 'smtp') {
			$this->preparemail()->send_smtp();
		}

		return 0;
	}

	private function preparemail(){
		if (!$this->to) {
			trigger_error('Error: E-Mail to required!');
			exit();			
		}

		if (!$this->from) {
			trigger_error('Error: E-Mail from required!');
			exit();					
		}

		if (!$this->sender) {
			trigger_error('Error: E-Mail sender required!');
			exit();					
		}

		if (!$this->subject) {
			trigger_error('Error: E-Mail subject required!');
			exit();					
		}

		if ((!$this->text) && (!$this->html)) {
			trigger_error('Error: E-Mail message required!');
			exit();					
		}

		if (is_array($this->to)) {
			$this->to = implode(',', $this->to);
		} else {
			$this->to = $this->to;
		}						

		$boundary = '----=_NextPart_' . md5(time());

		$header = '';

		$header .= 'MIME-Version: 1.0' . $this->newline;

		if ($this->protocol != 'mail') {
			$header .= 'To: ' . $this->to . $this->newline;
			$header .= 'Subject: ' . '=?UTF-8?B?' . base64_encode($this->subject) . '?=' . $this->newline;
		}

		$header .= 'Date: ' . date('D, d M Y H:i:s O') . $this->newline;
		$header .= 'From: ' . '=?UTF-8?B?' . base64_encode($this->sender) . '?=' . ' <' . $this->from . '>' . $this->newline;
		if($this->replyTo){
			$header .= 'Reply-To: ' . '=?UTF-8?B?' . base64_encode($this->replyToName) . '?=' . ' <' . $this->replyTo . '>' . $this->newline;
		} else {
			$header .= 'Reply-To: ' . '=?UTF-8?B?' . base64_encode($this->sender) . '?=' . ' <' . $this->from . '>' . $this->newline;
		}
		if($this->cc){
			$header .= 'cc: ' . $this->cc . $this->newline;
		}
		if($this->bcc){
			$header .= 'bcc: ' . $this->bcc . $this->newline;
		}
		$header .= 'Return-Path: ' . $this->from . $this->newline;
		$header .= 'X-Mailer: PHP/' . phpversion() . $this->newline;
		$header .= 'Content-Type: multipart/related; boundary="' . $boundary . '"' . $this->newline . $this->newline;

		if (!$this->html) {
			$message  = '--' . $boundary . $this->newline;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . $this->newline;
			$message .= 'Content-Transfer-Encoding: 8bit' . $this->newline . $this->newline;
			$message .= $this->text . $this->newline;
		} else {
			$message  = '--' . $boundary . $this->newline;
			$message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . $this->newline . $this->newline;
			$message .= '--' . $boundary . '_alt' . $this->newline;
			$message .= 'Content-Type: text/plain; charset="utf-8"' . $this->newline;
			$message .= 'Content-Transfer-Encoding: 8bit' . $this->newline . $this->newline;

			if ($this->text) {
				$message .= $this->text . $this->newline;
			} else {
				$message .= 'This is a HTML email and your email client software does not support HTML email!' . $this->newline;
			}

			$message .= '--' . $boundary . '_alt' . $this->newline;
			$message .= 'Content-Type: text/html; charset="utf-8"' . $this->newline;
			$message .= 'Content-Transfer-Encoding: 8bit' . $this->newline . $this->newline;
			$message .= $this->html . $this->newline;
			$message .= '--' . $boundary . '_alt--' . $this->newline;
		}

		setlocale(LC_ALL, "ru_RU.UTF-8");
		foreach ($this->attachments as $attachment) {
			if (file_exists($attachment)) {
				$handle = fopen($attachment, 'r');

				$content = fread($handle, filesize($attachment));

				fclose($handle);

				$message .= '--' . $boundary . $this->newline;
				$message .= 'Content-Type: application/octet-stream; name="' . basename($attachment) . '"' . $this->newline;
				$message .= 'Content-Transfer-Encoding: base64' . $this->newline;
				$message .= 'Content-Disposition: attachment; filename="' . basename($attachment) . '"' . $this->newline;
				$message .= 'Content-ID: <' . basename(urlencode($attachment)) . '>' . $this->newline;
				$message .= 'X-Attachment-Id: ' . basename(urlencode($attachment)) . $this->newline . $this->newline;
				$message .= chunk_split(base64_encode($content));
			}
		}

		$message .= '--' . $boundary . '--' . $this->newline;

		$this->header 	= $header;
		$this->message 	= $message;

		return $this;
	}
	
	private function send_sendmail(){
		ini_set('sendmail_from', $this->from);

		if ($this->config->get('config_mail_parameter')) {
			mail($this->to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $this->message, $this->header, $this->config->get('config_mail_parameter'));
		} else {
			mail($this->to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=',  $this->message, $this->header);
		}
	}

	private function send_smtp(){
		$handle = fsockopen($this->config->name('config_smtp_host'), $this->config->get('config_smtp_port'), $errno, $errstr, $this->config->get('config_smtp_timeout'));

		if (!$handle) {
			trigger_error('Error: ' . $errstr . ' (' . $errno . ')');
			exit();					
		} else {
			if (substr(PHP_OS, 0, 3) != 'WIN') {
				socket_set_timeout($handle, $this->config->get('config_smtp_timeout'), 0);
			}

			while ($line = fgets($handle, 515)) {
				if (substr($line, 3, 1) == ' ') {
					break;
				}
			}

			if (substr($this->config->name('config_smtp_host'), 0, 3) == 'tls') {
				fputs($handle, 'STARTTLS' . $this->crlf);

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 220) {
					trigger_error('Error: STARTTLS not accepted from server!');
					exit();								
				}
			}

			if (!empty($this->config->get('config_smtp_username'))  && !empty($this->config->get('config_smtp_password'))) {
				fputs($handle, 'EHLO ' . getenv('SERVER_NAME') . $this->crlf);

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 250) {
					trigger_error('Error: EHLO not accepted from server!');
					exit();								
				}

				fputs($handle, 'AUTH LOGIN' . $this->crlf);

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 334) {
					trigger_error('Error: AUTH LOGIN not accepted from server!');
					exit();						
				}

				fputs($handle, base64_encode($this->config->get('config_smtp_username')) . $this->crlf);

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 334) {
					trigger_error('Error: Username not accepted from server!');
					exit();								
				}

				fputs($handle, base64_encode($this->config->get('config_smtp_password')) . $this->crlf);

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 235) {
					trigger_error('Error: Password not accepted from server!');
							//	exit();								
				}
			} else {
				fputs($handle, 'HELO ' . getenv('SERVER_NAME') . $this->crlf);

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if (substr($reply, 0, 3) != 250) {
					trigger_error('Error: HELO not accepted from server!');											
				}
			}

			if ($this->verp) {
				fputs($handle, 'MAIL FROM: <' . $this->from . '>XVERP' . $this->crlf);
			} else {
				fputs($handle, 'MAIL FROM: <' . $this->from . '>' . $this->crlf);
			}

			$reply = '';

			while ($line = fgets($handle, 515)) {
				$reply .= $line;

				if (substr($line, 3, 1) == ' ') {
					break;
				}
			}

			if (substr($reply, 0, 3) != 250) {
				trigger_error('Error: MAIL FROM not accepted from server!');
				exit();							
			}

			if (!is_array($this->to)) {
				fputs($handle, 'RCPT TO: <' . $this->to . '>' . $this->crlf);

				$reply = '';

				while ($line = fgets($handle, 515)) {
					$reply .= $line;

					if (substr($line, 3, 1) == ' ') {
						break;
					}
				}

				if ((substr($reply, 0, 3) != 250) && (substr($reply, 0, 3) != 251)) {
					trigger_error('Error: RCPT TO not accepted from server!');
					exit();							
				}
			} else {
				foreach ($this->to as $recipient) {
					fputs($handle, 'RCPT TO: <' . $recipient . '>' . $this->crlf);

					$reply = '';

					while ($line = fgets($handle, 515)) {
						$reply .= $line;

						if (substr($line, 3, 1) == ' ') {
							break;
						}
					}

					if ((substr($reply, 0, 3) != 250) && (substr($reply, 0, 3) != 251)) {
						trigger_error('Error: RCPT TO not accepted from server!');
						exit();								
					}
				}
			}

			fputs($handle, 'DATA' . $this->crlf);

			$reply = '';

			while ($line = fgets($handle, 515)) {
				$reply .= $line;

				if (substr($line, 3, 1) == ' ') {
					break;
				}
			}

			if (substr($reply, 0, 3) != 354) {
				trigger_error('Error: DATA not accepted from server!');
				exit();						
			}

			$this->message = str_replace("\r\n", "\n",  $this->header . $this->message);
			$this->message = str_replace("\r", "\n", $this->message);

			$lines = explode("\n", $this->message);

			foreach ($lines as $line) {
				$results = str_split($line, 998);

				foreach ($results as $result) {
					if (substr(PHP_OS, 0, 3) != 'WIN') {
						fputs($handle, $result . $this->crlf);
					} else {
						fputs($handle, str_replace("\n", "\r\n", $result) . $this->crlf);
					}							
				}
			}

			fputs($handle, '.' . $this->crlf);

			$reply = '';

			while ($line = fgets($handle, 515)) {
				$reply .= $line;

				if (substr($line, 3, 1) == ' ') {
					break;
				}
			}

			if (substr($reply, 0, 3) != 250) {
				trigger_error('Error: DATA not accepted from server!');
				exit();						
			}

			fputs($handle, 'QUIT' . $this->crlf);

			$reply = '';

			while ($line = fgets($handle, 515)) {
				$reply .= $line;

				if (substr($line, 3, 1) == ' ') {
					break;
				}
			}

			if (substr($reply, 0, 3) != 221) {
				trigger_error('Error: QUIT not accepted from server!');
				exit();						
			}

			fclose($handle);
		}
	}

	private function send_sendsay(){
		$attachments = [];
		foreach ($this->attachments as $attachment) {
			if (file_exists($attachment)) {
				$handle = fopen($attachment, 'r');				
				$content = fread($handle, filesize($attachment));				
				fclose($handle);

				$finfo = new finfo(FILEINFO_MIME);
				$type  = $finfo->file($attachment);

				$attachments[] = [
					'name' 		=> basename($attachment),
					'mime-type' => $type,
					'content' 	=> chunk_split(base64_encode($content)),
					'encoding' 	=> 'base64',
				];			
			}
		}

		if (!$this->text){
			$this->text = strip_tags($this->html);
		}

		$json = [
			'action' => 'issue.send',
			'letter' => [
				'message' => [
					'html' 		=> $this->html,
					'text' 		=> $this->text					
				],
				'attaches' 		=> $attachments,
				'subject' 		=> (string)$this->subject,
				'from.email' 	=> (string)$this->from,
				'from.name' 	=> (string)$this->sender
			],
			'group' 		=> 'personal',			
			'email' 		=> $this->to,
			'sendwhen' 		=> 'now',
			'apikey' 		=> (string)$this->config->get('config_sendsay_api_key')
		];


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->config->get('config_sendsay_api_uri') . $this->config->get('config_sendsay_fid'));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		$headers = [];
		$headers[] = 'Accept:application/json';
		$headers[] = 'Accept-Encoding: gzip, deflate';
		$headers[] = 'Accept-Language: en-US,en;q=0.5';
		$headers[] = 'Content-Type: application/json; charset=utf-8';

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
		$result = json_decode(curl_exec($ch), true);		

		if (is_array($result)){
			if (isset($result['track.id']) && isset($result['track.id'])){
				$transmission_id = $result['track.id'];
			} else {
				$transmission_id = 0;
			}
		} else {
			$transmission_id = 0;
		}

		return $transmission_id;
	}

	private function send_mailgun($domain = false){
		$mgClient = \Mailgun\Mailgun::create($this->config->get('config_mailgun_api_private_key'), $this->config->get('config_mailgun_api_url'));

		if (!$domain){
			if ($this->is_marketing){
				$domain = $this->config->get('config_mailgun_api_marketing_domain');
			} else {
				$domain = $this->config->get('config_mailgun_api_transaction_domain');
			}			
		}

		try{	
			setlocale(LC_ALL, "ru_RU.UTF-8");

			$attachments = [];
			foreach ($this->attachments as $attachment) {
				if (file_exists($attachment)) {
					$attachments[] = ['filePath' => trim($attachment), 'filename' => basename($attachment)];		
				}
			}

			$result = $mgClient->messages()->send($domain, [
				'from'    => $this->sender .'<' . $this->from . '>',			
				'to'      => $this->to,
				'subject' => $this->subject,
				'text'    => $this->text,
				'html'    => $this->html,
				'attachment' => $attachments
			]);

			if ($result->getMessage() == "Queued. Thank you."){		
				return $result->getId();
			}

		} catch (\Mailgun\Exception\HttpClientException $e){
			$this->preparemail()->send_sendmail();
			return 0;
		}

		return 0;
	}

	private function send_sparkpost(){		
		setlocale(LC_ALL, "ru_RU.UTF-8");

		$attachments = [];
		foreach ($this->attachments as $attachment) {
			if (file_exists($attachment)) {
				$handle = fopen($attachment, 'r');				
				$content = fread($handle, filesize($attachment));				
				fclose($handle);

				$finfo = new finfo(FILEINFO_MIME);
				$type  = $finfo->file($attachment);

				$attachments[] = array(
					'type' => $type,
					'name' => basename($attachment),
					'data' => chunk_split(base64_encode($content))			
				);			
			}
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->config->get('config_sparkpost_api_url'));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		$headers = [];
		$headers[] = 'Accept:application/json';
		$headers[] = 'Accept-Encoding: gzip, deflate';
		$headers[] = 'Accept-Language: en-US,en;q=0.5';
		$headers[] = 'Authorization: ' . $this->config->get('config_sparkpost_api_key');
		$headers[] = 'Content-Type: application/json; charset=utf-8';

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$json = json_encode([
			'metadata'=>[
				'website' => $this->sender,
				'company' => $this->sender
			],
			'substitutionData'=>[					
			],							
			
			'description' => $this->subject,
			
			'options' => [
				'trackOpens'	 => true,
				'trackClicks' 	 => true,
				'sandbox' 		 => false,
				'inlineCss' 	 => false,
				'transactional'	 => true,			
			],
			'recipients'=>[
				[
					'address'=>[
						'email' => $this->to
					]
				]
			],
			'content' => [
				'from' 		=> [
					'name' 	=> $this->sender,
					'email' => $this->from
				],
				'reply_to' 		=> $this->from,
				'html' 			=> $this->html,
				'text' 			=> $this->text,
				'subject'		=> $this->subject,
				'attachments'	=> $attachments,
			],
		]);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		$result = json_decode(curl_exec($ch), true);

		if (is_array($result)){
			if (isset($result['results']) && isset($result['results']['id'])){
				$transmission_id = $result['results']['id'];
			} else {
				$transmission_id = 0;
			}
		} else {
			$transmission_id = 0;
		}

		return $transmission_id;					
	}

}		