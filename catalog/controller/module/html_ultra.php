<?php  
class ControllerModuleHtmlUltra extends Controller {
	protected function index($setting) {
//ставим значения отображения модуля по умолчанию активным
			$show_html_ultra = "1";
			$show_html_ultra_t = "1";
			
			$this->load->model('module/html_ultra');
			
			$setting = $this->model_module_html_ultra->getHtmlUltra($setting['modul_setting_id']);
			
  			foreach($setting as $setting_key => $setting_value){
				if ($setting_key == "value"){
						$setting = unserialize($setting_value['value']);
				}
			} 		
			
			//узнаем использовать ли php 
			$this->data['php_on']=html_entity_decode($setting['php_status'], ENT_QUOTES, 'UTF-8');
			
			//Проверяем соотвтетствует ли условию вывода данный модуль
			//щетчик наличия доп настройки
			$count_dop_setting=0;
			//щетчик что доп настройка равна 1
			$count_true=0; 
			 
			//Период 
			date_default_timezone_set('Europe/Moscow');
			if (!empty($setting['datetime_from']) and !empty($setting['datetime_to'])){
				if ((strtotime(date("d-m-Y H:i:s")) >= strtotime($setting['datetime_from'])) and (strtotime(date("d-m-Y H:i:s")) <= strtotime($setting['datetime_to']))){
					$count_dop_setting++; 
					$count_true++;
				} else {
					$count_dop_setting++;
				}
			}
			
			//Время 
			if (!empty($setting['time_from']) and !empty($setting['time_to'])){
				if ((strtotime(date("H:i:s")) >= strtotime($setting['time_from'])) and (strtotime(date("H:i:s")) <= strtotime($setting['time_to']))) {
					$count_dop_setting++; 
					$count_true++;
				} else {
					$count_dop_setting++;
				}
			}
			//дни недели
			if (!empty($setting['time_day'])){
				$count_dop_setting++;
				foreach ($setting['time_day'] as $day_key => $day){
					if ($day_key == date("w")){
						$count_true++;
						break;
					}
				}				
			}

			 
			//магазин
			if((isset($setting['paragraph_status']['store']) and $setting['paragraph_status']['store'] == "0"))	{
				if (!empty($setting['product_store'])){
					if (!empty($setting['product_store'][$this->config->get('config_store_id')])){
						$count_dop_setting++; 
						$count_true++;
					} else {
						$count_dop_setting++;
					}
				}
			} else {
				if (!empty($setting['product_store'])){
					if (empty($setting['product_store'][$this->config->get('config_store_id')])){
						$count_dop_setting++; 
						$count_true++;
					} else {
						$count_dop_setting++;
					}
				}				
			}
			//Производитель
			if (isset($this->request->get['route'])){
				if(strpos($this->request->get['route'], "manufacturer") !== false){
					if((isset($setting['paragraph_status']['manufacturer']) and $setting['paragraph_status']['manufacturer'] == "0"))	{
						if (!empty($setting['manufacturer'])){ 
							if(isset($this->request->get['manufacturer_id'])){
									if (!empty($setting['manufacturer'][$this->request->get['manufacturer_id']])){
										$count_dop_setting++; 
										$count_true++;
									} else {
										$count_dop_setting++;
									}
								}
								else{
									$count_dop_setting++;
								}
						}
					} else {
						if (!empty($setting['manufacturer'])){ 
							if(isset($this->request->get['manufacturer_id'])){
									if (empty($setting['manufacturer'][$this->request->get['manufacturer_id']])){
										$count_dop_setting++; 
										$count_true++;
									} else {
										$count_dop_setting++;
									}
								}
								else{
									$count_dop_setting++;
								}
						}				
					}			
				}	
			}
					
			//Категории	
			if (isset($this->request->get['route'])){
				if(strpos($this->request->get['route'], "category") !== false){
					if((isset($setting['paragraph_status']['category']) and $setting['paragraph_status']['category'] == "0"))	{			
						if (!empty($setting['category'])){ 
							if(isset($this->request->get['path'])){ 
								$count_dop_setting++;
									$categories = explode("_", $this->request->get['path']);
									foreach ($categories as $category_id_key => $category_id) {
										if (!empty($setting['category'][$category_id])){						 
											$count_true++;
											break;
										}
									}						
								} else {
									$count_dop_setting++;
								}
						}
					} else {
						if (!empty($setting['category'])){ 
							if(isset($this->request->get['path'])){ 
								
									$categories = explode("_", $this->request->get['path']);
									foreach ($categories as $category_id_key => $category_id) {
										if (!empty($setting['category'][$category_id])){
											$count_dop_setting++;										
											break;
										}
									}						
								}
								else{
									$count_dop_setting++;
								}
						}				
					}				
				}	
			}
		 	
			//учитавать для товаров категории и производителей
			if(isset($this->request->get['product_id'])){
				if (isset($setting['produkt_consider']['category'])){
					//включаем для товаров в категории
					if ($setting['paragraph_status']['category'] == "0"){
						$count_dop_setting++;
						foreach ($setting['category'] as $category_id => $category_value){
							$produkt_category = $this->model_module_html_ultra->getProduktCategori($this->request->get['product_id'],$category_id);						
							if ($produkt_category){
									$count_true++;
									break;
								}
						}
					}else {
					//исключаем для товаров в категории	
						foreach ($setting['category'] as $category_id => $category_value){
							$produkt_category = $this->model_module_html_ultra->getProduktCategori($this->request->get['product_id'],$category_id);
								if ($produkt_category){
									$count_dop_setting++;
								}
							}
					}
					
					
				}
				
				if (isset($setting['produkt_consider']['manufacturer'])){
					//включаем для товаров от производителя
					if ($setting['paragraph_status']['manufacturer'] == "0"){
						$count_dop_setting++;
						foreach ($setting['manufacturer'] as $manufacturer_id => $manufacturer_value){
							$produkt_manufacturer = $this->model_module_html_ultra->getProduktManufacturer($this->request->get['product_id'],$manufacturer_id);						
							if ($produkt_manufacturer){
									$count_true++;
									break;
								}
						}
					}else {
					//исключаем для товаров от производителя
					foreach ($setting['manufacturer'] as $manufacturer_id => $manufacturer_value){
						$produkt_manufacturer = $this->model_module_html_ultra->getProduktManufacturer($this->request->get['product_id'],$manufacturer_id);
							if ($produkt_manufacturer){
								$count_dop_setting++;
							}
						}
					}
				}
			}			
			
			
				
			//Товар	
			if(isset($this->request->get['product_id'])){
				if((isset($setting['paragraph_status']['product']) and $setting['paragraph_status']['product'] == "0"))	{			
					if (!empty($setting['product'])){ 
						if(isset($this->request->get['product_id'])){
								if (!empty($setting['product'][$this->request->get['product_id']])){
									$count_dop_setting++; 
									$count_true++;
								} else {
									$count_dop_setting++;
								}
							} else {
								$count_dop_setting++;
							}
					}	
				} else {
					if (!empty($setting['product'])){ 
						if(isset($this->request->get['product_id'])){
								if (empty($setting['product'][$this->request->get['product_id']])){
									$count_dop_setting++; 
									$count_true++;
								}else {
									$count_dop_setting++;
								}
							} else {
								$count_dop_setting++;
							}
					}				
				}	
			}		
			
			//Группа клиента
				if((isset($setting['paragraph_status']['grup_сustomers']) and $setting['paragraph_status']['grup_сustomers'] == "0"))	{			
					if (!empty($setting['grup_clients'])){
						if (!empty($setting['grup_clients'][$this->config->get('config_customer_group_id')])){
							$count_dop_setting++; 
							$count_true++;
						} else {
							$count_dop_setting++;
						}
					}
				} else {
					if (!empty($setting['grup_clients'])){
						if (empty($setting['grup_clients'][$this->config->get('config_customer_group_id')])){
							$count_dop_setting++; 
							$count_true++;
						} else {
							$count_dop_setting++;
						}
					}				
				}				

			//Клиент	
			if((isset($setting['paragraph_status']['сustomer']) and $setting['paragraph_status']['сustomer'] == "0"))	{				
				if (!empty($setting['сustomer'])){
					if (!empty($setting['сustomer'][$this->customer->getId()])){
						$count_dop_setting++; 
						$count_true++;
					} else {
						$count_dop_setting++;
					}
				}
			} else{
				if (!empty($setting['сustomer'])){
					if (empty($setting['сustomer'][$this->customer->getId()])){
						$count_dop_setting++; 
						$count_true++;
					} else {
						$count_dop_setting++;
					}
				}
			}
			//Язык	
			if((isset($setting['paragraph_status']['language']) and $setting['paragraph_status']['language'] == "0"))	{		
				if (!empty($setting['language_m'])){
					if (!empty($setting['language_m'][$this->config->get('config_language_id')])){
						$count_dop_setting++; 
						$count_true++;
					} else {
						$count_dop_setting++;
					}
				}
			} else {
				if (!empty($setting['language_m'])){
					if (empty($setting['language_m'][$this->config->get('config_language_id')])){
						$count_dop_setting++; 
						$count_true++;
					} else {
						$count_dop_setting++;
					}				
				}
			}
			//Страницы	
			if (isset($this->request->get['information_id'])){
				if((isset($setting['paragraph_status']['information']) and $setting['paragraph_status']['information'] == "0"))	{			
					if (!empty($setting['information'])){ 
						if(isset($this->request->get['information_id'])){
								if (!empty($setting['information'][$this->request->get['information_id']])){
									$count_dop_setting++;  
									$count_true++;
								} else {
									$count_dop_setting++;
								}
							}else{
								$count_dop_setting++;
							}
					}	
				} else {
					if (!empty($setting['information'])){ 
						if(isset($this->request->get['information_id'])){
								if (empty($setting['information'][$this->request->get['information_id']])){
									$count_dop_setting++; 
									$count_true++;
								} else {
									$count_dop_setting++;
								}
							}else{
								$count_dop_setting++;
							}
					}				
				}
			}		
			
			//авторизация 
			if (!empty($setting['authorization'])){
				if ($setting['authorization'] =="1"){
					$count_dop_setting++; 
					$count_true++;
				}
				elseif(($setting['authorization']=="2") and ($this->customer->isLogged()=="1")){
					$count_dop_setting++; 
					$count_true++;
				}
				elseif(($setting['authorization']=="3") and ($this->customer->isLogged()!= "1")){
					$count_dop_setting++; 
					$count_true++;
				} else {
					$count_dop_setting++;
				}
			}		
			  
			//узнаем использовать ли оформление
			$this->data['decor_status'] = $setting['decor_status'];
			//Значение заголовка и содержимого
			$html_ultra_title 	= html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');
			$html_ultra_description 	= html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
			//код офрмелния
			$html_ultra_decor 	= html_entity_decode($setting['html_ultra_decor'], ENT_QUOTES, 'UTF-8');
			//стиль оформления 
			$this->data['html_ultra_css'] 	= html_entity_decode($setting['html_ultra_css'], ENT_QUOTES, 'UTF-8');
			
			//Использование короткого кода
			$ticket_description= array(
					'[title]' => html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8'),
					'[content]' => html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8'),		
					'[config::name]' => $this->config->get('config_name'),
					'[config::title]' => $this->config->get('config_meta_title'),
					'[config::owner]' => $this->config->get('config_owner'),
					'[config::address]' => $this->config->get('config_address'),
					'[config::email]' => $this->config->get('config_email'),
					'[config::telephone]' => $this->config->get('config_telephone'),
					'[config::fax]' => $this->config->get('config_fax'), 
					'[customer::firstname]' => $this->customer->getFirstName(),
					'[customer::lastname]' => $this->customer->getLastName(),
					'[customer::email]' => $this->customer->getEmail(),
					'[customer::telephone]' => $this->customer->getTelephone(),
					'[customer::fax]' => $this->customer->getFax(),
					'[customer::reward]' => $this->customer->getRewardPoints(),  
					'[currency::code]' => $this->currency->getCode(),
					'[language::id]' => $this->config->get('config_language_id'),
					'[language::code]' => $this->config->get('config_language') 
				); 
			//стоит ли использовать оформелние  
			if ($setting['decor_status']==1){
				foreach ($ticket_description as $key_ticket =>$ticket_result){
					$html_ultra_decor = str_replace($key_ticket,$ticket_result,$html_ultra_decor);
				}
				$this->data['html_ultra'] = $html_ultra_decor;				
			} else{
				foreach ($ticket_description as $key_ticket =>$ticket_result){
					$html_ultra_description = str_replace($key_ticket,$ticket_result,$html_ultra_description);
				}
				$this->data['heading_title'] = $html_ultra_title; 
				$this->data['html_ultra'] = $html_ultra_description;								
			}  
											
			//вывод модуля если все условия учтены  
			$show_html_ultra = ($count_dop_setting != $count_true)? "0" : "1";
			$this->data['show_html_ultra_view'] = $show_html_ultra;
			
			
			
			$this->data['test_setting'] = $setting;

			
			
			
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/html_ultra.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/html_ultra.tpl';
		} else {
			$this->template = 'default/template/module/html_ultra.tpl';
		}
		
		$this->render();
	}
}
?>