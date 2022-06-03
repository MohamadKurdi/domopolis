<?php
class ControllerModuleSortmanager extends Controller {
    private $error = array(); 
	
	public function index() {   
		$this->load->language('module/sortmanager');
        
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('sortmanager', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['text_category'] = $this->language->get('text_category');
        $this->data['text_search'] = $this->language->get('text_search');
        $this->data['text_special'] = $this->language->get('text_special');
        $this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $this->data['entry_default'] = $this->language->get('entry_default');
        $this->data['entry_name_asc'] = $this->language->get('entry_name_asc');
        $this->data['entry_name_desc'] = $this->language->get('entry_name_desc');
        $this->data['entry_price_asc'] = $this->language->get('entry_price_asc');
        $this->data['entry_price_desc'] = $this->language->get('entry_price_desc');
        $this->data['entry_rating_asc'] = $this->language->get('entry_rating_asc');
        $this->data['entry_rating_desc'] = $this->language->get('entry_rating_desc');
        $this->data['entry_model_asc'] = $this->language->get('entry_model_asc');
        $this->data['entry_model_desc'] = $this->language->get('entry_model_desc');
        $this->data['entry_viewed_asc'] = $this->language->get('entry_viewed_asc');
        $this->data['entry_viewed_desc'] = $this->language->get('entry_viewed_desc');
        $this->data['entry_quantity_asc'] = $this->language->get('entry_quantity_asc');
        $this->data['entry_quantity_desc'] = $this->language->get('entry_quantity_desc');
        $this->data['entry_manufacturer_asc'] = $this->language->get('entry_manufacturer_asc');
        $this->data['entry_manufacturer_desc'] = $this->language->get('entry_manufacturer_desc');
        $this->data['entry_date_added_asc'] = $this->language->get('entry_date_added_asc');
        $this->data['entry_date_added_desc'] = $this->language->get('entry_date_added_desc');
		
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
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/sortmanager', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/sortmanager', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['modules'] = array();
		
		if (isset($this->request->post['sortmanager_module'])) {
			$this->data['modules'] = $this->request->post['sortmanager_module'];
		} elseif ($this->config->get('sortmanager_module')) { 
			$this->data['modules'] = $this->config->get('sortmanager_module');
		}
		
        // Категории
        
        if (isset($this->request->post['config_default'])) {
        	$this->data['config_default'] = $this->request->post['config_default'];
		} else {
			$this->data['config_default'] = $this->config->get('config_default');			
		}
        
        if (isset($this->request->post['config_default_text'])) {
            $this->data['config_default_text'] = $this->request->post['config_default_text'];
		} else {
			$this->data['config_default_text'] = $this->config->get('config_default_text');			
		}
        
        if (isset($this->request->post['config_name_asc'])) {
    		$this->data['config_name_asc'] = $this->request->post['config_name_asc'];
		} else {
			$this->data['config_name_asc'] = $this->config->get('config_name_asc');			
		}
        
        if (isset($this->request->post['config_name_asc_text'])) {
            $this->data['config_name_asc_text'] = $this->request->post['config_name_asc_text'];
    	} else {
			$this->data['config_name_asc_text'] = $this->config->get('config_name_asc_text');			
		}
        
        if (isset($this->request->post['config_name_desc'])) {
        	$this->data['config_name_desc'] = $this->request->post['config_name_desc'];
		} else {
			$this->data['config_name_desc'] = $this->config->get('config_name_desc');			
		}
        
        if (isset($this->request->post['config_name_desc_text'])) {
            $this->data['config_name_desc_text'] = $this->request->post['config_name_desc_text'];
        } else {
			$this->data['config_name_desc_text'] = $this->config->get('config_name_desc_text');			
		}
        
        if (isset($this->request->post['config_price_asc'])) {
            $this->data['config_price_asc'] = $this->request->post['config_price_asc'];
		} else {
			$this->data['config_price_asc'] = $this->config->get('config_price_asc');			
		}
        
        if (isset($this->request->post['config_price_asc_text'])) {
            $this->data['config_price_asc_text'] = $this->request->post['config_price_asc_text'];
        } else {
			$this->data['config_price_asc_text'] = $this->config->get('config_price_asc_text');			
		}
        
        if (isset($this->request->post['config_price_desc'])) {
            $this->data['config_price_desc'] = $this->request->post['config_price_desc'];
		} else {
			$this->data['config_price_desc'] = $this->config->get('config_price_desc');			
		}
        
        if (isset($this->request->post['config_price_desc_text'])) {
            $this->data['config_price_desc_text'] = $this->request->post['config_price_desc_text'];
        } else {
    		$this->data['config_price_desc_text'] = $this->config->get('config_price_desc_text');			
		}
        
        if (isset($this->request->post['config_rating_asc'])) {
            $this->data['config_rating_asc'] = $this->request->post['config_rating_asc'];
    	} else {
			$this->data['config_rating_asc'] = $this->config->get('config_rating_asc');			
		}
        
        if (isset($this->request->post['config_rating_asc_text'])) {
            $this->data['config_rating_asc_text'] = $this->request->post['config_rating_asc_text'];
        } else {
        	$this->data['config_rating_asc_text'] = $this->config->get('config_rating_asc_text');			
		}
        
        if (isset($this->request->post['config_rating_desc'])) {
            $this->data['config_rating_desc'] = $this->request->post['config_rating_desc'];
		} else {
			$this->data['config_rating_desc'] = $this->config->get('config_rating_desc');			
		}
        
        if (isset($this->request->post['config_rating_desc_text'])) {
            $this->data['config_rating_desc_text'] = $this->request->post['config_rating_desc_text'];
        } else {
            $this->data['config_rating_desc_text'] = $this->config->get('config_rating_desc_text');			
		}
        
        if (isset($this->request->post['config_model_asc'])) {
            $this->data['config_model_asc'] = $this->request->post['config_model_asc'];
    	} else {
			$this->data['config_model_asc'] = $this->config->get('config_model_asc');			
		}
        
        if (isset($this->request->post['config_model_asc_text'])) {
            $this->data['config_model_asc_text'] = $this->request->post['config_model_asc_text'];
        } else {
            $this->data['config_model_asc_text'] = $this->config->get('config_model_asc_text');    		
		}
        
        if (isset($this->request->post['config_model_desc'])) {
            $this->data['config_model_desc'] = $this->request->post['config_model_desc'];
        } else {
			$this->data['config_model_desc'] = $this->config->get('config_model_desc');			
		}
        
        if (isset($this->request->post['config_model_desc_text'])) {
            $this->data['config_model_desc_text'] = $this->request->post['config_model_desc_text'];
        } else {
            $this->data['config_model_desc_text'] = $this->config->get('config_model_desc_text');        	
		}
        
        if (isset($this->request->post['config_viewed_asc'])) {
            $this->data['config_viewed_asc'] = $this->request->post['config_viewed_asc'];
        } else {
			$this->data['config_viewed_asc'] = $this->config->get('config_viewed_asc');			
		}
        
        if (isset($this->request->post['config_viewed_asc_text'])) {
            $this->data['config_viewed_asc_text'] = $this->request->post['config_viewed_asc_text'];
        } else {
            $this->data['config_viewed_asc_text'] = $this->config->get('config_viewed_asc_text');        	
		}
        
        if (isset($this->request->post['config_viewed_desc'])) {
            $this->data['config_viewed_desc'] = $this->request->post['config_viewed_desc'];
        } else {
			$this->data['config_viewed_desc'] = $this->config->get('config_viewed_desc');			
		}
        
        if (isset($this->request->post['config_viewed_desc_text'])) {
            $this->data['config_viewed_desc_text'] = $this->request->post['config_viewed_desc_text'];
        } else {
            $this->data['config_viewed_desc_text'] = $this->config->get('config_viewed_desc_text');            
		}
        
        if (isset($this->request->post['config_quantity_asc'])) {
            $this->data['config_quantity_asc'] = $this->request->post['config_quantity_asc'];
        } else {
    		$this->data['config_quantity_asc'] = $this->config->get('config_quantity_asc');			
		}
        
        if (isset($this->request->post['config_quantity_asc_text'])) {
            $this->data['config_quantity_asc_text'] = $this->request->post['config_quantity_asc_text'];
        } else {
            $this->data['config_quantity_asc_text'] = $this->config->get('config_quantity_asc_text');            
		}
        
        if (isset($this->request->post['config_quantity_desc'])) {
            $this->data['config_quantity_desc'] = $this->request->post['config_quantity_desc'];
        } else {
			$this->data['config_quantity_desc'] = $this->config->get('config_quantity_desc');			
		}
        
        if (isset($this->request->post['config_quantity_desc_text'])) {
            $this->data['config_quantity_desc_text'] = $this->request->post['config_quantity_desc_text'];
        } else {
            $this->data['config_quantity_desc_text'] = $this->config->get('config_quantity_desc_text');            
    	}
        
        if (isset($this->request->post['config_manufacturer_asc'])) {
            $this->data['config_manufacturer_asc'] = $this->request->post['config_manufacturer_asc'];
        } else {
        	$this->data['config_manufacturer_asc'] = $this->config->get('config_manufacturer_asc');			
		}
        
        if (isset($this->request->post['config_manufacturer_asc_text'])) {
            $this->data['config_manufacturer_asc_text'] = $this->request->post['config_manufacturer_asc_text'];
        } else {
            $this->data['config_manufacturer_asc_text'] = $this->config->get('config_manufacturer_asc_text');            
    	}
        
        if (isset($this->request->post['config_manufacturer_desc'])) {
            $this->data['config_manufacturer_desc'] = $this->request->post['config_manufacturer_desc'];
        } else {
			$this->data['config_manufacturer_desc'] = $this->config->get('config_manufacturer_desc');			
		}
        
        if (isset($this->request->post['config_manufacturer_desc_text'])) {
            $this->data['config_manufacturer_desc_text'] = $this->request->post['config_manufacturer_desc_text'];
        } else {
            $this->data['config_manufacturer_desc_text'] = $this->config->get('config_manufacturer_desc_text');            
        }
        
        if (isset($this->request->post['config_date_added_asc'])) {
            $this->data['config_date_added_asc'] = $this->request->post['config_date_added_asc'];
        } else {
            $this->data['config_date_added_asc'] = $this->config->get('config_date_added_asc');            
		}
        
        if (isset($this->request->post['config_date_added_asc_text'])) {
            $this->data['config_date_added_asc_text'] = $this->request->post['config_date_added_asc_text'];
        } else {
            $this->data['config_date_added_asc_text'] = $this->config->get('config_date_added_asc_text');            
        }
        
        if (isset($this->request->post['config_date_added_desc'])) {
            $this->data['config_date_added_desc'] = $this->request->post['config_date_added_desc'];
        } else {
			$this->data['config_date_added_desc'] = $this->config->get('config_date_added_desc');			
		}
        
        if (isset($this->request->post['config_date_added_desc_text'])) {
            $this->data['config_date_added_desc_text'] = $this->request->post['config_date_added_desc_text'];
        } else {
            $this->data['config_date_added_desc_text'] = $this->config->get('config_date_added_desc_text');            
        }
        
        // Производители
        
        if (isset($this->request->post['config_default_man'])) {
            $this->data['config_default_man'] = $this->request->post['config_default_man'];
    	} else {
			$this->data['config_default_man'] = $this->config->get('config_default_man');			
		}
        
        if (isset($this->request->post['config_default_text_man'])) {
            $this->data['config_default_text_man'] = $this->request->post['config_default_text_man'];
    	} else {
			$this->data['config_default_text_man'] = $this->config->get('config_default_text_man');			
		}
        
        if (isset($this->request->post['config_name_asc_man'])) {
    		$this->data['config_name_asc_man'] = $this->request->post['config_name_asc_man'];
		} else {
			$this->data['config_name_asc_man'] = $this->config->get('config_name_asc_man');			
		}
        
        if (isset($this->request->post['config_name_asc_text_man'])) {
            $this->data['config_name_asc_text_man'] = $this->request->post['config_name_asc_text_man'];
        } else {
			$this->data['config_name_asc_text_man'] = $this->config->get('config_name_asc_text_man');			
		}
        
        if (isset($this->request->post['config_name_desc_man'])) {
        	$this->data['config_name_desc_man'] = $this->request->post['config_name_desc_man'];
		} else {
			$this->data['config_name_desc_man'] = $this->config->get('config_name_desc_man');			
		}
        
        if (isset($this->request->post['config_name_desc_text_man'])) {
            $this->data['config_name_desc_text_man'] = $this->request->post['config_name_desc_text_man'];
        } else {
    		$this->data['config_name_desc_text_man'] = $this->config->get('config_name_desc_text_man');			
		}
        
        if (isset($this->request->post['config_price_asc_man'])) {
            $this->data['config_price_asc_man'] = $this->request->post['config_price_asc_man'];
		} else {
			$this->data['config_price_asc_man'] = $this->config->get('config_price_asc_man');			
		}
        
        if (isset($this->request->post['config_price_asc_text_man'])) {
            $this->data['config_price_asc_text_man'] = $this->request->post['config_price_asc_text_man'];
        } else {
    		$this->data['config_price_asc_text_man'] = $this->config->get('config_price_asc_text_man');			
		}
        
        if (isset($this->request->post['config_price_desc_man'])) {
            $this->data['config_price_desc_man'] = $this->request->post['config_price_desc_man'];
		} else {
			$this->data['config_price_desc_man'] = $this->config->get('config_price_desc_man');			
		}
        
        if (isset($this->request->post['config_price_desc_text_man'])) {
            $this->data['config_price_desc_text_man'] = $this->request->post['config_price_desc_text_man'];
        } else {
        	$this->data['config_price_desc_text_man'] = $this->config->get('config_price_desc_text_man');			
		}
        
        if (isset($this->request->post['config_rating_asc_man'])) {
            $this->data['config_rating_asc_man'] = $this->request->post['config_rating_asc_man'];
    	} else {
			$this->data['config_rating_asc_man'] = $this->config->get('config_rating_asc_man');			
		}
        
        if (isset($this->request->post['config_rating_asc_text_man'])) {
            $this->data['config_rating_asc_text_man'] = $this->request->post['config_rating_asc_text_man'];
        } else {
            $this->data['config_rating_asc_text_man'] = $this->config->get('config_rating_asc_text_man');			
		}
        
        if (isset($this->request->post['config_rating_desc_man'])) {
            $this->data['config_rating_desc_man'] = $this->request->post['config_rating_desc_man'];
		} else {
			$this->data['config_rating_desc_man'] = $this->config->get('config_rating_desc_man');			
		}
        
        if (isset($this->request->post['config_rating_desc_text_man'])) {
            $this->data['config_rating_desc_text_man'] = $this->request->post['config_rating_desc_text_man'];
        } else {
            $this->data['config_rating_desc_text_man'] = $this->config->get('config_rating_desc_text_man');    		
		}
        
        if (isset($this->request->post['config_model_asc_man'])) {
            $this->data['config_model_asc_man'] = $this->request->post['config_model_asc_man'];
    	} else {
			$this->data['config_model_asc_man'] = $this->config->get('config_model_asc_man');			
		}
        
        if (isset($this->request->post['config_model_asc_text_man'])) {
            $this->data['config_model_asc_text_man'] = $this->request->post['config_model_asc_text_man'];
        } else {
            $this->data['config_model_asc_text_man'] = $this->config->get('config_model_asc_text_man');        	
		}
        
        if (isset($this->request->post['config_model_desc_man'])) {
            $this->data['config_model_desc_man'] = $this->request->post['config_model_desc_man'];
        } else {
			$this->data['config_model_desc_man'] = $this->config->get('config_model_desc_man');			
		}
        
        if (isset($this->request->post['config_model_desc_text_man'])) {
            $this->data['config_model_desc_text_man'] = $this->request->post['config_model_desc_text_man'];
        } else {
            $this->data['config_model_desc_text_man'] = $this->config->get('config_model_desc_text_man');            
		}
        
        if (isset($this->request->post['config_viewed_asc_man'])) {
            $this->data['config_viewed_asc_man'] = $this->request->post['config_viewed_asc_man'];
        } else {
			$this->data['config_viewed_asc_man'] = $this->config->get('config_viewed_asc_man');			
		}
        
        if (isset($this->request->post['config_viewed_asc_text_man'])) {
            $this->data['config_viewed_asc_text_man'] = $this->request->post['config_viewed_asc_text_man'];
        } else {
            $this->data['config_viewed_asc_text_man'] = $this->config->get('config_viewed_asc_text_man');            
		}
        
        if (isset($this->request->post['config_viewed_desc_man'])) {
            $this->data['config_viewed_desc_man'] = $this->request->post['config_viewed_desc_man'];
        } else {
			$this->data['config_viewed_desc_man'] = $this->config->get('config_viewed_desc_man');			
		}
        
        if (isset($this->request->post['config_viewed_desc_text_man'])) {
            $this->data['config_viewed_desc_text_man'] = $this->request->post['config_viewed_desc_text_man'];
        } else {
            $this->data['config_viewed_desc_text_man'] = $this->config->get('config_viewed_desc_text_man');            
    	}
        
        if (isset($this->request->post['config_quantity_asc_man'])) {
            $this->data['config_quantity_asc_man'] = $this->request->post['config_quantity_asc_man'];
        } else {
    		$this->data['config_quantity_asc_man'] = $this->config->get('config_quantity_asc_man');			
		}
        
        if (isset($this->request->post['config_quantity_asc_text_man'])) {
            $this->data['config_quantity_asc_text_man'] = $this->request->post['config_quantity_asc_text_man'];
        } else {
            $this->data['config_quantity_asc_text_man'] = $this->config->get('config_quantity_asc_text_man');            
    	}
        
        if (isset($this->request->post['config_quantity_desc_man'])) {
            $this->data['config_quantity_desc_man'] = $this->request->post['config_quantity_desc_man'];
        } else {
			$this->data['config_quantity_desc_man'] = $this->config->get('config_quantity_desc_man');			
		}
        
        if (isset($this->request->post['config_quantity_desc_text_man'])) {
            $this->data['config_quantity_desc_text_man'] = $this->request->post['config_quantity_desc_text_man'];
        } else {
            $this->data['config_quantity_desc_text_man'] = $this->config->get('config_quantity_desc_text_man');            
        }
        
        if (isset($this->request->post['config_date_added_asc_man'])) {
            $this->data['config_date_added_asc_man'] = $this->request->post['config_date_added_asc_man'];
        } else {
            $this->data['config_date_added_asc_man'] = $this->config->get('config_date_added_asc_man');            
		}
        
        if (isset($this->request->post['config_date_added_asc_text_man'])) {
            $this->data['config_date_added_asc_text_man'] = $this->request->post['config_date_added_asc_text_man'];
        } else {
            $this->data['config_date_added_asc_text_man'] = $this->config->get('config_date_added_asc_text_man');            
        }
        
        if (isset($this->request->post['config_date_added_desc_man'])) {
            $this->data['config_date_added_desc_man'] = $this->request->post['config_date_added_desc_man'];
        } else {
			$this->data['config_date_added_desc_man'] = $this->config->get('config_date_added_desc_man');			
		}
        
        if (isset($this->request->post['config_date_added_desc_text_man'])) {
            $this->data['config_date_added_desc_text_man'] = $this->request->post['config_date_added_desc_text_man'];
        } else {
            $this->data['config_date_added_desc_text_man'] = $this->config->get('config_date_added_desc_text_man');            
        }
        
        // Акции
        
        if (isset($this->request->post['config_default_sp'])) {
            $this->data['config_default_sp'] = $this->request->post['config_default_sp'];
    	} else {
			$this->data['config_default_sp'] = $this->config->get('config_default_sp');			
		}
        
        if (isset($this->request->post['config_default_text_sp'])) {
            $this->data['config_default_text_sp'] = $this->request->post['config_default_text_sp'];
    	} else {
			$this->data['config_default_text_sp'] = $this->config->get('config_default_text_sp');			
		}
        
        if (isset($this->request->post['config_name_asc_sp'])) {
    		$this->data['config_name_asc_sp'] = $this->request->post['config_name_asc_sp'];
		} else {
			$this->data['config_name_asc_sp'] = $this->config->get('config_name_asc_sp');			
		}
        
        if (isset($this->request->post['config_name_asc_text_sp'])) {
            $this->data['config_name_asc_text_sp'] = $this->request->post['config_name_asc_text_sp'];
        } else {
			$this->data['config_name_asc_text_sp'] = $this->config->get('config_name_asc_text_sp');			
		}
        
        if (isset($this->request->post['config_name_desc_sp'])) {
        	$this->data['config_name_desc_sp'] = $this->request->post['config_name_desc_sp'];
		} else {
			$this->data['config_name_desc_sp'] = $this->config->get('config_name_desc_sp');			
		}
        
        if (isset($this->request->post['config_name_desc_text_sp'])) {
            $this->data['config_name_desc_text_sp'] = $this->request->post['config_name_desc_text_sp'];
        } else {
    		$this->data['config_name_desc_text_sp'] = $this->config->get('config_name_desc_text_sp');			
		}
        
        if (isset($this->request->post['config_price_asc_sp'])) {
            $this->data['config_price_asc_sp'] = $this->request->post['config_price_asc_sp'];
		} else {
			$this->data['config_price_asc_sp'] = $this->config->get('config_price_asc_sp');			
		}
        
        if (isset($this->request->post['config_price_asc_text_sp'])) {
            $this->data['config_price_asc_text_sp'] = $this->request->post['config_price_asc_text_sp'];
        } else {
    		$this->data['config_price_asc_text_sp'] = $this->config->get('config_price_asc_text_sp');			
		}
        
        if (isset($this->request->post['config_price_desc_sp'])) {
            $this->data['config_price_desc_sp'] = $this->request->post['config_price_desc_sp'];
		} else {
			$this->data['config_price_desc_sp'] = $this->config->get('config_price_desc_sp');			
		}
        
        if (isset($this->request->post['config_price_desc_text_sp'])) {
            $this->data['config_price_desc_text_sp'] = $this->request->post['config_price_desc_text_sp'];
        } else {
        	$this->data['config_price_desc_text_sp'] = $this->config->get('config_price_desc_text_sp');			
		}
        
        if (isset($this->request->post['config_rating_asc_sp'])) {
            $this->data['config_rating_asc_sp'] = $this->request->post['config_rating_asc_sp'];
    	} else {
			$this->data['config_rating_asc_sp'] = $this->config->get('config_rating_asc_sp');			
		}
        
        if (isset($this->request->post['config_rating_asc_text_sp'])) {
            $this->data['config_rating_asc_text_sp'] = $this->request->post['config_rating_asc_text_sp'];
        } else {
            $this->data['config_rating_asc_text_sp'] = $this->config->get('config_rating_asc_text_sp');			
		}
        
        if (isset($this->request->post['config_rating_desc_sp'])) {
            $this->data['config_rating_desc_sp'] = $this->request->post['config_rating_desc_sp'];
		} else {
			$this->data['config_rating_desc_sp'] = $this->config->get('config_rating_desc_sp');			
		}
        
        if (isset($this->request->post['config_rating_desc_text_sp'])) {
            $this->data['config_rating_desc_text_sp'] = $this->request->post['config_rating_desc_text_sp'];
        } else {
            $this->data['config_rating_desc_text_sp'] = $this->config->get('config_rating_desc_text_sp');    		
		}
        
        if (isset($this->request->post['config_model_asc_sp'])) {
            $this->data['config_model_asc_sp'] = $this->request->post['config_model_asc_sp'];
    	} else {
			$this->data['config_model_asc_sp'] = $this->config->get('config_model_asc_sp');			
		}
        
        if (isset($this->request->post['config_model_asc_text_sp'])) {
            $this->data['config_model_asc_text_sp'] = $this->request->post['config_model_asc_text_sp'];
        } else {
            $this->data['config_model_asc_text_sp'] = $this->config->get('config_model_asc_text_sp');        	
		}
        
        if (isset($this->request->post['config_model_desc_sp'])) {
            $this->data['config_model_desc_sp'] = $this->request->post['config_model_desc_sp'];
        } else {
			$this->data['config_model_desc_sp'] = $this->config->get('config_model_desc_sp');			
		}
        
        if (isset($this->request->post['config_model_desc_text_sp'])) {
            $this->data['config_model_desc_text_sp'] = $this->request->post['config_model_desc_text_sp'];
        } else {
            $this->data['config_model_desc_text_sp'] = $this->config->get('config_model_desc_text_sp');            
		}
        
        if (isset($this->request->post['config_viewed_asc_sp'])) {
            $this->data['config_viewed_asc_sp'] = $this->request->post['config_viewed_asc_sp'];
        } else {
			$this->data['config_viewed_asc_sp'] = $this->config->get('config_viewed_asc_sp');			
		}
        
        if (isset($this->request->post['config_viewed_asc_text_sp'])) {
            $this->data['config_viewed_asc_text_sp'] = $this->request->post['config_viewed_asc_text_sp'];
        } else {
            $this->data['config_viewed_asc_text_sp'] = $this->config->get('config_viewed_asc_text_sp');            
		}
        
        if (isset($this->request->post['config_viewed_desc_sp'])) {
            $this->data['config_viewed_desc_sp'] = $this->request->post['config_viewed_desc_sp'];
        } else {
			$this->data['config_viewed_desc_sp'] = $this->config->get('config_viewed_desc_sp');			
		}
        
        if (isset($this->request->post['config_viewed_desc_text_sp'])) {
            $this->data['config_viewed_desc_text_sp'] = $this->request->post['config_viewed_desc_text_sp'];
        } else {
            $this->data['config_viewed_desc_text_sp'] = $this->config->get('config_viewed_desc_text_sp');            
    	}
        
        if (isset($this->request->post['config_quantity_asc_sp'])) {
            $this->data['config_quantity_asc_sp'] = $this->request->post['config_quantity_asc_sp'];
        } else {
    		$this->data['config_quantity_asc_sp'] = $this->config->get('config_quantity_asc_sp');			
		}
        
        if (isset($this->request->post['config_quantity_asc_text_sp'])) {
            $this->data['config_quantity_asc_text_sp'] = $this->request->post['config_quantity_asc_text_sp'];
        } else {
            $this->data['config_quantity_asc_text_sp'] = $this->config->get('config_quantity_asc_text_sp');            
    	}
        
        if (isset($this->request->post['config_quantity_desc_sp'])) {
            $this->data['config_quantity_desc_sp'] = $this->request->post['config_quantity_desc_sp'];
        } else {
			$this->data['config_quantity_desc_sp'] = $this->config->get('config_quantity_desc_sp');			
		}
        
        if (isset($this->request->post['config_quantity_desc_text_sp'])) {
            $this->data['config_quantity_desc_text_sp'] = $this->request->post['config_quantity_desc_text_sp'];
        } else {
            $this->data['config_quantity_desc_text_sp'] = $this->config->get('config_quantity_desc_text_sp');            
        }
        
        if (isset($this->request->post['config_manufacturer_asc_sp'])) {
            $this->data['config_manufacturer_asc_sp'] = $this->request->post['config_manufacturer_asc_sp'];
        } else {
        	$this->data['config_manufacturer_asc_sp'] = $this->config->get('config_manufacturer_asc_sp');			
		}
        
        if (isset($this->request->post['config_manufacturer_asc_text_sp'])) {
            $this->data['config_manufacturer_asc_text_sp'] = $this->request->post['config_manufacturer_asc_text_sp'];
        } else {
            $this->data['config_manufacturer_asc_text_sp'] = $this->config->get('config_manufacturer_asc_text_sp');            
        }
        
        if (isset($this->request->post['config_manufacturer_desc_sp'])) {
            $this->data['config_manufacturer_desc_sp'] = $this->request->post['config_manufacturer_desc_sp'];
        } else {
			$this->data['config_manufacturer_desc_sp'] = $this->config->get('config_manufacturer_desc_sp');			
		}
        
        if (isset($this->request->post['config_manufacturer_desc_text_sp'])) {
            $this->data['config_manufacturer_desc_text_sp'] = $this->request->post['config_manufacturer_desc_text_sp'];
        } else {
            $this->data['config_manufacturer_desc_text_sp'] = $this->config->get('config_manufacturer_desc_text_sp');            
        }
        
        if (isset($this->request->post['config_date_added_asc_sp'])) {
            $this->data['config_date_added_asc_sp'] = $this->request->post['config_date_added_asc_sp'];
        } else {
            $this->data['config_date_added_asc_sp'] = $this->config->get('config_date_added_asc_sp');            
		}
        
        if (isset($this->request->post['config_date_added_asc_text_sp'])) {
            $this->data['config_date_added_asc_text_sp'] = $this->request->post['config_date_added_asc_text_sp'];
        } else {
            $this->data['config_date_added_asc_text_sp'] = $this->config->get('config_date_added_asc_text_sp');            
        }
        
        if (isset($this->request->post['config_date_added_desc_sp'])) {
            $this->data['config_date_added_desc_sp'] = $this->request->post['config_date_added_desc_sp'];
        } else {
			$this->data['config_date_added_desc_sp'] = $this->config->get('config_date_added_desc_sp');			
		}
        
        if (isset($this->request->post['config_date_added_desc_text_sp'])) {
            $this->data['config_date_added_desc_text_sp'] = $this->request->post['config_date_added_desc_text_sp'];
        } else {
            $this->data['config_date_added_desc_text_sp'] = $this->config->get('config_date_added_desc_text_sp');            
        }
        
        // Поиск
        
        if (isset($this->request->post['config_default_sr'])) {
            $this->data['config_default_sr'] = $this->request->post['config_default_sr'];
    	} else {
			$this->data['config_default_sr'] = $this->config->get('config_default_sr');			
		}
        
        if (isset($this->request->post['config_default_text_sr'])) {
            $this->data['config_default_text_sr'] = $this->request->post['config_default_text_sr'];
    	} else {
			$this->data['config_default_text_sr'] = $this->config->get('config_default_text_sr');			
		}
        
        if (isset($this->request->post['config_name_asc_sr'])) {
    		$this->data['config_name_asc_sr'] = $this->request->post['config_name_asc_sr'];
		} else {
			$this->data['config_name_asc_sr'] = $this->config->get('config_name_asc_sr');			
		}
        
        if (isset($this->request->post['config_name_asc_text_sr'])) {
            $this->data['config_name_asc_text_sr'] = $this->request->post['config_name_asc_text_sr'];
        } else {
			$this->data['config_name_asc_text_sr'] = $this->config->get('config_name_asc_text_sr');			
		}
        
        if (isset($this->request->post['config_name_desc_sr'])) {
        	$this->data['config_name_desc_sr'] = $this->request->post['config_name_desc_sr'];
		} else {
			$this->data['config_name_desc_sr'] = $this->config->get('config_name_desc_sr');			
		}
        
        if (isset($this->request->post['config_name_desc_text_sr'])) {
            $this->data['config_name_desc_text_sr'] = $this->request->post['config_name_desc_text_sr'];
        } else {
    		$this->data['config_name_desc_text_sr'] = $this->config->get('config_name_desc_text_sr');			
		}
        
        if (isset($this->request->post['config_price_asc_sr'])) {
            $this->data['config_price_asc_sr'] = $this->request->post['config_price_asc_sr'];
		} else {
			$this->data['config_price_asc_sr'] = $this->config->get('config_price_asc_sr');			
		}
        
        if (isset($this->request->post['config_price_asc_text_sr'])) {
            $this->data['config_price_asc_text_sr'] = $this->request->post['config_price_asc_text_sr'];
        } else {
    		$this->data['config_price_asc_text_sr'] = $this->config->get('config_price_asc_text_sr');			
		}
        
        if (isset($this->request->post['config_price_desc_sr'])) {
            $this->data['config_price_desc_sr'] = $this->request->post['config_price_desc_sr'];
		} else {
			$this->data['config_price_desc_sr'] = $this->config->get('config_price_desc_sr');			
		}
        
        if (isset($this->request->post['config_price_desc_text_sr'])) {
            $this->data['config_price_desc_text_sr'] = $this->request->post['config_price_desc_text_sr'];
        } else {
        	$this->data['config_price_desc_text_sr'] = $this->config->get('config_price_desc_text_sr');			
		}
        
        if (isset($this->request->post['config_rating_asc_sr'])) {
            $this->data['config_rating_asc_sr'] = $this->request->post['config_rating_asc_sr'];
    	} else {
			$this->data['config_rating_asc_sr'] = $this->config->get('config_rating_asc_sr');			
		}
        
        if (isset($this->request->post['config_rating_asc_text_sr'])) {
            $this->data['config_rating_asc_text_sr'] = $this->request->post['config_rating_asc_text_sr'];
        } else {
            $this->data['config_rating_asc_text_sr'] = $this->config->get('config_rating_asc_text_sr');			
		}
        
        if (isset($this->request->post['config_rating_desc_sr'])) {
            $this->data['config_rating_desc_sr'] = $this->request->post['config_rating_desc_sr'];
		} else {
			$this->data['config_rating_desc_sr'] = $this->config->get('config_rating_desc_sr');			
		}
        
        if (isset($this->request->post['config_rating_desc_text_sr'])) {
            $this->data['config_rating_desc_text_sr'] = $this->request->post['config_rating_desc_text_sr'];
        } else {
            $this->data['config_rating_desc_text_sr'] = $this->config->get('config_rating_desc_text_sr');    		
		}
        
        if (isset($this->request->post['config_model_asc_sr'])) {
            $this->data['config_model_asc_sr'] = $this->request->post['config_model_asc_sr'];
    	} else {
			$this->data['config_model_asc_sr'] = $this->config->get('config_model_asc_sr');			
		}
        
        if (isset($this->request->post['config_model_asc_text_sr'])) {
            $this->data['config_model_asc_text_sr'] = $this->request->post['config_model_asc_text_sr'];
        } else {
            $this->data['config_model_asc_text_sr'] = $this->config->get('config_model_asc_text_sr');        	
		}
        
        if (isset($this->request->post['config_model_desc_sr'])) {
            $this->data['config_model_desc_sr'] = $this->request->post['config_model_desc_sr'];
        } else {
			$this->data['config_model_desc_sr'] = $this->config->get('config_model_desc_sr');			
		}
        
        if (isset($this->request->post['config_model_desc_text_sr'])) {
            $this->data['config_model_desc_text_sr'] = $this->request->post['config_model_desc_text_sr'];
        } else {
            $this->data['config_model_desc_text_sr'] = $this->config->get('config_model_desc_text_sr');            
		}
        
        if (isset($this->request->post['config_viewed_asc_sr'])) {
            $this->data['config_viewed_asc_sr'] = $this->request->post['config_viewed_asc_sr'];
        } else {
			$this->data['config_viewed_asc_sr'] = $this->config->get('config_viewed_asc_sr');			
		}
        
        if (isset($this->request->post['config_viewed_asc_text_sr'])) {
            $this->data['config_viewed_asc_text_sr'] = $this->request->post['config_viewed_asc_text_sr'];
        } else {
            $this->data['config_viewed_asc_text_sr'] = $this->config->get('config_viewed_asc_text_sr');            
		}
        
        if (isset($this->request->post['config_viewed_desc_sr'])) {
            $this->data['config_viewed_desc_sr'] = $this->request->post['config_viewed_desc_sr'];
        } else {
			$this->data['config_viewed_desc_sr'] = $this->config->get('config_viewed_desc_sr');			
		}
        
        if (isset($this->request->post['config_viewed_desc_text_sr'])) {
            $this->data['config_viewed_desc_text_sr'] = $this->request->post['config_viewed_desc_text_sr'];
        } else {
            $this->data['config_viewed_desc_text_sr'] = $this->config->get('config_viewed_desc_text_sr');            
    	}
        
        if (isset($this->request->post['config_quantity_asc_sr'])) {
            $this->data['config_quantity_asc_sr'] = $this->request->post['config_quantity_asc_sr'];
        } else {
    		$this->data['config_quantity_asc_sr'] = $this->config->get('config_quantity_asc_sr');			
		}
        
        if (isset($this->request->post['config_quantity_asc_text_sr'])) {
            $this->data['config_quantity_asc_text_sr'] = $this->request->post['config_quantity_asc_text_sr'];
        } else {
            $this->data['config_quantity_asc_text_sr'] = $this->config->get('config_quantity_asc_text_sr');            
    	}
        
        if (isset($this->request->post['config_quantity_desc_sr'])) {
            $this->data['config_quantity_desc_sr'] = $this->request->post['config_quantity_desc_sr'];
        } else {
			$this->data['config_quantity_desc_sr'] = $this->config->get('config_quantity_desc_sr');			
		}
        
        if (isset($this->request->post['config_quantity_desc_text_sr'])) {
            $this->data['config_quantity_desc_text_sr'] = $this->request->post['config_quantity_desc_text_sr'];
        } else {
            $this->data['config_quantity_desc_text_sr'] = $this->config->get('config_quantity_desc_text_sr');            
        }
        
        if (isset($this->request->post['config_manufacturer_asc_sr'])) {
            $this->data['config_manufacturer_asc_sr'] = $this->request->post['config_manufacturer_asc_sr'];
        } else {
        	$this->data['config_manufacturer_asc_sr'] = $this->config->get('config_manufacturer_asc_sr');			
		}
        
        if (isset($this->request->post['config_manufacturer_asc_text_sr'])) {
            $this->data['config_manufacturer_asc_text_sr'] = $this->request->post['config_manufacturer_asc_text_sr'];
        } else {
            $this->data['config_manufacturer_asc_text_sr'] = $this->config->get('config_manufacturer_asc_text_sr');            
        }
        
        if (isset($this->request->post['config_manufacturer_desc_sr'])) {
            $this->data['config_manufacturer_desc_sr'] = $this->request->post['config_manufacturer_desc_sr'];
        } else {
			$this->data['config_manufacturer_desc_sr'] = $this->config->get('config_manufacturer_desc_sr');			
		}
        
        if (isset($this->request->post['config_manufacturer_desc_text_sr'])) {
            $this->data['config_manufacturer_desc_text_sr'] = $this->request->post['config_manufacturer_desc_text_sr'];
        } else {
            $this->data['config_manufacturer_desc_text_sr'] = $this->config->get('config_manufacturer_desc_text_sr');            
        }
        
        if (isset($this->request->post['config_date_added_asc_sr'])) {
            $this->data['config_date_added_asc_sr'] = $this->request->post['config_date_added_asc_sr'];
        } else {
            $this->data['config_date_added_asc_sr'] = $this->config->get('config_date_added_asc_sr');    		
		}
        
        if (isset($this->request->post['config_date_added_asc_text_sr'])) {
            $this->data['config_date_added_asc_text_sr'] = $this->request->post['config_date_added_asc_text_sr'];
        } else {
            $this->data['config_date_added_asc_text_sr'] = $this->config->get('config_date_added_asc_text_sr');            
        }
        
        if (isset($this->request->post['config_date_added_desc_sr'])) {
            $this->data['config_date_added_desc_sr'] = $this->request->post['config_date_added_desc_sr'];
        } else {
			$this->data['config_date_added_desc_sr'] = $this->config->get('config_date_added_desc_sr');			
		}
        
        if (isset($this->request->post['config_date_added_desc_text_sr'])) {
            $this->data['config_date_added_desc_text_sr'] = $this->request->post['config_date_added_desc_text_sr'];
        } else {
            $this->data['config_date_added_desc_text_sr'] = $this->config->get('config_date_added_desc_text_sr');            
        }
        
		$this->template = 'module/sortmanager.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/sortmanager')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>