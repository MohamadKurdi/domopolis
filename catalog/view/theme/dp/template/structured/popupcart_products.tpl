	
		<?php foreach ($products as $product) { ?>
			<?php $op = explode(":", $product['key']); ?>
			<tr id="pid-<?=$product['key'] ?>" class="ajaxtable_tr_new">
				<?php $prod_id = preg_replace('/[^0-9]/', '',$product ['key']); ?>
				<td class="image" width="135px">
					<div>
					<?php if ($product['thumb']) { ?>						
						<img src="<?php echo $product['thumb']; ?>" class="colorbox-popup" title="<?php echo $product['name']; ?>" alt="<? echo $product['name']; ?>" loading="lazy">
					<?php } ?>
					<i class="fas fa-search-plus zoom-in-popap"></i>
					</div>
				</td>
				<td class="names_product">
					<a href="<?=$product['href'] ?>"><?php echo $product['name']; ?></a>
					<div class="product__rating">
						
						<span class="rate rate-<?php echo $product['rating']; ?>"></span>
						<?php if ($product['rating'] >= 1) { ?>
							<a href="<? echo $product['href']; ?>#reviews-col"><span class="count_reviews">(<?php echo $product['count_reviews']; ?>)</span></a>
						<?php } ?>	
					</div>


					<div class="product__price">
						
						<? if ($product['price_old'] && $product['saving']) { ?>
							<div class="product__price-old_wrap">
								<div id="price-old-<?=$product['key'] ?>" class="product__price-old">
									<?=$product['price_old']; ?>
								</div>
								<div class="price__sale" id="price-saving-<?=$product['key'] ?>"><?=$product['saving']; ?>%</div>	
							</div>
							<div id="price-<?=$product['key'] ?>" class="product__price-new">
								<?=$product['price']; ?>
							</div>
						<?php } else { ?>
							<div id="price-<?=$product['key'] ?>" class="product__price-new"><?=$product['price']; ?></div>	
						<?php } ?>

					</div>
					
				</td>
				<td class="quantity">
					<div>
						<input hidden class="product_id" value="<?=$product['key']; ?>" style="display:none;"/>
						<? if (!$product['is_special_offer']) { ?>	
							<a onclick="minus(this);" class="quantity-m">
								<svg width="14" height="2" viewBox="0 0 14 2" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M1.16602 1H12.8327" stroke="#696F74" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</a>
							<input value="<?php echo $product['quantity']; ?>" data-minimum="<?php echo !empty($product['minimum'])?(int)$product['minimum']:1; ?>" name="quainty" class="qt input_number" onchange="qtVal(this);" onchange="return validate(this); updateCart();" maxlength="4" onkeyup="return validate(this);" />
							<a onclick="plus(this);"  class="quantity-p">
								<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M6.99935 1.16669V12.8334M1.16602 7.00002H12.8327" stroke="#696F74" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</a>
							<? } else { ?>
							<span class="qt" style="font-size:14px"><?php echo $product['quantity']; ?></span>
							<input type="hidden" value="<?php echo $product['quantity']; ?>" name="quainty" maxlength="4" />
						<? } ?>
					</div>
					<?php if ($minimum > 1) { ?>
						<div class="minimum"><?php echo $minimum; ?></div>
					<?php } ?>
				</td>	
				<td class="remove">
					<input hidden class="product_id" value="<?=$product['key']; ?>" product-id="<?= $prod_id; ?>" style="display:none;"/>  
					<button title="<?php echo $button_remove; ?>" 
						onclick="delNew(this);">
						<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M11 1L1 11M1 1L11 11" stroke="#DDE1E4" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
				</td>						
			</tr>
			<!-- <tr class="clear"></tr> -->
		<?php } ?>	
