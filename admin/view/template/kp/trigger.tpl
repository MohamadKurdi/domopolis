<?php echo $header; ?>
<style>
	.fselected{
	background:green;
	color:white;
	border-bottom:1px dashed white; 
	}
</style>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/log.png" alt="" /> Тест триггеров</h1>   
		</div>
		<div class="content">
			
			<table style="width: 100%;">
				<tbody>
					<tr class="filter f_top">
						<td>
							<p>Файл</p>
							<input type="text" name="tpl" id="tpl" value="trigger.cancelled.tpl" /><br />
						</td>
						<td>
							<p>Номер заказа</p>
							<input type="text" name="order_id" id="order_id" value="" /><br />
						</td>
						<td>
							<a onclick="loadtemplate()" class="button">Пиздануть тест компиляции</a>
						</td>
						<td>
							<a onclick="loaddata()" class="button">Пиздануть тест данных</a>
						</td>
					</tr>
				</tbody>
			</table>
			
			<script>
				function loadtemplate(){
					
					
				}
				
				function loaddata(){
					$.ajax({
						url	: 'index.php?route=kp/triggers/echoData&token=<?php echo $token; ?>',
						type: 'POST',
						data : {
							order_id : $('#order_id').val(),
							tpl : $('#tpl').val()
						},
						success : function(html){
							$('#result').html('<pre>' + html + '</pre>');
						}
					});
				}
			</script>
			
			<div style="margin-top:20px;">
				<div id="result" style="margin-top:10px; border-top:1px solid #ccc; width:700px; margin:0 auto; overflow:scroll">
					
				</div>
			</div>
		</div>
	</div>
</div>