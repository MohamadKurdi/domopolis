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
  <div class="heading order_head">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
	
	<div id="tabs" class="vtabs">
		<a id="apri-general" href="#tab-general"><?php echo $tab_general; ?></a>
		<a id="apri-mail" href="#tab-mail"><?php echo $tab_mail; ?></a>
	</div>
  
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	  
		<div id="tab-general" class="vtabs-content">
		  <table class="form">
			<tr>
				<td class="left"><span class="required">* </span><?php echo $entry_cron_password; ?></td>
				<td><input type="text" name="apri_secret_code" value="<?php echo $apri_secret_code; ?>">
				<?php if ($error_cron_password) { ?>
				<span class="error"><?php echo $error_cron_password; ?></span>
				<?php } ?>
				</td>
			</tr>
			<tr>
				<td class="left"><?php echo $entry_start_date; ?></td>
				<td><input type="text" name="apri_start_date" value="<?php echo $apri_start_date; ?>" class="date"></td>
			</tr>
			<tr>
				<td class="left"><?php echo $entry_days_after; ?></td>
				<td><input type="text" name="apri_days_after" value="<?php echo $apri_days_after; ?>"></td>
			</tr>
			<tr>
				<td class="left"><span class="required">* </span><?php echo $entry_allowed_statuses; ?></td>
				<td>
					<div class="scrollbox">
					<?php $class = 'odd'; ?>
					<?php foreach($order_statuses as $order_status) { ?>
					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
						<div class="<?php echo $class;?>">
						<?php   if (in_array($order_status['order_status_id'], $apri_allowed_statuses)) { ?>
									<input id="apri_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="apri_allowed_statuses[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
									<label for="apri_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
						<?php   } else { ?>
									<input id="apri_<?php echo $order_status['order_status_id']; ?>" class="checkbox" type="checkbox" name="apri_allowed_statuses[]" value="<?php echo $order_status['order_status_id']; ?>" />
									<label for="apri_<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></label>
									<?php   } ?>
						</div>	
					<?php } ?>
					</div>
					<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
					<?php if ($error_allowed_statuses){  ?>
					<span class="error"><?php echo $error_allowed_statuses; ?></span>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td class="left"><?php echo $entry_allow_unsubscribe; ?></td>
				<td><select name="apri_allow_unsubscribe">
					<?php if ($apri_allow_unsubscribe) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				</select></td>
			</tr>
			<tr>
				<td class="left"><?php echo $entry_log_to_admin; ?></td>
				<td><select name="apri_log_admin">
					<?php if ($apri_log_admin) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				</select></td>
			</tr>
		  </table>
		</div> 
		
		<div id="tab-mail" class="vtabs-content">
			<table class="form">
				<tr>
					<td class="left"><?php echo $entry_use_html_email; ?></td>
					<td><select name="apri_use_html_email">
						<?php if ($apri_use_html_email) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select>
					
					<?php if ($error_use_html_email){  ?>
					<span class="error"><?php echo $error_use_html_email; ?></span>
					<?php } ?>
					</td>
				</tr>
			</table>
			
			<div class="attention"><?php echo $text_help_customized; ?></div>	
			
			<div id="languages" class="htabs" style="height: 23px;">
				<?php foreach ($languages as $language) { ?>
				<a href="#language<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
				<?php } ?>
			<div class="clr"></div>
			</div>
			
			<div class="th_style"></div>
			
			<?php foreach ($languages as $language) { ?>
			<div id="language<?php echo $language['language_id']; ?>">
				<table class="form">
					<tr>
						<td class="left"><span class="required">* </span><?php echo $entry_mail_subject; ?></td>
						<td><input name="apri_mail[<?php echo $language['language_id']; ?>][subject]" size="100" value="<?php echo isset($apri_mail[$language['language_id']]) ? $apri_mail[$language['language_id']]['subject'] : ''; ?>" />
						<?php if (isset($error_mail_subject[$language['language_id']])) { ?>
						<span class="error"><?php echo $error_mail_subject[$language['language_id']]; ?></span>
						<?php } ?>
						</td>
					</tr>
					<tr>
						<td class="left"><span class="required">* </span><?php echo $entry_mail_message; ?></td>
						<td><textarea name="apri_mail[<?php echo $language['language_id']; ?>][message]" id="apri_mail_<?php echo $language['language_id']; ?>" cols="120" rows="8"><?php echo isset($apri_mail[$language['language_id']]) ? $apri_mail[$language['language_id']]['message'] : ''; ?></textarea>
						<?php if (isset($error_mail_message[$language['language_id']])) { ?>
						<span class="error"><?php echo $error_mail_message[$language['language_id']]; ?></span>
						<?php } ?>
						</td>
					</tr>
					<tr>
						<td class="left"><span class="required">* </span><?php echo $entry_mail_log_subject; ?></td>
						<td><input name="apri_mail[<?php echo $language['language_id']; ?>][log_subject]" size="100" value="<?php echo isset($apri_mail[$language['language_id']]) ? $apri_mail[$language['language_id']]['log_subject'] : ''; ?>" />
						<?php if (isset($error_mail_log_subject[$language['language_id']])) { ?>
						<span class="error"><?php echo $error_mail_log_subject[$language['language_id']]; ?></span>
						<?php } ?>
						</td>
					</tr>
					<tr>
						<td class="left"><span class="required">* </span><?php echo $entry_mail_log_message; ?></td>
						<td><textarea name="apri_mail[<?php echo $language['language_id']; ?>][log_message]" id="apri_mail_log_<?php echo $language['language_id']; ?>" cols="120" rows="8"><?php echo isset($apri_mail[$language['language_id']]) ? $apri_mail[$language['language_id']]['log_message'] : ''; ?></textarea>
						<?php if (isset($error_mail_log_message[$language['language_id']])) { ?>
						<span class="error"><?php echo $error_mail_log_message[$language['language_id']]; ?></span>
						<?php } ?>
						</td>
					</tr>
				</table>
			</div>
			<?php } ?>		
		</div>	
		
    </form>
  </div>
</div>
<script type="text/javascript"><!--	
$('#tabs a').tabs();
$('#languages a').tabs();
//--></script>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('apri_mail_<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});

CKEDITOR.replace('apri_mail_log_<?php echo $language['language_id']; ?>', {
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
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<?php echo $footer; ?>