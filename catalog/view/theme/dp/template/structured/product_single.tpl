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
					<div class="product__label-stock has_video" data-tooltip="<?php echo $text_product_has_video; ?>" onclick="window.location='<? echo $product['href']; ?>#photo_btn'">
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
					<div class="product__label-bestseller" data-tooltip="<?php echo $label_sales_hit; ?>">
						<svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M6 7C7.5 4.78 6 1.75 5.25 1C5.25 3.2785 3.92025 4.55575 3 5.5C2.0805 6.445 1.5 7.93 1.5 9.25C1.5 10.4435 1.97411 11.5881 2.81802 12.432C3.66193 13.2759 4.80653 13.75 6 13.75C7.19347 13.75 8.33807 13.2759 9.18198 12.432C10.0259 11.5881 10.5 10.4435 10.5 9.25C10.5 8.101 9.708 6.295 9 5.5C7.6605 7.75 6.90675 7.75 6 7Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>		
					</div>
				<? } ?>
				<?php if ($product['new']) { ?>
					<div class="product__label-new" data-tooltip="<?php echo $label_sales_new; ?>">
						NEW						
					</div>
				<? } ?>
				<? /* if ($product['special']) { ?>
					<div class="product__label-stock">Акция</div>					
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
			<?php if (!empty($product['need_ask_about_stock'])) { ?>
			<?php } elseif (empty($product['can_not_buy'])) { ?>
				<? if ($product['points']) { ?>
					<div class="reward_wrap">
						<span class="icon">
							<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect width="18" height="18" rx="9" fill="#BFEA43"/>
								<g clip-path="url(#clip0_115_2364)">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M9.83398 4.83333C9.83398 4.64509 9.70777 4.48023 9.52606 4.4311C9.34434 4.38198 9.15226 4.46079 9.05741 4.62338L6.14074 9.62338C6.06557 9.75225 6.06503 9.91148 6.13934 10.0409C6.21365 10.1702 6.35146 10.25 6.50065 10.25H8.16732V13.1667C8.16732 13.3549 8.29353 13.5198 8.47524 13.5689C8.65696 13.618 8.84904 13.5392 8.94389 13.3766L11.8606 8.37661C11.9357 8.24774 11.9363 8.08851 11.862 7.95914C11.7877 7.82977 11.6498 7.75 11.5007 7.75H9.83398V4.83333Z" fill="#121415"/>
								</g>
								<defs>
								<clipPath id="clip0_115_2364">
								<rect width="10" height="10" fill="white" transform="translate(4 4)"/>
								</clipPath>
								</defs>
							</svg>
						</span>
						<span class="text"><?php echo $product['points']; ?></span>
					</div>
				<? } ?>
				
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
		</div>
		<!--/product__middle-->
		<!--product__bottom-->
		<div class="product__bottom_new">
			<div class="product__bottom_top">
				<?php if (empty($product['need_ask_about_stock']) && empty($product['can_not_buy'])) { ?>
					<div class="product__info">
						<div class="product__line">
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
						</div>
					</div>
				<?php } ?>

				<?php if (!empty($product['need_ask_about_stock'])) { ?>
					<span class="stock_status <?php if (!empty($do_not_show_left_aside_in_list)) { ?>stock_status_colection<?php } ?>">
						<?php echo $text_ask_about_price_and_stock; ?>
					</span>			
				<?php } elseif (empty($product['can_not_buy'])) { ?>
					<div class="product__btn-cart">
						<button onclick="addToCart('<?php echo $product['product_id']; ?>', '1');">
							<svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M14.9986 6C14.9986 7.06087 14.5772 8.07828 13.827 8.82843C13.0769 9.57857 12.0595 10 10.9986 10C9.93775 10 8.92033 9.57857 8.17018 8.82843C7.42004 8.07828 6.99861 7.06087 6.99861 6M2.63183 5.40138L1.93183 13.8014C1.78145 15.6059 1.70626 16.5082 2.0113 17.2042C2.2793 17.8157 2.74364 18.3204 3.3308 18.6382C3.99908 19 4.90447 19 6.71525 19H15.282C17.0928 19 17.9981 19 18.6664 18.6382C19.2536 18.3204 19.7179 17.8157 19.9859 17.2042C20.291 16.5082 20.2158 15.6059 20.0654 13.8014L19.3654 5.40138C19.236 3.84875 19.1713 3.07243 18.8275 2.48486C18.5247 1.96744 18.0739 1.5526 17.5331 1.29385C16.919 1 16.14 1 14.582 1L7.41525 1C5.85724 1 5.07823 1 4.46413 1.29384C3.92336 1.5526 3.47251 1.96744 3.16974 2.48486C2.82591 3.07243 2.76122 3.84875 2.63183 5.40138Z" stroke="#404345" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
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