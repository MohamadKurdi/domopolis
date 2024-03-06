<?php if ($products) { ?>
	<table class="list">
	<tr>
		<td>id</td>
		<td>название</td>
		<td>sku</td>
		<td>ean</td>
		<td>asin</td>
		<td>цена, eur</td>
		<td>скидка, eur</td>
	</tr>
	
	<?php foreach ($products as $product) { ?>

	<tr>
		<td><?php echo $product['product_id']; ?></td>
		<td><?php echo $product['name']; ?></td>
		<td><?php echo $product['sku']; ?></td>
		<td><?php echo $product['ean']; ?></td>
		<td><?php echo $product['asin']; ?></td>
		<td><?php echo $product['price']; ?></td>
		<td><?php echo $product['special']; ?></td>
	</tr>

	<?php } ?>

	</table>
<?php } else { ?>
	Нет скидок
<?php } ?>