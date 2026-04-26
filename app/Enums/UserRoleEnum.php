<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static USER()
 * @method static static ADMIN()
 */
final class UserRoleEnum extends Enum
{
    const USER = 'user';
    const ADMIN = 'admin';
}
