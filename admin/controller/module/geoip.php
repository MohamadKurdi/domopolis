<?php
    class ControllerModuleGeoIP extends Controller {

        private $error = array();

        public function index() {

            $this->load->language('module/geoip');

            $this->document->setTitle($this->language->get('heading_title'));

            $this->load->model('setting/setting');
            $this->load->model('module/geoip');

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

                $this->model_module_geoip->deleteCities();
                $this->model_module_geoip->deleteRules();
                $this->model_module_geoip->deleteRedirects();
                $this->model_module_geoip->deleteCurrencies();

                if (!empty($this->request->post['geoip_city'])) {

                    $this->model_module_geoip->editCities($this->request->post['geoip_city']);
                    
                    unset($this->request->post['geoip_city']);
                }
                
                if (!empty($this->request->post['geoip_rule'])) {

                    $this->model_module_geoip->editRules($this->request->post['geoip_rule']);
                    
                    unset($this->request->post['geoip_rule']);
                }
                
                if (!empty($this->request->post['geoip_redirect'])) {

                    $this->model_module_geoip->editRedirects($this->request->post['geoip_redirect']);
                    
                    unset($this->request->post['geoip_redirect']);
                }

                if (!empty($this->request->post['geoip_currency'])) {

                    $this->model_module_geoip->editCurrencies($this->request->post['geoip_currency']);

                    unset($this->request->post['geoip_currency']);
                }

                $this->request->post['geoip_setting']['popup_cookie_time'] = (int)$this->request->post['geoip_setting']['popup_cookie_time'];

                $this->model_setting_setting->editSetting('geoip', $this->request->post);

                $this->session->data['success'] = $this->language->get('text_success');

                $this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
            }

            $this->data['heading_title'] = $this->language->get('heading_title');

            $this->data['text_yes'] = $this->language->get('text_yes');
            $this->data['text_no'] = $this->language->get('text_no');
            $this->data['text_none'] = $this->language->get('text_none');

            $this->data['button_save'] = $this->language->get('button_save');
            $this->data['button_cancel'] = $this->language->get('button_cancel');
            $this->data['button_remove'] = $this->language->get('button_remove');
            $this->data['button_add_rule'] = $this->language->get('button_add_rule');

            $this->data['entry_set_zone'] = $this->language->get('entry_set_zone');
            $this->data['entry_from_ajax'] = $this->language->get('entry_from_ajax');
            $this->data['entry_key'] = $this->language->get('entry_key');
            $this->data['entry_zone'] = $this->language->get('entry_zone');
            $this->data['entry_city'] = $this->language->get('entry_city');
            $this->data['entry_value'] = $this->language->get('entry_value');
            $this->data['entry_subdomain'] = $this->language->get('entry_subdomain');
            $this->data['entry_popup_active'] = $this->language->get('entry_popup_active');
            $this->data['entry_popup_cookie_time'] = $this->language->get('entry_popup_cookie_time');

            $this->data['entry_currency'] = $this->language->get('entry_currency');
            $this->data['entry_country'] = $this->language->get('entry_country');

            $this->data['text_popup_cities'] = $this->language->get('text_popup_cities');

            $this->data['tab_general'] = $this->language->get('tab_general');
            $this->data['tab_popup'] = $this->language->get('tab_popup');
            $this->data['tab_messages'] = $this->language->get('tab_messages');
            $this->data['tab_redirects'] = $this->language->get('tab_redirects');
            $this->data['tab_currencies'] = $this->language->get('tab_currencies');

            $this->load->model('localisation/currency');
            $this->data['currencies'] = $this->model_localisation_currency->getCurrencies();

            $this->load->model('localisation/country');
            $this->data['countries'] = $this->model_localisation_country->getCountries();

            if (isset($this->error['warning'])) {
                $this->data['error_warning'] = $this->error['warning'];
            }
            else {
                $this->data['error_warning'] = '';
            }

            $this->data['error_key'] = isset($this->error['key']) ? $this->error['key'] : array();
            $this->data['error_fias'] = isset($this->error['fias']) ? $this->error['fias'] : array();
            $this->data['error_redirect_fias'] = isset($this->error['redirect_fias']) ? $this->error['redirect_fias'] : array();
            $this->data['error_subdomain'] = isset($this->error['subdomain']) ? $this->error['subdomain'] : array();
            $this->data['error_currency_country'] = isset($this->error['currency_country']) ? $this->error['currency_country'] : array();
            $this->data['error_currency_code'] = isset($this->error['currency_code']) ? $this->error['currency_code'] : array();

            $this->data['breadcrumbs'] = array();

            $this->data['breadcrumbs'][] = array('text'      => $this->language->get('text_home'),
                                                 'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
                                                 'separator' => false);

            $this->data['breadcrumbs'][] = array('text'      => $this->language->get('text_module'),
                                                 'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
                                                 'separator' => ' :: ');

            $this->data['breadcrumbs'][] = array('text'      => $this->language->get('heading_title'),
                                                 'href'      => $this->url->link('module/geoip', 'token=' . $this->session->data['token'], 'SSL'),
                                                 'separator' => ' :: ');

            $this->data['action'] = $this->url->link('module/geoip', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');

            $this->data['token'] = $this->session->data['token'];

            if (isset($this->request->post['geoip_setting'])) {
                $this->data['geoip_setting'] = $this->request->post['geoip_setting'];
            }
            elseif ($this->config->get('geoip_setting')) {
                $this->data['geoip_setting'] = $this->config->get('geoip_setting');
            }

            $this->data['rules'] = array();

            if (isset($this->request->post['geoip_rule'])) {
                $this->data['rules'] = $this->request->post['geoip_rule'];
            }
            else {
                $this->data['rules'] = $this->model_module_geoip->getRules();
            }

            foreach ($this->data['rules'] as & $rule) {
                $rule['fias_name'] = $this->model_module_geoip->getFiasName($rule['fias_id']);
            }

            $this->data['redirects'] = array();

            if (isset($this->request->post['geoip_redirect'])) {
                $this->data['redirects'] = $this->request->post['geoip_redirect'];
            }
            else {
                $this->data['redirects'] = $this->model_module_geoip->getRedirects();
            }

            $this->data['geoip_currencies'] = array();

            if (isset($this->request->post['geoip_currency'])) {
                $this->data['geoip_currencies'] = $this->request->post['geoip_currency'];
            }
            else {
                $this->data['geoip_currencies'] = $this->model_module_geoip->getCurrencies();
            }

            foreach ($this->data['redirects'] as & $redirect) {
                $redirect['fias_name'] = $this->model_module_geoip->getFiasName($redirect['fias_id']);
            }

            $this->data['cities'] = array();

            if (isset($this->request->post['geoip_city'])) {
                $this->data['cities'] = $this->request->post['geoip_city'];
            }
            else {
                $this->data['cities'] = $this->model_module_geoip->getCities();
            }

            $this->load->model('design/layout');

            $this->data['layouts'] = $this->model_design_layout->getLayouts();

            $this->load->model('localisation/language');

            $this->data['languages'] = $this->model_localisation_language->getLanguages();

            $this->template = 'module/geoip.tpl';
            $this->children = array('common/header', 'common/footer');

            $this->response->setOutput($this->render());
        }

        public function search() {

            $json = array();

            if (isset($this->request->get['term'])) {

                $this->load->model('module/geoip');

                $json = $this->model_module_geoip->findFiasByName($this->request->get['term']);
            }

            echo json_encode($json);
            die;
        }

        private function validate() {

            if (!$this->user->hasPermission('modify', 'module/geoip')) {
                $this->error['warning'] = $this->language->get('error_permission');
            }

            if (isset($this->request->post['geoip_rule'])) {

                foreach ($this->request->post['geoip_rule'] as $key => $value) {

                    if (!$value['key'] || !preg_match('#^[a-zA-Z0-9_-]*$#', $value['key'])) {
                        $this->error['key'][$key] = $this->language->get('error_key');
                    }

                    if (!(int)$value['fias_id']) {
                        $this->error['fias'][$key] = $this->language->get('error_fias');
                    }
                }
            }

            if (isset($this->request->post['geoip_redirect'])) {

                foreach ($this->request->post['geoip_redirect'] as $key => $value) {
				/*
                    if (!$value['url'] || !preg_match('#^http://([a-z0-9]+([\-a-z0-9]*[a-z0-9]+)?\.){0,}([a-z0-9]+([\-a-z0-9]*[a-z0-9]+)?){1,63}(\.[a-z0-9]{2,7})+/$#', $value['url'])) {
                        $this->error['subdomain'][$key] = $this->language->get('error_subdomain');
                    }
				*/
                    if (!(int)$value['fias_id']) {
                        $this->error['redirect_fias'][$key] = $this->language->get('error_fias');
                    }
                }
            }

            if (isset($this->request->post['geoip_city'])) {

                foreach ($this->request->post['geoip_city'] as $key => $value) {

                    if (!(int)$value['fias_id']) {
                        $this->error['city_fias'][$key] = $this->language->get('error_fias');
                    }
                }
            }

            if (isset($this->request->post['geoip_currency'])) {

                foreach ($this->request->post['geoip_currency'] as $key => $value) {

                    if (!(int)$value['country_id']) {
                        $this->error['currency_country'][$key] = $this->language->get('error_currency_country');
                    }

                    if (!$value['code']) {
                        $this->error['currency_code'][$key] = $this->language->get('error_currency_code');
                    }
                }
            }

            return !$this->error;
        }
    }