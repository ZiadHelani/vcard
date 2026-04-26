<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PERSONAL()
 * @method static static BUSINESS()
 */
final class CardTypeEnum extends Enum
{
    public const PERSONAL = "personal";
    public const BUSINESS = "business";
}
