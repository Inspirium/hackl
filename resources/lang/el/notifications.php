<?php

return [
    'Nova slika' => 'Νέα εικόνα',
    'Prijedlog termina' => 'Πρόταση διορισμού',
    'App\Notifications\TestNotification' => [
        'title' => 'Ειδοποίηση δοκιμής {name}',
        'body' => '{firstName} {lastName} έστειλε μια δοκιμαστική ειδοποίηση',
    ],
    'App\Notifications\WeatherUpdate' => [
        'title' => 'Καιρικές συνθήκες στο γήπεδo',
    ],

    'App\Notifications\WatcherAnnounced' => [
        'title' => 'Ανακοίνωση προσέλευσης θεατών',
        'body' => '{firstName} {lastName} ανακοίνωσε ότι ερχόταν στο ραντεβού: {time} στις {court}',
    ],
    'App\Notifications\TournamentStart' => [
        'title' => 'Ο διαγωνισμός ξεκινά',
        'body' => '{tournament} ο διαγωνισμός ξεκίνησε',
    ],
    'App\Notifications\SpannungComplete' => [
        'title' => 'Το Spanung τελείωσε',
        'body' => 'Η ρακέτα σας είναι έτοιμη για παραλαβή',
    ],
    'App\Notifications\ResultVerified' => [
        'title' => 'Επιβεβαίωση αποτελεσμάτων',
        'body' => '{name} επιβεβαίωσε το αποτέλεσμα',
    ],
    'App\Notifications\ResultDisputed' => [
        'title' => 'Αμφισβητώντας τα αποτελέσματα',
        'body' => '{name} αμφισβήτησε το αποτέλεσμα',
    ],
    'App\Notifications\ReservationCanceled' => [
        'title' => 'Η κράτηση ακυρώθηκε',
        'body' => 'Η κράτηση ακυρώθηκε {time} προς {court}',
    ],
    'App\Notifications\RequestResultVerificationNotification' => [
        'title' => 'Επαλήθευση αποτελεσμάτων',
        'body' => '{name} ζήτησε επαλήθευση αποτελέσματος',
    ],
    'App\Notifications\NewResultComment' => [
        'title' => 'Νέο σχόλιο για το αποτέλεσμα',
        'body' => '{name}: {message}',
    ],
    'App\Notifications\NewReservation' => [
        'title' => 'Νέα κράτηση',
        'body' => '{name} έκλεισε ένα ραντεβού: {time} προς {court}',
    ],
    'App\Notifications\NewProfileData' => [
        'title' => 'Νέο προφίλ του παιχνιδιού σας',
        'body' => '{name} βαθμολόγησε το προφίλ σας',
    ],
    'App\Notifications\NewPlayerInLeague' => [
        'title' => 'Νέος παίκτης στο πρωτάθλημα {name}',
        'body' => '{name} εγγράφηκε στο πρωτάθλημά σας {league}',
    ],
    'App\Notifications\NewPlayerInTournament' => [
        'title' => 'Νέος παίκτης στον αγώνα {name}',
        'body' => '{name} συμμετείχε στον διαγωνισμό σας {tournament}',
    ],
    'App\Notifications\NewNews' => [
        'title' => 'Νέα νέα',
    ],
    'App\Notifications\NewMultipleReservation' => [
        'title' => 'Πολλαπλές κρατήσεις',
        'body' => '{name} έκλεισαν περισσότερα ραντεβού στο {court}',
    ],
    'App\Notifications\NewMessage' => [
        'title' => 'Νέο μήνυμα από {name}',
    ],
    'App\Notifications\NewMember' => [
        'title' => 'Νέο μέλος στο κλαμπ',
        'body' => '{name} εγγράφηκε στο κλαμπ σας',
    ],
    'App\Notifications\NewClassifiedComment' => [
        'title' => 'Νέο σχόλιο στη διαφήμιση {name}',
    ],
    'App\Notifications\NewApplicant' => [
        'title' => 'Νέα αίτηση μέλους',
        'body' => '{name} έστειλε αίτημα μέλους',
    ],
    'App\Notifications\LeagueStart' => [
        'title' => 'Το πρωτάθλημα ξεκινά',
        'body' => '{name} πρωτάθλημα ξεκίνησε',
    ],
    'App\Notifications\InvitedToReservation' => [
        'title' => 'Καλέστε για κράτηση',
        'body' => '{name} σας προσκάλεσε να κάνετε κράτηση {time} προς {court}',
    ],
    'App\Notifications\ApplicationApproved' => [
        'title' => 'Εγκρίθηκε η αίτηση μέλους',
        'body' => 'Η αίτησή σας για συμμετοχή στο Club {name} έχει εγκριθεί',
    ],
    'App\Notifications\AddedToReservation' => [
        'title' => 'Έχετε προστεθεί στην κράτηση',
        'body' => '{name} σας πρόσθεσε στην κράτηση {time} προς {court}',
    ],
    'App\Notifications\AddedToMultipleReservations' => [
        'title' => 'Έχετε προστεθεί σε μια πολλαπλή κράτηση',
        'body' => '{name} σάς πρόσθεσε σε μια πολλαπλή κράτηση στο {court}',
    ],
    'App\Notifications\Shop\NewOrderCreated' => [
        'title' => 'Νέα παραγγελία',
        'body' => '{name} έστειλε μια νέα παραγγελία',
    ],
    'App\Notifications\Shop\NewWorkOrderCreated' => [
        'title' => 'Νέα παραγγελία εργασίας',
        'body' => '{name} έστειλε μια νέα παραγγελία',
    ],
    'App\Notifications\Shop\NewOrderCompleted' => [
        'title' => 'Παραγγελία ολοκληρώθηκε',
        'body' => '{name} ολοκλήρωσε την παραγγελία',
    ],
    'App\Notifications\Shop\NewWorkOrderCompleted' => [
        'title' => 'Η παραγγελία εργασίας έχει ολοκληρωθεί',
        'body' => '{name} ολοκλήρωσε την παραγγελία εργασίας',
    ],
    'Sve obavijesti' => 'Όλες οι ειδοποιήσεις',
    'Dodavanje na rezervaciju' => 'Προσθήκη στην κράτηση',
    'Pozivanje na rezervaciju' => 'Κλήση για κράτηση',
    'Novi član' => 'Νέο μέλος',
    'Nova rezervacija' => 'Νέα κράτηση',
    'Otkazivanje rezervacije' => 'Ακύρωση κράτησης',
    'Nova poruka' => 'Νέο μήνυμα',
    'Novi komentar na rezultatu' => 'Νέο σχόλιο για το αποτέλεσμα',
    'Odbijen rezultat' => 'Απορρίφθηκε το αποτέλεσμα',
    'Potvrđen rezultat' => 'Επιβεβαιωμένο αποτέλεσμα',
    'Potvrda rezultata' => 'Επιβεβαίωση αποτελεσμάτων',
    'Novi komentar na oglasu' => 'Νέο σχόλιο στη διαφήμιση',
    'Prihvaćena prijava' => 'Η αίτηση έγινε δεκτή',
    'Početak lige' => 'Η έναρξη του πρωταθλήματος',
    'Nova prijava za članstvo' => 'Νέα αίτηση μέλους',
    'Novi igrač u ligi' => 'Νέος παίκτης στο πρωτάθλημα',
    'Novi igrač u turniru' => 'Νέος παίκτης στο τουρνουά',
    'Rezervacija otkazana' => 'Η κράτηση ακυρώθηκε',
    'Završen španung' => 'Η επέκταση ολοκληρώθηκε',
    'Početak turnira' => 'Η έναρξη του τουρνουά',
    'Ažuriranje vremena' => 'Ενημέρωση ώρας',
    'Obavijest o gledanju' => 'Προβολή ειδοποίησης',
    'Nova narudžba' => 'Νέα παραγγελία',
    'Nova radna narudžba' => 'Νέα παραγγελία εργασίας',
];
