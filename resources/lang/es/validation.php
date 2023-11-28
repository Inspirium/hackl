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

    'accepted' => '{attribute} debe ser aceptado.',
    'active_url' => '{attribute} no es una dirección válida',
    'after' => '{attribute} debe ser después de {date} ',
    'after_or_equal' => '{attribute} debe ser posterior o igual a {date} ',
    'alpha' => '{attribute} solo puede contener letras',
    'alpha_dash' => '{attribute} solo puede contener letras, números y guiones',
    'alpha_num' => '{attribute} solo puede contener letras y números',
    'array' => '{attribute} debe ser una cadena',
    'before' => '{attribute} debe ir antes de {date} ',
    'before_or_equal' => '{attribute} debe ser anterior o igual a {date} ',
    'between' => [
        'numeric' => '{attribute} debe estar entre {min} y {max} ',
        'file' => '{attribute} debe tener entre {min} y {max} kilobytes',
        'string' => '{attribute} debe tener entre {min} y {max} caracteres',
        'array' => '{attribute} debe tener entre {min} y {max} elementos',
    ],
    'boolean' => '{attribute} campo puede ser verdadero o falso',
    'confirmed' => '{attribute} no coincide con lo ingresado',
    'date' => '{attribute} no es una fecha válida',
    'date_format' => '{attribute} no coincide con el formato {format} ',
    'different' => '{attribute} y {other} deben ser diferentes',
    'digits' => '{attribute} debe ser {digits} dígito',
    'digits_between' => '{attribute} debe tener entre {min} y {max} dígitos',
    'dimensions' => '{attribute} - dimensión de imagen incorrecta',
    'distinct' => '{attribute} tiene un valor duplicado',
    'email' => '{attribute} debe ser una dirección de correo electrónico válida',
    'exists' => 'El {attribute} seleccionado no es correcto',
    'file' => '{attribute} debe ser un documento',
    'filled' => '{attribute} debe tener algún valor',
    'image' => '{attribute} debe ser una imagen',
    'in' => 'El {attribute} seleccionado no es correcto',
    'in_array' => '{attribute} campo no existe en {other} ',
    'integer' => '{attribute} debe ser un número entero',
    'ip' => '{attribute} debe ser una dirección IP válida',
    'ipv4' => '{attribute} debe ser una dirección IPv4 válida',
    'ipv6' => '{attribute} debe ser una dirección IPv6 válida',
    'json' => '{attribute} debe ser una expresión JSON válida',
    'max' => [
        'numeric' => '{attribute} no debe ser mayor que {max} ',
        'file' => '{attribute} no debe ser mayor de {max} kilobyte',
        'string' => '{attribute} no debe exceder {max} carácter',
        'array' => '{attribute} no puede tener más de {max} elemento',
    ],
    'mimes' => '{attribute} debe ser {values} formato de archivo',
    'mimetypes' => '{attribute} debe ser {values} formato de archivo',
    'min' => [
        'numeric' => '{attribute} debe tener al menos {min} ',
        'file' => '{attribute} debe tener al menos {in} kilobyte',
        'string' => '{attribute} debe tener al menos {min} carácter',
        'array' => '{attribute} debe contener al menos {min} elemento',
    ],
    'not_in' => 'El {attribute} seleccionado es incorrecto',
    'numeric' => '{attribute} debe ser un dígito',
    'present' => 'Se debe mostrar el campo {attribute}',
    'regex' => '{attribute} es defectuoso',
    'required' => '{attribute} campo es obligatorio',
    'required_if' => 'El campo {attribute} es obligatorio si {other} y {value} ',
    'required_unless' => 'El campo {attribute} es obligatorio a menos que {other} sea {values} ',
    'required_with' => 'El campo {attribute} es obligatorio cuando se selecciona {value}',
    'required_with_all' => 'El campo {attribute} es obligatorio cuando se selecciona {values}',
    'required_without' => 'El campo {attribute} es obligatorio cuando no se selecciona {value}',
    'required_without_all' => 'El campo {attribute} es obligatorio cuando no se selecciona ninguno de {values}',
    'same' => '{attribute} y {other} deben coincidir',
    'size' => [
        'numeric' => '{attribute} debe ser {size} ',
        'file' => '{attribute} debe ser {size} kilobyte',
        'string' => '{attribute} debe tener {size} caracter',
        'array' => '{attribute} debe contener {size} elementos',
    ],
    'string' => '{attribute} debe ser una palabra',
    'timezone' => '{attribute} debe ser una zona horaria válida',
    'unique' => '{attribute} está ocupado',
    'uploaded' => '{attribute} no transferido',
    'url' => '{attribute} el formato no es correcto',
    'image64' => '{attribute} debe tener el formato: {values}.',

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
        'email' => 'Dirección de correo electrónico',
        'username' => 'nombre de usuario',
        'city' => 'Ciudad',
        'address' => 'DIRECCIÓN',
        'password' => 'Contraseña',
        'password_confirmation' => 'confirmación de contraseña',
        'birthyear' => 'año de nacimiento',
        'phone' => 'Teléfono móvil',
        'first_name' => 'Nombre',
        'last_name' => 'Apellido',
    ],

];
