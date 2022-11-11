<?php echo $header; ?>
<style>
	span.get_ttn_info {cursor:pointer; display:inline-block; border-bottom:1px dashed black;}
</style>
<div id="content">	
	<div class="box">
		<div class="heading order_head">
			<h1>Отчет по ТТН и обрабатываемым заказам</h1>
		</div>
		<div class="content">			
			<script>
				function init_ttns(){
					$('.get_ttn_info').click(function(){
						var span = $(this);
						span.next().html('<i class="fa fa-spinner fa-spin"></i>');
						span.next().show();
						var ttn = span.attr('data-ttn');
						$('#ttninfo').load(
						'index.php?route=sale/order/ttninfoajax2&token=<? echo $token ?>',
						{
							ttn : ttn
						}, 
						function(){
							span.next().hide();
							$(this).dialog({width:900, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: 'Информация по накладной '+ttn}); 
						});
					});
				}</script>
				<div style="clear:both"></div>
					<div id="ttnscan"  >
						<i class="fa fa-spinner fa-spin"></i>
					</div>	 
				<script>
					$(document).ready(function(){								
						$('#ttnscan').load('index.php?route=common/home/getTTNScanResult&token=<?php echo $token; ?>', function(){ init_ttns(); });	
					});
				</script>
				
				<div id="ttninfo"></div>
				<div style="clear:both;"></div>
		</div>
	</div>
</div>


<?php echo $footer; ?>