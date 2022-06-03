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
           <!-- <li><a href="#tab_macros" id="link_tab_macros"><?php echo $tab_macros; ?></a></li> -->
        </ul>
     </div>

      <div class="ocl-ui-body-content">
          <!-- BEGIN Manufacturers Export Tab Content -->
          <div id="tab_export" class="ocl-ui-tab-content">
              <form action="<?php echo $action_export; ?>" method="post" id="form_manufacturer_export" enctype="multipart/form-data" class="form-horizontal">
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
                            <tr>
                                <td colspan="2">
                                    <input type="hidden" name="csv_export[product_manufacturer]" value="0" />
                                    <?php echo $entry_manufacturer; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_export_all_manufacturer; ?>" />
                                    <div class="ocl-ui-scrollbox">
                                        <?php $class = 'odd'; ?>
                                        <?php foreach ($manufacturers as $manufacturer) { ?>
                                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                        <label title="<?php echo $manufacturer['name']; ?>"><div class="<?php echo $class; ?>">
                                        <input type="checkbox" name="csv_export[product_manufacturer][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                                        <?php echo $manufacturer['name']; ?>
                                        </div></label>
                                        <?php } ?>
                                    </div>
                                    <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
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
                  <div class="ocl-ui-buttons left"><a onclick="$('#form_manufacturer_export').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_export;?></a></div>
              </form>
          </div>
          <!-- ////////////////////////  END Manufacturers Export Tab Content ////////////////////////  -->
          <!-- ////////////////////////  BEGIN Manufacturers Import Tab Content ////////////////////////  -->
          <div id="tab_import" class="ocl-ui-tab-content">
              <form action="<?php echo $action_import; ?>" method="post" id="form_manufacturer_import" enctype="multipart/form-data" class="form-horizontal">
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
                            <td><select class="span180" name="csv_import[key_field]">
                                <?php foreach($csv_import_key_fields as $key => $name) { ?>
                                    <option value="<?php echo $key; ?>"<?php if ($csv_import['key_field'] == $key)echo ' selected="selected"';?>><?php echo $name;?></option>
                                <?php } ?>
                                  </select>
                            </td>
                        </tr>
                        <tr>
                                        <td><?php echo $entry_import_id; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_id; ?>" /></td>
                                        <td>
                                            <select class="span80" name="csv_import[import_id]">
                                            <?php if (isset($csv_import['import_id']) && $csv_import['import_id'] == 1) { ?>
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
                        <tr>
                            <td colspan="2">
                                <?php echo $entry_import_file; ?>
                                <div style="padding: 6px 0px;"><input style="width: 60%;" type="file" name="import" /></div>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <div class="ocl-ui-buttons left"><a onclick="$('#form_manufacturer_import').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_import;?></a></div>
              </form>
          </div>
          <!-- ////////////////////////  END Manufacturers Import Tab Content ////////////////////////  -->

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