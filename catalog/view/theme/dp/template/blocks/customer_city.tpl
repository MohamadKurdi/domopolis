<!--change-city-->
<? if (true) { ?>		
	<script>
		function doReloadProductPageTerms(){
			if (window.product_id){				
				if ($('#product_delivery_info_reloadable').length > 0){
					$('#product_delivery_info_reloadable').html('<i class="fas fa-spinner fa-spin"></i> <?php echo $delivery_to_city_temp_text; ?>');
					reloadAjaxReloadableElement($('#product_delivery_info_reloadable'));
				}
			}		
		}
		
		function setCustomerCityAjax(city, id){
			jQuery.ajax({
				url: "index.php?route=kp/checkout/setCustomerCityAjax",
				dataType: "json",
				type: "POST",
				data: {
					city: city,
					id: id
				},
				success: function(json){
					if (json.success){
						console.log('Success send city to remember controller.');
						$('#change-city-customer-city-name').text(city);
						$('#change-city-customer-city-name-selection-popup').hide();
						
						if (window.product_id){
							doReloadProductPageTerms();
							}
					}
				},
				error: function(error){
					console.log('Something bad happened. Sorry.');
				}
			});
		}
		
		function setCustomerCityWithNoIDAjax(city){
			jQuery.ajax({
				url: "index.php?route=kp/checkout/setCustomerCityWithNoIDAjax",
				dataType: "json",
				type: "POST",
				data: {
					city: city,
				},
				success: function(json){
					if (json.success){
					console.log('Success send city to no-id remember controller.');
					$('#change-city-customer-city-name').text(city);
					$('#change-city-customer-city-name-selection-popup').hide();
					
					if (window.product_id){
						doReloadProductPageTerms();
					}
				}
				},
				error: function(error){
					console.log('Something bad happened. Sorry.');
				}
			});
		}
	</script>
	
	<div class="change-city">
		<div class="change-city__btn">
			<svg width="14" height="20" viewBox="0 0 14 20" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M6.9987 0.833313C3.4512 0.833313 0.582031 3.70248 0.582031 7.24998C0.582031 8.84498 1.04036 10.3391 1.87453 11.6866C2.74536 13.0983 3.8912 14.3083 4.7712 15.72C5.20203 16.4075 5.5137 17.0491 5.8437 17.7916C6.08203 18.2958 6.27453 19.1666 6.9987 19.1666C7.72286 19.1666 7.91536 18.2958 8.14453 17.7916C8.4837 17.0491 8.7862 16.4075 9.21703 15.72C10.097 14.3175 11.2429 13.1075 12.1137 11.6866C12.957 10.3391 13.4154 8.84498 13.4154 7.24998C13.4154 3.70248 10.5462 0.833313 6.9987 0.833313ZM6.9987 9.77081C5.7337 9.77081 4.70703 8.74415 4.70703 7.47915C4.70703 6.21415 5.7337 5.18748 6.9987 5.18748C8.2637 5.18748 9.29036 6.21415 9.29036 7.47915C9.29036 8.74415 8.2637 9.77081 6.9987 9.77081Z" fill="#97B63C"/>
			</svg>
			<?php if ($customer_city) { ?>
				<span id="change-city-customer-city-name"><?php echo $customer_city['city']; ?></span>
				<script>
					console.log('pass City Suggest, already known');
				</script>
				<?php } else { ?>
				<span id="change-city-customer-city-name"><i class="fas fa-spinner fa-spin"></i></span>
				<script>																																		
					$(document).ready(function(){													
						$.ajax({
							url: "index.php?route=kp/checkout/suggestCurrentCity",
							dataType: "json",
							async : true,
							beforeSend: function(){
								console.log('init City Suggest');
							},
							error: function(){
								$('#change-city-customer-city-name').text('Bug happened, sorry');
							},
							success: function(json){
								if (json.city != 'undefined' && json.city && json.id){
									console.log('got City ' + json.city + ' it is guessed: ' + json.guessed);
									$('#change-city-customer-city-name').text(json.city);
									$('#change-city-customer-city-name-in-city-ask').text(json.city);
									
									setCustomerCityAjax(json.city, json.id);
									
									if (!json.found){
										console.log('city not found, triggering City Ask Popup');
										$('#change-city-customer-city-name-ask-popup').show();
									}

									$(".popup-city__close, .popup-city__btn-yes").on('click', function () {
										$('#change-city-customer-city-name-ask-popup').hide();
										$('#change-city-customer-city-name-selection-popup').hide();
										setCustomerCityAjax(json.city, json.id);
										return false;
									});
								}
							},
						});													
					});
				</script>
			<?php } ?>
			<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M1 1L5 5L9 1" stroke="#696F74" stroke-width="1.77778" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>

			<script>
				function openCityPopup(){
					$.ajax({
						url: "index.php?route=kp/checkout/getCitiesListAjax",
						dataType: "html",
						beforeSend: function(){
							$('#change-city-customer-city-name-selection-popup').html('<i class="fas fa-spinner fa-spin"></i>');
						},
						success: function(html){
							$('#change-city-customer-city-name-selection-popup').html(html);
						},
						complete: function(){
							$('#change-city-customer-city-name-ask-popup').hide();
							$('#change-city-customer-city-name-selection-popup').show();
						}														
					});			
				}
				
				
				$(document).ready(function(){		
					/*$(".popup-city__close, .popup-city__btn-yes").on('click', function () {
						$('#change-city-customer-city-name-ask-popup').hide();
						$('#change-city-customer-city-name-selection-popup').hide();
						return false;
					});*/
					
					$(".change-city__btn, .popup-city__btn-other, #change-city-customer-city-name").on('click', function () {
						openCityPopup();
					});
				});
			</script>
			
		</div>
		
		
		<!--popup-city-->
		
		<div id="change-city-customer-city-name-ask-popup" class="popup-city hidden" rel="your-city">
			<div class="popup-city__title"><?php echo $text_retranslate_37; ?> <span id="change-city-customer-city-name-in-city-ask"></span>?</div>
			<div class="popup-city__btn-group">
				<a href="#" class="popup-city__btn-yes btn"><?php echo $text_retranslate_38; ?></a>
				<a href="#" class="popup-city__btn-other"><?php echo $text_retranslate_39; ?></a>
			</div>
			<div class="popup-city__text">
				<?php echo $text_retranslate_40; ?>
			</div>
			<div class="popup-city__close"></div>
		</div>
		
		<!--/popup-city-->
		
		<!--popup-city-->
		
		<div id="change-city-customer-city-name-selection-popup" class="popup-city" rel="choose-city">
			
		</div>
		
		<!--/popup-city-->
		
	</div>
	
	
	
	<!--/change-city-->
<? } ?>