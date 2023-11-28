@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col nopadding">
            <div class="header-image">
               <div class="gradient"></div>
               <div class="heading-image">Korisnici</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
        
        <div class="search-bar mt-4">
            <input class="form-control" type="search" placeholder="Ponađi igrača" id="example-search-input">
            <i class="fa fa-search icon-search" aria-hidden="true"></i>
            <div class="clearfix"></div>
        </div>
    </div>
</div>       

<!-- Table list no checkbox -->
<div class="row">
    <div class="col">
        <div class="list-container mt-2 mb-4">
           <a class="list" data-toggle="modal" data-target="#modal-new-user">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/avatar-2.jpg">
                        <div class="list-names">
                            <div class="heading-mid">Josip Šimić</div>
                            <div class="heading-xs mt-1"><span class="bold">30-40</span> godina</div>
                        </div>
                    </div>
                    <div class="action">
<!--Novi korisnik koji se prijavio u sustav. Admin ga mora aktivirati. Nega se prikazuje prvog. Ostali po funkcijama (Voditelj, Blokiran) pa onda ostale po prezimenu-->
                        <div class="power-medium bg-primary">Novi korisnik</div>
<!--End-->          
                   </div>
                </div>
            </a>
            <a class="list" href="/players/player-profile">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/avatar-1.jpg">
                        <div class="list-names">
                            <div class="heading-mid">Marko Novak</div>
                            <div class="heading-xs mt-1"><span class="bold">30-40</span> godina</div>
                        </div>
                    </div>
                    <div class="action">
<!--Ako je korisnika admin blokirao-->
                        <div class="power-medium bg-danger mr-2">blokiran</div>
<!--End-->
                        <div class="power-medium power-40">40%</div>
                    </div>
                </div>
            </a>
            <a class="list" href="/players/player-profile">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/avatar-2.jpg">
                        <div class="list-names">
                            <div class="heading-mid">Josip Šimić</div>
                            <div class="heading-xs mt-1"><span class="bold">30-40</span> godina</div>
                        </div>
                    </div>
                    <div class="action">
                        <div class="power-medium power-50">50%</div>
                    </div>
                </div>
            </a>
            <a class="list" href="/players/player-profile">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/federer.jpg">
                        <div class="list-names">
                            <div class="heading-mid">Ivan Herceg</div>
                            <div class="heading-xs mt-1"><span class="bold">20-30</span> godina</div>
                        </div>
                    </div>
                    <div class="action">
<!--Ako je korisnika admin označio kao voditelja-->
                        <div class="power-medium bg-success mr-2">Voditelj</div>
<!--End-->
                        <div class="power-medium power-60">60%</div>
                    </div>
                </div>
            </a>
            <a class="list" href="/players/player-profile">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/avatar-1.jpg">
                        <div class="list-names">
                            <div class="heading-mid">Slavoljub Penkala</div>
                            <div class="heading-xs mt-1"><span class="bold">50-60</span> godina</div>
                        </div>
                    </div>
                    <div class="action">
                        <div class="power-medium power-90">90%</div>
                    </div>
                </div>
            </a>
            <a class="list" href="/players/player-profile">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/avatar-2.jpg">
                        <div class="list-names">
                            <div class="heading-mid">Marko Novak</div>
                            <div class="heading-xs mt-1"><span class="bold">30-40</span> godina</div>
                        </div>
                    </div>
                    <div class="action">
                        <div class="power-medium power-70">70%</div>
                    </div>
                </div>
            </a>
            <a class="list" href="/players/player-profile">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/federer.jpg">
                        <div class="list-names">
                            <div class="heading-mid">Marko Novak</div>
                            <div class="heading-xs mt-1"><span class="bold">50-60</span> godina</div>
                        </div>
                    </div>
                    <div class="action">
                        <div class="power-medium power-30">30%</div>
                    </div>
                </div>
            </a>
            <a class="list" href="/players/player-profile">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/avatar-1.jpg">
                        <div class="list-names">
                            <div class="heading-mid">Marko Novak</div>
                            <div class="heading-xs mt-1"><span class="bold">50-60</span> godina</div>
                        </div>
                    </div>
                    <div class="action">
                        <div class="power-medium power-10">10%</div>
                    </div>
                </div>

            </a>

        </div>
    </div>
</div>


</div>
@endsection