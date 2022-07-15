<?php echo $header; ?>

<div id="content">
	<div class="breadcrumb">
 	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
  		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  	<?php } ?>
	</div>

	<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
	<?php if ($error_attention) { ?><div class="attention"><?php echo $error_attention; ?></div><?php } ?>
	<?php if ($success) { ?><div class="success"><?php echo $success; ?></div><?php } ?>

	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<div class="box" id="emailtemplate">
			<div class="heading order_head">
				<h1><?php echo $heading_title; ?></h1>

				<div class="buttons">
					<a class="button" href="<?php echo $support_url; ?>" target="_blank" class="button button-secondary"><span><?php echo $text_support; ?></span></a>
					<span style="width:1px; height:24px; background:#e2e2e2; border-right:1px solid #fff; border-left:1px solid #fff; display:inline-block; *display:inline; zoom:1; line-height:0; vertical-align:top; margin: 0 1px 0 2px;"></span>
					<a class="button" href="<?php echo $cancel; ?>" class="button"><span><?php echo $button_back; ?></span></a>
				</div>
			</div>

			<div class="content">
				<div id="body-templates">
					<div class="mail content-heading">
						<span class="heading"><?php echo $heading_templates; ?></span>

						<div class="buttons" style="float:right; margin:0 12px">
							<a href="<?php echo $new_template; ?>" class="button button-secondary"><span><?php echo $button_insert_template; ?></span></a>
						</div>
					</div>

			        <table class="list" id="template_list">
						<thead>
							<tr>
								<td class="left"><a href="<?php echo $sort_label; ?>" class="<?php if ($sort == 'label') echo strtolower($order); ?>"><?php echo $column_label; ?></a></td>

								<td class="left"> <a href="<?php echo $sort_key; ?>" class="<?php if ($sort == 'key') echo strtolower($order); ?>"><?php echo $column_key; ?></a></td>

			              		<?php if(count($stores) > 1){ ?><td class="center" width="150"><a href="<?php echo $sort_store; ?>" class="<?php if ($sort == 'store') echo strtolower($order); ?>"><?php echo $column_store; ?></a></td><?php } ?>
			              		<?php if($templates_customer_group_id == '' && count($customer_groups) > 1){ ?><td class="center" width="120"><a href="<?php echo $sort_customer_group; ?>" class="<?php if ($sort == 'customer_group') echo strtolower($order); ?>"><?php echo $column_customer_group; ?></a></td><?php } ?>
			              		<td class="center" width="80"><a href="<?php echo $sort_modified; ?>" class="<?php if ($sort == 'modified') echo strtolower($order); ?>"><?php echo $column_modified; ?></a></td>
			              		<td class="center" width="40"><a href="<?php echo $sort_shortcodes; ?>" class="<?php if ($sort == 'shortcodes') echo strtolower($order); ?>"><?php echo $column_tested; ?></a></td>
			              		<td class="center" width="30"><a href="<?php echo $sort_status; ?>" class="<?php if ($sort == 'status') echo strtolower($order); ?>"><?php echo $column_status; ?></a></td>
								<td class="right" width="45"><?php echo $column_action; ?></td>
								<td class="right" width="45"><?php echo $column_action; ?></td>
			            	</tr>
			          	</thead>
			          	<tbody>
						<?php if ($templates) { ?>
			            <?php foreach ($templates as $template) { ?>
			            	<tr<?php if($template['action']){ ?> data-href="<?php echo $template['action']; ?>" style="cursor:pointer"<?php } ?>>
			              		<td class="left"><b><?php echo str_replace(':', ':</b>', $template['label']) . ($template['custom_count'] ? ' (' . $template['custom_count'] . ')': ''); ?></td>

			              		<td class="left"><b><?php echo $template['key']; ?></b></td>

		              			<?php if(count($stores) > 1){ ?><td class="center"><?php echo isset($template['store']['store_name']) ? $template['store']['store_name'] : '-'; ?></td><?php } ?>
		              			<?php if($templates_customer_group_id == '' && count($customer_groups) > 1){ ?><td class="center"><?php echo isset($template['customer_group']) ? $template['customer_group']['name'] : '-'; ?></td><?php } ?>
		              			
		              			<td class="center"><?php echo $template['modified']; ?></td>

		              			<td class="center"><span class="status_icon status-<?php echo $template['shortcodes']; ?>"><?php echo $template['shortcodes']; ?></span></td>
		              			<td class="center"><span class="status_icon status-<?php echo strtolower($template['status']); ?>"><?php echo $template['status']; ?></span></td>
			              		<td class="right">
			              			
			              			<?php if($template['action']){ ?>
			              				<a href="<?php echo $template['action']; ?>" class="button" title="Редактировать">Редактировать</a>
			              			<?php } ?>
			              		</td>
			              		
			              		<td class="right">	
			              			<?php if($template['action_delete']){ ?><a href="<?php echo $template['action_delete']; ?>" class="button" title="Удалить" onclick="return confirm('<?php echo sprintf($text_delete_confirm, $template['name']); ?>')">Удалить</a><?php } ?>
		              			</td>
			            	</tr>
			            <?php } ?>
			            <?php } else { ?>
			            	<tr>
			              		<td class="center" colspan="0">
			              			<p><?php echo $text_no_results; ?></p>
		              			</td>
		            		</tr>
			            <?php } ?>
			          </tbody>
			        </table>

			    	<div class="pagination" style="text-align: center;">
						<?php if ($templates) { ?>
					    	<?php echo $pagination; ?>
				    	<?php } ?>

			    		<div class="filter">
							<?php if(!empty($stores) && count($stores) > 1){ ?>
								<label for="field_templates_store_id" style="font-style: italic;"><?php echo $entry_store; ?></label>
								<select name="templates_store_id" id="field_templates_store_id" style="margin-right:15px; padding: 4px 3px; width:100px">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach($stores as $store){ ?>
									<option value="<?php echo $store['store_id']; ?>"<?php if($templates_store_id == $store['store_id'] && is_numeric($templates_store_id)) echo ' selected="selected"'; ?>><?php echo $store['store_name']; ?></option>
									<?php } ?>
								</select>
							<?php } ?>

							<?php if(!empty($customer_groups) && count($customer_groups) > 1){ ?>
								<label for="field_templates_customer_group_id" style="font-style: italic;"><?php echo $entry_customer_group; ?></label>
								<select name="templates_customer_group_id" id="field_templates_customer_group_id" style="margin-right:15px; padding: 4px 3px; width:100px">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach($customer_groups as $customer_group){ ?>
									<option value="<?php echo $customer_group['customer_group_id']; ?>"<?php if($templates_customer_group_id == $customer_group['customer_group_id']) echo ' selected="selected"'; ?>><?php echo $customer_group['name']; ?></option>
									<?php } ?>
								</select>
							<?php } ?>

							<label for="field_templates_key" style="font-style: italic;"><?php echo $entry_key; ?></label>
							<select name="templates_key" id="field_templates_key" style="margin-right:15px; padding: 4px 3px; width:100px">
								<option value=""><?php echo $text_select; ?></option>
								<?php foreach($emailtemplate_keys as $row){ ?>
									<option value="<?php echo $row['value']; ?>"<?php if($templates_key == $row['value']) echo ' selected="selected"'; ?>><?php echo $row['label']; ?></option>
								<?php } ?>
							</select>

							<button data-button="filter" class="button"><span><?php echo $button_filter;  ?></span></button>
						</div>
					</div>
		    	</div>

				<div id="body-config" style="margin-top: 20px; width:49%; float: left;">
					<div class="setting content-heading content-heading-a">
						<a href="<?php echo $config_url; ?>">
							<span class="heading"><?php echo $heading_config; ?></span>
							<span class="info"><?php echo $text_config_headline; ?></span>
						</a>
					</div>
				</div>

				<div id="body-config" style="margin-top: 20px; width:49%; float: right;">
					<div class="logs content-heading content-heading-a">
						<a href="<?php echo $logs_url; ?>">
							<span class="heading"><?php echo $heading_logs; ?></span>
							<span class="info"><?php echo $text_logs_headline; ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<link type="text/css" href="view/stylesheet/module/emailtemplate.css" rel="stylesheet" media="screen" />

<script type="text/javascript">
function parseVersionString(a) {
    if (typeof a != "string") return false;
    var b = 0,
        c = a.split("-");
    var d = a.split(".");
    a = parseInt(d[0]) || 0;
    c = parseInt(d[1]) || 0;
    var e = parseInt(d[2]) || 0;
    d = parseInt(d[3]) || 0;
    return a * 1E8 + c * 1E6 + e * 1E4 + d * 100 + b
}

(function($){

	function row_click(e){
		var target = e.srcElement || e.target;
		if((target instanceof HTMLInputElement) || (target instanceof HTMLAnchorElement)){
			return true;
		}
		if (e.ctrlKey){
			window.open($(this).data('href'), '_blank');
		} else {
			window.location.href = $(this).data('href');
		}
	}

	if($.fn.on){
		$("#template_list").on("dblclick", "tr[data-href]", row_click);
	} else {
		$("#template_list").delegate("tr[data-href]", "dblclick", row_click);
	}

	function current_version(){
		var current = parseVersionString('<?php echo EmailTemplate::$version; ?>'),
	        latest = parseVersionString(EmailTemplate_latest_version);
	    if (latest > current) {
	        var text = "<?php echo $text_newer_version; ?>".replace('%s', EmailTemplate_latest_version).replace('%s', EmailTemplate_latest_date);
	        $("#version-update").show().find('.info').html(text);
	    }
	}

	$(document).ready(function() {
		$.getScript("http://www.opencart-templates.co.uk/version.js", current_version);

		$("[data-button=filter]").click(function(e){
			e.preventDefault();
			var url = "index.php?route=module/emailtemplate&token=<?php echo $this->request->get['token']; ?>";

			var $storeId = $('#field_templates_store_id');
			if($storeId.length){
				url += '&store_id=' + $storeId.val();
			}

			var $customerGroupId = $('#field_templates_customer_group_id');
			if($customerGroupId.val()){
				url += '&customer_group_id=' + $customerGroupId.val();
			}

			var $key = $('#field_templates_key');
			if($key.val()){
				url += '&key=' + $key.val();
			}

			window.location = url;
		});


	});

})(jQuery);
</script>

<?php echo $footer; ?>