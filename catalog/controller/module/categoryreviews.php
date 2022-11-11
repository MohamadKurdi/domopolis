<?php  
class ControllerModuleCategoryreviews extends Controller {
	

	protected function index() { 
		$this->language->load('module/categoryreviews');
		$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_review'] = $this->language->get('entry_review');
			$this->data['entry_rating'] = $this->language->get('entry_rating');
			$this->data['entry_good'] = $this->language->get('entry_good');
			$this->data['entry_bad'] = $this->language->get('entry_bad');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');


			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_write'] = $this->language->get('text_write');
			$this->data['text_note'] = $this->language->get('text_note');
			$this->data['button_send'] = $this->language->get('button_send');
	


	  
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
			$category_id = end($parts);
		} else {
			$category_id = 0;
		}
		

		$this->load->model('module/categoryreviews');

		$this->data['text_on'] = $this->language->get('text_on');
		$this->data['text_no_categoryreviews'] = $this->language->get('text_no_categoryreviews');

	//	if (isset($this->request->get['page'])) {
	//		$page = $this->request->get['page'];
	//	} else {
	//		$page = 1;
	//	}  
		if (isset($this->request->get['page'])) {
			$this->data['hide_block'] = 1;
		}

		$this->data['categoryreviews'] = array();

	//	$categoryreview_total = $this->model_module_categoryreviews->getTotalCategoryreviewsByCategoryId($category_id);
	//	$results = $this->model_module_categoryreviews->getCategoryreviewsByCategoryId($category_id, ($page - 1) * 20, 20);
		$results = $this->model_module_categoryreviews->getCategoryreviewsByCategoryId($category_id);

		foreach ($results as $result) {
			$this->data['categoryreviews'][] = array(
				'author'     => $result['author'],
				'rating'     => (int)$result['rating'],
				'text'       => $result['text'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

	//	$pagination = new Pagination();
	//	$pagination->total = $categoryreview_total;
	//	$pagination->page = $page;
	//	$pagination->limit = 5; 
	//	$pagination->text = $this->language->get('text_pagination');
	//	$pagination->url = $this->url->link('product/category', 'path=' . $category_id . '&pages={page}');

	//	$this->data['pagination'] = $pagination->render();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/categoryreviews.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/categoryreviews.tpl';
		} else {
			$this->template = 'default/template/module/categoryreviews.tpl';
		}

		$this->response->setOutput($this->render());
	}


	public function write() {
$this->language->load('module/categoryreviews');
	if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = end($parts);
	}
	
		$this->load->model('module/categoryreviews');


					
	//	$this->language->load('product/product');
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating'])) {
				$json['error'] = $this->language->get('error_rating');
			}

			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
				$json['error'] = $this->language->get('error_captcha');
			}

			if (!isset($json['error'])) {

/*		
				$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
			    $message = $this->language->get('text_message');
				$message.= $this->language->get('text_poduct'). strip_tags($product_info['name']).'<br>';
				$message.= $this->language->get('text_reviewer').strip_tags($this->request->post['name']).'<br>';
				$message.= $this->language->get('text_rating'). strip_tags($this->request->post['rating']).'<br><br>';
				$message.= $this->language->get('text_text') . '<br>';
				$message.= strip_tags($this->request->post['text']) . '<br>';
				
				$message.='<a href="'.html_entity_decode(HTTP_SERVER . 'admin/index.php').'">'.$this->language->get('text_login').'</a>';
*/

				$this->log->write(HTTP_SERVER . '/admin/index.php');
				$this->model_module_categoryreviews->addCategoryreview($category_id, $this->request->post);
			
/*	
				$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
				$mail->setTo(array($this->config->get('config_email')));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject($this->language->get('text_subject'));
				$mail->setHtml($message);
				$mail->send();
*/			
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function captcha() {
		$this->load->library('captcha');

		$captcha = new Captcha();

		$this->session->data['captcha'] = $captcha->getCode();

		$captcha->showImage();
	}



}
?>