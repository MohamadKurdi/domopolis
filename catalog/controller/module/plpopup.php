<?php  
class ControllerModuleplpopup extends Controller {
	protected function index() {
		$this->language->load('module/plpopup');
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		
		$this->data['plpopup_headtop'] = $this->config->get('config_plpopup_headtop');
		$this->data['plpopup_headtopsize'] = $this->config->get('config_plpopup_headtopsize');
		$this->data['plpopup_headtopcolor'] = $this->config->get('config_plpopup_headtopcolor');
		$this->data['plpopup_headtopfw'] = $this->config->get('config_plpopup_headtopfw');
		$this->data['plpopup_headtoptype'] = $this->config->get('config_plpopup_headtoptype');
		$this->data['plpopup_headtopleft'] = $this->config->get('config_plpopup_headtopleft');
		$this->data['plpopup_headtopheight'] = $this->config->get('config_plpopup_headtopheight');
		$this->data['plpopup_linktop'] = $this->config->get('config_plpopup_linktop');
		$this->data['on_offlt'] = $this->config->get('on_offlt');
				
		$this->data['plpopup_headtop2'] = $this->config->get('config_plpopup_headtop2');
		$this->data['plpopup_headtopsize2'] = $this->config->get('config_plpopup_headtopsize2');
		$this->data['plpopup_headtopcolor2'] = $this->config->get('config_plpopup_headtopcolor2');
		$this->data['plpopup_headtopfw2'] = $this->config->get('config_plpopup_headtopfw2');
		$this->data['plpopup_headtoptype2'] = $this->config->get('config_plpopup_headtoptype2');
		$this->data['plpopup_headtopleft2'] = $this->config->get('config_plpopup_headtopleft2');
		$this->data['plpopup_headtopheight2'] = $this->config->get('config_plpopup_headtopheight2');
		$this->data['plpopup_linktop2'] = $this->config->get('config_plpopup_linktop2');
		$this->data['on_offlt2'] = $this->config->get('on_offlt2');
							
		$this->data['plpopup_home'] = $this->config->get('config_plpopup_home');
		$this->data['plpopup_homesize'] = $this->config->get('config_plpopup_homesize');
		$this->data['plpopup_homecolor'] = $this->config->get('config_plpopup_homecolor');
		$this->data['plpopup_homefw'] = $this->config->get('config_plpopup_homefw');
		$this->data['plpopup_hometype'] = $this->config->get('config_plpopup_hometype');
		$this->data['plpopup_homeleft'] = $this->config->get('config_plpopup_homeleft');
		$this->data['plpopup_homeheight'] = $this->config->get('config_plpopup_homeheight');
		$this->data['plpopup_linkhome'] = $this->config->get('config_plpopup_linkhome');
		$this->data['on_offlh'] = $this->config->get('on_offlh');
				
		$this->data['plpopup_home2'] = $this->config->get('config_plpopup_home2');
		$this->data['plpopup_homesize2'] = $this->config->get('config_plpopup_homesize2');
		$this->data['plpopup_homecolor2'] = $this->config->get('config_plpopup_homecolor2');
		$this->data['plpopup_home2'] = $this->config->get('config_plpopup_home2');
		$this->data['plpopup_homefw2'] = $this->config->get('config_plpopup_homefw2');
		$this->data['plpopup_hometype2'] = $this->config->get('config_plpopup_hometype2');
		$this->data['plpopup_homeleft2'] = $this->config->get('config_plpopup_homeleft2');
		$this->data['plpopup_homeheight2'] = $this->config->get('config_plpopup_homeheight2');
		$this->data['plpopup_linkhome2'] = $this->config->get('config_plpopup_linkhome2');
		$this->data['on_offlh2'] = $this->config->get('on_offlh2');
		
		$this->data['plpopup_home3'] = $this->config->get('config_plpopup_home3');
		$this->data['plpopup_homesize3'] = $this->config->get('config_plpopup_homesize3');
		$this->data['plpopup_homecolor3'] = $this->config->get('config_plpopup_homecolor3');
		$this->data['plpopup_homefw3'] = $this->config->get('config_plpopup_homefw3');
		$this->data['plpopup_hometype3'] = $this->config->get('config_plpopup_hometype3');
		$this->data['plpopup_homeleft3'] = $this->config->get('config_plpopup_homeleft3');
		$this->data['plpopup_homeheight3'] = $this->config->get('config_plpopup_homeheight3');
		$this->data['plpopup_linkhome3'] = $this->config->get('config_plpopup_linkhome3');
		$this->data['on_offlh3'] = $this->config->get('on_offlh3');
				
		$this->data['plpopup_home4'] = $this->config->get('config_plpopup_home4');
		$this->data['plpopup_homesize4'] = $this->config->get('config_plpopup_homesize4');
		$this->data['plpopup_homecolor4'] = $this->config->get('config_plpopup_homecolor4');
		$this->data['plpopup_homefw4'] = $this->config->get('config_plpopup_homefw4');
		$this->data['plpopup_hometype4'] = $this->config->get('config_plpopup_hometype4');
		$this->data['plpopup_homeleft4'] = $this->config->get('config_plpopup_homeleft4');
		$this->data['plpopup_homeheight4'] = $this->config->get('config_plpopup_homeheight4');
		$this->data['plpopup_linkhome4'] = $this->config->get('config_plpopup_linkhome4');
		$this->data['on_offlh4'] = $this->config->get('on_offlh4');
		
		$this->data['plpopup_footer'] = $this->config->get('config_plpopup_footer');
		$this->data['plpopup_footersize'] = $this->config->get('config_plpopup_footersize');
		$this->data['plpopup_footercolor'] = $this->config->get('config_plpopup_footercolor');
		$this->data['plpopup_footerfw'] = $this->config->get('config_plpopup_footerfw');
		$this->data['plpopup_footertype'] = $this->config->get('config_plpopup_footertype');
		$this->data['plpopup_footerleft'] = $this->config->get('config_plpopup_footerleft');
		$this->data['plpopup_footerheight'] = $this->config->get('config_plpopup_footerheight');
		$this->data['plpopup_linkfooter'] = $this->config->get('config_plpopup_linkfooter');
		$this->data['on_offlf'] = $this->config->get('on_offlf');
				
		$this->data['plpopup_footer2'] = $this->config->get('config_plpopup_footer2');
		$this->data['plpopup_footersize2'] = $this->config->get('config_plpopup_footersize2');
		$this->data['plpopup_footercolor2'] = $this->config->get('config_plpopup_footercolor2');
		$this->data['plpopup_footerfw2'] = $this->config->get('config_plpopup_footerfw2');
		$this->data['plpopup_footertype2'] = $this->config->get('config_plpopup_footertype2');
		$this->data['plpopup_footerleft2'] = $this->config->get('config_plpopup_footerleft2');
		$this->data['plpopup_footerheight2'] = $this->config->get('config_plpopup_footerheight2');		
		$this->data['plpopup_linkfooter2'] = $this->config->get('config_plpopup_linkfooter2');
		$this->data['on_offlf2'] = $this->config->get('on_offlf2');
		
		$this->data['plpopup_style'] = $this->config->get('config_plpopup_style');
		$this->data['plpopup_cookie'] = $this->config->get('config_plpopup_cookie');
		$this->data['plpopup_sr'] = $this->config->get('config_plpopup_sr');
		$this->data['plpopup_tm'] = $this->config->get('config_plpopup_tm');
		$this->data['plpopup_pic'] = $this->config->get('config_plpopup_pic');
		$this->data['plpopup_amw'] = $this->config->get('config_plpopup_amw');
		$this->data['plpopup_amh'] = $this->config->get('config_plpopup_amh');
		$this->data['plpopup_bg'] = $this->config->get('config_plpopup_bg');
		$this->data['on_off'] = $this->config->get('on_off');
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/plpopup.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/plpopup.tpl';
		} else {
			$this->template = 'default/template/module/plpopup.tpl';
		}
		
		$this->render();
	}
}
?>
