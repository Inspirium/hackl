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

    'accepted' => '{attribute} мора да се прифати.',
    'active_url' => '{attribute} не е валидна адреса',
    'after' => '{attribute} мора да биде по {date} ',
    'after_or_equal' => '{attribute} мора да биде по или еднаква на {date} ',
    'alpha' => '{attribute} може да содржи само букви',
    'alpha_dash' => '{attribute} може да содржи само букви, бројки и цртички',
    'alpha_num' => '{attribute} може да содржи само букви и бројки',
    'array' => '{attribute} мора да биде низа',
    'before' => '{attribute} мора да дојде пред {date} ',
    'before_or_equal' => '{attribute} мора да биде пред или еднаква на {date} ',
    'between' => [
        'numeric' => '{attribute} мора да биде помеѓу {min} и {max} ',
        'file' => '{attribute} мора да биде помеѓу {min} и {max} килобајти',
        'string' => '{attribute} мора да биде помеѓу {min} и {max} знаци',
        'array' => '{attribute} мора да има помеѓу {min} и {max} елементи',
    ],
    'boolean' => 'Полето {attribute} може да биде точно или неточно',
    'confirmed' => '{attribute} не се совпаѓа со внесеното',
    'date' => '{attribute} не е валиден датум',
    'date_format' => '{attribute} не се совпаѓа со формат {format} ',
    'different' => '{attribute} и {other} мора да бидат различни',
    'digits' => '{attribute} мора да биде {digits} цифра',
    'digits_between' => '{attribute} мора да биде помеѓу {min} и {max} цифри',
    'dimensions' => '{attribute} - неточна димензија на сликата',
    'distinct' => '{attribute} има дупликат вредност',
    'email' => '{attribute} мора да биде валидна адреса за е-пошта',
    'exists' => 'Избраната {attribute} не е точна',
    'file' => '{attribute} мора да биде документ',
    'filled' => '{attribute} мора да има одредена вредност',
    'image' => '{attribute} мора да биде слика',
    'in' => 'Избраната {attribute} не е точна',
    'in_array' => 'Полето {attribute} не постои во {other} ',
    'integer' => '{attribute} мора да биде цел број',
    'ip' => '{attribute} мора да биде валидна IP адреса',
    'ipv4' => '{attribute} мора да биде валидна IPv4 адреса',
    'ipv6' => '{attribute} мора да биде валидна IPv6 адреса',
    'json' => '{attribute} мора да биде валиден JSON израз',
    'max' => [
        'numeric' => '{attribute} не смее да биде поголем од {max} ',
        'file' => '{attribute} не смее да биде поголем од {max} килобајт',
        'string' => '{attribute} не смее да надминува {max} знак',
        'array' => '{attribute} не може да има повеќе од {max} елемент',
    ],
    'mimes' => '{attribute} мора да биде {values} формат на датотека',
    'mimetypes' => '{attribute} мора да биде {values} формат на датотека',
    'min' => [
        'numeric' => '{attribute} мора да има најмалку {min} ',
        'file' => '{attribute} мора да биде најмалку {in} килобајт',
        'string' => '{attribute} мора да има најмалку {min} знак',
        'array' => '{attribute} мора да содржи најмалку {min} елемент',
    ],
    'not_in' => 'Избраната {attribute} е неточна',
    'numeric' => '{attribute} мора да биде цифра',
    'present' => 'Полето {attribute} мора да се прикаже',
    'regex' => '{attribute} е дефектен',
    'required' => 'Полето {attribute} е задолжително',
    'required_if' => 'Полето {attribute} е задолжително ако {other} и {value} ',
    'required_unless' => 'Полето {attribute} е задолжително освен ако {other} е {values} ',
    'required_with' => 'Полето {attribute} е задолжително кога е избрано {value}',
    'required_with_all' => 'Полето {attribute} е задолжително кога се избира {values}',
    'required_without' => 'Полето {attribute} е задолжително кога не е избрано {value}',
    'required_without_all' => 'Полето {attribute} е задолжително кога не е избрано ниту едно од {values}',
    'same' => '{attribute} и {other} мора да се совпаѓаат',
    'size' => [
        'numeric' => '{attribute} мора да биде {size} ',
        'file' => '{attribute} мора да биде {size} килобајт',
        'string' => '{attribute} мора да има {size} знак',
        'array' => '{attribute} мора да содржи {size} елемент',
    ],
    'string' => '{attribute} мора да биде збор',
    'timezone' => '{attribute} мора да биде важечка временска зона',
    'unique' => '{attribute} е зафатен',
    'uploaded' => '{attribute} не е пренесено',
    'url' => 'Форматот {attribute} не е точен',
    'image64' => '{attribute} мора да биде со формат: {values}.',

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
        'email' => 'И-мејл адреса',
        'username' => 'корисничко име',
        'city' => 'Градот',
        'address' => 'Адреса',
        'password' => 'Лозинка',
        'password_confirmation' => 'Потврда на лозинка',
        'birthyear' => 'година на раѓање',
        'phone' => 'Мобилен телефон',
        'first_name' => 'Име',
        'last_name' => 'Презиме',
    ],

];
