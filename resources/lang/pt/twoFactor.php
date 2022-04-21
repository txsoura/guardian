<?php

return [
    'enable' => [
        'message' => 'Não é possível ativar a autenticação de dois fatores',
        'mail' => [
            'error' => 'Verifique o seu email para habilitar a autenticação de dois fatores',
        ],
        'sms' => [
            'error' => 'Por favor, verifique seu celular para habilitar a autenticação de dois fatores',
        ],
    ],
    'recovery' => [
        'message' => 'Não é possível recuperar a autenticação de dois fatores',
        'error' => 'Código de recuperação inválido',
    ],
    'verify' => [
        'message' => 'Não é possível verificar a autenticação de dois fatores',
        'error' => 'Código de verificação inválido',
    ],
    'verified' => 'Autenticação de dois fatores verificada',
    'two_factor_resend_failed' => 'Reenvio de dois fatores falhou',
    'two_factor_recovery_failed' => 'A recuperação de dois fatores falhou'
];
