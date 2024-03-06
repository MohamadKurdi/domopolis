<?php echo $header; ?>
<style>
    .red{
        background-color:#ef5e67;
    }
    .orange{
        background-color:#ff7f00;
    }
    .green{
        background-color:#00ad07;
    }    
    .black{
         background-color:#353740;
    }

    .text-red{
        color:#ef5e67;
    }
    .text-orange{
        color:#ff7f00;
    }
    .text-green{
        color:#00ad07;
    }

    input.process, textarea.process{
        border:2px solid #ff7f00;
        background-color: #ead985;
    }
    input.finished, textarea.finished{
        border:2px solid #00ad07;
        background-color: #e9ece6;
    }

    span.smallbutton{
        padding:3px 5px;
        display:inline-block;
        margin-right:10px;
        color:white;
        cursor:pointer;
    }
</style>
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
			<h1>
				<?php echo $heading_title; ?>
				<?php if (!empty($supplier_name)) { ?> / <?php echo $supplier_name; ?><?php } ?>
			</h1>
			<div class="buttons">
				<?php if (!empty($offers_link)) { ?>
					<a href="<?php echo $offers_link; ?>" class="button"><i class="fa fa-amazon"></i> Посмотреть офферы поставщика</a>
				<?php } ?>
				<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
				<a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
			</div>			
		</div>
		<div class="content">		
				<div id="tabs" class="htabs">
					<a href="#tab-info"><i class="fa fa-info"></i> Поставщик</a>
					<a href="#tab-categories"><i class="fa fa-refresh"></i> Сопоставление категорий парсера <?php if (!empty($supplier_categories_total)) { ?>(<?php echo $supplier_categories_total; ?>)<?php } ?></a>	
					<a href="#tab-attributes"><i class="fa fa-refresh"></i> Сопоставление атрибутов парсера <?php if (!empty($supplier_attributes_total)) { ?>(<?php echo $supplier_attributes_total; ?>)<?php } ?></a>			
					<div class="clr"></div>
				</div>
				<div class="th_style"></div>

			<div id="tab-info">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div style="width:100%;">
					<table class="form">
						<tr>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Наименование</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Business name</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Короткий код</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Тип поставщика</span></td>							
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Головной поставщик</span></td>
						</tr>
						
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="supplier_name" value="<?php echo $supplier_name; ?>" />
								<br /><span class="help">Читаемое название</span>
							</td>
							<td>
								<input type="text" name="business_name" value="<?php echo $business_name; ?>" />
								<br /><span class="help">Юридическое название (Amazon)</span>
							</td>
							<td>
								<input type="text" name="supplier_code" value="<?php echo $supplier_code; ?>" />
								<br /><span class="help">К примеру, VBOG</span>
							</td>
							<td>
								<? $_a = array(' ', 'Официальный поставщик / производитель', 'Официальный магазин сторонних поставщиков', 'Amazon', 'EBay'); ?>
								<select name="supplier_type">
									<? foreach ($_a as $_s) { ?>
										<option value="<? echo $_s; ?>" <? if ($supplier_type == $_s) { ?>selected="selected" <? } ?>><? echo $_s; ?></option>
									<? } ?>
								</select>
								<br /><span class="help">Тип поставщика. Нигде не используется</span>
							</td>							
							<td>
								<input type="hidden" name="supplier_parent_id" value="<?php echo $supplier_parent_id; ?>" style="width:300px" />
								<input name="supplier_parent" value="<? echo $supplier_parent; ?>" style="width:300px;" placeholder="Автоподбор" />
								<br /><span class="help">Головной, или родительский</span>
							</td>
						</tr>
					</table>


					<table class="form">
						<tr>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Amazon Seller ID</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Amazon Registration Number</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Amazon VAT NUMBER</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Amazon Business Type</span></td>	
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Ccылка на Amazon</span></td>			
						</tr>

						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="amazon_seller_id" value="<?php echo $amazon_seller_id; ?>" />

								<br />
								<span class="help"><i class="fa fa-info-circle"></i> Seller ID в Amazon</span>
							</td>
							<td>
								<input type="text" name="registration_number" value="<?php echo $registration_number; ?>" />

								<br />
								<span class="help"><i class="fa fa-info-circle"></i> Registration Number в Amazon</span>
							</td>
							<td>
								<input type="text" name="vat_number" value="<?php echo $vat_number; ?>" />

								<br />
								<span class="help"><i class="fa fa-info-circle"></i> VAT NUMBER в Amazon</span>
							</td>
							<td>
								<input type="text" name="business_type" value="<?php echo $business_type; ?>" />

								<br />
								<span class="help"><i class="fa fa-info-circle"></i> Business Type в Amazon</span>
							</td>
							<td>
								<input type="text" name="store_link" value="<?php echo $store_link; ?>" />

								<?php if ($store_link) { ?>
									<br />
									<span class="help"><a href="<?php echo $store_link; ?>" target="_blank">открыть ссылку</a></span>
								<?php } ?>
							</td>
						</tr>
					</table>

					<table class="form">
						<tr>
							<td width="33%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Рейтинг50</span></td>
							<td width="33%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Всего отзывов</span></td>
							<td width="33%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Процент позитивных</span></td>							
						</tr>
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="number" step="1" name="rating50" value="<?php echo $rating50; ?>" />
							</td>
							<td>
								<input type="number" step="1" name="ratings_total" value="<?php echo $ratings_total; ?>" />
							</td>
							<td>
								<input type="number" step="1" name="positive_ratings100" value="<?php echo $positive_ratings100; ?>" />
							</td>
						</tr>
					</table>

					<table class="form">
						<tr>
							<td width="33%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Amazon Приоритетный Поставщик</span></td>
							<td width="33%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Amazon Плохой Поставщик</span></td>
							<td width="33%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Amazon Произвольный Коэффициент</span></td>			
						</tr>

						<tr style="border-bottom:1px dashed gray">
							<td>
								<select name="amzn_good">
									<?php if ($amzn_good) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
								<br /><span class="help"><i class="fa fa-info-circle"></i> если поставщик приоритетный, то при оценивании его предложения к рейтингу добавляется 10</span>
							</td>
							<td>
								<select name="amzn_bad">
									<?php if ($amzn_bad) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
								<br /><span class="help"><i class="fa fa-info-circle"></i> если поставщик плохой, то при оценивании его предложения от рейтинга отнимается 20</span>
							</td>
							<td>
								<input type="number" step="1" name="amzn_coefficient" value="<?php echo $amzn_coefficient; ?>" />

								<br />
								<span class="help"><i class="fa fa-info-circle"></i> если нет понимания, что выбрать из предыдущих, то введи здесь коэффициент оптимизации или пессимизации рейтинга. Внимание! Для игнорирования офферов поставщика понизь рейтинг меньше 100 (например, -200, -500)</span>
							</td>
						</tr>
					</table>
					
					<table class="form">
						<tr>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Страна</span></td>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Телефон</span></td>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Почта</span></td>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Нативный поставщик</span></td>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Рынок</span></td>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Сортировка</span></td>
							<td width="14%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Описание / комментарий</span></td>
						</tr>
						
						<tr style="border-bottom:1px dashed gray">
							<td>
								<input type="text" name="supplier_country" value="<?php echo $supplier_country; ?>"  />
							</td>
							<td>
								<input type="text" name="telephone" value="<?php echo $telephone; ?>"  />
							</td>
							<td>
								<input type="text" name="email" value="<?php echo $email; ?>"  />
							</td>
							<td>
								<select name="is_native">
									<?php if ($is_native) { ?>
										<option value="1" selected="selected">Да</option>
										<option value="0">Нет</option>
									<?php } else { ?>
										<option value="1">Да</option>
										<option value="0" selected="selected">Нет</option>
									<?php } ?>
								</select>								
							</td>
							<td>
								<select name="supplier_inner">
									<?php if ($supplier_inner) { ?>
										<option value="1" selected="selected">Внутренний рынок</option>
										<option value="0">Внешний рынок</option>
									<?php } else { ?>
										<option value="1">Внутренний рынок</option>
										<option value="0" selected="selected">Внешний рынок</option>
									<?php } ?>
								</select>
							</td>
							<td>
								<input type="number" step="1" name="sort_order" value="<?php echo $sort_order; ?>" size="3" />
							</td>
							
							<td>
								<textarea name="supplier_comment" cols="50" rows="3"><?php echo $supplier_comment; ?></textarea>
							</td>
						</tr>
					</table>
					
					<table class="form">
						<tr>
							<td width="15%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Коэффициент - 1</span></td>
							<td width="15%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Коэффициент - 2</span></td>
							<td width="15%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Коэффициент - 3</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Сроки доставки - 1</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Сроки доставки - 2</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Склад</span></td>
						</tr>
						<tr>							
							<td>
								<input type="text" name="supplier_m_coef" value="<?php echo $supplier_m_coef; ?>" />
								<br /><span class="help">пока не используется</span>
							</td>
							
							<td>
								<input type="text" name="supplier_l_coef" value="<?php echo $supplier_l_coef; ?>" />
								<br /><span class="help">пока не используется</span>
							</td>
							
							<td>
								<input type="text" name="supplier_n_coef" value="<?php echo $supplier_n_coef; ?>" />
								<br /><span class="help">пока не используется</span>
							</td>
							
							<td>
								<input type="text" name="terms_instock" value="<?php echo $terms_instock; ?>" />
								<br /><span class="help">если есть в наличии у поставщика, дней от-до, или одно число</span>
							</td>
							
							<td>
								<input type="text" name="terms_outstock" value="<?php echo $terms_outstock; ?>" />
								<br /><span class="help">если нет в наличии у поставщика и на локальном складе, дней от-до, или одно число</span>
							</td>

							<td>
								<select name="same_as_warehouse">
									<?php if ($same_as_warehouse) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
								<br /><span class="help">приравнивает наличие у поставщика к наличию на локальном складе</span>
							</td>
						</tr>
					</table>

					<table class="form">
						<tr>
							<td width="50%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">About this Seller</span></td>
							<td width="50%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">Detailed Information</span></td>
						</tr>
						<tr>
							<td>
								<textarea name="about_this_seller" style="width:90%" rows="10"><?php echo $about_this_seller; ?></textarea>
							</td>
							<td>
								<textarea name="detailed_information" style="width:90%" rows="10"><?php echo $detailed_information; ?></textarea>
							</td>
						</tr>
					</table>

					<h2>Фид, парсер</h2>
					<table class="form">
						<tr>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Фид данных</span></td>
							<td width="10%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Библиотека разбора</span></td>
							<td width="10%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Поле</span></td>
							<td width="10%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Язык в фиде</span></td>
							<td width="10%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Парсить</span></td>													
							<td width="10%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Фильтр в админке</span></td>
							<td width="10%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Валюта</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Загрузить категории</span></td>
						</tr>
						<tr>
							<td>
								<textarea name="path_to_feed" style="width:90%" rows="2"><?php echo $path_to_feed; ?></textarea>
							</td>
							<td>
								<select name="parser">	
									<option value="">Нету библиотеки</option>
									<?php foreach ($parser_libraries as $parser_library) { ?>
										<?php if ($parser_library == $parser) { ?>
											<option value="<?php echo $parser_library; ?>" selected="selected"><?php echo $parser_library; ?></option>
										<?php } else { ?>
											<option value="<?php echo $parser_library; ?>"><?php echo $parser_library; ?></option>
										<?php } ?>
									<?php } ?>				
								</select>
							</td>
							<td>
								<input type="text" name="sync_field" value="<?php echo $sync_field; ?>" style="width:50px"/>
							</td>
							<td>
								<select name="language_in_feed">
									<?php foreach ($languages as $language) { ?>
										<?php if ($language['code'] == $language_in_feed) { ?>
											<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
							<td>
								<select name="parser_status">
									<?php if ($parser_status) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
							</td>
							<td>
								<select name="admin_status">
									<?php if ($admin_status) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
							</td>															
							<td>
								<input type="text" name="currency" value="<?php echo $currency; ?>" style="width:50px"/>
							</td>							
							<td>
								<?php if (!empty($update_categories)) { ?>
									<a class="button" href="<?php echo $update_categories; ?>">Загрузить</a>
									<a class="button" href="<?php echo $clear_categories; ?>">Очистить</a>
								<?php } ?>
							</td>
						</tr>
					</table>

					<table class="form">
						<tr>
							<td width="15%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">В фиде РРЦ</span></td>
							<td width="15%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Обновлять наличие</span></td>
							<td width="15%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Обновлять цену</span></td>
							<td width="15%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Сразу включать товары</span></td>
							<td width="15%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Пропускать без категорий</span></td>
							<td width="20%"><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">Загрузить Атрибуты</span></td>
						</tr>
						<tr>
							<td>
								<select name="rrp_in_feed">
									<?php if ($rrp_in_feed) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
							</td>
							<td>
								<select name="stock">
									<?php if ($stock) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
							</td>
							<td>
								<select name="prices">
									<?php if ($prices) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
							</td>
							<td>
								<select name="auto_enable">
									<?php if ($auto_enable) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
							</td>
							<td>
								<select name="skip_no_category">
									<?php if ($skip_no_category) { ?>
										<option value="1" selected="selected">Включить</option>
										<option value="0">Отключить</option>
									<?php } else { ?>													
										<option value="1">Включить</option>
										<option value="0"  selected="selected">Отключить</option>
									<? } ?>
								</select>
							</td>
							<td>
								<?php if (!empty($update_attributes)) { ?>
									<a class="button" href="<?php echo $update_attributes; ?>">Загрузить</a>
									<a class="button" href="<?php echo $clear_attributes; ?>">Очистить</a>
								<?php } ?>
							</td>
						</tr>
					</table>

				</div>
				<div style="clear:both;"></div>
				</form>
			</div>	

			<div id="tab-categories">
				<table class="list">
					<tr>
						<td class="left">Поставщик</td>
						<td class="left">Магазин</td>
						<td class="left">Товары</td>
						<td class="left">Наличие</td>
						<td class="left">Цены</td>						
					</tr>
					<?php foreach ($supplier_categories as $supplier_category) { ?>
						<tr>
							<td class="left" style="font-size:20px; width:400px;">
								<?php echo $supplier_category['supplier_category']; ?>
								<?php if ($supplier_category['supplier_category_full']) { ?>
									<br /><small style="font-size:10px;"><?php echo $supplier_category['supplier_category_full']; ?></small>
								<?php } ?>
							</td>
							<td class="left" style="padding:5px;">
								<div>
									<input type="text" style="width:95%;" class="supplier_category_autocomplete" data-supplier-category-id="<?php echo $supplier_category['supplier_category_id']; ?>" id="category_<?php echo $supplier_category['supplier_category_id']; ?>" value="<?php echo $supplier_category['path']; ?>" placeholder="Автоподбор" />			
									<input type="hidden" class="supplier_category_id" id="category_id_<?php echo $supplier_category['supplier_category_id']; ?>" data-field="category_id"  data-supplier-category-id="<?php echo $supplier_category['supplier_category_id']; ?>" value="<?php echo $supplier_category['category_id']; ?>">

									<a class="button" onclick="$('#category_id_<?php echo $supplier_category['supplier_category_id']; ?>').val('').trigger('change'); $('#category_<?php echo $supplier_category['supplier_category_id']; ?>').val(''); return false;"><i class="fa fa-times"></i></a>								
								</div>
								<div style="text-align:left;">
									<?php if ($supplier_category['guessed']) { ?>
										<?php foreach ($supplier_category['guessed'] as $guessed) { ?>
											<span style="border-bottom:1px dashed grey; cursor:pointer; margin-right:10px; font-size:10px;" onclick="$('#category_id_<?php echo $supplier_category['supplier_category_id']; ?>').val('<?php echo $guessed['category_id']; ?>').trigger('change'); $('#category_<?php echo $supplier_category['supplier_category_id']; ?>').val('<?php echo $guessed['name']; ?>'); ">
												<?php echo $guessed['name']; ?>
											</span>
										<?php } ?>
									<?php } ?>
								</div>
							</td>
							<td class="center" style="padding:5px; width:50px;">
								<input class="supplier_category_checkbox checkbox" data-field="products" data-supplier-category-id="<?php echo $supplier_category['supplier_category_id']; ?>" id="products_<?php echo $supplier_category['supplier_category_id']; ?>" type="checkbox" name="products_<?php echo $supplier_category['supplier_category_id']; ?>" <? if ($supplier_category['products']){ ?> checked="checked" <? } ?> value="1" />
								<label for="products_<?php echo $supplier_category['supplier_category_id']; ?>"></label>
							</td>
							<td class="center" style="padding:5px; width:50px;">
								<input class="supplier_category_checkbox checkbox" data-field="stocks" data-supplier-category-id="<?php echo $supplier_category['supplier_category_id']; ?>" id="stocks_<?php echo $supplier_category['supplier_category_id']; ?>" type="checkbox" name="stocks_<?php echo $supplier_category['supplier_category_id']; ?>" <? if ($supplier_category['stocks']){ ?> checked="checked" <? } ?> value="1" />
								<label for="stocks_<?php echo $supplier_category['supplier_category_id']; ?>"></label>
							</td>
							<td class="center" style="padding:5px; width:50px;">
								<input class="supplier_category_checkbox checkbox" data-field="prices" data-supplier-category-id="<?php echo $supplier_category['supplier_category_id']; ?>" id="prices_<?php echo $supplier_category['supplier_category_id']; ?>" type="checkbox" name="prices_<?php echo $supplier_category['supplier_category_id']; ?>" <? if ($supplier_category['prices']){ ?> checked="checked" <? } ?> value="1" />
								<label for="prices_<?php echo $supplier_category['supplier_category_id']; ?>"></label>
							</td>
						</tr>
					<?php } ?>
				</table>
			</div>

			<div id="tab-attributes">
				<table class="list">
					<tr>
						<td class="left">Поставщик</td>
						<td class="left">Магазин</td>						
					</tr>
					<?php foreach ($supplier_attributes as $supplier_attribute) { ?>
						<tr>
							<td class="left" style="font-size:20px; width:400px;">
								<?php echo $supplier_attribute['supplier_attribute']; ?>

							</td>
							<td class="left" style="padding:5px;">
								<div>
									<input type="text" style="width:95%;" class="supplier_attribute_autocomplete" data-supplier-attribute-id="<?php echo $supplier_attribute['supplier_attribute_id']; ?>" id="attribute_<?php echo $supplier_attribute['supplier_attribute_id']; ?>" value="<?php echo $supplier_attribute['name']; ?>" placeholder="Автоподбор" />			
									<input type="hidden" class="supplier_attribute_id" id="attribute_id_<?php echo $supplier_attribute['supplier_attribute_id']; ?>" data-field="attribute_id"  data-supplier-attribute-id="<?php echo $supplier_attribute['supplier_attribute_id']; ?>" value="<?php echo $supplier_attribute['attribute_id']; ?>">

									<a class="button" onclick="$('#attribute_id_<?php echo $supplier_attribute['supplier_attribute_id']; ?>').val('').trigger('change'); $('#attribute_<?php echo $supplier_attribute['supplier_attribute_id']; ?>').val(''); return false;"><i class="fa fa-times"></i></a>								
								</div>
								<div style="text-align:left;">
									<?php if ($supplier_attribute['guessed']) { ?>
										<?php foreach ($supplier_attribute['guessed'] as $guessed) { ?>
											<span style="border-bottom:1px dashed grey; cursor:pointer; margin-right:10px; font-size:10px;" onclick="$('#attribute_id_<?php echo $supplier_attribute['supplier_attribute_id']; ?>').val('<?php echo $guessed['attribute_id']; ?>').trigger('change'); $('#attribute_<?php echo $supplier_attribute['supplier_attribute_id']; ?>').val('<?php echo $guessed['name']; ?>'); ">
												<?php echo $guessed['name']; ?>
											</span>
										<?php } ?>
									<?php } ?>
								</div>
							</td>
						</tr>
					<?php } ?>
				</table>
			</div>


		</div>
	</div>
</div>
<script type="text/javascript">
	$('.supplier_attribute_id').bind('change', function(){save_attribute($(this)); });			

	function save_attribute(elem){
        let supplier_attribute_id  	= elem.attr('data-supplier-attribute-id');
        let field 					= elem.attr('data-field');
        let value 					= elem.val();   

        if (elem.attr('type') == 'checkbox'){
        	if (elem.attr('checked')){
        		value = 1;
        	} else {
        		value = 0;
        	}
        }           

        $.ajax({
            url : 'index.php?route=sale/supplier/field_attribute&token=<?php echo $token; ?>',
            data: {
                supplier_attribute_id: supplier_attribute_id,
                field:    	  field,
                value:    	  value,
            },
            type: 'POST',
            beforeSend: function(){
            	if (field == 'attribute_id'){
            		$('#attribute_' + supplier_attribute_id).removeClass('process, finished').addClass('finished');
            	} else {
            		elem.removeClass('process, finished').addClass('finished');
            	}               
            },
            success: function(){
            	if (field == 'attribute_id'){
                	$('#attribute_' + supplier_attribute_id).removeClass('process, finished').addClass('finished');
                } else {
            		elem.removeClass('process, finished').addClass('finished');
            	} 
            }
        });
    }

	$('.supplier_attribute_autocomplete').each(function(index, elem){
		elem = $(this);
		elem.autocomplete({
			delay: 500,
			source: function(request, response) {		
				$.ajax({
					url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {				
						response($.map(json, function(item) {
							return {
								label: item.name + ' (' + item.attribute_group + ')',
								value: item.attribute_id
							}
						}));
					}
				});
			},
			select: function(event, ui) {
				elem.val(ui.item.label);
				$('#attribute_id_' + elem.attr('data-supplier-attribute-id')).val(ui.item.value).trigger('change');

				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
	});
</script> 

<script type="text/javascript">
	$('.supplier_category_checkbox, .supplier_category_id').bind('change', function(){save_category($(this)); });			

	function save_category(elem){
        let supplier_category_id  	= elem.attr('data-supplier-category-id');
        let field 					= elem.attr('data-field');
        let value 					= elem.val();   

        if (elem.attr('type') == 'checkbox'){
        	if (elem.attr('checked')){
        		value = 1;
        	} else {
        		value = 0;
        	}
        }           

        $.ajax({
            url : 'index.php?route=sale/supplier/field_category&token=<?php echo $token; ?>',
            data: {
                supplier_category_id: supplier_category_id,
                field:    	  field,
                value:    	  value,
            },
            type: 'POST',
            beforeSend: function(){
            	if (field == 'category_id'){
            		$('#category_' + supplier_category_id).removeClass('process, finished').addClass('finished');
            	} else {
            		elem.removeClass('process, finished').addClass('finished');
            	}               
            },
            success: function(){
            	if (field == 'category_id'){
                	$('#category_' + supplier_category_id).removeClass('process, finished').addClass('finished');
                } else {
            		elem.removeClass('process, finished').addClass('finished');
            	} 
            }
        });
    }

	$('.supplier_category_autocomplete').each(function(index, elem){
		elem = $(this);
		elem.autocomplete({
			delay: 500,
			source: function(request, response) {		
				$.ajax({
					url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
					dataType: 'json',
					success: function(json) {				
						response($.map(json, function(item) {
							return {
								label: item.name,
								value: item.category_id
							}
						}));
					}
				});
			},
			select: function(event, ui) {
				elem.val(ui.item.label);
				$('#category_id_' + elem.attr('data-supplier-category-id')).val(ui.item.value).trigger('change');

				return false;
			},
			focus: function(event, ui) {
				return false;
			}
		});
	});
</script> 

<script type="text/javascript">
	$('input[name=\'supplier_parent\']').autocomplete({
		delay: 500,
		source: function(request, response) {		
			$.ajax({
				url: 'index.php?route=sale/supplier/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {
					json.unshift({
						'supplier_id':  0,
						'supplier_name':  'Не выбрано'
					});

					response($.map(json, function(item) {
						return {
							label: item.supplier_name,
							value: item.supplier_id
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'supplier_parent\']').val(ui.item.label);
			$('input[name=\'supplier_parent_id\']').val(ui.item.value);

			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
</script> 
<script type="text/javascript">
		$('#tabs a').tabs();
	</script> 
<?php echo $footer; ?>