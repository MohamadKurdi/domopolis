<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
 <div class="interplusplus">
 <p><?php echo $fail_text; ?></p>
 <div class="buttons">
 <a href="<?php echo $button_ok_url;?>" class="button" id="inter"><span><?php echo $button_ok; ?></span></a>
</div>
</div>
  </div>
  <?php echo $content_bottom; ?>
<?php echo $footer; ?>