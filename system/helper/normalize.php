<?php

function removeEOLS($string){			
	$string = str_replace(PHP_EOL, ' ', $string);			
	$string = trim(preg_replace('/\s+/', ' ', $string));

	return $s;
}

function removeLeftGT($string){
	if (!$string){
		return $string;
	}

	$string = trim($string);

	if (mb_substr($string, 0, 4) == '&gt;'){
		$string = mb_substr($string, 4);
	}

	return trim($string);
}	

function reparseEOLSToSpace($st){
	$ste = explode(PHP_EOL, $st);

	foreach ($ste as &$smste){
		$smste = trim($smste);
		$smste = str_replace('  ', ' ', $smste);
	}

	return implode(' ', $ste);	
}

function reparseEOLSToSlash($st){			
	$ste = explode(PHP_EOL, $st);

	foreach ($ste as &$smste){
		$smste = trim($smste);
		$smste = str_replace('  ', ' ', $smste);
	}

	return implode(' / ', $ste);			
}

function normalizeSKU($sku){
	return preg_replace("([^0-9])", "", $sku);
}

function arrayToInt($array){
	return array_map('toInt', $array);
}

function toInt($item){
	return (int)$item;
}

function extractNumeric($string){
	return preg_replace('/[^0-9.,]/', '', $string);
}

function removeSpaces($st){
	$st = str_replace(' ','',$st);

	return html_entity_decode($st, ENT_COMPAT, 'UTF-8');
}

function normalizeForGoogleV2($text){
	$text = html_entity_decode($text);
	$text = str_replace('&nbsp;', ' ', $text);
	$text = str_replace('&amp;', '&', $text);
	$text = preg_replace("/&#?[a-z0-9]{2,8};/i", "", $text);

	return $text;
}

function normalizeForGoogle($text){
	$text = html_entity_decode($text);
	$text = str_replace('&nbsp;', ' ', $text);
	$text = str_replace('&amp;', '&', $text);
	$text = preg_replace("/&#?[a-z0-9]{2,8};/i", "", $text);

	return $text;
}

function normalizeForYML($field) {
	$field = htmlspecialchars_decode($field);
	$field = strip_tags($field);
	$field = str_replace(['"', '>', '<', '&nbsp;', '&amp;quot;'], ['', '&gt;', '&lt;', ' ', '"'], $field);
	$field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

	return trim($field);
}

function prepareFieldForYML($field) {
	$field = htmlspecialchars_decode($field);
	$field = strip_tags($field);
	$from = array('"', '&', '>', '<', '\'', '&nbsp;');
	$to = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;', ' ');
	$field = str_replace($from, $to, $field);
	$field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

	return trim($field);
}

function preparePhone($phone){
	if ($phone[0] == '+'){
		$phone = substr($phone, 1);			
	}
	$phone = '+' . preg_replace("/\D+/", "", $phone);

	return $phone;
}

function preparePhoneNoPlus($phone){
	return preg_replace("/\D+/", "", $phone);
}

function prepareFileName($filename){
	$filename = urlencode($filename);
	$filename = str_replace('+', ' ', $filename);

	return $filename;
}

function prepareEcommPrice($price) : float{
	$hbprice = str_replace('.','',$price);
	$hbprice = str_replace(',','.',$hbprice);
	$hbprice = preg_replace("/[^0-9.]/", "", $hbprice);
	$hbprice = ltrim($hbprice,'.');
	$hbprice = rtrim($hbprice,'.');

	return (float)$hbprice;
}

function prepareEcommString($string) : string{
	if ($string){
		$string = str_replace('&amp;', '&', $string);
		$string = str_replace("'", "`", $string);
		$string = str_replace('"', "`", $string);
	}

	return (string)$string;
}

function prepareFuckenOpenGraph($string){
	$string = strip_tags($string);
	$string = str_replace(PHP_EOL, ' ', $string);
	$string = str_replace('&nbsp;', ' ', $string);
	$string = str_replace('  ', ' ', $string);

	return $string;	
}

function simple_rm1($st){
	$st = str_replace('"','',$st);

	return $st;	
}	

function rms($st, $uuml = false, $rmspace = false){
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


function simple_rms($st){
	$st = str_replace(',','',$st);
	$st = str_replace('’','',$st);
	$st = str_replace(' ','-',$st);
	$st = str_replace('"','',$st);
	$st = str_replace(')','',$st);
	$st = str_replace('(','',$st);
	$st = str_replace('.','',$st);
	$st = str_replace('+','',$st);
	$st = str_replace('*','',$st);
	$st = str_replace('“','',$st);
	$st = str_replace('”','',$st);
	$st = str_replace('&quot;','-',$st);
	$st = str_replace('&amp;','-and-',$st);
	$st = str_replace('&','-and-',$st);
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
	$st = str_replace('Ø','',$st);
	return $st;
}

function simple_normalize($st){
	return strtolower(rms(translit($st)));
} 


function shortentext($text, $length = 150){
	$exploded 	= explode(' ', $text);
	$current 	= 0;

	$i 		= 0;
	while ($current < $length && isset($exploded[$i]))
	{
		$current += mb_strlen($exploded[$i]) + 1;
		$i++;
	}

	return trim(implode(' ', array_slice($exploded, 0, $i - 1)), '()., ');
}

function atrim_array($array) {	
	$result = [];
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$result[$key] = atrim_array($value); 
		} else if (is_string($value)) {
			$result[$key] = atrim($value);
		} else {
			$result[$key] = $value;
		}
	}
	return $result;
}

function atrim($string){	
	if (!$string){
		return '';
	}

	$string = preg_replace('/(\x{200e}|\x{200f})/u', '', $string);
	$string = str_replace(['"', '“', '„'], "'", $string);
	$string = trim($string);

	return $string;
}

function clean_string($string) {
	if (!$string){
		return '';
	}

	$string = preg_replace( "/[^a-zA-ZА-Яа-я0-9\s]/", '', $string );
	$string = trim($string);

	return $string;
}