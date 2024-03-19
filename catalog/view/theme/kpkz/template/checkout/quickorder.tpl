<? if (mb_strlen($mask, 'UTF-8') > 1) { ?>
	<script>
		$(document).ready(function () {
			// $('#phone-quick-order').mask("<?php echo $mask; ?>");
			$('#phone-quick-order').inputmask("<?php echo $mask; ?>",{ "clearIncomplete": true });
		});
	</script>
<? } ?>

<script>
    $(document).ready(function () {						
		if ((typeof fbq !== 'undefined')){
			fbq('track', 'InitiateCheckout');
		}

		$(document).on('click', '#button-quick-order', function(e){											
			if ($(this).hasClass('disable-btn')){
				return false;
			}
			
			$.ajax({
				beforeSend: function () {
					$('#load-wrapper').css({display:'table'});
				},
				type: 'POST',
				dataType: 'JSON',
				url: '/index.php?route=checkout/quickorder/createorder',
				data: $('#product-cart input[type=\'text\'], #product-cart input[type=\'hidden\'], #product-cart input[type=\'radio\']:checked, #product-cart input[type=\'checkbox\']:checked, #product-cart select, #product-cart textarea, #quick-order input[type=\'text\'], #quick-order input[type=\'hidden\'], #quick-order input[type=\'tel\']'),
				success: function(data) {
					if (data.error) {
						$('#load-wrapper').css({display:'none'});
						$('#quick-order .errors').html('');
						$('#popup-cart-trigger').click(); 
						$.each(data.error, function (i, v) {
							$('#quick-order .errors').prepend(v+"<br>");
						});
						console.log('No');
						} else {
						if (data.success) {
							console.log(data);

							<?php if ($this->config->get('config_sendsay_enable_marketing')){ ?>
								(window['sndsyApiOnReady'] = window['sndsyApiOnReady'] || []).push(function () {
									sndsyApi.ssecEvent(
										'ORDER',
										[{
											'transaction_id'	: data.google_ecommerce_info.transactionId,
											'transaction_status': 1,
											'transaction_dt'	: '<?php echo date('Y-m-d H:i:s'); ?>',
											'transaction_sum'	: data.google_ecommerce_info.transactionTotal,
											'items': [{
														'id' 		: data.google_ecommerce_info.transactionProducts[0].id,
														'model'		: data.google_ecommerce_info.transactionProducts[0].model,
														'name' 		: data.google_ecommerce_info.transactionProducts[0].name,
														'picture' 	: [data.google_ecommerce_info.transactionProducts[0].image],
														'vendor' 	: data.google_ecommerce_info.transactionProducts[0].brand,
														'category_id'		: data.google_ecommerce_info.transactionProducts[0].main_category_id,
														'category_paths' 	: [data.google_ecommerce_info.transactionProducts[0].price],
														'price' 	: data.google_ecommerce_info.transactionProducts[0].price,
														'qnt' 		: data.google_ecommerce_info.transactionProducts[0].quantity
													}]
										}]
										<?php if ($this->customer->getIfRealEmail()) { ?>,
										{ email: '<?php echo $this->customer->getIfRealEmail(); ?>' } 
									<?php } ?>
									);
								});
								typeof sndsyApi != 'undefined' && sndsyApi.runQueue();
							<?php } ?>

							window.dataLayer = window.dataLayer || [];
							dataLayer.push({
								'event': 'orderPurchaseSuccess',
								'ecommerce': {
									'currencyCode': data.google_ecommerce_info.transactionCurrency,  
									'purchase': {
										'id':           data.google_ecommerce_info.transactionId,                        
										'affiliation':  data.google_ecommerce_info.transactionAffiliation, 
										'revenue':      data.google_ecommerce_info.transactionTotal,  
										'tax':          data.google_ecommerce_info.transactionTax, 
										'shipping':     data.google_ecommerce_info.transactionShipping,
										
										'actionField': {
											'id':           data.google_ecommerce_info.transactionId,                        
											'affiliation':  data.google_ecommerce_info.transactionAffiliation, 
											'revenue':      data.google_ecommerce_info.transactionTotal,  
											'tax':          data.google_ecommerce_info.transactionTax, 
											'shipping':     data.google_ecommerce_info.transactionShipping,
										},
										'products': [
										{
											'id' : data.google_ecommerce_info.transactionProducts[0].id,
											'sku': data.google_ecommerce_info.transactionProducts[0].sku,
											'name' : data.google_ecommerce_info.transactionProducts[0].name,
											'brand' : data.google_ecommerce_info.transactionProducts[0].brand,
											'category' : data.google_ecommerce_info.transactionProducts[0].category,
											'price' : data.google_ecommerce_info.transactionProducts[0].price,
											'quantity' : data.google_ecommerce_info.transactionProducts[0].quantity,
											'total' : data.google_ecommerce_info.transactionProducts[0].total
										}
										]					
									}	
								}
							});	

							<?php if ($this->config->get('config_vk_enable_pixel')) { ?>
							<?php } ?>			
							
							if (data.google_ecommerce_info.display_survey == true){
								console.log('Google Survey Init SetTimeout!');
								window.setTimeout(
								function(){
									console.log('Google Survey Init!');
									window.renderOptIn = function() {
										window.gapi.load('surveyoptin', function() {
											window.gapi.surveyoptin.render(
											{
												"merchant_id": data.google_ecommerce_info.config_google_merchant_id,
												"order_id": data.order_id,
												"email": data.google_ecommerce_info.transactionEmail,
												"delivery_country": data.google_ecommerce_info.transactionCountryCode,
												"estimated_delivery_date": data.google_ecommerce_info.transactionEstimatedDelivery,		
												"products": data.google_ecommerce_info.transactionGTINS
											});
										});
									}
									
									$.getScript('https://apis.google.com/js/platform.js?onload=renderOptIn');
								}, 1000);
							}
							
							if ((typeof fbq !== 'undefined')){
								fbq('track', 'Purchase', {
									value: data.total, 
									currency: data.currency
								});
							}
							$('#load-wrapper').css({display:'none'});
							$('#quick-order').closest('div').html(data.html);
						}
					}
				}
				
			});
		});
		
		function checkButtonTrigger(){
			<?php if (trim($mask)) { ?>
				var phone_length = $('#phone-quick-order').val().length;
				
				if (phone_length >= 15){
					$('#button-quick-order').removeClass('disable-btn').addClass('enable-btn');
					} else {
					$('#button-quick-order').removeClass('enable-btn').addClass('disable-btn');
				}
				<?php } else { ?>
				$('#button-quick-order').removeClass('disable-btn').addClass('enable-btn');
			<?php } ?>
		}
		
		$('#phone-quick-order').on('keyup',function(){
			checkButtonTrigger();
		});
		
		checkButtonTrigger();				
	});
</script>
<style>    
	#load-wrapper{
		display: none;
		z-index: 99999;
		position: absolute;
		width: 100%;
		height: 100%;
		background: rgba(255,255,255, 0.5);
		text-align: center;
	}

	#load-wrapper i{
		font-size: 50px;
		position: absolute;
		top: 0;
		bottom: 0;
		height: 50px;
		margin: auto;
	}
</style>

<div class="overlay_popup"></div>
<div class="popup-form" id="quick_popup">
    <div id="load-wrapper">
        <i class="fas fa-spinner fa-pulse"></i>
	</div>
    <div id="modal_form" class="object js-form">
        <div class="left-modal">
            <div>
                <i class="fas fa-mobile-alt"></i>
                <span><?php echo $text_retranslate1; ?></span>
			</div>
            <div>
                <i class="fas fa-map-marker-alt"></i>
                
                
                <span><?php echo $text_retranslate2; ?>
                    <?php if ($country == "Казахстана") { ?>
                        всему Казахстану
                        <?php } else { ?>
						<?php if (!empty($text_retranslate_country)) { ?>
							<?php echo $text_retranslate_country; ?>
							<?php } else { ?>
							всей <?=$country ?>
						<? } ?>
					<?php } ?>   
				</span>
			</div>
            <div>
                <i class="fas fa-paper-plane"></i>
                <span><?php echo $text_retranslate6; ?></span>             
			</div>
            <span style="font-size: 12px;"><?php echo $text_retranslate7; ?></span>
		</div>
        <div class="right-modal">
            <div id="quick-order">
                <div class="errors"></div>
                <input type='hidden' name='quickorder_key' value='<? echo $quickorder_key; ?>' />
                <? /*
                    <div class="col-xs-24 labels-of-modal-r">
                    <div class="col-xs-10 simplecheckout-customer-left"><span>Ваш Email:</span></div>
                    <div class="col-xs-14"><input type="text" id="" placeholder="Ваш Email" value="<?=$email ?>" name="quickorder-dialog-email"></div>
                    </div>
				*/ ?>
                <div class="your-phone">
                    <div>
                        <span><?php echo $text_retranslate8; ?></span>
					</div>
                    <div>
                        <input id="phone-quick-order" class="phone-quick 43344" type="tel" value="<?=$telephone ?>" placeholder="<?php echo str_replace('9', '_', $mask) ?>" name="quickorder-dialog-phone">
					</div>
				</div>
                <p><?php echo $text_retranslate9; ?></p>
                <div class="btn-group" style="text-align: center;">
                    <input type="button" class="button-quick btn btn-acaunt disable-btn" id="button-quick-order" name="" value="<?php echo $text_retranslate10; ?>" title="">
				</div>
                <div class="clear"></div>
			</div>
		</div>
        <div class="overlay-popup-close"></div>
        <div class="clear"></div>
	</div>
</div>									