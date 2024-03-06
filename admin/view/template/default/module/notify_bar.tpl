<?php echo $header; ?>
<style type="text/css">
	.clr{clear:both}
	input[type="text"]{width:80%;}
</style>
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
			<h1><img src="view/image/module.png" alt="" />  <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table id="notify_bar" class="list" width="100%">
					<thead>
						<tr>
							<td class="left" width="200px">SVG</td>
							<td class="left" width="150px">Фоны</td>
							<td class="left" width="450px">Тексты</td>
							<td class="left" width="450px">Ссылка</td>
							<td class="left" width="100px"><?php echo $entry_sort_order; ?></td>
							<td class="left" width="50px"><?php echo $text_status; ?></td>
							<td width="100px"><?php echo $entry_action; ?></td>
						</tr>
					</thead>
					<?php $notify_bar_row = 0; ?>
					<?php foreach ($notify_bars as $notify_bar) { ?>
						<tbody id="notify_bar-row<?php echo $notify_bar_row; ?>">
							<tr>
								<td class="left">
									<input type="hidden" name="notify_bar[<?php echo $notify_bar_row; ?>][notify_bar_id]" value="<?php echo $notify_bar['notify_bar_id']; ?>" />
									<textarea name="notify_bar[<?php echo $notify_bar_row; ?>][svg]" cols="30" rows="8"><?php echo isset($notify_bar['svg']) ? $notify_bar['svg'] : ''; ?></textarea>
								</td>
								<td class="left">
									<div style="margin-bottom:10px;">
										<span class="status_color" style="display:block; margin-bottom:5px; padding:3px 5px; background:#4ea24e; color:#FFF"><i class="fa fa-mobile"></i> Фон мобильный</span>

										<div class="image">
											<img src="<?php echo $notify_bar['thumb_mobile']; ?>" alt="" id="thumb_mobile<?php echo $notify_bar_row; ?>" />
											<input type="hidden" name="notify_bar[<?php echo $notify_bar_row; ?>][image_mobile]" value="<?php echo $notify_bar['image_mobile']; ?>" id="image_mobile<?php echo $notify_bar_row; ?>"  />
											<br />
											<a onclick="image_upload('image_mobile<?php echo $notify_bar_row; ?>', 'thumb_mobile<?php echo $notify_bar_row; ?>');">open</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_mobile<?php echo $notify_bar_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image_mobile<?php echo $notify_bar_row; ?>').attr('value', '');">del</a>
										</div>
									</div>

									<div>
										<span class="status_color" style="display:block; margin-bottom:5px; padding:3px 5px; background:#4ea24e; color:#FFF"><i class="fa fa-desktop"></i> Фон ПК</span>
										<div class="image">
											<img src="<?php echo $notify_bar['thumb_pc']; ?>" alt="" id="thumb_pc<?php echo $notify_bar_row; ?>" />
											<input type="hidden" name="notify_bar[<?php echo $notify_bar_row; ?>][image_pc]" value="<?php echo $notify_bar['image_pc']; ?>" id="image_pc<?php echo $notify_bar_row; ?>"  />
											<br />
											<a onclick="image_upload('image_pc<?php echo $notify_bar_row; ?>', 'thumb_pc<?php echo $notify_bar_row; ?>');">open</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb_pc<?php echo $notify_bar_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image_pc<?php echo $notify_bar_row; ?>').attr('value', '');">del</a>
										</div>
									</div>
								</td>

								<td class="left">
									<div style="margin-bottom:10px;">
										<span class="status_color" style="display:block; margin-bottom:5px; padding:3px 5px; background:#4ea24e; color:#FFF">Текст возле иконки</span>
										<?php foreach ($languages as $language) { ?>
											<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
											<input type="text" name="notify_bar[<?php echo $notify_bar_row; ?>][notify_bar_description][<?php echo $language['language_id']; ?>][text_near_svg]" value="<?php echo isset($notify_bar['notify_bar_description'][$language['language_id']]) ? $notify_bar['notify_bar_description'][$language['language_id']]['text_near_svg'] : ''; ?>" />
											<br />
										<?php } ?>	
									</div>

									<div>
										<span class="status_color" style="display:block; margin-bottom:5px; padding:3px 5px; background:#FF7815; color:#FFF">Основной текст</span>
										<?php foreach ($languages as $language) { ?>
											<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
											<input type="text" name="notify_bar[<?php echo $notify_bar_row; ?>][notify_bar_description][<?php echo $language['language_id']; ?>][main_text]" value="<?php echo isset($notify_bar['notify_bar_description'][$language['language_id']]) ? $notify_bar['notify_bar_description'][$language['language_id']]['main_text'] : ''; ?>" />
											<br />
										<?php } ?>
									</div>								
								</td>
								<td class="left">
									<div style="margin-bottom:10px;">
										<span class="status_color" style="display:block; margin-bottom:5px; padding:3px 5px; background:#4ea24e; color:#FFF">Ссылка</span>
										<?php foreach ($languages as $language) { ?>
										<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										<input type="text" name="notify_bar[<?php echo $notify_bar_row; ?>][notify_bar_description][<?php echo $language['language_id']; ?>][link]" value="<?php echo isset($notify_bar['notify_bar_description'][$language['language_id']]) ? $notify_bar['notify_bar_description'][$language['language_id']]['link'] : ''; ?>" />
										<br />
									<?php } ?>	
									</div>

									<div>
										<span class="status_color" style="display:block; margin-bottom:5px; padding:3px 5px; background:#FF7815; color:#FFF">Текст ссылки</span>
										<?php foreach ($languages as $language) { ?>
										<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										<input type="text" name="notify_bar[<?php echo $notify_bar_row; ?>][notify_bar_description][<?php echo $language['language_id']; ?>][link_text]" value="<?php echo isset($notify_bar['notify_bar_description'][$language['language_id']]) ? $notify_bar['notify_bar_description'][$language['language_id']]['link_text'] : ''; ?>" />
										<br />
									<?php } ?>
									</div>		
								</td>
								<td class="left">
									<input type="number" step="1" style="width:60px;" name="notify_bar[<?php echo $notify_bar_row; ?>][sort_order]" value="<?php echo $notify_bar['sort_order']; ?>" />
								</td>
								<td class="middle">
									<input class="checkbox" type="checkbox" name="notify_bar[<?php echo $notify_bar_row; ?>][status]" <?php if (isset($notify_bar['status'])) { ?> checked="checked"<?php } ?> id="notify_bar_status_<?php echo $notify_bar_row; ?>">
									<label for="notify_bar_status_<?php echo $notify_bar_row; ?>"></label>
								</td>
								<td class="left">
									<a onclick="$('#notify_bar-row<?php echo $notify_bar_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a>
								</td>
							</tr>
						</tbody>
																		
						<?php $notify_bar_row++; ?>
					<?php } ?>
					<tfoot>
						<tr>
							<td colspan="6"></td>
							<td class="right"><a onclick="addnotify_bar();" class="button"><?php echo $button_add_notify_bar; ?></a></td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 

<script type="text/javascript"><!--
	var notify_bar_row = <?php echo $notify_bar_row; ?>;

function addnotify_bar() {

  html  = '<tbody id="notify_bar-row' + notify_bar_row + '">';  
  html += '  <tr>';

  // SVG textarea
  html += '    <td class="left">';
  html += '      <textarea name="notify_bar[' + notify_bar_row + '][svg]" cols="50" rows="8"></textarea>';
  html += '      <input type="hidden" name="notify_bar[' + notify_bar_row + '][notify_bar_id]" value="' + notify_bar_row + '">';
  html += '    </td>';
  
  
  // Background image rows
  html += '    <td class="left">';
  html += '      <div style="margin-bottom:10px;">';
  html += '        <span class="status_color" style="display:block; margin-bottom:5px; padding:3px 5px; background:#4ea24e; color:#FFF"><i class="fa fa-mobile"></i> Фон мобильный</span>';
  html += '        <div class="image">'; 
  html += '          <img src="<?php echo $no_image; ?>" alt="" id="thumb_mobile' + notify_bar_row + '" />';
  html += '          <input type="hidden" name="notify_bar[' + notify_bar_row + '][image_mobile]" value="" id="image_mobile' + notify_bar_row + '" />';
  html += '          <br />';
  html += '          <a onclick="image_upload(\'image_mobile' + notify_bar_row + '\', \'thumb_mobile' + notify_bar_row + '\');">open</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb_mobile' + notify_bar_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image_mobile' + notify_bar_row + '\').attr(\'value\', \'\');">del</a>';
  html += '        </div>';
  html += '      </div>';
  
  html += '      <div>';
  html += '        <span class="status_color" style="display:block; margin-bottom:5px; padding:3px 5px; background:#4ea24e; color:#FFF"><i class="fa fa-desktop"></i> Фон ПК</span>';
  html += '        <div class="image">';
  html += '          <img src="<?php echo $no_image; ?>" alt="" id="thumb_pc' + notify_bar_row + '" />';
  html += '          <input type="hidden" name="notify_bar[' + notify_bar_row + '][image_pc]" value="" id="image_pc' + notify_bar_row + '" />';
  html += '          <br />';
  html += '          <a onclick="image_upload(\'image_pc' + notify_bar_row + '\', \'thumb_pc' + notify_bar_row + '\');">open</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb_pc' + notify_bar_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image_pc' + notify_bar_row + '\').attr(\'value\', \'\');">del</a>';
  html += '        </div>';
  html += '      </div>';
  html += '    </td>';
  
  // Text rows  
  html += '    <td class="left">';
  
  html += '      <div style="margin-bottom:10px;">';
  html += '        <span class="status_color" style="display:block; margin-bottom:5px; padding:3px 5px; background:#4ea24e; color:#FFF">Текст возле иконки</span>';
  <?php foreach ($languages as $language) { ?>
  html += '        <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />';
  html += '        <input type="text" name="notify_bar[' + notify_bar_row + '][notify_bar_description][<?php echo $language['language_id']; ?>][text_near_svg]" value="" />';
  html += '        <br />';
  <?php } ?>
  html += '      </div>';

  html += '      <div>';
  html += '        <span class="status_color" style="display:block; margin-bottom:5px; padding:3px 5px; background:#FF7815; color:#FFF">Основной текст</span>';
  <?php foreach ($languages as $language) { ?>
  html += '        <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />';
  html += '        <input type="text" name="notify_bar[' + notify_bar_row + '][notify_bar_description][<?php echo $language['language_id']; ?>][main_text]" value="" />';
  html += '        <br />';  
  <?php } ?>
  html += '      </div>';
  
  html += '    </td>';
  
  // Link rows
  html += '    <td class="left">';

  html += '      <div style="margin-bottom:10px;">';
  html += '        <span class="status_color" style="display:block; margin-bottom:5px; padding:3px 5px; background:#4ea24e; color:#FFF">Ссылка</span>';
  <?php foreach ($languages as $language) { ?>
  html += '        <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />';
  html += '        <input type="text" name="notify_bar[' + notify_bar_row + '][notify_bar_description][<?php echo $language['language_id']; ?>][link]" value="" />';
  html += '        <br />';
  <?php } ?>
  html += '      </div>';

  html += '      <div>';
  html += '        <span class="status_color" style="display:block; margin-bottom:5px; padding:3px 5px; background:#FF7815; color:#FFF">Текст ссылки</span>';
  <?php foreach ($languages as $language) { ?>
  html += '        <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />';
  html += '        <input type="text" name="notify_bar[' + notify_bar_row + '][notify_bar_description][<?php echo $language['language_id']; ?>][link_text]" value="" />';
  html += '        <br />';
  <?php } ?>
  html += '      </div>';
  
  html += '    </td>';
  
  // Sort order
  html += '    <td class="left">';
  html += '      <input type="number" step="1" style="width:40px;" name="notify_bar[' + notify_bar_row + '][sort_order]" value="" />';
  html += '    </td>';
  
  // Status
  html += '    <td class="middle">';
  html += '      <input class="checkbox" type="checkbox" name="notify_bar[' + notify_bar_row + '][status]" checked="checked" id="notify_bar_status_' + notify_bar_row + '">';
  html += '      <label for="notify_bar_status_' + notify_bar_row + '"></label>';
  html += '    </td>';
  
  // Remove button
  html += '    <td class="left">';
  html += '      <a onclick="$(\'#notify_bar-row' + notify_bar_row + '\').remove();" class="button"><?php echo $button_remove; ?></a>';
  html += '    </td>';

  html += '  </tr>';
  html += '</tbody>';

  $('#notify_bar tfoot').before(html);

  notify_bar_row++;
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
							url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
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