<?
	class ModelSaleAnalyze extends Model {
		private $error = array(); 
		
		private $stocks = array(
		'quantity_stock' => 'Германия',
		'quantity_stockK' => 'Украина',
		'quantity_stockM' => 'Россия',
		'quantity_stockMN' => 'Белоруссия',
		'quantity_stockAS' => 'Казахстан',
		);
		
		
		public function analyzeBuyPriority($order_id){							
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$this->load->model('sale/return');		
			$this->load->model('catalog/product');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/category');
			$this->load->model('catalog/collection');
			$this->load->model('catalog/parties');
			$this->load->model('localisation/order_status');
			$this->load->model('localisation/country');
			$this->load->model('localisation/currency');
			$this->load->model('localisation/return_reason');
			$this->load->model('localisation/return_action');
			$this->load->model('localisation/return_status');
			$this->load->model('localisation/weight_class');
			$this->load->model('sale/supplier');
			$this->load->model('user/user');
			$this->load->model('setting/setting');
			
			$result = array();
								
			//Определяем есть ли на остатках товары и какое количество
			$order_products = $this->model_sale_order->getOrderProducts($order_id);
			
			$quantity_in_order = 0;
			$good_products = 0;
			foreach ($order_products as $order_product){				
				$quantity_in_order += $order_product['quantity'];				
				$_product = $this->model_catalog_product->getProduct($order_product['product_id']);
								
				$quantity_on_stocks = 0;
				foreach ($this->stocks as $key=>$value){					
					$quantity_on_stocks += $_product[$key];
				}
				
				if ($quantity_on_stocks >= $order_product['quantity']){
					$good_products += 1;
				}

			}
			
			$result[] = array(
				'quantifier' => 2,
				'text'       => 'Полное обеспечение по складам есть у ' . $good_products . ' из ' . count($order_products)
			);
			
			return $result;
		}
		
	}