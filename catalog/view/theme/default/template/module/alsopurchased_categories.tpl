<?php if ($categories) { ?>

	<tabs-headings>
		<?php foreach ($categories as $category) { ?>
			<?php echo $category['name']; ?>
		<?php } ?>
	</tabs-headings>



	<tabs-divs>
		<?php foreach ($categories as $category) { ?>
			<?php foreach ($category['products'] as $product) { ?>
				<div class="swiper-slide">
					<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single.tpl')); ?>
				</div>
			<?php } ?>
		<?php } ?>
	</tabs-divs>



	<?php } ?>