<?

namespace hobotix\Suppliers;

class SuppliersGeneralClass {

	private $feed 		= null;

	public $content    = [];

	public function __construct($registry){
		$this->registry 		= $registry;
		$this->config 			= $registry->get('config');
		$this->db 				= $registry->get('db');
		$this->log 				= $registry->get('log');
	}

	public function setFeed($feed){		
		$this->feed = $feed;
	}	

	public function setContent(){
		echoLine('[SupplierGeneralClass::setContent] Started getting feed from ' . $this->feed, 'i');

		try{
			$httpClient = new \GuzzleHttp\Client();
			$httpResponse = $httpClient->request('GET', $this->feed, ['stream' => true]);	

			$content = $httpResponse->getBody()->getContents();

		} catch (GuzzleHttp\Exception\ValueError $e){
			echoLine('[SuppliersGeneralClass::setContent]: Could not get data: ' . $e->getMessage(), 'e');				
		} catch (GuzzleHttp\Exception\ClientException $e){
			echoLine('[SuppliersGeneralClass::setContent]: Could not get data: ' . $e->getMessage(), 'e');
		} catch (\Exception $e){
			echoLine('[SuppliersGeneralClass::setContent]: Could not get data ' . $e->getMessage(), 'e');
		}

		if ($content){
			if ($this->type == 'XML'){
				$this->content = xml2array($content);
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