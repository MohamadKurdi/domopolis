<div id="tab-elasticsearch">
	<h2><i class="fa fa-search"></i> Параметры индексирования</h2>
	<table class="form">
		<tr>
			<td style="width:15%">									
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Много model</span></p>
				<select name="config_elasticseach_many_models">
					<?php if ($config_elasticseach_many_models) { ?>
						<option value="1" selected="selected">Да</option>
						<option value="0">Нет</option>
					<?php } else { ?>													
						<option value="1">Да</option>
						<option value="0"  selected="selected">Нет</option>
					<? } ?>
				</select>

				<br />
				<span class="help"><i class="fa fa-search"></i> Разные варианты написания поля model</span>
			</td>

			<td style="width:15%">									
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Много sku</span></p>
				<select name="$config_elasticseach_many_skus">
					<?php if ($config_elasticseach_many_skus) { ?>
						<option value="1" selected="selected">Да</option>
						<option value="0">Нет</option>
					<?php } else { ?>													
						<option value="1">Да</option>
						<option value="0"  selected="selected">Нет</option>
					<? } ?>
				</select>

				<br />
				<span class="help"><i class="fa fa-search"></i> Разные варианты написания поля model</span>
			</td>

			<td style="width:15%">									
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Числа в текст</span></p>
				<select name="config_elasticseach_many_textnumbers">
					<?php if ($config_elasticseach_many_textnumbers) { ?>
						<option value="1" selected="selected">Да</option>
						<option value="0">Нет</option>
					<?php } else { ?>													
						<option value="1">Да</option>
						<option value="0"  selected="selected">Нет</option>
					<? } ?>
				</select>

				<br />
				<span class="help"><i class="fa fa-search"></i> можно искать по "два, три, десять". значительно увеличивает индекс</span>
			</td>

			<td style="width:15%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Индексировать бренды</span></p>
				<select name="config_elasticseach_index_manufacturers">
					<?php if ($config_elasticseach_index_manufacturers) { ?>
					<option value="1" selected="selected">Да</option>
					<option value="0">Нет</option>
					<?php } else { ?>
					<option value="1">Да</option>
					<option value="0"  selected="selected">Нет</option>
					<? } ?>
				</select>

				<br />
				<span class="help"><i class="fa fa-search"></i> увеличивает индекс</span>
			</td>

			<td style="width:15%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Индексировать связки</span></p>
				<select name="config_elasticseach_index_keyworder">
					<?php if ($config_elasticseach_index_keyworder) { ?>
					<option value="1" selected="selected">Да</option>
					<option value="0">Нет</option>
					<?php } else { ?>
					<option value="1">Да</option>
					<option value="0"  selected="selected">Нет</option>
					<? } ?>
				</select>

				<br />
				<span class="help"><i class="fa fa-search"></i> увеличивает индекс</span>
			</td>


			<td style="width:15%">									
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Использовать свои склады</span></p>
				<select name="config_elasticsearch_use_local_stock">
					<?php if ($config_elasticsearch_use_local_stock) { ?>
						<option value="1" selected="selected">Включить</option>
						<option value="0">Отключить</option>
					<?php } else { ?>													
						<option value="1">Включить</option>
						<option value="0"  selected="selected">Отключить</option>
					<? } ?>
				</select>

				<br />
				<span class="help"><i class="fa fa-search"></i> Если включено, товары, которые есть на локальном складе - всегда будут вверху любых результатов поиска. При этом товары, которых нет на складе - значительно пессимизируются в выдаче</span>										
			</td>
		</tr>
	</table>

	<h2><i class="fa fa-search"></i> Параметры поиска</h2>
	<table class="form">
		<tr>
			<td style="width:20%">									
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> FUZZY параметр для подбора товаров</span></p>
				<input type="number" step="0.1" name="config_elasticsearch_fuzziness_product" value="<?php echo $config_elasticsearch_fuzziness_product; ?>" size="3" style="width:100px;" />

				<br />
				<span class="help"><i class="fa fa-search"></i> чем это значение больше, тем больше будет нечетких результатов подбора, при этом поиск будет более широкий, но возможны неверные срабатывания</span>
			</td>

			<td style="width:20%">									
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> FUZZY параметр для подбора категорий</span></p>
				<input type="number" step="0.1" name="config_elasticsearch_fuzziness_category" value="<?php echo $config_elasticsearch_fuzziness_category; ?>" size="3" style="width:100px;" />

				<br />
				<span class="help"><i class="fa fa-search"></i> чем это значение больше, тем больше будет нечетких результатов подбора, при этом поиск будет более широкий, но возможны неверные срабатывания</span>
			</td>

			<td style="width:20%">									
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> FUZZY параметр для автокомплита</span></p>
				<input type="number" step="0.1" name="config_elasticsearch_fuzziness_autcocomplete" value="<?php echo $config_elasticsearch_fuzziness_autcocomplete; ?>" size="3" style="width:100px;" />

				<br />
				<span class="help"><i class="fa fa-search"></i> чем это значение больше, тем больше будет нечетких результатов подбора, при этом поиск будет более широкий, но возможны неверные срабатывания</span>										
			</td>

			<td style="width:20%">									
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Суффикс именования индексов</span></p>
				<input type="text" name="config_elasticsearch_index_suffix" value="<?php echo $config_elasticsearch_index_suffix; ?>" size="20" style="width:100px;" />

				<br />
				<span class="help"><i class="fa fa-search"></i> в случае работы нескольки магазинов на одном движке</span>
			</td>


			<td style="width:20%">									
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><i class="fa fa-search"></i> Минимум символов для поиска</span></p>
				<input type="number" name="config_elasticseach_index_autocomplete_symbols" value="<?php echo $config_elasticseach_index_autocomplete_symbols; ?>" size="20" style="width:100px;" />

				<br />
				<span class="help"><i class="fa fa-search"></i> если меньше - работает только автокомплит</span>
			</td>
		</tr>
	</table>
</div>