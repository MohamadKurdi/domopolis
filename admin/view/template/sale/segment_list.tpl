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
			<h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a>
				<a onclick="$('form').attr('action','<? echo $deletestats; ?>'); $('form').submit();" class="button" class="button">Очистить динамику</a>
			</div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left"><?php if ($sort == 'name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Название сегмента</a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>">Название сегмента</a>
							<?php } ?></td>
							<td class="right">Параметры сегмента</td>
							<td class="right">Количество покупателей</td>
							<td class="right">Общая сумма</td>
							<td class="right">Средний CSI</td>
							<td class="right">Средний чек</td>
							<td class="right">Вып. заказов</td>
							<td class="right">Отм. заказов</td>
							<td class="right">Соотношение</td>
							<td class="right">Записей <i class="fa fa-area-chart"></i></td>
							<td class="right">Статус</td>
							<td class="right"><?php if ($sort == 'sort_order') { ?>
								<a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
							<?php } ?></td>                
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($segments) { ?>
							<?php foreach ($segments as $group => $segment_list) { ?>
								<tr>
									<td class="left" colspan="13"><b><? echo $group; ?></b></td>
								</tr>
								<? foreach ($segment_list as $segment) { ?>
								<tr>
									<td style="text-align: center;"><?php if ($segment['selected']) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $segment['segment_id']; ?>" checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $segment['segment_id']; ?>" />
									<?php } ?></td>
									<td class="left">
										<? if ($segment['bg_color']) { ?>
											<span class="status_color" style="background:#<?php echo $segment['bg_color']; ?>">
												<? if ($segment['fa_icon']) { ?>
													<i class="fa <? echo $segment['fa_icon']; ?>" aria-hidden="true"></i>&nbsp;
												<? } ?>
												<?php echo $segment['name']; ?>
											</span>
											<? } else { ?>
											<? if ($segment['fa_icon']) { ?>
												<i class="fa <? echo $segment['fa_icon']; ?>" aria-hidden="true"></i>&nbsp;
											<? } ?>
											<?php echo $segment['name']; ?>
										<? } ?>
									</td>
									<td class="right" style="font-size:10px;">
										<? foreach ($segment['determination'] as $key => $value) { ?>
											<span style="background:#e5e5e5; padding:2px 2px; display:inline-block;margin-bottom:4px;margin-right:4px;"><? echo $key; ?> : <? echo mb_strlen($value)>=64?mb_substr($value,0,64).'...':$value; ?></span>		 
										<? } ?>
									</td>
									<td class="right"><?php echo $segment['customer_count']; ?></td>
									<td class="right"><?php echo $segment['total_cheque']; ?> €</td>
									<td class="right"><?php echo $segment['avg_csi']; ?></td>
									<td class="right"><?php echo $segment['avg_cheque']; ?> €</td>
									<td class="right"><?php echo $segment['order_good_count']; ?></td>
									<td class="right"><?php echo $segment['order_bad_count']; ?></td>
									<td class="right"><?php echo $segment['order_good_to_bad']; ?> %</td>
									<td class="right"><?php echo $segment['dynamics_counter']; ?></td>
									<td class="right"><?php echo $segment['enabled']; ?></td>
									<td class="right"><?php echo $segment['sort_order']; ?></td>
									<td class="right"><?php foreach ($segment['action'] as $action) { ?>
										<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
									<?php } ?></td>
								</tr>
							<?php } ?>
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