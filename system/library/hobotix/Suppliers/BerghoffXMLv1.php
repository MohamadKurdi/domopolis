<?

namespace hobotix\Supplier;

class BerghoffXMLv1 {

	private $content 	= [];
	private $feed 		= null;

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
		echoLine('[BerghoffXMLv1::setContent] Started getting feed from ' . $this->feed, 'i');
		$xml = file_get_contents($this->feed);

		if ($xml){
			echoLine('[BerghoffXMLv1::setContent] Got feed data', 'i');
		} else {
			echoLine('[BerghoffXMLv1::setContent] Could not get data, exiting', 'e');
			throw new \Exeption('Could not get data from ' . $this->feed);
		}
		
		$this->content = xml2array($xml);
	}

	public function getCategories(){
		$categories = [];

		$this->setContent();

		if (!empty($this->content['rss'])){
			if (!empty($this->content['rss']['channel']['item'])){
				foreach ($this->content['rss']['channel']['item'] as $item){
					if (!empty($item['product_type'])){

						if (is_array($item['product_type'])){
							if (!empty($item['product_type'][0])){
								$categories[$item['product_type'][0]] = $item['product_type'][0];
							} elseif (!empty($item['product_type'][1])){
								$categories[$item['product_type'][1]] = $item['product_type'][1];
							}							
						} else {
							$categories[$item['product_type']] = $item['product_type'];
						}

					}
				}
			}
		}

		return $categories;
	}














	
}