	<div id="tab-links">
		<table class="form">
			<tr>
				<td style="width:33%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Бренд</span>
				</td>
				<td style="width:33%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Коллекция</span>
				</td>
				<td style="width:33%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Основная категория</span>
				</td>
			</tr>
			<tr style="border-bottom: 1px dotted #000;">
				<td style="width:33%">
					<input type="text" name="manufacturer" value="<?php echo $manufacturer ?>" />
					<input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>" />
					<br />
					<span class="help">
						<i class="fa fa-info-circle"></i> автоподбор, начни вводить и выбери из списка	
					</span>
				</td>

				<td style="width:33%">

					<input type="text" name="collection" value="<? if(isset($collection)) { ?><? echo $collection['name']; ?><? } ?>" />
					<input type="hidden" name="collection_id" value="<? if(isset($collection)) { ?><? echo $collection['collection_id']; ?><? } else { ?>0<? } ?>" />
					<br />
					<span class="help">
						<i class="fa fa-info-circle"></i> автоподбор, если задан бренд - то подбираются только коллекции бренда	
					</span>

				</td>

				<td style="width:33%">
					<select name="main_category_id">
						<option value="0" selected="selected"><?php echo $text_none; ?></option>
						<?php foreach ($product_categories as $category) { ?>
							<?php if ($category['category_id'] == $main_category_id) { ?>
								<option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
					<br />
					<span class="help">
						<i class="fa fa-info-circle"></i> категория выбирается из заданных категорий отображения	
					</span>
				</td>
			</tr>
		</table>


		<table class="form">

			<tr>
				<td style="width:33%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Показывать в категориях</span>
				</td>
				<td style="width:33%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Включить в магазинах</span>
				</td>
				<td style="width:33%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Сервиз или набор</span>
				</td>
			</tr>

			<tr>

				<td style="width:33%">																			
					<input type="text" name="category" value="" /><br /><br />
					<div id="product-category" class="scrollbox" style="min-height: 200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($product_categories as $product_category) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div id="product-category<?php echo $product_category['category_id']; ?>" class="<?php echo $class; ?>"><?php echo $product_category['name']; ?><img src="view/image/delete.png" alt="" />
								<input type="hidden" name="product_category[]" value="<?php echo $product_category['category_id']; ?>" />
							</div>
						<?php } ?>
					</div>	
				</td>
				
				<td style="width:33%">																			
					<div class="scrollbox" style="min-height: 200px;">
						<?php $class = 'even'; ?>
						<?php foreach ($stores as $store) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div class="<?php echo $class; ?>">
								<?php if (in_array($store['store_id'], $product_store)) { ?>
									<input id="store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
									<label for="store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
								<?php } else { ?>
									<input id="store_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
									<label for="store_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
								<?php } ?>
							</div>
						<?php } ?>

					</div>
					<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
				</td>

				<td style="width:33%">					
					<div>														
						<input id="has_child" class="checkbox" name="has_child" value="1" <? if ($has_child) { ?>checked="checked"<? } ?> type="checkbox" />
						<label for="has_child">Включить сервиз/набор</label>
					</div>
					<input type="text" name="child" value="" /> 
					<br /><br />
					<div id="product-child" class="scrollbox" style="min-height: 200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($product_child as $product_child) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div id="product-child<?php echo $product_child['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_child['name']; ?><img src="view/image/delete.png" alt="" />
								<input type="hidden" name="product_child[]" value="<?php echo $product_child['product_id']; ?>" />
							</div>
						<?php } ?>
					</div>
				</td>

				
			</tr>
		</table>

		<h2>Перелинковка товаров (+Amazon)</h2>

		<table class="form">
			<tr>
				<td style="width:25%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Похожие или замена</span>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><i class="fa fa-amazon"></i> compare_with_similar</span>

					<span class="help"><i class="fa fa-info-circle"></i> Массив, содержащий сведения о других товарах из раздела «Сравнить с похожими товарами» на странице товара.</span>
				</td>
				<td style="width:25%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Sponsored Products</span>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><i class="fa fa-amazon"></i> sponsored_products</span>

					<span class="help"><i class="fa fa-info-circle"></i> Массив, содержащий сведения о спонсируемых продуктах, отображаемых на текущей странице продукта.</span>
				</td>
				<td style="width:25%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00AD07; color:#FFF">Сопутствующие</span>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00AD07; color:#FFF"><i class="fa fa-amazon"></i> frequently_bought_together</span>

					<span class="help"><i class="fa fa-info-circle"></i> Объект, содержащий сведения о других продуктах, которые, по мнению Amazon, «часто покупаются вместе» с текущим продуктом. Штатная логика related</span>
				</td>
				<td style="width:25%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#5D5D5D; color:#FFF">Похожие - 2</span>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#5D5D5D; color:#FFF"><i class="fa fa-amazon"></i> similar_to_consider</span>

					<span class="help"><i class="fa fa-info-circle"></i> Объект, содержащий сведения о разделе «похожий элемент для рассмотрения», если он показан.</span>
				</td>
			</tr>

			<tr>
				<td style="width:25%">																			
					<input type="text" name="similar" value="" /><br /><br />
					<div id="product-similar" class="scrollbox" style="min-height: 200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($products_similar as $product_similar) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div id="product-similar<?php echo $product_similar['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_similar['name']; ?><img src="view/image/delete.png" alt="" />
								<input type="hidden" name="product_similar[]" value="<?php echo $product_similar['product_id']; ?>" />
							</div>
						<?php } ?>
					</div>
				</td>

				<td style="width:25%">																			
					<input type="text" name="sponsored" value="" /><br /><br />
					<div id="product-sponsored" class="scrollbox" style="min-height: 200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($products_sponsored as $product_sponsored) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div id="product-sponsored<?php echo $product_sponsored['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_sponsored['name']; ?><img src="view/image/delete.png" alt="" />
								<input type="hidden" name="product_sponsored[]" value="<?php echo $product_sponsored['product_id']; ?>" />
							</div>
						<?php } ?>
					</div>
				</td>

				<td style="width:25%">																			
					<input type="text" name="related" value="" /><br /><br />
					<div id="product-related" class="scrollbox" style="min-height: 200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($products_related as $product_related) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div id="product-related<?php echo $product_related['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_related['name']; ?><img src="view/image/delete.png" alt="" />
								<input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
							</div>
						<?php } ?>
					</div>
				</td>

				<td style="width:25%">																			
					<input type="text" name="similar_to_consider" value="" /><br /><br />
					<div id="product-similar_to_consider" class="scrollbox" style="min-height: 200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($products_similar_to_consider as $product_similar_to_consider) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div id="product-similar_to_consider<?php echo $product_similar_to_consider['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_similar_to_consider['name']; ?><img src="view/image/delete.png" alt="" />
								<input type="hidden" name="product_similar_to_consider[]" value="<?php echo $product_related['product_id']; ?>" />
							</div>
						<?php } ?>
					</div>
				</td>
			</tr>

			<tr>
				<td style="width:25%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Купили после просмотра</span>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><i class="fa fa-amazon"></i> view_to_purchase</span>

					<span class="help"><i class="fa fa-info-circle"></i> Массив, содержащий информацию о других продуктах, которые клиенты Amazon купили после просмотра текущего продукта. Обычно отображается в разделе на странице продукта под заголовком «Что еще покупают покупатели после просмотра этого товара?».</span>
				</td>
				<td style="width:25%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00AD07; color:#FFF">Также смотрели</span>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00AD07; color:#FFF"><i class="fa fa-amazon"></i> also_viewed</span>

					<span class="help"><i class="fa fa-info-circle"></i> Массив, содержащий информацию о других продуктах, которые клиенты Amazon просматривали вместе с текущим продуктом.</span>
				</td>
				<td style="width:25%">
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Также купили</span>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><i class="fa fa-amazon"></i> also_bought</span>

					<span class="help"><i class="fa fa-info-circle"></i> Массив, содержащий информацию о других продуктах, которые также купили клиенты Amazon, купившие текущий продукт. Обычно отображается в разделе на странице продукта под заголовком «Клиенты, которые купили этот товар, также купили».</span>
				</td>
				<td style="width:25%">	
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#5D5D5D; color:#FFF">Shop By Look</span>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#5D5D5D; color:#FFF"><i class="fa fa-amazon"></i> shop_by_look</span>

					<span class="help"><i class="fa fa-info-circle"></i> Объект, содержащий сведения о разделе «Поиск по внешнему виду» на странице продукта.</span>																	
				</td>
			</tr>
			<tr>
				<td style="width:25%">																			
					<input type="text" name="view_to_purchase" value="" /><br /><br />
					<div id="product-view_to_purchase" class="scrollbox" style="min-height: 200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($products_view_to_purchase as $product_view_to_purchase) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div id="product-view_to_purchase<?php echo $product_view_to_purchase['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_view_to_purchase['name']; ?><img src="view/image/delete.png" alt="" />
								<input type="hidden" name="product_view_to_purchase[]" value="<?php echo $product_related['product_id']; ?>" />
							</div>
						<?php } ?>
					</div>
				</td>
				<td style="width:25%">																			
					<input type="text" name="also_viewed" value="" /><br /><br />
					<div id="product-also_viewed" class="scrollbox" style="min-height: 200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($products_also_viewed as $product_also_viewed) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div id="product-also_viewed<?php echo $product_also_viewed['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_also_viewed['name']; ?><img src="view/image/delete.png" alt="" />
								<input type="hidden" name="product_also_viewed[]" value="<?php echo $product_related['product_id']; ?>" />
							</div>
						<?php } ?>
					</div>
				</td>
				<td style="width:25%">																			
					<input type="text" name="also_bought" value="" /><br /><br />
					<div id="product-also_bought" class="scrollbox" style="min-height: 200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($products_also_bought as $product_also_bought) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div id="product-also_bought<?php echo $product_also_bought['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_also_bought['name']; ?><img src="view/image/delete.png" alt="" />
								<input type="hidden" name="product_also_bought[]" value="<?php echo $product_related['product_id']; ?>" />
							</div>
						<?php } ?>
					</div>
				</td>
				<td style="width:25%">																			
					<input type="text" name="shop_by_look" value="" /><br /><br />
					<div id="product-shop_by_look" class="scrollbox" style="min-height: 200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($products_shop_by_look as $product_shop_by_look) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div id="product-shop_by_look<?php echo $product_shop_by_look['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_shop_by_look['name']; ?><img src="view/image/delete.png" alt="" />
								<input type="hidden" name="product_shop_by_look[]" value="<?php echo $product_related['product_id']; ?>" />
							</div>
						<?php } ?>
					</div>
				</td>
			</tr>

		</table>

		<h2>Группировки и другая шляпа</h2>

		<table class="form">
			<tr>
				<td style="width:33%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Принадлежит к группам</span></p>
						<div class="scrollbox facats">
							<?php $class = 'odd'; ?>
							<?php foreach ($faproduct_facategory_all as $category) { ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								<div class="<?php echo $class; ?>">
									<?php if (in_array($category['facategory_id'], $faproduct_facategory)) { ?>
										<input id="facategory_<?php echo $category['facategory_id']; ?>" class="checkbox" type="checkbox" name="faproduct_facategory[]" value="<?php echo $category['facategory_id']; ?>" checked="checked" />
										<label for="facategory_<?php echo $category['facategory_id']; ?>"><?php echo $category['name']; ?></label>
									<?php } else { ?>
										<input id="facategory_<?php echo $category['facategory_id']; ?>" class="checkbox" type="checkbox" name="faproduct_facategory[]" value="<?php echo $category['facategory_id']; ?>" />
										<label for="facategory_<?php echo $category['facategory_id']; ?>"><?php echo $category['name']; ?></label>
									<?php } ?>
								</div>
							<?php } ?>
						</div>														
						<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Показать в карте товары группы</span></p>
						<select name="facategory_show">
							<option value="0" selected="selected"><?php echo $text_none; ?></option>
							<?php foreach ($faproduct_facategory_all as $category) { ?>
								<?php if ($category['facategory_id'] == $facategory_show) { ?>
									<option value="<?php echo $category['facategory_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $category['facategory_id']; ?>"><?php echo $category['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
				</td>

				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Фильтры</span></p>
					<input type="text" name="filter" value="" /><br /><br />
					<div id="product-filter" class="scrollbox" style="min-height: 200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($product_filters as $product_filter) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div id="product-filter<?php echo $product_filter['filter_id']; ?>" class="<?php echo $class; ?>"><?php echo $product_filter['name']; ?><img src="view/image/delete.png" alt="" />
								<input type="hidden" name="product_filter[]" value="<?php echo $product_filter['filter_id']; ?>" />
							</div>
						<?php } ?>
					</div>
				</td>
				
				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Загрузки</span></p>
					<input type="text" name="download" value="" /><br /><br />
					<div id="product-download" class="scrollbox" style="min-height: 200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($product_downloads as $product_download) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div id="product-download<?php echo $product_download['download_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_download['name']; ?><img src="view/image/delete.png" alt="" />
								<input type="hidden" name="product_download[]" value="<?php echo $product_download['download_id']; ?>" />
							</div>
						<?php } ?>
					</div></td>
				</tr>
			</table>
		</div>
<script type="text/javascript">
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';

		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');

				currentCategory = item.category;
			}

			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'collection\']').autocomplete({
	delay: 500,
	source: function(request, response) {		
		$.ajax({
			url: 'index.php?route=catalog/collection/autocomplete&token=<?php echo $token; ?>&filter_manufacturer_id='+ $('input[name=manufacturer_id]').val() +'&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			error:function(json){
				console.log(json)				
			},
			success: function(json) {
				json.unshift({
					'collection_id':  0,
					'name':  'Не выбрано'
				});

				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.collection_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'collection\']').val(ui.item.label);
		$('input[name=\'collection_id\']').val(ui.item.value);

		return false;
	},
	focus: function(event, ui) {
		return false;
	}
});

	// Manufacturer
	$('input[name=\'manufacturer\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.manufacturer_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=\'manufacturer\']').attr('value', ui.item.label);
			$('input[name=\'manufacturer_id\']').attr('value', ui.item.value);
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	// Category
	$('input[name=\'category\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
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
			$('#product-category' + ui.item.value).remove();
			
			$('#product-category').append('<div id="product-category' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_category[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-category div:odd').attr('class', 'odd');
			$('#product-category div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-category div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-category div:odd').attr('class', 'odd');
		$('#product-category div:even').attr('class', 'even');	
	});
	
	// Filter
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
			$('#product-filter' + ui.item.value).remove();
			
			$('#product-filter').append('<div id="product-filter' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_filter[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-filter div:odd').attr('class', 'odd');
			$('#product-filter div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-filter div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-filter div:odd').attr('class', 'odd');
		$('#product-filter div:even').attr('class', 'even');	
	});
	
	// Downloads
	$('input[name=\'download\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.download_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-download' + ui.item.value).remove();
			
			$('#product-download').append('<div id="product-download' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_download[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-download div:odd').attr('class', 'odd');
			$('#product-download div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-download div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-download div:odd').attr('class', 'odd');
		$('#product-download div:even').attr('class', 'even');	
	});

	// Related
	$('input[name=\'main_variant_product\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=\'main_variant_id\']').val(ui.item.value);
			$('input[name=\'main_variant_product\']').val(ui.item.label);
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});


	
	// Related
	$('input[name=\'related\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-related' + ui.item.value).remove();
			
			$('#product-related').append('<div id="product-related' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_related[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-related div:odd').attr('class', 'odd');
			$('#product-related div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-related div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-related div:odd').attr('class', 'odd');
		$('#product-related div:even').attr('class', 'even');	
	});
	
	// similar
	$('input[name=\'similar\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-similar' + ui.item.value).remove();
			
			$('#product-similar').append('<div id="product-similar' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_similar[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-similar div:odd').attr('class', 'odd');
			$('#product-similar div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-similar div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-similar div:odd').attr('class', 'odd');
		$('#product-similar div:even').attr('class', 'even');	
	});

	// similar_to_consider
	$('input[name=\'similar_to_consider\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-similar_to_consider' + ui.item.value).remove();
			
			$('#product-similar_to_consider').append('<div id="product-similar_to_consider' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_similar_to_consider[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-similar_to_consider div:odd').attr('class', 'odd');
			$('#product-similar_to_consider div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-similar_to_consider div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-similar_to_consider div:odd').attr('class', 'odd');
		$('#product-similar_to_consider div:even').attr('class', 'even');	
	});

	// view_to_purchase
	$('input[name=\'view_to_purchase\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-view_to_purchase' + ui.item.value).remove();
			
			$('#product-view_to_purchase').append('<div id="product-view_to_purchase' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_view_to_purchase[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-view_to_purchase div:odd').attr('class', 'odd');
			$('#product-view_to_purchase div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-view_to_purchase div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-view_to_purchase div:odd').attr('class', 'odd');
		$('#product-view_to_purchase div:even').attr('class', 'even');	
	});

	// also_viewed
	$('input[name=\'also_viewed\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-also_viewed' + ui.item.value).remove();
			
			$('#product-also_viewed').append('<div id="product-also_viewed' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_also_viewed[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-also_viewed div:odd').attr('class', 'odd');
			$('#product-also_viewed div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-also_viewed div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-also_viewed div:odd').attr('class', 'odd');
		$('#product-also_viewed div:even').attr('class', 'even');	
	});

	// also_bought
	$('input[name=\'also_bought\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-also_bought' + ui.item.value).remove();
			
			$('#product-also_bought').append('<div id="product-also_bought' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_also_bought[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-also_bought div:odd').attr('class', 'odd');
			$('#product-also_bought div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-also_bought div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-also_bought div:odd').attr('class', 'odd');
		$('#product-also_bought div:even').attr('class', 'even');	
	});

	// shop_by_look
	$('input[name=\'shop_by_look\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-shop_by_look' + ui.item.value).remove();
			
			$('#product-shop_by_look').append('<div id="product-shop_by_look' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_shop_by_look[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-shop_by_look div:odd').attr('class', 'odd');
			$('#product-shop_by_look div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-shop_by_look div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-shop_by_look div:odd').attr('class', 'odd');
		$('#product-shop_by_look div:even').attr('class', 'even');	
	});

	// sponsored
	$('input[name=\'sponsored\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-sponsored' + ui.item.value).remove();
			
			$('#product-sponsored').append('<div id="product-sponsored' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_sponsored[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-sponsored div:odd').attr('class', 'odd');
			$('#product-sponsored div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-sponsored div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-sponsored div:odd').attr('class', 'odd');
		$('#product-sponsored div:even').attr('class', 'even');	
	});
	
	// Child
	$('input[name=\'child\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-child' + ui.item.value).remove();
			
			$('#product-child').append('<div id="product-child' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_child[]" value="' + ui.item.value + '" /></div>');
			
			$('#product-child div:odd').attr('class', 'odd');
			$('#product-child div:even').attr('class', 'even');
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#product-child div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-child div:odd').attr('class', 'odd');
		$('#product-child div:even').attr('class', 'even');	
	});
	</script> 

