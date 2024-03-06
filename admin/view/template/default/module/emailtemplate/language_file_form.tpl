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
				<?php if(!empty($manual)){ ?>
				<table class="form">
					<tr>
						<td style="border:none; padding-bottom:0"><?php echo $manual['info']; ?></td>
					</tr>
					<tr>
						<td><textarea style="width:100%; height:200px;"><?php echo $manual['contents']; ?></textarea></td>
					</tr>				
				</table>
				<?php } ?>
				<table class="form">
					<?php foreach($language_vars as $langVar){ ?>
					<tr>
						<td>
							<label for="field_name"><b><?php echo $langVar['key']; ?></b></label>
						</td>
						<td>
							<?php if($langVar['hasHtml']){ ?>
								<textarea class="large lang_value hasHtml" name="vars[<?php echo $langVar['key'] ?>]" id="field_<?php echo $langVar['key']; ?>"><?php echo $langVar['value']; ?></textarea>
							<?php } elseif(strlen($langVar['value']) > 50) { ?>
								<textarea class="large lang_value" name="vars[<?php echo $langVar['key'] ?>]" id="field_<?php echo $langVar['key']; ?>"><?php echo $langVar['value']; ?></textarea>
								<span class="showEditor"></span>
							<?php } else { ?>
								<input class="large lang_value" type="text" name="vars[<?php echo $langVar['key'] ?>]" value="<?php echo $langVar['value']; ?>" id="field_<?php echo $langVar['key']; ?>" />
								<span class="showEditor"></span>
							<?php } ?>
							<?php if (isset($error_name)) {?><span class="error"><?php echo $error_name; ?></span><?php } ?> 							
						</td>
					</tr>
					<?php } ?>
				</table>
				
			</div>	
		</div>
	</form>
</div>

<link type="text/css" href="view/stylesheet/module/emailtemplate.css" rel="stylesheet" media="screen" />

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--

$('#language-body a').tabs();

var CKEDITOR_config = {
	enterMode : CKEDITOR.ENTER_BR,
    shiftEnterMode: CKEDITOR.ENTER_P,
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
};

<?php foreach($language_vars as $langVar): if($langVar['hasHtml']){ ?>
CKEDITOR.replace('field_<?php echo $langVar['key']; ?>', CKEDITOR_config);
<?php } endforeach; ?>

(function($){				
	$(document).ready(function() {
		$('.lang_value').not('.hasHtml').each(function(){
			var $this = $(this);
			$this.focus(function(){
				$('.showEditor').hide();
				$this.next().css('display', 'inline-block');
			});
		});
		$('#form').submit(function(){
			for(var instanceName in CKEDITOR.instances){
				$('#'+instanceName).val(CKEDITOR.instances[instanceName].getData());
			}
		});
		$('.showEditor').click(function(){
			CKEDITOR.replace($(this).prev().attr('id'), CKEDITOR_config)
					.setData($(this).prev().attr('value'));
			$(this).remove();
		});
	});	
})(jQuery);
//--></script>

<?php echo $footer; ?>