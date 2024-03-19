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
							<? } ?>
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