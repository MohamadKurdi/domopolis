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
		<div class="heading order_head">
			<h1><img src="view/image/mail.png" alt="" />
				<?php echo $heading_template;
				if($emailtemplate['label'] == 0){
					echo ': ' .  $emailtemplate['label'];
				} ?>
			</h1>

			<div class="buttons">
				<a id="submitButton" href="javascript:void(0)" onclick="$('#form').attr('action', '<?php echo $action; ?>'); $('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
				<?php if(isset($action_delete)){ ?>
					<a href="javascript:void(0)" onclick="$('#form').attr('action', '<?php echo $action_delete; ?>'); $('#form').submit();" class="button"><span><?php echo $button_delete; ?></span></a>
				<?php } ?>

				<span style="width:1px; height:24px; background:#e2e2e2; border-right:1px solid #fff; border-left:1px solid #fff; display:inline-block; *display:inline; zoom:1; line-height:0; vertical-align:top; margin: 0 1px 0 2px;"></span>

				<?php if(isset($template_default_url)){ ?>
					<a href="<?php echo $template_default_url; ?>" class="button"><span><?php echo $button_default_template; ?></span></a>
				<?php } ?>

				<a href="<?php echo $new_template; ?>" class="button button-secondary"><span><?php echo $button_insert_template; ?></span></a>

				<a href="<?php echo $cancel; ?>" class="button button-secondary"><span><?php echo $button_back; ?></span></a>
			</div>

			<?php if($emailtemplate['modified']){ ?>
			<div style="font-size: 11px; float: right; margin: 12px 10px 0 0;">
				<?php echo $text_modified . ': <i>' . $emailtemplate['modified'].'</i>'; ?>
			</div>
			<?php } ?>
		</div>

		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" style="position:relative">
				<?php if(isset($vqmod_xml)): ?>
					<?php foreach($vqmod_xml as $vqmod_xml_file => $vqmod_xml_content){ ?>
						<p class="error"><?php echo sprintf($error_vqmod_xml, $vqmod_xml_file); ?></p>
						<textarea style="width:100%; height: 150px;"><?php echo $vqmod_xml_content; ?></textarea>
						<br />
					<?php } ?>
				<?php endif;?>

				<div class="tabsHolder">
	    			<div class="vtabs tabs-nav" id="settings-tab">
	    				<a href="#tab-setting"><?php echo $heading_settings; ?></a>
	    				<a href="#tab-advanced"><?php echo $heading_advanced; ?></a>
	    				<a href="#tab-mail"><?php echo $heading_mail; ?></a>
	    				<a href="#tab-theme" style="margin-bottom: 20px"><?php echo $heading_theme; ?></a>
	    				<?php if (!empty($templates)) { ?>
		    				<a href="#tab-templates"><?php echo $heading_custom_templates; ?></a>
	    				<?php } ?>
		    			<?php if (!empty($shortcodes)) { ?>
		    				<a href="#tab-shortcodes"><?php echo $heading_shortcodes; ?></a>
	    				<?php } ?>
					</div>

					<div id="tab-setting" class="vtabs-content">
						<table class="form" style="margin-bottom:0">
		    				<tr>
								<td>
									<label for="emailtemplate_key"><span class="required">*</span> <?php echo $entry_key; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_key" value="<?php echo $emailtemplate['key']; ?>" placeholder="<?php echo $text_placeholder_key; ?>" id="emailtemplate_key" />
									<?php if(isset($error_emailtemplate_key)) { ?><span class="error"><?php echo $error_emailtemplate_key; ?></span><?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_label"><span class="required">*</span> <?php echo $entry_label; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_label" value="<?php echo $emailtemplate['label']; ?>" id="emailtemplate_label" />
									<?php if(isset($error_emailtemplate_label)) { ?><span class="error"><?php echo $error_emailtemplate_label; ?></span><?php } ?>
								</td>
							</tr>

							<?php if(!empty($emailtemplate_files)){ ?>
	    					<tr>
								<td>
									<label for="emailtemplate_template"><?php echo $entry_template_file; ?></label>
								</td>
								<td>
									<select name="emailtemplate_template" id="emailtemplate_template" class="large">
										<option value="">- - - <?php echo $text_none; ?> - - -</option>
										<?php if(!empty($emailtemplate_files['catalog'])): ?>
										<optgroup label="<?php echo $emailtemplate_files['dirs']['catalog']; ?>" data-type="catalog">
											<?php foreach($emailtemplate_files['catalog'] as $file){ ?>
												<?php if($emailtemplate['template'] == $file){ ?>
													<option value="<?php echo $file; ?>" selected="selected"><?php echo $file; ?></option>
												<?php } else { ?>
													<option value="<?php echo $file; ?>"><?php echo $file; ?></option>
												<?php } ?>
											<?php } ?>
										</optgroup>
										<?php endif; ?>

										<?php if(!empty($emailtemplate_files['catalog_default'])): ?>
										<optgroup label="<?php echo $emailtemplate_files['dirs']['catalog_default']; ?>" data-type="catalog">
											<?php foreach($emailtemplate_files['catalog_default'] as $file){ ?>
												<?php if($emailtemplate['template'] == $file){ ?>
													<option value="<?php echo $file; ?>" selected="selected"><?php echo str_replace($emailtemplate_files['dirs']['catalog_default'], '', $file); ?></option>
												<?php } else { ?>
													<option value="<?php echo $file; ?>"><?php echo str_replace($emailtemplate_files['dirs']['catalog_default'], '', $file); ?></option>
												<?php } ?>
											<?php } ?>
										</optgroup>
										<?php endif; ?>

										<?php if(!empty($emailtemplate_files['admin'])): ?>
										<optgroup label="<?php echo $emailtemplate_files['dirs']['admin']; ?>" data-type="admin">
											<?php foreach($emailtemplate_files['admin'] as $file){ ?>
												<?php if($emailtemplate['template'] == $file){ ?>
													<option value="<?php echo $file; ?>" selected="selected"><?php echo str_replace($emailtemplate_files['dirs']['admin'], '', $file); ?></option>
												<?php } else { ?>
													<option value="<?php echo $file; ?>"><?php echo str_replace($emailtemplate_files['dirs']['admin'], '', $file); ?></option>
												<?php } ?>
											<?php } ?>
										</optgroup>
										<?php endif; ?>
									</select>
									<?php if($emailtemplate['template']){ ?>
										<span class="help"><?php echo $emailtemplate_template_path . $emailtemplate['template']; ?></span>
									<?php } ?>
									<?php if(isset($error_emailtemplate_template)) { ?><span class="error"><?php echo $error_emailtemplate_template; ?></span><?php } ?>
								</td>
							</tr>
							<?php } ?>

							<tr>
								<td>
									<label for="emailtemplate_status"><span class="required">*</span> <?php echo $entry_status; ?></label>
								</td>
								<td>
									<label class="radio">
										<input type="radio" name="emailtemplate_status" value="ENABLED" <?php echo ($emailtemplate['status'] == '' || $emailtemplate['status'] == 'ENABLED') ? 'checked="checked"' : ''; ?>/>
										<?php echo $text_enabled; ?>
									</label>
									<label class="radio">
										<input type="radio" name="emailtemplate_status" value="DISABLED" <?php echo ($emailtemplate['status'] == 'DISABLED') ? 'checked="checked"' : ''; ?>/>
										<?php echo $text_disabled; ?>
									</label>

									<?php if(isset($error_emailtemplate_status)) { ?><span class="error"><?php echo $error_emailtemplate_status; ?></span><?php } ?>
								</td>
							</tr>
						</table>

						<?php if($emailtemplate['default'] == 0){ ?>
						<div id="table-options">
							<h2><?php echo $heading_conditions; ?></h2>

							<input type="hidden" name="emailtemplate_condition" value="" />

							<table class="form">
								<?php if(!empty($emailtemplate_shortcodes)){ ?>
								<tr data-field='conditionsShortcodes'>
									<td><label><?php echo $entry_condition; ?></label></td>
									<td>
										<select id="condition_add_select">
											<option value=""><?php echo $text_select; ?></option>
											<?php foreach($emailtemplate_shortcodes as $item){ ?>
												<?php if(substr($item['code'], 0, 5) == 'text_' ||
														 substr($item['code'], 0, 7) == 'button_' ||
														 substr($item['code'], 0, 6) == 'error_' ||
														 substr($item['code'], 0, 6) == 'entry_') continue; ?>
												<option><?php echo $item['code']; ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
								<?php } ?>
								<tr data-field='conditionsShortcodes'<?php if(empty($emailtemplate['condition'])){ ?> style="display:none;"<?php } ?>>
									<td>&nbsp;</td>
									<td>
										<table id="emailtemplate_conditions">
											<tbody>
												<?php if(!empty($emailtemplate['condition']) && is_array($emailtemplate['condition'])){ ?>
												<?php foreach($emailtemplate['condition'] as $i => $item){ ?>
												<tr data-count="<?php echo $i; ?>">
													<td><input type="text" name="emailtemplate_condition[<?php echo $i; ?>][key]" value="<?php echo $item['key']; ?>" /></td>
													<td>
														<select name="emailtemplate_condition[<?php echo $i; ?>][operator]">
															<option value="=="<?php if($item['operator'] == '==') echo ' selected="selected"'; ?>>(==) Equal</option>
															<option value="!="<?php if($item['operator'] == '!=') echo ' selected="selected"'; ?>>(!=) &nbsp;Not Equal</option>
															<option value="&gt;"<?php if($item['operator'] == '&gt;') echo ' selected="selected"'; ?>>(&gt;) &nbsp;&nbsp;Greater than</option>
															<option value="&lt;"<?php if($item['operator'] == '&lt;') echo ' selected="selected"'; ?>>(&lt;) &nbsp;&nbsp;Less than</option>
															<option value="&gt;="<?php if($item['operator'] == '&gt;=') echo ' selected="selected"'; ?>>(&gt;=) Greater than or equal to </option>
															<option value="&lt;="<?php if($item['operator'] == '&lt;=') echo ' selected="selected"'; ?>>(&lt;=) Less than or equal to </option>
															<option value="IN"<?php if($item['operator'] == 'IN') echo ' selected="selected"'; ?>>(IN) Checks if a value exists in comma-delimited string </option>
															<option value="NOTIN"<?php if($item['operator'] == 'NOTIN') echo ' selected="selected"'; ?>>(NOTIN) Checks if a value does not exist in comma-delimited string </option>
														</select>
													</td>
													<td><input type="text" name="emailtemplate_condition[<?php echo $i; ?>][value]" value="<?php echo $item['value']; ?>" placeholder="Value" /></td>
													<td><a class="button" onclick="$(this).parents('tr').eq(0).remove()"><?php echo $button_delete; ?></a></td>
												</tr>
												<?php } ?>
												<?php } ?>
											</tbody>
										</table>
									</td>
								</tr>

								<?php if(!empty($order_statuses)){ ?>
								<tr data-field='order_status'>
									<td><label for="order_status_id"><?php echo $entry_order_status; ?></label></td>
									<td>
										<select name="order_status_id" id="order_status_id">
											<option value=""><?php echo $text_select; ?></option>
											<?php foreach($order_statuses as $item){ ?>
											<option value="<?php echo $item['order_status_id']; ?>"<?php if($emailtemplate['order_status_id'] == $item['order_status_id']) echo ' selected="selected"'; ?>><?php echo $item['name']; ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
								<?php } ?>

								<?php if(!empty($customer_groups)){ ?>
								<tr>
									<td><label for="customer_group_id"><?php echo $entry_customer_group; ?></label></td>
									<td>
										<select name="customer_group_id" id="customer_group_id">
											<option value=""><?php echo $text_select; ?></option>
											<?php foreach($customer_groups as $item){ ?>
											<option value="<?php echo $item['customer_group_id']; ?>"<?php if($emailtemplate['customer_group_id'] == $item['customer_group_id']) echo ' selected="selected"'; ?>><?php echo $item['name']; ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
								<?php } ?>

								<?php if(!empty($stores)){ ?>
								<tr>
									<td><label for="store_id"><?php echo $entry_store; ?></label></td>
									<td>
										<select name="store_id" id="store_id">
											<option value="NULL"><?php echo $text_select; ?></option>
											<?php foreach($stores as $store){ ?>
											<option value="<?php echo $store['store_id']; ?>"<?php if($emailtemplate['store_id'] == $store['store_id'] && is_numeric($emailtemplate['store_id'])) echo ' selected="selected"'; ?>><?php echo $store['store_name']; ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
								<?php } ?>
							</table>
						</div>
						<?php } ?>
					</div>

					<div id="tab-advanced" class="vtabs-content">
						<table class="form">
							<?php if(!empty($shortcodes_data['order_id'])){ ?>
							<tr>
								<td>
									<label for="emailtemplate_attach_invoice"><?php echo $entry_attach_invoice; ?></label>
								</td>
								<td>
									<label class="radio">
										<input type="radio" name="emailtemplate_attach_invoice" value="1" id="emailtemplate_attach_invoice" <?php echo ($emailtemplate['attach_invoice'] == 1) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_yes; ?>
									</label>
									<label class="radio">
										<input type="radio" name="emailtemplate_attach_invoice" value="0" id="emailtemplate_attach_invoice" <?php echo ($emailtemplate['attach_invoice'] == 0) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_no; ?>
									</label>
									<?php if(isset($error_emailtemplate_attach_invoice)) { ?><span class="error"><?php echo $error_emailtemplate_attach_invoice; ?></span><?php } ?>
								</td>
							</tr>
							<?php } ?>
							<tr>
								<td>
									<label for="emailtemplate_log"><?php echo $entry_log; ?></label>
								</td>
								<td>
									<label class="radio">
										<input type="radio" name="emailtemplate_log" value="1" id="emailtemplate_log" <?php echo ($emailtemplate['log'] == 1) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_yes; ?>
									</label>
									<label class="radio">
										<input type="radio" name="emailtemplate_log" value="0" id="emailtemplate_log" <?php echo ($emailtemplate['log'] == 0) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_no; ?>
									</label>
									<?php if(isset($error_emailtemplate_log)) { ?><span class="error"><?php echo $error_emailtemplate_log; ?></span><?php } ?>
								</td>
							</tr>

							<?php if($emailtemplate['log'] == 1){ ?>
							<tr>
								<td>
									<label for="emailtemplate_view_browser"><?php echo $entry_view_browser; ?></label>
								</td>
								<td>
									<label class="radio">
										<input type="radio" name="emailtemplate_view_browser" value="1" id="emailtemplate_view_browser" <?php echo ($emailtemplate['view_browser'] == 1) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_yes; ?>
									</label>
									<label class="radio">
										<input type="radio" name="emailtemplate_view_browser" value="0" id="emailtemplate_view_browser" <?php echo ($emailtemplate['view_browser'] == 0) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_no; ?>
									</label>
									<?php if(isset($error_emailtemplate_view_browser)) { ?><span class="error"><?php echo $error_emailtemplate_view_browser; ?></span><?php } ?>
								</td>
							</tr>

							<?php if($emailtemplate['view_browser'] == 1){ ?>
							<tr>
								<td>
									<label for="emailtemplate_view_browser_theme"><?php echo $entry_view_browser_theme; ?></label>
								</td>
								<td>
									<label class="radio">
										<input type="radio" name="emailtemplate_view_browser_theme" value="1" id="emailtemplate_view_browser_theme" <?php echo ($emailtemplate['view_browser_theme'] == 1) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_yes; ?>
									</label>
									<label class="radio">
										<input type="radio" name="emailtemplate_view_browser_theme" value="0" id="emailtemplate_view_browser_theme" <?php echo ($emailtemplate['view_browser_theme'] == 0) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_no; ?>
									</label>
									<?php if(isset($error_emailtemplate_view_browser_theme)) { ?><span class="error"><?php echo $error_emailtemplate_view_browser_theme; ?></span><?php } ?>
								</td>
							</tr>
							<?php } ?>
							<?php } ?>

							<?php if(!empty($emailtemplate_configs)){ ?>
							<tr>
								<td>
									<label for="emailtemplate_config_id"><?php echo $entry_email_config; ?></label>
								</td>
								<td>
									<select name="emailtemplate_config_id" id="emailtemplate_config_id">
										<option value="0"><?php echo $text_auto; ?></option>
										<?php foreach($emailtemplate_configs as $row): ?>
										<option value="<?php echo $row['emailtemplate_config_id']; ?>"<?php echo ($emailtemplate['emailtemplate_config_id'] == $row['emailtemplate_config_id']) ? 'selected="selected"' : ''; ?>><?php echo $row['name']; ?></option>
										<?php endforeach; ?>
									</select>
									<?php if(isset($error_emailtemplate_config_id)) { ?><span class="error"><?php echo $error_emailtemplate_config_id; ?></span><?php } ?>
								</td>
							</tr>
							<?php } ?>

							<tr>
								<td>
									<label for="emailtemplate_wrapper_tpl"><?php echo $entry_template_wrapper; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_wrapper_tpl" value="<?php echo $emailtemplate['wrapper_tpl']; ?>" placeholder="_main.tpl" id="emailtemplate_wrapper_tpl" />
									<?php if(isset($error_emailtemplate_wrapper_tpl)) { ?><span class="error"><?php echo $error_emailtemplate_wrapper_tpl; ?></span><?php } ?>
								</td>
							</tr>

							<tr>
								<td>
									<label for="emailtemplate_language_files"><?php echo $entry_language_files; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_language_files" value="<?php echo $emailtemplate['language_files']; ?>" id="emailtemplate_language_files" />
									<?php if(isset($error_emailtemplate_language_files)) { ?><span class="error"><?php echo $error_emailtemplate_language_files; ?></span><?php } ?>
								</td>
							</tr>

							<tr>
								<td>
									<label for="emailtemplate_tracking_campaign_source"><?php echo $entry_tracking_campaign_source; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_tracking_campaign_source" value="<?php echo $emailtemplate['tracking_campaign_source']; ?>" id="emailtemplate_tracking_campaign_source" />
									<?php if(isset($error_emailtemplate_tracking_campaign_source)) { ?><span class="error"><?php echo $error_emailtemplate_tracking_campaign_source; ?></span><?php } ?>
								</td>
							</tr>

							<tr>
								<td>
									<label for="emailtemplate_plain_text"><?php echo $entry_plain_text; ?></label>
								</td>
								<td>
									<input type="radio" name="emailtemplate_plain_text" value="1" id="emailtemplate_plain_text" <?php echo ($emailtemplate['plain_text'] == 1) ? ' checked="checked"' : ''; ?>/><?php echo $text_yes; ?>
									<input type="radio" name="emailtemplate_plain_text" value="0" id="emailtemplate_plain_text" <?php echo ($emailtemplate['plain_text'] == 0) ? ' checked="checked"' : ''; ?>/><?php echo $text_no; ?>
									<?php if(isset($error_emailtemplate_plain_text)) { ?><span class="error"><?php echo $error_emailtemplate_plain_text; ?></span><?php } ?>
								</td>
							</tr>

							<tr>
								<td>
									<label for="emailtemplate_integrate_extension"><?php echo $entry_integrate_extension; ?></label>
								</td>
								<td>
									<input type="radio" name="emailtemplate_integrate_extension" value="1" id="emailtemplate_integrate_extension" <?php echo ($emailtemplate['integrate_extension'] == 1) ? ' checked="checked"' : ''; ?>/><?php echo $text_yes; ?>
									<input type="radio" name="emailtemplate_integrate_extension" value="0" id="emailtemplate_integrate_extension" <?php echo ($emailtemplate['integrate_extension'] == 0) ? ' checked="checked"' : ''; ?>/><?php echo $text_no; ?>
									<?php if(isset($error_emailtemplate_integrate_extension)) { ?><span class="error"><?php echo $error_emailtemplate_integrate_extension; ?></span><?php } ?>
								</td>
							</tr>

							<tr>
								<td>
									<label for="emailtemplate_mail_attachment"><?php echo $entry_mail_attachment; ?></label>
								</td>
								<td>
									<input placeholder="/var/www/mysite/image/foo.pdf" class="large" type="text" name="emailtemplate_mail_attachment" value="<?php echo $emailtemplate['mail_attachment']; ?>" id="emailtemplate_mail_attachment" />
									<?php if(isset($error_emailtemplate_mail_attachment)) { ?><span class="error"><?php echo $error_emailtemplate_mail_attachment; ?></span><?php } ?>
								</td>
							</tr>
						</table>
					</div>

					<div id="tab-theme" class="vtabs-content">
						<table class="form">
							<tr>
								<td>
									<label for="emailtemplate_showcase"><?php echo $entry_showcase; ?></label>
								</td>
								<td>
									<label class="radio">
										<input type="radio" name="emailtemplate_showcase" value="1" id="emailtemplate_showcase" <?php echo ($emailtemplate['showcase'] == 1) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_yes; ?>
									</label>
									<label class="radio">
										<input type="radio" name="emailtemplate_showcase" value="0" id="emailtemplate_showcase" <?php echo ($emailtemplate['showcase'] == 0) ? ' checked="checked"' : ''; ?>/>
										<?php echo $text_no; ?>
									</label>
									<?php if(isset($error_emailtemplate_showcase)) { ?><span class="error"><?php echo $error_emailtemplate_showcase; ?></span><?php } ?>
								</td>
							</tr>
						</table>
					</div>

					<div id="tab-mail" class="vtabs-content">
						<table class="form">
	    					<tr>
								<td>
									<label for="emailtemplate_mail_to"><?php echo $entry_mail_to; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_mail_to" value="<?php echo $emailtemplate['mail_to']; ?>" id="emailtemplate_mail_to" />
									<?php if(isset($error_emailtemplate_mail_to)) { ?><span class="error"><?php echo $error_emailtemplate_mail_to; ?></span><?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_mail_from"><?php echo $entry_mail_from; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_mail_from" value="<?php echo $emailtemplate['mail_from']; ?>" id="emailtemplate_mail_from" />
									<?php if(isset($error_emailtemplate_mail_from)) { ?><span class="error"><?php echo $error_emailtemplate_mail_from; ?></span><?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_mail_sender"><?php echo $entry_mail_sender; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_mail_sender" value="<?php echo $emailtemplate['mail_sender']; ?>" id="emailtemplate_mail_sender" />
									<?php if(isset($error_emailtemplate_mail_sender)) { ?><span class="error"><?php echo $error_emailtemplate_mail_sender; ?></span><?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_mail_replyto"><?php echo $entry_mail_replyto; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_mail_replyto" value="<?php echo $emailtemplate['mail_replyto']; ?>" id="emailtemplate_mail_replyto" />
									<?php if(isset($error_emailtemplate_mail_replyto)) { ?><span class="error"><?php echo $error_emailtemplate_mail_replyto; ?></span><?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_mail_replyto_name"><?php echo $entry_mail_replyto_name; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_mail_replyto_name" value="<?php echo $emailtemplate['mail_replyto_name']; ?>" id="emailtemplate_mail_replyto_name" />
									<?php if(isset($error_emailtemplate_mail_replyto_name)) { ?><span class="error"><?php echo $error_emailtemplate_mail_replyto_name; ?></span><?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_mail_cc"><?php echo $entry_mail_cc; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_mail_cc" value="<?php echo $emailtemplate['mail_cc']; ?>" id="emailtemplate_mail_cc" />
									<?php if(isset($error_emailtemplate_mail_cc)) { ?><span class="error"><?php echo $error_emailtemplate_mail_cc; ?></span><?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<label for="emailtemplate_mail_bcc"><?php echo $entry_mail_bcc; ?></label>
								</td>
								<td>
									<input class="large" type="text" name="emailtemplate_mail_bcc" value="<?php echo $emailtemplate['mail_bcc']; ?>" id="emailtemplate_mail_bcc" />
									<?php if(isset($error_emailtemplate_mail_bcc)) { ?><span class="error"><?php echo $error_emailtemplate_mail_bcc; ?></span><?php } ?>
								</td>
							</tr>
						</table>
					</div>

					<?php if (!empty($templates)) { ?>
						<div id="tab-templates" class="vtabs-content">

							<table class="list" id="template_list">
								<thead>
									<tr>
										<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'template_selected\']').attr('checked', this.checked);" /></td>
										<td class="left"><a href="<?php echo $sort_label; ?>" class="<?php if ($sort == 'label') echo strtolower($order); ?>"><?php echo $column_label; ?></a> // <a href="<?php echo $sort_key; ?>" class="<?php if ($sort == 'key') echo strtolower($order); ?>"><?php echo $column_key; ?></a></td>
										<td class="left"><a href="<?php echo $sort_template; ?>" class="<?php if ($sort == 'template') echo strtolower($order); ?>"><?php echo $column_template; ?></a></td>
					              		<td class="center" width="80"><a href="<?php echo $sort_modified; ?>" class="<?php if ($sort == 'modified') echo strtolower($order); ?>"><?php echo $column_modified; ?></a></td>
					              		<td class="center" width="30"><a href="<?php echo $sort_status; ?>" class="<?php if ($sort == 'status') echo strtolower($order); ?>"><?php echo $column_status; ?></a></td>
										<td class="right" width="45"><?php echo $column_action; ?></td>
					            	</tr>
					          	</thead>
					          	<tbody>
					            <?php foreach ($templates as $template) { ?>
					            	<tr<?php if($template['action']){ ?> data-href="<?php echo $template['action']; ?>" style="cursor:pointer"<?php } ?>>
					              		<td style="text-align: center;"><input type="checkbox" name="template_selected[]" value="<?php echo $template['id']; ?>" /></td>
					              		<td class="left"><?php echo $template['label']. (($template['label'] && $template['key']) ? ' - ' : ' '); ?><b><?php echo $template['key']; ?></b></td>
					              		<td class="left"><?php echo $template['template']; ?></td>
				              			<td class="center"><?php echo $template['modified']; ?></td>
				              			<td class="center"><span class="status_icon status-<?php echo strtolower($template['status']); ?>"><?php echo $template['status']; ?></span></td>
					              		<td class="right">
					              			<?php if($template['action']){ ?><a href="<?php echo $template['action']; ?>" class="action-icon-edit action-icons" title="<?php echo $text_edit; ?>"><?php echo $text_edit; ?></a><?php } ?>
					              			<?php if($template['action_delete']){ ?><a href="<?php echo $template['action_delete']; ?>" class="action-icon-delete action-icons" title="<?php echo $button_delete; ?>" onclick="return confirm('<?php echo sprintf($text_delete_confirm, $template['name']); ?>')"><?php echo $button_delete; ?></a><?php } ?>
				              			</td>
					            	</tr>
					            <?php } ?>
					            </tbody>
					            <tfoot>
					          	<tr>
					          		<td colspan="0" bgcolor="#F2F2F2" style="padding:10px">
					          			<a href="javascript:void(0)" onclick="$('#form').attr('action', '<?php echo $template_delete; ?>'); $('#form').submit();" class="button"><span><?php echo $button_delete; ?></span></a>
					          		</td>
					          	</tr>
					          </tfoot>
					        </table>

						</div>
					<?php } ?>

					<?php if (!empty($shortcodes)) { ?>
						<div id="tab-shortcodes" class="vtabs-content">
							<table class="list" id="shortcodes_list">
								<thead>
									<tr>
										<?php if($emailtemplate['default'] == 1){ ?><td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'shortcode_selected\']').attr('checked', this.checked);" /></td><?php } ?>
										<td class="left" width="200"><a href="<?php echo $sort_code; ?>" class="<?php if ($sort == 'code') echo strtolower($order); ?>"><?php echo $column_code; ?></a></td>
										<td class="left"><a href="<?php echo $sort_example; ?>" class="<?php if ($sort == 'example') echo strtolower($order); ?>"><?php echo $column_example; ?></a></td>
										<?php if($emailtemplate['default'] == 1){ ?><td width="1">&nbsp;</td><?php } ?>
					            	</tr>
					          	</thead>
					          	<tbody>
					            <?php foreach ($shortcodes as $shortcode) { ?>
					            	<tr>
					              		<?php if($emailtemplate['default'] == 1){ ?><td style="text-align: center;"><input type="checkbox" name="shortcode_selected[]" value="<?php echo $shortcode['id']; ?>" /></td><?php } ?>
	                					<td class="left">
	                						<a href="javascript:void(0)" class="insertHander">{$<?php echo $shortcode['code']; ?>}</a>
	                					</td>
					              		<td class="left">
					              			<?php if(is_array($shortcode['example'])){ ?>
					              				<pre><?php print_r($shortcode['example']); ?></pre>
					              			<?php } else { ?>
					              				<?php echo $shortcode['example']; ?>
				              				<?php } ?>
				              			</td>
					              		<?php if($emailtemplate['default'] == 1){ ?>
					              		<td class="right">
					              			<a href="<?php echo $shortcode['url_edit']; ?>" class="action-icon-edit action-icons"><?php echo $text_edit; ?></a>
				              			</td>
				              			<?php } ?>
					            	</tr>
					            <?php } ?>
					          </tbody>
					          <tfoot>
					          	<tr>
					          		<td colspan="0" bgcolor="#F2F2F2" style="padding:10px">
					          			<a href="javascript:void(0)" onclick="$('#form').attr('action', '<?php echo $shortcodes_delete; ?>'); $('#form').submit();" class="button"><span><?php echo $button_delete; ?></span></a>
					          		</td>
					          	</tr>
					          </tfoot>
					        </table>
						</div>
						<?php } ?>

					<hr style="clear:both" />
				</div>

				<div id="language-body" class="htabs" style="width:100%;">
					<?php foreach ($languages as $language) { ?>
						<a href="#tab-language-<?php echo $language['language_id']; ?>" style="width:auto;max-width:130px;">
							<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
							<?php echo $language['name']; ?>
							<?php if($language['default'] == 1){ echo ' - ' . $text_default; } ?>
						</a>
					<?php } ?>
				<div class="clr"></div>
				</div>
				<div class="th_style"></div>

				<?php foreach ($emailtemplate_description as $langId => $description) { ?>
				<div id="tab-language-<?php echo $langId; ?>" class="tabHolder tabLangHolder" style="display:none">
					<div class="buttons" style="float: right; margin-top: -15px; margin-right: 8px;">
						<button type="button" data-frame="preview-<?php echo $langId; ?>" data-lang="<?php echo $langId; ?>" class="button preview-template"><?php echo $button_preview; ?></button>
					</div>

					<?php if($emailtemplate['key'] == 'admin.order_update' || $emailtemplate['key'] == 'admin.return_history' || $emailtemplate['key'] == 'admin.newsletter'){ ?>
					<table class="form">
						<tr>
							<td style="vertical-align: top; padding-top: 20px;">
								<label for="emailtemplate_description_comment_<?php echo $langId; ?>">
									<b><?php echo $entry_comment; ?></b>
								</label>
							</td>
							<td>
								<textarea name="emailtemplate_description_comment[<?php echo $langId; ?>]" id="emailtemplate_description_comment_<?php echo $langId; ?>"><?php echo $description['comment']; ?></textarea>
								<?php if (isset($error_emailtemplate_description_comment[$langId])) {?><span class="error"><?php echo $error_emailtemplate_description_comment[$langId]; ?></span><?php } ?>
							</td>
						</tr>
						<?php if($emailtemplate['key'] == 'admin.newsletter'){ ?>
						<tr>
							<td style="vertical-align: top; padding-top: 20px;">
								<label for="emailtemplate_description_unsubscribe_text_<?php echo $langId; ?>">
									<b><?php echo $entry_unsubscribe; ?></b>
								</label>
							</td>
							<td>
								<input type="text" class="large" name="emailtemplate_description_unsubscribe_text[<?php echo $langId; ?>]" id="emailtemplate_description_unsubscribe_text_<?php echo $langId; ?>" value="<?php echo $description['unsubscribe_text']; ?>" />
								<?php if (isset($error_emailtemplate_description_unsubscribe_text[$langId])) {?><span class="error"><?php echo $error_emailtemplate_description_unsubscribe_text[$langId]; ?></span><?php } ?>
							</td>
						</tr>
						<?php } ?>
					</table>
					<hr />
					<?php } ?>

					<table class="form">
						<tr>
							<td>
								<label for="emailtemplate_description_subject_<?php echo $langId; ?>"><b><?php echo $entry_subject; ?></b></label>
							</td>
							<td>
								<input type="text" class="large" name="emailtemplate_description_subject[<?php echo $langId; ?>]" id="emailtemplate_description_subject_<?php echo $langId; ?>" value="<?php echo $description['subject']; ?>" />
								<?php if (isset($error_emailtemplate_description_subject[$langId])) {?><span class="error"><?php echo $error_emailtemplate_description_subject[$langId]; ?></span><?php } ?>
							</td>
						</tr>
						<?php for ($i = 1; $i <= EmailTemplateDescriptionDAO::$content_count; $i++) {
							if($i != 1 && empty($description['content'.$i])) break; ?>
						<tr>
							<td style="vertical-align: top; padding-top: 20px; border-bottom: none">
								<label for="emailtemplate_description_subject_<?php echo $langId; ?>">
									<b><?php echo $entry_content; ?></b>
									<span class="help">{$emailtemplate.content<?php echo $i; ?>}</span>
								</label>
							</td>
							<td rowspan="2">
								<textarea class="content<?php echo $i; ?>" name="emailtemplate_description_content<?php echo $i; ?>[<?php echo $langId; ?>]" id="emailtemplate_description_content<?php echo $i; ?>_<?php echo $langId; ?>"><?php echo $description['content'.$i]; ?></textarea>
								<?php if (isset(${"error_emailtemplate_description_content".$i}[$langId])) {?><span class="error"><?php echo ${"error_emailtemplate_description_content".$i}[$langId]; ?></span><?php } ?>
							</td>
						</tr>
						<tr>
							<td style="vertical-align: bottom; border-top: none; border-bottom: 1px dotted #CCCCCC; padding-bottom: 20px;">
								<?php if($i < EmailTemplateDescriptionDAO::$content_count && empty($description['content'.($i+1)])){ ?><a href="javascript:void(0)" class="addContentEditorButton" data-count="<?php echo $i + 1; ?>" data-lang="<?php echo $langId; ?>"><?php echo $text_add_editor; ?> <?php echo $i + 1; ?></a><?php } ?>
							</td>
							<td>&nbsp;</td>
						</tr>
						<?php } ?>
					</table>

					<div class="preview-email" style="display: none;">
						<div class="preview content-heading">
							<span class="heading"><?php echo $heading_preview; ?></span>

							<div class="mediaIcons buttons">
								<span class="desktop selected"></span>
								<span class="tablet"></span>
								<span class="mobile"></span>
							</div>
						</div>

						<iframe id="preview-<?php echo $langId; ?>" name="preview-<?php echo $langId; ?>" style="width:100%; height:500px; border:none; margin:0 auto; float:none; display:block"></iframe>
					</div>
				</div>
				<?php } ?>

			</form>
		</div>
	</div>
</div>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
CKEDITOR.stylesSet.add('ckeditor_custom_style', [
	{ name: 'Default', element: 'p', attributes: {'class': 'standard'} },
	//{ name: 'Table', element: 'table', attributes: {'class': 'table1'} },
	{ name: 'Link', element: 'p', attributes: {'class': 'link'} },
	{ name: 'Heading 1', element: 'h1', attributes: {'class': 'heading1'} },
	{ name: 'Heading 2', element: 'h2', attributes: {'class': 'heading2'} },
	{ name: 'Heading 3', element: 'h3', attributes: {'class': 'heading3'} },
	{ name: 'Heading 4', element: 'h4', attributes: {'class': 'heading4'} },
	{ name: 'Heading 5', element: 'h5', attributes: {'class': 'heading5'} },
	{ name: 'Heading 6', element: 'h6', attributes: {'class': 'heading6'} }
]);

CKEDITOR.on('instanceReady', function (ev) {
	ev.editor.dataProcessor.htmlFilter.addRules({
        elements: {
            $: function (element) {
                // Output dimensions of images as width and height
                if (element.name == 'img') {
                    var style = element.attributes.style;

                    if (style) {
                        // Get the width from the style.
                        var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style),
                            width = match && match[1];

                        // Get the height from the style.
                        match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
                        var height = match && match[1];

                        if (width) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)width\s*:\s*(\d+)px;?/i, '');
                            element.attributes.width = width;
                        }

                        if (height) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)height\s*:\s*(\d+)px;?/i, '');
                            element.attributes.height = height;
                        }
                    }
                }

                if (!element.attributes.style) delete element.attributes.style;

                return element;
            }
        }
    });
});

function addContentEditor(key){
	if($('#'+key).length){
		CKEDITOR.config.protectedSource.push( /<\?[\s\S]*?\?>/g );

		CKEDITOR.replace(key, {
		    contentsCss : '<?php echo str_replace('&amp;', '&', $css_url); ?>',
			filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			stylesSet: 'ckeditor_custom_style',
			enterMode: CKEDITOR.ENTER_BR,
			shiftEnterMode: CKEDITOR.ENTER_BR,
			autoParagraph: false,
			toolbar: [
		        ['Source'],
				['Maximize'],
				['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
				['NumberedList','BulletedList','-','Outdent','Indent'],
				['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
				['SpecialChar'],
				'/',
				['Undo','Redo'],
				['Styles'],
				['Font','FontSize'],
				['TextColor','BGColor'],
				['Link','Unlink','Anchor'],
				['Image','Table','HorizontalRule']
			],
			bodyId : 'emailTemplate'
		});
	}
}

<?php foreach ($emailtemplate_description as $langId => $description) { ?>
	addContentEditor('emailtemplate_description_comment_<?php echo $langId; ?>');

	<?php for ($i = 1; $i <= EmailTemplateDescriptionDAO::$content_count; $i++) {
		if($i != 1 && empty($description['content'.$i])) break; ?>
		addContentEditor('emailtemplate_description_content<?php echo $i; ?>_<?php echo $langId; ?>');
<?php } } ?>
//--></script>
<script type="text/javascript"><!--
(function($){

	function addCondition(key){
		var $table = $('#emailtemplate_conditions > tbody');
		var i = $table.find('tr:last').data('count');
		if(i >= 1){
			i++;
		} else {
			i = 1;
		}
		var html = '<tr data-count="' + i + '">';
		    html += '	<td><input type="text" name="emailtemplate_condition[' + i + '][key]" value="' + key + '" /></td>';
		    html += '	<td><select name="emailtemplate_condition[' + i + '][operator]">';
		    html += '		<option value="==">(==) Equal</option>';
			html += '		<option value="!=">(!=) &nbsp;Not Equal</option>';
			html += '		<option value="&gt;">(&gt;) &nbsp;&nbsp;Greater than</option>';
			html += '		<option value="&lt;">(&lt;) &nbsp;&nbsp;Less than</option>';
			html += '		<option value="&gt;=">(&gt;=) Greater than or equal to </option>';
			html += '		<option value="&lt;=">(&lt;=) Less than or equal to </option>';
			html += '		<option value="IN">(IN) Checks if a value exists in comma-delimited string </option>';
			html += '		<option value="NOTIN">(NOTIN) Checks if a value does not exist in comma-delimited string </option>';
			html += '	</select></td>';
			html += '	<td><input type="text" name="emailtemplate_condition[' + i + '][value]" value="" placeholder="Value" /></td>';
			html += '	<td><a class="button" onclick="$(this).parents(\'tr\').eq(0).remove();"><?php echo $button_delete; ?></a></td>';
			html += '</tr>';
		$table.append(html);
		$('[data-field=conditionsShortcodes]').show();
	}

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

	$(document).ready(function(){
		$('#settings-tab > a').tabs();
		$('#language-body > a').tabs();

		$('#condition_add_select').change(function(){
			addCondition($(this).find(":selected").text());
			$(this).find('option:selected').removeAttr("selected");
		});

		$('.addContentEditorButton').click(function(){
			var lang = $(this).data('lang');
			var count = $(this).data('count');
			var html = '<tr>' +
			    		'<td style="vertical-align: top; padding-top: 20px; border-bottom: none"><label for="emailtemplate_description_subject_' + lang + '"><b><?php echo $entry_content; ?></b><span class="help">{$emailtemplate.content' + count + '}</span></label></td>' +
			    		'<td rowspan="2" style="border-bottom: none"><textarea class="content' + count + '" name="emailtemplate_description_content' + count + '[' + lang + ']" id="emailtemplate_description_content' + count + '_' + lang + '"></textarea></td>' +
					   '</tr>';

			$(this).parents('tr').after(html);

			addContentEditor('emailtemplate_description_content' + count + '_' + lang);

			$(this).remove();
		});

		//Media Icons
		var $mediaIcons = $(".mediaIcons").children("span");
		var mediaAction = function(){
			var $previewArea = $(this).parents('.preview-email').find('iframe').eq(0);
			$mediaIcons.removeClass('selected');
			$(this).addClass('selected');

			if($(this).hasClass('desktop')){
				$previewArea.css('width', '100%');
			} else if($(this).hasClass('tablet')){
				$previewArea.css('width', '768px');
			} else if($(this).hasClass('mobile')){
				$previewArea.css('width', '320px');
			}
		};
		$mediaIcons.click(mediaAction);

		$('.preview-template').click(function(){
			var $self = $(this);
			$.ajax({
				url: 'index.php?route=module/emailtemplate/preview_email&token=<?php echo $token; ?>&key=<?php echo $emailtemplate['key']; ?>&emailtemplate_id=<?php echo $emailtemplate['emailtemplate_id']; ?>',
				dataType: 'text',
				data: 'language_id=' + $self.data('lang'),
				success: function(data) {
					if(data){
						var iframe = document.getElementById($self.data('frame'));
						iframe.parentNode.style.display = "block";
						iframe = (iframe.contentWindow) ? iframe.contentWindow : (iframe.contentDocument.document) ? iframe.contentDocument.document : iframe.contentDocument;
						iframe.document.open();
						iframe.document.write(data);
						iframe.document.close();

						$mediaIcons.eq(0).click();
					}
				}
			});
			$self.remove();
		});//.filter(':visible').click();

		if($.fn.on){
			$("#template_list").on("dblclick", "tr[data-href]", row_click);
		} else {
			$("#template_list").delegate("tr[data-href]", "dblclick", row_click);
		}

		$(".insertHander").click(function(e){
    	    e.preventDefault();
    	    var editorId = $('.tabLangHolder:visible textarea.content1').eq(0).attr('id');
	    	CKEDITOR.instances[editorId].insertText($(this).html());
	    });

		// Select tab if errors are hidden
		var $hidden_error = $('.tabsHolder .error').eq(0);
		if($hidden_error.length > 0){
			// tabs editor
		    $('.tabs-nav a[href=#'+$hidden_error.parents(".vtabs-content").eq(0).attr('id')+']').click();
		}

		//Save Form [ctrl+s]
		$(window).keypress(function(event) {
			if((event.which == 115 && (event.ctrlKey||event.metaKey)) || (event.which == 19)){
			    var $button = $("#submitButton").eq(0);
			    if($button.length){
				    $button.click();
				    event.preventDefault();
			    }
			    return false;
			}
		});
	});
})(jQuery);
//--></script>

<?php echo $footer; ?>