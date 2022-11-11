<?php  
class ControllerCommonFooterLandingNoShop extends Controller {
	protected function index() {
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['static_domain_url'] = $this->config->get('config_img_ssl');
		} else {
			$this->data['static_domain_url'] = $this->config->get('config_img_url');
		}
		
		$footerBottomScripts = array(
			$this->data['static_domain_url'] . "catalog/view/theme/".$this->config->get('config_template')."/js/jquery.carouFredSel-6.2.1-packed.js",
			$this->data['static_domain_url'] . "catalog/view/javascript/jquery/jquery.maskedinput-1.3.min.js",
			$this->data['static_domain_url'] . "catalog/view/javascript/jquery/jscrollpane/jquery.jscrollpane.min.js",
			$this->data['static_domain_url'] . "catalog/view/javascript/jquery/jscrollpane/jquery.mousewheel.min.js",
			$this->data['static_domain_url'] . "catalog/view/theme/".$this->config->get('config_template')."/js/responsive/enquire.min.js",
			$this->data['static_domain_url'] . "catalog/view/theme/".$this->config->get('config_template')."/js/newselect.js",
			$this->data['static_domain_url'] . "catalog/view/theme/".$this->config->get('config_template')."/js/main.js"
		);
		if ($this->config->get('topcontrol') == '1') {
			$footerBottomScripts[] = "catalog/view/theme/".$this->config->get('config_template')."/js/scroll/scrolltopcontrol.js";
		}
		if ($this->config->get('fixmenu') == '1') {
			$bottomScripts[] = "catalog/view/theme/".$this->config->get('config_template')."/js/fixmenu.js";
		}		
		if ($this->config->get('gen_responsive') == '1') {
			$bottomScripts[] = "catalog/view/theme/".$this->config->get('config_template')."/js/responsive/menu_script.js";			
		}
		
		$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		
		$this->data['footerBottomScripts'] = $footerBottomScripts;
		
		//GOOGLE CONVERSION		
		$this->data['google_conversion_id'] = $this->config->get('config_google_conversion_id');
		//END GOOGLE CONVERSION
		
				// Whos Online
		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];	
			} else {
				$ip = ''; 
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = 'https://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];	
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];	
			} else {
				$referer = '';
			}
			
			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$useragent = $this->request->server['HTTP_USER_AGENT'];	
			} else {
				$useragent = '';
			}

			$this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer, $useragent);
		}
			



		$this->load->model('module/referrer');
		$this->model_module_referrer->checkReferrer();

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/footer_noshop.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/common/footer_noshop.tpl';
				} else {
					$this->template = 'default/template/common/footer_noshop.tpl';
				}

			$this->response->setOutput($this->render());			
	}
}
?>