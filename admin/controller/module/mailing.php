<?php
class ControllerModuleMailing extends Controller {

	public function index() {
		$this->load->model('setting/setting');
		$this->load->model('design/layout');

		$this->document->setTitle('Email рассылки');

		// Получаем рассылки
		$compaings = $this->db->query('SELECT DISTINCT(`campaign_id`), cecn.email_campaign_name  FROM `customer_email_campaigns` cec LEFT JOIN `customer_email_campaigns_names` cecn ON (cec.campaign_id = cecn.email_campaign_mailwizz_id) WHERE 1');
		$compaingsArray = array();
		foreach ($compaings->rows as $x) {
			$a = array();
			$a['name'] = $x['email_campaign_name'] ? $x['email_campaign_name'] : '&mdash;';
			$a['id'] = $x['campaign_id'];
			$a['url'] = $this->url->link('module/mailing/update', 'token=' . $this->session->data['token'].'&id='.$x['campaign_id']);
			$compaingsArray[] = $a;
		}
		$this->data['compaings'] = $compaingsArray;


		// if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		// 	$this->model_setting_setting->editSetting('pp_layout', $this->request->post);
		//
		// 	$this->session->data['success'] = $this->language->get('text_success');
		//
		// 	$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
		// }
		//
		// $this->data['heading_title'] = $this->language->get('heading_title');
		//
		// $this->data['text_enabled'] = $this->language->get('text_enabled');
		// $this->data['text_disabled'] = $this->language->get('text_disabled');
		// $this->data['text_content_top'] = $this->language->get('text_content_top');
		// $this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		// $this->data['text_column_left'] = $this->language->get('text_column_left');
		// $this->data['text_column_right'] = $this->language->get('text_column_right');
		//
		// $this->data['entry_layout'] = $this->language->get('entry_layout');
		// $this->data['entry_position'] = $this->language->get('entry_position');
		// $this->data['entry_status'] = $this->language->get('entry_status');
		// $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		//
		// $this->data['button_save'] = $this->language->get('button_save');
		// $this->data['button_cancel'] = $this->language->get('button_cancel');
		// $this->data['button_add_module'] = $this->language->get('button_add_module');
		// $this->data['button_remove'] = $this->language->get('button_remove');
		//
		// if (isset($this->error['warning'])) {
		// 	$this->data['error_warning'] = $this->error['warning'];
		// } else {
		// 	$this->data['error_warning'] = '';
		// }
		//
		// $this->data['breadcrumbs'] = array();
		//
		// $this->data['breadcrumbs'][] = array(
		// 	'text' => $this->language->get('text_home'),
		// 	'href' => $this->url->link('common/home', 'token=' . $this->session->data['token']),
		// 	'separator' => false
		// );
		//
		// $this->data['breadcrumbs'][] = array(
		// 	'text' => $this->language->get('text_module'),
		// 	'href' => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
		// 	'separator' => ' :: '
		// );
		//
		// $this->data['breadcrumbs'][] = array(
		// 	'text' => $this->language->get('heading_title'),
		// 	'href' => $this->url->link('module/pp_layout', 'token=' . $this->session->data['token']),
		// 	'separator' => ' :: '
		// );
		//
		// $this->data['action'] = $this->url->link('module/pp_layout', 'token=' . $this->session->data['token']);
		// $this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);
		// $this->data['token'] = $this->session->data['token'];
		//
		// $this->data['modules'] = array();
		//
		// if (isset($this->request->post['pp_layout_module'])) {
		// 	$this->data['modules'] = $this->request->post['pp_layout_module'];
		// } elseif ($this->config->get('pp_layout_module')) {
		// 	$this->data['modules'] = $this->config->get('pp_layout_module');
		// }
		//
		// $this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/mailing_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function update() {		
		$id = $this->request->get('id');
		$this->data['name'] = '';

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$name = $_POST['name'];
			$this->db->query("INSERT INTO `customer_email_campaigns_names`
				(email_campaign_mailwizz_id, email_campaign_name)
				VALUES ('" . $id  . "', '" . $this->db->escape($name) . "')
				ON DUPLICATE KEY UPDATE email_campaign_name = '".$this->db->escape($name)."'");
		}

		$r = $this->db->query('SELECT * FROM `customer_email_campaigns_names` WHERE `email_campaign_mailwizz_id` = "' . (int)$id . '"');
		if ($r) {
			$row = $r->row;
			if ($row) {
				$this->data['name'] = $row['email_campaign_name'];
			}
		}

		// Получаем данные рассылки
		$m = $this->db->query('SELECT * FROM `customer_email_campaigns` WHERE `campaign_id` = "'. (int)$id . '"');
		$tabsArray = array();
		$tabsCountArray = array();

		$compaings = $this->db->query('SELECT DISTINCT(`mail_status`) FROM `customer_email_campaigns` WHERE 1');
		foreach ($compaings->rows as $c) {
			$tabsCountArray[$c['mail_status']] = 0;
			$tabsArray[$c['mail_status']] = array('opened' => 0, 'not_opened' => 0, 'clicked' => 0, 'not_clicked' => 0);
		}


		foreach ($m->rows as $mr) {
			$tabsCountArray[$mr['mail_status']] += 1;
			// Открыто сообщение, и не открыто
			if ($mr['mail_opened']) {
				$tabsArray[$mr['mail_status']]['opened'] ++;
			} else {
				$tabsArray[$mr['mail_status']]['not_opened'] ++;
			}

			// Есть переход по ссыке, или нет
			if ($mr['mail_clicked']) {
				$tabsArray[$mr['mail_status']]['clicked'] ++;
			} else {
				$tabsArray[$mr['mail_status']]['not_clicked'] ++;
			}

		}

		$this->data['mailing_info'] = $tabsArray;
		$this->data['tabs_count_array'] = $tabsCountArray;



		$this->template = 'module/mailing_update.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}