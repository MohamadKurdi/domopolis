<?php if ($categories) { ?>

	<!--popular-goods-->		
	<style type="text/css">
		.popular-goods .nav-group > div{
			cursor: pointer;
		}
		.popular-goods .nav-group  .swiper-next-slide,
		.popular-goods .nav-group  .swiper-prev-slide{
			position: absolute;
			top: calc(37% - 0px);
			display: flex;
			justify-content: center;
			align-items: center;
			cursor: pointer;
			z-index: 10;
			/*width: 44px;
			height: 44px;*/
			/*background: #FFF;
			border: 1px solid #C9CBCE;
			box-shadow: 0 12px 31px rgba(0,0,0,.16);
			border-radius: 30px;*/
			overflow: hidden;
		}
		.popular-goods .nav-group  .swiper-next-slide{
			right: 1%;
		}
		.popular-goods .nav-group  .swiper-prev-slide{
			left: 1%;
		}
		/*.popular-goods .nav-group  .swiper-prev-slide svg{
		  transform: rotate(180deg);
		}*/
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
			padding: 30px 0px 120px;
		}
		.popular-goods .tabs__nav li{
			font-weight: 500;
			font-size: 20px;
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
			background: #54a883;
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
			left: 0;
			right: 0;
			display: none;
		}
		
		@media screen and (max-width: 1400px) {
			.popular-goods{
				margin-bottom: 0px;
			}

		}
		@media screen and (max-width: 560px) {
			.popular-goods .tabs__nav li{
				line-height: 19px;
			}
			.popular-goods .nav-group .swiper-next-slide, .popular-goods .nav-group .swiper-prev-slide {
			    top: calc(40% - 0px);
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
								<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="https://www.w3.org/2000/svg">
						            <rect x="1" y="1" width="58" height="58" stroke="#51A881" stroke-width="2"></rect>
						            <path d="M35 42L23 30L35 18" stroke="#51A881" stroke-width="3"></path>
					          	</svg>
							</div>
							<div class="swiper-next-slide">
								<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="https://www.w3.org/2000/svg">
						            <rect x="-1" y="1" width="58" height="58" transform="matrix(-1 0 0 1 58 0)" stroke="#51A881" stroke-width="2"></rect>
						            <path d="M25 42L37 30L25 18" stroke="#51A881" stroke-width="3"></path>
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