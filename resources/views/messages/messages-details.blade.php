@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row">
        <div class="col nopadding">
            <div class="header-image">
               <div class="gradient"></div>
               <div class="heading-image">Pošalji poruku</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
        <a href="/players/player-profile">
            <div class="message-single-container mt-2 mb-1">
                <div class="message-single">
                    <div class="message-single-item">
                        <div class="message-single-user">
                            <img class="avatar-xs mr-2" src="/images/avatar-1.jpg">
                            <div class="message-single-names">
                                <div class="heading-mid">Marko Novak</div>
                                <div class="heading-xs mt-1"><span class="bold">30-40</span> godina</div>
                            </div>
                        </div>
                        <div class="action">
                            <div class="power-medium power-40">40%</div>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <div class="message-text mt-2">
            <div class="form-radio">
                <legend class="mt-3 mb-1">27.09., 12:35 > Marko Novak</legend>
            </div>
            <div class="heading-big-message">Bok, jesi li zainteresiran za meč?</div>
        </div>
        <div class="message-text mt-2">
            <div class="form-radio">
                <legend class="mt-3 mb-1">28.09., 13:35 > Roger Federer</legend>
            </div>
            <div class="heading-big-message">Zainteresiran sam...</div>
        </div>
        <div class="form-radio">
            <legend class="mt-3 mb-1">Napiši odgovor</legend>
            <textarea class="form-control" id="message" rows="6" placeholder="Zainteresiran sam..."></textarea>
        </div>
        <div class="text-center btn-box mt-4 mb-1">
            <a class="btn btn-info" href="#" role="button" data-toggle="modal"  data-target="#modal-prices">Pošalji odgovor</a>
        </div>        
        <div class="form-radio">
            <legend class=" mt-4 mb-2">Predloženi datumi</legend>
            <div class="heading-bullet">Svejedno, 18.09., 19.09., 20.09.</div>
            <legend class=" mt-4 mb-2">Predloženo vrijeme</legend>
            <div class="heading-bullet">Svejedno, 20:00, 21:00, 22:30</div>
        </div>
        <div class="form-radio">
            <legend class="mt-3">Dodatne opcije</legend>
            <input type="checkbox" id="court-buy" name="availability" value="yes" checked readonly="true">
            <label for="court-buy">Plaćam teren</label>
            <input type="checkbox" id="drink-buy" name="availability" value="no" checked>
            <label for="drink-buy">Plaćam piće</label>
            <input type="checkbox" id="new-balls" name="availability" value="no" checked>
            <label for="new-balls">Donosim nove loptice</label>
        </div>        


        <div class="text-center btn-box mt-4 mb-5">
            <a class="btn btn-danger" href="#" role="button" data-toggle="modal"  data-target="#modal-message-reject">Zahvali se</a>
            <a class="btn btn-primary" href="#" role="button" data-toggle="modal"  data-target="#modal-message-accept">Prihvati</a>
        </div>



    </div>
</div>


</div>
@endsection