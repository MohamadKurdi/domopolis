<style>
	#popup_autorization. .overlay-popup-close {
	    position: unset;
	    background-image: none;
	    border: 0;
	}
	#popup_autorization.loading .content.main-wrap::before{
		content: '';
	    background: #fff;
	    position: absolute;
	    width: 100%;
	    height: 100%;
	    z-index: 2;
	    opacity: 0.8;
	}
	#popup_autorization.loading  .content.main-wrap::after{
		content: '';
	    background-image: url(/catalog/view/theme/dp/img/Spinners.png);
	    position: absolute;
	    right: 0;
	    bottom: 0;
	    top: 0;
	    left: 0;
	    background-position: center;
	    background-repeat: no-repeat;
	}
	#popup_autorization .main-wrap{
		background: #fff;
		display: flex;
		gap: 0;
		min-height: 500px;
		border-radius: 15px;
	    overflow: hidden;
	    box-shadow: 2px 3px 19px #a5a4a4;
	    position: relative;
	}
	#popup_autorization .main-wrap .left-wrap .logo{
		display: flex;
	    height: 40px;
	    margin: auto;
	}

	#popup_autorization .main-wrap .right-wrap{
		width: 62%;
	    -webkit-box-flex: 1;
	    -ms-flex: 1;
	    flex: 1;
	    display: flex;
	    flex-direction: column;
	    justify-content: center;
	    padding: 0 40px;	
	}
	#popup_autorization .main-wrap .left-wrap{
	    width: 38%;
	    -webkit-box-flex: 0;
	    -ms-flex: 0 0 auto;
	    flex: 0 0 auto;
	    background: #97B63C;
	    padding: 40px 40px 0 40px;
	    display: -webkit-box;
	    display: -ms-flexbox;
	    display: flex;
	    -webkit-box-align: center;
	    -ms-flex-align: center;
	    align-items: center;
	    flex-direction: column;
	}
	#popup_autorization .main-wrap .right-wrap h3{
		margin-bottom: 20px;
	}
	#popup_autorization .main-wrap  .link{
		display: flex;
	    align-items: center;
	    justify-content: center;
	    border: 1px solid #97b63c;
	    border-radius: 10px;
	    height: 40px;
	    font-size: 17px;
	    font-weight: 500;
	    gap: 10px;
	    color: #ffffff;
	    background: #97b63c;
	}
	#popup_autorization .main-wrap .right-wrap .group_login_header .title{
		position: relative;
	    display: flex;
	    align-items: center;
	    justify-content: center;
	    margin-top: 25px;
	    margin-bottom: 20px;
	}
	#popup_autorization .main-wrap .right-wrap .group_login_header .title span{
		font-size: 14px;
	    color: #b3b3b3;
	    background: #fff;
	    position: relative;
	    z-index: 1;
	    padding: 1px 15px;
	    margin: 0;
	    width: auto;
	}
	#popup_autorization .main-wrap .right-wrap .group_login_header .title:after {
	    content: '';
	    position: absolute;
	    top: 50%;
	    left: 0;
	    right: 0;
	    height: 1px;
	    background: #b3b3b3;
	}
	#popup_autorization .phone-wrap span{
		font-size: 16px;
	    font-weight: 500;
	    margin-bottom: 10px;
	}
	#popup_autorization .phone-wrap input{
		padding: 10px;
	    font-size: large;
	    width: 100%;
	    height: 40px;
	    font-size: 16px;
	}
	#popup_autorization .phone-wrap .alert{
		padding: 0;
	}
	#popup_autorization #button-resend-code,
	#popup_autorization #button-change-telephone{
		font-size: 14px;
	    font-weight: 500;
	    margin-bottom: 10px;
	    text-decoration: underline;
	    cursor: pointer;
	}
	#popup_autorization .old-auth{
		text-decoration: underline;
	    text-align: center;
	    margin-top: 10px;
	    font-size: 13px;
	}
	@media screen and (max-width: 560px) {
		#popup_autorization .main-wrap{
			min-height: auto;
		}
		#popup_autorization .main-wrap .left-wrap{
			display: none;
		}
		#popup_autorization .main-wrap .right-wrap {
		    width: 100%;
	        padding: 20px;
		}
	}
</style>
<script>
	$('#login_phone').inputmask("<?php echo $this->config->get('config_phonemask'); ?>",{ 
		"clearIncomplete": true,
	});
</script>
<div class="content main-wrap">
		<div class="overlay-popup-close">
			<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="https://www.w3.org/2000/svg">
				<path d="M13 0.999939L1 12.9999M1 0.999939L13 12.9999" stroke="#888F97" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"></path>
			</svg>
		</div>
		<div class="left-wrap">
			<div class="logo">
				<svg id="Layer_1" data-name="Layer 1" xmlns="https://www.w3.org/2000/svg" width="100%" viewBox="0 0 176 60"><defs><style>.cls-1{fill:#020608;}.cls-2{fill:#6a979b;}.cls-3{fill:#e7b650;}.cls-4{fill:#d8b6cb;}.cls-5{fill:#d26244;}.cls-6{fill:#fff;}</style></defs><title>Domopolis</title><path class="cls-1" d="M66.62,16.81H77.81c7.47,0,11.6,3.57,11.6,10s-4.13,9.94-11.6,9.94H66.62V16.81ZM77.81,35.22c6.37,0,9.94-3,9.94-8.44s-3.57-8.44-9.94-8.44H68.29V35.22Z"></path><path class="cls-1" d="M91.26,26.72C91.26,20.37,96.69,16,104.7,16s13.45,4.33,13.45,10.68-5.4,10.64-13.45,10.64S91.26,33.06,91.26,26.72Zm25.22,0c0-5.44-4.75-9.15-11.78-9.15s-11.77,3.71-11.77,9.15,4.75,9.14,11.77,9.14,11.78-3.71,11.78-9.14Z"></path><path class="cls-1" d="M120.28,16.82h2.58L133.5,35.11h0l10.65-18.29h2.57V36.73h-1.67V18.58H145L134.46,36.73h-1.92L122,18.58H122V36.73h-1.67V16.82Z"></path><path class="cls-1" d="M148.89,26.79c0-6.34,5.44-10.67,13.45-10.67s13.45,4.33,13.45,10.67-5.41,10.64-13.45,10.64S148.89,33.13,148.89,26.79Zm25.23,0c0-5.44-4.75-9.15-11.78-9.15s-11.78,3.71-11.78,9.15,4.76,9.14,11.78,9.14S174.12,32.22,174.12,26.79Z"></path><path class="cls-1" d="M66.62,39.46h13.9c4.45,0,7,2.38,7,6.06s-2.58,6-7,6H68.29v7.82H66.62V39.46ZM80.35,50c3.52,0,5.5-1.75,5.5-4.5s-2-4.53-5.5-4.53H68.29v9Z"></path><path class="cls-1" d="M89.33,49.28c0-6.35,5.44-10.68,13.45-10.68s13.45,4.33,13.45,10.68-5.41,10.64-13.45,10.64-13.45-4.3-13.45-10.64Zm25.23,0c0-5.44-4.76-9.15-11.78-9.15S91,43.84,91,49.28s4.76,9.14,11.78,9.14S114.56,54.71,114.56,49.28Z"></path><path class="cls-1" d="M118.06,39.45h1.67v18.4h13.56v1.5H118.06V39.45Z"></path><path class="cls-1" d="M135.12,39.45h1.67v19.9h-1.67V39.45Z"></path><path class="cls-1" d="M138.62,53.16l1.42-.85c.93,3.65,5.24,6.08,10.65,6.08,4.72,0,7.81-1.78,7.81-4.35,0-2-1.81-3.23-6.06-3.91l-5.69-.94c-4.5-.71-6.43-2.09-6.43-4.81,0-3.54,3.43-5.78,8.44-5.78,4.39,0,8.1,1.87,9.54,4.65l-1.33.93c-1.27-2.41-4.44-4-8.21-4-4,0-6.77,1.67-6.77,4.16,0,1.79,1.5,2.81,5.07,3.34l5.61.91c5.24.82,7.5,2.44,7.5,5.32,0,3.68-3.79,6.06-9.54,6.06-6,0-10.73-2.71-12-6.76Z"></path><path class="cls-2" d="M26.82.46A26.31,26.31,0,0,0,.51,26.77V59.15H26.82Z"></path><path class="cls-3" d="M26.82.46h0V59.15H53.14V32.86h0V26.77A26.31,26.31,0,0,0,26.82.46Z"></path><rect class="cls-4" x="26.82" y="37.29" width="26.29" height="21.86"></rect><rect class="cls-5" x="0.52" y="47.99" width="26.29" height="11.16"></rect><rect class="cls-6" x="0.21" y="47.31" width="26.6" height="0.92"></rect><rect class="cls-6" x="13.2" y="3.42" width="0.92" height="21.54"></rect><polygon class="cls-2" points="13.66 21.07 8.7 26.77 18.63 26.77 13.66 21.07"></polygon><path class="cls-6" d="M19.64,27.23h-12l6-6.86,6,6.86Zm-9.93-.92h7.9l-3.95-4.54Z"></path><rect class="cls-5" x="10.42" y="41.25" width="6.52" height="6.52"></rect><path class="cls-6" d="M17.4,48.23H10V40.79H17.4Zm-6.52-.92h5.6V41.72h-5.6Z"></path><rect class="cls-6" x="26.81" y="36.83" width="26.55" height="0.92"></rect><rect class="cls-6" x="26.34" y="0.08" width="0.92" height="59.44"></rect><polygon class="cls-4" points="43.34 37.29 36.72 37.29 37.85 24.39 42.19 24.39 43.34 37.29"></polygon><path class="cls-6" d="M43.85,37.75H36.21l1.22-13.82h5.18Zm-6.63-.92h5.62l-1.07-12H38.28l-1.06,12Z"></path><path class="cls-6" d="M16.94,46.85v-.92a1.81,1.81,0,1,0,0-3.62v-.92a2.73,2.73,0,0,1,0,5.46Z"></path><path class="cls-6" d="M39.59,27.75l-.69-.61a6.14,6.14,0,0,0-.29-8.89l.66-.65A7,7,0,0,1,39.59,27.75Z"></path><path class="cls-6" d="M39.41,18.41l-.6-.09a5.94,5.94,0,0,1-3.07-1.52h0a5.92,5.92,0,0,1-1.52-3.07l-.09-.61.61.09a6,6,0,0,1,3.07,1.52,5.94,5.94,0,0,1,1.52,3.07l.08.61Zm-4.1-4.11a4.65,4.65,0,0,0,1.08,1.84h0a4.68,4.68,0,0,0,1.84,1.09,4.75,4.75,0,0,0-1.08-1.85,4.77,4.77,0,0,0-1.84-1.08Z"></path><path class="cls-6" d="M41.08,23.06l-.25-.56a6,6,0,0,1-.37-3.41,6,6,0,0,1,1.76-2.93l.47-.4.26.56a5.9,5.9,0,0,1,.36,3.4,6,6,0,0,1-1.76,2.94l-.47.4Zm1.25-5.67a4.77,4.77,0,0,0-1,1.9,4.66,4.66,0,0,0,.08,2.14,4.77,4.77,0,0,0,1-1.9,4.66,4.66,0,0,0-.08-2.14Z"></path></svg>
			</div>
			<img src="/catalog/view/theme/dp/img/footer_pwa_intall_bg_1.png" class="bg" loading="lazy">
		</div>
		<div class="right-wrap">
			<h3>Вхід</h3>
			
			<div class="phone-wrap">
				<div id="step-1">	
					<span><?php echo $entry_telephone; ?></span>
					<input id="login_phone" type="text" class="login-entry-field field" name="telephone" placeholder="<?php echo $this->config->get('config_phonemask'); ?>" />											
					<div id="div-get-code-result" class="alert"></div>

					<div id="div-button-1">
						<input type="button" value="<?php echo $entry_get_code; ?>" class="btn btn-acaunt" id="button-get-code" />
					</div>
				</div>

				<script>
					function sendSuccess(text){						
						$('#div-entry-code').text(text);
						$('#step-2').show();
						$('#step-1').hide();

						<?php if ('config_otp_enable_sms') { ?>
							if ("OTPCredential" in window) {
								console.log("[PWA] WebOTP in window");

								navigator.credentials.get({
									otp: {transport:['sms']}
								}).then(otp => {
									console.log("[PWA] Success getting OTP: " + otp.code);
									$('input[name=code]').val(otp.code);
									$('#button-check-code').trigger('click');
								}).catch(err => {   
									console.error("[PWA] WebOTP error: ", err);
								});
							} else {
								console.error("[PWA] WebOTP not supported");
							}

							$('input[name=code]').on('input', () => {});
						<?php } ?>
					}

					function sendFail(text){
						$('#div-get-code-result').removeClass('alert-success, alert-danger').addClass('alert-danger').text(text);
					}

					$('#button-get-code').on('click', function(){
						$.ajax({
							url			: '<?php echo $send_code_action; ?>',
							type 		: 'POST',
							dataType	: 'json',
							data  		: {
								'telephone' 			: $('input[name=telephone]').val(),
								'g-recaptcha-response' 	: $('input[name=g-recaptcha-response-1]').val(),
							},
							beforeSend: function(){
								$('#button-get-code').attr('disabled', 'disabled');
								$('#popup_autorization').addClass('loading');
							},
							success: function(json){
								$('#popup_autorization').removeClass('loading');
								$('#button-get-code').removeAttr('disabled');
								if (json.success){
									sendSuccess(json.success);
								} else if (json.error){
									sendFail(json.error);		
								}
							},
							error: function(json){
								sendFail('<?php echo $error_code; ?>');	
								$('#button-get-code').removeAttr('disabled');		
							}
  						});
					});
				</script>

				<div id="step-2" style="display:none;">					
						<div id="div-entry-code-wrap"><span id="div-entry-code"></span></div>
						<input type="text" class="login-entry-field field" inputmode="numeric" autocomplete="one-time-code" name="code" placeholder="000000"/>
						<div id="div-check-code-result" class="alert"></div>						

						<div id="div-button-1">
							<input type="button" value="<?php echo $entry_login_now; ?>" class="btn btn-acaunt" id="button-check-code" />
						</div>

						<span id="button-change-telephone"><?php echo $entry_change_phone_number; ?></span>
				</div>	

				<script>		
					$('#button-change-telephone').on('click', function(){
						$('#step-1').show();
						$('#step-2').hide();
						$('#button-get-code').removeAttr('disabled');
						$('#div-get-code-result').removeClass('alert-success, alert-danger').text('');
					});

					function codeSuccess(){
						location.reload();
					}

					function codeFail(text){
						$('#div-check-code-result').removeClass('alert-success, alert-danger').addClass('alert-danger').text(text);
						reloadCaptcha(2);
					}

					$('#button-check-code').on('click', function(){
						$.ajax({
							url			: '<?php echo $validate_code_action; ?>',
							type 		: 'POST',
							dataType	: 'json',
							data  		: {
								'code' 					: $('input[name=code]').val(),
								'g-recaptcha-response' 	: $('input[name=g-recaptcha-response-2]').val(),
							},
							success: function(json){
								if (json.success){
									codeSuccess();	
								} else if (json.error){
									codeFail(json.error);			
								}
							},
							error: function(json){
								codeFail('<?php echo $error_code_validation; ?>');		
							}
  						});
					});
				</script>
			</div>

			<div class="group_login_header">
				<div class="title"><span><?php echo $text_or; ?></span></div>
				
				<div class="btn-group-register">

					<?php if ($this->config->get('social_auth_google_app_id')) { ?>
					<button type="button" onclick="social_auth.googleplus(this)" data-loading-text="Loading" class="btn btn-primary btn-google">
						<div class="btn-img" style="padding: 0;margin-right: 13px;">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" width="46px" height="37px" viewBox="0 0 46 46" version="1.1">
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
					 <? } ?>
				</div>
				<a href="<?php echo $old_login_method; ?>" class="old-auth">
					<?php echo $entry_old_login_method; ?>
				</a>
			</div>
		</div>																	
</div>

<script>
	$(document).ready(function () {     
		$('#popup_autorization .overlay-popup-close').on('click', function(e){
			$('#main-overlay-popup').trigger('click');
		});
	});
	
</script>