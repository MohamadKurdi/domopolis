<b>Свои склады:</b>
<table class="list">
	<? foreach ($stocks as $stock) { ?>
		<tr>		
				<td class="left">
					<? echo $stock['name']; ?>
				</td>
				<td class="center">
					<? echo $stock['amount']; ?>
				</td>
		</tr>
	<? } ?>
</table>
<br /><br />
<b>Наличие у поставщиков</b>
<div id="optimal_price_hbt_<? echo $product_id; ?>">

</div>
<script>
	$.ajax({
		url:  'index.php?route=catalog/product/getOptimalPrice&product_id=<? echo $product_id; ?>&token=<? echo $token ?>',
		dataType: 'html',
		beforeSend : function(){
			$('#optimal_price_hbt_<? echo $product_id; ?>').html('');
			$('#optimal_price_hbt_<? echo $product_id; ?>').html('<i class="fa fa-spinner fa-spin"></i>');
		},
		success : function(html){
			$('#optimal_price_hbt_<? echo $product_id; ?>').html('');
			$('#optimal_price_hbt_<? echo $product_id; ?>').html(html);				
		}
	});
</script>