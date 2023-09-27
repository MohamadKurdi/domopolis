<?php if ($ukrcredits_order_status) { ?>
<script type="text/javascript">
$(document).ready(function(){

    $("#button-status").click(function(){
        $.ajax({
            type: 'post',
            url: 'index.php?route=sale/ukrcredits/askstatus&token=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>&payment_code=<?php echo $payment_code; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, attention').remove();
				$('#div-credits').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data['state']);
                    switch(data['state']){
                        case 'SUCCESS':
							$('.success, .warning, .attention').remove();
                            $('#div-credits').prepend('<div class="success">Статус заказа обновлен: ' + data['paymentState'] + '</div>');
							$('#privat_order_status').html(data['paymentState']);
                            break;
                        case 'FAIL':
							$('.success, .warning, .attention').remove();
                            $('#div-credits').prepend('<div class="warning">При обновлении статуса заказа произошла ошибка: ' + data['message'] + '</div>');
							break;
                        case 'sys_error':
							$('.success, .warning, .attention').remove();
                            $('#div-credits').prepend('<div class="warning">При обновлении статуса заказа произошла ошибка: ' + data['message'] + '</div>');
							break;
                    }

                    $('#cc_debug_result').html('<pre>' + syntaxHighlight(JSON.stringify(data, null, 2)) + '</pre>').dialog({title:'Информация о транзакции', width:900, height:700, modal:true,resizable:true,position:{my: 'center', at:'center top', of: window}, closeOnEscape: true})	;                                 
               }    
        });
        return false;    
    });
	
    $("#button-confirm").click(function(){
	if(confirm('<?php echo $text_confirm; ?>')){
        $.ajax({
            type: 'post',
            url: 'index.php?route=sale/ukrcredits/confirmhold&token=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>&order_id=<?php echo $order_id; ?>&payment_code=<?php echo $payment_code; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, attention').remove();
				$('#div-credits').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data['state']);
                    switch(data['state']){
                        case 'SUCCESS':
							$('.success, .warning, .attention').remove();
                            $('#div-credits').prepend('<div class="success">Заказ успешно подтвержден!</div>');
							$('#ukrcredits_order_status').html('SUCCESS');
							
							$.ajax({
								url: 'index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
								type: 'post',
								dataType: 'html',
								data: 'order_status_id=' + data['order_status_id'] + '&notify=1&append=0&comment=' + data['comment'],
								success: function(html) {
									$('#history').html(html);
									
									$('textarea[name=\'comment\']').val('');
									
									$('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());
								}
							});
							
                            break;
                        case 'FAIL':
							$('.success, .warning, .attention').remove();
                            $('#div-credits').prepend('<div class="alert alert-warning">При подтверждении заказа произошла ошибка: ' + data['message'] + '</div>');
							break;
                        case 'sys_error':
							$('.success, .warning, .attention').remove();
                            $('#div-credits').prepend('<div class="alert alert-warning">При подтверждении заказа произошла ошибка: ' + data['message'] + '</div>');
							break;
                    }                                 
               }    
        });
        return false;   
	}
    });

    $("#button-cancel").click(function(){
	if(confirm('<?php echo $text_confirm; ?>')){
        $.ajax({
            type: 'post',
            url: 'index.php?route=sale/ukrcredits/cancelhold&token=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>&order_id=<?php echo $order_id; ?>&payment_code=<?php echo $payment_code; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, .alert, attention').remove();
				$('.page-header > div').append('<div class="alert alert-info"> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data['state']);
                    switch(data['state']){
                        case 'SUCCESS':
							$('.success, .warning, .alert, .attention').remove();
                            $('#div-credits').prepend('<div class="alert alert-success">Заказ успешно отменен!</div>');
							$('#ukrcredits_order_status').html('CANCELED');
							$.ajax({
								url: 'index.php?route=sale/ukrcredits/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
								type: 'post',
								dataType: 'html',
								data: 'order_status_id=' + data['order_status_id'] + '&notify=1&append=0&comment=' + data['comment'],
								success: function(html) {
									$('#history').html(html);
									
									$('textarea[name=\'comment\']').val('');
									
									$('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());
								}
							});
                            break;
                        case 'FAIL':
							$('.success, .warning, .alert, .attention').remove();
                            $('#div-credits').prepend('<div class="alert alert-warning">При отмене заказа произошла ошибка: ' + data['message'] + '</div>');
							break;
                        case 'sys_error':
							$('.success, .warning, .alert, .attention').remove();
                            $('#div-credits').prepend('<div class="alert alert-warning">При отмене заказа произошла ошибка: ' + data['message'] + '</div>');
							break;
                    }                                 
               }    
        });
        return false;    
	}
    });  
	
    $("#button-status-mb").click(function(){
        $.ajax({
            type: 'post',
            url: 'index.php?route=sale/ukrcredits/askstatus_mb&token=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, attention').remove();
				$('#div-credits').prepend('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data);
					if (data['message']) {
						$('.success, .warning, .attention').remove();
                        $('#div-credits').prepend('<div class="warning">При обновлении статуса заказа произошла ошибка: ' + data['message'] + '</div>');
					}
					if (data['order_id']) {
						$('.success, .warning, .attention').remove();
                        $('#div-credits').prepend('<div class="success">Статус заказа обновлен: ' + data['state'] + ' / ' + data['order_sub_state'] + '</div>');
						$('#ukrcredits_order_status').html(data['state'] + ' / ' + data['order_sub_state']);					
					}
               }    
        });
        return false;    
    });
	
    $("#button-cancel-mb").click(function(){
	if(confirm('<?php echo $text_confirm; ?>')){
        $.ajax({
            type: 'post',
            url: 'index.php?route=sale/ukrcredits/cancelhold_mb&token=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>&order_id=<?php echo $order_id; ?>&payment_code=<?php echo $payment_code; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, .alert, attention').remove();
				$('#div-credits').prepend('<div class="alert alert-info"> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data);
					if (data['message']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('#div-credits').prepend('<div class="alert alert-warning">При отмене заказа произошла ошибка: ' + data['message'] + '</div>');
					}
					if (data['order_id']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('#div-credits').prepend('<div class="alert alert-success">Статус заказа обновлен: ' + data['state'] + ' / ' + data['order_sub_state'] + '</div>');
						$('#ukrcredits_order_status').html(data['state'] + ' / ' + data['order_sub_state']);
						$('#button-confirm-mb').remove();
						$('#button-cancel-mb').remove();
							$.ajax({
								url: 'index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
								type: 'post',
								dataType: 'json',
								data: 'order_status_id=' + data['order_status_id'] + '&notify=1&append=0&comment=' + data['comment'],
								success: function(html) {
									$('#history').html(html);
									
									$('textarea[name=\'comment\']').val('');
									
									$('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());
								}
							});
					}
               }                                   
        });
        return false;    
	}
    });  
    $("#button-confirm-mb").click(function(){
	if(confirm('<?php echo $text_confirm; ?>')){
        $.ajax({
            type: 'post',
            url: 'index.php?route=sale/ukrcredits/confirmhold_mb&token=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>&order_id=<?php echo $order_id; ?>&payment_code=<?php echo $payment_code; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, .alert, attention').remove();
				$('#div-credits').prepend('<div class="alert alert-info"> <?php echo $text_wait; ?></div>');
			},
               success: function(data){ 
					console.log(data);
					if (data['message']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('#div-credits').prepend('<div class="alert alert-warning">При подтверждении заказа произошла ошибка: ' + data['message'] + '</div>');
					}
					if (data['order_id']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('#div-credits').prepend('<div class="alert alert-success">Заказ успешно подтвержден!: ' + data['state'] + ' / ' + data['order_sub_state'] + '</div>');
						$('#ukrcredits_order_status').html(data['state'] + ' / ' + data['order_sub_state']);
						$('#button-confirm-mb').remove();
						$('#button-cancel-mb').remove();
							$.ajax({
								url: 'index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
								type: 'post',
								dataType: 'json',
								data: 'order_status_id=' + data['order_status_id'] + '&notify=1&append=0&comment=' + data['comment'],
								success: function(html) {
									$('#history').html(html);
									
									$('textarea[name=\'comment\']').val('');
									
									$('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());
								}
							});
					}
               }                                   
        });
        return false;    
	}
    });  	
});    
</script>
<?php } ?>