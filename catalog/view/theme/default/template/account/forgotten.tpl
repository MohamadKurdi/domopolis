<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<section id="content"><?php echo $content_top; ?>
<div class="wrap">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="simplecheckout-customer">
    <p><?php echo $text_email; ?></p>
    <h2><?php echo $text_your_email; ?></h2>
    <div class="content">
      <table class="form">
        <tr>
          <td class="simplecheckout-customer-left" style="width: 75px;"><?php echo $entry_email; ?></td>
          <td class="simplecheckout-customer-right"><input type="text" name="email" value="" /></td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <a href="<?php echo $back; ?>" class="btn btn-acaunt-none" style="padding: 11.3px 30px;"><?php echo $button_back; ?></a>      
        <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-acaunt" />     
    </div>
  </form>
  <?php echo $content_bottom; ?></div></section>
<?php echo $footer; ?>