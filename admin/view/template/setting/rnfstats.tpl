<table class="list">
	<tr>
		<td colspan="4" class="left" style="color:#0054b3;">
			<i class="fa fa-plus"></i> <b>Товары</b>
		</td>
	</tr>
	<tr>
		<td class="right">
			Добавлено товаров за сегодня
		</td>
		<td class="center">
			<b><?php echo $total_products_added_today; ?></b>
		</td>
		<td style="width:20px" class="center">
			<a style="color:#66c7a3; display:inline-block; font-size:20px;" href="<?php echo $filter_total_products_added_today; ?>"><i class="fa fa-filter"></i></a>
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Количество уникальных товаров, добавленных за сегодня. Варианты учитываются в этом подсчёте, поэтому при фильтрации с включенным режимом <i class="fa fa-amazon" aria-hidden="true"></i> VAR количество отображаемых товаров может быть меньше.
			</span>
		</td>		
	</tr>

	<tr>
		<td class="right">
			Добавлено товаров за вчера
		</td>
		<td class="center">
			<b><?php echo $total_products_added_yesterday; ?></b>
		</td>
		<td style="width:20px" class="center">
			<a style="color:#66c7a3; display:inline-block; font-size:20px;" href="<?php echo $filter_total_products_added_yesterday; ?>"><i class="fa fa-filter"></i></a>
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Количество уникальных товаров, добавленных за вчера. Варианты учитываются в этом подсчёте, поэтому при фильтрации с включенным режимом <i class="fa fa-amazon" aria-hidden="true"></i> VAR количество отображаемых товаров может быть меньше.
			</span>
		</td>
	</tr>

	<tr>
		<td class="right">
			Добавлено товаров за неделю
		</td>
		<td class="center">
			<b><?php echo $total_products_added_week; ?></b>
		</td>
		<td>
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Количество уникальных товаров, добавленных за неделю (7 дней от сегодня). Варианты учитываются в этом подсчёте, поэтому при фильтрации с включенным режимом <i class="fa fa-amazon" aria-hidden="true"></i> VAR количество отображаемых товаров может быть меньше.
			</span>
		</td>
	</tr>

	<tr>
		<td class="right">
			Добавлено товаров за месяц
		</td>
		<td class="center">
			<b><?php echo $total_products_added_month; ?></b>
		</td>
		<td>
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Количество уникальных товаров, добавленных за месяц (30 дней от сегодня). Варианты учитываются в этом подсчёте, поэтому при фильтрации с включенным режимом <i class="fa fa-amazon" aria-hidden="true"></i> VAR количество отображаемых товаров может быть меньше.
			</span>
		</td>		
	</tr>

	<tr>
		<td class="right" style="color:#fa4934;">
			Плохие товары
		</td>
		<td class="center">
			<b><?php echo $total_products_invalid_asin; ?></b>
		</td>
		<td class="center">
			<a style="color:#fa4934; display:inline-block; font-size:20px;" href="<?php echo $filter_total_products_invalid_asin; ?>"><i class="fa fa-filter"></i></a>
		</td>
		<td>
			<span class="help" style="color:#fa4934;">
				<i class="fa fa-info-circle"></i> Некоторые товары после добавления через какое-то время удаляются с Amazon. При проверке офферов, либо валидации по asin, если товар не найден на Amazon, его ASIN изменяется на значение INVALID. Желательно просматривать эти товары и удалять их, либо находить вручную измененный идентификатор
			</span>
		</td>		
	</tr>

	<tr>
		<td class="right" style="color:#fa4934;">
			Дубли ASIN
		</td>
		<td class="center">
			<b><?php echo $total_products_double_asin; ?></b>
		</td>
		<td class="center">			
		</td>
		<td>
			<span class="help" style="color:#fa4934;">
				<i class="fa fa-info-circle"></i> При загрузке больших объемов данных возможны сбои. Этот счетчик показывает количество задвоенных ASIN в базе товаров. Если он больше нуля - это сигнализирует о ошибках логики добавления. Желательно разбираться.
			</span>
		</td>		
	</tr>


	<tr>
		<td colspan="4" class="left" style="color:#0054b3;">
			<i class="fa fa-plus"></i> <b>Офферы</b>
		</td>
	</tr>

	<tr>
		<td class="right">
			В ожидании
		</td>
		<td class="center">
			<b><?php echo $total_product_to_get_offers; ?></b>
		</td>
		<td style="width:20px" class="center">			
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Количество офферов, которые стоят в очереди на получение для воркера <i>Получение офферов с Amazon</i>
			</span>
		</td>		
	</tr>

	<tr>
		<td class="right">
			Всего офферов в базе
		</td>
		<td class="center">
			<b><?php echo $total_product_got_offers; ?></b>
		</td>
		<td style="width:20px" class="center">			
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Общее количество товаров, по которым есть информация о предложениях, ценах и наличии на Amazon на текущий момент.
			</span>
		</td>		
	</tr>

	<tr>
		<td class="right">
			Получено за сегодня
		</td>
		<td class="center">
			<b><?php echo $total_product_got_offers_today; ?></b>
		</td>
		<td style="width:20px" class="center">			
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Общее количество товаров, по которым были получены офферы за сегодня
			</span>
		</td>		
	</tr>

	<tr>
		<td class="right">
			Получено за вчера
		</td>
		<td class="center">
			<b><?php echo $total_product_got_offers_yesterday; ?></b>
		</td>
		<td style="width:20px" class="center">			
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Общее количество товаров, по которым были получены офферы за вчера
			</span>
		</td>		
	</tr>

	<tr>
		<td class="right">
			Есть в наличии
		</td>
		<td class="center">
			<b><?php echo $total_product_have_offers; ?></b>
		</td>
		<td style="width:20px" class="center">	
			<a style="color:#66c7a3; display:inline-block; font-size:20px;" href="<?php echo $filter_total_product_have_offers; ?>"><i class="fa fa-filter"></i></a>		
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Общее количество товаров, которые есть в наличии у поставщиков на Amazon. Варианты учитываются в этом подсчёте, поэтому при фильтрации с включенным режимом <i class="fa fa-amazon" aria-hidden="true"></i> VAR количество отображаемых товаров может быть меньше.
			</span>
		</td>		
	</tr>

	<tr>
		<td class="right" style="color:#fa4934;">
			Нету в наличии
		</td>
		<td class="center" style="color:#fa4934;">
			<b><?php echo $total_product_have_no_offers; ?></b>
		</td>
		<td style="width:20px" class="center">	
			<a style="color:#fa4934; display:inline-block; font-size:20px;" href="<?php echo $filter_total_product_have_offers; ?>"><i class="fa fa-filter"></i></a>		
		</td>
		<td>
			<span class="help" style="color:#fa4934;">
				<i class="fa fa-info-circle"></i> Общее количество товаров, которых нет в наличии у поставщиков на Amazon. Это только те товары, по которым фактически была произведена попытка получить офферы, но Amazon вернул пустой массив. В это число не входят новые товары, по которым еще не было попытки получить предложения. Варианты учитываются в этом подсчёте, поэтому при фильтрации с включенным режимом <i class="fa fa-amazon" aria-hidden="true"></i> VAR количество отображаемых товаров может быть меньше.
			</span>
		</td>		
	</tr>


	<tr>
		<td colspan="4" class="left" style="color:#0054b3;">
			<i class="fa fa-bars"></i> <b>Категории</b>
		</td>
	</tr>

	<tr>
		<td class="right">
			Всего категорий
		</td>
		<td class="center">
			<b><?php echo $total_categories; ?></b>
		</td>
		<td style="width:20px" class="center">			
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Всего категорий в дереве категорий. Сюда включены как категории, полученные с Amazon, так и созданные вручную.
			</span>
		</td>		
	</tr>

	<tr>
		<td class="right">
			Конечных категорий
		</td>
		<td class="center">
			<b><?php echo $total_categories_final; ?></b>
		</td>
		<td style="width:20px" class="center">			
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Конечная категория - это категория, у которой нету дочерних. Воркер <i>Парсер новых товаров Amazon</i> работает исключительно с этими категориями. Также не стоит допускать, чтоб товары принадлежали каким-либо категориям, кроме этих. 
			</span>
		</td>		
	</tr>

	<tr>
		<td class="right">
			Синхронизируемые категории
		</td>
		<td class="center">
			<b><?php echo $total_categories_enable_load; ?></b>
		</td>
		<td style="width:20px" class="center">			
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Количество категорий, у которых включен маркер <i>Разрешить загрузку информации о новых товарах</i> для воркера <i>Парсер новых товаров Amazon</i>. Только по этим категориям выполняется запрос получения и добавления новых товаров.
			</span>
		</td>		
	</tr>

	<tr>
		<td class="right">
			Категории с полной загрузкой
		</td>
		<td class="center">
			<b><?php echo $total_categories_enable_full_load; ?></b>
		</td>
		<td style="width:20px" class="center">			
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Количество категорий, у которых включен маркер <i>Разрешить загрузку полной информации о товарах</i> для воркеров <i>Парсер данных о товарах Amazon</i> и <i>Парсер данных о товарах Amazon L2 	</i>. Только для товаров в этих категориях выполняется загрузка полной информации о товарах.
			</span>
		</td>		
	</tr>




	<tr>
		<td colspan="4" class="left" style="color:#0054b3;">
			<i class="fa fa-yahoo"></i> <b>Переводчик</b>
		</td>
	</tr>

	<tr>
		<td class="right">
			Переведено за час
		</td>
		<td class="center">
			<b><?php echo $translated_total_hour; ?></b>
		</td>
		<td style="width:20px" class="center">			
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Количество символов, переданных в переводчик за последний час
			</span>
		</td>		
	</tr>

	<tr>
		<td class="right">
			Переведено за сегодня
		</td>
		<td class="center">
			<b><?php echo $translated_total_today; ?></b>
		</td>
		<td style="width:20px" class="center">			
		</td>
		<td>
			<span class="help">
				<i class="fa fa-info-circle"></i> Количество символов, переданных в переводчик с начала этого дня
			</span>
		</td>		
	</tr>

</table>