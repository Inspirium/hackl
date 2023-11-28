<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <!--  Meta stuff -->
    <title>Invoice - Tennis.plus</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
            href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap'"
            rel="stylesheet">

</head>

<body>
<div class="full flex racun-wrapper">
    <div class="full racun">
        <div class="full racun-header bottom-margin">
            <div class="float-left right-margin half">
                <img src="{{ $invoice->club->logo }}" width="138" height="138" alt="Logo">
            </div>
            <div class="float-right half font-x2">
                <p class="full bold">{{ $invoice->club->business_data['subject_name'] ?? $invoice->club->name }}</p>
                <p class="full">{{ $invoice->club->business_data['subject_address'] ?? $invoice->club->address }}</p>
                @if(isset($invoice->club->business_data['bank_account']))
                    <p class="full">ŽR: {{ $invoice->club->business_data['bank_account'] }}</p>
                @endif
                @if(isset($invoice->club->business_data['subject_number']))
                    <p class="full">MBS: {{ $invoice->club->business_data['subject_number'] }}</p>
                @endif
                @if(isset($invoice->club->business_data['oib']))
                    <p class="full">OIB: {{ $invoice->club->business_data['oib'] }}</p>
                @endif
                @if(isset($invoice->club->business_data['swift_code']))
                <p class="full">SWIFT: {{ $invoice->club->business_data['swift_code'] }}</p>
                @endif
            </div>
        </div>
        @if($invoice->invoice_data['company'])
            <p style="font-size: 20px; margin-top: 10px; margin-bottom: 10px">R1 račun</p>
        @endif
        <div class="full half float-left racun-info">
            <div class="full">
                <div class="float-left two-thirds bold">RAČUN broj: </div><div class="float-left third bold">{{ $invoice->invoice_number }}</div>
                <div class="float-left two-thirds">Vrijeme: </div><div class="float-left  third bold">{{ $invoice->created_at->format('H:i') }}</div>
                <div class="float-left two-thirds">Datum izdavanja računa: </div><div class="float-left  third bold">{{ $invoice->created_at->format('d.m.Y.') }}</div>
                <div class="float-left two-thirds">Mjesto izdavanja: </div><div class="float-left  third bold">{{ $invoice->club->city }}</div>
                <div class="float-left two-thirds">OIB: </div><div class="float-left  third bold">{{ $invoice->club->business_data['oib'] }}</div>
            </div>
        </div>
        <div class="float-right half racun-client">
            <div class="float-right two-thirds">
                <div style="font-weight: bold; margin-top: -15px;" class="float-left two-thirds bold">Klijent:</div>

                <p class="full">{{ $invoice->invoice_data['name'] ?? $invoice->user->display_name }}</p>
                @if($invoice->invoice_data['company'])
                    <p class="full">{{ $invoice->invoice_data['company'] }}</p>
                    @if($invoice->invoice_data['vat_id'])
                    <p class="full">{{ $invoice->invoice_data['vat_id'] }}</p>
                    @endif
                @endif
                <p class="full">{{ $invoice->invoice_data['address'] ?? $invoice->user->address }}</p>
                @if( $invoice->invoice_data['address2'] )
                <p class="full">{{ $invoice->invoice_data['address2']  }}</p>
                @endif
                <p class="full">{{ $invoice->invoice_data['postal_code'] ?? $invoice->user->postal_code }} {{ $invoice->invoice_data['city'] ?? $invoice->user->city }}</p>
                <p class="full">{{ $invoice->invoice_data['country'] ?? ($invoice->user->country ? $invoice->user->country->name : '' ) }}</p>

            </div>
        </div>
        <div class="full racun-table">
            <div class="full red-row bold center-text table-row">
                <div class="float-left thirty">Opis</div>
                <div class="float-left tenth">Količina (h)</div>
                <div class="float-left fifty">Neto cijena sata</div>
                <div class="float-left tenth">Ukupno neto</div>
            </div>
            @foreach ($invoice->items as $item)
            <div class="full table-row bottom-border center-text stretch">
                <div class="float-left thirty">{{ $item->name }}</div>
                <div class="float-left tenth">{{ $item->quantity }}</div>
                <div class="float-left fifty">{{ number_format($item->amount, 2) }} {{ $item->currency }}</div>
                <div class="float-left tenth right-align right-text">{{ number_format($item->total_amount, 2) }} {{ $item->currency }}</div>
            </div>
            @endforeach
            <div class="full table-row right-text">
                <div class="third float-right">
                    <div class="float-left two-thirds bold">Osnovica: </div><div class="float-right third bold right-align">{{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}</div>
                    @if($invoice->taxes)
                    @foreach ($invoice->taxes as $tax)
                    <div class="float-left two-thirds bold">{{ $tax['tax_type'] }} {{ $tax['tax'] }}%: </div><div  class="float-right third bold right-align">{{ number_format($tax['tax_amount'], 2) }} {{ $invoice->currency }}</div>
                    @endforeach
                    @endif
                </div>
            </div>
            <div class="full red-row table-row bold right-text">
                <div class="half float-right font-x2">
                    <div class="float-left two-thirds bold">UKUPNO: </div><div class="float-right right-text third bold right-align">{{ number_format($invoice->total_amount, 2) }}  {{ $invoice->currency }}</div>
                </div>
                @if($invoice->club->country_id === 1)
                    <div class="float-left two-thirds bold"></div><div class="float-right right-text third bold right-align">{{ number_format($invoice->total_amount * 7.53450, 2) }}  HRK</div>
                @endif
            </div>
        </div>
        <div class="full" style="margin-top: 30px;">
            <p class="full">Račun izrađen: Račun je automatski generiran putem aplikacije TenisPlus</p>
            <p class="full">Odgovorna osoba: {{ $invoice->operator->display_name }}</p>
            <p class="full">Plaćanje: {{ $invoice->getPaymentMethod() }}</p>
            @if($invoice->fiscalization['zki'])
            <p>ZKI: {{ $invoice->fiscalization['zki'] }}</p>
            <p>JIR: {{ $invoice->fiscalization['jir'] }}</p>
            <p style="margin: 10px;"><img src="data:image/png;base64,{{ \Milon\Barcode\Facades\DNS2DFacade::getBarcodePNG($invoice->fiscalization['url'], 'QRCODE') }}"></p>
            @endif
        </div>
        @if($invoice->payment_status !== 'PAID')
        <div class="full">
            <img src="data:image/png;base64,{{ $invoice->barcode }}" style="background-color: white;" width="300">
        </div>
        @endif
        @if($invoice->business_unit->notes)
            @foreach(explode("\n", $invoice->business_unit->notes) as $note)
        <p class="font-size: 10px; margin-top: 10px">{{ $note }}</p>
            @endforeach
            @endif

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
        font-family: Quicksand, sans-serif;
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
        font-weight: 600;
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
        font-weight: 400;
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
        border-bottom: 1px dashed #111;
    }
    .red-row {
        background-color: #dadada;
        color: #3b3b3b;
        border-bottom: none;
        border-radius: 10px;
    }
    .red-row > * {
        padding: 8px 4px;
    }
    .right-align {
        justify-content: flex-end;
    }
    .font-x2 {
        font-size: 1rem;
    }
</style>
</body>

</html>
