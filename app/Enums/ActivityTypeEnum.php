<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SUCCESS()
 * @method static static ERROR()
 * @method static static WARNING()
 * @method static static PRIMARY()
 * @method static static INFO()
 */
final class ActivityTypeEnum extends Enum
{
    public const SUCCESS = "success";
    public const ERROR = "error";
    public const WARNING = "warning";
    public const PRIMARY = "primary";
    public const INFO = "info";
}
