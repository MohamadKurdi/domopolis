<?php
	$wayPath = 'catalog/view/theme/default/template/';
?> 
<?php if ($categories) { ?>
    <?php foreach ($categories as $category) { ?>
		<li <?php if ($category['children']) { ?> class="parent level1" <?php }?> >
			<a href="<?php echo $category['href']; ?>">
				<?php echo $category['menu_icon'];  ?>
				<span><?php echo $category['name']; ?></span>
				<?php if ($category['children']) { ?>
					<i class="fas fa-angle-right"></i>
				<?php }?>
			</a>
			
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
							<h2>Популярные товары</h2>							
							<ul>
								<?php foreach ($category['products'] as $products):  ?>
								
									<li class="list-group-item">
										<a href="<?=$products['href'] ?>" title="<?=$products['name'] ?>" style="background-image: url(<?=$products['thumb'] ?>);" >
										<!-- <img src="<?=$products['thumb'] ?>" alt="<?=$products['name'] ?>"> -->
										<!-- <span><?=$products['name'] ?></span>-->
									</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>



					<? if ($category['manufacturers']) { ?>		
						<div class="category-list-menu">							
							<h2>Популярные бренды</h2>	
							<div class="collections_ul">								
								<? foreach($category['manufacturers'] as $manufacturer) { ?>
									<div>
										<a href="<? echo  $manufacturer['href']; ?>" title="<?php echo $category['name']; ?> <? echo $manufacturer['name']; ?>">
												<img src="<? echo $manufacturer['image']; ?>" alt="<? echo $manufacturer['name']; ?>"/>
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

<!-- 		  <?php if (($this->config->get('gen_m_brand') == '1') && (isset($manufacturers))) { ?>
			
			 <li><a href="<? echo $href_manufacturer ?>"><? echo $text_manufacturers; ?></a>
				<div class="topmenu" id="topbrand">
					<ul>
						<? foreach ($manufacturers as $manufacturer) { ?>
							<li>
								<a href="<? echo $manufacturer['href']; ?>">
								<div class="sub_image">
									<img src="<? echo $manufacturer['image']; ?>" alt="<? echo $manufacturer['name']; ?>" width="<? echo $manufacturer['width']; ?>" height="<? echo $manufacturer['height']; ?>" />
								</div>
							<span><? echo $manufacturer['name']; ?></span></a>
							</li>
						<? } ?>
					</ul>
				</div>
			</li>
		  
		  <? } ?> -->

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
				var _heightMenu = $('.menu-list').innerHeight();
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

