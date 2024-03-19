<?php
$this->language->load('module/mattimeotheme');
$button_quick = $this->language->get('entry_quickview');
?>
<?php echo $header; ?><?php echo $column_right; ?>

<style type="text/css">
	
	@media screen and (max-width:560px){
		.stock_status {
			font-size: 13px;
			line-height: 16px;
		}
		.product__grid{
			justify-content: space-between;
		}
		.product__item, .manufacturer-list .manufacturer-content ul li:not(.list), 
		#content-search .catalog__content .product__item, #content-news-product .catalog__content .product__item{
			flex-basis: 49%;
			margin-bottom: 10px;
			padding: 10px
		}
		.product__item:hover .product__btn-cart button, .product__item:hover .product__btn-cart a,
		.product__item .product__btn-cart button, .product__item .product__btn-cart a{
			font-size: 0;
		}
		.product__rating{
			display: none
		}
		.product__title a {
			font-size: 12px;
			line-height: 14px;
			display: -webkit-box;
			-webkit-line-clamp: 3;
			-webkit-box-orient: vertical;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		.product__item .product__delivery{
			font-size: 10px;
			line-height: 1;
		}
		.product__label > div{
			font-size: 10px;
			padding: 4px 6px;
			line-height: 1;
			height: auto
		}
		.product__item .price__sale {
			right: 10px;
			left: inherit;
			top: 10px;
			font-size: 10px;
			padding: 4px 6px;
			line-height: 1;
		}
		.product__photo{
			margin-bottom: 10px
		}
		.product__price-new {
			font-size: 15px;
			line-height: 1;
			margin: 0
		}
		.product__price-old {
			font-size: 12px;
			line-height: 1;
			margin: 0;
			align-self: start;
		}
		.product__price {
			display: flex;
			flex-wrap: wrap;
			align-self: start;
			flex-direction: column;
			justify-content: start;
			align-items: start;
			text-align: left;
		}
		.product__item .product__btn-cart .number__product__block{
			display: none
		}
		.product__item .product__btn-cart button{
			padding-left: 0;
			width: 30px;
			height: 30px;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.product__item .product__btn-cart button svg{
			margin: 0 !important;
			width: 15px;
			height: 15px
		}
		.product__item .product__btn-cart {
			position: absolute;
			margin: 0;
			z-index: 1;
			width: auto;
			right: 10px;
			bottom: 10px;
			height: 30px
		}
		.product_add-favorite {
			top: 10px;
			width: 40px;
			height: 35px;
			bottom: initial;
			left: 10px;
			flex-direction: column;
		}
		.product_add-favorite button{
			margin-left: 0 !important;
			margin-top: 0 !important;
			font-size: 15px !important;
		}
		.product_add-favorite button i{
			font-size: 15px !important;
		}
		.product_add-favorite button svg{
			width: 25px;
			height: 25px
		}
		.product__item:hover .product__btn-cart{
			width: auto
		}
		.product__item:hover .product__btn-cart button{
			padding-left: 0
		}
		.product__item:hover .product__price{
			flex-direction: column;
		}
		.product__item .product__info{
			padding-bottom: 0 !important
		}
	}

	@media screen and (max-width:560px){
		#categories-photo{
			flex-direction: row;
		}
	}
	@media screen and (min-width: 768px) {
		.sticky {
			position: fixed;
			z-index: 101;
		}
		.stop {
			position: relative;
			z-index: 101;
		}
	}
</style>
<section class="catalog account_wrap" id="content"><?php echo $content_top; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<div class="wrap two_column">
	<div class="side_bar">
		<?php echo $column_left; ?>
	</div>
	<div class="account_content">
		<?php if ($products) { ?>
			<!--catalog__content-->
			<div class="catalog__content product-grid">
				<div class="product-grid product__grid" id="product__grid">
					<?php foreach ($products as $product) { ?>
						<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single.tpl')); ?>
					<?php } ?>

				</div>
				<output id="output" class="hidden"></output>
				
			<?php } else { ?>
				<div class="content"><?php echo $text_empty; ?></div>
				<div class="buttons">
					<a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a>
				</div>
			</div>
		<?php }?>
	</div>
</div>
</section>
<?php echo $content_bottom; ?>


<?php echo $footer; ?>