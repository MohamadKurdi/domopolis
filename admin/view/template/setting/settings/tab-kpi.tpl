<div id="tab-kpi">
	<h2 style="color:#000">KPI: Процент неконверсионных заказов</h2>
	<table class="form">
		<tr>
			<td style="width:33%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#51A62D; color:#FFF">Зеленая зона, до</span></p>
				<input type="number" step="0.1" name="config_kpi_complete_cancel_percent_params_0" value="<?php echo $config_kpi_complete_cancel_percent_params_0; ?>" size="50" style="width:200px;" />
			</td>

			<td style="width:33%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Желтая зона, до</span></p>
				<input type="number" step="0.1" name="config_kpi_complete_cancel_percent_params_1" value="<?php echo $config_kpi_complete_cancel_percent_params_1; ?>" size="50" style="width:200px;" />
			</td>

			<td style="width:33%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Красная зона, до</span></p>
				<input type="number" step="0.1" name="config_kpi_complete_cancel_percent_params_2" value="<?php echo $config_kpi_complete_cancel_percent_params_2; ?>" size="50" style="width:200px;" />
			</td>
		</tr>
	</table>

	<h2 style="color:#000">KPI: Среднее время подтверждения заказа, дней</h2>
	<table class="form">
		<tr>
			<td style="width:33%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#51A62D; color:#FFF">Зеленая зона, до</span></p>
				<input type="number" step="0.1" name="config_kpi_average_confirm_time_params_0" value="<?php echo $config_kpi_average_confirm_time_params_0; ?>" size="50" style="width:200px;" />
			</td>

			<td style="width:33%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Желтая зона, до</span></p>
				<input type="number" step="0.1" name="config_kpi_average_confirm_time_params_1" value="<?php echo $config_kpi_average_confirm_time_params_1; ?>" size="50" style="width:200px;" />
			</td>

			<td style="width:33%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Красная зона, до</span></p>
				<input type="number" step="0.1" name="config_kpi_average_confirm_time_params_2" value="<?php echo $config_kpi_average_confirm_time_params_2; ?>" size="50" style="width:200px;" />
			</td>
		</tr>
	</table>

	<h2 style="color:#000">KPI: Среднее время выполнения заказа, дней</h2>
	<table class="form">
		<tr>
			<td style="width:33%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#51A62D; color:#FFF">Зеленая зона, до</span></p>
				<input type="number" step="0.1" name="config_kpi_average_process_time_params_0" value="<?php echo $config_kpi_average_process_time_params_0; ?>" size="50" style="width:200px;" />
			</td>

			<td style="width:33%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Желтая зона, до</span></p>
				<input type="number" step="0.1" name="config_kpi_average_process_time_params_1" value="<?php echo $config_kpi_average_process_time_params_1; ?>" size="50" style="width:200px;" />
			</td>

			<td style="width:33%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Красная зона, до</span></p>
				<input type="number" step="0.1" name="config_kpi_average_process_time_params_2" value="<?php echo $config_kpi_average_process_time_params_2; ?>" size="50" style="width:200px;" />
			</td>
		</tr>
	</table>

	<h2 style="color:#000">Параметры премии для менеджеров</h2>
	<table class="form">
		<tr>
			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#51A62D; color:#FFF">Попадание в зеленую зону</span></p>
				<input type="number" step="0.01" name="config_kpi_percentage_params_0" value="<?php echo $config_kpi_percentage_params_0; ?>" size="50" style="width:200px;" />
			</td>

			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Попадание в желтую зону</span></p>
				<input type="number" step="0.01" name="config_kpi_percentage_params_1" value="<?php echo $config_kpi_percentage_params_1; ?>" size="50" style="width:200px;" />
			</td>

			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Попадание в красную зону</span></p>
				<input type="number" step="0.01" name="config_kpi_percentage_params_2" value="<?php echo $config_kpi_percentage_params_2; ?>" size="50" style="width:200px;" />
			</td>

			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Фиксированная ставка</span></p>
				<input type="number" step="100" name="config_kpi_fixed_salary" value="<?php echo $config_kpi_fixed_salary; ?>" size="50" style="width:200px;" />
			</td>
		</tr>
	</table>

	<h2 style="color:#000">Параметры премии для руководителя отдела продаж</h2>
	<table class="form">
		<tr>
			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#51A62D; color:#FFF">Попадание в зеленую зону</span></p>
				<input type="number" step="0.01" name="config_kpi_head_percentage_params_0" value="<?php echo $config_kpi_head_percentage_params_0; ?>" size="50" style="width:200px;" />
			</td>

			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Попадание в желтую зону</span></p>
				<input type="number" step="0.01" name="config_kpi_head_percentage_params_1" value="<?php echo $config_kpi_head_percentage_params_1; ?>" size="50" style="width:200px;" />
			</td>

			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Попадание в красную зону</span></p>
				<input type="number" step="0.01" name="config_kpi_head_percentage_params_2" value="<?php echo $config_kpi_head_percentage_params_2; ?>" size="50" style="width:200px;" />
			</td>

			<td style="width:25%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#CF4A61; color:#FFF">Фиксированная ставка</span></p>
				<input type="number" step="100" name="config_kpi_head_fixed_salary" value="<?php echo $config_kpi_head_fixed_salary; ?>" size="50" style="width:200px;" />
			</td>
		</tr>
	</table>

	<h2 style="color:#000">Общие параметры</h2>
	<table class="form">
		<tr>
			<td style="width:50%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#51A62D; color:#FFF">Дней по умолчанию для подсчета</span></p>
				<input type="number" step="10" name="config_kpi_default_filter_count_days" value="<?php echo $config_kpi_default_filter_count_days; ?>" size="50" style="width:200px;" />
			</td>

			<td style="width:50%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF7815; color:#FFF">Порог количества дней, после которых заказ помечается как проблемный</span></p>
				<input type="number" step="10" name="config_kpi_default_filter_count_days_problem" value="<?php echo $config_kpi_default_filter_count_days_problem; ?>" size="50" style="width:200px;" />
			</td>
		</tr>
	</table>
</div>