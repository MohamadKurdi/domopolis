<?php 
	$count_prod = count($products); 
	if($count_prod >= 2) {
	?>
<style>
	.section_slider{
		margin-bottom: 40px;
		position: relative;
	}
	.section_slider .head_slider{
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 36px;
	}
	.section_slider .head_slider .title{
		font-weight: 400;
		font-size: 45px;
		line-height: 134.1%;
		color: #0B280E;

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
		padding: 30px 0px 45px;
	}
	@media screen and (max-width: 1400px){
		.section_slider {
			margin-bottom: 0px;
		}
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
		    padding: 30px 20px 22px;
		}
	}
</style>

	<div class="section_slider">
		<div class="head_slider">
			<span id="viewed-products-reloadable-tab" class="title"><?php echo $heading_title; ?></span>	
		</div>
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
		<div class="swiper-pagination"></div>
	</div>
	
	
<?php } ?>