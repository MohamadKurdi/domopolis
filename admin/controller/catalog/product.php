<?php
class ControllerCatalogProduct extends Controller {
	private $error = []; 
	private $modelCatalogProduct;

	public function cli_deleteproduct($product_id = 0){
		$this->load->model('catalog/product');
		$this->model_catalog_product->deleteProduct($product_id);
	}

	public function ob_category() {
		$this->load->model('catalog/product');

		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 0;
		}

		$product_data = [];

		$results = $this->model_catalog_product->getProductsByCategoryId($category_id);

		foreach ($results as $result) {
			$product_data[] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model']
			);
		}

		$this->response->setOutput(json_encode($product_data));		
	}

	public function parsevariantnames(){
		$this->load->model('kp/product');

		$result = $this->model_kp_product->parseVariantNames($this->request->post['product_id'], $this->request->post['language_id'], $this->request->post['name']);

		$this->response->setOutput(json_encode($result));

	}

	public function index() {
		$this->language->load('catalog/product');

		$this->load->language('catalog/options_boost');
		$this->data['entry_info'] = $this->language->get('entry_info');
		$this->data['text_sku_override'] = $this->language->get('text_sku_override');			


		$this->document->setTitle($this->language->get('heading_title')); 

		$this->load->model('catalog/product');

		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product->addProduct($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';


			if ($this->config->get('admin_quick_edit_status') && $this->config->get('aqe_catalog_products_status')) {
				foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
					if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
						$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
					}
				}
				if (isset($this->request->get['filter_sub_category'])) {
					$url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
				}
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . urlencode(html_entity_decode($this->request->get['filter_price'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . urlencode(html_entity_decode($this->request->get['filter_quantity'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . urlencode(html_entity_decode($this->request->get['sort'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . urlencode(html_entity_decode($this->request->get['order'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . urlencode(html_entity_decode($this->request->get['page'], ENT_QUOTES, 'UTF-8'));
			}

			$this->redirect($this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function getFastHBT(){
		$stocks = array(
			'quantity_stock' => 'Германия',
			'quantity_stockK' => 'Украина',
			'quantity_stockM' => 'Россия',
			'quantity_stockMN' => 'Белоруссия',
			'quantity_stockAS' => 'Казахстан',
		);

		$this->load->model('catalog/product');
		$product_id = $this->request->get['product_id'];

		$product = $this->model_catalog_product->getProduct($product_id);

		$this->data['stocks'] = [];						
		foreach ($stocks as $key => $value){
			$this->data['stocks'][]= array(
				'name' => $value,
				'amount' => (int)$product[$key]
			);				
		}

		$this->data['product_id'] = $product_id;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/order_product_hbt.tpl';
		$this->response->setOutput($this->render());
	}

	public function getOptimalPrice () {
			// Загружаем нужные нам модели
		$this->load->model('catalog/product');
		$this->load->model('kp/price');

			// Получаем ID товара
		$productId = (int)$this->request->get['product_id'];

		$r = $this->model_kp_price->getProductPriceFromSources($productId);
		$resultArray = [];
		$resultArray['price'] = $r['price'];
		$resultArray['special_price'] =  $r['special_price'];
		$resultArray['suppliers'] = $r['result'];
		$resultArray['good'] = $r['sellData'];


		$resultArray['local_suppliers'] = [];


		$this->data['result'] = $resultArray;
			// Нужно вывести на экран
		$this->template = 'catalog/product_supplier_optimal_price.tpl';
		$this->response->setOutput($this->render());	
	}

	public function update() {
		$this->language->load('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';


			if ($this->config->get('admin_quick_edit_status') && $this->config->get('aqe_catalog_products_status')) {
				foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
					if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
						$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
					}
				}
				if (isset($this->request->get['filter_sub_category'])) {
					$url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
				}
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . urlencode(html_entity_decode($this->request->get['filter_price'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . urlencode(html_entity_decode($this->request->get['filter_quantity'], ENT_QUOTES, 'UTF-8'));
			}	

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . urlencode(html_entity_decode($this->request->get['sort'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . urlencode(html_entity_decode($this->request->get['order'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . urlencode(html_entity_decode($this->request->get['page'], ENT_QUOTES, 'UTF-8'));
			}

			if(isset($this->request->post['apply']) and $this->request->post['apply']) {
				$this->redirect($this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL'));
			} else {
				$this->redirect($this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL'));
				//	$this->redirect($this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');
		$this->load->model('kp/product');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				if ($this->rainforestAmazon->offersParser->PriceLogic->checkIfProductIsOnAnyWarehouse($product_id)){
					continue;
					echo 'P CND'; die();
				} else {
					$this->model_catalog_product->deleteProduct($product_id);
					$this->model_kp_product->deleteElastic($product_id);					
				}
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';


			if ($this->config->get('admin_quick_edit_status') && $this->config->get('aqe_catalog_products_status')) {
				foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
					if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
						$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
					}
				}
				if (isset($this->request->get['filter_sub_category'])) {
					$url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
				}
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . urlencode(html_entity_decode($this->request->get['filter_price'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . urlencode(html_entity_decode($this->request->get['filter_quantity'], ENT_QUOTES, 'UTF-8'));
			}	

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . urlencode(html_entity_decode($this->request->get['sort'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . urlencode(html_entity_decode($this->request->get['order'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . urlencode(html_entity_decode($this->request->get['page'], ENT_QUOTES, 'UTF-8'));
			}

			$this->redirect($this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function copyProductToPresent($product_id, $percent = 20, $count_orders = 2, $add_to_present = false){
		$this->load->model('catalog/product');

		$log = new Log('copy_to_stock.txt');

		$check_ord_query = $this->db->query("SELECT COUNT(*) as total FROM order_product op
			LEFT JOIN `order` o ON (op.order_id = o.order_id) 
			WHERE op.product_id = '" . (int)$product_id . "'
			AND o.date_added >= DATE_SUB(NOW(),INTERVAL 1 YEAR)
			AND o.order_status_id > 0
			");

		if ($check_ord_query->row['total'] >= $count_orders){

			$this->db->query("INSERT IGNORE INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = 6475");				
			$log->write('Добавлен  '.$product_id.' в подарочный');
			echo '>> Добавлен  '.$product_id.' в подарочный' . PHP_EOL;
		} else {
				// < 2
			echo '>> Пропущен  '.$product_id.', заказов меньше ' . $count_orders . PHP_EOL;
			$this->copyProductToStock($product_id, $percent, $count_orders, $add_to_present);
		}
	}

	public function copyProductToStock($product_id, $percent = 20, $count_orders = 2, $add_to_present = false){
		$this->load->model('catalog/product');

		$log = new Log('copy_to_stock.txt');

		$check_in_pa_query = $this->db->query("SELECT product_id FROM product_to_category WHERE product_id = '" . (int)$product_id . "' AND category_id = 6475");			
		$check_ord_query = $this->db->query("SELECT COUNT(*) as total FROM order_product op
			LEFT JOIN `order` o ON (op.order_id = o.order_id) 
			WHERE op.product_id = '" . (int)$product_id . "'
			AND o.date_added >= DATE_SUB(NOW(),INTERVAL 1 YEAR)
			AND o.order_status_id > 0");

		if ($check_in_pa_query->num_rows == 0 && $check_ord_query->row['total'] <= $count_orders){

			$check_query = $this->db->query("SELECT product_id FROM product WHERE stock_product_id = '" . (int)$product_id . "'");
			if ($check_query->num_rows){
					//включаем товар на стоке
				$this->db->query("UPDATE `product` SET 
					status = 1, 
					stock_status_id = '" . $this->config->get('config_stock_status_id') . "', 
					ean = '', 
					asin = '',
					isbn = ''
					WHERE product_id = '" . (int)$check_query->row['product_id'] . "'");

				$new_product_id = (int)$check_query->row['product_id'];

				$log->write('Включен  '.$product_id.' в стоке');
				echo '>> Включен  '.$product_id.' в стоке' . PHP_EOL;

				if ($add_to_present) {
					echo '>> Включен  '.$product_id.' в подарочный:)' . PHP_EOL;
					$this->db->query("INSERT IGNORE INTO product_to_category SET product_id = '" . (int)$new_product_id . "', category_id = 6475");
				}

			} else {
					//копируем товар и включаем..
				$new_product_id = $this->model_catalog_product->copyProduct($product_id);

				$this->db->query("DELETE FROM product_to_category WHERE product_id = '" .(int)$new_product_id. "'");
				$this->db->query("INSERT IGNORE INTO product_to_category SET product_id = '" . (int)$new_product_id . "', category_id = 6474");

				if ($add_to_present) {
					echo '>> Включен  '.$product_id.' в подарочный:)' . PHP_EOL;
					$this->db->query("INSERT IGNORE INTO product_to_category SET product_id = '" . (int)$new_product_id . "', category_id = 6475");		
				}

				$this->db->query("UPDATE product SET 
					stock_product_id = '" . (int)$product_id . "',
					stock_status_id = '" . $this->config->get('config_stock_status_id') . "', 
					ean = '', 
					asin = '',
					isbn = '',
					status = 1 WHERE product_id = '" .(int)$new_product_id. "'");

				$log->write('Скопирован  '.$product_id.' в '.$new_product_id . ', включен в стоке');
				echo '>> Скопирован  '.$product_id.' в '.$new_product_id . ', включен в стоке' . PHP_EOL;
			}


				//Копируем цену из основного товара
			$_query = $this->db->query("SELECT price FROM product WHERE product_id = '" . (int)$product_id  . "' LIMIT 1");

			if ($_query->row && isset($_query->row['price']) && $_query->row['price']){
				$this->db->query("UPDATE product p SET p.price = '" . $_query->row['price'] . "' WHERE p.product_id = '" . (int)$new_product_id . "'");
			}


				//обновляем цену товара на стоке
			$this->db->query("DELETE FROM product_special WHERE product_id = '" .(int)$new_product_id. "'");

			$special = $this->model_catalog_product->getProductSpecialOne($product_id);


			if ($special){

				$this->db->query("INSERT INTO product_special SET product_id = '" . (int)$new_product_id . "', customer_group_id = '1', priority = '1', price = '" . (float)($special * (100 - $percent) / 100) . "', date_start = '" . $this->db->escape('2005-12-12') . "', date_end = '" . $this->db->escape('2020-12-12') . "'");

			} else {

				$price = $this->model_catalog_product->getProductPrice($product_id);

				$this->db->query("INSERT INTO product_special SET product_id = '" . (int)$new_product_id . "', customer_group_id = '1', priority = '1', price = '" . (float)($price * (100 - $percent) / 100) . "', date_start = '" . $this->db->escape('2005-12-12') . "', date_end = '" . $this->db->escape('2020-12-12') . "'");

			}

		} else {
			$log->write('Пропущен  '.$product_id.' - есть в ПА или заказов больше ' . $count_orders);
			echo '>> Пропущен  '.$product_id.' - есть в ПА или заказов больше ' . $count_orders . PHP_EOL;
		}
	}

	public function copy() {
		$this->language->load('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->copyProduct($product_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';


			if ($this->config->get('admin_quick_edit_status') && $this->config->get('aqe_catalog_products_status')) {
				foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
					if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
						$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
					}
				}
				if (isset($this->request->get['filter_sub_category'])) {
					$url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
				}
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . urlencode(html_entity_decode($this->request->get['filter_price'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . urlencode(html_entity_decode($this->request->get['filter_quantity'], ENT_QUOTES, 'UTF-8'));
			}	

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . urlencode(html_entity_decode($this->request->get['sort'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . urlencode(html_entity_decode($this->request->get['order'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . urlencode(html_entity_decode($this->request->get['page'], ENT_QUOTES, 'UTF-8'));
			}

			$this->redirect($this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {				

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';


		if ($this->config->get('admin_quick_edit_status') && $this->config->get('aqe_catalog_products_status')) {
			foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
				if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
					$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
				}
			}
			if (isset($this->request->get['filter_sub_category'])) {
				$url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
			}
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . urlencode(html_entity_decode($this->request->get['filter_price'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . urlencode(html_entity_decode($this->request->get['filter_quantity'], ENT_QUOTES, 'UTF-8'));
		}		

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . urlencode(html_entity_decode($this->request->get['sort'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . urlencode(html_entity_decode($this->request->get['order'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . urlencode(html_entity_decode($this->request->get['page'], ENT_QUOTES, 'UTF-8'));
		}

		$this->data['breadcrumbs'] = [];

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url, 'SSL'),       		
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['copy'] = $this->url->link('catalog/product/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		$this->data['delete'] = $this->url->link('catalog/product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['products'] = [];

		$data = array(
			'filter_name'	  => $filter_name, 
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$this->load->model('tool/image');

		$product_total = $this->model_catalog_product->getTotalProducts($data);

		$results = $this->model_catalog_product->getProducts($data);

		foreach ($results as $result) {
			$action = [];

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);

			$special = false;

			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
					$special = $product_special['price'];

					break;
				}					
			}

			$this->data['products'][] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $result['price'],
				'special'    => $special,
				'image'      => $this->model_tool_image->resize($result['image'], 40, 40),
				'quantity'   => $result['quantity'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');		

		$this->data['text_enabled'] = $this->language->get('text_enabled');		
		$this->data['text_disabled'] = $this->language->get('text_disabled');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');		

		$this->data['column_image'] = $this->language->get('column_image');		
		$this->data['column_name'] = $this->language->get('column_name');		
		$this->data['column_model'] = $this->language->get('column_model');		
		$this->data['column_price'] = $this->language->get('column_price');		
		$this->data['column_quantity'] = $this->language->get('column_quantity');		
		$this->data['column_status'] = $this->language->get('column_status');		
		$this->data['column_action'] = $this->language->get('column_action');		

		$this->data['button_copy'] = $this->language->get('button_copy');		
		$this->data['button_insert'] = $this->language->get('button_insert');		
		$this->data['button_delete'] = $this->language->get('button_delete');		
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

		$this->data['tab_youtube'] = $this->language->get('tab_youtube');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';


		if ($this->config->get('admin_quick_edit_status') && $this->config->get('aqe_catalog_products_status')) {
			foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
				if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
					$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
				}
			}
			if (isset($this->request->get['filter_sub_category'])) {
				$url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
			}
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . urlencode(html_entity_decode($this->request->get['filter_price'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . urlencode(html_entity_decode($this->request->get['filter_quantity'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . urlencode(html_entity_decode($this->request->get['page'], ENT_QUOTES, 'UTF-8'));
		}

		$this->data['sort_name'] = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$this->data['sort_model'] = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$this->data['sort_price'] = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$this->data['sort_quantity'] = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$this->data['sort_order'] = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

		$url = '';


		if ($this->config->get('admin_quick_edit_status') && $this->config->get('aqe_catalog_products_status')) {
			foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
				if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
					$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
				}
			}
			if (isset($this->request->get['filter_sub_category'])) {
				$url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
			}
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . urlencode(html_entity_decode($this->request->get['filter_price'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . urlencode(html_entity_decode($this->request->get['filter_quantity'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . urlencode(html_entity_decode($this->request->get['sort'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . urlencode(html_entity_decode($this->request->get['order'], ENT_QUOTES, 'UTF-8'));
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_price'] = $filter_price;
		$this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_status'] = $filter_status;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/product_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->load->model('tool/image');


		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->load->language('catalog/options_boost');
		$this->data['entry_info'] = $this->language->get('entry_info');
		$this->data['text_sku_override'] = $this->language->get('text_sku_override');

		if ($this->config->get('config_load_ocfilter_in_product')) {
			$this->document->addStyle('view/stylesheet/ocfilter/ocfilter.css');
			$this->document->addScript('view/javascript/ocfilter/ocfilter.js');
		}

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_plus'] = $this->language->get('text_plus');
		$this->data['text_minus'] = $this->language->get('text_minus');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_option'] = $this->language->get('text_option');
		$this->data['text_option_value'] = $this->language->get('text_option_value');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');
		$this->data['text_type_radio'] = $this->language->get('text_type_radio');
		$this->data['text_type_select'] = $this->language->get('text_type_select');
		$this->data['text_type_checkbox'] = $this->language->get('text_type_checkbox');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_seo_title'] = $this->language->get('entry_seo_title');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_seo_h1'] = $this->language->get('entry_seo_h1');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_model'] = $this->language->get('entry_model');
		$this->data['entry_sku'] = $this->language->get('entry_sku');
		$this->data['entry_upc'] = $this->language->get('entry_upc');
		$this->data['entry_ean'] = $this->language->get('entry_ean');
		$this->data['entry_jan'] = $this->language->get('entry_jan');
		$this->data['entry_isbn'] = $this->language->get('entry_isbn');
		$this->data['entry_mpn'] = $this->language->get('entry_mpn');
		$this->data['entry_location'] = $this->language->get('entry_location');
		$this->data['entry_minimum'] = $this->language->get('entry_minimum');
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		$this->data['entry_date_available'] = $this->language->get('entry_date_available');
		$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
		$this->data['entry_price'] = $this->language->get('entry_price');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_points'] = $this->language->get('entry_points');
		$this->data['entry_option_points'] = $this->language->get('entry_option_points');
		$this->data['entry_subtract'] = $this->language->get('entry_subtract');
		$this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$this->data['entry_weight'] = $this->language->get('entry_weight');
		$this->data['entry_dimension'] = $this->language->get('entry_dimension');
		$this->data['entry_length'] = $this->language->get('entry_length');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_download'] = $this->language->get('entry_download');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_filter'] = $this->language->get('entry_filter');
		$this->data['entry_related'] = $this->language->get('entry_related');
		$this->data['entry_attribute'] = $this->language->get('entry_attribute');
		$this->data['entry_text'] = $this->language->get('entry_text');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_option_value'] = $this->language->get('entry_option_value');
		$this->data['entry_required'] = $this->language->get('entry_required');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_priority'] = $this->language->get('entry_priority');
		$this->data['entry_tag'] = $this->language->get('entry_tag');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_reward'] = $this->language->get('entry_reward');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_main_category'] = $this->language->get('entry_main_category');
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_profile'] = $this->language->get('entry_profile');
		$this->data['entry_percent'] = $this->language->get('entry_percent');

		$this->data['text_recurring_help'] = $this->language->get('text_recurring_help');
		$this->data['text_recurring_title'] = $this->language->get('text_recurring_title');
		$this->data['text_recurring_trial'] = $this->language->get('text_recurring_trial');
		$this->data['entry_recurring'] = $this->language->get('entry_recurring');
		$this->data['entry_recurring_price'] = $this->language->get('entry_recurring_price');
		$this->data['entry_recurring_freq'] = $this->language->get('entry_recurring_freq');
		$this->data['entry_recurring_cycle'] = $this->language->get('entry_recurring_cycle');
		$this->data['entry_recurring_length'] = $this->language->get('entry_recurring_length');
		$this->data['entry_trial'] = $this->language->get('entry_trial');
		$this->data['entry_trial_price'] = $this->language->get('entry_trial_price');
		$this->data['entry_trial_freq'] = $this->language->get('entry_trial_freq');
		$this->data['entry_trial_length'] = $this->language->get('entry_trial_length');
		$this->data['entry_trial_cycle'] = $this->language->get('entry_trial_cycle');

		$this->data['text_length_day'] = $this->language->get('text_length_day');
		$this->data['text_length_week'] = $this->language->get('text_length_week');
		$this->data['text_length_month'] = $this->language->get('text_length_month');
		$this->data['text_length_month_semi'] = $this->language->get('text_length_month_semi');
		$this->data['text_length_year'] = $this->language->get('text_length_year');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_attribute'] = $this->language->get('button_add_attribute');
		$this->data['button_add_option'] = $this->language->get('button_add_option');
		$this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
		$this->data['button_add_discount'] = $this->language->get('button_add_discount');
		$this->data['button_add_special'] = $this->language->get('button_add_special');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_add_profile'] = $this->language->get('button_add_profile');
		$this->data['text_additional_offer'] = $this->language->get('text_additional_offer');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_attribute'] = $this->language->get('tab_attribute');
		$this->data['tab_option'] = $this->language->get('tab_option');		
		$this->data['tab_product_option'] = $this->language->get('tab_product_option');
		$this->data['tab_profile'] = $this->language->get('tab_profile');
		$this->data['tab_discount'] = $this->language->get('tab_discount');
		$this->data['tab_special'] = $this->language->get('tab_special');
		$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['tab_links'] = $this->language->get('tab_links');
		$this->data['tab_reward'] = $this->language->get('tab_reward');
		$this->data['tab_design'] = $this->language->get('tab_design');
		$this->data['tab_additional_offer'] = $this->language->get('tab_additional_offer');
		$this->data['button_add_ao'] = $this->language->get('button_add_ao');
		$this->data['tab_marketplace_links'] = $this->language->get('tab_marketplace_links');

		/* FAproduct */
		$this->load->language('common/facommon');
		$this->data['show_fa_in_product'] = $this->language->get('product_groups');
		$this->data['show_group'] = $this->language->get('show_group');
		/* FAproduct */

		$this->data['tab_ocfilter'] = $this->language->get('tab_ocfilter');
		$this->data['entry_values'] = $this->language->get('entry_values');
		$this->data['ocfilter_select_category'] = $this->language->get('ocfilter_select_category');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = [];
		}

		if (isset($this->error['seo_title'])) {
			$this->data['error_seo_title'] = $this->error['seo_title'];
		} else {
			$this->data['error_seo_title'] = [];
		}

		if (isset($this->error['seo_h1'])) {
			$this->data['error_seo_h1'] = $this->error['seo_h1'];
		} else {
			$this->data['error_seo_h1'] = [];
		}	

		if (isset($this->error['meta_description'])) {
			$this->data['error_meta_description'] = $this->error['meta_description'];
		} else {
			$this->data['error_meta_description'] = [];
		}		

		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = [];
		}	

		if (isset($this->error['model'])) {
			$this->data['error_model'] = $this->error['model'];
		} else {
			$this->data['error_model'] = '';
		}		

		if (isset($this->error['date_available'])) {
			$this->data['error_date_available'] = $this->error['date_available'];
		} else {
			$this->data['error_date_available'] = '';
		}	

		$url = '';


		if ($this->config->get('admin_quick_edit_status') && $this->config->get('aqe_catalog_products_status')) {
			foreach($this->config->get('aqe_catalog_products') as $column => $attr) {
				if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
					$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
				}
			}
			if (isset($this->request->get['filter_sub_category'])) {
				$url .= '&filter_sub_category=' . urlencode(html_entity_decode($this->request->get['filter_sub_category'], ENT_QUOTES, 'UTF-8'));
			}
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . urlencode(html_entity_decode($this->request->get['filter_price'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . urlencode(html_entity_decode($this->request->get['filter_quantity'], ENT_QUOTES, 'UTF-8'));
		}	

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . urlencode(html_entity_decode($this->request->get['sort'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . urlencode(html_entity_decode($this->request->get['order'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . urlencode(html_entity_decode($this->request->get['page'], ENT_QUOTES, 'UTF-8'));
		}

		$this->data['breadcrumbs'] = [];

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['product_id'])) {
			$this->data['action'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/product_ext', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
		}

		$this->data['product_id'] = isset($this->request->get['product_id'])?$this->request->get['product_id']:false;

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if ($this->config->get('ukrcredits_status')) {
			$this->data['ukrcredits_status'] 	= true;
			$this->data['tab_ukrcredits'] 		= $this->language->get('tab_ukrcredits');
			$this->data['credit_type'] 			= $this->language->get('credit_type');
			$this->data['credit_type_ii'] 		= $this->language->get('credit_type_ii');
			$this->data['credit_type_pp'] 		= $this->language->get('credit_type_pp');
			$this->data['credit_type_mb'] 		= $this->language->get('credit_type_mb');
			$this->data['partsCount'] 			= $this->language->get('partsCount');
			$this->data['markup'] 				= $this->language->get('markup');
			$this->data['help_partsCount'] 		= $this->language->get('help_partsCount');
			$this->data['help_markup'] 			= $this->language->get('help_markup');			
		
            if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
                $credit_info = $this->model_catalog_product->getProductUkrcredits($this->request->get['product_id']);
            }

			$this->data['partscounts'] = array();
			for($i=24; $i>=1; $i--){
				$this->data['partscounts'][] = $i;           
			}
			
			if (isset($this->request->post['product_pp'])) {
				$this->data['product_pp'] = $this->request->post['product_pp'];
			} elseif (!empty($credit_info)) {
				$this->data['product_pp'] = $credit_info['product_pp'];
			} else {
				$this->data['product_pp'] = '';
			}

			if (isset($this->request->post['product_ii'])) {
				$this->data['product_ii'] = $this->request->post['product_ii'];
			} elseif (!empty($credit_info)) {
				$this->data['product_ii'] = $credit_info['product_ii'];
			} else {
				$this->data['product_ii'] = '';
			}
			
			if (isset($this->request->post['product_mb'])) {
				$this->data['product_mb'] = $this->request->post['product_mb'];
			} elseif (!empty($credit_info)) {
				$this->data['product_mb'] = $credit_info['product_mb'];
			} else {
				$this->data['product_mb'] = '';
			}
			
			if (isset($this->request->post['partscount_pp'])) {
				$this->data['partscount_pp'] = $this->request->post['partscount_pp'];
			} elseif (!empty($credit_info)) {
				$this->data['partscount_pp'] = $credit_info['partscount_pp'];
			} else {
				$this->data['partscount_pp'] = '';
			}
			
			if (isset($this->request->post['partscount_ii'])) {
				$this->data['partscount_ii'] = $this->request->post['partscount_ii'];
			} elseif (!empty($credit_info)) {
				$this->data['partscount_ii'] = $credit_info['partscount_ii'];
			} else {
				$this->data['partscount_ii'] = '';
			}
			
			if (isset($this->request->post['partscount_mb'])) {
				$this->data['partscount_mb'] = $this->request->post['partscount_mb'];
			} elseif (!empty($credit_info)) {
				$this->data['partscount_mb'] = $credit_info['partscount_mb'];
			} else {
				$this->data['partscount_mb'] = '';
			}

			if (isset($this->request->post['markup_pp'])) {
				$this->data['markup_pp'] = $this->request->post['markup_pp'];
			} elseif (!empty($credit_info)) {
				$this->data['markup_pp'] = $credit_info['markup_pp'];
			} else {
				$this->data['markup_pp'] = '';
			}
			
			if (isset($this->request->post['markup_ii'])) {
				$this->data['markup_ii'] = $this->request->post['markup_ii'];
			} elseif (!empty($credit_info)) {
				$this->data['markup_ii'] = $credit_info['markup_ii'];
			} else {
				$this->data['markup_ii'] = '';
			}

			if (isset($this->request->post['markup_mb'])) {
				$this->data['markup_mb'] = $this->request->post['markup_mb'];
			} elseif (!empty($credit_info)) {
				$this->data['markup_mb'] = $credit_info['markup_mb'];
			} else {
				$this->data['markup_mb'] = '';
			}			
		} else {
			$this->data['ukrcredits_status'] = false;
		}

		if (isset($this->request->post['product_description'])) {
			$this->data['product_description'] = $this->request->post['product_description'];
		} elseif (isset($this->request->get['product_id'])) {
			$this->data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);
		} else {
			$this->data['product_description'] = [];
		}

		if (isset($this->request->post['model'])) {
			$this->data['model'] = $this->request->post['model'];
		} elseif (!empty($product_info)) {
			$this->data['model'] = $product_info['model'];
		} else {
			$this->data['model'] = '';
		}

		if (isset($this->request->post['short_name'])) {
			$this->data['short_name'] = $this->request->post['short_name'];
		} elseif (!empty($product_info)) {
			$this->data['short_name'] = $product_info['short_name'];
		} else {
			$this->data['short_name'] = '';
		}

		if (isset($this->request->post['short_name_de'])) {
			$this->data['short_name_de'] = $this->request->post['short_name_de'];
		} elseif (!empty($product_info)) {
			$this->data['short_name_de'] = $product_info['short_name_de'];
		} else {
			$this->data['short_name_de'] = '';
		}

		if (isset($this->request->post['sku'])) {
			$this->data['sku'] = $this->request->post['sku'];
		} elseif (!empty($product_info)) {
			$this->data['sku'] = $product_info['sku'];
		} else {
			$this->data['sku'] = '';
		}

		if (isset($this->request->post['upc'])) {
			$this->data['upc'] = $this->request->post['upc'];
		} elseif (!empty($product_info)) {
			$this->data['upc'] = $product_info['upc'];
		} else {
			$this->data['upc'] = '';
		}

		if (isset($this->request->post['ean'])) {
			$this->data['ean'] = $this->request->post['ean'];
		} elseif (!empty($product_info)) {
			$this->data['ean'] = $product_info['ean'];
		} else {
			$this->data['ean'] = '';
		}

		if (isset($this->request->post['jan'])) {
			$this->data['jan'] = $this->request->post['jan'];
		} elseif (!empty($product_info)) {
			$this->data['jan'] = $product_info['jan'];
		} else {
			$this->data['jan'] = '';
		}

		if (isset($this->request->post['isbn'])) {
			$this->data['isbn'] = $this->request->post['isbn'];
		} elseif (!empty($product_info)) {
			$this->data['isbn'] = $product_info['isbn'];
		} else {
			$this->data['isbn'] = '';
		}

		if (isset($this->request->post['color_group'])) {
			$this->data['color_group'] = $this->request->post['color_group'];
		} elseif (!empty($product_info)) {
			$this->data['color_group'] = $product_info['color_group'];
		} else {
			$this->data['color_group'] = '';
		}

		if ($this->data['color_group']){
			$color_grouped_products = $this->model_catalog_product->getColorGroupedProducts($this->request->get['product_id'], $this->data['color_group']);

			$this->data['color_grouped_products'] = [];
			if ($color_grouped_products) {

				foreach ($color_grouped_products as $_cgproduct){
					$_cgrealproduct = $this->model_catalog_product->getProduct($_cgproduct['product_id']);

					$this->data['color_grouped_products'][] = array(
						'image' => $this->model_tool_image->resize($_cgrealproduct['image'], 100, 100),
						'name' => $_cgrealproduct['name'],
						'this' => ($_cgrealproduct['product_id'] == $this->request->get['product_id']),
						'action' => 'index.php?route=catalog/product/update&token=' . $this->session->data['token'].'&product_id='.$_cgrealproduct['product_id']
					);	

				}

			}

		}


		if (isset($this->request->post['mpn'])) {
			$this->data['mpn'] = $this->request->post['mpn'];
		} elseif (!empty($product_info)) {
			$this->data['mpn'] = $product_info['mpn'];
		} else {
			$this->data['mpn'] = '';
		}


		if (isset($this->request->post['asin'])) {
			$this->data['asin'] = $this->request->post['asin'];
		} elseif (!empty($product_info)) {
			$this->data['asin'] = $product_info['asin'];
		} else {
			$this->data['asin'] = '';
		}

		if (isset($this->request->post['old_asin'])) {
			$this->data['old_asin'] = $this->request->post['old_asin'];
		} elseif (!empty($product_info)) {
			$this->data['old_asin'] = $product_info['old_asin'];
		} else {
			$this->data['old_asin'] = '';
		}

		if (isset($this->request->post['amzn_last_search'])) {
			$this->data['amzn_last_search'] = $this->request->post['amzn_last_search'];
		} elseif (!empty($product_info)) {
			$this->data['amzn_last_search'] = $product_info['amzn_last_search'];
		} else {
			$this->data['amzn_last_search'] = '';
		}

		if (isset($this->request->post['amzn_last_offers'])) {
			$this->data['amzn_last_offers'] = $this->request->post['amzn_last_offers'];
		} elseif (!empty($product_info)) {
			$this->data['amzn_last_offers'] = $product_info['amzn_last_offers'];
		} else {
			$this->data['amzn_last_offers'] = '';
		}

		if (isset($this->request->post['added_from_amazon'])) {
			$this->data['added_from_amazon'] = $this->request->post['added_from_amazon'];
		} elseif (!empty($product_info)) {
			$this->data['added_from_amazon'] = $product_info['added_from_amazon'];
		} else {
			$this->data['added_from_amazon'] = 0;
		}

		if (isset($this->request->post['fill_from_amazon'])) {
			$this->data['fill_from_amazon'] = $this->request->post['fill_from_amazon'];
		} elseif (!empty($product_info)) {
			$this->data['fill_from_amazon'] = $product_info['fill_from_amazon'];
		} else {
			$this->data['fill_from_amazon'] = 0;
		}

			//Данные амазона
		if ($this->data['asin']){
			$this->load->model('kp/product');
			
			$this->data['amazon_product_link'] = $product_info['amazon_product_link'];
			$this->data['filled_from_amazon'] = $product_info['filled_from_amazon'];
			$this->data['description_filled_from_amazon'] = $product_info['description_filled_from_amazon'];

			$amazon_product_data = $this->model_kp_product->getProductAmazonFullData($this->data['asin']);
			if ($this->config->get('config_enable_amazon_asin_file_cache')){	
				if ($amazon_product_data && file_exists(DIR_CACHE . $amazon_product_data['file'])){
					$this->data['amazon_product_json'] = HTTPS_CATALOG . 'system/' . DIR_CACHE_NAME . $amazon_product_data['file'];				
				}
			} else {
				$this->data['amazon_product_json'] = 'IN DB';
			}

			$this->data['offers'] = [];
			$offers = $this->model_kp_product->getProductAmazonOffers($this->data['asin']);

			foreach ($offers as $offer){
				if ($offer['conditionIsNew']){
					$this->data['offers'][] = [					
						'seller' 				=> $offer['sellerName'],
						'prime'	 				=> $offer['isPrime'],
						'buybox_winner'	 		=> $offer['isBuyBoxWinner'],
						'is_best'				=> $offer['isBestOffer'],
						'offer_rating'			=> $offer['offerRating'],
						'supplier'				=> $this->rainforestAmazon->offersParser->Suppliers->getSupplier($offer['sellerName']),
						'price'	 				=> $this->currency->format_with_left($offer['priceAmount'], $offer['priceCurrency'], 1),
						'delivery'	 			=> $this->currency->format_with_left($offer['deliveryAmount'], $offer['deliveryCurrency'], 1),	
						'total'					=> $this->currency->format_with_left($offer['priceAmount'] + $offer['deliveryAmount'], $offer['priceCurrency'], 1),
						'delivery_fba' 			=> $offer['deliveryIsFba'],
						'delivery_comment' 		=> $offer['deliveryComments'],
						'min_days' 				=> $offer['minDays'],
						'delivery_from' 		=> $offer['deliveryFrom'],
						'delivery_to' 			=> $offer['deliveryTo'],
						'is_min_price'			=> $offer['is_min_price'],							
						'reviews'				=> (int)$offer['sellerRatingsTotal'],
						'rating'				=> (int)$offer['sellerRating50'] / 10,
						'positive'				=> (int)$offer['sellerPositiveRatings100'],
						'date_added'			=> date('Y-m-d H:i:s', strtotime($offer['date_added'])),
						'link'					=> $offer['sellerLink']?$offer['sellerLink']:$this->rainforestAmazon->createLinkToAmazonSearchPage($this->data['asin']),
					];
				}
			}

			$this->data['amazon_best_price'] = $this->currency->format($product_info['amazon_best_price'], 'EUR', 1);
			$this->data['amazon_lowest_price'] = $this->currency->format($product_info['amazon_lowest_price'], 'EUR', 1);
			$this->data['amzn_no_offers_counter'] = $product_info['amzn_no_offers_counter'];

		}				

		if (isset($this->request->post['amzn_not_found'])) {
			$this->data['amzn_not_found'] = $this->request->post['amzn_not_found'];
		} elseif (!empty($product_info)) {
			$this->data['amzn_not_found'] = $product_info['amzn_not_found'];
		} else {
			$this->data['amzn_not_found'] = false;
		}	

		if (isset($this->request->post['amzn_no_offers'])) {
			$this->data['amzn_no_offers'] = $this->request->post['amzn_no_offers'];
		} elseif (!empty($product_info)) {
			$this->data['amzn_no_offers'] = $product_info['amzn_no_offers'];
		} else {
			$this->data['amzn_no_offers'] = false;
		}	

		if (isset($this->request->post['amzn_ignore'])) {
			$this->data['amzn_ignore'] = $this->request->post['amzn_ignore'];
		} elseif (!empty($product_info)) {
			$this->data['amzn_ignore'] = $product_info['amzn_ignore'];
		} else {
			$this->data['amzn_ignore'] = false;
		}				


		$this->load->model('setting/store');			
		$this->data['stores'] = $this->model_setting_store->getStores();

		$this->data['priceva'] = [];
		$this->data['front'] = [];
		if (!empty($this->request->get['product_id'])){

			loadAndRenameCatalogModels('model/catalog/product.php', 'ModelCatalogProduct', 'ModelCatalogProductCatalog');
			$this->modelCatalogProduct = new ModelCatalogProductCatalog($this->registry);

			foreach ($this->data['stores'] as $store){
				if ($this->config->get('config_priceva_api_key_' . $store['store_id'])){
					$this->load->model('setting/setting');
					$catalogCurrency = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', (int)$store['store_id']);

					$this->data['front'][$store['name']] = [];
					$catalogProduct = $this->modelCatalogProduct->getUncachedProductForStore($this->request->get['product_id'], $store['store_id']);

					$catalogProduct['price'] = $this->currency->convert($catalogProduct['price'], $this->config->get('config_currency'), $catalogCurrency);
					$catalogProduct['special'] = $this->currency->convert($catalogProduct['special'], $this->config->get('config_currency'), $catalogCurrency);

					$this->data['front'][$store['name']]['price'] = $this->currency->format_with_left($catalogProduct['price'], $catalogCurrency, 1);
					$this->data['front'][$store['name']]['special'] = $this->currency->format_with_left($catalogProduct['special'], $catalogCurrency, 1);

					$this->model_setting_setting->loadSettings(0);

					$product_priceva_data = $this->pricevaAdaptor->getPricevaData($store['store_id'], $this->request->get['product_id']);

					$this->data['priceva'][$store['name']] = [];

					$idx = 0;
					foreach ($product_priceva_data as $product_priceva_line){						

						$this->data['priceva'][$store['name']][$idx] = $product_priceva_line;
						$this->data['priceva'][$store['name']][$idx]['price'] = $this->currency->format_with_left($product_priceva_line['price'], $product_priceva_line['currency'], 1);
						$this->data['priceva'][$store['name']][$idx]['discount'] = $this->currency->format_with_left($product_priceva_line['discount'], $product_priceva_line['discount'], 1);

						$this->data['priceva'][$store['name']][$idx]['relevance_status'] = $this->pricevaAdaptor->getPricevaRelevance()[$product_priceva_line['relevance_status']];

						$idx++;
					}
				}
			}
		}

		if (isset($this->request->post['tnved'])) {
			$this->data['tnved'] = $this->request->post['tnved'];
		} elseif (!empty($product_info)) {
			$this->data['tnved'] = $product_info['tnved'];
		} else {
			$this->data['tnved'] = '';
		}

		if (isset($this->request->post['location'])) {
			$this->data['location'] = $this->request->post['location'];
		} elseif (!empty($product_info)) {
			$this->data['location'] = $product_info['location'];
		} else {
			$this->data['location'] = '';
		}

		if (isset($this->request->post['source'])) {
			$this->data['source'] = $this->request->post['source'];
		} elseif (!empty($product_info)) {
			$this->data['source'] = $product_info['source'];
		} else {
			$this->data['source'] = '';
		}

		if (isset($this->request->post['competitors'])) {
			$this->data['competitors'] = $this->request->post['competitors'];
		} elseif (!empty($product_info)) {
			$this->data['competitors'] = $product_info['competitors'];
		} else {
			$this->data['competitors'] = '';
		}

		if (isset($this->request->post['competitors_ua'])) {
			$this->data['competitors_ua'] = $this->request->post['competitors_ua'];
		} elseif (!empty($product_info)) {
			$this->data['competitors_ua'] = $product_info['competitors_ua'];
		} else {
			$this->data['competitors_ua'] = '';
		}

		if (isset($this->request->post['ignore_parse'])) {
			$this->data['ignore_parse'] = $this->request->post['ignore_parse'];
		} elseif (!empty($product_info)) {
			$this->data['ignore_parse'] = $product_info['ignore_parse'];
		} else {
			$this->data['ignore_parse'] = '';
		}

		if (isset($this->request->post['big_business'])) {
			$this->data['big_business'] = $this->request->post['big_business'];
		} elseif (!empty($product_info)) {
			$this->data['big_business'] = $product_info['big_business'];
		} else {
			$this->data['big_business'] = '';
		}

		if (isset($this->request->post['new'])) {
			$this->data['new'] = $this->request->post['new'];
		} elseif (!empty($product_info)) {
			$this->data['new'] = $product_info['new'];
		} else {
			$this->data['new'] = '';
		}

		if (isset($this->request->post['ignore_parse_date_to'])) {
			$this->data['ignore_parse_date_to'] = $this->request->post['ignore_parse_date_to'];
		} elseif (!empty($product_info)) {
			$this->data['ignore_parse_date_to'] = $product_info['ignore_parse_date_to'];
		} else {
			$this->data['ignore_parse_date_to'] = date('Y-m-d', time() - 86400);;
		}

		if (isset($this->request->post['new_date_to'])) {
			$this->data['new_date_to'] = $this->request->post['new_date_to'];
		} elseif (!empty($product_info)) {
			$this->data['new_date_to'] = $product_info['new_date_to'];
		} else {
			$this->data['new_date_to'] = date('Y-m-d', time() - 86400);;
		}

		if (isset($this->request->post['is_option_with_id'])) {
			$this->data['is_option_with_id'] = $this->request->post['is_option_with_id'];
		} elseif (!empty($product_info)) {
			$this->data['is_option_with_id'] = $product_info['is_option_with_id'];
		} else {
			$this->data['is_option_with_id'] = '';
		}


		if (isset($this->request->post['product_store'])) {
			$this->data['product_store'] = $this->request->post['product_store'];
		} elseif (isset($this->request->get['product_id'])) {
			$this->data['product_store'] = $this->model_catalog_product->getProductStores($this->request->get['product_id']);
		} else {
			$this->data['product_store'] = array(0);
		}	

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($product_info)) {
			$this->data['keyword'] = $this->model_catalog_product->getKeyWords($this->request->get['product_id']);
		} else {
			$this->data['keyword'] = [];
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($product_info)) {
			$this->data['image'] = $product_info['image'];
		} else {
			$this->data['image'] = '';
		}


		if (isset($this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($product_info) && $product_info['image']) {
			$this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		/*Additional offer*/
		$this->data['product_additional_offer'] = [];
		if (isset($this->request->post['product_additional_offer'])) {
			$product_additional_offer = $this->request->post['product_additional_offer'];
			foreach($product_additional_offer as $key=>$product_ao) {

				if(isset($product_ao['ao_product_id'])) {
					$ao_product = $this->model_catalog_product->getProduct($product_ao['ao_product_id']);
					if(!empty($ao_product)) {
						$this->data['product_additional_offer'][$key]['product_ao_name'] = $ao_product['name'];   
					} else {
						$this->data['product_additional_offer'][$key]['product_ao_name'] = '';  
					}
				} else {
					$this->data['product_additional_offer'][$key]['product_ao_name'] = '';  
				}

				$this->data['product_additional_offer'][$key]['product_additional_offer_id'] = $product_ao['product_additional_offer_id'] ? $product_ao['product_additional_offer_id'] : 0;
				$this->data['product_additional_offer'][$key]['customer_group_id'] = $product_ao['customer_group_id'] ? $product_ao['customer_group_id'] : 0;
				$this->data['product_additional_offer'][$key]['priority'] = $product_ao['priority'] ? $product_ao['priority'] : '';
				$this->data['product_additional_offer'][$key]['quantity'] = $product_ao['quantity'] ? $product_ao['quantity'] : 1;
				$this->data['product_additional_offer'][$key]['ao_product_id'] = $product_ao['ao_product_id'] ? $product_ao['ao_product_id'] : '';
				$this->data['product_additional_offer'][$key]['price'] = $product_ao['price'];
				$this->data['product_additional_offer'][$key]['percent'] = $product_ao['percent'];
				$this->data['product_additional_offer'][$key]['date_start'] = $product_ao['date_start'];
				$this->data['product_additional_offer'][$key]['date_end'] = $product_ao['date_end'];
				$this->data['product_additional_offer'][$key]['ao_group'] = $product_ao['ao_group'];

				$this->data['product_additional_offer'][$key]['store_id'] = $product_ao['store_id'];

				$this->data['product_additional_offer'][$key]['image'] = $product_ao['image'] ? $product_ao['image'] : 'no_image.jpg';

				$img_ao = $product_ao['image'] ? $product_ao['image'] : 'no_image.jpg';
				$this->data['product_additional_offer'][$key]['thumb'] = $this->model_tool_image->resize($img_ao, 100, 100);

				$this->data['product_additional_offer'][$key]['description'] = $product_ao['description'];

			}

		} elseif (isset($this->request->get['product_id'])) {
			$product_additional_offer = $this->model_catalog_product->getProductAdditionalOffer($this->request->get['product_id']);

			foreach($product_additional_offer as $key => $product_ao) {

				if(isset($product_ao['ao_product_id'])) {
					$ao_product = $this->model_catalog_product->getProduct($product_ao['ao_product_id']);
					if(!empty($ao_product)) {
						$this->data['product_additional_offer'][$key]['product_ao_name'] = $ao_product['name'];   
					} else {
						$this->data['product_additional_offer'][$key]['product_ao_name'] = '';  
					}
				} else {
					$this->data['product_additional_offer'][$key]['product_ao_name'] = '';  
				}

				$this->data['product_additional_offer'][$key]['product_additional_offer_id'] = $product_ao['product_additional_offer_id'] ? $product_ao['product_additional_offer_id'] : 0;
				$this->data['product_additional_offer'][$key]['customer_group_id'] = $product_ao['customer_group_id'] ? $product_ao['customer_group_id'] : 0;
				$this->data['product_additional_offer'][$key]['priority'] = $product_ao['priority'] ? $product_ao['priority'] : '';
				$this->data['product_additional_offer'][$key]['quantity'] = $product_ao['quantity'] ? $product_ao['quantity'] : 1;
				$this->data['product_additional_offer'][$key]['ao_product_id'] = $product_ao['ao_product_id'] ? $product_ao['ao_product_id'] : '';
				$this->data['product_additional_offer'][$key]['price'] = $product_ao['price'];
				$this->data['product_additional_offer'][$key]['percent'] = $product_ao['percent'];
				$this->data['product_additional_offer'][$key]['date_start'] = $product_ao['date_start'];
				$this->data['product_additional_offer'][$key]['date_end'] = $product_ao['date_end'];
				$this->data['product_additional_offer'][$key]['ao_group'] = $product_ao['ao_group'];

				$this->data['product_additional_offer'][$key]['store_id'] = $this->model_catalog_product->getProductAdditionalOfferStoresByAOID($product_ao['product_additional_offer_id']);

				$this->data['product_additional_offer'][$key]['image'] = $product_ao['image'] ? $product_ao['image'] : 'no_image.jpg';

				$img_ao = $product_ao['image'] ? $product_ao['image'] : 'no_image.jpg';
				$this->data['product_additional_offer'][$key]['thumb'] = $this->model_tool_image->resize($img_ao, 100, 100);

				$this->data['product_additional_offer'][$key]['description'] = $product_ao['description'];

			}

		} else {
			$this->data['product_additional_offer'] = [];
		}

		if (isset($this->request->post['shipping'])) {
			$this->data['shipping'] = $this->request->post['shipping'];
		} elseif (!empty($product_info)) {
			$this->data['shipping'] = $product_info['shipping'];
		} else {
			$this->data['shipping'] = 1;
		}

		if (isset($this->request->post['price'])) {
			$this->data['price'] = $this->request->post['price'];
		} elseif (!empty($product_info)) {
			$this->data['price'] = $product_info['price'];
		} else {
			$this->data['price'] = '';
		}

		if (isset($this->request->post['price_delayed'])) {
			$this->data['price_delayed'] = $this->request->post['price_delayed'];
		} elseif (!empty($product_info)) {
			$this->data['price_delayed'] = $product_info['price_delayed'];
		} else {
			$this->data['price_delayed'] = '';
		}

		if (isset($this->request->post['mpp_price'])) {
			$this->data['mpp_price'] = $this->request->post['mpp_price'];
		} elseif (!empty($product_info)) {
			$this->data['mpp_price'] = $product_info['mpp_price'];
		} else {
			$this->data['mpp_price'] = '';
		}

		if (isset($this->request->post['yam_price'])) {
			$this->data['yam_price'] = $this->request->post['yam_price'];
		} elseif (!empty($product_info)) {
			$this->data['yam_price'] = $product_info['yam_price'];
		} else {
			$this->data['yam_price'] = '';
		}

		if (isset($this->request->post['yam_special'])) {
			$this->data['yam_special'] = $this->request->post['yam_special'];
		} elseif (!empty($product_info)) {
			$this->data['yam_special'] = $product_info['yam_special'];
		} else {
			$this->data['yam_special'] = '';
		}

		if (isset($this->request->post['yam_special_percent'])) {
			$this->data['yam_special_percent'] = $this->request->post['yam_special_percent'];
		} elseif (!empty($product_info)) {
			$this->data['yam_special_percent'] = $product_info['yam_special_percent'];
		} else {
			$this->data['yam_special_percent'] = '';
		}

		if (isset($this->request->post['yam_percent'])) {
			$this->data['yam_percent'] = $this->request->post['yam_percent'];
		} elseif (!empty($product_info)) {
			$this->data['yam_percent'] = $product_info['yam_percent'];
		} else {
			$this->data['yam_percent'] = '';
		}

		if (isset($this->request->post['yam_currency'])) {
			$this->data['yam_currency'] = $this->request->post['yam_currency'];
		} elseif (!empty($product_info)) {
			$this->data['yam_currency'] = $product_info['yam_currency'];
		} else {
			$this->data['yam_currency'] = '';
		}

		if (isset($this->request->get['product_id'])) {
			$this->data['yam_product_id'] = $this->config->get('config_yam_offer_id_prefix') . $this->request->get['product_id'];
		} elseif (!empty($product_info)){
			$this->data['yam_product_id'] = $this->config->get('config_yam_offer_id_prefix') . $product_info['product_id'];
		} else {
			$this->data['yam_product_id'] = '';
		}

			//get overload prices
		if (isset($this->request->post['product_price_national_to_yam'])) {
			$this->data['product_price_national_to_yam'] = $this->request->post['product_price_national_to_yam'];
		} elseif (!empty($product_info)) {
			$this->data['product_price_national_to_yam'] = $this->model_catalog_product->getProductStorePricesYam($this->request->get['product_id']);
		} else {
			$this->data['product_price_national_to_yam'] = [];
		}

			//get overload prices
		if (isset($this->request->post['product_price_to_store'])) {
			$this->data['product_price_to_store'] = $this->request->post['product_price_to_store'];
		} elseif (!empty($product_info)) {
			$this->data['product_price_to_store'] = $this->model_catalog_product->getProductStorePrices($this->request->get['product_id']);
		} else {
			$this->data['product_price_to_store'] = [];
		}

			//get overload prices national
		if (isset($this->request->post['product_price_national_to_store'])) {
			$this->data['product_price_national_to_store'] = $this->request->post['product_price_national_to_store'];
		} elseif (!empty($product_info)) {
			$this->data['product_price_national_to_store'] = $this->model_catalog_product->getProductStorePricesNational($this->request->get['product_id']);
		} else {
			$this->data['product_price_national_to_store'] = [];
		}

			//get overload prices national
		if (isset($this->request->post['product_stock_limits'])) {
			$this->data['product_stock_limits'] = $this->request->post['product_stock_limits'];
		} elseif (!empty($product_info)) {
			$this->data['product_stock_limits'] = $this->model_catalog_product->getProductStockLimits($this->request->get['product_id']);
		} else {
			$this->data['product_stock_limits'] = [];
		}

		if (isset($this->request->post['special_cost'])) {
			$this->data['special_cost'] = $this->request->post['special_cost'];
		} elseif (!empty($product_info)) {
			$this->data['special_cost'] = $product_info['special_cost'];
		} else {
			$this->data['special_cost'] = '';
		}

		$this->data['parser_price'] = !empty($product_info['parser_price'])?$product_info['parser_price']:false;
		$this->data['parser_special_price'] = !empty($product_info['parser_special_price'])?$product_info['parser_special_price']:false;

		if (isset($this->request->post['min_buy'])) {
			$this->data['min_buy'] = $this->request->post['min_buy'];
		} elseif (!empty($product_info)) {
			$this->data['min_buy'] = $product_info['min_buy'];
		} else {
			$this->data['min_buy'] = '';
		}

		if (isset($this->request->post['max_buy'])) {
			$this->data['max_buy'] = $this->request->post['max_buy'];
		} elseif (!empty($product_info)) {
			$this->data['max_buy'] = $product_info['max_buy'];
		} else {
			$this->data['max_buy'] = '';
		}

		$stockFieldsFast = [
			'quantity_stock','quantity_stockM','quantity_stockK','quantity_stockMN','quantity_stockAS'
		];

		foreach ($stockFieldsFast as $stockField){
			if (isset($this->request->post[$stockField])) {
				$this->data[$stockField] = $this->request->post[$stockField];
			} elseif (!empty($product_info)) {
				$this->data[$stockField] = $product_info[$stockField];
			} else {
				$this->data[$stockField] = '';
			}	

			if (isset($this->request->post[$stockField . '_onway'])) {
				$this->data[$stockField . '_onway'] = $this->request->post[$stockField . '_onway'];
			} elseif (!empty($product_info)) {
				$this->data[$stockField . '_onway'] = $product_info[$stockField . '_onway'];
			} else {
				$this->data[$stockField . '_onway'] = '';
			}		
		}	

		if (isset($this->request->post['cost'])) {
			$this->data['cost'] = $this->request->post['cost'];
		} elseif (!empty($product_info)) {
			$this->data['cost'] = $product_info['cost'];
		} else {
			$this->data['cost'] = '';
		}

		if (isset($this->request->post['costprice'])) {
			$this->data['costprice'] = $this->request->post['costprice'];
		} elseif (!empty($product_info)) {
			$this->data['costprice'] = $product_info['costprice'];
		} else {
			$this->data['costprice'] = '';
		}

		if (isset($this->request->post['price_national'])) {
			$this->data['price_national'] = $this->request->post['price_national'];
		} elseif (!empty($product_info)) {
			$this->data['price_national'] = $product_info['price_national'];
		} else {
			$this->data['price_national'] = '';
		}

		if (isset($this->request->post['currency'])) {
			$this->data['currency'] = $this->request->post['currency'];
		} elseif (!empty($product_info)) {
			$this->data['currency'] = $product_info['currency'];
		} else {
			$this->data['currency'] = '';
		}

		$this->load->model('localisation/currency');
		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$this->load->model('catalog/profile');

		$this->data['profiles'] = $this->model_catalog_profile->getProfiles();

		if (isset($this->request->post['product_profiles'])) {
			$this->data['product_profiles'] = $this->request->post['product_profiles'];
		} elseif (!empty($product_info)) {
			$this->data['product_profiles'] = $this->model_catalog_product->getProfiles($product_info['product_id']);
		} else {
			$this->data['product_profiles'] = [];
		}

		$this->load->model('localisation/tax_class');

		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['tax_class_id'])) {
			$this->data['tax_class_id'] = $this->request->post['tax_class_id'];
		} elseif (!empty($product_info)) {
			$this->data['tax_class_id'] = $product_info['tax_class_id'];
		} else {
			$this->data['tax_class_id'] = 0;
		}

		if (isset($this->request->post['date_available'])) {
			$this->data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($product_info)) {
			$this->data['date_available'] = date('Y-m-d', strtotime($product_info['date_available']));
		} else {
			$this->data['date_available'] = date('Y-m-d', time() - 86400);
		}

		if (isset($this->request->post['date_added'])) {
			$this->data['date_added'] = $this->request->post['date_added'];
		} elseif (!empty($product_info)) {
			$this->data['date_added'] = date('Y-m-d', strtotime($product_info['date_added']));
		} else {
			$this->data['date_added'] = date('Y-m-d', time() - 86400);
		}

		if (isset($this->request->post['quantity'])) {
			$this->data['quantity'] = $this->request->post['quantity'];
		} elseif (!empty($product_info)) {
			$this->data['quantity'] = $product_info['quantity'];
		} else {
			$this->data['quantity'] = 1;
		}

		if (isset($this->request->post['minimum'])) {
			$this->data['minimum'] = $this->request->post['minimum'];
		} elseif (!empty($product_info)) {
			$this->data['minimum'] = $product_info['minimum'];
		} else {
			$this->data['minimum'] = 1;
		}

		if (isset($this->request->post['package'])) {
			$this->data['package'] = $this->request->post['package'];
		} elseif (!empty($product_info)) {
			$this->data['package'] = $product_info['package'];
		} else {
			$this->data['package'] = 1;
		}

		if (isset($this->request->post['subtract'])) {
			$this->data['subtract'] = $this->request->post['subtract'];
		} elseif (!empty($product_info)) {
			$this->data['subtract'] = $product_info['subtract'];
		} else {
			$this->data['subtract'] = 1;
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($product_info)) {
			$this->data['sort_order'] = $product_info['sort_order'];
		} else {
			$this->data['sort_order'] = 1;
		}

		$this->load->model('localisation/stock_status');

		$this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		if (isset($this->request->post['stock_status_id'])) {
			$this->data['stock_status_id'] = $this->request->post['stock_status_id'];
		} elseif (!empty($product_info)) {
			$this->data['stock_status_id'] = $product_info['stock_status_id'];
		} else {
			$this->data['stock_status_id'] = $this->config->get('config_stock_status_id');
		}

		if (isset($this->request->post['product_stock_status'])) {
			$this->data['product_stock_status'] = $this->request->post['product_stock_status'];
		} elseif (!empty($product_info)) {
			$this->data['product_stock_status'] = $this->model_catalog_product->getStockStatuses($this->request->get['product_id']);
		} else {
			$this->data['product_stock_status'] = [];
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($product_info)) {
			$this->data['status'] = $product_info['status'];
		} else {
			$this->data['status'] = 1;
		}

		if (isset($this->request->post['is_illiquid'])) {
			$this->data['is_illiquid'] = $this->request->post['is_illiquid'];
		} elseif (!empty($product_info)) {
			$this->data['is_illiquid'] = $product_info['is_illiquid'];
		} else {
			$this->data['is_illiquid'] = 0;
		}

		if (isset($this->request->post['yam_disable'])) {
			$this->data['yam_disable'] = $this->request->post['yam_disable'];
		} elseif (!empty($product_info)) {
			$this->data['yam_disable'] = $product_info['yam_disable'];
		} else {
			$this->data['yam_disable'] = 0;
		}

		if (isset($this->request->post['priceva_enable'])) {
			$this->data['priceva_enable'] = $this->request->post['priceva_enable'];
		} elseif (!empty($product_info)) {
			$this->data['priceva_enable'] = $product_info['priceva_enable'];
		} else {
			$this->data['priceva_enable'] = 0;
		}

		if (isset($this->request->post['priceva_disable'])) {
			$this->data['priceva_disable'] = $this->request->post['priceva_disable'];
		} elseif (!empty($product_info)) {
			$this->data['priceva_disable'] = $product_info['priceva_disable'];
		} else {
			$this->data['priceva_disable'] = 0;
		}


		if (isset($this->request->post['yam_disable'])) {
			$this->data['yam_disable'] = $this->request->post['yam_disable'];
		} elseif (!empty($product_info)) {
			$this->data['yam_disable'] = $product_info['yam_disable'];
		} else {
			$this->data['yam_disable'] = 0;
		}

		if (isset($this->request->post['is_markdown'])) {
			$this->data['is_markdown'] = $this->request->post['is_markdown'];
		} elseif (!empty($product_info)) {
			$this->data['is_markdown'] = $product_info['is_markdown'];
		} else {
			$this->data['is_markdown'] = 0;
		}

		if (isset($this->request->post['markdown_product_id'])) {
			$this->data['markdown_product_id'] = $this->request->post['markdown_product_id'];
		} elseif (!empty($product_info)) {
			$this->data['markdown_product_id'] = $product_info['markdown_product_id'];
		} else {
			$this->data['markdown_product_id'] = 0;
		}

		if ($this->data['markdown_product_id']){
			$markdown_product = $this->model_catalog_product->getProduct($this->data['markdown_product_id']);
			$this->data['markdown_product'] = $markdown_product['name'];
		} else {
			$this->data['markdown_product'] = '';
		}

		if (isset($this->request->post['display_in_catalog'])) {
			$this->data['display_in_catalog'] = $this->request->post['display_in_catalog'];
		} elseif (!empty($product_info)) {
			$this->data['display_in_catalog'] = $product_info['display_in_catalog'];
		} else {
			$this->data['display_in_catalog'] = 0;
		}

		if (isset($this->request->post['main_variant_id'])) {
			$this->data['main_variant_id'] = $this->request->post['main_variant_id'];
		} elseif (!empty($product_info)) {
			$this->data['main_variant_id'] = $product_info['main_variant_id'];
		} else {
			$this->data['main_variant_id'] = 0;
		}

		if (isset($this->request->post['variant_1_is_color'])) {
			$this->data['variant_1_is_color'] = $this->request->post['variant_1_is_color'];
		} elseif (!empty($product_info)) {
			$this->data['variant_1_is_color'] = $product_info['variant_1_is_color'];
		} else {
			$this->data['variant_1_is_color'] = 0;
		}

		if (isset($this->request->post['variant_2_is_color'])) {
			$this->data['variant_2_is_color'] = $this->request->post['variant_2_is_color'];
		} elseif (!empty($product_info)) {
			$this->data['variant_2_is_color'] = $product_info['variant_2_is_color'];
		} else {
			$this->data['variant_2_is_color'] = 0;
		}

		if ($this->data['main_variant_id']){
			$main_variant_product = $this->model_catalog_product->getProduct($this->data['main_variant_id']);				
			$this->data['main_variant_product'] = $main_variant_product['name'];
		} else {
			$this->data['main_variant_product'] = '';
		}

		$this->data['other_variant_products'] = [];

		$other_variant_products = [];
		if (!empty($this->request->get['product_id'])){
			$other_variant_products = $this->model_catalog_product->getAllProductVariants($this->request->get['product_id']);			
		}

		if ($other_variant_products){

			foreach ($other_variant_products as $other_variant_product){

				$this->data['other_variant_products'][] = [
					'product_id'		=> $other_variant_product['product_id'],
					'asin'				=> $other_variant_product['asin'],
					'name'				=> $other_variant_product['name'],
					'variant_name'		=> $other_variant_product['variant_name'],
					'variant_name_1'	=> $other_variant_product['variant_name_1'],
					'variant_name_2'	=> $other_variant_product['variant_name_2'],
					'variant_value_1'	=> $other_variant_product['variant_value_1'],
					'variant_value_2'	=> $other_variant_product['variant_value_2'],
					'thumb'				=> $this->model_tool_image->resize($other_variant_product['image'], 50, 50),
					'link'				=> $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $other_variant_product['product_id'], 'SSL')
				];

			}

		}

		if (isset($this->request->post['weight'])) {
			$this->data['weight'] = $this->request->post['weight'];
		} elseif (!empty($product_info)) {
			$this->data['weight'] = $product_info['weight'];
		} else {
			$this->data['weight'] = '';
		}

		$this->load->model('localisation/weight_class');

		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['weight_class_id'])) {
			$this->data['weight_class_id'] = $this->request->post['weight_class_id'];
		} elseif (!empty($product_info)) {
			$this->data['weight_class_id'] = $product_info['weight_class_id'];
		} else {
			$this->data['weight_class_id'] = $this->config->get('config_weight_class_id');
		}

		if (isset($this->request->post['weight_amazon_key'])) {
			$this->data['weight_amazon_key'] = $this->request->post['weight_amazon_key'];
		} elseif (!empty($product_info)) {
			$this->data['weight_amazon_key'] = $product_info['weight_amazon_key'];
		} else {
			$this->data['weight_amazon_key'] = '';
		}

		if (isset($this->request->post['length'])) {
			$this->data['length'] = $this->request->post['length'];
		} elseif (!empty($product_info)) {
			$this->data['length'] = $product_info['length'];
		} else {
			$this->data['length'] = '';
		}

		if (isset($this->request->post['width'])) {
			$this->data['width'] = $this->request->post['width'];
		} elseif (!empty($product_info)) {	
			$this->data['width'] = $product_info['width'];
		} else {
			$this->data['width'] = '';
		}

		if (isset($this->request->post['height'])) {
			$this->data['height'] = $this->request->post['height'];
		} elseif (!empty($product_info)) {
			$this->data['height'] = $product_info['height'];
		} else {
			$this->data['height'] = '';
		}

		$this->load->model('localisation/length_class');

		$this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['length_class_id'])) {
			$this->data['length_class_id'] = $this->request->post['length_class_id'];
		} elseif (!empty($product_info)) {
			$this->data['length_class_id'] = $product_info['length_class_id'];
		} else {
			$this->data['length_class_id'] = $this->config->get('config_length_class_id');
		}

		if (isset($this->request->post['length_amazon_key'])) {
			$this->data['length_amazon_key'] = $this->request->post['length_amazon_key'];
		} elseif (!empty($product_info)) {
			$this->data['length_amazon_key'] = $product_info['length_amazon_key'];
		} else {
			$this->data['length_amazon_key'] = '';
		}

			//PACK DIMENSIONS
		if (isset($this->request->post['pack_weight'])) {
			$this->data['pack_weight'] = $this->request->post['pack_weight'];
		} elseif (!empty($product_info)) {
			$this->data['pack_weight'] = $product_info['pack_weight'];
		} else {
			$this->data['pack_weight'] = '';
		}

		if (isset($this->request->post['pack_weight_class_id'])) {
			$this->data['pack_weight_class_id'] = $this->request->post['pack_weight_class_id'];
		} elseif (!empty($product_info)) {
			$this->data['pack_weight_class_id'] = $product_info['pack_weight_class_id'];
		} else {
			$this->data['pack_weight_class_id'] = $this->config->get('config_weight_class_id');
		}

		if (isset($this->request->post['pack_weight_amazon_key'])) {
			$this->data['pack_weight_amazon_key'] = $this->request->post['pack_weight_amazon_key'];
		} elseif (!empty($product_info)) {
			$this->data['pack_weight_amazon_key'] = $product_info['pack_weight_amazon_key'];
		} else {
			$this->data['pack_weight_amazon_key'] = '';
		}

		if (isset($this->request->post['pack_length'])) {
			$this->data['pack_length'] = $this->request->post['pack_length'];
		} elseif (!empty($product_info)) {
			$this->data['pack_length'] = $product_info['pack_length'];
		} else {
			$this->data['pack_length'] = '';
		}

		if (isset($this->request->post['pack_width'])) {
			$this->data['pack_width'] = $this->request->post['pack_width'];
		} elseif (!empty($product_info)) {	
			$this->data['pack_width'] = $product_info['pack_width'];
		} else {
			$this->data['pack_width'] = '';
		}

		if (isset($this->request->post['pack_height'])) {
			$this->data['pack_height'] = $this->request->post['pack_height'];
		} elseif (!empty($product_info)) {
			$this->data['pack_height'] = $product_info['pack_height'];
		} else {
			$this->data['pack_height'] = '';
		}

		if (isset($this->request->post['pack_length_class_id'])) {
			$this->data['pack_length_class_id'] = $this->request->post['pack_length_class_id'];
		} elseif (!empty($product_info)) {
			$this->data['pack_length_class_id'] = $product_info['pack_length_class_id'];
		} else {
			$this->data['pack_length_class_id'] = $this->config->get('config_length_class_id');
		}

		if (isset($this->request->post['pack_length_amazon_key'])) {
			$this->data['pack_length_amazon_key'] = $this->request->post['pack_length_amazon_key'];
		} elseif (!empty($product_info)) {
			$this->data['pack_length_amazon_key'] = $product_info['pack_length_amazon_key'];
		} else {
			$this->data['pack_length_amazon_key'] = '';
		}


		$this->load->model('catalog/manufacturer');

		if (isset($this->request->post['manufacturer_id'])) {
			$this->data['manufacturer_id'] = $this->request->post['manufacturer_id'];
		} elseif (!empty($product_info)) {
			$this->data['manufacturer_id'] = $product_info['manufacturer_id'];
		} else {
			$this->data['manufacturer_id'] = 0;
		}

		if (isset($this->request->post['manufacturer'])) {
			$this->data['manufacturer'] = $this->request->post['manufacturer'];
		} elseif (!empty($product_info)) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

			if ($manufacturer_info) {		
				$this->data['manufacturer'] = $manufacturer_info['name'];
			} else {
				$this->data['manufacturer'] = '';
			}	
		} else {
			$this->data['manufacturer'] = '';
		}


		if (isset($this->request->post['collection_id'])) {
			$this->data['collection_id'] = $this->request->post['collection_id'];
		} elseif (!empty($product_info)) {
			$this->data['collection_id'] = $product_info['collection_id'];
		} else {
			$this->data['collection_id'] = '';
		}

		$this->load->model('catalog/collection');


		if ($this->data['collection_id']){
			$this->data['collection'] = $this->model_catalog_collection->getCollectionById($this->data['collection_id']);				
		}

			// Categories
		$this->load->model('catalog/category');

		if (isset($this->request->post['product_category'])) {
			$categories = $this->request->post['product_category'];
		} elseif (isset($this->request->get['product_id'])) {		
			$categories = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
		} else {
			$categories = [];
		}

		if (isset($this->request->post['main_category_id'])) {
			$this->data['main_category_id'] = $this->request->post['main_category_id'];
		} elseif (isset($product_info)) {
			$this->data['main_category_id'] = $this->model_catalog_product->getProductMainCategoryId($this->request->get['product_id']);
		} else {
			$this->data['main_category_id'] = 0;
		}

		$this->data['product_categories'] = [];

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$this->data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
				);
			}
		}

		$this->data['categories'] = $this->model_catalog_category->getCategories(0);

			// Filters
		$this->load->model('catalog/filter');

		if (isset($this->request->post['product_filter'])) {
			$filters = $this->request->post['product_filter'];
		} elseif (isset($this->request->get['product_id'])) {
			$filters = $this->model_catalog_product->getProductFilters($this->request->get['product_id']);
		} else {
			$filters = [];
		}

		$this->data['product_filters'] = [];

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$this->data['product_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}		
			
		$this->load->model('catalog/attribute');
		if (isset($this->request->post['product_attribute'])) {
			$product_attributes = $this->request->post['product_attribute'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_attributes = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
		} else {
			$product_attributes = [];
		}

		$this->data['product_attributes'] = [];
		foreach ($product_attributes as $product_attribute) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);

			if ($attribute_info) {
				$this->data['product_attributes'][] = array(
					'attribute_id'                  => $product_attribute['attribute_id'],
					'name'                          => $attribute_info['name'],
					'product_attribute_description' => $product_attribute['product_attribute_description']
				);
			}
		}		


		if (isset($this->request->post['product_feature'])) {
			$product_features = $this->request->post['product_feature'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_features = $this->model_catalog_product->getProductFeatures($this->request->get['product_id']);
		} else {
			$product_features = [];
		}

		$this->data['product_features'] = [];
		foreach ($product_features as $product_feature) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($product_feature['feature_id']);

			if ($attribute_info) {
				$this->data['product_features'][] = array(
					'feature_id'                  	=> $product_feature['feature_id'],
					'name'                          => $attribute_info['name'],
					'product_feature_description' 	=> $product_feature['product_attribute_description']
				);
			}
		}	


		$this->load->model('catalog/option');
		$data = array(
			'start' => 0,
			'limit' => 20000
		);

		$this->data['_all_options_list'] = $this->model_catalog_option->getOptions($data);

		if (isset($this->request->post['product_option'])) {
			$product_options = $this->request->post['product_option'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);			
		} else {
			$product_options = [];
		}			

		$this->data['product_options'] = [];

		foreach ($product_options as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'block' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = [];

				foreach ($product_option['product_option_value'] as $product_option_value) {

					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'this_is_product_id'      => $product_option_value['this_is_product_id'],
						'linked_product'		  => $this->model_catalog_product->getProduct($product_option_value['this_is_product_id']),
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],						
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix'],
						'ob_sku'                  	 => $product_option_value['ob_sku'], //Q: Options Boost
						'ob_info'                    => $product_option_value['ob_info'], //Q: Options Boost
						'ob_image'                   => $product_option_value['ob_image'], //Q: Options Boost
						'ob_sku_override'            => isset($product_option_value['ob_sku_override']) ? $product_option_value['ob_sku_override'] : false, //Q: Options Boost
					);
				}

				$this->data['product_options'][] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'required'             => $product_option['required']
				);				
			} else {
				$this->data['product_options'][] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);				
			}
		}

			//var_dump($this->data['product_options']);

		$this->data['option_values'] = [];

		foreach ($this->data['product_options'] as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				if (!isset($this->data['option_values'][$product_option['option_id']])) {
					$this->data['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
				}
			}
		}

		if (isset($this->request->post['product_product_option'])) {
			$product_product_options = $this->request->post['product_product_option'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_product_options = $this->model_catalog_product->getProductProductOptions($this->request->get['product_id']);			
		} else {
			$product_product_options = [];
		}	

		$this->data['product_product_options'] = [];

		foreach ($product_product_options as $product_product_option) {

			$product_product_option_value_data = [];

			foreach ($product_product_option['product_option'] as $product_option_value) {
				$special = false;

				$product_specials = $this->model_catalog_product->getProductSpecials($product_option_value['product_option_id']);

				foreach ($product_specials  as $product_special) {
					if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
						$special = $product_special['price'];

						break;
					}					
				}

				$product_product_option_value_data[] = array(
					'product_product_option_value_id' => $product_option_value['product_product_option_value_id'],
					'product_option_id'         	  => $product_option_value['product_option_id'],
					'name'                    		  => $product_option_value['name'],
					'image'                   		  => $this->model_tool_image->resize($product_option_value['image'], 100, 100),
					'price'                   		  => $product_option_value['price'],
					'special'						  => $special,
					'sort_order'            		  => $product_option_value['sort_order'],					
				);

			}

			$this->data['product_product_options'][] = array(
				'product_product_option_id'    => $product_product_option['product_product_option_id'],
				'category_id'            	   => $product_product_option['category_id'],
				'name'                 		   => $product_product_option['name'],
				'type'                 		   => $product_product_option['type'],
				'required'             		   => $product_product_option['required'],
				'sort_order' 				   => $product_product_option['sort_order'],
				'product_option' 			   => $product_product_option_value_data
			);
		}

		foreach ($this->data['product_options'] as $k1 => $product_option) {
			if (isset($product_option['product_option_value'])) {
				foreach ($product_option['product_option_value'] as $k2 => $product_option_value) {
					$this->data['product_options'][$k1]['product_option_value'][$k2]['preview'] = $this->model_tool_image->resize($product_option_value['ob_image'], 38, 38);
				}
			}
		}

		$this->load->model('sale/customer_group');

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		if (isset($this->request->post['product_discount'])) {
			$this->data['product_discounts'] = $this->request->post['product_discount'];
		} elseif (isset($this->request->get['product_id'])) {
			$this->data['product_discounts'] = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
		} else {
			$this->data['product_discounts'] = [];
		}

		if (isset($this->request->post['product_special'])) {
			$this->data['product_specials'] = $this->request->post['product_special'];
		} elseif (isset($this->request->get['product_id'])) {
			$this->data['product_specials'] = $this->model_catalog_product->getProductSpecials($this->request->get['product_id']);
		} else {
			$this->data['product_specials'] = [];
		}

			// videos
		if (isset($this->request->post['product_video'])) {
			$product_videos = $this->request->post['product_video'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_videos = $this->model_catalog_product->getProductVideos($this->request->get['product_id']);
		} else {
			$product_videos = [];
		}

		$this->data['product_videos'] = [];

		foreach ($product_videos as $product_video) {
			$this->data['product_videos'][] = array(
				'image'      				=> $product_video['image'],
				'video'      				=> $product_video['video'],
				'play'						=> $this->model_tool_image->video($product_video['video']),
				'thumb'      				=> $this->model_tool_image->resize($product_video['image'], 100, 100),					
				'sort_order' 				=> $product_video['sort_order'],
				'product_video_description' => $product_video['product_video_description']
			);
		}


			// Images
		if (isset($this->request->post['product_image'])) {
			$product_images = $this->request->post['product_image'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
		} else {
			$product_images = [];
		}

		$this->data['product_images'] = [];

		foreach ($product_images as $product_image) {
			$this->data['product_images'][] = array(
				'image'      => $product_image['image'],
				'thumb'      => $this->model_tool_image->resize($product_image['image'], 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

			// Downloads
		$this->load->model('catalog/download');

		if (isset($this->request->post['product_download'])) {
			$product_downloads = $this->request->post['product_download'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_downloads = $this->model_catalog_product->getProductDownloads($this->request->get['product_id']);
		} else {
			$product_downloads = [];
		}

		$this->data['product_downloads'] = [];

		foreach ($product_downloads as $download_id) {
			$download_info = $this->model_catalog_download->getDownload($download_id);

			if ($download_info) {
				$this->data['product_downloads'][] = array(
					'download_id' => $download_info['download_id'],
					'name'        => $download_info['name']
				);
			}
		}

		/* FAproduct */
		$this -> load -> model('catalog/faproduct');
		$this -> data['faproduct_facategory_all'] = $this -> model_catalog_faproduct ->getFACategories();
		if (isset($this -> request -> post['faproduct_facategory'])) {
			$this -> data['faproduct_facategory'] = [];
		} elseif (isset($this -> request -> get['product_id'])) {
			$this -> data['faproduct_facategory'] = $this -> model_catalog_faproduct -> getFAproductCats($this -> request -> get['product_id']);
		} else {
			$this -> data['faproduct_facategory'] = [];
		}
		if (isset($this->request->post['facategory_show'])) {
			$this->data['facategory_show'] = $this->request->post['facategory_show'];
		} elseif (isset($product_info)) {
			$this->data['facategory_show'] = $this->model_catalog_faproduct -> getFAcategoryShow($this->request->get['product_id']);
		} else {
			$this->data['facategory_show'] = 0;
		}
		/* FAproduct */

		if (isset($this->request->post['product_related'])) {
			$products = $this->request->post['product_related'];
		} elseif (isset($this->request->get['product_id'])) {		
			$products = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
		} else {
			$products = [];
		}

		$this->data['products_related'] = [];

		foreach ($products as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);

			if ($related_info) {
				$this->data['products_related'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}

		if (isset($this->request->post['product_similar'])) {
			$products = $this->request->post['product_similar'];
		} elseif (isset($this->request->get['product_id'])) {		
			$products = $this->model_catalog_product->getProductSimilar($this->request->get['product_id']);
		} else {
			$products = [];
		}

		$this->data['products_similar'] = [];

		foreach ($products as $product_id) {
			$similar_info = $this->model_catalog_product->getProduct($product_id);

			if ($similar_info) {
				$this->data['products_similar'][] = array(
					'product_id' => $similar_info['product_id'],
					'name'       => $similar_info['name']
				);
			}
		}

		if (isset($this->request->post['product_sponsored'])) {
			$products = $this->request->post['product_sponsored'];
		} elseif (isset($this->request->get['product_id'])) {		
			$products = $this->model_catalog_product->getProductSponsored($this->request->get['product_id']);
		} else {
			$products = [];
		}

		$this->data['products_sponsored'] = [];

		foreach ($products as $product_id) {
			$sponsored_info = $this->model_catalog_product->getProduct($product_id);

			if ($sponsored_info) {
				$this->data['products_sponsored'][] = array(
					'product_id' => $sponsored_info['product_id'],
					'name'       => $sponsored_info['name']
				);
			}
		}

		if (isset($this->request->post['product_similar_to_consider'])) {
			$products = $this->request->post['product_similar_to_consider'];
		} elseif (isset($this->request->get['product_id'])) {		
			$products = $this->model_catalog_product->getProductSimilarToConsider($this->request->get['product_id']);
		} else {
			$products = [];
		}

		$this->data['products_similar_to_consider'] = [];

		foreach ($products as $product_id) {
			$similar_to_consider_info = $this->model_catalog_product->getProduct($product_id);

			if ($similar_to_consider_info) {
				$this->data['products_similar_to_consider'][] = array(
					'product_id' => $similar_to_consider_info['product_id'],
					'name'       => $similar_to_consider_info['name']
				);
			}
		}


		if (isset($this->request->post['product_view_to_purchase'])) {
			$products = $this->request->post['product_view_to_purchase'];
		} elseif (isset($this->request->get['product_id'])) {		
			$products = $this->model_catalog_product->getProductViewToPurchase($this->request->get['product_id']);
		} else {
			$products = [];
		}

		$this->data['products_view_to_purchase'] = [];

		foreach ($products as $product_id) {
			$view_to_purchase_info = $this->model_catalog_product->getProduct($product_id);

			if ($view_to_purchase_info) {
				$this->data['products_view_to_purchase'][] = array(
					'product_id' => $view_to_purchase_info['product_id'],
					'name'       => $view_to_purchase_info['name']
				);
			}
		}

		if (isset($this->request->post['product_also_viewed'])) {
			$products = $this->request->post['product_also_viewed'];
		} elseif (isset($this->request->get['product_id'])) {		
			$products = $this->model_catalog_product->getProductAlsoViewed($this->request->get['product_id']);
		} else {
			$products = [];
		}

		$this->data['products_also_viewed'] = [];

		foreach ($products as $product_id) {
			$also_viewed_info = $this->model_catalog_product->getProduct($product_id);

			if ($also_viewed_info) {
				$this->data['products_also_viewed'][] = array(
					'product_id' => $also_viewed_info['product_id'],
					'name'       => $also_viewed_info['name']
				);
			}
		}


		if (isset($this->request->post['product_also_bought'])) {
			$products = $this->request->post['product_also_bought'];
		} elseif (isset($this->request->get['product_id'])) {		
			$products = $this->model_catalog_product->getProductAlsoBought($this->request->get['product_id']);
		} else {
			$products = [];
		}

		$this->data['products_also_bought'] = [];

		foreach ($products as $product_id) {
			$also_bought_info = $this->model_catalog_product->getProduct($product_id);

			if ($also_bought_info) {
				$this->data['products_also_bought'][] = array(
					'product_id' => $also_bought_info['product_id'],
					'name'       => $also_bought_info['name']
				);
			}
		}

		if (isset($this->request->post['product_shop_by_look'])) {
			$products = $this->request->post['product_shop_by_look'];
		} elseif (isset($this->request->get['product_id'])) {		
			$products = $this->model_catalog_product->getProductShopByLook($this->request->get['product_id']);
		} else {
			$products = [];
		}

		$this->data['products_shop_by_look'] = [];

		foreach ($products as $product_id) {
			$shop_by_look_info = $this->model_catalog_product->getProduct($product_id);

			if ($shop_by_look_info) {
				$this->data['products_shop_by_look'][] = array(
					'product_id' => $shop_by_look_info['product_id'],
					'name'       => $shop_by_look_info['name']
				);
			}
		}

		if (isset($this->request->post['product_child'])) {
			$с_products = $this->request->post['product_child'];
		} elseif (isset($this->request->get['product_id'])) {		
			$с_products = $this->model_catalog_product->getProductChild($this->request->get['product_id']);
		} else {
			$с_products = [];
		}

		$this->data['product_child'] = [];

		foreach ($с_products as $product_id) {
			$child_info = $this->model_catalog_product->getProduct($product_id);

			if ($child_info) {
				$this->data['product_child'][] = array(
					'product_id' => $child_info['product_id'],
					'name'       => $child_info['name']
				);
			}
		}

		if (isset($this->request->post['has_child'])) {
			$this->data['has_child'] = $this->request->post['has_child'];
		} elseif (!empty($product_info)) {
			$this->data['has_child'] = $product_info['has_child'];
		} else {
			$this->data['has_child'] = 0;
		}

		if (isset($this->request->post['points'])) {
			$this->data['points'] = $this->request->post['points'];
		} elseif (!empty($product_info)) {
			$this->data['points'] = $product_info['points'];
		} else {
			$this->data['points'] = '';
		}

		if (isset($this->request->post['product_reward'])) {
			$this->data['product_reward'] = $this->request->post['product_reward'];
		} elseif (isset($this->request->get['product_id'])) {
			$this->data['product_reward'] = $this->model_catalog_product->getProductRewards($this->request->get['product_id']);
		} else {
			$this->data['product_reward'] = [];
		}

		if (isset($this->request->post['product_layout'])) {
			$this->data['product_layout'] = $this->request->post['product_layout'];
		} elseif (isset($this->request->get['product_id'])) {
			$this->data['product_layout'] = $this->model_catalog_product->getProductLayouts($this->request->get['product_id']);
		} else {
			$this->data['product_layout'] = [];
		}

		if (isset($this->request->post['youtube'])) {
			$this->data['youtube'] = $this->request->post['youtube'];
		} elseif (!empty($product_info)) {
			$this->data['youtube'] = $product_info['youtube'];
		} else {
			$this->data['youtube'] = '';
		}


		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'catalog/product_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
			$this->error['model'] = $this->language->get('error_model');
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

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function roundPrices(){

		$this->load->model('kp/price');
		$affected = $this->model_kp_price->roundPrices();

		$this->response->setOutput('Округлили цену для ' . $affected . ' товаров.');		
	}

	public function setNewPrices(){

		$this->load->model('kp/price');
		$affected = $this->model_kp_price->setNewPrices();

		$this->response->setOutput('Обновили и округлили цену для ' . $affected . ' товаров.');		
	}

	public function setHistoricalPriceToPrice(){

		$query = $this->db->query("UPDATE product SET historical_price = price WHERE 1");
		$query = $this->db->query("UPDATE product SET isbn = 0 WHERE 1");
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSettingValue('history_prices', 'date_last_historical_price_update', date("Y-m-d H:i:s"));

		$this->response->setOutput(' ОК!');		
	}

	public function autocomplete() {
		$json = [];

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_category_id'])) {
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			$this->load->model('catalog/option');

			if (isset($this->request->get['only_enabled']) && $this->request->get['only_enabled'] == 1){
				$filter_status = true;	
			} else {
				$filter_status = false;
			}

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			

			$data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'filter_status' => 1,
				'start'        => 0,
				'limit'        => $limit
			);

			if ($filter_status){
				$data['filter_status'] = 1;
			}

			$results = $this->model_catalog_product->getProducts($data);		

			foreach ($results as $result) {
				$option_data = [];

				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);	

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {				
						if ($option_info['type'] == 'select' || $option_info['type'] == 'block' || $option_info['type'] == 'radio' || $option_info['type'] == 'checkbox' || $option_info['type'] == 'image') {
							$option_value_data = [];

							foreach ($product_option['product_option_value'] as $product_option_value) {
								$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

								if ($option_value_info) {
									$option_value_data[] = array(
										'product_option_value_id' => $product_option_value['product_option_value_id'],
										'option_value_id'         => $product_option_value['option_value_id'],
										'name'                    => $option_value_info['name'],
										'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
										'price_prefix'            => $product_option_value['price_prefix']
									);
								}
							}

							$option_data[] = array(
								'product_option_id' => $product_option['product_option_id'],
								'option_id'         => $product_option['option_id'],
								'name'              => $option_info['name'],
								'type'              => $option_info['type'],
								'option_value'      => $option_value_data,
								'required'          => $product_option['required']
							);	
						} else {
							$option_data[] = array(
								'product_option_id' => $product_option['product_option_id'],
								'option_id'         => $product_option['option_id'],
								'name'              => $option_info['name'],
								'type'              => $option_info['type'],
								'option_value'      => $product_option['option_value'],
								'required'          => $product_option['required']
							);				
						}
					}
				}

				$special = false;

				$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

				foreach ($product_specials  as $product_special) {
					if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
						$special = $product_special['price'];

						break;
					}					
				}

				$this->load->model('tool/image');
				$thumb = $this->model_tool_image->resize($result['image'], 100, 100);

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'image'       => $this->model_tool_image->resize($result['image'], 40, 40),
					'model'      => $result['model'],
					'ean'        => $result['ean'],
					'asin'       => $result['asin'],
					'option'     => $option_data,
					'price'      => $result['price'],
					'image'      => $thumb,
					'special'	 => $special,
				);	
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function deleteSourceAjax(){			
		$product_id = (int)$this->request->get['product_id'];
		$md5_source = $this->request->get['md5_source'];

		$source_query = $this->db->query("SELECT source FROM product WHERE product_id = '" . (int)$product_id . "' LIMIT 1");			
		$sources = explode(PHP_EOL, $source_query->row['source']);

		$new_sources = [];

		foreach ($sources as $_source){
			if (md5($_source) != $md5_source){
				$new_sources[] = $_source;
			}
		}

		$new_sources = array_unique($new_sources, SORT_STRING);
		$new_sources = implode(PHP_EOL, $new_sources);
		$this->db->query("UPDATE product SET source = '" . $this->db->escape($new_sources) . "' WHERE product_id = '" . (int)$product_id . "'");
	}

	public function getAmazonPriceAjax(){
	}

	public function updateProductStockLimitAjax(){
		$product_id = $this->request->post['product_id'];
		$type = $this->request->post['type'];
		$store_id = $this->request->post['store_id'];
		$value = $this->request->post['value'];

		if ($type == 'min'){
			$this->db->query("INSERT INTO product_stock_limits (product_id, store_id, min_stock) VALUES ('" .(int)$product_id. "', '" .(int)$store_id. "', '" .(int)$value. "')	ON DUPLICATE KEY UPDATE min_stock = '" .(int)$value. "'");
		}

		if ($type == 'rec'){
			$this->db->query("INSERT INTO product_stock_limits (product_id, store_id, rec_stock) VALUES ('" .(int)$product_id. "', '" .(int)$store_id. "', '" .(int)$value. "')	ON DUPLICATE KEY UPDATE rec_stock = '" .(int)$value. "'");
		}
	}

	public function getByURLPriceAjax(){
		$url = $this->request->get['url'];
		$product_id = $this->request->get['product_id'];

		$responce = $this->amazonprice->getPriceByURL($url, $product_id);

		var_dump($responce);
	}

}