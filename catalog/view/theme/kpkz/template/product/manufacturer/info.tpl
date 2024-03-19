<?php echo $header; ?>
<link rel="stylesheet" href="catalog/view/theme/kpkz/css/sumoselect.css" />
<script src="catalog/view/theme/kpkz/js/sumoselect.min.js"></script> 
<?php include($this->checkTemplate(dirname(__FILE__),'/structured_manufacturer/head.tpl')); ?>

<!--desc-brand-->
<div class="desc-brand">
	<div class="wrap">
		<div class="desc-brand__head">
			<h1><?php echo $heading_title; ?></h1>
			(<div class="desc-brand__number-products" ><?php echo $products_total;?> <?php echo $text_brands_count_products; ?></div>)
			<?php if($manufacturer_location) { ?>
				<div class="desc-brand__country"><?php echo $manufacturer_location; ?></div>
			<?php } ?>
		</div>
    <!-- <div class="desc-brand__show-more">
      <a class="btn-border" href="#">Все товары бренда</a>
    </div> -->
  </div>
</div>
<!--/desc-brand-->


<?php if ($additionalContent) { ?>
	<!--categories-photo-->
	<div class="categories-photo">
		<div class="wrap">
			<div class="categories-photo__row__collection">
				<?php foreach ($additionalContent as $additionalContentRow) { ?>
					<? if ($additionalContentRow['type'] == 'collections') { ?>					
						<?php if (!empty($additionalContentRow['virtual'])) { ?>
							<?php $i=1; foreach ($additionalContentRow['collections_array'] as $collection) { ?>
								<div class="categories-photo__item 3 <?php if (!empty($additionalContentRow['virtual'])) { ?>virtual<? } ?>" >	
									<a href="<?php echo $collection['href']; ?>" >
										<div style="
										background-image: url(<?php echo $collection['thumb']; ?>);
										background-position: center;
										background-repeat: no-repeat;
										height: 300px;
										display: block;">
									</div>

									<div class="categories-photo__label">
										<span><? echo $text_brands_head_collections; ?></span>
										<p><?php echo $collection['name']; ?></p>
									</div>
								</a>
							</div>	
						<? } ?>	
					<? } ?>															 
				<? } ?>		
			<?php } ?>
		</div>
	</div>
</div>
<!--/categories-photo-->
<?php } ?>
<!-- Категории слайдером -->
<?php if ($additionalContent) { ?>
	<?php foreach ($additionalContent as $additionalContentRow) { ?>
		<? if ($additionalContentRow['type'] == 'categories') { ?>											
			
			<div class="collection-content freeSlider-wrap">   
				<div class="navigation-slider">
					<div class="title"><?php echo $additionalContentRow['title']; ?></div>	
					<div class="nav-group">
						<div class="swiper-prev-slide">
							<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect x="1" y="1" width="58" height="58" stroke="#51A881" stroke-width="2"/>
								<path d="M35 42L23 30L35 18" stroke="#51A881" stroke-width="3"/>
							</svg>
						</div>
						<div class="swiper-next-slide">
							<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect x="-1" y="1" width="58" height="58" transform="matrix(-1 0 0 1 58 0)" stroke="#51A881" stroke-width="2"/>
								<path d="M25 42L37 30L25 18" stroke="#51A881" stroke-width="3"/>
							</svg>
						</div>
					</div>				

					<div class="swiper-pagination"></div>
				</div>
				<div class="slider-content">
					<div class="swiper-container">	    	
						<div class="swiper-wrapper">
							<?php foreach ($additionalContentRow['categories_array'] as $category) { ?>
								<div class="swiper-slide collection-all">
									<a href="<?php echo $category['href']; ?>"  title="Коллекция <?php echo $category['name']; ?>">  
										<?php if (!$category['thumb']) { ?>
											<img class="swiper-lazy" src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/images/noimage.jpg" />
										<?php } ?>	                                
										<?php if ($category['thumb']) { ?>
											<img loading="lazy" class="swiper-lazy lazy" src="<?php echo $category['thumb']; ?>" alt="Коллекция <?php echo $category['name']; ?>" />
										<?php } ?>                                   
										<div class="categori-photo">
											<span><?php echo $text_brands_head_category; ?></span>
											<p><?php echo $category['name']; ?></p>
										</div>
									</a>                            

								</div>
							<?php } ?>    
						</div>
					</div>
				</div>					
			</div>
		<? } ?>												
	<? } ?>
<?php } ?>	

<!-- Коллекции слайдером -->
<?php if ($additionalContent) { ?>


	<?php foreach ($additionalContent as $additionalContentRow) { ?>
		<? if ($additionalContentRow['type'] == 'collections') { ?>
			<?php if (empty($additionalContentRow['virtual'])) { ?>	
				<div class="collection-content freeSlider-wrap">   
					<div class="navigation-slider">
						<div class="title"><?php echo $additionalContentRow['title']; ?></div>	
						<div class="nav-group">
							<div class="swiper-prev-slide">
								<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
									<rect x="1" y="1" width="58" height="58" stroke="#51A881" stroke-width="2"/>
									<path d="M35 42L23 30L35 18" stroke="#51A881" stroke-width="3"/>
								</svg>
							</div>
							<div class="swiper-next-slide">
								<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
									<rect x="-1" y="1" width="58" height="58" transform="matrix(-1 0 0 1 58 0)" stroke="#51A881" stroke-width="2"/>
									<path d="M25 42L37 30L25 18" stroke="#51A881" stroke-width="3"/>
								</svg>
							</div>
						</div>				

						<div class="swiper-pagination"></div>
					</div>
					<div class="slider-content">
						<div class="swiper-container">	    	
							<div class="swiper-wrapper">  			        
								<?php foreach ($additionalContentRow['collections_array'] as $collection) { ?>
									<div class="swiper-slide collection-all">
										<a href="<?php echo $collection['href']; ?>"  title="Коллекция <?php echo $collection['name']; ?>">  
											<?php if (!$collection['thumb']) { ?>
												<img class="swiper-lazy" src="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/images/noimage.jpg" />
											<?php } ?>	                                
											<?php if ($collection['thumb']) { ?>
												<img loading="lazy" class="swiper-lazy lazy" src="<?php echo $collection['thumb']; ?>" alt="Коллекция <?php echo $collection['name']; ?>" />
											<?php } ?>                                   
											<div class="categori-photo">
												<span><?php echo $text_brands_head_collection; ?></span>
												<p><?php echo $collection['name']; ?></p>
											</div>
										</a>                            

									</div>
								<?php } ?>    
							</div>
						</div>
					</div>					
				</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>

	<script>

		window.onload = function() {
			$('.collection-content .swiper-container').each(function(){

				let navPag  = $(this).closest('.collection-content').find('.swiper-pagination'); 
				let navPrev = $(this).closest('.collection-content').find('.swiper-prev-slide'); 
				let navNext = $(this).closest('.collection-content').find('.swiper-next-slide'); 

				var swiper = new Swiper(this, {
					loop: false,
					slidesPerView: "auto",
				        // slidesPerView: 3,
				        preloadImages: false,
		                // freeMode: true,
		                lazy: true,
		                centeredSlides: false,
		                spaceBetween: 30,
		                grabCursor: true, 
		                pagination: {
		                	el: navPag,
		                	type: 'fraction',
		                	renderFraction: function (currentClass, totalClass) {
		                		return '<span class="' + currentClass + '"></span>' +
		                		' / ' +
		                		'<span class="' + totalClass + '"></span>';
		                	}
		                },
		                on: {
		                	init() {
		                		setTimeout(updateFraction, 0, this)
		                	},
		                	slideChange() {
		                		updateFraction(this);
		                	},
		                	resize() {
		                		updateFraction(this);
		                	},
		                },
		                navigation: {
		                	nextEl: navNext,
		                	prevEl: navPrev,
		                },
		                breakpoints: {
		                	320: {
		                		spaceBetween: 0,
		                		slidesPerView: 1,
		                	},
		                	679: {
		                		spaceBetween: 30,
		                		slidesPerView: "auto",
		                	},
		                }    
		              });
			});

			function updateFraction(slider) {

				const { params, activeIndex, realIndex } = slider;

				let copySlide = slider.$el.find('.swiper-slide-duplicate').length;

				slider.$el
				.find(`.${params.pagination.currentClass}`)
				.text(`${realIndex+1}`);

				slider.$el
				.find(`.${params.pagination.totalClass}`)
				.text(slider.slides.length - copySlide)
			}
		};
	</script>


<?php } ?>










<?php if ($products) { ?>

	<style type="text/css">
		@media screen and (min-width: 1280px){
			.product__item.tpl_list .product__line__promocode {
				margin-top: 25px;
			}
			.product__item.tpl_list .product__info {
				position: relative;
				top: 0;
				right: 0;
				left: 0
			} 
			.product__item.tpl_list:hover .product__info {
				position: relative;
				top: 0;
				right: 0;
				left: 0
			}
		}
		.product__item.tpl_list .product__info .reward_wrap {
			top: 6px;
			right: 0;
			position: relative;
		}
		@media screen and (max-width:560px){
			.product__grid{
				justify-content: space-between;
			}
			.product__item, .manufacturer-list .manufacturer-content ul li:not(.list), 
			#content-search .catalog__content .product__item, #content-news-product .catalog__content .product__item{
				flex-basis: 49%;
				margin-bottom: 10px;
				padding: 10px
			}
			.product__item:hover .product__btn-cart button, .product__item:hover .product__btn-cart a,
			.product__item .product__btn-cart button, .product__item .product__btn-cart a{
				font-size: 0;
			}
			.product__rating{
				display: none
			}
			.product__title a {
				font-size: 12px;
				line-height: 14px;
				display: -webkit-box;
				-webkit-line-clamp: 3;
				-webkit-box-orient: vertical;
				overflow: hidden;
				text-overflow: ellipsis;
			}
			.product__item .product__delivery{
				font-size: 10px;
				line-height: 1;
			}
			.product__label > div{
				font-size: 10px;
				padding: 4px 6px;
				line-height: 1;
				height: auto
			}
			.product__item .price__sale {
				right: 10px;
				left: inherit;
				top: 10px;
				font-size: 10px;
				padding: 4px 6px;
				line-height: 1;
			}
			.product__photo{
				margin-bottom: 10px
			}
			.product__price-new {
				font-size: 15px;
				line-height: 1;
				margin: 0
			}
			.product__price-old {
				font-size: 12px;
				line-height: 1;
				margin: 0;
				align-self: start;
			}
			.product__price {
				display: flex;
				flex-wrap: wrap;
				align-self: start;
				flex-direction: column;
				justify-content: start;
				align-items: start;
				text-align: left;
			}
			.product__item .product__btn-cart .number__product__block{
				display: none
			}
			.product__item .product__btn-cart button{
				padding-left: 0;
				width: 30px;
				height: 30px;
				display: flex;
				align-items: center;
				justify-content: center;
			}
			.product__item .product__btn-cart button svg{
				margin: 0 !important;
				width: 15px;
				height: 15px
			}
			.product__item .product__btn-cart {
				position: absolute;
				margin: 0;
				z-index: 1;
				width: auto;
				right: 10px;
				bottom: 10px;
				height: 30px
			}
			.product_add-favorite {
				top: 10px;
				width: 40px;
				height: 35px;
				bottom: initial;
				left: 10px;
				flex-direction: column;
			}
			.product_add-favorite button{
				margin-left: 0 !important;
				margin-top: 0 !important;
				font-size: 15px !important;
			}
			.product_add-favorite button i{
				font-size: 15px !important;
			}
			.product_add-favorite button svg{
				width: 25px;
				height: 25px
			}
			.product__item:hover .product__btn-cart{
				width: auto
			}
			.product__item:hover .product__btn-cart button{
				padding-left: 0
			}
			.product__item:hover .product__price{
				flex-direction: column;
			}
			.product__item .product__info{
				padding-bottom: 0 !important
			}
		}
	</style>
	<section  id="content-manufacturer" class="catalog ">
		<div class="wrap">
			<!--catalog-inner-->
			<div class="catalog-inner">    

				<!--aside-->
				<?php if ($column_left && empty($do_not_show_left_aside_in_list)) { ?>
					<div class="aside">
						<!-- <div class="filter-btn" data-name-btn="Показать фильтр" data-name-btn-opened="Скрыть фильтр"></div> -->
						<!--filter-->
						<div class="filter">
							<!--accordion-->
							<div class="accordion">
								<!--accordion__item-->
								<?php echo $column_left; ?>
								<!--/accordion__item-->
							</div>
						</div>
						<!--/filter-->
					</div>
				<?php } ?>
				<!--/aside-->   

				<!--catalog__content-->
				<div class="catalog__content product-grid <?php if (empty($do_not_show_left_aside_in_list)) { ?>list__colection<?php } ?>">

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

							<div class="limit">
								<div id="search-refine-search3" class="search-refine-search3 catalog__sort">
									<span id="what_to_search3" class="what_to_search3" ><?php echo $text_limit; ?></span>
									<div class="catalog__sort-btn">
										<select id="input-sort" class="form-control" onchange="location = this.value;"> 
											<?php foreach ($limits as $limits) { ?>
												<?php if ($limits['value'] == $limit) { ?>
													<option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
												<?php } else { ?> 
													<option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>      
												<?php } ?>                        
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<?php if (IS_MOBILE_SESSION) { ?>
							<div>
								<button class="open_mob_filter"><?php echo $text_filter; ?><i class="fas fa-filter"></i></button>
							</div>
							<?php } ?>

							<!--display-type-->
							<?php if (empty($do_not_show_left_aside_in_list)) { ?>
								<div class="display-type">
									<a href="#" data-tpl="tpl_tile" class="current">
										<svg width="20" height="20" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M8 1H1V8H8V1Z" stroke="#EAE9E8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
											<path d="M19 1H12V8H19V1Z" stroke="#EAE9E8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
											<path d="M19 12H12V19H19V12Z" stroke="#EAE9E8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
											<path d="M8 12H1V19H8V12Z" stroke="#EAE9E8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
										</svg>
									</a>
									<a href="#" data-tpl="tpl_list">
										<svg width="20" height="20" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M19 7H1M19 1H1M19 13H1M19 19H1" stroke="#EAE9E8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
										</svg>
									</a>
								</div>
							<?php } ?>  
							<!--/display-type-->
						</div>
						<!--/catalog__content-head-->
						<section id="section-catalog">
							<!--product__grid-->
							<div class="product__grid <?php if (!empty($do_not_show_left_aside_in_list)) { ?>product__grid__colection<?php } ?>" id="product__grid">
								<!--product__item-->
								<?php /* if (!empty($page_type) && $page_type == 'collection'){ */?>

									<?php $productIDS = array(); ?>
									<?php $productPriceSum = 0; ?>						
									<?php $productCounter = 1; ?> 
									<?php    foreach ($products as $product) { ?>
										<?php include($this->checkTemplate(dirname(__FILE__),'/../../structured/product_single.tpl')); ?>
										<?php if (empty($this->request->get['page'])) { ?>
											<?php if (IS_MOBILE_SESSION && $productCounter == 8) { ?>
												<?php include($this->checkTemplate(dirname(__FILE__),'/../../structured/listing_pwainstall.tpl')); ?>
											<?php } elseif ($productCounter == 12) { ?>
												<?php include($this->checkTemplate(dirname(__FILE__),'/../../structured/listing_pwainstall.tpl')); ?>
											<?php } ?>
										<?php } ?>
										<?php $productIDS[] = $product['product_id']; ?>
										<?php $productPriceSum += ($product['special']?prepareEcommPrice($product['special']):prepareEcommPrice($product['price'])); ?>						
										<?php $productCounter += 1; ?> 
									<?php } ?>
									<!--/product__item  -->

								</div>
								<output id="output" class="hidden"></output>
								<!--/product__grid-->
							</section>
							<!--pages-->
							<?php include($this->checkTemplate(dirname(__FILE__),'/../../structured/pagination.tpl')); ?>
							<!--/pages-->
						</div>
						<!--/catalog__content-->
					</div>
					<!--/catalog-inner-->

				</div>
			</section>
		<?php } ?>

		<!--desc-brand-->
		<div class="desc-brand">
			<div class="wrap">
				<div class="desc-brand__text">
					<div class="read-more" style="max-height: 155px;overflow: hidden;">
						<?php echo $description; ?>
					</div>
				</div>
    <!-- <div class="desc-brand__show-more">
      <a class="btn-border" href="#">Все товары бренда</a>
    </div> -->
  </div>
</div>
<!--/desc-brand-->



<!-- <div class="wrap">
  <p><?php echo $products_link; ?></p>
</div> -->



<?php if ($this->config->get('site_position') == '1') { ?>
	<?php echo $content_bottom; ?>
<?php } ?>



<script>
	$('select').SumoSelect();
  // Скрытие текста о бренде
  let _hDescripion = $('.desc-brand__text').innerHeight();    
  if( _hDescripion > 150){

  	$('.desc-brand__text').css({'height':'160px','overflow':'hidden'}).addClass('manufacturer-info-hide');

  	$('.desc-brand__text').parent().append('<button class="btn-open-desc"><span>Читать полностью</span><i class="fas fa-angle-down"></button>');

  	$('.btn-open-desc').on('click', function(){
  		var _textBtn = $(this).find('span').text();
  		$('.read-more').parent().toggleClass('open-btn');
  		$(this).toggleClass('open');
  		$(this).find('span').text(_textBtn == "Читать полностью" ? "Скрыть" : "Читать полностью");
  		return false;
  	});

  }
</script>

<?php if ($this->config->get('config_vk_enable_pixel')) { ?>
	<script>
		<?php if ($products) { ?>
				var VKRetargetFunction = function(){
				if((typeof VK !== 'undefined')){
					console.log('VK trigger view_other');

					let vkitems = [
						<?php $i = 0; foreach ($products as $product) { ?>
						{'id': '<?php echo $product['product_id']; ?>', 'price': '<?php echo ($product['special'])?prepareEcommPrice($product['special']):prepareEcommPrice($product['price']); ?>'}
						<?php if ($i < (count($products) - 1)) {?>,<?php } ?>
						<?php } ?>
					];

					VK.Retargeting.ProductEvent(<?php echo $this->config->get('config_vk_pricelist_id'); ?>, 'view_other', {'products' : vkitems, 'currency_code': '<?php echo $this->config->get('config_regional_currency'); ?>', 'total_price': '<?php echo prepareEcommPrice($productPriceSum); ?>' }); 
				} else {
					console.log('VK is undefined');
				}
			};
		<?php } ?>
	</script>
<?php } ?>

<script type="text/javascript">
	$('.desc-brand__number-products').on('click', function () {
		var $target = $('#product__grid');
		$('html, body').stop().animate({
			'scrollTop': $target.offset().top - 50
		}, 900, 'swing')
	});

// $('.desc-brand__number-products').on('click', function(){
// 	let url = window.location.href+'/#product__grid';
// 	console.log(url);
// });

$('.mfilter-search').find('.mfilter-heading-text > span').text('<?php echo $text_brands_search_by_brand; ?>');

if ($(window).width() >= '768'){
	(function(){
		let _hMenu = $('.top-menu').innerHeight()
		var a = document.querySelector('#column-left'), b = null, K = null, Z = 0, P = _hMenu, N = 20;  

		window.addEventListener('scroll', Ascroll, false);
		document.body.addEventListener('scroll', Ascroll, false);
		function Ascroll() {
			var Ra = a.getBoundingClientRect(),
			R1bottom = document.querySelector('.catalog__content').getBoundingClientRect().bottom;
			if (Ra.bottom < R1bottom) {
				if (b == null) {
					var Sa = getComputedStyle(a, ''), s = '';
					for (var i = 0; i < Sa.length; i++) {
						if (Sa[i].indexOf('overflow') == 0 || Sa[i].indexOf('padding') == 0 || Sa[i].indexOf('border') == 0 || Sa[i].indexOf('outline') == 0 || Sa[i].indexOf('box-shadow') == 0 || Sa[i].indexOf('background') == 0) {
							s += Sa[i] + ': ' +Sa.getPropertyValue(Sa[i]) + '; '
						}
					}
					b = document.createElement('div');
					b.className = "stop";
					b.style.cssText = s + ' box-sizing: border-box; width: ' + a.offsetWidth + 'px;';
					a.insertBefore(b, a.firstChild);
					var l = a.childNodes.length;
					for (var i = 1; i < l; i++) {
						b.appendChild(a.childNodes[1]);
					}
					a.style.height = b.getBoundingClientRect().height + 'px';
					a.style.padding = '0';
					a.style.border = '0';
				}
				var Rb = b.getBoundingClientRect(),
				Rh = Ra.top + Rb.height,
				W = document.documentElement.clientHeight,
				R1 = Math.round(Rh - R1bottom),
				R2 = Math.round(Rh - W);
				if (Rb.height > W) {
	      if (Ra.top < K) {  // скролл вниз
	      	if (R2 + N > R1) {  
	      		if (Rb.bottom - W + N <= 20) {  
	      			b.className = 'sticky';
	      			b.style.top = W - Rb.height - N + 'px';
	      			Z = N + Ra.top + Rb.height - W;
	      		} else {
	      			b.className = 'stop';
	      			b.style.top = - Z + 'px';
	      		}
	      	} else {
	      		b.className = 'stop';
	      		b.style.top = - R1 +'px';
	      		Z = R1;
	      	}
	      } else {  // скролл вверх
	      	if (Ra.top - P < 0) { 
	      		if (Rb.top - P >= 0) {  
	      			b.className = 'sticky';
	      			b.style.top = P + 'px';
	      			Z = Ra.top - P;
	      		} else {
	      			b.className = 'stop';
	      			b.style.top = - Z + 'px';
	      		}
	      	} else {
	      		b.className = '';
	      		b.style.top = '';
	      		Z = 0;
	      	}
	      }
	      K = Ra.top;
	    } else {
	    	if ((Ra.top - P) <= 0) {
	    		if ((Ra.top - P) <= R1) {
	    			b.className = 'stop';
	    			b.style.top = - R1 +'px';
	    		} else {
	    			b.className = 'sticky';
	    			b.style.top = P + 'px';
	    		}
	    	} else {
	    		b.className = '';
	    		b.style.top = '';
	    	}
	    }
	    window.addEventListener('resize', function() {
	    	a.children[0].style.width = getComputedStyle(a, '').width
	    }, false);
	  }
	}
})()
}   	
</script>
<?php echo $footer; ?>