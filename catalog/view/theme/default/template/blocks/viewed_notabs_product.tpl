<?php 
	$count_prod = count($products); 
	if($count_prod >= 2) {
	?>
	
	<div class="nd_slider">
		<div class="navigation-slider 2">
			<div class="title"><?php echo $heading_title; ?></div>	
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
			<?php if ($this->customer->isLogged()) { ?>
				<div class="show-more">
					<a href="<?php echo $viewed_href; ?>"> <?php echo $view_all; ?>
						<svg width="22" height="22" viewbox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M11 15L15 11M15 11L11 7M15 11H7M21 11C21 16.5228 16.5228 21 11 21C5.47715 21 1 16.5228 1 11C1 5.47715 5.47715 1 11 1C16.5228 1 21 5.47715 21 11Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
						</svg>
					</a>
				</div>
			<?php } ?>
		</div>
		<div class="right_column__slider-count">
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<?php foreach ($products as $product) { ?>
						<div class="swiper-slide">
							<?php include(dirname(__FILE__).'/../structured/product_single.tpl'); ?>
						</div>
					<? } unset($product); ?>
				</div>	
			</div>
		</div>						
	</div>
	
<?php } ?>