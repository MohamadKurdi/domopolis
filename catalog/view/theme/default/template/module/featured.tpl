<!--popular-goods-->
<section id="popular-goods" class="popular-goods">
	<div class="wrap">
		<h3 class="title center" style="text-transform: uppercase;font-weight: 400;font-size: 28px;"><?php echo $heading_title; ?></h3>
		<!--tabs-->
		<div class="tabs">
			<!--tabs__nav-->
			<div class="tabs__nav js-dragscroll-wrap2">
				<ul class="tabs__caption js-dragscroll2">
					<?php $i=0; foreach ($tabs as $tab) { ?>
						<li <?php if (!$i) { ?>class="active"<?php } ?>><?php echo $tab['title']; ?></li>					
					<? $i++; } unset($tab); ?>
				</ul>
			</div>
			<!--/tabs__nav-->
			
			<!--tabs__content-->
			<?php $i=0; foreach ($tabs as $tab) { ?>
				<div class="tabs__content carusel-mob swiper-container <?php if (!$i) { ?>active<?php } ?>">
					<!--product__grid-->
					<div class="product__grid product__grid-full swiper-wrapper">						
						<?php if ($tab['href'] && $tab['banner']) { ?>
							<!--product__item-->
							<div class="product__item product__item-ad">
								<div class="show-link">
									<a href="<?php echo $tab['href']; ?>"> <span>Вся категория</span>
										<svg width="22" height="22" viewbox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M11 15L15 11M15 11L11 7M15 11H7M21 11C21 16.5228 16.5228 21 11 21C5.47715 21 1 16.5228 1 11C1 5.47715 5.47715 1 11 1C16.5228 1 21 5.47715 21 11Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
										</svg>
									</a>
								</div>
								<div class="ban">
									<img src="<?php echo $tab['banner']; ?>" title="<?php echo $tab['title']; ?>" alt="<?php echo $tab['title']; ?>" />
								</div>
							</div>
						<?php } ?>
						<!--/product__item-->
						<?php foreach ($tab['products'] as $product) { ?>
							<!--product__item-->
							<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/product_single.tpl')); ?>
							<!--/product__item-->
						<?php $i++; } ?>
					</div>
					<!--/product__grid-->
					
				</div>
			<?php } ?>
			<!--/tabs__content-->					
			
		</div>
		<!--/tabs-->
	</div>
</section>
<!--/popular-goods-->