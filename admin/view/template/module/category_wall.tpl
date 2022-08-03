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
            <div class="buttons"><a onclick="$('#apply').val(1); $('#form').submit();" class="button"><span><?php echo $button_apply; ?></span></a><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a href="<?php echo $cancel; ?>" class="button"><span><?php echo $button_cancel; ?></span></a></div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
                <input type="hidden" name="apply" id="apply" value="0" />
                <div class="vtabs">
                    <?php $module_row = 1; ?>
                    <?php foreach ($modules as $module) { ?>
                        <a href="#tab-module-<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>">
                            <?php if (!empty($module['module_name'])) { ?>
                                <?php echo $module['module_name']; ?>
                                <?php } else { ?>
                                <?php echo $tab_module . ' ' . $module_row; ?>
                            <?php } ?>
                        &nbsp;<img src="view/image/delete.png" alt="" onclick="$('.vtabs a:first').trigger('click'); $('#module-<?php echo $module_row; ?>').remove(); $('#tab-module-<?php echo $module_row; ?>').remove(); return false;" /></a>
                        <?php $module_row++; ?>
                    <?php } ?>
                <span id="module-add"><?php echo $button_add_module; ?>&nbsp; <img src="view/image/add.png" alt="" onclick="addModule();" /></span></div>
                <?php $module_row = 1; ?>
                <?php $custom_item_row = 1; ?>
                <?php foreach ($modules as $module) { ?>
                    <div id="tab-module-<?php echo $module_row; ?>" class="vtabs-content">
                        <div id="tabs-<?php echo $module_row; ?>" class="htabs"><a href="#tab-module-setting-<?php echo $module_row; ?>"><?php echo $tab_module_setting; ?></a><a href="#tab-wall-setting-<?php echo $module_row; ?>"><?php echo $tab_showcase; ?></a><a href="#tab-other-<?php echo $module_row; ?>"><?php echo $tab_other; ?></a></div>
                        
                        <div id="tab-module-setting-<?php echo $module_row; ?>">
                            <table class="form">
                                <tr>
                                    <td><?php echo $entry_name; ?></td>
                                    <td><input type="text" name="category_wall_module[<?php echo $module_row; ?>][module_name]" value="<?php echo isset($module['module_name']) ? $module['module_name'] : ''; ?>" class="span3" /></td>
                                </tr>
                                <tr>
                                    <td><?php echo $entry_store; ?></td>
                                    <td>
                                    <?php foreach ($stores as $store) { ?>                                     
                                            <input type="checkbox" name="category_wall_module[<?php echo $module_row; ?>][store_id][]" value="<?php echo $store['store_id']; ?>" <?php echo isset($module['store_id']) && in_array($store['store_id'], $module['store_id']) ? 'checked="checked" ' : ''; ?> />
                                        <?php echo $store['name']; ?>
                                    <?php } ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_layout; ?></td>
                                <td><select name="category_wall_module[<?php echo $module_row; ?>][layout_id]" class="span3">
                                    <?php foreach ($layouts as $layout) { ?>
                                        <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                                            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_position; ?></td>
                                <td><select name="category_wall_module[<?php echo $module_row; ?>][position]" class="span3">
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
                            </tr>
                            <tr>
                                <td><?php echo $entry_count; ?></td>
                                <td><select name="category_wall_module[<?php echo $module_row; ?>][count]" class="span3">
                                    <?php if (!empty($module['count'])) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_status; ?></td>
                                <td><select name="category_wall_module[<?php echo $module_row; ?>][status]" class="span3">
                                    <?php if ($module['status']) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_sort_order; ?></td>
                                <td><input type="text" name="category_wall_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" class="span1" /></td>
                            </tr>
                        </table>
                    </div>
                    <div id="tab-wall-setting-<?php echo $module_row; ?>">
                        <table class="form">
                            <tr>
                                <td><?php echo $entry_title; ?></td>
                                <td><?php foreach ($languages as $language) { ?>
                                    <p>
                                        <input type="text" name="category_wall_module[<?php echo $module_row; ?>][menu_title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($module['menu_title'][$language['language_id']]) ? $module['menu_title'][$language['language_id']] : ''; ?>" class="span3" />
                                        <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin: 0 7px 0 -26px; vertical-align: middle;" /><span class="custom-checkbox">
                                            <input type="checkbox" id="checkboxTitleStatus-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>" name="category_wall_module[<?php echo $module_row; ?>][title_status][<?php echo $language['language_id']; ?>]" value="1" <?php echo (isset($module['title_status'][$language['language_id']]) ? 'checked="checked" ' : ''); ?> />
                                            <label for="checkboxTitleStatus-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>"></label>
                                        </span></p>
                                <?php } ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_items; ?></td>
                                <td colspan="2"><div id="items-tabs-<?php echo $module_row; ?>" class="htabs" style="width:auto; padding:0;"><a href="#tab-categories-<?php echo $module_row; ?>"><?php echo $tab_categories; ?></a><a href="#tab-manufacturers-<?php echo $module_row; ?>"><?php echo $tab_manufacturers; ?></a><a href="#tab-items-<?php echo $module_row; ?>"><?php echo $tab_custom_items; ?></a></div>
                                    <div id="tab-categories-<?php echo $module_row; ?>">
                                        <?php if ($module['featured_categories'] == 'all') { ?>
                                            <label class="radio inline">
                                                <input type="radio" name="category_wall_module[<?php echo $module_row; ?>][featured_categories]" value="all" checked="checked" />
                                            <?php echo $text_all; ?> </label>
                                            <?php } else { ?>
                                            <label class="radio inline">
                                                <input type="radio" name="category_wall_module[<?php echo $module_row; ?>][featured_categories]" value="all" />
                                            <?php echo $text_all; ?> </label>
                                        <?php } ?>
                                        &nbsp;&nbsp;
                                        <?php if ($module['featured_categories'] == 'featured') { ?>
                                            <label class="radio inline">
                                                <input type="radio" name="category_wall_module[<?php echo $module_row; ?>][featured_categories]" value="featured" checked="checked" />
                                            <?php echo $text_featured; ?> </label>
                                            <?php } else { ?>
                                            <label class="radio inline">
                                                <input type="radio" name="category_wall_module[<?php echo $module_row; ?>][featured_categories]" value="featured" />
                                            <?php echo $text_featured; ?> </label>
                                        <?php } ?>
                                        <div id="featured-categories-<?php echo $module_row; ?>" <?php echo (isset($module['featured_categories']) && $module['featured_categories'] == 'featured' ? '' : 'style="display:none;"'); ?>>
                                            <div class="scrollbox" style="margin-top: 10px;">
                                                <?php $class = 'odd'; ?>
                                                <?php foreach ($categories as $category) { ?>
                                                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                                    <div class="<?php echo $class; ?>">
                                                        <?php if (!empty($module['category_selected']) && in_array($category['category_id'], $module['category_selected'])) { ?>
                                                            <label>
                                                                <input type="checkbox" name="category_wall_module[<?php echo $module_row; ?>][category_selected][]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                                                            <?php echo $category['name']; ?> </label>
                                                            <?php } else { ?>
                                                            <label>
                                                                <input type="checkbox" name="category_wall_module[<?php echo $module_row; ?>][category_selected][]" value="<?php echo $category['category_id']; ?>" />
                                                            <?php echo $category['name']; ?> </label>
                                                        <?php } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a> </div>
                                    </div>
                                    <div id="tab-manufacturers-<?php echo $module_row; ?>">
                                        <div class="scrollbox">
                                            <?php $class = 'odd'; ?>
                                            <?php foreach ($manufacturers as $manufacturer) { ?>
                                                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                                <div class="<?php echo $class; ?>">
                                                    <?php if (!empty($module['manufacturer_selected']) && in_array($manufacturer['manufacturer_id'], $module['manufacturer_selected'])) { ?>
                                                        <label>
                                                            <input type="checkbox" name="category_wall_module[<?php echo $module_row; ?>][manufacturer_selected][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
                                                        <?php echo $manufacturer['name']; ?> </label>
                                                        <?php } else { ?>
                                                        <label>
                                                            <input type="checkbox" name="category_wall_module[<?php echo $module_row; ?>][manufacturer_selected][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                                                        <?php echo $manufacturer['name']; ?> </label>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a> </div>
                                    <div id="tab-items-<?php echo $module_row; ?>">
                                        <table id="items-<?php echo $module_row; ?>" class="list">
                                            <thead>
                                                <tr>
                                                    <td class="left"><?php echo $entry_item_title; ?></td>
                                                    <td class="left"><?php echo $entry_link; ?></td>
                                                    <td class="left"><?php echo $entry_image; ?></td>
                                                    <td class="left"><?php echo $entry_sort_order; ?></td>
                                                    <td></td>
                                                </tr>
                                            </thead>
                                            <?php if (isset($module['custom_item'])) { ?>
                                                <?php foreach ($module['custom_item'] as $custom_item) { ?>
                                                    <tbody id="item-row-<?php echo $module_row; ?>-<?php echo $custom_item_row; ?>">
                                                        <tr>
                                                            <td class="left"><?php foreach ($languages as $language) { ?>
                                                                <input type="text" name="category_wall_module[<?php echo $module_row; ?>][custom_item][<?php echo $custom_item_row; ?>][item_title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($custom_item['item_title'][$language['language_id']]) ? $custom_item['item_title'][$language['language_id']] : ''; ?>" style="margin-bottom: 3px;" class="span2" />
                                                                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                                                            <?php } ?></td>
                                                            <td class="left"><?php foreach ($languages as $language) { ?>
                                                                <input type="text" name="category_wall_module[<?php echo $module_row; ?>][custom_item][<?php echo $custom_item_row; ?>][href][<?php echo $language['language_id']; ?>]" value="<?php echo isset($custom_item['href'][$language['language_id']]) ? $custom_item['href'][$language['language_id']] : ''; ?>" style="margin-bottom: 3px;" class="span2" />
                                                                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                                                            <?php } ?></td>
                                                            <td class="left"><div class="image"><img src="<?php echo !empty($custom_item['image']) ? $this->model_tool_image->resize($custom_item['image'], 100, 100) : $no_image; ?>" alt="" id="thumb-<?php echo $module_row; ?>-<?php echo $custom_item_row; ?>" />
                                                                <input type="hidden" name="category_wall_module[<?php echo $module_row; ?>][custom_item][<?php echo $custom_item_row; ?>][image]" value="<?php echo $custom_item['image']; ?>" id="image-<?php echo $module_row; ?>-<?php echo $custom_item_row; ?>" />
                                                                <br />
                                                            <a onclick="image_upload('image-<?php echo $module_row; ?>-<?php echo $custom_item_row; ?>', 'thumb-<?php echo $module_row; ?>-<?php echo $custom_item_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-<?php echo $module_row; ?>-<?php echo $custom_item_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image-<?php echo $module_row; ?>-<?php echo $custom_item_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
                                                            <td class="left"><input type="text" name="category_wall_module[<?php echo $module_row; ?>][custom_item][<?php echo $custom_item_row; ?>][sort_order]" value="<?php echo isset($custom_item['sort_order']) ? $custom_item['sort_order'] : ''; ?>" class="span1" />
                                                            <input type="hidden" name="category_wall_module[<?php echo $module_row; ?>][custom_item][<?php echo $custom_item_row; ?>][ciid]" value="<?php echo $module_row; ?><?php echo $custom_item_row; ?>" /></td>
                                                            <td class="left"><a onclick="$('#item-row-<?php echo $module_row; ?>-<?php echo $custom_item_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
                                                        </tr>
                                                    </tbody>
                                                    <?php $custom_item_row++; ?>
                                                <?php } ?>
                                            <?php } ?>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td class="left"><a onclick="addLink('<?php echo $module_row; ?>');" class="button"><span><?php echo $button_insert; ?></span></a></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div></td>
                            </tr>
                            <tr>
                                <td colspan="2"><h3><?php echo $entry_parent; ?></h3></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_description; ?></td>
                                <td><select name="category_wall_module[<?php echo $module_row; ?>][description_status]" class="span2">
                                    <?php if ($module['description_status']) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                                &nbsp;&nbsp;
                                <input type="text" name="category_wall_module[<?php echo $module_row; ?>][description_limit]" value="<?php echo $module['description_limit']; ?>" class="span1" />
                                <span class="help-inline"><?php echo $text_limit; ?></span></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_image_size; ?></td>
                                <td colspan="2"><input type="text" name="category_wall_module[<?php echo $module_row; ?>][image_width]" value="<?php echo isset($module['image_width']) ? $module['image_width'] : '170'; ?>" class="span1" />
                                    <span class="muted"> x </span>
                                <input type="text" name="category_wall_module[<?php echo $module_row; ?>][image_height]" value="<?php echo isset($module['image_height']) ? $module['image_height'] : '100'; ?>" class="span1" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_column; ?></td>
                                <td><input type="text" name="category_wall_module[<?php echo $module_row; ?>][parent_column]" value="<?php echo !empty($module['parent_column']) ? $module['parent_column'] : '4'; ?>" class="span1" /></td>
                            </tr>
                            <tr>
                                <td colspan="2"><h3><?php echo $entry_child; ?></h3></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_design; ?></td>
                                <td><select name="category_wall_module[<?php echo $module_row; ?>][design]" class="span3" onchange="if($(this).val() == 'fixed') {$('#view-toggle-<?php echo $module_row; ?>,#sub-image-toggle-<?php echo $module_row; ?>').hide();} else {$('#view-toggle-<?php echo $module_row; ?>').show();}">
                                    <?php if (isset($module['design']) && $module['design'] == 'accordion') { ?>
                                        <option value="accordion" selected="selected"><?php echo $text_accordion; ?></option>
                                        <?php } else { ?>
                                        <option value="accordion"><?php echo $text_accordion; ?></option>
                                    <?php } ?>
                                    <?php if (isset($module['design']) && $module['design'] == 'flyout') { ?>
                                        <option value="flyout" selected="selected"><?php echo $text_flyout; ?></option>
                                        <?php } else { ?>
                                        <option value="flyout"><?php echo $text_flyout; ?></option>
                                    <?php } ?>
                                    <?php if (isset($module['design']) && $module['design'] == 'fixed') { ?>
                                        <option value="fixed" selected="selected"><?php echo $text_fixed; ?></option>
                                        <?php } else { ?>
                                        <option value="fixed"><?php echo $text_fixed; ?></option>
                                    <?php } ?>
                                </select></td>
                            </tr>
                            <tr id="view-toggle-<?php echo $module_row; ?>" <?php echo (isset($module['design']) &&  $module['design'] == 'fixed' ? 'style="display:none;"' : ''); ?>>
                                <td><?php echo $entry_view; ?></td>
                                <td><select name="category_wall_module[<?php echo $module_row; ?>][view]" class="span3" onchange="if($(this).val() == 'grid') {$('#sub-image-toggle-<?php echo $module_row; ?>').hide();} else {$('#sub-image-toggle-<?php echo $module_row; ?>').show();}">
                                    <?php if (isset($module['view']) && $module['view'] == 'grid' ) { ?>
                                        <option value="grid" selected="selected"><?php echo $text_grid; ?></option>
                                        <?php } else { ?>
                                        <option value="grid"><?php echo $text_grid; ?></option>
                                    <?php } ?>
                                    <?php if (isset($module['view']) && $module['view'] == 'list') { ?>
                                        <option value="list" selected="selected"><?php echo $text_list; ?></option>
                                        <?php } else { ?>
                                        <option value="list"><?php echo $text_list; ?></option>
                                    <?php } ?>
                                </select></td>
                            </tr>
                            <tr id="sub-image-toggle-<?php echo $module_row; ?>" <?php echo (isset($module['design']) &&  $module['design'] == 'fixed' || isset($module['view']) == 'grid' && $module['view'] == 'grid' ? 'style="display:none;"' : ''); ?>>
                                <td><?php echo $entry_sub_image; ?></td>
                                <td><select name="category_wall_module[<?php echo $module_row; ?>][sub_image]" class="span3">
                                    <?php if ($module['sub_image']) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_image_size; ?></td>
                                <td colspan="2"><input type="text" name="category_wall_module[<?php echo $module_row; ?>][child_image_width]" value="<?php echo isset($module['child_image_width']) ? $module['child_image_width'] : '170'; ?>" class="span1" />
                                    <span class="muted"> x </span>
                                <input type="text" name="category_wall_module[<?php echo $module_row; ?>][child_image_height]" value="<?php echo isset($module['child_image_height']) ? $module['child_image_height'] : '100'; ?>" class="span1" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_column; ?></td>
                                <td><input type="text" name="category_wall_module[<?php echo $module_row; ?>][child_column]" value="<?php echo !empty($module['child_column']) ? $module['child_column'] : '4'; ?>" class="span1" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_child_limit; ?></td>
                                <td><input type="text" name="category_wall_module[<?php echo $module_row; ?>][child_limit]" value="<?php echo !empty($module['child_limit']) ? $module['child_limit'] : '12'; ?>" class="span1" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_child2_limit; ?></td>
                                <td><input type="text" name="category_wall_module[<?php echo $module_row; ?>][child2_limit]" value="<?php echo !empty($module['child2_limit']) ? $module['child2_limit'] : '12'; ?>" class="span1" /></td>
                            </tr>
                        </table>
                    </div>
                    <div id="tab-other-<?php echo $module_row; ?>">
                        <table class="form">
                            <tr>
                                <td><?php echo $entry_box_class; ?></td>
                                <td><input type="text" name="category_wall_module[<?php echo $module_row; ?>][box_class]" value="<?php echo !empty($module['box_class']) ? $module['box_class'] : 'box'; ?>" class="span3" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_tag; ?></td>
                                <td><select name="category_wall_module[<?php echo $module_row; ?>][tag]" class="span3">
                                    <?php if ($module['tag']== 'div') { ?>
                                        <option value="div" selected="selected">div</option>
                                        <?php } else { ?>
                                        <option value="div">div</option>
                                    <?php } ?>
                                    <?php if ($module['tag'] == 'h1') { ?>
                                        <option value="h1" selected="selected">h1</option>
                                        <?php } else { ?>
                                        <option value="h1">h1</option>
                                    <?php } ?>
                                    <?php if ($module['tag'] == 'h2') { ?>
                                        <option value="h2" selected="selected">h2</option>
                                        <?php } else { ?>
                                        <option value="h2">h2</option>
                                    <?php } ?>
                                    <?php if ($module['tag'] == 'h3') { ?>
                                        <option value="h3" selected="selected">h3</option>
                                        <?php } else { ?>
                                        <option value="h3">h3</option>
                                    <?php } ?>
                                    <?php if ($module['tag'] == 'h4') { ?>
                                        <option value="h4" selected="selected">h4</option>
                                        <?php } else { ?>
                                        <option value="h4">h4</option>
                                    <?php } ?>
                                    <?php if ($module['tag'] == 'h5') { ?>
                                        <option value="h5" selected="selected">h5</option>
                                        <?php } else { ?>
                                        <option value="h5">h5</option>
                                    <?php } ?>
                                    <?php if ($module['tag'] == 'h6') { ?>
                                        <option value="h6" selected="selected">h6</option>
                                        <?php } else { ?>
                                        <option value="h6">h6</option>
                                    <?php } ?>
                                </select></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_heading_class; ?></td>
                                <td><input type="text" name="category_wall_module[<?php echo $module_row; ?>][heading_class]" value="<?php echo !empty($module['heading_class']) ? $module['heading_class'] : 'box-heading'; ?>" class="span3" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_content_class; ?></td>
                                <td><input type="text" name="category_wall_module[<?php echo $module_row; ?>][content_class]" value="<?php echo !empty($module['content_class']) ? $module['content_class'] : 'box-content'; ?>" class="span3" /></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php $module_row++; ?>
            <?php } ?>
        </form>
    </div>
</div>
</div>
<script type="text/javascript"><!--
    var module_row = <?php echo $module_row; ?>;
    
    function addModule() {
        
        html  = '<div id="tab-module-' + module_row + '" class="vtabs-content">';
        html += '  <div id="tabs-' + module_row + '" class="htabs"><a href="#tab-module-setting-' + module_row + '"><?php echo $tab_module_setting; ?></a><a href="#tab-wall-setting-' + module_row + '"><?php echo $tab_showcase; ?></a><a href="#tab-other-' + module_row + '"><?php echo $tab_other; ?></a></div>';
        html += '  <div id="tab-module-setting-' + module_row + '">';
        html += '    <table class="form">';
        html += '      <tr>';
        html += '        <td><?php echo $entry_name; ?></td>';
        html += '        <td><input type="text" name="category_wall_module[' + module_row + '][module_name]" value="" class="span3" /></td>';
        html += '      </tr>';
        html += '      <tr>';
        html += '        <td><?php echo $entry_store; ?></td>';
        html += '        <td><div class="row">';
        html += '          <div class="span3">';
        html += '          <label class="checkbox">';
        html += '            <input type="checkbox" name="category_wall_module[' + module_row + '][store_id][]" value="" checked="checked" />';
        html += '          <?php echo addslashes($default_store); ?> </label>';
        <?php foreach ($stores as $store) { ?>
            html += '          <label class="checkbox">';
            html += '            <input type="checkbox" name="category_wall_module[' + module_row + '][store_id][]" value="<?php echo $store['store_id']; ?>" checked="checked" />';
            html += '          <?php echo addslashes($store['name']); ?> </label>';
        <?php } ?>
        html += '          </div>';
        html += '        </div></td>';
        html += '      </tr>';
        html += '      <tr>';
        html += '        <td><?php echo $entry_layout; ?></td>';
        html += '        <td><select name="category_wall_module[' + module_row + '][layout_id]" class="span3">';
        <?php foreach ($layouts as $layout) { ?>
            html += '             <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
        <?php } ?>
        html += '        </select></td>';
        html += '      </tr>';
        html += '      <tr>';
        html += '        <td><?php echo $entry_position; ?></td>';
        html += '        <td><select name="category_wall_module[' + module_row + '][position]" class="span3">';
        html += '          <option value="content_top"><?php echo $text_content_top; ?></option>';
        html += '          <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
        html += '          <option value="column_left"><?php echo $text_column_left; ?></option>';
        html += '          <option value="column_right"><?php echo $text_column_right; ?></option>';
        html += '        </select></td>';
        html += '      </tr>';
        html += '      <tr>';
        html += '        <td><?php echo $entry_count; ?></td>';
        html += '        <td><select name="category_wall_module[' + module_row + '][count]" class="span3">';
        html += '          <option value="1"><?php echo $text_enabled; ?></option>';
        html += '          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
        html += '        </select></td>';
        html += '      </tr>';
        html += '      <tr>';
        html += '        <td><?php echo $entry_status; ?></td>';
        html += '        <td><select name="category_wall_module[' + module_row + '][status]" class="span3">';
        html += '          <option value="1"><?php echo $text_enabled; ?></option>';
        html += '          <option value="0"><?php echo $text_disabled; ?></option>';
        html += '        </select></td>';
        html += '      </tr>';
        html += '      <tr>';
        html += '        <td><?php echo $entry_sort_order; ?></td>';
        html += '        <td><input type="text" name="category_wall_module[' + module_row + '][sort_order]" value="" class="span1" /></td>';
        html += '      </tr>';
        html += '    </table>';
        html += '  </div>';
        html += '  <div id="tab-wall-setting-' + module_row + '">';
        html += '    <table class="form">';
        html += '      <tr>';
        html += '        <td><?php echo $entry_title; ?></td>';
        html += '        <td>';
        <?php foreach ($languages as $language) { ?>
            html += '          <p><input type="text" name="category_wall_module[' + module_row + '][menu_title][<?php echo $language['language_id']; ?>]" value="" class="span3" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin: 0 7px 0 -26px; vertical-align: middle;" /><span class="custom-checkbox"><input type="checkbox" id="checkboxTitleStatus-' + module_row + '-<?php echo $language['language_id']; ?>" name="category_wall_module[' + module_row + '][title_status][<?php echo $language['language_id']; ?>]" value="1" checked="checked" />';
            html += '          <label for="checkboxTitleStatus-' + module_row + '-<?php echo $language['language_id']; ?>"></label></span></p>';
        <?php } ?>
        html += '        </td>';
        html += '      </tr>';
        html += '      <tr>';
        html += '        <td><?php echo $entry_items; ?></td>';
        html += '        <td colspan="2"><div id="items-tabs-' + module_row + '" class="htabs" style="width:auto; padding:0;">';
        html += '          <a href="#tab-categories-'+ module_row + '"><?php echo $tab_categories; ?></a><a href="#tab-manufacturers-' + module_row + '"><?php echo $tab_manufacturers; ?></a><a href="#tab-items-' + module_row + '"><?php echo $tab_custom_items; ?></a>';
        html += '        </div>';
        html += '        <div id="tab-categories-' + module_row + '">';
        html += '          <label class="radio inline">';
        html += '            <input type="radio" name="category_wall_module[' + module_row + '][featured_categories]" value="all" checked="checked" onchange="$(\'#featured-categories-' + module_row + '\').toggle();" />';
        html += '            <?php echo $text_all; ?>';
        html += '          </label> &nbsp;&nbsp;';
        html += '          <label class="radio inline">';
        html += '            <input type="radio" name="category_wall_module[' + module_row + '][featured_categories]" value="featured" onchange="$(\'#featured-categories-' + module_row + '\').toggle();" />';
        html += '            <?php echo $text_featured; ?>';
        html += '          </label>';
        html += '        <div id="featured-categories-' + module_row + '" style="display:none;">';
        html += '          <div class="scrollbox" style="margin-top: 10px;">';
        <?php $class = 'odd'; ?>
        <?php foreach ($categories as $category) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            html += '          <div class="<?php echo $class; ?>">';
            html += '          <label><input type="checkbox" name="category_wall_module[' + module_row + '][category_selected][]" value="<?php echo $category['category_id']; ?>" />';
            html += '          <?php echo addslashes($category['name']); ?> </label>';
            html += '          </div>';
        <?php } ?>
        html += '        </div><a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a></div>';
        html += '        </div>';
        html += '        <div id="tab-manufacturers-' + module_row + '">';
        html += '          <div class="scrollbox">';
        <?php $class = 'odd'; ?>
        <?php foreach ($manufacturers as $manufacturer) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            html += '          <div class="<?php echo $class; ?>">';
            html += '          <label><input type="checkbox" name="category_wall_module[' + module_row + '][manufacturer_selected][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />';
            html += '          <?php echo addslashes($manufacturer['name']); ?> </label>';
            html += '          </div>';
        <?php } ?>
        html += '        </div><a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a></div>';
        html += '        <div id="tab-items-'+ module_row + '">';
        html += '          <table id="items-'+ module_row + '" class="list">';
        html += '            <thead>';
        html += '              <tr>';
        html += '                <td class="left"><?php echo $entry_item_title; ?></td>';
        html += '                <td class="left"><?php echo $entry_link; ?></td>';
        html += '                <td class="left"><?php echo $entry_image; ?></td>';
        html += '                <td class="left"><?php echo $entry_sort_order; ?></td>';
        html += '                <td></td>';
        html += '              </tr>';
        html += '            </thead>';
        html += '            <tfoot>';
        html += '              <tr>';
        html += '                <td colspan="4"></td>';
        html += '                <td class="left"><a onclick="addLink('+ module_row +');" class="button"><span><?php echo $button_insert; ?></span></a></td>';
        html += '              </tr>';
        html += '            </tfoot>';
        html += '          </table>';
        html += '        </div>';
        html += '      </td>';
        html += '    </tr>';
        html += '    <tr>';
        html += '      <td colspan="2"><h3><?php echo $entry_parent; ?></h3></td>';
        html += '    </tr>'; 
        html += '    <tr>';
        html += '      <td><?php echo $entry_description; ?></td>';
        html += '      <td><select name="category_wall_module[' + module_row + '][description_status]" class="span2">';
        html += '        <option value="1"><?php echo $text_enabled; ?></option>';
        html += '        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
        html += '      </select>&nbsp;&nbsp;&nbsp;';
        html += '      <input type="text" name="category_wall_module[' + module_row + '][description_limit]" value="" class="span1" /> <span class="help-inline"><?php echo $text_limit; ?></span></td>';
        html += '    </tr>';
        html += '    <tr>';
        html += '      <td><?php echo $entry_image_size; ?></td>';
        html += '      <td colspan="2"><input type="text" name="category_wall_module[' + module_row + '][image_width]" value="170" class="span1" /><span class="muted"> x </span><input type="text" name="category_wall_module[' + module_row + '][image_height]" value="100" class="span1" /></td>';
        html += '    </tr>';
        html += '    <tr>';
        html += '      <td><?php echo $entry_column; ?></td>';
        html += '      <td><input type="text" name="category_wall_module[' + module_row + '][parent_column]" value="4" class="span1" /></td>';
        html += '    </tr>';
        html += '    <tr>';
        html += '      <td colspan="2"><h3><?php echo $entry_child; ?></h3></td>';
        html += '    </tr>';
        html += '    <tr>';
        html += '      <td><?php echo $entry_design; ?></td>';
        html += '      <td><select name="category_wall_module[' + module_row + '][design]" class="span3" onchange="if($(this).val() == \'fixed\') {$(\'#view-toggle-'+ module_row +',#sub-image-toggle-'+ module_row +'\').hide();} else {$(\'#view-toggle-'+ module_row +'\').show();}">';
        html += '        <option value="accordion"><?php echo $text_accordion; ?></option>';
        html += '        <option value="flyout"><?php echo $text_flyout; ?></option>';
        html += '        <option value="fixed"><?php echo $text_fixed; ?></option>';
        html += '      </select></td>';
        html += '    </tr>';
        html += '    <tr id="view-toggle-' + module_row + '">';
        html += '      <td><?php echo $entry_view; ?></td>';
        html += '      <td><select name="category_wall_module[' + module_row + '][view]" class="span3" onchange="if($(this).val() == \'grid\') {$(\'#sub-image-toggle-'+ module_row +'\').hide();} else {$(\'#sub-image-toggle-'+ module_row +'\').show();}">';
        html += '        <option value="grid"><?php echo $text_grid; ?></option>';
        html += '        <option value="list"><?php echo $text_list; ?></option>';
        html += '      </select></td>';
        html += '    </tr>';
        html += '    <tr id="sub-image-toggle-' + module_row + '" style="display:none;">';
        html += '      <td><?php echo $entry_sub_image; ?></td>';
        html += '      <td><select name="category_wall_module[' + module_row + '][sub_image]" class="span3">';
        html += '        <option value="0"><?php echo $text_disabled; ?></option>';
        html += '        <option value="1"><?php echo $text_enabled; ?></option>';
        html += '      </select></td>';
        html += '    </tr>';
        html += '    <tr>';
        html += '      <td><?php echo $entry_image_size; ?></td>';
        html += '      <td colspan="2"><input type="text" name="category_wall_module[' + module_row + '][child_image_width]" value="170" class="span1" /><span class="muted"> x </span><input type="text" name="category_wall_module[' + module_row + '][child_image_height]" value="100" class="span1" /></td>';
        html += '    </tr>';
        html += '    <tr>';
        html += '      <td><?php echo $entry_column; ?></td>';
        html += '      <td><input type="text" name="category_wall_module[' + module_row + '][child_column]" value="4" class="span1" /></td>';
        html += '    </tr>';
        html += '    <tr>';
        html += '      <td><?php echo $entry_child_limit; ?></td>';
        html += '      <td><input type="text" name="category_wall_module[' + module_row + '][child_limit]" value="12" class="span1" /></td>';
        html += '    </tr>';
        html += '    <tr>';
        html += '      <td><?php echo $entry_child2_limit; ?></td>';
        html += '      <td><input type="text" name="category_wall_module[' + module_row + '][child2_limit]" value="12" class="span1" /></td>';
        html += '    </tr>';
        html += '  </table>';
        html += '</div>';
        html += '  <div id="tab-other-' + module_row + '">';
        html += '    <table class="form">';
        html += '      <tr>';
        html += '        <td><?php echo $entry_box_class; ?></td>';
        html += '        <td><input type="text" name="category_wall_module[' + module_row + '][box_class]; ?>]" value="box" class="span3" /></td>';
        html += '      </tr>';
        html += '      <tr>';
        html += '        <td><?php echo $entry_tag; ?></td>';
        html += '        <td><select name="category_wall_module[' + module_row + '][tag]" class="span3">';
        html += '          <option value="div">div</option>';
        html += '          <option value="h1">h1</option>';
        html += '          <option value="h2">h2</option>';
        html += '          <option value="h3">h3</option>';
        html += '          <option value="h4">h4</option>';
        html += '          <option value="h5">h5</option>';
        html += '          <option value="h6">h6</option>';
        html += '        </select></td>';
        html += '      </tr>';
        html += '      <tr>';
        html += '        <td><?php echo $entry_heading_class; ?></td>';
        html += '        <td><input type="text" name="category_wall_module[' + module_row + '][heading_class]; ?>]" value="box-heading" class="span3" /></td>';
        html += '      </tr>';
        html += '      <tr>';
        html += '        <td><?php echo $entry_content_class; ?></td>';
        html += '        <td><input type="text" name="category_wall_module[' + module_row + '][content_class]; ?>]" value="box-content" class="span3" /></td>';
        html += '      </tr>';
        html += '    </table>';
        html += '  </div>';
        
        $('#form').append(html);
        $('#tabs-' + module_row + ' a').tabs();
        $('#items-tabs-' + module_row + ' a').tabs();
        
        $('#module-add').before('<a href="#tab-module-' + module_row + '" id="module-' + module_row + '"><?php echo $tab_module; ?> ' + module_row + '&nbsp; <img src="view/image/delete.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); return false;" /></a>');
        
        $('.vtabs a').tabs();
        $('#module-' + module_row).trigger('click');
        
        module_row++;
    }
//--></script> 
<script type="text/javascript"><!--
    var custom_item_row = <?php echo $custom_item_row; ?>;
    
    function addLink(module_row) {
        html  = '<tbody id="item-row-' + module_row + '-' + custom_item_row + '">';
        html += '  <tr>';
        html += '    <td class="left">';
        <?php foreach ($languages as $language) { ?>
            html += '      <input type="text" name="category_wall_module[' + module_row + '][custom_item][' + custom_item_row + '][item_title][<?php echo $language['language_id']; ?>]" value="" style="margin-bottom: 3px;" class="span2" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
        <?php } ?>
        html += '    </td>';
        html += '    <td class="left">';
        <?php foreach ($languages as $language) { ?>
            html += '      <input type="text" name="category_wall_module[' + module_row + '][custom_item][' + custom_item_row + '][href][<?php echo $language['language_id']; ?>]" value="" style="margin-bottom: 3px;" class="span2" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
        <?php } ?>
        html += '    </td>';
        html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb-' + module_row + '-' + custom_item_row + '" /><input type="hidden" name="category_wall_module[' + module_row + '][custom_item][' + custom_item_row + '][image]" value="" id="image-' + module_row + '-' + custom_item_row + '" /><br /><a onclick="image_upload(\'image-' + module_row + '-' + custom_item_row + '\', \'thumb-' + module_row + '-' + custom_item_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb-' + module_row + '-' + custom_item_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image-' + module_row + '-' + custom_item_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
        html += '    <td class="left"><input type="text" name="category_wall_module[' + module_row + '][custom_item][' + custom_item_row + '][sort_order]" value="" class="span1" /><input type="hidden" name="category_wall_module[' + module_row + '][custom_item][' + custom_item_row + '][ciid]" value="' + module_row + '' + custom_item_row + '" /></td>';
        html += '    <td class="left"><a onclick="$(\'#item-row-' + module_row + '-' + custom_item_row  + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
        html += '  </tr>';
        html += '</tbody>';
        
        $('#items-' + module_row + ' tfoot').before(html);
        
        custom_item_row++;
    }
//--></script> 
<script type="text/javascript"><!--
    function image_upload(field, thumb) {
        $('#dialog').remove();
        
        $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
        
        $('#dialog').dialog({
            title: '<?php echo $text_image_manager; ?>',
            close: function (event, ui) {
                if ($('#' + field).attr('value')) {
                    $.ajax({
                        url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
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
<script type="text/javascript"><!--
    $('.vtabs a').tabs();
//--></script> 
<script type="text/javascript"><!--
    <?php $module_row = 1; ?>
    <?php foreach ($modules as $module) { ?>
        $('#tabs-<?php echo $module_row; ?> a').tabs();
        $('#items-tabs-<?php echo $module_row; ?> a').tabs();
        $('#tab-categories-<?php echo $module_row; ?> input[type=\'radio\']').change(function() { 
            $('#featured-categories-<?php echo $module_row; ?>').toggle();
        });
        <?php $module_row++; ?>
    <?php } ?>
//--></script> 
<?php echo $footer; ?>