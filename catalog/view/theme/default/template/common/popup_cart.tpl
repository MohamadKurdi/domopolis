<style type="text/css">
	.product_popup_list_wrap{
		display: flex;
		flex-direction: column;
		overflow: hidden;
		width: 100%;
	}
	.product_popup_list_wrap .title{
		display: flex;
		align-items: center;
		gap: 10px;
		font-size: 23px;
		font-weight: 500;
		line-height: 1em;

	}
	.product_popup_list_wrap .title a span{
		font-size: 12px;
		color: #e25c1d;
		display: flex;
		align-items: center;
		gap: 10px;
	}
	.product_popup_list_wrap .title a span i {
		font-size: 12px;
		transition: .15s ease-in-out;
	}
	.popup_cart_wrap .swiper-container{
		width: 100%;
	}
	.product_popup_list_wrap  .title_wrap{
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 20px;
		margin-top: 20px;
	}
	.product_popup_list_wrap  .title_wrap .nav-group{
		display: flex;
		gap: 20px;
	}
	.product_popup_list_wrap  .title_wrap .nav-group > div{
		display: flex;
		cursor: pointer;
	}
	.product_popup_list_wrap .swiper-slide .title_prod{
		font-size: 15px;
		display: -webkit-box;
		-webkit-line-clamp: 3;
		-webkit-box-orient: vertical;
		overflow: hidden;
		text-overflow: ellipsis;
		height: 67.5px;
		margin-bottom: 10px;
	}
	.product_popup_list_wrap .swiper-slide .img{
		display: flex;
		max-height: 100px;
		overflow: hidden;
		margin-bottom: 10px;
	}
	.product_popup_list_wrap .swiper-slide .img img{
		height: auto;
		width: auto;
		object-fit: contain;
		display: flex;
	}
	.product_popup_list_wrap .swiper-slide .product__price{
		flex-direction: row;
		flex-wrap: inherit;
		gap: 10px;
	}
	#ajaxcheckout #quick_order_popap{
		position: relative;
	}
	#ajaxcheckout #quick_order_popap .error {
		position: absolute;
		top: -22px;
	}
	@media screen and (min-width: 1000px){
		.popup_cart_wrap .mini-cart-infos{
			max-height: 380px;
			overflow-y: auto;
			display: block;
		}
		/* width */
		.popup_cart_wrap .mini-cart-infos::-webkit-scrollbar {
			width: 5px;
		}

		/* Track */
		.popup_cart_wrap .mini-cart-infos::-webkit-scrollbar-track {
			background: #f1f1f1;
		}

		/* Handle */
		.popup_cart_wrap .mini-cart-infos::-webkit-scrollbar-thumb {
			background: #51a881;
			border-radius: 2px;
		}

		/* Handle on hover */
		.popup_cart_wrap .mini-cart-infos::-webkit-scrollbar-thumb:hover {
			opacity: 0.8;
		}
	}
	@media screen and (max-width: 560px) {
		.product_popup_list_wrap .swiper-slide{
			background: #fff;
			border-radius: 10px;
			padding: 10px;
			border: 1px solid transparent;
		}
		#popup-cart .object{
			overflow-x: hidden;
		}
		.product_popup_list_wrap {
			overflow-x: hidden;
		}
	}
</style>
<div class="object popup_cart_wrap">

	<div class="overlay-popup-close"></div>

	<?php if (!empty($products)) { ?>
		<div class="delete_block" style="position: relative;">
			<div class="checkbox">
				<input type="checkbox" id="choose_all">
				<label for="choose_all"><?php echo $text_retranslate_1; ?></label>
			</div>
			<button id="del_choose" disabled><?php echo $text_retranslate_2; ?></button>
			<div class="popup-delete">
				<div class="popup-delete__favorite">
					<input hidden class="product_id" value="" product-id="" style="display:none;"/>
					<a onclick="delWishNew();">
						<svg width="39" height="34" viewbox="0 0 39 34" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M34.3012 4.65222C33.446 3.81139 32.4306 3.14438 31.313 2.6893C30.1954 2.23423 28.9975 2 27.7878 2C26.5781 2 25.3802 2.23423 24.2626 2.6893C23.145 3.14438 22.1296 3.81139 21.2744 4.65222L19.4996 6.39642L17.7247 4.65222C15.9972 2.95459 13.6543 2.00088 11.2113 2.00088C8.76832 2.00088 6.42539 2.95459 4.69793 4.65222C2.97048 6.34984 2 8.65231 2 11.0531C2 13.4539 2.97048 15.7564 4.69793 17.454L6.47279 19.1982L19.4996 32L32.5263 19.1982L34.3012 17.454C35.1568 16.6136 35.8355 15.6157 36.2986 14.5174C36.7617 13.4191 37 12.2419 37 11.0531C37 9.86428 36.7617 8.68709 36.2986 7.5888C35.8355 6.49052 35.1568 5.49265 34.3012 4.65222V4.65222Z" stroke="#51A881" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
						</svg>
						<span><?php echo $text_retranslate_3; ?></span>
					</a>
				</div>
				<div class="popup-delete__del">
					<input hidden class="product_id" value="" product-id="" style="display:none;"/>
					<a onclick="delNew();">
						<svg width="34" height="34" viewbox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M33 1L1 33M1 1L33 33" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
						</svg>
						<span><?php echo $text_retranslate_4; ?></span>
					</a>
				</div>
				<div class="popup-delete__cancel"><a href="#"><?php echo $text_retranslate_5; ?></a></div>
			</div>
		</div>
	<?php } ?>

	<div id="ajaxcartmodal" style="padding: 0;">
		<?php if (!empty($products)) { ?>
			<div class="mini-cart-infos">

				<?php if ($this->config->get('config_divide_cart_by_stock')) { ?>	
					<?php $reparsedProducts = reparseCartProductsByStock($products); ?>
					<?php if (!empty($reparsedProducts['in_stock'])) { $products = $reparsedProducts['in_stock']; ?>

					<h4><?php echo $in_stock_text_h4; ?></h4>
					<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/popupcart_products.tpl')); ?>

					<? unset($product); } ?>

					<?php if (!empty($reparsedProducts['not_in_stock'])) { $products = $reparsedProducts['not_in_stock'];  ?>

					<h4><?php echo $this->language->get('text_not_stock_' . $this->config->get('config_country_id')); ?></h4>
					<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/popupcart_products.tpl')); ?>

					<? unset($product); } ?>
					
					<?php if (!empty($reparsedProducts['certificates'])) { $products = $reparsedProducts['certificates'];  ?>

					<h4><?php echo $this->language->get('text_present_certificates'); ?></h4>
					<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/popupcart_products.tpl')); ?>

					<? unset($product); } ?>
				<?php } else { ?>
					<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/popupcart_products.tpl')); ?>
				<?php } ?>

			</div>
			<div class="total-bg">
				<table class="cart-modal__tottal">
					<?php foreach ($totals as $total) { ?>
						<tr id="ajaxtotal" class="ajaxtable_tr2">
							<td class="text"><b><?php echo $total['title']; ?></b> <b>:</b></td>
							<td class="total"><nobr><?php echo $total['text']; ?></nobr></td>
						</tr>
					<?php } ?>
				</table>
				<? if ($this->config->get('config_store_id') == 2222) { ?>
					<div class="promocode-block">


						<? if (!$coupon) { ?>
							<span class="btn-show-promo-code"><?php echo $text_retranslate_14; ?></span>
							<div class="promo-code" style="visibility: hidden;">
								<input id="promo-code-input" type="text" placeholder="Ввести промокод" name="coupon" value="<?php echo $coupon; ?>" onchange=""  style="border-bottom: 1px solid #79bc9e;margin-right: 15px;" /> <span class="promo-code-txt" onclick=""><?php echo $text_retranslate_15; ?></span>
							</div>
						<? } else { ?>
							<div class="promo-code-active">
								<span class="inputs" onclick="$('#popup-cart-coupon-use').toggle();" style="cursor:pointer; border-bottom:1px dashed #2B2B2B;"><?php echo $text_retranslate_16; ?></span> <span><b>(<?php echo $coupon; ?>)</b></span>

								<span class="inputs" id="popup-cart-coupon-use" style="display:none;"><input type="text" name="coupon" value="<?php echo $coupon; ?>" onchange=""  />
									<span class="inputs buttons"><a id="simplecheckout_button_cart" onclick="" class="button btn"><span><?php echo $button_use; ?></span></a></span>
								</span>
							</div>
						<? } ?>

					</div>
				<?php } ?>
				<div id="ajaxcheckout" class="btn-group">
					<a id="gotoshopping" class="close-modal-cart">
						<svg width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="https://www.w3.org/2000/svg">
							<path d="M1 13L6 7L0.999999 1" stroke="#51a881" stroke-width="2" stroke-linejoin="round"></path>
						</svg>
						<?php echo $text_retranslate_17; ?></a>


						<?php if (!$this->config->get('config_disable_fast_orders')) { ?>
							<div id="quick_order_popap">
								<input id="quickfastorder-dialog-phone" class="field" type="tel" value="<?=$telephone ?>" name="quickfastorder-phone" placeholder="<?php echo str_replace('9', '_', $mask) ?>">
								<input type="button" class=" btn disable-btn" id="quick_order_popap_btn" name="" value="<?php echo $text_retranslate_18; ?>" title="">
								<div class="error"></div>
							</div>
						<?php } ?>


						<a href="<?php echo $checkout; ?>" onclick="document.location.href = '<?php echo $checkout; ?>'; " id="gotoorder" class="button btn"><?php echo $text_retranslate_19; ?>
						<svg width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="https://www.w3.org/2000/svg">
							<path d="M1 13L6 7L0.999999 1" stroke="white" stroke-width="2" stroke-linejoin="round"></path>
						</svg>
					</a>
				</div>
			</div>
			<?php if (!empty($ajaxcartproducts)) { ?>

				<div class="product_popup_list_wrap" >
					<div class="title_wrap">
						<span class="title">
							<?php echo $config_popupcartblocktitle; ?>
							<!-- <a href="<?php echo $href_view_all; ?>" title="<?php echo $config_popupcartblocktitle; ?>"> -->

								<a href="/c6614" title="<?php echo $config_popupcartblocktitle; ?>">
									<span>
										<?php echo $text_view_all; ?> 
										<i class="fa-arrow-circle-right fas"></i>
									</span>
								</a>
							</span>
							
							<div class="nav-group">
								<div class="swiper-prev-slide swiper-button-disabled" tabindex="0" role="button" aria-label="Previous slide" aria-disabled="true"><svg xmlns="https://www.w3.org/2000/svg" fill="none" height="40" viewBox="0 0 60 60" width="40"><rect height="58" stroke="#51A881" stroke-width="2" width="58" x="1" y="1"></rect> <path d="M35 42L23 30L35 18" stroke="#51A881" stroke-width="3"></path></svg> 
								</div>
								<div class="swiper-next-slide" tabindex="0" role="button" aria-label="Next slide" aria-disabled="false"><svg xmlns="https://www.w3.org/2000/svg" fill="none" height="40" viewBox="0 0 60 60" width="40"><rect height="58" stroke="#51A881" stroke-width="2" transform="matrix(-1 0 0 1 58 0)" width="58" x="-1" y="1"></rect> <path d="M25 42L37 30L25 18" stroke="#51A881" stroke-width="3"></path></svg> 
								</div>
							</div>
							
						</div>		
						<div class="swiper-container">
							<!-- swiper-wrapper -->
							<div class="swiper-wrapper">
								<?php foreach ($ajaxcartproducts as $product) { ?>
									<div class="swiper-slide">
										<?php if ($product['thumb']) { ?>
											<a href="<?php echo $product['href']; ?>" class="img">
												<img  src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
											</a>
										<?php } ?>


										<a href="<?php echo $product['href']; ?>" class="title_prod"><?php echo $product['name']; ?></a>


										<?php if ($product['price']) { ?>
											<div class="product__price">
												<?php if (!$product['special']) { ?>
													<div class="product__price-new"><?php echo $product['special']; ?></div>
												<?php } else { ?>
													<div class="product__price-new"><?php echo $product['special']; ?></div>
													<div class="product__price-old"><?php echo $product['price']; ?></div>
												<?php } ?>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<!-- /swiper-wrapper -->
						</div>
					</div>
					<script>
						var swiperSales = new Swiper(".product_popup_list_wrap .swiper-container", {
							slidesPerView: 5,
							loop: false,
							centeredSlides: false,
							grabCursor: false,
							allowTouchMove: false,
							spaceBetween: 16,
							navigation: {
								nextEl: ".product_popup_list_wrap .swiper-next-slide",
								prevEl: ".product_popup_list_wrap .swiper-prev-slide",
							},
							breakpoints: {
								200: {
									slidesPerView: 2,
									spaceBetween: 10,
									autoHeight: false,
								},
								768: {
									autoHeight: false,
								},
								1300: {
									slidesPerView: 4,
									spaceBetween: 16,
								},
								1400: {
									spaceBetween: 16,
									autoHeight: false,
									slidesPerView: 5,
								},
							},
						});
						setTimeout(function(){
							//swiperSales.reInt();
							swiperSales.update();
						}, 500);
					</script>
				<?php } ?>
			<?php } else { ?>
				<div class="mini-cart-infos cart-clear-text">
					<div id="ajaxcartempty"><?php echo $text_retranslate_20; ?></div>
					<div id="quick_order_popap"><div id="quickfastorder-dialog-phone"></div></div>
				</div>
			<?php } ?>
		</div>


		<script type="text/javascript">

			<?php if ($this->config->get('config_sendsay_enable_marketing')){ ?>
				(window['sndsyApiOnReady'] = window['sndsyApiOnReady'] || []).push(function () {
					sndsyApi.ssecEvent(
						'BASKET_ADD',
						[{
							'transaction_status': 1,
							'transaction_dt'	: '<?php echo date('Y-m-d H:i:s'); ?>',
							'transaction_sum'	: '<? echo $google_ecommerce_info['transactionTotal'] ?>',
							'update_per_item'   : 0,
							'items': [
								<? $i=0; foreach ($google_ecommerce_info['transactionProducts'] as $transactionProduct) { ?>
									{
										'id' 		: '<? echo $transactionProduct['id']; ?>',
										'model'		: '<? echo $transactionProduct['model'] ?>',
										'name' 		: '<? echo $transactionProduct['name'] ?>',
										'picture' 	: ['<? echo $transactionProduct['image'] ?>'],
										'vendor' 	: '<? echo $transactionProduct['manufacturer'] ?>',
										'category_id'		: '<? echo $transactionProduct['main_category_id'] ?>',
										'category_paths' 	: ['<? echo $transactionProduct['category'] ?>'],
										'price' 	: '<? echo $transactionProduct['price'] ?>',
										'qnt' 		: '<? echo $transactionProduct['quantity'] ?>'
									}	<? if ($i < count($google_ecommerce_info['transactionProducts'])) { ?>,<?php } $i++; ?>
								<? } ?>
								]
						}]
						<?php /* if ($this->customer->getIfRealEmail()) { ?>,
							{ email: '<?php echo $this->customer->getIfRealEmail(); ?>' } 
						<?php } */ ?>
					);
				});
				typeof sndsyApi != 'undefined' && sndsyApi.runQueue();
			<?php } ?>


			function startLoader(){
				loadImgPopup.show();
				contentPopup.css('opacity','0.5');
			}

			function endLoader(){
				loadImgPopup.hide();
				contentPopup.css('opacity','1');
			}

			function pushVKRetargetingInfo(pid, event){
				if((typeof VK !== 'undefined')){
					$.ajax({
						url: "/index.php?route=product/product/getEcommerceInfo",
						data: "product_id=" + pid,
						dataType: "json",
						error: function(e){
							console.log(e);
						},
						success: function(json) {
							console.log('VK trigger ' + event);

							VK.Retargeting.ProductEvent(
								json.config_vk_pricelist_id, 
								event, 
								{
									'products' : [{
										id: json.product_id,
										price_from: 0,
										price: json.price
									}], 
									'currency_code': json.currency, 
									'total_price': json.price
								}
								);
						}
					});
				}
			}

			function pushEcommerceInfo(pid, quantity, event){
				$.ajax({
					url: "/index.php?route=product/product/getEcommerceInfo",
					data: "product_id=" + pid,
					dataType: "json",
					error: function(e){
						console.log(e);
					},
					success: function(json) {
						window.dataLayer = window.dataLayer || [];
						console.log('dataLayer.push ' + event);
						dataLayer.push({
							'event': event,
							'ecommerce': {
								'currencyCode': json.currency,
								'add': {
									'products': [{
										'id':	json.product_id,
										'name':	json.name,
										'price':json.price,
										'brand':json.brand,
										'category':json.category,
										'quantity': quantity
									}]
								}
							}
						});
					}
				});
			}

			function reloadCart(){
				console.log('reloadCart fired');
				$.ajax({
					url: '/index.php?route=common/popupcart',
					dataType: 'html',
					beforeSend : function(){
						startLoader();
					},
					complete : function(){
						endLoader();
					},
					success: function(html) {
						$('#popup-cart').html(html);
						$('#header-small-cart').load('/index.php?route=module/cart');
						$('#cart').load('index.php?route=module/cart #cart > *');
						if ((typeof simplecheckout_reload !== "undefined")){
							console.log('simplecheckout_reload');
							simplecheckout_reload('product_removed');
						}
					}
				});
			}

			<?php if (!$this->config->get('config_disable_fast_orders')) { ?>	
				$('#quick_order_popap_btn').on('click', function (e) {
					console.log($(this).hasClass('disable-btn'));
					if ($(this).hasClass('disable-btn')){
						return false;
					}
					$.ajax({
						type: 'POST',
						dataType: 'JSON',
						url: '/index.php?route=checkout/quickorder/createorderfast',
						data: $('#quick_order_popap #quickfastorder-dialog-phone'),
						success: function(data, json) {
							if (data.error) {
								$('#quick_order_popap .error').html('');
								$.each(data.error, function (i, v) {
									$('#quick_order_popap .error').prepend(v+"<br>");
								});
							} else {
								if (data.success) {
									window.location = data.redirect;
								}
							}
						}
					});
				});
			<?php } ?>

			function checkButtonTrigger(){
				<?php if (!$this->config->get('config_disable_fast_orders')) { ?>	
					<? if (mb_strlen($mask, 'UTF-8') > 1) { ?>
						var phone_length = $('#quickfastorder-dialog-phone').val().length;

						if (phone_length >= 15){
							$('#quick_order_popap_btn').removeClass('disable-btn').addClass('enable-btn');
						} else {
							$('#quick_order_popap_btn').removeClass('enable-btn').addClass('disable-btn');
						}
					<?php } else { ?>
						$('#quick_order_popap_btn').removeClass('disable-btn').addClass('enable-btn');
					<?php } ?>
				<?php } ?>
			}

			$('#quickfastorder-dialog-phone').on('keyup',function(){
				checkButtonTrigger();
			});

			checkButtonTrigger();


			<? if (mb_strlen($mask, 'UTF-8') > 1) { ?>
				$('#quickfastorder-dialog-phone').inputmask("<?php echo $mask; ?>",{ "clearIncomplete": true });
			<? } ?>


			$(".colorbox-popup").each(function(){
				$(this).click(function(){
					var img = $(this);
					var src = img.attr('src');
					$("body").append("<div class='popup_rev' style='background: #00000057;'>"+
						"<div class='modal-content cart-popap-modal'><div class='overlay-popup-close btn-modal'></div><img src='"+src+"' class='popup_img_rev' /><div>"+
						"</div>");
					$('.popup_bg_rev').fadeIn(300);
					$(".popup_rev").fadeIn(300);
					$(".popup_bg_rev, .btn-modal").click(function(){
						$(".popup_rev").fadeOut(300);
						$('.popup_bg_rev').fadeOut(300);
						$(this).empty();
						$(".popup_rev").remove();
						setTimeout(function() {
							$(".popup_rev").remove();
						}, 300);
					});
				});
			});

			$('.zoom-in-popap').each(function(){
				$(this).click(function(){
					$(this).prev('.colorbox-popup').trigger('click');
				});
			});

			$('#choose_all').click(function(){
				if ($(this).is(':checked')){
					$('.choose_input:checkbox').prop('checked', true);
					$('#del_choose').removeAttr('disabled');
				} else {
					$('.choose_input:checkbox').prop('checked', false);
					$('#del_choose').prop('disabled','true');
				}
			});

			$('.choose_input:checkbox').each(function(){
				$(this).click(function(){
					if ($(this).not(':checked')){
						$('#choose_all').prop('checked', false);
					}

					let neighbor = $(this).closest('.ajaxtable_tr_new').siblings(),
					choose = neighbor.find('.choose_input');

					if ($(choose).is(':checked')){
						$('#del_choose').removeAttr('disabled');
					} else {
						$('#del_choose').prop('disabled','true');
					}

					if ($(this).is(':checked')){
						$('#del_choose').removeAttr('disabled');
					}

				})
			});

			$('#del_choose').click(function () {
				$(this).parents('.delete_block').find('.popup-delete').css('display', 'flex');
				return false;
			});

			$('.delete_block .popup-delete__cancel').click(function () {
				$(this).parents('.delete_block').find('.popup-delete').css('display', 'none');
				return false;
			});

			var loadImgPopup = $('#ajaxcartloadimg');
			var contentPopup = $('.ajaxtable_table');
			function updateCart(id) {
				$('#ajaxcartloadimg').show();
				$('.quantity-p').click();
			}

			function validate(input) {
				input.value = input.value.replace(/[^\d,]/g, '');
			};


			function qtVal(id){
				let quantity = parseInt($(id).parent().children('.qt').val());
				(quantity == 0) ? quantity = minimum: false;

				pid = $(id).parent().children('.product_id').val();
				$.ajax({
					url: '/index.php?route=common/popupcart&update='+pid+'&qty='+quantity,
					type: 'post',
					dataType: 'html',
					beforeSend : function(){
						startLoader();
					},
					complete : function(){
						endLoader();
						reloadCart();
					},
					success:function(data) {}
				});
			}

			function plus(id){
				let quantity 	= parseInt($(id).parent().children('.qt').val());
				let minimum 	= parseInt($(id).parent().children('.qt').attr('data-minimum')) || 1;
				quantity 		= quantity + minimum;
				(quantity == 0) ? quantity = minimum: false;

				pid = $(id).parent().children('.product_id').val();
				$.ajax({
					url: '/index.php?route=common/popupcart&update='+pid+'&qty='+quantity,
					type: 'post',
					dataType: 'html',
					beforeSend : function(){
						startLoader();
					},
					complete : function(){
						endLoader();
						reloadCart();
					},
					success:function(data) {
						pushEcommerceInfo(pid, quantity, 'addToCart');
						pushVKRetargetingInfo(pid, 'add_to_cart');
					}
				});
			}

			function minus(id){
				let quantity 	= parseInt($(id).parent().children('.qt').val());
				let minimum 	= parseInt($(id).parent().children('.qt').attr('data-minimum')) || 1;
				quantity 		= quantity - minimum;
				(quantity == 0) ? quantity = minimum: false;
				pid = $(id).parent().children('.product_id').val();
				$.ajax({
					url: '/index.php?route=common/popupcart&update='+pid+'&qty='+quantity,
					type: 'post',
					dataType: 'html',
					beforeSend : function(){
						startLoader();
					},
					complete : function(){
						endLoader();
						reloadCart();
					},
					success:function(data) {
						pushEcommerceInfo(pid, quantity, 'removeFromCart');
						pushVKRetargetingInfo(pid, 'remove_from_cart');
					}
				});
			}

			function delNew(){
				var checkedData = [];
				$('.checkbox .choose_input:checked').each(function(){
					var pid = $(this).parent().children('.product_id').val();
					var quantityTotal = parseInt($(this).parent().parent().parent().children('.quantity').children().children('.qt').val());
					var quantity = 0;
					pushEcommerceInfo(pid, quantityTotal, 'removeFromCart');
					pushVKRetargetingInfo(pid, 'remove_from_cart');
					checkedData.push(pid + '_qt_' + quantity);
					console.log(checkedData);
				});
				startLoader();
				setTimeout(function(){
					$.ajax({
						url: '/index.php?route=common/popupcart&update=explicit',
						type: 'post',
						data: {explicit : checkedData},
						dataType: 'html',
						beforeSend : function(){
							startLoader();
						},
						complete : function(){
							endLoader();
							reloadCart();
						},
						success: function(data) {

						}
					});
				},1000)
			}

			function delWishNew(){
				var checkedData = [];
				$('.checkbox .choose_input:checked').each(function(){
					var pid = $(this).parent().children('.product_id').val();
					var wis = $(this).parent().children('.product_id').attr('product-id');
					var quantityTotal = parseInt($(this).parent().parent().parent().children('.quantity').children().children('.qt').val());
					var quantity = 0;
					pushEcommerceInfo(pid,wis, quantityTotal, 'removeFromCart');
					checkedData.push(pid + '_qt_' + quantity);
					console.log(checkedData);
				});
				startLoader();

				setTimeout(function(){
					$.ajax({
						url: '/index.php?route=common/popupcart&update=explicit',
						data: {explicit : checkedData},
						dataType: 'html',
						beforeSend : function(){

							startLoader();
						},
						complete : function(){
							endLoader();
							reloadCart();
						},
						success: function(data) {
						}
					});
				},1000)
			}

			$('.close-modal-cart, .overlay-popup-close').on('click', function(){
				$('#main-overlay-popup').click();
			});

			<? if ($this->config->get('config_store_id') == 222) { ?>
				$('.btn-show-promo-code').on('click', function(){
					$(this).hide();
					$('.promo-code').css('visibility', 'visible');

				});
			<?php } ?>
		</script>
	</div>
</div>
