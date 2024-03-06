<script>
	
	
	
	function getCopy(btn, type, name, full) {
		if(full){
			var from_el =  $(type + '[name=\''+ name + '\']');
			} else {
			var from_el =  $('[name=\''+ name + btn.parent().parent().find(type + ':first').attr('name').match(/\[(.*?)\]/g)[1] + '\']');
		}
		var to_el =  btn.parent().parent().find(type + ':first');
		
		if (typeof (CKEDITOR.instances[to_el.attr('id')]) == 'object'){
			CKEDITOR.instances[to_el.attr('id')].setData(CKEDITOR.instances[from_el.attr('id')].getData());
		}
		
		
		to_el.val(from_el.val());
	}
	
	function htmlentities(s){
		var div = document.createElement('div');
		var text = document.createTextNode(s);
		div.appendChild(text);
		return div.innerHTML;		
	}
	
	function getTranslate(btn, source, target, type, name, full) {
		
		$('.translate-error').remove();
		var format = 'text';
		if(full){
			var from_el = $(type + '[name=\'' + name + '\']');
			} else {
			var from_el = $(type + '[name=\'' + name + btn.parent().parent().find(type + ':first').attr('name').match(/\[(.*?)\]/g)[1] + '\']');
		}
		var to_el = btn.parent().parent().find(type + ':first');
		var text = from_el.val();
		if(to_el.next().hasClass('note-editor')){
			format = 'html';
			text = from_el.next().find('.note-editable').html();
			text.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
			}else if(to_el.next().is('[class*="cke_editor_input"]')){
			format = 'html';
			text = CKEDITOR.instances[from_el.attr('id')].getData();
			text.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
		}
		$.ajax({
			url: 'index.php?route=api/translate/ajax&token=<?php echo $token; ?>',
			type: 'post',
			data: 'format=' + format + '&source=' + source + '&target=' + target + '&q=' + encodeURIComponent(text),
			beforeSend: function() {			
				btn.find('i').removeClass('fa-arrow-circle-right').addClass('fa-spinner fa-spin');
			},
			complete: function() {
				btn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-arrow-circle-right');
			},
			dataType: 'json',
			success: function(response) {
				
				console.log(response);
				
				if (response.message){
					btn.parent().parent().prepend('<div class="error translate-error"><i class="fa fa-exclamation-circle"></i> Error: ' + response.message + ' ' + response.code + '</div>');
					} else {
					
					if (typeof (CKEDITOR.instances[to_el.attr('id')]) == 'object'){
						CKEDITOR.instances[to_el.attr('id')].setData(response.translations[0].text);
					}

					to_el.val(response.translations[0].text);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				btn.parent().parent().prepend('<div class="error translate-error"><i class="fa fa-exclamation-circle"></i> Error: ' + jqXHR.responseJSON.error.message + ' ' + jqXHR.responseJSON.error.code + '</div>');
			}
		});
		
	}
	
</script>