<?php

return [
    "reset_password" => [
        'subject' => 'Notificación de restablecimiento de contraseña',
        'line_one' => 'Recibió este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para su cuenta.',
        'action' => 'Restablecer la contraseña',
        'line_two' => 'Este enlace de restablecimiento de contraseña caducará en :count minutos.',
        'line_three' => 'Si no solicitó un restablecimiento de contraseña, no se requiere ninguna otra acción.',
    ],
    'user_password' => [
        'subject' => 'Contraseña',
        'password' => 'Contraseña: ',
        'paragraph' => 'Se ha registrado en nuestra plataforma, si reconoce esta actividad, no la comparta ni la cambie. Aquí está su contraseña temporal para acceder a su cuenta:',
        'footer' => 'Este es un mensaje automático, no responda.',
    ],
    'login' => [
        'subject' => 'Intento de inicio de sesión',
        'date' => 'Data: ',
        'ip' => 'Dirección IP: ',
        'paragraph' => 'Nuevo acceso a nuestra plataforma con su correo electrónico, si no reconoce esta actividad, actualice su contraseña, desactive su cuenta y contáctenos de inmediato.',
        'footer' => 'Este es un mensaje automático, no responda.',
    ],
    'two_factor' => [
        'subject' => 'Actualización de configuración de dos factores',
        'status' => 'Estado: ',
        'active' => 'Activo',
        'deactivate' => 'Desactivado',
        'verification' => 'Verificación: ',
        'paragraph' => 'Ha actualizado la configuración de dos factores de su cuenta, si no reconoce esta actividad, actualice su contraseña, desactive su cuenta y contáctenos de inmediato.',
        'footer' => 'Este es un mensaje automático, no responda.',
    ],
    'cellphone' => [
        'subject' => 'Actualización de celular',
        'paragraph' => 'Has actualizado tu celular, si no reconoce esta actividad, actualice su contraseña, desactive su cuenta y contáctenos de inmediato.',
        'footer' => 'Este es un mensaje automático, no responda.',
    ],
    'recovery_email' => [
        'subject' => 'Actualización de correo electrónico',
        'paragraph' => 'Ha actualizado su correo electrónico, si no reconoce esta actividad, haga clic en el botón de abajo para recuperar su correo electrónico, luego active la autenticación de dos factores para asegurar su cuenta.',
        'email' => 'Tu cuenta nueva dirección de correo electrónico: ',
        'action' => 'Recuperar correo electrónico',
        'expires' => 'Este enlace de recuperación de correo electrónico caducará en :count minutos.',
        'footer' => 'Este es un mensaje automático, no responda.',
    ],
    'password' => [
        'subject' => 'Actualización de su contraseña',
        'paragraph' => 'Has actualizado su contraseña, si no reconoce esta actividad, recupere su contraseña y habilite la autenticación de dos factores.',
        'footer' => 'Este es un mensaje automático, no responda.',
    ],
];
