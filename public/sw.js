const CACHE_NAME = "offline-v1";

const filesToCache = [
    "/",
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

// Fungsi untuk cek apakah URL adalah login/logout endpoint
const isAuthEndpoint = (url) => {
    return [
        "/teacher/login",
        "/teacher/logout",
        "/admin/login",
        "/admin/logout",
    ].some((endpoint) => url.endsWith(endpoint));
};

self.addEventListener("fetch", (event) => {
    const { request } = event;

    if (
        (request.method === "POST" &&
            isAuthEndpoint(new URL(request.url).pathname)) ||
        (request.method === "DELETE" &&
            isAuthEndpoint(new URL(request.url).pathname))
    ) {
        event.respondWith(
            // Lakukan fetch ke jaringan
            fetch(request)
                .then((response) => {
                    caches
                        .keys()
                        .then((keyList) => {
                            return Promise.all(
                                keyList.map((key) => caches.delete(key))
                            );
                        })
                        .then(() => {
                            preLoad();
                        });

                    return response;
                })
                .catch((error) => {
                    return new Response("Network error during auth", {
                        status: 500,
                        statusText: "Network error",
                    });
                })
        );
        return;
    }

    if (event.request.method !== "GET") {
        return;
    }

    // Offline First Strategy untuk GET
    if (request.method === "GET") {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }

                return fetch(request)
                    .then((networkResponse) => {
                        return caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, networkResponse.clone());
                            return networkResponse;
                        });
                    })
                    .catch(() => {
                        if (
                            request.headers.get("accept")?.includes("text/html")
                        ) {
                            return caches.match("/offline.html");
                        }
                    });
            })
        );
    }
});
