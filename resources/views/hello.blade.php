<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <title>Document</title>
</head>

<body>
    <h1 class="font-bold mt-3">Helo {{ \Carbon\Carbon::now()->isoFormat('dddd') }}</h1>
</body>

</html>
