<script type="text/javascript"><!--
$('#button-product, #button-voucher, #button-update').live('click', function() {	
	data  = '#tab-customer input[type=\'text\'], #tab-customer input[type=\'hidden\'], #tab-customer input[type=\'radio\']:checked, #tab-customer input[type=\'checkbox\']:checked, #tab-customer select, #tab-customer textarea, ';
	data += '#tab-payment input[type=\'text\'], #tab-payment input[type=\'hidden\'], #tab-payment input[type=\'radio\']:checked, #tab-payment input[type=\'checkbox\']:checked, #tab-payment select, #tab-payment textarea, ';
	data += '#tab-shipping input[type=\'text\'], #tab-shipping input[type=\'hidden\'], #tab-shipping input[type=\'radio\']:checked, #tab-shipping input[type=\'checkbox\']:checked, #tab-shipping select, #tab-shipping textarea, ';
	
	if ($(this).attr('id') == 'button-product') {
		data += '#tab-product input[type=\'text\'], #tab-product input[type=\'hidden\'], #tab-product input[type=\'radio\']:checked, #tab-product input[type=\'checkbox\']:checked, #tab-product select, #tab-product textarea, ';
	} else {
		data += '#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea, ';
	}
	
	if ($(this).attr('id') == 'button-voucher') {
		data += '#tab-voucher input[type=\'text\'], #tab-voucher input[type=\'hidden\'], #tab-voucher input[type=\'radio\']:checked, #tab-voucher input[type=\'checkbox\']:checked, #tab-voucher select, #tab-voucher textarea, ';
	} else {
		data += '#voucher input[type=\'text\'], #voucher input[type=\'hidden\'], #voucher input[type=\'radio\']:checked, #voucher input[type=\'checkbox\']:checked, #voucher select, #voucher textarea, ';
	}
	
	data += '#tab-total input[type=\'text\'], #tab-total input[type=\'hidden\'], #tab-total input[type=\'radio\']:checked, #tab-total input[type=\'checkbox\']:checked, #tab-total select, #tab-total textarea';

	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=checkout/manual&token=<?php echo $token; ?>',
		type: 'post',
		data: $(data),
		dataType: 'json',	
		beforeSend: function() {
			$('.success, .warning, .attention, .error').remove();
			
			$('.box').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},			
		success: function(json) {
			$('.success, .warning, .attention, .error').remove();
			
			// Check for errors
			if (json['error']) {
				if (json['error']['warning']) {
					$('.box').before('<div class="warning">' + json['error']['warning'] + '</div>');
				}
							
				// Order Details
				if (json['error']['customer']) {
					$('.box').before('<span class="error">' + json['error']['customer'] + '</span>');
				}	
								
				if (json['error']['firstname']) {
					$('input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}
				
				if (json['error']['lastname']) {
					$('input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}	
				
				if (json['error']['email']) {
					$('input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
				}
				
				if (json['error']['telephone']) {
					$('input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}	
			
				// Payment Address
				if (json['error']['payment']) {	
					if (json['error']['payment']['firstname']) {
						$('input[name=\'payment_firstname\']').after('<span class="error">' + json['error']['payment']['firstname'] + '</span>');
					}
					
					if (json['error']['payment']['lastname']) {
						$('input[name=\'payment_lastname\']').after('<span class="error">' + json['error']['payment']['lastname'] + '</span>');
					}	
					
					if (json['error']['payment']['address_1']) {
						$('input[name=\'payment_address_1\']').after('<span class="error">' + json['error']['payment']['address_1'] + '</span>');
					}	
					
					if (json['error']['payment']['city']) {
						$('input[name=\'payment_city\']').after('<span class="error">' + json['error']['payment']['city'] + '</span>');
					}	
																								
					if (json['error']['payment']['country']) {
						$('select[name=\'payment_country_id\']').after('<span class="error">' + json['error']['payment']['country'] + '</span>');
					}	
					
					if (json['error']['payment']['zone']) {
						$('select[name=\'payment_zone_id\']').after('<span class="error">' + json['error']['payment']['zone'] + '</span>');
					}
					
					if (json['error']['payment']['postcode']) {
						$('input[name=\'payment_postcode\']').after('<span class="error">' + json['error']['payment']['postcode'] + '</span>');
					}						
				}
			
				// Shipping	Address
				if (json['error']['shipping']) {		
					if (json['error']['shipping']['firstname']) {
						$('input[name=\'shipping_firstname\']').after('<span class="error">' + json['error']['shipping']['firstname'] + '</span>');
					}
					
					if (json['error']['shipping']['lastname']) {
						$('input[name=\'shipping_lastname\']').after('<span class="error">' + json['error']['shipping']['lastname'] + '</span>');
					}	
					
					if (json['error']['shipping']['address_1']) {
						$('input[name=\'shipping_address_1\']').after('<span class="error">' + json['error']['shipping']['address_1'] + '</span>');
					}	
					
					if (json['error']['shipping']['city']) {
						$('input[name=\'shipping_city\']').after('<span class="error">' + json['error']['shipping']['city'] + '</span>');
					}	
																								
					if (json['error']['shipping']['country']) {
						$('select[name=\'shipping_country_id\']').after('<span class="error">' + json['error']['shipping']['country'] + '</span>');
					}	
					
					if (json['error']['shipping_zone']) {
						$('select[name=\'shipping_zone_id\']').after('<span class="error">' + json['error']['shipping']['zone'] + '</span>');
					}
					
					if (json['error']['shipping']['postcode']) {
						$('input[name=\'shipping_postcode\']').after('<span class="error">' + json['error']['shipping']['postcode'] + '</span>');
					}	
				}
				
				// Products
				if (json['error']['product']) {
					if (json['error']['product']['option']) {	
						for (i in json['error']['product']['option']) {
							$('#option-' + i).after('<span class="error">' + json['error']['product']['option'][i] + '</span>');
						}						
					}
					
					if (json['error']['product']['stock']) {
						$('.box').before('<div class="warning">' + json['error']['product']['stock'] + '</div>');
					}	
											
					if (json['error']['product']['minimum']) {	
						for (i in json['error']['product']['minimum']) {
							$('.box').before('<div class="warning">' + json['error']['product']['minimum'][i] + '</div>');
						}						
					}
				} else {
					$('input[name=\'product\']').attr('value', '');
					$('input[name=\'product_id\']').attr('value', '');
					$('#option td').remove();			
					$('input[name=\'quantity\']').attr('value', '1');			
				}
				
				// Voucher
				if (json['error']['vouchers']) {
					if (json['error']['vouchers']['from_name']) {
						$('input[name=\'from_name\']').after('<span class="error">' + json['error']['vouchers']['from_name'] + '</span>');
					}	
					
					if (json['error']['vouchers']['from_email']) {
						$('input[name=\'from_email\']').after('<span class="error">' + json['error']['vouchers']['from_email'] + '</span>');
					}	
								
					if (json['error']['vouchers']['to_name']) {
						$('input[name=\'to_name\']').after('<span class="error">' + json['error']['vouchers']['to_name'] + '</span>');
					}	
					
					if (json['error']['vouchers']['to_email']) {
						$('input[name=\'to_email\']').after('<span class="error">' + json['error']['vouchers']['to_email'] + '</span>');
					}	
					
					if (json['error']['vouchers']['amount']) {
						$('input[name=\'amount\']').after('<span class="error">' + json['error']['vouchers']['amount'] + '</span>');
					}	
				} else {
					$('input[name=\'from_name\']').attr('value', '');	
					$('input[name=\'from_email\']').attr('value', '');	
					$('input[name=\'to_name\']').attr('value', '');
					$('input[name=\'to_email\']').attr('value', '');	
					$('textarea[name=\'message\']').attr('value', '');	
					$('input[name=\'amount\']').attr('value', '25.00');
				}
				
				// Shipping Method	
				if (json['error']['shipping_method']) {
					$('.box').before('<div class="warning">' + json['error']['shipping_method'] + '</div>');
				}	
				
				// Payment Method
				if (json['error']['payment_method']) {
					$('.box').before('<div class="warning">' + json['error']['payment_method'] + '</div>');
				}	
															
				// Coupon
				if (json['error']['coupon']) {
					$('.box').before('<div class="warning">' + json['error']['coupon'] + '</div>');
				}
				
				// Voucher
				if (json['error']['voucher']) {
					$('.box').before('<div class="warning">' + json['error']['voucher'] + '</div>');
				}
				
				// Reward Points		
				if (json['error']['reward']) {
					$('.box').before('<div class="warning">' + json['error']['reward'] + '</div>');
				}	
			} else {
				$('input[name=\'product\']').attr('value', '');
				$('input[name=\'product_id\']').attr('value', '');
				$('#option td').remove();	
				$('input[name=\'quantity\']').attr('value', '1');	
				
				$('input[name=\'from_name\']').attr('value', '');	
				$('input[name=\'from_email\']').attr('value', '');	
				$('input[name=\'to_name\']').attr('value', '');
				$('input[name=\'to_email\']').attr('value', '');	
				$('textarea[name=\'message\']').attr('value', '');	
				$('input[name=\'amount\']').attr('value', '25.00');									
			}

			if (json['success']) {
				$('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');

			scroll(0,0);
			
				
				$('.success').fadeIn('slow');				
			}
			
			if (json['order_product'] != '') {
				var product_row = 0;
				var option_row = 0;
				var download_row = 0;
	
				html = '';
				
				for (i = 0; i < json['order_product'].length; i++) {
					product = json['order_product'][i];
					
					html += '<tr id="product-row' + product_row + '">';

			html +='   <td class="left">'
			if (product['image']) {
			html +=' <img src="' + product['image'] + '" style="padding: 1px; border: 1px solid #DDDDDD;">';
			}
			html +=' </td>';
			
					html += '  <td class="center" style="width: 3px;"><img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#product-row' + product_row + '\').remove(); $(\'#button-update\').trigger(\'click\');" /></td>';
					html += '  <td class="left">' + product['name'] + '<br /><input type="hidden" name="order_product[' + product_row + '][order_product_id]" value="" /><input type="hidden" name="order_product[' + product_row + '][product_id]" value="' + product['product_id'] + '" /><input type="hidden" name="order_product[' + product_row + '][name]" value="' + product['name'] + '" />';
					
					if (product['option']) {
						for (j = 0; j < product['option'].length; j++) {
							option = product['option'][j];
							
							html += '  - <small>' + option['name'] + ': ' + option['value'] + '</small><br />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_option][' + option_row + '][order_option_id]" value="' + option['order_option_id'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_option][' + option_row + '][product_option_id]" value="' + option['product_option_id'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_option][' + option_row + '][product_option_value_id]" value="' + option['product_option_value_id'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_option][' + option_row + '][name]" value="' + option['name'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_option][' + option_row + '][value]" value="' + option['value'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_option][' + option_row + '][type]" value="' + option['type'] + '" />';
							
							option_row++;
						}
					}
					
					if (product['download']) {
						for (j = 0; j < product['download'].length; j++) {
							download = product['download'][j];
							
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_download][' + download_row + '][order_download_id]" value="' + download['order_download_id'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_download][' + download_row + '][name]" value="' + download['name'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_download][' + download_row + '][filename]" value="' + download['filename'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_download][' + download_row + '][mask]" value="' + download['mask'] + '" />';
							html += '  <input type="hidden" name="order_product[' + product_row + '][order_download][' + download_row + '][remaining]" value="' + download['remaining'] + '" />';
							
							download_row++;
						}
					}
					
					html += '  </td>';
					html += '  <td class="left">' + product['model'] + '<input type="hidden" name="order_product[' + product_row + '][model]" value="' + product['model'] + '" /></td>';
					html += '  <td class="right">' + product['quantity'] + '<input type="hidden" name="order_product[' + product_row + '][quantity]" value="' + product['quantity'] + '" /></td>';
					html += '  <td class="right">' + product['price'] + '<input type="hidden" name="order_product[' + product_row + '][price]" value="' + product['price'] + '" /></td>';
					html += '  <td class="right">' + product['total'] + '<input type="hidden" name="order_product[' + product_row + '][total]" value="' + product['total'] + '" /><input type="hidden" name="order_product[' + product_row + '][tax]" value="' + product['tax'] + '" /><input type="hidden" name="order_product[' + product_row + '][reward]" value="' + product['reward'] + '" /></td>';
					html += '</tr>';
					
					product_row++;			
				}
				
				$('#product').html(html);
			} else {
				html  = '</tr>';
				html += '  <td colspan="6" class="center"><?php echo $text_no_results; ?></td>';
				html += '</tr>';	

				$('#product').html(html);	
			}
						
			// Vouchers
			if (json['order_voucher'] != '') {
				var voucher_row = 0;
				
				 html = '';
				 
				 for (i in json['order_voucher']) {
					voucher = json['order_voucher'][i];
					 
					html += '<tr id="voucher-row' + voucher_row + '">';
					html += '  <td class="center" style="width: 3px;"><img src="view/image/delete.png" title="<?php echo $button_remove; ?>" alt="<?php echo $button_remove; ?>" style="cursor: pointer;" onclick="$(\'#voucher-row' + voucher_row + '\').remove(); $(\'#button-update\').trigger(\'click\');" /></td>';
					html += '  <td class="left">' + voucher['description'];
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][order_voucher_id]" value="" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][voucher_id]" value="' + voucher['voucher_id'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][description]" value="' + voucher['description'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][code]" value="' + voucher['code'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][from_name]" value="' + voucher['from_name'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][from_email]" value="' + voucher['from_email'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][to_name]" value="' + voucher['to_name'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][to_email]" value="' + voucher['to_email'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][voucher_theme_id]" value="' + voucher['voucher_theme_id'] + '" />';	
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][message]" value="' + voucher['message'] + '" />';
					html += '  <input type="hidden" name="order_voucher[' + voucher_row + '][amount]" value="' + voucher['amount'] + '" />';
					html += '  </td>';
					html += '  <td class="left"></td>';
					html += '  <td class="right">1</td>';
					html += '  <td class="right">' + voucher['amount'] + '</td>';
					html += '  <td class="right">' + voucher['amount'] + '</td>';
					html += '</tr>';	
				  
					voucher_row++;
				}
				  
				$('#voucher').html(html);				
			} else {
				html  = '</tr>';
				html += '  <td colspan="6" class="center"><?php echo $text_no_results; ?></td>';
				html += '</tr>';	

				$('#voucher').html(html);	
			}
						
			// Totals
			if (json['order_product'] != '' || json['order_voucher'] != '' || json['order_total'] != '') {
				html = '';
				
				if (json['order_product'] != '') {
					for (i = 0; i < json['order_product'].length; i++) {
						product = json['order_product'][i];
						
						html += '<tr>';
						html += '  <td class="left">' + product['name'] + '<br />';
						
						if (product['option']) {
							for (j = 0; j < product['option'].length; j++) {
								option = product['option'][j];
								
								html += '  - <small>' + option['name'] + ': ' + option['value'] + '</small><br />';
							}
						}
						
						html += '  </td>';
						html += '  <td class="left">' + product['model'] + '</td>';
						html += '  <td class="right">' + product['quantity'] + '</td>';
						html += '  <td class="right">' + product['price'] + '</td>';
						html += '  <td class="right">' + product['total'] + '</td>';
						html += '</tr>';
					}				
				}
				
				if (json['order_voucher'] != '') {
					for (i in json['order_voucher']) {
						voucher = json['order_voucher'][i];
						 
						html += '<tr>';
						html += '  <td class="left">' + voucher['description'] + '</td>';
						html += '  <td class="left"></td>';
						html += '  <td class="right">1</td>';
						html += '  <td class="right">' + voucher['amount'] + '</td>';
						html += '  <td class="right">' + voucher['amount'] + '</td>';
						html += '</tr>';	
					}	
				}
				
				var total_row = 0;
				
				for (i in json['order_total']) {
					total = json['order_total'][i];
					
					html += '<tr id="total-row' + total_row + '">';
					html += '  <td class="right" colspan="4"><input type="hidden" name="order_total[' + total_row + '][order_total_id]" value="" /><input type="hidden" name="order_total[' + total_row + '][code]" value="' + total['code'] + '" /><input type="hidden" name="order_total[' + total_row + '][title]" value="' + total['title'] + '" /><input type="hidden" name="order_total[' + total_row + '][text]" value="' + total['text'] + '" /><input type="hidden" name="order_total[' + total_row + '][value]" value="' + total['value'] + '" /><input type="hidden" name="order_total[' + total_row + '][sort_order]" value="' + total['sort_order'] + '" />' + total['title'] + ':</td>';
					html += '  <td class="right">' + total['value'] + '</td>';
					html += '</tr>';
					
					total_row++;
				}
				
				$('#total').html(html);
			} else {
				html  = '</tr>';
				html += '  <td colspan="5" class="center"><?php echo $text_no_results; ?></td>';
				html += '</tr>';	

				$('#total').html(html);					
			}
			
			// Shipping Methods
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
				
				$('input[name=\'shipping_code\']').attr('value', $('select[name=\'shipping\'] option:selected').attr('value'));	
			}
						
			// Payment Methods
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
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});
//--></script> 