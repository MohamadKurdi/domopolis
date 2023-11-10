		<div id="tab-viber" <?php if (!$config_smsgate_library_enable_viber) { ?>style="display:none;"<?php } ?>>
			<table class="list">
				<tr>
					<td style="width:200px;">
						<span class="status_color" style="text-align: left; background: #43B02A; color:#fff">
							Уведомление о новом заказе
						</span>
					</td>
					<td style="width:50px" class="center">
						<input class="checkbox" type="checkbox" name="config_viber_send_new_order" id="config_viber_send_new_order" <?php if ($config_viber_send_new_order) { echo ' checked="checked"'; }?> />
						<label for="config_viber_send_new_order"></label>
					</td>
					<td style="width:300px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст
							</span>
						</p>

						<textarea name="config_viber_new_order_message" cols="50" rows="7"><?php echo $config_viber_new_order_message; ?></textarea>
					</td>
					<td style="width:250px;">
						<div class="image">
							<img src="<?php echo $viber_new_order_image; ?>" alt="" id="thumb-viber_new_order_image" height="150px" />
							<input type="text" style="font-size:10px; width:150px;" class="image-ajax" name="config_viber_new_order_image" value="<?php echo $config_viber_new_order_image; ?>" id="viber_new_order_image" />
							<br />
							<a onclick="image_upload('viber_new_order_image', 'thumb-viber_new_order_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-viber_new_order_image').attr('src', '<?php echo $no_image; ?>'); $('#viber_new_order_image').attr('value', ''); $('#viber_new_order_image').trigger('change');"><?php echo $text_clear; ?></a>
						</div>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст кнопки
							</span>
						</p>

						<textarea name="config_viber_new_order_button_text" cols="50" rows="7"><?php echo $config_viber_new_order_button_text; ?></textarea>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								URL кнопки
							</span>
						</p>

						<textarea name="config_viber_new_order_button_url" cols="50" rows="7"><?php echo $config_viber_new_order_button_url; ?></textarea>
					</td>
				</tr>

				<?php foreach ($order_statuses as $order_status) { ?>
					<?php 
					$viber_status_message = [];
					if (isset($config_viber_order_status_message[$order_status['order_status_id']])) {
						$viber_status_message = $config_viber_order_status_message[$order_status['order_status_id']];
					} 
					?>
					<tr>
						<td style="width:200px;">
							<span class="status_color" style="text-align: left; background: #<?php echo !empty($order_status['status_bg_color']) ? $order_status['status_bg_color'] : ''; ?>; color: #<?php echo !empty($order_status['status_txt_color']) ? $order_status['status_txt_color'] : ''; ?>;">
								<?php echo $order_status['name']; ?>
							</span>
						</td>

						<td style="width:50px" class="center">
							<input data-key="config_viber_order_status_message" data-id="<?php echo $order_status['order_status_id']; ?>" data-name="enabled" class="checkbox" type="checkbox" name="config_viber_order_status_message[<?php echo $order_status['order_status_id']; ?>][enabled]" id="config_viber_order_status_message[<?php echo $order_status['order_status_id']; ?>][enabled]" <?php if (isset($viber_status_message['enabled']) && $viber_status_message['enabled']) { echo ' checked="checked"'; }?>/>
							<label for="config_viber_order_status_message[<?php echo $order_status['order_status_id']; ?>][enabled]"></label>
						</td>
						<td style="width:300px;">
							<textarea data-key="config_viber_order_status_message" data-id="<?php echo $order_status['order_status_id']; ?>" data-name="message" name="config_viber_order_status_message[<?php echo $order_status['order_status_id']; ?>][message]" cols="50" rows="7"><?php echo isset($viber_status_message['message']) ? $viber_status_message['message'] : ""; ?></textarea>
						</td>

						<td>

							<div class="image">
								<img src="<?php echo $viber_order_status_message_image[$order_status['order_status_id']]; ?>" alt="" id="thumb-viber_order_status_message_image_<?php echo $order_status['order_status_id']; ?>" height="150px" />

								<input data-key="config_viber_order_status_message" data-id="<?php echo $order_status['order_status_id']; ?>" data-name="image" type="text" style="font-size:10px; width:150;" class="image-ajax" name="config_viber_order_status_message[<?php echo $order_status['order_status_id']; ?>][image]" value="<?php echo isset($viber_status_message['image']) ? $viber_status_message['image'] : ""; ?>" id="viber_order_status_message_image_<?php echo $order_status['order_status_id']; ?>" />
								<br />

								<a onclick="image_upload('viber_order_status_message_image_<?php echo $order_status['order_status_id']; ?>', 'thumb-viber_order_status_message_image_<?php echo $order_status['order_status_id']; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
								<a onclick="$('#thumb-viber_order_status_message_image_<?php echo $order_status['order_status_id']; ?>').attr('src', '<?php echo $no_image; ?>'); $('#viber_order_status_message_image_<?php echo $order_status['order_status_id']; ?>').attr('value', ''); $('#viber_order_status_message_image_<?php echo $order_status['order_status_id']; ?>').trigger('change');"><?php echo $text_clear; ?></a>
							</div>

						</td>

						<td style="width:200px;">
							<p>
								<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
									Текст кнопки
								</span>
							</p>

							<textarea data-key="config_viber_order_status_message" data-id="<?php echo $order_status['order_status_id']; ?>" data-name="button_text" name="config_viber_order_status_message[<?php echo $order_status['order_status_id']; ?>][button_text]" cols="50" rows="7"><?php echo isset($viber_status_message['button_text']) ? $viber_status_message['button_text'] : ""; ?></textarea>
						</td>
						<td style="width:200px;">
							<p>
								<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
									URL кнопки
								</span>
							</p>

							<textarea data-key="config_viber_order_status_message" data-id="<?php echo $order_status['order_status_id']; ?>" data-name="button_url" name="config_viber_order_status_message[<?php echo $order_status['order_status_id']; ?>][button_url]" cols="50" rows="7"><?php echo isset($viber_status_message['button_url']) ? $viber_status_message['button_url'] : ""; ?></textarea>
						</td>
					</tr>										
				<?php } ?>


				<tr>
					<td style="width:200px;">
						<span class="status_color" style="text-align: left; background: #43B02A; color:#fff">
							Трекинг отправки со склада 
						</span>
					</td>
					<td style="width:50px" class="center">
						<input class="checkbox" type="checkbox" name="config_viber_tracker_leave_main_warehouse_enabled" id="config_viber_tracker_leave_main_warehouse_enabled" <?php if ($config_viber_tracker_leave_main_warehouse_enabled) { echo ' checked="checked"'; }?> />
						<label for="config_viber_tracker_leave_main_warehouse_enabled"></label>
					</td>
					<td style="width:300px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст
							</span>
						</p>

						<textarea name="config_viber_tracker_leave_main_warehouse" cols="50" rows="7"><?php echo $config_viber_tracker_leave_main_warehouse; ?></textarea>
					</td>
					<td style="width:250px;">
						<div class="image">
							<img src="<?php echo $viber_tracker_leave_main_warehouse_image; ?>" alt="" id="thumb-viber_tracker_leave_main_warehouse_image" height="150px" />
							<input type="text" style="font-size:10px; width:150;" class="image-ajax" name="config_viber_tracker_leave_main_warehouse_image" value="<?php echo $config_viber_tracker_leave_main_warehouse_image; ?>" id="viber_tracker_leave_main_warehouse_image" />
							<br />
							<a onclick="image_upload('viber_tracker_leave_main_warehouse_image', 'thumb-viber_tracker_leave_main_warehouse_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-viber_tracker_leave_main_warehouse_image').attr('src', '<?php echo $no_image; ?>'); $('#viber_tracker_leave_main_warehouse_image').attr('value', ''); $('#viber_tracker_leave_main_warehouse_image').trigger('change');"><?php echo $text_clear; ?></a>
						</div>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст кнопки
							</span>
						</p>

						<textarea name="config_viber_tracker_leave_main_warehouse_button_text" cols="50" rows="7"><?php echo $config_viber_tracker_leave_main_warehouse_button_text; ?></textarea>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								URL кнопки
							</span>
						</p>

						<textarea name="config_viber_tracker_leave_main_warehouse_button_url" cols="50" rows="7"><?php echo $config_viber_tracker_leave_main_warehouse_button_url; ?></textarea>
					</td>
				</tr>

				<tr>
					<td style="width:200px;">
						<span class="status_color" style="text-align: left; background: #000; color:#fff">
							Успешная оплата
						</span>
					</td>
					<td style="width:50px" class="center">
						<input class="checkbox" type="checkbox" name="config_viber_payment_recieved_enabled" id="config_viber_payment_recieved_enabled" <?php if ($config_viber_payment_recieved_enabled) { echo ' checked="checked"'; }?> />
						<label for="config_viber_payment_recieved_enabled"></label>
					</td>
					<td style="width:300px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст
							</span>
						</p>

						<textarea name="config_viber_payment_recieved" cols="50" rows="7"><?php echo $config_viber_payment_recieved; ?></textarea>
					</td>
					<td style="width:250px;">
						<div class="image">
							<img src="<?php echo $viber_payment_recieved_image; ?>" alt="" id="thumb-viber_payment_recieved_image" height="150px" />
							<input type="text" style="font-size:10px; width:150px;" class="image-ajax" name="config_viber_payment_recieved_image" value="<?php echo $config_viber_payment_recieved_image; ?>" id="viber_payment_recieved_image" />
							<br />
							<a onclick="image_upload('viber_payment_recieved_image', 'thumb-viber_payment_recieved_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-viber_payment_recieved_image').attr('src', '<?php echo $no_image; ?>'); $('#viber_payment_recieved_image').attr('value', ''); $('#viber_payment_recieved_image').trigger('change');"><?php echo $text_clear; ?></a>
						</div>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст кнопки
							</span>
						</p>

						<textarea name="config_viber_payment_recieved_button_text" cols="50" rows="7"><?php echo $config_viber_payment_recieved_button_text; ?></textarea>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								URL кнопки
							</span>
						</p>

						<textarea name="config_viber_payment_recieved_button_url" cols="50" rows="7"><?php echo $config_viber_payment_recieved_button_url; ?></textarea>
					</td>
				</tr>

				<tr>
					<td style="width:200px;">
						<span class="status_color" style="text-align: left; background: #ef5e67; color:#fff">
							ТТН службы доставки: отправка 
						</span>
					</td>
					<td style="width:50px" class="center">
						<input class="checkbox" type="checkbox" name="config_viber_ttn_sent_enabled" id="config_viber_ttn_sent_enabled" <?php if ($config_viber_ttn_sent_enabled) { echo ' checked="checked"'; }?> />
						<label for="config_viber_ttn_sent_enabled"></label>
					</td>
					<td style="width:300px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст
							</span>
						</p>

						<textarea name="config_viber_ttn_sent" cols="50" rows="7"><?php echo $config_viber_ttn_sent; ?></textarea>
					</td>
					<td style="width:250px;">
						<div class="image">
							<img src="<?php echo $viber_ttn_sent_image; ?>" alt="" id="thumb-viber_ttn_sent_image" height="150px" />
							<input type="text" style="font-size:10px; width:150px;" class="image-ajax" name="config_viber_ttn_sent_image" value="<?php echo $config_viber_ttn_sent_image; ?>" id="viber_ttn_sent_image" />
							<br />
							<a onclick="image_upload('viber_ttn_sent_image', 'thumb-viber_ttn_sent_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-viber_ttn_sent_image').attr('src', '<?php echo $no_image; ?>'); $('#viber_ttn_sent_image').attr('value', ''); $('#viber_ttn_sent_image').trigger('change');"><?php echo $text_clear; ?></a>
						</div>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст кнопки
							</span>
						</p>

						<textarea name="config_viber_ttn_sent_button_text" cols="50" rows="7"><?php echo $config_viber_ttn_sent_button_text; ?></textarea>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								URL кнопки
							</span>
						</p>

						<textarea name="config_viber_ttn_sent_button_url" cols="50" rows="7"><?php echo $config_viber_ttn_sent_button_url; ?></textarea>
					</td>
				</tr>

				<tr>
					<td style="width:200px;">
						<span class="status_color" style="text-align: left; background: #43B02A; color:#fff">
							ТТН службы доставки: доставлено
						</span>
					</td>
					<td style="width:50px" class="center">
						<input class="checkbox" type="checkbox" name="config_viber_ttn_ready_enabled" id="config_viber_ttn_ready_enabled" <?php if ($config_viber_ttn_ready_enabled) { echo ' checked="checked"'; }?> />
						<label for="config_viber_ttn_ready_enabled"></label>
					</td>
					<td style="width:300px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст
							</span>
						</p>

						<textarea name="config_viber_ttn_ready" cols="50" rows="7"><?php echo $config_viber_ttn_ready; ?></textarea>
					</td>
					<td style="width:250px;">
						<div class="image">
							<img src="<?php echo $viber_ttn_ready_image; ?>" alt="" id="thumb-viber_ttn_ready_image" height="150px" />
							<input type="text" style="font-size:10px; width:150px;" class="image-ajax" name="config_viber_ttn_ready_image" value="<?php echo $config_viber_ttn_ready_image; ?>" id="viber_ttn_ready_image" />
							<br />
							<a onclick="image_upload('viber_ttn_ready_image', 'thumb-viber_ttn_ready_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-viber_ttn_ready_image').attr('src', '<?php echo $no_image; ?>'); $('#viber_ttn_ready_image').attr('value', ''); $('#viber_ttn_ready_image').trigger('change');"><?php echo $text_clear; ?></a>
						</div>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст кнопки
							</span>
						</p>

						<textarea name="config_viber_ttn_ready_button_text" cols="50" rows="7"><?php echo $config_viber_ttn_ready_button_text; ?></textarea>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								URL кнопки
							</span>
						</p>

						<textarea name="config_viber_ttn_ready_button_url" cols="50" rows="7"><?php echo $config_viber_ttn_ready_button_url; ?></textarea>
					</td>
				</tr>

				<tr>
					<td style="width:200px;">
						<span class="status_color" style="text-align: left; background: #ef5e67; color:#fff">
							Уведомление о сгорании бонусов  
						</span>
					</td>
					<td style="width:50px" class="center">
						<input class="checkbox" type="checkbox" name="config_viber_rewardpoints_reminder_enabled" id="config_viber_rewardpoints_reminder_enabled" <?php if ($config_viber_rewardpoints_reminder_enabled) { echo ' checked="checked"'; }?> />
						<label for="config_viber_rewardpoints_reminder_enabled"></label>
					</td>
					<td style="width:300px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст
							</span>
						</p>

						<textarea name="config_viber_rewardpoints_reminder" cols="50" rows="7"><?php echo $config_viber_rewardpoints_reminder; ?></textarea>
					</td>
					<td style="width:250px;">
						<div class="image">
							<img src="<?php echo $viber_rewardpoints_reminder_image; ?>" alt="" id="thumb-viber_rewardpoints_reminder_image" height="150px" />
							<input type="text" style="font-size:10px; width:150px;" class="image-ajax" name="config_viber_rewardpoints_reminder_image" value="<?php echo $config_viber_rewardpoints_reminder_image; ?>" id="viber_rewardpoints_reminder_image" />
							<br />
							<a onclick="image_upload('viber_rewardpoints_reminder_image', 'thumb-viber_rewardpoints_reminder_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-viber_rewardpoints_reminder_image').attr('src', '<?php echo $no_image; ?>'); $('#viber_rewardpoints_reminder_image').attr('value', ''); $('#viber_rewardpoints_reminder_image').trigger('change');"><?php echo $text_clear; ?></a>
						</div>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст кнопки
							</span>
						</p>

						<textarea name="config_viber_rewardpoints_reminder_button_text" cols="50" rows="7"><?php echo $config_viber_rewardpoints_reminder_button_text; ?></textarea>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								URL кнопки
							</span>
						</p>

						<textarea name="config_viber_rewardpoints_reminder_button_url" cols="50" rows="7"><?php echo $config_viber_rewardpoints_reminder_button_url; ?></textarea>
					</td>
				</tr>

				<tr>
					<td style="width:200px;">
						<span class="status_color" style="text-align: left; background: #ff7f00; color:#fff">
							SMS о ручной транзакции. Успешная оплата.
						</span>
					</td>
					<td style="width:50px" class="center">                                      
					</td>
					<td style="width:300px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст
							</span>
						</p>

						<textarea name="config_viber_transaction_text_type_1" cols="50" rows="7"><?php echo $config_viber_transaction_text_type_1; ?></textarea>
					</td>
					<td style="width:250px;">
						<div class="image">
							<img src="<?php echo $viber_transaction_text_type_1_image; ?>" alt="" id="thumb-viber_transaction_text_type_1_image" height="150px" />
							<input type="text" style="font-size:10px; width:150px;" class="image-ajax" name="config_viber_transaction_text_type_1_image" value="<?php echo $config_viber_transaction_text_type_1_image; ?>" id="viber_transaction_text_type_1_image" />
							<br />
							<a onclick="image_upload('viber_transaction_text_type_1_image', 'thumb-viber_transaction_text_type_1_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-viber_transaction_text_type_1_image').attr('src', '<?php echo $no_image; ?>'); $('#viber_transaction_text_type_1_image').attr('value', ''); $('#viber_transaction_text_type_1_image').trigger('change');"><?php echo $text_clear; ?></a>
						</div>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст кнопки
							</span>
						</p>

						<textarea name="config_viber_transaction_text_type_1_button_text" cols="50" rows="7"><?php echo $config_viber_transaction_text_type_1_button_text; ?></textarea>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								URL кнопки
							</span>
						</p>

						<textarea name="config_viber_transaction_text_type_1_button_url" cols="50" rows="7"><?php echo $config_viber_transaction_text_type_1_button_url; ?></textarea>
					</td>
				</tr>

				<tr>
					<td style="width:200px;">
						<span class="status_color" style="text-align: left; background: #ff7f00; color:#fff">
							SMS о ручной транзакции. Возврат безналичный
						</span>
					</td>
					<td style="width:50px" class="center">                                      
					</td>
					<td style="width:300px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст
							</span>
						</p>

						<textarea name="config_viber_transaction_text_type_2" cols="50" rows="7"><?php echo $config_viber_transaction_text_type_2; ?></textarea>
					</td>
					<td style="width:250px;">
						<div class="image">
							<img src="<?php echo $viber_transaction_text_type_2_image; ?>" alt="" id="thumb-viber_transaction_text_type_2_image" height="150px" />
							<input type="text" style="font-size:10px; width:150px;" class="image-ajax" name="config_viber_transaction_text_type_2_image" value="<?php echo $config_viber_transaction_text_type_2_image; ?>" id="viber_transaction_text_type_2_image" />
							<br />
							<a onclick="image_upload('viber_transaction_text_type_2_image', 'thumb-viber_transaction_text_type_2_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-viber_transaction_text_type_2_image').attr('src', '<?php echo $no_image; ?>'); $('#viber_transaction_text_type_2_image').attr('value', ''); $('#viber_transaction_text_type_2_image').trigger('change');"><?php echo $text_clear; ?></a>
						</div>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст кнопки
							</span>
						</p>

						<textarea name="config_viber_transaction_text_type_2_button_text" cols="50" rows="7"><?php echo $config_viber_transaction_text_type_2_button_text; ?></textarea>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								URL кнопки
							</span>
						</p>

						<textarea name="config_viber_transaction_text_type_2_button_url" cols="50" rows="7"><?php echo $config_viber_transaction_text_type_2_button_url; ?></textarea>
					</td>
				</tr>

				<tr>
					<td style="width:200px;">
						<span class="status_color" style="text-align: left; background: #ff7f00; color:#fff">
							SMS о ручной транзакции. Возврат остальное 
						</span>
					</td>
					<td style="width:50px" class="center">                                      
					</td>
					<td style="width:300px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст
							</span>
						</p>

						<textarea name="config_viber_transaction_text_type_3" cols="50" rows="7"><?php echo $config_viber_transaction_text_type_3; ?></textarea>
					</td>
					<td style="width:250px;">
						<div class="image">
							<img src="<?php echo $viber_transaction_text_type_3_image; ?>" alt="" id="thumb-viber_transaction_text_type_3_image" height="150px" />
							<input type="text" style="font-size:10px; width:150px;" class="image-ajax" name="config_viber_transaction_text_type_3_image" value="<?php echo $config_viber_transaction_text_type_3_image; ?>" id="viber_transaction_text_type_3_image" />
							<br />
							<a onclick="image_upload('viber_transaction_text_type_3_image', 'thumb-viber_transaction_text_type_3_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-viber_transaction_text_type_3_image').attr('src', '<?php echo $no_image; ?>'); $('#viber_transaction_text_type_3_image').attr('value', ''); $('#viber_transaction_text_type_3_image').trigger('change');"><?php echo $text_clear; ?></a>
						</div>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст кнопки
							</span>
						</p>

						<textarea name="config_viber_transaction_text_type_3_button_text" cols="50" rows="7"><?php echo $config_viber_transaction_text_type_3_button_text; ?></textarea>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								URL кнопки
							</span>
						</p>

						<textarea name="config_viber_transaction_text_type_3_button_url" cols="50" rows="7"><?php echo $config_viber_transaction_text_type_3_button_url; ?></textarea>
					</td>
				</tr>

				<tr>
					<td style="width:200px;">
						<span class="status_color" style="text-align: left; background: #43B02A; color:#fff">
							Отправка ссылки на оплату
						</span>
					</td>
					<td style="width:50px" class="center">
						<input class="checkbox" type="checkbox" name="config_viber_payment_link_enabled" id="config_viber_payment_link_enabled" <?php if ($config_viber_payment_link_enabled) { echo ' checked="checked"'; }?> />
						<label for="config_viber_payment_link_enabled"></label>
					</td>
					<td style="width:300px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст
							</span>
						</p>

						<textarea name="config_viber_payment_link" cols="50" rows="7"><?php echo $config_viber_payment_link; ?></textarea>
					</td>
					<td style="width:250px;">
						<div class="image">
							<img src="<?php echo $viber_payment_link_image; ?>" alt="" id="thumb-viber_payment_link_image" height="150px" />
							<input type="text" style="font-size:10px; width:150px;" class="image-ajax" name="config_viber_payment_link_image" value="<?php echo $config_viber_payment_link_image; ?>" id="viber_payment_link_image" />
							<br />
							<a onclick="image_upload('viber_payment_link_image', 'thumb-viber_payment_link_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-viber_payment_link_image').attr('src', '<?php echo $no_image; ?>'); $('#viber_payment_link_image').attr('value', ''); $('#viber_payment_link_image').trigger('change');"><?php echo $text_clear; ?></a>
						</div>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст кнопки
							</span>
						</p>

						<textarea name="config_viber_payment_link_button_text" cols="50" rows="7"><?php echo $config_viber_payment_link_button_text; ?></textarea>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								URL кнопки
							</span>
						</p>

						<textarea name="config_viber_payment_link_button_url" cols="50" rows="7"><?php echo $config_viber_payment_link_button_url; ?></textarea>
					</td>
				</tr>

				<tr>
					<td style="width:200px;">
						<span class="status_color" style="text-align: left; background: #43B02A; color:#fff">
							После первого успешного заказа
						</span>
					</td>
					<td style="width:50px" class="center">
						<input class="checkbox" type="checkbox" name="config_viber_firstorder_enabled" id="config_viber_firstorder_enabled" <?php if ($config_viber_firstorder_enabled) { echo ' checked="checked"'; }?> />
						<label for="config_viber_firstorder_enabled"></label>
					</td>
					<td style="width:300px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст
							</span>
						</p>

						<textarea name="config_viber_firstorder" cols="50" rows="7"><?php echo $config_viber_firstorder; ?></textarea>
					</td>
					<td style="width:250px;">
						<div class="image">
							<img src="<?php echo $viber_firstorder_image; ?>" alt="" id="thumb-viber_firstorder_image" height="150px" />
							<input type="text" style="font-size:10px; width:150px;" class="image-ajax" name="config_viber_firstorder_image" value="<?php echo $config_viber_firstorder_image; ?>" id="viber_firstorder_image" />
							<br />
							<a onclick="image_upload('viber_firstorder_image', 'thumb-viber_firstorder_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-viber_firstorder_image').attr('src', '<?php echo $no_image; ?>'); $('#viber_firstorder_image').attr('value', ''); $('#viber_firstorder_image').trigger('change');"><?php echo $text_clear; ?></a>
						</div>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст кнопки
							</span>
						</p>

						<textarea name="config_viber_firstorder_button_text" cols="50" rows="7"><?php echo $config_viber_firstorder_button_text; ?></textarea>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								URL кнопки
							</span>
						</p>

						<textarea name="config_viber_firstorder_button_url" cols="50" rows="7"><?php echo $config_viber_firstorder_button_url; ?></textarea>
					</td>
				</tr>
				<tr>
					<td style="width:200px;">
						<span class="status_color" style="text-align: left; background: #ef5e67; color:#fff">
							Поздравление с днем рождения
						</span>
					</td>
					<td style="width:50px" class="center">
						<input class="checkbox" type="checkbox" name="config_viber_birthday_greeting_enabled" id="config_viber_birthday_greeting_enabled" <?php if ($config_viber_birthday_greeting_enabled) { echo ' checked="checked"'; }?> />
						<label for="config_viber_birthday_greeting_enabled"></label>
					</td>
					<td style="width:300px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст
							</span>
						</p>

						<textarea name="config_viber_birthday_greeting" cols="50" rows="7"><?php echo $config_viber_birthday_greeting; ?></textarea>
					</td>
					<td style="width:250px;">
						<div class="image">
							<img src="<?php echo $viber_birthday_greeting_image; ?>" alt="" id="thumb-viber_birthday_greeting_image" height="150px" />
							<input type="text" style="font-size:10px; width:150px;" class="image-ajax" name="config_viber_birthday_greeting_image" value="<?php echo $config_viber_birthday_greeting_image; ?>" id="viber_birthday_greeting_image" />
							<br />
							<a onclick="image_upload('viber_birthday_greeting_image', 'thumb-viber_birthday_greeting_image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-viber_birthday_greeting_image').attr('src', '<?php echo $no_image; ?>'); $('#viber_birthday_greeting_image').attr('value', ''); $('#viber_birthday_greeting_image').trigger('change');"><?php echo $text_clear; ?></a>
						</div>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								Текст кнопки
							</span>
						</p>

						<textarea name="config_viber_birthday_greeting_button_text" cols="50" rows="7"><?php echo $config_viber_birthday_greeting_button_text; ?></textarea>
					</td>
					<td style="width:200px;">
						<p>
							<span class="status_color" style="display:inline-block; padding:3px 5px; text-align: left; background: #43B02A; color:#fff">
								URL кнопки
							</span>
						</p>

						<textarea name="config_viber_birthday_greeting_button_url" cols="50" rows="7"><?php echo $config_viber_birthday_greeting_button_url; ?></textarea>
					</td>
				</tr>
			</table>
		</div>