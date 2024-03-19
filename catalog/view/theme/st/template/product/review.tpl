<?php if (!empty($reviews)) { ?>
	<div class="reviews-col__head">
		<h4 class="title"><?php echo $text_retranslate_82; ?></h4>
		<div class="add-rev">
			<a href="javascript:void(0)" class="btn do-popup-element" data-target="formRev"><?php echo $text_retranslate_83; ?></a>
		</div>
	</div>
	
	<?php foreach ($reviews as $onereview) { ?>
			
			<?php include(dirname(__FILE__).'/../structured/review_block.tpl'); ?>
		
	<?php } ?>
	
	<div class="pagination"><?php echo $pagination; ?></div>
	<script type="text/javascript">
		initPopups();
	</script>
<?php } else { ?>
<div class="reviews-col__head">
	<h4 class="title"><?php echo $text_retranslate_87; ?></h4>
</div>
<div id="form-review" class="<?php if ($reviews) { ?>hidden<? } ?>">
	<?php include(dirname(__FILE__).'/../structured/review_form.tpl'); ?>
</div>
<?php } ?>
