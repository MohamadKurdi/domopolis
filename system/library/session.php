<?php

use hobotix\SessionHandler;

class Session {	
	public $data 	= [];		

	private function is_cli(){
		return (php_sapi_name() === 'cli');
	}

	public function __construct($registry = false) {
		if (!$this->is_cli() && (!defined('API_SESSION')) && (!defined('CRAWLER_SESSION_DETECTED') || !CRAWLER_SESSION_DETECTED)){
			if (defined('DB_SESSION_HOSTNAME') && class_exists('hobotix\SessionHandler\SessionHandler')){
				$handler = new hobotix\SessionHandler\SessionHandler();
				$handler->setDbDetails(DB_SESSION_HOSTNAME, DB_SESSION_USERNAME, DB_SESSION_PASSWORD, DB_SESSION_DATABASE);
				$handler->setDbTable(DB_SESSION_TABLE);
				session_set_save_handler($handler, true);
			}

			if (!session_id()) {
				ini_set('session.use_only_cookies', 'On');
				ini_set('session.use_trans_sid', 'Off');
				ini_set('session.cookie_httponly', 'On');

				session_set_cookie_params(2592000, '/');
				session_start();
			}
		}

		$this->data = &$_SESSION;
	}

	function getId() {
		return session_id();
	}
}						