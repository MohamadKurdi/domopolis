<?php
class ControllerCommonbarbaratheme extends Controller {
	public function index() {
	  $data['button_cart'] = $this->language->get('button_cart');
      $this->document->addStyle('catalog/view/theme/barbaratheme/stylesheet/barbaramenu.css');
	  
	  $lang_id = $this->config->get('config_language_id');
        
      $this->load->model('catalog/information');  
      $this->load->model('catalog/manufacturer');
	  
	  $this->load->language('module/barbaratheme');
	   
      $this->load->model('catalog/category');
      $this->load->model('catalog/product');
      $this->load->model('tool/image');
      $this->load->model('catalog/review');
      $this->load->model('barbarablog/category');
      
	
		
       // Получаем все настройки темы
        $this->load->model('setting/setting');
        $data = $this->model_setting_setting->getSetting('barbara');
        $barbara_module = $this->config->get('barbara_module');
							
	 if (isset($barbara_module['barbara_catalog_name'][$lang_id]) && ($barbara_module['barbara_catalog_name'][$lang_id] != '')) {
		 $barbara_catalog_name = $barbara_module['barbara_catalog_name'][$lang_id]; 
		 } else {
		 $barbara_catalog_name = $this->language->get('text_menu');}
      $data['barbara_catalog_name'] = html_entity_decode($barbara_catalog_name, ENT_QUOTES, 'UTF-8');	
	  
	   $data['barbara_logo_place'] = $this->config->get('barbara_logo_place');
	   $data['barbara_show_catalog'] = $this->config->get('barbara_show_catalog');
	    $data['barbara_show_catalogup'] = $this->config->get('barbara_show_catalogup');

        $barbaramnheaders = $this->config->get('barbaramnheader');

			 $barbara_icon_width = $this->config->get('barbara_icon_width');
		     $barbara_icon_height = $this->config->get('barbara_icon_height');
			 $barbara_show_icon = $this->config->get('barbara_show_icon');
			 
			 
			 if ( (isset($barbara_icon_width)) && ($barbara_icon_width !=='')) {
			 $barbara_icon_width =  $this->config->get('barbara_icon_width');
			 } else {
			 $barbara_icon_width = 30;
			 }
			 if ( (isset($barbara_icon_height)) && ($barbara_icon_height !=='')) {
			 $barbara_icon_height = $this->config->get('barbara_icon_height');
			 } else {
			 $barbara_icon_height = 30;
			 }
			 
			 if ($barbara_show_icon == '0') { $data['show_icon'] =  'ic-left';} else { $data['show_icon'] =  'ic-top'; } 
			 
			  $data['barbara_catalog_fon'] =  $this->config->get('barbara_catalog_fon');
			  $data['barbara_catalog_colortext'] =  $this->config->get('barbara_catalog_colortext');
			
			if(isset($barbaramnheaders)){
			
			 if (isset($barbaramnheaders[0])) {unset($barbaramnheaders[0]);}	
			 if (count($barbaramnheaders)) {uasort($barbaramnheaders, array($this, 'compareBySortOrder'));	
				
				
				foreach ($barbaramnheaders as $tab) {
					
			if (!isset($tab['image_h']) || ($tab['image_h'] == '')) { $tab['image_h'] = 150; }
            if (!isset($tab['image_w']) || ($tab['image_w'] == '')) { $tab['image_w'] = 150; }
            if (!isset($tab['brand_h']) || ($tab['brand_h'] == '') || ($tab['brand_h'] == 0)) { $brand_h = 0; } else {$brand_h = $tab['brand_h'];}
            if (!isset($tab['brand_w']) || ($tab['brand_w'] == '') || ($tab['brand_w'] == 0)) { $brand_w = 0; } else {$brand_w = $tab['brand_h'];}
            if (!isset($tab['image_categ_h']) || ($tab['image_categ_h'] == '') || ($tab['image_categ_h'] == '0')) { $tab['image_categ_h'] = false; $image_categ_h = false;  }
            if (!isset($tab['image_categ_w']) || ($tab['image_categ_w'] == '') || ($tab['image_categ_w'] == '0')) { $tab['image_categ_w'] = false; }
			 if (!isset($tab['image_maincateg_h']) || ($tab['image_maincateg_h'] == '') || ($tab['image_maincateg_h'] == '0')) { $tab['image_maincateg_h'] = false; $image_maincateg_h = false;  }
            if (!isset($tab['image_maincateg_w']) || ($tab['image_maincateg_w'] == '') || ($tab['image_maincateg_w'] == '0')) { $tab['image_maincateg_w'] = false; }
            if (!isset($tab['image_allcateg_h']) || ($tab['image_allcateg_h'] == '') || ($tab['image_allcateg_h'] == '0')) { $tab['image_allcateg_h'] =false; }
            if (!isset($tab['image_allcateg_w']) || ($tab['image_allcateg_w'] == '') || ($tab['image_allcateg_w'] == '0')) { $tab['image_allcateg_w'] = false; }
            if (!isset($tab['image_limit']) || ($tab['image_limit'] == '0') || ($tab['image_limit'] == '') ) { $image_limit = false; } else { $image_limit = true; }
            if (!isset($tab['brand_column']) || ($tab['brand_column'] == '0') || ($tab['brand_column'] == '') ) { $brand_column = false; } else { $brand_column = true; }           
            if (!isset($tab['categ_sub']) || ($tab['categ_sub'] == '1')) {$categ_sub = false;} else { $categ_sub = $tab['categ_sub']; }
			 if (!isset($tab['column_mainlimit'])) {$tab['column_mainlimit'] = 1;}
              
            //home
            if ($tab['filter_type'] == "home") {
                $link = $this->url->link('common/home');
            }
              
            //news	
            if ($tab['filter_type'] == "news") {
                $link = $this->url->link('barbarablog/home');
            }
              
                 //special
            if ($tab['filter_type'] == "special") {
                $link = $this->url->link('product/special');
            
                if ($image_limit) {
                    $filter_data = array(
                      'sort'  => 'pd.name',
                      'order' => 'ASC',
                      'start' => 0,
                      'limit' => $tab['image_limit'],
                    );
                    $results = array();
                    $results = $this->model_catalog_product->getProductSpecials($filter_data);
                    
                    $products = array();
                    
                    foreach ($results as $result) {
                      
                      if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $tab['image_w'], $tab['image_h']);
                      } else {
                        $image = false;
                      }

                      if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                      } else {
                        $price = false;
                      }
                          
                      if ((float)$result['special']) { 
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                      } else {
                        $special = false;
                      }
                      
                      if ($this->config->get('config_review_status')) {
                        $rating = $result['rating'];
                      } else {
                        $rating = false;
                      }
                      $products[] = array(
                        'product_id'   => $result['product_id'],
                        'thumb'   	   => $image,
                        'name'    	   => $result['name'],
                        'price'   	   => $price,
                        'special' 	   => $special,
                        'saving'	   	 => round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
                        'href'    	   => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                      );
                    }
                } else { $products = false; }
              
            }
              
              //brand
              if ($tab['filter_type'] == "brand") {
                $results_brand = $this->model_catalog_manufacturer->getManufacturers();
                $link = $this->url->link('product/manufacturer');
                
                if ($brand_column) {
                    $filter_data = array();
                    $brand = array();
                    $manufacturers = $this->model_catalog_manufacturer->getManufacturers($filter_data);
                    if($manufacturers){
                      foreach($manufacturers as $manufacturer){
                        if (($manufacturer['image']) && ($brand_w != 0) && ($brand_h != 0)) {
                          $image = $this->model_tool_image->resize($manufacturer['image'], $brand_w, $brand_h);
                        } else {
                          $image = false;
                        }
                        $brand[] = array(
                          'name' => $manufacturer['name'],
                          'img'  => $image,
                          'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id='.$manufacturer['manufacturer_id'])
                        );
                      }
                    } 
                    $brand_column = $tab['brand_column'];
                } else {$brand = false;}
              }
              
              
            //choose information	
              if ($tab['filter_type'] == "one_info") {
                if (isset($tab['filter_type_info'])) {
                $link = $this->url->link('information/information', 'information_id=' . $tab['filter_type_info']);
				} else { $link = '';}
                     }
					 
			  //choose news	
              if ($tab['filter_type'] == "one_news") {
                 if (isset($tab['filter_type_news'])) {
                $link = $this->url->link('barbarablog/category', 'barbara_blog_path=' . $tab['filter_type_news']);
				} else { $link = '';}
                     }		 
               
            //information	
              if ($tab['filter_type'] == "info") {
                $punkt_info = $this->model_catalog_information->getInformations();
                $link = false;
                
                $inform = array();
            
								foreach ($this->model_catalog_information->getInformations() as $key => $result) {
									if  (isset($tab['filter_type_allinfo'.$result['information_id']])) {
										$inform[] = array(
											'title' => $result['title'],
											'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
										);
									} 
								}
               
              }
          
            //custom link	
              if ($tab['filter_type'] == "link_custom") {
                 if (isset($tab['filter_type_link'][$lang_id]) && ($tab['filter_type_link'][$lang_id] != '') && (isset($lang_id))) {
                $link = $tab['filter_type_link'][$lang_id];
                 } else {
                $link = '';
                 }
              }
            //contact	
              if ($tab['filter_type'] == "contact") {
                $link = $this->url->link('information/contact');
              }
              
            //custom block	
              if ($tab['filter_type'] == "box_custom") {
                 if (isset($tab['htmlbox_link'][$lang_id]) && ($tab['htmlbox_link'][$lang_id] != '') && (isset($lang_id))) {
                $link = $tab['htmlbox_link'][$lang_id];
                 } else {
                $link = false;
                 }
                if (($tab['htmlbox'][$lang_id] != '') && (isset($lang_id))) {
									$htmlbox = html_entity_decode($tab['htmlbox'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8' );
                } else {
									$htmlbox = false;
								}
              }
              
          //one category
              if ($tab['filter_type'] == "category") {
				  
				 if (isset($tab['filter_type_category'])) { 
                $filter_data = array(
                  'filter_category_id' => $tab['filter_type_category'],
                  'sort'  => 'pd.name',
                  'order' => 'ASC',
                  'start' => 0,
                );
                $children = $this->model_catalog_category->getCategories($tab['filter_type_category']);
                $link = $this->url->link('product/category', 'path=' . $tab['filter_type_category']);                
                        
                if  ($categ_sub) {
                  $column = $categ_sub;
                } else {
                  $column = 1;
                };
                
                $categ_data = array();
                foreach ($children as $child) {
                    //Будем вычислять кол-во товаров в категориях только если это кол-во надо показывать
                    if ($this->config->get('config_product_count')) {
                        $filter_data = array(
                            'filter_category_id'  => $child['category_id'],
                            'filter_sub_category' => true,
                          );
                         
                         $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
                    }
					 if (isset($child['image']) && ($tab['image_categ_h']) && ($tab['image_categ_w'])){
						$image   = $this->model_tool_image->resize($child['image'], $tab['image_categ_w'], $tab['image_categ_h']); 
						if ($tab['image_categ_h'] > 20) { $image_categ_h = ceil($tab['image_categ_h']/2) + 26;} else { $image_categ_h = false;}
                } else {
						$image = false;
						$image_categ_h = false;   
                }
				 //3 level
				 
								$level3 = false;
					//*** Получаем список подкатегорий для каждой категории третьего уровня
								$subchildren = $this->model_catalog_category->getCategories($child['category_id']);
 
								    //*** Обнуляем массив для каждого набора подкатегорий третьего уровня
									$subchildren_data = array(); 
 
										//*** Получаем список подкгатегорий для каждой категории третьего уровня
										foreach ($subchildren as $subchild) {
												//Будем вычислять кол-во товаров в категориях только если это кол-во надо показывать
												if ($this->config->get('config_product_count')) {
													$filter_data = array(
														'filter_category_id'  => $subchild['category_id'],
														'filter_sub_category' => true
													);
 
													$product_subtotal = $this->model_catalog_product->getTotalProducts($filter_data);
												}
												
												//*** Получаем список подкатегорий для каждой категории третьего уровня формируем массив со списками подкатегорий третьего уровня
												$subchildren_data[] = array(
													'category_id' => $subchild['category_id'],
													'name'  => $subchild['name'] . ($this->config->get('config_product_count') ? ' (' . $product_subtotal . ')' : ''),
													'href'  => $this->url->link('product/category', 'path=' . $child['category_id'] . '_' . $child['category_id']. '_' . $subchild['category_id'])	
 
												);						
											}					
			;
           
                    $categ_data[] = array(
                      'category_id'  => $child['category_id'],
                      'name'         => $child['name']. ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
                      'href'         => $this->url->link('product/category', 'path=' . $tab['filter_type_category'] . '_' . $child['category_id']),
                      //*** Добавляем к каждой категории первого уровня массив с подкатегориями третьего уровня
					  'children'      => $subchildren_data,
					  'img'           => $image,
					  'show_sub_cat'   => isset($tab['show_sub_cat']) ? $tab['show_sub_cat'] : 0,
                    );
              }  
              } else {
				  $link = '';
				  $categ_data = false;  
			  }
				  
			  }
			  
			  //choisecategory
              if ($tab['filter_type'] == "choisecategory") {
                $punkt_choisecategory = $this->model_catalog_category->getCategories(0);
                 if (isset($tab['maincateg_link'][$lang_id]) && ($tab['maincateg_link'][$lang_id] != '') && (isset($lang_id))) {
                $link = $tab['maincateg_link'][$lang_id];
                 } else {
                $link = false;
                 }
				 $column_mainlimit = $tab['column_mainlimit']; 
                
                $choisecategory = array();
            
				foreach ($punkt_choisecategory as $key => $result) {
				if  (isset($tab['filter_type_choisecategory'.$result['category_id']])) {
				// Level 2
                   $children_data = array();
              
                   $children = $this->model_catalog_category->getCategories($result['category_id']);
              
                   foreach ($children as $child) {
                //Будем вычислять кол-во товаров в категориях только если это кол-во надо показывать
                if ($this->config->get('config_product_count')) {
                  $filter_data = array(
                    'filter_category_id'  => $child['category_id'],
                    'filter_sub_category' => true
                  );
                  
                  $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
                }
                        
                //3 level
       
                //*** Получаем список подкгатегорий для каждой категории второго уровня
                      $subchildren = $this->model_catalog_category->getCategories($child['category_id']);
       
                          //*** Обнуляем массив для каждого набора подкатегорий вторго уровня
                        $subchildren_data = array(); 
       
                          //*** Получаем список подкгатегорий для каждой категории второго уровня
                          foreach ($subchildren as $subchild) {
                              //Будем вычислять кол-во товаров в категориях только если это кол-во надо показывать
                              if ($this->config->get('config_product_count')) {
                                $filter_data = array(
                                  'filter_category_id'  => $subchild['category_id'],
                                  'filter_sub_category' => true
                                );
       
                                $product_subtotal = $this->model_catalog_product->getTotalProducts($filter_data);
                              }
       
                              //*** Получаем список подкгатегорий для каждой категории второго уровня формируем массив со списками подкатегорий второго уровня
       
                              $subchildren_data[] = array(
                                'name'  => $subchild['name'] . ($this->config->get('config_product_count') ? ' (' . $product_subtotal . ')' : ''),
                                'href'  => $this->url->link('product/category', 'path=' . $result['category_id'] . '_' . $child['category_id']. '_' . $subchild['category_id'])	
       
                              );						
                            }	
                            
                                    
                         
                $children_data[] = array(
                  'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
                  'href'  => $this->url->link('product/category', 'path=' . $result['category_id'] . '_' . $child['category_id']),
                
                  //*** Добавляем к каждой категории первого уровня массив с подкатегориями второго уровня						
                  'subchildren' => $subchildren_data,
       
                );						
       
              }
       
               if (($tab['image_maincateg_h']) && ($tab['image_maincateg_w']) && ($result['image'])){
                 $img_allcategory   = $this->model_tool_image->resize($result['image'], $tab['image_maincateg_w'], $tab['image_maincateg_h']); 
				 if ($tab['image_maincateg_h'] > 20) { $image_maincateg_h = ceil($tab['image_maincateg_h']/2) + 26;} else { $image_maincateg_h = false;}
                 } else {
                $img_allcategory = false; 
				$image_maincateg_h = false;  
                 }			
              
					
				$choisecategory[] = array(
				'name' => $result['name'],
				'href'  => $this->url->link('product/category', 'path=' . $result['category_id']),
				'children' => $children_data,
                'column'   => $result['column'] ? $result['column'] : 1,
				'show_sub_main'   => isset($tab['show_sub_main']) ? $tab['show_sub_main'] : 0,
                'img'      => $img_allcategory,
				'image_maincateg_h'   => $image_maincateg_h,
				);
		
				} 
               
              }
			  }
			    
			  
               
            //all categories   
               if ($tab['filter_type'] == "all_category") {
                if (isset($tab['allcateg_link'][$lang_id]) && ($tab['allcateg_link'][$lang_id] != '') && (isset($lang_id))) {
                $link = $tab['allcateg_link'][$lang_id];
                 } else {
                $link = false;
                 }
				 
                $column = isset($tab['column_limit']) ? $tab['column_limit'] : 3;
				if ($column == 25 ) {$column_mainlimit = 5; } else { $column_mainlimit = (12/$column);}
				
                $categories = $this->model_catalog_category->getCategories(0);
                $all_categories = array();
                
                foreach ($categories as $category) {
                if ($category['top']) {
                  // Level 2
                   $children_data = array();
              
                   $children = $this->model_catalog_category->getCategories($category['category_id']);
              
                   foreach ($children as $child) {
                //Будем вычислять кол-во товаров в категориях только если это кол-во надо показывать
                if ($this->config->get('config_product_count')) {
                  $filter_data = array(
                    'filter_category_id'  => $child['category_id'],
                    'filter_sub_category' => true
                  );
                  
                  $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
                }
                             
                         
                $children_data[] = array(
                  'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
                  'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
       
                );						
       
              }
       
               if (($tab['image_allcateg_h']) && ($tab['image_allcateg_w']) && ($category['image'])){
                 $img_allcategory   = $this->model_tool_image->resize($category['image'], $tab['image_allcateg_w'], $tab['image_allcateg_h']); 
                 } else {
                $img_allcategory = false;   
                 }			
              
              // Level 1
              $all_categories[] = array(
                'name'     => $category['name'],
                'children' => $children_data,
                'column'   => $category['column'] ? $category['column'] : 1,
                'img'      => $img_allcategory,
				'show_sub_all'   => isset($tab['show_sub_all']) ? $tab['show_sub_all'] : 0,
                'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
              );
          }
      
            }
             
            }
            
            if ($tab['filter_type'] != "brand")        { $brand = false; }
            if ($tab['filter_type'] != "info")         { $inform = false; }
            if ($tab['filter_type'] != "box_custom")   { $htmlbox = false; }
            if ($tab['filter_type'] != "category")     { $categ_data = false; $image_categ_h = false;  }
            if ($tab['filter_type'] != "special")      { $products = false; }
            if ($tab['filter_type'] != "all_category") { $all_categories = false; }
			if ($tab['filter_type'] != "choisecategory")     { $choisecategory = false; }
            if ($tab['color'] != "") { $color = $tab['color']; } else {$color = false;}
            if ($tab['colortext'] != "") { $colortext = $tab['colortext']; } else {$colortext = false;}
      

            if ( ($tab['title'][$lang_id] =='') || ($tab['title'][$lang_id] =='Enter the name')){
              
                if ($tab['filter_type'] == "home") {$title = 'Home';}  
                if ($tab['filter_type'] == "news") {$title = 'News';} 
                if ($tab['filter_type'] == "special") {$title = 'Special';} 
                if ($tab['filter_type'] == "brand") {$title = 'Brand';}
                if ($tab['filter_type'] == "info") {$title = 'Information';}
                if ($tab['filter_type'] == "link_custom") {$title = 'Link';}  
                if ($tab['filter_type'] == "contact") {$title = 'Contact Us';} 
                if ($tab['filter_type'] == "box_custom") {$title = 'Custom block';} 
                if ($tab['filter_type'] == "all_category") {$title = 'Shop';}
				 if ($tab['filter_type'] == "choisecategory") {$title = 'Categories';}
                
                if ($tab['filter_type'] == "category") {
					if (isset($tab['filter_type_category'])) {
                    $category_name = $this->model_catalog_category->getCategory($tab['filter_type_category']);}
                    if (isset($category_name['name'])) {
                        $title = $category_name['name'];
                    } else {
                         $title = false;
                    }
                }
                
                if ($tab['filter_type'] == "one_info")   {
					 if (isset($tab['filter_type_info'])) {
                    $info_name = $this->model_catalog_information->getInformation($tab['filter_type_info']);}
                    if (isset($info_name['title'])) {
                        $title = $info_name['title'];
                    } else {
                         $title = false;
                    }
                }
				
				    if ($tab['filter_type'] == "one_news") {
					if (isset($tab['filter_type_news'])) {
                    $info_news = $this->model_barbarablog_category->getCategory($tab['filter_type_news']);}
                    if (isset($info_news['name'])) {
                        $title = $info_news['name'];
                    } else {
                         $title = false;
                    }
                }   
                  

              } else {
                $title = htmlspecialchars_decode($tab['title'][$lang_id], ENT_QUOTES );	
              }
              
              if (!isset($column)) { $column = false; }
			  if (!isset($column_mainlimit)) { $column_mainlimit = false; }
              
              if ($image_limit) {
                if ($tab['image_limit'] == 5) { $image_limit =  25;} elseif ($tab['image_limit'] > 6) { $image_limit =  2;
                                                                   } else { $image_limit =  12/$tab['image_limit'];}
              }
               
              
              if (($tab['image']) && ($tab['image']) != 'no_image.png')  {
                $imageicon=  $this->model_tool_image->resize($tab['image'],$barbara_icon_width, $barbara_icon_height);
                } else { 
                $imageicon = false; } 
          
            $data['tabs'][] = array(
                'title'           => $title,
                'link'            => $link,
                'products'        => $products,
                'info'            => $inform,
                'brand'           => $brand,
                'brand_column'    => $brand_column,
                'categ'           => $categ_data,
				'categ_sub'       => $categ_sub,
                'column'          => $column,
                'image_limit'     => $image_limit,
                'all_categories'  => $all_categories,
                'htmlbox'         => $htmlbox,
                'color'           => $color,
                'colortext'       => $colortext,
				'choisecategory'  => $choisecategory,
				'column_mainlimit' => $column_mainlimit,
                'image'           => $imageicon,
				'image_categ_h'   => isset($image_categ_h) ? $image_categ_h : false
				
              );

			  }
		
				
      }	
			}
			
	//catalog categories
	        $barbaractheaders = $this->config->get('barbaractheader');
			 $barbara_catalog_width = $this->config->get('barbara_catalog_width');
		     $barbara_catalog_height = $this->config->get('barbara_catalog_height');
			 
			 if ( (isset($barbara_catalog_width)) && ($barbara_catalog_width !=='')) {
			 $barbara_catalog_width =  $this->config->get('barbara_catalog_width');
			 } else {
			 $barbara_catalog_width = 20;
			 }
			 if ( (isset($barbara_catalog_height)) && ($barbara_catalog_height !=='')) {
			 $barbara_catalog_height = $this->config->get('barbara_catalog_height');
			 } else {
			 $barbara_catalog_height = 20;
			 }
			 if ($barbara_catalog_height > 20) { $data['icon_height'] = ceil($barbara_catalog_height/2) + 26;} else { $data['icon_height'] = false;} 
			 
			 
     if(isset($barbaractheaders)){
			
			 if (isset($barbaractheaders[0])) {unset($barbaractheaders[0]);}	
			 if (count($barbaractheaders)) {uasort($barbaractheaders, array($this, 'compareBySortOrder'));	
				
				
				foreach ($barbaractheaders as $rasdel) {
					
				 if (!isset($rasdel['categ_sub']) || ($rasdel['categ_sub'] == '1')) {$rasdel['categ_sub'] = false;}	
				 if (!isset($rasdel['image_categ_h']) || ($rasdel['image_categ_h'] == '') || ($rasdel['image_categ_h'] == '0')) { $rasdel['image_categ_h'] = false; $image_categ_h = false;  }
                 if (!isset($rasdel['image_categ_w']) || ($rasdel['image_categ_w'] == '') || ($rasdel['image_categ_w'] == '0')) { $rasdel['image_categ_w'] = false; }
				 if (!isset($rasdel['image_maincateg_h']) || ($rasdel['image_maincateg_h'] == '') || ($rasdel['image_maincateg_h'] == '0')) { $rasdel['image_maincateg_h'] = false; $image_maincateg_h = false;  }
                 if (!isset($rasdel['image_maincateg_w']) || ($rasdel['image_maincateg_w'] == '') || ($rasdel['image_maincateg_w'] == '0')) { $rasdel['image_maincateg_w'] = false; }
				 if (!isset($rasdel['column_mainlimit'])) {$rasdel['column_mainlimit'] = 1;}

                 //special
            if ($rasdel['filter_type'] == "special") {
                $link = $this->url->link('product/special');              
            }

          
            //custom link	
              if ($rasdel['filter_type'] == "link_custom") {
                 if (isset($rasdel['filter_type_link'][$lang_id]) && ($rasdel['filter_type_link'][$lang_id] != '') && (isset($lang_id))) {
                $link = $rasdel['filter_type_link'][$lang_id];
                 } else {
                $link = '';
                 }
              }

              
            //custom block	
              if ($rasdel['filter_type'] == "box_custom") {
                 if (isset($rasdel['htmlbox_link'][$lang_id]) && ($rasdel['htmlbox_link'][$lang_id] != '') && (isset($lang_id))) {
                $link = $rasdel['htmlbox_link'][$lang_id];
                 } else {
                $link = false;
                 }
                if (($rasdel['htmlbox'][$lang_id] != '') && (isset($lang_id))) {
									$htmlbox = html_entity_decode($rasdel['htmlbox'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8' );
                } else {
									$htmlbox = false;
								}
              }
			  //choisecategory
              if ($rasdel['filter_type'] == "choisecategory") {
                $punkt_choisecategory = $this->model_catalog_category->getCategories(0);
                 if (isset($rasdel['maincateg_link'][$lang_id]) && ($rasdel['maincateg_link'][$lang_id] != '') && (isset($lang_id))) {
                $link = $rasdel['maincateg_link'][$lang_id];
                 } else {
                $link = false;
                 }
				 $column_mainlimit = $rasdel['column_mainlimit'];  
                
                $choisecategory = array();
            
				foreach ($punkt_choisecategory as $key => $result) {
				if  (isset($rasdel['filter_type_choisecategory'.$result['category_id']])) {
				// Level 2
                   $children_data = array();
              
                   $children = $this->model_catalog_category->getCategories($result['category_id']);
              
                   foreach ($children as $child) {
                //Будем вычислять кол-во товаров в категориях только если это кол-во надо показывать
                if ($this->config->get('config_product_count')) {
                  $filter_data = array(
                    'filter_category_id'  => $child['category_id'],
                    'filter_sub_category' => true
                  );
                  
                  $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
                }
                        
                //3 level
       
                //*** Получаем список подкгатегорий для каждой категории второго уровня
                      $subchildren = $this->model_catalog_category->getCategories($child['category_id']);
       
                          //*** Обнуляем массив для каждого набора подкатегорий вторго уровня
                        $subchildren_data = array(); 
       
                          //*** Получаем список подкгатегорий для каждой категории второго уровня
                          foreach ($subchildren as $subchild) {
                              //Будем вычислять кол-во товаров в категориях только если это кол-во надо показывать
                              if ($this->config->get('config_product_count')) {
                                $filter_data = array(
                                  'filter_category_id'  => $subchild['category_id'],
                                  'filter_sub_category' => true
                                );
       
                                $product_subtotal = $this->model_catalog_product->getTotalProducts($filter_data);
                              }
       
                              //*** Получаем список подкгатегорий для каждой категории второго уровня формируем массив со списками подкатегорий второго уровня
       
                              $subchildren_data[] = array(
                                'name'  => $subchild['name'] . ($this->config->get('config_product_count') ? ' (' . $product_subtotal . ')' : ''),
                                'href'  => $this->url->link('product/category', 'path=' . $result['category_id'] . '_' . $child['category_id']. '_' . $subchild['category_id'])	
       
                              );						
                            }	
                            
                                    
                         
                $children_data[] = array(
                  'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
                  'href'  => $this->url->link('product/category', 'path=' . $result['category_id'] . '_' . $child['category_id']),
                
                  //*** Добавляем к каждой категории первого уровня массив с подкатегориями второго уровня						
                  'subchildren' => $subchildren_data,
       
                );						
       
              }
       
               if (($rasdel['image_maincateg_h']) && ($rasdel['image_maincateg_w']) && ($result['image'])){
                 $img_allcategory   = $this->model_tool_image->resize($result['image'], $rasdel['image_maincateg_w'], $rasdel['image_maincateg_h']);
				 if ($rasdel['image_maincateg_h'] > 20) { $image_maincateg_h = ceil($rasdel['image_maincateg_h']/2) + 26;} else { $image_maincateg_h = false;} 
                 } else {
                $img_allcategory = false; 
				$image_maincateg_h = false;  
                 }			
        
										
				$choisecategory[] = array(
				'name' => $result['name'],
				'href'  => $this->url->link('product/category', 'path=' . $result['category_id']),
				'children' => $children_data,
                'column'   => $result['column'] ? $result['column'] : 1,
                'img'      => $img_allcategory,
				'categ_sub_main'   => isset($rasdel['categ_sub_main']) ? $rasdel['categ_sub_main'] : 0,
			    'image_maincateg_h'   => $image_maincateg_h
				);
		
				} 
               
              }
			  }
			    
			  
              
          //one category
              if ($rasdel['filter_type'] == "category") {
				  
				 if (isset($rasdel['filter_type_category'])) { 
                $filter_data = array(
                  'filter_category_id' => $rasdel['filter_type_category'],
                  'sort'  => 'pd.name',
                  'order' => 'ASC',
                  'start' => 0,
                );
                $children = $this->model_catalog_category->getCategories($rasdel['filter_type_category']);
                $link = $this->url->link('product/category', 'path=' . $rasdel['filter_type_category']);
                $categ_sub = $rasdel['categ_sub'];  
                 
                 if  ($categ_sub) {
                  $column = $rasdel['categ_sub'];
                } else {
                  $column = 1;
                };
                
                $categ_data = array();
                foreach ($children as $child) {
                    //Будем вычислять кол-во товаров в категориях только если это кол-во надо показывать
                    if ($this->config->get('config_product_count')) {
                        $filter_data = array(
                            'filter_category_id'  => $child['category_id'],
                            'filter_sub_category' => true,
                          );
                         
                         $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
                    }
					 if (isset($child['image']) && ($rasdel['image_categ_h']) && ($rasdel['image_categ_w'])){
						$image   = $this->model_tool_image->resize($child['image'], $rasdel['image_categ_w'], $rasdel['image_categ_h']); 
						if ($rasdel['image_categ_h'] > 20) { $image_categ_h = ceil($rasdel['image_categ_h']/2) + 26;} else { $image_categ_h = false;}
                } else {
						$image = false;  
						$image_categ_h = false;    
                }

				 //3 level
 
								$level3 = false;
					//*** Получаем список подкатегорий для каждой категории третьего уровня
								$subchildren = $this->model_catalog_category->getCategories($child['category_id']);
 
								    //*** Обнуляем массив для каждого набора подкатегорий третьего уровня
									$subchildren_data = array(); 
 
										//*** Получаем список подкгатегорий для каждой категории третьего уровня
										foreach ($subchildren as $subchild) {
												//Будем вычислять кол-во товаров в категориях только если это кол-во надо показывать
												if ($this->config->get('config_product_count')) {
													$filter_data = array(
														'filter_category_id'  => $subchild['category_id'],
														'filter_sub_category' => true
													);
 
													$product_subtotal = $this->model_catalog_product->getTotalProducts($filter_data);
												}
												
												//*** Получаем список подкагатегорий для каждой категории третьего уровня формируем массив со списками подкатегорий третьего уровня
												$subchildren_data[] = array(
													'category_id' => $subchild['category_id'],
													'name'  => $subchild['name'] . ($this->config->get('config_product_count') ? ' (' . $product_subtotal . ')' : ''),
													'href'  => $this->url->link('product/category', 'path=' . $child['category_id'] . '_' . $child['category_id']. '_' . $subchild['category_id'])	
 
												);						
											}					
			;
           
                    $categ_data[] = array(
                      'category_id' => $child['category_id'],
                      'name'        => $child['name']. ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
                      'href'        => $this->url->link('product/category', 'path=' . $rasdel['filter_type_category'] . '_' . $child['category_id']),
                      //*** Добавляем к каждой категории первого уровня массив с подкатегориями третьего уровня
					  'children' => $subchildren_data,
					  'img'       => $image,
					   'categ_sub_cat'   => isset($rasdel['categ_sub_cat']) ? $rasdel['categ_sub_cat'] : 0,
                    );
              }  
              } else {
				  $link = '';
				  $categ_data = false;  

			  }
				  
			  }

               

            if ($rasdel['filter_type'] != "box_custom")   { $htmlbox = false; }
            if ($rasdel['filter_type'] != "category")     { $categ_data = false; $image_categ_h = false;  }
            if ($rasdel['filter_type'] != "choisecategory")     { $choisecategory = false; $column_mainlimit = false; }
      

            if ( ($rasdel['title'][$lang_id] =='') || ($rasdel['title'][$lang_id] =='Enter the name')){
              
                if ($rasdel['filter_type'] == "special") {$title = 'Special';} 
                if ($rasdel['filter_type'] == "link_custom") {$title = 'Link';}  
                if ($rasdel['filter_type'] == "box_custom") {$title = 'Custom block';} 
			    if ($rasdel['filter_type'] == "choisecategory") {$title = 'Categories';}
                
                if ($rasdel['filter_type'] == "category") {
					if (isset($rasdel['filter_type_category'])) {
                    $category_name = $this->model_catalog_category->getCategory($rasdel['filter_type_category']);}
                    if (isset($category_name['name'])) {
                        $title = $category_name['name'];
                    } else {
                         $title = false;
                    }
                }
                
 
                  

              } else {
                $title = htmlspecialchars_decode($rasdel['title'][$lang_id], ENT_QUOTES );	
              }

              if (!isset($column)) { $column = false; }  
              
              if (($rasdel['image']) && ($rasdel['image']) != 'no_image.png')  {
                $imageicon=  $this->model_tool_image->resize($rasdel['image'],$barbara_catalog_width, $barbara_catalog_height);
                } else { 
                $imageicon = false; } 
				
				if (!isset($categ_sub)) { $categ_sub = false; }
          
            $data['rasdels'][] = array(
                'title'           => $title,
                'link'            => $link,
                'categ'           => $categ_data,
			    'categ_sub'       => $categ_sub,
				'column'          => $column,
                'htmlbox'         => $htmlbox,
				'choisecategory'  => $choisecategory,
			    'column_mainlimit' => $column_mainlimit,
                'image'           => $imageicon,
				'image_categ_h'   => isset($image_categ_h) ? $image_categ_h : false
              );
			  


			  }
		
				
      }	
			}
	
     	 //  print_r('<pre>');
         // print_r($data['rasdels']);
        //  print_r('</pre>');  	
	
	/////////////////////////		
			
			

		$data['og_url'] = (isset($this->request->server['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER) . substr($this->request->server['REQUEST_URI'], 1, (strlen($this->request->server['REQUEST_URI'])-1));
		 if (version_compare(VERSION, '2.2.0.0', '<')) {
              return $this->load->view('barbaratheme/template/common/barbaratheme.tpl', $data);
          } else {
              return $this->load->view('common/barbaratheme', $data);
          }
	}
	
	 protected function recover($items, $store_id = 0){
      $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` IN ('" . implode("', '", $items) . "')");
      return $this->db->query("DELETE FROM `" . DB_PREFIX . "module` WHERE `code` IN ('" . implode("', '", $items) . "')");
    }


    function compareBySortOrder($a, $b) {
        if ($a['sort_order'] == $b['sort_order']) {
          return 0;
        }
        return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
    }


}
?>