<?php echo $header; ?>

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

  <div class="ocl-ui-content">
      
      <?php if (isset($error_license) && !empty($error_license)) { ?><div class="warning"><?php echo $error_license; ?></div><?php } ?>
      <?php if (isset($error) && !empty($error)) { ?><div class="warning"><?php echo $error; ?></div><?php } ?>
      <?php if (isset($warning) && !empty($warning)) { ?><div class="warning"><?php echo $warning; ?></div><?php } ?>
      <?php if (isset($success) && !empty($success)) { ?><div class="success"><?php echo $success; ?></div><?php } ?>
      
      <div class="ocl-ui-main-menu">
        <ul>
        	<?php foreach($main_menu as $key => $value){ ?>
        		<?php if($main_cfg[$key] == 1) { ?>
	                <li<?php if($driver == $key) echo ' class="selected"'; ?>><a href="<?php echo $main_url[$key];?>"><?php echo $value;?></a></li>
	            <?php }?>
        	<?php }?>
        </ul>
      </div>

		<div class="ocl-ui-header"><span class="ocl-ui-module-name"><?php echo $ocl_module_name; ?></span><?php echo $tpl_home_page; ?></div>
      
      <!-- BEGIN Categories Driver -->
      <div id="submenu"class="ocl-ui-submenu">
        <ul>
           <li><a href="#tab_export" id="link_tab_export"><?php echo $tab_export; ?></a></li>
           <li><a href="#tab_import" id="link_tab_import"><?php echo $tab_import; ?></a></li>
           <li><a href="#tab_macros" id="link_tab_macros"><?php echo $tab_macros; ?></a></li>
           <!-- <li><a href="#tab_help" id="link_tab_help"><?php echo $tab_help; ?></a></li> -->
        </ul>
     </div>

      <div class="ocl-ui-body-content">
          <!-- BEGIN Categories Export Tab Content -->
          <div id="tab_export" class="ocl-ui-tab-content">
              <form action="<?php echo $action_export; ?>" method="post" id="form_category_export" enctype="multipart/form-data" class="ocl-ui-form-horizontal">
                  <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%;vertical-align: top;">
                            
                        <table class="ocl-ui-form">
                        <tbody>
                            <tr>
                                <td><?php echo $entry_file_encoding; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_export_file_encoding; ?>" /></td>
                                <td><select name="csv_export[file_encoding]" class="span140"><option value="UTF-8" <?php if ( $csv_export['file_encoding'] == 'UTF-8' ) echo 'selected'; ?>>UTF-8</option><option value="WINDOWS-1251" <?php if ( $csv_export['file_encoding'] == 'WINDOWS-1251' ) echo 'selected'; ?>>Windows-1251</option></select></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_languages; ?></td>
                                <td><select class="span140" name="csv_export[language_id]">
                                <?php foreach ($languages as $language) { ?>
                                    <?php if ( $csv_export['language_id'] == $language['language_id'] ) { ?>
                                    <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                              </select></td>
                            </tr>
                            <tr><td colspan="2">
                                    <input type="hidden" name="csv_export[from_category]" value="0" />
                                    <?php echo $entry_category; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_export_all_category; ?>" />
                                    <div class="ocl-ui-scrollbox">
                                        <?php $class = 'odd'; ?>
                                        <?php foreach ($categories as $category) { ?>
                                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                        <label><div class="<?php echo $class; ?>">
                                        <input type="checkbox" name="csv_export[from_category][]" value="<?php echo $category['category_id']; ?>" />
                                        <?php echo $category['name']; ?>
                                        </div></label>
                                        <?php } ?>
                                    </div>
                                    <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_category_delimiter; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_export_delimiter_category; ?>" /></td>
                                <td>
                                    <select class="span80" name="csv_export[delimiter_category]">
                                        <option value="|"<?php if(isset($csv_export['delimiter_category']) && $csv_export['delimiter_category'] == '|'){echo ' selected="selected"';}?>> | </option>
	                                    <option value="/"<?php if(isset($csv_export['delimiter_category']) && $csv_export['delimiter_category'] == '/'){echo ' selected="selected"';}?>> / </option>
	                                    <option value=","<?php if(isset($csv_export['delimiter_category']) && $csv_export['delimiter_category'] == ','){echo ' selected="selected"';}?>> , </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_category_parent; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_export_category_parent; ?>" /></td>
                                <td>
                                    <select class="span140" name="csv_export[category_parent]">
                                            <option value="0"<?php if (!isset($csv_export['category_parent']) || $csv_export['category_parent'] == 0) { echo '  selected="selected"'; }?>><?php echo $text_disabled; ?></option>
                                            <option value="1"<?php if ( isset($csv_export['category_parent']) && $csv_export['category_parent'] == 1 ) { echo '  selected="selected"'; }?>><?php echo $text_enabled; ?></option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                        </table>

                        </td>
                        <td style="width: 50%;vertical-align: top; padding-left: 10px;">
                            <div style="margin-bottom: 6px;">
                                <a onclick="$('.ocl-ui-field-set').find(':checkbox:not(:checked)').parent().parent().parent().hide();"><?php echo $text_hide_all; ?></a>
                                /
                                <a onclick="$('.ocl-ui-field-set').find('tr').show();"><?php echo $text_show_all; ?></a>
                            </div>
                            <table class="ocl-ui-field-set">
                            <?php foreach( $csv_export['fields_set_data'] as $field ) { ?>
                                <tr id="row_<?php echo $field['uid']; ?>">
                                <td>
                                    <label class="checkbox inline" title="<?php echo $fields_set_help[$field['uid']]; ?> <?php echo $field['uid']; ?>">
                                    <input <?php if (array_key_exists($field['uid'], $csv_export['fields_set']) || $field['uid'] == '_ID_') echo 'checked="checked"';?> <?php    if ($field['uid'] == '_ID_') echo ' disabled="disabled" class="field_id" ';?> type="checkbox" id="<?php echo $field['uid']; ?>" name="csv_export[fields_set][<?php echo $field['uid']; ?>]" value="1" />
                                    <?php echo $fields_set_help[$field['uid']]; ?>
                                    </label>
                                </td>
                                <td><span><?php echo $field['uid']; ?></span></td>
                                </tr>
                            <?php } ?>
                            </table>
                            <input type="hidden" name="csv_export[fields_set][_ID_]" value="1">
                            <a onclick="$(this).parent().find(':checkbox').attr('checked', true);initFieldsSet();"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);initFieldsSet();"><?php echo $text_unselect_all; ?></a>
                        </td>
                    </tr>
                  </table>
                  <div class="ocl-ui-buttons left"><a onclick="$('#form_category_export').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_export;?></a></div>
              </form>
          </div>
          <!-- ////////////////////////  END Categories Export Tab Content ////////////////////////  -->
          <!-- ////////////////////////  BEGIN Categories Import Tab Content ////////////////////////  -->
          <div id="tab_import" class="ocl-ui-tab-content">
              <form action="<?php echo $action_import; ?>" method="post" id="form_catgeory_import" enctype="multipart/form-data" class="ocl-ui-form-horizontal">
                   <table class="ocl-ui-form">
                    <tbody>
                        <tr>
                            <td><?php echo $entry_file_encoding; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_export_file_encoding; ?>" /></td>
                            <td><select name="csv_import[file_encoding]" class="span140"><option value="UTF-8" <?php if ( $csv_import['file_encoding'] == 'UTF-8' ) echo 'selected'; ?>>UTF-8</option><option value="WINDOWS-1251" <?php if ( $csv_import['file_encoding'] == 'WINDOWS-1251' ) echo 'selected'; ?>>Windows-1251</option></select></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_languages; ?></td>
                            <td><select class="span140" name="csv_import[language_id]">
                                <?php foreach ($languages as $language) { ?>
                                    <?php if ( $csv_import['language_id'] == $language['language_id'] ) { ?>
                                    <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                              </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_import_mode; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_mode; ?>" /></td>
                            <td><select class="span180" name="csv_import[mode]">
                                <option value="2" <?php if ( $csv_import['mode'] == 2 ) echo 'selected'; ?>><?php echo $text_import_mode_update; ?></option>
                                <option value="3" <?php if ( $csv_import['mode'] == 3 ) echo 'selected'; ?>><?php echo $text_import_mode_insert; ?></option>
                                <option value="1" <?php if ( $csv_import['mode'] == 1 ) echo 'selected'; ?>><?php echo $text_import_mode_both; ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_key_field; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_key_field; ?>" /></td>
                            <td><select class="span140" name="csv_import[key_field]">
                                <?php foreach($csv_import_key_fields as $key => $name) { ?>
                                    <option value="<?php echo $key; ?>"<?php if ($csv_import['key_field'] == $key)echo ' selected="selected"';?>><?php echo $name;?></option>
                                <?php } ?>
                                  </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_category_delimiter; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_export_delimiter_category; ?>" /></td>
                            <td>
                                <select class="span80" name="csv_import[delimiter_category]">
                                    <option value="|"<?php if(isset($csv_import['delimiter_category']) && $csv_import['delimiter_category'] == '|'){echo ' selected="selected"';}?>> | </option>
                                    <option value="/"<?php if(isset($csv_import['delimiter_category']) && $csv_import['delimiter_category'] == '/'){echo ' selected="selected"';}?>> / </option>
                                    <option value=","<?php if(isset($csv_import['delimiter_category']) && $csv_import['delimiter_category'] == ','){echo ' selected="selected"';}?>> , </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_store; ?></td>
                            <td>
                                <div class="ocl-ui-scrollbox span300" style="height: 60px;">
                                  <?php $class = 'even'; ?>
                                  <label><div class="<?php echo $class; ?>"><input type="checkbox" name="csv_import[to_store][]" value="0" checked="checked" /> <?php echo $text_default; ?></div></label>
                                  <?php foreach ($stores as $store) { ?>
                                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                  <label><div class="<?php echo $class; ?>"> <input type="checkbox" name="csv_import[to_store][]" value="<?php echo $store['store_id']; ?>" <?if(in_array($store['store_id'], $csv_import['to_store'])){echo ' checked="checked"';}?> /> <?php echo $store['name']; ?></div></label>
                                  <?php } ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_sort_order; ?></td>
                            <td><input class="span60" type="text" name="csv_import[sort_order]" value="<?php if(isset($csv_import['sort_order']))echo $csv_import['sort_order'];?>" /></td>
                        </tr>
                        <tr><td><?php echo $entry_status; ?></td><td>
                                <select class="span140" name="csv_import[status]">
                                  <?php if ($csv_import['status']) { ?>
                                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                  <option value="0"><?php echo $text_disabled; ?></option>
                                  <?php } else { ?>
                                  <option value="1"><?php echo $text_enabled; ?></option>
                                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                  <?php } ?>
                                </select>      
                            </td></tr>
                        <tr>
                            <td><?php echo $entry_import_category_disable; ?></td>
                            <td>
                                <select class="span80" name="csv_import[category_disable]">
                                <?php if (isset($csv_import['catgeory_disable']) && $csv_import['category_disable'] == 1) { ?>
                                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                    <option value="0"><?php echo $text_no; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_yes; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php echo $entry_import_file; ?>
                                <div style="padding: 6px 0px;"><input style="width: 60%;" type="file" name="import" /></div>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <div class="ocl-ui-buttons left"><a onclick="$('#form_catgeory_import').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_import;?></a></div>
              </form>
          </div>
          <!-- ////////////////////////  END Category Import Tab Content ////////////////////////  -->

          <!-- ////////////////////////  BEGIN Category Macros Tab Content ////////////////////////  -->
          <div id="tab_macros" class="ocl-ui-tab-content">
                <form action="<?php echo $main_url['Categories'];?>" method="post" id="form_macros" enctype="multipart/form-data" class="ocl-ui-form-horizontal">
                <input type="hidden" name="form_macros_status" value="1" />
                <table id="table-custom-fields" class="ocl-ui-table-list">
                    <thead>
                        <tr>
                            <td><?php echo $entry_table; ?></td>
                            <td><?php echo $entry_field_name; ?></td>
                            <td><?php echo $entry_csv_name; ?></td>
                            <td><?php echo $entry_caption; ?></td>
                            <td>&nbsp;</td>
                        </tr>
                    </thead>
                    <?php $field_row = 0; ?>
                    <?php if(!empty($category_macros)) { ?>
                    <?php foreach ($category_macros as $fields) { ?>
                    <tbody id="custom-fields-row<?php echo $field_row; ?>">
                        <tr>
                            <td class="left"><input type="hidden" name="category_macros[<?php echo $field_row; ?>][tbl_name]" value="<?php echo $fields['tbl_name']; ?>" size="1" /><?php echo $fields['tbl_name']; ?></td>
                            <td class="left"><input type="hidden" name="category_macros[<?php echo $field_row; ?>][field_name]" value="<?php echo $fields['field_name']; ?>" size="1" /><?php echo $fields['field_name']; ?></td>
                            <td class="left"><input type="hidden" name="category_macros[<?php echo $field_row; ?>][csv_name]" value="<?php echo $fields['csv_name']; ?>" size="1" /><?php echo $fields['csv_name']; ?></td>
                            <td class="left"><input type="hidden" name="category_macros[<?php echo $field_row; ?>][csv_caption]" value="<?php echo $fields['csv_caption']; ?>" size="1" /><?php echo $fields['csv_caption']; ?></td>
                            <td class="center"><a onclick="$('#custom-fields-row<?php echo $field_row; ?>').remove();" class="ocl-ui-button ocl-ui-button-small"><?php echo $button_remove; ?></a></td>
                        </tr>
                    </tbody>
                    <?php $field_row++; ?>
                    <?php } ?>
                    <tfoot></tfoot>
                    <?php } else { ?>
                        <tfoot><tr><td colspan="5"><?php echo $text_no_results; ?></td></tr></tfoot>
                    <?php } ?>
                    
                </table>
                </form>
                
                <div class="ocl-ui-wrap span500">
                <form>
                    <table class="ocl-ui-form">
                        <tbody>
                            <tr>
                                <td><?php echo $entry_table; ?></td>
                                <td>
                                    <select id="tbl_name" class="span200" onchange="getCustomFields();">
                                        <option value="<?php echo DB_PREFIX;?>category"><?php echo DB_PREFIX;?>category</option>
                                        <option value="<?php echo DB_PREFIX;?>category_description"><?php echo DB_PREFIX;?>category_description</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_field_name; ?></td>
                                <td><select id="custom_fields"  class="span200"  onchange="selectFieldName();"></select></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_csv_name; ?></td>
                                <td><input class="span240" type="text" id="csv_name" value="" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_caption; ?></td>
                                <td><input class="span240" type="text" id="csv_caption" value="" /></td>
                            </tr>
                            <tr><td>&nbsp;</td><td><div class="buttons"><a onclick="add_CF();" class="ocl-ui-button"><?php echo $button_insert; ?></a></div></td></tr>
                        </tbody>
                    </table>
                </form>
                </div>
                <div class="ocl-ui-buttons left"><a  onclick="$('#form_macros').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_save; ?></a></div>
          </div>
          <!-- END Categories Macros Tab Content -->

      </div> <!-- class="vtabs-content" -->
    </div> <!-- END class="container" -->
    <div id="ocl_copyright"><?php echo $ocl_copyright;?></div>
</div> <!-- END id="content" -->

<script type="text/javascript"><!--
function setBackgroundColor(obj) {
    var row = '#row_' + $(obj).attr('id') + ' td';
    if($(obj).attr('checked') == 'checked'){
        $(row).addClass('selected');
    } else {
        $(row).removeClass('selected');
    }
}
function initFieldsSet() {
    $('.field_id').attr('checked', 'checked');
    $('.ocl-ui-field-set input[type=checkbox]').each(function() {
        setBackgroundColor(this);
    });
}

$(document).ready(function(){
	initFieldsSet();
});

$('.ocl-ui-field-set input[type=checkbox]').change(function(){
    setBackgroundColor(this);
});

function resetTextFields(){
    $('#csv_name').val('');
    $('#csv_caption').val('');
}
function selectFieldName(){
    $('#csv_name').val('');
    $('#csv_caption').val('');
    
    var field_name = $('#custom_fields option:selected').val();
    if( field_name != -1 ) {
        field_name = '_CUSTOM_' + field_name.toString().toUpperCase() + '_';
        $('#csv_name').val(field_name);
        $('#csv_caption').focus();
    }
}
function getCustomFields() {
    var url = '<?php echo $action_get_custom; ?>';
    url = url.replace( /\&amp;/g, '&' );

    $.ajax({
        type:'POST',
        url: url,
        dataType:'json',
        data:{tbl_name: $('#tbl_name option:selected').val()},
        success:function(json){
            $('#custom_fields').get(0).options.length = 0;
            $('#custom_fields').get(0).options[0] = new Option(" <?php echo $text_none; ?> ", "-1");
            $.each(json, function(i,item) {
                $("#custom_fields").get(0).options[$("#custom_fields").get(0).options.length] = new Option(item.name, item.name);
            });
        },
        error:function(){alert("Failed to load Fields!");}
    });
    resetTextFields();
    return false;
}
var field_row = <?php echo $field_row; ?>;

function del_CF(obj) {
    $('#custom-fields-row' + obj).remove();
}

function add_CF() {
        var tbl_name = $('#tbl_name option:selected').val();
        var field_name = $('#custom_fields option:selected').val();
        var csv_name = $('#csv_name').val();
        var csv_caption = $('#csv_caption').val();
        
        if( field_name == -1 || csv_name == '' || csv_caption == '' ) {
                //alert("Error! Field is not");
                return;
        }
        var html  = '<tbody id="custom-fields-row' + field_row + '">';
        html += '  <tr>';
        html += '    <td class="left"><input type="hidden" name="category_macros[' + field_row + '][tbl_name]" value="' + tbl_name + '" size="1" />' + tbl_name + '</td>';
        html += '    <td class="left"><input type="hidden" name="category_macros[' + field_row + '][field_name]" value="' + field_name + '" size="1" />' + field_name + '</td>';
        html += '    <td class="left"><input type="hidden" name="category_macros[' + field_row + '][csv_name]" value="' + csv_name + '" size="1" />' + csv_name + '</td>';
        html += '    <td class="left"><input type="hidden" name="category_macros[' + field_row + '][csv_caption]" value="' + csv_caption + '" size="1" />' + csv_caption + '</td>';
        html += '    <td class="center"><a onclick="del_CF(' + field_row+ ');" class="ocl-ui-button ocl-ui-button-small"><?php echo $button_remove; ?></a></td>';
        html += '  </tr>';
        html += '</tbody>';
        $('#table-custom-fields tfoot').before(html);
        if (field_row < 1) {
            $('#table-custom-fields tfoot').html('');
        }
        resetTextFields();
        field_row++;
}
$(document).ready(getCustomFields);
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
    // Tabs functions
    <?php if(isset($tab_selected)) { ?>
    $('#submenu a').tabs();
    $('#submenu a').removeClass('selected');
    $('.ocl-ui-tab-content').hide();
    $("#link_<?php echo $tab_selected; ?>").addClass('selected');
    $("#<?php echo $tab_selected; ?>").show();
    <?php } ?>
    
    // Tooltype functions
    function simple_tooltip(target_items, name){
        $(target_items).each(function(i){
            $("body").append("<div class='"+name+"' id='"+name+i+"'><p>"+$(this).attr('title')+"</p></div>");
            var my_tooltip = $("#"+name+i);

            $(this).removeAttr("title").mouseover(function(){
                my_tooltip.css({opacity:1, display:"none"}).fadeIn(200);
            }).mousemove(function(kmouse){
                my_tooltip.css({left:kmouse.pageX+15, top:kmouse.pageY+15});
            }).mouseout(function(){my_tooltip.fadeOut(200);});
        });
    }

    simple_tooltip(".helper","ocl-ui-tooltip");
});
//--></script>
<?php echo $footer; ?>