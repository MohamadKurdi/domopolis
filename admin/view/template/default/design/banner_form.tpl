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
						<td>
							<input type="text" name="name" value="<?php echo $name; ?>" size="100" />
							<?php if ($error_name) { ?>
								<span class="error"><?php echo $error_name; ?></span>
							<?php } ?>
						</td>
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
						</select>
					</td>
					<tr>
						<td>Сортировка</td>
						<td>
							<input type="number" step="1" style="width:90px;" name="sort_order" value="<?php echo $sort_order; ?>" size="100" />
							<i class="fa fa-info-circle"></i> используется для конструктора слайдов в модуле "Конструктор Баннеров" в случае если 1 баннер = 1 слайд
						</td>
					</tr>
					<style>
						.class-selector, .class-sm-selector{border:2px solid #D9D9D9;}
						.class-active{border:2px solid #51A62D;}
						#images > thead > tr > th{background: #FF7815;}
					</style>
					<tr>
						<td>Схема для больших разрешений</td>
						<td>		
							<input type="hidden" name="class" value="<?php echo $class; ?>" />					
							<?php foreach ($banner_layouts as $layout) { ?>
								<img src="<?php echo $layout['image']; ?>" data-layout="<?php echo $layout['layout']; ?>" height="50px;" class="class-selector <?php if ($class == $layout['layout']) { ?>class-active<? } ?>" />
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>Схема для небольших разрешений</td>
						<td>		
							<input type="hidden" name="class_sm" value="<?php echo $class_sm; ?>" />					
							<?php foreach ($banner_layouts as $layout) { ?>
								<img src="<?php echo $layout['image']; ?>" data-layout="<?php echo $layout['layout']; ?>" height="50px;" class="class-sm-selector <?php if ($class_sm == $layout['layout']) { ?>class-active<? } ?>" />
							<?php } ?>
						</td>
					</tr>
					<script>
						$('.class-selector').click(
							function(){
								$('.class-selector').removeClass('class-active');
								$(this).addClass('class-active');
								$('input[name=class]').val($(this).attr('data-layout'));
							}
						);

						$('.class-sm-selector').click(
							function(){
								$('.class-sm-selector').removeClass('class-active');
								$(this).addClass('class-active');
								$('input[name=class_sm]').val($(this).attr('data-layout'));
							}
						);

					</script>
				</table>
				<table id="images" class="list">
					<thead>
						<tr>
							<th class="left">Тайтл блока</th>
							<th class="left">Ссылки</th>
							<th class="left">Изображение для больших разрешений</th>
							<th class="left">Изображение для маленьких разрешений</th>
							<th></th>
						</tr>
					</thead>
					<?php $image_row = 0; ?>
					<?php foreach ($banner_images as $banner_image) { ?>
						<tbody id="image-row<?php echo $image_row; ?>">
							<tr>
								<td class="left">
									<?php foreach ($languages as $language) { ?>
										<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <input type="text" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['title'] : ''; ?>" />
										<br /><br />
										<?php if (isset($error_banner_image[$image_row][$language['language_id']])) { ?>
											<span class="error"><?php echo $error_banner_image[$image_row][$language['language_id']]; ?></span>
										<?php } ?>
									<?php } ?>
									<br />
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF">Сортировка</span>   
									<input type="number" step="1" style="width:90px;" name="banner_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $banner_image['sort_order']; ?>" />	
								</td>
								<td class="left">
									<input type="text" name="banner_image[<?php echo $image_row; ?>][link]" value="<?php echo $banner_image['link']; ?>" /><br /><br />
									<?php foreach ($languages as $language) { ?>
										<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <input type="text" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language['language_id']; ?>][link]" value="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['link'] : ''; ?>" />
										<br /><br />									
									<?php } ?>									
								</td>
								<td class="left">
									<div style="margin-bottom:10px;">
										<div class="image" style="float:left;">
											<img src="<?php echo $banner_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
											<input type="hidden" name="banner_image[<?php echo $image_row; ?>][image]" value="<?php echo $banner_image['image']; ?>" id="image<?php echo $image_row; ?>"  />
											<br />
											<a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');">Open</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');">Del</a>
										</div>

										<div style="float:right">
											<table class="form">
												<tr>
													<td style="width:60px;">
														Width
													</td>
													<td>
														<input type="number" step="1" style="width:90px;" name="banner_image[<?php echo $image_row; ?>][width]" value="<?php echo $banner_image['width']; ?>" />											
													</td>
													<td style="width:60px;">
														Height
													</td>
													<td>
														<input type="number" step="1" style="width:90px;" name="banner_image[<?php echo $image_row; ?>][height]" value="<?php echo $banner_image['height']; ?>" />
													</td>
												</tr>
												<tr>
													<td style="width:60px;">
														Class
													</td>
													<td>
														<input type="text" style="width:90px;" name="banner_image[<?php echo $image_row; ?>][class]" value="<?php echo $banner_image['class']; ?>" />
													</td>
													<td style="width:60px;">
														Slide ID
													</td>
													<td>
														<input type="number" step="1" style="width:90px;" name="banner_image[<?php echo $image_row; ?>][block]" value="<?php echo $banner_image['block']; ?>" />
													</td>
												</tr>
											</table>
		
										</div>
										<div class="clr"></div>
									</div>
									
									<div style="">
										<?php foreach ($languages as $language) { ?>
											<div class="image">
												<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
												<br />

												<img src="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['overload_thumb'] : ''; ?>" alt="" id="thumb<?php echo $language['language_id']; ?><?php echo $image_row; ?>" />

												<input type="hidden" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language['language_id']; ?>][overload_image]" value="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['overload_image'] : ''; ?>" id="image<?php echo $language['language_id']; ?><?php echo $image_row; ?>"  />
												<br />
												<a onclick="image_upload('image<?php echo $language['language_id']; ?><?php echo $image_row; ?>', 'thumb<?php echo $language['language_id']; ?><?php echo $image_row; ?>');">Open</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $language['language_id']; ?><?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $language['language_id']; ?><?php echo $image_row; ?>').attr('value', '');">Del</a>
											</div>
										<?php } ?>
									</div>
								</td>
								
								<td class="left">
									<div style="margin-bottom:10px;">	
										<div class="image" style="float:left;">
											<img src="<?php echo $banner_image['thumb_sm']; ?>" alt="" id="thumb_sm<?php echo $image_row; ?>" />
											<input type="hidden" name="banner_image[<?php echo $image_row; ?>][image_sm]" value="<?php echo $banner_image['image_sm']; ?>" id="image_sm<?php echo $image_row; ?>"  />
											<br />

											<a onclick="image_upload('image_sm<?php echo $image_row; ?>', 'thumb_sm<?php echo $image_row; ?>');">Open</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_sm<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image_sm<?php echo $image_row; ?>').attr('value', '');">Del</a>
										</div>

										<div style="float:right">
											<table class="form">
												<tr>
													<td style="width:60px;">
														Width
													</td>
													<td>
														<input type="number" step="1" style="width:90px;" name="banner_image[<?php echo $image_row; ?>][width_sm]" value="<?php echo $banner_image['width_sm']; ?>" />											
													</td>
													<td style="width:60px;">
														Height
													</td>
													<td>
														<input type="number" step="1" style="width:90px;" name="banner_image[<?php echo $image_row; ?>][height_sm]" value="<?php echo $banner_image['height_sm']; ?>" />
													</td>
												</tr>
												<tr>
													<td style="width:60px;">
														Class
													</td>
													<td>
														<input type="text" style="width:90px;" name="banner_image[<?php echo $image_row; ?>][class_sm]" value="<?php echo $banner_image['class_sm']; ?>" />
													</td>
													<td style="width:60px;">
														Slide ID
													</td>
													<td>
														<input type="number" step="1" style="width:90px;" name="banner_image[<?php echo $image_row; ?>][block_sm]" value="<?php echo $banner_image['block_sm']; ?>" />
													</td>
												</tr>
											</table>
		
										</div>
										<div class="clr"></div>
									</div>


									<div style="">	
										<?php foreach ($languages as $language) { ?>
											<div class="image">
												<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />

												<img src="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['overload_thumb_sm'] : ''; ?>" alt="" id="thumb_sm<?php echo $language['language_id']; ?><?php echo $image_row; ?>" />

												<input type="hidden" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language['language_id']; ?>][overload_image_sm]" value="<?php echo isset($banner_image['banner_image_description'][$language['language_id']]) ? $banner_image['banner_image_description'][$language['language_id']]['overload_image_sm'] : ''; ?>" id="image_sm<?php echo $language['language_id']; ?><?php echo $image_row; ?>"  />
												<br />
												<a onclick="image_upload('image_sm<?php echo $language['language_id']; ?><?php echo $image_row; ?>', 'thumb_sm<?php echo $language['language_id']; ?><?php echo $image_row; ?>');">Open</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_sm<?php echo $language['language_id']; ?><?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image_sm<?php echo $language['language_id']; ?><?php echo $image_row; ?>').attr('value', '');">Del</a>										
											</div>
										<?php } ?>	
									</div>								
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
			html += '<input type="text" name="banner_image[' + image_row + '][banner_image_description][<?php echo $language['language_id']; ?>][title]" value="" /> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br /><br />';
		<?php } ?>

		html += '<br /><span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF">Сортировка</span><input type="number" step="1" style="width:90px;" name="banner_image[<' + image_row + '][sort_order]" value="0" />	'
		html += '</td>';	
		html += '<td class="left"><input type="text" name="banner_image[' + image_row + '][link]" value="" /><br /><br />';
		<?php foreach ($languages as $language) { ?>
			html += '<input type="text" name="banner_image[' + image_row + '][banner_image_description][<?php echo $language['language_id']; ?>][link]" value="" /> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br /><br />';
		<?php } ?>

		html += '<input type="hidden" name="banner_image[' + image_row + '][width]" value="0" />';
		html += '<input type="hidden" name="banner_image[' + image_row + '][width_sm]" value="0" />';
		html += '<input type="hidden" name="banner_image[' + image_row + '][height]" value="0" />';
		html += '<input type="hidden" name="banner_image[' + image_row + '][height_sm]" value="0" />';
		html += '<input type="hidden" name="banner_image[' + image_row + '][class]" value="" />';
		html += '<input type="hidden" name="banner_image[' + image_row + '][class_sm]" value="" />';
		html += '<input type="hidden" name="banner_image[' + image_row + '][block]" value="0" />';
		html += '<input type="hidden" name="banner_image[' + image_row + '][block_sm]" value="0" />';

		<?php foreach ($languages as $language) { ?>
			html += '<input type="hidden" name="banner_image[' + image_row + '][banner_image_description][<?php echo $language['language_id']; ?>][overload_image]" value="" />';
			html += '<input type="hidden" name="banner_image[' + image_row + '][banner_image_description][<?php echo $language['language_id']; ?>][overload_image_sm]" value="" />';
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