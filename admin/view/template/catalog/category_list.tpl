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
			<h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">

				<a href="<?php echo $simpleview; ?>" class="button">
					<?php if ($simpleview_enabled) { ?>
						<i class="fa fa-refresh"></i> Страшное и сложное отображение
					<?php } else { ?>
						<i class="fa fa-refresh"></i> Простое отображение
					<?php } ?>
				</a>

				<a href="<?php echo $rollup; ?>" class="button">
					<?php if ($rollup_enabled) { ?>
						<i class="fa fa-caret-square-o-up" aria-hidden="true"></i> Отключить свёртывание
					<?php } else { ?>
						<i class="fa fa-caret-square-o-down" aria-hidden="true"></i> Включить свёртывание
					<?php } ?>
				</a>

				<a href="<?php echo $hidedisabled; ?>" class="button">
					<?php if ($hidedisabled_enabled) { ?>
						<i class="fa fa-eye-slash" aria-hidden="true"></i> Спрятать выключенные
					<?php } else { ?>
						<i class="fa fa-eye" aria-hidden="true"></i> Показать выключенные
					<?php } ?>
				</a>

				<?php if ($rollup_enabled) { ?>
					<a href="<?php echo $rollup_all; ?>" class="button"><i class="fa fa-caret-square-o-down" aria-hidden="true"></i> Свернуть всё</a>
				<?php } ?>

				<a href="<?php echo $repair; ?>" class="button"><?php echo $button_repair; ?></a>
				<a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a>
				<a onclick="$('#form').submit();" class="button"><?php echo $button_delete; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;">
								<input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
							</td>
							<td class="left" style="width:70px;">ID</td>
							<?php if ($rollup_enabled) { ?>
								<td width="1" style="text-align: center;">L</td>	
							<?php } ?>
							<td class="left" >Название</td>
							<td class="left" style="width:30px;">Статус</td>
							<?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>
								<td class="left" style="width:200px;">Amzn Link</td>
								<td class="left" style="width:30px;">Amzn Sync</td>
								<td class="left" style="width:30px;">Amzn Final</td>
								<td class="left" style="width:30px;">Amzn Allow Full</td>
							<?php } ?>
							<td class="left" style="width:100px;">Google</td>
							<td class="left" style="width:100px;">Yandex</td>
							<td class="left" style="width:60px;">Картинка</td>
							<td class="left" style="width:50px;">Иконка</td>
							<td class="left" style="width:100px;">Elastic</td>
							<td class="left" style="width:100px;">Габариты</td>
							<td class="left" style="width:30px;">Уд. нал</td>
							<td class="left" style="width:30px;">Меню в доч.</td>
							<td class="left" style="width:30px;">Пересеч.</td>
							<td class="left" style="width:30px;">Priceva</td>
							<td class="left" style="width:30px;">ТНВЭД</td>
							<?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>
								<td class="right" style="width:100px;">Товары -></td>	
								<td class="right" style="width:100px;">Загружено -></td>
								<td class="right" style="width:100px;">Есть цена</td>
							<?php } ?>
							<td class="right" style="width:30px;">Сортировка</td>
							<td class="right" style="width:50px;"></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($categories) { ?>
							<?php foreach ($categories as $category) { ?>
								<tr>
									<td style="text-align: center;"><?php if ($category['selected']) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
									<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" />
										<?php } ?></td>
										<td class="left">

											<span class="status_color" style="display:inline-block; padding:3px 5px; background:<?php if ($rollup_enabled) { ?><?php echo $levels[$category['level']]; ?><?php } else { ?>#4ea24e<?php } ?>; color:#FFF"><?php echo $category['category_id']; ?></span>									

										</td>		
										<?php if ($rollup_enabled) { ?>
											<td class="left" style="font-size:18px;">

												<b style="color:<?php echo $levels[$category['level']]; ?>">L<?php echo $category['level']; ?></b>

											</td>
										<?php } ?>

										<td class="left" style="<?php if ($category['indent']) { ?>padding-left:<?php echo $category['indent']; ?>px;<?php } ?> <?php if ($category['mark']) { ?>border-left:2px solid #f91c02;<?php } ?>">

											<?php if ($category['href']) { ?>
												<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
											<?php } else { ?>
												<?php echo $category['name']; ?>
											<?php } ?>

											<?php if ($category['menu_name']) { ?>
												<br /><small class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><?php echo $category['menu_name']; ?></small>
											<?php } ?>
										</td>

										<td class="right">
											<? if ($category['status']) { ?>
												<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Вкл</span>
											<? } else { ?>
												<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Выкл</span>
											<? } ?>
										</td>

									<?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>	

										<td class="left">
											<? if ($category['amazon_category_name']) { ?>

												<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffaa56; color:#FFF; font-size:10px;">
													<?php if ($category['amazon_category_link']) { ?>
														<a href="<?php echo $category['amazon_category_link']; ?>" target="_blank" style="color:white;text-decoration:none;">
														<?php } ?>

														<?php echo $category['amazon_category_name']; ?>

														<?php if ($category['amazon_category_link']) { ?>
															<i class="fa fa-external-link"></i></a>
														<?php } ?>
													</span>

													<br />

													<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffaa56; color:#FFF; font-size:10px;">
														<?php echo $category['amazon_category_id']; ?></span>


													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
													<? } ?>
												</td>	

												<td class="left">
													<? if ($category['amazon_sync_enable']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
													<? } ?>
												</td>

												<td class="left">
													<? if ($category['amazon_final_category']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
													<? } ?>
												</td>		

												<td class="left">
													<? if ($category['amazon_can_get_full']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
													<? } ?>
												</td>	
											<?php } ?>

												<td class="left">
													<? if ($category['google_category']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF; font-size:10px;"><?php echo $category['google_category']; ?></span>
													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
													<? } ?>
												</td>	


												<td class="left">
													<? if ($category['yandex_category_name']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF; font-size:10px;"><?php echo $category['yandex_category_name']; ?></span>

													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
													<? } ?>	
												</td>																						


												<td class="left">
													<img src="<?php echo $category['image']; ?>" height="50px" width="50px" />									
												</td>			  
												<td>
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 62 62" width="50" height="50">
														<? echo $category['menu_icon']; ?>
													</svg>  
												</td>	

												<td class="left">
													<small><?php echo $category['alternate_name']; ?></small>
												</td>

												<td class="left" style="white-space:nowrap">
													<? if ((float)$category['default_weight']) { ?>
														<small class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Вес: <?php echo (int)$category['default_weight']; ?></small>
													<? } else { ?>
														<small class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Вес не задан</small>
													<? } ?>
													<br />
													<? if ((float)$category['default_length'] && (float)$category['default_width'] && (float)$category['default_height']) { ?>
														<small class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Размер: <?php echo (int)$category['default_length']; ?> / <?php echo (int)$category['default_width']; ?> / <?php echo (int)$category['default_height']; ?></small>
													<? } else { ?>
														<small class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Размер не задан</small>
													<? } ?>
												</td>

												<td class="left">
													<? if ($category['deletenotinstock']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
													<? } ?>
												</td>

												<td class="left">
													<? if ($category['submenu_in_children']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
													<? } ?>
												</td>

												<td class="left">
													<? if ($category['intersections']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
													<? } ?>
												</td>

												<td class="left">
													<? if ($category['priceva_enable']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
													<? } ?>
												</td>

												<td class="right">
													<? if ($category['tnved']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><?php echo $category['tnved']; ?></span>
													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
													<? } ?>
												</td>									

												<?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>
													
													<td class="right">
														<?php if ($category['count']) { ?>
															<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">
																<a style="color:#FFF; text-decoration:none" href="<?php echo $category['filter']; ?>" target="_blank"><?php echo $category['count']; ?> 
																<i class="fa fa-filter"></i>
															</span>										
														<?php } else { ?>
															<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><?php echo $category['count']; ?></span>	
														<?php }?>
													</td>

													
													<td class="right">
														<?php if ($category['filled']) { ?>
															<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">
																<a style="color:#FFF; text-decoration:none" href="<?php echo $category['filter_filled']; ?>" target="_blank"><?php echo $category['filled']; ?> 
																<i class="fa fa-filter"></i>
															</span>										
														<?php } else { ?>
															<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><?php echo $category['filled']; ?></span>	
														<?php }?>
													</td>

												<?php } ?>

												<?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>												
													<td class="right">
														<?php if ($category['has_price']) { ?>
															<span class="status_color" style="display:inline-block; padding:3px 5px; background:#32bd38; color:#FFF">
																<a style="color:#FFF; text-decoration:none" href="<?php echo $category['filter_has_price']; ?>" target="_blank"><?php echo $category['has_price']; ?> 
																<i class="fa fa-filter"></i>
															</span>										
														<?php } else { ?>
															<span class="status_color" style="display:inline-block; padding:3px 5px; background:grey; color:#FFF"><?php echo $category['has_price']; ?></span>	
														<?php }?>
													</td>
												<?php } ?>

												<td class="right">
													<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><?php echo $category['sort_order']; ?></span>										
												</td>

												<td class="right"><?php foreach ($category['action'] as $action) { ?>
													<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>				
													<?php } ?></td>
												</tr>
											<?php } ?>
										<?php } else { ?>
											<tr>
												<td class="center" colspan="4"><?php echo $text_no_results; ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</form>
							<div class="pagination"><?php echo $pagination; ?></div>
						</div>
					</div>
				</div>
				<?php echo $footer; ?>