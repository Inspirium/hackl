<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title') - Tenis.plus</title>
    <meta name="author" content="Stjepan Drmic">

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

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">

    <link rel="stylesheet" href="{{mix('css/www/app.css')}}">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body class="body">

<header>
    <div class="header">
        <div class="gradient"></div>
        <div class="header_nav container">
            <a href="/">
                <img src="{{ asset('images/www/logo.svg') }}" class="header_nav_logo" alt="">
            </a>
            <label>
                <input type="checkbox">
                <span class="menu">
                         <span class="hamburger"></span>
                     </span>
                <ul>
                    <li><a href="/" class="{{ request()->route()->getName() === 'homepage' ? 'active' : '' }}">Home</a></li>
                    <li><a href="/prices" class="{{ request()->route()->getName() === 'prices' ? 'active' : '' }}">Cijena</a></li>
                    <li><a href="/contact" class="{{ request()->route()->getName() === 'contact' ? 'active' : '' }}">Kontakt</a></li>
                    <li><a class="login" href="/login">Prijavi se</a></li>
                </ul>
            </label>
        </div>
        <div class="header_main container">
            <h1 class="header_main_left c-white">SUSTAV ZA <span>UPRAVLJANJE TENISKIM KLUBOM</span> I ONLINE REZERVACIJU TERENA </h1>
            <div class="header_main_center">
                <img src="{{ asset('images/www/mockup_header.png') }}" alt="">
            </div>
            <h2 class="header_main_right c-white">Povećajte prihode za <span>25%*</span></h2>
        </div>
    </div>
    <div class="header_footer-price">
    </div>
</header>


<main>
    @yield('content')
</main>

<footer class="footer">
    <div class="footer__bckg">
        <div class="footer__bckg__box container">
            <div class="credit"><strong>© {{ date('Y') }} Tenis.plus</strong> Sva prava pridržana.</div>
            <div class="craftedby">Powered by Inspirium.hr</div>
        </div>
    </div>
</footer>
<script src="{{ mix('js/www/wow.min.js') }}"></script>
<script> new WOW().init(); </script>
</body>
</html>