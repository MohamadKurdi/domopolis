<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/banner.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $entry_name; ?></td>
						<td><input type="text" name="name" value="<?php echo $name; ?>" size="100" />
							<?php if ($error_name) { ?>
								<span class="error"><?php echo $error_name; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><?php echo $entry_status; ?></td>
						<td><select name="status">
							<?php if ($status) { ?>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
							<?php } ?>
						</select></td>
					</tr>
				</table>
				<table id="images" class="list">
					<thead>
						<tr>
							<th class="left">Тайтл блока</th>
							<th class="left">Текст в блоке</th>
							<th class="left">Текст кнопки</th>
							<th class="left">Ссылки</th>
							<th class="left">Йа картинко ПК</th>
							<th class="left">Йа картинко МОБ</th>
							<th></th>
						</tr>
					</thead>
					<?php $image_row = 0; ?>
					<?php foreach ($banner_images as $banner_image) { ?>
						<tbody id="image-row<?php echo $image_row; ?>">
							<tr>
								<td class="left">
									<?php foreach ($languages as $language) { ?>
										<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <input type="text" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['title'] : ''; ?>" />
										<br /><br />
										<?php if (isset($error_banner_image[$image_row][$language['language_id']])) { ?>
											<span class="error"><?php echo $error_banner_image[$image_row][$language['language_id']]; ?></span>
										<?php } ?>
									<?php } ?>
								</td>
								<td class="left"><?php foreach ($languages as $language) { ?>
									<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <textarea rows="3" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language['language_id']; ?>][block_text]" ><?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['block_text'] : ''; ?></textarea>
									<br /><br />
								<?php } ?>
								</td>
								<td class="left">
									<?php foreach ($languages as $language) { ?>
									<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <textarea rows="3" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language['language_id']; ?>][button_text]" ><?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['button_text'] : ''; ?></textarea>
									<br /><br />
								<?php } ?>
								</td>
								<td class="left">
									<input type="text" name="banner_image[<?php echo $image_row; ?>][link]" value="<?php echo $banner_image['link']; ?>" /><br /><br />
									<?php foreach ($languages as $language) { ?>
										<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <input type="text" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language['language_id']; ?>][link]" value="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['link'] : ''; ?>" />
										<br /><br />									
									<?php } ?>
									
								</td>
								<td class="left">
									<div class="image"><img src="<?php echo $banner_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
									<input type="hidden" name="banner_image[<?php echo $image_row; ?>][image]" value="<?php echo $banner_image['image']; ?>" id="image<?php echo $image_row; ?>"  />
									<br />
									<a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');">Открыть</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');">Удалить</a></div><br />
									
									<?php foreach ($languages as $language) { ?>
										<div class="image">
										<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
										
										<img src="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['overload_thumb'] : ''; ?>" alt="" id="thumb<?php echo $language['language_id']; ?><?php echo $image_row; ?>" />
										
										<input type="hidden" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language['language_id']; ?>][overload_image]" value="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['overload_image'] : ''; ?>" id="image<?php echo $language['language_id']; ?><?php echo $image_row; ?>"  />
										<br />
										<a onclick="image_upload('image<?php echo $language['language_id']; ?><?php echo $image_row; ?>', 'thumb<?php echo $language['language_id']; ?><?php echo $image_row; ?>');">Открыть</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $language['language_id']; ?><?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $language['language_id']; ?><?php echo $image_row; ?>').attr('value', '');">Удалить</a>
										
										</div>
									<?php } ?>
								</td>
								
								<td class="left">
									<div class="image">
										<img src="<?php echo $banner_image['thumb_sm']; ?>" alt="" id="thumb_sm<?php echo $image_row; ?>" />
										<input type="hidden" name="banner_image[<?php echo $image_row; ?>][image_sm]" value="<?php echo $banner_image['image_sm']; ?>" id="image_sm<?php echo $image_row; ?>"  />
									<br />
									
									<a onclick="image_upload('image_sm<?php echo $image_row; ?>', 'thumb_sm<?php echo $image_row; ?>');">Открыть</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_sm<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image_sm<?php echo $image_row; ?>').attr('value', '');">Удалить</a>
									</div><br />
									
									<?php foreach ($languages as $language) { ?>
										<div class="image">
										<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
										
										<img src="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['overload_thumb_sm'] : ''; ?>" alt="" id="thumb_sm<?php echo $language['language_id']; ?><?php echo $image_row; ?>" />
										
										<input type="hidden" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language['language_id']; ?>][overload_image_sm]" value="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['overload_image_sm'] : ''; ?>" id="image_sm<?php echo $language['language_id']; ?><?php echo $image_row; ?>"  />
										<br />
										<a onclick="image_upload('image_sm<?php echo $language['language_id']; ?><?php echo $image_row; ?>', 'thumb_sm<?php echo $language['language_id']; ?><?php echo $image_row; ?>');">Открыть</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_sm<?php echo $language['language_id']; ?><?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image_sm<?php echo $language['language_id']; ?><?php echo $image_row; ?>').attr('value', '');">Удалить</a>
										
										</div>
									<?php } ?>									
								</td>
								
								<td class="right"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
							</tr>
						</tbody>
						<?php $image_row++; ?>
					<?php } ?>
					<tfoot>
						<tr>
							<td colspan="3"></td>
							<td class="right"><a onclick="addImage();" class="button"><?php echo $button_add_banner; ?></a></td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
	var image_row = <?php echo $image_row; ?>;
	
	function addImage() {
		html  = '<tbody id="image-row' + image_row + '">';
		html += '<tr>';
		html += '<td class="left">';
		<?php foreach ($languages as $language) { ?>
			html += '<input type="text" name="banner_image[' + image_row + '][banner_image_description][<?php echo $language['language_id']; ?>][title]" value="" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
		<?php } ?>
		html += '</td>';	
		html += '<td class="left"><input type="text" name="banner_image[' + image_row + '][link]" value="" /><br />';
		<?php foreach ($languages as $language) { ?>
			html += '<input type="text" name="banner_image[' + image_row + '][banner_image_description][<?php echo $language['language_id']; ?>][link]" value="" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
		<?php } ?>
		
		html +='</td>';	
		html += '<td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="banner_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
		
		html += '<td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb_sm' + image_row + '" /><input type="hidden" name="banner_image[' + image_row + '][image_sm]" value="" id="image_sm' + image_row + '" /><br /><a onclick="image_upload(\'image_sm' + image_row + '\', \'thumb_sm' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
		
		html += '<td class="right"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '</tr>';
		html += '</tbody>'; 
		
		$('#images tfoot').before(html);
		
		image_row++;
	}
//--></script>
<script type="text/javascript"><!--
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
						success: function(data) {
							$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
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
//--></script> 
<?php echo $footer; ?>