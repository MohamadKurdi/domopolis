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
			<div class="buttons"><a href="<?php echo $repair; ?>" class="button"><?php echo $button_repair; ?></a><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('#form').submit();" class="button"><?php echo $button_delete; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left"></td>
							<td class="left">Картинка</td>
							<td class="left">Иконка меню</td>	
							<td class="left"><?php echo $column_name; ?></td>
							<td class="left"></td>
							<td class="left">Габариты</td>
							<td class="left">Уд. нал</td>
							<td class="left">Меню в дочерних</td>
							<td class="left">Пересечения</td>
							<td class="left" width="100px">Google</td>
							<td class="left" width="100px">Amazon Link</td>
							<td class="left">Amazon Sync</td>
							<td class="left" width="100px">Yandex</td>
							<td class="left">Priceva</td>
							<td class="left">ТНВЭД</td>	
							<td class="right"><?php echo $column_sort_order; ?></td>
							<td class="right"><?php echo $column_action; ?></td>
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
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><?php echo $category['category_id']; ?></span>									
									</td>	
									<td class="left">
										<img src="<?php echo $category['image']; ?>" height="50px" width="50px" />									
									</td>			  
									<td>
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 62 62" width="100" height="100">
											<? echo $category['menu_icon']; ?>
										</svg>  
									</td>	
									<td class="left">
										<b><?php echo $category['name']; ?></b>
										<?php if ($category['menu_name']) { ?>
											<br /><span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">В меню: <?php echo $category['menu_name']; ?></span>
										<?php } ?>
									</td>
									<td class="left">
										<small><?php echo $category['alternate_name']; ?></small>
									</td>
									
									<td class="left" style="white-space:nowrap">
										<? if ((float)$category['default_weight']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Вес: <?php echo (int)$category['default_weight']; ?></span>
											<? } else { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Вес не задан</span>
										<? } ?>
										<br /><br />
										<? if ((float)$category['default_length'] && (float)$category['default_width'] && (float)$category['default_height']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Размер: <?php echo (int)$category['default_length']; ?> / <?php echo (int)$category['default_width']; ?> / <?php echo (int)$category['default_height']; ?></span>
											<? } else { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Размер не задан</span>
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
										<? if ($category['google_category']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF; font-size:10px;"><?php echo $category['google_category']; ?></span>
											<? } else { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
										<? } ?>
									</td>	
									
									<td class="left">
										<? if ($category['amazon_category_name']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ffaa56; color:#FFF; font-size:10px;"><?php echo $category['amazon_category_name']; ?></span>
											
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
										<? if ($category['yandex_category_name']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF; font-size:10px;"><?php echo $category['yandex_category_name']; ?></span>
											
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
										<? } ?></td>
										<td class="right"><?php echo $category['sort_order']; ?></td>
										<? /*	<td class="right" style="word-wrap: normal; min-width:100px;">
										<?php if ($category['feeds']) { ?>
										<? foreach ($category['feeds'] as $_feed) { ?>  
										<a style="margin-top: 2px;" class="button" href="<? echo $_feed['path']; ?>" target="_blank"><? echo $_feed['store_id']; ?> <? echo $_feed['currency']; ?> <i class="fa fa-share-square"></i></a><br />
										<? } ?>
										<? } ?></td>
									*/ ?>
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