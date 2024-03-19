<?php echo $header; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<style type="text/css">
	#content-contact h3.title{
		margin-top: 0 !important;
		padding-top: 0 !important;
	}
	@media (min-width:561px) and (max-width: 1200px){
		#content-contact .social-btn-contact{
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 10px;
		}
	}
	
</style>
<section id="content-contact">
	<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
	<div class="wrap">
		<?php echo $content_top; ?>
	
    	<div class="contact-info">
				
				
				
				
				
				<div class="wrap-our-contact">			
					<div class="left-column">
						<div class="desk-contact">
							<?php echo $text_messenger_contact; ?>
						</div>
						<div class="social-btn-contact">
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
							<?php } ?>
						</div>
						
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
								<div class="form-group" style="padding-top: 10px;">
									<?php echo $captcha; ?>
									<? /*	
										<b><?php echo $entry_captcha; ?></b><br />
										<input type="text" name="captcha" value="<?php echo $captcha; ?>" />
										<br />
										<img src="index.php?route=information/contact/captcha" alt="" />
									*/ ?>
									<?php if ($error_captcha) { ?>
										<span class="error"><?php echo $error_captcha; ?></span>
									<?php } ?>
								</div>
								<div class="form-group-btn">
									<input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-default" />
								</div>
							</div>
						</form>
					</div>
					
				</div>
				<!-- <script>
					if(window.location.toString() != 'https://kitchen-profi.ru/contact'){
						
						document.getElementById("contact-address").style.display= 'none';
					}
					if(window.location.toString() == 'https://kitchen-profi.com.ua/contact'){
						
						document.getElementById("contact-address").style.display= 'block';
					}
				</script> -->
		</div>
	<!-- 
		<div class="contact-info col-md-12">
		<div class="content">
        <h3>Оптовый отдел</h3>
        <div class="row">
		<div class="col-md-12">
		<?php if ($opt_telephone) { ?>
			<b><?php echo $text_telephone; ?></b><br />
			<?php echo $opt_telephone; ?><br />
			<?php echo $opt_telephone2; ?><br />
		<?php } ?>
		</div>
		<div class="col-md-12">
		<?php if ($opt_email) { ?>
			<b>E-mail</b><br />
			<?php echo $opt_email; ?><br /> 
			<br />
		<?php } ?>
		</div>
        </div>
        </div>
	</div> -->
	
		<?php echo $content_bottom; ?>
	</div>
</section>
<?php echo $footer; ?>