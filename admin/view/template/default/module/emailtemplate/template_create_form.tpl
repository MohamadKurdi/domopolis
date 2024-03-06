<?php echo $header; ?>

<link href="view/stylesheet/module/emailtemplate.css" type="text/css" rel="stylesheet" />

<div id="content">
	<div class="breadcrumb">
 	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
  		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  	<?php } ?>
	</div>

	<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
	<?php if ($error_attention) { ?><div class="attention"><?php echo $error_attention; ?></div><?php } ?>
	<?php if ($success) { ?><div class="success"><?php echo $success; ?></div><?php } ?>

	<div class="box" id="emailtemplate">
		<div class="heading">
			<h1><img src="view/image/mail.png" alt="" /><?php echo $heading_template_create; ?></h1>

			<div class="buttons">
				<a id="submitButton" href="javascript:void(0)" onclick="$('#form').attr('action', '<?php echo $action; ?>'); $('#form').submit();" class="button"><span><?php echo $button_create; ?></span></a>

				<span style="width:1px; height:24px; background:#e2e2e2; border-right:1px solid #fff; border-left:1px solid #fff; display:inline-block; *display:inline; zoom:1; line-height:0; vertical-align:top; margin: 0 1px 0 2px;"></span>

				<a href="<?php echo $cancel; ?>" class="button button-secondary"><span><?php echo $button_back; ?></span></a>
			</div>
		</div>

		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" style="position:relative">
				<?php if(isset($vqmod_xml)): ?>
					<p class="error"><?php echo $error_vqmod_xml; ?></p>
					<textarea style="width:100%; height: 150px;"><?php echo $vqmod_xml; ?></textarea>
				<?php endif;?>

    			<table class="form">
    				<tr>
						<td>
							<label for="emailtemplate_key"><span class="required">*</span> <?php echo $entry_key; ?></label>
						</td>
						<td>
							<?php if(!empty($emailtemplate_keys)): ?>
							<select class="large" name="emailtemplate_key_select" id="emailtemplate_key_select">
								<option value=""><?php echo $text_select; ?></option>
								<?php foreach($emailtemplate_keys as $row): ?>
									<option value="<?php echo $row['value']; ?>"<?php if($emailtemplate['key'] == $row['value'] || $emailtemplate['key_select'] == $row['value']) echo ' selected="selected"'; ?>><?php echo $row['label']; ?></option>
								<?php endforeach; ?>
							</select>
							<?php endif; ?>

							<input class="large" type="text" name="emailtemplate_key" value="<?php echo $emailtemplate['key']; ?>" placeholder="<?php echo $text_placeholder_key; ?>" id="emailtemplate_key" />
							<?php if(isset($error_emailtemplate_key)) { ?><span class="error"><?php echo $error_emailtemplate_key; ?></span><?php } ?>
						</td>
					</tr>

					<tr>
						<td>
							<label for="emailtemplate_label"><span class="required">*</span> <?php echo $entry_label; ?></label>
						</td>
						<td>
							<input class="large" type="text" name="emailtemplate_label" value="<?php echo $emailtemplate['label']; ?>" id="emailtemplate_label" />
							<?php if(isset($error_emailtemplate_label)) { ?><span class="error"><?php echo $error_emailtemplate_label; ?></span><?php } ?>
						</td>
					</tr>

					<tr>
						<td>
							<label for="emailtemplate_status"><span class="required">*</span> <?php echo $entry_status; ?></label>
						</td>
						<td>
							<label class="radio">
								<input type="radio" name="emailtemplate_status" value="ENABLED" <?php echo ($emailtemplate['status'] == '' || $emailtemplate['status'] == 'ENABLED') ? 'checked="checked"' : ''; ?>/>
								<?php echo $text_enabled; ?>
							</label><br />
							<label class="radio">
								<input type="radio" name="emailtemplate_status" value="DISABLED" <?php echo ($emailtemplate['status'] == 'DISABLED') ? 'checked="checked"' : ''; ?>/>
								<?php echo $text_disabled; ?>
							</label>

							<?php if(isset($error_emailtemplate_status)) { ?><span class="error"><?php echo $error_emailtemplate_status; ?></span><?php } ?>
						</td>
					</tr>
				</table>

			</form>
		</div>
	</div>
</div>

<script type="text/javascript"><!--
(function($){

	$(document).ready(function(){
		//Save Form [ctrl+s]
		$(window).keypress(function(event) {
			if((event.which == 115 && (event.ctrlKey||event.metaKey)) || (event.which == 19)){
			    var $button = $("#submitButton").eq(0);
			    if($button.length){
				    $button.click();
				    event.preventDefault();
			    }
			    return false;
			}
		});
	});
})(jQuery);
//--></script>

<?php echo $footer; ?>