<?php
	class ControllerSaleCourier extends Controller {
		private $error = array();
		
		public function index() {
			$this->language->load('sale/order');
			
			$this->document->setTitle('Интерфейс курьера');
			$this->data['_title'] = 'Интерфейс курьера';
			$this->load->model('sale/order');
			
			$this->response->redirect(str_replace('&amp;', '&', $this->url->link('sale/courier2', 'token=' . $this->session->data['token'])));
			
			$this->data['getOrderAjaxUrl'] = $this->url->link('sale/courier/getOrderAjax', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['fullUrl'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->template="sale/courier.tpl";
			$this->response->setOutput($this->render());
		}
		
		public function updateOnlyDataAjax($data){
			$data = $this->request->post;
			$order_id = (int)$data['order_id'];
			$status_id = (int)$data['order_status_id'];
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$order_info = $this->model_sale_order->getOrder($order_id);
			
			
			
			//постоплата
			/*
				if (isset($data['payment']) && $data['payment'] && ((int)$data['payment_amount'] > 0)){
				
				$amount_national = (int)$data['payment_amount'];
				
				$amount = $this->currency->convert($amount_national, $order_info['currency_code'], $this->config->get('config_currency'));
				$this->model_sale_customer->addTransaction($order_info['customer_id'], 'Заказ #'.$order_id.': наличные курьеру', $amount, $amount_national, $order_info['currency_code'], $order_id, false, false, 'courier_cash');
				
				
				}
			*/
			
			if ($data['taken_products']){
				$gp = explode(',', $data['taken_products']);
				$this->db->query("UPDATE order_product SET `taken` = '0' WHERE order_id = ".(int)$data['order_id']);
				foreach ($gp as $gp_id){
					if ($gp_id > 0) {
						$this->db->query("UPDATE order_product SET `taken` = '1' WHERE order_product_id = ".(int)$gp_id);				
					}
				}
			}
			
			if (isset($data['ttn']) && mb_strlen($data['ttn']) > 1){
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_ttns` SET	
				order_id = '" . (int)$data['order_id'] . "', 
				ttn = '" .$this->db->escape($data['ttn']) . "',
				delivery_code = '" .$this->db->escape($data['shipping_code']) . "', 						
				date_ttn = '" .$this->db->escape($data['date_sent']). "'
				");	
				$this->db->query("UPDATE `".DB_PREFIX."order` SET `ttn` = '".$this->db->escape($data['ttn'])."' WHERE order_id = ".(int)$data['order_id']);			
			}
			
			if (isset($data['discount_card'])){
				$this->db->query("UPDATE `".DB_PREFIX."customer` SET `discount_card` = '".$this->db->escape($data['discount_card'])."' WHERE customer_id = ".(int)$data['customer_id']);
			}
			
			echo '<span style="color:green;">Обновлено: ТТН, товары, дисконтная карта</span><br />';
			return;
		}
		
		public function updateOrderAjax(){
			$data = $this->request->post;
			$order_id = (int)$data['order_id'];
			$status_id = (int)$data['order_status_id'];
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$this->load->model('localisation/order_status');
			$order_info = $this->model_sale_order->getOrder($data['order_id']);
			
			
			if ($this->model_sale_order->getIfOrderClosed($data['order_id'])){			
				echo '<span style="color:green;">Заказ уже закрыт и не подлежит редактированию!</span><br />';
				die();
			}
			
			//Разбираем товары
			$current_status_id = $this->model_sale_order->getOrderLastHistory((int)$data['order_id']);
			
			if ($data['order_status_id'] == $current_status_id){
				
				$this->updateOnlyDataAjax($data);
				$out = '<span style="color:red;">Данные обновлены</span><br />';
				$out = '<span style="color:red;">Нельзя поставить два одинаковых статуса подряд!</span>';
				
				echo $out;
				return;
			}
			
			if ($data['taken_products']){
				$gp = explode(',', $data['taken_products']);
				$this->db->query("UPDATE order_product SET `taken` = '0' WHERE order_id = ".(int)$data['order_id']);
				foreach ($gp as $gp_id){
					if ($gp_id > 0) {
						$this->db->query("UPDATE order_product SET `taken` = '1' WHERE order_product_id = ".(int)$gp_id);				
					}
				}
			}
			
			if (!isset($data['notify'])){
				$data['notify'] = 0;
			}				
			
			if ($data['notify'] &&  isset($data['sms']) && $data['sms'] && (mb_strlen($data['sms'], 'UTF-8') > 2)){
				$data['history_sms_text'] = $data['sms'];
				} else {
				$data['history_sms_text'] = false;
			}								
			
			$customer_comment_text = 'изменен статус на: <b>'.$this->model_localisation_order_status->getOrderStatusName($status_id).'</b>';
			$_data = array(
			'customer_id' 		      => $order_info['customer_id'], 
			'comment'     		      => $customer_comment_text,
			'order_id'    		      => $data['order_id'],
			'call_id'     		      => 0,
			'manager_id'    	      => 0,
			'need_call'    			  => 0,
			'segment_id'    		  => 0,
			'order_status_id'    	  => $data['order_status_id'],
			'prev_order_status_id'    => $current_status_id					
			);
			$this->model_sale_customer->addHistoryExtended($_data);
			
			if (isset($data['ttn']) && mb_strlen($data['ttn']) > 1){
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_ttns` SET	
				order_id = '" . (int)$data['order_id'] . "', 
				ttn = '" .$this->db->escape($data['ttn']) . "',
				delivery_code = '" .$this->db->escape($data['shipping_code']) . "', 						
				date_ttn = '" .$this->db->escape($data['date_sent']). "'
				");	
				$this->db->query("UPDATE `".DB_PREFIX."order` SET `ttn` = '".$this->db->escape($data['ttn'])."' WHERE order_id = ".(int)$data['order_id']);			
			}
			
			if (isset($data['date_sent']) && mb_strlen($data['date_sent']) > 1){
				$this->db->query("UPDATE `".DB_PREFIX."order` SET `date_sent` = '".$this->db->escape($data['date_sent'])."' WHERE order_id = ".(int)$data['order_id']);
			}
			
			//обновляем клиента, если есть дисконтная карта
			if (isset($data['discount_card']) /* && (mb_strlen($data['discount_card']) > 1) */){
				$this->db->query("UPDATE `".DB_PREFIX."customer` SET `discount_card` = '".$this->db->escape($data['discount_card'])."' WHERE customer_id = ".(int)$data['customer_id']);
			}
			
			//постоплата
			if (isset($data['payment']) && $data['payment'] && ((int)$data['payment_amount'] > 0)){
				
				if ($status_id == $this->config->get('config_complete_status_id') || $status_id == $this->config->get('config_partly_delivered_status_id')){
					$amount_national = (int)$data['payment_amount'];
					
					$amount = $this->currency->convert($amount_national, $order_info['currency_code'], $this->config->get('config_currency'));
					$this->model_sale_customer->addTransaction($order_info['customer_id'], 'Заказ #'.$order_id.': наличные курьеру', $amount, $amount_national, $order_info['currency_code'], $order_id, false, false, 'courier_cash');
				}
				
			}
			
			//транзакция			
			
			$transaction = false;
			if ($status_id == $this->config->get('config_complete_status_id')){						
				
				$this->load->model('sale/order');
				$this->load->model('sale/customer');											
				$order_total = $order_info['total_national'];					
				$customer_balance_national = $this->model_sale_customer->getTransactionTotalNational($order_info['customer_id']);			
				$balance_national = $this->model_sale_customer->getTransactionTotalNational($order_info['customer_id'], $order_id);
				$total_transaction_count = $this->model_sale_customer->getTotalTransactions($order_info['customer_id'], $order_id);
				
				$amount_national = ($order_total);
				$amount = $this->currency->convert($amount_national, $order_info['currency_code'], $this->config->get('config_currency'));
				
				if ($balance_national == 0 && $total_transaction_count == 0){
					$this->model_sale_customer->addTransaction($order_info['customer_id'], 'Заказ #'.$order_id.': внесение суммы на счет', $amount, $amount_national, $order_info['currency_code'], $order_id, false, false, 'auto_order_close');																		
				}
				
				$amount_national = -1*($order_total);
				$amount = $this->currency->convert($amount_national, $order_info['currency_code'], $this->config->get('config_currency'));
				
				$this->model_sale_customer->addTransaction($order_info['customer_id'], 'Заказ #'.$order_id.': списание со счета по статусу Выполнен', $amount, $amount_national, $order_info['currency_code'], $order_id, false, false, 'auto_order_close');	
				
				$this->load->model('user/user');					
				$user_name = $this->model_user_user->getRealUserNameById($this->user->getID());
				
				$aldata = array(
				'type' => 'warning',
				'text' => $user_name.' изменил статус на <b>ВЫПОЛНЕН</b>. <br />Заказ',
				'entity_type' => 'order', 
				'entity_id' => $order_id
				);
				
				$this->mAlert->insertAlertForGroup('sales', $aldata);
				$this->mAlert->insertAlertForGroup('admins', $aldata);
			}	
			
			if (isset($data['notify']) && $data['notify'] &&  isset($data['sms']) && $data['sms'] && (mb_strlen($data['sms'], 'UTF-8') > 2)){
				$this->load->model('setting/setting');
				
				$options = array(
				'to'       => $data['telephone'],
				'from'     => $this->model_setting_setting->getKeySettingValue('config', 'config_sms_sign', $data['store_id']),				
				'message'  => $data['sms']
				);
				
				$this->db->query("UPDATE `" . DB_PREFIX . "order_ttns` SET sms_sent = NOW() WHERE ttn = '" . $this->db->escape($data['ttn']) . "'");
				/*
					$this->load->library('sms');
					$sms = new Sms($this->config->get('config_sms_gatename'), $options);
					$sms_id = $sms->send();
					
					if ($sms_id){
					$sms_status = 'Только отправлено';					
					} else {
					$sms_status = 'неудача';
					}
					$sms_data = array(
					'order_status_id' => $data['order_status_id'],
					'sms' => $options['message']
					);
					
					$log = new Log('order_send_sms.txt');
					$log->write(serialize($sms_data) . ', returning: ' . );
					
					$this->model_sale_order->addOrderSmsDeliveryHistory($data['order_id'], $sms_data, $sms_status, $sms_id);
				*/
			}
			
			$this->model_sale_order->addOrderHistory((int)$data['order_id'], $data);
			
			$out = 'Обновлен заказ <b>'.$data['order_id'].'</b>';		
			$out .= '<br />Новый статус: <b>'.$data['order_status_id'].'</b>';
			if ($transaction) {
				$out .= '<br />Лицевой счет: <b>'.$transaction.'</b>';
			}
			if (isset($data['ttn']) && mb_strlen($data['ttn']) > 1){
				$out .= '<br />ТТН: <b>'.$data['ttn'].'</b>';
			}
			
			$out .= '<br />------------------------------------------';
			if (mb_strlen($data['discount_card']) > 1) {
				$out .= '<br />Дисконтная карта покупателя: <b>' . $data['discount_card'] . '</b>';		
				} else {
				$out .= '<br />Дисконтная карта покупателя: <b>' . 'нет' . '</b>';	
			}	
			$out .= '<br /><br />Для проверки рекомендую нажать Загрузить заказ'.'</b>';
			
			echo $out;
		}
		
		
		public function getOrderAjax(){
			$this->load->model('sale/order');
			$this->load->model('sale/customer');
			$this->load->model('localisation/order_status');
			
			if ($order_info = $this->model_sale_order->getOrder((int)$this->request->get['order_id'])){
				
				$totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
				$order_products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
				$order_statuses = $this->model_localisation_order_status->getAllowedOrderStatuses($order_info['order_status_id']);
				$customer = $this->model_sale_customer->getCustomer($order_info['customer_id']);
				$order_transactions_total = (int)$this->model_sale_customer->getTransactionTotalNational($order_info['customer_id'], $this->request->get['order_id']);
				$current_delivery_num = (int)$this->model_sale_order->getCurrentDelivery($this->request->get['order_id']) + 1;
				
				$out = '<form id="main_form">';
				$out .= "<table style='margin-top:30px; margin-bottom:10px;width:100%'>";
				$out .= 	"<tr><td>" . $order_info['store_name']."</td> <td>" . $order_info['firstname'] . ' ' . $order_info['lastname'] . "</td> </tr>";
				$out .= 	"<tr><td>" . $order_info['shipping_city'] . ', ' . $order_info['shipping_address_1'] . "</td>
				<td>" . $order_info['telephone'] . "</td>
				</tr>";			
				if ($order_info['fax']){
					$out .= 	"<tr><td></td><td>" . $order_info['fax'] . "</td></tr>";
				}
				
				$out .= "<tr><td>Дисконтная карта: <input type='text' id='discount_card' name='discount_card' value='" . $customer['discount_card'] . "' /></td><td></td></tr>";
				$out .= "<input type='hidden' id='customer_id' name='customer_id' value='" . $customer['customer_id'] . "' />";
				$out .= "</table>";
				$out .= "<div style='width:100%; height:1px; border-bottom:1px solid grey;'></div>";
				
				
				$taken_products = array();
				$untaken_products = array();
				foreach ($order_products as &$order_product){
					
					if ($order_product['total_national'] > 0){
						$order_product['totalp'] = $this->currency->format($order_product['total_national'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], '1');						
						} else {
						$order_product['totalp'] = number_format($this->currency->convert($order_product['total_national'],'EUR',$order_info['currency_code']),0,',',' ');
					}
					
					
					if ($current_delivery_num == $order_product['delivery_num']){
						$order_product['delivery_td'] = "<td style='font-size:12px; text-align:center; background-color:#CDEB8B'>" . $order_product['delivery_num'] ."</td>";				
						} else {
						$order_product['delivery_td'] = "<td style='font-size:12px; text-align:center;'>" . $order_product['delivery_num'] ."</td>";						
					}
					
					if ($order_product['taken']){
						$taken_products[] = $order_product;
						} else {
						$untaken_products[] = $order_product;
					}
					
					
					
				}
				
				$_dnamearray = [
				'первая','вторая','третья','четвертая','пятая','шестая','седьмая','восьмая','девятая'
				];	
				
				$out .= "<div style='width:100%;margin-top:5px;margin-bottom:4px;text-align:left;'>Текущая поставка: <span style='background:#CDEB8B; padding:3px;'>". $_dnamearray[$current_delivery_num-1] ."</span></div>";			
				if (count($taken_products)){
					
					$out .= "<table class='ptable' style='margin-bottom:5px;margin-bottom:10px;width:100%'>";
					$out .= "<thead style='background-color:#C3D9FF'>
					<td></td>
					<td>&#9972;</td>
					<td>Наименование</td>
					<td>SKU</td>
					<td>шт</td>
					<td>".$order_info['currency_code']."</td>
					</thead>";
					
					foreach ($taken_products as $taken_product){
						
						$out .= "<tr>
						<td>
						<input type='checkbox' value='".$taken_product['order_product_id']."' name='order_products' checked='checked' />
						</td>
						" . $taken_product['delivery_td']. "
						<td style='font-size:12px;'>" . $taken_product['name']."</td>
						<td style='font-size:12px; white-space:nowrap;'>" . $taken_product['model'] . "</td>
						<td style='text-align:center; font-size:12px;'>" . $taken_product['quantity'] . "</td>
						<td style='white-space:nowrap; font-size:12px; text-align:right;'>" . $taken_product['totalp'] . "</td>
						</tr>";				
						
					}
					
					$out .= "</table>";				
				}
				
				$out .= "<table class='ptable' style='margin-top:5px; margin-bottom:10px;width:100%'>";
				$out .= "<thead style='background-color:#C3D9FF'>
				<td><input type='checkbox' value='' onclick=\"$('.may_be_checked').click();\" /></td>
				<td>&#9972;</td>
				<td>Наименование</td>
				<td>SKU</td>
				<td>шт</td>
				<td>".$order_info['currency_code']."</td>
				</thead>";
				
				foreach ($untaken_products as $untaken_product){
					
					$out .= "<tr>
					<td>";
					if ($current_delivery_num == $untaken_product['delivery_num']){
						$out .= "<input type='checkbox' class='may_be_checked' value='".$untaken_product['order_product_id']."' name='order_products' />";
						} else {
						$out .= "<input type='checkbox' value='".$untaken_product['order_product_id']."' name='order_products' />";
					}
					
					$out .=	"</td>
					" . $untaken_product['delivery_td']."
					<td style='font-size:12px;'>" . $untaken_product['name']."</td>
					<td style='font-size:12px; white-space:nowrap;'>" . $untaken_product['model'] . "</td>
					<td style='text-align:center; font-size:12px;'>" . $untaken_product['quantity'] . "</td>
					<td style='white-space:nowrap; font-size:12px; text-align:right;'>" . $untaken_product['totalp'] . "</td>
					</tr>";				
					
				}
				
				$out .= "</table>";
				$out .= "<div style='width:100%; height:1px; border-bottom:1px solid grey;'></div>";
				
				
				$out .= "<table style='margin-top:10px; margin-bottom:10px;width:100%'>";
				foreach ($totals as $total){
					$out .= "<tr><td style='text-align:right; padding-right:20px;'>".$total['title']."</td><td style='text-align:right;'>" . $total['text']."</td></tr>";					
				}
				$out .= "</table>";			
				
				$out .= "<div style='width:100%; height:1px; border-bottom:1px solid grey;margin-bottom:5px;'></div>";
				
				$out .= '<table style="width:100%" width="100%">';
				
				$payment_must = mb_stripos($order_info['payment_method'], 'при получении') || mb_stripos($order_info['payment_method'], 'при доставке');
				
				$out .= '<tr><td width="139">Наличные</td><td style="white-space:nowrap;"><input id="payment" type="checkbox" name="payment" value="1" ';
				if ($payment_must){
					$out .= ' checked="checked" onclick="$(this).attr(\'checked\', \'checked\');" ';
				}
				$out .= ' />&nbsp;&nbsp;<input type="text" id="payment_amount" name="payment_amount" style="width:140px;" value="'. (int)$order_info['total_national'] .'" /> '.$order_info['currency_code'].'</td>';
				$out .= '<td style="color:red; text-align:right;" width="">Cчет: <b>'. $order_transactions_total .' '.$order_info['currency_code'].'</b></td></tr>';
				$out .=	'</table>';			
				
				
				if ($order_info['closed']){
					
					foreach ($order_statuses as $order_status) {
						if ($order_status["order_status_id"] == $order_info["order_status_id"]) {
							$current_status = $order_status["name"];
						}
					}
					
					$out .= "<div style='width:100%; font-size:18px; margin-bottom:5px; margin-top:5px; color:red'>Заказ в статусе ". $current_status ." заблокирован для редактирования.</div>";
					
					} else {
					
					$out .= "<div style='width:100%; height:1px; border-bottom:1px solid grey;margin-bottom:5px;'></div>";
					$out .= '<table><tr><td>Статус:</td><td style="width:200px"><select onchange="getStatusSMSTextAjax()" id="order_status_id" name="order_status_id" style="width:100%">';
					foreach ($order_statuses as $order_status) {
						if ($order_status["order_status_id"] == $order_info["order_status_id"]) {
							$out .= '<option value="' . $order_status["order_status_id"] . '" selected="selected">' . $order_status["name"] . '</option>';
							} else {
							$out .= '<option value="' . $order_status["order_status_id"] . '">' .	$order_status["name"] . '</option>';
						}
					}
					$out .=	 '</select></td>';
					$out .=  '<td>&nbsp;&nbsp;&nbsp;&nbspУведомление:'.'<input id="notify" type="checkbox" name="notify" value="1" /></td></tr>';
					$out .= '<td colspan="4"><span style="font-size:10px; color:red">Внимание! При установке статуса ВЫПОЛНЕН с виртуального счета покупателя будет снята сумма заказа</span></td>';
					$out .=  '<tr><td>ТТН (если есть)</td><td colspan="2"><input type="text" name="ttn" id="ttn" length="50" style="width:100%;" value="" /></td></tr>';
					
					if ($order_info['date_sent'] && $order_info['date_sent'] != '0000-00-00'){
						$sent = $order_info['date_sent'];
						} else {
						$sent = date("Y-m-d");
					}
					$out .=  '<tr><td>Дата дост./отпр.</td><td colspan="2"><input type="text" name="date_sent" id="date_sent" length="50" style="width:100%;" value="'.$sent.'" /></td></tr>';
					$out .= "<tr><td>SMS:<br /><span style='border-bottom:1px dashed black; cursor:pointer;' onclick=\"$('#sms').val('');\">очистить</span></td>";
					$out .= "<td colspan='2'><textarea id='sms' name='sms' style='width:100%; height:50px; margin-top:10px;' /></td></tr>";
					$out .= "<tr><td>Комментарий:</td>";
					$out .= "<td colspan='2'><textarea id='comment' name='comment' style='width:100%; height:50px; margin-top:10px;' /></td></tr>";
					$out .= "</table>";
					
					$out .= '<input type="button" value="Добавить историю, ТТН, обновить статус" id="addComment" style="padding:5px 10px;margin-top:10px;" />
					<input type="button" value="Обновить изменения" id="reloadAfterAddComment" onclick="$(\'#load_order\').click();" style="display:none;padding:5px 10px;margin-top:10px;" />';
					$out .= '</form>';	
					
				}
				$out .= '<script>';
				$out .=	"$('#addComment').click(function(){
				
				if ($('#payment:checked').val() && !$('#payment_amount').val()){
				$('#result').html('<span style=\'color:red\'>Ошибка: необходимо ввести сумму наличных при способе оплаты при доставке!</span>');
				} else {
				
				var gp = '';
				$('input[name=order_products]:checked').each(
				function(){
				gp+=$(this).val()+',';
				}
				);															
				$.ajax({
				url: '".str_replace('&amp;','&',$this->url->link('sale/courier/updateOrderAjax', 'token=' . $this->session->data['token'], 'SSL'))."',
				type: 'POST',
				data: {
				order_id : " . $order_info['order_id'] . ",
				telephone : '". $order_info['telephone'] ."',
				store_id : " . $order_info['store_id'] . ",
				order_status_id : $('#order_status_id').val(),
				shipping_code : '". $order_info['shipping_code'] ."',
				ttn : $('#ttn').val(),
				date_sent : $('#date_sent').val(),
				comment : $('#comment').val(),
				notify : $('#notify:checked').val(),
				payment : $('#payment:checked').val(),
				payment_amount : $('#payment_amount').val(),
				sms : $('#sms').val(),
				discount_card : $('#discount_card').val(),
				customer_id : $('#customer_id').val(),
				taken_products : gp,
				},
				beforeSend: function(){
				$('#addComment').hide();
				$('#result').html('');
				$('#result').html('Ждем... Обработка занимает время...');							
				},
				success: function(e){
				$('#sms').val('');
				$('#ttn').val('');
				$('#comment').val('');
				$('#result').html(e);
				$('#reloadAfterAddComment').show();
				},
				error: function(e){						
				$('#result').html('Error: '+e);								
				}
				});	
				}
				})";
				$out .= '</script>';
				$out .= '<script>';
				$out .= "function getSMSTextAjax(){
				$.ajax({
				url	: '".str_replace('&amp;','&',$this->url->link('sale/order/getDeliverySMSTextAjax', 'token=' . $this->session->data['token'], 'SSL'))."',
				type: 'POST',
				data : {									
				order_id : " . $order_info['order_id'] . ",
				senddate : $('input[name=\'date_sent\']').val(),
				ttn : $('input[name=\'ttn\']').val(),
				shipping_code : '". $order_info['shipping_code'] ."',
				},
				dataType : 'text',
				success : function(text){
				$('textarea[name=\'sms\']').val(text);
				},
				error : function(text){
				console.log(text);
				}						
				});										
				}
				
				$('input[name=\'ttn\']').keyup(function(){
				getSMSTextAjax();					
				});
				
				$('input[name=\'date_sent\'],input[name=\'ttn\']').change(function(){
				getSMSTextAjax();					
				});";
				$out .= '</script>';
				$out .= '<script>';
				$out .= "function getStatusSMSTextAjax(){
				$.ajax({
				url	: '".str_replace('&amp;','&',$this->url->link('sale/order/getStatusSMSTextAjax', 'token=' . $this->session->data['token'], 'SSL'))."',
				type: 'POST',
				data : {									
				order_id : " . $order_info['order_id'] . ",
				order_status_id : $('#order_status_id').val(),
				comment : $('textarea[name=\'comment\']').val()
				},
				dataType : 'json',
				success : function(json){
				if (json.message.length > 2) { $('textarea[name=\'sms\']').val(json.message); }
				},
				error : function(text){
				console.log(text);
				}						
				});										
				}				";			
				$out .= '</script>';
				echo $out;
				
				} else {
				echo 'Нет такого заказа';
			}
		}
		
		
		
		
	}				