<?

class ControllerProductPicview extends Controller {
	private $error = array(); 
	
	public function index() { 
	
		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		
		if (isset($this->request->get['picindex'])) {
			$picindex = (int)$this->request->get['picindex'];
		} else {
			$picindex = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			
			$this->data['product_id'] = (int)$product_id;
			
			$this->data['heading_title'] = $product_info['name'];
			
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['price'] = false;
			}
			
			if ((float)$product_info['special']) {
				$this->data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$this->data['special'] = false;
			}
			
			if ($this->config->get('config_tax')) {
				$this->data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
			} else {
				$this->data['tax'] = false;
			}
			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
			$this->data['discounts'] = array();
			foreach ($discounts as $discount) {
				$this->data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}
			
			$this->data['stock'] = $product_info['stock_status'];
			$this->data['stock_status_id'] = $product_info['stock_status_id'];
			$this->data['can_not_buy'] = ($product_info['stock_status_id'] == $this->config->get('config_not_in_stock_status_id'));
			$this->data['stock_color'] = ($product_info['stock_status_id'] == $this->config->get('config_stock_status_id'))?'#4C6600':'#BA0000';
			
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$this->data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				if ($product_info['price_recommend']) {
					$this->data['price_recommend'] = $this->currency->format($this->tax->calculate($product_info['price_recommend'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$this->data['price_recommend'] = false;
				}
			} else {
				$this->data['price'] = false;
				$this->data['price_recommend'] = false;
			}
			
			
			$this->load->model('tool/image');

			$this->data['images'] = array();

			$this->data['images'][] = array(
				'popup' => $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
				'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
				'index' => 0,
				'active' => ($picindex==0),
			);
			
			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
			
			$i = 1;
			foreach ($results as $result) {
				$this->data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height')),
					'index' => $i,
					'active' => ($picindex==$i)
					
				);
				$i++;
			}

			if ($product_info['youtube']) {			
				$this->data['youtubes'] = explode(',',$product_info['youtube']);				
			}			
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/picview.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/picview.tpl';
		} else {
				$this->template = 'default/template/product/picview.tpl';
		}
		$this->response->setOutput($this->render());
	
	
	}
	
}
	
	
?>