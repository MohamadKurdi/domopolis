<?php
	class ModelCatalogActionTemplateFunctions extends Model {
		
		public function empty(){}

		private function prepareProductsArray($results){
			$products = [];

			foreach ($results as $result){

				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 185, 185);						
				} else {
					$image = $this->model_tool_image->resize($this->config->get('config_noimage'), 185, 185);						
				}

				$products[] = [
					'image' 		=> $image,
					'date_added'  	=> date('d.m.Y', strtotime($result['date_added'])),
					'name'      	=> $result['name'],
					'product_id'	=> $result['product_id'],
					'collection'	=> $result['collection_name'],
					'href'			=> $this->url->link('product/product', 'product_id=' . $result['product_id'])
				];
			}

			return $products;
		}

		public function getNewProductsForCustomerAddedFromLastOrder($data){
			$this->load->model('sale/customer');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			$this->load->model('catalog/product_ext');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/collection');

			$result = [
				'customer' 		=> [],
				'manufacturers' => []
			];

			$customer 				= $this->model_sale_customer->getCustomer($data['customer_id']);
			$result['customer'] 	= [
				'name' 			=> $customer['firstname'],
				'customer_id' 	=> $customer['customer_id'],
			];

			$ordered_manufacturers 	= $this->model_sale_customer->getCustomerOrderManufacturers($data['customer_id']);
			$ordered_collections 	= $this->model_sale_customer->getCustomerOrderCollections($data['customer_id']);
			
			$collections_to_manufacturers = [];
			foreach ($ordered_collections as $collection){
				if ($collection){
					if (empty($collections_to_manufacturers[$collection['manufacturer_id']])){
						$collections_to_manufacturers[$collection['manufacturer_id']] = [];
					}

					$collections_to_manufacturers[$collection['manufacturer_id']][] = $collection['collection_id'];
				}
			}

			$general_limit = 3;
			$collection_limit = 6;
			if (count($ordered_manufacturers) == 1){
				$limit = 12;
			}

			foreach ($ordered_manufacturers as $manufacturer_id){
				$collections 	= [];
				$products 		= [];

				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

				if (!empty($collections_to_manufacturers[$manufacturer_id])){
					foreach ($collections_to_manufacturers[$manufacturer_id] as $collection_id){
						$filter_data = [
							'filter_manufacturer_id' 	=> $manufacturer_id,
							'filter_collection_id' 		=> $collection_id,
							'filter_status'  			=> true,
							'filter_has_image' 			=> true,
							'filter_stock_status_ids'  	=> [$this->config->get('config_in_stock_status_id'), $this->config->get('config_stock_status_id')],
							'filter_date_added_from'  	=> date('Y-m-d', strtotime($customer['order_good_last_date'])),
							'sort'  					=> $this->config->get('config_warehouse_identifier') . ' > 0, RAND()',
							'order' 					=> 'DESC',
							'start'						=> 0,
							'limit' 					=> $collection_limit
						];

						$collection_products = $this->prepareProductsArray($this->model_catalog_product->getProducts($filter_data));

						if ($collection_products){
							$collection = $this->model_catalog_collection->getCollection($collection_id);
							
							$collections[] = [
								'collection_id' => $collection['collection_id'],
								'name' 			=> $collection['name'],
								'href' 			=> $this->url->link('product/collection', 'collection_id=' . $collection_id),
								'products' 		=> $collection_products
							];
						}
					}
				}				

				if (empty($collections)){
					$filter_data = [
						'filter_manufacturer_id' 	=> $manufacturer_id,
						'filter_status'  			=> true,
						'filter_has_image' 			=> true,
						'filter_stock_status_ids'  	=> [$this->config->get('config_in_stock_status_id'), $this->config->get('config_stock_status_id')],
						'filter_date_added_from'  	=> date('Y-m-d', strtotime($customer['order_good_last_date'])),
						'sort'  					=> $this->config->get('config_warehouse_identifier') . ' > 0, RAND()',
						'order' 					=> 'DESC',
						'start'						=> 0,
						'limit' 					=> $general_limit
					];

					$products = $this->prepareProductsArray($this->model_catalog_product->getProducts($filter_data));				
				}

				if ($products || $collections){
					$result['manufacturers'][] = [
						'manufacturer' => [
							'name' 	=> $manufacturer['name'],
							'image' => $this->model_tool_image->resize($manufacturer['image'], 150, 150),
							'href' 	=> $this->url->link('product/manufacturer', 'manufacturer_id=' . $manufacturer_id)
						],
						'collections' => $collections,
						'products'    => $products
					];
				}				
			}

			return $result;
		}

	}