<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<style>
	.blackfriday_wrap{
		background: #020202;
		padding: 60px 0;
		margin-bottom: 50px;
	}
	.blackfriday_wrap .action_two_column{
		display: grid;
		grid-template-columns: repeat(1, 1fr);
	}
	.blackfriday_wrap .action_two_column .action_left img{
		width: 100%;
		margin: auto;
	}
	.blackfriday_wrap .action_two_column .action_right{
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
	.blackfriday_wrap .action_two_column .action_right .title{
		color: #fff;
		font-size: 32px;
		font-weight: 600;
		display: block;
		margin-bottom: 25px;
		text-transform: uppercase;
	}
	.blackfriday_wrap .action_two_column .action_right .date{
		color: #fbc17e;
		font-size: 32px;
		font-weight: 500;
		margin-bottom: 25px;
	}
	.blackfriday_wrap .text{
		color: #fff;
		font-size: 16px;
		margin-bottom: 20px;
	}
	.blackfriday_wrap .text a{
		color: #fbc17e !important;
	}
	.blackfriday_wrap ul{
		list-style: disc;
		padding-left: 20px;
		margin-bottom: 20px;
	}
	.blackfriday_wrap ul li{
		color: #fff;
		font-size: 16px;
	}
	
	.blackfriday_wrap .action_two_column .action_right.text-left{
		align-items: self-start;
	}
	
	#promo-code-txt-action {
		color: #fbc17e;
		white-space: nowrap;
		padding: 2px 9px;
		border: 1px dashed;
		font-weight: 500;
		cursor: pointer;
		margin-left: 15px;
	}
	#promo-code-txt{
		color: #fbc17e;
		white-space: nowrap;
		padding: 2px 9px;
		border: 1px dashed;
		font-weight: 500;
		cursor: pointer;
	}
	.btn-copy {

		width: 22px;
		height: 12px;
		background-color: transparent;
		background-repeat: no-repeat;
		background-size: contain;
		background-position: center;
		display: inline-flex;
		margin-left: 5px;
		cursor: pointer;
		position: relative;
	}
	.btn-copy .tooltiptext{
		display: none;
		width: 116px;
		background-color: black;
		color: #fff;
		text-align: center;
		padding: 9px 6px;
		border-radius: 6px;
		position: absolute;
		z-index: 1;
		font-size: 12px;
		top: -50px;
		left: 0;
	}
	.product_group{
		margin-bottom: 45px;
	}
	.product_group .head_wrap{
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 30px;
	}
	.product_group .head_wrap a{
		font-size: 20px;
	    color: #51a881;
	    text-decoration: underline;
	    font-weight: 500;
	    display: flex;
	    align-items: center;
	    gap: 10px;
	}
	.product_group .title{
	    color: #333;
	    font-size: 35px;
	    font-weight: 500;
	    display: block;
	}
	.product_group .product_wrap{
		display: grid;
	    grid-template-columns: repeat(4,minmax(0,1fr));
	    grid-auto-flow: row dense;
	}
	.product_group .product_wrap .product__item{
	}
	@media screen and (max-width: 768px) {
		.blackfriday_wrap .action_two_column{
			display: flex;
			flex-direction: column;
		}
		.product_group .product_wrap{
			display: grid;
		    grid-template-columns: repeat(2,minmax(0,1fr));
		    grid-auto-flow: row dense;
		}
		.product_group .head_wrap {
		    flex-direction: column;
		}
		.product_group .title {
		    font-size: 24px;
		    margin-bottom: 15px;
		}
		.product_group .head_wrap a {
		    font-size: 17px;
		}
	}	
	@media screen and (max-width:560px){
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
		.product_group .product_wrap .product__item:nth-of-type(1n + 5){
/*			display: none*/
		}
	}
</style>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<section class="action-page blackfriday_wrap">
	<div class="wrap">
		<div class="action_two_column">
			<div class="action_left">
				<?php if(IS_MOBILE_SESSION) { ?>
					<img src="<?php echo $img_mob; ?>" alt="<?php echo $text_blackfriday_title; ?>">
				<?php } else { ?>
					<img src="<?php echo $img_desc; ?>" alt="<?php echo $text_blackfriday_title; ?>">
				<?php } ?>
				
			</div>
			<div class="action_right">
				<h1 class="title"><?php echo $text_blackfriday_title; ?></h1>
				<p class="date"><?php echo $text_blackfriday_pre_title; ?></p>
				<p class="text"><?php echo $text_blackfriday_text_1; ?>
				<span id="promo-code-txt-action" onclick="copytext(this)" title="скопировать промокод"><?php echo $text_blackfriday_promo_text; ?></span><button class="btn-copy" onclick="copytext('#promo-code-txt-action')" title="скопировать промокод"><span class="tooltiptext">Промокод скопирован</span></button></p>
			</div>
		</div>
		<div class="action_two_column">
			<div class="action_left">
				<p class="text"><?php echo $text_blackfriday_text_2; ?></p>
				<p class="text"><?php echo $text_blackfriday_text_3; ?></p>
			</div>
			<div class="action_right text-left">
				<p class="text"><?php echo $text_blackfriday_text_4; ?></p>
				<!-- <p class="text"><?php echo $text_blackfriday_text_5; ?></p>
				<ul>
					<li><?php echo $text_blackfriday_text_6; ?></li>
					<li><?php echo $text_blackfriday_text_7; ?></li>
					<li><?php echo $text_blackfriday_text_8; ?></li>
				</ul> -->
				<!-- <p class="text"><?php echo $text_blackfriday_text_9; ?></p> -->
			</div>
		</div>

		
		
		
	</div>
</section>
<section class="action-page">
	<div class="wrap">
		<?php if ($product_groups) { ?>
			<?php foreach ($product_groups as $product_group) { ?>
				<div class="product_group">
					<div class="head_wrap">
						<span class="title"><?php echo $product_group['name']?></span>
						<a href="<?php echo $product_group['href']?>" title="<?php echo $product_group['href']?>">
							<?php echo $text_blackfriday_all_product; ?>
								<svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
				                  <path d="M11.5 15L15.5 11M15.5 11L11.5 7M15.5 11H7.5M21.5 11C21.5 16.5228 17.0228 21 11.5 21C5.97715 21 1.5 16.5228 1.5 11C1.5 5.47715 5.97715 1 11.5 1C17.0228 1 21.5 5.47715 21.5 11Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				                </svg>
						</a>
					</div>
					<div class="product_wrap">
						<?php foreach ($product_group['products'] as $product) { ?>
							<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single.tpl')); ?>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
		<div class="last-action-block">
			<h2 class="title"><?=$text_blackfriday_title_list ?></h2>
			<div  id="additionalActions" class="news-block">
				<?php foreach ($additionalActions as $item) { ?>
					<div class="actionListItem news-items" itemscope="" itemtype="http://schema.org/Article">
						<div class="actionsImage news__photo">
							<a href="<?php echo $item['href']; ?>">
								<img src="<?php echo $item['thumb']; ?>" title="<?php echo $item['caption']; ?>" alt="<?php echo $item['caption']; ?>" itemprop="image" />
							</a>
						</div>
						<div class="actionsHeader news__title">
							<a href="<?php echo $item['href']; ?>"><?php echo $item['caption']; ?></a>
						</div>
						<div class="actionsDescription news__desc" itemprop="description"><?php echo $item['description']; ?></div> 
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</section>
<script>
	
	
	
	function copytext(el) {
		var $tmp = $("<textarea>");
		$("body").append($tmp);
		$tmp.val($(el).text()).select();
		document.execCommand("copy");
		$tmp.remove();
		$('.btn-copy').find('.tooltiptext').show();
		setInterval(function(){
			$('.btn-copy').find('.tooltiptext').hide();
		}, 1500);
	}  
</script>
<?php echo $footer; ?>