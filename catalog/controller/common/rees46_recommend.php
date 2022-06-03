<?php
class ControllerCommonRees46Recommend extends Controller {
  public function index() {
    $products = array();
    if (isset($this->request->get['ids'])) {
      $recommended_ids = explode(',', $this->request->get['ids']);
      $this->load->model('catalog/product');
      $this->load->model('tool/image');
      foreach ($recommended_ids as $product_id) {

		if (is_int($product_id)){	  
			$product = $this->model_catalog_product->getProduct((int)$product_id);		
		} else {
			$product = false;			
		}
        if ($product) {
			
			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			}
			
          if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}
					
			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}	
			
			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}
		  
          $rees46_product = array(
		  
			'product_id' => $result['product_id'],
			'image_url'   	 => $image,
			'name'    	 => $result['name'],
			'price'   	 => $price,
			'special' 	 => $special,
			'saving'	 => round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
			'quantity'   => $result['quantity'],
			'rating'     => $rating,
			'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
			'url'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			
          /*  'name' => $product['name'],
            'price' => $this->currency->format($this->tax->calculate($price, $product['tax_class_id'], $this->config->get('config_tax'))),
            'url' => $this->url->link('product/product', 'product_id=' . $product['product_id']),
            'image_url' => $image
		*/
          );
          array_push($products, $rees46_product);
        }
      }
    }
    header('Content-Type: application/json');
    $this->response->setOutput(json_encode($products));
  }
}
?>