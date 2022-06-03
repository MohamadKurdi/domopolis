<? 
	$this->data['name']         = $product_info['name'];
	$this->data['url']          = $this->url->link('product/product', 'product_id=' . $this->request->get['product_id']); 
	$this->data['priceCurrency']= $this->currency->getCode(); 
	$this->data['availability'] = $product_info['quantity'] ? true : false; 
	$this->data['reviewCount']  = (int)$product_info['reviews']; 
	$this->data['ratingValue']  = $product_info['rating']; 
	$this->data['image']        = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));  
	$this->load->model('catalog/review');  
	$this->data['microdata_reviews'] = array(); 
	$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id']);  
	foreach ($results as $result) { 
	$this->data['microdata_reviews'][] = array( 'author'       => $result['author'], 'datePublished'=> date('Y-m-d', strtotime($result['date_added'])), 'ratingValue'  => (int)$result['rating'], 'description'  => $result['text'], ); }
?>