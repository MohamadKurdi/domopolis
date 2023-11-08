<?php
/**
 * HTML Email template extension
 *
 * @author: Ben Johnson, opencart-templates
 * @email: info@opencart-templates.co.uk
 * @website: http://www.opencart-templates.co.uk
 *
 */
class ControllerModuleEmailtemplate extends Controller {

	private $error = array();
	private $params = null;
	private $vqmod_files = array('0pencart_emailtemplate', 'emailtemplate');

	/**
	 * List Of Email Templates & Config
	 */
	public function index(){
		if(!$this->installed()){
			$this->redirect($this->url->link('module/emailtemplate/installer', 'token='.$this->session->data['token'], 'SSL'));
		}

		$this->load->language('module/emailtemplate');
		$this->load->model('module/emailtemplate');
		$this->load->model('localisation/language');

		if($this->model_module_emailtemplate->checkVersion() !== false){
			$this->upgrade();
		}

		$this->data = array_merge($this->data, $this->load->language('module/emailtemplate'));

		$this->_setTitle();

		$this->_messages();

		$this->_breadcrumbs();

		$this->data['token'] = $this->session->data['token'];

		$this->data['action'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token']);
		$this->data['cancel'] = $this->url->link('extension/module', 'token='.$this->session->data['token']);

		$this->data['config_url'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&id=1');
		$this->data['templates_url'] = $this->url->link('module/emailtemplate/templates', 'token='.$this->session->data['token']);
		$this->data['test_url'] = $this->url->link('module/emailtemplate/test', 'token='.$this->session->data['token']);
		$this->data['logs_url'] = $this->url->link('module/emailtemplate/logs', 'token='.$this->session->data['token']);
		$this->data['language_url'] = $this->url->link('module/emailtemplate/language_files', 'token='.$this->session->data['token'].'&id='.$this->config->get('config_language_id'));
		$this->data['support_url'] = '';

		/*$classVQMod = new ReflectionClass('VQMod');
		if($classVQMod->isAbstract()){
			$vqmodVer = VQMod::$_vqversion;
		} else {
			$vqmodVer = '';
		}*/

		$vqmodVer = '';

		if(defined('VERSION')){
			$ocVer = VERSION;
		} else {
			$ocVer = '';
		}

		$i = 1;
		foreach(array('name'=>$this->config->get("config_owner").' - '.$this->config->get("config_name"), 'email'=>$this->config->get("config_email"), 'protocol'=>$this->config->get("config_mail_protocol"), 'storeUrl'=>HTTP_CATALOG, 'version'=>EmailTemplate::$version, 'opencartVersion'=>$ocVer, 'vqmodVersion'=>$vqmodVer, 'phpVersion'=>phpversion()) as $key=>$val){
			$this->data['support_url'] .= (($i == 1) ? '?' : '&amp;') . $key . '=' . html_entity_decode($val,ENT_QUOTES,'UTF-8');
			$i++;
		}

		$this->data['new_template'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token']);

		$this->_template_list();

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->_output('module/emailtemplate/extension.tpl');
	}

	/**
	 * Config Form
	 */
	public function config(){
		$this->load->model('module/emailtemplate');
		$this->data = array_merge($this->data, $this->load->language('module/emailtemplate'));

	    $this->_setTitle($this->language->get('heading_config'));

		if(!isset($this->request->get['id'])){
			return false;
		}
		$this->data['emailtemplate_config_id'] = $this->request->get['id'];

		if (isset($this->request->get['create_custom'])) {
			$newId = $this->model_module_emailtemplate->cloneConfig($this->data['emailtemplate_config_id'], $this->request->post);
			if($newId){
				$this->session->data['success'] = $this->language->get('success_config');
				$this->redirect($this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'].'&id='.$newId, 'SSL'));
			}
		}

	    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->_validateConfig($this->request->post)) {
			$data = $this->request->post;

	    	// check style changed
	    	$config = $this->model_module_emailtemplate->getConfig($this->data['emailtemplate_config_id'], true);
	    	if($config['emailtemplate_config_style'] != $data['emailtemplate_config_style']){
	    		$data = $this->_config_style($data);
	    	}

	        if($this->model_module_emailtemplate->updateConfig($this->data['emailtemplate_config_id'], $data)){
	            $this->session->data['success'] = $this->language->get('success_config');
	        }

	        if (isset($this->request->post['send_to']) && $this->request->post['send_to']) {
	        	$email = $this->request->post['send_to'];
	        	if($this->_validateEmailAddress($email)){
	        		if($this->model_module_emailtemplate->sendTestEmail($email, $config['store_id'], $config['language_id'])){
	        			$this->session->data['success'] = $this->language->get('success_send');
	        		}
	        	}
	        }

        	$this->redirect($this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'].'&id='.$this->data['emailtemplate_config_id'], 'SSL'));
	    }

	    $this->_messages();

	    $this->_breadcrumbs(array('heading_config' => array(
			'link' => 'module/emailtemplate/config',
			'params' => '&id='.$this->data['emailtemplate_config_id']
		)));

		$this->_config_form();

		$this->_output('module/emailtemplate/config.tpl');
	}

	/**
	 * Delete Config
	 */
	public function config_delete(){
		$this->load->language('module/emailtemplate');
		$this->load->model('module/emailtemplate');

		if(isset($this->request->get['id'])){
			if($this->model_module_emailtemplate->deleteConfig($this->request->get['id'])){
				$this->session->data['success'] = $this->language->get('success_config_delete');
			}

			$this->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
		}
	}

	/**
	 * Template Details
	 */
	public function template(){
		$this->load->language('module/emailtemplate');
		$this->load->model('module/emailtemplate');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->_validateTemplate($this->request->post)) {
			$url = '';

			if(isset($this->request->get['id'])){
				if($this->model_module_emailtemplate->updateTemplate($this->request->get['id'], $this->request->post)){
					$this->session->data['success'] = $this->language->get('text_success');
				}
				$url .= '&id='.$this->request->get['id'];
			} else {
				$id = $this->model_module_emailtemplate->insertTemplate($this->request->post);
				if($id){
					$url .= '&id='.$id;
					$this->session->data['success'] = $this->language->get('success_insert');
				}
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . $url, 'SSL'));
		}

		if(isset($this->session->data['vqmod_update_failed'])){
			$xml_files = $this->model_module_emailtemplate->getVqmodXml();

			$this->data['vqmod_xml'] = array();

			foreach($xml_files as $xml_file => $xml_content){
				if($xml_content){
					$this->data['vqmod_xml'][$xml_file] = htmlentities($xml_content, ENT_QUOTES, "UTF-8");
				}
			}

			unset($this->session->data['vqmod_update_failed']);
		}

		$this->_template_form();

		if(isset($this->request->get['id'])){
			$this->_output('module/emailtemplate/template_form.tpl');
		} else {
			$this->_output('module/emailtemplate/template_create_form.tpl');
		}
	}

	/**
	 * Delete Config
	 */
	public function delete_template(){
		$this->load->language('module/emailtemplate');
		$this->load->model('module/emailtemplate');

		if(isset($this->request->get['id'])){
			$result = $this->model_module_emailtemplate->deleteTemplate($this->request->get['id']);
		} elseif(isset($this->request->post['template_selected'])) {
			$result = $this->model_module_emailtemplate->deleteTemplate($this->request->post['template_selected']);
		}

		if($result){
			$apostrophe = ($result > 1) ? "'s" : "";
			$this->session->data['success'] = sprintf($this->language->get('success_delete_template'), $result, $apostrophe);
		}

		$this->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
	}

	/**
	 * Delete Shortcode(s)
	 */
	public function delete_shortcode(){
		$this->load->language('module/emailtemplate');
		$this->load->model('module/emailtemplate');

		if(isset($this->request->get['id'])){
			$selected = isset($this->request->post['shortcode_selected']) ? $this->request->post['shortcode_selected'] : array();

			if($this->model_module_emailtemplate->deleteTemplateShortcodes($this->request->get['id'], $selected)){
				$this->session->data['success'] = $this->language->get('text_success');
			}

			$this->redirect($this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id='.$this->request->get['id'], 'SSL'));
		}
	}

	/**
	 * Logs
	 */
	public function logs(){
		$this->load->language('module/emailtemplate');
		$this->load->model('module/emailtemplate');
		$this->load->model('sale/customer');
		$this->load->model('sale/customer_group');

		$this->data = array_merge($this->data, $this->load->language('module/emailtemplate'));

		if (isset($this->request->get['store_id']) && is_numeric($this->request->get['store_id'])) {
			$this->data['filter_store_id'] = $this->request->get['store_id'];
		} else {
			$this->data['filter_store_id'] = null;
		}

		if (isset($this->request->get['filter_emailtemplate_id'])) {
			$this->data['filter_emailtemplate_id'] = $this->request->get['filter_emailtemplate_id'];
		} else {
			$this->data['filter_emailtemplate_id'] = '';
		}

		if (isset($this->request->get['filter_store_id'])) {
			$this->data['filter_store_id'] = $this->request->get['filter_store_id'];
		} else {
			$this->data['filter_store_id'] = '';
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$this->data['filter_customer_group_id'] = $this->request->get['filter_customer_group_id'];
		} else {
			$this->data['filter_customer_group_id'] = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$this->data['filter_customer'] = $this->request->get['filter_customer'];
		} else {
			$this->data['filter_customer'] = '';
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$this->data['filter_customer_id'] = $this->request->get['filter_customer_id'];
		} else {
			$this->data['filter_customer_id'] = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'sent';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit']) && $this->request->get['limit'] <= 100) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_admin_limit');
		}

		$url = '';
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		if (isset($this->request->get['filter_emailtemplate_id'])) {
			$url .= '&filter_emailtemplate_id=' . $this->request->get['filter_emailtemplate_id'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . $this->request->get['filter_customer'];
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}

		if($this->request->server['REQUEST_METHOD'] == 'POST'){
			if(isset($this->request->get['control'])){
				$action = strtolower($this->request->get['control']);
				switch ($action){
					case 'delete':
						$result = $this->model_module_emailtemplate->deleteLogs($this->request->post['selected']);

						if($result){
							$this->session->data['success'] = sprintf($this->language->get('success_delete_log'), $result);
						}

						$this->redirect($this->url->link('module/emailtemplate/logs', 'token='.$this->session->data['token'].$url, 'SSL'));
					break;
				}
			}
		}

		$this->_setTitle($this->language->get('heading_logs'));

		$this->_messages();

		$this->_breadcrumbs(array('heading_logs' => array(
			'link' => 'module/emailtemplate/logs'
		)));

		$filter = array();
		$filter['start'] = ($page - 1) * $limit;
		$filter['limit'] = $limit;
		$filter['order'] = $order;
		$filter['sort'] = $sort;
		$filter['emailtemplate_id'] = $this->data['filter_emailtemplate_id'];
		$filter['store_id'] = $this->data['filter_store_id'];
		$filter['customer_group_id'] = $this->data['filter_customer_group_id'];
		$filter['customer_id'] = $this->data['filter_customer_id'];

		$result = $this->model_module_emailtemplate->getTemplateLogs($filter, true, true);

		$total = $this->model_module_emailtemplate->getTotalTemplateLogs($filter);

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['total'] = $total;

		foreach(array('subject', 'to', 'from',  'sent', 'read', 'store', 'emailtemplate') as $var){
			$this->data['sort_'.$var] = $this->url->link('module/emailtemplate/logs', 'token='.$this->session->data['token'] . '&sort=' . $var . $url);
		}

		$link = $this->url->link('module/emailtemplate/logs', 'token='.$this->session->data['token'] . $url . '&page={page}');
		$this->data['pagination'] = $this->_renderPagination($link, $page, $total, $limit, 'select');

		$this->data['action'] = $this->url->link('module/emailtemplate/logs', 'token='.$this->session->data['token'] . $url);

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['token'] = $this->session->data['token'];
		$this->data['url_params'] = $url;
		$this->data['action'] = $this->url->link('module/emailtemplate/logs', 'token='.$this->session->data['token'] . $url);
		$this->data['cancel'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token']);

		$this->data['logs'] = array();

		foreach($result as $row){
			$row['preview'] = EmailTemplate::truncate_str($row['subject'], 50);

			if($row['sent'] && $row['sent'] != '0000-00-00 00:00:00'){
				$time = strtotime($row['sent']);
				if(date('Ymd') == date('Ymd', $time)){
					$row['sent'] = date($this->language->get('time_format'), $time);
				} else {
					$row['sent'] = date($this->language->get('date_format_short'), $time);
				}
			} else {
				$row['sent'] = '';
			}

			if($row['read'] && $row['read'] != '0000-00-00 00:00:00'){
				$time = strtotime($row['read']);
				if(date('Ymd') == date('Ymd', $time)){
					$row['read'] = date($this->language->get('time_format'), $time);
				} else {
					$row['read'] = date($this->language->get('date_format_short'), $time);
				}
			} else {
				$row['read'] = '';
			}

			if($row['emailtemplate_id']){
				$row['emailtemplate'] = $this->model_module_emailtemplate->getTemplate($row['emailtemplate_id'], $this->config->get('config_language_id'), true);
				if($row['emailtemplate']){
					$row['emailtemplate']['url_edit'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id=' . $row['emailtemplate_id']);
				}
			}

			if($row['store_id'] >= 0){
				$row['store'] = $this->model_module_emailtemplate->getStores($row['store_id']);
			}

			$row['action'] = array();

			if($row['customer_id']){
				$customer = $this->model_sale_customer->getCustomer($row['customer_id']);
				if($customer){
					$row['customer'] = $customer;
					$row['customer']['url_edit'] = $this->url->link('sale/customer/update', 'token='.$this->session->data['token'] . '&customer_id=' . $row['customer_id']);
				}
			} else {
				$customer = $this->model_sale_customer->getCustomerByEmail($row['to']);
				if($customer){
					$row['customer'] = $customer;
					$row['customer_id'] = $customer['customer_id'];
					$row['customer']['url_edit'] = $this->url->link('sale/customer/update', 'token='.$this->session->data['token'] . '&customer_id=' . $row['customer_id']);
				}
			}

			$row['action']['load'] = array(
				'label' => $this->data['button_load'],
				'url' => $this->url->link('module/emailtemplate/log', 'token='.$this->session->data['token'] . '&id=' . $row['emailtemplate_log_id'], 'SSL')
			);
			if($row['emailtemplate_id']){
				$row['action']['edit'] = array(
					'label' => $this->data['button_edit_template'],
					'url' => $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&id=' . $row['emailtemplate_id'], 'SSL')
				);
			}

			$row['selected'] = isset($this->request->post['selected']) && in_array($row['emailtemplate_log_id'], $this->request->post['selected']);

			$this->data['logs'][] = $row;
		}

		$this->data['emailtemplates'] = $this->model_module_emailtemplate->getTemplates(array(), true);

		$this->data['stores'] = $this->model_module_emailtemplate->getStores();

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$this->_output('module/emailtemplate/logs.tpl');
	}

	/**
	 * Get Template & Parse Tags
	 */
	public function get_template(){
		if(!isset($this->request->get['id'])) return false;
		$return = array();
		$template = new EmailTemplate($this->request, $this->registry);

		$template->set('insert_shortcodes', false);

		if(isset($this->request->get['parse']) && !$this->request->get['parse']){
			$template->set('parse_shortcodes', false);
		}

		$template_data = array(
			'emailtemplate_id' => $this->request->get['id']
		);

		if(isset($this->request->get['store_id'])){
			$template_data['store_id'] = $this->request->get['store_id'];
		}

		if(isset($this->request->get['language_id'])){
			$template_data['language_id'] = $this->request->get['language_id'];

			if($template->load($template_data)){
				$template->build();
				$return[$language['language_id']] = $template->data;
			}
		} else {
			$this->load->model('localisation/language');
			$languages = $this->model_localisation_language->getLanguages();

			foreach($languages as $language){
				$template_data['language_id'] = $language['language_id'];

				if($template->load($template_data)){
					$template->build();
					$return[$language['language_id']] = $template->data;
				}
			}
		}

		$this->response->setOutput(json_encode($return));
	}

	/**
	 * Get Template Shortcodes
	 *
	 * @deprecated
	 */
	public function get_template_shortcodes(){
		$this->load->model('module/emailtemplate');

		$filter = array();
		if(!empty($this->request->get['id'])){
			$filter['emailtemplate_id'] = $this->request->get['id'];
		} elseif(!empty($this->request->get['key'])){
			$filter['emailtemplate_key'] = $this->request->get['key'];
		} else {
			return false;
		}

		$return = $this->model_module_emailtemplate->getTemplateShortcodes($filter, true);

		$this->response->setOutput(json_encode($return));
	}

	/**
	 * Edit Shortcode
	 */
	public function template_shortcode(){
		if(!isset($this->request->get['id'])){
			$this->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
		}

		$this->load->language('module/emailtemplate');
		$this->load->model('module/emailtemplate');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->_validateTemplateShortcode($this->request->post)) {
			$url = '';

			if(isset($this->request->get['id'])){
				if($this->model_module_emailtemplate->updateTemplateShortcode($this->request->get['id'], $this->request->post)){
					$this->session->data['success'] = $this->language->get('text_success');
				}
				$url .= '&id='.$this->request->get['id'];
			} else {
				$id = $this->model_module_emailtemplate->insertTemplateShortcode($this->request->post);
				if($id){
					$url .= '&id='.$id;
					$this->session->data['success'] = $this->language->get('success_insert');
				}
			}

			$this->redirect($this->url->link('module/emailtemplate/template_shortcode', 'token='.$this->session->data['token'] . $url, 'SSL'));
		}

		$this->_template_shortcode_form();

		$this->_output('module/emailtemplate/template_shortcode_form.tpl');
	}

	/**
	 * Fetch Template & Parse Tags
	 */
	public function fetch_template($data = array()){
		if(empty($data)){
			$data = $this->request->get;
		}
		$template_data = array();
		$template = new EmailTemplate($this->request, $this->registry);

		$template->set('insert_shortcodes', false);

		if(isset($data['parse']) && !$data['parse']){
			$template->set('parse_shortcodes', false);
		}

		if (isset($data['order_id'])) {
			$this->load->model('sale/order');
			$order_info = $this->model_sale_order->getOrder($data['order_id']);

			$template->addData($order_info);
			$template->data['payment_address'] = EmailTemplate::formatAddress($order_info, 'payment', $order_info['payment_address_format']);
			$template->data['shipping_address'] = EmailTemplate::formatAddress($order_info, 'shipping', $order_info['shipping_address_format']);
		}

		if (isset($data['id'])) {
			$template_data['emailtemplate_id'] = $this->request->get['id'];
		}
		if (isset($data['store_id'])) {
			$template_data['store_id'] = $this->request->get['store_id'];
		}
		if (isset($data['language_id'])) {
			$template_data['language_id'] = $this->request->get['language_id'];
		}
		if (isset($data['customer_id'])) {
			$template_data['customer_id'] = $this->request->get['customer_id'];
		}

		if(empty($template_data)) return false;

		if($template->load($template_data)){
			$template->build();

			if(isset($data['output']) && isset($template->data['emailtemplate'][$data['output']])){
				$html = $template->data['emailtemplate'][$data['output']];
			} else {
				$template->set('wrapper_tpl', '');
				$html = $template->fetch();
			}

			echo $html;
			exit;
		}
	}

	/**
	 * Fetch Template Log
	 */
	public function fetch_log($data = array()){
		$data = array_merge($data, $this->request->get);

		if(empty($data['id'])){
			return false;
		}

		$this->load->model('module/emailtemplate');

		$return = $this->model_module_emailtemplate->getTemplateLog($data['id'], true);

		if(empty($return)){
			return false;
		}

		$return['preview'] = EmailTemplate::truncate_str($return['subject'], 50);

		$return['text'] = nl2br($return['text']);

		if($return['sent'] && $return['sent'] != '0000-00-00 00:00:00'){
			$time = strtotime($return['sent']);
			if(date('Ymd') == date('Ymd', $time)){
				$return['sent'] = date($this->language->get('time_format'), $time);
			} else {
				$return['sent'] = date($this->language->get('date_format_long'), $time);
			}
		} else {
			$return['sent'] = '';
		}

		if($return['read'] && $return['read'] != '0000-00-00 00:00:00'){
			$time = strtotime($return['read']);
			if(date('Ymd') == date('Ymd', $time)){
				$return['read'] = date($this->language->get('time_format'), $time);
			} else {
				$return['read'] = date($this->language->get('date_format_short'), $time);
			}
		} else {
			$return['read'] = '';
		}

		if($return['read_last'] && $return['read_last'] != '0000-00-00 00:00:00'){
			$time = strtotime($return['read_last']);
			if(date('Ymd') == date('Ymd', $time)){
				$return['read_last'] = date($this->language->get('time_format'), $time);
			} else {
				$return['read_last'] = date($this->language->get('date_format_short'), $time);
			}
		} else {
			$return['read_last'] = '';
		}

		if(isset($data['output']) && $data['output'] == 'html') {
			echo $return['html'];
			exit(0);
		} else {
			$this->response->setOutput(json_encode($return));
		}
	}

	/**
	 * Get preview of email using order
	 */
	public function preview_email(){
		$this->load->model('module/emailtemplate');

		if(!empty($this->request->get['order_id'])){
			$template = $this->model_module_emailtemplate->getCompleteOrderEmail($this->request->get['order_id'], array(), $this->request->get['key']);
		} else {
			$template = new EmailTemplate($this->request, $this->registry);

			if(!empty($this->request->get['emailtemplate_id'])){
				$data = $this->model_module_emailtemplate->getTemplateShortcodes($this->request->get['emailtemplate_id']);
				foreach($data as $shortcode){
					$template->addData($shortcode['emailtemplate_shortcode_code'], $shortcode['emailtemplate_shortcode_example']);
				}
			}

			$template->load($this->request->get);

			$template->set('insert_shortcodes', false);

			$template->build();
		}

		echo $template->fetch();
		exit;
	}

	/**
	 * Download preview of invoice
	 */
	public function preview_invoice(){
		exit;
	}

	/**
	 * Load language editor
	 */
	public function language_files(){
		$this->language->load_full('module/emailtemplate');
		$this->load->model('module/emailtemplate');
		$this->load->model('localisation/language');

		$this->data = array_merge($this->data, $this->load->language('module/emailtemplate'));

		$this->_setTitle($this->language->get('heading_language'));

		if(isset($this->request->post['language_search']) && $this->request->post['language_search']){
			$this->data['language_search'] = $this->request->post['language_search'];
		} else {
			$this->data['language_search'] = '';
		}

		$this->data['token'] = $this->session->data['token'];

		if(isset($this->request->post['language'])){
			$this->data['id'] = intval($this->request->post['language']);
		} else {
			$this->data['id'] = $this->config->get('config_language_id');
		}
		if(isset($this->request->post['admin'])){
			$this->data['type'] = 'admin';
		} else {
			$this->data['type'] = 'catalog';
		}

		$this->_messages();

		$this->_breadcrumbs(array('heading_language' => array(
			'link' => 'module/emailtemplate/language_files',
			'params' => '&id='.$this->data['id']
		)));

		$this->data['action'] = $this->url->link('module/emailtemplate/language_files', 'token='.$this->session->data['token'] . '&id=' . $this->data['id']);
		$this->data['cancel'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token']);

		switch($this->data['type']){
			case 'admin':
				$dir = DIR_LANGUAGE;
			break;

			default;
			case 'catalog':
				$dir = DIR_CATALOG . 'language/';
		}

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$language = $this->model_localisation_language->getLanguage($this->data['id']);
		if(!$language || !isset($language['directory']))
			return false;

		$langDir = rtrim($dir . str_replace('../', '', $language['directory']), '/');

		$directories = glob($langDir.'/*', GLOB_ONLYDIR);

		natsort($directories);

		$this->data['language_files'] = array();
		$this->data['language_files_count'] = 0;

		if ($directories) {
			foreach ($directories as $directory) {
				$i = basename($directory);
				$this->data['language_files'][$i] = array();
				$this->data['language_files'][$i]['dir'] = $directory;

				$_dirs = explode('/', $directory);
				$this->data['language_files'][$i]['path'] = $_dirs[sizeof($_dirs)-1];

				$files = glob(rtrim($directory, '/') . '/*.php');
				if ($files)  {
					natsort($files);

					$this->data['language_files'][$i]['files'] = array();

					foreach ($files as $ii => $file) {
						$ii = basename($file, ".php");

						if(mb_substr($ii, -1) == '_') continue;

						$localFile = basename(ltrim(str_replace($directory, '', $file), '/'), ".php");

						if($this->data['language_search']){
							$oLanguage = new Language($language['directory']);
							$oLanguage->setPath($dir);
							$language_vars = $oLanguage->load_full($this->data['language_files'][$i]['path'].'/'.$localFile);

							if(!preg_grep('~'.$this->data['language_search'].'~i', $language_vars)) {
								continue;
							}
						}

						$this->data['language_files'][$i]['files'][$localFile] = array(
							'file' => $localFile,
							'action' => $this->url->link('module/emailtemplate/language_file', 'file='.$language['directory'].'/'.$i.'/'.$ii.'&type='.$this->data['type'].'&token='.$this->session->data['token'], 'SSL')
						);

						$this->data['language_files_count']++;
					}
				}
			}
		}

		$this->_output('module/emailtemplate/language_files.tpl');
	}

	/**
	 * Parse Template Tags
	 */
	public function language_file(){
		$this->language->load_full('module/emailtemplate');
		$this->load->model('module/emailtemplate');
		$this->load->model('localisation/language');

		$this->data = array_merge($this->data, $this->load->language('module/emailtemplate'));

		# URL params
		if(isset($this->request->get['type'])){
			$this->data['type'] = $this->request->get['type'];
		} else {
			return false;
		}

		if(isset($this->request->get['file'])){
			$this->data['file'] = $this->request->get['file'];
		} else {
			return false;
		}

		# Type
		if($this->data['type'] == 'admin'){
			$dir = DIR_LANGUAGE;
		} else {
			$dir = DIR_CATALOG . 'language/';
		}

		list($language, $directory, $file) = explode('/', $this->data['file']);

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$result = $this->model_module_emailtemplate->languageFile($dir, $language, $directory, $file, $this->request->post['vars']);

			if(is_array($result)) {
				$this->error['warning'] = sprintf($this->language->get('error_language_permissions'), str_replace(str_replace('/system', '', DIR_SYSTEM), '', $result['file']));
				$this->data['manual'] = $result;
				$this->data['manual']['info'] = sprintf($this->language->get('text_language_permissions'), $result['filename'], $result['path']);
			} else {
				if($result === true){
					$this->session->data['success'] = $this->language->get('text_success');
				}

				$this->redirect($this->url->link('module/emailtemplate/language_file', 'token='.$this->session->data['token'].'&file='.$this->data['file'].'&type='.$this->data['type'], 'SSL'));
			}
		}

		$this->_setTitle($this->language->get('heading_language'). ' - ' . $this->data['file']);

		$this->_messages();

		$this->data['token'] = $this->session->data['token'];

		$this->_breadcrumbs(array(
			'heading_language' => array(
				'link' => 'module/emailtemplate/language_files'
			),
			$this->data['file'] => array(
				'link' => 'module/emailtemplate/language_file',
				'params' => '&file='.$this->data['file']
			)
		));

		$this->data['action'] = $this->url->link('module/emailtemplate/language_file', 'token='.$this->session->data['token'].'&file='.$this->data['file'].'&type='.$this->data['type']);
		$this->data['cancel'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token']);

		# Load language
		$language = new Language($language);
		$language->setPath($dir);
		$language_vars = $language->load_full($directory.'/'.$file);

		$this->data['language_vars'] = array();

		foreach($language_vars as $key => $value){
			if(isset($this->request->post['vars'][$key])){
				$value = $this->request->post['vars'][$key];
			}
			$this->data['language_vars'][] = array(
				'key' => $key,
				'value' => $value,
				'hasHtml' => ($value == strip_tags(html_entity_decode($value,ENT_QUOTES,'UTF-8'))) ? false : true
			);
		}

		$this->_output('module/emailtemplate/language_file_form.tpl');
	}

	/**
	 * Get CSS File
	 */
	public function css(){
		if(isset($this->request->get['language_id'])){
			$language_id = $this->request->get['language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		if(isset($this->request->get['store_id'])){
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

	    $template = new EmailTemplate($this->request, $this->registry);

		if($template->load(array(
			'emailtemplate_id' => 1,
			'language_id' => $language_id,
			'store_id' => $store_id
		))){
			$template->build();
			$template->set('insert_shortcodes', false);

			$content = $template->fetchCss();

			if($content){
				header('Content-Type: text/css; charset: UTF-8');
				echo $content;
				exit();
			}
		}

	}

	/**
	 * Test module/vqmod
	 * - Most of this code taken from vqmod file
	 */
	public function test(){
		$this->language->load_full('module/emailtemplate');

		$this->data = array_merge($this->data, $this->load->language('module/emailtemplate'));

		$testFiles = $this->_vqmodFileFiles();
		$log = VQMod::$logFolder . date('w_D') . '.log';
		$logPath = VQMod::path($log, true);

		if(file_exists($logPath)) unlink($logPath);

		$this->_vqmod();

		foreach($testFiles as $file){
			VQMod::modCheck($file);
		}

		$oVqmodLog = VQMod::$log;
		$oVqmodLog->__destruct();

		VQMod::$log = $oVqmodLog;

		if(file_exists($logPath)){
			unlink($logPath);
			$this->session->data['error'] = sprintf($this->language->get('error_vqmod_log'), $log);
		} else {
			$this->session->data['success'] = $this->language->get('test_success');
		}

		$this->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
	}

	/**
	 * Check module installed
	 */
	public function installed(){
		$chk = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX . "emailtemplate'");
		if($chk->num_rows) return true;

		return false;
	}

	/**
	 * Opencart module install, doesnt really do anything see installer()
	 */
	public function install(){
		$this->load->language('module/emailtemplate');

		if(!class_exists('VQMod')) {
			die('Error: check vqmod installed for admin/index.php, vqcache files are getting generated &amp; upgrade vqmod version greater than: 2.4.0');
		}

		$this->_vqmod("install");

		$this->redirect($this->url->link('module/emailtemplate/installer', 'token='.$this->session->data['token'], 'SSL'));
	}

	/**
	 * Install module
	 */
	public function installer(){
		$this->data = array_merge($this->data, $this->load->language('module/emailtemplate'));

		$this->load->model('module/emailtemplate');
		$this->load->model('setting/setting');

		if ($this->request->server['REQUEST_METHOD'] == 'POST'){
			if($this->model_module_emailtemplate->install($this->request->post)){
				$this->session->data['success'] = $this->language->get('install_success');
				$this->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
			}
		}

		$this->_setTitle($this->language->get('heading_install'));

		$this->_messages();

		$this->data['token'] = $this->session->data['token'];

		$this->_breadcrumbs(array(
			'heading_install' => array(
				'link' => 'module/emailtemplate/installer'
			)
		), false);

		$this->data['action'] = $this->url->link('module/emailtemplate/installer', 'token='.$this->data['token']);
		$this->data['cancel'] = $this->url->link('extension/module', 'token='.$this->data['token']);

		$this->data['install_cleanup'] = false;

		$result = $this->db->query("SHOW TABLES LIKE '". DB_PREFIX . "email_templates'");
		if($result->rows || file_exists(DIR_SYSTEM . 'library/email_template.php')){
			$this->data['install_cleanup'] = true;
		}

		$this->_output('module/emailtemplate/install.tpl');
	}

	/**
	* Delete module settings for each store.
	*/
	public function uninstall(){
		$this->load->language('module/emailtemplate');

		if (!$this->user->hasPermission('modify', 'module/emailtemplate')) {
			$this->session->data['error'] = $this->language->get('error_permission');
			$this->redirect($this->url->link('extension/module', 'token='.$this->session->data['token'], 'SSL'));
		} else {
			$this->load->model('setting/store');
			$this->load->model('setting/setting');
			$this->load->model('module/emailtemplate');

			foreach ($this->model_setting_store->getStores() as $store) {
				$this->model_setting_setting->deleteSetting("emailtemplate", $store['store_id']);
			}

			if($this->model_module_emailtemplate->uninstall() && $this->_vqmod("uninstall")){
				$this->session->data['success'] = $this->language->get('uninstall_success');
				return true;
			}
		}
	}

	/**
	 * Upgrade Extension
	 */
	public function upgrade(){
		$this->load->language('module/emailtemplate');
		$this->load->model('module/emailtemplate');

		if($this->model_module_emailtemplate->upgrade()){
			$this->session->data['success'] = $this->language->get('upgrade_success');
		}

		$this->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
	}


	/**
	 * Get Extension Form
	 */
	private function _config_form(){
		# Load extra models
		$this->load->model('setting/store');
		$this->load->model('tool/image');
		$this->load->model('localisation/language');
		$this->load->model('sale/customer_group');

		$this->data['token'] = $this->session->data['token'];
		$this->data['action'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&id=' . $this->data['emailtemplate_config_id']);
		if($this->data['emailtemplate_config_id'] != 1){
			$this->data['action_delete'] = $this->url->link('module/emailtemplate/config_delete', 'token='.$this->session->data['token'] . '&id=' . $this->data['emailtemplate_config_id']);
		}
		$this->data['cancel'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token']);

		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		$this->data['stores'] = $this->model_module_emailtemplate->getStores();
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		# Installed Themes
		$this->data['themes'] = array();
		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		foreach ($directories as $directory) {
			$this->data['themes'][] = basename($directory);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->data['emailtemplate_config'] = $this->model_module_emailtemplate->getConfig($this->data['emailtemplate_config_id']);
		$cols = EmailTemplateConfigDAO::describe();
		foreach($cols as $col => $type){
			if(isset($this->request->post[$col])) {
				$this->data['emailtemplate_config'][$col] = $this->request->post[$col];
			}
		}
		$this->data['emailtemplate_config'] = $this->model_module_emailtemplate->formatConfig($this->data['emailtemplate_config'], true);

		if(isset($this->data['emailtemplate_config']['language_id'])){
			$language_id = $this->data['emailtemplate_config']['language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		if(isset($this->data['emailtemplate_config']['store_id'])){
			$store_id = $this->data['emailtemplate_config']['store_id'];
		} else {
			$store_id = 0;
		}

		if($this->data['emailtemplate_config']['showcase'] == 'products'){
			$this->data['showcase_selection'] = $this->model_module_emailtemplate->getShowcase($this->data['emailtemplate_config']);
		}

		# Configs
		$configs = $this->model_module_emailtemplate->getConfigs(array(
			'not_emailtemplate_config_id' => $this->data['emailtemplate_config_id']
		));
		$this->data['configs'] = array();

		foreach($configs as $key => $item){
			if($item['emailtemplate_config_modified'] && $item['emailtemplate_config_modified'] != '0000-00-00 00:00:00'){
				$item['emailtemplate_config_modified'] = date($this->language->get('date_format_long'), strtotime($item['emailtemplate_config_modified']));
			} else {
				$item['emailtemplate_config_modified'] = '';
			}

			$item['action'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&id=' . $item['emailtemplate_config_id']);
			if($item['emailtemplate_config_id'] != 1){
				$item['action_delete'] = $this->url->link('module/emailtemplate/config_delete', 'token='.$this->session->data['token'] . '&id=' . $item['emailtemplate_config_id']);
			}

			# Clean up column names
			foreach($item as $col => $val){
				if (strpos($col, 'emailtemplate_config_') === 0 && substr($col, -3) != '_id') {
					$item[substr($col, 21)] = $val; # 21=strlen('emailtemplate_config_')
					unset($item[$col]);
				}
			}

			# Language
			if($item['language_id'] > 0){
				$this->load->model('localisation/language');
				$item['language'] = $this->model_localisation_language->getLanguage($item['language_id']);
			}

			# Store
			if($item['store_id'] >= 0){
				$item['store'] = $this->model_module_emailtemplate->getStores($item['store_id']);
			}

			# Customer Group
			if($item['customer_group_id'] > 0){
				$this->load->model('sale/customer_group');
				$item['customer_group'] = $this->model_sale_customer_group->getCustomerGroup($item['customer_group_id']);
			}

			$this->data['configs'][$key] = $item;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'modified';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->data['sort_name'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&sort=name' . $url);
		$this->data['sort_modified'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&sort=modified' . $url);
		$this->data['sort_language'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&sort=language' . $url);
		$this->data['sort_store'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&sort=store' . $url);
		$this->data['sort_customer_group'] = $this->url->link('module/emailtemplate/config', 'token='.$this->session->data['token'] . '&sort=customer_group' . $url);


		$result = $this->db->query("SELECT `order_id` FROM `" . DB_PREFIX . "order` WHERE `store_id` = '{$store_id}' AND (`language_id` = '{$language_id}' OR `language_id` > 0) AND order_status_id > '0' ORDER BY `order_id` DESC LIMIT 1");
		if($result->row){
			$this->data['invoice_preview'] = $this->url->link('module/emailtemplate/preview_invoice', 'token='.$this->session->data['token'] . '&order_id='.$result->row['order_id']);
			$this->data['preview_order_id'] = $result->row['order_id'];
		} else {
			$this->data['error_preview'] = $this->language->get('error_preview_order');
		}

	}

	/**
	 * Get Templates
	 */
	private function _config_style(array $data){
		foreach(array('top', 'bottom', 'left', 'right') as $place){
			foreach(array('length', 'overlap', 'start', 'end', 'left_img', 'right_img') as $var){
				$data['emailtemplate_config_shadow_'.$place][$var] = '';
				$data['emailtemplate_config_shadow_'.$place][$var] = '';
				$data['emailtemplate_config_shadow_'.$place][$var] = '';
				$data['emailtemplate_config_shadow_'.$place][$var] = '';
			}
		}

		$data['emailtemplate_config_head_section_bg_color'] = '';
		$data['emailtemplate_config_header_section_bg_color'] = '';
		$data['emailtemplate_config_body_section_bg_color'] = '';
		$data['emailtemplate_config_footer_section_bg_color'] = '';

		switch($data['emailtemplate_config_style']){
			case 'white':
				$data['emailtemplate_config_wrapper_tpl'] = '_main.tpl';
				$data['emailtemplate_config_body_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_page_bg_color'] = '#F9F9F9';

				$data['emailtemplate_config_shadow_top'] = array();
				$data['emailtemplate_config_shadow_top']['length'] = '';
				$data['emailtemplate_config_shadow_top']['overlap'] = '';
				$data['emailtemplate_config_shadow_top']['start'] = '';
				$data['emailtemplate_config_shadow_top']['end'] = '';
				$data['emailtemplate_config_shadow_top']['left_img'] = '';
				$data['emailtemplate_config_shadow_top']['right_img'] = '';

				$data['emailtemplate_config_shadow_bottom'] = array();
				$data['emailtemplate_config_shadow_bottom']['length'] = 9;
				$data['emailtemplate_config_shadow_bottom']['overlap'] = 8;
				$data['emailtemplate_config_shadow_bottom']['start'] = '#d4d4d4';
				$data['emailtemplate_config_shadow_bottom']['end'] = '#ffffff';
				$data['emailtemplate_config_shadow_bottom']['left_img'] = 'data/emailtemplate/white/bottom_left.png';
				$data['emailtemplate_config_shadow_bottom']['right_img'] = 'data/emailtemplate/white/bottom_right.png';

				$data['emailtemplate_config_shadow_left'] = array();
				$data['emailtemplate_config_shadow_left']['length'] = 9;
				$data['emailtemplate_config_shadow_left']['overlap'] = 8;
				$data['emailtemplate_config_shadow_left']['start'] = '#ffffff';
				$data['emailtemplate_config_shadow_left']['end'] = '#d4d4d4';

				$data['emailtemplate_config_shadow_right'] = array();
				$data['emailtemplate_config_shadow_right']['length'] = 9;
				$data['emailtemplate_config_shadow_right']['overlap'] = 8;
				$data['emailtemplate_config_shadow_right']['start'] = '#d4d4d4';
				$data['emailtemplate_config_shadow_right']['end'] = '#ffffff';
			break;

			case 'page':
				$data['emailtemplate_config_wrapper_tpl'] = '_main.tpl';
				$data['emailtemplate_config_body_bg_color'] = '#F9F9F9';
				$data['emailtemplate_config_page_bg_color'] = '#FFFFFF';

				$data['emailtemplate_config_shadow_top'] = array();
				$data['emailtemplate_config_shadow_top']['length'] = '';
				$data['emailtemplate_config_shadow_top']['overlap'] = '';
				$data['emailtemplate_config_shadow_top']['start'] = '';
				$data['emailtemplate_config_shadow_top']['end'] = '';
				$data['emailtemplate_config_shadow_top']['left_img'] = '';
				$data['emailtemplate_config_shadow_top']['right_img'] = '';

				$data['emailtemplate_config_shadow_bottom'] = array();
				$data['emailtemplate_config_shadow_bottom']['length'] = 9;
				$data['emailtemplate_config_shadow_bottom']['overlap'] = 8;
				$data['emailtemplate_config_shadow_bottom']['start'] = '#d4d4d4';
				$data['emailtemplate_config_shadow_bottom']['end'] = '#f8f8f8';
				$data['emailtemplate_config_shadow_bottom']['left_img'] = 'data/emailtemplate/gray/bottom_left.png';
				$data['emailtemplate_config_shadow_bottom']['right_img'] = 'data/emailtemplate/gray/bottom_right.png';

				$data['emailtemplate_config_shadow_left'] = array();
				$data['emailtemplate_config_shadow_left']['length'] = 9;
				$data['emailtemplate_config_shadow_left']['overlap'] = 8;
				$data['emailtemplate_config_shadow_left']['start'] = '#f8f8f8';
				$data['emailtemplate_config_shadow_left']['end'] = '#d4d4d4';

				$data['emailtemplate_config_shadow_right'] = array();
				$data['emailtemplate_config_shadow_right']['length'] = 9;
				$data['emailtemplate_config_shadow_right']['overlap'] = 8;
				$data['emailtemplate_config_shadow_right']['start'] = '#d4d4d4';
				$data['emailtemplate_config_shadow_right']['end'] = '#f8f8f8';
			break;

			case 'clean':
				$data['emailtemplate_config_wrapper_tpl'] = '_main.tpl';
				$data['emailtemplate_config_body_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_page_bg_color'] = '#FFFFFF';
			break;

			case 'border':
				$data['emailtemplate_config_wrapper_tpl'] = '_main.tpl';
				foreach(array('bottom', 'left', 'right') as $place){
					$data['emailtemplate_config_shadow_'.$place]['length'] = 1;
					$data['emailtemplate_config_shadow_'.$place]['overlap'] = 0;
					$data['emailtemplate_config_shadow_'.$place]['start'] = '#515151';
					$data['emailtemplate_config_shadow_'.$place]['end'] = '#515151';
					$data['emailtemplate_config_shadow_'.$place]['left_img'] = '';
					$data['emailtemplate_config_shadow_'.$place]['right_img'] = '';
				}
			break;

			case 'sections':
				$data['emailtemplate_config_wrapper_tpl'] = '_main.tpl';
				$data['emailtemplate_config_body_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_page_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_body_section_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_header_section_bg_color'] = $data['emailtemplate_config_header_bg_color'];

				$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
				$data['emailtemplate_config_head_section_bg_color'] = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
				$data['emailtemplate_config_footer_section_bg_color'] = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];

				foreach(array('top', 'bottom', 'left', 'right') as $place){
					foreach(array('length', 'overlap', 'start', 'end', 'left_img', 'right_img') as $var){
						$data['emailtemplate_config_shadow_'.$place][$var] = '';
						$data['emailtemplate_config_shadow_'.$place][$var] = '';
						$data['emailtemplate_config_shadow_'.$place][$var] = '';
						$data['emailtemplate_config_shadow_'.$place][$var] = '';
					}
				}
			break;

			case 'inner_page':
				$data['emailtemplate_config_wrapper_tpl'] = '_main_inner.tpl';
				$data['emailtemplate_config_body_bg_color'] = '#FFFFFF';
				$data['emailtemplate_config_page_bg_color'] = '#E85140';
				$data['emailtemplate_config_body_font_color'] = '#FFFFFF';

				foreach(array('top', 'bottom', 'left', 'right') as $place){
					foreach(array('length', 'overlap', 'start', 'end', 'left_img', 'right_img') as $var){
						$data['emailtemplate_config_shadow_'.$place][$var] = '';
						$data['emailtemplate_config_shadow_'.$place][$var] = '';
						$data['emailtemplate_config_shadow_'.$place][$var] = '';
						$data['emailtemplate_config_shadow_'.$place][$var] = '';
					}
				}
			break;
		}

		return $data;
	}

	/**
	 * Get Templates
	 */
	private function _template_list($data = null){
		if(is_null($data)) $data = $this->request->get;

		$this->load->model('sale/customer_group');

		if (isset($data['store_id']) && is_numeric($data['store_id'])) {
			$this->data['templates_store_id'] = $data['store_id'];
		} else {
			$this->data['templates_store_id'] = NULL;
		}

		if (isset($data['customer_group_id'])) {
			$this->data['templates_customer_group_id'] = $data['customer_group_id'];
		} else {
			$this->data['templates_customer_group_id'] = '';
		}

		if (isset($data['key'])) {
			$this->data['templates_key'] = $data['key'];
		} else {
			$this->data['templates_key'] = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'label';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = $this->config->get('config_admin_limit');
		$filter = array(
			'language_id' => $this->config->get('config_language_id'),
			'store_id' => $this->data['templates_store_id'],
			'customer_group_id' => $this->data['templates_customer_group_id'],
			'emailtemplate_key' => $this->data['templates_key'],
			'emailtemplate_default' => 1,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);

		if (isset($data['not_emailtemplate_id'])) {
			$filter['not_emailtemplate_id'] = $data['not_emailtemplate_id'];
		}

		if (isset($data['default'])) {
			$filter['emailtemplate_default'] = $data['default'];
		}

		$templates_total = $this->model_module_emailtemplate->getTotalTemplates($filter);
		$results = $this->model_module_emailtemplate->getTemplates($filter);

		$this->data['templates'] = array();
		foreach ($results as $item) {
			$row = array(
				'id' 		  	=> $item['emailtemplate_id'],
				'emailtemplate_config_id' => $item['emailtemplate_config_id'],
				'store_id' 		=> $item['store_id'],
				'customer_group_id' => $item['customer_group_id'],
				'key'    	  	=> $item['emailtemplate_key'],
				'name'    	  	=> $item['emailtemplate_label'] ? $item['emailtemplate_label'] : $item['emailtemplate_key'],
				'label'    	  	=> $item['emailtemplate_label'],
				'template'    	=> $item['emailtemplate_template'],
				'status'      	=> $item['emailtemplate_status'],
				'default'      	=> $item['emailtemplate_default'],
				'shortcodes'    => $item['emailtemplate_shortcodes'],
				'action'		=> $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id=' . $item['emailtemplate_id']),
				'action_delete' => $this->url->link('module/emailtemplate/delete_template', 'token='.$this->session->data['token'] . '&id=' . $item['emailtemplate_id']),
				'selected'	  	=> isset($this->request->post['selected']) && in_array($item['emailtemplate_id'], $this->request->post['selected'])
			);

			$row['custom_count'] = $this->model_module_emailtemplate->getTotalTemplates(array(
				'emailtemplate_key' => $item['emailtemplate_key'],
				'emailtemplate_default' => 0
			));

			$modified = strtotime($item['emailtemplate_modified']);
			if(date('Ymd') == date('Ymd', $modified)){
				$row['modified'] = date($this->language->get('time_format'), $modified);
			} else {
				$row['modified'] = date($this->language->get('date_format_short'), $modified);
			}

			if($item['emailtemplate_id'] == 1){
				$row['action_delete'] = '';
			}

			if($item['store_id'] >= 0){
				$row['store'] = $this->model_module_emailtemplate->getStores($item['store_id']);
			}

			if($item['customer_group_id']){
				$row['customer_group'] = $this->model_sale_customer_group->getCustomerGroup($item['customer_group_id']);
			}

			$this->data['templates'][] = $row;
		}

		$url = '';
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($data['page'])) {
			$url .= '&page=' . $data['page'];
		}

		$this->data['template_delete'] = $this->url->link('module/emailtemplate/delete_template', 'token='.$this->session->data['token']);

		$this->data['sort_label'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=label' . $url);
		$this->data['sort_key'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=key' . $url);
		$this->data['sort_template'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=template' . $url);
		$this->data['sort_shortcodes'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=shortcodes' . $url);
		$this->data['sort_default'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=default' . $url);
		$this->data['sort_modified'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=modified' . $url);
		$this->data['sort_content'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=content' . $url);
		$this->data['sort_status'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=status' . $url);
		$this->data['sort_store'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=store' . $url);
		$this->data['sort_customer_group'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . '&sort=customer_group' . $url);

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$url = '';
		if (isset($data['sort'])) {
			$url .= '&sort=' . $data['sort'];
		}
		if (isset($data['order'])) {
			$url .= '&order=' . $data['order'];
		}

		$link = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token'] . $url . '&page={page}');
		$this->data['pagination'] = $this->_renderPagination($link, $page, $templates_total, $limit);

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$this->data['stores'] = $this->model_module_emailtemplate->getStores();

		$this->data['emailtemplate_keys'] = $this->model_module_emailtemplate->getTemplateKeys();
	}


	/**
	 * Get Template Shortcodes
	 */
	private function _shortcodes_list($data = null){
		if(is_null($data)) $data = $this->request->get;

		if (isset($data['id'])) {
			$this->data['shortcode_emailtemplate_id'] = $data['id'];
		} else {
			return false;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'code';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		$limit = 15;
		$data = array(
			'emailtemplate_id'  => $this->data['shortcode_emailtemplate_id'],
			'sort'  => $sort,
			'order' => $order
		);

		$results = $this->model_module_emailtemplate->getTemplateShortcodes($data);

		$this->data['shortcodes'] = array();
		foreach ($results as $item) {
			$row = array(
				'id' 	   => $item['emailtemplate_shortcode_id'],
				'code' 	   => $item['emailtemplate_shortcode_code'],
				'type' 	   => $item['emailtemplate_shortcode_type'],
				'example'  => $item['emailtemplate_shortcode_example'],
				'url_edit'  => $this->url->link('module/emailtemplate/template_shortcode', 'token='.$this->session->data['token'].'&id='.$item['emailtemplate_shortcode_id']),
				'selected' => isset($this->request->post['selected']) && in_array($item['emailtemplate_shortcode_id'], $this->request->post['selected'])
			);

			$this->data['shortcodes'][] = $row;
		}

		$url = '';
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		if (isset($this->request->get['id'])) {
			$url .= '&id='.$this->request->get['id'];
		}

		$this->data['sort_code'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&sort=code' . $url);
		$this->data['sort_example'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&sort=example' . $url);

		$this->data['shortcodes_delete'] = $this->url->link('module/emailtemplate/delete_shortcode', 'token='.$this->session->data['token'] . '&id='.$this->request->get['id']);

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
	}

	/**
	 * Get template form
	 */
	private function _template_form(){
		$this->load->model('localisation/language');
		$this->load->model('localisation/order_status');
		$this->load->model('tool/image');
		$this->load->model('sale/customer_group');

		$this->data = array_merge($this->data, $this->load->language('module/emailtemplate'));

		$this->_messages();

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id=' . $this->request->get['id']);

			if($this->request->get['id'] != 1){
				$this->data['action_delete'] = $this->url->link('module/emailtemplate/delete_template', 'token='.$this->session->data['token'] . '&id='.$this->request->get['id']);
			}

			$emailtemplate = $this->model_module_emailtemplate->getTemplate($this->request->get['id'], 0);

			if(!$emailtemplate){
				$this->redirect($this->url->link('module/emailtemplate', 'token='.$this->session->data['token'], 'SSL'));
			}

			$emailtemplate['descriptions'] = array();

			$result = $this->model_module_emailtemplate->getTemplateDescription(array('emailtemplate_id' => $emailtemplate['emailtemplate_id']));

			foreach($result as $row){
				$emailtemplate['descriptions'][$row['language_id']] = $row;
			}

			$config = $this->data['emailtemplate_config'] = $this->model_module_emailtemplate->getConfig(array(
				'store_id' => $emailtemplate['store_id']
			));

			if($emailtemplate['emailtemplate_default']){
				$this->data['default_emailtemplate_id'] = $emailtemplate['emailtemplate_id'];
			} else {
				$templates = $this->model_module_emailtemplate->getTemplates(array(
					'emailtemplate_key' => $emailtemplate['emailtemplate_key'],
					'emailtemplate_default' => 1,
					'limit' => 1,
					'start' => 0
				));
				if($templates){
					$this->data['default_emailtemplate_id'] = $templates[0]['emailtemplate_id'];
				}

				$this->data['template_default_url'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id='.$this->data['default_emailtemplate_id']);
			}

			$this->_template_list(array(
				'not_emailtemplate_id' => $emailtemplate['emailtemplate_id'],
				'key' => $emailtemplate['emailtemplate_key'],
				'default' => ''
			));

			$this->_shortcodes_list();

			if(!empty($this->data['shortcodes'])){
				$this->data['shortcodes_data'] = array();
				foreach($this->data['shortcodes'] as $row){
					$this->data['shortcodes_data'][$row['code']] = $row['example'];
				}
			}

			$this->data['new_template'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&key=' . $emailtemplate['emailtemplate_key']);

			$this->_breadcrumbs(array('heading_template' => array(
				'link' => 'module/emailtemplate/template',
				'params' => '&id='.$this->request->get['id']
			)));
		} else {
			$this->data['action'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token']);

			$this->data['insertMode'] = true;

			$config = $this->data['emailtemplate_config'] = $this->model_module_emailtemplate->getConfig(1);

			$this->data['emailtemplate_keys'] = $this->model_module_emailtemplate->getTemplateKeys();

			$this->_breadcrumbs(array('heading_template' => array(
				'link' => 'module/emailtemplate/template'
			)));
		}

		$title = '';
		if(!empty($emailtemplate)){
			if($emailtemplate['emailtemplate_label']){
				$title .= $emailtemplate['emailtemplate_label'] . ' - ';
			} elseif($emailtemplate['emailtemplate_key']){
				$title .= $emailtemplate['emailtemplate_key'] . ' - ';
			}

			$title .= $this->language->get('heading_template');
		} else {
			$title = $this->language->get('heading_template_create');
		}
		$this->_setTitle($title);

		$this->data['cancel'] = $this->url->link('module/emailtemplate', 'token='.$this->session->data['token']);
		$this->data['css_url'] = $this->url->link('module/emailtemplate/css', 'token='.$this->session->data['token']);

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->data['emailtemplate'] = array();
		$cols = EmailTemplateDAO::describe();
		foreach($cols as $col => $type){
			$key = (strpos($col, 'emailtemplate_') === 0 && substr($col, -3) != '_id') ? substr($col, 14) : $col;
			if(isset($this->request->post[$col])) {
				$this->data['emailtemplate'][$key] = $this->request->post[$col];
			} elseif (isset($emailtemplate[$col])) {
				$this->data['emailtemplate'][$key] = $emailtemplate[$col];
			} else {
				$this->data['emailtemplate'][$key] = '';
			}
		}

		if(isset($this->request->post['emailtemplate_key_select'])) {
			$this->data['emailtemplate']['key_select'] = $this->request->post['emailtemplate_key_select'];
		} elseif(isset($this->request->get['key'])) {
			$this->data['emailtemplate']['key_select'] = $this->request->get['key'];
		} else {
			$this->data['emailtemplate']['key_select'] = '';
		}

		$descriptionCols = EmailTemplateDescriptionDAO::describe();
		$this->data['emailtemplate_description'] = array();

		foreach($this->data['languages'] as &$language){
			$row = array();

			if($language['language_id'] == $this->config->get('config_language_id')){
				$language['default'] = 1;
			} else {
				$language['default'] = 0;
			}

			foreach($descriptionCols as $col => $type){
				$key = (strpos($col, 'emailtemplate_description_') === 0) ? substr($col, 26) : $col;

				if(isset($this->request->post[$col][$language['language_id']])) {
					$value = $this->request->post[$col][$language['language_id']];
				} elseif (isset($emailtemplate['descriptions'][$language['language_id']][$col])) {
					$value = $emailtemplate['descriptions'][$language['language_id']][$col];
				} else {
					$value = '';
				}

				$row[$key] = $value;
			}

			$this->data['emailtemplate_description'][$language['language_id']] = $row;
		}
		unset($language);

		$modified = strtotime($this->data['emailtemplate']['modified']);
		if(date('Ymd') == date('Ymd', $modified)){
			$this->data['emailtemplate']['modified'] = date($this->language->get('time_format'), $modified);
		} else {
			$this->data['emailtemplate']['modified'] = date($this->language->get('date_format_short'), $modified);
		}

		$this->data['emailtemplate_files'] = $this->model_module_emailtemplate->getTemplateFiles($config['emailtemplate_config_theme']);

		if(substr($this->data['emailtemplate']['key'], 0, 6) == 'admin.'){
			$this->data['emailtemplate_template_path'] = 'admin/view/template/mail/';
		} else {
			$this->data['emailtemplate_template_path'] = 'catalog/view/theme/' . $config['emailtemplate_config_theme'] . '/template/mail/';
		}

		$this->data['emailtemplate_configs'] = $this->model_module_emailtemplate->getConfigs(array(), true, true);

		if(isset($emailtemplate['emailtemplate_id'])){
			$this->data['emailtemplate_shortcodes'] = $this->model_module_emailtemplate->getTemplateShortcodes(array('emailtemplate_id' => $emailtemplate['emailtemplate_id']), true);
		}

		$this->data['stores'] = $this->model_module_emailtemplate->getStores();
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$key = isset($this->request->get['key']) ? $this->request->get['key'] : $this->data['emailtemplate']['key'];
		switch($key){
			case 'admin.order_update':
			case 'order.customer':
			case 'order.customer_update':
				$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			break;
		}
	}

	/**
	 * Get template shortcode form
	 */
	private function _template_shortcode_form(){
		$this->data = array_merge($this->data, $this->load->language('module/emailtemplate'));

		$this->_messages();

		$this->data['token'] = $this->session->data['token'];

		$this->data['action'] = $this->url->link('module/emailtemplate/template_shortcode', 'token='.$this->session->data['token'] . '&id=' . $this->request->get['id']);

		$shortcodes = $this->model_module_emailtemplate->getTemplateShortcodes(array('emailtemplate_shortcode_id' => $this->request->get['id']));
		$shortcode = $shortcodes[0];

		$this->_breadcrumbs(array('heading_template_shortcode' => array(
			'link' => 'module/emailtemplate/template_shortcode',
			'params' => '&id='.$this->request->get['id']
		)));

		$this->_setTitle($this->language->get('heading_template_shortcode') . ' - ' . $shortcode['emailtemplate_shortcode_code']);

		$this->data['cancel'] = $this->url->link('module/emailtemplate/template', 'token='.$this->session->data['token'] . '&id=' . $shortcode['emailtemplate_id']);

		$this->data['shortcode'] = array();
		$cols = EmailTemplateShortCodesDAO::describe();
		foreach($cols as $col => $type){
			$key = (strpos($col, 'emailtemplate_shortcode_') === 0 && substr($col, -3) != '_id') ? substr($col, 24) : $col;
			if(isset($this->request->post[$col])) {
				$this->data['shortcode'][$key] = $this->request->post[$col];
			} elseif (isset($shortcode[$col])) {
				$this->data['shortcode'][$key] = $shortcode[$col];
			} else {
				$this->data['shortcode'][$key] = '';
			}
		}
	}

	/**
	 * Populates $this->data with error_* keys using data from $this->error
	 */
	private function _messages(){
		# Attention
		if (isset($this->session->data['attention'])) {
			$this->data['error_attention'] = $this->session->data['attention'];
			unset($this->session->data['attention']);
		} else {
			$this->data['error_attention'] = '';
		}

		# Error
		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$this->data['error_warning'] = '';
		}
		foreach ($this->error as $key => $val) {
			$this->data["error_{$key}"] = $val;
		}

		# Success
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
	}

	/**
	 * Populates breadcrumbs array for $this->data
	 */
	private function _breadcrumbs($crumbs = array(), $home = true){
		$bc = array();
		$bc_map = array(
			'text_home' => array('link' => 'common/home', 'params' => '', 'separator' => false),
			'text_module' => array('link' => 'extension/module', 'params' => '', 'separator' => ' :: ')
		);

		if($home){
			$bc_map = array_merge($bc_map, array('heading' => array('link' => 'module/emailtemplate', 'params' => $this->params, 'separator' => ' :: ')));
		}
		$bc_map = array_merge($bc_map, $crumbs);

		foreach ($bc_map as $name => $item) {
			$bc[]= array(
				'text'      => $this->language->get($name),
				'href'      => $this->url->link($item['link'], 'token='.$this->session->data['token'] . (isset($item['params']) ? $item['params'] : '')),
				'separator' => isset($item['separator']) ? $item['separator'] : ' :: '
			);
		}
   		$this->data['breadcrumbs'] = $bc;
	}

	/**
	 * Validate form data
	 */
	private function _validateConfig($data){
		if (!$this->user->hasPermission('modify', 'module/emailtemplate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		# Check required fields
		foreach(array(
			'emailtemplate_config_theme',
			'emailtemplate_config_name'
		) as $field){
			if (!isset($data[$field]) || $data[$field] == '') {
				$this->error[$field] = $this->language->get('error_required');
			}
		}

		# Step 1 failed
		if ($this->error) {
			if ($this->error && !isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}
			return false;
		}

		# Check directory and images exist
		$dir = DIR_CATALOG . 'view/theme/' . $data['emailtemplate_config_theme'] . '/template/mail/_main.tpl';
		if (!file_exists($dir)){
			$this->error['emailtemplate_config_theme'] = sprintf($this->language->get('error_theme'), $dir);
		}

		# Validate logo contains space or special character
		$logo = $data['emailtemplate_config_logo'];
		if ($logo && preg_match('/[^\w.-]/', basename($logo))){
			$this->error['emailtemplate_config_logo'] = sprintf($this->language->get('error_logo_filename'), $logo);
		}

		if ($logo && (!$data['emailtemplate_config_logo_width'] || !$data['emailtemplate_config_logo_height'])) {
			$this->error['emailtemplate_config_logo_width'] = $this->language->get('error_required');
		}


		# Step 2 failed
		if ($this->error) {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Validate template form data
	 */
	private function _validateTemplate($data){
		if (!$this->user->hasPermission('modify', 'module/emailtemplate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		// #1 key empty and select no set #2 either empty
		if (($data['emailtemplate_key'] == '' && !isset($data['emailtemplate_key_select'])) || ($data['emailtemplate_key'] == '' && empty($data['emailtemplate_key_select']))) {
			$this->error['emailtemplate_key'] = $this->language->get('error_required');
		} elseif($data['emailtemplate_key'] != '' && !empty($data['emailtemplate_key_select'])) {
			$this->error['emailtemplate_key'] = $this->language->get('error_key_select');
		}

		if (!isset($data['emailtemplate_label']) || $data['emailtemplate_label'] == '') {
			$this->error['emailtemplate_label'] = $this->language->get('error_required');
		}

		if (empty($data['emailtemplate_status'])) {
			$this->error['emailtemplate_status'] = $this->language->get('error_required');
		}

		if (isset($data['emailtemplate_mail_attachment']) && $data['emailtemplate_mail_attachment']) {
			$dir = substr(DIR_SYSTEM, 0, -7); // remove 'system/'
			if (!file_exists($dir.$data['emailtemplate_mail_attachment'])) {
				$this->error['emailtemplate_mail_attachment'] = sprintf($this->language->get('error_file_not_exists'), $dir.$data['emailtemplate_mail_attachment']);
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Validate template shortcode form data
	 */
	private function _validateTemplateShortcode($data){
		if (!$this->user->hasPermission('modify', 'module/emailtemplate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($data['emailtemplate_shortcode_code']) || $data['emailtemplate_shortcode_code'] == '') {
			$this->error['emailtemplate_shortcode_code'] = $this->language->get('error_required');
		}

		if (!isset($data['emailtemplate_shortcode_type']) || $data['emailtemplate_shortcode_type'] == '') {
			$this->error['emailtemplate_shortcode_type'] = $this->language->get('error_required');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Vqmod Action:
	 * - Default clear cache
	 * - Install/Uninstall vqmod files for extension
	 * - this should only return true if the vqmod files are successfully renamed
	 *
	 * @param string $action = install/uninstall
	 * @return bool
	 */
	private function _vqmod($action = ''){
		if(!class_exists('VQMod')) {
			die('Error: check vqmod installed for admin/index.php, vqcache files are getting generated &amp; upgrade vqmod version greater than: 2.4.0');
		}

		/* $classVQMod = new ReflectionClass('VQMod');
		if(!$classVQMod->isAbstract()){
			$this->session->data['error'] = 'Warning: old vqmod version detected, upgrade to version (greater than: 2.4.0)';
		} */

		$vqmod_path = str_replace("/system/", "/vqmod/", DIR_SYSTEM);
		$vqmod_xml_path = $vqmod_path . "xml/";

		$files = glob($vqmod_path.'vqcache/vq*');
		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					@unlink($file);
					clearstatcache();
				}
			}
		}

		if (file_exists($vqmod_path.'mods.cache')) {
			@unlink($vqmod_path.'mods.cache');
		}

		if($action != ""){
			$return = false;

			foreach($this->vqmod_files as $file){
				switch ($action){
					case 'uninstall' :
						$from = '.xml';
						$to = '.xml_';
						break;

					case 'install' :
						$from = '.xml_';
						$to = '.xml';
						break;
				}

				if (file_exists($vqmod_xml_path.$file.$from)) {
					rename($vqmod_xml_path.$file.$from, $vqmod_xml_path.$file.$to);
					$return = true;
				}
			}

			return $return;
		} else {
			return true;
		}
	}

	/**
	 * Output Page
	 *
	 * @param string $template - template file path
	 * @param array $children
	 */
	private function _setTitle($title = ''){
		if($title == ''){
			$title = $this->language->get('heading_title');
		} else {
			$title .= ' - ' . $this->language->get('heading_title');
		}

		$this->data['title'] = $title;

		$this->document->setTitle($title);

		return $this;
	}

	/**
	 * Output Page
	 *
	 * @param string $template - template file path
	 * @param array $children
	 */
	private function _output($template, $children = null){
		$this->template = $template;
		$this->children = (!is_null($children)) ? $children : array('common/header', 'common/footer',);
		$this->response->setOutput($this->render());
		return $this;
	}

	/**
	 * Pagination
	 *
	 * @param string $url
	 * @param int $page - current page number
	 * @param int $total - total rows count
	 */
	private function _renderPagination($url, $page, $total, $limit = null, $style = ''){
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->paging_style = $style;
		$pagination->page = $page;
		$pagination->limit = ($limit == null) ? $this->config->get('config_admin_limit') : $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $url;

		return $pagination->render();
	}

	/**
	 * Validate email address
	 */
	private function _validateEmailAddress($email){
		if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
			return false;
		}

		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
				return false;
			}
		}

		if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])){
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false;
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
					return false;
				}
			}
		}

		return true;
	}

	private function _link($link, $isAdmin = false){
		if($isAdmin){
			return $this->url->link($link, 'token='.$this->session->data['token']);
		} else {
			if($this->config->get('config_secure') && defined('HTTPS_SERVER') && defined('HTTPS_CATALOG')){
				return str_replace(HTTPS_SERVER, HTTPS_CATALOG, $this->url->link($link, '', 'SSL'));
			} else {
				return str_replace(HTTP_SERVER, HTTP_CATALOG, $this->url->link($link, '', 'SSL'));
			}
		}
	}

	private function _vqmodFileFiles(){
		$return = array();
		$dom = new DOMDocument('1.0', 'UTF-8');

		$systemPath = substr(DIR_SYSTEM, 0, -7);
		$systemPathLen = strlen($systemPath);
		$vqmodPath = $systemPath . 'vqmod/xml/';

		foreach($this->vqmod_files as $modFile){
			if(file_exists($vqmodPath.$modFile.'.xml')) {
				if(@$dom->load($vqmodPath.$modFile.'.xml')){

					$files = $dom->getElementsByTagName('file');
					foreach($files as $file){
						$path = $file->getAttribute('path') ? $file->getAttribute('path') : '';
						$fileFiles = explode(',', $file->getAttribute('name'));

						foreach($fileFiles as $filename){
							$filePath = $path . $filename;
							if(property_exists('VQMod', '_replaces') && !empty(VQMod::$_replaces)) {
								foreach(VQMod::$_replaces as $r){
									if(count($r) == 2){
										$filePath = preg_replace($r[0], $r[1], $filePath);
									}
								}
							}

							if(strpos($filePath, '*') !== false){
								foreach(glob($systemPath.$filePath) as $filePath){
									$return[] = substr($filePath, $systemPathLen);
								}
							} else {
								$return[] = $filePath;
							}
						}
					}
				} else {
					EmailTemplate::systemLog('VQMod TEST - DOM UNABLE TO LOAD: ' . $modFile);
				}
			} else {
				EmailTemplate::systemLog('VQMod TEST - FILE NOT FOUND: ' . $modFile);
			}
		}
		return $return;
	}

}
?>