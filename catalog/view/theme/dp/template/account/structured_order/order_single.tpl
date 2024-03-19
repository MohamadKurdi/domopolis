<div class="order_item completed">

	<?php if ($order['status_bg_color']) { ?>
		<style>
			.accordion_list_order .order_item.completed .head.color-<?php echo $order['status_bg_color']?>::before{
				background-color: #<?php echo $order['status_bg_color']?>!important;
			}
		</style>
	<?php } ?>

	<div class="head color-<?php echo $order['status_bg_color']?>">									
		<div class="about_order">
			<?php if ($order['preorder']) { ?>
				<span class="number">№ <?php echo $order['order_id']; ?> <?php echo $text_date_from; ?> <?php echo $order['date_added']; ?></span>
			<?php } else { ?>
				<span class="number">№ <?php echo $order['order_id']; ?> <?php echo $text_date_from; ?> <?php echo $order['date_added']; ?></span>
			<?php } ?>

			<span class="order-status"><b><?php echo $order['status']; ?></b></span>

			<?php if ($order['order_is_delivering'] && $order['tracking_code'] && $order['tracking_info']) { ?>
				<span class="order-status">
					<?php echo $order['tracking_code']?> 
					<i class="fas fa-info-circle tracking-click" data-tracking-code="<? echo $order['tracking_code']; ?>" data-shipping-code="<?php echo $order['shipping_code']; ?>" data-shipping-phone="<?php echo $order['telephone']; ?>" ></i><span id="ttninfo"></span>
				</span>

				<?php if ($order['tracking_info']) { ?>
					<span class="order-status"><?php echo $order['tracking_info']['tracking_status'];?></span>
				<?php } ?>
			<?php } ?>		
		</div>
		<div class="tottal_order">
			<p class="text"><?php echo $text_sum_order; ?></p>
			<p class="value">
				<?php if ($order['total_national']) {?>
					<?php echo $order['total_national']?>
				<?php } else { ?>
					-
				<?php } ?>
			</p>
		</div>
		<div class="list_product">
			<?php foreach ($order['products'] as $product) { ?>
				<div class="product_item">
					<img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>"/>
				</div>
			<?php } ?>
		</div>
		<button class="btn_detail">
			<svg width="14" height="7" viewBox="0 0 14 7" fill="none" xmlns="https://www.w3.org/2000/svg">
				<path d="M1 1L7 6L13 1" stroke="#FFC34F" stroke-width="2" stroke-linejoin="round"></path>
			</svg>
		</button>
	</div>
	<div class="detail_order">
		<div class="order-content">
			<h4 class="title"><?php echo $text_order_detail; ?></h4>
			<div>
				<?php if ($order['preorder']) { ?>
					<p class="text"><?php echo $text_date_added_preorder; ?></p> <p class="value"><?php echo $order['date_added']; ?></p> 
				<?php } else { ?>
					<p class="text"><?php echo $text_date_added; ?></p> <p class="value"><?php echo $order['date_added']; ?></p> 
				<?php } ?>				
			</div>
			<div>
				<p class="text"><?php echo $text_products; ?></p> <p class="value"><?php echo $order['product_count']; ?></p>
			</div>
			<div>
				<p class="text"><?php echo $text_customer; ?></p> <p class="value"><?php echo $order['name']; ?></p>
			</div>

			<?php if ($order['receipt_links']) { ?>
				<div>
					<p class="text"><?php echo $text_receipt; ?></p> 
						<p class="value">
							<?php if (!empty($order['receipt_links']['PDF'])) { ?>
								<a target="_blank" href="<?php echo $order['receipt_links']['PDF']['link']; ?>"><i class="fa fa-file-pdf"></i> <?php echo $text_view_receipt; ?></a>
							<?php } ?>
						</p>
				</div>
			<?php } ?>

			<div class="order-info" style="display: none;">						
				<a class="button btn btn-acaunt" style="text-transform:none; font-weight:400;" href="<?php echo $order['href']; ?>">
					<i class="fas fa-external-link-alt" title="<?php echo $button_view; ?>"></i><?php echo $button_view; ?>
				</a>											
			</div>
		</div>				
		<div class="order-products-table">
			<div class="order-details__header">
				<h4><?php echo $text_product_list;?></h4>
			</div>
			<ul>
				<?php foreach ($order['products'] as $product) { ?>
					<li class="order-details_product">
						<a href="<?php echo $product['link']; ?>" target="_blank" title="<?php echo $product['name']; ?>">
							<div class="img-wrap">
								<img src="<?php echo $product['image']; ?>" />
							</div>
							<p><?php echo $product['name']; ?></p>							
						</a>	
						<div class="order-product-price">
							<div class="order-product-price_item model">
								<p class="text"><?php echo $column_model; ?></p>
								<p class="value">
									<?php echo $product['model']; ?>
								</p>
							</div>
							<div class="order-product-price_item">
								<p class="text"><?php echo $column_price; ?></p>
								<p class="value">
									<?php if ($order['preorder'] && $product['price_isnull']) { ?>
										-
									<?php } else { ?>
										<?php echo $product['price']; ?>
									<?php } ?>
								</p>
							</div>
							<div class="order-product-price_item count">
								<p class="text"><?php echo $column_quantity; ?></p>
								<p class="value"><?php echo $product['quantity']; ?></p>
							</div>
							<div class="order-product-price_item">
								<p class="text"><?php echo $text_sum; ?></p>
								<p class="value">
									<?php if ($order['preorder'] && $product['price_isnull']) { ?>
										-
									<?php } else { ?>
										<?php echo $product['total']; ?>
									<?php } ?>
								</p>
							</div>
							
						</div>
					</li>
				<?php } ?>
			</ul>
			<div class="total_wrap">
				<div class="total_item">
					<div class="text"><p><?php echo $text_payment_method; ?></p></div>
					<div class="value"><p><?php if ($order['payment_method']) { ?><?php echo $order['payment_method']; ?><? } else { ?>-<?php } ?></p></div>
				</div>
				<div class="total_item">
					<div class="text"><p><?php echo $text_status; ?></p></div>
					<div class="value"><p><?php echo $order['status']?></p></div>
				</div>
				<div class="total_item">
					<div class="text"><p><?php echo $text_shipping_method; ?></p></div>
					<div class="value"><p><?php if ($order['shipping_method']) { ?><?php echo $order['shipping_method']; ?><? } else { ?>-<?php } ?></p></div>
				</div>
				<div class="total_item">
					<div class="text"><p><?php echo $text_total; ?></p></div>
					<div class="value"><p><?php if ($order['total_national']) {?>
						<?php echo $order['total_national']?>
					<?php } else { ?>
						-
						<?php } ?></p></div>
					</div>

				</div>
			</div>
		</div>

	</div>