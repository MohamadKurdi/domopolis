<?php 
	class ControllerCatalogParties extends Controller {
		private $error = array(); 
		
		public function index() {
			$this->language->load('catalog/product');
			
			$this->document->setTitle('Закупочные партии товаров'); 
			
			$this->load->model('catalog/parties');
			$this->load->model('catalog/product');
			$this->load->model('sale/order');
			
			$this->getList();
		}
		
		public function delete() {
			$this->language->load('catalog/product');
			
			$this->document->setTitle('Закупочные партии товаров'); 
			
			$this->load->model('catalog/product');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $product_id) {
					$this->model_catalog_product->deleteProductFromparties($product_id);
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->redirect($this->url->link('catalog/parties', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		public function getAllManagers(){
			$this->load->model('user/user');
			$managers = $this->model_user_user->getUsersByGroups(array(12, 19), true);		
			
			return $managers;
		}
		
		
		protected function getList() {
			
			$this->document->addScript('view/javascript/expromptum.min.js');
			
			$this->data['heading_title'] = 'Закупочные партии товаров'; 
			
			
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'op.part_num';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'DESC';
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			if (isset($this->request->get['filter_partie'])) {
				$filter_partie = $this->request->get['filter_partie'];
				} else {
				$filter_partie = false;
			}
			
			$url = '';
			
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['filter_partie'])) {
				$url .= '&filter_partie=' . $this->request->get['filter_partie'];
			}
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
			);
			
			$this->data['breadcrumbs'][] = array(
			'text'      => 'Закупочные партии товаров',
			'href'      => $this->url->link('catalog/parties', 'token=' . $this->session->data['token'] . $url, 'SSL'),       		
			'separator' => ' :: '
			);
			
			
			$this->data['products'] = array();
			
			$data = array(
			'sort'            => $sort,
			'order'           => $order,
			'filter_partie'   => $filter_partie,
			'start'           => ($page - 1) * 5,
			'limit'           => 5
			);
			
			$this->load->model('tool/image');
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$this->load->model('localisation/order_status');
			$this->load->model('user/user');
			
			$product_total = $this->model_catalog_parties->getTotalAllParties($data);
			$results = $this->model_catalog_parties->getJustParties($data);
			
			$this->data['parties'] = array();
			
			if ($results) {
				
				foreach ($results as $result) {
					
					$order_nums = $this->model_catalog_parties->getPartieOrders($result['part_num']);
					$orders = array();		
					
					$min_date = '2100-00-00';
					$max_date = '1980-00-00';
					
					$total_by_partie = 0;
					$total_by_partie_explicit = 0;
					$total_by_managers = array();
					
					foreach ($this->getAllManagers() as $_manager){
						$total_by_managers[$_manager['user_id']] = array(
						'total_orders' => 0,
						'total_explicit'    => 0,
						'total_in_partie' => 0,
						'manager_name'    => $this->model_user_user->getRealUserNameById($_manager['user_id'])
						);
					}
					
					foreach ($order_nums as $order_num){
						$order = $this->model_sale_order->getOrder($order_num['order_id']);
						$results_products = $this->model_catalog_parties->getOrderProducts($order_num['order_id']);
						
						$has_problem = false;
						foreach ($results_products as $_rp){
							if (!$_rp['part_num']){
								$has_problem = true;
								break;
							}
						}												
						
						$total_by_order_in_this_partie = 0;
						
						foreach ($results_products as &$_rp){
							$_rp['total_txt'] = $this->currency->format($_rp['total_national'], $order['currency_code'], 1);
							$_rp['totalwd_txt'] = $this->currency->format($_rp['totalwd_national'], $order['currency_code'], 1);
							
							if ($_rp['part_num'] == $result['part_num']) {
								$total_by_order_in_this_partie += $_rp['totalwd_national'];
								$total_by_partie += $_rp['totalwd_national'];
							}
							
						}
						unset($_rp);
						
						if (strtotime($order['date_added']) < strtotime($min_date)){
							$min_date = date('Y-m-d', strtotime($order['date_added']));
						}
						
						if (strtotime($order['date_added']) > strtotime($max_date)){
							$max_date = date('Y-m-d', strtotime($order['date_added']));
						}
						
						
						$order_totals = $this->model_sale_order->getOrderTotals($order_num['order_id']);
						$total_national = 0;
						foreach ($order_totals as $_total){
							if ($_total['code'] == 'total'){
								$total_national = $_total['value_national'];
								$total_by_partie_explicit +=  $_total['value_national'];
								break;
							}
						}
						
						if (isset($total_by_managers[$order['manager_id']])){
							$total_by_managers[$order['manager_id']]['total_orders'] += 1;
							$total_by_managers[$order['manager_id']]['total_explicit'] += $total_national;
							$total_by_managers[$order['manager_id']]['total_in_partie'] += $total_by_order_in_this_partie;
						}
						
						$orders[] = array(
						'order_id'    => $order['order_id'],
						'manager_id'  => $order['manager_id'],
						'has_problem' => $has_problem,
						'manager_name'=> $this->model_user_user->getRealUserNameById($order['manager_id']),
						'order_status'=> $this->model_localisation_order_status->getOrderStatus($order['order_status_id']),
						'total_by_order_in_this_partie_txt' => $this->currency->format($total_by_order_in_this_partie, $order['currency_code'], 1),
						'total_national_txt' =>  $this->currency->format($total_national, $order['currency_code'], 1),
						'href' => $this->url->link('sale/order/update', 'order_id=' . $order['order_id'] .'&token=' . $this->session->data['token']),
						'order' => $order,
						'products' => $results_products,
						);					
					}
					
					foreach ($total_by_managers as $key => &$_manager){
						$_manager['total_explicit_txt'] = $this->currency->format($_manager['total_explicit'], $order['currency_code'], 1);
						$_manager['total_in_partie_txt'] = $this->currency->format($_manager['total_in_partie'], $order['currency_code'], 1);
					}
					unset($_manager);
					
					$this->data['parties'][] = array(
					'part_num' => $result['part_num'],
					'total_by_partie' => $this->currency->format($total_by_partie, $order['currency_code'], 1),
					'total_by_partie_explicit' => $this->currency->format($total_by_partie_explicit, $order['currency_code'], 1),
					'total_by_managers' => $total_by_managers,
					'min_date' => date('d.m.Y' ,strtotime($min_date)),
					'max_date' => date('d.m.Y' ,strtotime($max_date)),
					'orders' => $orders,
					);
				}
				
				} else {
				
			}
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
				} else {
				$this->data['error_warning'] = '';
			}
			
			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];
				
				unset($this->session->data['success']);
				} else {
				$this->data['success'] = '';
			}
			
			$url = '';
			
			
			if ($order == 'ASC') {
				$url .= '&order=DESC';
				} else {
				$url .= '&order=ASC';
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->data['button_delete'] = $this->language->get('button_delete');		
			$this->data['button_filter'] = $this->language->get('button_filter');
			
			$this->data['sort_name'] = $this->url->link('catalog/parties', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
			$this->data['sort_model'] = $this->url->link('catalog/parties', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
			$this->data['sort_price'] = $this->url->link('catalog/parties', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
			$this->data['sort_quantity'] = $this->url->link('catalog/parties', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
			$this->data['sort_status'] = $this->url->link('catalog/parties', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
			$this->data['sort_order'] = $this->url->link('catalog/parties', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
			
			$this->data['token'] = $this->session->data['token'];
			
			
			$url = '';
			
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['filter_partie'])) {
				$url .= '&filter_partie=' . $this->request->get['filter_partie'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = 5;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('catalog/parties', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
			$this->data['pagination'] = $pagination->render();
			
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['filter_partie'] = $filter_partie;
			
			if (isset($this->request->get['ajax']) && ($this->request->get['ajax'] == 1)){
				$this->template = 'catalog/parties_ajax.tpl';
				} else {
				$this->template = 'catalog/parties.tpl';
				$this->children = array(
				'common/header',
				'common/footer'
				);			
			}
			
			
			$this->response->setOutput($this->render());
		}
		
		public function getOnDateCheques(){
			$_ondate = date('Y-m-d', strtotime($this->request->get['date']));
			if (!$_ondate || mb_strlen($_ondate)<10){
				return false;
			}
			$_store_id = (int)$this->request->get['store_id'];
			
			$this->load->model('catalog/parties');
			
			$_dtime = date('YmdHis');
			
			$_a4dir =   DIR_EXPORT . 'ondate/' . date('Y_m_d', strtotime($_ondate)) . '.' . $_dtime .  '/' . 'A4.' . md5(time());
			$_a5dir =   DIR_EXPORT . 'ondate/' . date('Y_m_d', strtotime($_ondate)) . '.' . $_dtime . '/' . 'A5.' . md5(time());
			$_pdir  =   DIR_EXPORT . 'ondate/' . date('Y_m_d', strtotime($_ondate)) . '.' . $_dtime . '/';
			$_zipname = DIR_EXPORT . 'ondate/' . date('Y_m_d', strtotime($_ondate)) .'.'. md5(time()).'.zip';
			
			mkdir($_a4dir, 0777, true);
			mkdir($_a5dir, 0777, true);
			
			$_orders = $this->db->query("SELECT order_id FROM `order` WHERE date_delivery_actual = '" . $_ondate . "' AND store_id = '" . $_store_id . "'");
			
			foreach ($_orders->rows as $_order){
				
				$_last_cheque = $this->model_catalog_parties->getOrderLastCheque($_order['order_id']);			
				$html = $_last_cheque['html'];
				
				if ($_last_cheque['filename'] && file_exists(DIR_EXPORT . 'odinass/cheques/' . $_last_cheque['filename'])){
					
					$_from = DIR_EXPORT . 'odinass/cheques/' . $_last_cheque['filename'];
					$_to = $_a4dir.'/' . 'Товарный чек '.$_last_cheque['invoice_name'].".pdf";
					copy($_from, $_to);					
					
					
					} else {
					
					$mpdf = new \Mpdf\Mpdf([
					'mode' => 'utf-8',
					'format' => 'A4'
					]);		
					
					$html = str_replace(' !important', '', $html);
					$html = html_entity_decode($html);
					
					$mpdf->WriteHTML("<style>*, html, body { font-size:9pt;  font-family: Arial, Helvetica; }</style>\n\n".$html);
					$filename = 'Товарный чек '.$_last_cheque['invoice_name'].".pdf";
					$mpdf->Output($_a4dir.'/' . $filename, 'F');
					
				}
				
				
			}
			
			$this->zip($_pdir, $_zipname, false);
			
			$file = $_zipname;
			if (file_exists($file)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/zip');
				header('Content-Disposition: attachment; filename="'.basename($file).'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				readfile($file);
				exit;
				
			}
			
			
		}
		
		public function getPartieCheques(){		
			$partie = $this->request->get['partie'];		
			$this->load->model('catalog/parties');
			
			$_dtime = date('YmdHis');
			
			$_a4dir =   DIR_EXPORT . 'parties/' . $partie . '.' . $_dtime .  '/' . 'A4.' . md5(time());
			$_a5dir =   DIR_EXPORT . 'parties/' . $partie . '.' . $_dtime . '/' . 'A5.' . md5(time());
			$_pdir  =   DIR_EXPORT . 'parties/' . $partie . '.' . $_dtime . '/';
			$_zipname = DIR_EXPORT . 'parties/' . $partie .'.'. md5(time()).'.zip';
			
			mkdir($_a4dir, 0777, true);
			mkdir($_a5dir, 0777, true);
			
			$_orders = $this->model_catalog_parties->getPartieOrders($partie);
			
			foreach ($_orders as $_order){			
				$_last_cheque = $this->model_catalog_parties->getOrderLastCheque($_order['order_id']);			
				$html = $_last_cheque['html'];
				
				if ($_last_cheque['filename'] && file_exists(DIR_EXPORT . 'odinass/cheques/' . $_last_cheque['filename'])){
					
					$_from = DIR_EXPORT . 'odinass/cheques/' . $_last_cheque['filename'];
					$_to = $_a4dir.'/' . 'Товарный чек '.$_last_cheque['invoice_name'].".pdf";
					copy($_from, $_to);					
					
					
					} else {
					
					$mpdf = new \Mpdf\Mpdf([
					'mode' => 'utf-8',
					'format' => 'A4'
					]);		
					
					$html = str_replace(' !important', '', $html);
					$html = html_entity_decode($html);
					
					$mpdf->WriteHTML("<style>*, html, body { font-size:9pt;  font-family: Arial, Helvetica; }</style>\n\n".$html);
					$filename = 'Товарный чек '.$_last_cheque['invoice_name'].".pdf";
					$mpdf->Output($_a4dir.'/' . $filename, 'F');
					
				}
				
				/* a5 iteration */
				/*
					$html = $_last_cheque['html'];
					$mpdf = new mPDF('', 'A5-L','','',8,8,5,1,0,0);
					$mpdf->SetDisplayMode('fullpage');
					$mpdf->setAutoBottomMargin = 'stretch';
					
					$html = str_replace(' !important', '', $html);
					$html = html_entity_decode($html);
					
					$mpdf->WriteHTML("<style>*, html, body { font-size:9pt;  font-family: Arial, Helvetica; margin-bottom:0px; margin-top:0px;} @page{ margin-footer:0pt; }</style>\n\n".$html);
					
					$filename = 'Товарный чек '.$_last_cheque['invoice_name'].".pdf";			
					$mpdf->Output($_a5dir . '/' . $filename, 'F');
				*/
			}
			
			
			$this->zip($_pdir, $_zipname, false);
			
			$file = $_zipname;
			if (file_exists($file)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/zip');
				header('Content-Disposition: attachment; filename="'.basename($file).'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				readfile($file);
				exit;
			}
			//	$this->rrmdir($_pdir);
		}
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'catalog/parties')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		protected function validateNewOrder() {
			if (!$this->user->hasPermission('modify', 'sale/order')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			if (!$this->error) {
				return true;
				} else {
				return false;
			}
		}
		
		private function rrmdir($src) {
			
			if (mb_strlen($src) < 20){
				return false;
			}
			
			$dir = opendir($src);
			while(false !== ( $file = readdir($dir)) ) {
				if (( $file != '.' ) && ( $file != '..' )) {
					$full = $src . '/' . $file;
					if ( is_dir($full) ) {
						$this->rrmdir($full);
					}
					else {
						unlink($full);
					}
				}
			}
			closedir($dir);
			rmdir($src);
		}
		
		
		
		private function Zip($source, $destination, $include_dir = false)
		{
			
			if (!extension_loaded('zip') || !file_exists($source)) {
				return false;
			}
			
			if (file_exists($destination)) {
				unlink ($destination);
			}
			
			$zip = new ZipArchive();
			if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
				return false;
			}
			$source = str_replace('\\', '/', realpath($source));
			
			if (is_dir($source) === true)
			{
				
				$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
				
				if ($include_dir) {
					
					$arr = explode("/",$source);
					$maindir = $arr[count($arr)- 1];
					
					$source = "";
					for ($i=0; $i < count($arr) - 1; $i++) { 
						$source .= '/' . $arr[$i];
					}
					
					$source = substr($source, 1);
					
					$zip->addEmptyDir($maindir);
					
				}
				
				foreach ($files as $file)
				{
					$file = str_replace('\\', '/', $file);
					
					// Ignore "." and ".." folders
					if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
					continue;
					
					$file = realpath($file);
					
					if (is_dir($file) === true)
					{
						$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
					}
					else if (is_file($file) === true)
					{
						$zip->addFromString(mb_convert_encoding(str_replace($source . '/', '', $file), "cp866", "UTF-8"), file_get_contents($file));
					}
				}
			}
			else if (is_file($source) === true)
			{
				$zip->addFromString(mb_convert_encoding(basename($source), "cp866", "UTF-8"), file_get_contents($source));
			}
			
			return $zip->close();
		}
		
		
	}
	
	
	
	
	
?>