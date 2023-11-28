@extends('www.layout')

@section('title', 'Naslovnica')

@section('content')
        <div class="header_footer">
             <div class="container">
                 <div class="header_footer_left">
                     <div class="bullet-md">Povećajte profitabilnost, efikasnost i transparentnost poslovanja</div>
                     <div class="bullet-md">Podignite razinu zadovoljstva i lojalnosti vaših igrača </div>
                     <div class="bullet-md">Bez obveza! Ako niste zadovoljni sustavom, vraćamo vam novac</div>
                 </div>
                 <div class="header_footer_right">
                     <a class="btn" href="#">Isprobajte sustav <span>besplatno!</span></a>
                 </div>
             </div>
        </div>
        <div class="advantage container">
        <div class="main_title">Prednosti korištenja online teniskog<br> management sustava </div>
        <div class="advantage-box">
            <div class="advantage-club">
                <div class="club-image club-image-club">
                    <div class="">Teniski klub</div>
                </div>
                <div class="advantage-list">
                    <div class="advantage-item">Potpuna kontrola nad članovima i članarinama</div>
                    <div class="advantage-item">Napredno upravljanje terenima</div>
                    <div class="advantage-item">Mogućnost akcijskih cijena za neprodane termine</div>
                    <div class="advantage-item">Trenutno obavještavanje o prekidu igre (kiša...)</div>
                    <div class="advantage-item">Mjesečna izvješća o zauzetosti terena i prihodu</div>
                    <div class="advantage-item">Klupska rang ljestvica - automatsko rangiranje igrača po jakosti</div>
                    <div class="advantage-item">Jednostavno slanje poruka i obavijesti</div>
                    <div class="advantage-item">Povećanje produktivnosti voditelja klubova</div>
                </div>
            </div>
            <div class="advantage-club">
                <div class="club-image club-image-member">
                    <div class="">Članovi/igrači</div>
                </div>
                <div class="advantage-list">
                    <div class="advantage-item">Jednostavna i pregledna rezervacija terena</div>
                    <div class="advantage-item">Jednostavno pronalaženje slobodnih igrača za igru</div>
                    <div class="advantage-item">Praćenje svih rezultata u klubu</div>
                    <div class="advantage-item">Rang ljestvica</div>
                    <div class="advantage-item">Povijest međusobnih ogleda</div>
                    <div class="advantage-item">Korištenje ELO metode rangiranja koja se koristi u šahu, američkom nogometu, bejzbolu...</div>
                    <div class="advantage-item">Igra se proizvoljan broj mečeva na temelju kojih se dobiva rang jačine</div>
                </div>
            </div>
        </div>
    </div>

    <div class="functions container">
        <div class="main_title">Što omogućuje Tenis.plus</div>
        <div class="functions-box">
            <img src="{{ asset('images/www/function_mockup.jpg') }}" alt="">
            <div class="functions-list">
                <div class="functions-item">
                    <img src="{{ asset('images/www/reservation.svg') }}" alt="">
                    <h3>Rezervacija terena</h3>
                    <p>Najbrža opcija za rezervaciju teniskih terena i upravljanje svim trenutnim rezervacijama.</p>
                </div>
                <div class="functions-item">
                    <img src="{{ asset('images/www/players.svg') }}" alt="">
                    <h3>Pronađi igrača</h3>
                    <p>Jednostavno pronađi  koji je igrač slobodan za igru. Prilagodi svoj izbor filtriranjem igrača prema dobi i jačini.</p>
                </div>
                <div class="functions-item">
                    <img src="{{ asset('images/www/results.svg') }}" alt="">
                    <h3>prati rezultate</h3>
                    <p>Pregledaj odigrane mečeve unutar kluba. Kontinuirano prati svoj napredak kroz statistiku, a po potrebi provjeri i povijest međusobnih mečeva.</p>
                </div>
                <div class="functions-item">
                    <img src="{{ asset('images/www/top-list.svg') }}" alt="">
                    <h3>rang liste</h3>
                    <p>Budi u toku sa svim promjenama na rang listi koja se osvježava nakon svakog odigranog verificiranog meča. </p>
                </div>
                <div class="functions-item">
                    <img src="{{ asset('images/www/news.svg') }}" alt="">
                    <h3>klupske novosti</h3>
                    <p>Informiraj se o svim klupskim novostima i ostalim događanjima unutar i oko kluba. </p>
                </div>
                <div class="functions-item">
                    <img src="{{ asset('images/www/messages.svg') }}" alt="">
                    <h3>POŠALJI PORUKU</h3>
                    <p>Slanjem poruke izravno stupi u kontakt s drugim članovima kluba i dogovori sljedeći meč.</p>
                </div>
                <div class="functions-item">
                    <img src="{{ asset('images/www/schedule.svg') }}" alt="">
                    <h3>Raspored mečeva</h3>
                    <p>Ne propustite klupske derbije pregledom svih dogovorenih mečeva po danima.</p>
                </div>
                <div class="functions-item">
                    <img src="{{ asset('images/www/shop.svg') }}" alt="">
                    <h3>Klupski oglasnik</h3>
                    <p>Objavite oglas i prodajte rabljenu ili novu tenisku opremu jednostavno i brzo.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="newsletter">
        <div class="container">
            <h1>PRIJAVI SE NA NEWSLETTER <br>I NE PROPUSTI NOVOSTI, AKCIJE I NOVE FUNKCIONALNOSTI</h1>
            <form>
                <input class="newsletter-input" type="text" name="mail" placeholder="Upiši e-mail">
                <input class="send" type="submit" value="Pretplati se">
            </form>
            <!--                <h3 class="newsletter-small">ili nas pratite na <a href="www.facebook.com"><img src="img/facebook.svg"> Facebooku</a></h3>-->
        </div>
    </div>
@endsection