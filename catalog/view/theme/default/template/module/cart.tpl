	<button id="cart-btn" class="cart" onClick="openCart();"> 
	    <i><?php echo $text_items; ?></i>
	    <svg width="40" height="36" viewBox="0 0 40 36" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M12.8876 24.0003H12.8894C12.891 24.0003 12.8925 24 12.894 24H34.1407C34.6638 24 35.1237 23.6447 35.2674 23.1297L39.9549 6.32969C40.0559 5.9675 39.9851 5.57813 39.7639 5.2775C39.5423 4.97688 39.1959 4.8 38.8282 4.8H10.185L9.34725 0.939688C9.22792 0.390625 8.75246 0 8.20314 0H1.17188C0.524598 0 0 0.537188 0 1.2C0 1.86281 0.524598 2.4 1.17188 2.4H7.2632C7.41151 3.08406 11.272 20.8734 11.4942 21.8969C10.2487 22.4513 9.37502 23.7228 9.37502 25.2C9.37502 27.185 10.9522 28.8 12.8906 28.8H34.1407C34.788 28.8 35.3126 28.2628 35.3126 27.6C35.3126 26.9372 34.788 26.4 34.1407 26.4H12.8906C12.2446 26.4 11.7188 25.8616 11.7188 25.2C11.7188 24.5394 12.2428 24.0019 12.8876 24.0003ZM37.2745 7.2L33.2566 21.6H13.8306L10.7056 7.2H37.2745Z" fill="#51A881"/>
			<path d="M11.7188 32.4C11.7188 34.385 13.2959 36 15.2344 36C17.1729 36 18.75 34.385 18.75 32.4C18.75 30.415 17.1729 28.8 15.2344 28.8C13.2959 28.8 11.7188 30.415 11.7188 32.4ZM15.2344 31.2C15.8805 31.2 16.4063 31.7384 16.4063 32.4C16.4063 33.0616 15.8805 33.6 15.2344 33.6C14.5883 33.6 14.0625 33.0616 14.0625 32.4C14.0625 31.7384 14.5883 31.2 15.2344 31.2Z" fill="#51A881"/>
			<path d="M28.2813 32.4C28.2813 34.385 29.8584 36 31.7969 36C33.7354 36 35.3125 34.385 35.3125 32.4C35.3125 30.415 33.7354 28.8 31.7969 28.8C29.8584 28.8 28.2813 30.415 28.2813 32.4ZM31.7969 31.2C32.443 31.2 32.9688 31.7384 32.9688 32.4C32.9688 33.0616 32.443 33.6 31.7969 33.6C31.1509 33.6 30.625 33.0616 30.625 32.4C30.625 31.7384 31.1509 31.2 31.7969 31.2Z" fill="#51A881"/>
		</svg>
	</button>
  	<div class="content pullDown">
	    <?php if ($products || $vouchers) { ?>
	    	
	    	<?php  $countProduct = preg_replace("/[^0-9]/", '', $text_items); ?>
	    	<?php if ($countProduct <= 2) { ?>
				
			    <div class="mini-cart-info">
					<table>
						<?php foreach ($products as $product) { ?>
							<tr>
								<td class="image"><?php if ($product['thumb']) { ?>
									<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
								<?php } ?></td>
								<td class="name">
									<? if ($product['is_special_offer']) { ?>
										<span style="color:red; font-weight:700;font-size: 14px;margin-bottom: 2px;">
											<? if ($product['is_special_offer_present']) { ?>
												<?=$text_gift ?>!
											<? } else { ?>
												<?=$text_special_offer ?>!
											<? } ?>
										</span><br />
									<? } ?>
									<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
									<div>
										<?php foreach ($product['option'] as $option) { ?>
											- <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
										<?php } ?>
									</div>
									<?php if ($product['childProductArray']): ?>
									<small><?=$text_product_in_set ?>:</small>
									<br/>
									<?php foreach ($product['childProductArray'] as $childProduct): ?>
									- <small><?=$childProduct['quantity'] ?> x <?=$childProduct['name'] ?></small><br/>
									<?php endforeach; ?>
									
									<?php endif; ?>
								</td>
								<td class="quantity">x&nbsp;<?php echo $product['quantity']; ?></td>
								<td class="total" style="white-space:nowrap">
									
									<? /* if ($product['total_old_national'] && $product['saving']) { ?>
										<div class="price-old" id="price-old-<?=$product['key'] ?>"><?=$product['total_old_national']; ?></div>
										<div class="price-saving" id="price-saving-<?=$product['key'] ?>"><?=$product['saving']; ?>%</div>
									<? } */ ?>
									
									<?php echo $product['total_national']; ?>
									
								</td>
								<td class="remove 1"><img src="catalog/view/theme/default/image/remove-small.png" style="width: 10px; height: 10px;" ыalt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" onclick="$('#header-small-cart').load('index.php?route=module/cart&remove=<?php echo $product['key']; ?>');<?php if ($route == 'checkout') { ?>simplecheckout_reload('product_removed');window.location.reload();<?php } ?>" /></td>
							</tr>
						<?php } ?>
						<?php foreach ($vouchers as $voucher) { ?>
							<tr>
								<td class="image"></td>
								<td class="name"><?php echo $voucher['description']; ?></td>
								<td class="quantity">x&nbsp;1</td>
								<td class="total"><?php echo $voucher['amount']; ?></td>
								<td class="remove 1"><img src="catalog/view/theme/default/image/remove-small.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" onclick="$('#header-small-cart').load('index.php?route=module/cart&remove=<?php echo $product['key']; ?>');<?php if ($route == 'checkout') { ?>simplecheckout_reload('product_removed');window.location.reload();<?php } ?>" /></td>
							</tr>
						<?php } ?>
					</table>
				</div>
				<?php } else if ($countProduct >= 5) { ?>
				<span>В корзине <?php echo $text_items; ?> товаров</span>
				<?php } else if ($countProduct >= 3) { ?>
				<span>В корзине <?php echo $text_items; ?> товара</span>
				
			<?php } ?>
		    <div class="mini-cart-total">
				<table>
					<?php foreach ($totals as $total) { ?>
						<tr>
							<td class="right"><?php echo $total['title']; ?>: </td>
							<td class="right" style="white-space:nowrap"><?php echo $total['text']; ?></td>
						</tr>
					<?php } ?>
				</table>
			</div>
	    	<div class="checkout">
	    		<button onClick="openCart();" class="btn-cart"><?php echo $text_cart; ?></button><a href="<?php echo $checkout; ?>" class="btn btn-acaunt"><?php echo $text_checkout; ?></a>
			</div>
			<?php } else { ?>
	    	<div class="empty">
	    		<?=$text_text_empty ?>
	    		<p><?php echo $text_view_sales; ?></p>
			</div>
		<?php } ?>
	</div>

<script>
	if (navigator.setAppBadge) {
		console.log("[PWA] The App Badging API is supported!");
		
		<?php if ($total_quantity > 0) { ?>
			navigator.setAppBadge(<?php echo (int)$total_quantity; ?>).then(() => {
				console.log("[PWA] The badge was added");
				}).catch(e => {
				console.error("[PWA] Error displaying the badge", e);
			});
			<?php } else { ?>
			console.log("[PWA] Clearing App badge");
			navigator.clearAppBadge();
		<?php } ?>
		
		} else {
		console.log("[PWA] The App Badging API is not supported!");
	}
</script>
