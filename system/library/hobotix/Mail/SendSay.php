<?php

namespace hobotix\Mail;

class SendSay {
	private $apiKey 		= null;
	private $apiUri 		= null;
	private $emailBlackList = null;

	private $limit 	= 1000;	

	private $countries = [
		'176' => '643',
		'109' => '398'
	];

	private $fieldsToConfigs = [
		'newsletter' 			=> 'config_sendsay_mapping_newsletter',
		'newsletter_news' 		=> 'config_sendsay_mapping_newsletter_news',
		'newsletter_personal' 	=> 'config_sendsay_mapping_newsletter_personal'
	];

	public function __construct($registry){
		$this->registry 		= $registry;
		$this->config 			= $registry->get('config');
		$this->db 				= $registry->get('db');
		$this->emailBlackList 	= $registry->get('emailBlackList');

		$this->apiKey = $this->config->get('config_sendsay_api_key');
		$this->apiUri = $this->config->get('config_sendsay_api_uri') . $this->config->get('config_sendsay_fid');
	}

	private function handleResult($data){
        if (empty($data['data'])) {
            $data['data'] = [];
        }

        if ($data['http_code'] !== 200) {
        	echoLine('[SendSay::handleResult] Error happened, code is ' . $data['http_code'], 'e');
           	throw new \Exception('Request error happened, code is: ' . $data['http_code']);	
        }

        if (!empty($data['data']['errors'])){
            $error = 'Error';
        	if (!empty($data['data']['errors'][0]['id'])){
        		$error = $data['data']['errors'][0]['id'];
        	} elseif ($data['data']['errors'][0]['name']){
        		$error = $data['data']['errors'][0]['name'];
        	}

        	echoLine('[SendSay::handleResult] Error happened, code is ' . $error, 'e');
        	throw new \Exception('Request error happened: ' . $error);
        }

        return $data['data'];
    }

	public function sendRequest($method = 'GET',  $data = []){
		$data['apikey']	= $this->apiKey;

        $method = strtoupper($method);
        $curl 	= curl_init();
        
        $headers = [];
		$headers[] = 'Accept:application/json';
		$headers[] = 'Accept-Encoding: gzip, deflate';
		$headers[] = 'Accept-Language: en-US,en;q=0.5';
		$headers[] = 'Content-Type: application/json; charset=utf-8';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $this->apiUri);

        switch ($method) {
            case 'POST':
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            default:
                if (!empty($data)) {
                    curl_setopt($curl, CURLOPT_URL, $this->apiUri . '?' . http_build_query($data));
                }
        }


        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);

        $response 		= curl_exec($curl);
		$header_size 	= curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headerCode 	= curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $responseBody 	= substr($response, $header_size);
	
		curl_close($curl);
	
		$result 			= [];
        $result['data'] 	= json_decode($responseBody, true);
        $result['http_code']= $headerCode;

        return $result;
    }

    /**
     * Cписок групп     
     *
     * @return array
     */
    public function listGroup(){
        $data = [
	    	'action' => 'group.list'
		];

        $requestResult = $this->sendRequest('POST', $data);

        return $this->handleResult($requestResult);
    }

    /**
     * Прочитать группу     
     *
     * @return array
     */
    public function getGroup($id){
        $data = [
	    	'action' 	=> 'group.get',
	    	'id' 		=> $id
		];

        $requestResult = $this->sendRequest('POST', $data);

        return $this->handleResult($requestResult);
    }

    public function updateSubscriber($customer){  
    	$groups = [];
    	foreach ($this->fieldsToConfigs as $key => $value){
    		if ($customer[$key]){
    			$groups[$this->config->get($value)] = 1;
    		} else {
    			$groups[$this->config->get($value)] = 0;
    		}

    		unset($customer[$key]);
    	}

    	$base_data = [];
    	if ($customer['firstname']){
    		$base_data['firstName'] = (string)$customer['firstname'];
    	}

    	if ($customer['lastname']){
    		$base_data['lastName'] = (string)$customer['lastname'];
    	}

    	if (checkBirthdayOrDate($customer['birthday'])){
    		$base_data['birthDate'] = (string)checkBirthdayOrDate($customer['birthday'], 'Y-m-d');
    	}

    	if ($customer['city']){
    		$base_data['city'] = (string)$customer['city'];
    	}

    	if (!empty($this->countries[$customer['country_id']])){
    		$base_data['country'] = [$this->countries[$customer['country_id']]];
    	} else {
    		$base_data['country'] = [$this->config->get('config_country_id')];
    	}

    	$custom_data = [];
    	if ($customer['birthday_month']){
    		$custom_data['birthday_month'] = (string)$customer['birthday_month'];
    	}

    	if ($customer['birthday_date']){
    		$custom_data['birthday_date'] = (string)$customer['birthday_date'];
    	}

    	if ((int)$customer['order_good_count'] > 0){
    		$custom_data['order_good_count'] = (string)$customer['order_good_count'];
    	}

    	if ((int)$customer['order_bad_count'] > 0){
    		$custom_data['order_bad_count'] = (string)$customer['order_bad_count'];
    	}

    	if (checkBirthdayOrDate($customer['order_good_first_date'])){
    		$custom_data['order_good_first_date'] = (string)checkBirthdayOrDate($customer['order_good_first_date'], 'Y-m-d');
    	}

    	if (checkBirthdayOrDate($customer['order_good_last_date'])){
    		$custom_data['order_good_last_date'] 	= (string)checkBirthdayOrDate($customer['order_good_last_date'], 'Y-m-d');
    		$custom_data['has_good_orders_in_days'] = [(int)($customer['order_good_last_date'] >= date('Y-m-d', strtotime('-' . $this->config->get('config_mailwizz_noorder_days') . ' day')))];
    	}

    	if (checkBirthdayOrDate($customer['order_first_date'])){
    		$custom_data['order_first_date'] = (string)checkBirthdayOrDate($customer['order_first_date'], 'Y-m-d');
    	}

    	if (checkBirthdayOrDate($customer['order_last_date'])){
    		$custom_data['order_last_date'] 	= (string)checkBirthdayOrDate($customer['order_last_date'], 'Y-m-d');
    		$custom_data['has_orders_in_days'] 	= [(int)($customer['order_last_date'] >= date('Y-m-d', strtotime('-' . $this->config->get('config_mailwizz_noorder_days') . ' day')))];
    	}

    	if ((float)$customer['avg_cheque'] > 0){
    		$custom_data['avg_cheque'] = (string)$customer['avg_cheque'];
    	}

    	if ((float)$customer['total_cheque'] > 0){
    		$custom_data['total_cheque'] = (string)$customer['total_cheque'];
    	}

    	if (checkBirthdayOrDate($customer['last_date'])){
    		$custom_data['last_site_action'] = (string)checkBirthdayOrDate($customer['last_date'], 'Y-m-d');
    	}

    	if ((int)$customer['total_reward'] > 0){
    		$custom_data['total_reward'] 		= (string)$customer['total_reward'];
    		$custom_data['has_reward_points'] 	= [1];
    	} else {
    		$custom_data['has_reward_points'] 	= [0];
    	}

    	$data = [
    		'action' 			=> 'member.set',
    		'email' 			=> (string)$customer['email'],
    		'newbie.confirm' 	=> 0,
    		'obj' 				=> [
    			'base' 			=> $base_data,
    			'custom' 		=> $custom_data,
    			'-group' 		=> $groups
    		]
    	];

    	$requestResult = $this->sendRequest('POST', $data);

    	if ($handledResult = $this->handleResult($requestResult)){
    		if (!empty($handledResult['member']) && !empty($handledResult['member']['id'])){
    			echoLine('[SendSay::updateSubscriber] Seems ok, added or updated, member id ' . $handledResult['member']['id'], 's');
    		} else {
    			echoLine('[SendSay::updateSubscriber] Hell unknown ' . print_r($handledResult, true), 's');
    		}    		
    	}
    }

    public function deleteSubscriber($customer){
    	$data = [
    		'action' 			=> 'member.delete',
    		'email' 			=> (string)$customer['email']    		
    	];

    	$requestResult = $this->sendRequest('POST', $data);

        return $this->handleResult($requestResult);
    } 

    private function getCustomers($customerIDS){
		if (!$customerIDS){
			return [];
		}

		return $this->db->ncquery("SELECT c.*, 
			(SELECT MAX(date_added) FROM customer_ip ci WHERE (ci.customer_id = c.customer_id)) AS last_date, 
			(SELECT SUM(points) AS total FROM customer_reward cr WHERE (cr.customer_id = c.customer_id)) as total_reward			 
			FROM customer c WHERE customer_id IN (" . implode(',', $customerIDS) . ")")->rows;									
	}

	public function sync(){
		if ($this->config->get('config_sendsay_enable')){
			foreach ($this->fieldsToConfigs as $key => $listConfig){		
				try {
					$group = $this->getGroup($this->config->get($listConfig));
				} catch (\Exception $e){
					echoLine('[SendSay::sync] Group ' . $this->config->get($listConfig) . ' error', 'e');
					throw new \Exception('[SendSay::sync] Groups config fail, can not sync');
				}			
			}

			$queue = $this->db->ncquery("SELECT * FROM mailwizz_queue ORDER BY RAND() LIMIT " . (int)$this->limit);

			$customerIDS = [];
			foreach ($queue->rows as $row){
				$customerIDS[] = $row['customer_id'];
			}	

			foreach ($this->getCustomers($customerIDS) as $customer){	
				if ($this->emailBlackList->native($customer['email'])){
					echoLine('[SendSay::sync] mail ' . $customer['email'] . ' is native', 'w');
					$this->db->query("DELETE FROM mailwizz_queue WHERE customer_id = '" . (int)$customer['customer_id'] . "'");
					continue;
				}

				if ($this->emailBlackList->check($customer['email'])){
					echoLine('[SendSay:sync] mail ' . $customer['email'] . ' valid stage-1, continue', 's');

					$continue = $this->emailBlackList->whitelist_check($customer['email']);
					if ($continue){
						echoLine('[SendSay:sync] mail ' . $customer['email'] . ' whitelisted', 's');
					}

					if (!$continue){
						$continue = $this->emailBlackList->checkfull($customer['email']);
					}

					if ($continue){
						if (!$this->emailBlackList->checkCustomerSubscription($customer)){
							echoLine('[SendSay:sync] mail ' . $customer['email'] . ' is not subscribed to any, deleting customer', 's');
							$this->deleteSubscriber($customer);
							$continue = false;
						}
					}

					if ($continue){
						echoLine('[SendSay:sync] mail ' . $customer['email'] . ' valid stage-2, continue', 's');
						$this->updateSubscriber($customer);
					} else {
						echoLine('[SendSay:sync] mail ' . $customer['email'] . ' invalid stage-2, moved to blacklist', 'e');
					}
				} else {
					if ($this->emailBlackList->blacklist_check($customer['email'])){
						echoLine('[SendSay:sync] Mail ' . $customer['email'] . ' in blacklist, deleting customer', 'e');
						$this->deleteSubscriber($customer);
					} else {
						echoLine('[SendSay:sync] Mail ' . $customer['email'] . ' invalid, passing', 'w');
					}
				}

				$this->db->query("DELETE FROM mailwizz_queue WHERE customer_id = '" . (int)$customer['customer_id'] . "'");

			}

			if ($customerIDS){
				$this->db->query("DELETE FROM mailwizz_queue WHERE customer_id IN (" . implode(',', $customerIDS) . ")");
			}

		} else {
			throw new \Exception('[SendSay::sync] SendSay is disabled in admin!', 'e');
		}
	}

}