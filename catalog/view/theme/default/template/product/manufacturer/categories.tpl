<?php echo $header; ?>

<?php include($this->checkTemplate(dirname(__FILE__),'/structured_manufacturer/head.tpl')); ?>
	<?php if ($this->config->get('config_store_id') == 2) { ?>

		<div class="wrap">
		    <section id="brand-categories" class="collection-list-children collection-list ">  

				<?php foreach ($categories as $category) { ?>	
					<?php if ($category['category_id'] == $category_id) { ?>
						<a href="<?php echo $category['href']; ?>" class="main_category_title active"><?php echo $category['name']; ?></a>
					<?php } else { ?>
						<a href="<?php echo $category['href']; ?>" class="main_category_title"><?php echo $category['name']; ?></a>
					<?php } ?>
					<div class="categories-photo__row__collection">
				      	<?php foreach ($category['children'] as $child) { ?>
					      	<div class="categories-photo__item">
						        <a href="<? echo $child['href']; ?>">
									<img src="<?php echo $child['thumb']; ?>" alt="<? echo $child['name']; ?>">
									<div class="categories-photo__label">
					          			<span><?php echo $text_subcategory_category;?></span>
					            		<p><? echo $child['name']; ?></p>
					          		</div>
						        </a>
					      	</div>
				      	<? } ?>
			      	</div>
			    <?php } ?>    	
		    </section>      
		</div>

	<?php } else { ?>
		<div class="wrap">
		    <section id="brand-categories" class="collection-list-children collection-list">  
		    	<ul>
					<?php foreach ($categories as $category) { ?>
						<?php if ($category['category_id'] == $category_id) { ?>
							<li class="cat-parent">
								<a href="<?php echo $category['href']; ?>" class="active"><b><?php echo $category['name']; ?></b></a>
						<?php } else { ?>
							<li>
								<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
						<?php } ?>
								<?php if (isset($category['children']) && $category['children']) { ?>
									<ul class="accordeon_subcat active">
										<?php foreach ($category['children'] as $child) { ?>
											<li>							
												<a href="<?php echo $child['href']; ?>" class="active"><?php echo $child['name']; ?></a>						
											</li>
										<?php } ?>
									</ul>
								<?php } ?>
							</li>
					<?php } ?>  
				</ul> 
		    </section>      
		</div>
	<?php } ?>
<?php echo $footer; ?>