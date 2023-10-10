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
			<h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;">
								<input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
							</td>
							<td class="right">ID</td>
							<td class="right">Лого</td>
							<td class="left">
							<?php if ($sort == 'name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
							<?php } ?>
							</td>
							<td class="right"><?php if ($sort == 'sort_order') { ?>
								<a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>">Сортировка</a>
								<?php } else { ?>
								<a href="<?php echo $sort_sort_order; ?>">Сортировка</a>
							<?php } ?></td>
							<td class="right" style="word-wrap: normal; width:100px;">Товаров</td>
							<td class="right" style="word-wrap: normal; min-width:100px;">Домашняя</td>
							<td class="right" style="word-wrap: normal; width:50px;">Только товары</td>
							<?php if ($this->config->get('config_hotline_feed_enable')) { ?>
								<td class="right" style="word-wrap: normal; width:50px;">Hotline</td>
							<?php } ?>
							<td class="right" style="word-wrap: normal; min-width:100px;">Магазины</td>
							<td class="right" style="word-wrap: normal; min-width:100px;">Страна</td>
							<td class="right" style="word-wrap: normal; min-width:100px;">SEO URL</td>
							<td class="right" style="word-wrap: normal; min-width:100px;">Альтернативные</td>
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($manufacturers) { ?>
							<?php foreach ($manufacturers as $manufacturer) { ?>
								<tr>
									<td style="text-align: center;">
										<?php if ($manufacturer['selected']) { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
										<?php } ?>
									</td>
									
									<td class="left" width='50px'>
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><b><?php echo $manufacturer['manufacturer_id']; ?></b></span>									
									</td>

									<td class="left" width='1'>
										<img src='<?php echo $manufacturer['image']; ?>' loading="lazy" />										
									</td>
									
									<td class="left">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#ff7815; color:#FFF"><b><?php echo $manufacturer['name']; ?></b></span>
									</td>
									<td class="right">										
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><b><?php echo $manufacturer['sort_order']; ?></b></span>
									</td>
									
									<td class="right">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:<?php if ($manufacturer['total_products']) { ?>#00ad07<?php } else { ?>#cf4a61<?php } ?>; color:#FFF">
											<b><?php echo $manufacturer['total_products']; ?></b>
										</span>
									</td>

									<td class="center">
										<? if ($manufacturer['menu_brand']) { ?>
											<i class="fa fa-check-circle" style="color:#4ea24e"></i>
										<? } else { ?>
											<i class="fa fa-times-circle" style="color:#cf4a61"></i>
										<? } ?>
									</td>

									<td class="center">
										<? if ($manufacturer['show_goods']) { ?>
											<i class="fa fa-check-circle" style="color:#4ea24e"></i>
										<? } else { ?>
											<i class="fa fa-times-circle" style="color:#cf4a61"></i>
										<? } ?>
									</td>

									<?php if ($this->config->get('config_hotline_feed_enable')) { ?>
										<td class="center">
											<? if ($manufacturer['hotline_enable']) { ?>
												<i class="fa fa-check-circle" style="color:#4ea24e"></i>
											<? } else { ?>
												<i class="fa fa-times-circle" style="color:#cf4a61"></i>
											<? } ?>
										</td>
									<?php } ?>
									
									<td class="left">										
										<?php foreach ($stores as $store) { ?>												
											<?php if (in_array($store['store_id'], $manufacturer['stores'])) { ?>
												<div><?php echo $store['name']; ?></div>
											<?php } ?>												
										<?php } ?>									
									</td>
									
									<td class="left">										
										<?php foreach ($languages as $language) { ?>
											<div>
												<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
												<?php if (empty($manufacturer['descriptions'][$language['language_id']]) || empty($manufacturer['descriptions'][$language['language_id']]['location'])) {  ?>
													<i class="fa fa-exclamation-triangle" style="color:#cf4a61"></i>не задано
												<?php } else {?>																																
													<small><?php echo $manufacturer['descriptions'][$language['language_id']]['location']; ?></small>
												<?php } ?>
											</div>
										<?php } ?>									
									</td>
									
									<td class="left">
										<?php foreach ($languages as $language) { ?>
											<div>
												<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
												<?php if (empty($manufacturer['seo_urls'][$language['language_id']])) {  ?>
													<i class="fa fa-exclamation-triangle" style="color:#cf4a61"></i>не задано
												<?php } else {?>																																
													<small><?php echo $manufacturer['seo_urls'][$language['language_id']]; ?></small>
												<?php } ?>
											</div>
										<?php } ?>										
									</td>
																		
									<td class="right">
										<?php foreach ($languages as $language) { ?>
											<div>
												<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
												<?php if (empty($manufacturer['descriptions'][$language['language_id']]) || empty($manufacturer['descriptions'][$language['language_id']]['alternate_name'])) {  ?>
													<i class="fa fa-exclamation-triangle" style="color:#cf4a61"></i>не задано
												<?php } else {?>																																
													<small><?php echo $manufacturer['descriptions'][$language['language_id']]['alternate_name']; ?></small>
												<?php } ?>
											</div>
										<?php } ?>
									</td>
									<td class="right"><?php foreach ($manufacturer['action'] as $action) { ?>
										<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>								
									<?php } ?>
									</td>
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