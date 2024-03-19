<? if ($products) { ?>

	<div class="default_slider_section">
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
	<!-- Add Pagination -->
	<div class="swiper-pagination"></div>	
<? } ?>