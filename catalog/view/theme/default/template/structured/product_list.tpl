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
					
					<!--product__grid-->
					<div class="product__grid <?php if (!empty($do_not_show_left_aside_in_list)) { ?>product__grid__colection<?php } ?>" id="product__grid">
						
						<!--product__item-->
						<?php /* if (!empty($page_type) && $page_type == 'collection'){ */?>
						
						<?php $productIDS = array(); ?>
						<?php $productPriceSum = 0; ?>						
						<?php $productCounter = 1; ?> 
						<?php    foreach ($products as $product) { ?>
							<?php include($this->checkTemplate(dirname(__FILE__),'/product_single.tpl')); ?>
							<?php if (empty($this->request->get['page'])) { ?>
								<?php if (IS_MOBILE_SESSION && $productCounter == 8) { ?>
									<?php include($this->checkTemplate(dirname(__FILE__),'/listing_pwainstall.tpl')); ?>
									<?php } elseif ($productCounter == 12) { ?>
									<?php include($this->checkTemplate(dirname(__FILE__),'/listing_pwainstall.tpl')); ?>
								<?php } ?>
							<?php } ?>
							<?php $productIDS[] = $product['product_id']; ?>
							<?php $productPriceSum += ($product['special']?(float)prepareEcommPrice($product['special']):(float)prepareEcommPrice($product['price'])); ?>						
							<?php $productCounter += 1; ?> 
						<?php } ?>
						<!--/product__item-->
						
					</div>
					<output id="output" class="hidden"></output>
					<!--/product__grid-->
					
					<!--pages-->
					<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/pagination.tpl')); ?>
					<!--/pages-->
				</div>
				<!--/catalog__content-->
			</div>
			<!--/catalog-inner-->
			
		</div>
	</section>
	
	<link rel="stylesheet" href="catalog/view/theme/kp/css/sumoselect.css" />
	<script src="catalog/view/theme/kp/js/sumoselect.min.js"></script> 
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
								'name': '<?php echo prepareEcommString($product['name']); ?>', 
								'id': '<?php echo prepareEcommString($product['product_id'] . GOOGLE_ID_ENDFIX); ?>',
								'price': '<?php echo ($product['special'])?prepareEcommPrice($product['special']):prepareEcommPrice($product['price']); ?>',
								'brand': '<?php echo prepareEcommString($product['manufacturer']); ?>',
								'category': '<?php echo prepareEcommString($this->model_catalog_product->getGoogleCategoryPath($product['product_id'])); ?>',
								'list': '<?php echo prepareEcommString($heading_title); ?>',
								'position': <?php echo $k; ?>
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
	
	</script>
	
	
	
<?php } ?>	