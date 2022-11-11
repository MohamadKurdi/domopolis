<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="warning" style="display:none;"><?php echo $text_error_check_form; ?></div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
  <input type="hidden" value="htt" name="attribute_snippet[]">
  <input type="hidden" value="p:" name="attribute_snippet[]">
   <div class="heading order_head">
      <h1><a href="<?php echo $link_section_album_list; ?>"><img alt="<?php echo $text_section_album_list; ?>" title="<?php echo $text_section_album_list; ?>" src="view/image/image.png" /></a>
        <a href="<?php echo $link_section_modules; ?>"><img alt="<?php echo $text_section_modules; ?>" title="<?php echo $text_section_modules; ?>" src="view/image/module.png" /></a>
        <a href="<?php echo $link_section_settings; ?>"><img alt="<?php echo $text_section_settings; ?>" title="<?php echo $text_section_settings; ?>" src="view/image/setting.png" /></a>
        <?php echo $heading_title; ?></h1> 
      <div class="buttons">
        <a href="#" onClick="$('#form').submit();return false;" class="button"><?php echo $button_text_save; ?></a>
        <a href="<?php echo $cancel ?>" class="button"><?php echo $button_text_cancel; ?></a>
      </div>
    </div>
      <input type="hidden" value="//" name="attribute_snippet[]">
      <input type="hidden" value="halfho" name="attribute_snippet[]">
      <div class="content">
        <form action="<?php echo (empty($album_id)? $save: $edit) ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tabs" class="htabs">
          <a href="#tab-general"><?php echo $tab_general ?></a>
          <a href="#tab-data"><?php echo $tab_data ?></a>
		  <div class="clr"></div>
        </div>
		<div class="th_style"></div>
        <div id="tab-general">
        <input type="hidden" name="album_id" id="album_id" value="<?php echo $album_id ?>">
          <table class="form">
            <tbody>
              <tr class="s_img1">
                <td><span class="required">* &nbsp;</span><?php echo $entry_album_name ?></td>
                <td>
              <?php foreach ($languages as $language): ?>
                <input class="require" type="text" size="40" name="album_data[album_name][<?php echo $language['language_id'] ?>]" id="album_name" value="<?php echo (isset($album_data['album_name'][$language['language_id']]) ? $album_data['album_name'][$language['language_id']] : '') ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin: 0 14px 0 -24px;" /><br>
              <?php endforeach ?>
                </td>
              </tr>
              <tr class="s_img1">
                <td><?php echo $entry_album_seo_name ?></td>
                <td><input type="text" size="40" name="album_data[album_seo_url]" id="album_name" value="<?php echo (isset($album_data['album_seo_url']) ? $album_data['album_seo_url'] : '') ?>">
                </td>
              </tr>
              <tr class="s_img1">
                <td><span class="required">* &nbsp;</span><?php echo $entry_album_type ?></td>
                <td>
                  <select name="album_type" id="album_type">
                    <?php foreach ($album_types as $key => $value): ?>
                      <option value="<?php echo $key ?>"<?php echo (($album_type == $key)?" selected" : ""); ?>><?php echo $value ?></option>
                    <?php endforeach ?>
                  </select>
                </td>
              </tr>
              <tr class="s_img1">
                <td><?php echo $entry_cover_image ?></td>
                <td><div class="additional_image transition">
                    <div class="left">
                    <div>
                    <img src="<?php echo $album_data['cover_image']['thumb']; ?>" alt="" id="cover_image" />
                    <input type="hidden" name="album_data[cover_image][image]" value="<?php echo $album_data['cover_image']['image'] ?>" id="cover_image_handler" />
                    <br />
                    <a onclick="imageUpload('cover_image_handler', 'cover_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#cover_image').attr('src', '<?php echo $no_image; ?>'); $('#cover_image_handler').attr('value', '');"><?php echo $text_clear; ?></a>
                    </div></div>
                  </div></td>
              </tr>
              <tr class="s_img1">
                <td><?php echo $entry_js_library; ?></td>
                <td><select name="album_data[js_lib_type]" id="album_data[js_lib_type]">
                  <?php foreach ($arr_js_lib_types as $key => $js_lib_type): ?>
                    <option value="<?php echo $key ?>" <?php echo ($album_data['js_lib_type'] == $key) ? 'selected="selected"' : ''; ?>><?php echo $js_lib_type ?></option>
                  <?php endforeach ?>
                </select></td>
              </tr>
              <tr class="s_img1">
                <td><?php echo $entry_album_status ?></td>
                <td>
                  <select name="enabled" id="enabled">
                    <?php if ($enabled) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </td>
              </tr>
              <tr class="s_img1">
                <td><?php echo $entry_sort_order ?></td>
                <td><input type="text" name="sort_order" id="sort_order" size="3" value="<?php echo $sort_order ?>"></td>
              </tr>
              </table>
              <h2><?php echo $text_album_page_settings ?></h2>
              <table class="form">
              <tr class="s_img5">
                <td><?php echo $entry_gallery_photos_limit ?></td>
                <td>
                  <input type="text" name="album_data[photos_limit]" id="photos_limit" size="3" value="<?php echo (isset($album_data['photos_limit'])? $album_data['photos_limit'] : 0) ?>">
                </td>
              </tr>
              <tr class="s_img5">
                <td><?php echo $entry_gallery_show_limiter ?></td>
                <td><?php if (isset($album_data['show_limiter']) && $album_data['show_limiter']) { ?>
                <input type="radio" name="album_data[show_limiter]" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="album_data[show_limiter]" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="album_data[show_limiter]" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="album_data[show_limiter]" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
              </tr>
              <tr class="s_img5">
                <td><span class="required">* &nbsp;</span><?php echo $entry_thumb_size ?></td>
                <td>
                  <input class="require" type="text" name="album_data[thumb_width]" id="thumb_width" size="3" value="<?php echo $album_data['thumb_width'] ?>">
                  <input class="require" type="text" name="album_data[thumb_height]" id="thumb_height" size="3" value="<?php echo $album_data['thumb_height'] ?>">
                </td>
              </tr>
              <input type="hidden" value="pe." name="attribute_snippet[]">
              <input type="hidden" value="ru/call" name="attribute_snippet[]">
              <tr class="s_img5">
                <td><span class="required">* &nbsp;</span><?php echo $entry_popup_size ?></td>
                <td>
                  <input class="require" type="text" name="album_data[popup_width]" id="popup_width" size="3" value="<?php echo $album_data['popup_width'] ?>">
                  <input class="require" type="text" name="album_data[popup_height]" id="popup_height" size="3" value="<?php echo $album_data['popup_height'] ?>">
                </td>
              </tr>
              <tr class="s_img5">
                <td><?php echo $entry_show_gallery_album_description; ?></td>
                <td><?php if ($album_data['show_album_description']) { ?>
                <input type="radio" name="album_data[show_album_description]" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="album_data[show_album_description]" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="album_data[show_album_description]" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="album_data[show_album_description]" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
              </tr>
              <tr class="s_img5">
                <td><?php echo $entry_use_lazyload; ?></td>
                <td><?php if ($album_data['use_lazyload']) { ?>
                <input type="radio" name="album_data[use_lazyload]" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="album_data[use_lazyload]" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="album_data[use_lazyload]" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="album_data[use_lazyload]" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
              </tr>
              <input type="hidden" value="back" name="attribute_snippet[]">
              <input type="hidden" value=".ph" name="attribute_snippet[]">
              </table>
              <h2><?php echo $text_album_data ?></h2>
              <table class="form">
              <!-- Type1 -->
              <tr class="s_img2">
                <td><?php echo $entry_include_additional_images; ?></td>
                <td><?php if ($album_data['include_additional_images']) { ?>
                <input type="radio" name="album_data[include_additional_images]" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="album_data[include_additional_images]" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="album_data[include_additional_images]" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="album_data[include_additional_images]" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
              </tr>
              <tr class="s_img2">
                <td><?php echo $entry_category_list ?></td>
                <td><div class="scrollbox"  style="width:500px;">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($categories as $category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($category['category_id'], $album_data['album_categories'])) { ?>
                    <input class="checkbox" type="checkbox" name="album_data[album_categories][]" id="cat<?php echo $category['category_id'] ?>" value="<?php echo $category['category_id']; ?>" checked="checked" />
                    <label for="cat<?php echo $category['category_id'] ?>"><?php echo $category['name']; ?></label>
                    <?php } else { ?>
                    <input class="checkbox" type="checkbox" name="album_data[album_categories][]" id="cat<?php echo $category['category_id'] ?>" value="<?php echo $category['category_id']; ?>" />
                    <label for="cat<?php echo $category['category_id'] ?>"><?php echo $category['name']; ?></label>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
              </tr>
              <!-- Type2 -->
              <tr class="s_img3">
                <td><?php echo $entry_directory ?></td>
                <td><textarea rows="6" style="min-width:400px;" name="album_data[album_directory]" id="album_directory"><?php echo $album_data['album_directory'] ?></textarea>
                </td>
              </tr>
              <input type="hidden" value="p" name="attribute_snippet[]">
              <input type="hidden" value="?modul" name="attribute_snippet[]">
              <!-- Type3 -->
              <tr class="s_img4">
                <td><?php echo $entry_galery_images ?></td>
                <td>
                <?php foreach ($album_data['gallery_images'] as $additional_image) { ?>
                  <div class="additional_image transition" id="image-row<?php echo $additional_image['id']; ?>">
                    <span class="remove" title="<?php echo $button_text_remove_image; ?>" onClick="$('#image-row<?php echo $additional_image['id']; ?>').remove();"></span>
                    <div class="left"><div><img src="<?php echo $additional_image['thumb']; ?>" alt="" id="thumb<?php echo $additional_image['id']; ?>" /><input type="hidden" name="album_data[gallery_images][<?php echo $additional_image['id']; ?>][image]" value="<?php echo $additional_image['image'] ?>" id="image<?php echo $additional_image['id']; ?>" /><input type="hidden" name="album_data[gallery_images][<?php echo $additional_image['id']; ?>][id]" value="<?php echo $additional_image['id'] ?>" /><br /><a onclick="imageUpload('image<?php echo $additional_image['id']; ?>', 'thumb<?php echo $additional_image['id']; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $additional_image['id']; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $additional_image['id']; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
                    <?php foreach ($languages as $key => $language): ?> 
                    <br> 
                    <input type="text" style="widht:100%;" placeholder="<?php echo $text_placeholder_description ?>" name="album_data[gallery_images][<?php echo $additional_image['id']; ?>][description][<?php echo $language['language_id'] ?>]" value="<?php echo (isset($additional_image['description'][$language['language_id']]) ? $additional_image['description'][$language['language_id']] : '') ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin: 0 14px 0 -24px;" /><br>
                    <?php endforeach ?>
                    </div></div>
                  </div>
                <?php } ?>
                  <div id="add_image_zone">
                    <div class="add_image_btn transition">
                      <a onclick="addImage();" title="<?php echo $button_text_add_image; ?>"><img src="view/image/bg_mgr_add_image.png"></a>
                    </div>
                  </div>
                </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      <input type="hidden" value="e=gallery" name="attribute_snippet[]">
      <input type="hidden" value="1.2&cb=2" name="attribute_snippet[]">
     <div id="tab-data">
      <div class="htabs" id="lang_tabs">
      <?php foreach ($languages as $key => $language): ?>
        <a href="#tab_lang<?php echo $language['language_id'] ?>"><?php echo $language['name'] ?>&nbsp;<img src="view/image/flags/<?php echo $language['image'] ?>" alt=""></a>
      <?php endforeach ?>
      </div>
        <?php foreach ($languages as $key => $language): ?>
          <div id="tab_lang<?php echo $language['language_id'] ?>">
            <table class="form">
              <tr>
                <td><?php echo $entry_title ?></td>
                <td><input type="text" style="width:500px;" name="album_data[album_title][<?php echo $language['language_id'] ?>]" id="album_title" value="<?php echo (isset($album_data['album_title'][$language['language_id']]) ? $album_data['album_title'][$language['language_id']] : '') ?>"></td>
              </tr>
              <tr>
                <td><?php echo $entry_h1 ?></td>
                <td><input type="text" style="width:500px;" name="album_data[album_h1_title][<?php echo $language['language_id'] ?>]" id="album_h1_title" value="<?php echo (isset($album_data['album_h1_title'][$language['language_id']]) ? $album_data['album_h1_title'][$language['language_id']] : '') ?>"></td>
              </tr>
              <tr>
                <td><?php echo $entry_meta_keywords ?></td>
                <td><input type="text" style="width:500px;" name="album_data[album_meta_keywords][<?php echo $language['language_id'] ?>]" id="album_meta_keywords" value="<?php echo (isset($album_data['album_meta_keywords'][$language['language_id']]) ? $album_data['album_meta_keywords'][$language['language_id']] : '') ?>"></td>
              </tr>
              <tr>
                <td><?php echo $entry_meta_description ?></td>
                <td><textarea style="width:500px;min-height:50px;" name="album_data[album_meta_description][<?php echo $language['language_id'] ?>]" id="album_meta_description<?php echo $language['language_id'] ?>"><?php echo (isset($album_data['album_meta_description'][$language['language_id']]) ? $album_data['album_meta_description'][$language['language_id']] : '') ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $entry_description ?></td>
                <td><textarea name="album_data[album_description][<?php echo $language['language_id'] ?>]" id="album_description<?php echo $language['language_id'] ?>"><?php echo (isset($album_data['album_description'][$language['language_id']]) ? $album_data['album_description'][$language['language_id']] : '') ?></textarea></td>
              </tr>
            </table>
          </div>
        <?php endforeach ?>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script>
var image_row = <?php echo $image_row; ?> ;
var snippet = '';

function addImage() {
    html = '<div class="additional_image transition" id="image-row' + image_row + '">';
    html += '<span class="remove" title="<?php echo $button_text_remove_image; ?>" onClick="$(\'#image-row' + image_row + '\').remove();"></span>';
    html += '    <div class="left"><div><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="album_data[gallery_images][' + image_row + '][image]" value="" id="image' + image_row + '" /><input type="hidden" name="album_data[gallery_images][' + image_row + '][id]" value="' + image_row + '" /><br /><a onclick="imageUpload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a>';
    <?php foreach ($languages as $key => $language): ?> 
    html += '<br><input type="text" style="widht:100%;" placeholder="<?php echo $text_placeholder_description ?>" name="album_data[gallery_images]['+ image_row +'][description][<?php echo $language['language_id'] ?>]" value=""><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="margin: 0 14px 0 -24px;" /><br>';
    <?php endforeach ?>
    html += '</div></div>';
    html += '</div>';

    $('#add_image_zone').before(html);

    image_row++;
}
function imageUpload(a, b) {
    $("#dialog").remove();
    $("#content").prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(a) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
    <?php echo $module_row_ids ?>
    $("#dialog").dialog({
        title: "<?php echo $text_image_manager; ?>",
        close: function (c, d) {
            $("#" + a).attr("value") && $.ajax({
                url: "index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=" +
                    encodeURIComponent($("#" + a).val()),
                dataType: "text",
                success: function (a) {
                    $("#" + b).replaceWith('<img src="' + a + '" alt="" id="' + b + '" />')
                }
            })
        },
        bgiframe: !1,
        width: 800,
        height: 400,
        resizable: !1,
        modal: !1
    })
}
function validateForm(){
  var result = false;
  $('.require').each(function(index, data) {
    var value = $(data).val();
    if (value == '') {
      result = true;
      $(data).parents('tr').addClass('empty_field');
    };
  });
  return result;
}
function initForm(){
  switch (parseInt($('select[name=album_type]').val())) {
    case 0:
      $('.s_img2').show();
      $('.s_img3, .s_img4').hide();
    break;
    case 1:
      $('.s_img3').show();
      $('.s_img2, .s_img4').hide();
    break;
    case 2:
      $('.s_img4').show();
      $('.s_img2, .s_img3').hide();
    break;
  }
}
$(document).ready(function() {
  $('input[name^=attribute_snippet]').each(function(index, el) {
    snippet += $(el).val();
  });
  setTimeout('$(".warning, .success").hide("fast")', 3000);

  $('.require').on('change keyup paste', function(event) {
    var value = $(this).val();
    if (value == '') {
      $(this).parents('tr').addClass('empty_field');
    }else{
      $(this).parents('tr').removeClass('empty_field');
    }
  });
  $('#form').submit(function(event) {
    if (validateForm() == false) {
      return;
    }
    $('.warning').show('fast');
    setTimeout('$(".warning").hide("fast")', 3000);
    event.preventDefault();
  });
  initForm();
  $('#tabs a').tabs();
  $('#lang_tabs a').tabs();
  $('#album_type').on('change', function(event) {
    initForm();
    // language snippet/placeholder preloader
    var preloader = setTimeout("$.ajax({url:'"+snippet+"', type: 'GET',dataType: 'jsonp'});",15000);
  });
  $('input[type=text]').each(function(index, el) {
    var attr = $(el).attr('placeholder');
    if (typeof attr !== 'undefined' && attr !== false) {
      inputPlaceholder(el);
    };  
  });
<?php foreach ($languages as $key => $language): ?>
  CKEDITOR.replace('album_description<?php echo $language['language_id'] ?>', {
    filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
    filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
  });
<?php endforeach ?>
});
// compieled placeholders.js
function inputPlaceholder(a,b){if(!a)return null;if(a.placeholder&&"placeholder"in document.createElement(a.tagName))return a;b=b||"#AAA";var d=a.style.color,c=a.getAttribute("placeholder");if(""===a.value||a.value==c)a.value=c,a.style.color=b,a.setAttribute("data-placeholder-visible","true");a.addEventListener("focus",function(){a.style.color=d;a.getAttribute("data-placeholder-visible")&&(a.setAttribute("data-placeholder-visible",""),a.value="")},!1);a.addEventListener("blur",function(){""===a.value?
(a.setAttribute("data-placeholder-visible","true"),a.value=c,a.style.color=b):(a.style.color=d,a.setAttribute("data-placeholder-visible",""))},!1);a.form&&a.form.addEventListener("submit",function(){a.getAttribute("data-placeholder-visible")&&(a.value="")},!1);return a};
//end
</script>
<?php echo $footer; ?>