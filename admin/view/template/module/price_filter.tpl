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
        <div class="heading">
            <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table id="module" class="list">
                    <thead>
                        <tr>
                            <td class="left"><?php echo $entry_category; ?></td>
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
                                    <input class="dont_chcked" onclick="disableCategories(<?php echo $module_row; ?>);"   name="price_filter_module[<?php echo $module_row; ?>][display_in_all]" type="checkbox" value="1" <?php if (isset($module['display_in_all']) && $module['display_in_all'] == '1') { ?>checked<?php } ?> /> Display in ALL Categories<br/>
                                    <br />
                                    <b>OR select Categories:</b>
                                    <br />
                                    <br />
                                    <?php
                                    if (isset($module['category_source'])) {
                                        $a = $module['category_source'];
                                    } else {
                                        $a = array();
                                    }
                                    ?>
                                    <?php foreach ($categories as $category) { ?>
                                        <input <?php if (isset($module['display_in_all']) && $module['display_in_all'] == '1') { ?> disabled="disabled" <?php } ?> class="category_item_<?php echo $module_row; ?>" name="price_filter_module[<?php echo $module_row; ?>][category_source][]" type="checkbox" value="<?php echo $category['category_id']; ?>" <?php if (in_array($category['category_id'], $a)) { ?>checked<?php } ?> /><?php echo $category['name'] ?><br/>
                                    <?php } ?>
                                    <br />
                                    <a onclick="$(this).parent().find(':checkbox:not( .dont_chcked )').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox:not( .dont_chcked )').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
                                </td>
                                <td class="left"><select name="price_filter_module[<?php echo $module_row; ?>][layout_id]">
                                        <?php foreach ($layouts as $layout) { ?>
                                            <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                                                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                <br />
                                        <small>Module works only Category layout!</small>
                                </td>
                                <td class="left"><select name="price_filter_module[<?php echo $module_row; ?>][position]">
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
                                <td class="left"><select name="price_filter_module[<?php echo $module_row; ?>][status]">
                                        <?php if ($module['status']) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select></td>
                                <td class="right"><input type="text" name="price_filter_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
                                <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
                            </tr>
                        </tbody>
                        <?php $module_row++; ?>
                    <?php } ?>
                    <tfoot>
                        <tr>
                            <td colspan="5"></td>
                            <td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    
    function disableCategories(row){
        if ($('.category_item_'+row).attr('disabled') == 'disabled'){
             $('.category_item_'+row).removeAttr('disabled');
        }else{
             $('.category_item_'+row).attr('disabled', 'disabled');
        }
       
    }
    
var module_row = <?php echo $module_row; ?>;

    function addModule() {
        html = '<tbody id="module-row' + module_row + '">';
        html += '  <tr>';
                html += '<td class="left">';
         html += '<input class="dont_chcked" onclick="disableCategories(' + module_row + ');" name="price_filter_module[' + module_row + '][display_in_all]" type="checkbox" value="1" /> Display in ALL Categories<br/>';
                                        html += '<br />';
                                        html += '<b>OR select Categories:</b>';
                                        html += '<br />';
                                       
        html += '<br />';
<?php foreach ($categories as $category) { ?>
            html += '<input class="category_item_' + module_row + '" name="price_filter_module[' + module_row + '][category_source][]" type="checkbox" value="<?php echo $category['category_id']; ?>" /><?php echo addslashes($category['name']) ?><br/>';
<?php } ?>
        html += '<br /><a onclick="$(this).parent().find(\':checkbox:not( .dont_chcked )\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox:not( .dont_chcked )\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a>';
        html += ' </td>';
        html += '    <td class="left"><select name="price_filter_module[' + module_row + '][layout_id]">';
<?php foreach ($layouts as $layout) { ?>
            html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
<?php } ?>
        html += '    </select>';
        html += ' <br />';
        html += ' <small>Module works only Category layout!</small></td>';
        html += '    <td class="left"><select name="price_filter_module[' + module_row + '][position]">';
        html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
        html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
        html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
        html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
        html += '    </select></td>';
        html += '    <td class="left"><select name="price_filter_module[' + module_row + '][status]">';
        html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
        html += '      <option value="0"><?php echo $text_disabled; ?></option>';
        html += '    </select></td>';
        html += '    <td class="right"><input type="text" name="price_filter_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
        html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';
        html += '</tbody>';

        $('#module tfoot').before(html);

        module_row++;
    }
//--></script> 
<?php echo $footer; ?>