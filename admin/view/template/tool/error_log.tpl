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
      <h1><img src="view/image/log.png" alt="" /> Системные журналы</h1>   
    </div>
    <div class="content">
	
	  <div class="files_list">
		<? foreach ($files as $key => $value) { ?>
			
			<span style="background:orange; display:inline-block; padding:2px 4px; color:white; width:100px;"><? echo $key ?>:</span> 
			<? foreach ($value as $text) { ?>			
				<span class="files_file" data-file="<? echo $text; ?>" style="font-size:12px; padding:2px 4px; cursor:pointer; border-bottom:1px dashed black; display:inline-block; margin-bottom:4px;"><? echo $text; ?></span>&nbsp;&nbsp;&nbsp;			
			<? } ?><br />
		<? } ?>	
	</div>	
	
	  <input type="hidden" name="tpl_file" id="tpl_file" value="" />
      <textarea id="text" name="text" wrap="off" style="width: 98%; height: 600px; padding: 5px; border: 1px solid #CCCCCC; background: #FFFFFF; overflow: scroll;"></textarea>
	  
	  <a class="button" style="width:90%; padding:10px 20px; text-align:center; margin-top:20px;" id="save_file">Очистить выбранный файл журнала</a>
	 
    </div>
  </div>
</div>


<script>
		function update_bt_template(tpl_file, elem){
			$.ajax({
				url	: 'index.php?route=tool/error_log/loadtext&token=<?php echo $token; ?>',
				type: 'POST',
				data : {
						tpl_file : tpl_file,					
					},
				dataType : 'text',
				success : function(text){
						$('#text').val(text);
						$('span.files_file').removeClass('fselected');
						elem.addClass('fselected');
						$('#tpl_file').val(tpl_file);
					},
				error : function(html){
						console.log(html);
					}
				});
			}
			
		$('span.files_file').click(function(){
			var filename = $(this).attr('data-file');
			var elem = $(this);
			
			update_bt_template(filename, elem);
		});
		
		function upload_text(){
			$.ajax({
				url	: 'index.php?route=tool/error_log/clearlog&token=<?php echo $token; ?>',
				type: 'POST',
				data : {
						tpl_file : $('#tpl_file').val(),						
					},
				dataType : 'html',
				success : function(html){
						swal("Очистили!", $('#tpl_file').val(), "success");					
					},
				error : function(html){
						swal(html, $('#tpl_file').val(), "error");	
						console.log(html);
					}
				});
			}
		
		$('a#save_file').click(function(){
			upload_text();			
		});
		
</script>

<?php echo $footer; ?>
