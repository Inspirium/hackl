<?php

return [
    'Nova slika' => 'Нова слика',
    'Prijedlog termina' => 'Предлог за назначување',
    'App\Notifications\TestNotification' => [
        'title' => 'Известување за тестирање {name}',
        'body' => '{firstName} {lastName} испрати известување за тест',
    ],
    'App\Notifications\WeatherUpdate' => [
        'title' => 'Временските услови на теренот',
        'body' => '',
    ],
    'App\Notifications\WatcherAnnounced' => [
        'title' => 'Најава за пристигнување на гледачи',
        'body' => '{firstName} {lastName} објави дека доаѓа на состанокот: {time} на {court}',
    ],
    'App\Notifications\TournamentStart' => [
        'title' => 'Натпреварот започнува',
        'body' => '{tournament} натпреварот започна',
    ],
    'App\Notifications\SpannungComplete' => [
        'title' => 'Spanung е завршен',
        'body' => 'Вашиот рекет е подготвен за подигање',
    ],
    'App\Notifications\ResultVerified' => [
        'title' => 'Потврда на резултатите',
        'body' => '{name} го потврди резултатот',
    ],
    'App\Notifications\ResultDisputed' => [
        'title' => 'Оспорување на резултатите',
        'body' => '{name} го оспори резултатот',
    ],
    'App\Notifications\ReservationCanceled' => [
        'title' => 'Резервацијата е откажана',
        'body' => 'Резервацијата беше откажана {time} спрема {court}',
    ],
    'App\Notifications\RequestResultVerificationNotification' => [
        'title' => 'Верификација на резултатите',
        'body' => '{name} побара верификација на резултатот',
    ],
    'App\Notifications\NewResultComment' => [
        'title' => 'Нов коментар за резултатот',
        'body' => '{name}: {message}',
    ],
    'App\Notifications\NewReservation' => [
        'title' => 'Нова резервација',
        'body' => '{name} резервирал термин: {time} на {court}',
    ],
    'App\Notifications\NewProfileData' => [
        'title' => 'Ново профилирање на вашата игра',
        'body' => '{name} ја профилираше вашата игра',
    ],
    'App\Notifications\NewPlayerInLeague' => [
        'title' => 'Novi igrač u ligi {name}',
        'body' => '{name} се регистрираа во вашата лига {league}',
    ],
    'App\Notifications\NewPlayerInTournament' => [
        'title' => 'Novi igrač u natjecanju {name}',
        'body' => '{name} влезе во вашата конкуренција {tournament}',
    ],
    'App\Notifications\NewNews' => [
        'title' => 'Нова вест',
        'body' => '',
    ],
    'App\Notifications\NewMultipleReservation' => [
        'title' => 'Повеќекратна резервација',
        'body' => '{name} резервирале повеќе состаноци на {court}',
    ],
    'App\Notifications\NewMessage' => [
        'title' => 'Нова порака од {name}',
        'body' => '',
    ],
    'App\Notifications\NewMember' => [
        'title' => 'Нов член во клубот',
        'body' => '{name} се регистрираа во вашиот клуб',
    ],
    'App\Notifications\NewClassifiedComment' => [
        'title' => 'Нов коментар на огласот {name}',
        'body' => '',
    ],
    'App\Notifications\NewApplicant' => [
        'title' => 'Ново барање за членство',
        'body' => '{name} испрати барање за членство',
    ],
    'App\Notifications\LeagueStart' => [
        'title' => 'Почнува лигата',
        'body' => '{name} лигата започна',
    ],
    'App\Notifications\InvitedToReservation' => [
        'title' => 'Јавете се за резервација',
        'body' => '{name} ве покани да резервирате {time} на {court}',
    ],
    'App\Notifications\ApplicationApproved' => [
        'title' => 'Одобрена е апликацијата за членство',
        'body' => 'Вашата апликација за членство во клубот {name} е одобрена',
    ],
    'App\Notifications\AddedToReservation' => [
        'title' => 'Додадени сте во резервацијата',
        'body' => '{name} ве додаде во резервацијата {time} на {court}',
    ],
    'App\Notifications\AddedToMultipleReservations' => [
        'title' => 'Додадени сте на повеќекратна резервација',
        'body' => '{name} ве додаде на повеќекратна резервација на {court}',
    ],
    'App\Notifications\Shop\NewOrderCreated' => [
        'title' => 'Нов поредок',
        'body' => '{name} испрати нова нарачка',
    ],
    'App\Notifications\Shop\NewWorkOrderCreated' => [
        'title' => 'Нов работен налог',
        'body' => '{name} испрати нов работен налог',
    ],
    'App\Notifications\Shop\NewOrderCompleted' => [
        'title' => 'Нарачката е завршена',
        'body' => '{name} ја заврши нарачката',
    ],
    'App\Notifications\Shop\NewWorkOrderCompleted' => [
        'title' => 'Работниот налог е завршен',
        'body' => '{name} го заврши работниот налог',
    ],
];
