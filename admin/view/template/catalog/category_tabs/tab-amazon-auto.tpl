<div id="tab-amazon-auto">
	<table class="form" id="category_search_words" >
		<thead>
			<tr>
				<td class="center" style="width:120px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Вид автоподбора</span>
				</td>
				<td class="center">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Слово, ссылка или категория</span>
				</td>
				<td class="center" style="min-width:250px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Валидация названия</span>
				</td>
				<td class="center" style="width:150px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сортировка</span>
				</td>
				<td class="center" style="width:120px;">
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
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF"><i class="fa fa-list"></i></span>
				</td>
				<td class="center" style="width:50px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF"><i class="fa fa-picture-o"></i></span>
				</td>
				<td class="center" style="width:50px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-plus"></i></span>
				</td>
				<td class="center" style="width:50px;">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-user-plus"></i> User</span>
				</td>
			</tr>						
		</thead>
		<?php $category_search_words_row = 0; ?>
		<script type="text/javascript">
			function refresh_counters(category_search_words_row){
				$.ajax({
					url: 'index.php?route=kp/amazon/countPagination&token=<?php echo $token; ?>',
					type: 'POST',
					dataType: 'JSON',
					data:{
						'word_or_uri' : $('#input_category_search_words_' + category_search_words_row + '_search_word').val(),
						'category_id' : $('#input_category_search_words_' + category_search_words_row + '_hidden_category_id').val(),
						'type' 		  : $('#select_category_search_words_' + category_search_words_row + '_word_type').val(),
						'sort' 		  : $('#select_category_search_words_' + category_search_words_row + '_category_search_sort').val()
					},
					beforeSend: function(){
						$('#span_category_word_total_pages_' + category_search_words_row).html('<i class="fa fa-spinner fa-spin"></i>');
						$('#span_category_word_total_products_' + category_search_words_row).html('<i class="fa fa-spinner fa-spin"></i>');
					},
					success: function(json){
						if (json.total_pages){
							$('#span_category_word_total_pages_' + category_search_words_row).html(json.total_pages);
						} else {
							$('#span_category_word_total_pages_' + category_search_words_row).html('<i class="fa fa-times-circle" style="color:#cf4a61"></i>');
						}

						if (json.total_results){
							$('#span_category_word_total_products_' + category_search_words_row).html(json.total_results);
						} else {
							$('#span_category_word_total_products_' + category_search_words_row).html('<i class="fa fa-times-circle" style="color:#cf4a61"></i>');
						}
					},
					error: function(json){
						$('#span_category_word_total_pages_' + category_search_words_row).html('<i class="fa fa-times-circle" style="color:#cf4a61"></i>');
						$('#span_category_word_total_products_' + category_search_words_row).html('<i class="fa fa-times-circle" style="color:#cf4a61"></i>');
					}
				});
			}

			function setAutocomplete(category_search_words_row){
				var input = $('#input_category_search_words_' + category_search_words_row + '_category');

				$('#input_category_search_words_' + category_search_words_row + '_category').autocomplete({
					delay: 500,
					source: function(request, response) {       
						$.ajax({
							url: 'index.php?route=catalog/category/amazon_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term) + '&type=' + encodeURIComponent($('#select_category_search_words_' + category_search_words_row + '_word_type').val()),
							dataType: 'json',
							success: function(json) {
								response($.map(json, function(item) {
									return {
										label: item.path,
										value: item.path,
										id:    item.id,
									}
								}));
							}
						});
					},
					select: function(event, ui) {										
						$('#input_category_search_words_' + category_search_words_row + '_hidden_category_id').val(ui.item.id);
						$('#input_category_search_words_' + category_search_words_row + '_search_word').val('');											
						$('#input_category_search_words_' + category_search_words_row + '_category').val(ui.item.value);
					},
					focus: function(event, ui) {
						return false;
					}
				});
			}

			function clean(type, category_search_words_row){
				if (type == 'word'){
					$('#input_category_search_words_' + category_search_words_row + '_search_word').val('');
				}

				if (type == 'category'){
					$('#input_category_search_words_' + category_search_words_row + '_hidden_category_id').val('');											
					$('#input_category_search_words_' + category_search_words_row + '_category').val('');
				}
			}

			function selectOnce(value, category_search_words_row){
				if ((value == 'bestsellers') || (value == 'standard')){
					$('#span_category_search_words_' + category_search_words_row + '_category_id').show();
					$('#span_category_search_words_' + category_search_words_row + '_search_word').show();
				} else if (value != 'disabled') {
					$('#span_category_search_words_<?php echo $category_search_words_row; ?>_category_id').trigger('click');
					$('#span_category_search_words_' + category_search_words_row + '_search_word').hide();
					$('#div_category_search_words_' + category_search_words_row + '_category_id').hide();											
					$('#div_category_search_words_' + category_search_words_row + '_search_word').show();
					clean('category', category_search_words_row);
				}
			}

			function setSelectChange(category_search_words_row){
				let select = $('#select_category_search_words_' + category_search_words_row + '_word_type');

				select.on('change', function(){
					selectOnce(select.val(), category_search_words_row);
				});
			}
		</script>

		<?php foreach ($category_search_words as $category_search_word) { ?>
			<tbody id="category_search_words-row<?php echo $category_search_words_row; ?>">
				<tr style="border-bottom:1px dashed grey;">
					<td class="center" style="width:100px;">
						<div style="margin-bottom:10px;">
						<select id="select_category_search_words_<?php echo $category_search_words_row; ?>_word_type" name="category_search_words[<?php echo $category_search_words_row; ?>][category_word_type]">
							<option value="disabled" <?php if ($category_search_word['category_word_type'] == 'disabled') { ?>selected="selected"<?php } ?>>Отключить</option>
							<?php foreach (\hobotix\RainforestAmazon::searchPageTypes as $key => $value) { ?>
								<option value="<?php echo $key; ?>" <?php if ($category_search_word['category_word_type'] == $key) { ?>selected="selected"<?php } ?>><?php echo $value['name']; ?></option>
							<?php } ?>
						</select>
						</div>
						<div style="text-align:center; margin-bottom:10px;">
							<a onclick="refresh_counters('<?php echo $category_search_words_row; ?>')" class="button"><i class="fa fa-refresh"></i></a>
							<a onclick="$('#category_search_words-row<?php echo $category_search_words_row; ?>').remove();" class="button"><i class="fa fa-times-circle"></i></a>
						</div>
					</td>
					<td class="center">
						<div id="div_category_search_words_<?php echo $category_search_words_row; ?>_search_word" <?php if (empty($category_search_word['category_search_word']) && !empty($category_search_word['category_word_category_id'])) { ?>style="display:none"<?php } ?>>
							<textarea rows="4" style="width:90%;" type="text" id="input_category_search_words_<?php echo $category_search_words_row; ?>_search_word" name="category_search_words[<?php echo $category_search_words_row; ?>][category_search_word]" /><?php echo $category_search_word['category_search_word'] ?></textarea>

							<span class="help">это поисковое слово, либо ссылка <span id="span_category_search_words_<?php echo $category_search_words_row; ?>_search_word" style="border-bottom:1px dashed grey; cursor: pointer; color:#7F00FF" onclick="$('#div_category_search_words_<?php echo $category_search_words_row; ?>_search_word').hide(); $('#div_category_search_words_<?php echo $category_search_words_row; ?>_category_id').show(); clean('word', <?php echo $category_search_words_row; ?>)">сменить на категорию</span></span>
						</div>

						<div id="div_category_search_words_<?php echo $category_search_words_row; ?>_category_id" <?php if (empty($category_search_word['category_word_category_id'])) { ?>style="display:none"<?php } ?>>
							<input style="width:90%;" type="text" placeholder="Начни заполнять, чтоб выбрать категорию amazon, только на языке <?php echo $this->config->get('config_rainforest_source_language')?>" id="input_category_search_words_<?php echo $category_search_words_row; ?>_category" name="category_search_words[<?php echo $category_search_words_row; ?>][category_word_category]" value="<?php echo $category_search_word['category_word_category'] ?>" />

							<input type="hidden" id="input_category_search_words_<?php echo $category_search_words_row; ?>_hidden_category_id" name="category_search_words[<?php echo $category_search_words_row; ?>][category_word_category_id]" value="<?php echo $category_search_word['category_word_category_id'] ?>" />

							<span class="help">это подбор категории <span id="span_category_search_words_<?php echo $category_search_words_row; ?>_category_id" style="border-bottom:1px dashed grey; cursor: pointer; color:#7F00FF" onclick="$('#div_category_search_words_<?php echo $category_search_words_row; ?>_search_word').show(); $('#div_category_search_words_<?php echo $category_search_words_row; ?>_category_id').hide(); clean('category', <?php echo $category_search_words_row; ?>)">сменить на поисковое слово или ссылку</span></span>	
						</div>										
					</td>
					<td class="center">
						<textarea rows="4" style="width:90%;" type="text" id="input_category_search_words_<?php echo $category_search_words_row; ?>_exact_words" name="category_search_words[<?php echo $category_search_words_row; ?>][category_search_exact_words]" /><?php echo $category_search_word['category_search_exact_words'] ?></textarea>
						<span class="help">каждое с новой строки</span>
					</td>

					<td class="center" style="width:100px;">
						<select id="select_category_search_words_<?php echo $category_search_words_row; ?>_category_search_sort" name="category_search_words[<?php echo $category_search_words_row; ?>][category_search_sort]">
							<?php foreach (\hobotix\RainforestAmazon::searchSorts as $key => $value) { ?>
								<option value="<?php echo $key; ?>" <?php if ($category_search_word['category_search_sort'] == $key) { ?>selected="selected"<?php } ?>><?php echo $value['name']; ?></option>
							<?php } ?>
						</select>
					</td>
					<td class="center">
						<div style="margin-bottom:5px;">
							<input type="number" step=".1" name="category_search_words[<?php echo $category_search_words_row; ?>][category_search_min_price]" value="<?php echo $category_search_word['category_search_min_price']; ?>" size="10" />
						</div>
						<div>
							<input type="number" step=".1" name="category_search_words[<?php echo $category_search_words_row; ?>][category_search_max_price]" value="<?php echo $category_search_word['category_search_max_price']; ?>" size="10" />
						</div>
					</td>
					<td class="center">
						<input type="number" step="1" name="category_search_words[<?php echo $category_search_words_row; ?>][category_search_min_offers]" value="<?php echo $category_search_word['category_search_min_offers']; ?>" size="3" />
					</td>
					<td class="center">
						<input class="checkbox" type="checkbox" name="category_search_words[<?php echo $category_search_words_row; ?>][category_search_has_prime]" value="1" id="checkbox_<?php echo $category_search_words_row; ?>_category_search_has_prime" <?php if ($category_search_word['category_search_has_prime']) { ?>checked="checked"<?php } ?>/>
						<label for="checkbox_<?php echo $category_search_words_row; ?>_category_search_has_prime"></label>
					</td>

					<td class="center">
						<input type="number" step=".1" name="category_search_words[<?php echo $category_search_words_row; ?>][category_search_min_rating]" value="<?php echo $category_search_word['category_search_min_rating']; ?>" size="4" />
					</td>
					<td class="center">
						<input type="number" step="1" name="category_search_words[<?php echo $category_search_words_row; ?>][category_search_min_reviews]" value="<?php echo $category_search_word['category_search_min_reviews']; ?>" size="3" />
					</td>

					<td class="center">
						<input class="checkbox" type="checkbox" name="category_search_words[<?php echo $category_search_words_row; ?>][category_search_auto]" value="1" id="checkbox_<?php echo $category_search_words_row; ?>_category_search_auto" <?php if ($category_search_word['category_search_auto']) { ?>checked="checked"<?php } ?> />
						<label for="checkbox_<?php echo $category_search_words_row; ?>_category_search_auto"></label>
					</td>

					<td class="center" style="border-left:1px dashed grey;">
						<input type="hidden" name="category_search_words[<?php echo $category_search_words_row; ?>][category_word_last_search]" value="<?php echo $category_search_word['category_word_last_search'] ?>" />	
						<?php if ($category_search_word['category_word_last_search'] == '0000-00-00 00:00:00') { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<?php } else { ?>
							<small><?php echo date('Y-m-d', strtotime($category_search_word['category_word_last_search'])); ?></small><br />
							<small><?php echo date('H:i:s', strtotime($category_search_word['category_word_last_search'])); ?></small>
						<?php } ?>
					</td>
					<td class="center">
						<input type="hidden" name="category_search_words[<?php echo $category_search_words_row; ?>][category_word_total_pages]" value="<?php echo $category_search_word['category_word_total_pages'] ?>" />
						<input type="hidden" name="category_search_words[<?php echo $category_search_words_row; ?>][category_word_pages_parsed]" value="<?php echo $category_search_word['category_word_pages_parsed'] ?>" />

						<span id="span_category_word_total_pages_<?php echo $category_search_words_row; ?>">
							<?php if ($category_search_word['category_word_total_pages']) { ?>
								<small class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $category_search_word['category_word_pages_parsed'] ?></small> / <small class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF"><?php echo $category_search_word['category_word_total_pages'] ?></small> 									
							<?php } else { ?>
								<i class="fa fa-times-circle" style="color:#cf4a61"></i>
							<?php } ?>
						</span>
					</td>
					<td class="center">
						<input type="hidden" name="category_search_words[<?php echo $category_search_words_row; ?>][category_word_total_products]" value="<?php echo $category_search_word['category_word_total_products'] ?>" />	

						<span id="span_category_word_total_products_<?php echo $category_search_words_row; ?>">
							<?php if ($category_search_word['category_word_total_products']) { ?>
								<small class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $category_search_word['category_word_total_products'] ?></small>
							<?php } else { ?>
								<i class="fa fa-times-circle" style="color:#cf4a61"></i>
							<?php } ?>		
						</span>							
					</td>
					<td class="center">
						<input type="hidden" name="category_search_words[<?php echo $category_search_words_row; ?>][category_word_product_added]" value="<?php echo $category_search_word['category_word_product_added'] ?>" />	

						<?php if ($category_search_word['category_word_product_added']) { ?>
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $category_search_word['category_word_product_added'] ?></small>
						<?php } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<?php } ?>									
					</td>
					<td class="center">
						<input type="hidden" name="category_search_words[<?php echo $category_search_words_row; ?>][category_word_user_id]" value="<?php echo $category_search_word['category_word_user_id'] ?>" />	
						<?php if ($category_search_word['category_word_user']) { ?>
							<small><?php echo $category_search_word['category_word_user'] ?></small>										
						<?php } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<?php } ?>									
					</td>									
				</tr>
			</tbody>
			<script>
				setAutocomplete(<?php echo $category_search_words_row; ?>);
				setSelectChange(<?php echo $category_search_words_row; ?>);
				selectOnce('<?php echo $category_search_word['category_word_type']; ?>', <?php echo $category_search_words_row; ?>);
			</script>
			<?php $category_search_words_row++; ?>
		<?php } ?>
		<tfoot>
			<tr>
				<td colspan="14"></td>
				<td class="right" colspan="1">
					<a onclick="addCategorySearchWord();" class="button">Добавить</a>									
				</td>
			</tr>
		</tfoot>
	</table>

	<script>									
		var category_search_words_row = <?php echo $category_search_words_row; ?>;

		function addCategorySearchWord() {
			html  = '<tbody id="category_search_words-row' + category_search_words_row + '">';
			html += '  <tr>';

			html += '    <td class="center">';
			html += '    <div style="margin-bottom:10px;">';
			html += '      <select id="select_category_search_words_' + category_search_words_row + '_word_type" name="category_search_words[' + category_search_words_row + '][category_word_type]">';
			html += '        <option value="disabled">Отключить</option>';
			html += '        <?php foreach (\hobotix\RainforestAmazon::searchPageTypes as $key => $value) { ?>';
			html += '          <option value="<?php echo $key; ?>" <?php if ($key == 'search') { ?>selected="selected"<?php } ?>><?php echo $value['name']; ?></option>';
			html += '        <?php } ?>';
			html += '      </select>';
			html += '      </div>'; 
			html += '    <div style="margin-bottom:10px; text-align:center">';
			html += '    	<a onclick="$(\'#category_search_words-row' + category_search_words_row + '\').remove();" class="button"><i class="fa fa-times-circle"></i></a>'; 
			html += '    </div>'; 
			html += '    </td>';

			html += '    <td class="center">';
			html += '      <div id="div_category_search_words_' + category_search_words_row + '_search_word">';
			html += '        <textarea rows="4" style="width:90%;" type="text" id="input_category_search_words_' + category_search_words_row + '_search_word" name="category_search_words[' + category_search_words_row + '][category_search_word]"></textarea>';
			html += '        <span class="help">это поисковое слово, либо ссылка <span id="span_category_search_words_' + category_search_words_row + '_search_word" style="border-bottom:1px dashed grey; cursor: pointer; color:#7F00FF" onclick="$(\'#div_category_search_words_' + category_search_words_row + '_search_word\').hide(); $(\'#div_category_search_words_' + category_search_words_row + '_category_id\').show(); clean(\'word\', '+ category_search_words_row +')">сменить на категорию</span></span>';
			html += '      </div>';

			html += '      <div id="div_category_search_words_' + category_search_words_row + '_category_id" style="display:none">';
			html += '        <input style="width:90%;" type="text" placeholder="Начни заполнять, чтоб выбрать категорию amazon, только на языке <?php echo $this->config->get('config_rainforest_source_language')?>" id="input_category_search_words_' + category_search_words_row + '_category" name="category_search_words[' + category_search_words_row + '][category_word_category]">';
			html += '        <input type="hidden" id="input_category_search_words_' + category_search_words_row + '_hidden_category_id" name="category_search_words[' + category_search_words_row + '][category_word_category_id]" value="">';
			html += '        <span class="help">это подбор категории <span id="span_category_search_words_' + category_search_words_row + '_category_id" style="border-bottom:1px dashed grey; cursor: pointer; color:#7F00FF" onclick="$(\'#div_category_search_words_' + category_search_words_row + '_search_word\').show(); $(\'#div_category_search_words_' + category_search_words_row + '_category_id\').hide(); clean(\'category\', ' + category_search_words_row + ')">сменить на поисковое слово или ссылку</span></span>';
			html += '      </div>';  
			html += '    </td>';

			html += '    <td class="center">';
			html += '      <textarea rows="4" style="width:90%;" type="text" id="input_category_search_words_' + category_search_words_row + '_exact_words" name="category_search_words[' + category_search_words_row + '][category_search_exact_words]"></textarea>';
			html += '      <span class="help">каждое с новой строки</span>';
			html += '    </td>';

			html += '    <td class="center">';
			html += '      <select id="select_category_search_words_' + category_search_words_row + '_category_search_sort" name="category_search_words[' + category_search_words_row + '][category_search_sort]">';
			html += '        <?php foreach (\hobotix\RainforestAmazon::searchSorts as $key => $value) { ?>';
			html += '          <option value="<?php echo $key; ?>" <?php if ($key == 'price_high_to_low') { ?>selected="selected"<?php } ?>><?php echo $value['name']; ?></option>';
			html += '        <?php } ?>';
			html += '      </select>';
			html += '    </td>';

			html += '    <td class="center">';
			html += '    	<div style="margin-bottom:5px;">';
			html += '      	<input type="number" step=".1" name="category_search_words[' + category_search_words_row + '][category_search_min_price]" value="<?php echo $this->config->get('config_rainforest_skip_low_price_products')?>" size="10">'; 
			html += '    	</div>';
			html += '    	<div>';
			html += '      <input type="number" step=".1" name="category_search_words[' + category_search_words_row + '][category_search_max_price]" value="<?php echo $this->config->get('config_rainforest_skip_high_price_products')?>" size="10">';
			html += '    	</div>';
			html += '    </td>';

			html += '    <td class="center">';
			html += '      <input type="number" step="1" name="category_search_words[' + category_search_words_row + '][category_search_min_offers]" value="<?php echo $this->config->get('config_rainforest_skip_min_offers_products')?>" size="5">';
			html += '    </td>';

			html += '    <td class="center">';
			html += '      <input class="checkbox" type="checkbox" id="checkbox_' + category_search_words_row + '>_category_search_has_prime" name="category_search_words[' + category_search_words_row + '][category_search_has_prime]" value="1">';
			html += '      <label for="checkbox_' + category_search_words_row + '>_category_search_has_prime"></label>';
			html += '    </td>';

			html += '    <td class="center">';
			html += '      <input type="number" step=".1" name="category_search_words[' + category_search_words_row + '][category_search_min_rating]" value="0" size="4">';
			html += '    </td>';

			html += '    <td class="center">';
			html += '      <input type="number" step="1" name="category_search_words[' + category_search_words_row + '][category_search_min_reviews]" value="0" size="3">'; 
			html += '    </td>';

			html += '    <td class="center">';
			html += '      <input class="checkbox" type="checkbox" id="checkbox_' + category_search_words_row + '>_category_search_auto" name="category_search_words[' + category_search_words_row + '][category_search_auto]" value="1">';
			html += '      <label for="checkbox_' + category_search_words_row + '>_category_search_auto"></label>';
			html += '    </td>';

			html += '    <td class="center" style="border-left:1px dashed grey;">';
			html += '      <input type="hidden" name="category_search_words[' + category_search_words_row + '][category_word_last_search]" value="0000-00-00 00:00:00">';
			html += '      <i class="fa fa-times-circle" style="color:#cf4a61"></i>';
			html += '    </td>';

			html += '    <td class="center">';  
			html += '      <input type="hidden" name="category_search_words[' + category_search_words_row + '][category_word_total_pages]" value="">';
			html += '      <input type="hidden" name="category_search_words[' + category_search_words_row + '][category_word_pages_parsed]" value="">';
			html += '      <span id="span_category_word_total_pages_' + category_search_words_row + '">';
			html += '        <i class="fa fa-times-circle" style="color:#cf4a61"></i>';
			html += '      </span>';
			html += '    </td>';

			html += '    <td class="center">';
			html += '      <input type="hidden" name="category_search_words[' + category_search_words_row + '][category_word_total_products]" value="">';  
			html += '      <span id="span_category_word_total_products_' + category_search_words_row + '">';
			html += '        <i class="fa fa-times-circle" style="color:#cf4a61"></i>';
			html += '      </span>';
			html += '    </td>';

			html += '    <td class="center">';
			html += '      <input type="hidden" name="category_search_words[' + category_search_words_row + '][category_word_product_added]" value="">';
			html += '      <i class="fa fa-times-circle" style="color:#cf4a61"></i>';
			html += '    </td>';

			html += '    <td class="center">';  
			html += '      <input type="hidden" name="category_search_words[' + category_search_words_row + '][category_word_user_id]" value="0">';
			html += '    </td>';

			html += '  </tr>';
			html += '</tbody>';

			$('#category_search_words tfoot').before(html);

			setAutocomplete(category_search_words_row);
			setSelectChange(category_search_words_row);
			selectOnce('search', category_search_words_row);

			category_search_words_row++;
		}
	</script>

	<div style="padding:10px; margin-top:10px; margin-bottom:10px; border:1px dashed grey;">
		<div style="float:left; width:400px; padding:10px; border-right:1px dashed grey;">
			<p><strong>Вы можете создавать разные типы правил для валидации названий товаров</strong></p>

			<p>
				<span style="color:#27ae60"><strong>[обязательное слово]</strong></span> - все эти слова ДОЛЖНЫ быть в названии товара.<br />
				<span style="color:#e67e22"><strong>(необязательное слово)</strong></span> - хотя бы одно из этих слов МОЖЕТ БЫТЬ в названии товара.<br />
				<span style="color:#c0392b"><strong>{запрещённое слово}</strong></span> - ни одно из этих слов НЕ ДОЛЖНО быть в названии товара.<br />
				<br />
				<strong>Примеры</strong>
			</p>

			<p>
				"<span style="color:#27ae60"><strong>[дом, другой] [деревня] [хобот]</strong></span>" - текст должен содержать слова "дом", "другой", "деревня", "хобот".<br />
				"<strong><span style="color:#e67e22">(река,озеро)</span> <span style="color:#c0392b">{море}</span></strong>" - текст должен содержать хотя бы одно из слов "река" или "озеро", но не может содержать слово "море".<br />
				"<strong><span style="color:#c0392b">{грустный}</span> <span style="color:#27ae60">[веселый]</span></strong>" - текст не может содержать слово "грустный", но должен содержать слово "веселый".
			</p>

			<p>
				<strong>Каждое правило вводится с новой строки</strong><br />
				<br />
				<span style="color:#27ae60"><strong>Правильно:</strong></span><br />
				<span style="color:#27ae60">
					<strong>[дом, другой]</strong><br />
					<strong>(река,озеро)</strong><br />
					<strong>(хобот)</strong><br />
					<strong>{море}</strong>
				</span><br /><br />
				<span style="color:#c0392b"><strong>Неправильно:</strong><br />
					<strong>[дом, другой] (хобот)</strong><br />
					<strong>{море}</strong>
				</span>
			</p>
		</div>
		<div style="float:left; width:900px; padding:10px;">
			
			<table class="form">
				<tr>
					<td class="center">
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Правила проверки</span>
					</td>
					<td class="center">
						<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Название товара</span>
					</td>
				</tr>	
				<tr>
					<td>
						<textarea id="rules" name="rules" rows="10" style="width:350px;"></textarea>
						<span class="help" style="cursor:pointer; display:inline-block; border-bottom:1px dashed grey;" onclick="validate_name();">проверка выполняется при вводе, но проверить вручную</span>
					</td>
					<td>
						<textarea id="text" name="text" rows="10" style="width:450px;"></textarea>
						<span class="help" style="cursor:pointer; display:inline-block; border-bottom:1px dashed grey;" onclick="$('#text').load('<?php echo $random_name; ?>', function(){ validate_name(); }); ">случайные 5 названий из базы</span>
					</td>
				</tr>
			</table>
			<div style="min-height: 200px;">
			<table class="form" style="width:800px;">					
				<tbody id="validate_result">

				</tbody>
			</table>
			</div>

			<style>
				#validate_result tr td{font-size:12px;}
				.validate_success{color:#00ad07}
				.validate_fail{color:#cf4a61}
			</style>
			<script>
				$('#rules').on('keyup', function(){ validate_name(); });
				$('#text').on('keyup', function(){ validate_name(); });

				function validate_name(){
					var rules 	= $("#rules").val();
					var text 	= $("#text").val();

					$.ajax({
						url: "<?php echo $validate_name; ?>",
						type: "POST",
						data: { rules: rules, text: text },
						dataType: 'HTML',
						beforeSend: function(){
							$('#validate_result').html('');
						},
						success: function(html) {
							$('#validate_result').html(html);
						},
						error: function(jqXHR, textStatus, errorThrown) {
							$("#validate_result").html("<b>Error:</b> " + errorThrown);
						}
					});
				}

			</script>
		</div>
		<div class="clr"></div>
	</div>

	<h2 style="color:#cf4a61">Устаревшие параметры, используемые для совместимости</h2>
	<table class="form">

		<tr>
			<td style="width:15%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Разрешить загрузку информации о новых</span>									
			</td>
			<td style="width:15%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Разрешить загрузку полной информации</span>									
			</td>
			<td style="width:15%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Конечная категория Amazon</span>									
			</td>
			<td style="width:15%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Дата последней синхронизации</span>
			</td>
			<td style="width:15%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Синхронизированы товары</span>						
			</td>
			<tr></tr>	
			<td style="width:15%">
				<select name="amazon_sync_enable">
					<?php if ($amazon_sync_enable) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				</select>
			</td>							
			<td style="width:15%">
				<select name="amazon_can_get_full">
					<?php if ($amazon_can_get_full) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				</select>
			</td>
			<td style="width:15%">
				<select name="amazon_final_category">
					<?php if ($amazon_final_category) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				</select>
			</td>
			<td style="width:15%">
				<input type="text" name="amazon_last_sync" value="<?php echo $amazon_last_sync; ?>" style="width:150px;"  />
			</td>
			<td style="width:15%">
				<select name="amzn_synced">
					<?php if ($amzn_synced) { ?>
						<option value="1" selected="selected">Да</option>
						<option value="0">Нет</option>
					<?php } else { ?>
						<option value="1">Да</option>
						<option value="0" selected="selected">Нет</option>
					<?php } ?>
				</select>								
			</td>	
		</tr>								
	</table>
</div>