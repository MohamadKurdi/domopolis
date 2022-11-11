<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading order_head">
      <h1><img src="view/image/mail.png" alt="" />Отправка SMS</h1>
      <div class="buttons"><a id="button-send" onclick="send('index.php?route=sale/sms/send&token=<?php echo $token; ?>');" class="button"><?php echo $button_send; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
		 <table id="mail" class="form">
			<tr>
				
			<td><span class="required">*</span>Телефон (несколько через запятую):</td><td><input type='text' name='phones' style='width:300px;' />&nbsp;&nbsp;&nbsp;
			Альфа: 
				<select name="store_id" id="store_id" onchange="$('#alfa').val($(this).find(':selected').data('alfa'));" style="width:150px;">
					<?php foreach ($sms_alfas as $value) { ?>
					<option data-alfa="<? echo $value['alfa']; ?>" value="<?php echo $value['store_id'] ?>"><?php echo $value['name'] ?> : <? echo $value['alfa']; ?>
					</option>
					<?php } ?>
				</select>	<span style="cursor:pointer" onclick="$('#alfa').val($('#store_id').find(':selected').data('alfa'));">>></span> 
			<input id="alfa" type="text" style="width:100px;" name="alfa" />
			</td>	
				
			</tr>
			
			<tr>
			
			<td><span class="required">*</span>Сообщение<br /><span style='font-size:10px;'>Количество симв:<span id='symbols'></span></span></td>
            <td>
			<textarea id='message' name="message" cols='100' rows='5' onKeyDown="$('#symbols').html($(this).val().length);">
			</textarea>
			<br /><br />							
			</td>			
			
			</tr>  
		</table>
		
		<div id='sms_history'>
			<table class='list'>
			<? foreach ($log as $sms) { ?>
			<tr>
				<td class='left'><? echo $sms['id']; ?></td>
				<td class='left'><? echo $sms['phone']; ?></td>
				<td class='right'><? echo $sms['text']; ?></td>
				<td class='right'><? echo $sms['date_send']; ?></td>
			</tr>			
			<? } ?>
			</table>		
		</div>
    </div>
  </div>
</div>

<script type="text/javascript"><!--	

$(document).ready(function(){
	$('#alfa').val($('#store_id').find(':selected').data('alfa'));
});

function send(url) { 
	$.ajax({
		url: url,
		type: 'post',
		data: $('input, textarea'),		
		dataType: 'json',
		beforeSend: function() {
			$('#button-send').attr('disabled', true);
			$('#button-send').before('<span class="wait"><img src="view/image/loading.gif" alt="" />&nbsp;</span>');
		},
		complete: function(json) {
			$('#button-send').attr('disabled', false);
			$('.wait').remove();
			
			console.log(json);
		},				
		success: function(json) {							
			$('.success, .warning, .error').remove();
			
			if (json['error']) {
				if (json['error']['warning']) {
					$('.box').before('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
			
					$('.warning').fadeIn('slow');
				}
				
				if (json['error']['phones']) {
					$('input[name=\'phones\']').after('<span class="error">' + json['error']['phones'] + '</span>');
				}	
				
				if (json['error']['message']) {
					$('textarea[name=\'message\']').parent().append('<span class="error">' + json['error']['message'] + '</span>');
				}									
			}			
			
			if (json['success']) {
				$('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
				$('.success').fadeIn('slow');
			}										
		}
	});
}
//--></script> 
<?php echo $footer; ?>
