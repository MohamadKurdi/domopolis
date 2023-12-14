<?php echo $header; ?>
<style type="text/css">
	#catalog_btn_wrap{
		display: none;
	}
	@media screen and (max-width: 560px) {
		#catalog_btn_wrap{
			display: block
		}
		body{
			display: flex;
			flex-direction: column;
		}
		header{
			order: 1;
		}
		.categories_home{
			order: 2;
			display: none
		}
		.categories_home .title{
			display: none;
		}
		.category_wall_allcat_container{
			margin-top: 10px !important;
			margin-bottom: 10px !important;
		}
		.slider-top-wrap{
			order: 3
		}
		#catalog_btn_wrap{
			order: 4;
		}
		#catalog_btn_wrap button{
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100%;
			background: #51a881;
			color: #fff;
			font-size: 18px;
			padding: 10px 0;
			border-radius: 5px;
			margin: 10px 0;
		}
		#catalog_btn_wrap button svg{
			width: 24px;
			margin-right: 10px
		}
		#catalog_btn_wrap button path{
			fill: #fff;
		}
		#carousel-brand-logo{
			order: 5;
			width: 100%;
		}
		.slider-section.carusel-tab{
			order:6;
			width: 100%;
		}
		.new_v_slider{
			order: 7;
		}
		.categories-photo{
			order: 8
		}
		.wrap.html_block{
			order: 9;
		}
		.pluses{
			order: 10;
			width: 100%;
		}
		footer{
			order: 11
		}
		@media screen and (max-width: 480px){
			.slider-section.carusel-tab{
				margin-top: 0;
			}
			#carousel-brand-logo{
				margin-bottom: 0;
			}
		}
	}
</style>
<span class="ajax-module-reloadable" data-modpath="common/home/homecart" data-reloadable-group="customer"></span>
<?php if ($this->config->get('site_position') == '1') { ?>
	<?php echo $content_top; ?>
<?php } ?>


<style type="text/css">
	.brand-slider .swiper-slide {
		min-height: 105px;
	}
	.btn-more-main_text-wrap{
		display: flex;
		margin: auto;
		background: transparent;
		margin-top: 20px;
	}
	.btn-more-main_text-wrap i{
		display: flex;
		align-items: center;
		transform: rotate(90deg);
		color: #51a881;
		margin-left: 10px;
		transition: .3s ease-in-out;
	}
	.btn-more-main_text-wrap.open{

	}
	.btn-more-main_text-wrap.open i{
		transform: rotate(-90deg);
	}
	.btn-more-main_text{

		cursor: pointer;
		font-size: 18px;
		font-weight: 500;
		color: #51a881;
		display: block;
		text-align: center;	
		text-decoration: underline;
		text-transform: uppercase;		
	}
	@media screen and (max-width: 480px){
		.brand-slider .swiper-slide {
			min-height: 94px;
		}
	}
	@media screen and (max-width: 480px){
		.brand-slider .swiper-slide {
			min-height: 90px;
		}
	}
	.swiper-lazy-preloader {
		margin-left: 0;
		margin-top: 0;
		z-index: 10;
		transform-origin: 50%;
		animation: none;
		box-sizing: border-box;
		border: 4px solid var(--swiper-preloader-color, #f7f4f4);
		border-radius: 50%;
		border-top-color: #f7f4f4;
		width: 105%;
		height: 100%;
		background: #f7f4f4;
		left: -10px;
		top: 0;

	}
	<?php if ($this->config->get('config_store_id') == 2) {?>
		.pluses{
			background: -webkit-linear-gradient(90deg, rgb(122, 176, 76), rgb(81, 168, 129));
			background: -moz-linear-gradient(90deg, rgb(122, 176, 76), rgb(81, 168, 129));
			background: linear-gradient(90deg, rgb(122, 176, 76), rgb(81, 168, 129));	    
			margin-bottom: 20px;
			padding: 60px 0;
		}		
		.pluses__icon img{
			filter: brightness(0) invert(1);
		}
		.pluses__item{
			color: #fff;
		}
	<?php } ?>
</style>


<div id="catalog_btn_wrap" class="wrap">
	<button class="catalog_btn_home">

		<svg viewBox="0 0 24 24" width="24" height="24" id="icon-catalog">
			<g clip-rule="evenodd" fill-rule="evenodd">
				<path d="m17 2.75735-4.2427 4.24264 4.2427 4.24261 4.2426-4.24261zm-5.6569 2.82843c-.7811.78104-.7811 2.04738 0 2.82842l4.2426 4.2427c.7811.781 2.0475.781 2.8285 0l4.2426-4.2427c.781-.78104.781-2.04738 0-2.82842l-4.2426-4.24264c-.781-.781048-2.0474-.781048-2.8285 0z"></path>
				<path d="m7 4h-4c-.55228 0-1 .44772-1 1v4c0 .5523.44772 1 1 1h4c.55228 0 1-.4477 1-1v-4c0-.55228-.44772-1-1-1zm-4-2c-1.65685 0-3 1.34315-3 3v4c0 1.6569 1.34315 3 3 3h4c1.65685 0 3-1.3431 3-3v-4c0-1.65685-1.34315-3-3-3z"></path>
				<path d="m7 16h-4c-.55228 0-1 .4477-1 1v4c0 .5523.44772 1 1 1h4c.55228 0 1-.4477 1-1v-4c0-.5523-.44772-1-1-1zm-4-2c-1.65685 0-3 1.3431-3 3v4c0 1.6569 1.34315 3 3 3h4c1.65685 0 3-1.3431 3-3v-4c0-1.6569-1.34315-3-3-3z"></path>
				<path d="m19 16h-4c-.5523 0-1 .4477-1 1v4c0 .5523.4477 1 1 1h4c.5523 0 1-.4477 1-1v-4c0-.5523-.4477-1-1-1zm-4-2c-1.6569 0-3 1.3431-3 3v4c0 1.6569 1.3431 3 3 3h4c1.6569 0 3-1.3431 3-3v-4c0-1.6569-1.3431-3-3-3z"></path>
			</g>
		</svg>
		<?php echo $text_retranslate_catalog_btn; ?>
	</button>
</div>

<?php if ($brands) { ?>
	<section id="carousel-brand-logo"  class="brand-top">
		<div class="wrap">
			<!--brand-slider-->
			<div class="brand-slider">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php foreach ($brands as $brand): ?>
							<?php if (!$brand['thumb']) continue; ?>
							<div class="swiper-slide">							
								<a href="<?php echo $brand['url'] ?>">
									<img data-src="<?php echo $brand['thumb'] ?>" width="100" height="100" class="swiper-lazy" title="<?php echo $brand['name'] ?>" alt="<? echo $brand['name']; ?>">
									<div class="swiper-lazy-preloader"></div>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<!-- arrows -->
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
				<!-- /arrows -->
				<!-- Add Pagination -->
				<div class="swiper-pagination"></div>
			</div>
			<!--/brand-slider-->
		</div>
	</section>
	<script>
		$(document).ready(function(){
			var swiper = new Swiper('#carousel-brand-logo .swiper-container', {
				slidesPerView: 2,
				loop: true,
				centeredSlides: false,
				preloadImages: false,   
				lazy: true,
				autoplay: {
					delay: 5000,
				},
			// init: false,
			// pagination: {
			// 	el: '#carousel-brand-logo .swiper-pagination',
			// 	clickable: true,
			// },
			breakpoints: {
				400: {
					slidesPerView: 4,
					spaceBetween: 20,
				},
				600: {
					slidesPerView: 4,
					spaceBetween: 20,
				},
				768: {
					slidesPerView: 5,
				},
				1000: {
					slidesPerView: 6,
				},
				1280: {
					slidesPerView: 7,
					navigation: {
						nextEl: '#carousel-brand-logo .swiper-next-slide',
						prevEl: '#carousel-brand-logo .swiper-prev-slide',
						clickable: true,
					},
				},
				1600: {
					slidesPerView: 9,
					navigation: {
						nextEl: '#carousel-brand-logo .swiper-next-slide',
						prevEl: '#carousel-brand-logo .swiper-prev-slide',
						clickable: true,
					},
				},
			}
		});


		});

	</script>
<?php } ?>

<?php if ($this->config->get('site_position') == '1') { ?>
	<?php echo $content_bottom; ?>
<?php } ?>



<?php if ($this->config->get('config_store_id') == 2) {?>
	<!--pluses-->
	<section class="pluses">
		<div class="wrap">
			<!--pluses-slider-->
			<div class="swiper-container pluses-slider">
				<div class="swiper-wrapper">
					<!--swiper-slide-->
					<div class="swiper-slide">
						<!--pluses__item-->
						<div class="pluses__item">
							<div class="pluses__icon">
								<img src="catalog/view/theme/kp/img/pluses-icon1.svg"  loading="lazy" width="100" height="100" alt="<?php echo $text_retranslate_7; ?> <?php echo $text_retranslate_8; ?>">
							</div>
							<div class="pluses__title"><?php echo $text_retranslate_7; ?></div>
							<p><?php echo $text_retranslate_8; ?></p>
						</div>
						<!--/pluses__item-->
					</div>
					<!--/swiper-slide-->

					<!--swiper-slide-->
					<div class="swiper-slide">
						<!--pluses__item-->
						<div class="pluses__item">
							<div class="pluses__icon">
								<img src="catalog/view/theme/kp/img/pluses-icon2.svg" loading="lazy" width="100" height="100" alt="Приятные цены Действительно лучшие цены на рынке">
							</div>
							<div class="pluses__title"><?php echo $text_retranslate_9; ?></div>
							<p><?php echo $text_retranslate_10; ?></p>
						</div>
						<!--/pluses__item-->
					</div>
					<!--/swiper-slide-->

					<!--swiper-slide-->
					<div class="swiper-slide">
						<!--pluses__item-->
						<div class="pluses__item">
							<div class="pluses__icon">
								<img src="catalog/view/theme/kp/img/pluses-icon3.svg" loading="lazy" width="100" height="100" alt="100% гарантия качества от ведущих европейских производителей">
							</div>
							<div class="pluses__title"><?php echo $text_retranslate_11; ?></div>
							<p><?php echo $text_retranslate_12; ?></p>
						</div>
						<!--/pluses__item-->
					</div>
					<!--/swiper-slide-->

					<!--swiper-slide-->
					<div class="swiper-slide">
						<!--pluses__item-->
						<div class="pluses__item">
							<div class="pluses__icon">
								<img src="catalog/view/theme/kp/img/pluses-icon4.svg" loading="lazy" width="100" height="100" alt="Постоянные акции и скидки для наших клиентов">
							</div>
							<div class="pluses__title"><?php echo $text_retranslate_13; ?></div>
							<p><?php echo $text_retranslate_14; ?></p>
						</div>
						<!--/pluses__item-->
					</div>
					<!--/swiper-slide-->
				</div>
				<div class="swiper-pagination"></div>
			</div>
			<!--/pluses-slider-->
		</div>
	</section>
	<!--/pluses-->
<?php } else { ?>
	<!--pluses-->
	<section class="pluses">
		<div class="wrap">
			<!--pluses-slider-->
			<div class="swiper-container pluses-slider">
				<div class="swiper-wrapper">
					<!--swiper-slide-->
					<div class="swiper-slide">
						<!--pluses__item-->
						<div class="pluses__item">
							<div class="pluses__icon">
								<img src="catalog/view/theme/kp/img/pluses-icon1.svg" loading="lazy" width="100" height="100" alt="<?php echo $text_retranslate_15; ?> <?php echo $text_retranslate_16; ?>">
							</div>
							<div class="pluses__title"><?php echo $text_retranslate_15; ?></div>
							<p><?php echo $text_retranslate_16; ?></p>
						</div>
						<!--/pluses__item-->
					</div>
					<!--/swiper-slide-->

					<!--swiper-slide-->
					<div class="swiper-slide">
						<!--pluses__item-->
						<div class="pluses__item">
							<div class="pluses__icon">
								<img src="catalog/view/theme/kp/img/pluses-icon2.svg" loading="lazy" width="100" height="100" alt="<?php echo $text_retranslate_17; ?> <?php echo $text_retranslate_18; ?>">
							</div>
							<div class="pluses__title"><?php echo $text_retranslate_17; ?></div>
							<p><?php echo $text_retranslate_18; ?></p>
						</div>
						<!--/pluses__item-->
					</div>
					<!--/swiper-slide-->

					<!--swiper-slide-->
					<div class="swiper-slide">
						<!--pluses__item-->
						<div class="pluses__item">
							<div class="pluses__icon">
								<img src="catalog/view/theme/kp/img/pluses-icon3.svg" loading="lazy" width="100" height="100" alt="<?php echo $text_retranslate_19; ?> <?php echo $text_retranslate_20; ?>">
							</div>
							<div class="pluses__title"><?php echo $text_retranslate_19; ?></div>
							<p><?php echo $text_retranslate_20; ?></p>
						</div>
						<!--/pluses__item-->
					</div>
					<!--/swiper-slide-->

					<!--swiper-slide-->
					<div class="swiper-slide">
						<!--pluses__item-->
						<div class="pluses__item">
							<div class="pluses__icon">
								<img src="catalog/view/theme/kp/img/pluses-icon4.svg" loading="lazy" width="100" height="100" alt="<?php echo $text_retranslate_21; ?> <?php echo $text_retranslate_22; ?>">
							</div>
							<div class="pluses__title"><?php echo $text_retranslate_21; ?></div>
							<p><?php echo $text_retranslate_22; ?></p>
						</div>
						<!--/pluses__item-->
					</div>
					<!--/swiper-slide-->
				</div>
				<div class="swiper-pagination"></div>
			</div>
			<!--/pluses-slider-->
		</div>
	</section>
	<!--/pluses-->
<?php } ?>	




<script>
	if ($(window).width() <= '500'){
		window.onload = function() {
			$('#popular-goods .carusel-mob.swiper-container').each(function()	{
				$(this).find('.product__item').addClass('swiper-slide');
				$(this).find('.product__grid').css('flex-wrap','inherit');
				var swiper = new Swiper(this, {
					loop: false,
					slidesPerView: 1,
					centeredSlides: true,
					spaceBetween: 30,
					grabCursor: true, 
				});
			});
		};
		$('#catalog_btn_wrap button').on('click', function(){
			$("#mobile-category").toggleClass("open_menu_category");
		})
	}	

	<?php if ($this->config->get('config_store_id') == 1) {?>

		$('#main_text_on_main_page').closest('.wrap').prepend('<button class="btn-more-main_text-wrap"><span class="btn-more-main_text"><?php echo $text_retranslate_23; ?></span><i class="fas fa-angle-right"></i></button>');
		$('#main_text_on_main_page').hide();
		window.onload = function() {
			$('.btn-more-main_text').on('click', function(){
				let _textBtn = $(this).text();
				$(this).text(_textBtn == "<?php echo $text_retranslate_23; ?>" ? "<?php echo $text_retranslate_24; ?>" : "<?php echo $text_retranslate_23; ?>");
				$(this).parent().toggleClass('open');
				$('#main_text_on_main_page').toggle();
			});
		};
	    // 
	  <?php } ?>
	</script>

	<?php if ($hb_snippets_kg_enable == '1'){echo html_entity_decode($hb_snippets_kg_data);} ?>
	<?php if ($hb_snippets_local_enable == 'y'){echo html_entity_decode($hb_snippets_local_snippet);} ?>

	<?php if ($this->config->get('config_vk_enable_pixel')) { ?>
		<script>
			
		</script>
	<?php } ?>

	<?php echo $footer; ?>