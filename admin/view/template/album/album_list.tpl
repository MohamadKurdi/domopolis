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
    <div class="heading order_head">
      <h1><a href="<?php echo $link_section_album_list ?>"><img alt="<?php echo $text_section_album_list ?>" title="<?php echo $text_section_album_list ?>" src="view/image/image.png" /></a>
        <a href="<?php echo $link_section_modules ?>"><img alt="<?php echo $text_section_modules ?>" title="<?php echo $text_section_modules ?>" src="view/image/module.png" /></a>
        <a href="<?php echo $link_section_settings ?>"><img alt="<?php echo $text_section_settings ?>" title="<?php echo $text_section_settings ?>" src="view/image/setting.png" /></a>
        <?php echo $heading_title ?></h1> 
      <div class="buttons">
        <a href="<?php echo $add ?>" class="button"><?php echo $button_text_insert ?></a><?php if (!empty($albums)): ?>
          <a href="#" onClick="$('#form').attr('action','<?php echo $copy ?>');$('#form').submit();return false;" class="button"><?php echo $button_text_copy ?></a>
        <a href="#" onClick="$('#form').attr('action','<?php echo $delete ?>');$('#form').submit();return false;" class="button"><?php echo $button_text_delete ?></a><?php endif ?>
      </div>
    </div>
    <div class="content">
      <?php if (empty($albums)){ ?>
        <center><?php echo $text_list_empty ?></center>
      <?php }else{ ?>
        <form action="<?php echo $delete ?>" method="post" enctype="multipart/form-data" id="form">
          <table class="list">
            <thead>
              <tr>
                <td style="text-align: center;" class="left" width="1">
                  <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);">
                </td>
                <td class="left"><?php echo $column_name ?></td>
                <td class="left"><?php echo $column_album_type ?></td>
                <td class="right" width="100"><?php echo $column_enabled ?></td>
                <td class="right" width="140"><?php echo $column_sort_order ?></td>
                <td class="right" width="80"><?php echo $column_action ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($albums as $key => $album): ?>
              <tr style="cursor:pointer;">
                <td style="text-align: center;" class="left">
                <input type="checkbox" class="chk<?php echo $album['album_id'] ?>" name="selected[]" value="<?php echo $album['album_id'] ?>" />
                </td>
                <td class="left" onClick="window.location.href='<?php echo $album['edit_link'] ?>';"><?php echo $album['album_data']['album_name'][$config_admin_language_id] ?></td>
                <td class="left" onClick="window.location.href='<?php echo $album['edit_link'] ?>';"><?php echo $album_types[$album['album_type']] ?></td>
                <td class="right"><?php echo $arr_enabled[$album['enabled']]; ?></td>
                <td class="right"><?php echo $album['sort_order'] ?></td>
                <td class="right">
                  [ <a href="<?php echo $album['edit_link'] ?>"><?php echo $button_text_edit ?></a> ]
                </td>
              </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </form>
      <?php } ?>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      setTimeout('$(".warning, .success").hide("fast")', 3000);
    });
  </script>
<?php echo $footer ?>