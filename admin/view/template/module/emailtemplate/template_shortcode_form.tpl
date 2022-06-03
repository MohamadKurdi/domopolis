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
				<h1><img src="view/image/mail.png" alt="<?php echo $heading_title; ?>" /><?php echo $heading_title; ?></h1>
	
				<div class="buttons">
					<a onclick="$('#form').attr('action', '<?php echo $action; ?>'); $('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
					<a href="<?php echo $cancel; ?>" class="button button-secondary"><span><?php echo $button_back; ?></span></a>
				</div>
			</div>
	
			<div class="content">
				<table class="form">
    				<tr>
						<td>
							<label for="emailtemplate_shortcode_code"><span class="required">*</span> <?php echo $entry_code; ?></label>
						</td>
						<td>
							<input class="large" type="text" name="emailtemplate_shortcode_code" id="emailtemplate_shortcode_code" value="<?php echo $shortcode['code']; ?>" />
							<?php if(isset($error_emailtemplate_shortcode_code)) { ?>
								<span class="error"><?php echo $error_emailtemplate_shortcode_code; ?></span>
							<?php } ?>
						</td>
					</tr>
    				<tr>
						<td>
							<label for="emailtemplate_shortcode_type"><span class="required">*</span> <?php echo $entry_type; ?></label>
						</td>
						<td>
							<select name="emailtemplate_shortcode_type" id="emailtemplate_shortcode_type">
								<option value="auto"<?php if($shortcode['type'] == 'auto'){ ?> selected="selected"<?php } ?>><?php echo $text_auto; ?></option>													
								<option value="language"<?php if($shortcode['type'] == 'language'){ ?> selected="selected"<?php } ?>><?php echo $text_language; ?></option>													
							</select>
							<?php if(isset($error_emailtemplate_shortcode_type)) { ?>
								<span class="error"><?php echo $error_emailtemplate_shortcode_type; ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<label for="emailtemplate_shortcode_example"><?php echo $entry_example; ?></label>
						</td>
						<td>
							<input class="large" type="text" name="emailtemplate_shortcode_example" id="emailtemplate_shortcode_example" value="<?php echo $shortcode['example']; ?>" />
							<?php if(isset($error_emailtemplate_shortcode_example)) { ?>
								<span class="error"><?php echo $error_emailtemplate_shortcode_example; ?></span>
							<?php } ?>
						</td>
					</tr>
				</table>
			</div>	
		</div>
	</form>
</div>

<link type="text/css" href="view/stylesheet/module/emailtemplate.css" rel="stylesheet" media="screen" />

<?php echo $footer; ?>