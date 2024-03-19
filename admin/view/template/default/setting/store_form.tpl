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
		<div class="heading">
			<h1><?php echo $heading_title; ?> / <?php echo $config_name; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<style>
				#tabs > a {font-weight:700}
			</style>
			<div id="tabs" class="htabs">
				<a href="#tab-general"><i class="fa fa-bars"></i> <?php echo $tab_general; ?></a>
				<a href="#tab-store"><i class="fa fa-cogs"></i> <?php echo $tab_store; ?></a>
				<a href="#tab-terms"><i class="fa fa-clock-o"></i> Сроки</a>
				<a href="#tab-app"><i class="fa fa-mobile"></i> Приложение</a>
				<a href="#tab-local"><i class="fa fa-bars"></i> <?php echo $tab_local; ?></a>
				<a href="#tab-option"><i class="fa fa-cogs"></i> <?php echo $tab_option; ?></a>
				<a href="#tab-image"><i class="fa fa-cogs"></i> <?php echo $tab_image; ?></a>
				<a href="#tab-sms"><i class="fa fa-mobile"></i> SMS</a>			
				<a href="#tab-server"><i class="fa fa-cogs"></i> <?php echo $tab_server; ?></a>
				<a href="#tab-telephony"><i class="fa fa-phone"></i> АТС</a>
				<a href="#tab-google-ya-fb-vk"><i class="fa fa-google"></i> <span style="color:#57AC79;">Google</span>, <span style="color:red;">Ya</span>, <span style="color:#7F00FF;">FB</span>, <span style="color:#3F6AD8;">VK</span></a>
			<div class="clr"></div></div>
			<div class="th_style"></div>
			
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<input type="hidden" name="store_id" value="<?php echo $store_id; ?>"/>
				<div id="tab-general">
					<table class="form">
						<tr>
							<td><span class="required">*</span> <?php echo $entry_url; ?></td>
							<td><input type="text" name="config_url" value="<?php echo $config_url; ?>" size="40" />
								<?php if ($error_url) { ?>
									<span class="error"><?php echo $error_url; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_ssl; ?></td>
							<td><input type="text" name="config_ssl" value="<?php echo $config_ssl; ?>" size="40" /></td>
						</tr>
						<tr>
							<td>Шард-сервер</td>
							<td><input type="text" name="config_img_url" value="<?php echo $config_img_url; ?>" size="40" /></td>
						</tr>
						<tr>
							<td>SSL Шард-сервер</td>
							<td><input type="text" name="config_img_ssl" value="<?php echo $config_img_ssl; ?>" size="40" /></td>
						</tr>
						<tr>
							<td>Шард-сервера дополнительные</td>
							<td><input type="text" name="config_img_urls" value="<?php echo $config_img_urls; ?>" size="40" /></td>
						</tr>
						<tr>
							<td>SSL Шард-сервера дополнительные</td>
							<td><input type="text" name="config_img_ssls" value="<?php echo $config_img_ssls; ?>" size="40" /></td>
						</tr>
						<tr>
							<td>Количество шард-серверов (0 - N)</td>
							<td><input type="text" name="config_img_server_count" value="<?php echo $config_img_server_count; ?>" size="2" /></td>
						</tr>
						<tr>
							<td>Поддомен для статик-контента</td>
							<td><input type="text" name="config_static_subdomain" value="<?php echo $config_static_subdomain; ?>" size="40" /></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_name; ?></td>
							<td><input type="text" name="config_name" value="<?php echo $config_name; ?>" size="40" />
								<?php if ($error_name) { ?>
									<span class="error"><?php echo $error_name; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_owner; ?></td>
							<td><input type="text" name="config_owner" value="<?php echo $config_owner; ?>" size="40" />
								<?php if ($error_owner) { ?>
									<span class="error"><?php echo $error_owner; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_address; ?></td>
							<td><textarea name="config_address" cols="40" rows="5"><?php echo $config_address; ?></textarea>
								<?php if ($error_address) { ?>
									<span class="error"><?php echo $error_address; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_email; ?></td>
							<td><input type="text" name="config_email" value="<?php echo $config_email; ?>" size="40" />
								<?php if ($error_email) { ?>
									<span class="error"><?php echo $error_email; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required"></span>E-mail для отображения в контактах</td>
							<td><input type="text" name="config_display_email" value="<?php echo $config_display_email; ?>" size="40" />              
							</tr>
							<tr>
								<td><span class="required"></span>E-mail оптовый</td>
								<td><input type="text" name="config_opt_email" value="<?php echo $config_opt_email; ?>" size="40" />              
								</tr>
								<tr>
									<td><span class="required"></span>Подпись для смсок (макс. 11 симв.)</td>
									<td><input type="text" name="config_sms_sign" value="<?php echo $config_sms_sign; ?>" size="11" />              
									</tr>
									<tr>
										<td><span class="required"></span>Маска ввода телефонного номера</td>
										<td><input type="text" name="config_phonemask" value="<?php echo $config_phonemask; ?>" size="20" />              
										</tr>
										<tr>
											<td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
											<td>
												++Текст над:<input type="text" name="config_t_tt" value="<?php echo $config_t_tt; ?>" size="40" /><br /><br />
												Сам телефон:<input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" size="40" /><br /><br />			 
												++Текст под:<input type="text" name="config_t_bt" value="<?php echo $config_t_bt; ?>" size="40" /> 
												<?php if ($error_telephone) { ?>
													<span class="error"><?php echo $error_telephone; ?></span>
												<?php } ?></td>
										</tr>
										<tr>
											<td><span class="required"></span>Второй телефон</td>
											<td>
												++Текст над:<input type="text" name="config_t2_tt" value="<?php echo $config_t2_tt; ?>"  size="40"/><br /><br />
												Сам телефон:<input type="text" name="config_telephone2" value="<?php echo $config_telephone2; ?>" size="40" /><br /><br />				
												++Текст под:<input type="text" name="config_t2_bt" value="<?php echo $config_t2_bt; ?>" size="40" /> 
											</td>
										</tr> 
										<tr>
											<td><span class="required"></span>Третий телефон</td>
											<td>	
												Сам телефон:<input type="text" name="config_telephone3" value="<?php echo $config_telephone3; ?>" size="40" /><br /><br />				
											</td>
										</tr> 
										<tr>
											<td><span class="required"></span>Время работы</td>
											<td><input type="text" name="config_worktime" value="<?php echo $config_worktime; ?>"  style="width:400px;" />
											</td>
										</tr>
										<tr>
											<td>Оптовый телефон 1</td>
											<td>			
												<input type="text" name="config_opt_telephone" value="<?php echo $config_opt_telephone; ?>" size="40" />
											</td>
										</tr>
										<tr>
											<td>Оптовый телефон 2</td>
											<td>			
												<input type="text" name="config_opt_telephone2" value="<?php echo $config_opt_telephone2; ?>" size="40" />
											</td>
										</tr>
										<tr>
											<td><?php echo $entry_fax; ?></td>
											<td><input type="text" name="config_fax" value="<?php echo $config_fax; ?>" /></td>
										</tr>			 
									</table>
								</div>
								<div id="tab-store">
									<table class="form">
										
										<tr>
											<td>Использовать логику цен B2B (скидки на определенные группы товаров)</td>
											<td>
												<select name="config_group_price_enable">
													<?php if ($config_group_price_enable) { ?>
														<option value="1" selected="selected">Включить</option>
														<option value="0">Отключить</option>
														<?php } else { ?>													
														<option value="1">Включить</option>
														<option value="0"  selected="selected">Отключить</option>
													<? } ?>
												</select>
												<br />
												<span class="help">логика нагружает магазин, если реально это не используется, пусть будет отключено</span>
											</td>
										</tr>
										
										<tr>
											<td>Монобрендовый магазин!</td>
											<td>
												<select name="config_monobrand">
													<option value="0">Это не монобрендовый магазин</option>
													
													<? foreach ($manufacturers as $manufacturer) { ?>
														<?php if ($manufacturer['manufacturer_id'] == $config_monobrand) { ?>
															<option value="<?php echo $manufacturer['manufacturer_id'] ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
															<?php } else { ?>
															<option value="<?php echo $manufacturer['manufacturer_id'] ?>"><?php echo $manufacturer['name']; ?></option>
														<? } ?>
													<? } ?>
												</select>			  
											</td>
										</tr>
										<tr>
											<td>dadata.ru</td>
											<td>
												<select name="config_dadata">
													<option value="0" <?php if ($config_dadata == 0) { ?>selected="selected"<?php } ?>>Отключить вообще</option>
													<option value="city" <?php if ($config_dadata == 'city') { ?>selected="selected"<?php } ?>>Подбор города</option>
													<option value="address" <?php if ($config_dadata == 'address') { ?>selected="selected"<?php } ?>>Подбор адреса</option>
												</select>			  
											</td>
										</tr>
										<tr>
											<td>В брендах только товары</td>
											<td>
												<select type="select" name="config_show_goods_overload">
													<? if ($config_show_goods_overload) { ?>
														
														<option value="1" selected='selected' >Да</option>
														<option value="0" >Нет</option>
														
														<? } else { ?>
														
														<option value="1" >Да</option>
														<option value="0"  selected='selected' >Нет</option>
														
													<? } ?>       
												</select>	
											</td>
										</tr>

										<tr>
											<td>Отключить логику быстрого заказа</td>
											<td>
												<select type="select" name="config_disable_fast_orders">
													<? if ($config_disable_fast_orders) { ?>
														<option value="1" selected='selected' >Да</option>
														<option value="0" >Нет</option>
														<? } else { ?>
														<option value="1" >Да</option>
														<option value="0"  selected='selected' >Нет</option>
													<? } ?>       
												</select>	
												<br />
												<span class="help">Если включено, то невозможно оформить быстрый заказ на сайте</span>		
											</td>
										</tr>

										<tr>
											<td>Логика раздела "все скидки"</td>
											<td>
												<select name="config_special_controller_logic">
														<?php if ($config_special_controller_logic == 'default') { ?>
															<option value="default" selected="selected">По-умолчанию</option>
															<option value="category">Товары из категории</option>
														<?php } else { ?>													
															<option value="default">По-умолчанию</option>
															<option value="category"  selected="selected">Товары из категории</option>
														<? } ?>
													</select>

											</td>												
										</tr>
										<tr>
											<td>ID категории товаров со скидками</td>
											<td>											
												<input type="number" name="config_special_category_id" value="<?php echo $config_special_category_id; ?>" size="50" style="width:90px;" />
											</td>												
										</tr>
										<tr>
											<td><span class="required">*</span> <?php echo $entry_title; ?></td>
											<td><input type="text" name="config_title" value="<?php echo $config_title; ?>" size="200" />
												<?php if ($error_title) { ?>
													<span class="error"><?php echo $error_title; ?></span>
												<?php } ?></td>
										</tr>
										<tr>
											<td><?php echo $entry_meta_description; ?></td>
											<td><textarea name="config_meta_description" cols="40" rows="5"><?php echo $config_meta_description; ?></textarea></td>
										</tr>
										<tr>
											<td><?php echo $entry_template; ?></td>
											<td><select name="config_template">
												<?php foreach ($templates as $template) { ?>
													<?php if ($template == $config_template) { ?>
														<option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
														<?php } else { ?>
														<option value="<?php echo $template; ?>"><?php echo $template; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
										<tr>
											<td></td>
											<td id="template"></td>
										</tr>
										<tr>
											<td><?php echo $entry_layout; ?></td>
											<td><select name="config_layout_id">
												<?php foreach ($layouts as $layout) { ?>
													<?php if ($layout['layout_id'] == $config_layout_id) { ?>
														<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
										<tr>
											<td>Показывать меню слева</td>
											<td>
												<? if ($show_menu_in_left) { ?>
													<select type="select" name="show_menu_in_left">
														<option value="1" selected='selected' >Да, показывать меню слева</option>
														<option value="0" >Нет, показывать меню сверху</option>
													</select>
													
													<? } else { ?>
													
													<select name="show_menu_in_left">
														<option value="1" >Да, показывать меню слева</option>
														<option value="0"  selected='selected' >Нет, показывать меню сверху</option>
													</select>
													
												<? } ?>                         
											</td>
										</tr>
									</table>
								</div>
								
								<div id="tab-terms">
									<h2>Сроки поставки. Задаются через дефис, цифрами, 15-30, 4-7, 1-2</h2>
						<table class="form">
							<tr>
								<td style="width:33%">
									<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сроки поставки если есть в наличии в текущей стране</span></p>
									<input type="text" name="config_delivery_instock_term" value="<?php echo $config_delivery_instock_term; ?>" size="10" />
								</td>
	
								<td style="width:33%">
									<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сроки поставки если нету в наличии в текущей стране, но есть в Германии</span></p>
									<input type="text" name="config_delivery_central_term" value="<?php echo $config_delivery_central_term; ?>" size="10" />
								</td>
								
								<td style="width:33%">
									<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сроки поставки если есть в наличии на складе РФ</span></p>
									<input type="text" name="config_delivery_russia_term" value="<?php echo $config_delivery_russia_term; ?>" size="10" />
								</td>
							</tr>

							<tr>
								<td style="width:33%">
									<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сроки поставки если есть в наличии на складе в Украине</span></p>
									<input type="text" name="config_delivery_ukrainian_term" value="<?php echo $config_delivery_ukrainian_term; ?>" size="10" />
								</td>

								<td style="width:33%">
									<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Сроки поставки если есть в наличии на складе в Украине</span></p>
									<input type="text" name="config_delivery_outstock_term" value="<?php echo $config_delivery_outstock_term; ?>" size="10" />
								</td>
							</tr>

							<tr>
								<td style="width:33%">
									<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Отображать сроки, если нет в наличии на складе в стране</span></p>
									<select name="config_delivery_outstock_enable">
										<?php if ($config_delivery_outstock_enable) { ?>
											<option value="1" selected="selected">Включить отображение информации о сроках</option>
											<option value="0">Отключить отображение информации о сроках</option>
										<?php } else { ?>													
											<option value="1">Включить отображение информации о сроках</option>
											<option value="0"  selected="selected">Отключить отображение информации о сроках</option>
										<? } ?>
									</select>
								</td>

								<td style="width:33%">
									<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Логика подсчёта сроков в карте товара</span></p>
									<select name="config_delivery_display_logic">
										<?php if ($config_delivery_display_logic == 'v1') { ?>
											<option value="v1" selected="selected">Логика v1, без блоков, разделять отправку и доставку</option>
											<option value="v2"></option>
										<?php } else { ?>													
											<option value="v1">Логика v1, без блоков, разделять отправку и доставку</option>
											<option value="v2"  selected="selected">Логика v2, блоками, даты в заголовке, не разделять отправку и доставку</option>
										<? } ?>
									</select>
								</td>
							</tr>
						</table>		
									
									<h2>Идентификаторы складов</h2>
									
									<table class="form">
							<tr>
								<td width="25%">
									<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Идентификатор склада</span></p>
									<input type="text" name="config_warehouse_identifier" value="<?php echo $config_warehouse_identifier; ?>" size="30" />
									<br />
									<span class="help">идентификатор склада, с которого выполняется отправка в эту страну (например, для КЗ и Белки - это РФ), это обычная логика обработки наличия</span>
								</td>

								<td width="25%">
									<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Идентификатор реального склада</span></p>
									<input type="text" name="config_warehouse_identifier_local" value="<?php echo $config_warehouse_identifier_local; ?>" size="30" />
									<br />
									<span class="help">идентификатор локального склада, для совместимости с логикой вычисления сроков поставки</span>
								</td>

								<td width="25%">
									<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Работать только со складом</span></p>
									<select name="config_warehouse_only">
													<?php if ($config_warehouse_only) { ?>
														<option value="1" selected="selected">Включить</option>
														<option value="0">Отключить</option>
														<?php } else { ?>													
														<option value="1">Включить</option>
														<option value="0"  selected="selected">Отключить</option>
													<? } ?>
												</select>
									<br />
									<span class="help">всё чего нет на складе - на фронте отдается в ноль</span>
								</td>

								<td width="25%">
									<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Статус если нет на складе</span></p>
									<select name="config_overload_stock_status_id">
										<?php foreach ($stock_statuses as $stock_status) { ?>
											<?php if ($stock_status['stock_status_id'] == $config_overload_stock_status_id) { ?>
												<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
									<br />
									<span class="help">всё чего нет на складе - отдается этот статус</span>
								</td>
							</tr>
							
						</table>
									
									<h2>Самовывоз</h2>
									
									<table class="form">
										<tr>
											<td>Возможность самовывоза</td>
											<td>
												<select name="config_pickup_enable">
													<?php if ($config_pickup_enable) { ?>
														<option value="1" selected="selected">Включить</option>
														<option value="0">Отключить</option>
														<?php } else { ?>													
														<option value="1">Включить</option>
														<option value="0"  selected="selected">Отключить</option>
													<? } ?>
												</select>
											</td>
										</tr>
										
										<tr>
											<td>Время работы пункта самовывоза</td>
											<td>
												<input type="text" name="config_pickup_times" value="<?php echo $config_pickup_times; ?>" size="50" />
												<br />
												<span class="help">Формат: 10:19;10:19;10:19;10:19;10:19;false;false;</span>
											</td>
										</tr>
										
									</table>
									<?php setlocale(LC_ALL, 'ru_RU', 'ru_RU.UTF-8', 'ru', 'russian'); ?>
									<?php $monthes = array(
										1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель',
										5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август',
										9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'
									); ?>
									<?php 
										$colors = array(
										1 => '#7F00FF', 
										2 => '#7F00FF', 
										3 => '#00ad07', 
										4 => '#00ad07',
										5 => '#00ad07', 
										6 => '#cf4a61', 
										7 => '#cf4a61', 
										8 => '#cf4a61',
										9 => '#ff7815', 
										10 => '#ff7815', 
										11 => '#ff7815', 
										12 => '#7F00FF'
										);
									?>
									<h2>Выходные дни самовывоза в этом и следующем годах, <span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">если доживем</span>. 
										Сейчас <span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF"><?php echo $monthes[date('n')]; ?> <?php echo date('Y'); ?></span>. 
										Следующий <span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><?php echo $monthes[date('n', strtotime('+1 month'))]; ?> <?php echo date('Y', strtotime('+1 month')); ?></span></h2>
									<table class="form">
										<tr>
											<?php for ($i=1; $i<=12; $i++) { ?>
												<td width="8%" style="width:8%" class="text-left">
													<span class="status_color" style="display:inline-block; padding:3px 5px; background:<?php echo $colors[$i]; ?>; color:#FFF">
													<?php echo $i; ?></span>
													
													<span class="status_color" style="display:inline-block; padding:3px 5px; background:<?php echo $colors[$i]; ?>; color:#FFF">
													<?php echo $monthes[$i]; ?></span>
													
													<br />
													<textarea rows="10" cols="8" name="config_pickup_dayoff_<?php echo $i; ?>"><?php echo ${'config_pickup_dayoff_' . $i};?></textarea>
													
													<?php if (date('n') == $i) { ?>
														<br /><span class="status_color" style="display:inline-block; padding:3px 5px; background:#00ad07; color:#FFF">
															Текущий
														</span>
													<?php } ?>
													
													<?php if (date('n', strtotime('+1 month')) == $i) { ?>
														<br /><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF">
															Следующий
														</span>
													<?php } ?>
													
													<?php if (date('n', strtotime('+1 month')) != $i && date('n') != $i && date('n') > $i) { ?>
														<br />
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><?php echo date('Y', strtotime('+1 year')); ?>
														</span>
													<? } ?>
													
													<?php if (date('n', strtotime('+1 month')) != $i && date('n') != $i && date('n') < $i) { ?>
														<br />
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo date('Y'); ?>
														</span>
													<? } ?>
												</td>												
												<?php  if ($i==100) { ?></tr><tr><? } ?>
											<?php } ?>
										</tr>
									</table>
								</div>
								
								
								<div id="tab-app">
									<h2>Google Play Store</h2>
									<table class="form">
									<tr>
									<td>Включить линк на GPS</td>
									<td>
									<select name="config_android_playstore_enable">
										<?php if ($config_android_playstore_enable) { ?>
											<option value="1" selected="selected">Включить</option>
											<option value="0">Отключить</option>
											<?php } else { ?>													
											<option value="1">Включить</option>
											<option value="0"  selected="selected">Отключить</option>
										<? } ?>
									</select>
									</td>
									</tr>
									
									<tr>
										<td>Google Play Store ID</td>
										<td>
											<input type="text" name="config_android_playstore_code" value="<?php echo $config_android_playstore_code; ?>" size="20" />
											<br />
											<span class="help">ua.com.kitchenprofi.twa</span>
										</td>
									</tr>
									
									<tr>
										<td>Ссылка на Google Play Store</td>
										<td>
											<input type="text" name="config_android_playstore_link" value="<?php echo $config_android_playstore_link; ?>" size="50" />
											<br />
											<span class="help">https://play.google.com/store/apps/details?id=ua.com.kitchenprofi.twa</span>
										</td>
									</tr>
									
									<tr>
										<td>Код FireBase (FCM)</td>
										<td><textarea name="config_firebase_code" cols="50" rows="10"><?php echo $config_firebase_code; ?></textarea></td>
									</tr>
									
								</table>	
								
								<h2>Microsoft Store</h2>
								<table class="form">
									<tr>
										<td>Включить линк на MSS</td>
										<td>
											<select name="config_microsoft_store_enable">
												<?php if ($config_microsoft_store_enable) { ?>
													<option value="1" selected="selected">Включить</option>
													<option value="0">Отключить</option>
													<?php } else { ?>													
													<option value="1">Включить</option>
													<option value="0"  selected="selected">Отключить</option>
												<? } ?>
											</select>
										</td>
									</tr>
									
									<tr>
										<td>Microsoft Store ID</td>
										<td>
											<input type="text" name="config_microsoft_store_code" value="<?php echo $config_microsoft_store_code; ?>" size="20" />
											<br />
											<span class="help"></span>
										</td>
									</tr>
									
									<tr>
										<td>Ссылка на Microsoft Store</td>
										<td>
											<input type="text" name="config_microsoft_store_link" value="<?php echo $config_microsoft_store_link; ?>" size="50" />
											<br />
											<span class="help"></span>
										</td>
									</tr>
								</table>	
								</div>
								
								<div id="tab-local">
									<table class="form">
										
										<tr>
											<td><?php echo $entry_country; ?></td>
											<td><select name="config_country_id">
												<?php foreach ($countries as $country) { ?>
													<?php if ($country['country_id'] == $config_country_id) { ?>
														<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
										
										<tr>
											<td>Идентификатор для Google Merchant Local</td>
											<td>
												<input type="text" name="config_googlelocal_code" value="<?php echo $config_googlelocal_code; ?>" size="6" />
												<span class="help">Шесть случайных цифр. При изменении сообщить @rayua</span>
											</td>
										</tr>
										
										
										<tr>
											<td>Название страны</td>
											<td><input type="text" name="config_countryname" value="<?php echo $config_countryname; ?>" size="30" />
											</td>
										</tr>
										<tr>
											<td><span class="required"></span>Дефолтный город (столица)</td>
											<td>
												<input type="text" name="config_default_city" value="<?php echo $config_default_city; ?>" size="20" />  
											 </td>            
										</tr>
										
										<?php foreach ($languages as $language) { ?>
											<tr>
												<td><p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#D69241; color:#FFF">Дефолтный город (столица) <?php echo $language['code']; ?></span></p></td>
												<td>										
													<input type="text" name="config_default_city_<?php echo $language['code']; ?>" value="<?php echo ${'config_default_city_' . $language['code']}; ?>" size="20" />										
												</td>
											</tr>													
										<?php } ?>

										<tr>
											<td>Активные способы оплаты<br /><span class="help">выводятся в карте товара, по одному в строке</span></td>
											<td><textarea name="config_payment_list" cols="40" rows="8"><?php echo $config_payment_list; ?></textarea></td>
										</tr>
										<tr>
											<td><?php echo $entry_zone; ?></td>
											<td>
											<select name="config_zone_id">
												<?php foreach ($zones as $zone) { ?>
										<?php if ($zone['zone_id'] == $config_zone_id) { ?>
											<option value="<?php echo $zone['zone_id']; ?>" selected="selected"><?php echo $zone['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $zone['zone_id']; ?>"><?php echo $zone['name']; ?></option>
										<?php } ?>
									<?php } ?>
											</select>
											</td>
										</tr>
										<tr>
											<td><?php echo $entry_language; ?></td>
											<td><select name="config_language">
												<?php foreach ($languages as $language) { ?>
													<?php if ($language['code'] == $config_language) { ?>
														<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
										
										<tr>
											<td>Второй язык фронта</td>
											<td><select name="config_second_language">
												<option value="" <?php if ($config_second_language == '') { ?>selected="selected"<?php } ?>>Не использовать</option>
												<?php foreach ($languages as $language) { ?>
													<?php if ($language['code'] == $config_second_language) { ?>
														<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>

										<tr>
											<td>Делать редирект на второй язык</td>
											<td>
												<select name="config_do_redirection_to_second_language">
													<?php if ($config_do_redirection_to_second_language) { ?>
														<option value="1" selected="selected">Включить</option>
														<option value="0">Отключить</option>
														<?php } else { ?>													
														<option value="1">Включить</option>
														<option value="0"  selected="selected">Отключить</option>
													<? } ?>
												</select>
												<br />
												<span class="help">Переадресовывать клиентов на второй язык (законы Украины о обязательном Украинском языке)</span>
											</td>
										</tr>
										
										<tr>
											<td><?php echo $entry_currency; ?></td>
											<td><select name="config_currency">
												<?php foreach ($currencies as $currency) { ?>
													<?php if ($currency['code'] == $config_currency) { ?>
														<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
										<tr>
											<td>Валюта отображения<br />
												<span class="help">Валюта, в которой отображаются цены в региональном магазине</span>
											</td>
											<td><select name="config_regional_currency">
												<?php foreach ($currencies as $currency) { ?>
													<?php if ($currency['code'] == $config_regional_currency) { ?>
														<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
									</table>
								</div>
								<div id="tab-option">		
									<h2>Бонусная система</h2>
									
									<table class="form">								
										<tr>
											<td>Время жизни бонусов, дней</td>
											<td>
												<input type="text" name="config_reward_lifetime" value="<?php echo $config_reward_lifetime; ?>" size="5" /> дней
											</td>
										</tr>
										
										<tr>
											<td>Максимальный процент</td>
											<td>
												<input type="text" name="config_reward_maxsalepercent" value="<?php echo $config_reward_maxsalepercent; ?>" size="5" />%
												<span class="help">такое количество процентов от суммы заказа можно оплатить бонусами</span>
											</td>
										</tr>
										
										
										<tr>
											<td><b>Процент начисления бонусов</b></td>
											<td>
												<input type="text" name="rewardpoints_pointspercent" value="<?php echo $rewardpoints_pointspercent; ?>" size="5" />
												<span class="help">значение по умолчанию, которое будет назначаться товарам, если не переназначено далее, в категория, брендах и коллекциях</span>
											</td>
										</tr>
										
										<tr>
											<td>SVG иконки бонусов</td>
											<td>
												<input type="text" name="config_reward_logosvg" value="<?php echo $config_reward_logosvg; ?>" size="30" />
												<span class="help">/catalog/view/theme/default/img/money.svg</span>
											</td>
										</tr>
										
									</table>
									
									
									<table class="form">	
										<h2>Начисление бонусов</h2>
										
										<tr>
											<td><b>Бонусов за установку приложения</b></td>
											<td>
												<input type="text" name="rewardpoints_appinstall" value="<?php echo $rewardpoints_appinstall; ?>" size="5" />
												<span class="help">количество бонусов в национальной валюте, начисляемое при установке приложения, код APPINSTALL_POINTS_ADD,бонусы с этим кодом могут быть начислены только один раз одному покупателю</span>
											</td>
										</tr>
										
										<tr>
											<td><b>Бонусов на день рождения</b></td>
											<td>
												<input type="text" name="rewardpoints_birthday" value="<?php echo $rewardpoints_birthday; ?>" size="5" />
												<span class="help">количество бонусов в национальной валюте, начисляемое на день рождения, код BIRTHDAY_POINTS_ADD, бонусы с этим кодом могут быть начислены не чаще чем раз в 365 дней</span>
											</td>
										</tr>
									</table>
									
									<h2>Настройки</h2>
									<table class="form">
										<tr>
											<td><span class="required">*</span> <?php echo $entry_catalog_limit; ?></td>
											<td><input type="text" name="config_catalog_limit" value="<?php echo $config_catalog_limit; ?>" size="3" />
												<?php if ($error_catalog_limit) { ?>
													<span class="error"><?php echo $error_catalog_limit; ?></span>
												<?php } ?></td>
										</tr>
										<tr>
											<td>Идентификатор группы атрибутов "особенности"</td>
											<td><input type="text" name="config_special_attr_id" value="<?php echo $config_special_attr_id; ?>" size="3" /></td>
										</tr>
									</table>
									<h2><?php echo $text_tax; ?></h2>
									<table class="form">
										<tr>
											<td><?php echo $entry_tax; ?></td>
											<td><?php if ($config_tax) { ?>
												<input type="radio" name="config_tax" value="1" checked="checked" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_tax" value="0" />
												<?php echo $text_no; ?>
												<?php } else { ?>
												<input type="radio" name="config_tax" value="1" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_tax" value="0" checked="checked" />
												<?php echo $text_no; ?>
											<?php } ?></td>
										</tr>
										<tr>
											<td><?php echo $entry_tax_default; ?></td>
											<td><select name="config_tax_default">
												<option value=""><?php echo $text_none; ?></option>
												<?php  if ($config_tax_default == 'shipping') { ?>
													<option value="shipping" selected="selected"><?php echo $text_shipping; ?></option>
													<?php } else { ?>
													<option value="shipping"><?php echo $text_shipping; ?></option>
												<?php } ?>
												<?php  if ($config_tax_default == 'payment') { ?>
													<option value="payment" selected="selected"><?php echo $text_payment; ?></option>
													<?php } else { ?>
													<option value="payment"><?php echo $text_payment; ?></option>
												<?php } ?>
											</select></td>
										</tr>
										<tr>
											<td><?php echo $entry_tax_customer; ?></td>
											<td><select name="config_tax_customer">
												<option value=""><?php echo $text_none; ?></option>
												<?php  if ($config_tax_customer == 'shipping') { ?>
													<option value="shipping" selected="selected"><?php echo $text_shipping; ?></option>
													<?php } else { ?>
													<option value="shipping"><?php echo $text_shipping; ?></option>
												<?php } ?>
												<?php  if ($config_tax_customer == 'payment') { ?>
													<option value="payment" selected="selected"><?php echo $text_payment; ?></option>
													<?php } else { ?>
													<option value="payment"><?php echo $text_payment; ?></option>
												<?php } ?>
											</select></td>
										</tr>
									</table>
									<h2><?php echo $text_account; ?></h2>
									<table class="form">
										<tr>
											<td><?php echo $entry_customer_group; ?></td>
											<td><select name="config_customer_group_id">
												<?php foreach ($customer_groups as $customer_group) { ?>
													<?php if ($customer_group['customer_group_id'] == $config_customer_group_id) { ?>
														<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
										<tr>
											<td>Группа, в которую попадают оптовики при регистрации</td>
											<td><select name="config_opt_group_id">
												<?php foreach ($customer_groups as $customer_group) { ?>
													<?php if ($customer_group['customer_group_id'] == $config_opt_group_id) { ?>
														<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
										<tr>
											<td><?php echo $entry_customer_group_display; ?></td>
											<td><div class="scrollbox">
												<?php $class = 'odd'; ?>
												<?php foreach ($customer_groups as $customer_group) { ?>
													<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
													<div class="<?php echo $class; ?>">
														<?php if (in_array($customer_group['customer_group_id'], $config_customer_group_display)) { ?>
															<input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
															<?php echo $customer_group['name']; ?>
															<?php } else { ?>
															<input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
															<?php echo $customer_group['name']; ?>
														<?php } ?>
													</div>
												<?php } ?>
											</div>
											<?php if ($error_customer_group_display) { ?>
												<span class="error"><?php echo $error_customer_group_display; ?></span>
											<?php } ?></td>
										</tr>            
										<tr>
											<td><?php echo $entry_customer_price; ?></td>
											<td><?php if ($config_customer_price) { ?>
												<input type="radio" name="config_customer_price" value="1" checked="checked" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_customer_price" value="0" />
												<?php echo $text_no; ?>
												<?php } else { ?>
												<input type="radio" name="config_customer_price" value="1" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_customer_price" value="0" checked="checked" />
												<?php echo $text_no; ?>
											<?php } ?></td>
										</tr>
										<tr>
											<td><?php echo $entry_account; ?></td>
											<td><select name="config_account_id">
												<option value="0"><?php echo $text_none; ?></option>
												<?php foreach ($informations as $information) { ?>
													<?php if ($information['information_id'] == $config_account_id) { ?>
														<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
									</table>
									<h2><?php echo $text_checkout; ?></h2>
									<table class="form">
										<tr>
											<td><?php echo $entry_cart_weight; ?></td>
											<td><?php if ($config_cart_weight) { ?>
												<input type="radio" name="config_cart_weight" value="1" checked="checked" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_cart_weight" value="0" />
												<?php echo $text_no; ?>
												<?php } else { ?>
												<input type="radio" name="config_cart_weight" value="1" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_cart_weight" value="0" checked="checked" />
												<?php echo $text_no; ?>
											<?php } ?></td>
										</tr>
										<tr>
											<td><?php echo $entry_guest_checkout; ?></td>
											<td><?php if ($config_guest_checkout) { ?>
												<input type="radio" name="config_guest_checkout" value="1" checked="checked" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_guest_checkout" value="0" />
												<?php echo $text_no; ?>
												<?php } else { ?>
												<input type="radio" name="config_guest_checkout" value="1" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_guest_checkout" value="0" checked="checked" />
												<?php echo $text_no; ?>
											<?php } ?></td>
										</tr>
										<tr>
											<td><?php echo $entry_checkout; ?></td>
											<td><select name="config_checkout_id">
												<option value="0"><?php echo $text_none; ?></option>
												<?php foreach ($informations as $information) { ?>
													<?php if ($information['information_id'] == $config_checkout_id) { ?>
														<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
										
										
										<tr>
											<td>Идентификаторы ОПЛАТЫ ПРИ ДОСТАВКЕ<span class="help">Идентификаторы способов оплат, при которых считается, что оплата будет произведена при доставке</span></td>
											<td><input name="config_confirmed_delivery_payment_ids" type="text" style="width:700px;" value="<? echo $config_confirmed_delivery_payment_ids; ?>"></td>
										</tr>
										
									</table>
									<h2><?php echo $text_stock; ?></h2>
									<table class="form">
										<tr>
											<td><?php echo $entry_stock_display; ?></td>
											<td><?php if ($config_stock_display) { ?>
												<input type="radio" name="config_stock_display" value="1" checked="checked" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_stock_display" value="0" />
												<?php echo $text_no; ?>
												<?php } else { ?>
												<input type="radio" name="config_stock_display" value="1" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_stock_display" value="0" checked="checked" />
												<?php echo $text_no; ?>
											<?php } ?></td>
										</tr>
										<tr>
											<td><?php echo $entry_stock_checkout; ?></td>
											<td><?php if ($config_stock_checkout) { ?>
												<input type="radio" name="config_stock_checkout" value="1" checked="checked" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_stock_checkout" value="0" />
												<?php echo $text_no; ?>
												<?php } else { ?>
												<input type="radio" name="config_stock_checkout" value="1" />
												<?php echo $text_yes; ?>
												<input type="radio" name="config_stock_checkout" value="0" checked="checked" />
												<?php echo $text_no; ?>
											<?php } ?></td>
										</tr>
									</table>
								</div>
								<div id="tab-image">
									<table class="form">
										<tr>
											<td><?php echo $entry_logo; ?></td>
											<td><div class="image"><img src="<?php echo $logo; ?>" alt="" id="thumb-logo" />
												<input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="logo" />
												<br />
											<a onclick="image_upload('logo', 'thumb-logo');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-logo').attr('src', '<?php echo $no_image; ?>'); $('#logo').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
										</tr>
										<tr>
											<td><?php echo $entry_icon; ?></td>
											<td><div class="image"><img src="<?php echo $icon; ?>" alt="" id="thumb-icon" />
												<input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="icon" />
												<br />
											<a onclick="image_upload('icon', 'thumb-icon');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-icon').attr('src', '<?php echo $no_image; ?>'); $('#icon').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
										</tr>
										<tr>
											<td>Заглушка</td>
											<td><div class="image"><img src="<?php echo $noimage; ?>" alt="" id="thumb-noimage" />
												<input type="hidden" name="config_noimage" value="<?php echo $config_noimage; ?>" id="noimage" />
												<br />
											<a onclick="image_upload('noimage', 'thumb-noimage');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-noimage').attr('src', '<?php echo $no_image; ?>'); $('#noimage').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
										</tr>
										<tr>
											<td><span class="required">*</span> <?php echo $entry_image_category; ?></td>
											<td><input type="text" name="config_image_category_width" value="<?php echo $config_image_category_width; ?>" size="3" />
												x
												<input type="text" name="config_image_category_height" value="<?php echo $config_image_category_height; ?>" size="3" />
												<?php if ($error_image_category) { ?>
													<span class="error"><?php echo $error_image_category; ?></span>
												<?php } ?></td>
										</tr>
										<tr>
											<td><span class="required">*</span> <?php echo $entry_image_thumb; ?></td>
											<td><input type="text" name="config_image_thumb_width" value="<?php echo $config_image_thumb_width; ?>" size="3" />
												x
												<input type="text" name="config_image_thumb_height" value="<?php echo $config_image_thumb_height; ?>" size="3" />
												<?php if ($error_image_thumb) { ?>
													<span class="error"><?php echo $error_image_thumb; ?></span>
												<?php } ?></td>
										</tr>
										<tr>
											<td><span class="required">*</span> <?php echo $entry_image_popup; ?></td>
											<td><input type="text" name="config_image_popup_width" value="<?php echo $config_image_popup_width; ?>" size="3" />
												x
												<input type="text" name="config_image_popup_height" value="<?php echo $config_image_popup_height; ?>" size="3" />
												<?php if ($error_image_popup) { ?>
													<span class="error"><?php echo $error_image_popup; ?></span>
												<?php } ?></td>
										</tr>
										<tr>
											<td><span class="required">*</span> <?php echo $entry_image_product; ?></td>
											<td><input type="text" name="config_image_product_width" value="<?php echo $config_image_product_width; ?>" size="3" />
												x
												<input type="text" name="config_image_product_height" value="<?php echo $config_image_product_height; ?>" size="3" />
												<?php if ($error_image_product) { ?>
													<span class="error"><?php echo $error_image_product; ?></span>
												<?php } ?></td>
										</tr>
										<tr>
											<td><span class="required">*</span> <?php echo $entry_image_additional; ?></td>
											<td><input type="text" name="config_image_additional_width" value="<?php echo $config_image_additional_width; ?>" size="3" />
												x
												<input type="text" name="config_image_additional_height" value="<?php echo $config_image_additional_height; ?>" size="3" />
												<?php if ($error_image_additional) { ?>
													<span class="error"><?php echo $error_image_additional; ?></span>
												<?php } ?></td>
										</tr>
										<tr>
											<td><span class="required">*</span> <?php echo $entry_image_related; ?></td>
											<td><input type="text" name="config_image_related_width" value="<?php echo $config_image_related_width; ?>" size="3" />
												x
												<input type="text" name="config_image_related_height" value="<?php echo $config_image_related_height; ?>" size="3" />
												<?php if ($error_image_related) { ?>
													<span class="error"><?php echo $error_image_related; ?></span>
												<?php } ?></td>
										</tr>
										<tr>
											<td><span class="required">*</span> <?php echo $entry_image_compare; ?></td>
											<td><input type="text" name="config_image_compare_width" value="<?php echo $config_image_compare_width; ?>" size="3" />
												x
												<input type="text" name="config_image_compare_height" value="<?php echo $config_image_compare_height; ?>" size="3" />
												<?php if ($error_image_compare) { ?>
													<span class="error"><?php echo $error_image_compare; ?></span>
												<?php } ?></td>
										</tr>
										<tr>
											<td><span class="required">*</span> <?php echo $entry_image_wishlist; ?></td>
											<td><input type="text" name="config_image_wishlist_width" value="<?php echo $config_image_wishlist_width; ?>" size="3" />
												x
												<input type="text" name="config_image_wishlist_height" value="<?php echo $config_image_wishlist_height; ?>" size="3" />
												<?php if ($error_image_wishlist) { ?>
													<span class="error"><?php echo $error_image_wishlist; ?></span>
												<?php } ?></td>
										</tr>
										<tr>
											<td><span class="required">*</span> <?php echo $entry_image_cart; ?></td>
											<td><input type="text" name="config_image_cart_width" value="<?php echo $config_image_cart_width; ?>" size="3" />
												x
												<input type="text" name="config_image_cart_height" value="<?php echo $config_image_cart_height; ?>" size="3" />
												<?php if ($error_image_cart) { ?>
													<span class="error"><?php echo $error_image_cart; ?></span>
												<?php } ?></td>
										</tr>
									</table>
								</div>		

								<div id="tab-sms">
							
							<h2>Уведомления клиента</h2>
							<table class="form">
								<tr>									
									<td style="width:33%">
										<div>
											<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Уведомлять клиента о заказе</span></p>

											<select name="config_sms_send_new_order">
												<?php if ($config_sms_send_new_order) { ?>
													<option value="1" selected="selected">Включить</option>
													<option value="0">Отключить</option>
												<?php } else { ?>													
													<option value="1">Включить</option>
													<option value="0"  selected="selected">Отключить</option>
												<? } ?>
											</select>
										</div>

										<div>
											<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Уведомлять клиента о смене статуса</span></p>

											<select name="config_sms_send_new_order_status">
												<?php if ($config_sms_send_new_order_status) { ?>
													<option value="1" selected="selected">Включить</option>
													<option value="0">Отключить</option>
												<?php } else { ?>													
													<option value="1">Включить</option>
													<option value="0"  selected="selected">Отключить</option>
												<? } ?>
											</select>
										</div>
									</td>


									<td style="width:25%" class="left">
										<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Текст SMS о новом заказе</span></p>
										<textarea name="config_sms_new_order_message" cols="40" rows="5"><?php echo $config_sms_new_order_message; ?></textarea>
									</td>

									<td style="width:33%" class="left">
										<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Шаблон нового</span></p>
										<span class="help">											
											<b>{SNAME}</b> - название магазина<br />
											<b>{ID}</b> - номер заказа<br />
											<b>{DATE}</b> - дата заказа<br />
											<b>{TIME}</b> - время заказа<br />
											<b>{SUM}</b> - сумма заказа<br />
											<b>{STATUS}</b> - новый статус заказа<br />
											<b>{PHONE}</b> - телефон клиента<br />
											<b>{FIRSTNAME}</b> - имя клиента<br />
											<b>{LASTNAME}</b> - фамилия клиента<br />
											<b>{TTN}</b> - ТТН службы доставки<br />
											<b>{DELIVERY_SERVICE}</b> - Служба доставки
										</span>
									</td>
								</tr>	
								</table>

								<table class="list">
									<?php foreach ($order_statuses as $order_status) { ?>
										<?php $status_message = '';
										if (isset($config_sms_new_order_status_message[$order_status['order_status_id']])) {
											$status_message = $config_sms_new_order_status_message[$order_status['order_status_id']];
										} ?>
										<tr>
											<td style="width:200px;">
												<span class="status_color" style="text-align: left; background: #<?php echo !empty($order_status['status_bg_color']) ? $order_status['status_bg_color'] : ''; ?>; color: #<?php echo !empty($order_status['status_txt_color']) ? $order_status['status_txt_color'] : ''; ?>;">
													<?php echo $order_status['name']; ?>
												</span>
											</td>
											<td style="width:50px" class="center">
												<input data-key="config_sms_new_order_status_message" data-id="<?php echo $order_status['order_status_id']; ?>" data-name="enabled" class="checkbox" type="checkbox" name="config_sms_new_order_status_message[<?php echo $order_status['order_status_id']; ?>][enabled]" id="config_sms_new_order_status_message[<?php echo $order_status['order_status_id']; ?>][enabled]" <?php if (isset($status_message['enabled']) && $status_message['enabled']) { echo ' checked="checked"'; }?>/>

												<label for="config_sms_new_order_status_message[<?php echo $order_status['order_status_id']; ?>][enabled]"></label>

											</td>
											<td style="padding:5px;">
												<input data-key="config_sms_new_order_status_message" data-id="<?php echo $order_status['order_status_id']; ?>" data-name="message" type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_new_order_status_message[<?php echo $order_status['order_status_id']; ?>][message]" value="<?php echo isset($status_message['message']) ? $status_message['message'] : ""; ?>" />
											</td>
										</tr>										
									<?php } ?>
									<tr>
										<td style="width:200px;">
											<span class="status_color" style="text-align: left; background: #43B02A; color: #FFF; ?>;">
												Трекинг отправки со склада
											</span>
										</td>
										<td style="width:50px" class="center">
											<input class="checkbox" type="checkbox" name="config_sms_tracker_leave_main_warehouse_enabled" id="config_sms_tracker_leave_main_warehouse_enabled"<?php if ($config_sms_tracker_leave_main_warehouse_enabled) { echo ' checked="checked"'; }?>/><label for="config_sms_tracker_leave_main_warehouse_enabled"></label>
										</td>
										<td style="padding:5px;">
											<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_tracker_leave_main_warehouse" value="<?php echo $config_sms_tracker_leave_main_warehouse; ?>" />
										</td>
									</tr>

									<tr>
										<td style="width:200px;">
											<span class="status_color" style="text-align: left; background: #000; color: #FFF; ?>;">
												Успешная оплата
											</span>
										</td>
										<td style="width:50px" class="center">
											<input class="checkbox" type="checkbox" name="config_sms_payment_recieved_enabled" id="config_sms_payment_recieved_enabled"<?php if ($config_sms_payment_recieved_enabled) { echo ' checked="checked"'; }?>/><label for="config_sms_payment_recieved_enabled"></label>
										</td>
										<td style="padding:5px;">
											<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_payment_recieved" value="<?php echo $config_sms_payment_recieved; ?>" />
										</td>
									</tr>

									<tr>
										<td style="width:200px;">
											<span class="status_color" style="text-align: left; background: #ef5e67; color: #FFF; ?>;">
												ТТН службы доставки: отправка
											</span>
										</td>
										<td style="width:50px" class="center">
											<input class="checkbox" type="checkbox" name="config_sms_ttn_sent_enabled" id="config_sms_ttn_sent_enabled"<?php if ($config_sms_ttn_sent_enabled) { echo ' checked="checked"'; }?>/><label for="config_sms_ttn_sent_enabled"></label>
										</td>
										<td style="padding:5px;">
											<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_ttn_sent" value="<?php echo $config_sms_ttn_sent; ?>" />
										</td>
									</tr>

									<tr>
										<td style="width:200px;">
											<span class="status_color" style="text-align: left; background: #43B02A; color: #FFF; ?>;">
												ТТН службы доставки: доставлено
											</span>
										</td>
										<td style="width:50px" class="center">
											<input class="checkbox" type="checkbox" name="config_sms_ttn_ready_enabled" id="config_sms_ttn_ready_enabled"<?php if ($config_sms_ttn_ready_enabled) { echo ' checked="checked"'; }?>/><label for="config_sms_ttn_ready_enabled"></label>
										</td>
										<td style="padding:5px;">
											<input type="text" size="200" style="width:90%; font-size:16px; padding:5px;" name="config_sms_ttn_ready" value="<?php echo $config_sms_ttn_ready; ?>" />
										</td>
									</tr>
								</table>
							</div>
								<div id="tab-server">
									<table class="form">
										<tr>
											<td>
												<p>Preload Links (от статик-субдомена)</p></td>
												<td>
												<textarea name="config_preload_links" cols="100" rows="10"><?php echo $config_preload_links; ?></textarea>
											</td>
										</tr>
										<tr>
											<td><?php echo $entry_secure; ?></td>
											<td>
												<select name="config_secure">
													<?php if ($config_secure) { ?>
														<option value="1" selected="selected">Включить</option>
														<option value="0">Отключить</option>
													<?php } else { ?>													
														<option value="1">Включить</option>
														<option value="0"  selected="selected">Отключить</option>
													<? } ?>
												</select>
											</td>
										</tr>
										<tr>
											<td>SEO URL</td>
											<td>
												<select name="config_seo_url">
													<?php if ($config_seo_url) { ?>
														<option value="1" selected="selected">Включить</option>
														<option value="0">Отключить</option>
													<?php } else { ?>													
														<option value="1">Включить</option>
														<option value="0"  selected="selected">Отключить</option>
													<? } ?>
												</select>
											</td>
										</tr>
										<tr>
											<td><?php echo $entry_seo_url_type; ?></td>
											<td><select name="config_seo_url_type">
												<?php foreach ($seo_types as $seo_type) { ?>
													<?php if ($seo_type['type'] == $config_seo_url_type) { ?>
														<option value="<?php echo $seo_type['type']; ?>" selected="selected"><?php echo $seo_type['name']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $seo_type['type']; ?>"><?php echo $seo_type['name']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
										<tr>
											<td><?php echo $entry_seo_url_include_path; ?></td>
											<td>
												<select name="config_seo_url_include_path">
													<?php if ($config_seo_url_include_path) { ?>
														<option value="1" selected="selected">Включить</option>
														<option value="0">Отключить</option>
													<?php } else { ?>													
														<option value="1">Включить</option>
														<option value="0"  selected="selected">Отключить</option>
													<? } ?>
												</select>
											</td>
										</tr>
										<tr>
											<td><?php echo $entry_seo_url_postfix; ?></td>
											<td><input type="text" name="config_seo_url_postfix" value="<?php echo $config_seo_url_postfix; ?>" size="3" /></td>
										</tr>											
									</table>
								</div>
								
								<div id="tab-telephony">

									<h2>Очереди</h2>
									<table class="form">
										<tr>
											<td>
												<p>Обслуживающая очередь</p>
												
											</td>
											
											<td>
												<input type="text" name="config_default_queue" value="<?php echo $config_default_queue; ?>" size="30" style="width:150px;" />
												
												<br /><span class="help">Сейчас у нас есть следующие очереди: 901 - Русскоязычная, 501 - Украиноязычная</span>
											</td>
										</tr>
										<tr>
											<td>
												<p>Очередь уведомлений менеджеров</p>														
											</td>
											
											<td>
												<input type="text" name="config_default_alert_queue" value="<?php echo $config_default_alert_queue; ?>" size="30" style="width:150px;" />
												
												<br /><span class="help">Сейчас у нас есть следующие очереди: sales - Русскоязычная, sales_ua - Украиноязычная</span>
											</td>
										</tr>
										<tr>
											<td>
												<p>Назначенная группа менеджеров</p>														
											</td>
											<td>
												<select name="config_default_manager_group">
													<option value="0">Похуй на заказы, не назначаем</option>
													
													<? foreach ($user_groups as $user_group) { ?>
														<?php if ($user_group['user_group_id'] == $config_default_manager_group) { ?>
															<option value="<?php echo $user_group['user_group_id'] ?>" selected="selected"><?php echo $user_group['name']; ?></option>
															<?php } else { ?>
															<option value="<?php echo $user_group['user_group_id'] ?>"><?php echo $user_group['name']; ?></option>
														<? } ?>
													<? } ?>
												</select>	
											</td>
										</tr>
									</table>

									<h2>Binotel (телефония)</h2>
							<table class="form">
								<tr>
									<td width="25%">
										<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Движок телефонии</span></p>
										<select name="config_telephony_engine">
											<?php if ($config_telephony_engine == 'asterisk') { ?>
												<option value="asterisk" selected="selected">Asterisk AMI</option>
												<option value="binotel">Binotel API</option>
												<?php } else { ?>													
												<option value="asterisk">Asterisk AMI</option>
												<option value="binotel"  selected="selected">Binotel API</option>
											<? } ?>
										</select>										
									</td>

									<td width="25%">
										<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Binotel API KEY</span></p>
										<input type="text" name="config_binotel_api_key" value="<?php echo $config_binotel_api_key; ?>" size="30" style="width:300px;" />										
									</td>

									<td width="25%">
										<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Binotel API SECRET</span></p>
										<input type="text" name="config_binotel_api_secret" value="<?php echo $config_binotel_api_secret; ?>" size="30" style="width:300px;" />		
									</td>

									<td width="25%">		
									</td>
								</tr>
							</table>
								</div>
								
								<div id="tab-google-ya-fb-vk">
									<h2 style="color:#57AC79">Соцсети и мессенджеры</h2>
									<table class="form">
										<tr>		
											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ссылка на Facebook</span></p>
												<input type="text" name="social_link_facebook" value="<?php echo $social_link_facebook; ?>" size="30" style="width:150px;" />
											</td>
											
											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ссылка на VK</span></p>
												<input type="text" name="social_link_vkontakte" value="<?php echo $social_link_vkontakte; ?>" size="30" style="width:150px;" />
											</td>	

											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ссылка на Instagram</span></p>
												<input type="text" name="social_link_instagram" value="<?php echo $social_link_instagram; ?>" size="30" style="width:150px;" />
											</td>

											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ссылка на Youtube</span></p>
												<input type="text" name="social_link_youtube" value="<?php echo $social_link_youtube; ?>" size="30" style="width:150px;" />
											</td>

											<td width="20%">
											<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ссылка на Twitter</span></p>
											<input type="text" name="social_link_twitter" value="<?php echo $social_link_twitter; ?>" size="30" style="width:150px;" />
									</td>
										</tr>

										<tr>		
											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Messenger BOT</span></p>
												<input type="text" name="social_link_messenger_bot" value="<?php echo $social_link_messenger_bot; ?>" size="30" style="width:150px;" />
											</td>
											
											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Viber BOT</span></p>
												<input type="text" name="social_link_viber_bot" value="<?php echo $social_link_viber_bot; ?>" size="30" style="width:150px;" />
											</td>	

											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Telegram BOT</span></p>
												<input type="text" name="social_link_telegram_bot" value="<?php echo $social_link_telegram_bot; ?>" size="30" style="width:150px;" />
											</td>

											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Vkontakte BOT</span></p>
												<input type="text" name="social_link_vkontakte_bot" value="<?php echo $social_link_vkontakte_bot; ?>" size="30" style="width:150px;" />
											</td>

											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Whatsapp BOT</span></p>
												<input type="text" name="social_link_whatsapp_bot" value="<?php echo $social_link_whatsapp_bot; ?>" size="30" style="width:150px;" />
											</td>
										</tr>
									</table>
									
									<h2 style="color:#57AC79">Авторизация Google + Facebook APP</h2>
									<table class="form">
										<tr>		
											<td width="25%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Google APP ID</span></p>
												<input type="text" name="social_auth_google_app_id" value="<?php echo $social_auth_google_app_id; ?>" size="30" style="width:150px;" />
											</td>

											<td width="25%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Google Secret Key</span></p>
												<input type="text" name="social_auth_google_secret_key" value="<?php echo $social_auth_google_secret_key; ?>" size="30" style="width:150px;" />
											</td>	

											<td width="25%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">FB APP ID</span></p>
												<input type="text" name="social_auth_facebook_app_id" value="<?php echo $social_auth_facebook_app_id; ?>" size="30" style="width:150px;" />
											</td>

											<td width="25%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">FB APP Secret Key</span></p>
												<input type="text" name="social_auth_facebook_secret_key" value="<?php echo $social_auth_facebook_secret_key; ?>" size="30" style="width:150px;" />
											</td>	
										</tr>
									</table>

									
									<h2 style="color:#57AC79">Google Tag Manager (GTM) + Custom JS code</h2>
									<table class="form">
										<tr>		
											<td width="25%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">GTM Javascript (header)</span></p>
												<textarea name="config_gtm_header" cols="40" rows="10"><?php echo $config_gtm_header; ?></textarea>
											</td>

											<td width="25%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">GTM NoScript (body)</span></p>
												<textarea name="config_gtm_body" cols="40" rows="10"><?php echo $config_gtm_body; ?></textarea>
											</td>	

											<td width="25%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Custom JS-код в футер</span></p>
												<textarea name="config_google_analytics" cols="40" rows="10"><?php echo $config_google_analytics; ?></textarea>
											</td>

											<td width="25%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Custom JS-код в хедер</span></p>
												<textarea name="config_google_analytics_header" cols="40" rows="10"><?php echo $config_google_analytics_header; ?></textarea>
											</td>
										</tr>
									</table>

									<h2 style="color:#57AC79">Google Analitycs, Merchant</h2>	
									<table class="form">
										<tr>

											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Идентификатор Google Analitycs</span></p>
												<input type="text" name="config_google_analitycs_id" value="<?php echo $config_google_analitycs_id; ?>" size="30" style="width:150px;" />
											</td>
											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Google Conversion ID</span></p>
												<input type="text" name="config_google_conversion_id" value="<?php echo $config_google_conversion_id; ?>" size="30" style="width:150px;" />
											</td>
											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Google Merchant ID</span></p>
												<input type="text" name="config_google_merchant_id" value="<?php echo $config_google_merchant_id; ?>" size="30" style="width:150px;" />											
											</td>
											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Тип Google remarketing</span></p>
												<select name="config_google_remarketing_type">
													<?php if ($config_google_remarketing_type == 'ecomm') { ?>
														<option value="ecomm" selected="selected">E-commerce ecomm_</option>
													<?php } else { ?>
														<option value="ecomm">E-commerce ecomm_</option>
													<?php } ?>
													<?php if ($config_google_remarketing_type == 'dynx') { ?>
														<option value="dynx" selected="selected">Универсальный dynx_</option>
													<?php } else { ?>
														<option value="dynx">Универсальный dynx_</option>
													<?php } ?>
												</select>
											</td>
											<td width="20%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Передача данных Ecommerce</span></p>
												<select name="config_google_ecommerce_enable">
													<?php if ($config_google_ecommerce_enable) { ?>
														<option value="1" selected="selected">Включить</option>
														<option value="0">Отключить</option>
													<?php } else { ?>													
														<option value="1">Включить</option>
														<option value="0"  selected="selected">Отключить</option>
													<? } ?>
												</select>
											</td>
										</tr>
									</table>


									<h2 style="color:#57AC79">Google ReCaptcha</h2>	
									<table class="form">
										<tr>		
											<td style="width:33%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Включить ReCaptcha</span></p>
												<select name="config_google_recaptcha_contact_enable">
													<?php if ($config_google_recaptcha_contact_enable) { ?>
														<option value="1" selected="selected">Включить</option>
														<option value="0">Отключить</option>
													<?php } else { ?>													
														<option value="1">Включить</option>
														<option value="0"  selected="selected">Отключить</option>
													<? } ?>
												</select>									
											</td>		
											<td style="width:33%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">ReCaptcha key</span></p>
												<input type="text" name="config_google_recaptcha_contact_key" value="<?php echo $config_google_recaptcha_contact_key; ?>" size="40" style="width:300px;" />
											</td>		
											<td style="width:33%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">ReCaptcha Secret</span></p>
												<input type="text" name="config_google_recaptcha_contact_secret" value="<?php echo $config_google_recaptcha_contact_secret; ?>" size="40" style="width:300px;" />

											</td>
										</tr>
									</table>

									<h2 style="color:#7F00FF">Facebook пиксель</h2>
									<table class="form">
										<tr>		
											<td width="25%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">FB пиксель (header)</span></p>
												<textarea name="config_fb_pixel_header" cols="40" rows="10"><?php echo $config_fb_pixel_header; ?></textarea>
											</td>

											<td width="25%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">FB пиксель (body)</span></p>
												<textarea name="config_fb_pixel_body" cols="40" rows="10"><?php echo $config_fb_pixel_body; ?></textarea>
											</td>	
											<td width="25%"></td>
											<td width="25%"></td>
										</tr>
									</table>

									<h2 style="color:#3F6AD8">VK пиксель / ремаркетинг / ретаргетинг</h2>
									<table class="form">
										<tr>		
											<td width="25%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3F6AD8; color:#FFF">VK пиксель (header)</span></p>
												<textarea name="config_vk_pixel_header" cols="40" rows="10"><?php echo $config_vk_pixel_header; ?></textarea>
											</td>

											<td width="25%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3F6AD8; color:#FFF">VK пиксель (body)</span></p>
												<textarea name="config_vk_pixel_body" cols="40" rows="10"><?php echo $config_vk_pixel_body; ?></textarea>
											</td>	
											<td width="50%">

												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3F6AD8; color:#FFF">Включить пиксель</span></p>
												<select name="config_vk_enable_pixel">
													<?php if ($config_vk_enable_pixel) { ?>
														<option value="1" selected="selected">Включить</option>
														<option value="0">Отключить</option>
													<?php } else { ?>													
														<option value="1">Включить</option>
														<option value="0"  selected="selected">Отключить</option>
													<? } ?>
												</select>

												<br />
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3F6AD8; color:#FFF">VK пиксель ID</span></p>
												<input type="text" name="config_vk_pixel_id" value="<?php echo $config_vk_pixel_id; ?>" size="40" />

												<br />
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3F6AD8; color:#FFF">VK прайслист ID</span></p>
												<input type="text" name="config_vk_pricelist_id" value="<?php echo $config_vk_pricelist_id; ?>" size="40" />

											</td>
										</tr>
									</table>



									<h2 style="color:#cf4a61">Yandex Metrika</h2>
									<table class="form">
										<tr>		
											<td style="width:33%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Yandex Metrika идентификатор</span></p>
												<input type="text" name="config_metrika_counter" value="<?php echo $config_metrika_counter; ?>" size="30" style="width:150px;" />
											</td>
											<td>
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Yandex WebVisor</span></p>
												<select name="config_webvisor_enable">
													<?php if ($config_webvisor_enable) { ?>
														<option value="1" selected="selected">Включить</option>
														<option value="0">Отключить</option>
													<?php } else { ?>													
														<option value="1">Включить</option>
														<option value="0"  selected="selected">Отключить</option>
													<? } ?>
												</select>
											</td>
											<td style="width:33%">
												<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Включить accurateTrackBounce, trackLinks, clickmap</span></p>
												<select name="config_clickmap_enable">
													<?php if ($config_clickmap_enable) { ?>
														<option value="1" selected="selected">Включить</option>
														<option value="0">Отключить</option>
													<?php } else { ?>													
														<option value="1">Включить</option>
														<option value="0"  selected="selected">Отключить</option>
													<? } ?>
												</select>
											</td>
										</tr>	
									</table>
								</div>
							</form>
						</div>
					</div>
				</div>
				<script type="text/javascript">

					$('select, textarea, input[type=text], input[type=number], input[type=time], input[type=checkbox]').bind('change', function() {
						var key  			= $(this).attr('name');
						var elem 			= $(this);
						var value 			= $(this).val();
						var store_id 		= $('input[name=store_id]').val();
						var js_serialized 	= 0;

						if (elem.attr('data-key') != null){
							console.log('multi setting, get all keys for ' + elem.attr('data-key'));

							key   			= elem.attr('data-key');					
							value 			= $('input[data-key=\'' + elem.attr('data-key') + '\']').serialize();
							js_serialized 	= 1;		
						} else {
							if (elem.attr('type') == 'checkbox'){
								value = [];
								if (key.indexOf('[]') > 0){
									var allboxes = $('input[name=\''+ key +'\']');

									allboxes.each(function(i){
										if ($(this).attr('checked')){
											value.push($(this).val());
										}
									});
								} else {
									if (elem.attr('checked')){
										value = elem.val();
									} else {
										value = 0;
									}
								}
							}
						}

						$.ajax({
							type: 'POST',
							url: 'index.php?route=setting/setting/editSettingAjax&store_id=' + store_id + '&token=<?php echo $token; ?>',
							data: {
								key: key,
								value: value,
								js_serialized: js_serialized					
							},
							beforeSend: function(){
								elem.css('border-color', 'yellow');
								elem.css('border-width', '2px');						
							},
							success: function(){
								elem.css('border-color', 'green');
								elem.css('border-width', '2px');
							}
						});

					});


				</script>
				<script type="text/javascript"><!--
					$('select[name=\'config_country_id\']').bind('change', function() {
						$.ajax({
							url: 'index.php?route=setting/store/country&token=<?php echo $token; ?>&country_id=' + this.value,
							dataType: 'json',
							beforeSend: function() {
								$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
							},		
							complete: function() {
								$('.wait').remove();
							},			
							success: function(json) {
								if (json['postcode_required'] == '1') {
									$('#postcode-required').show();
									} else {
									$('#postcode-required').hide();
								}
								
								html = '<option value=""><?php echo $text_select; ?></option>';
								
								if (json['zone'] != '') {
									for (i = 0; i < json['zone'].length; i++) {
										html += '<option value="' + json['zone'][i]['zone_id'] + '"';
										
										if (json['zone'][i]['zone_id'] == '<?php echo $config_zone_id; ?>') {
											html += ' selected="selected"';
										}
										
										html += '>' + json['zone'][i]['name'] + '</option>';
									}
									} else {
									html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
								}
								
								$('select[name=\'config_zone_id\']').html(html);
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					});
								
				//--></script> 
				<script type="text/javascript"><!--
					function image_upload(field, thumb) {
						$('#dialog').remove();
						
						$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
						
						$('#dialog').dialog({
							title: '<?php echo $text_image_manager; ?>',
							close: function (event, ui) {
								if ($('#' + field).attr('value')) {
									$.ajax({
										url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
										dataType: 'text',
										success: function(data) {
											$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
										}
									});
								}
							},	
							bgiframe: false,
							width: 800,
							height: 800,
							resizable: false,
							modal: false
						});
					};
				//--></script> 
				<script type="text/javascript"><!--
					$('#tabs a').tabs();
				//--></script> 
			<?php echo $footer; ?>																																																																																																																	