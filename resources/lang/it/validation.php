<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => '{attribute} deve essere accettato.',
    'active_url' => '{attribute} non è un indirizzo valido',
    'after' => '{attribute} deve essere dopo {date} ',
    'after_or_equal' => '{attribute} deve essere successivo o uguale a {date} ',
    'alpha' => '{attribute} può contenere solo lettere',
    'alpha_dash' => '{attribute} può contenere solo lettere, numeri e trattini',
    'alpha_num' => '{attribute} può contenere solo lettere e numeri',
    'array' => '{attribute} deve essere una stringa',
    'before' => '{attribute} deve precedere {date} ',
    'before_or_equal' => '{attribute} deve essere precedente o uguale a {date} ',
    'between' => [
        'numeric' => '{attribute} deve essere compreso tra {min} e {max} ',
        'file' => '{attribute} deve essere compreso tra {min} e {max} kilobyte',
        'string' => '{attribute} deve essere compreso tra {min} e {max} caratteri',
        'array' => '{attribute} deve avere tra {min} e {max} elementi',
    ],
    'boolean' => 'Il campo {attribute} può essere vero o falso',
    'confirmed' => '{attribute} non corrisponde a quanto inserito',
    'date' => '{attribute} non è una data valida',
    'date_format' => '{attribute} non corrisponde al formato {format} ',
    'different' => '{attribute} e {other} devono essere diversi',
    'digits' => '{attribute} deve essere {digits} cifra',
    'digits_between' => '{attribute} deve essere compreso tra {min} e {max} cifre',
    'dimensions' => '{attribute} - dimensione dell\'immagine errata',
    'distinct' => '{attribute} ha un valore duplicato',
    'email' => '{attribute} deve essere un indirizzo email valido',
    'exists' => 'Lo {attribute} selezionato non è corretto',
    'file' => '{attribute} deve essere un documento',
    'filled' => '{attribute} deve avere un valore',
    'image' => '{attribute} deve essere un\'immagine',
    'in' => 'Lo {attribute} selezionato non è corretto',
    'in_array' => 'Il campo {attribute} non esiste in {other} ',
    'integer' => '{attribute} deve essere un numero intero',
    'ip' => '{attribute} deve essere un indirizzo IP valido',
    'ipv4' => '{attribute} deve essere un indirizzo IPv4 valido',
    'ipv6' => '{attribute} deve essere un indirizzo IPv6 valido',
    'json' => '{attribute} deve essere un\'espressione JSON valida',
    'max' => [
        'numeric' => '{attribute} non deve essere maggiore di {max} ',
        'file' => '{attribute} non deve essere maggiore di {max} kilobyte',
        'string' => '{attribute} non deve superare {max} carattere',
        'array' => '{attribute} non può avere più di {max} elementi',
    ],
    'mimes' => '{attribute} deve essere {values} formato file',
    'mimetypes' => '{attribute} deve essere {values} formato file',
    'min' => [
        'numeric' => '{attribute} deve avere almeno {min} ',
        'file' => '{attribute} deve essere di almeno {in} kilobyte',
        'string' => '{attribute} deve avere almeno {min} carattere',
        'array' => '{attribute} deve contenere almeno {min} elemento',
    ],
    'not_in' => 'Lo {attribute} selezionato non è corretto',
    'numeric' => '{attribute} deve essere una cifra',
    'present' => 'Deve essere visualizzato il campo {attribute}',
    'regex' => '{attribute} è difettoso',
    'required' => 'Il campo {attribute} è obbligatorio',
    'required_if' => 'Il campo {attribute} è obbligatorio se {other} e {value} ',
    'required_unless' => 'Il campo {attribute} è obbligatorio a meno che {other} non sia {values} ',
    'required_with' => 'Il campo {attribute} è obbligatorio quando è selezionato {value}',
    'required_with_all' => 'Il campo {attribute} è obbligatorio quando {values} è selezionato',
    'required_without' => 'Il campo {attribute} è obbligatorio quando {value} non è selezionato',
    'required_without_all' => 'Il campo {attribute} è obbligatorio quando nessuno di {values} è selezionato',
    'same' => '{attribute} e {other} devono corrispondere',
    'size' => [
        'numeric' => '{attribute} deve essere {size} ',
        'file' => '{attribute} deve essere {size} kilobyte',
        'string' => '{attribute} deve contenere {size} carattere',
        'array' => '{attribute} deve contenere {size} elementi',
    ],
    'string' => '{attribute} deve essere una parola',
    'timezone' => '{attribute} deve essere un fuso orario valido',
    'unique' => '{attribute} è occupato',
    'uploaded' => '{attribute} non trasferito',
    'url' => 'Il formato {attribute} non è corretto',
    'image64' => '{attribute} deve essere nel formato: {values}.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'email' => 'Indirizzo e-mail',
        'username' => 'nome utente',
        'city' => 'Città',
        'address' => 'Indirizzo',
        'password' => 'Parola d\'ordine',
        'password_confirmation' => 'conferma password',
        'birthyear' => 'anno di nascita',
        'phone' => 'Cellulare',
        'first_name' => 'Nome',
        'last_name' => 'Cognome',
    ],

];
