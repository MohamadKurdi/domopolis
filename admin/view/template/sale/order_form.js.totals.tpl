<script>

	$('#add_custom_reward').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="reward" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Бонусные баллы ('+$('#custom_reward').val()+')" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="-' + $('#custom_reward').val() + '" style="width:200px; border: 1px solid #cf4a61;" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="3" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});

	
	$('#add_custom_coupon').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="coupon" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Промокод ('+$('#custom_coupon').val()+')" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="0" style="width:200px; border: 1px solid #cf4a61;" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="2" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	
	var row = <?php echo $total_row; ?>;
	$('#add_custom').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="custom'+row+'"" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Новое поле" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="" style="width:200px" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="5" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	$('#add_delivery').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="shipping" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="'+$('input[name=\'shipping_method\']').val()+'" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="" style="width:200px" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="3" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	$('#add_sub_total').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="sub_total" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Сумма" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="" style="width:200px" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="1" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	$('#add_total_total').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="total" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Итого" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="" style="width:200px" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="10" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	$('#add_prepay5').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="transfer_plus_prepayment" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Из них предоплата (5%)" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="'+count_prepay(5)+'" style="width:200px" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="9" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	$('#add_prepay10').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="transfer_plus_prepayment" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Из них предоплата (10%)" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="'+count_prepay(10)+'" style="width:200px" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="9" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	
	$('#add_prepay20').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="transfer_plus_prepayment" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Из них предоплата (20%)" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="'+count_prepay(20)+'" style="width:200px" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="9" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	$('#add_custom_prepay').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="transfer_plus_prepayment" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Из них предоплата ('+$('#custom_prepay').val()+'%)" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="'+count_prepay($('#custom_prepay').val())+'" style="width:200px" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="9" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	$('#add_custom_discount3').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="custom_discount'+row+'" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Скидка 3%" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="-'+count_discount(3)+'" style="width:200px; border: 1px solid #cf4a61;" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="5" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	$('#add_custom_discount5').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="custom_discount'+row+'" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Скидка 5%" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="-'+count_discount(5)+'" style="width:200px; border: 1px solid #cf4a61;" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="5" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	$('#add_custom_discount7').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="custom_discount'+row+'" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Скидка 7%" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="-'+count_discount(7)+'" style="width:200px; border: 1px solid #cf4a61;" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="5" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	$('#add_custom_discount10').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="custom_discount'+row+'" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Скидка 10%" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="-'+count_discount(10)+'" style="width:200px; border: 1px solid #cf4a61;" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="5" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	$('#add_custom_discount').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="custom_discount'+row+'" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Скидка '+$('#custom_discount').val()+'%" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="-'+count_discount($('#custom_discount').val())+'" style="width:200px; border: 1px solid #cf4a61;" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="5" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
	$('#add_custom_discount2').on('click',function(){
		
		var html = '<tr id="total-row'+row+'">';
		html += '<td class="right" colspan="4">';
		html += '<img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#total-row'+row+'\').remove();" />&nbsp;&nbsp;';
		html += '<input type="hidden" name="order_total['+row+'][code]" value="custom_discount'+row+'" />';
		html += '<input type="hidden" name="order_total['+row+'][value]" value="" />';
		html += '<input type="text" name="order_total['+row+'][title]" value="Скидка '+$('#custom_discount2').val()+'" style="width:300px;" /> : ';
		html += '<input type="text" name="order_total['+row+'][value_national]" value="-'+$('#custom_discount2').val()+'" style="width:200px; border: 1px solid #cf4a61;" /> <? echo $currency_code; ?> ';
		html += '<input type="text" name="order_total['+row+'][sort_order]" value="5" style="width:15px;" />';
		html += '</td>';
		html += '<td class="right"><b><input type="text" name="order_total['+row+'][text]" value="" style="width:150px; font-weight:700;" /></b></td>'
		html += '</tr>';
		
		$('#total-row<?php echo $total_row - 1; ?>').before(html);
		
		row++;
	});
	
</script>