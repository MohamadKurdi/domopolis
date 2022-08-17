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
			<svg width="20" height="24" viewBox="0 0 20 24" fill="none"
			xmlns="http://www.w3.org/2000/svg">
				<path d="M19 10C19 17 10 23 10 23C10 23 1 17 1 10C1 7.61305 1.94821 5.32387 3.63604 3.63604C5.32387 1.94821 7.61305 1 10 1C12.3869 1 14.6761 1.94821 16.364 3.63604C18.0518 5.32387 19 7.61305 19 10Z"
				stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				<path d="M10 13C11.6569 13 13 11.6569 13 10C13 8.34315 11.6569 7 10 7C8.34315 7 7 8.34315 7 10C7 11.6569 8.34315 13 10 13Z"
				stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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