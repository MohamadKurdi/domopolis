		<div id="tab-rainforest">
			<h2>Настройки воркеров Rainforest API</h2>

			<table class="form">
				<tr>
					<td style="width:20%">
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Парсер новых товаров Amazon</span></p>
							<select name="config_rainforest_enable_new_parser">
								<?php if ($config_rainforest_enable_new_parser) { ?>
									<option value="1" selected="selected">Включить</option>
									<option value="0">Отключить</option>
								<?php } else { ?>													
									<option value="1">Включить</option>
									<option value="0"  selected="selected">Отключить</option>
								<? } ?>
							</select>
						</div>
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-clock-o"></i> Время работы</span></p>

							<input type="time" name="config_rainforest_new_parser_time_start" value="<?php echo $config_rainforest_new_parser_time_start; ?>" size="50" style="width:70px;" /> - 
							<input type="time" name="config_rainforest_new_parser_time_end" value="<?php echo $config_rainforest_new_parser_time_end; ?>" size="50" style="width:70px;" />
						</div>

					</td>

					<td style="width:20%">
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Парсер данных о товарах Amazon</span></p>
							<select name="config_rainforest_enable_data_parser">
								<?php if ($config_rainforest_enable_data_parser) { ?>
									<option value="1" selected="selected">Включить</option>
									<option value="0">Отключить</option>
								<?php } else { ?>													
									<option value="1">Включить</option>
									<option value="0"  selected="selected">Отключить</option>
								<? } ?>
							</select>
						</div>
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-clock-o"></i> Время работы</span></p>

							<input type="time" name="config_rainforest_data_parser_time_start" value="<?php echo $config_rainforest_data_parser_time_start; ?>" size="50" style="width:70px;" /> - 
							<input type="time" name="config_rainforest_data_parser_time_end" value="<?php echo $config_rainforest_data_parser_time_end; ?>" size="50" style="width:70px;" />
						</div>
						
					</td>

					<td style="width:20%">
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Разгребатель технической категории</span></p>
							<select name="config_rainforest_enable_tech_category_parser">
								<?php if ($config_rainforest_enable_tech_category_parser) { ?>
									<option value="1" selected="selected">Включить</option>
									<option value="0">Отключить</option>
								<?php } else { ?>													
									<option value="1">Включить</option>
									<option value="0"  selected="selected">Отключить</option>
								<? } ?>
							</select>
						</div>
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-clock-o"></i> Время работы</span></p>

							<input type="time" name="config_rainforest_tech_category_parser_time_start" value="<?php echo $config_rainforest_tech_category_parser_time_start; ?>" size="50" style="width:70px;" /> - 
							<input type="time" name="config_rainforest_tech_category_parser_time_end" value="<?php echo $config_rainforest_tech_category_parser_time_end; ?>" size="50" style="width:70px;" />
						</div>
					</td>

					<td style="width:20%">
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Парсер данных о товарах Amazon L2</span></p>
							<select name="config_rainforest_enable_data_l2_parser">
								<?php if ($config_rainforest_enable_data_l2_parser) { ?>
									<option value="1" selected="selected">Включить</option>
									<option value="0">Отключить</option>
								<?php } else { ?>													
									<option value="1">Включить</option>
									<option value="0"  selected="selected">Отключить</option>
								<? } ?>
							</select>
						</div>
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-clock-o"></i> Время работы</span></p>

							<input type="time" name="config_rainforest_data_l2_parser_time_start" value="<?php echo $config_rainforest_data_l2_parser_time_start; ?>" size="50" style="width:70px;" /> - 
							<input type="time" name="config_rainforest_data_l2_parser_time_end" value="<?php echo $config_rainforest_data_l2_parser_time_end; ?>" size="50" style="width:70px;" />
						</div>
					</td>

					<td style="width:20%">
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Получение офферов с Amazon</span></p>
							<select name="config_rainforest_enable_offers_parser">
								<?php if ($config_rainforest_enable_offers_parser) { ?>
									<option value="1" selected="selected">Включить</option>
									<option value="0">Отключить</option>
								<?php } else { ?>													
									<option value="1">Включить</option>
									<option value="0"  selected="selected">Отключить</option>
								<? } ?>
							</select>
						</div>
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-clock-o"></i> Время работы</span></p>

							<input type="time" name="config_rainforest_offers_parser_time_start" value="<?php echo $config_rainforest_offers_parser_time_start; ?>" size="50" style="width:70px;" /> - 
							<input type="time" name="config_rainforest_offers_parser_time_end" value="<?php echo $config_rainforest_offers_parser_time_end; ?>" size="50" style="width:70px;" />
						</div>
					</td>
				</tr>
			</tr>

			<tr>
				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Очередь ручного добавления</span></p>
						<select name="config_rainforest_enable_add_queue_parser">
							<?php if ($config_rainforest_enable_add_queue_parser) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-clock-o"></i> Время работы</span></p>

						<input type="time" name="config_rainforest_add_queue_parser_time_start" value="<?php echo $config_rainforest_add_queue_parser_time_start; ?>" size="50" style="width:70px;" /> - 
						<input type="time" name="config_rainforest_add_queue_parser_time_end" value="<?php echo $config_rainforest_add_queue_parser_time_end; ?>" size="50" style="width:70px;" />
					</div>
				</td>


				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Поиск и обновление ASIN</span></p>
						<select name="config_rainforest_enable_recoverasins_parser">
							<?php if ($config_rainforest_enable_recoverasins_parser) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-clock-o"></i> Время работы</span></p>

						<input type="time" name="config_rainforest_recoverasins_parser_time_start" value="<?php echo $config_rainforest_recoverasins_parser_time_start; ?>" size="50" style="width:70px;" /> - 
						<input type="time" name="config_rainforest_recoverasins_parser_time_end" value="<?php echo $config_rainforest_recoverasins_parser_time_end; ?>" size="50" style="width:70px;" />
					</div>
				</td>

				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Очередь добавления вариантов</span></p>
						<select name="config_rainforest_enable_add_variants_queue_parser">
							<?php if ($config_rainforest_enable_add_variants_queue_parser) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-clock-o"></i> Время работы</span></p>

						<input type="time" name="config_rainforest_add_variants_queue_parser_time_start" value="<?php echo $config_rainforest_add_variants_queue_parser_time_start; ?>" size="50" style="width:70px;" /> - 
						<input type="time" name="config_rainforest_add_variants_queue_parser_time_end" value="<?php echo $config_rainforest_add_variants_queue_parser_time_end; ?>" size="50" style="width:70px;" />
					</div>
				</td>

				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Отложить изменение цен</span></p>
						<select name="config_rainforest_delay_price_setting">
							<?php if ($config_rainforest_delay_price_setting) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Отложить изменение наличия</span></p>
						<select name="config_rainforest_delay_stock_setting">
							<?php if ($config_rainforest_delay_stock_setting) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Ручное исправление</span></p>
						<select name="config_rainforest_enable_nooffers_parser">
							<?php if ($config_rainforest_enable_nooffers_parser) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>

				</td>

				<td style="width:20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Отложить офферы для очереди</span></p>
						<select name="config_rainforest_delay_queue_offers">
							<?php if ($config_rainforest_delay_queue_offers) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Отложить варианты для очереди</span></p>
						<select name="config_rainforest_delay_queue_variants">
							<?php if ($config_rainforest_delay_queue_variants) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Убрать кнопку "добавить всё"</span></p>
						<select name="config_rainforest_disable_add_all_button">
							<?php if ($config_rainforest_disable_add_all_button) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
						<span class="help">Если включить опцию, возможность массово добавлять будет отключена</span>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Не разрешать добавление без категорий</span></p>
						<select name="config_rainforest_do_not_add_without_category">
							<?php if ($config_rainforest_do_not_add_without_category) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>

				</td>


			</tr>

			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Парсер дерева категорий</span></p>
					<select name="config_rainforest_enable_category_tree_parser">
						<?php if ($config_rainforest_enable_category_tree_parser) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Валидация ASIN</span></p>
					<select name="config_rainforest_enable_asins_parser">
						<?php if ($config_rainforest_enable_asins_parser) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Валидация EAN/GTIN</span></p>
					<select name="config_rainforest_enable_eans_parser">
						<?php if ($config_rainforest_enable_eans_parser) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Генератор SEO-данных</span></p>
					<select name="config_enable_seogen_cron">
						<?php if ($config_enable_seogen_cron) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>
			</td>

			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Паралельная очередь офферов</span></p>
					<select name="config_rainforest_enable_offersqueue_parser">
						<?php if ($config_rainforest_enable_offersqueue_parser) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-clock-o"></i> Время работы</span></p>

					<input type="time" name="config_rainforest_offersqueue_parser_time_start" value="<?php echo $config_rainforest_offersqueue_parser_time_start; ?>" size="50" style="width:70px;" /> - 
					<input type="time" name="config_rainforest_offersqueue_parser_time_end" value="<?php echo $config_rainforest_offersqueue_parser_time_end; ?>" size="50" style="width:70px;" />
				</div>
			</td>

			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Воркер репрайсинга</span></p>
					<select name="config_rainforest_enable_reprice_cron">
						<?php if ($config_rainforest_enable_reprice_cron) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-clock-o"></i> Время работы</span></p>

					<input type="time" name="config_rainforest_reprice_cron_time_start" value="<?php echo $config_rainforest_reprice_cron_time_start; ?>" size="50" style="width:70px;" /> - 
					<input type="time" name="config_rainforest_reprice_cron_time_end" value="<?php echo $config_rainforest_reprice_cron_time_end; ?>" size="50" style="width:70px;" />
				</div>

			</td>

			<td style="width:20%">


			</td>

			<td style="width:20%">


			</td>
		</tr>
	</table>


	<h2>Rainforest API (получение цен и прочей шляпы из Amazon)</h2>
	<table class="form">
		<tr>
			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Включить rfAPI</span></p>
					<select name="config_rainforest_enable_api">
						<?php if ($config_rainforest_enable_api) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Ключ rfAPI</span></p>
					<input type="text" name="config_rainforest_api_key" value="<?php echo $config_rainforest_api_key; ?>" size="50" style="width:150px;" />
					<br/>
					<span class="help" style="display:inline-block;border-bottom:1px dashed black; cursor:pointer;" onclick="getZipRNFcodes();">получить zipcodes</span>
					<script>
						function getZipRNFcodes(){
							var zipCodeFields = [];
							for (var i = 1; i <= 5; i++) {
								var field = $('input[name=config_rainforest_api_zipcode_' + i + ']');
								zipCodeFields.push(field);
							}

							$.ajax({
								url: 'index.php?route=setting/rnf/getZipCodes&token=<?php echo $token; ?>',
								method: 'GET',
								dataType: 'json',
								success: function (ajaxResponse) {
									for (var i = 0; i < zipCodeFields.length; i++) {
										if (i < ajaxResponse.length) {
											console.log(ajaxResponse[i]);
											zipCodeFields[i].val(ajaxResponse[i]);
											zipCodeFields[i].trigger('change');
										} else {
											zipCodeFields[i].val('');
											zipCodeFields[i].trigger('change');
										}
									}
								},
								error: function () {
									console.error('AJAX request failed');
								}
							});
						}
					</script>
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Домен rfAPI - 1</span></p>
					<select name="config_rainforest_api_domain_1">
						<?php foreach ($amazon_domains as $amazon_domain) { ?>
							<option value="<?php echo $amazon_domain?>" <?php if ($config_rainforest_api_domain_1 == $amazon_domain) { ?>selected="selected"<?php }?>><?php echo $amazon_domain?></option>

						<?php } ?>
					</select>
				</div>
			</td>									
			
			<td style="width:20%">				
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">ZipCode rfAPI - 1</span></p>
					<input type="text" name="config_rainforest_api_zipcode_1" value="<?php echo $config_rainforest_api_zipcode_1; ?>" size="50" style="width:100px;" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">ZipCode rfAPI - 2</span></p>
					<input type="text" name="config_rainforest_api_zipcode_2" value="<?php echo $config_rainforest_api_zipcode_2; ?>" size="50" style="width:100px;" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">ZipCode rfAPI - 3</span></p>
					<input type="text" name="config_rainforest_api_zipcode_3" value="<?php echo $config_rainforest_api_zipcode_3; ?>" size="50" style="width:100px;" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">ZipCode rfAPI - 4</span></p>
					<input type="text" name="config_rainforest_api_zipcode_4" value="<?php echo $config_rainforest_api_zipcode_4; ?>" size="50" style="width:100px;" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">ZipCode rfAPI - 5</span></p>
					<input type="text" name="config_rainforest_api_zipcode_5" value="<?php echo $config_rainforest_api_zipcode_5; ?>" size="50" style="width:100px;" />
				</div>
			</td>
			
			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Модель работы</span></p>
					<select name="config_rainforest_category_model">

						<?php foreach (['standard', 'bestsellers', 'deals'] as $rainforest_model) { ?>
							<option value="<?php echo $rainforest_model; ?>" <?php if ($rainforest_model == $config_rainforest_category_model) { ?> selected="selected"<?php } ?>><?php echo $rainforest_model; ?></option>
						<?php } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Рекурсивно добавлять товар</span></p>
					<select name="config_rainforest_enable_recursive_adding">
						<?php if ($config_rainforest_enable_recursive_adding) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>	

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Периодически чистить INVALID ASIN</span></p>
					<select name="config_rainforest_delete_invalid_asins">
						<?php if ($config_rainforest_delete_invalid_asins) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>	

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Наполнять AlsoBought товары</span></p>
					<select name="config_also_bought_auto_enable">
						<?php if ($config_also_bought_auto_enable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Автоматически создавать бренды</span></p>
					<select name="config_rainforest_auto_create_manufacturers">
						<?php if ($config_rainforest_auto_create_manufacturers) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>									
			</td>

			<td style="width:20%">

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">ID технической категории</span></p>
					<input type="number" name="config_rainforest_default_technical_category_id" value="<?php echo $config_rainforest_default_technical_category_id; ?>" size="50" style="width:90px;" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Сканировать ТК</span></p>
					<select name="config_rainforest_check_technical_category_id">
						<?php if ($config_rainforest_check_technical_category_id) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">ID неопределенной категории</span></p>
					<input type="number" name="config_rainforest_default_unknown_category_id" value="<?php echo $config_rainforest_default_unknown_category_id; ?>" size="50" style="width:90px;" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Сканировать НК</span></p>
					<select name="config_rainforest_check_unknown_category_id">
						<?php if ($config_rainforest_check_unknown_category_id) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Наполнять Related категории</span></p>
					<select name="config_related_categories_auto_enable">
						<?php if ($config_related_categories_auto_enable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>
				
			</td>

			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Загружать макс вариантов</span></p>
					=<input type="number" name="config_rainforest_max_variants" value="<?php echo $config_rainforest_max_variants; ?>" size="50" style="width:100px;" />
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Пропускать товары с вариантами</span></p>
					><input type="number" name="config_rainforest_skip_variants" value="<?php echo $config_rainforest_skip_variants; ?>" size="50" style="width:100px;" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Не добавлять товары с ценой меньше</span></p>
					<input type="number" name="config_rainforest_skip_low_price_products" value="<?php echo $config_rainforest_skip_low_price_products; ?>" size="50" style="width:100px;" /> <i class="fa fa-eur"></i>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Удалять товары с ценой меньше</span></p>
					<select name="config_rainforest_drop_low_price_products">
						<?php if ($config_rainforest_drop_low_price_products) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>	
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Ограничить описание до перевода</span></p>
					<input type="number" name="config_rainforest_description_symbol_limit" value="<?php echo $config_rainforest_description_symbol_limit; ?>" size="50" style="width:100px;" />
				</div>
			</td>
			
			
			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Фильтры rfAPI - 1</span></p>
					<div class="scrollbox" style="height:200px;">
						<?php $class = 'odd'; ?>
						<?php foreach ($amazon_filters as $amazon_filter) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div class="<?php echo $class; ?>">
								<?php if (!empty($config_rainforest_amazon_filters_1) && in_array($amazon_filter, $config_rainforest_amazon_filters_1)) { ?>
									<input id="config_rainforest_amazon_filters_1<?php echo $amazon_filter; ?>" class="checkbox" type="checkbox" name="config_rainforest_amazon_filters_1[]" value="<?php echo $amazon_filter; ?>" checked="checked" />
									<label for="config_rainforest_amazon_filters_1<?php echo $amazon_filter; ?>"><?php echo $amazon_filter; ?></label>
								<?php } else { ?>
									<input id="config_rainforest_amazon_filters_1<?php echo $amazon_filter; ?>" class="checkbox" type="checkbox" name="config_rainforest_amazon_filters_1[]" value="<?php echo $amazon_filter; ?>" />
									<label for="config_rainforest_amazon_filters_1<?php echo $amazon_filter; ?>"><?php echo $amazon_filter; ?></label>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Добавлять в магазины</span></p>
					<div class="scrollbox" style="min-height: 150px;">
						<?php $class = 'even'; ?>
						<?php foreach ($stores as $store) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div class="<?php echo $class; ?>">
								<?php if (in_array($store['store_id'], $config_rainforest_add_to_stores)) { ?>
									<input id="config_rainforest_add_to_stores_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="config_rainforest_add_to_stores[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
									<label for="config_rainforest_add_to_stores_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
								<?php } else { ?>
									<input id="config_rainforest_add_to_stores_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="config_rainforest_add_to_stores[]" value="<?php echo $store['store_id']; ?>" />
									<label for="config_rainforest_add_to_stores_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
								<?php } ?>
							</div>
						<?php } ?>

					</div>
					<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
				</div>
			</td>									
		</tr>

		<tr>
			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Обрабатывать compare_with_similar</span></p>
					<select name="config_rainforest_enable_compare_with_similar_parsing">
						<?php if ($config_rainforest_enable_compare_with_similar_parsing) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>										
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Обрабатывать frequently_bought_together</span></p>
					<select name="config_rainforest_enable_related_parsing">
						<?php if ($config_rainforest_enable_related_parsing) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>	
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Обрабатывать sponsored_products</span></p>
					<select name="config_rainforest_enable_sponsored_parsing">
						<?php if ($config_rainforest_enable_sponsored_parsing) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Обрабатывать similar_to_consider</span></p>
					<select name="config_rainforest_enable_similar_to_consider_parsing">
						<?php if ($config_rainforest_enable_similar_to_consider_parsing) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>
			</td>

			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Обрабатывать view_to_purchase</span></p>
					<select name="config_rainforest_enable_view_to_purchase_parsing">
						<?php if ($config_rainforest_enable_view_to_purchase_parsing) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Обрабатывать also_viewed</span></p>
					<select name="config_rainforest_enable_also_viewed_parsing">
						<?php if ($config_rainforest_enable_also_viewed_parsing) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Обрабатывать also_bought</span></p>
					<select name="config_rainforest_enable_also_bought_parsing">
						<?php if ($config_rainforest_enable_also_bought_parsing) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Обрабатывать shop_by_look</span></p>
					<select name="config_rainforest_enable_shop_by_look_parsing">
						<?php if ($config_rainforest_enable_shop_by_look_parsing) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>
			</td>

			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Добавлять compare_with_similar</span></p>
					<select name="config_rainforest_enable_compare_with_similar_adding">
						<?php if ($config_rainforest_enable_compare_with_similar_adding) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>										
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Добавлять frequently_bought_together</span></p>
					<select name="config_rainforest_enable_related_adding">
						<?php if ($config_rainforest_enable_related_adding) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>	
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Добавлять sponsored_products</span></p>
					<select name="config_rainforest_enable_sponsored_adding">
						<?php if ($config_rainforest_enable_sponsored_adding) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Добавлять similar_to_consider</span></p>
					<select name="config_rainforest_enable_similar_to_consider_adding">
						<?php if ($config_rainforest_enable_similar_to_consider_adding) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>
			</td>

			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Добавлять view_to_purchase</span></p>
					<select name="config_rainforest_enable_view_to_purchase_adding">
						<?php if ($config_rainforest_enable_view_to_purchase_adding) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Добавлять also_viewed</span></p>
					<select name="config_rainforest_enable_also_viewed_adding">
						<?php if ($config_rainforest_enable_also_viewed_adding) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Добавлять also_bought</span></p>
					<select name="config_rainforest_enable_also_bought_adding">
						<?php if ($config_rainforest_enable_also_bought_adding) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Добавлять shop_by_look</span></p>
					<select name="config_rainforest_enable_shop_by_look_adding">
						<?php if ($config_rainforest_enable_shop_by_look_adding) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>
			</td>

			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Добавлять отзывы из top_reviews</span></p>
					<select name="config_rainforest_enable_review_adding">
						<?php if ($config_rainforest_enable_review_adding) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Мин. рейтинг (1-5)</span></p>
					<input type="number" name="config_rainforest_min_review_rating" value="<?php echo $config_rainforest_min_review_rating; ?>" size="50" style="width:100px;" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Макс на товар</span></p>
					<input type="number" name="config_rainforest_max_review_per_product" value="<?php echo $config_rainforest_max_review_per_product; ?>" size="50" style="width:100px;" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Макс символов</span></p>
					<input type="number" name="config_rainforest_max_review_length" value="<?php echo $config_rainforest_max_review_length; ?>" size="50" style="width:100px;" />
				</div>

			</td>

			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">🤖 Экспортные названия с OpenAI</span></p>
					<select name="config_rainforest_export_names_with_openai">
						<?php if ($config_rainforest_export_names_with_openai) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">🤖 Адекватные названия с OpenAI</span></p>
					<select name="config_rainforest_short_names_with_openai">
						<?php if ($config_rainforest_short_names_with_openai) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>
			</td>
		</tr>

		<tr>
			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Переводить</span></p>
					<select name="config_rainforest_enable_translation">
						<?php if ($config_rainforest_enable_translation) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Язык Amazon</span></p>
					<select name="config_rainforest_source_language">
						<?php foreach ($languages as $language) { ?>
							<?php if ($language['code'] == $config_rainforest_source_language) { ?>
								<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
				</div>
			</td>
			

			<td style="width:20%">
				<?php foreach ($languages as $language) { ?>
					<?php if ($language['code'] != $config_rainforest_source_language) { ?>
						<div>
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Переводить на <?php echo $language['code']; ?></span></p>
							<select name="config_rainforest_enable_language_<?php echo $language['code']; ?>">
								<?php if (${'config_rainforest_enable_language_' . $language['code']}) { ?>
									<option value="1" selected="selected">Включить</option>
									<option value="0">Отключить</option>
								<?php } else { ?>													
									<option value="1">Включить</option>
									<option value="0"  selected="selected">Отключить</option>
								<? } ?>
							</select>											
						</div>
					<?php } ?>
				<?php } ?>
			</td>


			<td style="width:20%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Обновлять кат. раз в Х дней</span></p>
					<input type="number" name="config_rainforest_category_update_period" value="<?php echo $config_rainforest_category_update_period; ?>" size="50" style="width:100px;" />
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Обновлять цену раз в Х дней</span></p>
					<input type="number" name="config_rainforest_update_period" value="<?php echo $config_rainforest_update_period; ?>" size="50" style="width:100px;" />
				</div>
			</td>

			<td style="width:20%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">TG группа уведомлений</span></p>
				<input type="text" name="config_rainforest_tg_alert_group_id" value="<?php echo $config_rainforest_tg_alert_group_id; ?>" size="50" style="width:250px;" />
			</td>

			<td style="width:20%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Автоматическое дерево</span></p>
				<select name="config_rainforest_enable_auto_tree">
					<?php if ($config_rainforest_enable_auto_tree) { ?>
						<option value="1" selected="selected">Включить</option>
						<option value="0">Отключить</option>
					<?php } else { ?>													
						<option value="1">Включить</option>
						<option value="0"  selected="selected">Отключить</option>
					<? } ?>
				</select>
			</td>

			<td style="width:20%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Корневые категории Amazon</span></p>
				<textarea name="config_rainforest_root_categories" rows="10"><?php echo $config_rainforest_root_categories; ?></textarea>
			</td>

		</tr>
	</table>

	<h2><i class="fa fa-search"></i> Ценообразование Amazon + RainForest API Основная формула ЦО</h2>

	<table class="form">
		<tr>
			<td width="60%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Формула подсчета цены по умолчанию</span></p>
				<input type="text" name="config_rainforest_main_formula" value="<?php echo $config_rainforest_main_formula; ?>" style="width:90%;" />
			</td>

			<td width="10%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Шагов</span></p>
				<input type="number" step="1" name="config_rainforest_main_formula_count" value="<?php echo $config_rainforest_main_formula_count; ?>" size="50" style="width:100px;" />
			</td>

			<td width="30%">
				<span class="help">
					<i class="fa fa-info"></i> <b>PRICE</b>  = цена товара у поставщика<br />
					<i class="fa fa-info"></i> <b>WEIGHT</b> = подсчитанный вес товара<br />
					<i class="fa fa-info"></i> <b>KG_LOGISTIC</b> = стоимость логистики одного килограмма<br />
					<i class="fa fa-info"></i> <b>VAT_SRC</b> = VAT/НДС страны - поставщика<br />
					<i class="fa fa-info"></i> <b>VAT_DST</b> = VAT/НДС страны - получателя<br />
					<i class="fa fa-info"></i> <b>TAX</b> = дополнительный налог<br />
					<i class="fa fa-info"></i> <b>SUPPLIER</b> = процент поставщика<br />
					<i class="fa fa-info"></i> <b>INVOICE</b> = коэффициент инвойса<br />

					<i class="fa fa-info"></i> <b>:COSTPRICE:</b> = разделитель себестоимости<br />
					
					<i class="fa fa-info"></i> <b>PLUS</b> = знак + нужно заменять на слово, в силу технических ограничений<br />
					<i class="fa fa-info"></i> <b>MULTIPLY</b> = знак * нужно заменять на слово, в силу технических ограничений<br />
					<i class="fa fa-info"></i> <b>DIVIDE</b> = знак / нужно заменять на слово, в силу технических ограничений<br />
				</span>
			</td>
		</tr>								
	</table>

	<table class="form">
		<tr>
			<td width="1%">										
			</td>
			<td width="10%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Цена закупки от, <?php echo $config_currency; ?></span>
			</td>
			<td width="10%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Цена закупки до, <?php echo $config_currency; ?></span>
			</td>
			<td width="10%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Себестоимость, если нет веса</span>
			</td>
			<td width="10%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Если нет веса</span>
			</td>
			<td width="69%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Формула</span>
			</td>
		</tr>
		<?php for ($crmfc = 1; $crmfc <= $config_rainforest_main_formula_count; $crmfc++){ ?>
			<tr>
				<td width="10%">
					<b><?php echo $crmfc; ?></b>
				</td>
				<td width="10%">
					<input type="number" step="1" name="config_rainforest_main_formula_min_<?php echo $crmfc; ?>" value="<?php echo ${'config_rainforest_main_formula_min_' . $crmfc}; ?>" size="50" style="width:100px; border-color:#00ad07;" />
				</td>
				<td width="10%">
					<input type="number" step="1" name="config_rainforest_main_formula_max_<?php echo $crmfc; ?>" value="<?php echo ${'config_rainforest_main_formula_max_' . $crmfc}; ?>" size="50" style="width:100px; border-color:#cf4a61;" />
				</td>
				<td width="10%">
					<input type="number" step="1" name="config_rainforest_main_formula_costprice_<?php echo $crmfc; ?>" value="<?php echo ${'config_rainforest_main_formula_costprice_' . $crmfc}; ?>" size="50" style="width:100px; border-color:#cf4a61;" />
				</td>
				<td width="10%">
					<input type="number" step=".1" name="config_rainforest_main_formula_default_<?php echo $crmfc; ?>" value="<?php echo ${'config_rainforest_main_formula_default_' . $crmfc}; ?>" size="50" style="width:100px; border-color:#D69241;" />
				</td>
				<td width="59%">
					<input type="text" name="config_rainforest_main_formula_overload_<?php echo $crmfc; ?>" value="<?php echo ${'config_rainforest_main_formula_overload_' . $crmfc}; ?>" style="width:90%;  border-color:#7F00FF;" />
				</td>
			</tr>
		<?php } ?>
	</table>


	<h2><i class="fa fa-search"></i> Ценообразование Amazon + RainForest API</h2>

	<table class="form">
		<tr>
			<td style="width:15%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Включить ценообразование A+rfAPI</span></p>
					<select name="config_rainforest_enable_pricing">
						<?php if ($config_rainforest_enable_pricing) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> в случае неоплаты - лучше отключить</span>
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Цена для этой страны ставится по-умолчанию</span></p>
					<select name="config_rainforest_default_store_id">
						<option value="-1" <?php if (-1 == $config_rainforest_default_store_id) { ?>selected="selected"<? } ?>>Переназначать все страны</option>
						<?php foreach ($stores as $store) { ?>
							<option value="<?php echo $store['store_id']; ?>" <?php if ($store['store_id'] == $config_rainforest_default_store_id) { ?>selected="selected"<? } ?>><?php echo $store['name']; ?></option>
						<?php } ?>
					</select>
					<br />
					<span class="help"><i class="fa fa-info"></i> если задано, то цена для этого магазина будет ценой по-умолчанию</span>
				</div>
			</td>
			

			<td style="width:15%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Если нет офферов</span></p>
					<select name="config_rainforest_nooffers_action">
						<?php if ($config_rainforest_nooffers_action) { ?>
							<option value="1" selected="selected">Менять статус</option>
							<option value="0">Ничего не делать</option>
						<?php } else { ?>													
							<option value="1">Менять статус</option>
							<option value="0"  selected="selected">Ничего не делать</option>
						<? } ?>
					</select>
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> что делать при обновлении, если на амазоне нет предложений</span>
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Статус, если нет офферов</span></p>
					<select name="config_rainforest_nooffers_status_id">
						<?php foreach ($stock_statuses as $stock_status) { ?>
							<?php if ($stock_status['stock_status_id'] == $config_rainforest_nooffers_status_id) { ?>
								<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
							<?php } ?>
						<?php } ?>
					</select>
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> установить этот статус товару, если на амазоне нет предложений</span>	
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Макс. дней доставки</span></p>
					<input type="number" name="config_rainforest_max_delivery_days_for_offer" value="<?php echo $config_rainforest_max_delivery_days_for_offer; ?>" size="50" style="width:100px;" />
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> оффер будет проигнорирован, если доставка занимает больше дней</span>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Макс. цена доставки</span></p>
					<input type="number" name="config_rainforest_max_delivery_price" value="<?php echo $config_rainforest_max_delivery_price; ?>" size="50" style="width:100px;" /> EUR
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> оффер будет проигнорирован, если доставка стоит больше</span>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Макс. цена доставки Х</span></p>
					<input type="number" name="config_rainforest_max_delivery_price_multiplier" value="<?php echo $config_rainforest_max_delivery_price_multiplier; ?>" size="50" style="width:100px;" />
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> оффер будет проигнорирован, если доставка больше цены в Х раз</span>
				</div>
			</td>

			<td style="width:15%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Удалять если нет офферов</span></p>
					<select name="config_rainforest_delete_no_offers">
						<?php if ($config_rainforest_delete_no_offers) { ?>
							<option value="1" selected="selected">Удалять</option>
							<option value="0">Ничего не делать</option>
						<?php } else { ?>													
							<option value="1">Удалять</option>
							<option value="0"  selected="selected">Ничего не делать</option>
						<? } ?>
					</select>
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> если офферов нету, удалять товар</span>	
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Порог "нет офферов"</span></p>
					<input type="number" name="config_rainforest_delete_no_offers_counter" value="<?php echo $config_rainforest_delete_no_offers_counter; ?>" size="50" style="width:100px;" />
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> если включено удаление то удаляются товары у которых не было офферов Х раз</span>
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Также изменять количество</span></p>
					<select name="config_rainforest_nooffers_quantity">
						<?php if ($config_rainforest_nooffers_quantity) { ?>
							<option value="1" selected="selected">Менять количество</option>
							<option value="0">Ничего не делать</option>
						<?php } else { ?>													
							<option value="1">Менять количество</option>
							<option value="0"  selected="selected">Ничего не делать</option>
						<? } ?>
					</select>
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> количество будет изменяться 0-9999</span>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Пропускать с скидкой</span></p>
					<select name="config_rainforest_disable_offers_if_has_special">
						<?php if ($config_rainforest_disable_offers_if_has_special) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Выключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Выключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> пропускать товары с актуальной скидкой</span>	
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Использовать логику парсера</span></p>
					<select name="config_rainforest_disable_offers_use_field_ignore_parse">
						<?php if ($config_rainforest_disable_offers_use_field_ignore_parse) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Выключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Выключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> пропускать товары с "Не обновлять цены"</span>	
				</div>

			</td>

			<td style="width:15%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Пропускать товары, которые были закуплены</span></p>
					<select name="config_rainforest_pass_offers_for_ordered">
						<?php if ($config_rainforest_pass_offers_for_ordered) { ?>
							<option value="1" selected="selected">Да</option>
							<option value="0">Нет</option>
						<?php } else { ?>													
							<option value="1">Да</option>
							<option value="0"  selected="selected">Нет</option>
						<? } ?>
					</select>
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Х дней от последней закупки</span></p>
					<input type="number" step="1" name="config_rainforest_pass_offers_for_ordered_days" value="<?php echo $config_rainforest_pass_offers_for_ordered_days; ?>" style="width:100px;" />
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> если пропускать товары, которые были закуплены, то как давно</span>
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Объемный вес макс Х</span></p>
					<input type="number" step="0.1" name="config_rainforest_volumetric_max_wc_multiplier" value="<?php echo $config_rainforest_volumetric_max_wc_multiplier; ?>" style="width:100px;" />
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> не учитывать объемный вес, если он больше в Х раз</span>
				</div>
			</td>

			<td style="width:15%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Получать офферы только для полностью загруженных</span></p>
					<select name="config_rainforest_enable_offers_only_for_filled">
						<?php if ($config_rainforest_enable_offers_only_for_filled) { ?>
							<option value="1" selected="selected">Да</option>
							<option value="0">Нет, для всех</option>
						<?php } else { ?>													
							<option value="1">Да</option>
							<option value="0"  selected="selected">Нет, для всех</option>
						<? } ?>
					</select>
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> в противном случае - для всех асинов.</span>
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Получать офферы для товаров на складе</span></p>
					<select name="config_rainforest_enable_offers_for_stock">
						<?php if ($config_rainforest_enable_offers_for_stock) { ?>
							<option value="1" selected="selected">Да</option>
							<option value="0">Нет</option>
						<?php } else { ?>													
							<option value="1">Да</option>
							<option value="0"  selected="selected">Нет</option>
						<? } ?>
					</select>	
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> если эта настройка выключена, также не будут изменяться статусы</span>									
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Получать офферы только для добавленных с амазон</span></p>
					<select name="config_rainforest_enable_offers_for_added_from_amazon">
						<?php if ($config_rainforest_enable_offers_for_added_from_amazon) { ?>
							<option value="1" selected="selected">Да</option>
							<option value="0">Нет</option>
						<?php } else { ?>													
							<option value="1">Да</option>
							<option value="0"  selected="selected">Нет</option>
						<? } ?>
					</select>	
					<br />
					<span class="help"><i class="fa fa-exclamation-circle"></i> Флаг added_from_amazon = 1. Игнорируется цена и наличие</span>									
				</div>
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Получать офферы сразу после заказа</span></p>
					<select name="config_rainforest_enable_offers_after_order">
						<?php if ($config_rainforest_enable_offers_after_order) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</div>
			</td>
		</tr>
	</table>
	<table class="form">
		<tr>									
			<?php foreach ($stores as $store) { ?>
				<td width="<?php echo (int)(100/count($stores))?>%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-eur"></i> Стоимость логистики 1 кг: <?php echo $store['name']; ?></span></p>
					<input type="number" step="0.1" name="config_rainforest_kg_price_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_kg_price_' . $store['store_id']}; ?>" style="width:200px;" />

					<br />
					<span class="help"><i class="fa fa-eur"></i> стоимость килограмма для доставки в страну (если 0 - цена не пересчитывается)</span>
				</td>
			<?php } ?>
		</tr>
	</table>

	<table class="form">
		<tr>									
			<?php foreach ($stores as $store) { ?>
				<td width="<?php echo (int)(100/count($stores))?>%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-eur"></i> Использовать объемный вес: <?php echo $store['name']; ?></span></p>
					<select name="config_rainforest_use_volumetric_weight_<?php echo $store['store_id']; ?>">
						<?php if (${'config_rainforest_use_volumetric_weight_' . $store['store_id']}) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
					<br />
					<span class="help"><i class="fa fa-eur"></i> стоимость килограмма для доставки в страну (если 0 - цена не пересчитывается)</span>
				</td>
			<?php } ?>
		</tr>
	</table>

	<table class="form">
		<tr>									
			<?php foreach ($stores as $store) { ?>
				<td width="<?php echo (int)(100/count($stores))?>%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-eur"></i> Коэффициент объемного веса <?php echo $store['name']; ?></span></p>
					<input type="number" step="100" name="config_rainforest_volumetric_weight_coefficient_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_volumetric_weight_coefficient_' . $store['store_id']}; ?>" style="width:200px;" />									
				</td>
			<?php } ?>
		</tr>
	</table>

	<table class="form">
		<tr>									
			<?php foreach ($stores as $store) { ?>
				<td width="<?php echo (int)(100/count($stores))?>%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-eur"></i> Умножать если нет веса: <?php echo $store['name']; ?></span></p>
					<input type="number" step="0.1" name="config_rainforest_default_multiplier_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_default_multiplier_' . $store['store_id']}; ?>" style="width:200px;" />
				</td>
			<?php } ?>
		</tr>
	</table>

	<table class="form">
		<tr>									
			<?php foreach ($stores as $store) { ?>
				<td width="<?php echo (int)(100/count($stores))?>%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-eur"></i> Множитель себестоимости, если нет веса: <?php echo $store['name']; ?></span></p>
					<input type="number" step="0.01" name="config_rainforest_default_costprice_multiplier_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_default_costprice_multiplier_' . $store['store_id']}; ?>" style="width:200px;" />
				</td>
			<?php } ?>
		</tr>
	</table>

	<table class="form">
		<tr>									
			<?php foreach ($stores as $store) { ?>
				<td width="<?php echo (int)(100/count($stores))?>%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF"><i class="fa fa-eur"></i> Максимально Х <?php echo $store['name']; ?></span></p>
					<input type="number" step="0.1" name="config_rainforest_max_multiplier_<?php echo $store['store_id']?>" value="<?php echo ${'config_rainforest_max_multiplier_' . $store['store_id']}; ?>" style="width:200px;" />
				</td>
			<?php } ?>
		</tr>
	</table>

	<table>
		<tr>									
			<td width="20%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Мин. внутр. рейтинг</span></p>
				<input type="number" step="1" name="config_rainforest_supplierminrating_inner" value="<?php echo $config_rainforest_supplierminrating_inner; ?>" style="width:200px;" />

				<br />
				<span class="help"><i class="fa fa-info"></i> если внутренний рейтинг поставщика (задан в справочнике) менее этой цифры, то оффер исключается из подсчетов</span>
			</td>

			<td width="20%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Мин. рейтинг Amazon</span></p>
				<input type="number" step="0.1" name="config_rainforest_supplierminrating" value="<?php echo $config_rainforest_supplierminrating; ?>" style="width:200px;" />

				<br />
				<span class="help"><i class="fa fa-info"></i> если рейтинг на основании отзывов на Amazon менее этой цифры, то оффер исключается из подсчетов</span>
			</td>
		</tr>
	</table>
</div>

