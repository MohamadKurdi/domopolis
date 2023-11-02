<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/setting.png" alt="" /> <?php echo $heading_title; ?> / <?php echo $config_name; ?></h1>			
			<div class="buttons">
				<?php /* ?>	
					<a onclick="$('#save_button').toggle()" class="button">Показать кнопку сохранения</a>
					<a onclick="$('#form').submit();" class="button" id="save_button" style="display:none; border-color:red;color:white;background-color: red">СОХРАНИТЬ [ НЕ НАЖИМАТЬ НА ХУЕВОМ ИНТЕРНЕТЕ ]</a>
					<?php */ ?>	
					<a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
				</div>
			</div>
			<div class="content">
				<style>
					#tabs > a {font-weight:700}
				</style>
				<div id="tabs" class="htabs">
					<?php foreach ($tabs as $tab) { ?>
						<?php foreach ($tab as $name => $setting) { ?>
							<a href="#<?php echo $name; ?>"><span style="color:#<?php echo $setting['color']; ?>;"><i class="fa <?php echo $setting['icon']; ?>"></i> <?php echo $setting['text']; ?></span></a>
						<?php } ?>
					<?php } ?>			
					<div class="clr"></div>
				</div>
				<div class="th_style"></div>
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
					<input type="hidden" name="store_id" value="0"/>
					
					<?php foreach ($tabs as $tab) { ?>
						<?php foreach ($tab as $name => $setting) { ?>
							<?php if (file_exists(dirname(__FILE__) . '/settings/' . $name . '.tpl')) { ?>
								<?php require_once(dirname(__FILE__) . '/settings/' . $name . '.tpl'); ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('select, textarea, input[type=text], input[type=number], input[type=time], input[type=checkbox], input[type=hidden]').bind('change', function() {
			console.log('Change triggered on ' + $(this).attr('name'));

			var key  			= $(this).attr('name');
			var elem 			= $(this);
			var value 			= $(this).val();
			var store_id 		= $('input[name=store_id]').val();
			var js_serialized 	= 0;

			if (elem.attr('data-key') != null){
				console.log('multi setting, get all keys for ' + elem.attr('data-key'));

				key   			= elem.attr('data-key');					
				value 			= $('input[data-key=\'' + elem.attr('data-key') + '\'], textarea[data-key=\'' + elem.attr('data-key') + '\']').serialize();
				js_serialized 	= 1;		
			} else {
				if (elem.attr('type') == 'checkbox'){
					value = [];
					if (key.indexOf('[]') > 0){
						var allboxes = $('input[name=\''+ key +'\']');

						allboxes.each(function(i){
							if ($(this).attr('checked')){
								value.push($(this).val());
							}
						});
					} else {
						if (elem.attr('checked')){
							value = elem.val();
						} else {
							value = 0;
						}
					}
				}
			}

			$.ajax({
				type: 'POST',
				url: 'index.php?route=setting/setting/editSettingAjax&store_id=' + store_id + '&token=<?php echo $token; ?>',
				data: {						
					key: key,
					value: value,
					js_serialized: js_serialized
				},
				beforeSend: function(){
					elem.css('border-color', 'yellow');
					elem.css('border-width', '2px');						
				},
				success: function(){
					elem.css('border-color', 'green');
					elem.css('border-width', '2px');
				}
			});

		});

		$('select[name=\'config_country_id\']').bind('change', function() {
			$.ajax({
				url: 'index.php?route=setting/setting/country&token=<?php echo $token; ?>&country_id=' + this.value,
				dataType: 'json',
				beforeSend: function() {
					$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" /></span>');
				},		
				complete: function() {
					$('.wait').remove();
				},			
				success: function(json) {
					if (json['postcode_required'] == '1') {
						$('#postcode-required').show();
					} else {
						$('#postcode-required').hide();
					}

					html = '<option value=""><?php echo $text_select; ?></option>';

					if (json['zone'] != '') {
						for (i = 0; i < json['zone'].length; i++) {
							html += '<option value="' + json['zone'][i]['zone_id'] + '"';

							if (json['zone'][i]['zone_id'] == '<?php echo $config_zone_id; ?>') {
								html += ' selected="selected"';
							}

							html += '>' + json['zone'][i]['name'] + '</option>';
						}
					} else {
						html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
					}

					$('select[name=\'config_zone_id\']').html(html);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		function image_upload(field, thumb) {
			$('#dialog').remove();

			$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

			$('#dialog').dialog({
				title: '<?php echo $text_image_manager; ?>',
				close: function (event, ui) {
					if ($('#' + field).attr('value')) {
						$.ajax({
							url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
							dataType: 'text',
							success: function(data) {
								$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
								$('#' + field).trigger('change');
							}
						});
					}
				},	
				bgiframe: false,
				width: 1000,
				height: 800,
				resizable: true,
				modal: false
			});
		};
	</script> 
	<script type="text/javascript">
		$('#tabs a').tabs();
	</script> 
	<script type="text/javascript">
		$('#vstabs a').tabs();
	</script> 
<?php echo $footer; ?>																																																																											