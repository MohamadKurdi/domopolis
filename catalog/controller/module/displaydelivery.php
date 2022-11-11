<?

class ControllerModuleDisplayDelivery extends Controller {
	public function index() {
		$this->language->load('product/delivery');
		
		
		
		
	if ($this->config->get('config_regional_currency') == 'RUB'){
		$lang = 'ru';		
	} elseif ($this->config->get('config_regional_currency') == 'UAH'){
		$lang = 'ua';	
	} elseif ($this->config->get('config_regional_currency') == 'BYN') {
		$lang = 'by';
	} elseif ($this->config->get('config_regional_currency') == 'BYN') {
		$lang = 'kz';
	} elseif ($this->config->get('config_regional_currency') == 'EUR') {
		$lang = 'de';
	} else {
		$lang = 'empty';
	}
		
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/deliveries/delivery.'.$lang.'.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/deliveries/delivery.'.$lang.'.tpl';
		} else {
			$this->template = $this->config->get('config_template') . '/template/product/deliveries/delivery.empty.tpl';
		}

		$this->response->setOutput($this->render());	
	}
}