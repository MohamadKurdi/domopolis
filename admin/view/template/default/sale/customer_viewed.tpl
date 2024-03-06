<style>
table.list > thead > tr > th, table.history > tbody > tr > th, table.form > tbody > tr > th {
    color: #FFF;
    font-size: 14px;
    font-weight: 400;
    margin-bottom: 5px;
}
</style>


<? if ($categories) { ?>
<div style="float:left; width:48%;">
<table class="list">	
          <thead>
            <tr>  
             	<th colspan='4'>Категории</th>
            </tr>
          </thead>
          <tbody>
				<? foreach ($categories as $category) { ?>	
					<tr>
						<td class="left" width="1px">
							<img src="<? echo $category['image']; ?>" />
						</td>
						<td class="left">
							<? echo $category['category_id']; ?>
						</td>
						<td class="left">
							<? echo $category['name']; ?>
						</td>
						<td class="right">
							<? echo $category['times']; ?>
						</td>
					</tr>					
				<? } ?>				
		  </tbody>
</table>
</div>
<? } ?>

<? if ($manufacturers) { ?>
<div style="float:left; width:48%; margin-left:10px;">
<table class="list">	
          <thead>
            <tr>  
             	<th colspan='4'>Бренды</th>
            </tr>
          </thead>
          <tbody>
				<? foreach ($manufacturers as $manufacturer) { ?>	
					<tr>
						<td class="left" width="1px">
							<img src="<? echo $manufacturer['image']; ?>" />
						</td>
						<td class="left">
							<? echo $manufacturer['manufacturer_id']; ?>
						</td>
						<td class="left">
							<? echo $manufacturer['name']; ?>
						</td>
						<td class="right">
							<? echo $manufacturer['times']; ?>
						</td>
					</tr>					
				<? } ?>				
		  </tbody>
</table>
</div>
<? } ?>

<div style="clear:both"></div>

<? if ($products) { ?>
<table class="list">	
          <thead>
            <tr>  
             	<th colspan='6'>Товары</th>
            </tr>
          </thead>
          <tbody>
				<? foreach ($products as $product) { ?>	
					<tr>
						<td class="left" width="1px">
							<img src="<? echo $product['image']; ?>" />
						</td>
						<td class="left" width="1px">
							<? if (in_array($product['product_id'], $ordered_products)) { ?>
								<span style='color:white; padding:3px; background:#85B200;'>заказан</span>
							<? } else { ?>
							
							<? } ?>
						</td>
						<td class="left">
							<? echo $product['product_id']; ?>
						</td>
						<td class="left">
							<? echo $product['name']; ?>
						</td>
						<td class="left">
							<? echo $product['model']; ?>
						</td>
						<td class="right">
							<? echo $product['times']; ?>
						</td>
					</tr>					
				<? } ?>				
		  </tbody>
</table>
<? } ?>
