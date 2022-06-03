<? if ($is_buyer) { ?>
	<script>
		$(document).ready(function(){
			$('td.good_td').click(function(){
				$(this).children('span').load('<? echo str_replace('&amp;','&',$goodproduct_link); ?>'+'&order_product_id='+$(this).attr('data-id'),
				function(){
					document.location.reload();
				});
				
			});
			
			$('input.nogood_waitlist').change(function(){
				var _opid = $(this).attr('data-order-product-id');
				var _wlst = $(this).is(':checked')?1:0;
				var _el = $(this);
				$.ajax({
					type : 'GET',
					dataType : 'text',
					url : 'index.php?route=sale/order/setNoGoodProductWaitList&token=<? echo $token; ?>&order_product_id='+_opid+'&waitlist='+_wlst,
					success : function(i){
						if (parseInt(i) == 1){
							swal("ОК",'Товар включен в листе ожидания',"success");
							_el.next('label').children('i').css('color','#4ea24e');
							} else if(parseInt(i) == 0) {
							swal("ОК",'Товар снят с листа ожидания',"success");
							_el.next('label').children('i').css('color','#cf4a61');
							} else {
							swal("Ошибка!",'Что-то пошло не так',"error");
						}
					},
					error : function(text){
						swal("Ошибка!",'Что-то пошло не так',"error");
						console.log(text);
					}																												
				});
			});
			
			$('input.nogood_onsite, input.good_onsite').change(function(){
				var _opid = $(this).attr('data-order-product-id');
				var _isgood = $(this).attr('data-is-good-product');
				var _onst = $(this).is(':checked')?1:0;
				var _el = $(this);
				$.ajax({
					type : 'GET',
					dataType : 'text',
					url : 'index.php?route=sale/order/setProductIsAvailableOnSite&token=<? echo $token; ?>&order_product_id='+_opid+'&onsite='+_onst+'&isgood='+_isgood,
					success : function(i){
						if (parseInt(i) == 1){
							swal("ОК",'Товар можно купить на сайте:(',"success");
							_el.next('label').children('i').css('color','#4ea24e');
							} else if(parseInt(i) == 0) {
							swal("ОК",'Товар нельзя купить на сайте:(',"warning");
							_el.next('label').children('i').css('color','#cf4a61');
							} else {
							swal("Ошибка!",'Что-то пошло не так',"error");
						}
					},
					error : function(text){
						swal("Ошибка!",'Что-то пошло не так',"error");
						console.log(text);
					}																												
				});
			});
			
			$('td.taken_td').click(function(){
				$(this).children('span').load('<? echo str_replace('&amp;','&',$takenproduct_link); ?>'+'&order_product_id='+$(this).attr('data-id'),
				function(){
					document.location.reload();
				});
			});
		})
	</script>
	<script>
		$(document).ready(function(){
			
			function hangsupplyautocomplete(){
				
				$('.order_order_set_supply_supplier').autocomplete({
					delay: 500,
					source: function(request, response) {
						$.ajax({
							url: 'index.php?route=sale/supplier/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
							dataType: 'json',
							success: function(json) {
								json.unshift({
									'supplier_id':  0,
									'supplier_name':  'Не выбрано'
								});
								
								response($.map(json, function(item) {
									return {
										label: item.supplier_name,
										value: item.supplier_id
									}
								}));
							}
						});
					},
					select: function(event, ui) {
						$('input#set_supply_supplier_autocomplete_'+ $(this).attr('data-order-set-id') +'_'+ $(this).attr('data-supply-row')).val(ui.item.label);
						$('input#set_supply_supplier_id_'+ $(this).attr('data-order-set-id') +'_'+ $(this).attr('data-supply-row')).val(ui.item.value);
						
						return false;
					},
					focus: function(event, ui) {
						return false;
					}
				});
				
				$('.order_product_supply_supplier').autocomplete({
					delay: 500,
					source: function(request, response) {
						$.ajax({
							url: 'index.php?route=sale/supplier/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
							dataType: 'json',
							success: function(json) {
								json.unshift({
									'supplier_id':  0,
									'supplier_name':  'Не выбрано'
								});
								
								response($.map(json, function(item) {
									return {
										label: item.supplier_name,
										value: item.supplier_id
									}
								}));
							}
						});
					},
					select: function(event, ui) {
						
						//console.log('input#supply_supplier_autocomplete_'+ $(this).attr('data-order-product-id') +'_'+ $(this).attr('data-supply-row'));
						
						$('input#supply_supplier_autocomplete_'+ $(this).attr('data-order-product-id') +'_'+ $(this).attr('data-supply-row')).val(ui.item.label);
						$('input#supply_supplier_id_'+ $(this).attr('data-order-product-id') +'_'+ $(this).attr('data-supply-row')).val(ui.item.value);
						
						return false;
					},
					focus: function(event, ui) {
						return false;
					}
				});
			}
			
			var supply_row = <? echo !empty($supply_row)?$supply_row:0; ?>;
			var set_supply_row = <? echo !empty($set_supply_row)?$set_supply_row:0; ?>;
			
			$('.do-add-set-supply').click(function(){
				
				var _sopid = $(this).attr('data-order-set-id');
				var _gepid = $(this).attr('data-product-id');
				var _setid = $(this).attr('data-set-id');
				
				var html = '<tr>';
				html += '<input type="hidden" name="order_set_supply['+_sopid+'][product_id]"  value="'+_gepid+'" />';
				html += '<input type="hidden" name="order_set_supply['+_sopid+'][order_set_id]"  value="'+_sopid+'" />';
				html += '<input type="hidden" name="order_set_supply['+_sopid+'][set_id]"  value="'+_setid +'" />';
				html += '<td width="1" class="left">';
				html += '<img style="cursor:pointer;" src="view/image/delete.png" onclick=\'var _el = $(this); swal({ title: "Точно удалить запись о закупке?", text: "Это действие нельзя отменить!", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, удалить!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() { _el.parent().parent().remove(); });\' />';
				html += '</td>';
				html += '<td class="left">';
				html += '<input type="hidden" id="set_supply_supplier_id_'+_sopid+'_'+set_supply_row+'" name="order_set_supply['+_sopid+'][is_for_order][]"  value="0" />';
				html += '<input type="checkbox" onclick="var _el = $(\'#\set_supply_supplier_id_'+_sopid+'_'+set_supply_row+'\'); if (_el.val() ==1 ){ _el.val(\'0\') } else { _el.val(\'1\') }"/>';
				html += '</td>';
				html += '<td class="left">';
				html += '<input type="hidden" id="set_supply_supplier_id_'+_sopid+'_'+set_supply_row+'" name="order_set_supply['+_sopid+'][supplier_id][]"  value="" />';
				html += '<input type="text" id="set_supply_supplier_autocomplete_'+_sopid+'_'+set_supply_row+'"  class="order_order_set_supply_supplier" data-order-set-id="'+_sopid+'"  data-supply-row="'+set_supply_row+'" value="" style="width:300px;" />';
				html += '</td>';
				html += '<td class="left">';
				html += '<input type="text" name="order_set_supply['+_sopid+'][amount][]" value="" style="width:30px;" />';
				html += '</td>';	
				html +=	'<td class="left">';
				html +=	'<input type="text" name="order_set_supply['+_sopid+'][price][]" value="" style="width:100px;" />';
				html += '</td>';
				html += '<td>';
				html += '<select name="order_set_supply['+_sopid+'][currency][]">';
				<? foreach ($currencies as $_currency) { ?>
					html +='<option value="<? echo $_currency['code']; ?>"><? echo $_currency['code']; ?></option>';
				<? } ?>
				html += '</select>';
				html +=	'</td>';
				html += '<td>';
				html +=	'</td>';
				html += '<td>';
				html +=	'</td>';
				html += '<td>';
				html += '<textarea name="order_set_supply['+_sopid+'][url][]" rows="2" style="height:30px;"></textarea>';
				html +=	'</td>';
				html += '<td>';
				html += '<textarea name="order_set_supply['+_sopid+'][comment][]" rows="2" style="height:30px;"></textarea>';
				html +=	'</td>';				
				html += '</tr>';					
				
				$('#set_supply_table_'+_sopid+' tbody').append(html);			
				set_supply_row++;
				
				hangsupplyautocomplete();
			});
			
			$('.do-add-supply').click(function(){
				
				var _sopid = $(this).attr('data-order-product-id');
				var _opid = $(this).attr('data-product-id');
				
				var html = '<tr>';
				html += '<input type="hidden" name="order_product_supply['+_sopid+'][product_id]"  value="'+_opid+'" />';
				html += '<input type="hidden" name="order_product_supply['+_sopid+'][set_id]"  value="0" />';
				html += '<td width="1" class="left">';
				html += '<img style="cursor:pointer;" src="view/image/delete.png" onclick=\'var _el = $(this); swal({ title: "Точно удалить запись о закупке?", text: "Это действие нельзя отменить!", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, удалить!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() { _el.parent().parent().remove(); });\' />';
				html += '</td>';
				html += '<td class="left">';
				html += '<input type="hidden" id="supply_supplier_id_'+_sopid+'_'+supply_row+'" name="order_product_supply['+_sopid+'][is_for_order][]"  value="0" />';
				html += '<input type="checkbox" onclick="var _el = $(\'#\supply_supplier_id_'+_sopid+'_'+supply_row+'\'); if (_el.val() ==1 ){ _el.val(\'0\') } else { _el.val(\'1\') }"/>';
				html += '</td>';
				html += '<td class="left">';
				html += '<input type="hidden" id="supply_supplier_id_'+_sopid+'_'+supply_row+'" name="order_product_supply['+_sopid+'][supplier_id][]"  value="" />';
				html += '<input type="text" id="supply_supplier_autocomplete_'+_sopid+'_'+supply_row+'" class="order_product_supply_supplier" data-order-product-id="'+_sopid+'" data-supply-row="'+supply_row+'" value="" style="width:300px;" />';
				html += '</td>';
				html += '<td class="left">';
				html += '<input type="text" name="order_product_supply['+_sopid+'][amount][]" value="" style="width:30px;" />';
				html += '</td>';	
				html +=	'<td class="left">';
				html +=	'<input type="text" name="order_product_supply['+_sopid+'][price][]" value="" style="width:100px;" />';
				html += '</td>';
				html += '<td>';
				html += '<select name="order_product_supply['+_sopid+'][currency][]">';
				<? foreach ($currencies as $_currency) { ?>
					html +='<option value="<? echo $_currency['code']; ?>"><? echo $_currency['code']; ?></option>';
				<? } ?>
				html += '</select>';
				html +=	'</td>';
				html += '<td>';
				html +=	'</td>';
				html += '<td>';
				html +=	'</td>';
				html += '<td>';
				html += '<textarea name="order_product_supply['+_sopid+'][url][]" rows="2" style="height:30px;"></textarea>';
				html +=	'</td>';	
				html += '<td>';
				html += '<textarea name="order_product_supply['+_sopid+'][comment][]" rows="2" style="height:30px;"></textarea>';
				html +=	'</td>';			
				html += '</tr>';
				
				$('#supply_table_'+_sopid+' tbody').append(html);
				supply_row++;
				
				hangsupplyautocomplete();
			});
			
			hangsupplyautocomplete();
		});
	</script>
<? } ?>