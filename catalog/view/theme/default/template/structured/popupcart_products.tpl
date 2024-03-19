<table id="ajaxtable_table" class="ajaxtable_table">
	<img src="/catalog/view/theme/default/img/Spinners.png" id="ajaxcartloadimg"/>
	
	<thead>
		<tr>
			<td colspan="5"></td>
			
		</tr>
	</thead>
	<tbody class="ajaxtable_tbody">
		
		<?php foreach ($products as $product) { ?>
			<?php $op = explode(":", $product['key']); ?>
			<tr id="pid-<?=$product['key'] ?>" class="ajaxtable_tr_new">
				<?php $prod_id = preg_replace('/[^0-9]/', '',$product ['key']); ?>
				<td class="select">
					<div class="checkbox">			 
						<input hidden class="product_id" value="<?=$product['key']; ?>" product-id="<?= $prod_id; ?>" style="display:none;"/>               
						<input type="checkbox" id="choose_<?=$product['key'] ?>" class="choose_input">
						<label for="choose_<?=$product['key'] ?>"></label>
					</div>
				</td>
				<td class="image">
					<?php if ($product['thumb']) { ?>
						
						<img src="<?php echo $product['thumb']; ?>" class="colorbox-popup" title="<?php echo $product['name']; ?>" alt="<? echo $product['name']; ?>" loading="lazy">
					<?php } ?>
					<i class="fas fa-search-plus zoom-in-popap"></i>
				</td>
				<td class="names_product">
					<? if ($product['is_special_offer']) { ?>
						<span style="color:red; font-weight:700;">
							<? if ($product['is_special_offer_present']) { ?>
								<?php echo $text_retranslate_11; ?>
							<? } else { ?>
								<?php echo $text_retranslate_12; ?>
							<? } ?>
						</span><br />
					<? } ?>
					<a href="<?=$product['href'] ?>"><?php echo $product['name']; ?></a>
					<div class="ajaxcartoptionname">
						<small><?php echo $text_retranslate_13; ?> <?php echo $product['model']; ?></small>	
					</div> 
					
					<?php if ($this->config->get('config_divide_cart_by_stock')) { ?>	
						<?php if ($product['is_certificate']) { ?>
							<span class="alert alert-success alert-no-padding"><?php echo $this->language->get('text_has_in_stock'); ?></span>
						<?php } elseif ($product['fully_in_stock']) { ?>
							<span class="alert alert-success alert-no-padding"><?php echo $this->language->get('text_has_in_stock'); ?> <?php echo $product['amount_in_stock']; ?> шт</span>
						<?php } elseif ($product['current_in_stock']) { ?>
							<span class="alert alert-warning alert-no-padding"><?php echo $this->language->get('text_has_in_stock'); ?> <?php echo $product['amount_in_stock']; ?> шт</span>
						<?php } else { ?>						
							<span class="alert alert-danger alert-no-padding"><?php echo $this->language->get('text_has_no_in_stock'); ?>, <?php echo $text_not_in_stock_delivery_term; ?></span>
						<?php } ?>
					<?php } ?>
					
				</td>
				<td class="price-block">								
					<? if ($product['price_old'] && $product['saving']) { ?>
						<div class="price-modal">
							<div class="price-old" id="price-old-<?=$product['key'] ?>"><?=$product['price_old']; ?> </div>
							<span class="price-saving" id="price-saving-<?=$product['key'] ?>"><?=$product['saving']; ?>%</span>
							<div class="value " id="price-<?=$product['key'] ?>"><?php if (!$product['amount_in_stock']) { ?><?php } ?><?=$product['price']; ?></div>
						</div>
					<? } else { ?>
						<div class="value " id="price-<?=$product['key'] ?>"><?php if (!$product['amount_in_stock']) { ?><?php } ?><?=$product['price']; ?></div>		
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
				</td>
				<td class="quantity">
					<div>
						<input hidden class="product_id" value="<?=$product['key']; ?>" style="display:none;"/>
						<? if (!$product['is_special_offer']) { ?>	
							<a onclick="minus(this);" class="quantity-m">-</a>
							<input value="<?php echo $product['quantity']; ?>" data-minimum="<?php echo !empty($product['minimum'])?(int)$product['minimum']:1; ?>" name="quainty" class="qt input_number" onchange="qtVal(this);" onchange="return validate(this); updateCart();" maxlength="4" onkeyup="return validate(this);" />
							<a onclick="plus(this);"  class="quantity-p">+</a>
						<? } else { ?>
							<span class="qt" style="font-size:14px"><?php echo $product['quantity']; ?></span>
							<input type="hidden" value="<?php echo $product['quantity']; ?>" name="quainty" maxlength="4" />
						<? } ?>
					</div>
					<?php if ($product['minimum'] > 1) { ?>
						<div class="minimum"><?php echo $product['minimum']; ?></div>
					<?php } ?>
				</td>
				<td class="total_price" style="text-align: center;">
					<?php if (!$product['amount_in_stock']) { ?><?php } ?><?php echo $product['total_national']; ?>
				</td>							
			</tr>
			<!-- <tr class="clear"></tr> -->
		<?php } ?>
		
	</tbody>
</table>