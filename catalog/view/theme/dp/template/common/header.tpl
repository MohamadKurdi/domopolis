<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
	<!--<![endif]-->
	<head>
		
		<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/header_pwa.tpl')); ?>
		<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/header_logged_datalayer.tpl')); ?>
		<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/header_head.tpl')); ?>
			
		<style type="text/css">	
			iframe[name="helpcrunch-iframe"]{
				left: initial !important;
				right: 4% !important;
			}
			.home.home iframe[name="helpcrunch-iframe"]{
				display: none !important;
			}
			#promo-code-txt-action{
				font-size: 20px
			}
			.parent.level1.brand,	
			.menu-list li.parent.brand{
				display: none !important; 
			}
			.menu-horizontal li.catalog-li .menu-list.open li.parent.brand{
/*				display: flex !important;*/
			}
			#mmenu_home{
			    background: #FFFFFF;
				box-shadow: 0px 10px 36px rgba(10, 32, 45, 0.12);
			}
			#mmenu_home.menu-list{    
				display: block;
    			position: relative;
				top: 0;
    			left: initial;
    		}
    		#mmenu_home li.parent>.topmenu{
    			z-index: 9;
    		}
    		#mmenu_home.open_main_menu{
    			opacity: 0;
    		}
			body.home .menu-horizontal .catalog__btn.razdel:hover #main-overlay-popup{
			    display: block;
    			z-index: 101;
			}
			header #account_header .content ul li a:hover,
			#mmenu_home li.level1 .topmenu .children-category-list a:hover, 
			#new_mmenu li.level1 .topmenu .children-category-list a:hover{
				text-decoration: underline !important;
    			color: #404345;
			}
			.simple-processing-dadata-request::after {
			content: '';
			position: absolute;
			right: 0;
			top: 20px;
			width: 30px;
			height: 30px;
			background-position: center;
			background-size: contain;
			background-repeat: no-repeat;
			background-image: url(/catalog/view/theme/dp/img/load_more.svg);
			}
			.grecaptcha-badge{
			right: -999px !important;
			}
			.home_sub_head{
				margin-bottom: 40px !important;
			}
			header .menu-horizontal > ul > li span.top_menu-horizontal{white-space: nowrap;}
			
			
			header .drop-list__menu.small_icon a{
				display: flex;
				align-items: center;
				font-weight: 400;
				font-size: 18px;
				line-height: 25px;
				color: #000000;
				padding: 15px 20px;
			}
			header .drop-list__menu.small_icon a .icon_b_none{
				display: flex;
				align-items: center;
				justify-content: center;
				margin-right: 13px;
			}
			header .information_drop-list .drop-list__menu,
			header .phone_drop-list .drop-list__menu{
		        width: 250px;
			}
			header .information_drop-list .drop-list__menu{
				left: 0;
			}
			header .drop-list__menu.small_icon li{
				border-bottom: 0;
				position: relative;
			}
			header .drop-list__menu.small_icon li:not(last-child)::after{
				content: "";
			    display: block;
			    height: 1px;
			    position: absolute;
			    width: 100%;
			    background: linear-gradient(90deg, rgba(30, 113, 163, 0) 0%, #1E71A3 50.83%, rgba(30, 113, 163, 0) 100%);
			    bottom: 0;
			    left: 0;
			    opacity: .3;
			}
			header .information_drop-list .contact_wrap,
			header .phone_drop-list .contact_wrap{
				display: flex;
				flex-direction: column;
			}
			header .phone_drop-list .contact_wrap li{
				border: none;
			}
			header .phone_drop-list .contact_wrap a{
				display: flex;
				padding: 5px 15px;
				align-items: center;
				color: #404345;
				font-weight: 500;
				font-size: 14px;
				line-height: 17px;
			}
			header .phone_drop-list .contact_wrap a svg{
				margin-right: 13px;
				margin-left: 0;
				width: 30px;
			}
			header .phone_drop-list .contact_wrap a span{
				display: flex;
				flex-direction: column;
				gap: 8px;
			}
			header .phone_drop-list .contact_wrap a span .worktime_text{
				font-weight: 400;
				font-size: 12px;
				line-height: 15px;
				color: rgba(0, 0, 0, 0.6);
			}
			header .phone_drop-list .element-target-click-event i{margin-right: 10px;}
			header .phone_drop-list .drop-list__menu .worktime{
			    padding: 10px 20px;
			    text-decoration: none;
			    font-size: 14px;
			    display: none;
			}
			header .phone_drop-list .drop-list__menu .social-contact .social-contact__list{
			    width: 100%;
			    display: flex;
			    flex-direction: column;
			    
			}
			header .phone_drop-list .drop-list__menu .social-contact li{
				border-bottom: 0;
			}
			header .phone_drop-list .drop-list__menu .social-contact li a{
				display: flex;
				padding: 5px 15px;
				align-items: center;
				color: #404345;
				font-weight: 500;
				font-size: 14px;
				line-height: 17px;
			}
			header .phone_drop-list .drop-list__menu .social-contact li a img,
			header .phone_drop-list .drop-list__menu .social-contact li a svg{
				margin-right: 13px;
				width: 30px;
				margin-left: 0;
			}

			header .menu-horizontal .catalog__btn {
				width: 205px;
				font-weight: 500;
				font-size: 16px;
				line-height: 19px;
				color: #696F74;
				padding-left: 12px;
			}
			header .menu-horizontal .catalog__btn svg{
				margin-right: 17px;
			}
			section{margin:50px auto}
			section.inside{padding:50px 0;margin:0}
			.wrap{position:relative;max-width:1395px;width:100%;padding:0 20px;margin:0 auto;height:100%}
			.wrap:after{clear:both;content:"";display:block}
			#mmenu_home li.parent>.topmenu,#new_mmenu li.parent>.topmenu{left:276px;}
			#mmenu_home, #new_mmenu{
				width:205px;
				border: 1px solid #DDE1E4;
				box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
				border-radius: 12px;
				z-index: 101;
			}
			#new_mmenu{
				border-radius:  12px 0 0 12px;
				border: 1px solid #DDE1E4;
				border-right-color: #fff;
			}
			#mmenu_home li.level1 > a, #new_mmenu li.level1 > a{
				padding: 11px 35px 11px 46px;
				width: 100%;
				position: relative;
				font-weight: 500;
				font-size: 14px;
				line-height: 17px;
				color: #404345;
				

			}
			#new_mmenu li.level1 > a{
			 	background: #fff;
			 }
			 #new_mmenu li.level1:nth-of-type(1) > a{
			 	border-radius: 12px 0 0 0;
			 }
			 #new_mmenu li.level1.last_parent_menu > a{
			 	border-radius:  0 0 0 12px;
			 }
			 #new_mmenu li.level1:last-child > a{
			 	border-radius: 0 0 0 12px;
			 }
			 #new_mmenu li.level1.open_menu > a,
			 #new_mmenu li.level1:hover > a{
		 	    background: #eef4f8;
			 }
			#mmenu_home::before, #new_mmenu::before{
				content: '';
				position: absolute;
				width: 20px;
				height: 100%;
				right: -20px;
				top: 0;
				display: none;
			}
			#mmenu_home::after, #new_mmenu::after{
				content: '';
				position: absolute;
				width: 100%;
				height: 27px;
				top: -25px;
			}
			#mmenu_home li.level1 > a > sup,#new_mmenu li.level1 > a > sup{display: none;}
			#mmenu_home li.level1 > a svg,#new_mmenu li.level1 > a svg{width:18px !important;height:18px; top: 0; bottom: 0; margin: auto;}
			.catalog_list__block .catalog__link_wrap>li>a>svg path,
			header #account_header .content ul li > a svg path,
			#mmenu_home li.level1 > a svg path,#new_mmenu li.level1 > a svg path{
				fill: #888F97 !important;
			}

			#mmenu_home li.level1 > a > i, #mmenu_home .level1.open_menu > a > i,#new_mmenu li.level1 > a > i,#new_mmenu .level1.open_menu > a > i{color:#BABEC2 !important;transition: .15s ease-in-out}
			#mmenu_home .level1.open_menu,#new_mmenu .level1.open_menu{background:rgba(30, 113, 163, 0.08)}
			#mmenu_home li.parent > .topmenu .wrap-children,#new_mmenu li.parent > .topmenu .wrap-children{padding:20px 0}
			.menu-list li.parent>.topmenu .children-category-list ul > li:not(last-child){
				margin-bottom: 10px;
			}
			#mmenu_home li.level1 .topmenu .children-category-list a,#new_mmenu li.level1 .topmenu .children-category-list a{
				display: block;
				padding:0;
				font-weight: 500;
				font-size: 14px;
				line-height: 15px;
				color: #404345;
			}
			#mmenu_home .topmenu .title_mmenu,#new_mmenu .topmenu .title_mmenu{font-size:24px;font-weight:500;margin-bottom:19px;display:block}
			#mmenu_home li.parent > .topmenu .category-list-menu .title_mmenu,#new_mmenu li.parent > .topmenu .category-list-menu .title_mmenu{text-align:center}
			#mmenu_home li.parent > .topmenu .main-center-cat-block .product__item a,#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item a{padding:0}
			#mmenu_home li.parent > .topmenu .main-center-cat-block .product__item .product__line__promocode,#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item .product__line__promocode{
			display: none !important;
			}
			#mmenu_home li.parent > .topmenu .main-center-cat-block .product__item .product__title a ,#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item .product__title a{font-size:17px;line-height:22px}
			#mmenu_home li.parent > .topmenu .main-center-cat-block .product__item .product_add-favorite,#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item .product_add-favorite{display:none}
			#mmenu_home li.parent > .topmenu .main-center-cat-block .product__item:hover .product__info .product__delivery,#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item:hover .product__info .product__delivery{display:none!important}
			#mmenu_home li.parent > .topmenu .main-center-cat-block .product__item:hover .product__btn-cart,#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item:hover .product__btn-cart{width:70px}
			#mmenu_home li.parent > .topmenu .main-center-cat-block .product__item .product__btn-cart button,#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item .product__btn-cart button{width:70px}
			#mmenu_home li.parent > .topmenu .main-center-cat-block .product__item:hover .product__btn-cart button,#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item:hover .product__btn-cart button{font-size:0}
			#mmenu_home li.parent > .topmenu .main-center-cat-block .product__item:hover .product__bottom,#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item:hover .product__bottom{flex-direction:row}
			#mmenu_home li.parent > .topmenu .main-center-cat-block .product__item:hover .product__info,#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item:hover .product__info{position:inherit;top:0;left:0;right:0;background:0;border:0;border-top:none;box-shadow:none;padding:0 0 0 15px;flex-direction:column}
			#mmenu_home li.parent > .topmenu .children-category-list ul > li.level2 a i,#new_mmenu li.parent > .topmenu .children-category-list ul > li.level2 a i{transform:rotate(0)!important}
			#mmenu_home li.parent > .topmenu .children-category-list ul > li.level2.open_menu a i::before,#new_mmenu li.parent > .topmenu .children-category-list ul > li.level2.open_menu a i::before{transform:rotate(0deg)!important}
			.menu-list li.parent > .topmenu .children-category-list ul > li.parent{display: flex;flex-direction: column;justify-content: flex-start;width: auto;}
			#mmenu_home .topmenu.col-2-list .wrap-children,#new_mmenu .topmenu.col-2-list .wrap-children{grid-template-columns:5fr 2fr 5fr!important}
			#mmenu_home .topmenu.col-2-list .main-center-cat-block > ul,#new_mmenu .topmenu.col-2-list .main-center-cat-block > ul{grid-template-columns:repeat(2,245.5px)!important}
			#mmenu_home .topmenu.col-2-list .wrap-children .children-category-list > ul,#new_mmenu .topmenu.col-2-list .wrap-children .children-category-list > ul{
			/*columns:2;
			display:block;
			height:100%*/
			display: flex;
			flex-wrap: wrap
			}
			#mmenu_home .topmenu.col-2-list .wrap-children .children-category-list > ul > li,#new_mmenu .topmenu.col-2-list .wrap-children .children-category-list > ul > li{display:inline-block;flex-basis: 50%;padding:0 5px;}
			
			#mmenu_home .topmenu.col-3-list .wrap-children,#new_mmenu .topmenu.col-3-list .wrap-children{grid-template-columns:9fr 2fr 4fr!important}
			#mmenu_home .topmenu.col-3-list .main-center-cat-block > ul,#new_mmenu .topmenu.col-3-list .main-center-cat-block > ul{grid-template-columns:245.5px!important}
			#mmenu_home .topmenu.col-3-list .wrap-children .children-category-list > ul,#new_mmenu .topmenu.col-3-list .wrap-children .children-category-list > ul{columns:3;display:block;height:100%}
			#mmenu_home .topmenu.col-3-list .wrap-children .children-category-list > ul > li,#new_mmenu .topmenu.col-3-list .wrap-children .children-category-list > ul > li{display:inline-block}
			#mmenu_home .topmenu.col-2-list .main-center-cat-block .product__item:nth-of-type(3),#new_mmenu .topmenu.col-2-list .main-center-cat-block .product__item:nth-of-type(3),#mmenu_home .topmenu.col-3-list .main-center-cat-block .product__item:nth-of-type(2),#new_mmenu .topmenu.col-3-list .main-center-cat-block .product__item:nth-of-type(2),#mmenu_home .topmenu.col-3-list .main-center-cat-block .product__item:nth-of-type(3),#new_mmenu .topmenu.col-3-list .main-center-cat-block .product__item:nth-of-type(3){display:none!important}
			@media (min-width: 1000px) and (max-width: 1440px) {
				section.slider-section{overflow:inherit!important}
				#mmenu_home li.parent > .topmenu .main-center-cat-block .product__item .product__title a, #new_mmenu li.parent > .topmenu .main-center-cat-block .product__item .product__title a{font-size:14px}
				#mmenu_home li.level1 .topmenu .children-category-list a, #new_mmenu li.level1 .topmenu .children-category-list a{font-size:14px;line-height:17px}

				#mmenu_home li.parent > .topmenu .wrap-children, #new_mmenu li.parent > .topmenu .wrap-children{padding:15px 0}
				#mmenu_home .topmenu .title_mmenu, #new_mmenu .topmenu .title_mmenu{font-size:18px;margin-bottom: 10px;}
				.menu-list li.parent > .topmenu .category-list-menu_brands .collections_ul {grid-template-rows: repeat(4,85px) !important;grid-gap: 10px !important;}
				.menu-list li.parent > .topmenu .category-list-menu_brands .collections_ul img{height: 100%}
				#mmenu_home li.parent > .topmenu .main-center-cat-block ul, #new_mmenu li.parent > .topmenu .main-center-cat-block ul{grid-template-columns:repeat(2,226.5px) !important}
				#mmenu_home .topmenu.col-1-list .main-center-cat-block .product__item:nth-of-type(3), #new_mmenu .topmenu.col-1-list .main-center-cat-block .product__item:nth-of-type(3){display:none!important}
				#mmenu_home .topmenu.col-2-list .wrap-children, #new_mmenu .topmenu.col-2-list .wrap-children{grid-template-columns:6fr 2fr 5fr!important}
				#mmenu_home li.parent > .topmenu.col-1-list .wrap-children, #new_mmenu li.parent > .topmenu.col-1-list .wrap-children{grid-template-columns:3fr 2fr 6fr}
				.menu-list li.parent > .topmenu .category-list-menu .collections_ul > div a img{max-height: 70px !important; }
				.menu-list li.parent > .topmenu .category-list-menu .collections_ul {
				height: calc(100% - 109px) !important;
				}
			}
			header{position:relative;z-index:220;background:#fff}
			header.fixed{padding-bottom:72px}
			header .head{display:flex;align-items:stretch;padding:5px 0}
			header .logo{flex-shrink:0}
			header .logo a{display:block}
			header .logo a svg,header .logo img{display:block;height:50px!important}
			header .middle{padding-left:15px;flex-grow:1}
			header .sub-head{display:flex;align-items:center;width: 100%;gap: 10px;}
			header .sub-head>div,header .sub-head>span{margin-left:0}
			header .sub-head>div:first-child{margin-left:0;margin-right: auto;margin-bottom: auto;margin-top: auto;}
			header .sub-head .menu-horizontal a{font-size: 15px;font-weight: 400;}
			/*header .head>.icons-link{display:none}*/
			header .icons-link{display:flex;align-self:flex-end;padding-left:10px}
			header .icons-link > li{margin:0 15px}
			header .icons-link li:last-child{margin-right:0}
			header .icons-link a{position:relative;display:flex;height:50px}
			header .icons-link path{transition:all .5s ease}
			header .icons-link a.favorite .txt{display: none;}
			header .icons-link li svg{align-self:center;margin:0 auto}
			header .icons-link i{
			    position: absolute;
				right: 5px;
				bottom: 6px;
				height: 14px;
				color: #fff;
				box-sizing: content-box;
				width: auto;
				display: flex !important;
				text-align: center;
				justify-content: center;
				min-width: 14px;
				background: #EB3274;
				border: 2px solid #FFFFFF;
				border-radius: 29px;
				font-weight: 600;
				font-size: 10px;
				line-height: 12px;
			}
			header.fixed .top-menu{position:fixed;top:0;left:0;width:100%;z-index:5000;box-shadow:0 0 30px rgba(0,0,0,0.1)}
			header .top-menu{position:relative;z-index:200;background: #FFFFFF;border-top: 1px solid #DDE1E4;}
			header .top-menu .inner{height:70px;display:flex;justify-content:space-between}
			header .top-menu .icons-link{padding-left:0;display:inline-flex;vertical-align:top}
			header .top-menu .icons-link li{margin:0 5px;display: flex;justify-content: center;align-items: center;}
			header .top-menu .icons-link li > button,
			header .top-menu .icons-link li > a{
				height: 45px;
				padding: 0;
				width: 45px;
				border: 1px solid #DDE1E4;
				border-radius: 71px;
				display: flex;
				align-content: center;
				justify-content: center;
				position: relative;
				transition: border 0.3s ease, box-shadow 0.5s ease;
				background: #fff;
			}
			header .top-menu .icons-link > li > button:hover,
			header .top-menu .icons-link > li > a:hover{
				box-shadow: 0 8px 20px rgb(0 0 0 / 20%);
    			border-color: #c0e856;
			}
			header .top-menu .icons-link > li:last-child{
				margin-right: 0;
			}
			header .top-menu .icons-link a.favorite svg{width:30px;height:26px}
			header .top-menu .icons-link button svg,header .top-menu .icons-link a.cart svg{width:33px;height:30px}
			header .top-menu .icons-link a.profile svg{width:30px;height:30px}
			header .mob-menu__btn{display:inline-flex;justify-content:center;align-items:center}
			header .menu-horizontal{display:flex; gap: 15px; align-items: center;width: 100%;}
			header .menu-horizontal>ul{display:flex;height:100%}
			header .menu-horizontal>ul>li:first-child{margin-left:0}
			header .menu-horizontal>ul>li{margin-left:30px}
			header .menu-horizontal>ul>li i{font-style:normal}
			header .menu-horizontal>ul>li i b{
				font-weight: 600;
			    position: absolute;
			    right: -7px;
			    top: -7px;
			    font-size: 14px;
			    color: #e93273;
			}
			header .menu-horizontal>ul>li>a{font-size:18px;white-space: nowrap;font-weight: 400;display:flex;height:100%;align-items:center;transition:color .2s ease;-webkit-touch-callout:none;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;justify-content: flex-start;}
			header .menu-horizontal a.stocks{color:#e16a5d}
			header .menu-horizontal>ul>li svg{
			  	margin: 0;
			  	width: 20px !important;
			}
			header .social-contact__list{display:none;vertical-align:top;height:100%}
			/*header .social-contact__list li:first-child{margin-left:0}
			header .social-contact__list li{display:inline-flex;align-items:center;height:100%;margin-left:10px}
			header .social-contact__list a{display:flex;justify-content:center;align-items:center;width:30px;height:30px;border-radius:50%;transition:background .2s ease}
			header .social-contact__list a.facebook{background-image:linear-gradient(to bottom,#03a9d8,#009cdf,#008de3,#007ce4,#0069e0);color:#fff}
			header .social-contact__list a.facebook:hover{background-image:linear-gradient(to bottom,#00c6ff,#00b5ff,#00a3ff,#008fff,#0078ff)}
			header .social-contact__list a.viber{background:#7c509a}
			header .social-contact__list a.viber:hover{background-color:#9967bf}
			header .social-contact__list a.tg{background:#2f89ce}
			header .social-contact__list a.tg:hover{background-color:#37c6f9}
			header .social-contact__list a.tg svg{position:relative;left:-1px;top:1px}
			header .social-contact__list a.wa{background:#48c95f}
			header .social-contact__list a.wa:hover{background-color:#53de6a}
			header .social-contact__list a.wa svg{position:relative;top:-1px}*/
			@media screen and (min-width: 440px) {
			header .social-contact__list{display:inline-flex}
			header .middle{padding-left:20px; display: flex;align-items: center;}
			}
			@media screen and (min-width: 768px) {
			header .sub-head{display:flex;align-items:flex-start;width: 100%;gap: 30px;}
			header .logo a svg,header .logo a,header .logo img{height:60px!important}
			header .middle{padding-left:44px;display: flex;align-items: center;}
			}
			@media screen and (min-width: 1000px) {
				#credential_picker_container{
					bottom: 0;
					top: initial !important;
				}
				#mmenu_home li.level1 .topmenu .children-category-list a, #new_mmenu li.level1 .topmenu .children-category-list a {
					color: #3b6d9a;
					transition: .15s ease-in-out;
				}
				#mmenu_home li.level1 .topmenu .children-category-list a:hover, #new_mmenu li.level1 .topmenu .children-category-list a:hover{
					color: #EB3274;
				}
				header .menu-horizontal .catalog__btn{
				    background: rgba(255, 255, 255, 0.11);
				}

			header .head>.icons-link{display:flex}
			header .mob-menu__btn{display:none}
			header .menu-horizontal{display:flex; gap: 15px; align-items: center;}
			}
			@media screen and (min-width: 1600px) {
			header .head{padding:20px 0}
			
			header .icons-link{padding-left:70px}
			header .icons-link > li{margin:0 30px}
			header .menu-horizontal>ul>li>a{font-size:18px;white-space: nowrap;font-weight: 400;}
			header .menu-horizontal>ul>li{margin-left:40px}
			header .menu-horizontal>ul>li:nth-child(1) i{display:flex;}
			}
			.b24-widget-button-wrapper{display: none !important;}
			.ui-helper-hidden-accessible{border:0;clip:rect(0 0 0 0);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px}
			.ui-widget-content{border:1px solid #aaa;background:#fff;color:#222;z-index:99 !important}
			.ui-widget-content li a{display:grid;padding:7px 15px;grid-template-columns:1fr 7fr;grid-column-gap:10px;align-items:center;transition:all .2s ease-in-out}
			.ui-widget-content li:hover a{background:#f7f4f4}
			.ui-widget-content li a img{margin:auto}
			.ui-widget-content li a h4{font-weight:500;text-align:left}

			header #account_header,header #header-small-cart{position:relative;display:flex}
			header #account_header > button{display:flex;align-items:center}
			header #account_header > button .user-names{font-weight: 600;font-size: 16px;border-bottom: 1px dashed #ffffff;height: auto;color: #fff;}
			header #account_header > button span i{display:none}
			header #account_header > button,header #header-small-cart button{background-color:transparent;margin:auto}
			header #header-small-cart button:before{content: '';position: absolute;right: 0;left: -11px;width: 70px;bottom: -10px;height: 20px;}
			header #header-small-cart .btn-cart{
				background: transparent;
				border: 0;
				font-weight: 500;
				font-size: 16px;
				line-height: 19px;
				color: #3050C2;
				margin: 0;
				height: max-content;
				width: max-content;
				display: flex;
			}
			header #header-small-cart .btn-cart:hover{color:#21aeac}
			header #account_header .content{min-width:320px!important}
			header #account_header .content.active{display: block;}
			header #account_header .content.user_logOut{min-height:120px!important;min-width:450px!important}
			header #account_header .content.user_logOut > a{
			    cursor: pointer;
			    display: flex;
			    align-items: center;
			    border: 1px solid #DDE1E4;
			    box-shadow: 0 5px 12px rgba(0,0,0,.05);
			    border-radius: 20px;
			    padding: 7.5px 20px;
			    gap: 15px;
			    justify-content: center;
			    font-size: 16px;
			    width: max-content;
			    margin-bottom: 15px;
			}
			header #account_header .content.user_logOut svg{
				width: auto !important;
    			height: auto;
			}
			header #account_header .user_logOut > a i{
				position: inherit;
				margin: 0;
				padding: 0;
				font-size: 16px;
			}
			header #account_header .user_logOut p{margin-bottom:0;margin-top:20px;text-align:center;border-bottom:0!important}
			header #account_header .user_logOut p a{height:auto;display:inline-block;position:relative;text-decoration:underline}
			header #account_header .user_logOut p a i{position:relative}
			header #account_header .content > p{gap: 5px;font-size:18px;margin-bottom:20px;border-bottom:1px solid #cccccc47;display:block;padding:0 10px 4px 0; text-align: left; display: flex;align-items: center;}
			header #account_header .content > p > a{height: auto;display: flex; align-items: center;}
			header #account_header .content ul li{position:relative;margin:0}
			header #account_header .content ul li > a{height:auto;display:block;padding:7px 0px 7px 0px;font-weight:500}
			header #account_header .content ul li > a svg{width: 20px; height: 18px;margin-right: 10px;}
			header #account_header .user_logOut a i,header #account_header .content ul li a i{font-weight:700;left:0;right:inherit;bottom:0;top:0;margin:auto;color:#000000b5;background:transparent}
			header #account_header .content ul li span{color:#b2b2b2;font-size:13px;padding:7px 20px 7px 30px}
			header #account_header .content ul li span a{height:auto;display:inline-block;text-decoration:underline;color:#b2b2b2;font-size:13px}
			header #account_header .content,
			header #header-small-cart .content{
				clear:both;
				display:none;
				position:absolute;
				top:70px;
				right:0;
				padding:12px!important;
				min-height:150px;
				min-width:380px;
				box-shadow: 0px 19px 41px rgba(0, 0, 0, 0.42);
				border-radius: 12px;
				background:#fff;
				z-index:999
			}
			 header #header-small-cart .content{
			 	min-height: 100px;
			 }
			header #account_header .content ul li{
				justify-content: start;
			}
			header #account_header .content ul li a{
				width: 100%;
			    padding: 0;
			    border: 0;
			    margin-bottom: 5px;
			    font-weight: 500;
			    font-size: 14px;
			    line-height: 17px;
			    color: #696F74;
			    display: flex;
			    align-items: center;
			    justify-content: start;
			    padding: 5px 0;
			}
			header #header-small-cart .content span{font-size:18px;font-weight:500;display:inline-block;margin-bottom:15px}
			header #header-small-cart .content .empty{display:inline-block;margin:auto;position:absolute;top:0;right:0;bottom:0;left:0;height:25px;text-align:center;font-weight:500;font-size:16px}
			header #header-small-cart .content .empty p{
			font-size: 14px;
			margin-top: 15px;
			font-weight: 400;
			margin-bottom: 0;
			}
			header #header-small-cart .content .empty p a{
			color: #97B63C;
			border-bottom:1px dotted #97B63C;
			position: initial;
			height: auto;
			display: inline-block;
			}
			header #header-small-cart .content .mini-cart-info{overflow-y:auto;max-height:430px;
				border: 1px solid #DDE1E4;
				box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
				border-radius: 12px;
				margin-bottom:15px;

			}
			header #header-small-cart .content .mini-cart-info table{border-collapse:collapse;width:100%;
				background: #FFFFFF;

			}
			header #header-small-cart .content .mini-cart-info table tr:nth-of-type(2){
				border-top: 1px solid #DDE1E4;
			}
			header #header-small-cart .content .mini-cart-info table td{vertical-align:top;padding:10px}
			header #header-small-cart .content .mini-cart-info table td.image{width:75px; height: 75px}
			header #header-small-cart .content .mini-cart-info table td.image a{width:75px; height: 75px}
			header #header-small-cart .content .mini-cart-info table td.image img{
				border: 0.581395px solid #DDE1E4;
				box-shadow: 0px 4.65116px 11.6279px rgba(0, 0, 0, 0.07);
				border-radius: 4.65116px;
				max-height: 75px;
				height: 75px;
			}
			header #header-small-cart .content .mini-cart-info table td.name{
				vertical-align: middle;
				padding-left: 0;
			}
			header #header-small-cart .content .mini-cart-info table td.name a{height: auto;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;
				font-weight: 500;
				font-size: 14px;
				line-height: 17px;
				color: #121415;
				max-width: 160px;
			}
			header #header-small-cart .content .mini-cart-info table td.quantity{text-align:right}
			header #header-small-cart .content .mini-cart-info table td.total{font-weight:500;text-align:right}
			header #header-small-cart .content .mini-cart-info table td.remove{
				padding: 10px 0;
				width: 25px;
			}
			header #header-small-cart .content .mini-cart-info table td.remove img{width:10px; cursor: pointer;}
			header #header-small-cart .content .mini-cart-total table{margin:0 0 15px;width: 100%}
			header #header-small-cart .content .mini-cart-total table tr td:first-child{font-weight:400;text-align: left;}
			header #header-small-cart .content .mini-cart-total table td{font-size:17px;padding:5px;font-weight:500}
			header #header-small-cart .content .mini-cart-total table td.right{font-weight: 600;text-align: right;}
			header #header-small-cart .content .checkout{padding:0;width:100%;margin:0;display:flex;flex-direction:row;border:0; gap: 10px;align-items: center}
			header #header-small-cart .content .checkout a.btn-acaunt{
				height: 38px;
				font-weight: 500;
				font-size: 14px;
				line-height: 17px;
				color: #404345 !important;
				background: #BFEA43;
				border-radius: 20px;
				padding: 10px 16px;
				margin: 0;
				display: flex;
				width: max-content;
			}
			header #header-small-cart .content .checkout a{justify-content:center;align-items:center}
			#ajaxcheckout #gotoorder,.subscribe__form .btn,header #header-small-cart .content .checkout a.btn.btn-acaunt{color: #fff;}
			.main-slider{-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}
			.main-slider .swiper-slide{
				/*padding:40px 0;*/
				/*width:100%;
				display:flex;
				align-items:center;
				margin:0 auto;
				background-position: center;
			    background-size: contain;
			    background-repeat: no-repeat;
			    height: 100% !important;
			    padding: 0 !important;
			    background-position: center top;*/
			}
			.main-slider .info{text-align:center;background:rgba(255,255,255,0.6);padding:25px 20px 60px}
			.main-slider .title-slide{font-size:24px;line-height:1.4em;margin-bottom:20px}
			.main-slider a.btn{display:block;width:240px;height:60px;line-height:60px;text-align:center;font-size:20px;margin:0 auto}
			.main-slider .swiper-arrows{display:none;position:absolute;left:0;right:0;top:calc(50% - 30px);z-index:1}
			.main-slider .swiper-prev-slide,.main-slider .swiper-next-slide{position:absolute;cursor:pointer}
			.main-slider .swiper-prev-slide{left:40px}
			.main-slider .swiper-next-slide{right:40px}
			.main-slider .swiper-button-disabled{opacity:.5}
			.main-slider .swiper-pagination-bullets{bottom:65px}
			.group_login_header{
			display: flex;
			flex-direction: column;
			}
			.group_login_header > span{
			font-size: 16px;
			font-weight: 400;
			margin-bottom: 10px;
			}
			.group_login_header .btn-group-register{
			display: flex;
			width: 100%;
			flex-direction: row;
			margin: 0;
			justify-content: space-between;
			}
			.group_login_header .btn-group-register .fa-facebook-f{
			color: #0385c1;
			position: initial;
			background: transparent;
			height: auto;
			font-size: 18px;
			}
			.group_login_header .btn-group-register button .btn-img{
			max-width: 46px;
			width: 46px;
			padding: 0;
			margin-right: 13px;
			display: flex;
			align-items: center;
			justify-content: center;
			}
			.group_login_header .btn-group-register button{
			width: 48%;
		    display: flex;
		    flex-direction: row;
		    align-content: center;
		    align-items: center;
		    margin-bottom: 10px;
		    padding: 15px 8px;
		    background: #fff !important;
		    background-color: #fff;
		    font-size: 18px;
		    color: #0385c1 !important;
		    text-shadow: none;
		    transition: .15s ease-in-out;
		    outline: none !important;
		    font-weight: 600;
		    letter-spacing: .21px;
		    height: 45px;
		    border: 1px solid #dde1e4;
		    border-radius: 20px;
			}
			.group_login_header .btn-group-register button:last-child{
			margin-bottom: 0;
			}
			.group_login_header .btn-group-register button:hover {
			box-shadow: 1px 1px 5px #ccc;
			}
			
			#del_choose[disabled]{
				opacity: 0.5;
				cursor: default;
			}
			#top{
				background: #ffffff87;
			}
			.lang-switch ul{
			display: flex;
			}
			.lang-switch ul li{
			margin-right: 5px;
			}
			.lang-switch ul li{
			font-size: 14px;
			padding: 0px 10px;
			display: inline-block;
			transition:.15s ease-in-out;
			font-weight: 500;
			}
			.lang-switch ul li:hover{
			color: #333;
			}
			.lang-switch ul li.active {
			background: #51a8811c;
			color: #333;
			
			}
			.overlay-popup{
			position: fixed;
			z-index: 1000;
			top: 0;
			left: 0;
			bottom: 0;
			right: 0;
			background:#000000b3;
			padding: 20px;
			display: none;
			}
			.overlay-popup .popup_wrap{
			max-width: 450px;
			margin: auto;
			box-shadow: 0px 2px 1px rgba(0, 0, 0, 0.25);
			border-radius: 8px;
			background:#fff;
			margin-bottom: 20px;
			padding: 15px;
			position: relative;
			top: 50%;
			transform: translate(0%, -50%);
			-o-transform: translate(0%, -50%);
			-ms-transform: translate(0%, -50%);
			}
			.overlay-popup .popup_wrap .title{
			font-size: 20px;
			text-align: center;
			}
			.overlay-popup .popup_wrap .popup-close {
			position: absolute;
			right: 10px;
			width: 25px;
			height: 25px;
			line-height: 25px;
			top: 10px;
			font-size: 15px;
			color: #2121217d;
			cursor: pointer;
			background-image: url(/catalog/view/theme/dp/img/close-modal.svg);
			background-size: 11px 11px;
			background-repeat: no-repeat;
			border: 1px solid #000;
			border-radius: 50px;
			text-align: center;
			background-position: center;
			opacity: .5;
			z-index: 10;
			background-color: #fff;
			}
			.ui-widget-content.countryWrap{
			border: 0 !important;
			background: #f7f4f4 !important;
			color: #222;
			z-index: 999 !important;
			max-height: 250px;
			overflow-y: auto;
			padding: 10px;
			scrollbar-color: #FFA254 #6f686869 !important;
			scrollbar-width: thin;
			}
			
			.footer__links-item.applinks_wrap{
			margin-left: auto;
			}
			.footer__links-item.applinks_wrap .applinks{
			display: flex;
			flex-direction: column;
			min-width: 150px;
			}
			.footer__links-item.applinks_wrap .applinks a{
			margin-bottom: 10px;
			}
			.footer__links-item.applinks_wrap .applinks a img{
			height: 55px;
			}
			header .head-menu ul {
				display: flex;
				align-items: center;
				gap: 15px;
			}
			header .head-menu ul a{
				display: flex;
				align-items: center;
				font-weight: 500;
				font-size: 14px;
				line-height: 17px;
				color: #696F74;
				transition: .3s ease-in-out;
			}
			.footer__links-item ul li a:hover,
			header .head-menu ul a:hover{
			    text-decoration: underline;
    			color: #404345;
			}
			.footer__links-item ul li a:hover{
				color: #696F74;
			}
			@media screen and (max-width: 1520px) {
				.slider-top-wrap{padding:0}

			}
			@media screen and (max-width: 1400px) {
				#mmenu_home li.level1 > a, #new_mmenu li.level1 > a {
				  padding: 10px 34px 10px 38px;
				}
				.menu-list li.level1 > a svg {
				  max-width: 20px;
				}
				#home-cart-products-in-cart-products{
					max-width: 35% !important;
				}
				#home-cart .container-cart {
				    margin: 14px 0;
				}
				header .top-menu .icons-link li > button, 
				header .top-menu .icons-link li > a {
    				height: 45px;
    			}
    			

    			#home-cart-text-products-in-cart-line-1{
    				font-size: 18px !important;
    			}
    			.topmenu .all-categories,
    			.topmenu .children-category-list .submenu-list__item > a span,
    			header .drop-list__menu.small_icon a,
    			.drop-list__btn,
    			header .phone_drop-list .drop-list__menu .social-contact li a,
    			#mmenu_home li.level1 > a, 
    			#new_mmenu li.level1 > a{
    				font-size: 14px !important;
    			}
    			.topmenu .children-category-list .submenu-list__item > a span{
    				line-height: 22px;
    			}
    			#new_mmenu .children-category-list .submenu-list__item > a {
				  margin-bottom: 10px !important;
				  padding-bottom: 10px !important;
				}
			}
			
			@media screen and (min-width: 768px) {
			.main-slider .info{text-align:left;max-width:355px;padding:25px 40px 90px}
			.main-slider .title-slide{font-size:32px;line-height:1.5em}
			.main-slider a.btn{width:200px;margin-left:0}
			.main-slider .swiper-pagination-bullets{bottom:100px}
			/*.main-slider .swiper-slide{min-height:550px}*/
			}
			@media screen and (min-width: 1400px) {
				/*.main-slider .swiper-slide{min-height:450px}*/
				/*.main-slider .swiper-slide{min-height:550px}*/
			}
		
			@media screen and (min-width: 1000px) {
			.main-slider .info{max-width:385px}
			}
			@media screen and (min-width: 1280px) {
			.main-slider .swiper-arrows{display:block}
			.main-slider .info{margin-left:100px;padding-bottom:35px}
			/*.main-slider .swiper-pagination-bullets{display:none}*/
			.main-slider .swiper-pagination-bullets{text-align: center;padding-left: 0;}
			.main-slider .swiper-arrows{display:none}
			}
			.brand-top{padding:0;margin-top:0;max-width:5000px;background:#FFF8EF}
			.brand-slider{padding:30px 0 40px;-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}
			.brand-slider .swiper-slide{text-align:center;display:-webkit-box;display:-ms-flexbox;display:-webkit-flex;display:flex;-webkit-box-pack:center;-ms-flex-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-ms-flex-align:center;-webkit-align-items:center;align-items:center}
			.brand-slider .swiper-slide a img{transition:opacity .3s ease}
			.brand-slider .swiper-slide a:hover img{opacity:.7}
			.brand-slider .swiper-pagination-bullets{position:relative;text-align:center}
			.brand-slider .swiper-pagination-bullet{margin:0 10px}
			.brand-slider .swiper-prev-slide,.brand-slider .swiper-next-slide{display:none;position:absolute;top:calc(50% - 30px);cursor:pointer;height:60px;z-index:10}
			.brand-slider .swiper-prev-slide svg path,.brand-slider .swiper-prev-slide svg rect,.brand-slider .swiper-next-slide svg path,.brand-slider .swiper-next-slide svg rect{transition:all .3s ease}
			.brand-slider .swiper-prev-slide:hover svg path,.brand-slider .swiper-prev-slide:hover svg rect,.brand-slider .swiper-next-slide:hover svg path,.brand-slider .swiper-next-slide:hover svg rect{stroke:#51A62D}
			.brand-slider .swiper-prev-slide{left:0}
			.brand-slider .swiper-next-slide{right:0}
			@media screen and (min-width: 1280px) {
			.brand-slider .swiper-pagination-bullets{display:none}
			.brand-slider{position:relative;padding:40px 100px}
			.brand-slider .swiper-prev-slide,.brand-slider .swiper-next-slide{display:block}
			}
			#brand-categories > ul{display:block;columns:3}
			#brand-categories > ul > li{margin-bottom:20px}
			#brand-categories ul li ul{padding-left:20px}
			.popular-goods{content-visibility: auto;contain-intrinsic-size: 1650px;}
			/*header top news*/
			.header-banner{
				background: rgb(85,85,85);
				background: linear-gradient(214deg, rgba(85,85,85,1) 0%, rgba(41,41,41,1) 100%);
				overflow:hidden; 
				position: relative;
				cursor: pointer;
			}

			.header-banner .img img{
				display: flex;
			}
			.header-banner .img{
			    height: auto;
			    width: 100%;
			}
			.header-banner .swiper-slide.image_bg {
/*				position: unset;*/
				width: 100% !important;
				padding: 0;
			}
			.header-banner.hide{max-height:0;padding-top:0;padding-bottom:0;margin-top:0;margin-bottom:0;transition-timing-function:cubic-bezier(0,1,.5,1)}
			.header-banner .content{position:relative;margin:auto;padding: 0 !important}
			.header-banner .close{
				position: absolute;
			    right: 40px;
			    width: 21px;
			    height: 19px;
			    line-height: 15px;
			    top: 0;
			    bottom: 0;
			    margin: auto;
			    cursor: pointer;
			    background-image: url(/catalog/view/theme/dp/img/close-modal-white.svg);
			    background-size: 15px 15px;
			    background-repeat: no-repeat;
			    text-align: center;
			    background-position: center;
			    opacity: .5;
			    z-index: 1;
			    background-color: #000;
			    padding: 3px 5px;
			}
			.header-banner .button-next,.header-banner .button-prev{position:absolute;top:0;bottom:0;margin:auto;width:30px;height:30px;cursor:pointer;transition:.3s ease-in-out;z-index:2}
			.header-banner .button-prev{left:0}
			.header-banner .button-next{right:0}
			.header-banner .swiper-slide{display:flex;flex-direction:row;align-items:center;-webkit-box-pack:space-evenly;-ms-flex-pack:space-evenly;justify-content:space-evenly;padding:15px 0}
			.header-banner .header-promotion{max-width:30%}
			.header-banner .header-promotion--text{max-width:40%}
			.header-banner .subtitle{max-width:20%}
			.header-banner .header-promotion span{color:#fff;font-size:13px;font-weight:500;display:flex;flex-direction:row;align-items:center;line-height:17px;text-align:center;text-transform:uppercase}
			.header-banner .header-promotion span svg{margin-right:15px; min-width: 20px}
			.header-banner .header-promotion--text p{color:#fff;text-align:center;font-size: 13px;font-weight: 500;line-height:15px;display:block; margin: auto;}
			.header-banner .subtitle a{color:#fff;text-transform:uppercase;font-size:10px;letter-spacing:1.54px;text-decoration:underline}
			.header-banner .subtitle a:hover{color:#fff;text-decoration: none;}
			.header-banner .wrap{z-index: 2;}
			.popup-form{display: none;}
			/*.header-banner:before{
			content: '';
			background: url(/catalog/view/theme/dp/img/snow1.png);
			display: block;
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-repeat: no-repeat;
			background-size: contain;
			}
			.header-banner:after{
			content: '';
			background: url(/catalog/view/theme/dp/img/snow1.png);
			display: block;
			position: absolute;
			right: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-repeat: no-repeat;
			background-size: contain;
			transform: scale(-1, 1);
			z-index: 0;
			}*/
			@media screen and (max-width:1400px){
			header.fixed{padding-bottom:60px}
/*			.header-banner .content{max-width:80%}*/
			.header-banner .header-promotion span{font-size:13px;line-height:18px}
			.header-banner .header-promotion--text p{font-size:13px;line-height:18px}
			}
			@media screen and (max-width:1250px){
/*			.header-banner .content{max-width:80%}*/
			}
			@media screen and (max-width:990px){

				.header-banner:before,
				.header-banner:after{
				opacity: 0.2;
				filter: drop-shadow(1px 1px 5px #235e3d);
				}
			
				.logo_mobile_menu{
				    margin-bottom: 20px;
				    max-height: 60px;
				    display: flex;
				    align-items: center;
				    justify-content: center;
				    margin-top: 20px;
				}
				.logo_mobile_menu a{
					display: flex;
    				align-items: center;
				}
				.logo_mobile_menu a svg{
				    
				    width: 120px;
				}
				.mobile-menu .close_menu_mobile{
					position: absolute;
				    right: 15px;
				    top: 15px;
				    z-index: 999;
				    width: 30px;
				    margin-right: 0;
				    height: 30px;
				    background-position: center;
				    background-size: auto;
				    background-repeat: no-repeat;
				    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iMTQiIHZpZXdCb3g9IjAgMCAxNCAxNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTMgMUwxIDEzTTEgMUwxMyAxMyIgc3Ryb2tlPSIjODg4Rjk3IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPjwvc3ZnPg==);
				}
				.mobile-menu #openModalAuth{
					display: flex;
				    padding-top: 0;
				    padding-bottom: 0;
				    background: #BFEA43;
				    border-radius: 20px;
				    font-weight: 500;
				    font-size: 13px;
				    line-height: 17px;
				    color: #404345;
				    transition: background 0.3s ease;
				    -webkit-touch-callout: none;
				    -webkit-user-select: none;
				    -khtml-user-select: none;
				    -moz-user-select: none;
				    -ms-user-select: none;
				    user-select: none;
				    cursor: pointer;
				    padding: 12px 15px;
				    gap: 10px;
				    align-items: center;
				    flex-direction: row;
				}.mobile-menu .account-menu__mobile > div i{
					margin-left: auto;
				}
				.mobile-menu .account-menu__mobile.open_menu_parent > div i{
					transform: rotate(90deg);
				}
				.mobile-menu .account-menu__mobile > ul{
					display: none;
				}
				.mobile-menu .account-menu__mobile > ul.open_menu{
					display: flex;
					flex-direction: column;
				}
				.mobile-menu .account-menu__mobile > div{
					display: flex;
				    padding-top: 0;
				    padding-bottom: 0;
				    background: #BFEA43;
				    border-radius: 20px;
				    font-weight: 500;
				    font-size: 14px;
				    line-height: 17px;
				    color: #404345;
				    transition: background 0.3s ease;
				    -webkit-touch-callout: none;
				    -webkit-user-select: none;
				    -khtml-user-select: none;
				    -moz-user-select: none;
				    -ms-user-select: none;
				    user-select: none;
				    cursor: pointer;
				    padding: 12px 15px;
				    gap: 10px;
				    align-items: center;
				    flex-direction: row;
				}
				.mobile-menu .account-menu__mobile{
				    display: flex;
					flex-direction: column;
					padding-top: 0;
					padding-bottom: 0
				}
				.mobile-menu .account-menu__mobile button.profile{
					font-size: 16px;
				    display: flex;
				    align-items: center;
				    justify-content: center;
				    padding: 7px 10px;
				    background: #e9dc45;
				    font-weight: 600;
				    color: #fff;
				}
				.mobile-menu .account-menu__mobile button.profile a{
					color: #fff;
				}
				.mobile-menu .account-menu__mobile .content {
				    flex-direction: column;
					align-items: flex-start;
				}
				.mobile-menu .account-menu__mobile .content > p{
					display: flex;
				    align-items: center;
				    position: relative;
				    width: 100%;
				}
				.mobile-menu .account-menu__mobile .content > p a{
					text-decoration: underline;
				    padding-left: 5px;
				    display: -webkit-box;
				    -webkit-line-clamp: 1;
				    -webkit-box-orient: vertical;
				    overflow: hidden;
				    text-overflow: ellipsis;
				    padding-right: 15px;
				}
				.mobile-menu .account-menu__mobile .content > p::before{
					content: "\f105";
				    position: absolute;
				    right: 0;
				    font-family: "Font Awesome 5 Free";
				    color: #fea15c;
				    z-index: 1;
				    display: flex;
				    font-weight: 600;
				    font-size: 17px;
			        transition: .3s ease-in-out;
				}
				.mobile-menu .account-menu__mobile.open_menu_parent .content > p::before{
					transform: rotate(90deg);
				}
				.mobile-menu .account-menu__mobile .welcome_menu {
					padding: 0
				}
				.mobile-menu .account-menu__mobile .welcome_menu li a{
					display: flex;
					align-items: center;
					padding-left: 0
				}
				.mobile-menu .account-menu__mobile .welcome_menu li a svg{
				    max-width: 21px;
				}
				.mobile-menu .account-menu__mobile .content.user_logOut > p::before{
					display: none
				}
			}
			@media screen and (max-width:900px){
				#popup-cart .object{
					padding: 40px 15px 15px 15px !important;
				}
				.mobile-menu__list .compare_main_menu_wrap{
					padding: 0 15px;
				}
				.mobile-menu__list .compare_main_menu{
				    font-size: 16px;
				    line-height: 19px;
				    color: #696F74;
				    font-weight: 500;
				    border: 1px solid #DDE1E4;
				    border-radius: 36px;
				    padding: 10px 18px;
				    display: flex;
				    align-items: center;
				    gap: 10px;
				}
				.mobile-menu__list .compare_main_menu svg{
					max-width: 20px;
				}
			.mobile-menu__list .mobile-menu__head button.profile > .user-names{
			display: flex;
			align-items: center;
			}
			.mobile-menu__list .mobile-menu__head button.profile  #account_header  .user-names{
			display: inline-block !important;
			font-size: 14px;
			margin-left: 5px;
			overflow: hidden;
			width: 100%;
			max-width: 130px;
			text-overflow: ellipsis;
			}
			.mobile-menu__list .mobile-menu__head button.profile .user-names svg{
			display: none
			}
			.mobile-menu__list a.favorite{
			display: flex;
			align-items: center;
			}
			.mobile-menu__list a.favorite svg{display: none}
			.mobile-menu__list a.favorite .txt{display: flex;align-items: center;}
			.mobile-menu__list a.favorite .txt svg{display: inline-block; margin-right: 10px;}
			.mobile-menu__list a.favorite i{font-style: inherit;margin-left: 3px;}
			.mobile-menu__list a.favorite i::before{
			content: '(';
			}
			.mobile-menu__list a.favorite i::after{
			content: ')';
			}
			.mobile-menu__list a.favorite svg{
			display: none !important
			}
			#home-cart{order: 5}
			.group_login_header .btn-group-register{flex-direction: column;}
			.group_login_header .btn-group-register button{width: 100%;}
/*			.header-banner .content{max-width:80%}*/
			/*.header-banner .swiper-slide{flex-direction:column}*/
			.header-banner .header-promotion{max-width:100%;padding:0 15px}
			.header-banner .header-promotion--text{max-width:100%;padding:0 15px;margin:10px 0}
			.header-banner .subtitle{max-width:100%;padding:0 15px}
			.header-banner .button-next,.header-banner .button-prev{display:none}
			.header-banner:after{content: unset;}
			.header-banner:before{background-size: cover;}
			.mobile-menu .top_menu .parent.level1 i{
				display: none !important
			}
			.mobile-menu .top_menu .parent.level1 svg{
				position: relative;
				left: 0;
				width: 19px;
				height: 19px;
			}
			}
			@media screen and (max-width:560px){
				.mobile-menu__list .popup-city__btn-group{
					flex-direction: column;
					gap: 10px;
				}
				.mobile-menu__list .popup-city__btn-group > a{
					width: 100%;
					margin: 0;
					display: flex !important;
				    align-items: center;
				    justify-content: center;
				}
				

				#search-wrap{
					/*display: flex;
					position: fixed;
					z-index: 1;
					max-width: 99dvw !important;
					left: 0;
					right: 0;
					margin: 0 auto;
					padding-right: 45px;*/
				}
				header .menu-horizontal{
					gap: 10px;
				}
				
				
				header .top-menu .icons-link li > button{
					border: 0;
				}
				header .top-menu .icons-link li > button i{
					position: absolute;
					right: 5px;
					bottom: 6px;
					height: 14px;
					color: #fff;
					box-sizing: content-box;
					width: auto;
					display: flex !important;
					text-align: center;
					justify-content: center;
					min-width: 14px;
					background: #EB3274;
					border: 2px solid #FFFFFF;
					border-radius: 29px;
					font-weight: 600;
					font-size: 10px;
					line-height: 12px;
					top: unset;
					padding: 0;
				}
				header .top-menu .inner{
					height: 54px;
				}
				header .sub-head{
					display: none;
				}
				.subscribe_wrap{
					order: 8;
				}
				#footer_app{
					order: 9;
				}
				.footer__links-item.applinks_wrap .footer__links-title:after{
				display: none
				}
				.footer__links-item.applinks_wrap .footer__links-title{
				text-align: center;
				border-top: 0;
				margin-top: 25px
				}
				.footer__links-item.applinks_wrap  a{
				text-align: center;
				}
				header #account_header .content.active{display: block;padding: 10px !important}
				header #account_header .content > p {
				font-size: 13px;
				margin-bottom: 20px;
				border-bottom: 1px solid #cccccc47;
				display: flex;
				padding: 0;
				text-align: left;
				flex-direction: column;
				gap: 5px;
				}
				
				header #account_header .user_logOut p a{
				padding-left: 0;
				
				}
				.footer__payments .by-img{
				width: 100%;
				}
				.header-banner .close{right:20px}
/*				.header-banner .content{max-width:90%}*/
				.header-banner .header-promotion--text {max-width: 100%;padding: 0 5px;margin: 2px 0;}
				.header-banner .swiper-slide{padding: 5px 0;}
				.header-banner .swiper-slide {justify-content: center;margin: auto;}
				
				
				header.fixed{padding-bottom: 56px !important;}
				
				header.fixed .mobile-menu {
					top: 0 !important;
					height: 100dvh !important;
					border-right: 1px solid #DDE1E4;
    				border-radius: 0 12px 12px 0;
    				z-index: 9999;
				}
				.header-banner .header-promotion span svg{
					width: 10px;
					height: 10px;
					min-width: 10px;
				}
				.header-banner .header-promotion span{
					font-size: 11px
				}
				.footer__medium{
					flex-direction: column;
				}
				footer .footer__links {
				    display: flex !important;
				    flex-direction: column;
				    gap: 10px !important;
				}
				footer .footer_copyright .logo_footer{
					margin-bottom: 0 !important
				}
				footer .footer_copyright .logo_footer svg{
					width: 100px;
				}
				footer .footer_copyright {
				    margin-right: 0;
				    display: flex;
				    align-items: center;
				    width: 100%;
				    gap: 69px;
				    margin-bottom: 20px;
				}
				.footer__medium {
				    padding-bottom: 30px !important;
				}
			}
			@media screen and (max-width: 480px) {
				.header-banner .header-promotion span svg {
					width: 10px;
					height: 10px;
					min-width: 10px;
					margin-bottom: auto;
					/*margin-top: 3px;*/
					margin-left: auto;
				}
				.main-slider .info{
					background: transparent;
				}
			}
		</style>

		<?php if (!empty($points_svg)) { ?>
			<style>
				#ajaxtable_table tr .price-block .reward_wrap .text::before, 
				.price__head .reward_wrap .text::before,
				.product__grid__colection .product__item .product__middle__colection .reward_wrap .text::before, 
				.product__info .reward_wrap .text::before{
				background-image: url("<?php echo $points_svg;?>")!important;
				}
			</style>
		<?php } ?>
		
		<?php if (!empty($npmScriptsMinified)) { ?>
			<script src="<?php echo $npmScriptsMinified; ?>"></script>
		<?php } else { ?>			
			<script src="<?php echo trim($this->config->get('config_static_subdomain')); ?>js/node_modules/jquery/dist/jquery.min.js"></script>
			<script src="<?php echo trim($this->config->get('config_static_subdomain')); ?>js/node_modules/jquery-ui/dist/jquery-ui.min.js"></script>
		<?php } ?>

		
		
		<?php require_once( DIR_SYSTEM . 'library/microdata/opentweet/template.tpl' ); ?>

	</head>
	
	<?php									
		$theme = 'common_header.tpl';
		
		if(file_exists( DIR_SYSTEM . 'library/microdata/schemaorg/' . $theme )) {
			require_once( DIR_SYSTEM . 'library/microdata/schemaorg/' . $theme );
		}
	?>
	
	<body class="<?php echo $body_class; ?> <?php if (!empty($page_type) && $page_type == 'homepage') { ?>home<?php } ?>">
		<? echo $config_gtm_body; ?>
		<? echo $config_fb_pixel_body; ?>
		<?php if ($config_vk_enable_pixel) { ?>
			<? echo $config_vk_pixel_body; ?>
		<?php } ?>
		
		<?php if (!empty($general_minified_css_uri)) { ?>
			<link href="<? echo $general_minified_css_uri; ?>" rel="stylesheet" media="screen" />
		<?php } ?>
		
		<?php if (!empty($added_minified_css_uri)) { ?>
			<link href="<? echo $added_minified_css_uri; ?>" rel="stylesheet" media="screen" />
		<?php } ?>
		
		
		<?php if (!empty($general_minified_js_uri)) { ?>
			<script src="<? echo $general_minified_js_uri; ?>"></script>
		<?php } ?>
		
		<?php if (!empty($added_minified_js_uri)) { ?>
			<script src="<? echo $added_minified_js_uri; ?>"></script>
		<?php } ?>
		
		<?php  foreach ($incompatible_scripts as $incompatible_script) { ?>
			<script src="<?php echo $incompatible_script; ?>"></script>
		<?php } ?>
		
		<?php  foreach ($added_but_excluded_scripts as $added_but_excluded_script) { ?>
      		<script src="<?php echo $added_but_excluded_script; ?>"></script>
		<?php } ?>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
		
		<!--header-->
		<header>
			
			<!-- header baner -->
			<?php if ($notifies) { ?>
				<div class="header-banner">
					
						<!-- <div class="close-button pull-right">
							<button type="button" class="close"></button>
						</div> -->			
						<div class="content">
							<div id="carousel-slide-up" class="swiper-container">
								<div class="swiper-wrapper">
									<?php foreach ($notifies as $notify) { ?>
										<div class="swiper-slide <?php if ($notify['image_pc']){ ?>image_bg<?php } ?>" onclick="javascript:location.href='<?php echo $notify['link'] ?>'">
											<?php if ($notify['image_pc']){ ?>
												<div class="img">
													<picture> 
														<source media="(min-width: 561px)" srcset="<?php echo $notify['image_pc'] ?>" /> 
														<img alt="<?php echo $notify['main_text'] ?>" src="<?php echo $notify['image_mobile'] ?>" /> 
													</picture>
												</div>
											<?php } ?>
											<?php if (!$notify['image_pc']){ ?>	
												<div class="header-promotion">
													<a target="_blank" href="<?php echo $notify['link'] ?>" title="<?php echo $notify['main_text'] ?>" class="header-promotion--link"><span>
														<?php echo $notify['svg'] ?><?php echo $notify['text_near_svg'] ?>
													</span></a>
												</div>
												<div class="header-promotion--text">
													<a target="_blank" href="<?php echo $notify['link'] ?>" title="<?php echo $notify['main_text'] ?>"><p><?php echo $notify['main_text'] ?></p></a>
												</div>
												<div class="subtitle">
													<a target="_blank" href="<?php echo $notify['link'] ?>" title="<?php echo $notify['main_text'] ?>" class="header-promotion--link"><?php echo $notify['link_text'] ?></a>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
								
								<div class="button-prev">
									<svg width="30" height="30" viewBox="0 0 60 60" fill="none" xmlns="https://www.w3.org/2000/svg">
										<path d="M35 42L23 30L35 18" stroke="#fff" stroke-width="4"></path>
									</svg>
								</div>
								<div class="button-next">
									<svg width="30" height="30" viewBox="0 0 60 60" fill="none" xmlns="https://www.w3.org/2000/svg">
										<path d="M25 42L37 30L25 18" stroke="#fff" stroke-width="4"></path>
									</svg>
								</div>
							</div>
						</div>
				</div>
			<?php } ?>
			<!-- !header baner -->
			
			<div class="wrap top-search">
				<!--head-->
				<div class="head">
					<div class="logo">
						<?php if ($logo) { ?>
						<? /*	<a href="<?php echo $home; ?>">
							<img src="<?php echo $logo; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>">
							</a>		
						
						
						<a href="<?php echo $home; ?>">
							<img src="/icons/LOGO_newyear.svg" alt="<?php echo $name; ?>" title="<?php echo $name; ?>">
							</a>	
						*/ ?>
						<?php } ?>
					
						<a href="<?php echo $home; ?>">
								<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 176 60"><defs><style>.cls-1{fill:#020608;}.cls-2{fill:#6a979b;}.cls-3{fill:#e7b650;}.cls-4{fill:#d8b6cb;}.cls-5{fill:#d26244;}.cls-6{fill:#fff;}</style></defs><title>Domopolis</title><path class="cls-1" d="M66.62,16.81H77.81c7.47,0,11.6,3.57,11.6,10s-4.13,9.94-11.6,9.94H66.62V16.81ZM77.81,35.22c6.37,0,9.94-3,9.94-8.44s-3.57-8.44-9.94-8.44H68.29V35.22Z"/><path class="cls-1" d="M91.26,26.72C91.26,20.37,96.69,16,104.7,16s13.45,4.33,13.45,10.68-5.4,10.64-13.45,10.64S91.26,33.06,91.26,26.72Zm25.22,0c0-5.44-4.75-9.15-11.78-9.15s-11.77,3.71-11.77,9.15,4.75,9.14,11.77,9.14,11.78-3.71,11.78-9.14Z"/><path class="cls-1" d="M120.28,16.82h2.58L133.5,35.11h0l10.65-18.29h2.57V36.73h-1.67V18.58H145L134.46,36.73h-1.92L122,18.58H122V36.73h-1.67V16.82Z"/><path class="cls-1" d="M148.89,26.79c0-6.34,5.44-10.67,13.45-10.67s13.45,4.33,13.45,10.67-5.41,10.64-13.45,10.64S148.89,33.13,148.89,26.79Zm25.23,0c0-5.44-4.75-9.15-11.78-9.15s-11.78,3.71-11.78,9.15,4.76,9.14,11.78,9.14S174.12,32.22,174.12,26.79Z"/><path class="cls-1" d="M66.62,39.46h13.9c4.45,0,7,2.38,7,6.06s-2.58,6-7,6H68.29v7.82H66.62V39.46ZM80.35,50c3.52,0,5.5-1.75,5.5-4.5s-2-4.53-5.5-4.53H68.29v9Z"/><path class="cls-1" d="M89.33,49.28c0-6.35,5.44-10.68,13.45-10.68s13.45,4.33,13.45,10.68-5.41,10.64-13.45,10.64-13.45-4.3-13.45-10.64Zm25.23,0c0-5.44-4.76-9.15-11.78-9.15S91,43.84,91,49.28s4.76,9.14,11.78,9.14S114.56,54.71,114.56,49.28Z"/><path class="cls-1" d="M118.06,39.45h1.67v18.4h13.56v1.5H118.06V39.45Z"/><path class="cls-1" d="M135.12,39.45h1.67v19.9h-1.67V39.45Z"/><path class="cls-1" d="M138.62,53.16l1.42-.85c.93,3.65,5.24,6.08,10.65,6.08,4.72,0,7.81-1.78,7.81-4.35,0-2-1.81-3.23-6.06-3.91l-5.69-.94c-4.5-.71-6.43-2.09-6.43-4.81,0-3.54,3.43-5.78,8.44-5.78,4.39,0,8.1,1.87,9.54,4.65l-1.33.93c-1.27-2.41-4.44-4-8.21-4-4,0-6.77,1.67-6.77,4.16,0,1.79,1.5,2.81,5.07,3.34l5.61.91c5.24.82,7.5,2.44,7.5,5.32,0,3.68-3.79,6.06-9.54,6.06-6,0-10.73-2.71-12-6.76Z"/><path class="cls-2" d="M26.82.46A26.31,26.31,0,0,0,.51,26.77V59.15H26.82Z"/><path class="cls-3" d="M26.82.46h0V59.15H53.14V32.86h0V26.77A26.31,26.31,0,0,0,26.82.46Z"/><rect class="cls-4" x="26.82" y="37.29" width="26.29" height="21.86"/><rect class="cls-5" x="0.52" y="47.99" width="26.29" height="11.16"/><rect class="cls-6" x="0.21" y="47.31" width="26.6" height="0.92"/><rect class="cls-6" x="13.2" y="3.42" width="0.92" height="21.54"/><polygon class="cls-2" points="13.66 21.07 8.7 26.77 18.63 26.77 13.66 21.07"/><path class="cls-6" d="M19.64,27.23h-12l6-6.86,6,6.86Zm-9.93-.92h7.9l-3.95-4.54Z"/><rect class="cls-5" x="10.42" y="41.25" width="6.52" height="6.52"/><path class="cls-6" d="M17.4,48.23H10V40.79H17.4Zm-6.52-.92h5.6V41.72h-5.6Z"/><rect class="cls-6" x="26.81" y="36.83" width="26.55" height="0.92"/><rect class="cls-6" x="26.34" y="0.08" width="0.92" height="59.44"/><polygon class="cls-4" points="43.34 37.29 36.72 37.29 37.85 24.39 42.19 24.39 43.34 37.29"/><path class="cls-6" d="M43.85,37.75H36.21l1.22-13.82h5.18Zm-6.63-.92h5.62l-1.07-12H38.28l-1.06,12Z"/><path class="cls-6" d="M16.94,46.85v-.92a1.81,1.81,0,1,0,0-3.62v-.92a2.73,2.73,0,0,1,0,5.46Z"/><path class="cls-6" d="M39.59,27.75l-.69-.61a6.14,6.14,0,0,0-.29-8.89l.66-.65A7,7,0,0,1,39.59,27.75Z"/><path class="cls-6" d="M39.41,18.41l-.6-.09a5.94,5.94,0,0,1-3.07-1.52h0a5.92,5.92,0,0,1-1.52-3.07l-.09-.61.61.09a6,6,0,0,1,3.07,1.52,5.94,5.94,0,0,1,1.52,3.07l.08.61Zm-4.1-4.11a4.65,4.65,0,0,0,1.08,1.84h0a4.68,4.68,0,0,0,1.84,1.09,4.75,4.75,0,0,0-1.08-1.85,4.77,4.77,0,0,0-1.84-1.08Z"/><path class="cls-6" d="M41.08,23.06l-.25-.56a6,6,0,0,1-.37-3.41,6,6,0,0,1,1.76-2.93l.47-.4.26.56a5.9,5.9,0,0,1,.36,3.4,6,6,0,0,1-1.76,2.94l-.47.4Zm1.25-5.67a4.77,4.77,0,0,0-1,1.9,4.66,4.66,0,0,0,.08,2.14,4.77,4.77,0,0,0,1-1.9,4.66,4.66,0,0,0-.08-2.14Z"/></svg>
					
						</a>
						</div>
					<!--middle-->
					<div class="middle">
						<!--sub-head-->

						<div class="sub-head">
							<?php if (!IS_TABLET_SESSION) { ?>
								<!--drop-list-->
							<div class="drop-list information_drop-list ">
								<div class="drop-list__btn">
									<?php echo $text_retranslate_1; ?>
									<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M1 1L5 5L9 1" stroke="#696F74" stroke-width="1.77778" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>

									<ul class="drop-list__menu">
										<li>
											<a href="<?php echo $href_payment; ?>">
												<?php echo $text_retranslate_2; ?>
											</a>
										</li>
										<li>
											<a href="<?php echo $href_delivery; ?>">
												<?php echo $text_retranslate_3; ?>
											</a>
										</li>
										<!-- <li>
											<a href="<?php echo $href_track; ?>">
												<?php echo $text_retranslate_4; ?>
											</a>
										</li> -->
										<li>
											<a href="<?php echo $href_faq; ?>">
												<?php echo $text_retranslate_6; ?>
											</a>	
										</li>
										<li>
											<a href="<?php echo $href_return; ?>">
												<?php echo $text_retranslate_47; ?>
											</a>
										</li>
										<li>
											<a href="<?php echo $href_contact_b2b?>">
												<?php echo $contact_b2b?>
											</a>
										</li>
									</ul>
								</div>
							</div>
							<!--/drop-list-->
<!-- 							<div class="head-menu">
								<ul>
									
								</ul>	
							</div> -->
							<?php } ?>


							<?php if(!IS_MOBILE_SESSION || IS_TABLET_SESSION) { ?>
								<span class="ajax-module-reloadable" data-modpath="common/header/customercity" data-reloadable-group="customer"></span>
							<?php } ?>

							<!--drop-list-->
							<div class="drop-list phone_drop-list">
								<div class="drop-list__btn">
									<?php echo $text_retranslate_49; ?>
									<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M1 1L5 5L9 1" stroke="#696F74" stroke-width="1.77778" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>

									<ul class="drop-list__menu">
										<!--social-contact-->
										<div class="social-contact">
											<ul class="social-contact__list">
												<?php if ($this->config->get('social_link_messenger_bot')) { ?>
												<li>
													<a href="<?php echo $this->config->get('social_link_messenger_bot'); ?>" class="facebook" rel="noindex nofollow" alt="Facebook" title="Facebook">
														<img src="/catalog/view/theme/dp/img/Facebook_Messenger.png" alt="" loading="lazy">
														Messenger
													</a>
												</li>
												<? } ?>
												<?php if ($this->config->get('social_link_viber_bot')) { ?>
												<li>
													<a class="viber" href="<?php echo $this->config->get('social_link_viber_bot'); ?>" rel="noindex nofollow" alt="Viber" title="Viber">
														<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
															<rect width="40" height="40" rx="20" fill="#7C509A"/>
															<path d="M26.2539 9.54591C22.4424 8.81803 18.4843 8.81803 14.6728 9.54591C12.9869 9.90986 10.8613 11.9479 10.4948 13.5493C9.83508 16.6792 9.83508 19.8819 10.4948 23.0118C10.9346 24.6131 13.0602 26.6512 14.6728 27.0152C14.7461 27.0152 14.8194 27.0879 14.8194 27.1607V31.7464C14.8194 31.9648 15.1126 32.1103 15.2592 31.892L17.4581 29.6355C17.4581 29.6355 19.2173 27.8158 19.5105 27.5247C19.5105 27.5247 19.5838 27.4519 19.6571 27.4519C21.856 27.5247 24.1283 27.3063 26.3272 26.9424C28.0131 26.5784 30.1387 24.5403 30.5052 22.939C31.1649 19.8091 31.1649 16.6064 30.5052 13.4765C30.0654 11.9479 27.9398 9.90986 26.2539 9.54591ZM26.3272 23.2302C25.9607 23.958 25.5209 24.5403 24.788 24.9043C24.5681 24.9771 24.3482 25.0499 24.1283 25.1227C23.8351 25.0499 23.6152 24.9771 23.3953 24.9043C21.0497 23.958 18.8508 22.6478 17.0916 20.7553C16.1387 19.6635 15.3325 18.4261 14.6728 17.1159C14.3796 16.4608 14.0864 15.8785 13.8665 15.2234C13.6466 14.6411 14.0131 14.0588 14.3796 13.6221C14.7461 13.1853 15.1859 12.8942 15.699 12.6758C16.0654 12.4575 16.4319 12.603 16.7251 12.8942C17.3115 13.6221 17.8979 14.35 18.3377 15.1506C18.6309 15.6601 18.5576 16.2425 18.0445 16.6064C17.8979 16.6792 17.8246 16.752 17.678 16.8976C17.6047 16.9703 17.4581 17.0431 17.3848 17.1887C17.2382 17.4071 17.2382 17.6254 17.3115 17.8438C17.8979 19.5179 18.9974 20.8281 20.6832 21.556C20.9764 21.7016 21.1963 21.7744 21.5628 21.7744C22.0759 21.7016 22.2958 21.1193 22.6623 20.8281C23.0288 20.537 23.4686 20.537 23.9084 20.7553C24.2749 20.9737 24.6414 21.2649 25.0811 21.556C25.4476 21.8472 25.8141 22.0655 26.1806 22.3567C26.4005 22.5023 26.4738 22.8662 26.3272 23.2302ZM23.2487 17.771C23.1021 17.771 23.1754 17.771 23.2487 17.771C22.9555 17.771 22.8822 17.6254 22.8089 17.4071C22.8089 17.2615 22.8089 17.0431 22.7356 16.8976C22.6623 16.6064 22.5157 16.3152 22.2225 16.0969C22.0759 16.0241 21.9293 15.9513 21.7827 15.8785C21.5628 15.8057 21.4162 15.8057 21.1963 15.8057C20.9764 15.7329 20.9031 15.5874 20.9031 15.369C20.9031 15.2234 21.123 15.0778 21.2696 15.0778C22.4424 15.1506 23.322 15.8057 23.4686 17.1887C23.4686 17.2615 23.4686 17.4071 23.4686 17.4799C23.4686 17.6254 23.3953 17.771 23.2487 17.771ZM22.5157 14.5683C22.1492 14.4227 21.7827 14.2772 21.3429 14.2044C21.1963 14.2044 20.9764 14.1316 20.8298 14.1316C20.6099 14.1316 20.4634 13.986 20.5366 13.7676C20.5366 13.5493 20.6832 13.4037 20.9031 13.4765C21.6361 13.5493 22.2958 13.6949 22.9555 13.986C24.2749 14.6411 25.0079 15.7329 25.2277 17.1887C25.2277 17.2615 25.2277 17.3343 25.2277 17.4071C25.2277 17.5526 25.2277 17.6982 25.2277 17.9166C25.2277 17.9894 25.2277 18.0622 25.2277 18.135C25.1545 18.4261 24.6414 18.4989 24.5681 18.135C24.5681 18.0622 24.4948 17.9166 24.4948 17.8438C24.4948 17.1887 24.3482 16.5336 24.055 15.9513C23.6152 15.2962 23.1021 14.8595 22.5157 14.5683ZM26.4738 18.9356C26.2539 18.9356 26.1073 18.7173 26.1073 18.4989C26.1073 18.0622 26.034 17.6254 25.9607 17.1887C25.6675 14.8595 23.7618 12.967 21.4895 12.603C21.123 12.5302 20.7565 12.5302 20.4634 12.4575C20.2435 12.4575 19.9503 12.4575 19.877 12.1663C19.8037 11.9479 20.0236 11.7296 20.2435 11.7296C20.3168 11.7296 20.3901 11.7296 20.3901 11.7296C23.3953 11.8024 20.5367 11.7296 20.3901 11.7296C23.4686 11.8024 26.034 13.8404 26.5471 16.8976C26.6204 17.4071 26.6937 17.9166 26.6937 18.4989C26.8403 18.7173 26.6937 18.9356 26.4738 18.9356Z" fill="white"/>
														</svg>
														Viber
													</a>
												</li>
												<? } ?>
												<?php if ($this->config->get('social_link_telegram_bot')) { ?>
												<li>
													<a class="tg" href="<?php echo $this->config->get('social_link_telegram_bot'); ?>" rel="noindex nofollow" alt="Telegram" title="Telegram">
														<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
															<rect width="40" height="40" rx="20" fill="#27A6E5"/>
															<path d="M28 12.6022L24.9946 28.2923C24.9946 28.2923 24.5741 29.3801 23.4189 28.8584L16.4846 23.3526L16.4524 23.3364C17.3891 22.4654 24.6524 15.7027 24.9698 15.3961C25.4613 14.9214 25.1562 14.6387 24.5856 14.9974L13.8568 22.053L9.71764 20.6108C9.71764 20.6108 9.06626 20.3708 9.00359 19.8491C8.9401 19.3265 9.73908 19.0439 9.73908 19.0439L26.6131 12.1889C26.6131 12.1889 28 11.5579 28 12.6022Z" fill="#FEFEFE"/>
														</svg>
														Telegram
													</a>
												</li>
												<?php } ?>
											</ul>
										</div>
										<!--/social-contact-->
										<div class="contact_wrap">
											<ul>
												<li>
													<a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone) ? $phone : ''; ?>">
														<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
															<rect width="40" height="40" rx="20" fill="#97B63C"/>
															<path d="M27.4582 20.9603C27.2382 20.9603 27.0082 20.8903 26.7882 20.8403C26.3427 20.7421 25.9048 20.6118 25.4782 20.4503C25.0143 20.2815 24.5043 20.2903 24.0465 20.4749C23.5886 20.6595 23.2153 21.007 22.9982 21.4503L22.7782 21.9003C21.8042 21.3585 20.9092 20.6856 20.1182 19.9003C19.3329 19.1093 18.66 18.2143 18.1182 17.2403L18.5382 16.9603C18.9815 16.7432 19.3289 16.3698 19.5136 15.912C19.6982 15.4542 19.7069 14.9442 19.5382 14.4803C19.3794 14.0527 19.2491 13.6151 19.1482 13.1703C19.0982 12.9503 19.0582 12.7203 19.0282 12.4903C18.9067 11.7859 18.5378 11.1481 17.9878 10.6916C17.4378 10.2351 16.7429 9.98993 16.0282 10.0003H13.0282C12.5972 9.99627 12.1704 10.0851 11.7769 10.2608C11.3833 10.4366 11.0323 10.695 10.7476 11.0186C10.4629 11.3422 10.2513 11.7233 10.1272 12.136C10.003 12.5488 9.96925 12.9834 10.0282 13.4103C10.5609 17.5997 12.4742 21.4922 15.4658 24.473C18.4574 27.4537 22.3569 29.3528 26.5482 29.8703H26.9282C27.6656 29.8714 28.3776 29.6009 28.9282 29.1103C29.2445 28.8274 29.4973 28.4805 29.6696 28.0926C29.842 27.7048 29.9301 27.2848 29.9282 26.8603V23.8603C29.9159 23.1657 29.663 22.4969 29.2125 21.968C28.762 21.4391 28.142 21.0829 27.4582 20.9603ZM27.9582 26.9603C27.958 27.1023 27.9276 27.2426 27.869 27.3719C27.8103 27.5013 27.7248 27.6166 27.6182 27.7103C27.5068 27.8073 27.3761 27.8797 27.2348 27.9228C27.0935 27.9659 26.9447 27.9787 26.7982 27.9603C23.0531 27.4801 19.5744 25.7668 16.9109 23.0906C14.2474 20.4144 12.5506 16.9277 12.0882 13.1803C12.0723 13.0338 12.0862 12.8856 12.1292 12.7447C12.1721 12.6038 12.2432 12.473 12.3382 12.3603C12.4319 12.2536 12.5472 12.1682 12.6765 12.1095C12.8059 12.0509 12.9462 12.0205 13.0882 12.0203H16.0882C16.3207 12.0151 16.5478 12.0912 16.7303 12.2354C16.9128 12.3796 17.0394 12.5829 17.0882 12.8103C17.1282 13.0837 17.1782 13.3537 17.2382 13.6203C17.3537 14.1475 17.5074 14.6655 17.6982 15.1703L16.2982 15.8203C16.1785 15.8752 16.0708 15.9533 15.9813 16.0499C15.8919 16.1466 15.8224 16.2599 15.7768 16.3835C15.7313 16.5071 15.7106 16.6385 15.716 16.77C15.7214 16.9016 15.7527 17.0309 15.8082 17.1503C17.2474 20.2331 19.7254 22.7111 22.8082 24.1503C23.0516 24.2503 23.3247 24.2503 23.5682 24.1503C23.6929 24.1057 23.8075 24.0368 23.9053 23.9475C24.0032 23.8582 24.0823 23.7504 24.1382 23.6303L24.7582 22.2303C25.2751 22.4152 25.8028 22.5688 26.3382 22.6903C26.6048 22.7503 26.8748 22.8003 27.1482 22.8403C27.3756 22.8891 27.5789 23.0157 27.7231 23.1982C27.8673 23.3807 27.9433 23.6078 27.9382 23.8403L27.9582 26.9603Z" fill="white"/>
														</svg>
														<span>
															<?php echo isset($phone) ? $phone : ''; ?>
															<span class="worktime_text"><? echo $worktime; ?></span>
														</span>
													</a>
												</li>
												<li>
													<a href="mailto:info@domopolis.ua">
														<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
															<rect width="40" height="40" rx="20" fill="#97B63C"/>
															<path d="M27 12H13C12.2044 12 11.4413 12.3161 10.8787 12.8787C10.3161 13.4413 10 14.2044 10 15V25C10 25.7956 10.3161 26.5587 10.8787 27.1213C11.4413 27.6839 12.2044 28 13 28H27C27.7956 28 28.5587 27.6839 29.1213 27.1213C29.6839 26.5587 30 25.7956 30 25V15C30 14.2044 29.6839 13.4413 29.1213 12.8787C28.5587 12.3161 27.7956 12 27 12ZM26.59 14L20.71 19.88C20.617 19.9737 20.5064 20.0481 20.3846 20.0989C20.2627 20.1497 20.132 20.1758 20 20.1758C19.868 20.1758 19.7373 20.1497 19.6154 20.0989C19.4936 20.0481 19.383 19.9737 19.29 19.88L13.41 14H26.59ZM28 25C28 25.2652 27.8946 25.5196 27.7071 25.7071C27.5196 25.8946 27.2652 26 27 26H13C12.7348 26 12.4804 25.8946 12.2929 25.7071C12.1054 25.5196 12 25.2652 12 25V15.41L17.88 21.29C18.4425 21.8518 19.205 22.1674 20 22.1674C20.795 22.1674 21.5575 21.8518 22.12 21.29L28 15.41V25Z" fill="white"/>
														</svg>
														info@domopolis.ua
													</a>
												</li>
											</ul>
										</div>
										
										<div class="worktime">
											<!-- <? echo $worktime; ?> -->
										</div>	
										
									</ul>
									
								</div>
							</div>
							<!--/drop-list-->
						
							<?php if ($language_switcher) { ?>
								<div class="lang-switch">
									<ul>
										<?php foreach ($language_switcher as $switch) { ?>
											<li class="<?php echo $switch['code']; ?><?php if ($switch['active']) { ?> active<?php } ?>">
												<?php if ($switch['active']) { ?>
													<?php echo $switch['text_code']; ?>
													<?php } else { ?>
													<a href="<?php echo $switch['href']; ?>" class="lang-switch-button" data-language='<?php echo $switch['code']; ?>'><?php echo $switch['text_code']; ?></a>
												<?php } ?>
											</li>
										<?php } ?>
									</ul>
								</div>
							<?php } ?>
							
							
						</div>
						<!--/sub-head-->
					</div>
					<!--/middle-->
				</div>
				<!--/head-->
			</div>
			<!--top-menu-->
			<div class="top-menu">
				<div class="wrap">
					<div class="inner">
						<!--menu-horizontal-->
						<div class="menu-horizontal">
							<ul>
								<li class="catalog-li">
									<a class="catalog__btn razdel mob-menu__btn">
										<?php if(!IS_MOBILE_SESSION) {?>
											<svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M1 7H19M1 1H19M1 13H19" stroke="#696F74" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										<?php } else { ?>
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M8 6H20M6 12H18M4 18H16" stroke="#696F74" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										<?php } ?>
										<span class="top_menu-horizontal"><?php echo $text_retranslate_16; ?> <?php echo $text_retranslate_17; ?></span>
									</a>
	                                <ul id="new_mmenu" class="menu-list">
	                                	<?php if(!IS_MOBILE_SESSION) {?>
	                                		<?php echo $mmenu; ?>
										<?php } elseif(IS_TABLET_SESSION) { ?>
	                                    	<?php echo $mmenu; ?>
	                                    <?php } ?>
	                                    
									</ul>
								</li>
							</ul>
							


							<?php include($this->checkTemplate(dirname( __FILE__), '/../search/' . mb_strtolower($this->config->get('config_search_library')) . '.tpl')); ?>

							<!--icons-link-->
							<ul class="icons-link">								
									<li class="ajax-module-reloadable" data-modpath="common/header/wishlistblock" data-reloadable-group="customer">
										<a href="<?php echo $wishlist; ?>" class="favorite">
											<svg width="22" height="21" viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M10.9932 4.13581C8.9938 1.7984 5.65975 1.16964 3.15469 3.31001C0.649644 5.45038 0.296968 9.02898 2.2642 11.5604C3.89982 13.6651 8.84977 18.1041 10.4721 19.5408C10.6536 19.7016 10.7444 19.7819 10.8502 19.8135C10.9426 19.8411 11.0437 19.8411 11.1361 19.8135C11.2419 19.7819 11.3327 19.7016 11.5142 19.5408C13.1365 18.1041 18.0865 13.6651 19.7221 11.5604C21.6893 9.02898 21.3797 5.42787 18.8316 3.31001C16.2835 1.19216 12.9925 1.7984 10.9932 4.13581Z" stroke="#404345" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										</a>
									</li>
								
									<li id="header-small-cart" class="ajax-module-reloadable" data-modpath="module/cart" data-reloadable-group="customer">
										<button id="cart-btn" class="cart" onClick="openCart();">
											<svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M14.9986 6C14.9986 7.06087 14.5772 8.07828 13.827 8.82843C13.0769 9.57857 12.0595 10 10.9986 10C9.93775 10 8.92033 9.57857 8.17018 8.82843C7.42004 8.07828 6.99861 7.06087 6.99861 6M2.63183 5.40138L1.93183 13.8014C1.78145 15.6059 1.70626 16.5082 2.0113 17.2042C2.2793 17.8157 2.74364 18.3204 3.3308 18.6382C3.99908 19 4.90447 19 6.71525 19H15.282C17.0928 19 17.9981 19 18.6664 18.6382C19.2536 18.3204 19.7179 17.8157 19.9859 17.2042C20.291 16.5082 20.2158 15.6059 20.0654 13.8014L19.3654 5.40138C19.236 3.84875 19.1713 3.07243 18.8275 2.48486C18.5247 1.96744 18.0739 1.5526 17.5331 1.29385C16.919 1 16.14 1 14.582 1L7.41525 1C5.85724 1 5.07823 1 4.46413 1.29384C3.92336 1.5526 3.47251 1.96744 3.16974 2.48486C2.82591 3.07243 2.76122 3.84875 2.63183 5.40138Z" stroke="#404345" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										</button>
									</li>
									<!-- $("#info_delivery .content").load("index.php?route=information/information/info2&information_id="+t+"&block="+b); -->
									
									<?php if(!IS_MOBILE_SESSION) { ?>
										<li id="account_header" class="1 ajax-module-reloadable" data-modpath="common/header/customermenu" data-reloadable-group="customer">
											<button class="profile">
												<svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M1 18C3.33579 15.5226 6.50702 14 10 14C13.493 14 16.6642 15.5226 19 18M14.5 5.5C14.5 7.98528 12.4853 10 10 10C7.51472 10 5.5 7.98528 5.5 5.5C5.5 3.01472 7.51472 1 10 1C12.4853 1 14.5 3.01472 14.5 5.5Z" stroke="#404345" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
												</svg>
											</button>
										</li>
									<?php } ?>
							</ul>
							<!--/icons-link-->
							
						</div>
						<!--/menu-horizontal-->
					</div>
				</div>
				<div class="tab-product-wrap js-dragscroll-wrap3 mb-scroll" hidden="">					
				</div>
			</div>
			<!--/top-menu-->
			<!--mobile-menu-->
			<div class="mobile-menu">
				<div class="close_menu_mobile"></div>
				<div class="mobile-menu__list">
					
					<div class="main-menu mobile-mega-menu">
						<div class="logo_mobile_menu">
							<a href="<?php echo $home; ?>">
									<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 176 60"><defs><style>.cls-1{fill:#020608;}.cls-2{fill:#6a979b;}.cls-3{fill:#e7b650;}.cls-4{fill:#d8b6cb;}.cls-5{fill:#d26244;}.cls-6{fill:#fff;}</style></defs><title>Domopolis</title><path class="cls-1" d="M66.62,16.81H77.81c7.47,0,11.6,3.57,11.6,10s-4.13,9.94-11.6,9.94H66.62V16.81ZM77.81,35.22c6.37,0,9.94-3,9.94-8.44s-3.57-8.44-9.94-8.44H68.29V35.22Z"/><path class="cls-1" d="M91.26,26.72C91.26,20.37,96.69,16,104.7,16s13.45,4.33,13.45,10.68-5.4,10.64-13.45,10.64S91.26,33.06,91.26,26.72Zm25.22,0c0-5.44-4.75-9.15-11.78-9.15s-11.77,3.71-11.77,9.15,4.75,9.14,11.77,9.14,11.78-3.71,11.78-9.14Z"/><path class="cls-1" d="M120.28,16.82h2.58L133.5,35.11h0l10.65-18.29h2.57V36.73h-1.67V18.58H145L134.46,36.73h-1.92L122,18.58H122V36.73h-1.67V16.82Z"/><path class="cls-1" d="M148.89,26.79c0-6.34,5.44-10.67,13.45-10.67s13.45,4.33,13.45,10.67-5.41,10.64-13.45,10.64S148.89,33.13,148.89,26.79Zm25.23,0c0-5.44-4.75-9.15-11.78-9.15s-11.78,3.71-11.78,9.15,4.76,9.14,11.78,9.14S174.12,32.22,174.12,26.79Z"/><path class="cls-1" d="M66.62,39.46h13.9c4.45,0,7,2.38,7,6.06s-2.58,6-7,6H68.29v7.82H66.62V39.46ZM80.35,50c3.52,0,5.5-1.75,5.5-4.5s-2-4.53-5.5-4.53H68.29v9Z"/><path class="cls-1" d="M89.33,49.28c0-6.35,5.44-10.68,13.45-10.68s13.45,4.33,13.45,10.68-5.41,10.64-13.45,10.64-13.45-4.3-13.45-10.64Zm25.23,0c0-5.44-4.76-9.15-11.78-9.15S91,43.84,91,49.28s4.76,9.14,11.78,9.14S114.56,54.71,114.56,49.28Z"/><path class="cls-1" d="M118.06,39.45h1.67v18.4h13.56v1.5H118.06V39.45Z"/><path class="cls-1" d="M135.12,39.45h1.67v19.9h-1.67V39.45Z"/><path class="cls-1" d="M138.62,53.16l1.42-.85c.93,3.65,5.24,6.08,10.65,6.08,4.72,0,7.81-1.78,7.81-4.35,0-2-1.81-3.23-6.06-3.91l-5.69-.94c-4.5-.71-6.43-2.09-6.43-4.81,0-3.54,3.43-5.78,8.44-5.78,4.39,0,8.1,1.87,9.54,4.65l-1.33.93c-1.27-2.41-4.44-4-8.21-4-4,0-6.77,1.67-6.77,4.16,0,1.79,1.5,2.81,5.07,3.34l5.61.91c5.24.82,7.5,2.44,7.5,5.32,0,3.68-3.79,6.06-9.54,6.06-6,0-10.73-2.71-12-6.76Z"/><path class="cls-2" d="M26.82.46A26.31,26.31,0,0,0,.51,26.77V59.15H26.82Z"/><path class="cls-3" d="M26.82.46h0V59.15H53.14V32.86h0V26.77A26.31,26.31,0,0,0,26.82.46Z"/><rect class="cls-4" x="26.82" y="37.29" width="26.29" height="21.86"/><rect class="cls-5" x="0.52" y="47.99" width="26.29" height="11.16"/><rect class="cls-6" x="0.21" y="47.31" width="26.6" height="0.92"/><rect class="cls-6" x="13.2" y="3.42" width="0.92" height="21.54"/><polygon class="cls-2" points="13.66 21.07 8.7 26.77 18.63 26.77 13.66 21.07"/><path class="cls-6" d="M19.64,27.23h-12l6-6.86,6,6.86Zm-9.93-.92h7.9l-3.95-4.54Z"/><rect class="cls-5" x="10.42" y="41.25" width="6.52" height="6.52"/><path class="cls-6" d="M17.4,48.23H10V40.79H17.4Zm-6.52-.92h5.6V41.72h-5.6Z"/><rect class="cls-6" x="26.81" y="36.83" width="26.55" height="0.92"/><rect class="cls-6" x="26.34" y="0.08" width="0.92" height="59.44"/><polygon class="cls-4" points="43.34 37.29 36.72 37.29 37.85 24.39 42.19 24.39 43.34 37.29"/><path class="cls-6" d="M43.85,37.75H36.21l1.22-13.82h5.18Zm-6.63-.92h5.62l-1.07-12H38.28l-1.06,12Z"/><path class="cls-6" d="M16.94,46.85v-.92a1.81,1.81,0,1,0,0-3.62v-.92a2.73,2.73,0,0,1,0,5.46Z"/><path class="cls-6" d="M39.59,27.75l-.69-.61a6.14,6.14,0,0,0-.29-8.89l.66-.65A7,7,0,0,1,39.59,27.75Z"/><path class="cls-6" d="M39.41,18.41l-.6-.09a5.94,5.94,0,0,1-3.07-1.52h0a5.92,5.92,0,0,1-1.52-3.07l-.09-.61.61.09a6,6,0,0,1,3.07,1.52,5.94,5.94,0,0,1,1.52,3.07l.08.61Zm-4.1-4.11a4.65,4.65,0,0,0,1.08,1.84h0a4.68,4.68,0,0,0,1.84,1.09,4.75,4.75,0,0,0-1.08-1.85,4.77,4.77,0,0,0-1.84-1.08Z"/><path class="cls-6" d="M41.08,23.06l-.25-.56a6,6,0,0,1-.37-3.41,6,6,0,0,1,1.76-2.93l.47-.4.26.56a5.9,5.9,0,0,1,.36,3.4,6,6,0,0,1-1.76,2.94l-.47.4Zm1.25-5.67a4.77,4.77,0,0,0-1,1.9,4.66,4.66,0,0,0,.08,2.14,4.77,4.77,0,0,0,1-1.9,4.66,4.66,0,0,0-.08-2.14Z"/></svg>
						
							</a>
						</div>
						<nav>
							
							<ul class="top_menu">

								<li class="catalog__link">
									<div class="catalog__link-mob-btn">
										<svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M1 7H19M1 1H19M1 13H19" stroke="#888F97" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
										<span><?php echo $text_retranslate_16; ?></span>
										<svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M1.5 11L6.5 6L1.5 1" stroke="#696F74" stroke-width="1.77778" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</div>
								</li>
								<li class="customercity-btn">
									<svg width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M9 12C10.6569 12 12 10.6569 12 9C12 7.34315 10.6569 6 9 6C7.34315 6 6 7.34315 6 9C6 10.6569 7.34315 12 9 12Z" stroke="#888F97" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M9 21C13 17 17 13.4183 17 9C17 4.58172 13.4183 1 9 1C4.58172 1 1 4.58172 1 9C1 13.4183 5 17 9 21Z" stroke="#888F97" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
									<?php if(IS_MOBILE_SESSION) { ?>
										<span class="ajax-module-reloadable" data-modpath="common/header/customercity" data-reloadable-group="customer"></span>
									<?php } ?>
									<svg class="change-city__btn" width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M1.5 11L6.5 6L1.5 1" stroke="#696F74" stroke-width="1.77778" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</li>
								<li class="contact__link">
									<div class="contact__link-mob-btn">
										<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M7.38028 7.85323C8.07627 9.30285 9.02506 10.6615 10.2266 11.8631C11.4282 13.0646 12.7869 14.0134 14.2365 14.7094C14.3612 14.7693 14.4235 14.7992 14.5024 14.8222C14.7828 14.904 15.127 14.8453 15.3644 14.6752C15.4313 14.6274 15.4884 14.5702 15.6027 14.4559C15.9523 14.1063 16.1271 13.9315 16.3029 13.8172C16.9658 13.3862 17.8204 13.3862 18.4833 13.8172C18.6591 13.9315 18.8339 14.1063 19.1835 14.4559L19.3783 14.6508C19.9098 15.1822 20.1755 15.448 20.3198 15.7333C20.6069 16.3009 20.6069 16.9712 20.3198 17.5387C20.1755 17.8241 19.9098 18.0898 19.3783 18.6213L19.2207 18.7789C18.6911 19.3085 18.4263 19.5733 18.0662 19.7756C17.6667 20 17.0462 20.1614 16.588 20.16C16.1751 20.1588 15.8928 20.0787 15.3284 19.9185C12.295 19.0575 9.43264 17.433 7.04466 15.045C4.65668 12.6571 3.03221 9.79471 2.17124 6.76131C2.01103 6.19687 1.93092 5.91464 1.9297 5.5017C1.92833 5.04347 2.08969 4.42298 2.31411 4.02348C2.51636 3.66345 2.78117 3.39863 3.3108 2.86901L3.46843 2.71138C3.99987 2.17993 4.2656 1.91421 4.55098 1.76987C5.11854 1.4828 5.7888 1.4828 6.35636 1.76987C6.64174 1.91421 6.90747 2.17993 7.43891 2.71138L7.63378 2.90625C7.98338 3.25585 8.15819 3.43065 8.27247 3.60643C8.70347 4.26932 8.70347 5.1239 8.27247 5.78679C8.15819 5.96257 7.98338 6.13738 7.63378 6.48698C7.51947 6.60129 7.46231 6.65845 7.41447 6.72526C7.24446 6.96269 7.18576 7.30695 7.26748 7.5873C7.29048 7.6662 7.32041 7.72854 7.38028 7.85323Z" stroke="#888F97" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
										<span>Контакти</span>
										<svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M1.5 11L6.5 6L1.5 1" stroke="#696F74" stroke-width="1.77778" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</div>
								</li>
							
							</ul>

							<ul class="info_block">
								<li><a href="<?php echo $href_payment; ?>"><?php echo $text_retranslate_2; ?></a></li>
								<li><a href="<?php echo $href_delivery; ?>"><?php echo $text_retranslate_3; ?></a></li>
								<li><a href="<?php echo $href_track; ?>"><?php echo $text_retranslate_4; ?></a></li>
								<li><a href="<?php echo $href_about; ?>"><?php echo $text_retranslate_5; ?></a></li>
								<li><a href="<?php echo $href_faq; ?>"><?php echo $text_retranslate_6; ?></a></li>
							</ul>
							
							<!--social-contact-->

							<?php if (!$this->customer->isLogged()) { ?>							
							<div class="compare_main_menu_wrap">
								<a href="<?php echo $compare; ?>" class="compare_main_menu">
			                       <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M14.4271 0.255888L14.1712 0.511672V5.76494V11.0182L13.4156 11.2345C12.9999 11.3534 12.2143 11.5748 11.6697 11.7264C11.1251 11.8779 10.2808 12.114 9.79353 12.2511C9.30624 12.388 7.19556 12.9726 5.10311 13.5503C0.446258 14.8358 0.3463 14.868 0.152221 15.1451C-0.23354 15.6958 0.174839 16.469 0.850989 16.4682C1.03986 16.4679 1.94271 16.2586 2.85724 16.003C3.77187 15.7473 4.53338 15.5514 4.54964 15.5677C4.5659 15.5839 3.63741 17.1651 2.48648 19.0817C-0.211547 23.5738 -0.0447763 23.2335 0.0344396 24.0864C0.174735 25.5958 0.833686 26.9825 1.93281 28.0816C3.96178 30.1107 6.85566 30.5675 9.48052 29.2732C11.5443 28.2556 13.0174 25.9651 13.0231 23.7648C13.0246 23.2004 12.9851 23.1256 10.4676 18.9301C9.06119 16.5863 7.93404 14.6451 7.96291 14.6164C7.99178 14.5875 10.2799 13.9356 13.0476 13.1677C20.6198 11.0667 21.4432 10.8401 21.504 10.8401C21.5349 10.8401 20.5308 12.5575 19.2728 14.6565C17.0111 18.4303 16.9855 18.4796 16.9871 19.0603C16.9943 21.8103 19.1556 24.4326 21.9886 25.1285C22.724 25.3093 24.2743 25.3095 25.0113 25.1291C27.4039 24.5435 29.3652 22.5195 29.8684 20.1167C30.1613 18.7182 30.2235 18.8684 27.4122 14.192C26.0218 11.8795 24.9246 9.95004 24.9739 9.90449C25.0231 9.85904 26.0947 9.53666 27.3552 9.18811C28.8913 8.76336 29.6918 8.49309 29.783 8.36833C30.1437 7.87541 30.0147 7.29433 29.4779 6.99299C29.2348 6.85655 29.1287 6.86375 28.4382 7.06429C27.4989 7.33696 16.5263 10.3803 16.1256 10.4792L15.8389 10.55V5.53083V0.511672L15.5831 0.255888C15.4218 0.0945381 15.2081 0 15.0051 0C14.8021 0 14.5884 0.0945381 14.4271 0.255888ZM25.5374 14.4909C26.64 16.3268 27.5194 17.8518 27.4916 17.8797C27.4637 17.9075 25.6323 17.918 23.4219 17.903L19.4028 17.8757L21.2194 14.853C22.2184 13.1905 23.127 11.6778 23.2383 11.4915C23.3497 11.3053 23.4614 11.1528 23.4866 11.1528C23.5118 11.1528 24.4346 12.6549 25.5374 14.4909ZM8.6451 19.2962L10.6074 22.5661L6.5883 22.5934C4.37787 22.6084 2.54871 22.6002 2.52359 22.5751C2.49847 22.55 3.38121 21.0233 4.48512 19.1824C5.69848 17.1591 6.52993 15.8733 6.58757 15.9309C6.64 15.9834 7.56589 17.4978 8.6451 19.2962ZM28.0935 19.9864C27.9509 20.5938 27.3881 21.5685 26.885 22.0797C24.9763 24.0186 22.0272 24.0322 20.1114 22.111C19.5236 21.5216 19.027 20.6702 18.9024 20.0385L18.8356 19.6997H23.4982H28.1609L28.0935 19.9864ZM11.1078 24.7289C10.8163 26.2071 9.21118 27.7631 7.59299 28.1361C5.98814 28.5062 3.94447 27.8419 2.93009 26.6204C2.56862 26.1852 2.02693 25.1464 1.91665 24.6768L1.84932 24.3902H6.51201H11.1746L11.1078 24.7289Z" fill="#888F97"/>
										</svg>
									<span><?php echo $text_compare; ?></span>
								</a>
							</div>
							<?php } ?>
							<div id="top-customer-block"></div>
						
								<?php if(IS_MOBILE_SESSION) {?>
								<ul style="padding: 0 15px; margin-top: 20px;">
									<li  class="account-menu__mobile ajax-module-reloadable" data-modpath="common/header/customermenu" data-x="m">
									</li>
								</ul>
								<?php } ?>
							
								<script>
									function compileCustomerMenu(){
										if ($('#top-customer-block-content-for-compile').length > 0){
											console.log('compileCustomerMenu fired');
											let html = $('#top-customer-block-content-for-compile').html();
											$('#top-customer-block').html(html);
											$('#top-customer-block-content-for-compile').remove();
										}
									}
								</script>
						</nav>
					</div>
					
				</div>
			</div>
			<!--/mobile-menu-->
		</header>
		
		<!--mobile-menu category-->
		<?php if(IS_MOBILE_SESSION) {?>

			<div id="mobile-category" class="catalog_list__block mobile_left_catalog">
				<span class="catalog__link-mob-btn">
					<svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M6.5 1L1.5 6L6.5 11" stroke="#696F74" stroke-width="1.77778" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<?php echo $text_retranslate_16; ?>
				</span>
				<div class="catalog__link_wrap">
					<?php echo $mmenu; ?>
				</div>
			</div>


			<div id="mobile-contact-block" class="contact_list__block mobile_left_catalog">
				<span class="contact__link-mob-btn">
					<svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M6.5 1L1.5 6L6.5 11" stroke="#696F74" stroke-width="1.77778" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					Контакти
				</span>
				<div class="wrap-contact-mobile">
					<div class="time-work-title">Графік роботи Call-Центру</div>
					<ul>
						<div class="time-work">
							<span class="by_schedule">
								Графік роботи call-центру пн-нд, з <b>09:00</b> до <b>19:00</b> Замовлення на сайті - 24/7
							</span>
						</div>
					</ul>
					<div class="phone-wrap">
						<ul>
							<li>
		                		<a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone) ? $phone : ''; ?>">
		                			<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M8.39027 10.0945C8.90582 10.4577 9.44632 10.7631 10.0035 10.9942C10.3494 11.1196 10.8922 10.7644 11.2914 10.5032C11.3912 10.4379 11.4819 10.3784 11.5584 10.3339L11.5835 10.3198C11.9595 10.1086 12.377 9.8742 12.9138 9.98723C13.3961 10.0863 15.0509 11.3903 15.5083 11.8525C15.8076 12.1497 15.9739 12.4633 15.9989 12.7852C16.0488 13.982 14.4439 15.3273 14.0614 15.5584C13.2465 16.1527 12.1571 16.1444 10.8433 15.5502C9.43801 14.9807 7.75829 13.7839 6.13679 12.3313C5.55634 11.8113 4.44166 10.7164 4.12444 10.3508C2.48631 8.57625 1.10595 6.66967 0.457348 5.12624C0.149678 4.47421 0 3.87169 0 3.33521C0 2.80698 0.149678 2.33652 0.440717 1.9321C0.615341 1.62671 2.02064 -0.0405155 3.25964 0.000753129C3.56731 0.0337668 3.8833 0.190585 4.19097 0.487716C4.65663 0.941664 5.99541 2.58413 6.09519 3.07109C6.20904 3.59574 5.97298 4.00988 5.7603 4.38299L5.74595 4.40818C5.69801 4.491 5.63303 4.58924 5.56198 4.69664C5.30024 5.0923 4.95627 5.61228 5.07988 5.94335C5.38838 6.70268 5.8291 7.4455 6.36046 8.13055C6.95016 8.83562 7.87471 9.73136 8.39027 10.0945Z" fill="#97B63C"/>
									</svg>
									<?php echo isset($phone) ? $phone : ''; ?>
								</a>
		                	</li>
		                	<?php if($phone2) { ?>
		                    	<li>
		                    		 <a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone2) ? $phone2 : ''; ?>">
		                    		 	<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 311.81 311.81" >
									    <circle fill="#E60000" stroke="none" cx="155.91" cy="155.91" r="155.91"></circle>
									    <path stroke="none" fill="#ffffff"
									          d="M157.13,242.31c-42.57.14-86.87-36.19-87.06-94.54C69.95,109.18,90.76,72,117.37,50c26-21.49,61.51-35.28,93.76-35.39,4.15,0,8.49.33,11.15,1.23-28.2,5.85-50.64,32.09-50.54,61.86a16.16,16.16,0,0,0,.19,2.52c47.18,11.49,68.6,40,68.73,79.35S209.69,242.13,157.13,242.31Z"></path>
									</svg>
										<?php echo isset($phone2) ? $phone2 : ''; ?>
									</a>
		                    	</li>
		                    <?php } ?>
		                    <?php if($phone3) { ?>
		                    	<li>
		                    		<a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone3) ? $phone3 : ''; ?>">
		                    			<svg id="Layer_1" width="16" height="16" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><defs><style>.cls-999{fill:#4a84c5;}</style></defs><title>Artboard 1</title><path class="cls-999" d="M16,12.4h0a2.09,2.09,0,0,0,.78-.16,2,2,0,0,0,.67-.45,2,2,0,0,0,.44-.67,2,2,0,0,0,.16-.79V2.74a2,2,0,0,0-.1-.85,1.93,1.93,0,0,0-.43-.73,2.15,2.15,0,0,0-.69-.5,2,2,0,0,0-1.66,0,2,2,0,0,0-.69.5,2.1,2.1,0,0,0-.44.73,2,2,0,0,0-.09.85v7.59a2.13,2.13,0,0,0,.15.79,2.19,2.19,0,0,0,.45.67,2,2,0,0,0,.67.45,2.09,2.09,0,0,0,.78.16h0Z"/><path class="cls-999" d="M11.18,15.91a2.12,2.12,0,0,1-.38.71,2.26,2.26,0,0,1-.64.5,2.07,2.07,0,0,1-.77.22,2,2,0,0,1-.8-.1L1.41,14.9a2.12,2.12,0,0,1-1.19-1A2.09,2.09,0,0,1,.1,12.3,2.07,2.07,0,0,1,2.68,11l7.18,2.35a2.06,2.06,0,0,1,1.32,2.6Z"/><path class="cls-999" d="M21.2,16.62a2.14,2.14,0,0,1-.39-.71,2.06,2.06,0,0,1,1.32-2.6L29.32,11A2.07,2.07,0,0,1,31.9,12.3a2.09,2.09,0,0,1-.12,1.57,2.12,2.12,0,0,1-1.19,1l-7.18,2.34a2,2,0,0,1-.8.1,2.12,2.12,0,0,1-.78-.22A2,2,0,0,1,21.2,16.62Z"/><path class="cls-999" d="M6.16,31.1a2.07,2.07,0,0,1-.46-2.89l4.44-6.12a2.06,2.06,0,0,1,.59-.55,2.27,2.27,0,0,1,.75-.28,2.16,2.16,0,0,1,1.54.37,2.09,2.09,0,0,1,.45,2.9L9,30.64a2,2,0,0,1-1.35.83,2.16,2.16,0,0,1-.8,0,2.21,2.21,0,0,1-.73-.34Z"/><path class="cls-999" d="M26.66,29.75a2.05,2.05,0,0,1-.82,1.35v0a2,2,0,0,1-.73.34,2.2,2.2,0,0,1-.81,0,2.07,2.07,0,0,1-.75-.28,2,2,0,0,1-.59-.54l-4.44-6.15a2.12,2.12,0,0,1-.34-.73,2.16,2.16,0,0,1,0-.8,2.05,2.05,0,0,1,.28-.76,2,2,0,0,1,2.08-1,2.1,2.1,0,0,1,.76.27,2.06,2.06,0,0,1,.59.55l4.43,6.12A2.06,2.06,0,0,1,26.66,29.75Z"/></svg>
										<?php echo isset($phone3) ? $phone3 : ''; ?>
									</a>
		                    	</li>
		                    <?php } ?>
		                     <?php if($phone4) { ?>
		                    	<li>
		                    		<a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone4) ? $phone4 : ''; ?>">
		                    			<svg width="16" height="16" viewBox="0 0 36 37" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M32.6617 8.4437C31.3526 6.34915 29.7163 4.64733 27.8836 3.33824C25.9854 2.02915 23.8908 1.04733 21.469 0.523696C17.5417 -0.458122 13.7454 5.97006e-05 10.0799 1.63642C8.05083 2.55279 6.2181 3.79642 4.71265 5.43279C2.74901 7.46188 1.37447 9.68733 0.523557 12.2401V12.3055C0.523557 12.371 0.458102 12.5019 0.458102 12.5673C-0.392807 15.5128 0.392648 17.0837 1.70174 15.7746C1.76719 15.7091 1.83265 15.6437 1.83265 15.5782L1.8981 15.5128C3.40356 13.6146 5.82537 12.0437 5.82537 12.0437C6.41446 11.651 7.06901 11.1928 7.72356 10.8655C8.50901 10.4073 9.29447 10.0146 10.0145 9.75279C10.0145 9.75279 10.669 9.42551 10.8654 8.50915V8.4437C10.9963 7.65824 11.8472 5.49824 14.2036 5.36733C14.9236 5.23642 15.709 5.36733 16.3636 5.6946C17.5417 6.21824 18.3926 7.33097 18.589 8.64006C18.7199 9.68733 18.4581 10.6691 17.9345 11.4546C17.9345 11.5201 17.869 11.5201 17.869 11.5855C17.869 11.5855 17.869 11.5855 17.869 11.651C17.4108 12.2401 16.8217 12.6982 16.0363 12.9601C15.5781 13.091 15.1199 13.2219 14.7272 13.2219C14.4654 13.2873 14.1381 13.2219 13.8763 13.1564C13.549 13.091 13.2217 12.9601 12.9599 12.8292C12.5017 12.6328 12.109 12.371 11.9781 12.2401C11.8472 12.1091 11.7817 12.1091 11.6508 12.0437C11.389 11.9782 11.1926 12.0437 11.0617 12.1091C10.9963 12.1746 10.9308 12.1746 10.8654 12.2401C10.2763 12.6982 9.75265 13.2219 9.22901 13.7455C7.85447 15.1855 6.74174 16.8219 6.2181 17.6073C5.95628 18.0655 5.69447 18.5237 5.43265 18.9819C5.17083 19.4401 4.97447 19.8982 4.7781 20.3564C4.25447 20.9455 3.92719 21.731 3.66537 22.5164C3.66537 22.5819 3.66537 22.5819 3.59992 22.6473C3.59992 22.7128 3.53447 22.7782 3.53447 22.7782C3.53447 22.8437 3.53447 22.9092 3.46901 22.9092C1.83265 28.5382 4.7781 31.811 7.26537 28.4073C7.39628 28.211 7.52719 27.9492 7.6581 27.7528C10.2763 23.2364 15.5781 19.9637 15.5781 19.9637C15.9054 19.7673 16.2326 19.571 16.5599 19.3746C16.5599 19.3746 16.5599 19.3746 16.6254 19.3091C17.5417 18.7855 18.4581 18.3273 19.4399 17.9346C20.2908 17.5419 21.2072 17.2146 21.9926 17.0182C21.9926 17.0182 22.909 16.7564 23.1054 15.7091C23.3017 14.9237 23.8254 13.7455 25.1345 13.1564C25.4617 13.0255 25.7236 12.8946 26.0508 12.8292C26.3781 12.7637 26.7708 12.6982 27.0981 12.7637C27.2945 12.7637 27.4908 12.7637 27.6872 12.8292C29.3236 13.1564 30.6326 14.4655 30.829 16.2328C31.0908 18.3928 29.5854 20.291 27.4254 20.5528C26.3781 20.6837 25.3963 20.4219 24.6108 19.8328C23.6945 19.4401 22.9745 19.7019 22.7126 19.8328C21.9272 20.291 21.1417 20.7492 20.4217 21.2728C19.0472 22.3201 17.9345 23.3673 17.5417 23.7601C16.6254 24.7419 15.7745 25.7891 15.0545 26.9019C14.989 26.9673 14.989 27.0328 14.9236 27.0982C14.9236 27.0982 14.9236 27.0982 14.9236 27.1637C14.2036 28.3419 13.3526 29.9128 12.6981 31.7455C12.6326 31.8764 12.5672 32.0073 12.5672 32.2037C11.8472 34.5601 13.4181 35.5419 15.1199 35.8691C15.1199 35.8691 16.4945 36.131 18.4581 36.0001C18.589 36.0001 18.6545 36.0001 18.7854 36.0001C19.309 35.9346 19.7672 35.8692 20.2908 35.8037C23.6945 35.2146 26.7054 33.7746 29.3236 31.4837C32.5963 28.5382 34.5599 24.9382 35.149 20.6182C35.869 16.1673 35.0181 12.1091 32.6617 8.4437Z" fill="#FFC40C"/>
										</svg>
										<?php echo isset($phone4) ? $phone4 : ''; ?>
									</a>
		                    	</li>
		                    <?php } ?>
		                    
		                </ul>
					</div>
					<div class="social">
						<ul>
							<li>
								<a href="http://m.me/105857422278656" class="facebook" rel="noindex nofollow" alt="Facebook" title="Facebook">
									<svg width="20" height="20" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect width="40" height="40" rx="20" fill="#0B84EE"/>
										<path d="M21.6571 20.3648H25.3793L25.9637 16.5605H21.6563V14.4813C21.6563 12.901 22.1696 11.4996 23.6389 11.4996H26V8.17974C25.5852 8.12338 24.7078 8 23.05 8C19.5882 8 17.5587 9.8393 17.5587 14.0297V16.5605H14V20.3648H17.5587V30.821C18.2634 30.9276 18.9773 31 19.7101 31C20.3724 31 21.0189 30.9391 21.6571 30.8522V20.3648Z" fill="white"/>
									</svg>
								</a>
							</li>
							<li>
								<a class="viber" href="viber://pa?chatURI=domopolisua" rel="noindex nofollow" alt="Viber" title="Viber">
									<svg width="20" height="20" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect width="40" height="40" rx="20" fill="#7C509A"/>
										<path d="M26.2539 9.54591C22.4424 8.81803 18.4843 8.81803 14.6728 9.54591C12.9869 9.90986 10.8613 11.9479 10.4948 13.5493C9.83508 16.6792 9.83508 19.8819 10.4948 23.0118C10.9346 24.6131 13.0602 26.6512 14.6728 27.0152C14.7461 27.0152 14.8194 27.0879 14.8194 27.1607V31.7464C14.8194 31.9648 15.1126 32.1103 15.2592 31.892L17.4581 29.6355C17.4581 29.6355 19.2173 27.8158 19.5105 27.5247C19.5105 27.5247 19.5838 27.4519 19.6571 27.4519C21.856 27.5247 24.1283 27.3063 26.3272 26.9424C28.0131 26.5784 30.1387 24.5403 30.5052 22.939C31.1649 19.8091 31.1649 16.6064 30.5052 13.4765C30.0654 11.9479 27.9398 9.90986 26.2539 9.54591ZM26.3272 23.2302C25.9607 23.958 25.5209 24.5403 24.788 24.9043C24.5681 24.9771 24.3482 25.0499 24.1283 25.1227C23.8351 25.0499 23.6152 24.9771 23.3953 24.9043C21.0497 23.958 18.8508 22.6478 17.0916 20.7553C16.1387 19.6635 15.3325 18.4261 14.6728 17.1159C14.3796 16.4608 14.0864 15.8785 13.8665 15.2234C13.6466 14.6411 14.0131 14.0588 14.3796 13.6221C14.7461 13.1853 15.1859 12.8942 15.699 12.6758C16.0654 12.4575 16.4319 12.603 16.7251 12.8942C17.3115 13.6221 17.8979 14.35 18.3377 15.1506C18.6309 15.6601 18.5576 16.2425 18.0445 16.6064C17.8979 16.6792 17.8246 16.752 17.678 16.8976C17.6047 16.9703 17.4581 17.0431 17.3848 17.1887C17.2382 17.4071 17.2382 17.6254 17.3115 17.8438C17.8979 19.5179 18.9974 20.8281 20.6832 21.556C20.9764 21.7016 21.1963 21.7744 21.5628 21.7744C22.0759 21.7016 22.2958 21.1193 22.6623 20.8281C23.0288 20.537 23.4686 20.537 23.9084 20.7553C24.2749 20.9737 24.6414 21.2649 25.0811 21.556C25.4476 21.8472 25.8141 22.0655 26.1806 22.3567C26.4005 22.5023 26.4738 22.8662 26.3272 23.2302ZM23.2487 17.771C23.1021 17.771 23.1754 17.771 23.2487 17.771C22.9555 17.771 22.8822 17.6254 22.8089 17.4071C22.8089 17.2615 22.8089 17.0431 22.7356 16.8976C22.6623 16.6064 22.5157 16.3152 22.2225 16.0969C22.0759 16.0241 21.9293 15.9513 21.7827 15.8785C21.5628 15.8057 21.4162 15.8057 21.1963 15.8057C20.9764 15.7329 20.9031 15.5874 20.9031 15.369C20.9031 15.2234 21.123 15.0778 21.2696 15.0778C22.4424 15.1506 23.322 15.8057 23.4686 17.1887C23.4686 17.2615 23.4686 17.4071 23.4686 17.4799C23.4686 17.6254 23.3953 17.771 23.2487 17.771ZM22.5157 14.5683C22.1492 14.4227 21.7827 14.2772 21.3429 14.2044C21.1963 14.2044 20.9764 14.1316 20.8298 14.1316C20.6099 14.1316 20.4634 13.986 20.5366 13.7676C20.5366 13.5493 20.6832 13.4037 20.9031 13.4765C21.6361 13.5493 22.2958 13.6949 22.9555 13.986C24.2749 14.6411 25.0079 15.7329 25.2277 17.1887C25.2277 17.2615 25.2277 17.3343 25.2277 17.4071C25.2277 17.5526 25.2277 17.6982 25.2277 17.9166C25.2277 17.9894 25.2277 18.0622 25.2277 18.135C25.1545 18.4261 24.6414 18.4989 24.5681 18.135C24.5681 18.0622 24.4948 17.9166 24.4948 17.8438C24.4948 17.1887 24.3482 16.5336 24.055 15.9513C23.6152 15.2962 23.1021 14.8595 22.5157 14.5683ZM26.4738 18.9356C26.2539 18.9356 26.1073 18.7173 26.1073 18.4989C26.1073 18.0622 26.034 17.6254 25.9607 17.1887C25.6675 14.8595 23.7618 12.967 21.4895 12.603C21.123 12.5302 20.7565 12.5302 20.4634 12.4575C20.2435 12.4575 19.9503 12.4575 19.877 12.1663C19.8037 11.9479 20.0236 11.7296 20.2435 11.7296C20.3168 11.7296 20.3901 11.7296 20.3901 11.7296C23.3953 11.8024 20.5367 11.7296 20.3901 11.7296C23.4686 11.8024 26.034 13.8404 26.5471 16.8976C26.6204 17.4071 26.6937 17.9166 26.6937 18.4989C26.8403 18.7173 26.6937 18.9356 26.4738 18.9356Z" fill="white"/>
									</svg>
								</a>
							</li>
							<li>
								<a class="tg" href="https://t.me/domopolis_bot" rel="noindex nofollow" alt="Telegram" title="Telegram">
									<svg width="20" height="20" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect width="40" height="40" rx="20" fill="#27A6E5"/>
										<path d="M28 12.6022L24.9946 28.2923C24.9946 28.2923 24.5741 29.3801 23.4189 28.8584L16.4846 23.3526L16.4524 23.3364C17.3891 22.4654 24.6524 15.7027 24.9698 15.3961C25.4613 14.9214 25.1562 14.6387 24.5856 14.9974L13.8568 22.053L9.71764 20.6108C9.71764 20.6108 9.06626 20.3708 9.00359 19.8491C8.9401 19.3265 9.73908 19.0439 9.73908 19.0439L26.6131 12.1889C26.6131 12.1889 28 11.5579 28 12.6022Z" fill="#FEFEFE"/>
									</svg>
								</a>
							</li>
						</ul>
					</div>
					<ul class="mail">
						<li>
							<a href="mailto:info@domopolis.ua">
								info@domopolis.ua
							</a>
						</li>
					</ul>
				</div>
			</div>
		<?php } ?>
		<!--/mobile-menu category-->
		<!--/header-->
		