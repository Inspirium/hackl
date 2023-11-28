<?php

return [
    'Nova slika' => 'Нова слика',
    'Prijedlog termina' => 'Предлог за именовање',
    'App\Notifications\TestNotification' => [
        'title' => 'Тестно обавештење {name}',
        'body' => '{firstName} {lastName} је послао тестно обавештење',
    ],
    'App\Notifications\WeatherUpdate' => [
        'title' => 'Временски услови на терену',
        'body' => '',
    ],
    'App\Notifications\WatcherAnnounced' => [
        'title' => 'Најава доласка гледалаца',
        'body' => '{firstName} {lastName} је најавио да долази на термин: {time} на {court}',
    ],
    'App\Notifications\TournamentStart' => [
        'title' => 'Такмичење почиње',
        'body' => '{tournament} такмичење је почело',
    ],
    'App\Notifications\SpannungComplete' => [
        'title' => 'Спанунг је завршен',
        'body' => 'Ваш рекет је спреман за преузимање',
    ],
    'App\Notifications\ResultVerified' => [
        'title' => 'Потврда резултата',
        'body' => '{name} је потврдило резултат',
    ],
    'App\Notifications\ResultDisputed' => [
        'title' => 'Оспоравање резултата',
        'body' => '{name} је оспорио резултат',
    ],
    'App\Notifications\ReservationCanceled' => [
        'title' => 'Резервација је отказана',
        'body' => 'Резервација је отказана од {time} до {court}',
    ],
    'App\Notifications\RequestResultVerificationNotification' => [
        'title' => 'Верификација резултата',
        'body' => '{name} захтева верификацију резултата',
    ],
    'App\Notifications\NewResultComment' => [
        'title' => 'Нови коментар резултата',
        'body' => '{name}: {message}',
    ],
    'App\Notifications\NewReservation' => [
        'title' => 'Нова резервација',
        'body' => '{name} резервисало термин: {time} на {court}',
    ],
    'App\Notifications\NewProfileData' => [
        'title' => 'Ново профилисање ваше игре',
        'body' => '{name} је профилисао вашу игру',
    ],
    'App\Notifications\NewPlayerInLeague' => [
        'title' => 'Нови играч у лиги {name}',
        'body' => '{name} се пријавило за вашу лигу {league}',
    ],
    'App\Notifications\NewPlayerInTournament' => [
        'title' => 'Нови играч у такмичењу {name}',
        'body' => '{name} је пријавило ваше такмичење {tournament}',
    ],
    'App\Notifications\NewNews' => [
        'title' => 'Нове вести',
        'body' => '',
    ],
    'App\Notifications\NewMultipleReservation' => [
        'title' => 'Вишеструка резервација',
        'body' => '{name} резервисало више термина {court}',
    ],
    'App\Notifications\NewMessage' => [
        'title' => 'Нова порука од {name}',
        'body' => '',
    ],
    'App\Notifications\NewMember' => [
        'title' => 'Нови члан у клубу',
        'body' => '{name} се пријавио у ваш клуб',
    ],
    'App\Notifications\NewClassifiedComment' => [
        'title' => 'Нови коментар на оглас {name}',
        'body' => '',
    ],
    'App\Notifications\NewApplicant' => [
        'title' => 'Нови захтев за чланство',
        'body' => '{name} је послао захтев за чланство',
    ],
    'App\Notifications\LeagueStart' => [
        'title' => 'Почиње лига',
        'body' => '{name} лига је почела',
    ],
    'App\Notifications\InvitedToReservation' => [
        'title' => 'Позовите за резервацију',
        'body' => '{name} вас је позвао да резервишете {time} на {court}',
    ],
    'App\Notifications\ApplicationApproved' => [
        'title' => 'Пријава за чланство је одобрена',
        'body' => 'Ваша пријава за чланство у клубу {name} је одобрена',
    ],
    'App\Notifications\AddedToReservation' => [
        'title' => 'Додати сте у резервацију',
        'body' => '{name} вас је додао у резервацију {time} на {court}',
    ],
    'App\Notifications\AddedToMultipleReservations' => [
        'title' => 'Додати сте у вишеструку резервацију',
        'body' => '{name} вас је додао у вишеструку резервацију {court}',
    ],
    'App\Notifications\Shop\NewOrderCreated' => [
        'title' => 'Нови поредак',
        'body' => '{name} је послао нову наруџбу',
    ],
    'App\Notifications\Shop\NewWorkOrderCreated' => [
        'title' => 'Нови радни налог',
        'body' => '{name} је послао нови радни налог',
    ],
    'App\Notifications\Shop\NewOrderCompleted' => [
        'title' => 'Наруџбина је завршена',
        'body' => '{name} је завршио наруџбу',
    ],
    'App\Notifications\Shop\NewWorkOrderCompleted' => [
        'title' => 'Радни налог је завршен',
        'body' => '{name} је завршио радни налог',
    ],
];
