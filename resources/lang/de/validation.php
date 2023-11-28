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

    'accepted' => '{attribute} muss akzeptiert werden.',
    'active_url' => '{attribute} ist keine gültige Adresse',
    'after' => '{attribute} muss nach {date} sein ',
    'after_or_equal' => '{attribute} muss nach oder gleich {date} sein ',
    'alpha' => '{attribute} darf nur Buchstaben enthalten',
    'alpha_dash' => '{attribute} darf nur Buchstaben, Ziffern und Bindestriche enthalten',
    'alpha_num' => '{attribute} darf nur Buchstaben und Ziffern enthalten',
    'array' => '{attribute} muss eine Zeichenfolge sein',
    'before' => '{attribute} muss vor {date} stehen ',
    'before_or_equal' => '{attribute} muss vor oder gleich {date} sein ',
    'between' => [
        'numeric' => '{attribute} muss zwischen {min} und {max} liegen ',
        'file' => '{attribute} muss zwischen {min} und {max} Kilobyte groß sein',
        'string' => '{attribute} muss zwischen {min} und {max} Zeichen lang sein',
        'array' => '{attribute} muss zwischen {min} und {max} Elemente haben',
    ],
    'boolean' => '{attribute} Feld kann wahr oder falsch sein',
    'confirmed' => '{attribute} stimmt nicht mit der Eingabe überein',
    'date' => '{attribute} ist kein gültiges Datum',
    'date_format' => '{attribute} stimmt nicht mit Format {format} überein ',
    'different' => '{attribute} und {other} müssen unterschiedlich sein',
    'digits' => '{attribute} muss {digits} stellig sein',
    'digits_between' => '{attribute} muss zwischen {min} und {max} Ziffern sein',
    'dimensions' => '{attribute} - falsche Bilddimension',
    'distinct' => '{attribute} hat einen doppelten Wert',
    'email' => '{attribute} muss eine gültige E-Mail-Adresse sein',
    'exists' => 'Die gewählte {attribute} ist nicht korrekt',
    'file' => '{attribute} muss ein Dokument sein',
    'filled' => '{attribute} muss irgendeinen Wert haben',
    'image' => '{attribute} muss ein Bild sein',
    'in' => 'Die gewählte {attribute} ist nicht korrekt',
    'in_array' => '{attribute} Feld existiert nicht in {other} ',
    'integer' => '{attribute} muss eine Ganzzahl sein',
    'ip' => '{attribute} muss eine gültige IP-Adresse sein',
    'ipv4' => '{attribute} muss eine gültige IPv4-Adresse sein',
    'ipv6' => '{attribute} muss eine gültige IPv6-Adresse sein',
    'json' => '{attribute} muss ein gültiger JSON-Ausdruck sein',
    'max' => [
        'numeric' => '{attribute} darf nicht größer als {max} sein ',
        'file' => '{attribute} darf nicht größer als {max} Kilobyte sein',
        'string' => '{attribute} darf {max} Zeichen nicht überschreiten',
        'array' => '{attribute} kann nicht mehr als {max} Elemente haben',
    ],
    'mimes' => '{attribute} muss {values} Dateiformat sein',
    'mimetypes' => '{attribute} muss {values} Dateiformat sein',
    'min' => [
        'numeric' => '{attribute} muss mindestens {min} haben ',
        'file' => '{attribute} muss mindestens {in} Kilobyte groß sein',
        'string' => '{attribute} muss mindestens {min} Zeichen haben',
        'array' => '{attribute} muss mindestens {min} Element enthalten',
    ],
    'not_in' => 'Die gewählte {attribute} ist falsch',
    'numeric' => '{attribute} muss eine Ziffer sein',
    'present' => '{attribute} Feld muss angezeigt werden',
    'regex' => '{attribute} ist defekt',
    'required' => '{attribute} Feld ist ein Pflichtfeld',
    'required_if' => '{attribute} Feld ist obligatorisch, wenn {other} und {value} ',
    'required_unless' => 'Das Feld {attribute} ist obligatorisch, es sei denn, {other} ist {values} ',
    'required_with' => 'Das Feld {attribute} ist obligatorisch, wenn {value} ausgewählt ist',
    'required_with_all' => 'Das Feld {attribute} ist obligatorisch, wenn {values} ausgewählt ist',
    'required_without' => 'Das Feld {attribute} ist obligatorisch, wenn {value} nicht ausgewählt ist',
    'required_without_all' => 'Das Feld {attribute} ist obligatorisch, wenn keines von {values} ausgewählt ist',
    'same' => '{attribute} und {other} müssen übereinstimmen',
    'size' => [
        'numeric' => '{attribute} muss {size} sein ',
        'file' => '{attribute} muss {size} Kilobyte sein',
        'string' => '{attribute} muss {size} Zeichen haben',
        'array' => '{attribute} muss {size} Elemente enthalten',
    ],
    'string' => '{attribute} muss ein Wort sein',
    'timezone' => '{attribute} muss eine gültige Zeitzone sein',
    'unique' => '{attribute} ist besetzt',
    'uploaded' => '{attribute} nicht übertragen',
    'url' => '{attribute} -Format ist nicht korrekt',
    'image64' => '{attribute} muss folgendes Format haben: {values}.',

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
        'email' => 'E-Mail-Addresse',
        'username' => 'Nutzername',
        'city' => 'Stadt',
        'address' => 'Adresse',
        'password' => 'Passwort',
        'password_confirmation' => 'Passwort Bestätigung',
        'birthyear' => 'Geburtsjahr',
        'phone' => 'Handy',
        'first_name' => 'Name',
        'last_name' => 'Familienname, Nachname',
    ],

];
