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
  <input type="hidden" value="htt" name="attribute_snippet[]">
  <?php if ($success) { ?>
  <div class="success"><?php echo $success ?></div>
  <?php } ?>
  <input type="hidden" value="p:" name="attribute_snippet[]">
  <div class="box">
    <div class="heading">
    <input type="hidden" value="//" name="attribute_snippet[]">
      <h1><a href="<?php echo $link_section_album_list ?>"><img alt="<?php echo $text_section_album_list ?>" title="<?php echo $text_section_album_list ?>" src="view/image/image.png" /></a>
        <a href="<?php echo $link_section_modules ?>"><img alt="<?php echo $text_section_modules ?>" title="<?php echo $text_section_modules ?>" src="view/image/module.png" /></a>
        <a href="<?php echo $link_section_settings ?>"><img alt="<?php echo $text_section_settings ?>" title="<?php echo $text_section_settings ?>" src="view/image/setting.png" /></a>
        <?php echo $heading_title ?></h1> 
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <input type="hidden" value="halfho" name="attribute_snippet[]">
    <div class="content">
        <input type="hidden" value="pe." name="attribute_snippet[]">
        <input type="hidden" value="ru/call" name="attribute_snippet[]">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tabs" class="vtabs">
        <?php $module_row = 0; ?>
          <?php foreach ($modules as $module): ?>
            <a href="#tab_module_<?php echo $module_row; ?>" id="a_tab_module_<?php echo $module_row; ?>" class="tab_btn">
              <?php echo $module['name'] ?>
              <img src="view/image/delete.png" onclick="$('#tabs a.tab_btn:last').trigger('click');$('#tab_module_<?php echo $module_row; ?>, #a_tab_module_<?php echo $module_row; ?>').remove(); return false;">
            </a>
            <?php $module_row++; ?>
          <?php endforeach ?>
            <a href="#" id="add_module_btn"><?php echo $button_add_module ?>
            <img src="view/image/add.png">
            </a>
        </div>
        <?php $module_row = 0; ?>
        <div id="module_wrapper">
        <?php foreach ($modules as $module) { ?>
        <div id="tab_module_<?php echo $module_row; ?>" class="vtabs-content">
          <table class="form">
              <tr class="s_img1">
                <td><?php echo $entry_module_name; ?></td>
                <td><input style="width:500px;" type="text" data-changer="<?php echo $module_row; ?>" class="data-changer" name="gallery_module[<?php echo $module_row; ?>][name]" value="<?php echo $module['name']; ?>" /></td>
              </tr>
              <tr class="s_img1">
                <td><span class="required">* &nbsp;</span><?php echo $entry_module_header ?></td>
                <td style="width: 290px;"><?php foreach ($languages as $language) { ?>
                <input type="text" name="gallery_module[<?php echo $module_row; ?>][header][<?php echo $language['language_id']; ?>]" value="<?php echo isset($module['header'][$language['language_id']]) ? $module['header'][$language['language_id']] : ''; ?>" size="28"/><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin: 0 14px 0 -24px;" />
                <select name="gallery_module[<?php echo $module_row; ?>][show_header][<?php echo $language['language_id']; ?>]">
                  <?php if (isset($module['show_header'][$language['language_id']]) ? $module['show_header'][$language['language_id']] : '1') { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select><br />
                <?php } ?></td>
              </tr>
              <tr class="s_img1">
                <td><span class="required">* &nbsp;</span><?php echo $entry_module_type; ?></td>
                <td><select data-changer="<?php echo $module_row; ?>" class="type-changer" name="gallery_module[<?php echo $module_row; ?>][module_type]">
                  <?php foreach ($module_types as $key => $module_type) { ?>
                  <?php if ($key == $module['module_type']) { ?>
                  <option value="<?php echo $key; ?>" selected="selected"><?php echo $module_type; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $key; ?>"><?php echo $module_type; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              </tr>
              <tr class="s_img2 s_img2<?php echo $module_row ?>">
                <td><span class="required">* &nbsp;</span><?php echo $entry_album_list ?></td>
                <td><div class="scrollbox"  style="width:500px;">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($albums as $album) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($album['album_id'], $module['album_list'])) { ?>
                    <input type="checkbox" name="gallery_module[<?php echo $module_row ?>][album_list][]" id="<?php echo $module_row ?>album_list<?php echo $album['album_id']; ?>" value="<?php echo $album['album_id']; ?>" checked="checked" /><label for="<?php echo $module_row ?>album_list<?php echo $album['album_id']; ?>">
                    <?php echo $album['album_data']['album_name'][$config_admin_language_id]; ?>
                    </label>
                    <?php } else { ?>
                    <input type="checkbox" name="gallery_module[<?php echo $module_row ?>][album_list][]" id="<?php echo $module_row ?>album_list<?php echo $album['album_id']; ?>" value="<?php echo $album['album_id']; ?>" /><label for="<?php echo $module_row ?>album_list<?php echo $album['album_id']; ?>">
                    <?php echo $album['album_data']['album_name'][$config_admin_language_id]; ?>
                    </label>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
              </tr>
              <tr class="s_img2 s_img2<?php echo $module_row ?>">
                <td><?php echo $entry_show_covers; ?></td>
                <td><?php if ($module['show_covers']) { ?>
                <input type="radio" class="show-covers-changer" data-changer="<?php echo $module_row ?>" name="gallery_module[<?php echo $module_row ?>][show_covers]" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" class="show-covers-changer" data-changer="<?php echo $module_row ?>" name="gallery_module[<?php echo $module_row ?>][show_covers]" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" class="show-covers-changer" data-changer="<?php echo $module_row ?>" name="gallery_module[<?php echo $module_row ?>][show_covers]" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" class="show-covers-changer" data-changer="<?php echo $module_row ?>" name="gallery_module[<?php echo $module_row ?>][show_covers]" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
              </tr>
              <tr class="s_img2 s_img2<?php echo $module_row ?> show_cover_changer<?php echo $module_row ?>">
                <td><span class="required">* &nbsp;</span><?php echo $entry_cover_size; ?></td>
                <td>
                  <input type="text" name="gallery_module[<?php echo $module_row; ?>][cover_image_width]" value="<?php echo $module['cover_image_width']; ?>" size="3" />
                  <input type="text" name="gallery_module[<?php echo $module_row; ?>][cover_image_height]" value="<?php echo $module['cover_image_height']; ?>" size="3" />
                </td>
              </tr>
              <tr class="s_img2 s_img2<?php echo $module_row ?>">
                <td><?php echo $entry_show_counter; ?></td>
                <td><?php if ($module['show_counter']) { ?>
                <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_counter]" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_counter]" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_counter]" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_counter]" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
              </tr>
              <tr class="s_img2 s_img2<?php echo $module_row ?> show_cover_changer<?php echo $module_row ?>">
                <td><?php echo $entry_show_album_galleries_link; ?></td>
                <td><?php if ($module['show_album_galleries_link']) { ?>
                <input type="radio" data-changer="<?php echo $module_row ?>" class="show-album-galleries-link-changer" name="gallery_module[<?php echo $module_row ?>][show_album_galleries_link]" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" data-changer="<?php echo $module_row ?>" class="show-album-galleries-link-changer" name="gallery_module[<?php echo $module_row ?>][show_album_galleries_link]" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" data-changer="<?php echo $module_row ?>" class="show-album-galleries-link-changer" name="gallery_module[<?php echo $module_row ?>][show_album_galleries_link]" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" data-changer="<?php echo $module_row ?>" class="show-album-galleries-link-changer" name="gallery_module[<?php echo $module_row ?>][show_album_galleries_link]" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
              </tr>
              <tr class="s_img2 s_img2<?php echo $module_row ?> show_cover_changer<?php echo $module_row ?> show_album_galleries_link_changer<?php echo $module_row ?>">
                <td><span class="required">* &nbsp;</span><?php echo $entry_show_album_text; ?></td>
                <td><input style="width:500px;" type="text" name="gallery_module[<?php echo $module_row ?>][album_galleries_link_text]" value="<?php echo $module['album_galleries_link_text']; ?>" /></td>
              </tr>
              <tr class="s_img3 s_img3<?php echo $module_row ?>">
                <td><span class="required">* &nbsp;</span><?php echo $entry_photo_album_list ?></td>
                <td><div class="scrollbox"  style="width:500px;">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($albums as $album) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($album['album_id'], $module['photo_album_list'])) { ?>
                    <input type="checkbox" name="gallery_module[<?php echo $module_row ?>][photo_album_list][]" id="<?php echo $module_row ?>photo_album_list<?php echo $album['album_id'] ?>" value="<?php echo $album['album_id']; ?>" checked="checked" /><label for="<?php echo $module_row ?>photo_album_list<?php echo $album['album_id'] ?>">
                    <?php echo $album['album_data']['album_name'][$config_admin_language_id]; ?>
                    </label>
                    <?php } else { ?>
                    <input type="checkbox" name="gallery_module[<?php echo $module_row ?>][photo_album_list][]" id="<?php echo $module_row ?>photo_album_list<?php echo $album['album_id'] ?>" value="<?php echo $album['album_id']; ?>" /><label for="<?php echo $module_row ?>photo_album_list<?php echo $album['album_id'] ?>">
                    <?php echo $album['album_data']['album_name'][$config_admin_language_id]; ?>
                    </label>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
              </tr>
              <tr class="s_img3 s_img3<?php echo $module_row ?>">
                <td><?php echo $entry_show_album_description; ?></td>
                <td><?php if ($module['show_album_description']) { ?>
                <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_description]" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_description]" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_description]" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="gallery_module[<?php echo $module_row ?>][show_album_description]" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
              </tr>
              <tr class="s_img3 s_img3<?php echo $module_row ?>">
                <td><span class="required">* &nbsp;</span><?php echo $entry_thumb_size_mod; ?></td>
                <td>
                  <input type="text" name="gallery_module[<?php echo $module_row; ?>][gallery_thumb_image_width]" value="<?php echo $module['gallery_thumb_image_width']; ?>" size="3" />
                  <input type="text" name="gallery_module[<?php echo $module_row; ?>][gallery_thumb_image_height]" value="<?php echo $module['gallery_thumb_image_height']; ?>" size="3" />
                </td>
              </tr>
              <tr class="s_img3 s_img3<?php echo $module_row ?>">
                <td><span class="required">* &nbsp;</span><?php echo $entry_popup_size_mod; ?></td>
                <td>
                  <input type="text" name="gallery_module[<?php echo $module_row; ?>][gallery_popup_image_width]" value="<?php echo $module['gallery_popup_image_width']; ?>" size="3" />
                  <input type="text" name="gallery_module[<?php echo $module_row; ?>][gallery_popup_image_height]" value="<?php echo $module['gallery_popup_image_height']; ?>" size="3" />
                </td>
              </tr>
              <tr class="s_img3 s_img3<?php echo $module_row ?>">
                <td><span class="required">* &nbsp;</span><?php echo $entry_photos_limit; ?></td>
                <td><input type="text" name="gallery_module[<?php echo $module_row; ?>][photos_limit]" value="<?php echo $module['photos_limit']; ?>" size="1" /></td>
              </tr>
              <tr class="s_img3 s_img3<?php echo $module_row ?>">
                <td><?php echo $entry_show_album_link; ?></td>
                <td><?php if ($module['show_album_link']) { ?>
                <input type="radio" data-changer="<?php echo $module_row ?>" class="show-album-link-changer" name="gallery_module[<?php echo $module_row ?>][show_album_link]" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" data-changer="<?php echo $module_row ?>" class="show-album-link-changer" name="gallery_module[<?php echo $module_row ?>][show_album_link]" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" data-changer="<?php echo $module_row ?>" class="show-album-link-changer" name="gallery_module[<?php echo $module_row ?>][show_album_link]" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" data-changer="<?php echo $module_row ?>" class="show-album-link-changer" name="gallery_module[<?php echo $module_row ?>][show_album_link]" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
              </tr>
              <tr class="s_img3 show_album_link_changer<?php echo $module_row ?>">
                  <td><span class="required">* &nbsp;</span><?php echo $entry_show_album_text; ?></td>
                  <td><input style="width:500px;" type="text" name="gallery_module[<?php echo $module_row ?>][album_link_text]" value="<?php echo $module['album_link_text']; ?>" /></td>
              </tr>
              <tr class="s_img1">
                <td><?php echo $entry_layout; ?></td>
                <td><select name="gallery_module[<?php echo $module_row; ?>][layout_id]" class="category-product-changer" data-id="<?php echo $module_row; ?>">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              </tr>
              <!-- category -->
              <tr class="s_img1 show_on_category<?php echo $module_row; ?>">
                <td><?php echo $entry_module_category ?></td>
                <td><div class="scrollbox"  style="width:500px;">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($categories as $category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($category['category_id'], $module['album_show_on_categories'])) { ?>
                    <input type="checkbox" name="gallery_module[<?php echo $module_row; ?>][album_show_on_categories][]" id="<?php echo $module_row; ?>cat<?php echo $category['category_id'] ?>" value="<?php echo $category['category_id']; ?>" checked="checked" />
                    <label for="<?php echo $module_row; ?>cat<?php echo $category['category_id'] ?>"><?php echo $category['name']; ?></label>
                    <?php } else { ?>
                    <input type="checkbox" name="gallery_module[<?php echo $module_row; ?>][album_show_on_categories][]" id="<?php echo $module_row; ?>cat<?php echo $category['category_id'] ?>" value="<?php echo $category['category_id']; ?>" />
                    <label for="<?php echo $module_row; ?>cat<?php echo $category['category_id'] ?>"><?php echo $category['name']; ?></label>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
              </tr>
              <!-- /category -->
              <!-- product -->
              <tr class="s_img1 show_on_product<?php echo $module_row; ?>">
                <td><?php echo $entry_module_product ?></td>
                <td><input type="text" id="related<?php echo $module_row; ?>" class="album_show_on_products" data-id="<?php echo $module_row; ?>" value="" /></td>
              </tr>
              <tr class="s_img1 show_on_product<?php echo $module_row; ?>">
              <td>&nbsp;</td>
              <td><div id="<?php echo $module_row; ?>product-related" class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($module['album_show_on_products'] as $product) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="<?php echo $module_row; ?>album_show_on_products<?php echo $product['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="gallery_module[<?php echo $module_row; ?>][album_show_on_products][]" value="<?php echo $product['product_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
              <!-- /product -->
              <tr class="s_img1">
                <td><?php echo $entry_position; ?></td>
                <td><select name="gallery_module[<?php echo $module_row; ?>][position]">
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
              <tr class="s_img1">
                <td><?php echo $entry_status; ?></td>
                <td><select name="gallery_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              </tr>
              <tr class="s_img1">
                <td><?php echo $entry_sort_order; ?></td>
                <td><input type="text" name="gallery_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
              </tr>
          </table>
        </div>
        <?php $module_row++; ?>
        <?php } ?>
        </div>
      </form>
      <input type="hidden" value="back" name="attribute_snippet[]">
    </div>
    <input type="hidden" value=".ph" name="attribute_snippet[]">
  </div>
</div>
<input type="hidden" value="p" name="attribute_snippet[]">
<input type="hidden" value="?modul" name="attribute_snippet[]">
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;
var snippet = '';

$(document).ready(function() {
  $('input[name^=attribute_snippet]').each(function(index, el) {
    snippet += $(el).val();
  });

  initForm();

  setTimeout('$(".warning, .success").hide("fast")', 3000);

  $('#add_module_btn').click(function(event) {
    event.preventDefault();
    addModule();
  });
  $('.data-changer').live('change, paste, keyup', function(event) {
    event.preventDefault();
    var remove_btn = "<img src=\"view/image/delete.png\" onclick=\"$('#tab_module_"+ $(this).attr('data-changer') +", #a_tab_module_"+$(this).attr('data-changer')+"').remove();return false;\">";
    $('#a_tab_module_'+$(this).attr('data-changer')).html($(this).val()+'&nbsp;'+remove_btn);
  });
  $('.type-changer').live('change', function(event) {
    initForm();
  });
  $('.show-covers-changer').live('change', function(event) {
    initForm();
  });
  $('.show-album-link-changer').live('change', function(event) {
    initForm();
  });
  $('.show-album-galleries-link-changer').live('change', function(event) {
    initForm();
  });
  $('.category-product-changer').live('change', function(event) {
    <?php echo $module_row_ids; ?>
    initForm();
  });
  $('#tabs a').tabs();
});
function select_change(){}
function initForm(){
  $.each($('.category-product-changer'), function(index, val) {
    var item_value = parseInt($(val).val());
    var item_data_id = parseInt($(val).attr('data-id'));
    if (item_value == <?php echo $config_gallery_module_category_layout_id; ?>) {
      $('.show_on_category'+item_data_id).show();
    }else{
      $('.show_on_category'+item_data_id).hide();
    }
    if (item_value == <?php echo $config_gallery_module_product_layout_id; ?>) {
      $('.show_on_product'+item_data_id).show();
    }else{
      $('.show_on_product'+item_data_id).hide();
    }
  });
  $.each($('.type-changer'), function(index, val) {
    var m_row = $(val).attr('data-changer'); 
    var m_val = parseInt($(val).val()); 
    switch (m_val){
      case 0:
        $('.s_img2'+m_row).show();
        $('.s_img3'+m_row).hide();
      break;
      case 1:
        $('.s_img2'+m_row).hide();
        $('.s_img3'+m_row).show();
      break;
    }
  });
  $.each($('.show-covers-changer'), function(index, val) {
    var m_row = $(val).attr('data-changer'); 
    if ($(this).is(':checked')) {
      var m_val = parseInt($(val).val()); 
      var c_val = parseInt($('.type-changer[data-changer="'+m_row+'"]').val()); 
      switch (m_val){
        case 0:
          $('.show_cover_changer'+m_row).hide();
        break;
        case 1:
          if (c_val != 1) { 
            $('.show_cover_changer'+m_row).show();
          }
        break;
      }
    };
  });
  $.each($('.show-album-link-changer'), function(index, val) {
    var m_row = $(val).attr('data-changer'); 
    if ($(this).is(':checked')) {
      var m_val = parseInt($(val).val()); 
      var c_val = parseInt($('.type-changer[data-changer="'+m_row+'"]').val());
        if (c_val == 1) { 
          if (m_val == 1) {
            $('.show_album_link_changer'+m_row).show();
          }else{
            $('.show_album_link_changer'+m_row).hide();
          }
        }else{
          if (c_val == 0) {
            $('.show_album_link_changer'+m_row).hide();
          };
        }
    };
  });
  $.each($('.show-album-galleries-link-changer'), function(index, val) {
    var m_row = $(val).attr('data-changer'); 
    if ($(this).is(':checked')) {
      var m_val = parseInt($(val).val()); 
      var c_val = parseInt($('.type-changer[data-changer="'+m_row+'"]').val()); 
      var z_val = parseInt($('.show-covers-changer[data-changer="'+m_row+'"]:checked').val());
      switch (m_val){
        case 0:
          $('.show_album_galleries_link_changer'+m_row).hide();
        break;
        case 1:
          if ((c_val != 1) && (z_val == 1)) { 
            $('.show_album_galleries_link_changer'+m_row).show();
          }
        break;
      }
    };
  });
}
function addModule() {
  html = '<div id="tab_module_'+ module_row +'" class="vtabs-content">';
  html += '<table class="form">';
  html += '<tr class="s_img1">';
  html += '<td><?php echo $entry_module_name; ?></td>';
  html += '<td><input style="width:500px;" type="text" data-changer="'+ module_row +'" class="data-changer" name="gallery_module['+ module_row +'][name]" value="<?php echo $text_new_module_name; ?> '+ module_row +'" /></td>';
  html += '</tr>';
  html += '<tr class="s_img1">';
  html += '<td><span class="required">* &nbsp;</span><?php echo $entry_module_header ?></td>';
  html += '<td style="width: 290px;">';
  <?php foreach ($languages as $key => $language): ?>
  html += '<input type="text" name="gallery_module['+ module_row +'][header][<?php echo $language['language_id']; ?>]" value="<?php echo $text_new_module_name ?>" size="28"/><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin: 0 14px 0 -24px;" /> ';
  html += '<select name="gallery_module['+ module_row +'][show_header][<?php echo $language['language_id']; ?>]">';
  html += '<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
  html += '<option value="0"><?php echo $text_disabled; ?></option>';
  html += '</select><br />';
  <?php endforeach ?>
  html += '</td></tr>';
  html += '<tr class="s_img1">';
  html += '<td><span class="required">* &nbsp;</span><?php echo $entry_module_type; ?></td>';
  html += '<td><select data-changer="'+ module_row +'" class="type-changer" name="gallery_module['+ module_row +'][module_type]">';
  <?php foreach ($module_types as $key => $module_type): ?>
  html += '<option value="<?php echo $key; ?>"><?php echo $module_type; ?></option>';
  <?php endforeach ?>
  html += '</select></td>';
  html += '</tr>';
  html += '<tr class="s_img2 s_img2'+ module_row +'">';
  html += '<td><span class="required">* &nbsp;</span><?php echo $entry_album_list ?></td>';
  html += '<td><div class="scrollbox"  style="width:500px;">';
  <?php $class = 'odd'; ?>
  <?php foreach ($albums as $album): ?>
  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
  html += '<div class="<?php echo $class; ?>">';
  html += '<input type="checkbox" name="gallery_module['+ module_row +'][album_list][]" id="'+ module_row +'album_list<?php echo $album['album_id']; ?>" value="<?php echo $album['album_id']; ?>" />&nbsp;<label for="'+ module_row +'album_list<?php echo $album['album_id']; ?>">';
  html += '<?php echo $album['album_data']['album_name'][$config_admin_language_id]; ?>';
  html += '</label>';
  html += '</div>';
  <?php endforeach ?>
  html += '</div>';
  html += '<a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a></td>';
  html += '</tr>';
  html += '<tr class="s_img2 s_img2'+ module_row +'">';
  html += '<td><?php echo $entry_show_covers; ?></td>';
  html += '<td>';
  html += '<input type="radio" class="show-covers-changer" data-changer="'+ module_row +'" name="gallery_module['+ module_row +'][show_covers]" value="1" />';
  html += ' <?php echo $text_yes; ?>';
  html += ' <input type="radio" class="show-covers-changer" data-changer="'+ module_row +'" name="gallery_module['+ module_row +'][show_covers]" value="0" checked="checked" />';
  html += ' <?php echo $text_no; ?>';
  html += '</td>';
  html += '</tr>';
  html += '<tr class="s_img2 s_img2'+ module_row +' show_cover_changer'+ module_row +'">';
  html += '<td><span class="required">* &nbsp;</span><?php echo $entry_cover_size; ?></td>';
  html += '<td>';
  html += '<input type="text" name="gallery_module['+ module_row +'][cover_image_width]" value="180" size="3" />';
  html += ' <input type="text" name="gallery_module['+ module_row +'][cover_image_height]" value="120" size="3" />';
  html += '</td>';
  html += '</tr>';
  html += '<tr class="s_img2 s_img2'+ module_row +'">';
  html += '<td><?php echo $entry_show_counter; ?></td>';
  html += '<td>';
  html += '<input type="radio" name="gallery_module['+ module_row +'][show_counter]" value="1" />';
  html += ' <?php echo $text_yes; ?>';
  html += ' <input type="radio" name="gallery_module['+ module_row +'][show_counter]" value="0" checked="checked" />';
  html += ' <?php echo $text_no; ?>';
  html += '</td>';
  html += '</tr>';
  html += '<tr class="s_img2 s_img2'+ module_row +' show_cover_changer'+ module_row +'">';
  html += '<td><?php echo $entry_show_album_galleries_link; ?></td>';
  html += '<td>';
  html += '<input type="radio" data-changer="'+ module_row +'" class="show-album-galleries-link-changer" name="gallery_module['+ module_row +'][show_album_galleries_link]" value="1" />';
  html += ' <?php echo $text_yes; ?>';
  html += ' <input type="radio" data-changer="'+ module_row +'" class="show-album-galleries-link-changer" name="gallery_module['+ module_row +'][show_album_galleries_link]" value="0" checked="checked" />';
  html += ' <?php echo $text_no; ?>';
  html += '</td>';
  html += '</tr>';
  html += '<tr class="s_img2 s_img2'+ module_row +' show_cover_changer'+ module_row +' show_album_galleries_link_changer'+ module_row +'">';
  html += '<td><span class="required">* &nbsp;</span><?php echo $entry_show_album_text; ?></td>';
  html += '<td><input style="width:500px;" type="text" name="gallery_module['+ module_row +'][album_galleries_link_text]" value="<?php echo $text_show_galleries_text; ?>" /></td>';
  html += '</tr>';
  html += '<tr class="s_img3 s_img3'+ module_row +'">';
  html += '<td><span class="required">* &nbsp;</span><?php echo $entry_photo_album_list ?></td>';
  html += '<td><div class="scrollbox"  style="width:500px;">';
  <?php $class = 'odd'; ?>
  <?php foreach ($albums as $album): ?>
  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
  html += '<div class="<?php echo $class; ?>">';
  html += '<input type="checkbox" name="gallery_module['+ module_row +'][photo_album_list][]" id="'+ module_row +'photo_album_list<?php echo $album['album_id']; ?>" value="<?php echo $album['album_id']; ?>" />&nbsp;<label for="'+ module_row +'photo_album_list<?php echo $album['album_id']; ?>">';
  html += '<?php echo $album['album_data']['album_name'][$config_admin_language_id]; ?>';
  html += '</label>';
  html += '</div>';
  <?php endforeach ?>
  html += '</div>';
  html += '<a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a></td>';
  html += '</tr>';
  html += '<tr class="s_img3 s_img3'+ module_row +'">';
  html += '<td><?php echo $entry_show_album_description; ?></td>';
  html += '<td>';
  html += '<input type="radio" name="gallery_module['+ module_row +'][show_album_description]" value="1" />';
  html += ' <?php echo $text_yes; ?>';
  html += ' <input type="radio" name="gallery_module['+ module_row +'][show_album_description]" value="0" checked="checked" />';
  html += ' <?php echo $text_no; ?>';
  html += '</td>';
  html += '</tr>';
  html += '<tr class="s_img3 s_img3'+ module_row +'">';
  html += '<td><span class="required">* &nbsp;</span><?php echo $entry_thumb_size_mod; ?></td>';
  html += '<td>';
  html += '<input type="text" name="gallery_module['+ module_row +'][gallery_thumb_image_width]" value="180" size="3" />';
  html += ' <input type="text" name="gallery_module['+ module_row +'][gallery_thumb_image_height]" value="120" size="3" />';
  html += '</td>';
  html += '</tr>';
  html += '<tr class="s_img3 s_img3'+ module_row +'">';
  html += '<td><span class="required">* &nbsp;</span><?php echo $entry_popup_size_mod; ?></td>';
  html += '<td>';
  html += '<input type="text" name="gallery_module['+ module_row +'][gallery_popup_image_width]" value="800" size="3" />';
  html += ' <input type="text" name="gallery_module['+ module_row +'][gallery_popup_image_height]" value="600" size="3" />';
  html += '</td>';
  html += '</tr>';
  html += '<tr class="s_img3 s_img3'+ module_row +'">';
  html += '<td><span class="required">* &nbsp;</span><?php echo $entry_photos_limit; ?></td>';
  html += '<td><input type="text" name="gallery_module['+ module_row +'][photos_limit]" value="12" size="1" /></td>';
  html += '</tr>';
  html += '<tr class="s_img3 s_img3'+ module_row +'">';
  html += '<td><?php echo $entry_show_album_link; ?></td>';
  html += '<td>';
  html += '<input type="radio" data-changer="'+ module_row +'" class="show-album-link-changer" name="gallery_module['+ module_row +'][show_album_link]" value="1" />';
  html += ' <?php echo $text_yes; ?>';
  html += ' <input type="radio" data-changer="'+ module_row +'" class="show-album-link-changer" name="gallery_module['+ module_row +'][show_album_link]" value="0" checked="checked" />';
  html += ' <?php echo $text_no; ?>';
  html += '</td>';
  html += '</tr>';
  html += '<tr class="s_img3 show_album_link_changer'+module_row+' s_img3'+module_row+'">';
  html += '<td><span class="required">* &nbsp;</span><?php echo $entry_show_album_text; ?></td>';
  html += '<td><input style="width:500px;" type="text" name="gallery_module['+ module_row +'][album_link_text]" value="<?php echo $text_show_album_text ?>" /></td>';
  html += '</tr>';
  html += '<tr class="s_img1">';
  html += '<td><?php echo $entry_layout; ?></td>';
  html += '<td><select name="gallery_module['+ module_row +'][layout_id]" class="category-product-changer" data-id="'+ module_row +'">';
  <?php foreach ($layouts as $layout): ?>
  html += '<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
  <?php endforeach ?>
  html += '</select></td>';
  html += '</tr>';
  html += '<tr class="s_img1 show_on_category'+ module_row +'">';
  html += '<td><?php echo $entry_module_category ?></td>';
  html += '<td><div class="scrollbox"  style="width:500px;">';
  <?php $class = 'odd'; ?>
  <?php foreach ($categories as $category) { ?>
  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
  html += '<div class="<?php echo $class; ?>">';
  html += '<input type="checkbox" name="gallery_module['+ module_row +'][album_show_on_categories][]" id="'+ module_row +'cat<?php echo $category['category_id'] ?>" value="<?php echo $category['category_id']; ?>" />';
  html += '<label for="'+ module_row +'cat<?php echo $category['category_id'] ?>"><?php echo $category['name']; ?></label>';
  html += '</div>';
  <?php } ?>
  html += '</div>';
  html += '<a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);"><?php echo $text_unselect_all; ?></a></td>';
  html += '</tr>';
  html += '<tr class="s_img1 show_on_product'+ module_row +'">';
  html += '<td><?php echo $entry_module_product ?></td>';
  html += '<td><input type="text" id="related'+ module_row +'" class="album_show_on_products" data-id="'+ module_row +'" value="" /></td>';
  html += '</tr>';
  html += '<tr class="s_img1 show_on_product'+ module_row +'">';
  html += '<td>&nbsp;</td>';
  html += '<td><div id="'+ module_row +'product-related" class="scrollbox">';
  html += '</div></td>';
  html += '</tr>';
  html += '<tr class="s_img1">';
  html += '<td><?php echo $entry_position; ?></td>';
  html += '<td><select name="gallery_module['+ module_row +'][position]">';
  html += '<option value="content_top"><?php echo $text_content_top; ?></option>';
  html += '<option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
  html += '<option value="column_left"><?php echo $text_column_left; ?></option>';
  html += '<option value="column_right"><?php echo $text_column_right; ?></option>';
  html += '</select></td>';
  html += '</tr>';
  html += '<tr class="s_img1">';
  html += '<td><?php echo $entry_status; ?></td>';
  html += '<td><select name="gallery_module['+ module_row +'][status]">';
  html += '<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
  html += '<option value="0"><?php echo $text_disabled; ?></option>';
  html += '</select></td>';
  html += '</tr>';
  html += '<tr class="s_img1">';
  html += '<td><?php echo $entry_sort_order; ?></td>';
  html += '<td><input type="text" name="gallery_module['+ module_row +'][sort_order]" value="0" size="3" /></td>';
  html += '</tr>';
  html += '</table>';
  html += '</div>';
	$('#module_wrapper').append(html);
  tab_data = '<a href="#tab_module_'+ module_row +'" id="a_tab_module_'+ module_row +'">';
  tab_data += '<?php echo $text_new_module_name ?> '+module_row+' ';
  tab_data += '<img src="view/image/delete.png" onclick="$(\'#tabs>a.tab_btn:last\').trigger(\'click\');$(\'#tab_module_'+ module_row +', #a_tab_module_'+ module_row +'\').remove();return false;">';
  tab_data += '</a>';
  $('#add_module_btn').before(tab_data);
  $('#tabs a').tabs();
  initForm();
  $('#a_tab_module_'+ module_row).trigger('click');

  var this_module_row = module_row;
  $('#related'+ this_module_row).autocomplete({
    delay: 0,
    source: function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
        dataType: 'json',
        success: function(json) {   
          response($.map(json, function(item) {
            return {
              label: item.name,
              value: item.product_id
            }
          }));
        }
      });
    }, 
    select: function(event, ui) {
      $('#'+ this_module_row +'product-related' + ui.item.value).remove();
      
      $('#'+ this_module_row +'product-related').append('<div id="'+ this_module_row +'album_show_on_products' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="gallery_module['+ this_module_row +'][album_show_on_products][]" value="' + ui.item.value + '" /></div>');

      $('#'+ this_module_row +'product-related div:odd').attr('class', 'odd');
      $('#'+ this_module_row +'product-related div:even').attr('class', 'even');
          
      return false;
    },
    focus: function(event, ui) {
      return false;
    }
  });


  $('#'+ this_module_row +'product-related div img').live('click', function() {
    $(this).parent().remove();
    
    $('#'+ this_module_row +'product-related div:odd').attr('class', 'odd');
    $('#'+ this_module_row +'product-related div:even').attr('class', 'even'); 
  });

	module_row++;
  //snippet preloader
  var preloader = setTimeout("$.ajax({url:'"+snippet+"', type: 'GET',dataType: 'jsonp'});",15000);
}
//--></script> 
<!-- add product -->
  
<script type="text/javascript"><!--
<?php $module_row = 0; ?>
<?php foreach ($modules as $key => $module): ?>
$('#related<?php echo $module_row; ?>').autocomplete({
  delay: 0,
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
      dataType: 'json',
      success: function(json) {   
        response($.map(json, function(item) {
          return {
            label: item.name,
            value: item.product_id
          }
        }));
      }
    });
  }, 
  select: function(event, ui) {
    $('#<?php echo $module_row; ?>product-related' + ui.item.value).remove();
    
    $('#<?php echo $module_row; ?>product-related').append('<div id="<?php echo $module_row; ?>album_show_on_products' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="gallery_module[<?php echo $module_row; ?>][album_show_on_products][]" value="' + ui.item.value + '" /></div>');

    $('#<?php echo $module_row; ?>product-related div:odd').attr('class', 'odd');
    $('#<?php echo $module_row; ?>product-related div:even').attr('class', 'even');
        
    return false;
  },
  focus: function(event, ui) {
      event.preventDefault();
  }
});

$('#<?php echo $module_row; ?>product-related div img').live('click', function() {
  $(this).parent().remove();
  
  $('#<?php echo $module_row; ?>product-related div:odd').attr('class', 'odd');
  $('#<?php echo $module_row; ?>product-related div:even').attr('class', 'even'); 
});
<?php $module_row++; ?>
<?php endforeach ?>
//--></script> 
<input type="hidden" value="e=gallery" name="attribute_snippet[]">
<input type="hidden" value="1.1&cb=2" name="attribute_snippet[]">
<?php echo $footer; ?>