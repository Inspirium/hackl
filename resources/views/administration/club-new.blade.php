@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <div class="text-center my-4">
                <img class="avatar-l" src="/images/logo.jpg">
            </div>
             <div class="btn-box text-center mt-4">
                <a class="btn btn-info btn-lg mr-2" href="#" role="button">Dodaj logotip kluba</a>
            </div>

            <form>
              <div class="heading-section full-width mt-4">Klub</div>
              <div class="form-group mt-4">
                <label for="clubname">Puni naziv kluba</label>
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
                <label for="webadress">Web stranica</label>
                <input type="url" class="form-control" id="webadress" aria-describedby="webadress" placeholder="">
              </div>
              <div class="form-group">
                <label for="email">email</label>
                <input type="email" class="form-control" id="email" aria-describedby="usercity" placeholder="">
              </div>
              <div class="form-group">
                <label for="email">Cijena mjesečno</label>
                <input type="email" class="form-control" id="email" aria-describedby="usercity" placeholder="">
              </div>
              <div class="form-group">
                <label for="payment">Vrsta plaćanja</label>
                <input type="text" class="form-control" id="payment" aria-describedby="emailHelp" placeholder="" data-toggle="modal" data-target="#modal-court-type" readonly="true">
              </div>
              <div class="form-group">
                <label for="email">Hosting stranice</label>
                <input type="email" class="form-control" id="email" aria-describedby="usercity" placeholder="">
              </div>
              <div class="form-radio">
                <legend>Hosting</legend>
                <input type="radio" id="hosting-self" name="member" value="no">
                <label for="hosting-self">Vlastiti hosting</label>
                <input type="radio" id="hosting-our" name="member" value="yes">
                <label for="hosting-our">Naš hosting</label>
              </div>
              
              
              
              <div class="heading-section full-width mt-4">Voditelj</div>
              <div class="form-group mt-4">
                <label for="firstname">Ime</label>
                <input type="text" class="form-control" id="firstname" aria-describedby="username" placeholder="">
              </div>
              <div class="form-group">
                <label for="lastname">Prezime</label>
                <input type="text" class="form-control" id="lastname" aria-describedby="userLastname" placeholder="">
              </div>
              <div class="form-radio">
                <legend>Spol</legend>
                <input type="radio" id="sex-m" name="sex" value="M">
                <label for="sex-m">M</label>
                <input type="radio" id="sex-z" name="sex" value="F">
                <label for="sex-z">Ž</label>
              </div>
              <div class="form-group">
                <label for="username-input">Korisničko ime</label>
                <input type="text" class="form-control" id="username-input" aria-describedby="username" placeholder="">
              </div>
              <div class="form-group">
                <label for="password-input">Lozinka</label>
                <input type="password" class="form-control" id="password-input" aria-describedby="username" placeholder="">
              </div>
              <div class="btn-box text-center mt-4 mb-2">
                <a class="btn btn-primary mr-2" href="#" role="button">Promjeni lozinku</a>
              </div>
              
<!--Ako korisnik klikne na promjenu lozinke, otvara mu se dva dodatna polja. Inače su skrivena.-->
             <div class="">
              <div class="form-group">
                <label for="password-input-old">Stara lozinka</label>
                <input type="password" class="form-control" id="password-input-old" aria-describedby="username" placeholder="">
              </div>
              <div class="form-group">
                <label for="password-input-new">Nova lozinka</label>
                <input type="password" class="form-control" id="password-input-new" aria-describedby="username" placeholder="">
              </div>
              <div class="form-group">
                <label for="password-input-new-confirm">Potvrdi novu lozinku</label>
                <input type="password" class="form-control" id="password-input-new-confirm" aria-describedby="username" placeholder="">
              </div>
              <div class="btn-box text-center mt-4 mb-2">
                <a class="btn btn-primary mr-2" href="#" role="button">Potvrdi lozinku</a>
              </div>
              </div>
<!--End-->
             
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

              <div class="text-center mb-4">
                <a class="btn btn-primary btn-lg mt-2" href="#" role="button">Spremi</a>
              </div>
            </form>
        </div>
    </div>

</div>
@endsection