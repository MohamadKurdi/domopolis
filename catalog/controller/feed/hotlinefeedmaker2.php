<?php
class ControllerFeedHotlineFeedMaker2 extends Controller
{
    private $steps          = [0, 100, 500, 1000, 2000, 5000, 7000, 10000, 15000, 20000, 25000, 1000000000];    
    private $tree_csv       = 'https://hotline.ua/download/hotline/hotline_tree_uk.csv';
    private $file           = 'hotline_full_feed_{store_id}_{language_id}_{iteration}.xml';
    private $maxNameLength  = 150;

    private $stockMode              = false;
    private $exclude_language_id    = null;
    private $languages              = [];
    private $urlcode                = '';
    private $eanLog                 = false;

    private $store_id               = 0;
    private $language_id            = null;
    private $iteration              = 0;

    private $maxCategoryId = 0;
    private $hotlineCategories          = [];
    private $hotlineCategoriesMapping   = [];
    private $allowedManufacturers       = [];



    public function __construct($registry){
        parent::__construct($registry);

        $this->maxCategoryId = (int)$this->db->query("SELECT MAX(category_id) as max_category_id FROM category WHERE 1")->row['max_category_id'] + 10;

        $query = $this->db->query("SELECT * FROM category_hotline_tree WHERE 1");

        foreach ($query->rows as $row){
            $this->hotlineCategories[$row['full_name']] = (int)$row['category_id'] + $this->maxCategoryId;
        }

        $query = $this->db->query("SELECT category_id, hotline_category_name FROM category WHERE hotline_enable = 1 AND status = 1");

         foreach ($query->rows as $row){
            $this->hotlineCategoriesMapping[$row['category_id']] = $row['hotline_category_name'];
        }

        $query = $this->db->query("SELECT manufacturer_id FROM manufacturer WHERE hotline_enable = 1");

         foreach ($query->rows as $row){
            $this->allowedManufacturers[] = $row['manufacturer_id'];
        }

        echoLine('[ControllerFeedHotlineFeedMaker2] Cache is built', 's');
    }

    public function tree(){        
        $this->db->query("TRUNCATE category_hotline_tree");

        $csv = file($this->tree_csv, FILE_SKIP_EMPTY_LINES);
        foreach($csv as &$line){
            $line = trim(iconv('cp1251', 'UTF-8', $line));
        }

        $levels = [0 => 0];
        $names  = [0 => ''];

        foreach ($csv as $line){            
            preg_match('/^;+/', $line, $matches);   
            if ($matches){
                $currentLevel   = strlen($matches[0]);
            } else {
                $currentLevel   = 0;
            }      
            $line   = str_replace(';', '', $line);
            if (empty($line)) continue;

            if ($currentLevel == 0){
                $levels = [0 => 0];
                $names  = [0 => ''];
            }

            if (empty($names[$currentLevel])){
                $full_name = $line;               
            } else {  
                $full_name = $names[$currentLevel] . '/' . $line;              
            }
           
            $this->db->query("INSERT INTO category_hotline_tree SET parent_id = '" . (int)$levels[$currentLevel] . "', final_category = 0, name = '" . $this->db->escape($line) . "', full_name = '" . $this->db->escape($full_name) . "'");
            $category_id = $this->db->getLastId();

            $levels[$currentLevel + 1]  = $category_id;
            $names[$currentLevel + 1]   = $full_name;
      }

      $this->db->query("UPDATE category_hotline_tree SET final_category = 1 WHERE category_id NOT IN (SELECT parent_id FROM category_hotline_tree)");
    }        

    private function writeYML(){
        $file = str_replace(['{store_id}', '{language_id}', '{iteration}'], [$this->store_id, $this->language_id, $this->iteration], $this->file);

        file_put_contents(DIR_REFEEDS . $file, $this->hml);

        return $this;
    }

    private function openYML(){
        $this->hml  = '';
        $this->hml .= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $this->hml .= '<price>' . PHP_EOL;
        $this->hml .= '<date>' . date('Y-m-d H:i:s') . '</date>' . PHP_EOL;
        $this->hml .= '<firmId>' . (int)$this->config->get('config_hotline_merchant_id') . '</firmId>' . PHP_EOL;

        return $this;
    }

    private function addCategories(){               
        $this->hml .= '<categories>' . PHP_EOL;

        $query = $this->db->query("SELECT cd.name, c.category_id, c.parent_id FROM category c 
            LEFT JOIN category_description cd ON (c.category_id = cd.category_id) LEFT JOIN category_to_store c2s ON (c.category_id = c2s.category_id) 
            WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
            AND c.status = '1' 
            AND c.sort_order <> '-1'");

        foreach ($query->rows as $row){
            $this->hml .= ' <category>' . PHP_EOL; 
            $this->hml .= '     <id>' . $row['category_id'] . '</id>' . PHP_EOL; 
            $this->hml .= '     <name>' . normalizeForYML($row['name']) . '</name>' . PHP_EOL; 

            if (!empty($row['parent_id'])){
                $this->hml .= '     <parentId>' . $row['parent_id'] . '</parentId>' . PHP_EOL; 
            }

            $this->hml .= ' </category>' . PHP_EOL;
        }

        if ($this->config->get('config_hotline_enable_category_tree')){
            $query = $this->db->query("SELECT * FROM category_hotline_tree WHERE 1");

            foreach ($query->rows as $row){
                $this->hml .= ' <category>' . PHP_EOL; 
                $this->hml .= '     <id>' . (int)($this->maxCategoryId + $row['category_id']) . '</id>' . PHP_EOL; 
                $this->hml .= '     <name>' . normalizeForYML($row['name']) . '</name>' . PHP_EOL; 

                if (!empty($row['parent_id'])){
                    $this->hml .= '     <parentId>' . (int)($this->maxCategoryId + $row['parent_id']) . '</parentId>' . PHP_EOL; 
                }

                $this->hml .= ' </category>' . PHP_EOL;
            }
        }

        $this->hml .= '</categories>' . PHP_EOL;

        echoLine('[ControllerFeedHotlineFeedMaker2] Added Categories', 's');

        return $this;
    }

    private function closeYML(){
        $this->hml .= '</price>' . PHP_EOL;

        return $this;
    }

    private function printItem($product, $hotline_category_id){
        $this->hml .= ' <item>' . PHP_EOL;

        $this->hml .= '     <id>' . $product['product_id'] . '</id>' . PHP_EOL;
        $this->hml .= '     <categoryId>' . $hotline_category_id . '</categoryId>' . PHP_EOL;

        if ($product['sku']){
            $this->hml .= '     <code><![CDATA[' . $product['sku'] . ']]></code>' . PHP_EOL;
        } elseif ($product['asin']){
            $this->hml .= '     <asin><![CDATA[' . $product['asin'] . ']]></asin>' . PHP_EOL;
        } elseif ($product['model']){
            $this->hml .= '     <model><![CDATA[' . $product['model'] . ']]></model>' . PHP_EOL;
        }
        
        if ($product['ean'] && (\BarcodeValidator::IsValidEAN13($product['ean']) || \BarcodeValidator::IsValidEAN8($product['ean']))) {
            $this->hml .= '     <barcode><![CDATA[' . $product['ean'] . ']]></barcode>' . PHP_EOL;
        }

        if ($product['manufacturer']){
            $this->hml .= '     <vendor><![CDATA[' . $product['manufacturer'] . ']]></vendor>' . PHP_EOL;
        } else {
            $this->hml .= '     <vendor><![CDATA[' . $this->config->get('config_owner') . ']]></vendor>' . PHP_EOL;
        }

        $this->hml .= '     <name><![CDATA[' . normalizeForYML($product['name']) . ']]></name>' . PHP_EOL;
        $this->hml .= '     <description><![CDATA[' . normalizeForYML($product['description']) . ']]></description>' . PHP_EOL;
        $this->hml .= '     <url><![CDATA[' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . ']]></url>' . PHP_EOL;

        if ($product['image']){
            $this->hml .= '     <image><![CDATA[' . $this->model_tool_image->resize($product['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) . ']]></image>' . PHP_EOL;
        }

        if ((float)$product['special']) {
            $this->hml .= '     <priceRUAH><![CDATA[' . $this->currency->format($product['special'], '', '', false) . ']]></priceRUAH>' . PHP_EOL;
        } else {
            $this->hml .= '     <priceRUAH><![CDATA[' . $this->currency->format($product['price'], '', '', false) . ']]></priceRUAH>' . PHP_EOL;
        }

        if ((int)$product['quantity']) {
            $this->hml .= '     <stock><![CDATA[В наявності]]></stock>' . PHP_EOL;
        } else {
            $this->hml .= '     <stock><![CDATA[Немає]]></stock>' . PHP_EOL;
        }

        if ((int)$product[$this->config->get('config_warehouse_identifier')]) {
            $this->hml .= '     <shipping><![CDATA[1]]></shipping>' . PHP_EOL;
        } else {
            if ((int)$product['quantity']) {
                $this->hml .= '     <shipping><![CDATA[' . $this->config->get('config_delivery_central_term') . ']]></shipping>' . PHP_EOL;
            }
        }

        $attributes = $this->model_catalog_product->getProductAttributesFlat($product['product_id']);
        if ($attributes) {
            foreach ($attributes as $attribute) {
               $this->hml .= '     <param name="' . normalizeForYML($attribute['attribute_name']) . '"><![CDATA[' . normalizeForYML($attribute['attribute_value']) . ']]></param>' . PHP_EOL;
            }
        }

        $this->hml .= '     <condition>0</condition>' . PHP_EOL;

        $this->hml .= ' </item>' . PHP_EOL;
    }

    public function makefeed(){        
     //   $this->rainforestAmazon->offersParser->PriceLogic->updatePricesFromDelayed();    

        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('localisation/currency');
        $this->load->model('tool/image');
        $this->load->model('catalog/manufacturer');

        $this->language_id    = $this->config->get('config_language_id');
        $this->store_id       = $this->config->get('config_store_id');

        $query = $this->db->query("SELECT *, cd.name as category_name FROM category c LEFT JOIN category_description cd ON c.category_id = cd.category_id WHERE hotline_enable = 1 AND status = 1 AND language_id = '" . (int)$this->language_id . "'");

        $this->openYML()->addCategories();

        $total_in_feed  = 0;
        foreach ($query->rows as $hotline_category) {
            echoLine('[ControllerFeedHotlineFeedMaker2] Working at category ' . $hotline_category['name'], 'i');


            $hotline_category_id = $hotline_category['category_id'];
            if (!empty($this->hotlineCategoriesMapping[$hotline_category['category_id']])){
                $hotline_category_full_name = $this->hotlineCategoriesMapping[$hotline_category['category_id']];

                if (!empty($this->hotlineCategories[$hotline_category_full_name])){
                    $hotline_category_id = (int)$this->hotlineCategories[$hotline_category_full_name];
                    echoLine('[ControllerFeedHotlineFeedMaker2] Found Hotline overload ' . $hotline_category_full_name . ', id: ' . $hotline_category_id, 's');
                }
            }

            $filter = [
                'filter_category_id'    => $hotline_category['category_id'],
                'start'                 => 0,
                'limit'                 => PHP_INT_MAX,
                'filter_status'         => true,
                'filter_not_bad'        => true,
                'filter_return_simple'  => true,
                'filter_exclude_certs'  => true
            ];

            if ($this->allowedManufacturers){
                $filter['filter_manufacturer_ids'] = $this->allowedManufacturers;
            }

            $total = $this->model_catalog_product->getTotalProducts($filter);
            echoLine('[ControllerFeedHotlineFeedMaker2] Total Products: ' . $total,'w');

            if ((int)($total + $total_in_feed) > (int)$this->config->get('config_hotline_feed_limit')){
                echoLine('[ControllerFeedHotlineFeedMaker2] One feed limit overage, continuing to next!', 'e');

                $this->closeYML()->writeYML();
                $this->openYML()->addCategories();
                $this->iteration++;
            }           

            $products = $this->model_catalog_product->getProducts($filter);

            $this->hml .= '<items>' . PHP_EOL;

            foreach ($products as $product) {
                if (!isFriendlyURL($this->url->link('product/product', 'product_id=' . $product['product_id']))){
                    continue;
                }

                $product = $this->model_catalog_product->getProduct($product['product_id']);
                $this->printItem($product, $hotline_category_id);

                echo '.';
            }

             $this->hml .= '</items>' . PHP_EOL;

             echoLine('');
        }

        $this->closeYML()->writeYML();
    }
}