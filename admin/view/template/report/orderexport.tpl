<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/language.png" alt="" />Выгрузка заказов для учета</h1>
		</div>
		<div class="content">
			<div style="float:left; margin-right:30px; margin-left:10px;">
				<div><input type="checkbox" class="checkbox" id="export_manager" checked="checked" /><label for="export_manager">Менеджер</label></div>
				<div style="clear:both; height:10px;"></div>
				<div><input type="checkbox" class="checkbox" id="export_country" /><label for="export_country">Страна</label></div>
			</div>
			<textarea id="input" cols="100" rows="1000" style="height:700px; width:300px; float:left; border:1px solid #ff7f00;"></textarea>
			<div style="float:left; font-size:36px;font-weight:900;margin:200px 20px 0 20px;"><i class="fa fa-arrow-circle-right" style="cursor:pointer;" id="parse"></i></div>
			<textarea id="output" cols="100" rows="1000" style="height:700px;width:700px;  float:left; border:1px solid #abce8e;"></textarea>		
			<div style="float:left; font-size:36px;font-weight:900;margin:200px 20px 0 20px"></div>			
			<div style="clear:both;"></div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--	
	$('#parse').click(function(){
		
		$.ajax({
			type : 'POST',
			dataType : 'JSON',
			url	: 'index.php?route=kp/orderexport/parseList&token=<?php echo $token; ?>',
			data : {
				'input' : $('textarea#input').val()
			},
			beforeSend : function(){
				$('textarea#output').val('Подождите, пожалуйста...');				
			},
			success : function(json){
				$('textarea#input').val(json.input);
				$('textarea#output').val(json.good);
				$('textarea#output_bad').val(json.bad)
				
			},
			error : function(json){
				console.log(json);
			}
			
		});
		
		
	});
//--></script> 
<?php echo $footer; ?>