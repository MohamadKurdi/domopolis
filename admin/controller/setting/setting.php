<?php
class ControllerSettingSetting extends Controller
{
    private $error = [];

    private $admin_modes = [
        'config_rainforest_asin_deletion_mode' => [
            'icon'      => 'fa-amazon',
            'btn_text'  => 'ASIN'
        ],
        'config_rainforest_variant_edition_mode' => [
            'icon'      => 'fa-amazon',
            'btn_text'  => 'VAR'
        ],
        'config_rainforest_translate_edition_mode' => [
            'icon'      => 'fa-refresh',
            'btn_text'  => 'TRNSL'
        ],
    ];
    
    public function getFPCINFO(){
        if (!$this->user->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
        } else {
            $this->load->model('setting/setting');
            $this->load->model('catalog/product');

            foreach ($this->admin_modes as $mode => $mode_config) {
                if (!isset($this->session->data[$mode])) {
                    $this->session->data[$mode] = $this->config->get($mode);
                }
            }

            if ($this->config->get('config_amazon_product_stats_enable')) {
                $this->data['totalProducts'] = formatLongNumber($this->model_catalog_product->getTotalProducts());
                $this->data['product_ext'] = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token']);

                if ($this->config->get('config_rainforest_default_technical_category_id')) {
                    $this->data['totalProductsInTechnicalCategory'] = formatLongNumber($this->model_catalog_product->getTotalProducts(['filter_category_id' => $this->config->get('config_rainforest_default_technical_category_id')]));
                }
            }


            if ($this->config->get('config_enable_amazon_specific_modes')) {
                foreach ($this->admin_modes as $mode => $mode_config) {
                    $this->data[$mode] = isset($this->session->data[$mode])?$this->session->data[$mode]:0;
                    $this->data['set_' . $mode] = $this->url->link('setting/setting/setworkmode', 'token=' . $this->session->data['token'] . '&mode=' . $mode);
                }
            }

            $this->data['admin_modes'] = $this->admin_modes;

            
            if (!$this->config->get('config_enable_highload_admin_mode') || $this->user->getUserGroup() == 1){
                $this->data['clearMemCache'] = $this->url->link('setting/setting/clearMemCache', 'token=' . $this->session->data['token']);                    

                $this->data['noPageCacheModeLink'] = $this->url->link('setting/setting/setNoPageCacheMode', 'token=' . $this->session->data['token']);
                $this->data['noPageCacheMode'] = file_exists(DIR_CACHE . PAGECACHE_DIR . 'nopagecache');

                if ($this->data['noPageCacheMode']) {
                    $this->data['noPageCacheModeDuration'] = time() - filemtime(DIR_CACHE . PAGECACHE_DIR . 'nopagecache');
                }

                $this->data['noPageCacheModeTTL'] = $this->PageCache->getTTL();
            }

            $this->data['panelLink'] = $this->url->link('common/panel', 'token=' . $this->session->data['token']);
       //     $this->data['serverResponceTime'] = $this->PageCache->getServerResponceTime();
            $this->data['redisMem']             = $this->PageCache->getRedisInfo();
            $this->data['pageCacheInfo']        = $this->PageCache->getPageCacheInfo();
            $this->data['refeedsCount']         = $this->PageCache->getReFeedsCount();
            $this->data['refeedsCountLink']     = $this->url->link('setting/feeds', 'token=' . $this->session->data['token']);

            
            $this->template = 'common/cachebuttons.tpl';
            
            $this->response->setOutput($this->render());
        }
    }

    public function clearMemCache(){
        if(!isset($this->session->data['token'])) $this->session->data['token'] = '';

        if (!$this->config->get('config_enable_highload_admin_mode') || $this->user->getUserGroup() == 1){

            $this->session->data['clear_memcache'] = true;

            if ($this->cache->flush()) {

                $this->load->model('user/user');                    
                $name = $this->model_user_user->getRealUserNameById($this->user->getID());

                $data = array(
                    'type' => 'warning',
                    'text' => $name . " очистил(а) временный кэш!", 
                    'entity_type' => '', 
                    'entity_id' => 0
                );

                $this->mAlert->insertAlertForGroup('admins', $data);
                $this->mAlert->insertAlertForGroup('contents', $data);

                echo 'ОК';        
            } else { 
                echo 'ERR';
            }

        } else {
            echo 'ERR';
        }
    }

    public function setworkmode(){
        $mode = $this->request->get['mode'];

        if (!empty($this->admin_modes[$mode])) {
            if ($this->session->data[$mode] == '1') {
                $this->session->data[$mode] = 0;
            } else {
                $this->session->data[$mode] = 1;
            }

                $this->response->setOutput($this->session->data[$mode]?'Вкл':'Выкл');
        } else {
            $this->response->setOutput('INVALID MODE');
        }
    }
    
    public function setNoPageCacheMode(){
        
        if ($this->user->getUserGroup() == 1) {
            $this->load->model('kp/bitrixBot');
            
            $enableNCM = false;
            
            if (file_exists(DIR_CACHE . PAGECACHE_DIR . 'nopagecache')) {
                @unlink(DIR_CACHE . PAGECACHE_DIR . 'nopagecache');
                $enableNCM = false;
            } else {
                @touch(DIR_CACHE . PAGECACHE_DIR . 'nopagecache');
                $enableNCM = true;
            }
            
            
            echo (file_exists(DIR_CACHE . PAGECACHE_DIR . 'nopagecache')?'Выкл':'Вкл');
        } else {
            echo 'Недоступно';
        }
    }
    
    public function setNoCacheMode(){
        
        if ($this->user->getUserGroup() == 1) {
            $enableNCM = false;
            
            if (file_exists(DIR_CACHE . BCACHE_DIR . 'nocache')) {
                @unlink(DIR_CACHE . BCACHE_DIR . 'nocache');
                $enableNCM = false;
            } else {
                @touch(DIR_CACHE . BCACHE_DIR . 'nocache');
                $enableNCM = true;
            }
            
            echo (file_exists(DIR_CACHE . BCACHE_DIR . 'nocache')?'Выкл':'Вкл');
        } else {
            echo 'Недоступно';
        }
    }
    
    public function editSettingAjax(){
        $store_id   = $this->request->get['store_id'];
        $key        = $this->request->post['key'];

        if (isset($this->request->post['value'])){
            $value      = $this->request->post['value'];
        } else {
            $value      = '';
        }

        if (!empty($this->request->post['js_serialized'])){
            parse_str(html_entity_decode($value), $result);
            if (is_array($result) && !empty($result[$key])){
                $value = $result[$key];
            }
        }

        $serialized = (int)is_array($value);
        $value      = (is_array($value))?serialize($value):$value;        
        $key        = trim($key, '[]');       

        if ($key) {
            $query = $this->db->query("SELECT * FROM setting WHERE store_id = '" . (int)$store_id . "' AND `group` = 'config' AND `key` = '" . $this->db->escape($key) . "'");

            if ($query->num_rows) {                
                $sql = "UPDATE setting SET `value` = '" . $this->db->escape($value) . "' WHERE `store_id` = '" . (int)$store_id . "' AND `group` = 'config' AND `key` = '" . $this->db->escape($key) . "'";
            } else {
                $sql = "INSERT INTO setting SET `value` = '" . $this->db->escape($value) . "', `store_id` = '" . (int)$store_id . "', `group` = 'config', `key` = '" . $this->db->escape($key) . "', serialized = '" . (int)$serialized . "'";
            }

            $query = $this->db->query($sql);
        }

        $this->response->setOutput($sql);
    }
        
    public function index(){
        $this->load->model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('config', $this->request->post);
            
            
            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['tabs'] = loadJSONConfig('setting_tabs');

        $this->data += $this->language->load('setting/setting');
        $this->document->setTitle($this->data['heading_title']);        
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = '';
        }
        
        if (isset($this->error['owner'])) {
            $this->data['error_owner'] = $this->error['owner'];
        } else {
            $this->data['error_owner'] = '';
        }
        
        if (isset($this->error['address'])) {
            $this->data['error_address'] = $this->error['address'];
        } else {
            $this->data['error_address'] = '';
        }
        
        if (isset($this->error['email'])) {
            $this->data['error_email'] = $this->error['email'];
        } else {
            $this->data['error_email'] = '';
        }
        
        if (isset($this->error['telephone'])) {
            $this->data['error_telephone'] = $this->error['telephone'];
        } else {
            $this->data['error_telephone'] = '';
        }
        
        if (isset($this->error['title'])) {
            $this->data['error_title'] = $this->error['title'];
        } else {
            $this->data['error_title'] = '';
        }
        
        if (isset($this->error['customer_group_display'])) {
            $this->data['error_customer_group_display'] = $this->error['customer_group_display'];
        } else {
            $this->data['error_customer_group_display'] = '';
        }
        
        if (isset($this->error['voucher_min'])) {
            $this->data['error_voucher_min'] = $this->error['voucher_min'];
        } else {
            $this->data['error_voucher_min'] = '';
        }
        
        if (isset($this->error['voucher_max'])) {
            $this->data['error_voucher_max'] = $this->error['voucher_max'];
        } else {
            $this->data['error_voucher_max'] = '';
        }
        
        if (isset($this->error['ftp_host'])) {
            $this->data['error_ftp_host'] = $this->error['ftp_host'];
        } else {
            $this->data['error_ftp_host'] = '';
        }
        
        if (isset($this->error['ftp_port'])) {
            $this->data['error_ftp_port'] = $this->error['ftp_port'];
        } else {
            $this->data['error_ftp_port'] = '';
        }
        
        if (isset($this->error['ftp_username'])) {
            $this->data['error_ftp_username'] = $this->error['ftp_username'];
        } else {
            $this->data['error_ftp_username'] = '';
        }
        
        if (isset($this->error['ftp_password'])) {
            $this->data['error_ftp_password'] = $this->error['ftp_password'];
        } else {
            $this->data['error_ftp_password'] = '';
        }
        
        if (isset($this->error['image_category'])) {
            $this->data['error_image_category'] = $this->error['image_category'];
        } else {
            $this->data['error_image_category'] = '';
        }
        
        if (isset($this->error['image_thumb'])) {
            $this->data['error_image_thumb'] = $this->error['image_thumb'];
        } else {
            $this->data['error_image_thumb'] = '';
        }
        
        if (isset($this->error['image_popup'])) {
            $this->data['error_image_popup'] = $this->error['image_popup'];
        } else {
            $this->data['error_image_popup'] = '';
        }
        
        if (isset($this->error['image_product'])) {
            $this->data['error_image_product'] = $this->error['image_product'];
        } else {
            $this->data['error_image_product'] = '';
        }
        
        if (isset($this->error['image_additional'])) {
            $this->data['error_image_additional'] = $this->error['image_additional'];
        } else {
            $this->data['error_image_additional'] = '';
        }
        
        if (isset($this->error['image_related'])) {
            $this->data['error_image_related'] = $this->error['image_related'];
        } else {
            $this->data['error_image_related'] = '';
        }
        
        if (isset($this->error['image_compare'])) {
            $this->data['error_image_compare'] = $this->error['image_compare'];
        } else {
            $this->data['error_image_compare'] = '';
        }
        
        if (isset($this->error['image_wishlist'])) {
            $this->data['error_image_wishlist'] = $this->error['image_wishlist'];
        } else {
            $this->data['error_image_wishlist'] = '';
        }
        
        if (isset($this->error['image_cart'])) {
            $this->data['error_image_cart'] = $this->error['image_cart'];
        } else {
            $this->data['error_image_cart'] = '';
        }
        
        if (isset($this->error['error_filename'])) {
            $this->data['error_error_filename'] = $this->error['error_filename'];
        } else {
            $this->data['error_error_filename'] = '';
        }
        
        if (isset($this->error['catalog_limit'])) {
            $this->data['error_catalog_limit'] = $this->error['catalog_limit'];
        } else {
            $this->data['error_catalog_limit'] = '';
        }
        
        if (isset($this->error['admin_limit'])) {
            $this->data['error_admin_limit'] = $this->error['admin_limit'];
        } else {
            $this->data['error_admin_limit'] = '';
        }
        
        if (isset($this->error['encryption'])) {
            $this->data['error_encryption'] = $this->error['encryption'];
        } else {
            $this->data['error_encryption'] = '';
        }
        
        $this->data['breadcrumbs'] = [];
        
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
            'separator' => false
        );
        
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('setting/setting', 'token=' . $this->session->data['token']),
            'separator' => ' :: '
        );
        
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
        
        $this->data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token']);
        
        $this->data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token']);
        
        $this->data['token'] = $this->session->data['token'];
        
        if (isset($this->request->post['config_name'])) {
            $this->data['config_name'] = $this->request->post['config_name'];
        } else {
            $this->data['config_name'] = $this->config->get('config_name');
        }

        if (isset($this->request->post['config_warmode_enable'])) {
            $this->data['config_warmode_enable'] = $this->request->post['config_warmode_enable'];
        } else {
            $this->data['config_warmode_enable'] = $this->config->get('config_warmode_enable');
        }

        if (isset($this->request->post['config_single_store_enable'])) {
            $this->data['config_single_store_enable'] = $this->request->post['config_single_store_enable'];
        } else {
            $this->data['config_single_store_enable'] = $this->config->get('config_single_store_enable');
        }

        if (isset($this->request->post['config_admin_flags_enable'])) {
            $this->data['config_admin_flags_enable'] = $this->request->post['config_admin_flags_enable'];
        } else {
            $this->data['config_admin_flags_enable'] = $this->config->get('config_admin_flags_enable');
        }

        if (isset($this->request->post['config_product_lists_text_in_orders'])) {
            $this->data['config_product_lists_text_in_orders'] = $this->request->post['config_product_lists_text_in_orders'];
        } else {
            $this->data['config_product_lists_text_in_orders'] = $this->config->get('config_product_lists_text_in_orders');
        }

        if (isset($this->request->post['config_enable_malert_in_admin'])) {
            $this->data['config_enable_malert_in_admin'] = $this->request->post['config_enable_malert_in_admin'];
        } else {
            $this->data['config_enable_malert_in_admin'] = $this->config->get('config_enable_malert_in_admin');
        }

        if (isset($this->request->post['config_special_logistics_enable'])) {
            $this->data['config_special_logistics_enable'] = $this->request->post['config_special_logistics_enable'];
        } else {
            $this->data['config_special_logistics_enable'] = $this->config->get('config_special_logistics_enable');
        }

        if (isset($this->request->post['config_no_zeroprice'])) {
            $this->data['config_no_zeroprice'] = $this->request->post['config_no_zeroprice'];
        } else {
            $this->data['config_no_zeroprice'] = $this->config->get('config_no_zeroprice');
        }

        if (isset($this->request->post['config_no_access_enable'])) {
            $this->data['config_no_access_enable'] = $this->request->post['config_no_access_enable'];
        } else {
            $this->data['config_no_access_enable'] = $this->config->get('config_no_access_enable');
        }
        
        if (isset($this->request->post['config_disable_empty_categories'])) {
            $this->data['config_disable_empty_categories'] = $this->request->post['config_disable_empty_categories'];
        } else {
            $this->data['config_disable_empty_categories'] = $this->config->get('config_disable_empty_categories');
        }

        if (isset($this->request->post['config_enable_non_empty_categories'])) {
            $this->data['config_enable_non_empty_categories'] = $this->request->post['config_enable_non_empty_categories'];
        } else {
            $this->data['config_enable_non_empty_categories'] = $this->config->get('config_enable_non_empty_categories');
        }

        if (isset($this->request->post['config_drop_special_prices_not_in_warehouses'])) {
            $this->data['config_drop_special_prices_not_in_warehouses'] = $this->request->post['config_drop_special_prices_not_in_warehouses'];
        } else {
            $this->data['config_drop_special_prices_not_in_warehouses'] = $this->config->get('config_drop_special_prices_not_in_warehouses');
        }

        if (isset($this->request->post['config_restore_special_prices_not_in_warehouses'])) {
            $this->data['config_restore_special_prices_not_in_warehouses'] = $this->request->post['config_restore_special_prices_not_in_warehouses'];
        } else {
            $this->data['config_restore_special_prices_not_in_warehouses'] = $this->config->get('config_restore_special_prices_not_in_warehouses');
        }

        if (isset($this->request->post['config_disable_fast_orders'])) {
            $this->data['config_disable_fast_orders'] = $this->request->post['config_disable_fast_orders'];
        } else {
            $this->data['config_disable_fast_orders'] = $this->config->get('config_disable_fast_orders');
        }

        if (isset($this->request->post['config_enable_form_bugfix_in_simplecheckout'])) {
            $this->data['config_enable_form_bugfix_in_simplecheckout'] = $this->request->post['config_enable_form_bugfix_in_simplecheckout'];
        } else {
            $this->data['config_enable_form_bugfix_in_simplecheckout'] = $this->config->get('config_enable_form_bugfix_in_simplecheckout');
        }

        if (isset($this->request->post['config_enable_do_not_call_in_simplecheckout'])) {
            $this->data['config_enable_do_not_call_in_simplecheckout'] = $this->request->post['config_enable_do_not_call_in_simplecheckout'];
        } else {
            $this->data['config_enable_do_not_call_in_simplecheckout'] = $this->config->get('config_enable_do_not_call_in_simplecheckout');
        }

        if (isset($this->request->post['config_enable_do_not_call_in_simplecheckout_only_full_in_stock'])) {
            $this->data['config_enable_do_not_call_in_simplecheckout_only_full_in_stock'] = $this->request->post['config_enable_do_not_call_in_simplecheckout_only_full_in_stock'];
        } else {
            $this->data['config_enable_do_not_call_in_simplecheckout_only_full_in_stock'] = $this->config->get('config_enable_do_not_call_in_simplecheckout_only_full_in_stock');
        }

        if (isset($this->request->post['config_ssl'])) {
            $this->data['config_ssl'] = $this->request->post['config_ssl'];
        } else {
            $this->data['config_ssl'] = $this->config->get('config_ssl');
        }
        
        if (isset($this->request->post['config_owner'])) {
            $this->data['config_owner'] = $this->request->post['config_owner'];
        } else {
            $this->data['config_owner'] = $this->config->get('config_owner');
        }
        
        if (isset($this->request->post['config_address'])) {
            $this->data['config_address'] = $this->request->post['config_address'];
        } else {
            $this->data['config_address'] = $this->config->get('config_address');
        }
        
        if (isset($this->request->post['config_popular_searches'])) {
            $this->data['config_popular_searches'] = $this->request->post['config_popular_searches'];
        } else {
            $this->data['config_popular_searches'] = $this->config->get('config_popular_searches');
        }
        
        if (isset($this->request->post['config_phonemask'])) {
            $this->data['config_phonemask'] = $this->request->post['config_phonemask'];
        } else {
            $this->data['config_phonemask'] = $this->config->get('config_phonemask');
        }

        $this->load->model('localisation/language');        
        $this->data['languages'] = $this->model_localisation_language->getLanguages();
        
        if (isset($this->request->post['config_default_city'])) {
            $this->data['config_default_city'] = $this->request->post['config_default_city'];
        } else {
            $this->data['config_default_city'] = $this->config->get('config_default_city');
        }

        foreach ($this->data['languages'] as $city_language){

            if (isset($this->request->post['config_default_city_' . $city_language['code']])) {
                $this->data['config_default_city_' . $city_language['code']] = $this->request->post['config_default_city_' . $city_language['code']];
            } else {
                $this->data['config_default_city_' . $city_language['code']] = $this->config->get('config_default_city_' . $city_language['code']);
            }

        }
        
        if (isset($this->request->post['config_email'])) {
            $this->data['config_email'] = $this->request->post['config_email'];
        } else {
            $this->data['config_email'] = $this->config->get('config_email');
        }
        
        if (isset($this->request->post['config_opt_email'])) {
            $this->data['config_opt_email'] = $this->request->post['config_opt_email'];
        } else {
            $this->data['config_opt_email'] = $this->config->get('config_opt_email');
        }
        
        if (isset($this->request->post['config_display_email'])) {
            $this->data['config_display_email'] = $this->request->post['config_display_email'];
        } else {
            $this->data['config_display_email'] = $this->config->get('config_display_email');
        }
        
        if (isset($this->request->post['config_telephone'])) {
            $this->data['config_telephone'] = $this->request->post['config_telephone'];
        } else {
            $this->data['config_telephone'] = $this->config->get('config_telephone');
        }        
        
        if (isset($this->request->post['config_telephone2'])) {
            $this->data['config_telephone2'] = $this->request->post['config_telephone2'];
        } else {
            $this->data['config_telephone2'] = $this->config->get('config_telephone2');
        }
        
        if (isset($this->request->post['config_telephone3'])) {
            $this->data['config_telephone3'] = $this->request->post['config_telephone3'];
        } else {
            $this->data['config_telephone3'] = $this->config->get('config_telephone3');
        }

        if (isset($this->request->post['config_telephone4'])) {
            $this->data['config_telephone4'] = $this->request->post['config_telephone4'];
        } else {
            $this->data['config_telephone4'] = $this->config->get('config_telephone4');
        }
                
        if (isset($this->request->post['config_opt_telephone'])) {
            $this->data['config_opt_telephone'] = $this->request->post['config_opt_telephone'];
        } else {
            $this->data['config_opt_telephone'] = $this->config->get('config_opt_telephone');
        }
        
        if (isset($this->request->post['config_opt_telephone2'])) {
            $this->data['config_opt_telephone2'] = $this->request->post['config_opt_telephone2'];
        } else {
            $this->data['config_opt_telephone2'] = $this->config->get('config_opt_telephone2');
        }
        
        if (isset($this->request->post['config_t_tt'])) {
            $this->data['config_t_tt'] = $this->request->post['config_t_tt'];
        } else {
            $this->data['config_t_tt'] = $this->config->get('config_t_tt');
        }
        
        if (isset($this->request->post['config_t2_tt'])) {
            $this->data['config_t2_tt'] = $this->request->post['config_t2_tt'];
        } else {
            $this->data['config_t2_tt'] = $this->config->get('config_t2_tt');
        }
        
        if (isset($this->request->post['config_t_bt'])) {
            $this->data['config_t_bt'] = $this->request->post['config_t_bt'];
        } else {
            $this->data['config_t_bt'] = $this->config->get('config_t_bt');
        }
        
        if (isset($this->request->post['config_t2_bt'])) {
            $this->data['config_t2_bt'] = $this->request->post['config_t2_bt'];
        } else {
            $this->data['config_t2_bt'] = $this->config->get('config_t2_bt');
        }
        
        if (isset($this->request->post['config_worktime'])) {
            $this->data['config_worktime'] = $this->request->post['config_worktime'];
        } else {
            $this->data['config_worktime'] = $this->config->get('config_worktime');
        }
        
        if (isset($this->request->post['config_fax'])) {
            $this->data['config_fax'] = $this->request->post['config_fax'];
        } else {
            $this->data['config_fax'] = $this->config->get('config_fax');
        }
        
        if (isset($this->request->post['config_social_auth'])) {
            $this->data['config_social_auth'] = $this->request->post['config_social_auth'];
        } else {
            $this->data['config_social_auth'] = $this->config->get('config_social_auth');
        }
        
        
        if (isset($this->request->post['config_title'])) {
            $this->data['config_title'] = $this->request->post['config_title'];
        } else {
            $this->data['config_title'] = $this->config->get('config_title');
        }
        
        if (isset($this->request->post['config_monobrand'])) {
            $this->data['config_monobrand'] = $this->request->post['config_monobrand'];
        } else {
            $this->data['config_monobrand'] = $this->config->get('config_monobrand');
        }

        if (isset($this->request->post['config_disable_filter_subcategory'])) {
            $this->data['config_disable_filter_subcategory'] = $this->request->post['config_disable_filter_subcategory'];
        } else {
            $this->data['config_disable_filter_subcategory'] = $this->config->get('config_disable_filter_subcategory');
        }

        if (isset($this->request->post['config_disable_filter_subcategory_only_for_main'])) {
            $this->data['config_disable_filter_subcategory_only_for_main'] = $this->request->post['config_disable_filter_subcategory_only_for_main'];
        } else {
            $this->data['config_disable_filter_subcategory_only_for_main'] = $this->config->get('config_disable_filter_subcategory_only_for_main');
        }
        
        if (isset($this->request->post['config_dadata'])) {
            $this->data['config_dadata'] = $this->request->post['config_dadata'];
        } else {
            $this->data['config_dadata'] = $this->config->get('config_dadata');
        }

        if (isset($this->request->post['config_dadata_api_key'])) {
            $this->data['config_dadata_api_key'] = $this->request->post['config_dadata_api_key'];
        } else {
            $this->data['config_dadata_api_key'] = $this->config->get('config_dadata_api_key');
        }

        if (isset($this->request->post['config_dadata_secret_key'])) {
            $this->data['config_dadata_secret_key'] = $this->request->post['config_dadata_secret_key'];
        } else {
            $this->data['config_dadata_secret_key'] = $this->config->get('config_dadata_secret_key');
        }

        if (isset($this->request->post['config_ip_api_enable'])) {
            $this->data['config_ip_api_enable'] = $this->request->post['config_ip_api_enable'];
        } else {
            $this->data['config_ip_api_enable'] = $this->config->get('config_ip_api_enable');
        }

        if (isset($this->request->post['config_ip_api_key'])) {
            $this->data['config_ip_api_key'] = $this->request->post['config_ip_api_key'];
        } else {
            $this->data['config_ip_api_key'] = $this->config->get('config_ip_api_key');
        }

        if (isset($this->request->post['config_zadarma_api_enable'])) {
            $this->data['config_zadarma_api_enable'] = $this->request->post['config_zadarma_api_enable'];
        } else {
            $this->data['config_zadarma_api_enable'] = $this->config->get('config_zadarma_api_enable');
        }

        if (isset($this->request->post['config_zadarma_api_key'])) {
            $this->data['config_zadarma_api_key'] = $this->request->post['config_zadarma_api_key'];
        } else {
            $this->data['config_zadarma_api_key'] = $this->config->get('config_zadarma_api_key');
        }

        if (isset($this->request->post['config_zadarma_secret_key'])) {
            $this->data['config_zadarma_secret_key'] = $this->request->post['config_zadarma_secret_key'];
        } else {
            $this->data['config_zadarma_secret_key'] = $this->config->get('config_zadarma_secret_key');
        }

        $this->data['smsgates'] = $this->smsAdaptor->getSmsGates();

        if (isset($this->request->post['config_smsgate_library'])) {
            $this->data['config_smsgate_library'] = $this->request->post['config_smsgate_library'];
        } else {
            $this->data['config_smsgate_library'] = $this->config->get('config_smsgate_library');
        }        

        if (isset($this->request->post['config_smsgate_library_enable_viber'])) {
            $this->data['config_smsgate_library_enable_viber'] = $this->request->post['config_smsgate_library_enable_viber'];
        } else {
            $this->data['config_smsgate_library_enable_viber'] = $this->config->get('config_smsgate_library_enable_viber');
        }

        if (isset($this->request->post['config_smsgate_library_enable_viber_fallback'])) {
            $this->data['config_smsgate_library_enable_viber_fallback'] = $this->request->post['config_smsgate_library_enable_viber_fallback'];
        } else {
            $this->data['config_smsgate_library_enable_viber_fallback'] = $this->config->get('config_smsgate_library_enable_viber_fallback');
        }

        if (isset($this->request->post['config_sms_editing_in_admin'])) {
            $this->data['config_sms_editing_in_admin'] = $this->request->post['config_sms_editing_in_admin'];
        } else {
            $this->data['config_sms_editing_in_admin'] = $this->config->get('config_sms_editing_in_admin');
        }

        if (isset($this->request->post['config_sms_status_use_only_settings'])) {
            $this->data['config_sms_status_use_only_settings'] = $this->request->post['config_sms_status_use_only_settings'];
        } else {
            $this->data['config_sms_status_use_only_settings'] = $this->config->get('config_sms_status_use_only_settings');
        }

        if (isset($this->request->post['config_smsgate_viber_auth_login'])) {
            $this->data['config_smsgate_viber_auth_login'] = $this->request->post['config_smsgate_viber_auth_login'];
        } else {
            $this->data['config_smsgate_viber_auth_login'] = $this->config->get('config_smsgate_viber_auth_login');
        }

        if (isset($this->request->post['config_smsgate_viber_auth_pwd'])) {
            $this->data['config_smsgate_viber_auth_pwd'] = $this->request->post['config_smsgate_viber_auth_pwd'];
        } else {
            $this->data['config_smsgate_viber_auth_pwd'] = $this->config->get('config_smsgate_viber_auth_pwd');
        }

        if (isset($this->request->post['config_smsgate_api_key'])) {
            $this->data['config_smsgate_api_key'] = $this->request->post['config_smsgate_api_key'];
        } else {
            $this->data['config_smsgate_api_key'] = $this->config->get('config_smsgate_api_key');
        }

        if (isset($this->request->post['config_smsgate_secret_key'])) {
            $this->data['config_smsgate_secret_key'] = $this->request->post['config_smsgate_secret_key'];
        } else {
            $this->data['config_smsgate_secret_key'] = $this->config->get('config_smsgate_secret_key');
        }

        if (isset($this->request->post['config_sms_from'])) {
            $this->data['config_sms_from'] = $this->request->post['config_sms_from'];
        } else {
            $this->data['config_sms_from'] = $this->config->get('config_sms_from');
        }

        if (isset($this->request->post['config_sms_sign'])) {
            $this->data['config_sms_sign'] = $this->request->post['config_sms_sign'];
        } else {
            $this->data['config_sms_sign'] = $this->config->get('config_sms_sign');
        }

        if (isset($this->request->post['config_viber_from'])) {
            $this->data['config_viber_from'] = $this->request->post['config_viber_from'];
        } else {
            $this->data['config_viber_from'] = $this->config->get('config_viber_from');
        }

        if (isset($this->request->post['config_smsgate_user'])) {
            $this->data['config_smsgate_user'] = $this->request->post['config_smsgate_user'];
        } else {
            $this->data['config_smsgate_user'] = $this->config->get('config_smsgate_user');
        }

        if (isset($this->request->post['config_smsgate_passwd'])) {
            $this->data['config_smsgate_passwd'] = $this->request->post['config_smsgate_passwd'];
        } else {
            $this->data['config_smsgate_passwd'] = $this->config->get('config_smsgate_passwd');
        }
        
        if (isset($this->request->post['config_show_goods_overload'])) {
            $this->data['config_show_goods_overload'] = $this->request->post['config_show_goods_overload'];
        } else {
            $this->data['config_show_goods_overload'] = $this->config->get('config_show_goods_overload');
        }
        
        if (isset($this->request->post['config_meta_description'])) {
            $this->data['config_meta_description'] = $this->request->post['config_meta_description'];
        } else {
            $this->data['config_meta_description'] = $this->config->get('config_meta_description');
        }
        
        if (isset($this->request->post['config_layout_id'])) {
            $this->data['config_layout_id'] = $this->request->post['config_layout_id'];
        } else {
            $this->data['config_layout_id'] = $this->config->get('config_layout_id');
        }
        
        $this->load->model('design/layout');
        
        $this->data['layouts'] = $this->model_design_layout->getLayouts();
        
        if (isset($this->request->post['config_template'])) {
            $this->data['config_template'] = $this->request->post['config_template'];
        } else {
            $this->data['config_template'] = $this->config->get('config_template');
        }
        
        $this->data['templates'] = $this->templateLib->getTemplates();               
        
        if (isset($this->request->post['config_country_id'])) {
            $this->data['config_country_id'] = $this->request->post['config_country_id'];
        } else {
            $this->data['config_country_id'] = $this->config->get('config_country_id');
        }

        if (isset($this->request->post['config_only_one_store_and_country'])) {
            $this->data['config_only_one_store_and_country'] = $this->request->post['config_only_one_store_and_country'];
        } else {
            $this->data['config_only_one_store_and_country'] = $this->config->get('config_only_one_store_and_country');
        }
        
        $this->load->model('localisation/country');
        $this->data['countries'] = $this->model_localisation_country->getCountries();
        
        if (isset($this->request->post['config_zone_id'])) {
            $this->data['config_zone_id'] = $this->request->post['config_zone_id'];
        } else {
            $this->data['config_zone_id'] = $this->config->get('config_zone_id');
        }

        $this->load->model('localisation/zone');
        $this->data['zones'] = $this->model_localisation_zone->getZonesByCountryId($this->data['config_country_id']);
        
        if (isset($this->request->post['config_countryname'])) {
            $this->data['config_countryname'] = $this->request->post['config_countryname'];
        } else {
            $this->data['config_countryname'] = $this->config->get('config_countryname');
        }
        
        if (isset($this->request->post['config_googlelocal_code'])) {
            $this->data['config_googlelocal_code'] = $this->request->post['config_googlelocal_code'];
        } else {
            $this->data['config_googlelocal_code'] = $this->config->get('config_googlelocal_code');
        }
        
        if (isset($this->request->post['config_warehouse_identifier'])) {
            $this->data['config_warehouse_identifier'] = $this->request->post['config_warehouse_identifier'];
        } else {
            $this->data['config_warehouse_identifier'] = $this->config->get('config_warehouse_identifier');
        }
        
        if (isset($this->request->post['config_warehouse_identifier_local'])) {
            $this->data['config_warehouse_identifier_local'] = $this->request->post['config_warehouse_identifier_local'];
        } else {
            $this->data['config_warehouse_identifier_local'] = $this->config->get('config_warehouse_identifier_local');
        }
        
        if (isset($this->request->post['config_warehouse_only'])) {
            $this->data['config_warehouse_only'] = $this->request->post['config_warehouse_only'];
        } else {
            $this->data['config_warehouse_only'] = $this->config->get('config_warehouse_only');
        }

        if (isset($this->request->post['config_overload_stock_status_id'])) {
            $this->data['config_overload_stock_status_id'] = $this->request->post['config_overload_stock_status_id'];
        } else {
            $this->data['config_overload_stock_status_id'] = $this->config->get('config_overload_stock_status_id');
        }

        if (isset($this->request->post['config_payment_list'])) {
            $this->data['config_payment_list'] = $this->request->post['config_payment_list'];
        } else {
            $this->data['config_payment_list'] = $this->config->get('config_payment_list');
        }
        
        if (isset($this->request->post['config_language'])) {
            $this->data['config_language'] = $this->request->post['config_language'];
        } else {
            $this->data['config_language'] = $this->config->get('config_language');
        }
        
        if (isset($this->request->post['config_second_language'])) {
            $this->data['config_second_language'] = $this->request->post['config_second_language'];
        } else {
            $this->data['config_second_language'] = $this->config->get('config_second_language');
        }               
        
        if (isset($this->request->post['config_admin_language'])) {
            $this->data['config_admin_language'] = $this->request->post['config_admin_language'];
        } else {
            $this->data['config_admin_language'] = $this->config->get('config_admin_language');
        }
        
        if (isset($this->request->post['config_de_language'])) {
            $this->data['config_de_language'] = $this->request->post['config_de_language'];
        } else {
            $this->data['config_de_language'] = $this->config->get('config_de_language');
        }
        
        if (isset($this->request->post['config_translate_from_ru'])) {
            $this->data['config_translate_from_ru'] = $this->request->post['config_translate_from_ru'];
        } else {
            $this->data['config_translate_from_ru'] = $this->config->get('config_translate_from_ru');
        }

        if (isset($this->request->post['config_translate_from_de'])) {
            $this->data['config_translate_from_de'] = $this->request->post['config_translate_from_de'];
        } else {
            $this->data['config_translate_from_de'] = $this->config->get('config_translate_from_de');
        }

        if (isset($this->request->post['config_translate_from_uk'])) {
            $this->data['config_translate_from_uk'] = $this->request->post['config_translate_from_uk'];
        } else {
            $this->data['config_translate_from_uk'] = $this->config->get('config_translate_from_uk');
        }

        if (isset($this->request->post['config_edit_simultaneously'])) {
            $this->data['config_edit_simultaneously'] = $this->request->post['config_edit_simultaneously'];
        } else {
            $this->data['config_edit_simultaneously'] = $this->config->get('config_edit_simultaneously');
        }

        if (isset($this->request->post['config_enable_overprice'])) {
            $this->data['config_enable_overprice'] = $this->request->post['config_enable_overprice'];
        } else {
            $this->data['config_enable_overprice'] = $this->config->get('config_enable_overprice');
        }
        
        if (isset($this->request->post['config_overprice'])) {
            $this->data['config_overprice'] = $this->request->post['config_overprice'];
        } else {
            $this->data['config_overprice'] = $this->config->get('config_overprice');
        }
        
        if (isset($this->request->post['config_currency'])) {
            $this->data['config_currency'] = $this->request->post['config_currency'];
        } else {
            $this->data['config_currency'] = $this->config->get('config_currency');
        }
        
        if (isset($this->request->post['config_regional_currency'])) {
            $this->data['config_regional_currency'] = $this->request->post['config_regional_currency'];
        } else {
            $this->data['config_regional_currency'] = $this->config->get('config_regional_currency');
        }
        
        if (isset($this->request->post['config_currency_auto'])) {
            $this->data['config_currency_auto'] = $this->request->post['config_currency_auto'];
        } else {
            $this->data['config_currency_auto'] = $this->config->get('config_currency_auto');
        }
            
        //Добавить меню как child на homepage
        if (isset($this->request->post['config_mmenu_on_homepage'])) {
            $this->data['config_mmenu_on_homepage'] = $this->request->post['config_mmenu_on_homepage'];
        } else {
            $this->data['config_mmenu_on_homepage'] = $this->config->get('config_mmenu_on_homepage');
        }

        if (isset($this->request->post['config_brands_in_mmenu'])) {
            $this->data['config_brands_in_mmenu'] = $this->request->post['config_brands_in_mmenu'];
        } else {
            $this->data['config_brands_in_mmenu'] = $this->config->get('config_brands_in_mmenu');
        }

        if (isset($this->request->post['config_brands_on_homepage'])) {
            $this->data['config_brands_on_homepage'] = $this->request->post['config_brands_on_homepage'];
        } else {
            $this->data['config_brands_on_homepage'] = $this->config->get('config_brands_on_homepage');
        }

        if (isset($this->request->post['config_bestsellers_in_mmenu'])) {
            $this->data['config_bestsellers_in_mmenu'] = $this->request->post['config_bestsellers_in_mmenu'];
        } else {
            $this->data['config_bestsellers_in_mmenu'] = $this->config->get('config_bestsellers_in_mmenu');
        }
        
        $this->load->model('localisation/currency');
        
        $this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
        
        if (isset($this->request->post['config_length_class_id'])) {
            $this->data['config_length_class_id'] = $this->request->post['config_length_class_id'];
        } else {
            $this->data['config_length_class_id'] = $this->config->get('config_length_class_id');
        }
        
        $this->load->model('localisation/length_class');        
        $this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

        if (isset($this->request->post['config_convert_lengths_class_id'])) {
            $this->data['config_convert_lengths_class_id'] = $this->request->post['config_convert_lengths_class_id'];
        } else {
            $this->data['config_convert_lengths_class_id'] = $this->config->get('config_convert_lengths_class_id');
        }
        
        if (isset($this->request->post['config_weight_class_id'])) {
            $this->data['config_weight_class_id'] = $this->request->post['config_weight_class_id'];
        } else {
            $this->data['config_weight_class_id'] = $this->config->get('config_weight_class_id');
        }
        
        $this->load->model('localisation/weight_class');        
        $this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

        if (isset($this->request->post['config_convert_weights_class_id'])) {
            $this->data['config_convert_weights_class_id'] = $this->request->post['config_convert_weights_class_id'];
        } else {
            $this->data['config_convert_weights_class_id'] = $this->config->get('config_convert_weights_class_id');
        }
        
        if (isset($this->request->post['config_review_good'])) {
            $this->data['config_review_good'] = $this->request->post['config_review_good'];
        } else {
            $this->data['config_review_good'] = $this->config->get('config_review_good');
        }
        if (isset($this->request->post['config_review_bad'])) {
            $this->data['config_review_bad'] = $this->request->post['config_review_bad'];
        } else {
            $this->data['config_review_bad'] = $this->config->get('config_review_bad');
        }
        if (isset($this->request->post['config_review_addimage'])) {
            $this->data['config_review_addimage'] = $this->request->post['config_review_addimage'];
        } else {
            $this->data['config_review_addimage'] = $this->config->get('config_review_addimage');
        }
        if (isset($this->request->post['config_review_captcha'])) {
            $this->data['config_review_captcha'] = $this->request->post['config_review_captcha'];
        } else {
            $this->data['config_review_captcha'] = $this->config->get('config_review_captcha');
        }
        if (isset($this->request->post['config_review_statusp'])) {
            $this->data['config_review_statusp'] = $this->request->post['config_review_statusp'];
        } else {
            $this->data['config_review_statusp'] = $this->config->get('config_review_statusp');
        }
        if (isset($this->request->post['config_review_email'])) {
            $this->data['config_review_email'] = $this->request->post['config_review_email'];
        } else {
            $this->data['config_review_email'] = $this->config->get('config_review_email');
        }
        if (isset($this->request->post['config_review_text_symbol'])) {
            $this->data['config_review_text_symbol'] = $this->request->post['config_review_text_symbol'];
        } else {
            $this->data['config_review_text_symbol'] = $this->config->get('config_review_text_symbol');
        }

        if (isset($this->request->post['config_onereview_amount'])) {
            $this->data['config_onereview_amount'] = $this->request->post['config_onereview_amount'];
        } else {
            $this->data['config_onereview_amount'] = $this->config->get('config_onereview_amount');
        }
        
        if (isset($this->request->post['config_reward_logosvg'])) {
            $this->data['config_reward_logosvg'] = $this->request->post['config_reward_logosvg'];
        } else {
            $this->data['config_reward_logosvg'] = $this->config->get('config_reward_logosvg');
        }
        
        if (isset($this->request->post['config_reward_lifetime'])) {
            $this->data['config_reward_lifetime'] = $this->request->post['config_reward_lifetime'];
        } else {
            $this->data['config_reward_lifetime'] = $this->config->get('config_reward_lifetime');
        }
        
        if (isset($this->request->post['config_reward_maxsalepercent'])) {
            $this->data['config_reward_maxsalepercent'] = $this->request->post['config_reward_maxsalepercent'];
        } else {
            $this->data['config_reward_maxsalepercent'] = $this->config->get('config_reward_maxsalepercent');
        }
        
        if (isset($this->request->post['rewardpoints_currency_mode'])) {
            $this->data['rewardpoints_currency_mode'] = $this->request->post['rewardpoints_currency_mode'];
        } else {
            $this->data['rewardpoints_currency_mode'] = $this->config->get('rewardpoints_currency_mode');
        }
        
        if (isset($this->request->post['rewardpoints_currency_prefix'])) {
            $this->data['rewardpoints_currency_prefix'] = $this->request->post['rewardpoints_currency_prefix'];
        } else {
            $this->data['rewardpoints_currency_prefix'] = $this->config->get('rewardpoints_currency_prefix');
        }
        
        if (isset($this->request->post['rewardpoints_currency_suffix'])) {
            $this->data['rewardpoints_currency_suffix'] = $this->request->post['rewardpoints_currency_suffix'];
        } else {
            $this->data['rewardpoints_currency_suffix'] = $this->config->get('rewardpoints_currency_suffix');
        }
        
        if (isset($this->request->post['rewardpoints_pointspercent'])) {
            $this->data['rewardpoints_pointspercent'] = $this->request->post['rewardpoints_pointspercent'];
        } else {
            $this->data['rewardpoints_pointspercent'] = $this->config->get('rewardpoints_pointspercent');
        }
        
        if (isset($this->request->post['rewardpoints_appinstall'])) {
            $this->data['rewardpoints_appinstall'] = $this->request->post['rewardpoints_appinstall'];
        } else {
            $this->data['rewardpoints_appinstall'] = $this->config->get('rewardpoints_appinstall');
        }
        
        if (isset($this->request->post['rewardpoints_birthday'])) {
            $this->data['rewardpoints_birthday'] = $this->request->post['rewardpoints_birthday'];
        } else {
            $this->data['rewardpoints_birthday'] = $this->config->get('rewardpoints_birthday');
        }

        if (isset($this->request->post['rewardpoints_birthday_days_to'])) {
            $this->data['rewardpoints_birthday_days_to'] = $this->request->post['rewardpoints_birthday_days_to'];
        } else {
            $this->data['rewardpoints_birthday_days_to'] = $this->config->get('rewardpoints_birthday_days_to');
        }

        if (isset($this->request->post['rewardpoints_review'])) {
            $this->data['rewardpoints_review'] = $this->request->post['rewardpoints_review'];
        } else {
            $this->data['rewardpoints_review'] = $this->config->get('rewardpoints_review');
        }

        if (isset($this->request->post['rewardpoints_review_need_image'])) {
            $this->data['rewardpoints_review_need_image'] = $this->request->post['rewardpoints_review_need_image'];
        } else {
            $this->data['rewardpoints_review_need_image'] = $this->config->get('rewardpoints_review_need_image');
        }

        if (isset($this->request->post['rewardpoints_review_min_length'])) {
            $this->data['rewardpoints_review_min_length'] = $this->request->post['rewardpoints_review_min_length'];
        } else {
            $this->data['rewardpoints_review_min_length'] = $this->config->get('rewardpoints_review_min_length');
        }

         if (isset($this->request->post['rewardpoints_needs_purchased'])) {
            $this->data['rewardpoints_needs_purchased'] = $this->request->post['rewardpoints_needs_purchased'];
        } else {
            $this->data['rewardpoints_needs_purchased'] = $this->config->get('rewardpoints_needs_purchased');
        }

        if (isset($this->request->post['rewardpoints_review_days'])) {
            $this->data['rewardpoints_review_days'] = $this->request->post['rewardpoints_review_days'];
        } else {
            $this->data['rewardpoints_review_days'] = $this->config->get('rewardpoints_review_days');
        }

        if (isset($this->request->post['rewardpoints_reminder_enable'])) {
            $this->data['rewardpoints_reminder_enable'] = $this->request->post['rewardpoints_reminder_enable'];
        } else {
            $this->data['rewardpoints_reminder_enable'] = $this->config->get('rewardpoints_reminder_enable');
        }

        if (isset($this->request->post['rewardpoints_reminder_days_left'])) {
            $this->data['rewardpoints_reminder_days_left'] = $this->request->post['rewardpoints_reminder_days_left'];
        } else {
            $this->data['rewardpoints_reminder_days_left'] = $this->config->get('rewardpoints_reminder_days_left');
        }

        if (isset($this->request->post['rewardpoints_reminder_min_amount'])) {
            $this->data['rewardpoints_reminder_min_amount'] = $this->request->post['rewardpoints_reminder_min_amount'];
        } else {
            $this->data['rewardpoints_reminder_min_amount'] = $this->config->get('rewardpoints_reminder_min_amount');
        }

        if (isset($this->request->post['rewardpoints_reminder_days_noactive'])) {
            $this->data['rewardpoints_reminder_days_noactive'] = $this->request->post['rewardpoints_reminder_days_noactive'];
        } else {
            $this->data['rewardpoints_reminder_days_noactive'] = $this->config->get('rewardpoints_reminder_days_noactive');
        }

        if (isset($this->request->post['rewardpoints_reminder_sms_text'])) {
            $this->data['rewardpoints_reminder_sms_text'] = $this->request->post['rewardpoints_reminder_sms_text'];
        } else {
            $this->data['rewardpoints_reminder_sms_text'] = $this->config->get('rewardpoints_reminder_sms_text');
        }

        if (isset($this->request->post['rewardpoints_added_sms_enable'])) {
            $this->data['rewardpoints_added_sms_enable'] = $this->request->post['rewardpoints_added_sms_enable'];
        } else {
            $this->data['rewardpoints_added_sms_enable'] = $this->config->get('rewardpoints_added_sms_enable');
        }

        if (isset($this->request->post['rewardpoints_added_sms_text'])) {
            $this->data['rewardpoints_added_sms_text'] = $this->request->post['rewardpoints_added_sms_text'];
        } else {
            $this->data['rewardpoints_added_sms_text'] = $this->config->get('rewardpoints_added_sms_text');
        }

        $reward_overload_keys = [
            'config_reward_overload_product',
            'config_reward_overload_collection',
            'config_reward_overload_manufacturer',
            'config_reward_overload_category'
        ];
        
        foreach ($reward_overload_keys as $reward_overload_key) {
            if (isset($this->request->post[$reward_overload_key])) {
                $this->data[$reward_overload_key] = $this->request->post[$reward_overload_key];
            } else {
                $this->data[$reward_overload_key] = $this->config->get($reward_overload_key);
            }
        }

        
        $termskeys = [
            'config_delivery_instock_term',
            'config_delivery_central_term',
            'config_delivery_russia_term',
            'config_delivery_ukrainian_term',
            'config_delivery_outstock_term',
            'config_delivery_outstock_enable',
            'config_order_bottom_text_enable',
            'config_divide_cart_by_stock',
            'config_display_dt_preorder_text',
            'config_delivery_display_logic'
        ];
        
        foreach ($termskeys as $termkey) {
            if (isset($this->request->post[$termkey])) {
                $this->data[$termkey] = $this->request->post[$termkey];
            } else {
                $this->data[$termkey] = $this->config->get($termkey);
            }
        }
        
        if (isset($this->request->post['config_pickup_enable'])) {
            $this->data['config_pickup_enable'] = $this->request->post['config_pickup_enable'];
        } else {
            $this->data['config_pickup_enable'] = $this->config->get('config_pickup_enable');
        }
        
        if (isset($this->request->post['config_pickup_times'])) {
            $this->data['config_pickup_times'] = $this->request->post['config_pickup_times'];
        } else {
            $this->data['config_pickup_times'] = $this->config->get('config_pickup_times');
        }
        
        for ($i=1; $i<=12; $i++) {
            if (isset($this->request->post['config_pickup_dayoff_' . $i])) {
                $this->data['config_pickup_dayoff_' . $i] = $this->request->post['config_pickup_dayoff_' . $i];
            } else {
                $this->data['config_pickup_dayoff_' . $i] = $this->config->get('config_pickup_dayoff_' . $i);
            }
        }
         
        if (isset($this->request->post['config_cdek_api_login'])) {
            $this->data['config_cdek_api_login'] = $this->request->post['config_cdek_api_login'];
        } else {
            $this->data['config_cdek_api_login'] = $this->config->get('config_cdek_api_login');
        }

        if (isset($this->request->post['config_cdek_api_key'])) {
            $this->data['config_cdek_api_key'] = $this->request->post['config_cdek_api_key'];
        } else {
            $this->data['config_cdek_api_key'] = $this->config->get('config_cdek_api_key');
        }

        if (isset($this->request->post['config_cdek_calculate_on_checkout'])) {
            $this->data['config_cdek_calculate_on_checkout'] = $this->request->post['config_cdek_calculate_on_checkout'];
        } else {
            $this->data['config_cdek_calculate_on_checkout'] = $this->config->get('config_cdek_calculate_on_checkout');
        }

        $this->data['cdek_tariffs'] = [];
        if ($this->config->get('config_country_id') == 176 && $this->data['config_cdek_api_key'] && $this->config->get('config_cdek_api_city_sender_id')){
            try{

            $CdekClient = new \AntistressStore\CdekSDK2\CdekClientV2($this->data['config_cdek_api_login'], $this->data['config_cdek_api_key']);
            $tariff     = (new \AntistressStore\CdekSDK2\Entity\Requests\Tariff())->setCityCodes($this->config->get('config_cdek_api_city_sender_id'), 137)->setPackageWeight(500);
            $tariffList = $CdekClient->calculateTariffList($tariff);

            foreach ($tariffList as $result) {
                $this->data['cdek_tariffs'][] = [
                    'code' => $result->getTariffCode(),
                    'name' => $result->getTariffName(),
                ];
            }
            } catch (\GuzzleHttp\Exception\ConnectException $e){
                
            }
        }

        if (isset($this->request->post['config_cdek_api_default_tariff_doors'])) {
            $this->data['config_cdek_api_default_tariff_doors'] = $this->request->post['config_cdek_api_default_tariff_doors'];
        } else {
            $this->data['config_cdek_api_default_tariff_doors'] = $this->config->get('config_cdek_api_default_tariff_doors');
        }

        if (isset($this->request->post['config_cdek_api_default_tariff_warehouse'])) {
            $this->data['config_cdek_api_default_tariff_warehouse'] = $this->request->post['config_cdek_api_default_tariff_warehouse'];
        } else {
            $this->data['config_cdek_api_default_tariff_warehouse'] = $this->config->get('config_cdek_api_default_tariff_warehouse');
        }

         if (isset($this->request->post['config_cdek_api_city_sender_id'])) {
            $this->data['config_cdek_api_city_sender_id'] = $this->request->post['config_cdek_api_city_sender_id'];
        } else {
            $this->data['config_cdek_api_city_sender_id'] = $this->config->get('config_cdek_api_city_sender_id');
        }


        if (isset($this->request->post['config_novaposhta_api_key'])) {
            $this->data['config_novaposhta_api_key'] = $this->request->post['config_novaposhta_api_key'];
        } else {
            $this->data['config_novaposhta_api_key'] = $this->config->get('config_novaposhta_api_key');
        }

        if (isset($this->request->post['config_novaposhta_default_city_guid'])) {
            $this->data['config_novaposhta_default_city_guid'] = $this->request->post['config_novaposhta_default_city_guid'];
        } else {
            $this->data['config_novaposhta_default_city_guid'] = $this->config->get('config_novaposhta_default_city_guid');
        }

        if (isset($this->request->post['config_novaposhta_ru_language'])) {
            $this->data['config_novaposhta_ru_language'] = $this->request->post['config_novaposhta_ru_language'];
        } else {
            $this->data['config_novaposhta_ru_language'] = $this->config->get('config_novaposhta_ru_language');
        }

        if (isset($this->request->post['config_novaposhta_ua_language'])) {
            $this->data['config_novaposhta_ua_language'] = $this->request->post['config_novaposhta_ua_language'];
        } else {
            $this->data['config_novaposhta_ua_language'] = $this->config->get('config_novaposhta_ua_language');
        }

        if (isset($this->request->post['config_justin_api_key'])) {
            $this->data['config_justin_api_key'] = $this->request->post['config_justin_api_key'];
        } else {
            $this->data['config_justin_api_key'] = $this->config->get('config_justin_api_key');
        }

        if (isset($this->request->post['config_justin_api_login'])) {
            $this->data['config_justin_api_login'] = $this->request->post['config_justin_api_login'];
        } else {
            $this->data['config_justin_api_login'] = $this->config->get('config_justin_api_login');
        }

        if (isset($this->request->post['config_justin_ru_language'])) {
            $this->data['config_justin_ru_language'] = $this->request->post['config_justin_ru_language'];
        } else {
            $this->data['config_justin_ru_language'] = $this->config->get('config_justin_ru_language');
        }

        if (isset($this->request->post['config_justin_ua_language'])) {
            $this->data['config_justin_ua_language'] = $this->request->post['config_justin_ua_language'];
        } else {
            $this->data['config_justin_ua_language'] = $this->config->get('config_justin_ua_language');
        }

        if (isset($this->request->post['config_ukrposhta_api_bearer'])) {
            $this->data['config_ukrposhta_api_bearer'] = $this->request->post['config_ukrposhta_api_bearer'];
        } else {
            $this->data['config_ukrposhta_api_bearer'] = $this->config->get('config_ukrposhta_api_bearer');
        }

        if (isset($this->request->post['config_ukrposhta_api_token'])) {
            $this->data['config_ukrposhta_api_token'] = $this->request->post['config_ukrposhta_api_token'];
        } else {
            $this->data['config_ukrposhta_api_token'] = $this->config->get('config_ukrposhta_api_token');
        }

        if (isset($this->request->post['config_ukrposhta_ru_language'])) {
            $this->data['config_ukrposhta_ru_language'] = $this->request->post['config_ukrposhta_ru_language'];
        } else {
            $this->data['config_ukrposhta_ru_language'] = $this->config->get('config_ukrposhta_ru_language');
        }

        if (isset($this->request->post['config_ukrposhta_ua_language'])) {
            $this->data['config_ukrposhta_ua_language'] = $this->request->post['config_ukrposhta_ua_language'];
        } else {
            $this->data['config_ukrposhta_ua_language'] = $this->config->get('config_ukrposhta_ua_language');
        }

        if (isset($this->request->post['config_amazon_product_stats_enable'])) {
            $this->data['config_amazon_product_stats_enable'] = $this->request->post['config_amazon_product_stats_enable'];
        } else {
            $this->data['config_amazon_product_stats_enable'] = $this->config->get('config_amazon_product_stats_enable');
        }

        if (isset($this->request->post['config_amazon_profitability_in_stocks'])) {
            $this->data['config_amazon_profitability_in_stocks'] = $this->request->post['config_amazon_profitability_in_stocks'];
        } else {
            $this->data['config_amazon_profitability_in_stocks'] = $this->config->get('config_amazon_profitability_in_stocks');
        }

        if (isset($this->request->post['config_load_ocfilter_in_product'])) {
            $this->data['config_load_ocfilter_in_product'] = $this->request->post['config_load_ocfilter_in_product'];
        } else {
            $this->data['config_load_ocfilter_in_product'] = $this->config->get('config_load_ocfilter_in_product');
        }

        if (isset($this->request->post['config_delete_products_images_enable'])) {
            $this->data['config_delete_products_images_enable'] = $this->request->post['config_delete_products_images_enable'];
        } else {
            $this->data['config_delete_products_images_enable'] = $this->config->get('config_delete_products_images_enable');
        }

        if (isset($this->request->post['config_never_delete_products_in_orders'])) {
            $this->data['config_never_delete_products_in_orders'] = $this->request->post['config_never_delete_products_in_orders'];
        } else {
            $this->data['config_never_delete_products_in_orders'] = $this->config->get('config_never_delete_products_in_orders');
        }

        if (isset($this->request->post['config_never_delete_products_in_warehouse'])) {
            $this->data['config_never_delete_products_in_warehouse'] = $this->request->post['config_never_delete_products_in_warehouse'];
        } else {
            $this->data['config_never_delete_products_in_warehouse'] = $this->config->get('config_never_delete_products_in_warehouse');
        }

        if (isset($this->request->post['config_cron_stats_display_enable'])) {
            $this->data['config_cron_stats_display_enable'] = $this->request->post['config_cron_stats_display_enable'];
        } else {
            $this->data['config_cron_stats_display_enable'] = $this->config->get('config_cron_stats_display_enable');
        }
        
        if (isset($this->request->post['config_group_price_enable'])) {
            $this->data['config_group_price_enable'] = $this->request->post['config_group_price_enable'];
        } else {
            $this->data['config_group_price_enable'] = $this->config->get('config_group_price_enable');
        }

        if (isset($this->request->post['config_option_price_enable'])) {
            $this->data['config_option_price_enable'] = $this->request->post['config_option_price_enable'];
        } else {
            $this->data['config_option_price_enable'] = $this->config->get('config_option_price_enable');
        }

        if (isset($this->request->post['config_option_products_enable'])) {
            $this->data['config_option_products_enable'] = $this->request->post['config_option_products_enable'];
        } else {
            $this->data['config_option_products_enable'] = $this->config->get('config_option_products_enable');
        }

        if (isset($this->request->post['config_additional_html_status_enable'])) {
            $this->data['config_additional_html_status_enable'] = $this->request->post['config_additional_html_status_enable'];
        } else {
            $this->data['config_additional_html_status_enable'] = $this->config->get('config_additional_html_status_enable');
        }

        if (isset($this->request->post['config_color_grouping_products_enable'])) {
            $this->data['config_color_grouping_products_enable'] = $this->request->post['config_color_grouping_products_enable'];
        } else {
            $this->data['config_color_grouping_products_enable'] = $this->config->get('config_color_grouping_products_enable');
        }

        if (isset($this->request->post['config_product_downloads_enable'])) {
            $this->data['config_product_downloads_enable'] = $this->request->post['config_product_downloads_enable'];
        } else {
            $this->data['config_product_downloads_enable'] = $this->config->get('config_product_downloads_enable');
        }

        if (isset($this->request->post['config_product_options_enable'])) {
            $this->data['config_product_options_enable'] = $this->request->post['config_product_options_enable'];
        } else {
            $this->data['config_product_options_enable'] = $this->config->get('config_product_options_enable');
        }

        if (isset($this->request->post['config_product_profiles_enable'])) {
            $this->data['config_product_profiles_enable'] = $this->request->post['config_product_profiles_enable'];
        } else {
            $this->data['config_product_profiles_enable'] = $this->config->get('config_product_profiles_enable');
        }

        if (isset($this->request->post['config_product_alsoviewed_enable'])) {
            $this->data['config_product_alsoviewed_enable'] = $this->request->post['config_product_alsoviewed_enable'];
        } else {
            $this->data['config_product_alsoviewed_enable'] = $this->config->get('config_product_alsoviewed_enable');
        }

        if (isset($this->request->post['config_description_in_lists'])) {
            $this->data['config_description_in_lists'] = $this->request->post['config_description_in_lists'];
        } else {
            $this->data['config_description_in_lists'] = $this->config->get('config_description_in_lists');
        }
        
        if (isset($this->request->post['config_android_playstore_enable'])) {
            $this->data['config_android_playstore_enable'] = $this->request->post['config_android_playstore_enable'];
        } else {
            $this->data['config_android_playstore_enable'] = $this->config->get('config_android_playstore_enable');
        }
        
        if (isset($this->request->post['config_android_playstore_code'])) {
            $this->data['config_android_playstore_code'] = $this->request->post['config_android_playstore_code'];
        } else {
            $this->data['config_android_playstore_code'] = $this->config->get('config_android_playstore_code');
        }
        
        if (isset($this->request->post['config_android_playstore_link'])) {
            $this->data['config_android_playstore_link'] = $this->request->post['config_android_playstore_link'];
        } else {
            $this->data['config_android_playstore_link'] = $this->config->get('config_android_playstore_link');
        }

        if (isset($this->request->post['config_android_application_link'])) {
            $this->data['config_android_application_link'] = $this->request->post['config_android_application_link'];
        } else {
            $this->data['config_android_application_link'] = $this->config->get('config_android_application_link');
        }
        
        if (isset($this->request->post['config_firebase_code'])) {
            $this->data['config_firebase_code'] = $this->request->post['config_firebase_code'];
        } else {
            $this->data['config_firebase_code'] = $this->config->get('config_firebase_code');
        }
        
        if (isset($this->request->post['config_microsoft_store_enable'])) {
            $this->data['config_microsoft_store_enable'] = $this->request->post['config_microsoft_store_enable'];
        } else {
            $this->data['config_microsoft_store_enable'] = $this->config->get('config_microsoft_store_enable');
        }
        
        if (isset($this->request->post['config_microsoft_store_code'])) {
            $this->data['config_microsoft_store_code'] = $this->request->post['config_microsoft_store_code'];
        } else {
            $this->data['config_microsoft_store_code'] = $this->config->get('config_microsoft_store_code');
        }
        
        
        if (isset($this->request->post['config_microsoft_store_link'])) {
            $this->data['config_microsoft_store_link'] = $this->request->post['config_microsoft_store_link'];
        } else {
            $this->data['config_microsoft_store_link'] = $this->config->get('config_microsoft_store_link');
        }
        
        if (isset($this->request->post['config_catalog_limit'])) {
            $this->data['config_catalog_limit'] = $this->request->post['config_catalog_limit'];
        } else {
            $this->data['config_catalog_limit'] = $this->config->get('config_catalog_limit');
        }

        $this->load->model('catalog/attribute_group');
        $this->data['attribute_groups'] = $this->model_catalog_attribute_group->getAttributeGroups(['limit' => 100]);
        
        if (isset($this->request->post['config_use_separate_table_for_features'])) {
            $this->data['config_use_separate_table_for_features'] = $this->request->post['config_use_separate_table_for_features'];
        } else {
            $this->data['config_use_separate_table_for_features'] = $this->config->get('config_use_separate_table_for_features');
        }

        if (isset($this->request->post['config_special_attr_id'])) {
            $this->data['config_special_attr_id'] = $this->request->post['config_special_attr_id'];
        } else {
            $this->data['config_special_attr_id'] = $this->config->get('config_special_attr_id');
        }

        if (isset($this->request->post['config_default_attr_id'])) {
            $this->data['config_default_attr_id'] = $this->request->post['config_default_attr_id'];
        } else {
            $this->data['config_default_attr_id'] = $this->config->get('config_default_attr_id');
        }

        if (isset($this->request->post['config_dimensions_attr_id'])) {
            $this->data['config_dimensions_attr_id'] = $this->request->post['config_dimensions_attr_id'];
        } else {
            $this->data['config_dimensions_attr_id'] = $this->config->get('config_dimensions_attr_id');
        }

        if (isset($this->request->post['config_enable_attributes_values_logic'])) {
            $this->data['config_enable_attributes_values_logic'] = $this->request->post['config_enable_attributes_values_logic'];
        } else {
            $this->data['config_enable_attributes_values_logic'] = $this->config->get('config_enable_attributes_values_logic');
        }

        if (isset($this->request->post['config_special_attr_name'])) {
            $this->data['config_special_attr_name'] = $this->request->post['config_special_attr_name'];
        } else {
            $this->data['config_special_attr_name'] = $this->config->get('config_special_attr_name');
        }

        if (isset($this->request->post['config_specifications_attr_id'])) {
            $this->data['config_specifications_attr_id'] = $this->request->post['config_specifications_attr_id'];
        } else {
            $this->data['config_specifications_attr_id'] = $this->config->get('config_specifications_attr_id');
        }

        if (isset($this->request->post['config_specifications_attr_name'])) {
            $this->data['config_specifications_attr_name'] = $this->request->post['config_specifications_attr_name'];
        } else {
            $this->data['config_specifications_attr_name'] = $this->config->get('config_specifications_attr_name');
        }
        
        if (isset($this->request->post['config_admin_limit'])) {
            $this->data['config_admin_limit'] = $this->request->post['config_admin_limit'];
        } else {
            $this->data['config_admin_limit'] = $this->config->get('config_admin_limit');
        }
        
        if (isset($this->request->post['config_product_hide_sku'])) {
            $this->data['config_product_hide_sku'] = $this->request->post['config_product_hide_sku'];
        } else {
            $this->data['config_product_hide_sku'] = $this->config->get('config_product_hide_sku');
        }
        
        if (isset($this->request->post['config_product_replace_sku_with_product_id'])) {
            $this->data['config_product_replace_sku_with_product_id'] = $this->request->post['config_product_replace_sku_with_product_id'];
        } else {
            $this->data['config_product_replace_sku_with_product_id'] = $this->config->get('config_product_replace_sku_with_product_id');
        }
        
        if (isset($this->request->post['config_product_use_sku_prefix'])) {
            $this->data['config_product_use_sku_prefix'] = $this->request->post['config_product_use_sku_prefix'];
        } else {
            $this->data['config_product_use_sku_prefix'] = $this->config->get('config_product_use_sku_prefix');
        }
        
        if (isset($this->request->post['config_product_use_sku_prefix'])) {
            $this->data['config_product_use_sku_prefix'] = $this->request->post['config_product_use_sku_prefix'];
        } else {
            $this->data['config_product_use_sku_prefix'] = $this->config->get('config_product_use_sku_prefix');
        }
        
        if (isset($this->request->post['config_product_count'])) {
            $this->data['config_product_count'] = $this->request->post['config_product_count'];
        } else {
            $this->data['config_product_count'] = $this->config->get('config_product_count');
        }

        if (isset($this->request->post['config_ignore_manual_marker_productnews'])) {
            $this->data['config_ignore_manual_marker_productnews'] = $this->request->post['config_ignore_manual_marker_productnews'];
        } else {
            $this->data['config_ignore_manual_marker_productnews'] = $this->config->get('config_ignore_manual_marker_productnews');
        }

         if (isset($this->request->post['config_productnews_exclude_added_from_amazon'])) {
            $this->data['config_productnews_exclude_added_from_amazon'] = $this->request->post['config_productnews_exclude_added_from_amazon'];
        } else {
            $this->data['config_productnews_exclude_added_from_amazon'] = $this->config->get('config_productnews_exclude_added_from_amazon');
        }

        if (isset($this->request->post['config_new_days'])) {
            $this->data['config_new_days'] = $this->request->post['config_new_days'];
        } else {
            $this->data['config_new_days'] = $this->config->get('config_new_days');
        }

        if (isset($this->request->post['config_newlong_days'])) {
            $this->data['config_newlong_days'] = $this->request->post['config_newlong_days'];
        } else {
            $this->data['config_newlong_days'] = $this->config->get('config_newlong_days');
        }

        if (isset($this->request->post['config_sort_default'])) {
            $this->data['config_sort_default'] = $this->request->post['config_sort_default'];
        } else {
            $this->data['config_sort_default'] = $this->config->get('config_sort_default');
        }

        if (isset($this->request->post['config_order_default'])) {
            $this->data['config_order_default'] = $this->request->post['config_order_default'];
        } else {
            $this->data['config_order_default'] = $this->config->get('config_order_default');
        }

        if (isset($this->request->post['config_special_controller_logic'])) {
            $this->data['config_special_controller_logic'] = $this->request->post['config_special_controller_logic'];
        } else {
            $this->data['config_special_controller_logic'] = $this->config->get('config_special_controller_logic');
        }

         if (isset($this->request->post['config_single_special_price'])) {
            $this->data['config_single_special_price'] = $this->request->post['config_single_special_price'];
        } else {
            $this->data['config_single_special_price'] = $this->config->get('config_single_special_price');
        }

        if (isset($this->request->post['config_related_categories_auto_enable'])) {
            $this->data['config_related_categories_auto_enable'] = $this->request->post['config_related_categories_auto_enable'];
        } else {
            $this->data['config_related_categories_auto_enable'] = $this->config->get('config_related_categories_auto_enable');
        }

        if (isset($this->request->post['config_also_bought_auto_enable'])) {
            $this->data['config_also_bought_auto_enable'] = $this->request->post['config_also_bought_auto_enable'];
        } else {
            $this->data['config_also_bought_auto_enable'] = $this->config->get('config_also_bought_auto_enable');
        }

        if (isset($this->request->post['config_special_category_id'])) {
            $this->data['config_special_category_id'] = $this->request->post['config_special_category_id'];
        } else {
            $this->data['config_special_category_id'] = $this->config->get('config_special_category_id');
        }

        if (isset($this->request->post['config_display_subcategory_in_all_categories'])) {
            $this->data['config_display_subcategory_in_all_categories'] = $this->request->post['config_display_subcategory_in_all_categories'];
        } else {
            $this->data['config_display_subcategory_in_all_categories'] = $this->config->get('config_display_subcategory_in_all_categories');
        }

        if (isset($this->request->post['config_subcategories_limit'])) {
            $this->data['config_subcategories_limit'] = $this->request->post['config_subcategories_limit'];
        } else {
            $this->data['config_subcategories_limit'] = $this->config->get('config_subcategories_limit');
        }

        if (isset($this->request->post['config_second_level_subcategory_in_categories'])) {
            $this->data['config_second_level_subcategory_in_categories'] = $this->request->post['config_second_level_subcategory_in_categories'];
        } else {
            $this->data['config_second_level_subcategory_in_categories'] = $this->config->get('config_second_level_subcategory_in_categories');
        }
        
        if (isset($this->request->post['config_review_status'])) {
            $this->data['config_review_status'] = $this->request->post['config_review_status'];
        } else {
            $this->data['config_review_status'] = $this->config->get('config_review_status');
        }
        
        if (isset($this->request->post['config_download'])) {
            $this->data['config_download'] = $this->request->post['config_download'];
        } else {
            $this->data['config_download'] = $this->config->get('config_download');
        }
        
        if (isset($this->request->post['config_voucher_min'])) {
            $this->data['config_voucher_min'] = $this->request->post['config_voucher_min'];
        } else {
            $this->data['config_voucher_min'] = $this->config->get('config_voucher_min');
        }
        
        if (isset($this->request->post['config_voucher_max'])) {
            $this->data['config_voucher_max'] = $this->request->post['config_voucher_max'];
        } else {
            $this->data['config_voucher_max'] = $this->config->get('config_voucher_max');
        }
        
        if (isset($this->request->post['config_tax'])) {
            $this->data['config_tax'] = $this->request->post['config_tax'];
        } else {
            $this->data['config_tax'] = $this->config->get('config_tax');
        }
        
        if (isset($this->request->post['config_vat'])) {
            $this->data['config_vat'] = $this->request->post['config_vat'];
        } else {
            $this->data['config_vat'] = $this->config->get('config_vat');
        }
        
        if (isset($this->request->post['config_tax_default'])) {
            $this->data['config_tax_default'] = $this->request->post['config_tax_default'];
        } else {
            $this->data['config_tax_default'] = $this->config->get('config_tax_default');
        }
        
        if (isset($this->request->post['config_tax_customer'])) {
            $this->data['config_tax_customer'] = $this->request->post['config_tax_customer'];
        } else {
            $this->data['config_tax_customer'] = $this->config->get('config_tax_customer');
        }
        
        if (isset($this->request->post['config_customer_online'])) {
            $this->data['config_customer_online'] = $this->request->post['config_customer_online'];
        } else {
            $this->data['config_customer_online'] = $this->config->get('config_customer_online');
        }
        
        if (isset($this->request->post['config_customer_group_id'])) {
            $this->data['config_customer_group_id'] = $this->request->post['config_customer_group_id'];
        } else {
            $this->data['config_customer_group_id'] = $this->config->get('config_customer_group_id');
        }
        
        if (isset($this->request->post['config_bad_customer_group_id'])) {
            $this->data['config_bad_customer_group_id'] = $this->request->post['config_bad_customer_group_id'];
        } else {
            $this->data['config_bad_customer_group_id'] = $this->config->get('config_bad_customer_group_id');
        }
        
        if (isset($this->request->post['config_opt_group_id'])) {
            $this->data['config_opt_group_id'] = $this->request->post['config_opt_group_id'];
        } else {
            $this->data['config_opt_group_id'] = $this->config->get('config_opt_group_id');
        }
        
        if (isset($this->request->post['config_myreviews_edit'])) {
            $this->data['config_myreviews_edit'] = $this->request->post['config_myreviews_edit'];
        } else {
            $this->data['config_myreviews_edit'] = $this->config->get('config_myreviews_edit');
        }
        if (isset($this->request->post['config_myreviews_moder'])) {
            $this->data['config_myreviews_moder'] = $this->request->post['config_myreviews_moder'];
        } else {
            $this->data['config_myreviews_moder'] = $this->config->get('config_myreviews_moder');
        }
        
        $this->load->model('sale/customer_group');
        
        $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
        
        if (isset($this->request->post['config_customer_group_display'])) {
            $this->data['config_customer_group_display'] = $this->request->post['config_customer_group_display'];
        } elseif ($this->config->get('config_customer_group_display')) {
            $this->data['config_customer_group_display'] = $this->config->get('config_customer_group_display');
        } else {
            $this->data['config_customer_group_display'] = [];
        }
        
        if (isset($this->request->post['config_customer_price'])) {
            $this->data['config_customer_price'] = $this->request->post['config_customer_price'];
        } else {
            $this->data['config_customer_price'] = $this->config->get('config_customer_price');
        }
        
        if (isset($this->request->post['config_account_id'])) {
            $this->data['config_account_id'] = $this->request->post['config_account_id'];
        } else {
            $this->data['config_account_id'] = $this->config->get('config_account_id');
        }
        
        $this->load->model('catalog/information');
        
        $this->data['informations'] = $this->model_catalog_information->getInformations();
        
        if (isset($this->request->post['config_cart_weight'])) {
            $this->data['config_cart_weight'] = $this->request->post['config_cart_weight'];
        } else {
            $this->data['config_cart_weight'] = $this->config->get('config_cart_weight');
        }
        
        if (isset($this->request->post['config_guest_checkout'])) {
            $this->data['config_guest_checkout'] = $this->request->post['config_guest_checkout'];
        } else {
            $this->data['config_guest_checkout'] = $this->config->get('config_guest_checkout');
        }
        
        if (isset($this->request->post['config_checkout_id'])) {
            $this->data['config_checkout_id'] = $this->request->post['config_checkout_id'];
        } else {
            $this->data['config_checkout_id'] = $this->config->get('config_checkout_id');
        }
        
        if (isset($this->request->post['config_order_edit'])) {
            $this->data['config_order_edit'] = $this->request->post['config_order_edit'];
        } elseif ($this->config->get('config_order_edit')) {
            $this->data['config_order_edit'] = $this->config->get('config_order_edit');
        } else {
            $this->data['config_order_edit'] = 7;
        }
        
        if (isset($this->request->post['config_invoice_prefix'])) {
            $this->data['config_invoice_prefix'] = $this->request->post['config_invoice_prefix'];
        } elseif ($this->config->get('config_invoice_prefix')) {
            $this->data['config_invoice_prefix'] = $this->config->get('config_invoice_prefix');
        } else {
            $this->data['config_invoice_prefix'] = 'INV-' . date('Y') . '-00';
        }
        
        if (isset($this->request->post['config_order_status_id'])) {
            $this->data['config_order_status_id'] = $this->request->post['config_order_status_id'];
        } else {
            $this->data['config_order_status_id'] = $this->config->get('config_order_status_id');
        }
        
        if (isset($this->request->post['config_confirmed_order_status_id'])) {
            $this->data['config_confirmed_order_status_id'] = $this->request->post['config_confirmed_order_status_id'];
        } else {
            $this->data['config_confirmed_order_status_id'] = $this->config->get('config_confirmed_order_status_id');
        }
        
        if (isset($this->request->post['config_confirmed_nopaid_order_status_id'])) {
            $this->data['config_order_confirmed_nopaid_status_id'] = $this->request->post['config_confirmed_nopaid_order_status_id'];
        } else {
            $this->data['config_confirmed_nopaid_order_status_id'] = $this->config->get('config_confirmed_nopaid_order_status_id');
        }
        
        
        if (isset($this->request->post['config_confirmed_delivery_payment_ids'])) {
            $this->data['config_confirmed_delivery_payment_ids'] = $this->request->post['config_confirmed_delivery_payment_ids'];
        } else {
            $this->data['config_confirmed_delivery_payment_ids'] = $this->config->get('config_confirmed_delivery_payment_ids');
        }
        
        if (isset($this->request->post['config_confirmed_prepay_payment_ids'])) {
            $this->data['config_confirmed_prepay_payment_ids'] = $this->request->post['config_confirmed_prepay_payment_ids'];
        } else {
            $this->data['config_confirmed_prepay_payment_ids'] = $this->config->get('config_confirmed_prepay_payment_ids');
        }
        
        if (isset($this->request->post['config_complete_status_id'])) {
            $this->data['config_complete_status_id'] = $this->request->post['config_complete_status_id'];
        } else {
            $this->data['config_complete_status_id'] = $this->config->get('config_complete_status_id');
        }
        
        if (isset($this->request->post['config_treated_status_id'])) {
            $this->data['config_treated_status_id'] = $this->request->post['config_treated_status_id'];
        } else {
            $this->data['config_treated_status_id'] = $this->config->get('config_treated_status_id');
        }
        
        if (isset($this->request->post['config_cancelled_status_id'])) {
            $this->data['config_cancelled_status_id'] = $this->request->post['config_cancelled_status_id'];
        } else {
            $this->data['config_cancelled_status_id'] = $this->config->get('config_cancelled_status_id');
        }
        
        if (isset($this->request->post['config_partly_delivered_status_id'])) {
            $this->data['config_partly_delivered_status_id'] = $this->request->post['config_partly_delivered_status_id'];
        } else {
            $this->data['config_partly_delivered_status_id'] = $this->config->get('config_partly_delivered_status_id');
        }
        
        if (isset($this->request->post['config_cancelled_after_status_id'])) {
            $this->data['config_cancelled_after_status_id'] = $this->request->post['config_cancelled_after_status_id'];
        } else {
            $this->data['config_cancelled_after_status_id'] = $this->config->get('config_cancelled_after_status_id');
        }
        
        if (isset($this->request->post['config_ready_to_delivering_status_id'])) {
            $this->data['config_ready_to_delivering_status_id'] = $this->request->post['config_ready_to_delivering_status_id'];
        } else {
            $this->data['config_ready_to_delivering_status_id'] = $this->config->get('config_ready_to_delivering_status_id');
        }
        
        if (isset($this->request->post['config_in_pickup_status_id'])) {
            $this->data['config_in_pickup_status_id'] = $this->request->post['config_in_pickup_status_id'];
        } else {
            $this->data['config_in_pickup_status_id'] = $this->config->get('config_in_pickup_status_id');
        }
        
        
        if (isset($this->request->post['config_delivering_status_id'])) {
            $this->data['config_delivering_status_id'] = $this->request->post['config_delivering_status_id'];
        } else {
            $this->data['config_delivering_status_id'] = $this->config->get('config_delivering_status_id');
        }
        
        if (isset($this->request->post['config_prepayment_paid_order_status_id'])) {
            $this->data['config_prepayment_paid_order_status_id'] = $this->request->post['config_prepayment_paid_order_status_id'];
        } else {
            $this->data['config_prepayment_paid_order_status_id'] = $this->config->get('config_prepayment_paid_order_status_id');
        }
        
        if (isset($this->request->post['config_total_paid_order_status_id'])) {
            $this->data['config_total_paid_order_status_id'] = $this->request->post['config_total_paid_order_status_id'];
        } else {
            $this->data['config_total_paid_order_status_id'] = $this->config->get('config_total_paid_order_status_id');
        }
        
        if (isset($this->request->post['config_odinass_order_status_id'])) {
            $this->data['config_odinass_order_status_id'] = $this->request->post['config_odinass_order_status_id'];
        } elseif ($this->config->get('config_odinass_order_status_id')) {
            $this->data['config_odinass_order_status_id'] = $this->config->get('config_odinass_order_status_id');
        } else {
            $this->data['config_odinass_order_status_id'] = [];
        }
        
        if (isset($this->request->post['config_problem_order_status_id'])) {
            $this->data['config_problem_order_status_id'] = $this->request->post['config_problem_order_status_id'];
        } elseif ($this->config->get('config_problem_order_status_id')) {
            $this->data['config_problem_order_status_id'] = $this->config->get('config_problem_order_status_id');
        } else {
            $this->data['config_problem_order_status_id'] = [];
        }
        
        if (isset($this->request->post['config_problem_quality_order_status_id'])) {
            $this->data['config_problem_quality_order_status_id'] = $this->request->post['config_problem_quality_order_status_id'];
        } elseif ($this->config->get('config_problem_quality_order_status_id')) {
            $this->data['config_problem_quality_order_status_id'] = $this->config->get('config_problem_quality_order_status_id');
        } else {
            $this->data['config_problem_quality_order_status_id'] = [];
        }
        
        if (isset($this->request->post['config_toapprove_order_status_id'])) {
            $this->data['config_toapprove_order_status_id'] = $this->request->post['config_toapprove_order_status_id'];
        } elseif ($this->config->get('config_toapprove_order_status_id')) {
            $this->data['config_toapprove_order_status_id'] = $this->config->get('config_toapprove_order_status_id');
        } else {
            $this->data['config_toapprove_order_status_id'] = [];
        }
        
        $this->load->model('sale/reject_reason');
        $this->data['reject_reasons'] = $this->model_sale_reject_reason->getRejectReasons();
        
        if (isset($this->request->post['config_brandmanager_fail_order_status_id'])) {
            $this->data['config_brandmanager_fail_order_status_id'] = $this->request->post['config_brandmanager_fail_order_status_id'];
        } elseif ($this->config->get('config_brandmanager_fail_order_status_id')) {
            $this->data['config_brandmanager_fail_order_status_id'] = $this->config->get('config_brandmanager_fail_order_status_id');
        } else {
            $this->data['config_brandmanager_fail_order_status_id'] = [];
        }
        
        if (isset($this->request->post['config_manager_confirmed_order_status_id'])) {
            $this->data['config_manager_confirmed_order_status_id'] = $this->request->post['config_manager_confirmed_order_status_id'];
        } elseif ($this->config->get('config_manager_confirmed_order_status_id')) {
            $this->data['config_manager_confirmed_order_status_id'] = $this->config->get('config_manager_confirmed_order_status_id');
        } else {
            $this->data['config_manager_confirmed_order_status_id'] = [];
        }
        
        if (isset($this->request->post['config_nodelete_order_status_id'])) {
            $this->data['config_nodelete_order_status_id'] = $this->request->post['config_nodelete_order_status_id'];
        } elseif ($this->config->get('config_nodelete_order_status_id')) {
            $this->data['config_nodelete_order_status_id'] = $this->config->get('config_nodelete_order_status_id');
        } else {
            $this->data['config_nodelete_order_status_id'] = [];
        }
        
        if (isset($this->request->post['config_amazonlist_order_status_id'])) {
            $this->data['config_amazonlist_order_status_id'] = $this->request->post['config_amazonlist_order_status_id'];
        } elseif ($this->config->get('config_amazonlist_order_status_id')) {
            $this->data['config_amazonlist_order_status_id'] = $this->config->get('config_amazonlist_order_status_id');
        } else {
            $this->data['config_amazonlist_order_status_id'] = [];
        }
        
        $this->load->model('localisation/order_status');
        
        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        
        if (isset($this->request->post['config_stock_display'])) {
            $this->data['config_stock_display'] = $this->request->post['config_stock_display'];
        } else {
            $this->data['config_stock_display'] = $this->config->get('config_stock_display');
        }
        
        if (isset($this->request->post['config_stock_warning'])) {
            $this->data['config_stock_warning'] = $this->request->post['config_stock_warning'];
        } else {
            $this->data['config_stock_warning'] = $this->config->get('config_stock_warning');
        }
        
        if (isset($this->request->post['config_stock_checkout'])) {
            $this->data['config_stock_checkout'] = $this->request->post['config_stock_checkout'];
        } else {
            $this->data['config_stock_checkout'] = $this->config->get('config_stock_checkout');
        }
        
        if (isset($this->request->post['config_default_queue'])) {
            $this->data['config_default_queue'] = $this->request->post['config_default_queue'];
        } else {
            $this->data['config_default_queue'] = $this->config->get('config_default_queue');
        }
        
        if (isset($this->request->post['config_default_queue'])) {
            $this->data['config_default_queue'] = $this->request->post['config_default_queue'];
        } else {
            $this->data['config_default_queue'] = $this->config->get('config_default_queue');
        }
        
        if (isset($this->request->post['config_default_alert_queue'])) {
            $this->data['config_default_alert_queue'] = $this->request->post['config_default_alert_queue'];
        } else {
            $this->data['config_default_alert_queue'] = $this->config->get('config_default_alert_queue');
        }
        
        if (isset($this->request->post['config_default_manager_group'])) {
            $this->data['config_default_manager_group'] = $this->request->post['config_default_manager_group'];
        } else {
            $this->data['config_default_manager_group'] = $this->config->get('config_default_manager_group');
        }
        
        $this->load->model('user/user_group');
        
        $this->data['user_groups'] = $this->model_user_user_group->getUserGroups();
        
        if (isset($this->request->post['config_stock_status_id'])) {
            $this->data['config_stock_status_id'] = $this->request->post['config_stock_status_id'];
        } else {
            $this->data['config_stock_status_id'] = $this->config->get('config_stock_status_id');
        }
        
        if (isset($this->request->post['config_not_in_stock_status_id'])) {
            $this->data['config_not_in_stock_status_id'] = $this->request->post['config_not_in_stock_status_id'];
        } else {
            $this->data['config_not_in_stock_status_id'] = $this->config->get('config_not_in_stock_status_id');
        }
        
        if (isset($this->request->post['config_in_stock_status_id'])) {
            $this->data['config_in_stock_status_id'] = $this->request->post['config_in_stock_status_id'];
        } else {
            $this->data['config_in_stock_status_id'] = $this->config->get('config_in_stock_status_id');
        }
        
        if (isset($this->request->post['config_partly_in_stock_status_id'])) {
            $this->data['config_partly_in_stock_status_id'] = $this->request->post['config_partly_in_stock_status_id'];
        } else {
            $this->data['config_partly_in_stock_status_id'] = $this->config->get('config_partly_in_stock_status_id');
        }
        
        $this->load->model('localisation/stock_status');        
        $this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
        
        if (isset($this->request->post['config_affiliate_id'])) {
            $this->data['config_affiliate_id'] = $this->request->post['config_affiliate_id'];
        } else {
            $this->data['config_affiliate_id'] = $this->config->get('config_affiliate_id');
        }
        
        if (isset($this->request->post['config_commission'])) {
            $this->data['config_commission'] = $this->request->post['config_commission'];
        } elseif ($this->config->has('config_commission')) {
            $this->data['config_commission'] = $this->config->get('config_commission');
        } else {
            $this->data['config_commission'] = '5.00';
        }
        
        if (isset($this->request->post['config_return_id'])) {
            $this->data['config_return_id'] = $this->request->post['config_return_id'];
        } else {
            $this->data['config_return_id'] = $this->config->get('config_return_id');
        }
        
        if (isset($this->request->post['config_return_status_id'])) {
            $this->data['config_return_status_id'] = $this->request->post['config_return_status_id'];
        } else {
            $this->data['config_return_status_id'] = $this->config->get('config_return_status_id');
        }

        $articlekeys = array(
            'config_reward_article_id',
            'config_how_order_article_id',
            'config_delivery_article_id',
            'config_payment_article_id',
            'config_return_article_id',            
            'config_discounts_article_id',
            'config_present_certificates_article_id',
            'config_about_article_id',
            'config_credits_article_id',
            'config_vendors_article_id',
            'config_agreement_article_id',
            'config_personaldata_article_id'
        );
        
        foreach ($articlekeys as $articlekey) {
            if (isset($this->request->post[$articlekey])) {
                $this->data[$articlekey] = $this->request->post[$articlekey];
            } else {
                $this->data[$articlekey] = $this->config->get($articlekey);
            }
        }

        $this->load->model('localisation/return_status');
        
        $this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();
        
        $this->load->model('tool/image');
        
        if (isset($this->request->post['config_logo'])) {
            $this->data['config_logo'] = $this->request->post['config_logo'];
        } else {
            $this->data['config_logo'] = $this->config->get('config_logo');
        }

        $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 200, 200);
        
        if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo')) && is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $this->data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 200, 200);
        } else {
            $this->data['logo'] = $this->model_tool_image->resize('no_image.jpg', 200, 200);
        }
        
        if (isset($this->request->post['config_icon'])) {
            $this->data['config_icon'] = $this->request->post['config_icon'];
        } else {
            $this->data['config_icon'] = $this->config->get('config_icon');
        }
        
        if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon')) && is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
            $this->data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 200, 200);
        } else {
            $this->data['icon'] = $this->model_tool_image->resize('no_image.jpg', 200, 200);
        }
        
        if (isset($this->request->post['config_noimage'])) {
            $this->data['config_noimage'] = $this->request->post['config_noimage'];
        } else {
            $this->data['config_noimage'] = $this->config->get('config_noimage');
        }

        if ($this->config->get('config_noimage') && file_exists(DIR_IMAGE . $this->config->get('config_noimage')) && is_file(DIR_IMAGE . $this->config->get('config_noimage'))) {
            $this->data['noimage'] = $this->model_tool_image->resize($this->config->get('config_noimage'), 200, 200);
        } else {
            $this->data['noimage'] = $this->model_tool_image->resize('no_image.jpg', 200, 200);
        }

        if (isset($this->request->post['config_image_jpeg_quality'])) {
            $this->data['config_image_jpeg_quality'] = $this->request->post['config_image_jpeg_quality'];
        } else {
            $this->data['config_image_jpeg_quality'] = $this->config->get('config_image_jpeg_quality');
        }

        if (isset($this->request->post['config_image_webp_quality'])) {
            $this->data['config_image_webp_quality'] = $this->request->post['config_image_webp_quality'];
        } else {
            $this->data['config_image_webp_quality'] = $this->config->get('config_image_webp_quality');
        }

        if (isset($this->request->post['config_image_avif_quality'])) {
            $this->data['config_image_avif_quality'] = $this->request->post['config_image_avif_quality'];
        } else {
            $this->data['config_image_avif_quality'] = $this->config->get('config_image_avif_quality');
        }
        
                
        if (isset($this->request->post['config_image_category_width'])) {
            $this->data['config_image_category_width'] = $this->request->post['config_image_category_width'];
        } else {
            $this->data['config_image_category_width'] = $this->config->get('config_image_category_width');
        }
        
        if (isset($this->request->post['config_image_category_height'])) {
            $this->data['config_image_category_height'] = $this->request->post['config_image_category_height'];
        } else {
            $this->data['config_image_category_height'] = $this->config->get('config_image_category_height');
        }

        if (isset($this->request->post['config_image_subcategory_width'])) {
            $this->data['config_image_subcategory_width'] = $this->request->post['config_image_subcategory_width'];
        } else {
            $this->data['config_image_subcategory_width'] = $this->config->get('config_image_subcategory_width');
        }
        
        if (isset($this->request->post['config_image_subcategory_height'])) {
            $this->data['config_image_subcategory_height'] = $this->request->post['config_image_subcategory_height'];
        } else {
            $this->data['config_image_subcategory_height'] = $this->config->get('config_image_subcategory_height');
        }
        
        if (isset($this->request->post['config_image_thumb_width'])) {
            $this->data['config_image_thumb_width'] = $this->request->post['config_image_thumb_width'];
        } else {
            $this->data['config_image_thumb_width'] = $this->config->get('config_image_thumb_width');
        }
        
        if (isset($this->request->post['config_image_thumb_height'])) {
            $this->data['config_image_thumb_height'] = $this->request->post['config_image_thumb_height'];
        } else {
            $this->data['config_image_thumb_height'] = $this->config->get('config_image_thumb_height');
        }
        
        if (isset($this->request->post['config_image_popup_width'])) {
            $this->data['config_image_popup_width'] = $this->request->post['config_image_popup_width'];
        } else {
            $this->data['config_image_popup_width'] = $this->config->get('config_image_popup_width');
        }
        
        if (isset($this->request->post['config_image_popup_height'])) {
            $this->data['config_image_popup_height'] = $this->request->post['config_image_popup_height'];
        } else {
            $this->data['config_image_popup_height'] = $this->config->get('config_image_popup_height');
        }
        
        if (isset($this->request->post['config_image_product_width'])) {
            $this->data['config_image_product_width'] = $this->request->post['config_image_product_width'];
        } else {
            $this->data['config_image_product_width'] = $this->config->get('config_image_product_width');
        }
        
        if (isset($this->request->post['config_image_product_height'])) {
            $this->data['config_image_product_height'] = $this->request->post['config_image_product_height'];
        } else {
            $this->data['config_image_product_height'] = $this->config->get('config_image_product_height');
        }
        
        if (isset($this->request->post['config_image_additional_width'])) {
            $this->data['config_image_additional_width'] = $this->request->post['config_image_additional_width'];
        } else {
            $this->data['config_image_additional_width'] = $this->config->get('config_image_additional_width');
        }
        
        if (isset($this->request->post['config_image_additional_height'])) {
            $this->data['config_image_additional_height'] = $this->request->post['config_image_additional_height'];
        } else {
            $this->data['config_image_additional_height'] = $this->config->get('config_image_additional_height');
        }
        
        if (isset($this->request->post['config_image_related_width'])) {
            $this->data['config_image_related_width'] = $this->request->post['config_image_related_width'];
        } else {
            $this->data['config_image_related_width'] = $this->config->get('config_image_related_width');
        }
        
        if (isset($this->request->post['config_image_related_height'])) {
            $this->data['config_image_related_height'] = $this->request->post['config_image_related_height'];
        } else {
            $this->data['config_image_related_height'] = $this->config->get('config_image_related_height');
        }
        
        if (isset($this->request->post['config_image_compare_width'])) {
            $this->data['config_image_compare_width'] = $this->request->post['config_image_compare_width'];
        } else {
            $this->data['config_image_compare_width'] = $this->config->get('config_image_compare_width');
        }
        
        if (isset($this->request->post['config_image_compare_height'])) {
            $this->data['config_image_compare_height'] = $this->request->post['config_image_compare_height'];
        } else {
            $this->data['config_image_compare_height'] = $this->config->get('config_image_compare_height');
        }
        
        if (isset($this->request->post['config_image_wishlist_width'])) {
            $this->data['config_image_wishlist_width'] = $this->request->post['config_image_wishlist_width'];
        } else {
            $this->data['config_image_wishlist_width'] = $this->config->get('config_image_wishlist_width');
        }
        
        if (isset($this->request->post['config_image_wishlist_height'])) {
            $this->data['config_image_wishlist_height'] = $this->request->post['config_image_wishlist_height'];
        } else {
            $this->data['config_image_wishlist_height'] = $this->config->get('config_image_wishlist_height');
        }
        
        if (isset($this->request->post['config_image_cart_width'])) {
            $this->data['config_image_cart_width'] = $this->request->post['config_image_cart_width'];
        } else {
            $this->data['config_image_cart_width'] = $this->config->get('config_image_cart_width');
        }
        
        if (isset($this->request->post['config_image_cart_height'])) {
            $this->data['config_image_cart_height'] = $this->request->post['config_image_cart_height'];
        } else {
            $this->data['config_image_cart_height'] = $this->config->get('config_image_cart_height');
        }
        
        if (isset($this->request->post['config_ftp_host'])) {
            $this->data['config_ftp_host'] = $this->request->post['config_ftp_host'];
        } elseif ($this->config->get('config_ftp_host')) {
            $this->data['config_ftp_host'] = $this->config->get('config_ftp_host');
        } else {
            $this->data['config_ftp_host'] = str_replace('www.', '', $this->request->server['HTTP_HOST']);
        }
        
        if (isset($this->request->post['config_ftp_port'])) {
            $this->data['config_ftp_port'] = $this->request->post['config_ftp_port'];
        } elseif ($this->config->get('config_ftp_port')) {
            $this->data['config_ftp_port'] = $this->config->get('config_ftp_port');
        } else {
            $this->data['config_ftp_port'] = 21;
        }
        
        if (isset($this->request->post['config_ftp_username'])) {
            $this->data['config_ftp_username'] = $this->request->post['config_ftp_username'];
        } else {
            $this->data['config_ftp_username'] = $this->config->get('config_ftp_username');
        }
        
        if (isset($this->request->post['config_ftp_password'])) {
            $this->data['config_ftp_password'] = $this->request->post['config_ftp_password'];
        } else {
            $this->data['config_ftp_password'] = $this->config->get('config_ftp_password');
        }
        
        if (isset($this->request->post['config_ftp_root'])) {
            $this->data['config_ftp_root'] = $this->request->post['config_ftp_root'];
        } else {
            $this->data['config_ftp_root'] = $this->config->get('config_ftp_root');
        }
        
        if (isset($this->request->post['config_ftp_status'])) {
            $this->data['config_ftp_status'] = $this->request->post['config_ftp_status'];
        } else {
            $this->data['config_ftp_status'] = $this->config->get('config_ftp_status');
        }

        $this->data['mailgates'] = $this->mailAdaptor->getMailGates();

        if (isset($this->request->post['config_mailgate_library'])) {
            $this->data['config_mailgate_library'] = $this->request->post['config_mailgate_library'];
        } else {
            $this->data['config_mailgate_library'] = $this->config->get('config_mailgate_library');
        }

        if (isset($this->request->post['config_mailgate_marketing_library'])) {
            $this->data['config_mailgate_marketing_library'] = $this->request->post['config_mailgate_marketing_library'];
        } else {
            $this->data['config_mailgate_marketing_library'] = $this->config->get('config_mailgate_marketing_library');
        }



        
        if (isset($this->request->post['config_mail_protocol'])) {
            $this->data['config_mail_protocol'] = $this->request->post['config_mail_protocol'];
        } else {
            $this->data['config_mail_protocol'] = $this->config->get('config_mail_protocol');
        }
        
        if (isset($this->request->post['config_mail_parameter'])) {
            $this->data['config_mail_parameter'] = $this->request->post['config_mail_parameter'];
        } else {
            $this->data['config_mail_parameter'] = $this->config->get('config_mail_parameter');
        }
        
        if (isset($this->request->post['config_mail_trigger_protocol'])) {
            $this->data['config_mail_trigger_protocol'] = $this->request->post['config_mail_trigger_protocol'];
        } else {
            $this->data['config_mail_trigger_protocol'] = $this->config->get('config_mail_trigger_protocol');
        }

        if (isset($this->request->post['config_mail_trigger_name_from'])) {
            $this->data['config_mail_trigger_name_from'] = $this->request->post['config_mail_trigger_name_from'];
        } else {
            $this->data['config_mail_trigger_name_from'] = $this->config->get('config_mail_trigger_name_from');
        }

        if (isset($this->request->post['config_mail_trigger_name_from'])) {
            $this->data['config_mail_trigger_name_from'] = $this->request->post['config_mail_trigger_name_from'];
        } else {
            $this->data['config_mail_trigger_name_from'] = $this->config->get('config_mail_trigger_name_from');
        }

        if (isset($this->request->post['config_mail_trigger_mail_from'])) {
            $this->data['config_mail_trigger_mail_from'] = $this->request->post['config_mail_trigger_mail_from'];
        } else {
            $this->data['config_mail_trigger_mail_from'] = $this->config->get('config_mail_trigger_mail_from');
        }

            //PAYMENT
        if (isset($this->request->post['config_payment_mail_from'])) {
            $this->data['config_payment_mail_from'] = $this->request->post['config_payment_mail_from'];
        } else {
            $this->data['config_payment_mail_from'] = $this->config->get('config_payment_mail_from');
        }

        if (isset($this->request->post['config_payment_mail_to'])) {
            $this->data['config_payment_mail_to'] = $this->request->post['config_payment_mail_to'];
        } else {
            $this->data['config_payment_mail_to'] = $this->config->get('config_payment_mail_to');
        }

        if (isset($this->request->post['config_main_redirect_domain'])) {
            $this->data['config_main_redirect_domain'] = $this->request->post['config_main_redirect_domain'];
        } else {
            $this->data['config_main_redirect_domain'] = $this->config->get('config_main_redirect_domain');
        }

        if (isset($this->request->post['config_main_wp_blog_domain'])) {
            $this->data['config_main_wp_blog_domain'] = $this->request->post['config_main_wp_blog_domain'];
        } else {
            $this->data['config_main_wp_blog_domain'] = $this->config->get('config_main_wp_blog_domain');
        }

        if (isset($this->request->post['config_courier_mail_to'])) {
            $this->data['config_courier_mail_to'] = $this->request->post['config_courier_mail_to'];
        } else {
            $this->data['config_courier_mail_to'] = $this->config->get('config_courier_mail_to');
        }


        $sparkpost_config_keys = [
          'config_sparkpost_bounce_enable',
          'config_sparkpost_api_url',
          'config_sparkpost_api_key',
          'config_sparkpost_api_user'
        ];

        foreach ($sparkpost_config_keys as $key) {
            if (isset($this->request->post[$key])) {
                $this->data[$key] = $this->request->post[$key];
            } else {
                $this->data[$key] = $this->config->get($key);
            }
        }

        $sendsay_config_keys = [
          'config_sendsay_enable',
          'config_sendsay_api_uri',
          'config_sendsay_api_key',
          'config_sendsay_enable_marketing',
          'config_sendsay_enable_webpush',
          'config_sendsay_webpush_id',
          'config_sendsay_fid',
          'config_sendsay_exclude_native',
          'config_sendsay_mapping_newsletter',          
          'config_sendsay_mapping_newsletter_news',
          'config_sendsay_mapping_newsletter_personal'
        ];

        foreach ($sendsay_config_keys as $key) {
            if (isset($this->request->post[$key])) {
                $this->data[$key] = $this->request->post[$key];
            } else {
                $this->data[$key] = $this->config->get($key);
            }
        }

        if (isset($this->request->post['config_mailgun_bounce_enable'])) {

            $this->data['config_mailgun_bounce_enable'] = $this->request->post['config_mailgun_bounce_enable'];

        } else {

            $this->data['config_mailgun_bounce_enable'] = $this->config->get('config_mailgun_bounce_enable');

        }

        if (isset($this->request->post['config_mailgun_api_url'])) {

            $this->data['config_mailgun_api_url'] = $this->request->post['config_mailgun_api_url'];

        } else {

            $this->data['config_mailgun_api_url'] = $this->config->get('config_mailgun_api_url');

        }

        if (isset($this->request->post['config_mailgun_api_transaction_domain'])) {

            $this->data['config_mailgun_api_transaction_domain'] = $this->request->post['config_mailgun_api_transaction_domain'];

        } else {

            $this->data['config_mailgun_api_transaction_domain'] = $this->config->get('config_mailgun_api_transaction_domain');

        }

        if (isset($this->request->post['config_mailgun_api_marketing_domain'])) {

            $this->data['config_mailgun_api_marketing_domain'] = $this->request->post['config_mailgun_api_marketing_domain'];

        } else {

            $this->data['config_mailgun_api_marketing_domain'] = $this->config->get('config_mailgun_api_marketing_domain');

        }

        if (isset($this->request->post['config_mailgun_api_public_key'])) {

            $this->data['config_mailgun_api_public_key'] = $this->request->post['config_mailgun_api_public_key'];

        } else {

            $this->data['config_mailgun_api_public_key'] = $this->config->get('config_mailgun_api_public_key');

        }

        if (isset($this->request->post['config_mailgun_api_private_key'])) {

            $this->data['config_mailgun_api_private_key'] = $this->request->post['config_mailgun_api_private_key'];

        } else {

            $this->data['config_mailgun_api_private_key'] = $this->config->get('config_mailgun_api_private_key');

        }

        if (isset($this->request->post['config_mailgun_api_signing_key'])) {

            $this->data['config_mailgun_api_signing_key'] = $this->request->post['config_mailgun_api_signing_key'];

        } else {

            $this->data['config_mailgun_api_signing_key'] = $this->config->get('config_mailgun_api_signing_key');

        }

        if (isset($this->request->post['config_mailgun_mail_limit'])) {

            $this->data['config_mailgun_mail_limit'] = $this->request->post['config_mailgun_mail_limit'];

        } else {

            $this->data['config_mailgun_mail_limit'] = $this->config->get('config_mailgun_mail_limit');

        }
        
        if (isset($this->request->post['config_mailwizz_enable'])) {
            $this->data['config_mailwizz_enable'] = $this->request->post['config_mailwizz_enable'];
        } else {
            $this->data['config_mailwizz_enable'] = $this->config->get('config_mailwizz_enable');
        }
        
        if (isset($this->request->post['config_mailwizz_api_uri'])) {
            $this->data['config_mailwizz_api_uri'] = $this->request->post['config_mailwizz_api_uri'];
        } else {
            $this->data['config_mailwizz_api_uri'] = $this->config->get('config_mailwizz_api_uri');
        }
        
        if (isset($this->request->post['config_mailwizz_api_key'])) {
            $this->data['config_mailwizz_api_key'] = $this->request->post['config_mailwizz_api_key'];
        } else {
            $this->data['config_mailwizz_api_key'] = $this->config->get('config_mailwizz_api_key');
        }
        
        if (isset($this->request->post['config_mailwizz_mapping_newsletter'])) {
            $this->data['config_mailwizz_mapping_newsletter'] = $this->request->post['config_mailwizz_mapping_newsletter'];
        } else {
            $this->data['config_mailwizz_mapping_newsletter'] = $this->config->get('config_mailwizz_mapping_newsletter');
        }
        
        if (isset($this->request->post['config_mailwizz_mapping_newsletter_news'])) {
            $this->data['config_mailwizz_mapping_newsletter_news'] = $this->request->post['config_mailwizz_mapping_newsletter_news'];
        } else {
            $this->data['config_mailwizz_mapping_newsletter_news'] = $this->config->get('config_mailwizz_mapping_newsletter_news');
        }
        
        if (isset($this->request->post['config_mailwizz_mapping_newsletter_personal'])) {
            $this->data['config_mailwizz_mapping_newsletter_personal'] = $this->request->post['config_mailwizz_mapping_newsletter_personal'];
        } else {
            $this->data['config_mailwizz_mapping_newsletter_personal'] = $this->config->get('config_mailwizz_mapping_newsletter_personal');
        }

        if (isset($this->request->post['config_mailwizz_noorder_days'])) {
            $this->data['config_mailwizz_noorder_days'] = $this->request->post['config_mailwizz_noorder_days'];
        } else {
            $this->data['config_mailwizz_noorder_days'] = $this->config->get('config_mailwizz_noorder_days');
        }

        if (isset($this->request->post['config_mailwizz_exclude_native'])) {
            $this->data['config_mailwizz_exclude_native'] = $this->request->post['config_mailwizz_exclude_native'];
        } else {
            $this->data['config_mailwizz_exclude_native'] = $this->config->get('config_mailwizz_exclude_native');
        }
        
        if (isset($this->request->post['config_smtp_host'])) {
            $this->data['config_smtp_host'] = $this->request->post['config_smtp_host'];
        } else {
            $this->data['config_smtp_host'] = $this->config->get('config_smtp_host');
        }
        
        if (isset($this->request->post['config_smtp_username'])) {
            $this->data['config_smtp_username'] = $this->request->post['config_smtp_username'];
        } else {
            $this->data['config_smtp_username'] = $this->config->get('config_smtp_username');
        }
        
        if (isset($this->request->post['config_smtp_password'])) {
            $this->data['config_smtp_password'] = $this->request->post['config_smtp_password'];
        } else {
            $this->data['config_smtp_password'] = $this->config->get('config_smtp_password');
        }
        
        if (isset($this->request->post['config_smtp_port'])) {
            $this->data['config_smtp_port'] = $this->request->post['config_smtp_port'];
        } elseif ($this->config->get('config_smtp_port')) {
            $this->data['config_smtp_port'] = $this->config->get('config_smtp_port');
        } else {
            $this->data['config_smtp_port'] = 25;
        }
        
        if (isset($this->request->post['config_smtp_timeout'])) {
            $this->data['config_smtp_timeout'] = $this->request->post['config_smtp_timeout'];
        } elseif ($this->config->get('config_smtp_timeout')) {
            $this->data['config_smtp_timeout'] = $this->config->get('config_smtp_timeout');
        } else {
            $this->data['config_smtp_timeout'] = 5;
        }
        
        if (isset($this->request->post['config_alert_mail'])) {
            $this->data['config_alert_mail'] = $this->request->post['config_alert_mail'];
        } else {
            $this->data['config_alert_mail'] = $this->config->get('config_alert_mail');
        }
        
        if (isset($this->request->post['config_account_mail'])) {
            $this->data['config_account_mail'] = $this->request->post['config_account_mail'];
        } else {
            $this->data['config_account_mail'] = $this->config->get('config_account_mail');
        }
        
        if (isset($this->request->post['config_alert_emails'])) {
            $this->data['config_alert_emails'] = $this->request->post['config_alert_emails'];
        } else {
            $this->data['config_alert_emails'] = $this->config->get('config_alert_emails');
        }        
        
        if (isset($this->request->post['config_secure'])) {
            $this->data['config_secure'] = $this->request->post['config_secure'];
        } else {
            $this->data['config_secure'] = $this->config->get('config_secure');
        }
        
        if (isset($this->request->post['config_shared'])) {
            $this->data['config_shared'] = $this->request->post['config_shared'];
        } else {
            $this->data['config_shared'] = $this->config->get('config_shared');
        }
        
        if (isset($this->request->post['config_robots'])) {
            $this->data['config_robots'] = $this->request->post['config_robots'];
        } else {
            $this->data['config_robots'] = $this->config->get('config_robots');
        }
        
        if (isset($this->request->post['config_seo_url'])) {
            $this->data['config_seo_url'] = $this->request->post['config_seo_url'];
        } else {
            $this->data['config_seo_url'] = $this->config->get('config_seo_url');
        }

         if (isset($this->request->post['config_index_category_pages'])) {
            $this->data['config_index_category_pages'] = $this->request->post['config_index_category_pages'];
        } else {
            $this->data['config_index_category_pages'] = $this->config->get('config_index_category_pages');
        }

        if (isset($this->request->post['config_index_manufacturer_pages'])) {
            $this->data['config_index_manufacturer_pages'] = $this->request->post['config_index_manufacturer_pages'];
        } else {
            $this->data['config_index_manufacturer_pages'] = $this->config->get('config_index_manufacturer_pages');
        }

        if (isset($this->request->post['google_sitemap_status'])) {
            $this->data['google_sitemap_status'] = $this->request->post['google_sitemap_status'];
        } else {
            $this->data['google_sitemap_status'] = $this->config->get('google_sitemap_status');
        }
        
        if (isset($this->request->post['config_seo_url_type'])) {
            $this->data['config_seo_url_type'] = $this->request->post['config_seo_url_type'];
        } elseif ($this->config->get('config_seo_url_type')) {
            $this->data['config_seo_url_type'] = $this->config->get('config_seo_url_type');
        } else {
            $this->data['config_seo_url_type'] = 'seo_url';
        }
        
        $this->data['seo_types'] = [];
        $this->data['seo_types'][] = array('type' => 'seo_url', 'name' => $this->language->get('text_seo_url'));
        $this->data['seo_types'][] = array('type' => 'seo_pro', 'name' => $this->language->get('text_seo_pro'));
        
        if (isset($this->request->post['config_seo_url_include_path'])) {
            $this->data['config_seo_url_include_path'] = $this->request->post['config_seo_url_include_path'];
        } else {
            $this->data['config_seo_url_include_path'] = $this->config->get('config_seo_url_include_path');
        }
        
        if (isset($this->request->post['config_seo_url_postfix'])) {
            $this->data['config_seo_url_postfix'] = $this->request->post['config_seo_url_postfix'];
        } else {
            $this->data['config_seo_url_postfix'] = $this->config->get('config_seo_url_postfix');
        }

        if (isset($this->request->post['config_seo_url_postfix'])) {
            $this->data['config_seo_url_postfix'] = $this->request->post['config_seo_url_postfix'];
        } else {
            $this->data['config_seo_url_postfix'] = $this->config->get('config_seo_url_postfix');
        }

        if (isset($this->request->post['config_seo_url_from_id'])) {
            $this->data['config_seo_url_from_id'] = $this->request->post['config_seo_url_from_id'];
        } else {
            $this->data['config_seo_url_from_id'] = $this->config->get('config_seo_url_from_id');
        }

        if (isset($this->request->post['config_seo_url_do_generate'])) {
            $this->data['config_seo_url_do_generate'] = $this->request->post['config_seo_url_do_not_generate'];
        } else {
            $this->data['config_seo_url_do_generate'] = $this->config->get('config_seo_url_do_generate');
        }

        if (isset($this->request->post['config_seo_url_do_redirect_to_new'])) {
            $this->data['config_seo_url_do_redirect_to_new'] = $this->request->post['config_seo_url_do_redirect_to_new'];
        } else {
            $this->data['config_seo_url_do_redirect_to_new'] = $this->config->get('config_seo_url_do_redirect_to_new');
        }

        if (isset($this->request->post['config_seo_url_do_redirect_to_new_with_language'])) {
            $this->data['config_seo_url_do_redirect_to_new_with_language'] = $this->request->post['config_seo_url_do_redirect_to_new_with_language'];
        } else {
            $this->data['config_seo_url_do_redirect_to_new_with_language'] = $this->config->get('config_seo_url_do_redirect_to_new_with_language');
        }

        if (isset($this->request->post['config_seo_url_do_redirect_to_new_lang_was_second'])) {
            $this->data['config_seo_url_do_redirect_to_new_lang_was_second'] = $this->request->post['config_seo_url_do_redirect_to_new_lang_was_second'];
        } else {
            $this->data['config_seo_url_do_redirect_to_new_lang_was_second'] = $this->config->get('config_seo_url_do_redirect_to_new_lang_was_second');
        }

        if (isset($this->request->post['config_seo_url_do_redirect_to_new_lang_became_second'])) {
            $this->data['config_seo_url_do_redirect_to_new_lang_became_second'] = $this->request->post['config_seo_url_do_redirect_to_new_lang_became_second'];
        } else {
            $this->data['config_seo_url_do_redirect_to_new_lang_became_second'] = $this->config->get('config_seo_url_do_redirect_to_new_lang_became_second');
        }
        
        if (isset($this->request->post['config_file_extension_allowed'])) {
            $this->data['config_file_extension_allowed'] = $this->request->post['config_file_extension_allowed'];
        } else {
            $this->data['config_file_extension_allowed'] = $this->config->get('config_file_extension_allowed');
        }
        
        if (isset($this->request->post['config_file_mime_allowed'])) {
            $this->data['config_file_mime_allowed'] = $this->request->post['config_file_mime_allowed'];
        } else {
            $this->data['config_file_mime_allowed'] = $this->config->get('config_file_mime_allowed');
        }
        
        if (isset($this->request->post['config_maintenance'])) {
            $this->data['config_maintenance'] = $this->request->post['config_maintenance'];
        } else {
            $this->data['config_maintenance'] = $this->config->get('config_maintenance');
        }
        
        if (isset($this->request->post['config_password'])) {
            $this->data['config_password'] = $this->request->post['config_password'];
        } else {
            $this->data['config_password'] = $this->config->get('config_password');
        }
        
        if (isset($this->request->post['config_encryption'])) {
            $this->data['config_encryption'] = $this->request->post['config_encryption'];
        } else {
            $this->data['config_encryption'] = $this->config->get('config_encryption');
        }
        
        if (isset($this->request->post['config_compression'])) {
            $this->data['config_compression'] = $this->request->post['config_compression'];
        } else {
            $this->data['config_compression'] = $this->config->get('config_compression');
        }
        
        if (isset($this->request->post['config_error_display'])) {
            $this->data['config_error_display'] = $this->request->post['config_error_display'];
        } else {
            $this->data['config_error_display'] = $this->config->get('config_error_display');
        }
        
        if (isset($this->request->post['config_error_log'])) {
            $this->data['config_error_log'] = $this->request->post['config_error_log'];
        } else {
            $this->data['config_error_log'] = $this->config->get('config_error_log');
        }
        
        if (isset($this->request->post['config_error_filename'])) {
            $this->data['config_error_filename'] = $this->request->post['config_error_filename'];
        } else {
            $this->data['config_error_filename'] = $this->config->get('config_error_filename');
        }
        
        if (isset($this->request->post['config_sms_to'])) {
            $this->data['config_sms_to'] = $this->request->post['config_sms_to'];
        } else {
            $this->data['config_sms_to'] = $this->config->get('config_sms_to');
        }

        $shipping_workers_settings = [            
            'config_shipping_enable_tracker_worker',
            'config_shipping_enable_tracker_worker_time_start',
            'config_shipping_enable_tracker_worker_time_end'
        ];

        foreach ($shipping_workers_settings as $shipping_workers_setting) {
            if (isset($this->request->post[$shipping_workers_setting])) {
                $this->data[$shipping_workers_setting] = $this->request->post[$shipping_workers_setting];
            } else {
                $this->data[$shipping_workers_setting] = $this->config->get($shipping_workers_setting);
            }
        }

        $sms_workers_settings = [
            'config_sms_enable_queue_worker',
            'config_sms_enable_queue_worker_time_start',
            'config_sms_enable_queue_worker_time_end'
        ];

        foreach ($sms_workers_settings as $sms_workers_setting) {
            if (isset($this->request->post[$sms_workers_setting])) {
                $this->data[$sms_workers_setting] = $this->request->post[$sms_workers_setting];
            } else {
                $this->data[$sms_workers_setting] = $this->config->get($sms_workers_setting);
            }
        }

        $this->load->model('catalog/actiontemplate');
        $forgotten_actiontemplates = $this->model_catalog_actiontemplate->getActionTemplates(['filter_use_for_forgotten' => true]);

        $this->data['forgotten_actiontemplates'] = [];
        foreach ($forgotten_actiontemplates as $forgotten_actiontemplate){
            $this->data['forgotten_actiontemplates'][] = [
                'actiontemplate_id'     => $forgotten_actiontemplate['actiontemplate_id'],
                'actiontemplate_title'  => $forgotten_actiontemplate['title'],
            ];
        }

        $forgottencart_config_keys = [
            'config_forgottencart_send_enable',
            'config_forgottencart_send_time_start',
            'config_forgottencart_send_time_end',

            'config_forgottencart_send_something_1',
            'config_forgottencart_promocode_1',
            'config_forgottencart_sms_enable_1',
            'config_forgottencart_sms_text_1',
            'config_forgottencart_email_enable_1',
            'config_forgottencart_email_template_1',
            'config_forgottencart_email_actiontemplate_1',
            'config_forgottencart_email_actiontemplate_tracking_code_1',
            'config_forgottencart_use_simplecheckout_carts_1',
            'config_forgottencart_use_zerostatus_orders_1',          
            'config_forgottencart_time_min_hours_1',
            'config_forgottencart_time_max_hours_1',

            'config_forgottencart_send_something_2',
            'config_forgottencart_promocode_2',
            'config_forgottencart_sms_enable_2',
            'config_forgottencart_sms_text_2',
            'config_forgottencart_email_enable_2',
            'config_forgottencart_email_template_2',
            'config_forgottencart_email_actiontemplate_2',
            'config_forgottencart_email_actiontemplate_tracking_code_2',
            'config_forgottencart_use_simplecheckout_carts_2',
            'config_forgottencart_use_zerostatus_orders_2',          
            'config_forgottencart_time_min_hours_2',
            'config_forgottencart_time_max_hours_2',
        ];

        foreach ($forgottencart_config_keys as $forgottencart_config_key) {
            if (isset($this->request->post[$forgottencart_config_key])) {
                $this->data[$forgottencart_config_key] = $this->request->post[$forgottencart_config_keys];
            } else {
                $this->data[$forgottencart_config_key] = $this->config->get($forgottencart_config_key);
            }
        }


        $firstorder_config_keys = [
          'config_firstorder_send_promocode',
          'config_firstorder_promocode',
          'config_firstorder_sms_enable',
          'config_firstorder_sms_text',
          'config_firstorder_email_enable',
          'config_firstorder_email_template'
        ];

        foreach ($firstorder_config_keys as $firstorder_config_key) {
            if (isset($this->request->post[$firstorder_config_key])) {
                $this->data[$firstorder_config_key] = $this->request->post[$firstorder_config_key];
            } else {
                $this->data[$firstorder_config_key] = $this->config->get($firstorder_config_key);
            }
        }

        $otp_auth_settings = [
            'config_otp_enable',
            'config_otp_auto_enable',
            'config_otp_enable_sms',
            'config_otp_enable_viber',
            'config_otp_enable_email',
            'config_sms_otp_text',
            'config_viber_otp_text',

            'config_restore_password_enable_sms',
            'config_restore_password_enable_email',
            'config_restore_password_enable_viber',
            'config_sms_restore_password_text',
            'config_viber_restore_password_text'
        ];

        foreach ($otp_auth_settings as $otp_auth_setting) {
            if (isset($this->request->post[$otp_auth_setting])) {
                $this->data[$otp_auth_setting] = $this->request->post[$otp_auth_setting];
            } else {
                $this->data[$otp_auth_setting] = $this->config->get($otp_auth_setting);
            }
        }
  
        $config_sms_keys = [
          'config_sms_tracker_leave_main_warehouse_enabled',
          'config_sms_tracker_leave_main_warehouse',
          'config_sms_payment_recieved_enabled',
          'config_sms_payment_recieved',
          'config_sms_ttn_sent_enabled',
          'config_sms_ttn_sent',
          'config_sms_ttn_ready_enabled',
          'config_sms_ttn_ready',
          'config_sms_payment_link_enabled',
          'config_sms_payment_link',
          'config_sms_birthday_greeting_enabled',
          'config_sms_birthday_greeting',
          'config_sms_send_new_order_status',
          'config_sms_new_order_status_message',
          'config_sms_transaction_text_type_1',
          'config_sms_transaction_text_type_2',
          'config_sms_transaction_text_type_3',
          'config_sms_send_new_order',
          'config_sms_new_order_message',
          'config_sms_alert',
          'config_sms_copy'
        ];

        foreach ($config_sms_keys as $sms_key) {
            if (isset($this->request->post[$sms_key])) {
                $this->data[$sms_key] = $this->request->post[$sms_key];
            } else {
                $this->data[$sms_key] = $this->config->get($sms_key); 
            }
        }

        $viberkeys = [
            'config_viber_send_new_order',
            'config_viber_new_order_message',
            'config_viber_new_order_image',
            'config_viber_new_order_button_text',
            'config_viber_new_order_button_url',  
            
            'config_viber_tracker_leave_main_warehouse_enabled',        
            'config_viber_tracker_leave_main_warehouse',
            'config_viber_tracker_leave_main_warehouse_image',
            'config_viber_tracker_leave_main_warehouse_button_text',
            'config_viber_tracker_leave_main_warehouse_button_url',

            'config_viber_payment_recieved_enabled',
            'config_viber_payment_recieved',
            'config_viber_payment_recieved_image',
            'config_viber_payment_recieved_button_text',
            'config_viber_payment_recieved_button_url',

            'config_viber_ttn_sent_enabled',
            'config_viber_ttn_sent',
            'config_viber_ttn_sent_image',
            'config_viber_ttn_sent_button_text',
            'config_viber_ttn_sent_button_url',

            'config_viber_ttn_ready_enabled',
            'config_viber_ttn_ready',
            'config_viber_ttn_ready_image',
            'config_viber_ttn_ready_button_text',
            'config_viber_ttn_ready_button_url',

            'config_viber_rewardpoints_reminder_enabled',
            'config_viber_rewardpoints_reminder',
            'config_viber_rewardpoints_reminder_image',
            'config_viber_rewardpoints_reminder_button_text',
            'config_viber_rewardpoints_reminder_button_url',

            'config_viber_rewardpoints_added_enabled',
            'config_viber_rewardpoints_added',
            'config_viber_rewardpoints_added_image',
            'config_viber_rewardpoints_added_button_text',
            'config_viber_rewardpoints_added_button_url',

            'config_viber_transaction_text_type_1',
            'config_viber_transaction_text_type_1_image',
            'config_viber_transaction_text_type_1_button_text',
            'config_viber_transaction_text_type_1_button_url',

            'config_viber_transaction_text_type_2',
            'config_viber_transaction_text_type_2_image',
            'config_viber_transaction_text_type_2_button_text',
            'config_viber_transaction_text_type_2_button_url',

            'config_viber_transaction_text_type_3',
            'config_viber_transaction_text_type_3_image',
            'config_viber_transaction_text_type_3_button_text',
            'config_viber_transaction_text_type_3_button_url',

            'config_viber_payment_link_enabled',
            'config_viber_payment_link',
            'config_viber_payment_link_image',
            'config_viber_payment_link_button_text',
            'config_viber_payment_link_button_url',            

            'config_viber_firstorder_enabled',
            'config_viber_firstorder',
            'config_viber_firstorder_image',
            'config_viber_firstorder_button_text',
            'config_viber_firstorder_button_url',

            'config_viber_birthday_greeting_enabled',
            'config_viber_birthday_greeting',
            'config_viber_birthday_greeting_image',
            'config_viber_birthday_greeting_button_text',
            'config_viber_birthday_greeting_button_url',

            'config_viber_forgottencart_enabled_1',
            'config_viber_forgottencart_1',
            'config_viber_forgottencart_1_image',
            'config_viber_forgottencart_1_image_product',
            'config_viber_forgottencart_1_image_product_if_single',
            'config_viber_forgottencart_button_text_1',
            'config_viber_forgottencart_button_url_1',

            'config_viber_forgottencart_enabled_2',
            'config_viber_forgottencart_2',
            'config_viber_forgottencart_2_image',
            'config_viber_forgottencart_2_image_product',
            'config_viber_forgottencart_2_image_product_if_single',
            'config_viber_forgottencart_button_text_2',
            'config_viber_forgottencart_button_url_2'
        ];
        
        foreach ($viberkeys as $viberkey) {
            if (isset($this->request->post[$viberkey])) {
                $this->data[$viberkey] = $this->request->post[$viberkey];
            } else {
                $this->data[$viberkey] = $this->config->get($viberkey);
            }

            if (strpos($viberkey, '_image')){
                $viberkey_image = str_replace('config_', '', $viberkey);

                if ($this->config->get($viberkey) && file_exists(DIR_IMAGE . $this->config->get($viberkey)) && is_file(DIR_IMAGE . $this->config->get($viberkey))) {
                    $this->data[$viberkey_image] = $this->model_tool_image->resize($this->config->get($viberkey), 200, 200);
                } else {
                    $this->data[$viberkey_image] = $this->model_tool_image->resize('no_image.jpg', 200, 200);
                }
            }
        }

        if (isset($this->request->post['config_viber_order_status_message'])) {
            $this->data['config_viber_order_status_message'] = $this->request->post['config_viber_order_status_message'];
        } else {
            $this->data['config_viber_order_status_message'] = (array)$this->config->get('config_viber_order_status_message');
        }

        $this->data['viber_order_status_message_image'] = [];

        if (!empty($this->data['config_viber_order_status_message'])){
            foreach ($this->data['config_viber_order_status_message'] as $order_status_id => $config_viber_order_status_message){            
                if ($config_viber_order_status_message['image'] && file_exists(DIR_IMAGE . $config_viber_order_status_message['image']) && is_file(DIR_IMAGE . $config_viber_order_status_message['image'])) {
                    $this->data['viber_order_status_message_image'][$order_status_id] = $this->model_tool_image->resize($config_viber_order_status_message['image'], 200, 200);
                } else {
                    $this->data['viber_order_status_message_image'][$order_status_id] = $this->model_tool_image->resize('no_image.jpg', 200, 200);
                }
            }
        } else {
            foreach ($this->data['order_statuses'] as $order_status){
                $this->data['viber_order_status_message_image'][$order_status['order_status_id']] = $this->model_tool_image->resize('no_image.jpg', 200, 200);
            }
        }
        
        $config_pixels_keys = [
          'config_google_analytics',
          'config_google_analytics_header',
          'config_fb_pixel_header',
          'config_fb_pixel_body',
          'config_vk_enable_pixel',
          'config_vk_pixel_id',
          'config_vk_pricelist_id',
          'config_vk_feed_only_in_stock',
          'config_vk_add_feed_for_category_id_0',
          'config_vk_add_feed_for_category_id_1',
          'config_vk_add_feed_for_category_id_2',
          'config_vk_ignore_general_brand_exclusion_for_category_id_0',
          'config_vk_ignore_general_brand_exclusion_for_category_id_1',
          'config_vk_ignore_general_brand_exclusion_for_category_id_2',
          'config_vk_pixel_header',
          'config_vk_pixel_body',
          'config_vk_feed_include_manufacturers',
          'config_gtm_header',
          'config_gtm_body',
          'config_preload_links',
          'config_sendpulse_script',
          'config_sendpulse_id',
          'config_onesignal_app_id',
          'config_onesignal_api_key', 
          'config_onesignal_safari_web_id',
          'config_google_analitycs_id',
          'config_google_conversion_id',
          'config_google_merchant_id',
          'config_google_merchant_feed_limit',
          'config_google_merchant_one_iteration_limit',
          'config_google_remarketing_type',
          'config_google_ecommerce_enable',
          'config_metrika_counter'
      ];

        foreach ($config_pixels_keys as $config_pixels_key) {
            if (isset($this->request->post[$config_pixels_key])) {
                $this->data[$config_pixels_key] = $this->request->post[$config_pixels_keys];
            } else {
                $this->data[$config_pixels_key] = $this->config->get($config_pixels_key);
            }
        }

        $assets_config = [
            'config_header_min_scripts',
            'config_header_excluded_scripts',
            'config_header_min_styles',
            'config_header_excluded_styles',
            'config_footer_min_scripts',
            'config_footer_min_styles',
            'config_footer_excluded_scripts',
            'config_footer_excluded_styles'
        ];

        foreach ($assets_config as $assets_config_key) {
            if (isset($this->request->post[$assets_config_key])) {
                $this->data[$assets_config_key] = $this->request->post[$assets_config_key];
            } else {
                $this->data[$assets_config_key] = $this->config->get($assets_config_key);
            }
        }        

        $assets_config_dev = [
            'config_header_min_scripts_dev',
            'config_preload_links_dev',
            'config_header_excluded_scripts_dev',
            'config_header_min_styles_dev',
            'config_header_excluded_styles_dev',
            'config_footer_min_scripts_dev',
            'config_footer_min_styles_dev',
            'config_footer_excluded_scripts_dev',
            'config_footer_excluded_styles_dev'
        ];

        foreach ($assets_config_dev as $assets_config_key_dev) {
            if (isset($this->request->post[$assets_config_key_dev])) {
                $this->data[$assets_config_key_dev] = $this->request->post[$assets_config_key_dev];
            } else {
                $this->data[$assets_config_key_dev] = $this->config->get($assets_config_key_dev);
            }
        }                       
        
        if (isset($this->request->post['config_odinass_soap_uri'])) {
            $this->data['config_odinass_soap_uri'] = $this->request->post['config_odinass_soap_uri'];
        } else {
            $this->data['config_odinass_soap_uri'] = $this->config->get('config_odinass_soap_uri');
        }

        if (isset($this->request->post['config_odinass_soap_user'])) {
            $this->data['config_odinass_soap_user'] = $this->request->post['config_odinass_soap_user'];
        } else {
            $this->data['config_odinass_soap_user'] = $this->config->get('config_odinass_soap_user');
        }

        if (isset($this->request->post['config_odinass_soap_passwd'])) {
            $this->data['config_odinass_soap_passwd'] = $this->request->post['config_odinass_soap_passwd'];
        } else {
            $this->data['config_odinass_soap_passwd'] = $this->config->get('config_odinass_soap_passwd');
        }

        if (isset($this->request->post['config_odinass_update_local_prices'])) {
            $this->data['config_odinass_update_local_prices'] = $this->request->post['config_odinass_update_local_prices'];
        } else {
            $this->data['config_odinass_update_local_prices'] = $this->config->get('config_odinass_update_local_prices');
        }

        if (isset($this->request->post['config_odinass_update_stockwaits'])) {
            $this->data['config_odinass_update_stockwaits'] = $this->request->post['config_odinass_update_stockwaits'];
        } else {
            $this->data['config_odinass_update_stockwaits'] = $this->config->get('config_odinass_update_stockwaits');
        }

        if (isset($this->request->post['config_bitrix_bot_enable'])) {
            $this->data['config_bitrix_bot_enable'] = $this->request->post['config_bitrix_bot_enable'];
        } else {
            $this->data['config_bitrix_bot_enable'] = $this->config->get('config_bitrix_bot_enable');
        }

        if (isset($this->request->post['config_bitrix_bot_domain'])) {
            $this->data['config_bitrix_bot_domain'] = $this->request->post['config_bitrix_bot_domain'];
        } else {
            $this->data['config_bitrix_bot_domain'] = $this->config->get('config_bitrix_bot_domain');
        }

        if (isset($this->request->post['config_bitrix_bot_scope'])) {
            $this->data['config_bitrix_bot_scope'] = $this->request->post['config_bitrix_bot_scope'];
        } else {
            $this->data['config_bitrix_bot_scope'] = $this->config->get('config_bitrix_bot_scope');
        }

        if (isset($this->request->post['config_bitrix_bot_client_id'])) {
            $this->data['config_bitrix_bot_client_id'] = $this->request->post['config_bitrix_bot_client_id'];
        } else {
            $this->data['config_bitrix_bot_client_id'] = $this->config->get('config_bitrix_bot_client_id');
        }

        if (isset($this->request->post['config_bitrix_bot_client_secret'])) {
            $this->data['config_bitrix_bot_client_secret'] = $this->request->post['config_bitrix_bot_client_secret'];
        } else {
            $this->data['config_bitrix_bot_client_secret'] = $this->config->get('config_bitrix_bot_client_secret');
        }

        if (isset($this->request->post['config_fixer_io_token'])) {
            $this->data['config_fixer_io_token'] = $this->request->post['config_fixer_io_token'];
        } else {
            $this->data['config_fixer_io_token'] = $this->config->get('config_fixer_io_token');
        }

         if (isset($this->request->post['config_currency_auto_threshold'])) {
            $this->data['config_currency_auto_threshold'] = $this->request->post['config_currency_auto_threshold'];
        } else {
            $this->data['config_currency_auto_threshold'] = $this->config->get('config_currency_auto_threshold');
        }

        if (isset($this->request->post['config_telegram_bot_enable_alerts'])) {
            $this->data['config_telegram_bot_enable_alerts'] = $this->request->post['config_telegram_bot_enable_alerts'];
        } else {
            $this->data['config_telegram_bot_enable_alerts'] = $this->config->get('config_telegram_bot_enable_alerts');
        }

        if (isset($this->request->post['config_telegram_bot_token'])) {
            $this->data['config_telegram_bot_token'] = $this->request->post['config_telegram_bot_token'];
        } else {
            $this->data['config_telegram_bot_token'] = $this->config->get('config_telegram_bot_token');
        }

        if (isset($this->request->post['config_telegram_bot_name'])) {
            $this->data['config_telegram_bot_name'] = $this->request->post['config_telegram_bot_name'];
        } else {
            $this->data['config_telegram_bot_name'] = $this->config->get('config_telegram_bot_name');
        }

       
        $openai_config_keys = [
          'config_openai_enable',
          'config_openai_api_key',
          'config_openai_default_model',
          'config_openai_enable_category_alternatenames',
          'config_openai_category_alternatenames_model',
          'config_openai_category_alternatenames_endpoint',
          'config_openai_category_alternatenames_maxtokens',
          'config_openai_category_alternatenames_temperature',
          'config_openai_category_alternatenames_top_p',
          'config_openai_category_alternatenames_freq_penalty',
          'config_openai_category_alternatenames_presence_penalty',
          'config_openai_enable_category_descriptions',
          'config_openai_category_descriptions_endpoint',
          'config_openai_category_descriptions_model',
          'config_openai_category_descriptions_maxtokens',
          'config_openai_category_descriptions_temperature',
          'config_openai_category_descriptions_top_p',
          'config_openai_category_descriptions_freq_penalty',
          'config_openai_category_descriptions_presence_penalty',
          'config_openai_enable_shorten_names',
          'config_openai_enable_shorten_names_before_translation',
          'config_openai_enable_shorten_names_after_translation',
          'config_openai_shortennames_endpoint',
          'config_openai_shortennames_model',
          'config_openai_shortennames_length',
          'config_openai_shortennames_maxtokens',
          'config_openai_shortennames_temperature',
          'config_openai_shortennames_top_p',
          'config_openai_shortennames_freq_penalty',
          'config_openai_shortennames_presence_penalty',
          'config_openai_enable_export_names',
          'config_openai_exportnames_model',
          'config_openai_exportnames_endpoint',
          'config_openai_exportnames_maxtokens',
          'config_openai_exportnames_length',
          'config_openai_exportnames_temperature',
          'config_openai_exportnames_top_p',
          'config_openai_exportnames_freq_penalty',
          'config_openai_exportnames_presence_penalty'  
        ];

        foreach ($openai_config_keys as $openai_config_key) {
            if (isset($this->request->post[$openai_config_key])) {
             $this->data[$openai_config_key] = $this->request->post[$openai_config_key];
            } else {
                $this->data[$openai_config_key] = $this->config->get($openai_config_key);
            }
        }

         foreach ($this->data['languages'] as $openai_language) {
            if (isset($this->request->post['config_openai_category_alternatenames_query_' . $openai_language['code']])) {
                $this->data['config_openai_category_alternatenames_query_' . $openai_language['code']] = $this->request->post['config_openai_category_alternatenames_query_' . $openai_language['code']];
            } else {
                $this->data['config_openai_category_alternatenames_query_' . $openai_language['code']] = $this->config->get('config_openai_category_alternatenames_query_' . $openai_language['code']);
            }

            if (isset($this->request->post['config_openai_category_descriptions_query_' . $openai_language['code']])) {
                $this->data['config_openai_category_descriptions_query_' . $openai_language['code']] = $this->request->post['config_openai_category_descriptions_query_' . $openai_language['code']];
            } else {
                $this->data['config_openai_category_descriptions_query_' . $openai_language['code']] = $this->config->get('config_openai_category_descriptions_query_' . $openai_language['code']);
            }

            if (isset($this->request->post['config_openai_shortennames_query_' . $openai_language['code']])) {
                $this->data['config_openai_shortennames_query_' . $openai_language['code']] = $this->request->post['config_openai_shortennames_query_' . $openai_language['code']];
            } else {
                $this->data['config_openai_shortennames_query_' . $openai_language['code']] = $this->config->get('config_openai_shortennames_query_' . $openai_language['code']);
            }

            if (isset($this->request->post['config_openai_exportnames_query_' . $openai_language['code']])) {
                $this->data['config_openai_exportnames_query_' . $openai_language['code']] = $this->request->post['config_openai_exportnames_query_' . $openai_language['code']];
            } else {
                $this->data['config_openai_exportnames_query_' . $openai_language['code']] = $this->config->get('config_openai_exportnames_query_' . $openai_language['code']);
            }
        }

        $this->data['openai_endpoints'] = [
            'chat',
            'completion',            
            'edit'
        ];

        if ($this->openaiAdaptor->OpenAI){
            $this->data['openai_models_list'] = json_decode($this->openaiAdaptor->OpenAI->listModels(), true);

            if (!empty($this->data['openai_models_list']['data'])){
                $this->data['openai_models_list'] = $this->data['openai_models_list']['data'];          
            }
        }
        

            //RNF
        if (isset($this->request->post['config_rainforest_enable_api'])) {
            $this->data['config_rainforest_enable_api'] = $this->request->post['config_rainforest_enable_api'];
        } else {
            $this->data['config_rainforest_enable_api'] = $this->config->get('config_rainforest_enable_api');
        }

        if (isset($this->request->post['config_enable_amazon_specific_modes'])) {
            $this->data['config_enable_amazon_specific_modes'] = $this->request->post['config_enable_amazon_specific_modes'];
        } else {
            $this->data['config_enable_amazon_specific_modes'] = $this->config->get('config_enable_amazon_specific_modes');
        }

        if (isset($this->request->post['config_enable_highload_admin_mode'])) {
            $this->data['config_enable_highload_admin_mode'] = $this->request->post['config_enable_highload_admin_mode'];
        } else {
            $this->data['config_enable_highload_admin_mode'] = $this->config->get('config_enable_highload_admin_mode');
        }

        if (isset($this->request->post['config_csvprice_pro_disable_manufacturers'])) {
            $this->data['config_csvprice_pro_disable_manufacturers'] = $this->request->post['config_csvprice_pro_disable_manufacturers'];
        } else {
            $this->data['config_csvprice_pro_disable_manufacturers'] = $this->config->get('config_csvprice_pro_disable_manufacturers');
        }

        if (isset($this->request->post['config_customer_filter_actiontemplates'])) {
            $this->data['config_customer_filter_actiontemplates'] = $this->request->post['config_customer_filter_actiontemplates'];
        } else {
            $this->data['config_customer_filter_actiontemplates'] = $this->config->get('config_customer_filter_actiontemplates');
        }

        if (isset($this->request->post['config_show_profitability_in_order_list'])) {
            $this->data['config_show_profitability_in_order_list'] = $this->request->post['config_show_profitability_in_order_list'];
        } else {
            $this->data['config_show_profitability_in_order_list'] = $this->config->get('config_show_profitability_in_order_list');
        }

        if (isset($this->request->post['config_sync_product_names_in_orders'])) {
            $this->data['config_sync_product_names_in_orders'] = $this->request->post['config_sync_product_names_in_orders'];
        } else {
            $this->data['config_sync_product_names_in_orders'] = $this->config->get('config_sync_product_names_in_orders');
        }

        if (isset($this->request->post['config_enable_amazon_asin_file_cache'])) {
            $this->data['config_enable_amazon_asin_file_cache'] = $this->request->post['config_enable_amazon_asin_file_cache'];
        } else {
            $this->data['config_enable_amazon_asin_file_cache'] = $this->config->get('config_enable_amazon_asin_file_cache');
        }

        if (isset($this->request->post['config_rainforest_asin_deletion_mode'])) {
            $this->data['config_rainforest_asin_deletion_mode'] = $this->request->post['config_rainforest_asin_deletion_mode'];
        } else {
            $this->data['config_rainforest_asin_deletion_mode'] = $this->config->get('config_rainforest_asin_deletion_mode');
        }

        $this->data['product_deletedasin'] = $this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token']);

        if (isset($this->request->post['config_rainforest_variant_edition_mode'])) {
            $this->data['config_rainforest_variant_edition_mode'] = $this->request->post['config_rainforest_variant_edition_mode'];
        } else {
            $this->data['config_rainforest_variant_edition_mode'] = $this->config->get('config_rainforest_variant_edition_mode');
        }

        if (isset($this->request->post['config_rainforest_translate_edition_mode'])) {
            $this->data['config_rainforest_translate_edition_mode'] = $this->request->post['config_rainforest_translate_edition_mode'];
        } else {
            $this->data['config_rainforest_translate_edition_mode'] = $this->config->get('config_rainforest_translate_edition_mode');
        }

        if (isset($this->request->post['config_rainforest_show_only_filled_products_in_catalog'])) {
            $this->data['config_rainforest_show_only_filled_products_in_catalog'] = $this->request->post['config_rainforest_show_only_filled_products_in_catalog'];
        } else {
            $this->data['config_rainforest_show_only_filled_products_in_catalog'] = $this->config->get('config_rainforest_show_only_filled_products_in_catalog');
        }
        
        if (isset($this->request->post['config_rainforest_api_key'])) {
            $this->data['config_rainforest_api_key'] = $this->request->post['config_rainforest_api_key'];
        } else {
            $this->data['config_rainforest_api_key'] = $this->config->get('config_rainforest_api_key');
        }
        
        if (isset($this->request->post['config_rainforest_api_domain_1'])) {
            $this->data['config_rainforest_api_domain_1'] = $this->request->post['config_rainforest_api_domain_1'];
        } else {
            $this->data['config_rainforest_api_domain_1'] = $this->config->get('config_rainforest_api_domain_1');
        }

        if (isset($this->request->post['config_rainforest_enable_translation'])) {
            $this->data['config_rainforest_enable_translation'] = $this->request->post['config_rainforest_enable_translation'];
        } else {
            $this->data['config_rainforest_enable_translation'] = $this->config->get('config_rainforest_enable_translation');
        }

        if (isset($this->request->post['config_rainforest_category_model'])) {
            $this->data['config_rainforest_category_model'] = $this->request->post['config_rainforest_category_model'];
        } else {
            $this->data['config_rainforest_category_model'] = $this->config->get('config_rainforest_category_model');
        }

        if (isset($this->request->post['config_rainforest_enable_review_adding'])) {
            $this->data['config_rainforest_enable_review_adding'] = $this->request->post['config_rainforest_enable_review_adding'];
        } else {
            $this->data['config_rainforest_enable_review_adding'] = $this->config->get('config_rainforest_enable_review_adding');
        }

        if (isset($this->request->post['config_rainforest_max_review_per_product'])) {
            $this->data['config_rainforest_max_review_per_product'] = $this->request->post['config_rainforest_max_review_per_product'];
        } else {
            $this->data['config_rainforest_max_review_per_product'] = $this->config->get('config_rainforest_max_review_per_product');
        }

        if (isset($this->request->post['config_rainforest_min_review_rating'])) {
            $this->data['config_rainforest_min_review_rating'] = $this->request->post['config_rainforest_min_review_rating'];
        } else {
            $this->data['config_rainforest_min_review_rating'] = $this->config->get('config_rainforest_min_review_rating');
        }

        if (isset($this->request->post['config_rainforest_max_review_length'])) {
            $this->data['config_rainforest_max_review_length'] = $this->request->post['config_rainforest_max_review_length'];
        } else {
            $this->data['config_rainforest_max_review_length'] = $this->config->get('config_rainforest_max_review_length');
        }

        if (isset($this->request->post['config_rainforest_enable_recursive_adding'])) {
            $this->data['config_rainforest_enable_recursive_adding'] = $this->request->post['config_rainforest_enable_recursive_adding'];
        } else {
            $this->data['config_rainforest_enable_recursive_adding'] = $this->config->get('config_rainforest_enable_recursive_adding');
        }

        if (isset($this->request->post['config_rainforest_default_technical_category_id'])) {
            $this->data['config_rainforest_default_technical_category_id'] = $this->request->post['config_rainforest_default_technical_category_id'];
        } else {
            $this->data['config_rainforest_default_technical_category_id'] = $this->config->get('config_rainforest_default_technical_category_id');
        }

        if (isset($this->request->post['config_rainforest_default_unknown_category_id'])) {
            $this->data['config_rainforest_default_unknown_category_id'] = $this->request->post['config_rainforest_default_unknown_category_id'];
        } else {
            $this->data['config_rainforest_default_unknown_category_id'] = $this->config->get('config_rainforest_default_unknown_category_id');
        }

        if (isset($this->request->post['config_rainforest_check_technical_category_id'])) {
            $this->data['config_rainforest_check_technical_category_id'] = $this->request->post['config_rainforest_check_technical_category_id'];
        } else {
            $this->data['config_rainforest_check_technical_category_id'] = $this->config->get('config_rainforest_check_technical_category_id');
        }

        if (isset($this->request->post['config_rainforest_check_unknown_category_id'])) {
            $this->data['config_rainforest_check_unknown_category_id'] = $this->request->post['config_rainforest_check_unknown_category_id'];
        } else {
            $this->data['config_rainforest_check_unknown_category_id'] = $this->config->get('config_rainforest_check_unknown_category_id');
        }

        if (isset($this->request->post['config_rainforest_enable_compare_with_similar_adding'])) {
            $this->data['config_rainforest_enable_compare_with_similar_adding'] = $this->request->post['config_rainforest_enable_compare_with_similar_adding'];
        } else {
            $this->data['config_rainforest_enable_compare_with_similar_adding'] = $this->config->get('config_rainforest_enable_compare_with_similar_adding');
        }

        if (isset($this->request->post['config_rainforest_enable_related_adding'])) {
            $this->data['config_rainforest_enable_related_adding'] = $this->request->post['config_rainforest_enable_related_adding'];
        } else {
            $this->data['config_rainforest_enable_related_adding'] = $this->config->get('config_rainforest_enable_related_adding');
        }

        if (isset($this->request->post['config_rainforest_enable_sponsored_adding'])) {
            $this->data['config_rainforest_enable_sponsored_adding'] = $this->request->post['config_rainforest_enable_sponsored_adding'];
        } else {
            $this->data['config_rainforest_enable_sponsored_adding'] = $this->config->get('config_rainforest_enable_sponsored_adding');
        }

        if (isset($this->request->post['config_rainforest_enable_similar_to_consider_adding'])) {
            $this->data['config_rainforest_enable_similar_to_consider_adding'] = $this->request->post['config_rainforest_enable_similar_to_consider_adding'];
        } else {
            $this->data['config_rainforest_enable_similar_to_consider_adding'] = $this->config->get('config_rainforest_enable_similar_to_consider_adding');
        }

        if (isset($this->request->post['config_rainforest_enable_view_to_purchase_adding'])) {
            $this->data['config_rainforest_enable_view_to_purchase_adding'] = $this->request->post['config_rainforest_enable_view_to_purchase_adding'];
        } else {
            $this->data['config_rainforest_enable_view_to_purchase_adding'] = $this->config->get('config_rainforest_enable_view_to_purchase_adding');
        }

        if (isset($this->request->post['config_rainforest_enable_also_viewed_adding'])) {
            $this->data['config_rainforest_enable_also_viewed_adding'] = $this->request->post['config_rainforest_enable_also_viewed_adding'];
        } else {
            $this->data['config_rainforest_enable_also_viewed_adding'] = $this->config->get('config_rainforest_enable_also_viewed_adding');
        }

        if (isset($this->request->post['config_rainforest_enable_also_bought_adding'])) {
            $this->data['config_rainforest_enable_also_bought_adding'] = $this->request->post['config_rainforest_enable_also_bought_adding'];
        } else {
            $this->data['config_rainforest_enable_also_bought_adding'] = $this->config->get('config_rainforest_enable_also_bought_adding');
        }

        if (isset($this->request->post['config_rainforest_enable_shop_by_look_adding'])) {
            $this->data['config_rainforest_enable_shop_by_look_adding'] = $this->request->post['config_rainforest_enable_shop_by_look_adding'];
        } else {
            $this->data['config_rainforest_enable_shop_by_look_adding'] = $this->config->get('config_rainforest_enable_shop_by_look_adding');
        }

        if (isset($this->request->post['config_rainforest_enable_compare_with_similar_parsing'])) {
            $this->data['config_rainforest_enable_compare_with_similar_parsing'] = $this->request->post['config_rainforest_enable_compare_with_similar_parsing'];
        } else {
            $this->data['config_rainforest_enable_compare_with_similar_parsing'] = $this->config->get('config_rainforest_enable_compare_with_similar_parsing');
        }

        if (isset($this->request->post['config_rainforest_enable_related_parsing'])) {
            $this->data['config_rainforest_enable_related_parsing'] = $this->request->post['config_rainforest_enable_related_parsing'];
        } else {
            $this->data['config_rainforest_enable_related_parsing'] = $this->config->get('config_rainforest_enable_related_parsing');
        }

        if (isset($this->request->post['config_rainforest_enable_sponsored_parsing'])) {
            $this->data['config_rainforest_enable_sponsored_parsing'] = $this->request->post['config_rainforest_enable_sponsored_parsing'];
        } else {
            $this->data['config_rainforest_enable_sponsored_parsing'] = $this->config->get('config_rainforest_enable_sponsored_parsing');
        }

        if (isset($this->request->post['config_rainforest_enable_similar_to_consider_parsing'])) {
            $this->data['config_rainforest_enable_similar_to_consider_parsing'] = $this->request->post['config_rainforest_enable_similar_to_consider_parsing'];
        } else {
            $this->data['config_rainforest_enable_similar_to_consider_parsing'] = $this->config->get('config_rainforest_enable_similar_to_consider_parsing');
        }

        if (isset($this->request->post['config_rainforest_enable_view_to_purchase_parsing'])) {
            $this->data['config_rainforest_enable_view_to_purchase_parsing'] = $this->request->post['config_rainforest_enable_view_to_purchase_parsing'];
        } else {
            $this->data['config_rainforest_enable_view_to_purchase_parsing'] = $this->config->get('config_rainforest_enable_view_to_purchase_parsing');
        }

        if (isset($this->request->post['config_rainforest_enable_also_viewed_parsing'])) {
            $this->data['config_rainforest_enable_also_viewed_parsing'] = $this->request->post['config_rainforest_enable_also_viewed_parsing'];
        } else {
            $this->data['config_rainforest_enable_also_viewed_parsing'] = $this->config->get('config_rainforest_enable_also_viewed_parsing');
        }

        if (isset($this->request->post['config_rainforest_enable_also_bought_parsing'])) {
            $this->data['config_rainforest_enable_also_bought_parsing'] = $this->request->post['config_rainforest_enable_also_bought_parsing'];
        } else {
            $this->data['config_rainforest_enable_also_bought_parsing'] = $this->config->get('config_rainforest_enable_also_bought_parsing');
        }

        if (isset($this->request->post['config_rainforest_enable_shop_by_look_parsing'])) {
            $this->data['config_rainforest_enable_shop_by_look_parsing'] = $this->request->post['config_rainforest_enable_shop_by_look_parsing'];
        } else {
            $this->data['config_rainforest_enable_shop_by_look_parsing'] = $this->config->get('config_rainforest_enable_shop_by_look_parsing');
        }

        if (isset($this->request->post['config_rainforest_enable_auto_tree'])) {
            $this->data['config_rainforest_enable_auto_tree'] = $this->request->post['config_rainforest_enable_auto_tree'];
        } else {
            $this->data['config_rainforest_enable_auto_tree'] = $this->config->get('config_rainforest_enable_auto_tree');
        }

        if (isset($this->request->post['config_rainforest_root_categories'])) {
            $this->data['config_rainforest_root_categories'] = $this->request->post['config_rainforest_root_categories'];
        } else {
            $this->data['config_rainforest_root_categories'] = $this->config->get('config_rainforest_root_categories');
        }

        if (isset($this->request->post['config_rainforest_export_names_with_openai'])) {
            $this->data['config_rainforest_export_names_with_openai'] = $this->request->post['config_rainforest_export_names_with_openai'];
        } else {
            $this->data['config_rainforest_export_names_with_openai'] = $this->config->get('config_rainforest_export_names_with_openai');
        }

        if (isset($this->request->post['config_rainforest_short_names_with_openai'])) {
            $this->data['config_rainforest_short_names_with_openai'] = $this->request->post['config_rainforest_short_names_with_openai'];
        } else {
            $this->data['config_rainforest_short_names_with_openai'] = $this->config->get('config_rainforest_short_names_with_openai');
        }

        if (isset($this->request->post['config_rainforest_source_language'])) {
            $this->data['config_rainforest_source_language'] = $this->request->post['config_rainforest_source_language'];
        } else {
            $this->data['config_rainforest_source_language'] = $this->config->get('config_rainforest_source_language');
        }

        if (isset($this->request->post['config_rainforest_description_symbol_limit'])) {
            $this->data['config_rainforest_description_symbol_limit'] = $this->request->post['config_rainforest_description_symbol_limit'];
        } else {
            $this->data['config_rainforest_description_symbol_limit'] = $this->config->get('config_rainforest_description_symbol_limit');
        }

        $this->load->model('setting/store');            
        $this->data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['config_rainforest_add_to_stores'])) {
            $this->data['config_rainforest_add_to_stores'] = $this->request->post['config_rainforest_add_to_stores'];
        } else {
            $this->data['config_rainforest_add_to_stores'] = [0];
        }

        foreach ($this->data['languages'] as $rnf_language) {
            if ($rnf_language['code'] != $this->data['config_rainforest_source_language']) {
                if (isset($this->request->post['config_rainforest_enable_language_' . $rnf_language['code']])) {
                    $this->data['config_rainforest_enable_language_' . $rnf_language['code']] = $this->request->post['config_rainforest_enable_language_' . $rnf_language['code']];
                } else {
                    $this->data['config_rainforest_enable_language_' . $rnf_language['code']] = $this->config->get('config_rainforest_enable_language_' . $rnf_language['code']);
                }
            }
        }

        foreach ($this->data['languages'] as $rnf_language) {
            if ($rnf_language['code'] != $this->data['config_rainforest_source_language']) {
                if (isset($this->request->post['config_rainforest_external_enable_language_' . $rnf_language['code']])) {
                    $this->data['config_rainforest_external_enable_language_' . $rnf_language['code']] = $this->request->post['config_rainforest_external_enable_language_' . $rnf_language['code']];
                } else {
                    $this->data['config_rainforest_external_enable_language_' . $rnf_language['code']] = $this->config->get('config_rainforest_external_enable_language_' . $rnf_language['code']);
                }
            }
        }

        if (isset($this->request->post['config_rainforest_max_variants'])) {
            $this->data['config_rainforest_max_variants'] = $this->request->post['config_rainforest_max_variants'];
        } else {
            $this->data['config_rainforest_max_variants'] = $this->config->get('config_rainforest_max_variants');
        }

        if (isset($this->request->post['config_rainforest_skip_variants'])) {
            $this->data['config_rainforest_skip_variants'] = $this->request->post['config_rainforest_skip_variants'];
        } else {
            $this->data['config_rainforest_skip_variants'] = $this->config->get('config_rainforest_skip_variants');
        }

        if (isset($this->request->post['config_rainforest_skip_min_offers_products'])) {
            $this->data['config_rainforest_skip_min_offers_products'] = $this->request->post['config_rainforest_skip_min_offers_products'];
        } else {
            $this->data['config_rainforest_skip_min_offers_products'] = $this->config->get('config_rainforest_skip_min_offers_products');
        }

        if (isset($this->request->post['config_rainforest_skip_high_price_products'])) {
            $this->data['config_rainforest_skip_high_price_products'] = $this->request->post['config_rainforest_skip_high_price_products'];
        } else {
            $this->data['config_rainforest_skip_high_price_products'] = $this->config->get('config_rainforest_skip_high_price_products');
        }

        if (isset($this->request->post['config_rainforest_skip_low_price_products'])) {
            $this->data['config_rainforest_skip_low_price_products'] = $this->request->post['config_rainforest_skip_low_price_products'];
        } else {
            $this->data['config_rainforest_skip_low_price_products'] = $this->config->get('config_rainforest_skip_low_price_products');
        }

        if (isset($this->request->post['config_rainforest_drop_low_price_products'])) {
            $this->data['config_rainforest_drop_low_price_products'] = $this->request->post['config_rainforest_drop_low_price_products'];
        } else {
            $this->data['config_rainforest_drop_low_price_products'] = $this->config->get('config_rainforest_drop_low_price_products');
        }

        if (isset($this->request->post['config_rainforest_update_period'])) {
            $this->data['config_rainforest_update_period'] = $this->request->post['config_rainforest_update_period'];
        } else {
            $this->data['config_rainforest_update_period'] = $this->config->get('config_rainforest_update_period');
        }

        if (isset($this->request->post['config_rainforest_category_update_period'])) {
            $this->data['config_rainforest_category_update_period'] = $this->request->post['config_rainforest_category_update_period'];
        } else {
            $this->data['config_rainforest_category_update_period'] = $this->config->get('config_rainforest_category_update_period');
        }

        if (isset($this->request->post['config_rainforest_tg_alert_group_id'])) {
            $this->data['config_rainforest_tg_alert_group_id'] = $this->request->post['config_rainforest_tg_alert_group_id'];
        } else {
            $this->data['config_rainforest_tg_alert_group_id'] = $this->config->get('config_rainforest_tg_alert_group_id');
        }

        if (isset($this->request->post['config_rainforest_enable_pricing'])) {
            $this->data['config_rainforest_enable_pricing'] = $this->request->post['config_rainforest_enable_pricing'];
        } else {
            $this->data['config_rainforest_enable_pricing'] = $this->config->get('config_rainforest_enable_pricing');
        }

        if (isset($this->request->post['config_rainforest_enable_offers_only_for_filled'])) {
            $this->data['config_rainforest_enable_offers_only_for_filled'] = $this->request->post['config_rainforest_enable_offers_only_for_filled'];
        } else {
            $this->data['config_rainforest_enable_offers_only_for_filled'] = $this->config->get('config_rainforest_enable_offers_only_for_filled');
        }

        if (isset($this->request->post['config_rainforest_enable_offers_after_order'])) {
            $this->data['config_rainforest_enable_offers_after_order'] = $this->request->post['config_rainforest_enable_offers_after_order'];
        } else {
            $this->data['config_rainforest_enable_offers_after_order'] = $this->config->get('config_rainforest_enable_offers_after_order');
        }

        if (isset($this->request->post['config_rainforest_disable_offers_if_has_special'])) {
            $this->data['config_rainforest_disable_offers_if_has_special'] = $this->request->post['config_rainforest_disable_offers_if_has_special'];
        } else {
            $this->data['config_rainforest_disable_offers_if_has_special'] = $this->config->get('config_rainforest_disable_offers_if_has_special');
        }

        if (isset($this->request->post['config_rainforest_disable_offers_use_field_ignore_parse'])) {
            $this->data['config_rainforest_disable_offers_use_field_ignore_parse'] = $this->request->post['config_rainforest_disable_offers_use_field_ignore_parse'];
        } else {
            $this->data['config_rainforest_disable_offers_use_field_ignore_parse'] = $this->config->get('config_rainforest_disable_offers_use_field_ignore_parse');
        }

        if (isset($this->request->post['config_rainforest_delay_price_setting'])) {
            $this->data['config_rainforest_delay_price_setting'] = $this->request->post['config_rainforest_delay_price_setting'];
        } else {
            $this->data['config_rainforest_delay_price_setting'] = $this->config->get('config_rainforest_delay_price_setting');
        }

        if (isset($this->request->post['config_rainforest_delay_stock_setting'])) {
            $this->data['config_rainforest_delay_stock_setting'] = $this->request->post['config_rainforest_delay_stock_setting'];
        } else {
            $this->data['config_rainforest_delay_stock_setting'] = $this->config->get('config_rainforest_delay_stock_setting');
        }

        if (isset($this->request->post['config_rainforest_enable_offers_for_stock'])) {
            $this->data['config_rainforest_enable_offers_for_stock'] = $this->request->post['config_rainforest_enable_offers_for_stock'];
        } else {
            $this->data['config_rainforest_enable_offers_for_stock'] = $this->config->get('config_rainforest_enable_offers_for_stock');
        }

        if (isset($this->request->post['config_rainforest_enable_offers_for_added_from_amazon'])) {
            $this->data['config_rainforest_enable_offers_for_added_from_amazon'] = $this->request->post['config_rainforest_enable_offers_for_added_from_amazon'];
        } else {
            $this->data['config_rainforest_enable_offers_for_added_from_amazon'] = $this->config->get('config_rainforest_enable_offers_for_added_from_amazon');
        }

        if (isset($this->request->post['config_rainforest_pass_offers_for_ordered'])) {
            $this->data['config_rainforest_pass_offers_for_ordered'] = $this->request->post['config_rainforest_pass_offers_for_ordered'];
        } else {
            $this->data['config_rainforest_pass_offers_for_ordered'] = $this->config->get('config_rainforest_pass_offers_for_ordered');
        }

        if (isset($this->request->post['config_rainforest_pass_offers_for_ordered_days'])) {
            $this->data['config_rainforest_pass_offers_for_ordered_days'] = $this->request->post['config_rainforest_pass_offers_for_ordered_days'];
        } else {
            $this->data['config_rainforest_pass_offers_for_ordered_days'] = $this->config->get('config_rainforest_pass_offers_for_ordered_days');
        }

        if (isset($this->request->post['config_rainforest_main_formula'])) {
            $this->data['config_rainforest_main_formula'] = $this->request->post['config_rainforest_main_formula'];
        } else {
            $this->data['config_rainforest_main_formula'] = $this->config->get('config_rainforest_main_formula');
        }

        if (isset($this->request->post['config_rainforest_main_formula_count'])) {
            $this->data['config_rainforest_main_formula_count'] = $this->request->post['config_rainforest_main_formula_count'];
        } else {
            $this->data['config_rainforest_main_formula_count'] = $this->config->get('config_rainforest_main_formula_count');
        }

        for ($crmfc = 1; $crmfc <= $this->data['config_rainforest_main_formula_count']; $crmfc++){

            if (isset($this->request->post['config_rainforest_main_formula_overload_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_overload_' . $crmfc] = $this->request->post['config_rainforest_main_formula_overload_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_overload_' . $crmfc] = $this->config->get('config_rainforest_main_formula_overload_' . $crmfc);
            }

            if (isset($this->request->post['config_rainforest_main_formula_min_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_min_' . $crmfc] = $this->request->post['config_rainforest_main_formula_min_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_min_' . $crmfc] = $this->config->get('config_rainforest_main_formula_min_' . $crmfc);
            }

            if (isset($this->request->post['config_rainforest_main_formula_default_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_default_' . $crmfc] = $this->request->post['config_rainforest_main_formula_default_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_default_' . $crmfc] = $this->config->get('config_rainforest_main_formula_default_' . $crmfc);
            }

            if (isset($this->request->post['config_rainforest_main_formula_costprice_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_costprice_' . $crmfc] = $this->request->post['config_rainforest_main_formula_costprice_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_costprice_' . $crmfc] = $this->config->get('config_rainforest_main_formula_costprice_' . $crmfc);
            }

            if (isset($this->request->post['config_rainforest_main_formula_max_' . $crmfc])) {
                $this->data['config_rainforest_main_formula_max_' . $crmfc] = $this->request->post['config_rainforest_main_formula_max_' . $crmfc];
            } else {
                $this->data['config_rainforest_main_formula_max_' . $crmfc] = $this->config->get('config_rainforest_main_formula_max_' . $crmfc);
            }
        }

        if (isset($this->request->post['config_rainforest_supplierminrating'])) {
            $this->data['config_rainforest_supplierminrating'] = $this->request->post['config_rainforest_supplierminrating'];
        } else {
            $this->data['config_rainforest_supplierminrating'] = $this->config->get('config_rainforest_supplierminrating');
        }

        if (isset($this->request->post['config_rainforest_supplierminrating_inner'])) {
            $this->data['config_rainforest_supplierminrating_inner'] = $this->request->post['config_rainforest_supplierminrating_inner'];
        } else {
            $this->data['config_rainforest_supplierminrating_inner'] = $this->config->get('config_rainforest_supplierminrating_inner');
        }

        if (isset($this->request->post['config_rainforest_volumetric_max_wc_multiplier'])) {
            $this->data['config_rainforest_volumetric_max_wc_multiplier'] = $this->request->post['config_rainforest_volumetric_max_wc_multiplier'];
        } else {
            $this->data['config_rainforest_volumetric_max_wc_multiplier'] = $this->config->get('config_rainforest_volumetric_max_wc_multiplier');
        }

        if (isset($this->request->post['config_rainforest_default_store_id'])) {
            $this->data['config_rainforest_default_store_id'] = $this->request->post['config_rainforest_default_store_id'];
        } else {
            $this->data['config_rainforest_default_store_id'] = $this->config->get('config_rainforest_default_store_id');
        }

        if (isset($this->request->post['config_rainforest_nooffers_quantity'])) {
            $this->data['config_rainforest_nooffers_quantity'] = $this->request->post['config_rainforest_nooffers_quantity'];
        } else {
            $this->data['config_rainforest_nooffers_quantity'] = $this->config->get('config_rainforest_nooffers_quantity');
        }

        if (isset($this->request->post['config_rainforest_nooffers_action'])) {
            $this->data['config_rainforest_nooffers_action'] = $this->request->post['config_rainforest_nooffers_action'];
        } else {
            $this->data['config_rainforest_nooffers_action'] = $this->config->get('config_rainforest_nooffers_action');
        }

        if (isset($this->request->post['config_rainforest_nooffers_status_id'])) {
            $this->data['config_rainforest_nooffers_status_id'] = $this->request->post['config_rainforest_nooffers_status_id'];
        } else {
            $this->data['config_rainforest_nooffers_status_id'] = $this->config->get('config_rainforest_nooffers_status_id');
        }

        if (isset($this->request->post['config_rainforest_delete_no_offers'])) {
            $this->data['config_rainforest_delete_no_offers'] = $this->request->post['config_rainforest_delete_no_offers'];
        } else {
            $this->data['config_rainforest_delete_no_offers'] = $this->config->get('config_rainforest_delete_no_offers');
        }

        if (isset($this->request->post['config_rainforest_delete_no_offers_counter'])) {
            $this->data['config_rainforest_delete_no_offers_counter'] = $this->request->post['config_rainforest_delete_no_offers_counter'];
        } else {
            $this->data['config_rainforest_delete_no_offers_counter'] = $this->config->get('config_rainforest_delete_no_offers_counter');
        }

        if (isset($this->request->post['config_rainforest_delete_invalid_asins'])) {
            $this->data['config_rainforest_delete_invalid_asins'] = $this->request->post['config_rainforest_delete_invalid_asins'];
        } else {
            $this->data['config_rainforest_delete_invalid_asins'] = $this->config->get('config_rainforest_delete_invalid_asins');
        }

         if (isset($this->request->post['config_rainforest_auto_create_manufacturers'])) {
            $this->data['config_rainforest_auto_create_manufacturers'] = $this->request->post['config_rainforest_auto_create_manufacturers'];
        } else {
            $this->data['config_rainforest_auto_create_manufacturers'] = $this->config->get('config_rainforest_auto_create_manufacturers');
        }

        if (isset($this->request->post['config_rainforest_max_delivery_days_for_offer'])) {
            $this->data['config_rainforest_max_delivery_days_for_offer'] = $this->request->post['config_rainforest_max_delivery_days_for_offer'];
        } else {
            $this->data['config_rainforest_max_delivery_days_for_offer'] = $this->config->get('config_rainforest_max_delivery_days_for_offer');
        }

         if (isset($this->request->post['config_rainforest_max_delivery_price'])) {
            $this->data['config_rainforest_max_delivery_price'] = $this->request->post['config_rainforest_max_delivery_price'];
        } else {
            $this->data['config_rainforest_max_delivery_price'] = $this->config->get('config_rainforest_max_delivery_price');
        }

         if (isset($this->request->post['config_rainforest_max_delivery_price_multiplier'])) {
            $this->data['config_rainforest_max_delivery_price_multiplier'] = $this->request->post['config_rainforest_max_delivery_price_multiplier'];
        } else {
            $this->data['config_rainforest_max_delivery_price_multiplier'] = $this->config->get('config_rainforest_max_delivery_price_multiplier');
        }

        $social_auth_config = [
            'social_auth_facebook_app_id',
            'social_auth_facebook_secret_key',
            'social_auth_google_app_id',
            'social_auth_google_secret_key',
            'social_auth_google_enable_sso_widget',
            'social_auth_yandex_client_id',
            'social_auth_yandex_secret_key',
            'social_auth_yandex_enable_sso_widget',
            'social_auth_mailru_client_id',
            'social_auth_mailru_secret_key',
            'social_auth_mailru_enable_sso_widget',
            'social_auth_insatagram_client_id',
            'social_auth_insatagram_secret_key'
        ];

        foreach ($social_auth_config as $social_auth_config_key) {
            if (isset($this->request->post[$social_auth_config_key])) {
                $this->data[$social_auth_config_key] = $this->request->post[$social_auth_config_key];
            } else {
                $this->data[$social_auth_config_key] = $this->config->get($social_auth_config_key);
            }
        }

         $social_links_config = [
            'social_link_facebook',
            'social_link_vkontakte',
            'social_link_instagram',
            'social_link_youtube',
            'social_link_twitter',
            'social_link_telegram',
            'social_link_viber_bot',
            'social_link_telegram_bot',
            'social_link_messenger_bot',
            'social_link_vkontakte_bot',
            'social_link_whatsapp_bot',
        ];

        foreach ($social_links_config as $social_links_config_key) {
            if (isset($this->request->post[$social_links_config_key])) {
                $this->data[$social_links_config_key] = $this->request->post[$social_links_config_key];
            } else {
                $this->data[$social_links_config_key] = $this->config->get($social_links_config_key);
            }
        }
        

        $this->load->model('setting/store');
        $stores = $this->data['stores'] = $this->model_setting_store->getStores();

        foreach ($stores as $store) {
            if (isset($this->request->post['config_rainforest_kg_price_' . $store['store_id']])) {
                $this->data['config_rainforest_kg_price_' . $store['store_id']] = $this->request->post['config_rainforest_kg_price_' . $store['store_id']];
            } else {
                $this->data['config_rainforest_kg_price_' . $store['store_id']] = $this->config->get('config_rainforest_kg_price_' . $store['store_id']);
            }
        }

        foreach ($stores as $store) {
            if (isset($this->request->post['config_rainforest_default_multiplier_' . $store['store_id']])) {
                $this->data['config_rainforest_default_multiplier_' . $store['store_id']] = $this->request->post['config_rainforest_default_multiplier_' . $store['store_id']];
            } else {
                $this->data['config_rainforest_default_multiplier_' . $store['store_id']] = $this->config->get('config_rainforest_default_multiplier_' . $store['store_id']);
            }

            if (isset($this->request->post['config_rainforest_default_costprice_multiplier_' . $store['store_id']])) {
                $this->data['config_rainforest_default_costprice_multiplier_' . $store['store_id']] = $this->request->post['config_rainforest_default_costprice_multiplier_' . $store['store_id']];
            } else {
                $this->data['config_rainforest_default_costprice_multiplier_' . $store['store_id']] = $this->config->get('config_rainforest_default_costprice_multiplier_' . $store['store_id']);
            }
        }

        foreach ($stores as $store) {
            if (isset($this->request->post['config_rainforest_max_multiplier_' . $store['store_id']])) {
                $this->data['config_rainforest_max_multiplier_' . $store['store_id']] = $this->request->post['config_rainforest_max_multiplier_' . $store['store_id']];
            } else {
                $this->data['config_rainforest_max_multiplier_' . $store['store_id']] = $this->config->get('config_rainforest_max_multiplier_' . $store['store_id']);
            }
        }

        foreach ($stores as $store) {
            if (isset($this->request->post['config_rainforest_use_volumetric_weight_' . $store['store_id']])) {
                $this->data['config_rainforest_use_volumetric_weight_' . $store['store_id']] = $this->request->post['config_rainforest_use_volumetric_weight_' . $store['store_id']];
            } else {
                $this->data['config_rainforest_use_volumetric_weight_' . $store['store_id']] = $this->config->get('config_rainforest_use_volumetric_weight_' . $store['store_id']);
            }
        }

        foreach ($stores as $store) {
            if (isset($this->request->post['config_rainforest_volumetric_weight_coefficient_' . $store['store_id']])) {
                $this->data['config_rainforest_volumetric_weight_coefficient_' . $store['store_id']] = $this->request->post['config_rainforest_volumetric_weight_coefficient_' . $store['store_id']];
            } else {
                $this->data['config_rainforest_volumetric_weight_coefficient_' . $store['store_id']] = $this->config->get('config_rainforest_volumetric_weight_coefficient_' . $store['store_id']);
            }
        }

        if (isset($this->request->post['config_priceva_enable_api'])) {
            $this->data['config_priceva_enable_api'] = $this->request->post['config_priceva_enable_api'];
        } else {
            $this->data['config_priceva_enable_api'] = $this->config->get('config_priceva_enable_api');
        }

        if (isset($this->request->post['config_pricecontrol_enable_api'])) {
            $this->data['config_pricecontrol_enable_api'] = $this->request->post['config_pricecontrol_enable_api'];
        } else {
            $this->data['config_pricecontrol_enable_api'] = $this->config->get('config_pricecontrol_enable_api');
        }

        if (isset($this->request->post['config_priceva_directory_name'])) {
            $this->data['config_priceva_directory_name'] = $this->request->post['config_priceva_directory_name'];
        } else {
            $this->data['config_priceva_directory_name'] = $this->config->get('config_priceva_directory_name');
        }

        if (isset($this->request->post['config_priceva_only_available'])) {
            $this->data['config_priceva_only_available'] = $this->request->post['config_priceva_only_available'];
        } else {
            $this->data['config_priceva_only_available'] = $this->config->get('config_priceva_only_available');
        }

        if (isset($this->request->post['config_priceva_competitor_field_mapping'])) {
            $this->data['config_priceva_competitor_field_mapping'] = $this->request->post['config_priceva_competitor_field_mapping'];
        } else {
            $this->data['config_priceva_competitor_field_mapping'] = $this->config->get('config_priceva_competitor_field_mapping');
        }
        
        foreach ($stores as $store) {
            if (isset($this->request->post['config_priceva_api_key_' . $store['store_id']])) {
                $this->data['config_priceva_api_key_' . $store['store_id']] = $this->request->post['config_priceva_api_key_' . $store['store_id']];
            } else {
                $this->data['config_priceva_api_key_' . $store['store_id']] = $this->config->get('config_priceva_api_key_' . $store['store_id']);
            }
        }

        $rnf_cron_settings = [
        'config_rainforest_enable_new_parser',
        'config_rainforest_new_parser_time_start',
        'config_rainforest_new_parser_time_end',

        'config_rainforest_enable_data_parser',
        'config_rainforest_data_parser_time_start',
        'config_rainforest_data_parser_time_end',

        'config_rainforest_enable_category_queue_parser',
        'config_rainforest_category_queue_parser_time_start',
        'config_rainforest_category_queue_parser_time_end',

        'config_rainforest_enable_tech_category_parser',
        'config_rainforest_tech_category_parser_time_start',
        'config_rainforest_tech_category_parser_time_end',

        'config_rainforest_enable_category_tree_parser',
        
        'config_rainforest_enable_data_l2_parser',
        'config_rainforest_data_l2_parser_time_start',
        'config_rainforest_data_l2_parser_time_end',

        'config_rainforest_enable_offers_parser',
        'config_rainforest_offers_parser_time_start',
        'config_rainforest_offers_parser_time_end',

        'config_rainforest_enable_add_queue_parser',
        'config_rainforest_add_queue_parser_time_start',
        'config_rainforest_add_queue_parser_time_end',
        'config_rainforest_delay_queue_offers',
        'config_rainforest_delay_queue_variants',

        'config_rainforest_disable_add_all_button',
        'config_rainforest_do_not_add_without_category',

        'config_rainforest_enable_add_variants_queue_parser',
        'config_rainforest_add_variants_queue_parser_time_start',
        'config_rainforest_add_variants_queue_parser_time_end',

        'config_rainforest_enable_recoverasins_parser',
        'config_rainforest_recoverasins_parser_time_start',
        'config_rainforest_recoverasins_parser_time_end',

        'config_rainforest_enable_offersqueue_parser',
        'config_rainforest_offersqueue_parser_time_start',
        'config_rainforest_offersqueue_parser_time_end',

        'config_rainforest_enable_reprice_cron',
        'config_rainforest_reprice_cron_time_start',
        'config_rainforest_reprice_cron_time_end',
        
        'config_rainforest_enable_asins_parser',
        'config_rainforest_enable_eans_parser',        
        'config_enable_seogen_cron'
        ];


        foreach ($rnf_cron_settings as $rnf_cron_setting) {
            if (isset($this->request->post[$rnf_cron_setting])) {
                $this->data[$rnf_cron_setting] = $this->request->post[$rnf_cron_setting];
            } else {
                $this->data[$rnf_cron_setting] = $this->config->get($rnf_cron_setting);
            }
        }
        
        if ($this->config->get('config_rainforest_enable_api')){
            $this->data['amazon_domains'] = $this->rainforestAmazon->getValidAmazonSitesArray();
        } else {
            $this->data['amazon_domains'] = [];           
        }

        $this->data['amazon_filters'] = \CaponicaAmazonRainforest\Request\OfferRequest::getStaticFilterKeys();
        
        if (isset($this->request->post['config_rainforest_amazon_filters_1'])) {
            $this->data['config_rainforest_amazon_filters_1'] = $this->request->post['config_rainforest_amazon_filters_1'];
        } else {
            $this->data['config_rainforest_amazon_filters_1'] = $this->config->get('config_rainforest_amazon_filters_1');
        }
        
        if (isset($this->request->post['config_rainforest_api_zipcode_1'])) {
            $this->data['config_rainforest_api_zipcode_1'] = $this->request->post['config_rainforest_api_zipcode_1'];
        } else {
            $this->data['config_rainforest_api_zipcode_1'] = $this->config->get('config_rainforest_api_zipcode_1');
        }

        if (isset($this->request->post['config_rainforest_api_zipcode_2'])) {
            $this->data['config_rainforest_api_zipcode_2'] = $this->request->post['config_rainforest_api_zipcode_2'];
        } else {
            $this->data['config_rainforest_api_zipcode_2'] = $this->config->get('config_rainforest_api_zipcode_2');
        }

        if (isset($this->request->post['config_rainforest_api_zipcode_3'])) {
            $this->data['config_rainforest_api_zipcode_3'] = $this->request->post['config_rainforest_api_zipcode_3'];
        } else {
            $this->data['config_rainforest_api_zipcode_3'] = $this->config->get('config_rainforest_api_zipcode_3');
        }

        if (isset($this->request->post['config_rainforest_api_zipcode_4'])) {
            $this->data['config_rainforest_api_zipcode_4'] = $this->request->post['config_rainforest_api_zipcode_4'];
        } else {
            $this->data['config_rainforest_api_zipcode_4'] = $this->config->get('config_rainforest_api_zipcode_4');
        }

        if (isset($this->request->post['config_rainforest_api_zipcode_5'])) {
            $this->data['config_rainforest_api_zipcode_5'] = $this->request->post['config_rainforest_api_zipcode_5'];
        } else {
            $this->data['config_rainforest_api_zipcode_5'] = $this->config->get('config_rainforest_api_zipcode_5');
        }

        if (isset($this->request->post['config_helpcrunch_enable'])) {
            $this->data['config_helpcrunch_enable'] = $this->request->post['config_helpcrunch_enable'];
        } else {
            $this->data['config_helpcrunch_enable'] = $this->config->get('config_helpcrunch_enable');
        }

        if (isset($this->request->post['config_helpcrunch_app_id'])) {
            $this->data['config_helpcrunch_app_id'] = $this->request->post['config_helpcrunch_app_id'];
        } else {
            $this->data['config_helpcrunch_app_id'] = $this->config->get('config_helpcrunch_app_id');
        }

        if (isset($this->request->post['config_helpcrunch_app_organisation'])) {
            $this->data['config_helpcrunch_app_organisation'] = $this->request->post['config_helpcrunch_app_organisation'];
        } else {
            $this->data['config_helpcrunch_app_organisation'] = $this->config->get('config_helpcrunch_app_organisation');
        }

        if (isset($this->request->post['config_helpcrunch_send_auth_data'])) {
            $this->data['config_helpcrunch_send_auth_data'] = $this->request->post['config_helpcrunch_send_auth_data'];
        } else {
            $this->data['config_helpcrunch_send_auth_data'] = $this->config->get('config_helpcrunch_send_auth_data');
        }

        $this->data['translategates'] = $this->translateAdaptor->getTranslateLibraries();

        if (isset($this->request->post['config_translate_api_enable'])) {
            $this->data['config_translate_api_enable'] = $this->request->post['config_translate_api_enable'];
        } else {
            $this->data['config_translate_api_enable'] = $this->config->get('config_translate_api_enable');
        }       

        if (isset($this->request->post['config_translation_library'])) {
            $this->data['config_translation_library'] = $this->request->post['config_translation_library'];
        } else {
            $this->data['config_translation_library'] = $this->config->get('config_translation_library');
        }  

        //Yandex Translate
        if (isset($this->request->post['config_yandex_translate_api_enable'])) {
            $this->data['config_yandex_translate_api_enable'] = $this->request->post['config_yandex_translate_api_enable'];
        } else {
            $this->data['config_yandex_translate_api_enable'] = $this->config->get('config_yandex_translate_api_enable');
        }

        if (isset($this->request->post['config_yandex_translate_api_key'])) {
            $this->data['config_yandex_translate_api_key'] = $this->request->post['config_yandex_translate_api_key'];
        } else {
            $this->data['config_yandex_translate_api_key'] = $this->config->get('config_yandex_translate_api_key');
        }

        if (isset($this->request->post['config_yandex_translate_api_id'])) {
            $this->data['config_yandex_translate_api_id'] = $this->request->post['config_yandex_translate_api_id'];
        } else {
            $this->data['config_yandex_translate_api_id'] = $this->config->get('config_yandex_translate_api_id');
        }

         //DL Translate
        if (isset($this->request->post['config_deepl_translate_api_enable'])) {
            $this->data['config_deepl_translate_api_enable'] = $this->request->post['config_deepl_translate_api_enable'];
        } else {
            $this->data['config_deepl_translate_api_enable'] = $this->config->get('config_deepl_translate_api_enable');
        }

        if (isset($this->request->post['config_deepl_translate_api_key'])) {
            $this->data['config_deepl_translate_api_key'] = $this->request->post['config_deepl_translate_api_key'];
        } else {
            $this->data['config_deepl_translate_api_key'] = $this->config->get('config_deepl_translate_api_key');
        }

        //MS Translate
        if (isset($this->request->post['config_azure_translate_api_enable'])) {
            $this->data['config_azure_translate_api_enable'] = $this->request->post['config_azure_translate_api_enable'];
        } else {
            $this->data['config_azure_translate_api_enable'] = $this->config->get('config_azure_translate_api_enable');
        }

        if (isset($this->request->post['config_azure_translate_api_key'])) {
            $this->data['config_azure_translate_api_key'] = $this->request->post['config_azure_translate_api_key'];
        } else {
            $this->data['config_azure_translate_api_key'] = $this->config->get('config_azure_translate_api_key');
        }

        if (isset($this->request->post['config_azure_translate_api_region'])) {
            $this->data['config_azure_translate_api_region'] = $this->request->post['config_azure_translate_api_region'];
        } else {
            $this->data['config_azure_translate_api_region'] = $this->config->get('config_azure_translate_api_region');
        }

            //LDAP AUTH
        if (isset($this->request->post['config_ldap_auth_enable'])) {
            $this->data['config_ldap_auth_enable'] = $this->request->post['config_ldap_auth_enable'];
        } else {
            $this->data['config_ldap_auth_enable'] = $this->config->get('config_ldap_auth_enable');
        }

        if (isset($this->request->post['config_ldap_dn'])) {
            $this->data['config_ldap_dn'] = $this->request->post['config_ldap_dn'];
        } else {
            $this->data['config_ldap_dn'] = $this->config->get('config_ldap_dn');
        }

        if (isset($this->request->post['config_ldap_host'])) {
            $this->data['config_ldap_host'] = $this->request->post['config_ldap_host'];
        } else {
            $this->data['config_ldap_host'] = $this->config->get('config_ldap_host');
        }

        if (isset($this->request->post['config_ldap_domain'])) {
            $this->data['config_ldap_domain'] = $this->request->post['config_ldap_domain'];
        } else {
            $this->data['config_ldap_domain'] = $this->config->get('config_ldap_domain');
        }

        if (isset($this->request->post['config_ldap_group'])) {
            $this->data['config_ldap_domain'] = $this->request->post['config_ldap_group'];
        } else {
            $this->data['config_ldap_group'] = $this->config->get('config_ldap_group');
        }


            //GOIP4
        if (isset($this->request->post['config_goip4_user'])) {
            $this->data['config_goip4_user'] = $this->request->post['config_goip4_user'];
        } else {
            $this->data['config_goip4_user'] = $this->config->get('config_goip4_user');
        }

        if (isset($this->request->post['config_goip4_passwd'])) {
            $this->data['config_goip4_passwd'] = $this->request->post['config_goip4_passwd'];
        } else {
            $this->data['config_goip4_passwd'] = $this->config->get('config_goip4_passwd');
        }

        if (isset($this->request->post['config_goip4_uri'])) {
            $this->data['config_goip4_uri'] = $this->request->post['config_goip4_uri'];
        } else {
            $this->data['config_goip4_uri'] = $this->config->get('config_goip4_uri');
        }

        if (isset($this->request->post['config_goip4_simnumber'])) {
            $this->data['config_goip4_simnumber'] = $this->request->post['config_goip4_simnumber'];
        } else {
            $this->data['config_goip4_simnumber'] = $this->config->get('config_goip4_simnumber');
        }

        if (!$this->data['config_goip4_simnumber']) {
            $this->data['config_goip4_simnumber'] = 4;
        }

        $config_goip4_simnumber = $this->data['config_goip4_simnumber'];
        for ($i = 1; $i <= $config_goip4_simnumber; $i++) {
            if (isset($this->request->post['config_goip4_simnumber_' . $i])) {
                $this->data['config_goip4_simnumber_' . $i] = $this->request->post['config_goip4_simnumber_' . $i];
            } else {
                $this->data['config_goip4_simnumber_' . $i] = $this->config->get('config_goip4_simnumber_' . $i);
            }

            if (isset($this->request->post['config_goip4_simnumber_checkfunction_' . $i])) {
                $this->data['config_goip4_simnumber_checkfunction_' . $i] = $this->request->post['config_goip4_simnumber_checkfunction_' . $i];
            } else {
                $this->data['config_goip4_simnumber_checkfunction_' . $i] = $this->config->get('config_goip4_simnumber_checkfunction_' . $i);
            }
        }

        //Binotel
        if (isset($this->request->post['config_telephony_engine'])) {
            $this->data['config_telephony_engine'] = $this->request->post['config_telephony_engine'];
        } else {
            $this->data['config_telephony_engine'] = $this->config->get('config_telephony_engine');
        }

        if (isset($this->request->post['config_binotel_api_key'])) {
            $this->data['config_binotel_api_key'] = $this->request->post['config_binotel_api_key'];
        } else {
            $this->data['config_binotel_api_key'] = $this->config->get('config_binotel_api_key');
        }

        if (isset($this->request->post['config_binotel_api_secret'])) {
            $this->data['config_binotel_api_secret'] = $this->request->post['config_binotel_api_secret'];
        } else {
            $this->data['config_binotel_api_secret'] = $this->config->get('config_binotel_api_secret');
        }

        //ASTERISK AMI
        if (isset($this->request->post['config_asterisk_ami_user'])) {
            $this->data['config_asterisk_ami_user'] = $this->request->post['config_asterisk_ami_user'];
        } else {
            $this->data['config_asterisk_ami_user'] = $this->config->get('config_asterisk_ami_user');
        }

        if (isset($this->request->post['config_asterisk_ami_pass'])) {
            $this->data['config_asterisk_ami_pass'] = $this->request->post['config_asterisk_ami_pass'];
        } else {
            $this->data['config_asterisk_ami_pass'] = $this->config->get('config_asterisk_ami_pass');
        }

        if (isset($this->request->post['config_asterisk_ami_host'])) {
            $this->data['config_asterisk_ami_host'] = $this->request->post['config_asterisk_ami_host'];
        } else {
            $this->data['config_asterisk_ami_host'] = $this->config->get('config_asterisk_ami_host');
        }

        if (isset($this->request->post['config_asterisk_ami_worktime'])) {
            $this->data['config_asterisk_ami_worktime'] = $this->request->post['config_asterisk_ami_worktime'];
        } else {
            $this->data['config_asterisk_ami_worktime'] = $this->config->get('config_asterisk_ami_worktime');
        }

            //REACHER
        if (isset($this->request->post['config_reacher_enable'])) {
            $this->data['config_reacher_enable'] = $this->request->post['config_reacher_enable'];
        } else {
            $this->data['config_reacher_enable'] = $this->config->get('config_reacher_enable');
        }

        if (isset($this->request->post['config_reacher_uri'])) {
            $this->data['config_reacher_uri'] = $this->request->post['config_reacher_uri'];
        } else {
            $this->data['config_reacher_uri'] = $this->config->get('config_reacher_uri');
        }

        if (isset($this->request->post['config_reacher_auth'])) {
            $this->data['config_reacher_auth'] = $this->request->post['config_reacher_auth'];
        } else {
            $this->data['config_reacher_auth'] = $this->config->get('config_reacher_auth');
        }

        if (isset($this->request->post['config_reacher_key'])) {
            $this->data['config_reacher_key'] = $this->request->post['config_reacher_key'];
        } else {
            $this->data['config_reacher_key'] = $this->config->get('config_reacher_key');
        }

        if (isset($this->request->post['config_reacher_from'])) {
            $this->data['config_reacher_from'] = $this->request->post['config_reacher_from'];
        } else {
            $this->data['config_reacher_from'] = $this->config->get('config_reacher_from');
        }

        if (isset($this->request->post['config_reacher_helo'])) {
            $this->data['config_reacher_helo'] = $this->request->post['config_reacher_helo'];
        } else {
            $this->data['config_reacher_helo'] = $this->config->get('config_reacher_helo');
        }
        
        
            //ELASTICSEARCH
        if (isset($this->request->post['config_elasticsearch_fuzziness_product'])) {
            $this->data['config_elasticsearch_fuzziness_product'] = $this->request->post['config_elasticsearch_fuzziness_product'];
        } else {
            $this->data['config_elasticsearch_fuzziness_product'] = $this->config->get('config_elasticsearch_fuzziness_product');
        }
        
        if (isset($this->request->post['config_elasticsearch_fuzziness_category'])) {
            $this->data['config_elasticsearch_fuzziness_category'] = $this->request->post['config_elasticsearch_fuzziness_category'];
        } else {
            $this->data['config_elasticsearch_fuzziness_category'] = $this->config->get('config_elasticsearch_fuzziness_category');
        }
        
        if (isset($this->request->post['config_elasticsearch_fuzziness_autcocomplete'])) {
            $this->data['config_elasticsearch_fuzziness_autcocomplete'] = $this->request->post['config_elasticsearch_fuzziness_autcocomplete'];
        } else {
            $this->data['config_elasticsearch_fuzziness_autcocomplete'] = $this->config->get('config_elasticsearch_fuzziness_autcocomplete');
        }

        if (isset($this->request->post['config_elasticsearch_index_suffix'])) {
            $this->data['config_elasticsearch_index_suffix'] = $this->request->post['config_elasticsearch_index_suffix'];
        } else {
            $this->data['config_elasticsearch_index_suffix'] = $this->config->get('config_elasticsearch_index_suffix');
        }

        if (isset($this->request->post['config_elasticsearch_use_local_stock'])) {
            $this->data['config_elasticsearch_use_local_stock'] = $this->request->post['config_elasticsearch_use_local_stock'];
        } else {
            $this->data['config_elasticsearch_use_local_stock'] = $this->config->get('config_elasticsearch_use_local_stock');
        }

        if (isset($this->request->post['config_hotline_feed_enable'])) {
            $this->data['config_hotline_feed_enable'] = $this->request->post['config_hotline_feed_enable'];
        } else {
            $this->data['config_hotline_feed_enable'] = $this->config->get('config_hotline_feed_enable');
        }

        if (isset($this->request->post['config_hotline_merchant_id'])) {
            $this->data['config_hotline_merchant_id'] = $this->request->post['config_hotline_merchant_id'];
        } else {
            $this->data['config_hotline_merchant_id'] = $this->config->get('config_hotline_merchant_id');
        }

        if (isset($this->request->post['config_hotline_feed_limit'])) {
            $this->data['config_hotline_feed_limit'] = $this->request->post['config_hotline_feed_limit'];
        } else {
            $this->data['config_hotline_feed_limit'] = $this->config->get('config_hotline_feed_limit');
        }

        if (isset($this->request->post['config_hotline_one_iteration_limit'])) {
            $this->data['config_hotline_one_iteration_limit'] = $this->request->post['config_hotline_one_iteration_limit'];
        } else {
            $this->data['config_hotline_one_iteration_limit'] = $this->config->get('config_hotline_one_iteration_limit');
        }

        if (isset($this->request->post['config_hotline_enable_category_tree'])) {
            $this->data['config_hotline_enable_category_tree'] = $this->request->post['config_hotline_enable_category_tree'];
        } else {
            $this->data['config_hotline_enable_category_tree'] = $this->config->get('config_hotline_enable_category_tree');
        }

        //OZON
        if (isset($this->request->post['config_ozon_enable_price_yam'])) {
            $this->data['config_ozon_enable_price_yam'] = $this->request->post['config_ozon_enable_price_yam'];
        } else {
            $this->data['config_ozon_enable_price_yam'] = $this->config->get('config_ozon_enable_price_yam');
        }

        if (isset($this->request->post['config_ozon_warehouse_0'])) {
            $this->data['config_ozon_warehouse_0'] = $this->request->post['config_ozon_warehouse_0'];
        } else {
            $this->data['config_ozon_warehouse_0'] = $this->config->get('config_ozon_warehouse_0');
        }

        $this->load->model('catalog/manufacturer');
        $this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

        if (isset($this->request->post['config_ozon_exclude_manufacturers'])) {
            $this->data['config_ozon_exclude_manufacturers'] = $this->request->post['config_ozon_exclude_manufacturers'];
        } elseif ($this->config->get('config_ozon_exclude_manufacturers')) {
            $this->data['config_ozon_exclude_manufacturers'] = $this->config->get('config_ozon_exclude_manufacturers');
        } else {
            $this->data['config_ozon_exclude_manufacturers'] = [];
        }

        if (isset($this->request->post['config_yandex_exclude_manufacturers'])) {
            $this->data['config_yandex_exclude_manufacturers'] = $this->request->post['config_yandex_exclude_manufacturers'];
        } elseif ($this->config->get('config_yandex_exclude_manufacturers')) {
            $this->data['config_yandex_exclude_manufacturers'] = $this->config->get('config_yandex_exclude_manufacturers');
        } else {
            $this->data['config_yandex_exclude_manufacturers'] = [];
        }

        $this->data['deprecated_yam_module'] = $this->url->link('feed/yandex_yml', 'token=' . $this->session->data['token']);
        $this->data['deprecated_hotline_module'] = $this->url->link('feed/hotline_yml', 'token=' . $this->session->data['token']);
        
        if (isset($this->request->post['config_yam_enable_category_tree'])) {
            $this->data['config_yam_enable_category_tree'] = $this->request->post['config_yam_enable_category_tree'];
        } else {
            $this->data['config_yam_enable_category_tree'] = $this->config->get('config_yam_enable_category_tree');
        }
        
        if (isset($this->request->post['config_yam_enable_sync_from_1c'])) {
            $this->data['config_yam_enable_sync_from_1c'] = $this->request->post['config_yam_enable_sync_from_1c'];
        } else {
            $this->data['config_yam_enable_sync_from_1c'] = $this->config->get('config_yam_enable_sync_from_1c');
        }
        
        if (isset($this->request->post['config_yam_default_comission'])) {
            $this->data['config_yam_default_comission'] = $this->request->post['config_yam_default_comission'];
        } else {
            $this->data['config_yam_default_comission'] = $this->config->get('config_yam_default_comission');
        }

        if (isset($this->request->post['config_yam_fuck_specials'])) {
            $this->data['config_yam_fuck_specials'] = $this->request->post['config_yam_fuck_specials'];
        } else {
            $this->data['config_yam_fuck_specials'] = $this->config->get('config_yam_fuck_specials');
        }
        
        if (isset($this->request->post['config_yam_enable_plus_percent'])) {
            $this->data['config_yam_enable_plus_percent'] = $this->request->post['config_yam_enable_plus_percent'];
        } else {
            $this->data['config_yam_enable_plus_percent'] = $this->config->get('config_yam_enable_plus_percent');
        }
        
        if (isset($this->request->post['config_yam_plus_percent'])) {
            $this->data['config_yam_plus_percent'] = $this->request->post['config_yam_plus_percent'];
        } else {
            $this->data['config_yam_plus_percent'] = $this->config->get('config_yam_plus_percent');
        }
        
        if (isset($this->request->post['config_yam_enable_plus_for_main_price'])) {
            $this->data['config_yam_enable_plus_for_main_price'] = $this->request->post['config_yam_enable_plus_for_main_price'];
        } else {
            $this->data['config_yam_enable_plus_for_main_price'] = $this->config->get('config_yam_enable_plus_for_main_price');
        }
        
        if (isset($this->request->post['config_yam_plus_for_main_price'])) {
            $this->data['config_yam_plus_for_main_price'] = $this->request->post['config_yam_plus_for_main_price'];
        } else {
            $this->data['config_yam_plus_for_main_price'] = $this->config->get('config_yam_plus_for_main_price');
        }    

        if (isset($this->request->post['config_yam_tg_alert_group_id'])) {
            $this->data['config_yam_tg_alert_group_id'] = $this->request->post['config_yam_tg_alert_group_id'];
        } else {
            $this->data['config_yam_tg_alert_group_id'] = $this->config->get('config_yam_tg_alert_group_id');
        }    
        
        if (isset($this->request->post['config_yam_fbs_campaign_id'])) {
            $this->data['config_yam_fbs_campaign_id'] = $this->request->post['config_yam_fbs_campaign_id'];
        } else {
            $this->data['config_yam_fbs_campaign_id'] = $this->config->get('config_yam_fbs_campaign_id');
        }
        
        if (isset($this->request->post['config_yam_fbs_warehouse_id'])) {
            $this->data['config_yam_fbs_warehouse_id'] = $this->request->post['config_yam_fbs_warehouse_id'];
        } else {
            $this->data['config_yam_fbs_warehouse_id'] = $this->config->get('config_yam_fbs_warehouse_id');
        }

        if (isset($this->request->post['config_yam_fbs_business_id'])) {
            $this->data['config_yam_fbs_business_id'] = $this->request->post['config_yam_fbs_business_id'];
        } else {
            $this->data['config_yam_fbs_business_id'] = $this->config->get('config_yam_fbs_business_id');
        }

         if (isset($this->request->post['config_yam_express_campaign_id'])) {
            $this->data['config_yam_express_campaign_id'] = $this->request->post['config_yam_express_campaign_id'];
        } else {
            $this->data['config_yam_express_campaign_id'] = $this->config->get('config_yam_express_campaign_id');
        }
        
        if (isset($this->request->post['config_yam_express_warehouse_id'])) {
            $this->data['config_yam_express_warehouse_id'] = $this->request->post['config_yam_express_warehouse_id'];
        } else {
            $this->data['config_yam_express_warehouse_id'] = $this->config->get('config_yam_express_warehouse_id');
        }

        if (isset($this->request->post['config_yam_express_business_id'])) {
            $this->data['config_yam_express_business_id'] = $this->request->post['config_yam_express_business_id'];
        } else {
            $this->data['config_yam_express_business_id'] = $this->config->get('config_yam_express_business_id');
        }
        
        if (isset($this->request->post['config_yam_stock_field'])) {
            $this->data['config_yam_stock_field'] = $this->request->post['config_yam_stock_field'];
        } else {
            $this->data['config_yam_stock_field'] = $this->config->get('config_yam_stock_field');
        }
        
        if (isset($this->request->post['config_yam_offer_id_prefix'])) {
            $this->data['config_yam_offer_id_prefix'] = $this->request->post['config_yam_offer_id_prefix'];
        } else {
            $this->data['config_yam_offer_id_prefix'] = $this->config->get('config_yam_offer_id_prefix');
        }
        
        if (isset($this->request->post['config_yam_offer_id_price_enable'])) {
            $this->data['config_yam_offer_id_price_enable'] = $this->request->post['config_yam_offer_id_price_enable'];
        } else {
            $this->data['config_yam_offer_id_price_enable'] = $this->config->get('config_yam_offer_id_price_enable');
        }
        
        if (isset($this->request->post['config_yam_offer_id_prefix_enable'])) {
            $this->data['config_yam_offer_id_prefix_enable'] = $this->request->post['config_yam_offer_id_prefix_enable'];
        } else {
            $this->data['config_yam_offer_id_prefix_enable'] = $this->config->get('config_yam_offer_id_prefix_enable');
        }
        
        if (isset($this->request->post['config_yam_offer_id_link_disable'])) {
            $this->data['config_yam_offer_id_link_disable'] = $this->request->post['config_yam_offer_id_link_disable'];
        } else {
            $this->data['config_yam_offer_id_link_disable'] = $this->config->get('config_yam_offer_id_link_disable');
        }
        
        if (isset($this->request->post['config_yam_offer_feed_template'])) {
            $this->data['config_yam_offer_feed_template'] = $this->request->post['config_yam_offer_feed_template'];
        } else {
            $this->data['config_yam_offer_feed_template'] = $this->config->get('config_yam_offer_feed_template');
        }
        
        if (isset($this->request->post['config_yam_excludewords'])) {
            $this->data['config_yam_excludewords'] = $this->request->post['config_yam_excludewords'];
        } else {
            $this->data['config_yam_excludewords'] = $this->config->get('config_yam_excludewords');
        }

        if (isset($this->request->post['config_yam_default_category_id'])) {
            $this->data['config_yam_default_category_id'] = $this->request->post['config_yam_default_category_id'];
        } else {
            $this->data['config_yam_default_category_id'] = $this->config->get('config_yam_default_category_id');
        }

        if (isset($this->request->post['config_yam_add_feed_for_category_id_0'])) {
            $this->data['config_yam_add_feed_for_category_id_0'] = $this->request->post['config_yam_add_feed_for_category_id_0'];
        } else {
            $this->data['config_yam_add_feed_for_category_id_0'] = $this->config->get('config_yam_add_feed_for_category_id_0');
        }

        if (isset($this->request->post['config_yam_add_feed_for_category_id_1'])) {
            $this->data['config_yam_add_feed_for_category_id_1'] = $this->request->post['config_yam_add_feed_for_category_id_1'];
        } else {
            $this->data['config_yam_add_feed_for_category_id_1'] = $this->config->get('config_yam_add_feed_for_category_id_1');
        }

        if (isset($this->request->post['config_yam_add_feed_for_category_id_2'])) {
            $this->data['config_yam_add_feed_for_category_id_2'] = $this->request->post['config_yam_add_feed_for_category_id_2'];
        } else {
            $this->data['config_yam_add_feed_for_category_id_2'] = $this->config->get('config_yam_add_feed_for_category_id_2');
        }
        
        if (isset($this->request->post['config_yam_fbs_yaMarketToken'])) {
            $this->data['config_yam_fbs_yaMarketToken'] = $this->request->post['config_yam_fbs_yaMarketToken'];
        } else {
            $this->data['config_yam_fbs_yaMarketToken'] = $this->config->get('config_yam_fbs_yaMarketToken');
        }

         if (isset($this->request->post['config_yam_express_yaMarketToken'])) {
            $this->data['config_yam_express_yaMarketToken'] = $this->request->post['config_yam_express_yaMarketToken'];
        } else {
            $this->data['config_yam_express_yaMarketToken'] = $this->config->get('config_yam_express_yaMarketToken');
        }
        
        if (isset($this->request->post['config_yam_yandexOauthID'])) {
            $this->data['config_yam_yandexOauthID'] = $this->request->post['config_yam_yandexOauthID'];
        } else {
            $this->data['config_yam_yandexOauthID'] = $this->config->get('config_yam_yandexOauthID');
        }
        
        if (isset($this->request->post['config_yam_yandexOauthSecret'])) {
            $this->data['config_yam_yandexOauthSecret'] = $this->request->post['config_yam_yandexOauthSecret'];
        } else {
            $this->data['config_yam_yandexOauthSecret'] = $this->config->get('config_yam_yandexOauthSecret');
        }
        
        if (isset($this->request->post['config_yam_yandexAccessToken'])) {
            $this->data['config_yam_yandexAccessToken'] = $this->request->post['config_yam_yandexAccessToken'];
        } else {
            $this->data['config_yam_yandexAccessToken'] = $this->config->get('config_yam_yandexAccessToken');
        }
        
        
        if (isset($this->request->post['config_google_recaptcha_contact_enable'])) {
            $this->data['config_google_recaptcha_contact_enable'] = $this->request->post['config_google_recaptcha_contact_enable'];
        } else {
            $this->data['config_google_recaptcha_contact_enable'] = $this->config->get('config_google_recaptcha_contact_enable');
        }
        
        if (isset($this->request->post['config_google_recaptcha_contact_key'])) {
            $this->data['config_google_recaptcha_contact_key'] = $this->request->post['config_google_recaptcha_contact_key'];
        } else {
            $this->data['config_google_recaptcha_contact_key'] = $this->config->get('config_google_recaptcha_contact_key');
        }
        
        if (isset($this->request->post['config_google_recaptcha_contact_secret'])) {
            $this->data['config_google_recaptcha_contact_secret'] = $this->request->post['config_google_recaptcha_contact_secret'];
        } else {
            $this->data['config_google_recaptcha_contact_secret'] = $this->config->get('config_google_recaptcha_contact_secret');
        }
        
        if (isset($this->request->post['config_webvisor_enable'])) {
            $this->data['config_webvisor_enable'] = $this->request->post['config_webvisor_enable'];
        } else {
            $this->data['config_webvisor_enable'] = $this->config->get('config_webvisor_enable');
        }
        
        if (isset($this->request->post['config_webvisor_enable'])) {
            $this->data['config_webvisor_enable'] = $this->request->post['config_webvisor_enable'];
        } else {
            $this->data['config_webvisor_enable'] = $this->config->get('config_webvisor_enable');
        }
        
        if (isset($this->request->post['config_clickmap_enable'])) {
            $this->data['config_clickmap_enable'] = $this->request->post['config_clickmap_enable'];
        } else {
            $this->data['config_clickmap_enable'] = $this->config->get('config_clickmap_enable');
        }

        $kpi_settings = [
            'config_kpi_complete_cancel_percent_params_0',
            'config_kpi_complete_cancel_percent_params_1',
            'config_kpi_complete_cancel_percent_params_2',

            'config_kpi_average_confirm_time_params_0',
            'config_kpi_average_confirm_time_params_1',
            'config_kpi_average_confirm_time_params_2',

            'config_kpi_average_process_time_params_0',
            'config_kpi_average_process_time_params_1',
            'config_kpi_average_process_time_params_2',

            'config_kpi_percentage_params_0',
            'config_kpi_percentage_params_1',
            'config_kpi_percentage_params_2',

            'config_kpi_fixed_salary',

            'config_kpi_head_percentage_params_0',
            'config_kpi_head_percentage_params_1',
            'config_kpi_head_percentage_params_2',

            'config_kpi_head_fixed_salary',

            'config_kpi_default_filter_count_days',
            'config_kpi_default_filter_count_days_problem',
        ];

        foreach ($kpi_settings as $salary_setting) {
            if (isset($this->request->post[$salary_setting])) {
                $this->data[$salary_setting] = $this->request->post[$salary_setting];
            } else {
                $this->data[$salary_setting] = $this->config->get($salary_setting);
            }
        }
        
        $this->data['token'] = $this->session->data['token'];
        
        $this->template = 'setting/setting.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        
        $this->response->setOutput($this->render());
    }
    
    protected function validate(){
        if (!$this->user->hasPermission('modify', 'setting/setting')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        if (!$this->request->post['config_name']) {
            $this->error['name'] = $this->language->get('error_name');
        }
        
        if ((utf8_strlen($this->request->post['config_owner']) < 3) || (utf8_strlen($this->request->post['config_owner']) > 64)) {
            $this->error['owner'] = $this->language->get('error_owner');
        }
        
        if ((utf8_strlen($this->request->post['config_address']) < 3) || (utf8_strlen($this->request->post['config_address']) > 256)) {
            $this->error['address'] = $this->language->get('error_address');
        }
        
        if ((utf8_strlen($this->request->post['config_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['config_email'])) {
            $this->error['email'] = $this->language->get('error_email');
        }
        
        if ((utf8_strlen($this->request->post['config_telephone']) < 3) || (utf8_strlen($this->request->post['config_telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }
        
        if (!$this->request->post['config_title']) {
            $this->error['title'] = $this->language->get('error_title');
        }
        
        if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
            $this->error['customer_group_display'] = $this->language->get('error_customer_group_display');
        }
        
        if (!$this->request->post['config_voucher_min']) {
            $this->error['voucher_min'] = $this->language->get('error_voucher_min');
        }
        
        if (!$this->request->post['config_voucher_max']) {
            $this->error['voucher_max'] = $this->language->get('error_voucher_max');
        }
        
        if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
            $this->error['image_category'] = $this->language->get('error_image_category');
        }
        
        if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
            $this->error['image_thumb'] = $this->language->get('error_image_thumb');
        }
        
        if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
            $this->error['image_popup'] = $this->language->get('error_image_popup');
        }
        
        if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
            $this->error['image_product'] = $this->language->get('error_image_product');
        }
        
        if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
            $this->error['image_additional'] = $this->language->get('error_image_additional');
        }
        
        if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
            $this->error['image_related'] = $this->language->get('error_image_related');
        }
        
        if (!$this->request->post['config_image_compare_width'] || !$this->request->post['config_image_compare_height']) {
            $this->error['image_compare'] = $this->language->get('error_image_compare');
        }
        
        if (!$this->request->post['config_image_wishlist_width'] || !$this->request->post['config_image_wishlist_height']) {
            $this->error['image_wishlist'] = $this->language->get('error_image_wishlist');
        }
        
        if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
            $this->error['image_cart'] = $this->language->get('error_image_cart');
        }
        
        if (!$this->request->post['config_error_filename']) {
            $this->error['error_filename'] = $this->language->get('error_error_filename');
        }
        
        if (!$this->request->post['config_catalog_limit']) {
            $this->error['catalog_limit'] = $this->language->get('error_limit');
        }
        
        if (!$this->request->post['config_admin_limit']) {
            $this->error['admin_limit'] = $this->language->get('error_limit');
        }
        
        if ((utf8_strlen($this->request->post['config_encryption']) < 3) || (utf8_strlen($this->request->post['config_encryption']) > 32)) {
            $this->error['encryption'] = $this->language->get('error_encryption');
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
    
    public function template(){
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $server = HTTPS_CATALOG;
        } else {
            $server = HTTP_CATALOG;
        }
        
        if (file_exists(DIR_IMAGE . 'templates/' . basename($this->request->get['template']) . '.png')) {
            $image = $server . 'image/templates/' . basename($this->request->get['template']) . '.png';
        } else {
            $image = $server . 'image/no_image.jpg';
        }
        
        $this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
    }
    
    public function country(){
        $json = [];
        
        $this->load->model('localisation/country');
        
        $country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
        
        if ($country_info) {
            $this->load->model('localisation/zone');
            
            $json = array(
                'country_id'        => $country_info['country_id'],
                'name'              => $country_info['name'],
                'iso_code_2'        => $country_info['iso_code_2'],
                'iso_code_3'        => $country_info['iso_code_3'],
                'address_format'    => $country_info['address_format'],
                'postcode_required' => $country_info['postcode_required'],
                'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
                'status'            => $country_info['status']
            );
        }
        
        $this->response->setOutput(json_encode($json));
    }
}
