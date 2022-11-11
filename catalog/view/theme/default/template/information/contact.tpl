<?php echo $header; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>

<?php if ($this->config->get('config_google_recaptcha_contact_enable')) { ?>
	<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $this->config->get('config_google_recaptcha_contact_key'); ?>"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo $this->config->get('config_google_recaptcha_contact_key'); ?>', {action: 'homepage'}).then(function(token) {
                document.getElementById('g-recaptcha-response').value=token;
			});
		});
	</script>
	
<?php } ?>

<section id="content-contact">
	<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
	<?php if ($hb_snippets_local_enable == 'y'){echo html_entity_decode($hb_snippets_local_snippet);} ?>
	<div class="wrap">
		<?php echo $content_top; ?>
		
    	<div class="contact-info">
			
			<div class="desk-contact">
				<?php echo $text_messenger_contact; ?>
			</div>
			
			<div class="social-btn-contact">
				<div class="<?php if ($this->config->get('config_regional_currency') != 'UAH') { ?><?php } else { ?><? } ?>" style="text-align:center;">
					<a href="https://m.me/104116757981722" style="text-decoration:none;" class="facebook" rel="noindex nofollow" >
						<i class="fab fa-facebook-messenger" style="
						background-image: linear-gradient(to bottom, #00c6ff, #00b5ff, #00a3ff, #008fff, #0078ff);
						color: #fff;
						width: 70px;
						height: 70px;
						font-size: 41px;
						border-radius: 100px;
						text-align: center;
						line-height: 70px;
						margin-bottom: 5px;"></i>
						<br />
						<div style="display:inline-block;background-color: #0078ff3b;color: #0078ff;text-align: center;height: 40px;border-radius: 100px; width:120px; line-height: 40px;font-weight: 700;">messenger</div>
					</a>
				</div>
				<div class="<?php if ($this->config->get('config_regional_currency') != 'UAH') { ?><?php } else { ?><? } ?>" style="text-align:center;">
					<a href="viber://pa?chatURI=kitchen-profi" style="text-decoration:none;" rel="nofollow">
						<div style="display:inline-block; background-color: #995aca; border-radius:50%; background-position: center;background-size: 100% auto;background-repeat: no-repeat;  background-image: url('https://imsgroup.bitrix24.ru/bitrix/js/ui/icons/service/images/ui-service-viber.svg'); width:70px; height:70px;"></div><br />
						<div style="display:inline-block;background-color:#f9f0ff;color:#8861b2;text-align: center;height: 40px;border-radius: 100px; width:120px; line-height: 40px;font-weight: 700;">VIBER</div>
					</a>
				</div>
				<div class="<?php if ($this->config->get('config_regional_currency') != 'UAH') { ?><?php } else { ?><? } ?>" style="text-align:center;">
					<a href="https://teleg.one/kitchenprofi_bot" style="text-decoration:none;" rel="nofollow">
						<div style="display:inline-block; background-color: #2fc6f6; border-radius:50%; background-position: center;background-size: 100% auto;background-repeat: no-repeat;  background-image: url('https://imsgroup.bitrix24.ru/bitrix/js/ui/icons/service/images/ui-service-telegram.svg'); width:70px; height:70px;"></div><br />
						<div style="display:inline-block;background-color:#e7f7ff;color:#4ba4e8;text-align: center;height: 40px;border-radius: 100px; width:120px; line-height: 40px;font-weight: 700;">TELEGRAM</div>
					</a>
				</div>
				<div class="<?php if ($this->config->get('config_regional_currency') != 'UAH') { ?><?php } else { ?>hidden<? } ?>" style="text-align:center;">
					<a href="http://vk.me/club88315749" style="text-decoration:none;" rel="nofollow">
						<div style="display:inline-block; background-color: #3871ba; border-radius:50%; background-position: center;background-size: 100% auto;background-repeat: no-repeat;  background-image: url('https://imsgroup.bitrix24.ru/bitrix/js/ui/icons/service/images/ui-service-vk.svg'); width:70px; height:70px;"></div><br />
						<div style="display:inline-block;background-color:#e7f7ff;color:#8861b2;text-align: center;height: 40px;border-radius: 100px; width:120px; line-height: 40px;font-weight: 700;">VKONTAKTE</div>
					</a>
				</div>
			</div>
			
			<div class="wrap-our-contact">			
				<div class="left-column">
					<div>
						<h3 class="title"><?php echo $text_address; ?></h3>
						<p><?php echo $store; ?></p> 
						<?php echo $address; ?>
						<?php if ($this->config->get('config_store_id') == 0) {?>
							<p>ИП Башлаев Сергей Иванович</p>
							<p>ОГРН 318774600597332</p>
							<?php } elseif ($this->config->get('config_store_id') == 5) { ?>
							<p hidden="hidden">
								Свидетельство о государственной регистрации № 193533874 от 13.04.2021 
								<br>выдано Минским горисполкомом 
								<br>Интернет-магазин зарегистрирован в Торговом реестре РБ 21.04.2021
							</p>
						<?php } ?>
					</div>
					<?php if ($telephone) { ?>
						<div>
							<h3 class="title"><?php echo $text_telephone; ?></h3>
							<?php if (!empty($telephone)) { ?>
								<p><i class="fa fa-phone"></i> <?php echo $telephone; ?></p>
							<?php } ?>
							<?php if (!empty($telephone2)) { ?>
								<p><i class="fa fa-phone"></i> <?php echo $telephone2; ?></p>
							<?php } ?>
						</div>	
					<?php } ?>
					<?php if ($contact_email) { ?>
						<div>
							<h3 class="title">E-mail</h3>
							<p><i class="fa fa-envelope"></i> <?php echo $contact_email; ?></p>
						</div>	
					<?php } ?>
				</div> 
				
				<div class="right-column">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data"> 
						<div class="superform">
							<h3 class="title"><?php echo $text_contact; ?></h3>
							<div class="form-group">
								<label style="margin-top: 0"><?php echo $entry_name; ?></label>
								<input type="text" name="name" value="<?php echo $name; ?>" class="form-control"/>
								
								<?php if ($error_name) { ?>
									<span class="error"><?php echo $error_name; ?></span>
								<?php } ?>
							</div>
							<div class="form-group">
								<label><?php echo $entry_email; ?></label>
								<input type="text" name="email" value="<?php echo $email; ?>" class="form-control"/>
								<?php if ($error_email) { ?>
									<span class="error"><?php echo $error_email; ?></span>
								<?php } ?>
							</div>
							<div class="form-group">
								<label><?php echo $entry_enquiry; ?></label>
								<textarea name="enquiry" cols="40" rows="10" class="form-control"><?php echo $enquiry; ?></textarea>
								<?php if ($error_enquiry) { ?>
									<span class="error"><?php echo $error_enquiry; ?></span>
								<?php } ?>
							</div>
							<?php if ($this->config->get('config_google_recaptcha_contact_enable')) { ?>
								<div class="form-group" style="padding-top: 10px;">
									<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" />
									<?php if ($error_captcha) { ?>
										<span class="error"><?php echo $error_captcha; ?></span>
									<?php } ?>
								</div>
							<?php } ?>
							
							<?php if (!empty($success)) { ?>
							<div class="form-group" style="padding-top: 10px;">
								<span class="text-success"><?php echo $success; ?></span>
							</div>
							<?php } ?>
							
							<div class="form-group-btn">
								<input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-default" />
							</div>
						</div>
					</form>
				</div>
			</div>
			
			<?php echo $content_bottom; ?>
		</div>
	</section>
<?php echo $footer; ?>