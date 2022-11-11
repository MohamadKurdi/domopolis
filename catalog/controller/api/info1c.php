<?
	
	class ControllerAPIInfo1C extends Controller {
		
		public function index(){
			
			
			
		}
		
		private function toNumeric($text){
		
			$text = preg_replace("/[^0-9.]/", "", $text);
			$text = trim($text);
			
			return $text;
		}
		
		public function getProductPrice($product_id){
			$this->load->model('catalog/product');
			
			$product = $this->model_catalog_product->getProduct($product_id);			
			
			if (!$product){
				$json = array(
				'error' 	=> 'product_doesnt_exists_on_front'				
				);
				die(json_encode($json));
			}
			
			$data = $this->getChildData('product/product', $product_id);
			
			$json = array(
			'price' 		=> $this->toNumeric($data['price']),
			'price_txt' 	=> $data['price'],
			'special' 		=> $this->toNumeric($data['special']),
			'special_txt' 	=> $data['special'],
			'currency'		=> $this->config->get('config_regional_currency')
			);
			
			$this->response->setOutput(json_encode($json));
			
		}
		
	}	