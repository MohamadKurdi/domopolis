<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<style>
	#content-success .footer__social a {
	    position: relative;
	    display: block;
	    width: 40px;
	    height: 40px;
	    background-color: #51a881;
	    background-position: 50% 50%;
	    background-repeat: no-repeat;
	    transition: all .3s ease;
	}
	#content-success .footer__social a.facebook {
	    background-image: url(/catalog/view/theme/dp/img/facebook.svg);
	}
	#content-success .footer__social a.instagram {
	    background-image: url(/catalog/view/theme/dp/img/instagram.svg);
	}
</style>
<section id="content-success">
	<div class="wrap">
		<?php echo $content_top; ?>
		
		<div id="success_page">
			<div class="success_page_wrap">
				<div class="success_page_item">
					<h2><?php echo $text_retranslate_1; ?></h2>
					
					<div class="success_page_item_content"><span><?php echo $text_retranslate_2; ?> <?php echo $order_data['order_id']; ?> <?php echo $text_retranslate_3; ?> <?php echo $order_data['date_order']; ?></span>
						
						<p><b><?php echo $text_retranslate_5; ?> <a href="<?php echo $href_account; ?>" target="_blank"> <?php echo $text_retranslate_6; ?></a> <?php echo $text_retranslate_7; ?> <?php echo $order_data['store_telephone']; ?>, <?php echo $order_data['store_telephone2']; ?>.</b></p>
					</div>
				</div>
				
				<div class="success_page_item">
					<h2><?php echo $text_retranslate_8; ?></h2>
					
					<div class="success_page_item_content">
						<span><b><?php echo $text_retranslate_9; ?></b><br />
							<?php if (!empty($order_data['payment_method'])) { ?>
								<?php echo $order_data['payment_method']; ?></span><br />
							<?php } else { ?>
								<?php echo $text_retranslate_10; ?><br />
							<?php } ?>
							<span><b><?php echo $text_retranslate_11; ?></b><br />
								<?php if (!empty($order_data['shipping_method'])) { ?>
									<?php echo $order_data['shipping_method']; ?></span><br />
								<?php } else { ?>
									<?php echo $text_retranslate_10; ?><br />
									<?php } ?></span>
									<span><b><?php echo $text_retranslate_12; ?></b><br />
										<?php if (!empty($order_data['delivery_address'])) { ?>
											<?php echo $order_data['delivery_address']; ?></span><br />
										<?php } else { ?>
											<?php echo $text_retranslate_10; ?><br />
										<?php } ?>
									</span>
								</div>
							</div>
						</div>
						<div class="social_page_link">
							<p class="title"><?php echo $text_retranslate_13; ?></p>
							<ul class="footer__social">
								<?php if ($this->config->get('social_link_facebook')) { ?>
									<li>
										<a href="<?php echo $this->config->get('social_link_facebook'); ?>" target="_blank" class="facebook" rel="noindex nofollow"></a>
									</li>
								<?php } ?>
								<?php if ($this->config->get('social_link_instagram')) { ?>
									<li>
										<a href="<?php echo $this->config->get('social_link_instagram'); ?>" target="_blank" class="instagram" rel="noindex nofollow"></a>
									</li>
								<?php } ?>
							</ul>
						</div>
					</div>

					<div class="buttons">
						<a href="<?php echo $continue; ?>" class="btn btn-acaunt"><?php echo $button_continue; ?></a>
					</div>
					<?php echo $content_bottom; ?>

				</div>
			</section>
			<?php if ($actions_all ) { ?>
				<section class="action-page">
					<div class="wrap">
						<div id="actionsList">
							<div class="news-block">
								<?php foreach ($actions_all as $actions) { ?>
									<div class="actionListItem news-items itemscope=" itemtype="http://schema.org/Article">
										<?php if ($actions['thumb']) { ?>
											<div class="actionsImage news__photo">
												<a href="<?php echo $actions['href']; ?>">
													<img src="<?php echo $actions['thumb']; ?>" title="<?php echo $actions['caption']; ?>" alt="<?php echo $actions['caption']; ?>" itemprop="image" />
												</a>
												<?php if ($actions['discount']): ?>
													<div class="black-abs-b">
														<span><?php if($actions['discount'] == 'free') print $text_in_gift; else print $actions['discount'];?></span>
													</div>
												<?php endif; ?>
												</div><?php } ?>
												<div class="actionsHeader news__title">								
													<a href="<?php echo $actions['href']; ?>"><?php echo $actions['caption']; ?></a>
												</div>
												<div class="actionsDescription news__desc" itemprop="description"><?php echo $actions['description']; ?></div>

												<div class="actionsReadMore">
													<?php if ($actions['date']) { ?>
														<div class="news__date"><?php echo $actions['date']; ?></div><?php } ?>
													</div>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</section>
						<?php } ?>

						<style>
							.social_page_link{
								background: #eeeeeed4;
								padding: 20px;
								-moz-border-radius: 5px;
								-webkit-border-radius: 5px;
								-khtml-border-radius: 5px;
								border-radius: 5px;
								text-align: center;
								margin-top: 30px;
							}
							.social_page_link .title{
								text-align: center;
								margin-bottom: 20px;
								font-size: 20px;
								font-weight: 500;
								text-transform: uppercase;
							}
							.social_page_link ul{
								justify-content: center;
								margin-bottom: 15px;
								position: relative;
								z-index: 1;
							}
							.social_page_link ul li{

							}
							.social_page_link ul li a{

							}

						</style>

						<?php if ($config_google_merchant_id && $google_ecommerce_info && $google_ecommerce_info['display_survey']) { ?>
							<script src="https://apis.google.com/js/platform.js?onload=renderOptIn" async defer></script>

							<script>
								window.renderOptIn = function() {
									window.gapi.load('surveyoptin', function() {
										window.gapi.surveyoptin.render(
										{
											"merchant_id": <?php echo $config_google_merchant_id; ?>,
											"order_id": "<?php echo $google_ecommerce_info['transactionId']; ?>",
											"email": "<?php echo $google_ecommerce_info['transactionEmail']; ?>",
											"delivery_country": "<?php echo $google_ecommerce_info['transactionCountryCode']; ?>",
											"estimated_delivery_date": "<?php echo $google_ecommerce_info['transactionEstimatedDelivery']; ?>",

											<?php if ($google_ecommerce_info['transactionGTINS']) {  ?>
												"products": [<?php echo implode(',', $google_ecommerce_info['transactionGTINS']); ?>]
											<?php } ?>
										});
									});
								}
							</script>


						<?php } ?>

						<? if (isset($google_ecommerce_info)) { ?>
							<script type="text/javascript">


								<?php if ($this->config->get('config_vk_enable_pixel')) { ?>
									var VKRetargetFunction = function(){
										if((typeof VK !== 'undefined')){
											var vkproduct = [<?php $i = 0; $total_vk_price = 0; foreach ($google_ecommerce_info['transactionProducts'] as $product) { ?>                 
												{                   
													'id': '<?php echo prepareEcommString($product['id']); ?>',
													'price': '<?php echo $product['price'] ?>',
													'price_from': 0                  
												}<?php if ($i < (count($google_ecommerce_info['transactionProducts']) - 1)) {?>,<?php } ?>
												<?php $i++; $total_vk_price+=$product['price']; ?>
												<?php } ?>]; 

												console.log('VK trigger purchase');      
												VK.Retargeting.ProductEvent(<?php echo $this->config->get('config_vk_pricelist_id'); ?>, 'purchase', {
													'products' : vkproduct, 
													'currency_code': '<?php echo $this->config->get('config_regional_currency'); ?>', 
													'total_price': '<?php echo $total_vk_price; ?>'
												});  
											}
										}
									<?php } ?>


									$(document).ready(function(){

										<?php
										$content_ids_str = "";
										foreach ($google_ecommerce_info['transactionProducts'] as $product){
											$content_ids_str .= "'" . $product['id'] . "',";
										}
										$content_ids_str = rtrim($content_ids_str, ',');
										?>

										$(document).ready(function() {
											if ((typeof fbq !== 'undefined')){
												fbq('track', 'Purchase', {
													value: '<? echo $google_ecommerce_info['transactionTotal'] ?>', 
													currency: '<?php echo $google_ecommerce_info['transactionCurrency'] ?>',
													content_type: 'product',
													content_ids: [<?php echo $content_ids_str?>]
												});
											}
											window.dataLayer = window.dataLayer || [];

											dataLayer.push({
												'event': 'purchase',
												'value': '<? echo $google_ecommerce_info['transactionTotal'] ?>',
												'items':[
												<? $i=0; foreach ($google_ecommerce_info['transactionProducts'] as $transactionProduct) { ?>
													{
														'id': '<? echo $transactionProduct['id'].GOOGLE_ID_ENDFIX; ?>', 
														'google_business_vertical': 'retail'
													}<? if ($i < count($google_ecommerce_info['transactionProducts'])) { ?>,<?php } $i++; ?>
												<?php } ?>
												<?php unset($transactionProduct); ?>
												]
											});


											dataLayer.push({
												'event': 'orderPurchaseSuccess',
												'ecommerce': {
													'currencyCode': '<?php echo $this->config->get('config_regional_currency'); ?>',  
													'customer':{
														<? $i=0; foreach ($google_ecommerce_info['customerData'] as $customerDataKey => $customerDataValue) { ?>
															'<? echo $customerDataKey; ?>': '<? echo $customerDataValue; ?>'
															<? if ($i < count($google_ecommerce_info['customerData'])) { ?>,<?php } $i++; ?>
														<? } ?>
													},
													'purchase': {
														'id': '<? echo $google_ecommerce_info['transactionId'] ?>',                        
														'affiliation': '<? echo $google_ecommerce_info['transactionAffiliation'] ?>',
														'revenue': '<? echo $google_ecommerce_info['transactionTotal'] ?>',  
														'tax':'<? echo $google_ecommerce_info['transactionTax'] ?>',

														<?php if (!empty($google_ecommerce_info['transactionCoupon'])) { ?>
															'coupon':'<? echo $google_ecommerce_info['transactionCoupon'] ?>',
														<?php } ?>

														'shipping': '<? echo $google_ecommerce_info['transactionShipping'] ?>',
														'actionField': {
															'id': '<? echo $google_ecommerce_info['transactionId'] ?>',                        
															'affiliation': '<? echo $google_ecommerce_info['transactionAffiliation'] ?>',
															'revenue': '<? echo $google_ecommerce_info['transactionTotal'] ?>',  
															'tax':'<? echo $google_ecommerce_info['transactionTax'] ?>',

															<?php if (!empty($google_ecommerce_info['transactionCoupon'])) { ?>
																'coupon':'<? echo $google_ecommerce_info['transactionCoupon'] ?>',
															<?php } ?>

															'shipping': '<? echo $google_ecommerce_info['transactionShipping'] ?>'
														},
														'products': [
														<? $i=0; foreach ($google_ecommerce_info['transactionProducts'] as $transactionProduct) { ?>
															{
																'id' : '<? echo $transactionProduct['id'].GOOGLE_ID_ENDFIX; ?>',
																'sku': '<? echo $transactionProduct['sku'] ?>',
																'name' : '<? echo $transactionProduct['name'] ?>',
																'brand' : '<? echo $transactionProduct['manufacturer'] ?>',
																'category' : '<? echo $transactionProduct['category'] ?>',
																'price' : '<? echo $transactionProduct['price'] ?>',
																'quantity' : '<? echo $transactionProduct['quantity'] ?>',
																'total' : '<? echo $transactionProduct['total'] ?>'
															}	<? if ($i < count($google_ecommerce_info['transactionProducts'])) { ?>,<?php } $i++; ?>
														<? } ?>

														]					
													}	
												}
											});
										});

									});
								</script>
							<? } ?>
							<? if (isset($google_tag_params)) { ?> 
								<script type="text/javascript">
									var google_tag_params = {
										<? foreach ($google_tag_params as $name => $value) { ?>
											<? if ($name != 'dynx_pagetype' && $name != 'ecomm_pagetype' ) { ?>
												<? if ($name != 'dynx_totalvalue' && $name != 'ecomm_totalvalue') { ?>
													<? echo $name; ?>:['<? echo $value ?>'],
												<? } else { ?>
													<? echo $name; ?>:[<? echo $value ?>],
												<? } ?>
											<? } else { ?>
												<? echo $name; ?>:'<? echo $value ?>',
											<? } ?>
										<? } ?>
									};
								</script>
							<? } ?>
							<?php echo $footer; ?>				