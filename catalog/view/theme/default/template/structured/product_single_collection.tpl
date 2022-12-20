<!--product__item-->
<div class="product__item <?php if (!empty($page_type) && $page_type == 'collection'){ ?>tpl_list<?php } ?>">
	<!--product__photo-->
	<div class="product__photo">
		<a href="<? echo $product['href']; ?>" title="<? echo $product['name']; ?>">
			<img loading="lazy" width="224" height="229" src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<? echo $product['name']; ?>">
		</a>
		<div class="product__label">
			
			<?php if ($product['active_actions']) { ?>
				
				<?php foreach ($product['active_actions'] as $active_action) { ?>
					<?php if ($active_action['label']) { ?>
						<div class="product__label-hit" style="background-color:#<?php echo $active_action['label_background']; ?>; color:<?php echo $active_action['label_color']; ?>" <?php if ($active_action['label_text']) { ?>data-tooltip="<?php echo $active_action['label_text']; ?>"<? } ?>><?php echo $active_action['label']; ?></div>
					<?php } ?>
				<?php } ?>
				
			<?php } ?>
			
			<?php if ($product['bestseller']) { ?>
				<div class="product__label-hit"><?php echo $label_sales_hit; ?></div>
			<? } ?>
			<?php if ($product['new']) { ?>
				<div class="product__label-new"><?php echo $label_sales_new; ?></div>
			<? } ?>
			<?php/* if ($product['special']) { ?>
				<div class="product__label-stock">Акция</div>
				<? } ?>
				
				<?  ?>
				<div class="product__label-best-price">Лучшая цена</div>
			<? */ ?>
		</div>
	</div>
	<!--/product__photo-->
	<div class="product__middle product__middle__colection">
		
		<?php if(!empty($product['rating'])) {?>
			<?php if ($this->config->get('config_review_status')) { ?>
				<div class="product__rating">
					<span class="rate rate-<?php echo $product['rating']; ?>"></span>
					<?php if ($product['rating'] >= 1) { ?>
						<a href="<? echo $product['href']; ?>#reviews-col"><span class="count_reviews"><i class="far fa-comment-alt"></i><?php echo $product['count_reviews']; ?></span></a>
					<?php } ?>
				</div>
			<?php } ?>
		<?php } ?>
		<? if ($product['points']) { ?>
			<div class="reward_wrap">
				<span class="text"><?php echo $product['points']; ?></span>
				<div class="prompt">
					<p><?php echo $text_bonus1; ?></p>
					<ul>
						<li><?php echo $text_bonus2; ?></li>
						<li><?php echo $text_bonus3; ?></li>
						<li><?php echo $text_bonus4; ?></li>
					</ul>
				</div>
			</div>
		<? } ?>
		<div class="product__title">
			<a href="<? echo $product['href']; ?>" title="<? echo $product['name']; ?>"><? echo $product['name']; ?></a>
		</div>
		<div class="delivery_mobile">
			<?php if (file_exists(dirname(__FILE__).'/delivery/delivery_terms.'. $this->config->get('config_language') .'.tpl')) { ?>
				<?php include($this->checkTemplate(dirname(__FILE__),'/delivery/delivery_terms.'. $this->config->get('config_language') .'.tpl')); ?>
				<?php } else { ?>
				<?php include($this->checkTemplate(dirname(__FILE__),'/delivery_terms.tpl')); ?>
			<?php } ?>
		</div>
		<?php if ($product['special']) { ?>
			<div class="price__sale <?php if (!empty($product['active_coupon'])) { ?>active_coupon_empty<?php } ?>">-<?php echo $product['saving']; ?>%</div>
		<?php } ?>
		<div class="product-info__code">
			<span><?php echo $text_model; ?> <?php echo $product['sku']; ?></span>
		</div>
	</div>
	<?php if (!empty($product['active_coupon'])) { ?>
		<div class="product__line__promocode hidden">
			<span><?php echo $product['active_coupon']['coupon_price']; ?></span>
			<span class="product__line__promocode__text"><?php echo $text_promocode_price;?></span>
			<span class="product__line__promocode__code"><?php echo $product['active_coupon']['code']; ?></span>
		</div>
	<?php } ?>

	<div class="product__delivery">
		<?php if (file_exists(dirname(__FILE__).'/delivery/delivery_terms.'. $this->config->get('config_language') .'.tpl')) { ?>
			<?php include($this->checkTemplate(dirname(__FILE__),'/delivery/delivery_terms.'. $this->config->get('config_language') .'.tpl')); ?>
			<?php } else { ?>
			<?php include($this->checkTemplate(dirname(__FILE__),'/delivery_terms.tpl')); ?>
		<?php } ?>
	</div>
	<?php if ($product['price']) { ?>
		<div class="product__price">
			<?php if ($product['can_not_buy']) { ?>
				<?php } elseif (!$product['special']) { ?>
				<div class="product__price-new"><?php echo $product['price']; ?></div>
				<?php } else { ?>
				<div class="product__price-new">
					<?php echo $product['special']; ?>
				</div>
				<div class="product__price-old">
					<?php echo $product['price']; ?>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
	<!--product__bottom-->
	<div class="product__bottom">
		<?php if ($this->config->get('show_wishlist') == '1') { ?>
			<div class="product_add-favorite">
				<button onclick="addToWishList('<?php echo $product['product_id']; ?>');" title="<?php echo $button_push_to_wishlist; ?>">
					<svg width="40" height="35" viewbox="0 0 40 35" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M35.2241 4.74063C34.3444 3.87177 33.3 3.18253 32.1505 2.71228C31.001 2.24204 29.7689 2 28.5246 2C27.2803 2 26.0482 2.24204 24.8987 2.71228C23.7492 3.18253 22.7047 3.87177 21.8251 4.74063L19.9995 6.54297L18.174 4.74063C16.3972 2.98641 13.9873 2.00091 11.4745 2.00091C8.9617 2.00091 6.55183 2.98641 4.77502 4.74063C2.9982 6.49484 2 8.87405 2 11.3549C2 13.8357 2.9982 16.2149 4.77502 17.9691L6.60058 19.7715L19.9995 33L33.3985 19.7715L35.2241 17.9691C36.1041 17.1007 36.8022 16.0696 37.2785 14.9347C37.7548 13.7998 38 12.5833 38 11.3549C38 10.1264 37.7548 8.90999 37.2785 7.7751C36.8022 6.6402 36.1041 5.60908 35.2241 4.74063V4.74063Z" stroke="#51A881" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
					</svg>
				<span><?php $button_push_to_wishlist; ?></span> </button>
				<button class="addToCompareBtn" onclick="addToCompare('<?php echo $product['product_id']; ?>');" title="<?php $button_push_to_compare; ?>">
					<i class="icons compare_icompare"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
					<span><?php $button_push_to_compare; ?></span> 
				</button>
			</div>
		<?php } ?> 
		<?php if($product['need_ask_about_stock']) { ?>
			<span class="stock_status <?php if (!empty($do_not_show_left_aside_in_list)) { ?>stock_status_colection<?php } ?>"><?php echo $text_ask_about_price_and_stock; ?></span>			
			<?php } elseif (!$product['can_not_buy']) { ?>
			<div class="product__btn-cart">
				<div class="number__product__block">
					<input id="htop_<?php echo $product['product_id']; ?>" type="text" name="quantity_<?php echo $product['product_id']; ?>" value="<?php echo !empty($product['minimum'])?(int)$product['minimum']:1; ?>" class="input_number htop" data-minimum="<?php echo !empty($product['minimum'])?(int)$product['minimum']:1; ?>" />
					
					<div class="number_controls">
						<div class="nc-plus"><i class="fas fa-angle-up"></i></div>
					    <div class="nc-minus"><i class="fas fa-angle-down"></i></div>
					</div>	
				</div>
				<button onclick="addToCart('<?php echo $product['product_id']; ?>', parseInt($('#htop_<?php echo $product['product_id']; ?>').val()));">
					<svg width="26" height="25" viewbox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1.19141 1.33936H5.38999L8.20304 15.6922C8.29902 16.1857 8.56192 16.629 8.94571 16.9445C9.3295 17.26 9.80973 17.4276 10.3023 17.418H20.5049C20.9975 17.4276 21.4777 17.26 21.8615 16.9445C22.2453 16.629 22.5082 16.1857 22.6042 15.6922L24.2836 6.6989H6.43963M10.6382 22.7775C10.6382 23.3695 10.1683 23.8495 9.58857 23.8495C9.00887 23.8495 8.53893 23.3695 8.53893 22.7775C8.53893 22.1855 9.00887 21.7056 9.58857 21.7056C10.1683 21.7056 10.6382 22.1855 10.6382 22.7775ZM22.1843 22.7775C22.1843 23.3695 21.7144 23.8495 21.1347 23.8495C20.555 23.8495 20.085 23.3695 20.085 22.7775C20.085 22.1855 20.555 21.7056 21.1347 21.7056C21.7144 21.7056 22.1843 22.1855 22.1843 22.7775Z" stroke="#333333" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
					</svg>
					<span><?php echo $button_add_to_cart;?></span>			
				</button>
			</div>			
			<?php } else { ?>
			<span class="stock_status <?php if (!empty($do_not_show_left_aside_in_list)) { ?>stock_status_colection<?php } ?>"><? echo $product['stock_status'] ?></span>
		<?php } ?>
	</div>
	<!--/product__bottom-->
</div>
<!--/product__item