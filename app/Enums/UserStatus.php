<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 *  user status enum
 */
final class UserStatus extends Enum
{
    const APPROVED = 'approved';
    const PENDENT = 'pendent';
    const BLOCKED = 'blocked';
}
