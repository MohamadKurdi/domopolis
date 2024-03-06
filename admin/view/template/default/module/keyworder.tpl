<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($success) { ?>
  <div class="success "><?php echo $success; ?></div>
  <?php } ?>
  <?php if ($warning) { ?>
  <div class="warning"><?php echo $warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading order_head">
      <h1><img src="view/image/module.png" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
		<a href="<?php echo $keyworder_manufacturer; ?>"class="button" style="float: left;"><?php echo $button_keyworder_manufacturer; ?></a>
		<a class="button" style="float: left; background: #ccc; cursor: default;"><?php echo $button_settings; ?></a>
		<img src="view/image/setting.png" style="float: left;margin: 0 50px 0 10px;" />
		<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
		<a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <h2><?php echo $text_module_location; ?></h2>
		<table id="module" class="list">
          <div class="attention"><?php echo $text_notice; ?></div>
		  <thead>
            <tr>
              <td class="left"><?php echo $entry_layout; ?></td>
              <td class="left"><?php echo $entry_position; ?></td>
			  <td class="left"><?php echo $entry_image; ?></td>
			  <td class="left"><?php echo $entry_size; ?></td>
			  <td class="left"><?php echo $entry_count; ?></td>
              <td class="left"><?php echo $entry_sort_order; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) { ?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
              <td class="left">
				<select name="keyworder_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
			  </td>
              <td class="left">
				<select name="keyworder_module[<?php echo $module_row; ?>][position]">
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
                </select>
			  </td>
              <td class="left">
				<select name="keyworder_module[<?php echo $module_row; ?>][image_status]">
                  <?php if ($module['image_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			  </td>
              <td class="left"><input type="text" name="keyworder_module[<?php echo $module_row; ?>][width]" value="<?php echo $module['width']; ?>" size="3" /> Х <input type="text" name="keyworder_module[<?php echo $module_row; ?>][height]" value="<?php echo $module['height']; ?>" size="3" /></td>
              <td class="left">
				<select name="keyworder_module[<?php echo $module_row; ?>][count]">
                  <?php if ($module['count']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			  </td>
              <td class="left"><input type="text" name="keyworder_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
              <td class="left">
				<select name="keyworder_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			  </td>
              <td class="right"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="7"></td>
              <td class="right"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
            </tr>
          </tfoot>
        </table>
		<h2><?php echo $text_module_template; ?></h2>
		<div class="attention"><?php echo $text_template_help; ?>.<br /><br />Перед генерацией ВАЖНО! Нажать "пересканировать" на предыдущем окне. Поскольку при генерации используется выражение "UPDATE", а не "INSERT". Скрипт сканирования переписан так, что по умолчанию включается все для всех языков.<br /><br />Также при использовании переменных, следует ЖЕЛАТЕЛЬНО обратить внимание на то, чтоб данные категории/бренда были заполнены на всех языках.</div>
        <table class="form">
			<tr>
			  <td style="width: 40%; vertical-align: top;">
				<table class="list">
				  <thead>
					<tr>
					  <td class="left"><?php echo $text_column_name; ?> <?php echo $text_category; ?></td>
					  <td class="left">{category_name}</td>
					</tr>
					<tr>
					  <td class="left"><?php echo $column_h1; ?> <?php echo $text_category; ?></td>
					  <td class="left">{category_h1}</td>
					</tr>
					<tr>
				  	  <td class="left"><?php echo $column_title; ?> <?php echo $text_category; ?></td>
					  <td class="left">{category_title}</td>
					</tr>
					<tr>
					  <td class="left"><?php echo $column_meta_keyword; ?> <?php echo $text_category; ?></td>
					  <td class="left">{category_meta_keyword}</td>
					</tr>
					<tr>
				  	  <td class="left"><?php echo $column_meta_description; ?> <?php echo $text_category; ?></td>
					  <td class="left">{category_meta_description}</td>
					</tr>
					<tr>
					  <td class="left"><?php echo $column_description; ?> <?php echo $text_category; ?></td>
					  <td class="left">{category_description}</td>
					</tr>
					<tr>
					  <td class="left"><?php echo $text_column_name; ?> <?php echo $text_manufacturer; ?></td>
					  <td class="left">{manufacturer_name}</td>
					</tr>
					<tr>
					  <td class="left"><?php echo $column_h1; ?> <?php echo $text_manufacturer; ?></td>
					  <td class="left">{manufacturer_h1}</td>
					</tr>
					<tr>
					  <td class="left"><?php echo $column_title; ?> <?php echo $text_manufacturer; ?></td>
					  <td class="left">{manufacturer_title}</td>
					</tr>
					<tr>
					  <td class="left"><?php echo $column_meta_keyword; ?> <?php echo $text_manufacturer; ?></td>
					  <td class="left">{manufacturer_meta_keyword}</td>
					</tr>
					<tr>
					  <td class="left"><?php echo $column_meta_description; ?> <?php echo $text_manufacturer; ?></td>
					  <td class="left">{manufacturer_meta_description}</td>
					</tr>
					<tr>
					  <td class="left"><?php echo $column_description; ?> <?php echo $text_manufacturer; ?></td>
					  <td class="left">{manufacturer_description}</td>
					</tr>
				  </thead>
				</table>
				
			  </td>
			  <td style="vertical-align: top;">
			    <div id="tabs" class="htabs" style="max-width:700px;height:80px;">
                <?php foreach ($languages as $language) { ?>
                  <a href="#cus_tab-language-<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                <?php } ?>
                </div>
				<div class="clear"></div>
				<?php foreach ($languages as $language) { ?>
				<div id="cus_tab-language-<?php echo $language['language_id']; ?>">
				  <table class="form">
					<tr>
					  <td><?php echo $text_image_description; ?></td>
					  <td>
						<select name="keyworder_template[<?php echo $language['language_id']; ?>][image_description]">
							<?php if (isset($keyworder_template[$language['language_id']]['image_description'])) { ?>
							<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
							<option value="0"><?php echo $text_disabled; ?></option>
						    <?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
							<?php } ?>
						</select>
					  </td>
					  <td>
						Перезап.
					  </td>
					</tr>
					<tr>
					  <td><?php echo $column_h1; ?></td>
					  <td><input type="text" name="keyworder_template[<?php echo $language['language_id']; ?>][seo_h1]" value="<?php echo isset($keyworder_template[$language['language_id']]) ? $keyworder_template[$language['language_id']]['seo_h1'] : ''; ?>" size="100" /></td>
					  <td>
						<input class="overwrite" type="checkbox" name="keyworder_template[<?php echo $language['language_id']; ?>][seo_h1_overwrite]" <?php if (isset($keyworder_template[$language['language_id']]['seo_h1_overwrite'])) echo "checked='checked'"; ?>>
					</td>
					</tr>
					<tr>
					  <td><?php echo $column_title; ?></td>
					  <td><input type="text" name="keyworder_template[<?php echo $language['language_id']; ?>][seo_title]" value="<?php echo isset($keyworder_template[$language['language_id']]) ? $keyworder_template[$language['language_id']]['seo_title'] : ''; ?>" size="100" /></td>
					   <td>
						<input class="overwrite" type="checkbox" name="keyworder_template[<?php echo $language['language_id']; ?>][seo_title_overwrite]" <?php if (isset($keyworder_template[$language['language_id']]['seo_title_overwrite'])) echo "checked='checked'"; ?>>
					</td>
					</tr>
					<tr>
					  <td><?php echo $column_meta_keyword; ?></td>
					  <td><input type="text" name="keyworder_template[<?php echo $language['language_id']; ?>][meta_keyword]" value="<?php echo isset($keyworder_template[$language['language_id']]) ? $keyworder_template[$language['language_id']]['meta_keyword'] : ''; ?>" size="100" /></td>
					   <td>
						<input class="overwrite" type="checkbox" name="keyworder_template[<?php echo $language['language_id']; ?>][meta_keyword_overwrite]" <?php if (isset($keyworder_template[$language['language_id']]['meta_keyword_overwrite'])) echo "checked='checked'"; ?>>
					</td>
					</tr>
					<tr>
					  <td><?php echo $column_meta_description; ?></td>
					  <td><textarea name="keyworder_template[<?php echo $language['language_id']; ?>][meta_description]" cols="100" rows="3"><?php echo isset($keyworder_template[$language['language_id']]) ? $keyworder_template[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
				
					 <td>
						<input class="overwrite" type="checkbox" name="keyworder_template[<?php echo $language['language_id']; ?>][meta_description_overwrite]" <?php if (isset($keyworder_template[$language['language_id']]['meta_description_overwrite'])) echo "checked='checked'"; ?>>
					</td>
				</tr>
					<tr>
					  <td><?php echo $column_description; ?></td>
					  <td><textarea name="keyworder_template[<?php echo $language['language_id']; ?>][description]" cols="100" rows="3"><?php echo isset($keyworder_template[$language['language_id']]) ? $keyworder_template[$language['language_id']]['description'] : ''; ?></textarea></td>
					   <td>
						<input class="overwrite" type="checkbox" name="keyworder_template[<?php echo $language['language_id']; ?>][description_overwrite]" <?php if (isset($keyworder_template[$language['language_id']]['description_overwrite'])) echo "checked='checked'"; ?>>
					</td>
					</tr>
                  </table>
                </div>
				<?php } ?>
			  </td>
			</tr>
        </table>
		<a id="generate_url" style="display: none" href="<?php echo $generate_url; ?>"></a>
		<input type="button" value="Генерировать и записать в базу!" style="width:100%; font-size:16px; padding:10px;" onclick="generate();" />
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function generate() {
	$(".success").remove();
	$(".attention").after('<div class="success">Начали.. Не трогаем ничего...</div>');	
	
	var data = $('#form :input').serialize();
	$.post($('#generate_url').attr('href'), data, function(html) {
		$(".success").html(html);
	});
}


$('#tabs a').tabs();

var module_row = <?php echo $module_row; ?>;

function addModule() {
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="keyworder_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="keyworder_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="keyworder_module[' + module_row + '][image_status]">';
    html += '      <option value="1"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="left"><input type="text" name="keyworder_module[' + module_row + '][width]" value="20" size="3" /> Х <input type="text" name="keyworder_module[' + module_row + '][height]" value="20" size="3" /></td>';
	html += '    <td class="left"><select name="keyworder_module[' + module_row + '][count]">';
    html += '      <option value="1"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="left"><input type="text" name="keyworder_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><select name="keyworder_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}

setInterval (function () {
    $('.breadcrumb + .success').fadeOut('slow');
}, 5000);
//--></script> 
<?php echo $footer; ?>