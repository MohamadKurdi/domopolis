<?php if (count(${'custom_' . $custom_type})) { ?>
    <div data-action="<?php echo $simple_action; ?>" id="<?php echo $form_id ?>" method="POST">        
		<?php foreach (${'custom_' . $custom_type} as $id => $field) { ?>
			<div>
				<?php if ($field['type'] == 'text') { ?>
					<span style="width:200px; text-align:left; font-weight:700; font-size:11px;"><?php echo $field['label']; ?></span>: 
					<span id="value_<?php echo $field['id'] ?>" style="font-size:11px;"><?php echo $field['value'] ?></span>
					<input style="width:90%;" type="hidden" name="<?php echo $field['id'] ?>" value="<?php echo $field['value'] ?>" />
				<?php } ?>
			</div>
		<?php } ?>
	</div>		
<?php } ?>
<script type="text/javascript">
	function submit_<?php echo $form_id ?>() {
		var data = $('#<?php echo $form_id ?>').find('input,select,textarea').serialize();
		
		$.ajax({
			url: '<?php echo htmlspecialchars_decode($simple_action) ?>',
			data: data,
			type: 'POST',
			dataType: 'text',
			beforeSend: function() {
				$('#<?php echo $form_id ?> a.button').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
			},      
			success: function(data) {
				$('#<?php echo $form_id ?>').parents('.simple-container').html(data);
				$('#<?php echo $form_id ?> span.wait').remove();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				$('#<?php echo $form_id ?> span.wait').remove();
			}
		});
	}
	$(function(){
		$('.simple-custom-form .datepicker').datepicker();
	});
</script>