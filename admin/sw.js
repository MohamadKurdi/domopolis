importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.4.1/workbox-sw.js');

const OFFLINE_HTML = '/admin/offline.html';
const PRECACHE = [{url: OFFLINE_HTML, revision: '1001'}];

workbox.precaching.precacheAndRoute(PRECACHE);
workbox.navigationPreload.enable();

workbox.routing.registerRoute(
	/\.(?:css)$/,
	new workbox.strategies.StaleWhileRevalidate({
		cacheName: 'admin-css',
		plugins: [
		new workbox.expiration.ExpirationPlugin({                
			maxEntries: 100,
			purgeOnQuotaError: true,
		}),
		],
	})
	);

workbox.routing.registerRoute(
	/\.(?:js)$/,
	new workbox.strategies.StaleWhileRevalidate({
		cacheName: 'admin-js',
		plugins: [
		new workbox.expiration.ExpirationPlugin({                
			maxEntries: 100,
			purgeOnQuotaError: true,
		}),
		],
	})
	);

workbox.routing.registerRoute(
	/\.(?:woff|woff2|ttf|otf|eot)$/,
	new workbox.strategies.StaleWhileRevalidate({
		cacheName: 'admin-fonts',
		plugins: [
		new workbox.expiration.ExpirationPlugin({                
			maxEntries: 20,
			purgeOnQuotaError: true,
		}),
		],
	})
	);


workbox.routing.registerRoute(
	({url}) => url.origin === 'https://fonts.googleapis.com' ||
	url.origin === 'https://fonts.gstatic.com',
	new workbox.strategies.StaleWhileRevalidate({
		cacheName: 'admin-google-fonts',
		plugins: [
		new workbox.expiration.ExpirationPlugin({maxEntries: 20}),
		],
	}),
	);



workbox.routing.registerRoute(
	/\.(?:png|gif|jpg|jpeg|svg|webp|avif)$/,
	new workbox.strategies.StaleWhileRevalidate({
		cacheName: 'admin-images',
		plugins: [
		new workbox.expiration.ExpirationPlugin({                
			maxEntries: 200,
			purgeOnQuotaError: true,
		}),
		],
	})
	);

const htmlHandler = new workbox.strategies.NetworkOnly();
const navigationRoute = new workbox.routing.NavigationRoute(({ event }) => {
	const request = event.request;
	return htmlHandler
	.handle({
		event,
		request,
	})
	.catch(() =>
		caches.match(OFFLINE_HTML, {
			ignoreSearch: true,
		})
		);
});
workbox.routing.registerRoute(navigationRoute);