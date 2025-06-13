<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <title>{{ $title }}</title>

    {{-- manifest --}}
    <link rel="manifest" href="/manifest.json" />
    <!-- ios support -->
    <link rel="apple-touch-icon" href="/images/icons/icon-96x96.png" />
    <meta name="apple-mobile-web-app-status-bar" content="#ffffff" />
    <meta name="theme-color" content="#ffffff" />
</head>

<body>
    {{ $slot }}


    <script>
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker
                .register("/sw-2.js")
                .then((res) => console.log("Service Worker Registered", res))
                .catch((err) => console.log("Failed to register Service Worker", err));
        }
    </script>
</body>

</html>
