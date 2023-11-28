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

    'accepted' => '{attribute} мора бити прихваћено.',
    'active_url' => '{attribute} није важећа адреса',
    'after' => '{attribute} мора бити после {date} ',
    'after_or_equal' => '{attribute} мора бити после или једнако {date} ',
    'alpha' => '{attribute} може да садржи само слова',
    'alpha_dash' => '{attribute} може да садржи само слова, бројеве и цртице',
    'alpha_num' => '{attribute} може да садржи само слова и бројеве',
    'array' => '{attribute} мора бити стринг',
    'before' => '{attribute} мора бити пре {date} ',
    'before_or_equal' => '{attribute} мора бити испред или једнако {date} ',
    'between' => [
        'numeric' => '{attribute} мора бити између {min} и {max} ',
        'file' => '{attribute} мора бити између {min} и {max} килобајта',
        'string' => '{attribute} мора бити између {min} и {max} знака',
        'array' => '{attribute} мора имати између {min} и {max} елемента',
    ],
    'boolean' => 'Поље {attribute} може бити тачно или нетачно',
    'confirmed' => '{attribute} не одговара ономе што је унето',
    'date' => '{attribute} није исправан датум',
    'date_format' => '{attribute} не одговара формату {format} ',
    'different' => '{attribute} и {other} морају бити различити',
    'digits' => '{attribute} мора бити {digits} цифра',
    'digits_between' => '{attribute} мора бити између {min} и {max} цифре',
    'dimensions' => '{attribute} - нетачна димензија слике',
    'distinct' => '{attribute} има дупликат вредности',
    'email' => '{attribute} мора бити важећа адреса е-поште',
    'exists' => 'Изабрана {attribute} није тачна',
    'file' => '{attribute} мора бити документ',
    'filled' => '{attribute} мора имати неку вредност',
    'image' => '{attribute} мора бити слика',
    'in' => 'Изабрана {attribute} није тачна',
    'in_array' => 'Поље {attribute} не постоји у {other} ',
    'integer' => '{attribute} мора бити цео број',
    'ip' => '{attribute} мора бити важећа ИП адреса',
    'ipv4' => '{attribute} мора бити важећа ИПв4 адреса',
    'ipv6' => '{attribute} мора бити важећа ИПв6 адреса',
    'json' => '{attribute} мора бити важећи ЈСОН израз',
    'max' => [
        'numeric' => '{attribute} не сме бити веће од {max} ',
        'file' => '{attribute} не сме бити већи од {max} килобајта',
        'string' => '{attribute} не сме да пређе {max} знак',
        'array' => '{attribute} не може имати више од {max} елемента',
    ],
    'mimes' => '{attribute} мора бити {values} формат датотеке',
    'mimetypes' => '{attribute} мора бити {values} формат датотеке',
    'min' => [
        'numeric' => '{attribute} мора имати најмање {min} ',
        'file' => '{attribute} мора бити најмање {in} килобајт',
        'string' => '{attribute} мора имати најмање {min} знак',
        'array' => '{attribute} мора да садржи најмање {min} елемент',
    ],
    'not_in' => 'Изабрана {attribute} је нетачна',
    'numeric' => '{attribute} мора бити цифра',
    'present' => '{attribute} поље мора бити приказано',
    'regex' => '{attribute} је неисправан',
    'required' => '{attribute} поље је обавезно',
    'required_if' => 'Поље {attribute} је обавезно ако су {other} и {value} ',
    'required_unless' => 'Поље {attribute} је обавезно осим ако {other} није {values} ',
    'required_with' => 'Поље {attribute} је обавезно када је изабрано {value}',
    'required_with_all' => 'Поље {attribute} је обавезно када је изабрано {values}',
    'required_without' => 'Поље {attribute} је обавезно када {value} није изабрано',
    'required_without_all' => 'Поље {attribute} је обавезно када није изабрано ниједно од {values}',
    'same' => '{attribute} и {other} морају се подударати',
    'size' => [
        'numeric' => '{attribute} мора бити {size} ',
        'file' => '{attribute} мора бити {size} килобајт',
        'string' => '{attribute} мора имати {size} знак',
        'array' => '{attribute} мора да садржи {size} елемент',
    ],
    'string' => '{attribute} мора бити реч',
    'timezone' => '{attribute} мора бити важећа временска зона',
    'unique' => '{attribute} је заузет',
    'uploaded' => '{attribute} није пренето',
    'url' => '{attribute} формат није тачан',
    'image64' => '{attribute} мора бити у формату: {values}.',

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
        'email' => 'Адреса Е-поште',
        'username' => 'корисничко име',
        'city' => 'Цити',
        'address' => 'Адреса',
        'password' => 'Лозинка',
        'password_confirmation' => 'Потврда лозинке',
        'birthyear' => 'година рођења',
        'phone' => 'Мобилни телефон',
        'first_name' => 'Име',
        'last_name' => 'Презиме',
    ],

];
