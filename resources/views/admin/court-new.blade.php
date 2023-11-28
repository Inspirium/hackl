@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col nopadding">
            <div class="header-image">
               <div class="gradient"></div>
               <div class="heading-image">Tereni</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
              <div class="text-center my-4">
                <a class="btn btn-info mt-2" href="#" role="button" data-toggle="modal"  data-target="#modal-court-free">Kopiraj podatke postojećeg terena</a>
              </div>

            <form>
              <div class="form-group mt-4">
                <label for="exampleInputEmail1">Ime terena</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Teren broj ...">
              </div>
              <div class="form-radio">
                <legend>Teren u funkciji</legend>
                <input type="radio" id="court-working-yes" name="court-working" value="yes" checked>
                <label for="court-working-yes">Da</label>
                <input type="radio" id="court-working-no" name="court-working" value="no">
                <label for="court-working-no">Ne</label>
              </div>
              <div class="form-group">
                <label for="">Vrsta podloga</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Odaberi podlogu" data-toggle="modal" data-target="#modal-court-type" readonly="true">
              </div>
              <div class="form-group">
                <label for="">radno vrijeme terena</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Odaberi vrijeme" data-toggle="modal" data-target="#modal-work-hours" readonly="true">
              </div>
              <div class="form-group">
                <label for="">Cijene po satima</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Odaberi raspone cijena" data-toggle="modal" data-target="#modal-court-timetable" readonly="true">
              </div>

              <div class="form-radio">
                <legend>Reflektori</legend>
                <input type="radio" id="lights-yes" name="lights" value="yes">
                <label for="lights-yes">Da</label>
                <input type="radio" id="lights-no" name="lights" value="no">
                <label for="lights-no">Ne</label>
              </div>
              <div class="form-radio">
                <legend>Tip terena</legend>
                <input type="radio" id="court-open" name="court-type" value="open">
                <label for="court-open">Otvoreni</label>
                <input type="radio" id="court-closed" name="court-type" value="closed">
                <label for="court-closed">Zatvoreni</label>
              </div>
              <div class="form-radio">
                <legend>Moguće rezervacije</legend>
                <input type="radio" id="full-hour" name="reservation-block" value="full">
                <label for="full-hour">Puni sat</label>
                <input type="radio" id="half-hour" name="reservation-block" value="half">
                <label for="half-hour">:30 minuta</label>
              </div>
              <div class="form-radio">
                <legend>Potvrda rezervacije</legend>
                <input type="radio" id="confirmation-yes" name="confirmation" value="yes">
                <label for="confirmation-yes">Da</label>
                <input type="radio" id="confirmation-no" name="confirmation" value="no">
                <label for="confirmation-no" checked>Ne</label>
              </div>

              <div class="text-center mb-4">
                <a class="btn btn-danger btn-lg mt-2" href="#" role="button">Obriši</a>
                <a class="btn btn-primary btn-lg mt-2" role="button" href="/reservation/reservation">Spremi</a>
              </div>
            </form>
        </div>
    </div>


</div>
@endsection