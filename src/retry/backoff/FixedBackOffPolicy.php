<?php

namespace Koschos\Retry\Backoff;

use Koschos\Retry\RetryContext;

/**
 * Class FixedBackOffPolicy
 */
final class FixedBackOffPolicy implements BackOffPolicy
{
    /**
     * Period in milliseconds between retries.
     *
     * @var int
     */
    private $backOffPeriod = 1000;

    /**
     * @param RetryContext $context
     */
    public function start(RetryContext $context)
    {
    }

    /**
     * @inheritdoc
     */
    public function backOff(RetryContext $context)
    {
        usleep($this->backOffPeriod * 1000);
    }

    /**
     * @return int
     */
    public function getBackOffPeriod()
    {
        return $this->backOffPeriod;
    }

    /**
     * @param int $backOffPeriod
     */
    public function setBackOffPeriod($backOffPeriod)
    {
        $this->backOffPeriod = $backOffPeriod > 0 ? $backOffPeriod : 1;
    }
}
