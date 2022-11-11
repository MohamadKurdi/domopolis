<?php 
	class ControllerAccountPromocodes extends Controller {
		
		private $error = array(); 
		
		public function index() {
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('account/promocodes', '', 'SSL');
				$this->redirect($this->url->link('account/login', '', 'SSL'));
			}
			
			$this->load->language('account/account');
			
			foreach ($this->language->loadRetranslate('account/promocodes') as $translationСode => $translationText){
				$this->data[$translationСode] = $translationText;
			}
			
			$this->document->setTitle($this->data['heading_title']);		
			
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account'),        	
			'separator' => false
			);
			
			$this->load->model('account/promocodes');
			$this->load->model('catalog/actions');
			$this->load->model('catalog/collection');
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/category');
			
			$promocodes = $this->model_account_promocodes->getActivePromocodes();
			
			$this->data['promocodes'] = array();
			
			foreach ($promocodes as $promocode){
				
				$action = [];
				if ($promocode['action_id']){
					$action = $this->model_catalog_actions->getActions($promocode['action_id']);
					
					if ($action){
						$action['href'] = $this->url->link('information/actions','actions_id=' . $promocode['action_id']);				
					}
					
					$this->log->debug($action);
				}
				
				//Если промокод привязан к акции, но она неактивна в текущей стране или с ней что-то не так			
				if ($promocode['action_id'] && empty($action)){
					continue;
				}
				
				$label = [];
				if (!empty($action['label'])){
					$label = [
						'label' 			=> $action['label'],
						'label_text' 		=> $action['label_text'],
						'label_color' 		=> $action['label_color'],
						'label_background' 	=> $action['label_background'],
					];				
				}
				
				$categories = [];
				if ($promocode_categories = $this->model_account_promocodes->getCouponCategories($promocode['coupon_id'])){
				
					foreach ($promocode_categories as $category_id){
						$category = $this->model_catalog_category->getCategory($category_id);
						
						if ($category){
							$categories[] = [
								'name' 	=> $category['name'],
								'href'	=> $this->url->link('product/category', 'path=' . $category['category_id'])
							];
						}
					
					}
				
				}
				
				$manufacturers = [];
				if ($promocode_manufacturers = $this->model_account_promocodes->getCouponManufacturers($promocode['coupon_id'])){
					
					foreach ($promocode_manufacturers as $manufacturer_id){
						$manufacturer = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
						
						if ($manufacturer){
							$manufacturers[] = [
								'name' 	=> $manufacturer['name'],
								'href'	=> $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id'])
							];
						}
					
					}
				
				
				}
				
				$collections = [];
				if ($promocode_collections = $this->model_account_promocodes->getCouponCollections($promocode['coupon_id'])){
				
					foreach ($promocode_collections as $collection_id){
						$collection = $this->model_catalog_collection->getCollection($collection_id);
						
						if ($collection){
							$collections[] = [
								'name' 	=> $collection['name'],
								'href'	=> $this->url->link('product/collection', 'collection_id=' . $collection['collection_id'])
							];
						}					
					}
				}
				
				$usage = [];				
				if (!$promocode['uses_customer']){		
					$usage['type'] = 'unlimited';
					$usage['text'] = $this->data['text_promocode_usage_unlimited'];
				}
				
				if ($promocode['uses_customer']){		
					$usage['type'] = 'limited';				
					$usage['text'] = sprintf($this->data['text_promocode_usage_limited'], $promocode['uses_customer']);
					$usage['text'] .= ' ' . morphos\Russian\NounPluralization::pluralize($promocode['uses_customer'], $this->data['text_promocode_usage_times']);
						
					$currentCustomerCount = $this->model_account_promocodes->countCustomerPromocodeUsage($promocode['coupon_id'], $promocode['code']);
					
					if ($currentCustomerCount >= $promocode['uses_customer']){	
						$usage['type'] = 'limited_and_used';		
						$usage['text2'] = $this->data['text_promocode_used_already'];
					}
					
					if ($currentCustomerCount < $promocode['uses_customer'] && $promocode['uses_customer'] > 1){
						$usageLeft = ($promocode['uses_customer'] - $currentCustomerCount);
					
						$usage['type'] = 'limited_and_partly_used';		
						$usage['text2'] = sprintf($this->data['text_promocode_used_partly'], $usageLeft);
						
						$usage['text2'] .= ' ' . morphos\Russian\NounPluralization::pluralize($usageLeft, $this->data['text_promocode_usage_times']);
					}
				}
				
				$couponTotal = $this->model_account_promocodes->countTotalPromocodeUsage($promocode['code']);
				
				if ($couponTotal > 0){
					$text_total = sprintf($this->data['text_promocode_used_times'], $couponTotal, getUkrainianPluralWord($couponTotal, $this->data['text_promocode_used_customer']));
				} else {
					$text_total = $this->data['text_promocode_used_never'];
				}
				
				$this->data['promocodes'][] = [
				'code' 				=> $promocode['code'],
				'date_start' 		=> ($promocode['date_start'] != '0000-00-00')?date('d.m.Y', strtotime($promocode['date_start'])):false,
				'date_end' 			=> ($promocode['date_end'] != '0000-00-00')?date('d.m.Y', strtotime($promocode['date_end'])):false,
				'name'				=> $promocode['full_name']?$promocode['full_name']:$promocode['code'],
				'short_description'	=> $promocode['short_description'],
				'action_caption'	=> !empty($action['caption'])?$action['caption']:false,
				'action_href'		=> !empty($action['href'])?$action['href']:false,
				'label'				=> $label,
				'categories'		=> $categories,
				'manufacturers'		=> $manufacturers,
				'collections'		=> $collections,
				'usage'				=> $usage,
				'text_total'		=> $text_total
				];
				
			}
			
			$this->template = $this->config->get('config_template') . '/template/account/promocodes.tpl';
			
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
		
	}	