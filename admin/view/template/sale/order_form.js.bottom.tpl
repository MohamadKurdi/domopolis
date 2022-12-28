<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/js/jquery.suggestions.min.js"></script>


<script>
	function checkConcarisIsPaid(){
		console.log('init checkConcarisIsPaid ');
		$.ajax({
			url: 'index.php?route=sale/order/parseConcardisOrderAjax&token=<?php echo $token; ?>&cc_order_id=<? echo $concardis_id; ?>',
			dataType: 'json',
			error: function(e) {
				console.log(e);
			},
			beforeSend: function(){
				$('#check_concardis_status_stage_id').html("<i class='fa fa-spinner fa-spin'></i> Проверяю статус оплаты");
			},
			success: function(json){				
				if (json.STATUS == 'SUCCESS'){
					html = "<i class='fa fa-check'></i> ";
					html += "Заявка Concardis оплачена.";
					html += "<br />Последняя операция: " + json.lastOperation;
					$('#check_concardis_status_stage_id').css({'background-color':'#4ea24e'});
					$('#check_concardis_status_stage_id').html(html);
				}
				
				if (json.STATUS == 'NOTPAID'){
					html = "<i class='fa fa-check'></i> ";
					html += "Заявка Concardis пока не оплачена";
					html += "<br />Последняя операция: " + json.lastOperation;
					$('#check_concardis_status_stage_id').html(html);
				}
				
				if (json.STATUS == 'ERROR'){
					html = "<i class='fa fa-exclamation-triangle'></i> ";
					html += "Ошибка при проверке статуса заявки Concardis";
					$('#check_concardis_status_stage_id').html(html);
				}
			}
		});
	}
</script>

<?php if ($CONCARDIS_IS_PAID_AND_CHECKED_MANUAL || $CHECK_CONCARDIS_STATUS_STAGE_1) { ?>
	<script>
		$(document).ready(function(){
			checkConcarisIsPaid();
		});
	</script>
<?php } ?>

<script>
	$('input[name=\'payment_city\']').suggestions({
        token: "<?php echo $this->config->get('config_dadata_api_key'); ?>",
        type: "ADDRESS",
		deferRequestBy: 200,
		minChars: 2,
		constraints: {
			locations: { 
				country_iso_code: $('select[name=\'payment_country_id\'] > option:selected').attr('data-iso') 
			},
		},
        onSelect: function(suggestion) {
            $('input[name=\'payment_address_1\']').trigger('focusout');
		}
	});
	
	function nullifyAddressCustomFields(scope){
		
		$('input[name=\''+scope+'_custom_unrestricted_value\']').val('');
		$('span#value_'+scope+'_custom_unrestricted_value').text('');
		$('input[name=\''+scope+'_custom_geolocation\']').val('');
		$('span#value_'+scope+'_custom_geolocation').text('');
		$('input[name=\''+scope+'_custom_beltway_distance\']').val('');
		$('span#value_'+scope+'_custom_beltway_distance').text('');	
		$('input[name=\''+scope+'_custom_beltway_hit\']').val('');
		$('span#value_'+scope+'_custom_beltway_hit').text('');
		$('span#'+scope+'-mkad-error').remove();
		
		
	}	
	
	function tryToGuessAddressStructFromDaData(scope){
		console.log('init tryToGuessAddressStructFromDaData ' + scope);
		$('#suggest_get_'+scope+'_address_struct_stage_2').html('Пробую получить данные...');
		
		var query = $('input[name=\'' + scope + '_address_1\']').val();
		var iso = $('select[name=\'' + scope + '_country_id\'] > option:selected').attr('data-iso');
		
		$.ajax({
			url: 'index.php?route=kp/customer/getFullAddressAjax&token=<?php echo $token; ?>&query='+query+'&iso='+iso,
			dataType: 'json',
			error: function(e) {
				console.log(e);
			},
			success: function(json) {
				console.log('tryToGuessAddressStructFromDaData ' + scope + ': ' + json.suggestions.length);
				if (typeof(json.suggestions) != 'undefined' && json.suggestions && json.suggestions.length == 1){
					$('#suggest_get_'+scope+'_address_struct_stage_2').removeClass('error').css({'color': '#4ea24e'}).html('<i class="fa fa-info-circle"></i> При автоматической попытке разбора мы получили один возможный вариант. В скором времени он будет подставляться автоматически!<br />' + json.suggestions[0].unrestricted_value);
					
					
					} else {
					
					var addresslist = '';
					for(var suggestion of json.suggestions) {
						console.log(suggestion);
						addresslist = addresslist + '<br />' + suggestion.unrestricted_value;
					}
					
					$('#suggest_get_'+scope+'_address_struct_stage_2').removeClass('error').css({'color': '#7F00FF'}).html('<i class="fa fa-info-circle"></i> При автоматической попытке разбора мы получили ' + json.suggestions.length + ' возможных вариантов!<br />' + addresslist);
				}
				
			}
		});
		
	}
	
	<?php if ($suggest_get_payment_address_struct_stage_2) { ?>		
		$(document).ready(function(){
			tryToGuessAddressStructFromDaData('payment');
		});		
	<?php } ?>
	
	<?php if ($suggest_get_shipping_address_struct_stage_2) { ?>		
		$(document).ready(function(){
			tryToGuessAddressStructFromDaData('shipping');
		});		
	<?php } ?>
	
	function updateAddressStructFromDaData(scope){
		console.log('init updateAddressStructFromDaData ' + scope);
		$('#suggest_get_'+scope+'_address_struct_stage_1').html('Пробую получить данные...');
		
		var query = $('input[name=\'' + scope + '_custom_unrestricted_value\']').val();
		var iso = $('select[name=\'' + scope + '_country_id\'] > option:selected').attr('data-iso');
		
		$.ajax({
			url: 'index.php?route=kp/customer/getFullAddressAjax&token=<?php echo $token; ?>&exact=1&query='+query+'&iso='+iso,
			dataType: 'json',
			error: function(e) {
				$('#suggest_get_'+scope+'_address_struct_stage_1').html('Что-то пошло не так. Не получилось разобрать адрес доставки.<br /> Чтоб курьер смог доставить заказ по правильному адресу, а не куда-то в ебеня, пожалуйста, воспользуйся подсказкой');
			},
			success: function(json) {
				if (typeof(json.unrestricted_value) != 'undefined' && json.unrestricted_value != ''){
					if (json.data){	
						$('#' + scope + '_address_struct').val(JSON.stringify(json.data)).trigger('change');
						$('#valid_' + scope + '_address_struct').removeClass('error').css({'color': '#4ea24e'});
						$('#suggest_get_'+scope+'_address_struct_stage_1').removeClass('error').css({'color': '#4ea24e'});
						$('#suggest_get_'+scope+'_address_struct_stage_1').html('Данные об адресе получены, курьер будет направлен куда нужно!<br />'+ json.unrestricted_value);
						$('#valid_'+scope+'_address_struct').html('Адрес получен, структура получена.');
						} else {
						$('#suggest_get_'+scope+'_address_struct_stage_1').html('Что-то пошло не так. Не получилось разобрать адрес доставки.<br /> Чтоб курьер смог доставить заказ по правильному адресу, а не куда-то в ебеня, пожалуйста, воспользуйся подсказкой');
					}
					} else {
					$('#suggest_get_'+scope+'_address_struct_stage_1').html('Что-то пошло не так. Не получилось разобрать адрес доставки.<br /> Чтоб курьер смог доставить заказ по правильному адресу, а не куда-то в ебеня, пожалуйста, воспользуйся подсказкой');
				}
			}
		});
	}
	
	<?php if ($suggest_get_payment_address_struct_stage_1) { ?>		
		$(document).ready(function(){
			updateAddressStructFromDaData('payment');
		});		
	<?php } ?>
	
	<?php if ($suggest_get_shipping_address_struct_stage_1) { ?>		
		$(document).ready(function(){
			updateAddressStructFromDaData('shipping');
		});		
	<?php } ?>
	
	function updateAddressCustomFields(scope, suggestion){
		let beltway_hit = suggestion.data.beltway_hit;
		let beltway_distance = suggestion.data.beltway_distance;
		let geolocation = suggestion.data.geo_lat + ', ' + suggestion.data.geo_lon;
		let unrestricted_value = suggestion.unrestricted_value;
		let address_json = JSON.stringify(suggestion);	
		
		if (address_json != null){
			$('input[name=\''+scope+'_address_struct\']').val(address_json);
			$('input[name=\''+scope+'_address_struct\']').trigger('change');
			$('span#valid_'+scope+'_address_struct').remove();
			$('span#suggest_get_'+scope+'_address_struct_stage_1').remove();
		}
		
		if (unrestricted_value != null){
			$('input[name=\''+scope+'_custom_unrestricted_value\']').val(unrestricted_value);
			$('span#value_'+scope+'_custom_unrestricted_value').text(unrestricted_value);
		}
		
		if (geolocation != null){
			$('input[name=\''+scope+'_custom_geolocation\']').val(geolocation);
			$('span#value_'+scope+'_custom_geolocation').text(geolocation);
		}
		
		if (beltway_distance != null){
			$('input[name=\''+scope+'_custom_beltway_distance\']').val(beltway_distance + 'км');
			$('span#value_'+scope+'_custom_beltway_distance').text(beltway_distance + 'км');
			$('input[name=\''+scope+'_address_1\']').before('<span id="'+scope+'-mkad-error" class="error"><i class="fa fa-exclamation-triangle"></i> Внимание! Адрес находится за МКАД. Стоимость доставки увеличена!</span>');
		}
		
		if (geolocation != null){
			$('input[name=\''+scope+'_custom_beltway_hit\']').val(beltway_hit);
			$('span#value_'+scope+'_custom_beltway_hit').text(beltway_hit);
		}
		
		$('input[name=\''+scope+'_postcode\']').val(suggestion.data.postal_code).trigger('focusout');
		$('input[name=\''+scope+'_address_1\']').trigger('focusout');
	}
	
	<?php if ($use_custom_dadata == 'address') { ?>
		$('input[name=\'payment_address_1\']').suggestions({
			token: "<?php echo $this->config->get('config_dadata_api_key'); ?>",
			type: "ADDRESS",
			deferRequestBy: 200,
			minChars: 2,
			restrict_value: true,
			constraints: {
				locations: {
					country_iso_code: $('select[name=\'payment_country_id\'] > option:selected').attr('data-iso'),
					city:  ($('input[name=\'payment_city\']').val() != '')?$('input[name=\'payment_city\']').val():'*'
				},		
			},
			onSearchStart: function(query){
				nullifyAddressCustomFields('payment');
			},
			onSelect: function(suggestion) {
				updateAddressCustomFields('payment', suggestion);
				submit_order_payment_address_<?php echo $order_id; ?>();
			}
		});
	<?php } ?>
	
	$('input[name=\'shipping_city\']').suggestions({
        token: "<?php echo $this->config->get('config_dadata_api_key'); ?>",
        type: "ADDRESS",
		deferRequestBy: 200,
		minChars: 2,
		constraints: {
			locations: { country_iso_code: $('select[name=\'shipping_country_id\'] > option:selected').attr('data-iso') 			
			},
		},
        onSelect: function(suggestion) {
            $('input[name=\'shipping_city\']').trigger('focusout');
		}
	});
	
	<?php if ($use_custom_dadata == 'address') { ?>
		console.log($('input[name=\'shipping_city\']').val());
		$('input[name=\'shipping_address_1\']').suggestions({
			token: "<?php echo $this->config->get('config_dadata_api_key'); ?>",
			type: "ADDRESS",
			deferRequestBy: 200,
			minChars: 2,
			restrict_value: true,
			constraints: {
				locations: {
					country_iso_code: $('select[name=\'payment_country_id\'] > option:selected').attr('data-iso'),
					city:  $('input[name=\'shipping_city\']').val()
				},		
			},
			onSearchStart: function(query){
				nullifyAddressCustomFields('shipping');
			},
			onSelect: function(suggestion) {
				updateAddressCustomFields('shipping', suggestion);
				submit_order_shipping_address_<?php echo $order_id; ?>();
			}
		});
	<?php } ?>
	
</script>


<script type="text/javascript"><!--
	$.widget('custom.catcomplete', $.ui.autocomplete, {
		_renderMenu: function(ul, items) {
			var self = this, currentCategory = '';
			
			$.each(items, function(index, item) {
				if (item['category'] != currentCategory) {
					ul.append('<li class="ui-autocomplete-category">' + item['category'] + '</li>');
					
					currentCategory = item['category'];
				}
				
				self._renderItem(ul, item);
			});
		}
	});
	
	$('select[id=\'customer_group_id\']').on('change', function() {
		$('input[name=\'customer_group_id\']').attr('value', this.value);
		
		var customer_group = [];
		
		<?php foreach ($customer_groups as $customer_group) { ?>
			customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
			customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
			customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_required'] = '<?php echo $customer_group['company_id_required']; ?>';
			customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
			customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_required'] = '<?php echo $customer_group['tax_id_required']; ?>';
		<?php } ?>
		
		if (customer_group[this.value]) {
			if (customer_group[this.value]['company_id_display'] == '1') {
				$('#company-id-display').show();
				} else {
				$('#company-id-display').hide();
			}
			
			if (customer_group[this.value]['company_id_required'] == '1') {
				$('#company-id-required').show();
				} else {
				$('#company-id-required').hide();
			}
			
			if (customer_group[this.value]['tax_id_display'] == '1') {
				$('#tax-id-display').show();
				} else {
				$('#tax-id-display').hide();
			}
			
			if (customer_group[this.value]['tax_id_required'] == '1') {
				$('#tax-id-required').show();
				} else {
				$('#tax-id-required').hide();
			}
		}
	});
	
	$('select[id=\'customer_group_id\']').trigger('change');
	
	
	
	$('input[name=\'affiliate\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=sale/affiliate/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['affiliate_id'],
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'affiliate\']').attr('value', ui.item['label']);
			$('input[name=\'affiliate_id\']').attr('value', ui.item['value']);
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	var payment_zone_id = '<?php echo $payment_zone_id; ?>';
	
	$('select[name=\'payment_country_id\']').bind('change', function() {
		$.ajax({
			url: 'index.php?route=sale/order/country&token=<?php echo $token; ?>&country_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'payment_country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
			},
			complete: function() {
				$('.wait').remove();
			},
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('#payment-postcode-required').show();
					} else {
					$('#payment-postcode-required').hide();
				}
				
				html = '<option value=""><?php echo $text_select; ?></option>';
				
				if (json != '' && json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';
						
						if (json['zone'][i]['zone_id'] == payment_zone_id) {
							html += ' selected="selected"';
						}
						
						html += '>' + json['zone'][i]['name'] + '</option>';
					}
					} else {
					html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
				}
				
				$('select[name=\'payment_zone_id\']').html(html);
				reloadAllShippingsAndPayments();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	
	$('select[name=\'payment_country_id\']').trigger('change');
	
	$('select[name=\'payment_address\']').bind('change', function() {
		$.ajax({
			url: 'index.php?route=sale/customer/address&token=<?php echo $token; ?>&address_id=' + this.value,
			dataType: 'json',
			success: function(json) {
				if (json != '') {
					$('input[name=\'payment_firstname\']').attr('value', json['firstname']);
					$('input[name=\'payment_lastname\']').attr('value', json['lastname']);
					$('input[name=\'payment_company\']').attr('value', json['company']);
					$('input[name=\'payment_company_id\']').attr('value', json['company_id']);
					$('input[name=\'payment_tax_id\']').attr('value', json['tax_id']);
					$('input[name=\'payment_address_1\']').attr('value', json['address_1']);
					$('input[name=\'payment_address_2\']').attr('value', json['address_2']);
					$('input[name=\'payment_city\']').attr('value', json['city']);
					$('input[name=\'payment_postcode\']').attr('value', json['postcode']);
					$('select[name=\'payment_country_id\']').attr('value', json['country_id']);
					
					payment_zone_id = json['zone_id'];
					
					$('select[name=\'payment_country_id\']').trigger('change');
				}
			}
		});
	});
	
	var shipping_zone_id = '<?php echo $shipping_zone_id; ?>';
	
	$('select[name=\'shipping_country_id\']').bind('change', function() {
		$.ajax({
			url: 'index.php?route=sale/order/country&token=<?php echo $token; ?>&country_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'payment_country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
			},
			complete: function() {
				$('.wait').remove();
			},
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('#shipping-postcode-required').show();
					} else {
					$('#shipping-postcode-required').hide();
				}
				
				html = '<option value=""><?php echo $text_select; ?></option>';
				
				if (json != '' && json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';
						
						if (json['zone'][i]['zone_id'] == shipping_zone_id) {
							html += ' selected="selected"';
						}
						
						html += '>' + json['zone'][i]['name'] + '</option>';
					}
					} else {
					html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
				}
				
				$('select[name=\'shipping_zone_id\']').html(html);
				reloadAllShippingsAndPayments();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	
	$('select[name=\'shipping_country_id\']').trigger('change');
	
	$('select[name=\'shipping_address\']').bind('change', function() {
		$.ajax({
			url: 'index.php?route=sale/customer/address&token=<?php echo $token; ?>&address_id=' + this.value,
			dataType: 'json',
			success: function(json) {
				if (json != '') {
					$('input[name=\'shipping_firstname\']').attr('value', json['firstname']);
					$('input[name=\'shipping_lastname\']').attr('value', json['lastname']);
					$('input[name=\'shipping_passport_serie\']').attr('value', json['passport_serie']);
					$('input[name=\'shipping_passport_given\']').attr('value', json['passport_given']);
					$('input[name=\'shipping_company\']').attr('value', json['company']);
					$('input[name=\'shipping_address_1\']').attr('value', json['address_1']);
					$('input[name=\'shipping_address_2\']').attr('value', json['address_2']);
					$('input[name=\'shipping_city\']').attr('value', json['city']);
					$('input[name=\'shipping_postcode\']').attr('value', json['postcode']);
					$('select[name=\'shipping_country_id\']').attr('value', json['country_id']);
					
					shipping_zone_id = json['zone_id'];
					
					$('select[name=\'shipping_country_id\']').trigger('change');
				}
			}
		});
	});
//--></script>
<script type="text/javascript"><!--
	$('input[name=\'product\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&only_enabled=1&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id,
							model: item.model,
							option: item.option,
							price: item.price,
							image: item.image
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'product\']').attr('value', ui.item['label']);
			$('input[name=\'product_id\']').attr('value', ui.item['value']);
			
			if (ui.item['option'] != '') {
				html = '';
				
				for (i = 0; i < ui.item['option'].length; i++) {
					option = ui.item['option'][i];
					
					if (option['type'] == 'select' || option['type'] == 'block') {
						html += '<div id="option-' + option['product_option_id'] + '">';
						
						if (option['required']) {
							html += ' ';
						}
						
						html += option['name'] + '<br />';
						html += '<select name="option[' + option['product_option_id'] + ']">';
						html += '<option value=""><?php echo $text_select; ?></option>';
						
						for (j = 0; j < option['option_value'].length; j++) {
							option_value = option['option_value'][j];
							
							html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
							
							if (option_value['price']) {
								html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
							}
							
							html += '</option>';
						}
						
						html += '</select>';
						html += '</div>';
						html += '<br />';
					}
					
					if (option['type'] == 'radio') {
						html += '<div id="option-' + option['product_option_id'] + '">';
						
						if (option['required']) {
							html += ' ';
						}
						
						html += option['name'] + '<br />';
						html += '<select name="option[' + option['product_option_id'] + ']">';
						html += '<option value=""><?php echo $text_select; ?></option>';
						
						for (j = 0; j < option['option_value'].length; j++) {
							option_value = option['option_value'][j];
							
							html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
							
							if (option_value['price']) {
								html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
							}
							
							html += '</option>';
						}
						
						html += '</select>';
						html += '</div>';
						html += '<br />';
					}
					
					if (option['type'] == 'checkbox') {
						html += '<div id="option-' + option['product_option_id'] + '">';
						
						if (option['required']) {
							html += ' ';
						}
						
						html += option['name'] + '<br />';
						
						for (j = 0; j < option['option_value'].length; j++) {
							option_value = option['option_value'][j];
							
							html += '<input type="checkbox" name="option[' + option['product_option_id'] + '][]" value="' + option_value['product_option_value_id'] + '" id="option-value-' + option_value['product_option_value_id'] + '" />';
							html += '<label for="option-value-' + option_value['product_option_value_id'] + '">' + option_value['name'];
							
							if (option_value['price']) {
								html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
							}
							
							html += '</label>';
							html += '<br />';
						}
						
						html += '</div>';
						html += '<br />';
					}
					
					if (option['type'] == 'image') {
						html += '<div id="option-' + option['product_option_id'] + '">';
						
						if (option['required']) {
							html += ' ';
						}
						
						html += option['name'] + '<br />';
						html += '<select name="option[' + option['product_option_id'] + ']">';
						html += '<option value=""><?php echo $text_select; ?></option>';
						
						for (j = 0; j < option['option_value'].length; j++) {
							option_value = option['option_value'][j];
							
							html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];
							
							if (option_value['price']) {
								html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
							}
							
							html += '</option>';
						}
						
						html += '</select>';
						html += '</div>';
						html += '<br />';
					}
					
					if (option['type'] == 'text') {
						html += '<div id="option-' + option['product_option_id'] + '">';
						
						if (option['required']) {
							html += ' ';
						}
						
						html += option['name'] + '<br />';
						html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" />';
						html += '</div>';
						html += '<br />';
					}
					
					if (option['type'] == 'textarea') {
						html += '<div id="option-' + option['product_option_id'] + '">';
						
						if (option['required']) {
							html += ' ';
						}
						
						html += option['name'] + '<br />';
						html += '<textarea name="option[' + option['product_option_id'] + ']" cols="40" rows="5">' + option['option_value'] + '</textarea>';
						html += '</div>';
						html += '<br />';
					}
					
					if (option['type'] == 'file') {
						html += '<div id="option-' + option['product_option_id'] + '">';
						
						if (option['required']) {
							html += ' ';
						}
						
						html += option['name'] + '<br />';
						html += '<a id="button-option-' + option['product_option_id'] + '" class="button"><?php echo $button_upload; ?></a>';
						html += '<input type="hidden" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" />';
						html += '</div>';
						html += '<br />';
					}
					
					if (option['type'] == 'date') {
						html += '<div id="option-' + option['product_option_id'] + '">';
						
						if (option['required']) {
							html += ' ';
						}
						
						html += option['name'] + '<br />';
						html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" class="date" />';
						html += '</div>';
						html += '<br />';
					}
					
					if (option['type'] == 'datetime') {
						html += '<div id="option-' + option['product_option_id'] + '">';
						
						if (option['required']) {
							html += ' ';
						}
						
						html += option['name'] + '<br />';
						html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" class="datetime" />';
						html += '</div>';
						html += '<br />';
					}
					
					if (option['type'] == 'time') {
						html += '<div id="option-' + option['product_option_id'] + '">';
						
						if (option['required']) {
							html += ' ';
						}
						
						html += option['name'] + '<br />';
						html += '<input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['option_value'] + '" class="time" />';
						html += '</div>';
						html += '<br />';
					}
				}
				
				$('#option').html('<td class="left"><?php echo $entry_option; ?></td><td class="left">' + html + '</td>');
				
				for (i = 0; i < ui.item.option.length; i++) {
					option = ui.item.option[i];
					
					if (option['type'] == 'file') {
						new AjaxUpload('#button-option-' + option['product_option_id'], {
							action: 'index.php?route=sale/order/upload&token=<?php echo $token; ?>',
							name: 'file',
							autoSubmit: true,
							responseType: 'json',
							data: option,
							onSubmit: function(file, extension) {
								$('#button-option-' + (this._settings.data['product_option_id'] + '-' + this._settings.data['product_option_id'])).after('<img src="view/image/loading.gif" class="loading" />');
							},
							onComplete: function(file, json) {
								
								$('.error').remove();
								
								if (json['success']) {
									alert(json['success']);
									
									$('input[name=\'option[' + this._settings.data['product_option_id'] + ']\']').attr('value', json['file']);
								}
								
								if (json.error) {
									$('#option-' + this._settings.data['product_option_id']).after('<span class="error">' + json['error'] + '</span>');
								}
								
								$('.loading').remove();
							}
						});
					}
				}
				
				$('.date').datepicker({dateFormat: 'yy-mm-dd'});
				$('.datetime').datetimepicker({
					dateFormat: 'yy-mm-dd',
					timeFormat: 'h:m'
				});
				$('.time').timepicker({timeFormat: 'h:m'});
				} else {
				$('#option td').remove();
			}
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
//--></script>
<script type="text/javascript"><!--
	$('select[name=\'payment\']').bind('change', function() {
		if (this.value) {
			$('input[name=\'payment_method\']').attr('value', $('select[name=\'payment\'] option:selected').text());
			} else {
			$('input[name=\'payment_method\']').attr('value', '');
		}
		
		$('input[name=\'payment_code\']').attr('value', this.value);
	});
	
	$('select[name=\'payment_secondary\']').bind('change', function() {
		if (this.value) {
			$('input[name=\'payment_secondary_method\']').attr('value', $('select[name=\'payment_secondary\'] option:selected').text());
			} else {
			$('input[name=\'payment_secondary_method\']').attr('value', '');
		}
		
		$('input[name=\'payment_secondary_code\']').attr('value', this.value);
	});
	
	$('select[name=\'shipping\']').bind('change', function() {
		if (this.value) {
			$('input[name=\'shipping_method\']').attr('value', $('select[name=\'shipping\'] option:selected').text());
			} else {
			$('input[name=\'shipping_method\']').attr('value', '');
		}
		
		$('input[name=\'shipping_code\']').attr('value', this.value);
		getSMSTextAjax();
	});
//--></script>

<script>
	function getShippings(){
		data  = '#tab-customer input[type=\'text\'], #tab-customer input[type=\'hidden\'], #tab-customer input[type=\'radio\']:checked, #tab-customer input[type=\'checkbox\']:checked, #tab-customer select, #tab-customer textarea, ';
		data += '#tab-payment input[type=\'text\'], #tab-payment input[type=\'hidden\'], #tab-payment input[type=\'radio\']:checked, #tab-payment input[type=\'checkbox\']:checked, #tab-payment select, #tab-payment textarea, ';
		data += '#tab-shipping input[type=\'text\'], #tab-shipping input[type=\'hidden\'], #tab-shipping input[type=\'radio\']:checked, #tab-shipping input[type=\'checkbox\']:checked, #tab-shipping select, #tab-shipping textarea, ';
		
		$.ajax({
			url: '<?php echo $store_url_main; ?>index.php?route=checkout/getsp/getShippings&token=<?php echo $token; ?>',
			type: 'post',
			crossDomain: true,
			data: $(data),
			dataType: 'json',
			beforeSend: function(){
				$('#load_shippings i').addClass('fa-spinner fa-spin');
			},
			success: function(json) {
				
				if (json['shipping_method']) {
					html = '<option value=""><?php echo $text_select; ?></option>';
					
					for (i in json['shipping_method']) {
						html += '<optgroup label="' + json['shipping_method'][i]['title'] + '">';
						
						if (!json['shipping_method'][i]['error']) {
							for (j in json['shipping_method'][i]['quote']) {
								if (json['shipping_method'][i]['quote'][j]['code'] == $('input[name=\'shipping_code\']').attr('value')) {
									html += '<option value="' + json['shipping_method'][i]['quote'][j]['code'] + '" selected="selected">' + json['shipping_method'][i]['quote'][j]['title'] + '</option>';
									} else {
									html += '<option value="' + json['shipping_method'][i]['quote'][j]['code'] + '">' + json['shipping_method'][i]['quote'][j]['title'] + '</option>';
								}
							}
							} else {
							html += '<option value="" style="color: #F00;" disabled="disabled">' + json['shipping_method'][i]['error'] + '</option>';
						}
						
						html += '</optgroup>';
					}
					
					$('select[name=\'shipping\']').html(html);
					
					if ($('select[name=\'shipping\'] option:selected').attr('value')) {
						$('input[name=\'shipping_method\']').attr('value', $('select[name=\'shipping\'] option:selected').text());
						} else {
						$('input[name=\'shipping_method\']').attr('value', '');
					}
					
					$('input[name=\'shipping\']').attr('value', $('select[name=\'shipping\'] option:selected').attr('value'));
				}
				
				$('#load_shippings i').removeClass('fa-spinner fa-spin');
				$('#load_shippings').css('color', '#4ea24e');
				
				$('select[name=\'shipping\']').on('change',
				function(){
					var d = $('select[name=\'shipping\'] option:selected').text();
					$('#title_shipping').val(d);
				});
			},
			error: function(json){
				console.log(json);
			},
		});
	}
	
	$('#load_shippings').on('click', function(){
		getShippings();
	});
	
	function getPayments(){
		data  = '#tab-customer input[type=\'text\'], #tab-customer input[type=\'hidden\'], #tab-customer input[type=\'radio\']:checked, #tab-customer input[type=\'checkbox\']:checked, #tab-customer select, #tab-customer textarea, ';
		data += '#tab-payment input[type=\'text\'], #tab-payment input[type=\'hidden\'], #tab-payment input[type=\'radio\']:checked, #tab-payment input[type=\'checkbox\']:checked, #tab-payment select, #tab-payment textarea, ';
		data += '#tab-shipping input[type=\'text\'], #tab-shipping input[type=\'hidden\'], #tab-shipping input[type=\'radio\']:checked, #tab-shipping input[type=\'checkbox\']:checked, #tab-shipping select, #tab-shipping textarea, ';
		data += 'input#total_num, input#currency_code, input#store_id';
		
		$.ajax({
			url: '<?php echo $store_url_main; ?>index.php?route=checkout/getsp/getPayments&secondary=0&token=<?php echo $token; ?>',
			type: 'post',
			data: $(data),
			crossDomain: true,
			dataType: 'json',
			beforeSend: function(){
				$('#load_payments i').addClass('fa-spinner fa-spin');
			},
			success: function(json) {
				if (json['payment_method']) {
					html = '<option value=""><?php echo $text_select; ?></option>';
					
					for (i in json['payment_method']) {
						if (json['payment_method'][i]['code'] == $('input[name=\'payment_code\']').attr('value')) {
							html += '<option value="' + json['payment_method'][i]['code'] + '" selected="selected">' + json['payment_method'][i]['title'] + '</option>';
							} else {
							html += '<option value="' + json['payment_method'][i]['code'] + '">' + json['payment_method'][i]['title'] + '</option>';
						}
					}
					
					$('select[name=\'payment\']').html(html);
					
					if ($('select[name=\'payment\'] option:selected').attr('value')) {
						$('input[name=\'payment_method\']').attr('value', $('select[name=\'payment\'] option:selected').text());
						} else {
						$('input[name=\'payment_method\']').attr('value', '');
					}
					
					$('input[name=\'payment_code\']').attr('value', $('select[name=\'payment\'] option:selected').attr('value'));
				}
				$('#load_payments i').removeClass('fa-spinner fa-spin');
				$('#load_payments').css('color', '#4ea24e');
			},
			error: function(json){
				console.log(json);
			},
		});
	}
	
	$('#load_payments').on('click', function(){
		getPayments();
	});
	
	function getSecondaryPayments(){
		data  = '#tab-customer input[type=\'text\'], #tab-customer input[type=\'hidden\'], #tab-customer input[type=\'radio\']:checked, #tab-customer input[type=\'checkbox\']:checked, #tab-customer select, #tab-customer textarea, ';
		data += '#tab-payment input[type=\'text\'], #tab-payment input[type=\'hidden\'], #tab-payment input[type=\'radio\']:checked, #tab-payment input[type=\'checkbox\']:checked, #tab-payment select, #tab-payment textarea, ';
		data += '#tab-shipping input[type=\'text\'], #tab-shipping input[type=\'hidden\'], #tab-shipping input[type=\'radio\']:checked, #tab-shipping input[type=\'checkbox\']:checked, #tab-shipping select, #tab-shipping textarea, ';
		data += 'input#total_num, input#currency_code, input#store_id';
		
		$.ajax({
			url: '<?php echo $store_url_main; ?>index.php?route=checkout/getsp/getPayments&secondary=1&token=<?php echo $token; ?>',
			type: 'post',
			data: $(data),
			crossDomain: true,
			dataType: 'json',
			beforeSend: function(){
				$('#load_secondary_payments i').addClass('fa-spinner fa-spin');
			},
			success: function(json) {
				if (json['payment_method']) {
					html = '<option value=""><?php echo $text_select; ?></option>';
					
					for (i in json['payment_method']) {
						if (json['payment_method'][i]['code'] == $('input[name=\'payment_secondary_code\']').attr('value')) {
							html += '<option value="' + json['payment_method'][i]['code'] + '" selected="selected">' + json['payment_method'][i]['title'] + '</option>';
							} else {
							html += '<option value="' + json['payment_method'][i]['code'] + '">' + json['payment_method'][i]['title'] + '</option>';
						}
					}
					
					$('select[name=\'payment_secondary\']').html(html);
					
					if ($('select[name=\'payment_secondary\'] option:selected').attr('value')) {
						$('input[name=\'payment_secondary_method\']').attr('value', $('select[name=\'payment_secondary\'] option:selected').text());
						} else {
						$('input[name=\'payment_secondary_method\']').attr('value', '');
					}
					
					$('input[name=\'payment_secondary_code\']').attr('value', $('select[name=\'payment_secondary\'] option:selected').attr('value'));
				}
				
				$('#load_secondary_payments i').removeClass('fa-spinner fa-spin');
				$('#load_secondary_payments').css('color', '#4ea24e');
			},
			error: function(json){
				console.log(json);
			},
		});
	}
	
	$('#load_secondary_payments').on('click', function(){
		getSecondaryPayments();
	});
	
	
	function reloadAllShippingsAndPayments(){
		getShippings();
		getPayments();
		getSecondaryPayments();
	}
	
	$(document).ready(function(){
		reloadAllShippingsAndPayments();
	});
</script>
<script type="text/javascript"><!--
	$('#invoice-generate').on('click', function() {
		$.ajax({
			url: 'index.php?route=sale/order/createinvoiceno&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			dataType: 'json',
			beforeSend: function() {
				$('#invoice').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				$('.success, .warning').remove();
				
				if (json['error']) {
					$('#tab-order').prepend('<div class="warning" style="display: none;">' + json['error'] + '</div>');
					
					$('.warning').fadeIn('slow');
				}
				
				if (json.invoice_no) {
					$('#invoice').fadeOut('slow', function() {
						$('#invoice').html(json['invoice_no']);
						
						$('#invoice').fadeIn('slow');
					});
				}
			}
		});
	});
	
	$('#credit-add').on('click', function() {
		$.ajax({
			url: 'index.php?route=sale/order/addcredit&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				$('#credit').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				$('.success, .warning').remove();
				
				if (json['error']) {
					$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
					
					$('.warning').fadeIn('slow');
				}
				
				if (json['success']) {
					$('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
					
					$('.success').fadeIn('slow');
					
					$('#credit').html('<b>[</b> <a id="credit-remove"><?php echo $text_credit_remove; ?></a> <b>]</b>');
				}
			}
		});
	});
	
	$('#credit-remove').on('click', function() {
		$.ajax({
			url: 'index.php?route=sale/order/removecredit&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				$('#credit').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				$('.success, .warning').remove();
				
				if (json['error']) {
					$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
					
					$('.warning').fadeIn('slow');
				}
				
				if (json['success']) {
					$('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
					
					$('.success').fadeIn('slow');
					
					$('#credit').html('<b>[</b> <a id="credit-add"><?php echo $text_credit_add; ?></a> <b>]</b>');
				}
			}
		});
	});
	
	$('#reward-add').on('click', function() {
		$.ajax({
			url: 'index.php?route=sale/order/addreward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				$('#reward').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				$('.success, .warning').remove();
				
				if (json['error']) {
					$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
					
					$('.warning').fadeIn('slow');
				}
				
				if (json['success']) {
					$('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
					
					$('.success').fadeIn('slow');
					
					$('#reward').html('<b>[</b> <a id="reward-remove"><?php echo $text_reward_remove; ?></a> <b>]</b>');
				}
			}
		});
	});
	
	$('#reward-remove').on('click', function() {
		$.ajax({
			url: 'index.php?route=sale/order/removereward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				$('#reward').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				$('.success, .warning').remove();
				
				if (json['error']) {
					$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
					
					$('.warning').fadeIn('slow');
				}
				
				if (json['success']) {
					$('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
					
					$('.success').fadeIn('slow');
					
					$('#reward').html('<b>[</b> <a id="reward-add"><?php echo $text_reward_add; ?></a> <b>]</b>');
				}
			}
		});
	});
	
	$('#commission-add').on('click', function() {
		$.ajax({
			url: 'index.php?route=sale/order/addcommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				$('#commission').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				$('.success, .warning').remove();
				
				if (json['error']) {
					$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
					
					$('.warning').fadeIn('slow');
				}
				
				if (json['success']) {
					$('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
					
					$('.success').fadeIn('slow');
					
					$('#commission').html('<b>[</b> <a id="commission-remove"><?php echo $text_commission_remove; ?></a> <b>]</b>');
				}
			}
		});
	});
	
	$('#commission-remove').on('click', function() {
		$.ajax({
			url: 'index.php?route=sale/order/removecommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				$('#commission').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
			},
			complete: function() {
				$('.loading').remove();
			},
			success: function(json) {
				$('.success, .warning').remove();
				
				if (json['error']) {
					$('.box').before('<div class="warning" style="display: none;">' + json['error'] + '</div>');
					
					$('.warning').fadeIn('slow');
				}
				
				if (json['success']) {
					$('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
					
					$('.success').fadeIn('slow');
					
					$('#commission').html('<b>[</b> <a id="commission-add"><?php echo $text_commission_add; ?></a> <b>]</b>');
				}
			}
		});
	});
	
	<? $can_delete = (in_array($this->user->getUserGroup(), array(1)) || $this->user->getIsMM()); ?>
	<? if ($can_delete) { ?>
		function setHistoryDeleteTriggers(){
			console.log('setHistoryDeleteTriggers');
			$('body').on('click', '#delete-history', function() {
				var ohd = $(this).attr("data-number")
				swal({
					title: "Вы уверены?",
					text: "Удаление истории необратимо!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#F96E64",
					cancelButtonText: "Отменить",
					confirmButtonText: "Удалить!",
					closeOnConfirm: true
				},
				function(){
					$.ajax({
						url: 'index.php?route=sale/order/delete_order_history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&order_history_id='+ohd,
						type: 'post',
						dataType: 'html',
						data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + encodeURIComponent($('input[name=\'notify\']').attr('checked') ? 1 : 0) + '&append=' + encodeURIComponent($('input[name=\'append\']').attr('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
						beforeSend: function() {
							$('.success, .warning').remove();
							$('#button-history').attr('disabled', true);
							$('#history').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
						},
						complete: function() {
							$('#button-history').attr('disabled', false);
							$('.attention').remove();
						},
						success: function(html) {
							$('#history').html(html);
							setHistoryEditTriggers();
							$('textarea[name=\'comment\']').val('');
							$.ajax({
								url: 'index.php?route=sale/order/getOrderHistoryLastStatus&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
								type: 'post',
								dataType: 'json',
								success: function(json) {
									$('#order-status').html(json);
								}
							});
						}
					});
				});
			});
		}
		setHistoryDeleteTriggers();
	<? } ?>
	
	function reloadOrderManager(){
		$.ajax({
			url: 'index.php?route=sale/order/reloadManagerAjax&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			dataType: 'json',
			type: 'get',
			success: function(json) {
				$('#manager_of_order').text(json.name);
			}
		});
	}
	
	$('#history .pagination a').on('click', function() {
		$('#history').load(this.href);
		setHistoryEditTriggers();
		return false;
	});
	
	<? $can_delete = (in_array($this->user->getUserGroup(), array(1)) || $this->user->getIsMM()); ?>
	
	
	$('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>', function(){ setHistoryEditTriggers(); 	<? if ($can_delete) { ?> setHistoryDeleteTriggers(); <?php } ?> });
	$('#order_sms_history').load('index.php?route=sale/order/smshistory&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
	$('#order_courier_history').load('index.php?route=sale/order/courierhistory&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
	$('#order_email_history').load('index.php?route=sale/order/emailhistory&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
	$('#order_invoice_history').load('index.php?route=sale/order/invoicehistory&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
	$('#order_save_history').load('index.php?route=sale/order/savehistory&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
	$('#order_call_history').load('index.php?route=sale/order/callhistory&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>', function(){ setAudioTrigger(); });
	
	$('#button-history').on('click', function() {
		/*
			if(typeof CKEDITOR !== "undefined")
			CKEDITOR.instances.hcomment.updateElement();
		*/
		if(typeof verifyStatusChange == 'function'){
			if(verifyStatusChange() == false){
				return false;
				}else{
				addOrderInfo();
			}
			}else{
			addOrderInfo();
		}
		
		$.ajax({
			url: 'index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			type: 'post',
			dataType: 'html',
			data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id_tc\']').val()) <?php if(!empty($products)){ ?>+ ($('input[name=\'show_products\']').attr('checked') ? '&show_products=1' : '')<?php }
				if(!empty($downloads)){ ?>+ ($('input[name=\'show_downloads\']').attr('checked') ? '&show_downloads=1' : '')<?php }
				if(!empty($vouchers)){ ?>+ ($('input[name=\'show_vouchers\']').attr('checked') ? '&show_vouchers=1' : '')<?php }
			if(!empty($totals)){ ?>+ ($('input[name=\'show_totals\']').attr('checked') ? '&show_totals=1' : '')<?php } ?>
			+ ($('input[name=\'attach_invoice_pdf\']').attr('checked') ? '&attach_invoice_pdf=1' : '')
			+ ($('select[name=\'field_template\']').val() ? '&field_template=' + $('select[name=\'field_template\']').val() : '')
			+ '&notify=' + encodeURIComponent($('input[name=\'notify\']').attr('checked') ? 1 : 0)
			+ '&courier=' + encodeURIComponent($('input[name=\'courier\']').attr('checked') ? 1 : 0)
			+ '&append=' + encodeURIComponent($('input[name=\'append\']').attr('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'hcomment\']').val())
			+ '&history_sms_text='+ encodeURIComponent($('textarea[name=\'history_sms_text\']').val())
			+ '&telephone='+encodeURIComponent($('input[name=\'telephone\']').val())
			+'&reject_reason_id='+encodeURIComponent($('input[name=\'reject_reason_id\']:checked').val()),
			beforeSend: function() {
				$('.success, .warning').remove();
				$('#button-history').attr('disabled', true);
				$('#history').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
			complete: function() {
				$('#button-history').attr('disabled', false);
				$('.attention').remove();
				$('#transactionorder').load('index.php?route=sale/customer/transactionorder&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>&order_id=<?php echo $order_id; ?>');
				$('#transaction').load('index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');
			},
			success: function(html) {
				$('#history').html(html);
				/*
					if(typeof CKEDITOR !== "undefined")
					CKEDITOR.instances.hcomment.setData('');
				*/
				$('.emailOptions input[type=checkbox], .emailOptions input[type=radio], input[name=notify], input[name=courier]').removeAttr('checked');
				$('.emailOptions option').removeAttr('selected');
				$('.emailOptions').hide();
				//	$('textarea[name=\'comment\']').val('');
				$('#statuses_select_td').load('index.php?route=sale/order/reload_statuses&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
				$('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());
				$('#order_sms_history').load('index.php?route=sale/order/smshistory&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
				$('#order_email_history').load('index.php?route=sale/order/emailhistory&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
				$('#order_save_history').load('index.php?route=sale/order/savehistory&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
				setHistoryEditTriggers();
				reloadOrderManager();
			}
		});
	});
//--></script>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
	(function($){
		
		// Order history show/hide summary options
		function showEmailOptions($row, $checkbox){
			if($checkbox.is(':checked')) {
				//	$row.show();
				} else {
				$row.hide();
			}
		}
		
		
		$(document).ready(function() {
			$('input[name=notify]').eq(0).each(function(){
				showEmailOptions($('.emailOptions'), $(this));
				}).change(function(){
				showEmailOptions($('.emailOptions'), $(this));
			});
			
			$('select#field_templates').change(function(){
				var val = $(this).val();
				if (!val || !confirm("<?php echo $warning_template_content; ?>")) return;
				$.ajax({
					url: '<?php echo html_entity_decode($templates_action); ?>',
					type: 'get',
					data: 'id=' + val + '&store_id=<?php echo $store_id; ?>' + '&language_id=<?php echo $language_id; ?>' + '&order_id=<?php echo $order_id; ?>',
					dataType: 'html',
					success: function(html) {
						$('textarea[name=hcomment]').val(html);
						/*
							if(typeof CKEDITOR !== "undefined"){
							CKEDITOR.instances['hcomment'].setData(html);
							}
						*/
					},
					error: function(xhr, ajaxOptions, thrownError) {
						console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						alert('Error. More details in console.');
					}
				});
			});
		}); // doc.ready
	})(jQuery);
	
	if(typeof CKEDITOR !== "undefined"){
		CKEDITOR.replace('bottom_text', {
			height:450,
			readOnly:false,
			filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			toolbarGroups: [
			{"name":"basicstyles","groups":["basicstyles"]},
			{"name":"links","groups":["links"]},
			{"name":"paragraph","groups":["list","blocks"]},
			{"name":"document","groups":["mode"]},
			{"name":"insert","groups":["insert"]},
			{"name":"styles","groups":["styles"]},
			{"name":"about","groups":["about"]}
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: ''
		});											
		CKEDITOR.on('instanceReady', function (ev) {
			ev.editor.dataProcessor.htmlFilter.addRules({
				elements: {
					$: function(element){
						if (element.name == 'img') {
							var style = element.attributes.style;
							
							if (style) {
								// Get the width from the style.
								var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style),
								width = match && match[1];
								
								// Get the height from the style.
								match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
								var height = match && match[1];
								
								if (width) {
									element.attributes.style = element.attributes.style.replace(/(?:^|\s)width\s*:\s*(\d+)px;?/i, '');
									element.attributes.width = width;
								}
								
								if (height) {
									element.attributes.style = element.attributes.style.replace(/(?:^|\s)height\s*:\s*(\d+)px;?/i, '');
									element.attributes.height = height;
								}
							}
						}
						
						if (!element.attributes.style) delete element.attributes.style;
						
						return element;
					}
				}
			});
		});
	} // CKEDITOR
//--></script>
<script type="text/javascript" src="view/javascript/order_page.js"></script>