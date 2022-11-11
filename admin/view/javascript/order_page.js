$('.htabs a').tabs();
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
function count_prepay(percent){
	var total_value = parseFloat($('#value_total').val());
	
	return Math.round(total_value / 100 * parseInt(percent));
}

function count_discount(percent){
	var total_value = parseFloat($('#value_sub_total').val());
	
	return Math.round(total_value / 100 * parseInt(percent));
}

$(document).ready(function(){				
	function spin_to_0(elm, vl) {
		if (elm.val() >= 0){
			elm.val(parseInt(elm.val(), 10) + vl);
		}
		if (elm.val() <= 0){
			elm.val('0');
		}
	}
	
	function spin_to_max(elm, vl, max) {
		if (elm.val() >= 0){
			elm.val(parseInt(elm.val(), 10) + vl);
		}																				
		if (elm.val() >= max){
			elm.val(max);
		}
		if (elm.val() <= 0){
			elm.val('0');
		}
	}
	
	$('i.divide-plus').click(function() {
		var _elem = $('#'+$(this).attr('data-elem')+'_quantity');																
		var _cnt = $('#'+$(this).attr('data-elem')+'');
		var _max = _cnt.attr('data-max-divide');
		spin_to_max(_elem, 1, _max);
		_cnt.text(_elem.val());
	});
	
	$('i.divide-minus').click(function() {
		var _elem = $('#'+$(this).attr('data-elem')+'_quantity');																
		var _cnt = $('#'+$(this).attr('data-elem')+'');
		var _max = _cnt.attr('data-max-divide');
		spin_to_max(_elem, -1, _max);
		_cnt.text(_elem.val());
	});
	
	$('i.reserve-plus').click(function() {
		var _elem = $('#'+$(this).attr('data-elem')+'_quantity');																
		var _cnt = $('#'+$(this).attr('data-elem')+'');
		var _max = _cnt.attr('data-max-reserve');
		spin_to_max(_elem, 1, _max);
		_cnt.text(_elem.val());
	});
	
	$('i.reserve-minus').click(function() {
		var _elem = $('#'+$(this).attr('data-elem')+'_quantity');
		var _cnt = $('#'+$(this).attr('data-elem')+'');
		var _max = _cnt.attr('data-max-reserve');
		spin_to_max(_elem, -1, _max);
		_cnt.text(_elem.val());																				
	});
	
	$('i.total-delivery-num-plus').click(function() {
		spin_to_0($('#order_total_'+$(this).attr('data-order-total-row')+'_for_delivery'), 1);
		$($('#total_delivery_'+$(this).attr('data-order-total-row')+'_for_delivery_span')).text($('#order_total_'+$(this).attr('data-order-total-row')+'_for_delivery').val());
	});
	$('i.total-delivery-num-minus').click(function() {
		spin_to_0($('#order_total_'+$(this).attr('data-order-total-row')+'_for_delivery'), -1);
		$($('#total_delivery_'+$(this).attr('data-order-total-row')+'_for_delivery_span')).text($('#order_total_'+$(this).attr('data-order-total-row')+'_for_delivery').val());
	});
	
});