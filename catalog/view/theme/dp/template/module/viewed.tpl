
<style>
	.section_slider{
		position: relative;
	}
	.section_slider .head_slider{
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.section_slider .head_slider .title{
        font-weight: 600;
        font-size: 26px;
        line-height: 32px;
        color: #121415;
        margin-bottom: 32px;
        text-align: left;
        font-family: 'Inter', sans-serif;

	}
	.section_slider .head_slider .nav-group{
		display: flex;
		align-items: center;
		gap: 20px;
	}
	.section_slider .head_slider .nav-group > div{
		cursor: pointer;
		display: flex;
	}
	.section_slider .default_slider_section .swiper-container{
		padding: 0 0 45px;
	}
	@media screen and (max-width: 768px){
		.section_slider .head_slider .title{
			font-size: 35px;
		}
	}
	@media screen and (max-width: 560px) {
		.section_slider .head_slider .nav-group > div{
			display: none
		}
		.section_slider .head_slider{
			justify-content: center;
			
		}
		.section_slider .head_slider .title{
			font-size: 22px;
		}
		.section_slider .head_slider{
			margin-bottom: 0
		}
		.section_slider{
			/*margin-bottom: 60px*/
			margin-bottom: 0
		}
		.section_slider .default_slider_section .swiper-container {
		    padding: 30px 0px 22px;
		}
	}
</style>

<section class="slider-section carusel-tab 1">
	<div class="wrap">
		<?php if ($tabs) { ?>
			<?php $i=0; foreach ($tabs as $tab) { ?>
				<div class="section_slider">
					<div class="head_slider">
						<span class="title"><? echo $tab['title']; ?></span>	
					</div>
			
					<div class="default_slider_section">
						<div class="swiper-container">
						 	<div class="swiper-wrapper">
								<?php foreach ($tab['products'] as $product) { ?>
									<div class="swiper-slide">
										<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single.tpl')); ?>
									</div>
								<?php } ?>
							</div>	
						</div>
						<div class="nav_slider">
		                   <!-- arrows -->
		                   <div class="swiper-prev-slide">
		                         <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
		                            <path d="M1.5 11L6.5 6L1.5 1" stroke="#3F3F49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		                        </svg>
		                    </div>
		                    <div class="swiper-next-slide">
		                        <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
		                            <path d="M1.5 11L6.5 6L1.5 1" stroke="#3F3F49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		                        </svg>
		                    </div>
		                    <!-- /arrows -->   
		                </div>
					</div>							
					<!-- Add Pagination -->
	            	<!-- <div class="swiper-pagination"></div>		 -->
								
				</div>
			<? $i++; } ?>
		<? } ?>
		<div class="section_slider" id="viewed-carousel-section">
			<div class="head_slider">
				<span id="viewed-products-reloadable-tab" class="title"><?php echo $heading_title; ?></span>	
			</div>
			<div class="ajax-module-reloadable" data-modpath="module/viewed/viewed" data-x='0' data-y="1" data-afterload="rebuildViewedTabs"></div>
			<div class="nav_slider">
               <!-- arrows -->
               <div class="swiper-prev-slide">
                     <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.5 11L6.5 6L1.5 1" stroke="#3F3F49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="swiper-next-slide">
                    <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.5 11L6.5 6L1.5 1" stroke="#3F3F49" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <!-- /arrows -->   
            </div>
		</div>
	</div>
</section>

<script>
	function rebuildViewedTabs(data){
		if (data.length <= 0 ){
			$('#viewed-carousel-section').css('display','none');
		}
	}
</script>