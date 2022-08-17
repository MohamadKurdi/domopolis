<?php if ($review) { ?>
	<div class="reviews-col__head">
		<h4 class="title"><?php echo $text_retranslate_82; ?></h4>
		<div class="add-rev">
			<a class="btn do-popup-element" data-target="formRev"><?php echo $text_retranslate_83; ?></a>
		</div>
	</div>
	<?php include(dirname(__FILE__).'/../structured/review_block.tpl'); ?>
	<div class="show-all-rev">
		<a class="reviews_btn"> <?php echo $text_retranslate_84; ?>
			<svg width="23" height="22" viewbox="0 0 23 22" fill="none"
			xmlns="http://www.w3.org/2000/svg">
				<path d="M11.5 15L15.5 11M15.5 11L11.5 7M15.5 11H7.5M21.5 11C21.5 16.5228 17.0228 21 11.5 21C5.97715 21 1.5 16.5228 1.5 11C1.5 5.47715 5.97715 1 11.5 1C17.0228 1 21.5 5.47715 21.5 11Z"
				stroke="#51A881" stroke-width="2" stroke-linecap="round"
				stroke-linejoin="round"></path>
			</svg>
		</a>
	</div>
<? } else { ?>
	<div class="reviews-col__head">
		<h4 class="title"><?php echo $text_retranslate_85; ?></h4>
	</div>
	<p class="txt-bonus"><i class="fas fa-gift"></i><span> + 10 бонусных</span> ₴ за отзыв <button data-target="info_popup" id="info-bonus-btn" class="do-popup-element">i</button></p>
	<?php include(dirname(__FILE__).'/../structured/review_form.tpl'); ?> 


<? } ?>