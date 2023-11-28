<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--    Favicon   -->

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('images/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('images/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('images/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('images/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('images/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('images/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('images/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon//manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('images/favicon//ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-title" content="Tenis App">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="tennis-api" content="{{ env('API_DOMAIN') }}">

    <title>Tenis</title>

    <!-- Styles -->
    <link href="{{ env('API_DOMAIN') . mix('css/app.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <script src="https://use.fontawesome.com/0875c5fde5.js"></script>

</head>
<body>
    <div id="app">
        <router-view></router-view>
    </div>
    <!-- Scripts -->
    <script src="{{ env('API_DOMAIN') . mix('js/manifest.js') }}"></script>
    <script src="{{ env('API_DOMAIN') . mix('js/vendor.js') }}"></script>
    <script src="{{ env('API_DOMAIN') . mix('js/app.js') }}"></script>
    <script src="{{ env('API_DOMAIN') . '/socket.io/socket.io.js' }}"></script>
    @if (env('APP_ENV') !== 'production')
    <script id="__bs_script__">//<![CDATA[
        document.write("<script async src='http://HOST:8000/browser-sync/browser-sync-client.js?v=2.23.6'><\/script>".replace("HOST", location.hostname));
        //]]></script>
    @endif
</body>
</html>
