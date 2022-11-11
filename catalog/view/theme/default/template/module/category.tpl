<? if (count($categories) > 0) { ?>
	
	<style>
		.side-catalog > li > ul > li > a {color:#333!important;}
		.side-catalog > li > ul > li > a:hover {color:#51a881!important;}
		.side-catalog a.active{color:#51a881!important;}
	</style>
	
	<div class="box accord_category filter">
		<div class="box-content box-shadowed">
			<div class="box-category accordeon_categ accordion">
				<div class="accordion__item opened">
					<div class="accordion__title category-block">Каталог</div>
					<div class="accordion__content">
						<ul class="side-catalog">
							<?php foreach ($categories as $category) { ?>
								<li>
									<a href="<?php echo $category['href']; ?>" <?php if ($category['category_id'] == $category_id) { ?>class="active"<?php } ?>><?php echo $category['name']; ?></a>
								</li>
								
								<?php if (isset($category['children']) && $category['children']) { ?>
									<li>
										<ul class="accordeon_subcat">
											<?php foreach ($category['children'] as $child) { ?>
												<li>							
													<a href="<?php echo $child['href']; ?>" <?php if ($child['category_id'] == $category_id) { ?>class="active"<?php } ?>><?php echo $child['name']; ?></a>						
												</li>
											<?php } ?>
										</ul>
									</li>
								<?php } ?>
								
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<? } ?>		