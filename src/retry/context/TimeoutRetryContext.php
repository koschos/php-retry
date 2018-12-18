<?php

namespace Koschos\retry\context;

/**
 * Class TimeoutRetryContext
 */
class TimeoutRetryContext extends DefaultRetryContext
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
        $this->start = microtime(true);
        $this->timeout = $timeout;
    }

    /**
     * @return bool
     */
    public function isAlive()
    {
        return (microtime(true) - $this->start) <= $this->timeout;
    }
}
