<?php

namespace Koschos\Retry\Context;

use Koschos\Retry\RetryContext;

/**
 * Class DefaultRetryContext
 */
class DefaultRetryContext implements RetryContext
{
    /**
     * @var int
     */
    private $count = 0;

    /**
     * @var \Exception
     */
    private $lastException;

    /**
     * @return int
     */
    public function getRetryCount(): int
    {
        return $this->count;
    }

    /**
     * @return \Exception
     */
    public function getLastException()
    {
        return $this->lastException;
    }

    /**
     * @param \Exception|null $exception
     */
    public function registerException(\Exception $exception = null)
    {
        $this->lastException = $exception;

        if ($this->lastException !== null) {
            $this->count++;
        }
    }
}
