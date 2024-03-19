<?php echo $header; ?>
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

<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<style>
	.account_content .head_wrap{
		margin-bottom: 30px;
	}
	.account_content .head_wrap .title{
		font-size: 22px;
		display: block;
		margin-bottom: 25px;
		font-weight: 500;
		text-align: center;
	}
	.account_content .head_wrap li{
	font-size: 16px;
	margin-bottom: 10px;
	line-height: 25px;
	position: relative;
	}
	.account_content .head_wrap ul li::before {
	content: "";
	background: #57ac79;
	font-weight: bold;
	display: inline-block;
	width: 7px;
	height: 7px;
	position: absolute;
	left: -16px;
	border-radius: 100px;
	top: 8px;
	}
	.account_content .head_wrap ul{
	padding-left: 25px;
	}
	.account_content .content_wrap{
	margin-bottom: 30px;
	}
	.account_content .content_wrap.two_column{
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 30px;
	}
	.account_content .content_wrap .text{
	display: flex;
	position: relative;
	flex-direction: column;
	}
	.account_content .content_wrap .text svg{
	position: absolute;
	left: 0;
	top: 0;
	}
	.account_content .content_wrap .text .title{
	font-size: 18px;
	padding-left: 60px;
	font-weight: 500;
	margin-bottom: 15px;
	}
	.account_content .content_wrap .text p{
	font-size: 14px;
	line-height: 16px;
	color: #b8b8b8;
	padding-left: 60px;
	}
	.account_content .content_wrap .phone{
	display: flex;
	align-items: center;
	font-size: 30px;
	letter-spacing: -1px;
	line-height: 30px;
	}
	.account_content .social_wrap{
	gap: 20px;
	display: grid;
	grid-template-columns: repeat(auto-fit,minmax(150px,1fr));
	}
	.form-group input{
	border: 1px solid #eae9e8;
	height: 40px;
	padding: 0 15px;
	}
	.form-group{
	display: flex;
	flex-direction: column;
	}
	.form-group label{
	margin-top: 10px;
	}
	.form-group-btn input {
	padding: 6px 0;
	display: flex;
	justify-content: center;
	align-items: center;
	width: 100%;
	height: 50px;
	color: #fff;
	font-weight: 600;
	font-size: 17px;
	text-decoration: none;
	text-align: center;
	cursor: pointer;
	margin-top: 30px;
	}
	.form-group textarea {
	border: 1px solid #eae9e8;
	padding: 0 15px;
	}
	#write_us_modal.popup-form{
	max-width: 600px;
	}
	#write_us_modal.popup-form form{
	padding: 20px;
	}
	#write_us_modal.popup-form form .title{
	font-size: 24px;
	}
	#write_us_modal.popup-form form .form-group label{
	font-size: 18px;
	display: block;
	margin-bottom: 5px;
	}
	#write_us_modal.popup-form form .form-group input{
	height: 60px;
	font-size: 17px;
	border-bottom: 1px solid #bdbdbd;
	transition: border-color .2s ease;
	padding: 0;
	box-shadow: none;
	border-top: 0;
	border-left: 0;
	border-right: 0;
	background: transparent;
	margin-bottom: 3px;
	}
	#write_us_modal.popup-form form .form-group textarea{
	border-bottom: 1px solid #bdbdbd;
	margin-bottom: 3px;
	border-top: 0;
	border-left: 0;
	border-right: 0;
	font-size: 17px;
	resize: none;
	background: transparent;
	padding: 0;
	}
	#write_us_modal.popup-form form .form-group .error{
	color: #e16a5d;
	margin-bottom: 10px;
	display: block;
	}
	@media screen and (max-width: 560px) {
		.account_content .content_wrap.two_column {
		    grid-template-columns:  1fr;
		}
		.account_content .content_wrap .phone{
			padding-left: 60px
		}
		.account_content .social_wrap {
		    display: flex;
		    flex-wrap: wrap;
		    justify-content: center;
		}
	}
</style>	
<section id="content" class="support_wrap account_wrap"><?php echo $content_top; ?>
	<div class="wrap two_column">
        <div class="side_bar">
            <?php echo $column_left; ?>
		</div>
		
		<div class="account_content">
			<div class="head_wrap">
				<span class="title"><?php echo $support_account_text;?></span>
				<ul>
					<li><?php echo $support_account_text_1;?></li>
					<li><?php echo $support_account_text_2;?></li>
					<li><?php echo $support_account_text_3;?></li>
					<li><?php echo $support_account_text_4;?></li>
					<li><?php echo $support_account_text_5;?></li>
				</ul>
			</div>
			<div class="content_wrap two_column">
				
				<div class="text">
					<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 122.88 107.22"><defs><style>.cls-1322{fill:#f0bd5c;}.cls-2144114{fill:#58a577;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1322" d="M81.64,54.65l-.53,1.64-.08.14-.07.09a7.07,7.07,0,0,1-2,.23,12.81,12.81,0,0,0,.61-5.63h0a1.55,1.55,0,0,1,.61-1.35,6.75,6.75,0,0,0,2.38-3.39l.33-2.61a4.22,4.22,0,0,0-.19-1.05.76.76,0,0,0-.11-.29h-.26a1.54,1.54,0,0,1-1.44-1.52c0-4.92-.47-8-1.37-10.13-1.52-2.57-3.87-3.87-6.67-5.27-.49-.21-1-.44-1.5-.7-9.13,9.54-16.69-2.73-27.52,15.75h-.37c-.14.32-.29.64-.43,1l-.08.16a1.53,1.53,0,0,1-2.09.56q-.36-.21-.42-.18c-.06,0-.13.16-.22.37A5,5,0,0,0,39.89,44c-.11,2.16.59,5,2,6.37a1.45,1.45,0,0,1,.47,1.06c.16,6.6,3.09,9.12,6.65,12.19l1.5,1.3c3.57,3.18,7.34,4.82,11,4.82S68.7,68.2,72,65h4.49l-.81.77-1.48,1.4c-4,3.81-8.26,5.65-12.64,5.65s-8.92-1.91-13.07-5.6l-1.46-1.28c-3.51-3-6.49-5.59-7.42-11.21l-4.82.39a3.58,3.58,0,0,1-4.05-2.93L28.89,37.41a3.55,3.55,0,0,1,3.16-3.9h.05l1.57-.13a2,2,0,0,1-.16-.65c-.94-15.35,5.68-25,14.63-29.63A29.8,29.8,0,0,1,77.25,4.6c8.18,5.27,13.52,14.91,11.12,28.32a1.72,1.72,0,0,1-.18.54l2.39.27A3.82,3.82,0,0,1,94,37.91S94,38,94,38L92.12,52.06a3.91,3.91,0,0,1-4.39,3.29h0a26.49,26.49,0,0,1-1,3.13,7,7,0,0,1-1.5,2.33c-2,2.06-8.46,2.06-10.75,2.06h-6a7,7,0,0,1-5.25,2c-3.32,0-6-1.76-6-3.93s2.68-3.93,6-3.93a7.05,7.05,0,0,1,5.06,2h6.15c1.8,0,7.14,0,8.06-.93a3,3,0,0,0,.64-1L83.82,55l-2.18-.25Z"/><path class="cls-2144114" d="M44.78,72.82,54,97l4.64-13.19-2.27-2.48c-1.71-2.5-1.12-5.33,2-5.84a23.11,23.11,0,0,1,3.43-.07,18.29,18.29,0,0,1,3.77.15c2.94.64,3.25,3.49,1.78,5.76l-2.23,2.48L69.75,97l8.35-24.2c6,5.42,27.21,6.51,33.84,10.2,9.18,5.14,8.93,15,10.94,24.2H0C2,98.11,1.79,88.08,10.94,83,19.09,78.47,38.11,78.82,44.78,72.82Z"/></g></g></svg>
					<span class="title"><?php echo $support_account_text_6;?></span>
					<p><?php echo $support_account_text_7;?></p>
				</div>
				
				
				<?php if ($telephone) { ?>
					<div class="phone-1">
						<?php if (!empty($telephone)) { ?>
							<a href="tel:<?php echo $telephone; ?>"><?php echo $telephone; ?></a>
						<?php } ?>
						<?php if (!empty($telephone2)) { ?>
							<a href="tel:<?php echo $telephone2; ?>"><?php echo $telephone2; ?></a>
						<?php } ?>
					</div>	
				<?php } ?>
			</div>
			<div class="content_wrap">
				<div class="text">
					<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 119.46 99.53"><path fill="#57ac79" d="M46.74,0h-30A16.09,16.09,0,0,0,4.88,4.88,16.09,16.09,0,0,0,0,16.7V46A16.11,16.11,0,0,0,4.88,57.85,16.17,16.17,0,0,0,16.7,62.73H29.6A65.21,65.21,0,0,1,26,73.2a27.84,27.84,0,0,1-7.3,9.49A57.84,57.84,0,0,0,36.1,75.11,51.69,51.69,0,0,0,49.27,62.73H60.35A16.68,16.68,0,0,0,77.05,46V16.7A16.06,16.06,0,0,0,72.16,4.88,16.09,16.09,0,0,0,60.35,0Z"/><path fill="#fbc04f" d="M119.46,30.79V60.1A19.35,19.35,0,0,1,118,67.58a19.12,19.12,0,0,1-4.27,6.31,22,22,0,0,1-2.58,2.19,18.89,18.89,0,0,1-2.84,1.68l-.16.07a18.43,18.43,0,0,1-3.86,1.29,19.7,19.7,0,0,1-4.31.46H90.74c.19.59.38,1.17.58,1.75.57,1.64,1.2,3.26,1.92,4.84v0h0a20.54,20.54,0,0,0,2.56,4.28,31.64,31.64,0,0,0,4,4.26,2.74,2.74,0,0,1-2.5,4.71h0a64.52,64.52,0,0,1-9.66-3.31,57,57,0,0,1-8.58-4.64h0a55.47,55.47,0,0,1-7.5-5.9,56.46,56.46,0,0,1-5.51-6H56.36a20,20,0,0,1-5.23-.68,18.12,18.12,0,0,1-4.75-2.05,2.74,2.74,0,1,1,2.84-4.69,12.65,12.65,0,0,0,3.33,1.43,14.62,14.62,0,0,0,3.81.48H67.43a2.74,2.74,0,0,1,2.2,1.1,49.3,49.3,0,0,0,5.74,6.42,48.73,48.73,0,0,0,6.74,5.3h0a50.66,50.66,0,0,0,7.42,4,23,23,0,0,1-1.32-2.56h0q-1.12-2.52-2.08-5.26t-1.61-5.36a2.66,2.66,0,0,1-.17-.94,2.75,2.75,0,0,1,2.75-2.75H100a15.15,15.15,0,0,0,3.15-.32,13.5,13.5,0,0,0,2.69-.9l.12-.06a12.62,12.62,0,0,0,2-1.19,15.64,15.64,0,0,0,1.9-1.6A13.68,13.68,0,0,0,113,65.49a14,14,0,0,0,1-5.39V30.79a13.92,13.92,0,0,0-1-5.37,13.74,13.74,0,0,0-3.06-4.5h0a13.93,13.93,0,0,0-4.5-3.07,14.11,14.11,0,0,0-5.37-1H86.4a2.75,2.75,0,1,1,0-5.5H100A18.89,18.89,0,0,1,113.76,17h0A19.06,19.06,0,0,1,118,23.33a19.29,19.29,0,0,1,1.43,7.46Z"/></svg>
					<span class="title"><?php echo $support_account_text_8;?></span>
					<p><?php echo $support_account_text_9;?></p>
				</div>
			</div>
			<div class="social_wrap">
				<!-- <div style="text-align:center;">
					<button style="background: transparent;padding: 0;" class="do-popup-element" data-target="write_us_modal">
						<i class="fa fa-comments" aria-hidden="true" style=" background-color: #57ac79;color: #fff;width: 70px;height: 70px;font-size: 41px;border-radius: 100px;text-align: center;line-height: 70px;margin-bottom: 5px;"></i>
						<br />
						<div style="display:inline-block;background-color: #57ac79;color: #fff;text-align: center;height: 40px;border-radius: 100px; width:120px; line-height: 40px;font-weight: 700;"><?php echo $support_account_text_10;?></div>
					</button>
				</div> -->
				<?php if ($this->config->get('social_link_messenger_bot')) { ?>
					<div class="<?php if ($this->config->get('config_regional_currency') != 'UAH') { ?><?php } else { ?><? } ?>" style="text-align:center;">
						<a href="<?php echo $this->config->get('social_link_messenger_bot'); ?>" style="text-decoration:none;" class="facebook" rel="noindex nofollow" >
							<i class="fab fa-facebook-messenger" style=" background-image: linear-gradient(to bottom, #00c6ff, #00b5ff, #00a3ff, #008fff, #0078ff);color: #fff;width: 70px;height: 70px;font-size: 41px;border-radius: 100px;text-align: center;line-height: 70px;margin-bottom: 5px;"></i>
							<br />
							<div style="display:inline-block;background-color: #0078ff3b;color: #0078ff;text-align: center;height: 40px;border-radius: 100px; width:120px; line-height: 40px;font-weight: 700;">messenger</div>
						</a>
					</div>
				<? } ?>
				<?php if ($this->config->get('social_link_viber_bot')) { ?>
					<div class="<?php if ($this->config->get('config_regional_currency') != 'UAH') { ?><?php } else { ?><? } ?>" style="text-align:center;">
						<a href="<?php echo $this->config->get('social_link_viber_bot'); ?>" style="text-decoration:none;" rel="nofollow">
							<div style="display:inline-block; background-color: #995aca; border-radius:50%; background-position: center;background-size: 100% auto;background-repeat: no-repeat;  background-image: url('https://imsgroup.bitrix24.ru/bitrix/js/ui/icons/service/images/ui-service-viber.svg'); width:70px; height:70px;"></div><br />
							<div style="display:inline-block;background-color:#f9f0ff;color:#8861b2;text-align: center;height: 40px;border-radius: 100px; width:120px; line-height: 40px;font-weight: 700;">VIBER</div>
						</a>
					</div>
				<? } ?>
				<?php if ($this->config->get('social_link_telegram_bot')) { ?>
					<div class="<?php if ($this->config->get('config_regional_currency') != 'UAH') { ?><?php } else { ?><? } ?>" style="text-align:center;">
						<a href="<?php echo $this->config->get('social_link_telegram_bot'); ?>" style="text-decoration:none;" rel="nofollow">
							<div style="display:inline-block; background-color: #2fc6f6; border-radius:50%; background-position: center;background-size: 100% auto;background-repeat: no-repeat;  background-image: url('https://imsgroup.bitrix24.ru/bitrix/js/ui/icons/service/images/ui-service-telegram.svg'); width:70px; height:70px;"></div><br />
							<div style="display:inline-block;background-color:#e7f7ff;color:#4ba4e8;text-align: center;height: 40px;border-radius: 100px; width:120px; line-height: 40px;font-weight: 700;">TELEGRAM</div>
						</a>
					</div>
				<?php } ?>
			</div>
		</div>
		
		
		<?php echo $content_bottom; ?>
	</div>
</div>
</section>


<div class="popup-form" id="write_us_modal">
    <div class="object">
        <div class="overlay-popup-close"><i class="fas fa-times"></i></div>
        <div class="info-order-container">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data"> 
				<div class="superform">
					<h3 class="title"><?php echo $text_contact; ?></h3>
					<div class="form-group">
						<!-- <label style="margin-top: 0"><?php echo $entry_name; ?></label> -->
						<input type="text" name="name" value="<?php echo $name; ?>" class="form-control" placeholder="<?php echo $entry_name; ?>"/>
						
						<?php if ($error_name) { ?>
							<span class="error"><?php echo $error_name; ?></span>
						<?php } ?>
					</div>
					<div class="form-group">
						<!-- <label><?php echo $entry_email; ?></label> -->
						<input type="text" name="email" value="<?php echo $email; ?>" class="form-control" placeholder="<?php echo $entry_email; ?>"/>
						<?php if ($error_email) { ?>
							<span class="error"><?php echo $error_email; ?></span>
						<?php } ?>
					</div>
					<div class="form-group">
						<!-- <label><?php echo $entry_enquiry; ?></label> -->
						<textarea name="enquiry" cols="40" rows="10" class="form-control" placeholder="<?php echo $entry_enquiry; ?>"><?php echo $enquiry; ?></textarea>
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
</div>
<?php echo $footer; ?>