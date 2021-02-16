<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method Two factor provider enum
 */
final class TwoFactorProvider extends Enum
{
    const SMS =  'sms';
    const MAIL =   'mail';
}
