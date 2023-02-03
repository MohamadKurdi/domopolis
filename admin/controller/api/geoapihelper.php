<?
	
	class ControllerApiGeoApiHelper extends Controller { 
		private $key = 'sE3w0cCr99RDRkFU1iHGOzz7cLhmqGtC';
		private $url = 'http://geohelper.info/api/v1/';			
		
		public function index() {}
		
		public function findCityID($city = ''){			
			$data['filter[name]'] = trim($city);
		
			$this->makeRequest('cities', $data);
		}
		
		private function makeRequest($method, $data){
			
			$data['apiKey'] = $this->key;
			$data['locale[lang]'] = 'ru';
			$data['locale[fallbacklang]'] = 'ru';
			
			$url = $this->url . $method . '?' . http_build_query($data);
			
			$_headers = array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Encoding: gzip, deflate, br',
            'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
            'Cache-Control: max-age=0',
            'Connection: keep-alive',
            'Upgrade-Insecure-Requests: 1',                      
			);
			
			//Curl
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0); // return headers 0 no 1 yes
			curl_setopt($ch, CURLOPT_HTTPHEADER, $_headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return page 1:yes
			curl_setopt($ch, CURLOPT_TIMEOUT, 200); // http request timeout 20 seconds
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects, need this if the url changes
			curl_setopt($ch, CURLOPT_MAXREDIRS, 2); //if http server gives redirection responce
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // false for https
			curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, br"); // the page encoding
						
			
			$result = curl_exec($ch);
			
			print_r($result);						
		}
	}