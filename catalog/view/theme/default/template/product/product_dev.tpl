<?php echo $header; ?>

<?php if (!empty($this->session->data['gac:listfrom'])) {
	$gacListFrom = $this->session->data['gac:listfrom'];
unset($this->session->data['gac:listfrom']); } 
?>

<style>
	@media screen and (max-width: 560px) {
	.pluses-item{padding:20px 0;margin-bottom:0}
	.pluses-item ul{display:grid;grid-template-columns:1fr 1fr;grid-gap:10px}
	.pluses-item ul li{padding:0;display:flex;flex-direction:row;align-items:center;margin:0}
	.pluses-item ul li img{width:50px;height:64px;min-width:64px}
	.pluses-item ul li p{font-size:13px;line-height:16px}
	.pluses-item ul li a{display:flex;flex-direction:row;align-items:center}
	.price__sale{right:20px;left:inherit}
	.price.no-special-price .price__head{padding-right:0;justify-content:center}
	.product-info__delivery{margin-bottom:0}
	.price.no-special-price,.price{padding:20px;margin-bottom:0}
	.price__head{min-height:auto}
	.price__head > div{align-self:center;margin:0 10px 0 0}
	.price__btn-group{position:fixed;bottom:0;left:0;width:100%;z-index:1;margin:0;padding:6px;display:flex;flex-direction:row;align-items:center;z-index:12;background:#fff;transition:.3s;border-top:1px solid #dedede;justify-content:space-around}
	.price__btn-group #main-add-to-cart-button{order:4;width:150px;font-size:13px;text-transform:uppercase;padding:0 10px;font-weight:600;height:38px;line-height:38px;display:flex;align-content:center;justify-content:center;align-items:center}
	.price__btn-group #main-add-to-cart-button span{height:25px;display:flex;margin-top:auto;flex-direction:row;align-items:center;justify-content:center;margin-bottom:auto;font-size:17px;font-weight:500}
	.price__btn-group #main-add-to-cart-button svg{width:21px;height:19px}
	.price__btn-group #quick-order-block{order:3;display:none}
	.price__btn-group #quick-order-block input{margin-top:0;height:38px;width:auto;font-size:13px;text-transform:uppercase;padding:0 10px;font-weight:600;line-height:38px}
	.price__btn-group .price__btn-favorite{position:relative;top:0;bottom:0;left:0;right:0;order:1}
	.price__btn-group .addToCompareBtn{position:relative;top:0;bottom:0;left:0;right:0;font-size:18px;order:2}
	.price__btn-group .price__btn-favorite svg{width:25px}
	.price__btn-group .addToCompareBtn > i{font-size:18px}
	footer{padding-bottom:38px}
	#top{bottom:60px}
	.char-list li{margin-bottom:0}
	.char-list li h2, .tabs__content.active .char-list li{display:flex;flex-direction:row}
	.char-list li:nth-child(odd){background:#f7f4f4}
	.char-list li span:nth-child(1){width:40%;background:transparent}
	.char-list li span:nth-child(2){width:60%;font-weight:500}
	.char-list li span{padding:8px 10px;font-size:13px;line-height:16px}
	.delivery_terms.position_pluses-item{margin:0}
	.delivery_terms.position_pluses-item p{margin:0;padding:10px 10px 10px 30px;line-height:15px}
	.product-info__delivery .delivery_terms.position_pluses-item p i{font-size:17px;left:6px}
	}
</style>
<style type="text/css">
	.active-coupon {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	justify-content: center;
	border: 1px dashed #c7dad2;
	padding: 15px 30px;
	background: #edf6f2;
	}
	.active-coupon .title-coupon{
	text-transform: uppercase;
	font-weight: 500;
	width: 50%;
	margin-bottom: 5px;
	}
	.active-coupon .active-coupon__price{
	font-weight: 700;
	font-size: 20px;
	line-height: 1em;
	color: #51a881;
	width: 50%;
	text-align: right;
	}
	.active-coupon .active-coupon__promocode{
	width: 50%;
	}
	.active-coupon .active-coupon__datend{
	/*margin-top: 5px;*/
	width: 50%;
	font-size: 13px;
	display: flex;
	justify-content: flex-end;
	
	}
	.active-coupon .active-coupon__datend b{
	font-weight: 500;
	margin-left: 5px;
	}
	#promo-code-txt {
	color: #50a780;
	white-space: nowrap;
	padding: 2px 9px;
	border: 1px dashed;
	font-weight: 500;
	cursor: pointer;
	}
	.btn-copy{
	background-image: url(data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDc3Ljg2NyA0NzcuODY3IiBzdHlsZT0iZmlsbDojNTFhODgxOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+ICA8Zz4gICAgPHBhdGggIGQ9Ik0zNDEuMzMzLDg1LjMzM0g1MS4yYy0yOC4yNzcsMC01MS4yLDIyLjkyMy01MS4yLDUxLjJ2MjkwLjEzM2MwLDI4LjI3NywyMi45MjMsNTEuMiw1MS4yLDUxLjJoMjkwLjEzMyAgICBjMjguMjc3LDAsNTEuMi0yMi45MjMsNTEuMi01MS4yVjEzNi41MzNDMzkyLjUzMywxMDguMjU2LDM2OS42MSw4NS4zMzMsMzQxLjMzMyw4NS4zMzN6IE0zNTguNCw0MjYuNjY3ICAgIGMwLDkuNDI2LTcuNjQxLDE3LjA2Ny0xNy4wNjcsMTcuMDY3SDUxLjJjLTkuNDI2LDAtMTcuMDY3LTcuNjQxLTE3LjA2Ny0xNy4wNjdWMTM2LjUzM2MwLTkuNDI2LDcuNjQxLTE3LjA2NywxNy4wNjctMTcuMDY3ICAgIGgyOTAuMTMzYzkuNDI2LDAsMTcuMDY3LDcuNjQxLDE3LjA2NywxNy4wNjdWNDI2LjY2N3oiLz4gIDwvZz48L2c+PGc+ICA8Zz4gICAgPHBhdGggIGQ9Ik00MjYuNjY3LDBoLTMwNy4yYy0yOC4yNzcsMC01MS4yLDIyLjkyMy01MS4yLDUxLjJjMCw5LjQyNiw3LjY0MSwxNy4wNjcsMTcuMDY3LDE3LjA2N1MxMDIuNCw2MC42MjYsMTAyLjQsNTEuMiAgICBzNy42NDEtMTcuMDY3LDE3LjA2Ny0xNy4wNjdoMzA3LjJjOS40MjYsMCwxNy4wNjcsNy42NDEsMTcuMDY3LDE3LjA2N3YzMDcuMmMwLDkuNDI2LTcuNjQxLDE3LjA2Ny0xNy4wNjcsMTcuMDY3ICAgIHMtMTcuMDY3LDcuNjQxLTE3LjA2NywxNy4wNjdzNy42NDEsMTcuMDY3LDE3LjA2NywxNy4wNjdjMjguMjc3LDAsNTEuMi0yMi45MjMsNTEuMi01MS4yVjUxLjIgICAgQzQ3Ny44NjcsMjIuOTIzLDQ1NC45NDQsMCw0MjYuNjY3LDB6Ii8+ICA8L2c+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg==);
	width: 22px;
	height: 12px;
	background-color: transparent;
	background-repeat: no-repeat;
	background-size: contain;
	background-position: center;
	display: inline-flex;
	margin-left: 5px;
	cursor: pointer;
	position: relative;
	}
	.btn-copy .tooltiptext {
	display: none;
	width: 116px;
	background-color: black;
	color: #fff;
	text-align: center;
	padding: 9px 6px;
	border-radius: 6px;
	position: absolute;
	z-index: 1;
	font-size: 12px;
	top: -50px;
	left: 0;
	}
	.markdown-reason{
	border: 2px solid #51a881;
	display: flex;
	padding: 13px 15px;
	align-items: center;
	justify-content: center;
	transition: .3s ease-in-out;
	cursor: pointer;
	}
	.markdown-reason span{
	font-size: 16px;
	font-weight: 500;
	text-transform: uppercase;
	margin-left: 10px;
	color: #51a881;		
	transition: .3s ease-in-out;	
	}
	.markdown-reason svg{
	transition: .3s ease-in-out;
	}
	.markdown-reason:hover{
	box-shadow: 0px 0px 8px #b3b3b3;
	background: #51a881;
	border-color: #ffc34f;
	}
	.markdown-reason:hover span{
	color: #ffc34f;
	}
	.markdown-reason:hover .cls-5{
	fill: #ffc34f;
	}
	.markdown-reason:hover .cls-3,
	.markdown-reason:hover .cls-4{
	fill: #51a881;
	}
	/* product !markdown */
	
	.markdown_product_blcok{
	/*display: flex;
	flex-direction: column;*/
	}
	.markdown_product_blcok .name{
	font-size: 13px;
	margin-bottom: 15px;
	text-transform: uppercase;
	}
	.markdown_product_blcok .markdown_product_block_info{
	position: relative;		
	display: inline-block;
	}
	.markdown_product_blcok .markdown_product_block_info span i{
	width: 25px;
	height: 17px;
	text-align: center;
	cursor: pointer;
	}
	.name_markdown_product_mardown_reasons{
	display: none;
	position: absolute;
	background: #ffff;
	box-shadow: 0px 0px 7px #0000004a;
	left: calc(100% + 20px);
	top: 0;
	padding: 15px;
	transition: .3s ease-in-out;
	width: 290px;
	font-size: 13px;
	line-height: 1.5;
	}
	.name_markdown_product_mardown_reasons.show{
	display: block;
	}
	.markdown_product_blcok .markdown_product_item{		
	/*display: flex;
	flex-direction: row;*/
	}	
	.markdown_product_blcok .img-markdown_product{
	border: 1px solid #51a881;
	margin-right: 15px;
	padding: 5px;
	display: flex;
	align-items: center;
	justify-content: center;
	}
	.markdown_product_blcok .img-markdown_product img{
	/* display: block; */
	}
	.markdown_product_blcok .name_markdown_product{
	
	}
	.markdown_product_blcok .name_markdown_product a{
	font-size: 16px;
	font-weight: 500;
	margin-bottom: 10px;
	display: block;
	}
	.markdown_product_blcok .name_markdown_product .price__new{
	font-size: 18px;
	}
	.markdown_product_blcok .name_markdown_product .price__old {
	font-size: 14px;
	}
	@media screen and (max-width: 1340px) {
	.markdown_product_blcok .markdown_product_block_info {
	display: block;
	}
	.name_markdown_product_mardown_reasons{
	left: unset;
	right: 0;
	top: 25px;
	}
	}
	@media screen and (max-width: 1280px) {
	.active-coupon .title-coupon{
	width: 70%;
	margin: 0;
	font-size: 14px;
	}
	.active-coupon .active-coupon__price{
	width: 30%;
	font-size: 17px;
	}
	.active-coupon .active-coupon__promocode,
	.active-coupon .active-coupon__datend{
	width: 100%;
	text-align: left;
	}
	.active-coupon .active-coupon__datend{
	justify-content: flex-start;
	}
	.active-coupon .active-coupon__promocode{
	margin: 10px 0;
	}
	}
	@media screen and (max-width: 860px) {
	.active-coupon .title-coupon,
	.active-coupon .active-coupon__price{
	width: 100%;
	text-align: left;
	}
	.active-coupon .active-coupon__datend{
	flex-wrap: wrap;
	}
	}
	@media screen and (max-width: 750px) {
	.active-coupon .title-coupon,
	.active-coupon .active-coupon__price,
	.active-coupon .active-coupon__promocode,
	.active-coupon .active-coupon__datend{
	width: 50%;
	}
	.active-coupon .active-coupon__price{
	text-align: right;
	}
	.active-coupon .active-coupon__datend{
	justify-content: flex-end;
	}
	.pluses-item {
	padding: 20px 0;
	}
	.markdown_product_blcok .img-markdown_product{
	width: 100px;
	height: 100px;
	}
	}
	@media screen and (max-width: 560px) {
	.active-coupon{
	padding: 15px 20px;
	margin-top: 5px;
	}
	.active-coupon .title-coupon{
	width: 70%;		
	}
	.active-coupon .active-coupon__price{
	width: 30%;
	}
	.active-coupon .active-coupon__promocode,
	.active-coupon .active-coupon__datend{
	width: 100%;
	text-align: left;
	}
	.active-coupon .active-coupon__datend{
	justify-content: flex-start;
	}
	}
	@media screen and (max-width: 480px) {
	.active-coupon .title-coupon,
	.active-coupon .active-coupon__price{
	width: 100%;
	text-align: left;
	}
	.markdown_product_blcok{
	margin-top: 15px;
	}
	.markdown_product_blcok .name{
	font-size: 14px;
	}
	.markdown_product_blcok .markdown_product_item{
	
	}
	.markdown_product_blcok .name_markdown_product a{
	font-size: 14px;
	line-height: 17px;
	}
	#markdown-reason-addToCart{
	display: none;
	}
	.markdown_product_blcok .img-markdown_product img{
	max-width: 50px;
	height: auto;
	}
	.markdown_product_blcok .name_markdown_product a {
	font-size: 16px;
	line-height: 18px;
	}
	.markdown-reason{
	padding: 8px 9px;
	margin-top: 10px
	}
	.markdown-reason span {
	font-size: 12px;
	line-height: 17px;
	}
	.markdown-reason svg {
	width: 20px;
	}
	.markdown-reason-content .characteristics,
	.markdown-reason-content .image-markdown-reason{
	flex-basis: 100% !important;
	width: 100% !important;
	padding: 0 !important;
	}
	.markdown-reason-content .characteristics .char-list li span:nth-child(1){
	background: inherit !important;
	}
	.name_markdown_product_mardown_reasons{
	width: 100%;
	}
	
	}
	@media screen and (max-width: 375px) {
	.markdown_product_blcok .img-markdown_product img{
	min-width: 70px;
	}
	}
	
</style>

<!-- new tab -->
<style>
	
	#new_mmenu{
	z-index: 2;
	}
	header .top-menu .tab-product-wrap #tab_header{
	display: grid !important;
	max-width: 1600px;
	margin: auto;
	padding: 0 40px;
	grid-template-columns: 36px 1fr 150px 120px;
	grid-gap: 20px;
	position: relative;
	}
	header .top-menu .tab-product-wrap{
	display: block !important;
	position: fixed;
	padding: 0 20px;
	margin: 0 auto;
	opacity: 0;
	background: #fff;
	z-index: -2999;
	box-shadow: 0 2px 3px rgba(0,0,0,.2);
	transform: translateY(-55px);
	transition: all .3s ease-in-out;
	height: 48px;
	left: 0;
	right: 0;
	top: 55px;
	width: 100%;
	}
	
	header .top-menu .tab-product-wrap.show{
	opacity: 1;
	transform: translateY(0);
	z-index: 1;
	}
	
	header .top-menu .tab-product-wrap #tab_header img{
	width: 36px;
	height:36px;
	object-fit: contain;
	}
	header .top-menu .tab-product-wrap #tab_header .name_prod{
	font-size: 13px;
	line-height: 15px;
	}
	header .top-menu .tab-product-wrap #tab_header .price_product{
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: flex-end;
	}
	header .top-menu .tab-product-wrap #tab_header .price_product .price__old_wrap{
	display: flex;
	flex-direction: row;
	align-items: center;
	}	
	header .top-menu .tab-product-wrap #tab_header .price_product .price__old_wrap .price__old{
	font-size: 11px;
	}	
	header .top-menu .tab-product-wrap #tab_header .price_product .price__old_wrap .price__saving{
	background: #e16a5d;
	border-radius: 4px;
	padding: 0 2px;
	font-size: 10px;
	line-height: 12px;
	font-weight: 700;
	color: #fff;
	height: 13px;
	margin-left: 9px;
	}
	header .top-menu .tab-product-wrap #tab_header .price_product .price__new{
	margin-right: 0 !important;
	font-weight: 500;
	font-size: 16px;
	line-height: 19px;
	}
	
	header .top-menu .tab-product-wrap #tab_header .price_product .price__sale{
	display: flex;
	flex-direction: row;
	}
	header .top-menu .tab-product-wrap #tab_header .addTo-cart-qty{
	display: flex;
	align-items: center;
	}
	header .top-menu .tab-product-wrap #tab_header .addTo-cart-qty #addTo-cart-button{
	width: 100% !important;
	margin: 0;
	font-size: 15px;
	font-weight: 400;
	display: flex;
	align-items: center;
	justify-content: center;
	height: 30px;
	}
	header .top-menu .tab-product-wrap #tab_header .tab_header ul{
	text-align: left;
	}
	header .top-menu .tab-product-wrap #tab_header .tab_header ul a li{
	padding: 0 0 2px 0;
	font-size: 12px;
	line-height: 14px;
	margin-right: 14px;
	color: #51a881;
	font-weight: 500;
	}
	header .top-menu .tab-product-wrap #tab_header .tab_header ul a li::after {
	height: 1px;
	background: #51a881;
	}
	.product__body .sticky-block {
	display: none;
	}
	@media (min-width: 1200px) {
	.tabs__content{
	/*overflow: hidden !important;*/
	}
	#product-tab-info .kit::before, .kit::after {
	display: none !important;
	}
	#product-tab-info .kit{
	margin: 36px -40px;
	padding: 25px 40px 25px;
	background:#fff;
	}
	#product-detail .item-page .tabs__caption li{
	padding-top: 0;
	}
	#product-detail{
	overflow: initial;
	}
	#collection-tab {
	padding-bottom: 0;
	}
	.product__body{
	align-items: flex-start;
	display: flex;
	justify-content: space-between;
	margin-top: 30px;
	margin-bottom: 30px;
	}
	.tabs__content .tab_reviews{
	width: calc(65% - 20px);
	}
	.product__body.description .sticky-block {
	display: none;
	}
	.product__body.description .tabs.item-page {
	width: 100%;
	}
	.product__body .tabs.item-page {
	width: calc(100% - 359px);
	}
	.product__body .sticky-block {
	display: block;
	width: 335px;
	background: #fff;
	box-shadow: 0 1px 2px rgba(0,0,0,.25);
	position: -webkit-sticky;
	position: sticky;
	top: 60px;
	padding: 24px;
	border-radius: 10px;
	transition: .3s ease-in-out;
	z-index: 3;
	}
	.product__body .sticky-block.show_tab{
	top: 107px;
	}
	.product__body .sticky-block__product {
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	flex-direction: column;
	}
	#collection-tab .catalog__content .product__item{
	flex-basis: 33%;
	}
	.product__body .sticky-block .sticky-block__image{
	display: flex;
	flex-direction: row;
	margin-bottom: 15px;
	}
	.product__body .sticky-block .sticky-block__image img{
	max-width: 100px;
	max-height: 100px;
	-o-object-fit: contain;
	object-fit: contain;
	margin-right: 14px;
	}
	
	.product__body .sticky-block .sticky-block__name{
	font-size: 15px;
	line-height: 18px;
	font-weight: 500;
	}
	.product__body .sticky-block .price_product{
	display: flex;
	position: relative;
	width: 100%;
	flex-direction: column-reverse;
	margin-bottom: 15px;
	}
	.product__body .sticky-block .price_product .price__saving{
	font-size: 13px;
	font-weight: 700;
	position: absolute;
	right: 0;
	top: 0;
	background: #e16a5d;
	color: #fff;
	padding: 1px 11px;
	margin-right: 0 !important;	
	}
	.product__body .sticky-block .addTo-cart-qty{
	display: flex;
	flex-direction: row;
	width: 100%;
	}
	.product__body .sticky-block .price__btn-buy{
	margin-right: 0;
	}
	.product__body .sticky-block .addTo-cart-qty .price__btn-favorite{
	margin-left: auto;
	}
	.product__body .sticky-block .addTo-cart-qty .addToCompareBtn{
	margin-left: 10px;
	}
	.product__body .sticky-block .addTo-cart-qty .price__btn-favorite,
	.product__body .sticky-block .addTo-cart-qty .addToCompareBtn{
	background: transparent;
	border: none;
	box-shadow: none;
	}
	
	.product__body .sticky-block .pluses-item{
	padding: inherit;
	margin: 0;
	}
	.product__body .sticky-block .pluses-item ul{
	display: flex;
	flex-direction: column;
	}
	.product__body .sticky-block .pluses-item ul li {
	width: 100%;
	}
	.product__body .sticky-block .pluses-item ul li p {
	font-size: 13px;
	line-height: 17px;
	}
	.product__body .sticky-block .pluses-item ul li img {
	width: 70px;
	height: 70px;
	min-width: 70px;
	}
	.product__body .sticky-block .product-info__delivery {
	align-items: start;
	margin-bottom: 10px;
	flex-direction: column;
	width: 100%;
	}
	.product__body .sticky-block .product-info__delivery .delivery_terms{
	width: 100% !important;
	margin: 10px 0 10px 0px;
	}
	.product__body .sticky-block .delivery_info_free{
	width: 100%;
	height: 50px;
	}
	.product__body .sticky-block .product-info__delivery .delivery_terms p{
	margin-bottom: 0;
	}
	.product__body .sticky-block .waitlist-block .btn-default {
	height: 43px;
	line-height: 43px;
	font-size: 15px;
	}
	.product__body .sticky-block .product-info__active_action{
	align-items: start;
	flex-direction: column;
	margin-top: 15px;
	}
	.product__body .sticky-block .product-info__active_action h3 {
	font-size: 14px;
	width: 100%;
	line-height: 16px;
	margin-bottom: 10px;
	}
	.product__body .sticky-block .product-info__delivery .delivery_terms p{
	width: 100%;
	}
	}
	
	@media screen and (max-width: 1279px) {
	header .top-menu .tab-product-wrap #tab_header{
	padding: 0;
	}
	}
	@media screen and (max-width: 999px) {
	.item-page .tabs__nav,
	header .top-menu .tab-product-wrap #tab_header .addTo-cart-qty,
	header .top-menu .tab-product-wrap #tab_header .price_product,
	header .top-menu .tab-product-wrap #tab_header .name_prod,
	header .top-menu .tab-product-wrap #tab_header img{
	display: none;
	}
	#product-detail .product-info{
	margin-bottom: 20px;
	}
	header .top-menu .tab-product-wrap {
	display: block !important;
	position: relative;
	opacity: 1;
	transform: translateY(0);
	z-index: 1;
	top: 0;
	width: 100%;
	height: 40px;
	overflow: hidden;
	}
	header .top-menu .tab-product-wrap #tab_header .tab_header ul a li {
	padding: 10px 0 10px 0;
	font-size: 15px;
	line-height: 20px;
	margin-right: 35px;
	color: #51a881;
	font-weight: 500;
	}
	header .top-menu .tab-product-wrap #tab_header{
	display: block !important;
	}
	}
	
</style>
<!-- new tab -->
<script type="text/javascript">
	if ($(window).width() >= '1000'){
		CloudZoom.quickStart();
	}          
</script>   

<? if (mb_strlen($mask, 'UTF-8') > 1) { ?>
	<script>
		
	    $(document).ready(function () {
			
	        $('#waitlist-phone').mask("<?php echo $mask; ?>");
		});
	</script>
<? } ?>

<?php echo $content_top; ?>

<?php if ($hb_snippets_prod_enable == '1'){ ?>
	<?php
		$desc = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", htmlentities(strip_tags($description))); 
		$desc = preg_replace('/\s{2,}/', ' ', trim($desc));
		$desc = str_replace('\\', '-', trim($desc));
	?>
	<script type="application/ld+json">
		{
			"@context": "http://schema.org/",
			"@type": "Product",
			"name": "<?php echo prepareEcommString($heading_title); ?>",
			"productID": "mpn:<?php echo trim($mpn)?trim(prepareEcommString($mpn)):trim(prepareEcommString($model)); ?>",
			"sku": "<?php echo trim($sku)?trim(prepareEcommString($sku)):trim(prepareEcommString($model)); ?>",
			"mpn": "<?php echo trim($mpn)?trim(prepareEcommString($mpn)):trim(prepareEcommString($model)); ?>"
			<?php if ($thumb) { ?>
				,"image": "<?php echo $thumb; ?>"
			<?php } ?>
			<?php if ($desc) { ?>
				,"description": "<?php echo $desc; ?>"
			<?php } ?>
			<?php if ($manufacturer) { ?>
				,"brand": {
					"@type": "Thing",
					"name": "<?php echo $manufacturer; ?>"
				}
			<?php } ?>
			<?php if (($review_status) and ($rating)) { ?>
				,"aggregateRating": {
					"@type": "AggregateRating",
					"ratingValue": "<?php echo $rating; ?>",
					"reviewCount": "<?php echo $review_count; ?>"
				}
			<?php } ?>
			<?php if ($price)  {  ?>
				,"offers":{"@type": "Offer",
					"url": "<?php echo $this_href; ?>", 
					"priceCurrency": "<?php echo $currencycode; ?>",
					"priceValidUntil": "<?php echo date('Y-m-d', strtotime('+1 year')); ?>",
					<?php if (!$special) {
						if ($language_decimal_point == ','){
							$hbprice = str_replace('.','',$price);
							$hbprice = str_replace(',','.',$hbprice);
							$hbprice = preg_replace("/[^0-9.]/", "", $hbprice);
							$hbprice = ltrim($hbprice,'.');
							$hbprice = rtrim($hbprice,'.');
							}else{
							$hbprice = $price;
							$hbprice = preg_replace("/[^0-9.]/", "", $hbprice);
							$hbprice = ltrim($hbprice,'.');
							$hbprice = rtrim($hbprice,'.');
						}
					?>
					"price": "<?php echo $hbprice; ?>"
					<?php } else {
						if ($language_decimal_point == ','){
							$hbspecial = str_replace('.','',$special);
							$hbspecial = str_replace(',','.',$hbspecial);
							$hbspecial =  preg_replace("/[^0-9.]/", "", $hbspecial);
							$hbspecial = ltrim($hbspecial,'.');
							$hbspecial = rtrim($hbspecial,'.');
							}else{
							$hbspecial = $special;
							$hbspecial =  preg_replace("/[^0-9.]/", "", $hbspecial);
							$hbspecial = ltrim($hbspecial,'.');
							$hbspecial = rtrim($hbspecial,'.');
						}
					?>
					"price": "<?php echo $hbspecial; ?>"				
					<?php } ?>					
					<?php if ($stockqty > 0) { ?>
						,"availability": "http://schema.org/InStock"
					<?php } ?>
				}
			<?php } ?>
		}
	</script>
<?php } ?>
<?php include($this->checkTemplate(dirname(FILE),'/../structured/breadcrumbs.tpl'); ?>
<!--article-->
<section id="product-detail" class="article product-detail">
	<div class="wrap">
		<!--product-info-->
		<div class="product-info">
			<div class="product-info__left-col">
				<h1 class="title"><?php echo $heading_title; ?></h1>
				<div class="product-info__left-col__wrap">
					<div class="product-info__reviews">
						<?php if ($review_status) { ?>
							<span class="rate rate-<?php echo $rating; ?>"></span>
						<?php } ?>
						<a class="reviews_btn"><?php echo $review_count; ?> 
							<?php if ($review_count == 0) { ?>
								отзывов
								<?php } elseif ($review_count == 1) { ?>
								отзыв
								<?php } elseif ($review_count == 2) { ?>
								отзыва
								<?php } elseif ($review_count == 3) { ?>
								отзыва
								<?php } elseif ($review_count == 4) { ?>
								отзыва
								<?php } elseif ($review_count > 5) { ?>
								отзывов
							<?php } ?>							
						</a>
					</div>
					<div class="product-info__code">
						<?php if ($manufacturer) { ?>
							<span>
								<?= $text_code ?>: <?php echo $product_id; ?>
							<?php } ?>
						</span> <span><?php echo $text_model; ?>: <?php echo $model; ?></span>
						
						<?php if (!empty($category_manufacturer_info)) { ?>
							<span>Категория: <a href="<?php echo $category_manufacturer_info['href']; ?>" title="<?php echo $category_manufacturer_info['text']; ?>" style="white-space: normal;"><?php echo $category_manufacturer_info['text']; ?></a></span>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="product-info__right-col">
				<?php if ($manufacturer) { ?>
					<div class="product-info__logo-brand">
						<div class="product-info__logo-brand">
							
							<? if ($show_manufacturer || true) { ?>
								<a href="<?php echo $manufacturers; ?>" title="<?php echo $manufacturer; ?>">						
									<img src="<? echo $manufacturers_img_260; ?>" alt="<?php echo $manufacturer; ?>">
								</a>
							<? } ?>
						</div>
					</div>
				<?php } ?>
				
			</div>
		</div>
		<!--/product-info-->
		<!--tabs  -->
		<main class="product__body description">
			<div class="tabs item-page">
				<!--tabs__nav-->
				<div class="tabs__nav js-dragscroll-wrap2">
					<ul class="tabs__caption js-dragscroll2">
						<li id="main_btn" class="active">Все о товаре</li>
						<?php if (isset($collection) && $collection) { ?>
							<li id="collection_btn">Коллекция <? echo $collection_name; ?></li>
						<?php } ?>
						<li id="characteristic_btn">Характеристики</li>
						<li id="photo_btn">Фото <?php if ($youtubes) { ?> и видео <?php } ?></li>
						<!-- <li>Сопутствующие товары</li> -->
						<li id="reviews_btn">Отзывы (<?php echo $review_count; ?>)</li>
					</ul>
				</div>
				<!--/tabs__nav-->
				
				<!--tabs__content-->
				<div class="tabs__content active" id="product-tab-info" style="position: relative;">
					
					<!--slider-box-->
					<div class="slider-box clrfix" style="position: relative;">
						<!--gallery-thumbs-->
						<?php if (!$images) { ?>
							<div class="gallery-thumbs" style="display: none !important;">
								<div class="swiper-container">
									<!--swiper-wrapper-->
									<div class="swiper-wrapper"></div>
								</div>
							</div>
						<?php } ?>
						<?php if ($thumb || $images) { ?>
							<?php $i=1; if ($images) { ?>			
								
								<?php 
									if($youtubes == true) {
										$videoInt = 1;
										} else{
										$videoInt = 0;
									}								
								?>
								
								<div class="gallery-thumbs">
									<?php $i = 1;
										if ($images) { ?>
										<div class="swiper-container thumbImages_<?php echo count($images) + $videoInt; ?>">
											
											
											<!--swiper-wrapper-->
											<div class="swiper-wrapper">
												<!--swiper-slide-->
												<?php if ($thumb) { ?>
													<div class="swiper-slide 1">
														<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>"/>
													</div>
												<?php } ?>
												<?php foreach ($images as $image) { ?>
													<?php if (isset($image['thumb'])) { ?>
														<div class="swiper-slide 2">
															<img src="<?php echo $image['middle']; ?>"
															alt="<?php echo $heading_title; ?>">
														</div>
													<?php } ?>
													<?php 
														$i++;
													} ?>
													
													<?php if ($youtubes) { ?>
														<div class="swiper-slide 3 youtubes_slider_thumb" style="display: flex;"></div>
														<script>
															$(document).ready(function(){
																<? foreach ($youtubes as $youtube) { ?>
																	$('.youtubes_slider_thumb').append('<i class="fab fa-youtube"></i><img class="video_prev" src="//img.youtube.com/vi/<? echo $youtube; ?>/default.jpg" alt="video-<? echo $youtube; ?>" width="100%" height="20px">');
																<? } ?>
															});
														</script>
													<? } ?>
													
													<!--/swiper-slide-->
											</div>
											<!--/swiper-wrapper-->
											
										</div>
									<?php } ?>
									<!-- Add Arrows -->
									<div class="swiper-button-next"></div>
									<div class="swiper-button-prev"></div>
								</div>
							<?php } ?>
						<?php } ?>
						<!--/gallery-thumbs-->
						
						<!--gallery-top-->
						<div class="gallery-top" <?php if (!$images) { ?> style="margin-left: 0" <?php } ?>>
							<?php if ($has_labels) { ?>
								<div class="product__label">
									<?php if (isset($has_labels['bestseller'])) { ?>
										<div class="product__label-hit">Хит продаж</div>
									<?php } ?>
									<?php if (isset($has_labels['bestprice'])) { ?>
										<div class="product__label-best-price">Лучшая цена</div>
									<?php } ?>
									<?php if (isset($has_labels['bestprice'])) { ?>
										<div class="product__label-new">Новинка</div>
									<?php } ?>
									<?php if (isset($has_labels['special'])) { ?>
										<div class="product__label-stock">Акция</div>
									<?php } ?>
								</div>
							<?php } ?>
							
							<div class="swiper-container topImages_<?php echo count($images) + $videoInt; ?>">
								
								<!--swiper-wrapper-->
								<div class="swiper-wrapper">
									<!--swiper-slide-->
									<?php if ($thumb || $images) { ?>
										
										<div class="swiper-slide">										
											<img class="cloudzoom main-photo-prod"
											src="<?php echo $popup; ?>" 
											alt="<?php echo $heading_title; ?>"	
											data-cloudzoom = "
											zoomImage: '<?php echo $popup_ohuevshiy; ?>',
											zoomSizeMode: 'image',
											autoInside:750,
											zoomPosition:'#product-cart'
											"
											>
											
										</div>
										
										<?php $i = 1;
											if ($images) { ?>
											<?php foreach ($images as $image) { ?>
												<?php if (isset($image['thumb'])) { ?>
													<div class="swiper-slide">													
														<img class="cloudzoom"
														src="<?php echo $image['popup']; ?>"
														alt="<?php echo $heading_title; ?>"
														data-cloudzoom = "
														zoomImage: '<?php echo $image['popup_ohuevshiy']; ?>',
														zoomSizeMode: 'image',
														autoInside:750,
														zoomPosition:'#product-cart'
														"
														>
														
													</div>
												<?php } ?>
												<?php $i++;
												} ?>
										<?php } ?>
										<?php if ($youtubes) { ?>
											<?php $cVideo = count($youtubes); ?>
											<?php echo $cVideo; ?>										
											<div class="swiper-slide 3 youtubes_slider1"></div>
											<script>
												$(document).ready(function(){
													let _hGalery = $('.gallery-top').innerHeight();
													<? foreach ($youtubes as $youtube) { ?>
														<? if ($youtube === reset($youtubes)){ ?>
															$('.youtubes_slider1').append('<iframe class="video-gallery-top" src="https://www.youtube.com/embed/<? echo $youtube; ?>" width="100%" height="'+_hGalery+'" frameborder="0" allowfullscreen"></iframe>');
														<? }?>
													<? } ?>
													
												});
												
											</script>
										<? } ?>
									<?php } ?>
									<!--/swiper-slide-->
								</div>
								<!--/swiper-wrapper-->
								<div class="swiper-pagination"></div>
							</div>
						</div>
						<!--/gallery-top-->
					</div>
					<!--/slider-box-->
					
					<!--item-column-->
					<div class="item-column " id="product-cart" style="position: relative; ">
						
						<input type="hidden" name="product_id" size="4" value="<?php echo $product_id; ?>">
						<?php if ($is_set) { ?>
							<input type="hidden" name="set_id" size="4" value="<?php echo $is_set; ?>">
						<?php } ?>
						
						<?php if (!empty($active_action)) { ?>
							<div class="product-info__active_action">					
								<h3>Акция! <?php echo $active_action['caption']; ?></h3>
								<a href="<?php echo $active_action['href']; ?>">Узнать детали</a>
							</div>
						<?php } ?>
						
						<div class="product-info__delivery">					
							
							<?php if ($show_delivery_terms) { ?>
								<div class="delivery_terms <? if (empty($bought_for_week)) { ?>position_pluses-item<? } ?>">					
									
									<?php
										//Dirty fix
										$GP_STOCK_TYPE = $stock_type; 
									?>
									
									<? if ($stock_type == 'in_stock_in_country') { ?>
										
										<? switch ($this->config->get('config_store_id')){
											case 0 : $_city = 'Москве'; break;
											case 1 : $_city = 'Киеве'; break;
											case 2 : $_city = 'Москве'; break;
											default : $_city = ''; break;
										}
										?>
										<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i>Есть в наличии в <? echo $_city; ?><br>
										<!-- <span style="color: rgb(76, 102, 0);font-size: 13px;">доставка / отправка 1-3 дня</span> --></p>
										<? } elseif ($stock_type == 'in_stock_in_moscow_for_kzby') { ?>
										<p style="color: rgb(76, 102, 0);font-size: 13px;"><i class="far fa-check-circle"></i>  доставка/отправка 7-14 дней</p>
										<? } elseif ($stock_type == 'in_stock_in_central_msk') { ?>								
										
										<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> Товар в наличии На нашем складе<br>
										<span style="color: rgb(76, 102, 0);font-size: 13px;">доставка / отправка 5-7 дней</span></p>
										<? } elseif ($stock_type == 'in_stock_in_central') { ?>
										<? if ($this->config->get('config_store_id') == 1) { ?>
											<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> На складе поставщика<br><span style="color: rgb(76, 102, 0);font-size: 13px;">доставка / отправка 10-14 д.</span></p>
											<? } else { ?>
											<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> На складе поставщика<br>
											<span style="color: rgb(76, 102, 0);font-size: 13px;">доставка / отправка 15-30д.</span></p>
										<? } ?>
										<? } elseif ($stock_type == 'supplier_has') { ?>
										<? if ($this->config->get('config_store_id') == 1) { ?>
											<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> На складе поставщика<br><span style="color: rgb(76, 102, 0);font-size: 13px;">доставка/отправка 10-14д.</span></p>
											<? } else { ?>
											<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> На складе поставщика<br><span style="color: rgb(76, 102, 0);font-size: 13px;">доставка/отправка 15-30д.</span></p>
										<? } ?>
										<? } elseif ($stock_type == 'need_ask_about_stock') { ?>
										<p style="color: #ffc34f"><i class="far fa-times-circle"></i> Наличие уточняйте</p>
										<? } elseif ($stock_type == 'supplier_has_no_can_not_buy') { ?>
										<p style="color: red"><i class="far fa-times-circle"></i> Нет в наличии</p>
										
										<? } else { ?>
										<p style="color: #ffc34f"><i class="far fa-times-circle"></i> Наличие уточняйте</p>
									<? } ?>
									<?php if (!empty($delivery)) { ?>
										<?php echo $delivery; ?>
									<?php } ?>
									
								</div>
							<? } ?>
							
							<? if ($is_markdown) { ?>	
								<div id="markdown-reason-btn" class="markdown-reason do-popup-element" data-target="markdown-reason">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500.84 553" width="30" height="30"><defs><style>.cls-3{fill:#ffc34f}.cls-4{fill:#ffc34f}</style></defs><g id="Слой_2" data-name="Слой 2"><g id="Слой_1-2" data-name="Слой 1"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1-2"><path d="M250.42 553a16.69 16.69 0 0 1-9.71-3.11L7 382.93a16.7 16.7 0 0 1 9.71-30.28h50.05V16.7A16.7 16.7 0 0 1 83.46 0h333.91a16.7 16.7 0 0 1 16.7 16.7v336h50.08a16.7 16.7 0 0 1 9.71 30.3L260.12 550a16.68 16.68 0 0 1-9.7 3z" class="cls-5" fill="#51a881"/><path d="M484.15 352.65h-50.08V16.7A16.7 16.7 0 0 0 417.37 0h-167v553a16.68 16.68 0 0 0 9.7-3.11l233.74-167a16.7 16.7 0 0 0-9.71-30.28z" style="isolation:isolate" opacity=".1"/><path class="cls-3" d="M183.63 248.42a50.09 50.09 0 1 1 50.09-50.09 50.14 50.14 0 0 1-50.09 50.09zm0-66.78a16.7 16.7 0 1 0 16.7 16.7 16.71 16.71 0 0 0-16.7-16.7z"/><path class="cls-4" d="M317.2 382a50.09 50.09 0 1 1 50.09-50.09A50.14 50.14 0 0 1 317.2 382zm0-66.78a16.7 16.7 0 1 0 16.69 16.71 16.72 16.72 0 0 0-16.69-16.73z"/><path class="cls-3" d="M179.58 352.65a16.7 16.7 0 0 1-11.81-28.5l141.67-141.67a16.69 16.69 0 1 1 23.61 23.61L191.38 347.76a16.64 16.64 0 0 1-11.8 4.89z"/><path class="cls-4" d="M309.44 182.48l-59 59v47.22l82.63-82.64a16.69 16.69 0 1 0-23.61-23.61z"/></g></g></g></g></svg>
									<span>Причина уценки</span>
								</div>
							<?php } ?>
							
							<?php if ($free_delivery) { ?>
								<?php if ($free_delivery == 'moscow') { ?>
									<div class="delivery_info_free ruMoskow"></div>
									<? } elseif($free_delivery == 'russia') { ?>
									<div class="delivery_info_free"></div>
									<? } elseif($free_delivery == 'kyiv') { ?>
									<div class="delivery_info_free uaKyiv"></div>
									<? } elseif($free_delivery == 'ukraine') { ?>
									<div class="delivery_info_free"></div>
								<? } ?>
							<?php } ?>
							
						</div>
						
						<!--price-->
						<?php if ($price) { ?>
							<div class="price <?php if (!$special) { ?>no-special-price<?php } ?>" <?php if ($need_ask_about_stock) { ?> style="flex-direction: column;" <?php } ?> >
								
								
								
								
								<div class="price__head" <?php  if ($need_ask_about_stock) { ?> style="display: block;" <?php } ?>>
									
									<?php  if ($need_ask_about_stock) { ?>			  
										<p>Данный товар доступен только под заказ. Оставляйте запрос и менеджер магазина свяжется с Вами для уточнения цены и сроков доставки. </p>
										<?php  } else if ($can_not_buy) { ?>
										<?php  } else if (!$special) { ?>
										<div class="price__new"><?php echo $price; ?></div>
										<?php if ($enable_found_cheaper){ ?>
											<div>
												<input type="button" class="btn btn-success btn-default found_cheaper do-popup-element" data-target="found_cheaper" value="Нашли дешевле?" id="ischeaper-send" style="margin-bottom: 6px;margin-right: 10px;color: #ffc34f;font-weight: 600; text-align: left;cursor: pointer; background: transparent;border-bottom: 1px dashed #ffc34f;font-size: 15px;" />	
											</div>
										<?php } ?>	
										<?php } else { ?>
										<div class="price__new"><?php echo $special; ?></div>
										<div class="price__old"><?php echo $price; ?></div>
										<div class="price__bottom">
											<div class="price__sale">-<?php echo $saving; ?>%</div>
											<!-- <div class="price__economy">Экономия 760 грн</div> -->
										</div>	
										<?php if ($enable_found_cheaper){ ?>
											<div>
												<input type="button" class="btn btn-success btn-default found_cheaper do-popup-element" data-target="found_cheaper" value="Нашли дешевле?" id="ischeaper-send" style="margin-bottom: 6px;margin-right: 10px;color: #ffc34f;font-weight: 600; text-align: left;cursor: pointer; background: transparent;border-bottom: 1px dashed #ffc34f;font-size: 15px;" />	
											</div>
										<?php } ?>									
									<?php } ?>	
									
									
								</div>
								<?php  if ($need_ask_about_stock) { ?>																		
									<div class="waitlist-block" style="display: block;width: 100%">
										<form id="waitlist-form">
											<div class="row">
												<img src="/catalog/view/theme/kp/img/Spinners.png" id="ajaxcartloadimg" class="loading_spiner" style="width: 70px;height: 70px;" />
												<div class="phone_block">
													<span >Ваш номер телефона:</span>
													<input class="waitlist__phone" type="text" name="waitlist-phone" id="waitlist-phone" placeholder="<?php echo $mask; ?>" value="<? if ($customer_telephone) { ?><? echo $customer_telephone; ?><? } ?>" />
													<input type="hidden" name="customer_id" value="<?php echo (int)$customer_id; ?>" />
													<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
												</div>		
												<div class="error"  id="error_phone"></div>
												<div class=" error" id="waitlist-success"></div>
											</div>
											<input type="button" class="btn btn-success btn-default" value="Оформить предзаказ" id="waitlist-send" />
											<? /* <br /><span style="color:#CCC; font-size:12px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Оставьте нам свой номер телефона, и если мы сможем привезти этот товар Вам - мы обязательно свяжемся с Вами.</span> */ ?>
										</form>
									</div>	
									<script type="text/javascript">
										
										var loadImgPopup = $('#ajaxcartloadimg');
										var contentPopup = $('#waitlist-form');
										
										$('#waitlist-send').on('click', function() {
											$.ajax({
												url: '/index.php?route=checkout/quickorder/createpreorder',
												type: 'post',
												data: $('#waitlist-form').serialize(),
												dataType: 'json',
												beforeSend: function() {
													$('#waitlist-send').bind('click', false);
													loadImgPopup.show();
													contentPopup.css('opacity','0.5');
												},
												complete: function() {
													$('#waitlist-send').unbind('click', false);
													if (success == 'true'){															
													}
													
												},
												success: function(json) {
													$('#error_phone').empty().hide();
													$('#waitlist-success').empty().hide();
													
													if (json['error']) {				
														if (json['error']['phone']) {
															$('#error_phone').html(json['error']['phone']);
															$('#error_phone').show();
														}
														loadImgPopup.hide();
														contentPopup.css('opacity','1');
													}
													
													if (json['success']){ 
														$('#waitlist-phone').val('').hide();
														$('#waitlist-send').bind('click', false);
														$('#waitlist-success').html(json['success']);
														$('#waitlist-success').show('slow');
														loadImgPopup.hide();
														contentPopup.css('opacity','1');
													} 
												}
												
											});
											
										});
									</script> 
									<? } else if ($can_not_buy) { ?>
									<div class="row" style="width: 100%;">
										<div class="col-md-12 col-sm-12">
											<span style="color:#CCC; font-size:22px; font-weight:700;"><? echo $stock; ?></span>
										</div>
										<div class="waitlist-block">
											<form id="waitlist-form">
												<div class="row">
													<img src="/catalog/view/theme/kp/img/Spinners.png" id="ajaxcartloadimg" class="loading_spiner" style="width: 70px;height: 70px;"/>
													<div class="phone_block" >
														<span>Ваш номер телефона:</span>
														<input class="waitlist__phone" type="text" name="waitlist-phone" id="waitlist-phone" placeholder="<?php echo $mask; ?>" value="<? if ($customer_telephone) { ?><? echo $customer_telephone; ?><? } ?>" />
														<input type="hidden" name="customer_id" value="<?php echo (int)$customer_id; ?>" />
														<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
													</div>		
													<div class="error"  id="error_phone"></div>
													<div class="error"  id="waitlist-success"></div>
												</div>
												<div class="row">
													<div class="text-center">
														<input type="button" class="btn btn-success btn-default" value="Сообщить о поступлении" id="waitlist-send" />
														<? /* <br /><span style="color:#CCC; font-size:12px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Оставьте нам свой номер телефона, и если мы сможем привезти этот товар Вам - мы обязательно свяжемся с Вами.</span> */ ?>
													</div>
												</div>
											</form>
										</div>	
										<script type="text/javascript">
											var loadImgPopup = $('#ajaxcartloadimg');
											var contentPopup = $('#waitlist-form');
											$('#waitlist-send').on('click', function() {
												$.ajax({
													url: '/index.php?route=module/callback/waitlist',
													type: 'post',
													data: $('#waitlist-form').serialize(),
													dataType: 'json',
													beforeSend: function() {
														$('#waitlist-send').bind('click', false);
														loadImgPopup.show();
														contentPopup.css('opacity','0.5');
													},
													complete: function() {
														$('#waitlist-send').unbind('click', false);
														if (success == 'true'){															
														}
													},
													success: function(json) {
														$('#error_phone').empty().hide();
														$('#waitlist-success').empty().hide();
														
														if (json['error']) {				
															if (json['error']['phone']) {
																$('#error_phone').html(json['error']['phone']);
																$('#error_phone').show();
															}
															loadImgPopup.hide();
															contentPopup.css('opacity','1');
														}
														
														if (json['success']){ 
															$('#waitlist-phone').val('').hide();
															$('#waitlist-send').bind('click', false);
															$('#waitlist-success').html(json['success']);
															$('#waitlist-success').show('slow');
															loadImgPopup.hide();
															contentPopup.css('opacity','1');
														} 
													}
													
												});
												
											});
										</script> 
									</div>
									<? } else { ?>	
									<div class="price__btn-group">
										<?php if ($additional_offers) { ?> 
											<?php $ao_has_zero_price = false;
												foreach ($additional_offers as $additional_offer) { ?>
												<input type='checkbox' checked="checked" style="display:none" class="check-offer-item" name='additional_offer[<? echo $additional_offer['additional_offer_id']; ?>]' id='additional_offer_<? echo $additional_offer['additional_offer_id']; ?>'/>
												<? } 
											} ?>
											<button 
											id="main-add-to-cart-button" 
											class="price__btn-buy btn <?php if ($additional_offers) { ?> btn-additional-offer <? } ?>" 
											<?php if ($additional_offers) { ?> 
												<?php $ao_has_zero_price = false;
													foreach ($additional_offers as $additional_offer) { ?>
													data-ao-id="<? echo $additional_offer['additional_offer_id']; ?>"
													<? } 
												} ?>
												>
													
													<svg width="26" height="25" viewbox="0 0 26 25" fill="none"
													xmlns="http://www.w3.org/2000/svg">
														<path d="M1 1.33948H5.19858L8.01163 15.6923C8.10762 16.1858 8.37051 16.6292 8.7543 16.9447C9.13809 17.2602 9.61832 17.4278 10.1109 17.4181H20.3135C20.8061 17.4278 21.2863 17.2602 21.6701 16.9447C22.0539 16.6292 22.3168 16.1858 22.4128 15.6923L24.0922 6.69903H6.24823M10.4468 22.7777C10.4468 23.3697 9.97687 23.8496 9.39716 23.8496C8.81746 23.8496 8.34752 23.3697 8.34752 22.7777C8.34752 22.1857 8.81746 21.7058 9.39716 21.7058C9.97687 21.7058 10.4468 22.1857 10.4468 22.7777ZM21.9929 22.7777C21.9929 23.3697 21.523 23.8496 20.9433 23.8496C20.3636 23.8496 19.8936 23.3697 19.8936 22.7777C19.8936 22.1857 20.3636 21.7058 20.9433 21.7058C21.523 21.7058 21.9929 22.1857 21.9929 22.7777Z"
														stroke="white" stroke-width="2" stroke-linecap="round"
														stroke-linejoin="round"></path>
													</svg>
													<span>Купить</span></button> <div id="quick-order-block">										
													<input type='button' data-product_id="<?=$product_id; ?>"  id="quick-order-btn" class="price__btn-quick-order do-popup-element js-fast-byu" value='Быстрый заказ' data-target="quick_popup"  />
												</div>
												<?php if ($this->config->get('show_wishlist') == '1')  { ?>                  
													<button onclick="addToWishList('<?php echo $product_id; ?>');" class="price__btn-favorite" title="<?php echo $button_wishlist; ?>">
														<svg width="39" height="34" viewbox="0 0 39 34" fill="none"
														xmlns="http://www.w3.org/2000/svg">
															<path d="M34.3012 4.65234C33.446 3.81151 32.4306 3.1445 31.313 2.68943C30.1954 2.23435 28.9975 2.00012 27.7878 2.00012C26.5781 2.00012 25.3802 2.23435 24.2626 2.68943C23.145 3.1445 22.1296 3.81151 21.2744 4.65234L19.4996 6.39654L17.7247 4.65234C15.9972 2.95472 13.6543 2.001 11.2113 2.001C8.76832 2.001 6.42539 2.95472 4.69793 4.65234C2.97048 6.34996 2 8.65243 2 11.0532C2 13.454 2.97048 15.7565 4.69793 17.4541L6.47279 19.1983L19.4996 32.0001L32.5263 19.1983L34.3012 17.4541C35.1568 16.6137 35.8355 15.6158 36.2986 14.5175C36.7617 13.4193 37 12.2421 37 11.0532C37 9.8644 36.7617 8.68721 36.2986 7.58893C35.8355 6.49064 35.1568 5.49278 34.3012 4.65234V4.65234Z"
															stroke="#51A881" stroke-width="2.2" stroke-linecap="round"
															stroke-linejoin="round"></path>
														</svg>
													</button>
												<?php } ?>
												<?php if ($this->config->get('show_compare') == '1')  { ?>
													<button class="addToCompareBtn" onclick="addToCompare('<?php echo $product_id; ?>');" title="В сравнение">
														<i class="icons compare_icompare"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>	
													</button>
												<?php } ?>
									</div>
								<?php } ?>
								
							</div>
						<?php } ?>
						
						<!--/price-->
						
						<?php if ($is_markdown && !empty($markdown_product)) { ?>
							
							<div class="markdown_product_blcok">
								<span class="name">Такой же товар без уценки</span>
								<div class="markdown_product_item">
									<!-- <div class="img-markdown_product">
										<img src="<?php echo $markdown_product['thumb']; ?>" width="100" height="100" title="<?php echo $markdown_product['name']; ?>"/>
									</div> -->
									<div class="name_markdown_product">
										<a href="<?php echo $markdown_product['href']; ?>" title="<?php echo $markdown_product['name']; ?>" ><?php echo $markdown_product['name']; ?></a>
										<?php if (!empty($markdown_product['special'])) { ?>
											<div class="price__new"><?php echo $markdown_product['special']; ?></div>
											<div class="price__old"><?php echo $markdown_product['price']; ?></div>
											<?php } else { ?>
											<div class="price__new"><?php echo $markdown_product['special']; ?></div>
										<?php } ?>
									</div>
								</div>
							</div>
						<?php } ?>
						
						<?php if (!empty($markdowned_products)) { ?>
							<div class="markdown_product_blcok">
								<div class="markdown_product_block_info">
									<?php if (count($markdowned_products) == 1) { ?>
										<span class="name">Такой же товар с уценкой <i id="btn-info-show" class="fas fa-info-circle"></i></span>
										<?php } else { ?>
										<span class="name">Такие же товары с уценкой</span>
										
									<?php } ?>
									<?php foreach ($markdowned_products as $markdown_product) { ?>
										<div class="name_markdown_product_mardown_reasons">
											<div><b>Внешний вид:</b> <?php echo $markdown_product['markdown_appearance']; ?></div>
											<div><b>Состояние:</b> <?php echo $markdown_product['markdown_condition']; ?></div>
											<div><b>Упаковка:</b> <?php echo $markdown_product['markdown_pack']; ?></div>
											<div><b>Комплектация:</b> <?php echo $markdown_product['markdown_equipment']; ?></div>
										</div>
									<?php } ?>
								</div>
								<?php foreach ($markdowned_products as $markdown_product) { ?>
									
									<div class="markdown_product_item">
										<!-- <div class="img-markdown_product">
											<img src="<?php echo $markdown_product['thumb']; ?>" width="100" height="100" title="<?php echo $markdown_product['name']; ?>"/>
										</div> -->
										<div class="name_markdown_product">
											<a href="<?php echo $markdown_product['href']; ?>" title="<?php echo $markdown_product['name']; ?>" ><?php echo $markdown_product['name']; ?></a>
											<?php if (!empty($markdown_product['special'])) { ?>
												<div class="price__new"><?php echo $markdown_product['special']; ?></div>
												<div class="price__old"><?php echo $markdown_product['price']; ?></div>
												<?php } else { ?>
												<div class="price__new"><?php echo $markdown_product['special']; ?></div>
											<?php } ?>
										</div>
										
									</div>
									
								<?php } ?>
								
							</div>
						<?php } ?>
						<?php if (!empty($active_coupon)) { ?>
							<div class="active-coupon">
								<span class="title-coupon">Цена по промокоду</span>
								<div class="active-coupon__price">
									<?php echo $active_coupon['coupon_price']; ?>
								</div>
								<div class="active-coupon__promocode">
									<span id="promo-code-txt" onclick="copytext(this)" title="скопировать промокод"><?php echo $active_coupon['code']; ?></span><button class="btn-copy" onclick="copytext('#promo-code-txt')" title="скопировать промокод"><span class="tooltiptext" style="display: none;">Промокод скопирован</span></button>
								</div>
								<div class="active-coupon__datend" style="opacity: 0;">
									Завершается через <b><div id="note"></div></b>
									
								</div>
							</div>
						<?php } ?>
						
						<!--pluses-item-->
						<div class="pluses-item">
							<ul <? if (empty($bought_for_week)) { ?>style="display: flex;flex-direction: row-reverse;"<? } ?>>
								<? if ($bought_for_week) { ?>
									<li>
										<img src="/catalog/view/theme/kp/img/pluses-icon3.svg" alt="">
										<p><? echo $bought_for_week ?></p>
									</li>
								<? } ?>
								<li>
									<a href="javascript:void(0)" onclick="do_notification_block(31,'delivery_block');return false;" data-target="info_delivery" class="do-popup-element" >
										<img src="/catalog/view/theme/kp/img/pluses-icon-dev.svg" alt="">
										<p>Варианты доставки <i class="fas fa-info-circle"></i></p>
									</a>
								</li>
							</ul>
						</div>
						<!--/pluses-item-->
						
						
						
						<!--delivery-->
						<!-- <div class="delivery">
							<div class="delivery__info">
						<span>Доставка в:</span> -->
						<!--change-city-->
						<!-- <div class="change-city"> -->
						<!-- <div class="change-city__btn">
							<svg width="20" height="24" viewbox="0 0 20 24" fill="none"
							xmlns="http://www.w3.org/2000/svg">
							<path d="M19 10C19 17 10 23 10 23C10 23 1 17 1 10C1 7.61305 1.94821 5.32387 3.63604 3.63604C5.32387 1.94821 7.61305 1 10 1C12.3869 1 14.6761 1.94821 16.364 3.63604C18.0518 5.32387 19 7.61305 19 10Z"
							stroke="#51A881" stroke-width="2" stroke-linecap="round"
							stroke-linejoin="round"></path>
							<path d="M10 13C11.6569 13 13 11.6569 13 10C13 8.34315 11.6569 7 10 7C8.34315 7 7 8.34315 7 10C7 11.6569 8.34315 13 10 13Z"
							stroke="#51A881" stroke-width="2" stroke-linecap="round"
							stroke-linejoin="round"></path>
							</svg>
							<span>Запорожье</span>
						</div> -->
						<!--popup-city-->
						<!-- <div class="popup-city" rel="choose-city">
							<div class="popup-city__title">Выберите город</div>
							<ul class="popup-city__city-list">
							<li><a class="active" href="#">Киев</a></li>
							<li><a href="#">Днепр</a></li>
							<li><a href="#">Харьков</a></li>
							<li><a href="#">Львов</a></li>
							<li><a href="#">Одесса</a></li>
							<li><a href="#">Запорожье</a></li>
							</ul>
							<div class="popup-city__search">
							<svg width="16" height="16" viewbox="0 0 16 16" fill="none"
							xmlns="http://www.w3.org/2000/svg">
							<path d="M1 15L4.38333 11.6167M2.55556 7.22222C2.55556 10.6587 5.34134 13.4444 8.77778 13.4444C12.2142 13.4444 15 10.6587 15 7.22222C15 3.78578 12.2142 1 8.77778 1C5.34134 1 2.55556 3.78578 2.55556 7.22222Z"
							stroke="#FFC34F" stroke-width="2" stroke-linecap="round"
							stroke-linejoin="round"></path>
							</svg>
							<input type="text" name="search-city" autocomplete="off" placeholder="Поиск">
							</div>
							<div class="popup-city__close"></div>
						</div> -->
						<!--/popup-city-->
						<!-- </div> -->
						<!--/change-city-->
						
						<!-- </div>
							<ul class="delivery__list">
							
							</ul>
						</div> -->
						
						<!--/delivery-->
					</div>
					<!--/item-column-->
					
					<!--item-bottom-->
					<div class="item-bottom">
						
						<!--char-->
						<div class="char">
							<h4 class="title">Характеристики</h4>
							<ul class="char-list">
								<?php if ($manufacturer) { ?>
									<li>
										<h2>
											<span><?=$text_brand ?></span> 
											<? if ($show_manufacturer || true) { ?>
												<span><a href="<?php echo $manufacturers; ?>" title="<?php echo $manufacturer; ?>"><?php echo $manufacturer; ?></a></span>
												<? } else { ?>	
												<span><?php echo $manufacturer; ?></span>
											<? }?>
										</h2>
									</li>
								<?php } ?>
								
								<?php if (isset($location) && mb_strlen(trim($location), 'UTF-8') > 0) { ?>
									<li>
										<h2>
											<span><?=$text_country ?></span> <span><?php echo $location; ?></span>
										</h2>
									</li>
								<?php } ?>
								
								<?php if (isset($collection_name)) { ?>
									<li>
										<h2>
											<span><?=$text_collection ?></span> 
											<span><a href="<?php echo $collection_link; ?>" title="<?php echo $collection_name; ?>"><?php echo $collection_name; ?></a></span>
										</h2>
									</li>
								<?php } ?>
								
								<?php if ($ean) { ?>
									<li>
										<h2>
											<span>EAN</span> <span><?php echo $ean; ?></span>
										</h2>
									</li>
								<? } ?>
								
								<?php if ($reward) { ?>
									<li>
										<h2>
											<span><?php echo $text_reward; ?></span> <span><?php echo $reward; ?></span>
										</h2>
									</li>
								<? } ?>
								<?php if ($attribute_groups) { ?>
									<?php foreach ($attribute_groups as $attribute_group) { ?>
										<?php if ($attribute_group['attribute_group_id'] != $special_attribute_group_id) { ?>
											<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
												<? if ($attribute['sort_order'] != '-1') { ?>
													<li>
														<h2>
															<span><?php echo $attribute['name']; ?></span> <span><?php echo $attribute['text']; ?></span>
														</h2>
													</li>
												<? } ?>
											<?php } ?>
										<?php } ?>
									<?php } ?>
								<?php } ?>
								<!--Custom product information-->
								<?php if (($this->config->get('status_product') == '1') && (isset($this->document->cusom_p)) ){ ?>
									<li>
										<h2>
											<span><?php echo htmlspecialchars_decode( $this->document->cusom_p[$this->config->get('config_language_id')]['product_text'], ENT_QUOTES ); ?></span>
										</h2>
									</li>
								<?php } ?>
								<!--end Custom product information-->
							</ul>	
						</div>
						<!--/char-->
						
						<div class="right-col">
							
							<!--payment-->
							<?php if ($payment_list) { ?>	
								<div class="payment">
									<h4 class="title">Оплата</h4>
									<ul>
										<?php foreach ($payment_list as $__payment) { ?>
											<li><?php echo trim($__payment); ?></li>								
										<?php } ?>
									</ul>
								</div>
							<? } ?>
							<!--/payment-->
							
							<!--guarantee-->
							<div class="guarantee">
								<h4 class="title">Гарантия</h4>
								<ul>
									<li>100% гарантия качества и подлинности всех товаров</li>
									<li>Прямые поставки от производителей лучших европейских брендов</li>
									<li>Качественная упаковка заказанного товара, которая обеспечит его целостность и сохранность</li>
								</ul>
							</div>
							<!--/guarantee-->
						</div>
						
					</div>
					<!--/item-bottom-->			
					
					<?php if ($additional_offers) { ?>
						<!--kit-->
						<div class="kit">
							<h3 class="title center"><?= $text_complect_discount ?></h3>
							<!--kit__box-->
							<div class="kit__box" id="kit-box">
								<div class="swiper-container-additional">
									<div class="swiper-wrapper">
										<!--kit__list-->
										<?php
											
											$price = 0;
											$priceNew = 0;
											
											$this->load->model('localisation/currency');
											$productCurr = $this->model_localisation_currency->getCurrencyByCode($this->session->data['currency']);
											
										?>
										<?php $ao_has_zero_price = false;
											foreach ($additional_offers as $additional_offer) { ?>
											<div class="swiper-slide">
												<div class="kit__list">
													<!--kit__item-->
													<div class="kit__item">
														<!--kit__photo-->
														<div class="kit__photo">
															<!--product__photo-->
															<div class="product__photo">
																<a class="img-block" href="<?php echo $popup; ?>">
																	<img src="<?php echo $thumb; ?>" alt="">
																</a>
															</div>
															<!--/product__photo-->
														</div>
														<!--/kit__photo-->
														<!--kit__info-->
														<div class="kit__info">
															<div class="product__rating">
																<span class="rate rate-<?php echo $rating; ?>"></span>
															</div>
															<div class="product__title">
																<a href="<?php echo $popup; ?>"
																class="longtext"><?php echo $heading_title; ?></a>
															</div>
															<div class="product__price">
																<div class="price__new"><?php echo $additional_offer['product_real_price']; ?></div>
															</div>
														</div>
														<!--/kit__info-->
													</div>
													<!--/kit__item-->
													
													<!--kit__item-->
													<div class="kit__item">
														<!--kit__photo-->
														<div class="kit__photo">
															<!--product__photo-->
															<div class="product__photo">
																<a class="img-block" href="<? echo $additional_offer['ao_href']; ?>">
																	<img src="<? echo $additional_offer['ao_image']; ?>" alt="">
																</a>
															</div>
															<!--/product__photo-->
														</div>
														<!--/kit__photo-->
														<!--kit__info-->
														<div class="kit__info">
															<div class="product__rating">
																<span class="rate rate-<?php echo $ao_product['rating']; ?>"></span>
															</div>
															<div class="product__title">
																<a href="<? echo $additional_offer['ao_href']; ?>" class="longtext">
																	<?php if ($additional_offer['ao_quantity'] > 1): ?>
																	<?= $additional_offer['ao_quantity'] ?> X
																	<?php endif; ?>
																	<? echo $additional_offer['ao_product_name']; ?>
																</a>
															</div>
															<div class="product__price">
																<?php // $this->log->debug($additional_offer); ?>
																<div class="price__new"><? echo $additional_offer['ao_price']; ?></div>
																<div class="price__old"><? echo $additional_offer['ao_real_price']; ?></div>																																							
															</div>
														</div>
														<!--/kit__info-->
													</div>
													<!--/kit__item-->
												</div>
												<!--/kit__list-->
												<!--kit__total-price-->
												<div class="kit__total-price">
													<div class="kit__group">													
														<div class="kit_price__old" style="font-size: 17px;text-decoration-line: line-through;color: #e16a5d;"><? echo $additional_offer['total_price_without_offer']; ?></div>
														<div class="kit__price"><?php echo $additional_offer['total_price_with_offer']; ?></div>
														<div class="sale_group">
															<div class="kit__sale">-<?php echo $additional_offer['percent_diff']; ?>%</div>
															<div class="kit__economy">Вы экономите <?php echo $additional_offer['absolute_diff']; ?></div>
														</div>													
													</div>
													<div class="kit__btn">
														<?php if ( $additional_offer['ao_price'] != 0  ) { ?>
															<!-- <div class="flex-item item-green">
																=
																</div>
																
																<div class="flex-item specialoffer-block-price">
																<span class="inf-price-old"><?php echo $price.' '.$productCurr["symbol_right"]; ?>   </span></br>
																<span class="inf-new-price"><?php echo $priceNew.' '.$productCurr["symbol_right"];  ?></span>
																<?php
																	
																	$price = 0;
																	$priceNew = 0;
																	
																?>
																
																
															</div> -->
														<? } else { $ao_has_zero_price = true; } ?>
														<input type='checkbox' <? /* if ($ao_has_zero_price) { ?>checked="checked"<? } */ ?>  style="display:none" class="check-offer-item" name='additional_offer[<? echo $additional_offer['additional_offer_id']; ?>]' id='additional_offer_<? echo $additional_offer['additional_offer_id']; ?>' />
														<button type="button" value="" class="btn btn-success btn-buy btn-additional-offer" placeholder="" data-ao-id="<? echo $additional_offer['additional_offer_id']; ?>"> <svg width="26" height="25" viewbox="0 0 26 25" fill="none"
															xmlns="http://www.w3.org/2000/svg">
																<path d="M1 1.33936H5.19858L8.01163 15.6922C8.10762 16.1857 8.37051 16.629 8.7543 16.9445C9.13809 17.26 9.61832 17.4276 10.1109 17.418H20.3135C20.8061 17.4276 21.2863 17.26 21.6701 16.9445C22.0539 16.629 22.3168 16.1857 22.4128 15.6922L24.0922 6.6989H6.24823M10.4468 22.7775C10.4468 23.3695 9.97687 23.8495 9.39716 23.8495C8.81746 23.8495 8.34752 23.3695 8.34752 22.7775C8.34752 22.1855 8.81746 21.7056 9.39716 21.7056C9.97687 21.7056 10.4468 22.1855 10.4468 22.7775ZM21.9929 22.7775C21.9929 23.3695 21.523 23.8495 20.9433 23.8495C20.3636 23.8495 19.8936 23.3695 19.8936 22.7775C19.8936 22.1855 20.3636 21.7056 20.9433 21.7056C21.523 21.7056 21.9929 22.1855 21.9929 22.7775Z"
																stroke="white" stroke-width="2" stroke-linecap="round"
																stroke-linejoin="round"></path>
															</svg>
															<?=$text_buy ?>										
														</button>
													</div>
												</div>
											</div>
										<? } ?>
										<!--/kit__total-price-->
									</div>
									<!-- arrows -->
									<div class="swiper-button-prev"></div>
									<div class="swiper-button-next"></div>
									<!-- /arrows -->
								</div>
								<!--/kit__box-->
							</div>
						</div>
						<!--/kit-->
						<script>
							$(document).ready(function () {
								var mySwiper = new Swiper('.swiper-container-additional', {
									slidesPerView: 'auto',
									// loop: true,
									centeredSlides: false,
									autoHeight: true,
									simulateTouch: false,
									watchOverflow: true,
									navigation: {
										nextEl: '.swiper-button-next',
										prevEl: '.swiper-button-prev',
									},
								});
							});
						</script>
					<? } ?>
					
					<? if (!empty($product_product_options)){ ?>
						<!--option-->
						<div class="kit option-products">
							<h3 class="title center">С этим товаром покупают</h3>
							<!--kit__box-->
							<div class="kit__box" id="option-box">
								<div class="swiper-container-option" style="overflow: hidden;">
									<div class="swiper-wrapper">
										<!--kit__list-->				
										<?php foreach ($product_product_options as $product_product_option) { ?>
											<?php if ($product_product_option['type'] == 'checkbox') { ?>
												<?php foreach ($product_product_option['product_option'] as $product_option_value) { ?>
													<div class="swiper-slide">
														<div class="kit__list">
															<!--kit__item-->
															<div class="kit__item">
																<!--kit__photo-->
																<div class="kit__photo">
																	<!--product__photo-->
																	<div class="product__photo">
																		<img src="<?php echo $thumb; ?>" alt="">
																	</div>
																	<!--/product__photo-->
																</div>
																<!--/kit__photo-->
																<!--kit__info-->
																<div class="kit__info">
																	<div class="product__rating">
																		<span class="rate rate-<?php echo $rating; ?>"></span>
																	</div>
																	<div class="product__title">
																		<?php echo $heading_title; ?>
																	</div>
																	<div class="product__price">
																		<div class="price__new"><?php echo $additional_offer['product_real_price']; ?></div>
																	</div>
																</div>
																<!--/kit__info-->
															</div>
															<!--/kit__item-->
															
															<!--kit__item-->
															
															<div id="product-option-<?php echo $product_product_option['product_product_option_id']; ?>" class="kit__item option options-special">
																
																
																
																
																
																<!--kit__photo-->
																<div class="kit__photo">
																	<!--product__photo-->
																	<div class="product__photo">
																		<a class="img-block" href="<? echo $product_option_value['href']; ?>">
																			<img class="no-border" src="<?php echo $product_option_value['image']; ?>" alt="<?php echo $product_option_value['name']; ?>" />
																		</a>
																	</div>
																	<!--/product__photo-->
																</div>
																<!--/kit__photo-->
																<!--kit__info-->
																<div class="kit__info">
																	<div class="product__rating">
																		<span class="rate rate-<?php echo $product_option_value['rating']; ?>"></span>
																	</div>
																	<div class="product__title">
																		<a href="<?=$product_option_value['href'] ?>" class="longtext">
																			<?php echo $product_option_value['name']; ?>
																		</a>
																	</div>
																	<div class="product__price">											
																		<div class="price__new">
																			<?php if ($product_option_value['special']) { ?>
																				+<?php echo $product_option_value['special']; ?>
																				<?php } else { ?>
																				+<?php echo $product_option_value['price']; ?>
																			<?php } ?>																			
																		</div>
																	</div>
																</div>
																<!--/kit__info-->
																
																
																
																
															</div>
															
															<!--/kit__item-->
															
														</div>
														<!--/kit__list-->
														<!--kit__total-price-->
														<div class="kit__total-price">
															<div class="kit__btn">
																<input type='checkbox' style="display:none" class="check-offer-item" name="product-option[<?php echo $product_product_option['product_product_option_id']; ?>][]"  value="<?php echo $product_option_value['product_option_id']; ?>" id="product-option-value-<?php echo $product_option_value['product_option_id']; ?>" />
																<button type="button" value="" class="btn btn-success btn-buy btn-option-offer" placeholder="" data-ao-id="<?php echo  $product_product_option['product_product_option_id']; ?>"> <svg width="26" height="25" viewbox="0 0 26 25" fill="none"
																	xmlns="http://www.w3.org/2000/svg">
																		<path d="M1 1.33936H5.19858L8.01163 15.6922C8.10762 16.1857 8.37051 16.629 8.7543 16.9445C9.13809 17.26 9.61832 17.4276 10.1109 17.418H20.3135C20.8061 17.4276 21.2863 17.26 21.6701 16.9445C22.0539 16.629 22.3168 16.1857 22.4128 15.6922L24.0922 6.6989H6.24823M10.4468 22.7775C10.4468 23.3695 9.97687 23.8495 9.39716 23.8495C8.81746 23.8495 8.34752 23.3695 8.34752 22.7775C8.34752 22.1855 8.81746 21.7056 9.39716 21.7056C9.97687 21.7056 10.4468 22.1855 10.4468 22.7775ZM21.9929 22.7775C21.9929 23.3695 21.523 23.8495 20.9433 23.8495C20.3636 23.8495 19.8936 23.3695 19.8936 22.7775C19.8936 22.1855 20.3636 21.7056 20.9433 21.7056C21.523 21.7056 21.9929 22.1855 21.9929 22.7775Z"
																		stroke="white" stroke-width="2" stroke-linecap="round"
																		stroke-linejoin="round"></path>
																	</svg>
																	<?=$text_buy ?>										
																</button>
															</div>
														</div>
													</div>
												<?php } ?>
											<?php } ?>
										<? } ?>
										<!--/kit__total-price-->
										
									</div>
									<!-- arrows -->
									<div class="swiper-button-prev"></div>
									<div class="swiper-button-next"></div>
									<!-- /arrows -->
								</div>
								<!--/kit__box-->
								<div class="swiper-pagination" style="left: 0; right: 0;"></div>
							</div>
						</div>				
						<script>
							$(document).ready(function () {
								var mySwiper = new Swiper('.swiper-container-option', {
									slidesPerView: 'auto',
									// loop: true,
									centeredSlides: false,
									pagination: {
										el: '#option-box .swiper-pagination',
										clickable: true,
									},
									autoHeight: true,
									simulateTouch: false,
									watchOverflow: true,
									navigation: {
										nextEl: '.swiper-button-next',
										prevEl: '.swiper-button-prev',
									},
								});
							});
						</script>
					<?php } ?>
					<!--/option-->
					
					
					
					
					<!--about-section-->
					<div class="about-section">
						<!--about-->
						<?php if ($attribute_groups_special || ($description && strlen(trim($description)) > 32)) { ?>
							<div class="about">
								<?php if ($description && strlen(trim($description)) > 32) { ?>
									<h4 class="title">Описание <span><?php echo $heading_title; ?></span></h4>
									<div class="desc">
										
										<?php if ($attribute_groups_special) { ?>
											<div class="attribute_groups_special">
												<ul>
													<?php foreach ($attribute_groups_special as $attribute_group) { ?>
														<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
															<li>
																<span><?php echo $attribute['text']; ?></span>
															</li>
														<? } ?>
													<?php } ?>												
												</ul>
											</div>		
										<?php } ?>
										
										<?php echo $description; ?>
									</div>
								<?php } ?>							
							</div>
							<div>							
							</div>
						<? } ?>
						<!--/about-->
						
						<!--reviews-col-->
						<div id="reviews-col" class="reviews-col">
							<div class="rev_content">
								<?php if ($review_status) { ?>  
									
									<?php echo $onereview; ?>
									
								<?php } ?>
							</div>
						</div>
						<!--/reviews-col-->
					</div>
					<!--/about-section-->
					
					
					
				</div>
				<!--/tabs__content-->
				
				<!--tabs__content-->
				<?php if (isset($collection) && $collection) { ?>
					<div id="collection-tab" class="tabs__content">
						<a href="<?php echo $collection_link; ?>" class="btn btn-acaunt"><?=$text_all_collection ?> <? echo isset($manufacturer)?$manufacturer.'&nbsp;':''; ?><? echo $collection_name; ?></a>
						
						<div class="catalog__content product-grid list__colection">
							<div class="product__grid"  id="product__grid">
								<?php foreach ($collection as $product) { ?>								
									<?php include($this->checkTemplate(dirname(FILE),'/../structured/product_single.tpl'); ?>
								<?php } ?>
								
							</div>				
						</div>
					</div>
				<? } ?>
				<!--/tabs__content-->
				
				<!--tabs__content-->
				<div class="tabs__content">
					
					<ul class="char-list">
						<?php if ($manufacturer) { ?>
							<li>
								<span><?=$text_brand ?></span> 
								<? if ($show_manufacturer || true) { ?>
									<span><a href="<?php echo $manufacturers; ?>" title="<?php echo $manufacturer; ?>"><?php echo $manufacturer; ?></a></span>
									<? } else { ?>	
									<span><?php echo $manufacturer; ?></span>
								<? }?>
							</li>
						<?php } ?>
						
						<?php if (isset($location) && mb_strlen(trim($location), 'UTF-8') > 0) { ?>
							<li>
								<span><?=$text_country ?></span> <span><?php echo $location; ?></span>
							</li>
						<?php } ?>
						
						<?php if (isset($collection_name)) { ?>
							<li>
								<span><?=$text_collection ?></span> 
								<span><a href="<?php echo $collection_link; ?>" title="<?php echo $collection_name; ?>"><?php echo $collection_name; ?></a></span>
							</li>
						<?php } ?>
						
						<!-- <li>
							<span><?=$text_code ?></span> <span><?php echo $product_id; ?></span>
						</li> -->
						<!-- <li>
							<span><?php echo $text_model; ?></span> <span><?php echo $model; ?></span>
						</li> -->
						<?php if ($ean) { ?>
							<li>
								<span>EAN</span> <span><?php echo $ean; ?></span>
							</li>
						<? } ?>
						
						<?php if ($reward) { ?>
							<li>
								<span><?php echo $text_reward; ?></span> <span><?php echo $reward; ?></span>
							</li>
						<? } ?>
						<!-- <li>
							<span><?php echo $text_stock; ?></span> <span><?php echo $stock; ?></span>
						</li> -->
						<?php if ($attribute_groups) { ?>
							<?php foreach ($attribute_groups as $attribute_group) { ?>
								<?php if ($attribute_group['attribute_group_id'] != $special_attribute_group_id) { ?>
									<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
										<? if ($attribute['sort_order'] != '-1') { ?>
											<li>
												<span><?php echo $attribute['name']; ?></span> <span><?php echo $attribute['text']; ?></span>
											</li>
										<? } ?>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						<!--Custom product information-->
						<?php if (($this->config->get('status_product') == '1') && (isset($this->document->cusom_p)) ){ ?>
							<li>
								<span><?php echo htmlspecialchars_decode( $this->document->cusom_p[$this->config->get('config_language_id')]['product_text'], ENT_QUOTES ); ?></span>
							</li>
						<?php } ?>
						<!--end Custom product information-->
					</ul>	
					
				</div>
				<!--/tabs__content-->
				
				<!--tabs__content-->
				<div class="tabs__content ">
					<?php if ($youtubes) { ?>
						<div id="video-content"  style="background-color: #f9f9f9; text-align:center;">
							<div id="youtubes_youtubes">
							</div>
							<script>
								$(document).ready(function(){
									<? foreach ($youtubes as $youtube) { ?>
										$('#youtubes_youtubes').append('<div class="item"><iframe src="https://www.youtube.com/embed/<? echo $youtube; ?>" frameborder="0" allowfullscreen width="100%" height="350px"></iframe></div>');
									<? } ?>
								});
								
							</script>
						</div>
					<? } ?>
					<?php if ($image) { ?>
						<div id="tab-image" class="tab-image" style="background-color: #f9f9f9; text-align:center;">
							<div>
								<img src="<?php echo $popup; ?>" alt="<? echo $heading_title; ?>" class="img-responsive" style="padding:3px; border:1px solid rgba(0,0,0,0.08); margin-top:10px;" />
							</div>
							<?php unset($image); foreach ($images as $image) { ?>
								<div>
									<img src="<?php echo $image['popup']; ?>" alt="<? echo $heading_title; ?>" class="img-responsive"  style="padding:3px; border:1px solid rgba(0,0,0,0.08); margin-top:10px;" />
								</div>
							<?php } ?>
						</div>
					<? } ?>
				</div>
				<!--/tabs__content-->
				
				<!--tabs__content-->
				<!-- <div class="tabs__content">
					Тут будет контент <b>Сопутствующие товары</b>
				</div> -->
				<!--/tabs__content-->
				
				
				<!--tabs__content-->
				<div class="tabs__content">
					<div class="reviews-col tab_reviews">
						<?php echo $review; ?>
					</div>
				</div>
				<!--/tabs__content-->
				
			</div>
			<div class="sticky-block base-padding">
				<div class="sticky-block__product">
					
					<div class="sticky-block__image">
						<?php if ($thumb) { ?>
							<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" width="100px"/>
						<?php } ?>
						<div class="sticky-block__name"><?php echo $heading_title; ?></div>
					</div> 
					
					<div class="price_product">
						<?php  if ($need_ask_about_stock) { ?>			  
							<p style="font-size: 13px;line-height: 18px;text-align: justify;color: #333;">Данный товар доступен только под заказ. Оставляйте запрос и менеджер магазина свяжется с Вами для уточнения цены и сроков доставки. </p>
							<?php  } else if ($can_not_buy) { ?>
							<span style="color:#CCC; font-size:18px; font-weight:700;"><? echo $stock; ?></span>
							<?php  } else if (!$special) { ?>
							<div class="price__new"><?php echo $price; ?></div>
							<?php } else { ?>
							<div class="price__old_wrap">
								<div class="price__old"><?php echo $price; ?></div>
								<span class="price__saving">-<?php echo $saving; ?>%</span>
							</div>
							<div class="price__new"><?php echo $special; ?></div>
						<?php } ?>		
					</div>
					
					
					<?php  if ($need_ask_about_stock) { ?>
						<div class="waitlist-block" style="display: block;width: 100%">
							<form id="waitlist-form_sticky-block">
								<div class="row">
									<div class="phone_block">
										<span >Ваш номер телефона:</span>
										<input class="waitlist__phone" type="text" name="waitlist-phone" id="waitlist-phone_sticky-block" placeholder="<?php echo $mask; ?>" value="<? if ($customer_telephone) { ?><? echo $customer_telephone; ?><? } ?>" />
										<input type="hidden" name="customer_id" value="<?php echo (int)$customer_id; ?>" />
										<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
									</div>		
									<div class="error"  id="error_phone_sticky-block"></div>
									<div class=" error" id="waitlist-success_sticky-block"></div>
								</div>
								<input type="button" class="btn btn-success btn-default" value="Оформить предзаказ" id="waitlist-send_sticky-block" />
								<? /* <br /><span style="color:#CCC; font-size:12px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Оставьте нам свой номер телефона, и если мы сможем привезти этот товар Вам - мы обязательно свяжемся с Вами.</span> */ ?>
							</form>
						</div>	
						<script type="text/javascript">
							
							var contentPopup = $('#waitlist-form_sticky-block');
							
							$('#waitlist-send_sticky-block').on('click', function() {
								$.ajax({
									url: '/index.php?route=checkout/quickorder/createpreorder',
									type: 'post',
									data: $('#waitlist-form_sticky-block').serialize(),
									dataType: 'json',
									beforeSend: function() {
										$('#waitlist-send_sticky-block').bind('click', false);
										contentPopup.css('opacity','0.5');
									},
									complete: function() {
										$('#waitlist-send_sticky-block').unbind('click', false);
										if (success == 'true'){															
										}
										
									},
									success: function(json) {
										$('#error_phone_sticky-block').empty().hide();
										$('#waitlist-success_sticky-block').empty().hide();
										
										if (json['error']) {				
											if (json['error']['phone']) {
												$('#error_phone_sticky-block').html(json['error']['phone']);
												$('#error_phone_sticky-block').show();
											}
											contentPopup.css('opacity','1');
										}
										
										if (json['success']){ 
											$('#waitlist-phone_sticky-block').val('').hide();
											$('#waitlist-send_sticky-block').hide();
											$('#waitlist-success_sticky-block').html(json['success']);
											$('#waitlist-success_sticky-block').show('slow');
											contentPopup.css('opacity','1');
										} 
									}
									
								});
								
							});
						</script> 
						<?php  } else if ($can_not_buy) { ?>
						<div class="row" style="width: 100%;">
							<div class="waitlist-block">
								<form id="waitlist-form_sticky-block">
									<div class="row">
										<div class="phone_block" >
											<span>Ваш номер телефона:</span>
											<input class="waitlist__phone" type="text" name="waitlist-phone" id="waitlist-phone_sticky-block" placeholder="<?php echo $mask; ?>" value="<? if ($customer_telephone) { ?><? echo $customer_telephone; ?><? } ?>" />
											<input type="hidden" name="customer_id" value="<?php echo (int)$customer_id; ?>" />
											<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
										</div>		
										<div class="error"  id="error_phone_sticky-block"></div>
										<div class="error"  id="waitlist-success_sticky-block"></div>
									</div>
									<div class="row">
										<div class="text-center">
											<input type="button" class="btn btn-success btn-default" value="Сообщить о поступлении" id="waitlist-send_sticky-block" />
											<? /* <br /><span style="color:#CCC; font-size:12px;"><i class="fa fa-info-circle" aria-hidden="true"></i> Оставьте нам свой номер телефона, и если мы сможем привезти этот товар Вам - мы обязательно свяжемся с Вами.</span> */ ?>
										</div>
									</div>
								</form>
							</div>	
							<script type="text/javascript">
								var contentPopup = $('#waitlist-form_sticky-block');
								$('#waitlist-send_sticky-block').on('click', function() {
									$.ajax({
										url: '/index.php?route=module/callback/waitlist',
										type: 'post',
										data: $('#waitlist-form_sticky-block').serialize(),
										dataType: 'json',
										beforeSend: function() {
											$('#waitlist-send_sticky-block').bind('click', false);
											contentPopup.css('opacity','0.5');
										},
										complete: function() {
											$('#waitlist-send_sticky-block').unbind('click', false);
											if (success == 'true'){															
											}
										},
										success: function(json) {
											$('#error_phone_sticky-block').empty().hide();
											$('#waitlist-success_sticky-block').empty().hide();
											
											if (json['error']) {				
												if (json['error']['phone']) {
													$('#error_phone_sticky-block').html(json['error']['phone']);
													$('#error_phone_sticky-block').show();
												}
												contentPopup.css('opacity','1');
											}
											
											if (json['success']){ 
												$('#waitlist-phone_sticky-block').val('').hide();
												$('#waitlist-send_sticky-block').hide();
												$('#waitlist-success_sticky-block').html(json['success']);
												$('#waitlist-success_sticky-block').show('slow');
												contentPopup.css('opacity','1');
											} 
										}
										
									});
									
								});
							</script> 
						</div>
						<?php } else { ?>
						<div class="addTo-cart-qty">
							<?php if ($additional_offers) { ?> 
								<?php $ao_has_zero_price = false;
									foreach ($additional_offers as $additional_offer) { ?>
									<input type='checkbox' checked="checked" style="display:none" class="check-offer-item" name='additional_offer[<? echo $additional_offer['additional_offer_id']; ?>]' id='additional_offer_<? echo $additional_offer['additional_offer_id']; ?>'/>
									<? } 
								} ?>
								<button id="sticky-block_addTo-cart-button" class="price__btn-buy btn <?php if ($additional_offers) { ?> btn-additional-offer <? } ?>" 
								<?php if ($additional_offers) { ?> 
									<?php $ao_has_zero_price = false;
										foreach ($additional_offers as $additional_offer) { ?>
										data-ao-id="<? echo $additional_offer['additional_offer_id']; ?>"
										<? } 
									} ?>
									>
										<svg width="15" height="15" viewbox="0 0 26 25" fill="none"
										xmlns="http://www.w3.org/2000/svg">
											<path d="M1 1.33948H5.19858L8.01163 15.6923C8.10762 16.1858 8.37051 16.6292 8.7543 16.9447C9.13809 17.2602 9.61832 17.4278 10.1109 17.4181H20.3135C20.8061 17.4278 21.2863 17.2602 21.6701 16.9447C22.0539 16.6292 22.3168 16.1858 22.4128 15.6923L24.0922 6.69903H6.24823M10.4468 22.7777C10.4468 23.3697 9.97687 23.8496 9.39716 23.8496C8.81746 23.8496 8.34752 23.3697 8.34752 22.7777C8.34752 22.1857 8.81746 21.7058 9.39716 21.7058C9.97687 21.7058 10.4468 22.1857 10.4468 22.7777ZM21.9929 22.7777C21.9929 23.3697 21.523 23.8496 20.9433 23.8496C20.3636 23.8496 19.8936 23.3697 19.8936 22.7777C19.8936 22.1857 20.3636 21.7058 20.9433 21.7058C21.523 21.7058 21.9929 22.1857 21.9929 22.7777Z"
											stroke="white" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round"></path>
										</svg>Купить
									</button>
									<?php if ($this->config->get('show_wishlist') == '1')  { ?>                  
										<button onclick="addToWishList('<?php echo $product_id; ?>');" class="price__btn-favorite" title="<?php echo $button_wishlist; ?>">
											<svg width="39" height="34" viewbox="0 0 39 34" fill="none"
											xmlns="http://www.w3.org/2000/svg">
												<path d="M34.3012 4.65234C33.446 3.81151 32.4306 3.1445 31.313 2.68943C30.1954 2.23435 28.9975 2.00012 27.7878 2.00012C26.5781 2.00012 25.3802 2.23435 24.2626 2.68943C23.145 3.1445 22.1296 3.81151 21.2744 4.65234L19.4996 6.39654L17.7247 4.65234C15.9972 2.95472 13.6543 2.001 11.2113 2.001C8.76832 2.001 6.42539 2.95472 4.69793 4.65234C2.97048 6.34996 2 8.65243 2 11.0532C2 13.454 2.97048 15.7565 4.69793 17.4541L6.47279 19.1983L19.4996 32.0001L32.5263 19.1983L34.3012 17.4541C35.1568 16.6137 35.8355 15.6158 36.2986 14.5175C36.7617 13.4193 37 12.2421 37 11.0532C37 9.8644 36.7617 8.68721 36.2986 7.58893C35.8355 6.49064 35.1568 5.49278 34.3012 4.65234V4.65234Z"
												stroke="#51A881" stroke-width="2.2" stroke-linecap="round"
												stroke-linejoin="round"></path>
											</svg>
										</button>
									<?php } ?>
									<?php if ($this->config->get('show_compare') == '1')  { ?>
										<button class="addToCompareBtn" onclick="addToCompare('<?php echo $product_id; ?>');" title="В сравнение">
											<i class="icons compare_icompare"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>	
										</button>
									<?php } ?>
						</div>
					<?php } ?>	
					
					
					
					<?php if (!empty($active_action)) { ?>
						<div class="product-info__active_action">					
							<h3>Акция! <?php echo $active_action['caption']; ?></h3>
							<a href="<?php echo $active_action['href']; ?>">Узнать детали</a>
						</div>
					<?php } ?>
					
					
					<div class="product-info__delivery">					
						
						<?php if ($show_delivery_terms) { ?>
							<div class="delivery_terms <? if (empty($bought_for_week)) { ?>position_pluses-item<? } ?>">					
								
								<?php
									//Dirty fix
									$stock_type = $GP_STOCK_TYPE; 
								?>
								
								<? if ($stock_type == 'in_stock_in_country') { ?>
									
									<? switch ($this->config->get('config_store_id')){
										case 0 : $_city = 'Москве'; break;
										case 1 : $_city = 'Киеве'; break;
										case 2 : $_city = 'Москве'; break;
										default : $_city = ''; break;
									}
									?>
									<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i>Есть в наличии в <? echo $_city; ?><br>
									<!-- <span style="color: rgb(76, 102, 0);font-size: 13px;">доставка / отправка 1-3 дня</span> --></p>
									<? } elseif ($stock_type == 'in_stock_in_moscow_for_kzby') { ?>
									<p style="color: rgb(76, 102, 0);font-size: 13px;"><i class="far fa-check-circle"></i>  доставка/отправка 7-14 дней</p>
									<? } elseif ($stock_type == 'in_stock_in_central_msk') { ?>								
									
									<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> Товар в наличии На нашем складе<br>
									<span style="color: rgb(76, 102, 0);font-size: 13px;">доставка / отправка 5-7 дней</span></p>
									<? } elseif ($stock_type == 'in_stock_in_central') { ?>
									<? if ($this->config->get('config_store_id') == 1) { ?>
										<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> На складе поставщика<br><span style="color: rgb(76, 102, 0);font-size: 13px;">доставка / отправка 10-14 д.</span></p>
										<? } else { ?>
										<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> На складе поставщика<br>
										<span style="color: rgb(76, 102, 0);font-size: 13px;">доставка / отправка 15-30д.</span></p>
									<? } ?>
									<? } elseif ($stock_type == 'supplier_has') { ?>
									<? if ($this->config->get('config_store_id') == 1) { ?>
										<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> На складе поставщика<br><span style="color: rgb(76, 102, 0);font-size: 13px;">доставка/отправка 10-14д.</span></p>
										<? } else { ?>
										<p style="color: rgb(76, 102, 0);"><i class="far fa-check-circle"></i> На складе поставщика<br><span style="color: rgb(76, 102, 0);font-size: 13px;">доставка/отправка 15-30д.</span></p>
									<? } ?>
									<? } elseif ($stock_type == 'need_ask_about_stock') { ?>
									<p style="color: #ffc34f"><i class="far fa-times-circle"></i> Наличие уточняйте</p>
									<? } elseif ($stock_type == 'supplier_has_no_can_not_buy') { ?>
									<p style="color: red"><i class="far fa-times-circle"></i> Нет в наличии</p>
									
									<? } else { ?>
									<p style="color: #ffc34f"><i class="far fa-times-circle"></i> Наличие уточняйте</p>
								<? } ?>
								<?php if (!empty($delivery)) { ?>
									<?php echo $delivery; ?>
								<?php } ?>
							</div>
						<? } ?>
						
						<? if ($is_markdown) { ?>	
							<div id="markdown-reason-btn" class="markdown-reason do-popup-element" data-target="markdown-reason">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500.84 553" width="30" height="30"><defs><style>.cls-3{fill:#ffc34f}.cls-4{fill:#ffc34f}</style></defs><g id="Слой_2" data-name="Слой 2"><g id="Слой_1-2" data-name="Слой 1"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1-2"><path d="M250.42 553a16.69 16.69 0 0 1-9.71-3.11L7 382.93a16.7 16.7 0 0 1 9.71-30.28h50.05V16.7A16.7 16.7 0 0 1 83.46 0h333.91a16.7 16.7 0 0 1 16.7 16.7v336h50.08a16.7 16.7 0 0 1 9.71 30.3L260.12 550a16.68 16.68 0 0 1-9.7 3z" class="cls-5" fill="#51a881"/><path d="M484.15 352.65h-50.08V16.7A16.7 16.7 0 0 0 417.37 0h-167v553a16.68 16.68 0 0 0 9.7-3.11l233.74-167a16.7 16.7 0 0 0-9.71-30.28z" style="isolation:isolate" opacity=".1"/><path class="cls-3" d="M183.63 248.42a50.09 50.09 0 1 1 50.09-50.09 50.14 50.14 0 0 1-50.09 50.09zm0-66.78a16.7 16.7 0 1 0 16.7 16.7 16.71 16.71 0 0 0-16.7-16.7z"/><path class="cls-4" d="M317.2 382a50.09 50.09 0 1 1 50.09-50.09A50.14 50.14 0 0 1 317.2 382zm0-66.78a16.7 16.7 0 1 0 16.69 16.71 16.72 16.72 0 0 0-16.69-16.73z"/><path class="cls-3" d="M179.58 352.65a16.7 16.7 0 0 1-11.81-28.5l141.67-141.67a16.69 16.69 0 1 1 23.61 23.61L191.38 347.76a16.64 16.64 0 0 1-11.8 4.89z"/><path class="cls-4" d="M309.44 182.48l-59 59v47.22l82.63-82.64a16.69 16.69 0 1 0-23.61-23.61z"/></g></g></g></g></svg>
								<span>Причина уценки</span>
							</div>
						<?php } ?>
						
						<?php if ($free_delivery) { ?>
							<?php if ($free_delivery == 'moscow') { ?>
								<div class="delivery_info_free ruMoskow"></div>
								<? } elseif($free_delivery == 'russia') { ?>
								<div class="delivery_info_free"></div>
								<? } elseif($free_delivery == 'kyiv') { ?>
								<div class="delivery_info_free uaKyiv"></div>
								<? } elseif($free_delivery == 'ukraine') { ?>
								<div class="delivery_info_free"></div>
							<? } ?>
						<?php } ?>
						
					</div>
					
					
					<?php if (!empty($active_coupon)) { ?>
						<div class="active-coupon">
							<span class="title-coupon">Цена по промокоду</span>
							<div class="active-coupon__price">
								<?php echo $active_coupon['coupon_price']; ?>
							</div>
							<div class="active-coupon__promocode">
								<span id="promo-code-txt" onclick="copytext(this)" title="скопировать промокод"><?php echo $active_coupon['code']; ?></span><button class="btn-copy" onclick="copytext('#promo-code-txt')" title="скопировать промокод"><span class="tooltiptext" style="display: none;">Промокод скопирован</span></button>
							</div>
							<div class="active-coupon__datend" style="opacity: 0;">
								Завершается через <b><div id="note"></div></b>
								
							</div>
						</div>
					<?php } ?>
					<!--pluses-item-->
					<div class="pluses-item">
						<ul>
							<? if ($bought_for_week) { ?>
								<li>
									<img src="/catalog/view/theme/kp/img/pluses-icon3.svg" alt="">
									<p><? echo $bought_for_week ?></p>
								</li>
							<? } ?>
							<li>
								<a href="javascript:void(0)" onclick="do_notification_block(31,'delivery_block');return false;" data-target="info_delivery" class="do-popup-element" >
									<img src="/catalog/view/theme/kp/img/pluses-icon-dev.svg" alt="">
									<p>Варианты доставки <i class="fas fa-info-circle"></i></p>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</main> 		
		<!--/tabs-->
	</div>
	
	<div class="wrap">
		<?php if ($this->config->get('site_position') == '1') { ?>
			<?php echo $content_bottom; ?>
		<?php } ?>
	</div>
	
	
</section>
<!--/article-->


<?php if (isset($show_button) && $show_button): ?>

<?php endif; ?>

<?php echo $column_left; ?><?php echo $column_right; ?>

<!-- popup_form_revievs -->
<div class="overlay_popup"></div>
<div class="popup-form" id="formRev">
	<div class="object">
		<div class="overlay-popup-close"><i class="fas fa-times"></i></div>
		<h3>Оставить отзыв</h3>		
		<?php include($this->checkTemplate(dirname(FILE),'/../structured/review_form.tpl'); ?>
	</div>
</div>
<!-- popup_form_revievs -->
<div id="quick_popup_container"></div>
<!-- popup_form_revievs -->
<div class="popup-form" id="info_popup">
	<?php include($this->checkTemplate(dirname(FILE),'/../common/popup_info.tpl'); ?>
</div>

<!-- popup_form_revievs -->
<div class="popup-form" id="info_delivery">
	<div class="object">
		<div class="overlay-popup-close"><i class="fas fa-times"></i></div>
		<h3>Варианты доставки</h3>
		<div class="info-order-container">
			<div class="content" style="background: #fff;padding: 20px 30px 20px;">	
				<?php if ($delivery_info) { ?>
					<?php echo $delivery_info; ?>
					<? echo $displaydelivery; ?>
				<?php } ?>
			</div>	
		</div>
	</div>
</div>

<?php if ($is_markdown) { ?>
	<!-- markdown-reason -->
	<div class="popup-form" id="markdown-reason">
		<div class="object">
			<div class="overlay-popup-close"><i class="fas fa-times"></i></div>
			<h3>Причина уценки</h3>
			<div class="info-order-container">
				<?php include($this->checkTemplate(dirname(FILE),'/popup/markdown-reason.tpl'); ?>
			</div>
		</div>
	</div>
	<!-- /markdown-reason -->
<?php } ?>

<!-- popup_form_revievs -->
<div class="popup-form" id="found_cheaper">
	<div class="object">
		<div class="overlay-popup-close"><i class="fas fa-times"></i></div>
		<h3>Нашли дешевле? Снизим цену!</h3>
		<div class="info-order-container">
			<div class="content">	
				<span style="text-align: center;display: block;font-size: 18px;margin-bottom: 10px;">Пришлите нам ссылку на этот товар в другом магазине и мы сделаем Вам предложение лучше!</span>
				<form  method="post" id="found_cheaper-form">	                    
					<div class="form-group">
						<label>Телефон</label>
						<input id="found_cheaper-phone" class="found_cheaper" type="text" value="<? if ($customer_telephone) { ?><? echo $customer_telephone; ?><? } ?>" placeholder="<?php echo str_replace('9', '_', $mask) ?>" name="found_cheaper-phone">
						<input type="hidden" name="customer_id" value="<?php echo (int)$customer_id; ?>" />
						<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
						<div class="error"  id="error_phone_found_cheaper" style="color: #e16a5d;display: block;font-weight: 600;"></div>											
					</div>    
					<div class="form-group">
						<label>Почта</label>
						<input type="email" id="found_cheaper-email" name="found_cheaper-email" class="form-control"/>
						<input type="hidden" value="1" name="is_cheaper" class="form-control"/>
					</div>                
					<div class="form-group">
						<label>Ссылка на товар</label>
						<input type="text" id="found_cheaper-link" name="found_cheaper-link" class="form-control" />
						<div class="error"  id="error_link_found_cheaper" style="color: #e16a5d;display: block;font-weight: 600;"></div>	
					</div>
					<div class="form-group-btn">
						<a id="found_cheaper-send" class="btn btn-acaunt" >Отправить</a>
						<span id="found_cheaper-success" style="color: #51a881;display: block;font-weight: 600;"></span>
					</div>
				</form>
			</div>	
		</div>
	</div>
</div>

<div id="tab_header" style="display: none;">
	
	<?php if ($thumb) { ?>
		<img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" width="36"/>
	<?php } ?>
	
	<!--tabs__nav-->
	<div class="tab_header">
		<span class="name_prod"><?php echo $heading_title; ?></span>
	</div>
	<!--/tabs__nav-->
	<div class="price_product">
		
		<?php  if (!$special) { ?>
			<div class="price__new"><?php echo $price; ?></div>
			<?php } else { ?>
			<div class="price__old_wrap">
				<div class="price__old"><?php echo $price; ?></div>
				<span class="price__saving">-<?php echo $saving; ?>%</span>
			</div>
			<div class="price__new"><?php echo $special; ?></div>
		<?php } ?>		
	</div>
	
	<div class="addTo-cart-qty">
		<?php if ($additional_offers) { ?> 
			<?php $ao_has_zero_price = false;
				foreach ($additional_offers as $additional_offer) { ?>
				<input type='checkbox' checked="checked" style="display:none" class="check-offer-item" name='additional_offer[<? echo $additional_offer['additional_offer_id']; ?>]' id='additional_offer_<? echo $additional_offer['additional_offer_id']; ?>'/>
				<? } 
			} ?>
			<button id="addTo-cart-button" class="price__btn-buy btn <?php if ($additional_offers) { ?> btn-additional-offer <? } ?>" 
			<?php if ($additional_offers) { ?> 
				<?php $ao_has_zero_price = false;
					foreach ($additional_offers as $additional_offer) { ?>
					data-ao-id="<? echo $additional_offer['additional_offer_id']; ?>"
					<? } 
				} ?>
				>
					<svg width="15" height="15" viewbox="0 0 26 25" fill="none"
					xmlns="http://www.w3.org/2000/svg">
						<path d="M1 1.33948H5.19858L8.01163 15.6923C8.10762 16.1858 8.37051 16.6292 8.7543 16.9447C9.13809 17.2602 9.61832 17.4278 10.1109 17.4181H20.3135C20.8061 17.4278 21.2863 17.2602 21.6701 16.9447C22.0539 16.6292 22.3168 16.1858 22.4128 15.6923L24.0922 6.69903H6.24823M10.4468 22.7777C10.4468 23.3697 9.97687 23.8496 9.39716 23.8496C8.81746 23.8496 8.34752 23.3697 8.34752 22.7777C8.34752 22.1857 8.81746 21.7058 9.39716 21.7058C9.97687 21.7058 10.4468 22.1857 10.4468 22.7777ZM21.9929 22.7777C21.9929 23.3697 21.523 23.8496 20.9433 23.8496C20.3636 23.8496 19.8936 23.3697 19.8936 22.7777C19.8936 22.1857 20.3636 21.7058 20.9433 21.7058C21.523 21.7058 21.9929 22.1857 21.9929 22.7777Z"
						stroke="white" stroke-width="2" stroke-linecap="round"
						stroke-linejoin="round"></path>
					</svg>Купить
				</button>
	</div>
</div>

<script type="text/javascript">
	
	var loadImgPopup = $('#ajaxcartloadimg');
	var contentPopup = $('#found_cheaper-form');
	
	$('#found_cheaper-send').on('click', function() {
		$.ajax({
			url: '/index.php?route=module/callback/found_cheaper',
			type: 'post',
			data: $('#found_cheaper-form').serialize(),
			dataType: 'json',
			beforeSend: function() {
				$('#found_cheaper-send').bind('click', false);
				loadImgPopup.show();
				contentPopup.css('opacity','0.5');
			},
			complete: function() {
				$('#found_cheaper-send').unbind('click', false);
				
			},
			success: function(json) {
				$('#error_phone_found_cheaper').empty().hide();
				$('#error_link_found_cheaper').empty().hide();
				
				if (json['error']) {				
					if (json['error']['phone']) {
						$('#error_phone_found_cheaper').html(json['error']['phone']);
						$('#error_phone_found_cheaper').show();
					}
					if (json['error']['link']) {
						$('#error_link_found_cheaper').html(json['error']['link']);
						$('#error_link_found_cheaper').show();
					}
					loadImgPopup.hide();
					contentPopup.css('opacity','1');
				}
				
				if (json['success']){ 
					$('#found_cheaper-phone').val('');
					$('#found_cheaper-email').val('');
					$('#found_cheaper-link').val('');			
					
					$('#found_cheaper-send').bind('click', false);
					$('#found_cheaper-success').html(json['success']);
					$('#found_cheaper-success').show('slow');
					loadImgPopup.hide();
					contentPopup.css('opacity','1');
				} 
			}
			
		});
		
	});
</script> 

<script type="text/javascript">
	function do_notification_block(t,b){			
		$("#info_delivery .content").load("index.php?route=information/information/info2&information_id="+t+"&block="+b);
	}
	
	$('.reviews_btn').on('click', function () {
		$('.tabs #reviews_btn').addClass('active').siblings().removeClass('active').closest('.tabs').find('div.tabs__content').removeClass('active').eq($('.tabs #reviews_btn').index()).addClass('active');
		var $target = $('.tabs #reviews_btn');
		$('html, body').stop().animate({
			'scrollTop': $target.offset().top - 50
			}, 900, 'swing', function () {
			window.location.hash = target;
		});
	});
	
	$('.btn-buy').click( function() {
		$('.kit__btn').find(".check-offer-item").prop('checked', false);			
		$(this).prev().prop('checked', true);	
		
	});
	
	// <? if (!$ao_has_zero_price) { ?>
	// 	$('#main-add-to-cart-button').click( function() {
	// 		$(".check-offer-item").prop("checked", false);
	// 	});
	// <? } ?>
	
	
	$('#main-add-to-cart-button, .btn-buy, #addTo-cart-button, #sticky-block_addTo-cart-button').on('click', function() {	
		
		if ($(this).hasClass('btn-additional-offer')){
			var ao_btn_id = parseInt($(this).attr('data-ao-id'));
			console.log(ao_btn_id + ' clicked');
		}
		
		if ($(this).hasClass('btn-option-offer')){
			var ao_btn_id = parseInt($(this).attr('data-ao-id'));
			console.log(ao_btn_id + ' clicked');
		}
		
		$.ajax({
			url: '/index.php?route=checkout/cart/add',
			type: 'post',
			data: $('#product-cart input[type=\'text\'], #product-cart input[type=\'hidden\'], #product-cart input[type=\'radio\']:checked, #product-cart input[type=\'checkbox\']:checked, #product-cart select, #product-cart textarea, #kit-box input[type=\'checkbox\']:checked, #option-box input[type=\'checkbox\']:checked, #product-cart .price__btn-group input[type=\'checkbox\']:checked, .addTo-cart-qty input[type=\'checkbox\']:checked'),
			dataType: 'json',
			error:function(json) {
				console.log(json);
			},
			beforeSend: function(){			
			},
			success: function(json) {	
				
				$('.success, .warning, .attention, information, .error').hide();
				
				if (json['error']) {
					if (json['error']['option']) {
						for (i in json['error']['option']) {
							$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
						}
					}
					
					if (json['error']['message']) {
						$('#alert-message').html(json['error']['message']);
						$('#alert-message').show();
					}
				}
				
				if (json['success']) {
					$('#header-small-cart').load('/index.php?route=module/cart #header-small-cart');
					$('#popup-cart').load('/index.php?route=common/popupcart', function(){ $('#popup-cart-trigger').click(); });
					
					window.dataLayer = window.dataLayer || [];
					dataLayer.push({
						'event': 'addToCart',
						'ecommerce': {
							'currencyCode': '<?php echo $this->config->get('config_regional_currency'); ?>',  
							'add': {                             
								'products': [{ 
									'id':'<?php echo $google_ecommerce_info['product_id']; ?>',
									'name':'<?php echo $google_ecommerce_info['name']; ?>',
									'price':'<?php echo $google_ecommerce_info['price']; ?>',
									'brand':'<?php echo $google_ecommerce_info['brand']; ?>',
									'category':'<?php echo $google_ecommerce_info['category']; ?>',								
									'quantity': 1
								}]
							}
						}
					});	
					
					if ((typeof fbq !== 'undefined')){
						fbq('track', 'AddToCart', 
						{
							value: <?php echo $google_ecommerce_info['price']; ?>,
							currency: '<?php echo $google_ecommerce_info['currency']; ?>',
							content_type: 'product',
							content_ids: '<?php echo $product_id; ?>'
						});
					}			
					
				}
			}
		});
	});
</script>






<style>		
	.product-detail section.top-slider{
	overflow: inherit;
	}
	
	.cloudzoom-lens{
	border:1px solid #dbdbdb;
	border-radius: 0;
	background:#51a88159;
	}
	.cloudzoom-blank > div:nth-of-type(3){
	display: none !important;
	}	
	
</style>
<script type="text/javascript">
	function copytext(el) {
		var $tmp = $("<textarea>");
		$("body").append($tmp);
		$tmp.val($(el).text()).select();
		document.execCommand("copy");
		$tmp.remove();
		$('.btn-copy').find('.tooltiptext').show();
		setInterval(function(){
            $('.btn-copy').find('.tooltiptext').hide();
		}, 1500);
	}  
	$(document).ready(function() {
		
		
		$('#btn-info-show').hover(function(){
			$('.name_markdown_product_mardown_reasons').addClass('show');
			}, function() {
			$('.name_markdown_product_mardown_reasons').removeClass('show');
		});
		
		
		if ((typeof fbq !== 'undefined')){
			fbq('track', 'ViewContent', 
			{
				content_type: 'product',
				content_ids: '<?php echo $product_id; ?>'
			});
		}
	});
	window.dataLayer = window.dataLayer || [];
	dataLayer.push({
		'event':'productClick',
		'ecommerce': {
			<?php if (!empty($gacListFrom)) { ?>
				'actionField': {'list': '<?php echo $gacListFrom ?>'}, 
			<?php } ?>
			'currencyCode':'<? echo $google_ecommerce_info['currency']; ?>',
			'click': {
				'products': [{
					'id':'<?php echo $google_ecommerce_info['product_id']; ?>',
					'name':'<?php echo $google_ecommerce_info['name']; ?>',
					'price':'<?php echo $google_ecommerce_info['price']; ?>',
					'brand':'<?php echo $google_ecommerce_info['brand']; ?>',
					'category':'<?php echo $google_ecommerce_info['category']; ?>'
				}]
			}
		}
	});	
</script>


<script type="text/javascript">
	$(document).ready(function() {	
		
		
		
		
		var note = $('#note');
		
		ts = "<?php echo $active_coupon['date_end']; ?>";
		ts = new Date(ts);
		$('#coupon__datend').countdown({
            timestamp : ts,
            format: "on",
            callback  : function(days, hours, minutes, seconds){
				
				var message = "";
				
				message += days + " дней " + ( days==1 ? '':'' ) + " ";
				message += hours + " :" + ( hours==1 ? '':'' ) + " ";
				message += minutes + " : " + ( minutes==1 ? '':'' ) + " ";
				message += seconds + "" + ( seconds==1 ? '':'' );
				
				note.html(message);
			}
		});
		
		
		
		
		$('#found_cheaper-phone').mask("<?php echo $mask; ?>");		
		
		
		
		if ($(window).width() >= '1310'){
			var _hLeftBlock = $('.gallery-top').innerHeight();
			<?php if (!empty($active_action)) { ?>
				$('#product-cart').css('height','auto');
				<?php } else { ?>
				$('#product-cart').css('height',_hLeftBlock);
			<?php } ?>
			
		}		
		
		// Скрытие текста О товаре
		let _hDescripionTitle = $('.about-section .about .title').innerHeight();	
		let _hDescripionText = $('.about-section .about .desc').innerHeight();	
		let _hMainDescripion = _hDescripionTitle + _hDescripionText;
		
		let _hReviews = $('.about-section .rev_content').innerHeight();
		let _hReviewsHead = $('.about-section .reviews-col__head').innerHeight();			
		
		if( _hMainDescripion > _hReviews){
			
			$('.desc').css({'height':_hReviews-_hDescripionTitle,'overflow':'hidden'}).addClass('manufacturer-info-hide');
			
			$('.desc').parent().append('<button class="btn-open-desc"><span>Читать полностью</span><i class="fas fa-angle-down"></button>');
			
			$('.btn-open-desc').on('click', function(){
				var _textBtn = $(this).find('span').text();
				$('.desc').parent().toggleClass('open-btn');
				$(this).find('span').text(_textBtn == "Читать полностью" ? "Скрыть" : "Читать полностью");
				return false;
			});
			
		}
		
		// Быстрый заказ
		$('#quick-order-btn').one('click', function (e) {
			
			e.preventDefault();
			
			$.post('/index.php?route=checkout/quickorder/loadtemplate',  
			$('#product-cart input[type=\'text\'], #product-cart input[type=\'hidden\'], #product-cart input[type=\'radio\']:checked, #product-cart input[type=\'checkbox\']:checked, #product-cart select, #product-cart textarea'), 
			function (data) {	
				
				window.dataLayer = window.dataLayer || [];
				dataLayer.push({
					'event': 'addToCart',
					'ecommerce': {
						'currencyCode': '<?php echo $this->config->get('config_regional_currency'); ?>',  
						'add': {                             
							'products': [{ 
								'id':'<?php echo $google_ecommerce_info['product_id']; ?>',
								'name':'<?php echo $google_ecommerce_info['name']; ?>',
								'price':'<?php echo $google_ecommerce_info['price']; ?>',
								'brand':'<?php echo $google_ecommerce_info['brand']; ?>',
								'category':'<?php echo $google_ecommerce_info['category']; ?>',								
								'quantity': 1
							}]
						}
					}
				});	
				
				$(data).insertAfter('#quick_popup_container');
				$('#quick-order-btn').click();
			});			
		});
		
		// Увеличения изображения отзыва
		$(".colorbox").each(function(){
			$(this).click(function(){	
				var img = $(this);	
				var src = img.attr('src');
				$("body").append("<div class='popup_rev'>"+ 
				"<div class='popup_bg_rev'></div>"+
				"<img src='"+src+"' class='popup_img_rev' />"+ 
				"</div>"); 
				$(".popup_rev").fadeIn(300); 
				$(".popup_bg_rev").click(function(){	
					$(".popup_rev").fadeOut(300);	
					setTimeout(function() {	
						$(".popup_rev").remove();
					}, 300);
				});
			});
		});			
		
	});
	<?php if(count($images) + $videoInt >= 1)  { ?>
		// Главный слайдер товара	
		if ($(".gallery-top")[0]) {			
			
			// Slider top product
			var galleryThumbs<?php echo count($images) + $videoInt; ?> = new Swiper('.gallery-thumbs .swiper-container.thumbImages_<?php echo count($images) + $videoInt; ?>', {
				centeredSlides: false,
				
				<?php if(count($images) + $videoInt >= 5)  {?>
					slidesPerView: 4,
					loop: true,
					loopedSlides: 4,
					height: 400,
					breakpoints: {
						1280: {
							loopedSlides: 5,
						},
					},
					<?php } elseif(count($images) + $videoInt == 4) { ?>
					slidesPerView: 4,
					height: 400,
					breakpoints: {
						1300: {
							slidesPerView: 4,
							height: 400,
						},
						1400: {
							slidesPerView: 5,
							height: 500,
						},
					},
					<?php } elseif(count($images) + $videoInt == 3) { ?>
					slidesPerView: 4,
					height: 400,
					<?php } elseif(count($images) + $videoInt == 2) { ?>
					slidesPerView: 3,
					height: 300,
					<?php } elseif(count($images) + $videoInt == 1) { ?>
					
					slidesPerView: 2,
					height: 200,
				<?php } ?>		
				on:{
					slideChangeTransitionEnd: function () {				      
						jQuery("iframe").each(function() { 
							var src= jQuery(this).attr('src');
							jQuery(this).attr('src',src);  
						});
					},
				},
				touchRatio: 0.2,
				slideToClickedSlide: true,
				slideActiveClass: 'swiper-slide-thumb-active',
				direction: 'vertical',
				navigation: {
					nextEl: '.gallery-thumbs .swiper-button-next',
					prevEl: '.gallery-thumbs .swiper-button-prev',
				},
				
			});
			
			// наведение на thumb смена слайда
			// $('.swiper-slide').on('mouseover', function() {
			//     galleryThumbs<?php echo count($images) + $videoInt; ?>.slideTo($(this).index());
			// });			
			
			var galleryTop<?php echo count($images) + $videoInt; ?> = new Swiper('.gallery-top .swiper-container.topImages_<?php echo count($images) + $videoInt; ?>', {
				slidesPerView: 'auto',
				loop: true,
				loopedSlides: 4,
				pagination: {
					el: '.gallery-top .swiper-pagination',
					clickable: true,
				},
				breakpoints: {
					1280: {
						loopedSlides: 5,
					},
				},
				<?php if(count($images) + $videoInt < 5)  {?>
					thumbs: {
						swiper: galleryThumbs<?php echo count($images) + $videoInt; ?>
					}
				<?php } ?>	
			});
			<?php if(count($images) + $videoInt >= 5)  {?>
				galleryTop<?php echo count($images) + $videoInt; ?>.controller.control = galleryThumbs<?php echo count($images) + $videoInt; ?>;
				galleryThumbs<?php echo count($images) + $videoInt; ?>.controller.control = galleryTop<?php echo count($images) + $videoInt; ?>;
			<?php } ?>	  
			
			
		}
		
	<?php } ?>
</script>

<?php if($is_set) { ?>
	<script type="text/javascript">			
		$('div.kitchen-review').append('<div id="list-products-in-set-product-page"><div class="load-image"><img src="image/set-loader-min.gif" alt=""></div></div>');
		$('#list-products-in-set-product-page').load(
		'index.php?route=module/set/productload&set_id=<?php echo $is_set; ?>',
		function () {
			$('#button-cart').on('click', function () {
				
			});
			$('.load-image').hide();
		}
		);
	</script>
<?php } ?>		

<?php if ($this->config->get('config_store_id') == 22) { ?>	
	<style>
		@media screen and (max-width: 560px){
		
		#product-detail .tabs__nav{
		position: fixed;
		left: 0;
		background: #f7f4f4;
		z-index: 100;
		}
		#product-detail .tabs__nav.fix-top{
		position: fixed;
		}
		}
	</style>
<?php } ?>


<script>
	if(document.documentElement.clientWidth > 1000) {  		
        var showFixedAdd = $('.gallery-top').innerHeight();
        
        if ($(this).scrollTop() > showFixedAdd) $('.addTo-cart-holder').fadeIn();
        else $('.addTo-cart-holder').fadeOut();
        $(window).scroll(function () { 
            if ($(this).scrollTop() > showFixedAdd) $('.addTo-cart-holder').fadeIn();
            else $('.addTo-cart-holder').fadeOut();
		});
		
		$(function(){
		    $('.addTo-cart-holder').liFixar({
		      	side: 'bottom',                
		     	position: 10,               
      			fix: function(el, side){
					el.liFixar('update');
					el.parent('.addTo-cart-wrapper').removeClass('unfix');
				},
				unfix: function(el, side){
					el.liFixar('update');
					el.parent('.addTo-cart-wrapper').addClass('unfix');
				}                                         
			});
		});
	};
	
	<?php if ($this->config->get('config_store_id') == 22) { ?>	
		// fix tab top on mobile	
		if(document.documentElement.clientWidth < 560) { 
			
			window.onscroll = function () {
				let scrollToTop = window.scrollY; 
			}		
			let topSearchHeight = $('.top-search').innerHeight();
			let topMenuHeight = $('.top-menu').innerHeight();
			let headerHeight = $('header').innerHeight();
			let fixNavTab = $('#product-detail .tabs__nav');
			let winSize = $(window).width();
			let sumHeight = topSearchHeight + topMenuHeight;
			
			if(fixNavTab.hasClass('fix-top')){
				fixNavTab.css({ 
					'top': sumHeight,
					'width': winSize
				});
				} else {
				fixNavTab.css({
					'top': headerHeight,
					'width': winSize
				})
			}
			
			$(window).scroll(function () {
				
				let scrollToTop = window.scrollY; 
				let topSearchHeight = $('.top-search').innerHeight();
				let topMenuHeight = $('.top-menu').innerHeight();
				let sumHeight = topSearchHeight + topMenuHeight;
				let headerHeight = $('header').innerHeight() - scrollToTop;
				let fixNavTab = $('#product-detail .tabs__nav');
				
				console.log(headerHeight);
				console.log(scrollToTop)
				if(fixNavTab.hasClass('fix-top')){
					fixNavTab.css({ 
						'top': sumHeight,
						'width': winSize
					});
					} else {
					fixNavTab.css({
						'top': headerHeight - 10,
						'width': winSize
					})
				}
			});
		};
	<?php } ?>
	
	<?php if ($is_markdown) { ?>	
		// markdownReasonSlider
		
		if ($(".image-markdown-reason .gallery-top")[0]) {
			var markdownReasonTop = new Swiper('.image-markdown-reason .gallery-top .swiper-container', {
		      	slidesPerView: 'auto',
		      	loop: true,
		      	loopedSlides: 4,
		      	pagination: {
		        	el: '.image-markdown-reason .gallery-top .swiper-pagination',
		        	clickable: true,
				},
		      	breakpoints: {
		        	1280: {
		          		loopedSlides: 4,
					},
				},
		      	thumbs: {
					swiper: markdownReasonThumb
				}
			});
		    var markdownReasonThumb = new Swiper('.image-markdown-reason .gallery-thumbs .swiper-container', {
		      	centeredSlides: false,
		      	slidesPerView: 4,
		      	touchRatio: 0.2,
		      	slideToClickedSlide: true,
		      	slideActiveClass: 'swiper-slide-thumb-active',
		      	loop: true,
		      	loopedSlides: 4,
		      	direction: 'vertical',
		      	height: 400,
		      	navigation: {
		        	nextEl: '.image-markdown-reason .gallery-thumbs .swiper-button-next',
		        	prevEl: '.image-markdown-reason .gallery-thumbs .swiper-button-prev',
				},
		      	breakpoints: {
		        	1280: {
		          		loopedSlides: 4,
					},
				}
			});
		    markdownReasonTop.controller.control = markdownReasonThumb;
		    markdownReasonThumb.controller.control = markdownReasonTop;
			
		}
		
	 	$('#markdown-reason-btn').on('click', function() {	 	
 			setTimeout(function(){
			  	markdownReasonThumb.update();
		    	markdownReasonTop.update();
			}, 100);
		});		
	<?php } ?>
	
	
	
	if(document.documentElement.clientWidth < 560) { 	
		// fixBTN	
		$('.price__btn-group').appendTo('.product-info__left-col');
		
		// delivery to pulse
		if($('.delivery_terms').hasClass('position_pluses-item')){
			$('.product-info__delivery').appendTo('.pluses-item ul').wrap('<li><li/>');
			// $('.product-info__delivery');
		};
		
		<?php if ($free_delivery) { ?>
			$('.delivery_info_free').appendTo('.pluses-item');
		<?php } ?>
	};				
	
	// Новые табы
	
	
	$(document).ready(function() {
		$(window).scroll(function () { 
			if ($(this).scrollTop() > 650) {
				$('header .top-menu .tab-product-wrap').addClass('show');
				$('.sticky-block.base-padding').addClass('show_tab');
		        }  else {
				$('header .top-menu .tab-product-wrap').removeClass('show');
				$('.sticky-block.base-padding').removeClass('show_tab');
				
			}
		});
	});
	
	var tabClon = $('.tabs__caption').clone();
	$('#tab_header').appendTo('header .top-menu .tab-product-wrap');
	$(tabClon).appendTo('#tab_header .tab_header');
	
	function tabDesc(){
		if($('#main_btn').hasClass('active')){
			$('.product__body').addClass('description');
			$('#tab_header .price_product').show();
			$('#tab_header .addTo-cart-qty').show();
			} else {
			$('.product__body').removeClass('description');
			$('#tab_header .price_product').hide();
			$('#tab_header .addTo-cart-qty').hide();
		}
	}
	tabDesc();
	
	$('.tabs.item-page').bind('DOMSubtreeModified', function(){
		tabDesc();
	})
	
	$('header .top-menu .tabs__caption li').each(function(){
		var idLi = $(this).attr('id');
		var link = $(this).wrap('<a href="'+window.location.href+'#'+idLi+'" class="tab_link" ></a>');
		
		$(link).click(function(event) {
			$('header .top-menu .tabs__caption li').removeClass('active');
			$('.tabs__nav .tabs__caption').find('#'+idLi+'').trigger('click');
			$(this).find('li').addClass('active');
			
			var qtarget = $('.tabs__nav .tabs__caption').find('#'+idLi+'');
			$('html, body').stop().animate({
				'scrollTop': qtarget.offset().top - 107.5
				}, 900, 'swing', function () {
			});
		});
		
	});
	
	$('.tabs__nav .tabs__caption li').each(function(index, el) {
		let idLi = $(this).attr('id');
		$(this).click(function(event) {
			
			window.location.hash = idLi;
			$('header .top-menu .tabs__caption li').removeClass('active');
			$('header .top-menu .tabs__caption li#'+idLi).addClass('active');
		});
	});
	if ($(window).width() <= '850'){
		let M3 = document.querySelector(".js-dragscroll-wrap3");
		if (M3) {
			let e3 = M3.querySelector(".js-dragscroll2");
			new ScrollBooster({
				viewport: M3,
				content: e3,
				emulateScroll: false,
				mode: "x",
				direction: 'horizontal',
				bounceForce: .2, onUpdate: t3 => {
			        e3.style.transform = `translate(\n                  ${-t3.position.x}px, 0px                )`
				}
			})
		}
	};
	
	
	
	// Новые табы
	
	
</script>		
<script type="text/javascript">
	$(document).ready(function() {
		if ((typeof fbq !== 'undefined')){
			fbq('track', 'ViewContent', 
			{
				content_type: 'product',
				content_ids: '<?php echo $product_id; ?>'
			});
		}
	});
	window.dataLayer = window.dataLayer || [];
	dataLayer.push({
		'event':'productDetail',
		'ecommerce': {
			<?php if (!empty($gacListFrom)) { ?>
				'actionField': {'list': '<?php echo $gacListFrom ?>'}, 
			<?php } ?>
			'currencyCode':'<? echo $google_ecommerce_info['currency']; ?>',
			'detail': {
				'products': [{
					'id':'<?php echo $google_ecommerce_info['product_id']; ?>',
					'name':'<?php echo $google_ecommerce_info['name']; ?>',
					'price':'<?php echo $google_ecommerce_info['price']; ?>',
					'brand':'<?php echo $google_ecommerce_info['brand']; ?>',
					'category':'<?php echo $google_ecommerce_info['category']; ?>'
				}]
			}
		}
	});	
</script>	
<?php echo $footer; ?>   