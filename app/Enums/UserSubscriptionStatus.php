<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ACTIVE()
 * @method static static EXPIRED()
 * @method static static CANCELLED()
 * @method static static PENDING()
 */
final class UserSubscriptionStatus extends Enum
{
    public const ACTIVE = "active";
    public const EXPIRED = "expired";
    public const CANCELLED = "cancelled";
    public const PENDING = "pending";
}
