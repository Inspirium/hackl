@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col nopadding">
            <div class="header-image">
               <div class="gradient"></div>
               <div class="heading-image">Lista izazivača</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">

<!--Ovo je verzija kada korisnik označi igrača-->
        <div class="select-dialog mt-3">
            <div class="heading-mid-dialog">Označeno <span class="">xy igrača</span>
            </div>
            <div class="text-center">
                <a class="btn btn-info mt-2" href="#" role="button" href="/players/player-mesages">Obriši označeno</a>
            </div>
        </div>
    </div>
</div>       

<!--Ako nema niti jednog izazivača-->
<div class="text-center mt-4">
    <img class="" src="/images/tennis-player.svg">
    <div class="heading-big-bigger text-uppercase">Ups!</div>
    <div class="heading-small">Nemate niti jednog izazivača</div>
</div>


<!-- Table list -->
<div class="row">
    <div class="col">
        <div class="list-container mt-4 mb-4">
            <a class="list" href="/players/player-profile">
<!--                <div class="heading-small-date">Poslano: 27.09.2017.</div>-->
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                        <div class="checkbox">
                            <input type="checkbox" id="player-check-1" name="player-check" value="yes">
                            <label for="player-check-1"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/avatar-1.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
                            <div class="heading-mid">Marko Novak</div>
                            <div class="heading-xs mt-1"><span class="bold">30-40</span> godina</div>
                        </div>
                    </div>
                    <div class="action">
                        <div class="power-medium power-40">40%</div>
                    </div>
                </div>
            </a>
            <a class="list" href="/players/player-profile">
                
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                        <div class="checkbox">
                            <input type="checkbox" id="player-check-2" name="player-check" value="yes">
                            <label for="player-check-2"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/avatar-2.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
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
                        <div class="checkbox">
                            <input type="checkbox" id="player-check-3" name="player-check" value="yes">
                            <label for="player-check-3"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/federer.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
                            <div class="heading-mid">Ivan Herceg</div>
                            <div class="heading-xs mt-1"><span class="bold">20-30</span> godina</div>
                        </div>
                    </div>
                    <div class="action">
                        <div class="power-medium power-60">60%</div>
                    </div>
                </div>
            </a>
            <a class="list" href="/players/player-profile">
                
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                        <div class="checkbox">
                            <input type="checkbox" id="player-check-4" name="player-check" value="yes">
                            <label for="player-check-4"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/avatar-1.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
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
                        <div class="checkbox">
                            <input type="checkbox" id="player-check-5" name="player-check" value="yes">
                            <label for="player-check-5"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/avatar-2.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
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
                        <div class="checkbox">
                            <input type="checkbox" id="player-check-6" name="player-check" value="yes">
                            <label for="player-check-6"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/federer.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
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
                        <div class="checkbox">
                            <input type="checkbox" id="player-check" name="player-check" value="yes">
                            <label for="player-check"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/avatar-1.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
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