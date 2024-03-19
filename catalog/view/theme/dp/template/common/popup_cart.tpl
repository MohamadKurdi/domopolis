<div class="object popup_cart_wrap">
	<style>
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
		    font-family: 'Inter',sans-serif;
		    font-size: 12px!important;
		    color: #404345!important;
		    display: flex;
		    align-items: center;
		    gap: 10px;
		    font-weight: 500;
		    line-height: 17px;
		    background: #BFEA43;
		    border-radius: 20px;
		    padding: 3px 16px;
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
			display: flex !important;
			cursor: pointer;
		}
		.product_popup_list_wrap .swiper-slide .title_prod{

		   
		    overflow: hidden;
		    text-overflow: ellipsis;
		    height: 57px;
		    margin-bottom: 10px;
		    display: block;
		    transition: color 0.2s ease;
		    font-weight: 500;
		    font-size: 16px;
		    line-height: 19px;
		    color: #121415;
		     display: -webkit-box;
		    -webkit-line-clamp: 3;
		    -webkit-box-orient: vertical;
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
		.product_popup_list_wrap .nav-group > div{
		    display: flex;
		    justify-content: center;
		    align-items: center;
		    cursor: pointer;
		    z-index: 10;
		    width: 44px;
		    height: 44px;
		    background: #FFF;
		    border: 1px solid #C9CBCE;
		    box-shadow: 0 3px 15px rgba(0,0,0,.16);
		    border-radius: 30px;
		    overflow: hidden;
		}
		.product_popup_list_wrap .nav-group > div.swiper-button-disabled{
			opacity: 0.5;
			cursor: default;
		}
		.product_popup_list_wrap .nav-group > div.swiper-prev-slide svg{
		    transform: rotate(180deg);
		}
		.product_popup_list_wrap .swiper-slide .product__price .product__price-new{
			font-weight: 600;
		    font-size: 18px;
		    line-height: 22px;
		    color: #121415;
		}
		.product_popup_list_wrap .swiper-slide .product__price .product__price-old{
			font-weight: 500;
		    font-size: 14px;
		    line-height: 17px;
		    color: #888F97;
		}
		.product_popup_list_wrap .swiper-slide .product__price{
			display: flex;
			flex-direction: column;
		    flex-wrap: inherit;
		    gap: 5px;
		}
		.product_popup_list_wrap .swiper-slide .product__price .product__price_wrap{
			display: flex;
			gap: 10px;
		}
		.product_popup_list_wrap .swiper-slide .product__price .product__price_wrap .price__sale{
			font-weight: 500;
		    font-size: 12px;
		    line-height: 15px;
		    color: #FFFFFF;
		    position: inherit;
		    background: #EB3274;
		    border-radius: 23px;
		    padding: 2px 4px;
		}

		#ajaxcheckout #quick_order_popap {
		    position: relative;
		}
		#ajaxcheckout #quick_order_popap .error {
		    position: absolute;
		    top: 100%;
		}
		.notification_popup_cart{

		}
		.notification_popup_cart div{
			display: flex;
		    flex-direction: column;
		    align-items: center;
		    padding: 15px 20px;
		    max-width: max-content;
		    margin: auto;
		    background: #fff2cf;
		    margin-top: 20px;
		    gap: 10px;
		    border-radius: 4px;
		}
		.notification_popup_cart div span{
		    font-size: 19px;
		    font-weight: 600;
		    text-align: center;
		}
		.alert.alert-small{font-size:12px!important;}
		#ajaxtable_table{
			margin-top: 0;
		}
		#ajaxtable_table tr .price-block .reward_wrap .text b{color:#51a62d!important;}
		.popup_cart_wrap .head-modal{
			display: flex;
			justify-content: space-between;
			margin-bottom: 24px;
			align-items: center;
		}
		.popup_cart_wrap .head-modal .title{
			font-family: 'Unbounded',sans-serif;
			font-weight: 500;
			font-size: 22px;
			line-height: 27px;
			color: #121415;
		}
		.popup_cart_wrap .head-modal .overlay-popup-close{
			position: unset;
			background-image: none;
			border: 0;
		}
		.popup_cart_wrap  .mini-cart-infos{
			background: #FFFFFF;
			border: 1px solid #DDE1E4;
			box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
			border-radius: 12px;
		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td{
			padding: 20px 0 20px 20px;
		}
		.popup_cart_wrap  .mini-cart-infos .ajaxtable_tbody tr{
			margin: 0;
			position: relative;
		}
		.popup_cart_wrap .mini-cart-infos .ajaxtable_tbody tr:not(:first-child)::after{
			content: '';
			background: #DDE1E4;
			width: calc(100% - 20px);
			height: 1px;
			position: absolute;
			right: 0;
		}
		.popup_cart_wrap .mini-cart-infos{
			max-height: 460px;
    		overflow-x: auto;
		}
		.popup_cart_wrap .mini-cart-infos::-webkit-scrollbar {
		    width: 8px;
		}
		.popup_cart_wrap .mini-cart-infos::-webkit-scrollbar-thumb {
		    background: #BFEA43;
		    border-radius: 4px;
		}
		.popup_cart_wrap .mini-cart-infos::-webkit-scrollbar-track {
		    background: #f1f1f17d;
		}
		.popup_cart_wrap .cart-modal__tottal .total{
		    font-size: 18px;
		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.image div{
			width: 130px;
			height: 130px;
			background: #FFFFFF;
			border: 1px solid #DDE1E4;
			box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
			border-radius: 8px;
			display: flex;
			align-items: center;
			justify-content: center;
			position: relative;
		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.image div i{

		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.names_product{
			color: #121415;
		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.names_product > a{
			font-weight: 500;
			font-size: 16px;
			line-height: 19px;
			color: #121415;
			max-width: 330px;
			display: -webkit-box;
			-webkit-line-clamp: 3;
			-webkit-box-orient: vertical;
			overflow: hidden;
			text-overflow: ellipsis;
			margin-bottom: 10px;
		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.names_product .product__price{

		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.names_product .product__price .product__price-new{
		    font-weight: 600;
		    font-size: 18px;
		    line-height: 22px;
		    color: #121415;
		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.names_product .product__price .product__price-old_wrap{
		    display: flex;
		    gap: 11px;
		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.names_product .product__price .product__price-old_wrap .product__price-old{
		    font-weight: 500;
		    font-size: 14px;
		    line-height: 17px;
		    color: #888F97;
		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.names_product .product__price .product__price-old_wrap  .price__sale{
		    font-weight: 500;
		    font-size: 12px;
		    line-height: 15px;
		    color: #FFFFFF;
		    position: inherit;
		    background: #EB3274;
		    border-radius: 23px;
		    padding: 2px 4px;
		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.quantity > div{
			border: 1px solid #DDE1E4;
			border-radius: 8px;
			width: 136px;
			height: 48px;
			display: flex;
			align-items: center;
			justify-content: center;
			margin-left: 0;
		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.quantity > div .input_number{
			border: 0;
			height: auto;
			font-weight: 500;
			font-size: 14px;
			line-height: 17px;
			color: #121415;
		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.remove{
			padding: 0;
			padding: 0;
			position: absolute;
			right: 20px;
			top: 20px;
		}
		.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.remove button{
			cursor: pointer;
			background: transparent;
		}
		#ajaxcheckout #quick_order_popap{
		    display: flex;
    		align-items: center;

		}
		#ajaxcheckout #quick_order_popap #quickfastorder-dialog-phone{
			max-width: 170px;
		}
		#ajaxcheckout #quick_order_popap #quick_order_popap_btn,
		.popup_cart_wrap #ajaxcheckout #gotoorder{
			background: #BFEA43;
			border-radius: 20px;
			padding: 10.5px 16px;
			font-weight: 500;
			font-size: 14px;
			line-height: 17px;
			color: #404345;
			height: auto;
		}
		#ajaxcartempty{
		    padding: 20px;
		    text-align: center;
		    font-weight: 500;
    		font-size: 16px;
		}
		#ajaxcheckout #quick_order_popap #quick_order_popap_btn{
			background: #FFF;
		    border: 1px solid #DDE1E4;
		    box-shadow: 0 8px 33px rgba(53,56,64,.2);
		}
		@media screen  and (max-width:560px){
			#popup-cart .cart-modal__tottal .ajaxtable_tr2 td{
				font-size: 16px;
			}
			#ajaxcheckout #quick_order_popap #quick_order_popap_btn{
				width: 100%;
			}
			#popup-cart .object{
				border-radius: 0;
				padding: 10px !important;
			}
			.notification_popup_cart div span {
			    font-size: 14px;
			}
			.notification_popup_cart div {
			    padding: 9px 20px;
			    gap: 5px;
			    border-radius: 4px;
			}
			#popup-cart .total-bg {
			    height: max-content;
			}
			.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td{
				padding: 0;
			}
			#ajaxtable_table tbody tr {
			  	padding: 10px;
			}
			.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.remove{
				width: 10px !important;
				position: absolute;
				right: 15px;
				top: 15px;
			}
			.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.image div {
			  width: 120px;
			  height: 120px;
			  margin-right: 10px;
			}
			.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody .quantity{
				position: absolute;
				left: 140px;
				bottom: 10px;
				background: #fff;
				width: auto !important;
			}
			#ajaxtable_table .image {
			  	width: 130px !important;
			}
			.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.names_product a {
  				-webkit-line-clamp: 2;
  				margin-bottom: 5px;
			}
			#ajaxtable_table .names_product{
			  padding-right: 30px !important;
			}
			.popup_cart_wrap .mini-cart-infos .ajaxtable_tbody tr:not(:first-child)::after {
			  display: none;
			}
			.popup_cart_wrap #ajaxtable_table .ajaxtable_tbody tr td.quantity > div {

			  width: 108px;
			  height: 36px;
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
				display: none;
			    overflow-x: hidden;
			}
		}
	</style>
	<div class="head-modal">
		<span class="title">Ваш кошик</span>
		<div class="overlay-popup-close">
			<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M13 0.999939L1 12.9999M1 0.999939L13 12.9999" stroke="#888F97" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</div>
	</div>
	<div id="ajaxcartmodal" style="padding: 0;">
		<?php if (!empty($products)) { ?>
			<div class="mini-cart-infos">
				<table id="ajaxtable_table" class="ajaxtable_table">
					<img src="/catalog/view/theme/dp/img/Spinners.png" id="ajaxcartloadimg"/>	
					<thead>
						<tr>
							<td colspan="3"></td>			
						</tr>
					</thead>
					<tbody class="ajaxtable_tbody">	
						<?php if ($this->config->get('config_divide_cart_by_stock')) { ?>
							<?php $reparsedProducts = reparseCartProductsByStock($products); ?>
							<?php if (!empty($reparsedProducts['in_stock'])) { $products = $reparsedProducts['in_stock']; ?>

							<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/popupcart_products.tpl')); ?>

							<? unset($product); } ?>

							<?php if (!empty($reparsedProducts['not_in_stock'])) { $products = $reparsedProducts['not_in_stock'];  ?>

							<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/popupcart_products.tpl')); ?>

							<? unset($product); } ?>

							<?php if (!empty($reparsedProducts['certificates'])) { $products = $reparsedProducts['certificates'];  ?>

							<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/popupcart_products.tpl')); ?>

							<? unset($product); } ?>
						<?php } else { ?>

							<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/popupcart_products.tpl')); ?>

						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="total-bg">
				<div id="ajaxcheckout" class="btn-group">

						
						<a href="<?php echo $checkout; ?>" onclick="document.location.href = '<?php echo $checkout; ?>'; " id="gotoorder" class="button btn">
							<?php echo $text_retranslate_19; ?>
						</a>

						<?php if (!$this->config->get('config_disable_fast_orders')) { ?>
						<div id="quick_order_popap">
							<input id="quickfastorder-dialog-phone" class="field" type="tel" value="<?=$telephone ?>" name="quickfastorder-phone" placeholder="<?php echo str_replace('9', '_', $mask) ?>">
							<input type="button" class=" btn disable-btn" id="quick_order_popap_btn" name="" value="<?php echo $text_retranslate_18; ?>" title="">
							<div class="error"></div>
						</div>
						<?php } ?>

						<table class="cart-modal__tottal">
							<?php foreach ($totals as $total) { ?>
								<tr id="ajaxtotal" class="ajaxtable_tr2">
									<td class="text"><b><?php echo $total['title']; ?></b> <b>:</b></td>
									<td class="total"><nobr><?php echo $total['text']; ?></nobr></td>
								</tr>
							<?php } ?>
						</table>
				</div>

				<?php if (!empty($ajaxcartproducts)) { ?>
					<div class="product_popup_list_wrap" >
						<div class="title_wrap">
							<span class="title">
								<?php echo $config_popupcartblocktitle; ?>
								<!-- <a href="<?php echo $href_view_all; ?>" title="<?php echo $config_popupcartblocktitle; ?>"> -->
									
								<a href="/c8307" title="<?php echo $config_popupcartblocktitle; ?>">
									<span>
										<?php echo $text_view_all; ?> 
									</span>
								</a>
							</span>
							
								<div class="nav-group">
									<div class="swiper-prev-slide swiper-button-disabled" tabindex="0" role="button" aria-label="Previous slide" aria-disabled="true"><svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="https://www.w3.org/2000/svg">
			                            <path d="M1.5 11L6.5 6L1.5 1" stroke="#3F3F49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
			                        </svg>
									</div>
									 <div class="swiper-next-slide" tabindex="0" role="button" aria-label="Next slide" aria-disabled="false">
									 	<svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="https://www.w3.org/2000/svg">
				                            <path d="M1.5 11L6.5 6L1.5 1" stroke="#3F3F49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				                        </svg>
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
														<div class="product__price_wrap">
															<div class="product__price-old"><?php echo $product['price']; ?></div>
															<div class="price__sale">-<?php echo $product['saving']; ?>%</div>	
														</div>
														<div class="product__price-new"><?php echo $product['special']; ?></div>
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
						    	swiperSales.reInt();
						    	swiperSales.update();
						    }, 500);
					</script>
					<?php } ?>

				</div>
			<?php } else { ?>
				<div class="mini-cart-infos cart-clear-text">
					<div id="ajaxcartempty"><?php echo $text_retranslate_20; ?></div>
					<div id="quick_order_popap"><div id="quickfastorder-dialog-phone"></div></div>
				</div>
			<?php } ?>
		</div>


		<script type="text/javascript">

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
					beforeSend : function(){
						$('#quick_order_popap_btn').addClass('disable-btn');
					},
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
			// $('#quickfastorder-dialog-phone').mask("<?php echo $mask; ?>");
			$('#quickfastorder-dialog-phone').inputmask("<?php echo $mask; ?>",{ "clearIncomplete": true });
		<? } ?>
		// Увеличения изображения
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







		// popup cart
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
				success:function(data) {

				}
			});
		}

		function plus(id){
			let quantity = parseInt($(id).parent().children('.qt').val());
			let minimum = parseInt($(id).parent().children('.qt').attr('data-minimum')) || 1;
			quantity = quantity + minimum;
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
			let quantity = parseInt($(id).parent().children('.qt').val());
			let minimum = parseInt($(id).parent().children('.qt').attr('data-minimum')) || 1;
			quantity = quantity - minimum;
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

		function delNew(id){
		
			var checkedData = [];
			
				var pid = $(id).parent().children('.product_id').val();
				var quantityTotal = parseInt($(id).parent().parent().parent().children('.quantity').children().children('.qt').val());
				var quantity = 0;
				pushEcommerceInfo(pid, quantityTotal, 'removeFromCart');
				pushVKRetargetingInfo(pid, 'remove_from_cart');
				checkedData.push(pid + '_qt_' + quantity);
				console.log(checkedData);
			
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

			console.log('delWishNew')
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
