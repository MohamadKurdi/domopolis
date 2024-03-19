	<style type="text/css">
		.topmenu {
			overflow: hidden;
		}
		.topmenu .children-category-list{
			column-count: 3;
		    min-height: 100%;
		    background: #fff;
		    position: relative;
		    top: 0;
		    overflow: hidden;
		}
		.topmenu .children-category-list .submenu-list__item{
		    padding-right: 2rem;
    		padding-left: 2rem;
    		break-inside: avoid-column;
		    white-space: nowrap;
		    overflow: hidden;
		    position: relative;
		}
		.topmenu .children-category-list .submenu-list__item:after {
		    content: "";
		    display: block;
		    position: absolute;
		    top: 0;
		    right: 0;
		    width: 40px;
		    bottom: 0;
		    background: linear-gradient(90deg,hsla(0,0%,100%,0),#fff 75%,#fff);
		    pointer-events: none;
		}
		.topmenu .children-category-list+.submenu-list__item{
			margin-top: 15px;
		}
		.topmenu .children-category-list .submenu-list__item .title span{
			position: relative;
			color: #1f9f9d;
			font-size: 16px;
		}
		.topmenu .children-category-list .submenu-list__item .menu-categories{
			height: auto;
			position: static;
		}
		.topmenu .children-category-list .submenu-list__item .menu-categories ul{
			padding: 0;
		}
		.topmenu .children-category-list .submenu-list__item .menu-categories ul li a{
			font-size: 14px;
			text-decoration: none !important;
		}
		.topmenu .all-categories{
			position: absolute;
			left: 0;
			bottom: 0;
			padding-right: 2rem;
			padding-left: 2rem;
			padding-bottom: 15px;
			background: #fff;
			width: 100%;
			color: #1f9f9d;
			font-size: 16px;
		}
		.topmenu .all-categories::after{
			content: "";
		    position: absolute;
		    bottom: 100%;
		    left: 0;
		    height: 20px;
		    width: 100%;
		    background: linear-gradient(0deg,#fff,hsla(0,0%,100%,0));
		}
	</style>
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

					<div class="topmenu default">
						<div class="wrap-children">
							<div class="children-category-list">
								<?php foreach ($category['children'] as $children): ?>	
									<div class="submenu-list__item">
										<a href="<?=$children['href']; ?>" class="title<?php  if (!$children['children']): ?> submenu-list__link_no-children<?php endif;  ?>">
											<span><?=$children['name'] ?></span>
										</a>
										<?php  if ($children['children']): ?>
											<div class="menu-categories">
												<div class="menu-categories-list-wrap">
													<ul class="menu-categories-list">
														<?php foreach ($children['children'] as $c2): ?>
															<li class="menu-categories-list__item">
																<a href="<?=$c2['href'] ?>" class="menu-categories-list__link"><?=$c2['name'] ?></a>
															</li>
														<?php endforeach; ?>
													</ul>
												</div>
											</div>
										<?php endif;  ?>
									</div>	
								<?php endforeach; ?>					
							</div>
						</div>
						<a href="<?php echo $category['href']; ?>" class="all-categories">Всі категорії</a>		
					</div>
				<?php } ?>
			</li>
		<?php } ?>
	<?php } ?>

		<li class="parent level1 brand">
				<a href="<?php echo $href_manufacturer; ?>">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M8 1H1V8H8V1Z" stroke="#1f9f9d" stroke-width="1" stroke-linecap="round"
						stroke-linejoin="round"/>
						<path d="M19 1H12V8H19V1Z" stroke="#1f9f9d" stroke-width="1" stroke-linecap="round"
						stroke-linejoin="round"/>
						<path d="M19 12H12V19H19V12Z" stroke="#1f9f9d" stroke-width="1"
						stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M8 12H1V19H8V12Z" stroke="#1f9f9d" stroke-width="1" stroke-linecap="round"
						stroke-linejoin="round"/>
					</svg>
					<span><? echo $text_manufacturers; ?></span>					
				</a>
				<i class="fas fa-angle-right"></i>
				<div class="topmenu default" >
					<div class="wrap-children">	
							<div class="category-list-menu_brands">	
								<span class="title_mmenu"><? echo $text_manufacturers; ?> <a href="<? echo $href_manufacturer; ?>"><? echo $text_all_manufacturers; ?><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="https://www.w3.org/2000/svg">
											<path d="M11 15L15 11M15 11L11 7M15 11H7M21 11C21 16.5228 16.5228 21 11 21C5.47715 21 1 16.5228 1 11C1 5.47715 5.47715 1 11 1C16.5228 1 21 5.47715 21 11Z" stroke="#1f9f9d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
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


			$(this).find('.product__btn-cart button').on('click', function(){
				 location.href = _linkProduct;
			});
			$(this).find('.product__btn-cart button').prop("onclick", null).off("click");
			$(this).find('.product__btn-cart button').attr('onClick','');
		});

    });

</script>



