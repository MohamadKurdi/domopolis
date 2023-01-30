<?

namespace hobotix\Amazon;

class InfoUpdater extends RainforestRetriever
{

	const CLASS_NAME = 'hobotix\\Amazon\\InfoUpdater';
	
	private $lengthCache = false;
	private $weightCache = false;	

	private $removeFromName = [
		'N/A',
		'1x',
		'1X',
		'включаючи ПДВ',
		'1 шт.',
		'1 шт'
	];

	private $removeFromReview = [
		'Читайте далі',
		'читайте далі',
		'Читати далі',
		'читати далі',
		'Докладно',
		'Lesen Sie weiter',
		'Sie weiter',
		'lessen Sie weiter',
		'продовжуйте читати'
	];

	public const descriptionsQueryLimit = 5000;

	
	public function __construct($registry){

		parent::__construct($registry);
		$this->setDimensionsCache();

	}

	public function getTotalNames(){
		return $this->db->query("SELECT COUNT(*) as total FROM product_description WHERE language_id = '" . $this->config->get('config_language_id') . "' AND name <> '' ")->row['total'];
	}

	public function getNames($start){
		$sql = "SELECT product_id, name, language_id FROM product_description WHERE name <> '' AND language_id = '" . $this->config->get('config_language_id') . "' ORDER BY product_id ASC limit " . (int)$start . ", " . (int)self::descriptionsQueryLimit;		
		$query = $this->db->ncquery($sql);

		return $query->rows;
	}

	public function getTotalAttributes(){
		return $this->db->query("SELECT COUNT(*) as total FROM product_attribute WHERE language_id = '" . $this->config->get('config_language_id') . "' AND `text` <> '' ")->row['total'];
	}

	public function getAttributes($start){
		$sql = "SELECT product_id, attribute_id, language_id, `text` FROM product_attribute WHERE `text` <> '' AND language_id = '" . $this->config->get('config_language_id') . "' ORDER BY product_id ASC limit " . (int)$start . ", " . (int)self::descriptionsQueryLimit;		
		$query = $this->db->ncquery($sql);

		return $query->rows;
	}

	public function normalizeProductAttributeText($text){

		//Убираем все кавычки, и другие непонятные спецсимволы, из-за них потом проблемы
		$text = str_replace(['"', ',,', '?'], '', $text);

		//Кавычки и другие символы, одинарная кавычка только с пробелом, потому что иначе это апостроф
		$text = str_replace(["&amp;", "' ", "( "], ['&', ' ', '('], $text);

		//Упоминания Amazon
		$text = str_ireplace(["Amazon", "amazon", "Амазон"], ['Domopolis'], $text);

		//Кавычка в начале - точно не апостроф
		$text = ltrim($text, "'");

		//Заданные строки
		$text = str_ireplace($this->removeFromReview, [''], $text);
		
		//Убрать всё остальное кроме нужных букв, цифр и символов
		$text = preg_replace('/[^a-zA-Z0-9а-щА-ЩЬьЮюЯяЇїІіЄєҐґ:()\-,&Ø\'\.\/\* ]/mui', '', $text, -1);

		//Убираем двойные пробелы
		$text = str_replace(['  '], [' '], $text);
		$text = trim($text);

		return $text;
	}

	public function normalizeProductReview($review){				
		//Убираем все кавычки, и другие непонятные спецсимволы, из-за них потом проблемы
		$review = str_replace(['"', ',,', '?'], '', $review);

		//Кавычки и другие символы, одинарная кавычка только с пробелом, потому что иначе это апостроф
		$review = str_replace(["&amp;", "' ", "( "], ['&', ' ', '('], $review);

		//Упоминания Amazon
		$review = str_ireplace(["Amazon", "amazon", "Амазон"], ['Domopolis'], $review);

		//Кавычка в начале - точно не апостроф
		$review = ltrim($review, "'");

		//Заданные строки
		$review = str_ireplace($this->removeFromReview, [''], $review);
		
		//Убрать всё остальное кроме нужных букв, цифр и символов
		$review = preg_replace('/[^a-zA-Z0-9а-щА-ЩЬьЮюЯяЇїІіЄєҐґ()\-,&Ø\'\.\/\* ]/mui', '', $review, -1);

		//Убираем двойные пробелы
		$review = str_replace(['  '], [' '], $review);
		$review = trim($review);

		return $review;
	}
	
	public function normalizeProductName($name){
		echoLine('[InfoUpdater::normalizeProductName] New name: ' . $name, 'w');
		
		//Убираем все кавычки, и другие непонятные спецсимволы, из-за них потом проблемы
		$name = str_replace(['"', ',,', '?', '()', '( )'], '', $name);

		//Кавычки и другие символы, одинарная кавычка только с пробелом, потому что иначе это апостроф
		$name = str_replace(["&amp;", "' ", "( ", " )", '(-', '-)'], ['&', ' ', '(', ' )', '(', ')'], $name);

		//Упоминания Amazon
		$name = str_ireplace(["Amazon", "amazon", "Амазон", "амазон", "Амазонов", "амазонов", "амазоней"], ['Domopolis'], $name);

		//Кавычка в начале - точно не апостроф
		$name = ltrim($name, "'");

		//Заданные строки
		$name = str_ireplace($this->removeFromName, [''], $name);
		
		//Убрать всё остальное кроме нужных букв, цифр и символов
		$name = preg_replace('/[^a-zA-Z0-9а-щА-ЩЬьЮюЯяЇїІіЄєҐґ()\-,&Ø\'\.\/\* ]/mui', '', $name, -1);

		//Убираем двойные пробелы
		$name = str_replace(['  '], [' '], $name);
		$name = trim($name);

		//Находим первое вхождение кириллических символов или цифр
		$cyrfirst = strpbrk($name, 'АаБбВвГгҐґДдЕеЄєЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЬьЮюЯя0123456789абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ');
		
		//Если есть, то обрезаем строку с начала до конца (типа убираем латиницу из начала)
		if ($cyrfirst){
			$name = $cyrfirst;
		}

		//Обрезать пробелы
		$name = trim($name);
		
		//Логика штук		
		for($i=200; $i>=1; $i--){
			$array_from = $array_to = [];
			$to_str = ($i . ' шт. ');

			$array_from[] = $i . 'штук';
			$array_from[] = $i . 'ШТУК';
			$array_from[] = $i . 'Штук';
			$array_from[] = $i . ' штук';
			$array_from[] = $i . ' ШТУК';
			$array_from[] = $i . ' Штук';

			$array_from[] = $i . 'Шт';
			$array_from[] = $i . 'Шт.';
			$array_from[] = $i . ' Шт';
			$array_from[] = $i . ' Шт.';

			$array_from[] = $i . 'шт';
			$array_from[] = $i . ' шт';
			
			$array_from[] = $i . ' ШТ';
			$array_from[] = $i . ' ШТ.';
			$array_from[] = $i . 'ШТ';
			$array_from[] = $i . 'ШТ.';

			$array_from[] = $i . 'X ';
			$array_from[] = $i . 'x ';
			$array_from[] = $i . 'X ';
			$array_from[] = $i . 'x ';
			$array_from[] = $i . 'Х ';
			$array_from[] = $i . 'х ';		

			for ($k=0; $k<count($array_from); $k++){
				$array_to[]  = $to_str;
			}

			$name = str_ireplace($array_from, $array_to, $name);		
		}				
		
		$name = trim($name);

		//Переносим штуки назад, в случае если они спереди и с иксом
		for($i=200; $i>=1; $i--){
			foreach ([($i . ' x '), ($i . ' Х '), ($i . 'x '), ($i . 'Х ')] as $str_from){
				$str2 = ($i . ' шт. ');
				
				if (mb_stripos($name, $str_from) === 0){
					$name = str_ireplace($str_from, '', $name);
					$name = trim($name);
					$name = $name . ', ' . $str2;
					break;
				}
			}
		}		

		//Переносим штуки назад
		for($i=200; $i>=1; $i--){
			$str = ($i . ' шт. ');
				
			if (mb_stripos($name, $str) === 0){
				$name = mb_substr($name, mb_strlen($str));
				$name = trim($name);
				$name = $name . ', ' . $str;
				break;
			}
		}

		//Могли образоваться двойные точки и пробелы
		$name = str_replace(['. .', '..', ' . ', ',.', '  ', ',,'], ['.', '.', '. ', ', ', ' ', ','], $name);
		$name = rtrim($name);					
		$name = rtrim($name, ',');					

		//Находим первое вхождение кириллических символов или цифр
		$cyrfirst = strpbrk($name, 'АаБбВвГгҐґДдЕеЄєЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЬьЮюЯяабвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ');
		$cyrfirstpos = strcspn($name, 'АаБбВвГгҐґДдЕеЄєЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЬьЮюЯяабвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ');

		if ($cyrfirstpos){
			$pre = mb_substr($name, 0, $cyrfirstpos);
			$pre = trim($pre);
		}
		
		//Если есть, то обрезаем строку с начала до конца (типа убираем все символы из начала)
		if ($cyrfirst){
			$name = $cyrfirst;
		}

		//Если есть еще что-то впереди, то переносим его назад
		if (!empty($pre)){
			$name = $name . ', ' . $pre;
		}

		//Переносим штуки назад еще блядь один раз, на тот случай, если они были впереди
		$str = ('Шт.');
		if (mb_stripos($name, $str) === 0){
			$name = mb_substr($name, mb_strlen($str));
			$name = trim($name);
			$name = $name . ' шт.';
		}

		$str = ('См.');
		if (mb_stripos($name, $str) === 0){
			$name = mb_substr($name, mb_strlen($str));
			$name = trim($name);
		}

		$str = ('См ');
		if (mb_stripos($name, $str) === 0){
			$name = mb_substr($name, mb_strlen($str));
			$name = trim($name);
		}

		//Находим первое вхождение кириллических символов или цифр
		$cyrfirst = strpbrk($name, 'АаБбВвГгҐґДдЕеЄєЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЬьЮюЯяабвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ');
		
		//Если есть, то обрезаем строку с начала до конца (типа убираем латиницу из начала)
		if ($cyrfirst){
			$name = $cyrfirst;
		}

		//Обрезать пробелы
		$name = trim($name);

		$name = str_replace(['. .', '..', ' . ', ',.', '  ', ',,', ', .', ' .'], ['.', '.', '. ', ', ', ' ', ',', '', ''], $name);

		//Обрезать пробелы
		$name = trim($name);					
		$name = rtrim($name, ', ');
		$name = rtrim($name);

		$name = ltrim($name, '?,. ');
		$name = ltrim($name);		

		//Первая буква - большая, функция своя, в хелпере utf8
		$name = \mb_ucfirst($name);

		echoLine('[InfoUpdater::normalizeProductName] New name: ' . $name, 's');		

		return $name;
	}


	public function setProductIsFilledFromAmazon($product_id){
		$this->db->query("UPDATE product SET filled_from_amazon = 1 WHERE product_id = '" . (int)$product_id . "'");

		return $this;
	}

	public function enableProduct($product_id){
		$this->db->query("UPDATE product SET status = 1 WHERE product_id = '" . (int)$product_id . "' AND filled_from_amazon = 1 AND product_id IN (SELECT product_id FROM product_to_category WHERE category_id IN (SELECT category_id FROM category WHERE status = 1))");

		return $this;
	}

	public function setDescriptionIsFilledFromAmazon($product_id){
		$this->db->query("UPDATE product SET description_filled_from_amazon = 1 WHERE product_id = '" . (int)$product_id . "'");

		return $this;
	}

	public function deleteLoadedAmazonData($asin){
		$this->db->query("DELETE FROM product_amzn_data WHERE asin LIKE ('" . $this->db->escape($asin) . "')");

		return $this;
	}

	public function createAsinCacheFileName($asin){
		$path      = 'asin/' . substr($asin,0,3) . '/' . substr($asin, 3, 3) . '/';
		$directory = DIR_CACHE . $path;

		if (!is_dir($directory)){
			mkdir($directory, 0775, true);
		}

		$filename  = $asin . '.json'; 

		return [
			'full' => $directory . $filename,
			'path' => $path . $filename
		];
	}

	public function putAsinDataToFileCache($asin, $json){

		$file = $this->createAsinCacheFileName($asin);
		file_put_contents($file['full'], $json);

		return $file['path'];

	}

	public function updateProductAmznData($product, $updateDimensions = true){
		
		if ($this->config->get('config_enable_amazon_asin_file_cache')){

			$file = $this->putAsinDataToFileCache($product['asin'], $product['json']);

			$sql = "INSERT INTO product_amzn_data SET
			product_id = '" . (int)$product['product_id'] . "', 
			asin = '" . $this->db->escape($product['asin']) . "',
			file = '" . $this->db->escape($file) . "',
			json = NULL
			ON DUPLICATE KEY UPDATE
			asin = '" . $this->db->escape($product['asin']) . "',
			file = '" . $this->db->escape($file) . "',
			json = NULL";

		} else {

			$sql = "INSERT INTO product_amzn_data SET
			product_id = '" . (int)$product['product_id'] . "', 
			asin = '" . $this->db->escape($product['asin']) . "',
			json = '" . $this->db->escape($product['json']) . "'
			ON DUPLICATE KEY UPDATE
			asin = '" . $this->db->escape($product['asin']) . "',
			json = '" . $this->db->escape($product['json']) . "'";

		}

		$this->db->query($sql);

		if ($updateDimensions){
			$product['json']['product_id'] = $product['product_id'];
			$this->parseAndUpdateProductDimensions($product['json']);
		}

		return $this;
	}

	public function setDimensionsCache(){
		if (!$this->weightCache) {
			$this->weightCache = [];

			$query = $this->db->query("SELECT * FROM weight_class WHERE 1");

			foreach ($query->rows as $row){
				if ($row['amazon_key']){
					$this->weightCache[$row['amazon_key']] = $row;
				}

				if ($row['variants'] && is_array(explode(',', $row['variants']))){
					foreach (explode(',', $row['variants']) as $variant){
						$this->weightCache[$variant] = $row;
					}
				}
			}
		}

		if (!$this->lengthCache) {
			$this->lengthCache = [];

			$query = $this->db->query("SELECT * FROM length_class WHERE 1");

			foreach ($query->rows as $row){
				if ($row['amazon_key']){
					$this->lengthCache[$row['amazon_key']] = $row;
				}

				if ($row['variants'] && is_array(explode(',', $row['variants']))){
					foreach (explode(',', $row['variants']) as $variant){
						$this->lengthCache[$variant] = $row;
					}
				}
			}
		}
	}


	/*
	 THIS WORKS ONLY FOR 10x10x10 Cm; 5 kg - this is standard Amazon dimension string, this is old function
	 THIS MUST be rewritten to regular expressions
	*/
	public function parseDimesionsString($string){
		$string = mb_strtolower(atrim($string));
		$exploded1 = explode(';', $string);

		if (count($exploded1) != 2){
			return false;
		}

		$exploded_length = explode('x', atrim($exploded1[0]));

		$exploded_weight_class = explode(' ', atrim($exploded1[1]));
		$exploded_length_class = explode(' ', atrim($exploded_length[2]));

		if (count($exploded_length) != 3 || count($exploded_weight_class) != 2 || count($exploded_length_class) != 2){
			return false;
		}

		$length_class_id = 0;
		$weight_class_id = 0;
		if (!empty($this->weightCache[atrim($exploded_weight_class[1])])){
			$weight_class_id = $this->weightCache[atrim($exploded_weight_class[1])]['weight_class_id'];
		}

		if (!empty($this->lengthCache[atrim($exploded_length_class[1])])){
			$length_class_id = $this->lengthCache[atrim($exploded_length_class[1])]['length_class_id'];
		}

		$weight = (float)atrim($exploded_weight_class[0]);
		if (!$weight_class_id){
			echoLine('[InfoUpdater::parseAndUpdateProductDimensions] Не найдена единица измерения веса: ' . atrim($exploded_weight_class[1]));
			$weight = 0;
		}

		$length = (float)atrim($exploded_length[0]);
		$width 	= (float)atrim($exploded_length[1]);
		$height = (float)atrim($exploded_length[2]);

		if (!$length_class_id){
			echoLine('[InfoUpdater::parseAndUpdateProductDimensions] Не найдена единица измерения размера: ' . atrim($exploded_length_class[1]));

			$length = 0;
			$width 	= 0;
			$height = 0;
		}

		if ($length + $width + $height + $weight == 0){
			return false;
		}

		return [
			'length' 			=> $length,
			'width' 			=> $width,
			'height' 			=> $height,
			'weight'			=> (float)atrim($exploded_weight_class[0]),
			'length_class_id' 	=> $length_class_id,
			'weight_class_id' 	=> $weight_class_id,
		];
	}


	public function tryToParseDimensionStringExtended($dimensionString, $type){
		$dimensionString = trim($dimensionString);

		//All, attribute that contains the same as in 'dimensions'
		if ($type == 'all'){
				//43.1 x 16.7 x 12.5 cm 480 Gramm, and also standard string, actually this is regexp implementation of parseDimesionsString 
			if (preg_match('/^[0-9]+[.,]?[0-9]* [x] [0-9]+[.,]?[0-9]* [x] [0-9]+[.,]?[0-9]* [a-zA-Zäа-яА-Я]+[;]? [0-9]+[.,]?[0-9]* [a-zA-Zäа-яА-Я]+$/', $dimensionString, $matches) == 1){
				preg_match_all('/[0-9]+[.,]?[0-9]*/', $dimensionString, $dimensions);
				preg_match_all('/[a-zA-Zäа-яА-Я]+/', $dimensionString, $classes);

				if (!empty($dimensions[0]) && count($dimensions[0]) == 4 && !empty($classes[0]) && count($classes[0]) == 4){
					$length 		= str_replace(',', '.', $dimensions[0][0]);
					$width 			= str_replace(',', '.', $dimensions[0][1]);
					$height 		= str_replace(',', '.', $dimensions[0][2]);
					$length_class 	= mb_strtolower($classes[0][2]);

					$weight 		= str_replace(',', '.', $dimensions[0][3]);
					$weight_class 	= mb_strtolower($classes[0][3]);

					$length_class_id = 0;
					if (!empty($this->lengthCache[atrim($length_class)])){
						$length_class_id = $this->lengthCache[atrim($length_class)]['length_class_id'];
					}

					$weight_class_id = 0;
					if (!empty($this->weightCache[atrim($weight_class)])){
						$weight_class_id = $this->weightCache[atrim($weight_class)]['weight_class_id'];
					}

					if ($length && $length_class_id && $weight && $weight_class_id){
						echoLine('[InfoUpdater::tryToParseDimensionStringExtended] Length and class found: ' . $length . ' ' . $length_class . ' -> ' . $length_class_id);
						echoLine('[InfoUpdater::tryToParseDimensionStringExtended] Weight and class found: ' . $weight . ' ' . $weight_class . ' -> ' . $weight_class_id);

						return [
							'length' 			=> (float)$length,
							'width' 			=> (float)$width,
							'height' 			=> (float)$height,
							'length_class_id' 	=> (int)$length_class_id,
							'weight' 			=> (float)$weight,
							'weight_class_id' 	=> (int)$weight_class_id
						];
					}
				}
			}
		}		

		//Just Weight
		if ($type == 'weight'){
			//6.7 Kilogramm or any other like that, float + text
			if (preg_match('/^[0-9]+[.,]?[0-9]* [a-zA-Zäа-яА-Я]+$/', $dimensionString, $matches) == 1){
				preg_match('/^[0-9]+[.,]?[0-9]*/', $dimensionString, $weight);
				preg_match('/[a-zA-Zäа-яА-Я]+$/', $dimensionString, $weight_class);

				$weight 		= str_replace(',', '.', $weight[0]);
				$weight_class 	= mb_strtolower($weight_class[0]);

				$weight_class_id = 0;
				if (!empty($this->weightCache[atrim($weight_class)])){
					$weight_class_id = $this->weightCache[atrim($weight_class)]['weight_class_id'];
				}

				if ($weight && $weight_class_id){
					echoLine('[InfoUpdater::tryToParseDimensionStringExtended] Weight and class found: ' . $weight . ' ' . $weight_class . ' -> ' . $weight_class_id);

					return [
						'weight' 			=> (float)$weight,
						'weight_class_id' 	=> (int)$weight_class_id
					];
				}
			}
		}

		//Just dimensions
		if ($type == 'dimensions'){
			//43.1 x 16.7 x 12.5 cm 
			if (preg_match('/^[0-9]+[.,]?[0-9]* [x] [0-9]+[.,]?[0-9]* [x] [0-9]+[.,]?[0-9]* [a-zA-Zäа-яА-Я]+$/', $dimensionString, $matches) == 1){
				preg_match_all('/[0-9]+[.,]?[0-9]*/', $dimensionString, $dimensions);
				preg_match_all('/[a-zA-Zäа-яА-Я]+$/', $dimensionString, $length_class);

				if (!empty($dimensions[0]) && count($dimensions[0]) == 3 && !empty($length_class[0]) && count($length_class[0]) == 1){
					$length 		= str_replace(',', '.', $dimensions[0][0]);
					$width 			= str_replace(',', '.', $dimensions[0][1]);
					$height 		= str_replace(',', '.', $dimensions[0][2]);
					$length_class 	= mb_strtolower($length_class[0][0]);

					$length_class_id = 0;
					if (!empty($this->lengthCache[atrim($length_class)])){
						$length_class_id = $this->lengthCache[atrim($length_class)]['length_class_id'];
					}

					if ($length && $length_class_id){
						echoLine('[InfoUpdater::tryToParseDimensionStringExtended] Length and class found: ' . $length . ' ' . $length_class . ' -> ' . $length_class_id);

						return [
							'length' 			=> (float)$length,
							'width' 			=> (float)$width,
							'height' 			=> (float)$height,
							'length_class_id' 	=> (int)$length_class_id
						];

					}
				}
			}
		}



		return false;
	}


	private function parseDimesionsAttributes($attributes){
		$result = [];

		foreach ($attributes as $attribute){
			if ($attribute_id = $this->model_product_cached_get->getAttribute($attribute['name'])){
				if ($attribute_info = $this->model_product_cached_get->getAttributeInfo($attribute_id)){
					if ($attribute_info['attribute_group_id'] == $this->config->get('config_dimensions_attr_id')){
						echoLine('[InfoUpdater::parseDimesionsAttributes] Found dimension attribute: ' . $attribute['name'] . ', value ' . $attribute['value'] . ', type ' . $attribute_info['dimension_type']);

						if ($attribute_info['dimension_type']){
							if ($parsed = $this->tryToParseDimensionStringExtended($attribute['value'], $attribute_info['dimension_type'])) {

								if ($attribute_info['dimension_type'] == 'all' && !empty($parsed['weight']) && !empty($parsed['length'])){
									$result['weight'] 			= $parsed['weight'];
									$result['weight_class_id'] 	= $parsed['weight_class_id'];
									$result['length'] 			= $parsed['length'];
									$result['width'] 			= $parsed['width'];
									$result['height'] 			= $parsed['height'];	
									$result['length_class_id']  = $parsed['length_class_id'];
								}

								if ($attribute_info['dimension_type'] == 'weight' && !empty($parsed['weight']) && empty($result['weight'])){
									$result['weight'] 			= $parsed['weight'];
									$result['weight_class_id'] 	= $parsed['weight_class_id'];
								}

								if ($attribute_info['dimension_type'] == 'dimensions'  && !empty($parsed['length']) && empty($result['length'])){
									$result['length'] 			= $parsed['length'];
									$result['width'] 			= $parsed['width'];
									$result['height'] 			= $parsed['height'];	
									$result['length_class_id']  = $parsed['length_class_id'];
								}

							}
						}
					}
				}
			}
		}

		return $result;
	}

	private function checkWeight($data){
		if (!empty($data['weight']) && !empty($data['weight_class_id'])){
			//Kilogramm > 100, it's a fail

			if ($data['weight_class_id'] == 1 && (float)$data['weight'] > 500){
				echoLine('[InfoUpdater::checkWeight] BAD WEIGHT DETECTED: ' . $data['weight']);	
				return false;
			}
		}

		return true;
	}


	private function updateProductDimensions($product, $data){
	//	return;

		if (!empty($data['weight']) && !empty($data['weight_class_id']) && $this->checkWeight($data)){
			echoLine('[InfoUpdater::updateProductDimensions] Weight: ' . $data['weight']);		
			echoLine('[InfoUpdater::updateProductDimensions] Weight class: ' . $data['weight_class_id']);

			$this->db->query("UPDATE product SET 
				weight 					= '" . (float)$data['weight'] . "', 
				weight_class_id 		= '" . (int)$data['weight_class_id'] . "',
				pack_weight 			= '" . (float)$data['weight'] . "',
				pack_weight_class_id 	= '" . (int)$data['weight_class_id'] . "'
				WHERE asin = '" . $this->db->escape($product['asin']) . "'");

			if (!empty($product['product_id'])){
				$this->db->query("UPDATE product SET 
				weight 					= '" . (float)$data['weight'] . "', 
				weight_class_id 		= '" . (int)$data['weight_class_id'] . "',
				pack_weight 			= '" . (float)$data['weight'] . "',
				pack_weight_class_id 	= '" . (int)$data['weight_class_id'] . "'
				WHERE product_id = '" . (int)$product['product_id'] . "'");
			}
		}

		if (!empty($data['length']) && !empty($data['width']) && !empty($data['height']) && !empty($data['length_class_id'])){					
			echoLine('[InfoUpdater::updateProductDimensions] Length: ' . $data['length']);		
			echoLine('[InfoUpdater::updateProductDimensions] Length class: ' . $data['length_class_id']);

			$this->db->query("UPDATE product SET
				length 					= '" . (float)$data['length'] . "',
				width					= '" . (float)$data['width'] . "',
				height					= '" . (float)$data['height'] . "',				
				length_class_id 		= '" . (int)$data['length_class_id'] . "',
				pack_length 			= '" . (float)$data['length'] . "',
				pack_width				= '" . (float)$data['width'] . "',
				pack_height				= '" . (float)$data['height'] . "',			
				pack_length_class_id 	= '" . (int)$data['length_class_id'] . "'			
				WHERE asin = '" . $this->db->escape($product['asin']) . "'");

			if (!empty($product['product_id'])){
				$this->db->query("UPDATE product SET
				length 					= '" . (float)$data['length'] . "',
				width					= '" . (float)$data['width'] . "',
				height					= '" . (float)$data['height'] . "',				
				length_class_id 		= '" . (int)$data['length_class_id'] . "',
				pack_length 			= '" . (float)$data['length'] . "',
				pack_width				= '" . (float)$data['width'] . "',
				pack_height				= '" . (float)$data['height'] . "',			
				pack_length_class_id 	= '" . (int)$data['length_class_id'] . "'			
				WHERE product_id = '" . (int)$product['product_id'] . "'");
			}
		}
	}

	private  static function prepareAttributesForParsing($attributes){
		$result = [];

		foreach ($attributes as $temp){
			$temp['name'] = atrim($temp['name']);
			$temp['value'] = atrim($temp['value']);

			$result[clean_string($temp['name'])] = [
				'name' 	=> $temp['name'],
				'value' => $temp['value']
			];
		}

		return $result;
	}

	public function parseAndUpdateProductDimensions($product){					
		if (!$product || (empty($product['dimensions']) && empty($product['attributes']) && empty($product['specifications']))){
			return false;
		}
		
		if (!empty($product['dimensions']) && $data = $this->parseDimesionsString(atrim($product['dimensions']))){	
			echoLine('[InfoUpdater::parseAndUpdateProductDimensions] Found dimensions ' . atrim($product['dimensions']));

			$this->updateProductDimensions($product, $data);
			return true;		
		} elseif (!empty($product['dimensions']) && $data = $this->tryToParseDimensionStringExtended(atrim($product['dimensions']), 'all')){	
			echoLine('[InfoUpdater::parseAndUpdateProductDimensions] Found dimensions ' . atrim($product['dimensions']));

			$this->updateProductDimensions($product, $data);
			return true;			
		} elseif (!empty($product['attributes']) && $data = $this->parseDimesionsAttributes(self::prepareAttributesForParsing($product['attributes']))) {
			echoLine('[InfoUpdater::parseAndUpdateProductDimensions] Found something from attributes');

			$this->updateProductDimensions($product, $data);
			return true;
		} elseif (!empty($product['specifications']) && $data = $this->parseDimesionsAttributes(self::prepareAttributesForParsing($product['specifications']))){
			echoLine('[InfoUpdater::parseAndUpdateProductDimensions] Found something from specifications');

			$this->updateProductDimensions($product, $data);
			return true;

		} else {

			echoLine('[InfoUpdater::parseAndUpdateProductDimensions] Could not parse dimensions for ' . $product['asin']);

		}

		return false;
	}

		//Работа с справочником товаров
	public function updateProductNotFoundOnAmazon($product_id){
		$this->db->query("UPDATE product SET amzn_not_found = 1 WHERE product_id = '" . $product_id . "'");			

		return $this;		
	}

	public function updateProductAmazonLastSearch($product_id){
		$this->db->query("UPDATE product SET amzn_last_search = NOW() WHERE product_id = '" . $product_id . "'");				

		return $this;
	}	

	public function setInvalidASIN($asin){
		$this->db->query("UPDATE product SET old_asin = asin WHERE asin = '" . $this->db->escape($asin) . "'");		
		$this->db->query("UPDATE product SET asin = 'INVALID' WHERE asin = '" .$this->db->escape($asin) . "'");

		return $this;
	}

	public function updateASINInDatabase($product){

		if ($product['asin'] == 'INVALID'){
			$this->db->query("UPDATE product SET old_asin = asin WHERE product_id = '" . $product['product_id'] . "'");
		}

		$this->db->query("UPDATE product SET asin = '" . $this->db->escape($product['asin']) . "' WHERE product_id = '" . $product['product_id'] . "'");				

		return $this;
	}						


	public function validateASINAndUpdateIfNeeded($product){

		if (empty($product['asin']) && empty($product['ean'])){
			return $product;
		}

		if (empty($product['asin'])){
			
			$rfRequests = [new \CaponicaAmazonRainforest\Request\ProductRequest($this->config->get('config_rainforest_api_domain_1'), null, ['customer_zipcode' => $this->config->get('config_rainforest_api_zipcode_1'), 'gtin' => $product['ean']])];
			$apiEntities = $this->rfClient->retrieveProducts($rfRequests);	

			if (!$apiEntities){
				return false;				
			}

			foreach ($apiEntities as $key => $rfProduct) {
				
				if ($rfProduct->getAsin()){

					$product['asin'] = $rfProduct->getAsin();						
					$this->updateASINInDatabase($product);

				}					
			}

		}


		return $product;
	}

}		