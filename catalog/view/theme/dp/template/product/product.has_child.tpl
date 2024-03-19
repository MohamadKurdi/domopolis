<?php echo $header; ?>

<?php if(isset($show_button) && $show_button): ?>

<?php endif; ?>


<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>

<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" class="colection-content colection-child-content">
	
	<?php echo $content_top; ?>
	<?php if ($hb_snippets_prod_enable == '1'){ ?>
		<script type="application/ld+json">
			{
				"@context": "http://schema.org/",
				"@type": "Product",
				"name": "<?php echo $heading_title; ?>",
				<?php if ($thumb) { ?>
					"image": "<?php echo $thumb; ?>"
				<?php } ?>
				,"description": "<?php $desc = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", htmlentities(strip_tags($description))); echo preg_replace('/\s{2,}/', ' ', trim($desc));?>"
				<?php if ($manufacturer) { ?>
					,"brand": {
						"@type": "Thing",
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
					, "offers":{"@type": "Offer",
						"priceCurrency": "<?php echo $currencycode; ?>",
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
						<?php if ($stockqty > 0) { ?>
							,"availability": "http://schema.org/InStock"
						<?php } ?>
					}
				<?php } ?>
			}
		</script>
	<?php } ?>

	<!--slider-box-->
	<div class="wrap">
		<!--slider-box-->
			<div class="slider-box clrfix">
				<!--gallery-thumbs-->
				<?php if (!$images) { ?>
					<div class="gallery-thumbs">
							<div class="swiper-container">
								<!--swiper-wrapper-->
								<div class="swiper-wrapper"></div>
						</div>
					</div>
				<?php } ?>
				<?php if ($thumb || $images) { ?>
					<?php $i=1; if ($images) { ?>
						<div class="gallery-thumbs">
							<div class="swiper-container thumbImages_<?php echo count($images); ?>">
								<!--swiper-wrapper-->
								<div class="swiper-wrapper">
									<!--swiper-slide-->
									
									
										<?php if ($thumb) { ?>
											<div class="swiper-slide">
												<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>"/>
											</div>
										<?php } ?>
										
										
										<?php $i = 1;
											if ($images) { ?>
											<?php foreach ($images as $image) { ?>
												<?php if (isset($image['thumb'])) { ?>
													<div class="swiper-slide">
														<img src="<?php echo $image['middle']; ?>"
														alt="<?php echo $heading_title; ?>">
													</div>
												<?php } ?>
												<?php $i++;
												} ?>
										<?php } ?>
									
									<!--/swiper-slide-->
								</div>
								<!--/swiper-wrapper-->
							</div>
							<!-- Add Arrows -->
							<div class="swiper-button-next"></div>
							<div class="swiper-button-prev"></div>
						</div>
					<?php } ?>
				<?php } ?>
				<!--/gallery-thumbs-->
				
				<!--gallery-top-->
				<div class="gallery-top">
					<div class="swiper-container topImages_<?php echo count($images); ?>">
						<!--swiper-wrapper-->
						<div class="swiper-wrapper">
							<!--swiper-slide-->
							<?php if ($thumb || $images) { ?>
								<?php if (($thumb) && ($smallimg) && ($this->config->get('product_zoom') == '1')) { ?>
									<div class="swiper-slide">
										<img src="<?php echo $popup; ?>" alt="<?php echo $heading_title; ?>">
									</div>
								<?php } ?>
								
								<?php $i = 1;
									if ($images) { ?>
									<?php foreach ($images as $image) { ?>
										<?php if (isset($image['thumb'])) { ?>
											<div class="swiper-slide">
												<img src="<?php echo $image['popup']; ?>"
												title="<?php echo $heading_title; ?>"
												alt="<?php echo $heading_title; ?>">
											</div>
										<?php } ?>
										<?php $i++;
										} ?>
								<?php } ?>
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
		<!--/slider-box-->
		
		<!--item-column-->
		<div class="item-column colaction_description">
			
			<a href="<?php echo $manufacturer_href; ?>" class="manufacturer_logo"><img src="<?php echo $manufacturers_img_big_thumb; ?>" alt="image-brand"></a>

			<?php if (!empty($heading_title)) { ?>
				<h1 class="title text-center"><?php echo $heading_title; ?></h1>
			<?php } ?>

			<div class="details-brand" style="margin-bottom: 25px;">
				<p>
					<span><?=$text_code ?>: </span>
					<span><?php echo $product_id; ?></span>
				</p>
				<?php if ($manufacturer) { ?>
					<p>
						<span>Бренд:</span>
						<a href="<?php echo $manufacturers; ?>" title="<?php echo $manufacturer; ?>" style="color: #51A881;font-weight: bold;">
							<?php echo $manufacturer; ?>							
						</a>					
					</p>
				<?php } ?>
				<?php if (isset($location) && mb_strlen(trim($location), 'UTF-8') > 0) { ?>
					<p>
						<span><?=$text_country ?>:</span>
						<span><?php echo $location; ?></span>
					</p>
				<?php } ?>							
			</div>
			<div >
				<div style="margin-bottom: 30px;     padding: 8px;   background-color: #7CC04B;  color: #FFF; font-size:14px;">
					<i class="fas fa-info-circle"></i> Составьте комплект в соответствии с вашими пожеланиями: просто введите нужные количества и нажмите «добавить в корзину» ниже
				</div>
			</div>
			<?php if ($description) { ?>
				<div class="manufacturer-info"><?php echo $description; ?></div>
			<?php } ?>
		</div>
		<script>
			    $(document).ready(function () {
			    	if(document.documentElement.clientWidth > 1600) {  
			        
						let _hDescripion = $('.manufacturer-info').innerHeight();

						if( _hDescripion > 300){

							$('.manufacturer-info').css({'height':'230px','overflow':'hidden'}).addClass('manufacturer-info-hide');

							$('.manufacturer-info').parent().append('<button class="btn-open-desc"><i class="fas fa-angle-down"></button>');

							$('.btn-open-desc').on('click', function(){
								$('.manufacturer-info').parent().toggleClass('open-btn');
								return false;
							});

						}
					};

    			});
		</script>
		<!--/item-column-->
	</div>		

	<section class="catalog ">
		<div class="wrap">
			<div class="catalog-inner">
				<div class="catalog__content product-grid ">
					<!--product__grid-->
						<div class="product__grid product__grid__colection aside-none" id="product__grid">
							
							<!--product__item-->
							<? foreach ($child_products as $product) { ?>
								<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single_collection_child.tpl')); ?>
							<?php } ?>
							<!--/product__item-->
							
						</div>
						<output id="output" class="hidden"></output>
						<div>
							<div style="float:right; text-align:right; padding-top:20px;">							
								<div style="font-size:24px;" id="chprice_result_general">-</div>							
							</div>
							<div style="float:right; text-align:right; padding-top:20px;">
								<div style="font-size:24px;">Общая сумма</div>
							</div>
						</div>
						<div>
							<div class="col-md-24 col-sm-24 col-xs-24 cart" style="text-align:right;">						
								<input type="button" value="Добавить выбранное в корзину" class="button" onclick="addChildCart()" style="padding-top:6px; padding-bottom:6px;"/>
								<script>
									function addChildCart(){
										var promises = [];
										$('input.htop').each(function(){
											var elem = $(this);
											
											var q = elem.val();
											var pid = elem.attr('data-product-id');
											
											if (q > 0){
												promises.push($.ajax({
													url: '/index.php?route=checkout/cart/add',
													type: 'post',
													cache: false,
													data: 'product_id=' + pid + '&quantity=' + q ,
													dataType: 'json',
													error: function(e){
														console.log(e);
													},
											        success: function(json) {
														if ((typeof fbq !== 'undefined')){
															fbq('track', 'AddToCart');
														}
														
														if (json['total']) { 
															$('#header-small-cart').load('/index.php?route=module/cart #header-small-cart');
															$('#popup-cart').load('/index.php?route=common/popupcart', function(){ $('#popup-cart-trigger').click(); });
														}
													}
																								
												}));
											}
											
										});	
										
										$.when.apply($, promises).then(function() {
											$('#showcart').trigger('click');
											$('#cart').load('index.php?route=module/cart #cart > *');
										});
										
									}						
								</script>
							</div>
						</div>
					<!--/product__grid-->
				</div>
			</div>
		</div>
	</section>

			






<div class="wrap">




		

		<div class="row">
			
			
			<div class="<?php if ($youtubes) { ?>col-md-12<? } else { ?>col-md-24<? } ?>">
				<div class="kitchen-review">
					<div id="tabs" class="htabs сol-md-12">
						
						<?php if ($attribute_groups_special) { ?>
							<a href="#tab-special"><?=$text_details ?></a>
						<?php } ?>
						
						<?php if ($description && strlen(trim($description)) > 32) { ?>
							<a href="#tab-description"><?php echo $tab_description; ?></a>
						<?php } ?>
						
						<?php if ($image) { ?>
							<a href="#tab-image">Фотографии</a>
						<?php } ?>
						
						<?php if ($review_status) { ?>
							<a href="#tab-review"><?php echo $tab_review; ?></a>
						<?php } ?>
						
						<?php if ($delivery_info) { ?>
							<a href="#delivery_info">Доставка</a>
						<?php } ?>
						
						<?php if (($this->config->get('status_product_tab') == '1') && (isset($this->document->cusom_p_tab)) ){ ?>
							<a href="#tab-custom"><?php echo htmlspecialchars_decode( $this->document->cusom_p_tab[$this->config->get('config_language_id')]['product_title_tab'], ENT_QUOTES ); ?></a>
						<?php } ?>
					</div>
					
					<?php if ($attribute_groups_special) { ?>
						<div id="tab-special" class="tab-content tabscroll" <?php if ($youtubes) { ?> style="min-height:355px" <? } else { ?>  style="min-height:150px!important;" <? } ?> >
							<div id="tabscroll">
								<div class="kitchen-review">
									<div id="tabscroll-two">
										<div class="kitchen-block">
											<ul>
												<?php foreach ($attribute_groups_special as $attribute_group) { ?>
													<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
														<li><i class="fa fa-circle"></i> <?php echo $attribute['text']; ?></li>
													<? } ?>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
					
					<?php if ($description && strlen(trim($description)) > 32) { ?>
						<div id="tab-description" class="tab-content tabscroll"  <?php if ($youtubes) { ?> style="min-height:355px" <? } else { ?>  style="min-height:150px!important;" <? } ?> >
							<div id="tabscroll"><?php echo $description; ?></div>
						</div>
					<? } ?>
					
					<?php if ($delivery_info) { ?>
						<div id="delivery_info" class="tab-content" style="background-color: #f9f9f9;"><?php echo $delivery_info; ?></div>
					<?php } ?>
					
					<?php if ($image) { ?>
						<div id="tab-image" class="tab-image" style="background-color: #f9f9f9; text-align:center;">
							
							<div>
								<img src="<?php echo $popup; ?>" alt="<? echo $heading_title; ?>" class="img-responsive" style="padding:3px; border:1px solid rgba(0,0,0,0.08); margin-top:10px;" />
							</div>
							
							<?php unset($image); foreach ($images as $image) { ?>
								<div>
									<img src="<?php echo $image['popup']; ?>" alt="<? echo $heading_title; ?>" class="img-responsive"  style="padding:3px; border:1px solid rgba(0,0,0,0.08); margin-top:10px;" />
								</div>
							<?php } ?>
							
							
						</div>
					<? } ?>
					
					<?php if ($review_status) { ?>
						<div id="tab-review" class="tab-content">
							<form id="product-review" method="post" enctype="multipart/form-data">
								<div class="row review-new">
									<div class="col-md-24"><div id="review"></div></div>
									<div class="<?php if ($attribute_groups_special) { ?> col-md-12 <?php } else if ($youtubes) { ?> col-md-24 <?php } else {?> col-md-6 <?php } ?>">
										
										<div class="form-group">
											<label id="review-title"><?php echo $entry_name; ?></label>
											<input type="text" name="name" value="" class="form-control">
										</div>
										<div class="form-group">
											<label>Поставьте оценку</label>
											
											<div class="btn-group btn-group-md pull-right" data-toggle="buttons">
												<label class="btn btn-success">
													<input type="radio" name="rating"  value="1" /> 1
												</label>
												<label class="btn btn-success">
													<input type="radio" name="rating"  value="2" /> 2
												</label>
												<label class="btn btn-success">
													<input type="radio" name="rating"  value="3" /> 3
												</label>
												<label class="btn btn-success">
													<input type="radio" name="rating"  value="4" /> 4
												</label>
												<label class="btn btn-success active">
													<input type="radio" name="rating"  value="5" checked="checked" /> 5
												</label>
											</div>
										</div>
										<div class="form-group">
											<label>Пожалуйста, введите код с картинки, для подтверждения что вы не робот</label>
											
											<div class="row">
												<div class="col-md-10"><img src="index.php?route=product/product/captcha" alt="" id="captcha" class="img-responsive" style="width:120px;" /></div>
												<div class="col-md-14"><input type="text" name="captcha" value="" class="form-control"/></div>
											</div>
											
											
										</div>
										<div class="form-group">
											<label>Добавьте фото к отзыву (*.jpeg, *.png)</label>
											
											<div class="row">
												<div class="col-md-24"><input type="file" name="add-review-image" accept="image/jpeg,image/png,image/jpg" /></div>
											</div>
											
											
										</div>
										<div class="form-group">
											<div class="left"><a id="button-review" class="btn btn-default"><?php echo $button_continue; ?></a></div>
										</div>
									</div>
									
									<div class="<?php if ($attribute_groups_special) { ?> col-md-12 <?php } else {?> col-md-18 <?php } ?>">
										<div class="form-group" style="border-bottom:none;">
											<? if ($this->config->get('config_review_good')) { ?>
												<div class="row">
													<div class="col-md-12">
														<label><i class="fa fa-thumbs-o-up"></i> Достоинства:</label>
														<textarea name="good" class="form-control" rows="3"></textarea>
													</div>
													<div class="col-md-12">
														<label><i class="fa fa-thumbs-o-down"></i>
														Недостатки:</label>
														<textarea name="bads" class="form-control" rows="3"></textarea>
													</div>
												</div>
											<? } ?>
											<div class="row">
												<div class="col-md-24">
													<label><?php echo $entry_review; ?></label>
													<textarea name="text" class="form-control" rows="4"></textarea>
													<span style="font-size: 11px;"><?php echo $text_note; ?></span>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						<?php } ?>
						
						<?php if (($this->config->get('status_product_tab') == '1') && (isset($this->document->cusom_p_tab)) ){ ?>
							<div id="tab-custom" class="tab-content"><?php echo htmlspecialchars_decode( $this->document->cusom_p_tab[$this->config->get('config_language_id')]['product_text_tab'], ENT_QUOTES ); ?></div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php if ($youtubes) { ?>
				<div class="col-md-12">
					<div class="kitchen-review">
						<div id="tabs" class="htabs">
							<?php if($youtubes) { ?>
								<span><?=$text_video_obzor ?></span>
							<?php } ?>
						</div>
						
					</div>
				<? } ?>
				
				<?php if ($youtubes) { ?>
					<div id="tab-youtubes" class="tab-content tabscroll">
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
				</div>
			<? } ?>
		</div>
	</div>
</div>

<?php if (isset($collection) && $collection) { ?>
	<div class="box">
		<div class="box-heading"><a href="<?php echo $collection_link; ?>"><?=$text_all_collection ?> <? echo isset($manufacturer)?$manufacturer.'&nbsp;':''; ?><? echo $collection_name; ?></a></div>
		<div class="box-content">
			<div class="product-list in-product">
				<?php foreach ($collection as $product) { ?>
					
					<div class="item">
						<?php if ($product['thumb']) { ?>
							<div class="left">
								<div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" ></a></div>
							</div>
						<?php } ?>
						
						<div class="centr">
							<div class="name">
							<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
							<div class="description"><?php echo $product['description']; ?></div>
						</div>
						
						<div class="right">
							<?php if ($product['price']) { ?>
								<div class="price">
									<?php if (!$product['special']) { ?>
										<?php echo $product['price']; ?>
										<?php } else { ?>
										
										<span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
										<?php if (isset($product['saving'])) { ?>
											<div  class="savemoney">- <?php echo $product['saving']; ?>%</div>
										<?php } ?>
									<?php } ?>
								</div>
							<?php } ?>
							
							<div class="cart">
								<? if ($product['can_not_buy']) { ?>
									<span style="color:#CCC; font-size:22px; font-weight:700;"><? echo $product['stock_status'] ?></span>
									<? } else { ?>
									
									<div class="button-orders">
										<div class="collection-counter">
											<div class="checker">
												<?php if ($price) { ?>
													<table class="gty cart-impulse">
														<tbody>
															<tr>
																<td><input type="button" class="decrease decrease_collection" data-product-id="<?php echo $product['product_id']; ?>" id="decrease_<?php echo $product['product_id']; ?>" value="▼"></td>
																<td><input type="text" name="quantity_<?php echo $product['product_id']; ?>" class="htop" id="htop_<?php echo $product['product_id']; ?>" size="2" value="<?php echo $product['minimum']; ?>"></td>
																<td>
																	<input type="button" class="increase increase_collection" data-product-id="<?php echo $product['product_id']; ?>" id="increase_<?php echo $product['product_id']; ?>" value="▲">
																	<input type="hidden" name="product_id" size="4" value="<?php echo $product_id; ?>">
																</td>
															</tr>
														</tbody>
													</table>
												<?php } ?>
											</div>
											
										</div>
										<input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>', parseInt($('#htop_<?php echo $product['product_id']; ?>').val()));" class="button" />
										<?php if ($product['minimum'] > 1) { ?>
											<div class="minimum"><?php echo $product['text_minimum']; ?></div>
										<?php } ?>
									</div>
								<? } ?>
								
							</div>
							<?php if ($this->config->get('config_review_status')) { ?>
								<div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				
			</div>
		
	</div>
	<div class="clear"></div>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#findcheaper_toggle').on('click', function () {
				var cboxOptions = {
					closeButton: false,
					scrolling: false,
					href: 'index.php?route=module/cheaper&pid=<? echo $product_id ?>',
					width: '95%',
					maxWidth: '675px'
				}
				void jQuery.colorbox(cboxOptions);
				$(window).resize(function(){
					$.colorbox.resize({
						width: window.innerWidth > parseInt(cboxOptions.maxWidth) ? cboxOptions.maxWidth : cboxOptions.width,
					});
				});
			});
			
		});
	</script>
	
<? } ?>
<script type="text/javascript">
	function spin(elm, vl) {
		
		console.log(parseInt(elm.val(), 10));
		
		if (parseInt(elm.val(), 10) >= 0){
			elm.val(parseInt(elm.val(), 10) + vl);
		}
		if (parseInt(elm.val(), 10) <= 0){
			elm.val('0');
		}
	}
	$(document).ready(function(){
	
		function recount_total(){
			var total_price = 0;
			$('input.quantity_child').each(function(){
				total_price = total_price + (parseInt($(this).attr('data-one-price')) * parseInt($(this).val()));
			});
			
			$('#chprice_result_general').load('index.php?route=product/product/getFormattedPriceAjax&q=1&num='+total_price);
		
		}
		
	
		$('input.quantity_child').change(function(){
			console.log($('#chtop_'+$(this).attr('data-product-id')));
			$('#chprice_result_'+$(this).attr('data-product-id')).load('index.php?route=product/product/getFormattedPriceAjax&q='+$(this).val()+'&num='+$(this).attr('data-one-price'), function(){ recount_total(); });			
		});
		
		$('.increase_collection_child').click(function() {
			console.log($('#chtop_'+$(this).attr('data-product-id')));
			spin($('#chtop_'+$(this).attr('data-product-id')), 1);
			$('#chtop_'+$(this).attr('data-product-id')).trigger('change');
		//	$('#chprice_result_'+$(this).attr('data-product-id')).load('index.php?route=product/product/getFormattedPriceAjax&q='+$('#chtop_'+$(this).attr('data-product-id')).val()+'&num='+$('#chtop_'+$(this).attr('data-product-id')).attr('data-one-price'));
		});
		$('.decrease_collection_child').click(function() {
			console.log($('#chtop_'+$(this).attr('data-product-id')));
			spin($('#chtop_'+$(this).attr('data-product-id')), -1);
			$('#chtop_'+$(this).attr('data-product-id')).trigger('change');
		//	$('#chprice_result_'+$(this).attr('data-product-id')).load('index.php?route=product/product/getFormattedPriceAjax&q='+$('#chtop_'+$(this).attr('data-product-id')).val()+'&num='+$('#chtop_'+$(this).attr('data-product-id')).attr('data-one-price'));
		});
	});
	$(document).ready(function(){
		$('input.increase_collection').click(function() {
			console.log($('#htop_'+$(this).attr('data-product-id')));
			spin($('#htop_'+$(this).attr('data-product-id')), 1);
		});
		$('input.decrease_collection').click(function() {
			spin($('#htop_'+$(this).attr('data-product-id')), -1);
		});
	});
</script>
<style>
	.zoomWindow{
	left:600px!important;
	top:-10px!important;
	border: 4px solid rgba(255, 255, 255, .0)!important;
	}
	
	@media (max-width: 1160px) {
	.zoomWindow , .zoomLens{
	
	opacity: 0!important;
	}
	}
</style>

<!--end Related Products-->

<?php if ($tags) { ?>
	<div class="tags"><b><?php echo $text_tags; ?></b>
		<?php for ($i = 0; $i < count($tags); $i++) { ?>
			<?php if ($i < (count($tags) - 1)) { ?>
				<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
				<?php } else { ?>
				<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
			<?php } ?>
		<?php } ?>
	</div>
<?php } ?>
<?php echo $content_bottom; ?>
</div>
</div>

<script type="text/javascript">
	
	$('.main-product a').click(function(){
		return false;
	});
	
	$('.btn-buy').click( function() {
		$('.flex-container').find(".check-offer-item").prop('checked', false);
		$(this).closest('.flex-container').find(".check-offer-item").prop('checked', true);
	});
	
	<? if (!$ao_has_zero_price) { ?>
		$('#button-cart').click( function() {
			$(".check-offer-item").prop("checked", false);
		});
	<? } ?>
	
	<!--
	$('#button-cart, .btn-buy').bind('click', function() {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
			dataType: 'json',
			error:function(json) {
				console.log(json);
			},
			success: function(json) {
				$('.success, .warning, .attention, information, .error').remove();
				
				if (json['error']) {
					if (json['error']['option']) {
						for (i in json['error']['option']) {
							$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
						}
					}
					
					if (json['error']['message']) {
						alert(json['error']['message']);
					}
				}
				
				if ($.browser.msie && ($.browser.version == 7 || $.browser.version == 8)) {
					if (json['success']) {
						$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
						$('.success').fadeIn('slow');
						
						$('#cart-total').html(json['total']);
						
						$('html, body').animate({ scrollTop: 0 }, 'slow');
					}
					} else {
					if (json['success']) {
						$('#showcart').trigger('click');
						$('#cart-total').html(json['total']).addClass('cart-full');
						$('#cart').load('index.php?route=module/cart #cart > *');
					}
				}
				
				if (json['success']) {
					/*
						$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
						
						$('.success').fadeIn('slow');
						
						$('#cart-total').html(json['total']).addClass('cart-full');
					*/
					
					<!--$('html, body').animate({ scrollTop: 0 }, 'slow'); -->
				}
			}
		});
	});
//--></script>
<?php if ($options) { ?>
<?php } ?>
<script type="text/javascript"><!--

	
	<!--$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');-->
	$.ajax({
		url: 'index.php?route=product/product/review&product_id=<?php echo $product_id; ?>',
		type: 'get',
		success: function(data) {
			$('#review').html(data);
			$("a.colorbox").click(function(e){
				e.preventDefault();
				var user_foto = $(this).attr('href');
				$.colorbox({
					innerWidth: 600,
					innerHeight: false,
					href:user_foto,
					//html:false,
					//onLoad:false,
					//closeButton: false,
				});
				$('#cboxClose').css({"display":"none"});
				$('#cboxMiddleRight').css({"display":"none"});
			});
		}
	});
	
	$('#button-review').bind('click', function() {
		
		var formData = new FormData($('form#product-review')[0]);
		
		$.ajax({
			url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
			type: 'post',
			dataType: 'json',
			data: formData,
			async: false,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$('.success, .warning').remove();
				$('#button-review').attr('disabled', true);
				$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
			complete: function() {
				$('#button-review').attr('disabled', false);
				$('.attention').remove();
			},
			success: function(data) {
				if (data['error']) {
					$('#review-title').after('<div class="warning">' + data['error'] + '</div>');
				}
				
				if (data['success']) {
					$('#review-title').after('<div class="success">' + data['success'] + '</div>');
					
					$('input[name=\'name\']').val('');
					$('textarea[name=\'text\']').val('');
					$('input[name=\'rating\']:checked').attr('checked', '');
					$('input[name=\'captcha\']').val('');
					$('textarea[name=\'good\']').val('');
					$('textarea[name=\'bads\']').val('');
					$('input[name=\'addimage\']').val('');
				}
			}
		});
	});
//--></script>
<script type="text/javascript"><!--
	$('#tabs a').tabs();
//--></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
	$(document).ready(function() {
		if ($.browser.msie && $.browser.version == 'uk') {
			$('.date, .datetime, .time').bgIframe();
		}
		
		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		$('.datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:m'
		});
		$('.time').timepicker({timeFormat: 'h:m'});
		
		
		$('#quick-order-btn').on('click', function (e) {
			e.preventDefault();
			$.post('index.php?route=checkout/quickorder/loadtemplate',  $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'), function (data) {
				$(data).insertAfter('#quick-order-btn');
				// $('.js-fast-byu')
			})
		});
	});
//--></script>
<script type="text/javascript">
	var google_tag_params = {
		<? foreach ($google_tag_params as $name => $value) { ?>
			<? if ($name != 'dynx_totalvalue' && $name != 'ecomm_totalvalue') { ?>
				<? echo $name; ?>:"<? echo $value; ?>",
				<? } else { ?>
				<? echo $name; ?>:<? echo $value; ?>,
			<? } ?>
		<? } ?>
	};
</script>
<?php if($is_set) { ?>
	<script type="text/javascript">
		// alert('<?php echo $count_set; ?>');
		// $('.product-info .right .price, .product-info .right .cart').remove();
		// $('.product-info .right .description').after('<div id="list-products-in-set-product-page"><div class="load-image"><img src="image/set-loader-min.gif" alt=""></div></div>');
		$('div.kitchen-review').append('<div id="list-products-in-set-product-page"><div class="load-image"><img src="image/set-loader-min.gif" alt=""></div></div>');
		$('#list-products-in-set-product-page').load(
		'index.php?route=module/set/productload&set_id=<?php echo $is_set; ?>',
		function () {
			$('#button-cart').on('click', function () {
				// alert('<?php echo $is_set ?>');
				// AddToCartSet('<?php echo $is_set ?>');
				// return false;
			});
			// alert($('base').attr('href') + 'index.php?route=module/set/productload&set_id=<?php echo $is_set; ?>');
			$('.load-image').hide();
		}
		);
	</script>
<?php } ?>

<?php if($count_set){ ?>
	<?php if($set_place=='before_tabs') { ?>
		<script type="text/javascript">
			$('.product-info').after('<div id="set-place"><div class="load-image"><img src="image/set-loader.gif" alt=""></div></div>');
			$('#set-place').load(
			'index.php?route=module/set/setsload&product_id=<?php echo $product_id; ?>',
			function () {
				$('.load-image').hide();
			}
			);
		</script>
		<?php } elseif($set_place=='in_tabs'){ ?>
		<script type="text/javascript">
			$('#tabs a:first-child').after('<a id="link-to-sets" href="#tab-sets"><img src="image/set-loader-min.gif" alt=""></a>');
			$('#tab-description').after('<div id="tab-sets" class="tab-content"></div>');
			$('#tab-sets').load(
			'index.php?route=module/set/setsload&product_id=<?php echo $product_id; ?>',
			function () {
				$('#tabs a').tabs();
				$('.load-image').hide();
				$('#link-to-sets').text('<?php echo $tab_sets; ?>');
			}
			);
			
		</script>
	<?php } ?>
<?php } ?>
<script type="text/javascript">
	dataLayer.push({
		"event":"ProductPageView",
		"ecommerce": {
			"currencyCode":"<? echo $google_ecommerce_info['currency']; ?>",
			"detail": {
				"products": [{
					"id":"<? echo $google_ecommerce_info['product_id']; ?>",
					"name":"<? echo $google_ecommerce_info['name']; ?>",
					"price":"<? echo $google_ecommerce_info['price']; ?>",
					"brand":"<? echo $google_ecommerce_info['brand']; ?>",
					"category":"<? echo $google_ecommerce_info['category']; ?>"
				}]
			}
		}
	});
</script>


















<!-- new -->
<script>
	
	function hDescripionMob() {
		let _hDescripion = $('.manufacturer-info').innerHeight();
		if( _hDescripion > 150){

			$('.colaction_description').css({'height':'auto'})

			$('.manufacturer-info').css({'height':'70px','overflow':'hidden'}).addClass('manufacturer-info-hide');

			$('.manufacturer-info').parent().append('<button class="btn-open-desc"><i class="fas fa-angle-down"></button>');

			$('.btn-open-desc').on('click', function(){
				$('.manufacturer-info').parent().toggleClass('open-btn');
				return false;
			});	
		
		}
	}

	function hDescripionDesc() {
		let _hDescripion = $('.manufacturer-info').innerHeight();
		if( _hDescripion > 150){

			$('.colaction_description').css({'height':'auto'})

			$('.manufacturer-info').css({'height':'135px','overflow':'hidden'}).addClass('manufacturer-info-hide');

			$('.manufacturer-info').parent().append('<button class="btn-open-desc"><i class="fas fa-angle-down"></button>');

			$('.btn-open-desc').on('click', function(){
				$('.manufacturer-info').parent().toggleClass('open-btn');
				return false;
			});
			let hLeftColumn = $('.colection-content .slider-box').height();
    		$('.colection-content .colaction_description').css('height', hLeftColumn);	
		
		} else {

		  	let hLeftColumn = $('.colection-content .slider-box').height();
    		$('.colection-content .colaction_description').css('height', hLeftColumn);
		}
	}




	
    $(document).ready(function () {
        if(document.documentElement.clientWidth < 768) {

			hDescripionMob();
			
    	} else if(document.documentElement.clientWidth < 1600) {  	        

			hDescripionDesc();
		};

    });

	$(window).resize(function() {
		if(document.documentElement.clientWidth < 768) {

			hDescripionMob();

    	} else if (document.documentElement.clientWidth < 1600) {  	        

			hDescripionDesc();
		};
    });






		if ($(".gallery-top")[0]) {
		// Slider top product
		var galleryThumbs<?php echo count($images); ?> = new Swiper('.gallery-thumbs .swiper-container.thumbImages_<?php echo count($images); ?>', {
		 	centeredSlides: false,
			 	<?php if(count($images) >= 5)  {?>
					slidesPerView: 4,
					loop: true,
					loopedSlides: 4,
					height: 400,
					breakpoints: {
						1280: {
						  	loopedSlides: 5,
						},
					},
				<?php } elseif(count($images) == 4) { ?>
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
				<?php } elseif(count($images) == 3) { ?>
					slidesPerView: 4,
					height: 400,
				<?php } elseif(count($images) == 2) { ?>
					slidesPerView: 3,
					height: 300,
				<?php } elseif(count($images) == 1) { ?>
					slidesPerView: 2,
					height: 200,
				<?php } ?>


			touchRatio: 0.2,
			slideToClickedSlide: true,
			slideActiveClass: 'swiper-slide-thumb-active',
			direction: 'vertical',
			navigation: {
				nextEl: '.gallery-thumbs .swiper-button-next',
				prevEl: '.gallery-thumbs .swiper-button-prev',
			},
			
		});
		
		var galleryTop<?php echo count($images); ?> = new Swiper('.gallery-top .swiper-container.topImages_<?php echo count($images); ?>', {
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
		  	<?php if(count($images) < 5)  {?>
	  			thumbs: {
				    swiper: galleryThumbs<?php echo count($images); ?>
				}
		  	<?php } ?>	
		});
		<?php if(count($images) >= 5)  {?>
			galleryTop<?php echo count($images); ?>.controller.control = galleryThumbs<?php echo count($images); ?>;
			galleryThumbs<?php echo count($images); ?>.controller.control = galleryTop<?php echo count($images); ?>;
		<?php } ?>	  
	}
</script>

<?php echo $footer; ?>		
