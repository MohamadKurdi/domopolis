<?php if ($reviews) { ?>
	<div class="reviews-col__head">
		<h4 class="title"><?php echo $text_retranslate_82; ?></h4>
		<div class="add-rev">
			<a class="btn do-popup-element" data-target="formRev"><?php echo $text_retranslate_83; ?></a>
		</div>
	</div>
	<?php 
		foreach ($reviews as $onereview){ ?>

		<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/review_block.tpl')); ?>

	<?php } ?>
	
	<div class="show-all-rev">
		<a class="reviews_btn"> <?php echo $text_retranslate_84; ?>
		</a>
	</div>
<? } else { ?>
	<div class="reviews-col__head">
		<h4 class="title"><?php echo $text_retranslate_85; ?></h4>
	</div>
	<p class="txt-bonus"><i class="fas fa-gift"></i><span> + 10 бонусных</span> ₴ за отзыв <button data-target="info_popup" id="info-bonus-btn" class="do-popup-element">i</button></p>
	<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/review_form.tpl')); ?> 


<? } ?>