		<div class="content">
				<table class="list">
					<tbody>						
						<?php if ($customers) { ?>
						<tr>
							<td class="left" colspan="9">
								<b>Всего <? echo $count; ?> клиентов попадают под заданные условия.</b>
							</td>
						<tr>
							<?php foreach ($customers as $customer) { ?>
								<tr>
									<td class="left">
										<? if ($customer['gender'] == 0) { ?>
											<i class='fa fa-transgender' style='color:green; font-size:16px;'></i>&nbsp;	
											<? } elseif ($customer['gender'] == 1) { ?>
											<i class='fa fa-male' style='color:#2c82b8; font-size:16px;'></i>&nbsp;	
											<? } elseif ($customer['gender'] == 2) { ?>
											<i class='fa fa-female' style='color:#d9534f; font-size:16px;'></i>&nbsp;	
										<? } ?>
										
										<? if ($customer['is_mudak']) { ?>
											<i class='fa fa-ambulance' style='color:red; font-size:16px;'></i>&nbsp;					
											<?php echo $customer['name']; ?>
											<? } else { ?>
											<?php echo $customer['name']; ?>
										<? } ?>
										<br />	<? if ($customer['segments']) { ?>
											<? foreach ($customer['segments'] as $_segment) { ?>
												<span style="display:inline-block; padding:3px 4px; font-size:10px; margin:3px; background:#<? if ($_segment['bg_color']) { ?><? echo $_segment['bg_color'] ?><? } else { ?>e6e9f3<? } ?>; color:#<? if ($_segment['txt_color']) { ?><? echo $_segment['txt_color'] ?><? } else { ?>696969<? } ?>">
													<? if ($_segment['fa_icon']) { ?>
														<i class="fa <? echo $_segment['fa_icon']; ?>" aria-hidden="true"></i>&nbsp;
													<? } ?><?php echo $_segment['name']; ?>&nbsp;
												</span>
											<? } ?>
										<? } ?><br />
									Карта: <b><?php echo $customer['discount_card']?$customer['discount_card']:'Нет'; ?></b></td>
									<td><span class="load_order_history" data-customer-id="<?php echo $customer['customer_id']; ?>" style="cursor:pointer; border-bottom:1px dashed grey;">
									Заказов <? echo $customer['order_good_count']; ?> / <? echo $customer['order_count']; ?></span>
									<? if ($customer['order_count']) { ?><br /><span style="padding:2px; background:#ecf3e6; display:inline-block; margin-top:2px;">Σ <? echo (int)$customer['total_cheque']; ?>&nbsp;<? echo $customer['currency']; ?><? } ?></span><? if ($customer['order_count']) { ?><br /><span style="padding:2px; background:#e6e9f3; display:inline-block; margin-top:2px;">СР <? echo (int)$customer['avg_cheque']; ?>&nbsp;<? echo $customer['currency']; ?><? } ?></span>								
									</td>
									<td class="left"><?php echo !in_array($customer['birthday'], array('30.11.-0001'))?$customer['birthday']:''; ?></td>
									<td class="left"><?php echo $customer['email']; ?>
										<br /><br /><span style="font-size:10px;"><? echo $customer['mail_status']; ?> OP: <? echo $customer['mail_opened'];  ?> CL: <? echo $customer['mail_clicked'];  ?></span>
										&nbsp;&nbsp;<i class="fa fa-bell" aria-hidden="true" style="color: <? if ($customer['has_push']) { ?>#4ea24e;<? } else { ?>#cf4a61;<? } ?>"></i>
									</td>
									
									<td>
										<? if ($customer['phone']) { ?>
											<?=$customer['phone'] ?><span class='click2call' data-phone="<?=$customer['phone'] ?>"></span>
										<? } ?>
										<? if ($customer['fax']) { ?>
											<br /><?=$customer['fax'] ?><span class='click2call' data-phone="<?=$customer['fax'] ?>"></span>
										<? } ?>
										<? if ($customer['total_calls']) { ?>
											<br /><span class="load_call_history" data-customer-id="<?php echo $customer['customer_id']; ?>" style="cursor:pointer; border-bottom:1px dashed grey;">
											Звонков: <? echo $customer['total_calls']; ?></span>	
										<? } ?>
									</td>
									
									<td class="left"><?php echo $customer['customer_group']; ?><br /><?php echo $customer['status']; ?> / <?php echo $customer['approved']; ?></td>
									<td class="left"><? if ($customer['country']) { ?><img src="<? echo HTTPS_IMAGE ?>flags/<? echo mb_strtolower($customer['country']) ?>.png" /><? } ?>&nbsp;&nbsp;<span style="font-size:10px;"><?php echo $customer['city']; ?></span></td>
									<td class="left" style="font-size:10px;"><?php echo $customer['ip']; ?><br /><?php echo $customer['source']; ?><br /><?php echo $customer['date_added']; ?></td>							
									<td class="right">
										<?php foreach ($customer['action'] as $action) { ?>											
											<a class='button' href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
										<?php } ?></td>
								</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="10">Пустой результат!</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
		</div>
<div id="mailpreview"></div>
<script>
	$('.load_order_history').click(function(){
		$.ajax({
			url: 'index.php?route=sale/order&ajax=1&limit=200&token=<?php echo $token; ?>&filter_customer=' +  $(this).attr('data-customer-id'),
			dataType: 'html',
			success : function(html){
				$('#mailpreview').html(html).dialog({width:900, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
			}
		})	
	});	
	$('.load_call_history').click(function(){
		$.ajax({
			url: 'index.php?route=sale/customer/callshistory&ajax=1&limit=200&token=<?php echo $token; ?>&customer_id=' +  $(this).attr('data-customer-id'),
			dataType: 'html',
			success : function(html){
				$('#mailpreview').html(html).dialog({width:900, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
			}
		})	
	});	
		$(document).ready(function(){
		$('.go_to_store').on('click', function(){
			var store_id = $(this).attr('data-store-id');
			var customer_id = $(this).attr('data-customer-id');
			
			swal({ title: "Перейти в магазин?", text: "В личный кабинет покупателя", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, перейти!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() {
				window.open('index.php?route=sale/customer/login&token=<?php echo $token; ?>&customer_id='+customer_id+'&store_id=' + store_id)
			});
			
		});
		
	});
</script>