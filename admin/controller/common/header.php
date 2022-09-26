<?php
class ControllerCommonHeader extends Controller
{
        
    protected function index()
    {
        $this->data['title'] = $this->document->getTitle();
        $this->data['config_title'] = $this->config->get('config_title');
            
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $this->data['base'] = HTTPS_SERVER;
        } else {
            $this->data['base'] = HTTP_SERVER;
        }
            
            
            $this->document->addStyle('view/javascript/sweetalert/sweetalert.css');
            $this->document->addStyle('view/javascript/noty/animate.css');
            $this->document->addStyle('view/javascript/noty/buttons.css');
            $this->document->addStyle('view/stylesheet/pbo.css');
            $this->document->addStyle('view/javascript/tootipster/css/tooltipster.bundle.min.css');
            $this->document->addStyle('view/javascript/tootipster/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-punk.min.css');
            $this->document->addStyle('view/javascript/tootipster/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-shadow.min.css');
            $this->document->addStyle('view/javascript/rateyo/jquery.rateyo.min.css');
            
            $this->document->addScript('view/javascript/jquery/tabs.js');
            $this->document->addScript('view/javascript/jquery/superfish/js/superfish.js');
            $this->document->addScript('view/javascript/sweetalert/sweetalert.min.js');
            $this->document->addScript('view/javascript/noty/jquery.noty.packaged.min.js');
            $this->document->addScript('view/javascript/noty/themes/relax2.js');
            $this->document->addScript('view/javascript/tootipster/js/tooltipster.bundle.min.js');
            $this->document->addScript('view/javascript/rateyo/jquery.rateyo.min.js');
            
            $this->data['description'] = $this->document->getDescription();
            $this->data['keywords'] = $this->document->getKeywords();
            $this->data['links'] = $this->document->getLinks();
            $this->data['styles'] = $this->document->getStyles();
            $this->data['scripts'] = $this->document->getScripts();
            $this->data['lang'] = $this->language->get('code');
            $this->data['direction'] = $this->language->get('direction');
            
            $this->load->language('common/facommon');
            $this->data['text_facategory'] = $this->language->get('fagroups');
            
            $this->language->load('common/header');
            
            $this->data['heading_title'] = $this->language->get('heading_title');
            
            $this->data['text_affiliate'] = $this->language->get('text_affiliate');
            $this->data['text_report_affiliate_statistics'] = $this->language->get('text_report_affiliate_statistics');
            $this->data['text_report_affiliate_statistics_all'] = $this->language->get('text_report_affiliate_statistics_all');
            $this->data['text_attribute'] = $this->language->get('text_attribute');
            $this->data['text_attribute_group'] = $this->language->get('text_attribute_group');
            $this->data['text_backup'] = $this->language->get('text_backup');
            $this->data['text_banner'] = $this->language->get('text_banner');
            $this->data['text_catalog'] = $this->language->get('text_catalog');
            $this->data['text_category'] = $this->language->get('text_category');
            $this->data['text_confirm'] = $this->language->get('text_confirm');
            $this->data['text_country'] = $this->language->get('text_country');
            $this->data['text_coupon'] = $this->language->get('text_coupon');
            $this->data['text_currency'] = $this->language->get('text_currency');
            $this->data['text_customer'] = $this->language->get('text_customer');
            $this->data['text_customer_group'] = $this->language->get('text_customer_group');
            $this->data['text_customer_field'] = $this->language->get('text_customer_field');
            $this->data['text_customer_ban_ip'] = $this->language->get('text_customer_ban_ip');
            $this->data['text_custom_field'] = $this->language->get('text_custom_field');
            $this->data['text_sale'] = $this->language->get('text_sale');
            $this->data['text_design'] = $this->language->get('text_design');
            $this->data['text_documentation'] = $this->language->get('text_documentation');
            $this->data['text_download'] = $this->language->get('text_download');
            $this->data['text_error_log'] = $this->language->get('text_error_log');
            $this->data['text_extension'] = $this->language->get('text_extension');
            $this->data['text_feed'] = $this->language->get('text_feed');
            $this->data['text_filter'] = $this->language->get('text_filter');
            $this->data['text_front'] = $this->language->get('text_front');
            $this->data['text_geo_zone'] = $this->language->get('text_geo_zone');
            $this->data['text_dashboard'] = $this->language->get('text_dashboard');
            $this->data['text_help'] = $this->language->get('text_help');
            $this->data['text_information'] = $this->language->get('text_information');
            $this->data['text_review_category'] = $this->language->get('text_review_category');
            $this->data['text_language'] = $this->language->get('text_language');
            $this->data['text_layout'] = $this->language->get('text_layout');
            $this->data['text_localisation'] = $this->language->get('text_localisation');
            $this->data['text_logout'] = $this->language->get('text_logout');
            $this->data['text_contact'] = $this->language->get('text_contact');
            $this->data['text_manager'] = $this->language->get('text_manager');
            $this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
            $this->data['text_module'] = $this->language->get('text_module');
            $this->data['text_option'] = $this->language->get('text_option');
            $this->data['text_ocfilter'] = $this->language->get('text_ocfilter');
            $this->data['text_order'] = $this->language->get('text_order');
            $this->data['text_order_status'] = $this->language->get('text_order_status');
            $this->data['text_opencart'] = $this->language->get('text_opencart');
            $this->data['text_payment'] = $this->language->get('text_payment');
            $this->data['text_product'] = $this->language->get('text_product');
            $this->data['text_profile'] = $this->language->get('text_profile');
            $this->data['text_reports'] = $this->language->get('text_reports');
            $this->data['text_report_sale_order'] = $this->language->get('text_report_sale_order');
            $this->data['text_report_sale_tax'] = $this->language->get('text_report_sale_tax');
            $this->data['text_report_sale_shipping'] = $this->language->get('text_report_sale_shipping');
            $this->data['text_report_sale_return'] = $this->language->get('text_report_sale_return');
            $this->data['text_report_sale_coupon'] = $this->language->get('text_report_sale_coupon');
            $this->data['text_report_product_viewed'] = $this->language->get('text_report_product_viewed');
            $this->data['text_report_product_purchased'] = $this->language->get('text_report_product_purchased');
            $this->data['text_report_customer_online'] = $this->language->get('text_report_customer_online');
            $this->data['text_report_customer_order'] = $this->language->get('text_report_customer_order');
            $this->data['text_report_customer_reward'] = $this->language->get('text_report_customer_reward');
            $this->data['text_report_customer_credit'] = $this->language->get('text_report_customer_credit');
            $this->data['text_report_affiliate_commission'] = $this->language->get('text_report_affiliate_commission');
            $this->data['text_report_sale_return'] = $this->language->get('text_report_sale_return');
            $this->data['text_report_product_viewed'] = $this->language->get('text_report_product_viewed');
            $this->data['text_report_customer_order'] = $this->language->get('text_report_customer_order');
            $this->data['text_review'] = $this->language->get('text_review');
            $this->data['text_actions'] = $this->language->get('text_actions');
            
            $this->load->language('catalog/shop_rating');
            $this->data['text_shop_rating'] = $this->language->get('text_shop_rating');
            
            $this->data['text_subscribe'] = $this->language->get('text_subscribe');
            $this->data['text_return'] = $this->language->get('text_return');
            $this->data['text_return_action'] = $this->language->get('text_return_action');
            $this->data['text_return_reason'] = $this->language->get('text_return_reason');
            $this->data['text_return_status'] = $this->language->get('text_return_status');
            $this->data['text_support'] = $this->language->get('text_support');
            $this->data['text_shipping'] = $this->language->get('text_shipping');
            $this->data['text_setting'] = $this->language->get('text_setting');
            $this->data['text_stock_status'] = $this->language->get('text_stock_status');
            $this->data['text_system'] = $this->language->get('text_system');
            $this->data['text_tax'] = $this->language->get('text_tax');
            $this->data['text_tax_class'] = $this->language->get('text_tax_class');
            $this->data['text_tax_rate'] = $this->language->get('text_tax_rate');
            $this->data['text_total'] = $this->language->get('text_total');
            $this->data['text_user'] = $this->language->get('text_user');
            $this->data['text_user_group'] = $this->language->get('text_user_group');
            $this->data['text_users'] = $this->language->get('text_users');
            $this->data['text_voucher'] = $this->language->get('text_voucher');
            $this->data['text_voucher_theme'] = $this->language->get('text_voucher_theme');
            $this->data['text_weight_class'] = $this->language->get('text_weight_class');
            $this->data['text_length_class'] = $this->language->get('text_length_class');
            $this->data['text_vqmod_manager'] = $this->language->get('text_vqmod_manager');
            $this->data['text_zone'] = $this->language->get('text_zone');
            
            /* Admin Header Notices 1.0 */
            $this->data['text_new_customer'] = $this->language->get('text_new_customer');
            $this->data['text_pending_customer'] = $this->language->get('text_pending_customer');
            $this->data['text_new_order'] = $this->language->get('text_new_order');
            $this->data['text_pending_order'] = $this->language->get('text_pending_order');
            $this->data['text_pending_review'] = $this->language->get('text_pending_review');
            $this->data['text_pending_affiliate'] = $this->language->get('text_pending_affiliate');
            $this->data['text_notification'] = $this->language->get('text_notification');
            $this->data['text_stockout'] = $this->language->get('text_stockout');
            /* Admin Header Notices 1.0 */
            
            
            
            $this->data['text_openbay_extension'] = $this->language->get('text_openbay_extension');
            $this->data['text_openbay_dashboard'] = $this->language->get('text_openbay_dashboard');
            $this->data['text_openbay_orders'] = $this->language->get('text_openbay_orders');
            $this->data['text_openbay_items'] = $this->language->get('text_openbay_items');
            $this->data['text_openbay_ebay'] = $this->language->get('text_openbay_ebay');
            $this->data['text_openbay_amazon'] = $this->language->get('text_openbay_amazon');
            $this->data['text_openbay_amazonus'] = $this->language->get('text_openbay_amazonus');
            $this->data['text_openbay_settings'] = $this->language->get('text_openbay_settings');
            $this->data['text_openbay_links'] = $this->language->get('text_openbay_links');
            $this->data['text_openbay_report_price'] = $this->language->get('text_openbay_report_price');
            $this->data['text_openbay_order_import'] = $this->language->get('text_openbay_order_import');
            
            $this->data['text_paypal_express'] = $this->language->get('text_paypal_manage');
            $this->data['text_paypal_express_search'] = $this->language->get('text_paypal_search');
            $this->data['text_recurring_profile'] = $this->language->get('text_recurring_profile');
            $this->data['text_addspecials'] = $this->language->get('text_addspecials');
            
            $this->data['text_report_adv_sale_order'] = $this->language->get('text_report_adv_sale_order');
            
            
            
        if (!$this->user->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
            $this->data['logged'] = '';
                
            $this->data['home'] = $this->url->link('common/login', '', 'SSL');
        } else {
            $this->data['logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
            $this->data['pp_express_status'] = $this->config->get('pp_express_status');
                
            $this->data['simple_module'] = $this->url->link('module/simple', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['simple_module_abandoned'] = $this->url->link('module/simple', 'abandoned&token=' . $this->session->data['token'], 'SSL');
                
            $this->data['home'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['panel'] = $this->url->link('common/panel', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['cronmon'] = $this->url->link('common/cronmon', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['affiliate'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['attribute'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['attribute_group'] = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['backup'] = $this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_affiliate_statistics'] = $this->url->link('report/affiliate_statistics', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_affiliate_statistics_all'] = $this->url->link('report/affiliate_statistics_all', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['report_reject'] = $this->url->link('report/sale_reject', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_marketplace'] = $this->url->link('report/marketplace', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['masspcategupd'] = $this->url->link('tool/masspcategupd', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['masspdiscoupd'] = $this->url->link('tool/masspdiscoupd', 'token=' . $this->session->data['token'], 'SSL');
                                
                
            $this->data['banner'] = $this->url->link('design/banner', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['category'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['facategory'] = $this->url->link('catalog/facategory', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['country'] = $this->url->link('localisation/country', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['coupon'] = $this->url->link('sale/coupon', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['courier_face'] = $this->url->link('sale/courier', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['courier_face2'] = $this->url->link('sale/courier2', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['currency'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['customer_fields'] = $this->url->link('sale/customer_field', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['customer_group'] = $this->url->link('sale/customer_group', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['customer_ban_ip'] = $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['custom_field'] = $this->url->link('design/custom_field', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['download'] = $this->url->link('catalog/download', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['error_log'] = $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['feed'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['seo_feeds'] = $this->url->link('setting/feeds', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['filter'] = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['geo_zone'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['information'] = $this->url->link('catalog/information', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['review_category'] = $this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['landingpage'] = $this->url->link('catalog/landingpage', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['actiontemplate'] = $this->url->link('catalog/actiontemplate', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['information_attribute'] = $this->url->link('catalog/information_attribute', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['language'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['legalperson'] = $this->url->link('localisation/legalperson', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['layout'] = $this->url->link('design/layout', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['contact'] = $this->url->link('sale/contact', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['manager'] = $this->url->link('extension/manager', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['manufacturer'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['module'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['option'] = $this->url->link('catalog/option', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['ocfilter'] = $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['ocfilter_page'] = $this->url->link('catalog/ocfilter/page', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['ocfilter_module'] = $this->url->link('module/ocfilter', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['addspecials'] = $this->url->link('catalog/addspecials', 'token='.$this->session->data['token'], 'SSL');
            $this->data['labelmaker'] = $this->url->link('module/labelmaker', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['notify_bar'] = $this->url->link('module/notify_bar', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['actions'] = $this->url->link('catalog/actions', 'token=' . $this->session->data['token'], 'SSL');
                
            $data = array('filter_order_status_id' => '0');
            $this->load->model('sale/order');
            $fucked_order_total = $this->model_sale_order->getTotalOrders($data);
            $this->data['fucked_order_total'] = $fucked_order_total;
            $this->data['fucked_link'] = $this->url->link('sale/order', 'filter_order_status_id=0&token=' . $this->session->data['token'], 'SSL');
                
            $this->data['report_adv_sale_order'] = $this->url->link('report/adv_sale_order', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_adv_product_purchased'] = $this->url->link('report/adv_product_purchased', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['suppliers'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['buyerprices'] = $this->url->link('catalog/buyer_prices', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['amazonorder'] = $this->url->link('sale/amazon', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['waitlist'] = $this->url->link('catalog/waitlist', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['stocks'] = $this->url->link('catalog/stocks', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['yandex'] = $this->url->link('catalog/yandex', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['priceva'] = $this->url->link('catalog/priceva', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['waitlist_ready'] = $this->url->link('catalog/waitlist', 'filter_supplier_has=1&token=' . $this->session->data['token'], 'SSL');
            $this->data['waitlist_pre'] = $this->url->link('catalog/waitlist', 'filter_prewait=1&token=' . $this->session->data['token'], 'SSL');
            $this->data['parties'] = $this->url->link('catalog/parties', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['competitors'] = $this->url->link('sale/competitors', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['order_status'] = $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['payment'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL');
            if ($this->config->get('admin_quick_edit_status') && $this->config->get('aqe_catalog_products_status') && isset($this->session->data['token'])) {
                $this->data['product'] = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'], 'SSL');
            }
            $this->data['product_deletedasin'] = $this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['product_parser'] = $this->url->link('catalog/product_parser', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['profile'] = $this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_sale_order'] = $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_sale_tax'] = $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_sale_shipping'] = $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_sale_return'] = $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_sale_coupon'] = $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_product_viewed'] = $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_buyanalyze'] = $this->url->link('report/buyanalyze', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_product_purchased'] = $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_customer_online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_customer_order'] = $this->url->link('report/customer_order', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_customer_reward'] = $this->url->link('report/customer_reward', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_customer_credit'] = $this->url->link('report/customer_credit', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['report_affiliate_commission'] = $this->url->link('report/affiliate_commission', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['shop_rating'] = $this->url->link('catalog/shop_rating', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['subscribe'] = $this->url->link('catalog/subscribe', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['return'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['return_action'] = $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['return_reason'] = $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['return_status'] = $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['shipping'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['setting'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['rnf'] = $this->url->link('setting/rnf', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['store'] = HTTPS_CATALOG;
            $this->data['stock_status'] = $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['tax_class'] = $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['tax_rate'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['total'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['user'] = $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['user_ticket'] = $this->url->link('user/ticket', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['user_myworktime'] = $this->url->link('user/mywork', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['user_worktime'] = $this->url->link('user/work', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['manager_quality'] = $this->url->link('kp/managerquality', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['user_sip'] = $this->url->link('user/user_sip', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['user_sip_history'] = $this->user->getIPBX()?$this->url->link('user/user_sip/history', 'user_id='.$this->user->getID().'&token=' . $this->session->data['token'], 'SSL'):false;
            $this->data['user_alerts'] = $this->url->link('user/user_alerts', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['user_myworktime'] = $this->url->link('user/mywork', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['user_worktime'] = $this->url->link('user/work', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['salary_manager'] = $this->url->link('kp/salary/countManagers', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['salary_customerservice'] = $this->url->link('kp/salary/countCustomerService', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['user_group'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['voucher'] = $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['voucher_theme'] = $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['weight_class'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['length_class'] = $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['vqmod_manager'] = $this->url->link('module/vqmod_manager', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['zone'] = $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], 'SSL');
            $this->language->load('common/newspanel');
            $this->data['nmod'] = $this->url->link('module/news', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['ncmod'] = $this->url->link('module/ncategory', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['npages'] = $this->url->link('catalog/news', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['ncategory'] = $this->url->link('catalog/ncategory', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['tocomments'] = $this->url->link('catalog/ncomments', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['nauthor'] = $this->url->link('catalog/nauthor', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['text_commod'] = $this->language->get('text_commod');
                
            $this->data['entry_npages'] = $this->language->get('entry_npages');
                
            $this->data['entry_nmod'] = $this->language->get('entry_nmod');
                
            $this->data['entry_ncmod'] = $this->language->get('entry_ncmod');
                
            $this->data['entry_ncategory'] = $this->language->get('entry_ncategory');
                
            $this->data['text_nauthor'] = $this->language->get('text_nauthor');
                
            $this->data['vk_export'] = $this->url->link('extension/vk_export', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['vk_export_albums'] = $this->url->link('extension/vk_export/albums', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['vk_export_setting'] = $this->url->link('module/vk_export', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['vk_export_report'] = $this->url->link('extension/vk_export/report', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['mreport_ttnscan'] = $this->url->link('report/mreports', 'report=ttnscan&token=' . $this->session->data['token'], 'SSL');
            $this->data['mreport_needtocall'] = $this->url->link('report/mreports', 'report=needtocall&token=' . $this->session->data['token'], 'SSL');
            $this->data['mreport_nopaid'] = $this->url->link('report/mreports', 'report=nopaid&token=' . $this->session->data['token'], 'SSL');
            $this->data['mreport_minusscan'] = $this->url->link('report/mreports', 'report=minusscan&token=' . $this->session->data['token'], 'SSL');
            $this->data['mreport_forgottencart'] = $this->url->link('report/mreports', 'report=forgottencart&token=' . $this->session->data['token'], 'SSL');
                
            $this->data['faq_url'] = $this->url->link('module/faq_system', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['adminlog_url'] = $this->url->link('module/adminlog', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['cdek_integrator'] = $this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->language->load('extension/vk_export_header');
            $this->data['text_vk_export'] = $this->language->get('text_vk_export');
            $this->data['text_vk_export_albums'] = $this->language->get('text_vk_export_albums');
            $this->data['text_vk_export_setting'] = $this->language->get('text_vk_export_setting');
            $this->data['text_vk_export_cron_report'] = $this->language->get('text_vk_export_cron_report');
                
            $this->data['collections_link'] = $this->url->link('catalog/collection', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['countrybrands_link'] = $this->url->link('catalog/countrybrand', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['affiliate_mod_link'] = $this->url->link('module/affiliate', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['anyport_link'] = $this->url->link('module/anyport', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['excelport_link'] = $this->url->link('module/excelport', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['rewards_gen'] = $this->url->link('module/reward_points_generator', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['rewards_mod'] = $this->url->link('module/rewardpoints', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['batch_editor_link'] = $this->url->link('module/batch_editor', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['batch_editor_link2'] = $this->url->link('batch_editor/index', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['advanced_banner_link'] = $this->url->link('module/ocaab', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['sms_link'] = $this->url->link('sale/sms', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['segments_link'] = $this->url->link('sale/segments', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['invite_after_order'] = $this->url->link('module/apri', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['custom_template_link'] = $this->url->link('module/custom_template', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['mattimeotheme'] = $this->url->link('module/mattimeotheme', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['autolink_link'] = $this->url->link('catalog/autolinks', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['redirect_manager'] = $this->url->link('module/redirect_manager', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['keyworder_link'] = $this->url->link('module/keyworder', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['categoryocshop'] = $this->url->link('catalog/categoryocshop', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['geoip_link'] = $this->url->link('module/geoip', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['csvpricelink'] = $this->url->link('module/csvprice_pro', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['translator'] = $this->url->link('module/textandheadings', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['order_bottom_forms'] = $this->url->link('localisation/order_bottom_forms', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['seogen'] = $this->url->link('module/seogen', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['metaseo_anypage'] = $this->url->link('tool/metaseo_anypage', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['etemplate'] = $this->url->link('module/emailtemplate', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['seo_snippet_link'] = $this->url->link('extension/hb_snippets', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['microdata_link'] = $this->url->link('module/microdata', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['optprices'] = $this->url->link('module/group_price', 'token=' . $this->session->data['token'], 'SSL');
                
            //modules
            $this->data['mod_latest'] = $this->url->link('module/latest', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['mod_featured'] = $this->url->link('module/featured', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['mod_blokviewed'] = $this->url->link('module/viewed', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['mod_featuredreview'] = $this->url->link('module/featuredreview', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['mod_bestseller'] = $this->url->link('module/bestseller', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['mod_special'] = $this->url->link('module/special', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['mod_customproduct'] = $this->url->link('module/customproduct', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['sets_link'] = $this->url->link('module/set', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['openbay_show_menu'] = $this->config->get('openbaymanager_show_menu');
                
            $this->data['openbay_link_extension'] = $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['openbay_link_orders'] = $this->url->link('extension/openbay/orderList', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['openbay_link_items'] = $this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['openbay_link_ebay'] = $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['openbay_link_ebay_settings'] = $this->url->link('openbay/openbay/settings', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['openbay_link_ebay_links'] = $this->url->link('openbay/openbay/viewItemLinks', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['openbay_link_ebay_orderimport'] = $this->url->link('openbay/openbay/viewOrderImport', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['openbay_link_amazon'] = $this->url->link('openbay/amazon', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['openbay_link_amazon_settings'] = $this->url->link('openbay/amazon/settings', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['openbay_link_amazon_links'] = $this->url->link('openbay/amazon/itemLinks', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['openbay_link_amazonus'] = $this->url->link('openbay/amazonus', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['openbay_link_amazonus_settings'] = $this->url->link('openbay/amazonus/settings', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['openbay_link_amazonus_links'] = $this->url->link('openbay/amazonus/itemLinks', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->data['openbay_markets'] = array(
            'ebay' => $this->config->get('openbay_status'),
            'amazon' => $this->config->get('amazon_status'),
            'amazonus' => $this->config->get('amazonus_status'),
            );
                
            $this->data['token'] = $this->session->data['token'];
                
                
            $this->data['paypal_express'] = $this->url->link('payment/pp_express', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['paypal_express_search'] = $this->url->link('payment/pp_express/search', 'token=' . $this->session->data['token'], 'SSL');
            $this->data['recurring_profile'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'], 'SSL');
                
            $this->load->model('sale/callback');
            $this->data['total_callbacks'] = $this->model_sale_callback->getOpenedCallBacks();
                
            $this->data['callback'] = $this->url->link('sale/callback', 'token=' . $this->session->data['token'], 'SSL');//!
            $this->data['text_callback'] = $this->language->get('text_callback');//!
                
            /* Admin Header Notices 1.0 */
                
            //waitlist ready
            $this->load->model('catalog/product');
            $this->data['total_waitlist_ready'] = $this->model_catalog_product->getTotalProductsWaitList(array('filter_supplier_has' => 1));
            $this->data['total_waitlist_prewaits'] = $this->model_catalog_product->getProductsWaitListTotalPreWaits();
                
            //CURRENCIES
            $this->load->model('localisation/currency');
            $currencies = $this->model_localisation_currency->getCurrencies();
                
            //count EUR TO UAH
            foreach ($currencies as $currency) {
                if ($currency['code']  == 'KZT') {
                    $this->data['KZTEUR'] = number_format($currency['value'], 2, '.', ' ') . ' кзт.';
                } elseif ($currency['code']  == 'UAH') {
                    $this->data['UAHEUR'] = number_format($currency['value'], 2, '.', ' ') . ' грн.';
                } elseif ($currency['code']  == 'BYN') {
                    $this->data['BYREUR'] = number_format($currency['value'], 2, '.', ' ') . ' быр.';
                } elseif ($currency['code']  == 'RUB') {
                    $this->data['RUBEUR'] = number_format($currency['value'], 2, '.', ' ') . ' руб.';
                }
            }
                
            //try to determine currency for order
            if (isset($this->request->get['order_id'])) {
                $order = $this->model_sale_order->getOrder($this->request->get['order_id']);
                    
                if ($order) {
                    $ccode = $order['currency_code'].'EUR';
                        
                    if (isset($this->data[$ccode])) {
                        $this->data['ONLYCURRENCY'] = $this->data[$ccode];
                    }
                }
            }
                
            //try to determine currency for order
            if (isset($this->request->get['filter_store_id'])) {
                $this->load->model('setting/setting');
                $setting = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $this->request->get['filter_store_id']);
                    
                if ($setting) {
                    $ccode = $setting.'EUR';
                        
                    if (isset($this->data[$ccode])) {
                        $this->data['ONLYCURRENCY'] = $this->data[$ccode];
                    }
                }
            }
                
            //try to determine currency for order
            if (isset($this->request->get['filter_order_store_id'])) {
                $this->load->model('setting/setting');
                $setting = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $this->request->get['filter_order_store_id']);
                    
                if ($setting) {
                    $ccode = $setting.'EUR';
                        
                    if (isset($this->data[$ccode])) {
                        $this->data['ONLYCURRENCY'] = $this->data[$ccode];
                    }
                }
            }
                
            $this->load->model('kp/work');
            if ($this->user->getID()) {
                $this->data['kpi_stats'] = $this->model_kp_work->getManagerLastKPI($this->user->getID());
            }
                
            $this->data['token'] = $this->session->data['token'];
                
                
            $this->data['stores'] = array();
                
            $this->load->model('setting/store');
            $results = $this->model_setting_store->getStores();

            foreach ($results as $result) {
                $this->data['stores'][] = array(
                'name' => $result['name'],
                'href' => !empty($result['url'])?$result['url']:HTTPS_SERVER
                );
            }
        }
            
            
        if ($template_prefix = $this->user->getTemplatePrefix()) {
            if (file_exists(DIR_TEMPLATE . 'common/headers/header'.$template_prefix.'.tpl')) {
                $this->template = 'common/headers/header'.$template_prefix.'.tpl';
            } else {
                $this->template = 'common/header.tpl';
            }
        } else {
            $this->template = 'common/header.tpl';
        }
            
            
            $this->render();
    }
}
