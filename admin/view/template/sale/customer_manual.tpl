<?php echo $header; ?>
<style>
	.swal-wide{
		width:650px !important;
	}
	.nbt_change{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px;}
	.nbt_change.is_nbt{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px; color:white; background-color:#cf4a61;}
	.rja_change{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px;}
	.rja_change.is_nbt{display:inline-block; cursor:pointer; font-weight:700 !important; padding:3px; color:white; background-color:#cf4a61;}
</style>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if (empty($actiontemplate_id)) { ?>
		<div class="warning">Не задан шаблон для отправки писем!</div>
	<?php } ?>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1 style="width:60%; float:left;">
				<?php echo $heading_title; ?>
			</h1>		
			<div style="width:38%; float:right; text-align: right;">	
				<input id="config_customer_manual_tracking_code" type="text" value="<?php echo $config_customer_manual_test_mode; ?>"/> Трек-код

				<input id="config_customer_manual_test_mode" type="checkbox" class="checkbox" name="config_customer_manual_test_mode" <? if ($config_customer_manual_test_mode){ ?> checked="checked" <? } ?> value="1" /><label for="config_customer_manual_test_mode"></label> Тестовый режим

				<script>
					$('input[name=config_customer_manual_test_mode], input[name=config_customer_manual_tracking_code]').bind('change', function() {
						var key  			= $(this).attr('name');
						var elem 			= $(this);

						if (elem.attr('checked')){
							value = elem.val();
						} else {
							value = 0;
						}

						$.ajax({
							type: 'POST',
							url: 'index.php?route=setting/setting/editSettingAjax&store_id=0&token=<?php echo $token; ?>',
							data: {						
								key: key,
								value: value
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
			</div>
		</div>
		<div style="clear:both;"></div>
		<div class="content">
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<table style="width: 100%;">
					<tbody>
						<tr class="filter f_top">
							<td class="left">
								<p><i class="fa fa-thumbs-up"></i> Количество успешных заказов</p>
								<input type="number" step="1" name="filter_order_good_count" value="<?php echo $filter_order_good_count; ?>" />
							</td>

							<td class="left">
								<p><i class="fa fa-calendar"></i> Последний выполненный заказ от</p>
								<input type="date" step="1" name="order_good_last_date_from" value="<?php echo $order_good_last_date_from; ?>" />
							</td>	

							<td class="left">
								<p><i class="fa fa-calendar"></i> Последний выполненный заказ до</p>
								<input type="date" step="1" name="order_good_last_date_to" value="<?php echo $order_good_last_date_to; ?>" />
							</td>		

							<td class="left">
								<div style="margin-top:5px;">
									<input id="checkbox_14" class="checkbox" type="checkbox" name="filter_simple_email" <? if ($filter_simple_email) { ?>checked="checked"<? } ?> value="1" /> 
									<label for="checkbox_14" style="color:#7F00FF;"><i class="fa fa-envelope" style="color: #7F00FF;"></i>&nbsp;Простая проверка email</label>								
								</div>

								<div style="margin-top:5px;">
									<input id="checkbox_141" class="checkbox" type="checkbox" name="filter_mail_status" <? if ($filter_mail_status && $filter_mail_status != '*') { ?>checked="checked"<? } ?> value="1" /> 
									<label for="checkbox_141" style="color:#FF5656;"><i class="fa fa-envelope" style="color: #FF5656;"></i>&nbsp;Жесткая проверка email на валидность</label>								
								</div>

								<div style="margin-top:5px;">
									<input id="had_not_sent_manual_letter" class="checkbox" type="checkbox" name="had_not_sent_manual_letter" value="1" <?php if ($filter_had_not_sent_manual_letter) print 'checked'; ?>>
									<label for="had_not_sent_manual_letter" style="color:#ff5656;"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;Не отправляли ручную рассылку</label>
								</div>		
							</td>		

							<td class="left">
								<div style="margin-top:5px;">
									<input id="nbt_customer" class="checkbox" type="checkbox" name="nbt_customer" value="1" <?php if ($filter_nbt_customer) print 'checked'; ?>>
									<label for="nbt_customer" style="color:#00AD07;"><i class="fa fa-deaf" aria-hidden="true"></i>&nbsp;Только недозвон</label>
								</div style="margin-top:5px;">

								<div style="margin-top:5px;">
									<input id="nbt_customer_exclude" class="checkbox" type="checkbox" name="nbt_customer_exclude" value="1" <?php if ($filter_nbt_customer_exclude) print 'checked'; ?>>
									<label for="nbt_customer_exclude" style="color:#ff5656;"><i class="fa fa-deaf" aria-hidden="true"></i>&nbsp;Исключить недозвон</label>
								</div>	
							</td>	
						</tr>

						<tr class="filter f_top">
							<td class="left">
								<p><i class="fa fa-user"></i> Имя</p>
								<input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /><br />
							</td>

							<td class="left">
								<p><i class="fa fa-mobile"></i> Телефон</p>
								<input type="text" name="filter_phone" value="<?php echo $filter_phone; ?>" />
							</td>

							<td  class="left">
								<p><i class="fa fa-envelope"></i> E-Mail</p>
								<input type="text" name="filter_email" value="<?php echo $filter_email; ?>" />                
							</td>

							<td></td>

							<td align="right">
								<p>	&#160;</p>
								<a onclick="filter();" class="button">Фильтр</a>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="filter_bord"></div>
				<div class="pagination" style="margin-bottom:5px;"><?php echo $pagination; ?></div>					
				<table class="list">
					<thead>
						<td class="left"><?php if ($sort == 'name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Клиент</a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>">Клиент</a>
							<?php } ?>
						</td>

						<td class="left">
							Телефон
						</td>

						<td class="left">
							Заказы
						</td>

						<td class="left">
							Первый заказ
						</td>

						<td class="left">
							Последний заказ
						</td>							

						<td class="left">
							<i class="fa fa-envelope-o"></i>&nbsp;<?php if ($sort == 'c.email') { ?>
								<a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>">Почта</a>
							<?php } else { ?>
								<a href="<?php echo $sort_email; ?>">Почта</a>
							<?php } ?>
						</td>

						<td class="left">
							Бренды
						</td>

						<td class="left">
							Коллекции
						</td>

						<td class="center" width="50px"></td>
						<td class="center" width="50px"></td>
						<td class="right"></td>
					</thead>

					<?php if ($customers) { ?>
						<?php foreach ($customers as $customer) { ?>
							<tr id="tr-customer-<?php echo $customer['customer_id']; ?>">
								<td class="left">
									<span style="font-weight:400; font-size:20px;">
										<a href="<? echo $customer['customer_href']; ?>" style="font-size:18px;" target="_blank"><?php echo $customer['name']; ?></a>
									</span>
									&nbsp;<span class="add2ticket" data-query="object=customer&object_value=<? echo $customer['customer_id']; ?>"></span>
									<div style="display:inline-block; background-color:#ccc; padding:3px; color:#FFF; margin-left:5px;"><? echo $customer['customer_id']; ?></div>

									<div>
										<? if ($customer['country']) { ?>
											<img src="<? echo HTTPS_IMAGE ?>flags/<? echo mb_strtolower($customer['country']) ?>.png" />
										<? } ?>
										&nbsp;&nbsp;<span style="font-size:10px;"><?php echo $customer['city']; ?></span>
									</div>
								</td>

								<td class="left">
									<? if ($customer['phone']) { ?>
										<div style="font-size:18px"><?php echo $customer['phone'] ?><span class='click2call' data-phone="<?php echo $customer['phone'] ?>"></span></div>
									<? } ?>

									<? if ($customer['fax']) { ?>
										<div><?php echo $customer['fax'] ?><span class='click2call' data-phone="<?php echo $customer['fax'] ?>"></span></div>
									<? } ?>

									<? if ($customer['phone'] || $customer['fax']) { ?>
										<div style="margin-top:8px;">
											<? if ($customer['nbt_customer']) { ?>
												<span class="nbt_change is_nbt" data-customer-id="<?php echo $customer['customer_id']; ?>"><i class="fa fa-deaf" aria-hidden="true"></i> Нет ответа</span>
											<? } else { ?>
												<span class="nbt_change" data-customer-id="<?php echo $customer['customer_id']; ?>"><i class="fa fa-deaf" aria-hidden="true"></i> Нет ответа</span>
											<? } ?>
											<? if ($customer['rja_customer']) { ?>
												<span class="rja_change is_nbt" data-customer-id="<?php echo $customer['customer_id']; ?>"><i class="fa fa-snowflake-o" aria-hidden="true"></i> Отк. адр.</span>
											<? } else { ?>
												<span class="rja_change" data-customer-id="<?php echo $customer['customer_id']; ?>"><i class="fa fa-snowflake-o" aria-hidden="true"></i> Отк. адр.</span>
											<? } ?>
										</div>
									<? } ?>
								</td>


								<td class="left" width="200px;">
									<span class="load_order_history" data-customer-id="<?php echo $customer['customer_id']; ?>" style="cursor:pointer; border-bottom:1px dashed grey;">
										Выполнено <? echo $customer['order_good_count']; ?> из <? echo $customer['order_count']; ?>
									</span>

									<? if ($customer['order_count']) { ?>
										<div>
											<span style="padding:2px; background:#ecf3e6; display:inline-block; margin-top:2px;">Σ <? echo $customer['total_cheque']; ?></span>
											<span style="padding:2px; background:#e6e9f3; display:inline-block; margin-top:2px;">AVG <? echo $customer['avg_cheque']; ?></span>								
										</div>
									<?php } ?>
								</td>

								<td class="left" style="white-space: nowrap;">
									<div>
										<span style="padding:2px; background:#00AD07; display:inline-block; color:white;"><? echo $customer['order_good_first_date']; ?></span>	
									</div>
									<div>
										<span style="padding:2px; background:#00AD07; display:inline-block; font-size:8px; color:white;"><? echo $customer['order_good_first_date_diff']; ?></span>	
									</div>
								</td>

								<td class="left">
									<div>
										<span style="padding:2px; background:#ff5656; display:inline-block; color:white;"><? echo $customer['order_good_last_date']; ?></span>
									</div>

									<div>
										<span style="padding:2px; background:#ff5656; display:inline-block; font-size:8px; color:white;"><? echo $customer['order_good_last_date_diff']; ?></span>	
									</div>
								</td>	

								<td class="left" style="vertical-align:top;">
										<div style="<?php if ($customer['email_bad']) { ?>color:#ff5656<?php } else { ?>color:#00AD07<? } ?>">
											<? echo $customer['email']; ?>

											<?php if ($customer['email_bad']) { ?>
												<i class="fa fa-exclamation-triangle" style="color:#ff5656"></i>
											<?php } else { ?>											
												<i class="fa fa-check-circle" style="color:#00AD07"></i>
											<?php } ?>
										</div>
										
										<div>
											<?php if ($customer['mail_status']) { ?>
												<span style="display:inline-block; padding:3px 4px; font-size:10px; <?php if ($customer['email_bad']) { ?>background:#ff5656; color:#FFF<?php } else { ?>background:#00AD07; color:#FFF<?php } ?>">
													<? echo $customer['mail_status']; ?>
												</span>
											<?php } ?>
											<span style="font-size:10px;">
												Открытий: <? echo $customer['mail_opened']; ?>
											</span>
											<span style="font-size:10px;">
												Кликов: <? echo $customer['mail_clicked']; ?>
											</span>
										</div>															
								</td>

								<td class="left" width="250px">
									<?php foreach ($customer['manufacturers'] as $manufacturer) { ?>
										<img src="<?php echo $manufacturer['image']; ?>" title="<?php echo $manufacturer['name']; ?>" style="float:left; margin-right:5px;" />
									<?php } ?>
								</td>

								<td class="left" width="250px">
									<?php foreach ($customer['collections'] as $manufacturer_name => $collections) { ?>
										<div style="float:left; margin-right:5px; margin-bottom:5px; padding:5px; border:1px solid #ccc; border-radius: 3px;">
											<span style="display:inline-block; padding:3px 4px; font-size:10px; margin-bottom:5px; background:#7F00FF; color:#FFF;"><?php echo $manufacturer_name; ?></span><br />
											<?php foreach ($collections as $collection) { ?>
												<img src="<?php echo $collection['image']; ?>" title="<?php echo $collection['name']; ?>" style="float:left; margin-right:5px;" />
											<?php } ?>
										</div>
									<?php } ?>
								</td>

								<td class="center" width="50px">
									<i class="fa fa-envelope" style="font-size:36px; color:#00AD07; cursor:pointer;" id="btn-send-customer-<?php echo $customer['customer_id']; ?>" data-customer-id="<?php echo $customer['customer_id']; ?>" onclick='swal({ title: "Отправить письмо <?php echo $customer['name']; ?>?", text: "На почту <? echo $customer['email']; ?>", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Отправить", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() { send(<?php echo $customer['customer_id']; ?>, <?php echo $customer['language_id']; ?>) });'></i>
								</td>

								<td class="center" width="50px">
									<i class="fa fa-eye" style="font-size:36px; color:#FF7815; cursor:pointer;" data-customer-id="<?php echo $customer['customer_id']; ?>" onclick="view(<?php echo $customer['customer_id']; ?>, <?php echo $customer['language_id']; ?>)"></i>
								</td>

								<td class="right">
										<a class="button" onclick="swal({title: 'Ссылка для входа без пароля',text: '<? echo $customer['preauth_url']; ?>', html: true,  type: 'info',  customClass: 'swal-wide',  showCancelButton: true,  showConfirmButton:false });"><i class="fa fa-link" aria-hidden="true"></i>
										</a>										

										<a class="button go_to_store" data-customer-id="<?php echo $customer['customer_id']; ?>" data-store-id="<?php echo $customer['store_id']; ?>">
											<i style="font-size:16px; cursor:pointer;" class="fa fa-sign-in"></i>
										</a>

										<?php foreach ($customer['action'] as $action) { ?>											
											<a class='button' href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
										<?php } ?>
									</td>
							</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td class="center" colspan="10"><?php echo $text_no_results; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo $pagination; ?></div>
	</div>
</div>
<div id="mailpreview"></div>
</div>

<script>
	function view(customer_id, language_id){
		$.ajax({
			type: 'POST',
			url: 'index.php?route=catalog/actiontemplate/loadTemplateV2&token=<?php echo $token; ?>',
			data: {
				customer_id: 		customer_id,
				language_id: 		language_id,
				use_seo_urls:       true, 
				actiontemplate_id:  <?php echo $actiontemplate_id; ?>
			},

			dataType: 'html',
			success : function(html){
				$('#mailpreview').html(html).dialog({width:1024, modal:true, title: '<?php echo $actiontemplate_title; ?>', resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
			}
		})	
	}

	function send(customer_id, language_id){
		$.ajax({
			type: 'POST',
			url: 'index.php?route=catalog/actiontemplate/sendMailV2&token=<?php echo $token; ?>',
			data: {
				customer_id: 		customer_id,
				language_id: 		language_id,
				use_seo_urls:       true, 
				actiontemplate_id:  <?php echo $actiontemplate_id; ?>
			},

			dataType: 'html',
			beforeSend: function(){
				$('#btn-send-customer-' + customer_id).removeClass('fa-envelope').addClass('fa-spinner fa-spin');
			},
			success : function(html){
				$('#btn-send-customer-' + customer_id).removeClass('fa-spinner fa-spin').addClass('fa-check');

				if ($('input[name=config_customer_manual_test_mode]').attr('checked') != 'checked'){
					setTimeout(() => {$('#tr-customer-' + customer_id).fadeOut('slow') }, 1000);		
				}		
			},
			error: function(html){
				$('#btn-send-customer-' + customer_id).removeClass('fa-spinner fa-spin').addClass('fa-times text-danger');
			}
		})	
	}

</script>

<script>
$(document).ready(function(){
		$('.go_to_store').on('click', function(){
			var store_id = $(this).attr('data-store-id');
			var customer_id = $(this).attr('data-customer-id');
			
			swal({ title: "Перейти в магазин?", text: "В личный кабинет покупателя", type: "warning", showCancelButton: true,  confirmButtonColor: "#F96E64",  confirmButtonText: "Да, перейти!", cancelButtonText: "Отмена",  closeOnConfirm: true }, function() {
				window.open('index.php?route=sale/customer/login&token=<?php echo $token; ?>&customer_id='+customer_id+'&store_id=' + store_id)
			});
			
		});		
	});

	$('.load_order_history').click(function(){
		$.ajax({
			url: 'index.php?route=sale/order&ajax=1&limit=200&token=<?php echo $token; ?>&filter_customer=' +  $(this).attr('data-customer-id'),
			dataType: 'html',
			success : function(html){
				$('#mailpreview').html(html).dialog({width:900, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true})				
			}
		})	
	});	
	
	function filter() {
		url = 'index.php?route=sale/customer_manual&token=<?php echo $token; ?>';
		
		var filter_order_good_count = $('input[name=\'filter_order_good_count\']').prop('value');		
		if (filter_order_good_count) {
			url += '&filter_order_good_count=' + encodeURIComponent(filter_order_good_count);
		}

		var order_good_last_date_from = $('input[name=\'order_good_last_date_from\']').prop('value');		
		if (order_good_last_date_from) {
			url += '&order_good_last_date_from=' + encodeURIComponent(order_good_last_date_from);
		}

		var order_good_last_date_to = $('input[name=\'order_good_last_date_to\']').prop('value');		
		if (order_good_last_date_to) {
			url += '&order_good_last_date_to=' + encodeURIComponent(order_good_last_date_to);
		}
		
		var filter_simple_email = $('input[name=\'filter_simple_email\']:checked').val();		
		if (filter_simple_email  !== undefined) {
			url += '&filter_simple_email=1';
		} else {
			url += '&filter_simple_email=0';
		}

		var filter_mail_status = $('input[name=\'filter_mail_status\']:checked').val();		
		if (filter_mail_status  !== undefined) {
			url += '&filter_mail_status=delivered';
		} else {
			url += '&filter_mail_status=*';
		}

		var had_not_sent_manual_letter = $('input[name=\'had_not_sent_manual_letter\']:checked').val();		
		if (had_not_sent_manual_letter  !== undefined) {
			url += '&had_not_sent_manual_letter=1';
		} else {
			url += '&had_not_sent_manual_letter=0';
		}

		var filter_nbt_customer = $('input[name=\'filter_nbt_customer\']:checked').val();		
		if (filter_nbt_customer  !== undefined) {
			url += '&filter_nbt_customer=1';
		}

		var nbt_customer_exclude = $('input[name=\'nbt_customer_exclude\']:checked').val();		
		if (nbt_customer_exclude  !== undefined) {
			url += '&nbt_customer_exclude=1';
		}

		var filter_name = $('input[name=\'filter_name\']').prop('value');		
		if (filter_name) {
			url += '&filter_name=' + encodeURIComponent(filter_name);
		}

		var filter_phone = $('input[name=\'filter_phone\']').prop('value');		
		if (filter_phone) {
			url += '&filter_phone=' + encodeURIComponent(filter_phone);
		}

		var filter_email = $('input[name=\'filter_email\']').prop('value');		
		if (filter_email) {
			url += '&filter_email=' + encodeURIComponent(filter_email);
		}
		
		location = url;
	}
</script>
<script>
$(document).ready(function(){
	$(".rja_change").click(function(){
			var _el = $(this);
			var _cid = $(this).attr('data-customer-id');
			$.ajax({
				url : 'index.php?route=sale/customer/setCustomerRJAAjax&customer_id=' + _cid + '&token=<?php echo $token; ?>',
				type : 'GET',
				success : function(i){
					if (i == 1){
						_el.addClass('is_nbt');
						} else {
						_el.removeClass('is_nbt');
					}					
				},
				error : function(e){
					console.log(e);
				}
			});
		});
});
	
$(document).ready(function(){
		$(".nbt_change").click(function(){
			var _el = $(this);
			var _cid = $(this).attr('data-customer-id');
			$.ajax({
				url : 'index.php?route=sale/customer/setCustomerNBTAjax&customer_id=' + _cid + '&token=<?php echo $token; ?>',
				type : 'GET',
				success : function(i){
					if (i == 1){
						_el.addClass('is_nbt');
						} else {
						_el.removeClass('is_nbt');
					}					
				},
				error : function(e){
					console.log(e);
				}
			});
		});
});
</script>
<?php echo $footer; ?>