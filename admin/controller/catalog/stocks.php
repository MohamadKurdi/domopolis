<?
class ControllerCatalogStocks extends Controller {
	private $modelCatalogProduct = null;

	public function index() {
		$this->document->setTitle('Наличие по складам свободных остатков');
		$this->data['heading_title'] = 'Наличие по складам свободных остатков';

		loadAndRenameCatalogModels('model/catalog/product.php', 'ModelCatalogProduct', 'ModelCatalogProductCatalog');
		$this->modelCatalogProduct = new ModelCatalogProductCatalog($this->registry);

		$this->getList();
	}

	public function clearAllSpecials(){
		$this->db->query("TRUNCATE product_special");
		$this->response->setOutput($this->db->countAffected());	
	}

	public function clearIlliquid(){
		$this->db->query("UPDATE product SET is_illiquid = 0 WHERE 1");
		$this->response->setOutput($this->db->countAffected());	
	}

	public function setLiquidSpecial(){
		$store_id 			= (int)$this->request->post['store_id'];
		$qty 				= (int)$this->request->post['liquid_qty'];
		$percent 			= (int)$this->request->post['liquid_percent'];
		$month 				= (int)$this->request->post['liquid_month'];
		$liquid 			= (int)$this->request->post['liquid'];
		$only_delete 		= (int)$this->request->post['only_delete'];

		$this->load->model('setting/setting');
		$stock_field 	= $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', $store_id);

		$sql = "DELETE FROM product_special WHERE store_id = '" . (int)$store_id . "' AND product_id IN (";
		$sql .= " SELECT product_id FROM product WHERE status = 1 AND is_markdown = 0 AND is_virtual = 0 AND `" . $stock_field . "` > 0 AND product_id IN  (";
		$sql .= " SELECT product_id FROM order_product WHERE order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "'";
		$sql .=	" AND date_added >= DATE_SUB(NOW(), INTERVAL " . (int)$month . " MONTH) ) GROUP BY product_id HAVING SUM(quantity)";		
			
		if ($liquid){
			$sql .= " >= " . (int)$qty . " )";				
		} else {
			$sql .= " <= " . (int)$qty . " )";
			}
		$sql .= ')';
		$this->db->query($sql);
		$count = $this->db->countAffected();		

		if (!$liquid){
			$sql = "DELETE FROM product_special WHERE store_id = '" . (int)$store_id . "' AND product_id IN (";
			$sql .= " SELECT product_id FROM product WHERE status = 1 AND is_markdown = 0 AND is_virtual = 0 AND `" . $stock_field . "` > 0 AND product_id NOT IN  (";
			$sql .= " SELECT product_id FROM order_product WHERE order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "'";
			$sql .=	" AND date_added >= DATE_SUB(NOW(), INTERVAL " . (int)$month . " MONTH))))";

			$this->db->query($sql);
			$count += $this->db->countAffected();
		}

		if (!$only_delete){
				$this->response->setOutput($count);							
		}


		if (!$only_delete){
			$sql = "INSERT INTO product_special( `product_id`, `priority`, `price`, `store_id`, `currency_scode`, `date_start`, `date_end`, `set_by_stock`, `set_by_stock_illiquid`, `date_settled_by_stock` ) ";
			$sql .= " SELECT product_id, 100, ROUND(price - price * " . (float)($percent/100) . "), '" . (int)$store_id . "', 'EUR', '0000-00-00', '0000-00-00',";

			if ($liquid){
				$sql .= " '1', '0' ";
			} else {
				$sql .= " '0', '1' ";
			}

			$sql .= ", DATE(NOW()) FROM product WHERE status = 1 AND is_markdown = 0 AND is_virtual = 0 AND `" . $stock_field . "` > 0 AND product_id IN  (";
			$sql .= " SELECT product_id FROM order_product WHERE order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "'";
			$sql .=	" AND date_added >= DATE_SUB(NOW(), INTERVAL " . (int)$month . " MONTH) ) GROUP BY product_id HAVING SUM(quantity)";

			if ($liquid){
				$sql .= " >= " . (int)$qty . " )";
			} else {
				$sql .= " <= " . (int)$qty . " )";
			}
			$this->db->query($sql);
			$count = $this->db->countAffected();

			if (!$liquid){
				$sql = "INSERT INTO product_special( `product_id`, `priority`, `price`, `store_id`, `currency_scode`, `date_start`, `date_end`, `set_by_stock`, `set_by_stock_illiquid`, `date_settled_by_stock` ) ";
				$sql .= " SELECT product_id, 100, ROUND(price - price * " . (float)($percent/100) . "), '" . (int)$store_id . "', 'EUR', '0000-00-00', '0000-00-00', '0', '1', DATE(NOW()) FROM product WHERE status = 1 AND is_markdown = 0 AND is_virtual = 0 AND `" . $stock_field . "` > 0 AND product_id NOT IN  (";
				$sql .= " SELECT product_id FROM order_product WHERE order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "'";
				$sql .=	" AND date_added >= DATE_SUB(NOW(), INTERVAL " . (int)$month . " MONTH)))";
		
				$this->db->query($sql);
				$count += $this->db->countAffected();

				//Обозначаем товары как неликвидные для ЯМ
				$sql = " UPDATE product SET is_illiquid = 1 WHERE status = 1 AND is_markdown = 0 AND is_virtual = 0 AND `" . $stock_field . "` > 0 AND product_id NOT IN  (";
				$sql .= " SELECT product_id FROM order_product WHERE order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "'";
				$sql .=	" AND date_added >= DATE_SUB(NOW(), INTERVAL " . (int)$month . " MONTH)))";

				$this->db->query($sql);

				$sql = " UPDATE product SET is_illiquid = 1 WHERE status = 1 AND is_markdown = 0 AND is_virtual = 0 AND `" . $stock_field . "` > 0 AND product_id IN  (";
				$sql .= " SELECT product_id FROM order_product WHERE order_id IN (SELECT order_id FROM `order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "'";
				$sql .=	" AND date_added >= DATE_SUB(NOW(), INTERVAL " . (int)$month . " MONTH)) GROUP BY product_id HAVING SUM(quantity) <= " . (int)$qty . ")";

				$this->db->query($sql);

			}
		
			$this->response->setOutput($count);
		}
	}

	public function getProductsWithSettledSpecialFromStocks(){
		$liquid 		= (int)$this->request->post['liquid'];
		$store_id 		= (int)$this->request->post['store_id'];
		$date_added 	= $this->request->post['date_added'];

		$sql = "SELECT DISTINCT ps.product_id, pd.name, p.sku, p.ean, p.asin, p.price, ps.price as special  FROM product_special ps 
		LEFT JOIN product p ON (p.product_id = ps.product_id) 
		LEFT JOIN product_description pd ON (p.product_id = pd.product_id)
		WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = 1 AND p.is_markdown = 0 AND p.is_virtual = 0 AND ps.store_id = '" . (int)$store_id . "' AND DATE(date_settled_by_stock) = '" . date('Y-m-d', strtotime($date_added)) . "'";
		
		if ($liquid){
			$sql .= " AND set_by_stock = 1 AND set_by_stock_illiquid = 0";
		} else {
			$sql .= " AND set_by_stock = 0 AND set_by_stock_illiquid = 1";
		}

		$query = $this->db->query($sql);

		$this->data['products'] = $query->rows;
		$this->template = 'catalog/stocks_special_result.tpl';
		$this->response->setOutput($this->render());
	}

	public function stockDynamics(){

		$this->load->model('tool/image');		
		$this->load->model('localisation/country');	
		$this->load->model('catalog/product');
		$this->load->model('kp/product');

		$stocks = $this->model_localisation_country->getWarehouses();
		$this->data['stocks'] = $stocks;

		$this->document->setTitle('Динамика расхода товара с остатков');
		$this->data['heading_title'] = 'Динамика расхода товара с остатков';

		$this->data['breadcrumbs'] = [];

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/stocks/stockDynamics', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
		);

		$this->data['backhref'] = $this->url->link('catalog/stocks', 'token=' . $this->session->data['token']);

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'catalog/stocks_dynamics.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function getStockDynamicsAjax(){
		$this->load->model('catalog/product');

		$warehouse_identifier = $this->request->get['warehouse_identifier'];
		$range = $this->request->get['range'];

		$data = $this->model_catalog_product->getStockDynamics($warehouse_identifier, $range);

		$this->response->setOutput(json_encode($data));
	}

	private function getList(){
		$this->load->model('tool/image');		
		$this->load->model('localisation/country');	
		$this->load->model('catalog/product');
		$this->load->model('kp/product');

		$stocks = $this->model_localisation_country->getWarehouses();

		$this->document->setTitle('Наличие по складам свободных остатков');
		$this->data['heading_title'] = 'Наличие по складам свободных остатков';

		$this->data['breadcrumbs'] = [];

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token']),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/stocks', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
		);

		if (!empty($this->request->get['store_id'])){
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		if (isset($this->request->get['filter_by_brand'])){
			$filter_by_brand = $this->request->get['filter_by_brand'];
		} else {
			$filter_by_brand = 1;
		}

		if (!empty($this->request->get['filter_manufacturer_id'])){
			$filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
		} else {
			$filter_manufacturer_id = null;
		}

		if (isset($this->request->get['filter_sort'])){
			$filter_sort = $this->request->get['filter_sort'];
		} else {
			$filter_sort = null;
		}

		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();
		$this->data['store_id'] = $store_id;

		$this->data['stores'] = [];

		foreach ($stores as $store){
			if ($this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier_local', $store['store_id']) == $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier', $store['store_id'])){		
				$store['href'] = $this->url->link('catalog/stocks', 'store_id=' . $store['store_id'] . '&token=' . $this->session->data['token']);
				$this->data['stores'][] = $store;
			}			
		}

		$this->data['manufacturers'] = [];

		$this->data['stock_last_sync'] 	= date('d.m.Y в H:i:s', strtotime($this->model_kp_product->getLastUpdate()));
		$this->data['stock_identifier'] = $stock_identifier = $this->model_setting_setting->getKeySettingValue('config', 'config_warehouse_identifier_local', $store_id);
		$this->data['current_currency'] = $this->model_setting_setting->getKeySettingValue('config', 'config_regional_currency', $store_id);

		$filter_data = [
			'identifier' 				=> $this->data['stock_identifier'],
			'filter_manufacturer_id'	=> $filter_manufacturer_id,
			'filter_by_brand'			=> $filter_by_brand,
			'filter_sort'				=> $filter_sort
		];

		$results = $this->model_catalog_product->getProductStocks($filter_data);

		if (!$filter_by_brand){
			$this->data['stocks']['Без группировки по бренду'] = [];
		}

		$this->data['total_amount'] = 0;
		$this->data['total_items'] = 0;

		if ($this->config->get('config_amazon_profitability_in_stocks')){
			foreach ($results as $result){
				$product_info 			= $this->modelCatalogProduct->getProduct($result['product_id'], false);
				$result['front_price'] 	= $this->currency->format_with_left($product_info['price'], $this->data['current_currency']);
				
				$result['front_special'] = false;
				if ((float)$product_info['special']) {
					$result['front_special'] = $this->currency->format_with_left($product_info['special'], $this->data['current_currency']);
				}

				$result['cost'] 			= $this->currency->format_with_left($result['costprice'], 'EUR', 1);	
				$result['cost_national']	= $this->currency->format_with_left($result['costprice'], $this->data['current_currency']);	

				if (!empty($result['costprice'])){				
					$this->data['total_amount'] += ($result['costprice'] * $result[$this->data['stock_identifier']]);
				}

				$result['actual_cost'] 			= $this->currency->format_with_left($result['costprice'], 'EUR', 1);	
				$result['actual_cost_national'] = $this->currency->format_with_left($result['costprice'], $this->data['current_currency']);	

				$result['amazon_best_price'] 			= $this->currency->format_with_left($result['amazon_best_price'], 'EUR', 1);
				$result['amazon_best_price_national'] 	= $this->currency->format_with_left($result['amazon_best_price'], $this->data['current_currency']);						

				$this->data['total_items'] += $result[$this->data['stock_identifier']];
				if (!$result['manufacturer_name']){
					$result['manufacturer_name'] = 'Unknown';
				}

				$result['image'] = $this->model_tool_image->resize($result['image'], 50, 50);

				if ($filter_by_brand){
					if (!isset($this->data['stocks'][$result['manufacturer_name']])){
						$this->data['stocks'][$result['manufacturer_name']] = [];

						$this->data['counts'][$result['manufacturer_name']] = array(
							'total_items' => 0,
							'total_amount' => 0,
						);
					}

					$this->data['stocks'][$result['manufacturer_name']][] = $result;	
					$this->data['counts'][$result['manufacturer_name']]['total_items'] += $result[$this->data['stock_identifier']];

					if (!empty($result['costprice'])){
						$this->data['counts'][$result['manufacturer_name']]['total_amount'] += ($result['costprice'] * $result[$this->data['stock_identifier']]);
					}					

					$this->data['counts'][$result['manufacturer_name']]['total_amount_in_eur'] 		= $this->currency->format_with_left($this->data['counts'][$result['manufacturer_name']]['total_amount'], 'EUR', 1);
					$this->data['counts'][$result['manufacturer_name']]['total_amount_by_inner'] 	= $this->currency->format_with_left($this->data['counts'][$result['manufacturer_name']]['total_amount'], $this->data['current_currency']);
					$this->data['counts'][$result['manufacturer_name']]['total_amount_by_real'] 	= $this->currency->format_with_left($this->currency->real_convert($this->data['counts'][$result['manufacturer_name']]['total_amount'], 'EUR', $this->data['current_currency'], true), $this->data['current_currency'], 1);

				} else {
					$this->data['stocks']['Без группировки по бренду'][] = $result;
				}

				$this->data['manufacturers'][$result['manufacturer_id']] = [
					'manufacturer_id' => $result['manufacturer_id'],
					'name' => $result['manufacturer_name']
				];
			}
		} else {
			foreach ($results as $result){
				$costs = $this->model_kp_product->getProductCosts($result['product_id']);

				if (!empty($costs[$store_id])){
					$result['cost'] 			= $this->currency->format_with_left($costs[$store_id]['cost'], 'EUR', 1);	
					$result['cost_national']	= $this->currency->format_with_left($costs[$store_id]['cost'], $this->data['current_currency']);	

					$this->data['total_amount'] += ($costs[$store_id]['cost'] * $result[$this->data['stock_identifier']]);

					$result['min_sale_price'] 			= $this->currency->format_with_left($costs[$store_id]['min_sale_price'], 'EUR', 1);
					$result['min_sale_price_national']	= $this->currency->format_with_left($costs[$store_id]['min_sale_price'], $this->data['current_currency']);
				}

				$result['actual_cost'] = $this->currency->format_with_left($result['actual_cost'], 'EUR', 1);	
				$result['actual_cost_national'] = $this->currency->format_with_left($result['actual_cost'], $this->data['current_currency']);	

				$this->data['total_items'] += $result[$this->data['stock_identifier']];
				if (!$result['manufacturer_name']){
					$result['manufacturer_name'] = 'Unknown';
				}

				$result['image'] = $this->model_tool_image->resize($result['image'], 50, 50);

				if ($filter_by_brand){
					if (!isset($this->data['stocks'][$result['manufacturer_name']])){
						$this->data['stocks'][$result['manufacturer_name']] = [];

						$this->data['counts'][$result['manufacturer_name']] = array(
							'total_items' => 0,
							'total_amount' => 0,
						);
					}

					$this->data['stocks'][$result['manufacturer_name']][] = $result;	
					$this->data['counts'][$result['manufacturer_name']]['total_items'] += $result[$this->data['stock_identifier']];

					if (!empty($costs[$store_id])){
						$this->data['counts'][$result['manufacturer_name']]['total_amount'] += ($costs[$store_id]['cost'] * $result[$this->data['stock_identifier']]);
					}

					$this->data['counts'][$result['manufacturer_name']]['total_amount_in_eur'] 		= $this->currency->format_with_left($this->data['counts'][$result['manufacturer_name']]['total_amount'], 'EUR', 1);
					$this->data['counts'][$result['manufacturer_name']]['total_amount_by_inner'] 	= $this->currency->format_with_left($this->data['counts'][$result['manufacturer_name']]['total_amount'], $this->data['current_currency']);
					$this->data['counts'][$result['manufacturer_name']]['total_amount_by_real'] 	= $this->currency->format_with_left($this->currency->real_convert($this->data['counts'][$result['manufacturer_name']]['total_amount'], 'EUR', $this->data['current_currency'], true), $this->data['current_currency'], 1);

				} else {
					$this->data['stocks']['Без группировки по бренду'][] = $result;
				}

				$this->data['manufacturers'][$result['manufacturer_id']] = [
					'manufacturer_id' => $result['manufacturer_id'],
					'name' => $result['manufacturer_name']
				];
			}


		}		

		$this->data['total_amount_eur'] 		= $this->currency->format_with_left($this->data['total_amount'], 'EUR', 1);
		$this->data['total_amount_by_inner'] 	= $this->currency->format_with_left($this->data['total_amount'], $this->data['current_currency']);
		$this->data['total_amount_by_real'] 	= $this->currency->format_with_left($this->currency->real_convert($this->data['total_amount'], 'EUR', $this->data['current_currency'], true), $this->data['current_currency'], 1);

		$this->data['identifier'] 				= $this->data['stock_identifier'];
		$this->data['filter_manufacturer_id'] 	= $filter_manufacturer_id;
		$this->data['filter_by_brand'] 			= $filter_by_brand;
		$this->data['filter_sort'] 				= $filter_sort;

		$this->data['dynamics_href'] 	= $this->url->link('catalog/stocks/stockDynamics', 'token=' . $this->session->data['token']);
		$this->data['token'] 			= $this->session->data['token'];

		$this->template = 'catalog/stocks.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}		