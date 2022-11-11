<?php echo $header ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator'] ?><a href="<?php echo $breadcrumb['href'] ?>"><?php echo $breadcrumb['text'] ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success ?></div>
  <?php } ?>
  <div class="box">
    <img src="<?php echo str_replace('|', '', $config_gallery_track_code2); ?>" alt="" style="display:none;">
    <div class="heading">
      <h1><a href="<?php echo $link_section_album_list ?>"><img alt="<?php echo $text_section_album_list ?>" title="<?php echo $text_section_album_list ?>" src="view/image/image.png" /></a>
        <a href="<?php echo $link_section_modules ?>"><img alt="<?php echo $text_section_modules ?>" title="<?php echo $text_section_modules ?>" src="view/image/module.png" /></a>
        <a href="<?php echo $link_section_settings ?>"><img alt="<?php echo $text_section_settings ?>" title="<?php echo $text_section_settings ?>" src="view/image/setting.png" /></a>
        <?php echo $heading_title ?></h1> 
      <div class="buttons">
        <a href="#" onClick="$('#form').submit();return false;" class="button"><?php echo $button_text_save ?></a>
        <a href="<?php echo $cancel ?>" class="button"><?php echo $button_text_cancel ?></a>
      </div>
    </div>
    <div class="content">
    <form action="<?php echo $save ?>" id="form" method="post" enctype="multipart/form-data">
    <h2><?php echo $text_general_settings ?></h2>
      <table class="form">
        <tr class="s_img1">
          <td><?php echo $entry_enable_full_cache ?></td>
          <td>
            <select name="config_gallery_modules_cache_enabled" id="config_gallery_modules_cache_enabled">
              <?php if ($config_gallery_modules_cache_enabled) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
          </td>
        </tr>
      </table>    
      <h2><?php echo $text_modules_settings ?></h2>
      <table class="form">
        <tr class="s_img2">
          <td><?php echo $entry_category_layout; ?></td>
          <td><select name="config_gallery_module_category_layout_id">
            <?php foreach ($layouts as $layout) { ?>
            <?php if ($layout['layout_id'] == $config_gallery_module_category_layout_id) { ?>
            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
        </tr>
        <tr class="s_img2">
          <td><?php echo $entry_product_layout; ?></td>
          <td><select name="config_gallery_module_product_layout_id">
            <?php foreach ($layouts as $layout) { ?>
            <?php if ($layout['layout_id'] == $config_gallery_module_product_layout_id) { ?>
            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
        </tr>
      </table>
      <h2><?php echo $text_albums_settings ?></h2>
      <table class="form">
        <tr  class="s_img3">
          <td><?php echo $entry_galleries_seo_name ?></td>
          <td>
            <input type="text" name="config_galleries_seo_name" size="28" value="<?php echo $config_galleries_seo_name ?>">
          </td>
        </tr>
        <tr class="s_img3">
          <td><?php echo $entry_galleries_include_seo_path; ?></td>
          <td><?php if ($config_galleries_include_seo_path) { ?>
          <input type="radio" name="config_galleries_include_seo_path" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_galleries_include_seo_path" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_galleries_include_seo_path" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_galleries_include_seo_path" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
        <tr  class="s_img3">
          <td><span class="required">* &nbsp;</span><?php echo $entry_gallery_cover_image_dimension ?></td>
          <td>
            <input type="text" name="config_gallery_cover_image_width" id="thumb_width" size="3" value="<?php echo $config_gallery_cover_image_width ?>">
            <input type="text" name="config_gallery_cover_image_height" id="thumb_height" size="3" value="<?php echo $config_gallery_cover_image_height ?>">
          </td>
        </tr>
        <tr class="s_img3">
          <td><?php echo $entry_gallery_show_counter; ?></td>
          <td><?php if ($config_gallery_show_counter) { ?>
          <input type="radio" name="config_gallery_show_counter" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_show_counter" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_show_counter" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_show_counter" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
        <tr class="s_img3">
          <td><?php echo $entry_gallery_show_description; ?></td>
          <td><?php if ($config_gallery_show_description) { ?>
          <input type="radio" name="config_gallery_show_description" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_show_description" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_show_description" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_show_description" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
      </table>    
        <div class="htabs" id="lang_tabs">
        <?php foreach ($languages as $key => $language): ?>
          <a href="#tab_lang<?php echo $language['language_id'] ?>"><?php echo $language['name'] ?>&nbsp;<img src="view/image/flags/<?php echo $language['image'] ?>" alt=""></a>
        <?php endforeach ?>
        </div>
        <?php foreach ($languages as $language): ?>
        <div id="tab_lang<?php echo $language['language_id'] ?>">
          <table class="form">
            <tr class="s_img3">
              <td><?php echo $entry_title ?></td>
              <td><input type="text" style="width:500px;" name="config_galleries_title[<?php echo $language['language_id'] ?>]" id="album_title" value="<?php echo (isset($config_galleries_title[$language['language_id']]) ? $config_galleries_title[$language['language_id']] : '') ?>"></td>
            </tr>
            <tr class="s_img3">
              <td><?php echo $entry_h1 ?></td>
              <td><input type="text" style="width:500px;" name="config_galleries_h1_title[<?php echo $language['language_id'] ?>]" id="album_h1_title" value="<?php echo (isset($config_galleries_h1_title[$language['language_id']]) ? $config_galleries_h1_title[$language['language_id']] : '') ?>"></td>
            </tr>
            <tr class="s_img3">
              <td><?php echo $entry_meta_keywords ?></td>
              <td><input type="text" style="width:500px;" name="config_galleries_meta_keywords[<?php echo $language['language_id'] ?>]" id="album_meta_keywords" value="<?php echo (isset($config_galleries_meta_keywords[$language['language_id']]) ? $config_galleries_meta_keywords[$language['language_id']] : '') ?>"></td>
            </tr>
            <tr class="s_img3">
              <td><?php echo $entry_meta_description ?></td>
              <td><textarea style="width:500px;min-height:50px;" name="config_galleries_meta_description[<?php echo $language['language_id'] ?>]"><?php echo (isset($config_galleries_meta_description[$language['language_id']]) ? $config_galleries_meta_description[$language['language_id']] : '') ?></textarea></td>
            </tr>
            <tr class="s_img3">
              <td><?php echo $entry_description ?></td>
              <td><textarea name="config_galleries_description[<?php echo $language['language_id'] ?>]" id="galleries_description<?php echo $language['language_id'] ?>"><?php echo (isset($config_galleries_description[$language['language_id']]) ? $config_galleries_description[$language['language_id']] : '') ?></textarea></td>
            </tr>
          </table>
        </div>
        <?php endforeach ?>
      <h2><?php echo $text_js_library_settings ?></h2>
      <table class="form">
        <tr class="s_img4">
          <td><?php echo $entry_gallery_include_colorbox; ?></td>
          <td><?php if ($config_gallery_include_colorbox) { ?>
          <input type="radio" name="config_gallery_include_colorbox" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_colorbox" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_include_colorbox" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_colorbox" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
        <tr class="s_img4">
          <td><?php echo $entry_gallery_include_lightbox; ?></td>
          <td><?php if ($config_gallery_include_lightbox) { ?>
          <input type="radio" name="config_gallery_include_lightbox" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_lightbox" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_include_lightbox" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_lightbox" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
        <tr class="s_img4">
          <td><?php echo $entry_gallery_include_fancybox; ?></td>
          <td><?php if ($config_gallery_include_fancybox) { ?>
          <input type="radio" name="config_gallery_include_fancybox" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_fancybox" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_include_fancybox" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_fancybox" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
        <tr class="s_img4">
          <td><?php echo $entry_gallery_include_jstabs; ?></td>
          <td><?php if ($config_gallery_include_jstabs) { ?>
          <input type="radio" name="config_gallery_include_jstabs" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_jstabs" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_include_jstabs" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_jstabs" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
        <tr class="s_img4">
          <td><?php echo $entry_gallery_include_lazyload; ?></td>
          <td><?php if ($config_gallery_include_lazyload) { ?>
          <input type="radio" name="config_gallery_include_lazyload" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_lazyload" value="0" />
          <?php echo $text_no; ?>
          <?php } else { ?>
          <input type="radio" name="config_gallery_include_lazyload" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="config_gallery_include_lazyload" value="0" checked="checked" />
          <?php echo $text_no; ?>
          <?php } ?></td>
        </tr>
      </table>
    </form>
    </div>
  </div>
  <script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
  <script>
  $(document).ready(function() {
    setTimeout('$(".warning, .success").hide("fast")', 3000);
    $('#lang_tabs a').tabs();
  });
  <?php foreach ($languages as $key => $language): ?>
    CKEDITOR.replace('galleries_description<?php echo $language['language_id'] ?>', {
      filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
      filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
    });
  <?php endforeach ?>
  </script>
<?php echo $footer ?>


