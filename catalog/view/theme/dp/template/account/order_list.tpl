<?php echo $header; ?>
<?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<style>
	.ui-dialog.ui-corner-all{
	    padding: 15px;
		z-index: 1000 !important; 
	    background-color: #fff;
	    border: 0;
	}
	.ui-dialog.ui-corner-all .ui-button.ui-dialog-titlebar-close{
		opacity: 0;
	}
	.ui-dialog.ui-corner-all .close_modal_order{
		position: absolute;
	    right: 10px;
	    width: 25px;
	    height: 25px;
	    line-height: 25px;
	    top: 10px;
	    font-size: 0;
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
	.ui-dialog.ui-corner-all .ui-dialog-title{
		font-size: 18px;
		font-weight: bold;
		margin-bottom: 20px;
		display: block;
	}
	.ui-dialog.ui-corner-all  .list tbody tr td{
		padding: 3px 5px;
	}
	.ui-dialog.ui-corner-all  .list tbody tr td i{
		color: #51a62d;
	}
	.ui-dialog.ui-corner-all  .list tbody tr:not(:first-child) td{
		border: 1px solid #eee;
	}
	.ui-dialog.ui-corner-all  .list tbody tr:nth-child(2n) td {
	  background: #f8f8f8 !important;
	}
	.ui-dialog.ui-corner-all  .list:not(:last-child){
		margin-bottom: 20px;
	}
	.ui-dialog.ui-corner-all .ui-widget-content{
		border: 0 !important;
	}
	.overlay_order{
		display: none;
		position: fixed;
		z-index: 999;
		top: 0;
		right: 0;
		left: 0;
		bottom: 0;
		background: #000;
		opacity: .5;
	}
	.tracker-order-info{		
	width:100% !important;
	float:none !important;
	}
	
	.tracker-order-info ul{
	padding-left:5px;
	padding-right:5px;
	}
	
	.tracker-order-info ul li{
	display: inline-block;
	width: 105px;
	margin: 0 10px 10px 0;
	vertical-align: top;
	text-align: center;
	padding: 5px 0 0;
	font-size:11px;
	}
	
	.tracker-order-info ul li i{
	font-size:32px !important;
	}
	
	.tracker-order-info ul li.undone{
	color:#7F7F7F;
	}
	
	.tracker-order-info ul li.done{
	color:#7cc04b;
	}
	.order-products-wrap{
	display: grid;
	grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
	grid-column-gap: 20px;
	grid-row-gap: 20px;
	}
	.order-products-wrap .order-product{
	text-align: center;
	border: 1px solid #dbdbdb;
	padding: 20px;
	background: #fff;
	}
	.order-product-price{
	margin-top: 5px;
	}
	
	/* accordion_list_order */
	.accordion_list_order{
	display: flex;
	flex-direction: column;
	}
	.accordion_list_order .order_item{
	padding: 16px;
	background: #fff;
	border-radius: 4px;
	margin-bottom: 10px;
	box-shadow: 0px 10px 36px rgb(10 32 45/12%);
	}
	.accordion_list_order .order_item:last-child{
	margin-bottom: 0;
	}
	.accordion_list_order .order_item .head{
	position: relative;
	padding-left: 24px;
	padding-right: 24px;
	display: flex;
	flex-direction: row;
	align-items: center;
	min-height: 40px;
	}
	.accordion_list_order .order_item .head::before{
	content: '';
	position: absolute;
	left: 0;
	top: 0;
	display: block;
	height: 100%;
	width: 8px;
	border-radius: 3px;
	transition: all .2s ease-in-out;
	}
	.accordion_list_order .order_item.completed .head::before{
	background-color: #51a881;
	}
	.accordion_list_order .order_item.canceled .head::before{
	background-color: rgb(210, 210, 210);
	}
	
	.accordion_list_order .order_item .head .about_order{
	width: 40%;
	display: flex;
	flex-direction: column;
	}
	.accordion_list_order .order_item .head .about_order .number{
	font-size: 12px;
	color: #797878;
	}
	.accordion_list_order .order_item .head .about_order .order-status{
	font-size: 14px;
	font-weight: 500;
	}
	.accordion_list_order .order_item .head .about_order .order-status i{
		cursor: pointer;
		color: #51a62d;
	}
	.accordion_list_order .order_item .head .tottal_order{
	width: 20%;
	}
	.accordion_list_order .order_item .head .tottal_order .text{
	font-size: 12px;
	color: #797878;
	}
	.accordion_list_order .order_item .head .tottal_order .value{
	font-size: 14px;
	font-weight: 500;
	}
	.accordion_list_order .order_item .head .list_product{
	justify-content: flex-end;
	width: 40%;
	margin-right: 24px;
	display: flex;
	flex-direction: row;
	}
	.accordion_list_order .order_item .head .list_product .product_item{
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: center;
	width: 40px;
	height: 40px;
	margin-left: 20px;
	}
	.accordion_list_order .order_item .head .list_product .product_item:nth-of-type(n+4){
	display: none;
	}
	.accordion_list_order .order_item .head .list_product .product_item:first-child{
	margin-left: 0;
	}
	.accordion_list_order .order_item .head .list_product .product_item img{
	max-width: 100%;
	width: auto;
	max-height: 100%;
	height: auto;
	}	
	.accordion_list_order .order_item .head .btn_detail{
	position: absolute;
	right: 0;
	top: 8px;
	width: 24px;
	background: transparent;
	border: 0;
	padding: 0;
	transition: .15s ease-in-out;
	}
	.accordion_list_order .order_item .detail_order{
	display: none;
	}
	.accordion_list_order .order_item.open .head .list_product,
	.accordion_list_order .order_item.open .head .tottal_order{
	display: none;
	}
	.accordion_list_order .order_item.open .head .btn_detail{
	transform: rotate(180deg);
	}
	.accordion_list_order .order_item.open .detail_order{
	display: flex;
	flex-direction: row;
	padding-left: 24px;
	padding-top: 16px;
	}
	.accordion_list_order .order_item .order-content{
	width: 35%;
	margin-bottom: 0;
	padding-right: 24px;
	}
	.accordion_list_order .order_item .order-content h4.title{
	display: flex;
	flex-direction: row;
	align-items: center;
	margin-bottom: 16px;
	font-size: 16px;
	color: #797878;
	}
	.accordion_list_order .order_item .order-content > div{
	display: flex;
	justify-content: space-between;
	margin-bottom: 10px;
	}
	.accordion_list_order .order_item .order-content > div .text{
	font-size: 14px;
	margin: 0;	
	
	}
	.accordion_list_order .order_item .order-content > div .value{
	font-size: 14px;
	margin: 0;
	font-weight: 500;
	}
	.accordion_list_order .order_item .order-products-table{
	width: 65%;
	display: flex;
	flex-direction: column;
	}
	.accordion_list_order .order_item .order-products-table .order-details__header h4{
	display: flex;
	flex-direction: row;
	align-items: center;
	margin-bottom: 16px;
	font-size: 14px;
	color: #a8a8a8;
	font-weight: 500;
	}
	.accordion_list_order .order_item .order-products-table ul{
	display: flex;
	flex-direction: column;
	padding: 0;
	margin: 0;
	list-style: none;
	}
	.accordion_list_order .order_item .order-products-table ul li{
	display: flex;
	flex-direction: row;
	align-items: flex-start;
	padding-bottom: 15px;
	}
	.accordion_list_order .order_item .order-products-table ul li a{
	width: 40%;
	margin-bottom: 0;
	display: flex;
	align-items: center;
	font-size: 12px;
	line-height: 17px;
	}
	.accordion_list_order .order_item .order-products-table ul li a .img-wrap{
	position: relative;
	justify-content: center;
	flex-shrink: 0;
	width: 56px;
	height: 56px;
	margin-right: 16px;
	}	
	.accordion_list_order .order_item .order-products-table ul li a .img-wrap img{
	width: auto;
	max-width: 100%;
	height: auto;
	max-height: 100%;
	}
	.accordion_list_order .order_item .order-products-table ul li a p{
	color: #e25c1d;
	margin-bottom: 0;
	font-weight: 500;
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	overflow: hidden;
	text-overflow: ellipsis;
	}
	.accordion_list_order .order_item .order-products-table ul li a:hover p{
	text-decoration: underline;
	}
	.accordion_list_order .order_item .order-products-table ul li .order-product-price{
	width: 60%;
	display: flex;
	flex-direction: row;
	align-items: center;
	margin-top: 0;
	}
	.accordion_list_order .order_item .order-products-table ul li .order-product-price .order-product-price_item{
	text-align: center;
	flex-grow: 1;
	}
	.accordion_list_order .order_item .order-products-table ul li .order-product-price .order-product-price_item.count{
	flex-grow: 0;
	padding-left: 8px;
	padding-right: 8px;
	}
	.accordion_list_order .order_item .order-products-table ul li .order-product-price .order-product-price_item .text{
	display: block;
	margin-bottom: 4px;
	color: #797878;
	}
	.accordion_list_order .order_item .order-products-table ul li .order-product-price .order-product-price_item .value{
	font-size: 14px;
	font-weight: 500;
	}
	
	.accordion_list_order .order_item .order-products-table .total_wrap{
	display: flex;
	flex-direction: column;
	margin-bottom: 15px;
	}
	.accordion_list_order .order_item .order-products-table .total_wrap .total_item{
	display: flex;
	flex-direction: row;
	justify-content: space-between;
	align-items: flex-end;
	font-size: 14px;
	margin-bottom: 5px;
	}
	.accordion_list_order .order_item .order-products-table .total_wrap .total_item:last-child{
	margin-bottom: 0;
	}
	.accordion_list_order .order_item .order-products-table .total_wrap .total_item .text{
	display: flex;
	flex-direction: row;
	align-items: center;
	font-size: 14px;
	color: #797878;
	}
	.head_tab{
		margin-bottom: 25px;
		background: #fff;
		padding: 10px 15px 0;
	}
	.head_tab ul{
		display: flex;
		align-items: center;
		justify-content: space-between;
		list-style: none;
	}
	.head_tab ul li{
		
	}
	.head_tab ul li a{
		display: inline-block;
		padding-bottom: 10px;
		font-size: 17px;
		padding-left: 5px;
		padding-right: 5px;
		border-bottom: 3px solid transparent;
	}
	.head_tab ul li a.active{
		color: #51a881;
		font-weight: 500;
		border-bottom: 3px solid #51a881;
	}
	@media screen and (max-width: 1200px){
	.order-products-wrap{
	grid-template-columns: 1fr 1fr 1fr 1fr;
	}
	}
	@media screen and (max-width: 992px){
		.order-products-wrap{
			grid-template-columns: 1fr 1fr 1fr;
		}
	}
	@media screen and (max-width: 768px){
		.order-products-wrap{
			grid-template-columns: 1fr 1fr;
		}
		.ui-dialog.ui-corner-all{
			width: 100% !important;
		}
		.ui-dialog.ui-corner-all .list tbody tr td {
		  font-size: 12px;
		}
	}
	@media screen and (max-width: 480px){
		.accordion_list_order .order_item .order-products-table ul li .order-product-price .order-product-price_item .value {
		  font-size: 11px;
		}
		.accordion_list_order .order_item .order-products-table ul li,
		.accordion_list_order .order_item.open .detail_order{
			flex-direction: column;
		}
		.accordion_list_order .order_item .order-content{
			padding-right: 0;
			margin-bottom: 10px
		}
		.head_tab {
		    margin-bottom: 15px;
		    padding: 10px 6px 0;
		}
		.head_tab ul li a {
		    font-size: 12px;
		    padding-left: 2px;
		    padding-right: 2px;
		}
		.head_tab ul li a,
		.accordion_list_order .order_item .order-content > div .text,
		.accordion_list_order .order_item .order-content > div .value,
		.accordion_list_order .order_item .order-products-table .total_wrap .total_item,
		.accordion_list_order .order_item .order-products-table ul li .order-product-price .order-product-price_item{
			font-size: 12px;
		}
		.accordion_list_order .order_item .order-products-table .total_wrap .total_item .value p{
			display: inline-block;
			text-align: right;
			line-height: 16px;
		}
		.accordion_list_order .order_item .order-content,
		.accordion_list_order .order_item .order-products-table ul li .order-product-price,
		.accordion_list_order .order_item .order-products-table ul li a,
		.accordion_list_order .order_item .order-products-table,
		.accordion_list_order .order_item .head .tottal_order,
		.accordion_list_order .order_item .head .about_order {
    		width: 100%;
		}
		.order-products-wrap{
			display: grid;
			grid-row-gap: 10px;
		}
		.order-products-wrap{
			grid-template-columns: 1fr;
		}
		.accordion_list_order .order_item .head{
			flex-direction: column;
			align-items: self-start;
		}
		.accordion_list_order .order_item .head .list_product {
		    justify-content: flex-start;
		    width: 100%;
		    margin-right: 0;
		}
	}
</style>	
<section id="content" class="order-history-list account_wrap"><?php echo $content_top; ?>
	<div class="wrap two_column">
        <div class="side_bar">
            <?php echo $column_left; ?>
		</div>
		
		<div class="account_content">			
			<?php if ($pages) { ?>
			<div class="head_tab">
				<ul>
					<?php foreach (['total','inprocess','cancelled','completed'] as $page_identifier) { ?>
						<?php if (!empty($pages['count'][$page_identifier])) { ?>								
							<li><a href='<?php echo $pages['links'][$page_identifier]; ?>'><?php echo ${'tab_' . $page_identifier}?> (<?php echo $pages['count'][$page_identifier]; ?>)</a></li>									
							<?php } ?>
						<? } ?>
					
				</ul>
			</div>
			<?php } ?>
			<div class="list_order accordion_list_order">
				<?php if ($orders) { ?>
					<?php foreach ($orders as $order) { ?>	
						<?php include($this->checkTemplate(dirname(__FILE__),'/structured_order/order_single.tpl')); ?>
					<?php } ?>
					<div class="pagination"><?php echo $pagination; ?></div>
					<?php } else { ?>
					<div class="content"><?php echo $text_empty; ?></div>
				<?php } ?>
			</div>
			<script>
				var list_order = document.querySelectorAll('.accordion_list_order .order_item');
				
				list_order.forEach(function(e){
					e.querySelector('.head').addEventListener('click', function () {
					    e.classList.toggle('open');
					});
				});
			</script>
			
			<?php echo $content_bottom; ?>
		</div>
	</div>
</section>
	<div class="overlay_order"></div>
	
	<script>
		$('.tracking-click').click(function(){
			var span = $(this);
			span.next().html('<i class="fa fa-spinner fa-spin"></i>');
			span.next().show();
			var tracking_code = span.attr('data-tracking-code');
			var shipping_code = span.attr('data-shipping-code');
			var shipping_phone = span.attr('data-shipping-phone');
			$('#ttninfo').load(
				'index.php?route=account/order/tracking_info',
				{
					tracking_code : tracking_code,
					shipping_code : shipping_code,
					shipping_phone: shipping_phone
				}, 
				function(){
					span.next().hide();
					$('.overlay_order').css('display', 'block');
					$(this).dialog({width:900, height:700, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: tracking_code}); 
					$('.ui-button.ui-dialog-titlebar-close').before('<p onclick="closeModalTTN()" class="close_modal_order"></p>')
				});
		});
		function closeModalTTN(){
			$('.ui-button.ui-dialog-titlebar-close').trigger('click');
			$('.overlay_order').css('display', 'none');
		}
		$('.overlay_order').on('click', function(){
			$(this).css('display', 'none');
			$('.ui-button.ui-dialog-titlebar-close').trigger('click');
		})

	</script>
<?php echo $footer; ?>