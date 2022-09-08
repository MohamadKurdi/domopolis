<link rel="manifest" href="<?php echo HTTPS_SERVER; ?>manifest.json?rand=<?php echo mt_rand(0,999); ?>" />
		<script>
			if ("serviceWorker" in navigator) {
				if (navigator.serviceWorker.controller) {
					console.log("[PWA] active service worker found, no need to register");
					} else {
					navigator.serviceWorker
					.register("/admin/sw.js?v=101", {
						scope: "/admin/"
					})
					.then(function (reg) {
						console.log("[PWA] Service worker has been registered for scope: " + reg.scope);
					});
				}
			}
			
			let deferredPrompt = null;
			
			window.addEventListener('beforeinstallprompt', function(e) {			
				e.preventDefault(); 
				deferredPrompt = e;				
				console.log('[PWA] APP-ADMIN beforeinstallprompt fired')				
			});
			
			window.addEventListener('appinstalled', function(e) {
				window.dataLayer = window.dataLayer || [];				
			});			
			
			async function installPWA() {
				if (deferredPrompt) {
					deferredPrompt.prompt();
					console.log(deferredPrompt)
					deferredPrompt.userChoice.then(function(choiceResult){
						
						if (choiceResult.outcome === 'accepted') {
							console.log('[PWA] APP-ADMIN is installed');
							window.dataLayer = window.dataLayer || [];							
							} else {
							console.log('[PWA] APP-ADMIN is not installed');
							window.dataLayer = window.dataLayer || [];							
						}
						
						deferredPrompt = null;
						
					});
				}
			}
			
			/*Different check functions*/
			function isStandaloneAPP(){
				if (window.matchMedia('(display-mode: standalone)').matches) {
					console.log('[PWA] display-mode is standalone');
					return true;
				}
				
				if ('standalone' in navigator) {
					console.log('[PWA] display-mode is standalone');
					return true;
				}
				
				if (window.navigator.standalone === true) {
					console.log('[PWA] display-mode is standalone');
					return true;
				}
				
				console.log('[PWA] display-mode is not standalone');
				return false;
			}
			
			if (localStorageSupported()){
					
			}
			
			function localStorageSupported(){
				try {
					localStorage.setItem('lstest', 'lstest');
					localStorage.removeItem('lstest');
					return true;
					} catch(e) {
					return false;
				}
			}
		</script>
		
		
		