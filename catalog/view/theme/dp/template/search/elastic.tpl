<!--search-->
	

	<style>
		header .search-scrol--wrap{opacity: 0;}
		header.fixed .search-scrol--wrap{opacity: 1;width: 100%;margin: 0px 10px;position: relative;height: 100%;display: flex;align-items: center;}
		header .search-scrol--wrap .search__btn{font-size: 15px !important}
		header .search-scrol--wrap #search-wrap{transition: .1s ease-in-out;position: absolute;right: 35px;top: 0;bottom: 0;margin: auto;opacity: 0;width: 0;}
		header .search-scrol--wrap #search-wrap.open-search{width: 700px;opacity: 1}
		header .search-scrol--wrap .search .button-search{background: #97B63C;width: 0;display: none;margin: 0 -2px -2px 0;font-size: 17px;height: 46px;}
		header .search-scrol--wrap #search-wrap.open-search .button-search{width: 117px;display: block;}
		header .search-scrol--wrap .search .search__select{display: none;}
		header .menu-horizontal #search-wrap{width: 100%;max-width: 660px;margin-right: auto;}
		header .search-scrol--wrap .search-scrol-btn{width: 30px;height: 30px;display: flex;align-items: center;justify-content: center;border: 2px solid #97B63C;border-radius: 50px;margin-left: auto;background-color: transparent;position: relative;z-index: 1;}
		header .search-scrol--wrap .search-scrol-btn.open{width: 30px;height: 30px;line-height: 25px;font-size: 15px;color: #2121217d;cursor: pointer;background-image: url(/catalog/view/theme/dp/img/close-modal.svg);background-size: 16px 12px;background-repeat: no-repeat;border: 1px solid #000;text-align: center;background-position: center;opacity: .5;}
		header .search-scrol--wrap .search-scrol-btn.open svg{display: none;}
		#search_input::placeholder {color: #00000096;}
		#content-search .search_body{border:2px solid #eae9e8;padding:25px;margin-bottom:25px}
		#content-search .search_body .search_wrap{display:flex;flex-direction:row;margin-bottom:25px}
		#content-search .search_body .search_wrap .search_input_block{display:flex;flex-shrink:0;font-size:17px;line-height:60px;font-weight:500;width:50%;max-width:700px}
		#content-search .search_body .search_wrap .search_input_block input{margin-left:15px}
		#content-search .search_body .search_wrap .search_select_block{margin-left:25px;width:25%;max-width:300px}
		#content-search .search_body .search_wrap .search_select_block .SumoSelect>.CaptionCont>span{padding-left:0;padding-right:25px}
		#content-search .search_body .search_wrap .search_select_block .SumoSelect>.CaptionCont{height:60px;line-height:60px;border-bottom:1px solid #bdbdbd}
		#content-search .search_body .search_wrap .search_select_block .SumoSelect>.CaptionCont>label{display:block}
		#content-search .search_body .search_wrap .search_select_block .SumoSelect>.CaptionCont>label>i{right:0;top:calc(50% - 4px);background-image:url(../img/arrow-down__dark.svg);width:15px;height:8px;background-size:cover}
		#content-search .search_body .search_wrap .search_select_block .SumoSelect>.optWrapper{width:300px}
		.search__field .voice_search_btn,
		.search__field .clear_btn{
			bbackground:  rgba(255, 255, 255, 0.11);
		}
		@media screen and (max-width: 1400px) {
			#search-wrap .search__field input,
			#search-wrap.search{
				height: 45px
			}
		}
		.search__field input::placeholder{
			color: #888F97;
		}
		@media screen and (min-width: 1000px) {
			.search__field input{
				font-weight: 400;
				font-size: 18px;
				padding: 0 85px 0 20px !important;
			}
		}
		@media screen and (max-width:990px){
			#search-wrap .search__field input {
			    padding-right: 73px;
			    text-overflow: ellipsis;
			}
		}

		@media screen and (max-width:560px){
			.autocomplete_wrap.search_wrap .rows {
			    flex-direction: column;
			}
			.autocomplete_wrap.search_wrap .right_block{
			    border-left: 0 !important;
				padding-left: 0 !important;
			}
			#search-wrap .search__btn{
				padding: 0px 10px 0 10px;
			}
			.autocomplete_wrap.search_wrap .left_block .title{
				padding-left: 0;
			}
			.btn_search_mobile{
				border: 0;
				background: transparent;
				display: flex;
				align-items: center;
				justify-content: center;
			}
			.btn_search_mobile.open svg{
				display: none;
			}
			.logo_mb{
				margin-right: auto;
				display: flex;
			}
			.btn_search_mobile.open{
				min-width: 18px;
				width: 40px;
				height: 45px;
				display: flex;
				background-position: center;
				background-repeat: no-repeat;
				background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iMTQiIHZpZXdCb3g9IjAgMCAxNCAxNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTMgMUwxIDEzTTEgMUwxMyAxMyIgc3Ryb2tlPSIjODg4Rjk3IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPjwvc3ZnPg==);
				position: fixed;
				right: 0;
				background-color: #fff;
				z-index: 1;
			}
			.autocomplete_wrap.search_wrap{
				max-width: 100dvw !important;
			}
			header .top-search .search__field input{padding: 0 77px 0 15px;}
			header .top-search{background: #f7f7f7;}
			header.fixed .top-search .search__field input{height: 31px;padding: 0 77px 0 15px;}
			header.fixed .top-search .search {height: 35px;}
			header.fixed .top-search .search__field svg {left: 12px;top: calc(50% - 9px);}
			header.fixed .top-search{position: fixed;top: 0;left: 0;width: 100%;z-index: 5000;border-bottom: 1px solid #eae9e8;height: 56px;}
			header.fixed .top-search .head .logo{display: none;}
			header.fixed .top-search .head .middle{padding-left: 0;}
			header.fixed .top-search .search__btn{font-size: 15px;}
		}

		.left_block__history_block .clear_history_btn{
		font-size: 15px;
		font-weight: 500;
		color: #97B63C;
		background: transparent;
		transition: .15s ease-in-out;
		}
		.left_block__history_block .clear_history_btn:hover{
		text-decoration: underline;
		}
		.autocomplete_wrap.search_wrap .left_block.left_block__history_block .title,
		.left_block__history_block .history_item{
		display: flex;
		justify-content: space-between;
		align-items: center;
		}
		#search-wrap{
		position: relative;
		}
		/*.autocomplete*/
		.autocomplete_wrap.search_wrap{
		display: none;
		background: #fbfbfb;
		box-shadow: 0px 5px 13px #cecece;
		position: absolute;
		z-index: 99999;
		top: calc(100% - -10px);
		left: 0;
		width: 100%;
		padding: 20px;
		max-height: 600px;
		overflow-y: auto;
		scrollbar-width: thin;
		font-weight: 500;
		font-size: 14px;
		line-height: 17px;
		color: #696F74;
		}
		.autocomplete_wrap.search_wrap::-webkit-scrollbar {
		height: 5px;
		width: 8px;
		background: #dadada;
		}
		
		.autocomplete_wrap.search_wrap::-webkit-scrollbar-thumb {
		background: #50a781;
		-webkit-border-radius: 1ex;
		}
		.autocomplete_wrap.search_wrap .rows{
		display: flex;
		flex-wrap: wrap;
		}
		.autocomplete_wrap.search_wrap .rows .autocomplete_search{
		border-bottom: 1px solid rgba(12,6,26,.05);
		padding-bottom: 10px;
		margin-bottom: 10px;
		}
		.autocomplete_wrap.search_wrap .left_block{
		flex-basis: 40%;
		overflow: hidden;
		}
		.autocomplete_wrap.search_wrap .left_block.two_column{
		flex-basis: 100%;
		display: grid;
		grid-template-columns: 1fr 1fr;
		}
		.autocomplete_wrap.search_wrap .left_block.two_column .evinent-search-group{
		border-bottom: 0;
		}
		.autocomplete_wrap.search_wrap .left_block .evinent-search-group{
		border-bottom: 1px solid rgba(12,6,26,.05);
		display: block;
		padding: 12px 0;
		margin-bottom: 15px;
		}
		
		.autocomplete_wrap.search_wrap .right_block{
		/*flex-basis: calc(60% - 31px);*/
		flex-basis: 60%;
		border-left: 1px solid rgba(12,6,26,.05);
		padding: 0 15px;
		overflow: hidden;
		}
		
		.autocomplete_wrap.search_wrap .right_block .title,
		.autocomplete_wrap.search_wrap .left_block .title{
		display: block;
		margin-bottom: 15px;
		font-size: 16px;
		font-weight: 500;
		color: #bdbdbd;
		}
		.autocomplete_wrap span.search_results_total{
		font-size: 12px;
		font-weight: 500;
		color: #bdbdbd;
		}
		
		.autocomplete_wrap.search_wrap .left_block .title{
		padding-left: 24px;
		}
		.autocomplete_wrap.search_wrap .left_block .category_list{
		
		}
		.autocomplete_wrap.search_wrap .left_block .category_list ul{
		margin: 0;
		list-style: none;
		padding: 0;
		}
		.autocomplete_wrap.search_wrap .left_block .category_list ul li{
		
		}
		.autocomplete_wrap.search_wrap .left_block .evinent-search-group a,
		.autocomplete_wrap.search_wrap .left_block .category_list ul li a{
		cursor: pointer;
		max-height: 50px;
		padding: 5px 24px;
		text-decoration: none;
		color: #333;
		word-spacing: normal;
		white-space: normal;
		position: relative;
		display: -webkit-box;
		-webkit-box-pack: start;
		-webkit-box-align: start;
		overflow: hidden;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		text-overflow: ellipsis;
		height: auto;
		
		}
		.autocomplete_wrap.search_wrap .left_block .evinent-search-group a i,
		.autocomplete_wrap.search_wrap .left_block .category_list ul li a i{
		position: absolute;
		left: 3px;
		top: 7px;
		}
		.autocomplete_wrap.search_wrap .left_block .category_list ul li a span.name{
		display: block;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		overflow: hidden;
		text-overflow: ellipsis;
		max-height: 32px;
		}
		.autocomplete_wrap.search_wrap .left_block .category_list ul li a span:last-child{
		margin-left: 10px;
		}
		.autocomplete_wrap.search_wrap .product_list .product_item a:hover .about_product .name,
		.autocomplete_wrap.search_wrap .product_list .product_item a:hover,
		.autocomplete_wrap.search_wrap .left_block .evinent-search-group a:hover,
		.autocomplete_wrap.search_wrap .left_block .category_list ul li a:hover{
		background: #F7F4F4;
		color: #97B63C;
		}
		.autocomplete_wrap.search_wrap .product_list{
		display: flex;
		flex-direction: column;
		}
		.autocomplete_wrap.search_wrap .product_list .product_item{
		margin-bottom: 5px;
		}
		.autocomplete_wrap.search_wrap .product_list .product_item a{
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		width: 100%;
		text-decoration: none;
		margin-top: 15px;
		padding: 1px 0;
		transition: .15s ease-in-out;
		}
		.autocomplete_wrap.search_wrap .product_list .product_item a .img{
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-align: center;
		-ms-flex-align: center;
		align-items: center;
		-webkit-box-pack: center;
		-ms-flex-pack: center;
		justify-content: center;
		margin-right: 8px;
		width: 75px;
		height: 75px;
		
		}
		.autocomplete_wrap.search_wrap .product_list .product_item a .img img{
		max-width: 90%;
		max-height: 90%;
		}
		.autocomplete_wrap.search_wrap .product_list .product_item a .about_product{
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-ms-flex-direction: column;
		flex-direction: column;
		width: calc(100% - 93px);
		}
		.autocomplete_wrap.search_wrap .product_list .product_item a .about_product .name{
		display: block;
		display: -webkit-box;
		width: 100%;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		overflow: hidden;
		text-overflow: ellipsis;
		color: #000;
		font-size: 14px;
		line-height: 20px;
		margin: 5px 0 10px;
		height: auto;
		transition: .15s ease-in-out;
		}
		.autocomplete_wrap.search_wrap .product_list .product_item a .about_product .product_price .special_wrap{
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-align: center;
		-ms-flex-align: center;
		align-items: center;
		}
		.autocomplete_wrap.search_wrap .product_list .product_item a .about_product .product_price .special_wrap .new{
		margin-top: 0 !important;
		
		}
		.autocomplete_wrap.search_wrap .product_list .product_item a .about_product .product_price .new{
		font-weight: 600;
		margin-top: 4px;
		font-size: 16px;
		color: #333;
		white-space: nowrap;
		}
		.autocomplete_wrap.search_wrap .product_list .product_item a .about_product .product_price .old{
		color: #e16a5d;
		text-decoration: line-through;
		align-self: center;
		white-space: nowrap;
		margin-left: 10px;
		margin-right: 0;
		}
		.autocomplete_wrap.search_wrap .product_list .product_item a .about_product .product_price .saved{
		background: #e16a5d;
		color: #fff;
		border-radius: 5px;
		height: 18px;
		font-size: 12px;
		padding: 0 7px;
		display: flex;
		align-items: center;
		margin-left: 12px;
		}
		.by_schedule{
		margin-top: 15px;
		font-weight: 500;
		display: block;
		}
		.main-slider .swiper-slide
		@media screen and (max-width: 680px) {
			.mfilter-free-button.open{
				border-color: #4a9674 !important;
				background-color: #4a9674 !important;
			}
			header.fixed .top-search{
			z-index: 99999;
			}
			.autocomplete_wrap.search_wrap {
			padding: 10px;
			max-height: 530px;
			width: 100%;
			/* position: fixed;
			top: 71px;*/
			}
			header.fixed  .autocomplete_wrap.search_wrap {
			/*top: 56px;*/
			}
			
			.autocomplete_wrap.search_wrap .rows {
			display: flex;
			flex-direction: column;
			}
			.autocomplete_wrap.search_wrap .left_block{
			margin-bottom: 15px
			}
			.autocomplete_wrap.search_wrap .left_block.two_column{
			display: flex;
			flex-direction: column;
			}
			.autocomplete_wrap.search_wrap .left_block,
			.autocomplete_wrap.search_wrap .right_block {
			flex-basis: 100%;
			padding: 0;
			border: 0;
			}
			.autocomplete_wrap.search_wrap .right_block .title, .autocomplete_wrap.search_wrap .left_block .title {
			
			font-size: 18px;
			}
			.autocomplete_wrap.search_wrap .left_block .title {
			padding-left: 0;
			}
			.autocomplete_wrap.search_wrap .left_block .category_list,
			.autocomplete_wrap.search_wrap .left_block .evinent-search-group {
			border-bottom: 1px solid rgba(12,6,26,.05);
			display: block;
			padding: 10px 0;
			margin-bottom: 15px;
			}
			.autocomplete_wrap.search_wrap .product_list .product_item a .about_product .product_price .special_wrap{
			flex-wrap: wrap;
			}
			.autocomplete_wrap.search_wrap .product_list .product_item a .about_product .product_price .old{
			margin-right: 10px
			}
			.autocomplete_wrap.search_wrap .product_list .product_item a .about_product .product_price .saved{
			margin-left: 0
			}
		
		}
		@media screen and (max-width: 560px) {
			#ratingBadgeContainer{
				display: none !important
			}
			header .top-search{
				display: none !important;
			}
			header .top-menu .icons-link #account_header,
			header .top-menu .icons-link .ajax-module-reloadable{
				display: none;
			}
			header .top-menu .icons-link #header-small-cart{
				display: flex !important;
			}
			header .icons-link i {
			    right: -3px;
			    top: 12px;
			}
			header .menu-horizontal .catalog__btn{
				padding-right: 0
			}
			.mobile-menu__list .user_logOut{
			    flex-direction: column;
			    align-items: flex-start !important;
			}
			.mobile-menu__list .user_logOut > p{
				display: flex;
				align-items: center;
				padding: 0;
				margin-bottom: 15px
			}
			.mobile-menu__list .user_logOut > p > a{
				display: inline-block;
				padding: 0
			}
			.mobile-menu__list .user_logOut .group_login_header {
			    display: flex;
			    flex-direction: column;
			    width: 100%;
			}
		}
		@media screen and (max-height: 450px) {
		.autocomplete_wrap.search_wrap {
		padding: 10px;
		max-height: calc(100vh - 115px);
		}
		}
		/*.autocomplete End*/
		
		
		.search__field .voice_search_btn{
		background: 0 0;
		border: 0;
		position: absolute;
		right: 0;
		top: 0;
		bottom: 0;
		font-size: 18px;
		color: #888F97;
		width: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
		}
		#search-wrap .search__btn {
		    display: flex;
		    align-items: center;
		}
		
		.search__field .voice_search_btn.active{
		color: #121415;
		}
		
		#main-ajax-search.voice_input_active{
		color:#bdbdbd;
		font-style:italic;
		}
		
	</style>
	<div id="search-wrap" class="search">
		<div class="search__field">
			<input type="text" value="<?php echo $search; ?>" placeholder="<?php echo $text_retranslate_35; ?>" id="main-ajax-search" name="search"  autocomplete="off">
			<button class="clear_btn">
				<i class="fas fa-times"></i>
			</button>
		</div>
		<button class="search__btn btn btn_default" type="button" id="main-ajax-search-submit">
			<svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M15.834 16.5L10.8341 11.5M12.5007 7.33333C12.5007 10.555 9.88898 13.1667 6.66732 13.1667C3.44566 13.1667 0.833984 10.555 0.833984 7.33333C0.833984 4.11167 3.44566 1.5 6.66732 1.5C9.88898 1.5 12.5007 4.11167 12.5007 7.33333Z" stroke="#888F97" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</button>
		<div class="search_wrap autocomplete_wrap"><i class="fas fa-user-graduate"></i> <?php echo $text_retranslate_search_go; ?></div>
	</div>
	
	<? /* VOICE SEARCH */ ?>
<style type="text/css">
    @-webkit-keyframes voice-modal__preview--outer{
	0%{box-shadow:0 0 0 0 rgb(81 168 129 / 60%)}
	30%{box-shadow:0 0 0 12px rgb(81 168 129 / 60%)}
	to{box-shadow:0 0 0 0 #e25c1d}
    }
    @keyframes voice-modal__preview--outer{
	0%{box-shadow:0 0 0 0 rgb(81 168 129 / 60%)}
	30%{box-shadow:0 0 0 12px rgb(81 168 129 / 60%)}
	to{box-shadow:0 0 0 0 rgb(81 168 129 / 60%)}
    }
    #voice_modal{
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	position: fixed;
	overflow: auto;
	margin: 0!important;
	background-color: rgba(0,0,0,.5);
	display: flex;
	align-items: center;
	overflow: hidden!important;
	z-index: 3001!important;
    }
    #voice_modal .wrap-modal{
	width: 288px;
	top: 50%;
	max-height: 100vh;
	overflow-y: auto;
	overflow-x: hidden;
	position: absolute;
	left: 50%;
	transform: translate(-50%, -50%);
	background: #fff;
	border-radius: 4px;
	box-shadow: 0 1px 3px rgb(0 0 0 / 30%);
	min-height: 150px;
	text-align: center;
	transition: padding-top .5s ease-out;
    }
    #voice_modal .wrap-modal .body .content{
	padding: 88px 24px 64px;       
	transition: padding-top .5s ease-out;
	height: 288px;
    }
    #voice_modal .wrap-modal .body.error_voice .voice-modal__icon{
	-webkit-animation: none;
	animation: none;
	border: 2px solid #f30;
	background-color: transparent;
	color:  #f30;
    }
    #voice_modal .wrap-modal .body.error_voice .content{
	padding: 51px 24px;
    }
    #voice_modal .wrap-modal .body .voice-modal__error p{
	line-height: 1.5;
	color: rgba(0,0,0,.87);
	text-align: center;
	max-width: 181px;
	margin: 0 auto 10px;
    }
    #voice_modal .wrap-modal .body .voice-modal__error button{
	line-height: normal;
	font-size: 14px;
	color: #e25c1d;
	cursor: pointer;
	background: 0 0;
	margin: 0 auto;
    }
    #voice_modal .wrap-modal p{
	font-size: 14px;
	margin-bottom: 0;
    }
    
    #voice_modal .voice-modal__icon{
	width: 64px;
	height: 64px;
	margin: 0 auto 25px;
	background: #e25c1d;
	-webkit-animation: voice-modal__preview--outer 1s ease-out infinite alternate;
	animation: voice-modal__preview--outer 1s ease-out infinite alternate;
	border-radius: 32px;
	position: relative;
	display: flex;
	align-items: center;
	box-shadow: 0 0 0 0 rgb(81 168 129 / 60%);
	justify-content: center;
	color: #fff;
	font-size: 21px;
    }
    #voice_modal .close_modals{
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
</style>
<div id="voice_modal" class="overlay_modal" style="display: none;">
    <div class="wrap-modal">
        <div class="body">
            <div class="content">
                <button class="close_modals"></button>
                <div class="voice-modal__icon">
                    <i class="fas fa-microphone"></i>
				</div>
                <p class="voice-modal__say">Скажите что-нибудь</p>
                <p class="voice-modal__text-recognize"></p>
                <div class="voice-modal__error">
                    <p class="voice-modal__error-text">
                        Ничего не найдено. Произнесите текст еще раз
					</p> 
                    <button class="voice-modal__repeat">
                        Повторить
					</button>
				</div>
			</div>            
		</div>  
	</div>
</div>

<?php include($this->checkTemplate(dirname( __FILE__), '/elastic.js.tpl')); ?>


<!--/search-->