<?

namespace hobotix\Supplier\Adaptors;

class SuppliersGeneralClass {

	private $feed 		= null;

	public $categories 	= [];
	public $content    	= [];

	public function __construct($registry){
		$this->registry 		= $registry;
		$this->config 			= $registry->get('config');
		$this->db 				= $registry->get('db'); 
		$this->log 				= $registry->get('log');
	}

	public function setFeed($feed){		
		$this->feed = $feed;
	}	

	public function setCategories(){
		if (!$this->getContent()){
			$this->setContent();
		}

		$this->categories = $this->getCategories();
	}		

	public function getCategory($category_id){
		if (!empty($this->categories[$category_id])){
			if (!empty($this->categories[$category_id]['category_name_full'])){
				return	$this->categories[$category_id]['category_name_full'];
			}

			return $this->categories[$category_id]['category_name'];
		}

		return false;
	}

	private function getHTTPS(){
		echoLine('[SuppliersGeneralClass::getHTTPS] Executing getHTTPS method', 's');

		try{
			$httpClient 	= new \GuzzleHttp\Client();
			$httpResponse 	= $httpClient->request('GET', $this->feed, ['stream' => true]);	
			$content 		= $httpResponse->getBody()->getContents();

		} catch (GuzzleHttp\Exception\ValueError $e){
			echoLine('[SuppliersGeneralClass::setContent]: Could not get data: ' . $e->getMessage(), 'e');				
		} catch (GuzzleHttp\Exception\ClientException $e){
			echoLine('[SuppliersGeneralClass::setContent]: Could not get data: ' . $e->getMessage(), 'e');
		} catch (\Exception $e){
			echoLine('[SuppliersGeneralClass::setContent]: Could not get data ' . $e->getMessage(), 'e');
		}

		return $content;
	}

	private function getFTP(){
		echoLine('[SuppliersGeneralClass::getFTP] Executing getFTP method', 's');

		try {
			if (!extension_loaded('ftp')) {
				throw new \RuntimeException("FTP extension not loaded.");
			}

			$ftpConnection = new \Lazzard\FtpClient\Connection\FtpSSLConnection(parse_url($this->feed,  PHP_URL_HOST), parse_url($this->feed,   PHP_URL_USER), parse_url($this->feed,  PHP_URL_PASS));
			$ftpConnection->open();

			$ftpConfig = new \Lazzard\FtpClient\Config\FtpConfig($ftpConnection);
			$ftpConfig->setPassive(true);

			$ftpClient 	= new \Lazzard\FtpClient\FtpClient($ftpConnection);
			$ftpListing = $ftpClient->listDir('/');

			$mostRecent = [
				'time' => 0,
				'file' => null
			];

			foreach ($ftpListing as $ftpFile){
				$time = $ftpClient->lastMTime($ftpFile);

				if ($time > $mostRecent['time']) {
					echoLine('[SuppliersGeneralClass::getFTP] Found file ' . $ftpFile . ' newer, time is ' . $time, 'i');

					$mostRecent = [
						'time' => $time,
						'file' => $ftpFile
					];
				}
			}

			if ($mostRecent['file']){
				echoLine('[SuppliersGeneralClass::getFTP] Last file is: ' . $mostRecent['file'], 's');
				$content = $ftpClient->getFileContent($mostRecent['file']);
			} else {
				throw new \RuntimeException("No last file in directory");
			}

			$ftpConnection->close();

		} catch (Throwable $e) {
			echoLine('[SuppliersGeneralClass::getFTP] Got exception!', $e->getMessage(), 'e');
		}

		return $content;
	}


	public function setContent(){
		echoLine('[SupplierGeneralClass::setContent] Started getting feed from ' . $this->feed, 'i');

		$parsed_url = parse_url($this->feed);

		if (method_exists($this, 'get' . mb_strtoupper($parsed_url['scheme']))){
			$content = $this->{'get' . mb_strtoupper($parsed_url['scheme'])}();
		}

		if ($content){
			if ($this->type == 'XML'){
				$xtoa  			= new \AlexTartan\Array2Xml\XmlToArray(['version'=> '1.0', 'encoding'=> 'UTF-8', 'attributesKey'=> '@attributes', 'cdataKey'=> '@cdata', 'valueKey'=> '@value','useNamespaces'=> false,'forceOneElementArray' => false]);
				$this->content 	= $xtoa->buildArrayFromString($content);
			} elseif ($this->type == 'JSON'){
				$this->content = json_decode($content, true);
			}
		} else {
			$this->content = [];
		}	
	}

	public function getContent(){
		return $this->content;
	}
}