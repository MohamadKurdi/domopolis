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
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/information.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="languages" class="htabs">
					<?php foreach ($languages as $language) { ?>
					<a href="#language<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
					<?php } ?>
				</div>
				<?php foreach ($languages as $language) { ?>
				<div id="language<?php echo $language['language_id']; ?>">
					<table class="form">
						<tr>
							<td><?php echo $entry_text_mail_authorization; ?></td>
							<td><textarea name="subscribe_authorization[<?php echo $language['language_id']; ?>]" id="subscribe-authorization<?php echo $language['language_id']; ?>"><?php echo isset($subscribe_authorization[$language['language_id']]) ? $subscribe_authorization[$language['language_id']] : ''; ?></textarea>
								<?php if (isset($error_authorization_description[$language['language_id']])) { ?>
								<span class="error"><?php echo $error_authorization_description[$language['language_id']]; ?></span>
								<?php } ?></td>
						</tr>
					</table>
				</div>
				<?php } ?>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
			CKEDITOR.replace('subscribe-authorization<?php echo $language['language_id']; ?>', {
			filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
			});
			<?php } ?>
			//--></script>
<script type="text/javascript"><!--
			$('#languages a').tabs();
//--></script>
<?php echo $footer; ?>
