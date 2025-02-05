<?php

function loadAndRenameCatalogModels($path, $className = '', $classNameTo = ''){
	if ($classNameTo && !class_exists($classNameTo)){
		$modelCatalogModelContents = file_get_contents(DIR_CATALOG . $path);

		if ($className && $classNameTo){
			$modelCatalogModelContents = str_replace($className, $classNameTo, $modelCatalogModelContents);
		}

		$modelCatalogModelContents = str_replace('<?php', '', $modelCatalogModelContents);
		$modelCatalogModelContents = str_replace('<?', '', $modelCatalogModelContents);

		eval($modelCatalogModelContents);
	}
}

function loadAndRenameAnyModels($path, $className, $classNameTo){
	$modelAnyModelContents = file_get_contents($path);

	$modelAnyModelContents = str_replace($className, $classNameTo, $modelAnyModelContents);
	$modelAnyModelContents = str_replace('<?php', '', $modelAnyModelContents);
	$modelAnyModelContents = str_replace('<?', '', $modelAnyModelContents);

	eval($modelAnyModelContents);
}

function addQueryArgs(array $args, string $url){
	if (filter_var($url, FILTER_VALIDATE_URL)) {
		$urlParts = parse_url($url);
		if (isset($urlParts['query'])) {
			parse_str($urlParts['query'], $urlQueryArgs);
			$urlParts['query'] = http_build_query(array_merge($urlQueryArgs, $args));
			$newUrl = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'] . '?' . $urlParts['query'];
		} else {
			$newUrl = $url . '?' . http_build_query($args);
		}
		return $newUrl;

	} else {
		return $url;
	}
}

function extract_get_param(string $url, string $param){
	$url_components = parse_url($url);
	if(!isset($url_components['query'])) {
		return false;
	}

	parse_str($url_components['query'], $params);

	if(isset($params[$param])) {
		return $params[$param]; 
	} else {
		return false;
	}
}

function reparseCartProductsByStock($products){
	$results = array(
		'in_stock' 		=> [],
		'not_in_stock' 	=> [],
		'certificates' 	=> [],	
	);

	foreach ($products as $product){
		if ($product['is_certificate']){
			$results['certificates'][] = $product;
		} elseif ($product['current_in_stock']){
			$results['in_stock'][] = $product;
		} else {
			$results['not_in_stock'][] = $product;
		}
	}

	return $results;
}

function checkUniqueCoupon($coupon){
	return mb_stripos($coupon, '-%UNIQUE%-');
}

function formatUniqueCoupon($coupon, $string){
	$unique = mb_substr(md5($string), 0, 4);

	return str_replace('-%UNIQUE%-', ('-' . $unique . '-'), $coupon);
}

function recoverUniqueCoupon($coupon, $string){
	$unique = mb_substr(md5($string), 0, 4);

	return str_replace(('-' . $unique . '-'), '-%UNIQUE%-', $coupon);
}

function addTrackingToHTML($html, $tracking){
	libxml_use_internal_errors(true);

	$doc = new DOMDocument();
	$doc->encoding = 'UTF-8';
	$doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $html);

	$anchors = $doc->getElementsByTagName('a');
	foreach ($anchors as $anchor) {
		$href = $anchor->getAttribute('href');

		if ($href){

			$url = parse_url($href);

			if (isset($url['query'])) {    
				$url['query'] .= '&tracking=' . $tracking;
			} else {
				$url['query'] = 'tracking=' . $tracking; 
			}

			$newHref = $url['scheme'] . '://' . $url['host'] . $url['path'] . '?' . $url['query'];
			$anchor->setAttribute('href', $newHref);
		}
	}

	$html = $doc->saveHTML(); 
	return $html;
}

function reTemplate($array, $string){
	$from 	= [];
	$to  	= [];

	foreach ($array as $key => $value){
		$from[] = $key;
		$to[]	= $value;
	}
	
	if (!empty($string)){	
		$string = str_replace($from, $to, $string);
		$string = str_replace('  ', ' ', $string);
		$string = trim($string);
	}

	return $string;
}

function tryToGuessPageType($request){	
	if (!isset($request['route']) || $request['route'] == 'common/home'){
		return 'home';
	}

	if (!empty($request['route'])){		
		if ($request['route'] == 'product/product' && !empty($request['product_id'])){
			return 'product';
		}

		if ($request['route'] == 'product/manufacturer' && !empty($request['manufacturer_id'])){
			return 'manufacturer';
		}

		if ($request['route'] == 'information/information' && !empty($request['information_id'])){
			return 'information';
		}

		if ($request['route'] == 'product/collection' && !empty($request['collection_id'])){
			return 'collection';
		}
		
		if ($request['route'] == 'product/category' && !empty($request['path'])){
			return 'category';
		}	

		if ($request['route'] == 'information/actions' && !empty($request['actions_id'])){
			return 'action';
		}	
	}

	return false;
}

function validate_name(string $string, $words, &$reason = null):bool {    
    $rules = [];

    if (!is_array($words)){
    	$words = prepareEOLArray($words);
    }

    foreach ($words as $word){
    	$type = mb_substr($word, 0, 1);

    	switch ($type){
        	case '[':
        		$type = 'required';
        		break;

        	case '(':
        		$type = 'optional';
        		break;

        	case '{':
        		$type = 'excluded';
        		break;

        	default:
        		$type = 'pass';
        		break;
        }

        if (empty($rules[$type])){
        	$rules[$type] = [];
        }

        $keywords 	= explode(',', trim($word, '{}[]()'));
        $keywords   = array_map('trim', $keywords);
        $keywords 	= array_map('mb_strtolower', $keywords);

        $rules[$type] = array_merge($rules[$type], $keywords);
    }

    $string = mb_strtolower($string);
    echoLine('[validate_name] Validating string: ' . $string, 'i');

    foreach ($rules as $type => $keywords) {
        switch ($type) {
            case 'required':
                foreach ($keywords as $keyword) {
                    if (mb_stripos($string, $keyword) === false) {
                    	echoLine('[validate_name] No required keyword ' . $keyword, 'e');
                    	$reason = 'Нет обязательного вхождения слова ' . $keyword;
                        return false;
                    }
                }
                break;
            case 'optional':
                $matched = false;
                foreach ($keywords as $keyword) {
                    if (mb_stripos($string, $keyword) !== false) {
                        $matched = true;
                        break;
                    }
                }
                if (!$matched) {
                	echoLine('[validate_name] No any optional keyword: ' . implode(', ', $keywords), 'e');
                	$reason = 'Нет не одного вхождения необязательных слов: ' . implode(', ', $keywords);
                    return false;
                }
                break;
            case 'excluded':
                foreach ($keywords as $keyword) {
                    if (mb_stripos($string, $keyword) !== false) {
                    	echoLine('[validate_name] Forbidden keyword match ' . $keyword, 'e');
						$reason = 'Найдено запрещенное слово ' . $keyword;
                        return false;
                    }
                }
                break;
            default:
                break;
        }
    }

    $reason = 'Проверка пройдена, товар будет добавлен';
    return true;
}


function getproductname($item) {
	return $item['name'];
}

function createEOLArray(array $array){
    $string = '';

    foreach ($array as $line){
        $string .= ($line . PHP_EOL);
    }

    return $string;
}

function prepareEOLArray($string){
	$result = [];

	$array = explode(PHP_EOL, $string);

	foreach ($array as $line){
		if (trim($line) && mb_strlen(trim($line)) > 0){
			$result[] = trim($line);
		}
	}

	return $result;
}

function checkAndFormatMultiAttributes($string, $separator, $type = 'ul'){
	$result = '';

	$exploded = explode($separator, $string);

	if (count($exploded) == 1){
		$result = $string;
	} else {
		if ($type == 'ul'){
			$result .= '<ul class="multi-attribute-ul">';

			foreach ($exploded as $line){
				$result .= '<li>' . trim($line) . '</li>';
			}

			$result .= '</ul>';
		} elseif ($type = 'comma'){
			$result = implode(', ', $exploded);
		}
	}


	return $result;
}

function countPercentByParam($num, $param_array, $percent_array)
{
	if ($num <= $param_array[0]) {
		return $percent_array[0];
	}

	if ($num <= $param_array[1]) {
		return $percent_array[1];
	}

	return $percent_array[2];
}

function countValueByParam($num, $param_value, $param_array, $percent_array)
{
	if ($param_value <= $param_array[0]) {
		return ($num / 100) * $percent_array[0];
	}

	if ($param_value <= $param_array[1]) {
		return ($num / 100) * $percent_array[1];
	}

	return ($num / 100) * $percent_array[2];
}

function tdClass($num, $param1, $param2, $param3)
{

	if ($num <= $param1) {
		return '_red';
	}

	if ($num <= $param2) {
		return '_orange';
	}

	if ($num <= $param3) {
		return '_green';
	}

	return '_green';
}

function tdClassRev($num, $param1, $param2, $param3)
{

	if ($num <= $param1) {
		return '_green';
	}

	if ($num <= $param2) {
		return '_orange';
	}

	if ($num <= $param3) {
		return '_red';
	}

	return '_green';
}

function array_insert2(&$array, $pos, $insert){
	$array = array_merge(array_slice($array, 0, $pos), [$insert], array_slice($array, $pos));
}

function checkIfGetParamIsArray($param){
	if (is_array($param)){
		return '';
	}

	return $param;
}

function generateRandomEmail($host, $user = 'guest'){
	$host = parse_url($host, PHP_URL_HOST);

	return $user . microtime(true) . '@' . $host;
}

function generateRandomUppercaseString($length = 4) {
	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}

function generateRandomString($length = 50) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}

function generateRandomPin($length = 6) {
	$characters = '0123456789';
	$randomPin = '';
	for ($i = 0; $i < $length; $i++) {
		$randomPin .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomPin;
}

if (!function_exists('cmp')) {
	function cmp($a,$b){
		return ($a['sort']<$b['sort'])?-1:1;
	}
}

if (!function_exists('com_create_guid')) {
	function com_create_guid() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0fff ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}
}

function sortByOrder($a, $b){
	return $a["sort_order"] > $b["sort_order"];
}

if (!function_exists('is_cli')){
	function is_cli(){
		return (php_sapi_name() == 'cli');	
	}
}

if (!function_exists('echoLine')){
	function echoLine($line){
		if (is_cli()){
			echo $line . PHP_EOL;
		}
	}
}