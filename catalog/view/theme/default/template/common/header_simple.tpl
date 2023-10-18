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
				background-image: url(/catalog/view/theme/kp/img/load_more.svg);
			}
			.simplecheckout-warning-block{
				background: #e16a5d !important;
				color: #fff !important;
				font-weight: 500 !important;
				border: 0 !important;
				padding: 10px 20px !important;
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
			.order-cart__item-right .reward_wrap .text{
			font-size: 13px;
			/*padding-left: 23px;*/
			border: 1px dashed #51a881;
			padding: 5px 5px 5px 30px;
			}
			.order-cart__item-right .reward_wrap .text b{
			color: #51a881;
			font-weight: 600;
			margin-right: 2px;
			}
			.order-cart__item-right .reward_wrap .text::before{
			content: '';
			background-image: url("/catalog/view/theme/kp/img/money.svg");
			background-size: contain;
			width: 20px;
			height: 18px;
			display: inline-block;
			background-repeat: no-repeat;
			position: absolute;
			left: 7px;
			top: 0;
			bottom: 0;
			margin: auto;
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
			.wrap{position:relative;max-width:1600px;width:100%;padding:0 20px;margin:0 auto;height:100%}
			.wrap:after{clear:both;content:"";display:block}
			#footer_app_google_play,#footer_app{margin-bottom: 0;}

			.b24-widget-button-wrapper{display: none !important;}
			.checkout .field-label {
			text-align: left;
			width: 205px;
			}
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
			width: 30%;
			}
			.simplecheckout-step.order-row.simplecheckout_step_2 #simplecheckout_summary {
			position: sticky;
			top: 5px;
			}
			.simplecheckout-step.order-row.simplecheckout_step_2 .simplecheckout-left-column {
			margin-right: 1%;

			max-width: 1010px;
			width: 69%;
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
			justify-content: space-between;
			}

			.btn-group-register button {
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
			transition: 0.15s ease-in-out;
			outline: none !important;
			font-weight: 500;
			letter-spacing: 0.21px;
			height: 54px;
			border: 1px solid #51a881;
			}
			.btn-group-register button .btn-img {
			max-width: 46px;
			width: 46px;
			padding: 0;
			margin-right: 13px;
			}
			.btn-group-register button:last-child {
			margin-bottom: 0;
			}
			.btn-group-register button .btn-img {
			max-width: 46px;
			width: 46px;
			padding: 0;
			margin-right: 13px;
			}
			.btn-group-register .fa-facebook-f {
			color: #0385c1;
			position: initial;
			background: transparent;
			height: auto;
			font-size: 18px;
			}
			h2.header_btn_register {
			font-size: 16px;
			font-weight: 400;
			margin-bottom: 10px;
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
			flex-basis: 35%;
			margin-left: auto;
			max-height: 40px;
			height: 40px;
			}
			#customer_telephone {
			flex-basis: 60%;
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
				.footer__payments .by-img{
					width: 100%;
				}
			}
			@media screen and (max-width: 480px) {
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
			.header-order__contact-tel {
			font-size: 15px;
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
			height: 37px;
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
		
		<script src="/js/node_modules/select2/dist/js/i18n/ru.js"></script>
		<script src="/js/node_modules/select2/dist/js/i18n/uk.js"></script>
		<script src="/catalog/view/theme/kp/js/sumoselect.min.js"></script>

	</head>
	<body>
		<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/header_body_scripts.tpl')); ?>

		<style>
			@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap');
		</style> 

		<section class="header-order">
			<div class="wrap">
				<div class="box">
					<div class="header-order__logo">
						<a href="<?php echo $home; ?>" style="max-width:150px; width:150px">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 706.49 296.83" style="height: 40px;">
								<defs>
								<style>.cls-1{fill:#4b4948;}.cls-2,.cls-7{fill:none;}.cls-2{stroke:#fff;stroke-miterlimit:10;stroke-width:0.5px;}.cls-3{fill:#57ac79;}.cls-4{fill:#e1675d;}.cls-5{fill:#fff;}.cls-6{fill:#fbc04f;}</style></defs>
								<g id="Слой_1" data-name="Слой 1">
								<path class="cls-1" d="M289.36,175.42V38.74H314V175.42Z" transform="translate(-38.44 -38)"/><path class="cls-1" d="M353.65,175.42V57.13H331.88V38.74h68.17V57.13H378.62V175.42Z" transform="translate(-38.44 -38)"/><path class="cls-1" d="M447.58,178.05q-15,0-23.54-5.9A30.79,30.79,0,0,1,412.15,156a74.93,74.93,0,0,1-3.38-23.54V83.73q0-14,3.38-24.3A29.12,29.12,0,0,1,424,43.57Q432.56,38,447.58,38q14.18,0,22.19,4.81a26.44,26.44,0,0,1,11.39,13.75,58.61,58.61,0,0,1,3.38,20.76V88.79h-24V77a83.5,83.5,0,0,0-.6-10.46,13,13,0,0,0-3.2-7.51c-1.75-1.86-4.75-2.79-9-2.79s-7.4,1-9.36,3a14.21,14.21,0,0,0-3.8,7.93,67.86,67.86,0,0,0-.84,11.22v59.39A52.51,52.51,0,0,0,434.93,150a12.66,12.66,0,0,0,4.3,7.25c2.08,1.64,4.92,2.45,8.52,2.45q6.24,0,8.86-2.95a14.57,14.57,0,0,0,3.29-7.85,80.73,80.73,0,0,0,.68-11V125.57h24v11a66.31,66.31,0,0,1-3.21,21.52,28,28,0,0,1-11.22,14.68Q462.09,178.05,447.58,178.05Z" transform="translate(-38.44 -38)"/><path class="cls-1" d="M502.58,175.42V38.74h24.81v57.2h27.84V38.74h25V175.42h-25V113.66H527.39v61.76Z" transform="translate(-38.44 -38)"/><path class="cls-1" d="M598,175.42V38.74h57.37V56.29h-32.4V95.61h25.48V113H622.93v45.22h32.74v17.21Z" transform="translate(-38.44 -38)"/><path class="cls-1" d="M673.63,175.42V38.74H691l33.24,78v-78h20.58V175.42H728.3L694.89,93.75v81.67Z" transform="translate(-38.44 -38)"/><path class="cls-1" d="M353.64,333.14V196.46H393.8q12.31,0,20,4.56a27,27,0,0,1,11.31,13.25q3.61,8.68,3.62,21,0,13.33-4.55,21.52a27.15,27.15,0,0,1-12.74,11.89,46.38,46.38,0,0,1-19.32,3.71h-13.5v60.75Zm25-78.29h9.45q6.75,0,10.55-1.94a10.42,10.42,0,0,0,5.23-6.25,37.87,37.87,0,0,0,1.43-11.56,54.13,54.13,0,0,0-1.1-12.06,10.34,10.34,0,0,0-4.72-6.83q-3.63-2.19-11.39-2.2h-9.45Z" transform="translate(-38.44 -38)"/><path class="cls-1" d="M446.78,333.14V196.46h32.73q13.85,0,23.37,3.29A26.83,26.83,0,0,1,517.39,211q5,7.92,5,21.59a65.72,65.72,0,0,1-1.52,14.85,28.15,28.15,0,0,1-5.14,11.22,23.13,23.13,0,0,1-10,7.17l19.07,67.33h-25l-16.53-62.6H471.75v62.6Zm25-78.29h7.76q7.26,0,11.56-2.11a12.35,12.35,0,0,0,6.16-6.75,32.33,32.33,0,0,0,1.86-11.9q0-10.29-3.8-15.44t-14.43-5.14h-9.11Z" transform="translate(-38.44 -38)"/><path class="cls-1" d="M583.61,334.83q-14.52,0-23.12-5.4a29.82,29.82,0,0,1-12.32-15.53q-3.7-10.12-3.71-24V238.82q0-13.84,3.71-23.71A29.05,29.05,0,0,1,560.49,200q8.61-5.23,23.12-5.23,14.83,0,23.36,5.23a29.63,29.63,0,0,1,12.32,15.1q3.8,9.87,3.8,23.71v51.29q0,13.67-3.8,23.71A30.85,30.85,0,0,1,607,329.34Q598.45,334.83,583.61,334.83Zm0-18.4c4.16,0,7.22-.89,9.19-2.7a13.57,13.57,0,0,0,4-7.42,49.46,49.46,0,0,0,1-10.29V233.08a47.91,47.91,0,0,0-1-10.29,13,13,0,0,0-4-7.17q-3-2.62-9.19-2.62-5.91,0-9,2.62a12.83,12.83,0,0,0-4,7.17,47.91,47.91,0,0,0-1,10.29V296a53.33,53.33,0,0,0,.93,10.29,12.9,12.9,0,0,0,4,7.42C576.52,315.54,579.56,316.43,583.61,316.43Z" transform="translate(-38.44 -38)"/><path class="cls-1" d="M645.55,333.14V196.46h56.18v17.21H670.52V253.5H696v17.38H670.52v62.26Z" transform="translate(-38.44 -38)"/><path class="cls-1" d="M720.3,333.14V196.46h24.63V333.14Z" transform="translate(-38.44 -38)"/><path class="cls-2" d="M69.39,280.22" transform="translate(-38.44 -38)"/><path class="cls-1" d="M196.13,175.42V38.74h25V98.81l27-60.07h23.79L245.73,99.82l28.86,75.6H249.78l-22.94-62.27-5.74,10.47v51.8Z" transform="translate(-38.44 -38)"/><path class="cls-3" d="M175,90.6V38.9h0V90.6Z" transform="translate(-38.44 -38)"/><path class="cls-3" d="M175,114.44V93.57h0v20.88Z" transform="translate(-38.44 -38)"/><path class="cls-3" d="M174.92,90.6V38.9H38.44V175.42H90.15A93.89,93.89,0,0,1,174.92,90.6Z" transform="translate(-38.44 -38)"/><path class="cls-3" d="M174.92,114.45V93.57a91,91,0,0,0-81.8,81.85H114A70.16,70.16,0,0,1,174.92,114.45Z" transform="translate(-38.44 -38)"/><path class="cls-3" d="M115.49,175.42h59.43V115.94A68.68,68.68,0,0,0,115.49,175.42Z" transform="translate(-38.44 -38)"/><path class="cls-3" d="M174.92,115.94v59.48h0V115.94Z" transform="translate(-38.44 -38)"/><rect class="cls-4" x="136.47" y="158.38" width="0.04" height="0.04"/><path class="cls-4" d="M174.92,196.42v80.87A93.91,93.91,0,0,1,90.6,196.42H38.44V332.89H175V196.42Z" transform="translate(-38.44 -38)"/><path class="cls-4" d="M90.59,196.38H38.44v0H90.6Z" transform="translate(-38.44 -38)"/><path class="cls-4" d="M114.58,196.42h-21a91,91,0,0,0,81.34,77.9V253.44A70.18,70.18,0,0,1,114.58,196.42Z" transform="translate(-38.44 -38)"/><path class="cls-4" d="M114.57,196.38h-21v0h21Z" transform="translate(-38.44 -38)"/><path class="cls-4" d="M174.92,252V196.42H116.08A68.71,68.71,0,0,0,174.92,252Z" transform="translate(-38.44 -38)"/><path class="cls-4" d="M116.08,196.42h58.84v0H116.07Z" transform="translate(-38.44 -38)"/><path class="cls-5" d="M93.58,196.42v0h-3v0Z" transform="translate(-38.44 -38)"/><path class="cls-5" d="M116.08,196.42v0h-1.5v0Z" transform="translate(-38.44 -38)"/><path class="cls-6" d="M196.17,196.38H196v55l.22,0Z" transform="translate(-38.44 -38)"/><path class="cls-6" d="M250.91,196.38H196.17v54.93A68.71,68.71,0,0,0,250.91,196.38Z" transform="translate(-38.44 -38)"/><path class="cls-6" d="M196,276.86v56h.22V276.83Z" transform="translate(-38.44 -38)"/><path class="cls-6" d="M332.46,196.38h-56a93.93,93.93,0,0,1-80.24,80.45v56.06H332.46Z" transform="translate(-38.44 -38)"/><path class="cls-7" d="M196,273.87v-21A71,71,0,0,1,183.5,254a69.34,69.34,0,0,1-8.58-.55v20.88q4.23.4,8.58.41A91.34,91.34,0,0,0,196,273.87Z" transform="translate(-38.44 -38)"/><path class="cls-6" d="M273.42,196.38h-21a70.21,70.21,0,0,1-56.24,56.44v21A91,91,0,0,0,273.42,196.38Z" transform="translate(-38.44 -38)"/><path class="cls-6" d="M196,252.86v21l.22,0v-21Z" transform="translate(-38.44 -38)"/><path class="cls-5" d="M196.17,273.84l-.22,0v3l.22,0Z" transform="translate(-38.44 -38)"/><path class="cls-5" d="M196.17,252.82v-1.51l-.22,0v1.51Z" transform="translate(-38.44 -38)"/></g></svg>
						</a>
					</div>
					
					<?php if (ADMIN_SESSION_DETECTED && !empty($user)) { ?>
						<div class="header-admin__user">
							<i class="fas fa-user"></i> <?php echo $user['realname']; ?>
							<?php if ($user['own_orders']) { ?>
								<br /><span class="alert alert-success alert-no-padding">Заказ будет присвоен</span>
							<?php } ?>
						</div>					
					<?php } ?>
					
					<div class="header-order__contact">
						<div class="header-order__contact-title"><?php echo $text_retranslate_if_questions;?></div>
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