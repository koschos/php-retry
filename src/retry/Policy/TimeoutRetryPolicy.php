<?php

namespace Koschos\Retry\Policy;

use Koschos\Retry\Context\DefaultRetryContext;
use Koschos\Retry\Context\TimeoutRetryContext;
use Koschos\Retry\RetryContext;
use Koschos\Retry\RetryPolicy;

/**
 * Class TimeoutRetryPolicy
 */
final class TimeoutRetryPolicy implements RetryPolicy
{
    const DEFAULT_TIMEOUT = 1000;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = (int) $timeout;
    }

    public function canRetry(RetryContext $context): bool
    {
        if ($context instanceof TimeoutRetryContext) {
            return $context->isAlive();
        }

        return false;
    }

    public function open(): RetryContext
    {
        return new TimeoutRetryContext($this->timeout);
    }

    public function close(RetryContext $context): void
    {
    }

    public function registerException(RetryContext $context, \Exception $exception): void
    {
        if ($context instanceof DefaultRetryContext) {
            $context->registerException($exception);
        }
    }

}