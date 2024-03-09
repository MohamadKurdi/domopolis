<div id="tab-menucontent">
	<div id="languages-menucontent" class="htabs">
		<?php foreach ($languages as $language) { ?>
			<a href="#language-menucontent<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br /><?php echo $language['name']; ?></a>
		<?php } ?>
		<div style="float:right; vertical-align: -15px;">
			<input class="checkbox" type="checkbox" name="copymain" value="1" id="copymain" />
			<label for="copymain"><b>Копировать контент <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $this->config->get('config_admin_language'); ?>.png" title="<?php echo $this->config->get('config_admin_language'); ?>" /> (<?php echo $this->config->get('config_admin_language'); ?>) на все языки</b></label>
			<span class="help"><i class="fa fa-exclamation-circle"></i> весь контент, кроме <?php echo $this->config->get('config_de_language');?> будет перезаписан</span>
		</div>
	</div>
	<? $max_row = 0; ?>
	<?php foreach ($languages as $language) { ?>
		<div id="language-menucontent<?php echo $language['language_id']; ?>">
			<table id="table_content_<?php echo $language['language_id']; ?>" style="width:100%">
				<? if (isset($category_menu_content[$language['language_id']])) { ?>			
					<? $row=0; foreach ($category_menu_content[$language['language_id']] as $menu_content) { ?>

						<tr id="tr_content_<?php echo $menu_content['category_menu_content_id'] ; ?>">				
							<? if ($row%2==0) { ?>
								<td style="border-left:2px solid green; padding:10px;">
								<? } else { ?>
									<td style="border-left:2px solid orange; padding:10px;">
									<? } ?>
									<table style="width:100%">
										<tr>
											<td colspan="2">
												<a style="float:right;" class="button" onclick="$('#tr_content_<?php echo $menu_content['category_menu_content_id'] ; ?>').remove();" data-language-id="<?php echo $language['language_id']; ?>"><i class="fa fa-trash-o"></i></a>
											</td>
										</tr>
										<tr>												
											<td>
												Заголовок: 
											</td>
											<td>
												<input type="text" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][title]" size="255" style="width:400px; margin-bottom:15px" value="<? echo $menu_content['title'] ?>" />										
												&nbsp;&nbsp;&nbsp;Сортировка: <input type="text" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][sort_order]" size="2" style="width:30px; margin-bottom:10px" value="<? echo $menu_content['sort_order'] ?>" />
											</td>
										</tr>
										<tr>
											<td>
												Ссылка: 
											</td>
											<td>
												<input type="text" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][href]" size="1024" style="width:600px; margin-bottom:15px" value="<? echo $menu_content['href'] ?>" />
											</td>
										</tr>
										<tr>
											<td>
												Контент / текст
											</td>
											<td>
												<textarea style="width:600px;" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][content]" id="content<?php echo $menu_content['category_menu_content_id'] ; ?>"><? echo $menu_content['content'] ?></textarea>
											</td>
										</tr>
										<tr>
											<td>
												Изображение
											</td>
											<td>
												<div class="image" style="float:left;">
													<img src="<?php echo $menu_content['thumb']; ?>" alt="" id="thumb<?php echo $menu_content['category_menu_content_id'] ; ?>" />

													<input type="hidden" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][image]" value="<?php echo $menu_content['image']; ?>" id="image<?php echo $menu_content['category_menu_content_id'] ; ?>" /><br />

													<a onclick="image_upload('image<?php echo $menu_content['category_menu_content_id'] ; ?>', 'thumb<?php echo $menu_content['category_menu_content_id'] ; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $menu_content['category_menu_content_id'] ; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $menu_content['category_menu_content_id'] ; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
												</div>
												<div style="padding-top:50px;">
													Ширина: <input type="text" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][width]" size="5" style="width:60px;" value="<? echo $menu_content['width'] ?>" />
													Высота: <input type="text" name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][height]" size="5" style="width:60px;" value="<? echo $menu_content['height'] ?>" />
												</div>
											</td>
										</tr>
										<tr>
											<td>
												Это баннер под списком
											</td>
											<td>
												<select name="category_menu_content[<?php echo $language['language_id']; ?>][<?php echo $menu_content['category_menu_content_id'] ; ?>][standalone]">
													<? if ($menu_content['standalone']) { ?>
														<option value="0" >нет, этот блок будет отображен в списке</option>
														<option value="1" selected="selected">да, этот блок будет отображен под списком</option>
													<? } else { ?>
														<option value="0" selected="selected">нет, этот блок будет отображен в списке</option>
														<option value="1">да, этот блок будет отображен под списком</option>
													<? } ?>
												</select>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<? if ($menu_content['category_menu_content_id'] > $max_row) $max_row = $menu_content['category_menu_content_id']; ?>  			
							<? $row++; } ?>
						<? } ?>
					</table>
					<a style="margin-top:20px; float:right;" class="button add-menu-content" data-language-id="<?php echo $language['language_id']; ?>">Добавить</a>
					<div class="clr"></div>
				</div>
			<? } ?>		  
		</div>

		<script type="text/javascript" >
			$('a.add-menu-content').click(function(){						
				var module_row = <?php echo ($max_row + 200); ?>;
				var language_id = $(this).attr('data-language-id');
				
				html  = '<tr id="tr_content_' + module_row + '">';
				html += '<td style="border-left:2px solid green; padding:10px;">';
				html += '<table style="width:100%">';
				html +=	'<tr>';
				html +=	'<td colspan="2">';
				html +=	'<a style="float:right;" class="button" onclick="$(\'#tr_content_' + module_row + '\').remove();" data-language-id="' + language_id + '">удалить</a>';
				html += '</td>';
				html += '</tr>';
				html += '<tr>';												
				html += '<td>Заголовок:</td>';
				html +=	'<td>';
				html += ' <input type="text" name="category_menu_content[' + language_id + '][' + module_row + '][title]" size="255" style="width:400px; margin-bottom:15px" value="" /> &nbsp;&nbsp;&nbsp;Сортировка: <input type="text" name="category_menu_content[' + language_id + '][' + module_row + '][sort_order]" size="2" style="width:30px; margin-bottom:10px" value="0" />'																				
				html +=	'</td>';
				html +=	'</tr>';
				html += '<tr>';												
				html += '<td>Сcылка:</td>';
				html +=	'<td>';
				html += ' <input type="text" name="category_menu_content[' + language_id + '][' + module_row + '][href]" size="1024" style="width:600px; margin-bottom:15px" value="" />';
				html +=	'</td>';
				html +=	'</tr>';
				html += '<tr>';												
				html += '<td>Контент / текст:</td>';
				html +=	'<td>';
				html += '<textarea style="width:600px;" name="category_menu_content[' + language_id + '][' + module_row + '][content]" id="content'+ module_row +'"></textarea>';
				html +=	'</td>';
				html +=	'</tr>';		
				html += '<tr>';												
				html += '<td>Изображение:</td>';
				html +=	'<td>';
				html += '<div class="image" style="float:left;">';
				html += '<img src="<?php echo $no_image; ?>" alt="" id="thumb' + module_row + '" />';
				html += '<input type="hidden" name="category_menu_content[' + language_id + '][' + module_row + '][image]" value="" id="image'+ module_row + '" /><br />';
				html += '<a onclick="image_upload(\'image' + module_row + '\', \'thumb'+ module_row + '\')"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + module_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + module_row + '\').attr(\'value\', \'\')"><?php echo $text_clear; ?></a>';
				html += '';
				html += '';
				html += '</div><div style="padding-top:50px;">';
				html +=	'Ширина: <input type="text" name="category_menu_content[' + language_id + '][' + module_row + '][width]" size="5" style="width:60px;" value="" />';
				html += 'Высота: <input type="text" name="category_menu_content[' + language_id + '][' + module_row + '][height]" size="5" style="width:60px;" value="" />';
				html +=		'</div>';
				html +=	'</td>';
				html +=	'</tr>';
				html += '<tr>';												
				html += '<td>Это баннер под списком:</td>';
				html +=	'<td>';
				html += '<select name="category_menu_content[' + language_id + '][' + module_row + '][standalone]">';
				html += '<option value="0" selected="selected">нет, этот блок будет отображен в списке</option><option value="1">да, этот блок будет отображен под списком</option>';
				html += '</select>';
				html +=	'</td>';
				html +=	'</tr>';	
				html += '</table>';
				html += '</td>';
				html += '</tr>';
				
				
				$('table#table_content_'+ language_id).append(html);		
				
				module_row++;
			});
		</script>