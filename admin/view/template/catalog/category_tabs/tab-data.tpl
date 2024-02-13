<div id="tab-data">
	<table class="form">
		<tr>
			<td><?php echo $entry_parent; ?></td>
			<td>
				<input type="text" name="path" value="<?php echo $path; ?>" size="100" />
				<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
			</td>
		</tr>

		<?php if ($this->config->get('config_rainforest_enable_api')) { ?>
			<tr>
				<td>Родительская Amazon</td>
				<td>
					<input type="text" name="amazon_parent_category_id" value="<?php echo $amazon_parent_category_id; ?>" size="100" />
					<br />
					<span class="help" style="color:#cf4a61"><i class="fa fa-exclamation-triangle"></i> При смене родительской категории магазина НЕОБХОДИМО очистить это поле, иначе категория привяжется заново</span>
					<?php if ($amazon_parent_category_name) { ?>
						<br /><span  style="color:#00ad07"><i class="fa fa-check"></i> родительская Amazon <?php echo $amazon_parent_category_name; ?></span>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>

		<tr><th colspan="2">Акции</th></tr>
		<tr>
			<td>Показывать акции сверху<br /><span class="help">(Автодополнение)</span></td>
			<td><input type="text" name="action" value="" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<div id="category-action" class="scrollbox" style="width:400px;">
					<?php $class = 'odd'; ?>
					<?php foreach ($category_actions as $category_action) { ?>
						<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
						<div id="category-action<?php echo $category_action['action_id']; ?>" class="<?php echo $class; ?>"><?php echo $category_action['name']; ?><img src="view/image/delete.png" alt="" />
							<input type="hidden" name="category_action[]" value="<?php echo $category_action['action_id']; ?>" />
						</div>
					<?php } ?>
				</div>
			</td>
		</tr>

		<tr><th colspan="2">Фильтр (не используется)</th></tr>
		<tr>
			<td><?php echo $entry_filter; ?></td>
			<td><input type="text" name="filter" value="" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<div id="category-filter" class="scrollbox" style="width:400px;">
					<?php $class = 'odd'; ?>
					<?php foreach ($category_filters as $category_filter) { ?>
						<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
						<div id="category-filter<?php echo $category_filter['filter_id']; ?>" class="<?php echo $class; ?>"><?php echo $category_filter['name']; ?><img src="view/image/delete.png" alt="" />
							<input type="hidden" name="category_filter[]" value="<?php echo $category_filter['filter_id']; ?>" />
						</div>
					<?php } ?>
				</div></td>
			</tr>
			<tr>
				<td>Виртуальная родительская категория<span class="help">Только в меню, товары не учитываются</span></td>
				<td><input type="text" name="virtual_path" value="<?php echo $virtual_path; ?>" size="100" />
					<input type="hidden" name="virtual_parent_id" value="<?php echo $virtual_parent_id; ?>" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_store; ?></td>
					<td>
						<div class="scrollbox" style="width:350px;">
							<?php $class = 'even'; ?>											
							<?php foreach ($stores as $store) { ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								<div class="<?php echo $class; ?>">
									<?php if (in_array($store['store_id'], $category_store)) { ?>
										<input id="category_store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
										<label for="category_store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
									<?php } else { ?>
										<input id="category_store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
										<label for="category_store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</td>
				</tr>								
				<tr>
					<td><?php echo $entry_keyword; ?></td>
					<td><?php foreach ($languages as $language) { ?>
						<input type="text" name="keyword[<?php echo $language['language_id']; ?>]" value="<?php if (isset($keyword[$language['language_id']])) { echo $keyword[$language['language_id']]; } ?>" />
						<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
						<?php } ?></td>
					</tr>
					<tr>
						<td><?php echo $entry_image; ?></td>
						<td valign="top">
							<div class="image">
								<img src="<?php echo $thumb; ?>" alt="" id="thumb" />
								<br />
								<input type="text" name="image" value="<?php echo $image; ?>" id="image" />
								<br />
								<a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
							</div>
						</td>
					</tr>
					<tr>
						<td><?php echo $entry_top; ?></td>
						<td><?php if ($top) { ?>
							<input id="checkbox_24" class="checkbox" type="checkbox" name="top" value="1" checked="checked" />
							<label for="checkbox_24"></label>
						<?php } else { ?>
							<input id="checkbox_25" class="checkbox" type="checkbox" name="top" value="1" />
							<label for="checkbox_25"></label>
							<?php } ?></td>
						</tr>

						<tr>
							<td>Домашняя страница</td>
							<td>
								<select name="homepage">
									<?php if ($homepage == 1) { ?>
										<option value="1" selected="selected">Принудительно включить</option>
										<option value="0">Автоматически</option>
										<option value="-1">Исключить</option>
									<?php } elseif ($homepage == 0) { ?>
										<option value="1">Принудительно включить</option>
										<option value="0" selected="selected">Автоматически</option>
										<option value="-1">Исключить</option>
									<?php } elseif ($homepage == -1) { ?>
										<option value="1">Принудительно включить</option>
										<option value="0" >Автоматически</option>
										<option value="-1" selected="selected">Исключить</option>
									<?php } ?>
								</select>

								<br/><span class="help">В модуле "популярные категории" выводятся самые просматриваемые категории, но можно добавить любую категорию вручную</span>
							</td>
						</tr>

						<tr>
							<td>Специальная категория</td>
							<td>
								<select name="special">
									<?php if ($special == 1) { ?>
										<option value="1" selected="selected">Это специальная категория</option>
										<option value="0">Это обычная категория</option>
									<?php } elseif ($special == 0) { ?>
										<option value="1">Это специальная категория</option>
										<option value="0" selected="selected">Это обычная категория</option>										
									<?php } ?>
								</select>

								<br/><span class="help">Специальная категория - исключена из выборок популярных и самых покупаемых в некоторых местах</span>
							</td>
						</tr>

						<tr>
							<td>Популярная категория</td>
							<td>
								<select name="popular">
									<?php if ($popular) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>

								<br/><span class="help">Популярная категория (пока нигде не используется)</span>
							</td>
						</tr>

						<tr>
							<td><?php echo $entry_column; ?></td>
							<td><input type="text" name="column" value="<?php echo $column; ?>" size="1" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_sort_order; ?></td>
							<td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_status; ?></td>
							<td><select name="status">
								<?php if ($status) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="color:red;">Выключить или включить дерево</td>
						<td>
							<i class="fa fa-exclamation-triangle"></i>
							<select name="status_tree">
								<option value="0" selected="selected">Не трогать дерево при нажатии кнопки сохранить</option>
								<option value="1">Выключить/включить всё дерево ниже при нажатии кнопки сохранить</option>
							</select>
						</td>
					</tr>

					<tr>
						<td style="color:red;">Выключить или включить дочерние</td>
						<td>
							<i class="fa fa-exclamation-triangle"></i>
							<select name="status_children">
								<option value="0" selected="selected">Не трогать дочерние при нажатии кнопки сохранить</option>
								<option value="1">Выключить/включить только дочерние при нажатии кнопки сохранить</option>
							</select>
						</td>
					</tr>
				</table>
			</div>

			<script type="text/javascript">
				$('input[name=\'path\']').autocomplete({
					delay: 500,
					source: function(request, response) {		
						$.ajax({
							url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
							dataType: 'json',
							success: function(json) {
								json.unshift({
									'category_id':  0,
									'name':  '<?php echo $text_none; ?>'
								});
								
								response($.map(json, function(item) {
									return {
										label: item.name,
										value: item.category_id
									}
								}));
							}
						});
					},
					select: function(event, ui) {
						$('input[name=\'path\']').val(ui.item.label);
						$('input[name=\'parent_id\']').val(ui.item.value);
						
						return false;
					},
					focus: function(event, ui) {
						return false;
					}
				});
			</script> 
			<script type="text/javascript">
				$('input[name=\'virtual_path\']').autocomplete({
					delay: 500,
					source: function(request, response) {		
						$.ajax({
							url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
							dataType: 'json',
							success: function(json) {
								json.unshift({
									'category_id':  0,
									'name':  '<?php echo $text_none; ?>'
								});
								
								response($.map(json, function(item) {
									return {
										label: item.name,
										value: item.category_id
									}
								}));
							}
						});
					},
					select: function(event, ui) {
						$('input[name=\'virtual_path\']').val(ui.item.label);
						$('input[name=\'virtual_parent_id\']').val(ui.item.value);
						
						return false;
					},
					focus: function(event, ui) {
						return false;
					}
				});
			</script> 	
			<script type="text/javascript">
				$('input[name=\'action\']').autocomplete({
					delay: 100,
					source: function(request, response) {
						$.ajax({
							url: 'index.php?route=catalog/actions/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
							dataType: 'json',
							success: function(json) {		
								response($.map(json, function(item) {
									return {
										label: item.name,
										value: item.action_id
									}
								}));
							}
						});
					}, 
					select: function(event, ui) {
						$('#category-action' + ui.item.value).remove();
						
						$('#category-action').append('<div id="category-action' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="category_action[]" value="' + ui.item.value + '" /></div>');
						
						$('#category-action div:odd').attr('class', 'odd');
						$('#category-action div:even').attr('class', 'even');
						
						return false;
					},
					focus: function(event, ui) {
						return false;
					}
				});
				
				$('#category-action div img').live('click', function() {
					$(this).parent().remove();
					
					$('#category-action div:odd').attr('class', 'odd');
					$('#category-action div:even').attr('class', 'even');	
				});
			</script> 	
			<script type="text/javascript">
				$('input[name=\'filter\']').autocomplete({
					delay: 500,
					source: function(request, response) {
						$.ajax({
							url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
							dataType: 'json',
							success: function(json) {		
								response($.map(json, function(item) {
									return {
										label: item.name,
										value: item.filter_id
									}
								}));
							}
						});
					}, 
					select: function(event, ui) {
						$('#category-filter' + ui.item.value).remove();
						
						$('#category-filter').append('<div id="category-filter' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="category_filter[]" value="' + ui.item.value + '" /></div>');
						
						$('#category-filter div:odd').attr('class', 'odd');
						$('#category-filter div:even').attr('class', 'even');
						
						return false;
					},
					focus: function(event, ui) {
						return false;
					}
				});
				
				$('#category-filter div img').live('click', function() {
					$(this).parent().remove();
					
					$('#category-filter div:odd').attr('class', 'odd');
					$('#category-filter div:even').attr('class', 'even');	
				});
			</script> 