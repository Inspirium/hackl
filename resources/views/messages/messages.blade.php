@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col nopadding">
            <div class="header-image">
               <div class="gradient"></div>
               <div class="heading-image">Poruke</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">

<!--     Ovo je verzija kada korisnik označi poruke-->
        <div class="select-dialog mt-3">
            <div class="heading-mid-dialog">Označeno <span class="">xy poruka</span>
            </div>
            <div class="text-center">
                <a class="btn btn-info mt-2" href="#" role="button" href="/players/player-mesages">Obriši označeno</a>
            </div>
        </div>
    </div>
</div>       


<!-- Table list -->
<div class="row">
    <div class="col">
        <div class="list-container mt-4 mb-4">
            <a class="list" href="/messages/messages-details">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                        <div class="checkbox">
                            <input type="checkbox" id="player-check-1" name="player-check" value="yes">
                            <label for="player-check-1"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs align-self-center mr-2" src="/images/avatar-1.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
                            <div class="heading-mid">Marko Novak</div>
                            <div class="heading-small-message mt-1">Bok, jesi li zainteresiran za meč? Imajući u vidu vrućine, možda bi bilo najbolje da odigramo kasno popodne. Što kažeš?</div>
                        </div>
                    </div>
                </div>
            </a>
            <a class="list" href="/messages/messages-details">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                        <div class="checkbox">
                            <input type="checkbox" id="player-check-4" name="player-check" value="yes">
                            <label for="player-check-4"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs align-self-center mr-2" src="/images/avatar-2.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
                            <div class="heading-mid">Marko Novak</div>
                            <div class="heading-small-message mt-1">Bok, jesi li zainteresiran za meč? </div>
                        </div>
                    </div>
                </div>
            </a>
            <a class="list" href="/messages/messages-details">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                        <div class="checkbox">
                            <input type="checkbox" id="player-check-3" name="player-check" value="yes">
                            <label for="player-check-3"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs align-self-center mr-2" src="/images/federer.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
                            <div class="heading-mid">Marko Novak</div>
                            <div class="heading-small-message mt-1">Bok, jesi li zainteresiran za meč? Možda bi bilo najbolje da odigramo kasno popodne.</div>
                        </div>
                    </div>
                </div>
            </a>
            <a class="list" href="/messages/messages-details">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                        <div class="checkbox">
                            <input type="checkbox" id="player-check-2" name="player-check" value="yes">
                            <label for="player-check-2"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs align-self-center mr-2" src="/images/avatar-2.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
                            <div class="heading-mid">Marko Novak</div>
                            <div class="heading-small-message mt-1">Bok, jesi li zainteresiran za meč? </div>
                        </div>
                    </div>
                </div>
            </a>
            <a class="list" href="/messages/messages-details">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                        <div class="checkbox">
                            <input type="checkbox" id="player-check-1" name="player-check" value="yes">
                            <label for="player-check-1"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs align-self-center mr-2" src="/images/avatar-1.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
                            <div class="heading-mid">Marko Novak</div>
                            <div class="heading-small-message mt-1">Bok, jesi li zainteresiran za meč? </div>
                        </div>
                    </div>
                </div>
            </a>
            <a class="list" href="/messages/messages-details">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                        <div class="checkbox">
                            <input type="checkbox" id="player-check-1" name="player-check" value="yes">
                            <label for="player-check-1"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs align-self-center mr-2" src="/images/federer.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
                            <div class="heading-mid">Marko Novak</div>
                            <div class="heading-small-message mt-1">Bok, jesi li zainteresiran za meč? Imajući u vidu vrućine, možda bi bilo najbolje da odigramo kasno popodne. Što kažeš?</div>
                        </div>
                    </div>
                </div>
            </a>
            <a class="list" href="/messages/messages-details">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                        <div class="checkbox">
                            <input type="checkbox" id="player-check-1" name="player-check" value="yes">
                            <label for="player-check-1"></label>
                        </div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs align-self-center mr-2" src="/images/avatar-2.jpg">
                        <div class="list-names">
                            <div class="heading-xs-date text-primary mb-1">27.09.2017.</div>
                            <div class="heading-mid">Marko Novak</div>
                            <div class="heading-small-message mt-1">Bok, jesi li zainteresiran za meč? Imajući u vidu vrućine, možda bi bilo najbolje da odigramo kasno popodne. Što kažeš?</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

</div>
@endsection