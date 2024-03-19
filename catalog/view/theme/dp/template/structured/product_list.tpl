<?php if ($products) { ?>
	<section  id="section-catalog" class="catalog ">
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
							<?php if (IS_MOBILE_SESSION) { ?>
								<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M15 1V17M15 17L11 13M15 17L19 13M5 17V1M5 1L1 5M5 1L9 5" stroke="#888F97" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							<?php } ?>
							<div class="catalog__sort-btn">
								<select id="input-sort" class="form-control" onchange="location = this.value;">
	                                <?php foreach ($sorts as $sort_item) { ?>
	                                    <?php if ($sort_item['value'] == ($sort . '-' . $order)) { ?>
	                                        <option value="<?php echo $sort_item['href']; ?>" selected="selected"><?php echo $sort_item['text']; ?></option>
											<?php } else { ?>
	                                        <option value="<?php echo $sort_item['href']; ?>"><?php echo $sort_item['text']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<!--/catalog__sort-->
						<?php if (IS_MOBILE_SESSION && !IS_TABLET_SESSION) { ?>
							<div>
								<button class="open_mob_filter">
									<svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M2.38589 3.66687C1.62955 2.82155 1.25138 2.39889 1.23712 2.03968C1.22473 1.72764 1.35882 1.42772 1.59963 1.22889C1.87684 1 2.44399 1 3.57828 1H18.4212C19.5555 1 20.1227 1 20.3999 1.22889C20.6407 1.42772 20.7748 1.72764 20.7624 2.03968C20.7481 2.39889 20.3699 2.82155 19.6136 3.66687L13.9074 10.0444C13.7566 10.2129 13.6812 10.2972 13.6275 10.3931C13.5798 10.4781 13.5448 10.5697 13.5236 10.6648C13.4997 10.7721 13.4997 10.8852 13.4997 11.1113V16.4584C13.4997 16.6539 13.4997 16.7517 13.4682 16.8363C13.4403 16.911 13.395 16.9779 13.336 17.0315C13.2692 17.0922 13.1784 17.1285 12.9969 17.2012L9.59686 18.5612C9.22931 18.7082 9.04554 18.7817 8.89802 18.751C8.76901 18.7242 8.6558 18.6476 8.583 18.5377C8.49975 18.4122 8.49975 18.2142 8.49975 17.8184V11.1113C8.49975 10.8852 8.49975 10.7721 8.47587 10.6648C8.45469 10.5697 8.41971 10.4781 8.37204 10.3931C8.31828 10.2972 8.2429 10.2129 8.09213 10.0444L2.38589 3.66687Z" stroke="#888F97" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
									Фільтр
								</button>
							</div>
							<button class="catalog__link-mob-btn">
								<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M6.4 1H2.6C2.03995 1 1.75992 1 1.54601 1.10899C1.35785 1.20487 1.20487 1.35785 1.10899 1.54601C1 1.75992 1 2.03995 1 2.6V6.4C1 6.96005 1 7.24008 1.10899 7.45399C1.20487 7.64215 1.35785 7.79513 1.54601 7.89101C1.75992 8 2.03995 8 2.6 8H6.4C6.96005 8 7.24008 8 7.45399 7.89101C7.64215 7.79513 7.79513 7.64215 7.89101 7.45399C8 7.24008 8 6.96005 8 6.4V2.6C8 2.03995 8 1.75992 7.89101 1.54601C7.79513 1.35785 7.64215 1.20487 7.45399 1.10899C7.24008 1 6.96005 1 6.4 1Z" stroke="#404345" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M17.4 1H13.6C13.0399 1 12.7599 1 12.546 1.10899C12.3578 1.20487 12.2049 1.35785 12.109 1.54601C12 1.75992 12 2.03995 12 2.6V6.4C12 6.96005 12 7.24008 12.109 7.45399C12.2049 7.64215 12.3578 7.79513 12.546 7.89101C12.7599 8 13.0399 8 13.6 8H17.4C17.9601 8 18.2401 8 18.454 7.89101C18.6422 7.79513 18.7951 7.64215 18.891 7.45399C19 7.24008 19 6.96005 19 6.4V2.6C19 2.03995 19 1.75992 18.891 1.54601C18.7951 1.35785 18.6422 1.20487 18.454 1.10899C18.2401 1 17.9601 1 17.4 1Z" stroke="#404345" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M17.4 12H13.6C13.0399 12 12.7599 12 12.546 12.109C12.3578 12.2049 12.2049 12.3578 12.109 12.546C12 12.7599 12 13.0399 12 13.6V17.4C12 17.9601 12 18.2401 12.109 18.454C12.2049 18.6422 12.3578 18.7951 12.546 18.891C12.7599 19 13.0399 19 13.6 19H17.4C17.9601 19 18.2401 19 18.454 18.891C18.6422 18.7951 18.7951 18.6422 18.891 18.454C19 18.2401 19 17.9601 19 17.4V13.6C19 13.0399 19 12.7599 18.891 12.546C18.7951 12.3578 18.6422 12.2049 18.454 12.109C18.2401 12 17.9601 12 17.4 12Z" stroke="#404345" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M6.4 12H2.6C2.03995 12 1.75992 12 1.54601 12.109C1.35785 12.2049 1.20487 12.3578 1.10899 12.546C1 12.7599 1 13.0399 1 13.6V17.4C1 17.9601 1 18.2401 1.10899 18.454C1.20487 18.6422 1.35785 18.7951 1.54601 18.891C1.75992 19 2.03995 19 2.6 19H6.4C6.96005 19 7.24008 19 7.45399 18.891C7.64215 18.7951 7.79513 18.6422 7.89101 18.454C8 18.2401 8 17.9601 8 17.4V13.6C8 13.0399 8 12.7599 7.89101 12.546C7.79513 12.3578 7.64215 12.2049 7.45399 12.109C7.24008 12 6.96005 12 6.4 12Z" stroke="#404345" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
						<?php } ?>
						
					</div>
					<!--/catalog__content-head-->
					
					<!--product__grid-->
					<div class="product__grid <?php if (!empty($do_not_show_left_aside_in_list)) { ?>product__grid__colection<?php } ?>" id="product__grid">
						
						<!--product__item-->						
						<?php $productIDS = array(); ?>
						<?php $productPriceSum = 0; ?>						
						<?php $productCounter = 1; ?> 
						<?php    foreach ($products as $product) { ?>
							<?php include(dirname(__FILE__).'/product_single.tpl'); ?>
							<?php if (empty($this->request->get['page'])) { ?>
								<?php if (IS_MOBILE_SESSION && $productCounter == 8) { ?>
									<?php include($this->checkTemplate(dirname(__FILE__), '/listing_pwainstall.tpl')); ?>
									<?php } elseif ($productCounter == 12) { ?>
									<?php include($this->checkTemplate(dirname(__FILE__), '/listing_pwainstall.tpl')); ?>
								<?php } ?>
							<?php } ?>
							<?php $productIDS[] = $product['product_id']; ?>
							<?php $productPriceSum += ($product['special']?prepareEcommPrice($product['special']):prepareEcommPrice($product['price'])); ?>						
							<?php $productCounter += 1; ?> 
						<?php } ?>
						<!--/product__item-->
						
					</div>
					<output id="output" class="hidden"></output>
					<!--/product__grid-->
					
					<!--pages-->
					<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/pagination.tpl')); ?>
					<!--/pages-->
				</div>
				<!--/catalog__content-->
			</div>
			<!--/catalog-inner-->
			
		</div>
	</section>
	
	<link rel="stylesheet" href="catalog/view/theme/dp/css/sumoselect.css" />
	<script src="catalog/view/theme/dp/js/sumoselect.min.js"></script> 
	<script>
		$('select').SumoSelect();
		
		if (!$(".aside").length){
		$('#product__grid').addClass('aside-none');
		}
		
		<?php if ($productIDS) { ?>
		$(document).ready(function(){
			window.dataLayer = window.dataLayer || [];
			dataLayer.push({
				'event': '<?php echo !empty($pagetype)?$pagetype:'view_item_list'; ?>',
				'value': '<?php echo $productPriceSum; ?>',
				'items':[
				<?php $i = 0; foreach ($productIDS as $productID) { ?>
					{
						'id': '<?php echo $productID . GOOGLE_ID_ENDFIX; ?>', 
						'google_business_vertical': 'retail'
					}<?php if ($i < (count($products) - 1)) {?>,<?php } ?>
				<?php } ?>
				]
			});
		});
	<?php } ?>

	<?php if ($this->config->get('config_vk_enable_pixel')) { ?>
		<?php if ($products && !empty($page_type) && $page_type == 'category') { ?>
			var VKRetargetFunction = function(){
				if((typeof VK !== 'undefined')){
					console.log('VK trigger view_category');
					
					let vkitems = [
						<?php $i = 0; foreach ($products as $product) { ?>
						{'id': '<?php echo $product['product_id']; ?>', 'price': '<?php echo ($product['special'])?prepareEcommPrice($product['special']):prepareEcommPrice($product['price']); ?>'}
						<?php if ($i < (count($products) - 1)) {?>,<?php } ?>
						<?php } ?>
					];					

					VK.Retargeting.ProductEvent(<?php echo $this->config->get('config_vk_pricelist_id'); ?>, 'view_category', { 'category_ids' : '<?php echo $category_id; ?>', 'products' : vkitems, 'currency_code': '<?php echo $this->config->get('config_regional_currency'); ?>', 'total_price': '<?php echo prepareEcommPrice($productPriceSum); ?>' });
				} else {
					console.log('VK is undefined');
				}
			};			
		<?php } elseif ($products && !empty($page_type) && $page_type == 'search_page') { ?>
			var VKRetargetFunction = function(){
				if((typeof VK !== 'undefined')){
					console.log('VK trigger view_search');

					let vkitems = [
						<?php $i = 0; foreach ($products as $product) { ?>
						{'id': '<?php echo $product['product_id']; ?>', 'price': '<?php echo ($product['special'])?prepareEcommPrice($product['special']):prepareEcommPrice($product['price']); ?>'}
						<?php if ($i < (count($products) - 1)) {?>,<?php } ?>
						<?php } ?>
					];

					VK.Retargeting.ProductEvent(<?php echo $this->config->get('config_vk_pricelist_id'); ?>, 'view_search', { 'search_string' : '<?php echo prepareEcommString($search); ?>', 'products' : vkitems, 'currency_code': '<?php echo $this->config->get('config_regional_currency'); ?>', 'total_price': '<?php echo prepareEcommPrice($productPriceSum); ?>' }); 
				} else {
					console.log('VK is undefined');
				}
			};
		<?php } elseif ($products) { ?>
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

	<?php } ?>
	
	<?php if ($products) { ?>	
		$(document).ready(function(){			
			<?php $this->load->model('catalog/product'); ?>
			
			<?php $chunkedProducts = array_chunk($products, 5); ?>
			<?php $this->session->data['gac:listfrom'] = prepareEcommString($heading_title); ?>			
			<?php $k = 0; foreach ($chunkedProducts as $key => $products) { ?>
				window.dataLayer = window.dataLayer || [];
				console.log('dataLayer.push impressions');
				dataLayer.push({
					'event': 'productImpression',
					'ecommerce': {
						'currencyCode': '<?php echo $this->config->get('config_regional_currency'); ?>',  
						'impressions':[
						<?php $i = 0; foreach ($products as $product) { ?>
							{	
							<?php if (!empty($product['ecommerceData'])) { ?>
								'name': 	'<?php echo $product['ecommerceData']['name']; ?>',
								'id': 		'<?php echo $product['ecommerceData']['id']; ?>',
								'price': 	'<?php echo $product['ecommerceData']['price']; ?>',
								'brand': 	'<?php echo $product['ecommerceData']['brand']; ?>',
								'category': '<?php echo $product['ecommerceData']['category']; ?>',
								'list': 	'<?php echo prepareEcommString($heading_title); ?>',
								'position': <?php echo $k; ?>
							<?php } else { ?>
								'name': 	'<?php echo prepareEcommString($product['name']); ?>', 
								'id': 		'<?php echo prepareEcommString($product['product_id'] . GOOGLE_ID_ENDFIX); ?>',
								'price': 	'<?php echo ($product['special'])?prepareEcommPrice($product['special']):prepareEcommPrice($product['price']); ?>',
								'brand': 	'<?php echo prepareEcommString($product['manufacturer']); ?>',
								'category': '<?php echo prepareEcommString($this->model_catalog_product->getGoogleCategoryPathForCategory($product['main_category_id'])); ?>',
								'list': 	'<?php echo prepareEcommString($heading_title); ?>',
								'position': <?php echo $k; ?>
							<?php } ?>
							}<?php if ($i < (count($products) - 1)) {?>,<?php } ?>
							<?php $i++; ?>
							<?php $k++; ?>
						<?php } ?>
						]
					}
				});	
			<?php } ?>
			
		});
	<?php } ?>
	
	<?php if ($products) { ?>
		if ((typeof fbq !== 'undefined')){
			
			<?php
				$content_ids_str = "";
				foreach ($products as $product){
					$content_ids_str .= "'" . $product['product_id'] . "',";
				}
				$content_ids_str = rtrim($content_ids_str, ',');
			?>
			
			fbq('track', 'ViewContent', 
			{
				content_type: 'product',
				content_ids: [<?php echo $content_ids_str?>]
			});
		}
	<?php } ?>	

	if (document.documentElement.clientWidth < 560) {
		$(function() {

			var element = $('.catalog__content');
		  	var elementOffsetTop = element.offset().top - 56;

		  	$(window).scroll(function() {
			    var scrollTop = $(window).scrollTop();

			    if (scrollTop >= elementOffsetTop) {
			      element.addClass('fixed');
			    } else {
			      element.removeClass('fixed');
			    }
		  	});
			
		});
 	}

 	
	</script>
	
	
	
<?php } ?>	