<?php
	$wayPath = 'catalog/view/theme/default/template/';
?> 
<? if ($this->config->get('config_store_id') == 2) { ?> 
	<style type="text/css">
		#new_mmenu li.parent>.topmenu{
			left: 276px;
			max-width: 1242px;
			width: 1242px;
			top: -2px;
			border-bottom: 2px solid #FFC34F;
		    border-top: 2px solid #FFC34F;
		    border-right: 2px solid #FFC34F;	
		}

		#new_mmenu {
		    width: 278px;
		    border-bottom: 2px solid #FFC34F;
		    border-top: 2px solid #FFC34F;
		    border-left: 2px solid #FFC34F;
		}

		#new_mmenu li.level1 > a{
			font-weight: 500;
			font-size: 15px;
			padding: 9px 20px 9px 50px;
		}

		#new_mmenu li.level1 > a svg{
			width: 24px;
			height: 24px;
		}

		#new_mmenu li.level1 > i,
		#new_mmenu .level1.open_menu > i{
			color: #FFC34F !important;
		}

		#new_mmenu .level1.open_menu{
			background: #F7F4F4;
		}

		#new_mmenu li.parent > .topmenu .wrap-children{
			grid-template-columns: 3fr 2fr 9fr;
			grid-template-rows: 1fr;
			grid-gap: 0;
			padding: 30px 0;
		}

		#new_mmenu li.parent > .topmenu .wrap-children > div{
			padding: 0 30px;
		}

		#new_mmenu li.parent > .topmenu .children-category-list{
			grid-column-start: 1;
			grid-column-end: 1;
			grid-row-start: 1;
			grid-row-end: 1;			
		}

		#new_mmenu li.parent > .topmenu .main-center-cat-block{
			grid-column-start: 3;
			grid-column-end: 3;
			grid-row-start: 1;
			grid-row-end: 1;

		}

		#new_mmenu li.parent > .topmenu .category-list-menu{
			grid-column-start: 2;
			grid-column-end: 2;
			grid-row-start: 1;
			grid-row-end: 1;
			border-left: 1px solid #EAE9E8;
			border-right: 1px solid #EAE9E8;
		}

		#new_mmenu li.parent > .topmenu .children-category-list ul > li {
		    width: 100%;
		    margin-right: 0;
		    border-bottom: 0;
		}

		#new_mmenu li.level1 .topmenu .children-category-list a{
			padding: 0;
			font-weight: 500;
			font-size: 15px;
			margin-bottom: 10px;
			line-height: 17px;
		}

		#new_mmenu .topmenu .title_mmenu{
			font-size: 24px;
			font-weight: 500;
			margin-bottom: 19px;
			display: block;
		}

		#new_mmenu li.parent > .topmenu .category-list-menu .title_mmenu{
			text-align: center;
		}

		#new_mmenu li.parent > .topmenu .main-center-cat-block{

		}

		#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item {

		}

		#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item a{
			padding: 0;
		}
		#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item .product__title a{
			font-size: 17px;
			line-height: 22px;
		}
		#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item .product__btn-cart{
			display:none;
		}
		#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item:hover .product__info{
			top: inherit;
			bottom: 0;
			box-shadow: none;
		}
		#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item:hover .product__info:after{
			background: url(/catalog/view/theme/kp/img/shadow.png) bottom repeat-x;
			content: "";
			position: absolute;
			bottom: calc(100% - 1px);
			height: 40px;
			width: 100%;
			left: 0;
			right: 0;
			max-width: 100%;
			margin: auto;
		}
		/*col-2*/
		#new_mmenu .topmenu.col-2-list .wrap-children{
			grid-template-columns: 9fr 2fr 5fr !important;
		}

		#new_mmenu .topmenu.col-2-list .main-center-cat-block .product__item:last-child{
			display: none !important;
		}

		/*col-3*/
		#new_mmenu .topmenu.col-3-list .wrap-children{
			grid-template-columns: 9fr 2fr 3fr !important;
		}
		#new_mmenu .col-3-list .main-center-cat-block .product__item:nth-of-type(3){
			display: none !important;
		}

	</style>
<?php } ?>
<? if ($this->config->get('config_store_id') == 2) { ?> 
	<!-- KZ menu -->
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
								<span class="title_mmenu">Популярные товары</span>		
								<ul>			
									
										<?php foreach ($category['products'] as $product):  ?>
											
												<?php include(dirname(__FILE__).'/../structured/product_single.tpl'); ?>
											

										<?php endforeach; ?>

									
								</ul>
							</div>

							<? if ($category['manufacturers']) { ?>		
								<div class="category-list-menu">	
									<span class="title_mmenu">Бренды</span>		
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
	<!-- /KZ menu -->
<?php } else { ?>
	<!-- other menu -->
		<?php if ($categories) { ?>
		    <?php foreach ($categories as $category) { ?>
				<li <?php if ($category['children']) { ?> class="parent level1" <?php }?> >
					<a href="<?php echo $category['href']; ?>">
						<?php echo $category['menu_icon'];  ?>
						<span><?php echo $category['name']; ?></span>
						
					</a>
					<?php if ($category['children']) { ?>
						<i class="fas fa-angle-right"></i>
					<?php }?>
					<?php if ($category['children']) { ?>
						<div class="topmenu default">
							<div class="wrap-children">
								<div class="children-category-list">
								
									
										<ul>
											<?php foreach ($category['children'] as $children): ?>
													<li <?php  if ($children['children']): ?>
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
									
								</div>



							<? if ($category['manufacturers']) { ?>		
								<div class="category-list-menu">							
									<h2>Популярные бренды</h2>	
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
	<!-- /other menu -->
<?php } ?>

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
    });

</script>

<?php 
	include $wayPath ."/common/m_customlink.php";
?>  

<?php 
	include $wayPath ."/common/m_htmlmenu.php";
?>



