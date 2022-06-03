<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <style>
	.fselected{
		background:green;
		color:white;
		 border-bottom:1px dashed white; 
	}
  </style>
  <div class="box">
    <div class="heading order_head">
      <h1><img src="view/image/order.png" alt="" /> Редактирование шаблонов подтверждения заказа</h1>
    </div>
    <div class="content">
	
	<div class="files_list">
		<? foreach ($texts as $key => $value) { ?>
			
			<span style="background:orange; display:inline-block; padding:2px 4px; color:white; width:100px;"><? echo $key ?>:</span> 
			<? foreach ($value as $text) { ?>			
				<span class="files_file" data-file="<? echo $text; ?>" style="font-size:12px; padding:2px 4px; cursor:pointer; border-bottom:1px dashed black; display:inline-block; margin-bottom:4px;"><? echo $text; ?></span>&nbsp;&nbsp;&nbsp;			
			<? } ?><br />
		<? } ?>	
	</div>
     
	 	
		<input type="hidden" name="tpl_file" id="tpl_file" value="" />
		<textarea id="text" name="text"></textarea>
		<a class="button" style="margin-top:20px;" id="save_file">Записать файл</a>
	 
    </div>
  </div>
</div>

<script>
		function update_bt_template(tpl_file, elem){
			$.ajax({
				url	: 'index.php?route=localisation/order_bottom_forms/loadtext&token=<?php echo $token; ?>',
				type: 'POST',
				data : {
						tpl_file : tpl_file,					
					},
				dataType : 'html',
				success : function(html){
						CKEDITOR.instances.text.setData(html);
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
				url	: 'index.php?route=localisation/order_bottom_forms/savetext&token=<?php echo $token; ?>',
				type: 'POST',
				data : {
						tpl_file : $('#tpl_file').val(),
						content : CKEDITOR.instances.text.getData()
					},
				dataType : 'html',
				success : function(html){
						swal("Записали!", $('#tpl_file').val(), "success");					
					},
				error : function(html){
						console.log(html);
					}
				});
			}
		
		$('a#save_file').click(function(){
			upload_text();			
		});
		
</script>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--



if(typeof CKEDITOR !== "undefined"){
	CKEDITOR.replace('text', {
		height:600,
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		toolbarGroups: [
				{"name":"basicstyles","groups":["basicstyles"]},
				{"name":"links","groups":["links"]},
				{"name":"paragraph","groups":["list","blocks"]},
				{"name":"document","groups":["mode"]},
				{"name":"insert","groups":["insert"]},
				{"name":"styles","groups":["styles"]},
				{"name":"about","groups":["about"]}
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: ''
	});


	// Output dimensions of images as width and height
	CKEDITOR.on('instanceReady', function (ev) {
		ev.editor.dataProcessor.htmlFilter.addRules({
			elements: {
				$: function(element){
					if (element.name == 'img') {
						var style = element.attributes.style;

						if (style) {
							// Get the width from the style.
							var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style),
								width = match && match[1];

							// Get the height from the style.
							match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
							var height = match && match[1];

							if (width) {
								element.attributes.style = element.attributes.style.replace(/(?:^|\s)width\s*:\s*(\d+)px;?/i, '');
								element.attributes.width = width;
							}

							if (height) {
								element.attributes.style = element.attributes.style.replace(/(?:^|\s)height\s*:\s*(\d+)px;?/i, '');
								element.attributes.height = height;
							}
						}
					}

					if (!element.attributes.style) delete element.attributes.style;

					return element;
				}
			}
		});
	});
} // CKEDITOR
//--></script>
<?php echo $footer; ?>