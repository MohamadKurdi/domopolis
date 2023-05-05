<style type="text/css">
<?php foreach ($banners as $banner) { ?>
	.banner_<?php echo md5($banner['image']); ?>{background-image: url("<?php echo $banner["image"] ?>") !important;}
	@media screen and (max-width: 560px) {
		.banner_<?php echo md5($banner['image']); ?>{background-image: url("<?php echo $banner["image_sm"] ?>") !important;}
	}
<?php } ?>
</style>
<div class="wrap slider-top-wrap">
	<div id="slideshow<?php echo $module; ?>" class="swiper-container main-slider">
		<div class="swiper-wrapper">
			<?php foreach ($blocks as $block) { ?>												
				<div class="this-is-wrapper-for-blocks-for-large-screens-this-div-is-a-slide">
					<?php foreach ($block as $banner) { ?>
						<div id="" class="one-image-class <?php echo $banner['class']; ?>" style="width:<?php echo $banner['width']; ?>px; height:<?php echo $banner['height']; ?>px">
							<img src="<?php echo $banner['image']; ?>">							
						</div>
					<?php } ?>
				</div>
			<?php } ?>

			<?php foreach ($blocks_sm as $block_sm) { ?>												
				<div class="this-is-wrapper-for-blocks-for-sm-screens-this-div-is-a-slide">
					<?php foreach ($block_sm as $banner) { ?>
						<div id="" class="one-image-class <?php echo $banner['class_sm']; ?>" style="width:<?php echo $banner['width_sm']; ?>px; height:<?php echo $banner['height_sm']; ?>px">
							<img src="<?php echo $banner['image_sm']; ?>" />							
						</div>
					<?php } ?>
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