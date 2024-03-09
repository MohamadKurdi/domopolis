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
									<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect width="60" height="60" transform="matrix(-1 0 0 1 60 0)" fill="white"/>
										<path d="M11.4354 28.9725C11.4358 28.972 11.4362 28.9715 11.4367 28.9711L19.1929 21.4227C19.7739 20.8572 20.7138 20.8593 21.2922 21.4276C21.8705 21.9959 21.8682 22.915 21.2872 23.4805L16.0797 28.5484H47.5156C48.3354 28.5484 49 29.1983 49 30C49 30.8017 48.3354 31.4516 47.5156 31.4516H16.0798L21.2871 36.5195C21.8682 37.085 21.8704 38.0041 21.2921 38.5724C20.7137 39.1407 19.7738 39.1427 19.1928 38.5773L11.4366 31.0289C11.4362 31.0285 11.4358 31.028 11.4353 31.0275C10.854 30.4601 10.8558 29.538 11.4354 28.9725Z" fill="#e25c1d"/>
									</svg>
								</div>
								<div class="swiper-next-slide">
									<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect width="60" height="60" fill="white"/>
										<path d="M48.5646 28.9725C48.5642 28.972 48.5638 28.9715 48.5633 28.9711L40.8071 21.4227C40.2261 20.8572 39.2862 20.8593 38.7078 21.4276C38.1295 21.9959 38.1318 22.915 38.7128 23.4805L43.9203 28.5484H12.4844C11.6646 28.5484 11 29.1983 11 30C11 30.8017 11.6646 31.4516 12.4844 31.4516H43.9202L38.7129 36.5195C38.1318 37.085 38.1296 38.0041 38.7079 38.5724C39.2863 39.1407 40.2262 39.1427 40.8072 38.5773L48.5634 31.0289C48.5638 31.0285 48.5642 31.028 48.5647 31.0275C49.146 30.4601 49.1442 29.538 48.5646 28.9725Z" fill="#e25c1d"/>
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
						<div class="swiper-pagination"></div>		

					</div>
					<? $i++; } ?>
				<?php } ?>
			</div>
		</section>