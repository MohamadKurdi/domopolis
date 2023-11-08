<?php  
    class ControllerCatalogAddSpecials extends Controller {
        private $product_filter = array();
        private $label_messege = array("type" => "success", "message" => array());
        
        
        public function index() {
            $this->load->language('catalog/addspecials');
            $this->load->model('catalog/manufacturer');
            $this->load->model('catalog/category');
            $this->load->model('setting/store');
            
            $this->data['breadcrumbs'] = array();
            
            $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
            'separator' => false
            );
            
            $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('catalog/addspecials', 'token=' . $this->session->data['token']),
            'separator' => ' :: '
            );
            
            if (isset($this->error['warning'])) {
                $this->data['error_warning'] = $this->error['warning'];
                } else {
                $this->data['error_warning'] = '';
            }
            
            $this->document->setTitle($this->language->get('heading_title'));
            $this->data['heading_title'] = $this->language->get('heading_title');
            
            $this->data['text_image_manager'] = $this->language->get('text_image_manager');
            $this->data['text_browse'] = $this->language->get('text_browse');
            $this->data['text_clear'] = $this->language->get('text_clear');
            
            $this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
            $this->data['entry_category'] = $this->language->get('entry_category');
            $this->data['entry_model'] = $this->language->get('entry_model');
            $this->data['entry_name'] = $this->language->get('entry_name');
            $this->data['entry_store'] = $this->language->get('entry_store');
            $this->data['entry_subcategory'] = $this->language->get('entry_subcategory');
            $this->data['store_default'] = $this->language->get('store_default');
            
            $this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
            $this->data['entry_quantity'] = $this->language->get('entry_quantity');
            $this->data['entry_priority'] = $this->language->get('entry_priority');
            $this->data['entry_price'] = $this->language->get('entry_price');
            $this->data['entry_percent'] = $this->language->get('entry_percent');
            $this->data['entry_date_start'] = $this->language->get('entry_date_start');
            $this->data['entry_date_end'] = $this->language->get('entry_date_end');
            $this->data['entry_image'] = $this->language->get('entry_image');
            $this->data['entry_name'] = $this->language->get('entry_name');
            
            
            $this->data['tab_additional_offer'] = $this->language->get('tab_additional_offer');
            $this->data['tab_del'] = $this->language->get('tab_del');
            
            $this->data['button_additional_offer'] = $this->language->get('button_additional_offer');
            $this->data['button_del'] = $this->language->get('button_del');
            
            $this->data['text_filtered_products'] = $this->language->get('text_filtered_products');
            
            $this->data['text_all'] = $this->language->get('text_all');
            
            $this->data['option_none'] = $this->language->get('option_none');
            $this->data['option_all'] = $this->language->get('option_all');
            
            $this->data['token'] = $this->session->data['token'];
            
            $this->data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
            $this->data['categories'] = $this->model_catalog_category->getCategories(0);
            
            $this->load->model('setting/store');			
            $this->data['stores'] = $this->model_setting_store->getStores();
            
            $this->load->model('sale/customer_group');
            
            $this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
            
            $this->load->model('tool/image');
            $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
            
            $this->data['add_additional_offer'] = $this->url->link('catalog/addspecials/add_additional_offer', 'token=' . $this->session->data['token']);
            $this->data['del_specials'] = $this->url->link('catalog/addspecials/del_specials', 'token=' . $this->session->data['token']);
            
            // Получаем все спец-предложения
            $sql = "SELECT 
            ao.product_additional_offer_id as id, 
            ao.price, 
            ao.percent, 
            ao.ao_group, 
            pd1.name as name1, 
            p1.sku as sku1,
            p1.product_id as product_id1, 
            p1.price as product_price_1, 
            ps1.price as product_special_1, 
            pd2.name as name2, 
            p2.sku as sku2, 
            p2.product_id as product_id2,
            p2.price as product_price_2, 
            ps2.price as product_special_2 
            FROM `product_additional_offer` ao 
            LEFT JOIN `product` p1 ON (ao.product_id = p1.product_id) 
            LEFT JOIN `product_description` pd1 ON (p1.product_id = pd1.product_id) 
            LEFT JOIN `product` p2 ON (ao.ao_product_id = p2.product_id) 
            LEFT JOIN `product_description` pd2 ON (p2.product_id = pd2.product_id)
            LEFT JOIN `product_special` ps1 ON (ps1.product_id = p1.product_id) 
            LEFT JOIN `product_special` ps2 ON (ps2.product_id = p2.product_id) 
            WHERE 
            pd1.language_id = '" . $this->config->get('config_language_id') . "' AND
            pd2.language_id = '" . $this->config->get('config_language_id') . "'
            GROUP BY ao.product_additional_offer_id
            ORDER BY ao.ao_group DESC";
            
            $query = $this->db->query($sql);
            
            
            
            $this->data['additional_offers'] = array();
            foreach ($query->rows as $oa) {	
                
                $this->data['additional_offers'][] = array(
                'id'        => $oa['id'],
                'price'     => $oa['price'],
                'percent'   => $oa['percent'],
				'ao_group'  => $oa['ao_group'],
                'name_1'    => $oa['name1'],
                'name_2'    => $oa['name2'],   
                'sku1'     => $oa['sku1'],
                'sku2'     => $oa['sku2'],
                'product_id1'     => $oa['product_id1'],
                'product_id2'     => $oa['product_id2'],
                );
                
                $this->data['delete_url'] = str_replace('&amp;', '&', $this->url->link('catalog/addspecials/delete', 'token=' . $this->session->data['token'], 'SSL'));
                $this->data['update_url'] = str_replace('&amp;', '&', $this->url->link('catalog/addspecials/update', 'token=' . $this->session->data['token'], 'SSL'));
                
            }
            
            
            $this->template = 'catalog/addspecials.tpl';
            $this->children = array(
			'common/header',
			'common/footer'
            );
            
            $this->response->setOutput($this->render());
        }
        
        public function productFilter(){
            $data = array();
            $json = array();
            
            $this->product_filter["name"] = "";
            if (isset($this->request->post['name'])) {
                $this->product_filter["name"] = $this->request->post['name'];
                $data['filter_name'] = $this->product_filter["name"];
            }
            
            $this->product_filter["model"] = "";
            if (isset($this->request->post['model'])) {
                $this->product_filter["model"] = $this->request->post['model'];
                $data['filter_model'] = $this->product_filter["model"];
            }
            
            $this->product_filter["ao_group"] = "";
            if (isset($this->request->post['ao_group'])) {
                $this->product_filter["ao_group"] = $this->request->post['ao_group'];
                $data['filter_ao_group'] = $this->product_filter["ao_group"];
            }
            
            $this->product_filter["ao_product_id"] = "";
            if (isset($this->request->post['ao_product_id'])) {
                $this->product_filter["ao_product_id"] = $this->request->post['ao_product_id'];
                $data['filter_ao_product_id'] = $this->product_filter["ao_product_id"];
            }
            
            $this->product_filter["change_all"] = false;
            if (isset($this->request->post['change_all'])) {
                $this->product_filter["change_all"] = true;
            }
            
            $this->product_filter["change_ids"] = array();
            if (isset($this->request->post['product_to_change'])) {
                $this->product_filter["change_ids"] = $this->request->post['product_to_change'];
            }
            
            if (isset($this->request->get['product_list'])) {
                $this->product_filter["start"] = 0;
                $this->product_filter["limit"] = 30;
                if (isset($this->request->get['start'])) {
                    $this->product_filter["start"] = $this->request->get['start'];
                    $data['start'] = $this->product_filter["start"];
                }
                if (isset($this->request->get['limit'])) {
                    $this->product_filter["limit"] = $this->request->get['limit'];
                    $data['limit'] = $this->product_filter["limit"];
                }
            }
            
            $this->product_filter["store_id"] = -1;
            if (isset($this->request->post['store_id'])) {
                if ($this->request->post['store_id'] >= 0){
                    $this->product_filter["store_id"] = $this->request->post['store_id'];
                    $data['filter_store_id'] = $this->product_filter["store_id"];
                }
            }
            
            $this->product_filter["manufacturer_id"] = 0;
            if (isset($this->request->post['manufacturer_id'])) {
                $this->product_filter["manufacturer_id"] = $this->request->post['manufacturer_id'];
                $data['filter_manufacturer_id'] = $this->product_filter["manufacturer_id"];
            }
            
            $this->product_filter["category_id"] = 0;
            if (isset($this->request->post['category_id'])) {
                $this->product_filter["category_id"] = $this->request->post['category_id'];
                $data['filter_category_id'] = $this->product_filter["category_id"];
            }
            
            $this->product_filter["filter_sub_category"] = false;
            $data["filter_sub_category"] = false;
            if (isset($this->request->post['filter_sub_category'])) {
                $this->product_filter["filter_sub_category"] = true;
                $data["filter_sub_category"] = true;
            }
            
            $this->load->model('catalog/addspecials');
            
            $json['products'] = $this->model_catalog_addspecials->getProducts($data);
            $json['total'] = $this->model_catalog_addspecials->getTotalProducts($data);
            
            $this->response->setOutput(json_encode($json));
        }
        
        public function add_additional_offer() {
            $this->load->language('catalog/addspecials');
            $this->load->model('catalog/addspecials');
            $this->load->model('catalog/product');
            $json = array();
            $data = array();
            
            
            $product_additional_offer = array();
            if(isset($this->request->post['product_additional_offer'])) {
                $product_additional_offer['customer_group_id'] = 1;
                if(isset($this->request->post['product_additional_offer']['customer_group_id'])) {
                    $product_additional_offer['customer_group_id'] = $this->request->post['product_additional_offer']['customer_group_id'];
                }
                
                $product_additional_offer['priority'] = 1;
                if(isset($this->request->post['product_additional_offer']['priority'])) {
                    $product_additional_offer['priority'] = $this->request->post['product_additional_offer']['priority'];
                }
                
                $product_additional_offer['quantity'] = 1;
                if(isset($this->request->post['product_additional_offer']['quantity'])) {
                    $product_additional_offer['quantity'] = $this->request->post['product_additional_offer']['quantity'];
                }
                
                $product_additional_offer['ao_product_id'] = 0;
                if(isset($this->request->post['product_additional_offer']['ao_product_id'])) {
                    $product_additional_offer['ao_product_id'] = $this->request->post['product_additional_offer']['ao_product_id'];
                }
                
                $product_additional_offer['price'] = 0;
                if(isset($this->request->post['product_additional_offer']['price'])) {
                    $product_additional_offer['price'] = $this->request->post['product_additional_offer']['price'];
                }
				
				$product_additional_offer['percent'] = 0;
                if(isset($this->request->post['product_additional_offer']['percent'])) {
                    $product_additional_offer['percent'] = $this->request->post['product_additional_offer']['percent'];
                }
				
				$product_additional_offer['ao_group'] = '';
                if(isset($this->request->post['product_additional_offer']['ao_group'])) {
                    $product_additional_offer['ao_group'] = $this->request->post['product_additional_offer']['ao_group'];
                }
                
                $product_additional_offer['store_id'] = array();
                if(isset($this->request->post['product_additional_offer']['store_id'])) {
                    $product_additional_offer['store_id'] = $this->request->post['product_additional_offer']['store_id'];
                }
                
                $product_additional_offer['date_start'] = date('Y-m-d');
                if(isset($this->request->post['product_additional_offer']['date_start'])) {
                    $product_additional_offer['date_start'] = $this->request->post['product_additional_offer']['date_start'];
                }
                
                $product_additional_offer['date_end'] = date('Y-m-d');
                if(isset($this->request->post['product_additional_offer']['date_end'])) {
                    $product_additional_offer['date_end'] = $this->request->post['product_additional_offer']['date_end'];
                }
                
                $product_additional_offer['image'] = 'no-image.jpg';
                if(isset($this->request->post['product_additional_offer']['image'])) {
                    $product_additional_offer['image'] = $this->request->post['product_additional_offer']['image'];
                }
                
                $product_additional_offer['description'] = '';
                if(isset($this->request->post['product_additional_offer']['description'])) {
                    $product_additional_offer['description'] = $this->request->post['product_additional_offer']['description'];
                }
                
                if(isset($this->request->post['product_to_change']) && !empty($this->request->post['product_additional_offer']['ao_product_id']) ) {
                    foreach($this->request->post['product_to_change'] as $product) {
                        $this->model_catalog_addspecials->AddAdditionalOffer($product,$product_additional_offer);
                    }    
                }
                
            }
            $json['message']['type'] = 'success';
            
            $json['message']['message'] = $this->language->get('success_add_specials');
            
            $this->response->setOutput(json_encode($json));
        }
        
        public function del_specials() {
            $this->load->language('catalog/addspecials');
            $this->load->model('catalog/addspecials');
            $this->load->model('catalog/product');
            $json = array();
            $data = array();
            
            
            $data['filter_category_id'] = null;
            if($this->request->post['category_id'] != 0) {
                $data['filter_category_id'] = $this->request->post['category_id'];
            }
            $data['filter_name'] = null;
            if($this->request->post['name']) {
                $data['filter_name'] = $this->request->post['name'];
            }
            $data['filter_model'] = null;
            if($this->request->post['model']) {
                $data['filter_model'] = $this->request->post['model'];
            }
            $data['filter_manufacturer_id'] = null;
            if($this->request->post['manufacturer_id'] != 0) {
                $data['filter_manufacturer_id'] = $this->request->post['manufacturer_id'];
            }
            $data['filter_sub_category'] = null;
            if(isset($this->request->post['filter_sub_category']) ) {
                $data['filter_sub_category'] = true;
            }
            $products = $this->model_catalog_addspecials->getProducts($data);
            $json['total'] = count($products);
            $json['products'] = $products;
            
            $product_del = array();
            if(isset($this->request->post['del'])) {
                $product_del['discount'] = false;
                if(isset($this->request->post['del']['discount'])) {
                    $product_del['discount'] = $this->request->post['del']['discount'];
                }
                
                $product_del['special'] = false;
                if(isset($this->request->post['del']['special'])) {
                    $product_del['special'] = $this->request->post['del']['special'];
                }
                
                $product_del['additional_offer'] = false;
                if(isset($this->request->post['del']['additional_offer'])) {
                    $product_del['additional_offer'] = $this->request->post['del']['additional_offer'];
                }
            }
            if(isset($this->request->post['product_to_change']) ) {
                foreach($this->request->post['product_to_change'] as $product) {
                    $this->model_catalog_addspecials->DelSpecial($product,$product_del);
                }    
            }
            
            $json['message']['type'] = 'success';
            
            $json['message']['message'] = $this->language->get('success_del_specials');
            
            $this->response->setOutput(json_encode($json));
        }
        
        
        public function delete () {
            $this->db->query("DELETE FROM `product_additional_offer` WHERE `product_additional_offer_id` IN (".implode(", ", $this->request->post['delete_id']).")");
        }
        
        public function update () {
            // Мы можем изменить только цену, и скидку
            
            foreach ($this->request->post['price'] as $id => $price) {
                $this->db->query("UPDATE `product_additional_offer` SET `price` = '".(float)$price."' WHERE `product_additional_offer_id` = (".(int)$id.")");
            }
            
            foreach ($this->request->post['percent'] as $id => $price) {
                $this->db->query("UPDATE `product_additional_offer` SET `percent` = '".(float)$price."' WHERE `product_additional_offer_id` = (".(int)$id.")");
            }
            
            foreach ($this->request->post['ao_group'] as $id => $ao_group) {			
                $this->db->query("UPDATE `product_additional_offer` SET `ao_group` = '".$this->db->escape(trim($ao_group))."' WHERE `product_additional_offer_id` = (".(int)$id.")");
            }
            // $this->db->query("DELETE FROM `product_additional_offer` WHERE `product_additional_offer_id` IN (".implode(", ", $this->request->post['delete_id']).")");
        }
        
        
    }    