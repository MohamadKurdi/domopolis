<script>
	function getStatusSMSTextAjax(){
		$.ajax({
			url	: ' <?php echo $getStatusSMSTextAjaxUrl; ?>',
			type: 'POST',
			data : {									
				order_id : <?php echo $order_info['order_id']  ?>,
				order_status_id : $('#order_status_id').val(),
				comment : $('textarea[name=\'comment\']').val()
			},
			dataType : 'json',
			success : function(json){
				$('textarea[name=\'sms\']').val(json.message);				
			},
			error : function(text){
				console.log(text);
			}						
		});										
	}	
	function getSMSTextAjax(){
		$.ajax({
			url	: '<?php echo $getDeliverySMSTextAjaxUrl;  ?>',
			type: 'POST',
			data : {									
				order_id : <?php echo $order_info['order_id'] ?>,
				senddate : $('input[name=\'date_sent\']').val(),
				ttn : $('input[name=\'ttn\']').val(),
				shipping_code : '<?php echo $order_info['shipping_code']  ?>',
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
</script>
<form id="main_form">
	<table class="edit-table" style='margin-bottom:10px;width:100%'>
		<tr>
			<td><i class="fa fa-user"></i> <?php echo $order_info['firstname'] ?>  <?php echo $order_info['lastname'] ?></td> 
			<td>
				<span style="white-space: nowrap;"><i class="fa fa-phone"></i> <a href="tel:<?php echo $order_info['telephone'] ?>"><?php echo $order_info['telephone'] ?></a></span>
				<?php if ($order_info['fax']){ ?>
					<br /><br /><span style="white-space: nowrap;"><i class="fa fa-phone"></i> <a href="tel:<?php echo $order_info['fax'] ?>" class="phone-number"><?php echo $order_info['fax'] ?></a></span>
				<?php } ?>
			</td> 
		</tr>
		<tr>
			<td colspan="2"><i class="fa fa-map-marker"></i> <span id="adress"><?php echo $order_info['shipping_city'] ?> <?php echo $order_info['shipping_address_1'] ?></span>
				<span class="btn-copy" onclick="copytext('#adress')" title="скопировать адрес">
					<span class="tooltiptext" style="display: none;">Адрес скопирован</span>
				</span>
			</td>
		</tr>	
		<?php if ($geolocation) { ?>
			<tr>
				<td colspan="2"><i class="fa fa-map-marker"></i> <a href="geo:<?php echo $geolocation ?>"><?php echo $geolocation ?></a></td>
			</tr>	
		<?php } ?>
		<? /*
			<tr>
			<td colspan="2"><input type='text' id='discount_card' name='discount_card' value='<?php echo $customer['discount_card'] ?>' placeholder="Дисконтная карта"/></td>
			</tr>
		*/ ?>
		<input type='hidden' id='customer_id' name='customer_id' value='<?php echo $customer['customer_id'] ?>' />
	</table>
	
	
	<div class="current_delivery" style='width:100%;margin-top:10px;margin-bottom:5px;text-align:left;'>
		Текущая поставка: <span class="span-decoraion"> <?php echo $_dnamearray[$current_delivery_num-1] ?></span>
	</div>	
	
	
	<?php if (!empty($order_products_untaken)) { ?>
		
		<table class='ptable' style='margin-top:5px; margin-bottom:10px;width:100%'>
			<thead style='background-color:#e16a5d; color: #fff; text-align: center;'>			
				<td>&#9972;</td>
				<td>Товар</td>
				<td>шт</td>
				<td><?php echo $order_info['currency_code'] ?></td>
			</thead>
			
			<?php $i=1;	foreach ($order_products_untaken as $order_product_untaken){ ?>
				<tr>
					<?	if ($current_delivery_num == $order_product_untaken['delivery_num']){ ?>
						<td class="td-decoration" style='font-size:12px; text-align:center;'><?php echo $order_product_untaken['delivery_num'] ?></td>
						<?php } else { ?>
						<td style='font-size:12px; text-align:center;'><?php echo $order_product_untaken['delivery_num'] ?></td>
					<?php } ?>
					
					<td style='font-size:12px; min-width: 220px;'>
						<?php echo $order_product_untaken['name'] ?>
						<br />
						<span style="font-size:11px; display:inline-block; float:right; padding:3px; color:#FFF; background-color:grey;"><?php echo $order_product_untaken['model'] ?></span>
					</td>
					<td style='text-align:center; font-size:12px;'><?php echo $order_product_untaken['quantity'] ?></td>
					
					<td style='white-space:nowrap; font-size:12px; text-align:right;'>
						<?php echo  $order_product_untaken['totalp'] ?>					
					</td>
				</tr>		
				
				<tr>
					<td colspan="2" style="background-color:white!important;color:grey; font-size:9px!important; <?php if ($i < count($order_products_untaken)) { ?>  border-bottom:0px; <?php } ?>"><i class="fa fa-info-circle"></i> клиент отказался от этого товара, чтоб вернуть обратно, нажми</td>
					<td colspan="2" class="move-to-taken" data-order-id="<?php echo $order_product['order_id']; ?>" data-order-product-id="<?php echo $order_product_untaken['order_product_id']; ?>" style="padding:10px!important;color:#FFF; background-color:#51a881;">
						<i class="fa fa-exclamation-triangle"></i> Продажа
					</td>
				</tr>
				<?php if ($i < count($order_products_untaken)) { ?> 
					<tr>
						<td colspan="4" style="height:5px;background-color:white!important;"></td>
					</tr>
				<?php } ?>
			<?php $i++; } ?>
			
		</table>
	<?php } ?>
	
	
	<table class='ptable' style='margin-top:5px; margin-bottom:10px;width:100%'>
		<thead style='background-color:#51a881; color: #fff; text-align: center;'>			
			<td>&#9972;</td>
			<td>Наименование</td>
			<td>шт</td>
			<td><?php echo $order_info['currency_code'] ?></td>
		</thead>
		
		<?php $i=1;	foreach ($order_products as $order_product){ ?>
			<tr>
				<? if ($current_delivery_num == $order_product['delivery_num']){ ?>
					<td class="td-decoration" style='font-size:12px; text-align:center;'><?php echo $order_product['delivery_num'] ?></td>
					<?php } else { ?>
					<td style='font-size:12px; text-align:center;'><?php echo $order_product['delivery_num'] ?></td>
				<?php } ?>
				<td style='font-size:12px; min-width: 220px;'>
					<?php echo $order_product['name'] ?>
					<br />
				</span>
				
				<span style="font-size:10px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:grey;"><?php echo $order_product['model']; ?></span>
				<span style="font-size:10px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#51a881;"><?php echo $order_product['product_id']; ?></span>
				<?php if ($order_product['ean']) { ?>
					<span style="font-size:10px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#7F00FF;"><?php echo $order_product['ean']; ?></span>
				<?php } ?>
			</td>
			
			
			<td style='text-align:center; font-size:12px;'><?php echo $order_product['quantity'] ?></td>
			
			<td style='white-space:nowrap; font-size:12px; text-align:right;'>
				<?php echo  $order_product['totalp'] ?>
				<?php if ($order_product['points_used_total_txt']) { ?>
					<br />
					<span style="display:inline-block; padding:3px; font-size:9px; color:#FFF; background-color:#cf4a61;">-<?php echo $order_product['points_used_total_txt'] ?></span>
				<?php } ?>	
			</td>
		</tr>
		<tr>
			<td colspan="2" style="background-color:white!important;  color:grey; font-size:9px!important; <?php if ($i < count($order_products)) { ?>  border-bottom:0px; <?php } ?>">
				
				<i class="fa fa-info-circle"></i> если клиент отказывается от товара, нажми "отказ" чтоб пересчитать сумму к оплате)
				
				<? if ($order_product['ao_text']) { ?>
					<br />
					<?php if ($order_product['order_has_main_ao']) { ?>
						<span style="color:#51a881"><i class="fa fa-info-circle"></i> <? echo $order_product['ao_text'];  ?>,  основной товар есть в заказе</span>
						<?php } else { ?>
						<span style="color:#ff5656"><i class="fa fa-exclamation-triangle"></i><? echo $order_product['ao_text'];  ?>, основного товара нет, данный товар тоже нужно убрать из заказа!</span>
					<?php } ?>
					
				<? } ?>
				
			</td>
			<td colspan="2" class="move-to-untaken" data-order-id="<?php echo $order_product['order_id']; ?>" data-order-product-id="<?php echo $order_product['order_product_id']; ?>" style="padding:10px!important;color:#FFF; background-color:#ff5656;">
				<i class="fa fa-exclamation-triangle"></i> Отказ
			</td>
		</tr>
		<?php if ($i < count($order_products)) { ?> 
			<tr>
				<td colspan="4" style="height:5px;background-color:white!important; border-bottom:0px; border-top:0px;"></td>
			</tr>
		<?php } ?>
	<?php $i++; } ?>
	
</table>
<table class="total" style='margin-top:10px; margin-bottom:10px;width:100%'>
	<?php	foreach ($totals as $total){ ?>
		<tr>
			<td style='text-align:right; padding-right:20px; font-size:14px;'><?php echo $total['title'] ?></td>
			<td style='text-align:right; font-weight: 600; font-size:14px; white-space:nowrap;'><?php echo $total['text'] ?></td>
		</tr>				
	<?php } ?>
	<tr>
		<td style='text-align:right; padding-right:20px; font-size:14px; color:red;'>На счету клиента</td>
		<td style='text-align:right; font-weight: 600; font-size:14px; white-space:nowrap; color:red;'><?php echo $order_transactions_total ?> <?php echo $order_info['currency_code'] ?></td>
	</tr>
</table>	


<?php if (!$order_is_paid) { ?>
	<div style='width:100%;height:1px;border-bottom:1px solid grey;margin-bottom: 15px;margin-top: 15px;'></div>
	
	<?php if ($payments) { ?>
	<div class="click-load-order-id button" style="background-color:#e16a5d; width:100%; text-align:center; padding-top:15px; padding-bottom:15px;margin:0px;margin-bottom:10px; font-size:16px;"><i class="fa fa-exclamation-triangle"></i> Заказ пока не оплачен</a></div>
	
	<?php foreach ($payments as $payment) { ?>
		<table style="width:100%; margin-bottom:10px;" width="100%">
			
			<tr>
				<td colspan="2" style="padding:10px; font-size:16px; border:1px solid #7F00FF; background-color:#7F00FF; color:#fff;" <?php if ($payment['collapse']) { ?>onclick="$('#collapsed-tr-<?php echo md5($payment['code']); ?>').toggle();"<?php } ?>>
					<?php echo $payment['title']; ?><?php if ($payment['collapse']) { ?><span style="float:right;"><i class="fa fa-plus"></i></span><?php } ?>
				</td>
			</tr>
			<tr <?php if ($payment['collapse']) { ?>id="collapsed-tr-<?php echo md5($payment['code']); ?>" style="display:none;"<?php } ?>>
				<td style="padding-bottom:10px;  border:1px solid #7F00FF; border-right:0px;">
					<img сlass="qr_code_img" onclick="openImg(this); return false;" src="<?php echo $payment['qr_image']; ?>" style="height:80px;" />
				</td>	
				<td style="padding-bottom:10px; border:1px solid #7F00FF; border-left:0px;">												
					<input type="hidden" id="code-<?php echo md5($payment['code']); ?>" value="<?php echo $payment['code']; ?>" />						
					<span id="<?php echo md5($payment['code']); ?>" onclick="copytext('#<?php echo md5($payment['code']); ?>')"><?php echo $payment['qr_link']; ?></span>
					<span class="btn-copy" onclick="copytext('#<?php echo md5($payment['code']); ?>')" title="скопировать ссылку">
						<span class="tooltiptext" style="display: none;">ссылка скопирована</span>
					</span>
					<br />
					<div style="display:none; width:100%; margin-top:10px;" id="div-telephone-<?php echo md5($payment['code']); ?>">
						<input class="input-telephone" type="tel" id="telephone-<?php echo md5($payment['code']); ?>" value="<?php echo $order_info['telephone'] ?>" />
						
						&nbsp;<span id="clear-telephone-<?php echo md5($payment['code']); ?>" onclick="$('#telephone-<?php echo md5($payment['code']); ?>').val('')" style="font-size:24px; color:red">
							<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
						</span>
					</div>
					<a class="click-load-order-id button send-button" style="background-color:#999999; margin-left:0px;" id="open-phone-<?php echo md5($payment['code']); ?>" onclick="openPhone('<?php echo md5($payment['code']); ?>')">Отправить клиенту</a>
					
					<a class="click-load-order-id button send-button" style="display:none; background-color:#51a881; margin-left:0px;" id="send-phone-<?php echo md5($payment['code']); ?>" onclick="sendPhone('<?php echo md5($payment['code']); ?>')"><i class="fa fa-paper-plane"></i> Отправить клиенту</a>
				</td>
			</tr>
		</table>
	<?php } ?>
	
	
	
	
	<script>
		function sendPhone(id){
			if ($('#telephone-' + id).val() != ''){
				$.ajax({
					url	: ' <?php echo $sendPaymentLinkToCustomer; ?>',
					type: 'POST',
					data : {									
						telephone : $('#telephone-' + id).val(),
						code: 		$('#code-' + id).val(),
						order_id: 	<?php echo $order_info['order_id']; ?>
					},
					dataType : 'text',
					beforeSend: function(){
						$('#send-phone-' + id + ' i').removeClass('fa-paper-plane').addClass('fa-spinner fa-spin');
						$('#send-phone-' + id).css({'background-color':'#ff5656'});
						$('#send-phone-' + id).unbind('click');
						$('#send-phone-' + id).attr('onclick', '');
					},
					success : function(json){
						$('#send-phone-' + id + ' i').removeClass('fa-spinner fa-spin').addClass('fa-paper-plane');
						$('#send-phone-' + id).css({'background-color':'#999999'});
						$('#send-phone-' + id).text('Ссылка отправлена');
					},
					error : function(e){
						console.log(e);
					}						
				});	
				} else {
				$('#telephone-' + id).css({'border-color':'#e16a5d'});
			}
		}
		
		function openPhone(id){
			$('#div-telephone-' + id).show();					
			$('#open-phone-' + id).unbind('click');
			$('#open-phone-' + id).hide();
			setTimeout(function(){$('#send-phone-' + id).show(); $('#clear-telephone-' + id + ' i').removeClass('fa-spinner fa-spin').addClass('fa-times')}, 800);
			
		}
	</script>
	
<?php } ?>

<table style="width:100%; margin-bottom:10px;" width="100%" >
	<tr>
		<td style="padding:10px; background-color:#7F00FF; border:1px solid #7F00FF; color:#fff; font-size:16px;">Наличка</td>
	</tr>
	<tr>
		<td style="white-space:nowrap; border:1px solid #7F00FF; padding:10px;">
			<input id="payment" type="checkbox" name="payment" value="1" <?php if ($payment_must){ ?>checked="checked" onclick="$(this).attr(\'checked\', \'checked\');" <?php } ?>/>&nbsp;&nbsp;<input type="text" id="payment_amount" name="payment_amount" style="width:140px;" value="<?php echo (int)$order_info['total_national'] ?>" /> 
			<?php echo $order_info['currency_code'] ?>
		</td>
	</tr>
</table>

<table  class="cash_table" style="width:100%" width="100%">
	<tr>
		<td>
			<a class="click-load-order-id button" style="background-color:#ff5656; width:100%; padding-top:20px; padding-bottom:20px; font-size:16px; margin-left:0px;" id="reload_order"><i class="fa fa-refresh"></i> Проверить оплату</a>
			<script>
				$('#reload_order i').removeClass('fa-spinner fa-spin').addClass('fa-refresh');
				$('#reload_order').click(function(){
					$('#reload_order i').removeClass('fa-refresh').addClass('fa-spinner fa-spin');
					$('#order_info').load('<?php echo $getOrderAjaxUrl ?>&order_id='+$('#order_id').val(), function(){ 
						$('#reload_order i').removeClass('fa-spinner fa-spin').addClass('fa-refresh');
					});
				});
			</script>
			
		</td>
	</tr>
</table>

<?php } else { ?>
<div class="" style='width:100%; text-align:center; background-color:#51a881; color:#FFF; font-size:18px; padding:20px 10px;'><i class="fa fa-check-circle"></i> Заказ оплачен!</div>
<?php } ?>


	<script>
		console.log('Вешаю триггеры переноса товара');
		$('.move-to-untaken').click(function(){
			var elem = $(this);
			$.ajax({
				url	: ' <?php echo $moveToUnTaken; ?>',
				type: 'POST',
				data : {									
					order_product_id: elem.attr('data-order-product-id'),
					order_id: 	<?php echo $order_info['order_id']; ?>
				},
				beforeSend: function(){
					elem.prev().html('<i class="fa fa-spinner fa-spin"></i> Жди, пересчитываю');
					elem.remove();
				},
				success : function(){
					
					$.ajax({								
						url	: ' <?php echo $resaveOrderAjax; ?>',
						type: 'GET',
						success: function(){
							loadOrder();
						}
					});
					
				}
			});
			
		});
		
		$('.move-to-taken').click(function(){
			var elem = $(this);
			$.ajax({
				url	: ' <?php echo $moveToTaken; ?>',
				type: 'POST',
				data : {									
					order_product_id: elem.attr('data-order-product-id'),
					order_id: 	<?php echo $order_info['order_id']; ?>
				},
				beforeSend: function(){
					elem.prev().html('<i class="fa fa-spinner fa-spin"></i> Жди, пересчитываю');
					elem.remove();
				},
				success : function(){
					$.ajax({								
						url	: ' <?php echo $resaveOrderAjax; ?>',
						type: 'GET',
						success: function(){
							loadOrder();
						}
					});
				}
			});
			
		});
		
	</script>


<?php if ($order_info['closed']){ ?>
	
	<div style='width:100%; font-size:18px; margin-bottom:5px; margin-top:5px; color:red'>Заказ в статусе <?php echo $current_status ?> заблокирован для редактирования.</div>
	
	<?php	} else { ?>
	
	<div style='width:100%;height:1px;border-bottom:1px solid grey;margin-bottom: 15px;margin-top: 15px;'></div>
	<table class="status_table" style="width:100%" width="100%">
		<tr>
			<td>
				<select onchange="getStatusSMSTextAjax()" id="order_status_id" name="order_status_id" style="width:100%">
					
					<?php if ($is_moscow_courier) { ?>
						
						<?php foreach ($order_statuses as $order_status) { ?>
							<?php if ($order_status['order_status_id'] == $this->config->get('config_complete_status_id')) { ?>
								<option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
								<?php } else { ?>
								<option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
							<?php } ?>
						<?php } ?>
						
						<script>
							$('#notify').prop('checked', true);
							getStatusSMSTextAjax();
						</script>
						<?php } else { ?>
						
						<?php foreach ($order_statuses as $order_status) { ?>
							<?php if ($order_status['order_status_id'] == $order_info['order_status_id']) { ?>
								<option value="<?php echo $order_status['order_status_id'] ?>" selected="selected"><?php echo $order_status['name'] ?></option>
								<?php } else { ?>
								<option value="<?php echo $order_status['order_status_id'] ?>"><?php echo $order_status['name'] ?></option>
							<?php } ?>
						<?php } ?>
						
					<?php } ?>
					
					
				</select>
			</td>
		</tr>
	</table>	
	<table class="additionally_table" style="width:100%" width="100%">	
		<tr class="content-inline">
			<td>Уведомление</td>
			<td>
				<input id="notify" type="checkbox" name="notify" value="1" />
			</td>
		</tr>
		
		<tr class="table_order_close" <?php if ($is_moscow_courier) { ?>style="display:none;"<?php } ?>>
			<td>ТТН (если отправка службой)</td>
			<td colspan=>
				<input type="text" name="ttn" id="ttn" length="50" style="width:100%;" value="" placeholder="ТТН" />
			</td>
		</tr>
		
		<tr class="table_order_close" <?php if ($is_moscow_courier) { ?>style="display:none;"<?php } ?>>
			<td>Дата дост./отпр.</td>
			<td>
				<input type="text" name="date_sent" id="date_sent" length="50" style="width:100%;" value="<?php echo $sent ?>" placeholder="Дата дост./отпр."/>
			</td>
		</tr>
		
		<tr class="table_order_close">
			<td>SMS:&nbsp;&nbsp;<span style='border-bottom:1px dashed black; cursor:pointer;' onclick=\"$('#sms').val('');\">очистить</span>
			</td>
			
			<td>
				<textarea id='sms' name='sms' style='width:100%; height:50px; margin-top:10px;' />
			</td>
		</tr>
		
		<tr class="table_order_close">
			<td>
				Комментарий:
			</td>
			<td>
				<textarea id='comment' name='comment' style='width:100%; height:50px; margin-top:10px;'/>
			</td>
		</tr>
	</table>
	<a class="button" id="close_order" style="">
		<i class="fa fa-check"></i> Закрыть заказ
	</a>
</form>	

<?php } ?>
<script>
	function sendOrder(){
		
		var gp = '';
		$('input[name=order_products]:checked').each(
		function(){
			gp+=$(this).val()+',';
		}
		);	
		
		console.log($('select#order_status_id').children('option:selected'));
		
		$.ajax({
			url: '<?php echo $updateOrderAjaxUrl; ?>',
			type: 'POST',
			data: {
				order_id : <?php echo $order_info['order_id'] ?>,
				telephone : '<?php echo $order_info['telephone'] ?>',
				store_id : <?php echo $order_info['store_id'] ?>,
				order_status_id : $('select#order_status_id').children('option:selected').val(),
				shipping_code : '<?php echo $order_info['shipping_code'] ?>',
				ttn : $('#ttn').val(),
				date_sent : $('#date_sent').val(),
				comment : $('#comment').val(),
				notify : $('#notify:checked').val(),
				payment : $('#payment:checked').val(),
				payment_amount : $('#payment_amount').val(),
				sms : $('#sms').val(),				
				customer_id : $('#customer_id').val(),
				taken_products : gp,
			},
			beforeSend: function(){				
				$('#result').html('');
				$('#close_order').css({'background-color':'#999999'});
				$('#close_order i').removeClass('fa-check').addClass('fa-spinner fa-spin');							
			},
			success: function(e){
				$('#order_info').load('<?php echo $getOrderAjaxUrl ?>&order_id='+$('#order_id').val(), function(){});
			},
			error: function(e){						
				$('#result').html('Error: '+e);								
			}
		});
	}
	
	$('#close_order').click(function(){
		<?php if ($order_is_paid) { ?>
			sendOrder();
			<?php } else { ?>
			if ($('#payment:checked').val() && !$('#payment_amount').val()){
				$('#result').html('<span style=\'color:red\'>Ошибка: необходимо ввести сумму наличных при способе оплаты при доставке!</span>');
				} else {
				sendOrder();
			}
		<?php } ?>
	});
</script>
<script>	
	$('input[name=\'ttn\']').keyup(function(){
		getSMSTextAjax();
		$('#notify').prop('checked', true);
	});
	
	$('input[name=\'date_sent\'],input[name=\'ttn\']').change(function(){
		getSMSTextAjax();					
	});
	</script>												