<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" title="<?php echo $breadcrumb['text']; ?>" href="<?php echo $breadcrumb['href']; ?>"><span itemprop="title"><?php echo $breadcrumb['text']; ?></span></a></span>
    <?php } ?>
  </div>
  <!-- Quick Checkout v4.0 by Dreamvention.com checkout/quickcheckout.tpl -->
  <?php echo $quickcheckout; ?>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>