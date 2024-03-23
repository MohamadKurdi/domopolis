<?php
class ControllerCommonHeader extends Controller
{

    protected function index()   {
        $this->load->model('report/product');
        $this->load->model('sale/callback');
        $this->load->model('setting/store');
        $this->load->model('sale/order');

        $this->data['title']        = $this->document->getTitle();
        $this->data['config_title'] = $this->config->get('config_title');

        $this->data['base'] = HTTPS_SERVER;

        $updater = new \hobotix\Installer\Updater();
        $this->data['current_version'] = $updater->get_current();
        $this->data['global_version'] = $updater->get_global();
        $this->data['needs_update'] = ($updater->compare() < 0);

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

        $this->data['description']  = $this->document->getDescription();
        $this->data['keywords']     = $this->document->getKeywords();
        $this->data['links']        = $this->document->getLinks();
        $this->data['styles']       = $this->document->getStyles();
        $this->data['scripts']      = $this->document->getScripts();
        $this->data['lang']         = $this->language->get('code');
        $this->data['direction']    = $this->language->get('direction');

        if (!$this->user->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
            $this->data['logged'] = '';

            $this->data['home'] = $this->url->link('common/login', '');
        } else {

            $this->load->language('common/facommon');
            $this->data['text_facategory'] = $this->language->get('fagroups');

            $this->data += $this->language->load('common/header');

            $this->load->language('catalog/shop_rating');
            $this->data['text_shop_rating'] = $this->language->get('text_shop_rating');

            $this->data['logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
            $this->data['pp_express_status'] = $this->config->get('pp_express_status');

            $this->data['simple_module'] = $this->url->link('module/simple', 'token=' . $this->session->data['token']);
            $this->data['simple_module_abandoned'] = $this->url->link('module/simple', 'abandoned&token=' . $this->session->data['token']);

            $this->data['home']             = $this->url->link('common/home', 'token=' . $this->session->data['token']);
            $this->data['panel']            = $this->url->link('common/panel', 'token=' . $this->session->data['token']);
            $this->data['cronmon']          = $this->url->link('common/cronmon', 'token=' . $this->session->data['token']);
            $this->data['affiliate']        = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token']);
            $this->data['attribute']        = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token']);
            $this->data['attribute_group']  = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token']);
            $this->data['backup']           = $this->url->link('tool/backup', 'token=' . $this->session->data['token']);
            $this->data['report_affiliate_statistics']      = $this->url->link('report/affiliate_statistics', 'token=' . $this->session->data['token']);
            $this->data['report_affiliate_statistics_all']  = $this->url->link('report/affiliate_statistics_all', 'token=' . $this->session->data['token']);

            $this->data['report_reject']        = $this->url->link('report/sale_reject', 'token=' . $this->session->data['token']);
            $this->data['report_marketplace']   = $this->url->link('report/marketplace', 'token=' . $this->session->data['token']);

            $this->data['masspcategupd']    = $this->url->link('tool/masspcategupd', 'token=' . $this->session->data['token']);
            $this->data['masspdiscoupd']    = $this->url->link('tool/masspdiscoupd', 'token=' . $this->session->data['token']);
            $this->data['shortnames']       = $this->url->link('catalog/shortnames', 'token=' . $this->session->data['token']);            
            $this->data['shortnames2']      = $this->url->link('catalog/shortnames2', 'token=' . $this->session->data['token']);    

            $this->data['banner']           = $this->url->link('design/banner', 'token=' . $this->session->data['token']);
            $this->data['banner_module']    = $this->url->link('module/banner', 'token=' . $this->session->data['token']);
            $this->data['slideshow_module'] = $this->url->link('module/slideshow', 'token=' . $this->session->data['token']);

            $this->data['category']         = $this->url->link('catalog/category', 'token=' . $this->session->data['token']);
            $this->data['facategory']       = $this->url->link('catalog/facategory', 'token=' . $this->session->data['token']);
            $this->data['country']          = $this->url->link('localisation/country', 'token=' . $this->session->data['token']);
            $this->data['coupon']           = $this->url->link('sale/coupon', 'token=' . $this->session->data['token']);
            $this->data['courier_face']     = $this->url->link('sale/courier', 'token=' . $this->session->data['token']);
            $this->data['courier_face2']    = $this->url->link('sale/courier2', 'token=' . $this->session->data['token']);
            $this->data['currency']         = $this->url->link('localisation/currency', 'token=' . $this->session->data['token']);
            $this->data['customer']         = $this->url->link('sale/customer', 'token=' . $this->session->data['token']);
            $this->data['customer_manual']  = $this->url->link('sale/customer_manual', 'token=' . $this->session->data['token']);            
            $this->data['customer_fields']  = $this->url->link('sale/customer_field', 'token=' . $this->session->data['token']);
            $this->data['customer_group']   = $this->url->link('sale/customer_group', 'token=' . $this->session->data['token']);
            $this->data['customer_ban_ip']  = $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token']);
            $this->data['custom_field']     = $this->url->link('design/custom_field', 'token=' . $this->session->data['token']);
            $this->data['download']         = $this->url->link('catalog/download', 'token=' . $this->session->data['token']);
            $this->data['error_log']        = $this->url->link('tool/error_log', 'token=' . $this->session->data['token']);
            $this->data['feed']             = $this->url->link('extension/feed', 'token=' . $this->session->data['token']);
            $this->data['seo_feeds']        = $this->url->link('setting/feeds', 'token=' . $this->session->data['token']);
            $this->data['filter']           = $this->url->link('catalog/filter', 'token=' . $this->session->data['token']);
            $this->data['geo_zone']         = $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token']);
            $this->data['information']      = $this->url->link('catalog/information', 'token=' . $this->session->data['token']);
            $this->data['review_category']  = $this->url->link('catalog/categoryreview', 'token=' . $this->session->data['token']);
            $this->data['landingpage']      = $this->url->link('catalog/landingpage', 'token=' . $this->session->data['token']);
            $this->data['actiontemplate']   = $this->url->link('catalog/actiontemplate', 'token=' . $this->session->data['token']);           
            $this->data['language']         = $this->url->link('localisation/language', 'token=' . $this->session->data['token']);
            $this->data['legalperson']      = $this->url->link('localisation/legalperson', 'token=' . $this->session->data['token']);
            $this->data['layout']           = $this->url->link('design/layout', 'token=' . $this->session->data['token']);
            $this->data['logout']           = $this->url->link('common/logout', 'token=' . $this->session->data['token']);
            $this->data['contact']          = $this->url->link('sale/contact', 'token=' . $this->session->data['token']);
            $this->data['manager']          = $this->url->link('extension/manager', 'token=' . $this->session->data['token']);
            $this->data['manufacturer']     = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token']);
            $this->data['module']           = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token']);
            $this->data['option']           = $this->url->link('catalog/option', 'token=' . $this->session->data['token']);
            $this->data['ocfilter']         = $this->url->link('catalog/ocfilter', 'token=' . $this->session->data['token']);
            $this->data['ocfilter_page']    = $this->url->link('catalog/ocfilter/page', 'token=' . $this->session->data['token']);
            $this->data['ocfilter_module']          = $this->url->link('module/ocfilter', 'token=' . $this->session->data['token']);
            $this->data['megafilter_module']        = $this->url->link('module/mega_filter', 'token=' . $this->session->data['token']);
            $this->data['information_attribute']    = $this->url->link('catalog/information_attribute', 'token=' . $this->session->data['token']);
            $this->data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token']);

            $this->data['sale_checkbox']    = $this->url->link('sale/receipt', 'token=' . $this->session->data['token']);
            $this->data['module_checkbox']  = $this->url->link('module/receipt', 'token=' . $this->session->data['token']);
            $this->data['log_checkbox']     = $this->url->link('tool/receipt_log', 'token=' . $this->session->data['token']);

            $this->data['addspecials']      = $this->url->link('catalog/addspecials', 'token='.$this->session->data['token']);
            $this->data['labelmaker']       = $this->url->link('module/labelmaker', 'token=' . $this->session->data['token']);
            $this->data['notify_bar']       = $this->url->link('module/notify_bar', 'token=' . $this->session->data['token']);
            $this->data['actions']          = $this->url->link('catalog/actions', 'token=' . $this->session->data['token']);
            
            $this->data['fucked_order_total'] = $this->model_sale_order->getTotalOrders(['filter_order_status_id' => '0']);

            $this->data['fucked_link'] = $this->url->link('sale/order', 'filter_order_status_id=0&token=' . $this->session->data['token']);

            $this->data['report_adv_sale_order'] = $this->url->link('report/adv_sale_order', 'token=' . $this->session->data['token']);
            $this->data['report_adv_product_purchased'] = $this->url->link('report/adv_product_purchased', 'token=' . $this->session->data['token']);

            $this->data['suppliers'] = $this->url->link('sale/supplier', 'token=' . $this->session->data['token']);
            $this->data['buyerprices'] = $this->url->link('catalog/buyer_prices', 'token=' . $this->session->data['token']);
            $this->data['amazonorder'] = $this->url->link('sale/amazon', 'token=' . $this->session->data['token']);

            $this->data['waitlist'] = $this->url->link('catalog/waitlist', 'token=' . $this->session->data['token']);
            $this->data['stocks'] = $this->url->link('catalog/stocks', 'token=' . $this->session->data['token']);

            $this->data['yandex'] = $this->url->link('catalog/yandex', 'token=' . $this->session->data['token']);
            $this->data['priceva'] = $this->url->link('catalog/priceva', 'token=' . $this->session->data['token']);

            $this->data['waitlist_ready'] = $this->url->link('catalog/waitlist', 'filter_supplier_has=1&token=' . $this->session->data['token']);
            $this->data['waitlist_pre'] = $this->url->link('catalog/waitlist', 'filter_prewait=1&token=' . $this->session->data['token']);
            $this->data['parties'] = $this->url->link('catalog/parties', 'token=' . $this->session->data['token']);
            $this->data['competitors'] = $this->url->link('sale/competitors', 'token=' . $this->session->data['token']);
            $this->data['order_status'] = $this->url->link('localisation/order_status', 'token=' . $this->session->data['token']);
            $this->data['payment'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token']);
            
            $this->data['addasin']          = $this->url->link('catalog/addasin', 'token=' . $this->session->data['token']);
            $this->data['addasin_amazonv1'] = $this->url->link('catalog/addasin/amazon', 'token=' . $this->session->data['token']);
            $this->data['addasin_amazonv2'] = $this->url->link('catalog/addasin/amazonv2', 'token=' . $this->session->data['token']);
            $this->data['addasin_report']   = $this->url->link('catalog/addasin/report', 'token=' . $this->session->data['token']);
            $this->data['total_product_in_asin_queue'] = $this->model_report_product->getCountWaitingInASINQueue();

            $this->data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token']);
            if ($this->config->get('admin_quick_edit_status') && $this->config->get('aqe_catalog_products_status') && isset($this->session->data['token'])) {
                $this->data['product'] = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token']);
            }
            $this->data['product_deletedasin']          = $this->url->link('report/product_deletedasin', 'token=' . $this->session->data['token']);
            $this->data['product_excludedasin']         = $this->url->link('report/product_excludedasin', 'token=' . $this->session->data['token']); 
            $this->data['product_parser']               = $this->url->link('catalog/product_parser', 'token=' . $this->session->data['token']);
            $this->data['profile']                      = $this->url->link('catalog/profile', 'token=' . $this->session->data['token']);
            $this->data['report_sale_order']            = $this->url->link('report/sale_order', 'token=' . $this->session->data['token']);
            $this->data['report_sale_tax']              = $this->url->link('report/sale_tax', 'token=' . $this->session->data['token']);
            $this->data['report_sale_shipping']         = $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token']);
            $this->data['report_sale_return']           = $this->url->link('report/sale_return', 'token=' . $this->session->data['token']);
            $this->data['report_sale_coupon']           = $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token']);
            $this->data['report_product_viewed']        = $this->url->link('report/product_viewed', 'token=' . $this->session->data['token']);
            $this->data['report_buyanalyze']            = $this->url->link('report/buyanalyze', 'token=' . $this->session->data['token']);
            $this->data['report_product_purchased']     = $this->url->link('report/product_purchased', 'token=' . $this->session->data['token']);
            $this->data['report_customer_online']       = $this->url->link('report/customer_online', 'token=' . $this->session->data['token']);
            $this->data['report_customer_order']        = $this->url->link('report/customer_order', 'token=' . $this->session->data['token']);
            $this->data['report_customer_reward']       = $this->url->link('report/customer_reward', 'token=' . $this->session->data['token']);
            $this->data['report_customer_credit']       = $this->url->link('report/customer_credit', 'token=' . $this->session->data['token']);
            $this->data['report_affiliate_commission']  = $this->url->link('report/affiliate_commission', 'token=' . $this->session->data['token']);
            $this->data['review']                       = $this->url->link('catalog/review', 'token=' . $this->session->data['token']);
            $this->data['shop_rating']                  = $this->url->link('catalog/shop_rating', 'token=' . $this->session->data['token']);

            $this->data['subscribe']        = $this->url->link('catalog/subscribe', 'token=' . $this->session->data['token']);
            $this->data['return']           = $this->url->link('sale/return', 'token=' . $this->session->data['token']);
            $this->data['return_action']    = $this->url->link('localisation/return_action', 'token=' . $this->session->data['token']);
            $this->data['return_reason']    = $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token']);
            $this->data['return_status']    = $this->url->link('localisation/return_status', 'token=' . $this->session->data['token']);
            $this->data['shipping']         = $this->url->link('extension/shipping', 'token=' . $this->session->data['token']);
            $this->data['setting']          = $this->url->link('setting/store', 'token=' . $this->session->data['token']);
            $this->data['rnf']              = $this->url->link('setting/rnf', 'token=' . $this->session->data['token']);

            $this->data['store'] = HTTPS_CATALOG;
            $this->data['stock_status']     = $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token']);
            $this->data['product_groups']   = $this->url->link('localisation/product_groups', 'token=' . $this->session->data['token']);
            $this->data['tax_class']    = $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token']);
            $this->data['tax_rate']     = $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token']);
            $this->data['total']        = $this->url->link('extension/total', 'token=' . $this->session->data['token']);
            $this->data['user']         = $this->url->link('user/user', 'token=' . $this->session->data['token']);
            $this->data['user_ticket']  = $this->url->link('user/ticket', 'token=' . $this->session->data['token']);                    
            $this->data['manager_quality']  = $this->url->link('kp/managerquality', 'token=' . $this->session->data['token']);
            $this->data['user_sip']         = $this->url->link('user/user_sip', 'token=' . $this->session->data['token']);
            $this->data['user_sip_history'] = $this->user->getIPBX()?$this->url->link('user/user_sip/history', 'user_id='.$this->user->getID().'&token=' . $this->session->data['token'], 'SSL'):false;
            $this->data['user_alerts']      = $this->url->link('user/user_alerts', 'token=' . $this->session->data['token']);

            $this->data['user_myworktime']  = $this->url->link('user/mywork', 'token=' . $this->session->data['token']);
            $this->data['user_worktime']    = $this->url->link('user/work', 'token=' . $this->session->data['token']);
            $this->data['user_content']     = $this->url->link('user/content', 'token=' . $this->session->data['token']);

            $this->data['salary_manager'] = $this->url->link('kp/salary/countManagers', 'token=' . $this->session->data['token']);
            $this->data['salary_customerservice'] = $this->url->link('kp/salary/countCustomerService', 'token=' . $this->session->data['token']);

            $this->data['user_group'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token']);
            $this->data['voucher'] = $this->url->link('sale/voucher', 'token=' . $this->session->data['token']);
            $this->data['voucher_theme'] = $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token']);
            $this->data['weight_class'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token']);
            $this->data['length_class'] = $this->url->link('localisation/length_class', 'token=' . $this->session->data['token']);
            $this->data['vqmod_manager'] = $this->url->link('module/vqmod_manager', 'token=' . $this->session->data['token']);
            $this->data['zone'] = $this->url->link('localisation/zone', 'token=' . $this->session->data['token']);
            $this->language->load('common/newspanel');
            $this->data['nmod'] = $this->url->link('module/news', 'token=' . $this->session->data['token']);

            $this->data['ncmod'] = $this->url->link('module/ncategory', 'token=' . $this->session->data['token']);
            $this->data['npages'] = $this->url->link('catalog/news', 'token=' . $this->session->data['token']);
            $this->data['ncategory'] = $this->url->link('catalog/ncategory', 'token=' . $this->session->data['token']);
            $this->data['tocomments'] = $this->url->link('catalog/ncomments', 'token=' . $this->session->data['token']);
            $this->data['nauthor'] = $this->url->link('catalog/nauthor', 'token=' . $this->session->data['token']);
            $this->data['text_commod'] = $this->language->get('text_commod');
            $this->data['entry_npages'] = $this->language->get('entry_npages');
            $this->data['entry_nmod'] = $this->language->get('entry_nmod');
            $this->data['entry_ncmod'] = $this->language->get('entry_ncmod');
            $this->data['entry_ncategory'] = $this->language->get('entry_ncategory');
            $this->data['text_nauthor'] = $this->language->get('text_nauthor');

            $this->data['mreport_ttnscan'] = $this->url->link('report/mreports', 'report=ttnscan&token=' . $this->session->data['token']);
            $this->data['mreport_needtocall'] = $this->url->link('report/mreports', 'report=needtocall&token=' . $this->session->data['token']);
            $this->data['mreport_nopaid'] = $this->url->link('report/mreports', 'report=nopaid&token=' . $this->session->data['token']);
            $this->data['mreport_minusscan'] = $this->url->link('report/mreports', 'report=minusscan&token=' . $this->session->data['token']);
            $this->data['mreport_forgottencart'] = $this->url->link('report/mreports', 'report=forgottencart&token=' . $this->session->data['token']);

            $this->data['faq_url'] = $this->url->link('module/faq_system', 'token=' . $this->session->data['token']);

            $this->data['adminlog_url'] = $this->url->link('module/adminlog', 'token=' . $this->session->data['token']);
            $this->data['cdek_integrator'] = $this->url->link('module/cdek_integrator', 'token=' . $this->session->data['token']);

            $this->data['collections_link'] = $this->url->link('catalog/collection', 'token=' . $this->session->data['token']);
            $this->data['countrybrands_link'] = $this->url->link('catalog/countrybrand', 'token=' . $this->session->data['token']);

            $this->data['affiliate_mod_link'] = $this->url->link('module/affiliate', 'token=' . $this->session->data['token']);
            $this->data['anyport_link'] = $this->url->link('module/anyport', 'token=' . $this->session->data['token']);
            $this->data['excelport_link'] = $this->url->link('module/excelport', 'token=' . $this->session->data['token']);

            $this->data['rewards_gen'] = $this->url->link('module/reward_points_generator', 'token=' . $this->session->data['token']);
            $this->data['rewards_mod'] = $this->url->link('module/rewardpoints', 'token=' . $this->session->data['token']);

            $this->data['batch_editor_link'] = $this->url->link('module/batch_editor', 'token=' . $this->session->data['token']);
            $this->data['batch_editor_link2'] = $this->url->link('batch_editor/index', 'token=' . $this->session->data['token']);

            $this->data['advanced_banner_link'] = $this->url->link('module/ocaab', 'token=' . $this->session->data['token']);

            $this->data['sms_link'] = $this->url->link('sale/sms', 'token=' . $this->session->data['token']);
            $this->data['segments_link'] = $this->url->link('sale/segments', 'token=' . $this->session->data['token']);

            $this->data['invite_after_order'] = $this->url->link('module/apri', 'token=' . $this->session->data['token']);

            $this->data['custom_template_link'] = $this->url->link('module/custom_template', 'token=' . $this->session->data['token']);
            $this->data['mattimeotheme'] = $this->url->link('module/mattimeotheme', 'token=' . $this->session->data['token']);

            $this->data['autolink_link'] = $this->url->link('catalog/autolinks', 'token=' . $this->session->data['token']);

            $this->data['redirect_manager'] = $this->url->link('module/redirect_manager', 'token=' . $this->session->data['token']);

            $this->data['keyworder_link'] = $this->url->link('module/keyworder', 'token=' . $this->session->data['token']);
            $this->data['categoryocshop'] = $this->url->link('catalog/categoryocshop', 'token=' . $this->session->data['token']);
            $this->data['geoip_link'] = $this->url->link('module/geoip', 'token=' . $this->session->data['token']);

            $this->data['csvpricelink']                 = $this->url->link('module/csvprice_pro', 'token=' . $this->session->data['token']);
            $this->data['csvprice_pro_products']        = $this->url->link('csvprice_pro/app_product', 'token=' . $this->session->data['token']);                        
            $this->data['csvprice_pro_categories']      = $this->url->link('csvprice_pro/app_category', 'token=' . $this->session->data['token']);                                    
            $this->data['csvprice_pro_manufacturers']   = $this->url->link('csvprice_pro/app_manufacturer', 'token=' . $this->session->data['token']);                                                
            $this->data['csvprice_pro_customers']       = $this->url->link('csvprice_pro/app_customer', 'token=' . $this->session->data['token']);                        
            $this->data['csvprice_pro_orders']          = $this->url->link('csvprice_pro/app_order', 'token=' . $this->session->data['token']);

            $this->data['translator']         = $this->url->link('module/textandheadings', 'token=' . $this->session->data['token']);
            $this->data['order_bottom_forms'] = $this->url->link('localisation/order_bottom_forms', 'token=' . $this->session->data['token']);

            $this->data['seogen'] = $this->url->link('module/seogen', 'token=' . $this->session->data['token']);
            $this->data['metaseo_anypage'] = $this->url->link('tool/metaseo_anypage', 'token=' . $this->session->data['token']);
            $this->data['etemplate'] = $this->url->link('module/emailtemplate', 'token=' . $this->session->data['token']);
            $this->data['seo_snippet_link'] = $this->url->link('extension/hb_snippets', 'token=' . $this->session->data['token']);
            $this->data['microdata_link'] = $this->url->link('module/microdata', 'token=' . $this->session->data['token']);

            $this->data['optprices'] = $this->url->link('module/group_price', 'token=' . $this->session->data['token']);

            $this->data['mod_latest'] = $this->url->link('module/latest', 'token=' . $this->session->data['token']);
            $this->data['mod_featured'] = $this->url->link('module/featured', 'token=' . $this->session->data['token']);
            $this->data['mod_blokviewed'] = $this->url->link('module/viewed', 'token=' . $this->session->data['token']);
            $this->data['mod_featuredreview'] = $this->url->link('module/featuredreview', 'token=' . $this->session->data['token']);
            $this->data['mod_bestseller'] = $this->url->link('module/bestseller', 'token=' . $this->session->data['token']);
            $this->data['mod_special'] = $this->url->link('module/special', 'token=' . $this->session->data['token']);
            $this->data['mod_customproduct'] = $this->url->link('module/customproduct', 'token=' . $this->session->data['token']);
            $this->data['sets_link'] = $this->url->link('module/set', 'token=' . $this->session->data['token']);                                

            $this->data['paypal_express']           = $this->url->link('payment/pp_express', 'token=' . $this->session->data['token']);
            $this->data['paypal_express_search']    = $this->url->link('payment/pp_express/search', 'token=' . $this->session->data['token']);
            $this->data['recurring_profile']        = $this->url->link('sale/recurring', 'token=' . $this->session->data['token']);

            $this->data['total_callbacks']  = $this->model_sale_callback->getOpenedCallBacks();

            $this->data['callback']         = $this->url->link('sale/callback', 'token=' . $this->session->data['token']);
            $this->data['text_callback']    = $this->language->get('text_callback');

            $this->load->model('catalog/product');
            $this->data['total_waitlist_ready']     = $this->model_catalog_product->getTotalProductsWaitList(array('filter_supplier_has' => 1));
            $this->data['total_waitlist_prewaits']  = $this->model_catalog_product->getProductsWaitListTotalPreWaits();

            $this->load->model('localisation/currency');
            $currencies = $this->model_localisation_currency->getCurrencies();

            
            foreach ($currencies as $currency) {
                $this->data[$currency['code'] . 'EUR'] = $this->currency->format($currency['value'], $currency['code'], 1, true, false, false, true, 2);             
            }

            
            if (isset($this->request->get['order_id'])) {
                $order = $this->model_sale_order->getOrder($this->request->get['order_id']);

                if ($order) {
                    $ccode = $order['currency_code'].'EUR';

                    if (isset($this->data[$ccode])) {
                        $this->data['ONLYCURRENCY'] = $this->data[$ccode];
                    }
                }
            }

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

            if ($this->config->get('config_only_one_store_and_country')){
                $this->load->model('setting/setting');
                $setting = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', 0);

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
        
            $this->data['stores'] = [];
            $results = $this->model_setting_store->getStores();

            $this->data['token'] = $this->session->data['token'];

            foreach ($results as $result) {
                $this->data['stores'][] = array(
                    'name' => $result['name'],
                    'href' => !empty($result['url'])?$result['url']:HTTPS_SERVER
                );
            }
        }

        $this->template = 'common/header.tpl';
        if ($template_prefix = $this->user->getTemplatePrefix()) {
             $this->template = 'common/headers/header' . $template_prefix . '.tpl';
        }

        $this->render();
    }
}
