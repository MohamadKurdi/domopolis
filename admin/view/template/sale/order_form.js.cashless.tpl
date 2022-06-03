<script>
	$(document).ready(function(){	
		$('#cashless_preview').click(function(){
			$.ajax({
				url: 'index.php?route=kp/printer/createDocument&token=<?php echo $token; ?>&order_id=<? echo $order_id; ?>&customer_id=<? echo $customer_id; ?>&doctype=cashless&action=preview&noprint='+$("#cashless_nostamp").is(":checked"),
				dataType: 'json',
				success : function(json){
					$('#mailpreview').html(json.html).dialog({title:'Счет на оплату по безналичному расчету', width:900, modal:true,resizable:true,position:{my: 'center', at:'center top', of: window}, closeOnEscape: true})				
				},
				error : function(e){
					console.log(e)
				}
			})	
		});	
		
		$('#cashless_print').click(function(){
			window.open('index.php?route=kp/printer/createDocument&token=<?php echo $token; ?>&order_id=<? echo $order_id; ?>&customer_id=<? echo $customer_id; ?>&doctype=cashless&action=print&noprint='+$("#cashless_nostamp").is(":checked"), '_blank');			
		});		
		
		$('#cashless_pdf').click(function(){
			window.open('index.php?route=kp/printer/createDocument&token=<?php echo $token; ?>&order_id=<? echo $order_id; ?>&customer_id=<? echo $customer_id; ?>&doctype=cashless&action=pdf&noprint='+$("#cashless_nostamp").is(":checked"), '_blank');				
		});	
		
		$('#torg12_preview').click(function(){
			$.ajax({
				url: 'index.php?route=kp/printer/createDocument&token=<?php echo $token; ?>&order_id=<? echo $order_id; ?>&customer_id=<? echo $customer_id; ?>&doctype=torg12&action=preview',
				dataType: 'json',
				success : function(json){
					$('#mailpreview').html(json.html).dialog({title:'Форма РФ ТОРГ - 12', width:1100, modal:true,resizable:true,position:{my: 'center', at:'center top', of: window}, closeOnEscape: true})				
				},
				error : function(e){
					console.log(e)
				}
			})	
		});	
		
		$('#torg12_print').click(function(){
			window.open('index.php?route=kp/printer/createDocument&token=<?php echo $token; ?>&order_id=<? echo $order_id; ?>&customer_id=<? echo $customer_id; ?>&doctype=torg12&action=print', '_blank');			
		});		
		
		$('#torg12_pdf').click(function(){
			window.open('index.php?route=kp/printer/createDocument&token=<?php echo $token; ?>&order_id=<? echo $order_id; ?>&customer_id=<? echo $customer_id; ?>&doctype=torg12&action=pdf', '_blank');				
		});	
	});
</script>