<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <title>Telegram Media Grupa račun</title>
    <!--  Meta stuff -->
    <meta charset="UTF-8">
    <meta name="title" content="Telegram Media Grupa račun">
    <meta name="description" content="Vaš račun za narudžbu paketa pretplate Telegram Media Grupe">
    <meta name="author" content="Telegram Studio - Telegramov studio za kreativna rješenja">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://kit.fontawesome.com/4878256e09.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://use.typekit.net/yjw4lwh.css">
    <link
            href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap'"
            rel="stylesheet">

</head>

<body>
<div class="full flex racun-wrapper">
    <div class="full racun">
        <div class="full racun-header bottom-margin">
            <div class="float-left right-margin half">
                <img src="https://www.telegram.hr/wp-content/themes/telegram2-desktop/assets/img/tg_neue_favicon.png" width="138" height="138" alt="Telegram Logo">
            </div>
            <div class="float-right half">
                <p class="full bold">Telegram media grupa d.o.o.</p>
                <p class="full">Franje Petračića, 10000 Zagreb, Hrvatska</p>
                <p class="full">ŽR: IBAN HR73 23600001101437160</p>
                <p class="full">MBS: 080341252,</p>
                <p class="full">OIB: 36974788949</p>
                <p class="full">SWIFT: ZABAHR2X</p>
            </div>
        </div>
        <div class="full half float-left racun-info">
            <div class="full">
                <div class="float-left two-thirds bold">RAČUN broj: </div><div class="float-left third bold">{{-- $subscription->receipt_number --}}/TG01/1</div>
                <div class="float-left two-thirds">Vrijeme: </div><div class="float-left  third bold">{{-- $subscription->created_at->format('H:i') --}}</div>
                <div class="float-left two-thirds">Datum izdavanja računa: </div><div class="float-left  third bold">{{-- $subscription->created_at->format('d.m.Y.') --}}</div>
                <div class="float-left two-thirds">Mjesto izdavanja: </div><div class="float-left  third bold">Zagreb</div>
                <div class="float-left two-thirds">OIB: </div><div class="float-left  third bold">36974788949</div>
            </div>
        </div>
        <div class="half float-left half racun-client">
            <div class="float-left two-thirds bold">Klijent: </div>
            <div class="float-left third">
                <p class="full">{{-- $subscription->customer->first_name }} {{ $subscription->customer->last_name --}}</p>
                <p class="full">{{-- $subscription->customer->company --}}</p>
                <p class="full">{{-- $subscription->customer->address --}}</p>
                <p class="full">{{-- $subscription->customer->oib --}}</p>
            </div>
        </div>
        <div class="full racun-table">
            <div class="full red-row table-row bold center-text">
                <div class="float-left thirty">Opis</div>
                <div class="float-left tenth">Količina</div>
                <div class="float-left fifty">&nbsp;</div>
                <div class="float-left tenth">Neto</div>
            </div>
            <div class="full table-row bottom-border center-text stretch">
                <div class="float-left thirty">{{-- $subscription->name --}}</div>
                <div class="float-left tenth">1</div>
                <div class="float-left fifty">&nbsp;</div>
                <div class="float-left tenth right-align">{{-- $subscription->storno?'-':'' }}{{ number_format( $subscription->tax_base, 2, ',') }} {{ $subscription->created_at->format('Y') < '2023'?'kn':'EUR' --}}</div>
            </div>
            <div class="full table-row center-text">
                <div class="third float-right">
                    <div class="float-left two-thirds bold">Osnovica: </div><div class="float-right third bold right-align">{{-- $subscription->storno?'-':'' }}{{ number_format( $subscription->tax_base, 2, ',') }} {{ $subscription->created_at->format('Y') < '2023'?'kn':'EUR' --}}</div>
                    <div class="float-left two-thirds bold">PDV 5%: </div><div class="float-right third bold right-align">{{-- $subscription->storno?'-':'' }}{{ number_format( $subscription->tax_amount, 2, ',') }} {{ $subscription->created_at->format('Y') < '2023'?'kn':'EUR' --}}</div>
                </div>
            </div>
            <div class="full red-row table-row bold center-text">
                <div class="third float-right">
                    @if($subscription->created_at->format('Y') >= '2023')
                        <div class="float-left two-thirds bold">UKUPNO: </div><div class="float-right third bold right-align">{{-- $subscription->storno?'-':'' }}{{ number_format( $subscription->total_amount, 2, ',') --}} EUR</div>
                        <div class="float-left two-thirds bold">UKUPNO: </div><div class="float-right third bold right-align">{{-- $subscription->storno?'-':'' }}{{ number_format( $subscription->total_amount * 7.53450, 2, ',') --}} kn</div>
                    @else
                        <div class="float-left two-thirds bold">UKUPNO: </div><div class="float-right third bold right-align">{{-- $subscription->storno?'-':'' }}{{ number_format( $subscription->total_amount, 2, ',') --}} kn</div>
                        <div class="float-left two-thirds bold">UKUPNO: </div><div class="float-right third bold right-align">{{-- $subscription->storno?'-':'' }}{{ number_format( $subscription->total_amount / 7.53450, 2, ',') --}} EUR</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="full" style="margin-top: 30px;">
            <p class="full">Plaćanje: Kartica</p>
            <p>ZKI: {{-- $subscription->zki --}}</p>
            <p>JIR: {{-- $subscription->jir --}}</p>
            <p style="margin: 10px;"><img src="data:image/png;base64,{{-- \Milon\Barcode\Facades\DNS2DFacade::getBarcodePNG($subscription->qr_code_link, 'QRCODE') --}}"></p>
        </div>
        <p style="font-size: 10px;">Stopa konverzije kune u euro određena je na Vijeću za ekonomske i financijske poslove EU-a, 12.srpnja 2022., po središnjem paritetu, te iznosi 1 euro = 7,53450 kuna.</p>
    </div>
</div>
<style>
    /* CSS Reset */

    html {
        margin: 0;
        padding: 0;
        border: 0;
        overflow-x: hidden;
    }

    body,
    div,
    div,
    object,
    iframe,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p,
    blockquote,
    pre,
    a,
    abbr,
    acronym,
    address,
    code,
    del,
    dfn,
    em,
    img,
    q,
    dl,
    dt,
    dd,
    ol,
    ul,
    li,
    fieldset,
    form,
    label,
    legend,
    table,
    caption,
    tbody,
    tfoot,
    thead,
    tr,
    th,
    td,
    article,
    aside,
    dialog,
    figure,
    footer,
    header,
    hgroup,
    nav,
    section {
        margin: 0;
        padding: 0;
        border: 0;
        font-weight: inherit;
        font-style: inherit;
        font-size: 100%;
        font-family: inherit;
        vertical-align: baseline;
        list-style: none;
    }

    .main-container {
        width: 100%;
        overflow-x: hidden;
        position: relative;
    }

    /* Basics */

    body {
        -webkit-font-smoothing: antialiased;
    }

    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -ms-box-sizing: border-box;
        -o-box-sizing: border-box;
        box-sizing: border-box;
    }

    a {
        text-decoration: none;
        color: inherit;
        cursor: pointer;
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        -ms-transition: all 0.3s;
        -o-transition: all 0.3s;
        transition: all 0.3s;
    }

    :focus {
        outline: none;
    }

    /* Typography */

    b,
    strong,
    .bold {
        font-weight: 700;
    }

    .superbold {
        font-weight: 900;
    }

    i,
    em,
    .italic {
        font-style: italic;
    }

    .center-text {
        text-align: center;
    }

    .left-text {
        text-align: left;
    }

    .right-text {
        text-align: right;
    }

    .float-right {
        float: right;
    }

    .float-left {
        float: left;
    }

    .white-text {
        color: white;
    }

    .clickable {
        cursor: pointer;
    }

    .animate,
    svg {
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        -ms-transition: all 0.3s;
        -o-transition: all 0.3s;
        transition: all 0.3s;
        -webkit-transform: translateZ(0);
        -moz-transform: translateZ(0);
        -ms-transform: translateZ(0);
        -o-transform: translateZ(0);
        transform: translateZ(0);
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Layout */

    .flex {
        display: block;
        float: left;
        flex-flow: row;
        flex-wrap: wrap;
        align-items: flex-start;
        align-content: flex-start;
    }

    .flex:after {
        content: "";
        display: table;
        clear: both;
    }


    .half {
        width: 49.99%;
    }

    .third {
        width: 33.33%;
    }

    .two-thirds {
        width: 66.66%;
    }

    .full {
        width: 100%;
    }



    /* Functions */


    .relative {
        position: relative;
    }

    .center-text {
        text-align: center;
    }

</style>
<style>
    .racun-wrapper {
        padding: 8vw 4vw;
    }
    html {
        font-size: 20px;
        line-height: 1.15em;
        font-family: Quicksand, sans-serif;
        font-weight: 300;
        font-style: normal;
        color: #111;
    }
    .racun {
        font-size: 14px;
        font-size: 0.7rem;
    }
    .bigger {
        font-size: 18px;
        font-size: 0.9rem;
    }
    .right-margin {
        margin-right: 24px;
    }
    .bottom-margin {
        margin-bottom: 24px;
    }
    .racun-info {
        padding-bottom: 16px;
        border-bottom: 2px solid #111;
        margin-bottom: 16px;
    }
    .racun-client {
        margin-bottom: 48px;
    }
    .table-row {
        padding: 10px;
    }
    .table-row > * {
        padding: 15px;
        display: block;
        justify-content: center;
        align-items: center;
        align-content: center;
    }
    .thirty {
        width: 29.99%;
    }
    .tenth {
        width: 9.99%;
    }
    .fifty {
        width: 49.99%;
    }
    .bottom-border {
        border-bottom: 1px solid #111;
    }
    .red-row {
        background-color: #ae3737;
        color: white;
        border-bottom: none;
    }
    .red-row > * {
        padding: 8px 4px;
    }
    .right-align {
        justify-content: flex-end;
    }
</style>
</body>

</html>
