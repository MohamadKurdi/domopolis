
<div class="buttons" id="category-list-buttons" style="width: 100%; text-align:right; margin-bottom: 10px;">
	<a class="button" onclick="getCategoryForm();"><?php echo $button_insert; ?></a>
	<a class="button" onclick="deleteCategories();"><?php echo $button_delete; ?></a>
</div>	

<table class="list">
  <thead>
	<tr>
	  <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('#category-list input[name*=\'selected\']').attr('checked', this.checked);" /></td>
	  <td class="left"><?php echo $column_name; ?></td>
	  <td class="left"><?php echo $column_status; ?></td>
	  <td class="right"><?php echo $column_sort_order; ?></td>
	  <td class="right"><?php echo $column_action; ?></td>
	</tr>
  </thead>
  <tbody>
	<?php if ($categories) { ?>
	<?php foreach ($categories as $category) { ?>
	<tr>
	  <td style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" /></td>
	  <td class="left"><?php echo $category['name']; ?></td>
	  <td class="left"><?php echo $category['status_text']; ?></td>
	  <td class="right"><?php echo $category['sort_order']; ?></td>
	  <td class="right"><?php foreach ($category['action'] as $action) { ?>
		<a class="button" onclick="<?php echo $action['onclick']; ?>"><?php echo $action['text']; ?></a>
		<?php } ?></td>
	</tr>
	<?php } ?>
	<?php } else { ?>
	<tr>
	  <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
	</tr>
	<?php } ?>
  </tbody>
</table>

