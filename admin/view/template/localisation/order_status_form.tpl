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
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td width="33%">
							<?php foreach ($languages as $language) { ?>
								<input type="text" name="order_status[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($order_status[$language['language_id']]) ? $order_status[$language['language_id']]['name'] : ''; ?>" style="margin-bottom:5px;" />
								<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
								<?php if (isset($error_name[$language['language_id']])) { ?>
									<span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
								<?php } ?>
							<?php } ?>
						</td>
						<td width="33%">
							<table class="form">					
								<tr>
									<td> Цвет фона в админке, #<br/><span class="help">Например: 999999, FF0000</span></td>
									<td>
										<input id="pick_status_bg_color" style="float: left" type="text" name="order_status[<?php echo $this->config->get('config_language_id'); ?>][status_bg_color]" value="<?php echo isset($order_status[$this->config->get('config_language_id')]) ? $order_status[$this->config->get('config_language_id')]['status_bg_color'] : ''; ?>" />
									</td>
								</tr>
								<tr>
									<td> Цвет шрифта в админке, #<br/><span class="help">Например: 999999, FF0000</span></td>
									<td>
										<input id="pick_status_txt_color" style="float: left" type="text" name="order_status[<?php echo $this->config->get('config_language_id'); ?>][status_txt_color]" value="<?php echo isset($order_status[$this->config->get('config_language_id')]) ? $order_status[$this->config->get('config_language_id')]['status_txt_color'] : ''; ?>" />			
									</td>
								</tr>
								<tr>
									<td> Цвет подсветки блока в ЛК покупателя #<br/><span class="help">Например: 999999, FF0000</span></td>
									<td>
										<input id="pick_front_bg_color" style="float: left" type="text" name="order_status[<?php echo $this->config->get('config_language_id'); ?>][front_bg_color]" value="<?php echo isset($order_status[$this->config->get('config_language_id')]) ? $order_status[$this->config->get('config_language_id')]['front_bg_color'] : ''; ?>" />
									</td>
								</tr>
								<tr>
									<td> Иконка FontAwesome <br/><span class="help">fa-address-book</span></td>
									<td>
										<input style="float: left" type="text" name="order_status[<?php echo $this->config->get('config_language_id'); ?>][status_fa_icon]" value="<?php echo isset($order_status[$this->config->get('config_language_id')]) ? $order_status[$this->config->get('config_language_id')]['status_fa_icon'] : ''; ?>" />
										<? if ($order_status[$this->config->get('config_language_id')]['status_fa_icon']) { ?><i class="fa <? echo $order_status[$this->config->get('config_language_id')]['status_fa_icon']; ?>" aria-hidden="true"></i><? } ?>
									</td>
								</tr>
							</table>
						</td>
						<td width="33%">
							<table class="form">	
								<tr>
									<td><div class="scrollbox" style="height:300px;">
										<?php $class = 'odd'; ?>
										<?php foreach ($order_statuses as $_os) { ?>
											<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
											<div class="<?php echo $class; ?>">
												<?php if (in_array($_os['order_status_id'], $linked_order_status_ids)) { ?>
													<input id="linked_order_<?php echo $_os['order_status_id']; ?>" class="checkbox" type="checkbox" name="linked_order_status_ids[]" value="<?php echo $_os['order_status_id']; ?>" checked="checked" />
													<label for="linked_order_<?php echo $_os['order_status_id']; ?>"><?php echo $_os['name']; ?> </label>
													<?php } else { ?>
													<input id="linked_order_<?php echo $_os['order_status_id']; ?>" class="checkbox" type="checkbox" name="linked_order_status_ids[]" value="<?php echo $_os['order_status_id']; ?>" />
													<label for="linked_order_<?php echo $_os['order_status_id']; ?>"><?php echo $_os['name']; ?></label>
												<?php } ?>
											</div>
										<?php } ?>
									</div>
									<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
									</td>
								</tr>	
								
							</table>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function()
	{
		$.fn.jPicker.defaults.images.clientPath='view/image/';
		var LiveCallbackElement = $('#Live'),
		LiveCallbackButton = $('#LiveButton');
		$('#pick_status_txt_color').jPicker({window:{title:'Binded Example'},color:{active:new $.jPicker.Color({ahex:'993300ff'})}});
		$('#pick_status_bg_color').jPicker({window:{title:'Binded Example'},color:{active:new $.jPicker.Color({ahex:'993300ff'})}});
		$('#pick_front_bg_color').jPicker({window:{title:'Binded Example'},color:{active:new $.jPicker.Color({ahex:'993300ff'})}});
	});
</script>
<?php echo $footer; ?>	