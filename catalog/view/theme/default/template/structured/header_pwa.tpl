<link rel="manifest" href="/manifest.json?rand=<?php echo mt_rand(0, 1000); ?>">
<script>
	function pushPWAEvent(eventAction){
		window.dataLayer = window.dataLayer || [];	
		
		dataLayer.push({
			event: 'PWA',
			eventCategory: 'PWA',
			eventAction: eventAction
		});
	}
	
	if ("Notification" in window) {
		console.log("[PWA] The Notifications API is supported");
	}
	
	if (Notification.permission === "granted") {
		console.log("[PWA] The user already accepted");
	}
	
	/* проверяем, ведроид ли это, чтоб не показывать на айфонах линк на гугл плей*/
	function isIphone(){		
		var user_agent = navigator.userAgent.toLowerCase();
		return ((user_agent.indexOf("iphone") > -1) || (user_agent.indexOf("ipad") > -1));				
	}
	
	/* Узнаем ширину экрана пользователя */
	function getUserWindowWidth(){
		return (window.innerWidth || document.body.clientWidth);
	}
	
	/* проверяем, маленький ли экранчик */
	function isSmallScreen(){
		return (getUserWindowWidth() <= 480);
	}
	
	function sendSimpleXHRWithCallback(uri, done){
		let xhr = new XMLHttpRequest();
		xhr.open('GET', uri);
		xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		
		xhr.onload = function() {
			console.log(xhr.response);
			done(JSON.parse(xhr.response));
		}
		
		xhr.send();
	}
	
	function sendSimpleXHR(uri){
		let xhr = new XMLHttpRequest();
		xhr.open('GET', uri);
		xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		
		xhr.onload = function() {
			console.log(xhr.response);
		}
		
		xhr.send();
	}
	
	function sendInstallEvent(){	
		console.log('[PWA] Sending PWA install event to engine');				
		sendSimpleXHR('<?php echo $pwa_spi_href; ?>');			
	}

	function getPWAKeys(){	
		console.log('[PWA] Getting keys');				
		sendSimpleXHR('<?php echo $pwa_keys_href; ?>');			
	}	
	
	function setPWASession(t){
		console.log('[PWA] Sending PWA session event to engine');				
		sendSimpleXHR('<?php echo $pwa_sps_href; ?>');
	}
	
	if ("serviceWorker" in navigator) {
		console.log("[PWA] ServiceWorker is in navigator, continue");
		if (navigator.serviceWorker.controller) {
			console.log("[PWA] active service worker found, no need to register");
		} else {
			navigator.serviceWorker
			.register("/sw.js?v=110", {scope: "/"})
			.then(function (reg) {
				console.log("[PWA] Service worker has been registered for scope: " + reg.scope);
			});
		}
	}  else {
		console.log("[PWA] ServiceWorker NOT in navigator, bad luck");		
	}
	
	let deferredPrompt = null;
	
	window.addEventListener('beforeinstallprompt', function(e) {			
		e.preventDefault(); 
		window.deferredPrompt = e;		
		pushPWAEvent('beforeinstallprompt');
		console.log('[PWA] KP PWA APP beforeinstallprompt fired');			
		
		showInstallFooterBlock();
		showInstallListingBlock();
	});		
	
	function showPrompt(){		
		let promptEvent = window.deferredPrompt;
		if (promptEvent) {
			
			pushPWAEvent('pwainstallbuttonclicked');
			promptEvent.prompt();
			promptEvent.userChoice.then(function(choiceResult){
				
				if (choiceResult.outcome === 'accepted') {
					pushPWAEvent('pwainstall');
					sendInstallEvent();
					localStorage.setItem('pwaaccepted', 'true');								
					console.log('[PWA] KP PWA APP is installed');							
				} else {
					pushPWAEvent('beforeinstallpromptdeclined');
					localStorage.setItem('pwadeclined', 'true');
					console.log('[PWA] KP PWA APP is not installed');
				}
				
				promptEvent = null;
				
			});
		}		
	}
	
	/* Вешаем триггер установки pwa на кнопку  */
	function hangClickInstallEvent(elementID){
		let pwaInstallButton = document.getElementById(elementID);	
		
		if (pwaInstallButton != null){
			pwaInstallButton.addEventListener('click', async () => {
				showPrompt();
			});
		}
	}
	
	/* Проверить существование элемента и показать его, если существует  */
	function validateAndShowBlockByID(elementID){
		let elementBlock = document.getElementById(elementID);	
		
		if (elementBlock != null){
			elementBlock.style.display = 'block';
		}
	}
	
	/* Проверить существование элемента и скрыть его, если существует  */
	function validateAndHideBlockByID(elementID){
		let elementBlock = document.getElementById(elementID);	
		
		if (elementBlock != null){
			elementBlock.style.display = 'none';
		}
	}
	
	/* Триггер отправки в аналитику установки PWA приложения  */
	window.addEventListener('appinstalled', function(e) {
		pushPWAEvent('pwainstall');				
	});			
	
	/* Вешаем триггеры установки и отсылаем инфу о просмотре в аналитику */
	document.addEventListener("DOMContentLoaded", function() {		
		/* При загрузке покажем блоки, ведущие на play store всем, кроме айфонов*/
		if (!isIphone()){
			validateAndShowBlockByID('footer_app_google_play');
			validateAndShowBlockByID('listing_app_google_play');
		}
		
		hangClickInstallEvent('download_app');
		hangClickInstallEvent('footer_app_button');
		hangClickInstallEvent('listing_download_app');
		
		if (isTWAApp()){
			console.log('[PWA] sending pwapageview event');
			pushPWAEvent('twapageview');
			setPWASession(true);
		}
		
		if (isStandaloneAPP()){
			console.log('[PWA] sending pwapageview event');
			pushPWAEvent('pwapageview');
			setPWASession(true);
		}
	});
	
	/* Функция проверки запуска в режиме TWA / Android Native */
	function isTWAApp(){		
		if (document.referrer.includes('android-app://')) {
			console.log('[TWA] User agents contains TWA: android-app/TWA');
			return true;
		}

		if (navigator.userAgent.toLowerCase().includes('twa')){
			console.log('[TWA] User agents contains TWA: TWA');
			return true;
		}

		if (typeof twa !== 'undefined'){
			console.log('[TWA] TWA object is defined');
			return true;
		}

		if ('getLaunchingApp' in window && window.getLaunchingApp()){
			console.log('[TWA] window has getLaunchingApp');
			return true;
		}

		<?php if ($this->config->get('config_android_playstore_code')) { ?>
		if (window.navigator.userAgent.indexOf('<?php echo $this->config->get('config_android_playstore_code'); ?>') !== -1) {
			console.log('[TWA] User agents contains PS code: <?php echo $this->config->get('config_android_playstore_code'); ?>');
			return true;
		}
		<?php } ?>
		
		return false;
	}
	
	/* Функция проверки режима запуска приложения */
	function isStandaloneAPP(){
		if (window.matchMedia('(display-mode: standalone)').matches) {
			console.log('[PWA] display-mode is standalone: display-mode');
			return true;
		}
		
		if ('standalone' in navigator && window.navigator.standalone === true) {
			console.log('[PWA] display-mode is standalone:  window.navigator.standalone = true');
			return true;
		}
		
		console.log('[PWA] display-mode is not standalone');
		return false;
	}
	
	/* Отображение блока установки в футере */
	function showInstallFooterBlock(){
		
		/* Прячем блок с ссылкой на play store, только для мобильных */
		if (isSmallScreen()){
			validateAndHideBlockByID('footer_app_google_play');
		}
		
		/* Показываем блок установки, только для мобильных */
		if (isSmallScreen()){
			validateAndShowBlockByID('footer_app');
		}
		
		/* Показываем кнопку в блоке "наши приложения" */
		validateAndShowBlockByID('footer_app_button');
	}
	
	/* Отображение блока установки в каталоге */
	function showInstallListingBlock(){
		
		/* Прячем блок с ссылкой на play store */
		if (isSmallScreen()){
			validateAndHideBlockByID('listing_app_google_play');
		}
		
		/* Показываем блок установки */
		if (isSmallScreen()){
			validateAndShowBlockByID('listing_app');
		}
	}
	
	/*проверка поддержки localStorage*/
	function localStorageSupported(){
		try {
			localStorage.setItem('lstest', 'lstest');
			localStorage.removeItem('lstest');
			return true;
		} catch(e) {
			return false;
		}
	}

	function pushImageSupportToDataLayer(format, isSupported){
		window.dataLayer 		= window.dataLayer || [];

		dataLayer.push({
				event: 			'ImageFormatSupport',
				eventCategory: 	'PageSpeed',
				eventAction:    isSupported,
				eventLabel: 	format
			});
	}

	function checkAVIFSupport(){
			
		window.afivacceptable 	= false;

		var avif = new Image();

		avif.src =
		"data:image/avif;base64,AAAAIGZ0eXBhdmlmAAAAAGF2aWZtaWYxbWlhZk1BMUIAAADybWV0YQAAAAAAAAAoaGRscgAAAAAAAAAAcGljdAAAAAAAAAAAAAAAAGxpYmF2aWYAAAAADnBpdG0AAAAAAAEAAAAeaWxvYwAAAABEAAABAAEAAAABAAABGgAAAB0AAAAoaWluZgAAAAAAAQAAABppbmZlAgAAAAABAABhdjAxQ29sb3IAAAAAamlwcnAAAABLaXBjbwAAABRpc3BlAAAAAAAAAAIAAAACAAAAEHBpeGkAAAAAAwgICAAAAAxhdjFDgQ0MAAAAABNjb2xybmNseAACAAIAAYAAAAAXaXBtYQAAAAAAAAABAAEEAQKDBAAAACVtZGF0EgAKCBgANogQEAwgMg8f8D///8WfhwB8+ErK42A=";
		avif.onload = function () {
			console.log('AVIF is supported in browser!');
			window.afivacceptable = true;
			pushImageSupportToDataLayer('AVIF', 'true');
		};

		avif.onerror = function () {
			window.afivacceptable = false;
			pushImageSupportToDataLayer('AVIF', 'false');
		}
	}

	function checkWEBPSupport(){		
		window.webpacceptable 	= false;

		var webp = new Image();
		webp.src =
		"data:image/webp;base64,UklGRhoAAABXRUJQVlA4TA0AAAAvAAAAEAcQERGIiP4HAA==";
		webp.onload = function () {
			console.log('WEBP is supported in browser!');
			window.webpacceptable = true;
			pushImageSupportToDataLayer('WEBP', 'true');
		};

		webp.onerror = function () {
			window.webpacceptable = false;
			pushImageSupportToDataLayer('WEBP', 'false');
		}
	}

	checkAVIFSupport();
	checkWEBPSupport();
	getPWAKeys();
</script>