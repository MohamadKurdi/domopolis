<?php
	$this->language->load('module/mattimeotheme');
	$button_quick = $this->language->get('entry_quickview');
	
?>
<?php echo $header; ?>
<link rel="stylesheet" href="catalog/view/theme/kp/css/sumoselect.css" />
	<script src="catalog/view/theme/kp/js/sumoselect.min.js"></script> 
<?php include(dirname(__FILE__).'/../structured/breadcrumbs.tpl'); ?>
<?php echo $column_right; ?>
<section id="content-manufacturer"  style="margin-top: 0;">
 <?php if (isset($category['manufacturer'][$i])) { ?>
 <img src="<? echo $category['manufacturer'][$i]['image']; ?>" title="<?php echo $category['manufacturer'][$i]['name']; ?>" alt="<?php echo $category['manufacturer'][$i]['name']; ?>" />
<?php } ?>
	<div class="wrap">
		<?php echo $content_top; ?>
		<!--catalog-inner-->
		<div class="catalog-inner">
			<!--aside-->
			
				<!-- <div class="aside"> -->
					<!-- <div class="filter-btn" data-name-btn="Показать фильтр" data-name-btn-opened="Скрыть фильтр"></div> -->
					<!--filter-->
					<!-- <div class="filter"> -->
						<!--accordion-->
						<!-- <div class="accordion"> -->
							<!--accordion__item-->
							<!-- <?php echo $column_left; ?> -->
							<!--/accordion__item-->
						<!-- </div>
					</div> -->
					<!--/filter-->
				<!-- </div> -->
			<!--/aside-->
		
		<!--catalog__content-->
				
				
				
	
    	

		<script type="application/ld+json">
			{
				"@context": "http://schema.org",
				"@type": "BreadcrumbList",
				"itemListElement": [<?php $bi = 1; foreach ($breadcrumbs as $breadcrumb) { ?>{
					"@type": "ListItem",
					"position": <?php echo $bi; ?>,
					"item": {
						"@id": "<?php echo $breadcrumb['href']; ?>",
						"name": "<?php echo $breadcrumb['text']; ?>"
					}
				}<?php if($bi != count($breadcrumbs)){ ?>,<?php } ?><?php $bi++; } ?>]
			}	  
		</script>

	    <!-- Main container -->
	    <!-- Head block -->
	    <div class="head-block-content">
	        <div class="brands-mini-main">
				<? if (!$show_goods) { ?>
					<a href="<?php echo $this_href; ?>?goods=all" class="all-price">
						<i class="fa fa-list"></i> <?=$text_show_all_products ?> <?php echo $heading_title; ?></a>
				<? } ?>
	            <? if( $show_collection_link ) { ?>
					<a href="<?php echo $this_href; ?>?collections=all" class="all-price" >
					<i class="fa fa-list"></i> <? echo $text_brands_all_collections; ?><?php echo $heading_title; ?></a>
				<? } ?>
			</div>
		</div>


		<? if (!$additional_content && $m_categories) { ?>
			<div class="alphabet-colection test1">
				<h2 class="title center"><?=$text_all_collection ?> <?php echo $heading_title; ?></h2>
				<ul class="alphabet-inline">
					<?php foreach ($m_categories as $m_category) { ?>
						<li>
							<a href="<?php echo $this_href; ?><? if ($show_collections) { ?>?collections=all<? } ?>#<?php echo $m_category['name']; ?>">
								<?php echo $m_category['name']; ?>									
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		<? } ?>
	
		<? if ($additional_content) { ?>	
			<? if ($virtual_collections) { ?>
				<div class="category-list collection-list test2" style="margin-bottom: 35px">							
						
						<?php $i=1; foreach ($virtual_collections as $collection) { ?>
							<div class="manufacturer_colection-item">
								<div class="collection-block-container">

									<a href="<? echo $collection['href']; ?>"  title="<?php echo $collection['name']; ?>">
										<div class="img-collection">
										<?php if (!$collection['thumb']) { ?>
											<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/images/noimage.jpg" />
										<?php } ?>
										
										<?php if ($collection['thumb']) { ?>
											<img src="<?php echo $collection['thumb']; ?>" alt="<?php echo $collection['thumb']; ?>" />
										<?php } ?>
										</div>
										<span><?php echo $collection['name']; ?></span>									
									</a>

								</div>
								
							</div>
						<? } ?>
						
				</div>				
			<? } ?>
		
		
		
			<? foreach ($additional_content as $content_block) { ?>
			<? if ($content_block['type'] == 'products') { ?>
				
				<div class="slider-manufacturer-product">
					<div class="product__slider-head viewed">
						<h4 class="title center"><? echo $content_block['title']; ?></h4>
					</div>
							<div id="acontent_products_scroll_<? echo $content_block['manufacturer_page_content_id']; ?>" class="product-slider">
								<div class="swiper-container">
									<div class="swiper-wrapper">
						            <?php foreach ($content_block['product_array'] as $product) { ?>
						              <div class="swiper-slide">
						                  <?php include(dirname(__FILE__).'/../structured/product_single.tpl'); ?>
						               
						              </div>
						            <?php } ?>
						          	</div>
									<div class="swiper-pagination"></div>
									
								</div>
								<div class="swiper-button-prev"></div>
        						<div class="swiper-button-next"></div>
							</div>
				</div>
				
				
				<!--end Carousel products-->        
				<script type="text/javascript"> 
					$(document).ready(function(){



var swiper = new Swiper('#acontent_products_scroll_<? echo $content_block['manufacturer_page_content_id']; ?> .swiper-container', {
			slidesPerView: 5,
			loop: true,
			centeredSlides: false,
			preloadImages: false,   
			lazy: true,
			// init: false,
			pagination: {
				el: '#acontent_products_scroll_<? echo $content_block['manufacturer_page_content_id']; ?> .swiper-pagination',
				clickable: true,
			},
			breakpoints: {
				400: {
					slidesPerView: 2,
				},
				600: {
					slidesPerView: 3,
				},
				768: {
					slidesPerView: 4,
				},
				1000: {
					slidesPerView: 4,
					navigation: {
						nextEl: '#acontent_products_scroll_<? echo $content_block['manufacturer_page_content_id']; ?> .swiper-button-next',
						prevEl: '#acontent_products_scroll_<? echo $content_block['manufacturer_page_content_id']; ?> .swiper-button-prev',
						clickable: true,
					},
				},
				1280: {
					slidesPerView: 5,
					navigation: {
						nextEl: '#acontent_products_scroll_<? echo $content_block['manufacturer_page_content_id']; ?> .swiper-button-next',
						prevEl: '#acontent_products_scroll_<? echo $content_block['manufacturer_page_content_id']; ?> .swiper-button-prev',
						clickable: true,
					},
				},
				1600: {
					slidesPerView: 5,
					navigation: {
						nextEl: '#acontent_products_scroll_<? echo $content_block['manufacturer_page_content_id']; ?> .swiper-button-next',
						prevEl: '#acontent_products_scroll_<? echo $content_block['manufacturer_page_content_id']; ?> .swiper-button-prev',
						clickable: true,
					},
				},
			}
		});


					});
				</script>
				
				
				<? } elseif ($content_block['type'] == 'collections') { ?>
				<? if ($content_block['title']) { ?>
					<div class="product__slider-head viewed">
						<h4 class="title center"><?php echo $content_block['title']; ?></h4>
					</div>
				<? } ?>
				<div class="category-list collection-list 4"  style="margin-bottom: 35px">
						
						
						
						<?php $i=1; foreach ($content_block['collections_array'] as $collection) { ?>
							<div class="manufacturer_colection-item">
								<div class="collection-block-container">
									
									<a href="<? echo $collection['href']; ?>"  title="<?php echo $collection['name']; ?>">
										<div class="img-collection">
										<?php if (!$collection['thumb']) { ?>
											<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/images/noimage.jpg" />
										<?php } ?>
										
										<?php if ($collection['thumb']) { ?>
											<img src="<?php echo $collection['thumb']; ?>" alt="<?php echo $collection['thumb']; ?>" />
										<?php } ?>
										</div>
										<span><?php echo $collection['name']; ?></span>									
									</a>
								</div>
								
							</div>
						<? } ?>
						
				</div>
				
				<? } elseif ($content_block['type'] == 'categories') { ?>
				
				
					
						<? if ($content_block['title']) { ?>
							<div class="product__slider-head viewed" style="margin-top: 35px">
								<h4 class="title center"><?php echo $content_block['title']; ?></h4>
							</div>
						<? } ?>
						
					<div class="category-list collection-list 1" style="margin-bottom: 35px;">	
						<?php $i=1; foreach ($content_block['categories_array'] as $category) { ?>


							<div class="manufacturer_colection-item">
									<div class="collection-block-container">
										
										<a href="<? echo $category['href']; ?>"  title="<?php echo $category['name']; ?>">
											<div class="img-collection">
											<?php if (!$category['thumb']) { ?>
											<img src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/images/noimage.jpg" />
												<?php } ?>
												
												<?php if ($category['thumb']) { ?>
													<img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['thumb']; ?>" />
												<?php } ?>
											</div>
											<span><?php echo $category['name']; ?></span>									
										</a>
									</div>
								
								</div>

							
						<? } ?>
						
					
				</div>
				
			<? } ?>

		<? } ?>
		

		
		<? /*  НЕТ ДОПКОНТЕНТА  */ ?>	
		<? } else {  ?>  
	
		<?php if ($products) { ?>
			<div style="width: 100%;">
			<!--catalog__content-head-->
			<div class="catalog__content-head">
				<!--catalog__sort-->
				<div class="catalog__sort">
					<span><?php echo $text_sort; ?></span>
					<div class="catalog__sort-btn">
						<select id="input-sort" class="form-control" onchange="location = this.value;">
	                        <?php foreach ($sorts as $sorts) { ?>
	                            <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
	                                <option value="<?php echo $sorts['href']; ?>"
									selected="selected"><?php echo $sorts['text']; ?></option>
									<?php } else { ?>
	                                <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
				</div>
				<!--/catalog__sort-->
			</div>
			<!--/catalog__content-head-->
			<div class="catalog__content ">
	          	<!--product__grid-->
	          	<div class="product__grid all-colection-product" id="product__grid">
	        		
	            	<?php foreach ($products as $product) { ?>
						              		<?php include(dirname(__FILE__).'/../structured/product_single.tpl'); ?>
						            	<?php } ?>
	            	
	          	</div>
	          	<!--/product__grid-->
	        </div>
			
			<div class="pagination"><?php echo $pagination; ?></div>
			</div>
			<?php } else { /* ?>
				<div class="content"><?php echo $text_empty; ?></div>
				<div class="buttons">
				<div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
				</div>
			<?php */ }?>
		<!-- </div>	 -->
	
			
	<? } ?>
</div>	
	<?php echo $content_bottom; ?>
		
    <?php if($description) { ?>
		<div class="category-info" style="width: 100%;">
			<?php echo $description; ?>
		</div>
	<?php } ?>
	
	</div>
</section>


<? if (isset($google_tag_params)) { ?> 
	<script type="text/javascript">
		var google_tag_params = {
			<? foreach ($google_tag_params as $name => $value) { ?>
				<? if ($name != 'dynx_totalvalue' && $name != 'ecomm_totalvalue') { ?>
					<? echo $name; ?>:'<? echo $value ?>',
					<? } else { ?>
					<? echo $name; ?>:<? echo $value ?>,
				<? } ?>
			<? } ?>
		};
	</script> 
<? } ?>
<script type="text/javascript"><!--
	$(document).ready(function(){
        $('#first-label').css({"background-image":"url('<? echo $manufacturer_logo; ?>')"});
		
		
	});
	$('select').SumoSelect();
	function display(view) {
		if (view == 'list') {
			$('.product-grid').attr('class', 'product-list');
			
			$('.product-list > div').each(function(index, element) {
				
				
				html  = '<div class="left">';
				
				<?php if  ($this->config->get('img_additional2') == '1') { ?> 
					html += '<div class="owl-addimagecat owl-carousel">';
				<?php } ?>
				
				var image = $(element).find('.image').html();
				if (image != null) { 
					html += '<div class="image">' + image + '</div>';
				}
				
				<?php if  ($this->config->get('img_additional2') == '1') { 
					for ($key = 0; $key < 6; $key++) { ?>
					var image2 = $(element).find('.image<?php echo $key;?>').html();
					if (image2 != null) { 
						html += ' <div class="image image<?php echo $key;?>">' + image2 + '</div>';
						
					}
				<?php } ?>
				
				html += '</div>';
				<?php } ?>
				
				
				html += '</div>';
				
				html += '<div class="centr">';
				
				html += ' <div class="name">' + $(element).find('.name').html() + '</div>';
				
				
				
				html += '  <div class="description">' + $(element).find('.description').html() + '</div>';
				var rating = $(element).find('.rating').html();
				
				if (rating != null) {
					html += '<div class="rating">' + rating + '</div>';
				}
				html += '</div>';
				
				html += '<div class="right">';
				var price = $(element).find('.price').html();
				
				if (price != null) {
					html += '<div class="price">' + price  + '</div>';
				}
				html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
				
				html += ' <div class="hover_but">';
				<?php if ($this->config->get('show_wishlist') == '1')  { ?>
					html += '  <div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
				<?php } ?>
				<?php if ($this->config->get('show_compare') == '1')  { ?>
					html += '  <div class="compare">' + $(element).find('.compare').html() + '</div>';
				<?php } ?>
				<?php if  ((isset($product['quickview'])) && ($this->config->get('quick_view') == '1')) { ?>  
					html += ' <div class="quickviewbutton">' + $(element).find('.quickviewbutton').html() + '</div>';
				<?php } ?>
				html += '</div>'; 
				
				html += '</div>';
				
				$(element).html(html);
			});		
			
			$('.display').html('<span class="iconlist"></span> <a onclick="display(\'grid\');" class="icongrid"></a>');
			
			$.totalStorage('display', 'list'); 
			} else {
			$('.product-list').attr('class', 'product-grid');
			
			$('.product-grid > div').each(function(index, element) {
				html = '';
				
				html += '<div class=img_but>';
				<?php if  ($this->config->get('img_additional2') == '1') { ?> 
					html += '<div class="owl-addimagecat owl-carousel">';
				<?php } ?>
				
				var image = $(element).find('.image').html();
				if (image != null) { 
					html += '<div class="image">' + image + '</div>';
				}
				
				<?php if  ($this->config->get('img_additional2') == '1') { 
					for ($key = 0; $key < 6; $key++) { ?>
					var image2 = $(element).find('.image<?php echo $key;?>').html();
					if (image2 != null) { 
						html += ' <div class="image image<?php echo $key;?>">' + image2 + '</div>';
						
					}
				<?php } ?>
				
				html += '</div>';
				<?php } ?>
				
				
				html += ' <div class="hover_but">';
				<?php if ($this->config->get('show_wishlist') == '1')  { ?>
					html += '  <div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
				<?php } ?>
				<?php if ($this->config->get('show_compare') == '1')  { ?>
					html += '  <div class="compare">' + $(element).find('.compare').html() + '</div>';
				<?php } ?>
				<?php if  ((isset($product['quickview'])) && ($this->config->get('quick_view') == '1')) { ?>  
					html += ' <div class="quickviewbutton">' + $(element).find('.quickviewbutton').html() + '</div>';
				<?php } ?>
				html += '</div>'; 
				html += '</div>';
				
				html += '<div class="name">' + $(element).find('.name').html() + '</div>';
				html += '<div class="description">' + $(element).find('.description').html() + '</div>';
				
				var price = $(element).find('.price').html();
				
				if (price != null) {
					html += '<div class="price">' + price  + '</div>';
				}
				
				html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';	
				var rating = $(element).find('.rating').html();
				
				if (rating != null) {
					html += '<div class="rating">' + rating + '</div>';
				}
				
				
				
				$(element).html(html);
			});	
					
			$('.display').html('<a onclick="display(\'list\');" class="iconlist"></a> <span class="icongrid"></span>');
			
			$.totalStorage('display', 'grid');
		}
	}
	
	view = $.totalStorage('display');
	
	if (view) {
		display(view);
		} else {
		display('grid');
	}
//--></script>
		<script type="text/javascript">
			$(document).ready(function() {
				if ((typeof fbq !== 'undefined')){
					fbq('track', 'ViewCategory');
				}
			});
		</script>

<script>
	
	openTextCreate('buttonOpenText', '.category-info', 100);
	
</script>
<?php echo $footer; ?>