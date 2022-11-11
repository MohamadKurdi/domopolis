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
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left"><?php if ($sort == 'title') { ?>
								<a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
							<?php } ?></td>
							
							<td class="left"><?php if ($sort == 'code') { ?>
								<a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>">ISO3</a>
								<?php } else { ?>
								<a href="<?php echo $sort_code; ?>">ISO3</a>
							<?php } ?></td>	
							<td>Крипто-пара</td>
							<td></td>
							<td class="left">Вн. курс к UAH</td>
							<td class="left">Вн. курс к UAH продажа</td>
							<td class="left">Вн. курс к RUB</td>
							<td class="right"><?php if ($sort == 'value') { ?>
								<a href="<?php echo $sort_value; ?>" class="<?php echo strtolower($order); ?>">Внутр к €</a>
								<?php } else { ?>
								<a href="<?php echo $sort_value; ?>">Внутр к €</a>
							<?php } ?></td>
							<td></td>
							<td class="right">Реальный €</td>
							<td class="right">ЕЦБ курс к €</td>
							<td class="right">Курс крипто</td>
							<td class="right">Наценка, %</td>
							<td class="right">Авто Плюс, %</td>
							<td class="right">Минимальный порог</td>
							<td class="left"><?php if ($sort == 'date_modified') { ?>
								<a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
							<?php } ?></td>
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($currencies) { ?>
							<?php foreach ($currencies as $currency) { ?>
								<? $our_is_more = ($currency['value'] > $currency['value_real']); ?>
								<? $our_is_equal = ($currency['value'] == $currency['value_real']); ?>
								<tr>
									<td style="text-align: center;"><?php if ($currency['selected']) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $currency['currency_id']; ?>" checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $currency['currency_id']; ?>" />
									<?php } ?></td>
									<td class="left"><?php echo $currency['title']; ?><br />
									<span class="help"><? echo $currency['morph']; ?></span></td>
									<td class="left"><?php echo $currency['code']; ?></td>
									<td class="left"><?php echo $currency['cryptopair']; ?></td>
									<td class="left"><? if (isset($currency['flag'])) { ?><img src="<? echo HTTPS_CATALOG ?>/image/flags/<?php echo $currency['flag']; ?>" /><? } ?></td>			 
									<td class="left"><?php echo $currency['mc']; ?> = <?php echo $currency['rc']; ?></td>
									<td class="left"><?php echo $currency['mc']; ?> = <?php echo $currency['value_uah_unreal']; ?> грн.</td>
									<td class="left"><?php echo $currency['mc']; ?> = <?php echo $currency['r_rc']; ?></td> 	
									<td class="right"><?php echo $currency['value']; ?></td>
									<td class="center">
										<? if ($our_is_equal) { ?>
											<span class="status_color_padding" style="background:#ff7f00; color:white;"> = </span>	   
											<? } elseif ($our_is_more) { ?>
											<span class="status_color_padding" style="background:#4ea24e; color:white;"> > </span>
											<? } else { ?>
											<span class="status_color_padding" style="background:#cf4a61; color:white;"> < </span>
											<? } ?>
											</td>
											<td class="right"><?php echo $currency['value_real']; ?></td> 
											<td class="right"><?php echo $currency['value_eur_official']; ?></td> 
											<td class="right"><?php echo (float)$currency['cryptopair_value']?$currency['cryptopair_value']:''; ?></td>	
											<td class="right">
												<? if ($currency['plus_percent']) { ?>
													<span class="status_color_padding" style="background:#ff7f00; color:white;"><?php echo $currency['plus_percent']; ?></span>
													<? } else { ?>
													<span class="status_color_padding" style="background:#4ea24e; color:white;"><?php echo $currency['plus_percent']; ?></span>
												<? } ?>
											</td> 
											<td class="center">
												<? if ($currency['auto_percent'] > 0) { ?>
													<span class="status_color_padding" style="background:#4ea24e; color:white;"><?php echo $currency['auto_percent']; ?></span>
													<? } else { ?>
													<span class="status_color_padding" style="background:#ff7f00; color:white;"><?php echo $currency['auto_percent']; ?></span>
												<? } ?>
											</td>
											<td class="right">
												<? if ($currency['value_minimal'] > 0) { ?>
													<span class="status_color_padding" style="background:#ff7f00; color:white;"><?php echo $currency['value_minimal']; ?></span>
												<? } ?>
											</td>
											<td class="center"><?php echo $currency['date_modified']; ?></td>
											<td class="right"><?php foreach ($currency['action'] as $action) { ?>
												<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
											<?php } ?></td>
										</tr>
									<?php } ?>
									<?php } else { ?>
									<tr>
										<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</form>
					<span class="help">Источники обновления: FIXER.IO, UAH: ПриватБанк карточный курс, продажа, RUB: Русский Стандарт карточный курс, продажа (http://www.rsb.ru/courses/)<br /><br />
						Внутренний курс к Евро влияет на ценообразование на витрине магазина.<br /><br />
						Реальный курс к Евро - используется исключительно в формировании подсказки закупщикам при поиске возможной цены закупки.
					</span>
					<div class="pagination"><?php echo $pagination; ?></div>
				</div>
			</div>
		</div>
	<?php echo $footer; ?> 				