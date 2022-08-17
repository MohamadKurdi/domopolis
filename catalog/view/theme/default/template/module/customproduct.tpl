<? if ($products) { ?>
<section class="slider-section">
  	<div class="wrap">
	  	<!--tabs-->
	  	
    	<div class="tabs">
			<div class="tabs__nav js-dragscroll-wrap">
	        	<ul class="tabs__caption big-size js-dragscroll">
	          		<li class="active"><?php echo $heading_title; ?></li>
        		</ul>
	      	</div>
			<div class="tabs__content active">
				<div class="product-slider">
		          	<div class="swiper-container">
            		<!-- swiper-wrapper -->
							
			            <div class="swiper-wrapper">
			            	<?php foreach ($products as $product) { ?>	
			              	<div class="swiper-slide">
										
											<div class="product__item">
												<div class="itemcolumns">
													<div>
														<div class="img_but">
															<?php if ($product['thumb']) { ?>
																
																<?php if  ($this->config->get('img_additional1') == '1') { ?> 
																	<!--Additional images--> 
																	<div class="owl-addimage owl-carousel"> <?php } ?>
																	
																	<div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" width="<? echo $dimensions['w']; ?>" height="<? echo $dimensions['h']; ?>" /></a></div>
																	
																	<?php if ((isset($product['dop_img'])) && ($this->config->get('img_additional1') == '1') ) { ?> 
																		<?php foreach ($product['dop_img'] as $img) { ?>
																			<div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $img;?>" alt="<?php echo $product['name']; ?>"></a></div>
																		<?php } ?>
																	<?php } ?>
																	
																	
																	<?php if  ($this->config->get('img_additional1') == '1') { ?>  
																		<!--end additional images--> 
																	</div> <?php } ?>
															<?php } ?>
															
															<div class="hover_but">
																<?php if ($this->config->get('show_wishlist') == '1')  { ?>
																	<div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');" title="<?php echo $button_wishlist; ?>" ></a></div>
																<?php } ?>
																<?php if ($this->config->get('show_compare') == '1')  { ?>
																	<div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');"  title="<?php echo $button_compare; ?>"></a></div>
																<?php } ?>
																<?php if  ((isset($product['quickview'])) && ($this->config->get('quick_view') == '1')) { ?>  
																	<div class="quickviewbutton"><a class='quickview' href="<?php echo $product['quickview']; ?>" title="<?php echo $button_quick; ?>"></a></div>
																<?php } ?> 
															</div>
														</div>
														
														<div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
														<?php if ($product['price']) { ?>
															<div class="price">
																<? if ($product['has_child']) { ?>
																	
																	Ваш индивидуальный набор
																	
																	<? } else { ?>
																	
																	<?php if (!$product['special']) { ?>
																		<?php echo $product['price']; ?>
																		<?php } else { ?>
																		<div class="savemoney">- <?php echo $product['saving']; ?>%</div>
																		<span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
																	<?php } ?>
																	
																<? } ?>
															</div>
														<?php } ?>
														<div class="cart">
															<? if ($product['can_not_buy']) { ?>
																<span style="color:#CCC; font-size:22px; font-weight:700;"><? echo $product['stock_status'] ?></span>        
																<? } else { ?>
																<? if ($product['has_child']) { ?>
																	<a href="<?php echo $product['href']; ?>" style="text-decoration:none;" ><input type="button" value="Собрать сервиз" class="button" /></a>
																	<? } else { ?>
																	<input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button" />
																<? } ?>
															<?	} ?>
														</div>
														
														<?php if ($this->config->get('config_review_status')) { ?>
															<div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" width="51" height="11" /></div>
														<?php } ?>
													</div>
												</div>
											</div>
										
							</div>
							<?php } ?>
						</div>
						
						<div class="swiper-pagination"></div>
					</div>
					<div class="swiper-button-prev"></div>
	          		<div class="swiper-button-next"></div>
				</div>
			</div>  
		</div> 

	</div> 
</section>  
<? } ?>
	
