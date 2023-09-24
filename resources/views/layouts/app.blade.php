<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Just 5 Quests</title>

    {{-- vue CDN --}}
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    {{-- sweetalert CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- css & js --}}
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body class="retro-bg-light">
    @yield('content')
</body>

</html>
