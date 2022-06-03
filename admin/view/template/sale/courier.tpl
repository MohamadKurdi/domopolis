<html>
	<header>
		<title><? echo $_title; ?></title>
		<script type="text/javascript" src="view/javascript/jquery/jquery-1.7.1.min.js"></script>
	</header>
	<body>
		<style>
			body{font-family: Tahoma,Arial,Helvetica,sans-serif;font-size:12px;}
			table{font-size:12px;}
			.ptable thead td{font-weight:400;}
		</style>
		<div style="width:600px; margin:10px auto;">
			<div style="float:left;"><img height="50px" src="view/image/<? echo FILE_LOGO; ?>" title="<?php echo $heading_title; ?>" /></div>
			<div style="float:right;">Курьер <? echo SITE_NAMESPACE; ?>&nbsp;&nbsp;<a href="<? echo $fullUrl; ?>">полный интерфейс</a></div>
			<div style="clear: both;"></div>
		</div>
		<div style="width:600px; margin:0px auto; border:1px solid grey; text-align:center;padding:20px;">
			<input type="text" id="order_id" style="font-size:32px;" /><input id="load_order" type="button" value="Загрузить заказ" />
			<div id="order_info" style="border-bottom:1px solid grey; padding-bottom:5px; margin-bottom:5px;">
				
			</div>
			<div id="result" >
				
			</div>
			<script>
				$('#load_order').click(
				function(){
					$('result').html('');
					$('#order_info').load('<? echo str_replace('&amp;','&',$getOrderAjaxUrl); ?>&order_id='+$('#order_id').val());	
				});
			</script>
		</div>
	</body>
</html>