<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="g f-content">
		<?php if (isset($warning) && !empty($warning)) { ?>
		<div class="f-message f-message-error"><?php echo $warning; ?></div>
		<?php } ?>
		<?php if (isset($success) && !empty($success)) { ?>
		<div class="f-message f-message-success"><?php echo $success; ?></div>
		<?php } ?>
		<?php echo $app_header; ?>
		<div class="g-row">
			<div class="g-6 f-sub-header">
				<h3><?php echo $heading_title; ?></h3>
			</div>
			<div class="g-6 f-text-right">&nbsp;</div>
		</div>
		<div class="g-row">
			<div id="tab_export" class="f-tabs">
				<form action="<?php echo $action_export; ?>" method="post" id="form_category_export" enctype="multipart/form-data">
					<div class="g-row">
						<div class="g-7">
							<div class="f-row">
								<label><?php echo $entry_file_format;?></label>
								<div class="f-input">
									<select name="csv_export[file_format]" class="g-2">
										<option value="csv" <?php if( isset($csv_export['file_format']) AND $csv_export['file_format'] == 'csv' ) echo ' selected="selected"';?>>&nbsp;CSV&nbsp;</option>
										<option value="vcf" <?php if( isset($csv_export['file_format']) AND $csv_export['file_format'] == 'vcf' ) echo ' selected="selected"';?>>&nbsp;vCard&nbsp;</option>
									</select>
								</div>
							</div>
							<div class="f-row">
								<label><?php echo $entry_file_encoding;?></label>
								<div class="f-input">
									<select name="csv_export[file_encoding]" class="g-3">
									<?php if (isset($charsets) && !empty($charsets)) { ?>
										<?php foreach ($charsets as $key => $val) { ?>
											<?php if ( $csv_export['file_encoding'] == $key ) { ?>
												<option value="<?php echo $key;?>" selected="selected"><?php echo $val;?></option>
											<?php } else { ?>
												<option value="<?php echo $key;?>"><?php echo $val;?></option>
											<?php } ?>
										<?php } ?>
									<?php } else { ?>
										<option value="UTF-8" <?php if ( $csv_export['file_encoding'] == 'UTF-8' ) echo 'selected'; ?>>UTF-8</option>
										<option value="WINDOWS-1251" <?php if ( $csv_export['file_encoding'] == 'WINDOWS-1251' ) echo 'selected'; ?>>Windows-1251</option>
									<?php } ?>
									</select>
								</div>
							</div>
							<div class="f-row">
								<label><?php echo $entry_csv_delimiter;?></label>
								<div class="f-input">
									<select name="csv_export[csv_delimiter]" class="g-1">
										<option value=";" <?php if( isset($csv_export['csv_delimiter']) AND $csv_export['csv_delimiter'] == ';' ) echo ' selected="selected"';?>> ; </option>
										<option value="," <?php if( isset($csv_export['csv_delimiter']) AND $csv_export['csv_delimiter'] == ',' ) echo ' selected="selected"';?>> , </option>
										<option value="^" <?php if ( isset($csv_export['csv_delimiter']) && $csv_export['csv_delimiter'] == '^' ) echo ' selected="selected"'; ?>> ^ </option>
									</select>
								</div>
							</div>
							<div class="f-row">
								<label><?php echo $entry_customer_group;?></label>
								<div class="f-input">
									<select name="csv_export[customer_group_id]" class="g-3">
										<option value="0" <?php if ($csv_export['customer_group_id'] == 0) echo ' selected="selected"'; ?>><?php echo $text_all; ?></option>
										<?php foreach ($customer_groups as $customer_group) { ?>
										<?php if ($customer_group['customer_group_id'] == $csv_export['customer_group_id']) { ?>
										<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="f-row">
								<label><?php echo $entry_newsletter;?></label>
								<div class="f-input">
									<select name="csv_export[newsletter]" class="g-2">
										<option value="2" <?php if( isset($csv_export['newsletter']) AND $csv_export['newsletter'] == 2) echo ' selected="selected"';?>>&nbsp;</option>
										<option value="0" <?php if( isset($csv_export['newsletter']) AND $csv_export['newsletter'] == 0 ) echo ' selected="selected"';?>><?php echo $text_enabled?></option>
										<option value="1" <?php if( isset($csv_export['newsletter']) AND $csv_export['newsletter'] == 1 ) echo ' selected="selected"';?>><?php echo $text_disabled?></option>
									</select>
								</div>
							</div>
							<div class="f-row">
								<label><?php echo $entry_date_start;?></label>
								<div class="f-input">
									<input type="text" name="csv_export[date_start]" value="<?php echo $csv_export['date_start'];?>" size="16" class="datetime" />
								</div>
							</div>
							<div class="f-row">
								<label><?php echo $entry_date_end;?></label>
								<div class="f-input">
									<input type="text" name="csv_export[date_end]" value="<?php echo $csv_export['date_end'];?>" size="16" class="datetime" />
								</div>
							</div>
							<div class="f-row">
								<label><?php echo $entry_status; ?></label>
								<div class="f-input">
									<select class="g-2" name="csv_export[status]">
										<option value="2" <?php if( isset($csv_export['status']) AND $csv_export['status'] == 2) echo ' selected="selected"';?>>&nbsp;</option>
										<option value="1" <?php if( isset($csv_export['status']) AND $csv_export['status'] == 1) echo ' selected="selected"';?>><?php echo $text_enabled; ?></option>
										<option value="0" <?php if( isset($csv_export['status']) AND $csv_export['status'] == 0) echo ' selected="selected"';?>><?php echo $text_disabled; ?></option>
									</select>
								</div>
								<!-- end f-input -->
							</div>
						</div>
						<!-- end g-7 -->
						<div class="g-5">
							<div><a onclick="$('.f-field-set').find(':checkbox:not(:checked)').parent().parent().parent().hide();"><?php echo $text_hide_all; ?></a> / <a onclick="$('.f-field-set').find('tr').show();"><?php echo $text_show_all; ?></a></div>
							<table class="f-field-set">
								<?php foreach( $csv_export['fields_set_data'] as $field ) { ?>
								<tr id="row_<?php echo $field['uid']; ?>">
									<td>
										<label title="<?php echo $fields_set_help[$field['uid']]; ?> <?php echo $field['uid']; ?>">
										<input <?php if (array_key_exists($field['uid'], $csv_export['fields_set']) || $field['uid'] == '_ID_') echo 'checked="checked"';?> <?php    if ($field['uid'] == '_ID_') echo ' disabled="disabled" class="field_id" ';?> type="checkbox" id="<?php echo $field['uid']; ?>" name="csv_export[fields_set][<?php echo $field['uid']; ?>]" value="1" />
										<?php echo $fields_set_help[$field['uid']]; ?>
										</label>
									</td>
									<td><span><?php echo $field['uid']; ?></span></td>
								</tr>
								<?php } ?>
							</table>
							<input type="hidden" name="csv_export[fields_set][_ID_]" value="1">
							<a onclick="$(this).parent().find(':checkbox').attr('checked', true);initFieldsSet();"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);initFieldsSet();"><?php echo $text_unselect_all; ?></a>
						</div>
						<!-- end g-5 -->
					</div>
				</form>
				<!-- end g-row -->
				<div class="f-row"></div>
				<div class="f-row f-actions-left"><a onclick="$('#form_category_export').submit();" class="f-bu f-bu-default g-2"><?php echo $button_export;?></a></div>
			</div>
			<!-- end tab_export -->		
		</div>
	</div>
	<?php echo $app_footer; ?>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
	<?php echo $js_datepicker_regional;?>
	$('.datetime').datetimepicker({
	    dateFormat: 'yy-mm-dd',
	    timeFormat: 'hh:mm',
		changeMonth: true,
		changeYear: true
	});
	function setBackgroundColor(obj) {
		    var row = '#row_' + $(obj).attr('id') + ' td';
		    if($(obj).attr('checked') == 'checked'){
		        $(row).addClass('selected');
		    } else {
		        $(row).removeClass('selected');
		    }
		}
	function initFieldsSet() {
		    $('.field_id').attr('checked', 'checked');
		    $('.f-field-set input[type=checkbox]').each(function() {
		        setBackgroundColor(this);
		    });
		}
		
		$(document).ready(function(){
			initFieldsSet();
		});
		
		$('.f-field-set input[type=checkbox]').change(function(){
		    setBackgroundColor(this);
		});
	//--></script>
<?php echo $footer; ?>