<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span> <?php echo $entry_title; ?></td>
						<td><input type="text" name="title" value="<?php echo $title; ?>" />
							<?php if ($error_title) { ?>
								<span class="error"><?php echo $error_title; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> Склонение</td>
						<td><input type="text" name="morph" style="width:250px;" value="<?php echo $morph; ?>" />
							<?php if ($error_title) { ?>
								<span class="error"><?php echo $error_title; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span> <?php echo $entry_code; ?></td>
						<td><input type="text" name="code" value="<?php echo $code; ?>" />
							<?php if ($error_code) { ?>
								<span class="error"><?php echo $error_code; ?></span>
							<?php } ?></td>
					</tr>
					<tr>
						<td>Флаг страны</td>
						<td><input type="text" name="flag" value="<?php echo $flag; ?>" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_symbol_left; ?></td>
						<td><input type="text" name="symbol_left" value="<?php echo $symbol_left; ?>" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_symbol_right; ?></td>
						<td><input type="text" name="symbol_right" value="<?php echo $symbol_right; ?>" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_decimal_place; ?></td>
						<td><input type="text" name="decimal_place" value="<?php echo $decimal_place; ?>" /></td>
					</tr>
					<tr>
						<td>Внутренний курс</td>
						<td><input type="text" name="value" value="<?php echo $value; ?>" /></td>
					</tr>
					
					<tr>
						<td>Реальный курс</td>
						<td><input type="text" name="value_real" value="<?php echo $value_real; ?>" /></td>
					</tr>
					
					<tr>
						<td>Минимальное значение</td>
						<td><input type="text" name="value_minimal" value="<?php echo $value_minimal; ?>" />
						<span class="help">минимальная планка курса, при увеличении реального курса - будет увеличиваться, при уменьшении - уменьшаться до данного минимального значения</span>	
						</td>
					</tr>
					
					<tr>
						<td>Курс к ГРН, продажа</td>
						<td><input type="text" name="value_uah_unreal" value="<?php echo $value_uah_unreal; ?>" /> грн.</td>
					</tr>
					
					<tr>
						<td>Курс к ЕВРО, для пересчета при продаже</td>
						<td><input type="text" name="value_eur_official" value="<?php echo $value_eur_official; ?>" /> евро</td>
					</tr>
					
					<tr>
						<td>Крипто-пара</td>
						<td><input type="text" name="cryptopair" value="<?php echo $cryptopair; ?>" /></td>
					</tr>

					<tr>
						<td>Цена крипто-пары</td>
						<td><input type="text" name="cryptopair_value" value="<?php echo $cryptopair_value; ?>" /></td>
					</tr>

					<tr>
						<td>Автоматом добавлять % к курсу на витрину</td>
						<td>
							<input type="text" name="auto_percent" value="<?php echo $auto_percent; ?>" />	
							<span class="help">Эта настройка используется при автоматическом получении и задании курсов в зависимости от реального. Добавляет процент к реальному курсу, полученному от банка, но не ниже минимального значения.</span>			
						</td>
					</tr>
					
					<tr>
						<td>Х процентов к цене</td>
						<td><input type="text" name="plus_percent" value="<?php echo $plus_percent; ?>" />
							<span class="help">Этот процент будет автоматически добавлен к цене после пересчета по курсу. Товар стоит 600, курс 150. Цена будет 90000, дополнительно 5%, результат 94500. Задавать значение можно в виде "5", "+5", "-5". Если не задать знак - по умолчанию используется плюс к цене.</span>
						</td>
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
						</select></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php echo $footer; ?>