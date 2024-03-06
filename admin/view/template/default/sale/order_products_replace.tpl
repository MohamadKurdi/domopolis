<div>
<? $code = md5(time() . mt_rand(0, 1000)); ?>
	<? if ($categories) { ?>
	
	<table style="width:100%">
	<tr>	
	<td style="width:220px; vertical-align:top; padding-right:5px; margin-right:5px; border-right:1px solid #DDD; ">
		<table class="list">	
		<tr>
			<td colspan="2"><h2>Характеристики исходного товара</h2></td>
		</tr>
		<? foreach ($main_attributes as $attribute) { ?>
			<tr>
				<td class="left" style="font-size:11px;"><? echo $attribute['name']; ?></td>
				<td class="left" style="font-size:11px;"><? echo $attribute['text']; ?></td>
			</tr>
			<? } ?>
		
		<? foreach ($special_attributes as $attribute) { ?>
			<tr>
				<td class="left" colspan="2" style="font-size:10px;"><? echo $attribute['text']; ?></td>				
			</tr>
			<? } ?>
		</table>	
	</td>
	<td id='replace_attributes_for_<? echo $code; ?>' style="width:220px; vertical-align:top; padding-right:5px; margin-right:5px; border-right:1px solid #DDD; ">
		
	</td>
	<td style="vertical-align:top;">	
	
		<? foreach ($categories as $category) { ?>
			<h2><? echo $category['name'] ?></h2>
			<? if ($category['products']) { ?>
	<? foreach ($category['products'] as $product) { ?>
		<div style="padding:3px; float:left; text-align:center; width:150px; border:1px solid <? if (!$product['special']) { ?>#DDD<? } else { ?>red<? } ?>; margin-left:5px; margin-bottom:3px;" onmouseover="$('#replace_attributes_for_<? echo $code; ?>').html($('#replace_attributes_<? echo $product['product_id'] ?>').html());">
			<img src="<? echo $product['image'] ?>"><br />
			<span style="display:inline-block; overflow:hidden; max-height:30px;"><? echo $product['name'] ?></span><br />
			<table style="width:100%; margin-bottom:5px;" class="list">
				<tr>
					<td class="left"><b>Код:</b></td>
					<td class="left"><? echo $product['product_id'] ?></td>
				</tr>
				<tr>
					<td class="left"><b>Арт:</b></td>
					<td class="left"><? echo $product['model'] ?></td>
				</tr>
				<tr>				
					<td class="center" colspan="2">
						
						<? if ($product['special']) { ?>
							<s><? echo $product['price'] ?></s>&nbsp;
							<span style="color:red;"><b><? echo $product['special'] ?></b></span>
						<? } else { ?>
							<b><? echo $product['price'] ?></b>
						<? } ?>
					</td>
				</tr>
			</table>
			<a style="padding-bottom:4px; margin-bottom:5px;display:inline-block;" id="product_fast_hbt_<? echo $product['product_id']; ?>" class="product_fast_hbt" data-product-id='<? echo $product['product_id']; ?>'>Быстрое наличие</a>&nbsp;<i class="fa fa-info"></i>
					
			<span id="fast_hbt_preview_<? echo $product['product_id']; ?>"></span>
					<script>
						$('#product_fast_hbt_<? echo $product['product_id'] ?>').click(function(){
								$.ajax({
									url:  'index.php?route=catalog/product/getFastHBT&product_id=<? echo $product['product_id'] ?>&token=<? echo $token ?>',
									dataType: 'html',
									beforeSend : function(){
										$('#fast_hbt_preview_<? echo $product['product_id'] ?>').html('');
										$('#fast_hbt_preview_<? echo $product['product_id'] ?>').html('<i class="fa fa-spinner fa-spin"></i>');
									},
									success : function(html){
										$('#fast_hbt_preview_<? echo $product['product_id'] ?>').html('');
										$('#fast_hbt_preview_<? echo $product['product_id'] ?>').html(html).dialog({width:600, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title:'Наличие по <? echo $product['product_id'] ?>'});				
									}
								})	
								});	
					</script>						
			<a class="button" onclick="$('#adding_product_id').val(<? echo $product['product_id'] ?>); $('a#button-product').click();">Добавить</a>
		</div>
		
		<div id="replace_attributes_<? echo $product['product_id'] ?>" style="display:none;">
				<table class="list">	
					<tr>
					<td colspan="2"><h2><? echo $product['name'] ?></h2></td>
					</tr>
					<? foreach ($product['main_attributes'] as $attribute) { ?>
					<tr>
						<td class="left" style="font-size:11px;"><? echo $attribute['name']; ?></td>
						<td class="left" style="font-size:11px;"><? echo $attribute['text']; ?></td>
					</tr>
					<? } ?>
		
					<? foreach ($product['special_attributes'] as $attribute) { ?>
					<tr>
						<td class="left" colspan="2" style="font-size:10px;"><? echo $attribute['text']; ?></td>				
					</tr>
					<? } ?>
				</table>	
			</div>
	<? } ?>
<? } ?>
		
		<? } ?>
		</td>
				</tr>
			</table>
	<? } else { ?>
	<div class="warning">Невозможно подобрать замену! Просьба сообщить об этом в контент-отдел!</div>
<? } ?>



</div>