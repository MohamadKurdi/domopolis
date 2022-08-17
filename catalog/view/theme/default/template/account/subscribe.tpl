<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<h1><?php echo $heading_title; ?></h1>
	<?php if ($error_warning) { ?>
		<div class="warning" style="color: red; font-weight: 500;"><?php echo $error_warning; ?></div>
	<?php } else { ?>
	<?php echo $text_message_confirm; ?>
	<?php } ?>
	<div class="buttons">
		<div class="right"><a href="<?php echo $continue; ?>" class="button btn btn-acaunt"><?php echo $button_continue; ?></a></div>
	</div>
	<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>