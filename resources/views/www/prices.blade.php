@extends('www.layout')

@section('title', 'Cijene')

@section('content')
    <div class="background">
        <div class="container-price">
            <div class="panel pricing-table">

                <!--
                                <div class="pricing-plan">
                                    <img src="https://s22.postimg.cc/8mv5gn7w1/paper-plane.png" alt="" class="pricing-img">
                                    <h2 class="pricing-header">Personal</h2>
                                    <ul class="pricing-features">
                                    <li class="pricing-features-item">Custom domains</li>
                                    <li class="pricing-features-item">Sleeps after 30 mins of inactivity</li>
                                    </ul>
                                    <span class="pricing-price">Free</span>
                                    <a href="#/" class="pricing-button">Prijavi se</a>
                                </div>
                -->

                <div class="pricing-plan">
                    <img src="{{ asset('images/www/price-icon.svg') }}" alt="" class="pricing-img">
                    <h2 class="pricing-header">Tenis.plus puni paket</h2>
                    <ul class="pricing-features">
                        <li class="pricing-features-item">Neograničeni hosting, broj terena, broj članova</li>
                        <li class="pricing-features-item">Online rezervacija terena</li>
                        <li class="pricing-features-item">Upravljanje članovima/igračima</li>
                        <li class="pricing-features-item">Unos, praćenje i statistika rezultata</li>
                        <li class="pricing-features-item">Rang ljestvica</li>
                        <li class="pricing-features-item">Klupski oglasnik</li>
                        <li class="pricing-features-item">Klupske novosti i obavijesti</li>
                    </ul>
                    <span class="pricing-price">250,00 kn/mjesečno</span>
                    <a href="/contact" class="pricing-button">Pošalji upit</a>
                </div>

                <!--
                                <div class="pricing-plan">
                                    <img src="https://s21.postimg.cc/tpm0cge4n/space-ship.png" alt="" class="pricing-img">
                                    <h2 class="pricing-header">Enterprise</h2>
                                    <ul class="pricing-features">
                                    <li class="pricing-features-item">Dedicated</li>
                                    <li class="pricing-features-item">Simple horizontal scalability</li>
                                    </ul>
                                    <span class="pricing-price">$400</span>
                                    <a href="#/" class="pricing-button">Free trial</a>
                                </div>
                -->

            </div>
        </div>
    </div>
@endsection