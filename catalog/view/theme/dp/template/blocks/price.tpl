<?php if ($price) { ?>	
	<div class="price-wrap <?php if (!$special) { ?>no-special-price<?php } ?>" <?php if ($need_ask_about_stock) { ?> style="flex-direction: column;" <?php } ?> >
		<div class="price__top">
			<?php  if ($need_ask_about_stock) { ?>		
			<?php  } else if ($can_not_buy) { ?>
			<?php } else { ?>
					<span class="title_module"><?php echo $text_retranslate_90; ?></span>
			<?php } ?>
			<div class="price-wrap_bottom">
				<?php  if ($need_ask_about_stock) { ?>		
				<?php  } else if ($can_not_buy) { ?>
				<?php } else { ?>
					<div class="quantity_wrap">
						<a onclick="minus_p(this);" class="quantity-m">
							<svg width="14" height="2" viewBox="0 0 14 2" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M1.16699 1H12.8337" stroke="#BABEC2" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</a>
						<input value="1" oninput="validateInput(this)" onchange="qtVal_p(this);" data-minimum="<?php echo !empty($product['minimum'])?(int)$product['minimum']:1; ?>" type="number" id="qt_product" name="quantity" class="qt input_number" min="1" data-maximum="999" maxlength="4" />
						<a onclick="plus_p(this);"  class="quantity-p">
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M7.00033 1.16699V12.8337M1.16699 7.00033H12.8337" stroke="#BABEC2" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</a>
					</div>
				<?php } ?>
				<div class="product__price">
					<?php  if ($need_ask_about_stock) { ?>			  
						<p><?php echo $text_retranslate_28; ?> </p>
					<?php  } else if ($can_not_buy) { ?>
					<?php  } else if (!$special) { ?>
						<div class="product__price-new"><?php echo $price; ?></div>
					<?php } else { ?>
						<div class="product__price-old_wrap">
							<div class="product__price-old">
								<?php echo $price; ?>
							</div>
							<div class="price__sales">-<?php echo $saving;?>%</div>
						</div>
						<div class="product__price-new"><?php echo $special; ?></div>
					<?php } ?>	
				</div>
				<?php if (IS_MOBILE_SESSION && !IS_TABLET_SESSION) { ?>
					<?php  if ($need_ask_about_stock) { ?>		
					<?php  } else if ($can_not_buy) { ?>
					<?php } else { ?>
					<button 
						id="main-add-to-cart-button" 
						class="price__btn-buy btn <?php if ($additional_offers) { ?> btn-additional-offer <? } ?>" 
						<?php if ($additional_offers) { ?> 
							<?php $ao_has_zero_price = false;
								foreach ($additional_offers as $additional_offer) { ?>
								data-ao-id="<? echo $additional_offer['additional_offer_id']; ?>"
								<? } 
							} ?>
							>
								<span><?php echo $text_retranslate_33; ?></span>
					</button> 
					<?php } ?>	
				<?php } ?>	
			</div>
		</div>

		<?php  if (!$need_ask_about_stock && !$can_not_buy) { ?>
			<? if ($points) { ?>
				<div class="reward_wrap">
					<span class="icon">
							<svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M3.83398 0.83272C3.83398 0.644481 3.70777 0.47962 3.52606 0.430493C3.34434 0.381366 3.15226 0.460176 3.05741 0.622773L0.140743 5.62277C0.0655687 5.75164 0.0650342 5.91087 0.139342 6.04025C0.213649 6.16962 0.351457 6.24939 0.500651 6.24939H2.16732V9.16605C2.16732 9.35429 2.29353 9.51915 2.47524 9.56828C2.65696 9.61741 2.84904 9.5386 2.94389 9.376L5.86056 4.376C5.93573 4.24713 5.93627 4.0879 5.86196 3.95853C5.78765 3.82916 5.64984 3.74939 5.50065 3.74939H3.83398V0.83272Z" fill="#121415"/>
							</svg>
						</span>
					<span class="text"><?php echo $points; ?></span>
					<div class="prompt hidden">
						<p><?php echo $text_bonus1; ?></p>
						<ul>
							<li><?php echo $text_bonus2; ?></li>
							<li><?php echo $text_bonus3; ?></li>
							<li><?php echo $text_bonus4; ?></li>
						</ul>
					</div>
				</div>
			<? } ?>
		<?php } ?>

		<div class="price__btn-group <?php if ($can_not_buy) { ?>can_not_buy_item<?php } ?>">
			<?php  if ($need_ask_about_stock) { ?>	
			<? } else if ($can_not_buy) { ?>
				<div class="row" style="width: 100%;">
					<span style="color:#CCC; font-size:22px; font-weight:700;"><? echo $stock; ?></span>
					<div class="waitlist-block">
						<form id="waitlist-form">
							<div class="row">
								<img src="/catalog/view/theme/dp/img/Spinners.png" id="ajaxcartloadimg" class="loading_spiner" style="width: 70px;height: 70px;"/>
								<div class="phone_block" >
									<span><?php echo $text_retranslate_30; ?></span>
									<input class="waitlist__phone" type="text" name="waitlist-phone" id="waitlist-phone" data-telephone="<?php echo str_replace('9', '_', $mask) ?>" placeholder="<?php echo str_replace('9', '_', $mask) ?>" value="<? if ($customer_telephone) { ?><? echo $customer_telephone; ?><? } ?>" style="background: #f8f8f8;"/>
									<input type="hidden" name="customer_id" value="<?php echo (int)$customer_id; ?>" />
									<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
								</div>		
								<div class="error"  id="error_phone"></div>
								<div class="error"  id="waitlist-success"></div>
							</div>
							<div class="row">
								<div class="text-center">
									<input type="button" class="btn btn-success btn-default" value="<?php echo $text_retranslate_32; ?>" id="waitlist-send" />
									<? /* <br /><span style="color:#CCC; font-size:12px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Оставьте нам свой номер телефона, и если мы сможем привезти этот товар Вам - мы обязательно свяжемся с Вами.</span> */ ?>
								</div>
							</div>
						</form>
					</div>
					 
					<? if ($customer_telephone) { ?>
						<script type="text/javascript">
							setTimeout(function(){
								$('#waitlist-phone').val('<? echo $customer_telephone; ?>');
							}, 1200)
						</script>
					<? } ?>
					
					<script type="text/javascript">
						console.log('customer_telephone <? echo $customer_telephone; ?>');
						function checkButtonTrigger(){
							<? if (mb_strlen($mask, 'UTF-8') > 1) { ?>

								var phone_length = $('#waitlist-phone').val().length;
								
								if (phone_length >= 15){	
									$('#waitlist-send').removeClass('disable-btn').addClass('enable-btn');
									
									} else {
									$('#waitlist-send').removeClass('enable-btn').addClass('disable-btn');
									
								}
								<?php } else { ?>
								$('#waitlist-send').removeClass('disable-btn').addClass('enable-btn');
							<?php } ?>
						}
						
						$('#waitlist-phone').on('keyup',function(){
							checkButtonTrigger();
						});
						
						checkButtonTrigger();
						
						var loadImgPopup = $('#ajaxcartloadimg');
						var contentPopup = $('#waitlist-form');
						$(document).on('click','#waitlist-send', function() {
							$.ajax({
								url: '/index.php?route=module/callback/waitlist',
								type: 'post',
								data: $('#waitlist-form').serialize(),
								dataType: 'json',
								beforeSend: function() {
									$('#waitlist-send').bind('click', false);
									loadImgPopup.show();
									contentPopup.css('opacity','0.5');
								},
								complete: function() {
									$('#waitlist-send').unbind('click', false);
									
								},
								success: function(json) {
									$('#error_phone').empty().hide();
									$('#waitlist-success').empty().hide();
									
									if (json['error']) {				
										if (json['error']['phone']) {
											$('#error_phone').html(json['error']['phone']);
											$('#error_phone').show();
										}
										loadImgPopup.hide();
										contentPopup.css('opacity','1');
									}
									
									if (json['success']){ 
										$('#waitlist-phone').val('').hide();
										$('#waitlist-send').bind('click', false);
										$('#waitlist-success').html(json['success']);
										$('#waitlist-success').show('slow');
										loadImgPopup.hide();
										$('#waitlist-send, #waitlist-form .phone_block > span,  #waitlist-form .phone_block > input').hide();
										contentPopup.css('opacity','1');
									} 
								}
								
							});
							
						});
					</script> 
				</div>
			<? } else { ?>	
			<?php if ($additional_offers) { ?> 
				<?php $ao_has_zero_price = false;
					foreach ($additional_offers as $additional_offer) { ?>
					<input type='checkbox' checked="checked" style="display:none" class="check-offer-item" name='additional_offer[<? echo $additional_offer['additional_offer_id']; ?>]' id='additional_offer_<? echo $additional_offer['additional_offer_id']; ?>'/>
					<? } 
				} ?>					
				<?php if (IS_MOBILE_SESSION) { ?>
					<div class="product__price">
						<?php  if ($need_ask_about_stock) { ?>			  
							<p><?php echo $text_retranslate_28; ?> </p>
						<?php  } else if ($can_not_buy) { ?>
						<?php  } else if (!$special) { ?>
							<div class="product__price-new"><?php echo $price; ?></div>
						<?php } else { ?>
							<div class="product__price-old_wrap">
								<div class="product__price-old">
									<?php echo $price; ?>
								</div>
								<div class="price__sales">-<?php echo $saving;?>%</div>
							</div>
							<div class="product__price-new"><?php echo $special; ?></div>
						<?php } ?>	
					</div>
				<?php } ?>
				<button 
				id="main-add-to-cart-button" 
				class="price__btn-buy btn <?php if ($additional_offers) { ?> btn-additional-offer <? } ?>" 
				<?php if ($additional_offers) { ?> 
					<?php $ao_has_zero_price = false;
						foreach ($additional_offers as $additional_offer) { ?>
						data-ao-id="<? echo $additional_offer['additional_offer_id']; ?>"
						<? } 
					} ?>
					>
						<span><?php echo $text_retranslate_33; ?></span>
				</button> 

				<?php if (!$this->config->get('config_disable_fast_orders')) { ?>
					<button  data-product_id="<?=$product_id; ?>"  id="quick-order-btn" class="price__btn-quick-order do-popup-element js-fast-byu" data-target="quick_popup" title="<?php echo $text_retranslate_34; ?>">
						<?php echo $text_retranslate_34; ?>
					</button>	
				<?php } ?>

				<?php if ($config_mono_monocheckout_enable) { ?>
					<button id="monocheckout-checkout-button" class="mono_monocheckout_enable">
						<picture> 
							<source media="(min-width: 561px)" srcset="/catalog/view/theme/dp/img/monocheckout_button_black_normal.svg"  alt="mono_monocheckout"> 
								<img alt="mono_monocheckout" src="/catalog/view/theme/dp/img/monocheckout_button_black_short_mob.svg"> 
							</picture>
							<span class="text-mob">-5%</span>
					</button>

					<?php if (!empty($monocheckout_pmd_discount)) { ?>
						<span class="alert alert-success" style="font-size:14px;"><?php echo $monocheckout_pmd_discount; ?></span>
					<?php } ?>
				<?php } ?>

			<?php } ?>	
		</div>
		<?php if ($config_mono_monocheckout_enable) { ?>
			<script type="text/javascript">					
				$('#monocheckout-checkout-button').on('click', function(){
					$.ajax({	
						url: 'index.php?route=payment/mono/checkoutorder',
						type: 'post',
						dataType: 'json',
						data:{
							product_id: <?php echo $product_id; ?>,
							quantity: 1
						}, 
						beforeSend: function(){
							console.log('[Mono] Init creating order');
						},
						success:function(json){
							if (json.success && json.mono_redirect){
								console.log('[Mono] Got Order, redirecting to monocheckout');
								//window.open(json.mono_redirect, '_blank');
								document.location = json.mono_redirect;
							}
						},
						error:function(json){
							console.log('[Mono] Creating order error');
							console.log(json);
						}
					});
				});
			</script>
		<?php } ?>
		
	</div>
<?php } ?>