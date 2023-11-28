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

    <div class="row">
        <div class="col">
            <div class="date-picker mt-2">
                <i class="fa fa-angle-left fa-4x" aria-hidden="true"></i>
                <div class="heading-date">Ponedjeljak, <span>19.06.</span></div>
                <i class="fa fa-angle-right fa-4x" aria-hidden="true"></i>
            </div>
        </div>
    </div>

    <div class="showcode mt-4">
        <h3>Class: datepicker</h3>
        <p>Početni prikaz - trenutni dan</p>
        <p>Pregled ne može ići u prošlost</p>
        <p>Inactive class za strelice je .inactive</p>
    </div>


    <!--Clay court -->
    <div class="row">
        <div class="col">
            <div class="court mt-4">
                <a href="#">
                    <div class="court-header d-flex">
                        <div class="heading-small mr-auto">Teren broj 1.</div>
                        <div class="badge badge-clay mb-1">Zemlja</div>
                    </div>
                    <div class="court-slots d-flex">
                        <div class="court-slot">
                            <div class="heading-num-small text-center">08</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">09</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">10</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">11</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">12</div>
                            <div class="indicator">

                                <svg width="100%" height="100%" viewBox="0 0 10 10" preserveAspectRatio="none">
                                     <polygon fill="#B25900" stroke-width=0
                                     points="0,10 10,10 10,0" />
                                </svg>

                            </div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">13</div>
                            <div class="indicator clay-reserve"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">14</div>
                            <div class="clay-reserve indicator "></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">15</div>
                            <div class="indicator clay-reserve"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">16</div>
                            <div class="indicator">

                                <svg width="100%" height="100%" viewBox="0 0 10 10" preserveAspectRatio="none">
                                     <polygon fill="#B25900" stroke-width=0
                                     points="0 0,0 10,10 0"/>
                                </svg>

                            </div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">17</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">18</div>
                            <div class="indicator">
                                <div class="clay-reserve-half-second"></div>
                            </div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">19</div>
                            <div class="indicator clay-reserve"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">20</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">21</div>
                            <div class="indicator">
                                <div class="clay-reserve-half"></div>
                            </div>
                        </div>

                        <div class="court-slot">
                            <div class="heading-num-small text-center">22</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">23</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">24</div>
                            <div class="indicator"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <a class="btn btn-info mt-3" href="#" role="button">Rezerviraj ovaj teren</a>
                    </div>
                </a>
            </div>

            <!--Other court -->
            <div class="court mt-4">
                <a href="#">
                    <div class="court-header d-flex">
                        <div class="heading-small mr-auto">Teren broj 1.</div>
                        <div class="badge bg-primary mb-1">Beton</div>
                    </div>
                    <div class="court-slots d-flex">
                        <div class="court-slot">
                            <div class="heading-num-small text-center">08</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">09</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">10</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">11</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">12</div>
                            <div class="indicator">

                                <svg width="100%" height="100%" viewBox="0 0 10 10" preserveAspectRatio="none">
                                     <polygon fill="#0085B2" stroke-width=0
                                     points="0,10 10,10 10,0" />
                                </svg>

                            </div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">13</div>
                            <div class="indicator bg-primary"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">14</div>
                            <div class="indicator bg-primary"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">15</div>
                            <div class="indicator bg-primary"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">16</div>
                            <div class="indicator">

                                <svg width="100%" height="100%" viewBox="0 0 10 10" preserveAspectRatio="none">
                                     <polygon fill="#0085B2" stroke-width=0
                                     points="0 0,0 10,10 0"/>
                                </svg>

                            </div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">17</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">18</div>
                            <div class="indicator">
                                <div class="clay-reserve-half-second"></div>
                            </div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">19</div>
                            <div class="indicator bg-primary"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">20</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">21</div>
                            <div class="indicator">
                                <div class="clay-reserve-half"></div>
                            </div>
                        </div>

                        <div class="court-slot">
                            <div class="heading-num-small text-center">22</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">23</div>
                            <div class="indicator"></div>
                        </div>
                        <div class="court-slot">
                            <div class="heading-num-small text-center">24</div>
                            <div class="indicator"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <a class="btn btn-info mt-3" href="#" role="button">Rezerviraj ovaj teren</a>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="showcode mt-4">
        <h3>Class: court</h3>
        <p>Prikaz ovisi o upisu rasponu radnog vremena pojedinog terena u adminu. Ovdje od 8-24</p>
        <p>U adminu se definiraju rezervacijski blokovi - puni sat, pola sata</p>
        <p>Dvije vrste terena - zemljani i ostali</p>
        <p>Class za rezerviran sat - zemlja: .clay-reserve, ostali: .bg-primary</p>
        <p>Class za pola sata - svg-ovi u kodu. Promjena samo boja</p>
        <p></p>
    </div>

</div>
@endsection