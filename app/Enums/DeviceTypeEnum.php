<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static CARD()
 * @method static static TAG()
 */
final class DeviceTypeEnum extends Enum
{
    public const CARD = "card";
    public const TAG = "tag";
}
