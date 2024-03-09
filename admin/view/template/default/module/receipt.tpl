<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
   <div class="page-header">
      <div class="container-fluid">
         <div class="pull-right">
            <a href="<?php echo $receipt_list; ?>" title="Список замовлень CheckBox" class="btn btn-default"><i class="fa fa-list"></i> Список замовлень CheckBox</a>
            <a onclick="$('#form-account').submit();"    title="<?php echo $button_save; ?>" class="btn btn-primary"><?php echo $button_save; ?></a>
            <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><?php echo $button_cancel; ?></a>
         </div>
         <h1><?php echo $heading_title; ?></h1>
         <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
         </ul>
      </div>
   </div>
   <div class="container-fluid">
      <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
         <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>
      <div class="panel panel-default">
         <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
         </div>
         <div class="panel-body">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-account" class="form-horizontal">
               <div id="tabs" class="htabsf nav nav-tabs">
                  <li><a class="selected" href="#tab-general" data-toggle="tab">Данні для підключення</a></li>
                  <li ><a href="#tab-receipt" data-toggle="tab">Налаштування формування чеку</a></li>
                  <li><a href="#tab-cron" data-toggle="tab">Автоматичне формування чеків (Cron)</a></li>
                  <li><a href="#tab-install" data-toggle="tab">Інструкції</a></li>
                  <li><a href="#tab-integration" data-toggle="tab">Додаткові інтеграції</a></li>
               </div>

               <!-- tab content start -->
               <div class="tab-content">
                  <div class="tab-pane " id="tab-general">
				  			<div class="form-group">
                    <label class="col-sm-2 control-label" for="input-language"> 
                    </label>
                    <div class="col-sm-9">
					<div style="color: #fff;background: #000;font-size: 16px;    display: inline-block;    padding: 5px;">Підключення <b>РОБОЧОЇ</b> каси та касира</div><br>
					
						<span style="font-size: 15px;">
						1. Заповнюємо логін і пароль КАСИРА<br>
						2. Заповнюємо ключ Каси<br>
						3. Режим тестування: <b>НІ</b>
						</span>
						<hr>
						<div style="color: #000;background: yellow;font-size: 16px;    display: inline-block;    padding: 5px;">Підключення тестової каси та тестового касира</div><br>
						
						<span style="font-size: 15px;">
						1. Ознайомитись з 
						<a target="_blank" href="https://docs.google.com/document/d/1kgUAMaRQ0vxN7Out-JXR48r_jKcAMrFEGKYTBbq0xL0/edit">Інструкція по роботі з тестовими даними ВІД Checkbox </a><br>
						2. Заповнюємо логін і пароль <b>ТЕСТОВОГО КАСИРА</b> <br>
						3. Заповнюємо ключ <b>ТЕСТОВОЇ КАСИ</b><br>
						4. Режим тестування: <b>НІ</b><br>
						</span>
						
                    </div>
                </div>
				
                     <div class="panel-heading" style="  border-color: #e8e8e8;   background: #fcfcfc;">
                        <h3 class="panel-title">Дані касира</h3>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Логін касира:</label>
                        <div class="col-sm-10">
                           <input type="text" name="receipt_login" value="<?php echo $receipt_login; ?>" class="form-control"/>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Пароль касира:</label>
                        <div class="col-sm-10">
                           <input type="password" name="receipt_password" value="<?php echo $receipt_password; ?>" class="form-control"/>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Ключ ліцензії каси:</label>
                        <div class="col-sm-10">
                           <input type="text" name="receipt_x_license_key" value="<?php echo $receipt_x_license_key; ?>" class="form-control"/>
                        </div>
                     </div>
                     <div class="form-group hide1">
                        <label class="col-sm-2 control-label">Режим тестування:</label>
                        <div class="col-sm-10">
                           <label class="radio-inline">
                           <?php if ($receipt_is_dev_mode) { ?>
                           <input type="radio" name="receipt_is_dev_mode" value="1" checked="checked" />
                           ТАК
                           <?php } else { ?>
                           <input type="radio" name="receipt_is_dev_mode" value="1" />
                           ТАК
                           <?php } ?>
                           </label>
                           <label class="radio-inline">
                           <?php if (!$receipt_is_dev_mode) { ?>
                           <input type="radio" name="receipt_is_dev_mode" value="0" checked="checked" />
                           НІ
                           <?php } else { ?>
                           <input type="radio" name="receipt_is_dev_mode" value="0" />
                           НІ
                           <?php } ?>
                           </label>
                        </div>
                     </div>
					 <div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-3">
						   <a href="javascript:void(0);"  class="btn btn-default button mini check-connection"><i class="fa fa-refresh"></i> Перевірити доступ</a>
							<span id="connect-info"> </span> 
						</div>
					  </div>
					   <script type="text/javascript"> 
					  
					  $(document).delegate('.check-connection', 'click', function() { 				        	
							var receipt_login = $( "input[name=receipt_login]" ).val();	
							var receipt_password = $( "input[name=receipt_password]" ).val();
							var receipt_x_license_key = $( "input[name=receipt_x_license_key]" ).val();    
							var receipt_is_dev_mode = $( "input[name=receipt_is_dev_mode]:checked" ).val();     
							
							$.ajax({
								url: 'index.php?route=module/receipt/check_connect&token=<?php echo $token; ?>',						
								type: 'POST',
								data: {'receipt_login':receipt_login,'receipt_password':receipt_password,'receipt_x_license_key':receipt_x_license_key,'receipt_is_dev_mode':receipt_is_dev_mode},
								dataType: 'json',
								  beforeSend: function() {
									  $('.alert-danger').remove();
								  },
						  
								success: function(json) { 
									if(json['error']){
											$('#connect-info').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
										}
										
									if (json['success']) {
										$('#connect-info').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <br><b> Не забудьте зберегти зміни!!!</b><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}
										
									 
								}
							});  
							event.preventDefault();
							
							
				
							$('#connect-info').html('');
							 
						  });
					  
					  </script>
			  
                  </div>
                  <div class="tab-pane active" id="tab-receipt">
                     <div class="form-group">
                     </div>
					 <div class="form-group">
						<label class="col-sm-2 control-label" for="input-price_round">Заокруглення як в налаштуваннях валют
						 <span style="    font-weight: 100;    display: block;">використовується стандартне заокруглення (currency->format)</span>
						</label>
						<div class="col-sm-10"> 
						  
						  <select name="receipt_price_format" id="input-price_round" class="form-control">
							
							<?php if ($receipt_price_format) { ?>
							<option value="1" selected="selected">Так</option>
							<option value="0">Ні</option>
							<?php } else { ?>
							<option value="1">Так</option>
							<option value="0" selected="selected">Ні</option>
							<?php } ?>
						   
						  </select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-price_round">Надсилати чек на Email клієнта </label>
						<div class="col-sm-10"> 
						  
						  <select name="receipt_is_customer_send_email" id="input-receipt_is_customer_send_email" class="form-control">
							
							<?php if ($receipt_is_customer_send_email) { ?>
							<option value="1" selected="selected">Так</option>
							<option value="0">Ні</option>
							<?php } else { ?>
							<option value="1">Так</option>
							<option value="0" selected="selected">Ні</option>
							<?php } ?> 
						  </select>
						  Якщо вибрати <b>Ні</b> - підставиться Email адміністратора 
						</div>
					</div>
                     <div class="form-group preview">
                        <label class="col-sm-2 control-label" for="input-language">Мова для товарів </label>
                        <div class="col-sm-10">
                           <select name="receipt_config_language" id="input-language" class="form-control">
                              <?php foreach ($languages as $language) { ?>
                              <?php if ($language['code'] == $receipt_config_language) { ?>
                              <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                              <?php } else { ?>
                              <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                              <?php } ?>
                              <?php } ?>
                           </select>
						    <b>УВАГА!!!</b>
							Якщо товар буде видалено, назва товару буде братись по стандарту (так як на сторінці замовлення)
                        </div>
                     </div>
					 <div class="form-group preview">
						<label class="col-sm-2 control-label" for="input-receipt_limit">Кількість замовлень на сторінці </label>
						<div class="col-sm-10"> 
						  <select name="receipt_limit" id="input-receipt_limit" class="form-control">
						  
						   <?php foreach ($limits as $item_receipt_limit) { ?>
							<?php if ($item_receipt_limit == $receipt_limit) { ?>
							<option value="<?php echo $item_receipt_limit; ?>" selected="selected"><?php echo $item_receipt_limit; ?></option>
							<?php } else { ?>
							<option value="<?php echo $item_receipt_limit; ?>"><?php echo $item_receipt_limit; ?></option>
							<?php } ?>
							<?php } ?> 
						  </select>
						  
						  <b>Рекомендую до 30!!!</b> 
						  
						</div>
					</div>
                     <div class="form-group preview">
                        <div class="col-sm-9">
                           <label class="col-sm-3 control-label">Дані, які будуть сумуватись для знижки    </label>
                           <div class="col-sm-9">
                              <div class="scrollbox"  style="height: 200px;">
                                 <?php $class = 'odd'; ?>
                                 <?php foreach($total_extensions as $total){ ?>
                                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                       <div class="<?php echo $class; ?>">
                                               <input class="checkbox" type="checkbox" name="receipt_codes_for_discounts[]" id="total_discount_<? echo $total['code'];?>" value="<? echo $total['code'];?>" <?php if (!empty($receipt_codes_for_discounts) && in_array($total['code'], $receipt_codes_for_discounts)) echo 'checked="checked"'; ?> />
                                             <label for="total_discount_<? echo $total['code'];?>"><? echo $total['name'];?></label>
                                       </div>
                                 <? } ?>
                              </div>
                           </div>


                          
                        </div>
                        <div class="col-sm-3">
                           <a href="/admin/view/image/Screenshot_67.png" target="_blank">
                           <img src="/admin/view/image/Screenshot_67.png"   class="img-thumbnail">
                           </a>
                        </div>
                     </div>
                     <div class="form-group preview" style="    background: #00000014;">
                        <div class="col-sm-9">
                           <label class="col-sm-3 control-label">Дані, які будуть сумуватись для НАДБАВКИ  </label>
                           <div class="col-sm-9">
                              <div class="scrollbox"  style="height: 200px;">
                                 <?php $class = 'odd'; ?>
                                 <?php foreach($total_extensions as $total){ ?>
                                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                                       <div class="<?php echo $class; ?>">
                                             <input class="checkbox" type="checkbox" name="receipt_codes_for_extrapayment[]" id="total_extrapayment_<? echo $total['code'];?>" value="<? echo $total['code'];?>" <?php  if (!empty($receipt_codes_for_extrapayment) && in_array($total['code'], $receipt_codes_for_extrapayment)) echo 'checked="checked"'; ?> />
                                             <label for="total_extrapayment_<? echo $total['code'];?>"><? echo $total['name'];?></label>
                                       </div>
                                 <? } ?>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-3 control-label" for="input-type_of_extrapayment">Варіанти типу надбавки</label>
                              <div class="col-sm-9">
                                 <select name="receipt_type_of_extrapayment" id="input-type_of_extrapayment" class="form-control">
                                    <option value="0" <?php if ($receipt_type_of_extrapayment == 0) { ?> selected="selected"  <?php } ?>>Не враховувати надбавки</option> 
                                    <option value="2" <?php if ($receipt_type_of_extrapayment == 2) { ?> selected="selected"  <?php } ?>>Додати додаткову позицію у чеку</option>
                                 </select>
                              </div>
                           </div>
                           <label class="col-sm-3 control-label">Назва для додаткової позиції: </label>
                           <div class="col-sm-9">
                              <input type="text" name="receipt_text_for_extrapayment" value="<?php echo $receipt_text_for_extrapayment; ?>" class="form-control"/>
                           </div>
                        </div>
                        <div class="col-sm-3">
                           <a href="/admin/view/image/Screenshot_69.png" target="_blank">
                           <img src="/admin/view/image/Screenshot_69.png"   class="img-thumbnail">
                           </a>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-language">Тип оплати по замовчуванню
                        </label>
                        <div class="col-sm-10">
                           <select name="receipt_payment_type" id="input-language" class="form-control">
                              <?php foreach ($payment_types as $payment) { ?>
                              <?php if ($payment['type'] == $receipt_payment_type) { ?>
                              <option value="<?php echo $payment['type']; ?>" selected="selected"><?php echo $payment['name']; ?></option>
                              <?php } else { ?>
                              <option value="<?php echo $payment['type']; ?>"><?php echo $payment['name']; ?></option>
                              <?php } ?>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     <div class="form-group " style="background: #0f188414;">
                        <div class="form-group " >
                           <label class="col-sm-2 control-label" for="input-payment_condition">Логіка вибірки
                           </label>
                           <div class="col-sm-10">
                              <select name="receipt_cash_payment_condition" id="input-payment_condition" class="form-control">
                                 <?php if ($receipt_cash_payment_condition == "AND" ) { ?>
                                 <option value="AND" selected="selected">I (AND)</option>
                                 <option value="OR">АБО (OR)</option>
                                 <?php } else { ?>
                                 <option value="AND">I (AND)</option>
                                 <option value="OR" selected="selected">АБО (OR)</option>
                                 <?php } ?>
                              </select>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-2 control-label" for="input-category">Cash - готівковий розрахунок:
                           <span style="    font-weight: 100;    display: block;">При яких подіях використовувати тип оплати - Готівка</span>
                           </label>
                           <div class="col-sm-4">
                              <div class="well well-sm" style="min-height: 100px;max-height: 500px;overflow: auto;">
                                 <table class="table table-striped">
                                    <tr>
                                       <td class="checkbox">
                                          <label>
                                          --- При Оплаті ---
                                          </label>
                                       </td>
                                    </tr>
                                    <?php foreach ($all_payments as $item) { ?>
                                    <tr>
                                       <td class="checkbox">
                                          <label>
                                          <?php if ($receipt_cash_payments && in_array($item['code'], $receipt_cash_payments)) { ?>
                                          <input type="checkbox" name="receipt_cash_payments[]" value="<?php echo $item['code']; ?>" checked="checked" />
                                          <?php echo $item['name']; ?> [<?php echo $item['code']; ?>]
                                          <?php } else { ?>
                                          <input type="checkbox" name="receipt_cash_payments[]" value="<?php echo $item['code']; ?>" />
                                          <?php echo $item['name']; ?> [<?php echo $item['code']; ?>]
                                          <?php } ?>
                                          </label>
                                       </td>
                                    </tr>
                                    <?php } ?>
                                 </table>
                              </div>
                              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);">Выделить всё</a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);">Снять выделение</a>
                           </div>
                           <div class="col-sm-4">
                              <div class="well well-sm" style="min-height: 100px;max-height: 500px;overflow: auto;">
                                 <table class="table table-striped">
                                    <tr>
                                       <td class="checkbox">
                                          <label>
                                          --- При доставці ---
                                          </label>
                                       </td>
                                    </tr>
                                    <?php foreach ($all_shippings as $item) { ?>
                                    <tr>
                                       <td class="checkbox">
                                          <label>
                                          <?php if ($receipt_cash_shippings && in_array($item['code'], $receipt_cash_shippings)) { ?>
                                          <input type="checkbox" name="receipt_cash_shippings[]" value="<?php echo $item['code']; ?>" checked="checked" />
                                          <?php echo $item['name']; ?> [<?php echo $item['code']; ?>]
                                          <?php } else { ?>
                                          <input type="checkbox" name="receipt_cash_shippings[]" value="<?php echo $item['code']; ?>" />
                                          <?php echo $item['name']; ?> [<?php echo $item['code']; ?>]
                                          <?php } ?>
                                          </label>
                                       </td>
                                    </tr>
                                    <?php } ?>
                                 </table>
                              </div>
                              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);">Выделить всё</a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);">Снять выделение</a>
                           </div>
                        </div>
                     </div>

                     <hr>

                  </div>
                  <div class="tab-pane" id="tab-cron">
                     <fieldset>

                      <div class="form-group">
                           <label class="col-sm-3 control-label" for="input-language">Відправляти в ТГ стату за день
                           </label>
                           <div class="col-sm-9">
                              <select name="receipt_tg_send_alerts" id="input-receipt_tg_send_alerts" class="form-control">                  
                                 <?php if ($receipt_tg_send_alerts) { ?>
                                    <option value="1" selected="selected">Так</option>
                                    <option value="0">Ні</option>
                                 <?php } else { ?>
                                    <option value="1">Так</option>
                                    <option value="0" selected="selected">Ні</option>
                                 <?php } ?> 
                              </select>
                           </div>
                        </div>

                         <div class="form-group">
                        <label class="col-sm-2 control-label">Токен бота</label>
                        <div class="col-sm-10">
                           <input type="text" name="receipt_tg_bot_token" value="<?php echo $receipt_tg_bot_token; ?>" class="form-control"/>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Айді групи</label>
                        <div class="col-sm-10">
                           <input type="text" name="receipt_tg_bot_group_id" value="<?php echo $receipt_tg_bot_group_id; ?>" class="form-control"/>
                        </div>
                     </div>
					
                        <div class="form-group">
                           <label class="col-sm-3 control-label" for="input-language">Статус замовлення, при якому автоматично буде створюватись чек
                           </label>
                           <div class="col-sm-9">
                              <select name="receipt_order_status_id" id="input-language" class="form-control">
                                 <?php foreach ($order_statuses as $order_status) { ?>
                                 <?php if ($order_status['order_status_id'] == $receipt_order_status_id) { ?>
                                 <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                 <?php } else { ?>
                                 <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                 <?php } ?>
                                 <?php } ?>
                              </select>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-3 control-label" for="input-language">Тип оплати, при якому автоматично буде створюватись чек
                           </label>
                           <div class="col-sm-9">
                              <select name="receipt_cron_order_payment_code" id="input-language" class="form-control">
                                 <option value="0">Не враховувати оплати</option>
                                 <?php foreach ($all_payments as $cron_order_payment) { ?>
                                 <?php if ($cron_order_payment['code'] == $receipt_cron_order_payment_code) { ?>
                                 <option value="<?php echo $cron_order_payment['code']; ?>" selected="selected"><?php echo $cron_order_payment['name']; ?> [<?php echo $cron_order_payment['code']; ?>]</option>
                                 <?php } else { ?>
                                 <option value="<?php echo $cron_order_payment['code']; ?>"><?php echo $cron_order_payment['name']; ?> [<?php echo $cron_order_payment['code']; ?>]</option>
                                 <?php } ?>
                                 <?php } ?>
                              </select>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-3 control-label" for="input-language">Умови вибірки
                           </label>
                           <div class="col-sm-9">
                              1. Статус, що вибрано вище <br>
                              2. Оплата, що вибрано вище (якщо вибрано)<br>
                              3. Дата зміни замовлення = сьогодні + вчора (для замовлень, які були реалізовані в 23:59) <br>
                              4. Чек ще не сторений <br>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-3 control-label" for="input-cron-path">Шлях до cron</label>
                           <div class="col-sm-9">
							<textarea rows="1" id="input-cron-path" class="form-control" readonly="readonly" disabled="disabled"><?php echo $cron_path; ?></textarea>
                           </div>
                        </div>
				<div class="panel-heading" style="  border-color: #e8e8e8;   background: #fcfcfc;">
                        <h3 class="panel-title"><b>Автоматичне відкриваття зміни кроном</b></h3>
				 </div>
				   <div class="form-group">
                    <label class="col-sm-3 control-label" for="input-language">Чи дозволяти крону автоматично відкривати зміну, якщо вона закрита?
                    </label>
                    <div class="col-sm-9">
						<select name="receipt_cron_auto_shifts_open" id="input-receipt_cron_auto_shifts_open" class="form-control">
						
						<?php if ($receipt_cron_auto_shifts_open) { ?>
						<option value="1" selected="selected">Так</option>
						<option value="0">Ні</option>
						<?php } else { ?>
						<option value="1">Так</option>
						<option value="0" selected="selected">Ні</option>
						<?php } ?> 
					  </select>
					  Якщо вибрати <b>Ні</b> крон буде створювати чеки тільки тоді, коли у нас ВРУЧНУ відкрита зміна.
					   
                    </div>										
                </div>
				
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="input-receipt_cron_auto_shifts_open_hour">Година, після якої автоматичне відкриття зміни кроном буде заблокована
                    </label>
                    <div class="col-sm-9">
					
					<select name="receipt_cron_auto_shifts_open_hour" id="input-receipt_cron_auto_shifts_open_hour" class="form-control">
                    
                     <option value="21" <?php if ($receipt_cron_auto_shifts_open_hour == 21) { ?> selected="selected"  <?php } ?>>21:59 (з 22:00 до 23:59 зміна НЕ відкриється автоматично)</option>  
                     <option value="22" <?php if ($receipt_cron_auto_shifts_open_hour == 22) { ?> selected="selected"  <?php } ?>>22:59 (з 23:00 до 23:59 зміна НЕ відкриється автоматично)</option>  
                     <option value="23" <?php if ($receipt_cron_auto_shifts_open_hour == 23) { ?> selected="selected"  <?php } ?>>23:59 (Без блокування)</option>   
                     </select> 
					   <span style="font-size: 13px;">
						Це налаштування для того, якщо у вас налаштоване автоматичне закриття зміни в 23:55, щоб в 23:56 вона автоматично НЕ відкрилась кроном.<br>
						Також рекомендую при налаштуванні крону НЕ запускати його після 23:45. 
						
						</span>
                    </div>
					
					
                </div>
                     </fieldset>
                  </div>
                  <div class="tab-pane" id="tab-install">
                     <div class="form-group">
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-8">
                           <span class="form-group">
                           Полное описание API можно просмотреть онлайн по адресу<br/>
                           <a target="_blank" href="https://dev-api.checkbox.in.ua/api/redoc">https://dev-api.checkbox.in.ua/api/redoc (ReDoc)</a>
                           <br/>
                           или<br/>
                           <a target="_blank" href="https://dev-api.checkbox.in.ua/api/docs">https://dev-api.checkbox.in.ua/api/docs (Swagger)</a> <br/>
                           </span>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-4 control-label">Інструкції</label>
                        <div class="col-sm-8">
                           <span class="form-group">
                              <a target="_blank" href="https://docs.google.com/document/d/1lr5HFn17EvWdGhl1U2sFCWeGhIsT7oAhOYeEY8m--Hs/edit?fbclid=IwAR1jSQeGYYHZKhS4K0Nazv5cs1lRK0mtg4msEmXr89nBpqhJoq-SR8JSRFk#heading=h.gjdgxs">Інструкція по роботі з порталом Checkbox</</a>  
                              <hr/>
                              <a target="_blank" href="https://docs.google.com/document/d/1Zhkc4OljKjea_235YafVvZunkWSp6TCAKeckhgl8t2w/edit?fbclid=IwAR38Mh4JJmDk4gvATG63qDvE8rZyl5MKjC5m1pgvaF7jj3SKGuPITiaIoYo">Checkbox_Специфікація взаємодії з API</a> <br/>
                           </span>
                        </div>
                     </div>
					  <div class="form-group">
					<label class="col-sm-4 control-label">Відео огляд модуля</label>
					<div class="col-sm-8">
					  <span class="form-group"> 
							 <a href="<?php echo $video_link;?>" target="_blank">версія 10.01.2022</a> 
					   
	 
						  </span>
					</div>
				  </div>
				  
                  </div>

                  <!-- new liqpay tab -->
                  <div class="tab-pane" id="tab-integration">

                    
					 <div class="alert alert-info loader_upper_block">
					   
							
							<div class="form-group preview">
                        <div class="col-sm-8">
                           <label class="col-sm-10 control-label">УВАГА!! Для додаткової інтеграції з платіжними системами, щоб при онлайн оплаті підтягувати дані і передавати в чек - необхідно додаткова платна інтеграція. 
							 <a href="<?php echo $integration_link;?>" target="_blank">Детальніше тут</a> </label>
                            
                        </div>
                        <div class="col-sm-2">
                           <a href="/admin/view/image/Screenshot_72.png" target="_blank">
                           <img src="/admin/view/image/Screenshot_72.png" class="img-thumbnail" style="    max-height: 200px;">
                           </a>
                        </div>
                     </div>
						  
					</div>
					<div class="panel-heading hide">
                        <h3 class="panel-title"><b>Liqpay</b></h3>
                     </div>
                     <div class="form-group hide">
                        <label class="col-sm-4 control-label">"label" <--> "текст Картка"</label>
                        <div class="col-sm-6">
                           <input type="text" name="receipt_payment_label_text" value="<?php echo $receipt_payment_label_text; ?>" class="form-control"/>
                        </div>
                     </div>

                     <div class="form-group hide">
                        <label class="col-sm-4 control-label">"payment_system" <--> "текст liqpay"</label>
                        <div class="col-sm-6">
                           <input type="text" name="receipt_payment_system_text" value="<?php echo $receipt_payment_system_text; ?>" class="form-control"/>
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="col-sm-4 control-label">Статичний текст в чеку в розіділі "Службова інформація"</label>
                        <div class="col-sm-6">
                          <textarea name="receipt_footer_text" cols="40" rows="5" class="form-control"><?php echo $receipt_footer_text; ?></textarea>

                        </div>
                     </div>
                     

 
                      
                  </div>
                  <!-- end new liqpay tab -->


               </div>
               <!-- tab content end -->



            </form>
         </div>
      </div>
   </div>
</div>
<link type="text/css" href="view/stylesheet/checkbox/bootstrap.css" rel="stylesheet" />
<script type="text/javascript"><!--
   $('#tabs a').tabs();  
   
   $(document).delegate('.alert button.close', 'click', function() {
	   $(this).parent().remove();
	});
   //-->
</script>
<style type="text/css">
   .form-group {
   padding-top: 15px;
   padding-bottom: 15px;
   margin-bottom: 0;
   }


.nav > li > a {
    position: relative;
    display: block;
    padding: 10px 15px;
}


.nav-tabs > li > a {
    margin-right: 2px;
    line-height: 1.42857;
    border: 1px solid transparent;
    border-radius: 3px 3px 0 0;
}

   .nav-tabs > li > a {
    color: #666;
    border-radius: 2px 2px 0 0;
}
#tabs a.selected{
	    background: whitesmoke;
}
</style>
<?php echo $footer; ?>