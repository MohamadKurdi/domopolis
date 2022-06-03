<?

require_once(DIR_SYSTEM . 'library/DaData/vendor/autoload.php');

class ControllerApiDaData extends Controller { 	
	private $daData;
	private $token = 'f2666e9304211b85243e94861cd63e4226bcab72';
	private $secret = '3cdc3bf194ec1233af142e1faab9a1341ec7744e';
	
	public function index(){
	}
	
	public function validateAddress(){
		$request = $this->request->get['address'];
	
		$this->daData = new \Dadata\DadataClient($this->token, $this->secret);
	
		
	
	
	
	}