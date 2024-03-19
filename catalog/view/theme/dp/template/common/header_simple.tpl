<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
	<!--<![endif]-->
	<head>
		<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/header_pwa.tpl')); ?>
		<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/header_logged_datalayer.tpl')); ?>
		<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/header_head.tpl')); ?>

		<link rel="stylesheet" href="/js/node_modules/pretty-checkbox/dist/pretty-checkbox.min.css" crossorigin="anonymous">
		<link rel="stylesheet" href="/js/node_modules/select2/dist/css/select2.min.css">
		<link rel="stylesheet" href="/catalog/view/theme/default/css/sumoselect.css">

		<style type="text/css">
			#ajaxcheckout {
			    display: flex;
			    flex-direction: row;
			    justify-content: space-between;
			    align-items: center;
			}
			#ajaxcheckout #quick_order_popap {
			    margin-left: auto;
			    margin-right: 15px;
			}
			#ajaxcheckout #quick_order_popap .error {
			    white-space: nowrap;
			    font-size: 14px;
			    color: #e16a5d;
			}
			#simplecheckout_shipping_address .simplecheckout-error-text{
				position: absolute;
    			top: -20px;
			}
			#quick_order_simplecheckout_btn{
				display: none !important;
			}
			#ajaxcheckout #gotoorder,
			#simplecheckout_button_login,
			.checkout .btn-group .btn{
				color: #404345;
			}
			.tr_wrap .simplecheckout-methods-items{
				margin-bottom: 35px;
			}
			.tr_wrap .simplecheckout-methods-items .title{
				position: relative;
				width: 100%;
			}
			.tr_wrap .simplecheckout-methods-items .title .description{
				position: absolute;
			    left: 33px;
			    top: 100%;
			    font-size: 11px !important;
			}
			.checkout .btn-group .btn svg path{
				stroke: #fff;
			}
			.description_tr label[for="wayforpay"]{
				text-align: center;
			    font-weight: 600;
			    font-size: 13px;
			    background: #51A62D;
			    color: #fff;
			    max-width: max-content;
			    padding: 3px 19px;
			    border-radius: 4px;
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
			.simplecheckout_step_2 .select2-container--default .select2-selection--single,
			.field {
			    background: #f8f8f8;
			}
			.simplecheckout-button-block{
				background: transparent !important;
			}
			.simplecheckout-warning-block{
				background: #e16a5d !important;
				color: #fff !important;
				font-weight: 500 !important;
				border: 0 !important;
				padding: 10px 20px !important;
			}
			#del_choose[disabled]{
				opacity: 0.5;
				cursor: default;
			}
			.header-order{
				margin-bottom: 25px!important;
			}
			.header-order .drop-list__menu.small_icon a{
				display: flex;
				align-items: center;
				font-weight: 400;
				font-size: 18px;
				line-height: 25px;
				color: #000000;
				padding: 15px 20px;
			}
			.header-order .drop-list__menu.small_icon a .icon_b_none{
				display: flex;
				align-items: center;
				justify-content: center;
				margin-right: 13px;
			}
			.header-order .phone_drop-list .drop-list__menu{
		        width: 304px;
			}
			.header-order .drop-list__menu.small_icon li{
				border-bottom: 0;
				position: relative;
			}
			.header-order .drop-list__menu.small_icon li:not(last-child)::after{
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
			.order-cart__item-right .reward_wrap{
				margin-top: 5px;
				position: relative;
				cursor: pointer;
				margin-left: auto;
			}
			.order-cart__item-right .reward_wrap{
			white-space: nowrap;
			}
			.order-cart__item-right .reward_wrap{
				display: flex;
			    align-items: center;
			    gap: 5px;
			}
			.order-cart__item-right .reward_wrap .text{
				font-weight: 400;
			    font-size: 14px;
			    line-height: 21px;
			    color: #2f2f2f;
			}
			.order-cart__item-right .reward_wrap .text b{
			color: #51a62d;
			font-weight: 600;
			margin-right: 2px;
			}
			.order-cart__item-right .reward_wrap .icon{
			    background: #51a62d;
			    border-radius: 4px;
			    display: flex;
			    align-items: center;
			    justify-content: center;
			    padding: 10px 9px;
			}

			.order-cart__item-right .reward_wrap .prompt{
			white-space: initial;
			display: none;
			position: absolute;
			background: #fff;
			z-index: 2;
			width: 250px;
			box-shadow: 0px 12px 17px rgba(0, 8, 29, 0.05),0px 5px 22px rgba(0, 8, 29, 0.06),0px 7px 8px rgba(0, 8, 29, 0.08);
			height: auto;
			font-size: 12px;
			line-height: 16px;
			padding: 15px;
			top: calc(100% - -4px);
			}
			#simplecheckout_cart .order-cart__item.last__item .order-cart__item-right .reward_wrap .prompt{
			    bottom: calc(100% - -4px);
			    top: initial;
			}
			.header-order .drop-list{
				margin-right: auto
			}
			.order-cart__item-right .reward_wrap .prompt{
			right: 0;
			}

			.order-cart__item-right .reward_wrap .prompt p{
			font-size: 12px;
			margin-bottom: 10px;
			}
			.order-cart__item-right .reward_wrap .prompt ul{
			padding-left: 0;
			margin-bottom: 0;
			list-style: none;
			}
			.order-cart__item-right .reward_wrap .prompt li{
			font-size: 12px;
			}
			.order-cart__item-right .reward_wrap:hover .prompt{
			display: block;
			}

			.reward_isLogged_title{
			font-weight: 500;
			text-transform: uppercase;
			margin-bottom: 3px;
			display: flex;
			align-items: center;
			}
			.simplecheckout-cart-total.reward-code .head .title .icon_info{
			background-repeat: no-repeat;
			background-image: url(data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMzMwIDMzMCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzMwIDMzMDsiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnPjxwYXRoIGQ9Ik0xNjUsMEM3NC4wMTksMCwwLDc0LjAyLDAsMTY1LjAwMUMwLDI1NS45ODIsNzQuMDE5LDMzMCwxNjUsMzMwczE2NS03NC4wMTgsMTY1LTE2NC45OTlDMzMwLDc0LjAyLDI1NS45ODEsMCwxNjUsMHogTTE2NSwzMDBjLTc0LjQ0LDAtMTM1LTYwLjU2LTEzNS0xMzQuOTk5QzMwLDkwLjU2Miw5MC41NiwzMCwxNjUsMzBzMTM1LDYwLjU2MiwxMzUsMTM1LjAwMUMzMDAsMjM5LjQ0LDIzOS40MzksMzAwLDE2NSwzMDB6IiBmaWxsPSIjNTFhODgxIi8+PHBhdGggZD0iTTE2NC45OTgsNzBjLTExLjAyNiwwLTE5Ljk5Niw4Ljk3Ni0xOS45OTYsMjAuMDA5YzAsMTEuMDIzLDguOTcsMTkuOTkxLDE5Ljk5NiwxOS45OTFjMTEuMDI2LDAsMTkuOTk2LTguOTY4LDE5Ljk5Ni0xOS45OTFDMTg0Ljk5NCw3OC45NzYsMTc2LjAyNCw3MCwxNjQuOTk4LDcweiIgZmlsbD0iIzUxYTg4MSIvPjxwYXRoIGQ9Ik0xNjUsMTQwYy04LjI4NCwwLTE1LDYuNzE2LTE1LDE1djkwYzAsOC4yODQsNi43MTYsMTUsMTUsMTVjOC4yODQsMCwxNS02LjcxNiwxNS0xNXYtOTBDMTgwLDE0Ni43MTYsMTczLjI4NCwxNDAsMTY1LDE0MHoiIGZpbGw9IiM1MWE4ODEiLz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PC9zdmc+);
			background-size: contain;
			background-position: center;
			width: 15px;
			height: 15px;
			display: inline-block;
			margin-left: 7px;
			position: relative;
			cursor: pointer;
			}
			.simplecheckout-cart-total.reward-code .head .title .icon_info::before{
			content: attr(arial-label);
			position: absolute;
			right: 0;
			bottom: calc(100% - -3px);
			display: flex;
			width: max-content;
			padding: 1px 15px;
			color: #fff;
			background: #333;
			font-size: 12px;
			border-radius: 5px 5px 0 5px;
			transition: .15s ease-in-out;
			display: none;
			max-width: 162px;
			text-align: center;
			text-transform: initial;
			}
			.simplecheckout-cart-total.reward-code .head .title .icon_info:hover::before{
			/*opacity: 1;*/
			display: block;
			}
			.reward_isLogged_text{
			display: block;
			border-left: 2px solid #f6f2b9;
			color: #212121;
			background-color: #fdfcef;
			padding: 16px;
			font-size: 14px;
			margin: 10px 0 15px;
			}
			.reward_isLogged_text a{
			border-bottom: 1px dashed #51a881;
			color: #51a881;
			}
			.reward_isLogged_text a:hover{
			border-bottom: 1px dashed transparent;
			}

			.simplecheckout-cart-total.reward-code.reward-code-disabled{
				color: #a8a8a8;
			}
			.simplecheckout-cart-total.reward-code{
			display: flex;
			flex-direction: column;
			margin-bottom: 20px;
			}
			.simplecheckout-cart-total.reward-code .head{
			display: flex;
			flex-direction: column;
			width: 100%;
			}
			.simplecheckout-cart-total.reward-code .head .title{
			font-weight: 500;
			text-transform: uppercase;
			margin-bottom: 3px;
			}
			.simplecheckout-cart-total.reward-code .head .text{
			font-size: 14px;
			margin-bottom: 5px;
			}
			.simplecheckout-cart-total.reward-code .content{
			display: flex;
			align-items: center;
			width: 100%;
			}
			.simplecheckout-cart-total.promo-code.no_coupon .promo-code-txt{
				border: 0;
				font-style: inherit;
				font-weight: 500;
				color: #e16a5d;
				line-height: 20px;
				font-size: 14px;
				cursor: default;
				text-transform: unset;
			}
			body{
			display: flex;
			flex-direction: column;
			min-height:100vh;
			}
			.footer__down{
			/*margin-top: auto;*/
			}
			section{margin:50px auto}
			section.inside{padding:50px 0;margin:0}
			.wrap{position:relative;max-width:1395px;width:100%;padding:0 20px;margin:0 auto;height:100%}
			.wrap:after{clear:both;content:"";display:block}
			#footer_app_google_play,#footer_app{margin-bottom: 0;}

			.b24-widget-button-wrapper{display: none !important;}
/*			.checkout .field-label {
			text-align: left;
			width: 205px;
			}*/
			.price-old {
			white-space: nowrap;
			font-size: 13px;
			text-decoration-line: line-through;
			color: #a4a4a4;
			font-weight: 500;
			text-align: center;
			}
			.simple-content {
			margin: 0px !important;
			}

			#content h1 {
			margin-bottom: 20px;
			margin-top: 20px;
			font-size: 32px;
			font-weight: 500;
			line-height: 1.3em;
			}
			#buttons.simplecheckout-button-block,
			.breadcrumb,
			#simplecheckout_step_menu,
			.simplecheckout-left-column.checkout #simplecheckout_cart .total .total_value:nth-of-type(2),
			.simplecheckout-left-column.checkout #simplecheckout_cart .total .total_value:nth-of-type(3),
			.simplecheckout-left-column.checkout #simplecheckout_cart .total .total_value:last-child,
			.order-cart .total .total_value:nth-of-type(2) {
			display: none !important;
			}
			.order-cart__item-right {
			text-align: right;
			}
			.simplecheckout-step.order-row.simplecheckout_step_2 {
			justify-content: start;
			justify-content: flex-start;
			}
			.simplecheckout-step.order-row.simplecheckout_step_2 .simplecheckout-right-column {
				width: 40%;
			}
			.simplecheckout-step.order-row.simplecheckout_step_2 #simplecheckout_summary {
				position: sticky;
				top: 5px;
				padding: 20px;
			}
			.simplecheckout-step.order-row.simplecheckout_step_2 .simplecheckout-left-column {
				margin-right: 1%;
				max-width: 1010px;
				width: 60%;
			}

			#simplecheckout_comment {
			margin-bottom: 0;
			}
			#simplecheckout_summary {
			margin-top: 0;
			margin-bottom: 30px;
			}
			#simplecheckout_summary .reward-code input,
			#simplecheckout_summary .voucher-code input,
			#simplecheckout_summary .promo-code input {
			font-size: 14px;
			padding: 10px 10px 10px 32px;
			}
			.checkout-steps__select-label {
			width: 205px;
			}
			.checkout-steps__select-right {
			/*width: calc(100% - 205px);*/
			width: 100%;
			}

			.simplecheckout-methods-description {
			padding: 0 !important;
			margin: 5px 0px;
			font-size: 12px;
			line-height: 14px;
			color: #848484;
			}
			#simplecheckout_customer .simple-address label {
			margin-right: 20px;
			}

			.simplecheckout-left-column.checkout #simplecheckout_cart .title {
			display: none;
			}

			.simplecheckout-left-column.checkout #simplecheckout_cart .order-cart__item .product__photo {
			width: 30px;
			}
			.simplecheckout-left-column.checkout #simplecheckout_cart .product__title a {
			font-size: 15px;
			line-height: 20px;
			}
			.simplecheckout-left-column.checkout #simplecheckout_cart {
			width: 100%;
			}
			.simplecheckout-left-column.checkout #simplecheckout_cart .link {
			font-size: 14px;
			}
			.simplecheckout-left-column.checkout #simplecheckout_cart #edit_cart_popap,
			.simplecheckout-left-column.checkout #simplecheckout_cart .order-cart__item .product__price-new {
			font-size: 14px;
			font-weight: 500;
			}
			.simplecheckout-left-column.checkout #simplecheckout_cart .order-cart__item .product__amount {
			font-size: 12px;
			line-height: 12px;
			}
			.simplecheckout-left-column.checkout #simplecheckout_cart .order-cart__item {
			padding: 5px 0;
			}
			.simplecheckout-left-column.checkout #simplecheckout_cart.order-cart .total {
			font-weight: 500;
			font-size: 15px;
			}

			.simplecheckout-left-column.checkout #simplecheckout_cart .order-cart__bottom {
			padding: 7px 0;
			}
			.simplecheckout-left-column.checkout .simplecheckout-login-right {
			width: auto;
			}
			.simplecheckout-left-column.checkout .simplecheckout-login {
			width: 100%;
			}
			#simplecheckout-block-login-ajax-wrap .simplecheckout-login-right input {
			max-width: 100% !important;
			}
			#simplecheckout_summary .simplecheckout-cart-total {
			border: 0 !important;
			}
			#simplecheckout_summary .reward-code,
			#simplecheckout_summary .voucher-code,
			#simplecheckout_summary .promo-code {
			padding: 0;
			text-align: left;
			}
			#simplecheckout_summary .reward-code,
			#simplecheckout_summary .voucher-code,
			#simplecheckout_summary .promo-code {
			display: flex;
			align-items: center;
			}
			#simplecheckout_summary .reward-code .inputs input,
			#simplecheckout_summary .voucher-code .inputs input,
			#simplecheckout_summary .promo-code .inputs input {
			max-width: 100%;
			}

			#simplecheckout_summary .reward-code .inputs input::-webkit-outer-spin-button,
			#simplecheckout_summary .reward-code .inputs input::-webkit-inner-spin-button {
			-webkit-appearance: none !important;
			}
			#simplecheckout_summary .reward-code .inputs input[type='number'] {
			-moz-appearance: textfield !important;
			}

			#simplecheckout_summary .reward-code #simplecheckout_button_cart,
			#simplecheckout_summary .voucher-code #simplecheckout_button_cart,
			#simplecheckout_summary .promo-code #simplecheckout_button_cart {
			border: 0 !important;
			}
			#simplecheckout_summary .cart__total_text {
			display: flex;
			justify-content: space-between;
			}
			#content{
				margin-bottom: auto !important;
			}
			#simplecheckout_summary .cart__total_text span:first-of-type {
			font-weight: 300;
			font-size: 14px;
			text-align: left;
			}
			#simplecheckout_summary .cart__total_text span:last-of-type {
			font-weight: 500;
			font-size: 16px;
			}
			#simplecheckout_summary .cart__total_text:after {
			display: none;
			}
			#simplecheckout_summary .voucher-code {
			margin-bottom: 20px;
			}
			#popup-cart #quick_order_popap {
			display: none;
			}
			/*комент*/
			.simple-content .checkout-heading,
			#simplecheckout_comment.simplecheckout-block .checkout-heading {
			background: transparent;
			padding: 0;
			margin-top: 15px;
			cursor: pointer;
			font-size: 17px;
			font-weight: 500;
			color: #51a881;
			}
			#simplecheckout_comment.simplecheckout-block .checkout-heading{
			border-bottom: 2px dotted;
			max-width: max-content;
			}
			#simplecheckout_comment .simplecheckout-block-content {
			height: 0;
			overflow: hidden;
			transition: 0.15s ease-in-out;
			}
			#simplecheckout_comment .simplecheckout-block-content.open {
			height: 116px;
			}
			#simplecheckout_comment .simplecheckout-block-content textarea {
			font-size: 16px;
			padding: 15px;
			box-shadow: none;
			resize: none;
			margin-top: 15px !important;
			-moz-appearance: textarea;
			-webkit-appearance: textarea;
			}
			/*.simple-content input[type="text"]{
			-moz-appearance: textarea;
			-webkit-appearance: textarea;
			}*/
			.checkout-steps__select {
			padding-left: 70px;
			}
			#popup-cart .cart-modal__tottal .ajaxtable_tr2:nth-of-type(2) {
			display: none;
			}
			#popup-cart #gotoorder {
			margin-left: auto;
			}
			.SumoSelect > .optWrapper {
			width: 100% !important;
			}
			.SumoSelect > .optWrapper > .options li.opt {
			text-indent: 5px !important;
			}
			.SumoSelect > .optWrapper > .options li.opt label,
			.SumoSelect > .CaptionCont,
			.SumoSelect .select-all > label {
			font-size: 14px;
			}
			.simplecheckout-button-right .button_oc {
			font-size: 18px;
			padding: 14px 16px;
			}
			.simple-content .buttons {
			padding: 0;
			display: block;
			margin-bottom: 25px;
			}
			.btn-group-register {
			display: flex;
			width: 100%;
			flex-direction: row;
			margin: 0;
			justify-content: start;
			gap: 8px;
			}
			.cart__total .simplecheckout_button_confirm, .order-cart .btn{
				font-size: 18px;
			}

			.btn-group-register button {
			width: 100%;
			max-width: 220px;
			display: flex;
			flex-direction: row;
			align-content: center;
			align-items: center;
			margin-bottom: 10px;
			padding: 10px;
			background: #fff !important;
			background-color: #fff;
			text-shadow: none;
			transition: 0.15s ease-in-out;
			outline: none !important;
			font-weight: 500;
			font-size: 14px;
			line-height: 17px;
			color: #404345 !important;
			height: 52px;
			border: 1px solid #EFF1F2;
			border-radius: 30px;
			}
			.btn-group-register button .btn-img {
				max-width: 32px;
				width: 32px;
				padding: 0;
				margin-right: 13px;
				height: 32px;
				display: flex;
				align-items: center;
				justify-content: center;
				border-radius: 100px;
				border: 1px solid #DDE1E4;
			}
			.btn-group-register button:last-child {
			margin-bottom: 0;
			}
			.btn-group-register .btn-facebook .btn-img{
				background: #3B5998;
				border-color: #3B5998;
			}
			.btn-group-register .fa-facebook-f {
				color: #fff;
				position: initial;
				background: transparent;
				height: auto;
				font-size: 18px;
			}
			.checkout .field-label,
			h2.header_btn_register {
				font-weight: 500;
				font-size: 14px;
				line-height: 17px;
				color: #404345;
				margin-bottom: 10px;
				text-align: left;
				display: flex;
				align-items: center;
			}
			#simplecheckout_payment .simplecheckout-methods-description img {
			max-width: 200px;
			}
			.quick_order_wrap {
			display: flex;
			flex-wrap: wrap;
			align-items: flex-end;
			}
			.quick_order_wrap #quick_order_simplecheckout_btn {
			flex-basis: 50%;
			margin-left: auto;
			max-height: 40px;
			height: 40px;
			}
			#customer_telephone {
/*			flex-basis: 45%;*/
			}
			#report_bug_simple{

			}
			#report_bug_simple .title{
			font-size: 16px;
			text-align: center;
			display: block;
			border-top: 1px solid #bfbfbf;
			margin-top: 15px;
			padding-top: 10px;
			margin-bottom: 9px;
			font-weight: 500;
			}
			#report_bug_simple textarea{
			padding: 10px;
			font-size: 14px;
			resize: none;
			border: 1px solid #bfbfbf;
			height: 125px;
			}
			#report_bug_simple button{
			display: block;
			text-align: center;
			width: 100%;
			background: #51a881;
			box-shadow: none;
			padding: 0;
			margin: 14px 0 0 0;
			font-weight: 600;
			font-size: 17px;
			color: #fff;
			height: 55px;
			transition: .3s ease-in-out;
			}
			#simplecheckout_comment .simplecheckout-block-content{
			border-bottom: 1px dotted;
			}
			#report_bug_simple button:hover{
			opacity: 0.7;
			}

			#checkout_customer_main_address_1_full,
			#shipping_courier_city_shipping_address{
			position: relative;
			max-width: 470px;
			width: max-content;
			min-width: 100%;
			}
			.ui-widget.ui-widget-content.ui-autocomplete{
			background: #fff;
			border: 1px solid #828282;
			z-index: 9999;
			}
			.SumoSelect>.optWrapper>.options li ul li.opt {
			padding-left: 0 !important;
			}
			.ui-menu li div {
			padding: 8px 13px !important;
			border-bottom: 1px solid #828282fa !important;
			font-size: 15px;
			}
			.ui-menu li div:last-child {
			border-bottom: 0 !important
			}
			.SumoSelect>.optWrapper>.options li.opt label{
			white-space: break-spaces;
			display: flex !important;
			align-items: baseline;
			justify-content: flex-start;
			padding: 8px 6px;
			}
			.simplecheckout_step_2 .order-row input[type="radio"],
			.simplecheckout_step_2 .checkbox input[type="checkbox"]{
			opacity: 1 !important;
			}
			input[type="checkbox"],
			input[type="radio"]{
			-webkit-appearance: checkbox;
			-webkit-appearance: checkbox !important;
			}
			::placeholder,
			::-webkit-input-placeholder,
		   	:-ms-input-placeholder,
		   	::-ms-input-placeholder,
		   	::placeholder,
			::-moz-placeholder {
			color: #a7a7a7 !important;
			font-weight: 300;

			}
			.simplecheckout_step_2 input {
			-webkit-appearance: auto;
			-webkit-appearance: auto !important;
			-moz-appearance: auto !important;
			appearance: auto !important;
			opacity: 1 !important;

			}
			.simplecheckout_step_2 .description_tr .title{
			font-size: 13px;
			padding: 0 5px;
			color: #888;
			}
			/* .simplecheckout_step_2 .field{
			border: 1px solid #bdbdbd;
			border-radius: 7px;
			padding: 0 10px;
			} */
			.simplecheckout_step_2 .tr_wrap{

			}
			.select2-dropdown{
			z-index: 10;
			background-color: #fff;
			box-shadow: 0 0 1rem rgb(0 0 0 / 15%);
			border-radius: .8rem;
			padding: 10px;
			}
			.select2-container--default .select2-search--dropdown .select2-search__field {
			-webkit-appearance: none;
			-moz-appearance: none;
			appearance: none;
			width: 100%;
			height: 60px;
			padding: 10px;
			font-weight: 400;
			color: #000;
			border: .1rem solid #e5e5e5;
			border-radius: .4rem;
			margin-bottom: 10px;
			font-size: 15px;
			}

			.row-shipping_address_city .select2-container{
			margin-top: -36px;
			opacity: 0;
			}
			.select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
			background-color: #51a881;
			color: white;
			}
			.select2-results__option--selectable {
			font-weight: 500;
			font-size: 15px;
			}
			.simplecheckout_step_2  .select2-container--default .select2-selection--single{
			height: 60px;
			border: 0;
			border-bottom: 1px solid #bdbdbd;
			border-radius: 0;
			}

			.simplecheckout_step_2  .select2-container--default .select2-selection--single .select2-selection__rendered{
			line-height: 60px !important;
			}
			.simplecheckout_step_2 .select2-container--default .select2-selection--single .select2-selection__arrow {
			top: 0;
			bottom: 0;
			margin: auto;
			}
			.simplecheckout_step_2 .simplecheckout-block-content,
			.simplecheckout_step_2 #simplecheckout_shipping_address .simplecheckout-block-content{
			padding-left: 0;
			}
			.simplecheckout_step_2 #simplecheckout_shipping_address .field-group{
			display: flex;
			flex-direction: column !important;
			}
			.simplecheckout_step_2 #simplecheckout_shipping_address .field-label {
			width: 250px;
			}
			.simplecheckout_step_2 .simplecheckout-block {
			display: flex;
			flex-direction: column;
			}
			#shipping_address_address_id{
			opacity: 0 !important;
			}

			select {
			opacity: 1 !important;
			}

			.simplecheckout-methods-table td.title label.description_tr{
			font-size: 13px;
			color: #888;
			}
			.simplecheckout-methods-table td.quote,
			.simplecheckout-methods-table td.code {
			vertical-align: top;
			}
			.simplecheckout-methods-table td.quote label{
			margin-top: 5px;
			}
			.simplecheckout-methods-table td.code input{
			margin-top: 10px;
			}
			.checkout .field-input .password-toggle {
			  	right: 15px !important;
			}
			/*  new design  */
			#simplecheckout_customer .simplecheckout-block-content {
				text-align: center;
				display: flex;
				flex-wrap: wrap;
				column-gap: 16px;
			}
			.field-group.row-shipping_novaposhta_warehouse,
			.field-group.row-shipping_novaposhta_flat,
			.field-group.row-shipping_novaposhta_house_number,
			.field-group.row-shipping_novaposhta_street,
			.field-group.row-shipping_novaposhta_warehous,
			.field-group.row-shipping_courier_city_shipping_address,
			.field-group.row-shipping_address_city,
			.field-group.row-customer_password,
			.field-group.row-customer_telephone,
			.field-group.row-customer_email,
			.field-group.row-customer_firstname,
			.login_tabs .field-group{
				max-width: 315px;
				width: 100%;
				background: #EFF1F2;
				border-radius: 8px;
				display: flex;
				flex-direction: column !important;
				padding: 9px 17px;
				margin-bottom: 8px !important;
				position: relative;
			}
			.field-group.row-shipping_novaposhta_warehouse .field-label,
			.field-group.row-shipping_novaposhta_flat .field-label,
			.field-group.row-shipping_novaposhta_house_number .field-label,
			.field-group.row-shipping_novaposhta_street .field-label,
			.field-group.row-shipping_novaposhta_warehous .field-label,
			.field-group.row-shipping_courier_city_shipping_address .field-label,
			.field-group.row-shipping_address_city .field-label,
			.field-group.row-customer_password .field-label,
			.field-group.row-customer_telephone .field-label,
			.field-group.row-customer_email .field-label,
			.field-group.row-customer_firstname .field-label,
			.login_tabs .field-group .field-label{
				font-weight: 500;
				font-size: 12px;
				line-height: 15px;
				color: #696F74;
				width: auto;
				position: unset;
				margin: 0;
			}
			.field-group.row-shipping_novaposhta_warehouse .field-input,
			.field-group.row-shipping_novaposhta_flat .field-input,
			.field-group.row-shipping_novaposhta_house_number .field-input,
			.field-group.row-shipping_novaposhta_street .field-input,
			.field-group.row-shipping_novaposhta_warehous .field-input,
			.field-group.row-shipping_courier_city_shipping_address .field-input,
			.field-group.row-shipping_address_city .field-input,
			.field-group.row-customer_password .field-input,
			.field-group.row-customer_telephone .field-input,
			.field-group.row-customer_email .field-input,
			.field-group.row-customer_firstname .field-input,
			.login_tabs .field-group .field-input{
				width: auto;
				flex-grow: unset;
				position: unset;
				height: auto;
			}
			.field-group.row-shipping_novaposhta_warehouse .field-input input,
			.field-group.row-shipping_novaposhta_flat .field-input input,
			.field-group.row-shipping_novaposhta_house_number .field-input input,
			.field-group.row-shipping_novaposhta_street .field-input input,
			.field-group.row-shipping_novaposhta_warehous .field-input input,
			.field-group.row-shipping_courier_city_shipping_address .field-input input,
			.field-group.row-shipping_address_city .field-input input,
			.field-group.row-customer_password .field-input input,
			.field-group.row-customer_telephone .field-input input,
			.field-group.row-customer_email .field-input input,
			.field-group.row-customer_firstname .field-input input,
			.login_tabs .field-group .field-input input{
				font-weight: 500;
				font-size: 16px;
				line-height: 19px;
				color: #404345;
				border: 0;
				background: transparent;
				height: auto;
			}
			.simplecheckout-block-content .btn-group,
			#content #simplecheckout_login .buttons{
				display: flex;
				width: auto;
				align-items: center;
				justify-content: start;
				gap: 18px;
				margin-top: 24px !important;
			}
			.simplecheckout_step_2 .simplecheckout-left-column .checkout-steps__item  #edit_cart_popap,
			#first_step_to_second_button,
			#simplecheckout_login #simplecheckout_button_login{
				width: auto;
				background: #BFEA43;
				border-radius: 20px;
				height: auto;
				padding: 10.5px 16px;
				margin: 0 !important;
			}
			.simplecheckout_step_2 .simplecheckout-left-column .checkout-steps__item  #edit_cart_popap{
				font-weight: 500;
				font-size: 14px;
				line-height: 17px;
				color: #404345;		
			}

			#simplecheckout_login  .forgotten{
				font-weight: 500;
				font-size: 12px;
				line-height: 15px;
				color: #696F74;
			}
			#simplecheckout_customer .simplecheckout-block-content .field-group:first-child{
				flex: 0 0 calc(50% - 8px);
			}
			#simplecheckout_customer .simplecheckout-block-content .btn-group,
			.no_autorization #simplecheckout_customer .simplecheckout-block-content .field-group:first-child{
				display: flex;
				width: 100%;
				flex: 0 0 100%;
			}
			#simplecheckout_customer .simplecheckout-block-content .field-group:not(:first-child){
				flex: 0 0 calc(50% - 8px);
			}
			#simplecheckout_customer .simplecheckout-error-text.simplecheckout-rule{
				position: absolute;
				font-size: 11px;
				line-height: 16px;
				bottom: -17px;
				z-index: 1;
			}
			.simplecheckout-error-text {
			    font-weight: 400;
			    background: #f4f6f8;
			    padding: 1px 6px;
			    border-radius: 4px;
			    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
			}
			#simplecheckout_customer .simplecheckout-block-content .field-group {
				margin-bottom: 17px;
			}
			.field-group.row-customer_register .field-label{
				margin-bottom: 0;
			}
			.field-group.row-customer_register .simple-address {
				height: auto !important;
			}
			#simplecheckout_customer .simple-address label{
				font-weight: 500;
				font-size: 16px;
				line-height: 19px;
				color: #404345;
				align-items: center;
				display: flex !important;
			}
			#simplecheckout_customer .simple-address label::before{
				width: 24px;
				height: 24px;
				background: #FFFFFF;
				border: 1px solid #DDE1E4;
				border-radius: 6px;
			}
			#simplecheckout_customer .simple-address label::after{
				border-color: #121415;
			}
			#simplecheckout_customer .simple-address input[type="radio"]:checked + label::before{
				background: #BFEA43;
				border-color: #BFEA43;	
			}
			.simplecheckout-block-content{
				padding: 10px 0 !important;
			}

			#simplecheckout_cart{
				padding: 0;
				background: #FFFFFF;
				border: 1px solid #DDE1E4;
				box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
				border-radius: 12px;
			}
			#simplecheckout_cart > h3{
				display: none;
			}
			#simplecheckout_cart .order-cart__item{
				border-top: 0;
				align-items: center;
				position: relative;
				padding: 20px;
			}
			#simplecheckout_cart .order-cart__item:not(:first-child)::before{
				content: '';
				background: #DDE1E4;
				width: calc(100% - 20px);
				height: 1px;
				position: absolute;
				right: 0;
				top: 0;
			}

			#simplecheckout_cart .order-cart__item .product__photo{
				width: 130px;
				height: 130px;
				background: #FFFFFF;
				border: 1px solid #DDE1E4;
				box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
				border-radius: 8px;
				display: flex;
				align-items: center;
				justify-content: center;
				position: relative;
				overflow: hidden;
			}
			#simplecheckout_cart .order-cart__item .product__photo a{ 
				display: flex;
				align-items: center;
				justify-content: center;
			}
			#simplecheckout_cart .order-cart__item .product__photo a img{
				max-width: 100%;
			}
			#simplecheckout_cart .order-cart__item .quantity > div {
				border: 1px solid #DDE1E4;
				border-radius: 8px;
				width: 136px;
				height: 48px;
				display: flex;
				align-items: center;
				justify-content: center;
				margin-left: 0;
			}
			#simplecheckout_cart .order-cart__item .quantity a {
				font-size: 30px;
				height: 45px;
				display: flex;
				align-items: center;
				width: 40px;
				justify-content: center;
			}
			#simplecheckout_cart .order-cart__item .quantity .input_number {
				border: 0;
				height: auto;
				font-weight: 500;
				font-size: 14px;
				line-height: 17px;
				color: #121415;
				width: 45px;
				text-align: center;
			}
			#simplecheckout_cart .product__title > a{
				font-weight: 500;
				font-size: 16px;
				line-height: 19px;
				color: #121415;
				max-width: 330px;
				display: -webkit-box;
				-webkit-line-clamp: 3;
				-webkit-box-orient: vertical;
				overflow: hidden;
				text-overflow: ellipsis;
				margin-bottom: 10px;	
			}
			#simplecheckout_cart .order-cart__item .remove{
				padding: 0;
				padding: 0;
				position: absolute;
				right: 20px;
				top: 20px;
			}
			#simplecheckout_cart .order-cart__item .remove button{
				cursor: pointer;
				background: transparent;
			}
			.main-body .breadcrumbs-section{
				margin: 0;
			}
			h1.title{
				font-weight: 500;
				font-size: 22px !important;
				line-height: 27px !important;
				color: #121415;
			}
			.simplecheckout_step_2 .simplecheckout-left-column .checkout-steps__item .checkout-steps__head .checkout-steps__count,
			.simplecheckout_step_2 .simplecheckout-left-column .checkout-steps__item:first-child{
				display: none;
			}
			.simplecheckout_step_2 .checkout-steps__select-label,
			.simplecheckout_step_2 .simplecheckout-left-column .checkout-steps__item .checkout-steps__title{
				font-weight: 600;
				font-size: 17px;
				line-height: 17px;
				color: #121415;
				font-family: 'Inter', sans-serif;
				margin-left: 0;
				display: flex;
				height: auto;
				margin-bottom: 5px;
			}
			.simplecheckout_step_2 .checkout-steps__info{
				margin-left: 0;
			}
			.simplecheckout_step_2 .checkout-steps__select{
				padding-left: 0;	
			}

			.field-group.row-shipping_address_city .changeSity{
				display: flex;
				margin: 0;
				color: #404345;
				position: absolute;
				bottom: -22px;
				left: 17px;
				font-weight: 500;
				font-size: 14px;
			}
			.simplecheckout_step_2 .select2-container--default .select2-selection--single .select2-selection__rendered,
			.field-group.row-shipping_address_city .field-input{
				height: 23px;
				background: transparent;
			}
			.simplecheckout_step_2 .select2-container--default .select2-selection--single .select2-selection__rendered{
				line-height: 23px !important;
				padding-left: 0 !important;
			}
			.simplecheckout_step_2 .select2-container--default .select2-selection--single, .field{
				background: transparent;
				border: 0;
			}
			.simplecheckout_step_2 .select2-container--default .select2-selection--single{
				height: auto;
			}
			.row-shipping_address_city .field-input::before{
				display: none !important;
			}
			.simplecheckout-methods-table {
				column-gap: 16px;
				display: flex;
				flex-wrap: wrap;
			}
			.simplecheckout-methods-table .simplecheckout-methods-items{
				flex: 0 0 100%;
			}
			.simplecheckout-methods-table .field-group {
			  	flex: 0 0 calc(50% - 8px);
			}
			.select2-container--default .select2-search--dropdown .select2-search__field{
				height: 40px;
			}
			.select2-results__option--selectable {
				font-weight: 500;
				font-size: 13px;
				line-height: 16px;
			}
			.simplecheckout-methods-table .simplecheckout-methods-items{
				display: flex;
				align-items: center;	
				height: 26px;
				margin-bottom: 17px;
			}
			.simplecheckout-methods-table .simplecheckout-methods-items .title{
				font-weight: 500;
				font-size: 16px;
				line-height: 19px;
				color: #404345;
				display: flex;
				align-items: center;
				gap: 10px;
			}
			.simplecheckout-methods-table .simplecheckout-methods-items .quote{
				font-size: 13px;
				padding: 0 5px;
				color: #888;
			}
			.simplecheckout-methods-table .simplecheckout-methods-items .title input[type="radio"]{
				opacity: 0;
				height: 0;
				width: 0;
				display: none;
			}
			.simplecheckout-methods-table .simplecheckout-methods-items .title label{
				padding-left: 33px;
				position: relative;
				height: 26px;
				display: flex !important;
				align-items: center;
				cursor: pointer;
			}
			.simplecheckout-methods-table .simplecheckout-methods-items .title label::before {
			  	width: 24px;
			  	height: 24px;
			  	background: #FFFFFF;
			  	border: 1px solid #DDE1E4;
			  	border-radius: 6px;
			  	left: 0;
			  	position: absolute;
				top: 0;
				transition: background-color 0.2s;
				content: '';
			}
			.simplecheckout-methods-table .simplecheckout-methods-items input[type="radio"]:checked + label::before {
				background: #BFEA43;
				border-color: #BFEA43;
			}
			.simplecheckout-methods-table .simplecheckout-methods-items label::after {
			  	border-color: #121415;
			}
			.simplecheckout-methods-table .simplecheckout-methods-items input[type="radio"]:checked + label::after{
			  	content: "";
			}
			.simplecheckout-methods-table .simplecheckout-methods-items label::after {
  				height: 5px;
				width: 9px;
				border-left: 2px solid #121415;
				border-bottom: 2px solid #121415;
				transform: rotate(-45deg);
				left: 7px;
				top: 7px;
				transition: all 0.2s;
				position: absolute;
			}
			.checkout-steps__item{
				border: 0 !important;
				padding: 0 !important;
				margin: 0 !important;
			}
			.simplecheckout_step_2 .checkout-steps__head{
				margin-bottom: 0;
			}
			#simplecheckout_comment{
				display: none;
			}
			.select2-container{
				width: 100% !important;
			}
			body{
				overflow-x: hidden;
			}
			.simplecheckout-cart-total.voucher-code,
			.simplecheckout-cart-total.promo-code{
				display: flex;
				flex-direction: column;
				gap: 16px;
				margin-bottom: 21px;
				align-items: start !important;
				overflow: hidden;
			}
			.simplecheckout-cart-total.voucher-code .title,
			.simplecheckout-cart-total.promo-code .title{
				font-weight: 500;
				font-size: 16px;
				line-height: 19px;
				color: #121415;
				margin-bottom: 0;
			}
			.simplecheckout-cart-total.voucher-code > div,
			.simplecheckout-cart-total.promo-code > div{
				display: flex;
				align-items: center;
				background: #FFFFFF;
				border: 1px solid #EFF1F2;
				border-radius: 30px;
				padding: 7px;
			}
			.simplecheckout-cart-total.voucher-code input,
			.simplecheckout-cart-total.promo-code input{
				background: none !important;
				border: 0;
				height: 36px;
				padding: 0 15px !important;
			}
			.simplecheckout-cart-total span.inputs{
				margin: 0 !important; 
			}
			#simplecheckout_summary .voucher-code #simplecheckout_button_cart,
			#simplecheckout_summary .promo-code #simplecheckout_button_cart{
				background: #FFFFFF;
				box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
				border-radius: 20px;
				font-weight: 500;
				font-size: 14px;
				line-height: 17px;
				color: #404345;
				padding: 10px 16px;
				display: flex;
				text-decoration: none;
			}
			.simplecheckout-cart-total::after{
				position: absolute;
			}
			#simplecheckout_summary .cart__total_text {
			  justify-content: start !important;
			  gap: 8px;
			}
			#simplecheckout_summary .cart__total_text span:first-of-type {
			  	font-weight: 500;
				font-size: 16px;
				line-height: 19px;
				color: #121415;

			}
			#simplecheckout_summary .cart__total_text span:last-of-type {
			  	font-weight: 500;
				font-size: 16px;
				line-height: 19px;
				color: #696F74;
			}
			#simplecheckout_summary  #total_total{
				flex-direction: column;
				border-top: 1px solid #DDE1E4 !important;
				margin-top: 24px;
				padding-top: 12px;
				align-items: start;
			}
			#simplecheckout_summary  #total_total .simplecheckout-cart-total-value{
				font-weight: 700;
				font-size: 32px;
				line-height: 160.52%;
				color: #121415;
			}
			#simplecheckout_summary  #simplecheckout-button-main-confirm{
				margin-top: 10px;
				background: #BFEA43;
				border-radius: 20px;
				width: max-content;
				font-weight: 500;
				font-size: 14px;
				line-height: 17px;
				padding: 10.5px 16px;
				color: #404345;
				height: auto;
			}
			.header-order .head-menu{
				margin-right: auto;
			}
			.header-order .head-menu ul {
				display: flex;
				align-items: center;
				gap: 15px;
			}
			.header-order .head-menu ul a{
				display: flex;
				align-items: center;
				font-weight: 500;
				font-size: 14px;
				line-height: 17px;
				color: #696F74;
			}
			.header-order__contact-tel a{
				display: flex;
				align-items: center;
				font-weight: 500;
				font-size: 14px;
				line-height: 17px;
				color: #121415;
			}
			.header-order .box{
				gap: 20px;
				border-color: #BFEA43 !important;
			}
			#simplecheckout_cart  .order-cart__bottom{
				border-top: 1px solid #DDE1E4;
				padding: 20px;
			}
			#simplecheckout_cart .order-cart .total{
				display: flex;
				align-items: center;
			}
			#simplecheckout_cart  .order-cart__bottom #edit_cart_popap{

			    width: auto;
		        background: #BFEA43 !important;
			    border-radius: 20px;
			    height: auto;
			    padding: 10.5px 16px !important;
			    margin: 0 !important;
			    font-weight: 500 !important;
			    font-size: 14px !important;
			    line-height: 17px !important;
			    color: #404345 !important;
			}
			.old_auth{
				background: transparent;
			    font-size: 14px;
			    font-weight: 500;
			    cursor: pointer;
			    text-decoration: underline;
			        transition: color 0.2s ease;
			}
			.old_auth:hover{
			    color: #97B63C;
			}
			#simplecheckout-block-login-ajax-wrap{
				height: 0;
				overflow: hidden;
				transition: .15s ease-in-out;
			}
			#simplecheckout-block-login-ajax-wrap.open{
				height: auto;
			}
			#auth_with_phone .overlay-popup-close,
			#auth_with_phone .group_login_header .old-auth,
			#auth_with_phone .left-wrap{
				display: none; 
			}
			#auth_with_phone .main-wrap .right-wrap{
				max-width: 350px;
			}
			#auth_with_phone .main-wrap .right-wrap h3{
					margin-bottom: 20px;
				}
			#auth_with_phone .main-wrap .right-wrap .group_login_header .title{
				position: relative;
			    display: flex;
			    align-items: center;
			    justify-content: center;
			    margin-top: 25px;
			    margin-bottom: 20px;
			}
			#auth_with_phone .main-wrap .right-wrap .group_login_header .title span{
				font-size: 14px;
			    color: #b3b3b3;
			    background: #f4f6f8;
			    position: relative;
			    z-index: 1;
			    padding: 1px 15px;
			    margin: 0;
			    width: auto;
			}
			#auth_with_phone .main-wrap .right-wrap .group_login_header .title:after {
			    content: '';
			    position: absolute;
			    top: 50%;
			    left: 0;
			    right: 0;
			    height: 1px;
			    background: #b3b3b3;
			}
			#auth_with_phone .phone-wrap span{
				font-size: 16px;
			    font-weight: 500;
			    margin-bottom: 10px;
			}
			#auth_with_phone .phone-wrap input{
				padding: 10px;
			    font-size: large;
			    width: 100%;
			    height: 40px;
			    font-size: 16px;
			}
			#auth_with_phone .phone-wrap .alert{
				padding: 0;
			}
			#auth_with_phone #button-resend-code,
			#auth_with_phone #button-change-telephone{
				font-size: 14px;
			    font-weight: 500;
			    margin-bottom: 10px;
			    text-decoration: underline;
			    cursor: pointer;
			}
			#auth_with_phone  .old-auth{
				text-decoration: underline;
			    text-align: center;
			    margin-top: 10px;
			}
			@media screen and (min-width: 1025px) {
				.simplecheckout_step_1 .simplecheckout-left-column {
				height: max-content;
				position: sticky;
				top: 5px;
				margin-bottom: 20px;
				}
			}
			@media screen and (max-width: 1440px) {
				#simplecheckout_summary .reward-code input, 
				#simplecheckout_summary .voucher-code input, 
				#simplecheckout_summary .promo-code input {
				    padding: 10px 9px 10px 25px;
				}
			}
			@media screen and (max-width: 768px) {
				.checkout-steps__select {
				padding-left: 0;
				}

				.simplecheckout_step_1 .simplecheckout-left-column,
				.simplecheckout-step.order-row.simplecheckout_step_2 .simplecheckout-left-column {
					margin-right: 0;
					margin-left: 0;
					padding: 15px;
				}
				#simplecheckout_payment > .checkout-steps__select-label,
				#simplecheckout_shipping > .checkout-steps__select-label{
					font-weight: 500;
					font-size: 20px;
					line-height: 150%;
					margin-bottom: 15px;
					padding-bottom: 15px;
					border-bottom: 1px solid #eae9e8;
					display: block;
					width: 100%;
				}
			}
			@media screen and (max-width:560px){
				#auth_with_phone .main-wrap .right-wrap{
					max-width: initial;
				}
				#simplecheckout_cart .order-cart__bottom{
					flex-direction: column;
				    justify-content: center;
				    align-items: center;
				    gap: 20px;
				    padding: 15px 0;
				}
				#simplecheckout_cart .order-cart__bottom #edit_cart_popap{
					justify-content: center;
				}
				#simplecheckout_cart .order-cart__bottom .total,
				#simplecheckout_cart .order-cart__bottom .edit{
					width: 100%;
				}
				#simplecheckout_customer .simplecheckout-block-content .field-group:first-child{
					flex: 0 0 100%;
				}
				.footer__payments .by-img{
					width: 100%;
				}
				.header-order .head-menu ul{
					display: none;
				}
				.header-order__contact-tel {
				    display: flex;
				    justify-content: end;
				}
				h1.title{
				    font-weight: 600;
				    font-size: 20px !important;
				    line-height: 25px !important;
				}
				#simplecheckout_payment > .checkout-steps__select-label, #simplecheckout_shipping > .checkout-steps__select-label{
				    font-size: 16px;
    				line-height: 19px;
				}
				.btn-group-register button{
					margin-bottom: 0;
				}
				.simplecheckout_step_2 .checkout-steps__info .checkout-steps__edit,
				.field-group.row-customer_register .field-label,
				.btn-group-register{
					margin-bottom: 15px;
				}
				#simplecheckout_customer .simplecheckout-block-content .field-group:not(:first-child) {
				    flex: 0 0 100%;
				}
				.field-group.row-shipping_novaposhta_warehouse, .field-group.row-shipping_novaposhta_flat, .field-group.row-shipping_novaposhta_house_number, .field-group.row-shipping_novaposhta_street, .field-group.row-shipping_novaposhta_warehous, .field-group.row-shipping_courier_city_shipping_address, .field-group.row-shipping_address_city, .field-group.row-customer_password, .field-group.row-customer_telephone, .field-group.row-customer_email, .field-group.row-customer_firstname, .login_tabs .field-group{
					max-width: 100%;
				}
				#simplecheckout_cart .order-cart__item{
					display: grid;
					grid-template-columns: 130px 1fr;
					grid-template-rows: auto auto;
					gap: 10px;
					padding: 10px;
					border-bottom: 1px solid #DDE1E4;
				}
				.simplecheckout-methods-table .simplecheckout-methods-items .quote {
				    font-size: 12px;
				    white-space: nowrap;
				}
				.tr_wrap .simplecheckout-methods-items .description{
					font-size: 12px;
				}
				#simplecheckout_cart .order-cart__item .product__photo{
					grid-column-start: 1;
					grid-column-end: 1;
					grid-row-start: 1;
					grid-row-end: 3;
				}
				#simplecheckout_cart .order-cart__item .product__title{
					grid-column-start: 2;
					grid-column-end: 2;
					grid-row-start: 1;
					grid-row-end: 1;
				}
				#simplecheckout_cart .order-cart__item .quantity{
					grid-column-start: 2;
					grid-column-end: 2;
					grid-row-start: 2;
					grid-row-end: 2;
				}
				#simplecheckout_cart {
				    position: unset;
				    display: flex;
				    flex-direction: column;
				    padding: 10px;
				    box-shadow: none;
				    margin: 0 0 20px 0;
				}
				.order-cart__item .product__price-new{
					text-align: left;
				}
				#simplecheckout_cart .order-cart__item .remove{
					right: 10px;
					top: 10px;
					left: initial;
				}
				#simplecheckout_cart .product__title a {
				    font-size: 14px;
				    line-height: 17px;
				    max-width: 85%;
				    -webkit-line-clamp: 2;
				}

				#simplecheckout_summary #simplecheckout-button-main-confirm,
				#simplecheckout_summary .cart__total_text span:last-of-type,
				#simplecheckout_summary .cart__total_text span:first-of-type,
				.simplecheckout-cart-total.voucher-code .title, .simplecheckout-cart-total.promo-code .title,
				.simplecheckout-methods-table .simplecheckout-methods-items .title label {
				    font-size: 14px !important;
				    line-height: 17px !important;
				}
			}
			@media screen and (max-width: 480px) {
				.header-order .drop-list{
					display: none;
				}
				.drop-list__btn{
					white-space: nowrap;
				}
				.header-order .drop-list__menu {
				    height: 0;
				    opacity: 0;
				    overflow: hidden;
				    position: absolute;
				    top: calc(100% + 15px);
				    left: 0;
				    right: 0;
				    width: 263px;
				    background: #fff;
				    box-shadow: 0 10px 20px rgb(0 0 0 / 10%);
				    transition: opacity 0.2s;
				    z-index: 299;
				    margin: auto;
				}
				.header-order .header-order .drop-list__menu.small_icon a {
				    display: flex;
				    align-items: center;
				    font-weight: 400;
				    font-size: 15px;
				    line-height: 20px;
				    color: #000000;
				    padding: 13px 20px;
				}
				.order-cart__item-right .reward_wrap{
				margin-top: 0
				}
				.order-cart__item .product__amount,
				.order-cart__item .product__price-new{
				text-align: center;
				}
				.order-cart__item-right {
				display: flex;
				align-items: center;
				}
				.simplecheckout-cart-total.reward-code .head .title .icon_info{
				background-image: none;
				display: flex;
				width: 100%;
				margin: 0;
				height: auto;
				}
				.simplecheckout-cart-total.reward-code .head .title .icon_info::before{
				content: "("attr(arial-label)")";
				opacity: 1;
				position: initial;
				display: flex;
				width: 100%;
				max-width: 100%;
				background: transparent;
				color: #333;
				padding: 0;
				font-size: 10px;
				}
				#simplecheckout_summary{
				padding: 10px
				}
				#simplecheckout_summary > .title{
				margin-bottom: 0
				}
				#report_bug_simple button,
				.cart__total .simplecheckout_button_confirm, .order-cart .btn {
				font-size: 18px !important;
				height: 46px !important;
				}
				.checkout-steps__title {
				font-size: 16px;
				}

				#simplecheckout_summary .reward-code input,
				#simplecheckout_summary .voucher-code input,
				#simplecheckout_summary .promo-code input {
				font-size: 12px;
				padding: 10px 10px 10px 27px;
				}
				.checkout-steps__item .link {
				font-size: 14px;
				}
				.simplecheckout-right-column {
				overflow: hidden;
				}
				.wrap {
				padding: 0 10px;
				}
				table tr{
				font-size: 14px;
				}
				.simplecheckout-methods-table td.title label.description_tr {
				font-size: 12px;
				line-height: 15px;
				}
				.simplecheckout-methods-table td.quote {
				width: 27% !important;
				}
				.simplecheckout-block-content {
				padding: 0 !important;
				}
				.quick_order_wrap #quick_order_simplecheckout_btn{
				font-size: 12px;
				}
				
				
				#content h1 {
				margin-bottom: 15px;
				margin-top: 15px;
				font-size: 23px;
				font-weight: 500;
				line-height: 1.1em;
				}
				#simplecheckout_cart #edit_cart_popap {
				font-size: 11px;
				line-height: 16px;
				}
				.checkout-steps__head {
					margin-top: 20px;
				}
				.order-cart .total {
				font-size: 14px;
				}
				.header-order__logo a {
				width: 100px;
				max-width: 100px !important;
				height: 40px;
				}
				.header-order__contact {
				height: auto;
				}
				.simplecheckout_step_1 .simplecheckout-left-column,
				.simplecheckout-step.order-row.simplecheckout_step_2 .simplecheckout-left-column {
				padding: 0;
				padding-bottom: 15px;
				}
				.btn-group-register {
				flex-direction: column;
				}
				.btn-group-register button {
				width: 100%;
				}
			}
		</style>


		<?php if (!empty($npmScriptsMinified)) { ?>
			<script src="<?php echo $npmScriptsMinified; ?>"></script>
		<?php } else { ?>
				<script src="<?php echo trim($this->config->get('config_static_subdomain')); ?>js/node_modules/jquery/dist/jquery.min.js"></script>
				<script src="<?php echo trim($this->config->get('config_static_subdomain')); ?>js/node_modules/jquery-ui/dist/jquery-ui.min.js"></script>
				<script src="<?php echo trim($this->config->get('config_static_subdomain')); ?>js/node_modules/select2/dist/js/select2.min.js"></script>
		<?php } ?>
				<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

		<script src="/js/node_modules/select2/dist/js/i18n/ru.js"></script>
		<script src="/js/node_modules/select2/dist/js/i18n/uk.js"></script>
		<script src="/catalog/view/theme/dp/js/sumoselect.min.js"></script>

	</head>
	<body class="<?php echo $body_class; ?>">
		<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/header_body_scripts.tpl')); ?>

		<style>
			@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap');
		</style> 

		<section class="header-order">
			<div class="wrap">
				<div class="box">
					<div class="header-order__logo">
						<a href="<?php echo $home; ?>" style="max-width:150px; width:150px">
							<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 176 60"><defs><style>.cls-1{fill:#020608;}.cls-2{fill:#6a979b;}.cls-3{fill:#e7b650;}.cls-4{fill:#d8b6cb;}.cls-5{fill:#d26244;}.cls-6{fill:#fff;}</style></defs><title>Domopolis</title><path class="cls-1" d="M66.62,16.81H77.81c7.47,0,11.6,3.57,11.6,10s-4.13,9.94-11.6,9.94H66.62V16.81ZM77.81,35.22c6.37,0,9.94-3,9.94-8.44s-3.57-8.44-9.94-8.44H68.29V35.22Z"/><path class="cls-1" d="M91.26,26.72C91.26,20.37,96.69,16,104.7,16s13.45,4.33,13.45,10.68-5.4,10.64-13.45,10.64S91.26,33.06,91.26,26.72Zm25.22,0c0-5.44-4.75-9.15-11.78-9.15s-11.77,3.71-11.77,9.15,4.75,9.14,11.77,9.14,11.78-3.71,11.78-9.14Z"/><path class="cls-1" d="M120.28,16.82h2.58L133.5,35.11h0l10.65-18.29h2.57V36.73h-1.67V18.58H145L134.46,36.73h-1.92L122,18.58H122V36.73h-1.67V16.82Z"/><path class="cls-1" d="M148.89,26.79c0-6.34,5.44-10.67,13.45-10.67s13.45,4.33,13.45,10.67-5.41,10.64-13.45,10.64S148.89,33.13,148.89,26.79Zm25.23,0c0-5.44-4.75-9.15-11.78-9.15s-11.78,3.71-11.78,9.15,4.76,9.14,11.78,9.14S174.12,32.22,174.12,26.79Z"/><path class="cls-1" d="M66.62,39.46h13.9c4.45,0,7,2.38,7,6.06s-2.58,6-7,6H68.29v7.82H66.62V39.46ZM80.35,50c3.52,0,5.5-1.75,5.5-4.5s-2-4.53-5.5-4.53H68.29v9Z"/><path class="cls-1" d="M89.33,49.28c0-6.35,5.44-10.68,13.45-10.68s13.45,4.33,13.45,10.68-5.41,10.64-13.45,10.64-13.45-4.3-13.45-10.64Zm25.23,0c0-5.44-4.76-9.15-11.78-9.15S91,43.84,91,49.28s4.76,9.14,11.78,9.14S114.56,54.71,114.56,49.28Z"/><path class="cls-1" d="M118.06,39.45h1.67v18.4h13.56v1.5H118.06V39.45Z"/><path class="cls-1" d="M135.12,39.45h1.67v19.9h-1.67V39.45Z"/><path class="cls-1" d="M138.62,53.16l1.42-.85c.93,3.65,5.24,6.08,10.65,6.08,4.72,0,7.81-1.78,7.81-4.35,0-2-1.81-3.23-6.06-3.91l-5.69-.94c-4.5-.71-6.43-2.09-6.43-4.81,0-3.54,3.43-5.78,8.44-5.78,4.39,0,8.1,1.87,9.54,4.65l-1.33.93c-1.27-2.41-4.44-4-8.21-4-4,0-6.77,1.67-6.77,4.16,0,1.79,1.5,2.81,5.07,3.34l5.61.91c5.24.82,7.5,2.44,7.5,5.32,0,3.68-3.79,6.06-9.54,6.06-6,0-10.73-2.71-12-6.76Z"/><path class="cls-2" d="M26.82.46A26.31,26.31,0,0,0,.51,26.77V59.15H26.82Z"/><path class="cls-3" d="M26.82.46h0V59.15H53.14V32.86h0V26.77A26.31,26.31,0,0,0,26.82.46Z"/><rect class="cls-4" x="26.82" y="37.29" width="26.29" height="21.86"/><rect class="cls-5" x="0.52" y="47.99" width="26.29" height="11.16"/><rect class="cls-6" x="0.21" y="47.31" width="26.6" height="0.92"/><rect class="cls-6" x="13.2" y="3.42" width="0.92" height="21.54"/><polygon class="cls-2" points="13.66 21.07 8.7 26.77 18.63 26.77 13.66 21.07"/><path class="cls-6" d="M19.64,27.23h-12l6-6.86,6,6.86Zm-9.93-.92h7.9l-3.95-4.54Z"/><rect class="cls-5" x="10.42" y="41.25" width="6.52" height="6.52"/><path class="cls-6" d="M17.4,48.23H10V40.79H17.4Zm-6.52-.92h5.6V41.72h-5.6Z"/><rect class="cls-6" x="26.81" y="36.83" width="26.55" height="0.92"/><rect class="cls-6" x="26.34" y="0.08" width="0.92" height="59.44"/><polygon class="cls-4" points="43.34 37.29 36.72 37.29 37.85 24.39 42.19 24.39 43.34 37.29"/><path class="cls-6" d="M43.85,37.75H36.21l1.22-13.82h5.18Zm-6.63-.92h5.62l-1.07-12H38.28l-1.06,12Z"/><path class="cls-6" d="M16.94,46.85v-.92a1.81,1.81,0,1,0,0-3.62v-.92a2.73,2.73,0,0,1,0,5.46Z"/><path class="cls-6" d="M39.59,27.75l-.69-.61a6.14,6.14,0,0,0-.29-8.89l.66-.65A7,7,0,0,1,39.59,27.75Z"/><path class="cls-6" d="M39.41,18.41l-.6-.09a5.94,5.94,0,0,1-3.07-1.52h0a5.92,5.92,0,0,1-1.52-3.07l-.09-.61.61.09a6,6,0,0,1,3.07,1.52,5.94,5.94,0,0,1,1.52,3.07l.08.61Zm-4.1-4.11a4.65,4.65,0,0,0,1.08,1.84h0a4.68,4.68,0,0,0,1.84,1.09,4.75,4.75,0,0,0-1.08-1.85,4.77,4.77,0,0,0-1.84-1.08Z"/><path class="cls-6" d="M41.08,23.06l-.25-.56a6,6,0,0,1-.37-3.41,6,6,0,0,1,1.76-2.93l.47-.4.26.56a5.9,5.9,0,0,1,.36,3.4,6,6,0,0,1-1.76,2.94l-.47.4Zm1.25-5.67a4.77,4.77,0,0,0-1,1.9,4.66,4.66,0,0,0,.08,2.14,4.77,4.77,0,0,0,1-1.9,4.66,4.66,0,0,0-.08-2.14Z"/></svg>
						</a>
					</div>
					<div class="head-menu">
						<ul>
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
							<li>
								<a href="<?php echo $href_track; ?>">
									<?php echo $text_retranslate_4; ?>
								</a>
							</li>
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
						</ul>	
					</div>
					
					<div class="header-order__contact">
						<div class="header-order__contact-tel">
							<a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone) ? $phone : ''; ?>"><?php echo isset($phone) ? $phone : ''; ?></a>
						</div>
						<div class="header-order__contact-tel">
							<a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone2) ? $phone2 : ''; ?>"><?php echo isset($phone2) ? $phone2 : ''; ?></a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<div style="display:none">
			<?php echo $cart; ?>
			<div class="search"><input type="text" name="search"></div>
		</div>

	<!--/order-header-->