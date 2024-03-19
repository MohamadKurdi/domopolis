<?php echo $header; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<style>
	.contact-info-wrap .right-column .superform .content textarea{
		max-height: 100px !important;
		resize: vertical !important;
	}
	#content-contact h3.title{
		padding: 0 !important;
	}
</style>
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
	<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/breadcrumbs.tpl')); ?>
	<?php if ($hb_snippets_local_enable == 'y' && !empty($hb_snippets_local_snippet)){echo html_entity_decode($hb_snippets_local_snippet);} ?>
	<div class="wrap">
		<?php echo $content_top; ?>
		
    	<div class="contact-info-wrap">
			
			<div class="left-column">
				<div class="text_messenger_contact">
					<!-- <?php echo $text_messenger_contact_b2b; ?> -->
					<b>DOMOPOLIS</b> співпрацює з понад 50 європейськими брендами, що дозволяє надавати вигідні умови співпраці для оптових покупців. <br>Для отримання більш детальної інформації, будь ласка, заповніть наступну форму та залиште свої контактні дані<div class="br"></div>
Наш спеціаліст зв'яжеться з вами найближчим часом, щоб узгодити деталі співпраці.
				</div>

			</div>
			<div class="right-column">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data"> 
					<div class="superform">
						<h3 class="title"><?php echo $text_contact; ?></h3>
						<div class="two-column">
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
						</div>
						
						<div class="form-group content">
							<label><?php echo $entry_enquiry; ?></label>
							<textarea name="enquiry" cols="40" rows="10" class="form-control"><?php echo $enquiry; ?></textarea>
							<?php if ($error_enquiry) { ?>
								<span class="error"><?php echo $error_enquiry; ?></span>
							<?php } ?>
						</div>
						<?php if ($this->config->get('config_google_recaptcha_contact_enable')) { ?>
							<div class="form-group">
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
							<span class="text">Натискаючи кнопку надіслати ви погоджуєтесь з нашими Правилами сервісу та Політикою конфіденційності</span>
						</div>
					</div>
				</form>
			</div>
			
		</div>

		<?php echo $content_bottom; ?>
	</div>	
</section>
<?php echo $footer; ?>