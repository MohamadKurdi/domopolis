
<style>
	
	@media screen and (min-width: 768px){
	.carusel-tab{
	margin-top: 25px;
	}
	.carusel-tab .tabs{
	position: relative;
	}
	.carusel-tab .tabs__nav{
	position: absolute;
	left: 0;
	top: 75px;
	}
	.carusel-tab ul.tabs__caption{
	display: flex;
	flex-direction: column;
	}
	.carusel-tab ul.tabs__caption li {
	padding: 13px;
	}
	.carusel-tab .tabs__caption li.active::after{
	display: none;
	}
	.carusel-tab .nd_slider .swiper-container{
	margin-top: 0;
	padding-top: 0;
	}
	.carusel-tab .tabs__nav::after{
	display: none;
	}
	.carusel-tab .nd_slider .navigation-slider .swiper-pagination{
	margin-top: 65px;
	}
	}
	@media screen and (max-width: 768px){
	.carusel-tab .navigation-slider{
	display: none;
	}
	.carusel-tab .right_column__slider-count{
	width: 100%;
	margin-left: 0;
	}
	.carusel-tab .nd_slider .swiper-container {
	margin-bottom: -83px;
	padding-bottom: 62px;
	}
	}
	
	.ui-widget-content{
		border:0px;
	}
</style>
<!--section-->
<section class="slider-section carusel-tab">
	<div class="wrap">
		<!--tabs-->
		<div class="tabs" id="viewed-tabs">
			
			<!--tabs__nav-->
			<div class="tabs__nav">
				<ul class="tabs__caption big-size"  id="viewed-carousel-tab-ul">	

					<?php if ($tabs) { ?>
						<? $i=0; foreach ($tabs as $tab) { ?>
							<li <? if (!$i) { ?>class="tabs__reloadable__content_tab active"<? } ?>><? echo $tab['title']; ?></li>
						<? $i++; } unset($tab) ?>
					<? } ?>
				</ul>
			</div>
			<!--/tabs__nav-->
			
			
			<div class="tabs__content tabs__reloadable__content ajax-module-reloadable" data-modpath="module/viewed/viewed" data-x='0' data-y="1" data-afterload="rebuildViewedTabs"></div>
			
			<!--tabs__content-->
			<?php if ($tabs) { ?>
				<? $i=0; foreach ($tabs as $tab) { ?>
					<div class="tabs__content tabs__reloadable__content <? if (!$i) { ?>active<? } ?>">
						
						<div class="nd_slider">
							<div class="navigation-slider 4">
								
								<div class="nav-group">
									<div class="swiper-prev-slide">
										<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
											<rect x="1" y="1" width="58" height="58" stroke="#51A881" stroke-width="2"/>
											<path d="M35 42L23 30L35 18" stroke="#51A881" stroke-width="3"/>
										</svg>
									</div>
									<div class="swiper-next-slide">
										<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
											<rect x="-1" y="1" width="58" height="58" transform="matrix(-1 0 0 1 58 0)" stroke="#51A881" stroke-width="2"/>
											<path d="M25 42L37 30L25 18" stroke="#51A881" stroke-width="3"/>
										</svg>
									</div>				
								</div>			
						    	<div class="swiper-pagination"></div>
							</div>
							<div class="right_column__slider-count">
								<div class="swiper-container">
								 	<div class="swiper-wrapper">
										<?php foreach ($tab['products'] as $product) { ?>
											<div class="swiper-slide">
												<?php include($this->checkTemplate(dirname(FILE),'/../structured/product_single.tpl'); ?>
											</div>
										<? } ?>
									</div>	
								</div>
							</div>							
							
						</div>
					</div>
				<? $i++; } ?>
			<? } ?>
			<!--/tabs__content-->
			
			
		</div>
		<!--/tabs-->
	</div>
</section>
<script>
	function rePaintTabs(){
		if (document.documentElement.clientWidth > 768) {  
			let _tabs_caption = $('.tabs__caption');
			let _tabs_navigation = $('.tabs__content .navigation-slider');
			
			let _hTabs_caption = _tabs_caption.innerHeight();
			let _wTabs_navigation = _tabs_navigation.innerWidth();
			
			$(_tabs_navigation).css('paddingTop', _hTabs_caption + 100);
			$(_tabs_caption).css('width', _wTabs_navigation);


		} else {
			
			$('.carusel-tab .tabs__nav').addClass('js-dragscroll-wrap');
			$('.carusel-tab .tabs__caption').addClass('big-size js-dragscroll'); 	

		}
	}

</script>
<script>
	function rebuildViewedTabs(data){
		if (data.length > 0){
			$('#viewed-carousel-tab-ul').prepend('<li class="active tabs__reloadable__content_tab" id="viewed-products-reloadable-tab" ><?php echo $heading_title; ?></li>');
			$('.tabs__reloadable__content').removeClass('active');
			$('.tabs__reloadable__content_tab').removeClass('active');
			$('.tabs__reloadable__content:first').addClass('active');
			$('#viewed-tabs').tabs({active:1});
			$('#viewed-products-reloadable-tab').trigger('click');
			rePaintTabs();
		} else{
			$('#viewed-carousel-tab-ul').prepend('<li></li>')
		 	rePaintTabs();
			
		}
	}
	
</script>


<!--/section-->		