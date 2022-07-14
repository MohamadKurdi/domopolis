<?php

class ControllerPaymentShoputilspsb extends Controller
{
    private $error = array();
    private $version = '1.1';
    const MAX_LAST_LOG_LINES = 500;
    const FILE_NAME_LIC = 'shoputils_psb.lic';
    const FILE_NAME_LOG = 'shoputils_psb.log';

    public function __construct($registry) {
        parent::__construct($registry);
        $this->language->load('payment/shoputils_psb');
        $this->document->setTitle($this->language->get('heading_title'));
    }

    public function index() {
	
		if (SITE_NAMESPACE == 'HAUSGARTEN'){
			die('THIS MODULE DOESNOT WORK ON HSG');
		}					
	
        if (!is_file(DIR_APPLICATION . self::FILE_NAME_LIC)) {
           // $this->redirect($this->url->link('payment/shoputils_psb/lic', '&token=' . $this->session->data['token'], 'SSL'));
        }

        register_shutdown_function(array($this, 'licShutdownHandler'));
        $this->load->model('shoputils/psb');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->model_shoputils_psb->setSetting();
            $this->redirect($this->model_shoputils_psb->makeUrl('extension/payment'));
        }

        $this->load->model('localisation/language');
        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');

        $this->_setData(array(
                             'heading_title',
                             'button_save',
                             'button_cancel',
                             'button_clear',
                             'button_calculate',
                             'tab_general',
                             'tab_emails',
                             'tab_settings',
                             'tab_log',
                             'tab_information',
                             'text_confirm',
                             'text_enabled',
                             'text_disabled',
                             'text_all_zones',
                             'text_yes',
                             'text_no',
                             'text_info',
                             'entry_geo_zone',
                             'entry_status',
                             'entry_minimal_order',
                             'entry_maximal_order',
                             'entry_sort_order',
                             'entry_order_status',
                             'entry_order_confirm_status',
                             'entry_order_fail_status',
                             'entry_laterpay_mode',
                             'entry_order_later_status',
                             'entry_title',
                             'entry_instruction',

                             'entry_notify_customer_success',
                             'entry_mail_customer_success_subject',
                             'entry_mail_customer_success_content',
                             'entry_notify_customer_fail',
                             'entry_mail_customer_fail_subject',
                             'entry_mail_customer_fail_content',
                             'entry_notify_admin_success',
                             'entry_mail_admin_success_subject',
                             'entry_mail_admin_success_content',
                             'entry_notify_admin_fail',
                             'entry_mail_admin_fail_subject',
                             'entry_mail_admin_fail_content',

                             'entry_sign_type',
                             'entry_terminal',
                             'entry_terminal_test',
                             'entry_merchant_id',
                             'entry_merchant_key',
                             'entry_merchant_name',
                             'entry_test_mode',

                             'entry_log',
                             'entry_log_file',

                             'entry_callback_url',
                             'entry_calculate',
                             'entry_component1',
                             'entry_component2',

                             'sample_mail_customer_success_content',
                             'sample_mail_customer_success_subject',
                             'sample_mail_customer_fail_subject',
                             'sample_mail_customer_fail_content',
                             'sample_mail_admin_success_subject',
                             'sample_mail_admin_success_content',
                             'sample_mail_admin_fail_subject',
                             'sample_mail_admin_fail_content',
                             'placeholder_instruction',
                             'help_minimal_order',
                             'help_maximal_order',
                             'help_order_confirm_status',
                             'help_order_status',
                             'help_order_fail_status',
                             'help_laterpay_mode',
                             'help_order_later_status',
                             'help_title',
                             'help_instruction',

                             'help_notify_customer_success',
                             'help_mail_customer_success_subject',
                             'help_mail_customer_success_content',
                             'help_notify_customer_fail',
                             'help_mail_customer_fail_subject',
                             'help_mail_customer_fail_content',
                             'help_notify_admin_success',
                             'help_mail_admin_success_subject',
                             'help_mail_admin_success_content',
                             'help_notify_admin_fail',
                             'help_mail_admin_fail_subject',
                             'help_mail_admin_fail_content',

                             'help_terminal',
                             'help_merchant_id',
                             'help_merchant_key',
                             'help_merchant_name',
                             'help_test_mode',
                             'help_lang',

                             'help_calculate',

                             'help_terminal_test'   => sprintf($this->language->get('help_terminal_test'), HTTP_CATALOG . 'index.php?route=payment/shoputils_psb/callback'),
                             'help_log_file'        => sprintf($this->language->get('help_log_file'), self::MAX_LAST_LOG_LINES),
                             'help_log'             => sprintf($this->language->get('help_log'), self::FILE_NAME_LOG),
                             'title_default'        => explode(',', $this->language->get('heading_title')),
                             'action'               => $this->model_shoputils_psb->makeUrl('payment/shoputils_psb'),
                             'cancel'               => $this->model_shoputils_psb->makeUrl('extension/payment'),
                             'clear_log'            => $this->model_shoputils_psb->makeUrl('payment/shoputils_psb/clearLog'),
                             'key_calculate'        => $this->model_shoputils_psb->makeUrl('payment/shoputils_psb/keyCalculate'),
                             'token'                => isset($this->session->data['token']) ? $this->session->data['token'] : '',
                             'text_copyright'       => sprintf($this->language->get('text_copyright'), $this->language->get('heading_title'), date('Y', time())),
                             'list_helper'          => $this->getListHelper(),
                             'error_warning'        => isset($this->error['warning']) ? $this->error['warning'] : '',
                             'error_mail_customer_success_subject' => isset($this->error['error_mail_customer_success_subject']) ? $this->error['error_mail_customer_success_subject'] : '',
                             'error_mail_customer_success_content' => isset($this->error['error_mail_customer_success_content']) ? $this->error['error_mail_customer_success_content'] : '',
                             'error_mail_customer_fail_subject'    => isset($this->error['error_mail_customer_fail_subject']) ? $this->error['error_mail_customer_fail_subject'] : '',
                             'error_mail_customer_fail_content'    => isset($this->error['error_mail_customer_fail_content']) ? $this->error['error_mail_customer_fail_content'] : '',
                             'error_mail_admin_success_subject'    => isset($this->error['error_mail_admin_success_subject']) ? $this->error['error_mail_admin_success_subject'] : '',
                             'error_mail_admin_success_content'    => isset($this->error['error_mail_admin_success_content']) ? $this->error['error_mail_admin_success_content'] : '',
                             'error_mail_admin_fail_subject'       => isset($this->error['error_mail_admin_fail_subject']) ? $this->error['error_mail_admin_fail_subject'] : '',
                             'error_mail_admin_fail_content'       => isset($this->error['error_mail_admin_fail_content']) ? $this->error['error_mail_admin_fail_content'] : '',
                             'error_terminal'       => isset($this->error['error_terminal']) ? $this->error['error_terminal'] : '',
                             'error_terminal_test'  => isset($this->error['error_terminal_test']) ? $this->error['error_terminal_test'] : '',
                             'error_merchant_id'    => isset($this->error['error_merchant_id']) ? $this->error['error_merchant_id'] : '',
                             'error_merchant_key'   => isset($this->error['error_merchant_key']) ? $this->error['error_merchant_key'] : '',
                             'error_merchant_name'  => isset($this->error['error_merchant_name']) ? $this->error['error_merchant_name'] : '',
                             'version'              => $this->version,
                             'log_lines'            => $this->readLastLines(DIR_LOGS . self::FILE_NAME_LOG, self::MAX_LAST_LOG_LINES),
                             'log_filename'         => self::FILE_NAME_LOG,
                             'geo_zones'            => $this->model_localisation_geo_zone->getGeoZones(),
                             'order_statuses'       => $this->model_localisation_order_status->getOrderStatuses(),
                             'oc_languages'         => $this->model_localisation_language->getLanguages()
                        ));

        $this->data['logs'] = array(
            '0' => $this->language->get('text_log_off'),
            '1' => $this->language->get('text_log_short'),
            '2' => $this->language->get('text_log_full')
        );

        $this->data['test_modes'] = array(
            '0' => $this->language->get('text_disabled'),
            '1' => $this->language->get('text_enabled')
        );

        $this->data['informations'] = array(
            $this->language->get('entry_result_url')  => HTTP_CATALOG . 'index.php?route=payment/shoputils_psb/callback'
        );

        if (isset($this->session->data['success'])) {
          $this->data['success'] = $this->session->data['success'];
          unset($this->session->data['success']);
        } else {
          $this->data['success'] = '';
        }

        if (isset($this->session->data['warning'])) {
          $this->data['error_warning'] = $this->session->data['warning'];
          unset($this->session->data['warning']);
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->model_shoputils_psb->makeUrl('common/home'),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->model_shoputils_psb->makeUrl('extension/payment'),
            'text' => $this->language->get('text_payment'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->model_shoputils_psb->makeUrl('payment/shoputils_psb'),
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '

        );

        $this->_updateData(
            array(
                 'shoputils_psb_geo_zone_id',
                 'shoputils_psb_sort_order',
                 'shoputils_psb_status',
				 'shoputils_psb_ismethod',
                 'shoputils_psb_minimal_order',
                 'shoputils_psb_maximal_order',
                 'shoputils_psb_laterpay_mode',
                 'shoputils_psb_order_status_id',
                 'shoputils_psb_order_fail_status_id',
                 'shoputils_psb_order_confirm_status_id',
                 'shoputils_psb_order_later_status_id',
                 'shoputils_psb_langdata',

                 'shoputils_psb_notify_customer_success',
                 'shoputils_psb_notify_customer_fail',
                 'shoputils_psb_notify_admin_success',
                 'shoputils_psb_mail_admin_success_subject',
                 'shoputils_psb_mail_admin_success_content',
                 'shoputils_psb_notify_admin_fail',
                 'shoputils_psb_mail_admin_fail_subject',
                 'shoputils_psb_mail_admin_fail_content',

                 'shoputils_psb_terminal',
                 'shoputils_psb_terminal_test',
                 'shoputils_psb_merchant_id',
                 'shoputils_psb_merchant_key',
                 'shoputils_psb_merchant_name',
                 'shoputils_psb_test_mode',

                 'shoputils_psb_log'
            ), array(
                      'shoputils_psb_terminal'      => str_repeat('0', 8),
                      'shoputils_psb_terminal_test' => '79036768',
                      'shoputils_psb_merchant_id'   => str_repeat('0', 15),
                      'shoputils_psb_merchant_key'  => str_repeat('0', 32),
                      'shoputils_psb_merchant_name' => $this->config->get('config_name')
                     )
        );
        
        $this->template = 'payment/shoputils_psb.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    public function lic() {
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (!$this->user->hasPermission('modify', 'payment/shoputils_psb')) {
                $this->session->data['warning'] = sprintf($this->language->get('error_permission'), $this->language->get('heading_title'));
            } elseif (!empty($this->request->post['lic_data'])) {
                if (!is_writable(DIR_APPLICATION)) {
                    $perms = fileperms(DIR_APPLICATION);
                    chmod(DIR_APPLICATION, 0775);
                }
                
                $lic = '------ LICENSE FILE DATA -------' . "\n";
                $lic .= trim($this->request->post['lic_data']) . "\n";
                $lic .= '--------------------------------' . "\n";
                $file = DIR_APPLICATION . self::FILE_NAME_LIC;
                $handle = @fopen($file, 'w'); 
                fwrite($handle, $lic);
                fclose($handle); 
                if (isset($perms)) {
                    chmod(DIR_APPLICATION, $perms);
                }

                if (!file_exists($file)) {
                    $this->session->data['warning'] = sprintf($this->language->get('error_dir_perm'), DIR_APPLICATION);
                    $this->redirect($this->url->link('payment/shoputils_psb/lic', '&token=' . $this->session->data['token'], 'SSL'));
                }

                register_shutdown_function(array($this, 'licShutdownHandler'));
                $this->load->model('shoputils/psb');

                $this->redirect($this->url->link('payment/shoputils_psb', '&token=' . $this->session->data['token'], 'SSL'));
            }
        }

        $domain = str_replace('http://', '', HTTP_SERVER);
        $domain = explode('/', str_replace('https://', '', $domain));
        
        $loader = extension_loaded('ionCube Loader');

        $loader_min_version = '4.2';
        $loader_version = function_exists('ioncube_loader_version') ? ioncube_loader_version() : $loader_min_version;
        $loader_compare = version_compare($loader_version, $loader_min_version, '>=');

        $php_min_version = '5.3';
        $php_version = phpversion();
        $php_compare = version_compare($php_version, $php_min_version, '>=');

        $this->_setData(array(
            'heading_title',
            'button_save',
            'button_cancel',
            'text_ok',
            'text_error',
            'text_get_key',
            'entry_key',
            'error_key',
            'error_php_version',
            'error_loader'          => sprintf($this->language->get('error_loader'), $loader_min_version),
            'error_loader_version'  => sprintf($this->language->get('error_loader_version'), $loader_min_version),
            'error'                 => !($loader && $loader_compare && $php_compare),
            'text_domain'           => sprintf($this->language->get('text_domain'), $domain[0]),
            'text_loader'           => sprintf($this->language->get('text_loader'), $loader_version, $loader_min_version),
            'text_php'              => sprintf($this->language->get('text_php'), $php_version, $php_min_version),
            'action'                => $this->url->link('payment/shoputils_psb/lic', '&token=' . $this->session->data['token'], 'SSL'),
            'cancel'                => $this->url->link('extension/payment', '&token=' . $this->session->data['token'], 'SSL'),
            'loader'                => $loader,
            'icon'                  => 'view/image/payment/shoputils_psb-icon.jpg',
            'loader_compare'        => $loader_compare,
            'php_compare'           => $php_compare
        ));
        
        if (isset($this->session->data['warning'])) {
          $this->data['error_warning'] = $this->session->data['warning'];
          unset($this->session->data['warning']);
          if (file_exists(DIR_APPLICATION . self::FILE_NAME_LIC)) {
              @unlink(DIR_APPLICATION . self::FILE_NAME_LIC);
          }
        } else {
          $this->data['error_warning'] = '';
        }

        $this->template = 'shoputils/payment/shoputils_psb_lic.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render());
    }

    public function clearLog() {
      $json = array();

      $this->load->model('shoputils/psb');
      if ($this->model_shoputils_psb->validatePermission()) {
          if (file_exists(DIR_LOGS . self::FILE_NAME_LOG)) {
              @unlink(DIR_LOGS . self::FILE_NAME_LOG);
          }
          $json['success'] = $this->language->get('text_clear_log_success');
      } else {
          $json['error'] = $this->language->get('error_clear_log');
      }

      $this->response->setOutput(json_encode($json));
    }

    public function keyCalculate() {
      $json = array();

      if ((isset($this->request->post['component1']) && (trim($this->request->post['component1']))) &&
          (isset($this->request->post['component2']) && (trim($this->request->post['component2']))) &&
          (strlen(trim($this->request->post['component1'])) == 32) &&
          (strlen(trim($this->request->post['component2'])) == 32) &&
          (ctype_xdigit(trim($this->request->post['component1']))) &&
          (ctype_xdigit(trim($this->request->post['component2'])))) {
          
          $pack1  = pack("H32", trim($this->request->post['component1']));
          $pack2  = pack("H32", trim($this->request->post['component2']));
          $result = strtoupper(implode(unpack("H32", $pack1 ^ $pack2)));
          $json['success']  = sprintf($this->language->get('text_key_calculate_success'), $result);
          $json['result']   = $result;
      } else {
          $json['error'] = $this->language->get('error_key_calculate_fail');
      }

      $this->response->setOutput(json_encode($json));
    }

    protected function getListHelper() {
        $this->_setData(array(
                             'text_order_id_ft',
                             'text_store_name_ft',
                             'text_logo_ft',
                             'text_products_ft',
                             'text_total_ft',
                             'text_customer_firstname_ft',
                             'text_customer_lastname_ft',
                             'text_customer_group_ft',
                             'text_customer_email_ft',
                             'text_customer_telephone_ft',
                             'text_order_status_ft',
                             'text_comment_ft',
                             'text_ip_ft',
                             'text_date_added_ft',
                             'text_date_modified_ft'
                        ));

        $this->template = 'payment/shoputils_psb_list_helper.tpl';
        return $this->render();
    }

    protected function validate() {
        if (!$this->model_shoputils_psb->validatePermission()) {
            $this->error['warning'] = sprintf($this->language->get('error_permission'), $this->language->get('heading_title'));
        } else {
            if (!isset($this->request->post['shoputils_psb_terminal']) || !trim($this->request->post['shoputils_psb_terminal'])) {
                $this->error['warning'] = $this->error['error_terminal'] = sprintf($this->language->get('error_form'),
                                                                                        $this->language->get('entry_terminal'),
                                                                                        $this->language->get('tab_settings'));
            }
            if (!isset($this->request->post['shoputils_psb_terminal_test']) || !trim($this->request->post['shoputils_psb_terminal_test'])) {
                $this->error['warning'] = $this->error['error_terminal_test'] = sprintf($this->language->get('error_form'),
                                                                                        $this->language->get('entry_terminal_test'),
                                                                                        $this->language->get('tab_settings'));
            }
            if (!isset($this->request->post['shoputils_psb_merchant_id']) || !trim($this->request->post['shoputils_psb_merchant_id'])) {
                $this->error['warning'] = $this->error['error_merchant_id'] = sprintf($this->language->get('error_form'),
                                                                                        $this->language->get('entry_merchant_id'),
                                                                                        $this->language->get('tab_settings'));
            }
            if (!isset($this->request->post['shoputils_psb_merchant_key']) || !trim($this->request->post['shoputils_psb_merchant_key'])) {
                $this->error['warning'] = $this->error['error_merchant_key'] = sprintf($this->language->get('error_form'),
                                                                                        $this->language->get('entry_merchant_key'),
                                                                                        $this->language->get('tab_settings'));
            }
            if (!isset($this->request->post['shoputils_psb_merchant_name']) || !trim($this->request->post['shoputils_psb_merchant_name'])) {
                $this->error['warning'] = $this->error['error_merchant_name'] = sprintf($this->language->get('error_form'),
                                                                                        $this->language->get('entry_merchant_name'),
                                                                                        $this->language->get('tab_settings'));
                $this->request->post['shoputils_psb_merchant_name'] = $this->config->get('config_name');
            }
            if (utf8_strlen(trim($this->request->post['shoputils_psb_merchant_name'])) > 50) {
                $this->error['warning'] = $this->error['error_merchant_name'] = sprintf($this->language->get('error_form_merchant_name'),
                                                                                        $this->language->get('entry_merchant_name'),
                                                                                        $this->language->get('tab_settings'), 50);
            }

            $this->load->model('localisation/language');
            foreach ($this->model_localisation_language->getLanguages() as $language) {
              if (($this->request->post['shoputils_psb_notify_customer_success']) && ((!isset($this->request->post['shoputils_psb_langdata'][$language['language_id']]['mail_customer_success_subject']) || !trim($this->request->post['shoputils_psb_langdata'][$language['language_id']]['mail_customer_success_subject'])))) {
                  $this->error['warning'] = $this->error['error_mail_customer_success_subject'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_customer_success_subject'),
                                                                                                          $this->language->get('tab_emails'));
              }
              if (($this->request->post['shoputils_psb_notify_customer_success']) && ((!isset($this->request->post['shoputils_psb_langdata'][$language['language_id']]['mail_customer_success_content']) || !$this->request->post['shoputils_psb_langdata'][$language['language_id']]['mail_customer_success_content']))) {
                  $this->error['warning'] = $this->error['error_mail_customer_success_content'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_customer_success_content'),
                                                                                                          $this->language->get('tab_emails'));
              }
              if (($this->request->post['shoputils_psb_notify_customer_fail']) && ((!isset($this->request->post['shoputils_psb_langdata'][$language['language_id']]['mail_customer_fail_subject']) || !trim($this->request->post['shoputils_psb_langdata'][$language['language_id']]['mail_customer_fail_subject'])))) {
                  $this->error['warning'] = $this->error['error_mail_customer_fail_subject'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_customer_fail_subject'),
                                                                                                          $this->language->get('tab_emails'));
              }
              if (($this->request->post['shoputils_psb_notify_customer_fail']) && ((!isset($this->request->post['shoputils_psb_langdata'][$language['language_id']]['mail_customer_fail_content']) || !$this->request->post['shoputils_psb_langdata'][$language['language_id']]['mail_customer_fail_content']))) {
                  $this->error['warning'] = $this->error['error_mail_customer_fail_content'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_customer_fail_content'),
                                                                                                          $this->language->get('tab_emails'));
              }
            }

            if (($this->request->post['shoputils_psb_notify_admin_success']) && ((!isset($this->request->post['shoputils_psb_mail_admin_success_subject']) || !trim($this->request->post['shoputils_psb_mail_admin_success_subject'])))) {
                $this->error['warning'] = $this->error['error_mail_admin_success_subject'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_admin_success_subject'),
                                                                                                          $this->language->get('tab_emails'));
            }
            if (($this->request->post['shoputils_psb_notify_admin_success']) && ((!isset($this->request->post['shoputils_psb_mail_admin_success_content']) || !$this->request->post['shoputils_psb_mail_admin_success_content']))) {
                $this->error['warning'] = $this->error['error_mail_admin_success_content'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_admin_success_content'),
                                                                                                          $this->language->get('tab_emails'));
            }
            if (($this->request->post['shoputils_psb_notify_admin_fail']) && ((!isset($this->request->post['shoputils_psb_mail_admin_fail_subject']) || !trim($this->request->post['shoputils_psb_mail_admin_fail_subject'])))) {
                $this->error['warning'] = $this->error['error_mail_admin_fail_subject'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_admin_fail_subject'),
                                                                                                          $this->language->get('tab_emails'));
            }
            if (($this->request->post['shoputils_psb_notify_admin_fail']) && ((!isset($this->request->post['shoputils_psb_mail_admin_fail_content']) || !$this->request->post['shoputils_psb_mail_admin_fail_content']))) {
                $this->error['warning'] = $this->error['error_mail_admin_fail_content'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_admin_fail_content'),
                                                                                                          $this->language->get('tab_emails'));
            }
        }

        return !$this->error;
    }

    protected function _setData($values) {
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $this->data[$value] = $this->language->get($value);
            } else {
                $this->data[$key] = $value;
            }
        }
    }

    protected function _updateData($keys, $info = array()) {
        foreach ($keys as $key) {
            if (isset($this->request->post[$key])) {
                $this->data[$key] = $this->request->post[$key];
            } elseif ($this->config->get($key)) {
                $this->data[$key] = $this->config->get($key);
            } elseif (isset($info[$key])) {
                $this->data[$key] = $info[$key];
            } else {
                $this->data[$key] = null;
            }
        }
    }

    function licShutdownHandler() {
        if (@is_array($e = @error_get_last())) {
            $code = isset($e['type']) ? $e['type'] : 0;
            $msg = isset($e['message']) ? $e['message'] : '';
            if(($code > 0) && (strpos($msg, 'requires a license file') || strpos($msg, 'is not valid for this server'))) {
                $this->session->data['warning'] = $this->language->get('error_key');
                $this->redirect($this->url->link('payment/shoputils_psb/lic', '&token=' . $this->session->data['token'], 'SSL'));
            }
        }
    }

    protected function readLastLines($filename, $lines) {
        if (!is_file($filename)) {
            return array();
        }
        $handle = @fopen($filename, "r");
        if (!$handle) {
            return array();
        }
        $linecounter = $lines;
        $pos = -1;
        $beginning = false;
        $text = array();

        while ($linecounter > 0) {
            $t = " ";

            while ($t != "\n") {
                /* if fseek() returns -1 we need to break the cycle*/
                if (fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos--;
            }

            $linecounter--;

            if ($beginning) {
                rewind($handle);
            }

            $text[$lines - $linecounter - 1] = fgets($handle);

            if ($beginning) {
                break;
            }
        }
        fclose($handle);

        return array_reverse($text);
    }
}
