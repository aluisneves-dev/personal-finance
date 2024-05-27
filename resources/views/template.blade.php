<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('css/index.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontawesome-free-5.15.3-web/css/all.min.css')}}">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>AL7 - Personal Finance</title>
</head>
<body>

    @yield('content')

    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/index.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>