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
				<?php if ($this->config->get('social_link_messenger_bot')) { ?>
					<div class="<?php if ($this->config->get('config_regional_currency') != 'UAH') { ?><?php } else { ?><? } ?>" style="text-align:center;">
						<a href="<?php echo $this->config->get('social_link_messenger_bot'); ?>" style="text-decoration:none;" class="facebook" rel="noindex nofollow" >
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
				<? } ?>
				<?php if ($this->config->get('social_link_viber_bot')) { ?>
					<div class="<?php if ($this->config->get('config_regional_currency') != 'UAH') { ?><?php } else { ?><? } ?>" style="text-align:center;">
						<a href="<?php echo $this->config->get('social_link_viber_bot'); ?>" style="text-decoration:none;" rel="nofollow">
							<div style="display:inline-block; background-color: #995aca; border-radius:50%;width:70px; height:70px;">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"><path fill="#FFF" d="M32.508 15.53l-.007-.027c-.53-2.17-2.923-4.499-5.12-4.983l-.025-.006a28.14 28.14 0 0 0-10.712 0l-.025.006c-2.197.484-4.59 2.813-5.121 4.983l-.006.027a21.443 21.443 0 0 0 0 9.135l.006.026c.509 2.078 2.723 4.3 4.839 4.91v2.423c0 .877 1.056 1.308 1.657.675l2.426-2.552a27.78 27.78 0 0 0 6.936-.467l.024-.005c2.198-.485 4.59-2.814 5.121-4.984l.007-.026a21.447 21.447 0 0 0 0-9.135zm-2.01 8.435c-.35 1.374-2.148 3.082-3.577 3.398-1.87.352-3.755.503-5.638.452a.134.134 0 0 0-.1.04L19.43 29.64l-1.865 1.899c-.136.14-.376.045-.376-.15v-3.895a.135.135 0 0 0-.11-.131h-.001c-1.429-.316-3.226-2.024-3.577-3.399a18.53 18.53 0 0 1 0-8.013c.351-1.374 2.148-3.082 3.577-3.398a26.437 26.437 0 0 1 9.843 0c1.43.316 3.227 2.024 3.578 3.398a18.511 18.511 0 0 1 0 8.014zm-5.676 2.065c-.225-.068-.44-.115-.64-.198-2.068-.861-3.97-1.973-5.478-3.677-.858-.968-1.529-2.062-2.096-3.22-.269-.549-.496-1.12-.727-1.686-.21-.517.1-1.05.427-1.44a3.37 3.37 0 0 1 1.128-.852c.334-.16.663-.068.906.216.527.614 1.01 1.259 1.402 1.97.24.438.175.973-.262 1.27-.106.073-.202.158-.301.24a.99.99 0 0 0-.228.24.662.662 0 0 0-.044.58c.538 1.486 1.446 2.64 2.935 3.263.238.1.477.215.751.183.46-.054.609-.56.931-.825.315-.258.717-.262 1.056-.046.34.215.668.447.995.68.321.23.64.455.936.717.285.251.383.581.223.923-.294.625-.72 1.146-1.336 1.478-.174.093-.382.124-.578.184-.225-.069.196-.06 0 0zm-2.378-11.847c2.464.075 4.488 1.86 4.922 4.517.074.452.1.915.133 1.375.014.193-.087.377-.278.38-.198.002-.286-.178-.3-.371-.025-.383-.042-.767-.09-1.146-.256-2.003-1.72-3.66-3.546-4.015-.275-.054-.556-.068-.835-.1-.176-.02-.407-.031-.446-.27a.32.32 0 0 1 .297-.37c.048-.003.096 0 .143 0 2.464.075-.047 0 0 0zm2.994 5.176c-.004.033-.006.11-.023.183-.06.265-.405.298-.484.03a.918.918 0 0 1-.028-.254c0-.558-.105-1.115-.347-1.6-.249-.5-.63-.92-1.075-1.173a2.786 2.786 0 0 0-.857-.306c-.13-.025-.26-.04-.39-.06-.157-.026-.241-.143-.234-.323.007-.169.114-.29.272-.28.52.035 1.023.165 1.485.45.94.579 1.478 1.493 1.635 2.713.007.055.018.11.022.165.009.137.014.274.023.455-.003.033-.009-.18 0 0zm-.996.397c-.275.005-.423-.144-.451-.391-.02-.173-.035-.348-.077-.516a1.447 1.447 0 0 0-.546-.84 1.436 1.436 0 0 0-.444-.21c-.202-.057-.412-.04-.613-.09-.219-.052-.34-.226-.305-.427a.394.394 0 0 1 .417-.311c1.275.09 2.186.737 2.316 2.209.01.104.02.213-.003.313a.325.325 0 0 1-.294.263c-.275.005.125-.008 0 0z"/></svg>
							</div><br />
							<div style="display:inline-block;background-color:#f9f0ff;color:#8861b2;text-align: center;height: 40px;border-radius: 100px; width:120px; line-height: 40px;font-weight: 700;">VIBER</div>
						</a>
					</div>
				<? } ?>
				<?php if ($this->config->get('social_link_telegram_bot')) { ?>
				<div class="<?php if ($this->config->get('config_regional_currency') != 'UAH') { ?><?php } else { ?><? } ?>" style="text-align:center;">
					<a href="<?php echo $this->config->get('social_link_telegram_bot'); ?>" style="text-decoration:none;" rel="nofollow">
						<div style="display:inline-block; background-color: #2fc6f6; border-radius:50%; width:70px; height:70px;">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"><path fill="#FFF" d="M25.616 16.036L17.8 23.269a1.61 1.61 0 0 0-.502.965l-.266 1.964c-.035.263-.405.289-.478.035l-1.024-3.582a.948.948 0 0 1 .417-1.068l9.471-5.807c.17-.104.346.125.2.26m3.793-3.997L9.52 19.677a.568.568 0 0 0 .005 1.064l4.847 1.8 1.876 6.005c.12.385.592.527.906.272l2.701-2.192a.809.809 0 0 1 .983-.028l4.872 3.522c.336.242.811.06.895-.344l3.57-17.09a.57.57 0 0 0-.765-.647"/></svg>
						</div><br />
						<div style="display:inline-block;background-color:#e7f7ff;color:#4ba4e8;text-align: center;height: 40px;border-radius: 100px; width:120px; line-height: 40px;font-weight: 700;">TELEGRAM</div>
					</a>
				</div>
				<? } ?>
				<?php if ($this->config->get('social_link_vkontakte')) { ?>
				<div class="<?php if ($this->config->get('config_regional_currency') != 'UAH') { ?><?php } else { ?>hidden<? } ?>" style="text-align:center;">
					<a href="<?php echo $this->config->get('social_link_vkontakte'); ?>" style="text-decoration:none;" rel="nofollow">
						<div style="display:inline-block; background-color: #3871ba; border-radius:50%;width:70px; height:70px;">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42"><path fill="#FFF" d="M30.889 26.837a1.64 1.64 0 0 0-.072-.137c-.364-.657-1.06-1.463-2.088-2.42l-.022-.022-.01-.01-.011-.012h-.011c-.467-.445-.762-.744-.886-.898-.226-.292-.277-.587-.153-.887.087-.226.415-.704.984-1.434.299-.387.535-.697.71-.93 1.261-1.68 1.808-2.753 1.64-3.22l-.065-.11c-.043-.065-.156-.126-.338-.18-.183-.055-.416-.064-.7-.028l-3.15.022a.408.408 0 0 0-.218.006l-.142.033-.055.027-.044.033a.486.486 0 0 0-.12.115.752.752 0 0 0-.109.191 17.894 17.894 0 0 1-1.17 2.464c-.27.453-.517.845-.744 1.178a5.663 5.663 0 0 1-.568.733 3.968 3.968 0 0 1-.416.378c-.124.095-.218.135-.284.12a8.002 8.002 0 0 1-.186-.043.728.728 0 0 1-.246-.269 1.203 1.203 0 0 1-.125-.427 4.716 4.716 0 0 1-.039-.443 9.28 9.28 0 0 1 .006-.526c.007-.226.01-.38.01-.46 0-.277.006-.579.017-.903l.027-.772c.008-.19.011-.391.011-.603 0-.211-.013-.377-.038-.498a1.706 1.706 0 0 0-.114-.35.59.59 0 0 0-.225-.263 1.262 1.262 0 0 0-.366-.148c-.386-.088-.878-.135-1.476-.142-1.356-.015-2.227.073-2.613.262a1.47 1.47 0 0 0-.416.329c-.13.16-.15.248-.054.263.437.065.747.222.929.47l.066.132c.05.095.102.263.153.504.05.24.084.507.098.799.036.533.036.99 0 1.369-.036.38-.07.675-.104.887a1.778 1.778 0 0 1-.147.515 2.25 2.25 0 0 1-.132.24.188.188 0 0 1-.054.055.818.818 0 0 1-.296.055c-.102 0-.226-.051-.371-.153a2.625 2.625 0 0 1-.454-.422 5.636 5.636 0 0 1-.53-.75 13.01 13.01 0 0 1-.613-1.139l-.175-.318c-.109-.204-.258-.502-.448-.892s-.357-.769-.503-1.134a.722.722 0 0 0-.262-.35l-.055-.033a.748.748 0 0 0-.175-.093 1.159 1.159 0 0 0-.251-.071l-2.996.022c-.306 0-.514.07-.623.208l-.044.066a.355.355 0 0 0-.033.175c0 .08.022.179.066.295.437 1.03.913 2.023 1.426 2.98.514.955.96 1.726 1.34 2.31a27.08 27.08 0 0 0 1.159 1.653c.393.518.654.85.781.997.128.146.228.255.301.328l.274.263c.175.175.431.385.77.63.34.244.715.485 1.127.722.412.238.89.431 1.437.58.547.15 1.08.21 1.597.182h1.257c.255-.023.448-.103.58-.241l.043-.055a.727.727 0 0 0 .082-.203c.026-.09.038-.191.038-.3a3.614 3.614 0 0 1 .071-.85c.055-.251.117-.441.186-.569a1.4 1.4 0 0 1 .422-.482.713.713 0 0 1 .087-.038c.175-.058.38-.002.618.17.237.172.459.383.667.635.208.252.457.535.749.849.291.314.546.547.765.7l.219.132c.146.088.335.168.568.241.233.073.437.091.613.055l2.799-.044c.276 0 .492-.046.645-.137.153-.091.244-.192.273-.301a.85.85 0 0 0 .006-.373 1.376 1.376 0 0 0-.077-.29z"/></svg></div><br />
						<div style="display:inline-block;background-color:#e7f7ff;color:#8861b2;text-align: center;height: 40px;border-radius: 100px; width:120px; line-height: 40px;font-weight: 700;">VKONTAKTE</div>
					</a>
				</div>
				<? } ?>
			</div>
			
			<div class="wrap-our-contact">			
				<div class="left-column">
					<div>
						<h3 class="title"><?php echo $text_address; ?></h3>
						<p><?php echo $store; ?></p> 
						<?php if ($this->config->get('config_store_id') == 0) {?>
						<?php } else { ?>
							<?php echo $address; ?>
						<?php } ?>
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
			<?php if ($this->config->get('config_store_id') == 0) {?>
				<style>
					.notice_wrap .text{
						font-size: 18px;
						display: block;
						background: #e0665f;
						padding: 25px;
						color: #fff;
						font-weight: 500;
						text-align: center;
						margin-bottom: 25px;
					}
					.notice_wrap .text a{
						color: #fff;
						text-decoration: underline;
					}
					.notice_wrap img{
						max-width: 1000px;
						display: block;
						margin: auto;
						width: 100%;
					}
				</style>
				<div class="notice_wrap">
					<div class="content">
						<p class="text">
							Уважаемые клиенты, обращаем Ваше внимание  - участились мошеннические действия. На некоторых сайтах указаны реквизиты похожие на реквизиты нашей компании, после получения предоплаты мошенники обрывают контакты, заказы не выполняют. Будьте бдительны и осторожны <a href="https://kitchen-profi.ru/">https://kitchen-profi.ru/</a> наш официальный сайт, за действия мошенников ответственности не несем.
						</p>
						<img src="/catalog/view/theme/kpkz/img/notification_message.jpg" alt="Уважаемые клиенты">
					</div>
				</div>
			<?php } ?>
			<?php echo $content_bottom; ?>
		</div>
	</section>
<?php echo $footer; ?>