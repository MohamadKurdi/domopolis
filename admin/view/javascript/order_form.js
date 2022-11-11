$(document).ready(function(){
	$('#go_to_order').keyup(function (e) {
		if (e.keyCode === 13) {
			go_to_order();
		}
	});
});

$('#changemanager_button').click(function(){
	$('#manager_of_order').load('<? echo str_replace('&amp;', '&', $changemanager_url); ?>&manager_id='+$('#changemanager option:selected').val());
});

function go_to_order(){
	if ($('#go_to_order').val().length >= 5){
		$.ajax({
			url : 'index.php?route=sale/order/if_order_exists&order_id='+parseInt($('#go_to_order').val())+'&token=<? echo $token ?>',
			type : 'GET',
			dataType : 'text',
			success : function(text){
				if (text == 'n'){
					swal("Ошибка!", "Такого заказа не существует!", "error");
					} else {
					document.location.href = 'index.php?route=sale/order/update&order_id='+text+'&token=<? echo $token ?>';
				}
			}
		});
		} else {
		alert('Некорректный номер заказа');
	}
	
}