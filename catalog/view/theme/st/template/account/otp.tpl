<style>
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
		border-radius: 4px;
	    overflow: hidden;
	    position: relative;
	}
	#popup_autorization .main-wrap .left-wrap .logo{
		display: flex;
	    height: 80px;
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
	    background: #f7f4f4;
/*	    padding: 40px 40px 0 40px;*/
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
		<div class="overlay-popup-close"></div>
		<div class="left-wrap">
			<div class="logo">
				<svg xmlns="https://www.w3.org/2000/svg" width="100%" viewBox="0 0 706.49 296.83"><title>Інтернет-магазин</title><defs><style>.cls-1{fill:#4b4948;}.cls-2,.cls-7{fill:none;}.cls-2{stroke:#fff;stroke-miterlimit:10;stroke-width:0.5px;}.cls-3{fill:#57ac79;}.cls-4{fill:#e1675d;}.cls-5{fill:#fff;}.cls-6{fill:#fbc04f;}</style></defs><g id="Слой_1" data-name="Слой 1"><path class="cls-1" d="M289.36,175.42V38.74H314V175.42Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M353.65,175.42V57.13H331.88V38.74h68.17V57.13H378.62V175.42Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M447.58,178.05q-15,0-23.54-5.9A30.79,30.79,0,0,1,412.15,156a74.93,74.93,0,0,1-3.38-23.54V83.73q0-14,3.38-24.3A29.12,29.12,0,0,1,424,43.57Q432.56,38,447.58,38q14.18,0,22.19,4.81a26.44,26.44,0,0,1,11.39,13.75,58.61,58.61,0,0,1,3.38,20.76V88.79h-24V77a83.5,83.5,0,0,0-.6-10.46,13,13,0,0,0-3.2-7.51c-1.75-1.86-4.75-2.79-9-2.79s-7.4,1-9.36,3a14.21,14.21,0,0,0-3.8,7.93,67.86,67.86,0,0,0-.84,11.22v59.39A52.51,52.51,0,0,0,434.93,150a12.66,12.66,0,0,0,4.3,7.25c2.08,1.64,4.92,2.45,8.52,2.45q6.24,0,8.86-2.95a14.57,14.57,0,0,0,3.29-7.85,80.73,80.73,0,0,0,.68-11V125.57h24v11a66.31,66.31,0,0,1-3.21,21.52,28,28,0,0,1-11.22,14.68Q462.09,178.05,447.58,178.05Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M502.58,175.42V38.74h24.81v57.2h27.84V38.74h25V175.42h-25V113.66H527.39v61.76Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M598,175.42V38.74h57.37V56.29h-32.4V95.61h25.48V113H622.93v45.22h32.74v17.21Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M673.63,175.42V38.74H691l33.24,78v-78h20.58V175.42H728.3L694.89,93.75v81.67Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M353.64,333.14V196.46H393.8q12.31,0,20,4.56a27,27,0,0,1,11.31,13.25q3.61,8.68,3.62,21,0,13.33-4.55,21.52a27.15,27.15,0,0,1-12.74,11.89,46.38,46.38,0,0,1-19.32,3.71h-13.5v60.75Zm25-78.29h9.45q6.75,0,10.55-1.94a10.42,10.42,0,0,0,5.23-6.25,37.87,37.87,0,0,0,1.43-11.56,54.13,54.13,0,0,0-1.1-12.06,10.34,10.34,0,0,0-4.72-6.83q-3.63-2.19-11.39-2.2h-9.45Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M446.78,333.14V196.46h32.73q13.85,0,23.37,3.29A26.83,26.83,0,0,1,517.39,211q5,7.92,5,21.59a65.72,65.72,0,0,1-1.52,14.85,28.15,28.15,0,0,1-5.14,11.22,23.13,23.13,0,0,1-10,7.17l19.07,67.33h-25l-16.53-62.6H471.75v62.6Zm25-78.29h7.76q7.26,0,11.56-2.11a12.35,12.35,0,0,0,6.16-6.75,32.33,32.33,0,0,0,1.86-11.9q0-10.29-3.8-15.44t-14.43-5.14h-9.11Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M583.61,334.83q-14.52,0-23.12-5.4a29.82,29.82,0,0,1-12.32-15.53q-3.7-10.12-3.71-24V238.82q0-13.84,3.71-23.71A29.05,29.05,0,0,1,560.49,200q8.61-5.23,23.12-5.23,14.83,0,23.36,5.23a29.63,29.63,0,0,1,12.32,15.1q3.8,9.87,3.8,23.71v51.29q0,13.67-3.8,23.71A30.85,30.85,0,0,1,607,329.34Q598.45,334.83,583.61,334.83Zm0-18.4c4.16,0,7.22-.89,9.19-2.7a13.57,13.57,0,0,0,4-7.42,49.46,49.46,0,0,0,1-10.29V233.08a47.91,47.91,0,0,0-1-10.29,13,13,0,0,0-4-7.17q-3-2.62-9.19-2.62-5.91,0-9,2.62a12.83,12.83,0,0,0-4,7.17,47.91,47.91,0,0,0-1,10.29V296a53.33,53.33,0,0,0,.93,10.29,12.9,12.9,0,0,0,4,7.42C576.52,315.54,579.56,316.43,583.61,316.43Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M645.55,333.14V196.46h56.18v17.21H670.52V253.5H696v17.38H670.52v62.26Z" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M720.3,333.14V196.46h24.63V333.14Z" transform="translate(-38.44 -38)"></path><path class="cls-2" d="M69.39,280.22" transform="translate(-38.44 -38)"></path><path class="cls-1" d="M196.13,175.42V38.74h25V98.81l27-60.07h23.79L245.73,99.82l28.86,75.6H249.78l-22.94-62.27-5.74,10.47v51.8Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M175,90.6V38.9h0V90.6Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M175,114.44V93.57h0v20.88Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M174.92,90.6V38.9H38.44V175.42H90.15A93.89,93.89,0,0,1,174.92,90.6Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M174.92,114.45V93.57a91,91,0,0,0-81.8,81.85H114A70.16,70.16,0,0,1,174.92,114.45Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M115.49,175.42h59.43V115.94A68.68,68.68,0,0,0,115.49,175.42Z" transform="translate(-38.44 -38)"></path><path class="cls-3" d="M174.92,115.94v59.48h0V115.94Z" transform="translate(-38.44 -38)"></path><rect class="cls-4" x="136.47" y="158.38" width="0.04" height="0.04"></rect><path class="cls-4" d="M174.92,196.42v80.87A93.91,93.91,0,0,1,90.6,196.42H38.44V332.89H175V196.42Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M90.59,196.38H38.44v0H90.6Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M114.58,196.42h-21a91,91,0,0,0,81.34,77.9V253.44A70.18,70.18,0,0,1,114.58,196.42Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M114.57,196.38h-21v0h21Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M174.92,252V196.42H116.08A68.71,68.71,0,0,0,174.92,252Z" transform="translate(-38.44 -38)"></path><path class="cls-4" d="M116.08,196.42h58.84v0H116.07Z" transform="translate(-38.44 -38)"></path><path class="cls-5" d="M93.58,196.42v0h-3v0Z" transform="translate(-38.44 -38)"></path><path class="cls-5" d="M116.08,196.42v0h-1.5v0Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M196.17,196.38H196v55l.22,0Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M250.91,196.38H196.17v54.93A68.71,68.71,0,0,0,250.91,196.38Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M196,276.86v56h.22V276.83Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M332.46,196.38h-56a93.93,93.93,0,0,1-80.24,80.45v56.06H332.46Z" transform="translate(-38.44 -38)"></path><path class="cls-7" d="M196,273.87v-21A71,71,0,0,1,183.5,254a69.34,69.34,0,0,1-8.58-.55v20.88q4.23.4,8.58.41A91.34,91.34,0,0,0,196,273.87Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M273.42,196.38h-21a70.21,70.21,0,0,1-56.24,56.44v21A91,91,0,0,0,273.42,196.38Z" transform="translate(-38.44 -38)"></path><path class="cls-6" d="M196,252.86v21l.22,0v-21Z" transform="translate(-38.44 -38)"></path><path class="cls-5" d="M196.17,273.84l-.22,0v3l.22,0Z" transform="translate(-38.44 -38)"></path><path class="cls-5" d="M196.17,252.82v-1.51l-.22,0v1.51Z" transform="translate(-38.44 -38)"></path></g></svg>
			</div>
			<!-- <img src="/catalog/view/theme/dp/img/footer_pwa_intall_bg_1.png" class="bg" loading="lazy"> -->
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