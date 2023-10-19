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
	if( is_string( $titles ) )
		$titles = preg_split( '/, */', $titles );

	if( empty( $titles[2] ) )
		$titles[2] = $titles[1];

	$cases = [ 2, 0, 1, 1, 1, 2 ];

	$intnum = abs( (int) strip_tags( $number ) );

	$title_index = ( $intnum % 100 > 4 && $intnum % 100 < 20 )
	? 2
	: $cases[ min( $intnum % 10, 5 ) ];

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