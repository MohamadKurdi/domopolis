<!--footer-->
<div class="subscribe_wrap">
	<div class="wrap ajax-module-reloadable" data-modpath="module/subscribe" data-reloadable-group="customer"></div>  
</div>	

    <?php if ($this->config->get('social_auth_google_app_id') && $this->config->get('social_auth_google_enable_sso_widget')) { ?> 
    <div class="hidden ajax-module-reloadable" data-modpath="api/google/widget" data-reloadable-group="customer"></div>  
    <?php } ?>
	
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/footer_pwainstall.tpl')); ?>

<footer>                    
    <div class="wrap">  
        <!--footer__medium-->
        <div class="footer__medium">
            <!--footer__contacts-->
            <div class="footer_copyright">
				<div class="logo_footer">
					<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 176 60"><defs><style>.cls-1{fill:#020608;}.cls-2{fill:#6a979b;}.cls-3{fill:#e7b650;}.cls-4{fill:#d8b6cb;}.cls-5{fill:#d26244;}.cls-6{fill:#fff;}</style></defs><title>Domopolis</title><path class="cls-1" d="M66.62,16.81H77.81c7.47,0,11.6,3.57,11.6,10s-4.13,9.94-11.6,9.94H66.62V16.81ZM77.81,35.22c6.37,0,9.94-3,9.94-8.44s-3.57-8.44-9.94-8.44H68.29V35.22Z"/><path class="cls-1" d="M91.26,26.72C91.26,20.37,96.69,16,104.7,16s13.45,4.33,13.45,10.68-5.4,10.64-13.45,10.64S91.26,33.06,91.26,26.72Zm25.22,0c0-5.44-4.75-9.15-11.78-9.15s-11.77,3.71-11.77,9.15,4.75,9.14,11.77,9.14,11.78-3.71,11.78-9.14Z"/><path class="cls-1" d="M120.28,16.82h2.58L133.5,35.11h0l10.65-18.29h2.57V36.73h-1.67V18.58H145L134.46,36.73h-1.92L122,18.58H122V36.73h-1.67V16.82Z"/><path class="cls-1" d="M148.89,26.79c0-6.34,5.44-10.67,13.45-10.67s13.45,4.33,13.45,10.67-5.41,10.64-13.45,10.64S148.89,33.13,148.89,26.79Zm25.23,0c0-5.44-4.75-9.15-11.78-9.15s-11.78,3.71-11.78,9.15,4.76,9.14,11.78,9.14S174.12,32.22,174.12,26.79Z"/><path class="cls-1" d="M66.62,39.46h13.9c4.45,0,7,2.38,7,6.06s-2.58,6-7,6H68.29v7.82H66.62V39.46ZM80.35,50c3.52,0,5.5-1.75,5.5-4.5s-2-4.53-5.5-4.53H68.29v9Z"/><path class="cls-1" d="M89.33,49.28c0-6.35,5.44-10.68,13.45-10.68s13.45,4.33,13.45,10.68-5.41,10.64-13.45,10.64-13.45-4.3-13.45-10.64Zm25.23,0c0-5.44-4.76-9.15-11.78-9.15S91,43.84,91,49.28s4.76,9.14,11.78,9.14S114.56,54.71,114.56,49.28Z"/><path class="cls-1" d="M118.06,39.45h1.67v18.4h13.56v1.5H118.06V39.45Z"/><path class="cls-1" d="M135.12,39.45h1.67v19.9h-1.67V39.45Z"/><path class="cls-1" d="M138.62,53.16l1.42-.85c.93,3.65,5.24,6.08,10.65,6.08,4.72,0,7.81-1.78,7.81-4.35,0-2-1.81-3.23-6.06-3.91l-5.69-.94c-4.5-.71-6.43-2.09-6.43-4.81,0-3.54,3.43-5.78,8.44-5.78,4.39,0,8.1,1.87,9.54,4.65l-1.33.93c-1.27-2.41-4.44-4-8.21-4-4,0-6.77,1.67-6.77,4.16,0,1.79,1.5,2.81,5.07,3.34l5.61.91c5.24.82,7.5,2.44,7.5,5.32,0,3.68-3.79,6.06-9.54,6.06-6,0-10.73-2.71-12-6.76Z"/><path class="cls-2" d="M26.82.46A26.31,26.31,0,0,0,.51,26.77V59.15H26.82Z"/><path class="cls-3" d="M26.82.46h0V59.15H53.14V32.86h0V26.77A26.31,26.31,0,0,0,26.82.46Z"/><rect class="cls-4" x="26.82" y="37.29" width="26.29" height="21.86"/><rect class="cls-5" x="0.52" y="47.99" width="26.29" height="11.16"/><rect class="cls-6" x="0.21" y="47.31" width="26.6" height="0.92"/><rect class="cls-6" x="13.2" y="3.42" width="0.92" height="21.54"/><polygon class="cls-2" points="13.66 21.07 8.7 26.77 18.63 26.77 13.66 21.07"/><path class="cls-6" d="M19.64,27.23h-12l6-6.86,6,6.86Zm-9.93-.92h7.9l-3.95-4.54Z"/><rect class="cls-5" x="10.42" y="41.25" width="6.52" height="6.52"/><path class="cls-6" d="M17.4,48.23H10V40.79H17.4Zm-6.52-.92h5.6V41.72h-5.6Z"/><rect class="cls-6" x="26.81" y="36.83" width="26.55" height="0.92"/><rect class="cls-6" x="26.34" y="0.08" width="0.92" height="59.44"/><polygon class="cls-4" points="43.34 37.29 36.72 37.29 37.85 24.39 42.19 24.39 43.34 37.29"/><path class="cls-6" d="M43.85,37.75H36.21l1.22-13.82h5.18Zm-6.63-.92h5.62l-1.07-12H38.28l-1.06,12Z"/><path class="cls-6" d="M16.94,46.85v-.92a1.81,1.81,0,1,0,0-3.62v-.92a2.73,2.73,0,0,1,0,5.46Z"/><path class="cls-6" d="M39.59,27.75l-.69-.61a6.14,6.14,0,0,0-.29-8.89l.66-.65A7,7,0,0,1,39.59,27.75Z"/><path class="cls-6" d="M39.41,18.41l-.6-.09a5.94,5.94,0,0,1-3.07-1.52h0a5.92,5.92,0,0,1-1.52-3.07l-.09-.61.61.09a6,6,0,0,1,3.07,1.52,5.94,5.94,0,0,1,1.52,3.07l.08.61Zm-4.1-4.11a4.65,4.65,0,0,0,1.08,1.84h0a4.68,4.68,0,0,0,1.84,1.09,4.75,4.75,0,0,0-1.08-1.85,4.77,4.77,0,0,0-1.84-1.08Z"/><path class="cls-6" d="M41.08,23.06l-.25-.56a6,6,0,0,1-.37-3.41,6,6,0,0,1,1.76-2.93l.47-.4.26.56a5.9,5.9,0,0,1,.36,3.4,6,6,0,0,1-1.76,2.94l-.47.4Zm1.25-5.67a4.77,4.77,0,0,0-1,1.9,4.66,4.66,0,0,0,.08,2.14,4.77,4.77,0,0,0,1-1.9,4.66,4.66,0,0,0-.08-2.14Z"/></svg>
				</div>
				

			
				<?php if ($config_google_merchant_id) { ?>
					<style type="text/css">
						#ratingBadgeContainer {
						    position: initial !important;
						    margin-top: 25px !important;
						    z-index: 0 !important;
						}
						#ratingBadgeContainer body{
							margin: 0 !important;
						}
						@media screen and (max-width: 1000px){
						#ratingBadgeContainer {
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
						</script>
					<?php } ?>
				<?php } ?>
			</div>
            <!--/footer__contacts-->
            <!--footer__links-->
            <div class="footer__links">
            	<!--footer__links-item-->
                <div class="footer__links-item">
                    <div class="footer__links-title"><?php echo $text_retranslate_13; ?></div>
                    <ul>
                        <li><a href="<?php echo $href_about; ?>"><?php echo $text_retranslate_14; ?></a></li>
                        <li><a href="<?php echo $href_faq; ?>"><?php echo $text_retranslate_15; ?></a></li>
                        <li><a href="<?php echo $href_contact; ?>"><?php echo $text_retranslate_16; ?></a></li>
                        <li><a href="<?php echo $href_sitemap; ?>"><?php echo $text_retranslate_18; ?></a></li>
					</ul>
				</div>
				<!--/footer__links-item-->
                <!--footer__links-item-->
                <div class="footer__links-item">
                    <div class="footer__links-title"><?php echo $text_retranslate_5; ?></div>
                    <ul>
                        <li><a href="<?php echo $href_how_order; ?>"><?php echo $text_retranslate_6; ?></a></li>
                        <li><a href="<?php echo $href_delivery; ?>"><?php echo $text_retranslate_7; ?></a></li>
                        <li><a href="<?php echo $href_payment; ?>"><?php echo $text_retranslate_8; ?></a></li>
                        <li><a href="<?php echo $href_return; ?>"><?php echo $text_retranslate_9; ?></a></li>
                        <?php if (!empty($text_cashback)) { ?>
						<li><a href="<?php echo $href_cashback; ?>"><?php echo $text_cashback; ?></a></li>
						<?php } ?>
						<?php if (!empty($markdown_link)) { ?>
                            <li><a href="<?php echo $markdown_link; ?>"><?php echo $text_retranslate_12; ?>  <sup class="text-danger" style="display: none;"><?php echo $markdown_total; ?></sup></a></li>
						<?php } ?>
						<li>
							<a href="<?php echo $href_contact_b2b?>"><?php echo $contact_b2b?></a>
						</li>
					</ul>
				</div>
                <!--/footer__links-item-->
                <!--footer__links-item-->
                <div class="footer__links-item">
                	<div class="footer__links-title">Графік роботи Call-Центру</div>
					<ul>
						<div class="footer__time-work">
							<span class="by_schedule">
								<?php echo $text_retranslate_1; ?><br />
								<b><?php echo $text_retranslate_2; ?></b><br />
								<?php echo $text_retranslate_3; ?> 
							</span>
							
						</div>
					</ul>
					
				</div>
				<!--/footer__links-item-->
				<div class="footer__links-item">
					<div class="footer__links-title">Ми приймаємо</div>
	                <ul class="footer__payments">
	                	<li>
                            <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
								<mask id="mask0_32_1245" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="38" height="38">
								<path d="M37.9624 0H0V37.9624H37.9624V0Z" fill="white"/>
								</mask>
								<g mask="url(#mask0_32_1245)">
								<path d="M24.1141 9.75977H13.8496V28.2033H24.1141V9.75977Z" fill="#FF5F00"/>
								<path d="M14.5006 18.9816C14.5006 15.2342 16.2603 11.9105 18.9649 9.75983C16.9772 8.19572 14.4681 7.25073 11.7308 7.25073C5.24632 7.25073 0 12.497 0 18.9816C0 25.4661 5.24632 30.7124 11.7308 30.7124C14.4681 30.7124 16.9772 29.7675 18.9649 28.2033C16.2603 26.0853 14.5006 22.729 14.5006 18.9816Z" fill="#EB001B"/>
								<path d="M37.963 18.9816C37.963 25.4661 32.7167 30.7124 26.2321 30.7124C23.4949 30.7124 20.9858 29.7675 18.998 28.2033C21.7353 26.0527 23.4623 22.729 23.4623 18.9816C23.4623 15.2342 21.7027 11.9105 18.998 9.75983C20.9858 8.19572 23.4949 7.25073 26.2321 7.25073C32.7167 7.25073 37.963 12.5296 37.963 18.9816Z" fill="#F79E1B"/>
								</g>
							</svg>
						</li>
						<li>
                            <svg width="80" height="50" viewBox="0 0 80 50" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M30.0717 34.8729L33.4931 15.2969H38.9658L35.5418 34.8729H30.0717ZM55.3138 15.7189C54.2298 15.3223 52.5307 14.8967 50.409 14.8967C45.0013 14.8967 41.1923 17.5518 41.1597 21.3571C41.1293 24.17 43.8792 25.7393 45.955 26.6756C48.0854 27.6354 48.8014 28.2472 48.7911 29.1039C48.7775 30.4162 47.09 31.0154 45.5168 31.0154C43.3266 31.0154 42.1629 30.7187 40.3656 29.9881L39.6601 29.677L38.8922 34.0592C40.1705 34.6059 42.5341 35.0791 44.9884 35.1037C50.7413 35.1037 54.4758 32.4789 54.5183 28.4152C54.5387 26.1882 53.0807 24.4937 49.9233 23.0964C48.0105 22.1908 46.839 21.5865 46.8514 20.6695C46.8514 19.8558 47.843 18.9857 49.9858 18.9857C51.7751 18.9586 53.0717 19.3391 54.0818 19.7357L54.5721 19.9616L55.3138 15.7189ZM69.3968 15.2966H65.1681C63.8582 15.2966 62.8777 15.6452 62.3025 16.92L54.1748 34.86H59.9216C59.9216 34.86 60.8609 32.4479 61.0735 31.9182C61.7015 31.9182 67.2842 31.9266 68.0823 31.9266C68.246 32.612 68.7481 34.86 68.7481 34.86H73.8263L69.3968 15.296V15.2966ZM62.6874 27.9376C63.1401 26.8097 64.8679 22.4652 64.8679 22.4652C64.8357 22.5173 65.3173 21.3318 65.5935 20.5968L65.9633 22.2846C65.9633 22.2846 67.0112 26.9575 67.2303 27.9373H62.6874V27.9376ZM25.4259 15.2966L20.068 28.6466L19.4973 25.9337C18.4997 22.8063 15.3921 19.418 11.918 17.7217L16.8171 34.8417L22.6074 34.8354L31.2232 15.2964L25.4259 15.2963" fill="#0E4595"/>
							<path d="M15.0685 15.2959H6.24378L6.17383 15.7032C13.0394 17.3236 17.582 21.2395 19.4682 25.9452L17.5493 16.9492C17.2181 15.7096 16.2573 15.3396 15.0687 15.2964" fill="#F2AE14"/>
							</svg>
						</li>
					</ul>

				</div>
			</div>
            <!--/footer__links-->
		</div>
        <!--/footer__medium-->
	</div>

	<div class="border-wrap">
		<div class="wrap">
			<div class="contact-footer">
				<div class="phone-footer">
					<ul>
						<li>
	                		<a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone) ? $phone : ''; ?>">
	                			<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M8.39027 10.0945C8.90582 10.4577 9.44632 10.7631 10.0035 10.9942C10.3494 11.1196 10.8922 10.7644 11.2914 10.5032C11.3912 10.4379 11.4819 10.3784 11.5584 10.3339L11.5835 10.3198C11.9595 10.1086 12.377 9.8742 12.9138 9.98723C13.3961 10.0863 15.0509 11.3903 15.5083 11.8525C15.8076 12.1497 15.9739 12.4633 15.9989 12.7852C16.0488 13.982 14.4439 15.3273 14.0614 15.5584C13.2465 16.1527 12.1571 16.1444 10.8433 15.5502C9.43801 14.9807 7.75829 13.7839 6.13679 12.3313C5.55634 11.8113 4.44166 10.7164 4.12444 10.3508C2.48631 8.57625 1.10595 6.66967 0.457348 5.12624C0.149678 4.47421 0 3.87169 0 3.33521C0 2.80698 0.149678 2.33652 0.440717 1.9321C0.615341 1.62671 2.02064 -0.0405155 3.25964 0.000753129C3.56731 0.0337668 3.8833 0.190585 4.19097 0.487716C4.65663 0.941664 5.99541 2.58413 6.09519 3.07109C6.20904 3.59574 5.97298 4.00988 5.7603 4.38299L5.74595 4.40818C5.69801 4.491 5.63303 4.58924 5.56198 4.69664C5.30024 5.0923 4.95627 5.61228 5.07988 5.94335C5.38838 6.70268 5.8291 7.4455 6.36046 8.13055C6.95016 8.83562 7.87471 9.73136 8.39027 10.0945Z" fill="#97B63C"/>
								</svg>
								<?php echo isset($phone) ? $phone : ''; ?>
							</a>
	                	</li>
	                	<?php if($phone2) { ?>
	                    	<li>
	                    		 <a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone2) ? $phone2 : ''; ?>">
	                    		 	<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 311.81 311.81" >
									    <circle fill="#E60000" stroke="none" cx="155.91" cy="155.91" r="155.91"></circle>
									    <path stroke="none" fill="#ffffff"
									          d="M157.13,242.31c-42.57.14-86.87-36.19-87.06-94.54C69.95,109.18,90.76,72,117.37,50c26-21.49,61.51-35.28,93.76-35.39,4.15,0,8.49.33,11.15,1.23-28.2,5.85-50.64,32.09-50.54,61.86a16.16,16.16,0,0,0,.19,2.52c47.18,11.49,68.6,40,68.73,79.35S209.69,242.13,157.13,242.31Z"></path>
									</svg>
									<?php echo isset($phone2) ? $phone2 : ''; ?>
								</a>
	                    	</li>
	                    <?php } ?>
	                    <?php if($phone3) { ?>
	                    	<li>
	                    		<a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone3) ? $phone3 : ''; ?>">
	                    			<svg id="Layer_1" width="16" height="16" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><defs><style>.cls-999{fill:#4a84c5;}</style></defs><title>Artboard 1</title><path class="cls-999" d="M16,12.4h0a2.09,2.09,0,0,0,.78-.16,2,2,0,0,0,.67-.45,2,2,0,0,0,.44-.67,2,2,0,0,0,.16-.79V2.74a2,2,0,0,0-.1-.85,1.93,1.93,0,0,0-.43-.73,2.15,2.15,0,0,0-.69-.5,2,2,0,0,0-1.66,0,2,2,0,0,0-.69.5,2.1,2.1,0,0,0-.44.73,2,2,0,0,0-.09.85v7.59a2.13,2.13,0,0,0,.15.79,2.19,2.19,0,0,0,.45.67,2,2,0,0,0,.67.45,2.09,2.09,0,0,0,.78.16h0Z"/><path class="cls-999" d="M11.18,15.91a2.12,2.12,0,0,1-.38.71,2.26,2.26,0,0,1-.64.5,2.07,2.07,0,0,1-.77.22,2,2,0,0,1-.8-.1L1.41,14.9a2.12,2.12,0,0,1-1.19-1A2.09,2.09,0,0,1,.1,12.3,2.07,2.07,0,0,1,2.68,11l7.18,2.35a2.06,2.06,0,0,1,1.32,2.6Z"/><path class="cls-999" d="M21.2,16.62a2.14,2.14,0,0,1-.39-.71,2.06,2.06,0,0,1,1.32-2.6L29.32,11A2.07,2.07,0,0,1,31.9,12.3a2.09,2.09,0,0,1-.12,1.57,2.12,2.12,0,0,1-1.19,1l-7.18,2.34a2,2,0,0,1-.8.1,2.12,2.12,0,0,1-.78-.22A2,2,0,0,1,21.2,16.62Z"/><path class="cls-999" d="M6.16,31.1a2.07,2.07,0,0,1-.46-2.89l4.44-6.12a2.06,2.06,0,0,1,.59-.55,2.27,2.27,0,0,1,.75-.28,2.16,2.16,0,0,1,1.54.37,2.09,2.09,0,0,1,.45,2.9L9,30.64a2,2,0,0,1-1.35.83,2.16,2.16,0,0,1-.8,0,2.21,2.21,0,0,1-.73-.34Z"/><path class="cls-999" d="M26.66,29.75a2.05,2.05,0,0,1-.82,1.35v0a2,2,0,0,1-.73.34,2.2,2.2,0,0,1-.81,0,2.07,2.07,0,0,1-.75-.28,2,2,0,0,1-.59-.54l-4.44-6.15a2.12,2.12,0,0,1-.34-.73,2.16,2.16,0,0,1,0-.8,2.05,2.05,0,0,1,.28-.76,2,2,0,0,1,2.08-1,2.1,2.1,0,0,1,.76.27,2.06,2.06,0,0,1,.59.55l4.43,6.12A2.06,2.06,0,0,1,26.66,29.75Z"/></svg>
									<?php echo isset($phone3) ? $phone3 : ''; ?>
								</a>
	                    	</li>
	                    <?php } ?>
	                    <?php if($phone4) { ?>
	                    	<li>
	                    		<a class="element-target-click-event" data-gtm-event="klik-nomer-telefona" data-ym-event="klik-nomer-telefona" href="tel:<?php echo isset($phone4) ? $phone4 : ''; ?>">
	                    			<svg width="16" height="16" viewBox="0 0 36 37" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M32.6617 8.4437C31.3526 6.34915 29.7163 4.64733 27.8836 3.33824C25.9854 2.02915 23.8908 1.04733 21.469 0.523696C17.5417 -0.458122 13.7454 5.97006e-05 10.0799 1.63642C8.05083 2.55279 6.2181 3.79642 4.71265 5.43279C2.74901 7.46188 1.37447 9.68733 0.523557 12.2401V12.3055C0.523557 12.371 0.458102 12.5019 0.458102 12.5673C-0.392807 15.5128 0.392648 17.0837 1.70174 15.7746C1.76719 15.7091 1.83265 15.6437 1.83265 15.5782L1.8981 15.5128C3.40356 13.6146 5.82537 12.0437 5.82537 12.0437C6.41446 11.651 7.06901 11.1928 7.72356 10.8655C8.50901 10.4073 9.29447 10.0146 10.0145 9.75279C10.0145 9.75279 10.669 9.42551 10.8654 8.50915V8.4437C10.9963 7.65824 11.8472 5.49824 14.2036 5.36733C14.9236 5.23642 15.709 5.36733 16.3636 5.6946C17.5417 6.21824 18.3926 7.33097 18.589 8.64006C18.7199 9.68733 18.4581 10.6691 17.9345 11.4546C17.9345 11.5201 17.869 11.5201 17.869 11.5855C17.869 11.5855 17.869 11.5855 17.869 11.651C17.4108 12.2401 16.8217 12.6982 16.0363 12.9601C15.5781 13.091 15.1199 13.2219 14.7272 13.2219C14.4654 13.2873 14.1381 13.2219 13.8763 13.1564C13.549 13.091 13.2217 12.9601 12.9599 12.8292C12.5017 12.6328 12.109 12.371 11.9781 12.2401C11.8472 12.1091 11.7817 12.1091 11.6508 12.0437C11.389 11.9782 11.1926 12.0437 11.0617 12.1091C10.9963 12.1746 10.9308 12.1746 10.8654 12.2401C10.2763 12.6982 9.75265 13.2219 9.22901 13.7455C7.85447 15.1855 6.74174 16.8219 6.2181 17.6073C5.95628 18.0655 5.69447 18.5237 5.43265 18.9819C5.17083 19.4401 4.97447 19.8982 4.7781 20.3564C4.25447 20.9455 3.92719 21.731 3.66537 22.5164C3.66537 22.5819 3.66537 22.5819 3.59992 22.6473C3.59992 22.7128 3.53447 22.7782 3.53447 22.7782C3.53447 22.8437 3.53447 22.9092 3.46901 22.9092C1.83265 28.5382 4.7781 31.811 7.26537 28.4073C7.39628 28.211 7.52719 27.9492 7.6581 27.7528C10.2763 23.2364 15.5781 19.9637 15.5781 19.9637C15.9054 19.7673 16.2326 19.571 16.5599 19.3746C16.5599 19.3746 16.5599 19.3746 16.6254 19.3091C17.5417 18.7855 18.4581 18.3273 19.4399 17.9346C20.2908 17.5419 21.2072 17.2146 21.9926 17.0182C21.9926 17.0182 22.909 16.7564 23.1054 15.7091C23.3017 14.9237 23.8254 13.7455 25.1345 13.1564C25.4617 13.0255 25.7236 12.8946 26.0508 12.8292C26.3781 12.7637 26.7708 12.6982 27.0981 12.7637C27.2945 12.7637 27.4908 12.7637 27.6872 12.8292C29.3236 13.1564 30.6326 14.4655 30.829 16.2328C31.0908 18.3928 29.5854 20.291 27.4254 20.5528C26.3781 20.6837 25.3963 20.4219 24.6108 19.8328C23.6945 19.4401 22.9745 19.7019 22.7126 19.8328C21.9272 20.291 21.1417 20.7492 20.4217 21.2728C19.0472 22.3201 17.9345 23.3673 17.5417 23.7601C16.6254 24.7419 15.7745 25.7891 15.0545 26.9019C14.989 26.9673 14.989 27.0328 14.9236 27.0982C14.9236 27.0982 14.9236 27.0982 14.9236 27.1637C14.2036 28.3419 13.3526 29.9128 12.6981 31.7455C12.6326 31.8764 12.5672 32.0073 12.5672 32.2037C11.8472 34.5601 13.4181 35.5419 15.1199 35.8691C15.1199 35.8691 16.4945 36.131 18.4581 36.0001C18.589 36.0001 18.6545 36.0001 18.7854 36.0001C19.309 35.9346 19.7672 35.8692 20.2908 35.8037C23.6945 35.2146 26.7054 33.7746 29.3236 31.4837C32.5963 28.5382 34.5599 24.9382 35.149 20.6182C35.869 16.1673 35.0181 12.1091 32.6617 8.4437Z" fill="#FFC40C"/>
									</svg>

									<?php echo isset($phone4) ? $phone4 : ''; ?>
								</a>
	                    	</li>
	                    <?php } ?>
	                    <?php if(IS_MOBILE_SESSION){ ?>
	                    	<li class="social_mob">
	                    		<a href="http://m.me/105857422278656" class="facebook" rel="noindex nofollow" alt="Facebook" title="Facebook">
									<svg width="20" height="20" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect width="40" height="40" rx="20" fill="#0B84EE"/>
										<path d="M21.6571 20.3648H25.3793L25.9637 16.5605H21.6563V14.4813C21.6563 12.901 22.1696 11.4996 23.6389 11.4996H26V8.17974C25.5852 8.12338 24.7078 8 23.05 8C19.5882 8 17.5587 9.8393 17.5587 14.0297V16.5605H14V20.3648H17.5587V30.821C18.2634 30.9276 18.9773 31 19.7101 31C20.3724 31 21.0189 30.9391 21.6571 30.8522V20.3648Z" fill="white"/>
									</svg>
								</a>
								<a class="viber" href="viber://pa?chatURI=domopolisua" rel="noindex nofollow" alt="Viber" title="Viber">
									<svg width="20" height="20" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect width="40" height="40" rx="20" fill="#7C509A"/>
										<path d="M26.2539 9.54591C22.4424 8.81803 18.4843 8.81803 14.6728 9.54591C12.9869 9.90986 10.8613 11.9479 10.4948 13.5493C9.83508 16.6792 9.83508 19.8819 10.4948 23.0118C10.9346 24.6131 13.0602 26.6512 14.6728 27.0152C14.7461 27.0152 14.8194 27.0879 14.8194 27.1607V31.7464C14.8194 31.9648 15.1126 32.1103 15.2592 31.892L17.4581 29.6355C17.4581 29.6355 19.2173 27.8158 19.5105 27.5247C19.5105 27.5247 19.5838 27.4519 19.6571 27.4519C21.856 27.5247 24.1283 27.3063 26.3272 26.9424C28.0131 26.5784 30.1387 24.5403 30.5052 22.939C31.1649 19.8091 31.1649 16.6064 30.5052 13.4765C30.0654 11.9479 27.9398 9.90986 26.2539 9.54591ZM26.3272 23.2302C25.9607 23.958 25.5209 24.5403 24.788 24.9043C24.5681 24.9771 24.3482 25.0499 24.1283 25.1227C23.8351 25.0499 23.6152 24.9771 23.3953 24.9043C21.0497 23.958 18.8508 22.6478 17.0916 20.7553C16.1387 19.6635 15.3325 18.4261 14.6728 17.1159C14.3796 16.4608 14.0864 15.8785 13.8665 15.2234C13.6466 14.6411 14.0131 14.0588 14.3796 13.6221C14.7461 13.1853 15.1859 12.8942 15.699 12.6758C16.0654 12.4575 16.4319 12.603 16.7251 12.8942C17.3115 13.6221 17.8979 14.35 18.3377 15.1506C18.6309 15.6601 18.5576 16.2425 18.0445 16.6064C17.8979 16.6792 17.8246 16.752 17.678 16.8976C17.6047 16.9703 17.4581 17.0431 17.3848 17.1887C17.2382 17.4071 17.2382 17.6254 17.3115 17.8438C17.8979 19.5179 18.9974 20.8281 20.6832 21.556C20.9764 21.7016 21.1963 21.7744 21.5628 21.7744C22.0759 21.7016 22.2958 21.1193 22.6623 20.8281C23.0288 20.537 23.4686 20.537 23.9084 20.7553C24.2749 20.9737 24.6414 21.2649 25.0811 21.556C25.4476 21.8472 25.8141 22.0655 26.1806 22.3567C26.4005 22.5023 26.4738 22.8662 26.3272 23.2302ZM23.2487 17.771C23.1021 17.771 23.1754 17.771 23.2487 17.771C22.9555 17.771 22.8822 17.6254 22.8089 17.4071C22.8089 17.2615 22.8089 17.0431 22.7356 16.8976C22.6623 16.6064 22.5157 16.3152 22.2225 16.0969C22.0759 16.0241 21.9293 15.9513 21.7827 15.8785C21.5628 15.8057 21.4162 15.8057 21.1963 15.8057C20.9764 15.7329 20.9031 15.5874 20.9031 15.369C20.9031 15.2234 21.123 15.0778 21.2696 15.0778C22.4424 15.1506 23.322 15.8057 23.4686 17.1887C23.4686 17.2615 23.4686 17.4071 23.4686 17.4799C23.4686 17.6254 23.3953 17.771 23.2487 17.771ZM22.5157 14.5683C22.1492 14.4227 21.7827 14.2772 21.3429 14.2044C21.1963 14.2044 20.9764 14.1316 20.8298 14.1316C20.6099 14.1316 20.4634 13.986 20.5366 13.7676C20.5366 13.5493 20.6832 13.4037 20.9031 13.4765C21.6361 13.5493 22.2958 13.6949 22.9555 13.986C24.2749 14.6411 25.0079 15.7329 25.2277 17.1887C25.2277 17.2615 25.2277 17.3343 25.2277 17.4071C25.2277 17.5526 25.2277 17.6982 25.2277 17.9166C25.2277 17.9894 25.2277 18.0622 25.2277 18.135C25.1545 18.4261 24.6414 18.4989 24.5681 18.135C24.5681 18.0622 24.4948 17.9166 24.4948 17.8438C24.4948 17.1887 24.3482 16.5336 24.055 15.9513C23.6152 15.2962 23.1021 14.8595 22.5157 14.5683ZM26.4738 18.9356C26.2539 18.9356 26.1073 18.7173 26.1073 18.4989C26.1073 18.0622 26.034 17.6254 25.9607 17.1887C25.6675 14.8595 23.7618 12.967 21.4895 12.603C21.123 12.5302 20.7565 12.5302 20.4634 12.4575C20.2435 12.4575 19.9503 12.4575 19.877 12.1663C19.8037 11.9479 20.0236 11.7296 20.2435 11.7296C20.3168 11.7296 20.3901 11.7296 20.3901 11.7296C23.3953 11.8024 20.5367 11.7296 20.3901 11.7296C23.4686 11.8024 26.034 13.8404 26.5471 16.8976C26.6204 17.4071 26.6937 17.9166 26.6937 18.4989C26.8403 18.7173 26.6937 18.9356 26.4738 18.9356Z" fill="white"/>
									</svg>
								</a>
								<a class="tg" href="https://t.me/domopolis_bot" rel="noindex nofollow" alt="Telegram" title="Telegram">
									<svg width="20" height="20" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect width="40" height="40" rx="20" fill="#27A6E5"/>
										<path d="M28 12.6022L24.9946 28.2923C24.9946 28.2923 24.5741 29.3801 23.4189 28.8584L16.4846 23.3526L16.4524 23.3364C17.3891 22.4654 24.6524 15.7027 24.9698 15.3961C25.4613 14.9214 25.1562 14.6387 24.5856 14.9974L13.8568 22.053L9.71764 20.6108C9.71764 20.6108 9.06626 20.3708 9.00359 19.8491C8.9401 19.3265 9.73908 19.0439 9.73908 19.0439L26.6131 12.1889C26.6131 12.1889 28 11.5579 28 12.6022Z" fill="#FEFEFE"/>
									</svg>
								</a>
	                    	</li>
	                    <?php } ?>
	                    <li>
							<a href="mailto:info@domopolis.ua">
								info@domopolis.ua
							</a>
						</li>
	                </ul>
				</div>
				<?php if(!IS_MOBILE_SESSION){ ?>
				<div class="social-footer">
					<ul>
						<?php if ($this->config->get('social_link_messenger_bot')) { ?>
							<li>
								<a href="<?php echo $this->config->get('social_link_messenger_bot'); ?>" class="facebook" title="Facebook" rel="noindex nofollow">		
									<img src="/catalog/view/theme/dp/img/Facebook_Messenger.png" alt="" loading="lazy">
								</a>
							</li> 
						<? } ?>

						<?php if ($this->config->get('social_link_viber_bot')) { ?>
							<li>
								<a class="viber" href="<?php echo $this->config->get('social_link_viber_bot'); ?>" title="Viber" rel="noindex nofollow">
									<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect width="40" height="40" rx="20" fill="#7C509A"/>
										<path d="M26.2539 9.54591C22.4424 8.81803 18.4843 8.81803 14.6728 9.54591C12.9869 9.90986 10.8613 11.9479 10.4948 13.5493C9.83508 16.6792 9.83508 19.8819 10.4948 23.0118C10.9346 24.6131 13.0602 26.6512 14.6728 27.0152C14.7461 27.0152 14.8194 27.0879 14.8194 27.1607V31.7464C14.8194 31.9648 15.1126 32.1103 15.2592 31.892L17.4581 29.6355C17.4581 29.6355 19.2173 27.8158 19.5105 27.5247C19.5105 27.5247 19.5838 27.4519 19.6571 27.4519C21.856 27.5247 24.1283 27.3063 26.3272 26.9424C28.0131 26.5784 30.1387 24.5403 30.5052 22.939C31.1649 19.8091 31.1649 16.6064 30.5052 13.4765C30.0654 11.9479 27.9398 9.90986 26.2539 9.54591ZM26.3272 23.2302C25.9607 23.958 25.5209 24.5403 24.788 24.9043C24.5681 24.9771 24.3482 25.0499 24.1283 25.1227C23.8351 25.0499 23.6152 24.9771 23.3953 24.9043C21.0497 23.958 18.8508 22.6478 17.0916 20.7553C16.1387 19.6635 15.3325 18.4261 14.6728 17.1159C14.3796 16.4608 14.0864 15.8785 13.8665 15.2234C13.6466 14.6411 14.0131 14.0588 14.3796 13.6221C14.7461 13.1853 15.1859 12.8942 15.699 12.6758C16.0654 12.4575 16.4319 12.603 16.7251 12.8942C17.3115 13.6221 17.8979 14.35 18.3377 15.1506C18.6309 15.6601 18.5576 16.2425 18.0445 16.6064C17.8979 16.6792 17.8246 16.752 17.678 16.8976C17.6047 16.9703 17.4581 17.0431 17.3848 17.1887C17.2382 17.4071 17.2382 17.6254 17.3115 17.8438C17.8979 19.5179 18.9974 20.8281 20.6832 21.556C20.9764 21.7016 21.1963 21.7744 21.5628 21.7744C22.0759 21.7016 22.2958 21.1193 22.6623 20.8281C23.0288 20.537 23.4686 20.537 23.9084 20.7553C24.2749 20.9737 24.6414 21.2649 25.0811 21.556C25.4476 21.8472 25.8141 22.0655 26.1806 22.3567C26.4005 22.5023 26.4738 22.8662 26.3272 23.2302ZM23.2487 17.771C23.1021 17.771 23.1754 17.771 23.2487 17.771C22.9555 17.771 22.8822 17.6254 22.8089 17.4071C22.8089 17.2615 22.8089 17.0431 22.7356 16.8976C22.6623 16.6064 22.5157 16.3152 22.2225 16.0969C22.0759 16.0241 21.9293 15.9513 21.7827 15.8785C21.5628 15.8057 21.4162 15.8057 21.1963 15.8057C20.9764 15.7329 20.9031 15.5874 20.9031 15.369C20.9031 15.2234 21.123 15.0778 21.2696 15.0778C22.4424 15.1506 23.322 15.8057 23.4686 17.1887C23.4686 17.2615 23.4686 17.4071 23.4686 17.4799C23.4686 17.6254 23.3953 17.771 23.2487 17.771ZM22.5157 14.5683C22.1492 14.4227 21.7827 14.2772 21.3429 14.2044C21.1963 14.2044 20.9764 14.1316 20.8298 14.1316C20.6099 14.1316 20.4634 13.986 20.5366 13.7676C20.5366 13.5493 20.6832 13.4037 20.9031 13.4765C21.6361 13.5493 22.2958 13.6949 22.9555 13.986C24.2749 14.6411 25.0079 15.7329 25.2277 17.1887C25.2277 17.2615 25.2277 17.3343 25.2277 17.4071C25.2277 17.5526 25.2277 17.6982 25.2277 17.9166C25.2277 17.9894 25.2277 18.0622 25.2277 18.135C25.1545 18.4261 24.6414 18.4989 24.5681 18.135C24.5681 18.0622 24.4948 17.9166 24.4948 17.8438C24.4948 17.1887 24.3482 16.5336 24.055 15.9513C23.6152 15.2962 23.1021 14.8595 22.5157 14.5683ZM26.4738 18.9356C26.2539 18.9356 26.1073 18.7173 26.1073 18.4989C26.1073 18.0622 26.034 17.6254 25.9607 17.1887C25.6675 14.8595 23.7618 12.967 21.4895 12.603C21.123 12.5302 20.7565 12.5302 20.4634 12.4575C20.2435 12.4575 19.9503 12.4575 19.877 12.1663C19.8037 11.9479 20.0236 11.7296 20.2435 11.7296C20.3168 11.7296 20.3901 11.7296 20.3901 11.7296C23.3953 11.8024 20.5367 11.7296 20.3901 11.7296C23.4686 11.8024 26.034 13.8404 26.5471 16.8976C26.6204 17.4071 26.6937 17.9166 26.6937 18.4989C26.8403 18.7173 26.6937 18.9356 26.4738 18.9356Z" fill="white"/>
									</svg>
								</a>
							</li>
						<? } ?>

						<?php if ($this->config->get('social_link_telegram_bot')) { ?>
							<li>
								<a class="tg" href="<?php echo $this->config->get('social_link_telegram_bot'); ?>" title="Telegram" rel="noindex nofollow">
									<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect width="40" height="40" rx="20" fill="#27A6E5"/>
										<path d="M28 12.6022L24.9946 28.2923C24.9946 28.2923 24.5741 29.3801 23.4189 28.8584L16.4846 23.3526L16.4524 23.3364C17.3891 22.4654 24.6524 15.7027 24.9698 15.3961C25.4613 14.9214 25.1562 14.6387 24.5856 14.9974L13.8568 22.053L9.71764 20.6108C9.71764 20.6108 9.06626 20.3708 9.00359 19.8491C8.9401 19.3265 9.73908 19.0439 9.73908 19.0439L26.6131 12.1889C26.6131 12.1889 28 11.5579 28 12.6022Z" fill="#FEFEFE"/>
									</svg>
								</a>
							</li>
						<?php } ?>

						<?php if ($this->config->get('social_link_whatsapp_bot')) { ?>
							<li>
								<a class="wa" href="<?php echo $this->config->get('social_link_whatsapp_bot'); ?>" rel="noindex nofollow">
									<svg width="17" height="17" viewBox="0 0 17 17" fill="none"
									xmlns="http://www.w3.org/2000/svg">
										<path d="M13.9451 3.04687C12.4634 1.57031 10.4878 0.75 8.40244 0.75C4.06707 0.75 0.554877 4.25 0.554877 8.57031C0.554877 9.9375 0.939024 11.3047 1.59756 12.4531L0.5 16.5L4.67073 15.4062C5.82317 16.0078 7.08536 16.3359 8.40244 16.3359C12.7378 16.3359 16.25 12.8359 16.25 8.51563C16.1951 6.49219 15.4268 4.52344 13.9451 3.04687ZM12.189 11.3594C12.0244 11.7969 11.2561 12.2344 10.872 12.2891C10.5427 12.3437 10.1037 12.3438 9.66463 12.2344C9.39024 12.125 9.0061 12.0156 8.56707 11.7969C6.59146 10.9766 5.32927 9.00781 5.21951 8.84375C5.10976 8.73437 4.39634 7.80469 4.39634 6.82031C4.39634 5.83594 4.89024 5.39844 5.05488 5.17969C5.21951 4.96094 5.43902 4.96094 5.60366 4.96094C5.71341 4.96094 5.87805 4.96094 5.9878 4.96094C6.09756 4.96094 6.26219 4.90625 6.42683 5.28906C6.59146 5.67187 6.97561 6.65625 7.03049 6.71094C7.08536 6.82031 7.08536 6.92969 7.03049 7.03906C6.97561 7.14844 6.92073 7.25781 6.81097 7.36719C6.70122 7.47656 6.59146 7.64062 6.53658 7.69531C6.42683 7.80469 6.31707 7.91406 6.42683 8.07812C6.53659 8.29687 6.92073 8.89844 7.52439 9.44531C8.29268 10.1016 8.89634 10.3203 9.11585 10.4297C9.33537 10.5391 9.44512 10.4844 9.55488 10.375C9.66463 10.2656 10.0488 9.82812 10.1585 9.60938C10.2683 9.39062 10.4329 9.44531 10.5976 9.5C10.7622 9.55469 11.75 10.0469 11.9146 10.1562C12.1341 10.2656 12.2439 10.3203 12.2988 10.375C12.3537 10.5391 12.3537 10.9219 12.189 11.3594Z"
									fill="white"/>
									</svg>
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>

	<div class="wrap">
		<div class="copyright-wrap">
			<p>&copy;<?php echo $this->config->get('config_name'); ?> 2010 - <?php echo date('Y'); ?> </p>
			<ul>
				<li>
					<a href="<?php echo $href_personaldata; ?>" title="<?php echo $text_retranslate_20; ?>">
						<?php echo $text_retranslate_20; ?>
					</a>
				</li>
                <li>
                	<a href="<?php echo $href_polzovatelskoe; ?>" title="<?php echo $text_retranslate_19; ?>">
                		<?php echo $text_retranslate_19; ?>
                	</a>
                </li>
			</ul>
		</div>
	</div>
	
</footer>
<div id="top" class="hide">
	<span class="icon_top">
		<svg width="18" height="38" viewBox="0 0 18 38" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M7.97248 0.435387C7.97204 0.435833 7.97153 0.436203 7.9711 0.436722L0.422705 8.19287C-0.142771 8.77393 -0.140667 9.71376 0.42764 10.2922C0.995874 10.8705 1.91496 10.8682 2.48051 10.2872L7.54839 5.0797L7.54839 36.5156C7.54839 37.3354 8.19827 38 9 38C9.80173 38 10.4516 37.3354 10.4516 36.5156L10.4516 5.07977L15.5195 10.2871C16.085 10.8682 17.0041 10.8704 17.5724 10.2921C18.1407 9.71369 18.1427 8.77378 17.5773 8.1928L10.0289 0.436649C10.0285 0.436203 10.028 0.435829 10.0275 0.43531C9.46009 -0.146046 8.53802 -0.144188 7.97248 0.435387Z" fill="#121415"/>
		</svg>
	</span>
</div>
<!--/footer-->
<div id="main-overlay-popup" class="overlay_popup"></div>
<div id="popup-cart-trigger" class="do-popup-element" data-target="popup-cart" class="hidden"></div>
<div id="popup-cart" class="popup-form" style="display:none;"></div>


<div id="popup_autorization-trigger" class="do-popup-element" data-target="popup_autorization" class="hidden"></div>
<div id="popup_autorization"  class="popup-form"></div>

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
	background-image: url(/catalog/view/theme/dp/img/close-modal.svg);
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
	color: #e25c1d;
    }
    #show_register_modal p a:hover{
	text-decoration: underline;
    }
</style>
<div id="show_register_modal" class="popup-form" style="display: none;">
    <div class="wrap-modal">
        <div class="body">
            <button class="close_modals"></button>
            <p><?php echo $text_retranslate_wishlist_register; ?></p>
		</div>  
	</div>
</div>



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
            height = $('#mmenu_home').outerHeight(),
            width = $('.top-menu .wrap').width();
            
            
            $submenu.css({
                display: "block",
                top: -1,
                width: width - 203,
                left: 203,
                height: height
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

    	// menu first li open
    	$('.menu-horizontal .catalog-li .menu-list > .level1').first().addClass('open_menu');
    	
    	$('body .menu-horizontal .catalog__btn, #mmenu_home').parent().on('mouseover', function(e){
	   		$('body .menu-horizontal .catalog__btn').find(".burger").addClass("open");
	        $('body .menu-horizontal .catalog__btn').parent().find(".menu-list").addClass("open");
	        $('#mmenu_home').addClass('open_main_menu');
	        $(".overlay_popup").show().css("z-index", "101");
	        $(".tab-product-wrap.show").addClass("open_menu");
	        return false;
	    }).on('mouseout', function(e){      
	    	$('body .menu-horizontal .catalog__btn').find(".burger").removeClass("open");
	        $('body .menu-horizontal .catalog__btn').parent().find(".menu-list").removeClass("open");
	        $(".overlay_popup").hide().css("z-index", "999");
	        $('#mmenu_home').removeClass('open_main_menu')
	        $(".tab-product-wrap.show").removeClass("open_menu");
	    });  
	  
	} 
	// else {
	// 	$(".catalog__btn").click(function () {
	//         $(this).find(".burger").toggleClass("open");
	//         $(this).parent().find(".menu-list").toggleClass("open");
	//         if ($(".menu-list").hasClass("open")) {
	//             $(".overlay_popup").show().css("z-index", "101");
	//             $(".tab-product-wrap.show").css("background", "rgba(0, 0, 0, 0.1)");
	//         } else {
	//             $(".overlay_popup").hide().css("z-index", "999");
	//             $(".tab-product-wrap.show").css("background", "rgba(255, 255, 255, 1)");
	//         }
	//         return false;
	//     });
	// }
   
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

<? if (!CRAWLER_SESSION_DETECTED) { ?>
	<?php echo ($google_analytics); ?>
	<?php if ($config_vk_enable_pixel) { ?>
		<? echo $config_vk_pixel_header; ?>
	<?php } ?>
<? } ?>


<script>
	function reloadAjaxReloadableGroupWithOutParameters(group, modules){		
		if (modules.length > 0){
			
			let uri = '<?php echo $ajax; ?>?modpath=' + modules.join(';') + '&group=' + group;
			
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
		let uri = '<?php echo $ajax; ?>?modpath=' + elem.attr('data-modpath');
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
				console.log('Finished loading ' + elem.attr('data-modpath'));	
				console.log(typeof window[elem.attr('data-afterload')]);			
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

		if(html.length > 0) {
			productBlock.find('.product__label').prepend(html);
		} 		
	}

	<?php if (ADMIN_SESSION_DETECTED && !empty($admin_uri) && $this->config->get('config_enable_amazon_specific_modes')) { ?>
		function updateAmazonDataInBlock(productBlock, amazon_data){
			let html = '';

			html += '<div style="margin-bottom:10px; margin-top:10px; font-weight:700; color:#e16a5d">';
			html += '<a style="padding:3px 6px; border-radius:4px; background-color:#e16a5d; color:#fff" href="'+ amazon_data.amazon_product_link +'" target="_blank">'+ amazon_data.asin +'</a>';

			<?php if ($this->config->get('config_product_quality_groups_enable')) { ?>
				if (amazon_data.product_group_id){
					html += ' <span style="float:right; margin-left:5px; color:#' + amazon_data.product_quality_group.text_color + '; padding:3px 6px; border-radius:4px; background-color:#' + amazon_data.product_quality_group.bg_color + ';">';
					html += '<i class="fas '+ amazon_data.product_quality_group.fa_icon +'"></i>';
					html += '</span>';
				}
			<?php } ?>

			if (amazon_data.amazon_seller_quality){
				html += ' <span style="float:right; color:#fff; padding:3px 6px; border-radius:4px; background-color:#4EA24E; margin-left:5px;">' + amazon_data.amazon_seller_quality + '</span>';
			}

			html += ' <span style="float:right; color:#fff; padding:3px 6px; border-radius:4px; background-color:#e16a5d;">' + amazon_data.amazon_offers_type + '</span>';
			html += '</div>';		

			html += '<div style="margin-bottom:5px; margin-top:5px; font-weight:700;">';
			html += '<span style="background-color:#FF3A44; color:#fff; padding:5px 7px; border-radius:4px; font-size:11px;">' + amazon_data.amazon_best_price + '</span>';
			html += '<span style="background-color:#e16a5d; color:#fff; padding:5px 7px; border-radius:4px; margin-left:10px; font-size:11px;">' + amazon_data.costprice + '</span>';
			html += '<span style="float:right; color:#fff; padding:5px 7px; border-radius:4px; background-color:#000;">' + amazon_data.profitability + '%</span>';
			html += '</div>';			
			productBlock.find('.product__bottom_new').after(html);
		}
	<?php } ?>	

	function updatePriceInBlock(productBlock, price){
		
	}
	
	function updateProductBlock(productBlock, item){
		if (item.active_coupon){
			updateActiveCouponInBlock(productBlock, item.active_coupon);
		}
		
		if (item.active_actions){
			updateActiveActionsInBlock(productBlock, item.active_actions);
		}

		<?php if (ADMIN_SESSION_DETECTED && !empty($admin_uri) && $this->config->get('config_enable_amazon_specific_modes')) { ?>
			if (item.amazon_data){
				updateAmazonDataInBlock(productBlock, item.amazon_data);
			}
		<?php } ?>
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
		
		<? if (!CRAWLER_SESSION_DETECTED) { ?>	
			if (productsOnPage.length > 0){
				$.ajax({
					url: "<?php echo $ajax_product; ?>",
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
	
	<? if (!CRAWLER_SESSION_DETECTED) { ?>	
		$(document).ready(function(){
			setTimeout(function(){ $.get('<?php echo $stat_uri; ?>') }, 1500);
		});
	<? } ?>
</script>

<? /* PWA AND HTML 5 FUNCTIONS */ ?>

<? /* SHARE BUTTON */ ?>
<script>
	$('document').ready(function(){
    if (window.location.href !== 'https://domopolis.ua/account/login') {
        
        // Ваш скрипт тут
        function _globalShare(){
            if ('share' in navigator) {
                window.dataLayer = window.dataLayer || [];    
                dataLayer.push({
                    event: 'share',
                    eventCategory: 'PWA',
                    eventAction: 'click',
                    eventLabel: '<?php echo prepareEcommString($this->document->getTitle()); ?>'
                });
                navigator.share({
                    title: document.title,                
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
    } else {
        console.log('[PWA] Skipping script execution on login page');
    }
});
</script>
<? /* SHARE BUTTON */ ?>


<? /* NPROGRESS */ ?>
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
	 
</script>

<?php if ($this->config->get('config_helpcrunch_enable')) { ?>
	<span class="ajax-module-reloadable" data-modpath="api/helpcrunch" data-reloadable-group="customer"></span>
<?php } ?>

<? if (ADMIN_SESSION_DETECTED) { ?>
	<?php if (!empty($admin_uri)) { ?>
		<script>
			$(document).ready(function(){
				var html = '';
				html += '<div style="padding:10px 0px; margin-bottom:10px; font-size:24px; color:#e16a5d; border-bottom:1px solid grey;">';
				html += '<a href="<?php echo $admin_uri; ?>" target="_blank"><i class="fa fa-edit"></i></a>';
				<?php if (!empty($qrcode)) { ?>
					html += '<a style="margin-left:20px" href="<?php echo $qrcode; ?>" target="_blank"><i class="fa fa-qrcode"></i></a>';
				<?php } ?>
				<?php if (!empty($admin_product_info['amazon_product_link'])) { ?>
					html += '<a style="margin-left:20px" href="<?php echo $admin_product_info['amazon_product_link']; ?>" target="_blank"><i class="fab fa-amazon"></i></a>';
				<?php } ?>
				<?php if (!empty($admin_product_info['asin'])) { ?>
					html += '<span style="margin-left:20px; padding:5px 10px; font-weight:700; border-radius:4px; color:#FFF; background-color:#e16a5d"><?php echo $admin_product_info['asin']; ?></span>';
				<?php } ?>
				<?php if ($this->config->get('config_product_quality_groups_enable') && !empty($admin_product_info)){ ?>
					html += '</div><div style="padding:10px 0px; margin-bottom:10px; font-size:24px; color:#e16a5d; border-bottom:1px solid grey;">';
				<?php } ?>
				<?php if ($this->config->get('config_product_quality_groups_enable') && !empty($admin_product_info)){ ?>
					<?php if (!empty($admin_product_info['product_quality_group'])) { ?>
						html += '<span style="padding:5px 10px; font-weight:700; border-radius:4px; color:#<?php echo $admin_product_info['product_quality_group']['text_color']; ?>; background-color:#<?php echo $admin_product_info['product_quality_group']['bg_color']; ?>"><i class="fas <?php echo $admin_product_info['product_quality_group']['fa_icon']; ?>"></i> <?php echo $admin_product_info['product_quality_group']['name']; ?></span>';
					<?php } else { ?>
						html += '<span style="padding:5px 10px; font-weight:700; border-radius:4px; color:#FFF; background-color:#000"><i class="fas fa-times-circle"></i></span>';
					<?php } ?>
				<?php } ?>
				<?php if (!empty($admin_product_info['amazon_offers_type'])) { ?>
					html += '<span style="margin-left:20px; padding:5px 10px; font-weight:700; border-radius:4px;  background-color:#e16a5d; color:#FFF;">OFRS: <?php echo $admin_product_info['amazon_offers_type']; ?></span>';
				<?php } ?>
				<?php if (!empty($admin_product_info['amazon_seller_quality'])) { ?>
					html += '<span style="margin-left:20px; padding:5px 10px; font-weight:700; border-radius:4px; background-color:#4EA24E; color:#FFF;">SLR: <?php echo $admin_product_info['amazon_seller_quality']; ?></span>';
				<?php } ?>

				html += '</div>';
				$('h1.title').before(html);		
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
<? if (!CRAWLER_SESSION_DETECTED) { ?>
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