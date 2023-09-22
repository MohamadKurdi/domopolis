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
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/customer.png" alt="" /><?php echo $heading_title; ?></h1>			
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
									<input id="had_not_sent_old_alert" class="checkbox" type="checkbox" name="had_not_sent_old_alert" value="1" <?php if ($filter_had_not_sent_old_alert) print 'checked'; ?>>
									<label for="had_not_sent_old_alert" style="color:#ff5656;"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;Не отправляли ручную рассылку</label>
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

						<td class="right"></td>
					</thead>

					<?php if ($customers) { ?>
						<?php foreach ($customers as $customer) { ?>
							<tr>
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

								<td class="left">
									<div>
										<span style="padding:2px; background:#00AD07; display:inline-block; color:white;"><? echo $customer['order_good_first_date']; ?></span>	
									</div>
									<div>
										<span style="padding:2px; background:#00AD07; display:inline-block; color:white;"><? echo $customer['order_good_first_date_diff']; ?></span>	
									</div>
								</td>

								<td class="left">
									<div>
										<span style="padding:2px; background:#ff5656; display:inline-block; color:white;"><? echo $customer['order_good_last_date']; ?></span>
									</div>

									<div>
										<span style="padding:2px; background:#ff5656; display:inline-block;  color:white;"><? echo $customer['order_good_last_date_diff']; ?></span>	
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

								<td class="left" width="300px">
									<?php foreach ($customer['manufacturers'] as $manufacturer) { ?>
										<img src="<?php echo $manufacturer['image']; ?>" title="<?php echo $manufacturer['name']; ?>" style="float:left; margin-right:5px;" />
									<?php } ?>
								</td>

								<td class="right">
										<a class="button" onclick=" swal({title: 'Ссылка для входа без пароля',text: '<? echo $customer['preauth_url']; ?>', html: true,  type: 'info',  customClass: 'swal-wide',  showCancelButton: true,  showConfirmButton:false });"><i class="fa fa-link" aria-hidden="true"></i>
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
		url = 'index.php?route=sale/customer&token=<?php echo $token; ?>';
		
		var filter_name = $('input[name=\'filter_name\']').prop('value');
		
		if (filter_name) {
			url += '&filter_name=' + encodeURIComponent(filter_name);
		}
		
		var filter_email = $('input[name=\'filter_email\']').prop('value');
		
		if (filter_email) {
			url += '&filter_email=' + encodeURIComponent(filter_email);
		}
		
		var filter_phone = $('input[name=\'filter_phone\']').prop('value');
		
		if (filter_phone) {
			url += '&filter_phone=' + encodeURIComponent(filter_phone);
		}
		
		
		var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').children("option:selected").val();
		
		if (filter_customer_group_id != '*') {
			url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
		}
		
		var filter_status = $('select[name=\'filter_status\']').children("option:selected").val();
		
		if (filter_status != '*') {
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}
		
		var filter_gender = $('select[name=\'filter_gender\']').children("option:selected").val();
		
		if (filter_gender != '*') {
			url += '&filter_gender=' + encodeURIComponent(filter_gender);
		}
		
		var filter_mail_status = $('select[name=\'filter_mail_status\']').children("option:selected").val();
		
		if (filter_mail_status != '*') {
			url += '&filter_mail_status=' + encodeURIComponent(filter_mail_status);
		}
		
		var filter_mail_opened = $('input[name=\'mail_opened\']:checked').val();
		
		if (filter_mail_opened !== undefined) {
			url += '&filter_mail_opened=1';
		}
		
		var filter_mail_checked = $('input[name=\'mail_checked\']:checked').val();
		
		if (filter_mail_checked !== undefined) {
			url += '&filter_mail_checked=1';
		}
		
		var filter_push_signed = $('input[name=\'push_sign\']:checked').val();
		
		if (filter_push_signed  !== undefined) {
			url += '&filter_push_signed=1';
		}
		
		var filter_nbt_customer = $('input[name=\'nbt_customer\']:checked').val();
		
		if (filter_nbt_customer  !== undefined) {
			url += '&filter_nbt_customer=1';
		}
		
		var filter_nbt_customer_exclude = $('input[name=\'nbt_customer_exclude\']:checked').val();
		
		if (filter_nbt_customer_exclude  !== undefined) {
			url += '&filter_nbt_customer_exclude=1';
		}
		
		var filter_segment_intersection = $('input[name=\'filter_segment_intersection\']:checked').val();
		
		if (filter_segment_intersection  !== undefined) {
			url += '&filter_segment_intersection=1';
		}
		
		var filter_has_discount = $('input[name=\'filter_has_discount\']:checked').val();
		
		if (filter_has_discount !== undefined) {
			url += '&filter_has_discount=1';
		}
		
		var filter_no_discount = $('input[name=\'filter_no_discount\']:checked').val();
		
		if (filter_no_discount !== undefined) {
			url += '&filter_no_discount=1';
		}
		
		var filter_no_birthday = $('input[name=\'filter_no_birthday\']:checked').val();
		
		if (filter_no_birthday !== undefined) {
			url += '&filter_no_birthday=1';
		}
		
		
		var filter_approved = $('select[name=\'filter_approved\']').children("option:selected").val();
		
		if (filter_approved != '*') {
			url += '&filter_approved=' + encodeURIComponent(filter_approved);
		}
		
		var filter_ip = $('input[name=\'filter_ip\']').prop('value');
		
		if (filter_ip) {
			url += '&filter_ip=' + encodeURIComponent(filter_ip);
		}
		
		var filter_country_id = $('select[name=\'filter_country_id\']').children("option:selected").val();
		
		if (filter_country_id != '*') {
			url += '&filter_country_id=' + encodeURIComponent(filter_country_id);
		}
		
		var filter_source = $('select[name=\'filter_source\']').children("option:selected").val();
		
		if (filter_source != '*') {
			url += '&filter_source=' + encodeURIComponent(filter_source);
		}
		
		var filter_date_added = $('input[name=\'filter_date_added\']').prop('value');
		
		if (filter_date_added) {
			url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
		}
		
		var filter_date_added = $('input[name=\'filter_date_added\']').prop('value');
		
		if (filter_date_added) {
			url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
		}
		
		var filter_last_call = $('input[name=\'filter_last_call\']').prop('value');
		
		if (filter_last_call) {
			url += '&filter_last_call=' + encodeURIComponent(filter_last_call);
		}
		
		var filter_birthday_from = $('input[name=\'filter_birthday_from\']').prop('value');
		
		if (filter_birthday_from) {
			url += '&filter_birthday_from=' + encodeURIComponent(filter_birthday_from);
		}
		
		var filter_birthday_to = $('input[name=\'filter_birthday_to\']').prop('value');
		
		if (filter_birthday_to) {
			url += '&filter_birthday_to=' + encodeURIComponent(filter_birthday_to);
		}
		
		var order_first_date_from = $('input[name=\'order_first_date_from\']').prop('value');
		
		if (order_first_date_from) {
			url += '&order_first_date_from=' + encodeURIComponent(order_first_date_from);
		}
		
		var order_first_date_to = $('input[name=\'order_first_date_to\']').prop('value');
		
		if (order_first_date_to) {
			url += '&order_first_date_to=' + encodeURIComponent(order_first_date_to);
		}
		
		var filter_order_count = $('input[name=\'filter_order_count\']').prop('value');
		
		if (filter_order_count) {
			url += '&filter_order_count=' + encodeURIComponent(filter_order_count);
		}
		
		var filter_order_good_count = $('input[name=\'filter_order_good_count\']').prop('value');
		
		if (filter_order_good_count) {
			url += '&filter_order_good_count=' + encodeURIComponent(filter_order_good_count);
		}
		
		var filter_total_sum = $('input[name=\'filter_total_sum\']').prop('value');
		
		if (filter_total_sum) {
			url += '&filter_total_sum=' + encodeURIComponent(filter_total_sum);
		}
		
		var filter_avg_cheque = $('input[name=\'filter_avg_cheque\']').prop('value');
		
		if (filter_avg_cheque) {
			url += '&filter_avg_cheque=' + encodeURIComponent(filter_avg_cheque);
		}
		
		var filter_interest_brand = $('input[name=\'filter_interest_brand\']').prop('value');
		
		if (filter_interest_brand) {
			url += '&filter_interest_brand=' + encodeURIComponent(filter_interest_brand);
		}
		
		var filter_interest_category = $('input[name=\'filter_interest_category\']').prop('value');
		
		if (filter_interest_category) {
			url += '&filter_interest_category=' + encodeURIComponent(filter_interest_category);
		}
		
		var filter_segment_id = $('input:checkbox:checked.filter_segment_id').map(function(){
		return this.value; }).get().join(",");
		
		if (filter_segment_id) {
			url += '&filter_segment_id=' + encodeURIComponent(filter_segment_id);
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