<div id="tab-general">
	<h2>Основное</h2>
	<table class="form">
		<tr>
			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Название</span></p>
				<input type="text" name="config_name" value="<?php echo $config_name; ?>" size="40" />							
			</td>
			
			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">HTTPS (для соместимости с хрефланг)</span></p>
				<input type="text" name="config_ssl" value="<?php echo $config_ssl; ?>" size="40" />
			</td>

			<td style="width:25%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $entry_owner; ?></span></p>
					<input type="text" name="config_owner" value="<?php echo $config_owner; ?>" size="40" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Время работы</span></p>
					<input type="text" name="config_worktime" value="<?php echo $config_worktime; ?>" size="60" />
				</div>
			</td>

			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $entry_address; ?></span></p>
				<textarea name="config_address" cols="40" rows="5"><?php echo $config_address; ?></textarea>
			</td>
		</tr>
	</table>

	<h2>Мейлы</h2>
	<table class="form">
		<tr>
			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $entry_email; ?></span></p>
				<input type="text" name="config_email" value="<?php echo $config_email; ?>" size="40" />							
			</td>

			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">E-mail для отображения в контактах</span></p>
				<input type="text" name="config_display_email" value="<?php echo $config_display_email; ?>" size="40" />							
			</td>

			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">E-mail оптовый</span></p>
				<input type="text" name="config_opt_email" value="<?php echo $config_opt_email; ?>" size="40" /> 							
			</td>
			
		</tr>
	</table>

	<h2>Телефоны</h2>
	<table class="form">
		<tr>							
			<td style="width:25%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Текст над</span></p>
					<input type="text" name="config_t_tt" value="<?php echo $config_t_tt; ?>" size="40" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Телефон - 1</span></p>
					<input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" size="40" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Текст под</span></p>
					<input type="text" name="config_t_bt" value="<?php echo $config_t_bt; ?>" size="40" />
				</div>
			</td>

			<td style="width:25%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Текст над</span></p>
					<input type="text" name="config_t2_tt" value="<?php echo $config_t2_tt; ?>" size="40" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Телефон - 2</span></p>
					<input type="text" name="config_telephone2" value="<?php echo $config_telephone2; ?>" size="40" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Текст под</span></p>
					<input type="text" name="config_t2_bt" value="<?php echo $config_t2_bt; ?>" size="40" />
				</div>
			</td>

			<td style="width:25%">
				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Телефон - 3</span></p>
					<input type="text" name="config_telephone3" value="<?php echo $config_telephone3; ?>" size="40" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Телефон - 4</span></p>
					<input type="text" name="config_telephone4" value="<?php echo $config_telephone4; ?>" size="40" />
				</div>
			</td>

			<td style="width:25%">									

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Оптовый телефон - 1</span></p>
					<input type="text" name="config_opt_telephone" value="<?php echo $config_opt_telephone; ?>" size="40" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Оптовый телефон - 2</span></p>
					<input type="text" name="config_opt_telephone2" value="<?php echo $config_opt_telephone2; ?>" size="40" />
				</div>

				<div>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Факс</span></p>
					<input type="text" name="config_fax" value="<?php echo $config_fax; ?>" size="40" />
				</div>
			</td>
		</tr>		
	</table>
</div>