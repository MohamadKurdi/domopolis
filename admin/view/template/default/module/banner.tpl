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
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_banner; ?></td>
              <td class="left"><?php echo $entry_dimension; ?> <i class="fa fa-desktop" aria-hidden="true"></i></td>
              <td class="left"><?php echo $entry_dimension; ?> <i class="fa fa-mobile" aria-hidden="true"></i></td>
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
                  <div class="scrollbox" style="width:350px; height:200px;">
                    <?php $class = 'even'; ?>                     
                    <?php foreach ($banners as $banner) { ?>
                      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                      <div class="<?php echo $class; ?>">
                        <?php if (is_array($module['banner_id']) && in_array($banner['banner_id'], $module['banner_id'])) { ?>
                          <input id="banner_module_<?php echo $module_row; ?>_<?php echo $banner['banner_id']; ?>" class="checkbox" type="checkbox" name="banner_module[<?php echo $module_row; ?>][banner_id][]" value="<?php echo $banner['banner_id']; ?>" checked="checked" />
                          <label for="banner_module_<?php echo $module_row; ?>_<?php echo $banner['banner_id']; ?>"><?php echo $banner['name']; ?></label>
                        <?php } else { ?>
                         <input id="banner_module_<?php echo $module_row; ?>_<?php echo $banner['banner_id']; ?>" class="checkbox" type="checkbox" name="banner_module[<?php echo $module_row; ?>][banner_id][]" value="<?php echo $banner['banner_id']; ?>"  />
                         <label for="banner_module_<?php echo $module_row; ?>_<?php echo $banner['banner_id']; ?>"><?php echo $banner['name']; ?></label>
                       <?php } ?>
                     </div>
                   <?php } ?>
                 </div>
              </td>
                <td class="left">
                  <input type="number" step="1" name="banner_module[<?php echo $module_row; ?>][width]" value="<?php echo $module['width']; ?>" size="3" style="width:90px;" />
                  <input type="number" step="1" name="banner_module[<?php echo $module_row; ?>][height]" value="<?php echo $module['height']; ?>" size="3" style="width:90px;" />
                  <?php if (isset($error_dimension[$module_row])) { ?>
                    <span class="error"><?php echo $error_dimension[$module_row]; ?></span>
                  <?php } ?>
                </td>
                <td class="left">
                  <input type="number" step="1" name="banner_module[<?php echo $module_row; ?>][width_sm]" value="<?php echo $module['width_sm']; ?>" size="3" style="width:90px;" />
                  <input type="number" step="1" name="banner_module[<?php echo $module_row; ?>][height_sm]" value="<?php echo $module['height_sm']; ?>" size="3" style="width:90px;"/>                
                </td> 
                <td class="left">
                  <select name="banner_module[<?php echo $module_row; ?>][layout_id]">
                    <?php foreach ($layouts as $layout) { ?>
                      <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                        <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </td>
                <td class="left"><select name="banner_module[<?php echo $module_row; ?>][position]">
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
                <td class="left"><select name="banner_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
                <td class="right"><input type="text" name="banner_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
                <td class="right"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
              </tr>
            </tbody>
            <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="6"></td>
              <td class="right"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
 html  = '<tbody id="module-row' + module_row + '">';
 html += '  <tr>';
 html += '    <td class="left">';
 html += '<div class="scrollbox" style="width:350px; height:200px;">';
 <?php $class = 'even'; ?>                     
 <?php foreach ($banners as $banner) { ?>
  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
  html += '<div class="<?php echo $class; ?>">';
  html += '                        <input id="banner_module_<?php echo $module_row; ?>_<?php echo $banner['banner_id']; ?>" class="checkbox" type="checkbox" name="banner_module[<?php echo $module_row; ?>][banner_id][]" value="<?php echo $banner['banner_id']; ?>"  />';
  html += '                       <label for="banner_module_<?php echo $module_row; ?>_<?php echo $banner['banner_id']; ?>"><?php echo $banner['name']; ?></label>';
  html += '                    </div>';
<?php } ?>
html += '                </div>';

html += '</td>';
 html += '    <td class="left"><input type="number" step="1" name="banner_module[' + module_row + '][width]" value="" size="3" style="width:90px;" /> <input type="number" step="1" name="banner_module[' + module_row + '][height]" value="" size="3" style="width:90px;" /></td>'; 
  html += '    <td class="left"><input type="number" step="1" name="banner_module[' + module_row + '][width_sm]" value="" size="3" style="width:90px;" /> <input type="number" step="1" name="banner_module[' + module_row + '][height_sm]" value="" size="3" style="width:90px;" /></td>';
 html += '    <td class="left"><select name="banner_module[' + module_row + '][layout_id]">';
 <?php foreach ($layouts as $layout) { ?>
   html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
 <?php } ?>
 html += '    </select></td>';
 html += '    <td class="left"><select name="banner_module[' + module_row + '][position]">';
 html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
 html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
 html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
 html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
 html += '    </select></td>';
 html += '    <td class="left"><select name="banner_module[' + module_row + '][status]">';
 html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
 html += '      <option value="0"><?php echo $text_disabled; ?></option>';
 html += '    </select></td>';
 html += '    <td class="right"><input type="text" name="banner_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
 html += '    <td class="right"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
 html += '  </tr>';
 html += '</tbody>';

 $('#module tfoot').before(html);

 module_row++;
}
//--></script> 
<?php echo $footer; ?>