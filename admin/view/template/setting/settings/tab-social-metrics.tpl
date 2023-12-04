	<div id="tab-social-metrics">
		<h2 style="color:#57AC79">Соцсети</h2>
		<table class="form">
			<tr>		
				<td width="15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ссылка на Facebook</span></p>
					<input type="text" name="social_link_facebook" value="<?php echo $social_link_facebook; ?>" size="30" style="width:200px;" />
				</td>
				
				<td width="15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ссылка на VK</span></p>
					<input type="text" name="social_link_vkontakte" value="<?php echo $social_link_vkontakte; ?>" size="30" style="width:200px;" />
				</td>	

				<td width="15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ссылка на Instagram</span></p>
					<input type="text" name="social_link_instagram" value="<?php echo $social_link_instagram; ?>" size="30" style="width:200px;" />
				</td>

				<td width="15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ссылка на Youtube</span></p>
					<input type="text" name="social_link_youtube" value="<?php echo $social_link_youtube; ?>" size="30" style="width:200px;" />
				</td>

				<td width="15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ссылка на Twitter</span></p>
					<input type="text" name="social_link_twitter" value="<?php echo $social_link_twitter; ?>" size="30" style="width:200px;" />
				</td>

				<td width="15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Ссылка на TG-канал</span></p>
					<input type="text" name="social_link_telegram" value="<?php echo $social_link_telegram; ?>" size="30" style="width:200px;" />
				</td>
			</tr>
		</table>

		<h2 style="color:#57AC79">Боты и мессенджеры</h2>
		<table class="form">
			<tr>		
				<td width="20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Messenger BOT</span></p>
					<input type="text" name="social_link_messenger_bot" value="<?php echo $social_link_messenger_bot; ?>" size="30" style="width:150px;" />
				</td>
				
				<td width="20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Viber BOT</span></p>
					<input type="text" name="social_link_viber_bot" value="<?php echo $social_link_viber_bot; ?>" size="30" style="width:150px;" />
				</td>	

				<td width="20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Telegram BOT</span></p>
					<input type="text" name="social_link_telegram_bot" value="<?php echo $social_link_telegram_bot; ?>" size="30" style="width:150px;" />
				</td>

				<td width="20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Vkontakte BOT</span></p>
					<input type="text" name="social_link_vkontakte_bot" value="<?php echo $social_link_vkontakte_bot; ?>" size="30" style="width:150px;" />
				</td>

				<td width="20%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Whatsapp BOT</span></p>
					<input type="text" name="social_link_whatsapp_bot" value="<?php echo $social_link_whatsapp_bot; ?>" size="30" style="width:150px;" />
				</td>
			</tr>
		</table>


		<h2 style="color:#57AC79">Авторизация Google + Facebook APP</h2>
		<table class="form">
			<tr>		
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Google APP ID</span></p>
					<input type="text" name="social_auth_google_app_id" value="<?php echo $social_auth_google_app_id; ?>" size="30" style="width:150px;" />
				</td>
				
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#ef5e67; color:#FFF">Google Secret Key</span></p>
					<input type="text" name="social_auth_google_secret_key" value="<?php echo $social_auth_google_secret_key; ?>" size="30" style="width:150px;" />
				</td>	
				
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">FB APP ID</span></p>
					<input type="text" name="social_auth_facebook_app_id" value="<?php echo $social_auth_facebook_app_id; ?>" size="30" style="width:150px;" />
				</td>
				
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">FB APP Secret Key</span></p>
					<input type="text" name="social_auth_facebook_secret_key" value="<?php echo $social_auth_facebook_secret_key; ?>" size="30" style="width:150px;" />
				</td>	
			</tr>
		</table>


		<h2 style="color:#57AC79">Google Tag Manager (GTM) + Custom JS code</h2>
		<table class="form">
			<tr>		
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">GTM Javascript (header)</span></p>
					<textarea name="config_gtm_header" cols="40" rows="10"><?php echo $config_gtm_header; ?></textarea>
				</td>
				
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">GTM NoScript (body)</span></p>
					<textarea name="config_gtm_body" cols="40" rows="10"><?php echo $config_gtm_body; ?></textarea>
				</td>	

				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Custom JS-код в футер</span></p>
					<textarea name="config_google_analytics" cols="40" rows="10"><?php echo $config_google_analytics; ?></textarea>
				</td>
				
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Custom JS-код в хедер</span></p>
					<textarea name="config_google_analytics_header" cols="40" rows="10"><?php echo $config_google_analytics_header; ?></textarea>
				</td>
			</tr>
		</table>

		<h2 style="color:#57AC79">Google Analitycs, Merchant</h2>	
		<table class="form">
			<tr>
				
				<td width="15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Идентификатор Google Analitycs</span></p>
					<input type="text" name="config_google_analitycs_id" value="<?php echo $config_google_analitycs_id; ?>" size="30" style="width:150px;" />
				</td>
				<td width="15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Google Conversion ID</span></p>
					<input type="text" name="config_google_conversion_id" value="<?php echo $config_google_conversion_id; ?>" size="30" style="width:150px;" />
				</td>
				<td width="15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Google Merchant ID</span></p>
					<input type="text" name="config_google_merchant_id" value="<?php echo $config_google_merchant_id; ?>" size="30" style="width:150px;" />								
				</td>
				<td width="15%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Лимит товаров на фид</span></p>
						<input type="number" step="1000" name="config_google_merchant_feed_limit" value="<?php echo $config_google_merchant_feed_limit; ?>" size="30" style="width:150px;" />	
					</div>
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Лимит товаров на выборку</span></p>
						<input type="number" step="100" name="config_google_merchant_one_iteration_limit" value="<?php echo $config_google_merchant_one_iteration_limit; ?>" size="30" style="width:150px;" />	
					</div>									
				</td>
				<td width="15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Тип Google remarketing</span></p>
					<select name="config_google_remarketing_type">
						<?php if ($config_google_remarketing_type == 'ecomm') { ?>
							<option value="ecomm" selected="selected">E-commerce ecomm_</option>
						<?php } else { ?>
							<option value="ecomm">E-commerce ecomm_</option>
						<?php } ?>
						<?php if ($config_google_remarketing_type == 'dynx') { ?>
							<option value="dynx" selected="selected">Универсальный dynx_</option>
						<?php } else { ?>
							<option value="dynx">Универсальный dynx_</option>
						<?php } ?>
					</select>
				</td>
				<td width="15%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Передача данных Ecommerce</span></p>
					<select name="config_google_ecommerce_enable">
						<?php if ($config_google_ecommerce_enable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>
			</tr>
		</table>

		
		<h2 style="color:#57AC79">Google ReCaptcha</h2>	
		<table class="form">
			<tr>		
				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Включить ReCaptcha</span></p>
					<select name="config_google_recaptcha_contact_enable">
						<?php if ($config_google_recaptcha_contact_enable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>									
				</td>		
				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">ReCaptcha key</span></p>
					<input type="text" name="config_google_recaptcha_contact_key" value="<?php echo $config_google_recaptcha_contact_key; ?>" size="40" style="width:300px;" />
				</td>		
				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">ReCaptcha Secret</span></p>
					<input type="text" name="config_google_recaptcha_contact_secret" value="<?php echo $config_google_recaptcha_contact_secret; ?>" size="40" style="width:300px;" />
					
				</td>
			</tr>
		</table>

		<h2 style="color:#7F00FF">Facebook пиксель</h2>
		<table class="form">
			<tr>		
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">FB пиксель (header)</span></p>
					<textarea name="config_fb_pixel_header" cols="40" rows="10"><?php echo $config_fb_pixel_header; ?></textarea>
				</td>
				
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">FB пиксель (body)</span></p>
					<textarea name="config_fb_pixel_body" cols="40" rows="10"><?php echo $config_fb_pixel_body; ?></textarea>
				</td>	
				<td width="25%"></td>
				<td width="25%"></td>
			</tr>
		</table>

		<h2 style="color:#3F6AD8">VK пиксель / ремаркетинг / ретаргетинг</h2>
		<table class="form">
			<tr>		
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3F6AD8; color:#FFF">VK пиксель (header)</span></p>
					<textarea name="config_vk_pixel_header" cols="40" rows="10"><?php echo $config_vk_pixel_header; ?></textarea>
				</td>
				
				<td width="25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3F6AD8; color:#FFF">VK пиксель (body)</span></p>
					<textarea name="config_vk_pixel_body" cols="40" rows="10"><?php echo $config_vk_pixel_body; ?></textarea>
				</td>	
				<td width="20%">
					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3F6AD8; color:#FFF">Включить пиксель</span></p>
						<select name="config_vk_enable_pixel">
							<?php if ($config_vk_enable_pixel) { ?>
								<option value="1" selected="selected">Включить</option>
								<option value="0">Отключить</option>
							<?php } else { ?>													
								<option value="1">Включить</option>
								<option value="0"  selected="selected">Отключить</option>
							<? } ?>
						</select>
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3F6AD8; color:#FFF">VK пиксель ID</span></p>
						<input type="text" name="config_vk_pixel_id" value="<?php echo $config_vk_pixel_id; ?>" size="40" />
					</div>

					<div>
						<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3F6AD8; color:#FFF">VK прайслист ID</span></p>
						<input type="text" name="config_vk_pricelist_id" value="<?php echo $config_vk_pricelist_id; ?>" size="40" />
					</div>
				</td>

				<td width="30%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Включить бренды в VK</span></p>
					<div class="scrollbox" style="height:350px;">
						<?php $class = 'odd'; ?>
						<?php if ($config_vk_enable_pixel) { ?>
							<?php foreach ($manufacturers as $manufacturer) { ?>
								<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
								<div class="<?php echo $class; ?>">
									<?php if (in_array($manufacturer['manufacturer_id'], $config_vk_feed_include_manufacturers)) { ?>
										<input id="config_vk_feed_include_manufacturers_<?php echo $manufacturer['manufacturer_id']; ?>" class="checkbox" type="checkbox" name="config_vk_feed_include_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
										<label for="config_vk_feed_include_manufacturers_<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></label>
									<?php } else { ?>
										<input id="config_vk_feed_include_manufacturers_<?php echo $manufacturer['manufacturer_id']; ?>" class="checkbox" type="checkbox" name="config_vk_feed_include_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
										<label for="config_vk_feed_include_manufacturers_<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></label>
									<?php } ?>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
				</td>
			</tr>
		</table>

		<h2 style="color:#3F6AD8">VK Ремаркетинг фид</h2>
		<table class="form">
			<tr>                
				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#3F6AD8; color:#FFF">В фиде только наличие</span></p>
					<select name="config_vk_feed_only_in_stock">
						<?php if ($config_vk_feed_only_in_stock) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>                                                   
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>

				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Специальная категория - 1</span></p>
					<input type="number" step="1" name="config_vk_add_feed_for_category_id_0" value="<?php echo $config_vk_add_feed_for_category_id_0; ?>" size="30" style="width:250px;" />
					<br />
					<span class="help">будет создан дополнительно фид из товаров только этой категории</span>
				</td>

				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Специальная категория - 2</span></p>
					<input type="number" step="1" name="config_vk_add_feed_for_category_id_1" value="<?php echo $config_vk_add_feed_for_category_id_1; ?>" size="30" style="width:250px;" />
					<br />
					<span class="help">будет создан дополнительно фид из товаров только этой категории</span>
				</td>

				<td style="width:25%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF">Специальная категория - 3</span></p>
					<input type="number" step="1" name="config_vk_add_feed_for_category_id_2" value="<?php echo $config_vk_add_feed_for_category_id_2; ?>" size="30" style="width:250px;" />
					<br />
					<span class="help">будет создан дополнительно фид из товаров только этой категории</span>
				</td>
			</tr>       
		</table>


		
		<h2 style="color:#cf4a61">Yandex Metrika</h2>
		<table class="form">
			<tr>		
				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Yandex Metrika идентификатор</span></p>
					<input type="text" name="config_metrika_counter" value="<?php echo $config_metrika_counter; ?>" size="30" style="width:150px;" />
				</td>
				<td>
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Yandex WebVisor</span></p>
					<select name="config_webvisor_enable">
						<?php if ($config_webvisor_enable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>
				<td style="width:33%">
					<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Включить accurateTrackBounce, trackLinks, clickmap</span></p>
					<select name="config_clickmap_enable">
						<?php if ($config_clickmap_enable) { ?>
							<option value="1" selected="selected">Включить</option>
							<option value="0">Отключить</option>
						<?php } else { ?>													
							<option value="1">Включить</option>
							<option value="0"  selected="selected">Отключить</option>
						<? } ?>
					</select>
				</td>
			</tr>	
		</table>
	</div>