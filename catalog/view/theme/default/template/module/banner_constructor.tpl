
<div class="wrap slider-top-wrap">
	<div id="slideshow<?php echo $module; ?>" class="swiper-container main-slider">
		<div class="swiper-wrapper">
			<?php foreach ($slides as $slide) { ?>												
				<div class="this-is-slide-wrap-for-big-screens <?php echo $slide['class']; ?>">
					
					<?php foreach ($slide['images'] as $image) { ?>
						<div class="one-image-wrap <?php echo $image['class']; ?>" style="width:<?php echo $image['width']; ?>;height:<?php echo $image['height']; ?>;">
							<?php if ($image['link']) { ?>
								<a href="<?php echo $image['link']; ?>" title="<?php echo $image['title']; ?>">
							<?php } ?>

							<img alt="<?php echo $image['title']; ?>" src="<?php echo $image['image']; ?>" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" />

							<?php if ($image['link']) { ?>								
								</a>
							<?php } ?>
						</div>
					<? } ?>

				</div>

				<div class="this-is-slide-wrap-for-small-screens <?php echo $slide['class_sm']; ?>">
					<?php foreach ($slide['images'] as $image) { ?>
					<div class="one-image-wrap <?php echo $image['class_sm']; ?>" style="width:<?php echo $image['width_sm']; ?>;height:<?php echo $image['height_sm']; ?>;">
							<?php if ($image['link']) { ?>
								<a href="<?php echo $image['link']; ?>" title="<?php echo $image['title']; ?>">
							<?php } ?>

							<img alt="<?php echo $image['title']; ?>" src="<?php echo $image['image_sm']; ?>" width="<?php echo $image['width_sm']; ?>" height="<?php echo $image['height_sm']; ?>" />

							<?php if ($image['link']) { ?>								
								</a>
							<?php } ?>
						</div>
					<? } ?>
				</div>
			<?php } ?>
		</div>
		<!-- arrows -->
		<div class="swiper-arrows">			
				<div class="swiper-prev-slide">
					<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect x="1" y="1" width="58" height="58" stroke="white" stroke-width="2"/>
						<path d="M35 42L23 30L35 18" stroke="white" stroke-width="3"/>
					</svg>
				</div>
				<div class="swiper-next-slide">
					<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect x="-1" y="1" width="58" height="58" transform="matrix(-1 0 0 1 58 0)" stroke="white"
						stroke-width="2"/>
						<path d="M25 42L37 30L25 18" stroke="white" stroke-width="3"/>
					</svg>
				</div>
		</div>
		<!-- /arrows -->
		<!-- If we need pagination -->
		<div class="swiper-pagination"></div>
	</div>
</div>
<script>
	$(document).ready(function () {
		
		var mySwiper = new Swiper('#slideshow<?php echo $module; ?>', {
			loop: true,
			preloadImages: false,   
			lazy: true,
			navigation: {
				nextEl: '#slideshow<?php echo $module; ?> .swiper-next-slide',
				prevEl: '#slideshow<?php echo $module; ?> .swiper-prev-slide',
			},
			autoplay: {
			    delay: 8000,
		  	},
			pagination: {
				el: '#slideshow<?php echo $module; ?> .swiper-pagination',
				clickable: true,
			},
			autoHeight: true
		});
		
	});
</script>