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
        <div class="filter">
            <div class="filter-box">
                <div class="filter-item">
                    <div class="filter-label">Prijedlog dana</div>
                    <div class="form-group">
                        <input type="text" class="form-control bg-white" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Sve" data-toggle="modal" data-target="#modal-day" readonly="true">
                        <i class="fa fa-caret-down icon-caret" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="filter-item">
                    <div class="filter-label">Prijedlog sata</div>
                    <div class="form-group">
                        <input type="text" class="form-control bg-white" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Sve" data-toggle="modal" data-target="#modal-time" readonly="true">
                        <i class="fa fa-caret-down icon-caret" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-radio">
            <legend class="mt-3 mb-1">Poruka</legend>
            <textarea class="form-control" id="message" rows="8" placeholder="Bok, jesi li zainteresiran za meč?"></textarea>
            <legend class="mt-3">Dodatne opcije</legend>
            <input type="checkbox" id="court-buy" name="availability" value="yes">
            <label for="court-buy">Plaćam teren</label>
            <input type="checkbox" id="drink-buy" name="availability" value="no">
            <label for="drink-buy">Plaćam piće</label>
            <input type="checkbox" id="new-balls" name="availability" value="no">
            <label for="new-balls">Donosim nove loptice</label>
        </div>


        <div class="text-center btn-box mt-4 mb-5">
            <a class="btn btn-danger" href="#" role="button" data-toggle="modal"  data-target="#modal-prices">Otkaži</a>
            <a class="btn btn-info" href="#" role="button" data-toggle="modal"  data-target="#modal-prices">Pošalji poruku</a>
        </div>



    </div>
</div>


</div>
@endsection