
<div class="buttons" id="question-list-buttons" style="width: 100%; text-align:right; margin-bottom: 10px;">
	<?php if ($filter_status){ ?>
		<a class="button" onclick="getQuestionForm();"><?php echo $button_insert; ?></a>
		<a class="button" onclick="deleteQuestions('validated');"><?php echo $button_delete; ?></a>
	<?php } else { ?>
		<a class="button" onclick="deleteQuestions('proposal');"><?php echo $button_delete; ?></a>
	<?php } ?>
	
</div>	

<table class="list">
  <thead>
	<tr>
	  <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('<?php echo ($filter_status ==1 )? '#question-list ' : '#proposal-list' ?> input[name*=\'selected\']').attr('checked', this.checked);" /></td>
	  <td class="left"><?php echo $column_question; ?></td>
	  <td class="left"><?php echo $column_category; ?></td>
	  <td class="left"><?php echo $column_status; ?></td>
	  <td class="right"><?php echo $column_sort_order; ?></td>
	  <td class="right"><?php echo $column_action; ?></td>
	</tr>
  </thead>
  <tbody>
	<?php if ($questions) { ?>
	<?php foreach ($questions as $question) { ?>
	<tr>
	  <td style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $question['question_id']; ?>" /></td>
	  <td class="left"><?php echo $question['title']; ?></td>
	  <td class="left"><?php echo $question['category']; ?></td>
	  <td class="left"><?php echo $question['status_text']; ?></td>
	  <td class="right"><?php echo $question['sort_order']; ?></td>
	  <td class="right"><?php foreach ($question['action'] as $action) { ?>
		<a class="button" onclick="<?php echo $action['onclick']; ?>"><?php echo $action['text']; ?></a>
		<?php } ?></td>
	</tr>
	<?php } ?>
	<?php } else { ?>
	<tr>
	  <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
	</tr>
	<?php } ?>
  </tbody>
</table>

