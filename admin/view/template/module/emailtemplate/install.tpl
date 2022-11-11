<?php echo $header; ?>

<div id="content">
	<div class="breadcrumb">
 	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
  		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  	<?php } ?>
	</div>

	<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
	<?php if ($error_attention) { ?><div class="attention"><?php echo $error_attention; ?></div><?php } ?>
	<?php if ($success) { ?><div class="success"><?php echo $success; ?></div><?php } ?>

	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<div class="box" id="emailtemplate">
			<div class="heading">
				<h1><img src="view/image/mail.png" alt="<?php echo $heading_install; ?>" /><?php echo $heading_install; ?></h1>

				<div class="buttons">
					<button type="submit" class="button"><span><?php echo $button_install; ?></span></button>
					<a href="<?php echo $cancel; ?>" class="button button-secondary"><span><?php echo $button_back; ?></span></a>
				</div>
			</div>

			<div class="content">
				<table class="form">
					<tr>
						<td align="right"><label for="field_insert_shortcodes"><?php echo $entry_default_shortcodes; ?></label></td>
						<td>
							<input type="checkbox" name="insert_shortcodes" id="field_insert_shortcodes" value="1" style="vertical-align:middle; margin-top: 1px;" checked="checked" />
							<span class="help" style="display: inline"><?php echo $entry_default_shortcodes_info; ?></span>
						</td>
					</tr>
					<tr>
						<td align="right"><label for="field_lang_shortcodes"><?php echo $entry_lang_shortcodes; ?></label></td>
						<td>
							<input type="checkbox" name="lang_shortcodes" id="field_lang_shortcodes" value="1" style="vertical-align:middle; margin-top: 1px;" checked="checked" />
						</td>
					</tr>
					<?php if($install_cleanup){ ?>
					<tr>
						<td align="right"><label for="field_install_cleanup"><?php echo $entry_install_cleanup; ?></label></td>
						<td>
							<input type="checkbox" name="install_cleanup" id="field_install_cleanup" value="1" style="vertical-align:middle; margin-top: 1px;" checked="checked" />
						</td>
					</tr>
					<?php } ?>
				</table>

			</div>
		</div>
	</form>
</div>

<link type="text/css" href="view/stylesheet/module/emailtemplate.css" rel="stylesheet" media="screen" />

<?php echo $footer; ?>