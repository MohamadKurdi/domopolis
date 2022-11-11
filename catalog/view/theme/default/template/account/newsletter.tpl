<?php echo $header; ?><?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<div id="content" class="account_wrap">
<?php echo $content_top; ?>
<div class="wrap two_column">
  <div class="side_bar">
      <?php echo $column_left; ?>
  </div>
  <div class="account_content">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div class="content newsletter-content">
      <table class="form">
        <tr>
          <td><p><?php echo $entry_newsletter; ?></p></td>
          <td><?php if ($newsletter) { ?>
            <div class="col-xs-12 col-sm-3">
              <input id="radio1" type="radio" name="newsletter" value="1" checked="checked" />
              <label for="radio1"><span><span></span></span><?php echo $text_yes; ?>&nbsp;</label>
            </div>
            <div class="col-xs-12 col-sm-3">
              <input id="radio2" type="radio" name="newsletter" value="0" />
              <label for="radio2"><span><span></span></span><?php echo $text_no; ?>&nbsp;</label>
            </div>
            <?php } else { ?>
            <div class="col-xs-12 col-sm-3">
              <input id="radio1" type="radio" name="newsletter" value="1"  />
              <label for="radio1"><span><span></span></span><?php echo $text_yes; ?>&nbsp;</label>
            </div>
            <div class="col-xs-12 col-sm-3">
              <input id="radio2" type="radio" name="newsletter" value="0" checked="checked" />
              <label for="radio2"><span><span></span></span><?php echo $text_no; ?>&nbsp;</label>
            </div>
            <?php } ?>
          </td>
        </tr>
      </table>
    </div>
    <div class="buttons">
   <!--    <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div> -->
      <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-acaunt"  />
    </div>
  </form>
  <?php echo $content_bottom; ?>
  </div>
</div>
</div>
<?php echo $footer; ?>