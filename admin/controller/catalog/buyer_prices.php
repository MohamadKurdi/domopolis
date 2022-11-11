<?
	
	class ControllerCatalogBuyerPrices extends Controller {
		protected $error = array();
		
		public function index() {
			
			$this->language->load('sale/contact');		
			
			$this->load->model('setting/setting');
			$this->load->model('setting/store');
			
			$this->document->setTitle('Массовая проверка цен');
			
			$this->data['button_send'] = $this->language->get('button_send');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			
			$this->data['token'] = $this->session->data['token'];
			
			
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => 'Массовая проверка цен',
			'href'      => $this->url->link('catalog/buyer_prices', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
			);
			
			
			$this->template = 'catalog/buyer_prices.tpl';
			$this->children = array(
			'common/header',
			'common/footer'
			);
			
			$this->response->setOutput($this->render());
			
			
		}
		
		public function parseList(){
			$this->load->model('sale/product');
			
			$_data = $this->request->post['input'];
			$_data = explode(PHP_EOL, $_data);
			if (!is_array($_data)){
				$_data = explode(",", $_data);	
			}
			if (!is_array($_data)){
				$_data = explode(";", $_data);	
			}
			
			$output = array(
						'input' => '',
						'good' => '', 
						'bad' => '');
			
			$data = array();
			foreach ($_data as $tmp){
				if ($tmp && mb_strlen($tmp) >= 2){
					$data[] = $tmp;
				}
			}
			
			
			foreach ($_data as $item){
			
				$output['input'] .= $item . PHP_EOL;
												
				$result = $this->model_sale_product->findProduct($item);
				
				if (count($result) == 1){
					$result = $result[0];
					
					$ean_link = '';
					$asin_link = '';
					
					if ($result['asin']){
						$asin_link = 'https://www.amazon.de/dp/'.$result['asin'];	
					}
					
					if ($result['ean']){
						$ean_link = 'https://www.amazon.de/s?field-keywords='.$result['ean'];	
					}
					
					if ($result['special']){
						$_price = $result['special'];
					} else {
						$_price = $result['price'];
					}
					
					$output['good'] .= $item . ';' . $_price . ';' 
					. $asin_link . ';' 
					. $ean_link . ';' . PHP_EOL;
				} elseif (count($result) == 0){
					$output['good'] .= $item . ';' . 'Не найден'. ';' . ';' . PHP_EOL;
					$output['bad'] .= $item . ';' . 'Не найден' . ';' . PHP_EOL;
				} elseif (count($result) > 1){
					$output['good'] .= $item . ';' . 'Дублирование' . ';' . ';' . ';' . PHP_EOL;
					$output['bad'] .= $item . ';' . 'Дублирование' . ';' . ';' . ';' . PHP_EOL;
				}

			}
			
			$this->response->setOutput(json_encode($output));
			
		}
		
		
	}			