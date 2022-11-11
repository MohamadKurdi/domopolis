<section id="carousel<?php echo $module; ?>"  class="brand-top">
	<div class="wrap">
		<!--brand-slider-->
		<div class="brand-slider">
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<?php foreach ($banners as $banner) { ?>
						<div class="swiper-slide">
							<?php if ($banner['link']) { ?>
								<a href="<?php echo $banner['link']; ?>">
									<img class="swiper-lazy" data-src="<?php echo $banner['thumb']; ?>" alt="<?php echo $banner['title']; ?>">
								</a>
								<?php } else { ?>
								<img class="swiper-lazy" data-src="<?php echo $banner['thumb']; ?>" alt="<?php echo $banner['title']; ?>">
							<?php } ?>
						</div>
					<?php } ?>
					<!-- <?php foreach ($brands as $b): ?>
						 <?php if (!$b['thumb']) continue; ?>
						<div class="swiper-slide">							
							 <a href="<?=$b['url'] ?>">
								<img class="swiper-lazy" data-src="<?php echo $banner['thumb']; ?>" title="<?=$b['name'] ?>" alt="<?=$b['name'] ?>">
							</a>
						</div>
					 <?php endforeach; ?> -->
				</div>
			</div>
			<!-- arrows -->
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
			<!-- /arrows -->
			<!-- Add Pagination -->
			<div class="swiper-pagination"></div>
		</div>
		<!--/brand-slider-->
	</div>
</section>
<script>
	$(document).ready(function(){
		var swiper = new Swiper('#carousel<?php echo $module; ?> .swiper-container', {
			slidesPerView: 2,
			loop: true,
			centeredSlides: false,
			preloadImages: false,   
			lazy: true,
			// init: false,
			pagination: {
				el: '#carousel<?php echo $module; ?> .swiper-pagination',
				clickable: true,
			},
			breakpoints: {
				400: {
					slidesPerView: 3,
				},
				600: {
					slidesPerView: 4,
				},
				768: {
					slidesPerView: 5,
				},
				1000: {
					slidesPerView: 6,
				},
				1280: {
					slidesPerView: 7,
					navigation: {
						nextEl: '#carousel<?php echo $module; ?> .swiper-next-slide',
						prevEl: '#carousel<?php echo $module; ?> .swiper-prev-slide',
						clickable: true,
					},
				},
				1600: {
					slidesPerView: 9,
					navigation: {
						nextEl: '#carousel<?php echo $module; ?> .swiper-next-slide',
						prevEl: '#carousel<?php echo $module; ?> .swiper-prev-slide',
						clickable: true,
					},
				},
			}
		});
		
		
	});
	
</script>