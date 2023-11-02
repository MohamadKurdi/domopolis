	<div id="tab-telephony">

		<h2>Очереди</h2>
		<table class="form">
			<tr>
				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Обслуживающая очередь</span></p>
					<select name="config_default_queue">
						<option value="">Не назначать</option>											
						<? foreach ($user_groups as $user_group) { ?>
							<?php if ($user_group['sip_queue']) { ?>	
								<?php if ($user_group['sip_queue'] == $config_default_queue) { ?>
									<option value="<?php echo $user_group['sip_queue'] ?>" selected="selected"><?php echo $user_group['sip_queue']; ?> (<?php echo $user_group['name']; ?>)</option>
								<?php } else { ?>
									<option value="<?php echo $user_group['sip_queue'] ?>"><?php echo $user_group['sip_queue']; ?> (<?php echo $user_group['name']; ?>)</option>
								<? } ?>
							<? } ?>
						<? } ?>
					</select>

				</td>

				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Очередь уведомлений менеджеров</span></p>
					<select name="config_default_alert_queue">
						<option value="">Не назначать</option>											
						<? foreach ($user_groups as $user_group) { ?>
							<?php if ($user_group['alert_namespace']) { ?>	
								<?php if ($user_group['alert_namespace'] == $config_default_alert_queue) { ?>
									<option value="<?php echo $user_group['alert_namespace'] ?>" selected="selected"><?php echo $user_group['alert_namespace']; ?> (<?php echo $user_group['name']; ?>)</option>
								<?php } else { ?>
									<option value="<?php echo $user_group['alert_namespace'] ?>"><?php echo $user_group['alert_namespace']; ?> (<?php echo $user_group['name']; ?>)</option>
								<? } ?>
							<? } ?>
						<? } ?>
					</select>

				</td>

				<td width="33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Назначенная группа менеджеров</span></p>
					<select name="config_default_manager_group">
						<option value="0">Не назначать</option>											
						<? foreach ($user_groups as $user_group) { ?>
							<?php if ($user_group['user_group_id'] == $config_default_manager_group) { ?>
								<option value="<?php echo $user_group['user_group_id'] ?>" selected="selected"><?php echo $user_group['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $user_group['user_group_id'] ?>"><?php echo $user_group['name']; ?></option>
							<? } ?>
						<? } ?>
					</select>
				</td>
			</tr>
		</table>

		<h2>Binotel (телефония)</h2>
		<table class="form">
			<tr>
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Движок телефонии</span></p>
					<select name="config_telephony_engine">
						<?php if ($config_telephony_engine == 'asterisk') { ?>
							<option value="asterisk" selected="selected">Asterisk AMI</option>
							<option value="binotel">Binotel API</option>
						<?php } else { ?>													
							<option value="asterisk">Asterisk AMI</option>
							<option value="binotel"  selected="selected">Binotel API</option>
						<? } ?>
					</select>										
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Binotel API KEY</span></p>
					<input type="text" name="config_binotel_api_key" value="<?php echo $config_binotel_api_key; ?>" size="30" style="width:300px;" />										
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Binotel API SECRET</span></p>
					<input type="text" name="config_binotel_api_secret" value="<?php echo $config_binotel_api_secret; ?>" size="30" style="width:300px;" />		
				</td>

				<td width="25%">		
				</td>
			</tr>
		</table>

		<h2>ASTERISK AMI (телефония)</h2>
		<table class="form">
			<tr>
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">AMI USER</span></p>
					<input type="text" name="config_asterisk_ami_user" value="<?php echo $config_asterisk_ami_user; ?>" size="30" style="width:300px;" />										
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">AMI PASSWD</span></p>
					<input type="text" name="config_asterisk_ami_pass" value="<?php echo $config_asterisk_ami_pass; ?>" size="30" style="width:300px;" />										
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">AMI HOST</span></p>
					<input type="text" name="config_asterisk_ami_host" value="<?php echo $config_asterisk_ami_host; ?>" size="30" style="width:300px;" />		
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">AMI WORKTIME</span></p>
					<input type="text" name="config_asterisk_ami_worktime" value="<?php echo $config_asterisk_ami_worktime; ?>" size="30" style="width:300px;" />		
				</td>
			</tr>
		</table>

		<h2>GOIP4 (телефония, для балансов и мониторинга)</h2>
		<table class="form">
			<tr>
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">GOIP4 USER</span></p>
					<input type="text" name="config_goip4_user" value="<?php echo $config_goip4_user; ?>" size="30" style="width:300px;" />									
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">GOIP4 PASSWD</span></p>
					<input type="text" name="config_goip4_passwd" value="<?php echo $config_goip4_passwd; ?>" size="30" style="width:300px;" />										
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">GOIP4 HOST</span></p>
					<input type="text" name="config_goip4_uri" value="<?php echo $config_goip4_uri; ?>" size="30" style="width:300px;" />		
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Количество SIM</span></p>
					<input type="number" step="1" name="config_goip4_simnumber" value="<?php echo $config_goip4_simnumber; ?>" size="2" style="width:100px;" />		
				</td>
			</tr>

			<tr>
				<?php for ($i=1; $i<=$config_goip4_simnumber; $i++) { ?>
					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">GOIP4 <?php echo $i; ?> линия</span></p>
						<input type="text" name="config_goip4_simnumber_<?php echo $i; ?>" value="<?php echo ${'config_goip4_simnumber_' . $i}; ?>" size="30" style="width:300px;" />		
					</td>
				<?php } ?>
			</tr>


			<tr>
				<?php for ($i=1; $i<=$config_goip4_simnumber; $i++) { ?>
					<td width="25%">
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Функция проверки баланса <?php echo $i; ?></span></p>
						<input type="text" name="config_goip4_simnumber_checkfunction_<?php echo $i; ?>" value="<?php echo ${'config_goip4_simnumber_checkfunction_' . $i}; ?>" size="30" style="width:300px;" />		
					</td>
				<?php } ?>
			</tr>

		</table>

		<h2>Авторизация пользователей на сервере LDAP (Active Directory)</h2>
		<table class="form">
			<tr>
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Включить</span></p>
					<select name="config_ldap_auth_enable">
						<?php if ($config_ldap_auth_enable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>										
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">LDAP Distinguished Name</span></p>
					<input type="text" name="config_ldap_dn" value="<?php echo $config_ldap_dn; ?>" size="30" style="width:250px;" />										
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">LDAP HOST</span></p>
					<input type="text" name="config_ldap_host" value="<?php echo $config_ldap_host; ?>" size="30" style="width:250px;" />		
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">LDAP DOMAIN</span></p>
					<input type="text" name="config_ldap_domain" value="<?php echo $config_ldap_domain; ?>" size="30" style="width:250px;" />		
				</td>

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Членство в группе</span></p>
					<input type="text" name="config_ldap_group" value="<?php echo $config_ldap_group; ?>" size="2" style="width:250px;" />		
				</td>
			</tr>
		</table>


	</div>