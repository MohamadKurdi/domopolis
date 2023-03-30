<style>
	.products-container {

		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		justify-content: space-around;
		align-content: stretch;
		align-items: flex-start;		
		gap: 10px 10px; /* row-gap column gap */
	}

	.products-container-product{
		border: 1px solid #f5f5f5;
		width:220px;
		padding:10px;
	}

	.products-container-product.grey{
		background-color:#D9D9D9!important;
	}

	.products-container-product-image, .products-container-product-name, .products-container-product-asin, .products-container-product-price, .products-container-product-rating, .products-container-product-delivery{
		margin-bottom:10px;
	}

	.products-container-product-rating{
		text-align:center;
		color:#565959 ;
	}

	.products-container-product-name{
		height:60px;
		font-size:14px;
		overflow-y:hidden;
	}

	.products-container-product-asin{
		text-align:center;
	}

	.products-container-product-asin > .asin{
		display: inline-block;
		padding:3px 5px; 
		background:#ffaa56; 
		color:#FFF;
	}

	.products-container-product-asin > .prime{
		display: inline-block;
		padding:3px 5px; 
		color:#009fd5;
	}

	.products-container-product-price{
		text-align:center;
		color: #0F1111;
		font-size:18px;
	}

	.products-container-product-delivery{
		text-align:center;
		font-size:12px;		
		color:#565959;
	}

	.products-container-product-add{
		background-color:#00AD07;
		padding:5px;
		width:80%;
		float:left;
		text-align:center;
		color:#fff;
		border-radius:5px;
	}

	.products-container-product-ignore{
		background-color:#cf4a61;
		padding:5px;
		width:10%;
		float:right;
		text-align:center;
		color:#fff;
		border-radius:5px;
	}
</style>

<?php if (!empty($products)) { ?>
	<div class="products-container">
		<?php foreach ($products as $product) { ?>
			<div class="products-container-product"  id="<?php echo $product['asin']; ?>-wrap">
				<div class="products-container-product-image">
					<img src="<?php echo $product['image']?>" height="200px" loading="lazy" />
				</div>				
				<div class="products-container-product-name">
					<a href="<?php echo $product['link']; ?>" target="_blank">
						<?php echo $product['title']; ?> <i class="fa fa-external-link"></i>
					</a>
				</div>

				<?php if (!empty($product['rating']) && !empty($product['ratings_total'])) { ?>
					<div class="products-container-product-rating">
						<i class="fa fa-thumbs-up"></i> <?php echo $product['rating']; ?>&nbsp;&nbsp;&nbsp;<i class="fa fa-comment"></i> <?php echo $product['ratings_total']?>
					</div>
				<?php } ?>

				<div class="products-container-product-asin">
					<span class="asin"><?php echo $product['asin']; ?></span>
					<?php if (!empty($product['is_prime'])) { ?>
						<span class="prime"><i class="fa fa-star"></i> Prime</span>
					<? } ?>
				</div>	

				<div class="products-container-product-price">
					<?php if (!empty($product['price'])) { ?>
						<b><sup><?php echo $product['price']['symbol']; ?></sup> <?php echo $product['price']['value']; ?></b>
					<?php } else { ?>
						😢
					<?php } ?>
				</div>

				<?php if (!empty($product['delivery'])) { ?>
					<div class="products-container-product-delivery">
						<i class="fa fa-info-circle"></i> <?php echo $product['delivery']['tagline']; ?>
					</div>
				<?php } ?>

				<div class="products-container-product-add-ignore-wrap">

					<div id="<?php echo $product['asin']; ?>-add-status" class="products-container-product-add" style="" onclick="add('<?php echo $product['asin']; ?>');">
						<i class="fa fa-plus"></i> Добавить
					</div>

					<div id="<?php echo $product['asin']; ?>-ignore-status" class="products-container-product-ignore" style="" onclick="ignore('<?php echo $product['asin']; ?>');">
						<i class="fa fa-times"></i>
					</div>

				</div>

			</div>
		<?php } ?>
	</div>
<?php } else { ?>
	<div class="warning" style="font-size:36px;">
		Нет товаров 😢
	</div>
<?php } ?>

<?php if (!empty($in_queue_products)) { ?>
	<div style="border:1px dashed #cf4a61; padding:10px; margin-bottom:10px; width:49%; float:left;">	
		<h3>Пропущенные товары в очереди: <?php echo count($in_queue_products); ?></h3>
		<table class="list">
			<?php foreach ($in_queue_products as $product) { ?>
				<tr>
					<td class="left">
						<b><?php echo $product['asin']; ?></b>
					</td>
					<td class="left">
						<?php echo $product['title']; ?>				
					</td>
				</tr>
			<?php } ?>
		</table>
	</div>
<?php } ?>

<?php if (!empty($deleted_products)) { ?>
	<div style="border:1px dashed #cf4a61; padding:10px; margin-bottom:10px; width:49%; float:left;">	
		<h3>Исключенные вручную товары: <?php echo count($deleted_products); ?></h3>
		<table class="list">
			<?php foreach ($deleted_products as $product) { ?>
				<tr>
					<td class="left">
						<b><?php echo $product['asin']; ?></b>
					</td>
					<td class="left">
						<?php echo $product['title']; ?>				
					</td>
				</tr>
			<?php } ?>
		</table>
	</div>
<?php } ?>

<?php if (!empty($cheap_products)) { ?>
	<div style="border:1px dashed #cf4a61; padding:10px; margin-bottom:10px; width:49%; float:left;">	
		<h3>Исключенные дешевые товары: <?php echo count($cheap_products); ?></h3>
		<table class="list">
			<?php foreach ($cheap_products as $product) { ?>
				<tr>
					<td class="left">
						<b><?php echo $product['asin']; ?></b>
					</td>
					<td class="left">
						<?php echo $product['title']; ?>				
					</td>
				</tr>
			<?php } ?>
		</table>
	</div>
<?php } ?>

<?php if (!empty($existent_products)) { ?>
	<div style="border:1px dashed #cf4a61; padding:10px; margin-bottom:10px; width:49%; float:left;">	
		<h3>Уже существующие товары: <?php echo count($existent_products); ?></h3>
		<table class="list">
			<?php foreach ($existent_products as $product) { ?>
				<tr>
					<td class="left">
						<b><?php echo $product['asin']; ?></b>
					</td>
					<td class="left">
						<?php echo $product['title']; ?>				
					</td>
				</tr>
			<?php } ?>
		</table>
	</div>
<?php } ?>