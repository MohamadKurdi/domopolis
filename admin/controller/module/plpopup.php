<?php
class ControllerModuleplpopup extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/plpopup');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('plpopup', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('module/plpopup', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');

		$this->data['entry_plpopup_headtop'] = $this->language->get('entry_plpopup_headtop');
		$this->data['entry_plpopupheadtop'] = $this->language->get('entry_plpopupheadtop');
		$this->data['entry_plpopup_headtopsize'] = $this->language->get('entry_plpopup_headtopsize');
		$this->data['entry_plpopupheadtopsize'] = $this->language->get('entry_plpopupheadtopsize');
		$this->data['entry_plpopup_headtopcolor'] = $this->language->get('entry_plpopup_headtopcolor');
		$this->data['entry_plpopupheadtopcolor'] = $this->language->get('entry_plpopupheadtopcolor');
		$this->data['entry_plpopup_headtopfw'] = $this->language->get('entry_plpopup_headtopfw');
		$this->data['entry_plpopupheadtopfw'] = $this->language->get('entry_plpopupheadtopfw');
		$this->data['entry_plpopup_headtoptype'] = $this->language->get('entry_plpopup_headtoptype');
		$this->data['entry_plpopupheadtoptype'] = $this->language->get('entry_plpopupheadtoptype');
		$this->data['entry_plpopup_headtopleft'] = $this->language->get('entry_plpopup_headtopleft');
		$this->data['entry_plpopupheadtopleft'] = $this->language->get('entry_plpopupheadtopleft');
		$this->data['entry_plpopup_headtopheight'] = $this->language->get('entry_plpopup_headtopheight');
		$this->data['entry_plpopupheadtopheight'] = $this->language->get('entry_plpopupheadtopheight');
		$this->data['entry_plpopup_linktop'] = $this->language->get('entry_plpopup_linktop');
		$this->data['entry_plpopuplinktop'] = $this->language->get('entry_plpopuplinktop');
		
		$this->data['entry_plpopup_headtop2'] = $this->language->get('entry_plpopup_headtop2');
		$this->data['entry_plpopupheadtop2'] = $this->language->get('entry_plpopupheadtop2');
		$this->data['entry_plpopup_headtopsize2'] = $this->language->get('entry_plpopup_headtopsize2');
		$this->data['entry_plpopupheadtopsize2'] = $this->language->get('entry_plpopupheadtopsize2');
		$this->data['entry_plpopup_headtopcolor2'] = $this->language->get('entry_plpopup_headtopcolor2');
		$this->data['entry_plpopupheadtopcolor2'] = $this->language->get('entry_plpopupheadtopcolor2');
		$this->data['entry_plpopup_headtopfw2'] = $this->language->get('entry_plpopup_headtopfw2');
		$this->data['entry_plpopupheadtopfw2'] = $this->language->get('entry_plpopupheadtopfw2');
		$this->data['entry_plpopup_headtoptype2'] = $this->language->get('entry_plpopup_headtoptype2');
		$this->data['entry_plpopupheadtoptype2'] = $this->language->get('entry_plpopupheadtoptype2');
		$this->data['entry_plpopup_headtopheight2'] = $this->language->get('entry_plpopup_headtopheight2');
		$this->data['entry_plpopupheadtopheight2'] = $this->language->get('entry_plpopupheadtopheight2');
		$this->data['entry_plpopup_headtopleft2'] = $this->language->get('entry_plpopup_headtopleft2');
		$this->data['entry_plpopupheadtopleft2'] = $this->language->get('entry_plpopupheadtopleft2');
		$this->data['entry_plpopup_linktop2'] = $this->language->get('entry_plpopup_linktop2');
		$this->data['entry_plpopuplinktop2'] = $this->language->get('entry_plpopuplinktop2');
				
		$this->data['entry_plpopup_home'] = $this->language->get('entry_plpopup_home');
		$this->data['entry_plpopuphome'] = $this->language->get('entry_plpopuphome');
		$this->data['entry_plpopup_homesize'] = $this->language->get('entry_plpopup_homesize');
		$this->data['entry_plpopuphomesize'] = $this->language->get('entry_plpopuphomesize');
		$this->data['entry_plpopup_homecolor'] = $this->language->get('entry_plpopup_homecolor');
		$this->data['entry_plpopuphomecolor'] = $this->language->get('entry_plpopuphomecolor');
		$this->data['entry_plpopup_homefw'] = $this->language->get('entry_plpopup_homefw');
		$this->data['entry_plpopuphomefw'] = $this->language->get('entry_plpopuphomefw');
		$this->data['entry_plpopup_hometype'] = $this->language->get('entry_plpopup_hometype');
		$this->data['entry_plpopuphometype'] = $this->language->get('entry_plpopuphometype');
		$this->data['entry_plpopup_homeleft'] = $this->language->get('entry_plpopup_homeleft');
		$this->data['entry_plpopuphomeleft'] = $this->language->get('entry_plpopuphomeleft');
		$this->data['entry_plpopup_homeheight'] = $this->language->get('entry_plpopup_homeheight');
		$this->data['entry_plpopuphomeheight'] = $this->language->get('entry_plpopuphomeheight');
		$this->data['entry_plpopup_linkhome'] = $this->language->get('entry_plpopup_linkhome');
		$this->data['entry_plpopuplinkhome'] = $this->language->get('entry_plpopuplinkhome');
				
		$this->data['entry_plpopup_home2'] = $this->language->get('entry_plpopup_home2');
		$this->data['entry_plpopuphome2'] = $this->language->get('entry_plpopuphome2');
		$this->data['entry_plpopup_homesize2'] = $this->language->get('entry_plpopup_homesize2');
		$this->data['entry_plpopuphomesize2'] = $this->language->get('entry_plpopuphomesize2');
		$this->data['entry_plpopup_homecolor2'] = $this->language->get('entry_plpopup_homecolor2');
		$this->data['entry_plpopuphomecolor2'] = $this->language->get('entry_plpopuphomecolor2');
		$this->data['entry_plpopup_homefw2'] = $this->language->get('entry_plpopup_homefw2');
		$this->data['entry_plpopuphomefw2'] = $this->language->get('entry_plpopuphomefw2');
		$this->data['entry_plpopup_hometype2'] = $this->language->get('entry_plpopup_hometype2');
		$this->data['entry_plpopuphometype2'] = $this->language->get('entry_plpopuphometype2');
		$this->data['entry_plpopup_homeleft2'] = $this->language->get('entry_plpopup_homeleft2');
		$this->data['entry_plpopuphomeleft2'] = $this->language->get('entry_plpopuphomeleft2');
		$this->data['entry_plpopup_homeheight2'] = $this->language->get('entry_plpopup_homeheight2');
		$this->data['entry_plpopuphomeheight2'] = $this->language->get('entry_plpopuphomeheight2');
		$this->data['entry_plpopup_linkhome2'] = $this->language->get('entry_plpopup_linkhome2');
		$this->data['entry_plpopuplinkhome2'] = $this->language->get('entry_plpopuplinkhome2');
		
		$this->data['entry_plpopup_home3'] = $this->language->get('entry_plpopup_home3');
		$this->data['entry_plpopuphome3'] = $this->language->get('entry_plpopuphome3');
		$this->data['entry_plpopup_homesize3'] = $this->language->get('entry_plpopup_homesize3');
		$this->data['entry_plpopuphomesize3'] = $this->language->get('entry_plpopuphomesize3');
		$this->data['entry_plpopup_homecolor3'] = $this->language->get('entry_plpopup_homecolor3');
		$this->data['entry_plpopuphomecolor3'] = $this->language->get('entry_plpopuphomecolor3');
		$this->data['entry_plpopup_homefw3'] = $this->language->get('entry_plpopup_homefw3');
		$this->data['entry_plpopuphomefw3'] = $this->language->get('entry_plpopuphomefw3');
		$this->data['entry_plpopup_hometype3'] = $this->language->get('entry_plpopup_hometype3');
		$this->data['entry_plpopuphometype3'] = $this->language->get('entry_plpopuphometype3');
		$this->data['entry_plpopup_homeleft3'] = $this->language->get('entry_plpopup_homeleft3');
		$this->data['entry_plpopuphomeleft3'] = $this->language->get('entry_plpopuphomeleft3');
		$this->data['entry_plpopup_homeheight3'] = $this->language->get('entry_plpopup_homeheight3');
		$this->data['entry_plpopuphomeheight3'] = $this->language->get('entry_plpopuphomeheight3');
		$this->data['entry_plpopup_linkhome3'] = $this->language->get('entry_plpopup_linkhome3');
		$this->data['entry_plpopuplinkhome3'] = $this->language->get('entry_plpopuplinkhome3');		
		
		$this->data['entry_plpopup_home4'] = $this->language->get('entry_plpopup_home4');
		$this->data['entry_plpopuphome4'] = $this->language->get('entry_plpopuphome4');
		$this->data['entry_plpopup_homesize4'] = $this->language->get('entry_plpopup_homesize4');
		$this->data['entry_plpopuphomesize4'] = $this->language->get('entry_plpopuphomesize4');
		$this->data['entry_plpopup_homecolor4'] = $this->language->get('entry_plpopup_homecolor4');
		$this->data['entry_plpopuphomecolor4'] = $this->language->get('entry_plpopuphomecolor4');
		$this->data['entry_plpopup_homefw4'] = $this->language->get('entry_plpopup_homefw4');
		$this->data['entry_plpopuphomefw4'] = $this->language->get('entry_plpopuphomefw4');
		$this->data['entry_plpopup_hometype4'] = $this->language->get('entry_plpopup_hometype4');
		$this->data['entry_plpopuphometype4'] = $this->language->get('entry_plpopuphometype4');
		$this->data['entry_plpopup_homeleft4'] = $this->language->get('entry_plpopup_homeleft4');
		$this->data['entry_plpopuphomeleft4'] = $this->language->get('entry_plpopuphomeleft4');
		$this->data['entry_plpopup_homeheight4'] = $this->language->get('entry_plpopup_homeheight4');
		$this->data['entry_plpopuphomeheight4'] = $this->language->get('entry_plpopuphomeheight4');
		$this->data['entry_plpopup_linkhome4'] = $this->language->get('entry_plpopup_linkhome4');
		$this->data['entry_plpopuplinkhome4'] = $this->language->get('entry_plpopuplinkhome4');
		
		$this->data['entry_plpopup_footer'] = $this->language->get('entry_plpopup_footer');
		$this->data['entry_plpopupfooter'] = $this->language->get('entry_plpopupfooter');
		$this->data['entry_plpopup_footersize'] = $this->language->get('entry_plpopup_footersize');
		$this->data['entry_plpopupfootersize'] = $this->language->get('entry_plpopupfootersize');
		$this->data['entry_plpopup_footercolor'] = $this->language->get('entry_plpopup_footercolor');
		$this->data['entry_plpopupfootercolor'] = $this->language->get('entry_plpopupfootercolor');
		$this->data['entry_plpopup_footerfw'] = $this->language->get('entry_plpopup_footerfw');
		$this->data['entry_plpopupfooterfw'] = $this->language->get('entry_plpopupfooterfw');
		$this->data['entry_plpopup_footertype'] = $this->language->get('entry_plpopup_footertype');
		$this->data['entry_plpopupfootertype'] = $this->language->get('entry_plpopupfootertype');
		$this->data['entry_plpopup_footerleft'] = $this->language->get('entry_plpopup_footerleft');
		$this->data['entry_plpopupfooterleft'] = $this->language->get('entry_plpopupfooterleft');
		$this->data['entry_plpopup_footerheight'] = $this->language->get('entry_plpopup_footerheight');
		$this->data['entry_plpopupfooterheight'] = $this->language->get('entry_plpopupfooterheight');
		$this->data['entry_plpopup_linkfooter'] = $this->language->get('entry_plpopup_linkfooter');
		$this->data['entry_plpopuplinkfooter'] = $this->language->get('entry_plpopuplinkfooter');
		
		$this->data['entry_plpopup_footer2'] = $this->language->get('entry_plpopup_footer2');
		$this->data['entry_plpopupfooter2'] = $this->language->get('entry_plpopupfooter2');
		$this->data['entry_plpopup_footersize2'] = $this->language->get('entry_plpopup_footersize2');
		$this->data['entry_plpopupfootersize2'] = $this->language->get('entry_plpopupfootersize2');
		$this->data['entry_plpopup_footercolor2'] = $this->language->get('entry_plpopup_footercolor2');
		$this->data['entry_plpopupfootercolor2'] = $this->language->get('entry_plpopupfootercolor2');
		$this->data['entry_plpopup_footerfw2'] = $this->language->get('entry_plpopup_footerfw2');
		$this->data['entry_plpopupfooterfw2'] = $this->language->get('entry_plpopupfooterfw2');
		$this->data['entry_plpopup_footertype2'] = $this->language->get('entry_plpopup_footertype2');
		$this->data['entry_plpopupfootertype2'] = $this->language->get('entry_plpopupfootertype2');
		$this->data['entry_plpopup_footerheight2'] = $this->language->get('entry_plpopup_footerheight2');
		$this->data['entry_plpopupfooterheight2'] = $this->language->get('entry_plpopupfooterheight2');
		$this->data['entry_plpopup_footerleft2'] = $this->language->get('entry_plpopup_footerleft2');
		$this->data['entry_plpopupfooterleft2'] = $this->language->get('entry_plpopupfooterleft2');
		$this->data['entry_plpopup_linkfooter2'] = $this->language->get('entry_plpopup_linkfooter2');
		$this->data['entry_plpopuplinkfooter2'] = $this->language->get('entry_plpopuplinkfooter2');
				
		$this->data['entry_plpopup_style'] = $this->language->get('entry_plpopup_style');
		$this->data['entry_plpopupstyle'] = $this->language->get('entry_plpopupstyle');
		$this->data['entry_plpopup_cookie'] = $this->language->get('entry_plpopup_cookie');
		$this->data['entry_plpopupcookie'] = $this->language->get('entry_plpopupcookie');
		$this->data['entry_plpopup_sr'] = $this->language->get('entry_plpopup_sr');
		$this->data['entry_plpopupsr'] = $this->language->get('entry_plpopupsr');
		$this->data['entry_plpopup_tm'] = $this->language->get('entry_plpopup_tm');
		$this->data['entry_plpopuptm'] = $this->language->get('entry_plpopuptm');
		$this->data['entry_plpopup_pic'] = $this->language->get('entry_plpopup_pic');
		$this->data['entry_plpopuppic'] = $this->language->get('entry_plpopuppic');
		$this->data['entry_plpopup_am'] = $this->language->get('entry_plpopup_am');
		$this->data['entry_plpopupam'] = $this->language->get('entry_plpopupam');
		$this->data['entry_plpopup_amo'] = $this->language->get('entry_plpopup_amo');
		$this->data['entry_plpopupamo'] = $this->language->get('entry_plpopupamo');
		$this->data['entry_plpopup_amw'] = $this->language->get('entry_plpopup_amw');
		$this->data['entry_plpopupamw'] = $this->language->get('entry_plpopupamw');
		$this->data['entry_plpopup_amh'] = $this->language->get('entry_plpopup_amh');
		$this->data['entry_plpopupamh'] = $this->language->get('entry_plpopupamh');
		$this->data['entry_plpopup_bg'] = $this->language->get('entry_plpopup_bg');
		$this->data['entry_plpopupbg'] = $this->language->get('entry_plpopupbg');
		$this->data['plpopup_heading_title'] = $this->language->get('plpopup_heading_title');
		$this->data['on_off_text'] = $this->language->get('on_off_text');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/plpopup', 'token=' . $this->session->data['token']),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/plpopup', 'token=' . $this->session->data['token']);
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);
		
		$this->data['config_plpopup_headtop'] = isset($this->request->post['config_plpopup_headtop']) ? $this->request->post['config_plpopup_headtop'] : $this->config->get('config_plpopup_headtop');
		$this->data['config_plpopupheadtop'] = isset($this->request->post['config_plpopupheadtop']) ? $this->request->post['config_plpopupheadtop'] : $this->config->get('config_plpopupheadtop');
		$this->data['config_plpopup_headtopsize'] = isset($this->request->post['config_plpopup_headtopsize']) ? $this->request->post['config_plpopup_headtopsize'] : $this->config->get('config_plpopup_headtopsize');
		$this->data['config_plpopupheadtopsize'] = isset($this->request->post['config_plpopupheadtopsize']) ? $this->request->post['config_plpopupheadtopsize'] : $this->config->get('config_plpopupheadtopsize');
		$this->data['config_plpopup_headtopcolor'] = isset($this->request->post['config_plpopup_headtopcolor']) ? $this->request->post['config_plpopup_headtopcolor'] : $this->config->get('config_plpopup_headtopcolor');
		$this->data['config_plpopupheadtopcolor'] = isset($this->request->post['config_plpopupheadtopcolor']) ? $this->request->post['config_plpopupheadtopcolor'] : $this->config->get('config_plpopupheadtopcolor');
		$this->data['config_plpopup_headtopfw'] = isset($this->request->post['config_plpopup_headtopfw']) ? $this->request->post['config_plpopup_headtopfw'] : $this->config->get('config_plpopup_headtopfw');
		$this->data['config_plpopupheadtopfw'] = isset($this->request->post['config_plpopupheadtopfw']) ? $this->request->post['config_plpopupheadtopfw'] : $this->config->get('config_plpopupheadtopfw');
		$this->data['config_plpopup_headtoptype'] = isset($this->request->post['config_plpopup_headtoptype']) ? $this->request->post['config_plpopup_headtoptype'] : $this->config->get('config_plpopup_headtoptype');
		$this->data['config_plpopupheadtoptype'] = isset($this->request->post['config_plpopupheadtoptype']) ? $this->request->post['config_plpopupheadtoptype'] : $this->config->get('config_plpopupheadtoptype');
		$this->data['config_plpopup_headtopleft'] = isset($this->request->post['config_plpopup_headtopleft']) ? $this->request->post['config_plpopup_headtopleft'] : $this->config->get('config_plpopup_headtopleft');
		$this->data['config_plpopupheadtopleft'] = isset($this->request->post['config_plpopupheadtopleft']) ? $this->request->post['config_plpopupheadtopleft'] : $this->config->get('config_plpopupheadtopleft');
		$this->data['config_plpopup_headtopheight'] = isset($this->request->post['config_plpopup_headtopheight']) ? $this->request->post['config_plpopup_headtopheight'] : $this->config->get('config_plpopup_headtopheight');
		$this->data['config_plpopupheadtopheight'] = isset($this->request->post['config_plpopupheadtopheight']) ? $this->request->post['config_plpopupheadtopheight'] : $this->config->get('config_plpopupheadtopheight');
		$this->data['config_plpopup_linktop'] = isset($this->request->post['config_plpopup_linktop']) ? $this->request->post['config_plpopup_linktop'] : $this->config->get('config_plpopup_linktop');
		$this->data['config_plpopuplinktop'] = isset($this->request->post['config_plpopuplinktop']) ? $this->request->post['config_plpopuplinktop'] : $this->config->get('config_plpopuplinktop');
		if (isset($this->request->post['on_offlt'])) {
					$this->data['on_offlt'] = $this->request->post['on_offlt'];
				} else {
					$this->data['on_offlt'] = $this->config->get('on_offlt');
				}
				
		$this->data['config_plpopup_headtop2'] = isset($this->request->post['config_plpopup_headtop2']) ? $this->request->post['config_plpopup_headtop2'] : $this->config->get('config_plpopup_headtop2');
		$this->data['config_plpopupheadtop2'] = isset($this->request->post['config_plpopupheadtop2']) ? $this->request->post['config_plpopupheadtop2'] : $this->config->get('config_plpopupheadtop2');
		$this->data['config_plpopup_headtopsize2'] = isset($this->request->post['config_plpopup_headtopsize2']) ? $this->request->post['config_plpopup_headtopsize2'] : $this->config->get('config_plpopup_headtopsize2');
		$this->data['config_plpopupheadtopsize2'] = isset($this->request->post['config_plpopupheadtopsize2']) ? $this->request->post['config_plpopupheadtopsize2'] : $this->config->get('config_plpopupheadtopsize2');
		$this->data['config_plpopup_headtopcolor2'] = isset($this->request->post['config_plpopup_headtopcolor2']) ? $this->request->post['config_plpopup_headtopcolor2'] : $this->config->get('config_plpopup_headtopcolor2');
		$this->data['config_plpopupheadtopcolor2'] = isset($this->request->post['config_plpopupheadtopcolor2']) ? $this->request->post['config_plpopupheadtopcolor2'] : $this->config->get('config_plpopupheadtopcolor2');
		$this->data['config_plpopup_headtopfw2'] = isset($this->request->post['config_plpopup_headtopfw2']) ? $this->request->post['config_plpopup_headtopfw2'] : $this->config->get('config_plpopup_headtopfw2');
		$this->data['config_plpopupheadtopfw2'] = isset($this->request->post['config_plpopupheadtopfw2']) ? $this->request->post['config_plpopupheadtopfw2'] : $this->config->get('config_plpopupheadtopfw2');
		$this->data['config_plpopup_headtoptype2'] = isset($this->request->post['config_plpopup_headtoptype2']) ? $this->request->post['config_plpopup_headtoptype2'] : $this->config->get('config_plpopup_headtoptype2');
		$this->data['config_plpopupheadtoptype2'] = isset($this->request->post['config_plpopupheadtoptype2']) ? $this->request->post['config_plpopupheadtoptype2'] : $this->config->get('config_plpopupheadtoptype2');
		$this->data['config_plpopup_headtopleft2'] = isset($this->request->post['config_plpopup_headtopleft2']) ? $this->request->post['config_plpopup_headtopleft2'] : $this->config->get('config_plpopup_headtopleft2');
		$this->data['config_plpopupheadtopleft2'] = isset($this->request->post['config_plpopupheadtopleft2']) ? $this->request->post['config_plpopupheadtopleft2'] : $this->config->get('config_plpopupheadtopleft2');
		$this->data['config_plpopup_headtopheight2'] = isset($this->request->post['config_plpopup_headtopheight2']) ? $this->request->post['config_plpopup_headtopheight2'] : $this->config->get('config_plpopup_headtopheight2');
		$this->data['config_plpopupheadtopheight2'] = isset($this->request->post['config_plpopupheadtopheight2']) ? $this->request->post['config_plpopupheadtopheight2'] : $this->config->get('config_plpopupheadtopheight2');
		$this->data['config_plpopup_linktop2'] = isset($this->request->post['config_plpopup_linktop2']) ? $this->request->post['config_plpopup_linktop2'] : $this->config->get('config_plpopup_linktop2');
		$this->data['config_plpopuplinktop2'] = isset($this->request->post['config_plpopuplinktop2']) ? $this->request->post['config_plpopuplinktop2'] : $this->config->get('config_plpopuplinktop2');
		if (isset($this->request->post['on_offlt2'])) {
					$this->data['on_offlt2'] = $this->request->post['on_offlt2'];
				} else {
					$this->data['on_offlt2'] = $this->config->get('on_offlt2');
				}
				
		$this->data['config_plpopup_home'] = isset($this->request->post['config_plpopup_home']) ? $this->request->post['config_plpopup_home'] : $this->config->get('config_plpopup_home');
		$this->data['config_plpopuphome'] = isset($this->request->post['config_plpopuphome']) ? $this->request->post['config_plpopuphome'] : $this->config->get('config_plpopuphome');
		$this->data['config_plpopup_homesize'] = isset($this->request->post['config_plpopup_homesize']) ? $this->request->post['config_plpopup_homesize'] : $this->config->get('config_plpopup_homesize');
		$this->data['config_plpopuphomesize'] = isset($this->request->post['config_plpopuphomesize']) ? $this->request->post['config_plpopuphomesize'] : $this->config->get('config_plpopuphomesize');
		$this->data['config_plpopup_homecolor'] = isset($this->request->post['config_plpopup_homecolor']) ? $this->request->post['config_plpopup_homecolor'] : $this->config->get('config_plpopup_homecolor');
		$this->data['config_plpopuphomecolor'] = isset($this->request->post['config_plpopuphomecolor']) ? $this->request->post['config_plpopuphhomecolor'] : $this->config->get('config_plpopuphomecolor');
		$this->data['config_plpopup_homefw'] = isset($this->request->post['config_plpopup_homefw']) ? $this->request->post['config_plpopup_homefw'] : $this->config->get('config_plpopup_homefw');
		$this->data['config_plpopuphomefw'] = isset($this->request->post['config_plpopuphomefw']) ? $this->request->post['config_plpopuphomefw'] : $this->config->get('config_plpopuphomefw');
		$this->data['config_plpopup_hometype'] = isset($this->request->post['config_plpopup_hometype']) ? $this->request->post['config_plpopup_hometype'] : $this->config->get('config_plpopup_hometype');
		$this->data['config_plpopuphometype'] = isset($this->request->post['config_plpopuphometype']) ? $this->request->post['config_plpopuphhometype'] : $this->config->get('config_plpopuphometype');
		$this->data['config_plpopup_homeleft'] = isset($this->request->post['config_plpopup_homeleft']) ? $this->request->post['config_plpopup_homeleft'] : $this->config->get('config_plpopup_homeleft');
		$this->data['config_plpopuphomeleft'] = isset($this->request->post['config_plpopuphomeleft']) ? $this->request->post['config_plpopuphomeleft'] : $this->config->get('config_plpopuphomeleft');
		$this->data['config_plpopup_homeheight'] = isset($this->request->post['config_plpopup_homeheight']) ? $this->request->post['config_plpopup_homeheight'] : $this->config->get('config_plpopup_homeheight');
		$this->data['config_plpopuphomeheight'] = isset($this->request->post['config_plpopuphomeheight']) ? $this->request->post['config_plpopuphhomeheight'] : $this->config->get('config_plpopuphomeheight');
		$this->data['config_plpopup_linkhome'] = isset($this->request->post['config_plpopup_linkhome']) ? $this->request->post['config_plpopup_linkhome'] : $this->config->get('config_plpopup_linkhome');
		$this->data['config_plpopuplinkhome'] = isset($this->request->post['config_plpopuplinkhome']) ? $this->request->post['config_plpopuplinkhome'] : $this->config->get('config_plpopuplinkhome');
		if (isset($this->request->post['on_offlh'])) {
					$this->data['on_offlh'] = $this->request->post['on_offlh'];
				} else {
					$this->data['on_offlh'] = $this->config->get('on_offlh');
				}
							
		$this->data['config_plpopup_home2'] = isset($this->request->post['config_plpopup_home2']) ? $this->request->post['config_plpopup_home2'] : $this->config->get('config_plpopup_home2');
		$this->data['config_plpopuphome2'] = isset($this->request->post['config_plpopuphome2']) ? $this->request->post['config_plpopuphome2'] : $this->config->get('config_plpopuphome2');
		$this->data['config_plpopup_homesize2'] = isset($this->request->post['config_plpopup_homesize2']) ? $this->request->post['config_plpopup_homesize2'] : $this->config->get('config_plpopup_homesize2');
		$this->data['config_plpopuphomesize2'] = isset($this->request->post['config_plpopuphomesize2']) ? $this->request->post['config_plpopuphomesize2'] : $this->config->get('config_plpopuphhomesize2');
		$this->data['config_plpopup_homecolor2'] = isset($this->request->post['config_plpopup_homecolor2']) ? $this->request->post['config_plpopup_homecolor2'] : $this->config->get('config_plpopup_homecolor2');
		$this->data['config_plpopuphomecolor2'] = isset($this->request->post['config_plpopuphomecolor2']) ? $this->request->post['config_plpopuphomecolor2'] : $this->config->get('config_plpopuphomecolor2');
		$this->data['config_plpopup_homefw2'] = isset($this->request->post['config_plpopup_homefw2']) ? $this->request->post['config_plpopup_homefw2'] : $this->config->get('config_plpopup_homefw2');
		$this->data['config_plpopuphomefw2'] = isset($this->request->post['config_plpopuphomefw2']) ? $this->request->post['config_plpopuphomefw2'] : $this->config->get('config_plpopuphhomefw2');
		$this->data['config_plpopup_hometype2'] = isset($this->request->post['config_plpopup_hometype2']) ? $this->request->post['config_plpopup_hometype2'] : $this->config->get('config_plpopup_hometype2');
		$this->data['config_plpopuphometype2'] = isset($this->request->post['config_plpopuphometype2']) ? $this->request->post['config_plpopuphometype2'] : $this->config->get('config_plpopuphometype2');
		$this->data['config_plpopup_homeleft2'] = isset($this->request->post['config_plpopup_homeleft2']) ? $this->request->post['config_plpopup_homeleft2'] : $this->config->get('config_plpopup_homeleft2');
		$this->data['config_plpopuphomeleft2'] = isset($this->request->post['config_plpopuphomeleft2']) ? $this->request->post['config_plpopuphomeleft2'] : $this->config->get('config_plpopuphhomeleft2');
		$this->data['config_plpopup_homeheight2'] = isset($this->request->post['config_plpopup_homeheight2']) ? $this->request->post['config_plpopup_homeheight2'] : $this->config->get('config_plpopup_homeheight2');
		$this->data['config_plpopuphomeheight2'] = isset($this->request->post['config_plpopuphomeheight2']) ? $this->request->post['config_plpopuphomeheight2'] : $this->config->get('config_plpopuphomeheight2');
		$this->data['config_plpopup_linkhome2'] = isset($this->request->post['config_plpopup_linkhome2']) ? $this->request->post['config_plpopup_linkhome2'] : $this->config->get('config_plpopup_linkhome2');
		$this->data['config_plpopuplinkhome2'] = isset($this->request->post['config_plpopuplinkhome2']) ? $this->request->post['config_plpopuplinkhome2'] : $this->config->get('config_plpopuplinkhome2');
		if (isset($this->request->post['on_offlh2'])) {
					$this->data['on_offlh2'] = $this->request->post['on_offlh2'];
				} else {
					$this->data['on_offlh2'] = $this->config->get('on_offlh2');
				}
				
		$this->data['config_plpopup_home3'] = isset($this->request->post['config_plpopup_home3']) ? $this->request->post['config_plpopup_home3'] : $this->config->get('config_plpopup_home3');
		$this->data['config_plpopuphome3'] = isset($this->request->post['config_plpopuphome3']) ? $this->request->post['config_plpopuphome3'] : $this->config->get('config_plpopuphome3');
		$this->data['config_plpopup_homesize3'] = isset($this->request->post['config_plpopup_homesize3']) ? $this->request->post['config_plpopup_homesize3'] : $this->config->get('config_plpopup_homesize3');
		$this->data['config_plpopuphomesize3'] = isset($this->request->post['config_plpopuphomesize3']) ? $this->request->post['config_plpopuphomesize3'] : $this->config->get('config_plpopuphomesize3');
		$this->data['config_plpopup_homecolor3'] = isset($this->request->post['config_plpopup_homecolor3']) ? $this->request->post['config_plpopup_homecolor3'] : $this->config->get('config_plpopup_homecolor3');
		$this->data['config_plpopuphomecolor3'] = isset($this->request->post['config_plpopuphomecolor3']) ? $this->request->post['config_plpopuphhomecolor3'] : $this->config->get('config_plpopuphomecolor3');
		$this->data['config_plpopup_homefw3'] = isset($this->request->post['config_plpopup_homefw3']) ? $this->request->post['config_plpopup_homefw3'] : $this->config->get('config_plpopup_homefw3');
		$this->data['config_plpopuphomefw3'] = isset($this->request->post['config_plpopuphomefw3']) ? $this->request->post['config_plpopuphomefw3'] : $this->config->get('config_plpopuphomefw3');
		$this->data['config_plpopup_hometype3'] = isset($this->request->post['config_plpopup_hometype3']) ? $this->request->post['config_plpopup_hometype3'] : $this->config->get('config_plpopup_hometype3');
		$this->data['config_plpopuphometype3'] = isset($this->request->post['config_plpopuphometype3']) ? $this->request->post['config_plpopuphhometype3'] : $this->config->get('config_plpopuphometype3');
		$this->data['config_plpopup_homeleft3'] = isset($this->request->post['config_plpopup_homeleft3']) ? $this->request->post['config_plpopup_homeleft3'] : $this->config->get('config_plpopup_homeleft3');
		$this->data['config_plpopuphomeleft3'] = isset($this->request->post['config_plpopuphomeleft3']) ? $this->request->post['config_plpopuphomeleft3'] : $this->config->get('config_plpopuphomeleft3');
		$this->data['config_plpopup_homeheight3'] = isset($this->request->post['config_plpopup_homeheight3']) ? $this->request->post['config_plpopup_homeheight3'] : $this->config->get('config_plpopup_homeheight3');
		$this->data['config_plpopuphomeheight3'] = isset($this->request->post['config_plpopuphomeheight3']) ? $this->request->post['config_plpopuphhomeheight3'] : $this->config->get('config_plpopuphomeheight3');
		$this->data['config_plpopup_linkhome3'] = isset($this->request->post['config_plpopup_linkhome3']) ? $this->request->post['config_plpopup_linkhome3'] : $this->config->get('config_plpopup_linkhome3');
		$this->data['config_plpopuplinkhome3'] = isset($this->request->post['config_plpopuplinkhome3']) ? $this->request->post['config_plpopuplinkhome3'] : $this->config->get('config_plpopuplinkhome3');
		if (isset($this->request->post['on_offlh3'])) {
					$this->data['on_offlh3'] = $this->request->post['on_offlh3'];
				} else {
					$this->data['on_offlh3'] = $this->config->get('on_offlh3');
				}
						
		$this->data['config_plpopup_home4'] = isset($this->request->post['config_plpopup_home4']) ? $this->request->post['config_plpopup_home4'] : $this->config->get('config_plpopup_home4');
		$this->data['config_plpopuphome4'] = isset($this->request->post['config_plpopuphome4']) ? $this->request->post['config_plpopuphome4'] : $this->config->get('config_plpopuphome4');
		$this->data['config_plpopup_homesize4'] = isset($this->request->post['config_plpopup_homesize4']) ? $this->request->post['config_plpopup_homesize4'] : $this->config->get('config_plpopup_homesize4');
		$this->data['config_plpopuphomesize4'] = isset($this->request->post['config_plpopuphomesize4']) ? $this->request->post['config_plpopuphomesize4'] : $this->config->get('config_plpopuphhomesize4');
		$this->data['config_plpopup_homecolor4'] = isset($this->request->post['config_plpopup_homecolor4']) ? $this->request->post['config_plpopup_homecolor4'] : $this->config->get('config_plpopup_homecolor4');
		$this->data['config_plpopuphomecolor4'] = isset($this->request->post['config_plpopuphomecolor4']) ? $this->request->post['config_plpopuphomecolor4'] : $this->config->get('config_plpopuphomecolor4');
		$this->data['config_plpopup_homefw4'] = isset($this->request->post['config_plpopup_homefw4']) ? $this->request->post['config_plpopup_homefw4'] : $this->config->get('config_plpopup_homefw4');
		$this->data['config_plpopuphomefw4'] = isset($this->request->post['config_plpopuphomefw4']) ? $this->request->post['config_plpopuphomefw4'] : $this->config->get('config_plpopuphhomefw4');
		$this->data['config_plpopup_hometype4'] = isset($this->request->post['config_plpopup_hometype4']) ? $this->request->post['config_plpopup_hometype4'] : $this->config->get('config_plpopup_hometype4');
		$this->data['config_plpopuphometype4'] = isset($this->request->post['config_plpopuphometype4']) ? $this->request->post['config_plpopuphometype4'] : $this->config->get('config_plpopuphometype4');
		$this->data['config_plpopup_homeleft4'] = isset($this->request->post['config_plpopup_homeleft4']) ? $this->request->post['config_plpopup_homeleft4'] : $this->config->get('config_plpopup_homeleft4');
		$this->data['config_plpopuphomeleft4'] = isset($this->request->post['config_plpopuphomeleft4']) ? $this->request->post['config_plpopuphomeleft4'] : $this->config->get('config_plpopuphhomeleft4');
		$this->data['config_plpopup_homeheight4'] = isset($this->request->post['config_plpopup_homeheight4']) ? $this->request->post['config_plpopup_homeheight4'] : $this->config->get('config_plpopup_homeheight4');
		$this->data['config_plpopuphomeheight4'] = isset($this->request->post['config_plpopuphomeheight4']) ? $this->request->post['config_plpopuphomeheight4'] : $this->config->get('config_plpopuphomeheight4');
		$this->data['config_plpopup_linkhome4'] = isset($this->request->post['config_plpopup_linkhome4']) ? $this->request->post['config_plpopup_linkhome4'] : $this->config->get('config_plpopup_linkhome4');
		$this->data['config_plpopuplinkhome4'] = isset($this->request->post['config_plpopuplinkhome4']) ? $this->request->post['config_plpopuplinkhome4'] : $this->config->get('config_plpopuplinkhome4');
		if (isset($this->request->post['on_offlh4'])) {
					$this->data['on_offlh4'] = $this->request->post['on_offlh4'];
				} else {
					$this->data['on_offlh4'] = $this->config->get('on_offlh4');
				}
					
		$this->data['config_plpopup_footer'] = isset($this->request->post['config_plpopup_footer']) ? $this->request->post['config_plpopup_footer'] : $this->config->get('config_plpopup_footer');
		$this->data['config_plpopupfooter'] = isset($this->request->post['config_plpopupfooter']) ? $this->request->post['config_plpopupfooter'] : $this->config->get('config_plpopupfooter');
		$this->data['config_plpopup_footersize'] = isset($this->request->post['config_plpopup_footersize']) ? $this->request->post['config_plpopup_footersize'] : $this->config->get('config_plpopup_footersize');
		$this->data['config_plpopupfootersize'] = isset($this->request->post['config_plpopupfootersize']) ? $this->request->post['config_plpopupfootersize'] : $this->config->get('config_plpopupfootersize');
		$this->data['config_plpopup_footercolor'] = isset($this->request->post['config_plpopup_footercolor']) ? $this->request->post['config_plpopup_footercolor'] : $this->config->get('config_plpopup_footercolor');
		$this->data['config_plpopupfootercolor'] = isset($this->request->post['config_plpopupfootercolor']) ? $this->request->post['config_plpopupfootercolor'] : $this->config->get('config_plpopupfootercolor');
		$this->data['config_plpopup_footerfw'] = isset($this->request->post['config_plpopup_footerfw']) ? $this->request->post['config_plpopup_footerfw'] : $this->config->get('config_plpopup_footerfw');
		$this->data['config_plpopupfooterfw'] = isset($this->request->post['config_plpopupfooterfw']) ? $this->request->post['config_plpopupfooterfw'] : $this->config->get('config_plpopupfooterfw');
		$this->data['config_plpopup_footertype'] = isset($this->request->post['config_plpopup_footertype']) ? $this->request->post['config_plpopup_footertype'] : $this->config->get('config_plpopup_footertype');
		$this->data['config_plpopupfootertype'] = isset($this->request->post['config_plpopupfootertype']) ? $this->request->post['config_plpopupfootertype'] : $this->config->get('config_plpopupfootertype');
		$this->data['config_plpopup_footerleft'] = isset($this->request->post['config_plpopup_footerleft']) ? $this->request->post['config_plpopup_footerleft'] : $this->config->get('config_plpopup_footerleft');
		$this->data['config_plpopupfooterleft'] = isset($this->request->post['config_plpopupfooterleft']) ? $this->request->post['config_plpopupfooterleft'] : $this->config->get('config_plpopupfooterleft');
		$this->data['config_plpopup_footerheight'] = isset($this->request->post['config_plpopup_footerheight']) ? $this->request->post['config_plpopup_footerheight'] : $this->config->get('config_plpopup_footerheight');
		$this->data['config_plpopupfooterheight'] = isset($this->request->post['config_plpopupfooterheight']) ? $this->request->post['config_plpopupfooterheight'] : $this->config->get('config_plpopupfooterheight');
		$this->data['config_plpopup_linkfooter'] = isset($this->request->post['config_plpopup_linkfooter']) ? $this->request->post['config_plpopup_linkfooter'] : $this->config->get('config_plpopup_linkfooter');
		$this->data['config_plpopuplinkfooter'] = isset($this->request->post['config_plpopuplinkfooter']) ? $this->request->post['config_plpopuplinkfooter'] : $this->config->get('config_plpopuplinkfooter');
		if (isset($this->request->post['on_offlf'])) {
					$this->data['on_offlf'] = $this->request->post['on_offlf'];
				} else {
					$this->data['on_offlf'] = $this->config->get('on_offlf');
				}
				
		$this->data['config_plpopup_footer2'] = isset($this->request->post['config_plpopup_footer2']) ? $this->request->post['config_plpopup_footer2'] : $this->config->get('config_plpopup_footer2');
		$this->data['config_plpopupfooter2'] = isset($this->request->post['config_plpopupfooter2']) ? $this->request->post['config_plpopupfooter2'] : $this->config->get('config_plpopupfooter2');
		$this->data['config_plpopup_footersize2'] = isset($this->request->post['config_plpopup_footersize2']) ? $this->request->post['config_plpopup_footersize2'] : $this->config->get('config_plpopup_footersize2');
		$this->data['config_plpopupfootersize2'] = isset($this->request->post['config_plpopupfootersize2']) ? $this->request->post['config_plpopupfootersize2'] : $this->config->get('config_plpopupfootersize2');
		$this->data['config_plpopup_footercolor2'] = isset($this->request->post['config_plpopup_footercolor2']) ? $this->request->post['config_plpopup_footercolor2'] : $this->config->get('config_plpopup_footercolor2');
		$this->data['config_plpopupfootercolor2'] = isset($this->request->post['config_plpopupfootercolor2']) ? $this->request->post['config_plpopupfootercolor2'] : $this->config->get('config_plpopupfootercolor2');
		$this->data['config_plpopup_footerfw2'] = isset($this->request->post['config_plpopup_footerfw2']) ? $this->request->post['config_plpopup_footerfw2'] : $this->config->get('config_plpopup_footerfw2');
		$this->data['config_plpopupfooterfw2'] = isset($this->request->post['config_plpopupfooterfw2']) ? $this->request->post['config_plpopupfooterfw2'] : $this->config->get('config_plpopupfooterfw2');
		$this->data['config_plpopup_footertype2'] = isset($this->request->post['config_plpopup_footertype2']) ? $this->request->post['config_plpopup_footertype2'] : $this->config->get('config_plpopup_footertype2');
		$this->data['config_plpopupfootertype2'] = isset($this->request->post['config_plpopupfootertype2']) ? $this->request->post['config_plpopupfootertype2'] : $this->config->get('config_plpopupfootertype2');
		$this->data['config_plpopup_footerleft2'] = isset($this->request->post['config_plpopup_footerleft2']) ? $this->request->post['config_plpopup_footerleft2'] : $this->config->get('config_plpopup_footerleft2');
		$this->data['config_plpopupfooterleft2'] = isset($this->request->post['config_plpopupfooterleft2']) ? $this->request->post['config_plpopupfooterleft2'] : $this->config->get('config_plpopupfooterleft2');
		$this->data['config_plpopup_footerheight2'] = isset($this->request->post['config_plpopup_footerheight2']) ? $this->request->post['config_plpopup_footerheight2'] : $this->config->get('config_plpopup_footerheight2');
		$this->data['config_plpopupfooterheight2'] = isset($this->request->post['config_plpopupfooterheight2']) ? $this->request->post['config_plpopupfooterheight2'] : $this->config->get('config_plpopupfooterheight2');
		$this->data['config_plpopup_linkfooter2'] = isset($this->request->post['config_plpopup_linkfooter2']) ? $this->request->post['config_plpopup_linkfooter2'] : $this->config->get('config_plpopup_linkfooter2');
		$this->data['config_plpopuplinkfooter2'] = isset($this->request->post['config_plpopuplinkfooter2']) ? $this->request->post['config_plpopuplinkfooter2'] : $this->config->get('config_plpopuplinkfooter2');
		if (isset($this->request->post['on_offlf2'])) {
					$this->data['on_offlf2'] = $this->request->post['on_offlf2'];
				} else {
					$this->data['on_offlf2'] = $this->config->get('on_offlf2');
				}
				
		$this->data['config_plpopup_style'] = isset($this->request->post['config_plpopup_style']) ? $this->request->post['config_plpopup_style'] : $this->config->get('config_plpopup_style');
		$this->data['config_plpopupstyle'] = isset($this->request->post['config_plpopupstyle']) ? $this->request->post['config_plpopupstyle'] : $this->config->get('config_plpopupstyle');
		
		$this->data['config_plpopup_cookie'] = isset($this->request->post['config_plpopup_cookie']) ? $this->request->post['config_plpopup_cookie'] : $this->config->get('config_plpopup_cookie');
		$this->data['config_plpopupcookie'] = isset($this->request->post['config_plpopupcookie']) ? $this->request->post['config_plpopupcookie'] : $this->config->get('config_plpopupcookie');

		$this->data['config_plpopup_sr'] = isset($this->request->post['config_plpopup_sr']) ? $this->request->post['config_plpopup_sr'] : $this->config->get('config_plpopup_sr');
		$this->data['config_plpopupsr'] = isset($this->request->post['config_plpopupsr']) ? $this->request->post['config_plpopupsr'] : $this->config->get('config_plpopupsr');

		$this->data['config_plpopup_tm'] = isset($this->request->post['config_plpopup_tm']) ? $this->request->post['config_plpopup_tm'] : $this->config->get('config_plpopup_tm');
		$this->data['config_plpopuptm'] = isset($this->request->post['config_plpopuptm']) ? $this->request->post['config_plpopuptm'] : $this->config->get('config_plpopuptm');

		$this->data['config_plpopup_pic'] = isset($this->request->post['config_plpopup_pic']) ? $this->request->post['config_plpopup_pic'] : $this->config->get('config_plpopup_pic');
		$this->data['config_plpopuppic'] = isset($this->request->post['config_plpopuppic']) ? $this->request->post['config_plpopuppic'] : $this->config->get('config_plpopuppic');
					
		$this->data['config_plpopup_amw'] = isset($this->request->post['config_plpopup_amw']) ? $this->request->post['config_plpopup_amw'] : $this->config->get('config_plpopup_amw');
		$this->data['config_plpopupamw'] = isset($this->request->post['config_plpopupamw']) ? $this->request->post['config_plpopupamw'] : $this->config->get('config_plpopupamw');
		
		$this->data['config_plpopup_amh'] = isset($this->request->post['config_plpopup_amh']) ? $this->request->post['config_plpopup_amh'] : $this->config->get('config_plpopup_amh');
		$this->data['config_plpopupamh'] = isset($this->request->post['config_plpopupamh']) ? $this->request->post['config_plpopupamh'] : $this->config->get('config_plpopupamh');
				
		$this->data['config_plpopup_bg'] = isset($this->request->post['config_plpopup_bg']) ? $this->request->post['config_plpopup_bg'] : $this->config->get('config_plpopup_bg');
		$this->data['config_plpopupbg'] = isset($this->request->post['config_plpopupbg']) ? $this->request->post['config_plpopupbg'] : $this->config->get('config_plpopupbg');
		
		if (isset($this->request->post['on_off'])) {
					$this->data['on_off'] = $this->request->post['on_off'];
				} else {
					$this->data['on_off'] = $this->config->get('on_off');
				}

		$this->data['modules'] = array();
		
		if (isset($this->request->post['plpopup_module'])) {
			$this->data['modules'] = $this->request->post['plpopup_module'];
		} elseif ($this->config->get('plpopup_module')) { 
			$this->data['modules'] = $this->config->get('plpopup_module');
		}	
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
						
		$this->template = 'module/plpopup.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/plpopup')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
