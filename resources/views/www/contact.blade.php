@extends('www.layout')

@section('title', 'Kontakt')

@section('content')
    <div class="contact-form container">
        <form method="post" action="/contact">
            <h2 class="contact-title">Javite se!</h2>
            <h4 class="contact-title">Zainteresirani ste i imate pitanja? Voljeli bismo da nam se javite. Pošaljite nam poruku i mi ćemo vam se javiti u najkraćem roku.</h4>
            <div class="form-input">
                <div class="">
                    <input type="text" id="name" name="name" placeholder="Upiši ime">
                </div>
                <div class="">
                    <input type="email" id="email" name="email" placeholder="Upiši email adresu">
                </div>
                <div class="">
                    <input type="tel" id="tel" name="tel" placeholder="Upiši broj telefona">
                </div>
                <div class="">
                    <textarea id="subject" name="subject" placeholder="Upiši poruku" style="height:100px"></textarea>
                </div>

                <input class="btn" type="submit" value="Pošalji">
            </div>
        </form>
    </div>
@endsection