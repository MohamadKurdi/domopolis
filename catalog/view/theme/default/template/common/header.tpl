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
			background-image: url(/catalog/view/theme/default/img/load_more.svg);
			}
			.grecaptcha-badge{
			right: -999px !important;
			}
			header .menu-horizontal > ul > li span.top_menu-horizontal{white-space: nowrap;}

			
			section{margin:50px auto}
			section.inside{padding:50px 0;margin:0}
			.wrap{position:relative;max-width:1600px;width:100%;padding:0 20px;margin:0 auto;height:100%}
			.wrap:after{clear:both;content:"";display:block}
			#new_mmenu li.parent>.topmenu{left:276px;max-width:1242px;width:1242px;top:-2px;border-bottom:2px solid #FFC34F;border-top:2px solid #FFC34F;border-right:2px solid #FFC34F}
			#new_mmenu{width:278px;border-bottom:2px solid #FFC34F;border-top:2px solid #FFC34F;border-left:2px solid #FFC34F}
			#new_mmenu li.level1 > a{font-weight:500;font-size:14px;padding:9px 13px 9px 50px}
			#new_mmenu li.level1 > a svg{width:24px;height:24px}
			#new_mmenu li.level1 > i,#new_mmenu .level1.open_menu > i{color:#FFC34F!important}
			#new_mmenu .level1.open_menu{background:#F7F4F4}
			#new_mmenu li.parent > .topmenu .wrap-children{grid-template-columns:3fr 2fr 9fr;grid-template-rows:1fr;grid-gap:0;padding:30px 0}
			#new_mmenu li.parent > .topmenu .wrap-children > div{padding:0 30px}
			#new_mmenu li.parent > .topmenu .children-category-list{grid-column-start:1;grid-column-end:1;grid-row-start:1;grid-row-end:1}
			#new_mmenu li.parent > .topmenu .main-center-cat-block{grid-column-start:3;grid-column-end:3;grid-row-start:1;grid-row-end:1}
			#new_mmenu li.parent > .topmenu .category-list-menu{grid-column-start:2;grid-column-end:2;grid-row-start:1;grid-row-end:1;border-left:1px solid #EAE9E8;border-right:1px solid #EAE9E8}
			#new_mmenu li.parent > .topmenu .children-category-list ul > li{width:100%;margin-right:0;border-bottom:0}
			#new_mmenu li.level1 .topmenu .children-category-list a{padding:0;font-size:15px;margin-bottom:10px;line-height:17px}
			#new_mmenu .topmenu .title_mmenu{font-size:24px;font-weight:500;margin-bottom:19px;display:block}
			#new_mmenu li.parent > .topmenu .category-list-menu .title_mmenu{text-align:center}
			#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item a{padding:0}
			#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item .product__line__promocode{
			display: none !important;
			}
			#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item .product__title a{font-size:17px;line-height:22px}
			#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item .product_add-favorite{display:none}
			#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item:hover .product__info .product__delivery{display:none!important}
			#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item:hover .product__btn-cart{width:70px}
			#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item .product__btn-cart button{width:70px}
			#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item:hover .product__btn-cart button{font-size:0}
			#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item:hover .product__bottom{flex-direction:row}
			#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item:hover .product__info{position:inherit;top:0;left:0;right:0;background:0;border:0;border-top:none;box-shadow:none;padding:0 0 0 15px;flex-direction:column}
			#new_mmenu li.parent > .topmenu ul li.parent > div{left:100%;top:0;box-shadow:0 0 8px #ccccccd6;padding:5px 15px}
			#new_mmenu li.parent > .topmenu .children-category-list ul > li.level2 a i{transform:rotate(0)!important}
			#new_mmenu li.parent > .topmenu .children-category-list ul > li.level2.open_menu a i::before{transform:rotate(0deg)!important}
			#new_mmenu .topmenu.col-2-list .wrap-children{grid-template-columns:5fr 2fr 5fr!important}
			#new_mmenu .topmenu.col-2-list .main-center-cat-block > ul{grid-template-columns:repeat(2,245.5px)!important}
			#new_mmenu .topmenu.col-2-list .wrap-children .children-category-list > ul{
			/*columns:2;
			display:block;
			height:100%*/
			display: flex;
			flex-wrap: wrap
			}
			#new_mmenu .topmenu.col-2-list .wrap-children .children-category-list > ul > li{display:inline-block;flex-basis: 50%;padding:0 5px;}
			
			#new_mmenu .topmenu.col-3-list .wrap-children{grid-template-columns:9fr 2fr 4fr!important}
			#new_mmenu .topmenu.col-3-list .main-center-cat-block > ul{grid-template-columns:245.5px!important}
			#new_mmenu .topmenu.col-3-list .wrap-children .children-category-list > ul{columns:3;display:block;height:100%}
			#new_mmenu .topmenu.col-3-list .wrap-children .children-category-list > ul > li{display:inline-block}
			#new_mmenu .topmenu.col-2-list .main-center-cat-block .product__item:nth-of-type(3),#new_mmenu .topmenu.col-3-list .main-center-cat-block .product__item:nth-of-type(2),#new_mmenu .topmenu.col-3-list .main-center-cat-block .product__item:nth-of-type(3){display:none!important}
			@media (min-width: 1000px) and (max-width: 1440px) {
			section.slider-section{overflow:inherit!important}
			#new_mmenu li.parent > .topmenu .main-center-cat-block .product__item .product__title a{font-size:14px}
			#new_mmenu li.level1 .topmenu .children-category-list a{font-size:14px;line-height:16px}
			#new_mmenu li.parent > .topmenu .wrap-children > div{padding:0 15px}
			#new_mmenu li.parent > .topmenu .wrap-children{padding:15px 0}
			#new_mmenu .topmenu .title_mmenu{font-size:18px;margin-bottom: 10px;}
			.menu-list li.parent > .topmenu .category-list-menu_brands .collections_ul {grid-template-rows: repeat(4,85px) !important;grid-gap: 10px !important;}
			.menu-list li.parent > .topmenu .category-list-menu_brands .collections_ul img{height: 100%}
			#new_mmenu li.parent > .topmenu .main-center-cat-block ul{grid-template-columns:repeat(2,226.5px) !important}
			#new_mmenu .topmenu.col-1-list .main-center-cat-block .product__item:nth-of-type(3){display:none!important}
			#new_mmenu .topmenu.col-2-list .wrap-children{grid-template-columns:6fr 2fr 5fr!important}
			#new_mmenu li.parent > .topmenu.col-1-list .wrap-children{grid-template-columns:3fr 2fr 6fr}
			.menu-list li.parent > .topmenu .category-list-menu .collections_ul > div a img{max-height: 70px !important; }
			.menu-list li.parent > .topmenu .category-list-menu .collections_ul {
			height: calc(100% - 109px) !important;
			}
			}
			header{position:relative;z-index:220;background:#fff}
			header.fixed{padding-bottom:55px}
			header .head{display:flex;align-items:stretch;padding:20px 0}
			header .logo{flex-shrink:0}
			header .logo a{display:block}
			header .logo a svg,header .logo img{display:block;height:50px!important}
			header .middle{padding-left:15px;flex-grow:1}
			header .sub-head{display:none;margin-top:-5px;height:41px}
			header .sub-head>div,header .sub-head>span{margin-left:40px}
			header .sub-head>div:first-child{margin-left:0;}
			/*header .head>.icons-link{display:none}*/
			header .icons-link{display:flex;align-self:flex-end;padding-left:10px}
			header .icons-link > li{margin:0 15px}
			header .icons-link li:last-child{margin-right:0}
			header .icons-link a{position:relative;display:flex;height:50px}
			header .icons-link path{transition:all .5s ease}
			header .icons-link a.favorite:hover path{stroke:#35805e}
			header .icons-link a.favorite .txt{display: none;}
			header .icons-link a.cart:hover path{fill:#35805e}
			header .icons-link a.profile:hover path{fill:#35805e}
			header .icons-link li svg{align-self:center;margin:0 auto}
			header .icons-link i{position:absolute;right:-15px;top:0;height:20px;line-height:20px;font-style:normal;color:#fff;font-size:13px;font-weight:500;text-align:center;padding:0 7px;background:#ffc34f;border-radius:10px;box-sizing:content-box}
			header.fixed .top-menu{position:fixed;top:0;left:0;width:100%;z-index:5000;border-bottom:1px solid #eae9e8;box-shadow:0 0 30px rgba(0,0,0,0.1)}
			header .top-menu{position:relative;z-index:200;background:#f7f4f4}
			header .top-menu .inner{height:55px;display:flex;justify-content:space-between}
			header .top-menu .icons-link{padding-left:0;margin-right:-15px;display:inline-flex;vertical-align:top}
			header .top-menu .icons-link li{margin:0 5px}
			header .top-menu .icons-link button,header .top-menu .icons-link a{height:55px;padding:0 10px}
			header .top-menu .icons-link a.favorite svg{width:30px;height:26px}
			header .top-menu .icons-link button svg,header .top-menu .icons-link a.cart svg{width:33px;height:30px}
			header .top-menu .icons-link a.profile svg{width:30px;height:30px}
			header .top-menu .icons-link i{right:-2px;top:10px;height:16px;line-height:16px;font-size:12px;padding:0 5px}
			header .mob-menu__btn{display:inline-flex;justify-content:center;align-items:center}
			header .menu-horizontal{flex-grow:1;display:none}
			header .menu-horizontal>ul{display:flex;height:100%}
			header .menu-horizontal>ul>li:first-child{margin-left:0}
			header .menu-horizontal>ul>li{margin-left:30px}
			header .menu-horizontal>ul>li i{font-style:normal}
			header .menu-horizontal>ul>li>a{white-space: nowrap;display:flex;height:100%;align-items:center;font-size:14px;font-weight:500;transition:color .2s ease;-webkit-touch-callout:none;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}
			header .menu-horizontal a.stocks{color:#e16a5d}
			header .menu-horizontal>ul>li svg{margin-right:10px}
			header .social-contact__list{display:none;vertical-align:top;height:100%}
			header .social-contact__list li:first-child{margin-left:0}
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
			header .social-contact__list a.wa svg{position:relative;top:-1px}
			@media screen and (min-width: 440px) {
			header .social-contact__list{display:inline-flex}
			header .top-menu .icons-link{margin-right:15px}
			header .middle{padding-left:20px}
			}
			@media screen and (min-width: 768px) {
			header .sub-head{display:flex;align-items:flex-start}
			header .logo a svg,header .logo a,header .logo img{height:86px!important}
			header .top-menu .icons-link{margin-right:30px}
			}
			@media screen and (min-width: 1000px) {
			header .head>.icons-link{display:flex}
			header .top-menu .icons-link{display:none}
			header .mob-menu__btn{display:none}
			header .menu-horizontal{display:flex}
			}
			@media screen and (min-width: 1600px) {
			header .head{padding:30px 0}
			header .middle{padding-left:40px}
			header .icons-link{padding-left:70px}
			header .icons-link > li{margin:0 30px}
			header .menu-horizontal>ul>li>a{font-size:17px;white-space: nowrap}
			header .menu-horizontal>ul>li{margin-left:40px}
			header .menu-horizontal>ul>li:nth-child(1) i{display:inline}
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
			header #account_header > button .user-names{margin-right:5px;font-weight:400;font-size:12px;border-bottom:1px dashed #51a881;height: auto;}
			header #account_header > button span i{display:none}
			header #account_header > button,header #header-small-cart button{background-color:transparent;margin:auto}
			header #header-small-cart button:before{content: '';position: absolute;right: 0;left: -11px;width: 70px;bottom: -10px;height: 20px;}
			header #header-small-cart .btn-cart{background:transparent;border:0;margin-top:4px;padding:7px 0}
			header #header-small-cart .btn-cart:hover{color: #51a881}
			header #account_header .content{min-width:320px!important}
			header #account_header .content.active{display: block;}
			header #account_header .content.user_logOut{min-height:120px!important;min-width:450px!important}
			header #account_header .user_logOut p{margin-bottom:0;margin-top:20px;text-align:center;border-bottom:0!important}
			header #account_header .user_logOut p a{height:auto;display:inline-block;position:relative;text-decoration:underline}
			header #account_header .user_logOut p a i{position:relative}
			header #account_header .content > p{font-size:16px;margin-bottom:20px;border-bottom:1px solid #cccccc47;display:block;padding:0 10px 4px 0; text-align: left;}
			header #account_header .content > p > a{height: auto;display: inline-block;}
			header #account_header .content ul li{position:relative;margin:0}
			header #account_header .content ul li > a{height:auto;display:block;padding:7px 0px 7px 0px;font-weight:500}
			header #account_header .content ul li > a svg{width: 20px; height: 18px;margin-right: 10px;}
			header #account_header .user_logOut a i,header #account_header .content ul li a i{font-weight:700;left:0;right:inherit;bottom:0;top:0;margin:auto;color:#000000b5;background:transparent}
			header #account_header .content ul li span{color:#b2b2b2;font-size:13px;padding:7px 20px 7px 30px}
			header #account_header .content ul li span a{height:auto;display:inline-block;text-decoration:underline;color:#b2b2b2;font-size:13px}
			header #account_header .content,header #header-small-cart .content{clear:both;display:none;position:absolute;top:50px;right:0;padding:20px!important;min-height:150px;min-width:460px;border-top:1px solid #e0e0e0;-webkit-box-shadow:0 3px 2px rgba(0,0,0,0.25);-moz-box-shadow:0 3px 2px rgba(0,0,0,0.25);box-shadow:0 3px 2px rgba(0,0,0,0.25);background:#fff;z-index:999}
			header #header-small-cart .content span{font-size:18px;font-weight:500;display:inline-block;margin-bottom:15px}
			header #header-small-cart .content .empty{display:inline-block;margin:auto;position:absolute;top:0;right:0;bottom:0;left:0;height:25px;text-align:center;font-weight:500;font-size:16px}
			header #header-small-cart .content .empty p{
			font-size: 14px;
			margin-top: 15px;
			font-weight: 400;
			margin-bottom: 0;
			}
			header #header-small-cart .content .empty p a{
			color: #51a881;
			border-bottom:1px dotted #51a881;
			position: initial;
			height: auto;
			display: inline-block;
			}
			header #header-small-cart .content .mini-cart-info{overflow-y:auto;max-height:430px}
			header #header-small-cart .content .mini-cart-info table{border-collapse:collapse;width:100%;margin-bottom:15px}
			header #header-small-cart .content .mini-cart-info table td{vertical-align:top;padding:5px}
			header #header-small-cart .content .mini-cart-info table td.image{width:50px}
			header #header-small-cart .content .mini-cart-info table td.image a{height:auto}
			header #header-small-cart .content .mini-cart-info table td.image img{border:1px solid #cdcd;max-height: 40px;height: 40px;}
			header #header-small-cart .content .mini-cart-info table td.name{font-size:14px;line-height:15px;font-weight:500}
			header #header-small-cart .content .mini-cart-info table td.quantity{text-align:right}
			header #header-small-cart .content .mini-cart-info table td.total{font-weight:500;text-align:right}
			header #header-small-cart .content .mini-cart-info table td.remove{padding: 5px 0;width: 10px;}
			header #header-small-cart .content .mini-cart-info table td.remove img{width:10px; cursor: pointer;}
			header #header-small-cart .content .mini-cart-total table{margin:0 0 15px}
			header #header-small-cart .content .mini-cart-total table tr td:first-child{font-weight:400}
			header #header-small-cart .content .mini-cart-total table td{font-size:17px;padding:5px;font-weight:500}
			header #header-small-cart .content .checkout{padding:0;width:100%;margin:0;display:flex;flex-direction:column-reverse;border:0}
			header #header-small-cart .content .checkout a.btn-cart{color:#51a881;font-weight:500;text-decoration:underline;height:35px}
			header #header-small-cart .content .checkout a{margin:2.5px 0;justify-content:center;align-items:center}
			.main-slider{-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}
			.main-slider .swiper-slide{padding:40px 0;width:100%;display:flex;align-items:center;margin:0 auto}
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
			font-weight: 500;
			letter-spacing: .21px;
			height: 54px;
			border: 1px solid #51a881;
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
			background-image: url(/catalog/view/theme/kp/img/close-modal.svg);
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
			scrollbar-color: #ffc34f #6f686869 !important;
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
			
			@media screen and (max-width: 1520px) {
			.slider-top-wrap{padding:0}
			.main-slider .swiper-slide{width:100%!important;background-size:cover;object-fit:cover}
			
			}
			@media screen and (max-width: 1400px) {
			header .menu-horizontal>ul>li>a{    font-size: 13px;}
			}
			
			@media screen and (min-width: 768px) {
			.main-slider .info{text-align:left;max-width:355px;padding:25px 40px 90px}
			.main-slider .title-slide{font-size:32px;line-height:1.5em}
			.main-slider a.btn{width:200px;margin-left:0}
			.main-slider .swiper-pagination{text-align:left;padding-left:56px}
			.main-slider .swiper-pagination-bullets{bottom:100px}
			.main-slider .swiper-slide{min-height:370px}
			}
			@media screen and (min-width: 1400px) {
			.main-slider .swiper-slide{min-height:450px}
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
			.brand-top{padding:0;margin-top:0;max-width:5000px;background:#f7f4f4}
			.brand-slider{padding:30px 0 40px;-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}
			.brand-slider .swiper-slide{text-align:center;display:-webkit-box;display:-ms-flexbox;display:-webkit-flex;display:flex;-webkit-box-pack:center;-ms-flex-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-ms-flex-align:center;-webkit-align-items:center;align-items:center}
			.brand-slider .swiper-slide a img{transition:opacity .3s ease}
			.brand-slider .swiper-slide a:hover img{opacity:.7}
			.brand-slider .swiper-pagination-bullets{position:relative;text-align:center}
			.brand-slider .swiper-pagination-bullet{margin:0 10px}
			.brand-slider .swiper-prev-slide,.brand-slider .swiper-next-slide{display:none;position:absolute;top:calc(50% - 30px);cursor:pointer;height:60px;z-index:10}
			.brand-slider .swiper-prev-slide svg path,.brand-slider .swiper-prev-slide svg rect,.brand-slider .swiper-next-slide svg path,.brand-slider .swiper-next-slide svg rect{transition:all .3s ease}
			.brand-slider .swiper-prev-slide:hover svg path,.brand-slider .swiper-prev-slide:hover svg rect,.brand-slider .swiper-next-slide:hover svg path,.brand-slider .swiper-next-slide:hover svg rect{stroke:#7acaa6}
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
			.header-banner{background: -webkit-linear-gradient(90deg, rgb(122, 176, 76), rgb(81, 168, 129));background: -moz-linear-gradient(90deg, rgb(122, 176, 76), rgb(81, 168, 129));background: linear-gradient(90deg, rgb(122, 176, 76), rgb(81, 168, 129));overflow:hidden; position: relative;}
			/*.header-banner .swiper-wrapper{height:64px;}*/
			.header-banner.hide{max-height:0;padding-top:0;padding-bottom:0;margin-top:0;margin-bottom:0;transition-timing-function:cubic-bezier(0,1,.5,1)}
			.header-banner .content{position:relative;max-width:60%;margin:auto;padding: 0 !important}
			.header-banner .close{position:absolute;right:40px;width:15px;height:15px;line-height:15px;top:0;bottom:0;margin:auto;cursor:pointer;background-image:url(/catalog/view/theme/kp/img/close-modal.svg);background-size:15px 15px;background-repeat:no-repeat;text-align:center;background-position:center;opacity:.5;background-color:transparent}
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
			/*.header-banner:before{
			content: '';
			background: url(/catalog/view/theme/kp/img/snow1.png);
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
			background: url(/catalog/view/theme/kp/img/snow1.png);
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
			.header-banner .content{max-width:80%}
			.header-banner .header-promotion span{font-size:13px;line-height:18px}
			.header-banner .header-promotion--text p{font-size:13px;line-height:18px}
			}
			@media screen and (max-width:1250px){
			.header-banner .content{max-width:80%}
			}
			@media screen and (max-width:990px){
			.header-banner:before,
			.header-banner:after{
			opacity: 0.2;
			filter: drop-shadow(1px 1px 5px #235e3d);
			}
			}
			@media screen and (max-width:900px){
				#popup-cart .object{
					padding: 40px 15px 15px 15px !important;
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
			.header-banner .content{max-width:80%}
			.header-banner .swiper-slide{flex-direction:column}
			.header-banner .header-promotion{max-width:100%;padding:0 15px}
			.header-banner .header-promotion--text{max-width:100%;padding:0 15px;margin:10px 0}
			.header-banner .subtitle{max-width:100%;padding:0 15px}
			.header-banner .button-next,.header-banner .button-prev{display:none}
			.header-banner:after{content: unset;}
			.header-banner:before{background-size: cover;}
			}
			@media screen and (max-width:560px){
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
			}
			header #account_header .user_logOut p a{
			padding-left: 0
			}
			.footer__payments .by-img{
			width: 100%;
			}
			.header-banner .close{right:20px}
			.header-banner .content{max-width:90%}
			.header-banner .header-promotion--text {max-width: 100%;padding: 0 5px;margin: 2px 0;}
			.header-banner .swiper-slide{padding: 5px 0;}
			.header-banner .swiper-slide {justify-content: center;margin: auto;}
			

			header.fixed{padding-bottom: 111px !important;}
			header.fixed .top-menu {top: 56px !important;}

			header.fixed .mobile-menu {top: 56px !important;height: calc(100vh - 56px) !important;}
			.header-banner .header-promotion span svg{
			width: 10px;
			height: 10px;
			min-width: 10px;
			}
			.header-banner .header-promotion span{
			font-size: 11px
			}
			}
			@media screen and (max-width: 480px) {
			.header-banner .header-promotion span svg {
			width: 10px;
			height: 10px;
			min-width: 10px;
			margin-bottom: auto;
			margin-top: 3px;
			margin-left: auto;
			}
			}
		</style>
		

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
	
	<body>
		<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/header_body_scripts.tpl')); ?>
		
		<style>
			@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap');
		</style> 	
		
		<!--header-->
		<header>
			
			<!-- header baner -->
			<?php if ($notifies) { ?>
				<div class="header-banner" >
					<div class="wrap">
						<div class="close-button pull-right">
							<button type="button" class="close"></button>
						</div>					
						<div class="content">
							<div id="carousel-slide-up" class="swiper-container">
								<div class="swiper-wrapper">
									<?php foreach ($notifies as $notify) { ?>
										<div class="swiper-slide test">
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
				</div>
			<?php } ?>
			<!-- !header baner -->
			
			<!--<span class="callback-view" id="callback-view"><i class="fa fa-phone"></i> Позвоните мне</span>-->
			<!-- <span data-target="callback-view" class="do-popup-element callback-view desctop-v">
				<span id="popup__toggle">
				<div class="circlephone" style="transform-origin: center;"></div>
				<div class="circle-fill" style="transform-origin: center;"></div>
				<div class="img-circle" style="transform-origin: center;">
				<div class="img-circleblock" style="transform-origin: center;"></div>
				</div>
				</span>
			</span> -->
			<div class="wrap top-search">
				<!--head-->
				<div class="head">
					<div class="logo">					
						<a href="<?php echo $home; ?>">
						<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 706.49 296.83"><title><?php echo($logo_alt_title);?></title><defs><style>.cls-1{fill:#4b4948;}.cls-2,.cls-7{fill:none;}.cls-2{stroke:#fff;stroke-miterlimit:10;stroke-width:0.5px;}.cls-3{fill:#57ac79;}.cls-4{fill:#e1675d;}.cls-5{fill:#fff;}.cls-6{fill:#fbc04f;}</style></defs><g id="Слой_1" data-name="Слой 1"><path class="cls-1" d="M289.36,175.42V38.74H314V175.42Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M353.65,175.42V57.13H331.88V38.74h68.17V57.13H378.62V175.42Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M447.58,178.05q-15,0-23.54-5.9A30.79,30.79,0,0,1,412.15,156a74.93,74.93,0,0,1-3.38-23.54V83.73q0-14,3.38-24.3A29.12,29.12,0,0,1,424,43.57Q432.56,38,447.58,38q14.18,0,22.19,4.81a26.44,26.44,0,0,1,11.39,13.75,58.61,58.61,0,0,1,3.38,20.76V88.79h-24V77a83.5,83.5,0,0,0-.6-10.46,13,13,0,0,0-3.2-7.51c-1.75-1.86-4.75-2.79-9-2.79s-7.4,1-9.36,3a14.21,14.21,0,0,0-3.8,7.93,67.86,67.86,0,0,0-.84,11.22v59.39A52.51,52.51,0,0,0,434.93,150a12.66,12.66,0,0,0,4.3,7.25c2.08,1.64,4.92,2.45,8.52,2.45q6.24,0,8.86-2.95a14.57,14.57,0,0,0,3.29-7.85,80.73,80.73,0,0,0,.68-11V125.57h24v11a66.31,66.31,0,0,1-3.21,21.52,28,28,0,0,1-11.22,14.68Q462.09,178.05,447.58,178.05Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M502.58,175.42V38.74h24.81v57.2h27.84V38.74h25V175.42h-25V113.66H527.39v61.76Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M598,175.42V38.74h57.37V56.29h-32.4V95.61h25.48V113H622.93v45.22h32.74v17.21Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M673.63,175.42V38.74H691l33.24,78v-78h20.58V175.42H728.3L694.89,93.75v81.67Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M353.64,333.14V196.46H393.8q12.31,0,20,4.56a27,27,0,0,1,11.31,13.25q3.61,8.68,3.62,21,0,13.33-4.55,21.52a27.15,27.15,0,0,1-12.74,11.89,46.38,46.38,0,0,1-19.32,3.71h-13.5v60.75Zm25-78.29h9.45q6.75,0,10.55-1.94a10.42,10.42,0,0,0,5.23-6.25,37.87,37.87,0,0,0,1.43-11.56,54.13,54.13,0,0,0-1.1-12.06,10.34,10.34,0,0,0-4.72-6.83q-3.63-2.19-11.39-2.2h-9.45Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M446.78,333.14V196.46h32.73q13.85,0,23.37,3.29A26.83,26.83,0,0,1,517.39,211q5,7.92,5,21.59a65.72,65.72,0,0,1-1.52,14.85,28.15,28.15,0,0,1-5.14,11.22,23.13,23.13,0,0,1-10,7.17l19.07,67.33h-25l-16.53-62.6H471.75v62.6Zm25-78.29h7.76q7.26,0,11.56-2.11a12.35,12.35,0,0,0,6.16-6.75,32.33,32.33,0,0,0,1.86-11.9q0-10.29-3.8-15.44t-14.43-5.14h-9.11Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M583.61,334.83q-14.52,0-23.12-5.4a29.82,29.82,0,0,1-12.32-15.53q-3.7-10.12-3.71-24V238.82q0-13.84,3.71-23.71A29.05,29.05,0,0,1,560.49,200q8.61-5.23,23.12-5.23,14.83,0,23.36,5.23a29.63,29.63,0,0,1,12.32,15.1q3.8,9.87,3.8,23.71v51.29q0,13.67-3.8,23.71A30.85,30.85,0,0,1,607,329.34Q598.45,334.83,583.61,334.83Zm0-18.4c4.16,0,7.22-.89,9.19-2.7a13.57,13.57,0,0,0,4-7.42,49.46,49.46,0,0,0,1-10.29V233.08a47.91,47.91,0,0,0-1-10.29,13,13,0,0,0-4-7.17q-3-2.62-9.19-2.62-5.91,0-9,2.62a12.83,12.83,0,0,0-4,7.17,47.91,47.91,0,0,0-1,10.29V296a53.33,53.33,0,0,0,.93,10.29,12.9,12.9,0,0,0,4,7.42C576.52,315.54,579.56,316.43,583.61,316.43Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M645.55,333.14V196.46h56.18v17.21H670.52V253.5H696v17.38H670.52v62.26Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M720.3,333.14V196.46h24.63V333.14Z" transform="translate(-38.44 -38)"></path><path class="cls-2" d="M69.39,280.22" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M196.13,175.42V38.74h25V98.81l27-60.07h23.79L245.73,99.82l28.86,75.6H249.78l-22.94-62.27-5.74,10.47v51.8Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M175,90.6V38.9h0V90.6Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M175,114.44V93.57h0v20.88Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M174.92,90.6V38.9H38.44V175.42H90.15A93.89,93.89,0,0,1,174.92,90.6Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M174.92,114.45V93.57a91,91,0,0,0-81.8,81.85H114A70.16,70.16,0,0,1,174.92,114.45Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M115.49,175.42h59.43V115.94A68.68,68.68,0,0,0,115.49,175.42Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M174.92,115.94v59.48h0V115.94Z" transform="translate(-38.44 -38)"></path><rect class="cls-4" x="136.47" y="158.38" width="0.04" height="0.04"></rect><path class="cls-4" d="M174.92,196.42v80.87A93.91,93.91,0,0,1,90.6,196.42H38.44V332.89H175V196.42Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M90.59,196.38H38.44v0H90.6Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M114.58,196.42h-21a91,91,0,0,0,81.34,77.9V253.44A70.18,70.18,0,0,1,114.58,196.42Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M114.57,196.38h-21v0h21Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M174.92,252V196.42H116.08A68.71,68.71,0,0,0,174.92,252Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M116.08,196.42h58.84v0H116.07Z" transform="translate(-38.44 -38)"></path><path class="cls-5" d="M93.58,196.42v0h-3v0Z" transform="translate(-38.44 -38)"></path><path class="cls-5" d="M116.08,196.42v0h-1.5v0Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M196.17,196.38H196v55l.22,0Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M250.91,196.38H196.17v54.93A68.71,68.71,0,0,0,250.91,196.38Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M196,276.86v56h.22V276.83Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M332.46,196.38h-56a93.93,93.93,0,0,1-80.24,80.45v56.06H332.46Z" transform="translate(-38.44 -38)"></path><path class="cls-7" d="M196,273.87v-21A71,71,0,0,1,183.5,254a69.34,69.34,0,0,1-8.58-.55v20.88q4.23.4,8.58.41A91.34,91.34,0,0,0,196,273.87Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M273.42,196.38h-21a70.21,70.21,0,0,1-56.24,56.44v21A91,91,0,0,0,273.42,196.38Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M196,252.86v21l.22,0v-21Z" transform="translate(-38.44 -38)"></path><path class="cls-5" d="M196.17,273.84l-.22,0v3l.22,0Z" transform="translate(-38.44 -38)"></path><path class="cls-5" d="M196.17,252.82v-1.51l-.22,0v1.51Z" transform="translate(-38.44 -38)"></path></g></svg>
					
						</a>
						</div>
					<!--middle-->
					<div class="middle">
						<!--sub-head-->
						<div class="sub-head">
							<!--drop-list-->
							<div class="drop-list">
								<div class="drop-list__btn"><?php echo $text_retranslate_1; ?>
									<svg width="14" height="7" viewBox="0 0 14 7" fill="none"
									xmlns="http://www.w3.org/2000/svg">
										<path d="M1 1L7 6L13 1" stroke="#FFC34F" stroke-width="2" stroke-linejoin="round"/>
									</svg>
									<ul class="drop-list__menu">
										<li><a href="<?php echo $href_payment; ?>"><?php echo $text_retranslate_2; ?></a></li>
										<li><a href="<?php echo $href_delivery; ?>"><?php echo $text_retranslate_3; ?></a></li>
										<li><a href="<?php echo $href_track; ?>"><?php echo $text_retranslate_4; ?></a></li>
										<li><a href="<?php echo $href_about; ?>"><?php echo $text_retranslate_5; ?></a></li>
										<li><a href="<?php echo $href_faq; ?>"><?php echo $text_retranslate_6; ?></a></li>
										<li><a href="<?php echo $href_return; ?>"><?php echo $text_retranslate_47; ?></a></li>
										<li><a href="<?php echo $feedback; ?>"><?php echo $text_feedback; ?></a></li>
									</ul>
								</div>
								
							</div>
							<?php if ($language_switcher) { ?>
								<div class="lang-switch">
									<ul>
										<?php foreach ($language_switcher as $switch) { ?>
											<li class="<?php echo $switch['code']; ?><?php if ($switch['active']) { ?> active<?php } ?>">
												<?php if ($switch['active']) { ?>
													<?php echo $switch['text_code']; ?>
													<?php } else { ?>
													<a href="<?php echo $switch['href']; ?>" class="lang-switch-button" data-language="<?php echo $switch['code']; ?>" data-redirect="<?php echo $switch['href']; ?>">
														<?php echo $switch['text_code']; ?>														
													</a>
												<?php } ?>
											</li>
										<?php } ?>
									</ul>
								</div>
							<?php } ?>
							<!--/drop-list-->
							<?php if(!IS_MOBILE_SESSION) { ?>
								<span class="ajax-module-reloadable" data-modpath="common/header/customercity" data-reloadable-group="customer"></span>
							<?php } ?>
						</div>
						<!--/sub-head-->

						<?php include($this->checkTemplate(dirname( __FILE__), '/../search/elastic.tpl')); ?>
						
					</div>
					<!--/middle-->
					<!--icons-link-->
					<ul class="icons-link">
						<?php if(!IS_MOBILE_SESSION) { ?>
							<li class="ajax-module-reloadable" data-modpath="common/header/wishlistblock" data-reloadable-group="customer">
								<a href="<?php echo $wishlist; ?>" class="favorite">
									<svg width="40" height="35" viewBox="0 0 40 35" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M35.2241 4.74063C34.3444 3.87177 33.3 3.18253 32.1505 2.71228C31.001 2.24204 29.7689 2 28.5246 2C27.2803 2 26.0482 2.24204 24.8987 2.71228C23.7492 3.18253 22.7047 3.87177 21.8251 4.74063L19.9995 6.54297L18.174 4.74063C16.3972 2.98641 13.9873 2.00091 11.4745 2.00091C8.9617 2.00091 6.55183 2.98641 4.77502 4.74063C2.9982 6.49484 2 8.87405 2 11.3549C2 13.8357 2.9982 16.2149 4.77502 17.9691L6.60058 19.7715L19.9995 33L33.3985 19.7715L35.2241 17.9691C36.1041 17.1007 36.8022 16.0696 37.2785 14.9347C37.7548 13.7998 38 12.5833 38 11.3549C38 10.1264 37.7548 8.90999 37.2785 7.7751C36.8022 6.6402 36.1041 5.60908 35.2241 4.74063V4.74063Z"
										stroke="#51A881" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</a>
							</li>
						<?php } ?>
							<li id="header-small-cart" class="ajax-module-reloadable" data-modpath="module/cart" data-reloadable-group="customer">
								<button id="cart-btn" class="cart" onClick="openCart();">
									<svg width="40" height="36" viewBox="0 0 40 36" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M12.8876 24.0003H12.8894C12.891 24.0003 12.8925 24 12.894 24H34.1407C34.6638 24 35.1237 23.6447 35.2674 23.1297L39.9549 6.32969C40.0559 5.9675 39.9851 5.57813 39.7639 5.2775C39.5423 4.97688 39.1959 4.8 38.8282 4.8H10.185L9.34725 0.939688C9.22792 0.390625 8.75246 0 8.20314 0H1.17188C0.524598 0 0 0.537188 0 1.2C0 1.86281 0.524598 2.4 1.17188 2.4H7.2632C7.41151 3.08406 11.272 20.8734 11.4942 21.8969C10.2487 22.4513 9.37502 23.7228 9.37502 25.2C9.37502 27.185 10.9522 28.8 12.8906 28.8H34.1407C34.788 28.8 35.3126 28.2628 35.3126 27.6C35.3126 26.9372 34.788 26.4 34.1407 26.4H12.8906C12.2446 26.4 11.7188 25.8616 11.7188 25.2C11.7188 24.5394 12.2428 24.0019 12.8876 24.0003ZM37.2745 7.2L33.2566 21.6H13.8306L10.7056 7.2H37.2745Z" fill="#51A881"/>
										<path d="M11.7188 32.4C11.7188 34.385 13.2959 36 15.2344 36C17.1729 36 18.75 34.385 18.75 32.4C18.75 30.415 17.1729 28.8 15.2344 28.8C13.2959 28.8 11.7188 30.415 11.7188 32.4ZM15.2344 31.2C15.8805 31.2 16.4063 31.7384 16.4063 32.4C16.4063 33.0616 15.8805 33.6 15.2344 33.6C14.5883 33.6 14.0625 33.0616 14.0625 32.4C14.0625 31.7384 14.5883 31.2 15.2344 31.2Z" fill="#51A881"/>
										<path d="M28.2813 32.4C28.2813 34.385 29.8584 36 31.7969 36C33.7354 36 35.3125 34.385 35.3125 32.4C35.3125 30.415 33.7354 28.8 31.7969 28.8C29.8584 28.8 28.2813 30.415 28.2813 32.4ZM31.7969 31.2C32.443 31.2 32.9688 31.7384 32.9688 32.4C32.9688 33.0616 32.443 33.6 31.7969 33.6C31.1509 33.6 30.625 33.0616 30.625 32.4C30.625 31.7384 31.1509 31.2 31.7969 31.2Z" fill="#51A881"/>
									</svg>
								</button>
							</li>
						<?php if(!IS_MOBILE_SESSION) { ?>
								<li id="account_header" class="1 ajax-module-reloadable" data-modpath="common/header/customermenu" data-reloadable-group="customer">
									<button class="profile">
										<svg width="38" height="38" viewBox="0 0 38 38" fill="none"
										xmlns="http://www.w3.org/2000/svg">
											<mask id="path-1-outside-1" maskUnits="userSpaceOnUse" x="0" y="0" width="38"
											height="38" fill="black">
												<rect fill="white" width="38" height="38"/>
												<path d="M37 19C37 9.07513 28.9249 1 19 1C9.07513 1 1 9.07513 1 19C1 24.2423 3.25425 28.9674 6.84247 32.2598L6.82545 32.2748L7.40931 32.7671C7.44727 32.7991 7.48851 32.8253 7.52647 32.8567C7.83673 33.114 8.15811 33.3581 8.48538 33.5944C8.59142 33.671 8.69745 33.7476 8.80545 33.8222C9.15498 34.0631 9.51367 34.2915 9.88022 34.5081C9.96007 34.5553 10.0406 34.6011 10.1211 34.6469C10.5223 34.8753 10.9321 35.0907 11.3516 35.2884C11.3824 35.3028 11.4138 35.3159 11.4446 35.3303C12.8119 35.9658 14.2716 36.4325 15.798 36.7081C15.8379 36.7153 15.8778 36.7225 15.9184 36.7297C16.3923 36.8115 16.8714 36.8769 17.3564 36.9208C17.4153 36.926 17.4743 36.9293 17.5338 36.9345C18.0169 36.9745 18.5052 37 19 37C19.4903 37 19.974 36.9745 20.4544 36.9359C20.5153 36.9306 20.5761 36.9273 20.637 36.9221C21.1181 36.8783 21.5933 36.8148 22.0626 36.7343C22.1032 36.7271 22.1444 36.7199 22.185 36.712C23.6885 36.4423 25.1272 35.9861 26.4769 35.3669C26.5266 35.344 26.577 35.3224 26.6268 35.2988C27.0306 35.109 27.4253 34.9041 27.8121 34.6862C27.9084 34.6319 28.0039 34.5769 28.0995 34.5206C28.4516 34.3131 28.7979 34.0964 29.1343 33.866C29.2554 33.7836 29.3739 33.6965 29.4937 33.6101C29.781 33.4033 30.0631 33.1899 30.3374 32.9667C30.3983 32.9176 30.4637 32.8751 30.5233 32.8247L31.1222 32.3246L31.1045 32.3095C34.7241 29.0159 37 24.2691 37 19ZM2.30909 19C2.30909 9.79644 9.79644 2.30909 19 2.30909C28.2036 2.30909 35.6909 9.79644 35.6909 19C35.6909 23.9595 33.5145 28.4183 30.069 31.4776C29.8766 31.3447 29.6828 31.2256 29.4845 31.1261L23.9425 28.3554C23.445 28.1067 23.1361 27.6066 23.1361 27.0509V25.1154C23.2644 24.957 23.3999 24.7777 23.5399 24.5807C24.2573 23.5674 24.8327 22.4403 25.2522 21.2274C26.0815 20.8334 26.6169 20.0073 26.6169 19.0746V16.7543C26.6169 16.1868 26.4088 15.6363 26.0364 15.2036V12.1489C26.0704 11.8092 26.1908 9.892 24.8039 8.31062C23.5975 6.93345 21.645 6.23636 19 6.23636C16.355 6.23636 14.4025 6.93345 13.1961 8.30996C11.8092 9.89135 11.9296 11.8085 11.9636 12.1482V15.203C11.5919 15.6356 11.3831 16.1861 11.3831 16.7536V19.074C11.3831 19.7946 11.7064 20.4668 12.2601 20.9204C12.7903 22.9973 13.8815 24.5695 14.2847 25.1023V26.9966C14.2847 27.5307 13.9934 28.0216 13.5241 28.2782L8.34858 31.1012C8.18364 31.1909 8.02 31.2956 7.85636 31.4128C4.45338 28.3548 2.30909 23.9235 2.30909 19ZM28.7913 32.5046C28.5623 32.6708 28.3292 32.8319 28.0929 32.9857C27.9843 33.0564 27.8763 33.1271 27.7657 33.1958C27.4567 33.3869 27.1425 33.5689 26.8218 33.7391C26.7511 33.7764 26.6798 33.8117 26.6084 33.8484C25.8714 34.226 25.1089 34.5507 24.3254 34.8145C24.2979 34.8236 24.2704 34.8335 24.2423 34.8426C23.8319 34.9788 23.4162 35.0999 22.996 35.2039C22.9947 35.2039 22.9934 35.2046 22.9921 35.2046C22.5679 35.3093 22.1385 35.3964 21.7065 35.4677C21.6948 35.4697 21.683 35.4723 21.6712 35.4743C21.2647 35.5404 20.855 35.5881 20.4439 35.6241C20.3713 35.6307 20.2986 35.6353 20.2253 35.6405C19.8188 35.6713 19.4104 35.6909 19 35.6909C18.585 35.6909 18.1713 35.6706 17.7596 35.6399C17.6883 35.6346 17.6169 35.63 17.5463 35.6235C17.1313 35.5868 16.7183 35.5377 16.3092 35.471C16.2908 35.4677 16.2725 35.4644 16.2542 35.4612C15.3889 35.3165 14.5373 35.1038 13.708 34.8256C13.6825 34.8171 13.6563 34.8079 13.6308 34.7994C13.2191 34.6593 12.8119 34.5042 12.412 34.3327C12.4094 34.3314 12.4061 34.3301 12.4035 34.3288C12.0252 34.1658 11.654 33.9852 11.2875 33.7947C11.2397 33.7698 11.1913 33.7463 11.1441 33.7207C10.8097 33.542 10.4824 33.3489 10.1597 33.1473C10.0641 33.0871 9.96924 33.0263 9.87498 32.9647C9.57782 32.7703 9.28458 32.5681 8.9992 32.354C8.96975 32.3318 8.9416 32.3082 8.91215 32.286C8.93309 32.2742 8.95404 32.2624 8.97498 32.2506L14.1505 29.4276C15.0407 28.9419 15.5937 28.0105 15.5937 26.9966L15.5931 24.6389L15.4425 24.4569C15.4281 24.4406 14.013 22.7191 13.4783 20.3883L13.4187 20.1291L13.1955 19.9844C12.8807 19.7809 12.6921 19.4405 12.6921 19.0733V16.7529C12.6921 16.4486 12.8211 16.1652 13.0567 15.9524L13.2727 15.7574V12.1116L13.2668 12.0258C13.2649 12.0101 13.0718 10.4359 14.1806 9.172C15.1271 8.09331 16.749 7.54545 19 7.54545C21.2425 7.54545 22.8592 8.08873 23.8083 9.15956C24.9158 10.4104 24.7345 12.014 24.7332 12.0271L24.7273 15.7587L24.9433 15.9537C25.1783 16.1658 25.3079 16.4499 25.3079 16.7543V19.0746C25.3079 19.5413 24.9904 19.9648 24.5348 20.1055L24.2095 20.2057L24.1048 20.5297C23.7186 21.7295 23.1688 22.8376 22.4711 23.8233C22.2996 24.0655 22.1327 24.2802 21.9893 24.4445L21.827 24.6297V27.0509C21.827 28.106 22.4135 29.0551 23.3573 29.5264L28.8993 32.2971C28.9347 32.3148 28.9694 32.3331 29.0041 32.3514C28.934 32.4044 28.862 32.4535 28.7913 32.5046Z"/>
											</mask>
											<path d="M37 19C37 9.07513 28.9249 1 19 1C9.07513 1 1 9.07513 1 19C1 24.2423 3.25425 28.9674 6.84247 32.2598L6.82545 32.2748L7.40931 32.7671C7.44727 32.7991 7.48851 32.8253 7.52647 32.8567C7.83673 33.114 8.15811 33.3581 8.48538 33.5944C8.59142 33.671 8.69745 33.7476 8.80545 33.8222C9.15498 34.0631 9.51367 34.2915 9.88022 34.5081C9.96007 34.5553 10.0406 34.6011 10.1211 34.6469C10.5223 34.8753 10.9321 35.0907 11.3516 35.2884C11.3824 35.3028 11.4138 35.3159 11.4446 35.3303C12.8119 35.9658 14.2716 36.4325 15.798 36.7081C15.8379 36.7153 15.8778 36.7225 15.9184 36.7297C16.3923 36.8115 16.8714 36.8769 17.3564 36.9208C17.4153 36.926 17.4743 36.9293 17.5338 36.9345C18.0169 36.9745 18.5052 37 19 37C19.4903 37 19.974 36.9745 20.4544 36.9359C20.5153 36.9306 20.5761 36.9273 20.637 36.9221C21.1181 36.8783 21.5933 36.8148 22.0626 36.7343C22.1032 36.7271 22.1444 36.7199 22.185 36.712C23.6885 36.4423 25.1272 35.9861 26.4769 35.3669C26.5266 35.344 26.577 35.3224 26.6268 35.2988C27.0306 35.109 27.4253 34.9041 27.8121 34.6862C27.9084 34.6319 28.0039 34.5769 28.0995 34.5206C28.4516 34.3131 28.7979 34.0964 29.1343 33.866C29.2554 33.7836 29.3739 33.6965 29.4937 33.6101C29.781 33.4033 30.0631 33.1899 30.3374 32.9667C30.3983 32.9176 30.4637 32.8751 30.5233 32.8247L31.1222 32.3246L31.1045 32.3095C34.7241 29.0159 37 24.2691 37 19ZM2.30909 19C2.30909 9.79644 9.79644 2.30909 19 2.30909C28.2036 2.30909 35.6909 9.79644 35.6909 19C35.6909 23.9595 33.5145 28.4183 30.069 31.4776C29.8766 31.3447 29.6828 31.2256 29.4845 31.1261L23.9425 28.3554C23.445 28.1067 23.1361 27.6066 23.1361 27.0509V25.1154C23.2644 24.957 23.3999 24.7777 23.5399 24.5807C24.2573 23.5674 24.8327 22.4403 25.2522 21.2274C26.0815 20.8334 26.6169 20.0073 26.6169 19.0746V16.7543C26.6169 16.1868 26.4088 15.6363 26.0364 15.2036V12.1489C26.0704 11.8092 26.1908 9.892 24.8039 8.31062C23.5975 6.93345 21.645 6.23636 19 6.23636C16.355 6.23636 14.4025 6.93345 13.1961 8.30996C11.8092 9.89135 11.9296 11.8085 11.9636 12.1482V15.203C11.5919 15.6356 11.3831 16.1861 11.3831 16.7536V19.074C11.3831 19.7946 11.7064 20.4668 12.2601 20.9204C12.7903 22.9973 13.8815 24.5695 14.2847 25.1023V26.9966C14.2847 27.5307 13.9934 28.0216 13.5241 28.2782L8.34858 31.1012C8.18364 31.1909 8.02 31.2956 7.85636 31.4128C4.45338 28.3548 2.30909 23.9235 2.30909 19ZM28.7913 32.5046C28.5623 32.6708 28.3292 32.8319 28.0929 32.9857C27.9843 33.0564 27.8763 33.1271 27.7657 33.1958C27.4567 33.3869 27.1425 33.5689 26.8218 33.7391C26.7511 33.7764 26.6798 33.8117 26.6084 33.8484C25.8714 34.226 25.1089 34.5507 24.3254 34.8145C24.2979 34.8236 24.2704 34.8335 24.2423 34.8426C23.8319 34.9788 23.4162 35.0999 22.996 35.2039C22.9947 35.2039 22.9934 35.2046 22.9921 35.2046C22.5679 35.3093 22.1385 35.3964 21.7065 35.4677C21.6948 35.4697 21.683 35.4723 21.6712 35.4743C21.2647 35.5404 20.855 35.5881 20.4439 35.6241C20.3713 35.6307 20.2986 35.6353 20.2253 35.6405C19.8188 35.6713 19.4104 35.6909 19 35.6909C18.585 35.6909 18.1713 35.6706 17.7596 35.6399C17.6883 35.6346 17.6169 35.63 17.5463 35.6235C17.1313 35.5868 16.7183 35.5377 16.3092 35.471C16.2908 35.4677 16.2725 35.4644 16.2542 35.4612C15.3889 35.3165 14.5373 35.1038 13.708 34.8256C13.6825 34.8171 13.6563 34.8079 13.6308 34.7994C13.2191 34.6593 12.8119 34.5042 12.412 34.3327C12.4094 34.3314 12.4061 34.3301 12.4035 34.3288C12.0252 34.1658 11.654 33.9852 11.2875 33.7947C11.2397 33.7698 11.1913 33.7463 11.1441 33.7207C10.8097 33.542 10.4824 33.3489 10.1597 33.1473C10.0641 33.0871 9.96924 33.0263 9.87498 32.9647C9.57782 32.7703 9.28458 32.5681 8.9992 32.354C8.96975 32.3318 8.9416 32.3082 8.91215 32.286C8.93309 32.2742 8.95404 32.2624 8.97498 32.2506L14.1505 29.4276C15.0407 28.9419 15.5937 28.0105 15.5937 26.9966L15.5931 24.6389L15.4425 24.4569C15.4281 24.4406 14.013 22.7191 13.4783 20.3883L13.4187 20.1291L13.1955 19.9844C12.8807 19.7809 12.6921 19.4405 12.6921 19.0733V16.7529C12.6921 16.4486 12.8211 16.1652 13.0567 15.9524L13.2727 15.7574V12.1116L13.2668 12.0258C13.2649 12.0101 13.0718 10.4359 14.1806 9.172C15.1271 8.09331 16.749 7.54545 19 7.54545C21.2425 7.54545 22.8592 8.08873 23.8083 9.15956C24.9158 10.4104 24.7345 12.014 24.7332 12.0271L24.7273 15.7587L24.9433 15.9537C25.1783 16.1658 25.3079 16.4499 25.3079 16.7543V19.0746C25.3079 19.5413 24.9904 19.9648 24.5348 20.1055L24.2095 20.2057L24.1048 20.5297C23.7186 21.7295 23.1688 22.8376 22.4711 23.8233C22.2996 24.0655 22.1327 24.2802 21.9893 24.4445L21.827 24.6297V27.0509C21.827 28.106 22.4135 29.0551 23.3573 29.5264L28.8993 32.2971C28.9347 32.3148 28.9694 32.3331 29.0041 32.3514C28.934 32.4044 28.862 32.4535 28.7913 32.5046Z"
											fill="#51A881"/>
											<path d="M37 19C37 9.07513 28.9249 1 19 1C9.07513 1 1 9.07513 1 19C1 24.2423 3.25425 28.9674 6.84247 32.2598L6.82545 32.2748L7.40931 32.7671C7.44727 32.7991 7.48851 32.8253 7.52647 32.8567C7.83673 33.114 8.15811 33.3581 8.48538 33.5944C8.59142 33.671 8.69745 33.7476 8.80545 33.8222C9.15498 34.0631 9.51367 34.2915 9.88022 34.5081C9.96007 34.5553 10.0406 34.6011 10.1211 34.6469C10.5223 34.8753 10.9321 35.0907 11.3516 35.2884C11.3824 35.3028 11.4138 35.3159 11.4446 35.3303C12.8119 35.9658 14.2716 36.4325 15.798 36.7081C15.8379 36.7153 15.8778 36.7225 15.9184 36.7297C16.3923 36.8115 16.8714 36.8769 17.3564 36.9208C17.4153 36.926 17.4743 36.9293 17.5338 36.9345C18.0169 36.9745 18.5052 37 19 37C19.4903 37 19.974 36.9745 20.4544 36.9359C20.5153 36.9306 20.5761 36.9273 20.637 36.9221C21.1181 36.8783 21.5933 36.8148 22.0626 36.7343C22.1032 36.7271 22.1444 36.7199 22.185 36.712C23.6885 36.4423 25.1272 35.9861 26.4769 35.3669C26.5266 35.344 26.577 35.3224 26.6268 35.2988C27.0306 35.109 27.4253 34.9041 27.8121 34.6862C27.9084 34.6319 28.0039 34.5769 28.0995 34.5206C28.4516 34.3131 28.7979 34.0964 29.1343 33.866C29.2554 33.7836 29.3739 33.6965 29.4937 33.6101C29.781 33.4033 30.0631 33.1899 30.3374 32.9667C30.3983 32.9176 30.4637 32.8751 30.5233 32.8247L31.1222 32.3246L31.1045 32.3095C34.7241 29.0159 37 24.2691 37 19ZM2.30909 19C2.30909 9.79644 9.79644 2.30909 19 2.30909C28.2036 2.30909 35.6909 9.79644 35.6909 19C35.6909 23.9595 33.5145 28.4183 30.069 31.4776C29.8766 31.3447 29.6828 31.2256 29.4845 31.1261L23.9425 28.3554C23.445 28.1067 23.1361 27.6066 23.1361 27.0509V25.1154C23.2644 24.957 23.3999 24.7777 23.5399 24.5807C24.2573 23.5674 24.8327 22.4403 25.2522 21.2274C26.0815 20.8334 26.6169 20.0073 26.6169 19.0746V16.7543C26.6169 16.1868 26.4088 15.6363 26.0364 15.2036V12.1489C26.0704 11.8092 26.1908 9.892 24.8039 8.31062C23.5975 6.93345 21.645 6.23636 19 6.23636C16.355 6.23636 14.4025 6.93345 13.1961 8.30996C11.8092 9.89135 11.9296 11.8085 11.9636 12.1482V15.203C11.5919 15.6356 11.3831 16.1861 11.3831 16.7536V19.074C11.3831 19.7946 11.7064 20.4668 12.2601 20.9204C12.7903 22.9973 13.8815 24.5695 14.2847 25.1023V26.9966C14.2847 27.5307 13.9934 28.0216 13.5241 28.2782L8.34858 31.1012C8.18364 31.1909 8.02 31.2956 7.85636 31.4128C4.45338 28.3548 2.30909 23.9235 2.30909 19ZM28.7913 32.5046C28.5623 32.6708 28.3292 32.8319 28.0929 32.9857C27.9843 33.0564 27.8763 33.1271 27.7657 33.1958C27.4567 33.3869 27.1425 33.5689 26.8218 33.7391C26.7511 33.7764 26.6798 33.8117 26.6084 33.8484C25.8714 34.226 25.1089 34.5507 24.3254 34.8145C24.2979 34.8236 24.2704 34.8335 24.2423 34.8426C23.8319 34.9788 23.4162 35.0999 22.996 35.2039C22.9947 35.2039 22.9934 35.2046 22.9921 35.2046C22.5679 35.3093 22.1385 35.3964 21.7065 35.4677C21.6948 35.4697 21.683 35.4723 21.6712 35.4743C21.2647 35.5404 20.855 35.5881 20.4439 35.6241C20.3713 35.6307 20.2986 35.6353 20.2253 35.6405C19.8188 35.6713 19.4104 35.6909 19 35.6909C18.585 35.6909 18.1713 35.6706 17.7596 35.6399C17.6883 35.6346 17.6169 35.63 17.5463 35.6235C17.1313 35.5868 16.7183 35.5377 16.3092 35.471C16.2908 35.4677 16.2725 35.4644 16.2542 35.4612C15.3889 35.3165 14.5373 35.1038 13.708 34.8256C13.6825 34.8171 13.6563 34.8079 13.6308 34.7994C13.2191 34.6593 12.8119 34.5042 12.412 34.3327C12.4094 34.3314 12.4061 34.3301 12.4035 34.3288C12.0252 34.1658 11.654 33.9852 11.2875 33.7947C11.2397 33.7698 11.1913 33.7463 11.1441 33.7207C10.8097 33.542 10.4824 33.3489 10.1597 33.1473C10.0641 33.0871 9.96924 33.0263 9.87498 32.9647C9.57782 32.7703 9.28458 32.5681 8.9992 32.354C8.96975 32.3318 8.9416 32.3082 8.91215 32.286C8.93309 32.2742 8.95404 32.2624 8.97498 32.2506L14.1505 29.4276C15.0407 28.9419 15.5937 28.0105 15.5937 26.9966L15.5931 24.6389L15.4425 24.4569C15.4281 24.4406 14.013 22.7191 13.4783 20.3883L13.4187 20.1291L13.1955 19.9844C12.8807 19.7809 12.6921 19.4405 12.6921 19.0733V16.7529C12.6921 16.4486 12.8211 16.1652 13.0567 15.9524L13.2727 15.7574V12.1116L13.2668 12.0258C13.2649 12.0101 13.0718 10.4359 14.1806 9.172C15.1271 8.09331 16.749 7.54545 19 7.54545C21.2425 7.54545 22.8592 8.08873 23.8083 9.15956C24.9158 10.4104 24.7345 12.014 24.7332 12.0271L24.7273 15.7587L24.9433 15.9537C25.1783 16.1658 25.3079 16.4499 25.3079 16.7543V19.0746C25.3079 19.5413 24.9904 19.9648 24.5348 20.1055L24.2095 20.2057L24.1048 20.5297C23.7186 21.7295 23.1688 22.8376 22.4711 23.8233C22.2996 24.0655 22.1327 24.2802 21.9893 24.4445L21.827 24.6297V27.0509C21.827 28.106 22.4135 29.0551 23.3573 29.5264L28.8993 32.2971C28.9347 32.3148 28.9694 32.3331 29.0041 32.3514C28.934 32.4044 28.862 32.4535 28.7913 32.5046Z"
											stroke="#51A881" stroke-width="0.8" mask="url(#path-1-outside-1)"/>
										</svg>
									</button>
								</li>
						<?php } ?>
					</ul>
					<!--/icons-link-->
				</div>
				<!--/head-->
			</div>
			<!--top-menu-->
			<div class="top-menu">
				<div class="wrap">
					<div class="inner">
						<div class="mob-menu__btn">
							<div class="burger">
								<span></span> <span></span> <span></span>
							</div>
							<?php echo $text_retranslate_16; ?>
						</div>
						<!--menu-horizontal-->
						<div class="menu-horizontal">
							<ul>
								<li class="catalog-li">
									<a class="catalog__btn razdel">
										<div class="burger">
											<span></span> <span></span> <span></span>
										</div>
									<span class="top_menu-horizontal"><?php echo $text_retranslate_16; ?> <i><?php echo $text_retranslate_17; ?></i></span></a>
	                                <ul id="new_mmenu" class="menu-list">
	                                	<?php if(!IS_MOBILE_SESSION) {?>
	                                		<?php echo $mmenu; ?>
										<?php } ?>
	                                    
									</ul>
								</li>
								<li>
									<? if ($this->config->get('config_store_id') == 0 || $this->config->get('config_store_id') == 2) { ?>
										<a href="<?php echo $href_stock_2_ekspress_podarki; ?>">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none"
											xmlns="http://www.w3.org/2000/svg">
												<path d="M19 11V21H3V11M11 21V6M11 6H6.5C5.83696 6 5.20107 5.73661 4.73223 5.26777C4.26339 4.79893 4 4.16304 4 3.5C4 2.83696 4.26339 2.20107 4.73223 1.73223C5.20107 1.26339 5.83696 1 6.5 1C10 1 11 6 11 6ZM11 6H15.5C16.163 6 16.7989 5.73661 17.2678 5.26777C17.7366 4.79893 18 4.16304 18 3.5C18 2.83696 17.7366 2.20107 17.2678 1.73223C16.7989 1.26339 16.163 1 15.5 1C12 1 11 6 11 6ZM1 6H21V11H1V6Z"
												stroke="#51A881" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round"/>
											</svg>
											<?php echo $text_retranslate_18; ?>
											
										</a>
									<?php } ?>
									
									<? if ($this->config->get('config_store_id') == 1) {?>
										<a href="<?php echo $href_stock_1_tovary_s_ekspress_dostavkoj; ?>">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none"
											xmlns="http://www.w3.org/2000/svg">
												<path d="M19 11V21H3V11M11 21V6M11 6H6.5C5.83696 6 5.20107 5.73661 4.73223 5.26777C4.26339 4.79893 4 4.16304 4 3.5C4 2.83696 4.26339 2.20107 4.73223 1.73223C5.20107 1.26339 5.83696 1 6.5 1C10 1 11 6 11 6ZM11 6H15.5C16.163 6 16.7989 5.73661 17.2678 5.26777C17.7366 4.79893 18 4.16304 18 3.5C18 2.83696 17.7366 2.20107 17.2678 1.73223C16.7989 1.26339 16.163 1 15.5 1C12 1 11 6 11 6ZM1 6H21V11H1V6Z"
												stroke="#51A881" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round"/>
											</svg>
											<?php echo $text_retranslate_19; ?>
										</a>
									<? } ?>
									
									<? if ($this->config->get('config_store_id') != 1 && $this->config->get('config_store_id') != 0 && $this->config->get('config_store_id') != 2) {?>
										
										<a href="<?php echo $href_stock_3_podarki; ?>">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none"
											xmlns="http://www.w3.org/2000/svg">
												<path d="M19 11V21H3V11M11 21V6M11 6H6.5C5.83696 6 5.20107 5.73661 4.73223 5.26777C4.26339 4.79893 4 4.16304 4 3.5C4 2.83696 4.26339 2.20107 4.73223 1.73223C5.20107 1.26339 5.83696 1 6.5 1C10 1 11 6 11 6ZM11 6H15.5C16.163 6 16.7989 5.73661 17.2678 5.26777C17.7366 4.79893 18 4.16304 18 3.5C18 2.83696 17.7366 2.20107 17.2678 1.73223C16.7989 1.26339 16.163 1 15.5 1C12 1 11 6 11 6ZM1 6H21V11H1V6Z"
												stroke="#51A881" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round"/>
											</svg>
											<?php echo $text_retranslate_20; ?>
										</a>
										
									<?php } ?>
								</li>
								
								<li>
									<a href="<?php echo $href_manufacturer; ?>">
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none"
										xmlns="http://www.w3.org/2000/svg">
											<path d="M8 1H1V8H8V1Z" stroke="#51A881" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round"/>
											<path d="M19 1H12V8H19V1Z" stroke="#51A881" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round"/>
											<path d="M19 12H12V19H19V12Z" stroke="#51A881" stroke-width="2"
											stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M8 12H1V19H8V12Z" stroke="#51A881" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round"/>
										</svg>
									<span class="top_menu-horizontal"><?php echo $text_retranslate_21; ?></span> </a>
								</li>
								<? /*<li>
									<a href="<?php echo $href_newyear; ?>" style="color:#51A881;">										
										
										<svg width="22" height="22" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"	 viewBox="0 0 391.9 391.9" style="enable-background:new 0 0 391.9 391.9;" xml:space="preserve"><path style="fill:#008DB3;" d="M391.9,188.449h-55.923l31.582-54.697l-12.99-7.5l-35.912,62.197h-86.755	c-1.614-7.839-5.706-14.784-11.418-19.977l43.318-75.034h71.815v-15h-63.156l27.965-48.439l-12.99-7.5l-27.965,48.439L227.89,16.234	l-12.99,7.5l35.911,62.205l-43.236,74.892c-3.781-1.301-7.831-2.017-12.048-2.017c-3.95,0-7.755,0.626-11.329,1.773l-43.1-74.653	l35.907-62.194l-12.99-7.5l-31.577,54.693l-27.963-48.434l-12.99,7.5l27.963,48.435l-63.167-0.002l-0.001,15l71.828,0.003	l43.035,74.541c-6.008,5.243-10.325,12.378-11.992,20.474H73.249L37.34,126.253l-12.99,7.5l31.58,54.697H0v15h55.925l-31.583,54.699	l12.99,7.5l35.913-62.199h85.906c1.667,8.096,5.983,15.231,11.991,20.475l-43.034,74.538H56.285v15h63.165L91.486,361.9l12.99,7.5	l27.96-48.43l31.577,54.696l12.99-7.5l-35.907-62.197l43.102-74.657c3.574,1.148,7.379,1.774,11.33,1.774	c4.217,0,8.268-0.716,12.049-2.017l43.233,74.887l-35.913,62.204l12.99,7.5l31.583-54.703l27.966,48.442l12.99-7.5l-27.961-48.433	l63.155,0.002l0.001-15l-71.816-0.003l-43.32-75.038c5.712-5.193,9.804-12.139,11.418-19.978h86.749l35.911,62.199l12.99-7.5	l-31.581-54.699H391.9V188.449z M173.39,195.95c0-12.206,9.931-22.136,22.137-22.136s22.136,9.93,22.136,22.136	c0,12.206-9.93,22.137-22.136,22.137S173.39,208.156,173.39,195.95z"/><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
										
									<span class="top_menu-horizontal"><?php echo $text_retranslate_99; ?> </span> </a>
										<span class="top_menu-horizontal"><?php echo $text_retranslate_22; ?> <b><?php echo $text_retranslate_48; ?></b></span> </a> 
								</li>*/ ?>
								<? /*<li >
									<a href="<?php echo $href_sale; ?>">
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none"
										xmlns="http://www.w3.org/2000/svg">
											<path d="M1.54604 11.7155L8.27788 18.4491C8.45228 18.6238 8.65937 18.7623 8.88733 18.8568C9.11529 18.9513 9.35964 19 9.60641 19C9.85318 19 10.0975 18.9513 10.3255 18.8568C10.5534 18.7623 10.7605 18.6238 10.9349 18.4491L19 10.3914V1H9.6111L1.54604 9.06717C1.19631 9.41909 1 9.89514 1 10.3914C1 10.8876 1.19631 11.3636 1.54604 11.7155Z"
											stroke="#51A881" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round"/>
										</svg>
									<span class="top_menu-horizontal"><?php echo $text_retranslate_22; ?> <b><?php echo $text_retranslate_48; ?></b></span> </a>
								</li>*/ ?>
								<li>
									<a href="<?php echo $href_newproducts; ?>">
										<svg width="20" height="20" viewBox="0 0 22 22" fill="none"
										xmlns="http://www.w3.org/2000/svg">
											<path d="M11 1L14.09 7.26L21 8.27L16 13.14L17.18 20.02L11 16.77L4.82 20.02L6 13.14L1 8.27L7.91 7.26L11 1Z"
											stroke="#51A881" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round"/>
										</svg>
									<span class="top_menu-horizontal"><?php echo $text_retranslate_23; ?></span> </a>
								</li>
								<li>
									<a href="<?php echo $href_actions; ?>" class="stocks">
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none"
										xmlns="http://www.w3.org/2000/svg">
											<path d="M18.75 1.25L1.25 18.75M7.25 4.125C7.25 5.85089 5.85089 7.25 4.125 7.25C2.39911 7.25 1 5.85089 1 4.125C1 2.39911 2.39911 1 4.125 1C5.85089 1 7.25 2.39911 7.25 4.125ZM19 15.875C19 17.6009 17.6009 19 15.875 19C14.1491 19 12.75 17.6009 12.75 15.875C12.75 14.1491 14.1491 12.75 15.875 12.75C17.6009 12.75 19 14.1491 19 15.875Z"
											stroke="#E16A5D" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round"/>
										</svg>
									<span class="top_menu-horizontal"><?php echo $text_retranslate_24; ?></span> </a>
									</li>
									<li>
									<a href="<?php echo $href_blog;?>">
										<svg width="22" height="23" viewBox="0 0 22 23" fill="none"
										xmlns="http://www.w3.org/2000/svg">
											<path d="M15 7.00834L1 21.0083M16 14.0083H8M19.24 11.2483C20.3658 10.1225 20.9983 8.59552 20.9983 7.00334C20.9983 5.41115 20.3658 3.88418 19.24 2.75834C18.1142 1.63249 16.5872 1 14.995 1C13.4028 1 11.8758 1.63249 10.75 2.75834L4 9.50834V18.0083H12.5L19.24 11.2483Z"
											stroke="#51A881" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round"/>
										</svg>
									<span class="top_menu-horizontal"><?php echo $text_retranslate_25; ?></span> </a>
								</li>
							</ul>
							
							<div class="search-scrol--wrap">
								<button class="search-scrol-btn">
									<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M1 15L4.38333 11.6167M2.55556 7.22222C2.55556 10.6587 5.34134 13.4444 8.77778 13.4444C12.2142 13.4444 15 10.6587 15 7.22222C15 3.78578 12.2142 1 8.77778 1C5.34134 1 2.55556 3.78578 2.55556 7.22222Z"
										stroke="#51a881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</button>
							</div>
							
						</div>
						<!--/menu-horizontal-->
						<!--social-contact-->
						<div class="social-contact">
							<ul class="social-contact__list">
								<?php if ($this->config->get('social_link_messenger_bot')) { ?>
									<li>
										<a href="<?php echo $this->config->get('social_link_messenger_bot'); ?>" class="facebook" rel="noindex nofollow" alt="Facebook" title="Facebook">
											<i class="fab fa-facebook-messenger"></i>
										</a>
									</li>
								<?php } ?>
								<?php if ($this->config->get('social_link_viber_bot')) { ?>
								<li>
									<a class="viber" href="<?php echo $this->config->get('social_link_viber_bot'); ?>" rel="noindex nofollow" alt="Viber" title="Viber">
										<svg width="17" height="18" viewBox="0 0 17 18" fill="none"
										xmlns="http://www.w3.org/2000/svg">
											<path d="M12.6904 0.909435C9.83181 0.363522 6.86322 0.363522 4.00458 0.909435C2.74018 1.18239 1.14594 2.71095 0.871073 3.91196C0.376309 6.25939 0.376309 8.66141 0.871073 11.0088C1.20092 12.2098 2.79516 13.7384 4.00458 14.0114C4.05955 14.0114 4.11453 14.066 4.11453 14.1205V17.5598C4.11453 17.7236 4.33442 17.8328 4.44437 17.669L6.09359 15.9767C6.09359 15.9767 7.41296 14.6119 7.63285 14.3935C7.63285 14.3935 7.68783 14.3389 7.7428 14.3389C9.39202 14.3935 11.0962 14.2297 12.7454 13.9568C14.0098 13.6838 15.6041 12.1553 15.8789 10.9542C16.3737 8.60682 16.3737 6.2048 15.8789 3.85737C15.5491 2.71095 13.9548 1.18239 12.6904 0.909435ZM12.7454 11.1726C12.4705 11.7185 12.1407 12.1553 11.591 12.4282C11.426 12.4828 11.2611 12.5374 11.0962 12.592C10.8763 12.5374 10.7114 12.4828 10.5465 12.4282C8.7873 11.7185 7.13809 10.7359 5.81872 9.31651C5.10406 8.49764 4.49934 7.56958 4.00458 6.58694C3.78468 6.09562 3.56479 5.65888 3.39987 5.16756C3.23495 4.73083 3.50981 4.2941 3.78468 3.96655C4.05955 3.639 4.3894 3.42064 4.77421 3.25686C5.04908 3.09309 5.32395 3.20227 5.54385 3.42064C5.98364 3.96655 6.42343 4.51247 6.75327 5.11297C6.97317 5.49511 6.91819 5.93184 6.53338 6.2048C6.42343 6.25939 6.36845 6.31398 6.25851 6.42316C6.20353 6.47776 6.09358 6.53235 6.03861 6.64153C5.92866 6.8053 5.92866 6.96908 5.98364 7.13285C6.42343 8.38845 7.24803 9.3711 8.51243 9.91701C8.73233 10.0262 8.89725 10.0808 9.17212 10.0808C9.55694 10.0262 9.72186 9.58946 9.99673 9.3711C10.2716 9.15273 10.6014 9.15273 10.9313 9.31651C11.2062 9.48028 11.481 9.69864 11.8109 9.91701C12.0857 10.1354 12.3606 10.2992 12.6355 10.5175C12.8004 10.6267 12.8554 10.8997 12.7454 11.1726ZM10.4365 7.07826C10.3266 7.07826 10.3815 7.07826 10.4365 7.07826C10.2166 7.07826 10.1616 6.96908 10.1067 6.8053C10.1067 6.69612 10.1067 6.53235 10.0517 6.42316C9.99673 6.2048 9.88678 5.98643 9.66689 5.82266C9.55694 5.76807 9.44699 5.71348 9.33704 5.65888C9.17212 5.60429 9.06217 5.60429 8.89725 5.60429C8.73233 5.5497 8.67736 5.44052 8.67736 5.27674C8.67736 5.16756 8.84228 5.05838 8.95222 5.05838C9.83181 5.11297 10.4915 5.60429 10.6014 6.64153C10.6014 6.69612 10.6014 6.8053 10.6014 6.85989C10.6014 6.96908 10.5465 7.07826 10.4365 7.07826ZM9.88678 4.67624C9.61191 4.56706 9.33704 4.45787 9.0072 4.40328C8.89725 4.40328 8.73233 4.34869 8.62238 4.34869C8.45746 4.34869 8.34751 4.23951 8.40249 4.07573C8.40249 3.91196 8.51243 3.80278 8.67736 3.85737C9.22709 3.91196 9.72186 4.02114 10.2166 4.23951C11.2062 4.73083 11.7559 5.5497 11.9208 6.64153C11.9208 6.69612 11.9208 6.75071 11.9208 6.8053C11.9208 6.91449 11.9208 7.02367 11.9208 7.18744C11.9208 7.24203 11.9208 7.29663 11.9208 7.35122C11.8658 7.56958 11.481 7.62417 11.426 7.35122C11.426 7.29663 11.3711 7.18744 11.3711 7.13285C11.3711 6.64153 11.2611 6.15021 11.0412 5.71348C10.7114 5.22215 10.3266 4.89461 9.88678 4.67624ZM12.8554 7.95172C12.6904 7.95172 12.5805 7.78795 12.5805 7.62417C12.5805 7.29663 12.5255 6.96908 12.4705 6.64153C12.2507 4.8946 10.8213 3.47523 9.11715 3.20227C8.84228 3.14768 8.56741 3.14768 8.34751 3.09309C8.18259 3.09309 7.96269 3.09309 7.90772 2.87472C7.85275 2.71095 8.01767 2.54718 8.18259 2.54718C8.23756 2.54718 8.29254 2.54718 8.29254 2.54718C10.5465 2.60177 8.40249 2.54718 8.29254 2.54718C10.6014 2.60177 12.5255 4.13033 12.9103 6.42316C12.9653 6.8053 13.0203 7.18744 13.0203 7.62417C13.1302 7.78795 13.0203 7.95172 12.8554 7.95172Z"
											fill="white"/>
										</svg>
									</a>
								</li>
								<?php } ?>
								<?php if ($this->config->get('social_link_telegram_bot')) { ?>
								<li>
									<a class="tg" href="<?php echo $this->config->get('social_link_telegram_bot'); ?>" rel="noindex nofollow" alt="Telegram" title="Telegram">
										<svg width="16" height="14" viewBox="0 0 16 14" fill="none"
										xmlns="http://www.w3.org/2000/svg">
											<path d="M1.01303 6.28056L4.4616 7.56647L5.80595 11.8918C5.8644 12.184 6.2151 12.2425 6.4489 12.0671L8.37775 10.489C8.5531 10.3136 8.84536 10.3136 9.07916 10.489L12.5277 13.0023C12.7615 13.1777 13.1122 13.0608 13.1707 12.7685L15.7425 0.493992C15.8009 0.201741 15.5087 -0.0905074 15.2164 0.0263929L1.01303 5.52071C0.662325 5.63761 0.662325 6.16366 1.01303 6.28056ZM5.6306 6.92351L12.4108 2.77355C12.5277 2.7151 12.6446 2.89045 12.5277 2.9489L6.97495 8.15097C6.7996 8.32632 6.62425 8.56012 6.62425 8.85237L6.4489 10.2552C6.4489 10.4305 6.15665 10.489 6.0982 10.2552L5.39679 7.68336C5.22144 7.39111 5.33835 7.04041 5.6306 6.92351Z"
											fill="white"/>
										</svg>
									</a>
								</li>
								<?php } ?>
							</ul>
							
						</div>
						<!--/social-contact-->
					</div>
				</div>
				<div class="tab-product-wrap js-dragscroll-wrap3 mb-scroll" hidden="">
					
				</div>
			</div>
			<!--/top-menu-->
			<!--mobile-menu-->
			<div class="mobile-menu">
				<div class="mobile-menu__list">
					
					<div class="main-menu mobile-mega-menu">
						<nav>
							<div class="mobile-menu__head">
								<a href="<?php echo $home; ?>">
									<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 706.49 296.83"><defs><style>.cls-1{fill:#4b4948;}.cls-2,.cls-7{fill:none;}.cls-2{stroke:#fff;stroke-miterlimit:10;stroke-width:0.5px;}.cls-3{fill:#57ac79;}.cls-4{fill:#e1675d;}.cls-5{fill:#fff;}.cls-6{fill:#fbc04f;}
									</style></defs><g id="Слой_1" data-name="Слой 1"><path class="cls-1" d="M289.36,175.42V38.74H314V175.42Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M353.65,175.42V57.13H331.88V38.74h68.17V57.13H378.62V175.42Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M447.58,178.05q-15,0-23.54-5.9A30.79,30.79,0,0,1,412.15,156a74.93,74.93,0,0,1-3.38-23.54V83.73q0-14,3.38-24.3A29.12,29.12,0,0,1,424,43.57Q432.56,38,447.58,38q14.18,0,22.19,4.81a26.44,26.44,0,0,1,11.39,13.75,58.61,58.61,0,0,1,3.38,20.76V88.79h-24V77a83.5,83.5,0,0,0-.6-10.46,13,13,0,0,0-3.2-7.51c-1.75-1.86-4.75-2.79-9-2.79s-7.4,1-9.36,3a14.21,14.21,0,0,0-3.8,7.93,67.86,67.86,0,0,0-.84,11.22v59.39A52.51,52.51,0,0,0,434.93,150a12.66,12.66,0,0,0,4.3,7.25c2.08,1.64,4.92,2.45,8.52,2.45q6.24,0,8.86-2.95a14.57,14.57,0,0,0,3.29-7.85,80.73,80.73,0,0,0,.68-11V125.57h24v11a66.31,66.31,0,0,1-3.21,21.52,28,28,0,0,1-11.22,14.68Q462.09,178.05,447.58,178.05Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M502.58,175.42V38.74h24.81v57.2h27.84V38.74h25V175.42h-25V113.66H527.39v61.76Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M598,175.42V38.74h57.37V56.29h-32.4V95.61h25.48V113H622.93v45.22h32.74v17.21Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M673.63,175.42V38.74H691l33.24,78v-78h20.58V175.42H728.3L694.89,93.75v81.67Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M353.64,333.14V196.46H393.8q12.31,0,20,4.56a27,27,0,0,1,11.31,13.25q3.61,8.68,3.62,21,0,13.33-4.55,21.52a27.15,27.15,0,0,1-12.74,11.89,46.38,46.38,0,0,1-19.32,3.71h-13.5v60.75Zm25-78.29h9.45q6.75,0,10.55-1.94a10.42,10.42,0,0,0,5.23-6.25,37.87,37.87,0,0,0,1.43-11.56,54.13,54.13,0,0,0-1.1-12.06,10.34,10.34,0,0,0-4.72-6.83q-3.63-2.19-11.39-2.2h-9.45Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M446.78,333.14V196.46h32.73q13.85,0,23.37,3.29A26.83,26.83,0,0,1,517.39,211q5,7.92,5,21.59a65.72,65.72,0,0,1-1.52,14.85,28.15,28.15,0,0,1-5.14,11.22,23.13,23.13,0,0,1-10,7.17l19.07,67.33h-25l-16.53-62.6H471.75v62.6Zm25-78.29h7.76q7.26,0,11.56-2.11a12.35,12.35,0,0,0,6.16-6.75,32.33,32.33,0,0,0,1.86-11.9q0-10.29-3.8-15.44t-14.43-5.14h-9.11Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M583.61,334.83q-14.52,0-23.12-5.4a29.82,29.82,0,0,1-12.32-15.53q-3.7-10.12-3.71-24V238.82q0-13.84,3.71-23.71A29.05,29.05,0,0,1,560.49,200q8.61-5.23,23.12-5.23,14.83,0,23.36,5.23a29.63,29.63,0,0,1,12.32,15.1q3.8,9.87,3.8,23.71v51.29q0,13.67-3.8,23.71A30.85,30.85,0,0,1,607,329.34Q598.45,334.83,583.61,334.83Zm0-18.4c4.16,0,7.22-.89,9.19-2.7a13.57,13.57,0,0,0,4-7.42,49.46,49.46,0,0,0,1-10.29V233.08a47.91,47.91,0,0,0-1-10.29,13,13,0,0,0-4-7.17q-3-2.62-9.19-2.62-5.91,0-9,2.62a12.83,12.83,0,0,0-4,7.17,47.91,47.91,0,0,0-1,10.29V296a53.33,53.33,0,0,0,.93,10.29,12.9,12.9,0,0,0,4,7.42C576.52,315.54,579.56,316.43,583.61,316.43Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M645.55,333.14V196.46h56.18v17.21H670.52V253.5H696v17.38H670.52v62.26Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M720.3,333.14V196.46h24.63V333.14Z" transform="translate(-38.44 -38)"></path><path class="cls-2" d="M69.39,280.22" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M196.13,175.42V38.74h25V98.81l27-60.07h23.79L245.73,99.82l28.86,75.6H249.78l-22.94-62.27-5.74,10.47v51.8Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M175,90.6V38.9h0V90.6Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M175,114.44V93.57h0v20.88Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M174.92,90.6V38.9H38.44V175.42H90.15A93.89,93.89,0,0,1,174.92,90.6Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M174.92,114.45V93.57a91,91,0,0,0-81.8,81.85H114A70.16,70.16,0,0,1,174.92,114.45Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M115.49,175.42h59.43V115.94A68.68,68.68,0,0,0,115.49,175.42Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M174.92,115.94v59.48h0V115.94Z" transform="translate(-38.44 -38)"></path><rect class="cls-4" x="136.47" y="158.38" width="0.04" height="0.04"></rect><path class="cls-4" d="M174.92,196.42v80.87A93.91,93.91,0,0,1,90.6,196.42H38.44V332.89H175V196.42Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M90.59,196.38H38.44v0H90.6Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M114.58,196.42h-21a91,91,0,0,0,81.34,77.9V253.44A70.18,70.18,0,0,1,114.58,196.42Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M114.57,196.38h-21v0h21Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M174.92,252V196.42H116.08A68.71,68.71,0,0,0,174.92,252Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M116.08,196.42h58.84v0H116.07Z" transform="translate(-38.44 -38)"></path><path class="cls-5" d="M93.58,196.42v0h-3v0Z" transform="translate(-38.44 -38)"></path><path class="cls-5" d="M116.08,196.42v0h-1.5v0Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M196.17,196.38H196v55l.22,0Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M250.91,196.38H196.17v54.93A68.71,68.71,0,0,0,250.91,196.38Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M196,276.86v56h.22V276.83Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M332.46,196.38h-56a93.93,93.93,0,0,1-80.24,80.45v56.06H332.46Z" transform="translate(-38.44 -38)"></path><path class="cls-7" d="M196,273.87v-21A71,71,0,0,1,183.5,254a69.34,69.34,0,0,1-8.58-.55v20.88q4.23.4,8.58.41A91.34,91.34,0,0,0,196,273.87Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M273.42,196.38h-21a70.21,70.21,0,0,1-56.24,56.44v21A91,91,0,0,0,273.42,196.38Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M196,252.86v21l.22,0v-21Z" transform="translate(-38.44 -38)"></path><path class="cls-5" d="M196.17,273.84l-.22,0v3l.22,0Z" transform="translate(-38.44 -38)"></path><path class="cls-5" d="M196.17,252.82v-1.51l-.22,0v1.51Z" transform="translate(-38.44 -38)"></path></g>
									</svg>
								</a>
								
								<div id="top-customer-block"></div>
								
								<?php if ($language_switcher) { ?>
									<div class="lang-switch">
										<ul>
											<?php foreach ($language_switcher as $switch) { ?>
												<li class="<?php echo $switch['code']; ?><?php if ($switch['active']) { ?> active<?php } ?>">
													<?php if ($switch['active']) { ?>
														<?php echo $switch['text_code']; ?>
														<?php } else { ?>
														<a href="<?php echo $switch['href']; ?>"><?php echo $switch['text_code']; ?></a>
													<?php } ?>
												</li>
											<?php } ?>
										</ul>
									</div>
								<?php } ?>
								<?php if(IS_MOBILE_SESSION) { ?>
									<span class="ajax-module-reloadable" data-modpath="common/header/customercity" data-reloadable-group="customer"></span>
								<?php } ?>
							</div>
							
							<ul>
								<li class="home-link">
									<a href="<?php echo $home; ?>"><?php echo $text_retranslate_27; ?></a>
								</li>
								<li class="catalog__link">
									<div class="catalog__link-mob-btn">
										<div class="burger">
											<span></span> <span></span> <span></span>
										</div>
										<span><?php echo $text_retranslate_16; ?></span>
									</div>
								</li>
								<li>
									<? if ($this->config->get('config_store_id') == 0) { ?>
										<a href="<?php echo $href_stock_2_ekspress_podarki; ?>">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none"
											xmlns="http://www.w3.org/2000/svg">
												<path d="M19 11V21H3V11M11 21V6M11 6H6.5C5.83696 6 5.20107 5.73661 4.73223 5.26777C4.26339 4.79893 4 4.16304 4 3.5C4 2.83696 4.26339 2.20107 4.73223 1.73223C5.20107 1.26339 5.83696 1 6.5 1C10 1 11 6 11 6ZM11 6H15.5C16.163 6 16.7989 5.73661 17.2678 5.26777C17.7366 4.79893 18 4.16304 18 3.5C18 2.83696 17.7366 2.20107 17.2678 1.73223C16.7989 1.26339 16.163 1 15.5 1C12 1 11 6 11 6ZM1 6H21V11H1V6Z"
												stroke="#51A881" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round"/>
											</svg>
											<?php echo $text_retranslate_18; ?>
											
										</a>
									<?php } ?>
									
									<? if ($this->config->get('config_store_id') == 1) {?>
										<a href="<?php echo $href_stock_1_tovary_s_ekspress_dostavkoj; ?>">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none"
											xmlns="http://www.w3.org/2000/svg">
												<path d="M19 11V21H3V11M11 21V6M11 6H6.5C5.83696 6 5.20107 5.73661 4.73223 5.26777C4.26339 4.79893 4 4.16304 4 3.5C4 2.83696 4.26339 2.20107 4.73223 1.73223C5.20107 1.26339 5.83696 1 6.5 1C10 1 11 6 11 6ZM11 6H15.5C16.163 6 16.7989 5.73661 17.2678 5.26777C17.7366 4.79893 18 4.16304 18 3.5C18 2.83696 17.7366 2.20107 17.2678 1.73223C16.7989 1.26339 16.163 1 15.5 1C12 1 11 6 11 6ZM1 6H21V11H1V6Z"
												stroke="#51A881" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round"/>
											</svg>
											<?php echo $text_retranslate_19; ?>
										</a>
									<? } ?>
									
									<? if ($this->config->get('config_store_id') != 1 && $this->config->get('config_store_id') != 0) {?>
										
										<a href="<?php echo $href_stock_3_podarki; ?>">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none"
											xmlns="http://www.w3.org/2000/svg">
												<path d="M19 11V21H3V11M11 21V6M11 6H6.5C5.83696 6 5.20107 5.73661 4.73223 5.26777C4.26339 4.79893 4 4.16304 4 3.5C4 2.83696 4.26339 2.20107 4.73223 1.73223C5.20107 1.26339 5.83696 1 6.5 1C10 1 11 6 11 6ZM11 6H15.5C16.163 6 16.7989 5.73661 17.2678 5.26777C17.7366 4.79893 18 4.16304 18 3.5C18 2.83696 17.7366 2.20107 17.2678 1.73223C16.7989 1.26339 16.163 1 15.5 1C12 1 11 6 11 6ZM1 6H21V11H1V6Z"
												stroke="#51A881" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round"/>
											</svg>
											<?php echo $text_retranslate_20; ?>
										</a>
										
									<?php } ?>
								</li>
								
								<li><a href="<?php echo $href_vb; ?>">
									<svg width="20" height="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 170.08 85.87"style="fill:#fff;background: #023e84;"><path d="M58,29.12a156.55,156.55,0,0,0,27,2.33,156.56,156.56,0,0,0,27-2.34l0.14,0.7A128.17,128.17,0,0,1,85,32.71,128,128,0,0,1,57.9,29.82l0.14-.7h0Z"/><path d="M123.5,33.34A182,182,0,0,1,85,37.43a181.86,181.86,0,0,1-38.46-4.09l0.14-.7A208.62,208.62,0,0,0,85,36.17a208.84,208.84,0,0,0,38.32-3.53l0.14,0.7h0Z"/><path d="M101.27,26.44A85,85,0,0,1,85,28,84.75,84.75,0,0,1,68.8,26.44l0.11-.7a129.26,129.26,0,0,0,16.12,1,129.5,129.5,0,0,0,16.13-1l0.11,0.7h0Z"/><path d="M77.83,8.74c0-4.06,2.6-6.58,5.81-6.58A5.68,5.68,0,0,1,89.35,8H89a4.66,4.66,0,0,0-4.67-4.79c-2.64,0-4.77,2-4.77,5.54V22.58H77.83V8.74h0Z"/><path d="M77.85,4.25A6.86,6.86,0,0,1,83.64,1.1c3.79,0,6.87,2.73,6.87,7.64V22.58h1.72V8.63C92.23,3.17,88.62,0,84.42,0a7.77,7.77,0,0,0-6.85,4.1l0.27,0.15h0Z"/><path d="M85.48,82.71L88.29,79v3.67h-2.8Zm-0.63.78h3.43v2.17h1.24V83.49h1.24V82.71H89.52V77.9H88.78l-3.92,5v0.56h0Z"/><path d="M97.93,81.21c-0.74-.39-2-0.7-2-1.65a1.35,1.35,0,0,1,1.59-1.26A1.27,1.27,0,0,1,99,79.58a1.75,1.75,0,0,1-1,1.64h0Zm-1.27.67c1,0.44,2.33.77,2.33,2a1.59,1.59,0,0,1-1.75,1.57,1.51,1.51,0,0,1-1.64-1.57,2.22,2.22,0,0,1,1.07-2h0Zm1.51-.58c0.77-.31,1.71-0.81,1.71-1.67,0-1.09-1.36-1.64-2.39-1.64-1.3,0-2.71.62-2.71,1.89,0,1,.69,1.46,1.6,1.88-0.92.48-1.77,1-1.77,2.07,0,1.34,1.26,1.92,2.45,1.92,1.68,0,3.09-.72,3.09-2.18,0-1.2-.85-1.79-2-2.27h0Z"/><path d="M72.71,85.66V85.43c-0.67,0-.84-0.07-0.84-0.56V78H71.65a4.45,4.45,0,0,1-2.27,1.17v0.24a1.72,1.72,0,0,1,1.15.24,1.49,1.49,0,0,1,.1.7v4.53c0,0.49-.27.56-0.92,0.56v0.23h3Z"/><path d="M81.71,78.1H79c-0.31,0-.64,0-1,0s-0.35,0-.35-0.32h-0.2L76.67,79.9h0.27l0.11-.32a0.51,0.51,0,0,1,.48-0.41h3.32l-2.44,4.89c-0.45.89-.77,1.54-0.77,1.59,0,0.23.34,0.22,0.51,0.22a0.74,0.74,0,0,0,.8-0.53l2.75-6.93V78.1h0Z"/><polygon points="1.72 70.11 75.14 70.11 75.14 70.82 1.72 70.82 1.72 70.11 1.72 70.11 1.72 70.11"/><polygon points="83.14 70.11 170.07 70.11 170.07 70.82 83.14 70.82 83.14 70.11 83.14 70.11 83.14 70.11"/><path d="M74.47,60.48c0-3.29-2.31-5.47-5.71-5.47a5.48,5.48,0,1,0,0,10.95,5.52,5.52,0,0,0,5.71-5.47h0Zm-2.43,0c0,2.69-1.12,4.89-3.28,4.89s-3.28-2.17-3.28-4.89,1.14-4.89,3.28-4.89c2.33,0,3.28,2.17,3.28,4.89h0Z"/><path d="M118.54,57.45h1.11c2.94,0,5.57.25,5.57,3.8,0,2.49-1.47,3.85-4.51,3.85-1.3,0-2.17-.22-2.17-1.47V57.45h0Zm0-7.77h0.86c3.92,0,4.84,1.57,4.84,3.47,0,2.65-2.07,3.63-4.46,3.63h-1.25v-7.1h0Zm-4.6,16c2,0,4.09.1,6.19,0.1a12.21,12.21,0,0,0,5.35-.92,4,4,0,0,0,2.5-3.74c0-2.39-1.78-4.1-4.33-4.39,2-.55,3.18-1.83,3.18-3.81a3.56,3.56,0,0,0-2-3.18,11.46,11.46,0,0,0-5-.75l-5.82,0v0.51c1.06,0.17,2-.07,2,1.16V64.14c0,1-1.06,1-2,1v0.51h0Z"/><path d="M142.45,60.48c0-3.29-2.31-5.47-5.71-5.47a5.48,5.48,0,1,0,0,10.95,5.52,5.52,0,0,0,5.71-5.47h0Zm-2.43,0c0,2.69-1.12,4.89-3.28,4.89s-3.28-2.17-3.28-4.89,1.14-4.89,3.28-4.89c2.33,0,3.28,2.17,3.28,4.89h0Z"/><path d="M0,49.56a2.53,2.53,0,0,1,1.68.62A9,9,0,0,1,3,52.86L8.44,66H9.32l5.15-13.36c0.6-1.57,1.22-3.11,3-3.11V49.05H11.86v0.51a1.81,1.81,0,0,1,1.85.75c0.24,0.44.17,1.35-.46,3.06L9.88,62.48H9.83L5.66,52a3.51,3.51,0,0,1-.25-1.49c0.17-.75,1.08-1,2-1V49.05H0v0.51H0Z"/><path d="M29.19,48.83c-1.17.34-2.62,0.82-3.86,1.11v0.29a1.86,1.86,0,0,1,1.14.36,2,2,0,0,1,.37,1.52V63.9c0,1.25-.34,1.25-1.54,1.33v0.46h5.35V65.23c-1.11-.07-1.46-0.07-1.46-1.33V48.83h0Z"/><path d="M36.82,48.83c-1.17.34-2.62,0.82-3.86,1.11v0.29a1.86,1.86,0,0,1,1.14.36,2,2,0,0,1,.37,1.52V63.9c0,1.25-.35,1.25-1.54,1.33v0.46h5.35V65.23c-1.11-.07-1.46-0.07-1.46-1.33V48.83h0Z"/><path d="M58.77,65.69V65.23c-1.35-.07-1.7-0.07-1.7-1.33V59.25c0-2,1-3,1.78-3s1,1,1.76,1a1.08,1.08,0,0,0,1.09-1.09,1.43,1.43,0,0,0-1.58-1.24A3.82,3.82,0,0,0,57,57V54.93c-1.09.39-2.36,0.89-3.48,1.23V56.4c0.93,0.41,1.22.46,1.22,1.4v6.1c0,1.25-.34,1.25-1.49,1.33v0.46h5.49Z"/><path d="M154.91,62.79A4,4,0,0,1,151.55,65c-2.85,0-3.58-2.49-3.58-4.71,0-1.93.57-4.74,3-4.74a1.82,1.82,0,0,1,2,1.68A1.14,1.14,0,0,0,154,58.48a1,1,0,0,0,1-1C155,55.92,153.06,55,151,55a5.29,5.29,0,0,0-5.52,5.58A5.15,5.15,0,0,0,150.72,66a4.82,4.82,0,0,0,4.59-3l-0.4-.12h0Z"/><path d="M162.45,65.69V65.23c-1.11-.07-1.46-0.07-1.46-1.33V57.51A3.37,3.37,0,0,1,163.78,56c1.47,0,2.26,1.21,2.26,2.81v5.1c0,1.25-.35,1.25-1.43,1.33v0.46h5.3V65.23c-1.17-.07-1.51-0.07-1.51-1.33V59.06c0-3.44-2.45-4.05-3.76-4.05A4.94,4.94,0,0,0,161,56.72V48.83c-1.14.41-2.56,0.8-3.71,1.23v0.19c0.69,0.14,1.35.36,1.35,1.11V63.9c0,1.25-.34,1.25-1.54,1.33v0.46h5.35Z"/><path d="M22.74,65.69V65.23c-1.11-.07-1.46-0.07-1.46-1.33v-9c-1.19.41-2.69,0.84-3.83,1.28V56.4a2.66,2.66,0,0,1,1,.24,1.63,1.63,0,0,1,.48,1.47V63.9c0,1.25-.34,1.25-1.54,1.33v0.46h5.39Z"/><path d="M18.6,51.75A1.37,1.37,0,1,1,20,53.11a1.37,1.37,0,0,1-1.37-1.37h0Z"/><path d="M43.61,58.37h4.94c0-1.82-1.09-2.77-2.19-2.77-1.25,0-2.29,1.07-2.74,2.77h0Zm-0.15.73a8.25,8.25,0,0,0-.09,1.24c0,2.53.89,4.71,3.7,4.71a4.33,4.33,0,0,0,3.44-2.26L51,62.91a4.92,4.92,0,0,1-4.58,3,5.24,5.24,0,0,1-5.55-5.37A5.33,5.33,0,0,1,46.28,55c2.44,0,4.48,1.38,4.63,4.09H43.46Z"/><path d="M80.85,67.9l0.85-2-4.18-9a1.69,1.69,0,0,0-1.64-1.25V55.27h5.54v0.46a1.18,1.18,0,0,0-1.26.47,1.28,1.28,0,0,0,.09.93l2.64,6.1,2.39-5.52a1.86,1.86,0,0,0,.15-1.52,1.68,1.68,0,0,0-1.48-.46V55.27h4.35v0.46a1.93,1.93,0,0,0-2,1.47L81.49,68.34c-0.48,1.12-1.09,2.53-2.61,2.53a1.07,1.07,0,0,1-1.09-1,1,1,0,0,1,1-1c0.29,0,.56.12,0.85,0.14a1.47,1.47,0,0,0,1.2-1.16h0Z"/><path d="M99.4,56.84a3.73,3.73,0,0,0,1.52-2.59c0.11-1.32-.59-1.78-1.37-1.78a1.44,1.44,0,0,0-1.3,1.35A4.4,4.4,0,0,0,98.93,56c0.11,0.23.28,0.54,0.48,0.89h0Zm2.15,6.84a43.59,43.59,0,0,1-3.42-5A4.64,4.64,0,0,0,96.54,62a2.62,2.62,0,0,0,2.57,2.76,3.67,3.67,0,0,0,2.45-1.13h0Zm1.9-1.39-0.23.41,0.36,0.42a3.72,3.72,0,0,0,2.85,1.49,2.07,2.07,0,0,0,1.77-.95l0.3,0.26c-0.63.91-1.68,2-3,2a4.32,4.32,0,0,1-3.31-1.5l-0.2-.21A5.07,5.07,0,0,1,98.43,66c-2.94,0-4-2-4-3.51,0-2.06,1.84-3.36,3.3-4.36-0.29-.51-0.52-0.92-0.64-1.17a6,6,0,0,1-.67-2.15,2.8,2.8,0,0,1,3.06-2.83c1.91,0,2.94,1,2.83,2.43-0.09,1.24-1.41,2.21-2.61,3.07a53.44,53.44,0,0,0,3.06,4.72l0.16-.27A6.84,6.84,0,0,0,104,58.74a0.94,0.94,0,0,0-1.16-1V57.39h4v0.39c-1.34,0-1.95,1.18-2.36,2.26a23.67,23.67,0,0,1-1.08,2.25h0Z"/></svg>
								Villeroy & Boch</a></li>
								<li><a href="<?php echo $href_wmf; ?>">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Ebene_1" x="0px" y="0px" viewBox="0 0 50 59.331" enable-background="new 0 0 50 59.331" xml:space="preserve" width="20" height="20">
										<path d="M43.051,40.357h5.155v4.125h-5.155v14.849h-8.249V34.583l4.762-8.248h9.259v8.248h-5.772V40.357L43.051,40.357z M9.525,0   l10.117,17.532l7.74-13.405l7.738,13.405L45.238,0H50L32.741,29.905l-7.739-13.404l-7.741,13.404L0,0H9.525L9.525,0z M7.334,37.452   v21.878H3.209V26.334h7.227l6.825,11.821l7.738-13.406l5.677,9.833v24.748h-8.248V37.452L14.88,50.527L7.334,37.452L7.334,37.452z"/>
									</svg>
								WMF</a></li>
								<li><a href="<?php echo $href_manufacturer; ?>"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="https://www.w3.org/2000/svg">
									<path d="M8 1H1V8H8V1Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
									<path d="M19 1H12V8H19V1Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
									<path d="M19 12H12V19H19V12Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
									<path d="M8 12H1V19H8V12Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
								</svg><?php echo $text_retranslate_28; ?></a></li>
								<li><a href="<?php echo $href_newproducts; ?>"><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="https://www.w3.org/2000/svg">
									<path d="M11 1L14.09 7.26L21 8.27L16 13.14L17.18 20.02L11 16.77L4.82 20.02L6 13.14L1 8.27L7.91 7.26L11 1Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
								</svg><?php echo $text_retranslate_23; ?></a></li>
								<li><a href="<?php echo $href_actions; ?>"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="https://www.w3.org/2000/svg">
									<path d="M18.75 1.25L1.25 18.75M7.25 4.125C7.25 5.85089 5.85089 7.25 4.125 7.25C2.39911 7.25 1 5.85089 1 4.125C1 2.39911 2.39911 1 4.125 1C5.85089 1 7.25 2.39911 7.25 4.125ZM19 15.875C19 17.6009 17.6009 19 15.875 19C14.1491 19 12.75 17.6009 12.75 15.875C12.75 14.1491 14.1491 12.75 15.875 12.75C17.6009 12.75 19 14.1491 19 15.875Z" stroke="#E16A5D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
								</svg><?php echo $text_retranslate_24; ?></a></li>
								<? /*<li><a href="<?php echo $href_sale; ?>"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="https://www.w3.org/2000/svg">
									<path d="M1.54604 11.7155L8.27788 18.4491C8.45228 18.6238 8.65937 18.7623 8.88733 18.8568C9.11529 18.9513 9.35964 19 9.60641 19C9.85318 19 10.0975 18.9513 10.3255 18.8568C10.5534 18.7623 10.7605 18.6238 10.9349 18.4491L19 10.3914V1H9.6111L1.54604 9.06717C1.19631 9.41909 1 9.89514 1 10.3914C1 10.8876 1.19631 11.3636 1.54604 11.7155Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
								</svg><?php echo $text_retranslate_22; ?>  <b style="margin-left: 3px;"> <?php echo $text_retranslate_48; ?></b></a></li>*/ ?>
								<li><a href="<?php echo $href_blog; ?>"><svg width="22" height="23" viewBox="0 0 22 23" fill="none" xmlns="https://www.w3.org/2000/svg">
									<path d="M15 7.00834L1 21.0083M16 14.0083H8M19.24 11.2483C20.3658 10.1225 20.9983 8.59552 20.9983 7.00334C20.9983 5.41115 20.3658 3.88418 19.24 2.75834C18.1142 1.63249 16.5872 1 14.995 1C13.4028 1 11.8758 1.63249 10.75 2.75834L4 9.50834V18.0083H12.5L19.24 11.2483Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
								</svg><?php echo $text_retranslate_25; ?></a></li>
							</ul>
							
							
							<ul>
								<li onclick="openCart();">
									<svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M7.4438 13.0002H7.44471C7.44548 13.0002 7.44624 13 7.447 13H18.0703C18.3319 13 18.5618 12.8223 18.6337 12.5648L20.9775 4.16485C21.028 3.98375 20.9926 3.78906 20.8819 3.63875C20.7712 3.48844 20.598 3.4 20.4141 3.4H6.09248L5.67362 1.46984C5.61396 1.19531 5.37623 1 5.10157 1H1.58594C1.2623 1 1 1.26859 1 1.6C1 1.93141 1.2623 2.2 1.58594 2.2H4.6316C4.70576 2.54203 6.636 11.4367 6.74708 11.9484C6.12437 12.2256 5.68751 12.8614 5.68751 13.6C5.68751 14.5925 6.47608 15.4 7.44532 15.4H18.0703C18.394 15.4 18.6563 15.1314 18.6563 14.8C18.6563 14.4686 18.394 14.2 18.0703 14.2H7.44532C7.12229 14.2 6.85939 13.9308 6.85939 13.6C6.85939 13.2697 7.12138 13.0009 7.4438 13.0002ZM19.6373 4.6L17.6283 11.8H7.9153L6.35279 4.6H19.6373Z" fill="#51A881"/>
										<path d="M6.85938 17.2C6.85938 18.1925 7.64796 19 8.6172 19C9.58644 19 10.375 18.1925 10.375 17.2C10.375 16.2075 9.58644 15.4 8.6172 15.4C7.64796 15.4 6.85938 16.2075 6.85938 17.2ZM8.6172 16.6C8.94023 16.6 9.20314 16.8692 9.20314 17.2C9.20314 17.5308 8.94023 17.8 8.6172 17.8C8.29417 17.8 8.03126 17.5308 8.03126 17.2C8.03126 16.8692 8.29417 16.6 8.6172 16.6Z" fill="#51A881"/>
										<path d="M15.1406 17.2C15.1406 18.1925 15.9292 19 16.8985 19C17.8677 19 18.6563 18.1925 18.6563 17.2C18.6563 16.2075 17.8677 15.4 16.8985 15.4C15.9292 15.4 15.1406 16.2075 15.1406 17.2ZM16.8985 16.6C17.2215 16.6 17.4844 16.8692 17.4844 17.2C17.4844 17.5308 17.2215 17.8 16.8985 17.8C16.5754 17.8 16.3125 17.5308 16.3125 17.2C16.3125 16.8692 16.5754 16.6 16.8985 16.6Z" fill="#51A881"/>
										<path fill-rule="evenodd" clip-rule="evenodd" d="M18.8264 12.6184C18.7311 12.9596 18.4245 13.1998 18.0704 13.1998H7.45278C7.45039 13.1999 7.44769 13.2 7.44476 13.2C7.23714 13.2007 7.05943 13.3754 7.05943 13.5998C7.05943 13.8246 7.23724 13.9998 7.44537 13.9998H18.0704C18.5089 13.9998 18.8563 14.3624 18.8563 14.7998C18.8563 15.2365 18.5099 15.5988 18.0723 15.5998C18.5488 15.9657 18.8563 16.5481 18.8563 17.1998C18.8563 18.2983 17.9826 19.1998 16.8985 19.1998C15.8144 19.1998 14.9407 18.2983 14.9407 17.1998C14.9407 16.5481 15.2482 15.9657 15.7247 15.5998H9.79102C10.2676 15.9657 10.5751 16.5481 10.5751 17.1998C10.5751 18.2983 9.70139 19.1998 8.61725 19.1998C7.5331 19.1998 6.65943 18.2983 6.65943 17.1998C6.65943 16.5481 6.96694 15.9657 7.44348 15.5998C6.36021 15.5988 5.48756 14.6976 5.48756 13.5998C5.48756 12.8403 5.90439 12.1772 6.51869 11.8387L4.47039 2.39981H1.58599C1.14745 2.39981 0.800049 2.03717 0.800049 1.5998C0.800049 1.16244 1.14745 0.799805 1.58599 0.799805H5.10162C5.47352 0.799805 5.79014 1.06381 5.86911 1.42718L6.25378 3.19981H20.4141C20.6628 3.19981 20.8952 3.31943 21.043 3.5199C21.1903 3.72011 21.237 3.97851 21.1701 4.21838L18.8264 12.6184ZM6.09253 3.39981L5.67367 1.46965C5.61401 1.19512 5.37628 0.999805 5.10162 0.999805H1.58599C1.26235 0.999805 1.00005 1.2684 1.00005 1.5998C1.00005 1.93121 1.26235 2.19981 1.58599 2.19981H4.63165L6.74713 11.9482C6.12442 12.2254 5.68756 12.8612 5.68756 13.5998C5.68756 14.5923 6.47613 15.3998 7.44537 15.3998H8.61209C8.32414 15.4007 8.05226 15.4728 7.8124 15.5998C7.24687 15.8993 6.85943 16.5041 6.85943 17.1998C6.85943 18.1923 7.64801 18.9998 8.61725 18.9998C9.58649 18.9998 10.3751 18.1923 10.3751 17.1998C10.3751 16.5041 9.98762 15.8993 9.42209 15.5998C9.18224 15.4728 8.91035 15.4007 8.62241 15.3998H16.8933C16.6054 15.4007 16.3335 15.4728 16.0937 15.5998C15.5281 15.8993 15.1407 16.5041 15.1407 17.1998C15.1407 18.1923 15.9293 18.9998 16.8985 18.9998C17.8677 18.9998 18.6563 18.1923 18.6563 17.1998C18.6563 16.5041 18.2689 15.8993 17.7033 15.5998C17.4635 15.4728 17.1916 15.4007 16.9037 15.3998H18.0704C18.394 15.3998 18.6563 15.1312 18.6563 14.7998C18.6563 14.4684 18.394 14.1998 18.0704 14.1998H7.44537C7.12234 14.1998 6.85943 13.9306 6.85943 13.5998C6.85943 13.2695 7.12143 13.0007 7.44385 13L7.44705 12.9998H18.0704C18.3319 12.9998 18.5619 12.8222 18.6337 12.5647L20.9775 4.16465C21.028 3.98356 20.9926 3.78887 20.882 3.63856C20.7712 3.48824 20.598 3.39981 20.4141 3.39981H6.09253ZM19.6373 4.59981H6.35284L7.91534 11.7998H17.6283L19.6373 4.59981ZM19.3739 4.79981H6.6009L8.0766 11.5998H17.4765L19.3739 4.79981ZM9.00318 17.1998C9.00318 16.975 8.82537 16.7998 8.61725 16.7998C8.40912 16.7998 8.23131 16.975 8.23131 17.1998C8.23131 17.4246 8.40912 17.5998 8.61725 17.5998C8.82537 17.5998 9.00318 17.4246 9.00318 17.1998ZM17.2844 17.1998C17.2844 16.975 17.1066 16.7998 16.8985 16.7998C16.6904 16.7998 16.5126 16.975 16.5126 17.1998C16.5126 17.4246 16.6904 17.5998 16.8985 17.5998C17.1066 17.5998 17.2844 17.4246 17.2844 17.1998ZM8.61725 16.5998C8.94028 16.5998 9.20319 16.869 9.20319 17.1998C9.20319 17.5306 8.94028 17.7998 8.61725 17.7998C8.29422 17.7998 8.03131 17.5306 8.03131 17.1998C8.03131 16.869 8.29422 16.5998 8.61725 16.5998ZM17.4844 17.1998C17.4844 16.869 17.2215 16.5998 16.8985 16.5998C16.5755 16.5998 16.3126 16.869 16.3126 17.1998C16.3126 17.5306 16.5755 17.7998 16.8985 17.7998C17.2215 17.7998 17.4844 17.5306 17.4844 17.1998Z" fill="#51A881"/>
									</svg><?php echo $text_retranslate_29; ?>
								</li>
								<li class="phone-mobile__menu">
									<a href="tel:<?php echo isset($phone) ? $phone : ''; ?>">
										<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M10.9104 4.05809C11.6586 4.20378 12.3462 4.56898 12.8852 5.10696C13.4242 5.64494 13.7901 6.33121 13.9361 7.07795M10.9104 1C12.4648 1.17235 13.9143 1.8671 15.0209 2.97018C16.1275 4.07326 16.8254 5.5191 17 7.0703M16.234 13.1712V15.4648C16.2349 15.6777 16.1912 15.8884 16.1057 16.0835C16.0203 16.2786 15.8949 16.4537 15.7377 16.5977C15.5805 16.7416 15.3949 16.8512 15.1928 16.9194C14.9908 16.9876 14.7766 17.013 14.5642 16.9938C12.2071 16.7382 9.94297 15.9343 7.95371 14.6467C6.10295 13.4729 4.53384 11.9068 3.35779 10.0596C2.06326 8.0651 1.25765 5.79431 1.00622 3.43118C0.987076 3.21976 1.01225 3.00669 1.08014 2.80551C1.14802 2.60434 1.25713 2.41948 1.40052 2.2627C1.54391 2.10592 1.71843 1.98066 1.91298 1.89489C2.10753 1.80912 2.31785 1.76472 2.53053 1.76452H4.82849C5.20022 1.76087 5.56061 1.89226 5.84247 2.13419C6.12433 2.37613 6.30843 2.71211 6.36046 3.0795C6.45745 3.81349 6.63732 4.53418 6.89665 5.22781C6.99971 5.50145 7.02201 5.79884 6.96092 6.08474C6.89983 6.37065 6.7579 6.63308 6.55195 6.84095L5.57915 7.81189C6.66958 9.7259 8.25739 11.3107 10.1751 12.399L11.1479 11.4281C11.3561 11.2225 11.6191 11.0809 11.9055 11.0199C12.192 10.9589 12.4899 10.9812 12.7641 11.084C13.4591 11.3429 14.1811 11.5224 14.9165 11.6192C15.2886 11.6716 15.6284 11.8587 15.8713 12.1448C16.1143 12.431 16.2433 12.7962 16.234 13.1712Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									<?php echo isset($phone) ? $phone : ''; ?></a><span>9:00 – 19:00</span></li>
									<li data-target="callback-view" class="do-popup-element callback-view">
										<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M11.6381 1.76452V6.35165M11.6381 6.35165H16.234M11.6381 6.35165L17 1M16.234 13.1712V15.4648C16.2349 15.6777 16.1912 15.8884 16.1057 16.0835C16.0203 16.2786 15.8949 16.4537 15.7377 16.5977C15.5805 16.7416 15.3949 16.8512 15.1928 16.9194C14.9908 16.9876 14.7766 17.013 14.5642 16.9938C12.2071 16.7382 9.94297 15.9343 7.95371 14.6467C6.10295 13.4729 4.53384 11.9068 3.35779 10.0596C2.06326 8.0651 1.25765 5.79431 1.00622 3.43118C0.987076 3.21976 1.01225 3.00669 1.08014 2.80551C1.14802 2.60434 1.25713 2.41948 1.40052 2.2627C1.54391 2.10592 1.71843 1.98066 1.91298 1.89489C2.10753 1.80912 2.31785 1.76472 2.53053 1.76452H4.82849C5.20022 1.76087 5.56061 1.89226 5.84247 2.13419C6.12433 2.37613 6.30843 2.71211 6.36046 3.0795C6.45745 3.81349 6.63732 4.53417 6.89665 5.2278C6.99971 5.50145 7.02201 5.79884 6.96092 6.08474C6.89983 6.37065 6.7579 6.63308 6.55195 6.84095L5.57915 7.81189C6.66958 9.7259 8.25739 11.3107 10.1751 12.399L11.1479 11.4281C11.3561 11.2225 11.6191 11.0809 11.9055 11.0199C12.192 10.9589 12.4899 10.9812 12.7641 11.084C13.4591 11.3429 14.1811 11.5224 14.9165 11.6192C15.2886 11.6716 15.6284 11.8587 15.8713 12.1448C16.1143 12.431 16.2433 12.7962 16.234 13.1712Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
										<?php echo $text_retranslate_30; ?>
									</li>
									<!-- <li class="b24-button">
										<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M1 12.3333C1 12.8343 1.19901 13.3147 1.55324 13.669C1.90748 14.0232 2.38792 14.2222 2.88889 14.2222H14.2222L18 18V2.88889C18 2.38792 17.801 1.90748 17.4468 1.55324C17.0925 1.19901 16.6121 1 16.1111 1H2.88889C2.38792 1 1.90748 1.19901 1.55324 1.55324C1.19901 1.90748 1 2.38792 1 2.88889V12.3333Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
										<?php echo $text_retranslate_31; ?>
									</li> -->
							</ul>
							
							<ul>
								<li>
									<a href="<? echo $compare; ?>"><svg width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M3.45455 16V10.1667M3.45455 6.83333V1M10 16V8.5M10 5.16667V1M16.5455 16V11.8333M16.5455 8.5V1M1 10.1667H5.90909M7.54545 5.16667H12.4545M14.0909 11.8333H19" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
									<? echo $text_compare; ?></a>
								</li>
							</ul>

							<?php if(IS_MOBILE_SESSION) { ?>
								<ul>
									<li class="account-menu__mobile ajax-module-reloadable" data-modpath="common/header/customermenu" data-x="m" data-afterload="compileCustomerMenu">
									</li>
								</ul>
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
							<?php } ?>
							
							<ul>
								<!-- <li><svg width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M17 9.18182C17 15.5455 9 21 9 21C9 21 1 15.5455 1 9.18182C1 7.01187 1.84285 4.93079 3.34315 3.3964C4.84344 1.86201 6.87827 1 9 1C11.1217 1 13.1566 1.86201 14.6569 3.3964C16.1571 4.93079 17 7.01187 17 9.18182Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M9 11.9091C10.4728 11.9091 11.6667 10.688 11.6667 9.18182C11.6667 7.67559 10.4728 6.45455 9 6.45455C7.52724 6.45455 6.33333 7.67559 6.33333 9.18182C6.33333 10.688 7.52724 11.9091 9 11.9091Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								Город</li> -->
								<li class="information-menu__mobile">
									<div>
										<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M10 19C14.9706 19 19 14.9706 19 10C19 5.02944 14.9706 1 10 1C5.02944 1 1 5.02944 1 10C1 14.9706 5.02944 19 10 19Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M10 14V10" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											<circle cx="10" cy="6" r="0.5" stroke="#51A881"/>
										</svg><?php echo $text_retranslate_1; ?><i class="fas fa-angle-right"></i>
									</div>
									<ul>
										<li><a href="<?php echo $href_payment; ?>"><?php echo $text_retranslate_2; ?></a></li>
										<li><a href="<?php echo $href_delivery; ?>"><?php echo $text_retranslate_3; ?></a></li>
										<li><a href="<?php echo $href_track; ?>"><?php echo $text_retranslate_4; ?></a></li>
										<li><a href="<?php echo $href_about; ?>"><?php echo $text_retranslate_5; ?></a></li>
										<li><a href="<?php echo $href_faq; ?>"><?php echo $text_retranslate_6; ?></a></li>
										<!-- <li><a href="#"><?php echo $text_retranslate_33; ?></a></li> -->
									</ul>
								</li>
								<li><a href="/about-kitchen-profi">
									<svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M0 7H18M0 1H18M0 13H18" stroke="#51A881" stroke-width="2" stroke-linejoin="round"/>
									</svg>
								<?php echo $text_retranslate_34; ?></a></li>
							</ul>
						</nav>
					</div>
					
				</div>
			</div>
			<!--/mobile-menu-->
		</header>
		
		<!--mobile-menu category-->
		<?php if(IS_MOBILE_SESSION) {?>
			<div id="mobile-category" class="catalog_list__block">
				<span class="catalog__link-mob-btn"><i class="fas fa-chevron-left"></i><?php echo $text_retranslate_16; ?></span>
				<div class="catalog__link_wrap">
					<?php echo $mmenu; ?>
				</div>
				
			</div>
		<?php } ?>
		<!--/mobile-menu category-->
		<!--/header-->
		