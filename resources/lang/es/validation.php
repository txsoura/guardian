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

    'accepted' => 'El :attribute debe ser aceptado.',
    'active_url' => 'El :attribute no es una URL válida.',
    'after' => 'El :attribute debe ser una fecha posterior :date.',
    'after_or_equal' => 'El :attribute debe ser una fecha posterior o igual a :date.',
    'alpha' => 'El :attribute solo puede contener letras.',
    'alpha_dash' => 'El :attribute solo puede contener letras, números, guiones y guiones bajos.',
    'alpha_num' => 'El :attribute solo puede contener letras y números.',
    'array' => 'El :attribute debe ser una matriz.',
    'before' => 'El :attribute debe ser una fecha antes :date.',
    'before_or_equal' => 'El :attribute debe ser una fecha anterior o igual a :date.',
    'between' => [
        'numeric' => 'El :attribute debe estar entre :min y :max.',
        'file' => 'El :attribute debe estar entre :min y :max kilobytes.',
        'string' => 'El :attribute debe estar entre :min y :max caracteres.',
        'array' => 'El :attribute debe tener más de between :min y :max artículos.',
    ],
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed' => 'El :attribute la confirmación no coincide.',
    'date' => 'El :attribute no es una fecha válida.',
    'date_equals' => 'El :attribute debe ser una fecha igual a :date.',
    'date_format' => 'El :attribute no coincide con el formato :format.',
    'different' => 'El :attribute y :other debe ser diferente.',
    'digits' => 'El :attribute debe ser :digits dígitos.',
    'digits_between' => 'El :attribute debe estar entre :min y :max dígitos.',
    'dimensions' => 'El :attribute tiene dimensiones de imagen no válidas.',
    'destinct' => 'El campo :attribute tiene un valor duplicado.',
    'email' => 'El :attribute debe ser una dirección de correo electrónico válida.',
    'ends_with' => 'El :attribute debe terminar con uno de los siguientes: :values',
    'exests' => 'El seleccionado :attribute Es invalido.',
    'file' => 'El :attribute debe ser un archivo.',
    'filled' => 'El campo :attribute debe tener un valor.',
    'gt' => [
        'numeric' => 'El :attribute debe ser mayor que :value.',
        'file' => 'El :attribute debe ser mayor que :value kilobytes.',
        'string' => 'El :attribute debe ser mayor que :value caracteres.',
        'array' => 'El :attribute debe tener más de mas que :value artículos.',
    ],
    'gte' => [
        'numeric' => 'El :attribute debe ser mayor que o igual :value.',
        'file' => 'El :attribute debe ser mayor que o igual :value kilobytes.',
        'string' => 'El :attribute debe ser mayor que o igual :value caracteres.',
        'array' => 'El :attribute debe tener más de :value artículos o más.',
    ],
    'image' => 'El :attribute debe ser an imagen.',
    'in' => 'El seleccionado :attribute es invalido.',
    'in_array' => 'El campo :attribute no exeste en :other.',
    'integer' => 'El :attribute debe ser un entero.',
    'ip' => 'El :attribute debe ser una dirección IP válida.',
    'ipv4' => 'El :attribute debe ser una dirección IPv4 válida.',
    'ipv6' => 'El :attribute debe ser una dirección IPv6 válida.',
    'json' => 'El :attribute debe ser una cadena JSON válida.',
    'lt' => [
        'numeric' => 'El :attribute debe ser menos que :value.',
        'file' => 'El :attribute debe ser menos que :value kilobytes.',
        'string' => 'El :attribute debe ser menos que :value caracteres.',
        'array' => 'El :attribute debe tener más de menos que :value value.',
    ],
    'lte' => [
        'numeric' => 'El :attribute debe ser menos que o igual :value.',
        'file' => 'El :attribute debe ser menos que o igual :value kilobytes.',
        'string' => 'El :attribute debe ser menos que o igual :value caracteres.',
        'array' => 'El :attribute no debe tener más de:value valor.',
    ],
    'max' => [
        'numeric' => 'El :attribute no puede ser mayor que :max.',
        'file' => 'El :attribute no puede ser mayor que :max kilobytes.',
        'string' => 'El :attribute no puede ser mayor que :max caracteres.',
        'array' => 'El :attribute puede no tener más de :max value.',
    ],
    'mimes' => 'El :attribute debe ser un archivo de tipo: :values.',
    'mimetypes' => 'El :attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'numeric' => 'El :attribute debe ser al menos :min.',
        'file' => 'El :attribute debe ser al menos :min kilobytes.',
        'string' => 'El :attribute debe ser al menos :min caracteres.',
        'array' => 'El :attribute debe tener más de al menos :min valor.',
    ],
    'not_in' => 'El seleccionado :attribute es invalido.',
    'not_regex' => 'El :attribute formato es invalido.',
    'numeric' => 'El :attribute debe ser a número.',
    'password' => 'La contraseña es incorrecta.',
    'present' => 'El campo :attribute debe ser presente.',
    'regex' => 'El :attribute formato es invalido.',
    'required' => 'El campo :attribute es requerido.',
    'required_if' => 'El campo :attribute es requerido cuando :other es :value.',
    'required_unless' => 'El campo :attribute es requerido  :other es en :values.',
    'required_with' => 'El campo :attribute es requerido cuando :values es presente.',
    'required_with_all' => 'El campo :attribute es requerido cuando :values son presente.',
    'required_without' => 'El campo :attribute es requerido cuando :values es no presente.',
    'required_without_all' => 'El campo :attribute es requerido cuando ninguno de :values son presente.',
    'same' => 'El :attribute y :other debe coincidir con.',
    'size' => [
        'numeric' => 'El :attribute debe ser :size.',
        'file' => 'El :attribute debe ser :size kilobytes.',
        'string' => 'El :attribute debe ser :size caracteres.',
        'array' => 'El :attribute debe contener :size valor.',
    ],
    'starts_with' => 'El :attribute debe comenzar con uno de los siguientes: :values',
    'string' => 'El :attribute debe ser una cuerda.',
    'timezone' => 'El :attribute debe ser una zona valida.',
    'unique' => 'El :attribute ya se ha tomado.',
    'uploaded' => 'El :attribute no se pudo cargar.',
    'url' => 'El :attribute formato es invalido.',
    'uuid' => 'El :attribute debe ser un válido UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
