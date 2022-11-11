<?php
/**
 * HTML Email template extension
 *
 * @author: Ben Johnson, opencart-templates
 * @email: info@opencart-templates.co.uk
 * @website: http://www.opencart-templates.co.uk
 *
 */
class ModelModuleEmailTemplateInvoice extends Model {

	public function getInvoice($order_id, $create_file = false){
		if(class_exists('VQMod')) {
			require_once(VQMod::modCheck(DIR_SYSTEM . 'library/emailtemplate/invoice.php'));
		} else {
			require_once(DIR_SYSTEM . 'library/emailtemplate/invoice.php');
		}

		$order_id = intval($order_id);

		$this->load->model('module/emailtemplate');
		$this->load->model('localisation/language');
		$this->load->model('sale/order');
		$this->load->model('setting/store');
		$this->load->model('setting/setting');
		$this->load->model('tool/image');

		$pdf = new EmailTemplateInvoice('P', 'mm', 'A4');

		$template = new Template();

		$template->data['order'] = $this->model_sale_order->getOrder($order_id);
		if(!$template->data['order']) return false;

		$template->data['order']['language_id'] = ($template->data['order']['language_id']) ? $template->data['order']['language_id'] : 1;

		$template->data['order']['shipping_address'] = EmailTemplate::formatAddress($template->data['order'], 'shipping', $this->data['order']['shipping_address_format']);
		$template->data['order']['shipping_method'] = strip_tags($template->data['order']['shipping_method']);

		$template->data['order']['payment_address'] = EmailTemplate::formatAddress($template->data['order'], 'payment', $this->data['order']['payment_address_format']);
		$template->data['order']['payment_method'] = strip_tags($template->data['order']['payment_method']);

		$template->data['order']['date_added'] = date($this->language->get('date_format_short'), strtotime($template->data['order']['date_added']));

		$template->data['order']['totals'] = $this->model_sale_order->getOrderTotals($order_id);

		foreach($template->data['order']['totals'] as &$total){
			$total['title'] = strip_tags($total['title']);
		}

		$template->data['store'] = array_merge(
			$this->model_setting_store->getStore($template->data['order']['store_id']),
			$this->model_setting_setting->getSetting("config", $template->data['order']['store_id'])
		);

		if(!isset($template->data['store']['config_url'])) {
			$template->data['store']['config_url'] = HTTP_CATALOG;
		}

		if($template->data['store']['config_logo'] && file_exists(DIR_IMAGE . $template->data['store']['config_logo'])) {
			$template->data['store']['config_logo'] = $this->getImageUrl($template->data['store']['config_logo']);
		}

		if($template->data['store']['config_address']) {
			$template->data['store']['config_address'] = nl2br($template->data['store']['config_address']);
		}

		$template->data['order']['products'] = array();

		$products = $this->model_sale_order->getOrderProducts($order_id);

		foreach ($products as $product) {
			$option_data = array();
			$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);
			foreach ($options as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
				}
				$option_data[] = array(
					'name'  => $option['name'],
					'value' => $value
				);
			}

			$str = '';
			if( count($option_data) > 0){
				$str = '<br /><br />';
				foreach ($option_data as $value) {
					$str .= $value['name'] . ' : ' .$value['value'] . '<br />' ;
				}
			}

			$template->data['order']['products'][] = array(
				'name'     => $product['name'],
				'model'    => $product['model'],
				'option'   => $str,
				'quantity' => $product['quantity'],
				'url' 	   => $template->data['order']['store_url'] . 'index.php?route=product/product&product_id=' . $product['product_id'],
				'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $template->data['order']['currency_code'], $template->data['order']['currency_value']),
				'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $template->data['order']['currency_code'], $template->data['order']['currency_value'])
			);
		}

		$template->data['order']['vouchers'] = array();

		if(method_exists($this->model_sale_order, 'getOrderVouchers')){
			$vouchers = $this->model_sale_order->getOrderVouchers($order_id);
			foreach ($vouchers as $voucher) {
				$template->data['order']['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $template->data['order']['currency_code'], $template->data['order']['currency_value'])
				);
			}
		}

		$template->data['config'] = $this->model_module_emailtemplate->getConfig(array(
			'store_id' 	  => $template->data['order']['store_id'],
			'language_id' => $template->data['order']['language_id']
		), true, true);

		$languages = $this->model_localisation_language->getLanguages();

		foreach($languages as $lang){
			if($lang['language_id'] == $template->data['order']['language_id']){
				$oLanguage = new Language($lang['directory']);
				$language = array_merge(
					$oLanguage->load_full($lang['filename']),
					$oLanguage->load_full('sale/order'),
					$oLanguage->load_full('sale/invoice')
				);
			}
		}
		$language['a_meta_charset'] = 'UTF-8';

		$template->data = array_merge($template->data, $language);

		$pdf->setLanguageArray($language);

		$pdf->data = $template->data;

		//echo '<pre>'; var_dump($pdf->data); exit;

		$pdf->data['html'] = $template->fetch('mail/pdf_invoice.tpl');

		$pdf->Build();

		$pdf->Draw();

		if (ob_get_length()) ob_end_clean();

		$filename = 'order_'.$order_id.'.pdf';

		if($create_file){
			$dir = DIR_CACHE . 'invoices/';
			if(!is_dir($dir) || !is_writable($dir)){
				mkdir($dir, 0777, true);
			}
			if(!is_dir($dir)){
				EmailTemplate::systemLog('Permissions Error: could not create directory \'invoices\' at: ' . $dir);
				return false;
			}

			if(file_exists($dir.$filename)){
				@unlink($dir.$filename);
			}

			$pdf->Output($dir.$filename, 'F');
			return $dir.$filename;
		} else {
			$pdf->Output($filename, 'I');
			return true;
		}
	}

	/**
	 * Image Absolute URL, no resize
	 *
	 * @duplicate: system/library/emailtemplate/email_template.php
	 */
	public function getImageUrl($filename){
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		}

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			if($this->config->get('config_ssl')){
				$url = $this->config->get('config_ssl') . 'image/';
			} else {
				$url = defined("HTTPS_IMAGE") ? HTTPS_IMAGE : HTTPS_CATALOG . 'image/';
			}
		} else {
			if($this->config->get('config_url')){
				$url = $this->config->get('config_url') . 'image/';
			} else {
				$url = defined("HTTP_IMAGE") ? HTTP_IMAGE : HTTP_CATALOG . 'image/';
			}
		}

		return $url . $filename;
	}
}