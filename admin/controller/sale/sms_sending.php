<?php 
class ControllerSaleSmsSending extends Controller {
	private $error = array();
	 
	public function index() {
                
                $this->load->model('sale/sms_sending');
                
                $this->language->load('sale/sms_sending');
			
             //   $settings = $this->model_sale_sms_sending->getSettings();
                
                if ($this->config->get('config_sms_prkey') == '' || 
					$this->config->get('config_sms_pukey') == '' || 
					$this->config->get('config_sms_sign') == '') {
			$this->data['error_warning'] = $this->language->get('error_warning');
		} else {
			$this->data['error_warning'] = '';
		}
                
                /*start getUserBalance*/
                if ($this->data['error_warning'] == ''){
                    /*start Balance*/
                    $params_bal ['version'] ="3.0";
                    $params_bal ['action'] = "getUserBalance";
                    $params_bal ['key'] = $this->config->get('config_sms_pukey');
                    $params_bal ['currency'] = 'UAH';
                    ksort ($params_bal);
                    $sum_bal='';
                    foreach ($params_bal as $k_bal=>$v_bal)
                    $sum_bal.=$v_bal;
                    $sum_bal .= $this->config->get('config_sms_prkey');
                    $control_sum_bal =  md5($sum_bal);             
                    $url_bal = "https://atompark.com/api/sms/3.0/getUserBalance?key=".$params_bal ['key']."&sum=".$control_sum_bal;
                            $data_bal = array('currency' => 'UAH');
                            $options_bal = array(
                                'http' => array(
                                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                'method'  => 'POST',
                                'content' => http_build_query($data_bal),
                                ),
                            );
                    $context_bal  = stream_context_create($options_bal);
                    $json_bal = file_get_contents($url_bal, false, $context_bal);
                    $balance = json_decode($json_bal, true);
                }
                /*end Balance*/
                
                /*start Customer Groups*/
                $customer_groups = $this->model_sale_sms_sending->getCustomerGroups();
                
                foreach ($customer_groups as $group) {
			$this->data['customer_groups'][] = array(
				'customer_group_id' => $group['customer_group_id'],
				'name'              => $group['name'] . (($group['customer_group_id'] == $this->config->get('config_customer_group_id')) ? $this->language->get('text_default') : null)
			);
		}
                /*end Customer Groups*/
                
		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['heading_title'] = $this->language->get('heading_title');
                
		$this->data['balance'] = isset($balance)? $balance["result"]["balance_currency"].' '.$balance["result"]["currency"]:$this->language->get('error_settings');
        $this->data['alias'] = $this->config->get('config_sms_sign');
		$this->data['text_balance'] = $this->language->get('text_balance');
        $this->data['text_alias'] = $this->language->get('text_alias');
        $this->data['text_datetime'] = $this->language->get('text_datetime');
        $this->data['text_instantly'] = $this->language->get('text_instantly');
        $this->data['text_start_at'] = $this->language->get('text_start_at');
        $this->data['text_translit'] = $this->language->get('text_translit');
        $this->data['text_message'] = $this->language->get('text_message');
        $this->data['text_symbols'] = $this->language->get('text_symbols');
                

		$this->data['entry_message'] = $this->language->get('entry_message');
        $this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_to'] = $this->language->get('entry_to');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_customer'] = $this->language->get('entry_customer');
		
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_ignore'] = $this->language->get('button_ignore');
        $this->data['button_correct'] = $this->language->get('button_correct');
                
		$this->data['error_settings'] = $this->language->get('error_settings');
		
		$this->data['token'] = $this->session->data['token'];

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/sms_sending', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
                $this->data['cancel'] = $this->url->link('sale/sms_sending', 'token=' . $this->session->data['token'], 'SSL');
                $this->data['correct'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		$this->load->model('sale/customer_group');
				
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups(0);
				
		$this->template = 'sale/sms_sending.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
                		
		$this->response->setOutput($this->render());
	}
	
	public function send() {
                
		$this->language->load('sale/sms_sending');
                
                $this->load->model('sale/sms_sending');
                
                $settings = $this->model_sale_sms_sending->getSettings();
                
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'sale/sms_sending')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->request->post['message']) {
				$json['error']['message'] = $this->language->get('error_message');
			}
                        
                        $telephone_total = 0;
                                
                        $to_info = $this->model_sale_sms_sending->getCustomers($this->request->post['to']);
                        $error_format = '';
                        if(!$to_info){
                            $json['error']['customers'] = $this->language->get('error_customers');
                        }else {
                            if($this->request->post['ignore'] == '0'){
                                foreach ($to_info as $result) {
                                    if(!preg_match("/[3][8][0][0-9]{9}$/i",$result['telephone'])){
                                        $error_format .= $result['CONCAT(firstname, \' \',lastname)'].' - '.$result['telephone']."</br>";
                                    }
                                }
                                if($error_format !='' ){
                                    $json['error']['format'] = $this->language->get('error_phones').$error_format;
                                }
                            }
                        }
			
			if (!$json) {
                            
                                if($this->request->post['timesend'] == '1') {
                                    $timesend = '';
                                }else{
                                    $timesend = $this->request->post['date'];
                                }
                                
                                /*start Balance*/
                                $params_bal ['version'] ="3.0";
                                $params_bal ['action'] = "getUserBalance";
                                $params_bal ['key'] = $this->config->get('config_sms_pukey');
                                $params_bal ['currency'] = 'UAH';
                                ksort ($params_bal);
                                $sum_bal='';
                                foreach ($params_bal as $k_bal=>$v_bal)
                                $sum_bal.=$v_bal;
                                $sum_bal .= $this->config->get('config_sms_prkey');
                                $control_sum_bal =  md5($sum_bal);                        
                                $url_bal = "https://atompark.com/api/sms/3.0/getUserBalance?key=".$params_bal ['key']."&sum=".$control_sum_bal;
                                        $data_bal = array('currency' => 'UAH');
                                        $options_bal = array(
                                            'http' => array(
                                            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                            'method'  => 'POST',
                                            'content' => http_build_query($data_bal),
                                            ),
                                        );
                                        $context_bal  = stream_context_create($options_bal);
                                $json_bal = file_get_contents($url_bal, false, $context_bal);
                                $balance = json_decode($json_bal, true);

                                /*end Balance*/
                            
                                if ($this->request->post['translit_checked'] == 1){
                                    $replace=array(
                                        "'"=>"",
                                        "`"=>"",
                                        "а"=>"a","А"=>"a",
                                        "б"=>"b","Б"=>"b",
                                        "в"=>"v","В"=>"v",
                                        "г"=>"g","Г"=>"g",
                                        "д"=>"d","Д"=>"d",
                                        "е"=>"e","Е"=>"e",
                                        "ж"=>"zh","Ж"=>"zh",
                                        "з"=>"z","З"=>"z",
                                        "и"=>"i","И"=>"i",
                                        "й"=>"y","Й"=>"y",
                                        "к"=>"k","К"=>"k",
                                        "л"=>"l","Л"=>"l",
                                        "м"=>"m","М"=>"m",
                                        "н"=>"n","Н"=>"n",
                                        "о"=>"o","О"=>"o",
                                        "п"=>"p","П"=>"p",
                                        "р"=>"r","Р"=>"r",
                                        "с"=>"s","С"=>"s",
                                        "т"=>"t","Т"=>"t",
                                        "у"=>"u","У"=>"u",
                                        "ф"=>"f","Ф"=>"f",
                                        "х"=>"h","Х"=>"h",
                                        "ц"=>"c","Ц"=>"c",
                                        "ч"=>"ch","Ч"=>"ch",
                                        "ш"=>"sh","Ш"=>"sh",
                                        "щ"=>"sch","Щ"=>"sch",
                                        "ъ"=>"","Ъ"=>"",
                                        "ы"=>"y","Ы"=>"y",
                                        "ь"=>"","Ь"=>"",
                                        "э"=>"e","Э"=>"e",
                                        "ю"=>"yu","Ю"=>"yu",
                                        "я"=>"ya","Я"=>"ya",
                                        "і"=>"i","І"=>"i",
                                        "ї"=>"yi","Ї"=>"yi",
                                        "є"=>"e","Є"=>"e"
                                    );
                                    $this->request->post['message']=iconv("UTF-8","UTF-8//IGNORE",strtr($this->request->post['message'],$replace));
                                }
                                                                
                                //start delete campaing
                                /*
                                $params_campinf ['version'] ="3.0";
                                $params_campinf ['action'] = "cancelCampaign";
                                $params_campinf ['key'] = $this->config->get('config_sms_pukey');
                                $params_campinf ['id'] = '11147';
                                ksort ($params_campinf);
                                $sum_campinf='';
                                foreach ($params_campinf as $k_campinf=>$v_campinf)
                                $sum_campinf.=$v_campinf;
                                $sum_campinf .= $this->config->get('config_sms_prkey');
                                $control_sum_campinf =  md5($sum_campinf);
                                $json_campinf = file_get_contents("https://atompark.com/api/sms/3.0/cancelCampaign?key=".$params_campinf ['key']."&sum=".$control_sum_campinf."&id=".$params_campinf['id']);
                                $campinf = json_decode($json_campinf, true);
                                var_dump($campinf); die();
                                */
                                //end delete campaign
                                
                                /*start*/
                                
                                //addAddressbook
                                $params_addbook ['version'] ="3.0";
                                $params_addbook ['action'] = "addAddressbook";
                                $params_addbook ['key'] = $this->config->get('config_sms_pukey');
                                $params_addbook ['name'] = $this->request->post['to'];
                                ksort ($params_addbook);
                                $sum_addbook='';
                                foreach ($params_addbook as $k_addbook=>$v_addbook)
                                $sum_addbook.=$v_addbook;
                                $sum_addbook .= $this->config->get('config_sms_prkey');
                                $control_sum_addbook =  md5($sum_addbook);
                                $json_addbook = file_get_contents("https://atompark.com/api/sms/3.0/addAddressbook?key=".$params_addbook ['key']."&sum=".$control_sum_addbook."&name=".$this->request->post['to']);
                                $book = json_decode($json_addbook, true);
                                $book_id = $book["result"]["addressbook_id"];
                                if($book_id){
                                    //addPhoneToAddressBook
                                    foreach ($to_info as $info) {
                                        $params_addphones ['version'] ="3.0";
                                        $params_addphones ['action'] = "addPhoneToAddressBook";
                                        $params_addphones ['key'] = $this->config->get('config_sms_pukey');
                                        $params_addphones ['idAddressBook'] = $book_id;
                                        $params_addphones ['phone'] = $info['telephone'];
                                        $params_addphones ['variables'] = $info['CONCAT(firstname, \' \',lastname)'];
                                        ksort ($params_addphones);
                                        $sum_addphones='';
                                        foreach ($params_addphones as $k_addphones=>$v_addphones)
                                        $sum_addphones.=$v_addphones;
                                        $sum_addphones .= $this->config->get('config_sms_prkey');
                                        $control_sum_addphones =  md5($sum_addphones);
                                        
                                        $url_addphones = "https://atompark.com/api/sms/3.0/addPhoneToAddressBook?key=".$params_addphones ['key']."&sum=".$control_sum_addphones."&idAddressBook=".$params_addphones ['idAddressBook']."&phone=".$params_addphones ['phone'];
                                        $data_addphones = array('variables' => $params_addphones ['variables']);
                                        $options_addphones = array(
                                            'http' => array(
                                            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                            'method'  => 'POST',
                                            'content' => http_build_query($data_addphones),
                                            ),
                                        );
                                        $context_addphones  = stream_context_create($options_addphones);
                                        file_get_contents($url_addphones, false, $context_addphones);
                                    }
                                    
                                    $params_campaign ['version'] ="3.0";
                                    $params_campaign ['action'] = "createCampaign";
                                    $params_campaign ['key'] = $this->config->get('config_sms_pukey');
                                    $params_campaign ['sender'] = $settings["sms_alias"];
                                    $params_campaign ['text'] = $this->request->post['message'];
                                    $params_campaign ['list_id'] = $book_id;
                                    $params_campaign ['datetime'] = $timesend;
                                    $params_campaign ['sms_lifetime'] = '0';
                                    ksort ($params_campaign);
                                    $sum_campaign='';
                                    foreach ($params_campaign as $k_campaign=>$v_campaign)
                                    $sum_campaign.=$v_campaign;
                                    $sum_campaign .= $this->config->get('config_sms_prkey');
                                    $control_sum_campaign =  md5($sum_campaign);
                                    $url_campaign = "https://atompark.com/api/sms/3.0/createCampaign?key=".$params_campaign ['key']."&sum=".$control_sum_campaign."&sender=".$params_campaign ['sender']."&list_id=".$params_campaign ['list_id']."&sms_lifetime=".$params_campaign ['sms_lifetime'];
                                    $data_campaign = array('text' => $params_campaign ['text'], 'datetime' =>$params_campaign ['datetime']);
                                    $options_campaign = array(
                                        'http' => array(
                                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                        'method'  => 'POST',
                                        'content' => http_build_query($data_campaign),
                                        ),
                                    );
                                    $context_campaign  = stream_context_create($options_campaign);
                                    $json_campaign = file_get_contents($url_campaign, false, $context_campaign);
                                    $campaign = json_decode($json_campaign, true);
                                    $campaign_id = $campaign["result"]["id"];
                                    
                                    if ($campaign_id){
                                        $params_campinf ['version'] ="3.0";
                                        $params_campinf ['action'] = "getCampaignInfo";
                                        $params_campinf ['key'] = $this->config->get('config_sms_pukey');
                                        $params_campinf ['id'] = $campaign_id;
                                        ksort ($params_campinf);
                                        $sum_campinf='';
                                        foreach ($params_campinf as $k_campinf=>$v_campinf)
                                        $sum_campinf.=$v_campinf;
                                        $sum_campinf .= $this->config->get('config_sms_prkey');
                                        $control_sum_campinf =  md5($sum_campinf);
                                        $json_campinf = file_get_contents("https://atompark.com/api/sms/3.0/getCampaignInfo?key=".$params_campinf ['key']."&sum=".$control_sum_campinf."&id=".$params_campinf['id']);
                                        $campinf = json_decode($json_campinf, true);
                                        $campinf_status_code = $campinf ["result"]["status"];
                                        $campinf_status = array('В очереди отправки','Недостаточно денег для рассылки','В процессе рассылки',
                                                                'Отправлено','Нет правильных номеров получателей','Частично отправлено',
                                                                'Спам','Недействительное имя отправителя','Пауза','Запланирована','Ожидает модерации');
                                        if(array_key_exists($campinf_status_code, $campinf_status)){
                                            $this->data['campinf_status'] = $campinf_status[$campinf_status_code];
                                            $json['success'] = sprintf($this->language->get('text_success'), $this->data['campinf_status']);
                                            //$this->data['success'] = sprintf($this->language->get('text_success'), $this->data['campinf_status']);
                                        }
                                    } 
                                    $params_delbook ['version'] ="3.0";
                                    $params_delbook ['action'] = "delAddressbook";
                                    $params_delbook ['key'] = $this->config->get('config_sms_pukey');
                                    $params_delbook ['id'] = $book_id;
                                    ksort ($params_delbook);
                                    $sum_delbook='';
                                    foreach ($params_delbook as $k_delbook=>$v_delbook)
                                    $sum_delbook.=$v_delbook;
                                    $sum_delbook .= $this->config->get('config_sms_prkey');
                                    $control_sum_delbook =  md5($sum_delbook);
                                    file_get_contents("https://atompark.com/api/sms/3.0/delAddressbook?key=".$params_delbook ['key']."&sum=".$control_sum_delbook."&idAddressBook=".$params_delbook ['id']);

                                }
                                
                                /*end*/
			}
		}
		
		$this->response->setOutput(json_encode($json));	
	}
}
?>