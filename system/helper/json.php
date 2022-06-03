<?php
	
	if (!function_exists('cmp')) {
		function cmp($a,$b){
			return ($a['sort']<$b['sort'])?-1:1;
		}
	}
	
	
	if (!function_exists('json_encode')) {
		function json_encode($data) {
			switch (gettype($data)) {
				case 'boolean':
				return $data ? 'true' : 'false';
				case 'integer':
				case 'double':
				return $data;
				case 'resource':
				case 'string':
				# Escape non-printable or Non-ASCII characters. 
				# I also put the \\ character first, as suggested in comments on the 'addclashes' page. 
				$json = ''; 
				
				$string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"'; 
				
				# Convert UTF-8 to Hexadecimal Codepoints. 
				for ($i = 0; $i < strlen($string); $i++) { 
					$char = $string[$i]; 
					$c1 = ord($char); 
					
					# Single byte; 
					if ($c1 < 128) { 
						$json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1); 
						
						continue; 
					} 
					
					# Double byte 
					$c2 = ord($string[++$i]); 
					
					if (($c1 & 32) === 0) { 
						$json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128); 
						
						continue;
					} 
					
					# Triple 
					$c3 = ord($string[++$i]); 
					
					if (($c1 & 16) === 0) { 
						$json .= sprintf("\\u%04x", (($c1 - 224) <<12) + (($c2 - 128) << 6) + ($c3 - 128)); 
						
						continue; 
					} 
					
					# Quadruple 
					$c4 = ord($string[++$i]); 
					
					if (($c1 & 8 ) === 0) { 
						$u = (($c1 & 15) << 2) + (($c2 >> 4) & 3) - 1; 
						
						$w1 = (54 << 10) + ($u << 6) + (($c2 & 15) << 2) + (($c3 >> 4) & 3); 
						$w2 = (55 << 10) + (($c3 & 15) << 6) + ($c4 - 128); 
						$json .= sprintf("\\u%04x\\u%04x", $w1, $w2); 
					} 
				} 				
				
				return $json;
				case 'array':
				if (empty($data) || array_keys($data) === range(0, sizeof($data) - 1)) {
					$output = array();
					
					foreach ($data as $value) {
						$output[] = json_encode($value);
					}
					
					return '[' . implode(',', $output) . ']';
				}
				case 'object':
				$output = array();
				
				foreach ($data as $key => $value) {
					$output[] = json_encode(strval($key)) . ':' . json_encode($value);
				}
				
				return '{' . implode(',', $output) . '}';
				default:
				return 'null';
			}
		}
	}
	
	if (!function_exists('json_decode')) {	
		function json_decode($json, $assoc = false) {
			$match = '/".*?(?<!\\\\)"/';
			
			$string = preg_replace($match, '', $json);
			$string = preg_replace('/[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/', '', $string);
			
			if ($string != '') {
				return null;
			}
			
			$s2m = array();
			$m2s = array();
			
			preg_match_all($match, $json, $m);
			
			foreach ($m[0] as $s) {
				$hash = '"' . md5($s) . '"';
				$s2m[$s] = $hash;
				$m2s[$hash] = str_replace('$', '\$', $s);
			}
			
			$json = strtr($json, $s2m);
			
			$a = ($assoc) ? '' : '(object) ';
			
			$data = array(
			':' => '=>', 
			'[' => 'array(', 
			'{' => "{$a}array(", 
			']' => ')', 
			'}' => ')'
			);
			
			$json = strtr($json, $data);
			
			$json = preg_replace('~([\s\(,>])(-?)0~', '$1$2', $json);
			
			$json = strtr($json, $m2s);
			
			$function = @create_function('', "return {$json};");
			$return = ($function) ? $function() : null;
			
			unset($s2m); 
			unset($m2s); 
			unset($function);
			
			return $return;
		}
	}
	
	if (!function_exists('xml2array')) {
		function xml2array($contents, $get_attributes=1, $priority = 'tag') {
			if(!$contents) return array();
			
			if(!function_exists('xml_parser_create')) {
				//print "'xml_parser_create()' function not found!";
				return array();
			}
			
			//Get the XML parser of PHP - PHP must have this module for the parser to work
			$parser = xml_parser_create('');
			xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parse_into_struct($parser, trim($contents), $xml_values);
			xml_parser_free($parser);
			
			if(!$xml_values) return;//Hmm...
			
			//Initializations
			$xml_array = array();
			$parents = array();
			$opened_tags = array();
			$arr = array();
			
			$current = &$xml_array; //Refference
			
			//Go through the tags.
			$repeated_tag_index = array();//Multiple tags with same name will be turned into an array
			foreach($xml_values as $data) {
				unset($attributes,$value);//Remove existing values, or there will be trouble
				
				//This command will extract these variables into the foreach scope
				// tag(string), type(string), level(int), attributes(array).
				extract($data);//We could use the array by itself, but this cooler.
				
				$result = array();
				$attributes_data = array();
				
				if(isset($value)) {
					if($priority == 'tag') $result = $value;
					else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
				}
				
				//Set the attributes too.
				if(isset($attributes) and $get_attributes) {
					foreach($attributes as $attr => $val) {
						if($priority == 'tag') $attributes_data[$attr] = $val;
						else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
					}
				}
				
				//See tag status and do the needed.
				if($type == "open") {//The starting of the tag '<tag>'
					$parent[$level-1] = &$current;
					if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
						$current[$tag] = $result;
						if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
						$repeated_tag_index[$tag.'_'.$level] = 1;
						
						$current = &$current[$tag];
						
						} else { //There was another element with the same tag name
						
						if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
							$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
							$repeated_tag_index[$tag.'_'.$level]++;
							} else {//This section will make the value an array if multiple tags with the same name appear together
							$current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
							$repeated_tag_index[$tag.'_'.$level] = 2;
							
							if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
								$current[$tag]['0_attr'] = $current[$tag.'_attr'];
								unset($current[$tag.'_attr']);
							}
							
						}
						$last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
						$current = &$current[$tag][$last_item_index];
					}
					
					} elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
					//See if the key is already taken.
					if(!isset($current[$tag])) { //New Key
						$current[$tag] = $result;
						$repeated_tag_index[$tag.'_'.$level] = 1;
						if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;
						
						} else { //If taken, put all things inside a list(array)
						if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...
							
							// ...push the new element into that array.
							$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
							
							if($priority == 'tag' and $get_attributes and $attributes_data) {
								$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
							}
							$repeated_tag_index[$tag.'_'.$level]++;
							
							} else { //If it is not an array...
							$current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
							$repeated_tag_index[$tag.'_'.$level] = 1;
							if($priority == 'tag' and $get_attributes) {
								if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
									
									$current[$tag]['0_attr'] = $current[$tag.'_attr'];
									unset($current[$tag.'_attr']);
								}
								
								if($attributes_data) {
									$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
								}
							}
							$repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
						}
					}
					
					} elseif($type == 'close') { //End of tag '</tag>'
					$current = &$parent[$level-1];
				}
			}
			
			return($xml_array);
		}  
	}