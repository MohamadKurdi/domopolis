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
			<h1><img src="view/image/information.png" alt="" />  <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a><a href="#tab-data"><?php echo $tab_data; ?></a><a href="#tab-help">Помощь в шаблонизации</a><div class="clr"></div></div>
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-general">
					<div id="languages" class="htabs">
						<?php foreach ($languages as $language) { ?>
							<a href="#language<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
						<?php } ?>
					</div>
					<?php foreach ($languages as $language) { ?>
						<div id="language<?php echo $language['language_id']; ?>">
							<table class="form">
								<tr>
									<td><span class="required">*</span> <?php echo $entry_title; ?></td>
									<td><input type="text" name="actiontemplate_description[<?php echo $language['language_id']; ?>][title]" size="100" value="<?php echo isset($actiontemplate_description[$language['language_id']]) ? $actiontemplate_description[$language['language_id']]['title'] : ''; ?>" />
										<?php if (isset($error_title[$language['language_id']])) { ?>
											<span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
										<?php } ?></td>
								</tr>
								
								<tr>
									<td><span class="required">*</span> Заголовок письма</td>
									<td><input type="text" name="actiontemplate_description[<?php echo $language['language_id']; ?>][seo_title]" size="100" value="<?php echo isset($actiontemplate_description[$language['language_id']]) ? $actiontemplate_description[$language['language_id']]['seo_title'] : ''; ?>" /></td>
								</tr>

								<tr>       
									<td>Использовать файл шаблона</td>        
									<td>
										<select name="actiontemplate_description[<?php echo $language['language_id']; ?>][file_template]">
											<option value="">Использовать шаблон из формы</option>
											<?php foreach ($file_templates as $file_template_one) { ?>
												<option value="<?php echo $file_template_one; ?>" <?php if (!empty($actiontemplate_description[$language['language_id']]) && $file_template_one == $actiontemplate_description[$language['language_id']]['file_template']) { ?>selected="selected"<?php } ?>><?php echo $file_template_one; ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
								
								<tr>               
									<td colspan="2"><textarea name="actiontemplate_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($actiontemplate_description[$language['language_id']]) ? $actiontemplate_description[$language['language_id']]['description'] : ''; ?></textarea>
										<?php if (isset($error_description[$language['language_id']])) { ?>
											<span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
										<?php } ?></td>
								</tr>
								
								
								<tr>
									<td>Meta Tag Description:</td>
									<td><textarea name="actiontemplate_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($actiontemplate_description[$language['language_id']]) ? $actiontemplate_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
								</tr>
								<tr>
									<td>Meta Tag Keywords:</td>
									<td><textarea name="actiontemplate_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="40" rows="5"><?php echo isset($actiontemplate_description[$language['language_id']]) ? $actiontemplate_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
								</tr>
								
							</table>
						</div>
					<?php } ?>
				</div>
				<div id="tab-data">
					<table class="form">
						<tr>
							<td><?php echo $entry_keyword; ?></td>
							<td><?php foreach ($languages as $language) { ?>
								<input type="text" name="keyword[<?php echo $language['language_id']; ?>]" value="<?php if (isset($keyword[$language['language_id']])) { echo $keyword[$language['language_id']]; } ?>" />
								<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
							<?php } ?></td>
						</tr>
						<tr>
							<td>Картинка</td>
							<td valign="top"><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" />
								<input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
							<br /><a onclick="image_upload('image', 'thumb');">Выбрать</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');">Очистить</a></div></td>
						</tr>
						<tr>
							<td>
								Использовать функцию подбора данных для компиляции шаблона
							</td>
							<td>
								<select name="data_function">
									<option value="">Не использовать</option>
									<?php foreach ($functions as $function_one) { ?>
										<option value="<?php echo $function_one; ?>" <?php if ($function_one == $data_function) { ?>selected="selected"<?php } ?>><?php echo $function_one; ?></option>
									<?php } ?>
								</select>

								<span class="help">Функции из модели catalog/ModelCatalogActionTemplateFunctions</span>
							</td>
						</tr> 
						<tr>
							<td>
								Использовать в текущем ручном обзвоне
							</td>
							<td>
								<select name="use_for_manual">
									<?php if ($use_for_manual) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>  
						<tr>
							<td>Отобразить обертку магазина
							<span class="help">Хедер, футер</span></td>
							<td>
								<select name="bottom">
									<?php if ($bottom) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>            
						<tr>
							<td><?php echo $entry_status; ?></td>
							<td>
								<select name="status">
								<?php if ($status) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_sort_order; ?></td>
							<td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
						</tr>
					</table>
				</div>
				<div id="tab-help">
					<h2>Доступные тэги</h2><br /><br />
					<b>{customer_name}</b>- имя<br />
					<b>{customer_lastname}</b> - фамилия<br />
					<b>{customer_email}</b> - мейл<br />
					<b>{customer_utoken}</b> - утокен<br />
					<b>{store_url}</b> - ссылка на магазин покупателя<br />
					<b>{date plus ХХ days}</b> - дата + ХХ суток. для админки - текущая дата, для каталога - дата последней отправки<br />
					<b>{date minus ХХ days}</b> - дата - ХХ суток. для админки - текущая дата, для каталога - дата последней отправки<br />
				</div>
					</form>
				</div>
			</div>
		</div>
		<script type="text/javascript"><!--
			function image_upload(field, thumb) {
				$('#dialog').remove();
				
				$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
				
				$('#dialog').dialog({
					title: 'Выбрать картинку',
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
					width: 800,
					height: 400,
					resizable: false,
					modal: false
				});
			};
		//--></script> 
		<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
		<script type="text/javascript"><!--
			CKEDITOR.config.height = 900; 
			CKEDITOR.config.FullPage = true;  
			<?php foreach ($languages as $language) { ?>
				CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
					FullPage : true,
					filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
				});
			<?php } ?>
		//--></script> 
		<script type="text/javascript"><!--
			$('#tabs a').tabs(); 
			$('#languages a').tabs(); 
		//--></script> 
	<?php echo $footer; ?>	