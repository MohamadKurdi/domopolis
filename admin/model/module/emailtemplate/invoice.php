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
		return false;
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