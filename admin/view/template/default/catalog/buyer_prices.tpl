<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/language.png" alt="" />Массовая проверка цен</h1>
		</div>
		<div class="content">			
			<textarea id="input" cols="100" rows="1000" style="height:700px;width:300px; float:left; border:1px solid #ff7f00;"></textarea>
				<div style="float:left; font-size:36px;font-weight:900;margin:200px 20px 0 20px;"><i class="fa fa-arrow-circle-right" style="cursor:pointer;" id="parse"></i></div>
			<textarea id="output" cols="100" rows="1000" style="height:700px;width:600px;  float:left; border:1px solid #abce8e;"></textarea>		
				<div style="float:left; font-size:36px;font-weight:900;margin:200px 20px 0 20px"></div>
			<textarea id="output_bad" cols="100" rows="1000" style="height:700px;width:300px;  float:left;  border:1px solid #F96E64;"></textarea>
			<div style="clear:both;"></div>
		</div>
	</div>
</div>

<script type="text/javascript"><!--	
	$('#parse').click(function(){
		
		$.ajax({
			type : 'POST',
			dataType : 'JSON',
			url	: 'index.php?route=catalog/buyer_prices/parseList&token=<?php echo $token; ?>',
			data : {
				'input' : $('textarea#input').val()
			},
			beforeSend : function(){
				$('textarea#output').val('Подождите, пожалуйста...');
				$('textarea#output_bad').val('Подождите, пожалуйста...');
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
