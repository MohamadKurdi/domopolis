<?
	
	class ControllerKPConnector extends Controller {		
		
		public function getProductReward(){
			$json = array('success' => false);
			
			$this->load->model('catalog/product');
			$product_id = (int)$this->request->get['entity_id'];
			$product_info = $this->model_catalog_product->getProduct($product_id, false);
			
			if ($product_info){
				$json['success'] = true;
				$json['reward']  = $product_info['reward'];
			}
			
			$this->response->setJSON($json);
		}

		public function getProductPrice(){
			$json = array('success' => false);
			
			$this->load->model('catalog/product');
			$product_id = (int)$this->request->get['entity_id'];
			$product_info = $this->model_catalog_product->getProduct($product_id, false);
			
			if ($product_info){
				$json['success'] 	= true;
				$json['price']  	= $product_info['price'];
				$json['special']  	= $product_info['special'];
			}
			
			$this->response->setJSON($json);
		}
		
	}	