<div>
	
	<? 
		$pagebreaks = array();
		if (count($order['product']) >= 20){
			
			for ($i=1; $i<=count($order['product']); $i++){
				if ($i%20==0){
					$pagebreaks[$i] = true;
				}
			}
		}		
	?>
	
	<table style="width:100%;">
		<tr>
			<td align="left" style="font-size:12px;">
				<?php echo $order['store_name']; ?><br />
				<?php echo $order['store_url']; ?><br />		
			</td>
			<td align="right">
				<img src='<? echo $order['store_logo']; ?>' />
			</td>
		</tr>
	</table>
	
	<div style="clear:both;height:5px;">	  
	</div>
	
	<style>
		table.address tr td{padding:1mm;border:0px;}
	</style>
	
	<style>
		table.product {border-collapse: collapse}
		table.product tr td{padding:2px;border:1px solid black;border-collapse: collapse; background:#FFF;}
		table.product tr.heading td{text-align:center;}
		.invoice_total_input, .invoice_total_sum_input {font-weight:700;text-align:right;border:0px;}
		.invoice_total_input:focus, .invoice_total_sum_input:focus {border:1px solid #40A0DD;}
		.invoice_total_input:disabled, .invoice_total_sum_input:disabled {background:white;}
		.totals_cheque_row_delete {float:left; cursor:pointer;}
		.totals_cheque_row_wide_to_remove {min-width:150px;}
	</style>
	
	<? if (md5(trim($order['order_info']['shipping_firstname'].$order['order_info']['shipping_lastname'])) != md5(trim($order['order_info']['firstname'].$order['order_info']['lastname']))) { ?>	
		<? $not_same_customer = true; ?>	
		<? } else { ?>
		
	<? } ?>
	
	<table class="product" id="preinvoice_table" style="width:100%">
		<tr>
			<td colspan="2" style="border:0">
				
			</td>
			<td colspan="2" style="text-align:left; font-size:14px; padding-bottom:6px;border:0; word-wrap:none; white-space: nowrap;">
				<b>Товарный чек № <? echo $cheque_num; ?></b>
			</td>
			<td colspan="1" style="text-align:right; font-size:14px; padding-bottom:6px;border:0">
				<b><? echo $cheque_date; ?></b>
			</td>
			<? /* if (isset($order['customer']['discount_card']) && mb_strlen($order['customer']['discount_card']) > 2) { ?>
				<td colspan="3" style="text-align:center; padding-left:5px; font-size:15px; padding-bottom:8px;border:0">
					ДК #<? echo $order['customer']['discount_card']; ?>
				</td>
				<? } else { ?>
				<td colspan="3" style="text-align:left; padding-left:5px; font-size:14px; padding-bottom:8px;border:0">
					ДК #
				</td>	
			<? } */ ?>
			<td colspan="3" style="text-align:left; padding-left:5px; font-size:14px; padding-bottom:8px;border:0"></td>	
			<tr>
				<td colspan="3">
					<b>Покупатель</b>
				</td>
				<td colspan="2">	
					<input style="border:1px solid #40A0DD; text-align:left;" type="text" class="invoice_total_input" value="<? echo $order['order_info']['firstname']; ?>&nbsp;<? echo isset($order['order_info']['lastname'])?$order['order_info']['lastname']:''; ?>" />
				</td>
				<td colspan="3">
					<input style="border:1px solid #40A0DD; text-align:left;" type="text" class="invoice_total_input" value="<? echo $order['order_info']['telephone']; ?>" />
				</td>
			</tr>
			
			<tr>   
				<td colspan="3" style="white-space: nowrap; word-wrap: normal">
					<b>Получатель</b>
				</td>
				<td colspan="2">
					<? if (isset($not_same_customer)) { ?>
						<input style="border:1px solid #40A0DD; text-align:left;" type="text" class="invoice_total_input" value="<? echo $order['order_info']['shipping_firstname']; ?>&nbsp;<? echo isset($order['order_info']['shipping_lastname'])?$order['order_info']['shipping_lastname']:''; ?>" />
						<? } else { ?>	
						<input style="border:1px solid #40A0DD; text-align:left;" type="text" class="invoice_total_input" value="" />
					<? } ?>
				</td>
				<td colspan="3">
					<? if (isset($order['order_info']['fax']) && mb_strlen($order['order_info']['fax'])>1) { ?>					
						<input style="border:1px solid #40A0DD; text-align:left;" type="text" class="invoice_total_input" value="<? echo (isset($order['order_info']['fax']) && $order['order_info']['fax'])?$order['order_info']['fax']:''; ?>" />	
						<? } else { ?>			
						<input style="border:1px solid #40A0DD; text-align:left;" type="text" class="invoice_total_input" value="" />	
					<? } ?>
				</td>
			</tr>
			
			<? if (!empty($order['order_info']['shipping_passport_serie'])) { ?>
				<tr>   
					<td colspan="3" style="white-space: nowrap; word-wrap: normal">
						<b>Документ</b>
					</td>
					<td colspan="5">
						
						<? 
							$_ppassport = $order['order_info']['shipping_passport_serie'];
							if ($order['order_info']['shipping_passport_given']){
								$_ppassport .= ',&nbsp;выдан&nbsp;'.$order['order_info']['shipping_passport_given'];				
							}
							
						?>
						
						
						<input style="border:1px solid #40A0DD; text-align:left;" type="text" class="invoice_total_input" value="<? echo $_ppassport; ?>" /><br /><span style="font-size:10px; color:#666; ">Согласно правилам оформления грузов, определенным в Федеральном законе от 6 июля 2016 г. № 374-ФЗ</span>
					</td>
					
				</tr>
			<? } ?>
			
			<tr>
				<td colspan="3"  style="white-space: nowrap; word-wrap: normal">
					<b>Доставка</b>
				</td>
				<td colspan="2">
					<input style="border:1px solid #40A0DD; text-align:left;" type="text" class="invoice_total_input" value="<?php echo $order['shipping_address']; ?>" />
				</td>
				<td colspan="3">
					<input style="border:1px solid #40A0DD; text-align:left;" type="text" class="invoice_total_input" value="<?php echo $order['short_shipping_method']; ?>" />
				</td>
			</tr>
			
			<tr>
				<td colspan="3"  style="white-space: nowrap; word-wrap: normal" >
					<b>Оплата</b>
				</td>
				<td colspan="2" style="text-align:left;" align="left">
					<? if (false) { ?>
						<? if (md5(trim($order['payment_address'])) != md5(trim($order['shipping_address']))) { ?>
							<?php echo $order['payment_address']; ?>
							<? } else { ?> 
						<? } ?>
					<? } ?>
					<input style="border:1px solid #40A0DD; text-align:left;" type="text" class="invoice_total_input" value="<? echo $cheque_type; ?>" />
				</td>
				<td colspan="3">
					<input style="border:1px solid #40A0DD; text-align:left;" type="text" class="invoice_total_input" value="<?php echo $order['payment_method']; ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="7" style="height:8px; border:0px;"></td>
			</tr>
			
			<tr class="heading">
				<td align="center" style="width:1px; text-align:center; word-wrap: normal;"><b>№</b></td>
				<td align="center" style="width:1px; text-align:center; word-wrap: normal;"></td>
				<td align="center" style="width:1px; text-align:center; word-wrap: normal;"><b>Артикул</b></td>  
				<td align="center" colspan="2" style="text-align:center; word-wrap: normal;"><b>Наименование</b></td>
				<td align="center" style="width:1px; text-align:center; word-wrap: normal;"><b>Кол-во</b></td>  
				<td align="center" style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal" ><b>Цена</b></td>
				<td align="center" style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal" ><b>Сумма<? $csis = array('UAH' => 'грн', 'RUB' => 'руб', 'BYN' => 'руб', 'KZT' => 'тнг'); ?><? if (isset($csis[$order['order_info']['currency_code']])) { ?>&nbsp;/&nbsp;<? echo $csis[$order['order_info']['currency_code']]; ?>
				<? } ?></b></td>
			</tr>
			
			<tbody>
				<?php $i=0; foreach ($order['product'] as $product) { $i++; ?>
					<tr>
						<td style="text-align:center;"><?php echo $i; ?></td>
						<td align="center" width="1px" style="white-space: nowrap; width:1px;"><?php echo $product['symbol']; ?></td>		
						<td style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal; padding-left:2px;padding-right:2px;">
							<? if ($product['set_products'] && mb_strlen($product['model']) > 9) { 
								$product['model'] = $product['product_id'];		
							} ?>
							<? if ($order['max_sku_length'] <=7) { ?>
								&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $product['model']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
								<? } else { ?>
								&nbsp;<?php echo $product['model']; ?>&nbsp;
							<? } ?>
							
						</td>								
						<td colspan="2" style="width:100%">
							<? if ($product['short_name'] && mb_strlen($product['short_name']) > 2) { ?>
								<input style="border:1px solid #40A0DD; text-align:left;" type="text" class="invoice_total_input" value="<?php echo $product['short_name']; ?>" />
								<? } else { ?>
								<input style="border:1px solid #40A0DD; text-align:left;" type="text" class="invoice_total_input" value="<?php echo $product['name']; ?>" />
							<? } ?>
							
							<?php foreach ($product['option'] as $option) { ?>
								<br />
								&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
							<?php } ?>
							
							<? if ($product['cumulative_is_active']) { ?>
								<br /><span style="font-size:10px; color:#666;">действует накопительная скидка -<? echo (int)$scd_discount_percent; ?>%</span>
							<? } ?>
							<? if ($product['coupon_is_active']) { ?>
								<br /><span style="font-size:10px; color:#666;">действует промокод  <? echo $coupon_name; ?> (-<? echo (int)$coupon_percent; ?>%)</span>
							<? } ?>
							<? if ($product['coupon_presentlogic_is_active']) { ?>
								<br /><span style="font-size:10px; color:#666;">действует промокод  <? echo $coupon_name; ?> (товар в подарок)</span>
							<? } ?>
							<? if ($product['birthday_is_active']) { ?>
								<br /><span style="font-size:10px; color:#666;">действует скидка на день рождения (-<? echo (int)$birthday_discount_percent; ?>%)</span>
							<? } ?>
							<? if ($product['ao_text']) { ?>
								<br /><span style="font-size:10px; color:#666;"><? echo $product['ao_text'];  ?></span>
							<? } ?>
							<? if ($product['points'] || $product['points_used_total_txt']) { ?>
								<br />
								<? if ($product['points']) { ?>
									<span style="font-size:10px; color:#fff; background-color:#666;"><? echo $product['points'];  ?></span>
								<? } ?>
								
								<? if ($product['points_used_total_txt']) { ?>
									<? if ($product['points']) { ?>&nbsp;&nbsp;<?php } ?>
									<span style="font-size:10px; color:#fff; background-color:#666;">бонусами: <? echo $product['points_used_total_txt'];  ?></span>
									
									&nbsp;&nbsp;<span style="font-size:10px; color:#fff; background-color:#666;">к оплате: <? echo $product['total_pay_with_cash'];  ?></span>
								<? } ?>
							<? } ?>							
						</td>      
						<td style="text-align:center;" align="center"><?php echo $product['quantity']; ?></td>
						<td align="right" style="white-space: nowrap; padding-left:5px;padding-right:5px;"><?php echo $product['price']; ?></td>
						<td align="right" style="white-space: nowrap; padding-left:5px;padding-right:5px;"><?php echo $product['total']; ?></td>
					</tr>
					
					<? if ($product['set_products']) { ?>
						<? foreach ($product['set_products'] as $set_product) { ?>
							<tr>
								<td style="text-align:center;"></td>
								<td style="width:1px; text-align:center; white-space: nowrap; word-wrap: normal; padding-left:2px;padding-right:2px; font-size:10px;">
									<? if ($order['max_sku_length'] <=7) { ?>
										&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $set_product['model']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
										<? } else { ?>
										&nbsp;<?php echo $set_product['model']; ?>&nbsp;
									<? } ?>
								</td>
								<td colspan="2" style="width:100%; font-size:10px;">
									<? if ($set_product['short_name'] && mb_strlen($set_product['short_name']) > 2) { ?>
										<?php echo $set_product['short_name']; ?>
										<? } else { ?>
										<?php echo $set_product['name']; ?>
									<? } ?>		     
								</td>  
								<td style="text-align:center; font-size:10px;" align="center"><?php echo $set_product['quantity']; ?></td>
								<td align="right" style="white-space: nowrap; padding-left:5px;padding-right:5px; font-size:10px;"><?php echo $set_product['price_national']; ?></td>
								<td align="right" style="white-space: nowrap; padding-left:5px;padding-right:5px; font-size:10px;"><?php echo $set_product['total_national']; ?></td>
							</tr>
							
						<? } ?>
					<? } ?>
					
				<?php } ?>
				<?php if (isset($order['order_info']['currency_code']) && $order['order_info']['currency_code'] == 'UAH') {?>
					<?php $empty_cnt = 1; ?>
					<?php } else { ?>
					<?php $empty_cnt = 3; ?>
				<?php } ?>
				<?php if(count($order['product']) < $empty_cnt): ?>
				<?php for($i = 1; $i <= ($empty_cnt - count($order['product'])); $i++ ): ?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td colspan="2">&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<?php endfor; ?>
				<?php endif;?>
				<?php if (isset($has_prev_deliveries) || isset($has_next_deliveries)) { ?>
					<tr>
						<td style="padding-bottom:3mm; padding-top:1mm; width:auto; vertical-align: top; white-space:nowrap; border:0px;" colspan="7">							
							<i>
								<? if (isset($has_prev_deliveries)) { ?>	
									<span style="font-size:10px; color:#666;">&#x2714; - товар Вами получен ранее&nbsp;&nbsp;</span>
								<? } ?>
								<span style="font-size:10px; color:#666;">&#x27A4; - товар доставлен&nbsp;&nbsp;</span>
								<? if (isset($has_next_deliveries)) { ?>	
									<span style="font-size:10px; color:#666;">&#x2609; - товар в пути, будет доставлен позже</span>
								<? } ?>
							</i>
						</td>					
					</tr>
				<?php } ?>
			</tbody>
			
			<tfoot id="invoice-table-tfoot">
				<?php $i=0; foreach ($order['total'] as $total) { $i++; ?>
					<tr id="totals_cheque_<? echo $i; ?>" class="tr_total_draggable"> 
						<td align="left" colspan="7" style="cursor:move; white-space: nowrap; padding-left:5px;padding-right:5px;">
							<span class="totals_cheque_row_delete" onclick="$(this).parent().parent().remove(); cheque_recount();"><img src="view/image/delete.png" /></span>
							<input type="text" style="text-align:left" id="totals_title_input_<? echo $i; ?>" class="invoice_total_input" value="<?php echo $total['title']; ?>" />
						</td>
						<td align="right" class="totals_cheque_row_wide_to_remove" style="white-space: nowrap; padding-left:5px;padding-right:5px;">
							<? if (!($total['text'] === false)) { ?>
								<input <? if ($total['code'] == 'total') { ?>id="cheque_total_total" disabled="disabled"<? } elseif ($total['code'] == 'sub_total') { ?>disabled="disabled"<? } else { ?>id="totals_text_input_<? echo $i; ?>"<? } ?> type="text"  class="invoice_total_sum_input" value="<?php echo $total['text']; ?>" />
							<? } ?>	  
						</td>
					</tr>
				<?php } ?>
			</tfoot>
			
			
			<?php foreach ($order['voucher'] as $voucher) { ?>
				<tr>
					<td align="left"><?php echo $voucher['description']; ?></td>
					<td align="left"></td>
					<td align="right">1</td>
					<td align="right"><?php echo $voucher['amount']; ?></td>
					<td align="right"><?php echo $voucher['amount']; ?></td>
				</tr>
			<?php } ?>
			
		</table>  
		<div id="cheque_add_row" style="display:inline-block; float:right; clear:both; padding-top:10px; text-align:right; cursor:pointer; border-bottom:1px dashed black;"><img src="view/image/add.png" /> добавить еще строку</div>
		
		<?php if ($order['related_orders']) { ?>
			<table style="border:0px;" border="0px">
				<tr>
					<td style="padding-top:3mm; width:auto; vertical-align: top; white-space:nowrap;"><b>Заказ доставляется с:</b></td>
					<td style="padding-top:3mm; padding-left:4mm; text-align:left; vertical-align: center;">
						<? foreach ($order['related_orders'] as $_related_order_id) { ?>
							<span style="padding-left:5px; padding-right:5px; padding-top:3px; padding-bottom:3px; font-weight:700">заказом #<? echo $_related_order_id; ?></span>&nbsp;&nbsp;
						<? } ?>
					</td>
				</tr>
			</table>
		<?php } ?>
		
		<?php if ($order['comment']) { ?>
			<table style="border:0px;margin-top:3mm;" border="0px">
				<tr>				
					<td style="border-left:1px solid grey; padding-left:4mm; text-align:left; vertical-align: center;">
						<? foreach ($order['comment'] as $_comment) { ?>
							<?php echo $_comment; ?><br />
						<? } ?>
					</td>
				</tr>
			</table>
		<?php } ?>
		
		<?php if ($order['qr_link']) { ?>
			<table style="border:0px;margin-top:3mm;" border="0px">
				<tr>				
					<td style="padding-left:4mm; text-align:left; vertical-align: middle; border:0px;" border="0px" valign="middle">
						<img src="<? echo $order['qr_link'] ?>" />
					</td>
					<td style="padding-left:4mm; text-align:left; vertical-align: middle; border:0px;" border="0px" valign="middle">
						<img src="<? echo HTTPS_CATALOG; ?>catalog/view/image/payment/Visa_gscale.png" alt="visa logo" width="80px">
						<img src="<? echo HTTPS_CATALOG; ?>catalog/view/image/payment/MasterCard_gscale.png" alt="" width="80px"><br />
						
						Вы можете оплатить заказ картой прямо сейчас, отсканировав этот QR-код и перейдя по ссылке.<br />
						<span style="font-size:10px; color:#666; font-style:italic;"><i>не передавайте этот QR-код третьим лицам, поскольку он содержит в себе ключ входа в личный кабинет</i></span>
					</td>
				</tr>
			</table>
		<?php } ?>
		
		<? if ($order['prim']) { ?>
			<table style="width:100%;" id="table_cheque_comment">
				<tr>
					<td style="width:1px; padding-top:3mm;"><b>Примечание:</b></td>
					<td style="padding-top:3mm; padding-left:4mm;">
						<textarea id="cheque_comment" style="width:100%;"><? if ($order['no_return']) { ?>Внимание! Возврат товара по данному заказу не предусмотрен условиями согласованной с Вами офертой. За исключением случая выявления брака, дефектов товара во время получения заказа.<? } ?>
						</textarea>
					</td>
				</tr>
			</table>
		<? } ?>
		<? if ($order['need_sign']) { ?>
			<div style="width:100%;text-align:center;padding-bottom:0px;padding-top:8px;font-size:10px;">
				Заказ получил, товар проверил. Претензий не имею: ____________________&nbsp;&nbsp;Дата: ___________________
			</div>
		<? } ?>
		<? if ($order['order_info']['date_delivery_to'] != '0000-00-00' || $order['order_info']['date_delivery_actual'] != '0000-00-00') { ?>
			<div style="width:100%;text-align:left;font-size:10px;padding-bottom:0px;padding-top:8px;">
				<? if ($order['order_info']['date_delivery_actual'] != '0000-00-00') { ?>
					Контрольная дата: <? echo date('d.m.Y', strtotime($order['order_info']['date_delivery_actual'])) ?>
					<? } elseif ($order['order_info']['date_delivery_to'] != '0000-00-00') { ?>
					Контрольная дата: <? echo date('d.m.Y', strtotime($order['order_info']['date_delivery_to'])) ?>
				<? } ?>
			</div>
		<? } ?>
	</div>				