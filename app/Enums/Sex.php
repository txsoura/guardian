<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method sex enum
 */
final class Sex extends Enum
{
    const MALE =  'male';
    const FEMALE =   'female';
    const OTHER = 'other';
}
