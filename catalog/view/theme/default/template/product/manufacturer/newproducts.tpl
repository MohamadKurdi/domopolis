<?php echo $header; ?>

<?php include($this->checkTemplate(dirname(__FILE__),'/structured_manufacturer/head.tpl')); ?>

<?php if ($products) { ?>
	<section class="catalog ">
		<div class="wrap">
			<!--catalog-inner-->
			<div class="catalog-inner">				
				
				<!--catalog__content-->
				<div class="catalog__content product-grid">
					
					<!--catalog__content-head-->
					<div class="catalog__content-head" style="margin-top: 25px;">
						
						<!--catalog__sort-->
						<!-- <div class="catalog__sort">
							<span>Сортировать</span>
							<div class="catalog__sort-btn">
								<select id="input-sort" class="form-control" onchange="location = this.value;">
	                                <?php foreach ($sorts as $sorts) { ?>
	                                    <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
	                                        <option value="<?php echo $sorts['href']; ?>"
											selected="selected"><?php echo $sorts['text']; ?></option>
											<?php } else { ?>
	                                        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div> -->
						<!--/catalog__sort-->

						<!-- <div class="limit">
							<div id="search-refine-search3" class="search-refine-search3 catalog__sort">
								<span id="what_to_search3" class="what_to_search3" ><?php echo $text_limit; ?></span>
								<div class="catalog__sort-btn">
									<select id="input-sort" class="form-control" onchange="location = this.value;">	
										<?php foreach ($limits as $limits) { ?>
											<?php if ($limits['value'] == $limit) { ?>
												<option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
											<?php } else { ?>	
												<option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>	   	
											<?php } ?>												
										<?php } ?>
									</select>
								</div>
							</div>
						</div> -->

						<!--display-type-->
						<!-- <?php if (empty($do_not_show_left_aside_in_list)) { ?>
							<div class="display-type">
								<a href="#" data-tpl="tpl_tile" class="current">
									<svg width="20" height="20" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M8 1H1V8H8V1Z" stroke="#EAE9E8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
										<path d="M19 1H12V8H19V1Z" stroke="#EAE9E8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
										<path d="M19 12H12V19H19V12Z" stroke="#EAE9E8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
										<path d="M8 12H1V19H8V12Z" stroke="#EAE9E8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
									</svg>
								</a>
								<a href="#" data-tpl="tpl_list">
									<svg width="20" height="20" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M19 7H1M19 1H1M19 13H1M19 19H1" stroke="#EAE9E8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
									</svg>
								</a>
							</div>
						<?php } ?>	 -->
						<!--/display-type-->
					</div>
					<!--/catalog__content-head-->
					<section id="section-catalog">
					<!--product__grid-->
					<div class="product__grid product-col-4" id="product__grid">
						
						<!--product__item-->													
							<?php $productCounter = 1; foreach ($products as $product) { ?>
							<?php include($this->checkTemplate(dirname(__FILE__),'/../../structured/product_single.tpl')); ?>
							<?php if (IS_MOBILE_SESSION && $productCounter == 8) { ?>
								<?php include($this->checkTemplate(dirname(__FILE__),'/../../structured/listing_pwainstall.tpl')); ?>
							<?php } elseif ($productCounter == 12) { ?>
								<?php include($this->checkTemplate(dirname(__FILE__),'/../../structured/listing_pwainstall.tpl')); ?>
							<?php } ?>
						<?php $productCounter += 1; } ?>
						<!--/product__item-->
						
					</div>
					<output id="output" class="hidden"></output>
					<!--/product__grid-->
					</section>
					<!--pages-->
					<?php include($this->checkTemplate(dirname(__FILE__),'/../../structured/pagination.tpl')); ?>
					<!--/pages-->
				</div>
				<!--/catalog__content-->
			</div>
			<!--/catalog-inner-->
					
		</div>
	</section>
<?php } ?>

<?php echo $footer; ?>