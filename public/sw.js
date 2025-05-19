const CACHE_NAME = "offline-v1";

const filesToCache = [
    "/",
    "/teacher/register",
    "/admin/login",
    "/teacher/schedule",

    "/offline.html",
    "/images/logo.png",
    "/images/offline.svg",
];

const preLoad = () => {
    return caches.open(CACHE_NAME).then((cache) => {
        return cache.addAll(filesToCache);
    });
};

self.addEventListener("install", (event) => {
    event.waitUntil(preLoad());
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((keyList) => {
            return Promise.all(
                keyList.map((key) => {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            );
        })
    );
});

// Offline First Strategy
self.addEventListener("fetch", (event) => {
    if (event.request.method !== "GET") {
        return;
    }

    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }

            return fetch(event.request)
                .then((networkResponse) => {
                    return caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, networkResponse.clone());
                        return networkResponse;
                    });
                })
                .catch(() => {
                    if (
                        event.request.headers
                            .get("accept")
                            .includes("text/html")
                    ) {
                        return caches.match("/offline.html");
                    }
                });
        })
    );
});
