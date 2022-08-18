        <!--product__slider-head-->
        <div class="product__slider-head viewed">
          	<h4 class="title center">
          		<?php if ($products) { ?>
					Просмотренные товары
				<? } ?>
			</h4>
			<?php if ($this->customer->isLogged()) { ?>
	      		<div class="show-more">
		            <a href="<?php echo $home; ?>/viewed-products"> Посмотреть все
		              	<svg width="22" height="22" viewbox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
		                	<path d="M11 15L15 11M15 11L11 7M15 11H7M21 11C21 16.5228 16.5228 21 11 21C5.47715 21 1 16.5228 1 11C1 5.47715 5.47715 1 11 1C16.5228 1 21 5.47715 21 11Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
		              	</svg>
		            </a>
	          	</div>
          	<?php } ?>
        </div>
        <!--/product__slider-head-->

        <!--product-slider-->
        <? if ($products) { ?>
	        <div class="product-slider border-bottom">
				<!-- swiper-container -->
				<div class="swiper-container">
						<!-- swiper-wrapper -->
						<div class="swiper-wrapper">
							<?php foreach ($products as $product) { ?>
								<div class="swiper-slide">
									<?php include($this->checkTemplate(dirname(FILE),'/../structured/product_single.tpl'); ?>
								</div>
							<? } unset($product); ?>
						</div>
	            		<!-- /swiper-wrapper -->
	            	<div class="swiper-pagination"></div>
	          	</div>
	          	<!-- /swiper-container -->
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
	        </div>
	        <!--/product-slider-->
		<? } ?>