<?php
class ControllerModuleUkrcreditsSimple extends Controller {

	public function index(){		
		$setting = $this->config->get('ukrcredits_settings');
		$this->load->language('module/ukrcredits');	

		$data['ukrcredits_setting'] = $this->config->get($type.'ukrcredits_settings');
		$data['currency_left'] 		= $this->currency->getSymbolLeft($this->session->data['currency']);
		$data['currency_right'] 	= $this->currency->getSymbolRight($this->session->data['currency']);
		$data['button_confirm'] 	= $this->language->get('button_confirm');

		$data['text_mounth'] 	= $this->language->get('text_mounth');
		$data['text_loading'] 	= $this->language->get('text_loading');
		$data['text_payments'] 	= $this->language->get('text_payments');
		$data['text_per'] 		= $this->language->get('text_per');
		$data['text_total'] 	= $this->language->get('text_total');
		
		if (isset($this->request->get['uctype'])) {
            $type = $this->request->get['uctype'];
        } else {
            $type = false;
        }
		
		if ($type) {
			$partsCount = 24;
			foreach ($this->cart->getProducts() as $cart) {
				$privat_query = $this->db->query("SELECT * FROM product_ukrcredits WHERE product_id = '" . (int)$cart['product_id'] . "'");
				if ($privat_query->row) {
					if ($privat_query->row['partscount_'.$type] <= $partsCount && $privat_query->row['partscount_'.$type] !=0) {
						$partsCount = (int)$privat_query->row['partscount_'.$type];
					}
				}
			}
			
			if ($partsCount == 24) {
				$partsCount = $setting[$type.'_pq'];
			}

			$this->load->model('setting/extension');

			$total_data = array();					
			$total = 0;
			$taxes = $this->cart->getTaxes();

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$sort_order = array(); 

				$results = $this->model_setting_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('total/' . $result['code']);

						$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
					}

					$sort_order = array(); 

					foreach ($total_data as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $total_data);
				}		
			}
			
			$replace_array = array($this->currency->getSymbolLeft($this->session->data['currency']),$this->currency->getSymbolRight($this->session->data['currency']),$this->language->get('thousand_point'));
			$data['total'] = str_replace($replace_array,"",$this->currency->format($this->tax->calculate($total, $this->config->get('tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']));
			
			$data['credit'] = array(
				'type' 				=> $setting[$type.'_merchantType'],
				'name' 				=> $this->language->get('text_title_'.mb_strtolower($setting[$type.'_merchantType'])),
				'text_in_product'	=> $setting['text_in_product_' . $type][$this->config->get('config_language_id')],
				'text_in_cart'		=> $setting['text_in_cart_' . $type][$this->config->get('config_language_id')],
				'partsCount' 		=> $partsCount,
				'price' 			=> $data['total']
			);
			
			if (isset($this->session->data['ukrcredits_' . $type . '_sel'])) {
				$data['credit']['partsCountSel'] = $this->session->data['ukrcredits_' . $type . '_sel'];
			} else {
				$data['credit']['partsCountSel'] = '';
			}

			$data['oc15'] = true;
			$this->data = $data;
			$this->template = 'module/ukrcredits_simple.tpl';
			$this->response->setOutput($this->render());
		}
    }
}