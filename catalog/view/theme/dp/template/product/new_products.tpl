<?php echo $header; ?>
<style type="text/css">

 	#content-news-product .catalog__content .product__item.tpl_list {
	    flex-basis: 100%;
	    border-top: 1px solid #eae9e8;
	}
	#content-news-product .catalog__content .product__item.tpl_list:first-child{
		border-top: 0;
	}
	#content-news-product .pagination {
		margin-top: 30px;
	}
</style>
<link rel="stylesheet" href="catalog/view/theme/dp/css/sumoselect.css" />
<script src="catalog/view/theme/dp/js/sumoselect.min.js"></script> 
<?php echo $column_left; ?><?php echo $column_right; ?><?php echo $content_top; ?>
<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/breadcrumbs.tpl')); ?>
<section id="content-news-product">
	<div class="wrap">
	    <h1 class="title"><?=$text_new_products ?></h1>
		<?php if ($products) { ?>
			<!--catalog__content-head-->
			<div class="catalog__content-head">
				<!--catalog__sort-->
				<div class="catalog__sort">
					<span><?php echo $text_sort; ?></span>
					<div class="catalog__sort-btn">
						<select id="input-sort" class="form-control" onchange="location = this.value;">
	                        <?php foreach ($sorts as $sorts) { ?>
	                            <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
	                                <option value="<?php echo $sorts['href']; ?>"
									selected="selected"><?php echo $sorts['text']; ?></option>
									<?php } else { ?>
	                                <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
				</div>
				<!--/catalog__sort-->
			</div>
			<!--/catalog__content-head-->
			<div class="catalog__content ">
	          	<!--product__grid-->
	          	<div class="product__grid" id="product__grid">
	        		<!--product__item-->
	            	<?php foreach ($products as $product) { ?>
	              		<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/product_single.tpl')); ?>
					<?php } ?>
	            	<!--/product__item-->
				</div>
	          	<!--/product__grid-->
			</div>
			
			<div class="pagination"><?php echo $pagination; ?></div>
			
			<?php } else { ?>
			
			<div class="content"><?php echo $text_empty; ?></div>
			
		<?php }?>
	</div>
</section>
<div class="cont_bottom"></div>
<script type="text/javascript">
	 $('select').SumoSelect();
</script>
<?php echo $content_bottom; ?>

<?php echo $footer; ?>	