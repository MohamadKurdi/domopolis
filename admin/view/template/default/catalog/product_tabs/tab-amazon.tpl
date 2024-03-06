<div id="tab-amazon">
	<table class="form">
		<tr>																		
			<td class="left" width="14%">
				<b>ASIN</b>
			</td>
			<td class="left" width="14%">
				<b>Ссылка</b>
			</td>
			<td class="left" width="14%">
				<b>EAN (GTIN)</b>
			</td>
			<td class="left" width="14%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Разрешить загрузку данных</span>
			</td>
			<td class="left" width="14%">
				<b>Данные обновлены</b>
			</td>
			<td class="left" width="14%">	
				<b>Офферы</b>															
			</td>

			<td class="left" width="14%">
				<input type="hidden" name="added_from_amazon" value="<?php echo $added_from_amazon; ?>" />
				<?php if ($added_from_amazon) { ?>																				
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(0, 173, 7); color:#FFF"><i class="fa fa-check"></i>Добавлен с Amazon</span>
				<?php } else {  ?>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-exclamation-triangle"></i> Добавлен вручную</span>
				<?php } ?>
			</td>

			<td class="left" width="14%">
				<?php if ($amzn_not_found) { ?>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-exclamation-triangle"></i> Товар не найден на Amazon</span>
				<?php } else {  ?>
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(0, 173, 7); color:#FFF"><i class="fa fa-check"></i>Товар найден на Amazon</span>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td class="left" width="14%">
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">
					<?php if (!empty($asin)) { ?>
						<?php echo $asin; ?>
					<?php } else { ?>
						Не задан
					<?php } ?>
				</span>


				<?php if (!empty($old_asin)) { ?>
					<br /><br />
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><?php echo $old_asin; ?>								
				</span>
			<?php } ?>
		</td>
		<td class="left" width="14%">
			<?php if ($amazon_product_link) { ?>
				<a href="<? echo $amazon_product_link; ?>" target="_blank"><? echo $amazon_product_link; ?></a>
			<?php } ?>
		</td>
		<td class="left" width="14%">
			<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">
				<?php if (!empty($ean)) { ?>
					<?php echo $ean; ?>
				<?php } else { ?>
					Не задан
				<?php } ?>
			</span>
		</td>
		<td class="left" width="14%">
			<select name="fill_from_amazon">
				<?php if ($fill_from_amazon) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
			</select>
		</td>
		<td class="left" width="14%">
			<input type="text" style="width:100px;" class="date" name="amzn_last_search" value="<?php echo $amzn_last_search; ?>" />
		</td>

		<td class="left" width="14%">
			<a class="button" onclick="$('#amazon_offers').html('<i class=\'fa fa-spinner fa-spin\'></i>');$('#amazon_offers').load('index.php?route=kp/amazon/getProductOffers&token=<?php echo $token; ?>&explicit=1&product_id=<? echo $product_id; ?>');">Загрузить офферы</a>
		</td>

		<td class="left" width="14%">

			<?php if (!empty($description_filled_from_amazon)) { ?>
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(0, 173, 7); color:#FFF"><i class="fa fa-check"></i>Описания загружены</span>
			<?php } else { ?>
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-exclamation-triangle"></i> Описания еще не загружены</span>
			<?php } ?>
			<br /><br />

			<?php if (!empty($filled_from_amazon)) { ?>
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(0, 173, 7); color:#FFF"><i class="fa fa-check"></i>Данные загружены</span>

				<?php if (!empty($amazon_product_json)) { ?>
					<a href="<?php echo $amazon_product_json; ?>" target="_blank"><i class="fa fa-download"></i></a>
				<?php } else { ?>																					
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-check"></i>Данные проебаны</span>
				<?php } ?>	
			<?php } else { ?>
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-exclamation-triangle"></i> Данные еще не загружены</span>
			<?php } ?>

		</td>

		<td class="left" width="14%">

			<span class="help"><i class="fa fa-info-circle"></i> если "найден", то это означает, что товар с данным ASIN существует на Amazon и мы можем получить по нему данные, но при этом он не обязательно доступен к покупке</span>
		</td>
	</tr>
</table>

<table class="form">
	<tr>
		<td class="left" width="15%">
			<b>Предложения обновлены</b>
		</td>
		<td class="left" width="15%">
			<b style="color:#cf4a61">Не парсить Amazon</b>
		</td>
		<td class="left" width="15%">
			<b style="color:#cf4a61">Цена BEST OFFER</b>
		</td>
		<td class="left" width="15%">
			<b style="color:#cf4a61">Цена LOWEST OFFER</b>
		</td>
		<td class="left" width="15%">
			<?php if ($amzn_no_offers) { ?>
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-exclamation-triangle"></i> Нет предложений на Amazon</span>
			<?php } else {  ?>
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(0, 173, 7); color:#FFF"><i class="fa fa-check"></i>Есть предложения на Amazon</span>
			<?php } ?>
		</td>
		<td class="left" width="15%">
			<?php if ($amzn_no_offers) { ?>
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:rgb(207, 74, 97); color:#FFF"><i class="fa fa-exclamation-triangle"></i> Счетчик: <?php echo $amzn_no_offers_counter; ?></span>
			<?php } ?>
		</td>
	</tr>

	<tr>
		<td class="left" width="15%">
			<input type="text" style="width:150px;" class="datetime" name="amzn_last_offers" value="<?php echo $amzn_last_offers; ?>" />
		</td>
		<td class="left" width="15%">
			<select name="amzn_ignore">
				<?php if ($amzn_ignore) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
			</select>
		</td>
		<td class="left" width="15%">
			<b><?php echo $amazon_best_price; ?></b>
		</td>
		<td class="left" width="15%">
			<b><?php echo $amazon_lowest_price; ?></b>
		</td>
		<td class="left" width="15%">
			<span class="help"><i class="fa fa-info-circle"></i> маркер НЕ ПАРСИТЬ можно устанавливать в любом случае, даже если предложения есть, в случае если, например, товар поставляется только с локальных складов и не планируется закупка с Amazon</span>
		</td>
		<td class="left" width="15%">
			<span class="help"><i class="fa fa-info-circle"></i> если нет предложений, то это значит, что на данный момент товар находится в статусе Currently unavailable. We don't know when or if this item will be back in stock. Нужно принять решение, сканировать ли далее этот товар или нет. Если нет - нужно установить маркер "Не парсить Амазон"</span>
		</td>
	</tr>
</table>

<h2 style="margin-top:10px;"><i class="fa fa-amazon"></i> Текущие офферы</h2>
<div style="margin-top:10px;" id="amazon_offers">
	<?php require_once(dirname(__FILE__) . '/../../sale/amazon_offers_list.tpl'); ?>
</div>

<h2 style="margin-top:10px;"><i class="fa fa-clock-o"></i> История ценообразования</h2>
<div style="margin-top:10px;" id="amazon_offers_history">
	<?php require_once(dirname(__FILE__) . '/../../sale/amazon_offers_history.tpl'); ?>
</div>

</div>