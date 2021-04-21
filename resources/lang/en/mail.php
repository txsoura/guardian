<?php

return [
    'two_factor_code' => [
        'subject' => 'Two-factor verification',
        'code' => 'Code: ',
        'paragraph' => 'Someone is trying to use your account, if you recognize this activity, please confirm it with the activation code. Here is your account activation code:',
        'footer' => 'This is an automated message, please do not reply.',
    ],
    "verify_email" => [
        'subject' => 'Verify Email Address',
        'line_one' => 'Please click the button below to verify your email address.',
        'action' => 'Verify Email Address',
        'line_two' => 'If you did not create an account, no further action is required.',
    ],
    "reset_password" => [
        'subject' => 'Reset Password Notification',
        'line_one' => 'You are receiving this email because we received a password reset request for your account.',
        'action' => 'Reset Password',
        'line_two' => 'This password reset link will expire in :count minutes.',
        'line_three' => 'If you did not request a password reset, no further action is required.',
    ],
    'password' => [
        'subject' => 'Password',
        'password' => 'Password: ',
        'paragraph' => 'You have been registered on our platform, if you recognize this activity, please don`t share & change it. Here is your temporary password to access your account:',
        'footer' => 'This is an automated message, please do not reply.',
    ]
];
