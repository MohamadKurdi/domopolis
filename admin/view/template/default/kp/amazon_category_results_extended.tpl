<style>
	.products-container {
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		justify-content: space-around;
		align-content: stretch;
		align-items: flex-start;		
		gap: 10px 10px;
	}

	.products-container-product{
		border: 1px solid #f5f5f5;
		width:220px;
		padding:10px;
	}

	.products-container-product.grey{
		background-color:#D9D9D9!important;
	}

	.products-container-product-image, .products-container-product-name, .products-container-product-asin, .products-container-product-price, .products-container-product-rating, .products-container-product-delivery, .products-container-buttons{
		margin-bottom:10px; text-align:center;
	}

	.products-container-product-warning{font-size:14px; padding:5px; margin-bottom:5px; border-radius:4px; background:#cf4a61; color:#fff; text-align:center;}

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
		cursor:pointer;
	}

	.products-container-product-ignore{
		background-color:#cf4a61;
		padding:5px;
		width:10%;
		float:right;
		text-align:center;
		color:#fff;
		border-radius:5px;
		cursor:pointer;
	}

	.products-container-pagination{
		text-align:center;
		margin-bottom:10px;		
	}

	.products-container-pagination .pagination{
		width:auto;
	}

	.products-container-buttons .buttons{
		margin-bottom:10px;		
		margin-top:10px;
		text-align:center;
	}

	.result-wrap{padding:10px; margin-bottom:10px; border:1px dashed #FF7815; border-radius:4px;}
	.result-wrap-title{font-size:20px; padding:5px; margin-bottom:10px; border-radius:4px; background-color:#7F00FF; color:#fff; display:inline-block; float:left;}
	.result-current-page{font-size:20px; padding:5px; margin-left:10px;  margin-bottom:10px; border-radius:4px; background-color:#FF7815; color:#fff; display:inline-block; float:left;}
	.result-wrap-pagination{font-size:20px; padding:5px; margin-left:10px; margin-bottom:10px; border-radius:4px; background-color:#00ad07; color:#fff; display:inline-block; float:left;}
	.result-wrap-warning{font-size:20px; padding:5px; margin-left:10px; margin-bottom:10px; border-radius:4px; display:inline-block; float:left; background:#cf4a61;}

</style>

<?php if (!empty($results)) { ?>
	<?php foreach ($results as $category_word_id => $result) { ?>
		<div class="result-wrap">
			<div class="result-wrap-title"><i class="fa fa-search"></i> <?php echo $result['title']; ?></div>
			<div class="result-current-page"><i class="fa fa-info"></i> Текущая страница: <?php echo $result['page']; ?></div>
			<?php if (!empty($result['pagination'])) { ?>
				<div class="result-wrap-pagination"><i class="fa fa-bars"></i> Всего страниц <?php echo $result['pagination']['total_pages']; ?>, товаров ~<?php echo $result['pagination']['total_results']; ?>, на странице <?php echo $result['pagination']['total_product_on_page']; ?></div>
			<?php } ?>
			<?php if (!empty($result['products'])) { ?>
				<div class="clr"></div>
				<div class="products-container">
					<?php foreach ($result['products'] as $product) { ?>
						<div class="products-container-product"  id="<?php echo $product['asin']; ?>-wrap">
							<div class="products-container-product-image">
								<img src="<?php echo $product['image']?>" height="200px" style="max-width: 200px;" loading="lazy" />
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
								<span class="asin">
									<?php echo $product['asin']; ?>										
								</span>
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
								<div id="<?php echo $product['asin']; ?>-add-status" class="products-container-product-add" style="" onclick="add('<?php echo $product['asin']; ?>', '<?php echo  $category_word_id; ?>');">
									<i class="fa fa-plus"></i> Добавить
								</div>

								<div id="<?php echo $product['asin']; ?>-ignore-status" class="products-container-product-ignore" style="" onclick="ignore('<?php echo $product['asin']; ?>');">
									<i class="fa fa-times"></i>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="products-container-buttons">
					<div class="buttons">
						<?php if (!$this->config->get('config_rainforest_disable_add_all_button')) { ?>
							<a class="button" onclick="add_all();"><i class="fa fa-plus"></i> Добавить все</a>                
						<?php } ?>					
					</div> 
				</div>
			<?php } else { ?>
				<div class="warning result-wrap-warning">
					Нет результатов 😢
				</div>
			<?php } ?>	

			<?php if (!empty($result['products_bad'])) { ?>
				<div class="clr"></div>
				<div style="margin-bottom:10px; text-align:center; "><span style="cursor:pointer; border-bottom: 1px dashed #cf4a61; color:#cf4a61" onclick="$(this).parent().next('.products-container').toggle()">Показать исключенные товары <i class="fa fa-caret-down"></i></div>
				<div class="products-container" style="display:none;">
					<?php foreach ($result['products_bad'] as $group => $products) { ?>
						<?php foreach ($products as $product) { ?>
							<div class="products-container-product"  id="<?php echo $product['asin']; ?>-wrap">
								<div class="products-container-product-warning"><i class="fa fa-info-circle"></i> <?php echo \hobotix\RainforestAmazon::validationReasons[$group]; ?></div>								
								
								<?php if (!empty($product['validation_name_reason'])) { ?>
									<div style="color:#cf4a61; margin-top:5px; margin-bottom: 5px;"><i class="fa fa-exclamation-triangle"></i> <?php echo $product['validation_name_reason']; ?></div>
								<?php } ?>

								<?php if (!empty($product['count_offers_before'])) { ?>
									<div style="color:#cf4a61; margin-top:5px; margin-bottom: 5px;"><i class="fa fa-exclamation-triangle"></i> Офферов до проверки: <?php echo $product['count_offers_before']; ?></div>
								<?php } ?>
								<?php if (!empty($product['count_offers_after'])) { ?>
									<div style="color:#cf4a61; margin-top:5px; margin-bottom: 5px;"><i class="fa fa-exclamation-triangle"></i> Офферов после проверки: <?php echo $product['count_offers_after']; ?></div>
								<?php } ?>

								<div class="products-container-product-image">
									<img src="<?php echo $product['image']?>" height="200px" style="max-width: 200px;" loading="lazy" />
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
									<span class="asin">
										<?php echo $product['asin']; ?>										
									</span>
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
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			<? } ?>
		</div>	
	<?php } ?>
<?php } else { ?>
	<div class="warning" style="font-size:24px; text-align:center;">
		Нет результатов 😢
	</div>
<?php } ?>	