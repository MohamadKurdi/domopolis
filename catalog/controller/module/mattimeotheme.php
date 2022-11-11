<?php  
class ControllerModuleMattimeoTheme extends Controller {
	
	protected function index() {
		
		$this->language->load('module/mattimeotheme');
		$this->data['mattimeomod'] = $this->config->get('mattimeomod');
		$this->data['mattimg_icon'] 	= $this->config->get('mattimg_icon');
		$this->data['matt_array'] 	= $this->config->get('matt_array');
		$this->data['mattimeolink'] 	= $this->config->get('mattimeolink');
		$this->data['mattimeolinkheader'] 	= $this->config->get('mattimeolinkheader');
		$this->data['mattimg'] 	= $this->config->get('mattimg');
		$this->data['mattnetwork'] 	= $this->config->get('mattnetwork');

		/* LANGUAGE */
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		$this->data['language_id'] = array();
		foreach ($this->data['languages'] as $result) {
      		$this->data['language_id'][] = array(
        		'language_id' => $result['language_id']
      		);
    	}
		$this->document->Lang_id = $this->data['language_id'];
		
		$this->load->model('tool/image');

		$this->data['cusom_h']	= array();
		$this->data['cusom_p']	= array();
		$this->data['cusom_p_tab']	= array();
		$this->data['cusom_f']	= array();
		$this->data['Top_m_link']	= array();
		$this->data['Header_m_link']	= array();
		$this->data['htmlmenu_t']	= array();
        $this->data['informations'] = array();
		$this->data['mattimg_f']		= array();
		$this->data['cusom_payment']	= array();
		$this->data['cusom_network']	= array();

		
		/* Information*/
		/*
		foreach ($this->model_catalog_information->getInformations() as $result) {
			$this->data['informations'][] = array(
				'title' => $result['title'],
				'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
			);
		}
		*/
		
		if($this->data['matt_array']){
			
          
			/* Custom header contact*/
			$i = 0;
			foreach ($this->data['matt_array'] as $result) {
				
				if (isset($this->data['language_id'][$i]['language_id'])) {
				
					$this->data['cusom_h'][$this->data['language_id'][$i]['language_id']] = array(
						'comptext_header_text'	=>	$result['comptext_header_text']
					);
				}
				$i++;
			}
			
			/* Custom product information*/
			$i = 0;
			foreach ($this->data['matt_array'] as $result) {
				
				if (isset($this->data['language_id'][$i]['language_id'])) {
				
				$this->data['cusom_p'][$this->data['language_id'][$i]['language_id']] = array(
					'product_text'	=>	$result['product_text']
				);
				
				}
				$i++;
			}
			
			$i = 0;
			foreach ($this->data['matt_array'] as $result) {
				
				if (isset($this->data['language_id'][$i]['language_id'])) {
					$this->data['cusom_p_tab'][$this->data['language_id'][$i]['language_id']] = array(
						'product_title_tab'	=>	$result['product_title_tab'],
						'product_text_tab'	=>	$result['product_text_tab']
					);
				}
				$i++;
			}
          
			/* Custom footer contact*/
			$i = 0;
			foreach ($this->data['matt_array'] as $result) {
				
				if (isset($this->data['language_id'][$i]['language_id'])) {
					$this->data['cusom_f'][$this->data['language_id'][$i]['language_id']] = array(
						'comptext_text'	=>	$result['comptext_text']
					);
				}
				$i++;
			}
			
			/* Custom payment text*/
			$i = 0;
			foreach ($this->data['matt_array'] as $result) {
			
				if (isset($this->data['language_id'][$i]['language_id'])) {
				
					$this->data['cusom_payment'][$this->data['language_id'][$i]['language_id']] = array(
						'compfootertext_title'		=>	$result['compfootertext_title'],
						'footer_payment_text'	=>	$result['footer_payment_text']
					);
				
				}
				$i++;
			}	
			
		}
		
		if($this->data['mattimeolink']){
			
			/* Custom menu links*/
			$i = 0;
            if(isset($this->data['mattimeolink'])){
               
     				$this->data['Top_m_link'] = $this->data['mattimeolink']['Top_m_links'];
               uasort($this->data['Top_m_link'],'cmp');
			}
		}
		
		if($this->data['mattimeolinkheader']){
			
			/* Custom menu header links*/
			$i = 0;
            if(isset($this->data['mattimeolinkheader'])){
			 
     				$this->data['Header_m_link'] = $this->data['mattimeolinkheader']['Header_m_links'];
               uasort($this->data['Header_m_link'],'cmp');
			}
		}
		
		
		if($this->data['mattimeomod']){

			
          /* Custom html menu*/
			$i = 0;
			foreach ($this->data['mattimeomod'] as $result) {
				
				if (isset($this->data['language_id'][$i]['language_id'])) {
				
					$this->data['htmlmenu_t'][$this->data['language_id'][$i]['language_id']] = array(
						'topmenulink_lang'		=>	$result['topmenulink_lang'],
						'topmenulink_custom'	=>	$result['topmenulink_custom']
					);
				
				}
				$i++;
			}	
		}
		
			if($this->data['mattimg']){
					/* Payments icons*/

			if(isset($this->data['mattimg'])){
				foreach ($this->data['mattimg'] as $result) {
					if (file_exists(DIR_IMAGE . $result['image'])) {
				$this->data['mattimg_f'][] = array(
					'title' => $result['title'],
					'image' => $this->model_tool_image->resize($result['image'],50, 30)
				);
			}
				}
			   }
			}
			
			if($this->data['mattnetwork']){
					/* Network icons*/

			if(isset($this->data['mattnetwork'])){
				foreach ($this->data['mattnetwork'] as $result) {
					if (file_exists(DIR_IMAGE . $result['image'])) {
				$this->data['cusom_network'][] = array(
					'title' => $result['title'],
					'href' => $result['href'],
					'image' => $this->model_tool_image->resize($result['image'],30, 30),
				);
			}
				}
			   }
			}

						
		
	    $this->document->cusom_h 		= $this->data['cusom_h'];
		$this->document->cusom_p 		= $this->data['cusom_p'];
		$this->document->cusom_p_tab 	= $this->data['cusom_p_tab'];
		$this->document->cusom_f 		= $this->data['cusom_f'];
		$this->document->Top_m_link 	= $this->data['Top_m_link'];
		$this->document->Header_m_link 	= $this->data['Header_m_link'];
		$this->document->htmlmenu_t 	= $this->data['htmlmenu_t'];
		$this->document->Information_menu = $this->data['informations'];
		$this->document->mattimg_f   	    = $this->data['mattimg_f'];
        $this->document->cusom_payment 		= $this->data['cusom_payment'];
		$this->document->cusom_network 		= $this->data['cusom_network'];
			


	}
}


?>