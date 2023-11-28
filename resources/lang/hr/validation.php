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

    'accepted' => '{attribute} mora biti prihvaćen.',
    'active_url' => '{attribute} nije ispravna adresa',
    'after' => '{attribute} mora biti nakon {date} ',
    'after_or_equal' => '{attribute} mora biti nakon ili jednak {date} ',
    'alpha' => '{attribute} može sadržavati samo slova',
    'alpha_dash' => '{attribute} može sadržavati samo slova, brojeve i povlake',
    'alpha_num' => '{attribute} može sadržavati samo slova i brojeve',
    'array' => '{attribute} mora biti niz',
    'before' => '{attribute} mora biti prije {date} ',
    'before_or_equal' => '{attribute} mora biti prije ili jednak {date} ',
    'between' => [
        'numeric' => '{attribute} mora biti između {min} i {max} ',
        'file' => '{attribute} mora biti između {min} i {max} kilobajta',
        'string' => '{attribute} mora biti između {min} i {max} znakova',
        'array' => '{attribute} mora imati između {min} i {max} elemenata',
    ],
    'boolean' => '{attribute} polje može biti točno ili netočno',
    'confirmed' => '{attribute} ne podudara se s unesenim',
    'date' => '{attribute} nije ispravan datum',
    'date_format' => '{attribute} ne odgovara formatu {format} ',
    'different' => '{attribute} i {other} moraju biti različiti',
    'digits' => '{attribute} mora biti {digits} znamenki',
    'digits_between' => '{attribute} mora biti između {min} i {max} znamenki',
    'dimensions' => '{attribute} - neispravna dimenzija slike',
    'distinct' => '{attribute} ima dupliciranu vrijednost',
    'email' => '{attribute} mora biti ispravna e-mail adresa',
    'exists' => 'Odabrani {attribute} nije ispravan',
    'file' => '{attribute} mora biti dokument',
    'filled' => '{attribute} mora imati neku vrijednost',
    'image' => '{attribute} mora biti slika',
    'in' => 'Odabrani {attribute} nije ispravan',
    'in_array' => '{attribute} polje ne postoji u {other} ',
    'integer' => '{attribute} mora biti cijeli broj',
    'ip' => '{attribute} mora biti ispravna IP adresa',
    'ipv4' => '{attribute} mora biti ispravna IPv4 adresa',
    'ipv6' => '{attribute} mora biti ispravna IPv6 adresa',
    'json' => '{attribute} mora biti ispravni JSON izraz',
    'max' => [
        'numeric' => '{attribute} ne smije biti veći od {max} ',
        'file' => '{attribute} ne smije biti veći od {max} kilobajta',
        'string' => '{attribute} ne smije biti veći od {max} znakova',
        'array' => '{attribute} ne smije imati više od {max} elemenata',
    ],
    'mimes' => '{attribute} mora biti {values} format datoteke',
    'mimetypes' => '{attribute} mora biti {values} format datoteke',
    'min' => [
        'numeric' => '{attribute} mora imati najmanje {min} ',
        'file' => '{attribute} mora imati najmanje {in} kilobajta',
        'string' => '{attribute} mora imati najmanje {min} znakova',
        'array' => '{attribute} mora sadržavati najmanje {min} elemenata',
    ],
    'not_in' => 'Odabrani {attribute} je neispravan',
    'numeric' => '{attribute} mora biti znamenka',
    'present' => '{attribute} polje mora biti prikazano',
    'regex' => '{attribute} je neispravan',
    'required' => '{attribute} polje je obvezno',
    'required_if' => '{attribute} polje je obvezno ako {other} is {value} ',
    'required_unless' => '{attribute} polje je obvezno osim ako {other} je {values} ',
    'required_with' => '{attribute} polje je obvezno kada je {value} odabran',
    'required_with_all' => '{attribute} polje je obvezno kada su {values} odabrane',
    'required_without' => '{attribute} polje je obvezno kada {value} nije odabran',
    'required_without_all' => '{attribute} polje je obvezno kada niti jedno od {values} nije odabrano',
    'same' => '{attribute} an {other} moraju se podudarati',
    'size' => [
        'numeric' => '{attribute} mora biti {size} ',
        'file' => '{attribute} mora imati {size} kilobajta',
        'string' => '{attribute} mora imati {size} znakova',
        'array' => '{attribute} mora sadržavati {size} elemenata',
    ],
    'string' => '{attribute} mora biti riječ',
    'timezone' => '{attribute} mora biti ispravna vremenska zona',
    'unique' => '{attribute} je zauzeto',
    'uploaded' => '{attribute} nije preneseno',
    'url' => '{attribute} format nije ispravan',
    'image64' => '{attribute} mora biti formata: {values} .',

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
        'email' => 'Email adresa',
        'username' => 'Korisničko ime',
        'city' => 'Grad',
        'address' => 'Adresa',
        'password' => 'Lozinka',
        'password_confirmation' => 'Potvrda lozinke',
        'birthyear' => 'Godina rođenja',
        'phone' => 'Mobitel',
        'first_name' => 'Ime',
        'last_name' => 'Prezime',
    ],

];
