<div class="row-fluid">
<div class="span7">
<style>
#statistics .nmbrPurchasesTogether {
	font-size: 28px;
	vertical-align: middle;
	color: #333;
	padding-left: 20px;
}
</style>
<table class="list" style="width:100%">
  <tr>
  	<th style="font-weight:normal;"># Просмотров</th>
  </tr>
<?php foreach($alsoViewedStats as $pair): ?>  
  <tr>
    <td class="nmbrPurchasesTogether left"><?php echo $pair['number']; ?></td>
    <td>
	<img src="<?php echo $pair['lowProduct']['image']; ?>" style="margin-right:10px;border:none;" />
	</td>
	<td>
	<a target="_blank" href="../index.php?route=product/product&product_id=<?php echo $pair['lowProduct']['product_id']; ?>"><?php echo $pair['lowProduct']['name']; ?></a>
	</td>
    <td>
	<img src="<?php echo $pair['highProduct']['image']; ?>" style="margin-right:10px;border:none;" />	
	<td>
	<a target="_blank" href="../index.php?route=product/product&product_id=<?php echo $pair['lowProduct']['product_id']; ?>"><?php echo $pair['highProduct']['name']; ?></a>
	</td>
  </tr>
<?php endforeach; ?>
</table>
</div>
</div>