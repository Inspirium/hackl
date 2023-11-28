@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <div class="text-center my-4">
                <img class="avatar-l" src="/images/brand-image.jpg">
            </div>
             <div class="btn-box text-center mt-4">
                <a class="btn btn-info btn-lg mr-2" href="#" role="button">Dodaj fotografiju</a>
            </div>

            <form>
              <div class="form-group mt-4">
                <label for="clubname">Naziv kluba</label>
                <input type="text" class="form-control" id="clubname" aria-describedby="clubname" placeholder="">
              </div>             
              <div class="form-group">
                <label for="useraddress">Adresa</label>
                <input type="text" class="form-control" id="useraddress" aria-describedby="useradress" placeholder="">
              </div>
              <div class="form-group">
                <label for="usercity">Mjesto</label>
                <input type="text" class="form-control" id="usercity" aria-describedby="usercity" placeholder="">
              </div>
              <div class="form-group">
                <label for="email">email</label>
                <input type="email" class="form-control" id="email" aria-describedby="usercity" placeholder="">
              </div>
              <div class="form-group">
                <label for="tel-input">Mobitel</label>
                <input type="tel" class="form-control" id="tel-input" aria-describedby="usercity" placeholder="">
              </div>
              <div class="form-group">
                <label for="about">O klubu</label>
                <textarea class="form-control" id="about" rows="6"></textarea>
              </div>
              <div class="text-center mb-4">
                <a class="btn btn-primary btn-lg mt-2" href="#" role="button">Spremi</a>
              </div>
            </form>
        </div>
    </div>

</div>
@endsection