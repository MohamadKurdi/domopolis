<? $can_delete = (in_array($this->user->getUserGroup(), array(1, 23)) || $this->user->getIsMM()); ?>

<?php if ($error) { ?>
	<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
<?php } ?>
<table class="list">
	<thead>
		<tr>
			<td class="left" width="1"><b>Дата</b></td>
			<td class="left"><b><?php echo $column_comment; ?></b></td>
			<td class="left"><b>Менеджер</b></td>
			<td class="left" width="1"><b><?php echo $column_status; ?></b></td>
			<td class="center" width="1"><i class="fa fa-bell" aria-hidden="true"></i></td>
			<td class="center" width="1"><i class="fa fa-truck" aria-hidden="true"></i></td>	
			<? if ($can_delete) { ?>
				<td class="left" width="1"><b></b></td>	  
			<? } ?>
		</tr>
	</thead>
	<tbody>
		<?php if ($histories) { ?>
			<?php foreach ($histories as $history) { ?>
				<tr id="history">
					<td class="left"><?php echo $history['date_added']; ?></td>
					<td class="left"><textarea style="width:90%;max-height:40px;" rows="3" class="onfocusout_edit_history" data-field-name="comment" data-order-history-id="<?php echo $history['order_history_id']; ?>"><?php echo $history['comment']; ?></textarea><span></span></td>
					<td class="left" style="width:1px; white-space:nowrap;"><?php echo $history['user']; ?></td>
					<td class="left" style="width:auto; white-space:nowrap;">
						
						<span class="status_color" style="font-size:14px; display:block; padding:5px 4px; background: #<?php echo $history['status_bg_color']; ?>; color:#<?php echo $history['status_txt_color']; ?>">
							<? if (!empty($history['status_fa_icon'])) { ?>
								<i class="fa <? echo $history['status_fa_icon']; ?>" aria-hidden="true"></i>
							<? } ?>
							<?php echo $history['status']; ?>
						</span>	
						
						<?php if (!empty($history['yam_status'])) { ?>
							<span class="status_color" style="font-size:14px; margin-top:5px; display:block; padding:5px 4px; background: #FF0000; color:#FFFFFF">
								<i class="fa fa-yoast" aria-hidden="true"></i> <?php echo $history['yam_status']; ?>
							</span>
						<?php } ?>
						
						<?php if (!empty($history['yam_substatus'])) { ?>
							<span class="status_color" style="font-size:14px;  margin-top:5px; display:block; padding:5px 4px; background: #ff7815; color:#FFFFFF">
								<i class="fa fa-yoast" aria-hidden="true"></i> <?php echo $history['yam_substatus']; ?>
							</span>
						<?php } ?>
						
					</td>
					<td class="center" style="padding:4px 8px;"><i class="fa fa-bell" aria-hidden="true"  style="font-size:18px;<?php if($history['notify']) { ?>color:#4ea24e<? } ?>"></i></td>
					<td class="center" style="padding:4px 8px;"><i class="fa fa-truck change_courier_in_history" data-field-name="courier" data-value="<? echo (int)$history['courier']; ?>" aria-hidden="true" data-order-history-id="<?php echo $history['order_history_id']; ?>"  style="cursor:pointer;font-size:18px;<?php if($history['courier']) { ?>color:#4ea24e<? } ?>"></i></td>
					<? if ($can_delete) { ?>
						<td class="left"><a id="delete-history" class="button save_button" data-number="<?php echo $history['order_history_id']; ?>"><?php echo $button_remove; ?></a></td>
					<? } ?>	  				
				</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="center" colspan="5"><?php echo $text_no_results; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<div class="pagination"><?php echo $pagination; ?></div>