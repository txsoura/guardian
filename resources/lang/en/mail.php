<?php

return [
    "reset_password" => [
        'subject' => 'Reset Password Notification',
        'line_one' => 'You are receiving this email because we received a password reset request for your account.',
        'action' => 'Reset Password',
        'line_two' => 'This password reset link will expire in :count minutes.',
        'line_three' => 'If you did not request a password reset, no further action is required.',
    ],
    'user_password' => [
        'subject' => 'Password',
        'password' => 'Password: ',
        'paragraph' => 'You have been registered on our platform, if you recognize this activity, please don`t share & change it. Here is your temporary password to access your account:',
        'footer' => 'This is an automated message, please do not reply.',
    ],
    'login' => [
        'subject' => 'Login Attempt',
        'date' => 'Date: ',
        'ip' => 'IP Address: ',
        'paragraph' => 'New access to our platform with your email, if you don`t recognize this activity, please update your password, disable your account and contact us immediately.',
        'footer' => 'This is an automated message, please do not reply.',
    ],
    'two_factor' => [
        'subject' => 'Two Factor Setting Update',
        'status' => 'Status: ',
        'active' => 'Active',
        'deactivate' => 'Deactivate',
        'verification' => 'Verification: ',
        'paragraph' => 'You have updated your account two factor setting, if you don`t recognize this activity, please update your password, disable your account and contact us immediately.',
        'footer' => 'This is an automated message, please do not reply.',
    ],
    'cellphone' => [
        'subject' => 'Cellphone Update',
        'paragraph' => 'You have updated your cellphone, if you don`t recognize this activity, please update your password, disable your account and contact us immediately.',
        'footer' => 'This is an automated message, please do not reply.',
    ],
    'recovery_email' => [
        'subject' => 'Email Update',
        'paragraph' => 'You have updated your email, if you don`t recognize this activity, click the button below to recover your email, after that activate two factor authentication to secure your account.',
        'email' => 'Your account new email: ',
        'action' => 'Recover Email',
        'expires' => 'This email recovery link will expire in :count minutes.',
        'footer' => 'This is an automated message, please do not reply.',
    ],
    'password' => [
        'subject' => 'Password Update',
        'paragraph' => 'You have updated your password, if you don`t recognize this activity, please recovery your password and activate two factor authentication.',
        'footer' => 'This is an automated message, please do not reply.',
    ],
];
