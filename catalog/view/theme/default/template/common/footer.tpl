<!--footer-->
<footer>
    <div class="wrap ajax-module-reloadable" data-modpath="module/subscribe" data-reloadable-group="customer">
	</div>  
	
	<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/footer_pwainstall.tpl')); ?>
	
    <div class="wrap">  
        <!--footer__medium-->
        <div class="footer__medium">
            <!--footer__contacts-->
            <div class="footer__contacts">
				<p>
                    <a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone) ? $phone : ''; ?>"><?php echo isset($phone) ? $phone : ''; ?></a>
				</p>
				
                <p>
                    <a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone2) ? $phone2 : ''; ?>"><?php echo isset($phone2) ? $phone2 : ''; ?></a>
				</p>
                
				
				<p>
					<a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone3) ? $phone3 : ''; ?>"><?php echo isset($phone3) ? $phone3 : ''; ?></a>
				</p>
				<button  data-target="callback-view" class="do-popup-element callback-view btn"><?php echo $text_retranslate_26; ?></button>
				
				
				<!--social-contact-->
				<div class="social-contact-footer">
					<ul class="social-contact__list">
						<li>
							<a href="http://m.me/104116757981722" class="facebook" title="Facebook" rel="noindex nofollow">
								<i class="fab fa-facebook-messenger"></i>
							</a>
						</li> 
						<li>
							
							<a class="viber" href="viber://pa?chatURI=kitchen-profi" title="Viber" rel="noindex nofollow">
								<svg width="17" height="18" viewBox="0 0 17 18" fill="none"
								xmlns="http://www.w3.org/2000/svg">
									<path d="M12.6904 0.909435C9.83181 0.363522 6.86322 0.363522 4.00458 0.909435C2.74018 1.18239 1.14594 2.71095 0.871073 3.91196C0.376309 6.25939 0.376309 8.66141 0.871073 11.0088C1.20092 12.2098 2.79516 13.7384 4.00458 14.0114C4.05955 14.0114 4.11453 14.066 4.11453 14.1205V17.5598C4.11453 17.7236 4.33442 17.8328 4.44437 17.669L6.09359 15.9767C6.09359 15.9767 7.41296 14.6119 7.63285 14.3935C7.63285 14.3935 7.68783 14.3389 7.7428 14.3389C9.39202 14.3935 11.0962 14.2297 12.7454 13.9568C14.0098 13.6838 15.6041 12.1553 15.8789 10.9542C16.3737 8.60682 16.3737 6.2048 15.8789 3.85737C15.5491 2.71095 13.9548 1.18239 12.6904 0.909435ZM12.7454 11.1726C12.4705 11.7185 12.1407 12.1553 11.591 12.4282C11.426 12.4828 11.2611 12.5374 11.0962 12.592C10.8763 12.5374 10.7114 12.4828 10.5465 12.4282C8.7873 11.7185 7.13809 10.7359 5.81872 9.31651C5.10406 8.49764 4.49934 7.56958 4.00458 6.58694C3.78468 6.09562 3.56479 5.65888 3.39987 5.16756C3.23495 4.73083 3.50981 4.2941 3.78468 3.96655C4.05955 3.639 4.3894 3.42064 4.77421 3.25686C5.04908 3.09309 5.32395 3.20227 5.54385 3.42064C5.98364 3.96655 6.42343 4.51247 6.75327 5.11297C6.97317 5.49511 6.91819 5.93184 6.53338 6.2048C6.42343 6.25939 6.36845 6.31398 6.25851 6.42316C6.20353 6.47776 6.09358 6.53235 6.03861 6.64153C5.92866 6.8053 5.92866 6.96908 5.98364 7.13285C6.42343 8.38845 7.24803 9.3711 8.51243 9.91701C8.73233 10.0262 8.89725 10.0808 9.17212 10.0808C9.55694 10.0262 9.72186 9.58946 9.99673 9.3711C10.2716 9.15273 10.6014 9.15273 10.9313 9.31651C11.2062 9.48028 11.481 9.69864 11.8109 9.91701C12.0857 10.1354 12.3606 10.2992 12.6355 10.5175C12.8004 10.6267 12.8554 10.8997 12.7454 11.1726ZM10.4365 7.07826C10.3266 7.07826 10.3815 7.07826 10.4365 7.07826C10.2166 7.07826 10.1616 6.96908 10.1067 6.8053C10.1067 6.69612 10.1067 6.53235 10.0517 6.42316C9.99673 6.2048 9.88678 5.98643 9.66689 5.82266C9.55694 5.76807 9.44699 5.71348 9.33704 5.65888C9.17212 5.60429 9.06217 5.60429 8.89725 5.60429C8.73233 5.5497 8.67736 5.44052 8.67736 5.27674C8.67736 5.16756 8.84228 5.05838 8.95222 5.05838C9.83181 5.11297 10.4915 5.60429 10.6014 6.64153C10.6014 6.69612 10.6014 6.8053 10.6014 6.85989C10.6014 6.96908 10.5465 7.07826 10.4365 7.07826ZM9.88678 4.67624C9.61191 4.56706 9.33704 4.45787 9.0072 4.40328C8.89725 4.40328 8.73233 4.34869 8.62238 4.34869C8.45746 4.34869 8.34751 4.23951 8.40249 4.07573C8.40249 3.91196 8.51243 3.80278 8.67736 3.85737C9.22709 3.91196 9.72186 4.02114 10.2166 4.23951C11.2062 4.73083 11.7559 5.5497 11.9208 6.64153C11.9208 6.69612 11.9208 6.75071 11.9208 6.8053C11.9208 6.91449 11.9208 7.02367 11.9208 7.18744C11.9208 7.24203 11.9208 7.29663 11.9208 7.35122C11.8658 7.56958 11.481 7.62417 11.426 7.35122C11.426 7.29663 11.3711 7.18744 11.3711 7.13285C11.3711 6.64153 11.2611 6.15021 11.0412 5.71348C10.7114 5.22215 10.3266 4.89461 9.88678 4.67624ZM12.8554 7.95172C12.6904 7.95172 12.5805 7.78795 12.5805 7.62417C12.5805 7.29663 12.5255 6.96908 12.4705 6.64153C12.2507 4.8946 10.8213 3.47523 9.11715 3.20227C8.84228 3.14768 8.56741 3.14768 8.34751 3.09309C8.18259 3.09309 7.96269 3.09309 7.90772 2.87472C7.85275 2.71095 8.01767 2.54718 8.18259 2.54718C8.23756 2.54718 8.29254 2.54718 8.29254 2.54718C10.5465 2.60177 8.40249 2.54718 8.29254 2.54718C10.6014 2.60177 12.5255 4.13033 12.9103 6.42316C12.9653 6.8053 13.0203 7.18744 13.0203 7.62417C13.1302 7.78795 13.0203 7.95172 12.8554 7.95172Z"
									fill="white"/>
								</svg>
							</a>
						</li>
						<li>
							<a class="tg" href="https://teleg.one/kitchenprofi_bot" title="Telegram" rel="noindex nofollow">
								<svg width="16" height="14" viewBox="0 0 16 14" fill="none"
								xmlns="http://www.w3.org/2000/svg">
									<path d="M1.01303 6.28056L4.4616 7.56647L5.80595 11.8918C5.8644 12.184 6.2151 12.2425 6.4489 12.0671L8.37775 10.489C8.5531 10.3136 8.84536 10.3136 9.07916 10.489L12.5277 13.0023C12.7615 13.1777 13.1122 13.0608 13.1707 12.7685L15.7425 0.493992C15.8009 0.201741 15.5087 -0.0905074 15.2164 0.0263929L1.01303 5.52071C0.662325 5.63761 0.662325 6.16366 1.01303 6.28056ZM5.6306 6.92351L12.4108 2.77355C12.5277 2.7151 12.6446 2.89045 12.5277 2.9489L6.97495 8.15097C6.7996 8.32632 6.62425 8.56012 6.62425 8.85237L6.4489 10.2552C6.4489 10.4305 6.15665 10.489 6.0982 10.2552L5.39679 7.68336C5.22144 7.39111 5.33835 7.04041 5.6306 6.92351Z"
									fill="white"/>
								</svg>
							</a>
						</li>
						<!-- <li>
							<a class="wa" href="whatsapp://send?phone=+12034567891" rel="noindex nofollow">
							<svg width="17" height="17" viewBox="0 0 17 17" fill="none"
							xmlns="http://www.w3.org/2000/svg">
							<path d="M13.9451 3.04687C12.4634 1.57031 10.4878 0.75 8.40244 0.75C4.06707 0.75 0.554877 4.25 0.554877 8.57031C0.554877 9.9375 0.939024 11.3047 1.59756 12.4531L0.5 16.5L4.67073 15.4062C5.82317 16.0078 7.08536 16.3359 8.40244 16.3359C12.7378 16.3359 16.25 12.8359 16.25 8.51563C16.1951 6.49219 15.4268 4.52344 13.9451 3.04687ZM12.189 11.3594C12.0244 11.7969 11.2561 12.2344 10.872 12.2891C10.5427 12.3437 10.1037 12.3438 9.66463 12.2344C9.39024 12.125 9.0061 12.0156 8.56707 11.7969C6.59146 10.9766 5.32927 9.00781 5.21951 8.84375C5.10976 8.73437 4.39634 7.80469 4.39634 6.82031C4.39634 5.83594 4.89024 5.39844 5.05488 5.17969C5.21951 4.96094 5.43902 4.96094 5.60366 4.96094C5.71341 4.96094 5.87805 4.96094 5.9878 4.96094C6.09756 4.96094 6.26219 4.90625 6.42683 5.28906C6.59146 5.67187 6.97561 6.65625 7.03049 6.71094C7.08536 6.82031 7.08536 6.92969 7.03049 7.03906C6.97561 7.14844 6.92073 7.25781 6.81097 7.36719C6.70122 7.47656 6.59146 7.64062 6.53658 7.69531C6.42683 7.80469 6.31707 7.91406 6.42683 8.07812C6.53659 8.29687 6.92073 8.89844 7.52439 9.44531C8.29268 10.1016 8.89634 10.3203 9.11585 10.4297C9.33537 10.5391 9.44512 10.4844 9.55488 10.375C9.66463 10.2656 10.0488 9.82812 10.1585 9.60938C10.2683 9.39062 10.4329 9.44531 10.5976 9.5C10.7622 9.55469 11.75 10.0469 11.9146 10.1562C12.1341 10.2656 12.2439 10.3203 12.2988 10.375C12.3537 10.5391 12.3537 10.9219 12.189 11.3594Z"
							fill="white"/>
							</svg>
							</a>
						</li> -->
					</ul>
					
				</div>
				<!--/social-contact-->
				<div class="footer__time-work">
					<?php if ($this->config->get('config_store_id') == 5) {?>
						
						<p hidden="hidden">ООО "Китчен профи"<br>
							УНП 193533874<br>
							Республика Беларусь, г. Минск, ул. Тимирязева 65Б<br>
							
							Свидетельство о государственной регистрации № 193533874 от 13.04.2021<br>
							выдано Минским горисполкомом<br>
						Интернет-магазин зарегистрирован в Торговом реестре РБ 21.04.2021 <br></p>
					<?php } ?>
					<?php if ($this->config->get('config_store_id') == 5) {?><span class="by_schedule"><?php } ?>
						<?php echo $text_retranslate_1; ?><br />
						<?php echo $text_retranslate_2; ?><br />
						<?php echo $text_retranslate_3; ?> 
					<?php if ($this->config->get('config_store_id') == 5) {?></span><?php } ?>
					<? /* if ($worktime) { ?>
						<? echo $worktime; ?>
					<? } */ ?>
				</div>
				<ul class="footer__social">
					<li><a href="https://twitter.com/KitchenProfi" target="_blank" class="twitter element-target-click-event" data-gtm-event="soc-seti" data-ym-event="soc-seti" title="Twitter" rel="noindex nofollow"></a></li>
					<li><a href="https://www.facebook.com/Kitchen-Profi-104116757981722" target="_blank" class="facebook element-target-click-event" data-gtm-event="soc-seti" data-ym-event="soc-seti" title="Facebook" rel="noindex nofollow"></a></li>
					<li><a href="https://www.instagram.com/kitchenprofi.de/" target="_blank" class="instagram element-target-click-event" data-gtm-event="soc-seti" data-ym-event="soc-seti" title="Instagram" rel="noindex nofollow"></a></li>
					<? if ($this->config->get('config_store_id') == 1) { ?>                        
						<? } else { ?>
						<li><a href="https://vk.me/club88315749" target="_blank" data-gtm-event="soc-seti" data-ym-event="soc-seti" class="vk element-target-click-event" title="vk" rel="noindex nofollow"></a></li>
					<? } ?>
				</ul>
				
				<?php if ($this->config->get('config_store_id') == 1) {?>
					<style type="text/css">
						#ratingBadgeContainer {
						position: absolute !important;
						margin-top: 0!important;
						right: 10% !important;
						top: 0;
						z-index: 0 !important;
						background: whitesmoke !important;
						}
					</style>     
					<?php } else{  ?>
					<style type="text/css">
						#ratingBadgeContainer {
						position: absolute !important;
						margin-top: 0!important;
						right: 10% !important;
						top: 150px;
						z-index: 0 !important;
						}
					</style>
				<?php } ?>
				
				<?php if ($config_google_merchant_id) { ?>
					<style type="text/css">
						@media screen and (max-width: 1000px){
						#ratingBadgeContainer {
						top: 0 !important;
						position: relative !important;
						right: 0 !important;
						margin-top: 25px !important;
						overflow: hidden;
						}
						}
					</style>
					
					<?php if (ADD_METRICS_TO_FRONT) { ?>					
						<script src="https://apis.google.com/js/platform.js?onload=renderBadge" async defer></script>
						<div id="ratingBadgeContainer" class="footer__ratingbadgecontainer"></div>

						<script>
							window.renderBadge = function() {
								var ratingBadgeContainer = document.getElementById('ratingBadgeContainer');
								window.gapi.load('ratingbadge', function() {
									window.gapi.ratingbadge.render(ratingBadgeContainer, {"merchant_id": <?php echo $config_google_merchant_id; ?>});
								});
							}

							$(document).ready(function(){
								setTimeout(renderBadge(), 1000);
							});
						</script>
					<?php } ?>
					
				<?php } ?>
				<? if ($this->config->get('config_country_id') != 220) { ?>                                            
					<div class="yandex-rait">
						<a href="https://clck.yandex.ru/redir/dtype=stred/pid=47/cid=73582/path=dynamic.200x125/*https://market.yandex.ru/shop--kitchen-profi/346381/reviews" target="_blank" rel="noindex nofollow"> <img src="https://clck.yandex.ru/redir/dtype=stred/pid=47/cid=73581/path=dynamic.200x125/*https://grade.market.yandex.ru/?id=346381&action=image&size=3" border="0" alt="<?php echo $text_retranslate_4; ?>" loading="lazy"/> </a>
					</div>					
				<? } ?>
			</div>
            <!--/footer__contacts-->
            <!--footer__links-->
            <div class="footer__links">
                <!--footer__links-item-->
                <div class="footer__links-item">
                    <div class="footer__links-title"><?php echo $text_retranslate_5; ?></div>
                    <ul>
                        <li><a href="<?php echo $href_how_order; ?>"><?php echo $text_retranslate_6; ?></a></li>
                        <li><a href="<?php echo $href_delivery; ?>"><?php echo $text_retranslate_7; ?></a></li>
                        <li><a href="<?php echo $href_payment; ?>"><?php echo $text_retranslate_8; ?></a></li>
                        <li><a href="<?php echo $href_return; ?>"><?php echo $text_retranslate_9; ?></a></li>
					</ul>
                    <ul>                        
                        <!-- <li><a href="<?php echo $href_discounts; ?>"><?php echo $text_retranslate_10; ?></a></li> -->
                        
                        <li><a href="<?php echo $href_present_sertificate; ?>"><?php echo $text_retranslate_11; ?></a></li>
						<li><a href="<?php echo $href_cashback; ?>"><?php echo $text_cashback; ?></a></li>
						<?php if (!empty($markdown_link)) { ?>
                            <li><a href="<?php echo $markdown_link; ?>"><?php echo $text_retranslate_12; ?>  <sup class="text-danger" style="display: none;"><?php echo $markdown_total; ?></sup></a></li>
						<?php } ?>
						
                        <!-- <li><span class="do-popup-element report_bug" data-target="report_bug"><i class="fas fa-exclamation-triangle" style="margin-right: 4px;"></i>Сообщить об ошибке</span></li> -->
					</ul>
				</div>
                <!--/footer__links-item-->
                <!--footer__links-item-->
                <div class="footer__links-item">
                    <div class="footer__links-title"><?php echo $text_retranslate_13; ?></div>
                    <ul>
                        <li><a href="<?php echo $href_about; ?>"><?php echo $text_retranslate_14; ?></a></li>
                        <li><a href="<?php echo $href_faq; ?>"><?php echo $text_retranslate_15; ?></a></li>
                        <li><a href="<?php echo $href_contact; ?>"><?php echo $text_retranslate_16; ?></a></li>
					</ul>
                    <ul>
                        <li><a href="<?php echo $href_shop_rating; ?>"><?php echo $text_retranslate_17; ?></a></li>
                        <li><a href="<?php echo $href_sitemap; ?>"><?php echo $text_retranslate_18; ?></a></li>
                        <? if ($this->config->get('config_store_id') == 0) { ?>
                            <li><a href="<?php echo $href_vendors; ?>"><?php echo $text_retranslate_27; ?></a></li>
						<?php } ?>
					</ul>
				</div>
				<!--/footer__links-item-->
				<!--footer__links-item-->
                <div class="footer__links-item applinks_wrap">
                    <div class="footer__links-title"><?php echo $text_retranslate_app_header; ?></div>
                    
                	<div class="applinks">
						
						<?php if ($this->config->get('config_android_playstore_enable')) { ?>
							<a href="<?php echo $this->config->get('config_android_playstore_link'); ?>" target="_blank" rel="noindex nofollow"> 
								<?php if ($this->config->get('config_language_id') == 6) { ?>
									<img src="/catalog/view/theme/kp/img/gplay_ua.svg" height="55px" loading="lazy" alt="logo google play"> 
									<?php } else { ?>
									<img src="/catalog/view/theme/kp/img/gplay_ru.svg" height="55px" loading="lazy" alt="logo google play"> 
								<?php } ?>
							</a> 
						<?php } ?>
						
						
                		<a target="_blank" id="footer_app_button" style="display: none;"> 
                			<?php if ($this->config->get('config_language_id') == 6) { ?>
                				<img src="/catalog/view/theme/kp/img/pwa_ua.svg" height="55px" loading="lazy" alt="logo pwa"> 
								<?php } else { ?>
                				<img src="/catalog/view/theme/kp/img/pwa_ru_new.svg" height="55px" loading="lazy" alt="logo pwa"> 
							<?php } ?>
						</a> 
					</div>
					
				</div>
				<!--/footer__links-item-->
			</div>
            <!--/footer__links-->
		</div>
        <!--/footer__medium-->
	</div>
	
    <!--footer__down-->
    <div class="footer__down">
		<?php if ($this->config->get('config_store_id') == 2) { ?>
			<div class="snow-left"></div>
			<div class="snow-right"></div>
		<?php } ?>
        <div class="wrap">
            <div class="footer__container">
                <div class="footer__copyright">
                    <div class="footer__language">

                    <?php if (!$this->config->get('config_warmode_enable')) { ?>                    	

						<div class="lang-menu <? if ($this->config->get('config_store_id') == 0) { ?>selected-lang_ru<?php } elseif ($this->config->get('config_store_id') == 1) {?>selected-lang_ua<?php } elseif ($this->config->get('config_store_id') == 2) {?>selected-lang_kz<?php }elseif ($this->config->get('config_store_id') == 5) {?>selected-lang_by<?php } ?>">
							<div class="selected-lang">
								<span class="active-lang"></span>
								<svg width="14" height="7" viewBox="0 0 14 7" fill="none" xmlns="https://www.w3.org/2000/svg">
									<path d="M1 1L7 6L13 1" stroke="#FFC34F" stroke-width="2" stroke-linejoin="round"></path>
								</svg>
							</div>
							<ul>
								<li class="href_ru">
									<a href="<?php echo $href_ru; ?>"><img src="/catalog/view/theme/kp/img/flags/ru.png" alt="Россия" loading="lazy"></a>
								</li>
								<li class="href_ua">
									<a href="<?php echo $href_ua; ?>"><img src="/catalog/view/theme/kp/img/flags/ua.png" alt="Украина" loading="lazy"></a>
								</li>
								<li class="href_by">
									<a href="<?php echo $href_by; ?>"><img src="/catalog/view/theme/kp/img/flags/by.png" alt="Беларусь" loading="lazy"></a>
								</li>
								<li class="href_kz">
									<a href="<?php echo $href_kz; ?>"><img src="/catalog/view/theme/kp/img/flags/kz.png" alt="Казахстан" loading="lazy"></a>
								</li>
							</ul>
							
						</div>
					<?php } ?>
						
						
					</div>
                    <p>&copy;<?php echo $this->config->get('config_name'); ?>
                    2010 - <?php echo date('Y'); ?> </p>
				</div>
                <div class="footer__agreement">
                    <ul>
                    	<?php if ($this->config->get('config_store_id') == 5) { ?>
							<?php } else { ?>
	                        <li><a href="<?php echo $href_polzovatelskoe; ?>" title="<?php echo $text_retranslate_19; ?>"><?php echo $text_retranslate_19; ?></a></li>
						<?php } ?>
                        <li><a href="<?php echo $href_personaldata; ?>" title="<?php echo $text_retranslate_20; ?>"><?php echo $text_retranslate_20; ?></a>
						</li>
					</ul>
				</div>
				
				
                <ul class="footer__payments">
                    <?php if ($this->config->get('config_store_id') == 5) {?>
                        <li>
                            <img src="/catalog/view/theme/kp/img/all.png" alt="" style="max-width: 486px;" class="by-img"  loading="lazy">
						</li>
						<?php } else { ?>
                        <li>
                            <img src="/catalog/view/theme/kp/img/master-card.svg" alt=""  loading="lazy">
						</li>
                        <li>
                            <img src="/catalog/view/theme/kp/img/visa.svg" alt=""  loading="lazy">
						</li>
                        <?php if ($this->config->get('config_store_id') == 1) {?>
    						<li>
                                <img src="/catalog/view/theme/kp/img/LIQPAY.png" alt="" width="47" loading="lazy">
							</li>
						<?php } ?>
                        <li>
                            <img src="/catalog/view/theme/kp/img/PayKeeper.svg" alt="" loading="lazy">
						</li>
                        <li>
                            <img src="/catalog/view/theme/kp/img/paypal.svg" alt="" loading="lazy">
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
    <!--/footer__down-->
    <?php if ($this->config->get('config_store_id') == 22222) { ?>
        <!-- SNOW -->
        <style type="text/css">
            #particles-js{
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            z-index:-1;
            overflow: hidden;
            }
		</style>
        <div id="particles-js"></div>
        <script src="/catalog/view/theme/kp/js/particles.min.js"></script>
        <script>
            particlesJS("particles-js", {
            "particles": {
            "number": {
            "value": 80,
            "density": {
            "enable": true,
            "value_area": 800
			}
			},
			"color": {
			"value": "#ffffff"
			},
			"shape": {
			"type": "image",
			"stroke": {
			"width": 3,
			"color": "#fff"
			},
			"polygon": {
			"nb_sides": 5
			},
			"image": {
			"src": "/catalog/view/theme/kp/img/snowflake-alt-512-green.png",
			"width": 100,
			"height": 100
			}
			},
			"opacity": {
			"value": 0.7,
			"random": false,
			"anim": {
			"enable": false,
			"speed": 1,
			"opacity_min": 0.1,
			"sync": false
			}
			},
			"size": {
			"value": 5,
			"random": true,
			"anim": {
			"enable": false,
			"speed": 20,
			"size_min": 0.1,
			"sync": false
			}
			},
			"line_linked": {
			"enable": false,
			"distance": 0,
			"color": "#ffffff",
			"opacity": 0,
			"width": 0.01
			},
			"move": {
			"enable": true,
			"speed": 2,
			"direction": "bottom",
			"random": true,
			"straight": false,
			"out_mode": "out",
			"bounce": false,
			"attract": {
			"enable": true,
			"rotateX": 300,
			"rotateY": 1200
			}
			}
			},
			"retina_detect": true
			});
			
		</script>   
	<?php } ?>
	
</footer>
<div id="top"><i class="fas fa-chevron-up"></i></div>
<!--/footer-->
<div id="main-overlay-popup" class="overlay_popup"></div>
<div id="popup-cart-trigger" class="do-popup-element" data-target="popup-cart" class="hidden"></div>
<div id="popup-cart" class="popup-form" style="display:none;"></div>

<style type="text/css">
    #show_register_modal{
	top: 20% !important;
	position: fixed;
	z-index: 999;
	background: #fff;
	width: 55%;
    }
    #show_register_modal .body{
	padding: 20px;
	padding-right: 45px;
    }
    #show_register_modal .close_modals {
	position: absolute;
	right: 10px;
	width: 25px;
	height: 25px;
	line-height: 25px;
	top: 10px;
	font-size: 15px;
	color: #2121217d;
	cursor: pointer;
	background-image: url(/catalog/view/theme/kp/img/close-modal.svg);
	background-size: 11px 11px;
	background-repeat: no-repeat;
	border: 1px solid #000;
	border-radius: 50px;
	text-align: center;
	background-position: center;
	opacity: .5;
	z-index: 10;
	background-color: #fff;
    }
    #show_register_modal p{
	font-size: 14px;
    }
    #show_register_modal p a{
	color: #51a881;
    }
    #show_register_modal p a:hover{
	text-decoration: underline;
    }
</style>
<div id="show_register_modal" class="popup-form" style="display: none;">
    <div class="wrap-modal">
        <div class="body">
            <button class="close_modals"></button>
            <p>Вы должны выполнить <a href="/account/login">вход</a> или <a href="/register">создать аккаунт</a> чтобы сохранить <a href="" class="name_product"></a> в свой список закладок!</p>
		</div>  
	</div>
</div>

<? /* VOICE SEARCH */ ?>
<style type="text/css">
    @-webkit-keyframes voice-modal__preview--outer{
	0%{box-shadow:0 0 0 0 rgb(81 168 129 / 60%)}
	30%{box-shadow:0 0 0 12px rgb(81 168 129 / 60%)}
	to{box-shadow:0 0 0 0 #51a881}
    }
    @keyframes voice-modal__preview--outer{
	0%{box-shadow:0 0 0 0 rgb(81 168 129 / 60%)}
	30%{box-shadow:0 0 0 12px rgb(81 168 129 / 60%)}
	to{box-shadow:0 0 0 0 rgb(81 168 129 / 60%)}
    }
    #voice_modal{
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	position: fixed;
	overflow: auto;
	margin: 0!important;
	background-color: rgba(0,0,0,.5);
	display: flex;
	align-items: center;
	overflow: hidden!important;
	z-index: 3001!important;
    }
    #voice_modal .wrap-modal{
	width: 288px;
	top: 50%;
	max-height: 100vh;
	overflow-y: auto;
	overflow-x: hidden;
	position: absolute;
	left: 50%;
	transform: translate(-50%, -50%);
	background: #fff;
	border-radius: 4px;
	box-shadow: 0 1px 3px rgb(0 0 0 / 30%);
	min-height: 150px;
	text-align: center;
	transition: padding-top .5s ease-out;
    }
    #voice_modal .wrap-modal .body .content{
	padding: 88px 24px 64px;       
	transition: padding-top .5s ease-out;
	height: 288px;
    }
    #voice_modal .wrap-modal .body.error_voice .voice-modal__icon{
	-webkit-animation: none;
	animation: none;
	border: 2px solid #f30;
	background-color: transparent;
	color:  #f30;
    }
    #voice_modal .wrap-modal .body.error_voice .content{
	padding: 51px 24px;
    }
    #voice_modal .wrap-modal .body .voice-modal__error p{
	line-height: 1.5;
	color: rgba(0,0,0,.87);
	text-align: center;
	max-width: 181px;
	margin: 0 auto 10px;
    }
    #voice_modal .wrap-modal .body .voice-modal__error button{
	line-height: normal;
	font-size: 14px;
	color: #51a881;
	cursor: pointer;
	background: 0 0;
	margin: 0 auto;
    }
    #voice_modal .wrap-modal p{
	font-size: 14px;
	margin-bottom: 0;
    }
    
    #voice_modal .voice-modal__icon{
	width: 64px;
	height: 64px;
	margin: 0 auto 25px;
	background: #51a881;
	-webkit-animation: voice-modal__preview--outer 1s ease-out infinite alternate;
	animation: voice-modal__preview--outer 1s ease-out infinite alternate;
	border-radius: 32px;
	position: relative;
	display: flex;
	align-items: center;
	box-shadow: 0 0 0 0 rgb(81 168 129 / 60%);
	justify-content: center;
	color: #fff;
	font-size: 21px;
    }
    #voice_modal .close_modals{
	position: absolute;
	right: 10px;
	width: 25px;
	height: 25px;
	line-height: 25px;
	top: 10px;
	font-size: 15px;
	color: #2121217d;
	cursor: pointer;
	background-image: url(/catalog/view/theme/kp/img/close-modal.svg);
	background-size: 11px 11px;
	background-repeat: no-repeat;
	border: 1px solid #000;
	border-radius: 50px;
	text-align: center;
	background-position: center;
	opacity: .5;
	z-index: 10;
	background-color: #fff;
    }
</style>
<div id="voice_modal" class="overlay_modal" style="display: none;">
    <div class="wrap-modal">
        <div class="body">
            <div class="content">
                <button class="close_modals"></button>
                <div class="voice-modal__icon">
                    <i class="fas fa-microphone"></i>
				</div>
                <p class="voice-modal__say">Скажите что-нибудь</p>
                <p class="voice-modal__text-recognize"></p>
                <div class="voice-modal__error">
                    <p class="voice-modal__error-text">
                        Ничего не найдено. Произнесите текст еще раз
					</p> 
                    <button class="voice-modal__repeat">
                        Повторить
					</button>
				</div>
			</div>            
		</div>  
	</div>
</div>

<? /* /VOICE SEARCH */ ?>

<?php if ($general_minified_css_uri) { ?>
    <link href="<? echo $general_minified_css_uri; ?>" rel="stylesheet" media="screen" />
<?php } ?>

<?php if ($general_minified_js_uri) { ?>
    <script src="<? echo $general_minified_js_uri; ?>"></script>
<?php } ?>

<?php  foreach ($incompatible_scripts as $incompatible_script) { ?>
    <script src="<?php echo $incompatible_script; ?>"></script>
<?php } ?>

<script type="text/javascript">
	
    function showRegisterModal(e){
        let prod  =  e.closest('.product__item').querySelector('.product__title .prod_name'),
		name  =  prod.getAttribute('title'),
		link  =  prod.getAttribute('href'),
		modal =  document.getElementById('show_register_modal');
		
        modal.querySelector('.name_product').setAttribute('href', link);
        modal.querySelector('.name_product').textContent = name;  
		
        document.getElementById('main-overlay-popup').style.display = 'block';
        document.getElementById('show_register_modal').style.display = 'block';
        document.getElementById('show_register_modal').querySelector('.close_modals').addEventListener('click', function(){
            document.getElementById('main-overlay-popup').style.display = 'none';
            document.getElementById('show_register_modal').style.display = 'none';
		});
	}   
    
    function removeHistory(id){
		
		$.ajax({
            url: "index.php?route=kp/search/clear",
            type: 'POST',
            data: {
                id: id
			},
            beforeSend: function(){
                $('#search-wrap').find('.clear_btn').html('<i class="fas fa-spinner fa-spin"></i>');  
                $('#search-wrap').find('.clear_btn').addClass('spinner');
			},
            complete: function(e){
                $('#search-wrap').find('.clear_btn').html('<i class="fas fa-times"></i>');  
                $('#search-wrap').find('.clear_btn').removeClass('spinner');				
			},
            success: function() {
                getSearch();
			}
		});
		
	}
	
    function showSearch(){
        $('#search-wrap input').closest('#search-wrap').find('.autocomplete_wrap').show(); 
	}
	
    function getSearch(){
        let query = $('#main-ajax-search').val();
		$.ajax({
            url: "index.php?route=kp/search",
            dataType: "html",
            type: 'GET',
            data: {
                query: query
			},
            beforeSend: function(){
                $('#search-wrap').find('.clear_btn').html('<i class="fas fa-spinner fa-spin"></i>');  
                $('#search-wrap').find('.clear_btn').addClass('spinner');
			},
            complete: function(e){
                $('#search-wrap').find('.clear_btn').html('<i class="fas fa-times"></i>');  
                $('#search-wrap').find('.clear_btn').removeClass('spinner');
			},
            success: function(html) {
                $('#search-wrap .autocomplete_wrap').html(html);
			}
		});
	}
	
    $('#search-wrap input').on('click', function(){
        if($(this).val().length >= 1){
            showSearch();
		}
	});	
	
	
	var timeoutID;
	
	document.querySelector('#search-wrap input').addEventListener('input', function(e) {
	    clearTimeout(timeoutID);
	    $('#search-wrap .clear_btn').show();
	    timeoutID = setTimeout(function() {
	        showSearch();
        	getSearch();  
		}, 1000);
	});
	
	$('#search-wrap input').focus(function(e) {
        showSearch();            
	});	
	
    $('#search-wrap .clear_btn').on('click', function(){
        $('#main-ajax-search').val('');
        $('#search-wrap').find('.autocomplete_wrap').hide();
        $(this).hide();
	});
	
    $(document).keypress(function (e) {        
        
        if($('#main-ajax-search').is(":focus") || $('#main-ajax-search').val().length){
            var key = e.which;
            if(key == 13)  {
                $('#search-wrap .search__btn').trigger('click');            
			}
		}
        
	});
	
	
	// $('#search-wrap input').keyup(function(){      
	// 	$('.search__field .clear_btn').show();
	//        let query = $('#main-ajax-search').val();
	// 	if (query.length >= 1){			
	//            showSearch();
	//            getSearch();
	// 	}
	// });
    var valSearch = $('#search-wrap input').prop('value');
	if(valSearch.length  > 0){
        $('.search__field .clear_btn').show();
	}
	
	
    $(document).mouseup(function (e){ 
        var searchWrap = $("#search-wrap");
        if (!searchWrap.is(e.target) && searchWrap.has(e.target).length === 0) {
            $('#search-wrap').find('.autocomplete_wrap').hide();
		}
	});
	
	
	
    if(document.documentElement.clientWidth > 992) {  
        
        $(window).resize(function() {
            let widthWrap = $('.top-menu .wrap').width();
            $('.menu-list li.parent > .topmenu').css('width', widthWrap-300);
		});
        
        let widthWrap = $('.top-menu .wrap').width();
        $('.menu-list li.parent > .topmenu').css('width', widthWrap-278);
        
        
        var $menu = $(".catalog-li .menu-list");
        
        $menu.menuAim({
            activate: activateSubmenu,
            deactivate: deactivateSubmenu
		});
        
        function activateSubmenu(row) {
            var $row = $(row),
            submenuId = $row.find('.topmenu'),
            $submenu = $(submenuId),
            // height = $('#catalog .catalog__list-wrap').outerHeight(),
            width = $('.top-menu .wrap').width();
            
            
            $submenu.css({
                display: "block",
                top: -2,
                width: width - 278,
                left: 276,
                // height: height - 4  
			});
            
            $row.find("a").addClass("maintainHover");
            $row.addClass("open_menu");
		}
        
        function deactivateSubmenu(row) {
            var $row = $(row),
            submenuId = $row.find('.topmenu'),
            $submenu = $(submenuId);
            
            $submenu.css("display", "none");
            $row.find("a").removeClass("maintainHover");
            $row.removeClass("open_menu");
		}
        
        // $(".catalog__list li").click(function(e) {
        //     e.stopPropagation();
        // });
        
        // $(document).click(function() {
        //     $(".topmenu").css("display", "none");
        //     $("a.maintainHover").removeClass("maintainHover");
        // });
        
    	// menu first li open
        $('.menu-horizontal .catalog-li .menu-list > .level1').first().addClass('open_menu').find('.topmenu').css('display','block');
        
        // level 2
        $('li.level2').each(function() {
            $(this).mousemove(function(){
                $(this).addClass('open_menu');
                $(this).find('.level3').parent().addClass('open_menu');                    
                
			});
            $(this).mouseout(function(){
                $(this).removeClass('open_menu');
                $(this).find('.level3').parent().removeClass('open_menu');
                
			});
		});
        
        
        
	}
    if(document.documentElement.clientWidth < 560) { 
    	setTimeout(function() {
			
			
	    	var widthDocument = document.documentElement.clientWidth,
			positionSearch = $('#search-wrap').offset().left+2;
			
			$('#search-wrap .search_wrap.autocomplete_wrap').css({
				width: widthDocument,
				left: -positionSearch
			});
		}, 1000);
	}
</script>
<div class="popup-form" id="callback-view">
    <div class="object">
        <div class="overlay-popup-close"><i class="fas fa-times"></i></div>
        <div class="info-order-container">
            <div class="content"></div>  
		</div>
	</div>
</div>


<div class="popup-form" id="report_bug">
    <div class="object">
        <div class="overlay-popup-close"><i class="fas fa-times"></i></div>
        <div class="info-order-container">
            <h3><?php echo $text_retranslate_21; ?></h3>
            <div class="content">                
                <form  method="post" id="report_bug-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label><?php echo $text_retranslate_22; ?></label>
                        <input type="text" name="name" class="form-control"/>
					</div>
                    <div class="form-group">
                        <label><?php echo $text_retranslate_23; ?></label>
                        <input id="phone-report_bug" class="report_bug" type="text" value="<?=$telephone ?>" placeholder="<?php echo str_replace('9', '_', $mask) ?>" name="telephone">
					</div>                    
                    <div class="form-group">
                        <label><?php echo $text_retranslate_24; ?></label>
                        <textarea name="text" class="form-control" rows="8" style="resize: none;padding-top: 15px;"></textarea>
					</div>
                    <div class="form-group-btn">
                        <a id="button-review-report_bug" class="btn btn-acaunt" ><?php echo $text_retranslate_25; ?></a>
					</div>
				</form>
			</div>  
		</div>
	</div>
</div>
<div class='popup_bg_rev' style="display: none;position: fixed;left: 0;top: 0;z-index: 999;"></div>

<div id="shov_mob">
    <div class="wrap">
        <div class="shov_mob-close"></div>
        <span class="title"><?php echo $text_retranslate_28; ?></span>
        <img class="img" src="" alt="prod">
        <span class="name"></span>
        <span class="price_prod"></span>
	</div>
</div>
<script>    
    $( document ).ready(function() {
        <? if (mb_strlen($mask, 'UTF-8') > 1) { ?>
            $('#phone-report_bug').mask("<?php echo $mask; ?>");
		<? } ?>
        
	});
</script>

<script>
    $( document ).ready(function() {
        $('#shov_mob .shov_mob-close').on('click', function(){
            $('#shov_mob').hide();
			$("body").css("overflow", "initial");
		});
        $('#shov_mob').on('click', function(){
			$("body").css("overflow", "initial");
		});
    	function accountHeader(){
    		$('.social-contact #account_header').on('click', function(){
	            $('.social-contact #account_header .user_logOut').toggleClass('active');
			});
			
		};
		
     	accountHeader();
	 	setTimeout(function() {
		    accountHeader();
		}, 1500);
        
		const   accauntHeaderBtnMob = $('.social-contact #account_header'),
		accauntMenuMob     = $('.social-contact #account_header .content');           
		
		accauntHeaderBtnMob.on('click', function() {
			if ( $(this).hasClass('is-active') ) {
				$(this).removeClass('is-active');
				accauntMenuMob.slideUp();
				} else {
				$(this).addClass('is-active');
				accauntMenuMob.slideDown();
			}
		});   
		
		$(document).mouseup(function (e){ 
			
			if (!accauntHeaderBtnMob.is(e.target) 
			&& accauntHeaderBtnMob.has(e.target).length === 0) { 
				accauntMenuMob.slideUp();
				accauntHeaderBtnMob.removeClass('is-active');
			}
		});  
        
	}); 
</script>

<script>
	
	function moveToURI(targetUrl){
		console.log('Moving to ' + targetUrl);
		window.location = targetUrl;
	}
	
    $( document ).ready(function() {
		
        $('.overlay-popup .popup_wrap .popup-close').click(function(){
            $('#country_popup').css('display', 'none');
		});
		
        $('.element-target-click-event').click(function(e){
            let elem = $(this);
            let gaEvent = elem.attr('data-gtm-event');
            let ymGoal = elem.attr('data-ym-event');
			var targetUrl = elem.attr('href');
            
            if (window['google_tag_manager']) {
                e.preventDefault();
                window.dataLayer = window.dataLayer || [];
                console.log('dataLayer.push ' + gaEvent);
                dataLayer.push({
                    'event' : gaEvent,
                    'data-href' : targetUrl,
                    'eventCallback' : function() {
                        <?php if ($this->config->get('config_metrika_counter')) { ?>                       
                            if (typeof ym !== 'undefined'){
                                ym(<?php echo $this->config->get('config_metrika_counter'); ?>,'reachGoal', ymGoal);
							}
						<?php } ?>
                        moveToURI(targetUrl);
					},
					'eventTimeout' : 1000
				});
				} else {			
				moveToURI(targetUrl);
			}
		});
	});
    
</script>

<? if (ADD_METRICS_TO_FRONT) { ?>
	<?php echo ($google_analytics); ?>
	<?php if ($config_vk_enable_pixel) { ?>
		<? echo $config_vk_pixel_header; ?>
	<?php } ?>
<? } ?>


<script>
	function reloadAjaxReloadableGroupWithOutParameters(group, modules){		
		if (modules.length > 0){
			
			let uri = 'index.php?route=kp/module&modpath=' + modules.join(';') + '&group=' + group;
			
			$.ajax({
				url: uri,
				type: 'GET',
				async: true,
				dataType: 'json',
				success: function(json){					
					$.each(json, function(i, item) {
						$('.ajax-module-reloadable[data-modpath="'+ item.path +'"]').html(item.html);
					});
				},
				error: function(error){
					console.log(error);
				}
			});
			
		}
		
	}
	
	function reloadAjaxReloadableElement(elem){
		let uri = 'index.php?route=kp/module&modpath=' + elem.attr('data-modpath');
		if (elem.attr('data-x')){
			uri += '&x=' + elem.attr('data-x');
		}
		if (elem.attr('data-y')){
			uri += '&y=' + elem.attr('data-y');
		}

		let acceptHeader = 'text/html,application/xhtml+xml,application/xml;q=0.9';
		if (window.afivacceptable){
			acceptHeader = acceptHeader + ',image/avif';
		}

		if (window.webpacceptable){
			acceptHeader = acceptHeader + ',image/webp';
		}

		$.ajax({
			url: uri,
			type: 'GET',
			async: true,
			dataType: 'html',
			headers: {
				Accept: acceptHeader
			},
			success: function(html){					
				elem.html(html);
			},
			complete: function(jqXHR){
				//console.log('Finished loading ' + elem.attr('data-modpath'));				
				if (elem.attr('data-afterload') && typeof window[elem.attr('data-afterload')] == 'function'){
				//	console.log('Elem has data-afterload: ' + elem.attr('data-afterload') + '=' + typeof(window[elem.attr('data-afterload')]));
					window[elem.attr('data-afterload')](jqXHR.responseText);
				}
			},
			error: function(error){
				console.log(error);
			}
		});
	}
	
	$(document).ready(function(){
		var reloadableGroups = [];
		
		$('.ajax-module-reloadable').each(function(index, elem){
			if ($(this).attr('data-reloadable-group')){
				let newReloadableGroup = $(this).attr('data-reloadable-group');
				if (reloadableGroups.indexOf(newReloadableGroup) < 0){
					reloadableGroups.push(newReloadableGroup);
				}
				//	reloadAjaxReloadableElement($(this));
				} else {
				reloadAjaxReloadableElement($(this));
			}
		});
		
		reloadableGroups.forEach(function(group){
			let reloadableGroupModules = [];
			
			$('*[data-reloadable-group="'+ group +'"]').each(function(index, elem){
				let reloadableItemModPath = $(this).attr('data-modpath');
				
				if (reloadableGroupModules.indexOf(reloadableItemModPath) < 0){
					reloadableGroupModules.push(reloadableItemModPath);
				}				
			});
			
			reloadAjaxReloadableGroupWithOutParameters(group, reloadableGroupModules);
			
		});
		
		$('.ajax-module-reloadable').each(async function(index, elem){
			//	reloadAjaxReloadableElement($(this));
		});
	});
	
	function updateActiveCouponInBlock(productBlock, active_coupon){
		let html = '';
		html += '<div class="product__line__promocode">';
		html += '<span>'+ active_coupon.coupon_price +'</span>'
		html += '<span class="product__line__promocode__text"><?php echo $text_promocode_price;?></span>';
		html +=	'<span class="product__line__promocode__code">'+ active_coupon.code +'</span>';
		html +=	'</div>';
		
		productBlock.find('.product__delivery').before(html);
	}
	
	function updateActiveActionsInBlock(productBlock, active_actions){
		let html = '';
		active_actions.forEach(async function(active_action){
			html += '<div class="product__label-hit" style="color:#'+active_action.label_color+'; --tooltip-color:#' + active_action.label_background + '; background-color:#' + active_action.label_background + '; --tooltip-color-txt:#' + active_action.label_color + '"'; 
			if (active_action.label_text){
				html += 'data-tooltip="' + active_action.label_text + '"';
			}
			html += '>';
			html += active_action.label;
			html += '</div>';			
		});
		if( $(html).length == 0) {
			productBlock.find('.product__label').prepend(html);
		} 
		
	}
	
	function updatePriceInBlock(productBlock, price){
		
	}
	
	function updateProductBlock(productBlock, item){
		//Активные промокоды
		if (item.active_coupon){
			updateActiveCouponInBlock(productBlock, item.active_coupon);
		}
		
		if (item.active_actions){
			updateActiveActionsInBlock(productBlock, item.active_actions);
		}
	}
	
	function updateProductBlocks(json){
		json.forEach(async function(item){
			let productBlocks = $('.product__item__reloadable[data-product-id=\''+ item.product_id +'\']');
			if (productBlocks.length > 0){
				productBlocks.each(function( index ){ updateProductBlock($(this), item); });
			}
		});
	}
	
	$(document).ready(function(){
		var productsOnPage = [];
		$('.product__item__reloadable').each(function(index, elem){
			let productBlock = $(this);
			
			if (productBlock.attr('data-product-id') !== null){
				productsOnPage.push(productBlock.attr('data-product-id'));
			}						
		});
		
		<? if (ADD_METRICS_TO_FRONT) { ?>	
			if (productsOnPage.length > 0){
				$.ajax({
					url: "index.php?route=product/product/getProductsArrayDataJSON",
					type: 'POST',
					async: true,
					data: {
						x: productsOnPage
					},
					dataType: 'json',
					success: function(json){					
						updateProductBlocks(json);
					},
					error: function(error){
						console.log(error);
					}
				});
			}
		<? } ?>
	});
	
	<? if (ADD_METRICS_TO_FRONT) { ?>	
		$(document).ready(function(){
			setTimeout(function(){ $.get('index.php?route=kp/stat/online') }, 1500);
		});
	<? } ?>
</script>

<? /* PWA AND HTML 5 FUNCTIONS */ ?>

<? /* SHARE BUTTON */ ?>
<script>
	$('document').ready(function(){
		
		function _globalShare(){
			
			if ('share' in navigator) {
				
				window.dataLayer = window.dataLayer || [];	
				
				dataLayer.push({
					event: 'share',
					eventCategory: 'PWA',
					eventAction: 'click',
					eventLabel: '<?php echo $this->document->getTitle(); ?>'
				});
				
				navigator.share({
					title: document.title,
					text: '<?php echo $text_retranslate_36; ?>',
					url: location.href,
				});	
			}
			
		}
		
		if ('share' in navigator) {
			console.log('[PWA] Enabling sharing button');
			var _dbc = $('div.breadcrumbs');
			
			if (_dbc.length){
				_dbc.after('<span id="global-share-button" class="global_share_button"><i class="fas fa-share-alt"></i></span>');
				$('#global-share-button').on('click touch', _globalShare);			
			}
			
			} else {
			console.log('[PWA] No sharing possibility in navigator');
		}
	});
</script>
<? /* SHARE BUTTON */ ?>
<? /* VOICE SEARCH */ ?>
<script>
	
	$('document').ready(function(){
		
		window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
		
		if (window.SpeechRecognition) {
			console.log('Voice search supported, triggering');
			var current_input = $('#main-ajax-search').val();
			$('#main-ajax-search').after('<button class="voice_search_btn" id="voice-search-btn"><i class="fas fa-microphone"></i></button>');
			
			
			var recognition = new SpeechRecognition();
			recognition.interimResults = true;
			recognition.lang = 'ru-RU';
			recognition.addEventListener('result', _recognitionResultHandler);
			recognition.addEventListener('end', _recognitionEndHandler);
			recognition.addEventListener('error', _recognitionErrorHandler);
			recognition.addEventListener('nomatch', _recognitionNoMatchHandler);
			recognition.addEventListener('onnomatch', _recognitionNoMatchHandler);
			
			$('#voice-search-btn, #voice_modal .voice-modal__repeat').on('click touch', listenStart);
			
			$('.close_modals').on('click touch', function(){
				$('#voice_modal').hide(); 
				_recognitionEndHandler();        
			});
			
			function listenStart(e){
				e.preventDefault();
				$('#voice_modal').show();
				
				$('#voice_modal .voice-modal__say').html("<?php echo $text_retranslate_29; ?>");
				$('#voice_modal .voice-modal__say').show();               
				$('#voice_modal .body').removeClass('error_voice');
				$('#voice_modal .voice-modal__error').hide();
				
				
				$('#main-ajax-search').val('').attr("placeholder", "<?php echo $text_retranslate_29; ?>");
				$('#main-ajax-search').addClass('voice_input_active');
				$('#voice-search-btn').addClass('active');    
				recognition.start();
			}
			
			function _recognitionErrorHandler(e){
				console.log('_recognitionErrorHandler fired');
				
				$('#voice_modal .body').addClass('error_voice');
				$('#voice_modal .voice-modal__error').show();
				$('#voice_modal .voice-modal__say').hide();  
				
				$('#main-ajax-search').val(current_input);
				$('#voice-search-btn').removeClass('active');
				$('#main-ajax-search').removeClass('voice_input_active');
			}
			
			function _recognitionEndHandler(e){
				console.log('_recognitionEndHandler fired');
				$('#voice-search-btn').removeClass('active');
				$('#main-ajax-search').removeClass('voice_input_active').attr("placeholder", "<?php echo $text_retranslate_35; ?>");
				
			}
			
			function _recognitionNoMatchHandler(e){         
				console.log('_recognitionNoMatchHandler fired');
				$('#main-ajax-search').val(current_input); 
				
				$('#voice_modal .body').addClass('error_voice');
				$('#voice_modal .voice-modal__error').show();
				$('#voice_modal .voice-modal__say').hide();              
			}
			
			
			function _recognitionResultHandler(e) {		
				console.log('_recognitionResultHandler fired');
				if (e.results.length){
					
					speechOutput = Array.from(e.results).map(function (result) { return result[0] }).map(function (result) { return result.transcript }).join('')
					
					$('#main-ajax-search').val(speechOutput);
					$('#voice_modal .voice-modal__text-recognize').html(speechOutput);   
					$('#voice_modal .voice-modal__say').hide();  
					if (e.results[0].isFinal) {
						$('#main-ajax-search-submit').trigger('click');
					} 
					
					} else {
					_recognitionNoMatchHandler(e);
				}
			}
			
			
			} else {
			console.log('Voice search not supported');
		}
		
	});
</script>
<? /* END VOICE SEARCH */ ?>

<script>
	window.addEventListener('beforeunload', function(event) {
	console.log('BeforeUnload fired');			
	if (NProgress instanceof Object){				
	NProgress.configure({ showSpinner: false });
	NProgress.start();
	NProgress.inc(0.1);
	setTimeout(function () {
	NProgress.inc(0.5);
	}, 100);
	setTimeout(function () {
	NProgress.done();
	$(".fade").removeClass("out");
	}, 1000);			
	}
	});
</script>
<script>	
	function openCart(e) {	
		if (NProgress instanceof Object){				
			NProgress.configure({ showSpinner: false });
			NProgress.start();
			NProgress.inc(0.4);
		}
		setTimeout(function(){
			checkButtonTrigger();
		},1000)
		
		$('#popup-cart').load('<?php echo $popupcart; ?>', function(){ $('#popup-cart-trigger').click(); if (NProgress instanceof Object){ NProgress.done();  $(".fade").removeClass("out"); } });	
	}
	$(document).ready(function () {
		if(document.documentElement.clientWidth < 1280) {};       
	}); 
</script>

<? if (ADMIN_SESSION_DETECTED) { ?>
	<?php if (!empty($admin_uri)) { ?>
		<script>
			$(document).ready(function(){
			$('h1.title').append('<span style="padding-left:10px; color:#e16a5d;"><a href="<?php echo $admin_uri; ?>" target="_blank"><i class="fa fa-edit"></i></a></span>');		
			});
		</script>
	<?php } ?>
<? } ?>

<? /* PWA AND HTML 5 FUNCTIONS END */ ?>
<script>
	window.___gcfg = {
		lang: '<?php echo $language_code; ?>'
	};
</script>
<? if (ADD_METRICS_TO_FRONT) { ?>
	<?php if ($this->config->get('config_metrika_counter')) { ?>
		<script type="text/javascript" >
			(function(m, e, t, r, i, k, a) {
				m[i] = m[i] || function() {
					(m[i].a = m[i].a || []).push(arguments)
				};
				m[i].l = 1 * new Date();
				k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
			})(window, document, "script", "https://cdn.jsdelivr.net/npm/yandex-metrica-watch/tag.js", "ym");
			ym(<?php echo $this->config->get('config_metrika_counter'); ?>, "init", {
				clickmap: <?php if ($this->config->get('config_clickmap_enable')) { ?>true<?php } else { ?>false<?php } ?>,
				trackLinks: <?php if ($this->config->get('config_clickmap_enable')) { ?>true<?php } else { ?>false<?php } ?>,
				accurateTrackBounce: <?php if ($this->config->get('config_clickmap_enable')) { ?>true<?php } else { ?>false<?php } ?>,
				webvisor: <?php if ($this->config->get('config_webvisor_enable')) { ?>true<?php } else { ?>false<?php } ?>,
				ecommerce: "yandexDataLayer"
			});</script> <noscript><div><img src="https://mc.yandex.ru/watch/<?php echo $this->config->get('config_metrika_counter'); ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<?php } ?>  
<? } ?>
</body>
</html>    	