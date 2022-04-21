<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 *  Two factor provider enum
 */
final class TwoFactorProvider extends Enum
{
    const SMS = 'sms';
    const MAIL = 'mail';
    const GOOGLE_AUTHENTICATOR = 'google_authenticator';
}
