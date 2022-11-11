<? if ($products) { ?>
	<div class="nd_slider">
		<div class="navigation-slider 3">
			<div class="nav-group">
				<div class="swiper-prev-slide">
					<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect x="1" y="1" width="58" height="58" stroke="#51A881" stroke-width="2"/>
						<path d="M35 42L23 30L35 18" stroke="#51A881" stroke-width="3"/>
					</svg>
				</div>
				<div class="swiper-next-slide">
					<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect x="-1" y="1" width="58" height="58" transform="matrix(-1 0 0 1 58 0)" stroke="#51A881" stroke-width="2"/>
						<path d="M25 42L37 30L25 18" stroke="#51A881" stroke-width="3"/>
					</svg>
				</div>				
			</div>			
			<div class="swiper-pagination"></div>
		</div>
		<div class="right_column__slider-count">
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<?php foreach ($products as $product) { ?>
						<div class="swiper-slide">
							<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single.tpl')); ?>
						</div>
					<? } unset($product); ?>
				</div>	
			</div>
		</div>							
		
	</div>
<? } ?>