<?php 
class ControllerShippingDostavkaPlus extends Controller {
	private $error = array();
    private $type = 'shipping';
   	private $name = 'dostavkaplus';

	public function index() {
        $this->data = array_merge($this->data, $this->load->language($this->type . '/' . $this->name));

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($this->name, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/'.$this->type, 'token=' . $this->session->data['token'], 'SSL'));
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		}
        else {
			$this->data['error_warning'] = '';
		}


        if (isset($this->error['title'])) {
            $this->data['error_title'] = $this->error['title'];
        } else {
            $this->data['error_title'] = array();
        }

        if (isset($this->error['store'])) {
            $this->data['error_store'] = $this->error['store'];
        } else {
            $this->data['error_store'] = array();
        }

        $this->data['text_default'] = preg_replace("|[^abcdefghijklmnopqrstuvwxyzабвгдежзийклмнопрстуфхцчшщъыьэюяё0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZАБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯЁ ]|", "", $this->config->get('config_name'));
		
		$this->load->model('localisation/currency');
		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();


        $this->data['modules'] = array();

        if (isset($this->request->post[$this->name.'_module'])) {
            $this->data['modules'] = $this->request->post[$this->name.'_module'];
        } elseif ($this->config->get($this->name.'_module')) {
            $this->data['modules'] = $this->config->get($this->name.'_module');
        }


        $this->load->model('tool/image');

        foreach ($this->data['modules'] as $key => $module) {
            $this->data['modules'][$key]['thumb'] =  $this->model_tool_image->resize($module['image'], 100, 100);
        }

        $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);


  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/'.$this->type, 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link($this->type.'/'.$this->name, 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link($this->type.'/'.$this->name, 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/'.$this->type, 'token=' . $this->session->data['token'], 'SSL');


        $this->load->model('localisation/language');
        $this->data['languages'] = $this->model_localisation_language->getLanguages();

        $this->load->model('localisation/order_status');
        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        foreach ($this->data['order_statuses'] as $key => $order_status) {
            $this->data['order_statuses'][$key]['name'] = preg_replace("|[^abcdefghijklmnopqrstuvwxyzабвгдежзийклмнопрстуфхцчшщъыьэюяё0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZАБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯЁ ]|", "", $order_status['name']);
        }

        $this->load->model('localisation/geo_zone');
        $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        foreach ($this->data['geo_zones'] as $key => $geo_zone) {
            $this->data['geo_zones'][$key]['name'] = preg_replace("|[^abcdefghijklmnopqrstuvwxyzабвгдежзийклмнопрстуфхцчшщъыьэюяё0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZАБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯЁ ]|", "", $geo_zone['name']);
        }

        $this->load->model('setting/store');
        $this->data['stores'] = $this->model_setting_store->getStores();
        foreach ($this->data['stores'] as $key => $store) {
            $this->data['stores'][$key]['name'] = preg_replace("|[^abcdefghijklmnopqrstuvwxyzабвгдежзийклмнопрстуфхцчшщъыьэюяё0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZАБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯЁ ]|", "", $store['name']);
        }

        $this->data['token'] = $this->session->data['token'];

        $this->data['name'] = $this->name;


        if (isset($this->request->post[$this->name.'_name'])) {
            $this->data[$this->name.'_name'] = $this->request->post[$this->name.'_name'];
        } else {
            $this->data[$this->name.'_name'] = $this->config->get($this->name.'_name');
        }

        if (isset($this->request->post[$this->name.'_status'])) {
            $this->data[$this->name.'_status'] = $this->request->post[$this->name.'_status'];
        } else {
            $this->data[$this->name.'_status'] = $this->config->get($this->name.'_status');
        }

        if (isset($this->request->post[$this->name.'_sort_order'])) {
            $this->data[$this->name.'_sort_order'] = $this->request->post[$this->name.'_sort_order'];
        } else {
            $this->data[$this->name.'_sort_order'] = $this->config->get($this->name.'_sort_order');
        }

        if (isset($this->request->post[$this->name.'_show_error_text'])) {
            $this->data[$this->name.'_show_error_text'] = $this->request->post[$this->name.'_show_error_text'];
        } else {
            $this->data[$this->name.'_show_error_text'] = $this->config->get($this->name.'_show_error_text');
        }

        $this->load->model('localisation/weight_class');
        $this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		$this->template = $this->type.'/'.$this->name.'.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', $this->type.'/'.$this->name)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        if (isset($this->request->post[$this->name.'_module'])) {
            foreach ($this->request->post[$this->name.'_module'] as $key => $val) {
                foreach ($val['title'] as $language_id => $value) {
                    if (!$value) {
                        $this->error['title'][$key][$language_id] = $this->language->get('error_title') .' '. $this->name.'_module';
                    }
                }

            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>