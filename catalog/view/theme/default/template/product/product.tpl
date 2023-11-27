<?php echo $header; ?>
<style>
	.product-info__active_action .btn-additional-offer{
	    margin-top: 10px;
	    font-size: 13px;
	    display: flex;
	    align-items: center;
	    padding: 10px 15px;
	}
	.product-info__active_action .btn-additional-offer svg{
		margin-right: 10px;
	}
	.product-info__active_action__item{
		position: relative;
	    display: grid;
	    width: calc(100% - 195px);
	    grid-template-columns: 50px 1fr;
	    gap: 10px;
	    padding-left: 20px;
	}
	.product-info__active_action__item::before{
    content: '+';
    font-size: 18px;
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    margin: auto;
    height: 20px;
    color: #5eab71;
    font-weight: 600;

	}
	.product-info__active_action__item a{
		background: transparent;
		padding: 0;
	}
	.product-info__active_action__item .product__photo {
		margin-bottom: 0;
		 display: flex;
		 align-items: center;
		 justify-content: center;
	}
	.product-info__active_action__item .product__photo a{
	    display: flex;
    	margin: auto;
	}
	.product-info__active_action__item .product-info__active_action__info{
		display: flex;
		flex-direction: column;
	}
	.product-info__active_action__item .product-info__active_action__info .title{
		font-size: 14px;
		margin-bottom: 5px;
	    color: #5eab71;
	    line-height: 20px;
	    background: transparent !important;
	}
	.product-info__active_action__item .product-info__active_action__info .price__new{
		font-size: 12px;
		    color: #e16a5d;
		margin-bottom: 5px;
	}
	.product-info__active_action__item .product-info__active_action__info .price__old{
		font-size: 12px;
	}
	@media screen and (max-width: 1200px) {
		.product-info__active_action__item {
		    width: 100%;
		}
	}
	@media screen and (max-width: 560px){
	.pluses-item ul{
	flex-direction: column-reverse !important;
	}
	.price__head .reward_wrap .prompt{
	max-width: 175px;
	}
	.price__btn-group #main-add-to-cart-button span {
	font-size: 15px !important;
	}
	#video video{
		max-width: 100%;
		max-height: 250px;
	}
	}
</style>
<?php if (!empty($this->session->data['gac:listfrom'])) {
	$gacListFrom = $this->session->data['gac:listfrom'];
unset($this->session->data['gac:listfrom']); } 
?>

<!-- new tab -->
<script type="text/javascript">
	window.product_id = <?php echo $product_id; ?>
	
	$(document).ready(function(){
		if ($(window).width() >= '1000'){
			setTimeout(function(){
				CloudZoom.quickStart();
			},1500);		
		}          
	});
	
	function rebuildMaskInput(){
		$('#waitlist-phone').inputmask("<?php echo $mask; ?>",{ "clearIncomplete": true });
	}
</script>   

<?php echo $content_top; ?>

<?php if ($hb_snippets_prod_enable == '1'){ ?>
	<?php
		$desc = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", htmlentities(strip_tags($description))); 
		$desc = preg_replace('/\s{2,}/', ' ', trim($desc));
		$desc = str_replace('\\', '-', trim($desc));
	?>
	<script type="application/ld+json">
		{
			"@context": "http://schema.org/",
			"@type": "Product",
			"name": "<?php echo prepareEcommString($heading_title); ?>",
			"productID": "mpn:<?php echo trim($mpn)?trim(prepareEcommString($mpn)):trim(prepareEcommString($model)); ?>",
			"sku": "<?php echo trim($sku)?trim(prepareEcommString($sku)):trim(prepareEcommString($model)); ?>",
			"mpn": "<?php echo trim($mpn)?trim(prepareEcommString($mpn)):trim(prepareEcommString($model)); ?>"
			<?php if ($thumb) { ?>
				,"image": "<?php echo $thumb; ?>"
			<?php } ?>
			<?php if ($desc) { ?>
				,"description": "<?php echo $desc; ?>"
			<?php } ?>
			<?php if ($manufacturer) { ?>
				,"brand": {
					"@type": "Brand",
					"name": "<?php echo $manufacturer; ?>"
				}
			<?php } ?>
			<?php if (($review_status) and ($rating)) { ?>
				,"aggregateRating": {
					"@type": "AggregateRating",
					"ratingValue": "<?php echo $rating; ?>",
					"reviewCount": "<?php echo $review_count; ?>"
				}
			<?php } ?>
			<?php if ($price)  {  ?>
				,"offers":{"@type": "Offer",
					"url": "<?php echo $this_href; ?>", 
					"priceCurrency": "<?php echo $currencycode; ?>",
					"priceValidUntil": "<?php echo date('Y-m-d', strtotime('+1 year')); ?>",
					<?php if (!$special) {
						if ($language_decimal_point == ','){
							$hbprice = str_replace('.','',$price);
							$hbprice = str_replace(',','.',$hbprice);
							$hbprice = preg_replace("/[^0-9.]/", "", $hbprice);
							$hbprice = ltrim($hbprice,'.');
							$hbprice = rtrim($hbprice,'.');
							}else{
							$hbprice = $price;
							$hbprice = preg_replace("/[^0-9.]/", "", $hbprice);
							$hbprice = ltrim($hbprice,'.');
							$hbprice = rtrim($hbprice,'.');
						}
					?>
					"price": "<?php echo $hbprice; ?>"
					<?php } else {
						if ($language_decimal_point == ','){
							$hbspecial = str_replace('.','',$special);
							$hbspecial = str_replace(',','.',$hbspecial);
							$hbspecial =  preg_replace("/[^0-9.]/", "", $hbspecial);
							$hbspecial = ltrim($hbspecial,'.');
							$hbspecial = rtrim($hbspecial,'.');
							}else{
							$hbspecial = $special;
							$hbspecial =  preg_replace("/[^0-9.]/", "", $hbspecial);
							$hbspecial = ltrim($hbspecial,'.');
							$hbspecial = rtrim($hbspecial,'.');
						}
					?>
					"price": "<?php echo $hbspecial; ?>"				
					<?php } ?>					
					<?php if (!$can_not_buy) { ?>
						,"availability": "http://schema.org/InStock"
					<?php } ?>
				}
			<?php } ?>
		}
	</script>
<?php } ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<!--article-->
<section id="product-detail" class="article product-detail">
	<div class="wrap">
		<!--product-info-->
		<div class="product-info">
			<div class="product-info__left-col">
				<h1 class="title"><?php echo $heading_title; ?></h1>
				<div class="product-info__left-col__wrap">
					<div class="product-info__reviews">
						<?php if ($review_status) { ?>
							<span class="rate rate-<?php echo $rating; ?>"></span>
						<?php } ?>
						<a class="reviews_btn"><?php echo $review_count; ?> 
							<?php if ($review_count == 0) { ?>
								<?php echo $text_retranslate_1; ?>
								<?php } elseif ($review_count == 1) { ?>
								<?php echo $text_retranslate_2; ?>
								<?php } elseif ($review_count == 2) { ?>
								<?php echo $text_retranslate_3; ?>
								<?php } elseif ($review_count == 3) { ?>
								<?php echo $text_retranslate_3; ?>
								<?php } elseif ($review_count == 4) { ?>
								<?php echo $text_retranslate_3; ?>
								<?php } elseif ($review_count > 5) { ?>
								<?php echo $text_retranslate_1; ?>
							<?php } ?>							
						</a>
					</div>
					<div class="product-info__code">
						<?php if ($manufacturer) { ?>
							<span class="code">
								<?= $text_code ?>: <?php echo $product_id; ?>
							</span>
						<?php } ?>
						
						<?php if (ADMIN_SESSION_DETECTED || !$this->config->get('config_product_hide_sku')) { ?>
							<span class="model">
								<?php echo $text_model; ?>: <?php echo $model; ?>
							</span>
						<?php } ?>
						
						<?php if (!empty($category_manufacturer_info)) { ?>
							<span class="category"><?php echo $text_retranslate_4; ?> <a href="<?php echo $category_manufacturer_info['href']; ?>" title="<?php echo $category_manufacturer_info['text']; ?>" style="white-space: normal;"><?php echo $category_manufacturer_info['text']; ?></a></span>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="product-info__right-col">
				<?php if ($manufacturer) { ?>
					<div class="product-info__logo-brand">
						<div class="product-info__logo-brand">
								<a href="<?php echo $manufacturers; ?>" title="<?php echo $manufacturer; ?>">						
									<img src="<? echo $manufacturers_img_260; ?>" alt="<?php echo $manufacturer; ?>" loading="lazy">
								</a>
						</div>
					</div>
				<?php } ?>
				
			</div>
		</div>
		<!--/product-info-->
		<!--tabs-->
		<main class="product__body description">
			<div class="tabs item-page">
				<!--tabs__nav-->
				<div class="tabs__nav js-dragscroll-wrap2">
					<ul class="tabs__caption js-dragscroll2">
						<li id="main_btn" class="active"><?php echo $text_retranslate_5; ?></li>
						<?php if (isset($collection) && $collection) { ?>
							<li id="collection_btn"><?php echo $text_retranslate_6; ?> <? echo $collection_name; ?></li>
						<?php } ?>
						<li id="characteristic_btn"><?php echo $text_retranslate_7; ?></li>
						<li id="photo_btn"><?php echo $text_retranslate_8; ?> <?php if ($youtubes || $videos) { ?> <?php echo $text_retranslate_9; ?> <?php } ?></li>
						<!-- <li>Сопутствующие товары</li> -->
						<li id="reviews_btn"><?php echo $text_retranslate_10; ?> (<?php echo $review_count; ?>)</li>
					</ul>
				</div>
				<!--/tabs__nav-->
				
				<!--tabs__content-->
				<div class="tabs__content main_btn active" id="product-tab-info" style="position: relative;">
					
					<!--slider-box-->
					<div class="slider-box clrfix" style="position: relative;">
						<!--gallery-thumbs-->
						<?php if (!$images) { ?>
							<div class="gallery-thumbs" style="display: none !important;">
								<div class="swiper-container">
									<!--swiper-wrapper-->
									<div class="swiper-wrapper"></div>
								</div>
							</div>
						<?php } ?>
						<?php if ($thumb || $images) { ?>
							<?php $i=1; if ($images) { ?>			
								
								<?php 
									if($youtubes == true) {
										$videoInt = 1;
										} else{
										$videoInt = 0;
									}								
								?>
								
								<div class="gallery-thumbs">
									<?php $i = 1;
										if ($images) { ?>
										<div class="swiper-container thumbImages_<?php echo count($images) + $videoInt; ?>">
											
											
											<!--swiper-wrapper-->
											<div class="swiper-wrapper">
												<!--swiper-slide-->
												<?php if ($thumb) { ?>
													<div class="swiper-slide 1">
														<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" loading="lazy"/>
													</div>
												<?php } ?>
												<?php foreach ($images as $image) { ?>
													<?php if (isset($image['thumb'])) { ?>
														<div class="swiper-slide 2">
															<img src="<?php echo $image['middle']; ?>" alt="<?php echo $heading_title; ?>" loading="lazy" />
														</div>
													<?php } ?>
													<?php 
														$i++;
													} ?>
													
													<?php if ($youtubes) { ?>
														<div class="swiper-slide 3 youtubes_slider_thumb" style="display: flex;"></div>
														<script>
															$(document).ready(function(){
																<? foreach ($youtubes as $youtube) { ?>
																	$('.youtubes_slider_thumb').append('<i class="fab fa-youtube"></i><img class="video_prev" src="//img.youtube.com/vi/<? echo $youtube; ?>/default.jpg" alt="video-<? echo $youtube; ?>" width="100%" height="20px">');
																<? } ?>
															});
														</script>
													<? } ?>
													
													<!--/swiper-slide-->
											</div>
											<!--/swiper-wrapper-->
											
										</div>
									<?php } ?>
									<!-- Add Arrows -->
									<div class="swiper-button-next"></div>
									<div class="swiper-button-prev"></div>
								</div>
							<?php } ?>
						<?php } ?>
						<!--/gallery-thumbs-->
						
						<!--gallery-top-->
						<div class="gallery-top" <?php if (!$images) { ?> style="margin-left: 0" <?php } ?>>
							<?php if ($has_labels) { ?>
								<div class="product__label">
									<?php if (isset($has_labels['bestseller'])) { ?>
										<div class="product__label-hit"><?php echo $text_retranslate_11; ?></div>
									<?php } ?>
									<?php if (isset($has_labels['bestprice'])) { ?>
										<div class="product__label-best-price"><?php echo $text_retranslate_12; ?></div>
									<?php } ?>
									<?php if (isset($has_labels['bestprice'])) { ?>
										<div class="product__label-new"><?php echo $text_retranslate_13; ?></div>
									<?php } ?>
									<?php if (isset($has_labels['special'])) { ?>
										<div class="product__label-stock"><?php echo $text_retranslate_14; ?></div>
									<?php } ?>
								</div>
							<?php } ?>
							
							<div class="swiper-container topImages_<?php echo count($images) + $videoInt; ?>">
								
								
								
								<!--swiper-wrapper-->
								<div class="swiper-wrapper">
									<!--swiper-slide-->
									<?php if ($thumb || $images) { ?>
										
										<div class="swiper-slide">										
											<img class="cloudzoom"
											src="<?php echo $popup; ?>" 
											alt="<?php echo $heading_title; ?>"	
											data-cloudzoom = "
											zoomImage: '<?php echo $popup_ohuevshiy; ?>',
											zoomSizeMode: 'image',
											autoInside:750,
											zoomPosition:'#product-cart'
											"
											width="600"
											height="600"
											>
											
										</div>
										
										<?php $i = 1;
											if ($images) { ?>
											<?php foreach ($images as $image) { ?>
												<?php if (isset($image['thumb'])) { ?>
													<div class="swiper-slide">													
														<img class="cloudzoom"
														src="<?php echo $image['popup']; ?>"
														alt="<?php echo $heading_title; ?>"
														data-cloudzoom = "
														zoomImage: '<?php echo $image['popup_ohuevshiy']; ?>',
														zoomSizeMode: 'image',
														autoInside:750,
														zoomPosition:'#product-cart'
														"
														>
														
													</div>
												<?php } ?>
												<?php $i++;
												} ?>
										<?php } ?>
										<?php if ($youtubes) { ?>
											<?php $cVideo = count($youtubes); ?>
											<?php echo $cVideo; ?>										
											<div class="swiper-slide 3 youtubes_slider1"></div>
											<script>
												$(document).ready(function(){
													let _hGalery = $('.gallery-top').innerHeight();
													<? foreach ($youtubes as $youtube) { ?>
														<? if ($youtube === reset($youtubes)){ ?>
															$('.youtubes_slider1').append('<iframe class="video-gallery-top" src="https://www.youtube.com/embed/<? echo $youtube; ?>" width="100%" height="'+_hGalery+'" frameborder="0" allowfullscreen"></iframe>');
														<? }?>
													<? } ?>
													
												});
												
											</script>
										<? } ?>
									<?php } ?>
									<!--/swiper-slide-->
								</div>
								<!--/swiper-wrapper-->
								<div class="swiper-pagination"></div>
							</div>
						</div>
						<!--/gallery-top-->
					</div>
					<!--/slider-box-->
					
					<!--item-column-->
					<div class="item-column " id="product-cart" style="position: relative; ">
						
						<input type="hidden" name="product_id" size="4" value="<?php echo $product_id; ?>">
						<?php if ($is_set) { ?>
							<input type="hidden" name="set_id" size="4" value="<?php echo $is_set; ?>">
						<?php } ?>

						<?php if (!empty($text_retranslate_reward_review)) { ?>
									<div class="product-info__active_action">					
										<h3><?php echo $text_retranslate_reward_review; ?></h3>										
									</div>
						<?php } ?>
						
						<?php if (!empty($active_action)) { ?>
							<div class="product-info__active_action">					
								<h3>Акция! <?php echo $active_action['caption']; ?></h3>
								<a href="<?php echo $active_action['href']; ?>"><?php echo $text_retranslate_15; ?></a>

								<?php if ($additional_offers) { ?>

									<?php
										
										$price = 0;
										$priceNew = 0;
										
										$this->load->model('localisation/currency');
										$productCurr = $this->model_localisation_currency->getCurrencyByCode($this->session->data['currency']);
										
									?>
									<?php $ao_has_zero_price = false;
										foreach ($additional_offers as $additional_offer) { ?>

									<div class="product-info__active_action__item">
										<div class="product__photo">
											<a class="img-block" href="<? echo $additional_offer['ao_href']; ?>">
												<img src="<? echo $additional_offer['ao_image']; ?>" alt="" loading="lazy">
											</a>
										</div>
										<div class="product-info__active_action__info">
											<a href="<? echo $additional_offer['ao_href']; ?>" class="title longtext">
												<?php if ($additional_offer['ao_quantity'] > 1): ?>
												<?= $additional_offer['ao_quantity'] ?> X
												<?php endif; ?>
												<? echo $additional_offer['ao_product_name']; ?>
											</a>
											<div class="product__price">
												<?php // $this->log->debug($additional_offer); ?>
												<div class="price__new"><? echo $additional_offer['ao_price']; ?></div>
												<div class="price__old"><? echo $additional_offer['ao_real_price']; ?></div>							
											</div>
										</div>
									</div>

									<input type='checkbox' <? /* if ($ao_has_zero_price) { ?>checked="checked"<? } */ ?>  style="display:none" class="check-offer-item" name='additional_offer[<? echo $additional_offer['additional_offer_id']; ?>]' id='additional_offer_<? echo $additional_offer['additional_offer_id']; ?>' />
									<button type="button" value="" class="btn btn-success btn-buy btn-additional-offer" placeholder="" data-ao-id="<? echo $additional_offer['additional_offer_id']; ?>"> <svg width="18" height="18" viewbox="0 0 26 25" fill="none"
											xmlns="http://www.w3.org/2000/svg">
												<path d="M1 1.33936H5.19858L8.01163 15.6922C8.10762 16.1857 8.37051 16.629 8.7543 16.9445C9.13809 17.26 9.61832 17.4276 10.1109 17.418H20.3135C20.8061 17.4276 21.2863 17.26 21.6701 16.9445C22.0539 16.629 22.3168 16.1857 22.4128 15.6922L24.0922 6.6989H6.24823M10.4468 22.7775C10.4468 23.3695 9.97687 23.8495 9.39716 23.8495C8.81746 23.8495 8.34752 23.3695 8.34752 22.7775C8.34752 22.1855 8.81746 21.7056 9.39716 21.7056C9.97687 21.7056 10.4468 22.1855 10.4468 22.7775ZM21.9929 22.7775C21.9929 23.3695 21.523 23.8495 20.9433 23.8495C20.3636 23.8495 19.8936 23.3695 19.8936 22.7775C19.8936 22.1855 20.3636 21.7056 20.9433 21.7056C21.523 21.7056 21.9929 22.1855 21.9929 22.7775Z"
												stroke="white" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round"></path>
											</svg>
											 <? echo $text_by_complect; ?>		
										</button>
									<? } ?>
								<? } ?>
							</div>
						<?php } ?>
						
						<div class="product-info__delivery">					
							
							<?php if ($show_delivery_terms) { ?>
								<div class="delivery_terms <? if (empty($bought_for_week)) { ?>position_pluses-item<? } ?>">	
									<?php echo $stock_text; ?>
								</div>								
							<? } ?>

							
							<? if ($is_markdown) { ?>	
								<div id="markdown-reason-btn" class="markdown-reason do-popup-element" data-target="markdown-reason">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500.84 553" width="30" height="30">
										<defs><style>.cls-3-1{fill:#ffc34f}.cls-4-1{fill:#ffc34f}</style></defs><g id="Слой_2" data-name="Слой 2"><g id="Слой_1-2" data-name="Слой 1"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1-2">
											<path d="M250.42 553a16.69 16.69 0 0 1-9.71-3.11L7 382.93a16.7 16.7 0 0 1 9.71-30.28h50.05V16.7A16.7 16.7 0 0 1 83.46 0h333.91a16.7 16.7 0 0 1 16.7 16.7v336h50.08a16.7 16.7 0 0 1 9.71 30.3L260.12 550a16.68 16.68 0 0 1-9.7 3z" class="cls-5" fill="#51a881"/><path d="M484.15 352.65h-50.08V16.7A16.7 16.7 0 0 0 417.37 0h-167v553a16.68 16.68 0 0 0 9.7-3.11l233.74-167a16.7 16.7 0 0 0-9.71-30.28z" style="isolation:isolate" opacity=".1"/><path class="cls-3-1" d="M183.63 248.42a50.09 50.09 0 1 1 50.09-50.09 50.14 50.14 0 0 1-50.09 50.09zm0-66.78a16.7 16.7 0 1 0 16.7 16.7 16.71 16.71 0 0 0-16.7-16.7z"/><path class="cls-4-1" d="M317.2 382a50.09 50.09 0 1 1 50.09-50.09A50.14 50.14 0 0 1 317.2 382zm0-66.78a16.7 16.7 0 1 0 16.69 16.71 16.72 16.72 0 0 0-16.69-16.73z"/><path class="cls-3-1" d="M179.58 352.65a16.7 16.7 0 0 1-11.81-28.5l141.67-141.67a16.69 16.69 0 1 1 23.61 23.61L191.38 347.76a16.64 16.64 0 0 1-11.8 4.89z"/><path class="cls-4-1" d="M309.44 182.48l-59 59v47.22l82.63-82.64a16.69 16.69 0 1 0-23.61-23.61z"/>
										</svg>
										<span><?php echo $text_retranslate_27; ?></span>
										</div>
										<?php } ?>
										
										<?php if ($free_delivery) { ?>
											<?php if ($free_delivery == 'moscow') { ?>
												<div class="delivery_info_free ruMoskow">
													<img src="/catalog/view/theme/kp/img/banner-logo.jpg" alt="" loading="lazy">
													<p><?php echo $text_retranslate_66; ?></p>										
												</div>
												<? } elseif($free_delivery == 'kyiv') { ?>
												<div class="delivery_info_free uaKyiv">
													<img src="/catalog/view/theme/kp/img/banner-logo.jpg" alt="" loading="lazy">
													<p><?php echo $text_retranslate_65; ?></p>
													
												</div>
											<? } ?>
										<?php } ?>
										
										</div>
										
										<!--price-->
										<span class="ajax-module-reloadable" data-modpath="product/product/getPriceInfo" data-x="<?php echo $product_id; ?>" data-afterload="rebuildMaskInput">						
											<?php include($this->checkTemplate(dirname(__FILE__),'/../blocks/price.tpl')); ?>
										</span>
										
										<script>
											
											var defaultCity;
											
											function openCityPopupInProduct(){
												$.ajax({
													url: "index.php?route=kp/checkout/getCitiesListAjax",
													dataType: "html",
													beforeSend: function(){
														$('#change-city-product-city-name').html('<i class="fas fa-spinner fa-spin"></i>');
													},
													success: function(html){
														$('#change-city-product-city-name-selection-popup').html(html);
													},
													complete: function(){
														$('#change-city-product-city-name-selection-popup').show();
														$('#change-city-product-city-name-selection-popup').addClass('open');
														closetPopupDelivery();
													}														
												});			
											}
											
											function initProductChangeCityPopupTrigger(){
												console.log('init initProductChangeCityPopupTrigger');
												$("#change-city-product-city-name").on('click', function () {
													openCityPopupInProduct();
												});	
												$('#change-city-product-city-name-selection-popup').hide();
												$('#change-city-product-city-name-selection-popup').removeClass('open');
												defaultCity = $('#change-city-product-city-name').text();
												console.log(defaultCity);
											}
											
											function closetPopupDelivery(){
												
												$(".popup-city__close, .popup-city__btn-yes").on('click', function () {
													$('#change-city-product-city-name-selection-popup').hide();
													$('#change-city-product-city-name-selection-popup').removeClass('open');
													$('#change-city-product-city-name').html(defaultCity);
													return false;
												});
												$('#product-cart .delivery  .popup-city__city-list-city-item').each(function(e) {
													$(this).on('click', function () {
														$('#change-city-product-city-name-selection-popup').hide();
														$('#change-city-product-city-name-selection-popup').removeClass('open');
														
														return false;
													});
												});
												
											}	
											
										</script>
										
										<?php if ($show_delivery_terms) { ?>
										<div class="delivery <?php if ($need_ask_about_stock || $can_not_buy) { ?>can_not_buy<?php } ?>">
											<div id="product_delivery_info_reloadable" class="delivery__info ajax-module-reloadable" data-modpath="product/product/getDeliveryInfo" data-x="<?php echo $product_id; ?>" data-afterload="initProductChangeCityPopupTrigger">
												<i class="fas fa-spinner fa-spin"></i> <?php echo $delivery_to_city_temp_text; ?>
											</div>
											<div id="change-city-product-city-name-selection-popup"></div>											
										</div>
										<?php } ?>
										
										<!--/price-->
										
										<?php if ($is_markdown && !empty($markdown_product)) { ?>
											
											<div class="markdown_product_blcok">
												<span class="name"><?php echo $text_retranslate_36; ?></span>
												<div class="markdown_product_item">
													<div class="name_markdown_product">
														<a href="<?php echo $markdown_product['href']; ?>" title="<?php echo $markdown_product['name']; ?>" ><?php echo $markdown_product['name']; ?></a>
														<?php if (!empty($markdown_product['special'])) { ?>
															<div class="price__new"><?php echo $markdown_product['special']; ?></div>
															<div class="price__old"><?php echo $markdown_product['price']; ?></div>
															<?php } else { ?>
															<div class="price__new"><?php echo $markdown_product['special']; ?></div>
														<?php } ?>
													</div>
												</div>
											</div>
										<?php } ?>
										
										<?php if (!empty($markdowned_products)) { ?>
											<div class="markdown_product_blcok">
												<div class="markdown_product_block_info">
													<?php if (count($markdowned_products) == 1) { ?>
														<span class="name"><?php echo $text_retranslate_37; ?> <i id="btn-info-show" class="fas fa-info-circle"></i></span>
														<?php } else { ?>
														<span class="name"><?php echo $text_retranslate_38; ?></span>
														
													<?php } ?>
													<?php foreach ($markdowned_products as $markdown_product) { ?>
														<div class="name_markdown_product_mardown_reasons">
															<div><b><?php echo $text_retranslate_39; ?></b> <?php echo $markdown_product['markdown_appearance']; ?></div>
															<div><b><?php echo $text_retranslate_40; ?></b> <?php echo $markdown_product['markdown_condition']; ?></div>
															<div><b><?php echo $text_retranslate_41; ?></b> <?php echo $markdown_product['markdown_pack']; ?></div>
															<div><b><?php echo $text_retranslate_42; ?></b> <?php echo $markdown_product['markdown_equipment']; ?></div>
														</div>
													<?php } ?>
												</div>
												<?php foreach ($markdowned_products as $markdown_product) { ?>
													
													<div class="markdown_product_item">
														<div class="name_markdown_product">
															<a href="<?php echo $markdown_product['href']; ?>" title="<?php echo $markdown_product['name']; ?>" ><?php echo $markdown_product['name']; ?></a>
															<?php if (!empty($markdown_product['special'])) { ?>
																<div class="price__new"><?php echo $markdown_product['special']; ?></div>
																<div class="price__old"><?php echo $markdown_product['price']; ?></div>
																<?php } else { ?>
																<div class="price__new"><?php echo $markdown_product['special']; ?></div>
															<?php } ?>
														</div>
														
													</div>
													
												<?php } ?>
												
											</div>
										<?php } ?>
										<?php if (!empty($active_coupon)) { ?>
											<div class="active-coupon">
												<span class="title-coupon"><?php echo $text_retranslate_43; ?></span>
												<div class="active-coupon__promocode">
													<span id="promo-code-txt" onclick="copytext(this)" title="<?php echo $text_retranslate_44; ?>"><?php echo $active_coupon['code']; ?></span><button class="btn-copy" onclick="copytext('#promo-code-txt')" title="<?php echo $text_retranslate_44; ?>"><span class="tooltiptext" style="display: none;"><?php echo $text_retranslate_45; ?></span></button>
												</div>
												<div class="active-coupon__price">
													<?php echo $active_coupon['coupon_price']; ?>
												</div>
												<div class="active-coupon__datend" style="opacity: 0;display: none;">
													<?php echo $text_retranslate_46; ?> <b><div id="note"></div></b>
													
												</div>
											</div>
										<?php } ?>
										
										<!--pluses-item-->
										<div class="pluses-item">
											<ul <? if (empty($bought_for_week)) { ?>style="display: flex;flex-direction: row-reverse;"<? } ?>>
												<? if ($bought_for_week) { ?>
													<li>
														<img src="/catalog/view/theme/kp/img/pluses-icon3.svg" alt="" loading="lazy">
														<p><? echo $bought_for_week ?></p>
													</li>
												<? } ?>
												<li>
													<a href="javascript:void(0)" onclick="do_notification_block(31,'delivery_block');return false;" data-target="info_delivery" class="do-popup-element" >
														<img src="/catalog/view/theme/kp/img/pluses-icon-dev.svg" alt="" loading="lazy">
														<p><?php echo $text_retranslate_47; ?> <i class="fas fa-info-circle"></i></p>
													</a>
												</li>
											</ul>
										</div>
										<!--/pluses-item-->
										
										<!--delivery-->
										<!-- <div class="delivery">
											<div class="delivery__info">
										<span>Доставка в:</span> -->
										<!--change-city-->
										<!-- <div class="change-city"> -->
										<!-- <div class="change-city__btn">
											<svg width="20" height="24" viewbox="0 0 20 24" fill="none"
											xmlns="http://www.w3.org/2000/svg">
											<path d="M19 10C19 17 10 23 10 23C10 23 1 17 1 10C1 7.61305 1.94821 5.32387 3.63604 3.63604C5.32387 1.94821 7.61305 1 10 1C12.3869 1 14.6761 1.94821 16.364 3.63604C18.0518 5.32387 19 7.61305 19 10Z"
											stroke="#51A881" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round"></path>
											<path d="M10 13C11.6569 13 13 11.6569 13 10C13 8.34315 11.6569 7 10 7C8.34315 7 7 8.34315 7 10C7 11.6569 8.34315 13 10 13Z"
											stroke="#51A881" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round"></path>
											</svg>
											<span>Запорожье</span>
										</div> -->
										<!--popup-city-->
										<!-- <div class="popup-city" rel="choose-city">
											<div class="popup-city__title">Выберите город</div>
											<ul class="popup-city__city-list">
											<li><a class="active" href="#">Киев</a></li>
											<li><a href="#">Днепр</a></li>
											<li><a href="#">Харьков</a></li>
											<li><a href="#">Львов</a></li>
											<li><a href="#">Одесса</a></li>
											<li><a href="#">Запорожье</a></li>
											</ul>
											<div class="popup-city__search">
											<svg width="16" height="16" viewbox="0 0 16 16" fill="none"
											xmlns="http://www.w3.org/2000/svg">
											<path d="M1 15L4.38333 11.6167M2.55556 7.22222C2.55556 10.6587 5.34134 13.4444 8.77778 13.4444C12.2142 13.4444 15 10.6587 15 7.22222C15 3.78578 12.2142 1 8.77778 1C5.34134 1 2.55556 3.78578 2.55556 7.22222Z"
											stroke="#FFC34F" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round"></path>
											</svg>
											<input type="text" name="search-city" autocomplete="off" placeholder="Поиск">
											</div>
											<div class="popup-city__close"></div>
										</div> -->
										<!--/popup-city-->
										<!-- </div> -->
										<!--/change-city-->
										
										<!-- </div>
											<ul class="delivery__list">
											
											</ul>
										</div> -->
										
										<!--/delivery-->
									</div>
									<!--/item-column-->
									
									<!--item-bottom-->
									<div class="item-bottom">
										
										<!--char-->
										<div class="char">
											<h4 class="title"><?php echo $text_retranslate_48; ?></h4>
											<ul class="char-list">
												<?php if ($manufacturer) { ?>
													<li>
														<h2>
															<span><?=$text_brand ?></span> 
															<? if ($show_manufacturer || true) { ?>
																<span><a href="<?php echo $manufacturers; ?>" title="<?php echo $manufacturer; ?>"><?php echo $manufacturer; ?></a></span>
																<? } else { ?>	
																<span><?php echo $manufacturer; ?></span>
															<? }?>
														</h2>
													</li>
												<?php } ?>
												
												<?php if (isset($location) && mb_strlen(trim($location), 'UTF-8') > 0) { ?>
													<li>
														<h2>
															<span><?=$text_country ?></span> <span><?php echo $location; ?></span>
														</h2>
													</li>
												<?php } ?>
												
												<?php if (isset($collection_name)) { ?>
													<li>
														<h2>
															<span><?=$text_collection ?></span> 
															<span><a href="<?php echo $collection_link; ?>" title="<?php echo $collection_name; ?>"><?php echo $collection_name; ?></a></span>
														</h2>
													</li>
												<?php } ?>
												
												<?php if ($ean) { ?>
													<li>
														<h2>
															<span>EAN</span> <span><?php echo $ean; ?></span>
														</h2>
													</li>
												<? } ?>
												
												<?php if ($reward) { ?>
													<li>
														<h2>
															<span><?php echo $text_reward; ?></span> <span><?php echo $reward; ?></span>
														</h2>
													</li>
												<? } ?>

												<?php if ($attribute_groups) { ?>
													<?php foreach ($attribute_groups as $attribute_group) { ?>												
														<?php foreach ($attribute_group['attribute'] as $attribute) { ?>												
															<li>
																<h2>
																	<span><?php echo $attribute['name']; ?></span> <span><?php echo $attribute['text']; ?></span>
																</h2>
															</li>
														<?php } ?>
													<?php } ?>
												<?php } ?>

												<?php if (!empty($weight)) { ?>
													<li>
														<h2>
															<span><?php echo $text_weight; ?></span> <span><?php echo $weight; ?></span>
														</h2>
													</li>
												<?php } ?>

												<?php if (!empty($product_dimensions)) { ?>
													<li>
														<h2>
															<span><?php echo $text_dimensions; ?></span> <span><?php echo $product_dimensions; ?></span>
														</h2>
													</li>
												<?php } ?>

												<!--Custom product information-->
												<?php if (($this->config->get('status_product') == '1') && (isset($this->document->cusom_p)) ){ ?>
													<li>
														<h2>
															<span><?php echo htmlspecialchars_decode( $this->document->cusom_p[$this->config->get('config_language_id')]['product_text'], ENT_QUOTES ); ?></span>
														</h2>
													</li>
												<?php } ?>
												
												<?php if (ADMIN_SESSION_DETECTED || !$this->config->get('config_product_hide_sku')) { ?>
													<li class="char-list-model">
														<h2><span><?php echo $text_model; ?></span> <span><?php echo $model; ?></span></h2>
													</li>
												<?php } ?>
												
												<?php if (!empty($category_manufacturer_info)) { ?>
													<li class="char-list-category">
													<h2><span><?php echo $text_retranslate_4; ?></span> <span><a href="<?php echo $category_manufacturer_info['href']; ?>" title="<?php echo $category_manufacturer_info['text']; ?>" style="white-space: normal;"><?php echo $category_manufacturer_info['text']; ?></a></span></h2></li>
												<?php } ?>
											</ul>	
											<!--end Custom product information-->
										</ul>	
									</div>
									<!--/char-->
									
									<div class="right-col">
										
										<!--payment-->
										<?php if ($payment_list) { ?>	
											<div class="payment">
												<h4 class="title"><?php echo $text_retranslate_49; ?></h4>
												<ul>
													<?php foreach ($payment_list as $__payment) { ?>
														<li><?php echo trim($__payment); ?></li>								
													<?php } ?>
												</ul>
											</div>
										<? } ?>
										<!--/payment-->
										
										<!--guarantee-->
										<div class="guarantee">
											<h4 class="title"><?php echo $text_retranslate_50; ?></h4>
											<ul>
												<li><?php echo $text_retranslate_51; ?></li>
												<li><?php echo $text_retranslate_52; ?></li>
												<li><?php echo $text_retranslate_53; ?></li>
											</ul>
										</div>
										<!--/guarantee-->
									</div>
									
								</div>
								<!--/item-bottom-->			
								
								
								<? if (!empty($product_product_options)){ ?>
									<!--option-->
									<div class="kit option-products">
										<h3 class="title center"><?php echo $text_retranslate_55; ?></h3>
										<!--kit__box-->
										<div class="kit__box" id="option-box">
											<div class="swiper-container-option" style="overflow: hidden;">
												<div class="swiper-wrapper">
													<!--kit__list-->				
													<?php foreach ($product_product_options as $product_product_option) { ?>
														<?php if ($product_product_option['type'] == 'checkbox') { ?>
															<?php foreach ($product_product_option['product_option'] as $product_option_value) { ?>
																<div class="swiper-slide">
																	<div class="kit__list">
																		<!--kit__item-->
																		<div class="kit__item">
																			<!--kit__photo-->
																			<div class="kit__photo">
																				<!--product__photo-->
																				<div class="product__photo">
																					<img src="<?php echo $thumb; ?>" alt="" loading="lazy">
																				</div>
																				<!--/product__photo-->
																			</div>
																			<!--/kit__photo-->
																			<!--kit__info-->
																			<div class="kit__info">
																				<div class="product__rating">
																					<span class="rate rate-<?php echo $rating; ?>"></span>
																				</div>
																				<div class="product__title">
																					<?php echo $heading_title; ?>
																				</div>
																				<div class="product__price">
																					<div class="price__new"><?php echo $additional_offer['product_real_price']; ?></div>
																				</div>
																			</div>
																			<!--/kit__info-->
																		</div>
																		<!--/kit__item-->
																		
																		<!--kit__item-->
																		
																		<div id="product-option-<?php echo $product_product_option['product_product_option_id']; ?>" class="kit__item option options-special">
																			
																			
																			
																			
																			
																			<!--kit__photo-->
																			<div class="kit__photo">
																				<!--product__photo-->
																				<div class="product__photo">
																					<a class="img-block" href="<? echo $product_option_value['href']; ?>">
																						<img class="no-border" src="<?php echo $product_option_value['image']; ?>" alt="<?php echo $product_option_value['name']; ?>"  loading="lazy"/>
																					</a>
																				</div>
																				<!--/product__photo-->
																			</div>
																			<!--/kit__photo-->
																			<!--kit__info-->
																			<div class="kit__info">
																				<div class="product__rating">
																					<span class="rate rate-<?php echo $product_option_value['rating']; ?>"></span>
																				</div>
																				<div class="product__title">
																					<a href="<?=$product_option_value['href'] ?>" class="longtext">
																						<?php echo $product_option_value['name']; ?>
																					</a>
																				</div>
																				<div class="product__price">											
																					<div class="price__new">
																						<?php if ($product_option_value['special']) { ?>
																							+<?php echo $product_option_value['special']; ?>
																							<?php } else { ?>
																							+<?php echo $product_option_value['price']; ?>
																						<?php } ?>																			
																					</div>
																				</div>
																			</div>
																			<!--/kit__info-->
																			
																			
																			
																			
																		</div>
																		
																		<!--/kit__item-->
																		
																	</div>
																	<!--/kit__list-->
																	<!--kit__total-price-->
																	<div class="kit__total-price">
																		<div class="kit__btn">
																			<input type='checkbox' style="display:none" class="check-offer-item" name="product-option[<?php echo $product_product_option['product_product_option_id']; ?>][]"  value="<?php echo $product_option_value['product_option_id']; ?>" id="product-option-value-<?php echo $product_option_value['product_option_id']; ?>" />
																			<button type="button" value="" class="btn btn-success btn-buy btn-option-offer" placeholder="" data-ao-id="<?php echo  $product_product_option['product_product_option_id']; ?>"> <svg width="26" height="25" viewbox="0 0 26 25" fill="none"
																				xmlns="http://www.w3.org/2000/svg">
																					<path d="M1 1.33936H5.19858L8.01163 15.6922C8.10762 16.1857 8.37051 16.629 8.7543 16.9445C9.13809 17.26 9.61832 17.4276 10.1109 17.418H20.3135C20.8061 17.4276 21.2863 17.26 21.6701 16.9445C22.0539 16.629 22.3168 16.1857 22.4128 15.6922L24.0922 6.6989H6.24823M10.4468 22.7775C10.4468 23.3695 9.97687 23.8495 9.39716 23.8495C8.81746 23.8495 8.34752 23.3695 8.34752 22.7775C8.34752 22.1855 8.81746 21.7056 9.39716 21.7056C9.97687 21.7056 10.4468 22.1855 10.4468 22.7775ZM21.9929 22.7775C21.9929 23.3695 21.523 23.8495 20.9433 23.8495C20.3636 23.8495 19.8936 23.3695 19.8936 22.7775C19.8936 22.1855 20.3636 21.7056 20.9433 21.7056C21.523 21.7056 21.9929 22.1855 21.9929 22.7775Z"
																					stroke="white" stroke-width="2" stroke-linecap="round"
																					stroke-linejoin="round"></path>
																				</svg>
																				<?=$text_buy ?>										
																			</button>
																		</div>
																	</div>
																</div>
															<?php } ?>
														<?php } ?>
													<? } ?>
													<!--/kit__total-price-->
													
												</div>
												<!-- arrows -->
												<div class="swiper-button-prev"></div>
												<div class="swiper-button-next"></div>
												<!-- /arrows -->
											</div>
											<!--/kit__box-->
											<div class="swiper-pagination" style="left: 0; right: 0;"></div>
										</div>
									</div>				
									<script>
										$(document).ready(function () {
											var mySwiper = new Swiper('.swiper-container-option', {
												slidesPerView: 'auto',
												// loop: true,
												centeredSlides: false,
												pagination: {
													el: '#option-box .swiper-pagination',
													clickable: true,
												},
												autoHeight: true,
												simulateTouch: false,
												watchOverflow: true,
												navigation: {
													nextEl: '.swiper-button-next',
													prevEl: '.swiper-button-prev',
												},
											});
										});
									</script>
								<?php } ?>
								<!--/option-->
								
								
								
								
								<!--about-section-->
								<div class="about-section">
									<!--about-->
									<?php if ($attribute_groups_special || ($description && strlen(trim($description)) > 32)) { ?>
										<div class="about">
											<?php if ($description && strlen(trim($description)) > 32) { ?>
												<h4 class="title"><?php echo $text_retranslate_56; ?> <span><?php echo $heading_title; ?></span></h4>
												<div class="desc">
													
													<?php if ($attribute_groups_special) { ?>
														<div class="attribute_groups_special">
															<ul>
																<?php foreach ($attribute_groups_special as $attribute_group) { ?>
																	<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
																		<li>
																			<span><?php echo $attribute['text']; ?></span>
																		</li>
																	<? } ?>
																<?php } ?>												
															</ul>
														</div>		
													<?php } ?>
													
													<?php echo $description; ?>
												</div>
											<?php } ?>							
										</div>
										<div>							
										</div>
									<? } ?>
									<!--/about-->
									
									<!--reviews-col-->
									<div id="reviews-col" class="reviews-col">
										<div class="rev_content">
											<?php if ($review_status) { ?>  
												
												<?php echo $onereview; ?>
												
											<?php } ?>
										</div>
									</div>
									<!--/reviews-col-->
								</div>
								<!--/about-section-->
								
								
								
							</div>
							<!--/tabs__content-->
							
							<!--tabs__content-->
							<?php if (isset($collection) && $collection) { ?>
								<div id="collection-tab" class="tabs__content collection_btn">
									
									<a href="<?php echo $collection_link; ?>" class="btn btn-acaunt"><?=$text_all_collection ?> <? echo isset($manufacturer)?$manufacturer.'&nbsp;':''; ?><? echo $collection_name; ?></a>
									
									<div class="catalog__content product-grid list__colection">
										<div class="product__grid"  id="product__grid">
											<?php foreach ($collection as $product) { ?>								
												<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single.tpl')); ?>
											<?php } ?>
											
										</div>				
									</div>
									
								</div>
							<? } ?>
							<!--/tabs__content-->
							
							<!--tabs__content-->
							<div class="tabs__content characteristic_btn">
								<ul class="char-list">
									<?php if ($manufacturer) { ?>
										<li>
											<span><?=$text_brand ?></span> 
											<? if ($show_manufacturer || true) { ?>
												<span><a href="<?php echo $manufacturers; ?>" title="<?php echo $manufacturer; ?>"><?php echo $manufacturer; ?></a></span>
												<? } else { ?>	
												<span><?php echo $manufacturer; ?></span>
											<? }?>
										</li>
									<?php } ?>
									
									<?php if (isset($location) && mb_strlen(trim($location), 'UTF-8') > 0) { ?>
										<li>
											<span><?=$text_country ?></span> <span><?php echo $location; ?></span>
										</li>
									<?php } ?>
									
									<?php if (isset($collection_name)) { ?>
										<li>
											<span><?=$text_collection ?></span> 
											<span><a href="<?php echo $collection_link; ?>" title="<?php echo $collection_name; ?>"><?php echo $collection_name; ?></a></span>
										</li>
									<?php } ?>
									
									
									<?php if ($ean) { ?>
										<li>
											<span>EAN</span> <span><?php echo $ean; ?></span>
										</li>
									<? } ?>
									
									<?php if ($reward) { ?>
										<li>
											<span><?php echo $text_reward; ?></span> <span><?php echo $reward; ?></span>
										</li>
									<? } ?>
									<!-- <li>
										<span><?php echo $text_stock; ?></span> <span><?php echo $stock; ?></span>
									</li> -->
									<?php if ($attribute_groups) { ?>
										<?php foreach ($attribute_groups as $attribute_group) { ?>												
											<?php foreach ($attribute_group['attribute'] as $attribute) { ?>												
												<li>
													<span><?php echo $attribute['name']; ?></span> <span><?php echo $attribute['text']; ?></span>
												</li>
											<?php } ?>
										<?php } ?>
									<?php } ?>

									<?php if (!empty($weight)) { ?>
										<li>											
											<span><?php echo $text_weight; ?></span> <span><?php echo $weight; ?></span>											
										</li>
									<?php } ?>

									<?php if (!empty($product_dimensions)) { ?>
										<li>
											<span><?php echo $text_dimensions; ?></span> <span><?php echo $product_dimensions; ?></span>
										</li>
									<?php } ?>

									<!--Custom product information-->
									<?php if (($this->config->get('status_product') == '1') && (isset($this->document->cusom_p)) ){ ?>
										<li>
											<span><?php echo htmlspecialchars_decode( $this->document->cusom_p[$this->config->get('config_language_id')]['product_text'], ENT_QUOTES ); ?></span>
										</li>
									<?php } ?>
									
									<?php if (ADMIN_SESSION_DETECTED || !$this->config->get('config_product_hide_sku')) { ?>
										<li class="char-list-model">
											<span><?php echo $text_model; ?></span> <span><?php echo $model; ?></span>
										</li>
									<?php } ?>
									
									<?php if (!empty($category_manufacturer_info)) { ?>
										<li class="char-list-category">
										<span><?php echo $text_retranslate_4; ?></span> <span><a href="<?php echo $category_manufacturer_info['href']; ?>" title="<?php echo $category_manufacturer_info['text']; ?>" style="white-space: normal;"><?php echo $category_manufacturer_info['text']; ?></a></span></li>
									<?php } ?>
								</ul>	
							</div>
							<!--/tabs__content-->
							
							<!--tabs__content-->
							<div class="tabs__content photo_btn">
								<?php if ($youtubes) { ?>
									<div id="video-content"  style="background-color: #f9f9f9; text-align:center;">
										<div id="youtubes_youtubes">
										</div>
										<script>
											$(document).ready(function(){
												<? foreach ($youtubes as $youtube) { ?>
													$('#youtubes_youtubes').append('<div class="item"><iframe src="https://www.youtube.com/embed/<? echo $youtube; ?>" frameborder="0" allowfullscreen width="100%" height="350px"></iframe></div>');
													<? } ?>
												});
												
											</script>
									</div>
								<? } ?>
								<?php if ($videos) { ?>
									<div id="video"  style="background-color: #f9f9f9; text-align:center;">
										<?php foreach ($videos as $video){ ?>
											<script type="application/ld+json">
												{
													"@context": 	"https://schema.org/",
													"@type": 		"VideoObject",
													"name": 		"<?php echo $video['title']; ?>",
												<?php if ($desc) { ?>
													"description": "<?php echo $desc; ?>",
												<?php } else { ?>	
													"description": 	"<?php echo $video['title']; ?>",
												<?php } ?>
													"thumbnailUrl": "<?php echo $video['thumb'] ?>",
													"contentUrl": "<?php echo $video['video'] ?>",
													"uploadDate":	"<?php echo $video['date_added'] ?>"
												}
											</script>
											
											<div>
												<video controlsList="nodownload" width="700" height="400" title="<?php echo $video['title']; ?>" poster="<?php echo $video['thumb'] ?>" controls>
												    <source src="<?php echo  $video['video']; ?>" type="video/mp4">>
												</video>
											</div>
										<? } ?>
									</div>
								<? } ?>
								<?php if ($image) { ?>
									<div id="tab-image" class="tab-image" style="background-color: #f9f9f9; text-align:center;">
										<div>
											<img src="<?php echo $popup; ?>" alt="<? echo $heading_title; ?>" class="img-responsive" style="padding:3px; border:1px solid rgba(0,0,0,0.08); margin-top:10px;"  loading="lazy"/>
										</div>
										<?php unset($image); foreach ($images as $image) { ?>
											<div>
												<img src="<?php echo $image['popup']; ?>" alt="<? echo $heading_title; ?>" class="img-responsive"  style="padding:3px; border:1px solid rgba(0,0,0,0.08); margin-top:10px;" loading="lazy" />
											</div>
										<?php } ?>
									</div>
								<? } ?>
							</div>
							<!--/tabs__content-->
							
							<!--tabs__content-->
							<!-- <div class="tabs__content">
								Тут будет контент <b>Сопутствующие товары</b>
							</div> -->
							<!--/tabs__content-->
							
							
							<!--tabs__content-->
							<div class="tabs__content reviews_btn">
								<div class="reviews-col tab_reviews">
									<div id="review"></div>
								</div>
							</div>
							<!--/tabs__content-->
							
						</div>
						<div class="sticky-block base-padding">
							<div class="sticky-block__product">
								
								<div class="sticky-block__image">
									<?php if ($thumb) { ?>
										<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" width="100px" loading="lazy"/>
									<?php } ?>
									<div class="sticky-block__name"><?php echo $heading_title; ?></div>
								</div> 
								
								<div class="price_product">
									
									<?php  if ($need_ask_about_stock) { ?>			  
										<p style="font-size: 13px;line-height: 18px;text-align: justify;color: #333;"><?php echo $text_retranslate_28; ?> </p>
										<?php  } elseif ($can_not_buy) { ?>
										<span style="color:#CCC; font-size:18px; font-weight:700;"><? echo $stock; ?></span>
										<?php  } elseif ($original_data['special']) { ?>
										<div class="price__old_wrap">
											<div class="price__old"><?php echo $original_data['price']; ?></div>
											<span class="price__saving">-<?php echo $saving; ?>%</span>
										</div>
										<div class="price__new"><?php echo $original_data['special']; ?></div>
										
										<?php } else { ?>
										<div class="price__new"><?php echo $original_data['price']; ?></div>
									<?php } ?>		
								</div>
								
								
								<?php  if ($need_ask_about_stock) { ?>
									<div class="waitlist-block" style="display: block;width: 100%">
										<form id="waitlist-form_sticky-block">
											<div class="row">
												<div class="phone_block">
													<span><?php echo $text_retranslate_30; ?></span>
													<input class="waitlist__phone" type="text" name="waitlist-phone" id="waitlist-phone_sticky-block" placeholder="<?php echo $mask; ?>" value="<? if ($customer_telephone) { ?><? echo $customer_telephone; ?><? } ?>" />
													<input type="hidden" name="customer_id" value="<?php echo (int)$customer_id; ?>" />
													<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
												</div>		
												<div class="error"  id="error_phone_sticky-block"></div>
												<div class=" error" id="waitlist-success_sticky-block"></div>
											</div>
											<input type="button" class="btn btn-success btn-default" value="<?php echo $text_retranslate_31; ?>" id="waitlist-send_sticky-block" />
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
														<span><?php echo $text_retranslate_30; ?></span>
														<input class="waitlist__phone" type="text" name="waitlist-phone" id="waitlist-phone_sticky-block" data-telephone="<?php echo str_replace('9', '_', $mask) ?>" placeholder="<?php echo str_replace('9', '_', $mask) ?>" value="<? if ($customer_telephone) { ?><? echo $customer_telephone; ?><? } ?>" />
														<input type="hidden" name="customer_id" value="<?php echo (int)$customer_id; ?>" />
														<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
													</div>		
													<div class="error"  id="error_phone_sticky-block"></div>
													<div class="error"  id="waitlist-success_sticky-block"></div>
												</div>
												<div class="row">
													<div class="text-center">
														<input type="button" class="btn btn-success btn-default" value="<?php echo $text_retranslate_32; ?>" id="waitlist-send_sticky-block" />
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
													</svg><?php echo $text_retranslate_33; ?>
												</button>
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
								<?php } ?>	
								
								
								
								<?php if (!empty($active_action)) { ?>
									<div class="product-info__active_action">					
										<h3>Акция! <?php echo $active_action['caption']; ?></h3>
										<a href="<?php echo $active_action['href']; ?>"><?php echo $text_retranslate_15; ?></a>
									</div>
								<?php } ?>
								
								
								<div class="product-info__delivery">					
									
									<?php if ($show_delivery_terms) { ?>
										<div class="delivery_terms <? if (empty($bought_for_week)) { ?>position_pluses-item<? } ?>">					
											
											<?php echo $stock_text; ?>
										</div>
									<? } ?>
									
									<? if ($is_markdown) { ?>	
										<div id="markdown-reason-btn" class="markdown-reason do-popup-element" data-target="markdown-reason">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500.84 553" width="30" height="30"><defs><style>.cls-3-1{fill:#ffc34f}.cls-4-1{fill:#ffc34f}</style></defs><g id="Слой_2" data-name="Слой 2"><g id="Слой_1-2" data-name="Слой 1"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1-2"><path d="M250.42 553a16.69 16.69 0 0 1-9.71-3.11L7 382.93a16.7 16.7 0 0 1 9.71-30.28h50.05V16.7A16.7 16.7 0 0 1 83.46 0h333.91a16.7 16.7 0 0 1 16.7 16.7v336h50.08a16.7 16.7 0 0 1 9.71 30.3L260.12 550a16.68 16.68 0 0 1-9.7 3z" class="cls-5-1" fill="#51a881"/><path d="M484.15 352.65h-50.08V16.7A16.7 16.7 0 0 0 417.37 0h-167v553a16.68 16.68 0 0 0 9.7-3.11l233.74-167a16.7 16.7 0 0 0-9.71-30.28z" style="isolation:isolate" opacity=".1"/><path class="cls-3-1" d="M183.63 248.42a50.09 50.09 0 1 1 50.09-50.09 50.14 50.14 0 0 1-50.09 50.09zm0-66.78a16.7 16.7 0 1 0 16.7 16.7 16.71 16.71 0 0 0-16.7-16.7z"/><path class="cls-4-1" d="M317.2 382a50.09 50.09 0 1 1 50.09-50.09A50.14 50.14 0 0 1 317.2 382zm0-66.78a16.7 16.7 0 1 0 16.69 16.71 16.72 16.72 0 0 0-16.69-16.73z"/><path class="cls-3-1" d="M179.58 352.65a16.7 16.7 0 0 1-11.81-28.5l141.67-141.67a16.69 16.69 0 1 1 23.61 23.61L191.38 347.76a16.64 16.64 0 0 1-11.8 4.89z"/><path class="cls-4-1" d="M309.44 182.48l-59 59v47.22l82.63-82.64a16.69 16.69 0 1 0-23.61-23.61z"/></g></g></g></g></svg>
											<span><?php echo $text_retranslate_27; ?></span>
										</div>
									<?php } ?>
									
									<?php if ($free_delivery) { ?>
										<?php if ($free_delivery == 'moscow') { ?>
											<div class="delivery_info_free ruMoskow">
												<img src="/catalog/view/theme/kp/img/banner-logo.jpg" alt="" loading="lazy">
												<p><?php echo $text_retranslate_66; ?></p>
											</div>
											<? } elseif($free_delivery == 'kyiv') { ?>
											<div class="delivery_info_free uaKyiv">
												<img src="/catalog/view/theme/kp/img/banner-logo.jpg" alt="" loading="lazy">
												<p><?php echo $text_retranslate_65; ?></p>
											</div>
										<? } ?>
									<?php } ?>
									
								</div>
								
								
								<?php if (!empty($active_coupon)) { ?>
									<div class="active-coupon">
										<span class="title-coupon"><?php echo $text_retranslate_43; ?></span>
										<div class="active-coupon__promocode">
											<span id="promo-code-txt" onclick="copytext(this)" title="<?php echo $text_retranslate_44; ?>"><?php echo $active_coupon['code']; ?></span><button class="btn-copy" onclick="copytext('#promo-code-txt')" title="<?php echo $text_retranslate_44; ?>"><span class="tooltiptext" style="display: none;"><?php echo $text_retranslate_45; ?></span></button>
										</div>
										<div class="active-coupon__price">
											<?php echo $active_coupon['coupon_price']; ?>
										</div>
										<div class="active-coupon__datend" style="opacity: 0;display: none;">
											<?php echo $text_retranslate_46; ?> <b><div id="note"></div></b>
											
										</div>
									</div>
								<?php } ?>
								<!--pluses-item-->
								<div class="pluses-item">
									<ul>
										<? if ($bought_for_week) { ?>
											<li>
												<img src="/catalog/view/theme/kp/img/pluses-icon3.svg" alt="" loading="lazy">
												<p><? echo $bought_for_week ?></p>
											</li>
										<? } ?>
										<li>
											<a href="javascript:void(0)" onclick="do_notification_block(31,'delivery_block');return false;" data-target="info_delivery" class="do-popup-element" >
												<img src="/catalog/view/theme/kp/img/pluses-icon-dev.svg" alt="" loading="lazy">
												<p><?php echo $text_retranslate_47; ?> <i class="fas fa-info-circle"></i></p>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</main> 		
					<!--/tabs-->
				</div>
				
				<div class="wrap">
					<?php if ($this->config->get('site_position') == '1') { ?>
						<?php echo $content_bottom; ?>
					<?php } ?>
				</div>
				
				
				
			</section>
			<!--/article-->
			
			
			<?php if (isset($show_button) && $show_button): ?>
			
			<?php endif; ?>
			
			<?php echo $column_left; ?><?php /* echo $column_right; */ ?>
			
			<!-- popup_form_revievs -->
			<div class="overlay_popup"></div>
			<div class="popup-form" id="formRev">
				<div class="object">
					<div class="overlay-popup-close"><i class="fas fa-times"></i></div>
					<h3><?php echo $text_retranslate_57; ?></h3>		
					<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/review_form.tpl')); ?>
				</div>
			</div>
			<!-- popup_form_revievs -->
			<div id="quick_popup_container"></div>
			<!-- popup_form_revievs -->
			<div class="popup-form" id="info_popup">
				<?php include($this->checkTemplate(dirname(__FILE__),'/../common/popup_info.tpl')); ?>
			</div>
			
			<!-- popup_form_revievs -->
			<div class="popup-form" id="info_delivery">
				<div class="object">
					<div class="overlay-popup-close"><i class="fas fa-times"></i></div>
					<h3><?php echo $text_retranslate_47; ?></h3>
					<div class="info-order-container">
						<div class="content" style="background: #fff;padding: 20px 30px 20px;">	
							<?php if ($delivery_info) { ?>
								<?php  echo $delivery_info; ?>
								<?php  echo $displaydelivery; ?>
							<?php } ?>
						</div>	
					</div>
				</div>
			</div>
			
			<?php if ($is_markdown) { ?>
				<!-- markdown-reason -->
				<div class="popup-form" id="markdown-reason">
					<div class="object">
						<div class="overlay-popup-close"><i class="fas fa-times"></i></div>
						<h3><?php echo $text_retranslate_58; ?></h3>
						<div class="info-order-container">
							<?php include($this->checkTemplate(dirname(__FILE__),'/popup/markdown-reason.tpl')); ?>
						</div>
					</div>
				</div>
				<!-- /markdown-reason -->
			<?php } ?>
			
			<!-- popup_form_revievs -->
			<div class="popup-form" id="found_cheaper">
				<div class="object">
					<div class="overlay-popup-close"><i class="fas fa-times"></i></div>
					<h3><?php echo $text_retranslate_59; ?></h3>
					<div class="info-order-container">
						<div class="content">	
							<span style="text-align: center;display: block;font-size: 18px;margin-bottom: 10px;"><?php echo $text_retranslate_60; ?></span>
							<form  method="post" id="found_cheaper-form">	                    
								<div class="form-group">
									<label><?php echo $text_retranslate_61; ?></label>
									<input id="found_cheaper-phone" class="found_cheaper" type="text" value="<? if ($customer_telephone) { ?><? echo $customer_telephone; ?><? } ?>" placeholder="<?php echo str_replace('9', '_', $mask) ?>" name="found_cheaper-phone">
									<input type="hidden" name="customer_id" value="<?php echo (int)$customer_id; ?>" />
									<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
									<div class="error"  id="error_phone_found_cheaper" style="color: #e16a5d;display: block;font-weight: 600;"></div>											
								</div>    
								<div class="form-group">
									<label><?php echo $text_retranslate_62; ?></label>
									<input type="email" id="found_cheaper-email" name="found_cheaper-email" class="form-control"/>
									<input type="hidden" value="1" name="is_cheaper" class="form-control"/>
								</div>                
								<div class="form-group">
									<label><?php echo $text_retranslate_63; ?></label>
									<input type="text" id="found_cheaper-link" name="found_cheaper-link" class="form-control" />
									<div class="error"  id="error_link_found_cheaper" style="color: #e16a5d;display: block;font-weight: 600;"></div>	
								</div>
								<div class="form-group-btn">
									<a id="found_cheaper-send" class="btn btn-acaunt" ><?php echo $text_retranslate_64; ?></a>
									<span id="found_cheaper-success" style="color: #51a881;display: block;font-weight: 600;"></span>
								</div>
							</form>
						</div>	
					</div>
				</div>
			</div>
			
			<div id="tab_header" style="display: none;">
				
				<?php if ($thumb) { ?>
					<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" width="36" loading="lazy"/>
				<?php } ?>
				
				<!--tabs__nav-->
				<div class="tab_header">
					<span class="name_prod"><?php echo $heading_title; ?></span>
				</div>
				<!--/tabs__nav-->
				<div class="price_product">
					<?php  if ($need_ask_about_stock) { ?>	
						
						<?php } else if ($can_not_buy) { ?>
						
						<?php } else if (!$special) { ?>
						<div class="price__new"><?php echo $original_data['price']; ?></div>
						<?php } else { ?>
						<div class="price__old_wrap">
							<div class="price__old"><?php echo $original_data['price']; ?></div>
							<span class="price__saving">-<?php echo $saving; ?>%</span>
						</div>
						<div class="price__new"><?php echo $original_data['special']; ?></div>
					<?php } ?>		
				</div>
				
				<div class="addTo-cart-qty">
					
					<?php if ($additional_offers) { ?> 
						<?php $ao_has_zero_price = false;
							foreach ($additional_offers as $additional_offer) { ?>
							<input type='checkbox' checked="checked" style="display:none" class="check-offer-item" name='additional_offer[<? echo $additional_offer['additional_offer_id']; ?>]' id='additional_offer_<? echo $additional_offer['additional_offer_id']; ?>'/>
							<? } 
						} ?>
						<?php  if ($need_ask_about_stock) { ?>		
							<span style="color:#ffc34f; font-size:12px; font-weight:600;"><? echo $text_retranslate_25; ?></span>
							<?php } else if ($can_not_buy) { ?>
							<span style="color:#CCC; font-size:14px; font-weight:600;"><? echo $stock; ?></span>
							<?php } else { ?>
							<button 
							id="addTo-cart-button" 
							class="price__btn-buy btn <?php if ($additional_offers) { ?> btn-additional-offer <? } ?>" 
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
									</svg><?php echo $text_buy;?>
								</button>
						<?php } ?>
				</div>
			</div>
			
			<script type="text/javascript">
				// $(document).ready(function(){
				//   	var text = window.location.href;// Берем ссылку
				//   	var regex = /#(\w+)/gi;
				//   	match = regex.exec(text);// Находим в ней все, что находится после знака #
				//   	if(match)// Если нашел
				//   	{
				//   		setTimeout(function(){
				//   			$('.tabs__caption li, .tabs__content').removeClass('active');// Удаляем все активные табы
				// 	    	$('.tabs__caption li#'+match[1]).addClass('active');// Добавляем класс 'in active' к ид у которого название с ссылки совпадает с ид у таба
				// 	    	$('.tabs__content.'+match[1]).addClass('active');	
				//   		},1500)
				
				//   	}
				// });
				
				
				var loadImgPopup = $('#ajaxcartloadimg');
				var contentPopup = $('#found_cheaper-form');
				
				$('#found_cheaper-send').on('click', function() {
					$.ajax({
						url: '/index.php?route=module/callback/found_cheaper',
						type: 'post',
						data: $('#found_cheaper-form').serialize(),
						dataType: 'json',
						beforeSend: function() {
							$('#found_cheaper-send').bind('click', false);
							loadImgPopup.show();
							contentPopup.css('opacity','0.5');
						},
						complete: function() {
							$('#found_cheaper-send').unbind('click', false);
							
						},
						success: function(json) {
							$('#error_phone_found_cheaper').empty().hide();
							$('#error_link_found_cheaper').empty().hide();
							
							if (json['error']) {				
								if (json['error']['phone']) {
									$('#error_phone_found_cheaper').html(json['error']['phone']);
									$('#error_phone_found_cheaper').show();
								}
								if (json['error']['link']) {
									$('#error_link_found_cheaper').html(json['error']['link']);
									$('#error_link_found_cheaper').show();
								}
								loadImgPopup.hide();
								contentPopup.css('opacity','1');
							}
							
							if (json['success']){ 
								$('#found_cheaper-phone').val('');
								$('#found_cheaper-email').val('');
								$('#found_cheaper-link').val('');			
								
								$('#found_cheaper-send').bind('click', false);
								$('#found_cheaper-success').html(json['success']);
								$('#found_cheaper-success').show('slow');
								loadImgPopup.hide();
								contentPopup.css('opacity','1');
							} 
						}
						
					});
					
				});
			</script> 
			
			<script type="text/javascript">
				
				function do_notification_block(t,b){			
					$("#info_delivery .content").load("index.php?route=information/information/info2&information_id="+t+"&block="+b);
				}
				
				$('.reviews_btn').on('click', function () {
					$('.tabs #reviews_btn').addClass('active').siblings().removeClass('active').closest('.tabs').find('div.tabs__content').removeClass('active').eq($('.tabs #reviews_btn').index()).addClass('active');
					var $target = $('.tabs #reviews_btn');
					$('html, body').stop().animate({
						'scrollTop': $target.offset().top - 50
						}, 500, 'swing', function () {
						window.location.hash = target;
					});
					
					
				});
				
				
				
				$('.btn-buy').click( function() {
					$('.kit__btn').find(".check-offer-item").prop('checked', false);			
					$(this).prev().prop('checked', true);	
					
				});
				
				// <? if (!$ao_has_zero_price) { ?>
				// 	$('#main-add-to-cart-button').click( function() {
				// 		$(".check-offer-item").prop("checked", false);
				// 	});
				// <? } ?>
				
				
				$(document).on('click','#main-add-to-cart-button, .btn-buy, #addTo-cart-button, #sticky-block_addTo-cart-button', function() {	
					
					if ($(this).hasClass('btn-additional-offer')){
						var ao_btn_id = parseInt($(this).attr('data-ao-id'));
						console.log(ao_btn_id + ' clicked');
					}
					
					if ($(this).hasClass('btn-option-offer')){
						var ao_btn_id = parseInt($(this).attr('data-ao-id'));
						console.log(ao_btn_id + ' clicked');
					}
					
					$.ajax({
						url: '/index.php?route=checkout/cart/add',
						type: 'post',
						data: $('#product-cart input[type=\'text\'], #product-cart input[type=\'hidden\'], #product-cart input[type=\'radio\']:checked, #product-cart input[type=\'checkbox\']:checked, #product-cart select, #product-cart textarea, #kit-box input[type=\'checkbox\']:checked, #option-box input[type=\'checkbox\']:checked, #product-cart .price__btn-group input[type=\'checkbox\']:checked, .addTo-cart-qty input[type=\'checkbox\']:checked'),
						dataType: 'json',
						error:function(json) {
							console.log(json);
						},
						beforeSend: function(){	
							if (NProgress instanceof Object){				
								NProgress.configure({ showSpinner: false });
								NProgress.start();
								NProgress.inc(0.3);
							}
						},
						complete: function(){
							if (NProgress instanceof Object){
								NProgress.inc(0.3);
							}
						},
						success: function(json) {	
							
							if ($(window).width() <= "500") {
								$("body").css("overflow", "hidden");
							}
							$('.success, .warning, .attention, information, .error').hide();
							
							if (json['error']) {
								if (json['error']['option']) {
									for (i in json['error']['option']) {
										$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
									}
								}
								
								if (json['error']['message']) {
									$('#alert-message').html(json['error']['message']);
									$('#alert-message').show();
								}
							}
							
							if (json['success']) {
								$('#header-small-cart').load('/index.php?route=module/cart');
								$('#popup-cart').load('/index.php?route=common/popupcart', function(){ $('#popup-cart-trigger').click(); if (NProgress instanceof Object){ NProgress.done();  $(".fade").removeClass("out"); } });
								
								window.dataLayer = window.dataLayer || [];
								dataLayer.push({
									'event': 'addToCart',
									'ecommerce': {
										'currencyCode': '<?php echo $this->config->get('config_regional_currency'); ?>',  
										'add': {                             
											'products': [{ 
												'id':'<?php echo $google_ecommerce_info['product_id'] . GOOGLE_ID_ENDFIX; ?>',
												'name':'<?php echo $google_ecommerce_info['name']; ?>',
												'price':'<?php echo $google_ecommerce_info['price']; ?>',
												'brand':'<?php echo $google_ecommerce_info['brand']; ?>',
												'category':'<?php echo $google_ecommerce_info['category']; ?>',								
												'quantity': 1
											}]
										}
									}
								});	
								
								dataLayer.push({
									'event': 'add_to_cart',
									'value': '<?php echo $google_ecommerce_info['price']; ?>',
									'items': [{
										'id': '<?php echo $google_ecommerce_info['product_id'] . GOOGLE_ID_ENDFIX; ?>', 
										'google_business_vertical': 'retail'
									}]
								});
								
								if ((typeof fbq !== 'undefined')){
									fbq('track', 'AddToCart', 
									{
										value: <?php echo $google_ecommerce_info['price']; ?>,
										currency: '<?php echo $google_ecommerce_info['currency']; ?>',
										content_type: 'product',
										content_ids: '<?php echo $product_id; ?>'
									});
								}			
								
							}
						}
					});
				});
			</script>
			
			
			
			
			
			
			<style>		
				.product-detail section.top-slider{
				overflow: inherit;
				}
				
				.cloudzoom-lens{
				border:1px solid #dbdbdb;
				border-radius: 0;
				background:#51a88159;
				}
				.cloudzoom-blank > div:nth-of-type(3){
				display: none !important;
				}	
				
			</style>
			<script type="text/javascript">
				
				$('#waitlist-phone_sticky-block').inputmask("<?php echo $mask; ?>",{ "clearIncomplete": true });
				
				function copytext(el) {
					var $tmp = $("<textarea>");
					$("body").append($tmp);
					$tmp.val($(el).text()).select();
					document.execCommand("copy");
					$tmp.remove();
					$('.btn-copy').find('.tooltiptext').show();
					setInterval(function(){
						$('.btn-copy').find('.tooltiptext').hide();
					}, 1500);
				}  
				$(document).ready(function() {
					$('#btn-info-show').hover(function(){
						$('.name_markdown_product_mardown_reasons').addClass('show');
						}, function() {
						$('.name_markdown_product_mardown_reasons').removeClass('show');
					});										

					if ((typeof fbq !== 'undefined')){
						fbq('track', 'ViewContent', 
						{
							content_type: 'product',
							content_ids: '<?php echo $product_id; ?>'
						});
					}
				});

				<?php if ($this->config->get('config_vk_enable_pixel')) { ?>
				<?php } ?>

				window.dataLayer = window.dataLayer || [];
				dataLayer.push({
					'event':'productClick',
					'ecommerce': {
						<?php if (!empty($gacListFrom)) { ?>
							'actionField': {'list': '<?php echo $gacListFrom ?>'}, 
						<?php } ?>
						'currencyCode':'<? echo $google_ecommerce_info['currency']; ?>',
						'click': {
							'products': [{
								'id':'<?php echo $google_ecommerce_info['product_id'] . GOOGLE_ID_ENDFIX; ?>',
								'name':'<?php echo $google_ecommerce_info['name']; ?>',
								'price':'<?php echo $google_ecommerce_info['price']; ?>',
								'brand':'<?php echo $google_ecommerce_info['brand']; ?>',
								'category':'<?php echo $google_ecommerce_info['category']; ?>'
							}]
						}
					}
				});									
			</script>
			
			
			<script type="text/javascript">
				
				
				$(document).ready(function() {											
					$('#review').on('DOMSubtreeModified', function(){												
						$('#review .pagination a').on('click', function(e) {
							e.preventDefault();
							
							$('#review').fadeOut('slow');
							$('#review').load(this.href);
							$('#review').fadeIn('slow');
							
							$("html, body").animate({ scrollTop: $('#review').offset().top }, 1000);
							
							return false;
						});
					})
					
					
					$.ajax({
						url: 'index.php?route=product/product/review&product_id=<?php echo $product_id; ?>',
						type: 'get',
						success: function(data) {
							$('#review').html(data);
						}
					});
					
					var note = $('#note');
					
					<?php if (!empty($active_coupon)) { ?>
					ts = "<?php echo $active_coupon['date_end']; ?>";
					ts = new Date(ts);
					$('#coupon__datend').countdown({
						timestamp : ts,
						format: "on",
						callback  : function(days, hours, minutes, seconds){
							
							var message = "";
							
							message += days + " дней " + ( days==1 ? '':'' ) + " ";
							message += hours + " :" + ( hours==1 ? '':'' ) + " ";
							message += minutes + " : " + ( minutes==1 ? '':'' ) + " ";
							message += seconds + "" + ( seconds==1 ? '':'' );
							
							note.html(message);
						}
					});
				<?php } ?>
					
					
					
					
					$('#found_cheaper-phone').mask("<?php echo $mask; ?>");		
					
					
					
					if ($(window).width() >= '1310'){
						var _hLeftBlock = $('.gallery-top').innerHeight();
						<?php if (!empty($active_action)) { ?>
							$('#product-cart').css('height','auto');
							<?php } else { ?>
							$('#product-cart').css('height',_hLeftBlock);
						<?php } ?>
						
					}		
					
					// Скрытие текста О товаре
					let _hDescripionTitle = $('.about-section .about .title').innerHeight();	
					let _hDescripionText = $('.about-section .about .desc').innerHeight();	
					let _hMainDescripion = _hDescripionTitle + _hDescripionText;
					
					let _hReviews = $('.about-section .rev_content').innerHeight();
					let _hReviewsHead = $('.about-section .reviews-col__head').innerHeight();			
					
					if( _hMainDescripion > _hReviews){
						
						$('.desc').css({'height':_hReviews-_hDescripionTitle,'overflow':'hidden'}).addClass('manufacturer-info-hide');
						
						$('.desc').parent().append('<button class="btn-open-desc"><span>Читать полностью</span><i class="fas fa-angle-down"></button>');
						
						$('.btn-open-desc').on('click', function(){
							let test = $('body').offset().top;
							console.log(test)
							var _textBtn = $(this).find('span').text();
							$('.desc').parent().toggleClass('open-btn');
							$(this).find('span').text(_textBtn == "Читать полностью" ? "Скрыть" : "Читать полностью");
							return false;
						});
						
					}
					
					// Быстрый заказ
					function reloadAllFn(){
						initPopups();
						rebuildMaskInput();
					}
					$(document).on('click', '#quick-order-btn', function (e) {
						
						e.preventDefault();
						
						if (NProgress instanceof Object){				
							NProgress.configure({ showSpinner: false });
							NProgress.start();
							NProgress.inc(0.4);
						}
						
						$.post('/index.php?route=checkout/quickorder/loadtemplate',  
						$('#product-cart input[type=\'text\'], #product-cart input[type=\'hidden\'], #product-cart input[type=\'radio\']:checked, #product-cart input[type=\'checkbox\']:checked, #product-cart select, #product-cart textarea'), 
						function (data) {	
							if (NProgress instanceof Object){ NProgress.inc(0.3);	}
							
							window.dataLayer = window.dataLayer || [];
							dataLayer.push({
								'event': 'addToCart',
								'ecommerce': {
									'currencyCode': '<?php echo $this->config->get('config_regional_currency'); ?>',  
									'add': {                             
										'products': [{ 
											'id':'<?php echo $google_ecommerce_info['product_id']; ?>',
											'name':'<?php echo $google_ecommerce_info['name']; ?>',
											'price':'<?php echo $google_ecommerce_info['price']; ?>',
											'brand':'<?php echo $google_ecommerce_info['brand']; ?>',
											'category':'<?php echo $google_ecommerce_info['category']; ?>',								
											'quantity': 1
										}]
									}
								}
							});	

							<?php if ($this->config->get('config_vk_enable_pixel')) { ?>
							<?php } ?>
							
							
							$('#quick_popup_container').html(data); 
							
							
							if (NProgress instanceof Object){ NProgress.done();  $(".fade").removeClass("out"); }							
							$('#quick_popup').show();
							$("#main-overlay-popup").show();
							let scrol = window.scrollY + 50;
							$('#quick_popup').css("top", scrol);
							$(".overlay-popup-close").click(function () {
								$("#main-overlay-popup").hide();
								$('#quick_popup').hide();
								if ($(window).width() <= "500") {
									$("body").css("overflow", "initial");
								}
							});
							
						});			
					});
					
				});
				
				
			</script>
			
			<?php if($is_set) { ?>
				<script type="text/javascript">			
					$('div.kitchen-review').append('<div id="list-products-in-set-product-page"><div class="load-image"><img src="image/set-loader-min.gif" alt=""></div></div>');
					$('#list-products-in-set-product-page').load(
					'index.php?route=module/set/productload&set_id=<?php echo $is_set; ?>',
					function () {
						$('#button-cart').on('click', function () {
							
						});
						$('.load-image').hide();
					}
					);
				</script>
			<?php } ?>		
			
			<?php if ($this->config->get('config_store_id') == 22) { ?>	
				<style>
					@media screen and (max-width: 560px){
					
					#product-detail .tabs__nav{
					position: fixed;
					left: 0;
					background: #f7f4f4;
					z-index: 100;
					}
					#product-detail .tabs__nav.fix-top{
					position: fixed;
					}
					}
				</style>
			<?php } ?>
			
			
			<script>
				if(document.documentElement.clientWidth > 1000) {  		
					var showFixedAdd = $('.gallery-top').innerHeight();
					
					if ($(this).scrollTop() > showFixedAdd) $('.addTo-cart-holder').fadeIn();
					else $('.addTo-cart-holder').fadeOut();
					$(window).scroll(function () { 
						if ($(this).scrollTop() > showFixedAdd) $('.addTo-cart-holder').fadeIn();
						else $('.addTo-cart-holder').fadeOut();
					});
					
					$(function(){
						$('.addTo-cart-holder').liFixar({
							side: 'bottom',                
							position: 10,               
							fix: function(el, side){
								el.liFixar('update');
								el.parent('.addTo-cart-wrapper').removeClass('unfix');
							},
							unfix: function(el, side){
								el.liFixar('update');
								el.parent('.addTo-cart-wrapper').addClass('unfix');
							}                                         
						});
					});
				};
				
				<?php if ($this->config->get('config_store_id') == 22) { ?>	
					// fix tab top on mobile	
					if(document.documentElement.clientWidth < 560) { 
						
						window.onscroll = function () {
							let scrollToTop = window.scrollY; 
						}		
						let topSearchHeight = $('.top-search').innerHeight();
						let topMenuHeight = $('.top-menu').innerHeight();
						let headerHeight = $('header').innerHeight();
						let fixNavTab = $('#product-detail .tabs__nav');
						let winSize = $(window).width();
						let sumHeight = topSearchHeight + topMenuHeight;
						
						if(fixNavTab.hasClass('fix-top')){
							fixNavTab.css({ 
								'top': sumHeight,
								'width': winSize
							});
							} else {
							fixNavTab.css({
								'top': headerHeight,
								'width': winSize
							})
						}
						
						$(window).scroll(function () {
							
							let scrollToTop = window.scrollY; 
							let topSearchHeight = $('.top-search').innerHeight();
							let topMenuHeight = $('.top-menu').innerHeight();
							let sumHeight = topSearchHeight + topMenuHeight;
							let headerHeight = $('header').innerHeight() - scrollToTop;
							let fixNavTab = $('#product-detail .tabs__nav');
							
							console.log(headerHeight);
							console.log(scrollToTop)
							if(fixNavTab.hasClass('fix-top')){
								fixNavTab.css({ 
									'top': sumHeight,
									'width': winSize
								});
								} else {
								fixNavTab.css({
									'top': headerHeight - 10,
									'width': winSize
								})
							}
						});
					};
				<?php } ?>
				setTimeout(function(){
					<?php if ($is_markdown) { ?>	
						// markdownReasonSlider
						
						if ($(".image-markdown-reason .gallery-top")[0]) {
							var markdownReasonTop = new Swiper('.image-markdown-reason .gallery-top .swiper-container', {
								slidesPerView: 'auto',
								loop: true,
								loopedSlides: 4,
								pagination: {
									el: '.image-markdown-reason .gallery-top .swiper-pagination',
									clickable: true,
								},
								breakpoints: {
									1280: {
										loopedSlides: 4,
									},
								},
								thumbs: {
									swiper: markdownReasonThumb
								}
							});
							var markdownReasonThumb = new Swiper('.image-markdown-reason .gallery-thumbs .swiper-container', {
								centeredSlides: false,
								slidesPerView: 4,
								touchRatio: 0.2,
								slideToClickedSlide: true,
								slideActiveClass: 'swiper-slide-thumb-active',
								loop: true,
								loopedSlides: 4,
								direction: 'vertical',
								height: 400,
								navigation: {
									nextEl: '.image-markdown-reason .gallery-thumbs .swiper-button-next',
									prevEl: '.image-markdown-reason .gallery-thumbs .swiper-button-prev',
								},
								breakpoints: {
									1280: {
										loopedSlides: 4,
									},
								}
							});
							markdownReasonTop.controller.control = markdownReasonThumb;
							markdownReasonThumb.controller.control = markdownReasonTop;
							
						}
						
						$('#markdown-reason-btn').on('click', function() {	 	
							setTimeout(function(){
								markdownReasonThumb.update();
								markdownReasonTop.update();
							}, 100);
						});		
					<?php } ?>
				},1000)
				
				
				if(document.documentElement.clientWidth < 560) { 	
					// fixBTN	
					$('#product-cart .price__btn-group').appendTo('.product-info__left-col');
					
					// delivery to pulse
					if($('.delivery_terms').hasClass('position_pluses-item')){
						$('#product-cart .product-info__delivery').appendTo('.pluses-item ul').wrap('<li><li/>');
						// $('.product-info__delivery');
					};
					
					<?php if ($free_delivery) { ?>
						$('#product-cart .delivery_info_free').appendTo('.pluses-item');
					<?php } ?>
					
					
					
				};				
				
				
				// Новые табы
				
				
				$(document).ready(function() {
					$(window).scroll(function () { 
						if ($(this).scrollTop() > 650) {
							$('header .top-menu .tab-product-wrap').addClass('show');
							$('.sticky-block.base-padding').addClass('show_tab');
							}  else {
							$('header .top-menu .tab-product-wrap').removeClass('show');
							$('.sticky-block.base-padding').removeClass('show_tab');
							
						}
					});
				});
				
				var tabClon = $('.tabs__caption').clone();
				$('#tab_header').appendTo('header .top-menu .tab-product-wrap');
				$(tabClon).appendTo('#tab_header .tab_header');
				
				function tabDesc(){
					if($('#main_btn').hasClass('active')){
						$('.product__body').addClass('description');
						$('#tab_header .price_product').show();
						$('#tab_header .addTo-cart-qty').show();
						} else {
						$('.product__body').removeClass('description');
						$('#tab_header .price_product').hide();
						$('#tab_header .addTo-cart-qty').hide();
					}
				}
				tabDesc();
				
				$('.tabs.item-page').bind('DOMSubtreeModified', function(){
					tabDesc();
				})
				
				$('header .top-menu .tabs__caption li').each(function(){
					var idLi = $(this).attr('id');
					var link = $(this).wrap('<a href="'+window.location.href+'#'+idLi+'" class="tab_link" ></a>');
					
					$(link).click(function(event) {
						$('header .top-menu .tabs__caption li').removeClass('active');
						$('.tabs__nav .tabs__caption').find('#'+idLi+'').trigger('click');
						$(this).find('li').addClass('active');
						
						var qtarget = $('.tabs__nav .tabs__caption').find('#'+idLi+'');
						if(document.documentElement.clientWidth < 560) { 	
							$('html, body').stop().animate({
								'scrollTop': qtarget.offset().top - 107.5
								}, 500, 'swing', function () {
							});
							} else {
							$('html, body').stop().animate({
								'scrollTop': qtarget.offset().top - 67.5
								}, 500, 'swing', function () {
							});
						}
					});
					
				});
				
				$('.tabs__nav .tabs__caption li').each(function(index, el) {
					let idLi = $(this).attr('id');
					$(this).click(function(event) {
						
						window.location.hash = idLi;
						$('header .top-menu .tabs__caption li').removeClass('active');
						$('header .top-menu .tabs__caption li#'+idLi).addClass('active');
					});
				});
				if ($(window).width() <= '850'){
					setTimeout(function(){
						
						
						let M3 = document.querySelector(".js-dragscroll-wrap3");
						if (M3) {
							let e3 = M3.querySelector(".js-dragscroll2");
							new ScrollBooster({
								viewport: M3,
								content: e3,
								emulateScroll: false,
								mode: "x",
								direction: 'horizontal',
								bounceForce: .2, onUpdate: t3 => {
									e3.style.transform = `translate(\n                  ${-t3.position.x}px, 0px                )`
								}
							})
						}
					}, 1000)
				};
				
				
				
				// var valWaitlist = telWaitlist.attr('data-simple-mask');
				// telWaitlist.val("").focus().val(valWaitlist);
				
				
				// .block-text-home-mob
				// var left_fade = 150;
				// $(document).ready(function() {
				//   $('.tab-product-wrap').scroll(function () { 
				//     if ($(this).scrollLeft() > left_fade) {
				//     	$(this).removeClass('mb-scroll');
				//     } else {
				//     	$(this).addClass('mb-scroll');
				//     }
				//   });
				// });
				
				// Новые табы
				
				function showRegisterProdDetail(e){
					let name  =  document.querySelector('h1.title').innerHTML,
					link  =  window.location.href,
					modal =  document.getElementById('show_register_modal');
					modal.querySelector('.name_product').setAttribute('href', link);
					modal.querySelector('.name_product').textContent = name;  
					
					document.getElementById('main-overlay-popup').style.display = 'block';
					document.getElementById('show_register_modal').style.display = 'block';
					document.getElementById('show_register_modal').querySelector('.close_modals').addEventListener('click', function(){
						document.getElementById('main-overlay-popup').style.display = 'none';
						document.getElementById('show_register_modal').style.display = 'none';
					});
				}   
				
				setTimeout(function(){
					<?php if(count($images) + $videoInt >= 1)  { ?>
						// Главный слайдер товара	
						if ($(".gallery-top")[0]) {			
							
							// Slider top product
							var galleryThumbs<?php echo count($images) + $videoInt; ?> = new Swiper('.gallery-thumbs .swiper-container.thumbImages_<?php echo count($images) + $videoInt; ?>', {
								centeredSlides: false,
								
								<?php if(count($images) + $videoInt >= 5)  {?>
									slidesPerView: 4,
									loop: true,
									loopedSlides: 4,
									height: 400,
									breakpoints: {
										1280: {
											loopedSlides: 5,
										},
									},
									<?php } elseif(count($images) + $videoInt == 4) { ?>
									slidesPerView: 4,
									height: 400,
									breakpoints: {
										1300: {
											slidesPerView: 4,
											height: 400,
										},
										1400: {
											slidesPerView: 5,
											height: 500,
										},
									},
									<?php } elseif(count($images) + $videoInt == 3) { ?>
									slidesPerView: 4,
									height: 400,
									<?php } elseif(count($images) + $videoInt == 2) { ?>
									slidesPerView: 3,
									height: 300,
									<?php } elseif(count($images) + $videoInt == 1) { ?>
									
									slidesPerView: 2,
									height: 200,
								<?php } ?>		
								on:{
									slideChangeTransitionEnd: function () {				      
										jQuery("iframe").each(function() { 
											var src= jQuery(this).attr('src');
											jQuery(this).attr('src',src);  
										});
									},
								},
								touchRatio: 0.2,
								slideToClickedSlide: true,
								slideActiveClass: 'swiper-slide-thumb-active',
								direction: 'vertical',
								navigation: {
									nextEl: '.gallery-thumbs .swiper-button-next',
									prevEl: '.gallery-thumbs .swiper-button-prev',
								},
								
							});
							
							// наведение на thumb смена слайда
							// $('.swiper-slide').on('mouseover', function() {
							//     galleryThumbs<?php echo count($images) + $videoInt; ?>.slideTo($(this).index());
							// });			
							
							var galleryTop<?php echo count($images) + $videoInt; ?> = new Swiper('.gallery-top .swiper-container.topImages_<?php echo count($images) + $videoInt; ?>', {
								slidesPerView: 'auto',
								loop: true,
								loopedSlides: 4,
								pagination: {
									el: '.gallery-top .swiper-pagination',
									clickable: true,
								},
								breakpoints: {
									1280: {
										loopedSlides: 5,
									},
								},
								<?php if(count($images) + $videoInt < 5)  {?>
									thumbs: {
										swiper: galleryThumbs<?php echo count($images) + $videoInt; ?>
									}
								<?php } ?>	
							});
							<?php if(count($images) + $videoInt >= 5)  {?>
								galleryTop<?php echo count($images) + $videoInt; ?>.controller.control = galleryThumbs<?php echo count($images) + $videoInt; ?>;
								galleryThumbs<?php echo count($images) + $videoInt; ?>.controller.control = galleryTop<?php echo count($images) + $videoInt; ?>;
							<?php } ?>	  
							
							
						}
						
					<?php } ?>
				},1100)
				
				
			</script>		
			<script type="text/javascript">
				
				$(document).ready(function() {
					
					if ((typeof fbq !== 'undefined')){
						fbq('track', 'ViewContent', 
						{
							content_type: 'product',
							content_ids: '<?php echo $product_id; ?>'
						});
					}
					
					window.dataLayer = window.dataLayer || [];
					dataLayer.push({
						'event':'productDetail',
						'ecommerce': {
							<?php if (!empty($gacListFrom)) { ?>
								'actionField': {'list': '<?php echo $gacListFrom ?>'}, 
							<?php } ?>
							'currencyCode':'<? echo $google_ecommerce_info['currency']; ?>',
							'detail': {
								'products': [{
									'id':'<?php echo $google_ecommerce_info['product_id'] . GOOGLE_ID_ENDFIX; ?>',
									'name':'<?php echo $google_ecommerce_info['name']; ?>',
									'price':'<?php echo $google_ecommerce_info['price']; ?>',
									'brand':'<?php echo $google_ecommerce_info['brand']; ?>',
									'category':'<?php echo $google_ecommerce_info['category']; ?>'
								}]
							}
						}
					});	
					
					dataLayer.push({
						'event': 'view_item',
						'value': '<?php echo $google_ecommerce_info['price']; ?>',
						'items': [{
							'id': '<?php echo $google_ecommerce_info['product_id'] . GOOGLE_ID_ENDFIX; ?>', 
							'google_business_vertical': 'retail'
						}]
					});
				});
			</script>	
			<script>
				function colorbox(el) {
					console.log(el)
					var img = $(el);  
					var src = img.attr('src');
					$("body").append("<div class='popup_rev' style='background: #00000057;'>"+ 
					"<div class='modal-content cart-popap-modal'><div class='overlay-popup-close btn-modal'></div><img src='"+src+"' class='popup_img_rev' /><div>"+ 
					"</div>"); 
					$('.popup_bg_rev').fadeIn(300);
					$(".popup_rev").fadeIn(300); 
					$(".popup_bg_rev, .btn-modal, .popup_rev").click(function(){  
						$(".popup_rev").fadeOut(300); 
						$('.popup_bg_rev').fadeOut(300);
						$(el).empty();
						$(".popup_rev").remove();
						setTimeout(function() { 
							$(".popup_rev").remove();
						}, 300);
					});
				}
			</script>			
		<?php echo $footer; ?>   																																			