<style>
	.main-slider .this-is-slide-wrap-for-big-screens{
		display: grid;
		max-height: 612px;
		height: 612px;
	}
	.main-slider .this-is-slide-wrap-for-big-screens > div{
		border-radius: 12px;
		overflow: hidden;
	}
	.main-slider .this-is-slide-wrap-for-big-screens > div img{
		width: 100%;
		height: 100%;
		object-fit: cover;
	}
	
	/*	three-with-one-big-right*/
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-right,
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-right{
		grid-template-columns: 6fr 5fr;
		grid-template-rows: 1fr 1fr;
		gap: 10px;
		display: grid;
	}
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-right > div:nth-of-type(1),
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-right > div:nth-of-type(1){
		grid-column-start: 1;
		grid-column-end: 1;
		grid-row-start: 1;
		grid-row-end: 1;
		border: 1px solid #DDE1E4;
		box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
	}
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-right > div:nth-of-type(2),
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-right > div:nth-of-type(2){
		grid-column-start: 1;
		grid-column-end: 1;
		grid-row-start: 2;
		grid-row-end: 2;
		border: 1px solid #DDE1E4;
		box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
	}
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-right > div:nth-of-type(3),
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-right > div:nth-of-type(3){
		grid-column-start: 2;
		grid-column-end: 2;
		grid-row-start: 1;
		grid-row-end: 3;
	}
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-right > div,
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-right > div{
		border-radius: 12px;
		overflow: hidden;
	}
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-right > div img,
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-right > div img{
		width: 100%;
		height: 100%;
		object-fit: cover;
	}
	/*	three-with-one-big-right end*/

	/*	three-with-one-big-left*/
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-left,
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-left{
		grid-template-columns: 5fr 6fr;
		grid-template-rows: 1fr 1fr;
		gap: 10px;
		display: grid;
	}
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-left > div:nth-of-type(1),
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-left > div:nth-of-type(1){
		grid-column-start: 1;
		grid-column-end: 1;
		grid-row-start: 1;
		grid-row-end: 3;
	
	}
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-left > div:nth-of-type(2),
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-left > div:nth-of-type(2){
		grid-column-start: 2;
		grid-column-end: 2;
		grid-row-start: 1;
		grid-row-end: 1;
		border: 1px solid #DDE1E4;
		box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
	}
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-left > div:nth-of-type(3),
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-left > div:nth-of-type(3){
		grid-column-start: 2;
		grid-column-end: 2;
		grid-row-start: 2;
		grid-row-end: 2;
		border: 1px solid #DDE1E4;
		box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
	}
	
	/*	three-with-one-big-left end*/


	/*	three-with-row */
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-row,
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-row{
		grid-template-columns: 1fr 1fr 1fr;
		gap: 10px;
		display: grid;
	}
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-row > div,
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-row > div{
		border: 1px solid #DDE1E4;
		box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
	}
	/*	three-with-row end */

	/*	two-with-column*/
	.main-slider .this-is-slide-wrap-for-small-screens.two-with-column,
	.main-slider .this-is-slide-wrap-for-big-screens.two-with-column{
		grid-template-rows: 1fr 1fr;
		gap: 10px;	
	}
	.main-slider .this-is-slide-wrap-for-small-screens.two-with-column > div,
	.main-slider .this-is-slide-wrap-for-big-screens.two-with-column > div{
		border: 1px solid #DDE1E4;
		box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
	}
	/*	two-with-column end*/

	/*	two-with-row*/
	.main-slider .this-is-slide-wrap-for-small-screens.two-with-row,
	.main-slider .this-is-slide-wrap-for-big-screens.two-with-row{
		grid-template-columns: 1fr 1fr;
		gap: 10px;
	}
	.main-slider .this-is-slide-wrap-for-small-screens.two-with-row > div,
	.main-slider .this-is-slide-wrap-for-big-screens.two-with-row > div{
		border: 1px solid #DDE1E4;
		box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
	}
	/*	two-with-row end*/

	/*	one-big  */
	.main-slider .this-is-slide-wrap-for-small-screens.one-big,
	.main-slider .this-is-slide-wrap-for-big-screens.one-big{
		grid-template-columns: 1fr;
		gap: 10px;
	}
	.main-slider .this-is-slide-wrap-for-small-screens.one-big > div,
	.main-slider .this-is-slide-wrap-for-big-screens.one-big > div{
		border: 1px solid #DDE1E4;
		box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
	}
	/*	one-big  end*/

	/*	one-big-top */
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-top,
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-top{
		display: grid;
		grid-template-columns: 1fr 1fr;
		grid-template-rows: auto auto;
		gap: 8px;
	}
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-top > div,
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-top > div{
		border-radius: 12px;
		overflow: hidden;
	}
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-top > div img,
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-top > div img{
		width: 100%;
		height: 100%;
		object-fit: cover;
	}
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-top > div:nth-of-type(1),
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-top > div:nth-of-type(1){
		grid-column-start: 1;
		grid-column-end: 1;
		grid-row-start: 2;
		grid-row-end: 2;
		border: 1px solid #DDE1E4;
		box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
		border-radius: 12px;
	}
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-top > div:nth-of-type(2),	
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-top > div:nth-of-type(2){
		grid-column-start: 2;
		grid-column-end: 2;
		grid-row-start: 2;
		grid-row-end: 2;
		border: 1px solid #DDE1E4;
		box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
		border-radius: 12px;
	}
	.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-top > div:nth-of-type(3),
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-top > div:nth-of-type(3){
		grid-column-start: 1;
		grid-column-end: 3;
		grid-row-start: 1;
		grid-row-end: 1;
		
	}


	/*	one-big-top  end*/

	/*	three-with-row*/
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-row{
		display: grid;
	    gap: 8px;
	    grid-template-columns: 1fr 1fr 1fr;
	}
	.main-slider .this-is-slide-wrap-for-small-screens.three-with-row > div img{
		width: 100%;
		height: 100%;
	}
	/*	three-with-row end*/
	@media screen and (max-width: 1400px){
		.main-slider .this-is-slide-wrap-for-big-screens{
			display: grid;
			max-height: 517px;
			height: 517px;
		}
	}
	@media screen and (max-width: 560px){
		.main-slider .this-is-slide-wrap-for-big-screens.three-with-one-big-top, .main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-top {
		    display: grid;
		    grid-template-columns: 1fr 1fr;
		    grid-template-rows: 358px 245px;
		    gap: 8px;
		}
		 .main-slider .this-is-slide-wrap-for-small-screens.three-with-one-big-top > div img{
		 	object-position: center;
		 }
	}
</style>


<div class="slider-top-wrap">
	<div id="slideshow<?php echo $module; ?>" class="swiper-container main-slider">
		<div class="swiper-wrapper">
			<?php $count = 0 ?>
			<?php foreach ($slides as $slide) { ?>	
				<?php $count++; ?>
				<?php if(IS_MOBILE_SESSION && !IS_TABLET_SESSION) { ?>	
					<div class="swiper-slide swiper-lazy this-is-slide-wrap-for-small-screens <?php echo $slide['class_sm']; ?>">
						<?php foreach ($slide['images'] as $image) { ?>
						<div class="one-image-wrap <?php echo $image['class_sm']; ?>" style="width:<?php echo $image['width_sm']; ?>;height:<?php echo $image['height_sm']; ?>;">
								<?php if ($image['link']) { ?>
									<a href="<?php echo $image['link']; ?>" title="<?php echo $image['title']; ?>" class="a-gtm-banner" data-gtm-banner="{}">
								<?php } ?>

								<img alt="<?php echo $image['title']; ?>" src="<?php echo $image['image_sm']; ?>" width="<?php echo $image['width_sm']; ?>" height="<?php echo $image['height_sm']; ?>" />

								<?php if ($image['link']) { ?>								
									</a>
								<?php } ?>
							</div>
						<? } ?>
					</div>
				<?php } elseif(IS_TABLET_SESSION) { ?>	
					<div class="swiper-slide swiper-lazy this-is-slide-wrap-for-big-screens <?php echo $slide['class']; ?>">
						<?php foreach ($slide['images'] as $image) { ?>
							<div class="one-image-wrap <?php echo $image['class']; ?>" style="width:<?php echo $image['width']; ?>;height:<?php echo $image['height']; ?>;">
								<?php if ($image['link']) { ?>
									<a href="<?php echo $image['link']; ?>" title="<?php echo $image['title']; ?>" class="a-gtm-banner">
								<?php } ?>

								<img alt="<?php echo $image['title']; ?>" src="<?php echo $image['image']; ?>" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" />

								<?php if ($image['link']) { ?>								
									</a>
								<?php } ?>
							</div>
						<? } ?>

					</div>
				<?php } else { ?>
					<div class="swiper-slide swiper-lazy this-is-slide-wrap-for-big-screens <?php echo $slide['class']; ?>">
						<?php foreach ($slide['images'] as $image) { ?>
							<div class="one-image-wrap <?php echo $image['class']; ?>" style="width:<?php echo $image['width']; ?>;height:<?php echo $image['height']; ?>;">
								<?php if ($image['link']) { ?>
									<a href="<?php echo $image['link']; ?>" title="<?php echo $image['title']; ?>" class="a-gtm-banner" data-gtm-banner='{"page" : "<?php echo $page; ?>", "title" : "<?php echo $image['title']; ?>", "href" : "<?php echo $image['link']; ?>"}'>
								<?php } ?>

								<img alt="<?php echo $image['title']; ?>" src="<?php echo $image['image']; ?>" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" />

								<?php if ($image['link']) { ?>								
									</a>
								<?php } ?>
							</div>
						<? } ?>

					</div>
				<?php } ?>
			

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
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.one-image-wrap a').on('click',
			function(element){
				element.preventDefault();	
				const href = $(this).attr('href');				
				try{
					let bannerObject=JSON.parse($(this).attr('data-gtm-banner'));

					if(typeof(bannerObject)=='object'){
						return new Promise((resolve) => {	
							console.log(bannerObject);

							window.dataLayer = window.dataLayer || [];
							dataLayer.push({
								'event': 		 'ClickOnBanner',
								'eventAction':   'banner-click',
								'eventCategory': 'Promo',
								'eventPage': 	 bannerObject.page,
								'bannerTitle': 	 bannerObject.title,
								'bannerLink': 	 bannerObject.href
							});

							resolve();
						}).then(() => {     
							window.location = href;
						});
					} else {
						window.location = href;
					}
				} catch(err){
					console.log(err);
					window.location   = href;
			}});
	});


	$(document).ready(function () {
		
		var mySwiper = new Swiper('#slideshow<?php echo $module; ?>', {
			loop: <?php if($count <= 1) {?>false<?php } else { ?>true<?php } ?>,
			preloadImages: false,   
			slidesPerView: 1,
			lazy: true,
			navigation: {
				nextEl: '#slideshow<?php echo $module; ?> .swiper-next-slide',
				prevEl: '#slideshow<?php echo $module; ?> .swiper-prev-slide',
			},
			autoplay: {
			    delay: 10000,
		  	},
		  	autoplay: <?php if($count <= 1) {?>false<?php } else { ?>true<?php } ?>,
			pagination: false,
			autoHeight: true
		});
		
	});
</script>