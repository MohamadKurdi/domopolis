<?php echo $header; ?><?php echo $column_right; ?>
<div id="content" class="account_wrap">
	
	
	<style>
		.list-transactions .text-danger, .list-transactions .text-success{font-size:100%}
		small {font-size:80%;}
		.unsubscribe_wrap input{
		text-align: left;
		text-indent: 25px;
		width:100%;
		max-width:  500px;
		margin-bottom: 15px;
		}
		
		.unsubscribe_wrap .simple-address{margin-bottom:25px;}
		.unsubscribe_wrap{margin:0 auto; text-align:center;}
		.unsubscribe_wrap form{max-width:500px; display:inline-block;}
		
		.unsubscribe-text{margin-bottom:25px;}
		
		.button.float-right{float:right;}
		@media screen  and (max-width: 560px){
			.unsubscribe_wrap input {
			    text-indent: 0;
			    font-size: 13px;
			}
			.simple-address label, .newsletter-content label {
			    padding-left: 32px;
			    font-size: 14px;
			    text-align: left;
			    align-items: center;
			    justify-content: center;
			}
			.simple-address label::before {
			    bottom: 0;
			    margin: auto;
			}

			.simple-address label::after{
			    top: 0;
			    bottom: 0;
			    margin: auto;
			}
		}
	</style>
	
	<?php echo $content_top; ?>
    <?php include(dirname(__FILE__).'/../structured/breadcrumbs.tpl'); ?>
    <div class="wrap">
        <div class="account_content unsubscribe_wrap">
			
			<div class="unsubscribe-text">
				<p><?php echo $text_unsubscribe_text_1; ?></p>
				<p><?php echo $text_unsubscribe_text_2;?></p>
			</div>
			
			<form action="<?php echo $action; ?>" method="POST">
				
				<input type="text" class="field" name="email" placeholder="<?php echo $text_enter_your_email; ?>" value="<?php echo $email;?>" />
				
				<?php if (!empty($error_email)) { ?>
					<span class="text-danger"><?php echo $text_error_email; ?></span>
				<?php } ?>
				
				<?php if (!empty($error_email_not_exists)) { ?>
					<span class="text-danger"><?php echo $text_error_email_not_exists; ?></span>
				<?php } ?>
				
				<?php if ($success) { ?>
					<span class="text-success"><?php echo $text_success; ?></span>
					
					<script>
						window.dataLayer = window.dataLayer || [];	
						
						dataLayer.push({
							event: 'Users',
							eventCategory: 'Email',
							eventAction: 'Unsubscribe',
							eventLabel: '<?php echo $email;?>'
						});
					</script>
					
				<?php } ?>
				
				<div class="simple-address">
					<input type="checkbox" name="unsubscribe_check" id="unsubscribe_check" value="1" />
					<label for="unsubscribe_check"><?php echo $text_label_check; ?></label>
					
					<?php if (!empty($error_unsubscribe_check)) { ?>
						<span class="text-danger"><?php echo $text_error_unsubscribe_check; ?></span>
					<?php } ?>
				</div>
				<div class="unsubscribe-button">
					<button type="submit" class="button btn btn-acaunt-none float-right"><?php echo $text_unsubscribe_button; ?></button>
				</div>
			</form>
		</div>
		<?php echo $content_bottom; ?>
	</div>
</div>
<?php echo $footer; ?>