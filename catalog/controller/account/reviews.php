<?php 
class ControllerAccountReviews extends Controller {
	
	public function index() {
    	if (!$this->customer->isLogged()) {
      		$this->session->data['redirect'] = $this->url->link('account/reviews', '', 'SSL');

	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	}
		
		$this->language->load('account/review');
		
		$this->load->model('account/review');
		
		$this->document->setTitle($this->language->get('heading_title'));

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),        	
        	'separator' => $this->language->get('text_separator')
      	);
		
		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_purchased_products'] = $this->language->get('text_purchased_products');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_edit'] = $this->language->get('button_edit');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['set_review'] = $this->language->get('set_review');

		
		$this->data['reviews_edit'] = (int)$this->config->get('config_myreviews_edit');
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$this->data['reviews'] = array();
		
		$reviews_total = $this->model_account_review->getTotalReviews();
		
		$this->data['purchased_products'] = $this->purchasedProducts();
		$results = $this->model_account_review->getReviews(($page - 1) * 10, 10);
		
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		foreach ($results as $result) {
			
			$product_info = $this->model_catalog_product->getProduct($result['product_id']);
			
			if ($product_info['image']) {
				$image = $this->model_tool_image->resize($product_info['image'], 50, 50);
			} else {
				$image = false;
			}

			$this->data['reviews'][] = array(
				'review_id'     => $result['review_id'],
				'product_id'    => $result['product_id'],
				'product_image' => $image,
				'product_name'  => $product_info['name'],
				'author' 		=> $result['author'],
				'text'  		=> $result['text'],
				'rating'   		=> $result['rating'],
				'class-status'  => 'status-'.$result['status'],
				'status'     	=> $this->language->get('status-'.$result['status']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'href'			=> $this->url->link('product/product', 'product_id=' . $result['product_id'], 'SSL')
			);
		}
		
		$pagination = new Pagination();
		$pagination->total = $reviews_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/reviews', 'page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/reviews.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/reviews.tpl';
		} else {
			$this->template = 'default/template/account/reviews.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
						
		$this->response->setOutput($this->render());
	}
	
	public function purchasedProducts(){
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		$products = array();
		$results = $this->model_account_review->getPurchasedProducts();
		
		foreach ($results as $result) {
			$product_info = $this->model_catalog_product->getProduct($result);
	
			if ($product_info['image']) {
				$image = $this->model_tool_image->resize($product_info['image'], 75, 75);
			} else {
				$image = false;
			}
			
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}
					
			if ((float)$product_info['special']) {
				$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}	
			
			if ($this->config->get('config_review_status')) {
				$rating = $product_info['rating'];
			} else {
				$rating = false;
			}
			
			$products[] = array(
				'product_id'    => $product_info['product_id'],
				'image' => $image,
				'name'  => $product_info['name'],
				'price'   	 => $price,
				'special' 	 => $special,
				'rating'     => $rating,
				'reviews'    => (int)$product_info['reviews'],
				'href'    	 => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
			);
		}
		return $products;
	
	}
	
	public function updateReview(){
		$json = array();
		
		$this->language->load('account/review');
		$this->load->model('account/review');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$update = array();
			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');		
			}	
			
			$update['text'] = $this->request->post['text'];
			$update['status'] = 1;
			
			if (!isset($json['error'])) {
				$this->model_account_review->updateReview($this->request->post['review_id'],$update);		
				$json['success'] = $this->language->get('text_success');
			}
		}
			
		$this->response->setOutput(json_encode($json));		
		
	}
	
}	