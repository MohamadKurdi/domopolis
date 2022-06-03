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
							
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked).trigger('change');" /></td>
							<td class="left" width='1'>Рисунок</td>
							<td class="left" width='1'>Баннер</td>
							<td class="left"><?php if ($sort == 'name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
							<?php } ?></td>
							
							<td class="left" width=''>
                               Название мультиязык
							</td>
							
							<td class="left" width=''>
                                Всего брендов
							</td>
							
							<td class="left" width=''>
                                Привязка брендов
							</td>
							
							<td class="left" width=''>
                                Магазины
							</td>
							
							<td class="right"><?php if ($sort == 'sort_order') { ?>
								<a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
							<?php } ?></td>
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($countrybrands) { ?>
							<?php foreach ($countrybrands as $countrybrand) { ?>
								<tr>
									<td style="text-align: center;"><?php if ($countrybrand['selected']) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $countrybrand['countrybrand_id']; ?>" checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $countrybrand['countrybrand_id']; ?>" />
									<?php } ?></td>
									
									<td class="left" width='1'>
										<img src='<?php echo $countrybrand['image']; ?>' />
										
									</td>
									<td class="left" width='1'>
										<img src='<?php echo $countrybrand['banner']; ?>' />
										
									</td>
									
									<td class="left">
										<b><?php echo $countrybrand['name']; ?></b>
									</td>
									
									<td class="left">
										<?php foreach ($languages as $language) { ?>
											<div>
												<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
												<?php if (empty($countrybrand['descriptions'][$language['language_id']]) || empty($countrybrand['descriptions'][$language['language_id']]['name_overload'])) {  ?>
													<i class="fa fa-exclamation-triangle" style="color:#cf4a61"></i>не задано
													<?php } else {?><small><?php echo $countrybrand['descriptions'][$language['language_id']]['name_overload']; ?></small>
												<?php } ?>
											</div>
										<?php } ?>
									</td>
									
									<td>
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:<?php if ($countrybrand['total_brands']) { ?>#00ad07<?php } else { ?>#cf4a61<?php } ?>; color:#FFF"><b><?php echo $countrybrand['total_brands']; ?> брендов</b></span>
									</td>
									
									<td class="left">
										
										<?php foreach ($languages as $language) { ?>
											<div>
												<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> 
												<?php if (empty($countrybrand['descriptions'][$language['language_id']]) || empty($countrybrand['descriptions'][$language['language_id']]['type'])) {  ?>
													<i class="fa fa-exclamation-triangle" style="color:#cf4a61"></i>не задано
													<?php } else {?>																																
													<small><?php echo $countrybrand['descriptions'][$language['language_id']]['type']; ?></small>
												<?php } ?>
											</div>
										<?php } ?>
										
									</td>
									
									<td class="right">
											<?php foreach ($stores as $store) { ?>
												
													<?php if (in_array($store['store_id'], $countrybrand['stores'])) { ?>
														<div><?php echo $store['name']; ?></div>
													<?php } ?>
												
											<?php } ?>
									</td>
									
									<td class="right">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><b><?php echo $countrybrand['sort_order']; ?></b></span>
									</td>
									<td class="right"><?php foreach ($countrybrand['action'] as $action) { ?>
										<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
									<?php } ?></td>
								</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="8"><strong><?php echo $text_no_results; ?></strong></td>
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