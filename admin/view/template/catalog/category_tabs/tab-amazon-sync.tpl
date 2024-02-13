				<div id="tab-amazon-sync">				
					<h2> AMAZON RAINFOREST API</h2>
					<table class="form">

						<tr >
							<td style="width:100%" colspan="6">
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Категория Amazon</span>																
								<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><a style="color:#FFF;text-decoration:none" href="index.php?route=catalog/category/getAmazonCategoriesCSV&token=<?php echo $token; ?>"><i class="fa fa-amazon"></i> Нажми сюда, чтоб скачать полный список категорий Amazon в CSV <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $this->config->get('config_rainforest_source_language');?>.png" /> и <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $this->config->get('config_admin_language');?>.png" /></span>					
							</td>
						</tr>

						<tr style="border-bottom:1px dashed gray">

							<td style="width:100%" colspan="6">
								<input type="text" name="amazon_category_name" value="<?php echo $amazon_category_name; ?>" style="width:90%;" />
								<?php if (!$amazon_category_id) { ?>
									<br /><span id="span-alert-no-amazon-id" style="color:#ef5e67"><i class="fa fa-exclamation-triangle"></i> идентификатор не задан, попробуй подбор. Внимание, подбор работает только на НЕМЕЦКОМ языке</span>
									<?php } else { ?>
									<br /><span  style="color:#00ad07"><i class="fa fa-check"></i> идентификатор категории <span id="span-alert-amazon-id"><?php echo $amazon_category_id; ?></span></span>
								<? } ?>	

								<?php if ($amazon_category_id && $amazon_category_link) { ?>
									<br /><span  style="color:#00ad07"><i class="fa fa-check"></i> ccылка категории <a href="<?php echo $amazon_category_link; ?>" target="_blank"><?php echo $amazon_category_link; ?></a></span>
								<?php } ?>		

								<?php if ($amazon_category_full_information) { ?>		
									<br />
									<?php if (!empty($amazon_category_full_information['final_category'])) { ?>										
										<span  style="color:#00ad07"> 
											<i class="fa fa-check"></i> это финальная категория на Amazon
										</span>
									<?php } else { ?>
										<span  style="color:#ef5e67"> 
											<i class="fa fa-exclamation-triangle"></i> это не финальная категория на Amazon, возможно стоит поискать другое соответствие
										</span>
									<?php } ?>

									<?php if (!empty($amazon_category_full_information['full_name'])) { ?>
									<br />
									<span  style="color:#00ad07"> 
										<i class="fa fa-check"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $this->config->get('config_rainforest_source_language');?>.png" /> <?php echo $amazon_category_full_information['full_name']; ?> (<?php echo $amazon_category_full_information['name']; ?>)
									</span>
									<?php } ?>
									
									<?php if (!empty($amazon_category_full_information['name_native'])) { ?>
										<br />
										<span  style="color:#00ad07"> 
											<i class="fa fa-check"></i> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $this->config->get('config_admin_language');?>.png" /> <?php echo $amazon_category_full_information['full_name_native']; ?> (<?php echo $amazon_category_full_information['name_native']; ?>)
										</span>
									<?php } ?>
								<?php } else { ?>													
									<br /><span style="color:#ef5e67"><i class="fa fa-exclamation-triangle"></i> категория не существует в сохраненном дереве категорий, это может привести к нелогичному поведению дерева категорий
								<?php } ?>

								<input type="hidden" name="amazon_category_id" value="<?php echo $amazon_category_id; ?>" style="width:90%;" />
								
								<script type="text/javascript">
									$('input[name=\'amazon_category_name\']').autocomplete({
										delay: 500,
										source: function(request, response) {		
											$.ajax({
												url: 'index.php?route=catalog/category/amazon_autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
												dataType: 'json',
												success: function(json) {
													response($.map(json, function(item) {
														return {
															label: item.path,
															value: item.id
														}
													}));
												}
											});
										},
										select: function(event, ui) {
											$('input[name=\'amazon_category_name\']').val(ui.item.label);
											$('input[name=\'amazon_category_id\']').val(ui.item.value);
											$('#span-alert-amazon-id').text(ui.item.value);
											$('#span-alert-no-amazon-id').hide();
											
											return false;
										},
										focus: function(event, ui) {
											return false;
										}
									});
								</script>
							</td>
						</tr>					
					</table>
					

					<div style="margin-top:10px; padding:10px; border:1px dashed grey;">
						<h2 style="color:#FF9900;">AMAZON RAINFOREST API - репрайс</h2>
						<table class="form">
							<tr>
								<td style="width:150px;">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Нужен репрайс</span>
								</td>
								<td style="width:100px;">
									<select name="need_reprice">
										<?php if ($need_reprice) { ?>
											<option value="1" selected="selected">Да</option>
											<option value="0">Нет</option>
										<?php } else { ?>
											<option value="1">Да</option>
											<option value="0" selected="selected">Нет</option>
										<?php } ?>
									</select>		
								</td>
								<td>
									<span class="help"><i class="fa fa-info-circle"></i> Поставить "да" в случае любых изменений перезначений ценообразования</span>
								</td>
							</tr>
							<tr>
								<td style="width:150px;">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Репрайс выполнен</span>
								</td>
								<td style="width:100px;">
									<input type="text" name="last_reprice" value="<?php echo $last_reprice; ?>" style="width:150px;"  />		
								</td>
								<td>
									<span class="help"><i class="fa fa-info-circle"></i> время последнего выполнения репрайса</span>
								</td>
							</tr>
							<tr>
								<td style="width:150px;">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Скидки</span>
								</td>
								<td style="width:100px;">
									<select name="need_special_reprice">
										<?php if ($need_special_reprice) { ?>
											<option value="1" selected="selected">Да</option>
											<option value="0">Нет</option>
										<?php } else { ?>
											<option value="1">Да</option>
											<option value="0" selected="selected">Нет</option>
										<?php } ?>
									</select>		
								</td>
								<td>
									<span class="help" style="color:#cf4a61;"><i class="fa fa-info-circle"></i> автоназначение скидок работает только в случае включения опции "единые скидки" в настройках в силу высокой сложности логики. текущий статус: <?php echo $this->config->get('config_single_special_price'); ?></span>
									<span class="help"><i class="fa fa-info-circle"></i> создавать или нет скидки во время выполнения следующего запланированного репрайса</span>
									<span class="help"><i class="fa fa-info-circle"></i> если запустить репрайс без этого флага - скидки товаров категории, назначенные предыдущими репрайсами - будут удалены</span>
									<span class="help"><i class="fa fa-info-circle"></i> скидки будут созданы в случае, если различаются <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">текущий</span> и <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">старый</span> множители цены</span>
								</td>
							</tr>
							<tr>
								<td style="width:150px;">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Скидки ПЛЮС</span>
								</td>
								<td style="width:100px;">
									<select name="special_reprice_plus">
										<?php if ($special_reprice_plus) { ?>
											<option value="1" selected="selected">Да</option>
											<option value="0">Нет</option>
										<?php } else { ?>
											<option value="1">Да</option>
											<option value="0" selected="selected">Нет</option>
										<?php } ?>
									</select>		
								</td>
								<td>
									<span class="help"><i class="fa fa-info-circle"></i> в случае если <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">текущий</span> множитель <b>больше</b> <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">старого</span> (цена товаров повышается) - цена будет сформирована с <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">старым</span> множителем, скидка - с <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">текущим</span> множителем</span>
								</td>
							</tr>
							<tr>
								<td style="width:150px;">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Скидки МИНУС</span>
								</td>
								<td style="width:100px;">
									<select name="special_reprice_minus">
										<?php if ($special_reprice_minus) { ?>
											<option value="1" selected="selected">Да</option>
											<option value="0">Нет</option>
										<?php } else { ?>
											<option value="1">Да</option>
											<option value="0" selected="selected">Нет</option>
										<?php } ?>
									</select>		
								</td>
								<td>
									<span class="help"><i class="fa fa-info-circle"></i> в случае если <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">текущий</span> множитель <b>меньше</b> <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">старого</span> (цена товаров понижается) - цена будет сформирована с <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">текущим</span> множителем, скидка - с <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">старым</span> множителем</span>
								</td>
							</tr>
						</table>

						<h2>AMAZON RAINFOREST API - правила переназначения цен</h2>
						<table class="form" id="category_overload_multipliers">
							<tr>
								<td class="left" style="width:200px;">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Максимальный множитель цены</span>
								</td>
								<td class="left">
									<td style="width:100px;" class="center">
										<input type="number" step="0.1" name="overload_max_multiplier" value="<?php echo $overload_max_multiplier; ?>" style="width:100px;" />
									</td>
								</td>
								<td class="left">
									<span class="help">
										<i class="fa fa-info-circle"></i> Максимальная наценка, Х раз. Для товаров у которых вес задан очень некорректно. Если продажная цена по формулам будет в Х раз больше, чем цена закупки, то будет использован "множитель без веса"
									</span>
								</td>
							</tr>
							<tr>
								<td class="left" style="width:200px;">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Игнорировать объемный вес</span>
								</td>
								<td class="left">
									<td style="width:100px;" class="center">
										<input id="overload_ignore_volumetric_weight" type="checkbox" class="checkbox" name="overload_ignore_volumetric_weight" <? if ($overload_ignore_volumetric_weight){ ?> checked="checked" <? } ?> value="1" />
										<label for="overload_ignore_volumetric_weight"></label>
									</td>
								</td>
								<td class="left">
									<span class="help">
										<i class="fa fa-info-circle"></i> Игнорировать подсчёт объемного веса для этой категории. Использовать только обычный вес.
									</span>
								</td>
							</tr>
							<tr>
								<td class="left" style="width:200px;">
									<span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Максимальный множитель объемного веса</span>
								</td>
								<td class="left">
									<td style="width:100px;" class="center">
										<input type="number" step="0.1" name="overload_max_wc_multiplier" value="<?php echo $overload_max_wc_multiplier; ?>" style="width:100px;" />
									</td>
								</td>
								<td class="left">
									<span class="help">
										<i class="fa fa-info-circle"></i> Защита от некорректного большого объемного веса, в случае если Амазон отдает габарит товара, который фактически складывается. Если объемный вес будет больше в Х раз чем фактический, то объемный учтён не будет.
									</span>
								</td>
							</tr>
						</table>

						<table class="form" id="category_overprice_rules" >
							<thead>
								<tr>
									<td colspan="2" class="center">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Границы цен</span>
									</td>
									<td colspan="3" class="center">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Множитель для формулы с весом</span>
									</td>
									<td colspan="3" class="center" >
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Множитель для формулы без веса</span>
									</td>
									<td class="right" colspan="2">
										<a onclick="copyDefaultValues();" class="button">Скопировать из общих</a>
									</td>
								</tr>
								<tr>
									<td class="center" style="width:10%">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Цена закупки от, <?php echo $this->config->get('config_currency'); ?></span>
									</td>
									<td class="center" style="width:10%; border-right:1px dashed grey;">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Цена закупки до, <?php echo $this->config->get('config_currency'); ?></span>
									</td>
									<td class="center">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Текущий</span>									
									</td>
									<td class="center" style="width:1%">									
									</td>
									<td class="center" style="border-right:1px dashed grey;">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Старый</span>									
									</td>
									<td class="center">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Текущий</span>
									</td>
									<td class="center" style="width:1%">									
									</td>
									<td class="center" style="border-right:1px dashed grey;">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Старый</span>
									</td>
									<td class="center" style="width:10%">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Создавать скидку</span>
									</td>								
								</tr>
							</thead>
							<?php $category_overprice_rules_row = 0; ?>

							<?php foreach ($category_overprice_rules as $category_overprice_rule) { ?>
								<tbody id="category_overprice_rules-row<?php echo $category_overprice_rules_row; ?>">
									<tr>									
										<td class="center">
											<input type="number" step="10" name="category_overprice_rules[<?php echo $category_overprice_rules_row; ?>][min]" value="<?php echo $category_overprice_rule['min']; ?>" size="10" />
										</td>
										<td class="center" style="border-right:1px dashed grey;">
											<input type="number" step="10" name="category_overprice_rules[<?php echo $category_overprice_rules_row; ?>][max]" value="<?php echo $category_overprice_rule['max']; ?>" size="10" />
										</td>
										<td class="center">
											<input type="number" step="0.01" name="category_overprice_rules[<?php echo $category_overprice_rules_row; ?>][multiplier]" value="<?php echo $category_overprice_rule['multiplier']; ?>" size="10" />
										</td>
										<td class="center">
											<i class="fa fa-arrow-circle-right" style="color:#00ad07; cursor:pointer;" onclick="$(this).parent().next('td').find('input').val($(this).parent('td').prev('td').find('input').val());"></i>
										</td>
										<td class="center" style="border-right:1px dashed grey;">
											<input type="number" step="0.01" name="category_overprice_rules[<?php echo $category_overprice_rules_row; ?>][multiplier_old]" value="<?php echo $category_overprice_rule['multiplier_old']; ?>" size="10" />
											<i class="fa fa-times-circle" style="cursor:pointer; color:#cf4a61" onclick="$(this).prev().val(0);"></i>
										</td>
										<td class="center">
											<input type="number" step="0.01" name="category_overprice_rules[<?php echo $category_overprice_rules_row; ?>][default_multiplier]" value="<?php echo $category_overprice_rule['default_multiplier']; ?>" size="10" />
										</td>
										<td class="center">
											<i class="fa fa-arrow-circle-right" style="color:#00ad07; cursor:pointer;" onclick="$(this).parent().next('td').find('input').val($(this).parent('td').prev('td').find('input').val());"></i>
										</td>
										<td class="center" style="border-right:1px dashed grey;">
											<input type="number" step="0.01" name="category_overprice_rules[<?php echo $category_overprice_rules_row; ?>][default_multiplier_old]" value="<?php echo $category_overprice_rule['default_multiplier_old']; ?>" size="10" />
											<i class="fa fa-times-circle" style="cursor:pointer; color:#cf4a61" onclick="$(this).prev().val(0);"></i>
										</td>
										<td class="center">
											<input id="category_overprice_rules_row_<?php echo $category_overprice_rules_row; ?>_discount" type="checkbox" class="checkbox" name="category_overprice_rules[<?php echo $category_overprice_rules_row; ?>][discount]" <? if ($category_overprice_rule['discount']){ ?> checked="checked" <? } ?> value="1" />
											<label for="category_overprice_rules_row_<?php echo $category_overprice_rules_row; ?>_discount"></label>
										</td>
										<td class="right">
											<a onclick="$('#category_overprice_rules-row<?php echo $category_overprice_rules_row; ?>').remove();" class="button"><i class="fa fa-times-circle"></i></a>
										</td>
									</tr>
								</tbody>
								<?php $category_overprice_rules_row++; ?>
							<?php } ?>
							<tfoot>
								<tr>
									<td colspan="9"></td>
									<td class="right" colspan="1">
										<a onclick="addCategoryOverpriceRule();" class="button">Добавить</a>									
									</td>
								</tr>
							</tfoot>
						</table>
					</div>

					<script type="text/javascript">
						var category_overprice_rules_row = <?php echo $category_overprice_rules_row; ?>;

						function copyDefaultValues(){
							$('.category_overprice_rules-rows').remove();
							category_overprice_rules_row = 0;

							<?php for ($crmfc = 1; $crmfc <= $config_rainforest_main_formula_count; $crmfc++){ ?>	

								html  = '<tbody class="category_overprice_rules-rows" id="category_overprice_rules-row' + category_overprice_rules_row + '">';
								html += '  <tr>'; 

								html += '    <td class="center"><input type="number" step="10" name="category_overprice_rules[' + category_overprice_rules_row + '][min]" value="<?php echo ${'config_rainforest_main_formula_min_' . $crmfc}; ?>" size="10" /></td>';
								html += '    <td class="center"><input type="number" step="10" name="category_overprice_rules[' + category_overprice_rules_row + '][max]" value="<?php echo ${'config_rainforest_main_formula_max_' . $crmfc}; ?>" size="10" /></td>';

								html += '    <td class="center"><input type="number" step="0.01" name="category_overprice_rules[' + category_overprice_rules_row + '][multiplier]" value="<?php echo ${'config_rainforest_main_formula_multiplier_' . $crmfc}; ?>" size="10" /></td>';
								html += '    <td class="center"><i class="fa fa-arrow-circle-right" style="color:#00ad07; cursor:pointer;" onclick="$(this).parent().next(\'td\').find(\'input\').val($(this).parent(\'td\').prev(\'td\').find(\'input\').val());"></i></td>';
								html += '    <td class="center"><input type="number" step="0.01" name="category_overprice_rules[' + category_overprice_rules_row + '][multiplier_old]" value="0" size="10" /> <i class="fa fa-times-circle" style="cursor:pointer; color:#cf4a61" onclick="$(this).prev().val(0);"></i></td>';


								html += '    <td class="center"><input type="number" step="0.01" name="category_overprice_rules[' + category_overprice_rules_row + '][default_multiplier]" value="<?php echo ${'config_rainforest_main_formula_default_' . $crmfc}; ?>" size="10" /></td>';
								html += '    <td class="center"><i class="fa fa-arrow-circle-right" style="color:#00ad07; cursor:pointer;" onclick="$(this).parent().next(\'td\').find(\'input\').val($(this).parent(\'td\').prev(\'td\').find(\'input\').val());"></i></td>';
								html += '    <td class="center"><input type="number" step="0.01" name="category_overprice_rules[' + category_overprice_rules_row + '][default_multiplier_old]" value="0" size="10" /> <i class="fa fa-times-circle" style="cursor:pointer; color:#cf4a61" onclick="$(this).prev().val(0);"></i></td>';

								html += '    <td class="center"><input id="category_overprice_rules_row_' + category_overprice_rules_row + '_discount" type="checkbox" class="checkbox" name="category_overprice_rules[' + category_overprice_rules_row + '][discount]" value="1" /><label for="category_overprice_rules_row_' + category_overprice_rules_row + '_discount"></label></td>';

								html += '    <td class="right"><a onclick="$(\'#category_overprice_rules-row' + category_overprice_rules_row + '\').remove();" class="button"><i class="fa fa-times-circle"></i></a></td>';

								html += '  </tr>';
								html += '</tbody>';

								$('#category_overprice_rules tfoot').before(html);

								category_overprice_rules_row++;
							<?php } ?>
						}							

						function addCategoryOverpriceRule() {
							html  = '<tbody class="category_overprice_rules-rows" id="category_overprice_rules-row' + category_overprice_rules_row + '">';
							html += '  <tr>'; 

							html += '    <td class="center"><input type="number" step="10" name="category_overprice_rules[' + category_overprice_rules_row + '][min]" value="" size="10" /></td>';
							html += '    <td class="center" style="border-right:1px dashed grey;"><input type="number" step="10" name="category_overprice_rules[' + category_overprice_rules_row + '][max]" value="" size="10" /></td>';

							html += '    <td class="center"><input type="number" step="0.01" name="category_overprice_rules[' + category_overprice_rules_row + '][multiplier]" value="" size="10" /></td>';
							html += '    <td class="center"><i class="fa fa-arrow-circle-right" style="color:#00ad07; cursor:pointer;" onclick="$(this).parent().next(\'td\').find(\'input\').val($(this).parent(\'td\').prev(\'td\').find(\'input\').val());"></i></td>';
							html += '    <td class="center" style="border-right:1px dashed grey;"><input type="number" step="0.01" name="category_overprice_rules[' + category_overprice_rules_row + '][multiplier_old]" value="" size="10" /> <i class="fa fa-times-circle" style="cursor:pointer; color:#cf4a61" onclick="$(this).prev().val(0);"></i></td>';

							html += '    <td class="center"><input type="number" step="0.01" name="category_overprice_rules[' + category_overprice_rules_row + '][default_multiplier]" value="" size="10" /></td>';
							html += '    <td class="center"><i class="fa fa-arrow-circle-right" style="color:#00ad07; cursor:pointer;" onclick="$(this).parent().next(\'td\').find(\'input\').val($(this).parent(\'td\').prev(\'td\').find(\'input\').val());"></i></td>';
							html += '    <td class="center" style="border-right:1px dashed grey;"><input type="number" step="0.01" name="category_overprice_rules[' + category_overprice_rules_row + '][default_multiplier_old]" value="" size="10" /> <i class="fa fa-times-circle" style="cursor:pointer; color:#cf4a61" onclick="$(this).prev().val(0);"></i></td>';

							html += '    <td class="center"><input id="category_overprice_rules_row_' + category_overprice_rules_row + '_discount" type="checkbox" class="checkbox" name="category_overprice_rules[' + category_overprice_rules_row + '][discount]" value="1" /><label for="category_overprice_rules_row_' + category_overprice_rules_row + '_discount"></label></td>';

							html += '    <td class="right"><a onclick="$(\'#category_overprice_rules-row' + category_overprice_rules_row + '\').remove();" class="button"><i class="fa fa-times-circle"></i></a></td>';

							html += '  </tr>';
							html += '</tbody>';

							$('#category_overprice_rules tfoot').before(html);

							category_overprice_rules_row++;
						}
					</script>
				</div>