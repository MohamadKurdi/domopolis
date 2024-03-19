<?php if ($price) { ?>	
	<div class="price <?php if (!$special) { ?>no-special-price<?php } ?>" <?php if ($need_ask_about_stock) { ?> style="flex-direction: column;" <?php } ?> >
		<div class="price__head" <?php  if ($need_ask_about_stock) { ?> style="display: block;" <?php } ?>>
			
			<?php  if ($need_ask_about_stock) { ?>			  
				<p><?php echo $text_retranslate_28; ?> </p>
				<?php  } else if ($can_not_buy) { ?>
				<?php  } else if (!$special) { ?>
				<div class="price__new"><?php echo $price; ?></div>
				
			<?php } else { ?>
				<div class="price__new"><?php echo $special; ?></div>
				<div class="price__old"><?php echo $price; ?></div>
				<div class="price__bottom">
					<div class="price__sale">-<?php echo $saving; ?>%</div>
					<!-- <div class="price__economy">Экономия 760 грн</div> -->
				</div>	
				
			<?php } ?>	
			<?php  if (!$need_ask_about_stock && !$can_not_buy) { ?>
				<? if ($points) { ?>
					<div class="reward_wrap">
						<span class="text"><?php echo $points; ?></span>
						<div class="prompt">
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
		</div>
		<?php  if ($need_ask_about_stock) { ?>																		
			<div class="waitlist-block" style="display: block;width: 100%">
				<form id="waitlist-form">
					<div class="row">
						<img src="/catalog/view/theme/kpua/img/Spinners.png" id="ajaxcartloadimg" class="loading_spiner" style="width: 70px;height: 70px;" />
						<div class="phone_block">
							<span ><?php echo $text_retranslate_30; ?></span>
							<input class="waitlist__phone" type="text" name="waitlist-phone" id="waitlist-phone" placeholder="Ваш номер телефона" data-telephone="<?php echo str_replace('9', '_', $mask) ?>" value="<? if ($customer_telephone) { ?><? echo $customer_telephone; ?><? } ?>" />
							<input type="hidden" name="customer_id" value="<?php echo (int)$customer_id; ?>" />
							<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
						</div>		
						<div class="error"  id="error_phone"></div>
						<div class=" error" id="waitlist-success"></div>
					</div>
					<input type="button" class="btn btn-success btn-default" value="<?php echo $text_retranslate_31; ?>" id="waitlist-send" />
					<? /* <br /><span style="color:#CCC; font-size:12px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Оставьте нам свой номер телефона, и если мы сможем привезти этот товар Вам - мы обязательно свяжемся с Вами.</span> */ ?>
				</form>
			</div>	
			<div class="btn_group_head">	
				<?php if ($this->config->get('show_wishlist') == '1')  { ?>                  
					<?php if ($logged) { ?>	
						<button 11111 onclick="addToWishList('<?php echo $product_id; ?>');"  class="price__btn-favorite" title="<?php echo $button_wishlist; ?>">
							<svg width="39" height="34" viewbox="0 0 39 34" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M34.3012 4.65234C33.446 3.81151 32.4306 3.1445 31.313 2.68943C30.1954 2.23435 28.9975 2.00012 27.7878 2.00012C26.5781 2.00012 25.3802 2.23435 24.2626 2.68943C23.145 3.1445 22.1296 3.81151 21.2744 4.65234L19.4996 6.39654L17.7247 4.65234C15.9972 2.95472 13.6543 2.001 11.2113 2.001C8.76832 2.001 6.42539 2.95472 4.69793 4.65234C2.97048 6.34996 2 8.65243 2 11.0532C2 13.454 2.97048 15.7565 4.69793 17.4541L6.47279 19.1983L19.4996 32.0001L32.5263 19.1983L34.3012 17.4541C35.1568 16.6137 35.8355 15.6158 36.2986 14.5175C36.7617 13.4193 37 12.2421 37 11.0532C37 9.8644 36.7617 8.68721 36.2986 7.58893C35.8355 6.49064 35.1568 5.49278 34.3012 4.65234V4.65234Z"
								stroke="#51A881" stroke-width="2.2" stroke-linecap="round"
								stroke-linejoin="round"></path>
							</svg>
						</button>
						<?php } else { ?>
						<button onclick="showRegisterProdDetail(this);" class="price__btn-favorite" title="<?php echo $button_wishlist; ?>">
							<svg width="39" height="34" viewbox="0 0 39 34" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M34.3012 4.65234C33.446 3.81151 32.4306 3.1445 31.313 2.68943C30.1954 2.23435 28.9975 2.00012 27.7878 2.00012C26.5781 2.00012 25.3802 2.23435 24.2626 2.68943C23.145 3.1445 22.1296 3.81151 21.2744 4.65234L19.4996 6.39654L17.7247 4.65234C15.9972 2.95472 13.6543 2.001 11.2113 2.001C8.76832 2.001 6.42539 2.95472 4.69793 4.65234C2.97048 6.34996 2 8.65243 2 11.0532C2 13.454 2.97048 15.7565 4.69793 17.4541L6.47279 19.1983L19.4996 32.0001L32.5263 19.1983L34.3012 17.4541C35.1568 16.6137 35.8355 15.6158 36.2986 14.5175C36.7617 13.4193 37 12.2421 37 11.0532C37 9.8644 36.7617 8.68721 36.2986 7.58893C35.8355 6.49064 35.1568 5.49278 34.3012 4.65234V4.65234Z"
								stroke="#51A881" stroke-width="2.2" stroke-linecap="round"
								stroke-linejoin="round"></path>
							</svg>
						</button>
					<?php } ?>
				<?php } ?>
				<?php if ($this->config->get('show_compare') == '1')  { ?>
					<button class="addToCompareBtn" onclick="addToCompare('<?php echo $product_id; ?>');" title="<?php echo $text_retranslate_35; ?>">
						<i class="icons compare_icompare"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>	
					</button>
				<?php } ?>
				</div>
			<script type="text/javascript">
				
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
				
				$(document).on('click', '#waitlist-send', function() {
					$.ajax({
						url: '/index.php?route=checkout/quickorder/createpreorder',
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
		<? } else if ($can_not_buy) { ?>
			<div class="row" style="width: 100%;">
				<div class="col-md-12 col-sm-12">
					<span style="color:#CCC; font-size:22px; font-weight:700;"><? echo $stock; ?></span>
				</div>

				<div class="waitlist-block">
					<form id="waitlist-form">
						<div class="row">
							<img src="/catalog/view/theme/kpua/img/Spinners.png" id="ajaxcartloadimg" class="loading_spiner" style="width: 70px;height: 70px;"/>
							<div class="phone_block" >
								<span><?php echo $text_retranslate_30; ?></span>
								<input class="waitlist__phone" type="text" name="waitlist-phone" id="waitlist-phone" data-telephone="<?php echo str_replace('9', '_', $mask) ?>" placeholder="<?php echo str_replace('9', '_', $mask) ?>" value="<? if ($customer_telephone) { ?><? echo $customer_telephone; ?><? } ?>" />
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
				<div class="btn_group_head">	
					<?php if ($this->config->get('show_wishlist') == '1')  { ?>                  
						<?php if ($logged) { ?>	
							<button onclick="addToWishList('<?php echo $product_id; ?>');"  class="price__btn-favorite" title="<?php echo $button_wishlist; ?>">
								<svg width="39" height="34" viewbox="0 0 39 34" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M34.3012 4.65234C33.446 3.81151 32.4306 3.1445 31.313 2.68943C30.1954 2.23435 28.9975 2.00012 27.7878 2.00012C26.5781 2.00012 25.3802 2.23435 24.2626 2.68943C23.145 3.1445 22.1296 3.81151 21.2744 4.65234L19.4996 6.39654L17.7247 4.65234C15.9972 2.95472 13.6543 2.001 11.2113 2.001C8.76832 2.001 6.42539 2.95472 4.69793 4.65234C2.97048 6.34996 2 8.65243 2 11.0532C2 13.454 2.97048 15.7565 4.69793 17.4541L6.47279 19.1983L19.4996 32.0001L32.5263 19.1983L34.3012 17.4541C35.1568 16.6137 35.8355 15.6158 36.2986 14.5175C36.7617 13.4193 37 12.2421 37 11.0532C37 9.8644 36.7617 8.68721 36.2986 7.58893C35.8355 6.49064 35.1568 5.49278 34.3012 4.65234V4.65234Z"
									stroke="#51A881" stroke-width="2.2" stroke-linecap="round"
									stroke-linejoin="round"></path>
								</svg>
							</button>
							<?php } else { ?>
							<button onclick="showRegisterProdDetail(this);" class="price__btn-favorite" title="<?php echo $button_wishlist; ?>">
								<svg width="39" height="34" viewbox="0 0 39 34" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M34.3012 4.65234C33.446 3.81151 32.4306 3.1445 31.313 2.68943C30.1954 2.23435 28.9975 2.00012 27.7878 2.00012C26.5781 2.00012 25.3802 2.23435 24.2626 2.68943C23.145 3.1445 22.1296 3.81151 21.2744 4.65234L19.4996 6.39654L17.7247 4.65234C15.9972 2.95472 13.6543 2.001 11.2113 2.001C8.76832 2.001 6.42539 2.95472 4.69793 4.65234C2.97048 6.34996 2 8.65243 2 11.0532C2 13.454 2.97048 15.7565 4.69793 17.4541L6.47279 19.1983L19.4996 32.0001L32.5263 19.1983L34.3012 17.4541C35.1568 16.6137 35.8355 15.6158 36.2986 14.5175C36.7617 13.4193 37 12.2421 37 11.0532C37 9.8644 36.7617 8.68721 36.2986 7.58893C35.8355 6.49064 35.1568 5.49278 34.3012 4.65234V4.65234Z"
									stroke="#51A881" stroke-width="2.2" stroke-linecap="round"
									stroke-linejoin="round"></path>
								</svg>
							</button>
						<?php } ?>
					<?php } ?>
					<?php if ($this->config->get('show_compare') == '1')  { ?>
						<button class="addToCompareBtn" onclick="addToCompare('<?php echo $product_id; ?>');" title="<?php echo $text_retranslate_35; ?>">
							<i class="icons compare_icompare"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>	
						</button>
					<?php } ?>
				</div>

				<? if ($customer_telephone) { ?>
					<script type="text/javascript">
						setTimeout(function(){
							$('#waitlist-phone').val('<? echo $customer_telephone; ?>');
						}, 1000)
					</script>
				<? } ?>

				<script type="text/javascript">
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


			<?php if ($this->config->get('config_warehouse_only') && $current_in_stock) { ?>
				<div class="delivery_terms product-info__delivery">	
					<span class="terms <?php echo $current_in_stock_color; ?>">На складе осталось <?php echo $current_in_stock; ?> шт.</span>
				</div>
			<?php } ?>
							


			<div class="price__btn-group">
				<div class="top">
					<?php if ($additional_offers) { ?> 
						<?php $ao_has_zero_price = false;
							foreach ($additional_offers as $additional_offer) { ?>
							<input type='checkbox' checked="checked" style="display:none" class="check-offer-item" name='additional_offer[<? echo $additional_offer['additional_offer_id']; ?>]' id='additional_offer_<? echo $additional_offer['additional_offer_id']; ?>'/>
							<? } 
					} ?>					

					<button id="main-add-to-cart-button" class="price__btn-buy btn <?php if ($additional_offers) { ?> btn-additional-offer <? } ?>" 
					<?php if ($additional_offers) { ?> 
						<?php $ao_has_zero_price = false;
							foreach ($additional_offers as $additional_offer) { ?>
							data-ao-id="<? echo $additional_offer['additional_offer_id']; ?>"
							<? } 
						} ?>
						>
							
						<svg width="26" height="25" viewbox="0 0 26 25" fill="none"
						xmlns="http://www.w3.org/2000/svg">
							<path d="M1 1.33948H5.19858L8.01163 15.6923C8.10762 16.1858 8.37051 16.6292 8.7543 16.9447C9.13809 17.2602 9.61832 17.4278 10.1109 17.4181H20.3135C20.8061 17.4278 21.2863 17.2602 21.6701 16.9447C22.0539 16.6292 22.3168 16.1858 22.4128 15.6923L24.0922 6.69903H6.24823M10.4468 22.7777C10.4468 23.3697 9.97687 23.8496 9.39716 23.8496C8.81746 23.8496 8.34752 23.3697 8.34752 22.7777C8.34752 22.1857 8.81746 21.7058 9.39716 21.7058C9.97687 21.7058 10.4468 22.1857 10.4468 22.7777ZM21.9929 22.7777C21.9929 23.3697 21.523 23.8496 20.9433 23.8496C20.3636 23.8496 19.8936 23.3697 19.8936 22.7777C19.8936 22.1857 20.3636 21.7058 20.9433 21.7058C21.523 21.7058 21.9929 22.1857 21.9929 22.7777Z"
							stroke="white" stroke-width="2" stroke-linecap="round"
							stroke-linejoin="round"></path>
						</svg>
						<span><?php echo $text_retranslate_33; ?></span>
						
						<?php  if ($need_ask_about_stock) { ?>			  
							<?php  } else if ($can_not_buy) { ?>
							<?php  } else if (!$special) { ?>
							<span class="price"><?php echo $price; ?></span>
							<?php } else { ?>
							<span class="price"><?php echo $special; ?></span>								
						<?php } ?>
						
					</button> 
					<div id="quick-order-block">										
						<input type='button' data-product_id="<?=$product_id; ?>"  id="quick-order-btn" class="price__btn-quick-order do-popup-element js-fast-byu" value='<?php echo $text_retranslate_34; ?>' data-target="quick_popup"  />
					</div>
					<?php if ($this->config->get('show_wishlist') == '1')  { ?>                  
						<?php if ($logged) { ?>	
							<button onclick="addToWishList('<?php echo $product_id; ?>');"  class="price__btn-favorite" title="<?php echo $button_wishlist; ?>">
								<svg width="39" height="34" viewbox="0 0 39 34" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M34.3012 4.65234C33.446 3.81151 32.4306 3.1445 31.313 2.68943C30.1954 2.23435 28.9975 2.00012 27.7878 2.00012C26.5781 2.00012 25.3802 2.23435 24.2626 2.68943C23.145 3.1445 22.1296 3.81151 21.2744 4.65234L19.4996 6.39654L17.7247 4.65234C15.9972 2.95472 13.6543 2.001 11.2113 2.001C8.76832 2.001 6.42539 2.95472 4.69793 4.65234C2.97048 6.34996 2 8.65243 2 11.0532C2 13.454 2.97048 15.7565 4.69793 17.4541L6.47279 19.1983L19.4996 32.0001L32.5263 19.1983L34.3012 17.4541C35.1568 16.6137 35.8355 15.6158 36.2986 14.5175C36.7617 13.4193 37 12.2421 37 11.0532C37 9.8644 36.7617 8.68721 36.2986 7.58893C35.8355 6.49064 35.1568 5.49278 34.3012 4.65234V4.65234Z"
									stroke="#51A881" stroke-width="2.2" stroke-linecap="round"
									stroke-linejoin="round"></path>
								</svg>
							</button>
							<?php } else { ?>
							<button onclick="showRegisterProdDetail(this);" class="price__btn-favorite" title="<?php echo $button_wishlist; ?>">
								<svg width="39" height="34" viewbox="0 0 39 34" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M34.3012 4.65234C33.446 3.81151 32.4306 3.1445 31.313 2.68943C30.1954 2.23435 28.9975 2.00012 27.7878 2.00012C26.5781 2.00012 25.3802 2.23435 24.2626 2.68943C23.145 3.1445 22.1296 3.81151 21.2744 4.65234L19.4996 6.39654L17.7247 4.65234C15.9972 2.95472 13.6543 2.001 11.2113 2.001C8.76832 2.001 6.42539 2.95472 4.69793 4.65234C2.97048 6.34996 2 8.65243 2 11.0532C2 13.454 2.97048 15.7565 4.69793 17.4541L6.47279 19.1983L19.4996 32.0001L32.5263 19.1983L34.3012 17.4541C35.1568 16.6137 35.8355 15.6158 36.2986 14.5175C36.7617 13.4193 37 12.2421 37 11.0532C37 9.8644 36.7617 8.68721 36.2986 7.58893C35.8355 6.49064 35.1568 5.49278 34.3012 4.65234V4.65234Z"
									stroke="#51A881" stroke-width="2.2" stroke-linecap="round"
									stroke-linejoin="round"></path>
								</svg>
							</button>
						<?php } ?>
					<?php } ?>
					<?php if ($this->config->get('show_compare') == '1')  { ?>
						<button class="addToCompareBtn" onclick="addToCompare('<?php echo $product_id; ?>');" title="<?php echo $text_retranslate_35; ?>">
							<i class="icons compare_icompare"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>	
						</button>
					<?php } ?>
				</div>		
				<?php if(!IS_MOBILE_SESSION) { ?>
					<?php if ($this->config->get('mono_monocheckout_enable')) { ?>
						<button id="monocheckout-checkout-button" class="mono_monocheckout_enable">
							<img src="/catalog/view/theme/kpua/img/monocheckout_button_black_normal.svg" alt="mono_monocheckout">
						</button>
					<?php } ?>
				<?php } ?>
			</div>
		<?php } ?>

		<?php if(IS_MOBILE_SESSION) { ?>
			<?php  if (!$need_ask_about_stock) { ?>
				<?php if ($this->config->get('mono_monocheckout_enable')) { ?>
					<button id="monocheckout-checkout-button" class="mono_monocheckout_enable">
						<img src="/catalog/view/theme/kpua/img/monocheckout_button_black_normal.svg" alt="mono_monocheckout">
					</button>
				<?php } ?>
			<?php } ?>
		<?php } ?>

		<?php if ($this->config->get('mono_monocheckout_enable')) { ?>
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