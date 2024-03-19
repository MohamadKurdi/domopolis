<?php if (!empty($product['is_inserted_action'])) { ?>
	<div class=" product__item <?php if (!empty($page_type) && $page_type == 'collection'){ ?>tpl_list<?php } ?> product-item-price-reloadable" data-product-id="<? echo $product['product_id']; ?>" id="product-item-single-<? echo $product['product_id']; ?>" data-gtm="">
		<!--product__photo-->
		<div class="reclame__block product__photo">
			<a href="<? echo $product['href']; ?>" title="<? echo $product['title']; ?>">
				<img loading="lazy" width="224" height="229" src="<?php echo $product['thumb']; ?>" title="<?php echo $product['title']; ?>" alt="<? echo $product['title']; ?>">
			</a>
		</div>
	</div>
<?php } else { ?>
	<div class="product__item product__item__reloadable<?php if (!empty($page_type) && $page_type == 'collection'){ ?> tpl_list<?php } ?>" data-product-id="<? echo $product['product_id']; ?>" data-gtm-product='{<?php foreach ($product['ecommerceData'] as $ecommerceKey => $ecommerceValue) { ?>"<?php echo $ecommerceKey; ?>": "<?php echo $ecommerceValue; ?>",<?php } ?> "url": "<?php echo $product['href']; ?>"}'>
		<div class="icon">
			<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M9.99315 2.13581C7.9938 -0.2016 4.65975 -0.830357 2.15469 1.31001C-0.350356 3.45038 -0.703032 7.02898 1.2642 9.5604C2.89982 11.6651 7.84977 16.1041 9.4721 17.5408C9.6536 17.7016 9.74436 17.7819 9.85021 17.8135C9.9426 17.8411 10.0437 17.8411 10.1361 17.8135C10.2419 17.7819 10.3327 17.7016 10.5142 17.5408C12.1365 16.1041 17.0865 11.6651 18.7221 9.5604C20.6893 7.02898 20.3797 3.42787 17.8316 1.31001C15.2835 -0.807843 11.9925 -0.2016 9.99315 2.13581Z" fill="#EB3274"/>
			</svg>
		</div>
		<!--product__photo-->
		<div class="product__photo">
			<a href="<? echo $product['href']; ?>" title="<?php echo strip_tags($product['name']); ?>">
				<img loading="lazy" width="265" height="265" src="<?php echo $product['thumb']; ?>" title="<?php echo strip_tags($product['name']); ?>" alt="<?php echo strip_tags($product['name']); ?>">
			</a>
			<?php if (!empty($product['additional_offer_product'])) { ?>
				<div class="product__additional_offer">
					<span class="label">+ <?php echo $text_present; ?></span>
					<span class="produc__additional_name"><?php echo $product['additional_offer_product']['name']; ?></span>
				</div>
			<? }  ?>
		</div>
		<!--/product__photo-->
		<!--product__middle-->
		<div class="product__middle product__middle__colection">					
			<?php if (!empty($product['need_ask_about_stock'])) { ?>
			<?php } elseif (empty($product['can_not_buy'])) { ?>				
			<?php } ?>
			<div class="product__title">
				<a href="<? echo $product['href']; ?>" title="<?php echo strip_tags($product['name']); ?>" class="prod_name"><? echo $product['name']; ?></a>				
				<? if (!empty($product['search_sku'])) { ?>
					<span class="product__sku"><small style="font-size:80%"><? echo $product['search_sku']; ?></small></span>
				<? } ?>
			</div>
			<div class="product__rating">
				<?php if ($this->config->get('config_review_status')) { ?>
					<span class="rate rate-<?php echo $product['rating']; ?>"></span>
					<?php if ($product['rating'] >= 1) { ?>
						<a href="<? echo $product['href']; ?>#reviews-col"><span class="count_reviews">(<?php echo $product['count_reviews']; ?>)</span></a>
					<?php } ?>					
				<?php } ?>
			</div>
			<?php if (empty($product['need_ask_about_stock']) && empty($product['can_not_buy'])) { ?>
				<?php if ($product['price']) { ?>
					<div class="product__price">
						<?php if (!$product['special']) { ?>
							<div class="product__price-new"><?php echo $product['price']; ?></div>
						<?php } else { ?>
							<div class="product__price-old_wrap">
								<div class="product__price-old">
									<?php echo $product['price']; ?>
								</div>
								<?php if ($product['special']) { ?>
									<div class="price__sale">-<?php echo $product['saving']; ?>%</div>			
								<?php } ?>
							</div>
							<div class="product__price-new">
								<?php echo $product['special']; ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
		<!--/product__middle-->
		<!--product__bottom-->
		<div class="product__bottom_new">
			<div class="product__bottom_top">
				<?php if (!empty($product['need_ask_about_stock'])) { ?>
					<span class="stock_status <?php if (!empty($do_not_show_left_aside_in_list)) { ?>stock_status_colection<?php } ?>">
						<?php echo $text_ask_about_price_and_stock; ?>
					</span>			
				<?php } elseif (empty($product['can_not_buy'])) { ?>
					<div class="product__btn-cart">
						<button onclick="addToCart('<?php echo $product['product_id']; ?>', parseInt($('#htop_<?php echo $product['product_id']; ?>').val()));">
							Додати до кошика
						</button>
					</div>
				<?php } else { ?>
					<span class="stock_status <?php if (!empty($do_not_show_left_aside_in_list)) { ?>stock_status_colection<?php } ?>">
						<? echo $product['stock_status'] ?>
					</span>
				<?php } ?>
				

			</div>
			
		</div>
		<!--/product__bottom-->
	</div>
<?php } ?>								