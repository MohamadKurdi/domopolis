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
				<small><?php echo $product['name']; ?></small>

				<br />
				<b><?php echo $product['weight']; ?>, <?php echo $product['length']; ?> * <?php echo $product['width']; ?> * <?php echo $product['height']; ?></b>
			</td>
			<td>
				<?php echo $product['amazon_best_price']; ?>
			</td>
			<td>
				<?php echo $product['counted_volumetric_weight_format']; ?>
				<br /><small><?php echo $product['counted_volumetric_weight']; ?></small>
			</td>
			<td>
				<?php echo $product['counted_weight']; ?>				
			</td>
			<td>
				<?php echo $product['counted_price_eur']; ?>
			</td>
			<td>
				<?php echo $product['counted_price_national']; ?>
			</td>
		</tr>


		<?php } ?>

	<?php } ?>
</table>