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
		<!--product__photo-->
		<div class="product__photo">
			<a href="<? echo $product['href']; ?>" title="<?php echo strip_tags($product['name']); ?>">
				<img loading="lazy" width="265" height="265" src="<?php echo $product['thumb']; ?>" title="<?php echo strip_tags($product['name']); ?>" alt="<?php echo strip_tags($product['name']); ?>">
			</a>
			<div class="product__label">
			
			<? /*	
				<?php if ($product['active_actions']) { ?>
					<?php foreach ($product['active_actions'] as $active_action) { ?>
						<?php if ($active_action['label']) { ?>
							<div class="product__label-hit" style="background-color:#<?php echo $active_action['label_background']; ?>; color:<?php echo $active_action['label_color']; ?>" <?php if ($active_action['label_text']) { ?>data-tooltip="<?php echo $active_action['label_text']; ?>"<? } ?>><?php echo $active_action['label']; ?>
							</div>
						<?php } ?>
					<?php } ?>					
				<?php } ?>
			*/ ?>

			<?php if ($product['has_video']) { ?>
					<div class="product__label-stock" data-tooltip="<?php echo $text_product_has_video; ?>">
						<svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M16.5 3.69853C16.5 3.24417 16.5 3.01699 16.4102 2.91179C16.3322 2.82051 16.2152 2.77207 16.0956 2.78149C15.9577 2.79234 15.797 2.95298 15.4757 3.27426L12.75 6L15.4757 8.72574C15.797 9.04702 15.9577 9.20766 16.0956 9.21851C16.2152 9.22793 16.3322 9.17949 16.4102 9.08821C16.5 8.98301 16.5 8.75583 16.5 8.30147V3.69853Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M1.5 4.35C1.5 3.08988 1.5 2.45982 1.74524 1.97852C1.96095 1.55516 2.30516 1.21095 2.72852 0.995235C3.20982 0.75 3.83988 0.75 5.1 0.75H9.15C10.4101 0.75 11.0402 0.75 11.5215 0.995235C11.9448 1.21095 12.289 1.55516 12.5048 1.97852C12.75 2.45982 12.75 3.08988 12.75 4.35V7.65C12.75 8.91012 12.75 9.54018 12.5048 10.0215C12.289 10.4448 11.9448 10.789 11.5215 11.0048C11.0402 11.25 10.4101 11.25 9.15 11.25H5.1C3.83988 11.25 3.20982 11.25 2.72852 11.0048C2.30516 10.789 1.96095 10.4448 1.74524 10.0215C1.5 9.54018 1.5 8.91012 1.5 7.65V4.35Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
				<? } ?>
				<?php if ($product['rating'] == 5 && $product['count_reviews'] >= 4) { ?>
					<div class="product__label-hit" data-tooltip="<?php echo $text_product_has_good_rating; ?>">
						<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5.25 16.5H3C2.60218 16.5 2.22064 16.342 1.93934 16.0607C1.65804 15.7794 1.5 15.3978 1.5 15V9.75C1.5 9.35218 1.65804 8.97064 1.93934 8.68934C2.22064 8.40804 2.60218 8.25 3 8.25H5.25M10.5 6.75V3.75C10.5 3.15326 10.2629 2.58097 9.84099 2.15901C9.41903 1.73705 8.84674 1.5 8.25 1.5L5.25 8.25V16.5H13.71C14.0717 16.5041 14.4228 16.3773 14.6984 16.143C14.9741 15.9087 15.1558 15.5827 15.21 15.225L16.245 8.475C16.2776 8.26002 16.2631 8.04051 16.2025 7.83169C16.1419 7.62287 16.0366 7.42972 15.8939 7.26564C15.7512 7.10155 15.5746 6.97045 15.3762 6.88141C15.1778 6.79238 14.9624 6.74754 14.745 6.75H10.5Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
				<? } ?>
				<?php if ($product['current_in_stock']) { ?>
					<div class="product__label-best-price" data-tooltip="<?php echo $text_product_current_in_stock; ?>">
						<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M9 11.2502L6.75 9.00019M9 11.2502C10.0476 10.8517 11.0527 10.3492 12 9.75019M9 11.2502V15.0002C9 15.0002 11.2725 14.5877 12 13.5002C12.81 12.2852 12 9.75019 12 9.75019M6.75 9.00019C7.14911 7.96476 7.65165 6.97223 8.25 6.03769C9.12389 4.64043 10.3407 3.48997 11.7848 2.69575C13.2288 1.90154 14.852 1.48996 16.5 1.50019C16.5 3.54019 15.915 7.12519 12 9.75019M6.75 9.00019H3C3 9.00019 3.4125 6.72769 4.5 6.00019C5.715 5.19019 8.25 6.00019 8.25 6.00019M3.375 12.3752C2.25 13.3202 1.875 16.1252 1.875 16.1252C1.875 16.1252 4.68 15.7502 5.625 14.6252C6.1575 13.9952 6.15 13.0277 5.5575 12.4427C5.26598 12.1644 4.88197 12.0037 4.47917 11.9912C4.07637 11.9787 3.68316 12.1155 3.375 12.3752Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
				<? } ?>
				
				<?php if ($product['bestseller']) { ?>
					<div class="product__label-hit"><?php echo $label_sales_hit; ?></div>
				<? } ?>
				<?php if ($product['new']) { ?>
					<div class="product__label-new"><?php echo $label_sales_new; ?></div>
				<? } ?>
				<? /* if ($product['special']) { ?>
					<div class="product__label-stock">Акция</div>
					<? } ?>
					
					<?  ?>
					<div class="product__label-best-price">Лучшая цена</div>
				<? */ ?>
			</div>
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
			<div class="product__rating">
				<?php if ($this->config->get('config_review_status')) { ?>
					<span class="rate rate-<?php echo $product['rating']; ?>"></span>
					<?php if ($product['rating'] >= 1) { ?>
						<a href="<? echo $product['href']; ?>#reviews-col"><span class="count_reviews"><i class="far fa-comment-alt"></i><?php echo $product['count_reviews']; ?></span></a>
					<?php } ?>					
				<?php } ?>
			</div>
			<div class="product__title">
				<a href="<? echo $product['href']; ?>" title="<?php echo strip_tags($product['name']); ?>" class="prod_name"><? echo $product['name']; ?></a>				
				<? if (!empty($product['search_sku'])) { ?><span class="product__sku"><small style="font-size:80%"><? echo $product['search_sku']; ?></small></span><? } ?>
				
				</div>
				 <?php  if (!$product['need_ask_about_stock'] && !$product['can_not_buy']) { ?>  

					<?php if ($product['special']) { ?>
						<div class="price__sale">-<?php echo $product['saving']; ?>%</div>			
					<?php } ?>
				<?php } ?>
			</div>
			<!--/product__middle-->
			<!--product__bottom-->
				<div class="product__bottom">
				<!-- <div class="price__sale">-X%</div> -->
				<div class="product_add-favorite">
				<?php if ($this->config->get('show_wishlist') == '1') { ?>
					<?php if (empty($logged)) { ?>	
						<button onclick="addToWishList('<?php echo $product['product_id']; ?>');" title="<?php $button_push_to_wishlist; ?>"  class="logged">
							<svg width="40" height="35" viewbox="0 0 40 35" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M35.2241 4.74063C34.3444 3.87177 33.3 3.18253 32.1505 2.71228C31.001 2.24204 29.7689 2 28.5246 2C27.2803 2 26.0482 2.24204 24.8987 2.71228C23.7492 3.18253 22.7047 3.87177 21.8251 4.74063L19.9995 6.54297L18.174 4.74063C16.3972 2.98641 13.9873 2.00091 11.4745 2.00091C8.9617 2.00091 6.55183 2.98641 4.77502 4.74063C2.9982 6.49484 2 8.87405 2 11.3549C2 13.8357 2.9982 16.2149 4.77502 17.9691L6.60058 19.7715L19.9995 33L33.3985 19.7715L35.2241 17.9691C36.1041 17.1007 36.8022 16.0696 37.2785 14.9347C37.7548 13.7998 38 12.5833 38 11.3549C38 10.1264 37.7548 8.90999 37.2785 7.7751C36.8022 6.6402 36.1041 5.60908 35.2241 4.74063V4.74063Z" stroke="#51A881" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
							</svg>
							<span><?php $button_push_to_wishlist; ?></span> 
						</button>
					<?php } else { ?>
						<button onclick="showRegisterModal(this);" title="<?php $button_push_to_wishlist; ?>" class="Nologged">
							<svg width="40" height="35" viewbox="0 0 40 35" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M35.2241 4.74063C34.3444 3.87177 33.3 3.18253 32.1505 2.71228C31.001 2.24204 29.7689 2 28.5246 2C27.2803 2 26.0482 2.24204 24.8987 2.71228C23.7492 3.18253 22.7047 3.87177 21.8251 4.74063L19.9995 6.54297L18.174 4.74063C16.3972 2.98641 13.9873 2.00091 11.4745 2.00091C8.9617 2.00091 6.55183 2.98641 4.77502 4.74063C2.9982 6.49484 2 8.87405 2 11.3549C2 13.8357 2.9982 16.2149 4.77502 17.9691L6.60058 19.7715L19.9995 33L33.3985 19.7715L35.2241 17.9691C36.1041 17.1007 36.8022 16.0696 37.2785 14.9347C37.7548 13.7998 38 12.5833 38 11.3549C38 10.1264 37.7548 8.90999 37.2785 7.7751C36.8022 6.6402 36.1041 5.60908 35.2241 4.74063V4.74063Z" stroke="#51A881" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
							</svg>
						<span><?php $button_push_to_wishlist; ?></span> </button>
					<?php } ?>	
				<?php } ?>		
					
				<?php if ($this->config->get('show_compare') == '1')  { ?>
					<button class="addToCompareBtn" onclick="addToCompare('<?php echo $product['product_id']; ?>');" title="<?php $button_push_to_compare; ?>">
						<i class="icons compare_icompare"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
						<span><?php $button_push_to_compare; ?></span> 
					</button>
				<?php } ?>
			</div>
			<?php if (!empty($product['need_ask_about_stock'])) { ?>
				<span class="stock_status <?php if (!empty($do_not_show_left_aside_in_list)) { ?>stock_status_colection<?php } ?>"><?php echo $text_ask_about_price_and_stock; ?></span>			
				<?php } elseif (empty($product['can_not_buy'])) { ?>
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
			
			<?php if (empty($product['need_ask_about_stock']) && empty($product['can_not_buy'])) { ?>
				<div class="product__info">
					<div class="product__line">
						<?php if ($product['price']) { ?>
							<div class="product__price">
								<?php if (!$product['special']) { ?>
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
					</div>
				<? /*	
					<?php if (!empty($product['active_coupon'])) { ?>
						<div class="product__line__promocode">	
							<span><?php echo $product['active_coupon']['coupon_price']; ?></span>
							<span class="product__line__promocode__text"><?php echo $text_promocode_price;?></span>
							<span class="product__line__promocode__code"><?php echo $product['active_coupon']['code']; ?></span>
						</div>
					<?php } ?>
				*/ ?>
					
					<div class="product__delivery">					
						<?php if (file_exists(dirname(__FILE__).'/delivery/delivery_terms.'. $this->config->get('config_language') .'.tpl')) { ?>
							<?php include($this->checkTemplate(dirname(__FILE__),'/delivery/delivery_terms.'. $this->config->get('config_language') .'.tpl')); ?>
							<?php } else { ?>
							<?php include($this->checkTemplate(dirname(__FILE__),'/delivery_terms.tpl')); ?>
						<?php } ?>
					</div>
					<? if ($product['points']) { ?>
						<div class="reward_wrap">
							<span class="text"><?php echo $product['points']; ?></span>
						</div>
					<? } ?>
				</div>
			<?php } ?>
			
		</div>
		<!--/product__bottom-->
	</div>
<?php } ?>								