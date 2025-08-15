<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines (Portuguese)
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'O campo :attribute deve ser aceito.',
    'active_url'           => 'O campo :attribute não é uma URL válida.',
    'after'                => 'O campo :attribute deve ser uma data posterior a :date.',

    // Confirmation and length (the ones you asked to translate)
    'confirmed'            => 'A confirmação do campo :attribute não corresponde.',

    'min' => [
        'string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
    ],

    'password' => 'O campo :attribute deve atender aos requisitos de segurança.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'password' => 'senha',
        'password_confirmation' => 'confirmação de senha',
    ],
];
