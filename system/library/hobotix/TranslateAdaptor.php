<?

namespace hobotix;

class TranslateAdaptor {	
	private $registry			= null;
	private $config				= null;
	private $db  				= null;
	private $translateObject 	= null;

	public function __construct($registry) {		
		$this->registry = $registry;
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');
		
		$this->use($this->config->get('config_translation_library'));
	}

	public function getTranslateLibraries(){
		$results = [];

		$libraries = glob(dirname(__FILE__) . '/Translate/*');        
        foreach ($libraries as $library) {
            $results[] = pathinfo($library,  PATHINFO_FILENAME);
        }

        return $results;
	}

	public function use($translateClass){
		if (file_exists(DIR_SYSTEM . '/library/hobotix/Translate/' . $translateClass . '.php')){
			require_once (DIR_SYSTEM . '/library/hobotix/Translate/' . $translateClass . '.php');
			$translateClass = "hobotix" . "\\" . "Translate" . "\\" . $translateClass;
			$this->translateObject = new $translateClass($this->registry);

			echoLine('[TranslateAdaptor::use] Using ' . $translateClass . ' translation library', 's');			
		} else {
			echoLine('[TranslateAdaptor::use] Tried to use ' . $translateClass . ' translation library, but failed', 'e');	
		}

		return $this;
	}

	public function setDebug($debug){
		if (method_exists($this->translateObject, 'setDebug')){
			return $this->translateObject->setDebug($debug);
		}
	}

	public function checkIfItIsPossibleToMakeRequest(){
		if (method_exists($this->translateObject, 'checkIfItIsPossibleToMakeRequest')){
			try {
				$result = $this->translateObject->checkIfItIsPossibleToMakeRequest();
			} catch (\Exception $e){
				$result = $e->getMessage();
			}
		}

		if (empty($result)){
			echoLine('[TranslateAdaptor::checkIfItIsPossibleToMakeRequest] Could not anything, maybe general api fail! ' . $result, 'e');			
			return false;			
		}			

		echoLine('[TranslateAdaptor::checkIfItIsPossibleToMakeRequest] Can make request!', 's');

		return $result;
	}


	public function translate($text, $from, $to, $returnString = false){
		if (method_exists($this->translateObject, 'translate')){
			try {
				$result = $this->translateObject->translate($text, $from, $to, $returnString);
			} catch (\Exception $e){
				throw new \Exception ($e->getMessage());
			}
		}

		return $result;
	}
}