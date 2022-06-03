<?
	define ('YANDEX_YML_VERSION', '0.9b');	
	class ControllerFeedYandexFeed extends Controller {
		
		//++++ Config section ++++
		//Из какого поля брать описание товара (description, meta_description)
		protected $DESCRIPTION_FIELD = 'description';
		//До какой длины укорачивать описание товара. 0 - не укорачивать
		protected $SHORTER_DESCRIPTION = 0;
		//Отдавать ли Яндексу оригиналы фотографий товаров. Если false - то всегда масштабировать
		protected $ORIGINAL_IMAGES = true;
		//---- Config section ----
		protected $CONFIG_PREFIX = 'yandex_yml_';
		
		protected $shop = array();
		protected $currencies = array();
		protected $categories = array();
		protected $manufacturers = array();
		protected $all_attributes = array();
		protected $offers = array();
		//protected $from_charset = 'utf-8';
		protected $eol = "\n";
		protected $yml = '';
		
		protected $color_options;
		protected $size_options;
		protected $size_units;
		protected $optioned_name;
		
		function convert($size)
		{
			$unit=array('b','kb','mb','gb','tb','pb');
			return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
		}
		
		
		public function index() {
			$this->response->setOutput('cli');
		}
		
		public function saveToFile($filename) {
			$this->generateYml();
			$fp = fopen($filename, 'w+');
			$this->putYml($fp);
			fclose($fp);
		}
		
		function translit($s)
	    {          
	        $from=array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','і','є');
	        $to=array('a','b','v','g','d','e','yo','zh','z','i','j','k','l','m','n','o','p','r','s','t','u','f','x','ts','ch','sh','sch','','y','','e','yu','ya','A','B','V','G','D','E','YO','ZH','Z','I','J','K','L','M','N','O','P','R','S','T','U','F','KH','TS','CH','SH','SCH','','Y','','E','YU','YA','i','ie');
	        return str_replace($from, $to, $s);
		}
		function rms($st)
		{
			$st = str_replace(',','',$st);$st = str_replace('’','',$st);$st = str_replace(' ','-',$st);	$st = str_replace('  ','-',$st);$st = str_replace('"','',$st);$st = str_replace(')','',$st);$st = str_replace('(','',$st);$st = str_replace('.','',$st);
			$st = str_replace('+','',$st);$st = str_replace('*','',$st);$st = str_replace('“','',$st);$st = str_replace('”','',$st);$st = str_replace('&quot;','-',$st);$st = str_replace('&','-and-',$st);$st = str_replace('$','',$st);$st = str_replace('«','',$st);$st = str_replace('»','',$st);$st = str_replace('.','',$st);$st = str_replace('/','-',$st);$st = str_replace('\\','-',$st);$st = str_replace('%','-',$st);$st = str_replace('№','-',$st);$st = str_replace('#','-',$st);$st = str_replace('_','-',$st);$st = str_replace('–','-',$st);$st = str_replace('---','-',$st);$st = str_replace('--','-',$st);$st = str_replace('\'','',$st);$st = str_replace('!','',$st);if ($st[strlen($st)-1] == '-'){	$st = substr($st, 0, strlen($st)-1); 
			}
			
			return $st;
		}	
		function normalize($st, $dot=true){
			return strtolower($this->rms($this->translit(trim($st))));
		}
		
		private function normalizeSKU($sku){
			return preg_replace("([^0-9])", "", $sku);
		}
		
		private function rms2($st, $uuml = false, $rmspace = false)
		{
			$st = str_replace('&Auml;','Ä',$st);
			$st = str_replace('&auml;','ä',$st);
			$st = str_replace('&Uuml;','Ü',$st);
			$st = str_replace('&uuml;','ü',$st);
			$st = str_replace('&Ouml;','Ö',$st);
			$st = str_replace('&ouml;','ö',$st);
			$st = str_replace('&szlig;','ß',$st);
			$st = str_replace('&Oslash;','Ø',$st);
			
			if ($uuml){
				return $st;
			}
			
			$st = str_replace(',','',$st);
			$st = str_replace('’','',$st);
			$st = str_replace('"','',$st);
			$st = str_replace(')','',$st);
			$st = str_replace('(','',$st);
			$st = str_replace('.','',$st);
			$st = str_replace('+','',$st);
			$st = str_replace('*','',$st);
			$st = str_replace('“','',$st);
			$st = str_replace('”','',$st);
			$st = str_replace('&#13;','',$st);
			$st = str_replace('\r\n','',$st);
			$st = str_replace("\x13",'', $st);			
			$st = str_replace('&quot;','-',$st);
			$st = str_replace('&nbsp;',' ',$st);
			$st = str_replace('&amp;','and',$st);
			$st = str_replace('&deg;','°',$st);
			$st = str_replace('&','',$st);
			$st = str_replace('«','',$st);
			$st = str_replace('»','',$st);
			$st = str_replace('.','',$st);
			$st = str_replace('/','-',$st);
			$st = str_replace('\\','-',$st);
			$st = str_replace('%','-',$st);
			$st = str_replace('№','-',$st);
			$st = str_replace('#','-',$st);
			$st = str_replace('_','-',$st);
			$st = str_replace('–','-',$st);
			$st = str_replace('---','-',$st);
			$st = str_replace('--','-',$st);
			$st = str_replace('\'','',$st);
			$st = str_replace('!','',$st);
			
			return html_entity_decode($st, ENT_COMPAT, 'UTF-8');
		}
		
		protected function getElement($attributes, $element_name, $element_value = '') {
			$retval = '<' . $element_name . ' ';
			foreach ($attributes as $key => $value) {
				$retval .= $key . '="' . $value . '" ';
			}
			$retval .= $element_value ? '>' . $this->eol . $element_value . '</' . $element_name . '>' : '/>';
			$retval .= $this->eol;
			
			return $retval;
		}
		
		protected function getElementNoEOL($attributes, $element_name, $element_value = '') {
			$retval = '<' . $element_name . ' ';
			foreach ($attributes as $key => $value) {
				$retval .= $key . '="' . $value . '" ';
			}
			$retval .= $element_value ? '>' . $element_value . '</' . $element_name . '>' : '/>';
			$retval .= $this->eol;
			
			return $retval;
		}
		
		protected function prepareField($field) {
			$field = htmlspecialchars_decode($field);
			$field = strip_tags($field);
			$from = array('"', '&', '>', '<', '\'', '&nbsp;');
			$to = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;', ' ');
			$field = str_replace($from, $to, $field);
			/**
				if ($this->from_charset != 'windows-1251') {
				$field = iconv($this->from_charset, 'windows-1251//IGNORE', $field);
				}
			**/
			$field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);
			
			return trim($field);
		}
		
		protected function getPath($category_id, $current_path = '') {
			if (isset($this->categories[$category_id])) {
				$this->categories[$category_id]['export'] = 1;
				
				if (!$current_path) {
					$new_path = $this->categories[$category_id]['id'];
					} else {
					$new_path = $this->categories[$category_id]['id'] . '_' . $current_path;
				}	
				
				if (isset($this->categories[$category_id]['parentId'])) {
					return $this->getPath($this->categories[$category_id]['parentId'], $new_path);
					} else {
					return $new_path;
				}
				
			}
		}
		
		/**
			* Преобразование массива в теги
			*
			* @param array $tags
			* @return string
		*/
		protected function array2Tag($tags) {
			$retval = '';
			foreach ($tags as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $val) {
						$retval .= '<' . $key . '>' . $val . '</' . $key . '>' . $this->eol;
					}
				}
				else {
					$retval .= '<' . $key . '>' . $value . '</' . $key . '>' . $this->eol;
				}
			}
			
			return $retval;
		}
		
		/**
			* Преобразование массива в теги параметров
			*
			* @param array $params
			* @return string
		*/
		protected function array2Param($params) {
			$retval = '';
			foreach ($params as $param) {
				$retval .= '<param name="' . $this->prepareField($param['name']);
				if (isset($param['unit'])) {
					$retval .= '" unit="' . $this->prepareField($param['unit']);
				}
				$retval .= '">' . $this->prepareField($param['value']) . '</param>' . $this->eol;
			}
			
			return $retval;
		}
		
		/**
			* Преобразование массива в теги accessory
			*
			* @param array $params
			* @return string
		*/
		protected function array2Accessory($rels) {
			$retval = '';
			foreach ($rels as $rel) {
				$retval .= '<accessory offer="' . $rel . '"/>';
			}
			
			return $retval;
		}
		
		/**
			* Методы формирования YML
		*/
		
		/**
			* Формирование массива для элемента shop описывающего магазин
			*
			* @param string $name - Название элемента
			* @param string $value - Значение элемента
		*/
		protected function setShop($name, $value) {
			$allowed = array('name', 'company', 'url', 'phone', 'platform', 'version', 'agency', 'email');
			if (in_array($name, $allowed)) {
				$this->shop[$name] = $this->prepareField($value);
			}
		}
		
		protected function setCategory($name, $id, $parent_id = 0) {
			$id = (int)$id;
			if ($id < 1 || trim($name) == '') {
				return false;
			}
			if ((int)$parent_id > 0) {
				$this->categories[$id] = array(
				'id'=>$id,
				'parentId'=>(int)$parent_id,
				'name'=>$this->prepareField($name)
				);
				} else {
				$this->categories[$id] = array(
				'id'=>$id,
				'name'=>$this->prepareField($name)
				);
			}
			
			return true;
		}
		
		/**
			* Валюты
			*
			* @param string $id - код валюты (RUR, RUB, USD, BYR, KZT, EUR, UAH)
			* @param float|string $rate - курс этой валюты к валюте, взятой за единицу.
			*	Параметр rate может иметь так же следующие значения:
			*		CBRF - курс по Центральному банку РФ.
			*		NBU - курс по Национальному банку Украины.
			*		NBK - курс по Национальному банку Казахстана.
			*		СВ - курс по банку той страны, к которой относится интернет-магазин
			* 		по Своему региону, указанному в Партнерском интерфейсе Яндекс.Маркета.
			* @param float $plus - используется только в случае rate = CBRF, NBU, NBK или СВ
			*		и означает на сколько увеличить курс в процентах от курса выбранного банка
			* @return bool
		*/
		protected function setCurrency($id, $rate = 'CBRF', $plus = 0) {
			$allow_id = array('RUR', 'RUB', 'USD', 'BYN', 'KZT', 'EUR', 'UAH');
			if (!in_array($id, $allow_id)) {
				return false;
			}
			$allow_rate = array('CBRF', 'NBU', 'NBK', 'CB');
			if (in_array($rate, $allow_rate)) {
				$plus = str_replace(',', '.', $plus);
				if (is_numeric($plus) && $plus > 0) {
					$this->currencies[] = array(
					'id'=>$this->prepareField(strtoupper($id)),
					'rate'=>$rate,
					'plus'=>(float)$plus
					);
					} else {
					$this->currencies[] = array(
					'id'=>$this->prepareField(strtoupper($id)),
					'rate'=>$rate
					);
				}
				} else {
				$rate = str_replace(',', '.', $rate);
				if (!(is_numeric($rate) && $rate > 0)) {
					return false;
				}
				$this->currencies[] = array(
				'id'=>$this->prepareField(strtoupper($id)),
				'rate'=>(float)$rate
				);
			}
			
			return true;
		}
		
		/*
			* Создает много элементов offer товарных предложений для разных опций цвет и размер товара
		*/
		protected function setOptionedOffer($data, $product, $shop_currency, $offers_currency, $decimal_place) {
			$offers_array = array();
			
			$coptions = array();
			if ($this->color_options)
			$coptions = $this->model_export_yandex_yml->getProductOptions($this->color_options, $product['product_id']);
			$soptions = array();
			if ($this->size_options)
			$soptions = $this->model_export_yandex_yml->getProductOptions($this->size_options, $product['product_id']);
			if (!count($coptions) && !count($soptions)) {
				return false;
			}
			//++++ Цвета x Размеры для магазинов одежды ++++
			if (count($coptions)) {
				foreach ($coptions as $option) {
					//Если в опциях кол-во равно 0, то в OpenCart эта опция не показывается совсем, хотя она может быть просто не быть в наличии
					if ($option['subtract'] && ($option['quantity'] <= 0)) {
						continue;
					}
					$data_arr = $data;
					if (($this->optioned_name == 'short') || ($this->optioned_name == 'long')) {
						//$data_arr['name'].= ', цвет '.$option['name'];
						$data_arr['name'].= ', '.$option['option_name'].' '.$option['name'];
					}
					//$data_arr['param'][] = array('name'=>'Цвет', 'value'=>$option['name']);
					$data_arr['param'][] = array('name'=>$option['option_name'], 'value'=>$option['name']);
					$data_arr['group_id'] = $product['product_id'];
					$data_arr['option_value_id'] = $option['option_value_id'];
					$data_arr['available'] = $data_arr['available'] && ($option['quantity'] > 0);
					if ($option['price_prefix'] == '+') {
						$data_arr['price']+= $option['price'];
						if (isset($data_arr['oldprice']))
						$data_arr['oldprice']+= $option['price'];
					}
					elseif ($option['price_prefix'] == '-') {
						$data_arr['price']-= $option['price'];
						if (isset($data_arr['oldprice']))
						$data_arr['oldprice']-= $option['price'];
					}
					elseif ($option['price_prefix'] == '=') {
						$data_arr['price'] = $option['price'];
					}
					$data_arr = $this->setOptionedWeight($data_arr, $option);
					$data_arr['url'].= '#'.$option['product_option_value_id'];
					$offers_array[] = $data_arr;
				}
			}
			else {
				$data['group_id'] = $product['product_id'];
				$offers_array[] = $data;
			}
			// Размеры
			foreach ($offers_array as $i=>$data) {
				if (count($soptions)) {
					foreach ($soptions as $option) {
						//Если в опциях кол-во равно 0, то в OpenCart эта опция не показывается совсем, хотя она может быть просто не быть в наличии
						if ($option['subtract'] && ($option['quantity'] <= 0)) {
							continue;
						}
						$size_option_name = $option['option_name'];
						$size_option_unit = $this->size_units[$option['option_id']];
						$data_arr = $data;
						if ($this->optioned_name == 'long') {
							$data_arr['name'].= ', '.$size_option_name.' '.$option['name'];
						}
						$size_param = array('name'=>$size_option_name, 'value'=>$option['name']);
						if ($size_option_unit) {
							$size_param['unit'] = $size_option_unit;
						} 
						$data_arr['param'][] = $size_param;
						$data_arr['available'] = $data_arr['available'] && ($option['quantity'] > 0);
						
						if ($option['price_prefix'] == '+') {
							$data_arr['price']+= $option['price'];
							if (isset($data_arr['oldprice']))
							$data_arr['oldprice']+= $option['price'];
						}
						elseif ($option['price_prefix'] == '-') {
							$data_arr['price']-= $option['price'];
							if (isset($data_arr['oldprice']))
							$data_arr['oldprice']-= $option['price'];
						}
						elseif ($option['price_prefix'] == '=') {
							$data_arr['price'] = $option['price'];
						}
						
						$data_arr = $this->setOptionedWeight($data_arr, $option);
						if (count($coptions)) {
							$data_arr['url'].= '-'.$option['product_option_value_id'];
						}
						else {
							$data_arr['url'].= '#'.$option['product_option_value_id'];
						}
						$offers_array[] = $data_arr;
						
						//$data_arr['id'] = $data['group_id'].str_pad($idx, 4, '0', STR_PAD_LEFT);
						$data_arr['id'] = $data['group_id']
						.(isset($data['option_value_id']) ? str_pad($data['option_value_id'], 7, '0', STR_PAD_LEFT) : '')
						.(isset($option['option_value_id']) ? str_pad($option['option_value_id'], 7, '0', STR_PAD_LEFT) : '');
						$data_arr['price'] = number_format($this->currency->convert($this->tax->calculate($data_arr['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
						if (isset($data_arr['oldprice']))
						$data_arr['oldprice'] = number_format($this->currency->convert($this->tax->calculate($data_arr['oldprice'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
						$this->setOffer($data_arr);
					}
				}
				else {
					//$data['id'] = $data['group_id'].str_pad($i, 4, '0', STR_PAD_LEFT);
					$data['id'] = $data['group_id']
					.(isset($data['option_value_id']) ? str_pad($data['option_value_id'], 7, '0', STR_PAD_LEFT) : '');
					$data['price'] = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
					if (isset($data['oldprice']))
					$data['oldprice'] = number_format($this->currency->convert($this->tax->calculate($data['oldprice'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
					if ($data['price'] > 0) {
						$this->setOffer($data);
					}
				}
			}
			return true;
			//---- Цвета x Размеры для магазинов одежды ----
		}
		
		/**
			* Меняет аттрибут веса товара в зависимости от опции
		*/
		protected function setOptionedWeight($product, $option) {
			if (isset($option['weight']) && isset($option['weight_prefix'])) {
				foreach ($product['param'] as $i=>$param) {
					if (isset($param['id']) && ($param['id'] == 'WEIGHT')) {
						if ($option['weight_prefix'] == '+')
						$product['param'][$i]['value']+= $option['weight'];
						elseif ($option['weight_prefix'] == '-')
						$product['param'][$i]['value']-= $option['weight'];
						break;
					}
				}
			}
			return $product;
		}
		
		/**
			* Подготовка данных о фотографии
		*/
		protected function prepareImage($image) {
			if ((strpos($image, 'http://') === 0) || (strpos($image, 'https://') === 0)) {
				return $image;
			}
			if (is_file(DIR_IMAGE . $image)) {
				list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $image);
				if ($width_orig < 600 || $height_orig < 600 || !$this->ORIGINAL_IMAGES) {
					return $this->model_tool_image->resize($image, 600, 600);
					} else {
					return $this->model_tool_image->resize($image, $width_orig, $height_orig);
				}
			}
			return false;
		}
		
		function filterCategory($category) {
			return isset($category['export']);
		}
		
		/**
			* Товарные предложения
			*
			* @param array $data - массив параметров товарного предложения
		*/
		protected function setOffer($data) {
			if ($data['price'] <= $this->config->get($this->CONFIG_PREFIX.'pricefrom')) return;
			
			$offer = array();
			
			$attributes = array('id', 'type', 'available', 'bid', 'cbid', 'param', 'group_id', 'accessory');
			$attributes = array_intersect_key($data, array_flip($attributes));
			
			foreach ($attributes as $key => $value) {
				switch ($key)
				{
					case 'id':
					$offer['id'] = $value;
					break;
					case 'bid':
					case 'cbid':
					case 'group_id':
					$value = (int)$value;
					if ($value > 0) {
						$offer[$key] = $value;
					}
					break;
					
					case 'type':
					if (in_array($value, array('vendor.model', 'book', 'audiobook', 'artist.title', 'tour', 'ticket', 'event-ticket'))) {
						$offer['type'] = $value;
					}
					break;
					
					case 'available':
					$offer['available'] = ($value ? 'true' : 'false');
					break;
					
					case 'param':
					if (is_array($value)) {
						$offer['param'] = $value;
					}
					break;
					
					case 'accessory':
					if (is_array($value)) {
						$offer['accessory'] = $value;
					}
					break;
					
					default:
					break;
				}
			}
			
			$type = isset($offer['type']) ? $offer['type'] : '';
			
			$allowed_tags = array('url'=>0, 'buyurl'=>0, 'price'=>1, 'oldprice'=>0, 'wprice'=>0, 'currencyId'=>1, 'xCategory'=>0, 'categoryId'=>1, 'market_category'=>0, 'picture'=>0, 'store'=>0, 'pickup'=>0, 'delivery'=>0, 'deliveryIncluded'=>0, 'local_delivery_cost'=>0, 'orderingTime'=>0);
			
			switch ($type) {
				case 'vendor.model':
				$allowed_tags = array_merge($allowed_tags, array('typePrefix'=>0, 'vendor'=>1, 'vendorCode'=>0, 'model'=>1, 'provider'=>0, 'tarifplan'=>0));
				break;
				
				case 'book':
				$allowed_tags = array_merge($allowed_tags, array('author'=>0, 'name'=>1, 'publisher'=>0, 'series'=>0, 'year'=>0, 'ISBN'=>0, 'volume'=>0, 'part'=>0, 'language'=>0, 'binding'=>0, 'page_extent'=>0, 'table_of_contents'=>0));
				break;
				
				case 'audiobook':
				$allowed_tags = array_merge($allowed_tags, array('author'=>0, 'name'=>1, 'publisher'=>0, 'series'=>0, 'year'=>0, 'ISBN'=>0, 'volume'=>0, 'part'=>0, 'language'=>0, 'table_of_contents'=>0, 'performed_by'=>0, 'performance_type'=>0, 'storage'=>0, 'format'=>0, 'recording_length'=>0));
				break;
				
				case 'artist.title':
				$allowed_tags = array_merge($allowed_tags, array('artist'=>0, 'title'=>1, 'year'=>0, 'media'=>0, 'starring'=>0, 'director'=>0, 'originalName'=>0, 'country'=>0));
				break;
				
				case 'tour':
				$allowed_tags = array_merge($allowed_tags, array('worldRegion'=>0, 'country'=>0, 'region'=>0, 'days'=>1, 'dataTour'=>0, 'name'=>1, 'hotel_stars'=>0, 'room'=>0, 'meal'=>0, 'included'=>1, 'transport'=>1, 'price_min'=>0, 'price_max'=>0, 'options'=>0));
				break;
				
				case 'event-ticket':
				$allowed_tags = array_merge($allowed_tags, array('name'=>1, 'place'=>1, 'hall'=>0, 'hall_part'=>0, 'date'=>1, 'is_premiere'=>0, 'is_kids'=>0));
				break;
				
				default:
				$allowed_tags = array_merge($allowed_tags, array('name'=>1, 'vendor'=>0, 'vendorCode'=>0));
				break;
			}
			
			$allowed_tags = array_merge($allowed_tags, array('aliases'=>0, 'additional'=>0, 'description'=>0, 'sales_notes'=>0, 'promo'=>0, 'manufacturer_warranty'=>0, 'country_of_origin'=>0, 'downloadable'=>0, 'adult'=>0, 'barcode'=>0, 'rec'=>0));
			
			$required_tags = array_filter($allowed_tags);
			
			if (sizeof(array_intersect_key($data, $required_tags)) != sizeof($required_tags)) {
				return;
			}
			
			$data = array_intersect_key($data, $allowed_tags);
			//		if (isset($data['tarifplan']) && !isset($data['provider'])) {
			//			unset($data['tarifplan']);
			//		}
			
			$allowed_tags = array_intersect_key($allowed_tags, $data);
			
			// Стандарт XML учитывает порядок следования элементов,
			// поэтому важно соблюдать его в соответствии с порядком описанным в DTD
			$offer['data'] = array();
			foreach ($allowed_tags as $key => $value) {
				if (!isset($data[$key]))
				continue;
				if (is_array($data[$key])) {
					foreach ($data[$key] as $i => $val) {
						$offer['data'][$key][$i] = $this->prepareField($val);
					}
				}
				else {
					$offer['data'][$key] = $this->prepareField($data[$key]);
				}
			}
			
			$this->offers[] = $offer;
		}
		
		/**
			* Определение единиц измерения по содержимому
			*
			* @param array $attr array('name'=>'Вес', 'value'=>'100кг')
			* @return array array('name'=>'Вес', 'unit'=>'кг', 'value'=>'100')
		*/
		protected function detectUnits($attr) {
			//$matches = array();
			$attr['name'] = trim(strip_tags($attr['name']));
			$attr['value'] = trim(strip_tags($attr['value']));
			if (preg_match('/\(([^\)]+)\)$/mi', $attr['name'], $matches)) {
				$attr['name'] = trim(str_replace('('.$matches[1].')', '', $attr['name']));
				$attr['unit'] = trim($matches[1]);
			}
			return $attr;
		}
		
		public function prepareOffers($products){
			$this->load->model('tool/image');
			$this->offers = array();
			
			$offers_currency = $this->config->get('config_regional_currency');
			$shop_currency = $this->config->get('config_currency');
			$decimal_place = intval($this->currency->getDecimalPlace($offers_currency));		
			//Тип данных vendor.model или default
			$datamodel = $this->config->get($this->CONFIG_PREFIX.'datamodel');							
			$in_stock_id = $this->config->get($this->CONFIG_PREFIX.'in_stock'); // id статуса товара "В наличии"
			$out_of_stock_id = $this->config->get($this->CONFIG_PREFIX.'out_of_stock'); // id статуса товара "Нет на складе"
			$vendor_required = ($datamodel == 'vendor_model'); // true - только товары у которых задан производитель, необходимо для 'vendor.model' 
			
			$pickup = ($this->config->get($this->CONFIG_PREFIX.'pickup') ? 'true' : false);
			
			if ($this->config->get($this->CONFIG_PREFIX.'delivery_cost') != '') {					
				$local_delivery_cost = intval($this->config->get($this->CONFIG_PREFIX.'delivery_cost'));
				if ($shop_currency == 'UAH'){
					$local_delivery_cost = 80;
				}
				$export_delivery_cost = true;
			}
			else {
				$export_delivery_cost = false;
			}				
			$store = ($this->config->get($this->CONFIG_PREFIX.'store') ? 'true' : false);
			$unavailable = $this->config->get($this->CONFIG_PREFIX.'unavailable');				
			$product_rel = $this->config->get($this->CONFIG_PREFIX.'product_rel');
			$product_accessory = $this->config->get($this->CONFIG_PREFIX.'product_accessory');
			
			$numpictures = $this->config->get($this->CONFIG_PREFIX.'numpictures');		
			$this->optioned_name = $this->config->get($this->CONFIG_PREFIX.'optioned_name');				
			$yandex_yml_categ_mapping = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_mapping'));				
			$this->color_options = explode(',', $this->config->get($this->CONFIG_PREFIX.'color_options'));
			$this->size_options = explode(',', $this->config->get($this->CONFIG_PREFIX.'size_options'));
			$this->size_units = $this->config->get($this->CONFIG_PREFIX.'size_units') ? unserialize($this->config->get($this->CONFIG_PREFIX.'size_units')) : array();
			
			$total = count($products);
			echo '>>> Подготовка ' . $total . ' товаров: ' . PHP_EOL;
			$i=1;
			foreach ($products as $product) {
				$data = array();
				
				if ($i%500 == 0){
					echo $i . ' (' .	$this->convert(memory_get_usage(true)) . ')' . "...";
				}
				$i++;
				
				// Атрибуты товарного предложения
				$data['id'] = $product['product_id'];
				$data['type'] = $datamodel; //'vendor.model' или 'default';
				$data['available'] = (!$unavailable && ($product['quantity'] > 0 || $product['stock_status_id'] == $in_stock_id) ? 'true' : false);
				
				// Параметры товарного предложения
				$data['url'] = $this->url->link('product/product', 'path=' . $this->getPath($product['category_id']) . '&product_id=' . $product['product_id']);
				if ($product['special'] && $product['special'] < $product['price']) {
					$data['price'] = $product['special'];
					$data['oldprice'] = $product['price'];
				}
				else {
					$data['price'] = $product['price'];
				}
				$data['currencyId'] = $offers_currency;
				
				$data['categoryId'] = $product['category_id'];
				if (isset($yandex_yml_categ_mapping[$product['category_id']]) && $yandex_yml_categ_mapping[$product['category_id']]) {
					$data['market_category'] = $yandex_yml_categ_mapping[$product['category_id']];
				}
				$data['delivery'] = 'true';
				if ($export_delivery_cost) {
					$data['local_delivery_cost'] = $local_delivery_cost;
				}
				if ($pickup)
				$data['pickup'] = $pickup;
				if ($store)
				$data['store'] = $store;
				
				$data['name'] = $product['name'];
				$data['vendor'] = $product['manufacturer'];
				$data['vendorCode'] = $product['model'];
				$data['model'] = $product['name'];
				
				if ($product['location']){
					$data['country_of_origin'] = $product['location'];
				}
				
				$sales_notes = $this->config->get($this->CONFIG_PREFIX.'sales_notes');
				if ($sales_notes) {
					$data['sales_notes'] = $sales_notes;
				}
				
				$data['barcode'] = $product['ean'];
				if ($numpictures > 0) {
					if ($product['image']) {
						$data['picture'] = array($this->prepareImage($product['image']));
					}
					//++++ Дополнительные изображения товара ++++
					if (isset($this->product_images[$product['product_id']])) {
						if (!isset($data['picture']) || !is_array($data['picture'])) {
							$data['picture'] = array();
						}
						foreach ($this->product_images[$product['product_id']] as $image) {
							$data['picture'][] = $this->prepareImage($image);
						}
					}
					//---- Дополнительные изображения товара ----
				}
				
				if ($product_rel && $product['rel']) {
					$data['rel'] = $product['rel'];
				}
				if ($product_accessory && $product['rel']) {
					$data['accessory'] = explode(',', $product['rel']);
				}
				
				$data['param'] = array();
				$attributes = $this->model_export_yandex_yml->getProductAttributes($product['product_id']);
				$attr_text = array();
				if (count($attributes) > 0) {
					foreach ($attributes as $attr) {
						if ($attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'adult')) {
							$data['adult'] = 'true';
						}
						elseif ($attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'manufacturer_warranty')) {
							$data['manufacturer_warranty'] = 'true';
						}
						elseif ($attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'country_of_origin')) {
							$data['country_of_origin'] = $attr['text'];
						}
						elseif (isset($this->all_attributes[$attr['attribute_id']])) {
							$data['param'][] = $this->detectUnits(array(
							'name' => $this->all_attributes[$attr['attribute_id']],
							'value' => $attr['text']));
						}
						$attr_text[] = $attr['name'].': '.$attr['text'];
					}
				}
				if ($product['weight'] > 0) {
					$data['param'][] = array('id'=>'WEIGHT', 'name'=>'Вес', 'value'=>$product['weight'], 'unit'=>$product['weight_unit']);
				}
				//---- Атрибуты товара ----
				
				//++++ Описание товара ++++
				if ($this->config->get($this->CONFIG_PREFIX.'attr_vs_description')) {
					$data['description'] = implode($attr_text,"\n");
				}
				else {
					$product_description = strip_tags($product[$this->DESCRIPTION_FIELD]);
					if ($this->SHORTER_DESCRIPTION > 0) {
						$product_description = mb_substr($product_description, 0, $this->SHORTER_DESCRIPTION, 'UTF-8');
					}
					$data['description'] = $product_description;
				}
				//---- Описание товара ----
				
				if ($product['minimum'] > 1) {
					if (isset($data['sales_notes']))
					$data['sales_notes'].= ', минимальное кол-во заказа: '.$product['minimum'];
					else
					$data['sales_notes'] = 'Минимальное кол-во заказа: '.$product['minimum'];
				}
				
				if (!$this->setOptionedOffer($data, $product, $shop_currency, $offers_currency, $decimal_place)) {
					$data['price'] = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
					if (isset($data['oldprice']))
					$data['oldprice'] = number_format($this->currency->convert($this->tax->calculate($data['oldprice'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
					if ($data['price'] > 0) {
						$this->setOffer($data);
					}
				}
			}
			
			echo PHP_EOL;
			
			//$this->categories = array_filter($this->categories, array($this, "filterCategory"));
		}	
		
		public function priceva(){
			$this->load->model('export/yandex_yml');
			$this->load->model('export/hotline_yml');
			$this->load->model('localisation/currency');
			$this->load->model('tool/image');
			$this->load->model('catalog/product');
			$this->load->model('catalog/manufacturer');
			
			$this->currencies = null;
			$this->categories = null;
			$this->offers = null;
			$this->shop = null;
			
			$this->currencies = array();
			$this->categories = array();
			$this->offers = array();
			$this->shop = array();
			
			$query = $this->db->non_cached_query("SELECT * FROM language"); 			
			foreach ($query->rows as $result) {
				$languages[$result['code']] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name'],
				'code'        => $result['code'],
				'locale'      => $result['locale'],
				'directory'   => $result['directory'],
				'filename'    => $result['filename']
				);
			}
			
			$file = DIR_REFEEDS . 'priceva.xml';
			
			$store_id = 0;			
			
			$this->setCurrency($offers_currency = 'RUB', 1);
			
			$shop_currency = $this->config->get('config_currency');
			$decimal_place = intval($this->currency->getDecimalPlace($offers_currency));						
			$currencies = $this->model_localisation_currency->getCurrencies();				
			$supported_currencies = array('RUR', 'RUB', 'USD', 'EUR', 'UAH');				
			$currencies = array_intersect_key($currencies, array_flip($supported_currencies));
			
			$this->setShop('name', $this->config->get('config_name'));				
			$this->setShop('company', $this->config->get('config_owner'));
			$this->setShop('url', $this->config->get('config_url'));
			$this->setShop('phone', $this->config->get('config_telephone'));
			$this->setShop('platform', 'Priceva YML Generator '.$this->config->get('config_name'));
			$this->setShop('version', YANDEX_YML_VERSION);			
			
			foreach ($currencies as $currency) {											
				if ($currency['code'] != $offers_currency && $currency['status'] == 1) {
					$this->setCurrency($currency['code'], number_format($this->currency->real_convert(1, $currency['code'],  $offers_currency), 2, '.', ''));
				}
			}
			
			$categories = $this->model_export_yandex_yml->getCategory();
			foreach ($categories as $category) {
				$this->setCategory($category['name'], $category['category_id'], $category['parent_id']);
			}	
			
			
			$priceva = fopen($file, 'w+');
			fwrite($priceva, '<?xml version="1.0" encoding="UTF-8"?>' . $this->eol);
			fwrite($priceva, '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . $this->eol);
			fwrite($priceva, '<yml_catalog date="' . date('Y-m-d H:i') . '">' . $this->eol);
			fwrite($priceva, '<shop>' . $this->eol);
			fwrite($priceva, $this->array2Tag($this->shop));
			
			fwrite($priceva, '<currencies>' . $this->eol);
			foreach ($this->currencies as $currency) {
				fwrite($priceva, $this->getElement($currency, 'currency'));
			}
			fwrite($priceva, '</currencies>' . $this->eol);
			
			fwrite($priceva, '<categories>' . $this->eol);
			foreach ($this->categories as $category) {
				$category_name = $category['name'];
				unset($category['name'], $category['export']);
				fwrite($priceva, $this->getElement($category, 'category', $category_name));
			}
			fwrite($priceva, '</categories>' . $this->eol);
			
			fwrite($priceva, '<offers>' . $this->eol);
			
			$products = $this->model_export_hotline_yml->getProductsForPriceva();
			echo '[i] Получили товары, всего ' . count($products) .  ' всего ' . count($products) . PHP_EOL;
			echo '[*] собираем мусор, освобождаем память: ' . $this->convert(memory_get_usage(true)) . PHP_EOL;
			gc_collect_cycles();
			
			$i = 0;
			foreach ($products as $product){			
				$item = $this->model_catalog_product->getProduct($product['product_id'], false);				
				if ($i%100 == 0){
					echo $i.'...';
				}
				$i++;
				
				if ($item['quantity'] == 0 && ($item['stock_status_id'] == $this->config->get('config_not_in_stock_status_id'))){
					$a = 'false';
					} else {
					$a = 'true';
				}
				
				fwrite($priceva, '<offer id="'. $item['product_id'] .'" available="' . $a . '">' . $this->eol);
				fwrite($priceva, '<categoryId>' . $this->model_catalog_product->getOneCategory($item['product_id']) . '</categoryId>' . $this->eol);
				fwrite($priceva, '<name><![CDATA[' . $this->rms2($item['name']) . ']]></name>' . $this->eol);
				fwrite($priceva, '<vendor><![CDATA[' . $this->rms2($item['manufacturer']) . ']]></vendor>' . $this->eol);
				fwrite($priceva, '<model><![CDATA[' . $item['model'] . ']]></model>' . $this->eol);											
				fwrite($priceva, '<vendorCode><![CDATA[' . $item['sku'] . ']]></vendorCode>' . $this->eol);
				fwrite($priceva, '<currencyId><![CDATA[' . $offers_currency . ']]></currencyId>' . $this->eol);				
				fwrite($priceva, '<url><![CDATA[' . $this->url->link('product/product', 'product_id=' . $item['product_id']) . ']]></url>' . $this->eol);
				
				if ($item['special'] > 0){
					fwrite($priceva, '<price><![CDATA[' . $this->currency->format($item['special'], '', '', false) . ']]></price>' . $this->eol);	
					fwrite($priceva, '<oldprice><![CDATA[' . $this->currency->format($item['price'], '', '', false) . ']]></oldprice>' . $this->eol);
					} else {
					fwrite($priceva, '<price><![CDATA[' . $this->currency->format($item['price'], '', '', false) . ']]></price>' . $this->eol);
				}
				
				if ($item['mpp_price'] > 0){
					fwrite($priceva, '<repricingMin><![CDATA[' . $this->currency->format($item['mpp_price'], '', '', false) . ']]></repricingMin>' . $this->eol);
				}
				
				$competitorUrls = explode(PHP_EOL, $item['competitors']);
				
				foreach ($competitorUrls as $competitorUrl){
					fwrite($priceva, '<competitorUrl><![CDATA[' . $competitorUrl . ']]></competitorUrl>' . $this->eol);	
				}			
				
				fwrite($priceva, '</offer>' . $this->eol);
				
			}
			
			
			fwrite($priceva, '</offers>' . $this->eol);
			fwrite($priceva, '</shop>' . $this->eol);
			fwrite($priceva, '</yml_catalog>' . $this->eol);
			
			
		}
		
		
		public function hotline(){			
			$this->load->model('export/yandex_yml');
			$this->load->model('export/hotline_yml');
			$this->load->model('localisation/currency');
			$this->load->model('tool/image');
			$this->load->model('catalog/product');
			$this->load->model('catalog/manufacturer');
			
			
			$this->currencies = null;
			$this->categories = null;
			$this->offers = null;
			$this->shop = null;
			
			$this->currencies = array();
			$this->categories = array();
			$this->offers = array();
			$this->shop = array();
			
			$query = $this->db->non_cached_query("SELECT * FROM language"); 			
			foreach ($query->rows as $result) {
				$languages[$result['code']] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name'],
				'code'        => $result['code'],
				'locale'      => $result['locale'],
				'directory'   => $result['directory'],
				'filename'    => $result['filename']
				);
			}
			
			$file = DIR_REFEEDS . 'hotline_full.xml';
			
			$store_id = 1;			
			
			$this->setCurrency($offers_currency = 'UAH', 1);
			
			$shop_currency = $this->config->get('config_currency');
			$decimal_place = intval($this->currency->getDecimalPlace($offers_currency));						
			$currencies = $this->model_localisation_currency->getCurrencies();				
			$supported_currencies = array('RUR', 'RUB', 'USD', 'EUR', 'UAH');				
			$currencies = array_intersect_key($currencies, array_flip($supported_currencies));
			
			foreach ($currencies as $currency) {											
				if ($currency['code'] != $offers_currency && $currency['status'] == 1) {
					$this->setCurrency($currency['code'], number_format($this->currency->real_convert(1, $currency['code'],  $offers_currency), 2, '.', ''));
				}
			}
			
			$this->config->set('config_url', HTTP_SERVER);
			$this->config->set('config_ssl', HTTPS_SERVER);
			// Settings
			$query = $this->db->non_cached_query("SELECT * FROM setting WHERE store_id = '0' OR store_id = '" . $store_id . "' ORDER BY store_id ASC");				
			foreach ($query->rows as $setting) {
				if (!$setting['serialized']) {
					$this->config->set($setting['key'], $setting['value']);
					} else {
					$this->config->set($setting['key'], unserialize($setting['value']));
				}
			}
			
			$this->config->set('config_store_id', $store_id);
			$this->config->set('config_language_id', $languages[$this->config->get('config_language')]['language_id']);
			$this->currency->set($this->config->get('config_regional_currency'));			
			
			
			$hotline = fopen($file, 'w+');
			fwrite($hotline, '<?xml version="1.0" encoding="UTF-8"?>' . $this->eol);
			fwrite($hotline, '<price>' . $this->eol);
			
			fwrite($hotline, '<date>');
			fwrite($hotline, date('Y-m-d H:i'));
			fwrite($hotline, '</date>' . $this->eol);
			
			fwrite($hotline, '<firmId>28513</firmId>'  . $this->eol);
			fwrite($hotline, '<firmName>' . $this->config->get('config_name') . '</firmName>'  . $this->eol);
			
			/*ЕСТЬ В НАЛИЧИИ*/
			//Доставка того, шо есть в наличии, курьером по Киеву
			fwrite($hotline, '<delivery id="1" type="address" carrier="SLF" cost="80" time="1" freeFrom="2000" region="01*-06*" />'  . $this->eol);
			//Доставка того, шо есть в наличии, курьером по Киевcкой области
			fwrite($hotline, '<delivery id="2" type="address" carrier="SLF" cost="350" time="1" freeFrom="25000" region="07*-09*" />' . $this->eol);
			//Доставка того, шо есть в наличии, Новой Почтой по Украине, адресная доставка
			fwrite($hotline, '<delivery id="3" type="address" carrier="NP" cost="null" time="1" freeFrom="25000" region="01*-99*" />' . $this->eol);
			//Доставка того, шо есть в наличии, Новой Почтой по Украине, на склад
			fwrite($hotline, '<delivery id="4" type="warehouse" carrier="NP" cost="null" time="1" freeFrom="25000" region="01*-99*" />' . $this->eol);
			
			/*НЕТ В НАЛИЧИИ*/
			fwrite($hotline, '<delivery id="5" type="address" carrier="SLF" cost="80" time="3" freeFrom="2000" region="01*-06*" />' . $this->eol);
			fwrite($hotline, '<delivery id="6" type="address" carrier="SLF" cost="350" time="3" freeFrom="25000" region="07*-09*" />' . $this->eol);
			fwrite($hotline, '<delivery id="7" type="address" carrier="NP" cost="null" time="3" freeFrom="25000" region="01*-99*" />' . $this->eol);
			fwrite($hotline, '<delivery id="8" type="warehouse" carrier="NP" cost="null" time="3" freeFrom="25000" region="01*-99*" />' . $this->eol);						
			
			fwrite($hotline,  '<categories>' . $this->eol);
			
			$categories = $this->model_export_yandex_yml->getCategory();
			foreach ($categories as $category) {
				$this->setCategory($category['name'], $category['category_id'], $category['parent_id']);
			}		
			
			foreach ($this->categories as $category) {
				fwrite($hotline, '<category>' . $this->eol);
				fwrite($hotline, '<id>' . $category['id'] . '</id>' . $this->eol);
				if (!empty($category['parentId'])){
					fwrite($hotline, '<parentId>' . $category['parentId'] . '</parentId>' . $this->eol);
				}
				fwrite($hotline, '<name>' . $category['name'] . '</name>' . $this->eol);
				fwrite($hotline, '</category>' . $this->eol);
			}
			
			fwrite($hotline, '</categories>' . $this->eol);
			fwrite($hotline, '<items>' . $this->eol);
			
			$products_1 = $this->model_export_hotline_yml->getProductsForHotline();
			$products_2 = $this->model_export_hotline_yml->getProductsForHotlineCategoryInStock(8307);
			
			$items = array();
			
			$i = 1;
			foreach ($products_1 as $product){						
				$items[$product['product_id']] = $this->model_catalog_product->getProduct($product['product_id']);
			}
			
			echo PHP_EOL;
			
			foreach ($products_2 as $product){
				if (!isset($items[$product['product_id']])){
					$items[$product['product_id']] = $this->model_catalog_product->getProduct($product['product_id']);
				}
			}
			
			echo '[i] Получили товары, всего ' . count($products_1) .  ' по параметрам, и ' . count($products_2) . ' в наличии, уникальных ' . count($items) . PHP_EOL;
			echo '[*] собираем мусор, освобождаем память: ' . $this->convert(memory_get_usage(true)) . PHP_EOL;
			gc_collect_cycles();
			
			foreach ($items as $item){
				fwrite($hotline, '<item>' . $this->eol);
				fwrite($hotline, '<id>' . $item['product_id'] . '</id>' . $this->eol);
				fwrite($hotline, '<categoryId>' . $this->model_catalog_product->getOneCategory($item['product_id']) . '</categoryId>' . $this->eol);
				fwrite($hotline, '<code>' . $item['model'] . '</code>' . $this->eol);
				if ($item['ean']){
					fwrite($hotline, '<barcode>' . $item['ean'] . '</barcode>' . $this->eol);
				}
				fwrite($hotline, '<vendor><![CDATA[' . $item['manufacturer'] . ']]></vendor>' . $this->eol);
				fwrite($hotline, '<name><![CDATA[' . $this->rms2($item['name']) . ']]></name>' . $this->eol);
				fwrite($hotline, '<description><![CDATA[' . $this->prepareField($item['description']) . ']]></description>' . $this->eol);
				fwrite($hotline, '<url><![CDATA[' . $this->url->link('product/product', 'product_id=' . $item['product_id']) . ']]></url>' . $this->eol);
				fwrite($hotline, '<image><![CDATA[' . $this->model_tool_image->resize($item['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) . ']]></image>' . $this->eol);
				
				if ($item['special']){
					fwrite($hotline, '<priceRUAH><![CDATA[' . preg_replace('/[^0-9.]+/', '', $this->currency->format($item['special'])) . ']]></priceRUAH>' . $this->eol);
					} else {
					fwrite($hotline, '<priceRUAH><![CDATA[' . preg_replace('/[^0-9.]+/', '', $this->currency->format($item['price'])) . ']]></priceRUAH>' . $this->eol);
				}
				
				if ($item['quantity_stockK']){
					fwrite($hotline, '<stock>В наличии</stock>' . $this->eol);
					fwrite($hotline, '<delivery id="1" />' . $this->eol);
					fwrite($hotline, '<delivery id="2" />' . $this->eol);
					fwrite($hotline, '<delivery id="3" />' . $this->eol);
					fwrite($hotline, '<delivery id="4" />' . $this->eol);
					} else {
					fwrite($hotline, '<stock>В наличии</stock>' . $this->eol);
					fwrite($hotline, '<delivery id="5" />' . $this->eol);
					fwrite($hotline, '<delivery id="6" />' . $this->eol);
					fwrite($hotline, '<delivery id="7" />' . $this->eol);
					fwrite($hotline, '<delivery id="8" />' . $this->eol);
				}
				
				fwrite($hotline, '<condition>0</condition>' . $this->eol);
				
				fwrite($hotline, '<param name="Оригинальность"><![CDATA[Оригинал]]></param>' . $this->eol);
				if ($item['location']){
					fwrite($hotline, '<param name="Страна изготовления"><![CDATA[' . $item['location'] . ']]></param>' . $this->eol);
				}
				
				fwrite($hotline, '</item>' . $this->eol);
			}
			
			fwrite($hotline, '</items>' . $this->eol);
			fwrite($hotline, '</price>' . $this->eol);
			
			
			fclose($hotline);				
			
		}
	}														