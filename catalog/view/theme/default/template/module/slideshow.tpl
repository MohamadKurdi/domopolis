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
			<?php foreach ($banners as $banner) { ?>
				
				
				<div 	
						data-background="<?php echo $banner['image']; ?>" 
						class="swiper-slide swiper-lazy banner_<?php echo md5($banner['image']); ?>">				
					<?php if ($banner['link']) { ?>	

						
						<?php if (!empty($banner['button_text'])) { ?>
							<div class="info">
								<div class="title-slide"><?php echo $banner['block_text']; ?></div>
								<a href="<?php echo $banner['link']; ?>" class="btn"><?php echo $banner['button_text']; ?></a>
							</div>
						<?php }  else { ?>
							<a href="<?php echo $banner['link']; ?>" class="linkBanerAll" style="position: absolute;width: 100%;height: 100%;"></a>
						<?php } ?>


					<?php } else { ?>					
						<div class="info">
							<div class="title-slide"><?php echo $banner['block_text']; ?></div>
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