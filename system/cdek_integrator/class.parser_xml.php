<?php  

class parser_xml extends response_parser {
	
	public function getData() {	
	//	var_dump($this->data);
	//	die();
		return (strpos($this->data, '<?xml') === 0) ? new SimpleXMLElement($this->data) : '';
	}
	
}

?>