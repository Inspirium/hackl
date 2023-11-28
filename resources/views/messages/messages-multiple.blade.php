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
        <a href="/players/players">
            <div class="message-multi-container mt-2">
                <div class="heading-mid-dialog">
                    <span class="d-inline-block mb-2">7 igrača</span> 
                </div>
                <div class="message-multi-players">
                    <img class="avatar-xs" src="/images/avatar-1.jpg">
                    <img class="avatar-xs" src="/images/avatar-2.jpg">
                    <img class="avatar-xs" src="/images/federer.jpg">
                    <img class="avatar-xs" src="/images/avatar-1.jpg">
                    <img class="avatar-xs" src="/images/avatar-2.jpg">
                    <img class="avatar-xs" src="/images/federer.jpg">
                    <img class="avatar-xs" src="/images/avatar-2.jpg">
                    <img class="avatar-xs" src="/images/avatar-1.jpg">
                    <img class="avatar-xs" src="/images/avatar-2.jpg">
                    <img class="avatar-xs" src="/images/federer.jpg">
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
            <legend class="mt-3 mb-3">Automatsko prihvaćanje</legend>
            <input type="checkbox" id="auto-accept" name="availability" value="yes" checked>
            <label for="auto-accept">Prihvati prvog igrača koji potvrdi</label>
        </div>
        <div class="form-radio">
            <legend class="mt-3 mb-1">Poruka</legend>
            <label for="message"></label>
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
@extends('templates.modals-code')
@endsection 