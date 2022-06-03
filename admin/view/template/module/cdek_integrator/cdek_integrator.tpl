<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?>
			<?php if ($breadcrumb['href']) { ?>
				<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
				<?php } else { ?>
				<?php echo $breadcrumb['text']; ?>
			<?php } ?>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<?php if (isset($attention)) { ?>
		<div class="attention"><?php echo $attention; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<?php /* if ($total) { ?>
					<a class="button" href="<?php echo $order; ?>" class="button"><?php echo $button_new_order; ?><span class="new"><?php echo $total; ?></span></a>
				<?php } */ ?>
				<a class="button" href="<?php echo $option; ?>" class="button"><?php echo $button_option; ?></a>
				<a class="button" href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
			<h2 class="title_h2">Последние отгрузки</h2>
			<table class="list">
				<thead>
					<td class="left">№ на сайте</td>
					<td class="left">Номер отправления</td>
					<td class="left">Акта приема-передачи</td>
					<td class="left">Дата</td>
					<td class="left">Откуда</td>
					<td class="left">Куда</td>
					<td class="left">Статус</td>
					<td class="left">Стоимость доставки</td>
					<td class="right"></td>
				</thead>
				<tbody>
					<?php if (!empty($dispatches)) { ?>
						<?php foreach ($dispatches as $dispatch_info) { ?>
							<tr>
								<td class="left">
									<?php echo $dispatch_info['order_id']; ?>
									
									<? if ($dispatch_info['parties']) { ?>
										<div>
											<?php foreach ($dispatch_info['parties'] as $_partie) { ?>
												<div><small><a style="color:#<? echo $dispatch_info['status_txt_color']; ?>" href="<?php echo $_partie['href']; ?>"><?php echo $_partie['part_num']; ?></a></small></div>
											<? } ?>
										</div>
									<? } ?>
									
								</td>
								<td class="left"><?php echo $dispatch_info['dispatch_number']; ?></td>
								<td class="left">
									<?php if ($dispatch_info['act_number']) { ?>
										<?php echo $dispatch_info['act_number']; ?>
										<?php } else { ?>
										<a class="js sync-row" href="<?php echo $dispatch_info['sync']; ?>">Синхронизовать</a>
									<?php } ?>
								</td>
								<td class="left"><?php echo $dispatch_info['date']; ?></td>
								<td class="left"><?php echo $dispatch_info['city_name']; ?></td>
								<td class="left"><?php echo $dispatch_info['recipient_city_name']; ?></td>
								<td class="left"><?php echo $dispatch_info['status']; ?><span class="help"><?php echo $dispatch_info['status_date']; ?></span></td>
								<td class="left">
									<?php if ($dispatch_info['cost']) { ?>
										<?php echo $dispatch_info['cost']; ?>
										<?php } else { ?>
										<a class="js sync-row" href="<?php echo $dispatch_info['sync']; ?>">Синхронизовать</a>
									<?php } ?>
								</td>
								<td class="right action">
									<?php foreach ($dispatch_info['action'] as $action) { ?>
										<a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
									<?php } ?>
								</td>
								</tr>
							<?php } ?>
							<tr>
								<td class="center" colspan="9"><a href="<?php echo $dispatch_list; ?>">Смотреть все отгрузки</a></td>
							</tr>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="9"><?php echo $text_no_results; ?></td>
							</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript"><!--
		
		$('a.js.sync-row').live('click', function(event){
			
			event.preventDefault();
			
			ajaxSend(this, {
				callback: function(el, json){
					
					var context = $(el).closest('tr');
					
					if (json.status != 'error') {
						
						$('td', context).animate({'background-color': '#000000'}, 'fast', function(){
							
							$('td:eq(2)', context).html(json.act_number);
							$('td:eq(7)', context).html(json.cost);
							
							$('td', context).animate({'background-color': '#FFFFFF'}, 'fast');
							
						});
						
						} else {
						$('.box').before('<div class="warning">' + json.message + '</div>');
					}
					
				}
			});
			
		});
		
	//--></script>
<?php echo $footer; ?>