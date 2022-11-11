<?php echo $header; ?>
<style>
    .sortable { list-style-type: none; margin: 0; padding: 0; width:300px}
    .sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1em;  } <!--height: 18px;-->
    .sortable li.ui-state-disabled { height: 34px; }
    span.handle { position: absolute; margin-left: -1.3em; cursor: move; }
    span.param {float:right; width:50px; text-align:center;}
    .ui-state-deselected, .ui-widget-content .ui-state-deselected {border: 1px solid #d3d3d3; background: #e6e6e6 url(view/javascript/ui/themes/smoothness/images/ui-bg_glass_75_e6e6e6_1x400.png) 50% 50% repeat-x; color: #888888; }
    .ui-state-deselected a, .ui-widget-content .ui-state-deselected a { color: #363636; }
    .ui-state-deselected .ui-icon {background-image: url(view/javascript/ui/themes/smoothness/images/ui-icons_888888_256x240.png); }
    .column_name{margin-top:19px;display:inline-block;}
</style>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } else if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="heading order_head">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a href="#" id="apply_settings" class="button"><span><?php echo $button_apply; ?></span></a><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
   <div class="act-cont">
    <div id="overlay" class="save-overlay">
      <div class="tbl">
        <div class="tbl_cell"><img src="view/image/aqe-pro/aqe_loading.gif" style="margin:-2px;"/></div>
      </div>
    </div>
    <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a><a href="#tab-catalog"><?php echo $tab_catalog; ?></a><a href="#tab-sales"><?php echo $tab_sales; ?></a><a href="#tab-about"><?php echo $tab_about; ?></a><div class="clr"></div></div>
    <div class="th_style"></div>
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
     <input type="hidden" name="aqe_installed" value="1" />
     <div id="tab-general">
      <table class="form">
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="admin_quick_edit_status">
              <?php if ($admin_quick_edit_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_match_anywhere; ?></td>
          <td><input id="aqe_match_anywhere" class="checkbox" type="checkbox" name="aqe_match_anywhere" <?php echo ($aqe_match_anywhere) ? ' checked="checked"': ''; ?> />
			  <label for="aqe_match_anywhere"><?php echo $text_match_anywhere; ?></label>
			  </td>
        </tr>
        <tr>
          <td><?php echo $entry_alternate_row_colour; ?></td>
          <td><input id="aqe_alternate_row_colour" class="checkbox" type="checkbox" name="aqe_alternate_row_colour" <?php echo ($aqe_alternate_row_colour) ? ' checked="checked"': ''; ?> />
			  <label for="aqe_alternate_row_colour"><?php echo $text_alternate_row_colour; ?></label>
			  </td>
        </tr>
        <tr>
          <td><?php echo $entry_row_hover_highlighting; ?></td>
          <td><input id="aqe_row_hover_highlighting" class="checkbox" type="checkbox" name="aqe_row_hover_highlighting" <?php echo ($aqe_row_hover_highlighting) ? ' checked="checked"': ''; ?> />
			  <label for="aqe_row_hover_highlighting"><?php echo $text_row_hover_highlighting; ?></label>
			  </td>
        </tr>
        <tr>
          <td><?php echo $entry_highlight_status; ?></td>
          <td><input id="aqe_highlight_status" class="checkbox" type="checkbox" name="aqe_highlight_status" <?php echo ($aqe_highlight_status) ? ' checked="checked"': ''; ?> />
			  <label for="aqe_highlight_status"><?php echo $text_highlight_status; ?></label>
			  </td>
        </tr>
        <tr>
          <td><?php echo $entry_interval_filter; ?></td>
          <td><input id="aqe_interval_filter" class="checkbox" type="checkbox" name="aqe_interval_filter" <?php echo ($aqe_interval_filter) ? ' checked="checked"': ''; ?> />
			  <label for="aqe_interval_filter"><?php echo $text_interval_filter; ?></label>
			  </td>
        </tr>
        <tr>
          <td><?php echo $entry_list_view_image_size; ?></td>
          <td><input type="text" name="aqe_list_view_image_width" value="<?php echo $aqe_list_view_image_width; ?>" size="4" />&nbsp;<input type="text" name="aqe_list_view_image_height" value="<?php echo $aqe_list_view_image_height; ?>" size="4" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_quick_edit_on; ?></td>
          <td>
            <select name="aqe_quick_edit_on">
              <option value="click"<?php echo ($aqe_quick_edit_on == 'click' || $aqe_quick_edit_on != 'dblclick') ? 'selected="selected"': ''; ?>><?php echo $text_single_click; ?></option>
              <option value="dblclick"<?php echo ($aqe_quick_edit_on == 'dblclick') ? 'selected="selected"': ''; ?>><?php echo $text_double_click; ?></option>
            </select>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_single_language_editing; ?></td>
          <td><input id="aqe_single_language_editing" class="checkbox" type="checkbox" name="aqe_single_language_editing" <?php echo ($aqe_single_language_editing) ? ' checked="checked"': ''; ?> />
			   <label for="aqe_single_language_editing"></label>
			   </td>
        </tr>
      </table>
     </div>
     <div id="tab-catalog">
      <div id="catalog-tabs" class="htabs">
        <a href="#tab-catalog-categories"><?php echo $tab_categories; ?></a>
        <a href="#tab-catalog-products"><?php echo $tab_products; ?></a>
        <a href="#tab-catalog-filters"><?php echo $tab_filters; ?></a>
        <a href="#tab-catalog-profiles"><?php echo $tab_profiles; ?></a>
        <a href="#tab-catalog-attributes"><?php echo $tab_attributes; ?></a>
        <a href="#tab-catalog-attribute-groups"><?php echo $tab_attribute_groups; ?></a>
        <a href="#tab-catalog-options"><?php echo $tab_options; ?></a>
        <a href="#tab-catalog-manufacturers"><?php echo $tab_manufacturers; ?></a>
        <a href="#tab-catalog-downloads"><?php echo $tab_downloads; ?></a>
        <a href="#tab-catalog-reviews"><?php echo $tab_reviews; ?></a>
        <a href="#tab-catalog-information"><?php echo $tab_information; ?></a>
     <div class="clr"></div>
	 </div>
      <div class="th_style"></div>
	  <div id="tab-catalog-categories">
        <table class="form">
          <tr>
            <td><?php echo $entry_catalog_categories_status; ?></td>
            <td><select name="aqe_catalog_categories_status">
                <?php if ($aqe_catalog_categories_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_catalog_categories" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" title="<?php echo $text_select_all; ?>"/><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_catalog_categories as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_catalog_categories-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_catalog_categories][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_catalog_categories][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-catalog-products">
        <table class="form">
          <tr>
            <td><?php echo $entry_catalog_products_status; ?></td>
            <td><select name="aqe_catalog_products_status">
                <?php if ($aqe_catalog_products_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_catalog_products" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_catalog_products as $k => $col) { ?>
                <li class="<?php echo ($col['sortable']) ? 'sort' : ''; ?> <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected'; ?>" id="aqe_catalog_products-<?php echo $k; ?>">
                  <?php if ($col['sortable']) { ?><span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span><?php } ?>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_catalog_products][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_catalog_products][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?><?php echo ($k != "action") ? '' : ' disabled="disabled"'; ?> />
                      <?php if ($k == "action") { ?><input type="hidden" name="display[aqe_catalog_products][<?php echo $k; ?>]" value="1" /><?php } ?>
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_actions; ?></td>
            <td>
              <ul id="aqe_catalog_products_actions" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_button; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_catalog_products_actions as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected'; ?>" id="aqe_catalog_products_actions-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_catalog_products_actions][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? ' checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_catalog_products_filter_sub_category; ?></td>
            <td><input type="checkbox" name="aqe_catalog_products_filter_sub_category" <?php echo ($aqe_catalog_products_filter_sub_category) ? ' checked="checked"': ''; ?> /></td>
          </tr>
        </table>
      </div>
      <div id="tab-catalog-filters">
        <table class="form">
          <tr>
            <td><?php echo $entry_catalog_filters_status; ?></td>
            <td><select name="aqe_catalog_filters_status">
                <?php if ($aqe_catalog_filters_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_catalog_filters" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" title="<?php echo $text_select_all; ?>"/><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_catalog_filters as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_catalog_filters-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_catalog_filters][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_catalog_filters][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-catalog-profiles">
        <table class="form">
          <tr>
            <td><?php echo $entry_catalog_profiles_status; ?></td>
            <td>
              <select name="aqe_catalog_profiles_status">
                <?php if ($aqe_catalog_profiles_status) { ?>
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
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_catalog_profiles" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" title="<?php echo $text_select_all; ?>"/><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_catalog_profiles as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_catalog_profiles-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_catalog_profiles][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_catalog_profiles][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-catalog-attributes">
        <table class="form">
          <tr>
            <td><?php echo $entry_catalog_attributes_status; ?></td>
            <td><select name="aqe_catalog_attributes_status">
                <?php if ($aqe_catalog_attributes_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_catalog_attributes" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_catalog_attributes as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_catalog_attributes-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_catalog_attributes][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_catalog_attributes][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-catalog-attribute-groups">
        <table class="form">
          <tr>
            <td><?php echo $entry_catalog_attribute_groups_status; ?></td>
            <td><select name="aqe_catalog_attribute_groups_status">
                <?php if ($aqe_catalog_attribute_groups_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_catalog_attribute_groups" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_catalog_attribute_groups as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_catalog_attribute_groups-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_catalog_attribute_groups][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_catalog_attribute_groups][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-catalog-options">
        <table class="form">
          <tr>
            <td><?php echo $entry_catalog_options_status; ?></td>
            <td><select name="aqe_catalog_options_status">
                <?php if ($aqe_catalog_options_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_catalog_options" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_catalog_options as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_catalog_options-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_catalog_options][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_catalog_options][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-catalog-manufacturers">
        <table class="form">
          <tr>
            <td><?php echo $entry_catalog_manufacturers_status; ?></td>
            <td><select name="aqe_catalog_manufacturers_status">
                <?php if ($aqe_catalog_manufacturers_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_catalog_manufacturers" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_catalog_manufacturers as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_catalog_manufacturers-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_catalog_manufacturers][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_catalog_manufacturers][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-catalog-downloads">
        <table class="form">
          <tr>
            <td><?php echo $entry_catalog_downloads_status; ?></td>
            <td><select name="aqe_catalog_downloads_status">
                <?php if ($aqe_catalog_downloads_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_catalog_downloads" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_catalog_downloads as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_catalog_downloads-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_catalog_downloads][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_catalog_downloads][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-catalog-reviews">
        <table class="form">
          <tr>
            <td><?php echo $entry_catalog_reviews_status; ?></td>
            <td><select name="aqe_catalog_reviews_status">
                <?php if ($aqe_catalog_reviews_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_catalog_reviews" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_catalog_reviews as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_catalog_reviews-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_catalog_reviews][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_catalog_reviews][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-catalog-information">
        <table class="form">
          <tr>
            <td><?php echo $entry_catalog_information_status; ?></td>
            <td><select name="aqe_catalog_information_status">
                <?php if ($aqe_catalog_information_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_catalog_information" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_catalog_information as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_catalog_information-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_catalog_information][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_catalog_information][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
     </div>

     <div id="tab-sales">
      <div id="sales-tabs" class="htabs">
        <a href="#tab-sales-orders"><?php echo $tab_orders; ?></a>
        <a href="#tab-sales-returns"><?php echo $tab_returns; ?></a>
        <a href="#tab-sales-customers"><?php echo $tab_customers; ?></a>
        <a href="#tab-sales-coupons"><?php echo $tab_coupons; ?></a>
        <a href="#tab-sales-affiliates"><?php echo $tab_affiliates; ?></a>
        <a href="#tab-sales-vouchers"><?php echo $tab_vouchers; ?></a>
        <a href="#tab-sales-voucher-themes"><?php echo $tab_voucher_themes; ?></a>
		<div class="clr"></div>
      </div>
	  <div class="th_style"></div>
      <div id="tab-sales-voucher-themes">
        <table class="form">
          <tr>
            <td><?php echo $entry_sales_voucher_themes_status; ?></td>
            <td><select name="aqe_sales_voucher_themes_status">
                <?php if ($aqe_sales_voucher_themes_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_sales_voucher_themes" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_sales_voucher_themes as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_sales_voucher_themes-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_sales_voucher_themes][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_sales_voucher_themes][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-sales-vouchers">
        <table class="form">
          <tr>
            <td><?php echo $entry_sales_vouchers_status; ?></td>
            <td><select name="aqe_sales_vouchers_status">
                <?php if ($aqe_sales_vouchers_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_sales_vouchers" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_sales_vouchers as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_sales_vouchers-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_sales_vouchers][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_sales_vouchers][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-sales-affiliates">
        <table class="form">
          <tr>
            <td><?php echo $entry_sales_affiliates_status; ?></td>
            <td><select name="aqe_sales_affiliates_status">
                <?php if ($aqe_sales_affiliates_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_sales_affiliates" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_sales_affiliates as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_sales_affiliates-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_sales_affiliates][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_sales_affiliates][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-sales-coupons">
        <table class="form">
          <tr>
            <td><?php echo $entry_sales_coupons_status; ?></td>
            <td><select name="aqe_sales_coupons_status">
                <?php if ($aqe_sales_coupons_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_sales_coupons" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_sales_coupons as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_sales_coupons-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_sales_coupons][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_sales_coupons][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-sales-customers">
        <table class="form">
          <tr>
            <td><?php echo $entry_sales_customers_status; ?></td>
            <td><select name="aqe_sales_customers_status">
                <?php if ($aqe_sales_customers_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_sales_customers" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_sales_customers as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_sales_customers-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_sales_customers][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_sales_customers][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
        </table>
      </div>
      <div id="tab-sales-returns">
        <table class="form">
          <tr>
            <td><?php echo $entry_sales_returns_status; ?></td>
            <td><select name="aqe_sales_returns_status">
                <?php if ($aqe_sales_returns_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_sales_returns" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span><span class="param"><input type="checkbox" class="show_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_show; ?></span><div class="clr"></div></li>
              <?php foreach($aqe_sales_returns as $k => $col) { ?>
                <li class="sort <?php echo ($col['display']) ? 'ui-state-default' : 'ui-state-deselected' ?>" id="aqe_sales_returns-<?php echo $k; ?>">
                  <span class="handle ui-icon ui-icon-arrowthick-2-n-s"></span>
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_sales_returns][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <span class="param">
                    <input type="checkbox" name="display[aqe_sales_returns][<?php echo $k; ?>]" class="column_display" <?php echo ($col["display"]) ? 'checked="checked"': ''; ?> />
                  </span>
				  <div class="clr"></div>
                </li>
              <?php } ?>
              </ul>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_notify_customer; ?></td>
            <td><input type="checkbox" name="aqe_sales_returns_notify_customer" value="1" <?php echo ($aqe_sales_returns_notify_customer) ? ' checked="checked"': ''; ?> /></td>
          </tr>
        </table>
      </div>
      <div id="tab-sales-orders">
        <table class="form">
          <tr>
            <td><?php echo $entry_sales_orders_status; ?></td>
            <td><select name="aqe_sales_orders_status">
                <?php if ($aqe_sales_orders_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_fields; ?></td>
            <td>
              <ul id="aqe_sales_orders" class="sortable">
                <li class="ui-state-disabled"><span class="column_name"><?php echo $text_column_name; ?></span><span class="param"><input type="checkbox" class="editable_all" title="<?php echo $text_select_all; ?>" /><?php echo $text_editable; ?></span></li>
              <?php foreach($aqe_sales_orders as $k => $col) { ?>
                <li class="sort ui-state-default" id="aqe_sales_orders-<?php echo $k; ?>">
                  <?php echo $col['name']; ?>
                  <span class="param">
                    <input type="checkbox" name="quick_edit[aqe_sales_orders][<?php echo $k; ?>]" <?php echo ($col["qe_status"]) ? ' checked="checked"': ''; ?><?php echo ($col["editable"]) ? '' : ' disabled="disabled"'; ?> />
                  </span>
                  <input type="hidden" name="display[aqe_sales_orders][<?php echo $k; ?>]" class="column_display" checked="checked" />
                <div class="clr"></div>
				</li>
              <?php } ?>
              </ul>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_notify_customer; ?></td>
            <td><input type="checkbox" name="aqe_sales_orders_notify_customer" value="1" <?php echo ($aqe_sales_orders_notify_customer) ? ' checked="checked"': ''; ?> /></td>
          </tr>
        </table>
      </div>
     </div>

     <div id="tab-about">
      <table class="form">
       <tr>
        <td style="min-width:200px;"><?php echo $text_ext_name; ?></td>
        <td style="min-width:400px;"><?php echo $ext_name; ?></td>
        <td rowspan="7" style="width:440px;border-bottom:0px;"><img src="view/image/aqe-pro/extension_logo.png" /></td>
       </tr>
       <tr>
        <td><?php echo $text_ext_version; ?></td>
        <td><b><?php echo $ext_version; ?></b> [ <?php echo $ext_type; ?> ]</td>
       </tr>
       <tr>
        <td><?php echo $text_ext_compat; ?></td>
        <td><?php echo $ext_compatibility; ?></td>
       </tr>
       <tr>
        <td><?php echo $text_ext_url; ?></td>
        <td><a href="<?php echo $ext_url; ?>" target="_blank"><?php echo $ext_url ?></a></td>
       </tr>
       <tr>
        <td><?php echo $text_ext_support; ?></td>
        <td>
          <a href="mailto:<?php echo $ext_support; ?>?subject=<?php echo $ext_subject; ?>"><?php echo $ext_support; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a href="<?php echo $ext_support_forum; ?>"><?php echo $text_forum; ?></a>
          <a href="view/static/bull5i_aqe_pro_extension_help.htm" id="help_notice" style="float:right;"><?php echo $text_asking_help; ?></a>
        </td>
       </tr>
       <tr>
        <td><?php echo $text_ext_legal; ?></td>
        <td>Copyright &copy; 2011 Romi Agar. <a href="view/static/bull5i_aqe_pro_extension_terms.htm" id="legal_notice"><?php echo $text_terms; ?></a></td>
       </tr>
       <tr>
        <td style="border-bottom:0px;"></td>
        <td style="border-bottom:0px;"></td>
       </tr>
      </table>
     </div>

    </form>
   </div>
  </div>
</div>
<div id="legal_text" style="display:none"></div>
<div id="help_text" style="display:none"></div>
<script type="text/javascript"><!--
$('#tabs a').tabs();
$('#catalog-tabs a, #sales-tabs a').tabs();
$('#aqe_catalog_products, #aqe_catalog_categories, #aqe_catalog_filters, #aqe_catalog_profiles, #aqe_catalog_attributes, ' +
  '#aqe_catalog_attribute_groups, #aqe_catalog_options, #aqe_catalog_manufacturers, #aqe_catalog_downloads, #aqe_catalog_reviews, ' +
  '#aqe_catalog_information, #aqe_sales_coupons, #aqe_sales_customers, #aqe_sales_affiliates, ' +
  '#aqe_sales_voucher_themes, #aqe_sales_vouchers, #aqe_sales_returns, #aqe_catalog_products_actions').sortable({
    handle : 'span.ui-icon',
    opacity : 0.8,
    items: 'li.sort',
    update: function(evt, ui){
        var id = $(ui.item).parent().attr("id");
        $.ajax({
            type: 'POST',
            url: '<?php echo $update_url; ?>',
            dataType: 'json',
            data: {page: id, data : $("#" + id).sortable('toArray')},
            success: function(data) {
                if (data.error) {
                    show_message(data.error, $(document), true);
                }
                if (data.success) {
                    $("div.warning").slideUp("slow");
                }
            }
        });
    }
});
$("input[type=checkbox].editable_all").change(function () {
    $("input[name^='quick_edit']", $(this).parents('ul').first()).not(":disabled").attr('checked', $(this).is(':checked'));
});
$("input[type=checkbox].show_all").change(function () {
    $("input[name^='display']", $(this).parents('ul').first()).not(":disabled").attr('checked', $(this).is(':checked')).trigger('change');
});
$('#aqe_catalog_products, #aqe_catalog_categories, #aqe_catalog_filters, #aqe_catalog_profiles, #aqe_catalog_attributes, ' +
  '#aqe_catalog_attribute_groups, #aqe_catalog_options, #aqe_catalog_manufacturers, #aqe_catalog_downloads, #aqe_catalog_reviews, ' +
  '#aqe_catalog_information, #aqe_sales_coupons, #aqe_sales_customers, #aqe_sales_affiliates, ' +
  '#aqe_sales_voucher_themes, #aqe_sales_vouchers, #aqe_sales_returns, #aqe_catalog_products_actions').disableSelection();
$('input[type=checkbox].column_display').change(function () {
    if ($(this).is(':checked')) {
        $(this).parents('li').first().removeClass('ui-state-deselected').addClass('ui-state-default');
    } else {
        $(this).parents('li').first().removeClass('ui-state-default').addClass('ui-state-deselected');
    }
});
$("#legal_notice").click(function(e) {
    e.preventDefault();
    $("#legal_text").load(this.href, function() {
        $(this).dialog({
            title: '<?php echo $text_terms; ?>',
            width:  800,
            height:  600,
            minWidth:  500,
            minHeight:  400,
            modal: true,
        });
    });
    return false;
});
$("#help_notice").click(function(e) {
    e.preventDefault();
    $("#help_text").load(this.href, function() {
        $(this).dialog({
            title: '<?php echo $text_help_request; ?>',
            width:  800,
            height:  600,
            minWidth:  500,
            minHeight:  400,
            modal: true,
        });
    });
    return false;
});
$('#apply_settings').click(function(e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: $("#form").attr("action"),
        dataType: 'html',
        data: $("#form").serialize() + "&ajax=1",
        beforeSend: function() {
            $("#overlay").stop().fadeTo(300, 0.8)
        },
        success: function(data) {
            $data = $(data);
            if($data.find("span.error").length > 0) {
                $data.find("span.error").each(function(i, e) {
                    name = $(e).prevAll(":input").attr("name");
                    $control = $("[name='"+ name +"']");

                    if($control.next("img").length > 0){
                        $control.next("img").after(e)
                    }else{
                        $control.after(e);
                    }
                });
            } else {
                $("span.error").remove();
            }
            warning = $data.find("div.warning").get(0);
            success = $data.find("div.success").get(0);
            if (warning) {
                show_message($(warning).html(), $(document), true);
            } else {
                show_message($(success).html(), $(document));
            }
        },
        complete: function() {
            $("#overlay").stop().fadeOut(300)
        }
    });
});
//--></script>
<?php echo $footer; ?>
