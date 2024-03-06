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
<?php echo $newspanel; ?>
<style>table.form > tbody > tr > td { padding: 6px; }</style>
<div class="box blogbox">
  <div class="heading order_head">
    <h1><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button sterge"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	<div id="tabs" class="htabs"><a href="#tab-general"><i class="fa fa-cogs"></i> <?php echo $text_bsettings; ?></a><a href="#tab-disqus"><i class="fa fa-comments"></i> <?php echo $tab_disqus; ?></a><a href="#tab-fb"><i class="fa fa-facebook-square"></i> <?php echo $tab_facebook; ?></a><a href="#tab-other"><i class="fa fa-info-circle"></i> <?php echo $tab_other; ?></a><div class="clr"></div></div>
	<div class="th_style"></div>
	<div id="tab-disqus">
		<table class="form">
			<tr>
				<td><?php echo $tab_disqus_enable; ?></td>
				<td><?php if ($bnews_disqus_status) { ?>
                <div class="radiowraper"><input type="radio" name="bnews_disqus_status" value="1" checked="checked" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_disqus_status" value="0" />
                <?php echo $text_no; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="bnews_disqus_status" value="1" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_disqus_status" value="0" checked="checked" />
                <?php echo $text_no; ?></div>
                <?php } ?></td>
			</tr>
			<tr>
				<td><?php echo $tab_disqus_sname; ?></td>
				<td><?php if ($bnews_disqus_sname) { ?>
						<input type="text" name="bnews_disqus_sname" value="<?php echo $bnews_disqus_sname; ?>" size="30" />
					<?php } else { ?>
						<input type="text" name="bnews_disqus_sname" value="short_name" size="30" />
					<?php } ?>
				</td>
			</tr>
		</table>
	</div>
	<div id="tab-fb">
		<table class="form">
			<tr>
				<td><?php echo $tab_facebook_status; ?></td>
				<td><?php if ($bnews_fbcom_status) { ?>
                <div class="radiowraper"><input type="radio" name="bnews_fbcom_status" value="1" checked="checked" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_fbcom_status" value="0" />
                <?php echo $text_no; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="bnews_fbcom_status" value="1" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_fbcom_status" value="0" checked="checked" />
                <?php echo $text_no; ?></div>
                <?php } ?></td>
			</tr>
			<tr>
				<td><?php echo $tab_facebook_appid; ?></td>
				<td><?php if ($bnews_fbcom_appid) { ?>
						<input type="text" name="bnews_fbcom_appid" value="<?php echo $bnews_fbcom_appid; ?>" size="30" />
					<?php } else { ?>
						<input type="text" name="bnews_fbcom_appid" value="" size="30" />
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $tab_facebook_posts; ?></td>
				<td><?php if ($bnews_fbcom_posts) { ?>
						<input type="text" name="bnews_fbcom_posts" value="<?php echo $bnews_fbcom_posts; ?>" size="2" />
					<?php } else { ?>
						<input type="text" name="bnews_fbcom_posts" value="10" size="2" />
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $tab_facebook_theme ?></td>
				<td><?php if ($bnews_fbcom_theme == 'light') { ?>
                <div class="radiowraper"><input type="radio" name="bnews_fbcom_theme" value="light" checked="checked" />
                Light</div>
                <div class="radiowraper"><input type="radio" name="bnews_fbcom_theme" value="dark" />
                Dark</div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="bnews_fbcom_theme" value="light" />
                Light</div>
                <div class="radiowraper"><input type="radio" name="bnews_fbcom_theme" value="dark" checked="checked" />
                Dark</div>
                <?php } ?></td>
			</tr>
		</table>
	</div>
	<div id="tab-other">
		<table class="form">
			<tr>
				<td><?php echo $text_facebook_tags; ?></td>
				<td><?php if (!$bnews_facebook_tags) { ?>
                <div class="radiowraper"><input type="radio" name="bnews_facebook_tags" value="0" checked="checked" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_facebook_tags" value="1" />
                <?php echo $text_no; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="bnews_facebook_tags" value="0" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_facebook_tags" value="1" checked="checked" />
                <?php echo $text_no; ?></div>
                <?php } ?></td>
			</tr>
			<tr>
				<td><?php echo $text_twitter_tags; ?></td>
				<td><?php if (!$bnews_twitter_tags) { ?>
                <div class="radiowraper"><input type="radio" name="bnews_twitter_tags" value="0" checked="checked" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_twitter_tags" value="1" />
                <?php echo $text_no; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="bnews_twitter_tags" value="0" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_twitter_tags" value="1" checked="checked" />
                <?php echo $text_no; ?></div>
                <?php } ?></td>
			</tr>
		</table>
	</div>
<div id="tab-general">
	<table class="form">
    <tr>
              <td><?php echo $text_bnews_order; ?></td>
              <td><?php if ($bnews_order) { ?>
                <div class="radiowraper"><input type="radio" name="bnews_order" value="1" checked="checked" />
                <?php echo $text_yess; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_order" value="0" />
                <?php echo $text_noo; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="bnews_order" value="1" />
                <?php echo $text_yess; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_order" value="0" checked="checked" />
                <?php echo $text_noo; ?></div>
                <?php } ?></td>
    </tr>
	 <tr>
              <td><?php echo $text_bnews_display_style; ?></td>
              <td><?php if ($bnews_display_style) { ?>
                <div class="radiowraper"><input type="radio" name="bnews_display_style" value="1" checked="checked" />
                <?php echo $text_bnews_dscols; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_display_style" value="0" />
                <?php echo $text_bnews_dscol; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="bnews_display_style" value="1" />
                <?php echo $text_bnews_dscols; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_display_style" value="0" checked="checked" />
                <?php echo $text_bnews_dscol; ?></div>
                <?php } ?></td>
    </tr>	
	<tr>
              <td><?php echo $text_tplpick; ?></td>
              <td><?php if ($bnews_tplpick) { ?>
                <div class="radiowraper"><input type="radio" name="bnews_tplpick" value="1" checked="checked" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_tplpick" value="0" />
                <?php echo $text_no; ?></div>
                <?php } else { ?>
                <div class="radiowraper"><input type="radio" name="bnews_tplpick" value="1" />
                <?php echo $text_yes; ?></div>
                <div class="radiowraper"><input type="radio" name="bnews_tplpick" value="0" checked="checked" />
                <?php echo $text_no; ?></div>
                <?php } ?></td>
    </tr>
	<tr>
        <td><?php echo $text_bnews_dselements; ?></td>
        <td>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="name" checked="checked" />
                    <?php echo $text_bnews_dse_name; ?>
                <?php } elseif (in_array("name", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="name" checked="checked" />
                    <?php echo $text_bnews_dse_name; ?>
                <?php } else { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="name" />
                    <?php echo $text_bnews_dse_name; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="image" checked="checked" />
                    <?php echo $text_bnews_dse_image; ?>
                <?php } elseif (in_array("image", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="image" checked="checked" />
                    <?php echo $text_bnews_dse_image; ?>
                <?php } else { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="image" />
                    <?php echo $text_bnews_dse_image; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="da" checked="checked" />
                    <?php echo $text_bnews_dse_da; ?>
                <?php } elseif (in_array("da", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="da" checked="checked" />
                    <?php echo $text_bnews_dse_da; ?>
                <?php } else { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="da" />
                    <?php echo $text_bnews_dse_da; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="du" checked="checked" />
                    <?php echo $text_bnews_dse_du; ?>
                <?php } elseif (in_array("du", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="du" checked="checked" />
                    <?php echo $text_bnews_dse_du; ?>
                <?php } else { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="du" />
                    <?php echo $text_bnews_dse_du; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="author" checked="checked" />
                    <?php echo $text_bnews_dse_author; ?>
                <?php } elseif (in_array("author", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="author" checked="checked" />
                    <?php echo $text_bnews_dse_author; ?>
                <?php } else { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="author" />
                    <?php echo $text_bnews_dse_author; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="category" checked="checked" />
                    <?php echo $text_bnews_dse_category; ?>
                <?php } elseif (in_array("category", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="category" checked="checked" />
                    <?php echo $text_bnews_dse_category; ?>
                <?php } else { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="category" />
                    <?php echo $text_bnews_dse_category; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="desc" checked="checked" />
                    <?php echo $text_bnews_dse_desc; ?>
                <?php } elseif (in_array("desc", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="desc" checked="checked" />
                    <?php echo $text_bnews_dse_desc; ?>
                <?php } else { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="desc" />
                    <?php echo $text_bnews_dse_desc; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="button" checked="checked" />
                    <?php echo $text_bnews_dse_button; ?>
                <?php } elseif (in_array("button", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="button" checked="checked" />
                    <?php echo $text_bnews_dse_button; ?>
                <?php } else { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="button" />
                    <?php echo $text_bnews_dse_button; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="com" checked="checked" />
                    <?php echo $text_bnews_dse_com; ?>
                <?php } elseif (in_array("com", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="com" checked="checked" />
                    <?php echo $text_bnews_dse_com; ?>
                <?php } else { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="com" />
                    <?php echo $text_bnews_dse_com; ?>
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="custom1" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 1
                <?php } elseif (in_array("custom1", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="custom1" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 1
                <?php } else { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="custom1" />
                    <?php echo $text_bnews_dse_custom; ?> 1
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="custom2" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 2
                <?php } elseif (in_array("custom2", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="custom2" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 2
                <?php } else { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="custom2" />
                    <?php echo $text_bnews_dse_custom; ?> 2
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="custom3" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 3
                <?php } elseif (in_array("custom3", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="custom3" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 3
                <?php } else { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="custom3" />
                    <?php echo $text_bnews_dse_custom; ?> 3
                <?php } ?>
            </div>
			<div class="radiowraper bes">
				<?php if ($bnews_display_elements_s === 'none') { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="custom4" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 4
                <?php } elseif (in_array("custom4", $bnews_display_elements)) { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="custom4" checked="checked" />
                    <?php echo $text_bnews_dse_custom; ?> 4
                <?php } else { ?>
                    <input type="checkbox" name="bnews_display_elements[]" value="custom4" />
                    <?php echo $text_bnews_dse_custom; ?> 4
                <?php } ?>
            </div>
		</td>
    </tr>
	<tr>
	<td><?php echo $text_bnews_image; ?></td>
    <td>
    <?php echo $text_bwidth; ?> <?php if ($bnews_image_width) { ?>
	<input type="text" name="bnews_image_width" value="<?php echo $bnews_image_width; ?>" size="3" />
	<?php } else { ?>
	<input type="text" name="bnews_image_width" value="80" size="3" />
	<?php } ?>   
	<?php echo $text_bheight; ?> <?php if ($bnews_image_height) { ?>
	<input type="text" name="bnews_image_height" value="<?php echo $bnews_image_height; ?>" size="3" />
	<?php } else { ?>
	<input type="text" name="bnews_image_height" value="80" size="3" />
	<?php } ?>
    </td></tr><tr>
    <td><?php echo $text_bnews_thumb; ?></td>
    <td>
    <?php echo $text_bwidth; ?> <?php if ($bnews_thumb_width) { ?>
	<input type="text" name="bnews_thumb_width" value="<?php echo $bnews_thumb_width; ?>" size="3" />
	<?php } else { ?>
	<input type="text" name="bnews_thumb_width" value="230" size="3" />
	<?php } ?>  
	<?php echo $text_bheight; ?> <?php if ($bnews_thumb_height) { ?>
	<input type="text" name="bnews_thumb_height" value="<?php echo $bnews_thumb_height; ?>" size="3" />
	<?php } else { ?>
	<input type="text" name="bnews_thumb_height" value="230" size="3" />
	<?php } ?>
    </td>	
	</tr>	
 </table>	
 </div>
 <div class="formheading"><?php echo $text_module_form; ?></div>
      <table id="module" class="list">
        <thead>
          <tr>
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
            <td class="left"><select name="ncategory_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
            <td class="left"><select name="ncategory_module[<?php echo $module_row; ?>][position]">
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
            <td class="left"><select name="ncategory_module[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td class="right"><input type="text" name="ncategory_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
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
    </form>
  </div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="ncategory_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="ncategory_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="ncategory_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="ncategory_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
	
	$("select, :radio, :checkbox").uniform();
}
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> 