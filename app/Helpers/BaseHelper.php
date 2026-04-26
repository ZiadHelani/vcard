<?php

use Random\RandomException;

if (!defined('PAGINATE_LIMIT')) {
    define('PAGINATE_LIMIT', 15);
}


if (!function_exists('generateOtpCode')) {
    /**
     * @throws RandomException
     */
    function generateOtpCode(int $length = 4): int
    {
        return random_int(1000, 9999);
    }
}
