<?php echo $header; ?>
<style>
	.mono_monocheckout_enable{
	    background: black;
	    padding: 0 0 !important;
	    overflow: hidden;
	}
	.mono_monocheckout_enable picture{
		display: flex;
	}
	@media screen and (max-width: 560px) {
		.mono_monocheckout_enable{
			height: 40px !important;
		    overflow: hidden;
		    border-radius: 8px !important;
		}
		.mono_monocheckout_enable img{
			height: 40px;
		}
	}
	header #tab_header .tab_header ul {
	    text-align: left
	}

	header #tab_header .tab_header ul a li {
	    padding: 0 0 2px 0;
	    font-size: 12px;
	    line-height: 14px;
	    margin-right: 14px;
	    color: #404345;
	    font-weight: 500
	}

	header #tab_header .tab_header ul a li::after {
	    height: 1px;
	    background: #51a881
	}
</style>
<?php if (!empty($this->session->data['gac:listfrom'])) {
	$gacListFrom = $this->session->data['gac:listfrom'];
unset($this->session->data['gac:listfrom']); } 
?>
<style type="text/css">
	.main_information_wrap.mobile{
		display: flex;
		flex-direction: column;
		gap: 20px;
	}
	@media screen and (max-width: 580px) {
		.popular-goods .head_slider .title,
		#product-detail .reviews_btn .reviews-col__item .reviews__author-name .reviews__about .name, #product-detail .reviews_btn .title, .main_information_wrap .delivery>p, #product-detail .photo_btn .mySwiperMain .navigation .title{
			font-weight: 500 !important;
		}
	}
</style>
<!-- new tab -->
<script type="text/javascript">
	window.product_id = <?php echo $product_id; ?>
	
	$(document).ready(function(){
		if ($(window).width() >= '1000'){
			setTimeout(function(){
				CloudZoom.quickStart();
				console.log('cloudzoom hover')
			},1500);		
		}          
	});
	
	function rebuildMaskInput(){
		$('#waitlist-phone').inputmask("<?php echo $mask; ?>",{ 
			"clearIncomplete": true, 
			_radixDance: true,
    		numericInput: true,
		});
		<? if ($customer_telephone) { ?>
			$('#waitlist-phone').val("<? echo $customer_telephone; ?>");
		<? } ?>
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
		<?php if (IS_MOBILE_SESSION) { ?>
			<div class="main_information_wrap mobile">
				
					<h1 class="title"><?php echo $heading_title; ?></h1>
				
			</div>
		<?php } ?>
		<div class="main_information_wrap product_head">
			
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
						<?php echo $youtubes; ?>
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
													<img src="<?php echo $image['middle']; ?>"
													alt="<?php echo $heading_title; ?>">
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
									zoomPosition:'#product-cart',
									hoverIntentDelay: 500,
									"
									width="500"
									height="500"
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
												zoomPosition:'#product-cart',
												hoverIntentDelay: 500,
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

			<div class="main_information_product item-column" id="product-cart">
				<div class="head">
					<div class="review">
						<?php if ($review_status) { ?>
							<span class="rate rate-<?php echo $rating; ?>"></span>
						<?php } ?>
						<span class="reviews_btn reviews_count">
							<?php echo $review_count; ?> відгуків
						</span>
					</div>
					<div class="product-info">
						<span>
							<?= $text_code ?>: <?php echo $product_id; ?>
						</span>
					</div>
					<div class="btn-group">
						<?php if ($this->config->get('show_wishlist') == '1')  { ?>                  
							<?php if ($logged) { ?>	
								<button onclick="addToWishList('<?php echo $product_id; ?>');"  class="btn-favorite" title="<?php echo $button_wishlist; ?>">
									<svg width="22" height="21" viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M10.9932 4.13581C8.9938 1.7984 5.65975 1.16964 3.15469 3.31001C0.649644 5.45038 0.296968 9.02898 2.2642 11.5604C3.89982 13.6651 8.84977 18.1041 10.4721 19.5408C10.6536 19.7016 10.7444 19.7819 10.8502 19.8135C10.9426 19.8411 11.0437 19.8411 11.1361 19.8135C11.2419 19.7819 11.3327 19.7016 11.5142 19.5408C13.1365 18.1041 18.0865 13.6651 19.7221 11.5604C21.6893 9.02898 21.3797 5.42787 18.8316 3.31001C16.2835 1.19216 12.9925 1.7984 10.9932 4.13581Z" stroke="#696F74" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</button>
							<?php } else { ?>
								<button onclick="showRegisterProdDetail(this);" class="btn-favorite" title="<?php echo $button_wishlist; ?>">
									<svg width="22" height="21" viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M10.9932 4.13581C8.9938 1.7984 5.65975 1.16964 3.15469 3.31001C0.649644 5.45038 0.296968 9.02898 2.2642 11.5604C3.89982 13.6651 8.84977 18.1041 10.4721 19.5408C10.6536 19.7016 10.7444 19.7819 10.8502 19.8135C10.9426 19.8411 11.0437 19.8411 11.1361 19.8135C11.2419 19.7819 11.3327 19.7016 11.5142 19.5408C13.1365 18.1041 18.0865 13.6651 19.7221 11.5604C21.6893 9.02898 21.3797 5.42787 18.8316 3.31001C16.2835 1.19216 12.9925 1.7984 10.9932 4.13581Z" stroke="#696F74" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>

								</button>
							<?php } ?>
						<?php } ?>
						<?php if ($this->config->get('show_compare') == '1')  { ?>
							<button class="addToCompareBtn" onclick="addToCompare('<?php echo $product_id; ?>');" title="<?php echo $text_retranslate_35; ?>">
								<svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M1 15V14.8498C1 14.5333 1 14.3751 1.02421 14.2209C1.0457 14.084 1.08136 13.9497 1.13061 13.8202C1.18609 13.6743 1.2646 13.5369 1.42162 13.2622L5 7M1 15C1 17.2091 2.79086 19 5 19C7.20914 19 9 17.2091 9 15M1 15V14.8C1 14.52 1 14.38 1.0545 14.273C1.10243 14.1789 1.17892 14.1024 1.273 14.0545C1.37996 14 1.51997 14 1.8 14H8.2C8.48003 14 8.62004 14 8.727 14.0545C8.82108 14.1024 8.89757 14.1789 8.9455 14.273C9 14.38 9 14.52 9 14.8V15M5 7L8.57838 13.2622C8.7354 13.5369 8.81391 13.6743 8.86939 13.8202C8.91864 13.9497 8.9543 14.084 8.97579 14.2209C9 14.3751 9 14.5333 9 14.8498V15M5 7L17 5M13 13V12.8498C13 12.5333 13 12.3751 13.0242 12.2209C13.0457 12.084 13.0814 11.9497 13.1306 11.8202C13.1861 11.6743 13.2646 11.5369 13.4216 11.2622L17 5M13 13C13 15.2091 14.7909 17 17 17C19.2091 17 21 15.2091 21 13M13 13V12.8C13 12.52 13 12.38 13.0545 12.273C13.1024 12.1789 13.1789 12.1024 13.273 12.0545C13.38 12 13.52 12 13.8 12H20.2C20.48 12 20.62 12 20.727 12.0545C20.8211 12.1024 20.8976 12.1789 20.9455 12.273C21 12.38 21 12.52 21 12.8V13M17 5L20.5784 11.2622C20.7354 11.5369 20.8139 11.6743 20.8694 11.8202C20.9186 11.9497 20.9543 12.084 20.9758 12.2209C21 12.3751 21 12.5333 21 12.8498V13M11 1V6" stroke="#696F74" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
						<?php } ?>
						<button  id="share-btn">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M19 10V14.2C19 15.8802 19 16.7202 18.673 17.362C18.3854 17.9265 17.9265 18.3854 17.362 18.673C16.7202 19 15.8802 19 14.2 19H5.8C4.11984 19 3.27976 19 2.63803 18.673C2.07354 18.3854 1.6146 17.9265 1.32698 17.362C1 16.7202 1 15.8802 1 14.2V10M14 5L10 1M10 1L6 5M10 1V13" stroke="#696F74" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<div id="share-popup">
							  	<h3>Поділитися:</h3>
							  	<ul>
								    <li>
								    	<a href="#" class="share-btn" data-network="facebook">
								    		<i class="fab fa-facebook-f"></i> Facebook
								    	</a>
								    </li>
								    <li>
								    	<a href="#" class="share-btn" data-network="twitter">
								    		<i class="fab fa-twitter"></i> Twitter
								    	</a>
								    </li>
								    <li>
								    	<a href="#" class="share-btn" data-network="email">
								    		<i class="far fa-envelope"></i> Email
								    	</a>
								    </li>
							  	</ul>
							</div>
						</button>

						
					</div>
				</div>
				<div class="label">
					<?php if ($has_video) { ?>
						<div class="product__label-stock">
							<svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M16.5 3.69853C16.5 3.24417 16.5 3.01699 16.4102 2.91179C16.3322 2.82051 16.2152 2.77207 16.0956 2.78149C15.9577 2.79234 15.797 2.95298 15.4757 3.27426L12.75 6L15.4757 8.72574C15.797 9.04702 15.9577 9.20766 16.0956 9.21851C16.2152 9.22793 16.3322 9.17949 16.4102 9.08821C16.5 8.98301 16.5 8.75583 16.5 8.30147V3.69853Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M1.5 4.35C1.5 3.08988 1.5 2.45982 1.74524 1.97852C1.96095 1.55516 2.30516 1.21095 2.72852 0.995235C3.20982 0.75 3.83988 0.75 5.1 0.75H9.15C10.4101 0.75 11.0402 0.75 11.5215 0.995235C11.9448 1.21095 12.289 1.55516 12.5048 1.97852C12.75 2.45982 12.75 3.08988 12.75 4.35V7.65C12.75 8.91012 12.75 9.54018 12.5048 10.0215C12.289 10.4448 11.9448 10.789 11.5215 11.0048C11.0402 11.25 10.4101 11.25 9.15 11.25H5.1C3.83988 11.25 3.20982 11.25 2.72852 11.0048C2.30516 10.789 1.96095 10.4448 1.74524 10.0215C1.5 9.54018 1.5 8.91012 1.5 7.65V4.35Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<?php echo $text_product_has_video; ?>
						</div>
					<? } ?>
					<?php if ($rating == 5 && $count_reviews >= 4) { ?>
						<div class="product__label-hit">
							<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5.25 16.5H3C2.60218 16.5 2.22064 16.342 1.93934 16.0607C1.65804 15.7794 1.5 15.3978 1.5 15V9.75C1.5 9.35218 1.65804 8.97064 1.93934 8.68934C2.22064 8.40804 2.60218 8.25 3 8.25H5.25M10.5 6.75V3.75C10.5 3.15326 10.2629 2.58097 9.84099 2.15901C9.41903 1.73705 8.84674 1.5 8.25 1.5L5.25 8.25V16.5H13.71C14.0717 16.5041 14.4228 16.3773 14.6984 16.143C14.9741 15.9087 15.1558 15.5827 15.21 15.225L16.245 8.475C16.2776 8.26002 16.2631 8.04051 16.2025 7.83169C16.1419 7.62287 16.0366 7.42972 15.8939 7.26564C15.7512 7.10155 15.5746 6.97045 15.3762 6.88141C15.1778 6.79238 14.9624 6.74754 14.745 6.75H10.5Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<?php echo $text_product_has_good_rating; ?>
						</div>
					<? } ?>
					<?php if ($current_in_stock) { ?>
						<div class="product__label-best-price">
							<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M9 11.2502L6.75 9.00019M9 11.2502C10.0476 10.8517 11.0527 10.3492 12 9.75019M9 11.2502V15.0002C9 15.0002 11.2725 14.5877 12 13.5002C12.81 12.2852 12 9.75019 12 9.75019M6.75 9.00019C7.14911 7.96476 7.65165 6.97223 8.25 6.03769C9.12389 4.64043 10.3407 3.48997 11.7848 2.69575C13.2288 1.90154 14.852 1.48996 16.5 1.50019C16.5 3.54019 15.915 7.12519 12 9.75019M6.75 9.00019H3C3 9.00019 3.4125 6.72769 4.5 6.00019C5.715 5.19019 8.25 6.00019 8.25 6.00019M3.375 12.3752C2.25 13.3202 1.875 16.1252 1.875 16.1252C1.875 16.1252 4.68 15.7502 5.625 14.6252C6.1575 13.9952 6.15 13.0277 5.5575 12.4427C5.26598 12.1644 4.88197 12.0037 4.47917 11.9912C4.07637 11.9787 3.68316 12.1155 3.375 12.3752Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<?php echo $text_product_current_in_stock; ?>
						</div>
					<? } ?>
					<?php if (!empty($bestseller)) { ?>
						<div class="product__label-bestseller">
							<svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M6 7C7.5 4.78 6 1.75 5.25 1C5.25 3.2785 3.92025 4.55575 3 5.5C2.0805 6.445 1.5 7.93 1.5 9.25C1.5 10.4435 1.97411 11.5881 2.81802 12.432C3.66193 13.2759 4.80653 13.75 6 13.75C7.19347 13.75 8.33807 13.2759 9.18198 12.432C10.0259 11.5881 10.5 10.4435 10.5 9.25C10.5 8.101 9.708 6.295 9 5.5C7.6605 7.75 6.90675 7.75 6 7Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<?php echo $label_sales_hit; ?>							
						</div>
					<? } ?>
					<?php if ($new) { ?>
						<div class="product__label-new">
							<?php echo $label_sales_new; ?>							
						</div>
					<? } ?>
				</div>
				<input type="hidden" name="product_id" size="4" value="<?php echo $product_id; ?>">
				<?php if (!IS_MOBILE_SESSION) { ?>
					<h1 class="title"><?php echo $heading_title; ?></h1>
				<?php } ?>
				<div class="detail-wrap <?php if ($need_ask_about_stock || $can_not_buy) { ?>can_not_buy<?php } ?>">
					<div class="left-column">
						<?php if ($variants) { ?>
							<div class="detail-variants">
								<span class="title_module"><?php echo $text_retranslate_89; ?></span>
								<div class="wrap_variant">
									<?php foreach($variants as $variant) { ?>
										<div class="item_variant variant_show">
											<a href="<?php echo $variant['href']; ?>" title="<?php echo $variant['name']; ?>">
												<?php if (!empty($variant['variant_name_2']) && !empty($variant['variant_value_2'])) { ?>
													<img src="<?php echo $variant['thumb']; ?>" alt="<?php echo $variant['variant_name_2']; ?>: <?php echo $variant['variant_value_2']; ?>" />											
													<span class="tooltiptext">
														<span class="text"><?php echo $variant['variant_name_2']; ?> : <?php echo $variant['variant_value_2']; ?></span>
														<span class="price">
															<?php echo $variant['price']; ?>
														</span>
													</span>
												<?php } else { ?>
													<img src="<?php echo $variant['thumb']; ?>" alt="<?php echo $variant['name']; ?>" />
													<span class="tooltiptext">
														<span class="text">
															<?php echo trim(substr($variant['name'], 0, 100)) . '...'; ?>
														</span>	
														<span class="price">
															<?php echo $variant['price']; ?>
														</span>
													</span>
												<?php } ?>
											</a>
											
										</div>
									<?php } ?>
									<?php if(count($variants) > 4) { ?>
										<div class="item_variant">
											<button class="variant-link variant-link-toggle">
												+ <?php echo count($variants) - 4; ?>
												<i class="fas fa-chevron-up"></i>
											</button>
										</div>
									<?php } ?>
								</div>	
									
							</div>
							<script type="text/javascript">
								let wrap_variant = document.querySelector('.detail-variants .wrap_variant'),
									variant_show = document.querySelectorAll('.wrap_variant .variant_show'),
									show_variant = document.querySelector('.variant-link.variant-link-toggle');

								for (var i = 0; i < variant_show.length; ++i) {
									if(i > 3) {
										variant_show[i].classList.add('variant_hidden');
									}
								}
								if(show_variant) {
									show_variant.addEventListener("click", function() {
									  	wrap_variant.classList.toggle('open');
									  	this.classList.toggle('open');
									  	document.getElementById('product-cart').classList.toggle('open');
									});
								}
								
								
							</script>
						<?php } ?>
						<span class="ajax-module-reloadable" data-modpath="product/product/getPriceInfo" data-x="<?php echo $product_id; ?>" data-afterload="rebuildMaskInput">
							<?php include($this->checkTemplate(dirname(__FILE__),'/../blocks/price.tpl')); ?>
						</span>
					</div>
					<div class="right-column <?php if ($need_ask_about_stock || $can_not_buy) { ?>can_not_buy<?php } ?>">
						<?php if ($show_delivery_terms) { ?>
							<div class="delivery <?php if ($need_ask_about_stock || $can_not_buy) { ?>can_not_buy<?php } ?>">
								<p>Інформація про доставку</p>
								<div id="product_delivery_info_reloadable" class="delivery__info ajax-module-reloadable" data-modpath="product/product/getDeliveryInfo" data-x="<?php echo $product_id; ?>" data-afterload="initProductChangeCityPopupTrigger">
									<div class="load">
										<i class="fas fa-spinner fa-spin"></i> <?php echo $delivery_to_city_temp_text; ?>
									</div>
								</div>
								<div id="change-city-product-city-name-selection-popup"></div>		
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>



	<div class="wrap">
		<?php /* З цим товаром також купують */ ?>
		<?php echo $content_product_middle; ?>
		
	</div>
				

	<div class="wrap">
		<div class="tabs item-page">
			<!--tabs__nav-->
			<div class="tabs__nav head_product js-dragscroll-wrap2">
				<ul class="tabs__caption tabs__caption_header js-dragscroll2">
					<?php if($attribute_groups_special || ($description && strlen(trim($description)) > 32)) { ?>
						<li id="main_btn" class="active"><?php echo $text_retranslate_5; ?></li>
					<?php } ?>
					<li id="characteristic_btn" <?php if(!$description && !$attribute_groups_special) { ?> class="active"<?php } ?>><?php echo $text_retranslate_7; ?></li>
					<li id="photo_btn">
						<?php echo $text_retranslate_8; ?> 
						<?php if ($youtubes || $videos) { ?> 
							<?php echo $text_retranslate_9; ?> 
						<?php } ?>
					</li>
					<li id="reviews_btn"><?php echo $text_retranslate_10; ?> (<?php echo $review_count; ?>)</li>
				</ul>

			</div>
			<!--/tabs__nav-->
			<!--tabs__content-->
			<?php if($description) { ?>
				<div class="tabs__content main_btn active" id="product-tab-info" style="position: relative;">
					<div class="about">											
						<h4 class="title"><?php echo $text_retranslate_56; ?> <?php echo $heading_title; ?></h4>
						<div class="desc">
							<?php if($attribute_groups_special || ($description && strlen(trim($description)) > 32)) { ?>
								<?php if ($description && strlen(trim($description)) > 32) { ?>	
									<?php echo $description; ?>
								<?php } ?>
								
								<?php if ($attribute_groups_special) { ?>
									<br />
									<br />
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
							<?php } ?>
						</div>																		
					</div>					
		
				</div>
			<?php } ?>	
				<!--/tabs__content-->
							

							
				<!--tabs__content-->
				<div class="tabs__content characteristic_btn <?php if(!$description) { ?> active<?php } ?>">
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
						
						<?php if (!empty($reward)) { ?>
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
								<? } ?>
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
					<div class="swiper mySwiperMain">
					    <div class="swiper-wrapper">
					      	<?php if ($videos) { ?>
								<div id="video" class="swiper-slide">
									<div class="btn-ok">Дивитися відео</div>
									<div class="btn-pause">
										<svg fill="#BFEA43" height="48px" width="48px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
											<path d="M256,0C114.617,0,0,114.615,0,256s114.617,256,256,256s256-114.615,256-256S397.383,0,256,0z M224,320
												c0,8.836-7.164,16-16,16h-32c-8.836,0-16-7.164-16-16V192c0-8.836,7.164-16,16-16h32c8.836,0,16,7.164,16,16V320z M352,320
												c0,8.836-7.164,16-16,16h-32c-8.836,0-16-7.164-16-16V192c0-8.836,7.164-16,16-16h32c8.836,0,16,7.164,16,16V320z"/>
										</svg>
									</div>
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
											<video id="video-player" controlsList="nodownload" width="100%" height="700" title="<?php echo $video['title']; ?>" poster="<?php echo $video['thumb'] ?>" >
											    <source src="<?php echo  $video['video']; ?>" type="video/mp4">>
											</video>
										</div>
								 	<? } ?>
							 	</div>
					 		<? } ?>
					      	<?php if ($image) { ?>
					      		<?php unset($image); foreach ($images as $image) { ?>
									<div class="swiper-slide">
										<img src="<?php echo $image['popup']; ?>" alt="<? echo $heading_title; ?>" class="img-responsive"  style="padding:3px; border:1px solid rgba(0,0,0,0.08); margin-top:10px;" />
									</div>
								<?php } ?>
					      	<? } ?>
						</div>
						<div class="navigation">
							<p class="title"><?php echo $heading_title; ?></p>
							<div class="btn-wrap">
						    	<div class="swiper-button-prev">
						    		<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M6.5 2L1.5 7L6.5 12" stroke="#888F97" stroke-width="2.22222" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
						    	</div>
						    	<div class="swiper-button-next">
									<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M1.5 12L6.5 7L1.5 2" stroke="#888F97" stroke-width="2.22222" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</div>
					    	</div>
						</div>	
					    
				  	</div>
				  	<div thumbsSlider="" class="swiper mySwiperThumb">
					    <div class="swiper-wrapper">
					    	<?php if ($videos) { ?>
								<div id="video" class="swiper-slide">

									
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
											<video controlsList="nodownload" width="100%" height="<?php if(!IS_MOBILE_SESSION) { ?>427<?php } ?>" title="<?php echo $video['title']; ?>" poster="<?php echo $video['thumb'] ?>">
											    <source src="<?php echo  $video['video']; ?>" type="video/mp4">>
											</video>
										</div>
								 	<? } ?>
							 	</div>
					 		<? } ?>
					     	<?php if ($image) { ?>
					      		<?php unset($image); foreach ($images as $image) { ?>
									<div class="swiper-slide">
										<img src="<?php echo $image['popup']; ?>" alt="<? echo $heading_title; ?>" class="img-responsive"  style="padding:3px; border:1px solid rgba(0,0,0,0.08); margin-top:10px;" />
									</div>
								<?php } ?>
				      		<? } ?>
					    </div>
				  	</div>
			 
				</div>
				<!--/tabs__content-->
				
				
				<!--tabs__content-->
				<div class="tabs__content reviews_btn">
					<div class="reviews-col tab_reviews">
						<div id="review"></div>
					</div>
				</div>
				<!--/tabs__content-->
		</div>
		<?php if ($this->config->get('site_position') == '1') { ?>
			<?php echo $content_bottom; ?>
		<?php } ?>		
	</div>			
</section>
<!--article-->













































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
					<?php echo $delivery_info; ?>
					<? echo $displaydelivery; ?>
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
						<span id="found_cheaper-success" style="color: #1f9f9d;display: block;font-weight: 600;"></span>
					</div>
				</form>
			</div>	
		</div>
	</div>
</div>

<div id="tab_header" style="display: none;">
	
	<?php if ($thumb) { ?>
		<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" width="60"/>
	<?php } ?>
	
	<!--tabs__nav-->
	<div class="tab_header">
		<div class="review">
			<?php if ($review_status) { ?>
				<span class="rate rate-<?php echo $rating; ?>"></span>
			<?php } ?>
			<span class="reviews_count">
				<?php echo $review_count; ?> відгуків
			</span>
		</div>
		<span class="name_prod"><?php echo $heading_title; ?></span>
	</div>
	<!--/tabs__nav-->
	<div class="price_wrap">
		<div class="product__price">
			<?php  if ($need_ask_about_stock) { ?>	
				
				<?php } else if ($can_not_buy) { ?>
				
				<?php } else if (!$special) { ?>
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
							<?php echo $text_buy;?>
						</button>
				<?php } ?>
		</div>
	</div>
</div>
			
<script type="text/javascript">
	$(document).ready(function(){
	  	var text = window.location.href;// Берем ссылку
	  	var regex = /#(\w+)/gi;
	  	match = regex.exec(text);// Находим в ней все, что находится после знака #
	  	console.log('match', match)
	  	if(match && match[1] === 'photo_btn') {
	  		// setTimeout(function(){
	  		// 	$('.tabs__caption li, .tabs__content').removeClass('active');
		    // 	$('.tabs__caption li#'+match[1]).addClass('active');
		    // 	$('.tabs__content.'+match[1]).addClass('active');	
	  		// },2000)
	  		setTimeout(function(){
		    	$('.label .product__label-stock').trigger('click')
	  		},500)
	  	} else if (match && match[1] === 'reviews_btn'){
	  		setTimeout(function(){
	  			$('.tabs__caption li, .tabs__content').removeClass('active');
		    	$('.tabs__caption li#'+match[1]).addClass('active');
		    	$('.tabs__content.'+match[1]).addClass('active');	
	  		},2000)
	  	}
	  	// if(match)// Если нашел
	  	// {
	  	// 	setTimeout(function(){
	  	// 		$('.tabs__caption li, .tabs__content').removeClass('active');// Удаляем все активные табы
		//     	$('.tabs__caption li#'+match[1]).addClass('active');// Добавляем класс 'in active' к ид у которого название с ссылки совпадает с ид у таба
		//     	$('.tabs__content.'+match[1]).addClass('active');	
	  	// 	},1500)
	
	  	// }
	});
	// let titleH = document.querySelector('h1.title');
	// if(document.documentElement.clientWidth > 1400) { 
	// 	if(titleH.offsetHeight > 100){
	// 		titleH.classList.add('shadow')
	// 	}
	// } else {
	// 	if(titleH.offsetHeight > 60){
	// 		titleH.classList.add('shadow')
	// 	}
	// }
	

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

	
	
	$('.btn-buy').click( function() {
		$('.kit__btn').find(".check-offer-item").prop('checked', false);			
		$(this).prev().prop('checked', true);	
		
	});
	
	// <? if (!$ao_has_zero_price) { ?>
	// 	$('#main-add-to-cart-button').click( function() {
	// 		$(".check-offer-item").prop("checked", false);
	// 	});
	// <? } ?>
	
	
	$(document).on('click','#main-add-to-cart-button, .btn-buy, #addTo-cart-button', function() {	
		
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
			data: $('#product-cart input[type=\'text\'], #product-cart input[type=\'hidden\'], #product-cart input[type=\'radio\']:checked, #product-cart input[type=\'checkbox\']:checked, #product-cart select, #product-cart textarea, #kit-box input[type=\'checkbox\']:checked, #option-box input[type=\'checkbox\']:checked, #product-cart .price__btn-group input[type=\'checkbox\']:checked, .addTo-cart-qty input[type=\'checkbox\']:checked, #qt_product'),
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
												
		<?php if (!empty($active_coupon) && !empty($active_coupon['date_end'])){ ?>
			var note = $('#note');
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
		
		
		
		// if ($(window).width() >= '1310'){
		// 	var _hLeftBlock = $('.gallery-top').innerHeight();
		// 	<?php if (!empty($active_action)) { ?>
		// 		$('#product-cart').css('height','auto');
		// 		<?php } else { ?>
		// 		$('#product-cart').css('height',_hLeftBlock);
		// 	<?php } ?>
			
		// }		
		
		// Скрытие текста О товаре
		let _hDescripionTitle = $('.about .title').innerHeight();	
		let _hDescripionText = $('.about .desc').innerHeight();	
		let _hMainDescripion = _hDescripionTitle + _hDescripionText;
		
		let _hReviews = $('.rev_content').innerHeight();
		let _hReviewsHead = $('.reviews-col__head').innerHeight();			
		
		if( _hMainDescripion > 380){
			
			$('.desc').css({'height':'372px','overflow':'hidden'}).addClass('manufacturer-info-hide');
			
			$('.desc').parent().append('<button class="btn-open-desc"><span>Читати далі</span></button>');
			
			$('.btn-open-desc').on('click', function(){
				let test = $('body').offset().top;
				console.log(test)
				var _textBtn = $(this).find('span').text();
				$('.desc').parent().toggleClass('open-btn');
				$(this).find('span').text(_textBtn == "Читати далі" ? "Приховати" : "Читати далі");
				return false;
			});			
		}
		
		// Быстрый заказ
		function reloadAllFn(){
			initPopups();
			rebuildMaskInput();
		}

		<?php if (!$this->config->get('config_disable_fast_orders')) { ?>
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
					if((typeof VK !== 'undefined')){
						console.log('VK trigger add_to_cart');		
						VK.Retargeting.ProductEvent(<?php echo $this->config->get('config_vk_pricelist_id'); ?>, 'add_to_cart', {'products' : vkproduct, 'currency_code': '<?php echo $this->config->get('config_regional_currency'); ?>', 'total_price': '<?php echo ($special)?prepareEcommPrice($special):prepareEcommPrice($price); ?>'}); 

						console.log('VK trigger init_checkout');	
						VK.Retargeting.ProductEvent(<?php echo $this->config->get('config_vk_pricelist_id'); ?>, 'init_checkout', {'products' : vkproduct, 'currency_code': '<?php echo $this->config->get('config_regional_currency'); ?>', 'total_price': '<?php echo ($special)?prepareEcommPrice($special):prepareEcommPrice($price); ?>'}); 
					} else {
						console.log('VK is undefined');
					}
				<?php } ?>
				
				
				$('#quick_popup_container').html(data); 
				
				
				if (NProgress instanceof Object){ NProgress.done();  $(".fade").removeClass("out"); }							
				$('#quick_popup').show();
				$("#main-overlay-popup").show();
				let scrol = window.scrollY + 80;
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
	<?php } ?>
		
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

<script>
	$('.reviews_btn').on('click', function () {
		$('.tabs #reviews_btn').addClass('active').siblings().removeClass('active').closest('.tabs').find('div.tabs__content').removeClass('active').eq($('.tabs #reviews_btn').index()).addClass('active');
		var $target = $('.tabs #reviews_btn');
		$('html, body').stop().animate({
			'scrollTop': $target.offset().top - 100
			}, 500, 'swing', function () {
			// window.location.hash = $target;
		});
		
		
	});

	$('.product__label-stock').on('click', function () {
		$('.tabs #photo_btn').addClass('active').siblings().removeClass('active').closest('.tabs').find('div.tabs__content').removeClass('active').eq($('.tabs #photo_btn').index()).addClass('active');
		var $target = $('.tabs #photo_btn');
		$('html, body').stop().animate({
			'scrollTop': $target.offset().top - 100
			}, 500, 'swing', function () {
			// window.location.hash = $target;
		});				
	});

	
	/* slider tab photo and vidio */
	var photo_btn = $('#photo_btn');
	photo_btn.on('DOMSubtreeModified', function() {
	  	if (photo_btn.hasClass('active')) {

	  		var mySwiperThumb = new Swiper(".mySwiperThumb", {
		      	spaceBetween: 16,
		      	slidesPerView: 3,
		      	freeMode: false,
		      	watchSlidesProgress: false,
		      	centeredSlides: false,
				grabCursor: false,
        		allowTouchMove: false,
		    });
		    var mySwiperMain = new Swiper(".mySwiperMain", {
		      	spaceBetween: 10,
		      	centeredSlides: false,
		      	navigation: {
			        nextEl: ".swiper-button-next",
			        prevEl: ".swiper-button-prev",
		      	},
		      	thumbs: {
		        	swiper: mySwiperThumb,
		      	},
		    });

	  		setTimeout(function(){
		  		mySwiperThumb.update();
				mySwiperMain.update();
				
				mySwiperMain.updateSlides();
				mySwiperThumb.updateSlides();
	  		}, 500);
	  		<?php if ($videos) { ?>
		  		const btnOk = document.querySelector('.btn-ok');
		  		const btnPause = document.querySelector('.btn-pause');
		  		const btnNextSlide = document.querySelector('.mySwiperMain .swiper-button-next');
		  		const btnPrevSlide = document.querySelector('.mySwiperMain .swiper-button-prev');
		  		
				const wrapperVideo = document.getElementById('video-player');

				btnOk.addEventListener('click',function(){
				  wrapperVideo.play();
				  btnPause.classList.add('show')
				  this.classList.add('hidden')
				});

				btnPause.addEventListener('click',function(){
				  wrapperVideo.pause();
				  btnPause.classList.remove('show')
				  btnOk.classList.remove('hidden')
				});

				btnNextSlide.addEventListener('click',function(){
				  wrapperVideo.pause();
				  btnPause.classList.remove('show')
				  btnOk.classList.remove('hidden')
				});
				


			<?php } ?>
	  		
	  	}
	});
	/* slider tab photo and vidio end */



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
			let fixNavTab = $('#product-detail .head_product');
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
				let fixNavTab = $('#product-detail .head_product');
				
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
					grabCursor: false,
            		allowTouchMove: false,
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
					grabCursor: false,
            		allowTouchMove: false,
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
	
	
	
	$('#tab_header').appendTo('header .top-menu .tab-product-wrap');
				
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

	var tabClon = $('.tabs__caption_header').clone();
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

	$('.product__body .tabs__nav .tabs__caption li').each(function(index, el) {
		console.log('$(this)', $(this))
		let idLi = $(this).attr('id');
		$(this).click(function(event) {
			
			window.location.hash = idLi;
			$('header .top-menu .tabs__caption li').removeClass('active');
			$('header .top-menu .tabs__caption li#'+idLi).addClass('active');
			tabDesc();
		});
	});
	
	$('.product__body').bind('DOMSubtreeModified', function(){
		tabDesc();
	})
	
	$('.tabs.item-page').bind('DOMSubtreeModified', function(){
		// tabDesc();
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
					'scrollTop': qtarget.offset().top - 125.5
					}, 500, 'swing', function () {
				});
			} else {
				$('html, body').stop().animate({
					'scrollTop': qtarget.offset().top - 80.5
					}, 500, 'swing', function () {
				});
			}
		});
		
	});
	
	
	$('.product__body .head_product .tabs__caption li').each(function(index, el) {
		let idLi = $(this).attr('id');
		$(this).click(function(event) {
			console.log('idLi--->', idLi)
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
	
	
	
	// Новые табы
	
	function showRegisterProdDetail(e){
		let name  =  document.querySelector('h1.title').innerHTML,
		modal =  document.getElementById('show_register_modal');					
		
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
					slideActiveClass: 'swiper-slide-thumb-active',
					slideToClickedSlide: true,
			        centeredSlides: false,
					grabCursor: false,
            		allowTouchMove: false,
				});
						
				
				var galleryTop<?php echo count($images) + $videoInt; ?> = new Swiper('.gallery-top .swiper-container.topImages_<?php echo count($images) + $videoInt; ?>', {
					slidesPerView: 'auto',
					// loop: true,
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
	$(".buy_together").click(function() {
	    $([document.documentElement, document.body]).animate({
	        scrollTop: $("#buy_together").offset().top - 85
	    }, 150);
	});
</script>		

<script>
	function validateInput(input) {
	 	 var inputValue = input.value;
		  var pattern = /[^0-9]/g;
		  input.value = inputValue.replace(pattern, '');

	  	if (input.value < 1) {
		    input.value = 1;
	  	}
	  	if (input.value.length > 3) {
		    input.value = input.value.slice(0, 3);
	  	}
	}
	function qtVal_p(id){
		let quantity = parseInt($(id).parent().children('.qt').val());
		(quantity == 1) ? quantity = minimum: false;

	}
	function plus_p(id){
		let quantity = parseInt($(id).parent().children('.qt').val());
		let minimum = parseInt($(id).parent().children('.qt').attr('data-minimum')) || 1;
		let maximum = parseInt($(id).parent().children('.qt').attr('data-maximum')) || 999;
		quantity = quantity + minimum;
		(quantity == 0) ? quantity = minimum: false;
		(quantity == 1000) ? quantity = maximum: false
		$('#qt_product').val(quantity);

	}

	function minus_p(id){
		let quantity = parseInt($(id).parent().children('.qt').val());
		let minimum = parseInt($(id).parent().children('.qt').attr('data-minimum')) || 1;
		quantity = quantity - minimum;
		(quantity == 0) ? quantity = minimum: false;
		$('#qt_product').val(quantity);
	}
	// share btn
	document.getElementById("share-btn").addEventListener("click", function(e) {
	  e.stopPropagation(); 
	  document.getElementById("share-popup").style.display = "block";
	});

	document.addEventListener("click", function(e) {
	  if (e.target.id !== "share-btn" && !e.target.closest("#share-popup")) {
	    document.getElementById("share-popup").style.display = "none";
	  }
	});


	document.querySelectorAll(".share-btn").forEach(function(btn) {
	  	btn.addEventListener("click", function(e) {
	    	e.preventDefault();
		    var network = btn.getAttribute("data-network");
		    var url = window.location.href;
		    switch (network) {
		      case "facebook":
		        window.open("https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(url), "_blank");
		        break;
		      case "twitter":
		        window.open("https://twitter.com/intent/tweet?url=" + encodeURIComponent(url), "_blank");
		        break;
		      case "linkedin":
		        window.open("https://www.linkedin.com/shareArticle?url=" + encodeURIComponent(url), "_blank");
		        break;
		      case "email":
		        window.location.href = "mailto:?subject=" + encodeURIComponent(document.title) + "&body=" + encodeURIComponent(url);
		        break;
		    }
		    document.getElementById("share-popup").style.display = "none";
	  	});
	});
	// share btn end



</script>	



<?php echo $footer; ?>   																																			