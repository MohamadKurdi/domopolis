<?php
	class ModelCatalogActionTemplateFunctions extends Model {
		
		public function empty(){}

		public function getNewProductsForCustomerAddedFromLastOrder($data){
			$this->load->model('sale/customer');
			$this->load->model('catalog/product');
			$this->load->model('catalog/product_ext');
			$this->load->model('catalog/manufacturer');

			$result = [
				'customer' 		=> [],
				'manufacturers' => []
			];

			$customer 				= $this->model_sale_customer->getCustomer($data['customer_id']);
			$result['customer'] 	= $customer;

			$ordered_manufacturers 	= $this->model_sale_customer->getCustomerOrderManufacturers($data['customer_id']);
		//	$order 	= $this->model_sale_customer->getCustomerOrderManufacturers($data['customer_id']);			

			foreach ($ordered_manufacturers as $manufacturer_id){
				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);



				$result['manufacturers'][] = [
					'manufacturer' => [
						'name' => $manufacturer['name'],
						'link' => $this->url->link('product/manufacturer', 'manufacturer_id=' . $manufacturer_id)
					],
					'products'    => $products
				];
			}

			$this->log->debug($result);

			return $result;
		}

	}