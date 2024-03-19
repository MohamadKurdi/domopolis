<div class="simplecheckout-block checkout-steps__select" id="simplecheckout_shipping" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $display_error && $has_error ? 'data-error="true"' : '' ?>>
    
    <style>
        .row-shipping_courier_city_dadata_unrestricted_value{display:none!important;}
        .row-shipping_courier_city_dadata_beltway_hit{display:none!important;}
        .row-shipping_courier_city_dadata_beltway_distance{display:none!important;}
        .row-shipping_courier_city_dadata_geolocation{display:none!important;}
        .row-shipping_courier_city_dadata_postalcode{display:none!important;}
	</style>
    <?php if ($display_header) { ?>
        <!-- checkout-heading -->
        <div class=" checkout-steps__select-label"><?php echo $text_checkout_shipping_method ?></div>
	<?php } ?>
    <div class="simplecheckout-warning-block" <?php echo $display_error && $has_error_shipping ? '' : 'style="display:none"' ?>><?php echo $error_shipping ?></div>
    <div class="simplecheckout-block-content checkout-steps__select-right">
        <?php if (!empty($shipping_methods)) { ?>
            <?php if ($display_type == 2 ) { ?>
                <?php $current_method = false; ?>
                <select data-onchange="reloadAll" name="shipping_method">
                    <option value="" disabled="disabled" <?php if (empty($code)) { ?>selected="selected"<?php } ?>><?php echo $text_select; ?></option>
                    <?php foreach ($shipping_methods as $shipping_method) { ?>
                        <?php if (!empty($shipping_method['title'])) { ?>
                            <optgroup label="<?php echo $shipping_method['title']; ?>">
							<?php } ?>
                            <?php if (empty($shipping_method['error'])) { ?>
                                <?php foreach ($shipping_method['quote'] as $quote) { ?>
                                    <option value="<?php echo $quote['code']; ?>" <?php echo !empty($quote['dummy']) ? 'disabled="disabled"' : '' ?> <?php echo !empty($quote['dummy']) ? 'data-dummy="true"' : '' ?> <?php if ($quote['code'] == $code) { ?>selected="selected"<?php } ?>><?php echo $quote['title']; ?><?php echo !empty($quote['text']) ? ' - '.$quote['text'] : ''; ?></option>
                                    <?php if ($quote['code'] == $code) { $current_method = $quote; } ?>
								<?php } ?>
                                <?php } else { ?>
                                <option value="<?php echo $shipping_method['code']; ?>" disabled="disabled"><?php echo $shipping_method['error']; ?></option>
							<?php } ?>
                            <?php if (!empty($shipping_method['title'])) { ?>
							</optgroup>
						<?php } ?>
					<?php } ?>
				</select>
                <?php if ($current_method) { ?>
                    <?php if (!empty($current_method['description'])) { ?>
                        <div class="simplecheckout-methods-description"><?php echo $current_method['description']; ?></div>
					<?php } ?>
                    <?php if (!empty($rows)) { ?>
                        <table class="simplecheckout-methods-table">
                            <tr>
                                <td colspan="2">
                                    <?php foreach ($rows as $row) { ?>
                                        <?php echo $row ?>
									<?php } ?>
								</td>
							</tr>
						</table>
					<?php } ?>
				<?php } ?>
                <?php } else { ?>
                <div class="simplecheckout-methods-table">
                    <?php foreach ($shipping_methods as $shipping_method) { ?>
                        <?php if (!empty($shipping_method['title'])) { ?>
                            <tr>
                                <td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
							</tr>
						<?php } ?>
                        <?php if (!empty($shipping_method['warning'])) { ?>
                            <tr>
                                <td colspan="2"><div class="simplecheckout-error-text"><?php echo $shipping_method['warning']; ?></div></td>
							</tr>
						<?php } ?>
                        <?php if (empty($shipping_method['error'])) { ?>
                            <?php foreach ($shipping_method['quote'] as $quote) { ?>
                                <div class="simplecheckout-methods-items">
                                    <div class="title" valign="middle">
                                    	<input type="radio" data-onchange="reloadAll" name="shipping_method" <?php echo !empty($quote['dummy']) ? 'disabled="disabled"' : '' ?> <?php echo !empty($quote['dummy']) ? 'data-dummy="true"' : '' ?> value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" <?php if ($quote['code'] == $code) { ?>checked="checked"<?php } ?> />
                                        <label for="<?php echo $quote['code']; ?>">
                                            <?php echo !empty($quote['title']) ? $quote['title'] : ''; ?>																				
										</label>
										<span class="quote"><?php echo !empty($quote['text']) ? $quote['text'] : ''; ?></span>
									</div>
								</div>
                                <?php if ($quote['code'] == $code && !empty($rows)) { ?>
                                    <tr>
                                        <td colspan="2">
                                            <?php foreach ($rows as $row) { ?>
                                                <?php echo $row ?>
											<?php } ?>
										</td>
									</tr>
								<?php } ?>
							<?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td colspan="2"><div class="simplecheckout-error-text"><?php echo $shipping_method['error']; ?></div></td>
							</tr>
						<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>
            <input type="hidden" name="shipping_method_current" value="<?php echo $code ?>" />
            <input type="hidden" name="shipping_method_checked" value="<?php echo $checked_code ?>" />
		<?php } ?>
        <?php if (empty($shipping_methods) && $address_empty && $display_address_empty) { ?>
            <div class="simplecheckout-warning-text"><?php echo $text_shipping_address; ?></div>
		<?php } ?>
        <?php if (empty($shipping_methods) && !$address_empty) { ?>
            <div class="simplecheckout-warning-text"><?php echo $error_no_shipping; ?></div>
		<?php } ?>
	</div>
</div>

<?php if ($this->config->get('config_dadata') == 'address') { ?>
	<style>
		.navigator-geo-init > span{border-bottom:1px dashed grey; cursor:pointer;}
	</style>
	<script>
		function initBrowserNavigationTrigger(){
			if (navigator.geolocation) {
				console.log('init location selector');
				
				if ($('#shipping_courier_city_shipping_address').length > 0){
					$('#shipping_courier_city_shipping_address').after('<div class="navigator-geo-init" data-for="shipping_courier_city_shipping_address"><span><i class="fas fa-map-marker-alt"></i> <?php echo $text_retranslate_detect_location; ?></span></div>');
				}
				
				if ($('#shipping_cdek_street').length > 0){
					$('#shipping_cdek_street').after('<div class="navigator-geo-init" data-for="shipping_cdek_street"><span><i class="fas fa-map-marker-alt"></i> <?php echo $text_retranslate_detect_location; ?></span></div>');
				}
				
				$('.navigator-geo-init').on('click', function(){
					var selector = $(this).attr('data-for');
					var elem = $(this);
					navigator.geolocation.getCurrentPosition(
					(position) => {
						
						jQuery.ajax({
							url: "index.php?route=kp/checkout/getAddressByGeoCoordinates",
							dataType: "json",
							type: "POST",
							data: {
								lat: position.coords.latitude,
								lon: position.coords.longitude
							},
							beforeSend: function(){
								elem.before('<span class="text-loading"><i class="fas fa-spinner fa-spin"></i> <?php echo $text_retranslate_27; ?></span>');								
							},
							success: function(json){
								
								elem.parent().children('.text-loading').remove();
								
								if (json.value != null && json.value){																											
									if (selector == 'shipping_courier_city_shipping_address'){
										$('input#shipping_courier_city_shipping_address').val(json.value);
										$('input#shipping_courier_city_dadata_postalcode').val(json.postal_code);
										$('input#shipping_courier_city_dadata_unrestricted_value').val(json.unrestricted_value);
										$('input#shipping_courier_city_dadata_beltway_hit').val(json.beltway_hit);
										$('input#shipping_courier_city_dadata_beltway_distance').val(json.beltway_distance);
										$('input#shipping_courier_city_dadata_geolocation').val(json.lat +', '+ json.lon);										
										
										} else if (selector == 'shipping_cdek_street'){
										$('input#shipping_cdek_street').val(json.value);
										$('input#shipping_cdek_house_number').val(json.house);
										
									}
									
									$('#' + selector).trigger('change');
									
									} else {
									elem.html('<i class="fas fa-sad-tear"></i> <?php echo $text_retranslate_no_location; ?>').addClass('simplecheckout-error-text');
								}						
							},
							error: function(){
								elem.html('<i class="fas fa-sad-tear"></i> <?php echo $text_retranslate_no_location; ?>').addClass('simplecheckout-error-text');								
							}
							
						});
					},
					(err) => { console.log('error getting position') },
					{
						enableHighAccuracy: true
					});
				});
				
				} else {
				console.log('pass init, no Navigation in Navigator');
			}
		}
		
		
		$(document).ready(function(){
			initBrowserNavigationTrigger();           
		});
	</script>
<?php } ?> 


<?php if ($this->config->get('config_dadata')) { ?>
    <script>        
        <?php if ($this->config->get('config_dadata') == 'address') { ?>
            function setFullAddressString(){
				if ($('input#shipping_courier_city_dadata_unrestricted_value').length > 0){
					let fullAddress = $('input#shipping_courier_city_dadata_unrestricted_value').val();
					if (fullAddress != 'undefined' && fullAddress.length){
						$('span#checkout_customer_main_address_1_full').html('<small style="font-size:12px;font-style:italic;color:#8A8A8A;">' + fullAddress + '</small>');
					}
				}
			}
            
            function initDaDataTriggersForCdekDelivery(){
                if ($('input#shipping_cdek_street').length == 0){
                    console.log('pass init initDaDataTriggersForCdekDelivery');
                    return;
				}
                
                var cdek_city_code = $('#shipping_address_cdek_city_guid').val();                
                
                if (cdek_city_code.length == 0){
                    console.log('pass init initDaDataTriggersForCdekDelivery - no CDEK ID');
                    return;
				}
                
                $('input#shipping_cdek_street').after('<br/><span id="shipping_cdek_street_1_full"></span>');
                
                console.log('init initDaDataTriggersForCdekDelivery');
                
                jQuery.ui.autocomplete.prototype._resizeMenu = function () {
                    var ul = this.menu.element;
					ul.outerWidth(this.element.outerWidth());
				}
				
				$('input#shipping_cdek_street').autocomplete({
					source: function( request, response ) {
						jQuery.ajax({
							url: "index.php?route=kp/checkout/suggestAddress",
							dataType: "json",
							data: {
								query: request.term,
								cdek_city_code: $('#shipping_address_cdek_city_guid').val()
							},
							beforeSend: function(){
								$('input#shipping_cdek_street').parent('div').addClass('simple-processing-dadata-request');
							},
							success: function( data ) {
								$('input#shipping_cdek_street').parent('div').removeClass('simple-processing-dadata-request');
								response( jQuery.map( data, function( item ) {                                 
									return {													
										label: item.value,
										value: item.value,
										unrestricted_value: item.unrestricted_value
									}
								}));
							}
						});
					},
					select: function( event, ui ) {
						if (ui.item.unrestricted_value != null){
							jQuery.ajax({
								url: "index.php?route=kp/checkout/suggestAddress",
								dataType: "json",
								data: {
									query: ui.item.unrestricted_value,
									exact: true
								},
								beforeSend: function(){
									$('span#shipping_cdek_street_1_full').html('<i class="fas fa-spinner fa-spin"></i> <?php echo $text_retranslate_27; ?>');
								},
								success: function( json ) {
									console.log(json);
									if (json.house != null && json.house){
										$('input#shipping_cdek_house_number').val(json.house);
									}
								},
								complete: function(){         
									$('span#shipping_cdek_street_1_full').html('<small>' + ui.item.unrestricted_value + '</small>');
									console.log('init Reload After DaData Changed');
									reloadAll();
								}
							});
						}
					},
					minLength: 2,
					delay: 300
				});
				
			}
			
			function initDaDataTriggersForDelivery(){
				
				if ($('input#shipping_courier_city_shipping_address').length == 0){
					console.log('pass init initDaDataTriggersForDelivery');
					return;
				}
				
				console.log('init initDaDataTriggersForDelivery');
				$('input#shipping_courier_city_shipping_address').after('<br/><span id="checkout_customer_main_address_1_full"></span>');                
				
				jQuery.ui.autocomplete.prototype._resizeMenu = function () {
					var ul = this.menu.element;
					ul.outerWidth(this.element.outerWidth());
				}                                
				
				$('input#shipping_courier_city_shipping_address').autocomplete({
					source: function( request, response ) {
						jQuery.ajax({
							url: "index.php?route=kp/checkout/suggestAddress",
							dataType: "json",
							data: {
								query: request.term,
								cdek_city_code: $('#shipping_address_cdek_city_guid').val()
							},
							success: function( data ) {
								response( jQuery.map( data, function( item ) {
									return {													
										label: item.value,
										value: item.value,
										unrestricted_value: item.unrestricted_value,
										postal_code: item.postal_code,
										city: item.city,
										geolocation: item.geolocation,
										beltway_hit: item.beltway_hit,
										beltway_distance: item.beltway_distance
									}
								}));
							}
						});
					},
					select: function( event, ui ) {
						if (ui.item.unrestricted_value != null){
							jQuery.ajax({
								url: "index.php?route=kp/checkout/suggestAddress",
								dataType: "json",
								data: {
									query: ui.item.unrestricted_value,
									exact: true
								},
								beforeSend: function(){
									$('span#checkout_customer_main_address_1_full').html('<i class="fas fa-spinner fa-spin"></i> <?php echo $text_retranslate_27; ?>');
								},
								success: function( json ) {
									if (json.value != null && json.value){
										$('input#shipping_courier_city_dadata_postalcode').val(json.postal_code);
										$('input#shipping_courier_city_dadata_unrestricted_value').val(json.unrestricted_value);
										$('input#shipping_courier_city_dadata_beltway_hit').val(json.beltway_hit);
										$('input#shipping_courier_city_dadata_beltway_distance').val(json.beltway_distance);
										$('input#shipping_courier_city_dadata_geolocation').val(json.geolocation);
									}
								},
								complete: function(){
									$('span#checkout_customer_main_address_1_full').html('<small>' + ui.item.unrestricted_value + '</small>');
									if ($('input#shipping_courier_city_dadata_beltway_hit').val() != ''){
										console.log('init Reload After DaData Changed');
										reloadAll();
									}
								}
							});
						}
					},
					minLength: 2,
					delay: 300
				});               
				
			}	
			<? } else { ?>
			function initDaDataTriggersForDelivery(){
			}
			function initDaDataTriggersForCdekDelivery(){
			}
			function setFullAddressString(){
			}
		<?php } ?>
		
		$(document).ready(function(){
			initDaDataTriggersForDelivery();
			initDaDataTriggersForCdekDelivery();
			setFullAddressString();
		});
	</script>
<?php } ?> 

<?php if ($this->config->get('config_country_id') != 220) { ?>
	<script>
		
		function initCDEKCounters(){		
			var cdek_city_code = $('#shipping_address_cdek_city_guid').val();
			
			if (!cdek_city_code){
				return false;
			}
			
			console.log('init initCDEKCounters');
			
			//СДЭК ПВЗ
			if ($("label[for='dostavkaplus.sh6']").length > 0 || $("label[for='dostavkaplus.sh17']").length > 0){
				jQuery.ajax({
					url: "index.php?route=kp/checkout/guessCDEKPriceAjax",
					dataType: "json",
					data: {
						type: '4',
						cdek_city_code: cdek_city_code
					},
					beforeSend: function(){
						
					},
					success: function(json){
						if (json.success && json.min_WW){
							$(".quote > label[for='dostavkaplus.sh6']").append("<br /><b>от " + json.min_WW + "</b>");
						}
						
						if (json.success && json.min_WD){
							$(".quote > label[for='dostavkaplus.sh17']").append("<br /><b>от " + json.min_WD + "</b>");
						}
						
						$('.total-shipping-info-price').remove();
						
						if (json.success && json.min_WW && $('input[name=shipping_method]:checked').val() == 'dostavkaplus.sh6'){
							console.log('CDEK sh6 is checked');
							$('div#total_shipping').children('span.simplecheckout-cart-total-value').append("<span class='total-shipping-info-price'><br /><small style='font-size:80%'>от " + json.min_WW + "</small></span>");					
						}
						
						if (json.success && json.min_WW && $('input[name=shipping_method]:checked').val() == 'dostavkaplus.sh17'){
							console.log('CDEK sh17 is checked');
							$('div#total_shipping').children('span.simplecheckout-cart-total-value').append("<span class='total-shipping-info-price'><br /><small style='font-size:80%'>от " + json.min_WD + "</small></span>");					
						}
					}
				});			
			}
		}
		
		$(document).ready(function(){
			initCDEKCounters();	
		});
		
	</script>
<?php } ?>


<?php if ($this->config->get('config_country_id') == 220) { ?>
	<script>
		function initPostCodeSearch(){
			if ($('input#shipping_address_novaposhta_city_guid').length == 0){
				console.log('pass init initPostCodeSearch');
				return;
			}
			
			if ($('input#shipping_address_novaposhta_city_guid').length == 0){
				console.log('pass init initPostCodeSearch - no NP GUID');
				return;
			}
			
			if ($('input#shipping_ukrpost_postcode').length > 0 && $('input#shipping_ukrpost_postcode').val().length > 0){
				console.log('pass init initPostCodeSearch - already have data');
				return;
			}
			
			console.log('init initPostCodeSearch');
			$('input#shipping_ukrpost_postcode').after('<br/><span id="shipping_ukrpost_postcode_full"></span>'); 
			
			jQuery.ajax({
				url: "index.php?route=kp/checkout/suggestPostCode",
				dataType: "json",
				data: {
					query: $('input#shipping_address_novaposhta_city_guid').val(),
				},
				beforeSend: function(){
					$('span#shipping_ukrpost_postcode_full').html('<i class="fas fa-spinner fa-spin"></i> <?php echo $text_retranslate_27; ?>');
				},
				success: function( json ) {
					if (json.postcode != null && json.postcode){
						$('input#shipping_ukrpost_postcode').val(json.postcode);
					}
					if (json.fulladdress != null && json.fulladdress){
						$('span#shipping_ukrpost_postcode_full').html('<small>' + json.fulladdress + '</small>');
					}
				}
			});
		}
		
		
		$(document).ready(function(){
            initPostCodeSearch();           
		});
	</script>
<?php } ?>
