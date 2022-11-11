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
    <div class="heading order_head">
      <h1><img src="view/image/backup.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#restore').submit();" class="button"><?php echo $button_restore; ?></a><a onclick="$('#backup').submit();" class="button"><?php echo $button_backup; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $restore; ?>" method="post" enctype="multipart/form-data" id="restore">
        <table class="form">
          <tr>
            <td><?php echo $entry_restore; ?></td>
            <td><input type="file" name="import" /></td>
          </tr>
        </table>
      </form>
      <form action="<?php echo $backup; ?>" method="post" enctype="multipart/form-data" id="backup">
        <table class="form">
          <tr>
            <td><?php echo $entry_backup; ?></td>
            <td><div class="scrollbox" style="margin-bottom: 5px;">
                <?php $class = 'odd'; ?>
                <?php foreach ($tables as $table) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <input id="backup_<?php echo $table; ?>" class="checkbox" type="checkbox" name="backup[]" value="<?php echo $table; ?>" checked="checked" />
                  <label for="backup_<?php echo $table; ?>"><?php echo $table; ?></div></label>
                <?php } ?>
              </div>
              <a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>