<input type="hidden" name="category_id" value="<?php echo $category_id; ?>">

<div class="content">
	<div class="buttons" id="category-form-buttons" style="width: 100%; text-align: right; margin-bottom: 3px;">
		<a class="button" id="save-category-form"><?php echo $button_save; ?></a>
	</div>
	
	<div id="languages" class="htabs">
		<?php foreach ($languages as $language) { ?>
			<a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
		<?php } ?>
	</div>
	<?php foreach ($languages as $language) { ?>
		<div id="language<?php echo $language['language_id']; ?>">
			<table class="form">
				<tr>
					<td><span class="required">*</span> <?php echo $entry_name; ?></td>
					<td><input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" size="50" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" /></td>					
				</tr>
				<tr>
					<td>SEO URL</td>
					<td><input type="text" name="keyword[<?php echo $language['language_id']; ?>]" value="<?php if (isset($keyword[$language['language_id']])) { echo $keyword[$language['language_id']]; } ?>" /></td>					
				</tr>
			</table>
		</div>
	<?php } ?>
	
	<table class="form">
		
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
	
	$('#save-category-form').bind('click', function(){
		
		$.ajax({
			type: 'POST',
			url: 'index.php?route=module/faq_system/getCategoryForm&token=<?php echo $token; ?>',
			data: $('#dialog-category-form input[type=\'text\'], #dialog-category-form input[type=\'hidden\'], #dialog-category-form select'),
			dataType: 'json',
			success: function(json){
				$('.warning').remove();
				$('.success').remove();
				
				if (json['error']){
					if (json['error']['warning']){
						$('#category-form-buttons').after('<div class="warning">' + json['error']['warning'] + '</div>');
						$('.warning').fadeIn('slow');
					}
				}
				
				if (json['success']){
					$('#category-form-buttons').after('<div class="success">' + json['success'] + '</div>');
					$('.success').fadeIn('slow');
					
					$('#category-list').load('index.php?route=module/faq_system/getCategories&token=<?php echo $token; ?>');
					
					setTimeout(function() {
						$('#dialog-category-form').dialog("close");
					}, 2000);
				}
			}
		});	
	});
	
//--></script> 