<?php
class ControllerFeedReFeedMaker2 extends Controller
{
    private $steps          = [0, 100, 500, 1000, 2000, 5000, 7000, 10000, 15000, 20000, 25000, 1000000000];    
    private $maxNameLength  = 150;

    private $stockMode              = false;
    private $exclude_language_id    = null;
    private $language_id            = null;
    private $languages              = [];
    private $urlcode                = '';
    private $eanLog                 = false;


    public function __construct($registry){
        parent::__construct($registry);            
    }

    private function setStockMode($stock)
    {
        $this->stockMode = $stock;
        return $this;
    }    

    private function openXML()
    {
        $output = '';
        $output  = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
        $output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . PHP_EOL;
        $output .= '  <channel>' . PHP_EOL;
        $output .= '  <title>' . $this->config->get('config_name') . '</title>' . PHP_EOL;
        $output .= '  <description>' . $this->config->get('config_meta_description') . '</description>' . PHP_EOL;
        $output .= '  <link>' . $this->config->get('config_url') . '</link>' . PHP_EOL;

        return $output;
    }

    public function cleanUp(){
        $feeds = glob(DIR_REFEEDS . '*');

        foreach ($feeds as $feed){
            if (is_file($feed) && (time() - filemtime($feed) > 60 * 60 * 24)){
                echoLine($feed . ', время больше двух дней, удаляем');
                unlink($feed);
            }
        }
    }

    private function closeXML()
    {
        $output = '';
        $output .= '  </channel>' . PHP_EOL;
        $output .= '</rss>' . PHP_EOL;

        return $output;
    }

    private function setLanguageID($language_id)
    {
        $this->language_id = $language_id;
        $this->load->model('localisation/language');
        $this->urlcode = $this->model_localisation_language->getLanguage($language_id)['urlcode'];

        return $this;
    }

    private function setEanLog()
    {
        $this->eanLog = new Log('invalid_ean.txt');
        $this->eanLog->clear();
        return $this;
    }

    private function setSteps()
    {        
        $steps = $this->steps;

        if ($this->config->get('config_store_id') != 0) {
            foreach ($steps as &$step) {
                $xStep = 0;
                for ($z = 10; $z <= 10000000000; $z*=5) {
                    if ($step && $step >= $z && $step <= $z*10) {
                        for ($i = 0; $i <= $steps[count($steps)-1]; $i+=$z) {
                            if ($i <= $step) {
                                $xStep = $i-$z;
                            }
                            break;
                        }
                        break;
                    }
                }
                $step = $xStep?($step - ($step % $xStep)):0;
            }
        }

        $this->steps = $steps;
    }    

    protected function printItemFast($product, $changeID = true)
    {
        if (!$product || !(float)$product['price']) {
            return '';
        }

        $output = '';

        $output .= '<item>' . PHP_EOL;

        if ($this->config->get('config_language_id') == $this->registry->get('excluded_language_id') && $changeID) {
            $output .= '<g:id>' . $product['product_id'] . '-'  . $this->config->get('config_language_id') . '</g:id>'. PHP_EOL;
        } else {
            $output .= '<g:id>' . $product['product_id'] . '</g:id>'. PHP_EOL;
        }

        if (!empty($product['current_in_stock'])) {
            $output .= '<g:custom_label_0><![CDATA[На складе]]></g:custom_label_0>'. PHP_EOL;
        }
        $output .= '  <g:price>' . $this->currency->format($product['price'], '', '', false) . ' ' .  $this->currency->getCode() . '</g:price>'. PHP_EOL;

        if ((float)$product['special']) {
            $output .= '  <g:sale_price>' .  $this->currency->format($product['special'], '', '', false) . ' ' . $this->currency->getCode() . '</g:sale_price>'. PHP_EOL;
            $output .= '  <g:offer_price>' .  $this->currency->format($product['special'], '', '', false) . ' ' . $this->currency->getCode() . '</g:offer_price>'. PHP_EOL;

            if (!empty($product['special_date_end']) && $product['special_date_end'] != '0000-00-00' && $product['special_date_end'] != '1970-01-01') {
                if (date('Y', strtotime($product['special_date_end'])) - date('Y') > 10) {
                    $product['special_date_end'] = str_replace(date('Y', strtotime($product['special_date_end'])), date('Y', strtotime('+2 year')), $product['special_date_end']);
                }

                $output .= '  <g:sale_price_effective_date>' . date('c', strtotime('-1 day')) .'/'. date('c', strtotime($product['special_date_end'])) . '</g:sale_price_effective_date>'. PHP_EOL;
                $output .= '  <g:offer_price_effective_date>' . date('c', strtotime('-1 day')) .'/'. date('c', strtotime($product['special_date_end'])) . '</g:offer_price_effective_date>'. PHP_EOL;
            }
        }

        $steps = $this->steps;
        for ($i = 0; $i<=(count($steps)-2); $i++) {
            if ((float)$this->currency->format($product['price'], '', '', false) > (float)$steps[$i] && (float)$this->currency->format($product['price'], '', '', false) <= (float)$steps[$i+1]) {
                $output .= '  <g:custom_label_1><![CDATA[' . 'price_' . $steps[$i] . '_' . $steps[$i+1] . ']]></g:custom_label_1>' . PHP_EOL;
                break;
            }
        }

        if ((float)$product['special']) {
             $output .= '  <g:custom_label_2><![CDATA[HAS_DISCOUNT]]></g:custom_label_2>' . PHP_EOL;
        }

        $output = str_replace(max($this->steps), 'MORE', $output);

        $output .= '  <g:availability><![CDATA[' . ($product['quantity'] ? 'in stock' : 'out of stock') . ']]></g:availability>'. PHP_EOL;
        $output .= '</item>'. PHP_EOL;


        return $output;
    }

    protected function printItem($product, $changeID = true)
    {

        if (!$product || !(float)$product['price']) {
            return '';
        }

        $output = '';

        $output .= '<item>' . PHP_EOL;

        if (!empty($product['short_name'])){
            $product['name'] = $product['short_name'];
        }

        $output .= '<title><![CDATA[' . shortentext(normalizeForGoogle($product['name']), $this->maxNameLength) . ']]></title>' . PHP_EOL;        

        $output .= '<link>' . $this->url->link('product/product', 'product_id=' . $product['product_id'], 'SSL') . '</link>' . PHP_EOL;
        $output .= '<description><![CDATA[' . normalizeForGoogle(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'))) . ']]></description>' . PHP_EOL;

        if (!empty($product['manufacturer'])){        
            $output .= '<g:brand><![CDATA[' . html_entity_decode($product['manufacturer'], ENT_QUOTES, 'UTF-8') . ']]></g:brand>'. PHP_EOL;
        } else {
            $output .= '<g:brand><![CDATA[NoName]]></g:brand>'. PHP_EOL;
        }

        $output .= '<g:condition>new</g:condition>'. PHP_EOL;

        if ($this->config->get('config_language_id') == $this->registry->get('excluded_language_id') && $changeID) {
            $output .= '<g:id>' . $product['product_id'] . '-'  . $this->config->get('config_language_id') . '</g:id>'. PHP_EOL;
        } else {
            $output .= '<g:id>' . $product['product_id'] . '</g:id>'. PHP_EOL;
        }

        if ($this->stockMode && !empty($product['region_id'])) {
            $output .= '    <g:region_id><![CDATA[' . $product['region_id'] . ']]></g:region_id>'. PHP_EOL;
        }

        if (!empty($product['size'])) {
            $output .= '    <g:size><![CDATA[' . $product['size'] . ']]></g:size>'. PHP_EOL;
        }

        if (!empty($product['color'])) {
            $output .= '    <g:color><![CDATA[' . $product['color'] . ']]></g:color>'. PHP_EOL;
        }

        if (!empty($product['current_in_stock'])) {
            $output .= '    <g:custom_label_0><![CDATA[На складе]]></g:custom_label_0>'. PHP_EOL;
        }

        $attributes = $this->model_catalog_product->getProductAttributesFlat($product['product_id']);
        if ($attributes) {
            foreach ($attributes as $attribute) {
                $output .= '    <g:product_detail>' . PHP_EOL;
                $output .= '        <g:section_name><![CDATA[' . $attribute['attribute_group'] . ']]></g:section_name>' . PHP_EOL;
                $output .= '        <g:attribute_name><![CDATA[' . $attribute['attribute_name'] . ']]></g:attribute_name>' . PHP_EOL;
                $output .= '        <g:attribute_value><![CDATA[' . $attribute['attribute_value'] . ']]></g:attribute_value>' . PHP_EOL;
                $output .= '    </g:product_detail>' . PHP_EOL;
            }
        }

        if ($this->config->get('config_use_separate_table_for_features')){
            $features = $this->model_catalog_product->getProductFeaturesFlat($product['product_id']);
            if ($features) {
                foreach ($features as $feature) {
                    $output .= '    <g:product_detail>' . PHP_EOL;
                    $output .= '        <g:section_name><![CDATA[' . $feature['attribute_group'] . ']]></g:section_name>' . PHP_EOL;
                    $output .= '        <g:attribute_name><![CDATA[' . $feature['attribute_name'] . ']]></g:attribute_name>' . PHP_EOL;
                    $output .= '        <g:attribute_value><![CDATA[' . $feature['attribute_value'] . ']]></g:attribute_value>' . PHP_EOL;
                    $output .= '    </g:product_detail>' . PHP_EOL;
                }
            }
        }

        if ($product['image']) {
            $output .= '    <g:image_link><![CDATA[' . $this->model_tool_image->resize($product['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) . ']]></g:image_link>'. PHP_EOL;
        } else {
            $output .= '    <g:image_link></g:image_link>'. PHP_EOL;
        }
        
        if ($product['images'] && $images = explode(':', $product['images'])) {
            foreach ($images as $image) {
                $output .= '    <g:additional_image_link><![CDATA[' . $this->model_tool_image->resize($image, $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) . ']]></g:additional_image_link>'. PHP_EOL;
            }
        }

        $output .= '    <g:model_number><![CDATA[' . $product['model'] . ']]></g:model_number>'. PHP_EOL;

        $mpn = false;
        $has_mpn = true;
        if ($product['sku']) {
            $mpn = $product['sku'];
        }

        if (!$mpn && $product['model']) {
            $mpn = $product['model'];
        }

        if ($mpn) {
            $has_mpn = true;
            $output .= '    <g:mpn><![CDATA[' . $mpn . ']]></g:mpn>'. PHP_EOL;
        }

        $has_gtin = false;

        if ($product['ean'] && (\BarcodeValidator::IsValidEAN13($product['ean']) || \BarcodeValidator::IsValidEAN8($product['ean']))) {
            $has_gtin = true;
            $output .= '    <g:ean><![CDATA[' . $product['ean'] . ']]></g:ean>'. PHP_EOL;
            $output .= '    <g:gtin><![CDATA[' . $product['ean'] . ']]></g:gtin>'. PHP_EOL;
        }

        if ($this->eanLog && $product['ean'] && !$has_gtin) {
            $this->eanLog->write('Некорректный EAN '. $product['product_id'] . ': ' . $product['ean']);
        }


        if (empty($product['manufacturer']) || !trim($product['manufacturer']) || (trim($product['manufacturer']) && !$has_gtin && !$has_mpn)) {
            $output .= '    <g:identifier_exists>false</g:identifier_exists>'. PHP_EOL;
        }

        $output .= '    <g:price><![CDATA[' . $this->currency->format($product['price'], '', '', false) . ' ' .  $this->currency->getCode() . ']]></g:price>'. PHP_EOL;

        if ((float)$product['special']) {
            $output .= '    <g:sale_price><![CDATA[' .  $this->currency->format($product['special'], '', '', false) . ' ' . $this->currency->getCode() . ']]></g:sale_price>'. PHP_EOL;
            $output .= '    <g:offer_price><![CDATA[' .  $this->currency->format($product['special'], '', '', false) . ' ' . $this->currency->getCode() . ']]></g:offer_price>'. PHP_EOL;

            if (!empty($product['special_date_end']) && $product['special_date_end'] != '0000-00-00' && $product['special_date_end'] != '1970-01-01') {
                if (date('Y', strtotime($product['special_date_end'])) - date('Y') > 10) {
                    $product['special_date_end'] = str_replace(date('Y', strtotime($product['special_date_end'])), date('Y', strtotime('+2 year')), $product['special_date_end']);
                }

                $output .= '    <g:sale_price_effective_date>' . date('c', strtotime('-1 day')) .'/'. date('c', strtotime($product['special_date_end'])) . '</g:sale_price_effective_date>'. PHP_EOL;
                $output .= '    <g:offer_price_effective_date>' . date('c', strtotime('-1 day')) .'/'. date('c', strtotime($product['special_date_end'])) . '</g:offer_price_effective_date>'. PHP_EOL;
            }
        }

        if (!empty($product['google_category_id'])) {
            $output .= '    <g:google_product_category>' . $product['google_category_id'] . '</g:google_product_category>'. PHP_EOL;
        }

        $steps = $this->steps;
        for ($i = 0; $i<=(count($steps)-2); $i++) {
            if ((float)$this->currency->format($product['price'], '', '', false) > $steps[$i] && (float)$this->currency->format($product['price'], '', '', false) <= $steps[$i+1]) {
                $output .= '  <g:custom_label_1><![CDATA[' . 'price_' . $steps[$i] . '_' . $steps[$i+1] . ']]></g:custom_label_1>' . PHP_EOL;
            }
        }

        $output = str_replace(max($this->steps), 'MORE', $output);

        if ((float)$product['special']) {
             $output .= '  <g:custom_label_2><![CDATA[HAS_DISCOUNT]]></g:custom_label_2>' . PHP_EOL;
        }

        if (!empty($product['main_category_id'])){
            $output .= '    <g:product_type><![CDATA[' . str_replace('/', ' > ', $this->model_catalog_product->getGoogleCategoryPathForCategory($product['main_category_id'])) . ']]></g:product_type>'. PHP_EOL;
        }

        //VARIANTS
        if (($product['main_variant_id'] || $product['variants_count']) && (!empty($product['color']) || !empty($product['material']))) {
            if ($product['main_variant_id']) {
                $output .= '    <g:item_group_id><![CDATA[VARIANTS' . $product['main_variant_id'] . ']]></g:item_group_id>'. PHP_EOL;
            } elseif ($product['variants_count']) {
                $output .= '    <g:item_group_id><![CDATA[VARIANTS' . $product['product_id'] . ']]></g:item_group_id>'. PHP_EOL;
            }
        }

        if (!empty($product['color'])) {
            $output .= '    <g:color><![CDATA[' . $product['color'] . ']]></g:color>'. PHP_EOL;
        }

        if (!empty($product['material'])) {
            $output .= '    <g:material><![CDATA[' . $product['material'] . ']]></g:material>'. PHP_EOL;
        }
        

        $output .= '    <g:quantity>' . $product['quantity'] . '</g:quantity>'. PHP_EOL;
        $output .= '    <g:weight>' . $this->weight->format($product['weight'], $product['weight_class_id']) . '</g:weight>'. PHP_EOL;
        $output .= '    <g:availability><![CDATA[' . ($product['quantity'] ? 'in stock' : 'out of stock') . ']]></g:availability>'. PHP_EOL;
        $output .= '</item>'. PHP_EOL;

        return $output;
    }

    public function supplemental()
    {

        if ($this->simpleProcess->isRunning('feed/refeedmaker2/makefeed')){   
            echoLine('[makefeed] Process feed/refeedmaker2/makefeed running we can not continue');
            return;
        }

        $this->load->model('catalog/product');

        $this->db->query("UPDATE product SET quantity = 0 WHERE quantity < 0 ");
        $this->db->query("UPDATE product_option_value SET quantity = 0 WHERE quantity < 0 ");

        foreach ($this->registry->get('supported_language_ids') as $store_id => $languages) {
            foreach ($languages as $language_id) {
                foreach (array(0,1) as $changeID) {                    
                    if ($language_id != $this->registry->get('excluded_language_id') && $changeID) {
                        continue;
                    }

                    $query = $this->db->non_cached_query("SELECT * FROM setting WHERE store_id = '0' OR store_id = '" . $store_id . "' ORDER BY store_id ASC");
                    foreach ($query->rows as $setting) {
                        if (!$setting['serialized']) {
                            $this->config->set($setting['key'], $setting['value']);
                        } else {
                            if ($setting['value']){
                                $this->config->set($setting['key'], unserialize($setting['value']));
                            } else {
                                $this->config->set($setting['key'], null);
                            }
                        }
                    }

                    $this->config->set('config_store_id', $store_id);
                    $this->config->set('config_language_id', $language_id);
                    $this->currency->set($this->config->get('config_regional_currency'));

                    $this->setSteps();

                    echoLine('[supplemental] Магазин ' . $store_id);
                    echoLine('[supplemental] Язык ' . $language_id);
                    echoLine('[supplemental] changeID ' . $changeID);

                    $filter = array(
                        'filter_status'         => true,
                        'filter_not_bad'        => true,
                        'filter_with_variants'  => true,
                        'filter_exclude_certs'  => true
                    );

                    $total = $this->model_catalog_product->getTotalProducts($filter);
                    $iterations = ceil($total/$this->config->get('config_google_merchant_feed_limit'));

                    echoLine('[supplemental] Всего товаров ' . $total);

                    if ($language_id == $this->registry->get('excluded_language_id')) {
                        if ($changeID) {
                            $file_full = DIR_REFEEDS . 'supplemental_feed_' . $store_id . '_' . $language_id . '_full_changed_ids.xml';
                        } else {
                            $file_full = DIR_REFEEDS . 'supplemental_feed_' . $store_id . '_' . $language_id . '_full.xml';
                        }
                    } else {
                        $file_full = DIR_REFEEDS . 'supplemental_feed_' . $store_id . '_full.xml';
                    }

                    $output_full = $this->openXML();

                    for ($i = 1; $i <= ($iterations); $i++) {
                        $output = $this->openXML();

                        $timer = new FPCTimer();

                        if ($language_id == $this->registry->get('excluded_language_id')) {
                            if ($changeID) {
                                $file = DIR_REFEEDS . 'supplemental_feed_' . $store_id . '_' . $language_id . '_' . $i . '_changed_ids.xml';
                            } else {
                                $file = DIR_REFEEDS . 'supplemental_feed_' . $store_id . '_' . $language_id . '_' . $i . '.xml';
                            }
                        } else {
                            $file = DIR_REFEEDS . 'supplemental_feed_' . $store_id . '_' . $i . '.xml';
                        }

                        $totalGet = ceil($this->config->get('config_google_merchant_feed_limit')/$this->config->get('config_google_merchant_one_iteration_limit'));
                        for ($iGet = 1; $iGet <= $totalGet; $iGet++) {

                            $filter = array(
                                'start'                     => ($i-1)*$this->config->get('config_google_merchant_feed_limit') + ($iGet-1)*$this->config->get('config_google_merchant_one_iteration_limit'),
                                'limit'                     => $this->config->get('config_google_merchant_one_iteration_limit'),
                                'filter_status'             => true,
                                'filter_not_bad'            => true,
                                'filter_exclude_certs'      => true,
                                'filter_with_variants'      => true,
                                'filter_get_product_mode'   => 'simple',
                                'sort'                      => 'p.product_id',
                                'order'                     => 'ASC'
                            );

                            echoLine('[supplemental] Итерация ' . $i . ' из ' . $iterations . ', товары с ' . ($filter['start']) . ' по ' . ($filter['start'] + $filter['limit']));                           
                            $products = $this->model_catalog_product->getProducts($filter);
                            if (!$products){
                                break;
                            }

                            $k = 0;
                            foreach ($products as $product) {

                                if (isFriendlyURL($this->url->link('product/product', 'product_id=' . $product['product_id']))){
                                    $itemPrinted = $this->printItemFast($product, $changeID);

                                    $output         .= $itemPrinted;
                                    $output_full    .= $itemPrinted;
                                }

                                if ($k % 10 == 0) {   echo $k . '..'; }
                                $k++;
                            }

                            echoLine('');
                        }

                        echoLine('[supplemental] Файл ' . $file);

                        $output .= $this->closeXML();
                        file_put_contents($file, $output);

                        echoLine('');
                        echoLine('[supplemental] памяти занято ' . convertSize(memory_get_usage(true)));
                        echoLine('[supplemental] собираем мусор, освобождаем память ' . convertSize(memory_get_usage(true)));
                        gc_collect_cycles();

                        echoLine('[supplemental] времени на итерацию ' . $timer->getTime() . ' сек.');
                        unset($timer);
                    }

                    $output_full .= $this->closeXML();
                    file_put_contents($file_full, $output_full);

                }
            }
        }
    }

    public function makestockfeed()
    {
        $this->setStockMode(true)->makefeed();
    }

    public function makefeed()
    {        
        $this->rainforestAmazon->offersParser->PriceLogic->updatePricesFromDelayed();    

        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('localisation/currency');
        $this->load->model('tool/image');
        $this->load->model('catalog/manufacturer');

        $language_id    = $this->config->get('config_language_id');
        $store_id       = $this->config->get('config_store_id');

        $this->setLanguageID($language_id);

        $this->db->query("UPDATE product SET quantity = 0 WHERE quantity < 0 ");
        $this->db->query("UPDATE product_option_value SET quantity = 0 WHERE quantity < 0 ");

        foreach (array(0,1) as $changeID) {
            if ($language_id != $this->registry->get('excluded_language_id') && $changeID) {
                continue;
            }

            $this->setSteps();

            echoLine('[makefeed] Магазин ' . $store_id);
            echoLine('[makefeed] Язык ' . $language_id);
            echoLine('[makefeed] changeID ' . $changeID);

            $filter = array(
                'filter_status'                     => true,
                'filter_not_bad'                    => true,
                'filter_with_variants'              => true,
                'filter_exclude_certs'              => true,
                'filter_exclude_google_categories'  => true
            );

            if ($this->stockMode) {
                $filter['filter_current_in_stock'] = true;
            }

            $total = $this->model_catalog_product->getTotalProducts($filter);
            $iterations = ceil($total/$this->config->get('config_google_merchant_feed_limit'));

            echoLine('[makefeed] Всего товаров ' . $total);

            for ($i = 1; $i <= ($iterations); $i++) {
                $output = $this->openXML();

                $timer = new FPCTimer();

                if ($language_id == $this->registry->get('excluded_language_id')) {
                    if ($changeID) {
                        $file = DIR_REFEEDS . 'remarketing_full_feed_' . $store_id . '_' . $language_id . '_' . $i . '_changed_ids.xml';
                    } else {
                        $file = DIR_REFEEDS . 'remarketing_full_feed_' . $store_id . '_' . $language_id . '_' . $i . '.xml';
                    }
                } else {
                    $file = DIR_REFEEDS . 'remarketing_full_feed_' . $store_id . '_' . $i . '.xml';
                }

                if ($this->stockMode) {
                    $file = str_replace('remarketing_full_feed_', 'merchant_stock_feed_', $file);
                }

                $totalGet = ceil($this->config->get('config_google_merchant_feed_limit')/$this->config->get('config_google_merchant_one_iteration_limit'));
                for ($iGet = 1; $iGet <= $totalGet; $iGet++) {

                    $filter = array(
                        'start'                     => ($i-1)*$this->config->get('config_google_merchant_feed_limit') + ($iGet-1)*$this->config->get('config_google_merchant_one_iteration_limit'),
                        'limit'                     => $this->config->get('config_google_merchant_one_iteration_limit'),
                        'filter_status'             => true,
                        'filter_not_bad'            => true,
                        'filter_exclude_certs'      => true,
                        'filter_with_variants'      => true,
                        'filter_get_product_mode'   => 'feed',
                        'sort'                      => 'p.product_id',
                        'order'                     => 'ASC'
                    );

                    echoLine('[makefeed] Итерация ' . $i . ' из ' . $iterations . ', товары с ' . ($filter['start']) . ' по ' . ($filter['start'] + $filter['limit']));
                    echoLine('[makefeed] Файл ' . $file);

                    $products = $this->model_catalog_product->getProducts($filter);
                    $k = 0;

                    foreach ($products as $product) {
                        if (!isFriendlyURL($this->url->link('product/product', 'product_id=' . $product['product_id']))){
                            continue;
                        }

                        $output .= $this->printItem($product, $changeID);

                        if ($k % 10 == 0) {   echo $k . '..'; }
                        $k++;
                    }

                    echoLine('');
                }

                $output .= $this->closeXML();
                file_put_contents($file, $output);

                echoLine('');
                echoLine('[makefeed] памяти занято ' . convertSize(memory_get_usage(true)));
                echoLine('[makefeed] собираем мусор, освобождаем память ' . convertSize(memory_get_usage(true)));
                gc_collect_cycles();

                echoLine('[makefeed] Времени на итерацию ' . $timer->getTime() . ' сек.');
                unset($timer);
            }
        }

        $this->cleanUp();
    }
}
