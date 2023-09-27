<?php
class ModelPaymentUkrcreditsIi extends Model {
	public function getMethod($address, $total, $explicit_show = false) {
		$method_data = [];

		if (!$this->config->get('ukrcredits_status')){
			return $method_data;
		}

		$setting = $this->config->get('ukrcredits_settings');

		if (!$setting['ii_status']){
			return $method_data;
		}

		$this->load->language('module/ukrcredits');
		$this->load->model('catalog/product');
		$this->load->model('module/ukrcredits');

		$query = $this->db->query("SELECT * FROM zone_to_geo_zone WHERE geo_zone_id = '" . (int)$setting['ii_geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		$status = false;

		if (!$setting['ii_geo_zone_id'] || $query->num_rows) {
			$status = true;

			$products = $this->cart->getProducts();

			foreach ($products as $product){
				$product_info 	= $this->model_catalog_product->getProduct($product['product_id']);
				$credits 		= $this->model_module_ukrcredits->checkproduct($product_info);

				if (empty($credits['ii'])){
					$status = false;
					break;
				}
			}			
		}

		if ($setting['ii_status'] && $explicit_show){
			$status = true;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'ukrcredits_ii',
				'title'      => $this->language->get('text_title_'.mb_strtolower($setting['ii_merchantType'])),
				'terms'      => '',
				'sort_order' => $this->config->get($type.'ukrcredits_ii_sort_order')
			);
		}
        
		return $method_data;
	}
}
