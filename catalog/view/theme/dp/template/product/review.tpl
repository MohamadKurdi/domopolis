<?php if (!empty($reviews)) { ?>
	<div class="leave_review_wrap">
		<div class="reviews-col__head">
			<h4 class="title">Ваш відгук</h4>
		</div>

		<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/review_form.tpl')); ?>
	</div>
	

	<div class="reviews-col__head">
		<h4 class="title"><?php echo $text_retranslate_82; ?></h4>
	</div>
	<?php foreach ($reviews as $onereview) { ?>
			
			<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/review_block.tpl')); ?>
		
	<?php } ?>
	
	<div class="pagination"><?php echo $pagination; ?></div>
	<script type="text/javascript">
		initPopups();
	</script>
<?php } else { ?>
<div class="reviews-col__head">
	<h4 class="title"><?php echo $text_retranslate_87; ?></h4>
</div>
<div id="form-review"  class="leave_review_wrap <?php if ($reviews) { ?>hidden<? } ?>">
	<div class="reviews-col__head">
		<h4 class="title">Ваш відгук</h4>
	</div>  
	<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/review_form.tpl')); ?>
</div> 

<?php } ?>
