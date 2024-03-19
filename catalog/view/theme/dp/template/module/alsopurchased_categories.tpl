<?php if ($categories) { ?>

	<!--popular-goods-->		
	<style type="text/css">
		.popular-goods .nav-group > div{
			cursor: pointer;
		}
		.popular-goods .nav-group  .swiper-next-slide,
		.popular-goods .nav-group  .swiper-prev-slide{
			position: absolute;
			top: calc(50% - 0px);
			display: flex;
			justify-content: center;
			align-items: center;
			cursor: pointer;
			z-index: 10;
			width: 44px;
			height: 44px;
			background: #FFF;
			border: 1px solid #C9CBCE;
			box-shadow: 0 12px 31px rgba(0,0,0,.16);
			border-radius: 30px;
			overflow: hidden;
		}
		.popular-goods .nav-group  .swiper-next-slide{
			right: 2%;
		}
		.popular-goods .nav-group  .swiper-prev-slide{
			left: 2%;
		}
		.popular-goods .nav-group  .swiper-prev-slide svg{
		  transform: rotate(180deg);
		}
		.popular-goods .tabs__content .nav-group{
			display: none;
		}
		.popular-goods .tabs__content.active .nav-group{
			display: block;
		}	

		.popular-goods{
			margin-bottom: 40px;
			position: relative;
		}
		.popular-goods .head_slider{
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
		}
		.popular-goods .head_slider .title{
			font-weight: 600;
			font-size: 26px;
			line-height: 32px;
			color: #121415;
			font-family: 'Inter', sans-serif;

		}
		.popular-goods .head_slider .nav-group{
			display: flex;
			align-items: center;
			gap: 20px;
		}
		.popular-goods .head_slider .nav-group > div{
			cursor: pointer;
			display: flex;
		}
		.popular-goods .tabs__nav{
			background: transparent;
			position: relative;
		}
		.popular-goods .tabs__nav::after{
			display: none;
		}
		.popular-goods .tabs__nav::before{
			background: #DDE1E4;
			height: 2px;
			width: 100%;
			top: initial;
			right: initial;
			left: 0;
			bottom: 0;
			z-index: 0;
			position: absolute;
			content: '';
		}
		.popular-goods .tabs__content.active .swiper-container {
			padding: 30px 0px 30px;
		}
		.popular-goods .tabs__nav li{
			font-weight: 500;
			font-size: 16px;
			line-height: 19px;
			color: #888F97;
			padding-bottom: 14px;
			position: relative;
		}
		.popular-goods .tabs__nav li.active{
			color: #121415;
		}
		.popular-goods .tabs__nav li.active:before{
			content: '';
			position: absolute;
			height: 2px;
			bottom: 1px;
			background: #97B63C;
			width: 100%;
			left: 0;
			z-index: 1;
		}
		.popular-goods .tabs__nav li.active:after{
			display: none;
		}
		.popular-goods .tabs__content.active{
			padding-bottom: 20px;
		}
		.popular-goods .swiper-pagination{
			bottom: 0;
		}
		
		@media screen and (max-width: 1400px) {
			.popular-goods{
				margin-bottom: 0px;
			}
		}
	</style>		
	<section id="buy_together" class="popular-goods">
			<div class="head_slider">
				<span class="title"><?php echo $heading_title; ?></span>	
			</div>
			<!--tabs-->
			<div class="tabs">
				<!--tabs__nav-->
				<div class="tabs__nav js-dragscroll-wrap">
					<ul class="tabs__caption js-dragscroll">
						<?php $i=0; foreach($categories as $category) { ?>
							<li <?php if ($i==0) { ?>class="active"<?php } ?>>
								<?php echo $category['name']; ?>
							</li>	
						<?php $i++; } ?>								
					</ul>
				</div>
					<!--/tabs__nav-->

				<!--tabs__content-->
				<?php $i=0; foreach($categories as $category) { ?>
					<div class="tabs__content <?php if ($i==0) { ?>active<?php } ?>">
						<div class="nav-group">
							<!-- arrows -->
							<div class="swiper-prev-slide">
								<svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="https://www.w3.org/2000/svg">
				                    <path d="M1.5 11L6.5 6L1.5 1" stroke="#3F3F49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				                </svg>
							</div>
							<div class="swiper-next-slide">
								<svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="https://www.w3.org/2000/svg">
				                    <path d="M1.5 11L6.5 6L1.5 1" stroke="#3F3F49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				                </svg>
							</div>
							<!-- /arrows -->  		
						</div>	
						<!--product__grid-->
						<div class="swiper swiper-container">
							<div class="swiper-wrapper">
								<!--product__item-->
								<?php foreach($category['products'] as $product) { ?>
									<div class="swiper-slide">
										<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single.tpl')); ?>
									</div>
								<?php } ?>
								<!--/product__item-->
							</div>
						</div>
						<!--/product__grid-->
						<!-- Add Pagination -->
						<div class="swiper-pagination"></div>
					</div>
					<!--/tabs__content-->
				<?php $i++; } ?>
			</div>
			<!--/tabs-->
	</section>
	<!--/popular-goods-->
<?php } ?>