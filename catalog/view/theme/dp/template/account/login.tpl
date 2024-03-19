<?php echo $header; ?>

<div hidden="hidden">
	<?php echo $column_left; ?>
</div>
<?php echo $column_right; ?>

<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<section id="content">
	<div class="wrap">
		<?php echo $content_top; ?>
		<div class="login-content">
			<style>
				.content {font-size: 16px;padding: 0;max-width: 815px;margin: auto;display: flex;column-gap: 30px;margin-bottom: 20px;}
				.content .left-wrap{width: 60%;}
				.content a{font-size: 16px; text-decoration: none;}
				.content a:hover{text-decoration: underline;}
				.content b {padding-bottom: 5px; display: inline-block;}
				.content .login-entry-field {padding: 10px; font-size:large; width: 100%;}
				.checkbox{vertical-align:top;margin:0 3px 0 0;width:17px;height:17px}
				.checkbox + label{cursor:pointer}
				.checkbox:not(checked){position:absolute;opacity:0}
				.checkbox:not(checked) + label{position:relative;padding:0 0 0 40px}
				.checkbox:not(checked) + label:before{content:'';position:absolute;left:0;top:0;width:35px;height:17px;border-radius:13px;background:#CDD1DA;box-shadow:inset 0 2px 3px rgba(0,0,0,.2)}
				.checkbox:not(checked) + label:after{content:'';position:absolute;top:2px;left:2px;width:13px;height:13px;border-radius:10px;background:#FFF;box-shadow:0 2px 5px rgba(0,0,0,.3);transition:all .2s}
				.checkbox:checked + label:before{background:#9FD468}
				.checkbox:checked + label:after{left:19px}
				.checkbox:focus + label:before{box-shadow:0 0 0 3px rgba(255,255,0,.5)}
				.forgotten-text {float: right;}
				.btn-group-register{display: flex;flex-direction: column;/*flex-direction: row;justify-content: space-between;*/margin-top: 20px;margin-bottom: 20px;width: 35%;}
				.btn-group-register button{width: 100%;display: flex;flex-direction: row;align-content: center;align-items: center;margin-bottom: 10px;padding: 15px 8px;background: #fff !important;background-color: #fff;
				font-size: 18px;
				color: #0385c1 !important;
				text-shadow: none;
				transition: .15s ease-in-out;
				outline: none !important;
				font-weight: 500;
				letter-spacing: .21px;
				height: 54px;
				border: 1px solid #51a881;
				}
				.btn-group-register button span{
				font-size: 17px !important;
				}
				.btn-group-register button:hover{
				box-shadow: 1px 1px 5px #ccc;
				}
				.btn-group-register button .btn-img {
				background-color: #fff;
				-webkit-border-radius: 1px;
				border-radius: 1px;
				padding: 15px;
				display: flex;
				margin-right: 10px;
				max-height: 52px;
				}
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
				@media (max-width: 790px) {
					.content,
					.btn-group-register{
	        	 	flex-direction: column;
					}
					.btn-group-register button{
	        	 	width: 100%;
					}

					.forgotten-text {float: none;white-space:nowrap}
	
					.btn-group-register,
					.content .left-wrap {
					    width: 100%;
					}
				}
				@media screen and (max-width: 560px) {
					.content .login-entry-field{
						font-size: 12px;
					}					
				}
			</style>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
				<div class="content">
					<div class="left-wrap">
						<b><?php echo $entry_email; ?></b><br />
						
							<input type="text" class="login-entry-field field" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $placeholder_email; ?>"/>
						
						<br />
						<br />

						<b><?php echo $entry_password; ?></b><br />
						<div class="login-entry">
							<input type="password" class="login-entry-field field" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $placeholder_password; ?>" />
						</div>

						<br />  <br />
						<input id = "checkbox" class = "checkbox" type="checkbox" name="autologin" value="0" checked="checked" />
						<label for ="checkbox" style="display: inline-block; margin-bottom: 5px;"><?php echo $text_save_me; ?></label>

						<?php if ($this->config->get('config_restore_password_enable_sms') || $this->config->get('config_restore_password_enable_email')) { ?>
							<a class="forgotten-text" href="<?php echo $forgotten; ?>" style="text-decoration: underline;"><?php echo $text_forgotten; ?></a>
						<?php } ?>


						<br />
						<span><?php echo $text_register; ?><br />
						<br />
						<?php if ($success) { ?>
							<div class="success" style="color: green; font-weight: 500;"><?php echo $success; ?></div>
						<?php } ?>
						<?php if ($error_warning) { ?>
							<div class="warning" style="color: red; font-weight: 500;"><?php echo $error_warning; ?></div>			
						<?php } ?>
						<input type="submit" value="<?php echo $button_login; ?>" class="btn btn-acaunt" />
					</div>		
					
					
					
					<div class="btn-group-register">
						<?php if ($this->config->get('social_auth_google_app_id')) { ?>
						<button type="button" onclick="social_auth.googleplus(this)" data-loading-text="Loading" class="btn btn-primary btn-google">
							<div class="btn-img" style="padding: 0;margin-right: 13px;">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" width="46px" height="46px" viewBox="0 0 46 46" version="1.1">
									<defs>
										<filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="filter-1">
											<feOffset dx="0" dy="1" in="SourceAlpha" result="shadowOffsetOuter1"/>
											<feGaussianBlur stdDeviation="0.5" in="shadowOffsetOuter1" result="shadowBlurOuter1"/>
											<feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.168 0" in="shadowBlurOuter1" type="matrix" result="shadowMatrixOuter1"/>
											<feOffset dx="0" dy="0" in="SourceAlpha" result="shadowOffsetOuter2"/>
											<feGaussianBlur stdDeviation="0.5" in="shadowOffsetOuter2" result="shadowBlurOuter2"/>
											<feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.084 0" in="shadowBlurOuter2" type="matrix" result="shadowMatrixOuter2"/>
											<feMerge>
												<feMergeNode in="shadowMatrixOuter1"/>
												<feMergeNode in="shadowMatrixOuter2"/>
												<feMergeNode in="SourceGraphic"/>
											</feMerge>
										</filter>
										<rect id="path-2" x="0" y="0" width="40" height="40" rx="2"/>
									</defs>
									<g id="Google-Button" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
										<g id="9-PATCH" sketch:type="MSArtboardGroup" transform="translate(-608.000000, -160.000000)"/>
										<g id="btn_google_light_normal" sketch:type="MSArtboardGroup" transform="translate(-1.000000, -1.000000)">
											
											<g id="logo_googleg_48dp" sketch:type="MSLayerGroup" transform="translate(15.000000, 15.000000)">
												<path d="M17.64,9.20454545 C17.64,8.56636364 17.5827273,7.95272727 17.4763636,7.36363636 L9,7.36363636 L9,10.845 L13.8436364,10.845 C13.635,11.97 13.0009091,12.9231818 12.0477273,13.5613636 L12.0477273,15.8195455 L14.9563636,15.8195455 C16.6581818,14.2527273 17.64,11.9454545 17.64,9.20454545 L17.64,9.20454545 Z" id="Shape" fill="#4285F4" sketch:type="MSShapeGroup"/>
												<path d="M9,18 C11.43,18 13.4672727,17.1940909 14.9563636,15.8195455 L12.0477273,13.5613636 C11.2418182,14.1013636 10.2109091,14.4204545 9,14.4204545 C6.65590909,14.4204545 4.67181818,12.8372727 3.96409091,10.71 L0.957272727,10.71 L0.957272727,13.0418182 C2.43818182,15.9831818 5.48181818,18 9,18 L9,18 Z" id="Shape" fill="#34A853" sketch:type="MSShapeGroup"/>
												<path d="M3.96409091,10.71 C3.78409091,10.17 3.68181818,9.59318182 3.68181818,9 C3.68181818,8.40681818 3.78409091,7.83 3.96409091,7.29 L3.96409091,4.95818182 L0.957272727,4.95818182 C0.347727273,6.17318182 0,7.54772727 0,9 C0,10.4522727 0.347727273,11.8268182 0.957272727,13.0418182 L3.96409091,10.71 L3.96409091,10.71 Z" id="Shape" fill="#FBBC05" sketch:type="MSShapeGroup"/>
												<path d="M9,3.57954545 C10.3213636,3.57954545 11.5077273,4.03363636 12.4404545,4.92545455 L15.0218182,2.34409091 C13.4631818,0.891818182 11.4259091,0 9,0 C5.48181818,0 2.43818182,2.01681818 0.957272727,4.95818182 L3.96409091,7.29 C4.67181818,5.16272727 6.65590909,3.57954545 9,3.57954545 L9,3.57954545 Z" id="Shape" fill="#EA4335" sketch:type="MSShapeGroup"/>
												<path d="M0,0 L18,0 L18,18 L0,18 L0,0 Z" id="Shape" sketch:type="MSShapeGroup"/>
											</g>
											<g id="handles_square" sketch:type="MSLayerGroup"/>
										</g>
									</g>
								</svg>           
							</div>
							<span style="color: #000000; opacity: 0.54; font-size: 14px;">Google</span>            
						</button>
						<? } ?>
						
						
						<?php if ($this->config->get('social_auth_facebook_app_id')) { ?>
						<button type="button" onclick="social_auth.facebook(this)" data-loading-text="Loading" class="btn btn-primary btn-facebook">
							<div class="btn-img"><i class="fab fa-facebook-f"></i></div> 
							<span style="color: #000000; opacity: 0.54; font-size: 14px;">Facebook</span>  
						</button>	
						<?php } ?>
					</div>
					<?php if ($redirect) { ?>
						<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
					<?php } ?>
				</div>
				
			</form>
			<!--/div-->
		</div>
		<?php echo $content_bottom; ?>
	</div>
</section>
<script type="text/javascript"><!--
	$('#login input').keydown(function(e) {
		if (e.keyCode == 13) {
			$('#login').submit();
		}
	});
//--></script> 
<?php echo $footer; ?>