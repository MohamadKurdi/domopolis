<?php echo $header; ?>
<style type="text/css">
	#catalog_btn_wrap{
		display: none;
	}
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
		color: #1f9f9d;
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
		color: #1f9f9d;
		display: block;
		text-align: center;	
		text-decoration: underline;
		text-transform: uppercase;		
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
	.home_sub_head{
		display: grid;
		grid-template-columns: 205px 1fr;
		gap: 15px;
		margin-top: 22px;
	}
	.home_sub_head .logo_cart_home{
		width: 100%;
		overflow: hidden;
/*		margin-top: 22px;*/
	}
	.home_sub_head .menu{
		height: max-content;
	}
	#home-cart{
		position: relative;
	}
	#close_home-cart{
	    position: absolute;
	    top: 0;
	    right: 15px;
	    bottom: 0;
	    margin: auto;
	}
	#home-cart-products-in-cart-buttons{
		margin-right: 15px;
	}
	@media screen and (max-width: 992px) {
		.home_sub_head{
			display: flex;
			flex-direction: row;
			gap: 0;
		    order: 3;
		}
		.home_sub_head .menu{
			display: none
		}
	}
	@media screen and (max-width: 560px) {
		#catalog_btn_wrap{
			/*display: flex;*/
			display: none;
		}
		.home_sub_head{
			margin-bottom: 15px !important;
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
			background: #54a53a;
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
			order: 7;
			width: 100%;
		}
		footer{
			order: 11
		}
	}
	@media screen and (max-width: 480px){

		.brand-slider .swiper-slide {
			min-height: 90px;
		}
		.slider-section.carusel-tab{
			margin-top: 0;
		}
		#carousel-brand-logo{
			margin-bottom: 0;
		}
	}
	
</style>
<div class="wrap home_sub_head">
	<div class="menu">
		<div id="mmenu_home" class="menu-list">
	    	<?php echo $mmenu; ?>
		</div>
	</div>
	<div class="logo_cart_home">
		<span class="ajax-module-reloadable" data-modpath="common/home/homecart" data-reloadable-group="customer"></span>
		<?php if ($this->config->get('site_position') == '1') { ?>
			<?php echo $content_top; ?>
		<?php } ?>
	</div>
</div>


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

<?php if ($this->config->get('site_position') == '1') { ?>
	<?php echo $content_bottom; ?>
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

	<?php if ($hb_snippets_kg_enable == '1' && !empty($hb_snippets_kg_data)){echo html_entity_decode($hb_snippets_kg_data);} ?>
	<?php if ($hb_snippets_local_enable == 'y' && !empty($hb_snippets_local_snippet)) {echo html_entity_decode($hb_snippets_local_snippet);} ?>

	<?php if ($this->config->get('config_vk_enable_pixel')) { ?>
		<script>
			var VKRetargetFunction = function(){
				if((typeof VK !== 'undefined')){
					console.log('VK trigger view_home');
					VK.Retargeting.ProductEvent(<?php echo $this->config->get('config_vk_pricelist_id'); ?>, 'view_home', {}); 
				} else {
					console.log('VK is undefined');
				}
			};
		</script>
	<?php } ?>

	<?php echo $footer; ?>