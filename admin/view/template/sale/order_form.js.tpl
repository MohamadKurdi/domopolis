<style type="text/css">
	table.ordermain,table.history{width:100%;border-collapse:collapse;margin-bottom:20px}
	table.orderadress,table.orderproduct{width:48%;border-collapse:collapse;margin-bottom:20px}
	table.ordermain > tbody > tr > th,table.orderadress > tbody > tr > th,table.list > thead > tr > th,table.history > tbody > tr > th,table.form > tbody > tr > th{padding:5px 0; color: #FFF;font-size: 14px;font-weight: 400;margin-bottom:5px;}
	table.ordermain > tbody > tr > td{width:25%}
	table.ordermain > tbody > tr > td:nth-child(odd),table.orderadress > tbody > tr > td:nth-child(odd){background:#EFEFEF}
	table.ordermain > tbody > tr > td,table.orderadress > tbody > tr > td{padding:5px;color:#696969;border-bottom:1px dotted #CCC}
	.clr{clear:both}
	input[type="text"]{width:70%;}
	input.onfocusedit_direct, textarea.onfocusedit_direct, textarea.onfocusout_edit_history,  select.onchangeedit_direct, input.onchangeedit_direct, textarea.onfocusedit_source, textarea.onfocusedit_customer{border-left-color:#4ea24e;}
	
	input.onfocusedit_direct.done, textarea.onfocusedit_direct.done, textarea.onfocusout_edit_history.done, select.onchangeedit_direct.done, input.onchangeedit_direct.done, textarea.onfocusedit_source.done, textarea.onfocusedit_customer.done{border-color:#4ea24e;-webkit-transition : border 500ms ease-out;-moz-transition : border 500ms ease-out; -o-transition : border 500ms ease-out;transition : border 500ms ease-out;}
	
	input.onfocusedit_direct.done+span:after, textarea.onfocusedit_direct.done+span:after, textarea.onfocusout_edit_history.done+span:after, select.onchangeedit_direct.done+span:after, textarea.onfocusedit_source.done+span:after,.onchangeedit_orderproduct.done+label+span:after, textarea.onfocusedit_customer.done+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#4ea24e;content:"\f00c"}
	
	input.onfocusedit_direct.loading+span:after, textarea.onfocusedit_direct.loading+span:after, textarea.onfocusout_edit_history.loading+span:after, select.onchangeedit_direct.loading+span:after, textarea.onfocusedit_source.loading+span:after,.onchangeedit_orderproduct.loading+label+span:after,textarea.onfocusedit_customer.loading+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#e4c25a;content:"\f021"}
	
	input.onfocusedit_direct.error+span:after, textarea.onfocusedit_direct.error+span:after, textarea.onfocusout_edit_history.error+span:after, select.onchangeedit_direct.error+span:after, textarea.onfocusedit_source.error+span:after, .onchangeedit_orderproduct.error+label+span:after,textarea.onfocusedit_customer.error+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#cf4a61;content:"\f071"}
	
	.do_divide:hover{color:#cf4a61;}
	.blue_heading{text-align:center; padding:5px 0;cursor:pointer;color: #FFF;font-size: 14px;font-weight: 400;margin-bottom:5px;}
	.is_from_stock{font-size:10px; cursor:pointer; background-color:none; padding:3px 4px; display:inline-block;margin-top:3px;}
	.is_from_stock.active{background-color:#85B200; color:white; }		
</style>
<style>
	#nbt_customer{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px;}
	#nbt_customer.is_nbt{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px; color:white; background-color:#cf4a61;}
	
	#rja_customer{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px;}
	#rja_customer.is_nbt{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px; color:white; background-color:#cf4a61;}
</style>
<? $field_classes = array(
	'onfocusedit_direct',
	'onfocusout_edit_history',
	'onchangeedit_direct',
	'onfocusedit_source',
	'onfocusedit_customer'
); ?>
<script>
	function setChequeViewTriggers(){
		console.log('setChequeViewTriggers');
		
		$('.view-cheque').click(function(){
			var _this = $(this);
			$.ajax({
				url: 'index.php?route=sale/order/singleinvoice_log&token=<?php echo $token; ?>&id=' +  $(this).attr('data-order_invoice_id'),
				dataType: 'html',
				success : function(html){
					$('#mailpreview').html(html).dialog({title:'Чек от ' + _this.attr('data-order_invoice-datetime') + ', сформирован ' + _this.attr('data-order_invoice-author') , width:800, modal:true,resizable:true,  position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
				}
			})	
		});	
		
		$('.print-cheque').click(function(){
			window.open('index.php?route=sale/order/singleinvoice_log&token=<?php echo $token; ?>&id=' +  $(this).attr('data-order_invoice_id'), '_blank');			
		});	
		
		$('.pdf-cheque').click(function(){
			window.open('index.php?route=sale/order/invoicehistory2pdf&order_id=<? echo $order_id; ?>&token=<?php echo $token; ?>&id=' +  $(this).attr('data-order_invoice_id'), '_blank');			
		});	
		
		$('.pdf-cheque-a5').click(function(){
			window.open('index.php?route=sale/order/invoicehistory2pdf_a5&order_id=<? echo $order_id; ?>&token=<?php echo $token; ?>&id=' +  $(this).attr('data-order_invoice_id'), '_blank');			
		});
		$('.pdf-cheque-auto').click(function(){
			window.open('index.php?route=sale/order/invoicehistory2pdf_auto&order_id=<? echo $order_id; ?>&token=<?php echo $token; ?>&id=' +  $(this).attr('data-order_invoice_id'), '_blank');
		});	
	}
</script>
<script>
	function setHistoryEditTriggers() {
		console.log('setHistoryEditTriggers');
		$('body').on('click', 'i.change_courier_in_history', function(){
			var _el = $(this);
			var _field = $(this).attr('data-field-name');
			var _ohid = $(this).attr('data-order-history-id');
			var _val = $(this).attr('data-value');
			
			$.ajax({
				url : 'index.php?route=sale/order/updateOrderHistoryFieldsAjax&token=<? echo $token; ?>',
				type: 'POST',
				dataType : 'text',
				data : {					
					order_history_id : _ohid,
					field : _field,
					value : (1 - parseInt(_val)),
				},
				beforeSend : function(){					
				},
				success : function(text){
					_el.attr('data-value', parseInt(text));
					if (text == '1'){
						_el.css('color', '#4ea24e');
						} else {
						_el.css('color', '#696969');
					}
				},
				error : function(error){					
					console.log(error);
				}	
			});				
		});
		
		
		$('body').on('focusout', 'textarea.onfocusout_edit_history', function(){
			var _el = $(this);
			var _elspan = $(this).next('span');
			var _ellabel = $(this).next('label');
			var _ohid = $(this).attr('data-order-history-id');
			var _val = $(this).val();
			var _field = $(this).attr('data-field-name');
			
			$.ajax({
				url : 'index.php?route=sale/order/updateOrderHistoryFieldsAjax&token=<? echo $token; ?>',
				type: 'POST',
				dataType : 'text',
				data : {					
					order_history_id : _ohid,
					field : _field,
					value : _val,
				},
				beforeSend : function(){
					_el.removeClass('done').addClass('loading');
				},
				success : function(text){
					_el.removeClass('loading').addClass('done');				
				},
				error : function(error){
					_el.removeClass('loading').addClass('error');
					console.log(error);
				}	
			});		
		});
	}
	
	function setAjaxFieldsTriggers(){
		$('textarea.onfocusedit_source').focusout(function(){
			var _val = $(this).val();
			var _el = $(this);
			var _elspan = $(this).next('span');
			var _opid = $(this).attr('data-order-product-id');
			var _pid = $(this).attr('data-product-id');
			var _row = $(this).attr('data-row');
			var _resspan = $('span.sources_for_'+_pid);
			var _oid = '<? echo $order_id; ?>';
			
			
			$.ajax({
				url : 'index.php?route=sale/order/updateProductSourceFromOrderAjax&token=<? echo $token; ?>',
				type: 'POST',
				dataType : 'html',
				data : {
					source : _val,
					order_product_id : _opid,
					product_id : _pid,
					order_id : _oid,
					row : _row
				},
				beforeSend : function(){
					_el.removeClass('done').addClass('loading');
					_resspan.html('обновляю, подождите...');
				},
				success : function(html){
					console.log(html);
					_resspan.html(html);
					_el.removeClass('loading').addClass('done');
					
				},
				error : function(error){
					_el.removeClass('loading').addClass('error');
					console.log(error);
				}			
			});		
			
		});
		
		$('input.onfocusedit_direct, textarea.onfocusedit_direct').focusout(function(){
			var _val = $(this).val();
			var _el = $(this);
			var _elspan = $(this).next('span');
			var _field = $(this).attr('name');
			var _updc = $('#do_update_customer').is(':checked');
			var _oid = '<? echo $order_id; ?>';	
			var _cid = '<? echo $customer_id; ?>';
			
			$.ajax({
				url : 'index.php?route=sale/order/updateOrderFieldsAjax&token=<? echo $token; ?>',
				type: 'POST',
				dataType : 'text',
				data : {
					order_id : _oid,
					customer_id : _cid,
					field : _field,
					value : _val,
					update_customer : _updc
				},
				beforeSend : function(){
					_el.removeClass('done').addClass('loading');
				},
				success : function(text){
					_el.removeClass('loading').addClass('done');
				},
				error : function(error){
					_el.removeClass('loading').addClass('error');
					console.log(error);
				}			
			});		
		});
		
		
		$('body').on('change', 'select.onchangeedit_direct, input.onchangeedit_direct', function(){
			
			var _el = $(this);
			
			console.log(_el);
			
			var _elspan = $(this).next('span');
			var _ellabel = $(this).next('label');
			var _field = $(this).attr('name');
			var _updc = $('#do_update_customer').is(':checked');
			var _oid = '<? echo $order_id; ?>';	
			var _cid = '<? echo $customer_id; ?>';
			if (typeof(_el.attr("type")) != 'undefined' && _el.attr("type") == 'checkbox') {
				var _val = $(this).is(':checked')?1:0;
				} else {
				var _val = $(this).val();
			}
			
			$.ajax({
				url : 'index.php?route=sale/order/updateOrderFieldsAjax&token=<? echo $token; ?>',
				type: 'POST',
				dataType : 'text',
				data : {
					order_id : _oid,
					customer_id : _cid,
					field : _field,
					value : _val,
					update_customer : _updc
				},
				beforeSend : function(){
					_el.removeClass('done').addClass('loading');
				},
				success : function(text){
					_el.removeClass('loading').addClass('done');
					console.log(_el.attr('id'));
					
					if (_el.attr('id') == 'csi_reject'){
						if (_val == 1){
							$('#csi_table').hide();
							$('#csi_average_result_wrapper').hide();
							} else {							
							$('#csi_table').show();
							$('#csi_average_result_wrapper').show();
						}						
					}
					
					if (_el.attr('id') == 'legalperson_id'){
						$.ajax({
							url : 'index.php?route=localisation/legalperson/getLegalPersonAllLimitsAjax&token=<? echo $token; ?>',
							type : 'POST',
							data : {
								legalperson_id : _val,
								currency : '<? echo $currency_code; ?>'
							},
							dataType: 'json',
							success : function(json){
								$('#clp_at_this_moment').text(json.at_this_moment);
								$('#clp_total_already_paid').text(json.total_already_paid);
								$('#clp_total_need_to_pay').text(json.total_need_to_pay);
							}
						})						
					}
					
					
					if (_el.attr('id').indexOf('_mark')){
						$.ajax({
							url : 'index.php?route=sale/order/getOrderCSIAjax&order_id='+ _oid +'&token=<? echo $token; ?>',
							type : 'GET',
							dataType: 'text',
							success : function(text){
								$("#csi_average_result").rateYo("option", "rating", parseFloat(text));
								$("#csi_average_result_text").text(parseFloat(text));
							}
						})											
					}
					
					if (_el.attr('id') == 'select_pay_type' && _el.children('option:selected').attr('data-type') == 'cashless'){
						$('div#div_cashless_info').show();			
						} else if (_el.attr('id') == 'select_pay_type') {
						$('div#div_cashless_info').hide();			
					}
					
					if (_el.attr('id') == 'select_pay_type' && _el.children('option:selected').attr('data-type') == 'bank'){
						$('div#div_card_info').show();			
						} else if (_el.attr('id') == 'select_pay_type') {
						$('div#div_card_info').hide();			
					}
				},
				error : function(error){
					_el.removeClass('loading').addClass('error');
					console.log(error);
				}			
			});		
		});
		
		$('body').on('change', 'input.onchangeedit_customer', function(){
			
			var _val = $(this).val();
			var _el = $(this);
			var _elspan = $(this).next('span');
			var _field = $(this).attr('name');
			var _oid = '<? echo $order_id; ?>';	
			var _cid = '<? echo $customer_id; ?>';
			
			if (_el.attr("type") == 'checkbox') {
				var _val = $(this).is(':checked')?1:0;
				} else {
				var _val = $(this).val();
			}
			
			$.ajax({
				url : 'index.php?route=sale/customer/updateCustomerFieldsAjax&token=<? echo $token; ?>',
				type: 'POST',
				dataType : 'text',
				data : {
					order_id : _oid,
					customer_id : _cid,
					field : _field,
					value : _val,
				},
				beforeSend : function(){
					_el.removeClass('done').addClass('loading');
				},
				success : function(text){
					_el.removeClass('loading').addClass('done');				
				},
				error : function(error){
					_el.removeClass('loading').addClass('error');
					console.log(error);
				}			
			});		
		});
		
		
		$('textarea.onfocusedit_customer, input.onfocusedit_customer').on('focusout', function(){
			
			var _val = $(this).val();
			var _el = $(this);
			var _elspan = $(this).next('span');
			var _field = $(this).attr('name');
			var _oid = '<? echo $order_id; ?>';	
			var _cid = '<? echo $customer_id; ?>';
			
			if (_el.attr("type") == 'checkbox') {
				var _val = $(this).is(':checked')?1:0;
				} else {
				var _val = $(this).val();
			}
			
			$.ajax({
				url : 'index.php?route=sale/customer/updateCustomerFieldsAjax&token=<? echo $token; ?>',
				type: 'POST',
				dataType : 'text',
				data : {
					order_id : _oid,
					customer_id : _cid,
					field : _field,
					value : _val,
				},
				beforeSend : function(){
					_el.removeClass('done').addClass('loading');
				},
				success : function(text){
					_el.removeClass('loading').addClass('done');				
				},
				error : function(error){
					_el.removeClass('loading').addClass('error');
					console.log(error);
				}			
			});		
		});
		
		$('body').on('change', 'input.onchangeedit_orderproduct', function(){
			var _el = $(this);
			var _ellabel = $(this).next('label');
			var _elspan = $(this).next('span');
			var _field = $(this).attr('data-field-name');
			var _opid = $(this).attr('data-order-product-id');
			if (_el.attr("type") == 'checkbox') {
				var _val = $(this).is(':checked')?1:0;
				} else {
				var _val = $(this).val();
			}
			var _oid = '<? echo $order_id; ?>';	
			var _cid = '<? echo $customer_id; ?>';
			
			$.ajax({
				url : 'index.php?route=sale/order/updateOrderProductFieldsAjax&token=<? echo $token; ?>',
				type: 'POST',
				dataType : 'text',
				data : {
					order_id : _oid,
					customer_id : _cid,
					order_product_id : _opid,
					field : _field,
					value : _val,
				},
				beforeSend : function(){
					_el.removeClass('done').addClass('loading');
				},
				success : function(text){
					if (_el.hasClass('checkbox_from_stock')){
						if (text == '0'){
							_el.val('0');
							$('#'+_el.attr('data-fake')).val('0');
							} else {
							_el.val('1');
							$('#'+_el.attr('data-fake')).val('1');
						}
					}
					_el.removeClass('loading').addClass('done');
					if (_el.attr("type") == 'checkbox') {
						if (text == '0'){
							_ellabel.css('color', '#cf4a61');							
							} else {
							_ellabel.css('color', '#4ea24e');
						}
					}
				},
				error : function(error){
					_el.removeClass('loading').addClass('error');
					console.log(error);
				}			
			});		
		});
	}
</script>
<script>
	function disableSaveButtons(){
		$('.save_button').each(function(){
			$(this).attr('disabled', 'disabled');
			$(this).hide();
		});
	}
	
	function disableAllInputs(){
		$("#form input:not('.csi_input'),#form textarea:not('.csi_input'),#form button:not('.csi_input'), #form select:not('.csi_input')").each(function(){
			$(this).attr('disabled', 'disabled');
		});
	}
	
	function enableAllInputs(){
		$("#form input:not('.csi_input'),#form textarea:not('.csi_input'),#form button:not('.csi_input'), #form select:not('.csi_input')").each(function(){
			$(this).removeAttr('disabled');
		});
	}
	
	function enableSaveButtons(){
		$('.save_button').each(function(){
			$(this).removeAttr('disabled');
			$(this).show();
		});
	}
	
	function closeAjaxFields(){
		<? foreach ($field_classes as $_class) { ?>
			$(".<? echo $_class; ?>:not('.csi_input')").each(function(){
				$(this).removeClass('<? echo $_class; ?>').addClass('<? echo $_class; ?>_d');
			});
		<? } ?>
	}
	
	function openAjaxFields(){
		<? foreach ($field_classes as $_class) { ?>
			$(".<? echo $_class; ?>_d:not('.csi_input')").each(function(){
				$(this).removeClass('<? echo $_class; ?>_d').addClass('<? echo $_class; ?>');
			});
		<? } ?>
	}
</script>
<? if ($closed) { ?>
	<script>
		$(document).ready(function(){
			setAjaxFieldsTriggers();
			closeAjaxFields();
			disableSaveButtons();
			disableAllInputs();
		});
	</script>	
	<? } else { ?>
	<script>
		$(document).ready(function(){
			setAjaxFieldsTriggers();
			setHistoryEditTriggers();		
			enableAllInputs();		
		});
	</script>
<? } ?>
<? if ($this->user->canUnlockOrders()) { ?>
	<script>
		$(document).ready(function(){
			$("#order_lock").click(function(){
				var _el = $(this);
				$.ajax({
					url : 'index.php?route=sale/order/setOrderLockAjax&order_id=<? echo $order_id; ?>&token=<?php echo $token; ?>',
					type : 'GET',
					success : function(i){
						if (i == 1){
							_el.css('color', '#cf4a61');
							_el.children("i").removeClass('fa-unlock').addClass('fa-lock');
							closeAjaxFields();
							disableSaveButtons();
							disableAllInputs();
							} else {
							_el.css('color', '#4ea24e');
							_el.children("i").removeClass('fa-lock').addClass('fa-unlock');
							openAjaxFields();
							setAjaxFieldsTriggers();
							setHistoryEditTriggers();
							enableSaveButtons();
							enableAllInputs();
						}					
					},
					error : function(e){
						console.log(e);
					}
				});
			});
			
			$("#order_salary_paid").click(function(){
				var _el = $(this);
				$.ajax({
					url : 'index.php?route=sale/order/setOrderSalaryPaidAjax&order_id=<? echo $order_id; ?>&token=<?php echo $token; ?>',
					type : 'GET',
					success : function(i){
						if (i == 1){
							_el.css('color', '#4ea24e');						
							} else {
							_el.css('color', 'grey');						
						}					
					},
					error : function(e){
						console.log(e);
					}
				});
			});
			
		});
	</script>
<? } ?>
<script>
	<? $rateYoFields = array(			
		'csi_mark',
		'speed_mark',
		'manager_mark',
		'quality_mark',
		'courier_mark'
	); ?>
	
	$(document).ready(function(){
		$("#csi_average_result").rateYo({
			rating: <? echo (float)$csi_average; ?>,
			precision: 1,
			starWidth: "24px",
			readOnly: true,
			normalFill: "#ccc",
			multiColor: {																
				"startColor": "#cf4a61", //RED
				"endColor"  : "#4ea24e"  //GREEN
			}
		});
		<? foreach ($rateYoFields as $_rf) { ?>
			$("#<? echo $_rf; ?>_div").rateYo({			
				rating: <? echo (int)${$_rf}; ?>,
				precision: 0,
				starWidth: "24px",
				normalFill: "#ccc",
				<? if (!$can_edit_csi) { ?>
					readOnly: true,
				<? } ?>
				fullStar: true,
				onSet: function (rating, rateYoInstance) {
					$('input#<? echo $_rf; ?>').val(parseInt(rating)).trigger('change');
					$('#finish_csi_button').show();
				},
				multiColor: {																
					"startColor": "#cf4a61", //RED
					"endColor"  : "#4ea24e"  //GREEN
				}
			});
		<? } ?>
	});
</script>
<script>
	function spin(elm, vl) {
		if (elm.val() >= 0){
			elm.val(parseInt(elm.val(), 10) + vl);
		}
		if (elm.val() <= 0){
			elm.val('1');
		}
	}
	
	function setRateYo(){
		
		$(".rateYo").rateYo({
			precision: 1,
			starWidth: "14px",
			readOnly: true,
			normalFill: "#ccc",
			multiColor: {																
				"startColor": "#cf4a61", //RED
				"endColor"  : "#4ea24e"  //GREEN
			}
		});
		
	}
	
	function go_to_order(){
		if ($('#go_to_order').val().length >= 5){
			$.ajax({
				url : 'index.php?route=sale/order/if_order_exists&order_id='+parseInt($('#go_to_order').val())+'&token=<? echo $token ?>',
				type : 'GET',
				dataType : 'text',
				success : function(text){
					console.log(text);
					console.log(parseInt(text) == '0');
					if (parseInt(text) == '0'){
						swal("Ошибка!", "Такого заказа не существует!", "error");
						} else {								
						document.location.href = 'index.php?route=sale/order/update&order_id='+text+'&token=<? echo $token ?>';
					}								
				},
				error : function(e){
					console.log(e);
				}
			});																			
			} else {
			alert('Некорректный номер заказа');
		}
	}
	
	function put_to_reserve(order_product_id, country){
		var _q = $('#reserve_'+country+'_'+order_product_id+'_quantity').val();
		swal({ title: "Поставить на резерв?", text: "На складе "+country+", "+_q+' шт.', type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Резервировать", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() {
		});
		
	}
	
	function remove_from_reserve(order_product_id, country){
		var _q = $('#reserved_'+country+'_'+order_product_id+'_quantity').val();
		swal({ title: "Снять с резерва?", text: "Со склада "+country+", "+_q+' шт.', type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Снять", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() {
		});
		
	}
	
	function recount_totals(){
		var tq = 0;
		$('.quantity_change').each(function(){
			tq += parseInt($(this).val());
		});
		$('#quantity_total').text(tq);
		
		var tt = 0;
		$('.total_nochange').each(function(){
			tt += parseInt($(this).val());
		});

		$('#total_total').text(tt + ' ' + '<? echo $currency_code; ?>');
	}
	
	function divide_product(order_product_id){
		var _q = $('#divide_'+order_product_id+'_quantity').val();
		
		if (parseInt(_q) > 0){
			swal({ title: "Разделить товар построчно?", text: "Будет создана отдельная строка с "+_q+' шт. товара', type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Разделить", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() {
				$.ajax({
					url : 'index.php?route=sale/order/divideProduct&token=<?php echo $token; ?>',
					dataType: 'text',
					crossDomain: true,
					type : 'post',
					data : {
						order_product_id : order_product_id,
						quantity : _q,
					},
					success : function(text){
						console.log(text);
						window.location.reload();
					},
					error : function(text){
						swal("Ошибка!",'Что-то пошло не так',"error");
						console.log(text);
					}
				});																
			});
			
			} else {
			swal("Количество!",'Укажите количество для разделения товара построчно',"error");
		}
	}
	function get_cheque_name(part_num){
		var order_id = '<? echo $order_id; ?>';
		var delivery_num = parseInt(part_num);
		
		//trying to get partname
		var elem = $('*').find($("input.part_num[data-delivery-num='"+delivery_num+"']")[ 0 ]);
		
		//console.log(elem);
		
		if (elem.length > 0 && elem.val() != 'undefined' && elem.val().length > 0)	{
			
			if (delivery_num == 1){
				$('#cheque-num').val(elem.val() +' - '+ order_id);
				} else {
				$('#cheque-num').val(elem.val() +' - '+ order_id + '-' + parseInt(delivery_num - 1));
			}
			} else {
			$('#cheque-num').val(order_id);
		}
	}
	get_cheque_name(1);
	
	function cheque_add_row(){
		var html = "<tr class='tr_total_draggable' id='totals_cheque_"+Math.floor((Math.random() * 10000) + 30)+"'>";
		html += "<td colspan='6' align='left' style='cursor:move; white-space: nowrap; padding-left:5px;padding-right:5px;'>";
		html += '<span class="totals_cheque_row_delete" onclick="$(this).parent().parent().remove(); cheque_recount();"><img src="view/image/delete.png" /></span>';
		html += '<input style="text-align:left" type="text" class="invoice_total_input" value="Новый итог" />:';
		html += "</td>";
		html += "<td align='right' style='white-space: nowrap; padding-left:5px;padding-right:5px;'>";
		html += '<input type="text" class="invoice_total_sum_input" value="0,00" />';
		html += "</td>";
		html += "</tr>";
		
		var html = $('#preinvoice_table tfoot').html() + html
		
		$('#preinvoice_table tfoot').html(html);
		
		$('.invoice_total_sum_input').on('keyup', function(){
			cheque_recount();
		});
	}
	
	function cheque_recount(){
		
		var info = [];
		$('.invoice_total_sum_input').not('#cheque_total_total').each(function(){
			info.push($(this).val());
		});
		//console.log(info);
		//console.log($('.invoice_total_sum_input').not('#cheque_total_total'));
		
		$.ajax({
			url : 'index.php?route=sale/order/count_sum_cheque&token=<? echo $token ?>',
			type : 'POST',
			dataType: 'html',
			data : {
				sums: info,
				currency_code : "<? echo $currency_code; ?>"
			},
			success : function(e){
				$('#cheque_total_total').val(e.trim());
			}
			
		});
	}
	
	function setPaymentSMSInfo(tr_description, transaction_sms_text = false, eq_type = 0){
		$('#tr_description').val(tr_description); 
		$('#eq_type').val(eq_type); 
		
		if (!transaction_sms_text) {
			getTransactionSMSAjax();
		}
		
		if (transaction_sms_text){
			$('textarea[name=\'transaction_sms_text\']').val(transaction_sms_text);
		}
	}
	
	function getTransactionSMSAjax(){
		$.ajax({
			url	: 'index.php?route=sale/order/getTransactionSMSTextAjax&token=<?php echo $token; ?>',
			type: 'POST',
			data : {
				order_id : <? echo $order_id ?>,
				type : $('input[name=\'eq_type\']').val(),
				sum : $('input[name=\'tr_amount\']').val()
			},
			dataType : 'text',
			success : function(text){
				$('textarea[name=\'transaction_sms_text\']').val(text);
			},
			error : function(text){
				console.log(text);
			}
		});
	}
	
	function getCompleteOrderTextAjax(){
		$.ajax({
			url	: 'index.php?route=sale/order/getCompleteOrderTextAjax&token=<?php echo $token; ?>',
			type: 'POST',
			data : {
				order_id : <? echo $order_id ?>,
				order_status_id : $('select[name="order_status_id_tc"]').val(),
			},
			dataType : 'html',
			success : function(html){
				$('span#CompleteOrderTextAjax').text(html);
			},
			error : function(text){
				console.log(text);
			}
		});
	}
	
	function countSMSLength(){
		var length = $('#history_sms_text').val().length;
		$("#history_sms_text_count").text(length);
		
		if (length > 140){
			$('#history_sms_text_count_alert').text('Это очень много, более 3 смсок, укороти текст!');
			$('#button-history-toggler').hide();
			$('#button-history').attr('disabled', 'disabled');
			} else {
			$('#history_sms_text_count_alert').text('');
			$('#button-history-toggler').show();
			$('#button-history').removeAttr('disabled');
		}
	}
	
	$(document).ready(function(){
		countSMSLength();
	});
	
	function getStatusSMSTextAjax(){
		$.ajax({
			url	: 'index.php?route=sale/order/getStatusSMSTextAjax&token=<?php echo $token; ?>',
			type: 'POST',
			data : {
				order_id : <? echo $order_id ?>,
				order_status_id : $('select[name="order_status_id_tc"]').val(),
				comment : $('textarea[name=\'hcomment\']').val()
			},
			dataType : 'JSON',
			success : function(json){
				if (json.error){
					$('textarea[name=\'history_sms_text\']').before("<span class='error' id='span_sms_error'>"+json.error+"</span>");
					} else {
					$('#span_sms_error').remove();
				}
				$('textarea[name=\'history_sms_text\']').val(json.message);
				countSMSLength();
			},
			error : function(json){
				console.log(json);
			}
		});
	}
	
	function get_ttn_history(){
		$('#ttn_history').load('index.php?route=sale/order/ttnhistory&order_id=<? echo $order_id; ?>&token=<? echo $token; ?>');
	}
	function addTTNAjax(){
		$.ajax({
			url	: 'index.php?route=sale/order/addTTNAjax&token=<?php echo $token; ?>',
			type: 'POST',
			data : {
				order_id : <? echo $order_id ?>,
				date_sent : $('input[name=\'date_sent\']').val(),
				ttn : $('input[name=\'ttn\']').val(),
				delivery_sms_text : $('textarea[name=\'delivery_sms_text\']').val(),
				send_delivery_sms : $('input[name=\'send_delivery_sms\']').attr('checked') ? 1 : 0,
			},
			beforeSend: function() {
				$('.success, .warning').remove();
				$('#delivery_sms_send_button').attr('disabled', true);
				$('#ttn_history').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /><?php echo $text_wait; ?></div>');
			},
			success : function(text){
				console.log(text);
				$('.attention').remove();
				$('#delivery_sms_send_button').attr('disabled', false);
				get_ttn_history();
			},
			error : function(text){
				console.log(text);
			}
		})
	}
	
	function getSMSTextAjax(){
		$.ajax({
			url	: 'index.php?route=sale/order/getDeliverySMSTextAjax&token=<?php echo $token; ?>',
			type: 'POST',
			data : {
				order_id : <? echo $order_id ?>,
				senddate : $('input[name=\'date_sent\']').val(),
				ttn : $('input[name=\'ttn\']').val(),
				shipping_code : $('input[name=\'shipping_code\']').val(),
			},
			dataType : 'text',
			success : function(text){
				$('textarea[name=\'delivery_sms_text\']').val(text);
			},
			error : function(text){
				console.log(text);
			}
		});
	}
	
	<? if ($can_edit_csi && $csi_average == 0) { ?>
		function finishCSI(){
			$.ajax({
				url	: 'index.php?route=sale/order/updateUserCSIWork&token=<?php echo $token; ?>',
				type: 'GET',
				dataType : 'text',
				success : function(text){
					swal({ title: "Опрос закончен!", text: "Умничка, теперь заказ можно закрыть!", type: "success"}, function(){ $('#finish_csi_button').remove(); });
					$('#finish_csi_button').remove();
				},
				error : function(text){
					console.log(text);
				}	
			})
		}
	<? } ?>
	
	function update_bt_template(){
		$.ajax({
			url	: 'index.php?route=sale/order/loadbottomtext&token=<?php echo $token; ?>',
			type: 'POST',
			data : {
				tpl_file : $('#text_template option:selected').val(),
				order_id : <? echo $order_id ?>,
				is_bad_good : $('input[name=\'is_bad_good\']').attr('checked') ? 1 : 0,
			},
			dataType : 'html',
			success : function(html){
				CKEDITOR.instances.bottom_text.setData(html);
				//	CKEDITOR.instances.bottom_text.setReadOnly(true);
			},
			error : function(html){
				console.log(html);
			}
		});
	}
</script>
<script>
	function syntaxHighlight(json) {
		json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
		return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
			var cls = 'number';
			if (/^"/.test(match)) {
				if (/:$/.test(match)) {
					cls = 'key';
					} else {
					cls = 'string';
				}
				} else if (/true|false/.test(match)) {
				cls = 'boolean';
				} else if (/null/.test(match)) {
				cls = 'null';
			}
			return '<span class="' + cls + '">' + match + '</span>';
		});
	}
	
	$(document).ready(function(){
		
		$('#get_cc_info').click(function(){
			var _el = $(this);
			$.ajax({
				url : 'index.php?route=sale/order/getConcardisOrder&cc_order_id='+$('#concardis_id').val() + '&token=<?php echo $token; ?>',
				type : 'GET',
				dataType : 'JSON',
				beforeSend : function(){				
				},
				success : function(j){
					$('#cc_debug_result').html('<pre>' + syntaxHighlight(JSON.stringify(j, null, 2)) + '</pre>').dialog({title:'Информация о транзакции', width:900, height:700, modal:true,resizable:true,position:{my: 'center', at:'center top', of: window}, closeOnEscape: true})	;
				},
				error : function(e){
					$('#cc_debug_result').html('Ошибка' + e);
				}
			});
		});
		
		$('#update_parties_from_1c').click(function(){
			var _el = $(this);
			$.ajax({
				url : 'index.php?route=sale/order/updatePartiesFrom1CAjax&order_id=<? echo $order_id; ?>&token=<?php echo $token; ?>',
				type : 'GET',
				dataType : 'JSON',
				beforeSend : function(){
					$('#update_partie_result').children('i').addClass('fa').addClass('fa-spinner').addClass('fa-spin');
				},
				success : function(json){
					$('#update_partie_result').children('i').removeClass('fa').removeClass('fa-spinner').removeClass('fa-spin');
					console.log(json);
					$.each(json, function(i, item){
						var _ipel = $(".part_num[data-order-product-id='" + item.order_product_id + "']");
						console.log(_ipel);
						_ipel.val(item.part_num);
					});
					$('#update_partie_result').html('Что-то получилось..');
				},
				error : function(e){
					$('#update_partie_result').html('Ошибка');
				}
			});
		});
		
		$('#go_to_order').keyup(function (e) {
			if (e.keyCode === 13) {
				go_to_order();
			}
		});
		
		$('.ask_buyers').click(function(){
			$.ajax({
				url : 'index.php?route=api/bitrixbot/askBuyersAboutProductIDAjax&quantity='+$(this).attr('data-quantity')+'&product_id='+$(this).attr('data-product-id')+'&token=<?php echo $token; ?>',
				type : 'GET',
				success : function(){
					
				},
				error : function(e){
					console.log(e);
				}
			});
		});
		
		$('#pushHobotunManagers').click(function(){
			$.ajax({
				url : 'index.php?route=api/bitrixbot/tellSalesToCallByOrderAjax&order_id=<?php echo $order_id; ?>&token=<?php echo $token; ?>',
				type : 'GET',
				success : function(){
					
				},
				error : function(e){
					console.log(e);
				}
			});
		});
		
		$('.tell_pricemanager').click(function(){
			$.ajax({
				url : 'index.php?route=api/bitrixbot/tellPriceManagerPriceNotActualAjax&quantity='+$(this).attr('data-quantity')+'&product_id='+$(this).attr('data-product-id')+'&token=<?php echo $token; ?>',
				type : 'GET',
				success : function(){
					
				},
				error : function(e){
					console.log(e);
				}
			});
		});
		
		$('.ask_hobotun').click(function(){
			var _el = $(this);
			$.ajax({
				url : 'index.php?route=api/bitrixbot/askHobotunAboutProductIDAjax&quantity='+$(this).attr('data-quantity')+'&product_id='+$(this).attr('data-product-id')+'&token=<?php echo $token; ?>',
				type : 'GET',
				beforeSend : function(){
					_el.next('i').removeClass('fa-smile-o');
					_el.next('i').addClass('fa-spinner').addClass('fa-spin');
				},
				success : function(){
					_el.next('i').removeClass('fa-spinner').removeClass('fa-spin');
					_el.next('i').addClass('fa-smile-o');
				},
				error : function(e){
					console.log(e);
				}
			});
		})
		
		
		$('#do_separate_order').click(function(){
			if ($(this).is(':checked')){
				$('#button_do_separate_order').removeClass('redbg');								
				} else {
				$('#button_do_separate_order').addClass('redbg');								
			}						
		});
		
		
		$('#do_empty_order').click(function(){
			if ($(this).is(':checked')){
				$('#button_do_empty_order').removeClass('redbg');
				$('#button_do_empty_order').on('click', function(){
					$('#form').attr('action', '<? echo str_replace('&amp;','&', $url_neworder); ?>'); $('#form').submit();
				});
				} else {
				$('#button_do_empty_order').addClass('redbg');	
				$('#button_do_empty_order').attr('onclick',null).off('click');		
			}						
		});
		
		$('#do_recalculate_date').click(function(){
			if ($(this).is(':checked')){
				$('#button_recalculate_date').removeClass('redbg');
				$('#button_recalculate_date').on('click', function(){
					
					<? if ($shipping_country_id == 220) { ?>
						
						$('input[name=date_buy]').val('<? echo date('Y-m-d'); ?>').focusout();					
						$('input[name=date_country]').val('<? echo date('Y-m-d', strtotime('+7 day')); ?>').focusout();
						$('input[name=date_delivery]').val('<? echo date('Y-m-d', strtotime('+10 day')); ?>').focusout();
						$('input[name=date_delivery_to]').val('<? echo date('Y-m-d', strtotime('+2 week')); ?>').focusout();
						//	$('input[name=date_delivery_actual]').val('<? echo date('Y-m-d', strtotime('+2 week 5 day')); ?>').focusout();
						$('input[name=date_maxpay]').val('<? echo date('Y-m-d', strtotime('+2 day')); ?>').focusout();
						
						<? } else { ?>
						
						$('input[name=date_buy]').val('<? echo date('Y-m-d'); ?>').focusout();					
						$('input[name=date_country]').val('<? echo date('Y-m-d', strtotime('+2 week')); ?>').focusout();
						$('input[name=date_delivery]').val('<? echo date('Y-m-d', strtotime('+2 week 2 day')); ?>').focusout();
						$('input[name=date_delivery_to]').val('<? echo date('Y-m-d', strtotime('+2 week 5 day')); ?>').focusout();
						//	$('input[name=date_delivery_actual]').val('<? echo date('Y-m-d', strtotime('+2 week 5 day')); ?>').focusout();
						$('input[name=date_maxpay]').val('<? echo date('Y-m-d', strtotime('+2 day')); ?>').focusout();
						
					<? } ?>
					
				});
				} else {
				$('#button_recalculate_date').addClass('redbg');
				$('#button_recalculate_date').on('click', function(){return false;});
			}						
		});
		
		$('.go_to_store').on('click', function(){
			var store_id = $(this).attr('data-store-id');
			var customer_id = $(this).attr('data-customer-id');
			
			swal({ title: "Перейти в магазин?", text: "В личный кабинет покупателя", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, перейти!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() {
				window.open('index.php?route=sale/customer/login&token=<?php echo $token; ?>&customer_id='+customer_id+'&store_id=' + store_id)
			});
			
		});
		
		
		$('#analyze-buy-button').click(function(){
			$.ajax({
				url: 'index.php?route=sale/analyze/analyzeBuyPriority&token=<?php echo $token; ?>&order_id=<? echo $order_id ?>',
				dataType: 'html',
				success : function(html){
					$('#analyze-buy-preview').html(html).dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: "Анализ приоритетности закупки заказа"})
				}
			})
		});
		
		$('.return-history-button').click(function(){
			$.ajax({
				url: 'index.php?route=sale/return/history&token=<?php echo $token; ?>&return_id=' +  $(this).attr('data-return-id'),
				dataType: 'html',
				success : function(html){
					$('#return-history-preview').html(html).dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: "История действий по возврату"+$(this).attr('data-return-id')})
				}
			})
		});
		
		$('.quantity_change').on('keyup, change', function(){
			$("#total_national_"+ $(this).attr('data-id')).val( $("#price_national_"+ $(this).attr('data-id')).val() * $(this).val() );
			
			var reward_points = $("#reward_one_"+ $(this).attr('data-id')).val();
			$("#reward_"+ $(this).attr('data-id')).val( $("#reward_one_"+ $(this).attr('data-id')).val() * $(this).val() );
			$("#reward_points_"+ $(this).attr('data-id')).text( $("#reward_one_"+ $(this).attr('data-id')).val() * $(this).val() );
			
			recount_totals();
		});
		
		$('.price_change').on('keyup', function(){
			$("#total_national_"+ $(this).attr('data-id')).val( $("#quantity_"+ $(this).attr('data-id')).val() * $(this).val() );
			recount_totals();
		});
		
		get_cheque_name(1);
		
		$('body').on('click', '#button-save-print-cheque', function(){
			
			if ($('#cheque-shown').html().length > 1){
				
				var original_html = $('#cheque-shown').html();
				
				$("#preinvoice_table tfoot").find('input').each(function() {
					$(this).replaceWith("<span><b>" + this.value + "</b></span>");
				});
				
				$("input.invoice_total_input").each(function() {
					$(this).replaceWith("<span>" + this.value + "</span>");
				});
				
				if ($("textarea#cheque_comment").length) {
					var cheque_comment = $("textarea#cheque_comment").attr('value');
					
					if (cheque_comment.length > 2){
						$("#cheque_comment").replaceWith("<span>" + cheque_comment + "</span>");
						} else {
						$('#table_cheque_comment').remove();
					}
				}
				
				$(".totals_cheque_row_delete").remove();
				$("#cheque_add_row").remove();
				
				$.ajax({
					url : 'index.php?route=sale/order/save_and_print_invoice&token=<? echo $token ?>',
					type : 'POST',
					dataType: 'html',
					data : {
						order_id : '<? echo $order_id; ?>',
						invoice_name: $('#cheque-num').val(),
						html : $('#cheque-shown').html(),
						delivery_num : $('#show-cheque-delivery-num').val()
					},
					success : function(e){
						$('#order_invoice_history').load('index.php?route=sale/order/invoicehistory&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
						$('#cheque-shown').html(original_html);
						swal("Чек записан!", "Выгрузить можно в истории чеков!", "success");
						//window.open('index.php?route=sale/order/singleinvoice_log&token=<?php echo $token; ?>&id=' +  parseInt(e), '_blank');
					}
					
				});
				} else {
				swal("Внимание!",'Сначала сформируем чек, перед тем, как его печатать',"warning");
			};
		});
		
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		};
		
		$('#button-show-cheque').click(function(){
			$.ajax({
				url : 'index.php?route=sale/order/single_invoice&token=<? echo $token ?>',
				type : 'POST',
				dataType: 'html',
				data : {
					order_id : '<? echo $order_id; ?>',
					delivery_num : $('#show-cheque-delivery-num').val(),
					cheque_date : $('#show-cheque-date').val(),
					cheque_num: $('#cheque-num').val(),
					cheque_type: $('#show-cheque-type').val(),
					cheque_prim : $('input[name=\'cheque-prim\']').attr('checked') ? 1 : 0,
					cheque_return : $('input[name=\'cheque-return\']').attr('checked') ? 1 : 0,
				},
				
				success : function(html){
					$('#cheque-shown').show();
					$('#cheque-shown').html(html);
					$('#button-save-print-cheque').show();
					$('#cheque_add_row').click(function(){
						cheque_add_row();
					});
					$("#preinvoice_table tfoot").sortable({
						helper: fixHelper
					});
					$('.invoice_total_sum_input').on('keyup', function(){
						cheque_recount();
					});
				},
				
				error : function(e){
					console.log(e);
				}
				
			});
			
			
		});
		
		$('#pay_equire_img').click(function(){
			if ($('input#pay_equire').val()=='1'){
				$('input#pay_equire').val('0').change();
				$(this).removeClass('eq_disabled');
				$(this).addClass('eq_disabled');
				} else if ($('input#pay_equire').val()=='0'){
				$('input#pay_equire').val('1').change();
				$(this).removeClass('eq_disabled');
			}
		});
		
		$('#pay_equirePP_img').click(function(){
			if ($('input#pay_equirePP').val()=='1'){
				$('input#pay_equirePP').val('0').change();
				$(this).removeClass('eq_disabled');
				$(this).addClass('eq_disabled');
				} else if ($('input#pay_equirePP').val()=='0'){
				$('input#pay_equirePP').val('1').change();
				$(this).removeClass('eq_disabled');
			}
		});
		
		$('#pay_equireLQP_img').click(function(){
			if ($('input#pay_equireLQP').val()=='1'){
				$('input#pay_equireLQP').val('0').change();
				$(this).removeClass('eq_disabled');
				$(this).addClass('eq_disabled');
				} else if ($('input#pay_equireLQP').val()=='0'){
				$('input#pay_equireLQP').val('1').change();
				$(this).removeClass('eq_disabled');
			}
		});
		
		$('#pay_equireWPP_img').click(function(){
			if ($('input#pay_equireWPP').val()=='1'){
				$('input#pay_equireWPP').val('0').change();
				$(this).removeClass('eq_disabled');
				$(this).addClass('eq_disabled');
				} else if ($('input#pay_equireWPP').val()=='0'){
				$('input#pay_equireWPP').val('1').change();
				$(this).removeClass('eq_disabled');
			}
		});

		$('#pay_equireMono_img').click(function(){
			if ($('input#pay_equireMono').val()=='1'){
				$('input#pay_equireMono').val('0').change();
				$(this).removeClass('eq_disabled');
				$(this).addClass('eq_disabled');
				} else if ($('input#pay_equireMono').val()=='0'){
				$('input#pay_equireMono').val('1').change();
				$(this).removeClass('eq_disabled');
			}
		});
		
		$('#pay_equireCP_img').click(function(){
			if ($('input#pay_equireCP').val()=='1'){
				$('input#pay_equireCP').val('0').change();
				$(this).removeClass('eq_disabled');
				$(this).addClass('eq_disabled');
				} else if ($('input#pay_equireCP').val()=='0'){
				$('input#pay_equireCP').val('1').change();
				$(this).removeClass('eq_disabled');
			}
		});
		
		var pfn = $('#tab-payment input[name=\'payment_firstname\']');
		var sfn = $('#tab-shipping input[name=\'shipping_firstname\']');
		
		var pln = $('#tab-payment input[name=\'payment_lastname\']');
		var sln = $('#tab-shipping input[name=\'shipping_lastname\']');
		
		var pco = $('#tab-payment input[name=\'payment_company\']');
		var sco = $('#tab-shipping input[name=\'shipping_company\']');
		
		var pad1 = $('#tab-payment input[name=\'payment_address_1\']');
		var sad1 = $('#tab-shipping input[name=\'shipping_address_1\']');
		
		var pas1 = $('#tab-payment input[name=\'payment_address_struct\']');
		var sas1 = $('#tab-shipping input[name=\'shipping_address_struct\']');
		
		var pad2 = $('#tab-payment input[name=\'payment_address_2\']');
		var sad2 = $('#tab-shipping input[name=\'shipping_address_2\']');
		
		var pct = $('#tab-payment input[name=\'payment_city\']');
		var sct = $('#tab-shipping input[name=\'shipping_city\']');
		
		var ppc = $('#tab-payment input[name=\'payment_postcode\']');
		var spc = $('#tab-shipping input[name=\'shipping_postcode\']');
		
		$('#payment-to-shipping').click(function(){
			sfn.val(pfn.val());
			sln.val(pln.val());
			sco.val(pco.val());
			sad1.val(pad1.val());
			sad2.val(pad2.val());
			sct.val(pct.val());
			spc.val(ppc.val());
			sas1.val(pas1.val());
			submit_order_shipping_address_<?php echo $order_id; ?>();
		});
		
		$('#shipping-to-payment').click(function(){
			pfn.val(sfn.val());
			pln.val(sln.val());
			pco.val(sco.val());
			pad1.val(sad1.val());
			pad2.val(sad2.val());
			pct.val(sct.val());
			ppc.val(spc.val());
			pas1.val(sas1.val());
			submit_order_payment_address_<?php echo $order_id; ?>();
		});
		
		$('#button-product').on('click', function() {
			var buttonAddProduct = $(this);
			if ($('input[name=\'product_id\']').val() > 0){
				var pid = $('input[name=\'product_id\']').val();
				var quant = $('input[name=\'quantity\']').val();
				var cid = $('input[name=\'customer_group_id\']').val();
				var override_price = $('input[name=\'override_price\']').val();
				var ao_id = $('input[name=\'ao_id\']').val();
				
				$.ajax({
					url : 'index.php?route=sale/order/addProduct&token=<?php echo $token; ?>',
					dataType: 'json',
					crossDomain: true,
					type : 'post',
					beforeSend: function(){
						console.log('ADD PRODUCT PROCESS STARTED');
						buttonAddProduct.html('<i class="fa fa-spinner fa-spin"></i> Пересчитываем итоги...');
					},
					data : {
						product_id : pid,
						customer_group_id : cid,
						order_id : <? echo $order_id; ?>,
						quantity : quant,
						override_price : override_price,
						ao_id : ao_id,
						ajax : 1
					},
					success : function(json){
						console.log('ADD PRODUCT PROCESS SUCCESS');
						console.log(json);
						location.reload();
					},
					error : function(e){
						console.log('ADD PRODUCT PROCESS FAILED');
						console.log(e);
					}
				});
			}
		});
		
		$('i.delivery-num-plus').click(function() {
			spin($('#delivery_num_'+$(this).attr('data-product-id')), 1);
			$($('#delivery_num_actual_'+$(this).attr('data-product-id'))).text($('#delivery_num_'+$(this).attr('data-product-id')).val());
		});
		$('i.delivery-num-minus').click(function() {
			spin($('#delivery_num_'+$(this).attr('data-product-id')), -1);
			$($('#delivery_num_actual_'+$(this).attr('data-product-id'))).text($('#delivery_num_'+$(this).attr('data-product-id')).val());
		});
		
		$("input.part_num").keyup(function(){
			var part_num = $(this).val();
			var delivery_num = $('#delivery_num_'+$(this).attr('data-product-id')).val();
			
			$("input.part_num[data-delivery-num='"+delivery_num+"']").val(part_num);
		});
		
		
		$('input[name=\'date_sent\'],input[name=\'ttn\'],input[name=\'shipping_code\']').change(function(){
			getSMSTextAjax();
		});
		
		$('input[name=\'send_delivery_sms\']').click(function(){
			if ($(this).attr('checked')=='checked'){
				$('#delivery_sms_send_button').text('Добавить ТТН, отправить SMS');
				} else {
				$('#delivery_sms_send_button').text('Добавить ТТН');
			}
		});
		
		$('input[name=\'ttn\']').keyup(function(){
			getSMSTextAjax();
		});
		
		$('#update_bottom_text').click(function(){
			update_bt_template();
		});
		
		recount_totals();
		getStatusSMSTextAjax();
		get_ttn_history();
		getSMSTextAjax();
		
		$('#transaction').load('index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');
		$('#waitlist-list').load('index.php?route=catalog/waitlist&token=<?php echo $token; ?>&limit=200&ajax=1&filter_customer_id=<?php echo $customer_id; ?>');
		$('#orders-list').load('index.php?route=sale/order&token=<?php echo $token; ?>&limit=200&ajax=1&filter_customer=<?php echo $customer_id; ?>', function(){ setRateYo();  });
		
		$('#reward-list').load('index.php?route=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>', function(){  });
		
		$('#calls-list').load('index.php?route=sale/customer/callshistory&token=<?php echo $token; ?>&limit=200&ajax=1&customer_id=<?php echo $customer_id; ?>', function(){ setAudioTrigger(); });
		$('#customer-v-history-list').load('index.php?route=sale/customer/history&token=<?php echo $token; ?>&limit=200&ajax=1&customer_id=<?php echo $customer_id; ?>');
		
		$('#reward-list .pagination a').on('click', function() {
			$('#reward-list').load(this.href);
			
			return false;
		});	
		
		$('#transaction .pagination a').on('click', function() {
			$('#transaction').load(this.href);
			return false;
		});
		$('#customer-v-history-list .pagination a').on('click', function() {
			$('#customer-v-history-list').load(this.href);
			return false;
		});
		$('#calls-list .pagination a').on('click', function() {
			$('#calls-list').load(this.href);
			return false;
		});
	});
</script>
<? if ($is_super_buyer) { ?>	
	<script>
		$(".is_from_stock").click(function(){ 
			var fsopid = $(this).attr('data-order-product-id');
			var fsopidelem = $('#hidden_from_stock_'+fsopid);
			
			console.log(fsopidelem);
			
			if (fsopidelem.val() == 0){
				fsopidelem.val(1);
				$(this).addClass('active');
				} else {
				fsopidelem.val(0);
				$(this).removeClass('active');																	
			}
		});
	</script>
<? } ?>
<script type="text/javascript">
	$(document).ready(function(){
		
	});
</script>
<script>
	
	$(document).ready(
	function(){
		
		$('input[name=\'tr_amount\']').keyup(function(){
			if ($(this).attr('data-no-update') != '1') {
				getTransactionSMSAjax();
			}
		});
		$('#transactionorder .pagination a').on('click', function() {
			$('#transactionorder').load(this.href);
			
			return false;
		});
		$('#transactionorder').load('index.php?route=sale/customer/transactionorder&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>&order_id=<?php echo $order_id; ?>', function(){ setTransactionEditTriggers(); });
		$('#button-transaction').bind('click', function() {
			$.ajax({
				url: 'index.php?route=sale/customer/transactionorder&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>&order_id=<?php echo $order_id; ?>',
				type: 'post',
				dataType: 'html',
				data: 'description=' + encodeURIComponent($('#tab-transactionorder input[name=\'tr_description\']').val()) + '&amount=' + encodeURIComponent($('#tab-transactionorder input[name=\'tr_amount\']').val()) + '&order_id=' + encodeURIComponent(<?php echo $order_id; ?>)+'&transaction_sms_text='+encodeURIComponent($('textarea[name=\'transaction_sms_text\']').val())+'&send_transaction_sms=' + encodeURIComponent($('input[name=\'send_transaction_sms\']').attr('checked') ? 1 : 0)+'&date_added='+encodeURIComponent($('input[name=\'tr_date_added\']').val())+'&legalperson_id='+encodeURIComponent($('select[name=\'tr_legalperson_id\']').val()),
				beforeSend: function() {
					$('.success, .warning').remove();
					$('#button-transaction').attr('disabled', true);
					$('#transactionorder').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
				},
				complete: function() {
					$('#button-transaction').attr('disabled', false);
					$('.attention').remove();
				},
				success: function(html) {
					$('#transactionorder').html(html);
					$('#transaction').load('index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>', function(){ setTransactionEditTriggers(); });
					$('#tab-transactionorder input[name=\'amount\']').val('');
					$('#tab-transactionorder input[name=\'description\']').val('');
					$('#order_sms_history').load('index.php?route=sale/order/smshistory&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
				}
			});
		});
		
		
		function setTransactionEditTriggers(){
			console.log('setTransactionEditTriggers');			
			$('body').on('click', '#delete-transaction', function(){
				var tid = $(this).attr('data-number');
				swal({ title: "Точно удалить транзакцию?", text: "Это действие нельзя отменить!", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, удалить!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() {
					$.ajax({
						url: 'index.php?route=sale/order/delete_order_transaction&token=<?php echo $token; ?>',
						type: 'post',
						dataType: 'html',
						data: {
							'transaction_id' : tid,
							'order_id'       : <? echo $order_id; ?>
						},
						success: function(html){
							$('#transactionorder').load('index.php?route=sale/customer/transactionorder&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>&order_id=<?php echo $order_id; ?>', function(){ setTransactionEditTriggers(); });
							$('#transaction').load('index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>', function(){ setTransactionEditTriggers(); });
						},
						error: function(html){
							console.log(html)
						}
						
					});
				});
			});
			
			$('body').on('click', '#change-transaction', function(){
				$('#transaction_amount_'+$(this).attr('data-number')).show();
				console.log($(this).attr('data-number'));
				$('#transaction_text_'+$(this).attr('data-number')).hide();
				$(this).next().show();
				$(this).hide();
			});
			
			$('body').on('click', "#rja_customer", function(){
				var _el = $(this);
				var _cid = $(this).attr('data-customer-id');
				$.ajax({
					url : 'index.php?route=sale/customer/setCustomerRJAAjax&customer_id=<? echo $customer_id; ?>&token=<?php echo $token; ?>',
					type : 'GET',
					success : function(i){
						if (i == 1){
							_el.addClass('is_nbt');
							} else {
							_el.removeClass('is_nbt');
						}					
					},
					error : function(e){
						console.log(e);
					}
				});
			});
			
			$('body').on('click', '#change-transaction-real', function(){
				var tid2 = $(this).attr('data-number');
				swal({ title: "Точно изменить транзакцию?", text: "Это действие нельзя отменить!", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, изменить!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() {
					$.ajax({
						url: 'index.php?route=sale/customer/changeTransactionAmountAjax&token=<?php echo $token; ?>',
						type: 'post',
						dataType: 'html',
						data: {
							'transaction_id' : tid2,
							'new_amount' : $('#transaction_amount_' + tid2).val(),
							'currency_code' : '<? echo $currency_code; ?>',
							'store_id' : '<? echo $store_id; ?>'
						},
						success: function(html){
							console.log(html);
							$('#transactionorder').load('index.php?route=sale/customer/transactionorder&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>&order_id=<?php echo $order_id; ?>', function(){ setTransactionEditTriggers(); });
							$('#transaction').load('index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>', function(){ setTransactionEditTriggers(); });
						},
						error: function(html){
							console.log(html)
						}					
					});				
				});
			});
		}
		setTransactionEditTriggers();
	});
</script>			