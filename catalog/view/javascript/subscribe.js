$(document).ready(function () {
	$('.subscribe-custom-position').each(function () {
		var subscribe_module = this;

		$.ajax({
			url: 'index.php?route=module/subscribe',
			type: 'post',
			data: 'module='+$(subscribe_module).data('module'),
			success: function (data) {
				$(subscribe_module).html(data);
			}
		});
	});
});

function addSubscribe(module) {
	var email = $('input[name="subscribe_email'+module+'"]').attr('value');

	$.ajax({
		url: 'index.php?route=module/subscribe/addSubscribe',
		type: 'post',
		dataType: 'json',
		data: 'email='+email+'&module='+module,
		success: function (data) {
			$('.subscribe_success'+module+', .error'+module).remove();

			if (data['error']) {
				$('.subscribe'+module).after('<span class="error error'+module+'">'+data['error']+'</span>');
			}

			if (data['success']) {
				$('.subscribe'+module).after('<span class="subscribe_success'+module+'">'+data['success']+'</span>');
				$('input[name="subscribe_email'+module+'"]').attr('value', '');
			}
		}
	});
}
