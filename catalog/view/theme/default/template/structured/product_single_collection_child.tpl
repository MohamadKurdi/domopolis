<!--product__item-->
<div class="product__item tpl_list">
	<!--product__photo-->
	<div class="product__photo">
		<a href="<? echo $product['href']; ?>" title="<? echo $product['name']; ?>">
			<img src="<? echo $product['thumb']; ?>" alt="<? echo $product['name']; ?>" loading="lazy" width="224" height="229"/>
		</a>
		<div class="product__label">
			<div class="product__label-hit">Хит продаж</div>
			<div class="product__label-best-price">Лучшая цена</div>
			<div class="product__label-new">Новинка</div>
			<div class="product__label-stock">Акция</div>
		</div>
	</div>
	<!--/product__photo-->
	<div class="product__middle product__middle__colection">
		<?php if(!empty($product['rating'])) {?>
			<?php if ($this->config->get('config_review_status')) { ?>
				<div class="product__rating">
					<span class="rate rate-<?php echo $product['rating']; ?>"></span>
				</div>
			<?php } ?>
		<?php } ?>
		<div class="product__title">
			<a href="<? echo $product['href']; ?>" title="<? echo $product['name']; ?>"><? echo $product['name']; ?></a>
		</div>
		<div class="delivery_mobile">
			<?php include(dirname(__FILE__).'/delivery_terms.tpl'); ?>
		</div>
	</div>
	<div class="product__delivery">
		<?php include(dirname(__FILE__).'/delivery_terms.tpl'); ?>
	</div>
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
	<!--product__bottom-->
	<div class="product__bottom">	
		<!-- <div class="price__sale">-X%</div> -->	
		<?php if($product['need_ask_about_stock']) { ?>
				<span class="stock_status <?php if (!empty($do_not_show_left_aside_in_list)) { ?>stock_status_colection<?php } ?>">Наличие и цену уточняйте</span>			
		<?php } elseif (!$product['can_not_buy']) { ?>
			<div class="product__btn-cart checker">
				<?php if ($price) { ?>
				<div class="number__product__block gty cart-impulse">
					<input data-product-id="<?php echo $product['product_id']; ?>" data-one-price="<? echo ($product['special'])?preg_replace("/[^0-9.]/", "", $product['special']):preg_replace("/[^0-9.]/", "",$product['price']); ?>" type="text" name="quantity_<?php echo $product['product_id']; ?>" class="htop quantity_child" id="chtop_<?php echo $product['product_id']; ?>" size="2" data-minimum="<?php echo $product['minimum']; ?>" value="0" />
				
					<div class="number_controls">
						 <div class="nc-plus increase increase_collection_child" data-product-id="<?php echo $product['product_id']; ?>" id="increase_<?php echo $product['product_id']; ?>"><i class="fas fa-angle-up"></i></div>
						<div class="nc-minus decrease decrease_collection_child" data-product-id="<?php echo $product['product_id']; ?>" id="decrease_<?php echo $product['product_id']; ?>"><i class="fas fa-angle-down"></i></div>
					   
					</div>	
				</div>				
				<?php } ?>
				<div style="font-size:24px;" id="chprice_result_<?php echo $product['product_id']; ?>">-</div>
			</div>			
		<?php } else { ?>
			<span class="stock_status <?php if (!empty($do_not_show_left_aside_in_list)) { ?>stock_status_colection<?php } ?>"><? echo $product['stock_status'] ?></span>
		<?php } ?>
	</div>
	<!--/product__bottom-->
</div>
<!--/product__item