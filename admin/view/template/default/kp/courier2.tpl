<html>
	<header>
		<? require_once(dirname(__FILE__).'/../common/pwa.tpl'); ?>
		
		<meta charset="utf-8">
		<meta name="viewport"
		content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><? echo $_title; ?></title>
		<script type="text/javascript" src="view/javascript/jquery/jquery-1.7.1.min.js"></script>
		<link rel="stylesheet" href="view/stylesheet/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	</header>
	<body>
		<style>
			.btn-copy {
		    background-image: url(data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDc3Ljg2NyA0NzcuODY3IiBzdHlsZT0iZmlsbDojNTFhODgxOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+ICA8Zz4gICAgPHBhdGggIGQ9Ik0zNDEuMzMzLDg1LjMzM0g1MS4yYy0yOC4yNzcsMC01MS4yLDIyLjkyMy01MS4yLDUxLjJ2MjkwLjEzM2MwLDI4LjI3NywyMi45MjMsNTEuMiw1MS4yLDUxLjJoMjkwLjEzMyAgICBjMjguMjc3LDAsNTEuMi0yMi45MjMsNTEuMi01MS4yVjEzNi41MzNDMzkyLjUzMywxMDguMjU2LDM2OS42MSw4NS4zMzMsMzQxLjMzMyw4NS4zMzN6IE0zNTguNCw0MjYuNjY3ICAgIGMwLDkuNDI2LTcuNjQxLDE3LjA2Ny0xNy4wNjcsMTcuMDY3SDUxLjJjLTkuNDI2LDAtMTcuMDY3LTcuNjQxLTE3LjA2Ny0xNy4wNjdWMTM2LjUzM2MwLTkuNDI2LDcuNjQxLTE3LjA2NywxNy4wNjctMTcuMDY3ICAgIGgyOTAuMTMzYzkuNDI2LDAsMTcuMDY3LDcuNjQxLDE3LjA2NywxNy4wNjdWNDI2LjY2N3oiLz4gIDwvZz48L2c+PGc+ICA8Zz4gICAgPHBhdGggIGQ9Ik00MjYuNjY3LDBoLTMwNy4yYy0yOC4yNzcsMC01MS4yLDIyLjkyMy01MS4yLDUxLjJjMCw5LjQyNiw3LjY0MSwxNy4wNjcsMTcuMDY3LDE3LjA2N1MxMDIuNCw2MC42MjYsMTAyLjQsNTEuMiAgICBzNy42NDEtMTcuMDY3LDE3LjA2Ny0xNy4wNjdoMzA3LjJjOS40MjYsMCwxNy4wNjcsNy42NDEsMTcuMDY3LDE3LjA2N3YzMDcuMmMwLDkuNDI2LTcuNjQxLDE3LjA2Ny0xNy4wNjcsMTcuMDY3ICAgIHMtMTcuMDY3LDcuNjQxLTE3LjA2NywxNy4wNjdzNy42NDEsMTcuMDY3LDE3LjA2NywxNy4wNjdjMjguMjc3LDAsNTEuMi0yMi45MjMsNTEuMi01MS4yVjUxLjIgICAgQzQ3Ny44NjcsMjIuOTIzLDQ1NC45NDQsMCw0MjYuNjY3LDB6Ii8+ICA8L2c+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg==);
		    width: 22px;
		    height: 14px;
		    background-color: transparent;
		    background-repeat: no-repeat;
		    background-size: contain;
		    background-position: center;
		    display: inline-flex;
		    margin-left: 5px;
		    cursor: pointer;
		    position: relative;
			}
			.tooltiptext{
			display: none;
			width: 116px;
			background-color: black;
			color: #fff;
			text-align: center;
			padding: 9px 6px;
			border-radius: 6px;
			position: absolute;
			z-index: 1;
			font-size: 14px;
			top: -50px;
			left: -83px;
			}
			.popup_rev {
			position: fixed;
			height: 100%;
			width: 100%;
			top: 0;
			left: 0;
			display: none;
			text-align: center;
			z-index: 99999999999;
			}
			
			.popup_bg_rev  {
			background: rgba(0, 0, 0, 0.64);
			position: absolute;
			z-index: 1;
			height: 100%;
			width: 100%;
			cursor: zoom-out;
			}
			
			.popup_rev .modal-content{
			position: absolute;
			margin: 50px auto;
			z-index: 2;
			max-height: 60%;
			left: 0;
			right: 0;
			top: 0;
			bottom: 0;
			border: 5px solid #fff;
			width: 96%;
			height: 400px;
			background: #fff;
			}
			
			.popup_img_rev{
			max-height: 100% !important;
			margin: 0 !important;
			width: 100%;
			object-fit: cover;
			}
			.overlay-popup-close{
			z-index: 3;
			}
			.overlay-popup-close {
			position: absolute;
			right: 10px;
			width: 25px;
			height: 25px;
			line-height: 20px;
			top: 10px;
			font-size: 15px;
			color: #2121217d;
			cursor: pointer;
			background-size: 11px 11px;
			background-repeat: no-repeat;
			border: 1px solid #000;
			border-radius: 50px;
			text-align: center;
			background-position: center;
			opacity: 0.5;
			z-index: 10;
			}
			.overlay-popup-close:before{
			content: 'x';
			position: absolute;
			right: 0;
			top: 0;
			bottom: 0;
			left: 0;
			margin: auto;
			font-size: 19px;
			}
			.popup_img_rev  {
			position: absolute;
			margin: 50px auto;
			z-index: 2;
			max-height: 60%;
			left: 0;
			right: 0;
			top: 0;
			bottom: 0;
			border: 5px solid #fff;
			
			}
			*{
			box-sizing: border-box;
			outline: 0;
			}
			body{font-family: 'Open Sans', sans-serif;font-size:14px;}
			table{font-size:14px;}
			.ptable thead td{font-weight:400;}
			.main-wrap{
			max-width: 600px;
			margin: auto;
			}
			.header-wrap{
			display: flex;
			justify-content: space-between;
			margin-bottom: 15px;
			}
			.header-wrap div{
			text-align: right;
			}
			.header-wrap div i{
			margin-right: 5px;
			}
			.header-wrap div span{
			color: #51a881;
			font-size: 13px;
			}
			.header-wrap div a{
			border-bottom: 1px dashed #51a881;
			font-size: 15px;
			color: #3f3f3f;
			text-decoration: none;
			margin-top: 5px;
			display: block;
			}
			.content-view{
			padding: 30px;
			background: #f7f4f459;
			border: 1px solid #e6e6e673;
			}
			.content-wrap .field-group{
			display: flex;
			align-items: center;
			}
			.content-wrap .field-label {
			font-size: 15px;
			white-space: nowrap;
			margin-right: 10px;
			}
			.content-wrap .field-group input{
			width: 100%;
			background: unset;
			border: unset;
			border-bottom: 1px solid;
			}
			tr.table_order_close td textarea{
			height: 80px !important;
			background: #fff;
			border: unset;
			border-radius: 2px;
			border-top: 1px solid #62626224;
			border-bottom: 1px solid #62626254;
			border-left: 1px solid #62626224;
			border-right: 1px solid #62626224;
			margin-right: 0;
			font-size: 15px;
			font-weight: 500;
			padding: 0 5px;	
			}
			#payment_amount, #order_status_id,
			tr.table_order_close td input{
			height: 36px;
			background: #fff;
			border: unset;
			border-radius: 2px;
			border-top: 1px solid #62626224;
			border-bottom: 1px solid #62626254;
			border-left: 1px solid #62626224;
			border-right: 1px solid #62626224;
			margin-right: 0;
			font-size: 15px;
			font-weight: 500;
			padding: 0 5px;	
			}
			#discount_card,
			.content-wrap .field-group #order_id, .input-telephone{
			width: calc(100% - 140px);
			height: 36px;
			background: #fff;
			border: unset;
			border-radius: 2px;
			border-top: 1px solid #62626224;
			border-bottom: 1px solid #62626254;
			border-left: 1px solid #62626224;
			border-right: 1px solid #62626224;
			margin-right: 10px;
			font-size: 15px;
			font-weight: 500;
			padding: 0 5px;			
			}
			.input-telephone{
			width:auto!important;
			}
			#close_order,
			#load_order{
			background: #51a881;
			color: #fff;
			padding: 20px 20px;
			text-align:center;
			border: 0;
			display: block;
			margin-left: auto;
			font-size: 16px;
			cursor: pointer;
			}
			#close_order{
			white-space: nowrap;
			}
			.content-view .button{
			margin-bottom: 10px;
			}
			#main_form{
			margin-top: 5px;
			padding: 30px;
			background: #f7f4f459;
			border: 1px solid #e6e6e673;
			}
			.span-decoraion{
			background: #bbffe070;
			color: #000000;
			font-size: 11px;
			padding: 1px 8px 3px 8px;
			border-radius: 3px;
			box-shadow: 0px 1px 2px #00000091;
			}
			.td-decoration{
			background: #bbffe070;
			}
			.ptable tbody tr:nth-of-type(2n+1){
			background: #f2f2f2;
			}
			table{
			border-collapse: collapse;
			
			}
			table a{
			text-decoration: none;
			color: inherit;
			border-bottom: 1px dashed;
			}
			.ptable tr td{
			padding: 3px;
			border: 1px solid #cdcdcd;
			}
			
			.click-load-order-id{
			background: #51a881;
			color: #fff;
			padding:10px 15px;
			float:left;
			margin-left:5px;
			margin-top:5px;
			border:0px;
			text-align: center;
			line-height: 1;
			}
			
			@media screen and (max-width: 580px) {
			
			.total td{
			font-size: 11px !important;
			padding-bottom: 5px;
			}
			.span-decoraion{
			margin-left: 10px;
			font-size: 14px;
			}
			.current_delivery{
			font-size: 14px;
			margin-bottom: 15px !important;
			margin-top: 15px !important;
			display: flex;
			align-items: center;
			}
			#main_form,
			.content-view {
			padding: 0px;
			}
			#discount_card{
			margin-right: 0;
			width: 100%;
			margin-top: 10px;
			}
			#discount_card, .content-wrap .field-group #order_id, .input-telephone {
			height: 40px;
			background: #fff;
			font-weight: 500;
			padding: 5 10px;
			}
			.content-wrap .field-group #order_id {
			width: calc(100% - 114px);
			margin-right: 0;
			}
			.content-view .field-group{
			flex-wrap: wrap;
			}
			#close_order,
			#load_order {		
			width: 100%;
			margin-top: 9px;
			}				
			#main_form .edit-table tr{
			display: flex;
			flex-direction: column;
			margin-bottom: 6px;
			}
			#main_form .edit-table tr td{
			font-size: 14px;
			}	
			.mb-style{
			width: auto !important;
			}
			
			.ptable{
			display: block;
			width: 100%;
			overflow-x: auto;
			}
			tr.table_order_close{
			display: flex;
			flex-direction: column;
			/*margin-top: 10px;*/
			}
			#payment_amount{
			margin-right: 5px;
			}
			#payment_amount,
			#order_status_id{
			height: 33px;
			font-size: 14px;
			font-weight: 500;
			padding: 0 10px;
			background: #fff;
			border: unset;
			border-radius: 2px;
			border-top: 1px solid #62626224;
			border-bottom: 1px solid #62626254;
			border-left: 1px solid #62626224;
			border-right: 1px solid #62626224;
			}
			tr.table_order_close td input{
			width: 100%;
			height: 33px;
			background: #fff;
			font-size: 14px;
			font-weight: 500;
			padding: 0 10px;
			background: #fff;
			border: unset;
			border-radius: 2px;
			border-top: 1px solid #62626224;
			border-bottom: 1px solid #62626254;
			border-left: 1px solid #62626224;
			border-right: 1px solid #62626224;
			}
			tr.table_order_close td textarea{
			width: 100%;
			height: 50px;
			background: #fff;
			margin-top: 0px !important;
			border: unset;
			border-radius: 2px;
			border-top: 1px solid #62626224;
			border-bottom: 1px solid #62626254;
			border-left: 1px solid #62626224;
			border-right: 1px solid #62626224;
			font-size: 14px;
			font-weight: 500;
			padding: 10px;
			}
			.cash_table tr:nth-of-type(2){
			display: flex;
			align-items: center;
			/*display: grid;
			grid-template-columns: 1fr 1fr;
			grid-template-rows: 1fr 1fr;*/
			}
			.additionally_table{
			width: 100%;
			}
			.additionally_table .content-inline{
			display: flex;
			flex-direction: row-reverse;
			justify-content: flex-end;
			margin: 10px 0;
			}
			.additionally_table tr td,
			.cash_table tr td, 
			.status_table tr td{
			font-size: 14px;
			display: flex;
			align-items: center;
			margin-bottom: 5px;
			line-height: 1;
			}
			/*.cash_table tr td:nth-of-type(1),*/
			.status_table tr td:nth-of-type(1){
			grid-column-start: 1;
			grid-column-end: 3;
			}
			/*.cash_table tr td:nth-of-type(2),*/
			.status_table tr td:nth-of-type(2){
			grid-column-start: 1;
			grid-column-end: 1;
			grid-row-start: 2;
			grid-row-end: 2;
			}
			/*.cash_table tr td:nth-of-type(3),*/
			.status_table tr td:nth-of-type(3){
			grid-column-start: 2;
			grid-column-end: 2;
			grid-row-start: 2;
			grid-row-end: 2;
			}
			/*.cash_table tr td:nth-of-type(3){*/
			justify-content: center;
			}
			.ptable tr td,
			.ptable thead td {
			font-weight: 400;
			font-size: 14px !important;
			padding: 5px 10px !important;
			}
			.ptable {
			padding-bottom: 10px;
			}
			
			}
		</style>
		<div class="main-wrap">
			<div class="header-wrap">
				<div><img height="40px" src="view/image/<? echo FILE_LOGO; ?>" /></div>
				<div>
					<span><i class="fa fa-truck icon_menu"></i> <?php echo $username; ?> (<? echo SITE_NAMESPACE; ?>)</span>
					<?php if (!$just_courier) { ?>
						<br /><a href="<? echo $fullUrl; ?>">Выйти в полный интерфейс</a>
					<?php } ?>
				</div>
				
			</div>
			<div class="content-wrap">
				<style>
					.bg-green{background-color:#51a881!important;}
					.bg-red{background-color:#ff5656!important;}
				</style>
				<?php if ($my_orders) { ?>
					<div class="content-view">					
						<?php foreach ($my_orders as $order) { ?>
							<a class="click-load-order-id courier-order-list bg-green button" id="order-<?php echo $order['order_id']; ?>" onclick="$('#order_id').val('<?php echo $order['order_id']; ?>'); $('#load_order').trigger('click'); return false;"><?php echo $order['order_id']; ?></a>
						<?php } ?>			
						<div style="clear:both"></div>
					</div>
				<?php } ?>
				
				<div class="content-view">
					<div class="field-group">						
						<input type="text" id="order_id" style="font-size:24px; width:100%; text-align:center; padding:10px; border:1px solid #51a881;" placeholder="Номер заказа" />						
						<a class="click-load-order-id button bg-green" style="width:100%; padding-top:20px; padding-bottom:20px;" id="load_order"><i class="fa fa-refresh"></i> Загрузить заказ</a>
					</div>					
				</div>
				
				
				<div id="order_info" style="padding-bottom:5px; margin-bottom:5px;"></div>
				<div id="result"></div>
				<script>
					
					function loadOrder(){
						
						$('#load_order i').removeClass('fa-refresh').addClass('fa-spinner fa-spin'); 
						$('#order_info').load('<?php echo $getOrderAjaxUrl ?>&order_id='+$('#order_id').val(), function(){ 
							$('#load_order i').removeClass('fa-spinner fa-spin').addClass('fa-refresh');
							$('.courier-order-list').each(function(i, el){$(this).removeClass('bg-red');});
							$('#order-' + $('#order_id').val()).addClass('bg-red');
						});
						
					}
					
					function openImg(e){
						
						var img = $(e);  
						var src = img.attr('src');
						$("body").append("<div class='popup_rev' style='background: #00000057;'>"+ 
						"<div class='modal-content cart-popap-modal'><div class='overlay-popup-close btn-modal'></div><img src='"+src+"' class='popup_img_rev' /><div>"+ 
						"</div>"); 
						$('.popup_bg_rev').fadeIn(300);
						$(".popup_rev").fadeIn(300); 
						$(".popup_bg_rev, .btn-modal").click(function(){  
							$(".popup_rev").fadeOut(300); 
							$('.popup_bg_rev').fadeOut(300);
							$(img).empty();
							$(".popup_rev").remove();
							setTimeout(function() { 
								$(".popup_rev").remove();
							}, 300);
						});
					}
					
					
					function copytext(el) {
						var $tmp = $("<textarea>");
						$("body").append($tmp);
						$tmp.val($(el).text()).select();
						document.execCommand("copy");
						$tmp.remove();
						$(el).next('.btn-copy').find('.tooltiptext').show();
						setTimeout(function(){
							$(el).next('.btn-copy').find('.tooltiptext').hide();
						}, 1500);
					} 
					
					$('#load_order i').removeClass('fa-spinner fa-spin').addClass('fa-refresh');
					$('#load_order').click(function(){loadOrder();});
					
				</script>
			</div>
		</div>
	</body>
</html>