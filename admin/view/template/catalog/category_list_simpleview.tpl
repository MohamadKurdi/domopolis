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
							<?php if ($rollup_enabled) { ?>
								<td width="1" style="text-align: center;">L</td>	
							<?php } ?>
							<td class="left" >Название</td>
							<?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>
								<td class="left">Ссылка на Amazon</td>
								<td class="center" style="width:100px;">Конечная категория Amazon</td>	
								<td class="center" style="width:100px;">Синхронизация с Amazon</td>
								<td class="center" style="width:100px;">Разрешить полную загрузку товаров</td>
							<?php } ?>
							<td class="right" style="width:100px;">Товары</td>		
							<?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>		
								<td class="right" style="width:100px;">Загружено</td>
							<?php } ?>
							<td class="right" style="width:30px;">Сортировка</td>
							<td class="left" style="width:30px;">Статус</td>
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
										<?php if ($rollup_enabled) { ?>
											<td class="left" style="font-size:18px;">

												<b style="color:<?php echo $levels[$category['level']]; ?>">L<?php echo $category['level']; ?></b>

											</td>
										<?php } ?>

										<?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>

											<td class="left" style="<?php if ($category['indent']) { ?>padding-left:<?php echo $category['indent']; ?>px;<?php } ?> <?php if ($category['mark']) { ?>border-left:2px solid #f91c02;<?php } ?>">

												<?php if ($category['href']) { ?>
													<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
												<?php } else { ?>
													<?php echo $category['name']; ?>
												<?php } ?>
											</td>

											<td class="left">
												<? if ($category['amazon_category_name']) { ?>
													<?php if ($category['amazon_category_link']) { ?>
														<a href="<?php echo $category['amazon_category_link']; ?>" target="_blank" style="text-decoration:none;">
														<?php } ?>

														<?php echo $category['amazon_category_name']; ?>

														<?php if ($category['amazon_category_link']) { ?>
															<i class="fa fa-external-link"></i></a>
														<?php } ?>
													<?php } ?>
												</td>	

												<td class="left">
													<? if ($category['amazon_final_category']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
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
													<? if ($category['amazon_can_get_full']) { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
													<? } else { ?>
														<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
													<? } ?>
												</td>			
											<?php } ?>

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

											<?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>
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

											<td class="right">
												<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><?php echo $category['sort_order']; ?></span>										
											</td>




											<td class="right">
												<? if ($category['status']) { ?>
													<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Вкл</span>
												<? } else { ?>
													<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Выкл</span>
												<? } ?>
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