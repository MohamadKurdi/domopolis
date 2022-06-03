<style>
	table.font6pt tr td {font-size: 6pt;}
	table.font9pt tr td {font-size: 9pt;}
	p {margin:0px;}
</style>
<div style="width:210mm; padding:10px; margin:0 auto;">
	<table class="font6pt" cellpadding="2" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 6pt;">
		<tbody>
			<tr>
				<td align="right" colspan="4">&nbsp; Унифицированная форма № ТОРГ- 12<br />
					Утверждена постановлением Госкомстата России<br />
				от 25.12.98 № 132</td>
			</tr>
			<tr>
				<td width="13%">&nbsp;</td>
				<td width="67%">&nbsp;</td>
				<td width="10%">&nbsp;</td>
				<td align="right" style="border: 1px solid #000000;" width="10%">
					<p>Код</p>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right">Форма по ОКУД</td>
				<td align="right" style="border: 1px solid #000000;">
					<p>0330212</p>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">ИП САРМИН ВИТАЛИЙ ВИТАЛЬЕВИЧ, улица 3-е Почтовое Отделение 58-54, 140003 г. Люберцы, Московская область, ИНН: 773008763324</td>
				<td align="right">по ОКПО</td>
				<td align="right" style="border: 1px solid #000000;">40148343</td>
			</tr>
			<tr>
				<td colspan="2">
					<div style="font-size: 10px; text-align: center;">организация &ndash; грузоотправитель, адрес, номер телефона, факса, банковские реквизиты</div>
				</td>
				<td align="right">&nbsp;</td>
				<td align="right">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right">Вид деятельности по ОКДП</td>
				<td align="right" style="border-left: 1px solid #000000; border-top: 1px solid #000000; border-right: 1px solid #000000;">&nbsp;</td>
			</tr>
			<tr>
				<td>Грузополучатель&nbsp;</td>
				<td style="border-bottom-width: 1px; border-bottom-style: solid;">
					<p><? echo str_replace(PHP_EOL, '<br />', $customer['cashless_info']); ?></p>
				</td>
				<td align="right">по ОКПО</td>
				<td align="right" style="border: 1px solid #000000;">40139191</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<div style="font-size: 10px; text-align: center;">наименование организации, адрес, номер телефона, банковские реквизиты</div>
				</td>
				<td align="right">&nbsp;</td>
				<td align="right" style="border: 1px solid #000000;">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right">&nbsp;</td>
				<td align="right" style="border: 1px solid #000000;">&nbsp;</td>
			</tr>
			<tr>
				<td>Поставщик</td>
				<td style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">ИП САРМИН ВИТАЛИЙ ВИТАЛЬЕВИЧ, улица 3-е Почтовое Отделение 58-54, 140003 г. Люберцы, Московская область, ИНН: 773008763324</td>
				<td align="right">по ОКПО</td>
				<td align="right" style="border: 1px solid #000000;">40148343</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<div style="font-size: 10px; text-align: center;">наименование организации, адрес, номер телефона, банковские реквизиты&nbsp;</div>
				</td>
				<td align="right">&nbsp;</td>
				<td align="right" style="border: 1px solid #000000;">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right">&nbsp;</td>
				<td align="right" style="border: 1px solid #000000;">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;Плательщик</td>
				<td style="border-bottom-width: 1px; border-bottom-style: solid;"><? echo str_replace(PHP_EOL, '<br />', $customer['cashless_info']); ?></td>
				<td align="right">по ОКПО</td>
				<td align="right" style="border: 1px solid #000000;">40139191</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<div style="font-size: 10px; text-align: center;">&nbsp;наименование организации, адрес, номер телефона, банковские реквизиты</div>
				</td>
				<td align="right">&nbsp;</td>
				<td align="right" style="border: 1px solid #000000;">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right">&nbsp;</td>
				<td align="right" style="border: 1px solid #000000;">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;Основание</td>
				<td style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">Счет на оплату № <? echo $order['invoice_no'] ?>-<? echo $order['order_id'] ?> от <? echo ($order['invoice_date'] == '0000-00-00')?date('d.m.Y'):date('d.m.Y', strtotime($order['invoice_date'])); ?></td>
				<td align="right" style="border: 1px solid #000000;">номер&nbsp;</td>
				<td align="right" style="border: 1px solid #000000;"><? echo $order['order_id']; ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;наименование документа (договор, контракт, заказ-наряд)</td>
				<td align="right" style="border: 1px solid #000000;">дата&nbsp;</td>
				<td style="border-right-width: 1px; border-right-style: solid; border-right-color: #000000; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<? echo ($order['invoice_date'] == '0000-00-00')?date('d.m.Y'):date('d.m.Y', strtotime($order['invoice_date'])); ?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td align="right">&nbsp;Транспортная накладная&nbsp;</td>
				<td align="right" style="border: 1px solid #000000;">номер&nbsp;</td>
				<td style="border-right-width: 1px; border-right-style: solid; border-right-color: #000000; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right" style="border: 1px solid #000000;">дата&nbsp;</td>
				<td style="border-right-width: 1px; border-right-style: solid; border-right-color: #000000;">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right">Вид операции</td>
				<td align="right" style="border: 1px solid #000000;">&nbsp;</td>
			</tr>
		</tbody>
	</table>
	
	<table class="font9pt" align="center" cellpadding="0" cellspacing="0" style="width: 70%; border-collapse: collapse; font-size: 9pt;">
		<tbody>
			<tr>
				<td colspan="29" rowspan="2">ТОВАРНАЯ НАКЛАДНАЯ&nbsp; &nbsp;&nbsp;&nbsp;</td>
				<td colspan="5">Номер документа&nbsp;</td>
				<td colspan="6">Дата составления&nbsp;</td>
			</tr>
			<tr>
				<td colspan="5"><span><? echo $order['invoice_no'] ?>-<? echo $order['order_id'] ?></span></td>
				<td colspan="6"><? echo date('d.m.Y'); ?></td>
			</tr>
		</tbody>
	</table>
	
	<table class="font6pt" border="0" cellspacing="0" class="data_table slim" style="width: 100%; border-collapse: collapse; font-size: 6pt;">
		<thead>
			<tr>
				<td align="center" rowspan="2" style="border: 1px solid #000000; width: 3%;"><span>Номер по порядку</span></td>
				<td align="center" colspan="2" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 29%;"><span>Товар</span></td>
				<td align="center" colspan="2" style="border-bottom-width: 1px; border-bottom-style: solid; border-top-width: 1px; border-top-style: solid; border-right-width: 1px; border-right-style: solid; width: 9%;"><span>Единица измерения</span></td>
				<td align="center" rowspan="2" style="border-bottom-width: 1px; border-bottom-style: solid; border-top-width: 1px; border-top-style: solid; border-right-width: 1px; border-right-style: solid; width: 4%;">&nbsp;<span>Вид упаковки</span>&nbsp;</td>
				<td align="center" colspan="2" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 9%;"><span>Количество (масса)</span></td>
				<td align="center" rowspan="2" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 4%;">&nbsp;<span>Масса брутто</span>&nbsp;</td>
				<td align="center" rowspan="2" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 6%;"><span>Коли-чество (масса нетто)</span>&nbsp;&nbsp;</td>
				<td align="center" rowspan="2" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 7%;"><span>Цена руб. коп</span>&nbsp;</td>
				<td align="center" rowspan="2" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 8%;"><span>Сумма без учета НДС, руб. коп</span>&nbsp;&nbsp;&nbsp;</td>
				<td align="center" colspan="2" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 12%;">НДС&nbsp;&nbsp;</td>
				<td align="center" rowspan="2" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 9%;"><span>Сумма с учетом НДС, руб. коп</span>&nbsp;</td>
			</tr>
			<tr>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 24%;"><span>наименование, характеристика, сорт, артикул товара</span></td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 5%;"><span>код</span></td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-top-width: 1px; border-top-style: solid; border-right-width: 1px; border-right-style: solid; width: 5%;"><span>наименование</span></td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-top-width: 1px; border-top-style: solid; border-right-width: 1px; border-right-style: solid; width: 4%;"><span>Код по ОКЕИ</span></td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 4%;"><span>в одном месте</span></td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 5%;"><span>мест, штук</span>&nbsp;&nbsp;</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 5%;"><span>ставка,%</span>&nbsp;</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 7%;"><span>сумма, руб. коп</span>&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td align="center" style="border: 1px solid #000000; width: 3%;">1</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 24%;">2</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 5%;">3</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-top-width: 1px; border-top-style: solid; border-right-width: 1px; border-right-style: solid; width: 5%;">4</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-top-width: 1px; border-top-style: solid; border-right-width: 1px; border-right-style: solid; width: 4%;">5</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-top-width: 1px; border-top-style: solid; border-right-width: 1px; border-right-style: solid; width: 4%;">6</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 4%;">7</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 5%;">8</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 4%;">9</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 6%;">10</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 7%;">11</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 8%;">12</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 5%;">13</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 7%;">14</td>
				<td align="center" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; border-top-width: 1px; border-top-style: solid; width: 9%;">15</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align="right" style="width: 3%; border-left: 1px solid #000000; border-right: 1px solid #000000; border-bottom: 1px solid #000000;">{CRMProduct:RowNum}</td>
				<td style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; width: 24%;">{CRMProduct:Name}</td>
				<td style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; width: 5%;">&nbsp;</td>
				<td style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; width: 5%;">шт.</td>
				<td style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; width: 4%;">&nbsp;<span>796</span></td>
				<td style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; width: 4%;">&nbsp;</td>
				<td align="right" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; width: 4%;">&nbsp;</td>
				<td align="right" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; width: 5%;">&nbsp;</td>
				<td align="right" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; width: 4%;">&nbsp;</td>
				<td align="right" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; width: 6%;"><span>{CRMProduct:Quantity}</span>&nbsp;</td>
				<td align="right" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; width: 7%;">{CRMProduct:Price}</td>
				<td align="right" style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; width: 8%;"><span>{CRMProduct:Sum}</span>&nbsp;</td>
				<td style="border-bottom-width: 1px; border-bottom-style: solid; border-right-width: 1px; border-right-style: solid; width: 5%;">{CRMProduct:VATRate}</td>
				<td style="border: 1px solid #000000; width: 7%;">{CRMProduct:VAT}</td>
				<td align="right" style="border: 1px solid #000000; width: 9%;">{CRMProduct:SumVAT}</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td align="right">&nbsp;</td>
				<td align="right">&nbsp;</td>
				<td align="right">&nbsp;</td>
				<td align="right">&nbsp;</td>
				<td align="right" colspan="3" style="border: 1px solid #000000;"><span>Всего по накладной</span></td>
				<td align="right" style="border: 1px solid #000000;">&nbsp;</td>
				<td align="right" style="border: 1px solid #000000;">&nbsp;</td>
				<td align="right" style="border: 1px solid #000000;">{CRMProduct:TotalQuantity}</td>
				<td align="right" style="border: 1px solid #000000;">x</td>
				<td align="right" style="border: 1px solid #000000;">{CRMProduct:TotalSum}</td>
				<td align="right" style="border: 1px solid #000000;">x</td>
				<td align="right" style="border: 1px solid #000000;">{CRMProduct:TotalVAT}&nbsp;</td>
				<td align="right" style="border: 1px solid #000000;">{CRMProduct:TotalSumVAT}</td>
			</tr>
		</tfoot>
	</table>
	
	<p>&nbsp;</p>
	
	<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 6pt;">
		<tbody>
			<tr>
				<td colspan="13">
					<p>Товарная накладная имеет приложение на</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="9" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td colspan="3">
					<p>листах</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td colspan="5" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p>и содержит&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
				</td>
				<td colspan="20" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">{CRMProduct:RowsCount}</p>
				</td>
				<td colspan="8">
					<p>порядковых номеров записей</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="20" valign="top">
					<div style="font-size: 6pt; text-align: center;">прописью</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="8">
					<p>&nbsp;Масса груза (нетто)</p>
				</td>
				<td colspan="4" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="14" rowspan="2" style="border: 1px solid #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p align="right">&nbsp;</p>
				</td>
				<td colspan="4" valign="top">
					<div style="font-size: 6pt; text-align: center;">прописью</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td colspan="5">
					<p align="right">Всего мест</p>
				</td>
				<td colspan="11" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">{CRMProduct:TotalQuantitySpelling}&nbsp;</p>
				</td>
				<td colspan="8">
					<p>&nbsp;Масса груза (брутто)</p>
				</td>
				<td colspan="4" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="14" rowspan="2" style="border: 1px solid #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="11" valign="top">
					<div style="font-size: 6pt; text-align: center;">прописью</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="4" valign="top">
					<div style="font-size: 6pt; text-align: center;">прописью</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
		</tbody>
	</table>
	
	<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 6pt;">
		<tbody>
			<tr>
				<td colspan="14">
					<p>Приложение (паспорта, сертификаты, и т.п.) на</p>
				</td>
				<td colspan="12" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p>&nbsp;</p>
				</td>
				<td colspan="3">
					<p>листах</p>
				</td>
				<td style="border-right-width: 1px; border-right-style: solid; border-right-color: #000000;">
					<p>&nbsp;</p>
				</td>
				<td colspan="7">
					<p>&nbsp;По доверенности №</p>
				</td>
				<td colspan="5" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>от</p>
				</td>
				<td colspan="12" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="10" valign="top">
					<div style="font-size: 6pt; text-align: center;">прописью</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td style="border-right-width: 1px; border-right-style: solid; border-right-color: #000000;">
					<p>&nbsp;</p>
				</td>
				<td colspan="3">
					<p>&nbsp;выданной</p>
				</td>
				<td colspan="22" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td colspan="29" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;" valign="top">
					<p>Всего отпущено на сумму {CRMProduct:TotalSumSpelling}&nbsp;</p>
				</td>
				<td style="border-right-width: 1px; border-right-style: solid; border-right-color: #000000;">
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="21" valign="top">
					<div style="font-size: 6pt; text-align: center;">кем, кому (организация, должность, фамилия, и., о.)</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<p>Отпуск разрешил</p>
				</td>
				<td colspan="9" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">{Corp.CEO_POST}</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="5">
					<p align="center">&nbsp;</p>
				</td>
				<td colspan="9" style="border-bottom-width: inherit; border-bottom-style: inherit; border-bottom-color: inherit; border-right-width: 1px; border-right-style: solid; border-right-color: #000000;">
					<p align="center">{Corp.CEO_SHORT}</p>
				</td>
				<td colspan="4">
					<p>&nbsp;Груз принял</p>
				</td>
				<td colspan="8" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="5" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="6" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="8" valign="top">
					<div style="font-size: 6pt; text-align: center;">должность&nbsp;</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="5" style="border-top-width: 1px; border-top-style: solid; border-top-color: #000000;" valign="top">
					<div style="font-size: 6pt; text-align: center;">подпись</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="7" style="border-top-width: 1px; border-top-style: solid; border-top-color: #000000;" valign="top">
					<div style="font-size: 6pt; text-align: center;">расшифровка подписи</div>
				</td>
				<td style="border-right-width: 1px; border-right-style: solid; border-right-color: #000000;">
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="7" valign="top">
					<div style="font-size: 6pt; text-align: center;">должность</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="5" valign="top">
					<div style="font-size: 6pt; text-align: center;">подпись</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="6" valign="top">
					<div style="font-size: 6pt; text-align: center;">расшифровка подписи</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td colspan="10">
					<p>Главный (старший) бухгалтер&nbsp;&nbsp;&nbsp;&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="5">
					<p align="center">&nbsp;</p>
				</td>
				<td colspan="9" style="border-bottom-width: inherit; border-bottom-style: inherit; border-bottom-color: inherit; border-right-width: 1px; border-right-style: solid; border-right-color: #000000;">
					<p align="center">{Corp.CHIF_ACC}</p>
				</td>
				<td colspan="4">
					<p>&nbsp;Груз получил</p>
				</td>
				<td colspan="8" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="5" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="6" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="5" style="border-top-width: 1px; border-top-style: solid; border-top-color: #000000;" valign="top">
					<div style="font-size: 6pt; text-align: center;">подпись</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="7" style="border-top-width: 1px; border-top-style: solid; border-top-color: #000000;" valign="top">
					<div style="font-size: 6pt; text-align: center;">расшифровка подписи</div>
				</td>
				<td style="border-right-width: 1px; border-right-style: solid; border-right-color: #000000;">
					<p>&nbsp;</p>
				</td>
				<td colspan="5">
					<p>&nbsp;грузополучатель</p>
				</td>
				<td colspan="7" valign="top">
					<div style="font-size: 6pt; text-align: center;">должность</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="5" valign="top">
					<div style="font-size: 6pt; text-align: center;">подпись</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="6" valign="top">
					<div style="font-size: 6pt; text-align: center;">расшифровка подписи</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td colspan="7">
					<p>Отпуск груза произвел</p>
				</td>
				<td colspan="8" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="5">
					<p align="center">&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="7" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center">&nbsp;</p>
				</td>
				<td style="border-right-width: 1px; border-right-style: solid; border-right-color: #000000;">
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="7" rowspan="4">
					<p>&nbsp;</p>
					
					<p>М.П.</p>
					
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="7" valign="top">
					<div style="font-size: 6pt; text-align: center;">должность</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="5" style="border-top-width: 1px; border-top-style: solid; border-top-color: #000000;" valign="top">
					<div style="font-size: 6pt; text-align: center;">подпись</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td colspan="7" valign="top">
					<div style="font-size: 6pt; text-align: center;">расшифровка подписи</div>
				</td>
				<td style="border-right-width: 1px; border-right-style: solid; border-right-color: #000000;">
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td style="border-right-width: 1px; border-right-style: solid; border-right-color: #000000;">
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td colspan="8" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #000000;">
					<p align="center"><span>{DocDate}</span></p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td style="border-right-width: 1px; border-right-style: solid; border-right-color: #000000;">
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>М.П.</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td colspan="8" valign="top">
					<div style="font-size: 6pt; text-align: center;">дата</div>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td style="border-right-width: 1px; border-right-style: solid; border-right-color: #000000;">
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
				<td>
					<p>&nbsp;</p>
				</td>
			</tr>
		</tbody>
	</table>
</div>