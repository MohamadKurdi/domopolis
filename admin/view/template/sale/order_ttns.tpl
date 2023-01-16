<style>
	span.get_ttn_info {cursor:pointer; display:inline-block; border-bottom:1px dashed black;}
</style>
<table class="list">
	<thead>
		<tr>
			<td class="left">Дата</td>
			<td class="left">ТК</td>
			<td class="left">ТТН</td>
			<? if ($has_rights) { ?>	  
				<td class="left" width="1px"></td>
			<? } ?>
			<td class="left">SMS</td>	  	
		</tr>
	</thead>
	<tbody>
		<?php if ($ttns) { ?>
			<?php foreach ($ttns as $ttn) { ?>
				<tr data-ttn-id="<?php echo $ttn['order_ttn_id']; ?>">
					<td class="left"><?php echo $ttn['date_ttn']; ?></td>
					<td class="left">
						<? if ($has_rights) { ?>
							<?php echo $ttn['delivery_company']; ?> <br />
							<input type="text" name="ttn_code_<?php echo $ttn['order_ttn_id']; ?>" id="ttn_code_<?php echo $ttn['order_ttn_id']; ?>" value="<?php echo $ttn['delivery_code']; ?>" />
							<? if ($ttn['delivery_code_err']) { ?><br /><span class="help" style="color:red"><i class="fa fa-warning"></i> несовпадение!</span><? } ?>						
						<? } else { ?>
							<?php echo $ttn['delivery_company']; ?>
						<? } ?>
					</td>
					
					<? if ($has_rights) { ?>	  						
						<td class="left" id="tr_ttn_<?php echo $ttn['order_ttn_id']; ?>">							
							<input type="text" name="ttn_value_<?php echo $ttn['order_ttn_id']; ?>" id="ttn_value_<?php echo $ttn['order_ttn_id']; ?>" value="<?php echo $ttn['ttn']; ?>" />
							<span class="get_ttn_info" id="ttn_checker_<?php echo $ttn['order_ttn_id']; ?>" data-ttn="<?php echo $ttn['ttn']; ?>" data-delivery-code="<?php echo $ttn['delivery_code']; ?>"><i class="fa fa-info"></i></span>&nbsp;&nbsp;<span style="display:none;"></span></td>
						</td>
						<td class="left">							
							<a class="button a_change_ttn_info" data-ttn-id="<?php echo $ttn['order_ttn_id']; ?>">ИЗМ</a>
						</td>
						<? } else {  ?>
						<td class="left">	
						<span class="get_ttn_info" data-ttn="<?php echo $ttn['ttn']; ?>" data-delivery-code="<?php echo $ttn['delivery_code']; ?>"><?php echo $ttn['ttn']; ?></span>&nbsp;&nbsp;<span style="display:none;"></span></td>
					</td>
				<? } ?>
				<td class="left"><?php echo $ttn['sms_sent']; ?></td>
			</tr>
		<?php } ?>   
		<?php } else { ?>
		<tr>
			<td class="center" colspan="2">Нету истории ТТН</td>
		</tr>
	<?php } ?>
</tbody>
</table>
<script>
	$('.a_change_ttn_info').click(function(){
		var ttn_id = $(this).attr('data-ttn-id');
		$.ajax({
			url : 'index.php?route=sale/order/ttnupdate&token=<? echo $token ?>',
			type : 'POST',
			dataType: 'html',
			data : {
				ttn: $('#ttn_value_'+ttn_id).val(),				
				ttn_id: ttn_id,
				delivery_code: $('#ttn_code_'+ttn_id).val()
			},
			success : function(e){
				swal("ТТН изменена!", "Попробуйте проверить ее статус!", "success");
				$('#ttn_checker_'+ttn_id).attr('data-ttn', $('#ttn_value_'+ttn_id).val());
				$('#ttn_checker_'+ttn_id).attr('data-delivery-code', $('#ttn_code_'+ttn_id).val());
			}
		});
	});
	
	$('.get_ttn_info').click(function(){
		var span = $(this);
		span.next().html('<i class="fa fa-spinner fa-spin"></i>');
		span.next().show();
		var ttn = span.attr('data-ttn');
		var code = span.attr('data-delivery-code');
		var phone = $('input[name=telephone]').val();
		$('#ttninfo').load(
		'index.php?route=sale/order/ttninfoajax&token=<? echo $token ?>',
		{
			ttn : ttn,
			phone: phone,
			delivery_code : code
		}, 
		function(){
			span.next().hide();
			$(this).dialog({width:900, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: 'Информация по накладной '+ttn}); 
		});
	});</script>
	<div id="ttninfo"></div>	