@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col nopadding">
            <div class="header-image">
               <div class="gradient"></div>
               <div class="heading-image">Administracija</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col nopadding">
            <div class="main-navigation">
                <a href="/administration/clubs" class="col-4 main-navigation-module">
                   <img src="/images/top-list.svg">
                   <div class="heading-small text-center mt-2">Pretraži klubove</div>
                </a>
                <a href="/administration/players" class="col-4 main-navigation-module">
                   <img src="/images/players.svg">
                   <div class="heading-small text-center mt-2">Pretraži igrače</div>
                </a>
<!--
                <a href="/results/results" class="col-4 main-navigation-module">
                   <img src="/images/results.svg">
                   <div class="heading-small text-center mt-2">Upiši rezultati</div>
                </a>
                <a href="/ranks/ranks" class="col-4 main-navigation-module">
                   <img src="/images/top-list.svg">
                   <div class="heading-small text-center mt-2">Rang lista</div>
                </a>
                <a href="#" class="col-4 main-navigation-module">
                   <img src="/images/news.svg">
                   <div class="heading-small text-center mt-2">Klupske novosti</div>
                </a>
                <a href="messages/messages" class="col-4 main-navigation-module">
                   <img src="/images/messages.svg">
                   <div class="heading-small text-center mt-2">Obavijesti i poruke</div>
                </a>
-->
            </div>
        </div>
    </div>
</div>
@endsection