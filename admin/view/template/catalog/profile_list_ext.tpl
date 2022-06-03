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
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a>
        <a onclick="$('#form').attr('action', '<?php echo $copy; ?>'); $('#form').submit();" class="button"><?php echo $button_copy; ?></a>
        <a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a>
      </div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td class="center" width="1"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked).trigger('change');" /></td>
              <?php foreach($column_order as $col) { ?>
                <?php if (!empty($column_info[$col]['sort'])) { ?>
              <td class="<?php echo $column_info[$col]['align']; ?>"><a href="<?php echo $sorts[$col]; ?>"<?php echo ($sort == $column_info[$col]['sort']) ? ' class="' . strtolower($order) . '"' : ''; ?>><?php echo $columns[$col]; ?></a></td>
                <?php } else { ?>
              <td class="<?php echo $column_info[$col]['align']; ?>"><?php echo $columns[$col]; ?></td>
                <?php } ?>
              <?php } ?>
              <td class="right"><?php echo $column_action ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($profiles) { ?>
              <?php foreach ($profiles as $profile) { ?>
            <tr>
              <td class="center">
                <?php if ($profile['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $profile['profile_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $profile['profile_id']; ?>" />
                <?php } ?>
              </td>
              <?php foreach($column_order as $col) {
                switch ($col) {
                  default: ?>
              <td class="<?php echo $column_info[$col]['align']; ?><?php echo ($column_info[$col]['qe_status']) ? ' ' . $column_info[$col]['qe_type'] : ''; ?>" id="<?php echo $col . "-" . $profile['profile_id']; ?>"><?php echo $profile[$col]; ?></td>
                    <?php break;
                }
              } ?>
              <td class="right">
                <?php foreach ($profile['action'] as $action) { ?>
                [<a href="<?php echo $action['href'] ?>"><?php echo $action['text'] ?></a>]
                <?php } ?>
              </td>
            </tr>
              <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="<?php echo count($column_order) + 2; ?>"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$(function() {
  $(".quick_edit").editable(function(value, settings) {
    return quick_update(this, value, settings, '<?php echo $update_url; ?>');
  }, {
      indicator : "<?php echo $text_saving; ?>",
      tooltip   : "<?php echo ($this->config->get('aqe_quick_edit_on') == 'dblclick') ? $text_double_click_edit : $text_click_edit; ?>",
      event     : "<?php echo $this->config->get('aqe_quick_edit_on'); ?>",
      onblur    : "cancel",
      placeholder : "",
      select    : true,
  });
  $(".status_quick_edit").editable(function(value, settings) {
    return quick_update(this, value, settings, '<?php echo $update_url; ?>');
  }, {
      data      : '<?php echo trim($status_select); ?>',
      type      : 'select',
      indicator : "<?php echo $text_saving; ?>",
      tooltip   : "<?php echo ($this->config->get('aqe_quick_edit_on') == 'dblclick') ? $text_double_click_edit : $text_click_edit; ?>",
      event     : "<?php echo $this->config->get('aqe_quick_edit_on'); ?>",
      onblur    : "cancel",
  });
  $(".freq_quick_edit").editable(function(value, settings) {
    return quick_update(this, value, settings, '<?php echo $update_url; ?>');
  }, {
      data      : '<?php echo trim($frequency_select); ?>',
      type      : 'select',
      indicator : "<?php echo $text_saving; ?>",
      tooltip   : "<?php echo ($this->config->get('aqe_quick_edit_on') == 'dblclick') ? $text_double_click_edit : $text_click_edit; ?>",
      event     : "<?php echo $this->config->get('aqe_quick_edit_on'); ?>",
      onblur    : "cancel",
  });
  $(".number_quick_edit").editable(function(value, settings) {
    return quick_update(this, value, settings, '<?php echo $update_url; ?>');
  }, {
      data      : function(value, settings) {
            return $.trim(value.replace(/[^\d\.]/g, ""));
      },
      indicator : "<?php echo $text_saving; ?>",
      tooltip   : "<?php echo ($this->config->get('aqe_quick_edit_on') == 'dblclick') ? $text_double_click_edit : $text_click_edit; ?>",
      event     : "<?php echo $this->config->get('aqe_quick_edit_on'); ?>",
      onblur    : "cancel",
      placeholder : "",
      select    : true,
  });
<?php if ($single_lang_editing) { ?>
 $(".name_quick_edit").editable(function(value, settings) {
    var params = {lang_id: <?php echo $language_id; ?>};
    return quick_update(this, value, settings, '<?php echo $update_url; ?>', params);
  }, {
      indicator : "<?php echo $text_saving; ?>",
      tooltip   : "<?php echo ($this->config->get('aqe_quick_edit_on') == 'dblclick') ? $text_double_click_edit : $text_click_edit; ?>",
      event     : "<?php echo $this->config->get('aqe_quick_edit_on'); ?>",
      onblur    : "cancel",
      placeholder : "",
  });
<?php } else { ?>
 $(".name_quick_edit").editable(function(value, settings) {
    var params = {lang_id: $('select', this).find(":selected").val()};
    return quick_update(this, value, settings, '<?php echo $update_url; ?>', params);
  }, {
      type      : 'multilingual_edit',
      indicator : "<?php echo $text_saving; ?>",
      tooltip   : "<?php echo ($this->config->get('aqe_quick_edit_on') == 'dblclick') ? $text_double_click_edit : $text_click_edit; ?>",
      event     : "<?php echo $this->config->get('aqe_quick_edit_on'); ?>",
      onblur    : "cancel",
      placeholder : "",
      loadurl   : "<?php echo $load_data_url; ?>",
      loadtype  : "POST",
      loadtext  : "<?php echo $text_loading; ?>",
      loaddata  : {lang_id: <?php echo $language_id; ?>}
  });
<?php } ?>
});
//--></script>
<?php echo $footer; ?>