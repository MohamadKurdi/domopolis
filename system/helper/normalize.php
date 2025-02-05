<?php

function removeEOLS($string){			
	$string = str_replace(PHP_EOL, ' ', $string);			
	$string = trim(preg_replace('/\s+/', ' ', $string));

	return $$string;
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

function clear_uri( $str, $clearOn = true ) {
    $str = str_replace(array(
        '`', '~', '!', '@', '#', '$', '%', '^', '*', '(', ')', '+', '=', '[', '{', ']', '}', '\\', '|', ';', ':', "'", '"', ',', '<', '.', '>', '/', '?'
    ), ' ', str_replace(array(
        '&'
    ), array(
        'and'
    ), htmlspecialchars_decode( $str )) );

    if( ! $clearOn )
        return mb_strtolower( trim( preg_replace( '/-+/', '-', preg_replace( '/ +/', '-', $str ) ), '-' ), 'utf-8' );

    $unPretty = array(
        'À', 'à', 'Á', 'á', 'Â', 'â', 'Ã', 'ã', 'Ä', 'ä', 'Å', 'å', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ǟ', 'ǟ', 'Ǻ', 'ǻ', 'Α', 'α', 'ъ',
        'Ḃ', 'ḃ', 'Б', 'б',
        'Ć', 'ć', 'Ç', 'ç', 'Č', 'č', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Ч', 'ч', 'Χ', 'χ',
        'Ḑ', 'ḑ', 'Ď', 'ď', 'Ḋ', 'ḋ', 'Đ', 'đ', 'Ð', 'ð', 'Д', 'д', 'Δ', 'δ',
        'Ǳ',  'ǲ', 'ǳ', 'Ǆ', 'ǅ', 'ǆ',
        'È', 'è', 'É', 'é', 'Ě', 'ě', 'Ê', 'ê', 'Ë', 'ë', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ę', 'ę', 'Ė', 'ė', 'Ʒ', 'ʒ', 'Ǯ', 'ǯ', 'Е', 'е', 'Э', 'э', 'Ε', 'ε',
        'Ḟ', 'ḟ', 'ƒ', 'Ф', 'ф', 'Φ', 'φ',
        'ﬁ', 'ﬂ',
        'Ǵ', 'ǵ', 'Ģ', 'ģ', 'Ǧ', 'ǧ', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ǥ', 'ǥ', 'Г', 'г', 'Γ', 'γ',
        'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ж', 'ж', 'Х', 'х',
        'Ì', 'ì', 'Í', 'í', 'Î', 'î', 'Ĩ', 'ĩ', 'Ï', 'ï', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'И', 'и', 'Η', 'η', 'Ι', 'ι',
        'Ĳ', 'ĳ',
        'Ĵ', 'ĵ',
        'Ḱ', 'ḱ', 'Ķ', 'ķ', 'Ǩ', 'ǩ', 'К', 'к', 'Κ', 'κ',
        'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Л', 'л', 'Λ', 'λ',
        'Ǉ', 'ǈ', 'ǉ',
        'Ṁ', 'ṁ', 'М', 'м', 'Μ', 'μ',
        'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'Ñ', 'ñ', 'ŉ', 'Ŋ', 'ŋ', 'Н', 'н', 'Ν', 'ν',
        'Ǌ', 'ǋ', 'ǌ',
        'Ò', 'ò', 'Ó', 'ó', 'Ô', 'ô', 'Õ', 'õ', 'Ö', 'ö', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ø', 'ø', 'Ő', 'ő', 'Ǿ', 'ǿ', 'О', 'о', 'Ο', 'ο', 'Ω', 'ω',
        'Œ', 'œ',
        'Ṗ', 'ṗ', 'П', 'п', 'Π', 'π',
        'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Р', 'р', 'Ρ', 'ρ', 'Ψ', 'ψ',
        'Ś', 'ś', 'Ş', 'ş', 'Š', 'š', 'Ŝ', 'ŝ', 'Ṡ', 'ṡ', 'ſ', 'ß', 'С', 'с', 'Ш', 'ш', 'Щ', 'щ', 'Σ', 'σ', 'ς',
        'Ţ', 'ţ', 'Ť', 'ť', 'Ṫ', 'ṫ', 'Ŧ', 'ŧ', 'Þ', 'þ', 'Т', 'т', 'Ц', 'ц', 'Θ', 'θ', 'Τ', 'τ',
        'Ù', 'ù', 'Ú', 'ú', 'Û', 'û', 'Ũ', 'ũ', 'Ü', 'ü', 'Ů', 'ů', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ų', 'ų', 'Ű', 'ű', 'У', 'у',
        'В', 'в', 'Β', 'β',
        'Ẁ', 'ẁ', 'Ẃ', 'ẃ', 'Ŵ', 'ŵ', 'Ẅ', 'ẅ',
        'Ξ', 'ξ',
        'Ỳ', 'ỳ', 'Ý', 'ý', 'Ŷ', 'ŷ', 'Ÿ', 'ÿ', 'Й', 'й', 'Ы', 'ы', 'Ю', 'ю', 'Я', 'я', 'Υ', 'υ',
        'Ź', 'ź', 'Ž', 'ž', 'Ż', 'ż', 'З', 'з', 'Ζ', 'ζ',
        'Æ', 'æ', 'Ǽ', 'ǽ', 'а', 'А'
    );
    $pretty   = array(
        'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A',
        'B', 'b', 'B', 'b',
        'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'CH', 'ch', 'CH', 'ch',
        'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd',
        'DZ', 'Dz', 'dz', 'DZ', 'Dz', 'dz',
        'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e',
        'F', 'f', 'f', 'F', 'f', 'F', 'f',
        'fi', 'fl',
        'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
        'H', 'h', 'H', 'h', 'ZH', 'zh', 'H', 'h',
        'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i',
        'IJ', 'ij',
        'J', 'j',
        'K', 'k', 'K', 'k', 'K', 'k', 'K', 'k', 'K', 'k',
        'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l',
        'LJ', 'Lj', 'lj',
        'M', 'm', 'M', 'm', 'M', 'm',
        'N', 'n', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'N', 'n', 'N', 'n', 'N', 'n',
        'NJ', 'Nj', 'nj',
        'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o',
        'OE', 'oe',
        'P', 'p', 'P', 'p', 'P', 'p', 'PS', 'ps',
        'R', 'r', 'R', 'r', 'R', 'r', 'R', 'r', 'R', 'r',
        'S', 's', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 's', 'ss', 'S', 's', 'SH', 'sh', 'SHCH', 'shch', 'S', 's', 's',
        'T', 't', 'T', 't', 'T', 't', 'T', 't', 'T', 't', 'T', 't', 'TS', 'ts', 'TH', 'th', 'T', 't',
        'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u',
        'V', 'v', 'V', 'v',
        'W', 'w', 'W', 'w', 'W', 'w', 'W', 'w',
        'X', 'x',
        'Y', 'y', 'Y', 'y', 'Y', 'y', 'Y', 'y', 'Y', 'y', 'Y', 'y', 'YU', 'yu', 'YA', 'ya', 'Y', 'y',
        'Z', 'z', 'Z', 'z', 'Z', 'z', 'Z', 'z', 'Z', 'z',
        'AE', 'ae', 'AE', 'ae', 'a', 'A'
    );

    $str = mb_strtolower( str_replace( $unPretty, $pretty, $str ), 'utf-8' );
    $str = trim( preg_replace('/[^A-Z^a-z^0-9]+/','-', $str), '-');
    return preg_replace( '/-+/', '-', $str );
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
	return strtolower(rms(simple_translit($st)));
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