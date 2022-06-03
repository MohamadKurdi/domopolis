          <table class="list">
            <thead>
              <tr>
                <td class="left" colspan="2"><?php echo $text_order; ?></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left"><?php echo $entry_shipping; ?></td>
                <td class="left"><select name="shipping">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php if ($shipping_code) { ?>
                    <option value="<?php echo $shipping_code; ?>" selected="selected"><?php echo $shipping_method; ?></option>
                    <?php } ?>
                  </select>
                  <input type="hidden" name="shipping_method" value="<?php echo $shipping_method; ?>" />
                  <input type="hidden" name="shipping_code" value="<?php echo $shipping_code; ?>" />
                  <?php if ($error_shipping_method) { ?>
                  <span class="error"><?php echo $error_shipping_method; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td class="left"><?php echo $entry_payment; ?></td>
                <td class="left"><select name="payment">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php if ($payment_code) { ?>
                    <option value="<?php echo $payment_code; ?>" selected="selected"><?php echo $payment_method; ?></option>
                    <?php } ?>
                  </select>
                  <input type="hidden" name="payment_method" value="<?php echo $payment_method; ?>" />
                  <input type="hidden" name="payment_code" value="<?php echo $payment_code; ?>" />
                  <?php if ($error_payment_method) { ?>
                  <span class="error"><?php echo $error_payment_method; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td class="left"><?php echo $entry_coupon; ?></td>
                <td class="left"><input type="text" name="coupon" value="" /></td>
              </tr>
              <tr>
                <td class="left"><?php echo $entry_voucher; ?></td>
                <td class="left"><input type="text" name="voucher" value="" /></td>
              </tr>
              <tr>
                <td class="left"><?php echo $entry_reward; ?></td>
                <td class="left"><input type="text" name="reward" value="" /></td>
              </tr>
              <tr>
                <td class="left"><?php echo $entry_order_status; ?></td>
                <td class="left"><select name="order_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $order_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td class="left"><?php echo $entry_comment; ?></td>
                <td class="left"><textarea name="comment" cols="40" rows="5"><?php echo $comment; ?></textarea></td>
              </tr>
              <tr>
                <td class="left"><?php echo $entry_affiliate; ?></td>
                <td class="left"><input type="text" name="affiliate" value="<?php echo $affiliate; ?>" />
                  <input type="hidden" name="affiliate_id" value="<?php echo $affiliate_id; ?>" /></td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td class="left">&nbsp;</td>
                <td class="left"><a id="button-update" class="button"><?php echo $button_update_total; ?></a></td>
              </tr>
            </tfoot>
          </table>
		  
		  
---------------------------

	 <th colspan="2" style="">Эквайринг / поступления</th>
             <tr>
              <td><b>Предоплата:</b></td>
              <td id="prepayment_paid_result">
				<? if ($prepayment_paid) { ?>
				   <span style="color:green"><b><? echo $prepayment; ?></b>, Оплачено, <? echo $prepayment_paid_date; ?></span>
				<? } else { ?>
					<span style="color:red"><b><? echo $prepayment; ?></b>, Пока не оплачено</span>
				<? } ?>
			  </td>						
            </tr>
		<? if ($this->user->getIsAV()) { ?> 
			<? if (!$prepayment_paid) { ?>
			<tr>
				<td colspan="2"><a class="button" id="prepayment_paid_button" >Оплатили!</a>&nbsp;Когда: <input type="text" class="date" name="prepayment_paid_date" style="width:90px;"></td>
				<script>				
					$('#prepayment_paid_button').on('click', function(){
					if (confirm("Подтверждаете оплату?")) {	
						$.ajax({
							url : 'index.php?route=sale/order/changePrepayPayAjax&token=<?php echo $token; ?>',
							dataType: 'json',
							crossDomain: true,
							type : 'post',
							data : {
								prepayment_paid : '1',
								order_id : <? echo $order_id; ?>,
								prepayment_paid_date : $('input[name=\'prepayment_paid_date\']').val()
							},
							success : function(json){
								$('#prepayment_paid_result').html(json.html);
								$('#order_status_id option[value='+json.status_id+']').attr('selected','selected');

								$.ajax({
									url: 'index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
									type: 'get',
									dataType: 'html',
									data: '',
									beforeSend: function() {
										$('.success, .warning').remove();
										$('#button-history').attr('disabled', true);
										$('#history').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
									},
									complete: function() {
										$('#button-history').attr('disabled', false);
										$('.attention').remove();
									},
									success: function(html) {
										$('#history').html(html);
									}
									});
								},
							error : function (html){
								console.log(html);	
							}
						});																
					}
					});	
				</script>
			</tr>
			<? } else { ?>
			<tr>
				<td colspan="2"><a class="button" id="prepayment_remove_button">Отменить оплату!</a></td>
				<script>
					$('#prepayment_remove_button').on('click', function(){
						if (confirm("Подтверждаете отмену оплаты?")) {	
						$.ajax({
							url : 'index.php?route=sale/order/changePrepayPayAjax&token=<?php echo $token; ?>',
							dataType: 'html',
							crossDomain: true,
							type : 'post',
							data : {
								prepayment_paid : '0',
								order_id : <? echo $order_id; ?>,
								prepayment_paid_date : $('input[name=\'prepayment_paid_date\']').val()
							},
							success : function(json){
								$('#prepayment_paid_result').html(json.html);
								$('#order_status_id option[value='+json.status_id+']').attr('selected','selected');

								$.ajax({
									url: 'index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
									type: 'get',
									dataType: 'html',
									data: '',
									beforeSend: function() {
										$('.success, .warning').remove();
										$('#button-history').attr('disabled', true);
										$('#history').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
									},
									complete: function() {
										$('#button-history').attr('disabled', false);
										$('.attention').remove();
									},
									success: function(html) {
										$('#history').html(html);
									}
									});
								},
							error : function (html){
								console.log(html);	
							}
						});	
					}
					});	
				</script>
			</tr>
			<? } ?>
		<? } ?>
			<tr>
              <td><b>Полная оплата:</b></td>
               <td id="total_paid_result">			   
				<? if ($total_paid) { ?>
				   <span style="color:green"><b><? echo $total; ?></b>, Оплачено, в <? echo $total_paid_date; ?></span>
				<? } else { ?>
					 <span style="color:red"><b><? echo $total; ?></b>, Пока не оплачено</span>
				<? } ?>
			  </td>			
			 </tr>
		<? if ($this->user->getIsAV()) { ?> 
			<? if (!$total_paid) { ?>
			 <tr>
				<td colspan="2"><a class="button" id="total_paid_button" >Оплатили!</a>&nbsp;Когда: <input type="text" class="date" name="total_paid_date" style="width:90px;"></td>
				<script>				
					$('#total_paid_button').on('click', function(){
					if (confirm("Подтверждаете оплату?")) {	
						$.ajax({
							url : 'index.php?route=sale/order/changeTotalPayAjax&token=<?php echo $token; ?>',
							dataType: 'html',
							crossDomain: true,
							type : 'post',
							data : {
								total_paid : '1',
								order_id : <? echo $order_id; ?>,
								total_paid_date : $('input[name=\'total_paid_date\']').val()
							},
							success : function(html){
								$('#total_paid_result').html(html);						
							},
							error : function (html){
								console.log(html);	
							}
						});																
					}
					});	
				</script>
			</tr>
			<? } else { ?>
			<tr>
				<td colspan="2"><a class="button" id="total_remove_button">Отменить оплату!</a></td>
				<script>				
					$('#total_remove_button').on('click', function(){
					if (confirm("Подтверждаете отмену оплаты?")) {	
						$.ajax({
							url : 'index.php?route=sale/order/changeTotalPayAjax&token=<?php echo $token; ?>',
							dataType: 'html',
							crossDomain: true,
							type : 'post',
							data : {
								total_paid : '0',
								order_id : <? echo $order_id; ?>,
								total_paid_date : $('input[name=\'total_paid_date\']').val()
							},
							success : function(html){
								$('#total_paid_result').html(html);						
							},
							error : function (html){
								console.log(html);	
							}
						});																
					}
					});	
				</script>
			</tr>
			<? } ?>
		<? } ?>
		
			
		<tr><td colspan="2" class="left" style="padding:10px; background:red; color: white;">Внимание! Обновление этой информации вручную повлечет за собой изменение статуса заказа, добавление истории и уведомление клиента!</td></tr>