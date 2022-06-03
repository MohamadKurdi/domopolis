<?php
class ControllerExtensionHbSnippets extends Controller {
	
	private $error = array(); 
	
	public function index() {   
		$this->load->language('extension/hb_snippets');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		
		//Save the settings if the user has submitted the admin form (ie if someone has pressed save).
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('hb_snippets', $this->request->post);	
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('extension/hb_snippets', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		
		$text_strings = array(
				'heading_title',
				'text_about','text_header','text_header_local','text_kg_logo','text_kg_contact','text_kg_social','text_kg_searchbox','text_kg_generate','text_enable','text_disable',
				'col_business_name','col_address','col_locality','col_postal','col_local_snippet','col_state','col_country','col_store_image','col_price_range','col_enable','col_corp_contact',
				'tab_kg','tab_contact','tab_breadcrumb','tab_product','tab_og',
				'button_save',
				'button_cancel','button_remove',
				'btn_generate',
				'btn_clear'
		);
		
		foreach ($text_strings as $text) {
			$this->data[$text] = $this->language->get($text);
		}
	
		//This creates an error message. The error['warning'] variable is set by the call to function validate() in this controller (below)
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/hb_snippets', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('extension/hb_snippets', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];
			
		$this->data['hb_snippets_logo_url'] = $this->config->get('hb_snippets_logo_url');	
		$this->data['hb_snippets_contact'] = $this->config->get('hb_snippets_contact');	
		$this->data['hb_snippets_contact_enable'] = $this->config->get('hb_snippets_contact_enable');
		$this->data['hb_snippets_socials'] = $this->config->get('hb_snippets_socials');
		$this->data['hb_snippets_social_enable'] = $this->config->get('hb_snippets_social_enable');
		$this->data['hb_snippets_search_enable'] = $this->config->get('hb_snippets_search_enable');
		$this->data['hb_snippets_kg_enable'] = $this->config->get('hb_snippets_kg_enable');
		$this->data['hb_snippets_kg_data'] = $this->config->get('hb_snippets_kg_data');
		
		$this->data['hb_snippets_prod_enable'] = $this->config->get('hb_snippets_prod_enable');
		$this->data['hb_snippets_bc_enable'] = $this->config->get('hb_snippets_bc_enable');
		
		$this->data['hb_snippets_og_enable'] = $this->config->get('hb_snippets_og_enable');
		$this->data['hb_snippets_ogp'] = $this->config->get('hb_snippets_ogp');
		$this->data['hb_snippets_ogc'] = $this->config->get('hb_snippets_ogc');
		
		$this->data['hb_snippets_local_name'] = $this->config->get('hb_snippets_local_name');
		$this->data['hb_snippets_local_st'] = $this->config->get('hb_snippets_local_st');
		$this->data['hb_snippets_local_location'] = $this->config->get('hb_snippets_local_location');
		$this->data['hb_snippets_local_state'] = $this->config->get('hb_snippets_local_state');
		$this->data['hb_snippets_local_postal'] = $this->config->get('hb_snippets_local_postal');
		$this->data['hb_snippets_local_country'] = $this->config->get('hb_snippets_local_country');
		$this->data['hb_snippets_store_image'] = $this->config->get('hb_snippets_store_image');
		$this->data['hb_snippets_price_range'] = $this->config->get('hb_snippets_price_range');
		$this->data['hb_snippets_local_snippet'] = $this->config->get('hb_snippets_local_snippet');
		$this->data['hb_snippets_local_enable'] = $this->config->get('hb_snippets_local_enable');

		$this->template = 'extension/hb_snippets.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->response->setOutput($this->render());

	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/hb_snippets')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	public function generatekg(){
		$hb_snippets_logo_url = $this->config->get('hb_snippets_logo_url');	
		$hb_snippets_contact = $this->config->get('hb_snippets_contact');	
		$hb_snippets_contact_enable = $this->config->get('hb_snippets_contact_enable');
		$hb_snippets_socials = $this->config->get('hb_snippets_socials');
		$hb_snippets_social_enable = $this->config->get('hb_snippets_social_enable');
		$hb_snippets_search_enable = $this->config->get('hb_snippets_search_enable');
		
		$code = '<script type="application/ld+json">';
		$code .= '
{
      "@context": "http://schema.org",
      "@type": "Organization",
      "url": "{store_url}"';
	  
	  if ($hb_snippets_logo_url == 1){
     	 $logo = ',"logo": "{store_logo}"';
		 $code .= $logo;
	  }
	  
	  if ($hb_snippets_search_enable == 1){
     	 $search = ',"potentialAction": {
				"@type": "SearchAction",
				"target": "{store_url}index.php?route=product/search&search={search_term_string}",
				"query-input": "required name=search_term_string"
			  }';
		 $code .= $search;
	  }
	  
	  if ($hb_snippets_contact_enable == 1){
     	 $contact_s  = ',"contactPoint" : [';
		 $contact_c = '';
		 foreach ($hb_snippets_contact as $contact){
		 	$contact_c .= '
	{
    "@type" : "ContactPoint",
    "telephone" : "'.$contact['n'].'",
    "contactType" : "'.$contact['t'].'"
	},'; 
		 }
		 $contact_e = ']';
		 $code .= $contact_s.rtrim($contact_c,',').$contact_e;
	  }
	  
	  if ($hb_snippets_social_enable == 1){
	  	$social_s  = ',"sameAs" : [';
		$social_c = '';
		 foreach ($hb_snippets_socials as $social){
		 	$social_c .= '"'.$social.'",'; 
		 }
		 $social_e = ']';
		 $code .= $social_s.rtrim($social_c,',').$social_e;
	  }
	  
		$code .= '}
		</script>';
		
		$json['success'] = $code;
		$this->response->setOutput(json_encode($json));

	}
	
	public function generatelocalsnippet(){
		$name = $_POST['name'];
		$street = $_POST['street'];
		$location = $_POST['location'];
		$postal = $_POST['postal'];
		
		$phone= $this->config->get('config_telephone');
		$country = $_POST['country'];
		$store_image = $_POST['store_image'];
		$price_range = $_POST['price_range'];
		$state = $_POST['state'];
		$store_url = HTTPS_CATALOG;
		//$logo = HTTPS_CATALOG . 'image/' . $this->config->get('config_logo');
		
		$code = '<script type="application/ld+json">
		{
  "@context": "http://schema.org",
  "@type": "Store",
  "@id": "'.$store_url.'",
  "image": "'.$store_image.'",
  "name": "'.$name.'",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "'.$street.'",
    "addressLocality": "'.$location.'",
    "addressRegion": "'.$state.'",
    "postalCode": "'.$postal.'",
    "addressCountry": "'.$country.'"
  },
"telephone": "'.$phone.'",
"priceRange": "'.$price_range.'"
}</script>';
		$json['success'] = $code;
		$this->response->setOutput(json_encode($json));
	}
	
	public function resetoldseopack(){
	$this->db->query("DELETE from `".DB_PREFIX."setting` where `group` = 'hb_snippets_suite' and `key` = 'hb_snippets_local_snippet'");
	}

}
?>