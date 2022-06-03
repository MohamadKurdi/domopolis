<?php echo $header; ?>

<link href="view/stylesheet/module/emailtemplate.css" type="text/css" rel="stylesheet" />

<div id="content">
	<div class="breadcrumb">
 	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
  		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  	<?php } ?>
	</div>

	<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
	<?php if ($error_attention) { ?><div class="attention"><?php echo $error_attention; ?></div><?php } ?>
	<?php if ($success) { ?><div class="success"><?php echo $success; ?></div><?php } ?>

	<div class="box" id="emailtemplate">
		<div class="heading">
			<h1><img src="view/image/log.png" alt="" /><?php echo $heading_logs; ?></h1>

			<div class="buttons">
				<a href="javascript:void(0)" onclick="$('#form').attr('action', '<?php echo $action; ?>&control=delete'); $('#form').submit();" class="button"><span><?php echo $button_delete; ?></span></a>
				<a href="<?php echo $cancel; ?>" class="button button-secondary"><span><?php echo $button_back; ?></span></a>
			</div>
		</div>

		<div class="content">
			<form action="<?php echo $action; ?>" method="post" id="form">
		        <table class="list">
		          	<thead>
		            	<tr>
		              		<td width="1" style="text-align: center;">
		              			<input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
	              			</td>
		              		<td class="left">
		                		<a href="<?php echo $sort_subject; ?>"<?php if ($sort == 'el.subject') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>><?php echo $column_subject; ?></a>
		                	</td>
		              		<td class="left">
		                		<a href="<?php echo $sort_to; ?>"<?php if ($sort == 'el.to') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>><?php echo $column_to; ?></a>
		                	</td>
		              		<td class="left">
		                		<a href="<?php echo $sort_from; ?>"<?php if ($sort == 'el.from') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>><?php echo $column_from; ?></a>
		                	</td>
		              		<td class="left">
		                		<a href="<?php echo $sort_sent; ?>"<?php if ($sort == 'el.sent') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>><?php echo $column_sent; ?></a>
		                	</td>
		                	<td width="55">&nbsp;</td>
		            	</tr>
		          	</thead>
		          	<tbody>
		            <?php if ($logs) { ?>
			            <?php foreach ($logs as $row) { ?>
			            <tr class="loadMailBox" data-id="<?php echo $row['emailtemplate_log_id']; ?>">
			            	<td class="center">
		                		<input type="checkbox" name="selected[]" value="<?php echo $row['emailtemplate_log_id']; ?>"<?php if ($row['selected']) { ?> checked="checked"<?php } ?> />
			                </td>
			              	<td class="left"><b><?php echo $row['preview']; ?></b></td>
			              	<td class="left">
			              		<?php if(!empty($row['customer']['url_edit'])){ ?>
				              		<a href="<?php echo $row['customer']['url_edit']; ?>"><?php echo $row['customer']['firstname'] . ' ' . $row['customer']['lastname']; ?></a>
				              		&lt;<a href="mailto:<?php echo $row['to']; ?>?subject=<?php echo $row['subject']; ?>"><?php echo $row['to']; ?></a>&gt;
			              		<?php } else { ?>
			              			<a href="mailto:<?php echo $row['to']; ?>?subject=<?php echo $row['subject']; ?>"><?php echo $row['to']; ?></a>
			              		<?php } ?>
			              	</td>
			              	<td class="left">
			              		<?php if($row['sender']){ ?>
				              		<?php echo $row['sender']; ?>
				              		&lt;<a href="mailto:<?php echo $row['from']; ?>?subject=<?php echo $row['subject']; ?>"><?php echo $row['from']; ?></a>&gt;
			              		<?php } else { ?>
			              			<a href="mailto:<?php echo $row['from']; ?>?subject=<?php echo $row['subject']; ?>"><?php echo $row['from']; ?></a>
			              		<?php } ?>
		              		</td>
			              	<td class="left"><i><?php echo $row['sent']; ?></i></td>
			              	<td class="right">
			              		<a href="#" class="action-icon-mail action-icons"></a>&nbsp;&nbsp;
			              		<?php if(!empty($row['emailtemplate'])){ ?>
			              			<a href="<?php echo $row['emailtemplate']['url_edit']; ?>" title="<?php echo $row['emailtemplate']['label']; ?>" class="action-icon-edit action-icons"><?php echo $row['emailtemplate']['label']; ?> (<?php echo $row['emailtemplate']['key']; ?>)</a>
			              		<?php } ?>
			              	</td>
			            </tr>
			            <?php } ?>
					<?php } else { ?>
			            <tr>
			              <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
			            </tr>
			        <?php } ?>
		          	</tbody>
	        	</table>

	        	<div class="pagination" style="text-align: center;">
					<?php if ($logs) { ?>
					<div id="pagination">
				    	<?php echo $pagination; ?>
			    	</div>
			    	<?php } ?>

		    		<div class="filter">
						<label for="filter_emailtemplate_id" style="font-style: italic;"><?php echo $text_template; ?></label>
						<select name="filter_emailtemplate_id" id="filter_emailtemplate_id" style="margin-right:15px; padding: 4px 3px; width:100px">
							<option value=""><?php echo $text_select; ?></option>
							<?php foreach($emailtemplates as $row){ ?>
								<option value="<?php echo $row['emailtemplate_id']; ?>"<?php if($filter_emailtemplate_id == $row['emailtemplate_id']) echo ' selected="selected"'; ?>><?php echo ($row['label']) ? $row['label'] : $row['key']; ?></option>
							<?php } ?>
						</select>

						<label for="filter_store_id" style="font-style: italic;"><?php echo $entry_store; ?></label>
						<select name="filter_store_id" id="filter_store_id" style="margin-right:15px; padding: 4px 3px; width:100px">
							<option value=""><?php echo $text_select; ?></option>
							<?php foreach($stores as $store){ ?>
							<option value="<?php echo $store['store_id']; ?>"<?php if($filter_store_id == $store['store_id'] && is_numeric($filter_store_id)) echo ' selected="selected"'; ?>><?php echo $store['store_name']; ?></option>
							<?php } ?>
						</select>

						<label for="filter_customer_id" style="font-style: italic;"><?php echo $text_customer; ?></label>
						<input type="hidden" name="filter_customer_id" id="filter_customer_id" value="<?php echo $filter_customer_id; ?>" />
						<input type="text" id="filter_customer" id="filter_customer" value="<?php echo $filter_customer; ?>" style="margin-right:15px; padding: 4px 3px; width:100px" />

						<button data-button="filter" class="button"><span><?php echo $button_filter;  ?></span></button>
					</div>
				</div>

				<div id="emailBox">
					<div class="emailBoxHeader">
						<ul class="emailBoxProperties">
							<li>
								<label><?php echo $text_from; ?></label>
								<span data-field="sender"></span>
								<i>&lt;</i><a data-field="from" data-type="mailto"></a><i>&gt;</i>
							</li>
							<li>
								<label><?php echo $text_subject; ?></label>
								<b data-field="subject"></b>
							</li>
							<li>
								<label><?php echo $text_to; ?></label>
								<i>&lt;</i><a data-field="to" data-type="mailto"></a><i>&gt;</i>
							</li>
						</ul>

						<div class="emailBoxButtons">
							<a href="#" class="button" data-button="plaintext"><?php echo $button_plain_text; ?></a>
							<a href="#" class="button hide" data-button="html"><?php echo $button_html; ?></a>
							<a href="" class="button" data-button="reply"><?php echo $button_reply; ?></a>
							<a href="" class="button" data-button="delete"><?php echo $button_delete; ?></a>
						</div>

						<div class="emailBoxMeta">
							<p>
								<i data-field="sent"></i>
							</p>
							<p class="hide"><b><?php echo $text_read; ?></b> <i data-field="read"></i></p>
							<p class="hide"><b><?php echo $text_read_last; ?></b> <i data-field="read_last"></i></p>
						</div>
					</div>

					<div class="emailBoxMain">
						<div id="emailBoxText"></div>
						<iframe src="javascript:false;" id="emailBoxFrame" style="width:100%; height:500px; border:none; margin:0 auto; float:none; display:block"></iframe>
					</div>
				</div>
	    	</form>
		</div>
	</div>
</div>

<script type="text/javascript"><!--
(function($){
	$(document).ready(function(){

		var $emailBox,
			$emailBoxText,
			$row,
			$buttons,
			$field,
			iframe,
			$iframe,
			id;

		$emailBox = $('#emailBox');
		$emailBoxText = $('#emailBoxText');
		$buttons = $emailBox.find('[data-button]');

		iframe = document.getElementById('emailBoxFrame');
		$iframe = $(iframe);
		iframe = (iframe.contentWindow) ? iframe.contentWindow : (iframe.contentDocument.document) ? iframe.contentDocument.document : iframe.contentDocument;

		$(".loadMailBox > td").click(function(e){
			if(e.target != this){
				e.stopPropagation();
				if($(e.target).hasClass('action-icon-mail') ){
					e.preventDefault();
				} else if(e.target instanceof HTMLInputElement || e.target instanceof HTMLAnchorElement){
					return;
				}
			}

			$row = $(this).parents('tr');

			if($row.hasClass('active')){
				return;
			}

			$row.siblings().removeClass('active');

			$row.addClass('active');

			$emailBox.find('[data-field]').html('').attr('href', '');
			$emailBox.find('[data-button]').attr('href', '');

			iframe.document.open();
			iframe.document.write('');
			iframe.document.close();

			id = $(this).parents('tr').data('id');

			if(!id) return false;

			$emailBox.data('id', id);

			$emailBox.find('.hide').hide();

			$.ajax({
				url: 'index.php?route=module/emailtemplate/fetch_log&token=<?php echo $token; ?>&id=' +  id,
				dataType: 'json',
				success: function(json) {
					for(var key in json){
						$field = $emailBox.find('[data-field=' + key + ']');

						if($field && json[key]){
							if($field.data('type') == 'mailto'){
								$field.attr('href', 'mailto:' + json[key] + '?subject=' + json['subject']);
							}

							$field.html(json[key]);

							if($field.parent().hasClass('hide')){
								$field.parent().show();
							}
						}
					}

					if(json['text']){
						$emailBoxText.hide().html(json['text']);
						$buttons.filter('[data-button=plaintext]').show();
					} else {
						$buttons.filter('[data-button=plaintext]').hide();
					}

					if(json['html']){
						iframe.document.open();
						iframe.document.write(json['html']);
						iframe.document.close();
					}

					$buttons.filter('[data-button=reply]').attr('href', 'mailto:' + json['to'] + '?subject=' + json['subject']);

					if($emailBox.is(":hidden")){
						$emailBox.show();
						$("html, body").animate({ scrollTop:($emailBox.offset().top-10) }, 500, "linear");
					}

					$buttons.filter('[data-button=html]:not(.hide)').click();
				},
				error: function(xhr, ajaxOptions, thrownError) {
					console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	            }
			});
		});

		// toggle: html OR text
		$buttons.click(function(e){
			switch($(this).data('button')){
				case 'plaintext':
					$iframe.hide();
					$emailBoxText.show();

					$(this).hide();
					$buttons.filter('[data-button=html]').show();
				break;

				case 'html':
					$emailBoxText.hide();
					$iframe.show();

					$(this).hide();
					$buttons.filter('[data-button=plaintext]').show();
				break;

				case 'delete':
					$('#form')
						.attr('action', 'index.php?route=module/emailtemplate/logs&control=delete&token=<?php echo $token; ?><?php echo $url_params; ?>')
						.append(
							$("<input>")
				               .attr("type", "hidden")
				               .attr("name", "selected").val($emailBox.data('id')))
						.submit();
				break;
				default:
					return;
			}
			e.preventDefault();
		});

		// delete

		$("[data-button=filter]").click(function(e){
			e.preventDefault();
			var url = "index.php?route=module/emailtemplate/logs&token=<?php echo $token; ?>";

			var $filter_emailtemplate_id = $('#filter_emailtemplate_id');
			if($filter_emailtemplate_id.val() != ''){
				url += '&filter_emailtemplate_id=' + encodeURIComponent($filter_emailtemplate_id.val());
			}

			var $filter_store_id = $('#filter_store_id');
			if($filter_store_id.val() != ''){
				url += '&filter_store_id=' + encodeURIComponent($filter_store_id.val());
			}

			var $filter_customer = $('#filter_customer');
			if($filter_customer.val() != ''){
				url += '&filter_customer=' + encodeURIComponent($filter_customer.val());
				url += '&filter_customer_id=' + encodeURIComponent($('#filter_customer_id').val());
			}

			window.location = url;
		});
	});
})(jQuery);
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

$('#filter_customer').catcomplete({
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
		$('#filter_customer').val(ui.item.label);
		$('#filter_customer_id').val(ui.item.value);
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script>
<?php echo $footer; ?>