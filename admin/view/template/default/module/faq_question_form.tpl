<input type="hidden" name="question_id" value="<?php echo $question_id; ?>">

<div class="content">
	<div class="buttons" id="question-form-buttons" style="width: 100%; text-align: right; margin-bottom: 3px;">
		<a class="button" id="save-question-form"><?php echo $button_save; ?></a>
	</div>

	<div id="languages" class="htabs">
	<?php foreach ($languages as $language) { ?>
	<a href="#language<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
	<?php } ?>
	</div>
	<?php foreach ($languages as $language) { ?>
	<div id="language<?php echo $language['language_id']; ?>">
	<table class="form">
	  <tr>
		<td><span class="required">*</span> <?php echo $entry_question; ?></td>
		<td><input type="text" name="question_description[<?php echo $language['language_id']; ?>][title]" size="100" value="<?php echo isset($question_description[$language['language_id']]) ? $question_description[$language['language_id']]['title'] : ''; ?>" /></td>
	  </tr>
	  <tr>
		<td><span class="required">*</span> <?php echo $entry_answer; ?></td>
		<td><textarea name="question_description[<?php echo $language['language_id']; ?>][description]" id="qd_<?php echo $language['language_id']; ?>"><?php echo isset($question_description[$language['language_id']]) ? $question_description[$language['language_id']]['description'] : ''; ?></textarea></td>
	  </tr>
	</table>
	</div>
	<?php } ?>

	<table class="form">
	  <tr>
		<td><span class="required">* </span><?php echo $entry_category; ?></td>
		<td><select name="category_id">
			<option value=""></option>
			<?php foreach($categories as $category) { ?>
			<?php   if ($category_id == $category['category_id']){ ?>
					   <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>				
		    <?php   } else  {?>
						<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>				
			<?php   } ?>
			<?php } ?>
		</select></td>
	  </tr>
	  
	  <tr>
		<td><?php echo $entry_sort_order; ?></td>
		<td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" /></td>
	  </tr>
	  
	  <tr>
		<td><?php echo $entry_status; ?></td>
		<td><select name="status">
			<?php if ($status) { ?>
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

<script type="text/javascript"><!--
$('#languages a').tabs();

<?php foreach($languages as $language) { ?>
if (CKEDITOR.instances['qd_<?php echo $language['language_id']; ?>']) {
	CKEDITOR.remove(CKEDITOR.instances['qd_<?php echo $language['language_id']; ?>']);
}

if ($('#qd_<?php echo $language['language_id']; ?>').length){
	CKEDITOR.replace('qd_<?php echo $language['language_id']; ?>', {
		filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	});
}
<?php } ?>

$('#save-question-form').bind('click', function(){
	
	for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
	
	$.ajax({
		type: 'POST',
		url: 'index.php?route=module/faq_system/getQuestionForm&token=<?php echo $token; ?>',
		data: $('#dialog-question-form input[type=\'text\'], #dialog-question-form input[type=\'hidden\'], #dialog-question-form select, #dialog-question-form textarea'),
		dataType: 'json',
		success: function(json){
			$('.warning').remove();
			$('.success').remove();
			
			if (json['error']){
				if (json['error']['warning']){
					$('#question-form-buttons').after('<div class="warning">' + json['error']['warning'] + '</div>');
					$('.warning').fadeIn('slow');
				}
			}
			
			if (json['success']){
				$('#question-form-buttons').after('<div class="success">' + json['success'] + '</div>');
				$('.success').fadeIn('slow');
				
				$('#question-list').load('index.php?route=module/faq_system/getQuestions&token=<?php echo $token; ?>');
				$('#proposal-list').load('index.php?route=module/faq_system/getQuestions&status=0&token=<?php echo $token; ?>');
				
				setTimeout(function() {
					$('#dialog-question-form').dialog("close");
				}, 2000);
			}
		}
	});	
});

//--></script> 