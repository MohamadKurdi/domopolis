<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } else { ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/mail.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><input type="hidden" id="ignore" name="ignore" value="0"/><a id="button-send" onclick="send('index.php?route=sale/sms_sending/send&token=<?php echo $token; ?>');" class="button"><?php echo $button_send; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
      <?php if (isset($success)) { ?>
      <div class="success"><?php echo $success; ?></div>
      <?php } ?>
    </div>
    <div class="content">
        <table id="mail" class="form">
          <tr>
            <td><?php echo $text_alias; ?></td>
            <td><?php echo $alias; ?></td>
          </tr>
          <tr>
            <td><?php echo $text_balance; ?></td>
            <td><?php echo $balance; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_to; ?></td>
            <td><select id="to" name="to">
                <?php if ($customer_groups) { ?>
                <?php foreach ($customer_groups as $customer_group) { ?>    
                <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_message; ?></td>
            <td><span id="counter"></span></br>
                <textarea id="message" name="message"></textarea></br>
                <input id="translit" type="checkbox" name="translit" onClick="document.getElementById('translit_checked').value = this.checked ? 1 : 0;"/>
                <label for="translit" style="margin-top:-12px;"><?php echo $text_translit; ?></label>
                <input type="hidden" name="translit_checked" id="translit_checked" value="0" />
            </td>
          </tr>
          <tr>
            <td><?php echo $text_datetime; ?></td>
            <td>
                <input name="timetype" id="imm" checked="checked" onclick="dis()" value="1" type="radio">
                <label for="imm"><?php echo $text_instantly; ?></label>
                <input id="custom" type="radio" value="2" onclick="en()" name="timetype"></input>
                <label for="custom"><?php echo $text_start_at; ?></label>
                <input type="text" id="date" name="date" disabled="disabled" value="">
                <input type="hidden" id="timesend" name="timesend" value="1">
                <script type="text/javascript">
                        $(function(){
                                $('*[name=date]').appendDtpicker();
                        });
                </script>
            </td>
          </tr>
        </table>
    </div>
  </div>
  <?php } ?>
</div>
<!--script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
CKEDITOR.replace('message', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
</script-->

<style> 
.highlight { background: #FA6; } 
</style>

<script>
    function dis(){
        $('#date').attr('disabled', 'disabled');
    }
    function en(){
        $('#date').removeAttr('disabled');
    }
    
</script>

<script type="text/javascript"><!--  

$("#counter").html("<?php echo $text_message; ?> (0 <?php echo $text_symbols; ?>, 1 SMS)"); 
$('#message').keyup(function count(){
    var string = $("#message").val();
    var number = $('#message').val().length;
    if( string.search(/[А-яЁё]/) === -1 ){
        var sms = [~~(((number-1)/160)+1)];
    }
    else{
        var sms = [~~(((number-1)/70)+1)];
    }
    $("#counter").html("<?php echo $text_message; ?> ("+number+"<?php echo $text_symbols; ?>, "+sms+" SMS)"); //Текст SMS (0 символов, 1 SMS):
}); 
      
$('select[name=\'to\']').bind('change', function() {
        $('#ignore').val('0');
});

$('select[name=\'to\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'customers\']').catcomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {	
				response($.map(json, function(item) {
					return {
						category: item.customer_group,
						label: item.name,
						value: item.customer_id
					}
				}));
			}
		});
		
	}, 
	select: function(event, ui) {
		$('#customer' + ui.item.value).remove();
		
		$('#customer').append('<div id="customer' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="customer[]" value="' + ui.item.value + '" /></div>');

		$('#customer div:odd').attr('class', 'odd');
		$('#customer div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('#customer div img').live('click', function() {
	$(this).parent().remove();
	
	$('#customer div:odd').attr('class', 'odd');
	$('#customer div:even').attr('class', 'even');	
});
//--></script> 
<script type="text/javascript"><!--	
$('input[name=\'affiliates\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/affiliate/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.affiliate_id
					}
				}));
			}
		});
		
	}, 
	select: function(event, ui) {
		$('#affiliate' + ui.item.value).remove();
		
		$('#affiliate').append('<div id="affiliate' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="affiliate[]" value="' + ui.item.value + '" /></div>');

		$('#affiliate div:odd').attr('class', 'odd');
		$('#affiliate div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('#affiliate div img').live('click', function() {
	$(this).parent().remove();
	
	$('#affiliate div:odd').attr('class', 'odd');
	$('#affiliate div:even').attr('class', 'even');	
});

$('input[name=\'products\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#product' + ui.item.value).remove();
		
		$('#product').append('<div id="product' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product[]" value="' + ui.item.value + '" /></div>');

		$('#product div:odd').attr('class', 'odd');
		$('#product div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('#product div img').live('click', function() {
	$(this).parent().remove();
	
	$('#product div:odd').attr('class', 'odd');
	$('#product div:even').attr('class', 'even');	
});

function send(url) { 
	
        $('textarea[name="message"]').val();
        
        if(document.getElementById('imm').checked) {
            $('input[name="timesend"]').val('1');
          }else if(document.getElementById('custom').checked) {
            $('input[name="timesend"]').val('2');
          }
	$.ajax({
		url: url,
		type: 'post',
		data: $('select, input, textarea'),		
		dataType: 'json',
		beforeSend: function() {
			$('#button-send').attr('disabled', true);
			$('#button-send').before('<span class="wait"><img src="view/image/loading.gif" alt="" />&nbsp;</span>');
		},
		complete: function() {
			$('#button-send').attr('disabled', false);
			$('.wait').remove();
		},				
		success: function(json) {
			$('.success, .warning, .error, .groups').remove();
			
			if (json['error']) { 
				if (json['error']['warning']) {
					$('.box').before('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
			
					$('.warning').fadeIn('slow');
				}
				
				if (json['error']['message']) {
					$('textarea[name=\'message\']').parent().append('<span class="error">' + json['error']['message'] + '</span>');
				}
                                
                                if (json['error']['customers']) {
					$('select[name=\'to\']').after('<span class="error">' + json['error']['customers'] + '</span>');
				}
                                if (json['error']['format']) {
					$('select[name=\'to\']').after('<div class="groups"><span class="error">' + json['error']['format'] + '</span>\n\
                                                                        <a href="<?php echo $correct; ?>" class="button"><?php echo $button_correct; ?></a>\n\
                                                                        <a onclick="document.getElementById(\'ignore\').value = \'1\';$(\'.groups\').remove();" class="button"><?php echo $button_ignore; ?></a></div>');
				}
			} else {
                            $('#ignore').val('0');
                        }
                        
			if (json['next']) {
				if (json['success']) {
					$('.box').before('<div class="success">' + json['success'] + '</div>');
					
					send(json['next']);
				}		
			} else {
				if (json['success']) {
					$('.box').before('<div class="success" style="display: none;">' + json['success'] + '</div>');
			
					$('.success').fadeIn('slow');
				}					
			}				
		}
	});
}
//--></script>

<?php echo $footer; ?>
