<div id="tab-app">
	<h2>Google Play Store</h2>
	<table class="form">
		<tr>
			<td>Включить линк на GPS</td>
			<td>
				<select name="config_android_playstore_enable">
					<?php if ($config_android_playstore_enable) { ?>
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
			<td>Google Play Store ID</td>
			<td>
				<input type="text" name="config_android_playstore_code" value="<?php echo $config_android_playstore_code; ?>" size="20" />
				<br />
				<span class="help">ua.com.mywonderapp.twa</span>
			</td>
		</tr>
		
		<tr>
			<td>Ссылка на Google Play Store</td>
			<td>
				<input type="text" name="config_android_playstore_link" value="<?php echo $config_android_playstore_link; ?>" size="50" />
				<br />
				<span class="help">https://play.google.com/store/apps/details?id=ua.com.mywonderapp.twa</span>
			</td>
		</tr>

		<tr>
			<td>Ссылка на Андроид приложение админки</td>
			<td>
				<input type="text" name="config_android_application_link" value="<?php echo $config_android_application_link; ?>" size="50" />
				<br />
				<span class="help"><?php echo HTTPS_CATALOG; ?>admin/app/admin.application.twa.apk</span>
			</td>
		</tr>
		
		<tr>
			<td>Код FireBase (FCM)</td>
			<td><textarea name="config_firebase_code" cols="50" rows="10"><?php echo $config_firebase_code; ?></textarea></td>
		</tr>
	</table>	
	
	<h2>Microsoft Store</h2>
	<table class="form">
		<tr>
			<td>Включить линк на MSS</td>
			<td>
				<select name="config_microsoft_store_enable">
					<?php if ($config_microsoft_store_enable) { ?>
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
			<td>Microsoft Store ID</td>
			<td>
				<input type="text" name="config_microsoft_store_code" value="<?php echo $config_microsoft_store_code; ?>" size="20" />
				<br />
				<span class="help"></span>
			</td>
		</tr>
		
		<tr>
			<td>Ссылка на Microsoft Store</td>
			<td>
				<input type="text" name="config_microsoft_store_link" value="<?php echo $config_microsoft_store_link; ?>" size="50" />
				<br />
				<span class="help"></span>
			</td>
		</tr>
	</table>	
</div>