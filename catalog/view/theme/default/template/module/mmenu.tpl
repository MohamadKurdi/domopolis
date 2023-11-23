
	<?php if ($categories) { ?>
	    <?php foreach ($categories as $category) { ?>
			<li <?php if ($category['children']) { ?> class="parent level1 kz" <?php }?> >
				<a href="<?php echo $category['href']; ?>">
					<?php echo $category['menu_icon'];  ?>
					<span><?php echo $category['name']; ?></span>
					
				</a>
				<?php if ($category['children']) { ?>
					<i class="fas fa-angle-right"></i>
				<?php }?>
				<?php if ($category['children']) { ?>
					
					<?php 
						$countChildren = count($category['children']);						
					 ?>

					<div class="topmenu default<?php if ($countChildren > 30){ ?> col-3-list <?php } else if ($countChildren > 16){ ?> col-2-list <?php } else { ?> col-1-list <?php } ?>" >
						<div class="wrap-children">
							<div class="children-category-list">							
								<span class="title_mmenu"><?php echo $category['name']; ?></span>
								<ul>
									
									<?php foreach ($category['children'] as $children): ?>
										<li 
										<?php  if ($children['children']): ?>
												class="parent level2" 
										<?php endif;  ?>>
											<a href="<?=$children['href']; ?>" title="<?=$children['name'] ?>" class="btn-children">
												<span><?=$children['name'] ?></span>
												<?php  if ($children['children']): ?> 
														<i class="fas fa-angle-right"></i>
													<?php endif;  ?>
											</a>				
												<!--level3-->
												<?php  if ($children['children']): ?>
													<div class="level3 pullDown">
														<ul>
															<?php foreach ($children['children'] as $c2): ?>
																<li><a href="<?=$c2['href'] ?>"><?=$c2['name'] ?></a></li>
															<?php endforeach; ?>
														</ul>
													</div>
												<?php endif;  ?>
										</li>
									<?php endforeach; ?>
								</ul>								
							</div>
									
							<div class="main-center-cat-block">		
								<span class="title_mmenu"><? echo $text_popular_products; ?></span>		
								<ul>												
									<?php foreach ($category['products'] as $product) {  ?>											
										<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/product_single.tpl')); ?>
									<?php } ?>									
								</ul>
							</div>

							<? if ($category['manufacturers']) { ?>		
								<div class="category-list-menu">	
									<span class="title_mmenu"><? echo $text_manufacturers; ?></span>		
									<div class="collections_ul">								
										<? foreach($category['manufacturers'] as $manufacturer) { ?>
											<div>
												<a href="<? echo  $manufacturer['href']; ?>" title="<?php echo $category['name']; ?> <? echo $manufacturer['name']; ?>">
														<img loading="lazy" src="<? echo $manufacturer['image']; ?>" alt="<? echo $manufacturer['name']; ?>"/>
												</a>
											</div>
										<?php } ?>
									</div>
								</div>	
							<?php } ?>

						</div>
					</div>
				<?php } ?>
			</li>
		<?php } ?>
	<?php } ?>

		<li class="parent level1 brand">
				<a href="<?php echo $href_manufacturer; ?>">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M8 1H1V8H8V1Z" stroke="#51A881" stroke-width="1" stroke-linecap="round"
						stroke-linejoin="round"/>
						<path d="M19 1H12V8H19V1Z" stroke="#51A881" stroke-width="1" stroke-linecap="round"
						stroke-linejoin="round"/>
						<path d="M19 12H12V19H19V12Z" stroke="#51A881" stroke-width="1"
						stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M8 12H1V19H8V12Z" stroke="#51A881" stroke-width="1" stroke-linecap="round"
						stroke-linejoin="round"/>
					</svg>
					<span><? echo $text_manufacturers; ?></span>					
				</a>
				<i class="fas fa-angle-right"></i>
				<div class="topmenu default" >
					<div class="wrap-children">	
							<div class="category-list-menu_brands">	
								<span class="title_mmenu"><? echo $text_manufacturers; ?> <a href="<? echo $href_manufacturer; ?>"><? echo $text_all_manufacturers; ?><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="https://www.w3.org/2000/svg">
											<path d="M11 15L15 11M15 11L11 7M15 11H7M21 11C21 16.5228 16.5228 21 11 21C5.47715 21 1 16.5228 1 11C1 5.47715 5.47715 1 11 1C16.5228 1 21 5.47715 21 11Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
										</svg></a></span>		
								<div class="collections_ul">									
									<?php if ($brands) { ?>							
										<?php foreach ($brands as $b): ?>
											<?php if (!$b['thumb']) continue; ?>
											<a href="<?=$b['url'] ?>" title="<?=$b['name'] ?>" class="items_brand">
												<img loading="lazy" src="<?=$b['thumb'] ?>" alt="<?=$b['name'] ?>"/>
											</a>
										<?php endforeach; ?>
									<?php } ?>	
								</div>
							</div>	

					</div>
				</div>
			</li>

<?php if ($this->config->get('gen_m_account') == '1') { ?>
	
	<li>
		<a href="<?php echo $account; ?>"><?php echo $this->language->get('text_account'); ?></a>
		<?php if (isset($entry_email)) { ?>
			<div class="topmenu" id="topmenuaccount">
				<ul>
					<li>
						<?php if (!$logged) { ?>
							<?php } else { ?>
							<?php echo $text_logged; ?>
						<?php } ?>
					</li>
					
					<?php if (!$this->customer->isLogged()) { ?>
						<li id="enterkabinet">
							<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
								<div><input type="text" name="email" value="<?php echo $entry_email; ?>" onclick="this.value = '';" onkeydown="this.style.color = '#000000';" /> </div>
								<div><input type="password" name="password" value="<?php echo $entry_password; ?>" onclick="this.value = '';" onkeydown="this.style.color = '#000000';" /></div>
								<div><input type="submit" value="<?php echo $button_login; ?>" class="button login" /></div>
								<div><a href="<?php echo $forgotten; ?>" ><?php echo $text_forgotten; ?></a><br> <a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></div>
							</form> </li>   
							
					<?php } ?>
				</ul>
			</div>
		<?php } ?>
	</li>
	
	
<?php }?>
<!--end Link Account--> 

<!--Link information-->  
<?php if (($this->config->get('gen_m_info') == '1') && (isset($this->document->Information_menu))) { ?>
	
	<li class="parent linkinfo"><a><?php echo $this->language->get('text_information'); ?></a>
		<div class="topmenu default">
	    	<ul>
				<?php foreach ($this->document->Information_menu as $information) { ?>
					<li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
				<?php } ?>
				</ul>
		</div>
	</li>
<?php } ?>
<!--end Link information-->

<!--Link Special-->  
<?php if (($this->config->get('gen_m_spec') == '1') && (isset($special)) && false) { ?>
	
	<li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
<?php } ?>
<!--end Special-->

<!--Link News-->
<?php if (($this->config->get('gen_news') == '1')&& (isset($link_news))) { ?>
	<li> <a href="<?php echo $link_news; ?>"><?php echo $text_news; ?></a></li>
<?php } ?>
<!--end Link News-->



<script>
	$(document).ready(function () {
		if(document.documentElement.clientWidth > 1000) {  				
			var _heightMenu = $('.menu-list').outerHeight();
			$('.topmenu.default').css('height',_heightMenu);
		}

		$('#new_mmenu .main-center-cat-block .product__item').each(function(){
			let _linkProduct = $(this).find('.product__title a').attr('href');

			$(this).find('.product__btn-cart button').prop("onclick", null).off("click");
			$(this).find('.product__btn-cart button').attr('onClick','');

			$(this).find('.product__btn-cart button').on('click', function(){
				 location.href = _linkProduct;
			});
		});
    });
</script>



