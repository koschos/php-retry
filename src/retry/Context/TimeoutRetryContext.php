<?php

namespace Koschos\Retry\Context;

use Koschos\Retry\Context\Util\TimeUtil;

/**
 * Class TimeoutRetryContext
 */
final class TimeoutRetryContext extends DefaultRetryContext
{
    /**
     * @var float
     */
    private $start;

    /**
     * @var int
     */
    private $timeout;

    /**
     * TimeoutRetryContext constructor.
     * @param int $timeout
     */
    public function __construct($timeout)
    {
        $this->start = TimeUtil::milliseconds();
        $this->timeout = $timeout;
    }

    public function isAlive(): bool
    {
        return (TimeUtil::milliseconds() - $this->start) <= $this->timeout;
    }
}
