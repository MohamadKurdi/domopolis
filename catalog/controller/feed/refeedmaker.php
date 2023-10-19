<?php
class ControllerFeedReFeedMaker extends Controller
{
    private $steps                  = array(0, 100, 500, 1000, 2000, 5000, 7000, 10000, 15000, 20000, 25000, 1000000000);
    private $limit                  = 20000;
    private $exclude_language_id    = null;
    private $language_id            = null;
    private $languages              = [];
    private $urlcode                = '';
    private $eanLog                 = false;
    private $attribute_separator    = false;

    public function __construct($registry){
        parent::__construct($registry);

        if ($this->config->get('mega_filter_settings') && $this->config->get('mega_filter_settings')['attribute_separator']){             
            $this->attribute_separator = $this->config->get('mega_filter_settings')['attribute_separator'];
        }
    }

    private function loadSettings($store_id)
    {
        $query = $this->db->non_cached_query("SELECT * FROM setting WHERE store_id = '0' OR store_id = '" . $store_id . "' ORDER BY store_id ASC");
        foreach ($query->rows as $setting) {
            if (!$setting['serialized']) {
                $this->config->set($setting['key'], $setting['value']);
            } else {
                $this->config->set($setting['key'], unserialize($setting['value']));
            }
        }

        $this->config->set('config_store_id', $store_id);
        $this->config->set('config_language_id', $this->registry->get('languages')[$this->config->get('config_language')]['language_id']);
        $this->currency->set($this->config->get('config_regional_currency'));

        return $this;
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

    private function setURLCode($urlcode)
    {
        $this->urlcode = $urlcode;
    }

    private function setSteps()
    {
        $steps = array();
        if ($this->config->get('config_store_id') == 1) {
            $steps = $this->steps;
        } else {
            foreach ($this->steps as $step) {
                $steps[] = $this->currency->convert($step, 'UAH', $this->currency->getCode(), true, true);
            }
        }

        if ($this->config->get('config_store_id') != 1) {
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

        $output = str_replace(max($this->steps), 'MORE', $output);


        if ((float)$product['special']) {
             $output .= '  <g:custom_label_2><![CDATA[HAS_DISCOUNT]]></g:custom_label_2>' . PHP_EOL;
        }


        $output .= '  <g:availability><![CDATA[' . ($product['quantity'] ? 'in stock' : 'out of stock') . ']]></g:availability>'. PHP_EOL;
        $output .= '</item>'. PHP_EOL;


        return $output;
    }

    public function supplemental()
    {
        $this->load->model('catalog/product');

        $this->db->query("UPDATE product SET quantity = 0 WHERE quantity < 0 ");
        $this->db->query("UPDATE product_option_value SET quantity = 0 WHERE quantity < 0 ");

        foreach ($this->registry->get('supported_language_ids') as $store_id => $languages) {
            foreach ($languages as $language_id) {
                foreach (array(0,1) as $changeID) {
                    if ($language_id != $this->registry->get('excluded_language_id') && $changeID) {
                        continue;
                    }

                    if ($language_id == $this->registry->get('excluded_language_id')) {
                        if ($changeID) {
                            $file = DIR_REFEEDS . 'supplemental_feed_' . $store_id . '_' . $language_id . '_changed_ids.xml';
                        } else {
                            $file = DIR_REFEEDS . 'supplemental_feed_' . $store_id . '_' . $language_id . '.xml';
                        }
                    } else {
                        $file = DIR_REFEEDS . 'supplemental_feed_' . $store_id . '.xml';
                    }

                    $query = $this->db->non_cached_query("SELECT * FROM setting WHERE store_id = '0' OR store_id = '" . $store_id . "' ORDER BY store_id ASC");
                    foreach ($query->rows as $setting) {
                        if (!$setting['serialized']) {
                            $this->config->set($setting['key'], $setting['value']);
                        } else {
                            $this->config->set($setting['key'], unserialize($setting['value']));
                        }
                    }

                    $this->config->set('config_store_id', $store_id);
                    $this->config->set('config_language_id', $language_id);
                    $this->currency->set($this->config->get('config_regional_currency'));

                    $this->setSteps();

                    $output = '';
                    $output  = '<?xml version="1.0" encoding="UTF-8" ?>';
                    $output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
                    $output .= '  <channel>';
                    $output .= '  <title>' . $this->config->get('config_name') . '</title>';
                    $output .= '  <description>' . $this->config->get('config_meta_description') . '</description>';
                    $output .= '  <link>' . $this->config->get('config_url') . '</link>';

                    echo '[S] Магазин ' . $store_id . PHP_EOL;

                    $filter = array(
                        'start'                 => 0,
                        'limit'                 => 1000000,
                        'filter_status'         => true,
                        'filter_not_bad'        => true,
                        'filter_return_simple'  => true
                    );

                    $products = $this->model_catalog_product->getProducts($filter);

                    foreach ($products as $_product) {
                        $product = $this->model_catalog_product->getProduct($_product['product_id']);
                        $output .= $this->printItemFast($product, $changeID);
                        echo '.';
                    }

                    echo PHP_EOL;


                    $output .= '  </channel>';
                    $output .= '</rss>';

                    file_put_contents($file, $output);

                    echo convertSize(memory_get_usage(true)) . PHP_EOL;
                    echo '[*] собираем мусор, освобождаем память: ' . convertSize(memory_get_usage(true)) . PHP_EOL;
                    gc_collect_cycles();
                }
            }
        }
    }

    public function makeStockFeedsCron()
    {
        $this->makeFeedsCron(true);
    }

    public function makeAllExceptExcludedLanguageCron()
    {
        $query = $this->db->non_cached_query("SELECT * FROM store ORDER BY store_id ASC");

        $stores = [0];
        foreach ($query->rows as $row) {
            $stores[] = $row['store_id'];
        }

        foreach ($stores as $store_id) {
            if ($store_id == 18 || $this->config->get('config_language_id') == $this->registry->get('excluded_language_id')) {
                continue;
            }


            $this->loadSettings($store_id);
            echoLine('[CRON2] Магазин ' . $store_id . ', язык ' . $this->config->get('config_language'));
            $this->makeFeedsCron();
        }
    }

    public function makeFeedsCron($stock = false)
    {
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('localisation/currency');
        $this->load->model('tool/image');
        $this->load->model('catalog/manufacturer');

        $query = $this->db->non_cached_query("SELECT * FROM language");
        foreach ($query->rows as $result) {
            $languages[$result['code']] = array(
                'language_id' => $result['language_id'],
                'name'        => $result['name'],
                'code'        => $result['code'],
                'locale'      => $result['locale'],
                'directory'   => $result['directory'],
                'filename'    => $result['filename']
            );
        }

        $language_id = $this->config->get('config_language_id');
        $store_id = $this->config->get('config_store_id');
            
        foreach (array(0,1) as $changeID) {
            if ($language_id != $this->registry->get('excluded_language_id') && $changeID) {
                continue;
            }

            if ($language_id == 2) {
                $this->setEanLog();
            }

            $this->setLanguageID($language_id);

            if ($language_id == $this->registry->get('excluded_language_id')) {
                if ($changeID) {
                    $file = DIR_REFEEDS . 'remarketing_full_feed_' . $store_id . '_' . $language_id . '_changed_ids.xml';
                } else {
                    $file = DIR_REFEEDS . 'remarketing_full_feed_' . $store_id . '_' . $language_id . '.xml';
                }
            } else {
                $file = DIR_REFEEDS . 'remarketing_full_feed_' . $store_id . '.xml';
            }

            if ($stock) {
                $file = str_replace('remarketing_full_feed_', 'merchant_stock_feed_', $file);
            }

                // Settings
            $query = $this->db->non_cached_query("SELECT * FROM setting WHERE store_id = '0' OR store_id = '" . $store_id . "' ORDER BY store_id ASC");
            foreach ($query->rows as $setting) {
                if (!$setting['serialized']) {
                    $this->config->set($setting['key'], $setting['value']);
                } else {
                    $this->config->set($setting['key'], unserialize($setting['value']));
                }
            }

            $this->config->set('config_store_id', $store_id);
            $this->config->set('config_language_id', $language_id);
            $this->currency->set($this->config->get('config_regional_currency'));

            $output = '';
            $output  = '<?xml version="1.0" encoding="UTF-8" ?>';
            $output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
            $output .= '  <channel>';
            $output .= '  <title>' . $this->config->get('config_name') . '</title>';
            $output .= '  <description>' . $this->config->get('config_meta_description') . '</description>';
            $output .= '  <link>' . $this->config->get('config_url') . '</link>';

            $this->setSteps();

            echo '[S] Магазин ' . $store_id . PHP_EOL;
            echo '[KK] Получаем категории с заданным соответствием ' . PHP_EOL;

            $query = $this->db->query("SELECT *, cd.name as category_name FROM category c LEFT JOIN category_description cd ON c.category_id = cd.category_id LEFT JOIN google_base_category gbc ON c.google_category_id = gbc.google_base_category_id WHERE google_category_id > 0 AND no_general_feed = 0 AND cd.language_id = '" . $this->config->get('config_language_id') . "'");

            foreach ($query->rows as $google_base_category) {
                echo '[KK'. $store_id .'] Категория  ' . $google_base_category['category_name'] . PHP_EOL;
                echo '[KK'. $store_id .'] ' . $google_base_category['name'] . PHP_EOL;

                $filter = array(
                    'filter_category_id'    => $google_base_category['category_id'],
                    'start'                 => 0,
                    'limit'                 => PHP_INT_MAX,
                    'filter_status'         => true,
                    'filter_not_bad'        => true,
                    'filter_return_simple'  => true,
                    'filter_exclude_certs'  => true
                );

                if ($stock) {
                    $filter['filter_current_in_stock'] = true;
                }

                $products = $this->model_catalog_product->getProducts($filter);

                foreach ($products as $_product) {

                    if (!isFriendlyURL($this->url->link('product/product', 'product_id=' . $_product['product_id']))){
                        continue;
                    }
                    
                    $product = $this->model_catalog_product->getProduct($_product['product_id']);

                    if ($stock && $this->config->get('config_googlelocal_code')) {
                        $product['region_id'] = $this->config->get('config_googlelocal_code');
                    }


                    $output .= $this->printItem($product, $google_base_category, $changeID);
                    echo '.';
                }

                echo PHP_EOL;
            }

            $output .= '  </channel>';
            $output .= '</rss>';

            file_put_contents($file, $output);

            echo convertSize(memory_get_usage(true)) . PHP_EOL;
            echo '[*] собираем мусор, освобождаем память: ' . convertSize(memory_get_usage(true)) . PHP_EOL;
            gc_collect_cycles();

            if (!$stock) {
                $this->makeFeedsCron(true);
            }
        }

        $this->cleanUp();
    }

    protected function printItem($product, $google_base_category, $changeID = true)
    {
        $output = '';

        $output .= '<item>' . PHP_EOL;
        $output .= '<title><![CDATA[' . normalizeForGoogleV2($product['manufacturer'] . ' ' . $product['name']) . ']]></title>' . PHP_EOL;

        $output .= '<link>' . $this->url->link('product/product', 'product_id=' . $product['product_id'], 'SSL') . '</link>' . PHP_EOL;
        $output .= '<description><![CDATA[' . normalizeForGoogleV2(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'))) . ']]></description>' . PHP_EOL;

        
        $output .= '<g:brand><![CDATA[' . html_entity_decode($product['manufacturer'], ENT_QUOTES, 'UTF-8') . ']]></g:brand>'. PHP_EOL;
        $output .= '<g:condition>new</g:condition>'. PHP_EOL;

        if ($this->config->get('config_language_id') == $this->registry->get('excluded_language_id') && $changeID) {
            $output .= '<g:id>' . $product['product_id'] . '-'  . $this->config->get('config_language_id') . '</g:id>'. PHP_EOL;
        } else {
            $output .= '<g:id>' . $product['product_id'] . '</g:id>'. PHP_EOL;
        }

        if (!empty($product['region_id'])) {
            $output .= '<g:region_id><![CDATA[' . $product['region_id'] . ']]></g:region_id>'. PHP_EOL;
        }

        if (!empty($product['size'])) {
            $output .= '<g:size><![CDATA[' . $product['size'] . ']]></g:size>'. PHP_EOL;
        }

        if (!empty($product['color'])) {
            $output .= '<g:color><![CDATA[' . $product['color'] . ']]></g:color>'. PHP_EOL;
        }

        if (!empty($product['current_in_stock'])) {
            $output .= '<g:custom_label_0><![CDATA[На складе]]></g:custom_label_0>'. PHP_EOL;
        }

        $attributes = $this->model_catalog_product->getProductAttributesFlat($product['product_id']);
        if ($attributes) {
            foreach ($attributes as $attribute) {
                $output .= '    <g:product_detail>' . PHP_EOL;
                $output .= '        <g:section_name><![CDATA[' . $attribute['attribute_group'] . ']]></g:section_name>' . PHP_EOL;
                $output .= '        <g:attribute_name><![CDATA[' . $attribute['attribute_name'] . ']]></g:attribute_name>' . PHP_EOL;
                if ($this->attribute_separator){
                     $output .= '        <g:attribute_value><![CDATA[' . checkAndFormatMultiAttributes($attribute['text'], $this->attribute_separator, 'comma') . ']]></g:attribute_value>' . PHP_EOL;
                } else {
                     $output .= '        <g:attribute_value><![CDATA[' . $attribute['attribute_value'] . ']]></g:attribute_value>' . PHP_EOL;
                }               
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
            $output .= '  <g:image_link>' . $this->model_tool_image->resize($product['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) . '</g:image_link>'. PHP_EOL;
        } else {
            $output .= '  <g:image_link></g:image_link>'. PHP_EOL;
        }

        $images = $this->model_catalog_product->getProductImages($product['product_id']);
        if ($images) {
            foreach ($images as $image) {
                $output .= '  <g:additional_image_link>' . $this->model_tool_image->resize($image['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) . '</g:additional_image_link>'. PHP_EOL;
            }
        }

        $output .= '  <g:model_number>' . $product['model'] . '</g:model_number>'. PHP_EOL;

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
            if (defined('RESTRICTED_FUNCTIONS')){
                $output .= '  <g:mpn>' . $mpn . '</g:mpn>'. PHP_EOL;
            } else {
                $output .= '  <g:mpn><![CDATA[' . $mpn . ']]></g:mpn>'. PHP_EOL;
            }            
        }


        $has_gtin = false;

        if ($product['ean'] && (\BarcodeValidator::IsValidEAN13($product['ean']) || \BarcodeValidator::IsValidEAN8($product['ean']))) {
            $has_gtin = true;
            $output .= '  <g:ean><![CDATA[' . $product['ean'] . ']]></g:ean>'. PHP_EOL;
            $output .= '  <g:gtin><![CDATA[' . $product['ean'] . ']]></g:gtin>'. PHP_EOL;
        }

        if ($this->eanLog && $product['ean'] && !$has_gtin) {
            $this->eanLog->write('Некорректный EAN '. $product['product_id'] . ': ' . $product['ean']);
        }


        if (!trim($product['manufacturer']) || (trim($product['manufacturer']) && !$has_gtin && !$has_mpn)) {
            $output .= '  <g:identifier_exists>false</g:identifier_exists>'. PHP_EOL;
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

                //      echo '[DEBUG] Опа, товар со скидкой, sale_price = ' . $this->currency->format($product['special'], '', '', false) . ' ' . $this->currency->getCode() . PHP_EOL;
                //      echo '[DEBUG] Опа, товар со скидкой, sale_price_effective_date = ' . $product['special_date_end'] . ' = ' . date('c', strtotime('-1 day')) .'/'. date('c', strtotime($product['special_date_end'])) . PHP_EOL;
        }


        $output .= '  <g:google_product_category>' . $google_base_category['google_base_category_id'] . '</g:google_product_category>'. PHP_EOL;

        $categories = $this->model_catalog_product->getCategories($product['product_id']);

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

        $output .= '  <g:quantity>' . $product['quantity'] . '</g:quantity>'. PHP_EOL;
        $output .= '  <g:weight>' . $this->weight->format($product['weight'], $product['weight_class_id']) . '</g:weight>'. PHP_EOL;
        $output .= '  <g:availability><![CDATA[' . ($product['quantity'] ? 'in stock' : 'out of stock') . ']]></g:availability>'. PHP_EOL;
        $output .= '</item>'. PHP_EOL;

        return $output;
    }

}
