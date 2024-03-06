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
      
      <!-- BEGIN Products Driver -->
      <div id="submenu"class="ocl-ui-submenu">
        <ul>
           <li><a href="#tab_setting" id="link_tab_setting"><?php echo $tab_setting; ?></a></li>
           <li><a href="#tab_export" id="link_tab_export"><?php echo $tab_export; ?></a></li>
           <li><a href="#tab_import" id="link_tab_import"><?php echo $tab_import; ?></a></li>
           <li><a href="#tab_macros" id="link_tab_macros"><?php echo $tab_macros; ?></a></li>
           <!-- <li><a href="#tab_help" id="link_tab_help"><?php echo $tab_help; ?></a></li> -->
        </ul>
     </div>
      <div class="ocl-ui-body-content">
          <!-- BEGIN Product Export Tab Content -->
          <div id="tab_export" class="ocl-ui-tab-content">
              <form action="<?php echo $action_export; ?>" method="post" id="form_product_export" enctype="multipart/form-data" class="ocl-ui-form-horizontal">
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
                                    <input type="hidden" name="csv_export[product_category]" value="0" />
                                    <?php echo $entry_category; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_export_category; ?>" />
                                    <div class="ocl-ui-scrollbox">
                                        <?php $class = 'odd'; ?>
                                        <?php foreach ($categories as $category) { ?>
                                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                        <label><div class="<?php echo $class; ?>">
                                        <input type="checkbox" name="csv_export[product_category][]" value="<?php echo $category['category_id']; ?>" />
                                        <?php echo $category['name']; ?>
                                        </div></label>
                                        <?php } ?>
                                    </div>
                                    <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_export_category; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_export_category_by; ?>" /></td>
                                <td>
                                    <select class="span200" name="csv_export[export_category]">
                                            <option value="0"<?php if (!isset($csv_export['export_category']) || $csv_export['export_category'] == 0) { echo '  selected="selected"'; }?>><?php echo $text_disabled; ?></option>
                                            <option value="1"<?php if ( isset($csv_export['export_category']) && $csv_export['export_category'] == 1 ) { echo '  selected="selected"'; }?>><?php echo $text_export_category_id; ?></option>
                                            <option value="2"<?php if ( isset($csv_export['export_category']) && $csv_export['export_category'] == 2 ) { echo '  selected="selected"'; }?>><?php echo $text_export_category; ?></option>
                                    </select>
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
                                <td colspan="2">
                                    <input type="hidden" name="csv_export[product_manufacturer]" value="0" />
                                    <?php echo $entry_manufacturer; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_export_manufacturer; ?>" />
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
                            <tr>
                                <td><?php echo $entry_expot_limit; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_export_limit; ?>" /></td>
                                <td><input title="<?php echo $help_export_limit; ?>" type="text" class="span60" name="csv_export[limit_start]" value="<?php echo $csv_export['limit_start']; ?>">&nbsp;-&nbsp;<input title="<?php echo $help_export_limit; ?>" type="text" class="span60" name="csv_export[limit_end]" value="<?php echo $csv_export['limit_end']; ?>"></td>
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
                                    <label title="<?php echo $fields_set_help[$field['uid']]; ?> <?php echo $field['uid']; ?>">
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
                  
                  <!-- BEGIN FIlter -->
                  <input type="hidden" name="csv_export[product_filter]" value="0" />
                  <label><input id="product_filter" class="wrap_content" data-wrap_id="wrap_product_filter" type="checkbox" name="csv_export[product_filter]" value="1" /><?php echo $text_product_filter;?></label>
                  <div id="wrap_product_filter" class="ocl-ui-wrap" style="display: none">
                  	<table class="ocl-ui-form">
                        <tbody>
                        	<tr>
                                <td><?php echo $entry_filter_name; ?></td>
                                <td><input type="text" class="span240" name="csv_export[filter_name]" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_filter_sku; ?></td>
                                <td><input type="text" class="span240" name="csv_export[filter_sku]" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_filter_location; ?></td>
                                <td><input type="text" class="span240" name="csv_export[filter_location]" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_filter_price; ?></td>
                                <td><select class="span60" name="csv_export[filter_price_prefix]">
										<option value="1"> = </option>
										<option value="2"> >= </option>
										<option value="3"> <= </option>
										<option value="4"> <> </option>
									</select>
									<input type="text" class="span100 text-right" name="csv_export[filter_price]" value="" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_filter_price_range; ?></td>
                                <td>
                                	<input type="text" class="span100 text-right" name="csv_export[filter_price_start]" value="" />
                                	-
                                	<input type="text" class="span100 text-right" name="csv_export[filter_price_end]" value="" />
                                </td>
                            </tr>
                  			<tr>
                                <td><?php echo $entry_filter_quantity; ?></td>
                                <td><select class="span60" name="csv_export[filter_quantity_prefix]">
										<option value="1"> = </option>
										<option value="2"> >= </option>
										<option value="3"> <= </option>
										<option value="4"> <> </option>
									</select>
                                	<input type="text" class="span100 text-right" name="csv_export[filter_quantity]" value="" />
                                </td>
                            </tr>
                            <tr><td><?php echo $entry_stock_status; ?></td>
                            	<td>
	                                <select class="span180" name="csv_export[filter_stock_status]">
										<option value="0" selected="selected"></option>
	                                    <?php foreach ($stock_statuses as $stock_status) { ?>
	                                    <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
	                                    <?php } ?>
	                                </select>
	                            </td></tr>
                            <tr>
                                <td><?php echo $entry_filter_status; ?></td>
                                <td><select class="span140" name="csv_export[filter_status]">
                                  <option value="3" selected="selected"><?php echo $text_all; ?></option>
                                  <option value="1"><?php echo $text_enabled; ?></option>
                                  <option value="0"><?php echo $text_disabled; ?></option>
                                </select>  
                                </td>
                            </tr>
                       </tbody>
					</table>
                  </div>
                  <!-- END Filter -->
                  
                  <div class="ocl-ui-buttons left" style="margin-top:20px;"><a onclick="$('#form_product_export').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_export;?></a></div>
              </form>
          </div>
          <!-- ////////////////////////  END Product Export Tab Content ////////////////////////  -->
          <!-- ////////////////////////  BEGIN Product Import Tab Content ////////////////////////  -->
          <div id="tab_import" class="ocl-ui-tab-content">
				<div class="ocl-ui-toolbar"><span class="ui-icon ui-icon-gear"></span><a class="wrap_content" data-wrap_id="wrap_profile_import"><?php echo $text_profiles;?></a> <img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_profile; ?>" /></div>
				<div id="wrap_profile_import" class="ocl-ui-wrap" style="display: none">
					<form id="form_profile_import">
					<table class="ocl-ui-form">
					<tr><td><?php echo $text_import_load_profile;?></td>
						<td>
							<input type="hidden" id="profile_action" name="profile_action" value="delete" />
							<select class="span200" name="profile_product_import" id="profile_product_import">
								<option value="0">Default</option>
							</select> <a onclick="oclLoadProfile('product_import');" class="ocl-ui-button ocl-ui-button-small"><?php echo $button_load;?></a> - <a onclick="oclDeleteProfile('product_import');" class="ocl-ui-button ocl-ui-button-small"><?php echo $button_delete;?></a>
						</td>
					</tr>
					<tr><td><?php echo $text_import_create_profile;?></td>
						<td>
							<input type="text" name="profile_name" id="profile_name" class="span200" value=""> <a onclick="oclCreateProfile('product_import');" class="ocl-ui-button ocl-ui-button-small"><?php echo $button_save;?></a>
						</td>
					</tr>
					</table>
					</form>
				</div>
              <form action="<?php echo $action_import; ?>" method="post" id="form_product_import" enctype="multipart/form-data" class="ocl-ui-form-horizontal">
                   <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%;vertical-align: top;">
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
                                            <option value="2" <?php if ( $csv_import['mode'] == 2 ) echo 'selected'; ?>> <?php echo $text_import_mode_update; ?></option>
                                            <option value="3" <?php if ( $csv_import['mode'] == 3 ) echo 'selected'; ?>> <?php echo $text_import_mode_insert; ?></option>
                                            <option value="1" <?php if ( $csv_import['mode'] == 1 ) echo 'selected'; ?>> <?php echo $text_import_mode_both; ?></option>
                                            <option value="10"><?php echo $text_import_mode_delete; ?></option>
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
                                        <td><?php echo $entry_import_delimiter_category; ?></td>
                                        <td>
                                            <select class="span80" name="csv_import[delimiter_category]">
												<option value="|"<?php if(isset($csv_import['delimiter_category']) && $csv_import['delimiter_category'] == '|'){echo ' selected="selected"';}?>> | </option>
			                                    <option value="/"<?php if(isset($csv_import['delimiter_category']) && $csv_import['delimiter_category'] == '/'){echo ' selected="selected"';}?>> / </option>
			                                    <option value=","<?php if(isset($csv_import['delimiter_category']) && $csv_import['delimiter_category'] == ','){echo ' selected="selected"';}?>> , </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_import_fill_category; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_fill_category; ?>" /></td>
                                        <td>
                                            <select class="span80" name="csv_import[fill_category]">
                                                <?php if (isset($csv_import['fill_category']) && $csv_import['fill_category'] == 1) { ?>
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
                                        <td><?php echo $entry_import_category_top; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_category_top; ?>" /></td>
                                        <td>
                                            <select class="span80" name="csv_import[top]">
                                                <?php if (isset($csv_import['top']) && $csv_import['top'] == 1) { ?>
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
                                        <td><?php echo $entry_import_category_column; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_category_column; ?>" /></td>
                                        <td><input class="span60" type="text" name="csv_import[column]" value="<?php echo $csv_import['column'];?>" /></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_import_calc_mode; ?></td>
                                        <td>
                                            <select class="span180" name="csv_import[calc_mode]">
                                                <option value="0" <?php if ( $csv_import['calc_mode'] == 0 ) echo 'selected'; ?>><?php echo $text_import_calc_mode_off; ?></option>
                                                <option value="1" <?php if ( $csv_import['calc_mode'] == 1 ) echo 'selected'; ?>><?php echo $text_import_calc_mode_multiply; ?></option>
                                                <option value="2" <?php if ( $csv_import['calc_mode'] == 3 ) echo 'selected'; ?>><?php echo $text_import_calc_mode_divide; ?></option>
                                                <option value="3" <?php if ( $csv_import['calc_mode'] == 2 ) echo 'selected'; ?>><?php echo $text_import_calc_mode_pluse; ?></option>
                                                <option value="4" <?php if ( $csv_import['calc_mode'] == 4 ) echo 'selected'; ?>><?php echo $text_import_calc_mode_minus; ?></option>
                                          </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_import_calc_value; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_calc_value; ?>" /></td>
                                        <td>
                                            <input class="span60" type="text" name="csv_import[calc_value][]" value="<?php if(isset($csv_import['calc_value'][0]))echo $csv_import['calc_value'][0];?>" size="2"/>
                                            <input class="span60" type="text" name="csv_import[calc_value][]" value="<?php if(isset($csv_import['calc_value'][1]))echo $csv_import['calc_value'][1];?>" size="2"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $entry_import_empty_field; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_empty_field; ?>" /></td>
                                        <td>
                                            <select class="span80" name="csv_import[empty_field]">
                                            <?php if (isset($csv_import['empty_field']) && $csv_import['empty_field'] == 1) { ?>
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
                                        <td><?php echo $entry_import_product_disable; ?></td>
                                        <td>
                                            <select class="span80" name="csv_import[product_disable]">
                                            <?php if (isset($csv_import['product_disable']) && $csv_import['product_disable'] == 1) { ?>
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
                                        <td><?php echo $entry_import_quantity_reset; ?></td>
                                        <td>
                                            <select class="span80" name="csv_import[quantity_reset]">
                                            <?php if (isset($csv_import['quantity_reset']) && $csv_import['quantity_reset'] == 1) { ?>
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
                                        <td><?php echo $entry_import_iter_limit; ?><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_iter_limit; ?>" /></td>
                                        <td><input class="span60" type="text" name="csv_import[iter_limit]" value="<?php echo $csv_import['iter_limit'];?>" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <?php echo $entry_import_file; ?>
                                            <div style="padding: 6px 0px;"><input style="width: 100%;" type="file" name="import" /></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="width: 50%;vertical-align: top; padding-left: 10px;">
                            <table class="ocl-ui-form ocl-ui-form-vertical">
                                <tbody>
                                    <tr>
                                        <td><div><?php echo $entry_import_manufacturer; ?><input type="hidden" value="0" name="csv_import[skip_manufacturer]" /></div>
                                            <div><label><input class="im_checkbox_skip" type="checkbox" value="1" name="csv_import[skip_manufacturer]" checked="checked" /> <?php echo $text_import_skip; ?></label><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_skip_data; ?>" /></div>
                                            <div>
                                                <select class="span300" name="csv_import[product_manufacturer]">
                                                <option value="0" selected="selected"><?php echo $text_none; ?></option>
                                                <?php foreach ($manufacturers as $manufacturer) { ?>
                                                <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                                                <?php } ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php if($core_type['MAIN_CATEGORY']) {?>
                                    <tr>
                                        <td><div><?php echo $entry_import_main_category; ?><input type="hidden" value="0" name="csv_import[skip_main_category]"></div>
                                            <div><label><input class="im_checkbox_skip" type="checkbox" value="1" name="csv_import[skip_main_category]" checked="checked" /> <?php echo $text_import_skip; ?></label><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_skip_data; ?>" /></div>
                                            <div><select class="span300" name="csv_import[main_category_id]">
                                                <option value="0" selected="selected"><?php echo $text_none; ?></option>
                                                <?php foreach ($categories as $category) { ?>
                                                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td><div><?php echo $entry_import_category; ?><input type="hidden" value="0" name="csv_import[skip_category]"></div>
                                            <div><label><input class="im_checkbox_skip" type="checkbox" value="1" name="csv_import[skip_category]" checked="checked" /> <?php echo $text_import_skip; ?></label><img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_skip_data; ?>" /></div>
                                            <input type="hidden" value="0" name="csv_import[product_category]" />
                                            <div class="ocl-ui-scrollbox" style="height: 260px;">
                                                <?php $class = 'odd'; ?>
                                                <?php foreach ($categories as $category) { ?>
                                                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                                <label><div class="<?php echo $class; ?>">
                                                <input class="in_checkbox_01" type="checkbox" name="csv_import[product_category][]" value="<?php echo $category['category_id']; ?>" /> <?php echo $category['name']; ?>
                                                </div></label>
                                                <?php } ?>
                                            </div>
                                            <a onclick="$('.in_checkbox_01').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$('.in_checkbox_01').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="ocl-ui-wrap">
	                            <input type="hidden" value="0" name="csv_import[exclude_filter]" />
	                            <label><input type="checkbox" name="csv_import[exclude_filter]" value="1" <?php if(isset($csv_import['exclude_filter']) && $csv_import['exclude_filter'] == 1 ) echo ' checked'; ?> /><?php echo $entry_import_exclude_filter; ?> <img src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" title="<?php echo $help_import_exclude_filter; ?>" /></label>
	                            <table class="ocl-ui-form">
	                            	<tr>
	                            		<td><?php echo $entry_import_exclude_filter_name; ?></td><td><input class="span160" type="text" name="csv_import[exclude_filter_name]" value="<?php if(isset($csv_import['exclude_filter_name'])) echo $csv_import['exclude_filter_name']; ?>" /></td>
	                            	</tr>
	                            	<tr>
	                            		<td><?php echo $entry_import_exclude_filter_desc; ?></td><td><input class="span160" type="text" name="csv_import[exclude_filter_desc]" value="<?php if(isset($csv_import['exclude_filter_desc'])) echo $csv_import['exclude_filter_desc']; ?>" /></td>
	                            	</tr>
	                            </table>
	                        </div>
	                         <table class="ocl-ui-form">
	                         	<tr><td><?php echo $entry_import_image_path; ?></td><td><input class="span160" type="text" name="csv_import[img_path]" value="<?php if(isset($csv_import['img_path'])) { echo $csv_import['img_path']; } ?>" /></td></tr>
	                         </table>
	                        	
	                        </div>
                        </td>
                    </tr>
                    </table>
                    <div class="ocl-ui-buttons left"><a onclick="$('#form_product_import').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_import;?></a></div>
              </form>
          </div>
          <!-- ////////////////////////  END Product Import Tab Content ////////////////////////  -->

          <!-- ////////////////////////  BEGIN Product Setting Tab Content ////////////////////////  -->
          <div id="tab_setting" class="ocl-ui-tab-content">
              <div class="ocl-ui-wrap"><span><?php echo $caption_product_setting; ?></span></div>
            <form action="<?php echo $main_url['Products'];?>" method="post" id="form_product_setting" enctype="multipart/form-data" class="ocl-ui-form-horizontal">
                <input type="hidden" name="form_product_setting_status" value="1" />
                <table class="ocl-ui-form">
                        <tbody>
                            <tr>
                                <td><?php echo $entry_store; ?></td><td>
                                    <div class="ocl-ui-scrollbox span300" style="height: 60px;">
                                      <?php $class = 'even'; ?>
                                      <div class="<?php echo $class; ?>">
                                        <?php if (in_array(0, $csv_setting['to_store'])) { ?>
                                        <input type="checkbox" name="csv_setting[to_store][]" value="0" checked="checked" />
                                        <?php echo $text_default; ?>
                                        <?php } else { ?>
                                        <input type="checkbox" name="csv_setting[to_store][]" value="0" />
                                        <?php echo $text_default; ?>
                                        <?php } ?>
                                      </div>
                                      <?php foreach ($stores as $store) { ?>
                                      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                      <div class="<?php echo $class; ?>">
                                        <?php if (in_array($store['store_id'], $csv_setting['to_store'])) { ?>
                                        <input type="checkbox" name="csv_setting[to_store][]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                        <?php echo $store['name']; ?>
                                        <?php } else { ?>
                                        <input type="checkbox" name="csv_setting[to_store][]" value="<?php echo $store['store_id']; ?>" />
                                        <?php echo $store['name']; ?>
                                        <?php } ?>
                                      </div>
                                      <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <tr><td><?php echo $entry_tax_class; ?></td><td>
                                <select name="csv_setting[tax_class_id]" class="span180">
                                  <option value="0"><?php echo $text_none; ?></option>
                                  <?php foreach ($tax_classes as $tax_class) { ?>
                                  <?php if ($tax_class['tax_class_id'] == $csv_setting['tax_class_id']) { ?>
                                  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                                  <?php } else { ?>
                                  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                                  <?php } ?>
                                  <?php } ?>
                                </select>
                            </td></tr>
                            <tr><td><?php echo $entry_minimum; ?><img title="<?php echo $help_setting_minimum;?>" src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" /></td><td><input class="span60" type="text" name="csv_setting[minimum]" value="<?php echo $csv_setting['minimum']; ?>" /></td></tr>
                            <tr><td><?php echo $entry_subtract; ?></td><td>
                                <select class="span80" name="csv_setting[subtract]">
                                      <?php if ($csv_setting['subtract']) { ?>
                                      <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                      <option value="0"><?php echo $text_no; ?></option>
                                      <?php } else { ?>
                                      <option value="1"><?php echo $text_yes; ?></option>
                                      <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                      <?php } ?>
                                </select>
                            </td></tr>
                            <tr><td><?php echo $entry_stock_status; ?><img title="<?php echo $help_setting_stock_status;?>" src="view/image/csvprice_pro/csvprice_pro_question.png" width="12" height="12" class="ocl-ui-helper-ico helper" /></td><td>
                                <select class="span180" name="csv_setting[stock_status_id]">
                                    <?php foreach ($stock_statuses as $stock_status) { ?>
                                    <?php if ($stock_status['stock_status_id'] == $csv_setting['stock_status_id']) { ?>
                                    <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </td></tr>
                            <tr><td><?php echo $entry_shipping; ?></td><td>
                                <select class="span80" name="csv_setting[shipping]">
                                    <?php if ($csv_setting['shipping']) { ?>
                                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                    <option value="0"><?php echo $text_no; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_yes; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                    <?php } ?>
                                </select>
                            </td></tr>
                            <tr><td><?php echo $entry_length_class; ?></td><td>
                                <select class="span180" name="csv_setting[length_class_id]">
                                  <?php foreach ($length_classes as $length_class) { ?>
                                  <?php if ($length_class['length_class_id'] == $csv_setting['length_class_id']) { ?>
                                  <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                                  <?php } else { ?>
                                  <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                                  <?php } ?>
                                  <?php } ?>
                                </select>
                            </td></tr>
                            <tr><td><?php echo $entry_weight_class; ?></td><td>
                                <select class="span180" name="csv_setting[weight_class_id]">
                                  <?php foreach ($weight_classes as $weight_class) { ?>
                                  <?php if ($weight_class['weight_class_id'] == $csv_setting['weight_class_id']) { ?>
                                  <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                                  <?php } else { ?>
                                  <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                                  <?php } ?>
                                  <?php } ?>
                                </select></td></tr>
                            <tr><td><?php echo $entry_sort_order; ?></td><td><input class="span60" type="text" name="csv_setting[sort_order]" value="<?php echo $csv_setting['sort_order']; ?>" /></td></tr>
                            <tr><td><?php echo $entry_status; ?></td><td>
                                <select class="span140" name="csv_setting[status]">
                                  <?php if ($csv_setting['status']) { ?>
                                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                  <option value="0"><?php echo $text_disabled; ?></option>
                                  <?php } else { ?>
                                  <option value="1"><?php echo $text_enabled; ?></option>
                                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                  <?php } ?>
                                </select>      
                            </td></tr>
                        </tbody>
                </table>
                
                <!-- BEGIN Product Options Setting Content -->
                
                <div class="ocl-ui-wrap"><span><?php echo $text_default_options_setting;?></span></div>
                <table class="ocl-ui-form">
                	<tr><td><?php echo $entry_option_type; ?></td><td>
                        <select class="span140" name="csv_setting[option_type]">
						  <optgroup label="Choose">
						    <option value="select"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'select') {echo ' selected="selected"';} ?>>Select</option>
						    <option value="radio"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'radio') {echo ' selected="selected"';} ?>>Radio</option>
						    <option value="checkbox"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'checkbox') {echo ' selected="selected"';} ?>>Checkbox</option>
						    <option value="image"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'image') {echo ' selected="selected"';} ?>>Image</option>
						  </optgroup>
						  <optgroup label="Input">
						    <option value="text"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'text') {echo ' selected="selected"';} ?>>Text</option>
						    <option value="textarea"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'textarea') {echo ' selected="selected"';} ?>>Textarea</option>
						  </optgroup>
						  <optgroup label="File">
						    <option value="file"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'file') {echo ' selected="selected"';} ?>>File</option>
						  </optgroup>
						  <optgroup label="Date">
						    <option value="date"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'date') {echo ' selected="selected"';} ?>>Date</option>
						    <option value="time"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'time') {echo ' selected="selected"';} ?>>Time</option>
						    <option value="datetime"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'datetime') {echo ' selected="selected"';} ?>>Date &amp; Time</option>
						  </optgroup>
						</select>
                    	</td>
                    </tr>
                    <tr>
	                    <td><?php echo $entry_option_required; ?></td>
	                    <td>
	                        <select class="span80" name="csv_setting[option_required]">
	                            <?php if (isset($csv_setting['option_required']) && $csv_setting['option_required'] == 1) { ?>
	                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
	                                <option value="0"><?php echo $text_no; ?></option>
	                                <?php } else { ?>
	                                <option value="1"><?php echo $text_yes; ?></option>
	                                <option value="0" selected="selected"><?php echo $text_no; ?></option>
	                            <?php } ?>
	                        </select>
	                    </td>
	                </tr>
                    <tr><td><?php echo $entry_option_quantity; ?></td><td><input class="span60" type="text" name="csv_setting[option_quantity]" value="<?php if(isset($csv_setting['option_quantity']))echo $csv_setting['option_quantity']; ?>" /></td></tr>
                     <tr>
	                    <td><?php echo $entry_option_subtract_stock; ?></td>
	                    <td>
	                        <select class="span80" name="csv_setting[option_subtract_stock]">
	                            <?php if (isset($csv_setting['option_subtract_stock']) && $csv_setting['option_subtract_stock'] == 1) { ?>
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
	                    <td><?php echo $entry_option_price_prefix; ?></td>
	                    <td>
	                        <select class="span80" name="csv_setting[option_price_prefix]">
                                <option value="+"<?php if (isset($csv_setting['option_price_prefix']) && $csv_setting['option_price_prefix'] == '+') {echo ' selected="selected"';} ?>>+</option>
                                <option value="-"<?php if (isset($csv_setting['option_price_prefix']) && $csv_setting['option_price_prefix'] == '-') {echo ' selected="selected"';} ?>>-</option>
	                        </select>
	                    </td>
	                </tr>
	                <tr>
	                    <td><?php echo $entry_option_points_prefix; ?></td>
	                    <td>
	                        <select class="span80" name="csv_setting[option_points_prefix]">
                                <option value="+"<?php if (isset($csv_setting['option_points_prefix']) && $csv_setting['option_points_prefix'] == '+') {echo ' selected="selected"';} ?>>+</option>
                                <option value="-"<?php if (isset($csv_setting['option_points_prefix']) && $csv_setting['option_points_prefix'] == '-') {echo ' selected="selected"';} ?>>-</option>
	                        </select>
	                    </td>
	                </tr>
	                <tr><td><?php echo $entry_option_points_default; ?></td><td><input class="span60" type="text" name="csv_setting[option_points_default]" value="<?php if(isset($csv_setting['option_points_default']))echo $csv_setting['option_points_default']; ?>" /></td></tr>
	                <tr>
	                    <td><?php echo $entry_option_weight_prefix; ?></td>
	                    <td>
	                        <select class="span80" name="csv_setting[option_weight_prefix]">
                                <option value="+"<?php if (isset($csv_setting['option_weight_prefix']) && $csv_setting['option_weight_prefix'] == '+') {echo ' selected="selected"';} ?>>+</option>
                                <option value="-"<?php if (isset($csv_setting['option_weight_prefix']) && $csv_setting['option_weight_prefix'] == '-') {echo ' selected="selected"';} ?>>-</option>
	                        </select>
	                    </td>
	                </tr>
	                <tr><td><?php echo $entry_option_weight_default; ?></td><td><input class="span60" type="text" name="csv_setting[option_weight_default]" value="<?php if(isset($csv_setting['option_weight_default']))echo $csv_setting['option_weight_default']; ?>" /></td></tr>
                </table>

                <!-- END Product Options Setting Content -->
                
            </form>
            <div class="ocl-ui-buttons left"><a onclick="$('#form_product_setting').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_save;?></a></div>
          </div>
          <!-- //////////////////////// END Product Setting Content ////////////////////////  -->

          <!-- ////////////////////////  BEGIN Product Macros Tab Content ////////////////////////  -->
          <div id="tab_macros" class="ocl-ui-tab-content">
                <form action="<?php echo $main_url['Products'];?>" method="post" id="form_macros" enctype="multipart/form-data" class="ocl-ui-form-horizontal">
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
                    <?php if(!empty($product_macros)) { ?>
                    <?php foreach ($product_macros as $fields) { ?>
                    <tbody id="custom-fields-row<?php echo $field_row; ?>">
                        <tr>
                            <td class="left"><input type="hidden" name="product_macros[<?php echo $field_row; ?>][tbl_name]" value="<?php echo $fields['tbl_name']; ?>" size="1" /><?php echo $fields['tbl_name']; ?></td>
                            <td class="left"><input type="hidden" name="product_macros[<?php echo $field_row; ?>][field_name]" value="<?php echo $fields['field_name']; ?>" size="1" /><?php echo $fields['field_name']; ?></td>
                            <td class="left"><input type="hidden" name="product_macros[<?php echo $field_row; ?>][csv_name]" value="<?php echo $fields['csv_name']; ?>" size="1" /><?php echo $fields['csv_name']; ?></td>
                            <td class="left"><input type="hidden" name="product_macros[<?php echo $field_row; ?>][csv_caption]" value="<?php echo $fields['csv_caption']; ?>" size="1" /><?php echo $fields['csv_caption']; ?></td>
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
                <form class="">
                    <table class="ocl-ui-form">
                            <tr>
                                <td><?php echo $entry_table; ?></td>
                                <td>
                                    <select id="tbl_name" class="span200" onchange="getCustomFields();">
                                        <option value="<?php echo DB_PREFIX;?>product"><?php echo DB_PREFIX;?>product</option>
                                        <option value="<?php echo DB_PREFIX;?>product_description"><?php echo DB_PREFIX;?>product_description</option>
                                    </select>
                                </td>
                            </tr>
                            <tr><td><?php echo $entry_field_name; ?></td><td><select id="custom_fields"  class="span200"  onchange="selectFieldName();"></select></td></tr>
                            <tr><td><?php echo $entry_csv_name; ?></td><td><input class="span240" type="text" id="csv_name" value="" /></td></tr>
                            <tr><td><?php echo $entry_caption; ?></td><td><input class="span240" type="text" id="csv_caption" value="" /></td></tr>
                            <tr><td>&nbsp;</td><td><a onclick="add_CF();" class="ocl-ui-button"><?php echo $button_insert; ?></a></td></tr>
                    </table>
                </form>
                </div>
                <div class="ocl-ui-buttons left"><a  onclick="$('#form_macros').submit();" class="ocl-ui-button ocl-ui-button-orange"><?php echo $button_save; ?></a></div>
          </div>
          <!-- END Product Macros Tab Content -->
          
          <!-- Begin Help Tab Content -->
            <!--<div id="tab_help" class="ocl-ui-tab-content">
                <div id="accordion">
        			<?php echo $help_products_driver;?>
        		</div>
            </div> -->
          <!-- END Help Tab Content -->
      </div>

    </div>
     <div id="ocl_copyright"><?php echo $ocl_copyright;?></div>
</div>
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

$('.wrap_content').bind('click', function(){
	var w = $(this).data('wrap_id');
	if($('#' + w).is(':visible')) {
		$('#' + w).hide();
	} else {
		$('#' + w).show();
	}
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
        html += '    <td class="left"><input type="hidden" name="product_macros[' + field_row + '][tbl_name]" value="' + tbl_name + '" size="1" />' + tbl_name + '</td>';
        html += '    <td class="left"><input type="hidden" name="product_macros[' + field_row + '][field_name]" value="' + field_name + '" size="1" />' + field_name + '</td>';
        html += '    <td class="left"><input type="hidden" name="product_macros[' + field_row + '][csv_name]" value="' + csv_name + '" size="1" />' + csv_name + '</td>';
        html += '    <td class="left"><input type="hidden" name="product_macros[' + field_row + '][csv_caption]" value="' + csv_caption + '" size="1" />' + csv_caption + '</td>';
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
$(document).ready(function() {
	oclGetProfile('product_import');
	$.fn.serializeJSON = function(){
        var json = {};
        $.map($(this).serializeArray(),function(n, i) {
            json[n['name']] = n['value'];
        });
        return json;
    }
});

function oclGetProfile(key) {
	var url = '<?php echo $action_get_profile; ?>' + '&key=' + key;
    url = url.replace( /\&amp;/g, '&' );
	
	var s = '#profile_' + key; 
	
    $.ajax({
        type:'POST',
        url: url,
        dataType:'json',
        success:function(json){
            $(s).get(0).options.length = 0;
            $.each(json, function(i,item) {
                $(s).get(0).options[$(s).get(0).options.length] = new Option(item.name, item.id);
            });
        },
        error:function(){alert("Failed to load Fields!");}
    });
}

function oclDeleteProfile(key) {
	var s = '#profile_' + key;
	if($(s).get(0).options.length == 0) {
		return;
	}
	
	var url = '<?php echo $action_del_profile;?>';
    url = url.replace( /\&amp;/g, '&' );
    
	var id = $( '#profile_'+key+' option:selected').val();
	$.ajax({
		type: "POST",
		url: url,
		data: {profile_id: id},

		success: function(data){
			oclGetProfile(key);
		}
 	});
 	return false;
}
function oclLoadProfile(key) {
	var s = '#profile_' + key;
	if($(s).get(0).options.length == 0) {
		return;
	}
	var url = '<?php echo $main_url['Products'];?>&profile_id=' + $( '#profile_'+key+' option:selected').val();;
    url = url.replace( /\&amp;/g, '&' );
    window.location.href = url;
	
}
function oclCreateProfile(key) {
	
	if($('#profile_name').val() == '') return false;
	
	var url = '<?php echo $action_add_profile;?>';
    url = url.replace( /\&amp;/g, '&' );
    
    $('#profile_action').val('create');
	$.ajax({
		type: "POST",
		url: url,
		data: $.extend($('#form_product_import').serializeJSON(), $('#form_product_setting').serializeJSON(), $('#form_profile_import').serializeJSON()),
		//data: form_profile_import,
		success: function(data){
			oclGetProfile('product_import');
			alert('<?php echo $text_profile_created; ?> ');
			$('#profile_name').val(''); 
		}
 	});
 	return false;
}
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