<div class="reviews-col__item">
	<div class="reviews__author-name">
		<span class="rate rate-<?php echo $onereview['rating']; ?>"></span><span class="name"><? echo $onereview['author']; ?></span> <span class="date"><? echo $onereview['date_added']; ?></span>
			
	</div>
	<div class="reviews__desc">
		<?php echo $onereview['text']; ?>
	</div>
	
	<?php if ($onereview['good'] || $onereview['bads']) { ?>
		<div class="ratings-item-good-bad">
			<div class="good">
				<ul>
					<?php $goodonce = explode("\n", nl2br($onereview['good'])) ?>
					<?php foreach($goodonce as $good_item){ ?>
						<?php if($good_item != ''){ ?>
							<li><?php echo $good_item;?></li>
						<?php }?>
					<?php }?>
				</ul>				
			</div>
			<div class="bad">									
				<ul>
					<?php $badonce = explode("\n", nl2br($onereview['bads'])) ?>
					<?php foreach($badonce as $bad_item){ ?>
						<?php if($bad_item != ''){ ?>
							<li><?php echo $bad_item;?></li>
						<?php }?>
					<?php }?>
				</ul>
			</div>
			<div class="clearfix"></div>
		</div>
	<? } ?>	
	<?php if ($onereview['addimage']) { ?>
		<div class="reviews__img">
			<img src="<?php echo $onereview['addimage']; ?>"  onclick="colorbox(this)" class="colorbox"  style="width:150px;" />
		</div>

	<?php } ?>
</div>
