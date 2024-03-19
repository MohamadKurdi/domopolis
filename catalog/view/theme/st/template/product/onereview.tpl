<?php if ($reviews) { ?>
	<div class="reviews-col__head">
		<h4 class="title"><?php echo $text_retranslate_82; ?></h4>
		<div class="add-rev">
			<a class="btn do-popup-element" data-target="formRev"><?php echo $text_retranslate_83; ?></a>
		</div>
	</div>
	<?php if (!empty($text_retranslate_reward_review)){ ?>
		<p class="txt-bonus">
			<?php echo $text_retranslate_reward_review; ?>
		</p>
	<?php  } ?>
	
	<?php 
		foreach ($reviews as $onereview){ ?>

		<?php include(dirname(__FILE__).'/../structured/review_block.tpl'); ?>

	<?php } ?>
	
	<div class="show-all-rev">
		<a class="reviews_btn"> <?php echo $text_retranslate_84; ?>
			<svg width="23" height="22" viewbox="0 0 23 22" fill="none"
			xmlns="http://www.w3.org/2000/svg">
				<path d="M11.5 15L15.5 11M15.5 11L11.5 7M15.5 11H7.5M21.5 11C21.5 16.5228 17.0228 21 11.5 21C5.97715 21 1.5 16.5228 1.5 11C1.5 5.47715 5.97715 1 11.5 1C17.0228 1 21.5 5.47715 21.5 11Z"
				stroke="#1f9f9d" stroke-width="2" stroke-linecap="round"
				stroke-linejoin="round"></path>
			</svg>
		</a>
	</div>
<? } else { ?>
	<div class="reviews-col__head">
		<h4 class="title"><?php echo $text_retranslate_85; ?></h4>
	</div>
	<?php if (!empty($text_retranslate_reward_review)){ ?>
		<p class="txt-bonus">
			<?php echo $text_retranslate_reward_review; ?>
		</p>
	<?php  } ?>
	<?php include(dirname(__FILE__).'/../structured/review_form.tpl'); ?> 


<? } ?>