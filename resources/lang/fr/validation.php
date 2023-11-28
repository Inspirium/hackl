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

    'accepted' => '{attribute} doit être accepté.',
    'active_url' => '{attribute} n\'est pas une adresse valide',
    'after' => '{attribute} doit être après {date} ',
    'after_or_equal' => '{attribute} doit être supérieur ou égal à {date} ',
    'alpha' => '{attribute} ne peut contenir que des lettres',
    'alpha_dash' => '{attribute} ne peut contenir que des lettres, des chiffres et des tirets',
    'alpha_num' => '{attribute} ne peut contenir que des lettres et des chiffres',
    'array' => '{attribute} doit être une chaîne',
    'before' => '{attribute} doit venir avant {date} ',
    'before_or_equal' => '{attribute} doit être avant ou égal à {date} ',
    'between' => [
        'numeric' => '{attribute} doit être compris entre {min} et {max} ',
        'file' => '{attribute} doit être compris entre {min} et {max} kilo-octets',
        'string' => '{attribute} doit être compris entre {min} et {max} caractères',
        'array' => '{attribute} doit avoir entre {min} et {max} éléments',
    ],
    'boolean' => '{attribute} champ peut être vrai ou faux',
    'confirmed' => '{attribute} ne correspond pas à ce qui a été saisi',
    'date' => '{attribute} n\'est pas une date valide',
    'date_format' => '{attribute} ne correspond pas au format {format} ',
    'different' => '{attribute} et {other} doivent être différents',
    'digits' => '{attribute} doit être {digits} chiffre',
    'digits_between' => '{attribute} doit être compris entre {min} et {max} chiffres',
    'dimensions' => '{attribute} - dimension d\'image incorrecte',
    'distinct' => '{attribute} a une valeur en double',
    'email' => '{attribute} doit être une adresse e-mail valide',
    'exists' => 'Le {attribute} sélectionné n\'est pas correct',
    'file' => '{attribute} doit être un document',
    'filled' => '{attribute} doit avoir une certaine valeur',
    'image' => '{attribute} doit être une image',
    'in' => 'Le {attribute} sélectionné n\'est pas correct',
    'in_array' => '{attribute} le champ n\'existe pas dans {other} ',
    'integer' => '{attribute} doit être un entier',
    'ip' => '{attribute} doit être une adresse IP valide',
    'ipv4' => '{attribute} doit être une adresse IPv4 valide',
    'ipv6' => '{attribute} doit être une adresse IPv6 valide',
    'json' => '{attribute} doit être une expression JSON valide',
    'max' => [
        'numeric' => '{attribute} ne doit pas être supérieur à {max} ',
        'file' => '{attribute} ne doit pas être supérieur à {max} kilo-octet',
        'string' => '{attribute} ne doit pas dépasser {max} caractère',
        'array' => '{attribute} ne peut pas avoir plus de {max} éléments',
    ],
    'mimes' => '{attribute} doit être {values} format de fichier',
    'mimetypes' => '{attribute} doit être {values} format de fichier',
    'min' => [
        'numeric' => '{attribute} doit avoir au moins {min} ',
        'file' => '{attribute} doit être au moins égal {in} kilo-octet',
        'string' => '{attribute} doit avoir au moins {min} caractère',
        'array' => '{attribute} doit contenir au moins {min} élément',
    ],
    'not_in' => 'Le {attribute} sélectionné est incorrect',
    'numeric' => '{attribute} doit être un chiffre',
    'present' => '{attribute} champ doit être affiché',
    'regex' => '{attribute} est défectueux',
    'required' => '{attribute} champ est obligatoire',
    'required_if' => 'Le champ {attribute} est obligatoire si {other} et {value} ',
    'required_unless' => 'Le champ {attribute} est obligatoire sauf si {other} vaut {values} ',
    'required_with' => 'Le champ {attribute} est obligatoire lorsque {value} est sélectionné',
    'required_with_all' => 'Le champ {attribute} est obligatoire lorsque {values} est sélectionné',
    'required_without' => 'Le champ {attribute} est obligatoire lorsque {value} n\'est pas sélectionné',
    'required_without_all' => 'Le champ {attribute} est obligatoire lorsqu\'aucun des {values} n\'est sélectionné',
    'same' => '{attribute} et {other} doivent correspondre',
    'size' => [
        'numeric' => '{attribute} doit être {size} ',
        'file' => '{attribute} doit être égal à {size}  Ko',
        'string' => '{attribute} doit avoir {size} caractère',
        'array' => '{attribute} doit contenir {size} éléments',
    ],
    'string' => '{attribute} doit être un mot',
    'timezone' => '{attribute} doit être un fuseau horaire valide',
    'unique' => '{attribute} est occupé',
    'uploaded' => '{attribute} non transféré',
    'url' => '{attribute} le format n\'est pas correct',
    'image64' => '{attribute} doit être au format : {values}.',

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
        'email' => 'Adresse e-mail',
        'username' => 'nom d\'utilisateur',
        'city' => 'Ville',
        'address' => 'Adresse',
        'password' => 'Mot de passe',
        'password_confirmation' => 'Confirmation mot de passe',
        'birthyear' => 'année de naissance',
        'phone' => 'Téléphone portable',
        'first_name' => 'Nom',
        'last_name' => 'Nom de famille',
    ],

];
