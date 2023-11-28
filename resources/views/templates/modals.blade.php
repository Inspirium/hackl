@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col nopadding">
            <div class="header-image">
               <div class="gradient"></div>
               <div class="heading-image">Rezervacija terena</div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a class="btn btn-info mt-3" href="#" role="button" data-toggle="modal"  data-target="#modal-2">Modal jedna kolona</a>
    </div>
    <div class="text-center">
        <a class="btn btn-info mt-3" href="#" role="button" data-toggle="modal"  data-target="#modal-menu">Modal menu</a>
    </div>
    <div class="text-center">
        <a class="btn btn-info mt-3" href="#" role="button" data-toggle="modal"  data-target="#modal-menu-user">Modal menu user</a>
    </div>
    <div class="text-center">
        <a class="btn btn-info mt-3" href="#" role="button" data-toggle="modal"  data-target="#modal-prices">Modal cijena terena</a>
    </div>
    <div class="text-center">
        <a class="btn btn-info mt-3" href="#" role="button" data-toggle="modal"  data-target="#modal-prices-input">Modal unos cijene</a>
    </div>
    <div class="text-center">
        <a class="btn btn-info mt-3" href="#" role="button" data-toggle="modal"  data-target="#modal-confirmation">Modal potvrda akcije</a>
    </div>
    <div class="text-center">
        <a class="btn btn-info mt-3" href="#" role="button" data-toggle="modal"  data-target="#modal-discount">Modal promotivna cijena</a>
    </div>
    <div class="text-center">
        <a class="btn btn-info mt-3" href="#" role="button" data-toggle="modal"  data-target="#modal-court-type">Modal vrsta terena</a>
    </div>
    <div class="text-center">
        <a class="btn btn-info mt-3" href="#" role="button" data-toggle="modal"  data-target="#modal-court-timetable">Modal slobodni termini</a>
    </div>
    <div class="text-center">
        <a class="btn btn-info mt-3" href="#" role="button" data-toggle="modal"  data-target="#modal-court-free">Modal slobodni tereni</a>
    </div>
    <div class="text-center">
        <a class="btn btn-info mt-3" href="#" role="button" data-toggle="modal"  data-target="#modal-reservation-confirmation">Modal potvrda rezervacije</a>
    </div>
    <div class="text-center">
        <a class="btn btn-info mt-3" href="#" role="button" data-toggle="modal"  data-target="#modal-power">Modal snaga igrača</a>
    </div>




<!-- Modal one column -->
<div class="modal fade" id="modal-2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="modal-title">Modal title</div>
                    <div class="modal-subtitle">Aditional description of this modal</div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-container">
                    <div class="modal-element-white modal-text">Link 1</div>
                    <div class="modal-element-white modal-text">Link 3</div>
                    <div class="modal-element-white modal-text">Link 2</div>
                    <div class="modal-element-white modal-text">Link 4</div>
                    <div class="modal-element-white modal-text">Link 6</div>
                    <div class="modal-element-white modal-text">Link 8</div>
                    <div class="modal-element-white modal-text">Link 3432</div>
                    <div class="modal-element-white modal-text">Link 253</div>
                    <div class="modal-element-white modal-text">Link sdudb sdfh dfsf sdfniouer</div>
                    <div class="modal-element-white modal-text">Link 4</div>
                    <div class="modal-element-white modal-text">Link 8</div>
                    <div class="modal-element-white modal-text">Link 3432</div>
                    <div class="modal-element-white modal-text">Link 253</div>
                    <div class="modal-element-white modal-text">Link sdudb sdfh dfsf sdfniouer</div>
                    <div class="modal-element-white modal-text">Link 4</div>
                </div>
            </div>
        </div>
    </div>
    <!--Close button fixed-->
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
    </button> 

    <div class="modal-footer modal-footer-fixed">
        <div class="modal-element-white close-btn" data-dismiss="modal">
            <div class="modal-text-btn">Zatvori</div>
        </div>
        <div class="modal-element-white ok-btn">
            <div class="modal-text-btn">U redu</div>
        </div>
    </div>
</div>

<!-- Modal Menu  -->
<div class="modal fade" id="modal-menu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="modal-title">Izbornik</div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-container">
                    <div class="modal-element-left modal-text">Rezerviraj teren</div>
                    <div class="modal-element-left modal-text">Igrači</div>
                    <div class="modal-element-left modal-text">Rezultati</div>
                    <div class="modal-element-left modal-text">Rang ljestvice</div>
                    <div class="modal-element-left modal-text">Novosti</div>
                    <div class="modal-element-left modal-text">O klubu</div>
                </div>
            </div>
        </div>
    </div>
    <!--Close button fixed-->
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
    </button> 

    <div class="modal-footer modal-footer-fixed">
        <div class="modal-element-white close-btn" data-dismiss="modal">
            <div class="modal-text-btn">Zatvori</div>
        </div>
    </div>
</div>

<!-- Modal Menu User -->
<div class="modal fade" id="modal-menu-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="modal-title">Stjepan Drmić</div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-container">
                    <div class="modal-element-left modal-text"><img class="mr-3" src="/images/notification.svg" width="17">
Obavijesti<span class="badge badge-primary ml-3">14</span></div>

                    <div class="modal-element-left modal-text">Moj profil</div>
                    <div class="modal-element-left modal-text">Moji podaci</div>
                    <div class="modal-element-left modal-text">Moje rezervacije</div>
                    <div class="modal-element-left modal-text">Odjavi se</div>
                </div>
            </div>
        </div>
    </div>
    <!--Close button fixed-->
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
    </button> 

    <div class="modal-footer modal-footer-fixed">
        <div class="modal-element-white close-btn" data-dismiss="modal">
            <div class="modal-text-btn">Zatvori</div>
        </div>
    </div>
</div>



<!-- Modal court prices -->
<div class="modal fade" id="modal-prices" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="modal-title">Cijene po satu</div>
                    <div class="modal-subtitle">Označite željen sate i unesite cijene</div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-container">
                    <div class="modal-element-white modal-element-gray">
                        <div class="modal-text modal-price">08:00 </div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 35 kn</div>
                    </div>
                    <div class="modal-element-white modal-element-gray">
                        <div class="modal-text modal-price ml-2">09:00</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 35 kn</div>
                    </div>
                    <div class="modal-element-white modal-element-gray">
                        <div class="modal-text modal-price">10:00</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 35 kn</div>
                    </div>
                    <div class="modal-element-white modal-element-gray">
                        <div class="modal-text modal-price">11:00</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text">35 kn</div>
                    </div>
                    <div class="modal-element-white active">
                        <div class="modal-text">12:00</div>
                    </div>
                    <div class="modal-element-white active">
                        <div class="modal-text">13:00</div>
                    </div>
                    <div class="modal-element-white active">
                        <div class="modal-text">14:00</div>
                    </div>
                    <div class="modal-element-white active">
                        <div class="modal-text">15:00</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text">16:00</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text">17:00</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text">18:00</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text">19:00</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text">20:00</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text">21:00</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text">22:00</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text">23:00</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text">24:00</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Close button fixed-->
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
    </button> 

    <div class="modal-footer modal-footer-fixed">
        <div class="modal-element-white close-btn" data-dismiss="modal">
            <div class="modal-text-btn">Zatvori</div>
        </div>
        <div class="modal-element-white other-btn">
            <div class="modal-text-btn">Unesi cijenu</div>
        </div>
        <div class="modal-element-white ok-btn">
            <div class="modal-text-btn">U redu</div>
        </div>
    </div>
</div>

<!-- Modal court prices input -->
<div class="modal fade" id="modal-prices-input" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="modal-title">Unesi cijenu</div>
                    <div class="modal-subtitle">Unesena cijena se odnosi za prethodno označene sate</div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="price-input">
                    <img src="/images/minus.svg" width="28" height="28">

                        <input type="email" class="form-control text-center heading-num-big" id="modal-price-input" aria-describedby="modal-price-input" placeholder="50 kn">

                    <img src="/images/plus.svg" width="28" height="28">
                </div>
            </div>
            <div class="modal-footer">
                <div class="modal-element-white close-btn" data-dismiss="modal">
                    <div class="modal-text-btn">Zatvori</div>
                </div>
                <div class="modal-element-white ok-btn">
                    <div class="modal-text-btn">U redu</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmation -->
<div class="modal fade" id="modal-confirmation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="modal-title"></div>
                    <div class="modal-subtitle"></div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-title">Da li ste sigurni da želite otkazati rezervaciju?</div>
                <div class="modal-subtitle">U slučaju potvrde otkaza rezervacije poruka će biti poslana korisniku na e-mail</div>
            </div>
            <div class="modal-footer modal-footer-sticky">
                <div class="modal-element-white close-btn" data-dismiss="modal">
                    <div class="modal-text-btn">Ne</div>
                </div>
                <div class="modal-element-white ok-btn">
                    <div class="modal-text-btn">Da</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal one column -->
<div class="modal fade" id="modal-discount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="modal-title">Promotivna cijena</div>
                    <div class="modal-subtitle">Svi članovi će dobiti obavijest o popustu na redovne cijene</div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-container">
                    <div class="modal-element-white modal-text">10%</div>
                    <div class="modal-element-white modal-text">20%</div>
                    <div class="modal-element-white modal-text">30%</div>
                    <div class="modal-element-white modal-text">40%</div>
                    <div class="modal-element-white modal-text">50%</div>
                    <div class="modal-element-white modal-text">60%</div>
                    <div class="modal-element-white modal-text">70%</div>
                    <div class="modal-element-white modal-text">80%</div>
                    <div class="modal-element-white modal-text">90%</div>
                    <div class="modal-element-white modal-text">Upiši cijenu</div>
                </div>
            </div>
        </div>
    </div>
    <!--Close button fixed-->
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
    </button> 

    <div class="modal-footer modal-footer-fixed">
        <div class="modal-element-white close-btn" data-dismiss="modal">
            <div class="modal-text-btn">Zatvori</div>
        </div>
        <div class="modal-element-white ok-btn">
            <div class="modal-text-btn">U redu</div>
        </div>
    </div>
</div>

<!-- Modal court type -->
<div class="modal fade" id="modal-court-type" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="modal-title">Promotivna cijena</div>
                    <div class="modal-subtitle">Svi članovi će dobiti obavijest o popustu na redovne cijene</div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Povlači podatke o vrstama terena koje je klub definirao u adminu. Sveukupno ih ima 6, a ovdje se prikazuje samo one koje klub ima. "Bilo koja podloga" su označeni kao default. Klikom na bilo koju drugu podlogu, aktivna postaje samo ta -->
                <div class="modal-container">
                    <div class="modal-element-white modal-text active">Bilo koja podloga</div>
                    <div class="modal-element-white modal-text">Zemlja</div>
                    <div class="modal-element-white modal-text">Beton</div>
                    <div class="modal-element-white modal-text">Tepih</div>
                    <div class="modal-element-white modal-text">Umjetna trava</div>
                    <div class="modal-element-white modal-text">Akril</div>
                    <div class="modal-element-white modal-text">Ostalo</div>
                </div>
            </div>
        </div>
    </div>
    <!--Close button fixed-->
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
    </button> 

    <div class="modal-footer modal-footer-fixed">
        <div class="modal-element-white close-btn" data-dismiss="modal">
            <div class="modal-text-btn">Zatvori</div>
        </div>
        <div class="modal-element-white ok-btn">
            <div class="modal-text-btn">U redu</div>
        </div>
    </div>
</div>

<!-- Modal court timetable -->
<div class="modal fade" id="modal-court-timetable" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="modal-title">Promotivna cijena</div>
                    <div class="modal-subtitle">Svi članovi će dobiti obavijest o popustu na redovne cijene</div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Povlači podatke iz "radnog vremena" pojedinog terena koje je definirano u adin dijelu kod unosa terena. Prikazuju se samo tereni označeni u koraku ranije (vrsta terena: zemljani, beton...) Ako je rezervacija na pola sata, prikazuju se ovako. Ako ne, samo okrugli sat. Klasa .modal-element-gray označuje nedostupne termine  -->
                <div class="modal-container">
                    <div class="modal-element-white">
                        <div class="modal-text modal-price">08:00</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 50 kn</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text modal-price">08:30</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 50 kn</div>
                    </div>
                    <div class="modal-element-white modal-element-gray">
                        <div class="modal-text modal-price">09:00</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 50 kn</div>
                    </div>
                    <div class="modal-element-white modal-element-gray">
                        <div class="modal-text modal-price">09:30</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 50 kn</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text modal-price">10:00</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 50 kn</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text modal-price">10:30</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 50 kn</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text modal-price">11:00</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 35 kn</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text modal-price">11:30</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 35 kn</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text modal-price">12:00</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 35 kn</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text modal-price">12:30</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 35 kn</div>
                    </div>
                    <div class="modal-element-white">
                        <div class="modal-text modal-price">13:00</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger" aria-hidden="true"></i></div>
                        <div class="modal-text"> 35 kn</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Close button fixed-->
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
    </button> 

    <div class="modal-footer modal-footer-fixed">
        <div class="modal-element-white close-btn" data-dismiss="modal">
            <div class="modal-text-btn">Zatvori</div>
        </div>
        <div class="modal-element-white ok-btn">
            <div class="modal-text-btn">U redu</div>
        </div>
    </div>
</div>

<!-- Modal available courts -->
<div class="modal fade" id="modal-court-free" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="modal-title">Promotivna cijena</div>
                    <div class="modal-subtitle">Svi članovi će dobiti obavijest o popustu na redovne cijene</div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Prikazuje terene sa slobodnim terminima koji udovoljavaju definiranim uvjetima -->
                <div class="modal-container">
                    <div class="modal-element-white modal-text">Teren broj 1</div>
                    <div class="modal-element-white modal-text">Teren broj 4</div>
                    <div class="modal-element-white modal-text">Teren broj 5</div>
                </div>
            </div>
        </div>
    </div>
    <!--Close button fixed-->
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
    </button> 

    <div class="modal-footer modal-footer-fixed">
        <div class="modal-element-white close-btn" data-dismiss="modal">
            <div class="modal-text-btn">Zatvori</div>
        </div>
        <div class="modal-element-white ok-btn">
            <div class="modal-text-btn">U redu</div>
        </div>
    </div>
</div>

<!-- Modal Reservation Confirmation -->
<div class="modal fade" id="modal-reservation-confirmation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="modal-title"></div>
                    <div class="modal-subtitle"></div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
                </button>
            </div>

            <!-- Ovaj modal se pojavljuje samo ako je u adminu terena označeno da se rezervacije potvrđuju. U suprotnom, korisnika se prebaci na početnu stranicu "rezervacije terena" i dobije alert da je rezervacija bila uspješna  -->
            <div class="modal-body">
                <div class="modal-title">Potvrda rezervacije će vam stići nakon autorizacije</div>
                <div class="modal-subtitle">U slučaju nejavljanja, predlažemo vam da kontaktirate osoblje kluba</div>
            </div>
            <div class="modal-footer modal-footer-sticky">
                <div class="modal-element-white ok-btn">
                    <div class="modal-text-btn">U redu</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal one column -->
<div class="modal fade" id="modal-power" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div class="modal-title">Promotivna cijena</div>
                    <div class="modal-subtitle">Svi članovi će dobiti obavijest o popustu na redovne cijene</div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-container">
                    <div class="modal-element-white-text modal-text power-10">10%</div>
                    <div class="modal-element-white-text modal-text power-20">20%</div>
                    <div class="modal-element-white-text modal-text power-30">30%</div>
                    <div class="modal-element-white-text modal-text power-40">40%</div>
                    <div class="modal-element-white-text modal-text power-50">50%</div>
                    <div class="modal-element-white-text modal-text power-60">60%</div>
                    <div class="modal-element-white-text modal-text power-70">70%</div>
                    <div class="modal-element-white-text modal-text power-80">80%</div>
                    <div class="modal-element-white-text modal-text power-90">90%</div>
                    <div class="modal-element-white-text modal-text power-100">Upiši cijenu</div>
                </div>
            </div>
        </div>
    </div>
    <!--Close button fixed-->
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><img class="modal-close" src="/images/close.svg" width="28" height="28"></span>
    </button> 

    <div class="modal-footer modal-footer-fixed">
        <div class="modal-element-white close-btn" data-dismiss="modal">
            <div class="modal-text-btn">Zatvori</div>
        </div>
        <div class="modal-element-white ok-btn">
            <div class="modal-text-btn">U redu</div>
        </div>
    </div>
</div>


</div>
@endsection