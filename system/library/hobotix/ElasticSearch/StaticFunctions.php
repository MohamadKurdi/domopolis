<?php
namespace hobotix\ElasticSearch;

class StaticFunctions{

	public static function prepareQueryExceptions($query){
		$search_settings = loadJsonConfig('search');		

		if (!empty($search_settings) && !empty($search_settings['exceptions'])){
			foreach ($search_settings['exceptions'] as $key => $value){
				$query = mb_strtolower($query);
				$key = mb_strtolower($key);

				if (trim($query) == $key){
					$query = $value;
				}

				$query = str_replace(' ' . $key, ' ' . $value, $query);
				$query = str_replace($key . ' ', $value . ' ', $query);
				$query = str_replace(' ' . $key . ' ', ' ' . $value . ' ', $query);
			}
		}

		return $query;
	}

	public static function validateCountResult($results){			
		if (!empty($results['count'])){
			return $results['count'];
		}

		return 0;
	}	

	public static function validateAggregationResult($results, $index){
		if (!empty($results['aggregations']) && !empty($results['aggregations'][$index]) && !empty($results['aggregations'][$index]['buckets']) ){
			return $results['aggregations'][$index]['buckets'];
		}

		return false;
	}

	public static function getFirstSuggestion($result, $query){
		if (is_array($result['suggest']) && is_array($result['suggest']['phrase-suggestion']) && !empty($result['suggest']['phrase-suggestion'][0])){

			if (!empty($result['suggest']['phrase-suggestion'][0]['options']) && $result['suggest']['phrase-suggestion'][0]['options'][0]['text'] != $query){				
				return 	$result['suggest']['phrase-suggestion'][0]['options'][0]['text'];		
			}
		}

		if (is_array($result['suggest']) && is_array($result['suggest']['term-suggestion']) && !empty($result['suggest']['term-suggestion'][0])){

			$text = ' ';
			foreach ($result['suggest']['term-suggestion'] as $suggestion_word){

				if (!empty($suggestion_word['options'][0])){
					$text .= $suggestion_word['options'][0]['text'];
				} else {
					$text .= $suggestion_word['text'] . ' ';
				}
			}

			$text = trim($text);
			if ($text){
				return $text;
			}

			return 	$result['suggest']['term-suggestion'][0]['options'][0]['text'];							
		}

		return false;			
	}


	public static function createIndexArray($mapping, $index, &$params){			
		$params['body'][$index] = [];
		
		foreach ($mapping as $key => $value){
			$params['body'][$index][] = $key;
		}						
	}

	public static function createMappingToNonEmpty($query, $indexOne){			
			$mapping = [];
			if ($query->num_rows){										
				foreach ($query->rows as $row){
					$mapping[$row[$indexOne]] = true;
				}
				unset($row);			
			}
			
			return $mapping;
		}

	public static function remapAlternateMapping(&$mapping){			
		foreach ($mapping as &$mapline){
			if (is_array($remapped = self::parseAlternateName($mapline))){
				$mapline = $remapped;
			}			
		}
	}

	public static function parseAlternateName($alternate_name){
		$results = [];

		if (trim($alternate_name) && mb_strlen(trim(str_replace(PHP_EOL, '', $alternate_name))) > 2){
			$exploded = explode(PHP_EOL, $alternate_name);

			foreach ($exploded as $line){
				if (trim($line) && mb_strlen(trim(str_replace(PHP_EOL, '', $line))) > 2){

					$results[] = trim(str_replace(PHP_EOL, '', $line));

				}				
			}
		}
		return $results;
	}

	public static function validateResult($results, $checkSuggestion = false){
		if (is_array($results['hits']) && (int)$results['hits']['total']['value'] > 0){
			return (int)$results['hits']['total']['value'];
		}

		if ($checkSuggestion && is_array($results['suggest']) && (int)$results['suggest']['completition-suggestion'][0]['length'] > 0){
			return (int)$results['suggest']['completition-suggestion'][0]['length'];
		}

		return false;
	}

	public static function prepareWords($query){
		$exploded = explode(' ',$query);
		$words = [];
		foreach ($exploded as $word){
			if (count($exploded) > 1){

				if (mb_strlen($word) > 2 && !self::is_stopword($word)){
					$words[] = trim($word);
				}

			} else {					
				$words[] = trim($word);
			}
		}

		return $words;
	}

	public static function createMappingToIDS($query, $indexOne, $indexTwo){			
		$mapping = [];
		if ($query->num_rows){										
			foreach ($query->rows as $row){
				$mapping[$row[$indexOne]] = $row[$indexTwo];
			}
			unset($row);			
		}

		return $mapping;
	}	

	public static function makeIdentifiersArray($sku){
		$results = [];

		$sku = trim($sku);

		$results[] = str_replace(' ', '', $sku);
		$results[] = str_replace('.', '', $sku);
		$results[] = str_replace('-', '', $sku);
		$results[] = str_replace('/', '', $sku);		
		$results[] = str_replace(array(' ', '.', '-', '/'), '', $sku);
		$results[] = str_replace(array(' ', '-'), '', $sku);
		$results[] = str_replace(array(' ', '.'), '', $sku);
		$results[] = str_replace(array('-', '.'), '', $sku);
		$results[] = str_replace(array('/', '.'), '', $sku);
		$results[] = str_replace(array('/', ' '), '', $sku);
		$results[] = str_replace(array('/', '-'), '', $sku);

		if (substr($sku, 0, 1) == '0'){				
			$sku = trim(substr($sku, 1));

			$results[] = str_replace(' ', '', $sku);
			$results[] = str_replace('.', '', $sku);
			$results[] = str_replace('-', '', $sku);
			$results[] = str_replace(array(' ', '.', '-', '/'), '', $sku);
			$results[] = str_replace(array(' ', '-'), '', $sku);
			$results[] = str_replace(array(' ', '.'), '', $sku);
			$results[] = str_replace(array('-', '.'), '', $sku);
			$results[] = str_replace(array('/', '.'), '', $sku);
			$results[] = str_replace(array('/', ' '), '', $sku);
			$results[] = str_replace(array('/', '-'), '', $sku);
		}

		return array_filter(array_values(array_unique($results)));		
	}	

	public static function transformNumber($number, $language_code){
		if ($language_code == 'uk'){
			$numberToWords 			= new \NumberToWords\NumberToWords();
			$numberTransformer 		= $numberToWords->getNumberTransformer('ua');
			return array($numberTransformer->toWords($number));
		}

		if ($language_code == 'ru'){			
			$transformed1 = array_values(\morphos\Russian\CardinalNumeralGenerator::getCases($number, $gender = \morphos\Gender::MALE));
			$transformed2 = array_values(\morphos\Russian\CardinalNumeralGenerator::getCases($number, $gender = \morphos\Gender::FEMALE));
			$transformed = (array_values(array_unique(array_merge($transformed1, $transformed2))));						

			return $transformed;	
		}	

		return [];
	}	
}