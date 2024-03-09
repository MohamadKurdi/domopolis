<?php

namespace  hobotix;

class UserExtended {
	private $user_id                = null;
	private $username 		        = null;
	private $fullname               = null;

	private $isSuperUser            = null;
	private $adminExtendedStats	    = null;
	private $unlockOrders          	= null;
	private $doTransactions        	= null;
	private $isManager             	= null;
	private $isMainManager         	= null;
	private $doEditCsi              = null;
	private $doOwnOrders            = null;
	private $doCountWorkTime        = null;
	private $doCountWorkContent     = null;

	private $bitrix_id              = null;
	private $user_group_id 			= null;
	private $user_group_name        = null;
	private $internal_pbx_num 		= null;
	private $internal_auth_pbx_num	= null;
	private $outbound_pbx_num		= null;
	private $template_prefix		= null;
	private $alert_namespace		= null;	

	private $stores 				= [];
	private $permission 			= [];
	private $manager_groups 		= MANAGER_GROUPS;

	private $exclude_path = [
		'/admin/index.php?route=api/alerts/getAlert',
		'/admin/index.php?route=sale/order/country',
		'/admin/index.php?route=sale/customer/transaction',
		'/admin/index.php?route=sale/order/invoicehistory',
		'/admin/index.php?route=sale/order/emailhistory',
		'/admin/index.php?route=sale/order/getDeliverySMSTextAjax',
		'/admin/index.php?route=sale/order/callhistory',
		'/admin/index.php?route=sale/order/smshistory',
		'/admin/index.php?route=sale/order/ttnhistory',
		'/admin/index.php?route=module/simple/custom',
		'/admin/index.php?route=setting/setting/getFPCINFO',
		'nolog'
	];

	private $config 				= null;
	private $log 					= null;
	private $ldap_host 		= null;
	private $ldap_dn 		= null;
	private $ldap_domain 	= null;
	private $ldap_group 	= null;	

	private function setGeneralUserObject($userData){
		$this->user_id 					= $userData['user_id'];
		$this->username 				= $userData['username'];
		$this->fullname 				= $userData['firstname'].' '.$userData['lastname'];
		$this->isSuperUser 				= $userData['is_av'];
		$this->adminExtendedStats 		= $userData['extended_stats'];
		$this->unlockOrders 			= $userData['unlock_orders'];
		$this->doTransactions 			= $userData['do_transactions'];
		$this->isManager 				= in_array($userData['user_group_id'], $this->manager_groups);
		$this->isMainManager 			= $userData['is_mainmanager'];
		$this->doOwnOrders 				= $userData['own_orders'];
		$this->doCountWorkTime 			= $userData['count_worktime'];
		$this->doCountWorkContent 		= $userData['count_content'];
		$this->doEditCsi 				= $userData['edit_csi'];

		$this->bitrix_id 				= $userData['bitrix_id'];
		$this->user_group_id 			= $userData['user_group_id'];
		$this->internal_pbx_num 		= $userData['internal_pbx_num'];
		$this->internal_auth_pbx_num 	= $userData['internal_auth_pbx_num'];
		$this->outbound_pbx_num 		= $userData['outbound_pbx_num'];	
	}

	public function __construct($registry) {
		$this->db 		= $registry->get('db');
		$this->request 	= $registry->get('request');
		$this->session 	= $registry->get('session');
		$this->config 	= $registry->get('config');

		$this->ldap_dn 		= $this->config->get('config_ldap_dn');
		$this->ldap_host 	= $this->config->get('config_ldap_host');
		$this->ldap_domain 	= $this->config->get('config_ldap_domain');
		$this->ldap_group 	= $this->config->get('config_ldap_group');
		$this->log 			= new \Log('ldap_authorizations.txt');

		if (isset($this->session->data['user_id'])) {
			$user_query = $this->db->ncquery("SELECT * FROM user WHERE user_id = '" . (int)$this->session->data['user_id'] . "' AND status = '1'");

			if ($user_query->num_rows) {
				$this->setGeneralUserObject($user_query->row);

				$this->db->ncquery("UPDATE user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");

				$user_group_query 		= $this->db->ncquery("SELECT * FROM user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
				$this->user_group_name 	= isset($user_group_query->row['name'])?$user_group_query->row['name']:'';
				$this->template_prefix 	= isset($user_group_query->row['template_prefix'])?$user_group_query->row['template_prefix']:'';
				$this->alert_namespace 	= isset($user_group_query->row['alert_namespace'])?$user_group_query->row['alert_namespace']:'';

				$user_group_store_query = $this->db->ncquery("SELECT * FROM user_group_to_store WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
				if ($user_group_store_query->num_rows){
					foreach ($user_group_store_query->rows as $result) {
						$this->stores[] = $result['store_id'];
					}
				}

				if (isset($user_group_query->row['permission'])){
					$permissions = unserialize($user_group_query->row['permission']);
				} else {
					$permissions = array();
				}

				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
					}
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($username, $password) {
		if (mb_strlen($username) < 3){
			return false;		
		}

		if (mb_strlen($password) < 5){
			return false;		
		}

		$username = str_replace('@' . $this->ldap_domain, '', $username);

		if ($this->config->get('config_ldap_auth_enable')){				
			$connection = @fsockopen($this->ldap_host, 3268, $error, $error_msg, 3);
			if (is_resource($connection)){

				fclose($connection);

				$ldap = @ldap_connect($this->ldap_host, 3268);

				ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
				ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
				error_reporting(0); 
				ini_set('display_errors', 0);

				if($bind = @ldap_bind($ldap, $username . '@' . $this->ldap_domain, $password)){
					$this->log->write($username . ' bind to ' . $this->ldap_domain . ' : ok');

					$filter 		= "(sAMAccountName=" . $username . ")";
					$attribute 		= array("memberof", "mail", "givenname", "sn", "ipPhone");
					$result 		= ldap_search($ldap, $this->ldap_dn, $filter, $attribute);
					$entries 		= ldap_get_entries($ldap, $result);
					ldap_unbind($ldap);

					$firstname 		= $entries['0']['givenname']['0'];
					$lastname 		= $entries['0']['sn']['0'];
					$email 			= isset($entries['0']['mail'])?$entries['0']['mail']['0']:'';
					$ipbx 			= isset($entries['0']['ipphone'])?$entries['0']['ipphone']['0']:'';

					$exists_query = $this->db->ncquery("SELECT * FROM user WHERE username = '" . $this->db->escape($username) . "'");
					if ($exists_query->rows){

						$_user_id = (int)$exists_query->row['user_id'];						
						$user_data = [
							'username'  		=> $username,
							'firstname' 		=> $firstname,
							'lastname'  		=> $lastname,
							'email' 			=> $email,
							'internal_pbx_num' 	=> $ipbx,
							'status'			=> '1',
							'ip' 				=> $_SERVER['REMOTE_ADDR'],
							'password'  		=> $password
						];						
						$this->editUser($_user_id, $user_data);
						$next_user_id = $_user_id;

						$this->log->write($username . ' exist, updated');

					} else {
						$user_data = [
							'username'  		=> $username,
							'firstname' 		=> $firstname,
							'lastname'  		=> $lastname,
							'email' 			=> $email,
							'internal_pbx_num' => $ipbx,
							'ip' 				=> $_SERVER['REMOTE_ADDR'],
							'password'  		=> $password
						];					
						$next_user_id = $this->addUser($user_data);						
						$this->log->write($username . ' not exist, created');
					}


					if($this->config->get('adminlog_enable') && $this->config->get('adminlog_hacklog')){
						$this->addToAdminLog('ldap_login', true);																	
					}										

				} else {
					$this->db->ncquery("UPDATE user SET status = 0 WHERE username = '" . $username . "'");
					$next_user_id = 0;

					if($this->config->get('adminlog_enable') && $this->config->get('adminlog_hacklog')){
						$this->addToAdminLog('ldap_login', false);
					}

					$this->log->write($username . ' bind to ' . $this->ldap_domain . ' : fail : username/password invalid');

					return false;

				}

			} else {
				$user_check_query = $this->db->ncquery("SELECT * FROM user WHERE username = '" . $this->db->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

				if ($user_check_query->num_rows) {
					$next_user_id = $user_check_query->row['user_id'];										
				} else {
					if($this->config->get('adminlog_enable') && $this->config->get('adminlog_hacklog')){
						$this->addToAdminLog('login', false);
					}

					return false;
				}
			}

		} else {
			$user_check_query = $this->db->ncquery("SELECT * FROM user WHERE username = '" . $this->db->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

			if ($user_check_query->num_rows) {					
				$next_user_id = $user_check_query->row['user_id'];		
			}
		}

		$user_query = $this->db->ncquery("SELECT * FROM user WHERE user_id = '" . (int)$next_user_id . "'");

		if ($user_query->num_rows) {
			$this->session->data['user_id'] = $user_query->row['user_id'];			
			$this->setGeneralUserObject($user_query->row);

			$user_group_query = $this->db->ncquery("SELECT * FROM user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

			$this->user_group_name 			= $user_group_query->row['name'];
			$this->template_prefix 			= $user_group_query->row['template_prefix'];
			$this->alert_namespace 			= $user_group_query->row['alert_namespace'];

			$user_group_store_query = $this->db->ncquery("SELECT * FROM user_group_to_store WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

			if ($user_group_store_query->num_rows){
				foreach ($user_group_store_query->rows as $result) {
					$this->stores[] = $result['store_id'];
				}
			}

			$permissions = unserialize($user_group_query->row['permission']);

			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}

			if($this->config->get('adminlog_enable') && $this->config->get('adminlog_login')){
				$this->addToAdminLog('login', true);
			}

			return true;

		} else {
			if($this->config->get('adminlog_enable') && $this->config->get('adminlog_hacklog')){
				$this->addToAdminLog('login', false);
			}

			return false;
		}
	}

	public function logout() {
		unset($this->session->data['user_id']);

		if($this->config->get('adminlog_enable') && $this->config->get('adminlog_logout')){
			$this->addToAdminLog('logout', true);
		}

		$this->db->ncquery("UPDATE `user` SET `ip` = '' WHERE user_id = '" . (int)$this->user_id . "'");

		$this->user_id 	= '';
		$this->username = '';

		session_destroy();
	}

	private function excludeFromAdminLog($url){		
		foreach($this->exclude_path as $excluded_url) {
			if (strpos($url, $excluded_url) !== false) {					
				return true;
			}
		}

		return false;
	}

	public function hasPermission($key, $value) {
		$exclude_path = ['/admin/index.php?route=api/alerts/getAlert'];

		if (isset($this->permission[$key])) {

			if($this->config->get('adminlog_enable')){
				if( ( ($this->config->get('adminlog_allowed') == 1 || $this->config->get('adminlog_allowed') == 2) && (in_array($value, $this->permission[$key])) )  ||
					( ($this->config->get('adminlog_allowed') == 0 || $this->config->get('adminlog_allowed') == 2) && !(in_array($value, $this->permission[$key])) )  ){
					if(($this->config->get('adminlog_access') && $key == "access") || ($this->config->get('adminlog_modify') && $key == "modify") ){

						if (!$this->excludeFromAdminLog($this->request->server['REQUEST_URI'])) {
							$this->addToAdminLog($key, in_array($value, $this->permission[$key]));
						}
					}
				}
			}

			return in_array($value, $this->permission[$key]);
		} else {

			if (!isset($this->request->server['REMOTE_ADDR'])){
				$this->request->server['REMOTE_ADDR'] = "127.0.0.1";
			}

			if($this->config->get('adminlog_enable') && ($this->config->get('adminlog_allowed') == 0 || $this->config->get('adminlog_allowed') == 2)){
				$this->addToAdminLog($key, false);
			}

			return false;
		}
	}

	private function addToAdminLog($action, $allowed){
		$this->db->ncquery("INSERT INTO adminlog SET 
			`user_id` 	= '" . (int)$this->user_id . "', 
			`user_name` = '" . $this->username . "', 
			`action` 	= '" . $this->db->escape($action) . "', 
			`allowed` 	= '" . (int)$allowed . "', 
			`url` 		= '".$this->db->escape($this->request->server['REQUEST_URI'])."', 
			`ip` 		= '" . $this->request->server['REMOTE_ADDR'] . "', 
			`date` 		= NOW()");		
	}

	private function addUser($data){
		$this->db->ncquery("INSERT INTO `user` SET 
			username 			= '" . $this->db->escape($data['username']) . "', 
			salt 				= '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
			password 			= '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', 
			firstname 			= '" . $this->db->escape($data['firstname']) . "', 
			is_av 				= '0', 
			unlock_orders 		= '0',  
			lastname 			= '" . $this->db->escape($data['lastname']) . "',
			internal_pbx_num 	= '" . $this->db->escape($data['internal_pbx_num']) . "',
			email 				= '" . $this->db->escape($data['email']) . "', 
			user_group_id 		= '25', 
			status 				= '1', 
			date_added 			= NOW()");

		return $this->db->getLastId();
	}

	private function editUser($user_id, $data) {							
		$this->db->ncquery("UPDATE `user` SET 
			username 			= '" . $this->db->escape($data['username']) . "',
			firstname 			= '" . $this->db->escape($data['firstname']) . "', 		
			lastname 			= '" . $this->db->escape($data['lastname']) . "', 
			email 				= '" . $this->db->escape($data['email']) . "',
			status 				= '" . (int)$data['status'] . "',		
			ip 					= '" . $this->db->escape($data['ip']) . "'
			WHERE user_id 		= '" . (int)$user_id . "'");

		if ($data['password']) {
			$this->db->ncquery("UPDATE `user` SET 
				`salt` 			= '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
				`password` 		= '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' 
				WHERE `user_id` = '" . (int)$user_id . "'");
		}
	}

	private function editPassword($user_id, $password) {
		$this->db->ncquery("UPDATE `user` SET 
			`salt` 		= '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
			`password` 	= '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', 
			`code` 		= '' 
			WHERE `user_id` = '" . (int)$user_id . "'");
	}

	public function isLogged() {
		return $this->user_id;
	}

	public function getId() {
		return $this->user_id;
	}

	public function getUserName() {
		return $this->username;
	}

	public function getUserFullName() {
		return $this->fullname;
	}

	public function getIsSuperUser() {
		return $this->isSuperUser;
	}

	public function getAdminExtendedStats() {
		return $this->adminExtendedStats;
	}

	public function getIsAV() {
		return $this->getIsSuperUser();
	}

	public function canUnlockOrders() {
		return $this->unlockOrders;
	}

	public function canDoTransactions() {
		return $this->doTransactions;
	}

	public function getManagerStores() {
		return $this->stores;
	}

	public function getIsMM() {
		return $this->isMainManager;
	}

	public function getOwnOrders() {
		return $this->doOwnOrders;
	}

	public function getIsManager() {
		return $this->isManager;
	}

	public function canEditCSI() {
		return $this->doEditCsi;
	}

	public function getBitrixID() {
		return $this->bitrix_id;
	}

	public function getCountWorktime() {
		return $this->doCountWorkTime;
	}

	public function getCountContent() {
		return $this->doCountWorkContent;
	}

	public function getIPBX() {
		return $this->internal_pbx_num;
	}

	public function getIAPBX() {
		return $this->internal_auth_pbx_num;
	}

	public function getOPBX() {
		return $this->outbound_pbx_num;
	}

	public function getUserGroup() {
		return $this->user_group_id;
	}

	public function getTemplatePrefix() {
		return $this->template_prefix;
	}

	public function getAlertNameSpace() {
		return $this->alert_namespace;
	}

	public function getUserGroupName() {
		return $this->user_group_name;
	}
}