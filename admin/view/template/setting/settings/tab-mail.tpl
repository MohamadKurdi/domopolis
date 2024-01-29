		<div id="tab-mail">
			<h2>Сервисные почтовые аккаунты и домены</h2>

			<table class="form">
				<tr>
					<td width="20%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Payment Mail FROM</span></p>
						<input type="text" name="config_payment_mail_from" value="<?php echo $config_payment_mail_from; ?>" size="30" style="width:250px;" />										
						<span class="help">отправлять уведомления оплаты с этой почты</span>
					</td>

					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Payment Mail TO</span></p>
						<input type="text" name="config_payment_mail_to" value="<?php echo $config_payment_mail_to; ?>" size="30" style="width:250px;" />
						<span class="help">отправлять уведомления оплаты на эту почту</span>									
					</td>

					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Courier Mail TO</span></p>
						<input type="text" name="config_courier_mail_to" value="<?php echo $config_courier_mail_to; ?>" size="30" style="width:250px;" />
						<span class="help">отправлять уведомления курьеру на эту почту</span>									
					</td>

					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Центральный домен редиректов</span></p>
						<input type="text" name="config_main_redirect_domain" value="<?php echo $config_main_redirect_domain; ?>" size="30" style="width:250px;" />	
						<span class="help">с геолокацией, блекджеком и шлюхами</span>	
					</td>

					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">WP-блог, если есть</span></p>
						<input type="text" name="config_main_wp_blog_domain" value="<?php echo $config_main_wp_blog_domain; ?>" size="30" style="width:250px;" />
						<span class="help">солидный современный ресурс</span>		
					</td>
				</tr>
			</table>

			<h2>Отправка почты</h2><sup style="color:red">Устаревшие настройки, оставлены для обратной совместимости</sup>
			<table class="form">
				<tr>		
					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Протокол для транзакций</span></p>
						<select name="config_mail_protocol">
							<?php if ($config_mail_protocol == 'mail') { ?>
								<option value="mail" selected="selected"><?php echo $text_mail; ?></option>
							<?php } else { ?>
								<option value="mail"><?php echo $text_mail; ?></option>
							<?php } ?>
							<?php if ($config_mail_protocol == 'smtp') { ?>
								<option value="smtp" selected="selected"><?php echo $text_smtp; ?></option>
							<?php } else { ?>
								<option value="smtp"><?php echo $text_smtp; ?></option>
							<?php } ?>
							<?php if ($config_mail_protocol == 'sparkpost') { ?>
								<option value="sparkpost" selected="selected">СпаркПост веб апи</option>
							<?php } else { ?>
								<option value="sparkpost">СпаркПост веб апи</option>
							<?php } ?>
							<?php if ($config_mail_protocol == 'mailgun') { ?>
								<option value="mailgun" selected="selected">MailGun веб апи</option>
							<?php } else { ?>
								<option value="mailgun">MailGun веб апи</option>
							<?php } ?>
						</select>
					</td>

					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Протокол для триггеров</span></p>
						<select name="config_mail_trigger_protocol">
							<?php if ($config_mail_trigger_protocol == 'mail') { ?>
								<option value="mail" selected="selected"><?php echo $text_mail; ?></option>
							<?php } else { ?>
								<option value="mail"><?php echo $text_mail; ?></option>
							<?php } ?>
							<?php if ($config_mail_trigger_protocol == 'smtp') { ?>
								<option value="smtp" selected="selected"><?php echo $text_smtp; ?></option>
							<?php } else { ?>
								<option value="smtp"><?php echo $text_smtp; ?></option>
							<?php } ?>
							<?php if ($config_mail_trigger_protocol == 'sparkpost') { ?>
								<option value="sparkpost" selected="selected">СпаркПост веб апи</option>
							<?php } else { ?>
								<option value="sparkpost">СпаркПост веб апи</option>
							<?php } ?>
							<?php if ($config_mail_trigger_protocol == 'mailgun') { ?>
								<option value="mailgun" selected="selected">MailGun веб апи</option>
							<?php } else { ?>
								<option value="mailgun">MailGun веб апи</option>
							<?php } ?>
						</select></td>

						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Имя отправителя</span></p>
							<input type="text" name="config_mail_trigger_name_from" value="<?php echo $config_mail_trigger_name_from; ?>" size="50" />								
						</td>
						
						<td width="25%">
							<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Почта отправителя</span></p>
							<input type="text" name="config_mail_trigger_mail_from" value="<?php echo $config_mail_trigger_mail_from; ?>" size="50" />								
						</td>
					</tr>
				</table>


				<h2>Интерграция с SparkPost</h2>
				
				<table class="form">
					<tr>
						<td>Включить синхронизацию Suppression List из SparkPost</td>
						<td>
							<select name="config_sparkpost_bounce_enable">
								<?php if ($config_sparkpost_bounce_enable) { ?>
									<option value="1" selected="selected">Включить</option>
									<option value="0">Отключить</option>
								<?php } else { ?>													
									<option value="1">Включить</option>
									<option value="0"  selected="selected">Отключить</option>
								<? } ?>
							</select>
						</td>

						<tr>
							<td>SparkPost API URL (EU/US)</td>
							<td>
								<input type="text" name="config_sparkpost_api_url" value="<?php echo $config_sparkpost_api_url; ?>" size="50" />								
							</td>
						</tr>
						
						<tr>
							<td>API Ключ SparkPost</td>
							<td>
								<input type="text" name="config_sparkpost_api_key" value="<?php echo $config_sparkpost_api_key; ?>" size="50" />								
							</td>
						</tr>

						<tr>
							<td>API USER SparkPost</td>
							<td>
								<input type="text" name="config_sparkpost_api_user" value="<?php echo $config_sparkpost_api_user; ?>" size="50" />								
							</td>
						</tr>
					</table>

					<h2>Интерграция с MailGun</h2>

					<table class="form">
						<td>Включить синхронизацию Suppression List из MailGun</td>
						<td>
							<select name="config_mailgun_bounce_enable">
								<?php if ($config_mailgun_bounce_enable) { ?>
									<option value="1" selected="selected">Включить</option>
									<option value="0">Отключить</option>
								<?php } else { ?>													
									<option value="1">Включить</option>
									<option value="0"  selected="selected">Отключить</option>
								<? } ?>
							</select>
						</td>

						<tr>
							<td>Домен для транзакционных</td>
							<td>
								<input type="text" name="config_mailgun_api_transaction_domain" value="<?php echo $config_mailgun_api_transaction_domain; ?>" size="50" />								
							</td>
						</tr>

						<tr>
							<td>Домен для триггеров и маркетинга</td>
							<td>
								<input type="text" name="config_mailgun_api_marketing_domain" value="<?php echo $config_mailgun_api_marketing_domain; ?>" size="50" />								
							</td>
						</tr>

						<tr>
							<td>MailGun API URL (EU/US)</td>
							<td>
								<input type="text" name="config_mailgun_api_url" value="<?php echo $config_mailgun_api_url; ?>" size="50" />								
							</td>
						</tr>

						<tr>
							<td>Public API Ключ MailGun</td>
							<td>
								<input type="text" name="config_mailgun_api_public_key" value="<?php echo $config_mailgun_api_public_key; ?>" size="50" />								
							</td>
						</tr>

						<tr>
							<td>Private API Ключ MailGun</td>
							<td>
								<input type="text" name="config_mailgun_api_private_key" value="<?php echo $config_mailgun_api_private_key; ?>" size="50" />								
							</td>
						</tr>

						<tr>
							<td>Signing Ключ MailGun</td>
							<td>
								<input type="text" name="config_mailgun_api_signing_key" value="<?php echo $config_mailgun_api_signing_key; ?>" size="50" />								
							</td>
						</tr>

						<tr>
							<td>Лимит писем MailGun</td>
							<td>
								<input type="text" name="config_mailgun_mail_limit" value="<?php echo $config_mailgun_mail_limit; ?>" size="50" />								
							</td>
						</tr>
					</tr>
				</table>

				
				
				
				<h2>Интерграция с MailWizz EMA</h2>
				<table class="form">
					<tr>
						<td>Включить MailWizz EMA API</td>
						<td>
							<select name="config_mailwizz_enable">
								<?php if ($config_mailwizz_enable) { ?>
									<option value="1" selected="selected">Включить</option>
									<option value="0">Отключить</option>
								<?php } else { ?>													
									<option value="1">Включить</option>
									<option value="0"  selected="selected">Отключить</option>
								<? } ?>
							</select>
						</td>
					</tr>
					
					<tr>
						<td>API URI</td>
						<td>
							<input type="text" name="config_mailwizz_api_uri" value="<?php echo $config_mailwizz_api_uri; ?>" size="50" />								
						</td>
					</tr>
					
					<tr>
						<td>API Ключ</td>
						<td>
							<input type="text" name="config_mailwizz_api_key" value="<?php echo $config_mailwizz_api_key; ?>" size="50" />								
						</td>
					</tr>
					
					<tr>
						<td>Лист - 1</td>
						<td>
							<input type="text" name="config_mailwizz_mapping_newsletter_news" value="<?php echo $config_mailwizz_mapping_newsletter_news; ?>" size="50" />
							<br />
							<span class="help">Новости, акции компании</span>								
						</td>
					</tr>
					
					<tr>
						<td>Лист - 2</td>
						<td>
							<input type="text" name="config_mailwizz_mapping_newsletter" value="<?php echo $config_mailwizz_mapping_newsletter; ?>" size="50" />
							<br />
							<span class="help">Информация об акциях, промокодах и скидках</span>								
						</td>
					</tr>
					
					<tr>
						<td>Лист - 3</td>
						<td>
							<input type="text" name="config_mailwizz_mapping_newsletter_personal" value="<?php echo $config_mailwizz_mapping_newsletter_personal; ?>" size="50" />
							<br />
							<span class="help">Персональные рекомендации</span>								
						</td>
					</tr>

					<tr>
						<td>Нативный домен</td>
						<td>
							<input type="text" name="config_mailwizz_exclude_native" value="<?php echo $config_mailwizz_exclude_native; ?>" size="50" />
							<br />
							<span class="help">исключать мейлы с вхождением</span>								
						</td>
					</tr>

					<tr>
						<td>Количество дней</td>
						<td>
							<input type="number" name="config_mailwizz_noorder_days" value="<?php echo $config_mailwizz_noorder_days; ?>" step="1" />
							<br />
							<span class="help">количество дней без заказов для рассылки с информацией о количестве бонусов</span>								
						</td>
					</tr>
				</table>
				
				<h2>Настройки SendMail</h2>
				<table class="form">
					<tr>
						<td>Параметры функции mail</td>
						<td><input type="text" name="config_mail_parameter" value="<?php echo $config_mail_parameter; ?>" style="width:300px;" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_smtp_host; ?></td>
						<td><input type="text" name="config_smtp_host" value="<?php echo $config_smtp_host; ?>" style="width:300px;" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_smtp_username; ?></td>
						<td><input type="text" name="config_smtp_username" value="<?php echo $config_smtp_username; ?>" style="width:300px;" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_smtp_password; ?></td>
						<td><input type="text" name="config_smtp_password" value="<?php echo $config_smtp_password; ?>" style="width:300px;" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_smtp_port; ?></td>
						<td><input type="text" name="config_smtp_port" value="<?php echo $config_smtp_port; ?>" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_smtp_timeout; ?></td>
						<td><input type="text" name="config_smtp_timeout" value="<?php echo $config_smtp_timeout; ?>" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_alert_mail; ?></td>
						<td><?php if ($config_alert_mail) { ?>
							<input type="radio" name="config_alert_mail" value="1" checked="checked" />
							<?php echo $text_yes; ?>
							<input type="radio" name="config_alert_mail" value="0" />
							<?php echo $text_no; ?>
						<?php } else { ?>
							<input type="radio" name="config_alert_mail" value="1" />
							<?php echo $text_yes; ?>
							<input type="radio" name="config_alert_mail" value="0" checked="checked" />
							<?php echo $text_no; ?>
							<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_account_mail; ?></td>
							<td><?php if ($config_account_mail) { ?>
								<input type="radio" name="config_account_mail" value="1" checked="checked" />
								<?php echo $text_yes; ?>
								<input type="radio" name="config_account_mail" value="0" />
								<?php echo $text_no; ?>
							<?php } else { ?>
								<input type="radio" name="config_account_mail" value="1" />
								<?php echo $text_yes; ?>
								<input type="radio" name="config_account_mail" value="0" checked="checked" />
								<?php echo $text_no; ?>
								<?php } ?></td>
							</tr>
							<tr>
								<td><?php echo $entry_alert_emails; ?></td>
								<td><textarea name="config_alert_emails" cols="40" rows="5"><?php echo $config_alert_emails; ?></textarea></td>
							</tr>
						</table>
					</div>