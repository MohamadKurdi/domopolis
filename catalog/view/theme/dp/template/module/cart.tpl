	<button id="cart-btn" class="cart" onClick="openCart();"> 
	    <i><?php if ($text_items > 99) { ?>99<b>+</b><?php } else { echo $text_items; }?></i>
	    <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M14.9986 6C14.9986 7.06087 14.5772 8.07828 13.827 8.82843C13.0769 9.57857 12.0595 10 10.9986 10C9.93775 10 8.92033 9.57857 8.17018 8.82843C7.42004 8.07828 6.99861 7.06087 6.99861 6M2.63183 5.40138L1.93183 13.8014C1.78145 15.6059 1.70626 16.5082 2.0113 17.2042C2.2793 17.8157 2.74364 18.3204 3.3308 18.6382C3.99908 19 4.90447 19 6.71525 19H15.282C17.0928 19 17.9981 19 18.6664 18.6382C19.2536 18.3204 19.7179 17.8157 19.9859 17.2042C20.291 16.5082 20.2158 15.6059 20.0654 13.8014L19.3654 5.40138C19.236 3.84875 19.1713 3.07243 18.8275 2.48486C18.5247 1.96744 18.0739 1.5526 17.5331 1.29385C16.919 1 16.14 1 14.582 1L7.41525 1C5.85724 1 5.07823 1 4.46413 1.29384C3.92336 1.5526 3.47251 1.96744 3.16974 2.48486C2.82591 3.07243 2.76122 3.84875 2.63183 5.40138Z" stroke="#404345" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
	</button>
  	<div class="content pullDown">
	    <?php if ($products || $vouchers) { ?>
	    	
	    	<?php  $countProduct = preg_replace("/[^0-9]/", '', $text_items); ?>
	    	<?php if ($countProduct <= 2) { ?>
				<span class="title">Ваші товари в кошику:</span>
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
								<td class="quantity" style="display: none;">x&nbsp;<?php echo $product['quantity']; ?></td>
								<td class="total" style="white-space:nowrap;display: none;">
									
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
								<td class="quantity" style="display: none;">x&nbsp;1</td>
								<td class="total" style="display: none;"><?php echo $voucher['amount']; ?></td>
								<td class="remove 1"><img src="catalog/view/theme/default/image/remove-small.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" onclick="$('#header-small-cart').load('index.php?route=module/cart&remove=<?php echo $product['key']; ?>');<?php if ($route == 'checkout') { ?>simplecheckout_reload('product_removed');window.location.reload();<?php } ?>" /></td>
							</tr>
						<?php } ?>
					</table>
				</div>
				<?php } else { ?>

				<span><?php echo $text_in_cart; ?> <?php echo $text_items; ?> <?php echo $products_pluralized; ?></span>
				
				
			<?php } ?>
		    <div class="mini-cart-total" style="display: none;">
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
