<table class="list">
	<?php foreach ($results as $zone => $products) { ?>
	<?php $exploded = explode('_', $zone); ?>

		<tr>
			<td colspan="8" class="left" style="color:#D69241;">
				<i class="fa fa-calculator"></i> <b>Ценовой диапазон: от <?php echo $exploded['1']; ?> до <?php echo $exploded['2']; ?></b>
			</td>
		</tr>

		<tr>
			<td>
				Название
			</td>
			<td style="white-space: nowrap;">
				Цена <i class="fa fa-amazon"></i>
			</td>
			<td style="white-space: nowrap;">
				Себестоимость <i class="fa fa-shopping-basket" aria-hidden="true"></i>
			</td>
			<td style="white-space: nowrap;">
				<i class="fa fa-percent" aria-hidden="true"></i>
			</td>
			<td style="white-space: nowrap;">
				Фронт
			</td>
			<td style="white-space: nowrap;">
				Объемный <i class="fa fa-balance-scale" aria-hidden="true"></i>
			</td>
			<td style="white-space: nowrap;">
				Фактический <i class="fa fa-balance-scale" aria-hidden="true"></i>
			</td>
		</tr>

		<?php foreach ($products as $product) { ?>

		<tr>
			<td>
				<span style="font-size:11px;"><?php echo $product['name']; ?></span>
				<br />
				<span style="font-size:10px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#FF9900;"><?php echo $product['asin']; ?></span>		
				<span style="font-size:10px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#4ea24e;"><?php echo $product['product_id']; ?></span>

				<span style="font-size:10px; display:inline-block; float:left; padding:3px; color:#FFF; background-color:#7F00FF;"><?php echo $product['weight']; ?>, <?php echo $product['length']; ?> * <?php echo $product['width']; ?> * <?php echo $product['height']; ?></span>				
			</td>
			<td style="white-space: nowrap;">
				<?php echo $product['amazon_best_price']; ?>

				<?php if (!empty($product['formula_overloaded'])) { ?>
					<br />
					<small style="color:#cf4a61"><?php echo $product['used_min']; ?> - <?php echo $product['used_max']; ?></small>
				<?php } ?>
			</td>

			<td style="white-space: nowrap;">
				<b style="color:#ff5656;"><?php echo $product['counted_сostprice_eur']; ?></b>
				<br />
				<b style="color:#ff5656;"><?php echo $product['counted_сostprice_national']; ?></b>
			</td>

			<td style="white-space: nowrap;">
				<span style="display:inline-block;padding:2px 3px; font-size:12px; <?php if ((float)$product['profitability'] < 0) { ?>background:#ff5656;<?php } else { ?>background:#000;<?php } ?> color:#fff; white-space:nowrap;"><? echo $product['profitability']; ?> %</span>

				<br />
				<span style="display:inline-block;padding:2px 3px; font-size:10px; background:#ff7f00; color:#FFF; white-space:nowrap;"><? echo $product['diff_eur']; ?></span>

				<br />
				<span style="display:inline-block;padding:2px 3px; font-size:10px; background:#ff7f00; color:#FFF; white-space:nowrap;"><? echo $product['diff_national']; ?></span>
			</td>

			<td style="white-space: nowrap;">
				<b style="color:#00ad07"><?php echo $product['counted_price_eur']; ?></b>
				<br />
				<b style="color:#00ad07"><?php echo $product['counted_price_national']; ?></b>
			</td>

			<td style="white-space: nowrap;">
				<?php echo $product['counted_volumetric_weight_format']; ?>				
			</td>
			<td style="white-space: nowrap;">
				<?php echo $product['counted_weight']; ?>				
			</td>
		</tr>
		<tr>
			<td colspan="3">				
				<div class="clr"></div>
				<small style="color:#cf4a61; font-size:9px">Formula: <?php echo $product['compiled_formula']; ?></small>
				
				<div class="clr"></div>
				<small style="color:#cf4a61; font-size:9px;">Costprice: <?php echo $product['compiled_costprice_formula']; ?></small>
			</td>
			<td colspan="2">
				<?php if (!empty($product['formula_overloaded_from'])) { ?>
					<div class="clr"></div>
					<small style="color:#cf4a61; font-size:9px">PriceLogic: <?php echo $product['formula_overloaded_from']; ?></small>
				<?php } ?>
			</td>
			<td colspan="2">	
				<?php foreach ($product['compiled_weight'] as $line => $text) { ?>
					<div class="clr"></div>
					<small style="color:#cf4a61; font-size:9px;"><?php echo $line?>: <?php echo $text; ?></small>
				<?php } ?>
			</td>
		</tr>


		<?php } ?>

	<?php } ?>
</table>