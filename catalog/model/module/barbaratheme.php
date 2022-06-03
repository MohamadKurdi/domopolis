<?php
class ModelModulebarbaratheme extends Model {	
    public function getSettings() {
        $this->load->model('tool/image');

        // Получаем все настройки темы
        $this->load->model('setting/setting');
        $data = $this->model_setting_setting->getSetting('barbara');
        $barbara_module = $this->config->get('barbara_module');

        // Custom css
	    $barbara_custom_css = isset($data['barbara_custom_css']) ? $data['barbara_custom_css'] : "";
        $data['barbara_custom_css'] = html_entity_decode(trim($barbara_custom_css), ENT_QUOTES, 'UTF-8');

        // Custom js
		 $barbara_custom_js = isset($data['barbara_custom_js']) ? $data['barbara_custom_js'] : "";
        $data['barbara_custom_js'] = html_entity_decode(trim($barbara_custom_js), ENT_QUOTES, 'UTF-8');
		
		 $data['barbara_quick'] = isset($barbara_module['barbara_view_text'][$this->config->get('config_language_id')]) ? $barbara_module['barbara_view_text'][$this->config->get('config_language_id')] : '';
		 $data['barbara_click'] = isset($barbara_module['barbara_click_text'][$this->config->get('config_language_id')]) ? $barbara_module['barbara_click_text'][$this->config->get('config_language_id')] : '';
		 
		 // Soc product js
	    $barbara_product_soc = isset($data['barbara_product_soc']) ? $data['barbara_product_soc'] : "";
        $data['barbara_product_soc'] = html_entity_decode(trim($barbara_product_soc), ENT_QUOTES, 'UTF-8');

        // Header text
        $barbara_header_text = isset($barbara_module['barbara_header_text'][$this->config->get('config_language_id')]) ? $barbara_module['barbara_header_text'][$this->config->get('config_language_id')] : "";
        $data['barbara_header_text'] = html_entity_decode($barbara_header_text, ENT_QUOTES, 'UTF-8');

        // Header phone
        $barbara_header_phone = isset($barbara_module['barbara_header_phone'][$this->config->get('config_language_id')]) ? $barbara_module['barbara_header_phone'][$this->config->get('config_language_id')] : "";
        $data['barbara_header_phone'] = html_entity_decode($barbara_header_phone, ENT_QUOTES, 'UTF-8');
		
		$barbara_header_phone2 = isset($barbara_module['barbara_header_phone2'][$this->config->get('config_language_id')]) ? $barbara_module['barbara_header_phone2'][$this->config->get('config_language_id')] : "";
        $data['barbara_header_phone2'] = html_entity_decode($barbara_header_phone2, ENT_QUOTES, 'UTF-8');
		
		 $barbara_phone_phone = isset($barbara_module['barbara_phone_phone'][$this->config->get('config_language_id')]) ? $barbara_module['barbara_phone_phone'][$this->config->get('config_language_id')] : "";
        $data['barbara_phone_phone'] = html_entity_decode($barbara_phone_phone, ENT_QUOTES, 'UTF-8');
		
		//Related product
		if (isset($data['barbara_product_sop1']) && $data['barbara_product_sop1']!='') { $data['barbara_product_sop1'] = $data['barbara_product_sop1'];} else { $data['barbara_product_sop1'] = 4;}
		if (isset($data['barbara_product_sop2']) && $data['barbara_product_sop2']!='') { $data['barbara_product_sop2'] = $data['barbara_product_sop2'];} else { $data['barbara_product_sop2'] = 4;}
		
		if ($data['barbara_product_sop2'] == 5) { $cols =  25;} elseif ($data['barbara_product_sop2'] > 6) { $cols =  3; } else { $cols =  12/$data['barbara_product_sop2'];}
		$data['cols'] = $cols;
		
		//товаров в мобильной карусели
		   if ($data['barbara_product_sop2'] > 1)  {
			if ($data['barbara_product_sop2'] != 2){$data['numl1'] = $data['barbara_product_sop2'] - 1; } else { $data['numl1'] = 2;}
			} else { $data['numl1'] = 1;}
        if ($data['barbara_product_sop2'] > 2)  {
			if ($data['barbara_product_sop2'] != 3){$data['numl2'] = $data['barbara_product_sop2'] - 2; } else { $data['numl2'] = 2;}
			 } else { $data['numl2'] = 1;}	
		//разбить для owl carousel
		$data['owl_limit'] = ceil($data['barbara_product_sop1']/$data['barbara_product_sop2']);	
		
		//categories menu
		  $categories_menu = $this->config->get('barbaractheader');
		  if (isset($categories_menu) && (count($categories_menu) > 1)) { 
		  $data['categories_menu'] = true;} else {$data['categories_menu'] = false;  } 
		  
		// Footer text

		
		$barbara_footer_address = isset($barbara_module['barbara_footer_address'][$this->config->get('config_language_id')]) ? $barbara_module['barbara_footer_address'][$this->config->get('config_language_id')]: "";
        $data['barbara_footer_address'] = html_entity_decode($barbara_footer_address, ENT_QUOTES, 'UTF-8');
		
	    $barbara_footer_email = isset($barbara_module['barbara_footer_email']) ? $barbara_module['barbara_footer_email']: "";
        $data['barbara_footer_email'] = html_entity_decode($barbara_footer_email, ENT_QUOTES, 'UTF-8');
		
		$barbara_footer_phone = isset($barbara_module['barbara_footer_phone']) ? $barbara_module['barbara_footer_phone']: "";
        $data['barbara_footer_phone'] = html_entity_decode($barbara_footer_phone, ENT_QUOTES, 'UTF-8');
		
		$barbara_footer_copyright = isset($barbara_module['barbara_footer_copyright'][$this->config->get('config_language_id')]) ? $barbara_module['barbara_footer_copyright'][$this->config->get('config_language_id')]: "";
        $data['barbara_footer_copyright'] = html_entity_decode($barbara_footer_copyright, ENT_QUOTES, 'UTF-8');
		
		if (($barbara_footer_address =='') && ($barbara_footer_email == '') && ($barbara_footer_phone == '')) { $data['barbara_footer_contact'] = false; } else {$data['barbara_footer_contact'] = true;}
		
		$barbara_footer_product_title = isset($barbara_module['barbara_footer_product_title'][$this->config->get('config_language_id')]) ? $barbara_module['barbara_footer_product_title'][$this->config->get('config_language_id')] : "";	
        $data['barbara_footer_product_title'] = html_entity_decode($barbara_footer_product_title, ENT_QUOTES, 'UTF-8');
		
        $barbara_footer_product_text = isset($barbara_module['barbara_footer_product_text'][$this->config->get('config_language_id')]) ? $barbara_module['barbara_footer_product_text'][$this->config->get('config_language_id')] : "";
        $data['barbara_footer_product_text'] = html_entity_decode($barbara_footer_product_text, ENT_QUOTES, 'UTF-8');
		
		$barbara_maps_title = isset($barbara_module['barbara_maps_title'][$this->config->get('config_language_id')] ) ? $barbara_module['barbara_maps_title'][$this->config->get('config_language_id')] : "";	
        $data['barbara_maps_title'] = html_entity_decode($barbara_maps_title, ENT_QUOTES, 'UTF-8');
		 $barbara_adress_ymaps = isset($data['barbara_adress_ymaps']) ? $data['barbara_adress_ymaps'] : "";
        $data['barbara_adress_ymaps'] = html_entity_decode(trim($barbara_adress_ymaps), ENT_QUOTES, 'UTF-8');
		
		$barbara_footer_info = isset($barbara_module['barbara_footer_info'][$this->config->get('config_language_id')]) ? $barbara_module['barbara_footer_info'][$this->config->get('config_language_id')]: "";
        $data['barbara_footer_info'] = html_entity_decode($barbara_footer_info, ENT_QUOTES, 'UTF-8');
		
		$barbara_footer_extras = isset($barbara_module['barbara_footer_extras'][$this->config->get('config_language_id')]) ? $barbara_module['barbara_footer_extras'][$this->config->get('config_language_id')]: "";
        $data['barbara_footer_extras'] = html_entity_decode($barbara_footer_extras, ENT_QUOTES, 'UTF-8');
		
		//stily for cap
	  $barbara_logo_place = $this->config->get('barbara_logo_place'); 
	  $barbara_h_call = $this->config->get('barbara_h_call'); 
	  	
       if (!isset($barbara_logo_place) || ($barbara_logo_place == '1'))  { 
	  $data['menuplace'] = 'menuplace menuplace1';
      $data['widthlogo'] = 'col-md-3 col-sm-3 col-xs-6';
      if ( (strlen($data['barbara_phone_phone']) > 11 ) || ((isset($barbara_h_call)) && ($barbara_h_call) )) {   
      $data['widthsearch'] = 'col-lg-8 col-md-12 col-sm-8 col-xs-12'; 
	  } else { 
	  $data['widthsearch'] = 'col-xs-12'; }
	  
      $data['widtphone'] = 'col-lg-4 col-md-12 col-sm-4 col-xs-12';
      $data['headerinfo'] = 'col-lg-7 col-md-6 col-sm-12 col-xs-12';
	   }

      if ($barbara_logo_place == '2')  { 
      $data['menuplace'] = 'menuplace menuplace2'; 
	  $data['widthlogo'] = 'col-md-3 col-sm-3 col-xs-6';
      if ( (strlen($data['barbara_phone_phone'] > 11) ) || ((isset($barbara_h_call)) && ($barbara_h_call) )) {   
      $data['widthsearch'] = 'col-lg-8 col-md-6 col-sm-8 col-xs-12'; 
	  $data['headerinfo'] = 'col-lg-7 col-md-6 col-sm-12 col-xs-12';
       } else { 
	  $data['widthsearch'] = 'col-xs-12';
	  $data['headerinfo'] = 'col-lg-7 col-md-6 col-sm-5 col-xs-12';  }
      $data['widtphone'] = 'col-lg-4 col-md-6 col-sm-4 col-xs-12';
	  }
	// print_r('<pre>');
   //   print_r(strlen($data['barbara_phone_phone']));
  // print_r('</pre>'); 
			
      
      if ($barbara_logo_place == '3')  { 
	  $data['headerinfo'] = 'col-md-9 col-sm-9'; 
	   $data['widthlogo'] = 'col-md-3 col-sm-3 col-xs-6';
	  $data['menuplace'] = 'menuplace menuplace3'; 
      $data['widthsearch'] = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';  
	  $data['widtphone'] = 'col-md-6 col-sm-6 col-xs-12';
	   }
      
      if ($barbara_logo_place == '4')  { 
	  $data['menuplace'] = 'menuplace4';  
	  $data['widthlogo'] = 'col-md-3 col-sm-3 col-xs-6';
	  $data['widthsearch'] = 'col-xs-12';
	  $data['widtphone'] = 'col-lg-4 col-md-12 col-sm-4 col-xs-12';
      $data['headerinfo'] = 'col-lg-7 col-md-6 col-sm-12 col-xs-12'; 
	  }
      if ($barbara_logo_place == '5')  { 
	  $data['menuplace'] = 'menuplace menuplace5'; 
	  $data['widthlogo'] = 'col-md-4 col-sm-4 col-xs-7';
	  $data['widthsearch'] = 'col-xs-12';
	  $data['widtphone'] = 'col-lg-4 col-md-12 col-sm-4 col-xs-12';
      $data['headerinfo'] = 'col-lg-7 col-md-6 col-sm-12 col-xs-12';   
	  }
      if ($barbara_logo_place == '6')  { 
	  $data['menuplace'] = 'menuplace menuplace6';  
	  $data['widthlogo'] = 'col-md-4 col-sm-4 col-xs-7';
	  $data['widthsearch'] = 'col-xs-12';
	  $data['widtphone'] = 'col-lg-4 col-md-12 col-sm-4 col-xs-12';
      $data['headerinfo'] = 'col-lg-7 col-md-6 col-sm-12 col-xs-12';    
	  }
		

		//Payments icons
		 $barbara_paymenticon = $this->config->get('barbara_paymenticon');

			if(isset($barbara_paymenticon)){
				foreach ($barbara_paymenticon as $result) {
					if (is_file(DIR_IMAGE . $result['image'])) {
				    $data['barbara_paymenticon_f'][] = array(
					'title' => $result['title'],
					'image' => $this->model_tool_image->resize($result['image'],50, 30)
				     );
			    }
				}
			   }	   
			   
		/* footer Network icons*/
		 $barbara_webicon = $this->config->get('barbara_webicon');
	
          if(isset($barbara_webicon)){
				foreach ($barbara_webicon as $result) {
					if (is_file(DIR_IMAGE . $result['image']) && ($result['image'] != 'no_image.png')) {
						$image = $this->model_tool_image->resize($result['image'],28, 28);;
					}	else {
						$image = false;
					}
				    $data['barbara_webicon_f'][] = array(
					'title' => $result['title'],
					'awesome' => $result['awesome'],
					'href' => $result['href'],
					'image' => $image,
				);
				}
			   }
			   /* widget*/
		 $barbara_webwidget = $this->config->get('barbara_webwidget');
	
          if(isset($barbara_webwidget)){
				foreach ($barbara_webwidget as $result) {
					if (is_file(DIR_IMAGE . $result['image']) && ($result['image'] != 'no_image.png')) {
						$image = $this->model_tool_image->resize($result['image'],32, 32);
					}	else {
						$image = $this->model_tool_image->resize('no_image.png', 32, 32);
					}
				    $data['barbara_webwidget_r'][] = array(
					'title' => $result['title'],
					'href' => html_entity_decode($result['href'], ENT_QUOTES, 'UTF-8'),
					'image' => $image,
				);
				}
			   }
		/* header Network icons*/
		 $barbara_topwebicon = $this->config->get('barbara_topwebicon');
          if(isset($barbara_topwebicon)){
				foreach ($barbara_topwebicon as $result) {
					if (is_file(DIR_IMAGE . $result['image']) && ($result['image'] != 'no_image.png')) {
						$image = $this->model_tool_image->resize($result['image'],28, 28);;
					}	else {
						$image = false;
					}
				    $data['barbara_topwebicon_f'][] = array(
					'title' => $result['title'],
					'awesome' => $result['awesome'],
					'href' => $result['href'],
					'image' => $image,
				);
			
				}
			   }	      
			   
		/* Featured products*/
		$this->load->model('catalog/product'); 

		$data['barbara_products'] = array();

		$barbara_products = $this->config->get('barbara_featured_product2');	
		
	 if ((isset($barbara_products)) && ($barbara_products)) {
			 
		$barbara_f_limit = $this->config->get('barbara_f_limit');
		
		if (($barbara_f_limit !=='') && (isset($barbara_f_limit))) {
			 $barbara_f_limit = $barbara_f_limit; 
			 } else {
			 $barbara_f_limit = 3; }	

		
		$barbara_products = array_slice($barbara_products, 0, (int)$barbara_f_limit);
		
		$barbara_f_width = $this->config->get('barbara_f_width');
		$barbara_f_height = $this->config->get('barbara_f_height');

		
		if ((isset($barbara_f_width)) && ($barbara_f_width !=='')) {
			 $barbara_f_width = $barbara_f_width;
			 } else {
			 $barbara_f_width = 68;
			 }
			 
		if ((isset($barbara_f_height)) && ($barbara_f_height !=='')) {
			 $barbara_f_height = $barbara_f_height; 
			 } else {
			 $barbara_f_height = 68;
}	 

		
		foreach ($barbara_products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'],  $barbara_f_width, $barbara_f_height);
				} else {
					$image = false;
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}
						
				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}
				
					
				$data['barbara_products'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb'   	 => $image,
					'name'    	 => $product_info['name'],
					'price'   	 => $price,
					'special' 	 => $special,
					'href'    	 => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
				);
			}
		}
	 }
		
		//Schetchiki
		 $barbara_schet = $this->config->get('barbara_schet');

			if(isset($barbara_schet)){
				foreach ($barbara_schet as $result) {
				    $data['barbara_schet_f'][] = array(
					'title' => html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'),
				     );
				}
			   }
	/*Product icon text*/
			   		
             $barbara_product_icons = $this->config->get('barbaraprod');
			 
			 $barbara_product_wicon1 = $this->config->get('barbara_product_wicon1');
			 $barbara_product_wicon2 = $this->config->get('barbara_product_wicon2');
			 
			 if ( (isset($barbara_product_wicon1)) && ($barbara_product_wicon1 !=='')) {
			 $barbara_product_wicon1 = $barbara_product_wicon1;
			 } else {
			 $barbara_product_wicon1 = 42;
			 }
			 
		if ( (isset($barbara_product_wicon2)) && ($barbara_product_wicon2 !=='')) {
			 $barbara_product_wicon2 = $barbara_product_wicon2;
			 } else {
			 $barbara_product_wicon2 = 42;
			 }	 

			
			if(isset($barbara_product_icons)){
				foreach ($barbara_product_icons as $result) {
					
          if (($result['image'])) {
            $image = $this->model_tool_image->resize($result['image'],  $barbara_product_wicon1, $barbara_product_wicon2);
          } else{
            $image = false;	
          }
            
            
          $data['barbara_product_icon'][] = array(
            'title' => html_entity_decode($result['title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8'),
            'text' => html_entity_decode($result['text'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8'),
            'image' => $image,
          );
			  }		   
      }   
			/*header icon text*/
			   		
             $barbara_topcustom_icons = $this->config->get('barbaratopheader');
			 $barbara_header_wicon1 = $this->config->get('barbara_header_wicon1');
		     $barbara_header_wicon2 = $this->config->get('barbara_header_wicon2');
			 
			 if ( (isset($barbara_header_wicon1)) && ($barbara_header_wicon1 !=='')) {
			 $barbara_header_wicon1 = $barbara_header_wicon1;
			 } else {
			 $barbara_header_wicon1 = 48;
			 }
			 if ( (isset($barbara_header_wicon2)) && ($barbara_header_wicon2 !=='')) {
			 $barbara_header_wicon2 = $barbara_header_wicon2;
			 } else {
			 $barbara_header_wicon2 = 48;
			 }
			 
			 
			 $barbara_top_left = $this->config->get('barbara_top_left');
			 if (isset($barbara_top_left) && ($barbara_top_left)) {
			$data['barbara_wicon2'] = 'topinfo-img-left';
			} else {$data['barbara_wicon2'] = 'topinfo-img-top'; }

			if(isset($barbara_topcustom_icons)){
				foreach ($barbara_topcustom_icons as $result) {
					
          if (($result['image'])) {
            $image = $this->model_tool_image->resize($result['image'],  $barbara_header_wicon1, $barbara_header_wicon2);
		    $barbara_wicon =  $barbara_header_wicon1 + 6;
			if (isset($barbara_top_left) && ($barbara_top_left)) {
			$data['barbara_wicon'] = 'style="margin-left:' . $barbara_wicon . 'px; min-height:' . $barbara_header_wicon2 . 'px;"';
			} else {
				$data['barbara_wicon'] = ''; }
          } else{
            $image = false;	
          }
            
            
          $data['barbara_topcustom_icon'][] = array(
            'title' => html_entity_decode($result['title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8'),
			'text' => html_entity_decode($result['text'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8'),
            'image' => $image,
          );
			  }
			   
	
				$barbara_class_topcustom = count($barbara_topcustom_icons);
				if ($barbara_class_topcustom == 5) {
          $data['barbara_class_topcustom'] =  25;
				} elseif ($barbara_class_topcustom > 6) {
          $data['barbara_class_topcustom'] =  2;
				} else {
          $data['barbara_class_topcustom'] =  12/$barbara_class_topcustom;
        }
      }	
	  
	  	/*Options in category  */
		$data['barbara_optioncateg'] = array();
        $data['barbara_optioncateg'] = $this->config->get('barbara_optioncateg');	 
		
		/*Footer icon text*/
			   		
             $barbara_custom_icons = $this->config->get('barbaraheader');
			 $barbara_footer_wicon1 = $this->config->get('barbara_footer_wicon1');
		     $barbara_footer_wicon2 = $this->config->get('barbara_footer_wicon2');
			 
			 if ( (isset($barbara_footer_wicon1)) && ($barbara_footer_wicon1 !=='')) {
			 $barbara_footer_wicon1 = $barbara_footer_wicon1;
			 } else {
			 $barbara_footer_wicon1 = 64;
			 }
			 if ( (isset($barbara_footer_wicon2)) && ($barbara_footer_wicon2 !=='')) {
			 $barbara_footer_wicon2 = $barbara_footer_wicon2;
			 } else {
			 $barbara_footer_wicon2 = 64;
			 }
			
			if(isset($barbara_custom_icons)){
				foreach ($barbara_custom_icons as $result) {
					
          if (($result['image'])) {
            $image = $this->model_tool_image->resize($result['image'],  $barbara_footer_wicon1, $barbara_footer_wicon2);
          } else{
            $image = false;	
          }
            
            
          $data['barbara_custom_icon'][] = array(
            'title' => html_entity_decode($result['title'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8'),
            'href' => $result['href'][$this->config->get('config_language_id')],
            'image' => $image,
          );
			  }
			   
	
				$barbara_class_custom = count($barbara_custom_icons);
				if ($barbara_class_custom == 5) {
          $data['barbara_class_custom'] =  25;
				} elseif ($barbara_class_custom > 6) {
          $data['barbara_class_custom'] =  2;
				} else {
          $data['barbara_class_custom'] =  12/$barbara_class_custom;
        }
      }

      return $data;
    }

     public function isModuleInstalled($type, $code) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");
        return $query->num_rows;
    }
}
?>