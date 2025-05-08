<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Offline</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: system-ui, sans-serif;
            background-color: #f3f4f6;
            /* bg-gray-100 */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }

        .container {
            max-width: 480px;
            padding: 1rem;
        }

        .logo {
            width: 96px;
            height: 96px;
            margin: 0 auto 1rem;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            /* text-gray-800 */
            margin-bottom: 0.5rem;
        }

        p {
            color: #4b5563;
            /* text-gray-600 */
            margin-bottom: 1.5rem;
        }

        button {
            background-color: #708871;
            /* simipa-2 */
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #606676;
            /* simipa-1 */
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="images/offline.svg" alt="App Logo" class="logo">
        <h1>Kamu lagi offline</h1>
        <p>Kayaknya koneksi internet kamu terputus. Tapi tenang, kamu bisa balik lagi nanti.</p>
        <button onclick="window.location.reload()">Coba Lagi</button>
    </div>
</body>

</html>
