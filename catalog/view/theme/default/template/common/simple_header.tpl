<?php if (!empty($header)) { echo $header; } ?>
<?php if (!empty($simpleheader)) { echo $simpleheader; } ?>
<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php include(dirname(__FILE__).'/../structured/breadcrumbs.tpl'); ?>
<section id="content"  class="<?php if (!empty($simple_page_type) && ($simple_page_type == 'simple_account' || $simple_page_type == 'simple_edit_address')) {?>account_wrap<?php } ?>" style="margin: 0;"><?php echo $content_top; ?>
<div class="wrap <?php if (!empty($simple_page_type) && ($simple_page_type == 'simple_account' || $simple_page_type == 'simple_edit_address')) {?>two_column<?php } ?>">