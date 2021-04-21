<?php

return [
    'two_factor_code' => [
        'subject' => 'Verificación de dos factores',
        'code' => 'Código: ',
        'paragraph' => 'Alguien está intentando usar su cuenta, si reconoce esta actividad, confírmela con el código de activación. Aquí está el código de activación de su cuenta:',
        'footer' => 'Este es un mensaje automático, no responda.',
    ],
    "verify_email" => [
        'subject' => 'Confirme su dirección de correo electrónico',
        'line_one' => 'Haga clic en el botón de abajo para verificar su dirección de correo electrónico.',
        'action' => 'Confirme su dirección de correo electrónico',
        'line_two' => 'Si no creó una cuenta, no es necesario realizar ninguna otra acción.',
    ],
    "reset_password" => [
        'subject' => 'Notificación de restablecimiento de contraseña',
        'line_one' => 'Recibió este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para su cuenta.',
        'action' => 'Restablecer la contraseña',
        'line_two' => 'Este enlace de restablecimiento de contraseña caducará en :count minutos.',
        'line_three' => 'Si no solicitó un restablecimiento de contraseña, no se requiere ninguna otra acción.',
    ],
    'password' => [
        'subject' => 'Contraseña',
        'password' => 'Contraseña: ',
        'paragraph' => 'Se ha registrado en nuestra plataforma, si reconoce esta actividad, no la comparta ni la cambie. Aquí está su contraseña temporal para acceder a su cuenta:',
        'footer' => 'Este es un mensaje automático, no responda.',
    ]
];
