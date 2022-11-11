<style>
	.user_id_span, .span_priority {
	padding:4px;
	display:inline-block;
	cursor:pointer;	
	}		
	
	.user_id_span.active {
	color:white;
	background-color:#85B200;
	}
	
	.to_supplier_span.active {
	color:white;
	border-bottom:1px dashed white;
	background-color:#F96E64;
	}
	
	.span_priority.p_red.active{
	color:white;
	background-color:#CC0000;
	}
	
	.span_priority.p_orange.active{
	color:white;
	background-color:#FF7400;
	}
	
	.span_priority.p_green.active{
	color:white;
	background-color:#008C00;	
	}
	
	.select_date_hm{
	padding:6px 10px;
	}
	
	td.right{
	text-align:right;
	}
	
	table.big_font tr td {
	font-size:16px;
	}
</style>
<script>
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});	
	
	$('.span_priority').click(function(){
		$('.span_priority').removeClass('active');	
		$('#priority').val($(this).attr('data-priority'));
		$(this).addClass('active');
	});
	
	
	//привязка самому себе
	$('#user_id_span').click(function(){
		$('.user_id_span').removeClass('active');		
		$(this).addClass('active');
		$('input#user_id').val('<? echo $this->user->getID(); ?>');
		$('input#user_group_id').val('0');
		$('input#user_group_select option[value=0]').attr('selected','selected');
		$('input#user_id_select').val('0');
	});
	
	//привязка комусь
	$('#user_id_select').change(function(){ 
		var _val = $(this).val();
		//my
		var _mes = $('#user_id_span');
		//group select
		var _ugs = $('#user_group_select_span');
		//user select
		var _uis = $('#user_id_select_span');
		if (parseInt(_val) == 0){
			$('.user_id_span').removeClass('active');		
			_mes.addClass('active');			
			$('input#user_group_id').val('0');
			$('input#user_id').val('<? echo $this->user->getID(); ?>');
			} else {
			$('.user_id_span').removeClass('active');
			_uis.addClass('active');
			$('input#user_id').val($(this).val());
			$('input#user_group_id').val('0');
		}
	});
	
	//привязка группе
	$('#user_group_select').change(function(){ 
		var _val = $(this).val();
		//my
		var _mes = $('#user_id_span');
		//group select
		var _ugs = $('#user_group_select_span');
		//user select
		var _uis = $('#user_id_select_span');
		if (parseInt(_val) == 0){
			$('.user_id_span').removeClass('active');		
			_mes.addClass('active');			
			$('input#user_group_id').val($(this).val());
			$('input#user_id').val('<? echo $this->user->getID(); ?>');
			} else {
			$('.user_id_span').removeClass('active');
			_ugs.addClass('active');
			$('input#user_group_id').val($(this).val());
			$('input#user_id').val('0');
		}
	});
	
	//время
	$('#date_at_date, #date_at_hour, #date_at_minute').on('change', function(){
		$('#date_at').val($('#date_at_date').val() + ' ' + $('#date_at_hour').val() +':'+$('#date_at_minute').val());
	});
	
	$('#date_max_date, #date_max_hour, #date_max_minute').on('change', function(){
		$('#date_max').val($('#date_max_date').val() + ' ' + $('#date_max_hour').val() +':'+$('#date_max_minute').val());
	});
	
	$('#btn_add_new_task').click(function(){
		$.ajax({
			url : 'index.php?route=user/ticket/add_ticket&token=<? echo $token; ?>',
			type: 'POST',
			dataType : 'text',
			data : {
				user_id : $('input#user_id').val(),
				user_group_id : $('input#user_group_id').val(),
				priority : $('input#priority').val(),
				date_at : $('input#date_at').val(),
				date_max : $('input#date_max').val(),
				message : $('textarea#message').val(),
				entity_id : $('input#entity_id').val(),
				entity_type : $('input#entity_type').val(),
				entity_string : $('input#entity_string').val(),
				is_recall : $('input#is_recall').val(),
			},
			success : function(text){
				$('#add_task_dialog').dialog('destroy');
				swal('Задача '+ text +' поставлена', "Продолжайте упорно трудиться:)", "success");
			},
			error : function(error){
				swal('Что-то пошло не так!', error, "error");
				console.log(error);
			}			
		});		
	});
</script>
<div style="border-bottom: 1px solid #ccc; margin-top:5px; margin-bottom:5px;"></div>
<table class="form big_font" style="width:100%;">
	<tr>
		<td class="right" style="width:1px; word-wrap:none; white-space: nowrap;">
			<span class="t_selectedblue"><i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;Исполнитель</span> 
		</td>
		<td>
			<input type="hidden" name="user_id" id="user_id" value="<? echo $this->user->getID(); ?>" />
			<input type="hidden" name="user_group_id" id="user_group_id" value="0" />
			
			<span class="user_id_span active" id="user_id_span" data-value='<? echo $this->user->getID(); ?>'>Моя задача</span>&nbsp;&nbsp;&nbsp;
			
			<span class="user_group_select_span" data-value=''>
				<span class="user_id_span" id="user_group_select_span"><i class="fa fa-users" aria-hidden="true"></i></span>
				<select name="user_group_select" id="user_group_select">
					<option value="0">Без группы</option>
					<? foreach ($user_groups as $user_group) { ?>
						<option value="<? echo $user_group['user_group_id']; ?>"><? echo $user_group['name']; ?></option>
					<? } ?>
				</select>
			</span>&nbsp;&nbsp;&nbsp;
			
			<span class="user_id_select_span" data-value=''>
				<span class="user_id_span" id="user_id_select_span"><i class="fa fa-user-circle" aria-hidden="true"></i></span>
				<select name="user_id_select" id="user_id_select">
					<option value="0">Без сотрудника</option>
					<? foreach ($users as $user) { ?>
						<option value="<? echo $user['user_id']; ?>"><? echo $user['firstname'] . ' ' . $user['lastname']; ?></option>
					<? } ?>
				</select>
			</span>
			<span class="help">кому будет поставлена задача: вам, группе, либо конкретному сотруднику</span>
		</td>
		
	</tr>
	<tr>
		<td class="right" style="width:1px; word-wrap:none; white-space: nowrap;">
			<span class="t_selectedorange"><i class="fa fa-hourglass-half"></i>&nbsp;Приоритет</span> 
		</td>
		<td>
			<input type="hidden" name="priority" id="priority" value="orange" />
			<span class="span_priority p_red" data-priority="red"><i class="fa fa-hourglass-o"></i> высокий</span>
			<span class="span_priority p_orange active" data-priority="orange"><i class="fa fa-hourglass-half"></i> средний</span>
			<span class="span_priority p_green" data-priority="green"><i class="fa fa fa-hourglass"></i> низкий</span>
			<span class="help">приоритетность, либо срочность задачи: высокий, средний, низкий</span>
		</td>
	</tr>
	<tr>
		<td class="right" style="width:1px; word-wrap:none; white-space: nowrap;">
			<span class="t_selectedorange"><i class="fa fa-clock-o"></i>&nbsp;Время</span> 
		</td>
		<td>
			<input type="hidden" name="date_at" id="date_at" value="<? echo date('Y-m-d H:0', strtotime("+1 hour")); ?>" />			
			<input type="text" class="date" id="date_at_date" value="<? echo date('Y-m-d'); ?>" style="width:100px;" /> в 
			<select id="date_at_hour" class="select_date_hm"><? for ($i = 0; $i <= 23; $i+=1) { ?><option value="<? echo $i; ?>" <? if ($i == (date('H')+1)) { ?>selected="selected"<? } ?>><? echo $i; ?></option><? } ?></select> : 
			<select id="date_at_minute" class="select_date_hm"><? for ($i = 0; $i <= 45; $i+=15) { ?><option value="<? echo $i; ?>" <? if ($i == 0) { ?>selected="selected"<? } ?>><? echo ($i==0)?'00':$i; ?></option><? } ?></select>
			<span class="help">время, когда нужно выполнить задачу</span>
		</td>
	</tr>
	<tr>
		<td class="right" style="width:1px; word-wrap:none; white-space: nowrap;">
			<span class="t_selectedbrown"><i class="fa fa-clock-o"></i>&nbsp;Конечный срок</span> 
		</td>
		<td>
			<input type="hidden" name="date_max" id="date_max" value="<? echo date('Y-m-d H:0', strtotime("+1 hour")); ?>" />			
			<input type="text" class="date" id="date_max_date" value="<? echo date('Y-m-d'); ?>" style="width:100px;" /> в 
			<select id="date_max_hour" class="select_date_hm"><? for ($i = 0; $i <= 24; $i+=1) { ?><option value="<? echo $i; ?>" <? if ($i == (date('H')+1)) { ?>selected="selected"<? } ?>><? echo $i; ?></option><? } ?></select> : 
			<select id="date_max_minute" class="select_date_hm"><? for ($i = 0; $i <= 45; $i+=15) { ?><option value="<? echo $i; ?>" <? if ($i == 0) { ?>selected="selected"<? } ?>><? echo ($i==0)?'00':$i; ?></option><? } ?></select>
			<span class="help">крайний срок выполнения задачи</span>
		</td>
	</tr>
	<tr>
		<td class="right" style="width:1px; word-wrap:none; white-space: nowrap;">
			<span class="t_selectedblue"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i>&nbsp;Привязка</span>
		</td>
		<td style="font-size:16px;">
			<? if ($this_is_customer) { ?>
			
				<input type="hidden" id="entity_id" value="<? echo $customer_id; ?>" />
				<input type="hidden" id="entity_type" value="customer" />
				<input type="hidden" id="entity_string" value="" />
				
				Клиент <? echo $customer_name; ?>, номер телефона <? echo $telephone; ?>&nbsp;&nbsp;<input type="checkbox" class="checkbox" id="is_recall" name="is_recall" value="1" /><label for="is_recall"><i style="font-size:20px;" class="fa fa-phone-square" aria-hidden="true"></i></label>
				
				<? } elseif ($this_is_order) { ?>
				
				<input type="hidden" id="entity_id" value="<? echo $order_id; ?>" />
				<input type="hidden" id="entity_type" value="order" />
				<input type="hidden" id="entity_string" value="" />
				
				Заказ <? echo $order_id; ?>, клиент <? echo $customer_name; ?>, номер телефона <? echo $telephone; ?>&nbsp;&nbsp;<input type="checkbox" class="checkbox" id="is_recall" name="is_recall" value="1" /><label for="is_recall"><i style="font-size:20px;" class="fa fa-phone-square" aria-hidden="true"></i></label>	
				
				<? } elseif ($this_is_call) { ?>
				
				<input type="hidden" id="entity_id" value="" />
				<input type="hidden" id="entity_type" value="call" />
				<input type="hidden" id="entity_string" value="<? echo $telephone; ?>" />
				
				Номер телефона <? echo $telephone; ?>&nbsp;&nbsp;<input type="checkbox" class="checkbox" id="is_recall" name="is_recall" value="1" /><label for="is_recall"><i style="font-size:20px;" class="fa fa-phone-square" aria-hidden="true"></i></label>
			<? } else { ?>
			
				<input type="hidden" id="entity_id" value="0" />
				<input type="hidden" id="entity_type" value="general" />
				<input type="hidden" id="entity_string" value="" />
				<input type="hidden" id="is_recall" value="0" />
				
			<? } ?>
		</td>
	</tr>
	<tr>
		<td class="right" style="width:1px; word-wrap:none; white-space: nowrap;">
			<span class="t_selectedblue"><i class="fa fa-comment" aria-hidden="true"></i>&nbsp;Комментарий</span>
		</td>
		<td>
			<textarea rows="3" name="message" id="message" style="width:70%"></textarea>
		</td>
	</tr>
	<tr>
		<td class="right" style="width:1px; word-wrap:none; white-space: nowrap;">			
		</td>
		<td class="">
			<a class="button" id="btn_add_new_task">Поставить задачу</a>
		</td>
	</tr>
	
</table>