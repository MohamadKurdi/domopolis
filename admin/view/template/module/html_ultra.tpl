<?php echo $header; ?>
<div id="content">


<style>     
   .list-inline ul li {
    display: inline; 
    margin-right: 5px; 
   }
  </style>
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons">
		<a onclick="apply()" class="button"><?php echo $button_apply; ?></a>
			<script language="javascript">
				function apply(){
				$('#form').append('<input type="hidden" id="apply" name="apply" value="1"  />');
				$('#form').submit();
				}
			</script>
		<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
		<a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
	</div>
  </div>
  <div class="content">
    <ul class="nav nav-tabs">
		<li class="active"><a href="#tab-position" data-toggle="tab"><?php echo $entry_position; ?></a></li>
		<li><a href="#tab-html-ultra" data-toggle="tab"><?php echo $text_osnov; ?></a></li>
		<li><a href="#tab-help" data-toggle="tab"><?php echo $text_help_dop; ?></a></li>
    </ul> 
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	<!-- Расположение -->
		<div class="tab-content">  		
			<div class="tab-pane active" id="tab-position">
				<table id="module" class="list">
					<thead>
					<tr>
						<td class="left"><?php echo $entry_html_ultra; ?></td>
						<td class="left"><?php echo $entry_layout; ?></td>
						<td class="left"><?php echo $entry_position; ?></td>
						<td class="left"><?php echo $entry_status; ?></td>
						<td class="right"><?php echo $entry_sort_order; ?></td>
						<td></td>
					</tr>
					</thead>
					<?php $module_row = 0; ?>
					<?php foreach ($modules as $module) { ?>
					<tbody id="module-row<?php echo $module_row; ?>">
					  <tr>
						<td class="left">
							<select name="html_ultra_module[<?php echo $module_row; ?>][modul_setting_id]">
								<?php foreach ($setting as $module_id => $module_setting) { ?>		
								<?php if ($module_setting['key'] == $module['modul_setting_id']) { ?>
								<option value="<?php echo $module_setting['key']; ?>" selected="selected"><?php echo $module_setting['value']['module_description'][$language_id]['title']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $module_setting['key']; ?>"><?php echo $module_setting['value']['module_description'][$language_id]['title'];; ?></option>
								<?php } ?>
								<?php } ?>
							</select></td>
						<td class="left">
							<select name="html_ultra_module[<?php echo $module_row; ?>][layout_id]">
								<?php foreach ($layouts as $layout) { ?>
								<?php if ($layout['layout_id'] == $module['layout_id']) { ?>
								<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select></td>
						<td class="left"><select name="html_ultra_module[<?php echo $module_row; ?>][position]">
							<?php if ($module['position'] == 'content_top') { ?>
							<option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
							<?php } else { ?>
							<option value="content_top"><?php echo $text_content_top; ?></option>
							<?php } ?>  
							<?php if ($module['position'] == 'content_bottom') { ?>
							<option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
							<?php } else { ?>
							<option value="content_bottom"><?php echo $text_content_bottom; ?></option>
							<?php } ?>    
							<?php if ($module['position'] == 'column_left') { ?>
							<option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
							<?php } else { ?>
							<option value="column_left"><?php echo $text_column_left; ?></option>
							<?php } ?>
							<?php if ($module['position'] == 'column_right') { ?>
							<option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
							<?php } else { ?>
							<option value="column_right"><?php echo $text_column_right; ?></option>
							<?php } ?>
						   </select></td>
						<td class="left"><select name="html_ultra_module[<?php echo $module_row; ?>][status]">
							<?php if ($module['status']) { ?>
							<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
							<option value="0"><?php echo $text_disabled; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
							<?php } ?>
						  </select></td>
						<td class="right"><input type="text" name="html_ultra_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
						<td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
					  </tr>
					</tbody>
					<?php $module_row++; ?>
					<?php } ?>
					<tfoot>
					  <tr>
						<td colspan="4"></td>
						<td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
					  </tr>
					</tfoot>
				</table>
			</div>
			<div class="tab-pane" id="tab-html-ultra">
			
			<script src="view/javascript/ckeditor/ckeditor.js" type="text/javascript"></script>
				<!-- Список созданых модулей   -->
				<table class="form">
					<tr>
						<td class="tab-top">
							<ul class="nav nav-tabs nav-stacked">
								<?php $modul_tab_id = 0; ?>
								<?php foreach ($setting as $module_id => $module_setting) { ?>
										<li id="li-tab-<?php echo $module_id; ?>" <?php echo ($modul_tab_id == 0)? 'class="active"':'';?>><a href="#tab-module-<?php echo $module_id; ?>" data-toggle="tab"><img onclick="$('#tab-module-<?php echo $module_id; ?>').remove(); $('#li-tab-<?php echo $module_id; ?>').remove(); $('tbody:has(option[value=html_setting_<?php echo $module_id; ?>]option:selected)').remove(); return false;" alt="" src="view/image/delete.png"> <?php echo $module_setting['value']['module_description'][$language_id]['title']; ?></a></li>
								<?php $modul_tab_id++; ?>						
								<?php } ?>	
								<li id="new_module"> <a onclick="addNewModule();" class="button"><?php echo $button_add_module; ?></a></li>						
							</ul> 
						</td>
				<!-- Основное  -->
						<td>
						<div class="tab-content">  
						<?php $modul_tab_id = 0; ?>
						<?php $new_modul_id = 0; ?>
						
						<?php foreach ($setting as $module_id => $module_setting) { ?>							
							<?php	$module_value = $module_setting['value'];?>						
								<div class="tab-pane <?php echo ($modul_tab_id == 0)? 'active':'';?>" id="tab-module-<?php echo $module_id; ?>">
								
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab-content-<?php echo $module_id; ?>" data-toggle="tab"><?php echo $text_osnov; ?></a></li>
										<li><a href="#tab-dop-setting-<?php echo $module_id; ?>" data-toggle="tab"><?php echo $text_dop_setting; ?></a></li>
										<li><a href="#tab-view-<?php echo $module_id; ?>" data-toggle="tab"><?php echo $text_decor; ?></a></li>
									</ul> 
								
									<div class="tab-content">  
									<!-- основной контент -->
									<div class="tab-pane active" id="tab-content-<?php echo $module_id; ?>">
										<ul id="languages" class="nav nav-tabs">
										<?php $language_i=0; ?>
											<?php foreach ($languages as $language) { ?>
											<li <?php echo ($language_i == 0)? 'class="active"':'';?>><a data-toggle="tab"  href="#language<?php echo $language['language_id']."_".$module_id; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
											<?php $language_i++; ?>
											<?php } ?>
										</ul>
										<div class="tab-content">  
										<?php $language_i=0; ?>										
										<?php foreach ($languages as $language) { ?>
											<div class="tab-pane <?php echo ($language_i == 0)? 'active':'';?>" id="language<?php echo $language['language_id']."_".$module_id; ?>"> 
												<table class="form">
													<tr>
														<td><span class="required">*</span><?php echo $entry_title; ?></td>
														<td>
															<input type="text" name="html_setting_<?php echo $module_id; ?>[module_description][<?php echo $language['language_id']; ?>][title]" placeholder="<?php echo $entry_title; ?>" id="input-heading<?php echo $language['language_id']; ?>" value="<?php echo isset($module_value['module_description'][$language['language_id']]['title']) ? $module_value['module_description'][$language['language_id']]['title'] : ''; ?>" />
														</td>
													</tr>
													<tr>
													<div class="form-group">
													  <td><?php echo $entry_description; ?></td>
													  <td>
															<textarea style = "height: 300px; width:80%" name="html_setting_<?php echo $module_id; ?>[module_description][<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="description<?php echo $module_id."_".$language['language_id']; ?>" ><?php echo isset($module_value['module_description'][$language['language_id']]['description']) ? $module_value['module_description'][$language['language_id']]['description'] : ''; ?></textarea>
													  </td>
													  </div>														  
														<script type="text/javascript">
														<!--
														CKEDITOR.config.indentClasses = ["ul-grey", "ul-red", "text-red", "ul-content-red", "circle", "style-none", "decimal", "paragraph-portfolio-top", "ul-portfolio-top", "url-portfolio-top", "text-grey"];
														CKEDITOR.config.protectedSource.push(/<(style)[^>]*>.*<\/style>/ig);// разрешить теги <style>
														CKEDITOR.config.protectedSource.push(/<(script)[^>]*>.*<\/script>/ig);// разрешить теги <script>
														CKEDITOR.config.protectedSource.push(/<\?[\s\S]*?\?>/g);// разрешить php-код
														CKEDITOR.config.protectedSource.push(/<!--dev-->[\s\S]*<!--\/dev-->/g);
														CKEDITOR.config.allowedContent = true; /* все теги */
															CKEDITOR.replace('description<?php echo $module_id."_".$language['language_id']; ?>', {
																filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token_sesiya; ?>',
																filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token_sesiya; ?>',
																filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token_sesiya; ?>',
																filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token_sesiya; ?>',
																filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token_sesiya; ?>',
																filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token_sesiya; ?>'
															});
														//-->
														</script>		
																									
													</tr>
													<!-- Доп настройки-->
													<tr>
														<td></td>
														<td>
															<div class="panel-group" id="accordion">
															  <!-- 2 Короткий код -->
																<div class="panel panel-default">
																	<div class="panel-heading">
																		<h4 class="panel-title">
																			<a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><?php echo $help_titel_shortcodes; ?></a>
																		</h4>
																	</div>
																	<div id="collapse1" class="panel-collapse collapse">
																		<div class="panel-body">
																			<p><?php echo $help_text_shortcodes; ?></p>
																		</div>
																	</div>
																</div>   
															</div>   
														</td>
													</tr>
												</table>
											</div>				
										<?php $language_i++; ?>									
										<?php } ?> 
										</div>
										<table class="form">
										<!-- Использовать php --> 
											<tr>
											  <td><?php echo $test_php_on; ?></td>
											  <td>        
												<p style="padding-left:20px;font-style:oblique;" ><span style="padding-right:5px;color:#0000FF;">**</span><?php echo $php_status_help; ?></p>
												 <select name="html_setting_<?php echo $module_id; ?>[php_status]" id="input-php_status" class="form-control"> 
													<?php if ($module_value['php_status']=="on") { ?>
													<option value="on" selected="selected"><?php echo $on; ?></option>
													<option value="off"><?php echo $off; ?></option>
													<?php } else { ?>
													<option value="on"><?php echo $on; ?></option>
													<option value="off" selected="selected"><?php echo $off; ?></option>
													<?php } ?>
												</select>
												  
											  <td>
											</tr>	
										</table>			
									</div>
									<!-- Доп настройки -->
									<div class="tab-pane" id="tab-dop-setting-<?php echo $module_id; ?>">
									<!-- Период-->
										<table class="form">
											<tr style="background-color: rgb(244, 244, 248);">					
												<td rowspan="2"><?php echo $entry_date_from_to; ?></td>
												<!-- способ использования-->
												<td>
													<select name="html_setting_<?php echo $module_id; ?>[timezone_res]" id="input-timezone">
													<?php foreach (DateTimeZone::listIdentifiers( ) as $timezone){?>	
														<option value="<?php echo $timezone; ?>" <?php echo ($module_value['timezone_res'] == $timezone)? 'selected="selected"': ""; ?>><?php echo $timezone; ?></option>
													<?php } ?>
													</select>
												<td><?php
												date_default_timezone_set($module_value['timezone_res']);
												echo $entry_time_now." : ". date("H:i:s"); ?></td>
											</tr>
											<tr style="background-color: rgb(244, 244, 248);"> 
												<td><input type='text' class="datetimepicker_from" name="html_setting_<?php echo $module_id; ?>[datetime_from]" value="<?php echo $module_value['datetime_from']; ?>"/></td>
												<td><input type='text' class="datetimepicker_to" name="html_setting_<?php echo $module_id; ?>[datetime_to]" value="<?php echo $module_value['datetime_to']; ?>"/></td>
											</tr>	
											
											<!-- Время вывода-->
											<tr>					
												<td rowspan="2"><?php echo $entry_time_from_to; ?></td>
												<td><input type='text' class="timepicker_from" name="html_setting_<?php echo $module_id; ?>[time_from]" value="<?php echo $module_value['time_from']; ?>"/></td>
												<td><input type='text' class="timepicker_to" name="html_setting_<?php echo $module_id; ?>[time_to]" value="<?php echo $module_value['time_to']; ?>"/></td> 
											</tr>  
											<!-- Дни недедли  -->
											<tr>  
												<td colspan="2" class="list-inline"> 
													<ul> 
														<li><input type="checkbox" name="html_setting_<?php echo $module_id; ?>[time_day][1]" value="1" <?php echo (isset($module_value['time_day'][1]) != 1)? "": 'checked="checked"'; ?>/> 
														<span style="padding-right:10px"><?php echo $entry_days_week_monday;?></span></li>
														<li><input type="checkbox" name="html_setting_<?php echo $module_id; ?>[time_day][2]" value="1" <?php echo (isset($module_value['time_day'][2]) != 1)? "": 'checked="checked"'; ?> />
														<span style="padding-right:10px"><?php echo $entry_days_week_tuesday;?></span></li>
														<li><input type="checkbox" name="html_setting_<?php echo $module_id; ?>[time_day][3]" value="1" <?php echo (isset($module_value['time_day'][3]) != 1)? "": 'checked="checked"'; ?>/>
														<span style="padding-right:10px"><?php echo $entry_days_week_medium;?></span></li>
														<li><input type="checkbox" name="html_setting_<?php echo $module_id; ?>[time_day][4]" value="1" <?php echo (isset($module_value['time_day'][4]) != 1)? "": 'checked="checked"'; ?>/>
														<span style="padding-right:10px"><?php echo $entry_days_week_thursday;?></span></li>
														<li><input type="checkbox" name="html_setting_<?php echo $module_id; ?>[time_day][5]" value="1" <?php echo (isset($module_value['time_day'][5]) != 1)? "": 'checked="checked"'; ?>/>
														<span style="padding-right:10px"><?php echo $entry_days_week_friday;?></span></li>
														<li><input type="checkbox" name="html_setting_<?php echo $module_id; ?>[time_day][6]" value="1" <?php echo (isset($module_value['time_day'][6]) != 1)? "": 'checked="checked"'; ?>/>
														<span style="padding-right:10px"><?php echo $entry_days_week_saturday;?></span></li>
														<li><input type="checkbox" name="html_setting_<?php echo $module_id; ?>[time_day][0]" value="1" <?php echo (isset($module_value['time_day'][0]) != 1)? "": 'checked="checked"'; ?>/>
														<span style="padding-right:10px"><?php echo $entry_days_week_sunday;?></span></li>
													 </ul>
												</td>
											</tr>	
											<div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>
											<!-- Магазины-->
											<tr>
												<td rowspan="2"><?php echo $entry_store; ?></td>
												<td>	
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][store]" value="0" <?php echo ((isset($module_value['paragraph_status']['store']) and $module_value['paragraph_status']['store']== "0" ) or (!isset($module_value['paragraph_status']['store'])))? 'checked="checked"': ""; ?>/> 	<?php echo $entry_paragraph_enable;?>
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][store]" value="1" <?php echo (isset($module_value['paragraph_status']['store']) and $module_value['paragraph_status']['store']== "1" )? 'checked="checked"': ""; ?>/><?php echo $entry_paragraph_deleted;?>
												</td>	
											</tr>	
											<tr>
												<td colspan="2">		
													<div class="scrollbox"> 
														<?php if (!isset($stores[0])){ ?>
															<input type="checkbox" name="html_setting_<?php echo $module_id; ?>[product_store][0]" value="1" checked="checked" /><?php echo $text_default;?>
														<?php } else { ?>			
															<input type="checkbox" name="html_setting_<?php echo $module_id; ?>[product_store][0]" value="1" <?php echo (isset($product_store[0]) != 1)? "": 'checked="checked"'; ?> /><?php echo $text_default;?>
															<?php $class = 'odd'; ?>
															<?php foreach ($stores as $store) { ?>
															<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
																	<div class="<?php echo $class; ?>">
																		<input type="checkbox" name="html_setting_<?php echo $module_id; ?>[product_store][<?php echo $store['store_id']; ?>]" value="1" <?php echo (isset($module_value['product_store'][$store['store_id']]) != 1)? "": 'checked="checked"'; ?>  />
																		<?php echo $store['name'];?>
																	</div>
																
															<?php } ?>

														<?php } ?> 
													</div> 
												</td>
											</tr>
											<!-- Производители-->
											<tr>					
												<td rowspan="2"><?php echo $entry_manufacturer; ?></td>
												<td colspan="2">
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][manufacturer]" value="0" <?php echo ((isset($module_value['paragraph_status']['manufacturer']) and $module_value['paragraph_status']['manufacturer']== "0" ) or (!isset($module_value['paragraph_status']['manufacturer'])))? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_enable;?>
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][manufacturer]" value="1" <?php echo (isset($module_value['paragraph_status']['manufacturer']) and $module_value['paragraph_status']['manufacturer']== "1" )? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_deleted;?>
													<input type="checkbox" name="html_setting_<?php echo $module_id; ?>[produkt_consider][manufacturer]" value="1" <?php echo (isset($module_value['produkt_consider']['manufacturer']) != 1)? "": 'checked="checked"'; ?>/> <span style="padding-right:10px">
														<?php echo $entry_produkt_consider;?></span>
												</td>	
											</tr>	
											<tr>	
												<td colspan="2">	
													<div class="scrollbox">
														<?php $class = 'odd'; ?>
														<?php foreach ($manufacturer_info as $manufacturer_in) { ?>
														<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
															<div class="<?php echo $class; ?>">
																<input type="checkbox" name="html_setting_<?php echo $module_id; ?>[manufacturer][<?php echo $manufacturer_in['manufacturer_id']; ?>]" value="1" <?php echo (isset($module_value['manufacturer'][$manufacturer_in['manufacturer_id']]) != 1)? "": 'checked="checked"'; ?>  />
																<?php echo $manufacturer_in['name'];?>
															</div>
														<?php } ?>
													</div>
												</td>
											</tr>
											<!-- Категории-->
											<tr>					
												<td rowspan="2"><?php echo $entry_category; ?></td>
												<td colspan="2">
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][category]" value="0" <?php echo ((isset($module_value['paragraph_status']['category']) and $module_value['paragraph_status']['category']== "0" ) or (!isset($module_value['paragraph_status']['category'])))? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_enable;?>
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][category]" value="1" <?php echo (isset($module_value['paragraph_status']['category']) and $module_value['paragraph_status']['category']== "1" )? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_deleted;?>
													<input type="checkbox" name="html_setting_<?php echo $module_id; ?>[produkt_consider][category]" value="1" <?php echo (isset($module_value['produkt_consider']['category']) != 1)? "": 'checked="checked"'; ?>/> <span style="padding-right:10px">
														<?php echo $entry_produkt_consider;?></span>
												</td>	
											</tr>	
											<tr>	
												<td colspan="2">	
													<div class="scrollbox"> 
														<?php $class = 'odd'; ?>
														<?php foreach ($categories_info as $categories_in) { ?>
															<?php if($categories_in['language_id'] == $language_id) { ?>	
															<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>								
																<div class="<?php echo $class; ?>">
																		<input type="checkbox" name="html_setting_<?php echo $module_id; ?>[category][<?php echo $categories_in['category_id']; ?>]" value="1" <?php echo (isset($module_value['category'][$categories_in['category_id']]) != 1)? "": 'checked="checked"'; ?>  />
																		<?php echo $categories_in['name'];?>
																</div>
															<?php } ?>
														<?php } ?>
													</div>
												</td>
											</tr>
											<!-- Товары-->
											<tr>					
												<td rowspan="2"><?php echo $entry_product; ?></td>
												<td>
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][product]" value="0" <?php echo ((isset($module_value['paragraph_status']['product']) and $module_value['paragraph_status']['product']== "0" ) or (!isset($module_value['paragraph_status']['product'])))? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_enable;?>
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][product]" value="1" <?php echo (isset($module_value['paragraph_status']['product']) and $module_value['paragraph_status']['product']== "1" )? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_deleted;?>
												</td>	
											</tr>	
											<tr>	
												<td colspan="2">	
													<div class="scrollbox"> 
														<?php $class = 'odd'; ?>
														<?php foreach ($product_info as $product_in) { ?>					
															<?php if($product_in['language_id'] == $language_id) { ?>	
															<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>								
																<div class="<?php echo $class; ?>">
																		<input type="checkbox" name="html_setting_<?php echo $module_id; ?>[product][<?php echo $product_in['product_id']; ?>]" value="1" <?php echo (isset($module_value['product'][$product_in['product_id']]) != 1)? "": 'checked="checked"'; ?>  />
																		<?php echo $product_in['name'];?>
																</div>
															<?php } ?>
														<?php } ?>
													</div>
												</td>
											</tr>
											<!-- Для авторизированных или для неавторезированных  -->
											<tr>					
												<td><?php echo $entry_authorization; ?></td>
												<td>
													<div class="scrollbox"> 
													<?php $module_value['authorization'] = (isset($module_value['authorization']))? $module_value['authorization'] : "1"; ?>
														<div>
															<input type="radio" name="html_setting_<?php echo $module_id; ?>[authorization]" value="1" <?php echo ($module_value['authorization'] == "1")? 'checked="checked"': ""; ?>/> 
															<?php echo $authorization_all;?>						
														</div>
														<div>
															<input type="radio" name="html_setting_<?php echo $module_id; ?>[authorization]" value="2" <?php echo ($module_value['authorization'] == "2")? 'checked="checked"': ""; ?>/> 
															<?php echo $authorization_on;?>
														</div>							
														<div>
															<input type="radio" name="html_setting_<?php echo $module_id; ?>[authorization]" value="3" <?php echo ($module_value['authorization'] == "3")? 'checked="checked"': ""; ?>/> 
															<?php echo $authorization_off;?>
														</div>
													</div>								
												</td>
											</tr> 
											<!-- Группы Клиентов-->
											<tr>					
												<td rowspan="2"><?php echo $entry_grup_сustomers; ?></td>
												<td>
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][grup_сustomers]" value="0" <?php echo ((isset($module_value['paragraph_status']['grup_сustomers']) and $module_value['paragraph_status']['grup_сustomers']== "0" ) or (!isset($module_value['paragraph_status']['grup_сustomers'])))? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_enable;?>
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][grup_сustomers]" value="1" <?php echo (isset($module_value['paragraph_status']['grup_сustomers']) and $module_value['paragraph_status']['grup_сustomers']== "1" )? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_deleted;?>
												</td>	
											</tr>	
											<tr>	
												<td colspan="2">	
													<div class="scrollbox"> 
														<?php $class = 'odd'; ?>
														<?php foreach ($grup_clients_info as $grup_clients_in) { ?>
															<?php if($grup_clients_in['language_id'] == $language_id) { ?>							
															<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>								
																<div class="<?php echo $class; ?>">
																		<input type="checkbox" name="html_setting_<?php echo $module_id; ?>[grup_clients][<?php echo $grup_clients_in['customer_group_id']; ?>]" value="1" <?php echo (isset($module_value['grup_clients'][$grup_clients_in['customer_group_id']]) != 1)? "": 'checked="checked"'; ?>  />
																		<?php echo $grup_clients_in['name'];?>
																</div>
															<?php } ?>
														<?php } ?>
													</div>
												</td> 		
											</tr>		
											
											  <!-- Клиенты -->
											<tr>					
												<td rowspan="2"><?php echo $entry_сustomer; ?></td>
												<td>
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][сustomer]" value="0" <?php echo ((isset($module_value['paragraph_status']['сustomer']) and $module_value['paragraph_status']['сustomer']== "0" ) or (!isset($module_value['paragraph_status']['сustomer'])))? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_enable;?>
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][сustomer]" value="1" <?php echo (isset($module_value['paragraph_status']['сustomer']) and $module_value['paragraph_status']['сustomer']== "1" )? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_deleted;?>
												</td>
											</tr>
											<tr>
												<td colspan="2">	
													<div class="scrollbox"> 
														<?php $class = 'odd'; ?>
														<?php foreach ($сustomer_info as $сustomer_in) { ?>						
														<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>								
															<div class="<?php echo $class; ?>">
																<input type="checkbox" name="html_setting_<?php echo $module_id; ?>[сustomer][<?php echo $сustomer_in['customer_id']; ?>]" value="1" <?php echo (isset($module_value['сustomer'][$сustomer_in['customer_id']]) != 1)? "": 'checked="checked"'; ?>  />
															</div>
														<?php } ?>
													</div>
												</td>
											</tr>			
											<!-- Языки-->
											<tr>					
												<td rowspan="2"><?php echo $entry_language; ?></td>
												<td>
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][language]" value="0" <?php echo ((isset($module_value['paragraph_status']['language']) and $module_value['paragraph_status']['language']== "0" ) or (!isset($module_value['paragraph_status']['language'])))? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_enable;?>
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][language]" value="1" <?php echo (isset($module_value['paragraph_status']['language']) and $module_value['paragraph_status']['language']== "1" )? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_deleted;?>
												</td>
											</tr>
											<tr>
												<td colspan="2">	
													<div class="scrollbox"> 
														<?php $class = 'odd'; ?>				
														<?php foreach ($language_info as $language_in) { ?>							
														<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>								
															<div class="<?php echo $class; ?>">
																<input type="checkbox" name="html_setting_<?php echo $module_id; ?>[language_m][<?php echo $language_in['language_id']; ?>]" value="1" <?php echo (isset($module_value['language_m'][$language_in['language_id']]) != 1)? "": 'checked="checked"'; ?>  />
																	<?php echo $language_in['name'];?>
															</div>
														<?php } ?>
													</div>
												</td>
											</tr>
											<!-- Страницы -->
											<tr>					
												<td rowspan="2"><?php echo $entry_information; ?></td>
												<td>
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][information]" value="0" <?php echo ((isset($module_value['paragraph_status']['information']) and $module_value['paragraph_status']['information']== "0" ) or (!isset($module_value['paragraph_status']['information'])))? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_enable;?>
													<input type="radio" name="html_setting_<?php echo $module_id; ?>[paragraph_status][information]" value="1" <?php echo (isset($module_value['paragraph_status']['information']) and $module_value['paragraph_status']['information']== "1" )? 'checked="checked"': ""; ?>/> 
														<?php echo $entry_paragraph_deleted;?>
												</td>	
											</tr>
											<tr>
												<td colspan="2">	
													<div class="scrollbox"> 
														<?php $class = 'odd'; ?>	
														<?php foreach ($information_sort as $information_in) { ?>  
															<?php if($information_in['language_id'] == $language['language_id']) { ?>							
															<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>								
																<div class="<?php echo $class; ?>">
																	<input type="checkbox" name="html_setting_<?php echo $module_id; ?>[information][<?php echo $information_in['information_id']; ?>]" value="1" <?php echo (isset($module_value['information'][$information_in['information_id']]) != 1)? "": 'checked="checked"'; ?>  />
																		<?php echo $information_in['title'];?>
																</div>
															<?php } ?>
														<?php } ?>
													</div>
												</td>
											</tr>
										</table>	
									</div>
									<!-- Оформление -->
									<div class="tab-pane" id="tab-view-<?php echo $module_id; ?>">
										<!-- Использовать оформление -->
										<table class="form">
											<tr>
												<td><?php echo $entry_decor_status; ?></td>
												<td>
													<select name="html_setting_<?php echo $module_id; ?>[decor_status]" id="input-decor-status" class="form-control">
														<?php if ($module_value['decor_status']) { ?> 
														<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
														<option value="0"><?php echo $text_disabled; ?></option>
														<?php } else { ?>
														<option value="1"><?php echo $text_enabled; ?></option>
														<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
														<?php } ?>
													</select>
												</td>
											</tr>			
											<!-- Оформление -->
											<tr>		
												<td rowspan="3"><?php echo $text_decor; ?></td>
												<td>
													<div class="panel-group" id="accordion">
													  <!-- 1 Основное -->
													  <div class="panel panel-default">
														<div class="panel-heading">
															<a data-toggle="collapse" data-parent="#accordion" href="#text_decor"><?php echo $help_titel_decor; ?></a>
														</div>
														<div id="text_decor" class="panel-collapse collapse">
														  <div class="panel-body">
															<p><?php echo $help_text_decor; ?></p>
														  </div>
														</div>
													  </div>
													  </div>
												</td>
											</tr>
											<tr>
												<td colspan="2">
													  <!-- 2 Короткий код -->
													  <div class="panel panel-default">
														<div class="panel-heading">
															<a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><?php echo $help_titel_shortcodes; ?></a>
														</div>
														<div id="collapse2" class="panel-collapse collapse">
														  <div class="panel-body">
															<p><?php echo $help_text_shortcodes; ?></p>
														  </div>
														</div>
													  </div> 
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<textarea class="form-control" style = "height: 200px; width:80%" name="html_setting_<?php echo $module_id; ?>[html_ultra_decor]" placeholder="<?php echo $text_placeholder_decor; ?>"><?php echo (isset($module_value['html_ultra_decor']))? $module_value['html_ultra_decor'] : "";?></textarea>	 		
												</td>
											</tr>
												<!-- CSS -->
											<tr>					
												<td><?php echo $entry_css; ?></td>
												<td>
													<textarea class="form-control" style = "height: 200px; width:80%" name="html_setting_<?php echo $module_id; ?>[html_ultra_css]" placeholder="<?php echo $text_placeholder_css; ?>"><?php echo (isset($module_value['html_ultra_css']))? $module_value['html_ultra_css'] : "";?></textarea>			
												</td>
											</tr>
										</table>
									</div>
									</div>
									</div>
									
									<?php $modul_tab_id++; ?>		
							<?php } ?>
							<?php $new_modul_id = (isset($module_id))?$module_id+1:$new_modul_id; ?>
							<div id="new_module_<?php echo $new_modul_id; ?>"></div>
						</td>
					</tr>
				</table>
				
			</div>
			
			
			<!-- Справка -->
			<div class="tab-pane" id="tab-help">
				<table class="form">
					<tr>
						<td class="box">
						  <!-- 1 Основное -->
							<div class="heading">
								<h4>
									<a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><?php echo $help_titel_main; ?></a>
								</h4>
							</div>
							<div id="collapse1" class="content">
								<div>
									<p><?php echo $help_text_main; ?></p>
								</div>
							</div>
						</td> 
					</tr>
					<tr>
						  <!-- 2 Основное -->
						<td class="box">
							<div class="heading">
								<h4>
									<a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><?php echo $help_titel_developer; ?></a>
								</h4>
							</div>
							<div id="collapse2" style="min-height:0px;" class="content">
								<div>
									<p><?php echo $help_text_developer; ?></p>
								</div>
							</div>
						  </div>   
						</td>
					</tr>
				</table>
			</div>
		</div>
	</form>	
  </div> 
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="html_ultra_module[' + module_row + '][modul_setting_id]">';
	<?php foreach ($setting as $module_id => $module_setting) { ?>
	html += '      <option value="<?php echo $module_setting['key']; ?>"><?php echo $module_setting['value']['module_description'][$language_id]['title']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="html_ultra_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="html_ultra_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="html_ultra_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="html_ultra_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script> 

<script type="text/javascript"><!--
var new_modul_id = <?php echo $new_modul_id; ?>;

function addNewModule() {	
	html  = '<li><a href="#tab-module-'+ new_modul_id +'" data-toggle="tab">'+ new_modul_id +'</a></li>';
	$('#new_module').before(html);	
	
	html  = '<div class="tab-pane active" id="tab-module-'+ new_modul_id +'">';
		html += '<ul class="nav nav-tabs">';
			html += '<li class="active"><a href="#tab-content-'+ new_modul_id +'" data-toggle="tab"><?php echo $text_osnov; ?></a></li>';
			html += '<li><a href="#tab-dop-setting-'+ new_modul_id +'" data-toggle="tab"><?php echo $text_dop_setting; ?></a></li>';
			html += '<li><a href="#tab-view-'+ new_modul_id +'" data-toggle="tab"><?php echo $text_decor; ?></a></li>';
		html += '</ul> ';
		html += '<div class="tab-content">';  
		html += '<div class="tab-pane active" id="tab-content-'+ new_modul_id +'">';
			html += '<ul id="languages" class="nav nav-tabs">'; 
				<?php $language_i=0; ?>
				<?php foreach ($languages as $language) { ?>
					html += '<li <?php echo ($language_i == 0)? 'class="active"':'';?>><a data-toggle="tab"  href="#language<?php echo $language['language_id']."_";?>'+ new_modul_id +'"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>';
				<?php $language_i++; ?>
				<?php } ?>
			html += '</ul>';		
			html += '<div class="tab-content">'; 
			<?php $language_i=0; ?>										
			<?php foreach ($languages as $language) { ?>
				html += '<div class="tab-pane <?php echo ($language_i == 0)? 'active':'';?>" id="language<?php echo $language['language_id']."_"; ?>'+ new_modul_id +'">';
					html += '<table class="form">';
						html += '<tr>';
							html += '<td><span class="required">*</span><?php echo $entry_title; ?></td>';
							html += '<td>';
								html += '<input type="text" name="html_setting_'+ new_modul_id +'[module_description][<?php echo $language['language_id']; ?>][title]" placeholder="<?php echo $entry_title; ?>" id="input-heading<?php echo $language['language_id']; ?>" value="" />';
							html += '</td>';
						html += '</tr>';
						html += '<tr>';
						  html += '<td><?php echo $entry_description; ?></td>';
						  html += '<td>';
							html += '<textarea style = "height: 300px; width:80%" name="html_setting_'+ new_modul_id +'[module_description][<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="description'+ new_modul_id +'<?php echo "_".$language['language_id']; ?>"></textarea>';
						  html += '</td>';													
						html += '</tr>';
						html += '<tr>';
							html += '<td></td>';
							html += '<td>';
								html += '<div class="panel-group" id="accordion">';
									html += '<div class="panel panel-default">';
										html += '<div class="panel-heading">';
											html += '<h4 class="panel-title">';
												html += '<a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><?php echo $help_titel_shortcodes; ?></a>';
											html += '</h4>';
										html += '</div>';
										html += '<div id="collapse1" class="panel-collapse collapse">';
											html += '<div class="panel-body">';
												html += '<p><?php echo $help_text_shortcodes; ?></p>';
											html += '</div>';
										html += '</div>';
									html += '</div>';   
								html += '</div>';   
							html += '</td>';
						html += '</tr>';
					html += '</table>';
				html += '</div>';			
			<?php $language_i++; ?>									
			<?php } ?> 
			html += '</div>';	
			html += '<table class="form">';
			html += '<tr>';
			html += '<td><?php echo $test_php_on; ?></td>';
				  html += '<td>';        
					html += '<p style="padding-left:20px;font-style:oblique;" ><span style="padding-right:5px;color:#0000FF;">**</span><?php echo $php_status_help; ?></p>';
					html += ' <select name="html_setting_'+ new_modul_id +'[php_status]" id="input-php_status" class="form-control">'; 
						html += '<option value="on"><?php echo $on; ?></option>';
						html += '<option value="off" selected="selected"><?php echo $off; ?></option>';
					html += '</select>';
				html += '<td>';
				html += '</tr>';	
			html += '</table>';			
		html += '</div>';
		html += '<div class="tab-pane" id="tab-dop-setting-'+ new_modul_id +'">';
			html += '<table class="form">';
				html += '<tr style="background-color: rgb(244, 244, 248);">';
					html += '<td rowspan="2"><?php echo $entry_date_from_to; ?></td>';
					html += '<td>';
						html += '<select name="html_setting_'+ new_modul_id +'[timezone_res]" id="input-timezone">';
						<?php foreach (DateTimeZone::listIdentifiers( ) as $timezone){?>	
							html += '<option value="<?php echo $timezone; ?>" <?php echo ("Europe/Kiev" == $timezone)? 'selected="selected"': ""; ?>><?php echo $timezone; ?></option>';
						<?php } ?>
						html += '</select>';
					html += '<td><?php
								date_default_timezone_set("Europe/Kiev");
								echo $entry_time_now." : ". date("H:i:s"); ?></td>';
				html += '</tr>';
				html += '<tr style="background-color: rgb(244, 244, 248);"> ';
					html += '<td><input type="text" class="datetimepicker_from" name="html_setting_'+ new_modul_id +'[datetime_from]" value=""/></td>';
					html += '<td><input type="text" class="datetimepicker_to" name="html_setting_'+ new_modul_id +'[datetime_to]" value=""/></td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td rowspan="2"><?php echo $entry_time_from_to; ?></td>';
					html += '<td><input type="text" class="timepicker_from" name="html_setting_'+ new_modul_id +'[time_from]" value=""/></td>';
					html += '<td><input type="text" class="timepicker_to" name="html_setting_'+ new_modul_id +'[time_to]" value=""/></td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td colspan="2" class="list-inline"> ';
						html += '<ul>'; 
							html += '<li><input type="checkbox" name="html_setting_'+ new_modul_id +'[time_day][1]" value="1"/>';
							html += '<span style="padding-right:10px"><?php echo $entry_days_week_monday;?></span></li>';
							html += '<li><input type="checkbox" name="html_setting_'+ new_modul_id +'[time_day][2]" value="1"/>';
							html += '<span style="padding-right:10px"><?php echo $entry_days_week_tuesday;?></span></li>';
							html += '<li><input type="checkbox" name="html_setting_'+ new_modul_id +'[time_day][3]" value="1"/>';
							html += '<span style="padding-right:10px"><?php echo $entry_days_week_medium;?></span></li>';
							html += '<li><input type="checkbox" name="html_setting_'+ new_modul_id +'[time_day][4]" value="1"/>';
							html += '<span style="padding-right:10px"><?php echo $entry_days_week_thursday;?></span></li>';
							html += '<li><input type="checkbox" name="html_setting_'+ new_modul_id +'[time_day][5]" value="1"/>';
							html += '<span style="padding-right:10px"><?php echo $entry_days_week_friday;?></span></li>';
							html += '<li><input type="checkbox" name="html_setting_'+ new_modul_id +'[time_day][6]" value="1"/>';
							html += '<span style="padding-right:10px"><?php echo $entry_days_week_saturday;?></span></li>';
							html += '<li><input type="checkbox" name="html_setting_'+ new_modul_id +'[time_day][0]" value="1"/>';
							html += '<span style="padding-right:10px"><?php echo $entry_days_week_sunday;?></span></li>';
						 html += '</ul>';
					html += '</td>';
				html += '</tr>';
				html += '<div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>';
				html += '<tr>';
					html += '<td rowspan="2"><?php echo $entry_store; ?></td>';
					html += '<td>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][store]" value="0" checked="checked"/><?php echo $entry_paragraph_enable;?>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][store]" value="1"/><?php echo $entry_paragraph_deleted;?>';
					html += '</td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td colspan="2">';
						html += '<div class="scrollbox">'; 
							<?php if (!isset($stores['0'])){ ?>
								html += '<input type="checkbox" name="html_setting_'+ new_modul_id +'[product_store][0]" value="1" checked="checked" /><?php echo $text_default;?>';
							<?php } else { ?>			
								html += '<input type="checkbox" name="html_setting_'+ new_modul_id +'[product_store][0]" value="1"/><?php echo $text_default;?>';
								<?php $class = 'odd'; ?>
								<?php foreach ($stores as $store) { ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										html += '<div class="<?php echo $class; ?>">';
											html += '<input type="checkbox" name="html_setting_'+ new_modul_id +'[product_store][<?php echo $store['store_id']; ?>]" value="1"/>';
											html += '<?php echo $store['name'];?>';
										html += '</div>';																
								<?php } ?>
							<?php } ?> 
						html += '</div>'; 
					html += '</td>';
				html += '</tr>';
				<!-- Производители-->
				html += '<tr>';
					html += '<td rowspan="2"><?php echo $entry_manufacturer; ?></td>';
					html += '<td>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][manufacturer]" value="0" checked="checked"/>';
							html += '<?php echo $entry_paragraph_enable;?>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][manufacturer]" value="1"/>';
							html += '<?php echo $entry_paragraph_deleted;?>';
					html += '</td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td colspan="2">';
						html += '<div class="scrollbox">';
							<?php $class = 'odd'; ?>
							<?php foreach ($manufacturer_info as $manufacturer_in) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								html += '<div class="<?php echo $class; ?>">';
									html += '<input type="checkbox" name="html_setting_'+ new_modul_id +'[manufacturer][<?php echo addslashes($manufacturer_in['manufacturer_id']); ?>]" value="1"/>';
									html += '<?php echo addslashes($manufacturer_in['name']);?>';
								html += '</div>';
							<?php } ?>
						html += '</div>';
					html += '</td>';
				html += '</tr>';
				<!-- Категории-->
				html += '<tr>';
					html += '<td rowspan="2"><?php echo $entry_category; ?></td>';
					html += '<td>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][category]" value="0" checked="checked"/>';
							html += '<?php echo $entry_paragraph_enable;?>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][category]" value="1"/>';
							html += '<?php echo $entry_paragraph_deleted;?>';
					html += '</td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td colspan="2">';
						html += '<div class="scrollbox">';
							<?php $class = 'odd'; ?>
							<?php foreach ($categories_info as $categories_in) { ?>
								<?php if($categories_in['language_id'] == $language_id) { ?>	
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>								
									html += '<div class="<?php echo $class; ?>">';
											html += '<input type="checkbox" name="html_setting_'+ new_modul_id +'[category][<?php echo addslashes($categories_in['category_id']); ?>]" value="1"/>';
											html += '<?php echo addslashes($categories_in['name']);?>';
									html += '</div>';
								<?php } ?>
							<?php } ?>
						html += '</div>';
					html += '</td>';
				html += '</tr>';
				<!-- Товары-->
				html += '<tr>';
					html += '<td rowspan="2"><?php echo $entry_product; ?></td>';
					html += '<td>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][product]" value="0" checked="checked"/>';
							html += '<?php echo $entry_paragraph_enable;?>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][product]" value="1"/>'; 
							html += '<?php echo $entry_paragraph_deleted;?>';
					html += '</td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td colspan="2">';
						html += '<div class="scrollbox">';
							<?php $class = 'odd'; ?>
							<?php foreach ($product_info as $product_in) { ?>					
								<?php if($product_in['language_id'] == $language_id) { ?>	
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>								
									html += '<div class="<?php echo $class; ?>">';
											html += '<input type="checkbox" name="html_setting_'+ new_modul_id +'[product][<?php echo addslashes($product_in['product_id']); ?>]" value="1"/>';
											html += '<?php echo addslashes($product_in['name']);?>';
									html += '</div>';
								<?php } ?>
							<?php } ?>
						html += '</div>';
					html += '</td>';
				html += '</tr>';
				<!-- Для авторизированных или для неавторезированных  -->
				html += '<tr>';
					html += '<td><?php echo $entry_authorization; ?></td>';
					html += '<td>';
						html += '<div class="scrollbox"> ';
						<?php $module_value['authorization'] = (isset($module_value['authorization']))? $module_value['authorization'] : "1"; ?>
							html += '<div>';
								html += '<input type="radio" name="html_setting_'+ new_modul_id +'[authorization]" value="1"/>';
								html += '<?php echo $authorization_all;?>';					
							html += '</div>';
							html += '<div>';
								html += '<input type="radio" name="html_setting_'+ new_modul_id +'[authorization]" value="2"'; 
								html += '<?php echo $authorization_on;?>';
							html += '</div>';							
							html += '<div>';
								html += '<input type="radio" name="html_setting_'+ new_modul_id +'[authorization]" value="3"/>'; 
								html += '<?php echo $authorization_off;?>';
							html += '</div>';
						html += '</div>';
					html += '</td>';
				html += '</tr>';
				<!-- Группы Клиентов-->
				html += '<tr>';					
					html += '<td rowspan="2"><?php echo $entry_grup_сustomers; ?></td>';
					html += '<td>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][grup_сustomers]" value="0"checked="checked"/>';
							html += '<?php echo $entry_paragraph_enable;?>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][grup_сustomers]" value="1"/>';
							html += '<?php echo $entry_paragraph_deleted;?>';
					html += '</td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td colspan="2">';
						html += '<div class="scrollbox">'; 
							<?php $class = 'odd'; ?>
							<?php foreach ($grup_clients_info as $grup_clients_in) { ?>
								<?php if($grup_clients_in['language_id'] == $language['language_id']) { ?>							
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>								
									html += '<div class="<?php echo $class; ?>">';
											html += '<input type="checkbox" name="html_setting_'+ new_modul_id +'[grup_clients][<?php echo $grup_clients_in['customer_group_id']; ?>]" value="1"/>';
											html += '<?php echo $grup_clients_in['name'];?>';
									html += '</div>';
								<?php } ?>
							<?php } ?>
						html += '</div>';
					html += '</td>';
				html += '</tr>';
				
				  <!-- Клиенты -->
				html += '<tr>';					
					html += '<td rowspan="2"><?php echo $entry_сustomer; ?></td>';
					html += '<td>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][сustomer]" value="0" checked="checked"/>';
							html += '<?php echo addslashes($entry_paragraph_enable);?>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][сustomer]" value="1"/>';
							html += '<?php echo addslashes($entry_paragraph_deleted);?>';
					html += '</td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td colspan="2">';
						html += '<div class="scrollbox">';
							<?php $class = 'odd'; ?>
							<?php foreach ($сustomer_info as $сustomer_in) { ?>						
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>								
								html += '<div class="<?php echo $class; ?>">';
									html += '<input type="checkbox" name="html_setting_'+ new_modul_id +'[сustomer][<?php echo addslashes($сustomer_in['customer_id']); ?>]" value="1"/>';
								html += '</div>';
							<?php } ?>
						html += '</div>';
					html += '</td>';
				html += '</tr>';			
				<!-- Языки-->
				html += '<tr>';					
					html += '<td rowspan="2"><?php echo $entry_language; ?></td>';
					html += '<td>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][language]" value="0" checked="checked"/>'; 
							html += '<?php echo $entry_paragraph_enable;?>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][language]" value="1"/>'; 
							html += '<?php echo $entry_paragraph_deleted;?>';
					html += '</td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td colspan="2">';	
						html += '<div class="scrollbox"> ';
							<?php $class = 'odd'; ?>				
							<?php foreach ($language_info as $language_in) { ?>							
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>								
								html += '<div class="<?php echo $class; ?>">';
									html += '<input type="checkbox" name="html_setting_'+ new_modul_id +'[language_m][<?php echo $language_in['language_id']; ?>]" value="1"/>';
										html += '<?php echo $language_in['name'];?>';
								html += '</div>';
							<?php } ?>
						html += '</div>';
					html += '</td>';
				html += '</tr>';
				<!-- Страницы -->
				html += '<tr>';					
					html += '<td rowspan="2"><?php echo $entry_information; ?></td>';
					html += '<td>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][information]" value="0" checked="checked"/>'; 
							html += '<?php echo $entry_paragraph_enable;?>';
						html += '<input type="radio" name="html_setting_'+ new_modul_id +'[paragraph_status][information]" value="1"/>'; 
							html += '<?php echo $entry_paragraph_deleted;?>';
					html += '</td>';	
				html += '</tr>';
				html += '<tr>';
					html += '<td colspan="2">';	
						html += '<div class="scrollbox"> ';
							<?php $class = 'odd'; ?>	
							<?php foreach ($information_sort as $information_in) { ?>  
								<?php if($information_in['language_id'] == $language['language_id']) { ?>							
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>								
									html += '<div class="<?php echo $class; ?>">';
										html += '<input type="checkbox" name="html_setting_'+ new_modul_id +'[information][<?php echo $information_in['information_id']; ?>]" value="1"/>';
											html += '<?php echo $information_in['title'];?>';  
									html += '</div>';
								<?php } ?>
							<?php } ?>
						html += '</div>';
					html += '</td>';
				html += '</tr>';
			html += '</table>';	
		html += '</div>';
		<!-- Оформление -->
		html += '<div class="tab-pane" id="tab-view-'+ new_modul_id +'">';
			<!-- Использовать оформление -->
			html += '<table class="form">';
				html += '<tr>';
					html += '<td><?php echo $entry_decor_status; ?></td>';
					html += '<td>';
						html += '<select name="html_setting_'+ new_modul_id +'[decor_status]" id="input-decor-status" class="form-control">';
							html += '<option value="1"><?php echo $text_enabled; ?></option>';
							html += '<option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
						html += '</select>';
					html += '</td>';
				html += '</tr>';
				<!-- Оформление -->
				html += '<tr>';		
					html += '<td rowspan="3"><?php echo $text_decor; ?></td>';
					html += '<td>';
						html += '<div class="panel-group" id="accordion">';
						  <!-- 1 Основное -->
						  html += '<div class="panel panel-default">';
							html += '<div class="panel-heading">';
								html += '<a data-toggle="collapse" data-parent="#accordion" href="#text_decor"><?php echo $help_titel_decor; ?></a>';
							html += '</div>';
							html += '<div id="text_decor" class="panel-collapse collapse">';
							  html += '<div class="panel-body">';
								html += '<p><?php echo $help_text_decor; ?></p>';
							  html += '</div>';
							html += '</div>';
						  html += '</div>';
						  html += '</div>';
					html += '</td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td colspan="2">';
						  <!-- 2 Короткий код -->
						  html += '<div class="panel panel-default">';
							html += '<div class="panel-heading">';
								html += '<a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><?php echo $help_titel_shortcodes; ?></a>';
							html += '</div>';
							html += '<div id="collapse2" class="panel-collapse collapse">';
							  html += '<div class="panel-body">';
								html += '<p><?php echo $help_text_shortcodes; ?></p>';
							  html += '</div>';
							html += '</div>';
						  html += '</div> ';
					html += '</td>';
				html += '</tr>';
				html += '<tr>';
					html += '<td colspan="2">';
						html += '<textarea class="form-control" style = "height: 200px; width:80%" name="html_setting_'+ new_modul_id +'[html_ultra_decor]" placeholder="<?php echo $text_placeholder_decor; ?>"></textarea>';	 		
					html += '</td>';
				html += '</tr>';
					<!-- CSS -->
				html += '<tr>';					
					html += '<td><?php echo $entry_css; ?></td>';
					html += '<td>';
						html += '<textarea class="form-control" style = "height: 200px; width:80%" name="html_setting_'+ new_modul_id +'[html_ultra_css]" placeholder="<?php echo $text_placeholder_css; ?>"></textarea>	';		
					html += '</td>';
				html += '</tr>';
			html += '</table>';
		html += '</div>';
		html += '</div>';
	html += '</div>';
	$('#new_module_'+ new_modul_id +'').before(html);	
	<?php $language_i=0; ?>										
		<?php foreach ($languages as $language) { ?>
			
			CKEDITOR.config.indentClasses = ["ul-grey", "ul-red", "text-red", "ul-content-red", "circle", "style-none", "decimal", "paragraph-portfolio-top", "ul-portfolio-top", "url-portfolio-top", "text-grey"];
			CKEDITOR.config.protectedSource.push(/<(style)[^>]*>.*<\/style>/ig);// разрешить теги <style>
			CKEDITOR.config.protectedSource.push(/<(script)[^>]*>.*<\/script>/ig);// разрешить теги <script>
			CKEDITOR.config.protectedSource.push(/<\?[\s\S]*?\?>/g);// разрешить php-код
			CKEDITOR.config.protectedSource.push(/<!--dev-->[\s\S]*<!--\/dev-->/g);
			CKEDITOR.config.allowedContent = true; /* все теги */
			CKEDITOR.replace('description'+ new_modul_id +'<?php echo "_".$language['language_id']; ?>', {
			filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token_sesiya; ?>',
			filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token_sesiya; ?>',
			filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token_sesiya; ?>',
			filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token_sesiya; ?>',
			filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token_sesiya; ?>',
			filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token_sesiya; ?>'
		});
		<?php $language_i++; ?>									
	<?php } ?> 
	new_modul_id++;
	
	
}
//--></script> 
<script type="text/javascript">
  $(function () {
    $('.datetimepicker_from').datetimepicker({
		format: 'DD-MM-YYYY HH:mm:ss'
	});
  });
  $(function () {
    $('.datetimepicker_to').datetimepicker({
		format: 'DD-MM-YYYY HH:mm:ss'
	});
  });
  $(function () {
    $('.timepicker_from').datetimepicker({
		pickDate:false,
		format: 'HH:mm:ss'
	});
  });
  $(function () {
    $('.timepicker_to').datetimepicker({
		pickDate:false,
		format: 'HH:mm:ss'
	});
  });
</script>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>
<?php echo $footer; ?>