<?php

namespace Koschos\Retry\Context\Util;

/**
 * Class Time
 */
class TimeUtil
{
    /**
     * @return string
     */
    public static function milliseconds()
    {
        $microTimeParts = explode(' ', microtime());

        return sprintf('%d%03d', $microTimeParts[1], $microTimeParts[0] * 1000);
    }
}