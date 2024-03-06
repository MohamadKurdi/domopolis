<div id="tab-image">
	<table id="images" class="list">
		<thead>
			<tr>
				<td class="left"><?php echo $entry_image; ?></td>
				<td class="right"><?php echo $entry_sort_order; ?></td>
				<?php if ($this->config->get('pim_status')) { ?>
					<td>Сделать главной</td>
				<?php } ?>
				<td></td>
			</tr>
		</thead>
		<?php $image_row = 0; ?>
		<?php foreach ($product_images as $product_image) { ?>
			<tbody id="image-row<?php echo $image_row; ?>">
				<tr>
					<td class="left"><div class="image"><img src="<?php echo $product_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
						<input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="image<?php echo $image_row; ?>" />
						<br />
						<a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
						<td class="right"><input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" size="2" /></td>
						<?php if ($this->config->get('pim_status')) {?>
							<td><input type="radio" name="def_img" value="<?php  if (isset($product_image['image'])) { echo $product_image['image']; } ?>">
							</td>
						<?php } ?>    
						<td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
					</tr>
				</tbody>
				<?php $image_row++; ?>
			<?php } ?>
			<tfoot>
				<tr>
					<td class="right" colspan="4"><?php if ($this->config->get('pim_status')) {?>
						<a id="file-uploader" class="button"></a>
						<a onclick="image_upload();" class="button">Управление изображениями</a>&nbsp;<?php } ?>
						<a onclick="addImage();" class="button"><?php echo $button_add_image; ?></a>																										
					</td>
				</tr>
			</tfoot>
		</table>																									
	</div>

	<script type="text/javascript">
		var image_row = <?php echo $image_row; ?>;
		
		function addImage() {
			html  = '<tbody id="image-row' + image_row + '">';
			html += '  <tr>';
			html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
			html += '    <td class="right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" size="2" /></td>';
			<?php if ($this->config->get('pim_status')) {?>
				html += '<td><input type="radio" name="def_img" id="radio_' + image_row + '" value="" disabled="disabled"></td>';
			<?php } ?>
			html += '    <td class="right"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
			html += '  </tr>';
			html += '</tbody>';
			
			$('#images tfoot').before(html);
			
			image_row++;
		}
	</script> 

	<script type="text/javascript">
		function image_upload(field, thumb) {
			$('#dialog').remove();
			
			$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
			
			$('#dialog').dialog({
				title: '<?php echo $text_image_manager; ?>',
				close: function (event, ui) {
					if ($('#' + field).attr('value')) {
						$.ajax({
							url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
							dataType: 'text',
							success: function(text) {
							//	alert(text);
								$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
								<?php if ($this->config->get('pim_status')) {?>
									var row =field.replace('image','');  $('#radio_'+row).replaceWith('<input type="radio" name="def_img" value='+$('#' + field).attr('value')+'>');
								<?php } ?>
							}
						});
					}
				},	
				bgiframe: false,
				width: <?php echo $this->config->get('pim_width')?$this->config->get('pim_width'):800;?>,
				height: <?php echo $this->config->get('pim_height')?$this->config->get('pim_height'):400;?>,
				resizable: false,
				modal: false
			});
		};
	</script> 

	<script type="text/javascript">
		var image_row = <?php echo $image_row; ?>;
		function addPowerImage(img) {
			<?php if ($this->config->get('pim_miu')){?>
				addMultiImage(img);
			<?php } else { ?>
				
				if (image_row>0) {
					var k = (image_row-1);
					
					if ($('#image'+k).val() == "") {
						$('#image-row'+k).remove();
					}
				}
				html ='<tbody id="image-row'+ image_row +'">';
				html+='<tr>';
				html+='<td class="left"><div class="image"><img src="<?php echo HTTPS_CATALOG."image/"; ?>/'+img+'" alt="" id="thumb' + image_row + '" height=100 />';
				html+='<input type="hidden" name="product_image[' + image_row + '][image]" value="' + img + '" id="image' + image_row + '" />';
				html+='<br />';
				html+='<a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
				html+='<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
				html+='<td class="right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="<?php if (isset($product_image['image'])) { echo $product_image["sort_order"]; } ?>" size="2" /></td>';
				<?php if (!$this->config->get('pim_miu')) {?>
					html += '<td><input type="radio" name="def_img" value="'+img+'"></td>';
				<?php } ?>
				html+='<td class="left"><a onclick=\'$("#image-row' + image_row + '").remove();\' class="button"><?php echo $button_remove; ?></a></td>';
				html+='</tr>';
				html+='</tbody>';	
				$('#images tfoot').before(html);
				image_row++;
			<?php } ?>
		}
	</script> 

	<script type="text/javascript">
		var uploader = new qq.FileUploader({
			element: document.getElementById('file-uploader'),
			action: 'index.php?route=tool/upload&token=<?php echo $token;?>',
			allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
			onComplete: function(id, fileName, responseJSON){addMultiImage(responseJSON.fileName); },
		});
		var image_row = <?php echo $image_row; ?>;
		function addMultiImage(img) {

			html ='<tbody id="image-row'+ image_row +'">';
			html+='<tr>';
			html+='<td class="left"><div class="image"><img src="<?php echo HTTP_CATALOG."image/"; ?>'+img+'" alt="" id="thumb' + image_row + '" height=100 />';
			html+='<input type="hidden" name="product_image[' + image_row + '][image]" value="' + img + '" id="image' + image_row + '" />';
			html+='<br />';
			html+='<a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
			html+='<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
			html+='<td class="right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="<?php if (isset($product_image['image'])) { echo $product_image["sort_order"]; } ?>" size="2" /></td>';
			html += '<td><input type="radio" name="def_img" value="'+img+'"></td>';
			html+='<td class="left"><a onclick=\'$("#image-row' + image_row + '").remove();\' class="button"><?php echo $button_remove; ?></a></td>';
			html+='</tr>';
			html+='</tbody>';	
			$('#images tfoot').before(html);
			image_row++;
		}
	</script> 