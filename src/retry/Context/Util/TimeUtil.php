<?php

namespace Koschos\Retry\Context\Util;

/**
 * Class Time
 */
class TimeUtil
{
    public static function milliseconds(): int
    {
        $microTimeParts = explode(' ', microtime());

        return (int) sprintf('%d%03d', $microTimeParts[1], $microTimeParts[0] * 1000);
    }
}