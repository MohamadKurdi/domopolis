	<div id="tab-openai">
		<h2>🤖 Общие настройки</h2>
		<table class="form">
			<tr>
				<td width="33%">	
					<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Включить OpenAI</span></p>
					<select name="config_openai_enable">
						<?php if ($config_openai_enable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>									
				</td>
				
				<td width="33%">
					<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">API KEY</span></p>
					<input type="text" name="config_openai_api_key" value="<?php echo $config_openai_api_key; ?>" size="50" style="width:250px;" />
				</td>	

				<td width="33%">
					<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Модель по-умолчанию</span></p>
					<select name="config_openai_default_model">
						<?php foreach ($openai_models_list as $openai_model) { ?>
							<?php if ($config_openai_default_model == $openai_model['id']) { ?>
								<option value="<?php echo $openai_model['id']; ?>" selected="selected"><?php echo $openai_model['id']; ?></option>												
							<?php } else { ?>													
								<option value="<?php echo $openai_model['id']; ?>"><?php echo $openai_model['id']; ?></option>
							<? } ?>
						<?php } ?>
					</select>	
				</td>
			</tr>
		</table>

		<table class="list">
			<tr>
				<td>
					<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Max tokens</span></p>
				</td>
				<td>
					<span class="help">
						Запросы могут использовать до 2048 или 4000 токенов, разделенных между подсказкой и завершением. Точный предел зависит от модели. (Один токен составляет примерно 4 символа для обычного английского текста)
					</span>
				</td>
			</tr>
			<tr>
				<td>
					<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Temperature</span></p>
				</td>
				<td>
					<span class="help">
						Управляет случайностью: понижение приводит к меньшему количеству случайных завершений. Когда температура приближается к нулю, модель становится детерминированной и повторяющейся.
					</span>
				</td>
			</tr>
			<tr>
				<td>
					<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Top P</span></p>
				</td>
				<td>
					<span class="help">
						Контролирует разнообразие посредством выборки ядра: 0,5 означает, что рассматривается половина всех вариантов, взвешенных по правдоподобию.
					</span>
				</td>
			</tr>
			<tr>
				<td>
					<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Frequency penalty</span></p>
				</td>
				<td>
					<span class="help">
						Насколько штрафовать новые токены на основе их существующей частоты в тексте на данный момент. Уменьшает вероятность того, что модель дословно повторит одну и ту же строку.
					</span>
				</td>
			</tr>
			<tr>
				<td>
					<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Presence penalty</span></p>
				</td>
				<td>
					<span class="help">
						Насколько штрафовать новые токены в зависимости от того, появляются ли они в тексте до сих пор. Увеличивает вероятность того, что модель заговорит на новые темы.
					</span>
				</td>
			</tr>
		</table>

		<h2>🤖 Альтернативные названия категорий для поиска</h2>
		<table class="form">
			<tr>
				<td style="width:15%">	
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Включить</span></p>
						<select name="config_openai_enable_category_alternatenames">
							<?php if ($config_openai_enable_category_alternatenames) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>									
					</div>

					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Точка входа</span></p>
						<select name="config_openai_category_alternatenames_endpoint">
							<?php foreach ($openai_endpoints as $openai_endpoint) { ?>
								<?php if ($config_openai_category_alternatenames_endpoint == $openai_endpoint) { ?>
									<option value="<?php echo $openai_endpoint; ?>" selected="selected"><?php echo $openai_endpoint; ?></option>												
								<?php } else { ?>													
									<option value="<?php echo $openai_endpoint; ?>"><?php echo $openai_endpoint; ?></option>
								<? } ?>
							<?php } ?>
						</select>											
					</div>

					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Модель</span></p>
						<select name="config_openai_category_alternatenames_model">
							<?php foreach ($openai_models_list as $openai_model) { ?>
								<?php if ($config_openai_category_alternatenames_model == $openai_model['id']) { ?>
									<option value="<?php echo $openai_model['id']; ?>" selected="selected"><?php echo $openai_model['id']; ?></option>												
								<?php } else { ?>													
									<option value="<?php echo $openai_model['id']; ?>"><?php echo $openai_model['id']; ?></option>
								<? } ?>
							<?php } ?>
						</select>											
					</div>
				</td>

				<td style="width:15%">
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Max tokens</span></p>
						<input type="number" step="10" min="100" max="4000" name="config_openai_category_alternatenames_maxtokens" value="<?php echo $config_openai_category_alternatenames_maxtokens; ?>" size="50" style="width:60px;" />										
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Temperature</span></p>
						<input type="number" step="0.1" min="0" max="1" name="config_openai_category_alternatenames_temperature" value="<?php echo $config_openai_category_alternatenames_temperature; ?>" size="50" style="width:60px;" />	
					</div>
				</td>

				<td style="width:15%">
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Top P</span></p>
						<input type="number" step="0.1" min="0" max="1" name="config_openai_category_alternatenames_top_p" value="<?php echo $config_openai_category_alternatenames_top_p; ?>" size="50" style="width:60px;" />
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Frequency penalty</span></p>
						<input type="number" step="0.1" min="0" max="2" name="config_openai_category_alternatenames_freq_penalty" value="<?php echo $config_openai_category_alternatenames_freq_penalty; ?>" size="50" style="width:60px;" />
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Presence penalty</span></p>
						<input type="number" step="0.1" min="0" max="2" name="config_openai_category_alternatenames_presence_penalty" value="<?php echo $config_openai_category_alternatenames_presence_penalty; ?>" size="50" style="width:60px;" />
					</div>
				</td>

				<td style="width:55%">
					<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Запрос к AI</span></p>	
					<?php foreach ($languages as $language) { ?>											
						<div style="margin-bottom: 10px;">											
							<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
							<textarea style="width:80%" name="config_openai_category_alternatenames_query_<?php echo $language['code']; ?>" rows="4"><?php echo ${'config_openai_category_alternatenames_query_' . $language['code']}; ?></textarea>
						</div>
					<?php } ?>
				</td>						
			</tr>
		</table>

		<h2>🤖 Описания категорий</h2>
		<table class="form">
			<tr>
				<td style="width:15%">	
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Включить</span></p>
						<select name="config_openai_enable_category_descriptions">
							<?php if ($config_openai_enable_category_descriptions) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>									
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Точка входа</span></p>
						<select name="config_openai_category_descriptions_endpoint">
							<?php foreach ($openai_endpoints as $openai_endpoint) { ?>
								<?php if ($config_openai_category_descriptions_endpoint == $openai_endpoint) { ?>
									<option value="<?php echo $openai_endpoint; ?>" selected="selected"><?php echo $openai_endpoint; ?></option>												
								<?php } else { ?>													
									<option value="<?php echo $openai_endpoint; ?>"><?php echo $openai_endpoint; ?></option>
								<? } ?>
							<?php } ?>
						</select>											
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Модель</span></p>
						<select name="config_openai_category_descriptions_model">
							<?php foreach ($openai_models_list as $openai_model) { ?>
								<?php if ($config_openai_category_descriptions_model == $openai_model['id']) { ?>
									<option value="<?php echo $openai_model['id']; ?>" selected="selected"><?php echo $openai_model['id']; ?></option>												
								<?php } else { ?>													
									<option value="<?php echo $openai_model['id']; ?>"><?php echo $openai_model['id']; ?></option>
								<? } ?>
							<?php } ?>
						</select>											
					</div>
				</td>

				<td style="width:15%">
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Max tokens</span></p>
						<input type="number" step="10" min="100" max="4000" name="config_openai_category_descriptions_maxtokens" value="<?php echo $config_openai_category_descriptions_maxtokens; ?>" size="50" style="width:60px;" />										
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Temperature</span></p>
						<input type="number" step="0.1" min="0" max="1" name="config_openai_category_descriptions_temperature" value="<?php echo $config_openai_category_descriptions_temperature; ?>" size="50" style="width:60px;" />	
					</div>
				</td>

				<td style="width:15%">
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Top P</span></p>
						<input type="number" step="0.1" min="0" max="1" name="config_openai_category_descriptions_top_p" value="<?php echo $config_openai_category_descriptions_top_p; ?>" size="50" style="width:60px;" />
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Frequency penalty</span></p>
						<input type="number" step="0.1" min="0" max="2" name="config_openai_category_descriptions_freq_penalty" value="<?php echo $config_openai_category_descriptions_freq_penalty; ?>" size="50" style="width:60px;" />
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Presence penalty</span></p>
						<input type="number" step="0.1" min="0" max="2" name="config_openai_category_descriptions_presence_penalty" value="<?php echo $config_openai_category_descriptions_presence_penalty; ?>" size="50" style="width:60px;" />
					</div>
				</td>

				<td style="width:55%">
					<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Запрос к AI</span></p>	
					<?php foreach ($languages as $language) { ?>											
						<div style="margin-bottom: 10px;">											
							<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
							<textarea style="width:80%" name="config_openai_category_descriptions_query_<?php echo $language['code']; ?>" rows="4"><?php echo ${'config_openai_category_descriptions_query_' . $language['code']}; ?></textarea>
						</div>
					<?php } ?>
				</td>						
			</tr>
		</table>

		<h2>🤖 Адекватные названия</h2>
		<table class="form">
			<tr>
				<td style="width:15%">
					<div>		
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Включить</span></p>
						<select name="config_openai_enable_shorten_names">
							<?php if ($config_openai_enable_shorten_names) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>									
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Точка входа</span></p>
						<select name="config_openai_shortennames_endpoint">
							<?php foreach ($openai_endpoints as $openai_endpoint) { ?>
								<?php if ($config_openai_shortennames_endpoint == $openai_endpoint) { ?>
									<option value="<?php echo $openai_endpoint; ?>" selected="selected"><?php echo $openai_endpoint; ?></option>												
								<?php } else { ?>													
									<option value="<?php echo $openai_endpoint; ?>"><?php echo $openai_endpoint; ?></option>
								<? } ?>
							<?php } ?>
						</select>											
					</div>
					<div>	
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Модель</span></p>
						<select name="config_openai_shortennames_model">
							<?php foreach ($openai_models_list as $openai_model) { ?>
								<?php if ($config_openai_shortennames_model == $openai_model['id']) { ?>
									<option value="<?php echo $openai_model['id']; ?>" selected="selected"><?php echo $openai_model['id']; ?></option>												
								<?php } else { ?>													
									<option value="<?php echo $openai_model['id']; ?>"><?php echo $openai_model['id']; ?></option>
								<? } ?>
							<?php } ?>
						</select>
					</div>
				</td>

				<td style="width:15%">
					<div>		
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Включить до перевода</span></p>
						<select name="config_openai_enable_shorten_names_before_translation">
							<?php if ($config_openai_enable_shorten_names_before_translation) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>									
					</div>
					<div>		
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Включить после перевода</span></p>
						<select name="config_openai_enable_shorten_names_after_translation">
							<?php if ($config_openai_enable_shorten_names_after_translation) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>									
					</div>
				</td>

				<td style="width:15%">
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Длина</span></p>
						<input type="number" step="1" min="10" max="100" name="config_openai_shortennames_length" value="<?php echo $config_openai_shortennames_length; ?>" size="50" style="width:60px;" />
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Max tokens</span></p>
						<input type="number" step="10" min="100" max="4000" name="config_openai_shortennames_maxtokens" value="<?php echo $config_openai_shortennames_maxtokens; ?>" size="50" style="width:60px;" />
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Temperature</span></p>
						<input type="number" step="0.1" min="0" max="1" name="config_openai_shortennames_temperature" value="<?php echo $config_openai_shortennames_temperature; ?>" size="50" style="width:60px;" />
					</div>
				</td>

				<td style="width:15%">
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Top P</span></p>
						<input type="number" step="0.1" min="0" max="1" name="config_openai_shortennames_top_p" value="<?php echo $config_openai_shortennames_top_p; ?>" size="50" style="width:60px;" />
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Frequency penalty</span></p>
						<input type="number" step="0.1" min="0" max="2" name="config_openai_shortennames_freq_penalty" value="<?php echo $config_openai_shortennames_freq_penalty; ?>" size="50" style="width:60px;" />
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Presence penalty</span></p>
						<input type="number" step="0.1" min="0" max="2" name="config_openai_shortennames_presence_penalty" value="<?php echo $config_openai_shortennames_presence_penalty; ?>" size="50" style="width:60px;" />
					</div>
				</td>


				<td style="width:35%">
					<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Запрос к AI</span></p>	
					<?php foreach ($languages as $language) { ?>											
						<div style="margin-bottom: 10px;">											
							<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <input type="text" name="config_openai_shortennames_query_<?php echo $language['code']; ?>" value="<?php echo ${'config_openai_shortennames_query_' . $language['code']}; ?>" style="width:80%" />
						</div>
					<?php } ?>
				</td>
			</tr>
		</table>

		<h2>🤖 Экспортные названия</h2>
		<table class="form">
			<tr>
				<td style="width:15%">	
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Включить</span></p>
						<select name="config_openai_enable_export_names">
							<?php if ($config_openai_enable_export_names) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>									
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Точка входа</span></p>
						<select name="config_openai_exportnames_endpoint">
							<?php foreach ($openai_endpoints as $openai_endpoint) { ?>
								<?php if ($config_openai_exportnames_endpoint == $openai_endpoint) { ?>
									<option value="<?php echo $openai_endpoint; ?>" selected="selected"><?php echo $openai_endpoint; ?></option>												
								<?php } else { ?>													
									<option value="<?php echo $openai_endpoint; ?>"><?php echo $openai_endpoint; ?></option>
								<? } ?>
							<?php } ?>
						</select>											
					</div>
					<div>	
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Модель</span></p>
						<select name="config_openai_exportnames_model">
							<?php foreach ($openai_models_list as $openai_model) { ?>
								<?php if ($config_openai_exportnames_model == $openai_model['id']) { ?>
									<option value="<?php echo $openai_model['id']; ?>" selected="selected"><?php echo $openai_model['id']; ?></option>												
								<?php } else { ?>													
									<option value="<?php echo $openai_model['id']; ?>"><?php echo $openai_model['id']; ?></option>
								<? } ?>
							<?php } ?>
						</select>	
					</div>									
				</td>

				<td style="width:15%">
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Длина</span></p>
						<input type="number" step="1" min="10" max="100" name="config_openai_exportnames_length" value="<?php echo $config_openai_exportnames_length; ?>" size="50" style="width:60px;" />
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Max tokens</span></p>
						<input type="number" step="10" min="100" max="4000" name="config_openai_exportnames_maxtokens" value="<?php echo $config_openai_exportnames_maxtokens; ?>" size="50" style="width:60px;" />
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Temperature</span></p>
						<input type="number" step="0.1" min="0" max="1" name="config_openai_exportnames_temperature" value="<?php echo $config_openai_exportnames_temperature; ?>" size="50" style="width:60px;" />
					</div>
				</td>

				<td style="width:15%">
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Top P</span></p>
						<input type="number" step="0.1" min="0" max="1" name="config_openai_exportnames_top_p" value="<?php echo $config_openai_exportnames_top_p; ?>" size="50" style="width:60px;" />
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Frequency penalty</span></p>
						<input type="number" step="0.1" min="0" max="2" name="config_openai_exportnames_freq_penalty" value="<?php echo $config_openai_exportnames_freq_penalty; ?>" size="50" style="width:60px;" />
					</div>
					<div>
						<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Presence penalty</span></p>
						<input type="number" step="0.1" min="0" max="2" name="config_openai_exportnames_presence_penalty" value="<?php echo $config_openai_exportnames_presence_penalty; ?>" size="50" style="width:60px;" />
					</div>
				</td>

				<td style="width:55%">
					<p>🤖 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#353740; color:#FFF">Запрос к AI</span></p>	
					<?php foreach ($languages as $language) { ?>											
						<div style="margin-bottom: 10px;">											
							<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <input type="text" name="config_openai_exportnames_query_<?php echo $language['code']; ?>" value="<?php echo ${'config_openai_exportnames_query_' . $language['code']}; ?>" style="width:80%" />
						</div>
					<?php } ?>
				</td>
			</tr>
		</table>
	</div>