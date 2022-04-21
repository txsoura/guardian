<?php

use PragmaRX\Google2FALaravel\Support\Constants;

return [

    /*
     * Enable / disable Google2FA.
     */
    'enabled' => env('OTP_ENABLED', true),

    /*
     * Lifetime in minutes.
     *
     * In case you need your users to be asked for a new one time passwords from time to time.
     */
    'lifetime' => env('OTP_LIFETIME', 1), // 0 = eternal

    /*
     * Renew lifetime at every new request.
     */
    'keep_alive' => env('OTP_KEEP_ALIVE', true),

    /*
     * Auth container binding.
     */
    'auth' => 'auth',

    /*
     * Guard.
     */
    'guard' => 'api',

    /*
     * 2FA verified session var.
     */
    'session_var' => 'google2fa',

    /*
     * One Time Password request input name.
     */
    'otp_input' => 'code',

    /*
     * One Time Password Window.
     */
    'window' => 0,

    /*
     * Forbid user to reuse One Time Passwords.
     */
    'forbid_old_passwords' => true,

    /*
     * User's table column for google2fa secret.
     */
    'otp_secret_column' => 'totp_secret',

    /*
     * One Time Password View.
     */
    'view' => 'google2fa.index',

    /*
     * One Time Password error message.
     */
    'error_messages' => [
        'wrong_otp' => "The 'One Time Password' typed was wrong.",
        'cannot_be_empty' => 'One Time Password cannot be empty.',
        'unknown' => 'An unknown error has occurred. Please try again.',
    ],

    /*
     * Throw exceptions or just fire events?
     */
    'throw_exceptions' => env('OTP_THROW_EXCEPTION', true),

    /*
     * Which image backend to use for generating QR codes?
     *
     * Supports imagemagick, svg and eps
     */
    'qrcode_image_backend' => Constants::QRCODE_IMAGE_BACKEND_SVG,

];
