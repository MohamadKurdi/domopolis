<style>
	.return_reason_span, .return_action_span, .return_status_span, .return_was_opened_span, .to_supplier_span, .return_reorder_span {
	padding:4px;
	display:inline-block;
	cursor:pointer;
	border-bottom:1px dashed black;
	}		
	
	.return_reason_span.active, .return_action_span.active, .return_status_span.active, .return_was_opened_span.active, .return_reorder_span.active {
	color:white;
	border-bottom:1px dashed white;
	background-color:#85B200;
	}
	
	.to_supplier_span.active {
	color:white;
	border-bottom:1px dashed white;
	background-color:#F96E64;
	}
</style>
<script>
	$('#return_button').click(
	function(){		
		$.ajax({
			url : 'index.php?route=sale/order/add_return&token=<? echo $token; ?>',
			type: 'POST',
			dataType : 'html',
			data : {
				'order_id' : '<? echo $order['order_id']; ?>',
				'product_id' : '<? echo $real_product['product_id']; ?>',
				'order_product_id' : '<? echo $order_product_id; ?>',
				'customer_id' : '<? echo $order['customer_id']; ?>',
				'firstname' : '<? echo trim(str_replace("'", '`', $order['firstname'])); ?>',
				'lastname' : '<? echo trim(str_replace("'", '`', $order['lastname'])); ?>',
				'email' : '<? echo $order['email']; ?>',
				'telephone' : '<? echo $order['telephone']; ?>',
				'product' : '<? echo $real_product['name']; ?>',
				'model' : '<? echo $real_product['model']; ?>',
				'max_quantity' : '<? echo $quantity; ?>',
				'quantity' : parseInt($('#return_quantity').val()),
				'price' : '<? echo $order_product['price']; ?>',
				'price_national' : '<? echo $order_product['price_national']; ?>',
				'pricewd_national' : '<? echo $order_product['pricewd_national']; ?>',			
				'total' : parseFloat(<? echo $order_product['price']; ?>) * parseInt($('#return_quantity').val()),
				'total_national' : $('#return_total').val(),
				'opened' : $('#return_was_opened').val(),
				'return_reason_id' : $('#return_reason_id').val(),
				'return_action_id' : $('#return_action_id').val(),
				'return_status_id' : $('#return_status_id').val(),
				'return_reorder' : $('#return_reorder').val(),
				'to_supplier' : $('#to_supplier').val(),
				'comment' : $('#return_comment').val(),
				'date_ordered' : '<? echo date('Y-m-d H:i:s', strtotime($order['date_added'])); ?>'				
			},
			success : function(html){
				/*
					if (parseInt($('#return_quantity').val()) == <? echo $quantity; ?>){
					$('#product-row<? echo $index; ?>').remove();
					} else {
					$('#quantity_<? echo $index; ?>').val(parseInt($('#quantity_<? echo $index; ?>').val()) - parseInt($('#return_quantity').val()));
					$('#total_national_<? echo $index; ?>').val(parseInt($('#quantity_<? echo $index; ?>').val()) * parseInt($('#price_national_<? echo $index; ?>').val()));
					recount_totals();
					}
				*/
				swal({ title:'Возврат оформлен!', text : "Нажмите ОК и подождите, пока будет произведен перерасчет!", type: "success", buttons: true}, function(){ $('#return_dialog').hide(); window.location.reload(); });
			},
			error : function(error){
				console.log(e);
			}			
		});		
	});
	
	$('.to_supplier_span').click(function(){
		$('.to_supplier_span').removeClass('active');
		$('#to_supplier').val($(this).attr('data-value'));
		$(this).addClass('active');
	});
	
	$('.return_was_opened_span').click(function(){
		$('.return_was_opened_span').removeClass('active');
		$('#return_was_opened').val($(this).attr('data-value'));
		$(this).addClass('active');
	});
	
	$('.return_reorder_span').click(function(){
		$('.return_reorder_span').removeClass('active');
		$('#return_reorder').val($(this).attr('data-value'));
		$(this).addClass('active');
	});
	
	$('.return_reason_span').click(function(){
		$('.return_reason_span').removeClass('active');
		$('#return_reason_id').val($(this).attr('data-value'));
		$(this).addClass('active');
	});
	
	$('.return_action_span').click(function(){
		$('.return_action_span').removeClass('active');
		$('#return_action_id').val($(this).attr('data-value'));
		$(this).addClass('active');
	});
	
	$('.return_status_span').click(function(){
		$('.return_status_span').removeClass('active');
		$('#return_status_id').val($(this).attr('data-value'));
		$(this).addClass('active');
	});
	
	$('#return_quantity').keyup(function(){ 
		$('#return_quantity_show').text(parseInt($(this).val())); 
		$('#return_total').val(parseInt($(this).val()) * <? echo $order_product['price_national']; ?>);
	});
</script>
<table class="form" style="width:100%">
	<tr>
		<td class="left" style="width:1px; word-wrap:none; white-space: nowrap;">
			<b>Покупатель</b> 
		</td>
		<td>
			<? echo $order['firstname']; ?> <? echo $order['lastname']; ?> (id: <? echo $order['customer_id']; ?>)
		</td>
	</tr>
	<tr>
		<td class="left" style="width:1px; word-wrap:none; white-space: nowrap;">
			<b>Товар, кол-во</b> 
		</td>
		<td>
			<input name="return_quantity" id="return_quantity" value="<? echo $quantity; ?>" style="width:50px;" /> x <? echo $real_product['name']; ?>
			<span class="help">В поле ввода при открытии указано максимальное количество товаров на возврат: <b><? echo $quantity; ?> шт.</b></span>
		</td>
	</tr>
	<tr>
		<td class="left" style="width:1px; word-wrap:none; white-space: nowrap;">
			<b>Цена товара, итого</b> 
		</td>
		<td>
			<span id="return_quantity_show"><? echo $quantity; ?></span> * <? echo $order_product['price_national']; ?> = <input name="return_total" id="return_total" value="<? echo $order_product['total_national']; ?>" /><b><? echo $order['currency_code']; ?></b>
			<span class="help">Сумма в поле ввода ИТОГО, в случае возврата на счет, и статуса "выполнен", будет положена на внутренний счет покупателя.</span>
		</td>
	</tr>
	<tr>
		<td class="left" style="width:1px; word-wrap:none; white-space: nowrap;">
			<b>Тип возврата</b> 
		</td>
		<td>
			<input type="hidden" name="to_supplier" id="to_supplier" value="0" />
			<span class="to_supplier_span active" data-value='0'>Возврат от клиента</span>&nbsp;&nbsp;&nbsp;
			<span class="to_supplier_span" data-value='1'>Возврат поставщику</span>&nbsp;&nbsp;&nbsp;
			<span class="to_supplier_span" data-value='2'>Отказ после подтверждения</span>
		</td>
		<tr>
			<td class="left" style="width:1px; word-wrap:none; white-space: nowrap;">
				<b>Причина возврата</b> 
			</td>
			<td>
				<input type="hidden" name="return_reason_id" id="return_reason_id" value="1" />
				<?php foreach ($return_reasons as $return_reason) { ?>
					<? if ($return_reason['return_reason_id'] == 1) { ?>
						<span class="return_reason_span active" data-value='<?php echo $return_reason['return_reason_id']; ?>'><?php echo $return_reason['name']; ?></span>&nbsp;&nbsp;&nbsp;
						<?} else { ?>
						<span class="return_reason_span" data-value='<?php echo $return_reason['return_reason_id']; ?>'><?php echo $return_reason['name']; ?></span>&nbsp;&nbsp;&nbsp;
					<? } ?>
				<? } ?>
				<span class="help">Почему покупатель хочет вернуть товар? Если причины нет в списке, выбери "Другое" и опиши в поле комментария</span>
			</td>
		</tr>
		<tr>
			<td class="left" style="width:1px; word-wrap:none; white-space: nowrap;">
				<b>Действие по возврату</b> 
			</td>
			<td>
				<input type="hidden" name="return_action_id" id="return_action_id" value="1" />
				<?php foreach ($return_actions as $return_action) { ?>
					<? if ($return_action['return_action_id'] == 1) { ?>
						<span class="return_action_span active" data-value='<?php echo $return_action['return_action_id']; ?>'><?php echo $return_action['name']; ?></span>&nbsp;&nbsp;&nbsp;
						<?} else { ?>
						<span class="return_action_span" data-value='<?php echo $return_action['return_action_id']; ?>'><?php echo $return_action['name']; ?></span>&nbsp;&nbsp;&nbsp;
					<? } ?>
				<? } ?>
				<span class="help">Какие действия были приняты по возврату товара.</span>
			</td>
		</tr>
		<tr>
			<td class="left" style="width:1px; word-wrap:none; white-space: nowrap;">
				<b>Перезаказ</b> 
			</td>
			<td>
				<input type="hidden" name="return_reorder" id="return_reorder" value="0" />
				<span class="return_reorder_span" data-value='1'>Да</span>&nbsp;&nbsp;&nbsp;<span class="return_reorder_span active" data-value='0'>Нет</span>
				<span class="help">Необходимо перезакупить товар. Будет оформлен новый заказ на покупателя с статусом "в обработке"</span>
			</td>
		</tr>
		<tr>
			<td class="left" style="width:1px; word-wrap:none; white-space: nowrap;">
				<b>Статус возврата</b> 
			</td>
			<td>
				<input type="hidden" name="return_status_id" id="return_status_id" value="<? echo $this->config->get('config_return_status_id'); ?>" />
				<?php foreach ($return_statuses as $return_status) { ?>
					<? if ($return_status['return_status_id'] == $this->config->get('config_return_status_id')) { ?>
						<span class="return_status_span active" data-value='<?php echo $return_status['return_status_id']; ?>'><?php echo $return_status['name']; ?></span>&nbsp;&nbsp;&nbsp;
						<?} else { ?>
						<span class="return_status_span" data-value='<?php echo $return_status['return_status_id']; ?>'><?php echo $return_status['name']; ?></span>&nbsp;&nbsp;&nbsp;
					<? } ?>
				<? } ?>
			</td>
		</tr>
		<tr>
			<td class="left" style="width:1px; word-wrap:none; white-space: nowrap;">
				<b>Товар был распакован?</b> 
			</td>
			<td>
				<input type="hidden" name="return_was_opened" id="return_was_opened" value="1" />
				<span class="return_was_opened_span active" data-value='1'>Да</span>&nbsp;&nbsp;&nbsp;<span class="return_was_opened_span" data-value='0'>Нет</span>
			</td>
		</tr>
		<tr>
			<td class="left" style="width:1px; word-wrap:none; white-space: nowrap;">
				<b>Комментарий</b> 
			</td>
			<td>
				<textarea name="return_comment" id="return_comment" style="width:100%;" rows='3'></textarea>
			</td>
		</tr>
	</table>
	<div class="buttons" style="float:right;">
		<a class="button redbg" style="color:#FFF" id="return_button_toggle" onclick="$('#return_button').show();">Я все проверил!</a>
		<a class="button" id="return_button" style="color:#FFF;display:none;">Оформить возврат</a>
	</div>
