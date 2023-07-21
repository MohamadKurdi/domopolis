<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
<?php } ?>
<table class="list">
	<thead>
		<tr>
			<td class="left"><?php echo $column_date_added; ?></td>
			<td class="left">Заказ</td>
			<td class="left">Действие</td>
			<td class="left">Комментарий</td>
			<td class="left">Просмотр</td>
			<td class="left">Менеджер</td>
		</tr>
	</thead>
	<tbody>
		<?php if ($histories) { ?>
			<?php foreach ($histories as $history) { ?>
				<tr>
					<td class="left" style="width:1px; padding:2px 5px; white-space:nowrap;"><span class="history_data"><?php echo $history['date_added']; ?></span></td>
					<td class="left" style="width:1px; padding:2px 5px; white-space:nowrap;">
						<? if ($history['order_id']) { ?><a class="button" href="index.php?route=sale/order/update&order_id=<? echo $history['order_id'] ?>&token=<? echo $token ?>">Заказ #<? echo $history['order_id']; ?></a><? } ?>
					</td>
					<td class="left" style="width:1px; padding:2px 5px; white-space:nowrap;">
						<? if ($history['call_id']) { ?>
							<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#abce8e;">
								<i class="fa fa-phone"></i>&nbsp; Звонок
							</span>							
							<? } elseif ($history['email_id']) { ?>
							<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#f990c3;">
								<i class="fa fa-envelope" aria-hidden="true"></i>&nbsp; E-mail
							</span>		
							<? } elseif ($history['sms_id']) { ?>
							<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#e4c25a;">
								<i class="fa fa-mobile" aria-hidden="true"></i>&nbsp; SMS
							</span>		
							<? } elseif ($history['segment_id']) { ?>
							<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#ccfcfc;">
								<i class="fa fa-area-chart" aria-hidden="true"></i>&nbsp; Сегмент
							</span>	
							<? } elseif ($history['is_error']) { ?>
							<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#cf4a61; color:white">
								<i class="fa fa-warning"></i>&nbsp; Действие
							</span>	
							<? } elseif ($history['order_status_id']) { ?>
							<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#aaffaa;">
								<i class="fa fa-check-square" aria-hidden="true"></i>&nbsp; Статус
							</span>	
						<? } ?>
					</td>
					<td class="left" style="padding-left:10px;">					
						<? if ($history['need_call']) { ?>
							<span>Договорились перезвонить в: <b><? echo $history['need_call']; ?></b></span>
						<? } ?>
					</span>
					
					<? if ($history['call_id']) { ?>
						<span style="font-style:;">Длительность: <?php echo $history['call']['length']; ?> сек</span>					
						<? } elseif ($history['email_id']) { ?>
						<span style="font-style:;">
							<b><?php echo  $history['email']['emailtemplate_log_to']; ?></b>: <?php echo $history['email']['emailtemplate_log_subject']; ?></span>	
						<? } elseif ($history['sms_id']) { ?>
						<?php if (!empty($history['sms']['comment'])) { ?>	
							<span style="font-style:;">
								<?php echo $history['sms']['comment']; ?>								
							</span>	
						<?php } else { ?>
							<span style="font-style:;">
								<?php echo $history['comment']; ?>								
							</span>	
						<?php } ?>
	
						<? } elseif ($history['segment_id']) { ?>
						<span style="font-style:;">	
							<? $segment = $history['segment']; if ($segment['bg_color']) { ?>
								<span class="status_color" style="display:inline-block; padding:5px 10px; background:#<?php echo $segment['bg_color']; ?>">
									<? if ($segment['fa_icon']) { ?>
										<i class="fa <? echo $segment['fa_icon']; ?>" aria-hidden="true"></i>&nbsp;
									<? } ?>
									<?php echo $history['comment']; ?>
								</span>
								<? } else { ?>
								<? if ($segment['fa_icon']) { ?>
									<i class="fa <? echo $segment['fa_icon']; ?>" aria-hidden="true"></i>&nbsp;
								<? } ?>	<?php echo $history['comment']; ?>							
							<? } ?>
						</span>		
						<? } elseif ($history['is_error']) { ?>
						<span class="status_color" style="display:inline-block; padding:5px 10px; background-color:#cf4a61; color:white">
							<?php echo $history['comment']; ?>
						</span>
						<? } elseif ($history['order_status_id']) { ?>
						
						<? if ($history['prev_order_status_id']) { ?>
							<span class="status_color" style="display:inline-block; padding:5px 10px; background: #<?php echo $history['prev_order_status']['status_bg_color']; ?>; color: #<?php echo $history['prev_order_status']['status_txt_color']; ?>;"><? if ($history['prev_order_status']['status_fa_icon']) { ?>
								<i class="fa <? echo $history['order_status']['status_fa_icon']; ?>" aria-hidden="true"></i>
							<? } ?><?php echo $history['prev_order_status']['name']; ?></span>
							&rarr; 	<span class="status_color" style="display:inline-block; padding:5px 10px; background: #<?php echo $history['order_status']['status_bg_color']; ?>; color: #<?php echo $history['order_status']['status_txt_color']; ?>;"><? if ($history['order_status']['status_fa_icon']) { ?>
								<i class="fa <? echo $history['order_status']['status_fa_icon']; ?>" aria-hidden="true"></i>
							<? } ?><?php echo $history['order_status']['name']; ?></span>
							<? } else { ?>
							<span class="status_color" style="display:inline-block; padding:5px 10px; background: #<?php echo $history['order_status']['status_bg_color']; ?>; color: #<?php echo $history['order_status']['status_txt_color']; ?>;"><? if ($history['order_status']['status_fa_icon']) { ?>
								<i class="fa <? echo $history['order_status']['status_fa_icon']; ?>" aria-hidden="true"></i>
							<? } ?><?php echo $history['order_status']['name']; ?></span>
						<? } ?>
						
						<? } else { ?>
						<?php echo $history['comment']; ?>
					<? } ?>
				</td>
				<td style="width:1px; padding:2px 5px; white-space:nowrap;">
					<? if ($history['call']) { ?>
						<audio src="<? echo $history['call']['filename']; ?>" controls preload='none'></audio>
					<? } ?>
					<? if ($history['email_id']) { ?>
						<span class="view-mail" style="font-size:20px; cursor:pointer;" data-emailtemplate-log="<?php echo $history['email']['emailtemplate_log_id']; ?>"><i class="fa fa-eye"></i></span>&nbsp;&nbsp;&nbsp;
						<span class="print-mail" style="font-size:20px; cursor:pointer;" data-emailtemplate-log="<?php echo $history['email']['emailtemplate_log_id']; ?>"><i class="fa fa-print"></i></span>&nbsp;&nbsp;&nbsp;
						<span class="pdf-mail" style="font-size:20px; cursor:pointer;" data-emailtemplate-log="<?php echo $history['email']['emailtemplate_log_id']; ?>"><i class="fa fa-file-pdf-o"></i></span>&nbsp;&nbsp;&nbsp;
					<? } ?>
				</td>	
				<td class="left">
					<span <?if ($history['manager']) { ?> class="history_manager" <? } ?>><?php echo $history['manager']; ?></span>
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
<div id="mailpreview"></div>
<script>
	$(document).ready(function(){
		$('.view-mail').click(function(){
			$.ajax({
				url: 'index.php?route=module/emailtemplate/fetch_log&token=<?php echo $token; ?>&id=' +  $(this).attr('data-emailtemplate-log'),
				dataType: 'json',
				success : function(json){
					$('#mailpreview').html(json.html).dialog({width:800, modal:true,resizable:true,position:{my: 'center', at:'center top', of: window}, closeOnEscape: true})				
				}
			})	
		});	
		
		
		$('.print-mail').click(function(){
			window.open('index.php?route=module/emailtemplate/fetch_log&output=html&order_id=<? echo $order_id; ?>&token=<?php echo $token; ?>&id=' +  $(this).attr('data-emailtemplate-log'), '_blank');			
		});		
		
		$('.pdf-mail').click(function(){
			window.open('index.php?route=sale/order/emailhistory2pdf&order_id=<? echo $order_id; ?>&token=<?php echo $token; ?>&id=' +  $(this).attr('data-emailtemplate-log'), '_blank');			
		});		
	})
</script>
<div class="pagination"><?php echo $pagination; ?></div>
