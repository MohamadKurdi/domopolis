<?php if (!empty($intersections)) { ?>
	<div class="wrap">
		<div class="tags">
			<div class="tags__row">
				<?php foreach ($intersections as $intersection) { ?>
		
					<a href="<? echo $intersection['href']; ?>" class="tags__link subcategory-intersection <? if($intersection['active']) { ?>active<? } ?>" title="<? echo $intersection['name']; ?>"><? echo $intersection['name']; ?> 
						<?php if (!empty($intersection['count'])) { ?><span class="badge badge-info"><? echo $intersection['count']; ?></span><? } ?>
					</a>
				
				<? } ?>
				
				<?php if (!empty($clear_url)) { ?>
					<a href="<? echo $clear_url; ?>" class="tags__link subcategory-intersection subcategory-intersection-clear" title=""><i class="fas fa-times"></i></a>
				<? } ?>
			</div>
		</div>
	</div>
<?php } ?>

<?php if (!empty($intersections_l2)) { ?>
	<div class="wrap">
		<div class="tags">
			<div class="tags__row">
				<?php foreach ($intersections_l2 as $intersection_l2) { ?>
		
					<a href="<? echo $intersection_l2['href']; ?>" class="tags__link subcategory-intersection <? if($intersection_l2['active']) { ?>active<? } ?>" title="<? echo $intersection_l2['name']; ?>"><? echo $intersection_l2['name']; ?></a>
				
				<? } ?>
			</div>
		</div>
	</div>
	
<?php } ?>