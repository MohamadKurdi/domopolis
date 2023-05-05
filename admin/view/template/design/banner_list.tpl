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
      <h1><img src="view/image/banner.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
                <td class="left">Классы</td>
                <td class="center">Сортировка</td>
                <td class="center">
                  <?php if ($sort == 'status') { ?>
                  <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                  <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                  <?php } ?>
                </td>
                  <td class="right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($banners) { ?>
                  <?php foreach ($banners as $banner) { ?>
                    <tr>
                      <td style="text-align: center;">
                        <?php if ($banner['selected']) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $banner['banner_id']; ?>" checked="checked" />
                        <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $banner['banner_id']; ?>" />
                        <?php } ?>
                      </td>
                      <td class="left">
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><?php echo $banner['name']; ?></span>                           
                      </td>
                      <td class="left">
                        <i class="fa fa-desktop" aria-hidden="true"></i> <?php echo $banner['class']; ?><br />
                        <i class="fa fa-mobile" aria-hidden="true"></i> <?php echo $banner['class_sm']; ?>                           
                      </td>
                      <td class="center">
                        <span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><?php echo $banner['sort_order']; ?></span>                           
                      </td>                      
                      <td class="center">
                        <? if ($banner['status']) { ?>
                          <i class="fa fa-check-circle" style="color:#4ea24e"></i>
                        <? } else { ?>
                          <i class="fa fa-times-circle" style="color:#cf4a61"></i>
                        <? } ?>
                      </td>
                      <td class="right"><?php foreach ($banner['action'] as $action) { ?>
                        <a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
                        <?php } ?></td>
                      </tr>
                    <?php } ?>
                  <?php } else { ?>
                    <tr>
                      <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </form>
            <div class="pagination"><?php echo $pagination; ?></div>
          </div>
        </div>
      </div>
      <?php echo $footer; ?>