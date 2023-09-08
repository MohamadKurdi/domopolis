<table class="list">
	<?php foreach ($results as $zone => $products) { ?>
	<?php $exploded = explode('_', $zone); ?>

		<tr>
			<td colspan="6" class="left" style="color:#D69241;">
				<i class="fa fa-calculator"></i> <b>Ценовой диапазон: от <?php echo $exploded['1']; ?> до <?php echo $exploded['2']; ?></b>
			</td>
		</tr>

		<tr>
			<td>
				Название
			</td>
			<td style="white-space: nowrap;">
				<i class="fa fa-amazon"></i>
			</td>
			<td style="white-space: nowrap;">
				О. вес
			</td>
			<td style="white-space: nowrap;">
				Факт. вес
			</td>
			<td style="white-space: nowrap;">
				EUR
			</td>
			<td style="white-space: nowrap;">
				Фронт
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


				<div class="clr"></div>
				<small style="color:#cf4a61"><?php echo $product['compiled_formula']; ?></small>
			</td>
			<td style="white-space: nowrap;">
				<?php echo $product['amazon_best_price']; ?>

				<?php if (!empty($product['formula_overloaded'])) { ?>
					<br />
					<small style="color:#cf4a61"><?php echo $product['used_min']; ?> - <?php echo $product['used_max']; ?></small>
				<?php } ?>
			</td>
			<td style="white-space: nowrap;">
				<?php echo $product['counted_volumetric_weight_format']; ?>
				<br /><small><?php echo $product['counted_volumetric_weight']; ?></small>
			</td>
			<td style="white-space: nowrap;">
				<?php echo $product['counted_weight']; ?>				
			</td style="white-space: nowrap;">
			<td style="white-space: nowrap;">
				<b><?php echo $product['counted_price_eur']; ?></b>
			</td>
			<td style="white-space: nowrap;">
				<b><?php echo $product['counted_price_national']; ?></b>
			</td>
		</tr>


		<?php } ?>

	<?php } ?>
</table>