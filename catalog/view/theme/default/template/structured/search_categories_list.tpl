<?php if ($top_found_cmas) { ?>
	<div class="wrap">
		<div class="tags">				<?php echo $text_retranslate_go2cat; ?>
			<div class="tags__row">
				<?php foreach ($top_found_cmas as $top_found_cma) { ?>
		
					<a href="<? echo $top_found_cma['href']; ?>" class="tags__link subcategory-intersection <? if(!empty($top_found_cma['active'])) { ?>active<? } ?>" ><? echo $top_found_cma['name']; ?> 
						<?php if (!empty($top_found_cma['count'])) { ?><span class="badge badge-info"><? echo $top_found_cma['count']; ?></span><? } ?>
					</a>
				
				<? } ?>
			</div>
		</div>
	</div>	
<?php } ?>