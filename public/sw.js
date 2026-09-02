const CACHE_NAME = 'jobsportal-pwa-v1';
const STATIC_ASSETS = [
  '/',
  '/manifest.json',
  '/css/bootstrap.min.css',
  '/css/main.css',
  '/css/font-awesome.css',
  '/css/apna-theme.css',
  '/images/pwa/icon-192.png',
  '/images/pwa/icon-512.png'
];

// Install Event
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(STATIC_ASSETS).catch(err => {
        console.warn('Some static assets failed to precache:', err);
      });
    }).then(() => self.skipWaiting())
  );
});

// Activate Event
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch Event (Network-First for dynamic pages, Cache-First for static assets)
self.addEventListener('fetch', event => {
  const request = event.request;
  
  // Skip non-GET and cross-origin non-http requests
  if (request.method !== 'GET' || !request.url.startsWith('http')) {
    return;
  }

  // Admin and API requests always bypass cache
  if (request.url.includes('/admin') || request.url.includes('/api/')) {
    return;
  }

  const url = new URL(request.url);
  const isStaticAsset = url.pathname.match(/\.(css|js|woff2|woff|ttf|png|jpg|jpeg|svg|webp|ico)$/i);

  if (isStaticAsset) {
    // Cache First for static media
    event.respondWith(
      caches.match(request).then(cachedResponse => {
        if (cachedResponse) {
          return cachedResponse;
        }
        return fetch(request).then(networkResponse => {
          if (networkResponse && networkResponse.status === 200) {
            const responseClone = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => cache.put(request, responseClone));
          }
          return networkResponse;
        });
      })
    );
  } else {
    // Network First for HTML pages
    event.respondWith(
      fetch(request)
        .then(networkResponse => {
          if (networkResponse && networkResponse.status === 200) {
            const responseClone = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => cache.put(request, responseClone));
          }
          return networkResponse;
        })
        .catch(() => {
          return caches.match(request).then(cached => cached || caches.match('/'));
        })
    );
  }
});
