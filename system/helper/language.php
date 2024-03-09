<?php

function strpos_offset($needle, $haystack, $occurrence) {			
	$arr = explode($needle, $haystack);			
	switch($occurrence) {
		case $occurrence == 0:
		return false;
		case $occurrence > max(array_keys($arr)):
		return false;
		default:
		return strlen(implode($needle, array_slice($arr, 0, $occurrence)));
	}
}

function bool_real_stripos($haystack, $needle){			
	return !(stripos($haystack, $needle) === false);			
}

function detectUnits($attribute) {
	$attribute['name'] = trim(strip_tags($attribute['name']));
	$attribute['text'] = trim(strip_tags($attribute['text']));

	if (preg_match('/\(([^\)]+)\)$/mi', $attribute['name'], $matches)) {
		$attribute['name'] = trim(str_replace('('.$matches[1].')', '', $attribute['name']));
		$attribute['unit'] = trim($matches[1]);
	}

	return $attribute;
}		

function parseText($node, $keyword, $dom, $link, $target = '', $tooltip = 0){
	if (mb_strpos($node->nodeValue, $keyword) !== false) {
		$keywordOffset 	= mb_strpos($node->nodeValue, $keyword, 0, 'UTF-8');
		$newNode 		= $node->splitText($keywordOffset);
		$newNode->deleteData(0, mb_strlen($keyword, 'UTF-8'));
		$span = $dom->createElement('a', $keyword);
		if ($tooltip) {
			$span->setAttribute('href', '#');
			$span->setAttribute('style', 'text-decoration:none');
			$span->setAttribute('class', 'title');
			$span->setAttribute('title', $keyword . '|' . $link);
		} else {
			$span->setAttribute('href', $link);
			$span->setAttribute('target', $target);
		}

		$node->parentNode->insertBefore($span, $newNode);
        parseText($newNode, $keyword, $dom, $link, $target, $tooltip);
	}
}

function num2str($num, $currency_morph = array('рубль','рубля','рублей',0)) {
	$nul='ноль';
	$ten=array(
		array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
		array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
	);
	$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
	$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
	$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
	$unit=array();
	$unit[] = array('копейка' ,'копейки' ,'копеек',	 1);
	$unit[] = $currency_morph;		
	$unit[] = array('тысяча'  ,'тысячи'  ,'тысяч'     ,1);
	$unit[] = array('миллион' ,'миллиона','миллионов' ,0);
	$unit[] = array('миллиард','милиарда','миллиардов',0);

	list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
	$out = array();
	if (intval($rub)>0) {
		foreach(str_split($rub,3) as $uk=>$v) {
			if (!intval($v)) continue;
			$uk = sizeof($unit)-$uk-1;
			$gender = $unit[$uk][3];
			list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
			$out[] = $hundred[$i1];
			if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3];
			else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3];

			if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
		}
	} else  { 
		$out[] = $nul;
	}

	$out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]);
	$_kop = ', ' . $kop.' '. morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]);
	return mb_ucfirst(trim(preg_replace('/ {2,}/', ' ', join(' ', $out) . $_kop)));
}

function num2strUA($num, $currency_morph = array('гривня','гривні','гривень',0)) {
	$nul='нуль';
	$ten=array(
		array('','один','два','три','чотири','п`ять','шість','сім', 'вісім','дев`ять'),
		array('','одна','дві','три','четыре','п`ять','шість','сім', 'вісім','дев`ять'),
	);
	$a20=array('десять','одиннадцять','дванадцять','тринадцять','чотирнадцять' ,'п`ятнадцять','шістнадцять','сімнадцять','вісімнадцять','дев`ятнадцять');
	$tens=array(2=>'двадцять','тридцять','сорок','п`ятдесят','шістдесят','сімьдесят' ,'вісімдесят','дев`яносто');
	$hundred=array('','сто','двісті','триста','чотириста','п`ятсот','шістсот', 'сімсот','вісімсот','дев`ятсот');
	$unit=array();
	$unit[] = array('копійка' ,'копійки' ,'копійок',	 1);
	$unit[] = $currency_morph;		
	$unit[] = array('тисяча'  ,'тисячі'  ,'тисяч'     ,1);
	$unit[] = array('мільйон' ,'мильйона','мильйонів' ,0);
	$unit[] = array('мільярд','мильярда','мильярдів',0);

	list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
	$out = array();
	if (intval($rub)>0) {
		foreach(str_split($rub,3) as $uk=>$v) {
			if (!intval($v)) continue;
			$uk = sizeof($unit)-$uk-1;
			$gender = $unit[$uk][3];
			list($i1,$i2,$i3) = array_map('intval',str_split($v,1));

			$out[] = $hundred[$i1];
			if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3];
			else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3];

			if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
		}
	} else { 
		$out[] = $nul;
	}
	$out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]);
	$_kop = ', ' . $kop.' '. morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]);
	return mb_ucfirst(trim(preg_replace('/ {2,}/', ' ', join(' ', $out) . $_kop)));
}

function morph($n, $f1, $f2, $f5) {
	$n = abs(intval($n)) % 100;
	if ($n>10 && $n<20) return $f5;
	$n = $n % 10;
	if ($n>1 && $n<5) return $f2;
	if ($n==1) return $f1;
	return $f5;
}

function parseAmazonDeliveryDateToEnglish($date){
	$de_months 		= ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
	$de_months2 	= ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'];
	$en_months  	= ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

	$de_days 	= ['Montag', 'Morgen', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonnabend', 'Sonntag'];
	$de_days2 	= ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];

	$de_remove	= ['frühestens'];

	foreach ($de_days as $de_day){
		$date = str_ireplace(($de_day.','), '', $date);
	}

	foreach ($de_days2 as $de_day){
		$date = str_ireplace(($de_day.'.,'), '', $date);
	}

	$date = str_ireplace($de_months, $en_months, $date);
	$date = str_ireplace($de_remove, '', $date);
	$date = trim($date);

	return $date;
}

function convertSize($size){
	$unit=array('b','kb','mb','gb','tb','pb');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

function getAmazonDomainsList(){
	return [
		'amazon.com.br','amazon.ca','amazon.com.mx','amazon.com','amazon.cn','amazon.in','amazon.co.jp','amazon.sg','amazon.ae','amazon.sa','amazon.fr','amazon.de','amazon.it','amazon.nl','amazon.pl','amazon.es','amazon.se','amazon.com.tr','amazon.co.uk','amazon.com.au'
	];
}

function simple_translit($string){
	$from = array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','і','є');
	$to = array('a','b','v','g','d','e','yo','zh','z','i','j','k','l','m','n','o','p','r','s','t','u','f','h','ts','ch','sh','sch','','y','','e','yu','ya','A','B','V','G','D','E','YO','ZH','Z','I','J','K','L','M','N','O','P','R','S','T','U','F','KH','TS','CH','SH','SCH','','Y','','E','YU','YA','i','ie');

	return str_replace($from, $to, $string);
}

function getDeliveryCompany($delivery_code, $second = false){
	return $delivery_code;
}

function getFreeDeliveryInfo($shippingSettings){
	if (!empty($shippingSettings['sumrate'])){
		$exploded = explode(',', $shippingSettings['sumrate']);

		foreach ($exploded as $info){
			if (strpos($info, ':0') !== false){
				return (int)str_replace(':0', '', $info);
			}
		}
	}

	return PHP_INT_MAX;
}

function getUkrainianPluralWord($number, $titles, $show_number = false) {
	if( is_string( $titles ) ){
		$titles = preg_split( '/, */', $titles );
	}		

	if(empty($titles[2]) && !empty($titles[1])){
		$titles[2] = $titles[1];
	}

	$cases = [ 2, 0, 1, 1, 1, 2 ];

	$intnum = $number?abs( (int) strip_tags( $number ) ):0;

	$title_index = ( $intnum % 100 > 4 && $intnum % 100 < 20 )
	? 2
	: $cases[ min( $intnum % 10, 5 ) ];

	if (empty($titles[ $title_index ])){
		return ( $show_number ? "$number " : '' );
	}

	return ( $show_number ? "$number " : '' ) . $titles[ $title_index ];
}	

function limit_text_by_sentences($text, $max_symbols = 1000) {	
	$text = str_replace('  ', ' ', $text);

	if (mb_strlen($text) <= ($max_symbols + ($max_symbols/10))){
		return $text;
	}

	$sentences 		= preg_split('/([.?!]+)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
	$limited_text 	= '';
	$symbol_count 	= 0;

	foreach ($sentences as $sentence) {
		$symbol_count += mb_strlen($sentence);
		$limited_text .= $sentence; 

		if ($symbol_count > $max_symbols) {
			break;
		}
	}

	return $limited_text;
}


function formatLongNumber($number, $format = true){
	if (!$format){
		return $number;
	}

	$number = (float)$number;

	if ($number > 1000000000000) {
		return round($number / 1000000000000, 1) . 'T';
	} elseif ($number > 1000000000) {
		return round($number / 1000000000, 1) . 'B';
	} elseif ($number > 1000000) {
		return round($number / 1000000, 1) . 'M';
	} elseif ($number > 1000) {
		return round($number / 1000, 1) . 'K';
	} else {
		return $number;
	}

	return $number;
}