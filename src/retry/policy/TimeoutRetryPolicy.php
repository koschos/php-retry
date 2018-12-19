<?php

namespace Koschos\Retry\Policy;

use Koschos\Retry\Context\DefaultRetryContext;
use Koschos\Retry\Context\TimeoutRetryContext;
use Koschos\Retry\RetryContext;
use Koschos\Retry\RetryPolicy;

/**
 * Class TimeoutRetryPolicy
 */
class TimeoutRetryPolicy implements RetryPolicy
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

    /**
     * @inheritdoc
     */
    public function canRetry(RetryContext $context)
    {
        if ($context instanceof TimeoutRetryContext) {
            return $context->isAlive();
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function open()
    {
        return new TimeoutRetryContext($this->timeout);
    }

    /**
     * @inheritdoc
     */
    public function close(RetryContext $context)
    {
    }

    /**
     * @inheritdoc
     */
    public function registerException(RetryContext $context, \Exception $exception)
    {
        if ($context instanceof DefaultRetryContext) {
            $context->registerException($context);
        }
    }

}