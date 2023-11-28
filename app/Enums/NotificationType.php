<?php

namespace App\Enums;

use ArchTech\Enums\Options;

enum NotificationType: string
{
    use Options;

    case ALL = 'Sve obavijesti';
    case RESERVATION_ADD = 'Dodavanje na rezervaciju';
    case RESERVATION_INVITE = 'Pozivanje na rezervaciju';
    case ADMIN_MEMBER_NEW = 'Novi član';
    case ADMIN_RESERVATION_NEW = 'Nova rezervacija';
    case ADMIN_RESERVATION_CANCEL = 'Otkazivanje rezervacije';
    case MESSAGE_NEW = 'Nova poruka';
    case RESULT_COMMENT = 'Novi komentar na rezultatu';
    case RESULT_DISPUTED = 'Odbijen rezultat';
    case RESULT_VERIFIED = 'Potvrđen rezultat';
    case RESULT_VERIFICATION = 'Potvrda rezultata';
    case CLASSIFIED_NEW_MESSAGE = 'Novi komentar na oglasu';

    case APPLICATION_APPROVED = 'Prihvaćena prijava';

    case LEAGUE_START = 'Početak lige';
    case ADMIN_NEW_APPLICANT = 'Nova prijava za članstvo';

    case ADMIN_LEAGUE_NEW_PLAYER = 'Novi igrač u ligi';

    case ADMIN_NEW_PLAYER_IN_TOURNAMENT = 'Novi igrač u turniru';
    case RESERVATION_CANCELED = 'Rezervacija otkazana';
    case SPANNUNG_COMPLETE = 'Završen španung';

    case TOURNAMENT_START = 'Početak turnira';
    case WEATHER_UPDATE = 'Ažuriranje vremena';
    case WATCHER_ANNOUNCED = 'Obavijest o gledanju';
    case NEW_ORDER_CREATED = 'Nova narudžba';
    case NEW_WORK_ORDER_CREATED = 'Nova radna narudžba';

}
