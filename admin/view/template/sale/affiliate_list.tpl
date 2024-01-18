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
			<h1><img src="view/image/customer.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('form').attr('action', '<?php echo $approve; ?>'); $('form').submit();" class="button"><?php echo $button_approve; ?></a><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
		</div>
		<div class="content">
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<table style="width: 100%;">
					<tbody>
						<tr class="filter">
							<td>
								<p>Имя партнера</p>
								<input type="text" name="filter_name" value="<?php echo $filter_name; ?>" />
							</td>
							<td>
								<p>E-Mail</p>
								<input type="text" name="filter_email" value="<?php echo $filter_email; ?>" />
							</td>
							
							<td>
								<p>Статус</p>
								<select name="filter_status">
									<option value="*"></option>
									<?php if ($filter_status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
									<?php } ?>
									<?php if (!is_null($filter_status) && !$filter_status) { ?>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="0"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
							<td>
								<p>Утверждено</p>
								<select name="filter_approved">
									<option value="*"></option>
									<?php if ($filter_approved) { ?>
										<option value="1" selected="selected"><?php echo $text_yes; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_yes; ?></option>
									<?php } ?>
									<?php if (!is_null($filter_approved) && !$filter_approved) { ?>
										<option value="0" selected="selected"><?php echo $text_no; ?></option>
										<?php } else { ?>
										<option value="0"><?php echo $text_no; ?></option>
									<?php } ?>
								</select>
							</td>
							<td>
								<p>Дата добавления</p>
							<input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" id="date" /></td>
							<td align="right">
								<p>&#160;</p>
							<a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
						</tr>
					</tbody>
				</table>
				<div class="filter_bord"></div>
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
							<td class="left"><?php if ($sort == 'name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
							<?php } ?></td>
							<td class="left"><?php if ($sort == 'c.email') { ?>
								<a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
							<?php } ?></td>
							<td class="left">Код отслеживания</td>
							<td class="left">Линк</td>								
							<td class="left"><?php if ($sort == 'c.status') { ?>
								<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
							<?php } ?></td>
							<td class="left"><?php if ($sort == 'c.approved') { ?>
								<a href="<?php echo $sort_approved; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_approved; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_approved; ?>"><?php echo $column_approved; ?></a>
							<?php } ?></td>
							<td class="left"><?php if ($sort == 'c.date_added') { ?>
								<a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
							<?php } ?></td>
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						
						<?php if ($affiliates) { ?>
							<?php foreach ($affiliates as $affiliate) { ?>
								<tr>
									<td style="text-align: center;">
										<?php if ($affiliate['selected']) { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $affiliate['affiliate_id']; ?>" checked="checked" />
										<?php } else { ?>
											<input type="checkbox" name="selected[]" value="<?php echo $affiliate['affiliate_id']; ?>" />
										<?php } ?>
									</td>
									<td class="left">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF;"><?php echo $affiliate['name']; ?></span>
									</td>
									<td class="center">
										<?php echo $affiliate['email']; ?>											
									</td>
									
									<td class="center">
										<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF;"><?php echo $affiliate['code']; ?></kbd>
									</td>

									<td class="center">
										<kbd>?tracking=<?php echo $affiliate['code']; ?></kbd>																			
									</td>
									
									<td class="center">
										<? if ($affiliate['status']) { ?>
											<i class="fa fa-check-circle" style="color:#4ea24e"></i>
										<? } else { ?>
											<i class="fa fa-times-circle" style="color:#cf4a61"></i>
										<? } ?>											
									</td>

									<td class="center">
										<? if ($affiliate['approved']) { ?>
											<i class="fa fa-check-circle" style="color:#4ea24e"></i>
										<? } else { ?>
											<i class="fa fa-times-circle" style="color:#cf4a61"></i>
										<? } ?>											
									</td>

									<td class="center"><small><?php echo $affiliate['date_added']; ?></small></td>
									
									<td class="right">
										<?php foreach ($affiliate['action'] as $action) { ?>
											<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="8"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
	function filter() {
		url = 'index.php?route=sale/affiliate&token=<?php echo $token; ?>';
		
		var filter_request_payment = $('select[name=\'filter_request_payment\']').attr('value');
		if (filter_request_payment != '*') {
			url += '&filter_request_payment=' + encodeURIComponent(filter_request_payment); 
		}
		
		var filter_name = $('input[name=\'filter_name\']').attr('value');
		
		if (filter_name) {
			url += '&filter_name=' + encodeURIComponent(filter_name);
		}
		
		var filter_email = $('input[name=\'filter_email\']').attr('value');
		
		if (filter_email) {
			url += '&filter_email=' + encodeURIComponent(filter_email);
		}
		
		var filter_affiliate_group_id = $('select[name=\'filter_affiliate_group_id\']').attr('value');
		
		if (filter_affiliate_group_id != '*') {
			url += '&filter_affiliate_group_id=' + encodeURIComponent(filter_affiliate_group_id);
		}	
		
		var filter_status = $('select[name=\'filter_status\']').attr('value');
		
		if (filter_status != '*') {
			url += '&filter_status=' + encodeURIComponent(filter_status); 
		}	
		
		var filter_approved = $('select[name=\'filter_approved\']').attr('value');
		
		if (filter_approved != '*') {
			url += '&filter_approved=' + encodeURIComponent(filter_approved);
		}	
		
		var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
		
		if (filter_date_added) {
			url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
		}
		
		location = url;
	}
//--></script> 
<script type="text/javascript"><!--
	$(document).ready(function() {
		$('#date').datepicker({dateFormat: 'yy-mm-dd'});
	});
//--></script> 
<script type="text/javascript"><!--
	$('input[name=\'filter_name\']').autocomplete({
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
			$('input[name=\'filter_name\']').val(ui.item.label);
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
//--></script> 
<?php echo $footer; ?>