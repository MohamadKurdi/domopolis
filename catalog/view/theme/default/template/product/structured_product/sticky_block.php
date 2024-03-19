<div class="sticky-block base-padding 12">
	<div class="sticky-block__product">
		
		<div class="sticky-block__image">
			<?php if ($thumb) { ?>
				<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" width="100px"/>
			<?php } ?>
			<div class="sticky-block__name"><?php echo $heading_title; ?></div>
		</div> 
		
		<div class="price_product">
			<?php  if ($need_ask_about_stock) { ?>			  
				<p style="font-size: 13px;line-height: 18px;text-align: justify;color: #333;">Данный товар доступен только под заказ. Оставляйте запрос и менеджер магазина свяжется с Вами для уточнения цены и сроков доставки. </p>
				<?php  } else if ($can_not_buy) { ?>
				<span style="color:#CCC; font-size:18px; font-weight:700;"><? echo $stock; ?></span>
				<?php  } else if (!$special) { ?>
				<div class="price__new"><?php echo $price; ?></div>
				<?php } else { ?>
				<div class="price__old_wrap">
					<div class="price__old"><?php echo $price; ?></div>
					<span class="price__saving">-<?php echo $saving; ?>%</span>
				</div>
				<div class="price__new"><?php echo $special; ?></div>
			<?php } ?>		
		</div>
		
		
		<?php  if ($need_ask_about_stock) { ?>
			<div class="waitlist-block" style="display: block;width: 100%">
				<form id="waitlist-form_sticky-block">
					<div class="row">
						<div class="phone_block">
							<span >Ваш номер телефона:</span>
							<input class="waitlist__phone" type="text" name="waitlist-phone" id="waitlist-phone_sticky-block" placeholder="<?php echo $mask; ?>" value="<? if ($customer_telephone) { ?><? echo $customer_telephone; ?><? } ?>" />
							<input type="hidden" name="customer_id" value="<?php echo (int)$customer_id; ?>" />
							<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
						</div>		
						<div class="error"  id="error_phone_sticky-block"></div>
						<div class=" error" id="waitlist-success_sticky-block"></div>
					</div>
					<input type="button" class="btn btn-success btn-default" value="Оформить предзаказ" id="waitlist-send_sticky-block" />
					<? /* <br /><span style="color:#CCC; font-size:12px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Оставьте нам свой номер телефона, и если мы сможем привезти этот товар Вам - мы обязательно свяжемся с Вами.</span> */ ?>
				</form>
			</div>	
			<script type="text/javascript">
				
				var contentPopup = $('#waitlist-form_sticky-block');
				
				$('#waitlist-send_sticky-block').on('click', function() {
					$.ajax({
						url: '/index.php?route=checkout/quickorder/createpreorder',
						type: 'post',
						data: $('#waitlist-form_sticky-block').serialize(),
						dataType: 'json',
						beforeSend: function() {
							$('#waitlist-send_sticky-block').bind('click', false);
							contentPopup.css('opacity','0.5');
						},
						complete: function() {
							$('#waitlist-send_sticky-block').unbind('click', false);
							if (success == 'true'){															
							}
							
						},
						success: function(json) {
							$('#error_phone_sticky-block').empty().hide();
							$('#waitlist-success_sticky-block').empty().hide();
							
							if (json['error']) {				
								if (json['error']['phone']) {
									$('#error_phone_sticky-block').html(json['error']['phone']);
									$('#error_phone_sticky-block').show();
								}
								contentPopup.css('opacity','1');
							}
							
							if (json['success']){ 
								$('#waitlist-phone_sticky-block').val('').hide();
								$('#waitlist-send_sticky-block').hide();
								$('#waitlist-success_sticky-block').html(json['success']);
								$('#waitlist-success_sticky-block').show('slow');
								contentPopup.css('opacity','1');
							} 
						}
						
					});
					
				});
			</script> 
			<?php  } else if ($can_not_buy) { ?>
			<div class="row" style="width: 100%;">
				<div class="waitlist-block">
					<form id="waitlist-form_sticky-block">
						<div class="row">
							<div class="phone_block" >
								<span>Ваш номер телефона:</span>
								<input class="waitlist__phone" type="text" name="waitlist-phone" id="waitlist-phone_sticky-block" placeholder="<?php echo $mask; ?>" value="<? if ($customer_telephone) { ?><? echo $customer_telephone; ?><? } ?>" />
								<input type="hidden" name="customer_id" value="<?php echo (int)$customer_id; ?>" />
								<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
							</div>		
							<div class="error"  id="error_phone_sticky-block"></div>
							<div class="error"  id="waitlist-success_sticky-block"></div>
						</div>
						<div class="row">
							<div class="text-center">
								<input type="button" class="btn btn-success btn-default" value="Сообщить о поступлении" id="waitlist-send_sticky-block" />
								<? /* <br /><span style="color:#CCC; font-size:12px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Оставьте нам свой номер телефона, и если мы сможем привезти этот товар Вам - мы обязательно свяжемся с Вами.</span> */ ?>
							</div>
						</div>
					</form>
				</div>	
				<script type="text/javascript">
					var contentPopup = $('#waitlist-form_sticky-block');
					$('#waitlist-send_sticky-block').on('click', function() {
						$.ajax({
							url: '/index.php?route=module/callback/waitlist',
							type: 'post',
							data: $('#waitlist-form_sticky-block').serialize(),
							dataType: 'json',
							beforeSend: function() {
								$('#waitlist-send_sticky-block').bind('click', false);
								contentPopup.css('opacity','0.5');
							},
							complete: function() {
								$('#waitlist-send_sticky-block').unbind('click', false);
								if (success == 'true'){															
								}
							},
							success: function(json) {
								$('#error_phone_sticky-block').empty().hide();
								$('#waitlist-success_sticky-block').empty().hide();
								
								if (json['error']) {				
									if (json['error']['phone']) {
										$('#error_phone_sticky-block').html(json['error']['phone']);
										$('#error_phone_sticky-block').show();
									}
									contentPopup.css('opacity','1');
								}
								
								if (json['success']){ 
									$('#waitlist-phone_sticky-block').val('').hide();
									$('#waitlist-send_sticky-block').hide();
									$('#waitlist-success_sticky-block').html(json['success']);
									$('#waitlist-success_sticky-block').show('slow');
									contentPopup.css('opacity','1');
								} 
							}
							
						});
						
					});
				</script> 
			</div>
			<?php } else { ?>
			<div class="addTo-cart-qty">
				<?php if ($additional_offers) { ?> 
					<?php $ao_has_zero_price = false;
						foreach ($additional_offers as $additional_offer) { ?>
						<input type='checkbox' checked="checked" style="display:none" class="check-offer-item" name='additional_offer[<? echo $additional_offer['additional_offer_id']; ?>]' id='additional_offer_<? echo $additional_offer['additional_offer_id']; ?>'/>
						<? } 
					} ?>
					<button id="sticky-block_addTo-cart-button" class="price__btn-buy btn <?php if ($additional_offers) { ?> btn-additional-offer <? } ?>" 
					<?php if ($additional_offers) { ?> 
						<?php $ao_has_zero_price = false;
							foreach ($additional_offers as $additional_offer) { ?>
							data-ao-id="<? echo $additional_offer['additional_offer_id']; ?>"
							<? } 
						} ?>
						>
							<svg width="15" height="15" viewbox="0 0 26 25" fill="none"
							xmlns="http://www.w3.org/2000/svg">
								<path d="M1 1.33948H5.19858L8.01163 15.6923C8.10762 16.1858 8.37051 16.6292 8.7543 16.9447C9.13809 17.2602 9.61832 17.4278 10.1109 17.4181H20.3135C20.8061 17.4278 21.2863 17.2602 21.6701 16.9447C22.0539 16.6292 22.3168 16.1858 22.4128 15.6923L24.0922 6.69903H6.24823M10.4468 22.7777C10.4468 23.3697 9.97687 23.8496 9.39716 23.8496C8.81746 23.8496 8.34752 23.3697 8.34752 22.7777C8.34752 22.1857 8.81746 21.7058 9.39716 21.7058C9.97687 21.7058 10.4468 22.1857 10.4468 22.7777ZM21.9929 22.7777C21.9929 23.3697 21.523 23.8496 20.9433 23.8496C20.3636 23.8496 19.8936 23.3697 19.8936 22.7777C19.8936 22.1857 20.3636 21.7058 20.9433 21.7058C21.523 21.7058 21.9929 22.1857 21.9929 22.7777Z"
								stroke="white" stroke-width="2" stroke-linecap="round"
								stroke-linejoin="round"></path>
							</svg>Купить
						</button>
						<?php if ($this->config->get('show_wishlist') == '1')  { ?>                  
							<button onclick="addToWishList('<?php echo $product_id; ?>');" class="price__btn-favorite" title="<?php echo $button_wishlist; ?>">
								<svg width="39" height="34" viewbox="0 0 39 34" fill="none"
								xmlns="http://www.w3.org/2000/svg">
									<path d="M34.3012 4.65234C33.446 3.81151 32.4306 3.1445 31.313 2.68943C30.1954 2.23435 28.9975 2.00012 27.7878 2.00012C26.5781 2.00012 25.3802 2.23435 24.2626 2.68943C23.145 3.1445 22.1296 3.81151 21.2744 4.65234L19.4996 6.39654L17.7247 4.65234C15.9972 2.95472 13.6543 2.001 11.2113 2.001C8.76832 2.001 6.42539 2.95472 4.69793 4.65234C2.97048 6.34996 2 8.65243 2 11.0532C2 13.454 2.97048 15.7565 4.69793 17.4541L6.47279 19.1983L19.4996 32.0001L32.5263 19.1983L34.3012 17.4541C35.1568 16.6137 35.8355 15.6158 36.2986 14.5175C36.7617 13.4193 37 12.2421 37 11.0532C37 9.8644 36.7617 8.68721 36.2986 7.58893C35.8355 6.49064 35.1568 5.49278 34.3012 4.65234V4.65234Z"
									stroke="#51A881" stroke-width="2.2" stroke-linecap="round"
									stroke-linejoin="round"></path>
								</svg>
							</button>
						<?php } ?>
						<?php if ($this->config->get('show_compare') == '1')  { ?>
							<button class="addToCompareBtn" onclick="addToCompare('<?php echo $product_id; ?>');" title="В сравнение">
								<i class="icons compare_icompare"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>	
							</button>
						<?php } ?>
			</div>
		<?php } ?>	
		
		
		
		<?php if (!empty($active_action)) { ?>
			<div class="product-info__active_action">					
				<h3>Акция! <?php echo $active_action['caption']; ?></h3>
				<a href="<?php echo $active_action['href']; ?>">Узнать детали</a>
			</div>
		<?php } ?>
		
		
		<div class="product-info__delivery">					
			
			<?php if ($show_delivery_terms) { ?>
				<div class="delivery_terms <? if (empty($bought_for_week)) { ?>position_pluses-item<? } ?>">					
					
					<?php
						//Dirty fix
						$stock_type = $GP_STOCK_TYPE; 
					?>
					
					<? if ($stock_type == 'in_stock_in_country') { ?>
						
						<? switch ($this->config->get('config_store_id')){
							case 0 : $_city = 'Москве'; break;
							case 1 : $_city = 'Киеве'; break;
							case 2 : $_city = 'Москве'; break;
							default : $_city = ''; break;
						}
						?>
						<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i>Есть в наличии в <? echo $_city; ?><br>
						<!-- <span style="color: rgb(76, 102, 0);font-size: 13px;">доставка / отправка 1-3 дня</span> --></p>
						<? } elseif ($stock_type == 'in_stock_in_moscow_for_kzby') { ?>
						<p style="color: rgb(76, 102, 0);font-size: 13px;"><i class="far fa-check-circle"></i>  доставка/отправка 7-14 дней</p>
						<? } elseif ($stock_type == 'in_stock_in_central_msk') { ?>								
						
						<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> Товар в наличии На нашем складе<br>
						<span style="color: rgb(76, 102, 0);font-size: 13px;">доставка / отправка 5-7 дней</span></p>
						<? } elseif ($stock_type == 'in_stock_in_central') { ?>
						<? if ($this->config->get('config_store_id') == 1) { ?>
							<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> На складе поставщика<br><span style="color: rgb(76, 102, 0);font-size: 13px;">доставка / отправка 10-14 д.</span></p>
							<? } else { ?>
							<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> На складе поставщика<br>
							<span style="color: rgb(76, 102, 0);font-size: 13px;">доставка / отправка 15-30д.</span></p>
						<? } ?>
						<? } elseif ($stock_type == 'supplier_has') { ?>
						<? if ($this->config->get('config_store_id') == 1) { ?>
							<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> На складе поставщика<br><span style="color: rgb(76, 102, 0);font-size: 13px;">доставка/отправка 10-14д.</span></p>
							<? } else { ?>
							<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> На складе поставщика<br><span style="color: rgb(76, 102, 0);font-size: 13px;">доставка/отправка 15-30д.</span></p>
						<? } ?>
						<? } elseif ($stock_type == 'need_ask_about_stock') { ?>
						<p style="color: #ffc34f"><i class="far fa-times-circle"></i> Наличие уточняйте</p>
						<? } elseif ($stock_type == 'supplier_has_no_can_not_buy') { ?>
						<p style="color: red"><i class="far fa-times-circle"></i> Нет в наличии</p>
						
						<? } else { ?>
						<p style="color: #ffc34f"><i class="far fa-times-circle"></i> Наличие уточняйте</p>
					<? } ?>
					<?php if (!empty($delivery)) { ?>
						<?php echo $delivery; ?>
					<?php } ?>
				</div>
			<? } ?>
			
			<? if ($is_markdown) { ?>	
				<div id="markdown-reason-btn" class="markdown-reason do-popup-element" data-target="markdown-reason">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500.84 553" width="30" height="30"><defs><style>.cls-3{fill:#ffc34f}.cls-4{fill:#ffc34f}</style></defs><g id="Слой_2" data-name="Слой 2"><g id="Слой_1-2" data-name="Слой 1"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1-2"><path d="M250.42 553a16.69 16.69 0 0 1-9.71-3.11L7 382.93a16.7 16.7 0 0 1 9.71-30.28h50.05V16.7A16.7 16.7 0 0 1 83.46 0h333.91a16.7 16.7 0 0 1 16.7 16.7v336h50.08a16.7 16.7 0 0 1 9.71 30.3L260.12 550a16.68 16.68 0 0 1-9.7 3z" class="cls-5" fill="#51a881"/><path d="M484.15 352.65h-50.08V16.7A16.7 16.7 0 0 0 417.37 0h-167v553a16.68 16.68 0 0 0 9.7-3.11l233.74-167a16.7 16.7 0 0 0-9.71-30.28z" style="isolation:isolate" opacity=".1"/><path class="cls-3" d="M183.63 248.42a50.09 50.09 0 1 1 50.09-50.09 50.14 50.14 0 0 1-50.09 50.09zm0-66.78a16.7 16.7 0 1 0 16.7 16.7 16.71 16.71 0 0 0-16.7-16.7z"/><path class="cls-4" d="M317.2 382a50.09 50.09 0 1 1 50.09-50.09A50.14 50.14 0 0 1 317.2 382zm0-66.78a16.7 16.7 0 1 0 16.69 16.71 16.72 16.72 0 0 0-16.69-16.73z"/><path class="cls-3" d="M179.58 352.65a16.7 16.7 0 0 1-11.81-28.5l141.67-141.67a16.69 16.69 0 1 1 23.61 23.61L191.38 347.76a16.64 16.64 0 0 1-11.8 4.89z"/><path class="cls-4" d="M309.44 182.48l-59 59v47.22l82.63-82.64a16.69 16.69 0 1 0-23.61-23.61z"/></g></g></g></g></svg>
					<span>Причина уценки</span>
				</div>
			<?php } ?>
			
			<?php if ($free_delivery) { ?>
				<?php if ($free_delivery == 'moscow') { ?>
					<div class="delivery_info_free ruMoskow"></div>
					<? } elseif($free_delivery == 'russia') { ?>
					<div class="delivery_info_free"></div>
					<? } elseif($free_delivery == 'kyiv') { ?>
					<div class="delivery_info_free uaKyiv"></div>
					<? } elseif($free_delivery == 'ukraine') { ?>
					<div class="delivery_info_free"></div>
				<? } ?>
			<?php } ?>
			
		</div>
		
		
		<?php if (!empty($active_coupon)) { ?>
			<div class="active-coupon">
				<span class="title-coupon">Цена по промокоду</span>
				<div class="active-coupon__price">
					<?php echo $active_coupon['coupon_price']; ?>
				</div>
				<div class="active-coupon__promocode">
					<span id="promo-code-txt" onclick="copytext(this)" title="скопировать промокод"><?php echo $active_coupon['code']; ?></span><button class="btn-copy" onclick="copytext('#promo-code-txt')" title="скопировать промокод"><span class="tooltiptext" style="display: none;">Промокод скопирован</span></button>
				</div>
				<div class="active-coupon__datend" style="opacity: 0;">
					Завершается через <b><div id="note"></div></b>
					
				</div>
			</div>
		<?php } ?>
		<!--pluses-item-->
		<div class="pluses-item">
			<ul>
				<? if ($bought_for_week) { ?>
					<li>
						<img src="/catalog/view/theme/default/img/pluses-icon3.svg" alt="">
						<p><? echo $bought_for_week ?></p>
					</li>
				<? } ?>
				<li>
					<a href="javascript:void(0)" onclick="do_notification_block(31,'delivery_block');return false;" data-target="info_delivery" class="do-popup-element" >
						<img src="/catalog/view/theme/default/img/pluses-icon-dev.svg" alt="">
						<p>Варианты доставки <i class="fas fa-info-circle"></i></p>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>