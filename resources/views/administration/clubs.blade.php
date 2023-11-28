@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col nopadding">
            <div class="header-image">
               <div class="gradient"></div>
               <div class="heading-image">Klubovi</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="text-center">
                <a class="btn btn-primary btn-lg my-3" role="button" href="/administration/club-new">Dodaj novi klub</a>
            </div>
            <div class="search-bar mt-2">
                <input class="form-control" type="search" placeholder="Ponađi klub" id="example-search-input">
                <i class="fa fa-search icon-search" aria-hidden="true"></i>
                <div class="clearfix"></div>
            </div>
            <div class="select-dialog mb-3">
                <div class="heading-mid-dialog">Ukupno <span class="">14 klubova</span></div>
            </div>
        </div>
    </div>       


<!-- Table list no checkbox -->
<div class="row">
    <div class="col">
        <div class="list-container mt-2 mb-4">
            <a class="list" href="/messages/messages-details">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/logo.jpg">
                        <div class="list-names">
                            <div class="heading-mid">Tenis centar Zaprešić</div>
                            <div class="heading-xs mt-1"><span class="bold">Zaprešić </span>| 091-4502-752</div>
                        </div>
                    </div>
                    <div class="action">
                        <div class="power-medium bg-primary">290</div>
                    </div>
                </div>
            </a>
            <a class="list" href="/messages/messages-details">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/logo.jpg">
                        <div class="list-names">
                            <div class="heading-mid">Lamaki tenis</div>
                            <div class="heading-xs mt-1"><span class="bold">zagreb </span>| 091-4502-752</div>
                        </div>
                    </div>
                    <div class="action">
                        <div class="power-medium bg-primary">122</div>
                    </div>
                </div>
            </a>
            <a class="list" href="/messages/messages-details">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/logo.jpg">
                        <div class="list-names">
                            <div class="heading-mid">Tenis centar Concordia</div>
                            <div class="heading-xs mt-1"><span class="bold">zagreb </span>| 091-4502-752</div>
                        </div>
                    </div>
                    <div class="action">
                        <div class="power-medium bg-primary">86</div>
                    </div>
                </div>
            </a>
            <a class="list" href="/messages/messages-details">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/logo.jpg">
                        <div class="list-names">
                            <div class="heading-mid">Tenis centar Maksimir</div>
                            <div class="heading-xs mt-1"><span class="bold">zagreb </span>| 091-4502-752</div>
                        </div>
                    </div>
                    <div class="action">
                        <div class="power-medium bg-primary">189</div>
                    </div>
                </div>
            </a>
            <a class="list" href="/messages/messages-details">
                <div class="list-item">
                    <div class="heading-num-small align-self-center">
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" src="/images/logo.jpg">
                        <div class="list-names">
                            <div class="heading-mid">Tenis centar Varaždin</div>
                            <div class="heading-xs mt-1"><span class="bold">Varaždin </span>| 091-4502-752</div>
                        </div>
                    </div>
                    <div class="action">
                        <div class="power-medium bg-primary">45</div>
                    </div>
                </div>
            </a>

        </div>
    </div>
</div>


</div>
@endsection