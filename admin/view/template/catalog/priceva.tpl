		<?php echo $header; ?>
		<div id="content">
			<?php if ($error_warning) { ?>
				<div class="warning"><?php echo $error_warning; ?></div>
			<?php } ?>
			<?php if ($success) { ?>
				<div class="success"><?php echo $success; ?></div>
			<?php } ?>
			<div class="box">
				<div class="heading order_head">
					<h1 style="color:#7F00FF; padding-top:10px;padding-left:10px;"><i class="fa fa-product-hunt" style="display:inline-block!important;"></i> Мониторинг</h1>

					
					<style>
						a.button{color:#cf4a61; border-color:#cf4a61}
						a.button.active{color:white; background-color:#cf4a61}
						table.no-border tr td{border: 0;}
					</style>
					
					<div class="buttons" style="padding-top:10px;padding-left:10px;">

						<a class="button <?php if ($filter_is_illiquid) { ?>active<? } ?>" href="<?php echo $href_filter_is_illiquid; ?>"><i class="fa fa-exclamation-triangle"></i> Неликвид <?php if ($total_not_liquid) { ?>(<?php echo $total_not_liquid; ?>)<?php } ?></a>

						<a class="button <?php if ($filter_show_without_links) { ?>active<? } ?>" href="<?php echo $href_filter_show_without_links; ?>"><i class="fa fa-exclamation-triangle"></i> Без ссылок <?php if ($total_without_links) { ?> <?php echo $total_without_links; ?> из  <?php echo $total_total_in_db; ?><?php } ?></a>

						<a class="button <?php if ($filter_competitor_stock_all && $filter_kitchenprofi_not_stock) { ?>active<? } ?>" href="<?php echo $href_competitor_stock_kitchenprofi_not_stock; ?>"><i class="fa fa-exclamation-triangle"></i> Есть у всех, кроме нас <?php if ($total_competitor_stock_kitchenprofi_not_stock) { ?> <?php echo $total_competitor_stock_kitchenprofi_not_stock; ?><?php } ?></a>

						<a class="button <?php if ($filter_competitor_stock && $filter_kitchenprofi_not_stock) { ?>active<? } ?>" href="<?php echo $href_any_competitor_stock_kitchenprofi_not_stock; ?>"><i class="fa fa-exclamation-triangle"></i> Есть у кого-то, у нас нет <?php if ($total_any_competitor_stock_kitchenprofi_not_stock) { ?> <?php echo $total_any_competitor_stock_kitchenprofi_not_stock; ?><?php } ?></a>

					</div>
				</div>
				<div class="content">
					<form>
						<table style="width: 100%;">
							<tr class="filter">
								<td class="left">
									<div >
										<input type="text" id="filter_name" name="filter_name" style="width:230px;" autocomplete="off" value="<?php echo $filter_name; ?>" placeholder="Название, ID, SKU, EAN" />
									</div>

									<div style="margin-top:10px;">
										<select name="filter_store_id" style="width:250px;">	
											<?php foreach ($stores as $store) { ?>
												<option value="<? echo $store['store_id'] ?>" <?php if ($store['store_id'] == $filter_store_id) { ?>selected="selected"<?php } ?>><? echo $store['name']; ?></option>
											<? } ?>							
										</select>
									</div>

									<div style="margin-top:10px;">					
										<select name="filter_manufacturer_id" style="width:250px;">
											<option value="*">- Выбери бренд -</option>
											<? foreach ($manufacturers as $manufacturer) { ?>
												<option value="<? echo $manufacturer['manufacturer_id'] ?>" <?php if ($manufacturer['manufacturer_id'] == $filter_manufacturer_id) { ?>selected="selected"<?php } ?>><? echo $manufacturer['name']; ?></option>
											<? } ?>
										</select>
									</div>

									
									<div style="margin-top:10px;">
										<select name="filter_priceva_category" style="width:250px;">
											<option value="*">- Выбери категорию -</option>
											<? foreach ($priceva_categories as $priceva_category) { ?>

												<?php if ($priceva_category['category_name']) { ?>

													<option value="<? echo $priceva_category['category_name'] ?>" <?php if ($priceva_category['category_name'] == $filter_priceva_category) { ?>selected="selected"<?php } ?>><? echo $priceva_category['category_name']; ?> (<?php echo $priceva_category['total_products']; ?>)</option>

												<?php } ?>
											<? } ?>
										</select>
									</div>

								</td>

								<td class="left" valign="top" style="vertical-align: top; font-weight: 700;">
									<div>
										<input id="checkbox_35" class="checkbox" type="checkbox" name="filter_dates_periods" <? if ($filter_dates_periods) { ?>checked="checked"<? } ?> value="1" /> 
										<label for="checkbox_35" style="color:#7F00FF"><i class="fa fa-calendar" style="padding: 3px 3px;color: #7F00FF;"></i>&nbsp;Периоды от сегодня </label>				
									</div>
									<div>
										<small style="color:#7F00FF; font-weight:400;">Если вкл, то периоды продаж считаются от сегодняшнего дня</small>
									</div>

									<div style="margin-top:10px;" class="help">
										<i class="fa fa-question-circle"></i> приоритет назначения цены на фронте: Общая цена, EUR -> РРЦ цена, EUR -> РРЦ цена, RUB.<br />
										Изменение общей цены EUR повлияет и на другие магазины.
									</div>
								</td>

								<td class="left">
									<div class="scrollbox" style="max-width:300px; height:150px;">
										<?php if ($competitors) { ?>
											<?php $filter_competitors = $filter_competitors?explode(',',html_entity_decode($filter_competitors)):array(); ?>
											<?php $class = 'odd'; ?>																						
											<?php foreach ($competitors as $competitor) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div> 
													<input class="checkbox filter_competitors" id="competitor_<?php echo md5($competitor['company_name']); ?>" type="checkbox" name="filter_competitors[]" value="<?php echo $competitor['company_name']; ?>" <? if (in_array($competitor['company_name'], $filter_competitors)) { ?> checked="checked" <? } ?> />
													<label for="competitor_<?php echo md5($competitor['company_name']); ?>">
														<i class="fa fa-product-hunt"></i> <b><?php echo $competitor['company_name']; ?> (<?php echo $competitor['total_products']; ?>)</b>
													</label>
												</div>
											<? } ?>
										<? } ?>

									</div>
									<a class="select_all" onclick="$(this).parent().find(':checkbox').prop('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').prop('checked', false);">Снять выделение</a>
								</td>

								<td class="left" valign="top" style="vertical-align: top; font-weight: 700;">
									<div>
										<input id="checkbox_23" class="checkbox" type="checkbox" name="filter_links_only_selected" <? if ($filter_links_only_selected) { ?>checked="checked"<? } ?> value="1" /> 
										<label for="checkbox_23" style="color:#7F00FF"><i class="fa fa-external-link" style="padding: 3px 3px;color: #7F00FF;"></i>&nbsp;Ссылки только выбранных </label>
									</div>

									<div style="margin-top:10px;">
										<input id="checkbox_22" class="checkbox" type="checkbox" name="filter_competitor_stock" <? if ($filter_competitor_stock) { ?>checked="checked"<? } ?> value="1" /> 
										<label for="checkbox_22" style="color:#008000"><i class="fa fa-check-square" style="padding: 3px 3px;color: #008000;"></i>&nbsp;Есть у кого-то из конкурентов</label>
									</div>

									<div style="margin-top:10px;">
										<input id="checkbox_24" class="checkbox" type="checkbox" name="filter_competitor_not_stock" <? if ($filter_competitor_not_stock) { ?>checked="checked"<? } ?> value="1" /> 
										<label for="checkbox_24" style="color:#cf4a61"><i class="fa fa-times" style="padding: 3px 3px;color: #cf4a61;"></i>&nbsp;Нет у кого-то из конкурентов</label>
									</div>

									<div style="margin-top:10px;">
										<input id="checkbox_27" class="checkbox" type="checkbox" name="filter_competitor_stock_all" <? if ($filter_competitor_stock_all) { ?>checked="checked"<? } ?> value="1" /> 
										<label for="checkbox_27" style="color:#008000"><i class="fa fa-check-square" style="padding: 3px 3px;color: #008000;"></i>&nbsp;Есть у всех конкурентов</label>
									</div>

									<div style="margin-top:10px;">
										<input id="checkbox_28" class="checkbox" type="checkbox" name="filter_competitor_not_stock_all" <? if ($filter_competitor_not_stock_all) { ?>checked="checked"<? } ?> value="1" /> 
										<label for="checkbox_28" style="color:#cf4a61"><i class="fa fa-times" style="padding: 3px 3px;color: #cf4a61;"></i>&nbsp;Нет ни у кого из конкурентов</label>
									</div>

									<div style="margin-top:10px;">
										<input id="checkbox_25" class="checkbox" type="checkbox" name="filter_kitchenprofi_stock" <? if ($filter_kitchenprofi_stock) { ?>checked="checked"<? } ?> value="1" /> 
										<label for="checkbox_25" style="color:#008000"><i class="fa fa-check-square" style="padding: 3px 3px;color: #008000;"></i>&nbsp;Есть на складе</label>
									</div>

									<div style="margin-top:10px;">
										<input id="checkbox_26" class="checkbox" type="checkbox" name="filter_kitchenprofi_not_stock" <? if ($filter_kitchenprofi_not_stock) { ?>checked="checked"<? } ?> value="1" /> 
										<label for="checkbox_26" style="color:#cf4a61"><i class="fa fa-times" style="padding: 3px 3px;color: #cf4a61;"></i>&nbsp;Нет на складе</label>
									</div>
								</td>

								<td class="left" valign="top" style="vertical-align: top; font-weight: 700;">
									<div>
										<input id="checkbox_44" class="checkbox" type="checkbox" name="filter_kitchenprofi_stockwait" <? if ($filter_kitchenprofi_stockwait) { ?>checked="checked"<? } ?> value="1" /> 
										<label for="checkbox_44" style="color:#008000"><i class="fa fa-truck" style="padding: 3px 3px;color: #008000;"></i>&nbsp;Едет на склад</label>
									</div>

									<div style="margin-top:10px;">
										<input id="checkbox_45" class="checkbox" type="checkbox" name="filter_kitchenprofi_not_stockwait" <? if ($filter_kitchenprofi_not_stockwait) { ?>checked="checked"<? } ?> value="1" /> 
										<label for="checkbox_45" style="color:#cf4a61"><i class="fa fa-truck" style="padding: 3px 3px;color: #cf4a61;"></i>&nbsp;Не едет на склад</label>
									</div>
								</td>
								
							</tr>
							
							
							<tr>
								<td colspan="5" align="right" style="padding-right:10px;"><a onclick="filter(false);" class="button">Фильтр</a></td>
							</tr>
						</table>
					</form>
					
					<div class="filter_bord"></div>
					
					<table class="list bold-inputs">				
						<thead>
							<tr>
								
								<td class="center" width="1"></td>
								<td class="center"></td>
								
								<td class="left">
									<?php if ($sort == 'pd.name') { ?>
										<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Товар</a>
									<?php } else { ?>
										<a href="<?php echo $sort_name; ?>"><i class="fa fa-sort"></i> Товар</a>
									<?php } ?>
								</td>								

								<td class="left">
									Себестоимость
								</td>
								
								<td class="left">
									МВЦ Фронта								
								</td>
								
								<td class="left">
									Общая цена<br />
									KitchenProfi, <b><?php echo $defaultCurrency; ?></b>
								</td>

								<td class="left">
									РРЦ цена<br />
									KitchenProfi, <b><?php echo $defaultCurrency; ?></b>
								</td>

								<td class="left">
									РРЦ цена<br />
									KitchenProfi, <b><?php echo $catalogCurrency; ?></b>						
								</td>

								<td class="left">
									Фронт цена<br />
									KitchenProfi, <b><?php echo $catalogCurrency; ?></b>						
								</td>

								<td class="left">
									Конкуренты
								</td>

								<td class="left">
									Продаж<br />
									Месяц, квартал
								</td>

								<td class="left">
									Продаж<br />
									Полгода, год
								</td>

								<td class="right" style="width:50px;">
									<?php if ($sort == 'p.quantity_stockM') { ?>
										<a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>">Склад</a>
									<?php } else { ?>
										<a href="<?php echo $sort_quantity; ?>"><i class="fa fa-sort"></i> Склад</a>
									<?php } ?>
									<br />Едет
								</td>
								
								
								
								<td class="right" width="35px">
									
								</td>
								
							</tr>
						</thead>
						<tbody>
							
							<?php if ($products) { ?>
								<?php foreach ($products as $product) { ?>
									<tr>
										
										<td class="center" style="white-space:nowrap;">
											<?php if ($product['problem']) { ?>
												<i class="fa fa-exclamation-triangle" style="color:#cf4a61; font-size:24px;"></i>

												<?php if ($product['problem_sales']) { ?>
													<br />
													<span style="font-size:10px; display:inline-block;padding:3px; color:#FFF; background-color:#cf4a61;">НЕТ ПРОДАЖ</span>
												<?php } ?>

												<?php if ($product['problem_stock']) { ?>
													<br />
													<span style="font-size:10px; display:inline-block;padding:3px; color:#FFF; background-color:<?php if (!$product['problem_sales']) { ?>#cf4a61;<?php } else { ?>#ff7815<?php } ?>">НЕТ НА СКЛ</span>
												<?php } ?>


											<?php } elseif ($product['warning']) { ?>
												<i class="fa fa-question-circle" style="color:#ff7815; font-size:24px;"></i>

												<?php if ($product['warning_small_sales']) { ?>
													<br />
													<span style="font-size:10px; display:inline-block;padding:3px; color:#FFF; background-color:#ff7815">МАЛО ПРОДАЖ</span>
												<?php } ?>

											<?php } else { ?>
												<i class="fa fa-check-circle" style="color:#51a881; font-size:24px;"></i>
											<?php } ?>
										</td>	
										
										<td class="center">
											<img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" loading="lazy" />
										</td>
										
										<td class="left">
											<b><?php echo $product['name']; ?></b>
											
											
											<?php if ($product['priceva_category_name']) { ?>
												<div style="margin-top:5px;font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#ff7815;">
													<i class="fa fa-product-hunt"></i>	<?php echo $product['priceva_category_name']; ?>
												</div>
											<?php } ?>
											
											
											<div style="margin-top:5px;">																		
												
												<span style="font-size:10px; display:inline-block;padding:3px; color:#FFF; background-color:#000;"><?php echo $product['manufacturer']; ?></span>
												
												<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:grey;"><?php echo $product['model']; ?></span>	
												<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#51a881;"><?php echo $product['product_id']; ?></span>
												
												<?php if ($product['ean']) { ?>
													<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#7F00FF;"><?php echo $product['ean']; ?></span>
												<?php } ?>

												<?php if ($product['asin']) { ?>
													<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#FF9900;"><?php echo $product['asin']; ?></span>
												<?php } ?>
												
												
											</div>
											
											<div style="margin-top:5px;">
												<?php if ($product['is_illiquid']) { ?>
													<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#cf4a61;"><i class="fa fa-exclamation-triangle"></i> Неликвид</span>
												<?php } ?>
											</div>
											
										</td>
										
										<td class="left" style="white-space:nowrap; font-weight:700;">
											<?php if ($product['cost_formatted']) { ?>
												<?php echo $product['cost_formatted']; ?>
												
												
												<?php if ($product['cost_in_eur']) { ?>
													<div style="margin-top:5px;">
														<span style="display:inline-block; padding:3px; color:#FFF; background-color:#7F00FF;"><small><?php echo $product['cost_in_eur']; ?></small></span>
													</div>
												<?php } ?>	
											<?php } ?>																	
										</td>
										
										<td class="left" style="white-space:nowrap; font-weight:700;">
											
											<?php if ($product['min_sale_price_formatted']) { ?>
												<?php echo $product['min_sale_price_formatted']; ?>
												
												<?php if ($product['min_sale_price_in_eur']) { ?>
													<div style="margin-top:5px;">

														<span style="display:inline-block; padding:3px; color:#FFF; background-color:#7F00FF;"><small><?php echo $product['min_sale_price_in_eur']; ?></small></span>


													</div>
												<?php } ?>										
											<?php } ?>											
										</td>

										
										<td class="left" style=" white-space:nowrap; font-weight:700;">
											
											<input type="text" id="<? echo $product['product_id']; ?>-price" class="onfocusedit_direct" data-field="price" data-product-id="<? echo $product['product_id']; ?>" style="color:#008000" value="<?php echo $product['price_admin_unformatted']; ?>" /><?php echo $defaultCurrencySign; ?>

											<?php if ($product['price_admin_unformatted']) { ?>
												<div>
													<small style="color:#7F00FF;"><?php echo $product['price_admin_in_national']; ?></small>
												</div>
											<?php } else { ?>
												<div>
													<small style="color:grey;">не задано</small>
												</div>
											<?php } ?>
										</td>	

										<td class="left" style=" white-space:nowrap; font-weight:700;">

											<input type="text" id="<? echo $product['product_id']; ?>-price-to-store" class="onfocusedit_direct" data-field="price_to_store" data-product-id="<? echo $product['product_id']; ?>" style="color:#008000" value="<?php echo $product['price_to_store_unformatted']; ?>" /><?php echo $defaultCurrencySign; ?>

											<?php if ($product['price_to_store_unformatted']) { ?>
												<div>
													<small style="color:#7F00FF;"><?php echo $product['price_admin_to_store_in_national']; ?></small>
												</div>
											<?php } else { ?>
												<div>
													<small style="color:grey;">не задано</small>
												</div>
											<?php } ?>

										</td>	

										<td class="left" style=" white-space:nowrap; font-weight:700;">

											<input type="text" id="<? echo $product['product_id']; ?>-price-national-to-store" class="onfocusedit_direct" data-field="price_national_to_store" data-product-id="<? echo $product['product_id']; ?>" style="color:#008000" value="<?php echo $product['price_national_to_store_unformatted']; ?>" /><?php echo $catalogCurrencySign; ?>

											<?php if ($product['price_national_to_store_unformatted']) { ?>
												<div>
													<small style="color:#7F00FF;"><?php echo $product['price_admin_national_to_store_in_eur']; ?></small>
												</div>
											<?php } else { ?>
												<div>
													<small style="color:grey;">не задано</small>
												</div>
											<?php } ?>

										</td>

										<td class="left" style=" white-space:nowrap; font-weight:700;">
											<div>
												<?php if ($product['special']) { ?>
													<span style="color:#008000"><?php echo $product['special']; ?></span>
													<br /><small style="color:#cf4a61"><s><?php echo $product['price']; ?></s></small>
												<?php } else { ?>									
													<span style="color:#008000"><?php echo $product['price']; ?></span>
												<?php } ?>		
											</div>

											<div style="margin-top:5px;">
												<span style="display:inline-block; padding:3px; color:#FFF; background-color:#7F00FF;"><small><?php echo $product['price_in_eur']; ?></small></span>
											</div>
										</td>							
																				

										<td class="left" style="white-space:nowrap;">
											<table class="list no-border">
												<?php foreach($product['competitors_data'] as $competitor_data) { ?>
													<?php $display_td = true; ?>
													<?php if ($filter_links_only_selected && !empty($filter_competitors)) { ?>
														<?php if (!in_array($competitor_data['company_name'], $filter_competitors)) { $display_td = false; }?>
													<?php } ?>

													<?php if ($display_td) { ?>
														<tr>
															<td class="left">
																<a href="<?php echo $competitor_data['url'] ?>" target="_blank"><?php echo $competitor_data['company_name']; ?> <i class="fa fa-external-link"></i></a>
																<div style="margin-top: 5px;">
																	<span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:#51a881;"><i class="fa fa-refresh"></i> <?php echo $competitor_data['last_check_date']; ?></span>
																	<br /><span style="font-size:10px; display:inline-block; padding:3px; color:#FFF; background-color:grey;"><?php echo $competitor_data['relevance_status']; ?></span>
																</div>
															</td>
															
															<?php if ($competitor_data['price'] || $competitor_data['special']) { ?>
																<td class="left" style="white-space:nowrap; font-weight:700;">
																	<?php if ($competitor_data['special']) { ?>
																		<?php echo $competitor_data['special']; ?>
																		<br /><small style="color:#cf4a61"><s><?php echo $product['price']; ?></s></small>
																	<?php } else { ?>
																		<?php echo $competitor_data['price']; ?>
																	<?php } ?>
																</td>
															<?php } else { ?>
																<td class="left" style="white-space:nowrap;">
																	<i class="fa fa-question-circle" style="color:#ff7815;"></i>
																</td>
															<?php } ?>

															<td class="left" style="white-space:nowrap;">
																<?php if ($competitor_data['in_stock']) { ?>
																	<i class="fa fa-check-square" style="color:#008000"></i>
																<?php } else { ?>
																	<i class="fa fa-times" style="color:#cf4a61"></i>
																<?php } ?>
															</td>
														</tr>
													<?php } ?>
												<?php } ?>
											</table>
										</td>

										<td class="center" style="white-space:nowrap; font-weight:700;">
											<div>
												<?php if ($product['bf_month']) { ?>											
													МЕС: <?php echo $product['bf_month'];?> 
												<?php } else { ?>
													<span style="color: #cf4a61;"><i class="fa fa-exclamation-triangle"></i></span>
												<?php } ?>
											</div>

											<div style="margin-top:5px;">
												<?php if ($product['bf_3month']) { ?>
													КВРТ: <?php echo $product['bf_3month'];?> 
												<?php } else { ?>
													<span style="color: #cf4a61;"><i class="fa fa-exclamation-triangle"></i></span>
												<?php } ?>
											</div>
										</td>

										<td class="center" style=" white-space:nowrap; font-weight:700;">
											<div>
												<?php if ($product['bf_halfyear']) { ?>
													ПЛГД: <?php echo $product['bf_halfyear'];?> 
												<?php } else { ?>
													<span style="color: #cf4a61;"><i class="fa fa-exclamation-triangle"></i></span>
												<?php } ?>
											</div>

											<div style="margin-top:5px;">
												<?php if ($product['bf_year']) { ?>
													ГОД: <?php echo $product['bf_year'];?> 
												<?php } else { ?>
													<span style="color: #cf4a61;"><i class="fa fa-exclamation-triangle"></i></span>
												<?php } ?>
											</div>
										</td>

										<td class="center" style=" white-space:nowrap; font-weight:700;">
											<div>
												<? if ($product['quantity_stock'] <= 1) { ?>
													<span style="color: #cf4a61;"><i class="fa fa-bars"></i> <?php echo $product['quantity_stock']; ?> шт.</span>
												<? } elseif ($product['quantity_stock'] <= 5) { ?>
													<span style="color: #FFA500;"><i class="fa fa-bars"></i> <?php echo $product['quantity_stock']; ?> шт.</span>
												<?php } else { ?>
													<span style="color: #008000;"><i class="fa fa-bars"></i> <?php echo $product['quantity_stock']; ?> шт.</span>
												<?php } ?>
											</div>

											<?php if ($product['quantity_stockwait']) { ?>
												<div style="margin-top:5px;">
													<? if ($product['quantity_stockwait'] <= 1) { ?>
														<span style="color: #cf4a61;"><i class="fa fa-truck"></i> <?php echo $product['quantity_stockwait']; ?> шт.</span>
													<? } elseif ($product['quantity_stockwait'] <= 5) { ?>
														<span style="color: #FFA500;"><i class="fa fa-truck"></i> <?php echo $product['quantity_stockwait']; ?> шт.</span>
													<?php } else { ?>
														<span style="color: #008000;"><i class="fa fa-truck"></i> <?php echo $product['quantity_stockwait']; ?> шт.</span>
													<?php } ?>
												</div>
											<?php } ?>
										</td>



										<td class="right" style="width:35px;">
											<a class="button" href="<?php echo $product['edit']; ?>" target="_blank"><i class="fa fa-edit"></i></a>
											<a class="button" href="<?php echo $product['view']; ?>" target="_blank"><i class="fa fa-eye"></i></a>
										</td>
									</tr>
								<?php } ?>
							<?php } else { ?>
								<tr>
									<td class="center" colspan="8"><?php echo $text_no_results; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</form>
				<div class="pagination"><?php echo $pagination; ?></div>
			</div>
		</div>
	</div>
	<style type="text/css">
		input[type="number"]{width:60px!important; font-weight:700}
		.bold-inputs input[type="text"]{width:60px!important; font-weight:700}
		input.onfocusedit_direct, textarea.onfocusedit_direct, textarea.onfocusout_edit_history,  select.onchangeedit_direct, input.onchangeedit_direct, textarea.onfocusedit_source, textarea.onfocusedit_customer{border-left-color:#4ea24e;}

		input.onfocusedit_direct.done, textarea.onfocusedit_direct.done, textarea.onfocusout_edit_history.done, select.onchangeedit_direct.done, input.onchangeedit_direct.done, textarea.onfocusedit_source.done, textarea.onfocusedit_customer.done{border-color:#4ea24e;-webkit-transition : border 500ms ease-out;-moz-transition : border 500ms ease-out; -o-transition : border 500ms ease-out;transition : border 500ms ease-out;}

		input.onfocusedit_direct.done+span:after, textarea.onfocusedit_direct.done+span:after, textarea.onfocusout_edit_history.done+span:after, select.onchangeedit_direct.done+span:after, textarea.onfocusedit_source.done+span:after,.onchangeedit_orderproduct.done+label+span:after, textarea.onfocusedit_customer.done+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#4ea24e;content:"\f00c"}

		input.onfocusedit_direct.loading+span:after, textarea.onfocusedit_direct.loading+span:after, textarea.onfocusout_edit_history.loading+span:after, select.onchangeedit_direct.loading+span:after, textarea.onfocusedit_source.loading+span:after,.onchangeedit_orderproduct.loading+label+span:after,textarea.onfocusedit_customer.loading+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#e4c25a;content:"\f021"}

		input.onfocusedit_direct.error+span:after, textarea.onfocusedit_direct.error+span:after, textarea.onfocusout_edit_history.error+span:after, select.onchangeedit_direct.error+span:after, textarea.onfocusedit_source.error+span:after, .onchangeedit_orderproduct.error+label+span:after,textarea.onfocusedit_customer.error+span:after{padding:2px;width:20px;height:20px;display:inline-block;font-family:FontAwesome;font-size:20px;color:#cf4a61;content:"\f071"}

	</style>
	<script type="text/javascript">
		$('input.onfocusedit_direct').on('focusout, change', function(){
			var _value = $(this).val();
			var _el = $(this);
			var _elspan = $(this).next('span');
			var _product_id = $(this).attr('data-product-id');
			var _field = $(this).attr('data-field');

			$.ajax({
				url : 'index.php?route=catalog/priceva/updateProductFieldAjax&token=<? echo $token; ?>',
				type: 'POST',
				dataType : 'text',
				data : {
					product_id : _product_id,
					field : _field,
					value : _value,
				},
				beforeSend : function(){
					_el.removeClass('done').addClass('loading');
				},
				success : function(text){
					_el.removeClass('loading').addClass('done');

					if (text == 'OK'){
						_el.after('<div><small style="color:#51a881"><i class="fa fa-check-circle"></i> KP: OK</small></div>');
					} else {
						_el.after('<div><small style="color:#cf4a61"><i class="fa fa-exclamation-triangle"></i> KP: ' + text + '</small></div>');
					}
				},
				error : function(error){
					_el.removeClass('loading').addClass('error');
					console.log(error);
				}			
			});		
		});

	</script>
	<script type="text/javascript"><!--
	function filter() {
		url = 'index.php?route=catalog/priceva&token=<?php echo $token; ?>';

		var filter_name = $('input[name=\'filter_name\']').attr('value');

		if (filter_name) {
			url += '&filter_name=' + encodeURIComponent(filter_name);
		}

		var filter_links_only_selected = $('input[name=\'filter_links_only_selected\']:checked').val();

		if (filter_links_only_selected) {
			url += '&filter_links_only_selected=' + encodeURIComponent(filter_links_only_selected);
		}

		var filter_competitor_stock = $('input[name=\'filter_competitor_stock\']:checked').val();

		if (filter_competitor_stock) {
			url += '&filter_competitor_stock=' + encodeURIComponent(filter_competitor_stock);
		}

		var filter_competitor_not_stock = $('input[name=\'filter_competitor_not_stock\']:checked').val();

		if (filter_competitor_not_stock) {
			url += '&filter_competitor_not_stock=' + encodeURIComponent(filter_competitor_not_stock);
		}

		var filter_competitor_stock_all = $('input[name=\'filter_competitor_stock_all\']:checked').val();

		if (filter_competitor_stock_all) {
			url += '&filter_competitor_stock_all=' + encodeURIComponent(filter_competitor_stock_all);
		}

		var filter_kitchenprofi_not_stock = $('input[name=\'filter_kitchenprofi_not_stock\']:checked').val();

		if (filter_kitchenprofi_not_stock) {
			url += '&filter_kitchenprofi_not_stock=' + encodeURIComponent(filter_kitchenprofi_not_stock);
		}

		var filter_kitchenprofi_not_stock = $('input[name=\'filter_kitchenprofi_not_stock\']:checked').val();

		if (filter_kitchenprofi_not_stock) {
			url += '&filter_kitchenprofi_not_stock=' + encodeURIComponent(filter_kitchenprofi_not_stock);
		}

		var filter_links_only_selected = $('input[name=\'filter_links_only_selected\']:checked').val();

		if (filter_links_only_selected) {
			url += '&filter_links_only_selected=' + encodeURIComponent(filter_links_only_selected);
		}

		var filter_kitchenprofi_stockwait = $('input[name=\'filter_kitchenprofi_stockwait\']:checked').val();

		if (filter_kitchenprofi_stockwait) {
			url += '&filter_kitchenprofi_stockwait=' + encodeURIComponent(filter_kitchenprofi_stockwait);
		}

		var filter_kitchenprofi_not_stockwait = $('input[name=\'filter_kitchenprofi_not_stockwait\']:checked').val();

		if (filter_kitchenprofi_not_stockwait) {
			url += '&filter_kitchenprofi_not_stockwait=' + encodeURIComponent(filter_kitchenprofi_not_stockwait);
		}

		var filter_dates_periods = $('input[name=\'filter_dates_periods\']:checked').val();

		if (filter_dates_periods) {
			url += '&filter_dates_periods=' + encodeURIComponent(filter_dates_periods);
		}

		var filter_store_id = $('select[name=\'filter_store_id\']').children("option:selected").val();

		if (filter_store_id != '*') {
			url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
		}

		var filter_manufacturer_id = $('select[name=\'filter_manufacturer_id\']').children("option:selected").val();

		if (filter_manufacturer_id != '*') {
			url += '&filter_manufacturer_id=' + encodeURIComponent(filter_manufacturer_id);
		}

		var filter_priceva_category = $('select[name=\'filter_priceva_category\']').children("option:selected").val();

		if (filter_priceva_category != '*') {
			url += '&filter_priceva_category=' + encodeURIComponent(filter_priceva_category);
		}

		var filter_competitors = $('input:checkbox:checked.filter_competitors').map(function(){
			return this.value; }).get().join(",");

		if (filter_competitors) {
			url += '&filter_competitors=' + encodeURIComponent(filter_competitors);
		}

		var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');

		if (filter_quantity) {
			url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
		}


		location = url;
	}
	//--></script> 
	<script type="text/javascript"><!--
	$('#form input').keydown(function(e) {
		if (e.keyCode == 13) {
			filter();
		}
	});
	//--></script> 

	<?php echo $footer; ?>	