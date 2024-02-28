<form id="category_search_words_form">
	<table class="form" id="search_words" >
		<thead>
			<tr>
				<td class="center" style="width:1px" width="1">					
				</td>
				<td class="center" style="width:120px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Вид запроса</span>
				</td>
				<td class="center" style="max-width:400px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Слово или ссылка</span>
				</td>
				<td class="center" style="min-width:150px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Валидация названия</span>
				</td>
				<td class="center" style="width:120px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сортировка</span>
				</td>
				<td class="center" style="width:150px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Диапазон цены</span>
				</td>
				<td class="center" style="width:100px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Офферов</span>
				</td>
				<td class="center" style="width:70px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#009fd5; color:#FFF"><i class="fa fa-star"></i> Prime</span>
				</td>
				<td class="center" style="width:80px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Рейтинг</span>
				</td>
				<td class="center" style="width:80px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Отзывов</span>
				</td>
				<td class="center" style="width:60px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-refresh"></i> Авто</span>
				</td>
				<td class="center" style="width:60px; border-left:1px dashed grey;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-clock-o"></i></span>
				</td>
				<td class="center" style="width:50px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><i class="fa fa-list"></i></span>
				</td>
				<td class="center" style="width:70px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF"><i class="fa fa-list"></i></span>
				</td>
				<td class="center" style="width:50px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF"><i class="fa fa-picture-o"></i></span>
				</td>
				<td class="center" style="width:50px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-plus"></i></span>
				</td>
				<td class="center" style="width:60px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-user-plus"></i></span>
				</td>
			</tr>						
		</thead>
		<tbody>
			<?php foreach ($category_search_words as $search_word) { ?>
				<tr style="border-bottom:1px dashed grey;">
					<td class="center" style="width:1px" width="1">		
						<input class="checkbox" type="checkbox" name="checkboxes[<?php echo $search_word['category_search_word_id']; ?>]" id="checkbox-<?php echo $search_word['category_search_word_id']; ?>" value="<?php echo $search_word['category_search_word_id']; ?>">
						<label for="checkbox-<?php echo $search_word['category_search_word_id']; ?>"></label>
					</td>
					<td class="center">
						<b>
							<?php echo \hobotix\RainforestAmazon::searchPageTypes[$search_word['category_word_type']]['name']; ?>							
						</b>
					</td>
					<td class="center"  style="max-width:400px;" >
						<div style="overflow-x:scroll; min-height:60px; text-align:left;">
						<?php if (!empty($search_word['category_search_word'])) { ?>
							<small><?php echo $search_word['category_search_word']; ?></small>
						<?php } elseif (!empty($search_word['category_word_category'])) { ?>
							<small><?php echo $search_word['category_word_category']; ?></small>
						<?php } ?>
						</div>
					</td>
					<td class="center">
						<?php echo $search_word['category_search_exact_words']; ?>
					</td>
					<td class="center">
						<b><?php echo \hobotix\RainforestAmazon::searchSorts[$search_word['category_search_sort']]['name']; ?></b>
					</td>
					<td class="center">
						<?php echo $search_word['category_search_min_price']; ?> - <?php echo $search_word['category_search_max_price']; ?>
					</td>
					<td class="center">
						
						<input class="checkbox" type="checkbox" name="offers[<?php echo $search_word['category_search_word_id']; ?>]" id="offers-<?php echo $search_word['category_search_word_id']; ?>" value="<?php echo $search_word['category_search_word_id']; ?>">
						<label for="offers-<?php echo $search_word['category_search_word_id']; ?>"></label>&nbsp;&nbsp;
						
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $search_word['category_search_min_offers']; ?></span>
					</td>
					<td class="center">
						<? if ($search_word['category_search_has_prime']) { ?>
							<i class="fa fa-star" style="color:#009fd5"></i>
						<? } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<? } ?>
					</td>
					<td class="center">
						<? if ((float)$search_word['category_search_min_rating'] > 0) { ?>
							min <?php echo $search_word['category_search_min_rating']; ?>
						<? } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<? } ?>
					</td>
					<td class="center">
						<? if ((float)$search_word['category_search_min_reviews'] > 0) { ?>
							min <?php echo $search_word['category_search_min_reviews']; ?>
						<? } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<? } ?>
					</td>
					<td class="center">
						<? if ((float)$search_word['category_search_auto'] > 0) { ?>
							<i class="fa fa-check-circle" style="color:#4ea24e"></i>
						<? } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<? } ?>
					</td>
					<td class="center" style="border-left:1px dashed grey;">
						<?php if ($search_word['category_word_last_search'] == '0000-00-00 00:00:00') { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<?php } else { ?>
							<small><?php echo date('Y-m-d', strtotime($search_word['category_word_last_search'])); ?></small><br />
							<small><?php echo date('H:i:s', strtotime($search_word['category_word_last_search'])); ?></small>
						<?php } ?>
					</td>
					<td class="center">
						<input type="number" step="1" name="pages[<?php echo $search_word['category_search_word_id']; ?>]" id="page-<?php echo $search_word['category_search_word_id']; ?>" value="<?php echo ((int)$search_word['category_word_pages_parsed'] + 1); ?>" size="3" />
					</td>
					<td class="center">						
						<?php if ($search_word['category_word_total_pages']) { ?>
						<div style="width:70px;">						
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF" id="small-category-word-pages-parsed-<?php echo $search_word['category_search_word_id']; ?>"><?php echo $search_word['category_word_pages_parsed'] ?></small> / 
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF" id="small-category-word-total-pages-<?php echo $search_word['category_search_word_id']; ?>"><?php echo $search_word['category_word_total_pages'] ?></small> 		
						</div>							
						<?php } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<?php } ?>
					</td>
					<td class="center">
						<?php if ($search_word['category_word_total_products']) { ?>
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF" id="small-category-word-total-products-<?php echo $search_word['category_search_word_id']; ?>"><?php echo $search_word['category_word_total_products'] ?></small>
						<?php } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<?php } ?>	
					</td>
					<td class="center">
						<?php if ($search_word['category_word_product_added']) { ?>
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF" id="small-category-word-products-added-<?php echo $search_word['category_search_word_id']; ?>"><?php echo $search_word['category_word_product_added'] ?></small>
						<?php } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<?php } ?>		
					</td>
					<td class="center">
						<?php if ($search_word['category_word_user']) { ?>
							<small><?php echo $search_word['category_word_user'] ?></small>										
						<?php } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<?php } ?>	
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</form>