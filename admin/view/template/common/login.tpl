<?php echo $header; ?>

<link rel="stylesheet" type="text/css" href="/catalog/view/theme/kp/css/fontawesome/css/all.css">
<style type="text/css">
		.login-entry{
			position: relative;
		}
	.login-entry .password-toggle{
		position: absolute;
		right: 10px;
		top: 0;
		bottom: 0;
		margin: auto;
		height: 16px;
		cursor: pointer;

	}
	.form-row{
		display: flex;
		width: 100%;
		margin-bottom: 15px;
	}
	.form-row input{
		width: 100%;
		height: 40px;
		font-size: 17px;
	}
	.fa.fa-android{
		font: normal normal normal 14px/1 FontAwesome !important;
		font-weight: 400 !important;
	}
	@media screen and (max-width: 560px){
	#header .div1,
	#content{
	max-width: 100% !important;
	min-width: 1px !important;		
	}
	
	}
	@media screen and (max-width: 480px){
	#content .box_bg{
	width: auto !important;
	/*height:600px;*/
	}
	}
	
</style>
<div id="content" style="height: auto;min-height: 100%;">
	<div class="box box_bg" style="width: 400px; height: auto; margin-top: 40px; margin-left: auto; margin-right: auto;">   
		<div class="content" style="min-height: 750px; overflow: hidden;">
			<?php if ($success) { ?>
				<div class="success"><?php echo $success; ?></div>
			<?php } ?>
			<?php if (false && $error_warning) { ?>
				<div class="warning"><?php echo $error_warning; ?></div>
			<?php } ?>
			
			<?php if (!empty($connection_error_message)) { ?>
				<div class="warning">Внимание! Нет подключения к серверу авторизации! <?php echo $connection_error_message; ?></div>
			<?php } ?>
			
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table style="width: 100%; max-width:100%;">
					<tr>
						<td style="text-align: center; width:100%;"><img style="max-width:100%;" src="view/image/<? echo FILE_LOGO; ?>" alt="<?php echo $text_login; ?>" /></td>
					</tr>
					
					<tr>
						<td style="text-align: center;"><br />
							<div class="form-row">
								<input type="text" name="username" value="<?php echo $username; ?>" style="margin-top: 4px;" />
							</div>
							<div class="login-entry form-row"> 
								<input type="password" name="password" value="<?php echo $password; ?>" style="margin-top: 4px;" />
								<span class="password-toggle" onclick="passwordToggle(this);"><i class="fas fa-eye"></i></span>
							</div>						
							
							<?php if ($forgotten) { ?>
								<br />
								<a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td style="text-align: center;">
							<a style="width:100%; padding:20px 0;border-width: 1px;font-size: 17px;" onclick="" id="login_button" class="button"><?php echo $button_login; ?></a>
						</td>
					</tr>
					<script>											
						<?php if ($username && $password) { ?>
							var autologin = window.setTimeout(function(){	
								$('#form').submit();						
							}, 3000);
							
							var tmr = 4;
							
							$(document).ready(function(){	

								var counter = setInterval(timer, 1000);												
								$('#login_button').click(function(){ clearInterval(counter); clearTimeout(autologin); $('#login_button').html('Войти'); $('#login_button').click(function(){ $('#form').submit(); }); });
							});
							
							function timer(){
								$('#login_button').html('<i class="fas fa-spinner fa-spin"></i> Автовход через ' + window.tmr + ' секунд, остановить');
								console.log(window.tmr);
								window.tmr = window.tmr - 1;
							}
						<?php } else { ?>
							$(document).ready(function(){																			
								$('#login_button').click(function(){  $('#form').submit(); });
							});
						<?php } ?>
						
					</script>
					<?php if ($this->config->get('config_android_application_link')) { ?>
					<tr>
						<td style="text-align: center; width:100%; padding-top:20px">
							<i class="fa fa-android" aria-hidden="true" style="color:#02760e;"></i> <a style="width:90%; padding:5px 7px; color:#02760e;" href="<?php echo $this->config->get('config_android_application_link');?>">установить приложение KP ADMIN для Android</a>
						</td>
					</tr>
					<?php } ?>
					
					
				</table>
				<?php if ($redirect) { ?>
					<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
				<?php } ?>
			</form>
		</div>
	</div>
	
	<? /*
		<div style="width: 400px;color:#000; font-weight:400; color:red; padding-left:20px; width:700px;  margin-left: auto; margin-right: auto;">Внимание! В связи с недавними неполадками на центральном сервере компании, возможны сбои с входом в админчасть. Если у вас не получается войти с вашим паролем, обратитесь к Николаю Рабченюку, skype: mykola_mykolayovuch, либо Чулкову Егору skype: yegor-10. <br />Так же, если вы помните свой старый пароль, вы можете изменить его по <a href="http://office.ims-group.de/products/files/doceditor.aspx?fileid=121" target="_blank">этой инструкции</a><br /><br /> В онлайне меня до понедельника большей частью не будет, связь по телефону: +380632708881 / +380960350063, Виктор.<br /><br />Всем хороших выходных:) </div>
	*/ ?>
	
</div>
<script type="text/javascript"><!--
	function passwordToggle(elem){

		let passwdInput = elem.closest('.form-row').querySelector('input');
		let eye = elem.querySelector('i');
		if(passwdInput.type === "password"){
			passwdInput.setAttribute('type','text');
			eye.classList.remove('fa-eye');
			eye.classList.add('fa-eye-slash');
		}else{
			passwdInput.setAttribute('type','password');
			eye.classList.remove('fa-eye-slash');
			eye.classList.add('fa-eye');
		}
	}
	$('#form input').keydown(function(e) {
		if (e.keyCode == 13) {
			$('#form').submit();
		}
	});
	
//--></script> 

<?php echo $footer; ?>		