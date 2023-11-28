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

    'accepted' => '{attribute} je treba sprejeti.',
    'active_url' => '{attribute} ni veljaven naslov',
    'after' => '{attribute} mora biti za {date} ',
    'after_or_equal' => '{attribute} mora biti za ali enako {date} ',
    'alpha' => '{attribute} lahko vsebuje samo črke',
    'alpha_dash' => '{attribute} lahko vsebuje samo črke, številke in pomišljaje',
    'alpha_num' => '{attribute} lahko vsebuje samo črke in številke',
    'array' => '{attribute} mora biti niz',
    'before' => '{attribute} mora biti pred {date} ',
    'before_or_equal' => '{attribute} mora biti pred ali enako {date} ',
    'between' => [
        'numeric' => '{attribute} mora biti med {min} in {max} ',
        'file' => '{attribute} mora biti med {min} in {max} kilobajtoma',
        'string' => '{attribute} mora biti med {min} in {max} znakoma',
        'array' => '{attribute} mora imeti med {min} in {max} elementa',
    ],
    'boolean' => 'Polje {attribute} je lahko resnično ali napačno',
    'confirmed' => '{attribute} se ne ujema z vnesenim',
    'date' => '{attribute} ni veljaven datum',
    'date_format' => '{attribute} se ne ujema z obliko {format} ',
    'different' => '{attribute} in {other} morata biti različna',
    'digits' => '{attribute} mora biti {digits} številka',
    'digits_between' => '{attribute} mora biti med {min} in {max} števkama',
    'dimensions' => '{attribute} - nepravilna dimenzija slike',
    'distinct' => '{attribute} ima podvojeno vrednost',
    'email' => '{attribute} mora biti veljaven e-poštni naslov',
    'exists' => 'Izbrana {attribute} ni pravilna',
    'file' => '{attribute} mora biti dokument',
    'filled' => '{attribute} mora imeti neko vrednost',
    'image' => '{attribute} mora biti slika',
    'in' => 'Izbrana {attribute} ni pravilna',
    'in_array' => 'Polje {attribute} ne obstaja v {other} ',
    'integer' => '{attribute} mora biti celo število',
    'ip' => '{attribute} mora biti veljaven naslov IP',
    'ipv4' => '{attribute} mora biti veljaven naslov IPv4',
    'ipv6' => '{attribute} mora biti veljaven naslov IPv6',
    'json' => '{attribute} mora biti veljaven izraz JSON',
    'max' => [
        'numeric' => '{attribute} ne sme biti večji od {max} ',
        'file' => '{attribute} ne sme biti večji od {max} kilobajta',
        'string' => '{attribute} ne sme presegati {max} znaka',
        'array' => '{attribute} ne more imeti več kot {max} element',
    ],
    'mimes' => '{attribute} mora biti {values} format datoteke',
    'mimetypes' => '{attribute} mora biti {values} format datoteke',
    'min' => [
        'numeric' => '{attribute} mora imeti vsaj {min} ',
        'file' => '{attribute} mora biti vsaj {in} kilobajt',
        'string' => '{attribute} mora imeti vsaj {min} znak',
        'array' => '{attribute} mora vsebovati vsaj {min} element',
    ],
    'not_in' => 'Izbrana {attribute} ni pravilna',
    'numeric' => '{attribute} mora biti številka',
    'present' => 'Polje {attribute} mora biti prikazano',
    'regex' => '{attribute} je okvarjen',
    'required' => 'Polje {attribute} je obvezno',
    'required_if' => 'Polje {attribute} je obvezno, če sta {other} in {value} ',
    'required_unless' => 'Polje {attribute} je obvezno, razen če je {other} {values} ',
    'required_with' => 'Polje {attribute} je obvezno, ko je izbrano {value}',
    'required_with_all' => 'Polje {attribute} je obvezno, ko je izbrano {values}',
    'required_without' => 'Polje {attribute} je obvezno, če {value} ni izbrano',
    'required_without_all' => 'Polje {attribute} je obvezno, če ni izbrano nobeno od {values}',
    'same' => '{attribute} in {other} se morata ujemati',
    'size' => [
        'numeric' => '{attribute} mora biti {size} ',
        'file' => '{attribute} mora biti {size} kilobajt',
        'string' => '{attribute} mora imeti {size} znak',
        'array' => '{attribute} mora vsebovati {size} element',
    ],
    'string' => '{attribute} mora biti beseda',
    'timezone' => '{attribute} mora biti veljaven časovni pas',
    'unique' => '{attribute} je zaseden',
    'uploaded' => '{attribute} ni preneseno',
    'url' => '{attribute} oblika ni pravilna',
    'image64' => '{attribute} mora biti v obliki: {values}.',

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
        'email' => 'Email naslov',
        'username' => 'uporabniško ime',
        'city' => 'Mesto',
        'address' => 'Naslov',
        'password' => 'Geslo',
        'password_confirmation' => 'potrditev gesla',
        'birthyear' => 'leto rojstva',
        'phone' => 'Mobilni telefon',
        'first_name' => 'Ime',
        'last_name' => 'Priimek',
    ],

];
