<style type="text/css">
	@media screen and (min-width: 1000px){
		.popup_cart_wrap .mini-cart-infos{
			max-height: 400px;
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
	
</style>
<div class="object popup_cart_wrap">
	
    <div onclick="reloadAll();" class="overlay-popup-close"></div>
	
	<?php if (!empty($products)) { ?>
	    <div class="delete_block" style="position: relative;">
	   		<div class="checkbox">
	            <input type="checkbox" id="choose_all">
	            <label for="choose_all"><?php echo $text_retranslate_1; ?></label>
			</div>
	      	<button id="del_choose"><?php echo $text_retranslate_2; ?></button>
	      	<div class="popup-delete">
			    <div class="popup-delete__favorite">
			    	<input hidden class="product_id" value="<?=$product['key']; ?>" product-id="<?= $prod_id; ?>" style="display:none;"/>
			      	<a onclick="delWishNew();">
			        	<svg width="39" height="34" viewbox="0 0 39 34" fill="none" xmlns="http://www.w3.org/2000/svg">
			          		<path d="M34.3012 4.65222C33.446 3.81139 32.4306 3.14438 31.313 2.6893C30.1954 2.23423 28.9975 2 27.7878 2C26.5781 2 25.3802 2.23423 24.2626 2.6893C23.145 3.14438 22.1296 3.81139 21.2744 4.65222L19.4996 6.39642L17.7247 4.65222C15.9972 2.95459 13.6543 2.00088 11.2113 2.00088C8.76832 2.00088 6.42539 2.95459 4.69793 4.65222C2.97048 6.34984 2 8.65231 2 11.0531C2 13.4539 2.97048 15.7564 4.69793 17.454L6.47279 19.1982L19.4996 32L32.5263 19.1982L34.3012 17.454C35.1568 16.6136 35.8355 15.6157 36.2986 14.5174C36.7617 13.4191 37 12.2419 37 11.0531C37 9.86428 36.7617 8.68709 36.2986 7.5888C35.8355 6.49052 35.1568 5.49265 34.3012 4.65222V4.65222Z" stroke="#51A881" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
						</svg>
			       	 	<span><?php echo $text_retranslate_3; ?></span>
					</a>
				</div>
			    <div class="popup-delete__del">
			    	<input hidden class="product_id" value="<?=$product['key']; ?>" product-id="<?= $prod_id; ?>" style="display:none;"/>
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
					<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/popupcart_simple_products.tpl')); ?>
					
					<? unset($product); } ?>

					<?php if (!empty($reparsedProducts['not_in_stock'])) { $products = $reparsedProducts['not_in_stock'];  ?>
					
					<h4><?php echo $this->language->get('text_not_stock_' . $this->config->get('config_country_id')); ?></h4>
					<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/popupcart_simple_products.tpl')); ?>
					
					<? unset($product); } ?>

					<?php if (!empty($reparsedProducts['certificates'])) { $products = $reparsedProducts['certificates'];  ?>

					<h4><?php echo $this->language->get('text_present_certificates'); ?></h4>
					<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/popupcart_simple_products.tpl')); ?>

					<? unset($product); } ?>

				<?php } else { ?>
					<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/popupcart_simple_products.tpl')); ?>
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
					<button onclick="reloadAll();$('body').css('overflow','initial')" id="gotoorder" class="button btn "><?php echo $text_retranslate_21; ?>
						<svg width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="https://www.w3.org/2000/svg">
							<path d="M1 13L6 7L0.999999 1" stroke="white" stroke-width="2" stroke-linejoin="round"></path>
						</svg>
					</button>
				</div>
				
				<?php if (!empty($ajaxcartproducts)) { ?>
					<div style="clear:both;"></div>
					<div style="width:100%; text-align:center; padding-left: 20px; display: none;">
						<div style="background-color: #262626; color:#FFF; margin-bottom:5px; padding:3px;"><?php echo $this->config->get('config_popupcartblocktitle'); ?></div>
						<?php foreach ($ajaxcartproducts as $product) { ?>
							<div class="view-model" style="float:left; margin-left:10px; margin-right:10px; width:136px; border:1px solid #ddd;">
								<?php if ($product['thumb']) { ?>
									<a href="<?php echo $product['href']; ?>">
									<img  src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a>
								<?php } ?>
								
								<span class="inf-block"><a href="<?php echo $product['href']; ?>" class="longtext"><?php echo $product['name']; ?></a></span>
								
								<?php if ($product['price']) { ?>
									<?php if (!$product['special']) { ?>
										<span class="inf-price"><?php echo $product['price']; ?></span>
										<?php } else { ?>
		                                <span class="inf-price-old"><?php echo $product['price']; ?></span>
		                                <span class="inf-new-price"><?php echo $product['special']; ?></span>
									<?php } ?>
									
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<?php } else { ?>
		    <div class="mini-cart-infos cart-clear-text">
	        	<div id="ajaxcartempty"><?php echo $text_retranslate_20; ?></div>
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
		function startLoader(){
			loadImgPopup.show();
			contentPopup.css('opacity','0.5');
		}
		
		function endLoader(){
			loadImgPopup.hide();
			contentPopup.css('opacity','1');
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
					$('#header-small-cart').load('/index.php?route=module/cart #header-small-cart');
					$('#cart').load('index.php?route=module/cart #cart > *');
					if ((typeof simplecheckout_reload !== "undefined")){
						console.log('simplecheckout_reload');
						simplecheckout_reload('product_removed');
					}
				}
			});
		}
		
		<? if ($this->config->get('config_store_id') == 222) { ?>
			$('.btn-show-promo-code').on('click', function(){
				$(this).hide();
				$('.promo-code').css('visibility', 'visible');
				
			});
		<?php } ?>
		
		
		
		
		
		$('.zoom-in-popap').each(function(){
			$(this).click(function(){
				$(this).prev('.colorbox-popup').trigger('click');
			});
		});
		
		$('#choose_all').click(function(){
			if ($(this).is(':checked')){
				$('.choose_input:checkbox').prop('checked', true);
				} else {
				$('.choose_input:checkbox').prop('checked', false);
			}
		});
		
		$('.choose_input:checkbox').each(function(){
			$(this).click(function(){
				if ($(this).not(':checked')){
					$('#choose_all').prop('checked', false);
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
					if ((typeof simplecheckout_reload !== "undefined")){
						console.log('simplecheckout_reload');
						simplecheckout_reload('product_removed');
					};
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
					if ((typeof simplecheckout_reload !== "undefined")){
						console.log('simplecheckout_reload');
						simplecheckout_reload('product_removed');
					};
				}
			});
		}
		
		function delNew(){
			console.log('delNew fired');
			var checkedData = [];
			$('.checkbox .choose_input[type="checkbox"]:checked').each(function(){
				var pid = $(this).parent().children('.product_id').val();
				var quantityTotal = parseInt($(this).parent().parent().parent().children('.quantity').children().children('.qt').val());
				var quantity = 0;
				pushEcommerceInfo(pid, quantityTotal, 'removeFromCart');
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
						if ((typeof simplecheckout_reload !== "undefined")){
							console.log('simplecheckout_reload');
							simplecheckout_reload('product_removed');
						};
					}
				});
			},1000)
		}
		
		function delWishNew(){
			$('.choose_input:checked').each(function(){
				var pid = $(this).parent().children('.product_id').val();
				var wis = $(this).parent().children('.product_id').attr('product-id');
				quantity = 0;
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
					success: function(data) {
						if ((typeof simplecheckout_reload !== "undefined")){
							console.log('simplecheckout_reload');
							simplecheckout_reload('product_removed');
						};
					}
				});
			});
		}
		
		$('.close-modal-cart, .overlay-popup-close').on('click', function(){
			$('#main-overlay-popup').click();
		});
		
	</script>
	
</div>