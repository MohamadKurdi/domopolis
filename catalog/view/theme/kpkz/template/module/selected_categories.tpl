<style type="text/css">
	.slider-section.carusel-tab .section_slider{
		position: relative;
	}
	.slider-section.carusel-tab .head_slider{
		display: flex;
	    align-items: center;
	    justify-content: space-between;
	}
	.slider-section.carusel-tab .head_slider .nav-group{
		display: flex;
		gap: 20px;
	}
	.slider-section.carusel-tab .head_slider .nav-group div{
		cursor: pointer;
		display: flex;
	}
	.slider-section.carusel-tab .swiper-pagination.slider_no_tabs{
		position: absolute;
		left: 0;
		right: 0;
		z-index: 0;
		bottom: 80px;
	}
	.slider-section.carusel-tab .swiper-container{
		padding-bottom: 108px;
	}
	@media screen and (min-width: 1200px) {
		.slider-section.carusel-tab .head_slider{
			margin-bottom: 25px;
		}
	}
	@media screen  and (max-width: 560px){
		.slider-section.carusel-tab .head_slider{
			flex-direction: column;
		}
		.slider-section.carusel-tab .swiper-container {
		    padding-bottom: 50px;
		}
		.slider-section.carusel-tab .swiper-pagination.slider_no_tabs {
		    bottom: 30px;
		}
		.slider-section.carusel-tab .head_slider .nav-group{
			display: none;
		}
		.slider-section.carusel-tab .head_slider .title {
		    gap: 10px;
		    flex-direction: column;
		    align-items: center;
		    margin-bottom: 10px !important;
		}
	}
</style>
<section class="slider-section carusel-tab">
	<div class="wrap">
		<?php if ($categories) { ?>
			<? $i=0; foreach ($categories as $category) { ?>
				<div class="section_slider">
					<div class="head_slider">
						<span class="title">
							<a href="<? echo $category['href']; ?>" title="<? echo $category['name']; ?>"><? echo $category['name']; ?></a>

							<a href="<? echo $category['href']; ?>" title="<? echo $category['name']; ?>"><span><?php echo $text_view_all; ?> <i class="fas fa-arrow-circle-right"></i></span></a>
						</span>
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
						</div>

						<div class="default_slider_section">
							<div class="swiper-container">
								<div class="swiper-wrapper">
									<?php foreach ($category['products'] as $product) { ?>
										<div class="swiper-slide">
											<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single.tpl')); ?>
										</div>
									<? } ?>
								</div>	
							</div>
						</div>							
						<!-- Add Pagination -->
						<div class="swiper-pagination slider_no_tabs"></div>		

					</div>
					<? $i++; } ?>
				<?php } ?>
			</div>
		</section>