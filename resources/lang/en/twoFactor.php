<?php

return [
    'enable' => [
        'message' => 'Cannot enable two-factor authentication',
        'mail' => [
            'error' => 'Please verify your email to enable two-factor authentication',
        ],
        'sms' => [
            'error' => 'Please verify your cellphone to enable two-factor authentication',
        ],
    ],
    'recovery' => [
        'message' => 'Cannot recovery two-factor authentication',
        'error' => 'Invalid recovery code',
    ],
    'verify' => [
        'message' => 'Cannot verify two-factor authentication',
        'error' => 'Invalid verification code',
    ],
    'verified' => 'Two-factor authentication verified'
];
