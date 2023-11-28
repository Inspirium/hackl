<?php

return [
    'Nova slika' => 'Nova slika',
    'Prijedlog termina' => 'Prijedlog termina',
    'App\Notifications\TestNotification' => [
        'title' => 'Test notification {name}',
        'body' => '{firstName} {lastName} je poslao testnu obavijest',
    ],
    'App\Notifications\WeatherUpdate' => [
        'title' => 'Vremenske prilike na terenu',
        'body' => 'body',
    ],
    'App\Notifications\WatcherAnnounced' => [
        'title' => 'Najava dolaska gledatelja',
        'body' => '{firstName} {lastName} se najavio da dolazi na termin: {time} na {court}',
    ],
    'App\Notifications\TournamentStart' => [
        'title' => 'Natjecanje počinje',
        'body' => '{tournament} natjecanje je počelo',
    ],
    'App\Notifications\SpannungComplete' => [
        'title' => 'Španung je završen',
        'body' => 'Vaš reket je spreman za preuzimanje',
    ],
    'App\Notifications\ResultVerified' => [
        'title' => 'Potvrda rezultata',
        'body' => '{name} je potvrdio rezultat',
    ],
    'App\Notifications\ResultDisputed' => [
        'title' => 'Osporavanje rezultata',
        'body' => '{name} je osporio rezultat',
    ],
    'App\Notifications\ReservationCanceled' => [
        'title' => 'Rezervacija otkazana',
        'body' => 'Rezervacija je otkazana {time} na {court}',
    ],
    'App\Notifications\ReservationCanceledPayment' => [
        'title' => 'Rezervacija otkazana jer nije plaćena',
        'body' => 'Rezervacija je otkazana {time} na {court}',
    ],
    'App\Notifications\RequestResultVerificationNotification' => [
        'title' => 'Verifikacija rezultata',
        'body' => '{name} je zatražio verifikaciju rezultata',
    ],
    'App\Notifications\NewResultComment' => [
        'title' => 'Novi komentar na rezultatu',
        'body' => '{name}: {message}',
    ],
    'App\Notifications\NewReservation' => [
        'title' => 'Nova rezervacija',
        'body' => '{name} je rezervirao termin: {time} na {court}',
    ],
    'App\Notifications\NewProfileData' => [
        'title' => 'Novo profiliranje Vaše igre',
        'body' => '{name} je profilirao Vašu igru',
    ],
    'App\Notifications\NewPlayerInLeague' => [
        'title' => 'Novi igrač u ligi {name}',
        'body' => '{name} se prijavio u Vašu ligu {league}',
    ],
    'App\Notifications\NewPlayerInTournament' => [
        'title' => 'Novi igrač u natjecanju {name}',
        'body' => '{name} se prijavio u Vaše natjecanje {tournament}',
    ],
    'App\Notifications\NewNews' => [
        'title' => 'Nova vijest',
        'body' => 'body',
    ],
    'App\Notifications\NewMultipleReservation' => [
        'title' => 'Višestruka rezervacija',
        'body' => '{name} je rezervirao više termina na {court}',
    ],
    'App\Notifications\NewMessage' => [
        'title' => 'Nova poruka od {name}',
        'body' => 'body',
    ],
    'App\Notifications\NewMember' => [
        'title' => 'Novi član u klubu',
        'body' => '{name} se prijavio u Vaš klub',
    ],
    'App\Notifications\NewClassifiedComment' => [
        'title' => 'Novi komentar na oglas {name}',
        'body' => 'body',
    ],
    'App\Notifications\NewApplicant' => [
        'title' => 'Novi zahtjev za članstvo',
        'body' => '{name} je poslao zahtjev za članstvo',
    ],
    'App\Notifications\LeagueStart' => [
        'title' => 'Liga počinje',
        'body' => '{name} liga je počela',
    ],
    'App\Notifications\InvitedToReservation' => [
        'title' => 'Poziv na rezervaciju',
        'body' => '{name} Vas je pozvao na rezervaciju {time} na {court}',
    ],
    'App\Notifications\ApplicationApproved' => [
        'title' => 'Prijava za članstvo odobrena',
        'body' => 'Vaša prijava za članstvo u klubu {name} je odobrena',
    ],
    'App\Notifications\AddedToReservation' => [
        'title' => 'Dodani ste na rezervaciju',
        'body' => '{name} Vas je dodao na rezervaciju {time} na {court}',
    ],
    'App\Notifications\AddedToMultipleReservations' => [
        'title' => 'Dodani ste na višestruku rezervaciju',
        'body' => '{name} Vas je dodao na višestruku rezervaciju na {court}',
    ],
    'App\Notifications\Shop\NewOrderCreated' => [
        'title' => 'Nova narudžba',
        'body' => '{name} je poslao novu narudžbu',
    ],
    'App\Notifications\Shop\NewWorkOrderCreated' => [
        'title' => 'Novi radni nalog',
        'body' => '{name} je poslao novi radni nalog',
    ],
    'App\Notifications\Shop\NewOrderCompleted' => [
        'title' => 'Narudžba gotova',
        'body' => '{name} je završio narudžbu',
    ],
    'App\Notifications\Shop\NewWorkOrderCompleted' => [
        'title' => 'Radni nalog je završen',
        'body' => '{name} je završio radni nalog',
    ],
    'App\Notifications\SchoolGroupInvoicesCreated' => [
        'title' => 'Novi računi za grupu',
        'body' => 'Pripremljeni su računi za grupu {name}',
    ],

    'Sve obavijesti' => 'Sve obavijesti',
    'Dodavanje na rezervaciju' => 'Dodavanje na rezervaciju',
    'Pozivanje na rezervaciju' => 'Pozivanje na rezervaciju',
    'Novi član' => 'Novi član',
    'Nova rezervacija' => 'Nova rezervacija',
    'Otkazivanje rezervacije' => 'Otkazivanje rezervacije',
    'Nova poruka' => 'Nova poruka',
    'Novi komentar na rezultatu' => 'Novi komentar na rezultatu',
    'Odbijen rezultat' => 'Odbijen rezultat',
    'Potvrđen rezultat' => 'Potvrđen rezultat',
    'Potvrda rezultata' => 'Potvrda rezultata',
    'Novi komentar na oglasu' => 'Novi komentar na oglasu',
    'Prihvaćena prijava' => 'Prihvaćena prijava',
    'Početak lige' => 'Početak lige',
    'Nova prijava za članstvo' => 'Nova prijava za članstvo',
    'Novi igrač u ligi' => 'Novi igrač u ligi',
    'Novi igrač u turniru' => 'Novi igrač u turniru',
    'Rezervacija otkazana' => 'Rezervacija otkazana',
    'Završen španung' => 'Završen španung',
    'Početak turnira' => 'Početak turnira',
    'Ažuriranje vremena' => 'Ažuriranje vremena',
    'Obavijest o gledanju' => 'Obavijest o gledanju',
    'Nova narudžba' => 'Nova narudžba',
    'Nova radna narudžba' => 'Nova radna narudžba',
];
