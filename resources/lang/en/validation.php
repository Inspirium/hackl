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

    'accepted' => '{attribute} must be accepted.',
    'active_url' => '{attribute} is not a valid address',
    'after' => '{attribute} must be after {date} ',
    'after_or_equal' => '{attribute} must be after or equal to {date} ',
    'alpha' => '{attribute} can only contain letters',
    'alpha_dash' => '{attribute} can only contain letters, numbers and dashes',
    'alpha_num' => '{attribute} can only contain letters and numbers',
    'array' => '{attribute} must be a string',
    'before' => '{attribute} must come before {date} ',
    'before_or_equal' => '{attribute} must be before or equal to {date} ',
    'between' => [
        'numeric' => '{attribute} must be between {min} and {max} ',
        'file' => '{attribute} must be between {min} and {max} kilobytes',
        'string' => '{attribute} must be between {min} and {max} characters',
        'array' => '{attribute} must have between {min} and {max} elements',
    ],
    'boolean' => '{attribute} field can be true or false',
    'confirmed' => '{attribute} does not match what was entered',
    'date' => '{attribute} is not a valid date',
    'date_format' => '{attribute} does not match format {format} ',
    'different' => '{attribute} and {other} must be different',
    'digits' => '{attribute} must be {digits} digit',
    'digits_between' => '{attribute} must be between {min} and {max} digits',
    'dimensions' => '{attribute} - incorrect image dimension',
    'distinct' => '{attribute} has a duplicate value',
    'email' => '{attribute} must be a valid email address',
    'exists' => 'The selected {attribute} is not correct',
    'file' => '{attribute} must be a document',
    'filled' => '{attribute} must have some value',
    'image' => '{attribute} must be an image',
    'in' => 'The selected {attribute} is not correct',
    'in_array' => '{attribute} field does not exist in {other} ',
    'integer' => '{attribute} must be an integer',
    'ip' => '{attribute} must be a valid IP address',
    'ipv4' => '{attribute} must be a valid IPv4 address',
    'ipv6' => '{attribute} must be a valid IPv6 address',
    'json' => '{attribute} must be a valid JSON expression',
    'max' => [
        'numeric' => '{attribute} must not be greater than {max} ',
        'file' => '{attribute} must not be larger than {max} kilobyte',
        'string' => '{attribute} must not exceed {max} character',
        'array' => '{attribute} cannot have more than {max} elements',
    ],
    'mimes' => '{attribute} must be {values} file format',
    'mimetypes' => '{attribute} must be {values} file format',
    'min' => [
        'numeric' => '{attribute} must have at least {min} ',
        'file' => '{attribute} must be at least {in} kilobyte',
        'string' => '{attribute} must have at least {min} character',
        'array' => '{attribute} must contain at least {min} element',
    ],
    'not_in' => 'The selected {attribute} is incorrect',
    'numeric' => '{attribute} must be a digit',
    'present' => '{attribute} field must be displayed',
    'regex' => '{attribute} is defective',
    'required' => '{attribute} field is mandatory',
    'required_if' => '{attribute} field is mandatory if {other} and {value} ',
    'required_unless' => '{attribute} field is mandatory unless {other} is {values} ',
    'required_with' => '{attribute} field is mandatory when {value} is selected',
    'required_with_all' => '{attribute} field is mandatory when {values} are selected',
    'required_without' => '{attribute} field is mandatory when {value} is not selected',
    'required_without_all' => '{attribute} field is mandatory when none of {values} is selected',
    'same' => '{attribute} and {other} must match',
    'size' => [
        'numeric' => '{attribute} must be {size} ',
        'file' => '{attribute} must be {size} kilobyte',
        'string' => '{attribute} must have {size} character',
        'array' => '{attribute} must contain {size} elements',
    ],
    'string' => '{attribute} must be a word',
    'timezone' => '{attribute} must be a valid time zone',
    'unique' => '{attribute} is busy',
    'uploaded' => '{attribute} not transferred',
    'url' => '{attribute} format is not correct',
    'image64' => '{attribute} must be of the format: {values}.',

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
        'email' => 'Email address',
        'username' => 'Username',
        'city' => 'City',
        'address' => 'Address',
        'password' => 'Password',
        'password_confirmation' => 'Password confirmation',
        'birthyear' => 'Birth Year',
        'phone' => 'Cell phone',
        'first_name' => 'Name',
        'last_name' => 'Surname',
    ],

];
