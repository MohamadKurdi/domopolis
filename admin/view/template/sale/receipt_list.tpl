<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
   <div class="page-header">
      <div class="container-fluid">
         <div class="pull-right">
            <a href="<?php echo $receipt_log;?>"  class="btn btn-default" >Журнал запитів</a>
            <a href="<?php echo $extention_setting_link;?>" class="btn btn-default" >Настройки</a>
            <a href="javascript:void(0);" style="   " class="btn   btn-primary   button3   create-receipt" onclick="confirm('Чеки рекомендуємо створювати по одному, для перевірки їх правильності заповнення') ? $('#form-return').submit(): false;"><i class="fa fa-plus"></i> Створити чеки</a>
            <button type="button" data-toggle="tooltip" title="" class="btn btn-danger" formaction="<?php echo $delete; ?>" onclick="confirm('ТІЛЬКИ ДЛЯ ТЕСТОВИХ ЗАМОВЛЕНЬ. Увага, чек видалиться тільки з вашої бази данних. В чекбоксі і в ДПС він буде!!!') ? $('#form-return').attr('action',$(this).attr('formaction')).submit() : false;" > Видалити </button>
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
      <?php if ($success) { ?>
      <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
         <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>
      <div class="panel panel-default">
         <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> Перелік замовлень</h3>
         </div>
         <div class="panel-body">
            <div class="alert alert-info loader_upper_block">
               <span id="shift-info"></span> 
            </div>
            <div class=" sync-receipts-service-work" style="display:none;">
               <div class="row">
                  <div class="col-sm-4 well">
                     <div style="    ">
                        <div class="col-sm-12 text-center">
                           <h4>Створення сервісного чеку внесення або винесення коштів, та його фіскалізація.</h4>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              Сума
                              <input type="text" name="service_value" value="" placeholder="Сума" id="input-return-id" class="form-control">
                           </div>
                        </div>
                        <div class="col-sm-6">
                           <div class="form-group">
                              PaymentType (тільки готівка)
                              <select name="service_type" id="input-type" class="form-control">
                                 <option value="CASH">CASH</option>
                              </select>
                           </div>
                        </div>
                        <div class="  text-center">
                           <div class="form-group">
                              <a type="button" class="sync-receipts-service btn btn-default " data-toggle="tooltip" data-service_operation="IN" ><i class="fa fa-plus-circle"></i> Внести кошти</a>
                              <a type="button" class="sync-receipts-service btn btn-default " data-service_operation="OUT"><i class="fa fa-minus-circle"></i> Видати кошти</a>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-4 wel2l">
                     <div class="table-responsive">
                        <table class="table table-bordered">
                           <thead>
                              <tr>
                                 <td colspan="3"> Останні  чеку (Створенні через API)</td>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach($receipts_history as $history){?>
                              <tr>
                                 <td><?php echo $history['type']; ?> 
                                 </td>
                                 <td><?php echo $history['fiscal_date']; ?>
                                 </td>
                                 <td>  
                                    <a target="_blank" href="<?php echo $history['html_link']; ?>" class="btn btn-default button3 mini show-receipt">-== HTML==></a>
                                    <a target="_blank" href="<?php echo $history['text_link']; ?>" class="btn btn-default button3 mini show-receipt"> -== Text==></a><br>
                                 </td>
                              </tr>
                              <?php } ?>
                           </tbody>
                        </table>
						<div class="alert alert-info">
						Якщо дата відображається за 1970 рік - не хвилюйтесь, в чеку дата відображається коректно!
						</div>
                     </div>
                  </div>
                  <div class="col-sm-4 wel2l">
                     <div class="table-responsive">
                        <table class="table table-bordered">
                           <thead>
                              <tr>
                                 <td colspan="2"> Останні  Z звіти (Створенні через API)</td>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach($shifts_history as $history){?>
                              <tr>
                                 <td><?php echo $history['serial']; ?> 
                                 </td>
                                 <td>  
                                    <a target="_blank" href="<?php echo $history['text_link']; ?>" class="btn btn-default button3 mini show-receipt"> -== Text==></a> 
                                 </td>
                              </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
            <div class="well control-button">
               <div class="pull-right" style="    float: none!important;    text-align: right;
                  ">
                  <a type="button" class="show-service-block btn btn-default " data-toggle="tooltip" title="" data-original-title="Додаткові можливості" ><i class="fa fa-refresh"></i> Додаткові можливості</a>
                  <?php if($current_shifts){ // зміну відкрито ?>
                  <a type="button" class="sync-shift-update btn btn-default " data-toggle="tooltip" title="" data-original-title="Оновити інформацію про відкриту зміну" ><i class="fa fa-refresh"></i> Оновити інформацію про відкриту зміну</a>
                  <a type="button" class="sync-shift-close btn btn-danger  button2 miniв" data-toggle="tooltip" title="" data-original-title="Закрити зміну" >[х] Закрити зміну</a>
                  <? }else{ ?>
                  <a type="button" class="sync-shift-open btn btn-default  button2 mini2" data-toggle="tooltip" title="" data-original-title="Відкрити зміну" >[+]Відкрити зміну</a>
                  <? } ?>	 
               </div>
            </div>
            <div class="well">
               <div class="row">
               	<div class="col-sm-3">
                     <!-- Filter 1 -->
                     <div class="form-group">
                        <label class="control-label" for="input-return-id"><?php echo $entry_fiscal_code; ?></label>
                        <input type="text" name="filter_fiscal_code" value="<?php echo $filter_fiscal_code; ?>" placeholder="<?php echo $entry_fiscal_code; ?>" id="input-return-id" class="form-control" />
                     </div>
                     <!-- Filter 2 -->
                     <div class="form-group">
                        <label class="control-label" for="input-has_receipt">Наявність чеку</label>
                        <select name="filter_has_receipt" id="input-has_receipt" class="form-control">
                           <option value="*"></option>
                           <?php if ($filter_has_receipt) { ?>
                           <option value="1" selected="selected">Є чек</option>
                           <?php } else { ?>
                           <option value="1">Є чек</option>
                           <?php } ?>
                           <?php if (!$filter_has_receipt && !is_null($filter_has_receipt)) { ?>
                           <option value="0" selected="selected">Немає чеку</option>
                           <?php } else { ?>
                           <option value="0">Немає чеку</option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="form-group">
                        <label class="control-label" for="input-order-id">№ Замовлення (order_id)</label>
                        <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="№ Замовлення (order_id)" id="input-order-id" class="form-control" />
                     </div>
                     <div class="form-group">
                        <label class="control-label" for="input-order-status">Статус замовлення</label>
                        <select name="filter_order_status" id="input-order-status" class="form-control">
                           <option value="*"></option>
                           <?php foreach ($order_statuses as $order_status) { ?>
                           <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                           <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                           <?php } else { ?>
                           <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                           <?php } ?>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="form-group">
                        <label class="control-label" for="input-customer">Клієнт (customer)</label>
                        <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="Клієнт (customer)" id="input-customer" class="form-control" />
                     </div>
                     <div class="form-group">
                        <label class="control-label" for="input-has_receipt">Тип оплати</label>
                        <select name="filter_order_payment_code" id="input-order-status" class="form-control">
                           <option value="*"></option>
                           <?php foreach ($all_payments as $payment) { ?>
                           <?php if ($payment['code'] == $filter_order_payment_code) { ?>
                           <option value="<?php echo $payment['code']; ?>" selected="selected"><?php echo $payment['name']; ?></option>
                           <?php } else { ?>
                           <option value="<?php echo $payment['code']; ?>"><?php echo $payment['name']; ?></option>
                           <?php } ?>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="form-group">
                        <label class="control-label" for="input-date-added">Дата створення </label>
                        <div class="input-group date">
                           <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" size="20" />
                           
                        </div>
                     </div>
                     <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
                  </div>

 
               </div>
            </div>
			<div class="pagination"><?php echo $pagination; ?></div>
            <form action="<?php echo $create_orders; ?>" method="post" enctype="multipart/form-data" id="form-return">
               <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                     <thead>
                        <tr>
                           <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                           <td class="text-right"><?php echo $column_fiscal_code; ?>
                           </td>
                           <td class="text-right"><?php if ($sort == 'r.order_id') { ?>
                              <a href="<?php echo $sort_order_id; ?>" class="<?php echo strtolower($order); ?>">№ Замовлення (order_id)</a>
                              <?php } else { ?>
                              <a href="<?php echo $sort_order_id; ?>">№ Замовлення (order_id)</a>
                              <?php } ?>
                           </td>
                           <td class="text-left">
                              Товари
                           </td>
                           <td class="text-left"><?php if ($sort == 'r.date_added') { ?>
                              <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>">Дата створення</a>
                              <?php } else { ?>
                              <a href="<?php echo $sort_date_added; ?>">Дата створення</a>
                              <?php } ?>
                           </td>
                           <td class="text-left"><?php echo $column_status; ?></td>
                           <td class="text-right"></td>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if ($receipts) { ?>
                        <?php foreach ($receipts as $receipt) { ?>
                        <tr>
                           <td class="text-center"><?php if (in_array($receipt['order_id'], $selected)) { ?>
                              <input type="checkbox" name="selected[]" value="<?php echo $receipt['order_id']; ?>" checked="checked" />
                              <?php } else { ?>
                              <input type="checkbox" name="selected[]" value="<?php echo $receipt['order_id']; ?>" />
                              <?php } ?>
                           </td>
                           <td class="text-right"><?php echo $receipt['fiscal_code']; ?></td>
							<td class="text-left">
                              <a href="<?php echo $receipt['order_info_link']; ?>"  >Order <?php echo $receipt['order_id']; ?></a>
                              <br>
                              --------- Дані про замовлення ---------<br>
                              Доставка: <?php echo $receipt['shipping_method']; ?> <br>
                              Оплата: <?php echo $receipt['payment_method']; ?> <br> 
                              Сума замовлення: <b><?php echo $receipt['total']; ?></b><br> 
                              --------- Клієнт --------- <br> 
                              <?php echo $receipt['customer']; ?><br>
                               <i class="fa fa-paper-plane"></i> <?php echo $receipt['email']; ?>                                
                           </td> 
                           <td class="text-right">
                              <?php if($receipt['preview_data']){ ?>
                              <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#000000;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:14px;line-height:16px;table-layout:auto;width:100%;border:none;">
                                 <!-- Products list -->
                                 <tbody>
                                    <?php foreach($receipt['preview_data']['products'] as $key=> $product){ ?>
                                    <tr style="vertical-align: bottom;">
                                       <td style="padding: 5px;padding-bottom:12px;vertical-align: bottom;width: 50%;"> <?php echo $product['name'];?> </td>
                                       <td class="info-grey" style="color: grey; padding: 5px; padding-bottom: 12px; vertical-align: bottom; white-space: nowrap; width: 30%; text-align: right;" width="30%" valign="bottom" align="right"> <?php echo $product['quantity'];?> x <?php echo $product['price'];?> </td>
                                       <td style="padding: 5px; padding-bottom:12px; text-align: right;vertical-align: bottom; white-space: nowrap"> <?php echo $product['total'];?> </td>
                                       <td style="padding: 5px; padding-bottom:12px; white-space: nowrap;width: 7%;">
                                       </td>
                                    </tr>
									<?php $product_left = count($receipt['preview_data']['products']) - ($key+1); ?>
									<?php if($key >= 6 && $product_left){ ?>
									
									<tr style="vertical-align: bottom;">
                                       <td  colspan="4" style="padding: 5px;padding-bottom:12px;vertical-align: bottom;width: 100%;">  
									<a target="_blank" href="<?php echo $receipt['preview']; ?>"  class="btn btn-default button mini"><i class="fa fa-eye"></i> + ще <?php echo $product_left;?> товар/товарів </a> </td>
                                          
                                       </td>
                                    </tr>
									
									
									<?php  
										break;
									} ?>
                                    <?php } ?>
                                    <!-- End of Products list -->
                                    <tr>
                                       <td colspan="4" class="divider-stars" style="color: #161B25; opacity: 0.5; max-width: 100px; overflow: hidden; white-space: nowrap; padding: 0; margin: 0;"> * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * </td>
                                    </tr>
                                    <?php if($receipt['preview_data']['discount_value']){ ?>
                                    <tr>
                                       <td style="padding: 5px" colspan="2"> <?php echo $receipt['preview_data']['discount_name'];?> </td>
                                       <td style="padding: 5px; text-align: right; white-space: nowrap"> <?php echo $receipt['preview_data']['discount_value'];?> </td>
                                       <td style="padding: 5px;"></td>
                                    </tr>
									<tr>
                                            <td colspan="4" class="divider-stars" style="color: #161B25; opacity: 0.5; max-width: 100px; overflow: hidden; white-space: nowrap; padding: 0; margin: 0;"> * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * </td>
                                          </tr>
                                    <?php } ?> 

									 <!-- Payments --> 
										  <tr><td colspan="2" style="padding: 5px  0px;"> <?php echo $receipt['preview_data']['payment_name'];?>  </td><td style="padding: 5px; text-align: right"> <?php echo $receipt['preview_data']['payment_value'];?> </td><td style="padding: 5px; text-align: right"> ГРН </td><td></td></tr>
											<!-- End of Payments -->
											
									<!-- Summary -->										  
								  <tr style="font-weight: bold; font-size: 16px;">
									<td colspan="2" style="padding: 5px;"> Сума </td>
									<td style="padding: 5px; text-align: right; white-space: nowrap"> <?php echo $receipt['preview_data']['payment_value'];?> </td>
									<td style="padding: 5px; text-align: right; white-space: nowrap"> ГРН </td>
								  </tr> 
								  <!-- End of Summary -->
   
                                 </tbody>
                              </table>
                             <? }else{  ?>
                              Чек створено! 
                              <? } ?>  
                           </td>
                           <td class="text-left"><?php echo ($receipt['date_added']); ?>
                              <br> <?php echo ($receipt['order_status']); ?>
                           </td>
                           <td class="text-left">
                              <?php if($receipt['receipt_id']){ ?>
                              <small>
                                 <div>Чек: <?php echo $receipt['is_created_offline']; ?> </div>
                              </small>
                              <small>
                                 <div>Дата створення: <?php echo $receipt['fiscal_date']; ?> </div>
                              </small>
                              <?php if( $receipt['is_sent_dps']){ ?>
                              <small>
                                 <div>Відправлено в ДФС: <?php echo $receipt['sent_dps_at']; ?> </div>
                              </small>
                              <? } ?>
                              <? } ?>			  
                           </td>
                           <td class="text-right action loader_upper_block">
                              <?php if($receipt['receipt_id']){ ?>
                              <a target="_blank" href="<?php echo $receipt['update_link']; ?>" data-receipt_id="<?php echo $receipt['receipt_id']; ?>" class="btn btn-default button2 mini update-receipt"><i class="fa fa-refresh"></i> Оновити інформацію</a><br>
                              <a target="_blank" href="<?php echo $receipt['html_link']; ?>" class="btn btn-default button mini show-receipt">Чек -== HTML ==></a><br>
                              <a target="_blank" href="<?php echo $receipt['pdf_link']; ?>" class="btn btn-default button mini show-receipt">Чек -== pdf ==></a><br>
                              <a target="_blank" href="<?php echo $receipt['text_link']; ?>" class="btn btn-default button mini show-receipt">Чек -== Text==></a><br>
							        <a target="_blank" href="<?php echo $receipt['png_link']; ?>" class="btn btn-default button mini show-receipt">Чек -== PNG==></a><br>
                             <a target="_blank" href="<?php echo $receipt['qrcode_link']; ?>" class="btn btn-default button mini show-receipt">Чек -== QRcode==></a><br>
                              <? }else{ ?>
                              <a href="<?php echo $receipt['preview']; ?>" class="btn   btn-default pull-leftf button2  create-receipt="><i class="fa fa-eye"></i> Попередній перегляд чеку</a><br>
                              <button data-create="<?php echo $receipt['create']; ?>" type="button" class="btn   btn-primary button   create-receipt " ><i class="fa fa-plus"></i> Створити чек</button>
                             <? } ?>
                           </td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                           <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
            </form>
            <div class="pagination"><?php echo $pagination; ?></div>
         </div>
      </div>
   </div>
   <style>
      a.button.mini {
      padding: 2px 6px;
      }
   </style>
   <script type="text/javascript"> 
      var loader = '<div class="loader_block"><img class="loader" src="view/image/loader-search.gif" alt="Загрузка..." title="Загрузка..." /></div>';
      
      $('#shift-info').load('index.php?route=sale/receipt/shift&token=<?php echo $token;?>');
      
	  // well control-button
      <?php if(!$current_shifts){ // зміну відкрито ?> 
      setTimeout(function() {			
      	$('.control-button').load('index.php?route=sale/receipt&token=<?php echo $token;?> .control-button .pull-right', function() {
      		
      	}); 
      }, 100);
      <?php } ?>
       
      $(document).delegate('.sync-shift-update', 'click', function() {
      	$('#shift-info').html(loader);
      	$('#shift-info').load('index.php?route=sale/receipt/shiftUpdate&token=<?php echo $token;?>', function() {$('.loader_block').remove();});
      });
       
      $(document).delegate('.sync-shift-open', 'click', function() {
      	$('#shift-info').html(loader);
      	
      	$('#shift-info').load('index.php?route=sale/receipt/shiftOpen&token=<?php echo $token;?>', function() {
      		$('.loader_block').remove(); 
      		setTimeout(function() {			
      			$('.control-button').load('index.php?route=sale/receipt&token=<?php echo $token;?> .control-button .pull-right', function() {
      				$(".sync-shift-update").trigger('click');
      			}); 
      		}, 3000); 
      	});
      });
      $(document).delegate('.sync-shift-close', 'click', function() {	
      	$('#shift-info').html(loader); 	
      	//control-button
      	
       	$('#shift-info').load('index.php?route=sale/receipt/shiftClose&token=<?php echo $token;?>', function() {
      		$('.loader_block').remove();
      		setTimeout(function() {
      			$('#shift-info').load('index.php?route=sale/receipt/shift&token=<?php echo $token;?>');
      			$('.control-button').load('index.php?route=sale/receipt&token=<?php echo $token;?> .control-button .pull-right');
      		}, 2000);
      		});
      });
       
        $(document).delegate('.show-service-block', 'click', function() {
      	  	$( ".sync-receipts-service-work" ).toggle();  
      	event.preventDefault();
      });  
      
      
      
      $(document).delegate('.sync-receipts-service', 'click', function() {
      	
      	var service_operation = $( this ).data('service_operation');	
      	var service_value = $( "input[name=service_value]" ).val();
      	var service_type = $( "select[name=service_type] option:selected" ).val();	  
      
      	$.ajax({
      		url: 'index.php?route=sale/receipt/receipts_service&token=<?php echo $token; ?>&service_operation='+service_operation+'&service_value='+service_value+'&service_type='+service_type,
      		dataType: 'json',
              beforeSend: function() {
                  $('.alert-danger').remove();
              },
      
      		success: function(json) {
      			console.info(json);
      			if(json['error']){
      					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['message'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						animate2up();
      				}
      				
      			if (json['success']) {
      				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					animate2up();
      			}
      				
      			 
      		}
      	});  
      	event.preventDefault();
      });
      
      
      
      	  
      $('button.create-receipt').click(function() {
		 var button_item =  $(this);        
         $(this).parent().append(loader);
      	 $.ajax({
      		url: $(this).attr('data-create'),
      		dataType: 'json',
      		beforeSend: function() {               
      			button_item.val('loading'); 
      		},
      		complete: function() {
				  button_item.val('Ok'); 
      			$('.loader_block').remove();
              	$(this).attr("disabled", false);
      		},
      		success: function(json) { 
      			 console.log(json);
      			if(json['error']){
      					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['message'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      				  animate2up();
				}
      				
      			if (json['success']) {
      				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                  animate2up();
      			}
      		},
      		error: function(xhr, ajaxOptions, thrownError) {
      			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      			$('.loader_block').remove();
      		}
      	}); 
      	event.preventDefault();
      });
      
      
      
      $('a.update-receipt').click(function() {      
		$(this).parent().append(loader); 

          $.ajax({
              url: $(this).attr('href'),
              dataType: 'json',
              beforeSend: function() {
                  $(this).parent().append(loader) ; 
              },
              complete: function() {
                  $('.loader_block').remove();
              },
              success: function(json) {
                  console.log(json);
                  if(json['error']){
                     $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['message'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                     animate2up();
                  }
      
                  if (json['success']) {
                        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						animate2up();
                  }
                  //$('.loader_block').remove();
                  //$('#form-return .table-responsive').load('index.php?route=sale/receipt&token=<?php echo $token;?> #form-return .table-responsive ');
              },
              error: function(xhr, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                  $('.loader_block').remove();
              }
          });
          event.preventDefault();
      });
      
	function modal_z_report(url){ 
			$('#dialog').remove();
	
			$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="' + url + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
			
			$('#dialog').dialog({
				title: 'Z звіт',			 
				bgiframe: false,
				width: 350,
				height: 400,
				resizable: false,
				modal: false
			});  
      }
        
   </script>
   <script type="text/javascript"><!--
      $('#button-filter').on('click', function() {
      	url = 'index.php?route=sale/receipt&token=<?php echo $token; ?>';
      	
      	var filter_fiscal_code = $('input[name=\'filter_fiscal_code\']').val();
      	
      	if (filter_fiscal_code) {
      		url += '&filter_fiscal_code=' + encodeURIComponent(filter_fiscal_code);
      	}
      	
      	var filter_order_id = $('input[name=\'filter_order_id\']').val();
      	
      	if (filter_order_id) {
      		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
      	}	
      		
      	var filter_customer = $('input[name=\'filter_customer\']').val();
      	
      	if (filter_customer) {
      		url += '&filter_customer=' + encodeURIComponent(filter_customer);
      	}
      	
		var filter_order_status = $('select[name=\'filter_order_status\']').val();
      
      	if (filter_order_status != '*') {
      		url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
      	}
      	var filter_order_payment_code = $('select[name=\'filter_order_payment_code\']').val();
      
      	if (filter_order_payment_code != '*') {
      		url += '&filter_order_payment_code=' + encodeURIComponent(filter_order_payment_code);
      	}
      	var filter_has_receipt = $('select[name=\'filter_has_receipt\']').val();
      
      	if (filter_has_receipt != '*') {
      		url += '&filter_has_receipt=' + encodeURIComponent(filter_has_receipt);
      	}
      	var filter_product = $('input[name=\'filter_product\']').val();
      	
      	if (filter_product) {
      		url += '&filter_product=' + encodeURIComponent(filter_product);
      	}
      
      
      	
      	var filter_date_added = $('input[name=\'filter_date_added\']').val();
      	
      	if (filter_date_added) {
      		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
      	}
      			
      	location = url;
      });
      //-->
   </script> 
   <script type="text/javascript"><!--
      $('input[name=\'filter_customer\']').autocomplete({
      	'source': function(request, response) {
      		$.ajax({
      			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      			dataType: 'json',			
      			success: function(json) {
      				response($.map(json, function(item) {
      					return {
      						label: item['name'],
      						value: item['customer_id']
      					}
      				}));
      			}
      		});
      	},
      	'select': function(item) {
      		$('input[name=\'filter_customer\']').val(item['label']);
      	}	
      });
      
      $('input[name=\'filter_product\']').autocomplete({
      	'source': function(request, response) {
      		$.ajax({
      			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      			dataType: 'json',			
      			success: function(json) {
      				response($.map(json, function(item) {
      					return {
      						label: item['name'],
      						value: item['product_id']
      					}
      				}));
      			}
      		});
      	},
      	'select': function(item) {
      		$('input[name=\'filter_product\']').val(item['label']);
      	}	
      });
 	function animate2up(){
         $('html, body').animate({ scrollTop: 0 }, 'slow');
      }
  $(document).delegate('#content .container-fluid  button.close', 'click', function() {
   $(this).parent().remove();
});
      //-->
   </script> 
   <script type="text/javascript"><!--
      /* $('.date').datetimepicker({
      	pickTime: false
      }); */
      //-->
   </script>
</div> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#input-date-added').datepicker({dateFormat: 'yy-mm-dd'});
	 
});
 
//--></script> 

<link type="text/css" href="view/stylesheet/checkbox/bootstrap.css" rel="stylesheet" />
<style type="text/css">
	a.btn.mini {
    padding: 2px 6px;
}

   .loader_upper_block{
   position: relative;
   }
   .loader_block{
   position: absolute;
   width: 100%;
   height: 100%;
   z-index: 10;
   background: white;
   top: 0;
   left: 0;
   opacity: 0.9;
   margin: 0;
   padding: 0;
   }
   .loader_block .loader {
   width: 16px; 
   margin: 0;
   padding: 0;
   position: absolute;
   top: 50%;
   left: 50%;
   margin-left: -8px;
   margin-top: -5px;
 #shift-info{
      min-height: 50px;
      display: block;
   }
   }
</style>
<?php echo $footer; ?>