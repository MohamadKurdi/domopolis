<section class="slider-section carusel-tab 2">
	<div class="wrap">
		<?php if ($categories) { ?>
			<? $i=0; foreach ($categories as $category) { ?>
				<div class="section_slider">
					<div class="head_slider">
						<span class="title">
							<a href="<? echo $category['href']; ?>" title="<? echo $category['name']; ?>"><? echo $category['name']; ?></a>

							<a href="<? echo $category['href']; ?>" title="<? echo $category['name']; ?>"><span><?php echo $text_view_all; ?> <i class="fas fa-arrow-circle-right"></i></span></a>
						</span>	
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
					<? $i++; } ?>
				<?php } ?>
			</div>
		</section>