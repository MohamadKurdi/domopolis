<?php
class ControllerToolReceiptLog extends Controller {
	private $error = array();

	public function index() {		
		$this->language->load('tool/error_log');		
		$this->document->setTitle('CheckBox ' . $this->language->get('heading_title'));

		$data['heading_title'] = 'CheckBox ' . $this->language->get('heading_title');
		
		$data['text_list'] 		= $this->language->get('text_list');
		$data['text_confirm'] 	= $this->language->get('text_confirm');

		$data['button_download'] 	= $this->language->get('button_download');
		$data['button_clear'] 		= $this->language->get('button_clear');

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text' => 'CheckBox програмний РРО',
			'href' => $this->url->link('sale/receipt', 'token=' . $this->session->data['token']),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/receipt_log', 'token=' . $this->session->data['token'], true),
			'separator' => ' :: '
		);

		$data['download'] 				= $this->url->link('tool/receipt_log/download', 'token=' . $this->session->data['token'], true);
		$data['backup_receipt_tables'] 	= $this->url->link('tool/receipt_log/backup_receipt_tables', 'token=' . $this->session->data['token'], true);
		$data['clear'] 					= $this->url->link('tool/receipt_log/clear', 'token=' . $this->session->data['token'], true);

		$data['log'] = '';
		$file = DIR_LOGS . 'checkbox.log';

		if (file_exists($file)) {
			$size = filesize($file);

			if ($size >= 5242880) {
				$suffix = array(
					'B',
					'KB',
					'MB',
					'GB',
					'TB',
					'PB',
					'EB',
					'ZB',
					'YB'
				);

				$i = 0;

				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}

				$data['error_warning'] = sprintf($this->language->get('error_warning'), basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
			} else {
				$data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
			}
		}

		$data['column_left'] = '';

		$this->data = $data;
		$this->template = 'tool/error_log.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function download() {
		$this->language->load('tool/error_log');

		$file = DIR_LOGS . 'checkbox.log';

		if (file_exists($file) && filesize($file) > 0) {
			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename="' . $this->config->get('config_name') . '_' . date('Y-m-d_H-i-s', time()) . '_checkbox.log"');
			$this->response->addheader('Content-Transfer-Encoding: binary');
			$this->response->setOutput(file_get_contents($file, FILE_USE_INCLUDE_PATH, null));
		} else {
			$this->session->data['error'] = sprintf($this->language->get('error_warning'), basename($file), '0B');
			$this->redirect($this->url->link('tool/receipt_log', 'token=' . $this->session->data['token'], true));
		}
	}

	public function backup_receipt_tables() {

		 if ($this->user->hasPermission('modify', 'tool/backup')) {
			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename="' . DB_DATABASE . '_' . date('Y-m-d_H-i-s', time()) . '_checkbox_backup.sql"');
			$this->response->addheader('Content-Transfer-Encoding: binary');

			$this->load->model('tool/backup');

			$tables_backup = array(
				DB_PREFIX .'order_receipt',
				DB_PREFIX .'shift',
			);

			$this->response->setOutput($this->model_tool_backup->backup($tables_backup));
		}  
	}
	
	public function clear() {
		$this->language->load('tool/error_log');

		if (!$this->user->hasPermission('modify', 'tool/receipt_log')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} else {
			$file = DIR_LOGS . 'checkbox.log';
			$handle = fopen($file, 'w+');
			fclose($handle);
			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->redirect($this->url->link('tool/receipt_log', 'token=' . $this->session->data['token'], true));
	}
}
